<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000; background: #fff; }
    @page { size: A4 portrait; margin: 12mm 15mm 12mm 15mm; }

    .annex { font-size: 8pt; text-align: right; }
    .doc-title { font-size: 16pt; font-weight: bold; text-align: center; margin: 6px 0 2px 0; letter-spacing: 1px; }
    .lgu-line { font-size: 10pt; font-weight: bold; margin-bottom: 6px; border: 1px solid #000; padding: 3px 6px; display: flex; align-items: center; gap: 8px; }
    .logo-area { display: inline-block; width: 40px; height: 40px; vertical-align: middle; margin-right: 8px; }
    .header-row { display: flex; gap: 0; margin-bottom: 0; }
    .header-left { flex: 1; border: 1px solid #000; border-right: none; }
    .header-right { flex: 0 0 200px; border: 1px solid #000; }
    .hfield { padding: 2px 5px; border-bottom: 1px solid #000; font-size: 9pt; min-height: 18px; }
    .hfield:last-child { border-bottom: none; }
    .hfield label { font-weight: bold; font-size: 8pt; }
    .par-no { font-size: 14pt; font-weight: bold; color: #cc0000; text-align: center; padding: 8px 5px; }

    table { width: 100%; border-collapse: collapse; margin-top: 0; }
    th, td { border: 1px solid #000; padding: 3px 4px; font-size: 9pt; vertical-align: top; }
    th { background: #f5f5f5; font-weight: bold; text-align: center; font-size: 8pt; }
    td.numeric { text-align: right; }
    td.center { text-align: center; }
    .total-row td { font-weight: bold; background: #f9f9f9; }
    .total-label { text-align: right; padding-right: 8px; }

    .sig-section { display: flex; margin-top: 10px; gap: 0; }
    .sig-box { flex: 1; border: 1px solid #000; padding: 6px 10px; }
    .sig-box + .sig-box { border-left: none; }
    .sig-label { font-weight: bold; font-size: 9pt; margin-bottom: 8px; }
    .sig-line { border-top: 1px solid #000; margin: 22px 30px 2px 30px; }
    .sig-name { text-align: center; font-weight: bold; font-size: 9pt; }
    .sig-position { text-align: center; font-size: 9pt; }
    .sig-date-line { border-top: 1px solid #000; margin: 18px 30px 2px 30px; }
    .sig-date-label { text-align: center; font-size: 9pt; }

    .instructions { font-size: 7.5pt; margin-top: 6px; }
    .instructions p { margin-bottom: 2px; }

    .item-rows td { min-height: 18px; height: 20px; }
</style>
</head>
<body>

<div class="annex">Annex A.1</div>

{{-- Header with Logo + Title --}}
<table style="border:none; margin-bottom:4px;">
    <tr>
        <td style="border:none; width:55px; vertical-align:middle; text-align:center;">
            @if(file_exists(public_path('images/surigaodelnorte.png')))
            <img src="{{ public_path('images/surigaodelnorte.png') }}" style="width:48px; height:48px; object-fit:contain;" alt="Seal">
            @endif
        </td>
        <td style="border:none; vertical-align:middle;">
            <div class="doc-title">PROPERTY ACKNOWLEDGEMENT RECEIPT</div>
        </td>
    </tr>
</table>

{{-- LGU Line --}}
<div style="border:1px solid #000; padding:3px 6px; font-weight:bold; font-size:10pt; margin-bottom:0;">
    LGU:&nbsp;&nbsp; PROVINCIAL GOVERNMENT OF SURIGAO DEL NORTE
</div>

{{-- Fund / Office / PAR No row --}}
<div style="display:flex;">
    <div style="flex:1; border:1px solid #000; border-top:none; border-right:none; padding:2px 5px; font-size:9pt;">
        <span style="font-weight:bold;">Fund:</span>&nbsp; {{ $issuance->fundCluster->code ?? '' }}
    </div>
    <div style="flex:0 0 220px; border:1px solid #000; border-top:none; padding:4px 8px; text-align:center;">
        <div class="par-no">{{ $issuance->control_no }}</div>
    </div>
</div>
<div style="border:1px solid #000; border-top:none; padding:2px 5px; font-size:9pt; margin-bottom:0;">
    <span style="font-weight:bold;">Office:</span>&nbsp; {{ $issuance->office->name ?? '' }}
</div>

{{-- Items Table --}}
<table>
    <thead>
        <tr>
            <th style="width:8%">Quantity</th>
            <th style="width:8%">Unit</th>
            <th style="width:30%">DESCRIPTION</th>
            <th style="width:15%">Property Number</th>
            <th style="width:12%">Date Acquired</th>
            <th style="width:12%">Unit Cost</th>
            <th style="width:15%">Amount</th>
        </tr>
    </thead>
    <tbody>
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
        {{-- Blank filler rows --}}
        @for($i = $issuance->lines->count(); $i < 10; $i++)
        <tr class="item-rows">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
        <tr class="total-row">
            <td colspan="5" class="total-label">TOTAL &gt;&gt;&gt;&gt;</td>
            <td class="numeric">{{ number_format($issuance->lines->sum('unit_cost'), 2) }}</td>
            <td class="numeric">{{ number_format($issuance->lines->sum('total_cost'), 2) }}</td>
        </tr>
    </tbody>
</table>

{{-- Signature Section --}}
<div class="sig-section" style="margin-top:8px;">
    <div class="sig-box">
        <div class="sig-label">Received by:</div>
        <div class="sig-line"></div>
        <div class="sig-name">{{ $issuance->employee->name ?? '' }}</div>
        <div class="sig-position" style="font-size:8pt;">Name</div>
        <div class="sig-date-line"></div>
        <div class="sig-position" style="font-size:8pt;">Position</div>
        <div class="sig-date-line"></div>
        <div class="sig-date-label">Date</div>
    </div>
    <div class="sig-box" style="border-left:none;">
        <div class="sig-label">Issued by:</div>
        <div class="sig-line"></div>
        <div class="sig-name">CHARLITO G. DE LA COSTA</div>
        <div class="sig-position" style="font-size:8pt;">Name</div>
        <div class="sig-date-line"></div>
        <div class="sig-position" style="font-size:8pt;">OIC-Provincial General Services Office</div>
        <div class="sig-date-line"></div>
        <div class="sig-date-label">Date</div>
    </div>
</div>

{{-- Instructions --}}
<div class="instructions" style="margin-top:6px;">
    <p><strong>Instruction:</strong></p>
    <p>To be prepared by Provincial General Services Office in three (3) copies.</p>
    <p>Original Copy: &nbsp;&nbsp;&nbsp; To be filed by Provincial General Services Office</p>
    <p>Duplicate Copy: To be attached to Disbursement Voucher and marked copy in file by Provincial General Services Office</p>
    <p>Third Copy: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; For Office concerned</p>
</div>

@if(($version ?? 1) > 1)
<div style="margin-top:5px; font-size:8pt; text-align:right; color:#666;">Print Version: {{ $version }}</div>
@endif

</body>
</html>
