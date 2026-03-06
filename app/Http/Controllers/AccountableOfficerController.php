<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Office;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountableOfficerController extends Controller
{
    public function index(): View
    {
        $this->authorize('issuance.manage');

        $employees = Employee::with('office')->orderBy('name')->get();
        $offices = Office::orderBy('name')->get();

        return view('accountable-officers.index', compact('employees', 'offices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'station' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:employees,email'],
        ]);

        Employee::create($validated);

        return redirect()->route('accountable-officers.index')->with('status', 'Accountable officer added.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->authorize('issuance.manage');

        try {
            $employee->delete();
        } catch (QueryException) {
            return back()->withErrors([
                'employee' => 'Accountable officer cannot be deleted because it is used in existing records.',
            ]);
        }

        return redirect()->route('accountable-officers.index')->with('status', 'Accountable officer deleted.');
    }
}
