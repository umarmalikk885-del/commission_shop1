<style>
    /* Modern Attractive Sidebar Styles */
    .sidebar {
        width: 260px;
        height: 100vh;
        background: linear-gradient(180deg, #1e88e5 0%, #1565c0 100%);
        color: #fff;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        transition: transform 0.3s ease, width 0.3s ease;
    }

    /* RTL Support */
    [dir="rtl"] .sidebar {
        left: auto;
        right: 0;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
    }

    .sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        pointer-events: none;
    }

    .sidebar h2 {
        text-align: center;
        padding: 25px 20px;
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
        flex-shrink: 0;
        position: relative;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #64b5f6, transparent);
        border-radius: 2px;
    }

    .sidebar ul {
        list-style: none;
        padding: 15px 0;
        margin: 0;
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
        -webkit-overflow-scrolling: touch;
        min-height: 0;
    }

    /* Custom Scrollbar */
    .sidebar ul::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar ul::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin: 10px 0;
    }

    .sidebar ul::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        transition: background 0.3s ease;
    }

    .sidebar ul::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    .sidebar ul li {
        width: 100%;
        box-sizing: border-box;
        margin: 3px 0;
        padding: 0 12px;
    }

    .sidebar ul li a {
        padding: 14px 18px;
        display: flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.95);
        text-decoration: none;
        width: 100%;
        box-sizing: border-box;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        font-size: 15px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .sidebar ul li a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 0;
        background: #fff;
        border-radius: 0 4px 4px 0;
        transition: height 0.3s ease;
    }

    .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sidebar ul li a:hover::before {
        height: 60%;
    }

    .sidebar ul li a.active {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-left: 4px solid #fff;
        padding-left: 14px;
    }

    .sidebar ul li a.active::before {
        height: 100%;
        width: 4px;
    }

    .sidebar ul li a.active i {
        color: #fff;
        transform: scale(1.1);
    }

    .sidebar i {
        margin-right: 14px;
        font-size: 18px;
        width: 22px;
        text-align: center;
        transition: all 0.3s ease;
        color: rgba(255, 255, 255, 0.9);
    }

    .sidebar ul li a:hover i {
        transform: scale(1.15) rotate(5deg);
        color: #fff;
    }

    /* Dark Mode Styles */
    body.dark-mode .sidebar {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.5) !important;
    }

    body.dark-mode .sidebar h2 {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.15) !important;
    }

    body.dark-mode .sidebar h2::after {
        background: linear-gradient(90deg, transparent, #60a5fa, transparent) !important;
    }

    body.dark-mode .sidebar ul li a {
        color: #cbd5e1 !important;
    }

    body.dark-mode .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.15) !important;
    }

    .logout-link {
        color: #ffcdd2 !important;
        margin-top: 10px;
    }

    .logout-link:hover {
        background: rgba(211, 47, 47, 0.4) !important;
        color: #fff !important;
    }

    body.dark-mode .logout-link {
        color: #f87171 !important;
    }

    body.dark-mode .logout-link:hover {
        background: rgba(153, 27, 27, 0.6) !important;
    }

    body.dark-mode .sidebar ul li a.active {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.3) 0%, rgba(96, 165, 250, 0.2) 100%) !important;
        border-left-color: #60a5fa !important;
        color: #fff !important;
    }

    body.dark-mode .sidebar i {
        color: rgba(203, 213, 225, 0.9) !important;
    }

    body.dark-mode .sidebar ul li a:hover i,
    body.dark-mode .sidebar ul li a.active i {
        color: #60a5fa !important;
    }

    body.dark-mode .sidebar ul::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2) !important;
    }

    body.dark-mode .sidebar ul::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4) !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            width: 260px;
            position: fixed;
            transform: translateX(-100%);
            box-shadow: 4px 0 10px rgba(0,0,0,0.2);
        }

        [dir="rtl"] .sidebar {
            transform: translateX(100%);
            box-shadow: -4px 0 10px rgba(0,0,0,0.2);
        }

        .sidebar.mobile-open {
            transform: translateX(0) !important;
        }

        .sidebar ul {
            max-height: none;
            overflow-y: auto;
        }

        .sidebar ul li a:hover {
            transform: none;
        }

        [dir="rtl"] .sidebar ul li a:hover {
            transform: none;
        }
    }

    /* Animation for menu items */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .sidebar ul li {
        animation: slideIn 0.3s ease forwards;
        opacity: 0;
    }

    .sidebar ul li:nth-child(1) { animation-delay: 0.05s; }
    .sidebar ul li:nth-child(2) { animation-delay: 0.1s; }
    .sidebar ul li:nth-child(3) { animation-delay: 0.15s; }
    .sidebar ul li:nth-child(4) { animation-delay: 0.2s; }
    .sidebar ul li:nth-child(5) { animation-delay: 0.25s; }
    .sidebar ul li:nth-child(6) { animation-delay: 0.3s; }
    .sidebar ul li:nth-child(7) { animation-delay: 0.35s; }
    .sidebar ul li:nth-child(8) { animation-delay: 0.4s; }
    .sidebar ul li:nth-child(9) { animation-delay: 0.45s; }
    .sidebar ul li:nth-child(10) { animation-delay: 0.5s; }
    .sidebar ul li:nth-child(11) { animation-delay: 0.55s; }
    .sidebar ul li:nth-child(12) { animation-delay: 0.6s; }
</style>
