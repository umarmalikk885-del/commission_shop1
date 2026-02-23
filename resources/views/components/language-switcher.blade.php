<div class="language-switcher-wrapper">
    <button id="languageToggle" class="language-switcher" type="button" title="{{ __('messages.switch_language') ?? 'زبان تبدیل کریں' }}">
        <i class="fa fa-language"></i>
        <span id="currentLanguage">{{ $appLanguage === 'ur' ? 'اردو' : 'انگریزی' }}</span>
        <i class="fa fa-caret-down"></i>
    </button>
    <div id="languageDropdown" class="language-dropdown" style="display: none;">
        <button type="button" class="language-option {{ $appLanguage === 'en' ? 'active' : '' }}" data-lang="en">
            <i class="fa fa-globe"></i> انگریزی
        </button>
        <button type="button" class="language-option {{ $appLanguage === 'ur' ? 'active' : '' }}" data-lang="ur">
            <i class="fa fa-globe"></i> اردو
        </button>
    </div>
</div>

<style>
    .language-switcher-wrapper {
        position: relative;
        display: inline-block;
    }

    .language-switcher {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
        transition: all 0.2s;
    }

    .language-switcher:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .language-switcher i.fa-language {
        font-size: 16px;
        color: #6b7280;
    }

    .language-switcher i.fa-caret-down {
        font-size: 12px;
        color: #9ca3af;
    }

    .language-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 4px;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        min-width: 160px;
        overflow: hidden;
    }

    .language-option {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 16px;
        background: #fff;
        border: none;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
        transition: background 0.2s;
    }

    .language-option:hover {
        background: #f9fafb;
    }

    .language-option.active {
        background: #eff6ff;
        color: #1e88e5;
        font-weight: 600;
    }

    .language-option i {
        font-size: 14px;
        color: #6b7280;
    }

    .language-option.active i {
        color: #1e88e5;
    }

    /* Dark Mode Styles */
    body.dark-mode .language-switcher {
        background: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }

    body.dark-mode .language-switcher:hover {
        background: #334155;
        border-color: #475569;
    }

    body.dark-mode .language-switcher i.fa-language {
        color: #94a3b8;
    }

    body.dark-mode .language-switcher i.fa-caret-down {
        color: #94a3b8;
    }

    body.dark-mode .language-dropdown {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    body.dark-mode .language-option {
        background: #1e293b;
        color: #e2e8f0;
    }

    body.dark-mode .language-option:hover {
        background: #334155;
    }

    body.dark-mode .language-option.active {
        background: #1e3a5f;
        color: #60a5fa;
    }

    body.dark-mode .language-option i {
        color: #94a3b8;
    }

    body.dark-mode .language-option.active i {
        color: #60a5fa;
    }

    /* RTL Support */
    [dir="rtl"] .language-dropdown {
        right: auto;
        left: 0;
    }

    [dir="rtl"] .language-option {
        text-align: right;
    }
</style>

<script>
    (function() {
        const toggle = document.getElementById('languageToggle');
        const dropdown = document.getElementById('languageDropdown');
        const currentLangSpan = document.getElementById('currentLanguage');
        const options = dropdown ? dropdown.querySelectorAll('.language-option') : [];

        // Toggle dropdown
        if (toggle && dropdown) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = dropdown.style.display !== 'none';
                dropdown.style.display = isVisible ? 'none' : 'block';
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (dropdown && toggle && !toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Handle language selection
        options.forEach(function(option) {
            option.addEventListener('click', function() {
                const lang = option.getAttribute('data-lang');
                if (lang) {
                    // Update UI immediately
                    options.forEach(function(opt) {
                        opt.classList.remove('active');
                    });
                    option.classList.add('active');
                    
                    // Update current language display
                    if (currentLangSpan) {
                        currentLangSpan.textContent = lang === 'ur' ? 'اردو' : 'انگریزی';
                    }

                    // Save to localStorage for immediate persistence
                    localStorage.setItem('preferredLanguage', lang);

                    // Get CSRF token from meta tag or form
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                        || document.querySelector('input[name="_token"]')?.value 
                        || '';

                    // Update database via AJAX
                    fetch('/api/language/switch', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ language: lang })
                    })
                    .then(function(response) {
                        if (response.ok) {
                            // Reload page to apply language change
                            window.location.reload();
                        } else {
                            console.error('Failed to update language');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error updating language:', error);
                        // Still reload to apply localStorage preference
                        window.location.reload();
                    });

                    // Close dropdown
                    if (dropdown) {
                        dropdown.style.display = 'none';
                    }
                }
            });
        });
    })();
</script>
