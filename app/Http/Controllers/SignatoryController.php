<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SignatoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('issuance.manage');

        $signatories = Signatory::orderBy('role_key')->get();

        return view('signatories.index', compact('signatories'));
    }

    public function create(): View
    {
        $this->authorize('issuance.manage');

        return view('signatories.create', ['roles' => Signatory::ROLES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'role_key' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'entity_name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Signatory::create($validated);

        return redirect()->route('signatories.index')->with('status', 'Signatory created successfully.');
    }

    public function edit(Signatory $signatory): View
    {
        $this->authorize('issuance.manage');

        return view('signatories.edit', [
            'signatory' => $signatory,
            'roles' => Signatory::ROLES,
        ]);
    }

    public function update(Request $request, Signatory $signatory): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'role_key' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'entity_name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $signatory->update($validated);

        return redirect()->route('signatories.index')->with('status', 'Signatory updated successfully.');
    }

    public function destroy(Signatory $signatory): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $signatory->delete();

        return redirect()->route('signatories.index')->with('status', 'Signatory deleted.');
    }
}
