<script>
    (function() {
        const DARK_MODE_KEY = 'darkMode';
        const THEME_KEY = 'theme';

        function prefersDarkMode() {
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
                // Ignore storage errors and keep default (light mode).
            }

            return false;
        }

        const enabled = prefersDarkMode();
        document.documentElement.classList.toggle('dark-mode', enabled);

        function applyBodyClass() {
            if (document.body && document.body.classList) {
                document.body.classList.toggle('dark-mode', enabled);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyBodyClass, { once: true });
        } else {
            applyBodyClass();
        }
    })();
</script>

<style>
    :root {
        --spacing-sm: 0.5rem;
        --spacing-md: 1rem;
        --spacing-lg: 1.5rem;
        --radius-md: 0.5rem;

        /* Core theme palette */
        --bg-color: #f8fafc;
        --card-bg: #ffffff;
        --border-color: #e2e8f0;
        --text-color: #0f172a;
        --muted-text-color: #64748b;

        --primary-color: #6366f1;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --secondary-color: #1e293b;

        /* Gradients and effects */
        --primary-gradient: linear-gradient(135deg, #0ea5e9 0%, #6366f1 50%, #ec4899 100%);
        --secondary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
        --accent-gradient: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
        --card-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.12), 0 4px 6px -2px rgba(15, 23, 42, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        margin: 0;
    }

    /* Cards */
    .card {
        background: var(--card-bg);
        border-radius: var(--radius-md);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
        border: 1px solid var(--border-color);
    }

    @media (min-width: 768px) {
        .card {
            padding: var(--spacing-lg);
        }
    }

    /* Form Elements */
    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.375rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        gap: 0.5rem;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-primary { background: var(--primary-color); color: #fff; }
    .btn-success { background: var(--success-color); color: #fff; }
    .btn-danger { background: var(--danger-color); color: #fff; }
    .btn-secondary { background: #6b7280; color: #fff; }

    /* Navigation/Action Icon Buttons */
    .nav-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        background: #f1f5f9;
        color: var(--primary-color);
        transform: scale(1.1);
    }

    /* Tables */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: var(--radius-md);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
        font-size: 0.95rem;
    }

    th {
        background: #f9fafb;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    th, td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    [dir="rtl"] th, [dir="rtl"] td {
        text-align: right;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .urdu-text {
        font-family: 'Noto Nastaliq Urdu', serif;
        line-height: 2;
    }

    /* Dark Mode Overrides */
    .dark-mode {
        --bg-color: #0f172a;
        --text-color: #e2e8f0;
        --card-bg: #1e293b;
        --border-color: #334155;
    }

    .dark-mode .form-control {
        background: #1e293b;
        border-color: #4b5563;
        color: #e2e8f0;
    }

    .dark-mode th {
        background: #111827;
        color: #9ca3af;
    }

    .dark-mode th, .dark-mode td {
        border-color: #334155;
    }
    
    /* Status Badges */
    .status-active {
        color: #10b981;
        font-weight: 600;
    }

    .status-blocked {
        color: #ef4444;
        font-weight: 600;
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1000;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: block;
        }
    }

    .main { 
        padding: 24px; 
        margin-left: 260px;
        width: calc(100% - 260px);
        min-height: 100vh;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    [dir="rtl"] .main {
        margin-left: 0;
        margin-right: 260px;
    }

    @media (max-width: 768px) {
        .main {
            margin: 0 !important;
            width: 100% !important;
            padding: 15px;
            padding-top: 60px; /* Space for mobile menu button */
        }
    }

    /* Topbar */
    .topbar {
        background: var(--card-bg);
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
    }

    .topbar h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Mandi Card */
    .mandi-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--bg-color);
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-mandi {
        padding: 10px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: white;
        transition: var(--transition);
    }
    
    .btn-mandi:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Mandi Modal */
    .mandi-modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        background: var(--card-bg);
    }

    .mandi-modal-header {
        border-bottom: 2px solid var(--bg-color);
        background: #f8fafc;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        padding: 1rem 1rem;
    }

    .mandi-modal-footer {
        border-top: 2px solid var(--bg-color);
        background: #f8fafc;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        padding: 15px 24px;
    }

    .dark-mode .mandi-modal-header,
    .dark-mode .mandi-modal-footer {
        background: #0f172a; /* Darker background for header/footer */
        border-color: #334155;
    }
    
    .dark-mode .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Mandi Form Elements */
    .mandi-label {
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .dark-mode .mandi-label {
        color: #94a3b8;
    }

    .mandi-input {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 10px 14px;
        background-color: #fff;
        color: #1e293b;
    }
    .mandi-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .dark-mode .mandi-input {
        background-color: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
    }
    .dark-mode .mandi-input:focus {
        border-color: #818cf8;
    }

    /* Badge Code Style */
    .badge-code {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
        font-size: 0.9em;
    }

    .dark-mode .badge-code {
        background: #334155;
        color: #e2e8f0;
    }

    /* Summary Boxes */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .summary-box {
        padding: 20px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        text-align: center;
        transition: var(--transition);
    }

    .summary-box .label { font-size: 0.8rem; color: #64748b; margin-bottom: 5px; font-weight: 600; text-transform: uppercase; }
    .summary-box .value { font-size: 1.4rem; font-weight: 800; color: #0f172a; }

    /* Mandi Table Styling */
    .mandi-table-container, .table-responsive { 
        border-radius: 12px; 
        overflow-x: auto; 
        border: 1px solid var(--border-color); 
        background: var(--card-bg);
    }

    .mandi-table, table { 
        width: 100%; 
        border-collapse: collapse; 
        min-width: 600px;
    }

    .mandi-table th, table th { 
        background: #f8fafc; 
        padding: 14px 16px; 
        text-align: inherit; 
        font-size: 0.85rem; 
        font-weight: 700; 
        color: #475569; 
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mandi-table td, table td { 
        padding: 14px 16px; 
        border-bottom: 1px solid #f1f5f9; 
        font-size: 0.95rem; 
        color: #334155;
        vertical-align: middle;
    }

    .mandi-table tr:hover, table tr:hover {
        background-color: #f8fafc;
    }

    /* Dark Mode Overrides */
    .dark-mode {
        --bg-color: #0f172a;
        --text-color: #e2e8f0;
        --card-bg: #1e293b;
        --border-color: #334155;
    }

    .dark-mode .main {
        background-color: var(--bg-color);
    }

    .dark-mode .nav-btn {
        color: #94a3b8;
    }
    
    .dark-mode .nav-btn:hover {
        background: #334155;
        color: #60a5fa; /* lighter blue */
    }

    .dark-mode .form-control {
        background: #1e293b;
        border-color: #4b5563;
        color: #e2e8f0;
    }

    .dark-mode .summary-box {
        background: #1e293b;
        border-color: #334155;
    }
    
    .dark-mode .summary-box .label { color: #94a3b8; }
    .dark-mode .summary-box .value { color: #f1f5f9; }

    .dark-mode .mandi-table th, .dark-mode table th {
        background: #111827;
        color: #94a3b8;
        border-bottom-color: #374151;
    }

    .dark-mode .mandi-table td, .dark-mode table td {
        border-bottom-color: #334155;
        color: #cbd5e1;
    }

    .dark-mode .mandi-table tr:hover, .dark-mode table tr:hover {
        background-color: #1e293b; /* or slightly lighter than card bg */
    }
    
    /* Specific Summary Box Colors in Dark Mode - optional override if needed */
    
    /* Status Badges */
    }

    .topbar input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    @media (max-width: 576px) {
        .topbar {
            flex-direction: column;
            gap: 10px;
            align-items: stretch;
            padding: 10px;
        }
        
        .topbar input {
            width: 100%;
        }
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--text-color);
    }

    /* Pills/Badges */
    .pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
    }
    
    .pill-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .pill-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .dark-mode .pill-success {
        background: #065f46;
        color: #ecfdf5;
    }
    
    .dark-mode .pill-danger {
        background: #7f1d1d;
        color: #fecaca;
    }

    /* Alerts */
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: var(--radius-md);
        border: 1px solid transparent;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .alert-error, .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .dark-mode .alert-success {
        background-color: #064e3b;
        color: #ecfdf5;
        border-color: #059669;
    }

    .dark-mode .alert-error, .dark-mode .alert-danger {
        background-color: #7f1d1d;
        color: #fecaca;
        border-color: #991b1b;
    }

    /* Utility Classes */
    .d-flex { display: flex; }
    .flex-wrap { flex-wrap: wrap; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
    .mb-3 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mt-3 { margin-top: 0.75rem; }
    .mt-4 { margin-top: 1rem; }
    .w-full { width: 100%; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    [dir="rtl"] .text-right { text-align: left; }
    
    /* Grid System */
    .grid-responsive {
        display: grid;
        gap: 1rem;
        grid-template-columns: 1fr;
    }
    
    @media (min-width: 640px) {
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (min-width: 768px) {
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
    }

    /* Tabs Navigation */
    .tabs-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        background: var(--card-bg);
        padding: 1rem;
        border-radius: var(--radius-md);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
    }

    .tab-link {
        padding: 0.5rem 1rem;
        background: var(--bg-color);
        color: var(--text-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-link:hover {
        background: #e5e7eb;
    }

    .tab-link.active {
        background: var(--primary-color);
        color: #fff;
        border-color: var(--primary-color);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Search & Results Sections */
    .search-section, .results-section {
        background: var(--card-bg);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
    }

    .search-section h3, .results-section h3 {
        margin: 0 0 1rem 0;
        color: var(--primary-color);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.75rem;
    }

    /* Summary Cards */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem;
        border-radius: var(--radius-md);
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .summary-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .summary-card.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .summary-card.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .summary-card.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .summary-card.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .summary-card .label { font-size: 0.75rem; opacity: 0.9; margin-bottom: 0.25rem; }
    .summary-card .value { font-size: 1.5rem; font-weight: 700; }

    /* Badge Variants (Extending Pill) */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }
    
    .badge-primary { background: #dbeafe; color: #1e40af; }
    .badge-secondary { background: #f3f4f6; color: #374151; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fef3c7; color: #92400e; }

    /* Print Styles */
    .print-header { display: none; }
    
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        
        .no-print, .sidebar, .topbar, .tabs-nav, .search-section form {
            display: none !important;
        }
        
        .main, .container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .card, .search-section, .results-section {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
        
        .print-header { 
            display: block !important; 
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .print-header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .print-header p { margin: 0; color: #000; }
        
        .table-responsive { overflow: visible !important; }
    }

    /* Dark Mode Extensions */
    .dark-mode .tabs-nav { background: var(--card-bg); border-color: var(--border-color); }
    .dark-mode .tab-link { background: var(--bg-color); color: var(--text-color); border-color: var(--border-color); }
    .dark-mode .tab-link:hover { background: #374151; }
    .dark-mode .search-section, .dark-mode .results-section { background: var(--card-bg); border-color: var(--border-color); }
    .dark-mode .badge-primary { background: #1e3a8a; color: #dbeafe; }
    .dark-mode .badge-secondary { background: #374151; color: #f3f4f6; }
    .dark-mode .badge-success { background: #064e3b; color: #ecfdf5; }
    .dark-mode .badge-danger { background: #7f1d1d; color: #fecaca; }
    .dark-mode .badge-warning { background: #78350f; color: #fef3c7; }

    @media (max-width: 768px) {
        .tabs-nav { flex-direction: column; }
        .tab-link { justify-content: center; }
    }
</style>

@include('components.main-content-spacing')

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('mobile-open');
            
            // Create overlay if it doesn't exist
            let overlay = document.getElementById('sidebar-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'sidebar-overlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.right = '0';
                overlay.style.bottom = '0';
                overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
                overlay.style.zIndex = '999';
                overlay.style.opacity = '0';
                overlay.style.transition = 'opacity 0.3s';
                overlay.onclick = toggleSidebar;
                document.body.appendChild(overlay);
                
                // Trigger reflow
                void overlay.offsetWidth;
                overlay.style.opacity = '1';
            } else {
                if (sidebar.classList.contains('mobile-open')) {
                    overlay.style.display = 'block';
                    setTimeout(() => overlay.style.opacity = '1', 0);
                } else {
                    overlay.style.opacity = '0';
                    setTimeout(() => overlay.style.display = 'none', 300);
                }
            }
        }
    }
    
    // Auto-init sidebar check
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure overlay is removed on page load (if stuck)
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) overlay.style.display = 'none';
    });
</script>
