@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">PGSO Reports</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Reports &amp; Audit Center</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Reports</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-6">

        {{-- Report Type Cards --}}
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Generate Reports</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('reports.ppe_count') }}" class="group flex flex-col items-center gap-3 rounded border border-gray-200 bg-gray-50 p-5 text-center shadow-sm transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow-md hover:bg-white">
                    <div class="flex h-12 w-12 items-center justify-center rounded border border-gray-200 bg-white text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 text-sm group-hover:text-[#1a2c5b]">PPE Count</p>
                        <p class="text-xs text-gray-400 mt-0.5">Physical count of PPE</p>
                    </div>
                </a>
                <a href="{{ route('reports.semi_count') }}" class="group flex flex-col items-center gap-3 rounded border border-gray-200 bg-gray-50 p-5 text-center shadow-sm transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow-md hover:bg-white">
                    <div class="flex h-12 w-12 items-center justify-center rounded border border-gray-200 bg-white text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 text-sm group-hover:text-[#1a2c5b]">Semi Count</p>
                        <p class="text-xs text-gray-400 mt-0.5">Semi-expendable items</p>
                    </div>
                </a>
                <a href="{{ route('reports.regspi') }}" class="group flex flex-col items-center gap-3 rounded border border-gray-200 bg-gray-50 p-5 text-center shadow-sm transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow-md hover:bg-white">
                    <div class="flex h-12 w-12 items-center justify-center rounded border border-gray-200 bg-white text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M8 8h8M8 12h8M8 16h5"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 text-sm group-hover:text-[#1a2c5b]">RegSPI</p>
                        <p class="text-xs text-gray-400 mt-0.5">Registry of semi-PPE items</p>
                    </div>
                </a>
                <a href="{{ route('reports.logs') }}" class="group flex flex-col items-center gap-3 rounded border border-gray-200 bg-gray-50 p-5 text-center shadow-sm transition hover:-translate-y-0.5 hover:border-[#1a2c5b] hover:shadow-md hover:bg-white">
                    <div class="flex h-12 w-12 items-center justify-center rounded border border-gray-200 bg-white text-[#1a2c5b] transition group-hover:bg-[#1a2c5b] group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 text-sm group-hover:text-[#1a2c5b]">Audit Logs</p>
                        <p class="text-xs text-gray-400 mt-0.5">Print &amp; audit history</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Pending Approvals --}}
        @if(isset($approvals) && $approvals->count() > 0)
        <div>
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Pending Approvals</h2>
            <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">ID</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Type</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Document</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Status</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach($approvals as $approval)
                        @php
                            $record = $approval->approvable;
                            $viewUrl = $approval->approvableViewUrl();
                        @endphp
                        <tr class="hover:bg-amber-50/40 transition">
                            <td class="px-5 py-3.5 font-mono text-slate-700">{{ $approval->id }}</td>
                            <td class="px-5 py-3.5 text-slate-700">{{ $approval->approvableLabel() }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $record->control_no ?? 'No control number' }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $record->document_type ?? 'Document details unavailable' }}
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">{{ ucfirst($approval->status) }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($viewUrl)
                                    <a href="{{ $viewUrl }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-[#1a2c5b] hover:text-[#1a2c5b]">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/></svg>
                                        View
                                    </a>
                                    @endif
                                    <form method="POST" action="{{ route('approvals.approve', $approval) }}" class="inline">
                                        @csrf
                                        <button class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('approvals.return', $approval) }}" class="inline">
                                        @csrf
                                        <button class="inline-flex items-center gap-1 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            Return
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Audit / Print Logs --}}
        @if(isset($auditLogs) || isset($printLogs))
        <div>
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Audit Logs</h2>
            <div class="mb-5 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Date</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Event</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">User ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse(($auditLogs ?? []) as $log)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $log->created_at }}</td>
                            <td class="px-5 py-3.5 text-slate-700">{{ $log->event }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $log->user_id }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-slate-400">No audit logs.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Print Logs</h2>
            <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-left">
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Date</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Template</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Version</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Printed By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse(($printLogs ?? []) as $log)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-3.5 text-slate-500 font-mono text-xs">{{ $log->printed_at }}</td>
                            <td class="px-5 py-3.5 text-slate-700">{{ $log->template_name }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $log->version }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $log->printed_by }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">No print logs.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
