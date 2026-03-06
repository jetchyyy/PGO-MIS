@extends('layouts.app')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Inventory Management</h1>
            <p class="text-sm text-gray-500">Track issued, transferred, and disposed units with QR codes.</p>
        </div>
        <a href="{{ route('inventory.create') }}" class="rounded bg-[#1a2c5b] px-4 py-2 text-sm font-semibold text-white hover:bg-[#243f83]">Add Inventory</a>
    </div>

    @if(session('status'))
    <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    <form method="GET" class="mb-4 grid gap-3 rounded border bg-white p-3 md:grid-cols-3">
        <input name="search" value="{{ request('search') }}" placeholder="Search code, description, property no."
            class="rounded border border-gray-300 px-3 py-2 text-sm">
        <select name="status" class="rounded border border-gray-300 px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['in_stock' => 'In Stock', 'issued' => 'Issued', 'disposed' => 'Disposed'] as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button class="rounded bg-gray-800 px-4 py-2 text-sm font-semibold text-white">Filter</button>
            <a href="{{ route('inventory.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm">Reset</a>
        </div>
    </form>

    <form method="GET" action="{{ route('inventory.print') }}">
        <div class="mb-3 flex justify-end">
            <button class="rounded border border-[#1a2c5b] px-4 py-2 text-sm font-semibold text-[#1a2c5b] hover:bg-[#1a2c5b] hover:text-white">
                Print Selected QR Labels
            </button>
        </div>

        <div class="overflow-x-auto rounded border bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-3 py-2"><input type="checkbox" onclick="document.querySelectorAll('.pick-item').forEach(cb => cb.checked = this.checked)"></th>
                        <th class="px-3 py-2">Code</th>
                        <th class="px-3 py-2">Description</th>
                        <th class="px-3 py-2">Holder</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventoryItems as $row)
                    <tr class="border-t">
                        <td class="px-3 py-2"><input class="pick-item" type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                        <td class="px-3 py-2 font-semibold">{{ $row->inventory_code }}</td>
                        <td class="px-3 py-2">{{ $row->description }}</td>
                        <td class="px-3 py-2">{{ $row->currentEmployee?->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">
                            <span class="rounded px-2 py-1 text-xs font-semibold {{ $row->status === 'disposed' ? 'bg-red-100 text-red-700' : ($row->status === 'issued' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ str_replace('_', ' ', $row->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('inventory.show', $row) }}" class="text-[#1a2c5b] hover:underline">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-gray-500">No inventory records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-4">{{ $inventoryItems->links() }}</div>
</div>
@endsection
