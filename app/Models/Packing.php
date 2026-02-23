<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    protected $fillable = [
        'name',
        'code',
        'labor',
        'details',
    ];
}
