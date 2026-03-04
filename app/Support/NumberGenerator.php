<?php

namespace App\Support;

class NumberGenerator
{
    public static function next(string $prefix, int $year, int $lastId): string
    {
        return sprintf('%s-%d-%04d', $prefix, $year, $lastId);
    }
}
