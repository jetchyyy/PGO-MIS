@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Physical Count of Semi-Expendable</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Semi-Expendable Property Items (SPI)</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office — Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('reports.index') }}" class="hover:text-[#1a2c5b]">Reports</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">Semi Count</span>
            </div>
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Report
            </button>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-5">

        {{-- Summary Statistics --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#1a2c5b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ $rows->count() }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Office Groups</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#c8a84b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ number_format($rows->sum('count')) }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Total Transactions</p>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Semi-Expendable Property — Summary by Office</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">No.</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Office / Department</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Fund Cluster</th>
                            <th class="px-4 py-2.5 text-right text-[11px] font-bold uppercase tracking-widest text-gray-600">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                            <td class="px-4 py-2.5 text-gray-400 font-semibold text-xs border-r border-gray-100">{{ $i + 1 }}</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-800 border-r border-gray-100">{{ $row->office->name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-600 border-r border-gray-100">{{ $row->fundCluster->code ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-right font-bold text-gray-800">{{ number_format($row->count ?? 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic text-sm">— No records found —</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($rows->count() > 0)
                    <tfoot class="border-t-2 border-[#1a2c5b] bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-2.5 text-sm font-bold text-[#1a2c5b] uppercase tracking-widest">Grand Total</td>
                            <td class="px-4 py-2.5 text-right font-bold text-[#1a2c5b] text-base">{{ number_format($rows->sum('count')) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Footer Document Certification --}}
        <div class="text-center text-[11px] text-gray-400 border-t border-gray-200 pt-4">
            Generated by PGSO Property Management System — Provincial Government of Surigao Del Norte &mdash; {{ now()->format('F d, Y') }}
        </div>

    </div>
</div>
@endsection
