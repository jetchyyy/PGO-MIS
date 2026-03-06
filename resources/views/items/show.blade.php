@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Inventory Management</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">{{ $item->name }}</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('items.print_qr', $item) }}" target="_blank"
                   class="inline-flex items-center gap-1 rounded border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                    QR Label
                </a>
                <a href="{{ route('items.edit', $item) }}"
                   class="inline-flex items-center gap-1 rounded border border-[#c8a84b] bg-[#c8a84b]/10 px-3 py-1.5 text-xs font-semibold text-[#c8a84b] hover:bg-[#c8a84b]/20 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('items.index') }}"
                   class="inline-flex items-center gap-1 rounded border border-gray-400 bg-white/10 px-3 py-1.5 text-xs font-semibold text-gray-200 hover:bg-white/20 transition">
                    &larr; Back
                </a>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('settings.index') }}" class="hover:text-[#1a2c5b]">Settings</a>
            <span>&rsaquo;</span>
            <a href="{{ route('items.index') }}" class="hover:text-[#1a2c5b]">Items</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">{{ Str::limit($item->name, 40) }}</span>
        </div>
    </div>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-4 space-y-4">

        {{-- Item Details Card --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm">
            <div class="border-b border-gray-100 px-4 py-3 bg-gray-50">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Item Details</h3>
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Name</label>
                    <p class="font-semibold text-gray-800">{{ $item->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Category</label>
                    <p class="text-gray-700">{{ $item->category ?? '—' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Unit</label>
                    <p class="text-gray-700">{{ $item->unit }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Unit Cost</label>
                    <p class="font-mono font-semibold text-gray-800">₱{{ number_format($item->unit_cost, 2) }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Classification</label>
                    @php
                        $cls = match($item->classification) {
                            'ppe' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'sphv' => 'bg-amber-100 text-amber-700 border-amber-200',
                            default => 'bg-gray-100 text-gray-600 border-gray-200',
                        };
                    @endphp
                    <span class="inline-flex rounded px-2 py-0.5 text-[10px] font-bold uppercase border {{ $cls }}">
                        {{ strtoupper($item->classification) }}
                    </span>
                </div>
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Status</label>
                    @if($item->is_active)
                    <span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[10px] font-bold">Active</span>
                    @else
                    <span class="inline-flex rounded-full bg-gray-100 text-gray-500 px-2 py-0.5 text-[10px] font-bold">Inactive</span>
                    @endif
                </div>
                @if($item->estimated_useful_life)
                <div>
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Useful Life</label>
                    <p class="text-gray-700">{{ $item->estimated_useful_life }}</p>
                </div>
                @endif
                @if($item->description)
                <div class="md:col-span-3">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Description</label>
                    <p class="text-gray-600 text-xs leading-relaxed">{{ $item->description }}</p>
                </div>
                @endif
                <div class="md:col-span-3 pt-2">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">QR Code Preview</label>
                    <div class="mt-2 flex items-center gap-4">
                        <img src="{{ $item->qrDataUri(200) }}" alt="Item QR" class="h-24 w-24 border border-gray-300 p-1 bg-white">
                        <div class="text-xs text-gray-500">
                            <p>Scan to view encoded catalog item details.</p>
                            <a href="{{ route('items.print_qr', $item) }}" target="_blank" class="text-emerald-700 hover:underline font-semibold">Print QR Label</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalIssuedQty }}</p>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Active Issued Qty</p>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-[#1a2c5b]">{{ $issuanceLines->count() }}</p>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Issuance Lines</p>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-amber-600">{{ $transferLines->count() }}</p>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Transfers</p>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-red-600">{{ $disposalLines->count() }}</p>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Disposals</p>
            </div>
        </div>

        {{-- Issuance History --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-4 py-3 bg-gray-50 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Issuance History (PAR / ICS)</h3>
                <span class="text-[10px] bg-blue-100 text-blue-700 rounded px-2 py-0.5 font-bold">{{ $issuanceLines->count() }} Records</span>
            </div>
            @if($issuanceLines->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Control No.</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Date</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Description</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Property No.</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Accountable Person</th>
                            <th class="px-3 py-2 text-center text-[10px] font-bold uppercase text-gray-500">Qty</th>
                            <th class="px-3 py-2 text-right text-[10px] font-bold uppercase text-gray-500">Total Cost</th>
                            <th class="px-3 py-2 text-center text-[10px] font-bold uppercase text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($issuanceLines as $line)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-3 py-2">
                                <a href="{{ route('issuance.show', $line->transaction) }}" class="text-blue-600 hover:underline font-semibold">
                                    {{ $line->transaction->control_no }}
                                </a>
                            </td>
                            <td class="px-3 py-2 text-gray-600">{{ optional($line->date_acquired)->format('M d, Y') ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-700 max-w-xs truncate">{{ Str::limit($line->description, 60) }}</td>
                            <td class="px-3 py-2 font-mono text-gray-600">{{ $line->property_no ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $line->transaction->employee->name ?? '—' }}</td>
                            <td class="px-3 py-2 text-center font-semibold">{{ $line->quantity }}</td>
                            <td class="px-3 py-2 text-right font-mono">₱{{ number_format($line->total_cost, 2) }}</td>
                            <td class="px-3 py-2 text-center">
                                @php
                                    $stCls = match($line->item_status) {
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'transferred' => 'bg-amber-100 text-amber-700',
                                        'disposed' => 'bg-red-100 text-red-700',
                                        'returned' => 'bg-gray-100 text-gray-600',
                                        default => 'bg-gray-100 text-gray-500',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold {{ $stCls }}">
                                    {{ ucfirst($line->item_status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-4 py-6 text-center text-gray-400 text-xs">No issuance records found for this item.</div>
            @endif
        </div>

        {{-- Transfer History --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-4 py-3 bg-gray-50 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Transfer History (PTR / ITR)</h3>
                <span class="text-[10px] bg-amber-100 text-amber-700 rounded px-2 py-0.5 font-bold">{{ $transferLines->count() }} Records</span>
            </div>
            @if($transferLines->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Control No.</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Transfer Date</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Description</th>
                            <th class="px-3 py-2 text-center text-[10px] font-bold uppercase text-gray-500">Qty</th>
                            <th class="px-3 py-2 text-right text-[10px] font-bold uppercase text-gray-500">Amount</th>
                            <th class="px-3 py-2 text-center text-[10px] font-bold uppercase text-gray-500">Condition</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($transferLines as $tLine)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-3 py-2 font-semibold text-amber-700">{{ $tLine->transfer->control_no ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ optional($tLine->transfer->transfer_date ?? null)->format('M d, Y') ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-700 max-w-xs truncate">{{ Str::limit($tLine->description, 60) }}</td>
                            <td class="px-3 py-2 text-center font-semibold">{{ $tLine->quantity }}</td>
                            <td class="px-3 py-2 text-right font-mono">₱{{ number_format($tLine->amount, 2) }}</td>
                            <td class="px-3 py-2 text-center text-gray-600">{{ $tLine->condition }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-4 py-6 text-center text-gray-400 text-xs">No transfer records found for this item.</div>
            @endif
        </div>

        {{-- Disposal History --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-4 py-3 bg-gray-50 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Disposal History (IIRUP / RRSEP)</h3>
                <span class="text-[10px] bg-red-100 text-red-700 rounded px-2 py-0.5 font-bold">{{ $disposalLines->count() }} Records</span>
            </div>
            @if($disposalLines->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Control No.</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Disposal Date</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Particulars</th>
                            <th class="px-3 py-2 text-left text-[10px] font-bold uppercase text-gray-500">Property No.</th>
                            <th class="px-3 py-2 text-center text-[10px] font-bold uppercase text-gray-500">Qty</th>
                            <th class="px-3 py-2 text-right text-[10px] font-bold uppercase text-gray-500">Total Cost</th>
                            <th class="px-3 py-2 text-right text-[10px] font-bold uppercase text-gray-500">Carrying Amt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($disposalLines as $dLine)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-3 py-2 font-semibold text-red-700">{{ $dLine->disposal->control_no ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ optional($dLine->disposal->disposal_date ?? null)->format('M d, Y') ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-700 max-w-xs truncate">{{ Str::limit($dLine->particulars, 60) }}</td>
                            <td class="px-3 py-2 font-mono text-gray-600">{{ $dLine->property_no ?? '—' }}</td>
                            <td class="px-3 py-2 text-center font-semibold">{{ $dLine->quantity }}</td>
                            <td class="px-3 py-2 text-right font-mono">₱{{ number_format($dLine->total_cost, 2) }}</td>
                            <td class="px-3 py-2 text-right font-mono">₱{{ number_format($dLine->carrying_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-4 py-6 text-center text-gray-400 text-xs">No disposal records found for this item.</div>
            @endif
        </div>

    </div>
</div>
@endsection
