<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PrintLog;
use App\Models\RegSPIEntry;
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

        $query = DB::table('property_transaction_lines as ptl')
            ->join('property_transactions as pt', 'pt.id', '=', 'ptl.property_transaction_id')
            ->leftJoin('offices as o', 'o.id', '=', 'pt.office_id')
            ->leftJoin('fund_clusters as fc', 'fc.id', '=', 'pt.fund_cluster_id')
            ->where('pt.asset_type', 'ppe')
            ->whereIn('pt.status', ['approved', 'issued'])
            ->selectRaw('
                pt.office_id,
                o.name as office_name,
                pt.fund_cluster_id,
                fc.code as fund_cluster_code,
                COUNT(DISTINCT pt.id) as transaction_count,
                COUNT(ptl.id) as line_count,
                COALESCE(SUM(ptl.quantity), 0) as qty,
                COALESCE(SUM(ptl.total_cost), 0) as total_cost
            ')
            ->groupBy('pt.office_id', 'o.name', 'pt.fund_cluster_id', 'fc.code')
            ->orderBy('o.name')
            ->orderBy('fc.code');

        if ($request->filled('office_id')) {
            $query->where('pt.office_id', $request->integer('office_id'));
        }
        if ($request->filled('fund_cluster_id')) {
            $query->where('pt.fund_cluster_id', $request->integer('fund_cluster_id'));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('pt.transaction_date', [$request->date('from'), $request->date('to')]);
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

        $query = DB::table('property_transaction_lines as ptl')
            ->join('property_transactions as pt', 'pt.id', '=', 'ptl.property_transaction_id')
            ->leftJoin('offices as o', 'o.id', '=', 'pt.office_id')
            ->leftJoin('fund_clusters as fc', 'fc.id', '=', 'pt.fund_cluster_id')
            ->where('pt.asset_type', 'semi_expendable')
            ->whereIn('pt.status', ['approved', 'issued'])
            ->selectRaw('
                pt.office_id,
                o.name as office_name,
                pt.fund_cluster_id,
                fc.code as fund_cluster_code,
                COUNT(DISTINCT pt.id) as transaction_count,
                COUNT(ptl.id) as line_count,
                COALESCE(SUM(ptl.quantity), 0) as qty,
                COALESCE(SUM(ptl.total_cost), 0) as total_cost
            ')
            ->groupBy('pt.office_id', 'o.name', 'pt.fund_cluster_id', 'fc.code')
            ->orderBy('o.name')
            ->orderBy('fc.code');

        if ($request->filled('office_id')) {
            $query->where('pt.office_id', $request->integer('office_id'));
        }
        if ($request->filled('fund_cluster_id')) {
            $query->where('pt.fund_cluster_id', $request->integer('fund_cluster_id'));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('pt.transaction_date', [$request->date('from'), $request->date('to')]);
        }

        $rows = $query->get();

        return view('reports.semi_count', [
            'rows' => $rows,
            'offices' => Office::orderBy('name')->get(),
            'fundClusters' => FundCluster::orderBy('code')->get(),
        ]);
    }

    public function breakdown(Request $request): View
    {
        $this->authorize('reports.view');

        $validated = $request->validate([
            'asset_type' => ['required', 'string'],
            'office_id' => ['required', 'integer', 'exists:offices,id'],
            'fund_cluster_id' => ['required', 'integer', 'exists:fund_clusters,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        abort_unless(in_array($validated['asset_type'], ['ppe', 'semi_expendable'], true), 404);

        $query = DB::table('property_transaction_lines as ptl')
            ->join('property_transactions as pt', 'pt.id', '=', 'ptl.property_transaction_id')
            ->leftJoin('employees as e', 'e.id', '=', 'pt.employee_id')
            ->where('pt.asset_type', $validated['asset_type'])
            ->whereIn('pt.status', ['approved', 'issued'])
            ->where('pt.office_id', (int) $validated['office_id'])
            ->where('pt.fund_cluster_id', (int) $validated['fund_cluster_id'])
            ->selectRaw('
                ptl.id,
                pt.control_no,
                pt.document_type,
                pt.transaction_date,
                ptl.description,
                ptl.property_no,
                ptl.quantity,
                ptl.unit,
                ptl.unit_cost,
                ptl.total_cost,
                e.name as accountable_officer
            ')
            ->orderByDesc('pt.transaction_date')
            ->orderByDesc('ptl.id');

        if (!empty($validated['from']) && !empty($validated['to'])) {
            $query->whereBetween('pt.transaction_date', [$validated['from'], $validated['to']]);
        }

        if (!empty($validated['q'])) {
            $term = trim((string) $validated['q']);
            $query->where(function ($inner) use ($term): void {
                $inner->where('pt.control_no', 'like', '%'.$term.'%')
                    ->orWhere('ptl.description', 'like', '%'.$term.'%')
                    ->orWhere('ptl.property_no', 'like', '%'.$term.'%')
                    ->orWhere('e.name', 'like', '%'.$term.'%');
            });
        }

        $lines = $query->paginate(12)->withQueryString();

        return view('reports.partials.breakdown_modal_content', [
            'lines' => $lines,
            'officeName' => Office::whereKey((int) $validated['office_id'])->value('name') ?? '-',
            'fundClusterCode' => FundCluster::whereKey((int) $validated['fund_cluster_id'])->value('code') ?? '-',
        ]);
    }

    public function regspi(Request $request): View
    {
        $this->authorize('reports.view');

        $query = RegSPIEntry::with(['employee', 'office', 'fundCluster', 'semiExpendableCard', 'propertyTransaction'])
            ->latest('id');

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->integer('office_id'));
        }
        if ($request->filled('classification')) {
            $query->where('classification', $request->input('classification'));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('issue_date', [$request->date('from'), $request->date('to')]);
        }

        $totalEntries = (clone $query)->count();
        $classificationCounts = (clone $query)
            ->select('classification', DB::raw('COUNT(*) as total'))
            ->groupBy('classification')
            ->pluck('total', 'classification');
        $filteredTotalCost = (clone $query)->sum('total_cost');

        $rows = $query->paginate(25)->withQueryString();

        return view('reports.regspi', [
            'rows' => $rows,
            'offices' => Office::orderBy('name')->get(),
            'totalEntries' => $totalEntries,
            'classificationCounts' => $classificationCounts,
            'totalCost' => $filteredTotalCost,
        ]);
    }

    public function logs(): View
    {
        $this->authorize('reports.view');

        return view('reports.logs', [
            'logs' => AuditLog::query()
                ->with(['user', 'subject'])
                ->latest('id')
                ->paginate(50),
            'printLogs' => PrintLog::query()
                ->with(['printedByUser', 'printable'])
                ->latest('id')
                ->limit(50)
                ->get(),
        ]);
    }
}
