<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'gst_number',
        'default_commission_rate',
        'currency_symbol',
        'tax_rate',
        'role',
        'language',
    ];

    protected $casts = [
        'default_commission_rate' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    /**
     * Get the current (latest) company settings record.
     *
     * Using the latest record instead of first() avoids issues where
     * multiple rows exist and an old row would otherwise control
     * the application role and other global settings.
     */
    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }

    /**
     * Get translated company name if it matches the default English value.
     */
    public function getTranslatedCompanyNameAttribute(): string
    {
        // Default English value from seeder
        $defaultEnglishName = 'SMX TRADES';
        if ($this->company_name === $defaultEnglishName) {
            return __('messages.default_company_name');
        }
        return $this->company_name ?? '';
    }

    /**
     * Get translated company address if it matches the default English value.
     */
    public function getTranslatedAddressAttribute(): string
    {
        // Default English value from seeder
        $defaultEnglishAddress = 'Bangalore, Karnataka';
        if ($this->address === $defaultEnglishAddress) {
            return __('messages.default_company_address');
        }
        return $this->address ?? '';
    }
}
