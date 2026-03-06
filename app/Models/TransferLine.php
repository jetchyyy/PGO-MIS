<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id', 'item_id', 'inventory_item_id', 'property_transaction_line_id', 'date_acquired', 'reference_no', 'quantity',
        'unit', 'description', 'amount', 'condition',
    ];

    protected $casts = ['date_acquired' => 'date'];

    public function transfer(): BelongsTo { return $this->belongsTo(Transfer::class); }
    public function sourceLine(): BelongsTo { return $this->belongsTo(PropertyTransactionLine::class, 'property_transaction_line_id'); }
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
    public function inventoryItem(): BelongsTo { return $this->belongsTo(InventoryItem::class); }
}
