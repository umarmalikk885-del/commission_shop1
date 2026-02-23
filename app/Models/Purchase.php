<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseItem;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'purchase_date',
        'vendor_id',
        'customer_name',
        'purchaser_code',
        'item_name',
        'quantity',
        'unit',
        'rate',
        'total_amount',
        'commission_amount',
        'paid_amount',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankCashTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function laga()
    {
        return $this->belongsTo(Laga::class, 'purchaser_code', 'code');
    }
    
    /**
     * Scope a query to only include purchases for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId = null)
    {
        if ($userId !== null) {
            return $query->where('user_id', $userId);
        }
        return $query;
    }

    public function getLagaCodeAttribute()
    {
        return $this->purchaser_code;
    }
}

