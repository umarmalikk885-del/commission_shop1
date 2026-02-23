<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketItem extends Model
{
    protected $fillable = [
        'name',
        'type',
        'product_owner_id',
        'quantity',
        'unit',
        'price_per_unit',
        'status',
        'available_from',
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
    ];

    public function productOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'product_owner_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }
}

