// Global Dark Mode Toggle Script
// Shared dark-mode state across all pages and toggle instances.

(function () {
    'use strict';

    if (window.__globalDarkModeInitialized) {
        return;
    }
    window.__globalDarkModeInitialized = true;

    const DARK_MODE_KEY = 'darkMode';
    const THEME_KEY = 'theme';
    const TOGGLE_SELECTOR = '[data-dark-mode-toggle], #globalDarkModeToggle, #themeToggle';

    function readDarkModePreference() {
        try {
            const darkMode = localStorage.getItem(DARK_MODE_KEY);
            if (darkMode !== null) {
                return darkMode === 'true';
            }

            const theme = localStorage.getItem(THEME_KEY);
            if (theme === 'dark') {
                return true;
            }
            if (theme === 'light') {
                return false;
            }
        } catch (e) {
            // Ignore storage errors and fallback to light mode.
        }

        return false;
    }

    function writeDarkModePreference(enabled) {
        try {
            localStorage.setItem(DARK_MODE_KEY, enabled ? 'true' : 'false');
            localStorage.setItem(THEME_KEY, enabled ? 'dark' : 'light');
        } catch (e) {
            // Ignore storage errors.
        }
    }

    function injectDarkModeStyles() {
        const styleId = 'global-dark-mode-override';
        let styleEl = document.getElementById(styleId);

        if (!styleEl) {
            styleEl = document.createElement('style');
            styleEl.id = styleId;
            document.head.appendChild(styleEl);
        }

        styleEl.textContent = `
            html.dark-mode,
            html.dark-mode body,
            html body.dark-mode {
                --bg-color: #0f172a !important;
                --card-bg: #1e293b !important;
                --border-color: #334155 !important;
                --text-color: #e2e8f0 !important;
                --muted-text-color: #94a3b8 !important;
                --dm-bg: #0f172a;
                --dm-surface: #1e293b;
                --dm-surface-alt: #111827;
                --dm-border: #334155;
                --dm-text: #e2e8f0;
                --dm-muted: #94a3b8;
                --dm-link: #60a5fa;
                --dm-link-hover: #93c5fd;
                --dm-sidebar-start: #1e293b;
                --dm-sidebar-end: #0f172a;
                background: #0f172a !important;
                background-color: #0f172a !important;
                color: #e2e8f0 !important;
            }

            html body.dark-mode .main,
            html body.dark-mode .content,
            html body.dark-mode .container {
                background: transparent !important;
                color: #e2e8f0 !important;
            }

            html body.dark-mode .sidebar {
                background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
                color: #e2e8f0 !important;
            }

            html body.dark-mode .sidebar h2 {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
                color: #e2e8f0 !important;
                border-bottom-color: rgba(255, 255, 255, 0.15) !important;
            }

            html body.dark-mode .sidebar ul li a {
                color: #cbd5e1 !important;
            }

            html body.dark-mode .sidebar ul li a:hover {
                background: rgba(255, 255, 255, 0.15) !important;
            }

            html body.dark-mode .sidebar ul li a.active {
                background: linear-gradient(135deg, rgba(96, 165, 250, 0.3) 0%, rgba(96, 165, 250, 0.2) 100%) !important;
                border-left-color: #60a5fa !important;
                color: #ffffff !important;
            }

            html body.dark-mode .topbar,
            html body.dark-mode .header,
            html body.dark-mode .card,
            html body.dark-mode .mandi-card,
            html body.dark-mode .summary-card,
            html body.dark-mode .settings-card,
            html body.dark-mode .role-management-card,
            html body.dark-mode .info-box,
            html body.dark-mode .search-section,
            html body.dark-mode .results-section,
            html body.dark-mode .tabs-nav,
            html body.dark-mode .nav-footer,
            html body.dark-mode .expense-panel,
            html body.dark-mode .modal-content {
                background: #1e293b !important;
                border-color: #334155 !important;
                color: #e2e8f0 !important;
            }

            html body.dark-mode h1,
            html body.dark-mode h2,
            html body.dark-mode h3,
            html body.dark-mode h4,
            html body.dark-mode h5,
            html body.dark-mode h6,
            html body.dark-mode label,
            html body.dark-mode p,
            html body.dark-mode li {
                color: #e2e8f0 !important;
            }

            html body.dark-mode .text-muted,
            html body.dark-mode small,
            html body.dark-mode .muted {
                color: #94a3b8 !important;
            }

            html body.dark-mode table,
            html body.dark-mode .table {
                color: #e2e8f0 !important;
            }

            html body.dark-mode table th,
            html body.dark-mode .table th {
                background: #111827 !important;
                color: #94a3b8 !important;
                border-bottom-color: #334155 !important;
            }

            html body.dark-mode table td,
            html body.dark-mode .table td {
                color: #cbd5e1 !important;
                border-bottom-color: #334155 !important;
            }

            html body.dark-mode table tbody tr:hover,
            html body.dark-mode .table tbody tr:hover {
                background-color: rgba(148, 163, 184, 0.08) !important;
            }

            html body.dark-mode .form-control,
            html body.dark-mode input[type="text"],
            html body.dark-mode input[type="email"],
            html body.dark-mode input[type="password"],
            html body.dark-mode input[type="number"],
            html body.dark-mode input[type="date"],
            html body.dark-mode input[type="search"],
            html body.dark-mode select,
            html body.dark-mode textarea {
                background: #0f172a !important;
                border: 1px solid #334155 !important;
                color: #e2e8f0 !important;
            }

            html body.dark-mode .form-control:focus,
            html body.dark-mode input:focus,
            html body.dark-mode select:focus,
            html body.dark-mode textarea:focus {
                border-color: #60a5fa !important;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15) !important;
                outline: none !important;
            }

            html body.dark-mode select option {
                background: #1e293b !important;
                color: #e2e8f0 !important;
            }

            html body.dark-mode input::placeholder,
            html body.dark-mode textarea::placeholder {
                color: #64748b !important;
                opacity: 1 !important;
            }

            html body.dark-mode .user-role-display,
            html body.dark-mode #userDropdownTrigger {
                color: #e2e8f0 !important;
            }

            html body.dark-mode #userDropdownMenu {
                background: #1e293b !important;
                border-color: #334155 !important;
            }

            html body.dark-mode #userDropdownMenu button,
            html body.dark-mode #userDropdownMenu a {
                color: #e2e8f0 !important;
            }

            html body.dark-mode #userDropdownMenu button:hover,
            html body.dark-mode #userDropdownMenu a:hover {
                background-color: #334155 !important;
            }

            html body.dark-mode [style*="color: #000"],
            html body.dark-mode [style*="color:#000"],
            html body.dark-mode [style*="color: #111"],
            html body.dark-mode [style*="color:#111"],
            html body.dark-mode [style*="color: #333"],
            html body.dark-mode [style*="color:#333"],
            html body.dark-mode [style*="color: #374151"],
            html body.dark-mode [style*="color:#374151"],
            html body.dark-mode [style*="color: #475569"],
            html body.dark-mode [style*="color:#475569"],
            html body.dark-mode [style*="color: #4b5563"],
            html body.dark-mode [style*="color:#4b5563"],
            html body.dark-mode [style*="color: #555"],
            html body.dark-mode [style*="color:#555"],
            html body.dark-mode [style*="color: #6b7280"],
            html body.dark-mode [style*="color:#6b7280"] {
                color: #e2e8f0 !important;
            }

            html body.dark-mode [style*="color: #64748b"],
            html body.dark-mode [style*="color:#64748b"],
            html body.dark-mode [style*="color: #94a3b8"],
            html body.dark-mode [style*="color:#94a3b8"] {
                color: #94a3b8 !important;
            }

            html body.dark-mode [style*="background: #fff"],
            html body.dark-mode [style*="background:#fff"],
            html body.dark-mode [style*="background: white"],
            html body.dark-mode [style*="background:white"],
            html body.dark-mode [style*="background-color: #fff"],
            html body.dark-mode [style*="background-color:#fff"],
            html body.dark-mode [style*="background-color: white"],
            html body.dark-mode [style*="background-color:white"] {
                background: #1e293b !important;
                background-color: #1e293b !important;
            }

            html body.dark-mode [style*="background: #f8fafc"],
            html body.dark-mode [style*="background:#f8fafc"],
            html body.dark-mode [style*="background-color: #f8fafc"],
            html body.dark-mode [style*="background-color:#f8fafc"],
            html body.dark-mode [style*="background: #f9fafb"],
            html body.dark-mode [style*="background:#f9fafb"],
            html body.dark-mode [style*="background: #f4f6f9"],
            html body.dark-mode [style*="background:#f4f6f9"],
            html body.dark-mode [style*="background: #f1f5f9"],
            html body.dark-mode [style*="background:#f1f5f9"] {
                background: #0f172a !important;
                background-color: #0f172a !important;
            }
        `;
    }

    function removeDarkModeStyles() {
        const styleEl = document.getElementById('global-dark-mode-override');
        if (styleEl) {
            styleEl.remove();
        }
    }

    function syncToggleUi(enabled) {
        document.querySelectorAll(TOGGLE_SELECTOR).forEach(function (toggle) {
            toggle.setAttribute('aria-pressed', enabled ? 'true' : 'false');

            const icon = toggle.querySelector('[data-dark-mode-icon], #darkModeIcon, #themeIcon');
            if (icon && icon.classList) {
                if (enabled) {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                } else {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }

            const text = toggle.querySelector('[data-dark-mode-text], #darkModeText');
            if (text) {
                text.textContent = enabled ? 'Light Mode' : 'Dark Mode';
            }
        });
    }

    function applyDarkMode(enabled) {
        document.documentElement.classList.toggle('dark-mode', enabled);

        const body = document.body;
        if (body && body.classList) {
            body.classList.toggle('dark-mode', enabled);

            if (enabled && body.style) {
                body.style.setProperty('background', '#0f172a', 'important');
                body.style.setProperty('background-color', '#0f172a', 'important');
                body.style.setProperty('color', '#e2e8f0', 'important');
            }

            if (!enabled && body.style) {
                body.style.removeProperty('background');
                body.style.removeProperty('background-color');
                body.style.removeProperty('color');
            }
        }

        if (enabled) {
            injectDarkModeStyles();
        } else {
            removeDarkModeStyles();
        }

        syncToggleUi(enabled);
    }

    function toggleDarkMode(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const nextState = !readDarkModePreference();
        writeDarkModePreference(nextState);
        applyDarkMode(nextState);

        window.dispatchEvent(new CustomEvent('darkModeChanged', {
            detail: { enabled: nextState }
        }));
    }

    function bindToggleHandlers(root) {
        const scope = root || document;
        scope.querySelectorAll(TOGGLE_SELECTOR).forEach(function (toggle) {
            if (toggle.dataset.darkModeBound === 'true') {
                return;
            }

            toggle.dataset.darkModeBound = 'true';
            toggle.addEventListener('click', toggleDarkMode);
        });
    }

    function initializeDarkMode() {
        applyDarkMode(readDarkModePreference());
        bindToggleHandlers(document);
    }

    window.addEventListener('storage', function (event) {
        if (event.key === DARK_MODE_KEY || event.key === THEME_KEY) {
            applyDarkMode(readDarkModePreference());
        }
    });

    window.addEventListener('darkModeChanged', function (event) {
        if (event.detail && typeof event.detail.enabled === 'boolean') {
            applyDarkMode(event.detail.enabled);
        } else {
            applyDarkMode(readDarkModePreference());
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeDarkMode);
    } else {
        initializeDarkMode();
    }

    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    bindToggleHandlers(document);
                }
            });
        });

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true,
        });
    }
})();
