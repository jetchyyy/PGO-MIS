<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'property_transaction_line_id',
        'office_id',
        'fund_cluster_id',
        'current_employee_id',
        'accountable_name',
        'inventory_committee_name',
        'inventory_code',
        'qr_token',
        'description',
        'model',
        'serial_number',
        'unit',
        'unit_cost',
        'classification',
        'property_no',
        'date_acquired',
        'status',
        'issued_at',
        'disposed_at',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'date_acquired' => 'date',
        'issued_at' => 'date',
        'disposed_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (InventoryItem $item): void {
            if (empty($item->qr_token)) {
                $item->qr_token = (string) Str::uuid();
            }
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function sourceLine(): BelongsTo
    {
        return $this->belongsTo(PropertyTransactionLine::class, 'property_transaction_line_id');
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function fundCluster(): BelongsTo
    {
        return $this->belongsTo(FundCluster::class);
    }

    public function currentEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'current_employee_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest('movement_date')->latest('id');
    }

    public function qrTrackingUrl(): string
    {
        return route('inventory.track', $this->qr_token);
    }

    public function qrPayload(): string
    {
        $lines = [
            'Inventory Code: '.$this->inventory_code,
            'Description: '.$this->description,
            'Property No: '.($this->property_no ?: 'N/A'),
            'Model: '.($this->model ?: 'N/A'),
            'Serial No: '.($this->serial_number ?: 'N/A'),
            'Unit Cost: '.number_format((float) $this->unit_cost, 2),
            'Date Acquired: '.($this->date_acquired?->format('M d, Y') ?: 'N/A'),
            'Office: '.($this->office?->name ?: 'N/A'),
            'Person Accountable: '.($this->accountable_name ?: $this->currentEmployee?->name ?: 'N/A'),
            'Status: '.str_replace('_', ' ', $this->status),
            'Track URL: '.$this->qrTrackingUrl(),
        ];

        return implode("\n", $lines);
    }

    public function qrImageUrl(int $size = 240): string
    {
        $value = urlencode($this->qrPayload());

        return "https://api.qrserver.com/v1/create-qr-code/?margin=0&size={$size}x{$size}&data={$value}";
    }

    public function qrDataUri(int $size = 240): string
    {
        try {
            $response = Http::timeout(12)->get($this->qrImageUrl($size));
            if ($response->successful() && $response->body() !== '') {
                return 'data:image/png;base64,'.base64_encode($response->body());
            }
        } catch (\Throwable) {
            // Fall through to SVG fallback.
        }

        $fallback = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'">'
            .'<rect width="100%" height="100%" fill="#fff" stroke="#000"/>'
            .'<text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" '
            .'font-family="Arial" font-size="18" fill="#000">QR</text>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($fallback);
    }
}
