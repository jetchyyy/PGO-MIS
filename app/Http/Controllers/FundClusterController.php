<?php

namespace App\Http\Controllers;

use App\Models\FundCluster;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FundClusterController extends Controller
{
    public function index(): View
    {
        $this->authorize('issuance.manage');

        $fundClusters = FundCluster::orderBy('code')->get();

        return view('fund-clusters.index', compact('fundClusters'));
    }

    public function create(): View
    {
        $this->authorize('issuance.manage');

        return view('fund-clusters.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:fund_clusters,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        FundCluster::create($validated);

        return redirect()->route('fund-clusters.index')->with('status', 'Fund cluster created successfully.');
    }

    public function edit(FundCluster $fundCluster): View
    {
        $this->authorize('issuance.manage');

        return view('fund-clusters.edit', compact('fundCluster'));
    }

    public function update(Request $request, FundCluster $fundCluster): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:fund_clusters,code,' . $fundCluster->id],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $fundCluster->update($validated);

        return redirect()->route('fund-clusters.index')->with('status', 'Fund cluster updated successfully.');
    }

    public function destroy(FundCluster $fundCluster): RedirectResponse
    {
        $this->authorize('issuance.manage');

        // Check if fund cluster is in use
        $inUse = \App\Models\PropertyTransaction::where('fund_cluster_id', $fundCluster->id)->exists();
        if ($inUse) {
            return redirect()->route('fund-clusters.index')->with('error', 'Cannot delete: this fund cluster is referenced by existing transactions.');
        }

        $fundCluster->delete();

        return redirect()->route('fund-clusters.index')->with('status', 'Fund cluster deleted.');
    }
}
