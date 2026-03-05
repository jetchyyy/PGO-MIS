@extends('layouts.blank')

@section('content')
<div class="relative h-screen w-full overflow-y-auto bg-slate-900 flex flex-col">

    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/capitol-bg.jpg') }}" alt="Capitol" class="h-full w-full object-cover object-center opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/90 via-slate-900/75 to-slate-900/90"></div>
    </div>

    {{-- Header --}}
    <div class="relative z-10 shrink-0 flex w-full items-center justify-between px-6 py-4 border-b border-white/10 backdrop-blur-md">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/10 p-1.5 ring-2 ring-white/20 shadow-xl">
                <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Logo" class="h-full w-full object-contain">
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-amber-400 leading-tight">Provincial General Services Office</p>
                <p class="text-base font-extrabold text-white leading-tight">Property Management System</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="hidden text-right lg:block">
                <div id="live-time" class="text-xl font-bold tracking-widest text-white font-mono leading-none">--:--:--</div>
                <div id="live-date" class="text-xs font-medium text-slate-400 mt-1 uppercase tracking-wider">Loading...</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-bold text-white transition hover:bg-rose-600 shadow-lg hover:shadow-rose-600/20 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    {{-- Content --}}
    <main class="relative z-10 flex flex-col px-6 py-4 gap-4 max-w-7xl w-full mx-auto pb-10">

        {{-- Welcome --}}
        <div class="shrink-0">
            <h2 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">Welcome back, {{ auth()->user()->name ?? 'Admin' }}!</h2>
            <p class="text-sm font-bold text-slate-400 mt-0.5">System Administrator &mdash; Full Governance Access</p>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">
            <a href="{{ route('issuance.index') }}" class="group relative overflow-hidden flex items-center justify-between rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-blue-500/40">
                <div class="relative z-10 flex flex-col">
                    <span class="text-4xl font-black tracking-tight text-white">{{ $stats['issuances'] }}</span>
                    <span class="mt-1 text-[11px] font-black uppercase tracking-widest text-blue-100">Issuances</span>
                </div>
                <div class="relative z-10 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                </div>
            </a>
            <a href="{{ route('transfer.index') }}" class="group relative overflow-hidden flex items-center justify-between rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-emerald-500/40">
                <div class="relative z-10 flex flex-col">
                    <span class="text-4xl font-black tracking-tight text-white">{{ $stats['transfers'] }}</span>
                    <span class="mt-1 text-[11px] font-black uppercase tracking-widest text-emerald-100">Transfers</span>
                </div>
                <div class="relative z-10 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
            </a>
            <a href="{{ route('disposal.index') }}" class="group relative overflow-hidden flex items-center justify-between rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-rose-500/40">
                <div class="relative z-10 flex flex-col">
                    <span class="text-4xl font-black tracking-tight text-white">{{ $stats['disposals'] }}</span>
                    <span class="mt-1 text-[11px] font-black uppercase tracking-widest text-rose-100">Disposals</span>
                </div>
                <div class="relative z-10 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
            </a>
            <a href="{{ route('approvals.index') }}" class="group relative overflow-hidden flex items-center justify-between rounded-3xl bg-gradient-to-br from-amber-500 to-amber-700 p-6 shadow-xl transition hover:-translate-y-1 hover:shadow-amber-500/40">
                <div class="relative z-10 flex flex-col">
                    <span class="text-4xl font-black tracking-tight text-white">{{ $stats['pending_approvals'] }}</span>
                    <span class="mt-1 text-[11px] font-black uppercase tracking-widest text-amber-100">Approvals</span>
                </div>
                <div class="relative z-10 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </a>
        </div>

        {{-- Property Modules --}}
        <div class="shrink-0 p-6 rounded-[2rem] bg-white/10 border border-white/20 backdrop-blur-xl shadow-2xl mt-2">
            <p class="mb-4 text-xs font-black uppercase tracking-[0.2em] text-amber-400 drop-shadow-md flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                System Modules
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <a href="{{ route('issuance.index') }}" class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 p-5 shadow-lg transition-all hover:-translate-y-1 hover:shadow-blue-500/40 min-h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                        </div>
                        <svg class="h-6 w-6 text-white/50 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-xl font-black text-white tracking-tight">Issuance</p>
                        <p class="text-xs font-bold text-blue-100 uppercase tracking-widest mt-1">PAR / ICS</p>
                    </div>
                </a>

                <a href="{{ route('transfer.index') }}" class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-5 shadow-lg transition-all hover:-translate-y-1 hover:shadow-emerald-500/40 min-h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <svg class="h-6 w-6 text-white/50 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-xl font-black text-white tracking-tight">Transfer</p>
                        <p class="text-xs font-bold text-emerald-100 uppercase tracking-widest mt-1">PTR / ITR</p>
                    </div>
                </a>

                <a href="{{ route('disposal.index') }}" class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-5 shadow-lg transition-all hover:-translate-y-1 hover:shadow-rose-500/40 min-h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <svg class="h-6 w-6 text-white/50 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-xl font-black text-white tracking-tight">Disposal</p>
                        <p class="text-xs font-bold text-rose-100 uppercase tracking-widest mt-1">IIRUP / RRSEP</p>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-gradient-to-br from-purple-500 to-purple-700 p-5 shadow-lg transition-all hover:-translate-y-1 hover:shadow-purple-500/40 min-h-[140px]">
                    <div class="flex items-center justify-between">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <svg class="h-6 w-6 text-white/50 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                    <div class="mt-4">
                        <p class="text-xl font-black text-white tracking-tight">Reports</p>
                        <p class="text-xs font-bold text-purple-100 uppercase tracking-widest mt-1">PPE / AUDIT</p>
                    </div>
                </a>

            </div>
        </div>

        {{-- Admin Functionalities --}}
        <div class="pr-1 mt-2">
            <div class="p-6 rounded-[2rem] bg-white/10 border border-white/20 backdrop-blur-xl shadow-2xl">
                <p class="mb-4 text-xs font-black uppercase tracking-[0.2em] text-teal-300 drop-shadow-md flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Administrative Tools
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-sky-500 to-sky-700 px-6 py-5 shadow-lg transition hover:-translate-y-1 hover:shadow-sky-500/40">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-white">User Management</p>
                            <p class="text-[11px] font-bold tracking-widest uppercase mt-0.5 text-sky-100">System Roles & Profiles</p>
                        </div>
                    </a>

                    <a href="{{ route('approvals.index') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-amber-500 to-amber-700 px-6 py-5 shadow-lg transition hover:-translate-y-1 hover:shadow-amber-500/40">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-white">Approvals Queue</p>
                            <p class="text-[11px] font-bold tracking-widest uppercase mt-0.5 text-amber-100">Pending Actions: {{ $stats['pending_approvals'] }}</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.logs') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-slate-600 to-slate-800 px-6 py-5 shadow-lg transition hover:-translate-y-1 hover:shadow-slate-500/40">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-white">System Logs</p>
                            <p class="text-[11px] font-bold tracking-widest uppercase mt-0.5 text-slate-300">Audit & Print History</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.ppe_count') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-violet-500 to-violet-700 px-6 py-5 shadow-lg transition hover:-translate-y-1 hover:shadow-violet-500/40">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-white">PPE Inventory</p>
                            <p class="text-[11px] font-bold tracking-widest uppercase mt-0.5 text-violet-100">Physical Count Reports</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.semi_count') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-indigo-500 to-indigo-700 px-6 py-5 shadow-lg transition hover:-translate-y-1 hover:shadow-indigo-500/40">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-white">Semi-Expendable</p>
                            <p class="text-[11px] font-bold tracking-widest uppercase mt-0.5 text-indigo-100">Property Record Audit</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.regspi') }}" class="group flex items-center gap-4 rounded-3xl bg-gradient-to-br from-teal-500 to-teal-700 px-6 py-5 shadow-lg transition hover:-translate-y-1 hover:shadow-teal-500/40">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-white shadow-inner group-hover:scale-110 transition-transform">
                             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M8 8h8M8 12h8M8 16h5"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-white">RegSPI Masterlist</p>
                            <p class="text-[11px] font-bold tracking-widest uppercase mt-0.5 text-teal-100">Semi-PPE Registry</p>
                        </div>
                    </a>

                </div>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-[10px] font-bold text-white/50 uppercase tracking-widest shrink-0 border-t border-white/5 pt-2 mt-auto">
            &copy; {{ date('Y') }} PGSO Property Management System &mdash; Provincial Government of Surigao Del Norte
        </p>

    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const t = document.getElementById('live-time');
        const d = document.getElementById('live-date');
        function tick() {
            const now = new Date();
            const timeOptions = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const dateOptions = { timeZone: 'Asia/Manila', weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
            if (t) t.textContent = new Intl.DateTimeFormat('en-US', timeOptions).format(now);
            if (d) d.textContent = new Intl.DateTimeFormat('en-US', dateOptions).format(now).toUpperCase();
        }
        tick(); setInterval(tick, 1000);
    });
</script>
@endsection
