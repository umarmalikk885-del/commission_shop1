<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LagaPayment extends Model
{
    use HasFactory;

    protected $table = 'laga_payments';

    protected $fillable = [
        'laga_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function laga()
    {
        return $this->belongsTo(Laga::class);
    }
}
