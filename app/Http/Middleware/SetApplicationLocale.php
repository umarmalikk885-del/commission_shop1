<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class SetApplicationLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Read language setting from database and set locale early in request lifecycle
        // Use current() method to get the latest settings record (same as SettingsController)
        // Only try to access database if user is authenticated or if it's a safe route
        try {
            $settings = \App\Models\CompanySetting::current();
            $language = optional($settings)->language ?? 'ur';
        } catch (\Exception $e) {
            // If database is not accessible (e.g., during migrations or before auth), default to English
            $language = 'ur';
        }
        
        // Ensure language is valid (fallback to 'en' if invalid)
        if (!in_array($language, ['en', 'ur'], true)) {
            $language = 'ur';
        }
        
        // Set application locale early in request lifecycle
        // This must be set before any views are rendered
        app()->setLocale($language);
        Config::set('app.locale', $language);
        
        // Also set it in the request for consistency
        if (method_exists($request, 'setLocale')) {
            $request->setLocale($language);
        }
        
        // Set Carbon locale for date translations
        // Map 'ur' to 'ur_PK' for Carbon (Urdu Pakistan locale)
        $carbonLocale = $language === 'ur' ? 'ur_PK' : 'en';
        try {
            Carbon::setLocale($carbonLocale);
        } catch (\Exception $e) {
            // Fallback to English if locale is not available
            Carbon::setLocale('en');
        }
        
        return $next($request);
    }
}
