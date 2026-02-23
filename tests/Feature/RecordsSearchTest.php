<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\BakriBook;
use App\Models\BakriBookTransaction;
use App\Models\BakriBookItem;

class RecordsSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->artisan('migrate');
    }

    public function test_search_by_owner_and_purchaser_is_case_insensitive_and_partial()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $book = BakriBook::create([
            'user_id' => $user->id,
            'record_date' => '2026-02-14',
            'trader' => 'Faheem Khan',
            'goat_number' => '123',
            'truck_number' => 'ABC-1234',
        ]);

        BakriBookTransaction::create([
            'bakri_book_id' => $book->id,
            'transaction_date' => '2026-02-14',
            'book' => 'Ali Ahmed',
            'book_code' => 'A01',
        ]);

        BakriBookItem::create([
            'bakri_book_id' => $book->id,
            'code' => 'X1',
            'item_type' => 'Tomato',
        ]);

        $resp = $this->get('/records/search?owner=fahe&purchaser=ali&product=tom');
        $resp->assertStatus(200);
        $resp->assertSee('Faheem Khan');
        $resp->assertSee('Ali Ahmed');
        $resp->assertSee('Tomato');
    }

    public function test_date_range_filters_both_owner_and_transaction_dates()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $book = BakriBook::create([
            'user_id' => $user->id,
            'record_date' => '2026-02-10',
            'trader' => 'Owner A',
        ]);

        BakriBookTransaction::create([
            'bakri_book_id' => $book->id,
            'transaction_date' => '2026-02-12',
            'book' => 'Buyer B',
        ]);

        $resp = $this->get('/records/search?from=2026-02-11&to=2026-02-13');
        $resp->assertStatus(200);
        $resp->assertSee('Buyer B'); // transaction date included
    }

    public function test_export_csv_contains_expected_columns()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $book = BakriBook::create([
            'user_id' => $user->id,
            'record_date' => '2026-02-14',
            'trader' => 'Owner',
        ]);
        BakriBookTransaction::create([
            'bakri_book_id' => $book->id,
            'transaction_date' => '2026-02-14',
            'book' => 'Buyer',
            'book_code' => 'B1',
        ]);

        $resp = $this->get('/records/search?export=csv');
        $resp->assertStatus(200);
        $resp->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $resp->assertSee('Owner,Truck No,Goat No,Owner Date,Purchaser,Book Code,Transaction Date,Item Type,Item Code', false);
    }
}
