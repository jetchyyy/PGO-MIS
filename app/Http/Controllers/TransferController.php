<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferStoreRequest;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\InventoryItem;
use App\Models\PrintLog;
use App\Models\PropertyTransactionLine;
use App\Models\Transfer;
use App\Support\AuditLogger;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(): View
    {
        $this->authorize('transfer.manage');

        $transfers = Transfer::with(['fromEmployee', 'toEmployee'])->latest('id')->paginate(20);

        return view('transfer.index', compact('transfers'));
    }

    public function create(): View
    {
        $this->authorize('transfer.manage');

        return view('transfer.create', [
            'employees' => Employee::orderBy('name')->get(),
            'fundClusters' => FundCluster::orderBy('code')->get(),
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
                'control_no' => NumberGenerator::next($transfer->document_type, now()->year, $transfer->id),
            ]);

            foreach ($validated['lines'] as $line) {
                $inventory = null;
                if (!empty($line['inventory_item_id'])) {
                    $inventory = InventoryItem::with(['sourceLine.transaction'])->findOrFail((int) $line['inventory_item_id']);
                    abort_if($inventory->status !== 'issued', 422, 'Selected inventory item is not currently issued.');
                    abort_if((int) $inventory->current_employee_id !== (int) $validated['from_employee_id'], 422, 'Selected item does not belong to transfer origin employee.');
                }

                if (!$inventory && !empty($line['property_transaction_line_id'])) {
                    $source = PropertyTransactionLine::with('transaction')->findOrFail($line['property_transaction_line_id']);
                    abort_if($source->item_status === 'disposed', 422, 'Cannot transfer disposed items.');
                    abort_if(!in_array($source->transaction->status, ['approved', 'issued'], true), 422, 'Cannot transfer unissued items.');
                }

                $transfer->lines()->create([
                    'item_id' => $inventory?->item_id ?? ($line['item_id'] ?? null),
                    'inventory_item_id' => $inventory?->id ?? null,
                    'property_transaction_line_id' => $inventory?->property_transaction_line_id ?? ($line['property_transaction_line_id'] ?? null),
                    'date_acquired' => $inventory?->date_acquired ?? ($line['date_acquired'] ?? null),
                    'reference_no' => $inventory?->sourceLine?->transaction?->control_no ?? $line['reference_no'],
                    'quantity' => $inventory ? 1 : $line['quantity'],
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

        $transfer->load(['lines', 'fromEmployee', 'toEmployee']);

        return view('transfer.show', compact('transfer'));
    }

    public function submit(Transfer $transfer, Request $request): RedirectResponse
    {
        $this->authorize('transfer.manage');
        abort_if($transfer->status !== 'draft', 422);

        $transfer->update(['status' => 'submitted', 'submitted_at' => now()]);
        $transfer->approvals()->create(['status' => 'pending']);

        AuditLogger::log($request->user()->id, 'transfer.submitted', $transfer, [], $request->ip(), $request->userAgent());

        return back()->with('status', 'Transfer submitted for approval.');
    }

    public function print(Transfer $transfer, string $template, Request $request)
    {
        $this->authorize('transfer.manage');
        abort_unless(in_array($template, ['ptr', 'itr'], true), 404);
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

        $transfer->load(['lines', 'fromEmployee', 'toEmployee', 'fundCluster']);

        $sig = \App\Models\Signatory::where('is_active', true)->get()->keyBy('role_key');

        return Pdf::loadView('transfer.pdf.'.$template, compact('transfer', 'version', 'sig'))->setPaper('a4')->stream($template.'-'.$transfer->control_no.'.pdf');
    }
}
