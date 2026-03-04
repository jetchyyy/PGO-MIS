@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-3">Physical Count of PPE</h1>
<form class="grid md:grid-cols-5 gap-2 mb-4">
<select name="office_id" class="rounded border p-2"><option value="">All Offices</option>@foreach($offices as $o)<option value="{{ $o->id }}" @selected(request('office_id')==$o->id)>{{ $o->name }}</option>@endforeach</select>
<select name="fund_cluster_id" class="rounded border p-2"><option value="">All Fund Clusters</option>@foreach($fundClusters as $f)<option value="{{ $f->id }}" @selected(request('fund_cluster_id')==$f->id)>{{ $f->code }}</option>@endforeach</select>
<input type="date" name="from" class="rounded border p-2" value="{{ request('from') }}">
<input type="date" name="to" class="rounded border p-2" value="{{ request('to') }}">
<button class="rounded bg-slate-900 px-3 py-2 text-white">Filter</button>
</form>
<table class="min-w-full bg-white rounded shadow text-sm"><thead><tr class="border-b"><th class="p-2">Office</th><th class="p-2">Fund Cluster</th><th class="p-2">Qty</th></tr></thead><tbody>@foreach($rows as $row)<tr class="border-b"><td class="p-2">{{ $row->office->name ?? '-' }}</td><td class="p-2">{{ $row->fundCluster->code ?? '-' }}</td><td class="p-2">{{ $row->qty }}</td></tr>@endforeach</tbody></table>
@endsection
