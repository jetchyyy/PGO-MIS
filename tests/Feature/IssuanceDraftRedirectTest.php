<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\InventoryItem;
use App\Models\Item;
use App\Models\Office;
use App\Models\PropertyTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssuanceDraftRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_draft_redirects_to_the_created_draft_page(): void
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

        $response = $this->actingAs($user)->post(route('issuance.store'), [
            'entity_name' => 'Provincial Government of Surigao del Norte',
            'office_id' => $office->id,
            'employee_id' => $employee->id,
            'fund_cluster_id' => $fundCluster->id,
            'transaction_date' => '2026-03-09',
            'reference_no' => 'REF-001',
            'lines' => [
                [
                    'quantity' => 1,
                    'unit' => 'pcs',
                    'description' => 'Office Chair',
                    'property_no' => null,
                    'date_acquired' => null,
                    'unit_cost' => 4500,
                    'estimated_useful_life' => '5 years',
                    'remarks' => 'Draft redirect test',
                ],
            ],
        ]);

        $issuance = PropertyTransaction::query()->firstOrFail();

        $response->assertRedirect(route('issuance.show', $issuance, false));
        $this->assertDatabaseCount((new PropertyTransaction())->getTable(), 1);
        $this->assertDatabaseHas((new PropertyTransaction())->getTable(), [
            'id' => $issuance->id,
            'status' => 'draft',
            'document_type' => 'ICS-SPLV',
        ]);
    }

    public function test_save_draft_rejects_quantity_above_remaining_unissued_stock(): void
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

        $item = Item::create([
            'name' => 'Office Chair',
            'unit' => 'pcs',
            'unit_cost' => 4500,
            'classification' => 'splv',
            'is_active' => true,
        ]);

        InventoryItem::create([
            'item_id' => $item->id,
            'inventory_code' => 'INV-001',
            'description' => 'Office Chair',
            'unit' => 'pcs',
            'unit_cost' => 4500,
            'classification' => 'splv',
            'status' => 'in_stock',
        ]);

        InventoryItem::create([
            'item_id' => $item->id,
            'inventory_code' => 'INV-002',
            'description' => 'Office Chair',
            'unit' => 'pcs',
            'unit_cost' => 4500,
            'classification' => 'splv',
            'status' => 'in_stock',
        ]);

        $user = User::factory()->create([
            'role' => User::ROLE_PROPERTY_STAFF,
            'is_active' => true,
        ]);

        $response = $this->from(route('issuance.create'))
            ->actingAs($user)
            ->post(route('issuance.store'), [
                'entity_name' => 'Provincial Government of Surigao del Norte',
                'office_id' => $office->id,
                'employee_id' => $employee->id,
                'fund_cluster_id' => $fundCluster->id,
                'transaction_date' => '2026-03-09',
                'lines' => [
                    [
                        'item_id' => $item->id,
                        'quantity' => 3,
                        'unit' => 'pcs',
                        'description' => 'Office Chair',
                        'unit_cost' => 4500,
                    ],
                ],
            ]);

        $response->assertRedirect(route('issuance.create', absolute: false));
        $response->assertSessionHasErrors(['lines.0.quantity']);
        $this->assertDatabaseCount((new PropertyTransaction())->getTable(), 0);
    }
}
