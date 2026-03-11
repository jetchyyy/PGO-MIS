<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferStoreRequest;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\PrintLog;
use App\Models\PropertyTransaction;
use App\Models\PropertyTransactionLine;
use App\Models\Signatory;
use App\Models\Transfer;
use App\Support\AuditLogger;
use App\Support\DocumentControlRegistry;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('transfer.manage');

        $documentTab = strtolower($request->string('doc', 'all')->toString());

        $transfers = Transfer::query()
            ->with(['fromEmployee', 'toEmployee', 'documentControls'])
            ->when(in_array($documentTab, ['ptr', 'itr'], true), fn ($q) => $q->where('document_type', strtoupper($documentTab)))
            ->latest('id')
            ->paginate(20);

        return view('transfer.index', compact('transfers', 'documentTab'));
    }

    public function create(Request $request): View
    {
        $this->authorize('transfer.manage');

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
                ->map(function (PropertyTransactionLine $line) use ($issuance, $inventoryRows): array {
                    $quantity = (int) ($inventoryRows[$line->id] ?? $line->quantity);
                    return [
                        'property_transaction_line_id' => (int) $line->id,
                        'reference_no' => (string) $issuance->control_no,
                        'quantity' => max(1, $quantity),
                        'available_quantity' => max(1, $quantity),
                        'unit' => (string) ($line->unit ?? ''),
                        'description' => (string) ($line->description ?? ''),
                        'amount' => (float) ($line->unit_cost * max(1, $quantity)),
                        'condition' => 'Functional',
                    ];
                })
                ->filter(fn (array $line): bool => $line['quantity'] > 0)
                ->values()
                ->all();

            $prefill = [
                'entity_name' => $issuance->entity_name,
                'from_employee_id' => (string) $issuance->employee_id,
                'fund_cluster_id' => (string) $issuance->fund_cluster_id,
                'transfer_type' => 'reassignment_recall',
                'document_type' => $issuance->document_type === 'PAR' ? 'PTR' : 'ITR',
                'transfer_date' => now()->toDateString(),
                'lines' => $lines,
            ];
        }

        return view('transfer.create', [
            'employees' => Employee::orderBy('name')->get(),
            'fundClusters' => FundCluster::orderBy('code')->get(),
            'issuanceOptions' => PropertyTransaction::query()
                ->with('employee:id,name')
                ->whereIn('status', ['approved', 'issued'])
                ->latest('id')
                ->limit(200)
                ->get(['id', 'control_no', 'employee_id', 'document_type', 'status']),
            'prefill' => $prefill,
            'selectedIssuanceId' => $issuanceId > 0 ? (string) $issuanceId : '',
        ]);
    }

    public function store(TransferStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $transfer = DB::transaction(function () use ($validated, $request) {
            $transfer = Transfer::create([
                'entity_name' => $validated['entity_name'],
                'from_employee_id' => $validated['from_employee_id'],
                'to_employee_id' => $validated['to_employee_id'],
                'fund_cluster_id' => $validated['fund_cluster_id'],
                'transfer_type' => $validated['transfer_type'],
                'transfer_type_other' => $validated['transfer_type_other'] ?? null,
                'transfer_date' => $validated['transfer_date'],
                'document_type' => $validated['document_type'],
                'control_no' => 'TMP',
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $transfer->update([
                'control_no' => NumberGenerator::next($transfer->document_type, $validated['transfer_date']),
            ]);

            foreach ($validated['lines'] as $line) {
                $inventory = null;
                $requestedQty = max(1, (int) ($line['quantity'] ?? 1));
                if (!empty($line['inventory_item_id'])) {
                    $inventory = InventoryItem::with(['sourceLine.transaction'])->findOrFail((int) $line['inventory_item_id']);
                    if ($inventory->status !== 'issued') {
                        throw ValidationException::withMessages([
                            'inventory' => 'Selected inventory item is not currently issued.',
                        ]);
                    }
                    if ((int) $inventory->current_employee_id !== (int) $validated['from_employee_id']) {
                        throw ValidationException::withMessages([
                            'inventory' => 'Selected item does not belong to transfer origin employee.',
                        ]);
                    }

                    if ($requestedQty > 1 && $inventory->property_transaction_line_id) {
                        $availableQty = InventoryItem::query()
                            ->where('status', 'issued')
                            ->where('property_transaction_line_id', (int) $inventory->property_transaction_line_id)
                            ->where('current_employee_id', (int) $validated['from_employee_id'])
                            ->count();

                        if ($availableQty < $requestedQty) {
                            throw ValidationException::withMessages([
                                'inventory' => "Insufficient quantity for transfer. Requested {$requestedQty}, available {$availableQty}.",
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
                            'inventory' => 'Cannot transfer disposed items.',
                        ]);
                    }
                    if (!in_array($source->transaction->status, ['approved', 'issued'], true)) {
                        throw ValidationException::withMessages([
                            'inventory' => 'Cannot transfer unissued items.',
                        ]);
                    }
                }

                $transfer->lines()->create([
                    'item_id' => $inventory?->item_id ?? ($line['item_id'] ?? null),
                    'inventory_item_id' => $inventory?->id ?? null,
                    'property_transaction_line_id' => $inventory?->property_transaction_line_id ?? ($line['property_transaction_line_id'] ?? null),
                    'date_acquired' => $inventory?->date_acquired ?? ($line['date_acquired'] ?? null),
                    'reference_no' => $inventory?->sourceLine?->transaction?->control_no ?? $line['reference_no'],
                    'quantity' => $inventory ? 1 : $requestedQty,
                    'unit' => $inventory?->unit ?? $line['unit'],
                    'description' => $inventory?->description ?? $line['description'],
                    'amount' => $inventory ? (float) $inventory->unit_cost : $line['amount'],
                    'condition' => $line['condition'],
                ]);
            }

            AuditLogger::log($request->user()->id, 'transfer.created', $transfer, [], $request->ip(), $request->userAgent());

            return $transfer;
        });

        return redirect()->route('transfer.show', $transfer)->with('status', 'Transfer draft created.');
    }

    public function show(Transfer $transfer): View
    {
        $this->authorize('transfer.manage');

        $transfer->load(['lines', 'fromEmployee', 'toEmployee', 'fundCluster', 'approvals', 'documentControls']);
        $generatedDocuments = in_array($transfer->status, ['approved', 'issued'], true)
            ? DocumentControlRegistry::listFor($transfer)
            : [];

        return view('transfer.show', compact('transfer', 'generatedDocuments'));
    }

    public function submit(Transfer $transfer, Request $request): RedirectResponse
    {
        $this->authorize('transfer.manage');
        $wasReturned = $transfer->status === 'returned';
        abort_if(! in_array($transfer->status, ['draft', 'returned'], true), 422);

        $transfer->update(['status' => 'submitted', 'submitted_at' => now()]);
        $transfer->approvals()->create(['status' => 'pending']);

        AuditLogger::log($request->user()->id, 'transfer.submitted', $transfer, [], $request->ip(), $request->userAgent());

        return back()->with('status', $wasReturned ? 'Transfer resubmitted for approval.' : 'Transfer submitted for approval.');
    }

    public function print(Transfer $transfer, string $template, Request $request)
    {
        $this->authorize('transfer.manage');
        abort_unless(in_array($template, ['ptr', 'itr', 'sticker'], true), 404);
        abort_unless(in_array($transfer->status, ['approved', 'issued'], true), 422);

        $version = (int) PrintLog::where('printable_type', Transfer::class)
            ->where('printable_id', $transfer->id)
            ->where('template_name', $template)
            ->count() + 1;

        PrintLog::create([
            'printable_type' => Transfer::class,
            'printable_id' => $transfer->id,
            'template_name' => $template,
            'version' => $version,
            'printed_by' => $request->user()->id,
            'printed_at' => now(),
        ]);

        $transfer->update(['status' => 'issued']);

        AuditLogger::log($request->user()->id, 'transfer.printed', $transfer, ['template' => $template, 'version' => $version], $request->ip(), $request->userAgent());

        $transfer->load(['lines.sourceLine', 'fromEmployee', 'toEmployee.office', 'fundCluster']);
        $document = DocumentControlRegistry::findFor($transfer->loadMissing('documentControls'), $template);

        $sig = Signatory::where('is_active', true)->get()->keyBy('role_key');

        $stickerEntries = collect();
        if ($template === 'sticker') {
            $movedItemIds = InventoryMovement::query()
                ->where('reference_type', Transfer::class)
                ->where('reference_id', $transfer->id)
                ->pluck('inventory_item_id')
                ->filter()
                ->unique()
                ->values();

            $pool = InventoryItem::with(['currentEmployee', 'office'])
                ->whereIn('id', $movedItemIds)
                ->orderBy('id')
                ->get();

            foreach ($transfer->lines as $line) {
                $needed = max(1, (int) $line->quantity);

                $candidates = $pool->filter(function (InventoryItem $item) use ($line): bool {
                    if ($line->inventory_item_id) {
                        return (int) $item->id === (int) $line->inventory_item_id;
                    }
                    if ($line->property_transaction_line_id) {
                        return (int) $item->property_transaction_line_id === (int) $line->property_transaction_line_id;
                    }

                    return trim((string) $item->description) === trim((string) $line->description);
                })->take($needed)->values();

                foreach ($candidates as $item) {
                    $stickerEntries->push(['line' => $line, 'inventory' => $item]);
                }

                if ($candidates->isNotEmpty()) {
                    $usedIds = $candidates->pluck('id')->all();
                    $pool = $pool->reject(fn (InventoryItem $item): bool => in_array($item->id, $usedIds, true))->values();
                }

                for ($i = $candidates->count(); $i < $needed; $i++) {
                    $stickerEntries->push(['line' => $line, 'inventory' => null]);
                }
            }
        }

        return Pdf::loadView('transfer.pdf.'.$template, compact('transfer', 'version', 'sig', 'stickerEntries') + [
            'documentControlNo' => $document?->control_no,
        ])
            ->setPaper('a4')
            ->stream($template.'-'.($document?->control_no ?? $transfer->control_no).'.pdf');
    }
}
