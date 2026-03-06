@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Inventory Management</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Add New Item</p>
            <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('settings.index') }}" class="hover:text-[#1a2c5b]">Settings</a>
            <span>&rsaquo;</span>
            <a href="{{ route('items.index') }}" class="hover:text-[#1a2c5b]">Items</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Create</span>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <p class="font-semibold mb-1">Please correct the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Form --}}
    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('items.store') }}" class="space-y-5" x-data="itemForm()">
            @csrf

            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">1</span>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Item Details</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Item Name <span class="text-red-400">*</span></label>
                        <input name="name" value="{{ old('name') }}"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
                            placeholder="e.g. Laptop Computer, Office Chair" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</label>
                        <select name="category" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Item::CATEGORIES as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5 md:col-span-2 lg:col-span-3">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</label>
                        <textarea name="description" rows="2"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
                            placeholder="Optional detailed description">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit <span class="text-red-400">*</span></label>
                        <input name="unit" value="{{ old('unit', 'pcs') }}"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
                            placeholder="e.g. pcs, set, unit" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Cost (₱) <span class="text-red-400">*</span></label>
                        <input name="unit_cost" type="number" step="0.01" min="0.01" value="{{ old('unit_cost') }}" x-model.number="unitCost"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
                            placeholder="0.00" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Est. Useful Life</label>
                        <input name="estimated_useful_life" value="{{ old('estimated_useful_life') }}"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
                            placeholder="e.g. 5 years">
                    </div>
                </div>

                {{-- Classification Preview --}}
                <div class="px-5 pb-4" x-show="unitCost > 0" x-cloak>
                    <div class="rounded border-2 p-3 flex items-center gap-3 transition-all duration-200"
                         :class="classInfo.borderClass">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-white text-xs font-bold"
                             :class="classInfo.bgClass">
                            <span x-text="classInfo.icon"></span>
                        </div>
                        <div>
                            <p class="text-sm font-bold" :class="classInfo.textClass" x-text="classInfo.label"></p>
                            <p class="text-xs text-gray-500" x-text="classInfo.desc"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#253d82] transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Item
                </button>
                <a href="{{ route('items.index') }}" class="rounded border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function itemForm() {
    return {
        unitCost: {{ old('unit_cost', 0) }},
        get classInfo() {
            const c = parseFloat(this.unitCost) || 0;
            if (c >= 50000) return { label: 'PPE — Property, Plant & Equipment', desc: 'Unit cost ≥ ₱50,000 • PAR document • Property Card ledger', borderClass: 'border-blue-400 bg-blue-50', bgClass: 'bg-blue-600', textClass: 'text-blue-700', icon: 'P' };
            if (c >= 5000) return { label: 'Semi-Expendable — SPHV (₱5,000–₱49,999)', desc: 'ICS-SPHV document • Semi-Expendable Property Card', borderClass: 'border-amber-400 bg-amber-50', bgClass: 'bg-amber-500', textClass: 'text-amber-700', icon: 'S' };
            if (c > 0) return { label: 'Semi-Expendable — SPLV (₱1–₱4,999)', desc: 'ICS-SPLV document • Semi-Expendable Property Card', borderClass: 'border-gray-300 bg-gray-50', bgClass: 'bg-gray-500', textClass: 'text-gray-700', icon: 'S' };
            return { label: '', desc: '', borderClass: 'border-gray-200 bg-gray-50', bgClass: 'bg-gray-400', textClass: 'text-gray-600', icon: '?' };
        }
    };
}
</script>
@endsection
