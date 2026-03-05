@extends('layouts.app')

@section('content')
@php $user = auth()->user(); $role = $user->role; @endphp

{{-- Content Header --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 leading-tight">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Welcome, <strong>{{ $user->name }}</strong> &mdash;
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

    @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER, \App\Models\User::ROLE_SYSTEM_ADMIN))
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
    @endif

    @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_SYSTEM_ADMIN))
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
    @endif

    @if($user->hasRole(\App\Models\User::ROLE_APPROVING_OFFICIAL, \App\Models\User::ROLE_SYSTEM_ADMIN))
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
    @endif

</div>

{{-- Module Cards --}}
<div class="bg-white border border-gray-200 shadow-sm mb-5">
    <div class="bg-white px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            System Modules
        </h3>
    </div>
    <div class="divide-y divide-gray-100">

        @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER, \App\Models\User::ROLE_SYSTEM_ADMIN))
        <a href="{{ route('issuance.index') }}" class="group flex items-center gap-4 px-4 py-3 hover:bg-[#f8f9fa] transition">
            <div class="w-1 h-8 bg-[#17a2b8] shrink-0"></div>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#17a2b8] text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm group-hover:text-[#007bff]">Issuance</p>
                <p class="text-xs text-gray-500 truncate">PAR & ICS &mdash; Property Acknowledgment & Inventory Custodian</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#007bff] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('transfer.index') }}" class="group flex items-center gap-4 px-4 py-3 hover:bg-[#f8f9fa] transition">
            <div class="w-1 h-8 bg-[#28a745] shrink-0"></div>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#28a745] text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm group-hover:text-[#007bff]">Transfer</p>
                <p class="text-xs text-gray-500 truncate">PTR & ITR &mdash; Property & Inventory Transfer Reports</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#007bff] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_SYSTEM_ADMIN))
        <a href="{{ route('disposal.index') }}" class="group flex items-center gap-4 px-4 py-3 hover:bg-[#f8f9fa] transition">
            <div class="w-1 h-8 bg-[#dc3545] shrink-0"></div>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#dc3545] text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm group-hover:text-[#007bff]">Disposal</p>
                <p class="text-xs text-gray-500 truncate">IIRUP & RRSEP &mdash; Unserviceable Property Inspection</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#007bff] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($user->hasRole(\App\Models\User::ROLE_APPROVING_OFFICIAL, \App\Models\User::ROLE_SYSTEM_ADMIN))
        <a href="{{ route('approvals.index') }}" class="group flex items-center gap-4 px-4 py-3 hover:bg-[#f8f9fa] transition">
            <div class="w-1 h-8 bg-[#ffc107] shrink-0"></div>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#ffc107] text-gray-900">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm group-hover:text-[#007bff]">Approvals</p>
                <p class="text-xs text-gray-500 truncate">Review and approve submitted issuance, transfer, and disposal requests</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#007bff] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($user->hasRole(\App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_SYSTEM_ADMIN))
        <a href="{{ route('reports.index') }}" class="group flex items-center gap-4 px-4 py-3 hover:bg-[#f8f9fa] transition">
            <div class="w-1 h-8 bg-[#6f42c1] shrink-0"></div>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#6f42c1] text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm group-hover:text-[#007bff]">Reports & Audits</p>
                <p class="text-xs text-gray-500 truncate">PPE Count, Semi Count, RegSPI, and compliance audit logs</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#007bff] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

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
