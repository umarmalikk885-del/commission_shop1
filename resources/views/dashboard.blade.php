<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ڈیش بورڈ - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')

    <style>
        /* Dark Mode Toggle in Topbar (Dashboard only) */
        .topbar-dark-mode-toggle {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .topbar-dark-mode-btn {
            position: relative;
            width: 70px;
            height: 32px;
            padding: 0;
            border-radius: 999px;
            background: #f97316; /* light track (orange) */
            border: 1px solid #d1d5db;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.25s ease;
        }
        
        /* Knob */
        .topbar-dark-mode-btn::before {
            content: "";
            position: absolute;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #f9fafb;
            box-shadow: 0 3px 6px rgba(15,23,42,0.25);
            left: 4px;
            top: 50%;
            transform: translateY(-50%);
            transition: left 0.25s ease;
        }
        
        /* Icon lives inside the knob (moves with state) */
        .topbar-dark-mode-btn i {
            position: absolute;
            top: 50%;
            left: 10px; /* inside left knob in light mode */
            transform: translateY(-50%);
            z-index: 1;
            font-size: 15px;
            color: #facc15 !important; /* sun color in light mode */
            transition: left 0.25s ease, color 0.25s ease;
        }
        
        .topbar-dark-mode-btn span {
            display: none; /* text kept for JS but hidden visually */
        }
        
        .topbar-dark-mode-btn:hover {
            box-shadow: 0 6px 14px rgba(148,163,184,0.5);
            border-color: #9ca3af;
        }
        
        .topbar-dark-mode-btn:active {
            transform: scale(0.97);
            box-shadow: 0 3px 8px rgba(148,163,184,0.6);
        }
        
        /* Dark Mode overrides */
        body.dark-mode .topbar-dark-mode-btn {
            background: #020617; /* dark track */
            border-color: #4b5563;
        }
        
        body.dark-mode .topbar-dark-mode-btn::before {
            left: 40px; /* move knob to right side */
        }
        
        body.dark-mode .topbar-dark-mode-btn i {
            left: 46px; /* icon inside right knob */
            color: #020617 !important; /* dark navy so moon stands out on white knob */
            text-shadow: none;
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 40px auto 32px auto;
        }

        .summary-card {
            background: var(--card-bg);
            padding: 24px 24px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(15,23,42,0.08);
            text-align: left;
            border: 1px solid var(--border-color);
        }

        .summary-card h4 {
            margin: 0 0 12px 0;
            font-size: 15px;
            color: var(--text-muted, #6b7280);
            font-weight: 500;
        }

        .summary-card p {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-color);
        }

        .summary-card.today { border-left: 4px solid #4CAF50; }
        .summary-card.week { border-left: 4px solid #2196F3; }
        .summary-card.monthly { border-left: 4px solid #FF9800; }
        .summary-card.yearly { border-left: 4px solid #9C27B0; }
        .summary-card.total { border-left: 4px solid #F44336; }

        .summary-card.dues {
            border-left: 4px solid #FF5722;
            background: linear-gradient(135deg, var(--card-bg) 0%, rgba(255, 87, 34, 0.05) 100%);
        }
        
        /* Dark Mode overrides */
        body.dark-mode .summary-card.dues {
            background: linear-gradient(135deg, var(--card-bg) 0%, rgba(61, 30, 30, 0.5) 100%);
        }
    </style>
    @include('components.urdu-input-support')
</head>
<body>

@include('components.sidebar')

<!-- Main -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <h2 style="margin: 0;">{{ __('messages.dashboard') }}</h2>
        <div style="display: flex; align-items: center; gap: 16px;">
            <div class="topbar-dark-mode-toggle">
                <button id="globalDarkModeToggle" class="topbar-dark-mode-btn" type="button" title="{{ __('Toggle Dark Mode') }}" aria-label="{{ __('Toggle Dark Mode') }}" aria-pressed="false" data-dark-mode-toggle="dashboard">
                    <i id="darkModeIcon" class="fa fa-moon"></i>
                    <span id="darkModeText">{{ __('Dark Mode') }}</span>
                </button>
            </div>
            @include('components.user-role-display')
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card today">
            <h4>{{ __('messages.today_sales') }}</h4>
            <p>Rs. {{ number_format($todaySales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card week">
            <h4>{{ __('messages.week_sales') }}</h4>
            <p>Rs. {{ number_format($weekSales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card monthly">
            <h4>{{ __('messages.monthly_sales') }}</h4>
            <p>Rs. {{ number_format($monthlySales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card yearly">
            <h4>{{ __('messages.yearly_sales') ?? __('messages.this_year') ?? 'سالانہ سیلز' }}</h4>
            <p>Rs. {{ number_format($yearlySales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card total">
            <h4>{{ __('messages.total_sales') }}</h4>
            <p>Rs. {{ number_format($totalSales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card dues">
            <h4>{{ __('messages.total_dues') ?? 'کل بقایا' }}</h4>
            <p>Rs. {{ number_format($totalDues ?? 0, 2) }}</p>
            <small style="display: block; margin-top: 8px; font-size: 12px; color: #666;" class="dues-breakdown">
                {{ __('messages.purchase') ?? 'خریداری' }}: Rs. {{ number_format($totalPurchaseDues ?? 0, 2) }}
            </small>
        </div>
    </div>

</div>

<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

</body>
</html>
