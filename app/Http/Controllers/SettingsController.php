<?php

namespace App\Http\Controllers;

use App\Models\FundCluster;
use App\Models\Item;
use App\Models\Office;
use App\Models\Employee;
use App\Models\Signatory;
use App\Models\User;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $this->authorize('issuance.manage');

        $signatories = Signatory::orderBy('role_key')->get();
        $fundClusters = FundCluster::orderBy('code')->get();
        $offices = Office::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $userCount = User::count();

        return view('settings.index', compact('signatories', 'fundClusters', 'offices', 'employees', 'items', 'userCount'));
    }
}
