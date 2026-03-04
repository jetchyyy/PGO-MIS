<!DOCTYPE html>
<html><head><style>body{font-family: DejaVu Sans, sans-serif; font-size:12px;} table{width:100%; border-collapse:collapse;} th,td{border:1px solid #000; padding:4px;} .mb{margin-bottom:10px;} @page { size: A4; margin: 20mm; }</style></head>
<body>
<h3 class="mb">PROPERTY ACKNOWLEDGEMENT RECEIPT (PAR)</h3>
<p>Entity: {{ $issuance->entity_name }} | Office: {{ $issuance->office->name }} | Fund Cluster: {{ $issuance->fundCluster->code }}<br>
Accountable Officer: {{ $issuance->employee->name }} | Date: {{ $issuance->transaction_date?->format('Y-m-d') }} | No: {{ $issuance->control_no }} | Version: {{ $version ?? 1 }}</p>
<table><thead><tr><th>Qty</th><th>Unit</th><th>Description</th><th>Property No</th><th>Date Acquired</th><th>Unit Cost</th><th>Total</th></tr></thead><tbody>
@foreach($issuance->lines as $line)
<tr><td>{{ $line->quantity }}</td><td>{{ $line->unit }}</td><td>{{ $line->description }}</td><td>{{ $line->property_no }}</td><td>{{ $line->date_acquired?->format('Y-m-d') }}</td><td>{{ number_format($line->unit_cost,2) }}</td><td>{{ number_format($line->total_cost,2) }}</td></tr>
@endforeach
</tbody></table>
<p style="margin-top:30px;">Prepared by: ____________________ &nbsp;&nbsp;&nbsp; Received by: ____________________</p>
</body></html>
