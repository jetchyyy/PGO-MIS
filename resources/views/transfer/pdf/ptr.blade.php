<!DOCTYPE html><html><head><style>body{font-family: DejaVu Sans; font-size:12px;} table{width:100%; border-collapse:collapse;} th,td{border:1px solid #000;padding:4px;} @page { size:A4; margin:20mm; }</style></head><body>
<h3>PROPERTY TRANSFER REPORT (PTR)</h3>
<p>No: {{ $transfer->control_no }} | Version: {{ $version ?? 1 }}</p>
<table><thead><tr><th>Ref</th><th>Description</th><th>Qty</th><th>Unit</th><th>Amount</th><th>Condition</th></tr></thead><tbody>@foreach($transfer->lines as $line)<tr><td>{{ $line->reference_no }}</td><td>{{ $line->description }}</td><td>{{ $line->quantity }}</td><td>{{ $line->unit }}</td><td>{{ number_format($line->amount,2) }}</td><td>{{ $line->condition }}</td></tr>@endforeach</tbody></table>
</body></html>
