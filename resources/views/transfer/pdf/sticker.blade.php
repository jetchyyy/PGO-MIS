<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer Sticker - {{ $transfer->control_no }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7px; color: #111; }
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
        .header {
            position: absolute;
            top: 2mm;
            left: 0;
            right: 0;
            text-align: center;
            white-space: nowrap;
        }
        .seal {
            width: 8mm;
            height: 8mm;
            display: inline-block;
            vertical-align: middle;
        }
        .gov {
            font-size: 5.8px;
            line-height: 1.1;
            font-weight: 700;
            text-align: left;
            display: inline-block;
            vertical-align: middle;
            margin-left: 1.6mm;
        }
        .content {
            position: absolute;
            top: 10.5mm;
            left: 2mm;
            right: 24mm;
            bottom: 11.5mm;
        }
        .row {
            display: grid;
            grid-template-columns: 25mm 1fr;
            gap: 0.8mm;
            margin-bottom: 0.4mm;
            align-items: start;
        }
        .label { font-weight: 700; white-space: nowrap; font-size: 8px; }
        .value {
            border-bottom: 0.3px solid #555;
            white-space: normal;
            overflow: hidden;
            min-height: 3mm;
            line-height: 1.08;
            font-size: 7.5px;
        }
        .value.description {
            min-height: 6.4mm;
            max-height: 6.4mm;
        }
        .signature-block {
            position: absolute;
            left: 2mm;
            right: 24mm;
            bottom: 3.9mm;
            text-align: center;
            min-height: 5.4mm;
            padding-top: 0;
        }
        .cms-sign {
            max-width: 24mm;
            max-height: 3.6mm;
            object-fit: contain;
            display: block;
            margin: 0 auto 0.1mm;
        }
        .cms-name {
            font-weight: 700;
            color: #222;
            font-size: 6px;
            line-height: 1;
        }
        .qr {
            position: absolute;
            right: 2mm;
            bottom: 6mm;
            width: 20mm;
            text-align: center;
        }
        .qr img {
            width: 17mm;
            height: 17mm;
            border: 0.4px solid #777;
            display: block;
            margin: 0 auto 0.5mm;
        }
        .qr-code {
            font-size: 5px;
            line-height: 1.05;
            word-break: break-word;
        }
        .qr-placeholder {
            width: 17mm;
            height: 17mm;
            border: 0.4px solid #777;
            margin: 0 auto 0.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5.2px;
            color: #444;
        }
        .warning {
            position: absolute;
            left: 2mm;
            bottom: 1mm;
            color: #c1121f;
            font-size: 6.1px;
            font-style: italic;
            font-weight: 700;
        }
    </style>
</head>
<body>
@php
    $entries = collect($stickerEntries ?? []);
    $cmsSignatory = $sig->first(function ($signatory): bool {
        $haystack = strtolower(($signatory->name ?? '').' '.($signatory->designation ?? '').' '.($signatory->role_key ?? ''));
        return str_contains($haystack, 'cms');
    }) ?? ($sig['property_inspector'] ?? null) ?? $sig->first(fn ($signatory) => !empty($signatory->signature_path));
@endphp

<div class="sheet">
@foreach($entries as $entry)
    @php
        $line = $entry['line'];
        $inventory = $entry['inventory'];
        $acqDate = optional($line->date_acquired)->format('M d, Y') ?? optional($transfer->transfer_date)->format('M d, Y');
        $accountable = trim((string) ($inventory?->accountable_name ?? $inventory?->currentEmployee?->name ?? $transfer->toEmployee?->name ?? ''));
        $model = trim((string) ($inventory?->model ?? ''));
        $serial = trim((string) ($inventory?->serial_number ?? ''));
        if ($accountable === '') { $accountable = 'N/A'; }
        if ($model === '') { $model = 'N/A'; }
        if ($serial === '') { $serial = 'N/A'; }
        $officeName = $inventory?->office?->name ?? $transfer->toEmployee?->office?->name ?? 'N/A';
        $propertyNo = $inventory?->property_no ?? $line->sourceLine?->property_no ?? 'N/A';
    @endphp
    <div class="tag">
        <div class="header">
            <img class="seal" src="{{ public_path('images/surigaodelnorte.png') }}" alt="Seal">
            <div class="gov">Republic of the Philippines<br>Province of Surigao del Norte</div>
        </div>

        <div class="content">
            <div class="row"><div class="label">Name of Office:</div><div class="value">{{ $officeName }}</div></div>
            <div class="row"><div class="label">Description:</div><div class="value description">{{ $line->description }}</div></div>
            <div class="row"><div class="label">Property Number:</div><div class="value">{{ $propertyNo }}</div></div>
            <div class="row"><div class="label">Model:</div><div class="value">{{ $model }}</div></div>
            <div class="row"><div class="label">Serial Number:</div><div class="value">{{ $serial }}</div></div>
            <div class="row"><div class="label">Acq. Cost/Date:</div><div class="value">Php {{ number_format((float) $line->amount, 2) }} - {{ $acqDate }}</div></div>
            <div class="row"><div class="label">Person Accountable:</div><div class="value">{{ $accountable }}</div></div>
        </div>

        <div class="signature-block">
            @if($cmsSignatory?->signature_full_path)
                <img class="cms-sign" src="{{ $cmsSignatory->signature_full_path }}" alt="Signature">
            @endif
            <div class="cms-name">{{ $cmsSignatory?->name ?? 'N/A' }}</div>
        </div>

        <div class="qr">
            @if($inventory)
                <img src="{{ $inventory->qrDataUri(220) }}" alt="QR">
                <div class="qr-code">{{ $inventory->inventory_code }}</div>
            @else
                <div class="qr-placeholder">NO QR</div>
                <div class="qr-code">QR pending</div>
            @endif
        </div>

        <div class="warning">Tampering of this TAG is prohibited</div>
    </div>
@endforeach
</div>
</body>
</html>
