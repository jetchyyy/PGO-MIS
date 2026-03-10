<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_name',
        'document_code',
        'document_title',
        'control_no',
        'generated_on',
    ];

    protected $casts = [
        'generated_on' => 'date',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
