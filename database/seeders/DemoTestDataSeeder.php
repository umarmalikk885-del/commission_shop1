<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Laga;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\BakriBook;
use App\Models\BankCashTransaction;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class DemoTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(StockTestDataSeeder::class);

        Vendor::where('name', 'like', '[TEST]%')->delete();
        Laga::where('name', 'like', '[TEST]%')->delete();
        Item::where('code', 'like', 'TST-%')->delete();
        Purchase::where('notes', 'like', '[TEST]%')->delete();
        BakriBook::where('additional_details', 'like', '[TEST]%')->delete();
        BankCashTransaction::where('description', 'like', '[TEST]%')->delete();
        Invoice::where('customer', 'like', '[TEST]%')->delete();
        InvoiceItem::where('item_name', 'like', '[TEST]%')->delete();

        $vendors = [];
        $vendorNames = [
            '[TEST] خان گوشت فروش',
            '[TEST] بلال لائیو اسٹاک',
            '[TEST] اصغر ڈیری فارم',
            '[TEST] سٹی مویشی منڈی',
            '[TEST] رحمان ٹریڈرز',
        ];

        foreach ($vendorNames as $name) {
            $vendors[] = Vendor::create([
                'name' => $name,
                'mobile' => '03' . rand(100000000, 999999999),
                'email' => null,
                'address' => 'مین بازار',
                'status' => 'active',
                'commission_rate' => rand(1, 5),
            ]);
        }

        $lagas = [];
        $lagaNames = [
            '[TEST] حامد خریدار',
            '[TEST] سعید مویشی خریدار',
            '[TEST] نعیم ہول سیلر',
            '[TEST] وسیم کمیشن ایجنٹ',
            '[TEST] الکریم بکرے والے',
        ];

        foreach ($lagaNames as $name) {
            $lagas[] = Laga::create([
                'name' => $name,
                'mobile' => '03' . rand(100000000, 999999999),
                'address' => 'مقامی مارکیٹ',
                'location' => 'شہر',
                'bod' => null,
                'contact_number' => null,
                'status' => 'active',
            ]);
        }

        $items = [];
        $itemDefinitions = [
            ['name' => 'بکرا', 'urdu_name' => 'بکرا', 'unit' => 'عدد'],
            ['name' => 'دنبہ', 'urdu_name' => 'دنبہ', 'unit' => 'عدد'],
            ['name' => 'گائے', 'urdu_name' => 'گائے', 'unit' => 'عدد'],
            ['name' => 'بچھڑا', 'urdu_name' => 'بچھڑا', 'unit' => 'عدد'],
            ['name' => 'اونٹ', 'urdu_name' => 'اونٹ', 'unit' => 'عدد'],
        ];

        $index = 1;
        foreach ($itemDefinitions as $def) {
            $code = 'TST-' . str_pad((string) $index, 3, '0', STR_PAD_LEFT);
            $items[] = Item::create([
                'name' => $def['name'],
                'urdu_name' => $def['urdu_name'],
                'code' => $code,
                'type' => 'general',
                'unit' => $def['unit'],
                'rate' => rand(20000, 120000),
                'quantity' => rand(5, 40),
                'created_by' => null,
            ]);
            $index++;
        }

        $purchases = [];
        for ($i = 0; $i < 25; $i++) {
            $vendor = $vendors[$i % count($vendors)];
            $laga = $lagas[$i % count($lagas)];
            $item = $items[$i % count($items)];

            $quantity = rand(1, 40);
            $rate = rand(20000, 120000);
            $total = $quantity * $rate;

            if ($i % 5 === 0) {
                $paid = 0;
            } elseif ($i % 4 === 0) {
                $paid = (int) ($total * 0.5);
            } else {
                $paid = $total;
            }

            $commission = (int) round($total * 0.02);

            $purchase = Purchase::create([
                'bill_number' => 'TST-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'purchase_date' => now()->subDays(rand(0, 30)),
                'vendor_id' => $vendor->id,
                'customer_name' => $laga->name,
                'purchaser_code' => $laga->code,
                'item_name' => $item->name,
                'quantity' => $quantity,
                'unit' => $item->unit,
                'rate' => $rate,
                'total_amount' => $total,
                'commission_amount' => $commission,
                'paid_amount' => $paid,
                'notes' => '[TEST] Demo purchase',
                'user_id' => null,
            ]);

            $purchases[] = $purchase;
        }

        for ($i = 0; $i < 15; $i++) {
            $rawGoat = rand(100, 400);
            $fare = rand(5000, 20000);
            $commission = rand(3000, 15000);
            $labor = rand(2000, 8000);
            $mashiana = rand(1000, 5000);
            $stamp = rand(500, 3000);
            $other = rand(1000, 7000);

            $bakri = new BakriBook([
                'user_id' => null,
                'record_date' => now()->subDays(rand(0, 30)),
                'trader' => '[TEST] بکری ٹریڈر ' . ($i + 1),
                'goat_number' => 'TST-GOAT-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                'truck_number' => 'TRK-' . rand(100, 999),
                'raw_goat' => $rawGoat,
                'fare' => $fare,
                'commission' => $commission,
                'labor' => $labor,
                'mashiana' => $mashiana,
                'stamp' => $stamp,
                'other_expenses' => $other,
                'total_expenses' => 0,
                'net_goat' => 0,
                'balance1' => 0,
                'balance2' => 0,
                'additional_details' => '[TEST] Demo bakri record',
            ]);

            $bakri->calculateTotalExpenses();
            $bakri->calculateNetGoat();
            $bakri->save();
        }

        $transactions = [];
        $types = ['cash', 'bank'];
        for ($i = 0; $i < 20; $i++) {
            $type = $types[$i % count($types)];
            $isDeposit = $i % 2 === 0;
            $amount = rand(5000, 80000);

            $transactions[] = BankCashTransaction::create([
                'transaction_date' => now()->subDays(rand(0, 30)),
                'type' => $type,
                'transaction_type' => $isDeposit ? 'deposit' : 'withdrawal',
                'amount' => $amount,
                'description' => '[TEST] Demo ' . ($isDeposit ? 'deposit' : 'withdrawal'),
                'notes' => null,
                'purchase_id' => $isDeposit && count($purchases) ? $purchases[$i % count($purchases)]->id : null,
                'invoice_id' => null,
            ]);
        }

        $invoices = [];
        for ($i = 0; $i < 10; $i++) {
            $invoice = Invoice::create([
                'bill_no' => 'INV-TST-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                'invoice_date' => now()->subDays(rand(0, 30)),
                'customer' => '[TEST] انوائس کسٹمر ' . ($i + 1),
                'total_amount' => 0,
                'user_id' => null,
            ]);

            $total = 0;
            $lineCount = rand(1, 3);
            for ($j = 0; $j < $lineCount; $j++) {
                $item = $items[($i + $j) % count($items)];
                $qty = rand(1, 5);
                $rate = rand(20000, 90000);
                $lineAmount = $qty * $rate;
                $total += $lineAmount;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $item->name,
                    'quantity' => $qty,
                    'qty' => $qty,
                    'unit' => $item->unit,
                    'rate' => $rate,
                    'amount' => $lineAmount,
                ]);
            }

            $invoice->total_amount = $total;
            $invoice->save();

            $invoices[] = $invoice;
        }
    }
}

