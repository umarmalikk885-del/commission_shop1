<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        if (Stock::count() > 0) {
            return;
        }

        Stock::insert([
            [
                'item_name' => 'آلو',
                'quantity' => 500,
                'unit' => 'کلو',
                'min_stock_level' => 1000,
                'rate' => 50,
                'description' => 'نارمل اسٹاک',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_name' => 'پیاز',
                'quantity' => 120,
                'unit' => 'کلو',
                'min_stock_level' => 800,
                'rate' => 70,
                'description' => 'نارمل اسٹاک',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_name' => 'ٹماٹر',
                'quantity' => 90,
                'unit' => 'کلو',
                'min_stock_level' => 600,
                'rate' => 60,
                'description' => 'کم اسٹاک (15٪ سے کم)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_name' => 'بند گوبھی',
                'quantity' => 20,
                'unit' => 'کلو',
                'min_stock_level' => 200,
                'rate' => 40,
                'description' => 'کم اسٹاک (15٪ کے قریب)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item_name' => 'کھیرا',
                'quantity' => 0,
                'unit' => 'کلو',
                'min_stock_level' => 150,
                'rate' => 30,
                'description' => 'اسٹاک ختم',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

