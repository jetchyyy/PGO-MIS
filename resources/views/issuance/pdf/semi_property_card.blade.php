<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    @page { size: A4 landscape; margin: 0; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000; background: #fff; margin: 0; padding: 12mm 15mm !important; }

    .doc-title { font-size: 14pt; font-weight: bold; text-align: center; letter-spacing: 2px; margin-bottom: 4px; }
    .appendix { font-size: 8pt; text-align: right; }

    .top-header { display: flex; justify-content: space-between; margin-bottom: 3px; }
    .top-left { line-height: 1.8; }
    .top-right { line-height: 1.8; text-align: right; }
    .hfield { font-size: 9pt; }
    .hfield-label { font-weight: bold; }

    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 2px 4px; font-size: 8pt; vertical-align: middle; }
    th { font-weight: bold; text-align: center; background: #f5f5f5; line-height: 1.3; }
    td.center { text-align: center; }
    td.numeric { text-align: right; }
    .data-row td { height: 18px; }
</style>
</head>
<body>

<div class="appendix">COA Circular 2022-004</div>
<div class="doc-title">SEMI-EXPENDABLE PROPERTY CARD</div>

<div class="top-header">
    <div class="top-left">
        <div class="hfield"><span class="hfield-label">Entity Name:</span>&nbsp;&nbsp;<span style="border-bottom:1px solid #000; display:inline-block; min-width:200px;">{{ $issuance->entity_name ?? 'Provincial Government of Surigao Del Norte' }}</span></div>
        <div class="hfield"><span class="hfield-label">Semi-Expendable Property:</span>&nbsp;&nbsp;<span style="border-bottom:1px solid #000; display:inline-block; min-width:200px;">{{ $issuance->lines->first()->description ?? '' }}</span></div>
        <div class="hfield"><span class="hfield-label">Description:</span>&nbsp;&nbsp;<span style="border-bottom:1px solid #000; display:inline-block; min-width:200px;">{{ $issuance->lines->first()->remarks ?? '' }}</span></div>
    </div>
    <div class="top-right">
        <div class="hfield"><span class="hfield-label">Fund Cluster:</span>&nbsp;&nbsp;<span style="border-bottom:1px solid #000; display:inline-block; min-width:120px;">{{ $issuance->fundCluster->code ?? '' }}</span></div>
        <div class="hfield"><span class="hfield-label">Semi-Expendable Property No.:</span>&nbsp;&nbsp;<span style="border-bottom:1px solid #000; display:inline-block; min-width:120px;">{{ $issuance->lines->first()->property_no ?? '' }}</span></div>
        <div class="hfield"><span class="hfield-label">UACS Object Code:</span>&nbsp;&nbsp;<span style="border-bottom:1px solid #000; display:inline-block; min-width:120px;">{{ $issuance->semiExpendableCard->uacs_object_code ?? '' }}</span></div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:9%">Date</th>
            <th rowspan="2" style="width:14%">Reference/<br>ICS No.</th>
            <th colspan="2" style="width:16%">Receipt</th>
            <th rowspan="2" style="width:18%">Issue/Transfer/Disposal<br>Office/Officer</th>
            <th colspan="2" style="width:16%">Balance</th>
            <th rowspan="2" style="width:10%">Amount</th>
            <th rowspan="2" style="width:17%">Remarks</th>
        </tr>
        <tr>
            <th style="width:8%">Qty.</th>
            <th style="width:8%">Unit Cost</th>
            <th style="width:8%">Qty.</th>
            <th style="width:8%">Total Cost</th>
        </tr>
    </thead>
    <tbody>
        {{-- First entry from the issuance itself --}}
        <tr class="data-row">
            <td class="center">{{ $issuance->transaction_date ? \Carbon\Carbon::parse($issuance->transaction_date)->format('m/d/Y') : '' }}</td>
            <td class="center">{{ $issuance->control_no }}</td>
            <td class="center">{{ $issuance->lines->sum('quantity') }}</td>
            <td class="numeric">{{ number_format($issuance->lines->first()->unit_cost ?? 0, 2) }}</td>
            <td>{{ $issuance->office->name ?? '' }} / {{ $issuance->employee->name ?? '' }}</td>
            <td class="center">{{ $issuance->lines->sum('quantity') }}</td>
            <td class="numeric">{{ number_format($issuance->lines->sum('total_cost'), 2) }}</td>
            <td class="numeric">{{ number_format($issuance->lines->sum('total_cost'), 2) }}</td>
            <td>{{ $issuance->document_type }} — {{ $issuance->lines->first()->classification === 'sphv' ? 'High Value' : 'Low Value' }}</td>
        </tr>
        {{-- Blank rows for future entries --}}
        @for($i = 0; $i < 22; $i++)
        <tr class="data-row">
            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        @endfor
    </tbody>
</table>

</body>
</html>
