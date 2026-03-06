<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_token',
        'name',
        'description',
        'unit',
        'unit_cost',
        'classification',
        'category',
        'estimated_useful_life',
        'is_active',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Item $item): void {
            if (empty($item->qr_token)) {
                $item->qr_token = (string) Str::uuid();
            }
        });
    }

    /**
     * Classification constants matching COA Circular 2022-004 thresholds.
     */
    public const CLASSIFICATIONS = [
        'ppe' => 'PPE (≥₱50,000)',
        'sphv' => 'Semi-Expendable HV (₱5,000–₱49,999)',
        'splv' => 'Semi-Expendable LV (₱1–₱4,999)',
    ];

    public const CATEGORIES = [
        'Office Equipment',
        'IT Equipment',
        'Furniture & Fixtures',
        'Communication Equipment',
        'Machinery',
        'Motor Vehicle',
        'Medical Equipment',
        'Sports Equipment',
        'Technical & Scientific Equipment',
        'Other Equipment',
    ];

    /**
     * Auto-classify based on unit_cost when saving.
     */
    public static function classifyByCost(float $cost): string
    {
        if ($cost >= 50000) {
            return 'ppe';
        }

        return $cost >= 5000 ? 'sphv' : 'splv';
    }

    /**
     * Scope: only active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: search by name or description.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('category', 'like', "%{$term}%");
        });
    }

    /* ──────────── Relationships ──────────── */

    public function issuanceLines(): HasMany
    {
        return $this->hasMany(PropertyTransactionLine::class);
    }

    public function transferLines(): HasMany
    {
        return $this->hasMany(TransferLine::class);
    }

    public function disposalLines(): HasMany
    {
        return $this->hasMany(DisposalLine::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get the current accountable status based on latest issuance lines.
     */
    public function currentStatus(): string
    {
        $latest = $this->issuanceLines()->latest()->first();

        return $latest ? $latest->item_status : 'unissued';
    }

    /**
     * Total quantity currently issued (active issuance lines).
     */
    public function totalIssuedQty(): int
    {
        return (int) $this->issuanceLines()
            ->whereHas('transaction', fn ($q) => $q->whereIn('status', ['approved', 'issued']))
            ->where('item_status', 'active')
            ->sum('quantity');
    }

    public function qrPayload(): string
    {
        $lines = [
            'Item ID: '.$this->id,
            'Name: '.$this->name,
            'Description: '.($this->description ?: 'N/A'),
            'Category: '.($this->category ?: 'N/A'),
            'Unit: '.$this->unit,
            'Unit Cost: '.number_format((float) $this->unit_cost, 2),
            'Classification: '.strtoupper($this->classification),
            'Useful Life: '.($this->estimated_useful_life ?: 'N/A'),
            'Status: '.($this->is_active ? 'Active' : 'Inactive'),
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
            // Fall through to fallback.
        }

        $fallback = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'">'
            .'<rect width="100%" height="100%" fill="#fff" stroke="#000"/>'
            .'<text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" '
            .'font-family="Arial" font-size="18" fill="#000">QR</text>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($fallback);
    }
}
