<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PropertyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_name', 'office_id', 'employee_id', 'fund_cluster_id', 'transaction_date', 'reference_no',
        'control_no', 'document_type', 'asset_type', 'status', 'created_by', 'submitted_at', 'approved_at',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function office(): BelongsTo { return $this->belongsTo(Office::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function fundCluster(): BelongsTo { return $this->belongsTo(FundCluster::class); }
    public function lines(): HasMany { return $this->hasMany(PropertyTransactionLine::class); }
    public function approvals(): MorphMany { return $this->morphMany(Approval::class, 'approvable'); }
    public function attachments(): MorphMany { return $this->morphMany(Attachment::class, 'attachable'); }
    public function printLogs(): MorphMany { return $this->morphMany(PrintLog::class, 'printable'); }
}
