<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BakeryPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->artisan('migrate');
    }

    public function test_bakery_page_loads_without_stock_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('bakery'));

        $response->assertStatus(200);
        $response->assertSee('اشیاء کی تفصیل');
        $response->assertDontSee('اسٹاک سے اشیاء');
        $response->assertDontSee('Stock Items');
        $response->assertDontSee('/stock/bakery');
    }
}
