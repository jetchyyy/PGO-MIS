@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">RegSPI Masterlist</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Registry of Semi-Expendable Property Issued</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office — Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb & Actions --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('reports.index') }}" class="hover:text-[#1a2c5b]">Reports</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">RegSPI Masterlist</span>
            </div>
            <button onclick="window.print()" class="inline-flex w-full items-center justify-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#253d82] sm:w-auto">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Export PDF
            </button>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-5">

        {{-- Filters --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden print:hidden">
            <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-200">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-600">Filters</h3>
            </div>
            <form method="GET" action="{{ route('reports.regspi') }}" class="flex flex-col gap-4 p-4 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Office</label>
                    <select name="office_id" class="w-full rounded border border-gray-300 px-3 py-1.5 text-xs sm:w-48">
                        <option value="">All Offices</option>
                        @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Classification</label>
                    <select name="classification" class="w-full rounded border border-gray-300 px-3 py-1.5 text-xs sm:w-36">
                        <option value="">All</option>
                        <option value="splv" {{ request('classification') === 'splv' ? 'selected' : '' }}>SPLV</option>
                        <option value="sphv" {{ request('classification') === 'sphv' ? 'selected' : '' }}>SPHV</option>
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Date From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="rounded border border-gray-300 text-xs px-3 py-1.5">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Date To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="rounded border border-gray-300 text-xs px-3 py-1.5">
                </div>
                <button type="submit" class="rounded bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">Filter</button>
                <a href="{{ route('reports.regspi') }}" class="text-xs text-gray-500 hover:text-gray-700 underline">Clear</a>
            </form>
        </div>

        {{-- Summary Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#1a2c5b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ number_format($totalEntries) }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Total Entries</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-amber-500"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ number_format((int) ($classificationCounts['sphv'] ?? 0)) }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">SPHV (High Value)</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-gray-400"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ number_format((int) ($classificationCounts['splv'] ?? 0)) }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">SPLV (Low Value)</p>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="flex flex-col gap-2 border-b border-gray-200 bg-[#1a2c5b] px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Registry of Semi-Expendable Property Issued (RegSPI)</h2>
                <span class="text-blue-200 text-[11px]">Page {{ $rows->currentPage() }} of {{ $rows->lastPage() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">No.</th>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">ICS No.</th>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Description</th>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Property No.</th>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Accountable Officer</th>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Office</th>
                            <th class="px-3 py-2.5 text-right text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Qty</th>
                            <th class="px-3 py-2.5 text-right text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Unit Cost</th>
                            <th class="px-3 py-2.5 text-right text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Total Cost</th>
                            <th class="px-3 py-2.5 text-center text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Class</th>
                            <th class="px-3 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Date Issued</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $entry)
                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                            <td class="px-3 py-2.5 text-gray-400 font-semibold text-xs border-r border-gray-100">{{ $rows->firstItem() + $i }}</td>
                            <td class="px-3 py-2.5 font-semibold text-gray-800 border-r border-gray-100 font-mono text-xs">
                                @if($entry->propertyTransaction)
                                <a href="{{ route('issuance.show', $entry->property_transaction_id) }}" class="text-[#1a2c5b] hover:underline">{{ $entry->ics_no }}</a>
                                @else
                                {{ $entry->ics_no }}
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-gray-700 border-r border-gray-100">{{ $entry->description }}</td>
                            <td class="px-3 py-2.5 text-gray-600 font-mono text-xs border-r border-gray-100">{{ $entry->property_no ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-gray-700 border-r border-gray-100">{{ $entry->employee->name ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-gray-600 border-r border-gray-100">{{ $entry->office->name ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-right text-gray-700 border-r border-gray-100">{{ $entry->quantity_issued }}</td>
                            <td class="px-3 py-2.5 text-right text-gray-700 border-r border-gray-100">{{ number_format($entry->unit_cost, 2) }}</td>
                            <td class="px-3 py-2.5 text-right font-semibold text-gray-800 border-r border-gray-100">{{ number_format($entry->total_cost, 2) }}</td>
                            <td class="px-3 py-2.5 text-center border-r border-gray-100">
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border
                                    {{ $entry->classification === 'sphv' ? 'border-amber-300 bg-amber-50 text-amber-700' : 'border-gray-300 bg-gray-100 text-gray-600' }}">
                                    {{ strtoupper($entry->classification) }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5 text-gray-500 text-xs">{{ $entry->issue_date ? \Carbon\Carbon::parse($entry->issue_date)->format('M d, Y') : '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-6 py-10 text-center text-gray-400 italic text-sm">— No RegSPI records found —</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($rows->count())
                    <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                        <tr>
                            <td colspan="8" class="px-3 py-2.5 text-right text-xs font-bold uppercase tracking-widest text-gray-500">Page Total</td>
                            <td class="px-3 py-2.5 text-right font-extrabold text-[#1a2c5b]">PHP {{ number_format((float) $totalCost, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($rows->hasPages())
            <div class="px-5 py-3 border-t border-gray-200 bg-gray-50">
                {{ $rows->links() }}
            </div>
            @endif
        </div>

        {{-- Footer Document Certification --}}
        <div class="text-center text-[11px] text-gray-400 border-t border-gray-200 pt-4">
            Generated by PGSO Property Management System — Provincial Government of Surigao Del Norte &mdash; {{ now()->format('F d, Y') }}
        </div>

    </div>
</div>
@endsection
