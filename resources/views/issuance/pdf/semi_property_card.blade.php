<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000; background: #fff; }
    @page { size: A4 landscape; margin: 10mm 12mm; }

    .appendix { font-size: 8pt; text-align: right; }
    .doc-title { font-size: 14pt; font-weight: bold; text-align: center; letter-spacing: 1px; margin: 3px 0; }
    .doc-subtitle { font-size: 9pt; text-align: center; margin-bottom: 2px; }

    .header-info { margin-bottom: 4px; font-size: 9pt; line-height: 2; }
    .header-info span { border-bottom: 1px solid #000; display: inline-block; min-width: 180px; }

    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt; vertical-align: middle; }
    th { background: #f5f5f5; font-weight: bold; text-align: center; line-height: 1.3; }
    td.center { text-align: center; }
    td.numeric { text-align: right; }
    .data-row td { height: 18px; }

    .sig-section { display: flex; margin-top: 8px; gap: 0; align-items: flex-start; }
    .sig-block { flex: 1; padding: 4px 8px; }
    .sig-title { font-weight: bold; font-size: 9pt; margin-bottom: 20px; }
    .sig-line { border-top: 1px solid #000; margin: 0 20px 2px 0; width: 160px; }
    .sig-name-label { font-size: 8pt; text-align: center; width: 160px; }
    .sig-sub-label { font-size: 8pt; font-style: italic; margin-top: 2px; }
</style>
</head>
<body>

<div class="appendix">Appendix 73</div>
<div class="doc-title">REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT AND EQUIPMENT</div>
<div class="doc-subtitle">(Type of Property, Plant and Equipment)</div>

<div class="header-info">
    &nbsp;&nbsp; As at <span style="min-width:120px;">&nbsp;</span>
    &nbsp;&nbsp; Fund Cluster: <span>{{ $issuance->fundCluster->code ?? '' }}</span>
    &nbsp;&nbsp; For which: <span>{{ $issuance->employee->name ?? '' }}</span>, <span style="min-width:120px;">{{ $issuance->office->name ?? '' }}</span> (Official Designation) is accountable, having assumed such accountability on <span>(Date of assumption)</span>
</div>

<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:5%">Article #</th>
            <th rowspan="2" style="width:22%">DESCRIPTION</th>
            <th rowspan="2" style="width:12%">Property Number</th>
            <th colspan="2" style="width:16%">Unit of Measure / Unit Value</th>
            <th rowspan="2" style="width:9%">Quantity Per Property Card</th>
            <th rowspan="2" style="width:9%">Quantity Physical Count</th>
            <th colspan="2" style="width:14%">Shortage/Overage</th>
            <th rowspan="2" style="width:13%">REMARKS</th>
        </tr>
        <tr>
            <th style="width:8%">Unit</th>
            <th style="width:8%">Value</th>
            <th style="width:7%">Qty</th>
            <th style="width:7%">Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($issuance->lines as $i => $line)
        <tr class="data-row">
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $line->description }}</td>
            <td class="center">{{ $line->property_no ?? '' }}</td>
            <td class="center">{{ $line->unit }}</td>
            <td class="numeric">{{ number_format($line->unit_cost, 2) }}</td>
            <td class="center">{{ $line->quantity }}</td>
            <td class="center"></td>
            <td class="center"></td>
            <td class="numeric"></td>
            <td>{{ $line->remarks ?? '' }}</td>
        </tr>
        @endforeach
        @for($i = $issuance->lines->count(); $i < 12; $i++)
        <tr class="data-row">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
    </tbody>
</table>

{{-- Signature Section --}}
<div class="sig-section">
    <div class="sig-block" style="flex:1.2;">
        <div class="sig-title">Certified Correct by:</div>
        <div class="sig-line"></div>
        <div class="sig-name-label">Signature over Printed Name of<br>Inventory Committee Chair and<br>Members</div>
    </div>
    <div class="sig-block" style="flex:1;">
        <div style="margin-bottom:20px;">&nbsp;</div>
        <div class="sig-line"></div>
        <div class="sig-name-label">Approved by:</div>
        <div style="margin-top:8px;"></div>
        <div class="sig-line"></div>
        <div class="sig-name-label">Signature over Printed Name of Head of<br>Agency/Entity or Authorized Representative</div>
    </div>
    <div class="sig-block" style="flex:1;">
        <div style="margin-bottom:20px;">&nbsp;</div>
        <div class="sig-line"></div>
        <div class="sig-name-label">Verified by:</div>
        <div style="margin-top:8px;"></div>
        <div class="sig-line"></div>
        <div class="sig-name-label">Signature over Printed Name of COA<br>Representative</div>
    </div>
</div>

</body>
</html>
