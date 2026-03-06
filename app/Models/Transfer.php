<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_name', 'from_employee_id', 'to_employee_id', 'fund_cluster_id', 'transfer_type', 'transfer_type_other',
        'transfer_date', 'document_type', 'control_no', 'status', 'created_by', 'submitted_at', 'approved_at',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function lines(): HasMany { return $this->hasMany(TransferLine::class); }
    public function fromEmployee(): BelongsTo { return $this->belongsTo(Employee::class, 'from_employee_id'); }
    public function toEmployee(): BelongsTo { return $this->belongsTo(Employee::class, 'to_employee_id'); }
    public function fundCluster(): BelongsTo { return $this->belongsTo(FundCluster::class); }
    public function approvals(): MorphMany { return $this->morphMany(Approval::class, 'approvable'); }
    public function printLogs(): MorphMany { return $this->morphMany(PrintLog::class, 'printable'); }
}
