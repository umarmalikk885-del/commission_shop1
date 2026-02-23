<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasMonetaryColumns;

class BakriBook extends Model
{
    use HasFactory;
    use HasMonetaryColumns;

    protected $fillable = [
        'user_id',
        'record_date',
        'trader',
        'goat_number',
        'truck_number',
        'raw_goat',
        'fare',
        'food_rent',
        'commission',
        'labor',
        'mashiana',
        'stamp',
        'other_expenses',
        'total_expenses',
        'net_goat',
        'balance1',
        'balance2',
        'additional_details',
    ];

    protected static $monetaryColumns = [
        'raw_goat',
        'fare',
        'food_rent',
        'commission',
        'labor',
        'mashiana',
        'stamp',
        'other_expenses',
        'total_expenses',
        'net_goat',
        'balance1',
        'balance2',
    ];

    protected $casts = [
        'record_date' => 'date',
        'raw_goat' => 'decimal:2',
        'fare' => 'decimal:2',
        'food_rent' => 'decimal:2',
        'commission' => 'decimal:2',
        'labor' => 'decimal:2',
        'mashiana' => 'decimal:2',
        'stamp' => 'decimal:2',
        'other_expenses' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_goat' => 'decimal:2',
        'balance1' => 'decimal:2',
        'balance2' => 'decimal:2',
    ];

    /**
     * Get the user that owns the bakri book.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the bakri book (first table).
     */
    public function items()
    {
        return $this->hasMany(BakriBookItem::class)->orderBy('row_order');
    }

    /**
     * Get the trader details for the bakri book (second table).
     */
    public function traderDetails()
    {
        return $this->hasMany(BakriBookTraderDetail::class);
    }

    /**
     * Get the transactions for the bakri book (third table).
     */
    public function transactions()
    {
        return $this->hasMany(BakriBookTransaction::class)->orderBy('row_order');
    }

    public function calculateTotalExpenses()
    {
        $components = [
            'fare' => $this->fare,
            'commission' => $this->commission,
            'labor' => $this->labor,
            'mashiana' => $this->mashiana,
            'other_expenses' => $this->other_expenses,
        ];

        $total = 0.0;

        foreach ($components as $field => $value) {
            if (!is_null($value) && !is_numeric($value)) {
                logger()->warning('BakriBook expense component is non-numeric', [
                    'field' => $field,
                    'value' => $value,
                    'bakri_book_id' => $this->id,
                ]);
                continue;
            }

            $total += (float) ($value ?? 0);
        }

        $this->total_expenses = $total;
        return $this->total_expenses;
    }

    public function calculateNetGoat()
    {
        if ($this->total_expenses === null) {
            $this->calculateTotalExpenses();
        }

        $raw = (float) ($this->raw_goat ?? 0);
        $expenses = (float) ($this->total_expenses ?? 0);

        $this->net_goat = $raw - $expenses;
        return $this->net_goat;
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
}
