<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    protected $fillable = [
        'market_item_id',
        'user_id',
        'action',
        'quantity_before',
        'quantity_after',
        'price_before',
        'price_after',
        'meta',
    ];

    protected $casts = [
        'quantity_before' => 'decimal:2',
        'quantity_after' => 'decimal:2',
        'price_before' => 'decimal:2',
        'price_after' => 'decimal:2',
        'meta' => 'array',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(MarketItem::class, 'market_item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

