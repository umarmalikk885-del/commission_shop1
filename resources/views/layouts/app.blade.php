@php
    // Global settings and language, reused across pages
    $settings = \App\Models\CompanySetting::current();
    $appLanguage = $settings ? ($settings->language ?? 'ur') : 'ur';
@endphp
<!DOCTYPE html>
<html lang="{{ $appLanguage }}" dir="{{ $appLanguage === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'کمیشن شاپ')</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @include('components.prevent-back-button')
    @include('components.global-dark-mode-styles')
    @include('components.main-content-spacing')

    <!-- Initialize dark mode immediately -->
    <script>
        (function() {
            const DARK_MODE_KEY = 'darkMode';
            const THEME_KEY = 'theme';
            const root = document.documentElement;

            function isDarkMode() {
                const darkMode = localStorage.getItem(DARK_MODE_KEY);
                if (darkMode !== null) return darkMode === 'true';
                const theme = localStorage.getItem(THEME_KEY);
                if (theme === 'dark') return true;
                if (theme === 'light') return false;
                return localStorage.getItem('darkMode') === 'true';
            }

            const enabled = isDarkMode();
            root.classList.toggle('dark-mode', enabled);

            function applyToBody() {
                if (document.body && document.body.classList) {
                    document.body.classList.toggle('dark-mode', enabled);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyToBody, { once: true });
            } else {
                applyToBody();
            }
        })();
    </script>

    @yield('head')
</head>
<body>
    @include('components.sidebar')

    <div class="main">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'کمیشن شاپ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    
    @stack('scripts')
    </body>
</html>
