@if(!($asReport ?? false))
@include('issuance.pdf.par')
@else
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-3">RegSPI Listing</h1>
<table class="min-w-full bg-white rounded shadow text-sm">
<thead><tr class="border-b"><th class="p-2">Control #</th><th class="p-2">Office</th><th class="p-2">Employee</th><th class="p-2">Status</th></tr></thead>
<tbody>
@foreach($rows as $row)
<tr class="border-b"><td class="p-2">{{ $row->control_no }}</td><td class="p-2">{{ $row->office->name ?? '-' }}</td><td class="p-2">{{ $row->employee->name ?? '-' }}</td><td class="p-2">{{ $row->status }}</td></tr>
@endforeach
</tbody>
</table>
<div class="mt-3">{{ $rows->links() }}</div>
@endsection
@endif
