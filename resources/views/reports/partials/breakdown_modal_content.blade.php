<div class="space-y-3">
    <form method="GET" action="{{ route('reports.breakdown') }}" class="js-breakdown-search-form flex flex-wrap items-end gap-2 border border-gray-200 rounded p-3 bg-gray-50">
        <input type="hidden" name="asset_type" value="{{ request('asset_type') }}">
        <input type="hidden" name="office_id" value="{{ request('office_id') }}">
        <input type="hidden" name="fund_cluster_id" value="{{ request('fund_cluster_id') }}">
        <input type="hidden" name="from" value="{{ request('from') }}">
        <input type="hidden" name="to" value="{{ request('to') }}">

        <div class="flex-1 min-w-[220px]">
            <label class="block text-[11px] font-semibold uppercase tracking-widest text-gray-500 mb-1">Search</label>
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Doc no, description, property no, accountable officer..."
                class="w-full rounded border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]"
            >
        </div>
        <button type="submit" class="rounded border border-[#1a2c5b] bg-[#1a2c5b] px-3 py-2 text-xs font-semibold text-white hover:bg-[#253d82] transition">Search</button>
        <button type="button" class="js-breakdown-clear rounded border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">Clear</button>
    </form>

    <div class="text-xs text-gray-500">
        <span class="font-semibold text-[#1a2c5b]">{{ $officeName }}</span>
        <span class="mx-1">&middot;</span>
        <span>Fund Cluster: {{ $fundClusterCode }}</span>
        @if($lines->total() > 0)
            <span class="mx-1">&middot;</span>
            <span>Showing {{ $lines->firstItem() }}-{{ $lines->lastItem() }} of {{ $lines->total() }}</span>
        @endif
    </div>

    <div class="overflow-x-auto border border-gray-200 rounded">
        <table class="min-w-full text-xs">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="px-3 py-2 text-left font-bold uppercase tracking-wider text-gray-600">Date</th>
                    <th class="px-3 py-2 text-left font-bold uppercase tracking-wider text-gray-600">Doc No.</th>
                    <th class="px-3 py-2 text-left font-bold uppercase tracking-wider text-gray-600">Type</th>
                    <th class="px-3 py-2 text-left font-bold uppercase tracking-wider text-gray-600">Description</th>
                    <th class="px-3 py-2 text-left font-bold uppercase tracking-wider text-gray-600">Property No.</th>
                    <th class="px-3 py-2 text-left font-bold uppercase tracking-wider text-gray-600">Accountable Officer</th>
                    <th class="px-3 py-2 text-right font-bold uppercase tracking-wider text-gray-600">Qty</th>
                    <th class="px-3 py-2 text-right font-bold uppercase tracking-wider text-gray-600">Unit Cost</th>
                    <th class="px-3 py-2 text-right font-bold uppercase tracking-wider text-gray-600">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lines as $line)
                <tr class="border-b border-gray-100">
                    <td class="px-3 py-2 text-gray-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($line->transaction_date)->format('M d, Y') }}</td>
                    <td class="px-3 py-2 text-[#1a2c5b] font-semibold">{{ $line->control_no }}</td>
                    <td class="px-3 py-2 text-gray-600 whitespace-nowrap">{{ $line->document_type }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $line->description }}</td>
                    <td class="px-3 py-2 text-gray-600 whitespace-nowrap">{{ $line->property_no ?: '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $line->accountable_officer ?: '-' }}</td>
                    <td class="px-3 py-2 text-right font-semibold text-gray-700">{{ number_format((float) $line->quantity, 0) }}</td>
                    <td class="px-3 py-2 text-right text-gray-600">{{ number_format((float) $line->unit_cost, 2) }}</td>
                    <td class="px-3 py-2 text-right font-semibold text-gray-800">{{ number_format((float) $line->total_cost, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-gray-400 italic">No breakdown records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lines->hasPages())
    <div class="js-breakdown-pagination">
        {{ $lines->onEachSide(1)->links() }}
    </div>
    @endif
</div>
