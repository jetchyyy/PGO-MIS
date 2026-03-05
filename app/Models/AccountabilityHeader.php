<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountabilityHeader extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'office_id', 'fund_cluster_id', 'reference_no', 'status'];

    public function lines(): HasMany { return $this->hasMany(AccountabilityLine::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
}
