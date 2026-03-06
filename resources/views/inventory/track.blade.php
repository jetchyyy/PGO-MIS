<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Property Tag Lookup</title>
    <style>
        body { margin: 0; padding: 18px; background: #f1f5f9; font-family: Arial, sans-serif; }
        .card { max-width: 760px; margin: 0 auto; background: #fff; border: 1px solid #d5dce7; border-radius: 8px; overflow: hidden; }
        .head { padding: 14px 16px; border-bottom: 1px solid #e6ebf2; background: #0f274f; color: #fff; }
        .head h1 { margin: 0; font-size: 20px; }
        .head p { margin: 4px 0 0; font-size: 13px; color: #c6d4ee; }
        .body { padding: 16px; display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .box { border: 1px solid #e8edf5; border-radius: 6px; padding: 10px; }
        .k { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #64748b; }
        .v { margin-top: 4px; font-size: 15px; font-weight: 700; color: #0f172a; }
        .full { grid-column: 1 / -1; }
    </style>
</head>
<body>
    <div class="card">
        <div class="head">
            <h1>QR Property Tag Information</h1>
            <p>Inventory Code: {{ $inventory->inventory_code }}</p>
        </div>
        <div class="body">
            <div class="box"><div class="k">Name of Office</div><div class="v">{{ $inventory->office?->name ?? 'N/A' }}</div></div>
            <div class="box"><div class="k">Status</div><div class="v">{{ str_replace('_', ' ', $inventory->status) }}</div></div>
            <div class="box full"><div class="k">Description of Property</div><div class="v">{{ $inventory->description }}</div></div>
            <div class="box"><div class="k">Property Number</div><div class="v">{{ $inventory->property_no ?? 'N/A' }}</div></div>
            <div class="box"><div class="k">Model</div><div class="v">{{ $inventory->model ?? 'N/A' }}</div></div>
            <div class="box"><div class="k">Serial Number</div><div class="v">{{ $inventory->serial_number ?? 'N/A' }}</div></div>
            <div class="box"><div class="k">Acquisition Cost</div><div class="v">Php {{ number_format((float) $inventory->unit_cost, 2) }}</div></div>
            <div class="box"><div class="k">Date Acquired</div><div class="v">{{ $inventory->date_acquired?->format('M d, Y') ?? 'N/A' }}</div></div>
            <div class="box"><div class="k">Person Accountable</div><div class="v">{{ $inventory->accountable_name ?? $inventory->currentEmployee?->name ?? 'N/A' }}</div></div>
            <div class="box"><div class="k">Inventory Committee</div><div class="v">{{ $inventory->inventory_committee_name ?? 'N/A' }}</div></div>
        </div>
    </div>
</body>
</html>
