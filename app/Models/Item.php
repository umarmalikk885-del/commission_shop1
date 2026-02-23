<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'urdu_name',
        'code',
        'type',
        'unit',
        'rate',
        'quantity',
        'created_by',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];
}
