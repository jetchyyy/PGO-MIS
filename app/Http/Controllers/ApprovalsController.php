<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Disposal;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use App\Support\AuditLogger;
use App\Support\WorkflowUpdater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ApprovalsController extends Controller
{
    public function index(): View
    {
        $this->authorize('approvals.manage');

        $approvals = Approval::where('status', 'pending')->latest('id')->paginate(25);

        return view('reports.index', compact('approvals'));
    }

    public function approve(Approval $approval, Request $request): RedirectResponse
    {
        $this->authorize('approvals.manage');
        abort_if($approval->status !== 'pending', 422, 'Approval action already completed.');

        DB::transaction(function () use ($approval, $request): void {
            $approval->update([
                'status' => 'approved',
                'acted_by' => $request->user()->id,
                'remarks' => $request->string('remarks')->toString() ?: null,
                'acted_at' => now(),
            ]);

            $record = $approval->approvable;
            if ($record instanceof PropertyTransaction) {
                $record->update(['status' => 'approved', 'approved_at' => now()]);
                WorkflowUpdater::applyIssuance($record->load('lines'));
            }

            if ($record instanceof Transfer) {
                $record->update(['status' => 'approved', 'approved_at' => now()]);
                WorkflowUpdater::applyTransfer($record->load(['lines', 'fromEmployee']));
            }

            if ($record instanceof Disposal) {
                $record->update(['status' => 'approved', 'approved_at' => now()]);
                WorkflowUpdater::applyDisposal($record->load('lines'));
            }

            AuditLogger::log($request->user()->id, 'approval.approved', $record, ['approval_id' => $approval->id], $request->ip(), $request->userAgent());
        });

        return back()->with('status', 'Record approved.');
    }

    // Return an approval for correction
    public function return(Approval $approval, Request $request): RedirectResponse
    {
        $this->authorize('approvals.manage');
        abort_if($approval->status !== 'pending', 422, 'Approval action already completed.');

        DB::transaction(function () use ($approval, $request): void {
            $approval->update([
                'status' => 'returned',
                'acted_by' => $request->user()->id,
                'remarks' => $request->string('remarks')->toString() ?: 'Returned for correction.',
                'acted_at' => now(),
            ]);

            $record = $approval->approvable;
            $record?->update(['status' => 'returned']);

            AuditLogger::log($request->user()->id, 'approval.returned', $record, ['approval_id' => $approval->id], $request->ip(), $request->userAgent());
        });

        return back()->with('status', 'Record returned.');
    }
}
