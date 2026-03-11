<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 portrait; margin: 0; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000; background: #fff; margin: 0; padding: 15mm 18mm !important; }
    .annex { font-size: 8pt; text-align: right; font-style: italic; }
    .doc-title { font-size: 14pt; font-weight: bold; text-align: center; letter-spacing: 1px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 2px 4px; font-size: 9pt; vertical-align: top; }
    th { background: #f5f5f5; font-weight: bold; text-align: center; font-size: 8pt; }
    td.numeric { text-align: right; }
    td.center { text-align: center; }
</style>
</head>
<body>
@php($pgso = $sig['pgso_head'] ?? null)
<div class="annex">Return Module</div>
<table style="border:none; margin-bottom:2px;">
    <tr>
        <td style="border:none; width:55px; vertical-align:middle; text-align:center;">
            @if(file_exists(public_path('images/surigaodelnorte.png')))
            <img src="{{ public_path('images/surigaodelnorte.png') }}" style="width:50px; height:50px; object-fit:contain;" alt="Seal">
            @endif
        </td>
        <td style="border:none; vertical-align:middle;">
            <div class="doc-title">RECEIPT OF RETURNED SEMI-EXPENDABLE PROPERTY</div>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td colspan="4"><strong>Entity Name:</strong> {{ $returnRecord->entity_name }}</td>
        <td colspan="2"><strong>Date:</strong> {{ $returnRecord->return_date?->format('M. d, Y') }}</td>
        <td class="center"><strong>{{ $documentControlNo ?? $returnRecord->control_no }}</strong></td>
    </tr>
</table>
<table>
    <thead>
    <tr>
        <th>Item Description</th>
        <th>Qty</th>
        <th>Date Acquired</th>
        <th>Property / ICS No.</th>
        <th>Amount</th>
        <th>Officer</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($returnRecord->lines as $line)
    <tr>
        <td>{{ $line->particulars }}</td>
        <td class="center">{{ $line->quantity }}</td>
        <td class="center">{{ $line->date_acquired?->format('m/d/Y') }}</td>
        <td class="center">{{ $line->property_no }}</td>
        <td class="numeric">{{ number_format((float) $line->total_cost, 2) }}</td>
        <td class="center">{{ $returnRecord->employee->name ?? '' }}</td>
        <td>{{ $line->remarks }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<table style="margin-top: 12px;">
    <tr>
        <td style="width:50%; padding:8px;">
            <div style="font-weight:bold;">RETURNED BY:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; text-decoration:underline;">{{ $returnRecord->employee->name ?? '' }}</div>
                <div>{{ $returnRecord->designation ?? '' }}</div>
            </div>
        </td>
        <td style="width:50%; padding:8px;">
            <div style="font-weight:bold;">RECEIVED BY:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; text-decoration:underline;">{{ $pgso->name ?? '' }}</div>
                <div>{{ $pgso->designation ?? '' }}</div>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
