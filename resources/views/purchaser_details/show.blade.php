<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $laga->name }} - تفصیلات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Font Awesome & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.main-content-spacing')
</head>
<body>
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
    @include('components.sidebar')

    <div class="main">
        <div class="topbar">
            <h2 style="margin: 0;" class="urdu-text">
                <a href="{{ route('laga-details.index') }}" style="text-decoration: none; color: inherit; margin-right: 10px;">
                    <i class="fa fa-arrow-{{ app()->getLocale() == 'ur' ? 'right' : 'left' }}"></i>
                </a>
                {{ $laga->name }} ({{ $laga->code }})
            </h2>
            <div style="display: flex; align-items: center; gap: 15px;">
                @include('components.user-role-display')
            </div>
        </div>

        <!-- Profile Card -->
        <div class="mandi-card">
            <div class="card-header">
                <h3><i class="fa fa-user" style="color: #6366f1;"></i> {{ __('ذاتی معلومات') }}</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div><strong>{{ __('نام') }}:</strong> <span class="urdu-text" style="font-size: 1.1rem;">{{ $laga->name }}</span></div>
                <div><strong>{{ __('کوڈ') }}:</strong> <span class="badge-code">{{ $laga->code }}</span></div>
                <div><strong>{{ __('موبائل') }}:</strong> {{ $laga->mobile ?? '-' }}</div>
                <div><strong>{{ __('پتہ') }}:</strong> <span class="urdu-text">{{ $laga->address ?? '-' }}</span></div>
                <div><strong>{{ __('سٹیٹس') }}:</strong> <span class="badge {{ $laga->status == 'active' ? 'bg-success' : 'bg-secondary' }}" style="padding: 2px 8px; border-radius: 4px; color: white; font-size: 0.8rem;">{{ $laga->status }}</span></div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="summary-grid">
            <div class="summary-box">
                <div class="label">{{ __('کل آئٹمز') }}</div>
                <div class="value">{{ number_format($stats['total_items']) }}</div>
            </div>
            <div class="summary-box" style="background: #eff6ff; border-color: #dbeafe;">
                <div class="label" style="color: #1e40af;">{{ __('کل رقم') }}</div>
                <div class="value" style="color: #1d4ed8;">{{ number_format($stats['total_amount']) }}</div>
            </div>
            <div class="summary-box" style="background: #ecfdf5; border-color: #d1fae5;">
                <div class="label" style="color: #047857;">{{ __('وصول شدہ') }}</div>
                <div class="value" style="color: #059669;">{{ number_format($stats['total_paid']) }}</div>
            </div>
            <div class="summary-box" style="background: #fef2f2; border-color: #fee2e2;">
                <div class="label" style="color: #b91c1c;">{{ __('بقایا') }}</div>
                <div class="value" style="color: #dc2626;">{{ number_format($stats['balance']) }}</div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="mandi-card">
            <div class="card-header">
                <h3><i class="fa fa-history" style="color: #6366f1;"></i> {{ __('لاگا کی تفصیل') }}</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('تاریخ') }}</th>
                            <th>{{ __('آئٹم') }}</th>
                            <th>{{ __('تعداد') }}</th>
                            <th>{{ __('ریٹ') }}</th>
                            <th>{{ __('کل رقم') }}</th>
                            <th>{{ __('وصول') }}</th>
                            <th>{{ __('بقایا') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $p)
                            <tr>
                                <td>{{ $p->purchase_date->format('d/m/Y') }}</td>
                                <td class="urdu-text">{{ $p->item_name }}</td>
                                <td>{{ number_format($p->quantity, 2) }}</td>
                                <td>{{ number_format($p->rate, 2) }}</td>
                                <td>{{ number_format($p->total_amount, 2) }}</td>
                                <td style="color: #059669; font-weight: 600;">{{ number_format($p->paid_amount, 2) }}</td>
                                <td style="color: #dc2626; font-weight: 600;">{{ number_format($p->total_amount - $p->paid_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">{{ __('کوئی ریکارڈ نہیں ملا') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main').classList.toggle('active');
        }
    </script>
</body>
</html>
