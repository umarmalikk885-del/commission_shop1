<?php

namespace Tests\Unit;

use App\Models\Stock;
use Tests\TestCase;

class StockLowStockThresholdTest extends TestCase
{
    public function test_not_low_stock_when_quantity_above_fifteen_percent_of_min_level()
    {
        $stock = new Stock([
            'quantity' => 20,
            'min_stock_level' => 100,
        ]);

        $this->assertFalse($stock->isLowStock());
        $this->assertSame('in_stock', $stock->stock_status);
    }

    public function test_low_stock_when_quantity_at_or_below_fifteen_percent_of_min_level()
    {
        $stock = new Stock([
            'quantity' => 15,
            'min_stock_level' => 100,
        ]);

        $this->assertTrue($stock->isLowStock());
        $this->assertSame('low_stock', $stock->stock_status);
    }

    public function test_not_low_stock_when_quantity_zero_or_less()
    {
        $stock = new Stock([
            'quantity' => 0,
            'min_stock_level' => 10,
        ]);

        $this->assertFalse($stock->isLowStock());
        $this->assertSame('out_of_stock', $stock->stock_status);
    }

    public function test_not_low_stock_when_min_level_is_zero_or_negative()
    {
        $stock = new Stock([
            'quantity' => 10,
            'min_stock_level' => 0,
        ]);

        $this->assertFalse($stock->isLowStock());
        $this->assertSame('in_stock', $stock->stock_status);
    }
}
