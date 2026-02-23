<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakriBookTraderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'bakri_book_id',
        'trader_quantity',
        'trader_rate',
        'trader_amount',
        'book_quantity',
        'book_rate',
        'payment_rate',
        'book_amount',
    ];

    protected $casts = [
        'trader_quantity' => 'decimal:2',
        'trader_rate' => 'decimal:2',
        'trader_amount' => 'decimal:2',
        'book_quantity' => 'decimal:2',
        'book_rate' => 'decimal:2',
        'payment_rate' => 'decimal:2',
        'book_amount' => 'decimal:2',
    ];

    /**
     * Get the bakri book that owns the trader detail.
     */
    public function bakriBook()
    {
        return $this->belongsTo(BakriBook::class);
    }

    /**
     * Calculate trader amount.
     */
    public function calculateTraderAmount()
    {
        $this->trader_amount = $this->trader_quantity * $this->trader_rate;
        return $this->trader_amount;
    }

    /**
     * Calculate book amount.
     */
    public function calculateBookAmount()
    {
        $this->book_amount = $this->book_quantity * $this->book_rate;
        return $this->book_amount;
    }
}
