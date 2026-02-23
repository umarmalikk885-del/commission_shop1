<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'quantity',
        'unit',
        'min_stock_level',
        'rate',
        'description',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_stock_level' => 'decimal:2',
        'rate' => 'decimal:2',
    ];

    public function isLowStock()
    {
        if ($this->quantity <= 0) {
            return false;
        }

        if ($this->min_stock_level <= 0) {
            return false;
        }

        $thresholdQuantity = $this->min_stock_level * 0.15;

        return $this->quantity <= $thresholdQuantity;
    }
    
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        }

        if ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }
}
