<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PropertyTransaction;
use App\Models\User;
use App\Support\DocumentControlRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalDocumentControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_controls_are_generated_separately_for_issuance_documents(): void
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

        $approver = User::factory()->create([
            'role' => User::ROLE_APPROVING_OFFICIAL,
        ]);

        $issuance = PropertyTransaction::create([
            'entity_name' => 'PGSO',
            'office_id' => $office->id,
            'employee_id' => $employee->id,
            'fund_cluster_id' => $fundCluster->id,
            'transaction_date' => '2026-03-10',
            'control_no' => 'ICS-SPLV-2026-03-0001',
            'document_type' => 'ICS-SPLV',
            'asset_type' => 'semi_expendable',
            'status' => 'submitted',
            'created_by' => $approver->id,
            'submitted_at' => now(),
        ]);

        $issuance->lines()->create([
            'quantity' => 1,
            'unit' => 'pcs',
            'description' => 'Handheld Radio',
            'property_no' => null,
            'date_acquired' => '2026-03-01',
            'unit_cost' => 3000,
            'total_cost' => 3000,
            'classification' => 'splv',
            'remarks' => null,
            'item_status' => 'active',
        ]);

        $issuance->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        DocumentControlRegistry::ensureFor($issuance);

        $issuance->refresh()->load('documentControls');

        $this->assertCount(5, $issuance->documentControls);
        $this->assertSame(
            ['ICS', 'REGSPI', 'SPC', 'SPLV', 'TAG'],
            $issuance->documentControls->pluck('document_code')->sort()->values()->all()
        );
        $this->assertSame(
            ['ICS-2026-03-0001', 'REGSPI-2026-03-0001', 'SPC-2026-03-0001', 'SPLV-2026-03-0001', 'TAG-2026-03-0001'],
            $issuance->documentControls->pluck('control_no')->sort()->values()->all()
        );
    }
}
