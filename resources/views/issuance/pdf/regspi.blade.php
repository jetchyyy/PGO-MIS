<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 landscape; margin: 0; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000; background: #fff; margin: 0; padding: 12mm 15mm !important; }

    .doc-title { font-size: 13pt; font-weight: bold; text-align: center; letter-spacing: 1px; margin-bottom: 2px; }
    .doc-subtitle { font-size: 9pt; text-align: center; margin-bottom: 6px; font-style: italic; }
    .appendix { font-size: 8pt; text-align: right; margin-bottom: 2px; }

    .header-info { margin-bottom: 6px; font-size: 9pt; line-height: 2; }
    .header-info .field-label { font-weight: bold; }
    .header-info .field-val { border-bottom: 1px solid #000; display: inline-block; min-width: 180px; padding: 0 4px; }

    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 2px 4px; font-size: 8pt; vertical-align: middle; }
    th { font-weight: bold; text-align: center; background: #f5f5f5; line-height: 1.3; }
    td.center { text-align: center; }
    td.numeric { text-align: right; }
    .data-row td { height: 18px; }

    .sig-section { margin-top: 12px; }
    .sig-row { display: flex; justify-content: space-between; }
    .sig-block { flex: 1; padding: 4px 8px; }
    .sig-title { font-weight: bold; font-size: 9pt; margin-bottom: 25px; }
    .sig-line { border-top: 1px solid #000; width: 180px; margin-bottom: 2px; }
    .sig-name-label { font-size: 8pt; width: 180px; }
</style>
</head>
<body>

<div class="appendix">COA Circular 2022-004</div>
<div class="doc-title">REGISTRY OF SEMI-EXPENDABLE PROPERTY ISSUED</div>
<div class="doc-subtitle">(RegSPI)</div>

<div class="header-info">
    <span class="field-label">Entity Name:</span> <span class="field-val">{{ $issuance->entity_name ?? 'Provincial Government of Surigao Del Norte' }}</span>
    &nbsp;&nbsp;
    <span class="field-label">Fund Cluster:</span> <span class="field-val" style="min-width:100px;">{{ $issuance->fundCluster->code ?? '' }}</span>
    <br>
    <span class="field-label">Semi-Expendable Property:</span> <span class="field-val" style="min-width:250px;">{{ $issuance->lines->first()->description ?? '' }}</span>
    &nbsp;&nbsp;
    <span class="field-label">Semi-Expendable Property No.:</span> <span class="field-val" style="min-width:120px;">{{ $issuance->lines->first()->property_no ?? '' }}</span>
</div>

<table>
    <thead>
        <tr>
            <th style="width:8%">Date</th>
            <th style="width:12%">ICS No.</th>
            <th style="width:22%">Item Description</th>
            <th style="width:10%">Estimated<br>Useful Life</th>
            <th style="width:7%">Qty<br>Issued</th>
            <th style="width:7%">Unit</th>
            <th style="width:9%">Unit Cost</th>
            <th style="width:9%">Total Cost</th>
            <th style="width:10%">Classification<br>(SPLV/SPHV)</th>
            <th style="width:6%">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php($remainingRows = max(5 - $issuance->lines->count(), 0))
        @foreach($issuance->lines as $i => $line)
        <tr class="data-row">
            <td class="center">{{ $issuance->transaction_date ? \Carbon\Carbon::parse($issuance->transaction_date)->format('m/d/Y') : '' }}</td>
            <td class="center">{{ $issuance->control_no }}</td>
            <td>{{ $line->description }}</td>
            <td class="center">{{ $line->estimated_useful_life ?? '' }}</td>
            <td class="center">{{ $line->quantity }}</td>
            <td class="center">{{ $line->unit }}</td>
            <td class="numeric">{{ number_format($line->unit_cost, 2) }}</td>
            <td class="numeric">{{ number_format($line->total_cost, 2) }}</td>
            <td class="center">{{ strtoupper($line->classification) }}</td>
            <td>{{ $line->remarks ?? '' }}</td>
        </tr>
        @endforeach
        @for($i = 0; $i < $remainingRows; $i++)
        <tr class="data-row">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
        <tr>
            <td colspan="10" class="center" style="font-style:italic;">------nothing follows-----</td>
        </tr>
    </tbody>
</table>

{{-- Totals Row --}}
<table>
    <tr>
        <td style="width:66%; border:none;"></td>
        <td style="width:17%; border:1px solid #000; text-align:right; font-weight:bold; padding:3px 6px;">Total:</td>
        <td style="width:17%; border:1px solid #000; text-align:right; font-weight:bold; padding:3px 6px;">{{ number_format($issuance->lines->sum('total_cost'), 2) }}</td>
    </tr>
</table>

{{-- Signature Section --}}
<div class="sig-section">
    <div class="sig-row">
        <div class="sig-block">
            <div class="sig-title">Certified Correct:</div>
            <div class="sig-line"></div>
            <div class="sig-name-label">Signature over Printed Name of<br>Supply and/or Property Custodian</div>
        </div>
        <div class="sig-block">
            <div class="sig-title">Approved by:</div>
            <div class="sig-line"></div>
            <div class="sig-name-label">Signature over Printed Name of Head of<br>Agency/Entity or Authorized Representative</div>
        </div>
    </div>
</div>

</body>
</html>
