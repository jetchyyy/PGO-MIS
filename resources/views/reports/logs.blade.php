@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">PGSO Reports</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Audit Logs</p>
                <p class="text-blue-200 text-[11px]">Provincial General Services Office &mdash; Surigao Del Norte</p>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('reports.index') }}" class="hover:text-[#1a2c5b]">Reports</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Audit Logs</span>
        </div>
    </div>

    {{-- Table --}}
    <div class="w-full px-4 pt-4 pb-8 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-[#1a2c5b]">
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Date &amp; Time</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">User</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Action</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Module</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Record</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-widest text-[#1a2c5b]">Details</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs ?? [] as $i => $log)
                    @php
                        $eventParts = explode('.', (string) $log->event, 2);
                        $module = $eventParts[0] ?? '-';
                        $action = $eventParts[1] ?? ($eventParts[0] ?? '-');
                        $record = ($log->subject_type && $log->subject_id) ? (class_basename($log->subject_type).'#'.$log->subject_id) : '-';
                        $details = !empty($log->context) ? json_encode($log->context) : '-';
                    @endphp
                    <tr class="border-b border-gray-200 hover:bg-blue-50/50 transition {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}">
                        <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                        <td class="px-5 py-4 font-semibold text-[#1a2c5b]">{{ $log->user->name ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border border-amber-300 bg-amber-50 text-amber-700">
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-700">{{ ucfirst(str_replace('_', ' ', $module)) }}</td>
                        <td class="px-5 py-4 font-mono text-gray-600 text-xs">{{ $record }}</td>
                        <td class="px-5 py-4 text-gray-500 text-xs max-w-xs truncate">{{ \Illuminate\Support\Str::limit((string) $details, 140) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg class="h-10 w-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="text-sm font-medium italic">&mdash; No audit log records found &mdash;</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(!empty($logs) && method_exists($logs, 'hasPages') && $logs->hasPages())
        <div class="mt-4 px-2">{{ $logs->links() }}</div>
        @endif
    </div>

</div>
@endsection
