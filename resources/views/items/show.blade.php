@extends('layouts.app')

@section('content')
@php
    $statusBadgeClass = match (strtolower($currentLifecycleStatus)) {
        'disposed' => 'bg-red-500 text-white',
        'transferred' => 'bg-amber-500 text-white',
        'issued' => 'bg-emerald-500 text-white',
        default => 'bg-slate-500 text-white',
    };

    $statusPillClass = match (strtolower($currentLifecycleStatus)) {
        'disposed' => 'bg-red-100 text-red-700 border-red-200',
        'transferred' => 'bg-amber-100 text-amber-700 border-amber-200',
        'issued' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $classificationClass = match($item->classification) {
        'ppe' => 'bg-blue-100 text-blue-700 border-blue-200',
        'sphv' => 'bg-amber-100 text-amber-700 border-amber-200',
        default => 'bg-gray-100 text-gray-600 border-gray-200',
    };
@endphp

<div class="min-h-screen bg-slate-100">
    <div class="bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900 shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-300">Item History</p>
                <p class="mt-1 text-xl font-bold text-white">{{ $item->name }}</p>
                <p class="text-[11px] text-slate-300">Issuance, transfer, and disposal timeline for this catalog item</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start lg:self-auto">
                <span class="inline-flex items-center rounded-sm px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $statusBadgeClass }}">
                    {{ $currentLifecycleStatus }}
                </span>
                <a href="{{ route('items.print_qr', $item) }}" target="_blank"
                   class="inline-flex items-center rounded border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                    QR Label
                </a>
                <a href="{{ route('items.edit', $item) }}"
                   class="inline-flex items-center rounded border border-amber-300 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">
                    Edit
                </a>
                <a href="{{ route('items.index') }}"
                   class="inline-flex items-center rounded border border-slate-300 bg-white/10 px-3 py-1.5 text-xs font-semibold text-slate-100 transition hover:bg-white/20">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-900">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('settings.index') }}" class="hover:text-slate-900">Settings</a>
            <span>&rsaquo;</span>
            <a href="{{ route('items.index') }}" class="hover:text-slate-900">Items</a>
            <span>&rsaquo;</span>
            <span class="font-semibold text-slate-900">{{ Str::limit($item->name, 40) }}</span>
        </div>
    </div>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.3fr,0.7fr]">
            <div class="rounded border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Item Details</h3>
                </div>
                <div class="grid grid-cols-1 gap-4 p-4 text-sm md:grid-cols-3">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Name</p>
                        <p class="font-semibold text-slate-800">{{ $item->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Category</p>
                        <p class="text-slate-700">{{ $item->category ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Unit</p>
                        <p class="text-slate-700">{{ $item->unit }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Unit Cost</p>
                        <p class="font-mono font-semibold text-slate-800">PHP {{ number_format((float) $item->unit_cost, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Classification</p>
                        <span class="inline-flex rounded border px-2 py-0.5 text-[10px] font-bold uppercase {{ $classificationClass }}">
                            {{ strtoupper($item->classification) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Lifecycle Status</p>
                        <span class="inline-flex rounded border px-2 py-0.5 text-[10px] font-bold uppercase {{ $statusPillClass }}">
                            {{ $currentLifecycleStatus }}
                        </span>
                    </div>
                    @if($item->estimated_useful_life)
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Useful Life</p>
                        <p class="text-slate-700">{{ $item->estimated_useful_life }}</p>
                    </div>
                    @endif
                    @if($item->description)
                    <div class="md:col-span-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Description</p>
                        <p class="text-xs leading-relaxed text-slate-600">{{ $item->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="rounded border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">QR Preview</h3>
                </div>
                <div class="flex h-full flex-col items-center justify-center gap-4 p-5 text-center">
                    <img src="{{ $item->qrDataUri(200) }}" alt="Item QR" class="h-28 w-28 border border-slate-300 bg-white p-1">
                    <div class="space-y-1 text-xs text-slate-500">
                        <p>Scan to open the encoded item details.</p>
                        <a href="{{ route('items.print_qr', $item) }}" target="_blank" class="font-semibold text-emerald-700 hover:underline">
                            Print QR Label
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-2xl font-bold text-emerald-600">{{ $totalIssuedQty }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Active Issued Qty</p>
            </div>
            <div class="rounded border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-2xl font-bold text-rose-500">{{ $issuanceLines->count() }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Issuance Events</p>
            </div>
            <div class="rounded border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-2xl font-bold text-amber-500">{{ $transferLines->count() }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Transfer Events</p>
            </div>
            <div class="rounded border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-2xl font-bold text-cyan-600">{{ $disposalLines->count() }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Disposal Events</p>
            </div>
        </div>

        <div class="overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Item Breakdown</h3>
                    <p class="mt-1 text-xs text-slate-400">Current holder and movement path for each inventory unit of this catalog item</p>
                </div>
                <span class="rounded border border-slate-200 bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    {{ $holderBreakdown->count() }} Units
                </span>
            </div>

            @if($holderBreakdown->isNotEmpty())
            <div class="grid gap-4 p-4 xl:grid-cols-2">
                @foreach($holderBreakdown as $unit)
                @php
                    $unitStatusClass = match ($unit['status']) {
                        'issued' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'disposed' => 'bg-rose-100 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp
                <article class="rounded border border-slate-200 bg-slate-50/50 p-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Inventory Code</p>
                            <p class="font-mono text-sm font-semibold text-slate-800">{{ $unit['inventory_code'] ?: '-' }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $unit['description'] }}</p>
                        </div>
                        <span class="inline-flex rounded border px-2 py-0.5 text-[10px] font-bold uppercase {{ $unitStatusClass }}">
                            {{ str_replace('_', ' ', $unit['status']) }}
                        </span>
                    </div>

                    <div class="mt-4 grid gap-3 md:grid-cols-3">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Current Holder</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $unit['current_holder'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Location</p>
                            <p class="text-sm text-slate-700">{{ $unit['current_location'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Source Reference</p>
                            <p class="text-sm text-slate-700">{{ $unit['source_reference'] ?: '-' }}</p>
                        </div>
                    </div>

                    @if($unit['property_no'])
                    <div class="mt-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Property No.</p>
                        <p class="text-sm text-slate-700">{{ $unit['property_no'] }}</p>
                    </div>
                    @endif

                    <div class="mt-4 border-t border-slate-200 pt-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Movement Breakdown</p>
                        @if($unit['journey']->isNotEmpty())
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-600">
                            @foreach($unit['journey'] as $step)
                            <span class="rounded border border-slate-200 bg-white px-2 py-1">
                                {{ $step['label'] }}
                                @if($step['date'])
                                <span class="text-slate-400">({{ $step['date']->format('M d, Y') }})</span>
                                @endif
                            </span>
                            @unless($loop->last)
                            <span class="text-slate-300">→</span>
                            @endunless
                            @endforeach
                        </div>
                        @else
                        <p class="mt-2 text-xs text-slate-400">No movement records yet.</p>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="px-4 py-10 text-center text-sm text-slate-400">
                No inventory units have been created for this item yet.
            </div>
            @endif
        </div>

        <div class="overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Lifecycle Timeline</h3>
                    <p class="mt-1 text-xs text-slate-400">Chronological history from issuance to transfer to disposal</p>
                </div>
                <span class="rounded border border-slate-200 bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    {{ $historyEvents->count() }} Events
                </span>
            </div>

            @if($historyEvents->isNotEmpty())
            <div class="overflow-x-auto px-6 py-6">
                <div class="flex min-w-max items-start gap-10 pb-2">
                    @foreach($historyEvents as $event)
                    @php
                        $isLast = $loop->last;
                        $accent = match($event['accent']) {
                            'rose' => [
                                'panel' => 'bg-rose-500',
                                'title' => 'text-rose-600',
                                'meta' => 'border-rose-300 text-rose-700',
                                'line' => 'bg-rose-200',
                            ],
                            'amber' => [
                                'panel' => 'bg-amber-400',
                                'title' => 'text-amber-500',
                                'meta' => 'border-amber-300 text-amber-700',
                                'line' => 'bg-amber-200',
                            ],
                            default => [
                                'panel' => 'bg-cyan-500',
                                'title' => 'text-cyan-600',
                                'meta' => 'border-cyan-300 text-cyan-700',
                                'line' => 'bg-cyan-200',
                            ],
                        };
                    @endphp

                    <article class="relative w-[220px] shrink-0">
                        @unless($isLast)
                        <div class="absolute left-[108px] top-7 h-1 w-[calc(100%+2.5rem)] {{ $accent['line'] }}"></div>
                        @endunless

                        <div class="relative z-10">
                            <div class="flex h-16 w-16 items-center justify-center {{ $accent['panel'] }} text-white shadow-lg">
                                @if($event['icon'] === 'issued')
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 8v5"></path>
                                    <path d="M12 16h.01"></path>
                                </svg>
                                @elseif($event['icon'] === 'transferred')
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 7h11l-2-2"></path>
                                    <path d="M17 17H6l2 2"></path>
                                    <path d="M18 5v4"></path>
                                    <path d="M6 15v4"></path>
                                </svg>
                                @else
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 3h6"></path>
                                    <path d="M10 8v6"></path>
                                    <path d="M14 8v6"></path>
                                    <path d="M5 6h14"></path>
                                    <path d="M6 6l1 14h10l1-14"></path>
                                </svg>
                                @endif
                            </div>

                            <div class="mt-4 border-t-4 {{ $accent['line'] }} pt-2">
                                <div class="inline-flex items-center gap-1 border border-dashed px-3 py-2 text-xs font-bold {{ $accent['meta'] }}">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v5l3 2"></path>
                                    </svg>
                                    {{ $event['document_type'] ?: strtoupper($event['type']) }}
                                </div>
                            </div>

                            <h4 class="mt-3 text-3xl font-black leading-none {{ $accent['title'] }}">
                                {{ $event['title'] }}
                            </h4>

                            <div class="mt-2 space-y-1 text-sm text-slate-600">
                                <p class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v5l3 2"></path>
                                    </svg>
                                    {{ $event['event_time_label'] }}
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                        <path d="M16 3v4"></path>
                                        <path d="M8 3v4"></path>
                                        <path d="M3 11h18"></path>
                                    </svg>
                                    {{ $event['event_at'] ? $event['event_at']->format('M d, Y') : '-' }}
                                </p>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ $event['headline'] }}
                                </p>
                                @if($event['subheadline'])
                                <p class="text-xs text-slate-500">{{ $event['subheadline'] }}</p>
                                @endif
                                @if($event['control_no'])
                                <p class="text-xs text-slate-500">Ref: {{ $event['control_no'] }}</p>
                                @endif
                                <p class="text-xs text-slate-500">
                                    Qty {{ $event['quantity'] }} | PHP {{ number_format($event['amount'], 2) }}
                                </p>
                                @if($event['property_no'])
                                <p class="text-xs text-slate-500">Property No: {{ $event['property_no'] }}</p>
                                @endif
                                <p class="truncate text-xs italic text-slate-400">{{ $event['note'] }}</p>
                                @if($event['link'])
                                <a href="{{ $event['link'] }}" class="inline-flex items-center pt-1 text-xs font-semibold text-slate-700 hover:text-slate-900 hover:underline">
                                    Open record
                                </a>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @else
            <div class="px-4 py-10 text-center text-sm text-slate-400">
                No issuance, transfer, or disposal records found for this item yet.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
