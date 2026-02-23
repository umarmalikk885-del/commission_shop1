<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCashTransaction extends Model
{
    use HasFactory;

    protected $table = 'bank_cash_transactions';

    protected $fillable = [
        'transaction_date',
        'type',
        'transaction_type',
        'amount',
        'description',
        'notes',
        'purchase_id',
        'invoice_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
