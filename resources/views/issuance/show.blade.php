@extends('layouts.app')

@section('content')
@php
    $statusColor = match ($issuance->status) {
        'draft' => 'bg-gray-100 text-gray-600 border border-gray-300',
        'submitted' => 'bg-amber-100 text-amber-700 border border-amber-300',
        'approved' => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
        'issued' => 'bg-blue-100 text-blue-700 border border-blue-300',
        default => 'bg-gray-100 text-gray-600 border border-gray-300',
    };
    $documentTabs = $generatedDocuments ?? [];
    $activeDocumentKey = $documentTabs[0]['key'] ?? null;
@endphp
<div class="min-h-screen bg-gray-100" x-data="@js([
    'activeDocument' => $activeDocumentKey,
    'documents' => $documentTabs,
])">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Document Issuance</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Issuance Record: {{ $issuance->control_no }}</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('issuance.index') }}" class="hover:text-[#1a2c5b]">Issuance</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">{{ $issuance->control_no }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded px-3 py-1 text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">
                    {{ ucfirst($issuance->status) }}
                </span>
                @if(in_array($issuance->status, ['draft', 'returned'], true))
                <form method="POST" action="{{ route('issuance.submit', $issuance) }}">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        {{ $issuance->status === 'returned' ? 'Resubmit for Approval' : 'Submit for Approval' }}
                    </button>
                </form>
                @endif
                @if(in_array($issuance->status, ['approved', 'issued']))
                @can('transfer.manage')
                <a href="{{ route('transfer.create', ['issuance_id' => $issuance->id]) }}"
                   class="inline-flex items-center gap-1.5 rounded border border-indigo-300 bg-indigo-50 px-4 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-8 4h5M3 6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6z"/></svg>
                    Create Transfer
                </a>
                @endcan
                @can('return.manage')
                @if(($returnableCount ?? 0) > 0)
                <a href="{{ route('returns.create', ['issuance_id' => $issuance->id]) }}"
                   class="inline-flex items-center gap-1.5 rounded border border-amber-300 bg-amber-50 px-4 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10H7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 12 2 2 4-4"/></svg>
                    Create Return
                </a>
                @endif
                @endcan
                <a href="{{ route('issuance.print', [$issuance, 'sticker']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-emerald-300 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Sticker
                </a>
                @if($issuance->document_type === 'PAR')
                <a href="{{ route('issuance.print', [$issuance, 'par']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print PAR
                </a>
                <a href="{{ route('issuance.print', [$issuance, 'property_card']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M8 6h.01M8 18h.01"/></svg>
                    Property Card
                </a>
                @endif
                @if(in_array($issuance->document_type, ['ICS-SPLV', 'ICS-SPHV']))
                <a href="{{ route('issuance.print', [$issuance, 'ics']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print ICS
                </a>
                <a href="{{ route('issuance.print', [$issuance, 'semi_property_card']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M8 6h.01M8 18h.01"/></svg>
                    Semi-Property Card
                </a>
                <a href="{{ route('issuance.print', [$issuance, 'regspi']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                    Print RegSPI
                </a>
                @endif
                @endif
            </div>
        </div>
    </div>

    @if(session('status'))
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 font-medium">{{ session('status') }}</div>
    </div>
    @endif

    <div class="w-full px-4 pt-5 pb-8 sm:px-6 lg:px-8">
        @if(in_array($issuance->status, ['approved', 'issued']))
        <div class="mb-4">
            @include('partials.document-tabs', ['documents' => $generatedDocuments, 'record' => $issuance, 'routeName' => 'issuance.print'])
        </div>
        @endif

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Transaction Details</h2>
                    </div>
                    <dl class="divide-y divide-gray-100 text-sm">
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Control No.</dt>
                            <dd class="font-bold text-gray-800">{{ $issuance->control_no }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Document Type</dt>
                            <dd class="font-bold text-gray-800">{{ $issuance->document_type }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Asset Type</dt>
                            <dd class="text-gray-700 capitalize">{{ str_replace('_', ' ', $issuance->asset_type) }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Entity Name</dt>
                            <dd class="text-gray-700">{{ $issuance->entity_name }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Fund Cluster</dt>
                            <dd class="text-gray-700">{{ $issuance->fundCluster->code ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Office</dt>
                            <dd class="text-gray-700">{{ $issuance->office->name ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Officer</dt>
                            <dd class="text-gray-700">{{ $issuance->employee->name ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Date</dt>
                            <dd class="text-gray-700">{{ $issuance->transaction_date ? \Carbon\Carbon::parse($issuance->transaction_date)->format('M d, Y') : '-' }}</dd>
                        </div>
                        @if($issuance->reference_no)
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between">
                            <dt class="font-medium text-gray-500">Reference No.</dt>
                            <dd class="text-gray-700">{{ $issuance->reference_no }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                @if($issuance->approvals->count())
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Approvals</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($issuance->approvals as $approval)
                        <div class="flex items-center justify-between px-4 py-2.5 text-sm">
                            <span class="text-gray-500 text-xs">{{ $approval->created_at->format('M d, Y') }}</span>
                            <span class="font-semibold text-xs {{ $approval->status === 'approved' ? 'text-emerald-600' : ($approval->status === 'rejected' ? 'text-rose-600' : 'text-amber-600') }}">
                                {{ ucfirst($approval->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Line Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-50">
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Description</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Qty</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Unit</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Unit Cost</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Total</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Est. Useful Life</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Classification</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($issuance->lines as $i => $line)
                                <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                                    <td class="px-5 py-3 font-medium text-gray-800">
                                        {{ $line->description }}
                                        @if($line->property_no)
                                        <div class="text-xs text-gray-400 mt-0.5">Prop No: {{ $line->property_no }}</div>
                                        @endif
                                        @if($line->date_acquired)
                                        <div class="text-xs text-gray-400">Acquired: {{ \Carbon\Carbon::parse($line->date_acquired)->format('M d, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center text-gray-700">{{ $line->quantity }}</td>
                                    <td class="px-5 py-3 text-center text-gray-500">{{ $line->unit }}</td>
                                    <td class="px-5 py-3 text-right text-gray-700">{{ number_format($line->unit_cost, 2) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-800">{{ number_format($line->total_cost, 2) }}</td>
                                    <td class="px-5 py-3 text-center text-gray-500 text-xs">{{ $line->estimated_useful_life ?? '-' }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase border {{ $line->classification === 'ppe' ? 'border-blue-200 bg-blue-50 text-blue-700' : ($line->classification === 'sphv' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-gray-200 bg-gray-50 text-gray-600') }}">
                                            {{ strtoupper($line->classification) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-gray-500">Grand Total</td>
                                    <td class="px-5 py-3 text-right font-extrabold text-[#1a2c5b]">PHP {{ number_format($issuance->lines->sum('total_cost'), 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="{{ route('issuance.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#1a2c5b] transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Issuance List
            </a>
        </div>
    </div>
</div>
@endsection
