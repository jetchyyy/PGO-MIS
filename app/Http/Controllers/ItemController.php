<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        $inventoryItems = $item->inventoryItems()
            ->with([
                'currentEmployee.office',
                'office',
                'sourceLine.transaction.employee',
                'movements.fromEmployee',
                'movements.toEmployee',
            ])
            ->orderBy('id')
            ->get();

        $issuanceLines = $item->issuanceLines()
            ->with(['transaction.employee', 'transaction.office'])
            ->orderBy('date_acquired')
            ->orderBy('id')
            ->get();

        $transferLines = $item->transferLines()
            ->with(['transfer.fromEmployee', 'transfer.toEmployee', 'inventoryItem', 'sourceLine'])
            ->orderBy('date_acquired')
            ->orderBy('id')
            ->get();

        $disposalLines = $item->disposalLines()
            ->with(['disposal.employee', 'inventoryItem', 'sourceLine'])
            ->orderBy('date_acquired')
            ->orderBy('id')
            ->get();

        $totalIssuedQty = $item->totalIssuedQty();
        $historyEvents = $this->buildHistoryEvents($issuanceLines, $transferLines, $disposalLines);
        $currentLifecycleStatus = $historyEvents->last()['status_label'] ?? 'Unissued';
        $holderBreakdown = $this->buildHolderBreakdown($inventoryItems);

        return view('items.show', compact(
            'item',
            'inventoryItems',
            'issuanceLines',
            'transferLines',
            'disposalLines',
            'totalIssuedQty',
            'historyEvents',
            'currentLifecycleStatus',
            'holderBreakdown'
        ));
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

    private function buildHistoryEvents(Collection $issuanceLines, Collection $transferLines, Collection $disposalLines): Collection
    {
        $events = collect();

        foreach ($issuanceLines as $line) {
            $transaction = $line->transaction;
            $eventDate = $transaction?->transaction_date ?? $line->date_acquired ?? $line->created_at;

            $events->push([
                'type' => 'issuance',
                'title' => 'Issued',
                'status_label' => $line->item_status === 'disposed' ? 'Disposed' : 'Issued',
                'event_at' => $eventDate,
                'event_sort' => $this->normalizeEventDate($eventDate),
                'event_time_label' => $this->formatEventTime($eventDate),
                'sort_order' => 1,
                'control_no' => $transaction?->control_no,
                'document_type' => $transaction?->document_type,
                'link' => $transaction ? route('issuance.show', $transaction) : null,
                'headline' => $transaction?->employee?->name ?? 'Accountable officer not set',
                'subheadline' => $transaction?->office?->name,
                'quantity' => (int) $line->quantity,
                'amount' => (float) $line->total_cost,
                'property_no' => $line->property_no,
                'note' => $line->remarks ?: 'Item issued to accountable officer.',
                'icon' => 'issued',
                'accent' => 'rose',
            ]);
        }

        foreach ($transferLines as $line) {
            $transfer = $line->transfer;
            $eventDate = $transfer?->transfer_date ?? $line->created_at;

            $events->push([
                'type' => 'transfer',
                'title' => 'Transferred',
                'status_label' => 'Transferred',
                'event_at' => $eventDate,
                'event_sort' => $this->normalizeEventDate($eventDate),
                'event_time_label' => $this->formatEventTime($eventDate),
                'sort_order' => 2,
                'control_no' => $transfer?->control_no,
                'document_type' => $transfer?->document_type,
                'link' => $transfer ? route('transfer.show', $transfer) : null,
                'headline' => trim(($transfer?->fromEmployee?->name ?? 'Unassigned').' to '.($transfer?->toEmployee?->name ?? 'Unassigned')),
                'subheadline' => $transfer?->transfer_type === 'others'
                    ? ($transfer?->transfer_type_other ?: 'Transfer')
                    : Str::headline((string) $transfer?->transfer_type),
                'quantity' => (int) $line->quantity,
                'amount' => (float) $line->amount,
                'property_no' => $line->inventoryItem?->property_no ?? $line->sourceLine?->property_no,
                'note' => $line->condition ? 'Condition: '.$line->condition : 'Item transferred to another accountable officer.',
                'icon' => 'transferred',
                'accent' => 'amber',
            ]);
        }

        foreach ($disposalLines as $line) {
            $disposal = $line->disposal;
            $eventDate = $disposal?->disposal_date ?? $line->created_at;

            $events->push([
                'type' => 'disposal',
                'title' => 'Disposed',
                'status_label' => 'Disposed',
                'event_at' => $eventDate,
                'event_sort' => $this->normalizeEventDate($eventDate),
                'event_time_label' => $this->formatEventTime($eventDate),
                'sort_order' => 3,
                'control_no' => $disposal?->control_no,
                'document_type' => $disposal?->document_type,
                'link' => $disposal ? route('disposal.show', $disposal) : null,
                'headline' => $disposal?->employee?->name ?? 'Disposed record',
                'subheadline' => $disposal?->disposal_type === 'others'
                    ? ($disposal?->disposal_type_other ?: 'Disposal')
                    : Str::headline((string) $disposal?->disposal_type),
                'quantity' => (int) $line->quantity,
                'amount' => (float) $line->total_cost,
                'property_no' => $line->property_no,
                'note' => $line->remarks ?: 'Item removed from active inventory.',
                'icon' => 'disposed',
                'accent' => 'cyan',
            ]);
        }

        return $events
            ->sortBy([
                ['event_sort', 'asc'],
                ['sort_order', 'asc'],
            ])
            ->values();
    }

    private function buildHolderBreakdown(Collection $inventoryItems): Collection
    {
        return $inventoryItems->map(function ($inventory): array {
            $latestMovement = $inventory->movements->sortBy([
                ['movement_date', 'asc'],
                ['id', 'asc'],
            ])->values();

            $journey = $latestMovement->map(function ($movement): array {
                $label = match ($movement->movement_type) {
                    'issued' => 'Issued to '.($movement->toEmployee?->name ?? 'Unassigned'),
                    'transferred' => 'Transferred to '.($movement->toEmployee?->name ?? 'Unassigned'),
                    'disposed' => 'Disposed by '.($movement->fromEmployee?->name ?? 'Unknown'),
                    default => Str::headline((string) $movement->movement_type),
                };

                return [
                    'label' => $label,
                    'date' => $movement->movement_date,
                ];
            });

            $currentHolder = $inventory->currentEmployee?->name
                ?? $inventory->accountable_name
                ?? ($inventory->status === 'disposed' ? 'Disposed' : 'Unassigned');

            $currentLocation = $inventory->currentEmployee?->office?->name
                ?? $inventory->office?->name
                ?? '-';

            return [
                'inventory_code' => $inventory->inventory_code,
                'property_no' => $inventory->property_no,
                'status' => $inventory->status,
                'description' => $inventory->description,
                'current_holder' => $currentHolder,
                'current_location' => $currentLocation,
                'source_reference' => $inventory->sourceLine?->transaction?->control_no,
                'journey' => $journey,
            ];
        });
    }

    private function normalizeEventDate(CarbonInterface|string|null $date): string
    {
        if ($date instanceof CarbonInterface) {
            return $date->toDateTimeString();
        }

        return (string) $date;
    }

    private function formatEventTime(CarbonInterface|string|null $date): string
    {
        if (! $date instanceof CarbonInterface) {
            return '-';
        }

        if ($date->format('H:i:s') === '00:00:00') {
            return '-';
        }

        return $date->format('g:i A');
    }
}
