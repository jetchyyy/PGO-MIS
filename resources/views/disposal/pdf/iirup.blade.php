<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 landscape; margin: 0; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 8pt; color: #000; background: #fff; margin: 0; padding: 9mm 10mm 8mm 10mm !important; }

    .annex { font-size: 8pt; text-align: right; font-style: italic; margin-bottom: 2px; }
    .doc-title { font-size: 12pt; font-weight: bold; text-align: center; letter-spacing: .3px; }
    .doc-subtitle { font-size: 8pt; text-align: center; margin-top: 1px; margin-bottom: 3px; }

    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    th, td { border: 1px solid #000; padding: 1px 2px; font-size: 7pt; vertical-align: middle; line-height: 1.05; }
    th { background: #f5f5f5; font-weight: bold; text-align: center; }
    td.center { text-align: center; }
    td.numeric { text-align: right; }
    .no-border, .no-border td { border: none; padding: 0; }
    .small { font-size: 7pt; }
    .tiny { font-size: 6pt; }
    .header-label { font-weight: bold; }
    .line-fill { border-bottom: 1px solid #000; display: inline-block; min-height: 11px; padding: 0 3px; vertical-align: bottom; }
    .item-rows td { height: 12px; }
    .sig-cell { height: 58px; vertical-align: top; padding: 4px 6px; }
    .sig-wrap { margin-top: 16px; text-align: center; }
    .sig-name { font-weight: bold; font-size: 8pt; text-decoration: underline; }
    .sig-role { font-size: 7pt; }
</style>
</head>
<body>

@php
    $pgso = $sig['pgso_head'] ?? null;
    $inspector = $sig['property_inspector'] ?? null;
    $coa = $sig['coa_representative'] ?? null;
    $governor = $sig['governor'] ?? null;
    $totalCost = (float) $disposal->lines->sum('total_cost');
    $totalDepreciation = (float) $disposal->lines->sum('accumulated_depreciation');
    $totalCarrying = (float) $disposal->lines->sum('carrying_amount');
    $totalAppraised = (float) $disposal->lines->sum(fn ($line) => $line->appraised_value ?? $line->carrying_amount ?? 0);
    $totalSales = (float) ($disposal->sale_amount ?? 0);
    $blankRows = max(22 - $disposal->lines->count(), 0);
@endphp

<div class="annex">Annex A.14</div>

<table class="no-border" style="margin-bottom:3px;">
    <tr>
        <td style="width:55px;"></td>
        <td>
            <div class="doc-title">INVENTORY AND INSPECTION REPORT OF UNSERVICEABLE PROPERTY</div>
            <div class="doc-subtitle">As of {{ $disposal->disposal_date?->format('F d, Y') ?? '' }}</div>
        </td>
        <td style="width:55px;"></td>
    </tr>
</table>

<table class="no-border" style="margin-bottom:5px;">
    <tr>
        <td style="width:60%;">
            <span class="header-label small">Entity Name:</span>
            <span class="line-fill small" style="width:315px; text-align:center;">{{ $disposal->entity_name ?? '' }}</span>
        </td>
        <td style="width:40%; text-align:right;">
            <span class="header-label small">Control No.:</span>
            <span class="line-fill small" style="width:140px; text-align:center;">{{ $documentControlNo ?? $disposal->control_no }}</span>
        </td>
    </tr>
    <tr>
        <td style="width:60%;">
            <span class="header-label small">Fund Cluster:</span>
            <span class="line-fill small" style="width:120px; text-align:center;">{{ $disposal->fundCluster->code ?? '' }}</span>
        </td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" class="center" style="padding-top:3px;">
            <span class="line-fill small" style="width:180px; text-align:center;">{{ $disposal->employee->name ?? '' }}</span>
            <span class="line-fill small" style="width:170px; margin-left:18px; text-align:center;">{{ $disposal->designation ?? '' }}</span>
            <span class="line-fill small" style="width:130px; margin-left:18px; text-align:center;">{{ $disposal->station ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center tiny">
            (Name of Accountable Officer)
            <span style="display:inline-block; width:118px;"></span>
            (Designation)
            <span style="display:inline-block; width:112px;"></span>
            (Station)
        </td>
    </tr>
</table>

<table>
    <colgroup>
        <col style="width:6.5%;">
        <col style="width:15%;">
        <col style="width:8%;">
        <col style="width:4%;">
        <col style="width:5%;">
        <col style="width:6.5%;">
        <col style="width:6.5%;">
        <col style="width:7.5%;">
        <col style="width:7.5%;">
        <col style="width:7%;">
        <col style="width:4.5%;">
        <col style="width:4.5%;">
        <col style="width:4.5%;">
        <col style="width:4.5%;">
        <col style="width:6.5%;">
        <col style="width:4.5%;">
        <col style="width:4.5%;">
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2" class="tiny">Date Acquired</th>
            <th rowspan="2" class="tiny">Particulars / Articles</th>
            <th rowspan="2" class="tiny">Property No.</th>
            <th rowspan="2" class="tiny">Qty</th>
            <th colspan="6" class="tiny">INVENTORY</th>
            <th colspan="4" class="tiny">INSPECTION AND DISPOSAL</th>
            <th rowspan="2" class="tiny">Appraised Value</th>
            <th colspan="2" class="tiny">RECORD OF SALES</th>
        </tr>
        <tr>
            <th class="tiny">Unit</th>
            <th class="tiny">Unit Cost</th>
            <th class="tiny">Total Cost</th>
            <th class="tiny">Accumulated Depreciation</th>
            <th class="tiny">Accumulated Impairment Losses</th>
            <th class="tiny">Carrying Amount</th>
            <th class="tiny">Sale</th>
            <th class="tiny">Transfer</th>
            <th class="tiny">Destruction</th>
            <th class="tiny">Others</th>
            <th class="tiny">OR No.</th>
            <th class="tiny">Amount</th>
        </tr>
        <tr>
            @for($i = 1; $i <= 17; $i++)
                <th class="tiny">({{ $i }})</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($disposal->lines as $line)
        <tr class="item-rows">
            <td class="center tiny">{{ $line->date_acquired?->format('m/d/Y') ?? '' }}</td>
            <td class="tiny">{{ $line->particulars }}</td>
            <td class="center tiny">{{ $line->property_no ?? '' }}</td>
            <td class="center">{{ $line->quantity }}</td>
            <td class="center tiny">{{ $line->unit ?? '' }}</td>
            <td class="numeric tiny">{{ number_format((float) $line->unit_cost, 2) }}</td>
            <td class="numeric tiny">{{ number_format((float) $line->total_cost, 2) }}</td>
            <td class="numeric tiny">{{ number_format((float) ($line->accumulated_depreciation ?? 0), 2) }}</td>
            <td class="numeric tiny">0.00</td>
            <td class="numeric tiny">{{ number_format((float) $line->carrying_amount, 2) }}</td>
            <td class="center tiny">{{ $disposal->disposal_method === 'public_auction' ? '/' : '' }}</td>
            <td class="center tiny">{{ $disposal->disposal_type === 'transfer' ? '/' : '' }}</td>
            <td class="center tiny">{{ $disposal->disposal_method === 'destruction' ? '/' : '' }}</td>
            <td class="center tiny">{{ in_array($disposal->disposal_method, ['throwing', 'others'], true) ? '/' : '' }}</td>
            <td class="numeric tiny">{{ number_format((float) ($line->appraised_value ?? $line->carrying_amount ?? 0), 2) }}</td>
            <td class="center tiny">{{ $disposal->or_no ?? '' }}</td>
            <td class="numeric tiny">{{ number_format($totalSales, 2) }}</td>
        </tr>
        @endforeach
        @for($i = 0; $i < $blankRows; $i++)
        <tr class="item-rows">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
        <tr>
            <td colspan="6" class="small" style="text-align:right;"><strong>TOTAL</strong></td>
            <td class="numeric tiny"><strong>{{ number_format($totalCost, 2) }}</strong></td>
            <td class="numeric tiny"><strong>{{ number_format($totalDepreciation, 2) }}</strong></td>
            <td class="numeric tiny"><strong>0.00</strong></td>
            <td class="numeric tiny"><strong>{{ number_format($totalCarrying, 2) }}</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="numeric tiny"><strong>{{ number_format($totalAppraised, 2) }}</strong></td>
            <td></td>
            <td class="numeric tiny"><strong>{{ number_format($totalSales, 2) }}</strong></td>
        </tr>
    </tbody>
</table>

<table style="margin-top:4px;">
    <tr>
        <td class="sig-cell small" style="width:33.33%;">
            I HEREBY request inspection and disposition, pursuant to Section 79 of PD 1445, of the property enumerated above.
            <div class="sig-wrap">
                @if($pgso?->signature_full_path)
                <div style="height:18px; margin-bottom:1px;"><img src="{{ $pgso->signature_full_path }}" alt="Signature" style="max-height:17px; max-width:150px;"></div>
                @endif
                <div class="sig-name">{{ $pgso->name ?? '' }}</div>
                <div class="sig-role">{{ $pgso->designation ?? 'Signature over Printed Name of Requesting Officer' }}</div>
            </div>
        </td>
        <td class="sig-cell small" style="width:33.33%;">
            I CERTIFY that I have inspected each and every article enumerated in this report, and that the disposition made thereof was, in my judgment, the best for the public interest.
            <div class="sig-wrap">
                @if($inspector?->signature_full_path)
                <div style="height:18px; margin-bottom:1px;"><img src="{{ $inspector->signature_full_path }}" alt="Signature" style="max-height:17px; max-width:150px;"></div>
                @endif
                <div class="sig-name">{{ $inspector->name ?? '' }}</div>
                <div class="sig-role">{{ $inspector->designation ?? 'Signature over Printed Name of Inspection Officer' }}</div>
            </div>
        </td>
        <td class="sig-cell small" style="width:33.33%;">
            I CERTIFY that I have witnessed the disposition of the articles enumerated on this report this ____ day of __________.
            <div class="sig-wrap">
                @if($coa?->signature_full_path)
                <div style="height:18px; margin-bottom:1px;"><img src="{{ $coa->signature_full_path }}" alt="Signature" style="max-height:17px; max-width:150px;"></div>
                @endif
                <div class="sig-name">{{ $coa->name ?? '' }}</div>
                <div class="sig-role">{{ $coa->designation ?? 'Signature over Printed Name of Witness' }}</div>
            </div>
        </td>
    </tr>
</table>

<table class="no-border" style="margin-top:2px;">
    <tr>
        <td style="width:33.33%; padding-left:2px;" class="small">
            <div style="font-size:7pt; margin-bottom:2px;">Approved by:</div>
            <div class="sig-wrap" style="margin-top:0; width:220px;">
                @if($governor?->signature_full_path)
                <div style="height:18px; margin-bottom:1px;"><img src="{{ $governor->signature_full_path }}" alt="Signature" style="max-height:17px; max-width:150px;"></div>
                @endif
                <div class="sig-name">{{ $governor->name ?? '' }}</div>
                <div class="sig-role">{{ $governor->designation ?? 'Signature over Printed Name of Approving Authority' }}</div>
            </div>
        </td>
        <td style="width:66.67%;"></td>
    </tr>
</table>

</body>
</html>
