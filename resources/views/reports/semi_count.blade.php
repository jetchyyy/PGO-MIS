@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-3">Physical Count of Semi-Expendable</h1>
<table class="min-w-full bg-white rounded shadow text-sm"><thead><tr class="border-b"><th class="p-2">Office</th><th class="p-2">Fund Cluster</th><th class="p-2">Count</th></tr></thead><tbody>@foreach($rows as $row)<tr class="border-b"><td class="p-2">{{ $row->office->name ?? '-' }}</td><td class="p-2">{{ $row->fundCluster->code ?? '-' }}</td><td class="p-2">{{ $row->count }}</td></tr>@endforeach</tbody></table>
@endsection
