@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Create Disposal</h1>
<form method="POST" action="{{ route('disposal.store') }}" class="space-y-3">@csrf
<div class="grid md:grid-cols-3 gap-2">
<input name="entity_name" class="rounded border p-2" placeholder="Entity Name" required>
<select name="employee_id" class="rounded border p-2" required><option value="">Accountable Officer</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select>
<input name="designation" class="rounded border p-2" placeholder="Designation">
<input name="station" class="rounded border p-2" placeholder="Station">
<select name="fund_cluster_id" class="rounded border p-2" required><option value="">Fund Cluster</option>@foreach($fundClusters as $f)<option value="{{ $f->id }}">{{ $f->code }}</option>@endforeach</select>
<input type="date" name="disposal_date" class="rounded border p-2" required>
<select name="disposal_type" class="rounded border p-2" required><option value="sale">Sale</option><option value="transfer">Transfer</option><option value="destruction">Destruction</option><option value="others">Others</option></select>
<input name="disposal_type_other" class="rounded border p-2" placeholder="Specify others">
<select name="document_type" class="rounded border p-2" required><option value="IIRUP">IIRUP</option><option value="RRSEP">RRSEP</option></select>
</div>
<div class="grid md:grid-cols-4 gap-2 rounded border bg-white p-3">
<input name="lines[0][particulars]" class="rounded border p-2" placeholder="Particulars" required>
<input name="lines[0][property_no]" class="rounded border p-2" placeholder="Property #">
<input name="lines[0][quantity]" type="number" min="1" class="rounded border p-2" placeholder="Qty" required>
<input name="lines[0][unit_cost]" type="number" step="0.01" class="rounded border p-2" placeholder="Unit Cost" required>
<input name="lines[0][accumulated_depreciation]" type="number" step="0.01" class="rounded border p-2" placeholder="Accum. Depreciation">
</div>
<button class="rounded bg-slate-900 px-4 py-2 text-white">Save Draft</button>
</form>
@endsection
