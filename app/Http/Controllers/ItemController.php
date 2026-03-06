<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('issuance.manage');

        $items = Item::query()
            ->when($request->filled('search'), fn ($q) => $q->search($request->string('search')))
            ->when($request->filled('classification'), fn ($q) => $q->where('classification', $request->string('classification')))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->orderBy('name')
            ->paginate(25)
            ->appends($request->query());

        $categories = Item::whereNotNull('category')->distinct()->pluck('category')->sort()->values();

        return view('items.index', compact('items', 'categories'));
    }

    public function create(): View
    {
        $this->authorize('issuance.manage');

        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
            'estimated_useful_life' => 'nullable|string|max:100',
        ]);

        $validated['classification'] = Item::classifyByCost((float) $validated['unit_cost']);

        Item::create($validated);

        return redirect()->route('items.index')->with('status', 'Item added to catalog.');
    }

    public function edit(Item $item): View
    {
        $this->authorize('issuance.manage');

        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
            'estimated_useful_life' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['classification'] = Item::classifyByCost((float) $validated['unit_cost']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $item->update($validated);

        return redirect()->route('items.index')->with('status', 'Item updated.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $this->authorize('issuance.manage');

        $item->delete();

        return redirect()->route('items.index')->with('status', 'Item removed from catalog.');
    }

    /**
     * Show item details with issuance / transfer / disposal history.
     */
    public function show(Item $item): View
    {
        $this->authorize('issuance.manage');
        $this->ensureQrToken($item);

        $issuanceLines = $item->issuanceLines()
            ->with(['transaction.employee', 'transaction.office'])
            ->orderByDesc('created_at')
            ->get();

        $transferLines = $item->transferLines()
            ->with(['transfer'])
            ->orderByDesc('created_at')
            ->get();

        $disposalLines = $item->disposalLines()
            ->with(['disposal'])
            ->orderByDesc('created_at')
            ->get();

        $totalIssuedQty = $item->totalIssuedQty();

        return view('items.show', compact('item', 'issuanceLines', 'transferLines', 'disposalLines', 'totalIssuedQty'));
    }

    public function printQr(Item $item)
    {
        $this->authorize('issuance.manage');
        $this->ensureQrToken($item);

        return Pdf::loadView('items.pdf.qr_label', compact('item'))
            ->setOption([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ])
            ->setPaper('a4', 'portrait')
            ->stream('item-qr-'.$item->id.'.pdf');
    }

    /**
     * JSON search endpoint for Alpine.js autocomplete.
     * GET /items/search?q=keyboard&limit=10
     */
    public function search(Request $request): JsonResponse
    {
        $term = $request->string('q', '');
        $limit = min((int) $request->input('limit', 10), 25);

        $items = Item::active()
            ->search($term)
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'description', 'unit', 'unit_cost', 'classification', 'category', 'estimated_useful_life']);

        return response()->json($items);
    }

    private function ensureQrToken(Item $item): void
    {
        if (! $item->qr_token) {
            $item->update(['qr_token' => (string) Str::uuid()]);
        }
    }
}
