<?php

namespace Tests\Feature;

use App\Models\Disposal;
use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\PropertyReturn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisposalDraftRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_redirects_to_existing_disposal_draft_for_selected_return(): void
    {
        [$user, $return, $disposal] = $this->makeExistingDraftScenario();

        $response = $this->actingAs($user)->get(route('disposal.create', [
            'return_id' => $return->id,
        ]));

        $response->assertRedirect(route('disposal.show', $disposal, false));
        $response->assertSessionHas('status', 'A disposal draft already exists for this return record.');
    }

    public function test_store_redirects_to_existing_disposal_draft_for_selected_return(): void
    {
        [$user, $return, $disposal] = $this->makeExistingDraftScenario();

        $response = $this->actingAs($user)->post(route('disposal.store'), [
            'property_return_id' => $return->id,
            'disposal_date' => '2026-03-11',
            'item_disposal_condition' => 'unserviceable',
            'disposal_method' => 'public_auction',
            'appraised_value' => 65000,
        ]);

        $response->assertRedirect(route('disposal.show', $disposal, false));
        $response->assertSessionHas('status', 'A disposal draft already exists for this return record.');
        $this->assertDatabaseCount((new Disposal())->getTable(), 1);
    }

    /**
     * @return array{0: User, 1: PropertyReturn, 2: Disposal}
     */
    private function makeExistingDraftScenario(): array
    {
        $office = Office::create([
            'name' => 'Property Office',
            'code' => 'PROP',
        ]);

        $employee = Employee::create([
            'office_id' => $office->id,
            'name' => 'Accountable Employee',
        ]);

        $fundCluster = FundCluster::create([
            'name' => 'General Fund',
            'code' => 'GF',
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_PROPERTY_STAFF,
            'is_active' => true,
        ]);

        $return = PropertyReturn::create([
            'entity_name' => 'Provincial Government of Surigao del Norte',
            'employee_id' => $employee->id,
            'designation' => 'Clerk',
            'station' => 'Main Office',
            'fund_cluster_id' => $fundCluster->id,
            'return_date' => '2026-03-11',
            'return_reason' => 'Unserviceable',
            'control_no' => 'PRS-2026-03-0001',
            'document_type' => 'PRS',
            'status' => 'approved',
            'created_by' => $user->id,
            'approved_at' => now(),
        ]);

        $return->lines()->create([
            'particulars' => 'Office Chair',
            'quantity' => 1,
            'unit' => 'pcs',
            'unit_cost' => 65000,
            'total_cost' => 65000,
            'condition' => 'Unserviceable',
        ]);

        $disposal = Disposal::create([
            'entity_name' => $return->entity_name,
            'employee_id' => $return->employee_id,
            'designation' => $return->designation,
            'station' => $return->station,
            'fund_cluster_id' => $return->fund_cluster_id,
            'property_return_id' => $return->id,
            'disposal_date' => '2026-03-11',
            'disposal_type' => 'sale',
            'item_disposal_condition' => 'unserviceable',
            'or_no' => null,
            'sale_amount' => null,
            'appraised_value' => 65000,
            'disposal_method' => 'public_auction',
            'document_type' => 'IIRUP',
            'prerequisite_form_type' => $return->document_type,
            'prerequisite_form_no' => $return->control_no,
            'prerequisite_form_date' => $return->return_date,
            'control_no' => 'IIRUP-2026-03-0001',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        return [$user, $return, $disposal];
    }
}
