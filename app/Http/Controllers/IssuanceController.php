<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssuanceStoreRequest;
use App\Models\Approval;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PrintLog;
use App\Models\PropertyTransaction;
use App\Support\AuditLogger;
use App\Support\NumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IssuanceController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('issuance.manage');

        $issuances = PropertyTransaction::query()
            ->with(['office', 'employee', 'fundCluster'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('id')
            ->paginate(20);

        return view('issuance.index', compact('issuances'));
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
                $cost = (float) $line['unit_cost'];
                if ($cost >= 50000) {
                    return 'ppe';
                }

                return $cost >= 5000 ? 'sphv' : 'splv';
            })->unique();

            abort_if($classifications->count() > 1, 422, 'Mixed classifications are not allowed in one transaction.');

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
                'control_no' => NumberGenerator::next($docType, now()->year, $tx->id),
            ]);

            foreach ($validated['lines'] as $line) {
                $unitCost = (float) $line['unit_cost'];
                $classificationPerLine = $unitCost >= 50000 ? 'ppe' : ($unitCost >= 5000 ? 'sphv' : 'splv');

                $tx->lines()->create([
                    'quantity' => (int) $line['quantity'],
                    'unit' => $line['unit'],
                    'description' => $line['description'],
                    'property_no' => $line['property_no'] ?? null,
                    'date_acquired' => $line['date_acquired'] ?? null,
                    'unit_cost' => $unitCost,
                    'total_cost' => (int) $line['quantity'] * $unitCost,
                    'classification' => $classificationPerLine,
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

        $issuance->load(['lines', 'office', 'employee', 'fundCluster', 'approvals']);

        return view('issuance.show', compact('issuance'));
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
        abort_unless(in_array($template, ['par', 'ics', 'property_card', 'semi_property_card', 'regspi'], true), 404);
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

        $issuance->load(['lines', 'office', 'employee', 'fundCluster']);

        return Pdf::loadView('issuance.pdf.'.$template, [
            'issuance' => $issuance,
            'version' => $version,
        ])->setPaper('a4')->stream($template.'-'.$issuance->control_no.'.pdf');
    }
}
