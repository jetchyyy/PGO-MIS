<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisposalStoreRequest;
use App\Models\Disposal;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\InventoryItem;
use App\Models\PrintLog;
use App\Models\PropertyTransactionLine;
use App\Support\AuditLogger;
use App\Support\DisposalDepreciation;
use App\Support\DocumentControlRegistry;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DisposalController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('disposal.manage');

        $documentTab = strtolower($request->string('doc', 'all')->toString());

        $disposals = Disposal::query()
            ->with(['employee', 'documentControls'])
            ->when(in_array($documentTab, ['iirup', 'iirusp', 'rrsep'], true), fn ($q) => $q->where('document_type', strtoupper($documentTab)))
            ->when($documentTab === 'wmr', fn ($q) => $q->whereIn('status', ['approved', 'issued']))
            ->latest('id')
            ->paginate(20);

        return view('disposal.index', compact('disposals', 'documentTab'));
    }

    public function create(): View
    {
        $this->authorize('disposal.manage');

        return view('disposal.create', [
            'employees' => Employee::orderBy('name')->get(),
            'fundClusters' => FundCluster::orderBy('code')->get(),
        ]);
    }

    public function store(DisposalStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $documentType = Disposal::resolveDocumentType($validated['lines']);

        $disposal = DB::transaction(function () use ($validated, $request, $documentType) {
            $disposal = Disposal::create([
                'entity_name' => $validated['entity_name'],
                'employee_id' => $validated['employee_id'],
                'designation' => $validated['designation'] ?? null,
                'station' => $validated['station'] ?? null,
                'fund_cluster_id' => $validated['fund_cluster_id'],
                'disposal_date' => $validated['disposal_date'],
                'disposal_type' => $this->legacyDisposalType($validated['disposal_method']),
                'disposal_type_other' => $validated['disposal_method'] === 'others' ? ($validated['disposal_method_other'] ?? null) : null,
                'item_disposal_condition' => $validated['item_disposal_condition'],
                'item_disposal_condition_other' => $validated['item_disposal_condition_other'] ?? null,
                'or_no' => $validated['or_no'] ?? null,
                'sale_amount' => $validated['sale_amount'] ?? null,
                'appraised_value' => $validated['appraised_value'] ?? null,
                'disposal_method' => $validated['disposal_method'],
                'disposal_method_other' => $validated['disposal_method_other'] ?? null,
                'document_type' => $documentType,
                'control_no' => 'TMP',
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $disposal->update(['control_no' => NumberGenerator::next($disposal->document_type, $validated['disposal_date'])]);

            foreach ($validated['lines'] as $line) {
                $inventory = null;
                $requestedQty = max(1, (int) ($line['quantity'] ?? 1));
                if (!empty($line['inventory_item_id'])) {
                    $inventory = InventoryItem::findOrFail((int) $line['inventory_item_id']);
                    if ($inventory->status !== 'issued') {
                        throw ValidationException::withMessages([
                            'inventory' => 'Selected inventory item is not currently issued.',
                        ]);
                    }
                    if ((int) $inventory->current_employee_id !== (int) $validated['employee_id']) {
                        throw ValidationException::withMessages([
                            'inventory' => 'Selected item does not belong to the selected accountable officer.',
                        ]);
                    }
                    if ((int) $inventory->fund_cluster_id !== (int) $validated['fund_cluster_id']) {
                        throw ValidationException::withMessages([
                            'fund_cluster_id' => 'Selected item does not belong to the chosen fund cluster.',
                        ]);
                    }

                    if ($requestedQty > 1 && $inventory->property_transaction_line_id) {
                        $availableQty = InventoryItem::query()
                            ->where('status', 'issued')
                            ->where('property_transaction_line_id', (int) $inventory->property_transaction_line_id)
                            ->where('current_employee_id', (int) $validated['employee_id'])
                            ->count();

                        if ($availableQty < $requestedQty) {
                            throw ValidationException::withMessages([
                                'inventory' => "Insufficient quantity with selected accountable officer. Requested {$requestedQty}, available {$availableQty}.",
                            ]);
                        }

                        $line['property_transaction_line_id'] = $inventory->property_transaction_line_id;
                        $line['item_id'] = $inventory->item_id;
                        $inventory = null;
                    }
                }

                if (!$inventory && !empty($line['property_transaction_line_id'])) {
                    $source = PropertyTransactionLine::with('transaction')->findOrFail($line['property_transaction_line_id']);
                    if ($source->item_status === 'disposed') {
                        throw ValidationException::withMessages([
                            'inventory' => 'Item already disposed.',
                        ]);
                    }
                    if (!in_array($source->transaction->status, ['approved', 'issued'], true)) {
                        throw ValidationException::withMessages([
                            'inventory' => 'Cannot dispose unissued items.',
                        ]);
                    }

                    $availableQty = InventoryItem::query()
                        ->where('status', 'issued')
                        ->where('property_transaction_line_id', (int) $line['property_transaction_line_id'])
                        ->where('current_employee_id', (int) $validated['employee_id'])
                        ->count();
                    if ($availableQty < $requestedQty) {
                        throw ValidationException::withMessages([
                            'inventory' => "Insufficient quantity with selected accountable officer. Requested {$requestedQty}, available {$availableQty}.",
                        ]);
                    }
                }

                $qty = $inventory ? 1 : $requestedQty;
                $unitCost = $inventory ? (float) $inventory->unit_cost : (float) ($line['unit_cost'] ?? 0);
                $total = $qty * $unitCost;
                $useFormulaDepreciation = array_key_exists('use_formula_depreciation', $line)
                    ? filter_var($line['use_formula_depreciation'], FILTER_VALIDATE_BOOL)
                    : true;
                $formulaDepreciation = DisposalDepreciation::calculate(
                    $inventory?->date_acquired?->toDateString() ?? ($line['date_acquired'] ?? null),
                    $validated['disposal_date'],
                    $total
                );
                $depr = $useFormulaDepreciation
                    ? $formulaDepreciation
                    : min($total, max(0, (float) ($line['accumulated_depreciation'] ?? 0)));
                $appraisedValue = (float) ($line['appraised_value'] ?? ($validated['appraised_value'] ?? $total));
                $carryingAmount = max(0, round($total - $depr, 2));

                $disposal->lines()->create([
                    'item_id' => $inventory?->item_id ?? ($line['item_id'] ?? null),
                    'inventory_item_id' => $inventory?->id ?? null,
                    'property_transaction_line_id' => $inventory?->property_transaction_line_id ?? ($line['property_transaction_line_id'] ?? null),
                    'date_acquired' => $inventory?->date_acquired ?? ($line['date_acquired'] ?? null),
                    'particulars' => $inventory?->description ?? ($line['particulars'] ?? ''),
                    'property_no' => $inventory?->property_no ?? ($line['property_no'] ?? null),
                    'quantity' => $qty,
                    'unit' => $inventory?->unit ?? ($line['unit'] ?? null),
                    'unit_cost' => $unitCost,
                    'total_cost' => $total,
                    'appraised_value' => $appraisedValue,
                    'accumulated_depreciation' => $depr,
                    'carrying_amount' => $carryingAmount,
                    'remarks' => $line['remarks'] ?? null,
                ]);
            }

            AuditLogger::log($request->user()->id, 'disposal.created', $disposal, [], $request->ip(), $request->userAgent());

            return $disposal;
        });

        return redirect()->route('disposal.show', $disposal)
            ->with('status', "Disposal draft created. Document type: {$documentType}.");
    }

    private function legacyDisposalType(string $disposalMethod): string
    {
        return match ($disposalMethod) {
            'public_auction' => 'sale',
            'destruction' => 'destruction',
            default => 'others',
        };
    }

    public function show(Disposal $disposal): View
    {
        $this->authorize('disposal.manage');

        $disposal->load(['lines', 'employee', 'fundCluster', 'approvals', 'documentControls']);
        $generatedDocuments = in_array($disposal->status, ['approved', 'issued'], true)
            ? DocumentControlRegistry::listFor($disposal)
            : [];

        return view('disposal.show', compact('disposal', 'generatedDocuments'));
    }

    public function submit(Disposal $disposal, Request $request): RedirectResponse
    {
        $this->authorize('disposal.manage');
        abort_if($disposal->status !== 'draft', 422);

        $disposal->update(['status' => 'submitted', 'submitted_at' => now()]);
        $disposal->approvals()->create(['status' => 'pending']);

        AuditLogger::log($request->user()->id, 'disposal.submitted', $disposal, [], $request->ip(), $request->userAgent());

        return back()->with('status', 'Disposal submitted for approval.');
    }

    public function print(Disposal $disposal, string $template, Request $request)
    {
        $this->authorize('disposal.manage');
        abort_unless(in_array($template, ['iirup', 'iirusp', 'rrsep', 'wmr'], true), 404);
        abort_unless(in_array($disposal->status, ['approved', 'issued'], true), 422);

        $version = (int) PrintLog::where('printable_type', Disposal::class)
            ->where('printable_id', $disposal->id)
            ->where('template_name', $template)
            ->count() + 1;

        PrintLog::create([
            'printable_type' => Disposal::class,
            'printable_id' => $disposal->id,
            'template_name' => $template,
            'version' => $version,
            'printed_by' => $request->user()->id,
            'printed_at' => now(),
        ]);

        $disposal->update(['status' => 'issued']);

        AuditLogger::log($request->user()->id, 'disposal.printed', $disposal, ['template' => $template, 'version' => $version], $request->ip(), $request->userAgent());

        $disposal->load(['lines', 'employee', 'fundCluster']);
        $document = DocumentControlRegistry::findFor($disposal->loadMissing('documentControls'), $template);

        $sig = \App\Models\Signatory::where('is_active', true)->get()->keyBy('role_key');

        $orientation = in_array($template, ['iirup', 'iirusp'], true) ? 'landscape' : 'portrait';

        return Pdf::loadView('disposal.pdf.'.$template, compact('disposal', 'version', 'sig') + [
            'documentControlNo' => $document?->control_no,
        ])
            ->setPaper('a4', $orientation)
            ->stream($template.'-'.($document?->control_no ?? $disposal->control_no).'.pdf');
    }
}
