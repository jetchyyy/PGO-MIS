<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use InvalidArgumentException;

class PropertyReturn extends Model
{
    use HasFactory;

    public const DOCUMENT_TYPE_PRS = 'PRS';
    public const DOCUMENT_TYPE_RRSP = 'RRSP';
    public const PPE_THRESHOLD = 50000;

    protected $fillable = [
        'entity_name',
        'employee_id',
        'designation',
        'station',
        'fund_cluster_id',
        'return_date',
        'return_reason',
        'control_no',
        'document_type',
        'status',
        'created_by',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'return_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function resolveDocumentType(iterable $lines): string
    {
        $classes = collect($lines)
            ->map(function ($line): string {
                $unitCost = (float) data_get($line, 'unit_cost', 0);

                return $unitCost >= self::PPE_THRESHOLD ? 'ppe' : 'semi_expendable';
            })
            ->unique()
            ->values();

        if ($classes->isEmpty()) {
            throw new InvalidArgumentException('At least one return line is required.');
        }

        if ($classes->count() > 1) {
            throw new InvalidArgumentException('Mixed PPE and semi-expendable items are not allowed in one return transaction.');
        }

        return $classes->first() === 'ppe'
            ? self::DOCUMENT_TYPE_PRS
            : self::DOCUMENT_TYPE_RRSP;
    }

    public function lines(): HasMany
    {
        return $this->hasMany(PropertyReturnLine::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function fundCluster(): BelongsTo
    {
        return $this->belongsTo(FundCluster::class);
    }

    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function printLogs(): MorphMany
    {
        return $this->morphMany(PrintLog::class, 'printable');
    }

    public function documentControls(): MorphMany
    {
        return $this->morphMany(DocumentControl::class, 'documentable');
    }

    public function disposal(): HasOne
    {
        return $this->hasOne(Disposal::class);
    }
}
