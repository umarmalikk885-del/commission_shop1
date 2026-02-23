<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasMonetaryColumns;

class BakriBookItem extends Model
{
    use HasFactory;
    use HasMonetaryColumns;

    protected $fillable = [
        'bakri_book_id',
        'code',
        'item_date',
        'item_type',
        'packing_code',
        'packing',
        'quantity',
        'labor_rate',
        'labor',
        'labor_transport',
        'commission_rate',
        'marker',
        'row_order',
    ];

    protected $casts = [
        'item_date' => 'date',
        'quantity' => 'decimal:2',
        'labor_rate' => 'decimal:2',
        'labor' => 'decimal:2',
        'labor_transport' => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    protected static $monetaryColumns = [
        'labor',
        'labor_transport',
    ];

    /**
     * Get the bakri book that owns the item.
     */
    public function bakriBook()
    {
        return $this->belongsTo(BakriBook::class);
    }

    /**
     * Calculate labor based on quantity and labor rate.
     */
    public function calculateLabor()
    {
        $this->labor = $this->quantity * $this->labor_rate;
        return $this->labor;
    }
}
