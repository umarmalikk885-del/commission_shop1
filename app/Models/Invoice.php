<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no',
        'invoice_date',
        'customer',
        'total_amount',
        'user_id'
    ];
    
    protected $casts = [
        'invoice_date' => 'date',
        'total_amount' => 'decimal:2'
    ];
    
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankCashTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    /**
     * Scope a query to only include invoices for a specific user.
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
}
