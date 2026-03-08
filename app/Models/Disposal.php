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

    public const DOCUMENT_TYPE_IIRUP = 'IIRUP';
    public const DOCUMENT_TYPE_IIRUSP = 'IIRUSP';
    public const DOCUMENT_TYPE_RRSEP = 'RRSEP';
    public const PPE_THRESHOLD = 50000;

    protected $fillable = [
        'entity_name', 'employee_id', 'designation', 'station', 'fund_cluster_id', 'disposal_date',
        'disposal_type', 'disposal_type_other', 'item_disposal_condition', 'item_disposal_condition_other',
        'or_no', 'sale_amount', 'appraised_value', 'disposal_method', 'disposal_method_other',
        'document_type', 'control_no', 'status', 'created_by', 'submitted_at', 'approved_at',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function resolveDocumentType(iterable $lines): string
    {
        foreach ($lines as $line) {
            $unitCost = (float) data_get($line, 'unit_cost', 0);

            if ($unitCost >= self::PPE_THRESHOLD) {
                return self::DOCUMENT_TYPE_IIRUP;
            }
        }

        return self::DOCUMENT_TYPE_IIRUSP;
    }

    public function printTemplate(): string
    {
        return strtolower($this->document_type ?: self::DOCUMENT_TYPE_IIRUP);
    }

    public function lines(): HasMany { return $this->hasMany(DisposalLine::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function fundCluster(): BelongsTo { return $this->belongsTo(FundCluster::class); }
    public function approvals(): MorphMany { return $this->morphMany(Approval::class, 'approvable'); }
    public function printLogs(): MorphMany { return $this->morphMany(PrintLog::class, 'printable'); }
}
