<?php

namespace Database\Factories;

use App\Models\BakriBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BakriBook>
 */
class BakriBookFactory extends Factory
{
    protected $model = BakriBook::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'record_date' => now()->toDateString(),
            'trader' => null,
            'goat_number' => null,
            'truck_number' => null,
            'raw_goat' => 0,
            'fare' => 0,
            'food_rent' => 0,
            'commission' => 0,
            'labor' => 0,
            'mashiana' => 0,
            'stamp' => 0,
            'other_expenses' => 0,
            'total_expenses' => 0,
            'net_goat' => 0,
            'balance1' => 0,
            'balance2' => 0,
            'additional_details' => null,
        ];
    }
}

