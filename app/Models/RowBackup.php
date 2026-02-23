<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RowBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_name',
        'record_id',
        'data',
        'operation_type',
        'user_id',
        'inserted_at',
    ];

    protected $casts = [
        'data' => 'array',
        'inserted_at' => 'datetime',
    ];
}

