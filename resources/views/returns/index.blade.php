@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Return</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Return Records</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">Returns</span>
            </div>
            <a href="{{ route('returns.create') }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#253d82] sm:w-auto">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Return
            </a>
        </div>
        <div class="w-full px-4 pb-3 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2">
                @php($tabs = ['all' => 'All Returns', 'prs' => 'PRS', 'rrsp' => 'RRSP'])
                @foreach($tabs as $tabKey => $tabLabel)
                <a href="{{ route('returns.index', array_filter(['doc' => $tabKey !== 'all' ? $tabKey : null])) }}"
                   class="rounded border px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider transition {{ ($documentTab ?? 'all') === $tabKey ? 'border-[#1a2c5b] bg-[#1a2c5b] text-white' : 'border-gray-300 bg-white text-gray-600 hover:bg-gray-100' }}">
                    {{ $tabLabel }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full px-4 pt-4 pb-8 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1a2c5b]">
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Control #</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Officer</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Document</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Disposal</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($returns as $i => $row)
                    @php($primaryDocument = $row->documentControls->firstWhere('template_name', strtolower($row->document_type)))
                    <tr class="border-b border-gray-200 hover:bg-blue-50/50 transition {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                        <td class="px-5 py-4 font-mono font-bold text-[#1a2c5b] text-base">{{ $primaryDocument?->control_no ?? $row->control_no }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $row->employee->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-700">{{ $row->document_type }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border
                                {{ $row->status === 'approved' ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : ($row->status === 'draft' ? 'border-gray-300 bg-gray-100 text-gray-500' : 'border-amber-300 bg-amber-50 text-amber-700') }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-600 text-xs">
                            {{ $row->disposal?->control_no ?? 'Not yet linked' }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex items-center gap-3">
                                @if(in_array($row->status, ['approved', 'issued'], true) && !$row->disposal)
                                <a href="{{ route('disposal.create', ['return_id' => $row->id]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                                    Create Disposal
                                </a>
                                @endif
                                <a href="{{ route('returns.show', $row) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#1a2c5b] hover:text-[#253d82]">
                                    View <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400 italic">&mdash; No return records found &mdash;</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())
        <div class="mt-4 px-2">{{ $returns->links() }}</div>
        @endif
    </div>
</div>
@endsection
