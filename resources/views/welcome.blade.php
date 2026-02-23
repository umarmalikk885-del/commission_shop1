<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('ڈیش بورڈ') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.urdu-input-support')
    @include('components.main-content-spacing')

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --accent-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Outfit', 'Noto Nastaliq Urdu', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .main { padding: 24px; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: #6366f1;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-info h4 { margin: 0; font-size: 0.9rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .stat-info p { margin: 4px 0 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; }

        .welcome-hero {
            background: var(--primary-gradient);
            border-radius: 24px;
            padding: 48px;
            color: white;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .welcome-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .welcome-text h1 { font-size: 2.5rem; margin: 0; font-family: 'Noto Nastaliq Urdu', serif; }
        .welcome-text p { font-size: 1.1rem; opacity: 0.9; margin-top: 10px; }

        .btn-mandi {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: white;
            color: #0f172a;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 20px;
            transition: var(--transition);
        }

        .btn-mandi:hover { background: #f1f5f9; transform: scale(1.05); }

        .urdu-text { font-family: 'Noto Nastaliq Urdu', serif; line-height: 2; }

    </style>
</head>
<body class="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : '' }}">

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    @include('components.sidebar')

    <div class="main">
        <div class="welcome-hero">
            <div class="welcome-text">
                <h1 class="urdu-text">{{ __('خوش آمدید') }}</h1>
                <p>کمیشن شاپ مینجمنٹ سسٹم v2.0 پریمیم میں خوش آمدید</p>
                <div style="display: flex; gap: 15px;">
                    <a href="/purchase" class="btn-mandi"><i class="fa fa-cart-shopping"></i> {{ __('خریداری شروع کریں') }}</a>
                    <a href="/rokad" class="btn-mandi" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);"><i class="fa fa-book"></i> {{ __('لیجر دیکھیں') }}</a>
                </div>
            </div>
            <i class="fa fa-shop" style="font-size: 8rem; opacity: 0.1;"></i>
        </div>

        <div class="dashboard-grid">
            <a href="/purchase" class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);"><i class="fa fa-boxes-packing"></i></div>
                <div class="stat-info">
                    <h4>{{ __('لاگا') }}</h4>
                    <p>{{ __('آئٹمز اور بل') }}</p>
                </div>
            </a>
            <a href="/rokad" class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);"><i class="fa fa-hand-holding-dollar"></i></div>
                <div class="stat-info">
                    <h4>{{ __('روکڑ') }}</h4>
                    <p>{{ __('خریداروں کا بیلنس') }}</p>
                </div>
            </a>
            <a href="/recovery" class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);"><i class="fa fa-money-bill-transfer"></i></div>
                <div class="stat-info">
                    <h4>{{ __('وصولی') }}</h4>
                    <p>{{ __('ادائیگیوں کا اندراج') }}</p>
                </div>
            </a>
            <a href="/payment" class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #db2777);"><i class="fa fa-book-bookmark"></i></div>
                <div class="stat-info">
                    <h4>{{ __('بکری بُک') }}</h4>
                    <p>{{ __('اخراجات اور لیجر') }}</p>
                </div>
            </a>
        </div>

        <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="mandi-card" style="background: white; border-radius: 20px; padding: 24px; box-shadow: var(--card-shadow);">
                <h3 class="urdu-text"><i class="fa fa-clock-rotate-left"></i> {{ __('حالیہ سرگرمی') }}</h3>
                <div style="margin-top: 20px; color: #64748b; text-align: center; padding: 40px;">
                    <i class="fa fa-folder-open" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p>کوئی حالیہ سرگرمی نہیں ملی۔</p>
                </div>
            </div>
            <div class="mandi-card" style="background: white; border-radius: 20px; padding: 24px; box-shadow: var(--card-shadow);">
                <h3 class="urdu-text"><i class="fa fa-bullhorn"></i> {{ __('اطلاعات') }}</h3>
                <div style="margin-top: 20px; color: #64748b; text-align: center; padding: 40px;">
                    <i class="fa fa-bell-slash" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p>کوئی نئی اطلاع موجود نہیں۔</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
