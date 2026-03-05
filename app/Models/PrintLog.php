<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PrintLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['template_name', 'version', 'printed_by', 'printed_at'];

    protected $casts = ['printed_at' => 'datetime'];

    public function printable(): MorphTo
    {
        return $this->morphTo();
    }
}
