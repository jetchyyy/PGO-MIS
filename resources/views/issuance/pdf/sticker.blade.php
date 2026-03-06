<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Property Sticker - {{ $issuance->control_no }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7.2px; color: #111; }
        .sheet { display: grid; grid-template-columns: repeat(2, 90mm); gap: 5mm; }
        .tag {
            width: 90mm;
            height: 68mm;
            border: 0.4px solid #111;
            padding: 2mm;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }
        .seal {
            position: absolute;
            top: 2mm;
            left: 2mm;
            width: 8mm;
            height: 8mm;
        }
        .gov {
            position: absolute;
            top: 2mm;
            left: 11mm;
            right: 2mm;
            font-size: 5.8px;
            line-height: 1.1;
            font-weight: 700;
        }
        .content {
            position: absolute;
            top: 10.5mm;
            left: 2mm;
            right: 22mm;
            bottom: 13.5mm;
            overflow: hidden;
        }
        .row {
            display: grid;
            grid-template-columns: 25mm 1fr;
            gap: 0.8mm;
            margin-bottom: 0.45mm;
            align-items: end;
        }
        .label { font-weight: 700; white-space: nowrap; font-size: 8px; }
        .value {
            border-bottom: 0.3px solid #555;
            white-space: normal;
            overflow: hidden;
            min-height: 3.15mm;
            line-height: 1.08;
            font-size: 8px;
        }
        .qr {
            position: absolute;
            right: 2mm;
            bottom: 8mm;
            width: 18.5mm;
            text-align: center;
        }
        .qr img {
            width: 13.5mm;
            height: 13.5mm;
            border: 0.3px solid #999;
            display: block;
            margin: 0 auto 0.5mm;
        }
        .qr-code {
            font-size: 4.9px;
            line-height: 1.05;
            word-break: break-word;
        }
        .warning {
            position: absolute;
            left: 2mm;
            bottom: 2mm;
            color: #c1121f;
            font-size: 6.1px;
            font-style: italic;
            font-weight: 700;
        }
    </style>
</head>
<body>
@php
    $committeeName = $sig['property_inspector']->name ?? 'Inventory Committee';
    $stickers = collect();
    foreach ($issuance->lines as $line) {
        $inventoryRows = $inventoryByLine[$line->id] ?? collect();
        if ($inventoryRows->isNotEmpty()) {
            foreach ($inventoryRows as $inv) {
                $stickers->push(['line' => $line, 'inventory' => $inv]);
            }
        } else {
            for ($i = 0; $i < (int) $line->quantity; $i++) {
                $stickers->push(['line' => $line, 'inventory' => null]);
            }
        }
    }
@endphp

<div class="sheet">
@foreach($stickers as $entry)
    @php
        $line = $entry['line'];
        $inventory = $entry['inventory'];
        $acqDate = optional($line->date_acquired)->format('M d, Y') ?? optional($issuance->transaction_date)->format('M d, Y');
    @endphp
    <div class="tag">
        <img class="seal" src="{{ public_path('images/surigaodelnorte.png') }}" alt="Seal">
        <div class="gov">Republic of the Philippines<br>Province of Surigao del Norte</div>

        <div class="content">
            <div class="row"><div class="label">Name of Office:</div><div class="value">{{ $issuance->office->name ?? 'N/A' }}</div></div>
            <div class="row"><div class="label">Description:</div><div class="value">{{ $line->description }}</div></div>
            <div class="row"><div class="label">Property Number:</div><div class="value">{{ $line->property_no ?? 'N/A' }}</div></div>
            <div class="row"><div class="label">Model:</div><div class="value">{{ $inventory?->model ?? 'N/A' }}</div></div>
            <div class="row"><div class="label">Serial Number:</div><div class="value">{{ $inventory?->serial_number ?? 'N/A' }}</div></div>
            <div class="row"><div class="label">Acq. Cost/Date:</div><div class="value">Php {{ number_format((float) $line->unit_cost, 2) }} - {{ $acqDate }}</div></div>
            <div class="row"><div class="label">Person Accountable:</div><div class="value">{{ $issuance->employee->name ?? 'N/A' }}</div></div>
            <div class="row"><div class="label">Signature Name:</div><div class="value">{{ strtoupper($committeeName) }}</div></div>
        </div>

        <div class="qr">
            @if($inventory)
                <img src="{{ $inventory->qrDataUri(220) }}" alt="QR">
                <div class="qr-code">{{ $inventory->inventory_code }}</div>
            @else
                <div class="qr-code">QR pending</div>
            @endif
        </div>

        <div class="warning">Tampering of this TAG is prohibited</div>
    </div>
@endforeach
</div>
</body>
</html>
