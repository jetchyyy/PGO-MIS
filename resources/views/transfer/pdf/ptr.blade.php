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
    .instructions { font-size: 7.5pt; margin-top: 4px; line-height: 1.5; }
</style>
</head>
<body>

@php
    $pgso = $sig['pgso_head'] ?? null;
    $governor = $sig['governor'] ?? null;
    $type = $transfer->transfer_type ?? '';
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
            <div class="doc-title">PROPERTY TRANSFER REPORT</div>
        </td>
    </tr>
</table>

{{-- Header Info --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="3" style="font-size:10pt; padding:3px 6px;">
            <span style="font-weight:bold;">Entity Name:</span>&nbsp;
            <span style="font-size:12pt; font-weight:bold;">{{ $transfer->entity_name ?? 'PROVINCE OF SURIGAO DEL NORTE' }}</span>
        </td>
        <td colspan="3" style="font-size:9pt; padding:3px 6px;">
            <span style="font-weight:bold;">Fund Cluster:</span>&nbsp; {{ $transfer->fundCluster->code ?? '' }}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">From Accountable Officer:</span>&nbsp;
            <span style="font-size:11pt; font-weight:bold;">{{ $transfer->fromEmployee->name ?? '' }}</span>
        </td>
        <td colspan="3" style="text-align:center; padding:4px 6px;">
            <span style="font-size:12pt; font-weight:bold; color:#cc0000;">{{ $transfer->control_no }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">To Accountable Officer:</span>&nbsp;
            <span style="font-size:11pt; font-weight:bold;">{{ $transfer->toEmployee->name ?? '' }}</span>
        </td>
        <td colspan="3" style="font-size:9pt; padding:2px 6px;">
            <span style="font-weight:bold;">Date:</span>&nbsp;
            {{ $transfer->transfer_date ? $transfer->transfer_date->format('M. d, Y') : '' }}
        </td>
    </tr>
</table>

{{-- Transfer Type Checkboxes --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="6" style="font-size:9pt; padding:4px 6px;">
            <span style="font-weight:bold;">Transfer Type:</span>&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $type === 'donation' ? '☑' : '☐' }}</span> Donation&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $type === 'relocate' ? '☑' : '☐' }}</span> Relocate&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $type === 'retirement_resignation' ? '☑' : '☐' }}</span> Retirement/Resignation&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ $type === 'reassignment_recalled' ? '☑' : '☐' }}</span> Reassignment/Recalled&nbsp;&nbsp;&nbsp;
            <span class="chk">{{ !in_array($type, ['donation','relocate','retirement_resignation','reassignment_recalled']) && $type ? '☑' : '☐' }}</span> Others (Specify: {{ $transfer->transfer_type_other ?? '' }})
        </td>
    </tr>
</table>

{{-- Items Table --}}
<table>
    <thead>
        <tr>
            <th style="width:11%">Date<br>Acquired</th>
            <th style="width:13%">Inventory Item No.<br>/Property No.</th>
            <th style="width:12%">PAR No.</th>
            <th style="width:28%">Description</th>
            <th style="width:12%">Amount</th>
            <th style="width:14%">Condition of<br>Inventory</th>
        </tr>
    </thead>
    <tbody>
        @php($remainingRows = max(5 - $transfer->lines->count(), 0))
        @foreach($transfer->lines as $line)
        <tr class="item-rows">
            <td class="center">{{ $line->date_acquired ? $line->date_acquired->format('m/d/Y') : '' }}</td>
            <td class="center">{{ $line->reference_no ?? '' }}</td>
            <td class="center">{{ $line->reference_no ?? '' }}</td>
            <td>{{ $line->description }}</td>
            <td class="numeric">{{ number_format($line->amount, 2) }}</td>
            <td class="center">{{ ucfirst($line->condition ?? '') }}</td>
        </tr>
        @endforeach
        @for($i = 0; $i < $remainingRows; $i++)
        <tr class="item-rows">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
        <tr>
            <td colspan="6" class="center" style="font-style:italic;">------nothing follows-----</td>
        </tr>
    </tbody>
</table>

{{-- Supplier / PO / Location / Reasons --}}
<table style="margin-bottom:0;">
    <tr>
        <td colspan="3" style="font-size:8pt; padding:2px 4px; border-top:none;">
            <span style="font-weight:bold;">Supplier:</span>&nbsp; {{ $transfer->entity_name ?? '' }}
        </td>
        <td colspan="3" style="font-size:8pt; padding:2px 4px; border-top:none;">
            <span style="font-weight:bold;">P.O. No.:</span>&nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:8pt; padding:2px 4px;">
            <span style="font-weight:bold;">Location:</span>&nbsp;
        </td>
        <td colspan="3" style="font-size:8pt; padding:2px 4px;">
            <span style="font-weight:bold;">Reasons for Transfer:</span>&nbsp; {{ ucfirst(str_replace('_', ' ', $type)) }}{{ $transfer->transfer_type_other ? ' — '.$transfer->transfer_type_other : '' }}
        </td>
    </tr>
</table>

{{-- 3-Column Signature Section --}}
<table style="margin-bottom:0;">
    <tr>
        <td style="width:34%; padding:6px 8px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Approved by:</div>
            <div style="margin-top:28px; text-align:center;">
                @if($governor?->signature_full_path)
                <div style="height:26px; margin-bottom:2px;"><img src="{{ $governor->signature_full_path }}" alt="Signature" style="max-height:24px; max-width:150px;"></div>
                @endif
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $governor->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $governor->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:150px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:33%; padding:6px 8px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Released / Issued by:</div>
            <div style="margin-top:28px; text-align:center;">
                @if($pgso?->signature_full_path)
                <div style="height:26px; margin-bottom:2px;"><img src="{{ $pgso->signature_full_path }}" alt="Signature" style="max-height:24px; max-width:150px;"></div>
                @endif
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $pgso->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $pgso->designation ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:150px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
        <td style="width:33%; padding:6px 8px; vertical-align:top;">
            <div style="font-weight:bold; font-size:9pt; margin-bottom:4px;">Received by:</div>
            <div style="margin-top:28px; text-align:center;">
                <div style="font-weight:bold; font-size:10pt; text-decoration:underline;">{{ $transfer->toEmployee->name ?? '' }}</div>
                <div style="font-size:9pt; font-style:italic;">{{ $transfer->toEmployee->designation ?? $transfer->toEmployee->position ?? '' }}</div>
            </div>
            <div style="border-top:1px solid #000; margin:18px auto 2px auto; width:150px;"></div>
            <div style="text-align:center; font-size:9pt;">Date</div>
        </td>
    </tr>
</table>

{{-- Instructions --}}
<div class="instructions">
    <p><strong><em>Instructions:</em></strong></p>
    <p><em>To be prepared in three (3) copies by PGSO.</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Original Copy: For PGSO file</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Duplicate Copy: For releasing officer</em></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Third Copy: For receiving officer</em></p>
</div>

</body>
</html>
