@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Reports</h1>
<div class="flex flex-wrap gap-2 mb-4">
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('reports.ppe_count') }}">PPE Count</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('reports.semi_count') }}">Semi Count</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('reports.regspi') }}">RegSPI</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('reports.logs') }}">Audit/Print Logs</a>
</div>
@if(isset($approvals))
<h2 class="font-semibold mb-2">Pending Approvals</h2>
<table class="min-w-full bg-white rounded shadow text-sm mb-4"><thead><tr class="border-b"><th class="p-2">ID</th><th class="p-2">Type</th><th class="p-2">Status</th><th class="p-2">Actions</th></tr></thead><tbody>@foreach($approvals as $approval)<tr class="border-b"><td class="p-2">{{ $approval->id }}</td><td class="p-2">{{ class_basename($approval->approvable_type) }}</td><td class="p-2">{{ $approval->status }}</td><td class="p-2"><form method="POST" action="{{ route('approvals.approve', $approval) }}" class="inline">@csrf<button class="rounded bg-emerald-600 px-2 py-1 text-white">Approve</button></form> <form method="POST" action="{{ route('approvals.return', $approval) }}" class="inline">@csrf<button class="rounded bg-amber-600 px-2 py-1 text-white">Return</button></form></td></tr>@endforeach</tbody></table>
@endif
@if(isset($auditLogs) || isset($printLogs))
<h2 class="font-semibold mb-2">Audit Logs</h2>
<table class="min-w-full bg-white rounded shadow text-sm mb-4"><thead><tr class="border-b"><th class="p-2">Date</th><th class="p-2">Event</th><th class="p-2">User ID</th></tr></thead><tbody>@foreach(($auditLogs ?? []) as $log)<tr class="border-b"><td class="p-2">{{ $log->created_at }}</td><td class="p-2">{{ $log->event }}</td><td class="p-2">{{ $log->user_id }}</td></tr>@endforeach</tbody></table>
<h2 class="font-semibold mb-2">Print Logs</h2>
<table class="min-w-full bg-white rounded shadow text-sm"><thead><tr class="border-b"><th class="p-2">Date</th><th class="p-2">Template</th><th class="p-2">Version</th><th class="p-2">Printed By</th></tr></thead><tbody>@foreach(($printLogs ?? []) as $log)<tr class="border-b"><td class="p-2">{{ $log->printed_at }}</td><td class="p-2">{{ $log->template_name }}</td><td class="p-2">{{ $log->version }}</td><td class="p-2">{{ $log->printed_by }}</td></tr>@endforeach</tbody></table>
@endif
@endsection
