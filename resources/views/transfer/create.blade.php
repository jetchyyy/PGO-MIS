@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Create Transfer</h1>
<form method="POST" action="{{ route('transfer.store') }}" class="space-y-3">@csrf
<div class="grid md:grid-cols-3 gap-2">
<input name="entity_name" class="rounded border p-2" placeholder="Entity Name" required>
<select name="from_employee_id" class="rounded border p-2" required><option value="">From</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select>
<select name="to_employee_id" class="rounded border p-2" required><option value="">To</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select>
<select name="fund_cluster_id" class="rounded border p-2" required><option value="">Fund Cluster</option>@foreach($fundClusters as $f)<option value="{{ $f->id }}">{{ $f->code }}</option>@endforeach</select>
<select name="transfer_type" class="rounded border p-2" required><option value="donation">Donation</option><option value="reassignment_recall">Reassignment-Recall</option><option value="relocate">Relocate</option><option value="retirement_resignation">Retirement-Resignation</option><option value="others">Others</option></select>
<input name="transfer_type_other" class="rounded border p-2" placeholder="Specify others">
<input type="date" name="transfer_date" class="rounded border p-2" required>
<select name="document_type" class="rounded border p-2" required><option value="PTR">PTR</option><option value="ITR">ITR</option></select>
</div>
<div class="grid md:grid-cols-4 gap-2 rounded border bg-white p-3">
<input name="lines[0][reference_no]" class="rounded border p-2" placeholder="PAR/ICS Ref" required>
<input name="lines[0][quantity]" type="number" min="1" class="rounded border p-2" placeholder="Qty" required>
<input name="lines[0][unit]" class="rounded border p-2" placeholder="Unit" required>
<input name="lines[0][description]" class="rounded border p-2" placeholder="Description" required>
<input name="lines[0][amount]" type="number" step="0.01" class="rounded border p-2" placeholder="Amount" required>
<input name="lines[0][condition]" class="rounded border p-2" placeholder="Condition" value="Functional" required>
</div>
<button class="rounded bg-slate-900 px-4 py-2 text-white">Save Draft</button>
</form>
@endsection
