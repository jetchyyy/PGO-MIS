@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Transfer</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Transfer Transactions</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb & Actions --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">Transfer</span>
            </div>
            <a href="{{ route('transfer.create') }}"
               class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Transfer
            </a>
        </div>
    </div>

    {{-- Table: Full-width seamless --}}
    <div class="w-full px-4 pt-4 pb-8 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1a2c5b]">
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Control #</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">From</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">To</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($transfers as $i => $row)
                    <tr class="border-b border-gray-200 hover:bg-blue-50/50 transition {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                        <td class="px-5 py-4 font-mono font-bold text-[#1a2c5b] text-base">{{ $row->control_no }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $row->fromEmployee->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $row->toEmployee->name ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border
                                {{ $row->status === 'approved' ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : ($row->status === 'draft' ? 'border-gray-300 bg-gray-100 text-gray-500' : 'border-amber-300 bg-amber-50 text-amber-700') }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('transfer.show', $row) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#1a2c5b] hover:text-[#253d82]">
                                View <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400 italic">&mdash; No transfer records found &mdash;</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($transfers->hasPages())
        <div class="mt-4 px-2">{{ $transfers->links() }}</div>
        @endif
    </div>

</div>
@endsection
