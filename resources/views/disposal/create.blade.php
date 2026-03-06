@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="disposalForm()">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Disposal</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">New Disposal Record</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('disposal.index') }}" class="hover:text-[#1a2c5b]">Disposal</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Create</span>
        </div>
    </div>

    {{-- Form --}}
    <div class="w-full px-4 py-5 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('disposal.store') }}" class="space-y-4">
            @csrf

            {{-- Section 1: Header Details --}}
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">1</span>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Header Details</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Entity Name</label>
                        <input name="entity_name" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Entity name" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Accountable Officer</label>
                        <select name="employee_id" x-model="employeeId" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Officer</option>
                            @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach
                        </select>
                        <p class="text-[11px] text-gray-500">Disposal items are fetched from the latest assigned holder based on issuance and transfer history.</p>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Designation</label>
                        <input name="designation" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Optional">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Station</label>
                        <input name="station" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Optional">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fund Cluster</label>
                        <select name="fund_cluster_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Fund Cluster</option>
                            @foreach($fundClusters as $f)<option value="{{ $f->id }}">{{ $f->code }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disposal Date</label>
                        <input type="date" name="disposal_date" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disposal Type</label>
                        <select name="disposal_type" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="sale">Sale</option>
                            <option value="transfer">Transfer</option>
                            <option value="destruction">Destruction</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Specify Others</label>
                        <input name="disposal_type_other" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="If others">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Document Type</label>
                        <select name="document_type" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="IIRUP">IIRUP</option>
                            <option value="RRSEP">RRSEP</option>
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
                            <div class="flex flex-col gap-1.5 md:col-span-2 relative" x-data="{ showSuggestions: false, suggestions: [], browseOpen: false, browseItems: [], browseQuery: '' }" @click.outside="showSuggestions = false">
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Particulars (Issued Inventory)</label>
                                    <button type="button"
                                        @click="
                                            browseOpen = !browseOpen;
                                            if (browseOpen) {
                                                if (!employeeId) { browseItems = []; return; }
                                                fetch('/inventory/search?mode=disposal&employee_id=' + encodeURIComponent(employeeId) + '&q=' + encodeURIComponent(browseQuery) + '&limit=25')
                                                    .then(r => r.json())
                                                    .then(data => browseItems = data);
                                            }
                                        "
                                        class="rounded border border-gray-300 px-2 py-1 text-[10px] font-semibold text-gray-600 hover:bg-gray-50">
                                        View Issued Items
                                    </button>
                                </div>
                                <input type="hidden" :name="'lines['+index+'][item_id]'" x-model="line.item_id">
                                <input type="hidden" :name="'lines['+index+'][inventory_item_id]'" x-model="line.inventory_item_id">
                                <input type="hidden" :name="'lines['+index+'][property_transaction_line_id]'" x-model="line.property_transaction_line_id">
                                <input :name="'lines['+index+'][particulars]'" x-model="line.particulars"
                                    @input.debounce.300ms="
                                        line.inventory_item_id = '';
                                        line.property_transaction_line_id = '';
                                        if (!employeeId) { showSuggestions = false; suggestions = []; return; }
                                        if (line.particulars.length >= 2) {
                                            fetch('/inventory/search?mode=disposal&employee_id=' + encodeURIComponent(employeeId) + '&q=' + encodeURIComponent(line.particulars))
                                                .then(r => r.json())
                                                .then(data => { suggestions = data; showSuggestions = data.length > 0; });
                                        } else { showSuggestions = false; suggestions = []; }
                                    "
                                    @focus="if (suggestions.length > 0) showSuggestions = true"
                                    autocomplete="off"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Search from issued inventory" required>
                                <div x-show="showSuggestions" x-cloak
                                     class="absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-48 overflow-y-auto">
                                    <template x-for="(inv, si) in suggestions" :key="inv.id">
                                        <button type="button"
                                            @click="line.inventory_item_id = inv.id; line.item_id = inv.item_id || ''; line.property_transaction_line_id = inv.property_transaction_line_id || ''; line.particulars = inv.description || ''; line.property_no = inv.property_no || ''; line.quantity = 1; line.unit_cost = parseFloat(inv.unit_cost || 0); line.accumulated_depreciation = line.accumulated_depreciation || 0; showSuggestions = false;"
                                            class="w-full text-left px-3 py-2 hover:bg-blue-50 border-b border-gray-100 last:border-b-0 transition">
                                            <p class="text-sm font-semibold text-gray-800">
                                                <span x-text="inv.description"></span>
                                            </p>
                                            <p class="text-[11px] text-gray-500">
                                                <span x-text="inv.inventory_code || 'N/A'"></span> &bull;
                                                <span x-text="inv.holder || 'N/A'"></span> &bull;
                                                <span x-text="inv.reference_no || 'No issuance ref'"></span> &bull;
                                                ₱<span x-text="parseFloat(inv.unit_cost || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                            </p>
                                        </button>
                                    </template>
                                </div>
                                <div x-show="browseOpen" x-cloak class="mt-2 rounded border border-gray-200 bg-white p-2">
                                    <div class="mb-2 flex gap-2">
                                        <input type="text" x-model="browseQuery" placeholder="Search issued inventory"
                                            class="w-full rounded border border-gray-300 px-2 py-1 text-xs">
                                        <button type="button"
                                            @click="
                                                if (!employeeId) { browseItems = []; return; }
                                                fetch('/inventory/search?mode=disposal&employee_id=' + encodeURIComponent(employeeId) + '&q=' + encodeURIComponent(browseQuery) + '&limit=25')
                                                    .then(r => r.json())
                                                    .then(data => browseItems = data);
                                            "
                                            class="rounded bg-gray-800 px-3 py-1 text-xs font-semibold text-white">
                                            Search
                                        </button>
                                    </div>
                                    <p x-show="!employeeId" class="mb-2 text-[11px] text-amber-600">Select Accountable Officer first to load latest issued items.</p>
                                    <div class="max-h-44 overflow-y-auto border border-gray-100">
                                        <template x-for="inv in browseItems" :key="'browse-'+inv.id">
                                            <button type="button"
                                                @click="line.inventory_item_id = inv.id; line.item_id = inv.item_id || ''; line.property_transaction_line_id = inv.property_transaction_line_id || ''; line.particulars = inv.description || ''; line.property_no = inv.property_no || ''; line.quantity = 1; line.unit_cost = parseFloat(inv.unit_cost || 0); line.accumulated_depreciation = line.accumulated_depreciation || 0; browseOpen = false;"
                                                class="block w-full border-b border-gray-100 px-2 py-1.5 text-left text-xs hover:bg-blue-50">
                                                <span class="font-semibold" x-text="inv.description"></span>
                                                <span class="text-gray-500" x-text="' [' + (inv.inventory_code || 'N/A') + '] - ' + (inv.holder || 'N/A')"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Property No.</label>
                                <input :name="'lines['+index+'][property_no]'" x-model="line.property_no"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Optional">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</label>
                                <input :name="'lines['+index+'][quantity]'" type="number" min="1" x-model.number="line.quantity"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Cost</label>
                                <input :name="'lines['+index+'][unit_cost]'" type="number" step="0.01" x-model.number="line.unit_cost"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0.00" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Accumulated Depreciation</label>
                                <input :name="'lines['+index+'][accumulated_depreciation]'" type="number" step="0.01" x-model.number="line.accumulated_depreciation"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0.00">
                            </div>
                        </div>
                        {{-- Computed carrying amount --}}
                        <div class="mt-3 flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2 bg-gray-50 rounded px-3 py-1.5 border border-gray-200">
                                <span class="text-gray-500 text-xs font-semibold">Total Cost:</span>
                                <span class="font-bold text-gray-800" x-text="'₱' + ((line.quantity || 0) * (line.unit_cost || 0)).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="flex items-center gap-2 bg-gray-50 rounded px-3 py-1.5 border border-gray-200">
                                <span class="text-gray-500 text-xs font-semibold">Carrying Amount:</span>
                                <span class="font-bold text-gray-800" x-text="'₱' + (((line.quantity || 0) * (line.unit_cost || 0)) - (line.accumulated_depreciation || 0)).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
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
                <a href="{{ route('disposal.index') }}" class="rounded border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function disposalForm() {
    return {
        employeeId: '',
        lines: [{ item_id: '', inventory_item_id: '', property_transaction_line_id: '', particulars: '', property_no: '', quantity: 1, unit_cost: 0, accumulated_depreciation: 0 }],
        init() {
            this.$watch('employeeId', () => {
                this.lines = this.lines.map(line => ({
                    ...line,
                    inventory_item_id: '',
                    property_transaction_line_id: '',
                }));
            });
        },
        addLine() {
            this.lines.push({ item_id: '', inventory_item_id: '', property_transaction_line_id: '', particulars: '', property_no: '', quantity: 1, unit_cost: 0, accumulated_depreciation: 0 });
        },
        removeLine(index) {
            if (this.lines.length > 1) this.lines.splice(index, 1);
        }
    };
}
</script>
@endsection
