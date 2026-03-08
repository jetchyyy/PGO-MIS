<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('inventory.manage');

        $inventoryItems = InventoryItem::query()
            ->with(['item', 'currentEmployee', 'office'])
            ->when(
                $request->filled('search'),
                fn ($q) => $q->where(function ($sub) use ($request): void {
                    $term = $request->string('search')->toString();
                    $sub->where('inventory_code', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('property_no', 'like', "%{$term}%")
                        ->orWhere('model', 'like', "%{$term}%")
                        ->orWhere('serial_number', 'like', "%{$term}%")
                        ->orWhere('accountable_name', 'like', "%{$term}%");
                })
            )
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')->toString()))
            ->latest('id')
            ->paginate(20)
            ->appends($request->query());

        return view('inventory.index', compact('inventoryItems'));
    }

    public function search(Request $request): JsonResponse
    {
        $this->authorize('inventory.manage');

        $mode = $request->string('mode', 'issuance')->toString();
        $term = $request->string('q')->toString();
        $limit = min(max((int) $request->input('limit', 15), 1), 50);

        $query = InventoryItem::query()
            ->with(['currentEmployee:id,name,designation,station', 'sourceLine:id,property_transaction_id', 'sourceLine.transaction:id,control_no'])
            ->when($mode === 'issuance', fn ($q) => $q->where('status', 'in_stock'))
            ->when($mode === 'transfer', function ($q) use ($request): void {
                $q->where('status', 'issued');
                if ($request->filled('from_employee_id')) {
                    $q->where('current_employee_id', (int) $request->input('from_employee_id'));
                }
            })
            ->when($mode === 'disposal', function ($q) use ($request): void {
                $q->where('status', 'issued');
                if ($request->filled('employee_id')) {
                    $q->where('current_employee_id', (int) $request->input('employee_id'));
                }
            })
            ->when($term !== '', function ($q) use ($term): void {
                $q->where(function ($sub) use ($term): void {
                    $sub->where('inventory_code', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('property_no', 'like', "%{$term}%")
                        ->orWhere('model', 'like', "%{$term}%")
                        ->orWhere('serial_number', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('id')
            ->limit($limit);

        $rows = $query->get()->map(function (InventoryItem $row): array {
            $availableQuantity = null;
            if ($row->property_transaction_line_id) {
                $availableQuantity = InventoryItem::query()
                    ->where('property_transaction_line_id', $row->property_transaction_line_id)
                    ->when(
                        $row->current_employee_id,
                        fn ($q) => $q->where('current_employee_id', $row->current_employee_id),
                        fn ($q) => $q->whereNull('current_employee_id')
                    )
                    ->where('status', 'issued')
                    ->count();
            }

            return [
                'id' => $row->id,
                'item_id' => $row->item_id,
                'fund_cluster_id' => $row->fund_cluster_id,
                'inventory_code' => $row->inventory_code,
                'description' => $row->description,
                'property_no' => $row->property_no,
                'model' => $row->model,
                'serial_number' => $row->serial_number,
                'unit' => $row->unit,
                'unit_cost' => (float) $row->unit_cost,
                'classification' => $row->classification,
                'date_acquired' => optional($row->date_acquired)->toDateString(),
                'current_employee_id' => $row->current_employee_id,
                'holder' => $row->currentEmployee?->name,
                'holder_designation' => $row->currentEmployee?->designation,
                'holder_station' => $row->currentEmployee?->station,
                'property_transaction_line_id' => $row->property_transaction_line_id,
                'reference_no' => $row->sourceLine?->transaction?->control_no,
                'available_quantity' => $availableQuantity ?? 1,
            ];
        });

        return response()->json($rows);
    }

    public function create(): View
    {
        $this->authorize('inventory.manage');

        $items = Item::active()->orderBy('name')->get(['id', 'name', 'unit', 'unit_cost', 'classification']);

        return view('inventory.create', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('inventory.manage');

        $validated = $request->validate([
            'item_id' => ['nullable', 'exists:items,id'],
            'description' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:100'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'classification' => ['nullable', 'in:ppe,sphv,splv'],
            'property_no' => ['nullable', 'string', 'max:255'],
            'date_acquired' => ['nullable', 'date'],
            'accountable_name' => ['nullable', 'string', 'max:255'],
            'inventory_committee_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        for ($i = 0; $i < (int) $validated['quantity']; $i++) {
            InventoryItem::create([
                'item_id' => $validated['item_id'] ?? null,
                'inventory_code' => $this->nextInventoryCode(),
                'qr_token' => (string) Str::uuid(),
                'description' => $validated['description'],
                'model' => $validated['model'] ?? null,
                'serial_number' => $validated['serial_number'] ?? null,
                'unit' => $validated['unit'] ?? null,
                'unit_cost' => $validated['unit_cost'],
                'classification' => $validated['classification'] ?? null,
                'property_no' => $validated['property_no'] ?? null,
                'date_acquired' => $validated['date_acquired'] ?? null,
                'accountable_name' => $validated['accountable_name'] ?? null,
                'inventory_committee_name' => $validated['inventory_committee_name'] ?? null,
                'status' => 'in_stock',
            ]);
        }

        return redirect()->route('inventory.index')->with('status', 'Inventory items added with QR codes.');
    }

    public function show(InventoryItem $inventory): View
    {
        $this->authorize('inventory.manage');
        $inventory->load(['item', 'currentEmployee', 'office', 'fundCluster', 'movements.fromEmployee', 'movements.toEmployee']);

        return view('inventory.show', compact('inventory'));
    }

    public function print(Request $request): View
    {
        $this->authorize('inventory.manage');

        $ids = collect((array) $request->input('ids'))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->values();

        abort_if($ids->isEmpty(), 422, 'Select at least one inventory item to print.');

        $inventoryItems = InventoryItem::with(['office', 'currentEmployee'])
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        abort_if($inventoryItems->isEmpty(), 404);

        return view('inventory.print', compact('inventoryItems'));
    }

    public function track(string $token): View
    {
        $inventory = InventoryItem::with(['currentEmployee', 'office', 'fundCluster'])
            ->where('qr_token', $token)
            ->firstOrFail();

        return view('inventory.track', compact('inventory'));
    }

    private function nextInventoryCode(): string
    {
        do {
            $code = 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
        } while (InventoryItem::where('inventory_code', $code)->exists());

        return $code;
    }
}
