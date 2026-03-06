<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
            'signature_upload' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'signature_data' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['signature_path'] = $this->storeSignature($request);

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
            'signature_upload' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'signature_data' => ['nullable', 'string'],
            'remove_signature' => ['nullable', 'boolean'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $hasIncomingSignature = $request->hasFile('signature_upload')
            || ((string) $request->input('signature_data', '') !== '');
        $validated['signature_path'] = $this->storeSignature($request, $signatory->signature_path);

        if ($request->boolean('remove_signature') && ! $hasIncomingSignature) {
            if ($signatory->signature_path) {
                Storage::disk('public')->delete($signatory->signature_path);
            }
            $validated['signature_path'] = null;
        }

        $signatory->update($validated);

        return redirect()->route('signatories.index')->with('status', 'Signatory updated successfully.');
    }

    public function destroy(Signatory $signatory): RedirectResponse
    {
        $this->authorize('issuance.manage');

        if ($signatory->signature_path) {
            Storage::disk('public')->delete($signatory->signature_path);
        }

        $signatory->delete();

        return redirect()->route('signatories.index')->with('status', 'Signatory deleted.');
    }

    private function storeSignature(Request $request, ?string $existingPath = null): ?string
    {
        if ($request->hasFile('signature_upload')) {
            $path = $request->file('signature_upload')->store('signatures', 'public');
            if ($existingPath) {
                Storage::disk('public')->delete($existingPath);
            }

            return $path;
        }

        $signatureData = (string) $request->input('signature_data', '');
        if ($signatureData !== '' && str_starts_with($signatureData, 'data:image/')) {
            $parts = explode(',', $signatureData, 2);
            if (count($parts) === 2) {
                $raw = base64_decode($parts[1], true);
                if ($raw !== false) {
                    $path = 'signatures/'.Str::uuid().'.png';
                    Storage::disk('public')->put($path, $raw);
                    if ($existingPath) {
                        Storage::disk('public')->delete($existingPath);
                    }

                    return $path;
                }
            }
        }

        return $existingPath;
    }
}
