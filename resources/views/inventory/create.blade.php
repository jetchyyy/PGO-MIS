@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="mb-1 text-xl font-bold text-gray-900">Add Inventory</h1>
    <p class="mb-5 text-sm text-gray-500">Each quantity creates one physical unit with its own QR code.</p>

    @if($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('inventory.store') }}" class="space-y-4 rounded border bg-white p-4">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Catalog Item</label>
                <select name="item_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Manual Entry</option>
                    @foreach($items as $item)
                    <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Quantity</label>
                <input type="number" name="quantity" min="1" max="500" value="{{ old('quantity', 1) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Description</label>
                <input name="description" value="{{ old('description') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Model</label>
                <input name="model" value="{{ old('model') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Serial Number</label>
                <input name="serial_number" value="{{ old('serial_number') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Unit</label>
                <input name="unit" value="{{ old('unit') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Unit Cost</label>
                <input type="number" step="0.01" min="0" name="unit_cost" value="{{ old('unit_cost', 0) }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Classification</label>
                <select name="classification" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Select</option>
                    <option value="ppe" @selected(old('classification') === 'ppe')>PPE</option>
                    <option value="sphv" @selected(old('classification') === 'sphv')>SPHV</option>
                    <option value="splv" @selected(old('classification') === 'splv')>SPLV</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Property No.</label>
                <input name="property_no" value="{{ old('property_no') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Date Acquired</label>
                <input type="date" name="date_acquired" value="{{ old('date_acquired') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Person Accountable</label>
                <input name="accountable_name" value="{{ old('accountable_name') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase text-gray-500">Inventory Committee</label>
                <input name="inventory_committee_name" value="{{ old('inventory_committee_name') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button class="rounded bg-[#1a2c5b] px-5 py-2 text-sm font-semibold text-white hover:bg-[#243f83]">Save Inventory</button>
            <a href="{{ route('inventory.index') }}" class="rounded border border-gray-300 px-5 py-2 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
