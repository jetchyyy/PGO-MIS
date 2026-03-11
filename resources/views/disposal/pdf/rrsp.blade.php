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
    .item-rows td { min-height: 18px; }
    .instructions { font-size: 7.5pt; margin-top: 4px; line-height: 1.5; }
</style>
</head>
<body>

@php
    $pgso = $sig['pgso_head'] ?? null;
@endphp

<div class="annex">Disposal Pre-Requirement</div>

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

<table style="margin-bottom:0;">
    <tr>
        <td colspan="4" style="font-size:10pt; padding:3px 6px;">
            <span style="font-weight:bold;">Entity Name:</span>&nbsp;
            <span style="font-size:12pt; font-weight:bold;">{{ $disposal->entity_name ?? 'PROVINCE OF SURIGAO DEL NORTE' }}</span>
        </td>
        <td colspan="2" style="font-size:9pt; padding:3px 6px;">
            <span style="font-weight:bold;">Date:</span>&nbsp;
            {{ $disposal->disposal_date ? $disposal->disposal_date->format('M. d, Y') : '' }}
        </td>
        <td style="text-align:center; padding:4px 6px;">
            <span style="font-size:12pt; font-weight:bold; color:#cc0000;">{{ $documentControlNo ?? $disposal->control_no }}</span>
        </td>
    </tr>
</table>

<table style="margin-bottom:0;">
    <tr>
        <td colspan="7" style="font-size:9pt; padding:4px 6px; text-align:center; font-style:italic;">
            This is to acknowledge receipt of the returned semi-expendable property prior to disposal processing.
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th style="width:22%">Item Description</th>
            <th style="width:7%">Quantity</th>
            <th style="width:11%">Date<br>Acquired</th>
            <th style="width:14%">Property No.<br>/ICS Number</th>
            <th style="width:11%">Amount</th>
            <th style="width:16%">End-User</th>
            <th style="width:14%">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php($remainingRows = max(5 - $disposal->lines->count(), 0))
        @foreach($disposal->lines as $line)
        <tr class="item-rows">
            <td>{{ $line->particulars }}</td>
            <td class="center">{{ $line->quantity }}</td>
            <td class="center">{{ $line->date_acquired ? $line->date_acquired->format('m/d/Y') : '' }}</td>
            <td class="center">{{ $line->property_no ?? '' }}</td>
            <td class="numeric">{{ number_format($line->appraised_value ?? $line->total_cost ?? $line->unit_cost ?? 0, 2) }}</td>
            <td class="center">{{ $disposal->employee->name ?? '' }}</td>
            <td>{{ $line->remarks ?? '' }}</td>
        </tr>
        @endforeach
        @for($i = 0; $i < $remainingRows; $i++)
        <tr class="item-rows">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
        <tr>
            <td colspan="7" class="center" style="font-style:italic;">------nothing follows-----</td>
        </tr>
    </tbody>
</table>

<table style="margin-bottom:0;">
    <tr>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:6px;">RETURNED BY:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $disposal->employee->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $disposal->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:20px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:6px;">RECEIVED BY:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $pgso->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $pgso->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:20px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
    </tr>
</table>

<div class="instructions">
    <p><strong><em>Instructions:</em></strong></p>
    <p><em>To be prepared before the disposal request is processed.</em></p>
    <p><em>Use this form for items with a value below PHP 50,000.</em></p>
</div>

</body>
</html>
