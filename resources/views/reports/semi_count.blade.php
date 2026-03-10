@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Physical Count of Semi-Expendable</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Semi-Expendable Property Items (SPI)</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office - Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('reports.index') }}" class="hover:text-[#1a2c5b]">Reports</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">Semi Count</span>
            </div>
            <button onclick="window.print()" class="inline-flex w-full items-center justify-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#253d82] sm:w-auto">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Report
            </button>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-5">

        {{-- Summary Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#1a2c5b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ $rows->count() }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Office Groups</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded shadow-sm p-4 flex items-center gap-4">
                <div class="h-10 w-1 rounded-full bg-[#c8a84b]"></div>
                <div>
                    <p class="text-2xl font-bold text-[#1a2c5b]">{{ number_format($rows->sum('qty')) }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mt-0.5">Total Quantity</p>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Semi-Expendable Property - Summary by Office</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">No.</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Office / Department</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600 border-r border-gray-200">Fund Cluster</th>
                            <th class="px-4 py-2.5 text-right text-[11px] font-bold uppercase tracking-widest text-gray-600">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition {{ $i % 2 === 0 ? '' : 'bg-gray-50/50' }}">
                            <td class="px-4 py-2.5 text-gray-400 font-semibold text-xs border-r border-gray-100">{{ $i + 1 }}</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-800 border-r border-gray-100">{{ $row->office_name ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-gray-600 border-r border-gray-100">{{ $row->fund_cluster_code ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-right font-bold text-gray-800">
                                <button
                                    type="button"
                                    class="js-open-breakdown inline-flex items-center gap-1 rounded border border-[#1a2c5b] px-2 py-1 text-xs font-semibold text-[#1a2c5b] hover:bg-[#1a2c5b] hover:text-white transition"
                                    data-office-id="{{ $row->office_id }}"
                                    data-fund-cluster-id="{{ $row->fund_cluster_id }}"
                                    data-office-name="{{ $row->office_name ?? '-' }}"
                                    data-fund-cluster-code="{{ $row->fund_cluster_code ?? '-' }}"
                                >
                                    {{ number_format($row->qty ?? 0) }}
                                    <span class="opacity-80">(View)</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic text-sm">- No records found -</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($rows->count() > 0)
                    <tfoot class="border-t-2 border-[#1a2c5b] bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-2.5 text-sm font-bold text-[#1a2c5b] uppercase tracking-widest">Grand Total</td>
                            <td class="px-4 py-2.5 text-right font-bold text-[#1a2c5b] text-base">{{ number_format($rows->sum('qty')) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Footer Document Certification --}}
        <div class="text-center text-[11px] text-gray-400 border-t border-gray-200 pt-4">
            Generated by PGSO Property Management System - Provincial Government of Surigao Del Norte - {{ now()->format('F d, Y') }}
        </div>

    </div>
</div>

<div id="breakdown-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 js-close-breakdown"></div>
    <div class="relative mx-auto mt-10 w-[95%] max-w-7xl rounded bg-white shadow-2xl">
        <div class="flex flex-col gap-3 border-b border-gray-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Office Breakdown</p>
                <p id="breakdown-title" class="text-sm font-bold text-[#1a2c5b]">Loading...</p>
            </div>
            <button type="button" class="js-close-breakdown rounded border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-100">Close</button>
        </div>
        <div id="breakdown-content" class="max-h-[75vh] overflow-auto p-4 text-sm text-gray-600">
            Loading breakdown data...
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('breakdown-modal');
    const content = document.getElementById('breakdown-content');
    const title = document.getElementById('breakdown-title');
    if (!modal || !content || !title) return;

    const endpoint = @json(route('reports.breakdown'));

    const openModal = function () { modal.classList.remove('hidden'); };
    const closeModal = function () { modal.classList.add('hidden'); };

    document.querySelectorAll('.js-close-breakdown').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeModal();
    });

    const buildUrl = function (params) {
        const url = new URL(endpoint, window.location.origin);
        Object.keys(params).forEach(function (key) {
            if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                url.searchParams.set(key, params[key]);
            }
        });
        return url.toString();
    };

    const loadBreakdown = function (url) {
        content.innerHTML = 'Loading breakdown data...';
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (response) { return response.text(); })
            .then(function (html) { content.innerHTML = html; })
            .catch(function () { content.innerHTML = '<p class="text-red-600">Failed to load breakdown data.</p>'; });
    };

    document.querySelectorAll('.js-open-breakdown').forEach(function (button) {
        button.addEventListener('click', function () {
            const params = {
                asset_type: 'semi_expendable',
                office_id: button.dataset.officeId,
                fund_cluster_id: button.dataset.fundClusterId
            };

            title.textContent = (button.dataset.officeName || '-') + ' / ' + (button.dataset.fundClusterCode || '-');
            openModal();
            loadBreakdown(buildUrl(params));
        });
    });

    content.addEventListener('click', function (event) {
        const link = event.target.closest('.js-breakdown-pagination a');
        if (!link) return;
        event.preventDefault();
        loadBreakdown(link.href);
    });

    content.addEventListener('submit', function (event) {
        const form = event.target.closest('.js-breakdown-search-form');
        if (!form) return;
        event.preventDefault();
        const url = new URL(form.action, window.location.origin);
        const params = new URLSearchParams(new FormData(form));
        url.search = params.toString();
        loadBreakdown(url.toString());
    });

    content.addEventListener('click', function (event) {
        const clearButton = event.target.closest('.js-breakdown-clear');
        if (!clearButton) return;
        const form = content.querySelector('.js-breakdown-search-form');
        if (!form) return;
        const input = form.querySelector('input[name="q"]');
        if (input) input.value = '';
        const url = new URL(form.action, window.location.origin);
        const params = new URLSearchParams(new FormData(form));
        url.search = params.toString();
        loadBreakdown(url.toString());
    });
});
</script>
@endsection
