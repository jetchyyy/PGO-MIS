@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

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
                        <select name="employee_id" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select Officer</option>
                            @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach
                        </select>
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

            {{-- Section 2: Line Item --}}
            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8a84b] text-[#1a2c5b] text-xs font-black">2</span>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Line Item</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-3 lg:grid-cols-4">
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Particulars</label>
                        <input name="lines[0][particulars]" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Item particulars" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Property No.</label>
                        <input name="lines[0][property_no]" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="Optional">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</label>
                        <input name="lines[0][quantity]" type="number" min="1" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Cost</label>
                        <input name="lines[0][unit_cost]" type="number" step="0.01" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0.00" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Accumulated Depreciation</label>
                        <input name="lines[0][accumulated_depreciation]" type="number" step="0.01" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" placeholder="0.00">
                    </div>
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
@endsection
