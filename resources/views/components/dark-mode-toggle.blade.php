<!-- Global Dark Mode Toggle Button -->
<div class="sidebar-dark-mode-toggle">
    <button class="dark-mode-toggle-btn" type="button" title="Toggle Dark Mode" aria-label="Toggle Dark Mode" aria-pressed="false" data-dark-mode-toggle="sidebar">
        <span class="toggle-icon toggle-icon-sun"><i class="fa fa-sun"></i></span>
        <span class="toggle-icon toggle-icon-moon"><i class="fa fa-moon"></i></span>
        <span class="toggle-knob"></span>
    </button>
</div>

<style>
    .sidebar-dark-mode-toggle {
        padding: 14px 20px;
        border-top: 1px solid rgba(148, 163, 184, 0.3);
        margin-top: auto;
        flex-shrink: 0;
    }

    .dark-mode-toggle-btn {
        position: relative;
        width: 64px;
        height: 32px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        background: #f97316; /* light mode: orange */
        padding: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.4);
        transition: background 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }

    .toggle-knob {
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 999px;
        background: #f9fafb;
        top: 50%;
        left: 4px;
        transform: translateY(-50%);
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.35);
        transition: left 0.25s ease, background 0.25s ease, box-shadow 0.25s ease;
        z-index: 1;
    }

    .toggle-icon {
        position: absolute;
        font-size: 14px;
        color: #f9fafb;
        z-index: 0;
        opacity: 0.35;
        transition: opacity 0.25s ease;
    }

    .toggle-icon-sun {
        left: 10px;
    }

    .toggle-icon-moon {
        right: 10px;
    }

    /* Light mode state (default) */
    body:not(.dark-mode) .dark-mode-toggle-btn {
        background: #f97316;
        border-color: rgba(248, 250, 252, 0.7);
    }

    body:not(.dark-mode) .dark-mode-toggle-btn .toggle-knob {
        left: 4px;
        background: #f9fafb;
    }

    body:not(.dark-mode) .dark-mode-toggle-btn .toggle-icon-sun {
        opacity: 1;
    }

    /* Dark mode state */
    body.dark-mode .dark-mode-toggle-btn {
        background: #020617;
        border-color: rgba(148, 163, 184, 0.9);
    }

    body.dark-mode .dark-mode-toggle-btn .toggle-knob {
        left: calc(100% - 4px - 24px);
        background: #e5e7eb;
    }

    body.dark-mode .dark-mode-toggle-btn .toggle-icon-moon {
        opacity: 1;
    }

    .dark-mode-toggle-btn:hover .toggle-knob {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.6);
    }
</style>
