<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\BankCashTransaction;

class BalanceSheetExpenseRowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->artisan('migrate');
    }

    public function test_appended_expense_rows_appear_and_total_includes_them()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $today = date('Y-m-d');

        BankCashTransaction::create([
            'transaction_date' => $today,
            'transaction_type' => 'withdrawal',
            'amount' => 1000,
            'description' => 'Rent',
        ]);
        BankCashTransaction::create([
            'transaction_date' => $today,
            'transaction_type' => 'withdrawal',
            'amount' => 800,
            'description' => 'Advance Rent',
        ]);
        BankCashTransaction::create([
            'transaction_date' => $today,
            'transaction_type' => 'withdrawal',
            'amount' => 500,
            'description' => 'Food Salary',
        ]);
        BankCashTransaction::create([
            'transaction_date' => $today,
            'transaction_type' => 'withdrawal',
            'amount' => 200,
            'description' => 'Food Expense',
        ]);
        BankCashTransaction::create([
            'transaction_date' => $today,
            'transaction_type' => 'withdrawal',
            'amount' => 300,
            'description' => 'Former Payment',
        ]);
        BankCashTransaction::create([
            'transaction_date' => $today,
            'transaction_type' => 'withdrawal',
            'amount' => 200,
            'description' => 'Tea',
        ]);

        $response = $this->get(route('balance-sheet', [
            'start_date' => $today,
            'end_date' => $today,
        ]));

        $response->assertStatus(200);
        $response->assertSee('دکان کرایہ (ماہانہ)');
        $response->assertSee('خوراک');
        $response->assertSee('کل کسان ایڈوانس رقم');
        $response->assertSee('1800.00'); // Rent + Advance Rent
        $response->assertSee('700.00');  // Food Salary + Food Expense
        $response->assertSee('300.00');  // Former Payment
        $response->assertSee('Tea');
        $response->assertSee('200.00');

        // Total: (1800 rent) + (700 food) + (300 former) + Tea 200 = 3000.00
        $response->assertSee('3000.00');
    }
}
