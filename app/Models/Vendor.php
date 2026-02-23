<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'mobile',
        'email',
        'address',
        'status',
        'commission_rate',
    ];

    protected static function booted()
    {
        static::creating(function ($vendor) {
            if (empty($vendor->code)) {
                $vendor->code = static::generateUniqueCode();
            }
        });
    }

    protected static function generateUniqueCode()
    {
        do {
            $code = mt_rand(10000, 99999);
        } while (static::where('code', $code)->exists());
        return (string) $code;
    }
}
