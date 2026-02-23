<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.role_management') }} - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.urdu-input-support')
    @include('components.prevent-back-button')
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
        .main { 
            margin-left: 230px; 
            padding: 20px; 
        }
        .card { 
            background: #fff; 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
        }
        .card h3 {
            margin-top: 0;
            color: #111827;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .btn { 
            padding: 10px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            transition: all 0.2s;
        }
        .btn-primary { 
            background: #1e88e5; 
            color: white; 
        }
        .btn-primary:hover {
            background: #1565c0;
        }
        .btn-danger { 
            background: #ef4444; 
            color: white; 
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-success { 
            background: #10b981; 
            color: white; 
        }
        .btn-success:hover {
            background: #059669;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table th, .table td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #e5e7eb; 
        }
        .table th { 
            background: #f9fafb; 
            font-weight: 600; 
            color: #111827;
        }
        .table td {
            color: #374151;
        }
        .badge { 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 12px; 
        }
        .badge-success { 
            background: #10b981; 
            color: white; 
        }
        .badge-warning { 
            background: #f59e0b; 
            color: white; 
        }
        .form-group { 
            margin-bottom: 15px; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: 600; 
            color: #111827;
        }
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #d1d5db; 
            border-radius: 6px; 
            background: #fff;
            color: #111827;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        .checkbox-group { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 10px; 
            margin-top: 10px; 
        }
        .checkbox-group label { 
            font-weight: normal; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            color: #374151;
        }
        .alert { 
            padding: 12px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
        }
        .alert-success { 
            background: #d1fae5; 
            color: #065f46; 
            border: 1px solid #10b981; 
        }
        .alert-error { 
            background: #fee2e2; 
            color: #991b1b; 
            border: 1px solid #ef4444; 
        }
        h2 {
            color: #111827;
            margin-bottom: 20px;
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

        body.dark-mode .card {
            background: #1e293b;
            border: 1px solid #334155;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .card h3 {
            color: #e2e8f0;
            border-bottom-color: #475569;
        }

        body.dark-mode .table th {
            background: #0f172a;
            color: #e2e8f0;
            border-bottom-color: #334155;
        }

        body.dark-mode .table td {
            color: #cbd5e1;
            border-bottom-color: #334155;
        }

        body.dark-mode .form-group label {
            color: #e2e8f0;
        }

        body.dark-mode .form-group input,
        body.dark-mode .form-group select {
            background: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        body.dark-mode .form-group input:focus,
        body.dark-mode .form-group select:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        body.dark-mode .checkbox-group label {
            color: #cbd5e1;
        }

        body.dark-mode .checkbox-group input[type="checkbox"] {
            accent-color: #60a5fa;
        }

        body.dark-mode .alert-success {
            background: #064e3b;
            color: #6ee7b7;
            border-color: #10b981;
        }

        body.dark-mode .alert-error {
            background: #7f1d1d;
            color: #fca5a5;
            border-color: #ef4444;
        }

        body.dark-mode h2 {
            color: #e2e8f0;
        }

        body.dark-mode [style*="color: #888"] {
            color: #94a3b8 !important;
        }

        /* RTL Support - text/layout only (sidebar stays fixed) */
        [dir="rtl"] .table th,
        [dir="rtl"] .table td {
            text-align: right;
        }

    </style>
</head>
<body>
    @php
        // Helper function to translate permission names
        function translatePermission($permissionName) {
            $translations = [
                'view dashboard' => __('messages.permission_view_dashboard'),
                'view sales' => __('messages.permission_view_sales'),
                'manage sales' => __('messages.permission_manage_sales'),
                'view purchases' => __('messages.permission_view_purchases'),
                'manage purchases' => __('messages.permission_manage_purchases'),
                'view vendors' => __('messages.permission_view_vendors'),
                'manage vendors' => __('messages.permission_manage_vendors'),
                'view reports' => __('messages.permission_view_reports'),
                'view stock' => __('messages.permission_view_stock'),
                'manage stock' => __('messages.permission_manage_stock'),
                'view settings' => __('messages.permission_view_settings'),
                'manage settings' => __('messages.permission_manage_settings'),
                'view bank-cash' => __('messages.permission_view_bank_cash'),
                'manage bank-cash' => __('messages.permission_manage_bank_cash'),
                'manage roles' => __('messages.permission_manage_roles'),
                'manage users' => __('messages.permission_manage_users'),
            ];
            return $translations[$permissionName] ?? $permissionName;
        }

        // Helper to translate role display names while keeping internal names intact
        function translateRoleName($roleName) {
            $map = [
                'Super Admin' => __('messages.role_super_admin'),
                'Admin'       => __('messages.role_admin'),
                'User'        => __('messages.role_user'),
                'Operator'    => __('messages.role_operator'),
            ];
            return $map[$roleName] ?? $roleName;
        }
    @endphp
    
    @include('components.sidebar')
    
    <div class="main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>{{ __('messages.role_management') }}</h2>
            <div style="display: flex; gap: 15px; align-items: center;">
                @include('components.user-role-display')
                <a href="/settings" class="btn btn-primary"><i class="fa fa-arrow-left"></i> {{ __('messages.back_to_settings') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div id="successMessage" class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div id="errorMessage" class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Create New Role -->
        <div class="card">
            <h3>{{ __('messages.create_new_role') }}</h3>
            <form action="{{ route('roles.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="role_name">{{ __('messages.role_name') }}</label>
                    <input type="text" id="role_name" name="name" required placeholder="{{ __('messages.role_name_placeholder') }}" autocomplete="organization-title">
                </div>
                <button type="submit" class="btn btn-success">{{ __('messages.create_role') }}</button>
            </form>
        </div>

        <!-- Existing Roles -->
        <div class="card">
            <h3>{{ __('messages.existing_roles') }}</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.role_name') }}</th>
                        <th>{{ __('messages.permissions') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ translateRoleName($role->name) }}</strong>
                            @if($role->name === 'Super Admin')
                                <span class="badge badge-warning">{{ __('messages.protected') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($role->permissions->count() > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($role->permissions as $permission)
                                        <span class="badge badge-success">{{ translatePermission($permission->name) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color: #888;">{{ __('messages.no_permissions') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($role->name !== 'Super Admin')
                                <form action="{{ route('roles.update', $role) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                    @csrf
                                    @method('PUT')
                                    <div class="checkbox-group">
                                        @foreach($allPermissions as $permission)
                                            <label>
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                {{ translatePermission($permission->name) }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('messages.update_permissions') }}</button>
                                </form>
                                <!-- Delete button removed -->
                            @else
                                <span style="color: #888;">{{ __('messages.cannot_modify') }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Global Dark Mode Script
    </script>
    
    <!-- Auto-hide success/error messages after 7 seconds -->
    <script>
        (function() {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        if (successMessage && successMessage.parentNode) {
                            successMessage.parentNode.removeChild(successMessage);
                        }
                    }, 500);
                }, 7000); // 7 seconds
            }
            
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.transition = 'opacity 0.5s ease-out';
                    errorMessage.style.opacity = '0';
                    setTimeout(function() {
                        if (errorMessage && errorMessage.parentNode) {
                            errorMessage.parentNode.removeChild(errorMessage);
                        }
                    }, 500);
                }, 7000); // 7 seconds
            }
        })();
    </script>

<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

</body>
</html>
