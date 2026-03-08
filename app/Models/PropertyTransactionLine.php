<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyTransactionLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_transaction_id', 'item_id', 'inventory_item_id', 'quantity', 'unit', 'description', 'property_no', 'date_acquired',
        'unit_cost', 'total_cost', 'classification', 'estimated_useful_life', 'remarks', 'item_status',
    ];

    protected $casts = [
        'date_acquired' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(PropertyTransaction::class, 'property_transaction_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'property_transaction_line_id');
    }

    public function activeQuantity(): int
    {
        return (int) $this->inventoryItems()
            ->whereIn('status', ['in_stock', 'issued'])
            ->count();
    }

    public function disposedQuantity(): int
    {
        return (int) $this->inventoryItems()
            ->where('status', 'disposed')
            ->count();
    }
}
