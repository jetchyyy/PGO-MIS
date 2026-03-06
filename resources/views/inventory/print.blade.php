<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Property Tag Print</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { margin: 0; font-family: Arial, sans-serif; }
        .toolbar { margin: 12px; }
        .sheet { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8mm; padding: 4mm; }
        .tag {
            border: 1px solid #222;
            width: 88mm;
            height: 58mm;
            box-sizing: border-box;
            padding: 3mm;
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-rows: auto 1fr;
        }
        .hdr { display: grid; grid-template-columns: 10mm 1fr; gap: 2mm; align-items: center; }
        .hdr img { width: 9mm; height: 9mm; object-fit: contain; }
        .hdr .gov { font-size: 3.7mm; font-weight: 700; line-height: 1.12; }
        .body { margin-top: 2mm; display: grid; grid-template-columns: 1fr 21mm; gap: 2mm; }
        .row { margin: 0 0 1mm; font-size: 2.9mm; line-height: 1.2; display: grid; grid-template-columns: 33mm 1fr; gap: 1mm; }
        .k { font-weight: 700; text-transform: uppercase; }
        .v { border-bottom: 0.2mm solid #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .desc { font-size: 2.8mm; line-height: 1.15; max-height: 8mm; overflow: hidden; }
        .qr img { width: 20mm; height: 20mm; border: 0.2mm solid #999; display: block; margin: 0 auto; }
        .qr .code { font-size: 2.3mm; text-align: center; margin-top: 0.8mm; }
        .warn {
            position: absolute; left: 2mm; bottom: 2.2mm;
            color: #b91c1c; font-style: italic; font-weight: 700; font-size: 2.8mm;
        }
        @media print { .toolbar { display: none; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">Print Property Tags</button>
    </div>

    <div class="sheet">
        @foreach($inventoryItems as $inventory)
        <div class="tag">
            <div class="hdr">
                <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Seal">
                <div class="gov">
                    Republic of the Philippines<br>
                    Province of Surigao del Norte
                </div>
            </div>
            <div class="body">
                <div>
                    <div class="row"><div class="k">Name of Office:</div><div class="v">{{ $inventory->office?->name ?? 'PDRRMO' }}</div></div>
                    <div class="row"><div class="k">Description:</div><div class="v desc">{{ $inventory->description }}</div></div>
                    <div class="row"><div class="k">Property No.:</div><div class="v">{{ $inventory->property_no ?? 'N/A' }}</div></div>
                    <div class="row"><div class="k">Model:</div><div class="v">{{ $inventory->model ?? 'N/A' }}</div></div>
                    <div class="row"><div class="k">Serial Number:</div><div class="v">{{ $inventory->serial_number ?? 'N/A' }}</div></div>
                    <div class="row"><div class="k">Acq. Cost/Date:</div><div class="v">Php {{ number_format((float) $inventory->unit_cost, 2) }} - {{ $inventory->date_acquired?->format('M d, Y') ?? 'N/A' }}</div></div>
                    <div class="row"><div class="k">Person Accountable:</div><div class="v">{{ $inventory->accountable_name ?? $inventory->currentEmployee?->name ?? 'N/A' }}</div></div>
                    <div class="row"><div class="k">Signature over printed name:</div><div class="v">{{ $inventory->inventory_committee_name ?? 'N/A' }}</div></div>
                </div>
                <div class="qr">
                    <img src="{{ $inventory->qrImageUrl(220) }}" alt="QR code">
                    <div class="code">{{ $inventory->inventory_code }}</div>
                </div>
            </div>
            <div class="warn">Tampering of this TAG is prohibited</div>
        </div>
        @endforeach
    </div>
</body>
</html>
