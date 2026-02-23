<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'vendor',
        'amount',
        'transaction_date'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date'
    ];
}
