@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Inventory Management</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Item Catalog</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
            <a href="{{ route('items.create') }}"
               class="inline-flex items-center gap-2 rounded border border-[#c8a84b] bg-[#c8a84b]/10 px-4 py-2 text-sm font-semibold text-[#c8a84b] hover:bg-[#c8a84b]/20 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Item
            </a>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('settings.index') }}" class="hover:text-[#1a2c5b]">Settings</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Items</span>
        </div>
    </div>

    {{-- Status Flash --}}
    @if(session('status'))
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
    </div>
    @endif

    {{-- Filters --}}
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <form method="GET" action="{{ route('items.index') }}" class="bg-white border border-gray-200 rounded shadow-sm p-4">
            <div class="grid gap-3 md:grid-cols-4">
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Search</label>
                    <input name="search" value="{{ request('search') }}"
                        class="rounded border border-gray-300 bg-gray-50 px-3 py-1.5 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
                        placeholder="Name, description, or category...">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Classification</label>
                    <select name="classification" class="rounded border border-gray-300 bg-gray-50 px-3 py-1.5 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                        <option value="">All</option>
                        @foreach(\App\Models\Item::CLASSIFICATIONS as $key => $label)
                        <option value="{{ $key }}" {{ request('classification') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Category</label>
                    <select name="category" class="rounded border border-gray-300 bg-gray-50 px-3 py-1.5 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                        <option value="">All</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded bg-[#1a2c5b] px-4 py-1.5 text-sm font-semibold text-white hover:bg-[#253d82] transition">Filter</button>
                    <a href="{{ route('items.index') }}" class="rounded border border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 transition">Clear</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Items Table --}}
    <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-4 py-2.5 text-left text-[10px] font-bold uppercase tracking-wider text-gray-500">Category</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-gray-500">Unit</th>
                        <th class="px-4 py-2.5 text-right text-[10px] font-bold uppercase tracking-wider text-gray-500">Unit Cost</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-gray-500">Classification</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-gray-500">Issued</th>
                        <th class="px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-4 py-2.5 text-right text-[10px] font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-2.5">
                            <a href="{{ route('items.show', $item) }}" class="font-semibold text-blue-700 hover:underline">{{ $item->name }}</a>
                            @if($item->description)
                            <p class="text-xs text-gray-400 truncate max-w-xs">{{ $item->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-gray-600">{{ $item->category ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-center text-gray-600">{{ $item->unit }}</td>
                        <td class="px-4 py-2.5 text-right font-mono font-semibold text-gray-800">₱{{ number_format($item->unit_cost, 2) }}</td>
                        <td class="px-4 py-2.5 text-center">
                            @php
                                $cls = match($item->classification) {
                                    'ppe' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'sphv' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex rounded px-2 py-0.5 text-[10px] font-bold uppercase border {{ $cls }}">
                                {{ strtoupper($item->classification) }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            @php $issuedQty = $item->totalIssuedQty(); @endphp
                            @if($issuedQty > 0)
                            <span class="font-semibold text-blue-700">{{ $issuedQty }}</span>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            @if($item->is_active)
                            <span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[10px] font-bold">Active</span>
                            @else
                            <span class="inline-flex rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-[10px] font-bold">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('items.show', $item) }}" class="text-blue-600 hover:underline text-xs font-semibold">View</a>
                                <a href="{{ route('items.edit', $item) }}" class="text-[#1a2c5b] hover:underline text-xs font-semibold">Edit</a>
                                <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Remove this item from catalog?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                            <p class="font-semibold">No items in catalog</p>
                            <p class="text-xs mt-1">Add items to enable search & auto-fill in transaction forms.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($items->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $items->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- About Section --}}
    <div class="w-full px-4 sm:px-6 lg:px-8 pb-6">
        <div class="bg-blue-50 border border-blue-200 rounded p-4 text-xs text-blue-700">
            <p class="font-bold mb-1">About Item Catalog</p>
            <p>Items stored here serve as a master list. When creating Issuance, Transfer, or Disposal transactions, you can search and select items from this catalog to auto-fill line item details &mdash; or enter them manually.</p>
            <p class="mt-1">Classification is auto-determined: <strong>PPE</strong> (≥₱50,000), <strong>SPHV</strong> (₱5,000–₱49,999), <strong>SPLV</strong> (<₱5,000) per COA Circular 2022-004.</p>
        </div>
    </div>
</div>
@endsection
