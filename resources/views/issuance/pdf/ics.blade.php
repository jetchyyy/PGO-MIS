<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 portrait; margin: 0; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000; background: #fff; margin: 0; padding: 15mm 18mm !important; }

    .annex { font-size: 8pt; text-align: right; font-style: italic; }
    .doc-title { font-size: 15pt; font-weight: bold; text-align: center; letter-spacing: 1px; }

    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 2px 4px; font-size: 9pt; vertical-align: top; }
    th { background: #f5f5f5; font-weight: bold; text-align: center; font-size: 8pt; }
    td.numeric { text-align: right; }
    td.center { text-align: center; }
    .item-rows td { min-height: 18px; }

    .sig-box { border: 1px solid #000; padding: 6px 10px; }
    .sig-label { font-weight: bold; font-size: 9pt; margin-bottom: 6px; }
    .sig-line { border-top: 1px solid #000; margin: 26px auto 2px auto; width: 200px; }
    .sig-name { text-align: center; font-weight: bold; font-size: 10pt; text-decoration: underline; }
    .sig-position { text-align: center; font-size: 9pt; font-style: italic; }
    .sig-date-line { border-top: 1px solid #000; margin: 20px auto 2px auto; width: 160px; }
    .sig-date-label { text-align: center; font-size: 9pt; }

    .instructions { font-size: 7.5pt; margin-top: 4px; line-height: 1.5; }
</style>
</head>
<body>

@php
    $pgso = $sig['pgso_head'] ?? null;
@endphp

<div class="annex">Annex A.3</div>

{{-- Header with Logo + Title --}}
<table style="border:none; margin-bottom:2px;">
    <tr>
        <td style="border:none; width:55px; vertical-align:middle; text-align:center;">
            @if(file_exists(public_path('images/surigaodelnorte.png')))
            <img src="{{ public_path('images/surigaodelnorte.png') }}" style="width:50px; height:50px; object-fit:contain;" alt="Seal">
            @endif
        </td>
        <td style="border:none; vertical-align:middle;">
            <div class="doc-title">INVENTORY CUSTODIAN SLIP</div>
        </td>
    </tr>
</table>

{{-- Header Info --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="5" style="font-size:10pt; padding:3px 6px;">
            <span style="font-weight:bold;">Entity Name:</span>&nbsp;
            <span style="font-size:12pt; font-weight:bold;">PROVINCE OF SURIGAO DEL NORTE</span>
        </td>
        <td colspan="3" style="font-size:12pt; font-weight:bold; color:#cc0000; text-align:center; padding:4px 6px;">
            {{ $issuance->control_no }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Fund Cluster:</span>&nbsp; {{ $issuance->fundCluster->code ?? '' }}
        </td>
        <td colspan="2" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Office/RC:</span>&nbsp;
            <span style="font-size:11pt; font-weight:bold;">{{ $issuance->office->name ?? '' }}</span>
        </td>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Date:</span>&nbsp;
            {{ $issuance->transaction_date ? \Carbon\Carbon::parse($issuance->transaction_date)->format('M. d, Y') : '' }}
        </td>
    </tr>
</table>

{{-- Items Table --}}
<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:7%">Quantity</th>
            <th rowspan="2" style="width:7%">Unit</th>
            <th colspan="2" style="width:20%">Amount</th>
            <th rowspan="2" style="width:28%">Description</th>
            <th rowspan="2" style="width:14%">Item No.</th>
            <th rowspan="2" style="width:11%">Estimated<br>Useful Life</th>
        </tr>
        <tr>
            <th style="width:10%">Unit Cost</th>
            <th style="width:10%">Total Cost</th>
        </tr>
    </thead>
    <tbody>
        @php($remainingRows = max(5 - $issuance->lines->count(), 0))
        @foreach($issuance->lines as $line)
        <tr class="item-rows">
            <td class="center">{{ $line->quantity }}</td>
            <td class="center">{{ $line->unit }}</td>
            <td class="numeric">{{ number_format($line->unit_cost, 2) }}</td>
            <td class="numeric">{{ number_format($line->total_cost, 2) }}</td>
            <td>{{ $line->description }}</td>
            <td class="center" style="color:#cc0000;">{{ $line->property_no ?? '' }}</td>
            <td class="center">{{ $line->estimated_useful_life ?? '' }}</td>
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

{{-- Supplier / PO / Date Acquired --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="7" style="font-size:8pt; padding:2px 4px; border-top:none;">
            <span style="font-weight:bold;">Supplier:</span>&nbsp; {{ $issuance->entity_name ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:8pt; padding:2px 4px;">
            <span style="font-weight:bold;">P.O. No.:</span>&nbsp; {{ $issuance->reference_no ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:8pt; padding:2px 4px;">
            <span style="font-weight:bold;">Date Acquired:</span>&nbsp;
            @if($issuance->lines->first() && $issuance->lines->first()->date_acquired)
                {{ \Carbon\Carbon::parse($issuance->lines->first()->date_acquired)->format('m/d/Y') }}
            @endif
        </td>
    </tr>
</table>

{{-- Signature Section --}}
<table style="margin-bottom:0;">
    <tr>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:6px;">RECEIVED FROM:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $pgso->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $pgso->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:20px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:6px;">RECEIVED BY:</div>
            <div style="margin-top:30px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $issuance->employee->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $issuance->employee->designation ?? $issuance->employee->position ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:20px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
    </tr>
</table>

{{-- Instructions --}}
<div class="instructions">
    <p><strong><em>Instructions:</em></strong></p>
    <p><em>To be prepared by Office concerned in four copies</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Original Copy: To be Filed by Office concerned</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Duplicate Copy: To be attached to Disbursement Voucher and marked copy on file by PGSO</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Third Copy: To be filed by end-user</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Fourth Copy: To be filed by PGSO</em></p>
</div>

</body>
</html>
