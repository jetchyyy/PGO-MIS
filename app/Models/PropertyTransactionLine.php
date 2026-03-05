<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyTransactionLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_transaction_id', 'quantity', 'unit', 'description', 'property_no', 'date_acquired',
        'unit_cost', 'total_cost', 'classification', 'remarks', 'item_status',
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
}
