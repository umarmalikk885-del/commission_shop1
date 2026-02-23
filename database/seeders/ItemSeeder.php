<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Vegetables
            ['code' => "V-001", 'name' => "Potato", 'urdu_name' => "آلو", 'type' => 'vegetable'],
            ['code' => "V-002", 'name' => "Onion", 'urdu_name' => "پیاز", 'type' => 'vegetable'],
            ['code' => "V-003", 'name' => "Tomato", 'urdu_name' => "ٹماٹر", 'type' => 'vegetable'],
            ['code' => "V-004", 'name' => "Ginger", 'urdu_name' => "ادرک", 'type' => 'vegetable'],
            ['code' => "V-005", 'name' => "Garlic", 'urdu_name' => "لہسن", 'type' => 'vegetable'],
            ['code' => "V-006", 'name' => "Green Chilli", 'urdu_name' => "ہری مرچ", 'type' => 'vegetable'],
            ['code' => "V-007", 'name' => "Lemon", 'urdu_name' => "لیموں", 'type' => 'vegetable'],
            ['code' => "V-008", 'name' => "Cucumber", 'urdu_name' => "کھیرا", 'type' => 'vegetable'],
            ['code' => "V-009", 'name' => "Cabbage", 'urdu_name' => "بند گوبھی", 'type' => 'vegetable'],
            ['code' => "V-010", 'name' => "Cauliflower", 'urdu_name' => "پھول گوبھی", 'type' => 'vegetable'],
            ['code' => "V-011", 'name' => "Peas", 'urdu_name' => "مٹر", 'type' => 'vegetable'],
            ['code' => "V-012", 'name' => "Carrot", 'urdu_name' => "گاجر", 'type' => 'vegetable'],
            ['code' => "V-013", 'name' => "Radish", 'urdu_name' => "مولی", 'type' => 'vegetable'],
            ['code' => "V-014", 'name' => "Spinach", 'urdu_name' => "پالک", 'type' => 'vegetable'],
            ['code' => "V-015", 'name' => "Turnip", 'urdu_name' => "شلجم", 'type' => 'vegetable'],
            ['code' => "V-016", 'name' => "Ladyfinger", 'urdu_name' => "بھنڈی", 'type' => 'vegetable'],
            ['code' => "V-017", 'name' => "Bitter Gourd", 'urdu_name' => "کریلا", 'type' => 'vegetable'],
            ['code' => "V-018", 'name' => "Brinjal", 'urdu_name' => "بینگن", 'type' => 'vegetable'],
            ['code' => "V-019", 'name' => "Pumpkin", 'urdu_name' => "کدو", 'type' => 'vegetable'],
            ['code' => "V-020", 'name' => "Capsicum", 'urdu_name' => "شملہ مرچ", 'type' => 'vegetable'],
            
            // Fruits
            ['code' => "F-001", 'name' => "Apple", 'urdu_name' => "سیب", 'type' => 'fruit'],
            ['code' => "F-002", 'name' => "Banana", 'urdu_name' => "کیلا", 'type' => 'fruit'],
            ['code' => "F-003", 'name' => "Mango", 'urdu_name' => "آم", 'type' => 'fruit'],
            ['code' => "F-004", 'name' => "Orange", 'urdu_name' => "مالٹا", 'type' => 'fruit'],
            ['code' => "F-005", 'name' => "Grapes", 'urdu_name' => "انگور", 'type' => 'fruit'],
            ['code' => "F-006", 'name' => "Peach", 'urdu_name' => "آڑو", 'type' => 'fruit'],
            ['code' => "F-007", 'name' => "Pomegranate", 'urdu_name' => "انار", 'type' => 'fruit'],
            ['code' => "F-008", 'name' => "Guava", 'urdu_name' => "امرود", 'type' => 'fruit'],
            ['code' => "F-009", 'name' => "Apricot", 'urdu_name' => "خوبانی", 'type' => 'fruit'],
            ['code' => "F-010", 'name' => "Watermelon", 'urdu_name' => "تربوز", 'type' => 'fruit'],
            ['code' => "F-011", 'name' => "Melon", 'urdu_name' => "خربوزہ", 'type' => 'fruit'],
            ['code' => "F-012", 'name' => "Dates", 'urdu_name' => "کھجور", 'type' => 'fruit'],
            ['code' => "F-013", 'name' => "Strawberry", 'urdu_name' => "اسٹرابیری", 'type' => 'fruit'],
            ['code' => "F-014", 'name' => "Papaya", 'urdu_name' => "پپیتا", 'type' => 'fruit'],
            ['code' => "F-015", 'name' => "Pineapple", 'urdu_name' => "انناس", 'type' => 'fruit'],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
