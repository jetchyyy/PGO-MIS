@extends('layouts.app')

@section('content')
@php $user = auth()->user(); $role = $user->role; @endphp

{{-- Content Header --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 leading-tight">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Good day, <strong>{{ $user->name }}</strong> &mdash;
            <span class="inline-block bg-[#007bff] text-white text-xs font-semibold px-2 py-0.5 ml-1 align-middle">{{ str_replace('_', ' ', $role) }}</span>
        </p>
    </div>
    <div class="hidden sm:block text-right">
        <div id="staff-time" class="text-lg font-bold text-gray-700 font-mono tracking-wider">--:--:--</div>
        <div id="staff-date" class="text-xs text-gray-400">Loading...</div>
    </div>
</div>

{{-- Info Boxes --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

    <a href="{{ route('issuance.index') }}" class="group block bg-[#17a2b8] text-white shadow hover:shadow-md transition">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['issuances'] }}</p>
                <p class="text-sm mt-1 opacity-90">Issuances</p>
            </div>
            <svg class="h-14 w-14 opacity-20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="3" width="16" height="18" rx="2"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">More info &rarr;</div>
    </a>

    <a href="{{ route('transfer.index') }}" class="group block bg-[#28a745] text-white shadow hover:shadow-md transition">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['transfers'] }}</p>
                <p class="text-sm mt-1 opacity-90">Transfers</p>
            </div>
            <svg class="h-14 w-14 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">More info &rarr;</div>
    </a>

    <a href="{{ route('disposal.index') }}" class="group block bg-[#dc3545] text-white shadow hover:shadow-md transition">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['disposals'] }}</p>
                <p class="text-sm mt-1 opacity-90">Disposals</p>
            </div>
            <svg class="h-14 w-14 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">More info &rarr;</div>
    </a>

    <a href="{{ route('approvals.index') }}" class="group block bg-[#ffc107] text-gray-900 shadow hover:shadow-md transition">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['pending_approvals'] }}</p>
                <p class="text-sm mt-1 opacity-80">Pending Approvals</p>
            </div>
            <svg class="h-14 w-14 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">More info &rarr;</div>
    </a>

</div>

{{-- Quick Actions Card --}}
<div class="bg-white border border-gray-200 shadow-sm mb-5">
    <div class="bg-white px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-bold text-gray-700">Quick Actions</h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <a href="{{ route('issuance.index') }}" class="group border border-gray-200 bg-white hover:border-[#007bff] transition shadow-sm hover:shadow">
                <div class="border-t-4 border-[#17a2b8] p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center bg-[#17a2b8] text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 group-hover:text-[#007bff]">Issuance</p>
                            <p class="text-xs text-gray-500">PAR / ICS</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Manage PAR / ICS property issuance records.</p>
                </div>
            </a>

            <a href="{{ route('transfer.index') }}" class="group border border-gray-200 bg-white hover:border-[#007bff] transition shadow-sm hover:shadow">
                <div class="border-t-4 border-[#28a745] p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center bg-[#28a745] text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 group-hover:text-[#007bff]">Transfer</p>
                            <p class="text-xs text-gray-500">PTR / ITR</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Process PTR / ITR transfers between departments.</p>
                </div>
            </a>

            <a href="{{ route('disposal.index') }}" class="group border border-gray-200 bg-white hover:border-[#007bff] transition shadow-sm hover:shadow">
                <div class="border-t-4 border-[#dc3545] p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center bg-[#dc3545] text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 group-hover:text-[#007bff]">Disposal</p>
                            <p class="text-xs text-gray-500">IIRUP / RRSEP</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Handle unserviceable items via IIRUP / RRSEP.</p>
                </div>
            </a>

            <a href="{{ route('reports.index') }}" class="group border border-gray-200 bg-white hover:border-[#007bff] transition shadow-sm hover:shadow">
                <div class="border-t-4 border-[#6f42c1] p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center bg-[#6f42c1] text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 group-hover:text-[#007bff]">Reports & Audits</p>
                            <p class="text-xs text-gray-500">PPE / Audit</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Generate physical counts, RegSPI, and audit logs.</p>
                </div>
            </a>

        </div>
    </div>
</div>

{{-- System Notice --}}
<div class="border-l-4 border-[#17a2b8] bg-white border border-gray-200 shadow-sm p-3">
    <p class="text-xs text-gray-500">
        You are accessing the <strong class="text-gray-700">PGSO Property Management System</strong> of the Provincial Government of Surigao Del Norte.
        This system is for authorized personnel only. All actions are logged and monitored.
    </p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeEl = document.getElementById('staff-time');
        const dateEl = document.getElementById('staff-date');
        function tick() {
            const now = new Date();
            if(timeEl) timeEl.textContent = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }).format(now);
            if(dateEl) dateEl.textContent = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Manila', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }).format(now);
        }
        tick();
        setInterval(tick, 1000);
    });
</script>
@endsection
