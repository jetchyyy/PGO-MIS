<?php

namespace Tests\Feature;

use App\Models\Disposal;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use App\Models\User;
use App\Support\NumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumberGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_monthly_series_for_property_transactions(): void
    {
        [$user, $office, $employee, $fundCluster] = $this->baseDependencies();

        PropertyTransaction::create([
            'entity_name' => 'PGSO',
            'office_id' => $office->id,
            'employee_id' => $employee->id,
            'fund_cluster_id' => $fundCluster->id,
            'transaction_date' => '2026-03-05',
            'control_no' => 'PAR-2026-03-0001',
            'document_type' => 'PAR',
            'asset_type' => 'ppe',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        PropertyTransaction::create([
            'entity_name' => 'PGSO',
            'office_id' => $office->id,
            'employee_id' => $employee->id,
            'fund_cluster_id' => $fundCluster->id,
            'transaction_date' => '2026-03-10',
            'control_no' => 'PAR-2026-03-0002',
            'document_type' => 'PAR',
            'asset_type' => 'ppe',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $this->assertSame('PAR-2026-03-0003', NumberGenerator::next('PAR', '2026-03-22'));
        $this->assertSame('PAR-2026-04-0001', NumberGenerator::next('PAR', '2026-04-01'));
    }

    public function test_it_generates_monthly_series_for_other_transaction_tables(): void
    {
        [$user, , $employee, $fundCluster] = $this->baseDependencies();
        $toEmployee = Employee::create([
            'office_id' => $employee->office_id,
            'name' => 'Destination Employee',
        ]);

        Transfer::create([
            'entity_name' => 'PGSO',
            'from_employee_id' => $employee->id,
            'to_employee_id' => $toEmployee->id,
            'fund_cluster_id' => $fundCluster->id,
            'transfer_type' => 'relocate',
            'transfer_date' => '2026-03-01',
            'document_type' => 'PTR',
            'control_no' => 'PTR-2026-03-0001',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        Disposal::create([
            'entity_name' => 'PGSO',
            'employee_id' => $employee->id,
            'fund_cluster_id' => $fundCluster->id,
            'disposal_date' => '2026-03-01',
            'disposal_type' => 'sale',
            'document_type' => 'RRSEP',
            'control_no' => 'RRSEP-2026-03-0001',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $this->assertSame('PTR-2026-03-0002', NumberGenerator::next('PTR', '2026-03-15'));
        $this->assertSame('RRSEP-2026-03-0002', NumberGenerator::next('RRSEP', '2026-03-15'));
    }

    private function baseDependencies(): array
    {
        $office = Office::create([
            'name' => 'Property Office',
            'code' => 'PROP',
        ]);

        $fundCluster = FundCluster::create([
            'name' => 'General Fund',
            'code' => 'GF',
        ]);

        $employee = Employee::create([
            'office_id' => $office->id,
            'name' => 'Accountable Employee',
        ]);

        $user = User::factory()->create();

        return [$user, $office, $employee, $fundCluster];
    }
}
