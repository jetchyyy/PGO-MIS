<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemCatalogQuantityTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_catalog_item_creates_initial_unissued_stock(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PROPERTY_STAFF,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('items.store'), [
            'name' => 'Office Chair',
            'description' => 'Mesh back office chair',
            'unit' => 'pcs',
            'quantity' => 3,
            'unit_cost' => 4500,
            'category' => 'Furniture & Fixtures',
            'estimated_useful_life' => '5 years',
        ]);

        $item = Item::query()->firstOrFail();

        $response->assertRedirect(route('items.index', absolute: false));
        $this->assertSame('splv', $item->classification);
        $this->assertDatabaseCount((new Item())->getTable(), 1);
        $this->assertDatabaseCount((new InventoryItem())->getTable(), 3);
        $this->assertDatabaseHas((new InventoryItem())->getTable(), [
            'item_id' => $item->id,
            'status' => 'in_stock',
            'description' => 'Office Chair',
        ]);
    }
}
