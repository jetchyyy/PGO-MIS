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
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('reports.index') }}" class="hover:text-[#1a2c5b]">Reports</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">RegSPI Masterlist</span>
            </div>
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Export PDF
            </button>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-5">

        {{-- Summary Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#1a2c5b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ $rows->total() }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Total Records</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-emerald-600"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ $rows->where('status','issued')->count() }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Issued</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#c8a84b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ $rows->where('status','approved')->count() }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Pending / Approved</p>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b] flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Registry of Semi-Expendable Property Issued (RegSPI)</h2>
                <span class="text-blue-200 text-[11px]">Page {{ $rows->currentPage() }} of {{ $rows->lastPage() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">No.</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Transaction No.</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Accountable Officer</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Office / Department</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Date</th>
                            <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-widest text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                        @php $status = $row->status ?? 'draft'; @endphp
                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                            <td class="px-4 py-2.5 text-gray-400 font-semibold text-xs border-r border-gray-100">{{ $rows->firstItem() + $i }}</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-800 border-r border-gray-100 font-mono text-xs">{{ $row->transaction_no ?? $row->reference_no ?? ('SPI-' . str_pad($row->id, 5, '0', STR_PAD_LEFT)) }}</td>
                            <td class="px-4 py-2.5 text-gray-700 border-r border-gray-100">{{ $row->employee->name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-600 border-r border-gray-100">{{ $row->office->name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs border-r border-gray-100">{{ $row->transaction_date ? \Carbon\Carbon::parse($row->transaction_date)->format('M d, Y') : '—' }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border
                                    @if($status === 'issued') border-emerald-300 bg-emerald-50 text-emerald-700
                                    @elseif($status === 'approved') border-blue-300 bg-blue-50 text-blue-700
                                    @elseif($status === 'pending') border-amber-300 bg-amber-50 text-amber-700
                                    @else border-gray-300 bg-gray-100 text-gray-500
                                    @endif">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic text-sm">— No RegSPI records found —</td>
                        </tr>
                        @endforelse
                    </tbody>
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
