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
    .item-rows td { min-height: 18px; }
    .chk { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
</style>
</head>
<body>

@php
    $pgso = $sig['pgso_head'] ?? null;
    $governor = $sig['governor'] ?? null;
    $inspector = $sig['property_inspector'] ?? null;
    $coa = $sig['coa_representative'] ?? null;
    $dtype = $disposal->disposal_type ?? '';
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
            <div class="doc-title">WASTE MATERIALS REPORT</div>
        </td>
    </tr>
</table>

{{-- Header Info --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="3" style="font-size:10pt; padding:3px 6px;">
            <span style="font-weight:bold;">Entity Name:</span>&nbsp;
            <span style="font-size:12pt; font-weight:bold;">{{ $disposal->entity_name ?? 'PROVINCE OF SURIGAO DEL NORTE' }}</span>
        </td>
        <td colspan="3" style="font-size:9pt; padding:3px 6px;">
            <span style="font-weight:bold;">Place of Storage:</span>&nbsp; {{ $disposal->station ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Fund Cluster:</span>&nbsp;
        </td>
        <td colspan="2" style="text-align:center; padding:4px 6px;">
            <span style="font-size:12pt; font-weight:bold; color:#cc0000;">{{ $disposal->control_no }}</span>
        </td>
        <td style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Date:</span>&nbsp;
            {{ $disposal->disposal_date ? $disposal->disposal_date->format('M. d, Y') : '' }}
        </td>
    </tr>
</table>

{{-- Items for Disposal Type --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="6" style="font-size:9pt; padding:4px 6px;">
            <span style="font-weight:bold;">ITEMS FOR DISPOSAL:</span>&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $dtype === 'unserviceable' ? '☑' : '☐' }}</span> Unserviceable&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $dtype === 'no_longer_needed' ? '☑' : '☐' }}</span> No longer needed&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $dtype === 'obsolete' ? '☑' : '☐' }}</span> Obsolete&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ !in_array($dtype, ['unserviceable','no_longer_needed','obsolete']) && $dtype ? '☑' : '☐' }}</span> Others ({{ $disposal->disposal_type_other ?? '' }})
        </td>
    </tr>
</table>

{{-- Items Table --}}
<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:5%">ITEM</th>
            <th rowspan="2" style="width:6%">QTY</th>
            <th rowspan="2" style="width:8%">Unit of<br>Issue</th>
            <th rowspan="2" style="width:30%">DESCRIPTION</th>
            <th rowspan="2" style="width:12%">Property<br>No.</th>
            <th rowspan="2" style="width:10%">Appraised<br>Value</th>
            <th colspan="2" style="width:22%">RECORD OF SALES</th>
        </tr>
        <tr>
            <th style="width:11%">OR Number</th>
            <th style="width:11%">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($disposal->lines as $i => $line)
        <tr class="item-rows">
            <td class="center">{{ $i + 1 }}</td>
            <td class="center">{{ $line->quantity }}</td>
            <td class="center">{{ $line->unit ?? 'pc' }}</td>
            <td>{{ $line->particulars }}</td>
            <td class="center">{{ $line->property_no ?? '' }}</td>
            <td class="numeric">{{ number_format($line->carrying_amount ?? 0, 2) }}</td>
            <td class="center">{{ $disposal->or_no ?? '' }}</td>
            <td class="numeric">{{ number_format($disposal->sale_amount ?? 0, 2) }}</td>
        </tr>
        @endforeach
        @for($i = $disposal->lines->count(); $i < 10; $i++)
        <tr class="item-rows">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
    </tbody>
</table>

{{-- Certified Correct / Disposal Approved Signatures --}}
<table style="margin-bottom:0;">
    <tr>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Certified Correct:</div>
            <div style="margin-top:28px; text-align:center;">
                @if($pgso?->signature_full_path)
                <div style="height:26px; margin-bottom:2px;"><img src="{{ $pgso->signature_full_path }}" alt="Signature" style="max-height:24px; max-width:180px;"></div>
                @endif
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $pgso->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $pgso->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Disposal Approved:</div>
            <div style="margin-top:28px; text-align:center;">
                @if($governor?->signature_full_path)
                <div style="height:26px; margin-bottom:2px;"><img src="{{ $governor->signature_full_path }}" alt="Signature" style="max-height:24px; max-width:180px;"></div>
                @endif
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $governor->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $governor->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
    </tr>
</table>

{{-- Certificate of Inspection --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="2" style="padding:6px 10px; text-align:center; font-weight:bold; font-size:10pt; background:#f5f5f5;">
            CERTIFICATE OF INSPECTION
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding:6px 10px; font-size:9pt;">
            I hereby certify that I have inspected each and every article enumerated in this Report and that the disposition made thereof was, in my judgment, the best for the public interest.
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding:4px 10px; font-size:9pt;">
            <span style="font-weight:bold;">Disposal / Destruction was made by:</span>&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $dtype === 'sale' || ($disposal->sale_amount ?? 0) > 0 ? '☑' : '☐' }}</span> Public Auction&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $dtype === 'destruction' ? '☑' : '☐' }}</span> Destruction&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $dtype === 'throwing' ? '☑' : '☐' }}</span> Throwing&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ !in_array($dtype, ['sale','destruction','throwing','unserviceable','no_longer_needed','obsolete']) && $dtype ? '☑' : '☐' }}</span> Others
        </td>
    </tr>
</table>

{{-- Inspector / Witness Signatures --}}
<table style="margin-bottom:0;">
    <tr>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Property Inspector:</div>
            <div style="margin-top:28px; text-align:center;">
                @if($inspector?->signature_full_path)
                <div style="height:26px; margin-bottom:2px;"><img src="{{ $inspector->signature_full_path }}" alt="Signature" style="max-height:24px; max-width:180px;"></div>
                @endif
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $inspector->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $inspector->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:50%; padding:6px 10px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Witness (COA Representative):</div>
            <div style="margin-top:28px; text-align:center;">
                @if($coa?->signature_full_path)
                <div style="height:26px; margin-bottom:2px;"><img src="{{ $coa->signature_full_path }}" alt="Signature" style="max-height:24px; max-width:180px;"></div>
                @endif
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $coa->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $coa->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:180px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
    </tr>
</table>

</body>
</html>
