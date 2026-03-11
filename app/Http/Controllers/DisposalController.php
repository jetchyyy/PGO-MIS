<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisposalStoreRequest;
use App\Models\Disposal;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\InventoryItem;
use App\Models\PrintLog;
use App\Models\PropertyReturn;
use App\Models\PropertyTransactionLine;
use App\Support\AuditLogger;
use App\Support\DisposalDepreciation;
use App\Support\DocumentControlRegistry;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DisposalController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('disposal.manage');

        $documentTab = strtolower($request->string('doc', 'all')->toString());

        $allowedTabs = ['iirup', 'iirusp', 'rrsep'];

        $disposals = Disposal::query()
            ->with(['employee', 'documentControls'])
            ->when(in_array($documentTab, $allowedTabs, true), fn ($q) => $q->where('document_type', strtoupper($documentTab)))
            ->when($documentTab === 'wmr', fn ($q) => $q->whereIn('status', ['approved', 'issued']))
            ->latest('id')
            ->paginate(20);

        return view('disposal.index', compact('disposals', 'documentTab'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $this->authorize('disposal.manage');

        $selectedReturnId = $request->integer('return_id') ?: null;
        if ($selectedReturnId) {
            $selectedReturn = PropertyReturn::query()
                ->with('disposal')
                ->findOrFail($selectedReturnId);

            if ($selectedReturn->disposal !== null) {
                return redirect()
                    ->route('disposal.show', $selectedReturn->disposal)
                    ->with('status', 'A disposal draft already exists for this return record.');
            }
        }

        return view('disposal.create', [
            'approvedReturns' => PropertyReturn::query()
                ->with(['employee', 'fundCluster', 'lines', 'disposal'])
                ->whereIn('status', ['approved', 'issued'])
                ->latest('id')
                ->get()
                ->filter(fn (PropertyReturn $return): bool => $return->disposal === null)
                ->values(),
            'selectedReturnId' => $selectedReturnId,
        ]);
    }

    public function store(DisposalStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $propertyReturn = PropertyReturn::with(['lines', 'employee', 'fundCluster', 'disposal'])
            ->findOrFail((int) $validated['property_return_id']);

        if (! in_array($propertyReturn->status, ['approved', 'issued'], true)) {
            throw ValidationException::withMessages([
                'property_return_id' => 'Only approved return records can proceed to disposal.',
            ]);
        }

        if ($propertyReturn->disposal !== null) {
            return redirect()
                ->route('disposal.show', $propertyReturn->disposal)
                ->with('status', 'A disposal draft already exists for this return record.');
        }

        try {
            $documentType = Disposal::resolveDocumentType($propertyReturn->lines->all());
        } catch (InvalidArgumentException $e) {
            throw ValidationException::withMessages([
                'property_return_id' => $e->getMessage(),
            ]);
        }

        $disposal = DB::transaction(function () use ($validated, $request, $documentType, $propertyReturn) {
            $disposal = Disposal::create([
                'entity_name' => $propertyReturn->entity_name,
                'employee_id' => $propertyReturn->employee_id,
                'designation' => $propertyReturn->designation,
                'station' => $propertyReturn->station,
                'fund_cluster_id' => $propertyReturn->fund_cluster_id,
                'property_return_id' => $propertyReturn->id,
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
                'prerequisite_form_type' => $propertyReturn->document_type,
                'prerequisite_form_no' => $propertyReturn->control_no,
                'prerequisite_form_date' => $propertyReturn->return_date,
                'control_no' => 'TMP',
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $disposal->update(['control_no' => NumberGenerator::next($disposal->document_type, $validated['disposal_date'])]);

            foreach ($propertyReturn->lines as $line) {
                $qty = (int) $line->quantity;
                $unitCost = (float) $line->unit_cost;
                $total = $qty * $unitCost;
                $formulaDepreciation = DisposalDepreciation::calculate(
                    $line->date_acquired?->toDateString(),
                    $validated['disposal_date'],
                    $total
                );
                $depr = $formulaDepreciation;
                $appraisedValue = (float) ($validated['appraised_value'] ?? $total);
                $carryingAmount = max(0, round($total - $depr, 2));

                $disposal->lines()->create([
                    'item_id' => $line->item_id,
                    'inventory_item_id' => $line->inventory_item_id,
                    'property_transaction_line_id' => $line->property_transaction_line_id,
                    'date_acquired' => $line->date_acquired,
                    'particulars' => $line->particulars,
                    'property_no' => $line->property_no,
                    'quantity' => $qty,
                    'unit' => $line->unit,
                    'unit_cost' => $unitCost,
                    'total_cost' => $total,
                    'appraised_value' => $appraisedValue,
                    'accumulated_depreciation' => $depr,
                    'carrying_amount' => $carryingAmount,
                    'remarks' => $line->remarks,
                ]);
            }

            AuditLogger::log($request->user()->id, 'disposal.created', $disposal, [], $request->ip(), $request->userAgent());

            return $disposal;
        });

        return redirect()->route('disposal.show', $disposal)
            ->with('status', 'Disposal draft created.');
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
        $disposal->loadMissing('propertyReturn');
        $generatedDocuments = in_array($disposal->status, ['approved', 'issued'], true)
            ? DocumentControlRegistry::listFor($disposal)
            : [];

        return view('disposal.show', compact('disposal', 'generatedDocuments'));
    }

    public function submit(Disposal $disposal, Request $request): RedirectResponse
    {
        $this->authorize('disposal.manage');
        $wasReturned = $disposal->status === 'returned';
        abort_if(! in_array($disposal->status, ['draft', 'returned'], true), 422);

        $disposal->update(['status' => 'submitted', 'submitted_at' => now()]);
        $disposal->approvals()->create(['status' => 'pending']);

        AuditLogger::log($request->user()->id, 'disposal.submitted', $disposal, [], $request->ip(), $request->userAgent());

        return back()->with('status', $wasReturned ? 'Disposal resubmitted for approval.' : 'Disposal submitted for approval.');
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
