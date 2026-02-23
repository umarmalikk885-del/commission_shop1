<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\StockTestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTestDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_page_loads_with_test_data(): void
    {
        $this->seed(StockTestDataSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo('view stock');

        $response = $this->actingAs($user)->get('/stock');

        $response->assertStatus(200);
        $response->assertSee('[TEST]', false);
    }

    public function test_inventory_api_crud_with_test_item(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['view stock', 'manage stock']);

        $createResponse = $this->actingAs($user)->post('/stock', [
            'item_name' => 'ٹیسٹ آئٹم',
            'quantity' => 10,
            'unit' => 'کلو',
            'min_stock_level' => 100,
            'rate' => 50,
            'description' => '[TEST] Manual created item',
        ]);

        $createResponse->assertRedirect('/stock');

        $this->assertDatabaseHas('stocks', [
            'item_name' => 'ٹیسٹ آئٹم',
            'description' => '[TEST] Manual created item',
        ]);
    }
}

