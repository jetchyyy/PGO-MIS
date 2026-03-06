<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisposalLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'disposal_id', 'item_id', 'property_transaction_line_id', 'date_acquired', 'particulars', 'property_no', 'quantity',
        'unit_cost', 'total_cost', 'accumulated_depreciation', 'carrying_amount',
    ];

    protected $casts = ['date_acquired' => 'date'];

    public function disposal(): BelongsTo { return $this->belongsTo(Disposal::class); }
    public function sourceLine(): BelongsTo { return $this->belongsTo(PropertyTransactionLine::class, 'property_transaction_line_id'); }
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
}
