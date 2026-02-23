<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.manage_users') }} - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('components.urdu-input-support')
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
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #f4f6f9; 
        }

        /* Dark mode override - must come after body rule to override it */
        body.dark-mode {
            background: #0f172a !important;
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }

        /* Main content area (sidebar spacing handled by main-content-spacing component) */
        .main { 
            padding: 20px; 
        }
        .card { 
            background: #fff; 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
        }
        .card h3 {
            margin-top: 0;
            color: #111827;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .btn { 
            padding: 10px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            transition: all 0.2s;
        }
        .btn-primary { 
            background: #1e88e5; 
            color: white; 
        }
        .btn-primary:hover {
            background: #1565c0;
        }
        .btn-danger { 
            background: #ef4444; 
            color: white; 
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-success { 
            background: #10b981; 
            color: white; 
        }
        .btn-success:hover {
            background: #059669;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table th, .table td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #e5e7eb; 
        }
        .table th { 
            background: #f9fafb; 
            font-weight: 600; 
            color: #111827;
        }
        .table td {
            color: #374151;
        }
        .badge { 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 12px; 
        }
        .badge-success { 
            background: #10b981; 
            color: white; 
        }
        .badge-warning { 
            background: #f59e0b; 
            color: white; 
        }
        .badge-info { 
            background: #3b82f6; 
            color: white; 
        }
        .form-group { 
            margin-bottom: 1.25rem; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 0.5rem; 
            font-weight: 600; 
            color: #111827;
        }
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 0.625rem 0.75rem; 
            border: 1px solid #d1d5db; 
            border-radius: 6px; 
            background: #fff;
            color: #111827;
            font-size: 1rem;
            line-height: 1.5;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        .form-row { 
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }
        .form-row > .form-group {
            flex: 0 0 auto;
            width: 100%;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        @media (min-width: 576px) {
            .form-row > .form-group {
                flex: 0 0 auto;
                width: 50%;
            }
        }
        .checkbox-group { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 10px; 
            margin-top: 10px; 
        }
        .checkbox-group label { 
            font-weight: normal; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            color: #374151;
        }
        .alert { 
            padding: 12px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
        }
        .alert-success { 
            background: #d1fae5; 
            color: #065f46; 
            border: 1px solid #10b981; 
        }
        .alert-error { 
            background: #fee2e2; 
            color: #991b1b; 
            border: 1px solid #ef4444; 
        }
        .modal { 
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.5); 
        }
        .modal-content { 
            background: white; 
            margin: 50px auto; 
            padding: 20px; 
            border-radius: 8px; 
            width: 90%; 
            max-width: 600px; 
        }
        .modal-content h3 {
            margin-top: 0;
            color: #111827;
        }
        h2 {
            color: #111827;
            margin-bottom: 20px;
        }

        /* RTL Support - text/layout only (sidebar stays fixed) */
        [dir="rtl"] .table th,
        [dir="rtl"] .table td {
            text-align: right;
        }

        [dir="rtl"] .form-row {
            direction: rtl;
        }


        /* Additional responsive styles */
        @media (max-width: 575.98px) {
            .form-row > .form-group {
                width: 100%;
                margin-bottom: 1rem;
            }
            .main {
                padding: 15px;
            }
            .card {
                padding: 15px;
            }
        }
        
        /* Ensure form controls don't overlap */
        .form-control {
            display: block;
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #111827;
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            color: #111827;
            background-color: #fff;
            border-color: #1e88e5;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        
        /* Dark mode support for muted text */
        .text-muted {
            color: #888;
        }
        
        body.dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        
        /* Dark mode styles for table - Maximum visibility with bright colors */
        body.dark-mode .table {
            color: #f1f5f9 !important;
        }
        
        body.dark-mode .table th {
            background: #0f172a !important;
            color: #f8fafc !important;
            border-bottom-color: #334155 !important;
        }
        
        body.dark-mode .table td {
            color: #e2e8f0 !important;
            border-bottom-color: #334155 !important;
        }

        /* Force all text in table cells to be visible - Bright colors */
        body.dark-mode .table td,
        body.dark-mode .table td *:not(.badge):not(.btn),
        body.dark-mode .table td span:not(.badge),
        body.dark-mode .table td div:not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }

        /* Name column - Brightest white */
        body.dark-mode .table td:first-child,
        body.dark-mode .table td:first-child *:not(.badge):not(.btn),
        body.dark-mode .table td:first-child strong,
        body.dark-mode .table td:first-child b {
            color: #f8fafc !important;
        }

        /* Email column - Bright */
        body.dark-mode .table td:nth-child(2),
        body.dark-mode .table td:nth-child(2) *:not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }

        /* Permissions column - Bright */
        body.dark-mode .table td:nth-child(4),
        body.dark-mode .table td:nth-child(4) *:not(.badge):not(.btn),
        body.dark-mode .table td:nth-child(4) span:not(.badge),
        body.dark-mode .table td:nth-child(4) div:not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }
        
        body.dark-mode .table tbody tr:hover {
            background-color: #1e293b !important;
        }

        body.dark-mode .table tbody tr:hover td,
        body.dark-mode .table tbody tr:hover td *:not(.badge):not(.btn) {
            color: #f1f5f9 !important;
        }
        
        body.dark-mode .table td strong,
        body.dark-mode .table td b {
            color: #f8fafc !important;
            font-weight: 600 !important;
        }
        
        body.dark-mode .table .badge {
            color: #ffffff !important;
        }
        
        body.dark-mode .card {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            color: #e2e8f0 !important;
        }
        
        body.dark-mode .card h3 {
            color: #e2e8f0 !important;
            border-bottom-color: #475569 !important;
        }

        /* Force all text in cards to be visible */
        body.dark-mode .card,
        body.dark-mode .card *,
        body.dark-mode .card p,
        body.dark-mode .card span,
        body.dark-mode .card div {
            color: #e2e8f0 !important;
        }

        /* Comprehensive Dark Mode Styles for Users Page */
        body.dark-mode h2 {
            color: #e2e8f0 !important;
        }

        body.dark-mode .form-group label {
            color: #e2e8f0 !important;
        }

        body.dark-mode .form-group input,
        body.dark-mode .form-group select,
        body.dark-mode .form-control {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .form-group input:focus,
        body.dark-mode .form-group select:focus,
        body.dark-mode .form-control:focus {
            background: #0f172a !important;
            border-color: #60a5fa !important;
            color: #e2e8f0 !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1) !important;
        }

        body.dark-mode .form-group select option {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .table tbody tr {
            background: transparent !important;
        }

        body.dark-mode .table tbody tr:hover {
            background-color: #1e293b !important;
        }

        body.dark-mode .table td strong {
            color: #e2e8f0 !important;
        }

        body.dark-mode .badge {
            color: white !important;
            font-weight: 500 !important;
        }

        body.dark-mode .badge-success {
            background: #059669 !important;
            color: white !important;
        }

        body.dark-mode .badge-warning {
            background: #d97706 !important;
            color: white !important;
        }

        body.dark-mode .badge-info {
            background: #0284c7 !important;
            color: white !important;
        }

        body.dark-mode .btn-primary {
            background: #1e88e5 !important;
            color: white !important;
        }

        body.dark-mode .btn-primary:hover {
            background: #1565c0 !important;
        }

        body.dark-mode .btn-danger {
            background: #ef4444 !important;
            color: white !important;
        }

        body.dark-mode .btn-danger:hover {
            background: #dc2626 !important;
        }

        body.dark-mode .btn-success {
            background: #10b981 !important;
            color: white !important;
        }

        body.dark-mode .btn-success:hover {
            background: #059669 !important;
        }

        body.dark-mode .modal {
            background: rgba(0, 0, 0, 0.7) !important;
        }

        body.dark-mode .modal-content {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .modal-content h3 {
            color: #e2e8f0 !important;
        }

        body.dark-mode .alert-success {
            background: #064e3b !important;
            color: #6ee7b7 !important;
            border-color: #10b981 !important;
        }

        body.dark-mode .alert-error {
            background: #7f1d1d !important;
            color: #fca5a5 !important;
            border-color: #ef4444 !important;
        }

        body.dark-mode .checkbox-group label {
            color: #cbd5e1 !important;
        }

        body.dark-mode input[type="checkbox"] {
            accent-color: #60a5fa !important;
        }

        /* Override inline styles */
        body.dark-mode [style*="color: #111827"],
        body.dark-mode [style*="color:#111827"] {
            color: #e2e8f0 !important;
        }

        body.dark-mode [style*="color: #374151"],
        body.dark-mode [style*="color:#374151"] {
            color: #cbd5e1 !important;
        }

        body.dark-mode [style*="background: #fff"],
        body.dark-mode [style*="background:white"],
        body.dark-mode [style*="background-color: #fff"],
        body.dark-mode [style*="background-color:white"] {
            background: #1e293b !important;
            background-color: #1e293b !important;
        }

        body.dark-mode [style*="background: #f9fafb"],
        body.dark-mode [style*="background-color: #f9fafb"] {
            background: #0f172a !important;
            background-color: #0f172a !important;
        }

        /* Ensure all text in table cells is visible - Most aggressive */
        body.dark-mode .table td,
        body.dark-mode .table td *,
        body.dark-mode .table td span,
        body.dark-mode .table td div,
        body.dark-mode .table td p,
        body.dark-mode .table td a:not(.btn) {
            color: #cbd5e1 !important;
        }

        body.dark-mode .table td strong,
        body.dark-mode .table td b {
            color: #e2e8f0 !important;
        }

        /* Force email and name columns to be visible */
        body.dark-mode .table tbody tr td:first-child,
        body.dark-mode .table tbody tr td:first-child *,
        body.dark-mode .table tbody tr td:nth-child(2),
        body.dark-mode .table tbody tr td:nth-child(2) * {
            color: #e2e8f0 !important;
        }

        /* Permission badges container - ensure text is visible */
        body.dark-mode [style*="display: flex"] {
            color: #cbd5e1 !important;
        }

        body.dark-mode [style*="display: flex"] *:not(.badge):not(.btn) {
            color: #cbd5e1 !important;
        }

        /* Force all text elements in main content */
        body.dark-mode .main,
        body.dark-mode .main *:not(.badge):not(.btn):not(a.btn),
        body.dark-mode .main p,
        body.dark-mode .main span:not(.badge),
        body.dark-mode .main div:not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }

        /* Override Bootstrap text colors */
        body.dark-mode .text-muted,
        body.dark-mode .text-dark,
        body.dark-mode .text-black {
            color: #94a3b8 !important;
        }

        /* Override any remaining dark text - but exclude badges and buttons */
        body.dark-mode *:not(.badge):not(.btn):not(a.btn):not(input):not(select):not(textarea) {
            color: #e2e8f0 !important;
        }

        /* But preserve badge and button colors */
        body.dark-mode .badge,
        body.dark-mode .badge *,
        body.dark-mode .btn,
        body.dark-mode .btn * {
            color: white !important;
        }

        /* Preserve link colors */
        body.dark-mode a:not(.btn) {
            color: #60a5fa !important;
        }

        body.dark-mode a:not(.btn):hover {
            color: #93c5fd !important;
        }

        /* Override Bootstrap table default colors */
        body.dark-mode table *:not(.badge):not(.btn) {
            color: #cbd5e1 !important;
        }

        body.dark-mode table strong,
        body.dark-mode table b {
            color: #e2e8f0 !important;
        }

        /* Final override - highest specificity for all text */
        html body.dark-mode .table tbody tr td,
        html body.dark-mode .table tbody tr td *:not(.badge):not(.btn) {
            color: #cbd5e1 !important;
        }

        html body.dark-mode .table tbody tr td strong,
        html body.dark-mode .table tbody tr td:first-child,
        html body.dark-mode .table tbody tr td:first-child *:not(.badge):not(.btn),
        html body.dark-mode .table tbody tr td:nth-child(2),
        html body.dark-mode .table tbody tr td:nth-child(2) *:not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }

        /* Comprehensive dark mode for all possible elements */
        body.dark-mode .main h2,
        body.dark-mode .main h3,
        body.dark-mode .main h4 {
            color: #e2e8f0 !important;
        }

        /* Ensure all text in the page is visible */
        body.dark-mode .main,
        body.dark-mode .main *:not(.badge):not(.btn):not(input):not(select):not(textarea):not(a.btn) {
            color: #e2e8f0 !important;
        }

        /* Table specific - ensure all text */
        body.dark-mode table,
        body.dark-mode table *:not(.badge):not(.btn) {
            color: #cbd5e1 !important;
        }

        body.dark-mode table th {
            color: #e2e8f0 !important;
        }

        body.dark-mode table td:first-child,
        body.dark-mode table td:first-child *:not(.badge):not(.btn),
        body.dark-mode table td:nth-child(2),
        body.dark-mode table td:nth-child(2) *:not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }

        /* Form row containers */
        body.dark-mode .form-row,
        body.dark-mode .form-row *:not(input):not(select):not(textarea):not(.badge):not(.btn) {
            color: #e2e8f0 !important;
        }

        /* Ensure icons are visible */
        body.dark-mode .fa,
        body.dark-mode i {
            color: inherit !important;
        }

        /* Links in dark mode */
        body.dark-mode a:not(.btn) {
            color: #60a5fa !important;
        }

        body.dark-mode a:not(.btn):hover {
            color: #93c5fd !important;
        }

        /* Ensure all divs with inline styles get dark mode */
        body.dark-mode div[style*="display: flex"],
        body.dark-mode div[style*="display:flex"] {
            color: #e2e8f0 !important;
        }

        body.dark-mode div[style*="display: flex"] *:not(.badge):not(.btn),
        body.dark-mode div[style*="display:flex"] *:not(.badge):not(.btn) {
            color: #cbd5e1 !important;
        }

        /* SweetAlert2 popup - ensure readable text in both light and dark mode */
        .swal2-popup {
            background: #ffffff !important;
            color: #111827 !important;
        }

        .swal2-title,
        .swal2-html-container {
            color: #111827 !important;
        }

        body.dark-mode .swal2-popup {
            background: #0f172a !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .swal2-title,
        body.dark-mode .swal2-html-container {
            color: #e2e8f0 !important;
        }

        /* Override Bootstrap table classes */
        body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(30, 41, 59, 0.3) !important;
        }

        body.dark-mode .table-hover tbody tr:hover {
            background-color: #1e293b !important;
        }

        /* Ensure placeholder text is visible */
        body.dark-mode ::placeholder,
        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder {
            color: #64748b !important;
            opacity: 1 !important;
        }
    </style>

    <!-- Additional dark mode text override script - Comprehensive -->
    <script>
    (function() {
        function applyDarkModeTextColors() {
            if (!document.body.classList.contains('dark-mode')) return;
            
            // Force text colors on all table cells - Maximum brightness
            const tableCells = document.querySelectorAll('.table tbody td');
            tableCells.forEach(function(cell) {
                // Set cell text color - use brightest colors
                if (cell.cellIndex === 0) {
                    // Name column - brightest
                    cell.style.setProperty('color', '#f8fafc', 'important');
                } else if (cell.cellIndex === 1) {
                    // Email column - bright
                    cell.style.setProperty('color', '#e2e8f0', 'important');
                } else if (cell.cellIndex === 4) {
                    // Permissions column - bright
                    cell.style.setProperty('color', '#e2e8f0', 'important');
                } else {
                    cell.style.setProperty('color', '#e2e8f0', 'important');
                }
                
                // Set all children text colors - Maximum visibility
                const children = cell.querySelectorAll('*:not(.badge):not(.btn):not(i)');
                children.forEach(function(child) {
                    if (child.tagName === 'STRONG' || child.tagName === 'B') {
                        child.style.setProperty('color', '#f8fafc', 'important');
                    } else if (cell.cellIndex === 0) {
                        // Name column children - brightest
                        child.style.setProperty('color', '#f8fafc', 'important');
                    } else if (cell.cellIndex === 1 || cell.cellIndex === 4) {
                        // Email and Permissions columns - bright
                        child.style.setProperty('color', '#e2e8f0', 'important');
                    } else {
                        child.style.setProperty('color', '#e2e8f0', 'important');
                    }
                });
            });
            
            // Force text colors on all main content elements
            const mainContent = document.querySelector('.main');
            if (mainContent) {
                const allTextElements = mainContent.querySelectorAll('*:not(.badge):not(.btn):not(input):not(select):not(textarea):not(a.btn):not(i)');
                allTextElements.forEach(function(el) {
                    if (el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'H4' || el.tagName === 'STRONG' || el.tagName === 'B') {
                        el.style.setProperty('color', '#e2e8f0', 'important');
                    } else if (el.tagName !== 'TABLE' && el.tagName !== 'TD' && el.tagName !== 'TH' && el.tagName !== 'TR') {
                        el.style.setProperty('color', '#e2e8f0', 'important');
                    }
                });
            }
            
            // Force table header text - Brightest
            const tableHeaders = document.querySelectorAll('.table th');
            tableHeaders.forEach(function(th) {
                th.style.setProperty('color', '#f8fafc', 'important');
            });

            // Force all text in permission badges container to be visible
            const permissionContainers = document.querySelectorAll('.table td[style*="display: flex"]');
            permissionContainers.forEach(function(container) {
                container.style.setProperty('color', '#e2e8f0', 'important');
                const children = container.querySelectorAll('*:not(.badge):not(.btn)');
                children.forEach(function(child) {
                    child.style.setProperty('color', '#e2e8f0', 'important');
                });
            });
        }
        
        // Apply on page load with multiple delays
        function initDarkModeText() {
            setTimeout(applyDarkModeTextColors, 50);
            setTimeout(applyDarkModeTextColors, 100);
            setTimeout(applyDarkModeTextColors, 300);
            setTimeout(applyDarkModeTextColors, 500);
            setTimeout(applyDarkModeTextColors, 1000);
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDarkModeText);
        } else {
            initDarkModeText();
        }
        
        // Re-apply when dark mode changes
        window.addEventListener('darkModeChanged', function() {
            setTimeout(applyDarkModeTextColors, 100);
        });
        
        // Watch for DOM changes and re-apply
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function() {
                if (document.body.classList.contains('dark-mode')) {
                    setTimeout(applyDarkModeTextColors, 100);
                }
            });
            
            const mainContent = document.querySelector('.main');
            if (mainContent) {
                observer.observe(mainContent, {
                    childList: true,
                    subtree: true
                });
            }
        }
    })();
    </script>
</head>
<body>
    @php
        // Helper function to translate permission names
        function translatePermission($permissionName) {
            $translations = [
                'view dashboard' => __('messages.permission_view_dashboard'),
                'view sales' => __('messages.permission_view_sales'),
                'manage sales' => __('messages.permission_manage_sales'),
                'view purchases' => __('messages.permission_view_purchases'),
                'manage purchases' => __('messages.permission_manage_purchases'),
                'view vendors' => __('messages.permission_view_vendors'),
                'manage vendors' => __('messages.permission_manage_vendors'),
                'view reports' => __('messages.permission_view_reports'),
                'view stock' => __('messages.permission_view_stock'),
                'manage stock' => __('messages.permission_manage_stock'),
                'view settings' => __('messages.permission_view_settings'),
                'manage settings' => __('messages.permission_manage_settings'),
                'view bank-cash' => __('messages.permission_view_bank_cash'),
                'manage bank-cash' => __('messages.permission_manage_bank_cash'),
                'manage roles' => __('messages.permission_manage_roles'),
                'manage users' => __('messages.permission_manage_users'),
            ];
            return $translations[$permissionName] ?? $permissionName;
        }

        // Helper to translate role display names while keeping internal names intact
        function translateRoleName($roleName) {
            $map = [
                'Super Admin' => __('messages.role_super_admin'),
                'Admin'       => __('messages.role_admin'),
                'User'        => __('messages.role_user'),
                'Operator'    => __('messages.role_operator'),
            ];
            return $map[$roleName] ?? $roleName;
        }
    @endphp
    
    @include('components.sidebar')
    
    <div class="main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>{{ __('messages.manage_users') }}</h2>
            <div style="display: flex; gap: 15px; align-items: center;">
                @include('components.user-role-display')
                <a href="/settings" class="btn btn-primary"><i class="fa fa-arrow-left"></i> {{ __('messages.back_to_settings') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div id="successMessage" class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div id="errorMessage" class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Create New User -->
        <div class="card">
            <h3>{{ __('messages.create_new_user') }}</h3>
            <form action="{{ route('users.create') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">{{ __('messages.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" required autocomplete="name">
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('messages.email') }}</label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">{{ __('messages.password') }}</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required
                            minlength="4"
                            maxlength="10"
                            autocomplete="new-password"
                        >
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('messages.confirm_password') }}</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            required
                            minlength="4"
                            maxlength="10"
                            autocomplete="new-password"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label for="role">{{ __('messages.role') }}</label>
                    <select id="role" name="role" class="form-control" required autocomplete="organization-title">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ translateRoleName($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-success">{{ __('messages.create_user') }}</button>
                </div>
            </form>
        </div>

        <!-- Existing Users -->
        <div class="card">
            <h3>{{ __('messages.existing_users') }}</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.permissions') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge badge-info">{{ translateRoleName($role->name) }}</span>
                            @endforeach
                        </td>
                        <td>
                            @php
                                // Get permissions from role and direct permissions
                                $rolePermissions = $user->roles->flatMap->permissions;
                                $directPermissions = $user->permissions;
                                $userPermissions = $rolePermissions->merge($directPermissions)->unique('id');
                            @endphp
                            @if($userPermissions->count() > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($userPermissions->take(5) as $permission)
                                        <span class="badge badge-success">{{ translatePermission($permission->name) }}</span>
                                    @endforeach
                                    @if($userPermissions->count() > 5)
                                        <span class="badge badge-warning">+{{ $userPermissions->count() - 5 }} {{ __('messages.more') }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">{{ __('messages.no_permissions') }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $currentUser = auth()->user();
                                $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
                                $userRoles = $user->roles->pluck('name')->toArray();
                                $canEdit = $isSuperAdmin || (!in_array('Super Admin', $userRoles) && !in_array('Admin', $userRoles));
                                $canDelete = $canEdit && $user->id !== auth()->id();
                            @endphp
                            @if($canEdit)
                                <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->roles->first()->name ?? '' }}')" class="btn btn-primary">{{ __('messages.edit') }}</button>
                            @endif
                            @if($canDelete)
                                <!-- Delete button removed -->
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>{{ __('messages.edit_user') }}</h3>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editRole">{{ __('messages.role') }}</label>
                    <select name="role" id="editRole" required autocomplete="organization-title">
                        @foreach($roles as $role)
                            @if($role->name !== 'Super Admin')
                                <option value="{{ $role->name }}">{{ translateRoleName($role->name) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">{{ __('messages.update_role') }}</button>
                    <button type="button" onclick="closeEditModal()" class="btn btn-primary">{{ __('messages.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(userId, userName, userEmail, userRole) {
            document.getElementById('editUserForm').action = '/settings/users/' + userId + '/role';
            const roleSelect = document.getElementById('editRole');
            
            // Set the role value, but only if it exists in the options
            if (roleSelect.querySelector('option[value="' + userRole + '"]')) {
                roleSelect.value = userRole;
            } else {
                // If the role is not in the options (e.g., Admin trying to edit Admin user),
                // set to the first available option (should be "User" for Admin)
                if (roleSelect.options.length > 0) {
                    roleSelect.value = roleSelect.options[0].value;
                }
            }
            
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }

    </script>
    
    <!-- Auto-hide success/error messages after 7 seconds -->
    <!-- SweetAlert2 for success / error flash messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            if (successMessage) {
                const msg = successMessage.textContent.trim();
                // Hide the inline bootstrap alert
                successMessage.style.display = 'none';
                if (msg) {
                    Swal.fire({
                        icon: 'success',
                        title: 'کامیابی',
                        text: msg,
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            }

            if (errorMessage) {
                const msg = errorMessage.textContent.trim();
                errorMessage.style.display = 'none';
                if (msg) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خرابی',
                        text: msg
                    });
                }
            }
        });
    </script>
    
    <!-- Global Dark Mode Script -->
    <script src="{{ asset('js/global-dark-mode.js') }}"></script>
</body>
</html>
