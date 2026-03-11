@extends('layouts.app')

@section('content')
@php
    $statusColor = match($returnRecord->status) {
        'draft' => 'bg-gray-100 text-gray-600 border border-gray-300',
        'submitted' => 'bg-amber-100 text-amber-700 border border-amber-300',
        'approved' => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
        'issued' => 'bg-blue-100 text-blue-700 border border-blue-300',
        default => 'bg-gray-100 text-gray-600 border border-gray-300',
    };
@endphp
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Return</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Return Record: {{ $returnRecord->control_no }}</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('returns.index') }}" class="hover:text-[#1a2c5b]">Returns</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">{{ $returnRecord->control_no }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded px-3 py-1 text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">
                    {{ ucfirst($returnRecord->status) }}
                </span>
                @if(in_array($returnRecord->status, ['draft', 'returned'], true))
                <form method="POST" action="{{ route('returns.submit', $returnRecord) }}">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                        {{ $returnRecord->status === 'returned' ? 'Resubmit for Approval' : 'Submit for Approval' }}
                    </button>
                </form>
                @endif
                @if(in_array($returnRecord->status, ['approved', 'issued']))
                <a href="{{ route('returns.print', [$returnRecord, strtolower($returnRecord->document_type)]) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Print {{ $returnRecord->document_type }}
                </a>
                @if(!$returnRecord->disposal)
                <a href="{{ route('disposal.create', ['return_id' => $returnRecord->id]) }}"
                   class="inline-flex items-center gap-1.5 rounded border border-emerald-300 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                    Create Disposal
                </a>
                @endif
                @endif
            </div>
        </div>
    </div>

    <div class="w-full px-4 pt-5 pb-8 sm:px-6 lg:px-8">
        @if(in_array($returnRecord->status, ['approved', 'issued']))
        <div class="mb-4">
            @include('partials.document-tabs', ['documents' => $generatedDocuments, 'record' => $returnRecord, 'routeName' => 'returns.print'])
        </div>
        @endif

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Return Details</h2>
                    </div>
                    <dl class="divide-y divide-gray-100 text-sm">
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Control No.</dt><dd class="font-bold text-gray-800">{{ $returnRecord->control_no }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Document Type</dt><dd class="font-semibold text-gray-700">{{ $returnRecord->document_type }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Officer</dt><dd class="text-gray-700">{{ $returnRecord->employee->name ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Designation</dt><dd class="text-gray-700">{{ $returnRecord->designation ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Station</dt><dd class="text-gray-700">{{ $returnRecord->station ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Return Date</dt><dd class="text-gray-700">{{ $returnRecord->return_date?->format('M d, Y') ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Reason</dt><dd class="text-gray-700">{{ $returnRecord->return_reason ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:justify-between"><dt class="font-medium text-gray-500">Fund Cluster</dt><dd class="text-gray-700">{{ $returnRecord->fundCluster->code ?? '-' }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Returned Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-50">
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Particulars</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Property No.</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Qty</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Unit Cost</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Total Cost</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Condition</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($returnRecord->lines as $i => $line)
                                <tr class="border-b border-gray-100 {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $line->particulars }}</td>
                                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $line->property_no ?? '-' }}</td>
                                    <td class="px-5 py-3 text-center text-gray-700">{{ $line->quantity }}</td>
                                    <td class="px-5 py-3 text-right text-gray-700">{{ number_format((float) $line->unit_cost, 2) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-800">{{ number_format((float) $line->total_cost, 2) }}</td>
                                    <td class="px-5 py-3 text-gray-700">{{ $line->condition ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-gray-500">Grand Total</td>
                                    <td class="px-5 py-3 text-right font-extrabold text-[#1a2c5b]">PHP {{ number_format((float) $returnRecord->lines->sum('total_cost'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
