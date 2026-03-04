@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Create Issuance</h1>
<form method="POST" action="{{ route('issuance.store') }}" class="space-y-4">
    @csrf
    <div class="grid md:grid-cols-3 gap-3">
        <input name="entity_name" class="rounded border p-2" placeholder="Entity Name" required>
        <select name="office_id" class="rounded border p-2" required>
            <option value="">Office</option>
            @foreach($offices as $office)<option value="{{ $office->id }}">{{ $office->name }}</option>@endforeach
        </select>
        <select name="employee_id" class="rounded border p-2" required>
            <option value="">Employee</option>
            @foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach
        </select>
        <select name="fund_cluster_id" class="rounded border p-2" required>
            <option value="">Fund Cluster</option>
            @foreach($fundClusters as $fc)<option value="{{ $fc->id }}">{{ $fc->code }} - {{ $fc->name }}</option>@endforeach
        </select>
        <input type="date" name="transaction_date" class="rounded border p-2" required>
        <input name="reference_no" class="rounded border p-2" placeholder="Reference">
    </div>

    <div class="rounded border bg-white p-3 space-y-2">
        <h2 class="font-medium">Line Item</h2>
        <div class="grid md:grid-cols-4 gap-2">
            <input name="lines[0][quantity]" class="rounded border p-2" type="number" min="1" placeholder="Qty" required>
            <input name="lines[0][unit]" class="rounded border p-2" placeholder="Unit" required>
            <input name="lines[0][description]" class="rounded border p-2" placeholder="Description" required>
            <input name="lines[0][property_no]" class="rounded border p-2" placeholder="Property #">
            <input name="lines[0][date_acquired]" class="rounded border p-2" type="date" placeholder="Date acquired">
            <input name="lines[0][unit_cost]" class="rounded border p-2" type="number" step="0.01" min="0.01" placeholder="Unit cost" required>
            <input name="lines[0][remarks]" class="rounded border p-2 md:col-span-2" placeholder="Remarks">
        </div>
    </div>

    <button class="rounded bg-slate-900 px-4 py-2 text-white">Save Draft</button>
</form>
@endsection
