<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyCard extends Model
{
    use HasFactory;

    protected $fillable = ['card_no', 'property_no', 'description', 'office_id', 'fund_cluster_id', 'balance_qty', 'balance_amount'];

    public function entries(): HasMany
    {
        return $this->hasMany(PropertyCardEntry::class);
    }
}
