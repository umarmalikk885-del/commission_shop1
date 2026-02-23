<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Laga extends Model
{
    use HasFactory;

    protected $table = 'lagas';

    protected $fillable = [
        'code',
        'name',
        'mobile',
        'address',
        'location',
        'bod',
        'contact_number',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($purchaser) {
            if (empty($purchaser->code)) {
                $purchaser->code = static::generateUniqueCode();
            }
        });
    }

    protected static function generateUniqueCode()
    {
        do {
            $code = mt_rand(100000, 999999);
        } while (static::where('code', $code)->exists());
        return (string) $code;
    }

    public function payments()
    {
        $table = (new LagaPayment)->getTable();
        $fk = Schema::hasColumn($table, 'laga_id') ? 'laga_id' : (Schema::hasColumn($table, 'purchaser_id') ? 'purchaser_id' : 'laga_id');
        return $this->hasMany(LagaPayment::class, $fk, 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'purchaser_code', 'code');
    }

    public function advances()
    {
        return $this->hasMany(LagaAdvance::class);
    }
}
