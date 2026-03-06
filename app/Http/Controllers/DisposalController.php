<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisposalStoreRequest;
use App\Models\Disposal;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\PrintLog;
use App\Models\PropertyTransactionLine;
use App\Support\AuditLogger;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DisposalController extends Controller
{
    public function index(): View
    {
        $this->authorize('disposal.manage');

        $disposals = Disposal::with('employee')->latest('id')->paginate(20);

        return view('disposal.index', compact('disposals'));
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

        $disposal = DB::transaction(function () use ($validated, $request) {
            $disposal = Disposal::create([
                'entity_name' => $validated['entity_name'],
                'employee_id' => $validated['employee_id'],
                'designation' => $validated['designation'] ?? null,
                'station' => $validated['station'] ?? null,
                'fund_cluster_id' => $validated['fund_cluster_id'],
                'disposal_date' => $validated['disposal_date'],
                'disposal_type' => $validated['disposal_type'],
                'disposal_type_other' => $validated['disposal_type_other'] ?? null,
                'or_no' => $validated['or_no'] ?? null,
                'sale_amount' => $validated['sale_amount'] ?? null,
                'appraised_value' => $validated['appraised_value'] ?? null,
                'document_type' => $validated['document_type'],
                'control_no' => 'TMP',
                'status' => 'draft',
                'created_by' => $request->user()->id,
            ]);

            $disposal->update(['control_no' => NumberGenerator::next($disposal->document_type, now()->year, $disposal->id)]);

            foreach ($validated['lines'] as $line) {
                if (!empty($line['property_transaction_line_id'])) {
                    $source = PropertyTransactionLine::with('transaction')->findOrFail($line['property_transaction_line_id']);
                    abort_if($source->item_status === 'disposed', 422, 'Item already disposed.');
                    abort_if(!in_array($source->transaction->status, ['approved', 'issued'], true), 422, 'Cannot dispose unissued items.');
                }

                $qty = (int) $line['quantity'];
                $unitCost = (float) $line['unit_cost'];
                $total = $qty * $unitCost;
                $depr = (float) ($line['accumulated_depreciation'] ?? 0);

                $disposal->lines()->create([
                    'property_transaction_line_id' => $line['property_transaction_line_id'] ?? null,
                    'date_acquired' => $line['date_acquired'] ?? null,
                    'particulars' => $line['particulars'],
                    'property_no' => $line['property_no'] ?? null,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'total_cost' => $total,
                    'accumulated_depreciation' => $depr,
                    'carrying_amount' => $total - $depr,
                ]);
            }

            AuditLogger::log($request->user()->id, 'disposal.created', $disposal, [], $request->ip(), $request->userAgent());

            return $disposal;
        });

        return redirect()->route('disposal.show', $disposal)->with('status', 'Disposal draft created.');
    }

    public function show(Disposal $disposal): View
    {
        $this->authorize('disposal.manage');

        $disposal->load(['lines', 'employee']);

        return view('disposal.show', compact('disposal'));
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
        abort_unless(in_array($template, ['iirup', 'rrsep'], true), 404);
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

        $disposal->load(['lines', 'employee']);

        $sig = \App\Models\Signatory::where('is_active', true)->get()->keyBy('role_key');

        return Pdf::loadView('disposal.pdf.'.$template, compact('disposal', 'version', 'sig'))->setPaper('a4')->stream($template.'-'.$disposal->control_no.'.pdf');
    }
}
