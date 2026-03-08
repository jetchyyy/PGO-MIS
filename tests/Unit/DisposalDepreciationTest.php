<?php

namespace Tests\Unit;

use App\Support\DisposalDepreciation;
use Tests\TestCase;

class DisposalDepreciationTest extends TestCase
{
    public function test_it_returns_zero_without_acquisition_date(): void
    {
        $amount = DisposalDepreciation::calculate(null, '2026-03-08', 12000);

        $this->assertSame(0.0, $amount);
    }

    public function test_it_uses_straight_line_depreciation_over_five_years(): void
    {
        $amount = DisposalDepreciation::calculate('2025-03-08', '2026-03-08', 12000);

        $this->assertSame(2400.0, $amount);
    }

    public function test_it_caps_depreciation_at_total_cost(): void
    {
        $amount = DisposalDepreciation::calculate('2018-01-01', '2026-03-08', 12000);

        $this->assertSame(12000.0, $amount);
    }
}
