<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\CompanySetting;
use App\Models\BakriBook;
use App\Models\BakriBookTransaction;
use App\Models\BakriBookItem;
use App\Observers\MonetaryObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Services\RowBackupService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share current application role and language with all views
        // Read fresh from database each time to ensure language changes take effect immediately
        View::composer('*', function ($view) {
            // Always read fresh settings using current() to get the latest record
            // This ensures language changes take effect immediately
            // Use the same method the middleware uses to ensure consistency
            $settings = CompanySetting::current();
            
            // Get language directly from settings (same source as middleware uses)
            // The middleware sets app()->setLocale() from this same source
            $language = optional($settings)->language ?? 'ur';
            
            // Ensure language is valid (fallback to 'en' if invalid)
            if (!in_array($language, ['en', 'ur'], true)) {
                $language = 'ur';
            }
            
            $role = optional($settings)->role ?? 'admin';
            
            // Share with views - this ensures dir attribute is set correctly
            // (middleware already set app()->setLocale() for translations)
            $view->with('appRole', $role);
            $view->with('appLanguage', $language);
            
            // Set Carbon locale to match application locale for date translations
            // Map 'ur' to 'ur_PK' for Carbon (Urdu Pakistan locale)
            $carbonLocale = $language === 'ur' ? 'ur_PK' : 'en';
            try {
                Carbon::setLocale($carbonLocale);
            } catch (\Exception $e) {
                // Fallback to English if locale is not available
                Carbon::setLocale('en');
            }
        });

        BakriBook::observe(MonetaryObserver::class);
        BakriBookTransaction::observe(MonetaryObserver::class);
        BakriBookItem::observe(MonetaryObserver::class);

        Model::created(function (Model $model) {
            app(RowBackupService::class)->backupModel($model, 'insert');
        });
    }
}
