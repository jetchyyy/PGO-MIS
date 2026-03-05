<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccountabilityLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountability_header_id', 'quantity', 'unit', 'description', 'property_no',
        'unit_cost', 'amount', 'status',
    ];

    public function header(): BelongsTo { return $this->belongsTo(AccountabilityHeader::class, 'accountability_header_id'); }
    public function sourceLine(): MorphTo { return $this->morphTo(); }
}
