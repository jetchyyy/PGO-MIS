@extends('layouts.app')

@section('content')
@php
    $statusColor = match($disposal->status) {
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
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Disposal</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Disposal Record: {{ $disposal->control_no }}</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('disposal.index') }}" class="hover:text-[#1a2c5b]">Disposal</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">{{ $disposal->control_no }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded px-3 py-1 text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">
                    {{ ucfirst($disposal->status) }}
                </span>
                @if(in_array($disposal->status, ['draft', 'returned'], true))
                <form method="POST" action="{{ route('disposal.submit', $disposal) }}">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        {{ $disposal->status === 'returned' ? 'Resubmit for Approval' : 'Submit for Approval' }}
                    </button>
                </form>
                @endif
                @if(in_array($disposal->status, ['approved', 'issued']))
                <a href="{{ route('disposal.print', [$disposal, strtolower($disposal->document_type)]) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Print {{ $disposal->document_type }}
                </a>
                <a href="{{ route('disposal.print', [$disposal, 'wmr']) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded border border-emerald-300 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                    Print WMR
                </a>
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
        @if(in_array($disposal->status, ['approved', 'issued']))
        <div class="mb-4">
            @include('partials.document-tabs', ['documents' => $generatedDocuments, 'record' => $disposal, 'routeName' => 'disposal.print'])
        </div>
        @endif

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Disposal Details</h2>
                    </div>
                    <dl class="divide-y divide-gray-100 text-sm">
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Control No.</dt><dd class="font-bold text-gray-800">{{ $disposal->control_no }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Document Type</dt><dd class="font-semibold text-gray-700">{{ $disposal->document_type }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Return Record</dt><dd class="text-gray-700">@if($disposal->propertyReturn)<a href="{{ route('returns.show', $disposal->propertyReturn) }}" class="font-semibold text-[#1a2c5b] hover:underline">{{ $disposal->propertyReturn->control_no }}</a>@else-@endif</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Prior Form</dt><dd class="font-semibold text-gray-700">{{ $disposal->prerequisite_form_type ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Prior Form No.</dt><dd class="text-gray-700">{{ $disposal->prerequisite_form_no ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Prior Form Date</dt><dd class="text-gray-700">{{ $disposal->prerequisite_form_date?->format('M d, Y') ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Disposal Type</dt><dd class="text-gray-700 capitalize">{{ str_replace('_', ' ', $disposal->disposal_type) }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Officer</dt><dd class="text-gray-700">{{ $disposal->employee->name ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Designation</dt><dd class="text-gray-700">{{ $disposal->designation ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Station</dt><dd class="text-gray-700">{{ $disposal->station ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Disposal Date</dt><dd class="text-gray-700">{{ $disposal->disposal_date ? \Carbon\Carbon::parse($disposal->disposal_date)->format('M d, Y') : '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Entity</dt><dd class="text-gray-700">{{ $disposal->entity_name ?? '-' }}</dd></div>
                        <div class="flex flex-col gap-1 px-4 py-2.5 sm:flex-row sm:items-start sm:justify-between"><dt class="font-medium text-gray-500">Fund Cluster</dt><dd class="text-gray-700">{{ $disposal->fundCluster->code ?? '-' }}</dd></div>
                    </dl>
                </div>

                @if(!empty($generatedDocuments))
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Generated Control Numbers</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($generatedDocuments as $document)
                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 text-sm">
                            <span class="font-semibold text-gray-600">{{ $document['code'] }}</span>
                            <span class="font-mono text-xs font-bold text-[#1a2c5b]">{{ $document['control_no'] }}</span>
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
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Particulars</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Property No.</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Qty</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Unit Cost</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Total Cost</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Carrying Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($disposal->lines as $i => $line)
                                <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $line->particulars }}</td>
                                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $line->property_no ?? '-' }}</td>
                                    <td class="px-5 py-3 text-center text-gray-700">{{ $line->quantity }}</td>
                                    <td class="px-5 py-3 text-right text-gray-700">{{ number_format($line->unit_cost, 2) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-800">{{ number_format($line->total_cost, 2) }}</td>
                                    <td class="px-5 py-3 text-right text-gray-700">{{ number_format($line->carrying_amount, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-gray-500">Grand Total</td>
                                    <td class="px-5 py-3 text-right font-extrabold text-[#1a2c5b]">PHP {{ number_format($disposal->lines->sum('total_cost'), 2) }}</td>
                                    <td class="px-5 py-3 text-right font-extrabold text-gray-700">PHP {{ number_format($disposal->lines->sum('carrying_amount'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="{{ route('disposal.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#1a2c5b] transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Disposal List
            </a>
        </div>
    </div>
</div>
@endsection
