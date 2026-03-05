@extends('layouts.app')

@section('content')
@php $user = auth()->user(); $role = $user->role; @endphp

{{-- Hero Welcome Banner --}}
<div class="relative overflow-hidden bg-gradient-to-r from-blue-950 via-blue-900 to-blue-950 py-10 px-6 shadow-lg">
    <div class="pointer-events-none absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
    <div class="relative mx-auto max-w-7xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-1">Provincial General Services Office</p>
            <h1 class="text-3xl font-black text-white tracking-tight drop-shadow">Good day, {{ $user->name }}!</h1>
            <p class="mt-1 text-blue-200 text-sm font-semibold">
                You are logged in as
                <span class="ml-1 inline-block rounded-full bg-amber-400/20 px-3 py-0.5 text-xs font-bold uppercase tracking-widest text-amber-300 ring-1 ring-amber-400/30">
                    {{ str_replace('_', ' ', $role) }}
                </span>
            </p>
        </div>
        <div class="text-right hidden sm:block">
            <div id="staff-time" class="text-2xl font-bold text-white font-mono tracking-widest">--:--:--</div>
            <div id="staff-date" class="text-xs font-semibold text-blue-300 mt-0.5">Loading...</div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="bg-[#1a2c5b] border-b border-blue-900">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex divide-x divide-blue-800/50">

            @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER, \App\Models\User::ROLE_SYSTEM_ADMIN))
            <a href="{{ route('issuance.index') }}" class="group flex flex-1 flex-col items-center gap-1 py-4 transition hover:bg-white/5">
                <span class="text-3xl font-black text-white group-hover:text-[#c8a84b] transition">{{ $stats['issuances'] }}</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-300">Issuances</span>
                <span class="mt-1 h-0.5 w-8 rounded-full bg-[#c8a84b] opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>
            <a href="{{ route('transfer.index') }}" class="group flex flex-1 flex-col items-center gap-1 py-4 transition hover:bg-white/5">
                <span class="text-3xl font-black text-white group-hover:text-[#c8a84b] transition">{{ $stats['transfers'] }}</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-300">Transfers</span>
                <span class="mt-1 h-0.5 w-8 rounded-full bg-[#c8a84b] opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>
            @endif

            @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_SYSTEM_ADMIN))
            <a href="{{ route('disposal.index') }}" class="group flex flex-1 flex-col items-center gap-1 py-4 transition hover:bg-white/5">
                <span class="text-3xl font-black text-white group-hover:text-[#c8a84b] transition">{{ $stats['disposals'] }}</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-300">Disposals</span>
                <span class="mt-1 h-0.5 w-8 rounded-full bg-[#c8a84b] opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>
            @endif

            @if($user->hasRole(\App\Models\User::ROLE_APPROVING_OFFICIAL, \App\Models\User::ROLE_SYSTEM_ADMIN))
            <a href="{{ route('approvals.index') }}" class="group flex flex-1 flex-col items-center gap-1 py-4 transition hover:bg-white/5">
                <span class="text-3xl font-black text-white group-hover:text-[#c8a84b] transition">{{ $stats['pending_approvals'] }}</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-300">Pending Approvals</span>
                <span class="mt-1 h-0.5 w-8 rounded-full bg-[#c8a84b] opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>
            @endif

        </div>
    </div>
</div>

{{-- Module Cards --}}
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4 border-b border-gray-200 pb-2">System Modules</p>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER, \App\Models\User::ROLE_SYSTEM_ADMIN))
        {{-- Issuance --}}
        <a href="{{ route('issuance.index') }}" class="group flex items-center gap-5 bg-white border border-gray-200 rounded shadow-sm px-5 py-4 transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow">
            <div class="w-1 h-12 rounded-full bg-[#1a2c5b] shrink-0"></div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded border border-gray-100 bg-gray-50 text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white group-hover:border-[#1a2c5b]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 group-hover:text-[#1a2c5b] transition">Issuance</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate">PAR & ICS — Property Acknowledgment &amp; Inventory Custodian</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#1a2c5b] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('transfer.index') }}" class="group flex items-center gap-5 bg-white border border-gray-200 rounded shadow-sm px-5 py-4 transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow">
            <div class="w-1 h-12 rounded-full bg-[#1a2c5b] shrink-0"></div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded border border-gray-100 bg-gray-50 text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white group-hover:border-[#1a2c5b]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 group-hover:text-[#1a2c5b] transition">Transfer</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate">PTR & ITR — Property &amp; Inventory Transfer Reports</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#1a2c5b] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($user->hasRole(\App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_SYSTEM_ADMIN))
        {{-- Disposal --}}
        <a href="{{ route('disposal.index') }}" class="group flex items-center gap-5 bg-white border border-gray-200 rounded shadow-sm px-5 py-4 transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow">
            <div class="w-1 h-12 rounded-full bg-[#c8a84b] shrink-0"></div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded border border-gray-100 bg-gray-50 text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white group-hover:border-[#1a2c5b]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 group-hover:text-[#1a2c5b] transition">Disposal</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate">IIRUP & RRSEP — Unserviceable Property Inspection</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#1a2c5b] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($user->hasRole(\App\Models\User::ROLE_APPROVING_OFFICIAL, \App\Models\User::ROLE_SYSTEM_ADMIN))
        {{-- Approvals --}}
        <a href="{{ route('approvals.index') }}" class="group flex items-center gap-5 bg-white border border-gray-200 rounded shadow-sm px-5 py-4 transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow">
            <div class="w-1 h-12 rounded-full bg-emerald-600 shrink-0"></div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded border border-gray-100 bg-gray-50 text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white group-hover:border-[#1a2c5b]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 group-hover:text-[#1a2c5b] transition">Approvals</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate">Review and approve submitted issuance, transfer, and disposal requests</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#1a2c5b] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($user->hasRole(\App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_SYSTEM_ADMIN))
        {{-- Reports --}}
        <a href="{{ route('reports.index') }}" class="group flex items-center gap-5 bg-white border border-gray-200 rounded shadow-sm px-5 py-4 transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow">
            <div class="w-1 h-12 rounded-full bg-[#1a2c5b] shrink-0"></div>
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded border border-gray-100 bg-gray-50 text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white group-hover:border-[#1a2c5b]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 group-hover:text-[#1a2c5b] transition">Reports &amp; Audits</p>
                <p class="text-xs text-gray-400 mt-0.5 truncate">PPE Count, Semi Count, RegSPI, and compliance audit logs</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-[#1a2c5b] transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

    </div>

    {{-- System Notice --}}
    <div class="mt-8 text-center">
        <p class="text-xs text-gray-400">
            You are accessing the <strong class="text-gray-500">PGSO Property Management System</strong> of the Provincial Government of Surigao Del Norte.
            This system is for authorized personnel only. All actions are logged and monitored.
        </p>
    </div>
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
