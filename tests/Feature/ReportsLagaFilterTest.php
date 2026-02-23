<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Purchase;

class ReportsLagaFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->artisan('migrate');
    }

    protected function createPurchase(User $user, Vendor $vendor, array $overrides = []): Purchase
    {
        $data = array_merge([
            'bill_number' => 'B1',
            'purchase_date' => '2026-02-10',
            'vendor_id' => $vendor->id,
            'customer_name' => 'Test Laga',
            'purchaser_code' => 'L001',
            'item_name' => 'Wheat',
            'quantity' => 10,
            'unit' => 'kg',
            'rate' => 100,
            'total_amount' => 1000,
            'commission_amount' => 0,
            'paid_amount' => 0,
            'user_id' => $user->id,
        ], $overrides);

        return Purchase::create($data);
    }

    public function test_filters_by_name_and_date_together()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vendor = Vendor::factory()->create();

        $this->createPurchase($user, $vendor, [
            'customer_name' => 'Ahmed Khan',
            'purchase_date' => '2026-02-15',
        ]);

        $this->createPurchase($user, $vendor, [
            'customer_name' => 'Other Name',
            'purchase_date' => '2026-02-15',
        ]);

        $this->createPurchase($user, $vendor, [
            'customer_name' => 'Ahmed Khan',
            'purchase_date' => '2026-02-01',
        ]);

        $resp = $this->get('/reports?laga_search=1&laga_query=Ahmed&laga_from_date=2026-02-10&laga_to_date=2026-02-20');

        $resp->assertStatus(200);
        $resp->assertSee('Ahmed Khan');
        $resp->assertDontSee('Other Name');
    }

    public function test_item_name_filter_respects_date_range()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vendor = Vendor::factory()->create();

        $this->createPurchase($user, $vendor, [
            'item_name' => 'Potato',
            'purchase_date' => '2026-03-05',
        ]);

        $this->createPurchase($user, $vendor, [
            'item_name' => 'Potato',
            'purchase_date' => '2026-02-01',
        ]);

        $resp = $this->get('/reports?laga_search=1&item_name=Potato&laga_from_date=2026-03-01&laga_to_date=2026-03-31');

        $resp->assertStatus(200);
        $resp->assertSee('Potato');
        $resp->assertSee('2026-03-05');
        $resp->assertDontSee('2026-02-01');
    }

    public function test_empty_name_pattern_with_valid_dates_returns_matching_records()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $vendor = Vendor::factory()->create();

        $this->createPurchase($user, $vendor, [
            'customer_name' => 'No Name Filter',
            'purchase_date' => '2026-04-10',
        ]);

        $resp = $this->get('/reports?laga_search=1&laga_from_date=2026-04-01&laga_to_date=2026-04-30');

        $resp->assertStatus(200);
        $resp->assertSee('No Name Filter');
    }
}

