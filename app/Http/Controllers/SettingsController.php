<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    public function index()
    {
        // Always use the latest settings row to avoid stale data
        $settings = CompanySetting::current();
        
        // Get current language from locale (set by middleware) or fallback to settings
        $appLanguage = app()->getLocale();
        if ($appLanguage === null) {
            $appLanguage = $settings->language ?? 'ur';
        } elseif (str_starts_with($appLanguage, 'ur')) {
            $appLanguage = 'ur';
        } else {
            $appLanguage = 'ur';
        }
        
        return view('settings', compact('settings', 'appLanguage'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'default_commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // Role and Language are now handled separately via AJAX, so they're optional here
            'role' => ['nullable', 'in:admin,operator'],
            'language' => ['nullable', 'in:en,ur'],
        ]);

        // Always update the latest settings row (or create one if missing)
        $settings = CompanySetting::current();
        
        // If role is not provided, keep the existing role
        if (!isset($data['role'])) {
            $data['role'] = $settings->role ?? 'admin';
        }
        
        // If language is not provided, keep the existing language
        if (!isset($data['language'])) {
            $data['language'] = $settings->language ?? 'ur';
        }
        
        if ($settings) {
            $settings->update($data);
            // Refresh the model to ensure latest data
            $settings->refresh();
        } else {
            $settings = CompanySetting::create($data);
        }

        // Clear any caches to ensure fresh data on next request
        Cache::forget('company_settings');
        
        // Immediately set locale for this request
        $newLanguage = $data['language'] ?? $settings->language ?? 'ur';
        
        // Ensure language is valid
        if (!in_array($newLanguage, ['en', 'ur'], true)) {
            $newLanguage = 'ur';
        }
        
        app()->setLocale($newLanguage);
        Config::set('app.locale', $newLanguage);
        
        // Pass appLanguage to ensure the redirect shows correct direction
        $appLanguage = $newLanguage;

        return redirect('/settings')->with('success', __('messages.settings_updated'));
    }

    /**
     * Switch language via AJAX (for global language switcher)
     */
    public function switchLanguage(Request $request)
    {
        // Handle both JSON and form data
        $language = $request->input('language');
        
        // If JSON request, get from JSON body
        if ($request->isJson() || $request->expectsJson()) {
            $language = $request->json('language') ?? $request->input('language');
        }
        
        $request->validate([
            'language' => ['required', 'in:en,ur'],
        ]);

        // Update database
        $settings = CompanySetting::current();
        if ($settings) {
            $settings->update(['language' => $language]);
            $settings->refresh();
        } else {
            // Create default settings if none exist
            $defaultData = [
                'company_name' => 'Commission Shop',
                'address' => '',
                'phone' => '',
                'role' => 'admin',
                'language' => $language,
            ];
            CompanySetting::create($defaultData);
        }

        // Clear cache
        Cache::forget('company_settings');

        // Set locale for response
        app()->setLocale($language);
        Config::set('app.locale', $language);

        return response()->json([
            'success' => true,
            'language' => $language,
            'message' => __('messages.language_updated') ?? 'Language updated successfully'
        ]);
    }

    /**
     * Update application role via AJAX (for instant role switching)
     */
    public function updateRole(Request $request)
    {
        // Handle both JSON and form data
        $role = $request->input('role');
        
        // If JSON request, get from JSON body
        if ($request->isJson() || $request->expectsJson()) {
            $role = $request->json('role') ?? $request->input('role');
        }
        
        $request->validate([
            'role' => ['required', 'in:admin,operator'],
        ]);

        // Update database
        $settings = CompanySetting::current();
        if ($settings) {
            $settings->update(['role' => $role]);
            $settings->refresh();
        } else {
            // Create default settings if none exist
            $defaultData = [
                'company_name' => 'Commission Shop',
                'address' => '',
                'phone' => '',
                'role' => $role,
                'language' => 'en',
            ];
            CompanySetting::create($defaultData);
        }

        // Clear cache
        Cache::forget('company_settings');

        return response()->json([
            'success' => true,
            'role' => $role,
            'message' => 'Application role updated successfully'
        ]);
    }
}
