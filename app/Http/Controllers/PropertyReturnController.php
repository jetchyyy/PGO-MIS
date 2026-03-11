<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyReturnStoreRequest;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\InventoryItem;
use App\Models\PrintLog;
use App\Models\PropertyReturn;
use App\Models\PropertyTransaction;
use App\Models\PropertyTransactionLine;
use App\Models\Signatory;
use App\Support\AuditLogger;
use App\Support\DocumentControlRegistry;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use InvalidArgumentException;

class PropertyReturnController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('return.manage');

        $documentTab = strtolower($request->string('doc', 'all')->toString());

        $returns = PropertyReturn::query()
            ->with(['employee', 'documentControls', 'disposal'])
            ->when(in_array($documentTab, ['prs', 'rrsp'], true), fn ($q) => $q->where('document_type', strtoupper($documentTab)))
            ->latest('id')
            ->paginate(20);

        return view('returns.index', compact('returns', 'documentTab'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $this->authorize('return.manage');

        $prefill = null;
        $issuanceId = (int) $request->input('issuance_id');
        if ($issuanceId > 0) {
            $issuance = PropertyTransaction::with(['lines', 'employee', 'fundCluster'])
                ->findOrFail($issuanceId);

            $lineIds = $issuance->lines->pluck('id')->all();
            $inventoryRows = InventoryItem::query()
                ->whereIn('property_transaction_line_id', $lineIds)
                ->where('status', 'issued')
                ->where('current_employee_id', $issuance->employee_id)
                ->selectRaw('property_transaction_line_id, COUNT(*) as qty')
                ->groupBy('property_transaction_line_id')
                ->pluck('qty', 'property_transaction_line_id');

            $lines = $issuance->lines
                ->map(function (PropertyTransactionLine $line) use ($inventoryRows): array {
                    $quantity = (int) ($inventoryRows[$line->id] ?? 0);
                    return [
                        'property_transaction_line_id' => (int) $line->id,
                        'quantity' => $quantity,
                        'available_quantity' => $quantity,
                        'particulars' => (string) ($line->description ?? ''),
                        'property_no' => (string) ($line->property_no ?? ''),
                        'date_acquired' => optional($line->date_acquired)->toDateString(),
                        'unit' => (string) ($line->unit ?? ''),
                        'unit_cost' => (float) ($line->unit_cost ?? 0),
                        'condition' => 'Functional',
                        'remarks' => '',
                    ];
                })
                ->filter(fn (array $line): bool => $line['quantity'] > 0)
                ->values()
                ->all();

            if (empty($lines)) {
                return redirect()
                    ->route('issuance.show', $issuance)
                    ->with('status', 'No issued items are currently available for return from this issuance.');
            }

            $prefill = [
                'entity_name' => $issuance->entity_name,
                'employee_id' => (string) $issuance->employee_id,
                'designation' => (string) ($issuance->employee->designation ?? ''),
                'station' => (string) ($issuance->employee->station ?? ''),
                'fund_cluster_id' => (string) $issuance->fund_cluster_id,
                'return_date' => now()->toDateString(),
                'lines' => $lines,
            ];
        }

        return view('returns.create', [
            'employees' => Employee::orderBy('name')->get(),
            'fundClusters' => FundCluster::orderBy('code')->get(),
            'prefill' => $prefill,
            'selectedIssuanceId' => $issuanceId > 0 ? (string) $issuanceId : '',
        ]);
    }

    public function store(PropertyReturnStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $classificationLines = collect($validated['lines'])->map(function (array $line): array {
            if (! empty($line['inventory_item_id'])) {
                $inventory = InventoryItem::findOrFail((int) $line['inventory_item_id']);
                $line['unit_cost'] = (float) $inventory->unit_cost;
            }

            return $line;
        })->all();

        try {
            $documentType = PropertyReturn::resolveDocumentType($classificationLines);
        } catch (InvalidArgumentException $e) {
            throw ValidationException::withMessages([
                'lines' => $e->getMessage(),
            ]);
        }

        $returnRecord = DB::transaction(function () use ($validated, $request, $documentType) {
            $return = PropertyReturn::create([
                'entity_name' => $validated['entity_name'],
                'employee_id' => $validated['employee_id'],
                'designation' => $validated['designation'] ?? null,
                'station' => $validated['station'] ?? null,
                'fund_cluster_id' => $validated['fund_cluster_id'],
                'return_date' => $validated['return_date'],
                'return_reason' => $validated['return_reason'] ?? null,
                'document_type' => $documentType,
                'control_no' => 'TMP',
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $return->update([
                'control_no' => NumberGenerator::next($documentType, $validated['return_date']),
            ]);

            foreach ($validated['lines'] as $line) {
                $inventory = null;
                $requestedQty = max(1, (int) ($line['quantity'] ?? 1));

                if (! empty($line['inventory_item_id'])) {
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

                if (! $inventory && ! empty($line['property_transaction_line_id'])) {
                    $source = PropertyTransactionLine::with('transaction')->findOrFail($line['property_transaction_line_id']);
                    if ($source->item_status === 'disposed') {
                        throw ValidationException::withMessages([
                            'inventory' => 'Item already disposed.',
                        ]);
                    }
                    if (! in_array($source->transaction->status, ['approved', 'issued'], true)) {
                        throw ValidationException::withMessages([
                            'inventory' => 'Cannot return unissued items.',
                        ]);
                    }
                }

                $qty = $inventory ? 1 : $requestedQty;
                $unitCost = $inventory ? (float) $inventory->unit_cost : (float) ($line['unit_cost'] ?? 0);

                $return->lines()->create([
                    'item_id' => $inventory?->item_id ?? ($line['item_id'] ?? null),
                    'inventory_item_id' => $inventory?->id ?? null,
                    'property_transaction_line_id' => $inventory?->property_transaction_line_id ?? ($line['property_transaction_line_id'] ?? null),
                    'date_acquired' => $inventory?->date_acquired ?? ($line['date_acquired'] ?? null),
                    'particulars' => $inventory?->description ?? ($line['particulars'] ?? ''),
                    'property_no' => $inventory?->property_no ?? ($line['property_no'] ?? null),
                    'quantity' => $qty,
                    'unit' => $inventory?->unit ?? ($line['unit'] ?? null),
                    'unit_cost' => $unitCost,
                    'total_cost' => $qty * $unitCost,
                    'condition' => $line['condition'] ?? null,
                    'remarks' => $line['remarks'] ?? null,
                ]);
            }

            AuditLogger::log($request->user()->id, 'return.created', $return, [], $request->ip(), $request->userAgent());

            return $return;
        });

        return redirect()->route('returns.show', $returnRecord)->with('status', 'Return draft created.');
    }

    public function show(PropertyReturn $return): View
    {
        $this->authorize('return.manage');

        $return->load(['lines', 'employee', 'fundCluster', 'approvals', 'documentControls', 'disposal']);
        $generatedDocuments = in_array($return->status, ['approved', 'issued'], true)
            ? DocumentControlRegistry::listFor($return)
            : [];

        return view('returns.show', [
            'returnRecord' => $return,
            'generatedDocuments' => $generatedDocuments,
        ]);
    }

    public function submit(PropertyReturn $return, Request $request): RedirectResponse
    {
        $this->authorize('return.manage');
        $wasReturned = $return->status === 'returned';
        abort_if(! in_array($return->status, ['draft', 'returned'], true), 422, 'Only draft or returned returns can be submitted.');

        DB::transaction(function () use ($return, $request): void {
            $return->update(['status' => 'submitted', 'submitted_at' => now()]);
            $return->approvals()->create(['status' => 'pending']);
            AuditLogger::log($request->user()->id, 'return.submitted', $return, [], $request->ip(), $request->userAgent());
        });

        return back()->with('status', $wasReturned ? 'Return resubmitted for approval.' : 'Return submitted for approval.');
    }

    public function print(PropertyReturn $return, string $template, Request $request)
    {
        $this->authorize('return.manage');
        abort_unless(in_array($template, ['prs', 'rrsp'], true), 404);
        abort_unless(in_array($return->status, ['approved', 'issued'], true), 422, 'Only approved returns can be printed.');

        $version = (int) PrintLog::where('printable_type', PropertyReturn::class)
            ->where('printable_id', $return->id)
            ->where('template_name', $template)
            ->count() + 1;

        PrintLog::create([
            'printable_type' => PropertyReturn::class,
            'printable_id' => $return->id,
            'template_name' => $template,
            'version' => $version,
            'printed_by' => $request->user()->id,
            'printed_at' => now(),
        ]);

        $return->update(['status' => 'issued']);

        AuditLogger::log($request->user()->id, 'return.printed', $return, ['template' => $template, 'version' => $version], $request->ip(), $request->userAgent());

        $return->load(['lines', 'employee', 'fundCluster']);
        $document = DocumentControlRegistry::findFor($return->loadMissing('documentControls'), $template);
        $sig = Signatory::where('is_active', true)->get()->keyBy('role_key');

        return Pdf::loadView('returns.pdf.'.$template, [
            'returnRecord' => $return,
            'version' => $version,
            'sig' => $sig,
            'documentControlNo' => $document?->control_no,
        ])->setPaper('a4', 'portrait')
            ->stream($template.'-'.($document?->control_no ?? $return->control_no).'.pdf');
    }
}
