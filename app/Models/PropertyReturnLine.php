<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyReturnLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_return_id',
        'item_id',
        'inventory_item_id',
        'property_transaction_line_id',
        'date_acquired',
        'particulars',
        'property_no',
        'quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'condition',
        'remarks',
    ];

    protected $casts = [
        'date_acquired' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function propertyReturn(): BelongsTo
    {
        return $this->belongsTo(PropertyReturn::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function sourceLine(): BelongsTo
    {
        return $this->belongsTo(PropertyTransactionLine::class, 'property_transaction_line_id');
    }
}
