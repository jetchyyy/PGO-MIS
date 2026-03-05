<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Disposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_name', 'employee_id', 'designation', 'station', 'fund_cluster_id', 'disposal_date',
        'disposal_type', 'disposal_type_other', 'or_no', 'sale_amount', 'appraised_value',
        'document_type', 'control_no', 'status', 'created_by', 'submitted_at', 'approved_at',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function lines(): HasMany { return $this->hasMany(DisposalLine::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function approvals(): MorphMany { return $this->morphMany(Approval::class, 'approvable'); }
    public function printLogs(): MorphMany { return $this->morphMany(PrintLog::class, 'printable'); }
}
