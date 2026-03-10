<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssuanceStoreRequest;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PrintLog;
use App\Models\PropertyTransaction;
use App\Models\InventoryItem;
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

class IssuanceController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('issuance.manage');

        $documentTab = strtolower($request->string('doc', 'all')->toString());

        $issuances = PropertyTransaction::query()
            ->with(['office', 'employee', 'fundCluster', 'documentControls'])
            ->when($documentTab === 'par', fn ($q) => $q->where('document_type', 'PAR'))
            ->when($documentTab === 'ics', fn ($q) => $q->whereIn('document_type', ['ICS-SPLV', 'ICS-SPHV']))
            ->when($documentTab === 'splv', fn ($q) => $q->where('document_type', 'ICS-SPLV'))
            ->when($documentTab === 'sphv', fn ($q) => $q->where('document_type', 'ICS-SPHV'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(20);

        return view('issuance.index', compact('issuances', 'documentTab'));
    }

    public function create(): View
    {
        $this->authorize('issuance.manage');

        $offices = Office::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $fundClusters = FundCluster::orderBy('code')->get();

        return view('issuance.create', compact('offices', 'employees', 'fundClusters'));
    }

    public function store(IssuanceStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $issuance = DB::transaction(function () use ($request, $validated) {
            $classifications = collect($validated['lines'])->map(function (array $line): string {
                $cost = !empty($line['inventory_item_id'])
                    ? (float) InventoryItem::findOrFail((int) $line['inventory_item_id'])->unit_cost
                    : (float) $line['unit_cost'];
                if ($cost >= 50000) {
                    return 'ppe';
                }

                return $cost >= 5000 ? 'sphv' : 'splv';
            })->unique();

            if ($classifications->count() > 1) {
                throw ValidationException::withMessages([
                    'lines' => 'Mixed classifications are not allowed in one transaction.',
                ]);
            }

            $classification = $classifications->first();
            $assetType = $classification === 'ppe' ? 'ppe' : 'semi_expendable';
            $docType = match ($classification) {
                'ppe' => 'PAR',
                'splv' => 'ICS-SPLV',
                default => 'ICS-SPHV',
            };

            $tx = PropertyTransaction::create([
                'entity_name' => $validated['entity_name'],
                'office_id' => $validated['office_id'],
                'employee_id' => $validated['employee_id'],
                'fund_cluster_id' => $validated['fund_cluster_id'],
                'transaction_date' => $validated['transaction_date'],
                'reference_no' => $validated['reference_no'] ?? null,
                'control_no' => 'TMP',
                'document_type' => $docType,
                'asset_type' => $assetType,
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $tx->update([
                'control_no' => NumberGenerator::next($docType, $validated['transaction_date']),
            ]);

            foreach ($validated['lines'] as $lineIndex => $line) {
                $inventory = null;
                if (! empty($line['inventory_item_id'])) {
                    $inventory = InventoryItem::findOrFail((int) $line['inventory_item_id']);
                    if ($inventory->status !== 'in_stock') {
                        throw ValidationException::withMessages([
                            'inventory' => 'Selected inventory item is not available for issuance.',
                        ]);
                    }
                }

                if (! $inventory && ! empty($line['item_id'])) {
                    $availableQuantity = InventoryItem::query()
                        ->where('item_id', (int) $line['item_id'])
                        ->where('status', 'in_stock')
                        ->count();

                    if ($availableQuantity < (int) $line['quantity']) {
                        throw ValidationException::withMessages([
                            "lines.{$lineIndex}.quantity" => "Only {$availableQuantity} unissued item(s) are available for issuance.",
                        ]);
                    }
                }

                $qty = $inventory ? 1 : (int) $line['quantity'];
                $unit = $inventory?->unit ?? $line['unit'];
                $description = $inventory?->description ?? $line['description'];
                $propertyNo = $inventory?->property_no ?? ($line['property_no'] ?? null);
                $dateAcquired = $inventory?->date_acquired ?? ($line['date_acquired'] ?? null);
                $unitCost = $inventory ? (float) $inventory->unit_cost : (float) $line['unit_cost'];
                $classificationPerLine = $unitCost >= 50000 ? 'ppe' : ($unitCost >= 5000 ? 'sphv' : 'splv');

                $tx->lines()->create([
                    'item_id' => $inventory?->item_id ?? ($line['item_id'] ?? null),
                    'inventory_item_id' => $inventory?->id ?? null,
                    'quantity' => $qty,
                    'unit' => $unit,
                    'description' => $description,
                    'property_no' => $propertyNo,
                    'date_acquired' => $dateAcquired,
                    'unit_cost' => $unitCost,
                    'total_cost' => $qty * $unitCost,
                    'classification' => $classificationPerLine,
                    'estimated_useful_life' => $line['estimated_useful_life'] ?? null,
                    'remarks' => $line['remarks'] ?? null,
                    'item_status' => 'active',
                ]);
            }

            AuditLogger::log(
                $request->user()->id,
                'issuance.created',
                $tx,
                ['control_no' => $tx->control_no],
                $request->ip(),
                $request->userAgent()
            );

            return $tx;
        });

        return redirect()->route('issuance.show', $issuance)->with('status', 'Issuance draft created.');
    }

    public function show(PropertyTransaction $issuance): View
    {
        $this->authorize('issuance.manage');

        $issuance->load(['lines', 'office', 'employee', 'fundCluster', 'approvals', 'documentControls']);
        $generatedDocuments = in_array($issuance->status, ['approved', 'issued'], true)
            ? DocumentControlRegistry::listFor($issuance)
            : [];

        return view('issuance.show', compact('issuance', 'generatedDocuments'));
    }

    public function submit(PropertyTransaction $issuance, Request $request): RedirectResponse
    {
        $this->authorize('issuance.manage');
        abort_if($issuance->status !== 'draft', 422, 'Only draft transactions can be submitted.');

        DB::transaction(function () use ($issuance, $request): void {
            $issuance->update(['status' => 'submitted', 'submitted_at' => now()]);

            $issuance->approvals()->create(['status' => 'pending']);

            AuditLogger::log($request->user()->id, 'issuance.submitted', $issuance, [], $request->ip(), $request->userAgent());
        });

        return back()->with('status', 'Issuance submitted for approval.');
    }

    public function print(PropertyTransaction $issuance, string $template, Request $request)
    {
        $this->authorize('issuance.manage');
        abort_unless(in_array($template, ['par', 'ics', 'property_card', 'semi_property_card', 'regspi', 'sticker'], true), 404);
        abort_unless(in_array($issuance->status, ['approved', 'issued'], true), 422, 'Only approved transactions can be printed.');

        $version = (int) PrintLog::where('printable_type', PropertyTransaction::class)
            ->where('printable_id', $issuance->id)
            ->where('template_name', $template)
            ->count() + 1;

        PrintLog::create([
            'printable_type' => PropertyTransaction::class,
            'printable_id' => $issuance->id,
            'template_name' => $template,
            'version' => $version,
            'printed_by' => $request->user()->id,
            'printed_at' => now(),
        ]);

        $issuance->update(['status' => 'issued']);

        AuditLogger::log($request->user()->id, 'issuance.printed', $issuance, ['template' => $template, 'version' => $version], $request->ip(), $request->userAgent());

        $issuance->load(['lines.inventoryItem.currentEmployee', 'lines.inventoryItem.office', 'office', 'employee', 'fundCluster']);
        $document = DocumentControlRegistry::findFor($issuance->loadMissing('documentControls'), $template);

        $sig = Signatory::where('is_active', true)->get()->keyBy('role_key');
        $orientation = in_array($template, ['property_card', 'semi_property_card', 'regspi']) ? 'landscape' : 'portrait';
        $inventoryByLine = InventoryItem::with(['currentEmployee', 'office'])
            ->whereIn('property_transaction_line_id', $issuance->lines->pluck('id'))
            ->orderBy('id')
            ->get()
            ->groupBy('property_transaction_line_id');

        return Pdf::loadView('issuance.pdf.'.$template, [
            'issuance' => $issuance,
            'version' => $version,
            'sig' => $sig,
            'documentControlNo' => $document?->control_no,
            'inventoryByLine' => $inventoryByLine,
        ])->setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ])->setPaper('a4', $orientation)->stream($template.'-'.($document?->control_no ?? $issuance->control_no).'.pdf');
    }
}
