@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Transfer</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Transfer Record: {{ $transfer->control_no }}</p>
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
                <a href="{{ route('transfer.index') }}" class="hover:text-[#1a2c5b]">Transfer</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">{{ $transfer->control_no }}</span>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $statusColor = match($transfer->status) {
                        'draft'     => 'bg-gray-100 text-gray-600 border border-gray-300',
                        'submitted' => 'bg-amber-100 text-amber-700 border border-amber-300',
                        'approved'  => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                        'issued'    => 'bg-blue-100 text-blue-700 border border-blue-300',
                        default     => 'bg-gray-100 text-gray-600 border border-gray-300',
                    };
                @endphp
                <span class="inline-flex items-center rounded px-3 py-1 text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">
                    {{ ucfirst($transfer->status) }}
                </span>
                @if($transfer->status === 'draft')
                <form method="POST" action="{{ route('transfer.submit', $transfer) }}">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Submit for Approval
                    </button>
                </form>
                @endif
                @if(in_array($transfer->status, ['approved', 'issued']))
                <a href="{{ route('transfer.print', [$transfer, 'sticker']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-emerald-300 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Print Sticker
                </a>
                <a href="{{ route('transfer.print', [$transfer, 'ptr']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print PTR
                </a>
                <a href="{{ route('transfer.print', [$transfer, 'itr']) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded border border-gray-300 bg-white px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print ITR
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Session Status --}}
    @if(session('status'))
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 font-medium">{{ session('status') }}</div>
    </div>
    @endif

    {{-- Content --}}
    <div class="w-full px-4 pt-5 pb-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">

            {{-- Details Sidebar --}}
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Transfer Details</h2>
                    </div>
                    <dl class="divide-y divide-gray-100 text-sm">
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">Control No.</dt>
                            <dd class="font-bold text-gray-800">{{ $transfer->control_no }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">Transfer Type</dt>
                            <dd class="text-gray-700 capitalize">{{ str_replace('_', ' ', $transfer->transfer_type) }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">Document</dt>
                            <dd class="text-gray-700 font-semibold">{{ $transfer->document_type }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">From</dt>
                            <dd class="text-gray-700">{{ $transfer->fromEmployee->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">To</dt>
                            <dd class="text-gray-700">{{ $transfer->toEmployee->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">Transfer Date</dt>
                            <dd class="text-gray-700">{{ $transfer->transfer_date ? \Carbon\Carbon::parse($transfer->transfer_date)->format('M d, Y') : '—' }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">Entity</dt>
                            <dd class="text-gray-700">{{ $transfer->entity_name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between px-4 py-2.5">
                            <dt class="font-medium text-gray-500">Fund Cluster</dt>
                            <dd class="text-gray-700">{{ $transfer->fundCluster->code ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                @if($transfer->approvals->count())
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Approvals</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($transfer->approvals as $approval)
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

            {{-- Line Items --}}
            <div class="lg:col-span-3">
                <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Line Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-50">
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">PAR/ICS Ref</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Description</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Qty</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Unit</th>
                                    <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Amount</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Condition</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($transfer->lines as $i => $line)
                                <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $line->reference_no ?? '—' }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $line->description }}</td>
                                    <td class="px-5 py-3 text-center text-gray-700">{{ $line->quantity }}</td>
                                    <td class="px-5 py-3 text-gray-500">{{ $line->unit ?? '—' }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-800">{{ number_format($line->amount, 2) }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $line->condition ?? '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-gray-300 bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-widest text-gray-500">Total Amount</td>
                                    <td class="px-5 py-3 text-right font-extrabold text-[#1a2c5b]">₱{{ number_format($transfer->lines->sum('amount'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-5">
            <a href="{{ route('transfer.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#1a2c5b] transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Transfer List
            </a>
        </div>
    </div>

</div>
@endsection
