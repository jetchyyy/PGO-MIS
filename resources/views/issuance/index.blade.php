@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Issuance Transactions</h1>
    <a href="{{ route('issuance.create') }}" class="rounded bg-slate-900 px-4 py-2 text-sm text-white">New Issuance</a>
</div>
<table class="min-w-full bg-white rounded shadow text-sm">
    <thead><tr class="border-b"><th class="p-2 text-left">Control #</th><th class="p-2">Type</th><th class="p-2">Status</th><th class="p-2">Date</th><th></th></tr></thead>
    <tbody>
    @foreach($issuances as $row)
        <tr class="border-b"><td class="p-2">{{ $row->control_no }}</td><td class="p-2">{{ $row->document_type }}</td><td class="p-2">{{ $row->status }}</td><td class="p-2">{{ $row->transaction_date?->format('Y-m-d') }}</td><td class="p-2"><a class="underline" href="{{ route('issuance.show', $row) }}">View</a></td></tr>
    @endforeach
    </tbody>
</table>
<div class="mt-3">{{ $issuances->links() }}</div>
@endsection
