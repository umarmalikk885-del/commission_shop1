<!DOCTYPE html>
<html lang="{{ $appLanguage ?? ($settings->language ?? 'ur') }}" dir="{{ ($appLanguage ?? ($settings->language ?? 'ur')) === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.settings') }} - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.prevent-back-button')
    @include('components.global-dark-mode-styles')
    @include('components.main-content-spacing')
    <!-- Initialize dark mode immediately -->
    <script>
        (function() {
            const DARK_MODE_KEY = 'darkMode';
            const THEME_KEY = 'theme';
            const body = document.body;
            
            function isDarkMode() {
                const darkMode = localStorage.getItem(DARK_MODE_KEY);
                if (darkMode !== null) return darkMode === 'true';
                const theme = localStorage.getItem(THEME_KEY);
                if (theme === 'dark') return true;
                if (theme === 'light') return false;
                return localStorage.getItem('darkMode') === 'true';
            }
            
            if (isDarkMode() && body && body.classList) {
                body.classList.add('dark-mode');
            }
        })();
    </script>

    <style>
        @php
            $settingsRole = optional(\App\Models\CompanySetting::current())->role ?? 'admin';
            // Admin user should always see full admin interface
            $appRole = (auth()->check() && auth()->user()->email === 'admin') ? 'admin' : $settingsRole;
        @endphp
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        /* Main Content */
        .main {
            padding: 20px;
        }

        /* Top Bar */
        .topbar {
            background: #fff;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .topbar div {
            font-size: 15px;
        }

        /* Role Management Card */
        .role-management-card {
            background: #fff;
            border-left: 4px solid #1e88e5;
        }

        .role-management-desc {
            margin-bottom: 15px;
            color: #4b5563;
        }

        .role-btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .role-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .role-btn-primary {
            background: #1e88e5;
            color: #ffffff !important;
            font-weight: 600;
            border: 2px solid #1565c0;
        }

        .role-btn-primary:hover {
            background: #1565c0;
            color: #ffffff !important;
            border-color: #0d47a1;
        }

        .role-btn-success {
            background: #10b981;
            color: #ffffff !important;
            font-weight: 600;
            border: 2px solid #059669;
        }

        .role-btn-success:hover {
            background: #059669;
            color: #ffffff !important;
            border-color: #047857;
        }

        /* Dark Mode for Role Management */
        body.dark-mode .role-management-card {
            background: #1e293b;
            border-left-color: #60a5fa;
        }

        body.dark-mode .role-management-desc {
            color: #94a3b8;
        }

        body.dark-mode .role-btn-primary {
            background: #3b82f6 !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
        }

        body.dark-mode .role-btn-primary:hover {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #1d4ed8 !important;
        }

        body.dark-mode .role-btn-success {
            background: #10b981 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }

        body.dark-mode .role-btn-success:hover {
            background: #059669 !important;
            color: #ffffff !important;
            border-color: #047857 !important;
        }


        /* Settings Card */
        .settings-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .settings-card h3 {
            margin: 0 0 25px 0;
            font-size: 20px;
            color: #111827;
            border-bottom: 2px solid #1e88e5;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-card h3 i {
            color: #1e88e5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-save {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 136, 229, 0.4);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        .success-message {
            background: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #1e88e5;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #1e40af;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background: #0f172a;
            color: #e2e8f0;
        }

        body.dark-mode .sidebar {
            background: #1e293b;
        }

        body.dark-mode .sidebar h2 {
            background: #0f172a;
        }

        body.dark-mode .topbar {
            background: #1e293b;
            color: #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .topbar div {
            color: #e2e8f0;
        }

        body.dark-mode .settings-card {
            background: #1e293b;
            border: 1px solid #334155;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .settings-card h3 {
            color: #e2e8f0;
            border-bottom-color: #60a5fa;
        }

        body.dark-mode .settings-card h3 i {
            color: #60a5fa;
        }

        body.dark-mode .form-group label {
            color: #e2e8f0;
        }

        body.dark-mode .form-group input,
        body.dark-mode .form-group textarea {
            background: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        body.dark-mode .form-group input:focus,
        body.dark-mode .form-group textarea:focus,
        body.dark-mode .form-group select:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        body.dark-mode .form-group select {
            background: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        body.dark-mode .form-group select option {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .info-box {
            background: #1e3a5f;
            border-left-color: #60a5fa;
            color: #93c5fd;
        }

        body.dark-mode small {
            color: #94a3b8;
        }

        body.dark-mode h2 {
            color: #e2e8f0;
        }

        body.dark-mode .success-message {
            background: #059669;
        }

        body.dark-mode p,
        body.dark-mode div:not(.sidebar):not(.theme-toggle):not(.theme-toggle-slider),
        body.dark-mode span:not(.theme-toggle-slider) {
            color: #e2e8f0;
        }

        body.dark-mode [style*="color: #111"],
        body.dark-mode [style*="color: #333"],
        body.dark-mode [style*="color: #555"],
        body.dark-mode [style*="color: #111827"],
        body.dark-mode [style*="color: #6b7280"] {
            color: #e2e8f0 !important;
        }

        body.dark-mode small {
            color: #94a3b8 !important;
        }

        /* Ensure language dropdown text is clearly visible in all modes */
        #language {
            background: #fff;
            color: #111827;
        }

        body.dark-mode #language {
            background: #0f172a;
            color: #e2e8f0;
            border-color: #334155;
        }

        body.dark-mode #language option {
            background: #1e293b;
            color: #e2e8f0;
        }

        /* RTL Support - text/layout only (sidebar stays fixed) */
        [dir="rtl"] .form-row {
            direction: rtl;
        }

        [dir="rtl"] .topbar {
            direction: rtl;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

        }
    </style>
    @include('components.urdu-input-support')
</head>
<body>

<!-- Sidebar -->
@include('components.sidebar')

<!-- Main -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div>
            <h2 style="margin: 0; font-size: 24px; color: #111827;">{{ __('messages.settings') }}</h2>
        </div>
        <div>
            @include('components.user-role-display')
        </div>
    </div>

    @if(session('success'))
    <div class="success-message">
        <i class="fa fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Company Information Form (Auto-saves on blur/change) -->
    <form id="companyInfoForm" action="/settings" method="POST">
        @csrf

        <!-- Role Management Section (Only for users with manage roles/users permissions) -->
        @php
            $currentUser = auth()->user();
            $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
            $isAdmin = $currentUser && ($currentUser->hasRole('Admin') || $currentUser->hasRole('Super Admin'));
            $canManageRoles = $isSuperAdmin;
            $canManageUsers = $isAdmin;
        @endphp

        {{-- Show role/user management card for all Admins & Super Admins.
             The individual buttons inside still respect permissions. --}}
        @if($isSuperAdmin || $isAdmin)
        <div class="settings-card role-management-card">
            <h3><i class="fa fa-users-cog"></i> {{ __('messages.role_management') }}</h3>
            <p class="role-management-desc">{{ __('messages.role_management_description') }}</p>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                @if($canManageRoles || $isSuperAdmin)
                <a href="/settings/roles" class="role-btn role-btn-primary">
                    <i class="fa fa-user-shield"></i> {{ __('messages.manage_roles') }}
                </a>
                @endif
                
                @if($canManageUsers || $isAdmin)
                <a href="/settings/users" class="role-btn role-btn-success">
                    <i class="fa fa-users"></i> {{ __('messages.manage_users') }}
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Language Settings (Standalone - Auto-saves on change) -->
        <div class="settings-card">
            <h3><i class="fa fa-language"></i> {{ __('messages.language_settings') }}</h3>
            
            <div class="form-group">
                <label for="language">{{ __('messages.application_language') }} *</label>
                <select id="language" name="language" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; cursor: pointer;" autocomplete="language">
                    <option value="en" {{ ($appLanguage ?? ($settings->language ?? 'ur')) === 'en' ? 'selected' : '' }}>{{ __('messages.english') }}</option>
                    <option value="ur" {{ ($appLanguage ?? ($settings->language ?? 'ur')) === 'ur' ? 'selected' : '' }}>{{ __('messages.urdu') }}</option>
                </select>
            </div>
        </div>

        <!-- Company Information (only for Admin / Super Admin) -->
        @if($isSuperAdmin || $isAdmin)
        <div class="settings-card">
            <h3><i class="fa fa-building"></i> {{ __('messages.company_information') }}</h3>
            
            <div class="form-group">
                <label for="company_name">{{ __('messages.company_name') }} *</label>
                <input type="text" id="company_name" name="company_name" 
                       value="{{ $settings->company_name ?? '' }}" 
                       placeholder="{{ __('messages.enter_company_name') }}" required autocomplete="organization">
            </div>

            <div class="form-group">
                <label for="address">{{ __('messages.address') }} *</label>
                <textarea id="address" name="address" 
                          placeholder="{{ __('messages.enter_complete_address') }}" required autocomplete="street-address">{{ $settings->address ?? '' }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">{{ __('messages.phone') }} *</label>
                    <input type="text" id="phone" name="phone" 
                           value="{{ $settings->phone ?? '' }}" 
                           placeholder="{{ __('messages.phone_placeholder') }}" required autocomplete="tel">
                </div>

                <div class="form-group">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input type="email" id="email" name="email" 
                           value="{{ $settings->email ?? '' }}" 
                           placeholder="{{ __('messages.email_placeholder') }}" autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="gst_number">{{ __('messages.gst_number') }}</label>
                <input type="text" id="gst_number" name="gst_number" 
                       value="{{ $settings->gst_number ?? '' }}" 
                       placeholder="{{ __('messages.gst_placeholder') }}" autocomplete="off">
            </div>
        </div>
        @endif
    </form>
    
    <!-- Auto-save script for Company Information -->
    <script>
        (function() {
            const form = document.getElementById('companyInfoForm');
            if (!form) return;

            const formFields = form.querySelectorAll('input, textarea, select');
            let saveTimeout = null;
            let isSaving = false;

            function autoSave() {
                if (isSaving) return;
                
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    isSaving = true;
                    
                    // Create FormData
                    const formData = new FormData(form);
                    
                    // Show saving indicator
                    const firstCard = document.querySelector('.settings-card');
                    let savingIndicator = document.getElementById('saving-indicator');
                    if (!savingIndicator && firstCard) {
                        savingIndicator = document.createElement('div');
                        savingIndicator.id = 'saving-indicator';
                        savingIndicator.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 10px 20px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; display: flex; align-items: center; gap: 8px;';
                        savingIndicator.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('messages.saving') }}';
                        document.body.appendChild(savingIndicator);
                    }

                    // Send form data
                    fetch('/settings', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch {
                                    return { success: true };
                                }
                            });
                        }
                        throw new Error('Save failed');
                    })
                    .then(data => {
                        if (savingIndicator) {
                            savingIndicator.innerHTML = '<i class="fa fa-check-circle"></i> {{ __('messages.saved') }}';
                            savingIndicator.style.background = '#10b981';
                            setTimeout(() => {
                                if (savingIndicator && savingIndicator.parentNode) {
                                    savingIndicator.style.transition = 'opacity 0.3s';
                                    savingIndicator.style.opacity = '0';
                                    setTimeout(() => {
                                        if (savingIndicator && savingIndicator.parentNode) {
                                            savingIndicator.parentNode.removeChild(savingIndicator);
                                        }
                                    }, 300);
                                }
                            }, 1500);
                        }
                        isSaving = false;
                    })
                    .catch(error => {
                        console.error('Auto-save error:', error);
                        if (savingIndicator) {
                            savingIndicator.innerHTML = '<i class="fa fa-exclamation-circle"></i> {{ __('messages.save_failed') }}';
                            savingIndicator.style.background = '#ef4444';
                            setTimeout(() => {
                                if (savingIndicator && savingIndicator.parentNode) {
                                    savingIndicator.parentNode.removeChild(savingIndicator);
                                }
                            }, 2000);
                        }
                        isSaving = false;
                    });
                }, 1000); // Wait 1 second after last change before saving
            }

            // Add event listeners to all form fields
            formFields.forEach(field => {
                if (field.id !== 'role' && field.id !== 'language') {
                    field.addEventListener('blur', autoSave);
                    field.addEventListener('change', autoSave);
                    field.addEventListener('input', autoSave);
                }
            });
        })();
    </script>
    
</div>

<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

<script>

    // Auto-hide success message after 5 seconds
    (function() {
        const successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.4s ease';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    if (successMessage && successMessage.parentNode) {
                        successMessage.parentNode.removeChild(successMessage);
                    }
                }, 400);
            }, 5000);
        }
    })();

    // Instant language switching
    (function() {
        const languageSelect = document.getElementById('language');
        if (!languageSelect) return;

        let isChanging = false;

        languageSelect.addEventListener('change', function() {
            if (isChanging) return;
            
            const selectedLanguage = this.value;
            isChanging = true;
            
            // Disable select during request
            this.disabled = true;
            this.style.opacity = '0.6';
            this.style.cursor = 'wait';

            // Show loading indicator
            const originalText = this.parentElement.querySelector('.muted')?.textContent || '';
            const infoText = this.parentElement.querySelector('.muted');
            if (infoText) {
                infoText.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('messages.language_switching') }}';
            }

            // Send AJAX request
            fetch('/api/language/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    language: selectedLanguage
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to apply the new language across the entire website
                    window.location.reload();
                } else {
                    // Show error and revert selection
                    alert(data.message || '{{ __('messages.language_switch_failed') }}');
                    languageSelect.value = '{{ $appLanguage ?? ($settings->language ?? "en") }}';
                    isChanging = false;
                    languageSelect.disabled = false;
                    languageSelect.style.opacity = '1';
                    languageSelect.style.cursor = 'pointer';
                    if (infoText) {
                        infoText.innerHTML = originalText;
                    }
                }
            })
            .catch(error => {
                console.error('Language switch error:', error);
                alert('{{ __('messages.language_switch_error') }}');
                languageSelect.value = '{{ $appLanguage ?? ($settings->language ?? "en") }}';
                isChanging = false;
                languageSelect.disabled = false;
                languageSelect.style.opacity = '1';
                languageSelect.style.cursor = 'pointer';
                if (infoText) {
                    infoText.innerHTML = originalText;
                }
            });
        });
    })();

</script>

</body>
</html>

