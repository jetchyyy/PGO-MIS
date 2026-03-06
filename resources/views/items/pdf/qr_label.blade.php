<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item QR Label - {{ $item->name }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .label {
            width: 90mm;
            min-height: 60mm;
            border: 0.6px solid #111;
            padding: 3mm;
            box-sizing: border-box;
        }
        .head { font-size: 9px; font-weight: 700; margin-bottom: 2mm; }
        .row { margin-bottom: 1.4mm; }
        .k { font-weight: 700; display: inline-block; min-width: 24mm; }
        .v { border-bottom: 0.3px solid #666; display: inline-block; width: 58mm; padding-bottom: 0.3mm; }
        .qr { margin-top: 2mm; text-align: right; }
        .qr img { width: 22mm; height: 22mm; border: 0.3px solid #888; }
    </style>
</head>
<body>
    <div class="label">
        <div class="head">Republic of the Philippines<br>Province of Surigao del Norte</div>
        <div class="row"><span class="k">Item Name:</span><span class="v">{{ $item->name }}</span></div>
        <div class="row"><span class="k">Category:</span><span class="v">{{ $item->category ?? 'N/A' }}</span></div>
        <div class="row"><span class="k">Unit:</span><span class="v">{{ $item->unit }}</span></div>
        <div class="row"><span class="k">Unit Cost:</span><span class="v">Php {{ number_format((float) $item->unit_cost, 2) }}</span></div>
        <div class="row"><span class="k">Classification:</span><span class="v">{{ strtoupper($item->classification) }}</span></div>
        <div class="qr">
            <img src="{{ $item->qrDataUri(220) }}" alt="QR">
        </div>
    </div>
</body>
</html>
