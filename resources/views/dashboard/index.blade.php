@extends('layouts.app')

@section('content')
@php $user = auth()->user(); $role = $user->role; @endphp

{{-- Hero Welcome Banner --}}
<div class="relative overflow-hidden bg-gradient-to-r from-blue-950 via-blue-900 to-blue-950 py-10 px-6 shadow-lg">
    <div class="pointer-events-none absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
    <div class="relative mx-auto max-w-7xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-amber-400 mb-1">Provincial General Services Office</p>
            <h1 class="text-3xl font-extrabold text-white tracking-tight drop-shadow">Good day, {{ $user->name }}!</h1>
            <p class="mt-1 text-blue-200 text-sm font-medium">
                You are logged in as
                <span class="ml-1 inline-block rounded-full bg-amber-400/20 px-3 py-0.5 text-xs font-bold uppercase tracking-widest text-amber-300 ring-1 ring-amber-400/30">
                    {{ str_replace('_', ' ', $role) }}
                </span>
            </p>
        </div>
        <div class="text-right hidden sm:block">
            <div id="staff-time" class="text-2xl font-bold text-white font-mono tracking-widest">--:--:--</div>
            <div id="staff-date" class="text-xs font-medium text-blue-300 mt-0.5">Loading...</div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="bg-white border-b border-slate-100 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 divide-x divide-slate-100 sm:grid-cols-4">

            <a href="{{ route('issuance.index') }}" class="group flex flex-col items-center gap-1 py-5 transition hover:bg-blue-50">
                <span class="text-3xl font-extrabold text-blue-600 group-hover:scale-105 transition-transform">{{ $stats['issuances'] }}</span>
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Issuances</span>
                <span class="mt-1 h-1 w-8 rounded-full bg-blue-400 opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>

            <a href="{{ route('transfer.index') }}" class="group flex flex-col items-center gap-1 py-5 transition hover:bg-emerald-50">
                <span class="text-3xl font-extrabold text-emerald-600 group-hover:scale-105 transition-transform">{{ $stats['transfers'] }}</span>
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Transfers</span>
                <span class="mt-1 h-1 w-8 rounded-full bg-emerald-400 opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>

            <a href="{{ route('disposal.index') }}" class="group flex flex-col items-center gap-1 py-5 transition hover:bg-rose-50">
                <span class="text-3xl font-extrabold text-rose-600 group-hover:scale-105 transition-transform">{{ $stats['disposals'] }}</span>
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Disposals</span>
                <span class="mt-1 h-1 w-8 rounded-full bg-rose-400 opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>

            <a href="{{ route('approvals.index') }}" class="group flex flex-col items-center gap-1 py-5 transition hover:bg-amber-50">
                <span class="text-3xl font-extrabold text-amber-600 group-hover:scale-105 transition-transform">{{ $stats['pending_approvals'] }}</span>
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">Pending Approvals</span>
                <span class="mt-1 h-1 w-8 rounded-full bg-amber-400 opacity-0 group-hover:opacity-100 transition-all"></span>
            </a>

        </div>
    </div>
</div>

{{-- Main content --}}
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Issuance (Blue) --}}
        <a href="{{ route('issuance.index') }}" class="group relative flex flex-col gap-4 rounded-2xl border border-blue-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 transition group-hover:bg-blue-600 group-hover:text-white">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors text-lg">Issuance</p>
                <p class="text-sm text-slate-500 mt-1 leading-snug">Manage PAR / ICS property issuance records.</p>
            </div>
            <div class="mt-auto pt-2 border-t border-slate-100 flex items-center text-xs font-semibold text-blue-500">
                Open module <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        {{-- Transfer (Emerald) --}}
        <a href="{{ route('transfer.index') }}" class="group relative flex flex-col gap-4 rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 transition group-hover:bg-emerald-600 group-hover:text-white">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors text-lg">Transfer</p>
                <p class="text-sm text-slate-500 mt-1 leading-snug">Process PTR / ITR transfers between departments.</p>
            </div>
            <div class="mt-auto pt-2 border-t border-slate-100 flex items-center text-xs font-semibold text-emerald-500">
                Open module <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        {{-- Disposal (Rose) --}}
        <a href="{{ route('disposal.index') }}" class="group relative flex flex-col gap-4 rounded-2xl border border-rose-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-rose-300 hover:shadow-lg">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 transition group-hover:bg-rose-600 group-hover:text-white">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-800 group-hover:text-rose-700 transition-colors text-lg">Disposal</p>
                <p class="text-sm text-slate-500 mt-1 leading-snug">Handle unserviceable items via IIRUP / RRSEP.</p>
            </div>
            <div class="mt-auto pt-2 border-t border-slate-100 flex items-center text-xs font-semibold text-rose-500">
                Open module <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        {{-- Reports (Purple) --}}
        <a href="{{ route('reports.index') }}" class="group relative flex flex-col gap-4 rounded-2xl border border-purple-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-purple-600 ring-1 ring-purple-100 transition group-hover:bg-purple-600 group-hover:text-white">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-800 group-hover:text-purple-700 transition-colors text-lg">Reports & Audits</p>
                <p class="text-sm text-slate-500 mt-1 leading-snug">Generate physical counts, RegSPI, and audit logs.</p>
            </div>
            <div class="mt-auto pt-2 border-t border-slate-100 flex items-center text-xs font-semibold text-purple-500">
                Open module <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

    </div>

    {{-- System Notice --}}
    <p class="mt-8 text-xs text-slate-400 text-center">
        You are accessing the <strong class="text-slate-500">PGSO Property Management System</strong> of the Provincial Government of Surigao Del Norte.
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
