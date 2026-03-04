@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-3">Issuance {{ $issuance->control_no }}</h1>
<div class="mb-3 flex gap-2 flex-wrap">
    @if($issuance->status === 'draft')
    <form method="POST" action="{{ route('issuance.submit', $issuance) }}">@csrf<button class="rounded bg-blue-600 px-3 py-2 text-white text-sm">Submit</button></form>
    @endif
    @if(in_array($issuance->status, ['approved', 'issued']))
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('issuance.print', [$issuance, 'par']) }}">Print PAR</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('issuance.print', [$issuance, 'ics']) }}">Print ICS</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('issuance.print', [$issuance, 'property_card']) }}">Property Card</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('issuance.print', [$issuance, 'semi_property_card']) }}">Semi Card</a>
    <a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('issuance.print', [$issuance, 'regspi']) }}">RegSPI</a>
    @endif
</div>
<table class="min-w-full bg-white rounded shadow text-sm">
<thead><tr class="border-b"><th class="p-2">Qty</th><th class="p-2 text-left">Description</th><th class="p-2">Unit Cost</th><th class="p-2">Total</th><th class="p-2">Class</th></tr></thead>
<tbody>
@foreach($issuance->lines as $line)
<tr class="border-b"><td class="p-2">{{ $line->quantity }}</td><td class="p-2">{{ $line->description }}</td><td class="p-2">{{ number_format($line->unit_cost, 2) }}</td><td class="p-2">{{ number_format($line->total_cost, 2) }}</td><td class="p-2">{{ strtoupper($line->classification) }}</td></tr>
@endforeach
</tbody>
</table>
@endsection
