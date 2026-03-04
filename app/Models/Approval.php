<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'acted_by', 'remarks', 'acted_at'];

    protected $casts = ['acted_at' => 'datetime'];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }
}
