@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="transferForm()">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Transfer</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">New Transfer Transaction</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('transfer.index') }}" class="hover:text-[#1a2c5b]">Transfer</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Create</span>
        </div>
    </div>

    {{-- Form --}}
    <div class="w-full px-4 py-5 sm:px-6 lg:px-8">
        @php
            $defaultLine = ['item_id' => '', 'inventory_item_id' => '', 'property_transaction_line_id' => '', 'reference_no' => '', 'quantity' => 1, 'unit' => '', 'description' => '', 'amount' => 0, 'condition' => 'Functional'];
            $initialLines = old('lines', $prefill['lines'] ?? [$defaultLine]);
            if (empty($initialLines)) {
                $initialLines = [$defaultLine];
            }
            $initialFromEmployeeId = (string) old('from_employee_id', $prefill['from_employee_id'] ?? '');
        @endphp
        <form method="POST" action="{{ route('transfer.store') }}" class="space-y-4">
            @csrf

            {{-- Section 1: Header Details --}}
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">1</span>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Header Details</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                    <div class="flex flex-col gap-1.5 md:col-span-3 lg:col-span-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Source Issuance (Optional)</label>
                        <select x-model="selectedIssuanceId" @change="loadIssuancePrefill()" class="rounded border border-indigo-300 bg-indigo-50 px-3 py-2 text-sm focus:border-indigo-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-600">
                            <option value="">Manual transfer entry</option>
                            @foreach($issuanceOptions as $issuance)
                            <option value="{{ $issuance->id }}">
                                {{ $issuance->control_no }} - {{ $issuance->employee?->name ?? 'N/A' }} ({{ strtoupper($issuance->document_type) }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-500">Selecting an issuance auto-fills the transfer details and line items from issued inventory.</p>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Entity Name</label>
                        <input name="entity_name" value="{{ old('entity_name', $prefill['entity_name'] ?? '') }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Entity name" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transfer From</label>
                        <select name="from_employee_id" x-model="fromEmployeeId" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $e)<option value="{{ $e->id }}" {{ (string) $e->id === $initialFromEmployeeId ? 'selected' : '' }}>{{ $e->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transfer To</label>
                        <select name="to_employee_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $e)<option value="{{ $e->id }}" {{ (string) $e->id === (string) old('to_employee_id') ? 'selected' : '' }}>{{ $e->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fund Cluster</label>
                        <select name="fund_cluster_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Fund Cluster</option>
                            @foreach($fundClusters as $f)<option value="{{ $f->id }}" {{ (string) $f->id === (string) old('fund_cluster_id', $prefill['fund_cluster_id'] ?? '') ? 'selected' : '' }}>{{ $f->code }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transfer Type</label>
                        <select name="transfer_type" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            @php($selectedType = old('transfer_type', $prefill['transfer_type'] ?? 'donation'))
                            <option value="donation" {{ $selectedType === 'donation' ? 'selected' : '' }}>Donation</option>
                            <option value="reassignment_recall" {{ $selectedType === 'reassignment_recall' ? 'selected' : '' }}>Reassignment-Recall</option>
                            <option value="relocate" {{ $selectedType === 'relocate' ? 'selected' : '' }}>Relocate</option>
                            <option value="retirement_resignation" {{ $selectedType === 'retirement_resignation' ? 'selected' : '' }}>Retirement-Resignation</option>
                            <option value="others" {{ $selectedType === 'others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Specify Others</label>
                        <input name="transfer_type_other" value="{{ old('transfer_type_other', $prefill['transfer_type_other'] ?? '') }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="If others">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transfer Date</label>
                        <input type="date" name="transfer_date" value="{{ old('transfer_date', $prefill['transfer_date'] ?? now()->toDateString()) }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Document Type</label>
                        <select name="document_type" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            @php($selectedDoc = old('document_type', $prefill['document_type'] ?? 'PTR'))
                            <option value="PTR" {{ $selectedDoc === 'PTR' ? 'selected' : '' }}>PTR</option>
                            <option value="ITR" {{ $selectedDoc === 'ITR' ? 'selected' : '' }}>ITR</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 2: Line Items --}}
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">2</span>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Line Items</h2>
                    </div>
                    <button type="button" @click="addLine()"
                        class="inline-flex items-center gap-1.5 rounded border border-[#c8a84b] bg-transparent px-3 py-1 text-[11px] font-semibold text-[#c8a84b] hover:bg-[#c8a84b]/10 transition">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Line
                    </button>
                </div>

                <template x-for="(line, index) in lines" :key="index">
                    <div class="p-5 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-gray-400">ITEM #<span x-text="index + 1"></span></span>
                            <button type="button" x-show="lines.length > 1" @click="removeLine(index)"
                                class="text-red-400 hover:text-red-600 text-xs font-semibold transition">&times; Remove</button>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                            <input type="hidden" :name="'lines['+index+'][property_transaction_line_id]'" x-model="line.property_transaction_line_id">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">PAR/ICS Reference</label>
                                <input :name="'lines['+index+'][reference_no]'" x-model="line.reference_no"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Reference number" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</label>
                                <input :name="'lines['+index+'][quantity]'" type="number" min="1" x-model.number="line.quantity"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</label>
                                <input :name="'lines['+index+'][unit]'" x-model="line.unit"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="e.g. pcs" required>
                            </div>
                            <div class="flex flex-col gap-1.5 md:col-span-2 relative" x-data="{ showSuggestions: false, suggestions: [], browseOpen: false, browseItems: [], browseQuery: '' }" @click.outside="showSuggestions = false">
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</label>
                                    <button type="button"
                                        @click="
                                            browseOpen = !browseOpen;
                                            if (browseOpen) {
                                                fetch('/items/search?q=' + encodeURIComponent(browseQuery) + '&limit=25')
                                                    .then(r => r.json())
                                                    .then(data => browseItems = data);
                                            }
                                        "
                                        class="rounded border border-gray-300 px-2 py-1 text-[10px] font-semibold text-gray-600 hover:bg-gray-50">
                                        View Item Catalog
                                    </button>
                                </div>
                                <input type="hidden" :name="'lines['+index+'][item_id]'" x-model="line.item_id">
                                <input type="hidden" :name="'lines['+index+'][inventory_item_id]'" x-model="line.inventory_item_id">
                                <input :name="'lines['+index+'][description]'" x-model="line.description"
                                    @input.debounce.300ms="
                                        line.inventory_item_id = '';
                                        line.property_transaction_line_id = '';
                                        if (line.description.length >= 2) {
                                            fetch('/items/search?q=' + encodeURIComponent(line.description))
                                                .then(r => r.json())
                                                .then(data => { suggestions = data; showSuggestions = data.length > 0; });
                                        } else { showSuggestions = false; suggestions = []; }
                                    "
                                    @focus="if (suggestions.length > 0) showSuggestions = true"
                                    autocomplete="off"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Search item catalog" required>
                                <div x-show="showSuggestions" x-cloak
                                     class="absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-48 overflow-y-auto">
                                    <template x-for="(item, si) in suggestions" :key="item.id">
                                        <button type="button"
                                            @click="line.inventory_item_id = ''; line.item_id = item.id; line.property_transaction_line_id = ''; line.reference_no = line.reference_no || ''; line.description = item.name + (item.description ? ' — ' + item.description : ''); line.unit = item.unit || ''; line.quantity = line.quantity || 1; line.amount = parseFloat(item.unit_cost); showSuggestions = false;"
                                            class="w-full text-left px-3 py-2 hover:bg-blue-50 border-b border-gray-100 last:border-b-0 transition">
                                            <p class="text-sm font-semibold text-gray-800">
                                                <span x-text="item.name"></span>
                                            </p>
                                            <p class="text-[11px] text-gray-500">
                                                <span x-text="item.category || 'Uncategorized'"></span> &bull;
                                                <span x-text="item.unit || 'unit'"></span> &bull;
                                                ₱<span x-text="parseFloat(item.unit_cost).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                            </p>
                                        </button>
                                    </template>
                                </div>
                                <div x-show="browseOpen" x-cloak class="mt-2 rounded border border-gray-200 bg-white p-2">
                                    <div class="mb-2 flex gap-2">
                                        <input type="text" x-model="browseQuery" placeholder="Search inventory list"
                                            class="w-full rounded border border-gray-300 px-2 py-1 text-xs">
                                        <button type="button"
                                            @click="
                                                fetch('/items/search?q=' + encodeURIComponent(browseQuery) + '&limit=25')
                                                    .then(r => r.json())
                                                    .then(data => browseItems = data);
                                            "
                                            class="rounded bg-gray-800 px-3 py-1 text-xs font-semibold text-white">
                                            Search
                                        </button>
                                    </div>
                                    <div class="max-h-44 overflow-y-auto border border-gray-100">
                                        <template x-for="inv in browseItems" :key="'browse-'+inv.id">
                                            <button type="button"
                                                @click="line.inventory_item_id = ''; line.item_id = inv.id; line.property_transaction_line_id = ''; line.reference_no = line.reference_no || ''; line.description = inv.name + (inv.description ? ' — ' + inv.description : ''); line.unit = inv.unit || ''; line.quantity = line.quantity || 1; line.amount = parseFloat(inv.unit_cost); browseOpen = false;"
                                                class="block w-full border-b border-gray-100 px-2 py-1.5 text-left text-xs hover:bg-blue-50">
                                                <span class="font-semibold" x-text="inv.name"></span>
                                                <span class="text-gray-500" x-text="' (' + (inv.category || 'Uncategorized') + ')'"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</label>
                                <input :name="'lines['+index+'][amount]'" type="number" step="0.01" x-model.number="line.amount"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0.00" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Condition</label>
                                <input :name="'lines['+index+'][condition]'" x-model="line.condition"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Functional" required>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200">
                    <span class="text-xs text-gray-500"><span x-text="lines.length"></span> item(s)</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-1">
                <button type="submit" class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#253d82] transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Save Draft
                </button>
                <a href="{{ route('transfer.index') }}" class="rounded border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function transferForm() {
    const blankLine = { item_id: '', inventory_item_id: '', property_transaction_line_id: '', reference_no: '', quantity: 1, unit: '', description: '', amount: 0, condition: 'Functional' };
    return {
        fromEmployeeId: @json($initialFromEmployeeId),
        selectedIssuanceId: @json($selectedIssuanceId ?? ''),
        lines: @json($initialLines),
        init() {
            this.lines = (this.lines || []).map((line) => ({ ...blankLine, ...line }));
            if (this.lines.length === 0) this.lines = [{ ...blankLine }];
        },
        loadIssuancePrefill() {
            const baseUrl = @json(route('transfer.create'));
            const next = this.selectedIssuanceId
                ? `${baseUrl}?issuance_id=${encodeURIComponent(this.selectedIssuanceId)}`
                : baseUrl;
            window.location.href = next;
        },
        addLine() {
            this.lines.push({ ...blankLine });
        },
        removeLine(index) {
            if (this.lines.length > 1) this.lines.splice(index, 1);
        }
    };
}
</script>
@endsection
