<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_code',
        'item_name',
        'category',
        'labor_rate',
        'unit',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'labor_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the labor rate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active rates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include records for a specific user.
     */
    public function scopeForUser($query, $userId = null)
    {
        if ($userId !== null) {
            return $query->where('user_id', $userId);
        }
        return $query;
    }

    /**
     * Get rate by item code.
     */
    public static function getRateByCode($code, $userId = null)
    {
        return self::active()
            ->forUser($userId)
            ->where('item_code', $code)
            ->first();
    }

    /**
     * Get rate by item name.
     */
    public static function getRateByName($name, $userId = null)
    {
        return self::active()
            ->forUser($userId)
            ->where('item_name', 'like', "%{$name}%")
            ->first();
    }
}
