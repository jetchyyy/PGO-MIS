<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Disposal;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'issuances'         => PropertyTransaction::count(),
            'transfers'         => Transfer::count(),
            'disposals'         => Disposal::count(),
            'pending_approvals' => Approval::where('status', 'pending')->count(),
        ];

        if ($user->role === User::ROLE_SYSTEM_ADMIN) {
            return view('dashboard.admin', compact('stats'));
        }

        return view('dashboard.staff', compact('stats'));
    }
}
