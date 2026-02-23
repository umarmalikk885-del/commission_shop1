<style>
    /* Global Dark Mode Styles: shared palette and component consistency */
    html.dark-mode,
    body.dark-mode {
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
    }

    html.dark-mode,
    html.dark-mode body,
    body.dark-mode {
        background: var(--dm-bg) !important;
        background-color: var(--dm-bg) !important;
        color: var(--dm-text) !important;
    }

    body.dark-mode .main,
    body.dark-mode .content,
    body.dark-mode .container {
        background: transparent !important;
        color: var(--dm-text) !important;
    }

    /* Sidebar: keep the same dark look on every page */
    body.dark-mode .sidebar {
        background: linear-gradient(180deg, var(--dm-sidebar-start) 0%, var(--dm-sidebar-end) 100%) !important;
        color: var(--dm-text) !important;
    }

    body.dark-mode .sidebar h2 {
        background: linear-gradient(135deg, var(--dm-sidebar-end) 0%, var(--dm-sidebar-start) 100%) !important;
        color: var(--dm-text) !important;
        border-bottom-color: rgba(255, 255, 255, 0.15) !important;
    }

    body.dark-mode .sidebar ul li a {
        color: #cbd5e1 !important;
    }

    body.dark-mode .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.15) !important;
    }

    body.dark-mode .sidebar ul li a.active {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.3) 0%, rgba(96, 165, 250, 0.2) 100%) !important;
        border-left-color: #60a5fa !important;
        color: #ffffff !important;
    }

    /* Shared surfaces */
    body.dark-mode .topbar,
    body.dark-mode .header,
    body.dark-mode .card,
    body.dark-mode .mandi-card,
    body.dark-mode .summary-card,
    body.dark-mode .settings-card,
    body.dark-mode .role-management-card,
    body.dark-mode .info-box,
    body.dark-mode .search-section,
    body.dark-mode .results-section,
    body.dark-mode .tabs-nav,
    body.dark-mode .nav-footer,
    body.dark-mode .expense-panel,
    body.dark-mode .modal-content {
        background: var(--dm-surface) !important;
        border-color: var(--dm-border) !important;
        color: var(--dm-text) !important;
    }

    body.dark-mode .topbar,
    body.dark-mode .header {
        border-bottom-color: var(--dm-border) !important;
    }

    /* Headings and body text */
    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode h3,
    body.dark-mode h4,
    body.dark-mode h5,
    body.dark-mode h6,
    body.dark-mode label,
    body.dark-mode p,
    body.dark-mode li,
    body.dark-mode dt,
    body.dark-mode dd {
        color: var(--dm-text) !important;
    }

    body.dark-mode .text-muted,
    body.dark-mode small,
    body.dark-mode .muted {
        color: var(--dm-muted) !important;
    }

    /* Tables */
    body.dark-mode table,
    body.dark-mode .table {
        color: var(--dm-text) !important;
    }

    body.dark-mode table th,
    body.dark-mode .table th {
        background: var(--dm-surface-alt) !important;
        color: var(--dm-muted) !important;
        border-bottom-color: var(--dm-border) !important;
    }

    body.dark-mode table td,
    body.dark-mode .table td {
        color: #cbd5e1 !important;
        border-bottom-color: var(--dm-border) !important;
    }

    body.dark-mode table tbody tr:hover,
    body.dark-mode .table tbody tr:hover {
        background-color: rgba(148, 163, 184, 0.08) !important;
    }

    /* Forms */
    body.dark-mode .form-group label,
    body.dark-mode label {
        color: var(--dm-text) !important;
    }

    body.dark-mode .form-control,
    body.dark-mode input[type="text"],
    body.dark-mode input[type="email"],
    body.dark-mode input[type="password"],
    body.dark-mode input[type="number"],
    body.dark-mode input[type="date"],
    body.dark-mode input[type="search"],
    body.dark-mode select,
    body.dark-mode textarea {
        background: var(--dm-bg) !important;
        border: 1px solid var(--dm-border) !important;
        color: var(--dm-text) !important;
    }

    body.dark-mode .form-control:focus,
    body.dark-mode input:focus,
    body.dark-mode select:focus,
    body.dark-mode textarea:focus {
        border-color: #60a5fa !important;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15) !important;
        outline: none !important;
    }

    body.dark-mode select option {
        background: var(--dm-surface) !important;
        color: var(--dm-text) !important;
    }

    body.dark-mode input::placeholder,
    body.dark-mode textarea::placeholder {
        color: #64748b !important;
        opacity: 1 !important;
    }

    /* Links and controls */
    body.dark-mode a:not(.btn):not(.sidebar a) {
        color: var(--dm-link) !important;
    }

    body.dark-mode a:not(.btn):not(.sidebar a):hover {
        color: var(--dm-link-hover) !important;
    }

    body.dark-mode .nav-btn {
        color: #cbd5e1 !important;
        background: #334155 !important;
    }

    body.dark-mode .nav-btn:hover {
        background: #475569 !important;
        color: #ffffff !important;
    }

    /* User dropdown consistency */
    body.dark-mode .user-role-display,
    body.dark-mode #userDropdownTrigger {
        color: var(--dm-text) !important;
    }

    body.dark-mode #userDropdownTrigger:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-mode #userDropdownMenu {
        background: var(--dm-surface) !important;
        border-color: var(--dm-border) !important;
    }

    body.dark-mode #userDropdownMenu button,
    body.dark-mode #userDropdownMenu a {
        color: var(--dm-text) !important;
    }

    body.dark-mode #userDropdownMenu button:hover,
    body.dark-mode #userDropdownMenu a:hover {
        background-color: #334155 !important;
    }

    /* Inline style harmonization for legacy pages */
    body.dark-mode [style*="color: #000"],
    body.dark-mode [style*="color:#000"],
    body.dark-mode [style*="color: #111"],
    body.dark-mode [style*="color:#111"],
    body.dark-mode [style*="color: #333"],
    body.dark-mode [style*="color:#333"],
    body.dark-mode [style*="color: #374151"],
    body.dark-mode [style*="color:#374151"],
    body.dark-mode [style*="color: #475569"],
    body.dark-mode [style*="color:#475569"],
    body.dark-mode [style*="color: #4b5563"],
    body.dark-mode [style*="color:#4b5563"],
    body.dark-mode [style*="color: #555"],
    body.dark-mode [style*="color:#555"],
    body.dark-mode [style*="color: #6b7280"],
    body.dark-mode [style*="color:#6b7280"] {
        color: var(--dm-text) !important;
    }

    body.dark-mode [style*="color: #64748b"],
    body.dark-mode [style*="color:#64748b"],
    body.dark-mode [style*="color: #94a3b8"],
    body.dark-mode [style*="color:#94a3b8"] {
        color: var(--dm-muted) !important;
    }

    body.dark-mode [style*="background: #fff"],
    body.dark-mode [style*="background:#fff"],
    body.dark-mode [style*="background: white"],
    body.dark-mode [style*="background:white"],
    body.dark-mode [style*="background-color: #fff"],
    body.dark-mode [style*="background-color:#fff"],
    body.dark-mode [style*="background-color: white"],
    body.dark-mode [style*="background-color:white"] {
        background: var(--dm-surface) !important;
        background-color: var(--dm-surface) !important;
    }

    body.dark-mode [style*="background: #f8fafc"],
    body.dark-mode [style*="background:#f8fafc"],
    body.dark-mode [style*="background-color: #f8fafc"],
    body.dark-mode [style*="background-color:#f8fafc"],
    body.dark-mode [style*="background: #f9fafb"],
    body.dark-mode [style*="background:#f9fafb"],
    body.dark-mode [style*="background: #f4f6f9"],
    body.dark-mode [style*="background:#f4f6f9"],
    body.dark-mode [style*="background: #f1f5f9"],
    body.dark-mode [style*="background:#f1f5f9"] {
        background: var(--dm-bg) !important;
        background-color: var(--dm-bg) !important;
    }
</style>
