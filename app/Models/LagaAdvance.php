<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LagaAdvance extends Model
{
    use HasFactory;

    protected $table = 'laga_advances';

    protected $fillable = [
        'laga_id',
        'advance_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'advance_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function laga()
    {
        return $this->belongsTo(Laga::class);
    }
}

