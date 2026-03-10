<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 portrait; margin: 0; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000; background: #fff; margin: 0; padding: 15mm 18mm !important; }

    .doc-title { font-size: 15pt; font-weight: bold; text-align: center; letter-spacing: 1px; }

    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 2px 4px; font-size: 9pt; vertical-align: top; }
    th { background: #f5f5f5; font-weight: bold; text-align: center; font-size: 8pt; }
    td.numeric { text-align: right; }
    td.center { text-align: center; }
    .total-row td { font-weight: bold; background: #f9f9f9; }
    .item-rows td { min-height: 18px; }
    .instructions { font-size: 7.5pt; margin-top: 4px; line-height: 1.5; }
</style>
</head>
<body>

@php
    $pgso = $sig['pgso_head'] ?? null;
@endphp

{{-- Header with Logo + Title --}}
<table style="border:none; margin-bottom:2px;">
    <tr>
        <td style="border:none; width:55px; vertical-align:middle; text-align:center;">
            @if(file_exists(public_path('images/surigaodelnorte.png')))
            <img src="{{ public_path('images/surigaodelnorte.png') }}" style="width:50px; height:50px; object-fit:contain;" alt="Seal">
            @endif
        </td>
        <td style="border:none; vertical-align:middle;">
            <div class="doc-title">PROPERTY ACKNOWLEDGEMENT RECEIPT</div>
        </td>
    </tr>
</table>

{{-- LGU Line --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="7" style="font-size:10pt; padding:3px 6px;">
            <span style="font-weight:bold;">LGU:</span>&nbsp;
            <span style="font-size:12pt; font-weight:bold;">PROVINCIAL GOVERNMENT OF SURIGAO DEL NORTE</span>
        </td>
    </tr>
</table>

{{-- Fund / Office / PAR No row --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Fund:</span>&nbsp; {{ $issuance->fundCluster->code ?? '' }}
        </td>
        <td colspan="2" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Office:</span>&nbsp;
            <span style="font-size:11pt; font-weight:bold;">{{ $issuance->office->name ?? '' }}</span>
        </td>
        <td colspan="2" style="text-align:center; padding:4px 6px;">
            <span style="font-size:12pt; font-weight:bold; color:#cc0000;">{{ $documentControlNo ?? $issuance->control_no }}</span>
        </td>
    </tr>
</table>

{{-- Items Table --}}
<table>
    <thead>
        <tr>
            <th style="width:7%">Quantity</th>
            <th style="width:7%">Unit</th>
            <th style="width:30%">DESCRIPTION</th>
            <th style="width:14%">Property Number</th>
            <th style="width:12%">Date Acquired</th>
            <th style="width:12%">Unit Cost</th>
            <th style="width:13%">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php($remainingRows = max(5 - $issuance->lines->count(), 0))
        @foreach($issuance->lines as $line)
        <tr class="item-rows">
            <td class="center">{{ $line->quantity }}</td>
            <td class="center">{{ $line->unit }}</td>
            <td>{{ $line->description }}</td>
            <td class="center">{{ $line->property_no ?? '' }}</td>
            <td class="center">{{ $line->date_acquired ? \Carbon\Carbon::parse($line->date_acquired)->format('m/d/Y') : '' }}</td>
            <td class="numeric">{{ number_format($line->unit_cost, 2) }}</td>
            <td class="numeric">{{ number_format($line->total_cost, 2) }}</td>
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
        <tr class="total-row">
            <td colspan="5" style="text-align:right; padding-right:8px;">TOTAL &gt;&gt;&gt;&gt;</td>
            <td class="numeric">{{ number_format($issuance->lines->sum('unit_cost'), 2) }}</td>
            <td class="numeric">{{ number_format($issuance->lines->sum('total_cost'), 2) }}</td>
        </tr>
    </tbody>
</table>

{{-- Signature Section --}}
<table style="margin-bottom:0;">
    <tr>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:6px;">Received by:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $issuance->employee->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $issuance->employee->designation ?? $issuance->employee->position ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:20px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:6px;">Issued by:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $pgso->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $pgso->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:20px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
    </tr>
</table>

{{-- Instructions --}}
<div class="instructions">
    <p><strong><em>Instructions:</em></strong></p>
    <p><em>To be prepared by Provincial General Services Office in three (3) copies.</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Original Copy: To be filed by Provincial General Services Office</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Duplicate Copy: To be attached to Disbursement Voucher and marked copy on file by PGSO</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Third Copy: For Office concerned</em></p>
</div>

</body>
</html>
