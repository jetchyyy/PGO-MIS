@extends('layouts.app')

@section('content')
@php
    $employeeDirectory = $employees->mapWithKeys(fn ($employee) => [
        $employee->id => [
            'designation' => $employee->designation ?? '',
            'station' => $employee->station ?? '',
        ],
    ]);
    $defaultLine = [
        'item_id' => '',
        'inventory_item_id' => '',
        'property_transaction_line_id' => '',
        'available_quantity' => 1,
        'particulars' => '',
        'property_no' => '',
        'date_acquired' => '',
        'quantity' => 1,
        'unit' => '',
        'unit_cost' => 0,
        'condition' => 'Functional',
        'remarks' => '',
    ];
    $prefill = $prefill ?? null;
    $initialLines = old('lines', $prefill['lines'] ?? [$defaultLine]);
    if (empty($initialLines)) {
        $initialLines = [$defaultLine];
    }
@endphp
<div class="min-h-screen bg-gray-100" x-data="returnForm()">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Return</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">New Return Record</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('returns.index') }}" class="hover:text-[#1a2c5b]">Returns</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Create</span>
        </div>
    </div>

    <div class="w-full px-4 py-5 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('returns.store') }}" class="space-y-4">
            @csrf

            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">1</span>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Header Details</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Entity Name</label>
                        <input name="entity_name" value="{{ old('entity_name', $prefill['entity_name'] ?? 'Provincial Government of Surigao del Norte') }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Accountable Officer</label>
                        <select name="employee_id" x-model="employeeId" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Officer</option>
                            @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Designation</label>
                        <input name="designation" x-model="designation" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Station / Office</label>
                        <input name="station" x-model="station" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fund Cluster</label>
                        <select name="fund_cluster_id" x-model="fundClusterId" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Fund Cluster</option>
                            @foreach($fundClusters as $f)<option value="{{ $f->id }}">{{ $f->code }}</option>@endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Return Date</label>
                        <input type="date" name="return_date" value="{{ old('return_date', $prefill['return_date'] ?? now()->toDateString()) }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason for Return</label>
                        <input name="return_reason" value="{{ old('return_reason') }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Reason for return">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Required Form</label>
                        <input type="hidden" name="document_type" :value="documentType">
                        <div class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-800">
                            <span class="font-semibold" x-text="documentType"></span>
                            <p class="mt-1 text-[11px] text-gray-500">PHP 50,000 and above uses `PRS`. Below PHP 50,000 uses `RRSP`.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-gray-200 bg-[#1a2c5b] px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">2</span>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Line Items</h2>
                    </div>
                    <button type="button" @click="addLine()" class="inline-flex w-full items-center justify-center gap-1.5 rounded border border-[#c8a84b] bg-transparent px-3 py-1 text-[11px] font-semibold text-[#c8a84b] transition hover:bg-[#c8a84b]/10 sm:w-auto">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Line
                    </button>
                </div>

                <template x-for="(line, index) in lines" :key="index">
                    <div class="p-5 border-b border-gray-100 last:border-b-0">
                        <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-xs font-bold text-gray-400">ITEM #<span x-text="index + 1"></span></span>
                            <button type="button" x-show="lines.length > 1" @click="removeLine(index)" class="text-red-400 hover:text-red-600 text-xs font-semibold transition">&times; Remove</button>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <div class="flex flex-col gap-1.5 md:col-span-2 relative" x-data="{ showSuggestions: false, suggestions: [], browseOpen: false, browseItems: [], browseQuery: '' }" @click.outside="showSuggestions = false">
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Issued Inventory</label>
                                    <button type="button"
                                        @click="
                                            browseOpen = !browseOpen;
                                            if (browseOpen) {
                                                const params = new URLSearchParams({ mode: 'disposal', q: browseQuery, limit: '25' });
                                                if (employeeId) params.set('employee_id', employeeId);
                                                fetch('/inventory/search?' + params.toString()).then(r => r.json()).then(data => browseItems = data);
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
                                        if (line.particulars.length >= 2) {
                                            const params = new URLSearchParams({ mode: 'disposal', q: line.particulars });
                                            if (employeeId) params.set('employee_id', employeeId);
                                            fetch('/inventory/search?' + params.toString()).then(r => r.json()).then(data => { suggestions = data; showSuggestions = data.length > 0; });
                                        } else { showSuggestions = false; suggestions = []; }
                                    "
                                    @focus="if (suggestions.length > 0) showSuggestions = true"
                                    autocomplete="off"
                                    class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                                <div x-show="showSuggestions" x-cloak class="absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-48 overflow-y-auto">
                                    <template x-for="inv in suggestions" :key="inv.id">
                                        <button type="button" @click="applyInventorySelection(line, inv); showSuggestions = false;" class="w-full text-left px-3 py-2 hover:bg-blue-50 border-b border-gray-100 last:border-b-0 transition">
                                            <p class="text-sm font-semibold text-gray-800"><span x-text="inv.description"></span></p>
                                            <p class="text-[11px] text-gray-500">
                                                <span x-text="inv.inventory_code || 'N/A'"></span> •
                                                <span x-text="inv.holder || 'N/A'"></span> •
                                                <span x-text="inv.reference_no || 'No issuance ref'"></span>
                                            </p>
                                        </button>
                                    </template>
                                </div>
                                <div x-show="browseOpen" x-cloak class="mt-2 rounded border border-gray-200 bg-white p-2">
                                    <div class="mb-2 flex gap-2">
                                        <input type="text" x-model="browseQuery" placeholder="Search issued inventory" class="w-full rounded border border-gray-300 px-2 py-1 text-xs">
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
                                    <div class="max-h-44 overflow-y-auto border border-gray-100">
                                        <template x-for="inv in browseItems" :key="'browse-'+inv.id">
                                            <button type="button" @click="applyInventorySelection(line, inv); browseOpen = false;" class="block w-full border-b border-gray-100 px-2 py-1.5 text-left text-xs hover:bg-blue-50">
                                                <span class="font-semibold" x-text="inv.description"></span>
                                                <span class="text-gray-500" x-text="' [' + (inv.inventory_code || 'N/A') + '] - ' + (inv.holder || 'Unassigned')"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Property No.</label>
                                <input :name="'lines['+index+'][property_no]'" x-model="line.property_no" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Acquired</label>
                                <input :name="'lines['+index+'][date_acquired]'" type="date" x-model="line.date_acquired" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</label>
                                <input :name="'lines['+index+'][quantity]'" type="number" min="1" :max="line.available_quantity || null" x-model.number="line.quantity" @input="clampQuantity(line)" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</label>
                                <input :name="'lines['+index+'][unit]'" x-model="line.unit" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Cost</label>
                                <input :name="'lines['+index+'][unit_cost]'" type="number" step="0.01" min="0" x-model.number="line.unit_cost" @input="syncDocumentType()" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Condition</label>
                                <input :name="'lines['+index+'][condition]'" x-model="line.condition" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            </div>
                            <div class="flex flex-col gap-1.5 md:col-span-2 lg:col-span-4">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Remarks</label>
                                <input :name="'lines['+index+'][remarks]'" x-model="line.remarks" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]">
                            </div>
                        </div>
                    </div>
                </template>

                <div class="px-5 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <span class="text-xs text-gray-500"><span x-text="lines.length"></span> item(s)</span>
                        <span x-show="hasMixedClassifications" x-cloak class="text-xs font-semibold text-red-600">
                            Mixed PPE and semi-expendable items are not allowed in one return record.
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center">
                <button type="submit" :disabled="hasMixedClassifications" :class="hasMixedClassifications ? 'cursor-not-allowed border-gray-300 bg-gray-300 text-white hover:bg-gray-300' : 'border-[#1a2c5b] bg-[#1a2c5b] text-white hover:bg-[#253d82]'" class="inline-flex w-full items-center justify-center gap-2 rounded border px-6 py-2.5 text-sm font-semibold transition sm:w-auto">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Save Draft
                </button>
                <a href="{{ route('returns.index') }}" class="rounded border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50 sm:w-auto">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function returnForm() {
    const employeeDirectory = @json($employeeDirectory);
    const blankLine = @json($defaultLine);

    return {
        employeeId: @json((string) old('employee_id', $prefill['employee_id'] ?? '')),
        designation: @json(old('designation', $prefill['designation'] ?? '')),
        station: @json(old('station', $prefill['station'] ?? '')),
        fundClusterId: @json((string) old('fund_cluster_id', $prefill['fund_cluster_id'] ?? '')),
        documentType: @json(old('document_type', 'RRSP')),
        hasMixedClassifications: false,
        lines: @json($initialLines),
        suppressEmployeeReset: false,
        init() {
            this.lines = (this.lines || []).map((line) => ({ ...blankLine, ...line }));
            this.syncDocumentType();

            this.$watch('employeeId', (value) => {
                const selectedEmployee = employeeDirectory[value] || null;
                if (selectedEmployee) {
                    this.designation = selectedEmployee.designation || '';
                    this.station = selectedEmployee.station || '';
                }

                if (this.suppressEmployeeReset) {
                    this.suppressEmployeeReset = false;
                    return;
                }

                this.lines = this.lines.map(line => ({
                    ...line,
                    inventory_item_id: '',
                    property_transaction_line_id: '',
                }));
            });
        },
        syncDocumentType() {
            const types = Array.from(new Set(
                this.lines
                    .filter((line) => Number(line.unit_cost || 0) > 0)
                    .map((line) => Number(line.unit_cost || 0) >= 50000 ? 'PRS' : 'RRSP')
            ));

            this.hasMixedClassifications = types.length > 1;
            this.documentType = types[0] || 'RRSP';
        },
        applyInventorySelection(line, inventory) {
            if (inventory.current_employee_id) {
                this.suppressEmployeeReset = true;
                this.employeeId = String(inventory.current_employee_id);
                this.designation = inventory.holder_designation || '';
                this.station = inventory.holder_station || '';
            }

            line.inventory_item_id = inventory.id;
            line.item_id = inventory.item_id || '';
            line.property_transaction_line_id = inventory.property_transaction_line_id || '';
            line.available_quantity = Math.max(1, parseInt(inventory.available_quantity || 1, 10));
            line.particulars = inventory.description || '';
            line.property_no = inventory.property_no || '';
            line.date_acquired = inventory.date_acquired || '';
            line.quantity = Math.min(line.quantity || 1, line.available_quantity);
            line.unit = inventory.unit || line.unit || 'pc';
            line.unit_cost = parseFloat(inventory.unit_cost || 0);
            line.condition = line.condition || 'Functional';
            if (inventory.fund_cluster_id) {
                this.fundClusterId = String(inventory.fund_cluster_id);
            }
            this.syncDocumentType();
        },
        clampQuantity(line) {
            const max = Number(line.available_quantity || 0);
            if (max > 0 && Number(line.quantity || 0) > max) {
                line.quantity = max;
            }
            if (Number(line.quantity || 0) < 1) {
                line.quantity = 1;
            }
        },
        addLine() {
            this.lines.push({ ...blankLine });
        },
        removeLine(index) {
            if (this.lines.length > 1) {
                this.lines.splice(index, 1);
                this.syncDocumentType();
            }
        }
    };
}
</script>
@endsection
