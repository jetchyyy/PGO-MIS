@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100" x-data="issuanceForm()">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Document Issuance</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">New Issuance Transaction</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('issuance.index') }}" class="hover:text-[#1a2c5b]">Issuance</a>
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
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Classification Preview Banner --}}
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4" x-show="lines.length > 0 && lines[0].unit_cost > 0" x-cloak>
        <div class="rounded border-2 p-3 flex items-center gap-3 transition-all duration-200"
             :class="classificationInfo.borderClass">
            <div class="flex h-8 w-8 items-center justify-center rounded-full text-white text-xs font-bold"
                 :class="classificationInfo.bgClass">
                <span x-text="classificationInfo.icon"></span>
            </div>
            <div>
                <p class="text-sm font-bold" :class="classificationInfo.textClass">
                    <span x-text="classificationInfo.label"></span>
                    &mdash; Will generate: <span class="font-black" x-text="classificationInfo.docType"></span>
                </p>
                <p class="text-xs text-gray-500" x-text="classificationInfo.description"></p>
            </div>
            <div class="ml-auto text-right">
                <p class="text-xs text-gray-500">Grand Total</p>
                <p class="text-lg font-black" :class="classificationInfo.textClass" x-text="'₱' + grandTotal.toLocaleString('en-PH', {minimumFractionDigits: 2})"></p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('issuance.store') }}" class="space-y-5">
            @csrf

            {{-- Section 1: Header Details --}}
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">1</span>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Header Details</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Entity Name <span class="text-red-400">*</span></label>
                        <input name="entity_name" value="{{ old('entity_name', 'Provincial Government of Surigao del Norte') }}"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Office <span class="text-red-400">*</span></label>
                        <select name="office_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Office</option>
                            @foreach($offices as $office)<option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Accountable Officer <span class="text-red-400">*</span></label>
                        <select name="employee_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)<option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fund Cluster <span class="text-red-400">*</span></label>
                        <select name="fund_cluster_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Fund Cluster</option>
                            @foreach($fundClusters as $fc)<option value="{{ $fc->id }}" {{ old('fund_cluster_id') == $fc->id ? 'selected' : '' }}>{{ $fc->code }} - {{ $fc->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaction Date <span class="text-red-400">*</span></label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference No.</label>
                        <input name="reference_no" value="{{ old('reference_no') }}"
                            class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Optional">
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
                        {{-- Line header --}}
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-400">ITEM #<span x-text="index + 1"></span></span>
                                {{-- Per-line classification badge --}}
                                <template x-if="line.unit_cost > 0">
                                    <span class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-[10px] font-bold uppercase border"
                                          :class="getLineClassification(line).badgeClass"
                                          x-text="getLineClassification(line).label">
                                    </span>
                                </template>
                            </div>
                            <button type="button" x-show="lines.length > 1" @click="removeLine(index)"
                                class="text-red-400 hover:text-red-600 text-xs font-semibold transition">
                                &times; Remove
                            </button>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity <span class="text-red-400">*</span></label>
                                <input :name="'lines['+index+'][quantity]'" type="number" min="1" x-model.number="line.quantity"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit <span class="text-red-400">*</span></label>
                                <input :name="'lines['+index+'][unit]'" x-model="line.unit"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="e.g. pcs" required>
                            </div>
                            <div class="flex flex-col gap-1.5 md:col-span-2 relative" x-data="{ showSuggestions: false, suggestions: [], searchTimeout: null }" @click.outside="showSuggestions = false">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description <span class="text-red-400">*</span></label>
                                <input :name="'lines['+index+'][description]'" x-model="line.description"
                                    @input.debounce.300ms="
                                        if (line.description.length >= 2) {
                                            fetch('/items/search?q=' + encodeURIComponent(line.description))
                                                .then(r => r.json())
                                                .then(data => { suggestions = data; showSuggestions = data.length > 0; });
                                        } else { showSuggestions = false; suggestions = []; }
                                    "
                                    @focus="if (suggestions.length > 0) showSuggestions = true"
                                    autocomplete="off"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Type to search catalog or enter manually" required>
                                {{-- Autocomplete dropdown --}}
                                <div x-show="showSuggestions" x-cloak
                                     class="absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-48 overflow-y-auto">
                                    <template x-for="(item, si) in suggestions" :key="item.id">
                                        <button type="button"
                                            @click="line.description = item.name + (item.description ? ' — ' + item.description : ''); line.unit = item.unit; line.unit_cost = parseFloat(item.unit_cost); line.estimated_useful_life = item.estimated_useful_life || ''; showSuggestions = false;"
                                            class="w-full text-left px-3 py-2 hover:bg-blue-50 border-b border-gray-100 last:border-b-0 transition">
                                            <p class="text-sm font-semibold text-gray-800" x-text="item.name"></p>
                                            <p class="text-[11px] text-gray-500">
                                                <span x-text="item.category || 'Uncategorized'"></span> &bull;
                                                <span x-text="item.unit"></span> &bull;
                                                ₱<span x-text="parseFloat(item.unit_cost).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                                <span class="ml-1 uppercase font-bold" x-text="item.classification"></span>
                                            </p>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Property No.</label>
                                <input :name="'lines['+index+'][property_no]'" x-model="line.property_no"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Optional">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Acquired</label>
                                <input :name="'lines['+index+'][date_acquired]'" type="date" x-model="line.date_acquired"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Cost (₱) <span class="text-red-400">*</span></label>
                                <input :name="'lines['+index+'][unit_cost]'" type="number" step="0.01" min="0.01" x-model.number="line.unit_cost"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0.00" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Est. Useful Life</label>
                                <input :name="'lines['+index+'][estimated_useful_life]'" x-model="line.estimated_useful_life"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="e.g. 5 years">
                            </div>
                        </div>

                        {{-- Computed row --}}
                        <div class="mt-3 flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2 bg-gray-50 rounded px-3 py-1.5 border border-gray-200">
                                <span class="text-gray-500 text-xs font-semibold">Total Cost:</span>
                                <span class="font-bold text-gray-800" x-text="'₱' + ((line.quantity || 0) * (line.unit_cost || 0)).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="flex flex-col gap-1.5 flex-1">
                                <input :name="'lines['+index+'][remarks]'" x-model="line.remarks"
                                    class="rounded border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Remarks (optional)">
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Summary Footer --}}
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <span class="text-xs text-gray-500"><span x-text="lines.length"></span> item(s)</span>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 mr-2">Grand Total:</span>
                        <span class="text-lg font-black text-[#1a2c5b]" x-text="'₱' + grandTotal.toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>
            </div>

            {{-- Mixed Classification Warning --}}
            <div x-show="hasMixedClassifications" x-cloak
                 class="rounded border-2 border-amber-400 bg-amber-50 p-3 flex items-center gap-3">
                <svg class="h-5 w-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <div>
                    <p class="text-sm font-bold text-amber-700">Mixed Classifications Detected</p>
                    <p class="text-xs text-amber-600">All line items in a single transaction must be the same classification (all PPE or all Semi-Expendable). Please separate them into different transactions.</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-1">
                <button type="submit" :disabled="hasMixedClassifications"
                    class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#253d82] transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Save Draft
                </button>
                <a href="{{ route('issuance.index') }}" class="rounded border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function issuanceForm() {
    return {
        lines: [{ quantity: 1, unit: '', description: '', property_no: '', date_acquired: '', unit_cost: 0, estimated_useful_life: '', remarks: '' }],

        addLine() {
            this.lines.push({ quantity: 1, unit: '', description: '', property_no: '', date_acquired: '', unit_cost: 0, estimated_useful_life: '', remarks: '' });
        },

        removeLine(index) {
            if (this.lines.length > 1) this.lines.splice(index, 1);
        },

        get grandTotal() {
            return this.lines.reduce((sum, l) => sum + ((l.quantity || 0) * (l.unit_cost || 0)), 0);
        },

        getLineClassification(line) {
            const cost = parseFloat(line.unit_cost) || 0;
            if (cost >= 50000) return { label: 'PPE', badgeClass: 'border-blue-300 bg-blue-50 text-blue-700', type: 'ppe' };
            if (cost >= 5000) return { label: 'SPHV', badgeClass: 'border-amber-300 bg-amber-50 text-amber-700', type: 'sphv' };
            if (cost > 0) return { label: 'SPLV', badgeClass: 'border-gray-300 bg-gray-100 text-gray-600', type: 'splv' };
            return { label: '', badgeClass: '', type: '' };
        },

        get classificationInfo() {
            const validLines = this.lines.filter(l => (parseFloat(l.unit_cost) || 0) > 0);
            if (validLines.length === 0) return { label: '', docType: '', description: '', borderClass: 'border-gray-200 bg-gray-50', bgClass: 'bg-gray-400', textClass: 'text-gray-600', icon: '?' };

            const firstCost = parseFloat(validLines[0].unit_cost) || 0;
            if (firstCost >= 50000) {
                return {
                    label: 'PPE — Property, Plant & Equipment',
                    docType: 'PAR (Property Acknowledgement Receipt)',
                    description: 'Unit cost ≥ ₱50,000 — Ledger: Property Card | Appendix 69',
                    borderClass: 'border-blue-400 bg-blue-50',
                    bgClass: 'bg-blue-600',
                    textClass: 'text-blue-700',
                    icon: 'P'
                };
            }
            const subLabel = firstCost >= 5000 ? 'SPHV (₱5,000–₱49,999)' : 'SPLV (₱1–₱4,999)';
            return {
                label: 'Semi-Expendable — ' + subLabel,
                docType: 'ICS (Inventory Custodian Slip)',
                description: 'Unit cost < ₱50,000 — Ledger: Semi-Expendable Property Card | Registry: RegSPI',
                borderClass: 'border-amber-400 bg-amber-50',
                bgClass: 'bg-amber-500',
                textClass: 'text-amber-700',
                icon: 'S'
            };
        },

        get hasMixedClassifications() {
            const validLines = this.lines.filter(l => (parseFloat(l.unit_cost) || 0) > 0);
            if (validLines.length <= 1) return false;
            const types = new Set(validLines.map(l => {
                const c = parseFloat(l.unit_cost) || 0;
                return c >= 50000 ? 'ppe' : 'semi';
            }));
            return types.size > 1;
        }
    };
}
</script>
@endsection
