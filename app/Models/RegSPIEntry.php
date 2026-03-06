<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegSPIEntry extends Model
{
    use HasFactory;

    protected $table = 'regspi_entries';

    protected $fillable = [
        'semi_expendable_card_id',
        'property_transaction_id',
        'property_transaction_line_id',
        'ics_no',
        'description',
        'employee_id',
        'office_id',
        'fund_cluster_id',
        'quantity_issued',
        'unit_cost',
        'total_cost',
        'property_no',
        'issue_date',
        'classification',
        'remarks',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function semiExpendableCard(): BelongsTo
    {
        return $this->belongsTo(SemiExpendableCard::class);
    }

    public function propertyTransaction(): BelongsTo
    {
        return $this->belongsTo(PropertyTransaction::class);
    }

    public function propertyTransactionLine(): BelongsTo
    {
        return $this->belongsTo(PropertyTransactionLine::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function fundCluster(): BelongsTo
    {
        return $this->belongsTo(FundCluster::class);
    }
}
