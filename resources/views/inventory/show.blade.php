@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Inventory Unit: {{ $inventory->inventory_code }}</h1>
            <p class="text-sm text-gray-500">Single physical item record with QR tracking.</p>
        </div>
        <a href="{{ route('inventory.print', ['ids' => [$inventory->id]]) }}" class="rounded border border-[#1a2c5b] px-4 py-2 text-sm font-semibold text-[#1a2c5b] hover:bg-[#1a2c5b] hover:text-white">
            Print QR Label
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded border bg-white p-4 md:col-span-2">
            <dl class="grid gap-3 md:grid-cols-2">
                <div><dt class="text-xs uppercase text-gray-500">Description</dt><dd class="font-medium">{{ $inventory->description }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Status</dt><dd class="font-medium">{{ str_replace('_', ' ', $inventory->status) }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Current Holder</dt><dd class="font-medium">{{ $inventory->currentEmployee?->name ?? 'N/A' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Office</dt><dd class="font-medium">{{ $inventory->office?->name ?? 'N/A' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Property No.</dt><dd class="font-medium">{{ $inventory->property_no ?? 'N/A' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Model</dt><dd class="font-medium">{{ $inventory->model ?? 'N/A' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Serial Number</dt><dd class="font-medium">{{ $inventory->serial_number ?? 'N/A' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Unit Cost</dt><dd class="font-medium">PHP {{ number_format((float) $inventory->unit_cost, 2) }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Person Accountable</dt><dd class="font-medium">{{ $inventory->accountable_name ?? $inventory->currentEmployee?->name ?? 'N/A' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-500">Inventory Committee</dt><dd class="font-medium">{{ $inventory->inventory_committee_name ?? 'N/A' }}</dd></div>
            </dl>
        </div>
        <div class="rounded border bg-white p-4 text-center">
            <img src="{{ $inventory->qrImageUrl(190) }}" alt="QR Code" class="mx-auto h-44 w-44 border p-1">
            <p class="mt-2 text-xs text-gray-500">Scan to track</p>
            <a href="{{ route('inventory.track', $inventory->qr_token) }}" target="_blank" class="text-xs text-[#1a2c5b] hover:underline">Open tracking page</a>
        </div>
    </div>

    <div class="mt-5 rounded border bg-white">
        <div class="border-b px-4 py-3 text-sm font-semibold">Movement History</div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-3 py-2">Date</th>
                    <th class="px-3 py-2">Type</th>
                    <th class="px-3 py-2">From</th>
                    <th class="px-3 py-2">To</th>
                    <th class="px-3 py-2">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory->movements as $movement)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $movement->movement_date?->format('M d, Y') }}</td>
                    <td class="px-3 py-2">{{ ucfirst($movement->movement_type) }}</td>
                    <td class="px-3 py-2">{{ $movement->fromEmployee?->name ?? 'N/A' }}</td>
                    <td class="px-3 py-2">{{ $movement->toEmployee?->name ?? 'N/A' }}</td>
                    <td class="px-3 py-2">{{ $movement->remarks }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-5 text-center text-gray-500">No movement history.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
