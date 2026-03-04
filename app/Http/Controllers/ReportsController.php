<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PrintLog;
use App\Models\PropertyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        $this->authorize('reports.view');

        return view('reports.index');
    }

    public function ppeCount(Request $request): View
    {
        $this->authorize('reports.view');

        $query = PropertyTransaction::query()
            ->where('asset_type', 'ppe')
            ->whereIn('status', ['approved', 'issued'])
            ->select('office_id', 'fund_cluster_id', DB::raw('SUM((SELECT COALESCE(SUM(quantity),0) FROM property_transaction_lines WHERE property_transaction_lines.property_transaction_id = property_transactions.id)) as qty'))
            ->groupBy('office_id', 'fund_cluster_id')
            ->with(['office', 'fundCluster']);

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->integer('office_id'));
        }
        if ($request->filled('fund_cluster_id')) {
            $query->where('fund_cluster_id', $request->integer('fund_cluster_id'));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('transaction_date', [$request->date('from'), $request->date('to')]);
        }

        $rows = $query->get();

        return view('reports.ppe_count', [
            'rows' => $rows,
            'offices' => Office::orderBy('name')->get(),
            'fundClusters' => FundCluster::orderBy('code')->get(),
        ]);
    }

    public function semiCount(Request $request): View
    {
        $this->authorize('reports.view');

        $rows = PropertyTransaction::query()
            ->where('asset_type', 'semi_expendable')
            ->whereIn('status', ['approved', 'issued'])
            ->select('office_id', 'fund_cluster_id', DB::raw('COUNT(*) as count'))
            ->groupBy('office_id', 'fund_cluster_id')
            ->with(['office', 'fundCluster'])
            ->get();

        return view('reports.semi_count', compact('rows'));
    }

    public function regspi(): View
    {
        $this->authorize('reports.view');

        $rows = PropertyTransaction::with(['employee', 'office'])
            ->where('asset_type', 'semi_expendable')
            ->latest('id')
            ->paginate(25);

        return view('issuance.pdf.regspi', ['rows' => $rows, 'asReport' => true]);
    }

    public function logs(): View
    {
        $this->authorize('logs.view');

        return view('reports.index', [
            'auditLogs' => AuditLog::latest('id')->paginate(20, ['*'], 'audit_page'),
            'printLogs' => PrintLog::latest('id')->paginate(20, ['*'], 'print_page'),
        ]);
    }
}
