<?php

namespace App\Support;

use App\Models\Disposal;
use App\Models\DocumentControl;
use App\Models\PropertyReturn;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class NumberGenerator
{
    private const DOCUMENT_CONTROL_PREFIXES = [
        'PAR',
        'ICS',
        'PTR',
        'ITR',
        'PRS',
        'RRSP',
        'IIRUP',
        'IIRUSP',
        'RRSEP',
        'WMR',
        'SPLV',
        'SPHV',
        'PC',
        'SPC',
        'REGSPI',
        'TAG',
    ];

    private const DOCUMENT_SOURCES = [
        'PAR' => [PropertyTransaction::class, 'transaction_date'],
        'ICS-SPLV' => [PropertyTransaction::class, 'transaction_date'],
        'ICS-SPHV' => [PropertyTransaction::class, 'transaction_date'],
        'PTR' => [Transfer::class, 'transfer_date'],
        'ITR' => [Transfer::class, 'transfer_date'],
        'PRS' => [PropertyReturn::class, 'return_date'],
        'RRSP' => [PropertyReturn::class, 'return_date'],
        'IIRUP' => [Disposal::class, 'disposal_date'],
        'IIRUSP' => [Disposal::class, 'disposal_date'],
        'RRSEP' => [Disposal::class, 'disposal_date'],
    ];

    public static function next(string $prefix, CarbonInterface|string $date): string
    {
        $transactionDate = $date instanceof CarbonInterface ? $date : Carbon::parse($date);
        [$modelClass, $dateColumn] = self::sourceFor($prefix);

        $year = $transactionDate->format('Y');
        $month = $transactionDate->format('m');
        $pattern = sprintf('%s-%s-%s-', $prefix, $year, $month);

        $lastSeries = $modelClass::query()
            ->where('document_type', $prefix)
            ->whereYear($dateColumn, (int) $year)
            ->whereMonth($dateColumn, (int) $month)
            ->where('control_no', 'like', $pattern.'%')
            ->pluck('control_no')
            ->map(fn (string $controlNo): int => self::extractSeries($controlNo, $prefix, $year, $month))
            ->max() ?? 0;

        return sprintf('%s-%s-%s-%04d', $prefix, $year, $month, $lastSeries + 1);
    }

    public static function nextDocument(string $prefix, CarbonInterface|string $date): string
    {
        if (! in_array($prefix, self::DOCUMENT_CONTROL_PREFIXES, true)) {
            throw new InvalidArgumentException("Unsupported document control type [{$prefix}] for control number generation.");
        }

        $generatedDate = $date instanceof CarbonInterface ? $date : Carbon::parse($date);
        $year = $generatedDate->format('Y');
        $month = $generatedDate->format('m');
        $pattern = sprintf('%s-%s-%s-', $prefix, $year, $month);

        $lastSeries = DocumentControl::query()
            ->where('document_code', $prefix)
            ->whereYear('generated_on', (int) $year)
            ->whereMonth('generated_on', (int) $month)
            ->where('control_no', 'like', $pattern.'%')
            ->pluck('control_no')
            ->map(fn (string $controlNo): int => self::extractSeries($controlNo, $prefix, $year, $month))
            ->max() ?? 0;

        return sprintf('%s-%s-%s-%04d', $prefix, $year, $month, $lastSeries + 1);
    }

    private static function sourceFor(string $prefix): array
    {
        if (! isset(self::DOCUMENT_SOURCES[$prefix])) {
            throw new InvalidArgumentException("Unsupported document type [{$prefix}] for control number generation.");
        }

        return self::DOCUMENT_SOURCES[$prefix];
    }

    private static function extractSeries(string $controlNo, string $prefix, string $year, string $month): int
    {
        $pattern = sprintf('/^%s-%s-%s-(\d{4})$/', preg_quote($prefix, '/'), $year, $month);

        if (! preg_match($pattern, $controlNo, $matches)) {
            return 0;
        }

        return (int) $matches[1];
    }
}
