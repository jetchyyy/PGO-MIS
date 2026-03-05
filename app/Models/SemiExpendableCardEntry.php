<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SemiExpendableCardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'semi_expendable_card_id', 'entry_date', 'reference_no', 'qty_in', 'qty_out', 'running_balance_qty',
        'amount_in', 'amount_out', 'running_balance_amount', 'remarks',
    ];

    public function semiExpendableCard(): BelongsTo { return $this->belongsTo(SemiExpendableCard::class); }
    public function source(): MorphTo { return $this->morphTo(); }
}
