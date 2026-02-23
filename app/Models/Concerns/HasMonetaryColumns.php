<?php

namespace App\Models\Concerns;

trait HasMonetaryColumns
{
    public static function monetaryColumns(): array
    {
        return property_exists(static::class, 'monetaryColumns') ? static::$monetaryColumns : [];
    }
}
