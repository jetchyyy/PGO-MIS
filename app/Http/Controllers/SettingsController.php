<?php

namespace App\Http\Controllers;

use App\Models\FundCluster;
use App\Models\Item;
use App\Models\Signatory;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $this->authorize('issuance.manage');

        $signatories = Signatory::orderBy('role_key')->get();
        $fundClusters = FundCluster::orderBy('code')->get();
        $items = Item::orderBy('name')->get();

        return view('settings.index', compact('signatories', 'fundClusters', 'items'));
    }
}
