@extends('layouts.app')

@section('content')
{{-- Content Header / Breadcrumb --}}
<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 leading-tight">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">Welcome back, <strong>{{ auth()->user()->name }}</strong> &mdash; System Administrator</p>
    </div>
    <div class="hidden sm:block text-right">
        <div id="live-time" class="text-lg font-bold text-gray-700 font-mono tracking-wider">--:--:--</div>
        <div id="live-date" class="text-xs text-gray-400">Loading...</div>
    </div>
</div>

{{-- Info Boxes Row (AdminLTE small-box style) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

    {{-- Issuances --}}
    <a href="{{ route('issuance.index') }}" class="group block bg-[#17a2b8] text-white shadow hover:shadow-md transition">
        <div class="flex items-center justify-between gap-3 p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['issuances'] }}</p>
                <p class="text-sm mt-1 opacity-90">Issuances</p>
            </div>
            <svg class="h-12 w-12 shrink-0 opacity-20 transition group-hover:opacity-30 sm:h-16 sm:w-16" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="3" width="16" height="18" rx="2"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">
            More info <span class="ml-1">&rarr;</span>
        </div>
    </a>

    {{-- Transfers --}}
    <a href="{{ route('transfer.index') }}" class="group block bg-[#28a745] text-white shadow hover:shadow-md transition">
        <div class="flex items-center justify-between gap-3 p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['transfers'] }}</p>
                <p class="text-sm mt-1 opacity-90">Transfers</p>
            </div>
            <svg class="h-12 w-12 shrink-0 opacity-20 transition group-hover:opacity-30 sm:h-16 sm:w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">
            More info <span class="ml-1">&rarr;</span>
        </div>
    </a>

    {{-- Disposals --}}
    <a href="{{ route('disposal.index') }}" class="group block bg-[#dc3545] text-white shadow hover:shadow-md transition">
        <div class="flex items-center justify-between gap-3 p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['disposals'] }}</p>
                <p class="text-sm mt-1 opacity-90">Disposals</p>
            </div>
            <svg class="h-12 w-12 shrink-0 opacity-20 transition group-hover:opacity-30 sm:h-16 sm:w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">
            More info <span class="ml-1">&rarr;</span>
        </div>
    </a>

    {{-- Pending Approvals --}}
    <a href="{{ route('approvals.index') }}" class="group block bg-[#ffc107] text-gray-900 shadow hover:shadow-md transition">
        <div class="flex items-center justify-between gap-3 p-4">
            <div>
                <p class="text-3xl font-bold leading-none">{{ $stats['pending_approvals'] }}</p>
                <p class="text-sm mt-1 opacity-80">Pending Approvals</p>
            </div>
            <svg class="h-12 w-12 shrink-0 opacity-20 transition group-hover:opacity-30 sm:h-16 sm:w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="bg-black/10 text-center py-1.5 text-xs font-medium">
            More info <span class="ml-1">&rarr;</span>
        </div>
    </a>

</div>

{{-- System Modules Card --}}
<div class="bg-white border border-gray-200 shadow-sm mb-5">
    <div class="bg-[#343a40] px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-bold text-white flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Property Modules
        </h3>
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
                    <p class="text-xs text-gray-400">Manage property acknowledgment and inventory custodian slips.</p>
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
                    <p class="text-xs text-gray-400">Process property and inventory transfer reports between offices.</p>
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
                    <p class="text-xs text-gray-400">Handle unserviceable items inspection and disposal process.</p>
                </div>
            </a>

            <a href="{{ route('reports.index') }}" class="group border border-gray-200 bg-white hover:border-[#007bff] transition shadow-sm hover:shadow">
                <div class="border-t-4 border-[#6f42c1] p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-10 w-10 items-center justify-center bg-[#6f42c1] text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 group-hover:text-[#007bff]">Reports</p>
                            <p class="text-xs text-gray-500">PPE / Audit</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Generate physical count reports, RegSPI, and audit logs.</p>
                </div>
            </a>

        </div>
    </div>
</div>

{{-- Admin Tools Card --}}
<div class="bg-white border border-gray-200 shadow-sm mb-5">
    <div class="bg-[#343a40] px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-bold text-white flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Administrative Tools
        </h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

            <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 border border-gray-200 p-3 hover:bg-[#e9ecef] transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#007bff] text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">User Management</p>
                    <p class="text-xs text-gray-500">System Roles & Profiles</p>
                </div>
            </a>

            <a href="{{ route('approvals.index') }}" class="group flex items-center gap-3 border border-gray-200 p-3 hover:bg-[#e9ecef] transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#ffc107] text-gray-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">Approvals Queue</p>
                    <p class="text-xs text-gray-500">Pending: {{ $stats['pending_approvals'] }}</p>
                </div>
            </a>

            <a href="{{ route('reports.logs') }}" class="group flex items-center gap-3 border border-gray-200 p-3 hover:bg-[#e9ecef] transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#6c757d] text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">System Logs</p>
                    <p class="text-xs text-gray-500">Audit & Print History</p>
                </div>
            </a>

            <a href="{{ route('reports.ppe_count') }}" class="group flex items-center gap-3 border border-gray-200 p-3 hover:bg-[#e9ecef] transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#6f42c1] text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">PPE Inventory</p>
                    <p class="text-xs text-gray-500">Physical Count Reports</p>
                </div>
            </a>

            <a href="{{ route('reports.semi_count') }}" class="group flex items-center gap-3 border border-gray-200 p-3 hover:bg-[#e9ecef] transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#20c997] text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">Semi-Expendable</p>
                    <p class="text-xs text-gray-500">Property Record Audit</p>
                </div>
            </a>

            <a href="{{ route('reports.regspi') }}" class="group flex items-center gap-3 border border-gray-200 p-3 hover:bg-[#e9ecef] transition">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#17a2b8] text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M8 8h8M8 12h8M8 16h5"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-sm">RegSPI Masterlist</p>
                    <p class="text-xs text-gray-500">Semi-PPE Registry</p>
                </div>
            </a>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const t = document.getElementById('live-time');
        const d = document.getElementById('live-date');
        function tick() {
            const now = new Date();
            if (t) t.textContent = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }).format(now);
            if (d) d.textContent = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Manila', weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' }).format(now);
        }
        tick(); setInterval(tick, 1000);
    });
</script>
@endsection
