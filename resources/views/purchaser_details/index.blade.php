<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('لاگا تفصیلات') }} | کمیشن شاپ</title>
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
                <i class="fa fa-users text-primary" style="margin-left: 10px;"></i>
                {{ __('لاگا تفصیلات') }}
            </h2>
            <div style="display: flex; align-items: center; gap: 15px;">
                @include('components.user-role-display')
            </div>
        </div>

        <div class="mandi-card">
            <div class="card-header">
                <h3><i class="fa fa-search" style="color: #6366f1;"></i> {{ __('لاگا تلاش کریں') }}</h3>
            </div>
            
            <form method="GET" action="{{ route('laga-details.index') }}" style="display: flex; gap: 10px; margin-bottom: 24px;">
                <input type="text" name="search" class="form-control" placeholder="{{ __('نام یا کوڈ لکھیں...') }}" value="{{ request('search') }}" style="max-width: 400px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> {{ __('تلاش') }}
                </button>
                @if(request('search'))
                    <a href="{{ route('laga-details.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> {{ __('Reset') }}
                    </a>
                @endif
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('کوڈ') }}</th>
                            <th>{{ __('نام') }}</th>
                            <th>{{ __('موبائل') }}</th>
                            <th>{{ __('پتہ') }}</th>
                            <th>{{ __('مقام') }}</th>
                            <th>{{ __('BOD') }}</th>
                            <th>{{ __('Contact Number') }}</th>
                            <th>{{ __('کارروائی') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lagas as $laga)
                            <tr>
                                <td><span class="badge-code">{{ $laga->code }}</span></td>
                                <td class="urdu-text" style="font-size: 1.05rem; font-weight: 500;">{{ $laga->name }}</td>
                                <td>{{ $laga->mobile }}</td>
                                <td>{{ $laga->address ?? '-' }}</td>
                                <td>{{ $laga->location ?? '-' }}</td>
                                <td>{{ $laga->bod ?? '-' }}</td>
                                <td>{{ $laga->contact_number ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('laga-details.show', $laga->id) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem;">
                                        <i class="fa fa-eye"></i> {{ __('تفصیلات') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">
                                    <i class="fa fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                                    {{ __('کوئی ریکارڈ نہیں ملا') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 20px;">
                {{-- Pagination if needed, though controller uses get() currently --}}
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
