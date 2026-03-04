@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-3">Disposal {{ $disposal->control_no }}</h1>
@if($disposal->status === 'draft')<form method="POST" action="{{ route('disposal.submit', $disposal) }}">@csrf<button class="rounded bg-blue-600 px-3 py-2 text-sm text-white mb-3">Submit</button></form>@endif
@if(in_array($disposal->status, ['approved','issued']))
<div class="mb-3 flex gap-2"><a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('disposal.print', [$disposal,'iirup']) }}">Print IIRUP</a><a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('disposal.print', [$disposal,'rrsep']) }}">Print RRSEP</a></div>
@endif
<table class="min-w-full bg-white rounded shadow text-sm"><thead><tr class="border-b"><th class="p-2">Particulars</th><th class="p-2">Qty</th><th class="p-2">Cost</th><th class="p-2">Carrying</th></tr></thead><tbody>@foreach($disposal->lines as $line)<tr class="border-b"><td class="p-2">{{ $line->particulars }}</td><td class="p-2">{{ $line->quantity }}</td><td class="p-2">{{ number_format($line->total_cost,2) }}</td><td class="p-2">{{ number_format($line->carrying_amount,2) }}</td></tr>@endforeach</tbody></table>
@endsection
