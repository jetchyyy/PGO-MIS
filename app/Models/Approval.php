<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\PropertyReturn;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'acted_by', 'remarks', 'acted_at'];

    protected $casts = ['acted_at' => 'datetime'];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function approvableLabel(): string
    {
        return match (true) {
            $this->approvable instanceof PropertyTransaction => 'Issuance',
            $this->approvable instanceof Transfer => 'Transfer',
            $this->approvable instanceof PropertyReturn => 'Return',
            $this->approvable instanceof Disposal => 'Disposal',
            default => class_basename((string) $this->approvable_type),
        };
    }

    public function approvableViewUrl(): ?string
    {
        $approvable = $this->approvable;

        if (! $approvable) {
            return null;
        }

        return match (true) {
            $approvable instanceof PropertyTransaction => route('issuance.show', $approvable),
            $approvable instanceof Transfer => route('transfer.show', $approvable),
            $approvable instanceof PropertyReturn => route('returns.show', $approvable),
            $approvable instanceof Disposal => route('disposal.show', $approvable),
            default => null,
        };
    }
}
