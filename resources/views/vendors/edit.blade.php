<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>وینڈر میں ترمیم - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.global-dark-mode-styles')
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

        /* Sidebar */
        .sidebar {
            width: 230px;
            height: 100vh;
            background: #1e88e5;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar h2 {
            text-align: center;
            padding: 20px 0;
            margin: 0;
            font-size: 20px;
            background: #1565c0;
            flex-shrink: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto;
            overflow-x: hidden;
            flex: 1;
        }

        .sidebar ul li a {
            padding: 14px 20px;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        .sidebar ul li a:hover {
            background: rgba(255,255,255,0.15);
        }

        .sidebar ul li a.active {
            background: rgba(255,255,255,0.25);
            font-weight: bold;
        }

        .sidebar i {
            margin-right: 10px;
        }

        /* Main Content */
        .main {
            margin-left: 230px;
            padding: 20px;
        }
        
        /* Ensure sidebar stays on left in RTL mode */
        [dir="rtl"] .sidebar {
            left: 0;
            right: auto;
        }
        
        [dir="rtl"] .main {
            margin-left: 230px;
            margin-right: 0;
        }

        /* Top Bar */
        .topbar {
            background: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .topbar input {
            padding: 8px;
            width: 250px;
            border-radius: 4px;
            border: 1px solid #ccc;
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

        body.dark-mode .topbar input {
            background: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        body.dark-mode .topbar input::placeholder {
            color: #94a3b8;
        }

        body.dark-mode .card,
        body.dark-mode form {
            background: #1e293b;
            border: 1px solid #334155;
        }

        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea {
            background: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        body.dark-mode h2, body.dark-mode h3 {
            color: #e2e8f0;
        }

        body.dark-mode label {
            color: #e2e8f0;
        }

        body.dark-mode p,
        body.dark-mode div:not(.sidebar):not(.theme-toggle):not(.theme-toggle-slider),
        body.dark-mode span:not(.theme-toggle-slider),
        body.dark-mode td,
        body.dark-mode th {
            color: #e2e8f0;
        }

        body.dark-mode [style*="color: #111"],
        body.dark-mode [style*="color: #333"],
        body.dark-mode [style*="color: #555"] {
            color: #e2e8f0 !important;
        }

        body.dark-mode [style*="color: red"] {
            color: #f87171 !important;
        }

        body.dark-mode a {
            color: #60a5fa;
        }

        body.dark-mode a:hover {
            color: #93c5fd;
        }

        body.dark-mode .btn {
            color: #fff;
        }

        .card {
            background: #fff;
            border-radius: 6px;
            padding: 20px;
            margin-top: 20px;
        }

        .card h3 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            padding: 10px 20px;
            background: #1e88e5;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn:hover {
            background: #1565c0;
        }

        .btn-secondary {
            background: #6c757d;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .required {
            color: red;
        }
    </style>
    @include('components.urdu-input-support')
    @php
        // Effective role for UI: admin user always sees full interface,
        // others follow the application role from settings.
        $settingsRole = optional(\App\Models\CompanySetting::current())->role ?? 'admin';
        $appRole = (auth()->check() && auth()->user()->email === 'admin') ? 'admin' : $settingsRole;
    @endphp
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>کمیشن شاپ</h2>
    <ul>
        <li><a href="/dashboard"><i class="fa fa-dashboard"></i> {{ __('messages.dashboard') }}</a></li>
        <li><a href="/sales"><i class="fa fa-cart-shopping"></i> {{ __('messages.sales') }}</a></li>
        <li><a href="/purchase"><i class="fa fa-box"></i> {{ __('messages.purchase') }}</a></li>
        @if($appRole === 'admin')
            <li><a href="/reports"><i class="fa fa-chart-line"></i> {{ __('messages.reports') }}</a></li>
            <li><a href="/bank-cash"><i class="fa fa-university"></i> {{ __('messages.bank_cash') }}</a></li>
            <li><a href="/settings"><i class="fa fa-gear"></i> {{ __('messages.settings') }}</a></li>
        @endif
        <li><a href="/bakery" class="{{ request()->is('bakery*') ? 'active' : '' }}"><i class="fa fa-bread-slice"></i> بیکری</a></li>
    </ul>
</div>

<!-- Main -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <label for="vendorSearch" class="sr-only">{{ __('messages.search') }}</label>
        <input type="search" id="vendorSearch" name="search" placeholder="{{ __('messages.search') }}..." autocomplete="off">
        @include('components.user-role-display')
    </div>

    <!-- Content -->
    <div class="card">
        <h3>وینڈر میں ترمیم</h3>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="/vendors/{{ $vendor->id }}" method="POST">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="name">وینڈر نام <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $vendor->name) }}" required autocomplete="organization">
            </div>

            <div class="form-group">
                <label for="mobile">موبائل <span class="required">*</span></label>
                <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $vendor->mobile) }}" required autocomplete="tel">
            </div>

            <div class="form-group">
                <label for="email">ای میل</label>
                <input type="email" id="email" name="email" value="{{ old('email', $vendor->email) }}" autocomplete="email">
            </div>

            <div class="form-group">
                <label for="address">پتہ</label>
                <textarea id="address" name="address" autocomplete="street-address">{{ old('address', $vendor->address) }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">حالت <span class="required">*</span></label>
                <select id="status" name="status" required autocomplete="off">
                    <option value="active" {{ old('status', $vendor->status) == 'active' ? 'selected' : '' }}>فعال</option>
                    <option value="blocked" {{ old('status', $vendor->status) == 'blocked' ? 'selected' : '' }}>بلاک</option>
                </select>
            </div>

            <div class="form-group">
                <label for="commission_rate">کمیشن شرح (%) <span class="required">*</span></label>
                <input type="number" id="commission_rate" name="commission_rate" step="0.01" min="0" max="100" value="{{ old('commission_rate', $vendor->commission_rate) }}" required autocomplete="off">
            </div>

            <div>
                <button type="submit" class="btn">وینڈر اپ ڈیٹ کریں</button>
                <a href="/vendors" class="btn btn-secondary">منسوخ</a>
            </div>
        </form>
    </div>

</div>

<script>
</script>

<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

</body>
</html>
