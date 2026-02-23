<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\BakriBook;
use App\Models\BakriBookTransaction;
use App\Models\BakriBookItem;

class MonetaryObserverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->artisan('migrate');
    }

    public function test_single_insert_autofills_and_sanitizes_monetary_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $book = BakriBook::create([
            'user_id' => $user->id,
            'record_date' => '2026-02-14',
            'trader' => 'Owner',
            'raw_goat' => 1000,
            'fare' => null,
            'commission' => -10,
            'labor' => 20.123,
            'mashiana' => 0,
            'stamp' => 0,
            'other_expenses' => 0,
        ]);

        $this->assertSame(0.00, (float)$book->fare);
        $this->assertSame(0.00, (float)$book->commission);
        $this->assertSame(20.12, (float)$book->labor);
        $this->assertSame(0.00, (float)$book->mashiana);

        $this->assertSame(20.12, (float)$book->total_expenses);
        $this->assertSame(979.88, (float)$book->net_goat);
    }

    public function test_bulk_insert_on_related_models_is_sanitized()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $book = BakriBook::create([
            'user_id' => $user->id,
            'record_date' => '2026-02-14',
            'trader' => 'Owner',
            'raw_goat' => 0,
        ]);

        $book->items()->createMany([
            ['code' => 'A', 'item_type' => 'X', 'labor' => -5, 'labor_transport' => null],
            ['code' => 'B', 'item_type' => 'Y', 'labor' => 12.999, 'labor_transport' => 3.456],
        ]);

        $items = $book->items()->get();
        $this->assertSame(0.00, (float)$items[0]->labor);
        $this->assertSame(0.00, (float)$items[0]->labor_transport);
        $this->assertSame(13.00, (float)$items[1]->labor);
        $this->assertSame(3.46, (float)$items[1]->labor_transport);

        $book->transactions()->createMany([
            ['book' => 'Buyer', 'trader_amount' => -1, 'book_amount' => null],
            ['book' => 'Buyer2', 'trader_amount' => 10.004, 'book_amount' => 5.505],
        ]);

        $tx = $book->transactions()->get();
        $this->assertSame(0.00, (float)$tx[0]->trader_amount);
        $this->assertSame(0.00, (float)$tx[0]->book_amount);
        $this->assertSame(10.00, (float)$tx[1]->trader_amount);
        $this->assertSame(5.51, (float)$tx[1]->book_amount);
    }
}
