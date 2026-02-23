<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use Illuminate\Support\Str;

class StockTestDataSeeder extends Seeder
{
    public function run(): void
    {
        Stock::where('description', 'like', '[TEST]%')->delete();

        $products = [
            ['name' => 'آلو', 'unit' => 'کلو', 'category' => 'سبزی'],
            ['name' => 'پیاز', 'unit' => 'کلو', 'category' => 'سبزی'],
            ['name' => 'ٹماٹر', 'unit' => 'کلو', 'category' => 'سبزی'],
            ['name' => 'آم', 'unit' => 'کلو', 'category' => 'پھل'],
            ['name' => 'سیب', 'unit' => 'کلو', 'category' => 'پھل'],
            ['name' => 'کیلا', 'unit' => 'درجن', 'category' => 'پھل'],
            ['name' => 'چاول باسمتی', 'unit' => 'کلو', 'category' => 'گراسری'],
            ['name' => 'چینی', 'unit' => 'کلو', 'category' => 'گراسری'],
            ['name' => 'گھی', 'unit' => 'لیٹر', 'category' => 'گراسری'],
            ['name' => 'صاف تیل', 'unit' => 'لیٹر', 'category' => 'گراسری'],
            ['name' => 'دودھ پیکٹ', 'unit' => 'لیٹر', 'category' => 'ڈیری'],
            ['name' => 'دہی', 'unit' => 'کلو', 'category' => 'ڈیری'],
            ['name' => 'چیز', 'unit' => 'کلو', 'category' => 'ڈیری'],
            ['name' => 'فروزن مٹر', 'unit' => 'کلو', 'category' => 'فروزن'],
            ['name' => 'فروزن نگٹس', 'unit' => 'ڈبی', 'category' => 'فروزن'],
        ];

        $suppliers = [
            'الحبیب ٹریڈرز',
            'خان برادرز',
            'سٹی سپلائرز',
            'المکہ ڈسٹری بیوٹرز',
            'عمران اینڈ سنز',
            'الرشید کمپنی',
            'فیصل ٹریڈنگ',
        ];

        $items = [];
        $totalItems = 80;

        for ($i = 0; $i < $totalItems; $i++) {
            $product = $products[$i % count($products)];
            $supplier = $suppliers[$i % count($suppliers)];

            $baseSku = Str::upper(Str::slug($product['name'], ''));
            $sku = 'TEST-' . $baseSku . '-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);

            $minStockLevel = rand(50, 1000);

            if ($i % 10 === 0) {
                $quantity = 0;
            } elseif ($i % 9 === 0) {
                $quantity = (int) floor($minStockLevel * 0.1);
            } elseif ($i % 8 === 0) {
                $quantity = (int) ceil($minStockLevel * 0.15);
            } else {
                $quantity = rand($minStockLevel, $minStockLevel * 4);
            }

            if ($quantity < 0) {
                $quantity = 0;
            }

            $rate = rand(40, 800);
            if ($i % 13 === 0) {
                $rate = 15000;
            }

            $notes = [];
            if ($quantity === 0) {
                $notes[] = 'Zero stock';
            }
            if ($quantity > $minStockLevel * 3) {
                $notes[] = 'High stock';
            }
            if ($rate >= 10000) {
                $notes[] = 'High value item';
            }
            if ($i % 7 === 0) {
                $notes[] = 'Expired batch 2023-12-31';
            }

            $items[] = [
                'item_name' => $product['name'],
                'quantity' => $quantity,
                'unit' => $product['unit'],
                'min_stock_level' => $minStockLevel,
                'rate' => $rate,
                'description' => '[TEST] SKU: ' . $sku .
                    ' | Category: ' . $product['category'] .
                    ' | Supplier: ' . $supplier .
                    (count($notes) ? ' | Notes: ' . implode(', ', $notes) : ''),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Stock::insert($items);
    }
}

