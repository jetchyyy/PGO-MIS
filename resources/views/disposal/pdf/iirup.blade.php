<!DOCTYPE html><html><head><style>body{font-family: DejaVu Sans; font-size:12px;} table{width:100%; border-collapse:collapse;} th,td{border:1px solid #000;padding:4px;} @page { size:A4; margin:20mm; }</style></head><body>
<h3>INVENTORY AND INSPECTION REPORT OF UNSERVICEABLE PROPERTY (IIRUP)</h3>
<p>No: {{ $disposal->control_no }} | Version: {{ $version ?? 1 }}</p>
<table><thead><tr><th>Particulars</th><th>Property No</th><th>Qty</th><th>Total Cost</th><th>Accum. Dep</th><th>Carrying</th></tr></thead><tbody>@foreach($disposal->lines as $line)<tr><td>{{ $line->particulars }}</td><td>{{ $line->property_no }}</td><td>{{ $line->quantity }}</td><td>{{ number_format($line->total_cost,2) }}</td><td>{{ number_format($line->accumulated_depreciation,2) }}</td><td>{{ number_format($line->carrying_amount,2) }}</td></tr>@endforeach</tbody></table>
</body></html>
