@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-3">Transfer {{ $transfer->control_no }}</h1>
@if($transfer->status === 'draft')<form method="POST" action="{{ route('transfer.submit', $transfer) }}">@csrf<button class="rounded bg-blue-600 px-3 py-2 text-sm text-white mb-3">Submit</button></form>@endif
@if(in_array($transfer->status, ['approved','issued']))
<div class="mb-3 flex gap-2"><a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('transfer.print', [$transfer,'ptr']) }}">Print PTR</a><a class="rounded bg-slate-800 px-3 py-2 text-sm text-white" href="{{ route('transfer.print', [$transfer,'itr']) }}">Print ITR</a></div>
@endif
<table class="min-w-full bg-white rounded shadow text-sm"><thead><tr class="border-b"><th class="p-2">Ref</th><th class="p-2">Desc</th><th class="p-2">Qty</th><th class="p-2">Amount</th></tr></thead><tbody>@foreach($transfer->lines as $line)<tr class="border-b"><td class="p-2">{{ $line->reference_no }}</td><td class="p-2">{{ $line->description }}</td><td class="p-2">{{ $line->quantity }}</td><td class="p-2">{{ number_format($line->amount,2) }}</td></tr>@endforeach</tbody></table>
@endsection
