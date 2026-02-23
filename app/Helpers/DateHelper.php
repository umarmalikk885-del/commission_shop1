<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date with localized day and month names.
     * 
     * @param mixed $date The date to format (Carbon instance, string, or null)
     * @param string $format The format string (supports Carbon format codes)
     * @param string|null $fallback Fallback text if date is null
     * @return string
     */
    public static function formatLocalized($date, string $format = 'D, d/m/Y', ?string $fallback = '—'): string
    {
        if (!$date) {
            return $fallback ?? '—';
        }

        try {
            $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
            
            // Get current locale
            $locale = app()->getLocale();
            $carbonLocale = $locale === 'ur' ? 'ur_PK' : 'en';
            
            // Set Carbon locale for this date
            $carbon->setLocale($carbonLocale);
            
            // Use translatedFormat for day/month names
            return $carbon->translatedFormat($format);
        } catch (\Exception $e) {
            return $fallback ?? '—';
        }
    }

    /**
     * Format date with day name (e.g., "Mon, 22/01/2026" or "پیر, 22/01/2026").
     * 
     * @param mixed $date The date to format
     * @param string|null $fallback Fallback text if date is null
     * @return string
     */
    public static function formatWithDay($date, ?string $fallback = '—'): string
    {
        return self::formatLocalized($date, 'D, d/m/Y', $fallback);
    }

    /**
     * Format date with day and month name (e.g., "Mon, 22 Jan 2026" or "پیر, 22 جنوری 2026").
     * 
     * @param mixed $date The date to format
     * @param string|null $fallback Fallback text if date is null
     * @return string
     */
    public static function formatWithDayAndMonth($date, ?string $fallback = '—'): string
    {
        return self::formatLocalized($date, 'D, d M Y', $fallback);
    }

    /**
     * Format month and year (e.g., "January 2026" or "جنوری 2026").
     * 
     * @param mixed $date The date to format
     * @param string|null $fallback Fallback text if date is null
     * @return string
     */
    public static function formatMonthYear($date, ?string $fallback = '—'): string
    {
        return self::formatLocalized($date, 'F Y', $fallback);
    }
}
