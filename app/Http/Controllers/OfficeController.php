<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeController extends Controller
{
    public function index(): View
    {
        $this->authorize('issuance.manage');

        $offices = Office::orderBy('name')->get();

        return view('offices.index', compact('offices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:offices,code'],
        ]);

        Office::create($validated);

        return redirect()->route('offices.index')->with('status', 'Office added.');
    }

    public function destroy(Office $office): RedirectResponse
    {
        $this->authorize('issuance.manage');

        try {
            $office->delete();
        } catch (QueryException) {
            return back()->withErrors([
                'office' => 'Office cannot be deleted because it is used in existing records.',
            ]);
        }

        return redirect()->route('offices.index')->with('status', 'Office deleted.');
    }
}
