<?php

namespace App\Support;

use Carbon\Carbon;

class DisposalDepreciation
{
    public const USEFUL_LIFE_YEARS = 5;

    public static function usefulLifeMonths(): int
    {
        return self::USEFUL_LIFE_YEARS * 12;
    }

    public static function calculate(?string $dateAcquired, string $disposalDate, float $totalCost): float
    {
        $totalCost = max(0, round($totalCost, 2));

        if ($totalCost <= 0 || blank($dateAcquired)) {
            return 0.0;
        }

        $acquiredAt = Carbon::parse($dateAcquired)->startOfDay();
        $disposedAt = Carbon::parse($disposalDate)->startOfDay();

        if ($disposedAt->lessThanOrEqualTo($acquiredAt)) {
            return 0.0;
        }

        $monthsUsed = min($acquiredAt->diffInMonths($disposedAt), self::usefulLifeMonths());
        $monthlyDepreciation = $totalCost / self::usefulLifeMonths();

        return min($totalCost, round($monthsUsed * $monthlyDepreciation, 2));
    }
}
