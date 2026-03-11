<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use InvalidArgumentException;

class Disposal extends Model
{
    use HasFactory;

    public const DOCUMENT_TYPE_IIRUP = 'IIRUP';
    public const DOCUMENT_TYPE_IIRUSP = 'IIRUSP';
    public const DOCUMENT_TYPE_RRSEP = 'RRSEP';
    public const PPE_THRESHOLD = 50000;

    protected $fillable = [
        'entity_name', 'employee_id', 'designation', 'station', 'fund_cluster_id', 'property_return_id', 'disposal_date',
        'disposal_type', 'disposal_type_other', 'item_disposal_condition', 'item_disposal_condition_other',
        'or_no', 'sale_amount', 'appraised_value', 'disposal_method', 'disposal_method_other',
        'document_type', 'prerequisite_form_type', 'prerequisite_form_no', 'prerequisite_form_date',
        'control_no', 'status', 'created_by', 'submitted_at', 'approved_at',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'prerequisite_form_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function documentTypes(): array
    {
        return [
            self::DOCUMENT_TYPE_IIRUP,
            self::DOCUMENT_TYPE_IIRUSP,
            self::DOCUMENT_TYPE_RRSEP,
        ];
    }

    public static function resolveDocumentType(iterable $lines): string
    {
        return self::resolveAssetClass($lines) === 'ppe'
            ? self::DOCUMENT_TYPE_IIRUP
            : self::DOCUMENT_TYPE_RRSEP;
    }

    public static function requiredFormTypeForLines(iterable $lines): string
    {
        return self::resolveAssetClass($lines) === 'ppe'
            ? PropertyReturn::DOCUMENT_TYPE_PRS
            : PropertyReturn::DOCUMENT_TYPE_RRSP;
    }

    public static function resolveAssetClass(iterable $lines): string
    {
        $classes = collect($lines)
            ->map(function ($line): string {
                $unitCost = (float) data_get($line, 'unit_cost', 0);

                return $unitCost >= self::PPE_THRESHOLD ? 'ppe' : 'semi_expendable';
            })
            ->unique()
            ->values();

        if ($classes->isEmpty()) {
            throw new InvalidArgumentException('At least one disposal line is required.');
        }

        if ($classes->count() > 1) {
            throw new InvalidArgumentException('Mixed PPE and semi-expendable items are not allowed in one disposal transaction.');
        }

        return (string) $classes->first();
    }

    public function printTemplate(): string
    {
        return strtolower($this->document_type ?: self::DOCUMENT_TYPE_IIRUP);
    }

    public function lines(): HasMany { return $this->hasMany(DisposalLine::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function fundCluster(): BelongsTo { return $this->belongsTo(FundCluster::class); }
    public function propertyReturn(): BelongsTo { return $this->belongsTo(PropertyReturn::class); }
    public function approvals(): MorphMany { return $this->morphMany(Approval::class, 'approvable'); }
    public function printLogs(): MorphMany { return $this->morphMany(PrintLog::class, 'printable'); }
    public function documentControls(): MorphMany { return $this->morphMany(DocumentControl::class, 'documentable'); }
}
