@php
    // Use settings passed from controller, or get from database if not available
    $settings = $settings ?? \App\Models\CompanySetting::current();
    $appLanguage = $settings ? ($settings->language ?? 'ur') : 'ur';
@endphp
<!DOCTYPE html>
<html lang="{{ $appLanguage }}" dir="{{ $appLanguage === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.profile') }} - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.prevent-back-button')
    @include('components.global-dark-mode-styles')
    @include('components.main-content-spacing')
    @include('components.urdu-input-support')
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

        /* Main Content */
        .main {
            padding: 20px;
        }

        /* Top Bar */
        .topbar {
            background: #fff;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .topbar div {
            font-size: 15px;
        }

        /* Profile Card */
        .profile-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .profile-card h3 {
            margin: 0 0 25px 0;
            font-size: 20px;
            color: #111827;
            border-bottom: 2px solid #1e88e5;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-card h3 i {
            color: #1e88e5;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #1e88e5;
        }

        .info-item label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .info-item .value {
            font-size: 16px;
            color: #111827;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }

        .form-group input[type="file"] {
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
        }

        .profile-image-container {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 5px solid #1e88e5;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.2);
        }

        .profile-image-display {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            color: white;
            font-size: 90px;
        }

        .profile-image-upload-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 45px;
            height: 45px;
            background: #1e88e5;
            border: 3px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .profile-image-upload-label:hover {
            background: #1565c0;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.4);
        }

        .profile-image-upload-label i {
            color: white;
            font-size: 18px;
        }



        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Dark Mode */
        body.dark-mode {
            background: #0f172a;
            color: #e2e8f0;
        }

        body.dark-mode .topbar,
        body.dark-mode .profile-card {
            background: #1e293b;
            border-color: #334155;
        }

        body.dark-mode .profile-card h3 {
            color: #e2e8f0;
            border-bottom-color: #60a5fa;
        }

        body.dark-mode .info-item {
            background: #0f172a;
            border-left-color: #60a5fa;
        }

        body.dark-mode .info-item label {
            color: #94a3b8;
        }

        body.dark-mode .info-item .value {
            color: #e2e8f0;
        }

        body.dark-mode .form-group label {
            color: #cbd5e1;
        }

        body.dark-mode .form-group input {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.dark-mode .form-group input:focus {
            border-color: #60a5fa;
        }

        .btn-save:hover {
            background: #1565c0 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
        }

        body.dark-mode .btn-save {
            background: #3b82f6 !important;
        }

        body.dark-mode .btn-save:hover {
            background: #2563eb !important;
        }

        body.dark-mode .form-group input[type="file"] {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.dark-mode .profile-image-container {
            border-color: #60a5fa;
            background: #1e293b;
        }

        body.dark-mode .profile-image-placeholder {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        body.dark-mode .profile-image-upload-label {
            background: #3b82f6;
            border-color: #1e293b;
        }

        body.dark-mode .profile-image-upload-label:hover {
            background: #2563eb;
        }

        .profile-image-delete-label {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 45px;
            height: 45px;
            background: #ef4444;
            border: 3px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
            padding: 0;
            margin: 0;
        }

        .profile-image-container {
            cursor: pointer;
        }

        .profile-image-container:hover {
            opacity: 0.9;
        }

        .profile-image-delete-label:hover {
            background: #dc2626;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .profile-image-delete-label i {
            color: white;
            font-size: 18px;
        }

        body.dark-mode .profile-image-delete-label {
            background: #ef4444;
            border-color: #1e293b;
        }

        body.dark-mode .profile-image-delete-label:hover {
            background: #dc2626;
        }

        body.dark-mode [style*="background: #f9fafb"] {
            background: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-mode [style*="color: #111827"] {
            color: #e2e8f0 !important;
        }

        body.dark-mode [style*="color: #6b7280"] {
            color: #94a3b8 !important;
        }

        body.dark-mode [style*="color: #1e88e5"] {
            color: #60a5fa !important;
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

        body.dark-mode .topbar {
            background: #1e293b;
            border-color: #334155;
        }

        body.dark-mode .topbar h1 {
            color: #e2e8f0;
        }

        body.dark-mode .topbar h1 i {
            color: #60a5fa;
        }

        body.dark-mode .profile-card h3 i {
            color: #60a5fa;
        }



        body.dark-mode small {
            color: #94a3b8 !important;
        }

        body.dark-mode small i.fa-check-circle {
            color: #10b981 !important;
        }

        body.dark-mode small i.fa-exclamation-circle {
            color: #f59e0b !important;
        }

        body.dark-mode .info-item .value span {
            color: #94a3b8 !important;
        }

        body.dark-mode .alert strong {
            color: #e2e8f0 !important;
        }

        body.dark-mode .alert ul {
            color: #cbd5e1 !important;
        }

        body.dark-mode .alert ul li {
            color: #cbd5e1 !important;
        }

        /* Ensure all text elements are visible in dark mode */
        body.dark-mode .value {
            color: #e2e8f0 !important;
        }

        body.dark-mode .profile-info {
            color: #e2e8f0 !important;
        }

        /* Override inline color styles in dark mode */
        body.dark-mode [style*="color: #9ca3af"] {
            color: #94a3b8 !important;
        }

        body.dark-mode [style*="color: #10b981"] {
            color: #10b981 !important;
        }

        body.dark-mode [style*="color: #f59e0b"] {
            color: #f59e0b !important;
        }

        /* Ensure form inputs have proper styling */
        body.dark-mode input[type="text"]:not(.sidebar input),
        body.dark-mode input[type="email"]:not(.sidebar input) {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode input[type="text"]:focus:not(.sidebar input),
        body.dark-mode input[type="email"]:focus:not(.sidebar input) {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1) !important;
        }

        /* Ensure icons are visible */
        body.dark-mode .alert i {
            color: inherit !important;
        }

        body.dark-mode .profile-card h3 i,
        body.dark-mode .topbar h1 i {
            color: #60a5fa !important;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }

            .profile-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
        $isAdmin = $currentUser && ($currentUser->hasRole('Admin') || $currentUser->hasRole('Super Admin'));
        $isUser = $currentUser && $currentUser->hasRole('User');
        $isOperator = $currentUser && $currentUser->hasRole('Operator');

        // Translate role display names while keeping internal names unchanged
        function translateRoleNameProfile($roleName) {
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
        <div class="topbar">
            <div>
                <h1 style="margin: 0; font-size: 24px;">
                    <i class="fa fa-user-circle"></i> {{ __('messages.profile') }}
                </h1>
            </div>
            <div>
                @include('components.user-role-display')
            </div>
        </div>

        @if(session('status') === 'profile-updated')
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                <span>{{ __('messages.profile_updated_success') }}</span>
            </div>
        @endif

        @if(session('status') === 'image-deleted')
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                <span>{{ __('messages.image_deleted_success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fa fa-exclamation-circle"></i>
                <div>
                    <strong>{{ __('messages.please_fix_following') }}</strong>
                    <ul style="margin: 8px 0 0 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Profile Information Display -->
        <div class="profile-card">
            <h3>
                <i class="fa fa-info-circle"></i>
                {{ __('messages.account_information') }}
            </h3>
            <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 30px; padding: 20px; background: #f9fafb; border-radius: 12px; border: 2px dashed #e5e7eb;">
                <div style="position: relative;">
                    <div class="profile-image-container" onclick="toggleDeleteButton(event)">
                        @if($user->hasProfileImage())
                            <img src="{{ $user->profile_image_url }}" alt="Profile Image" class="profile-image-display" id="currentProfileImage">
                        @else
                            <div class="profile-image-placeholder" id="profileImagePlaceholder">
                                <i class="fa fa-user-circle"></i>
                            </div>
                        @endif
                    </div>
                    @if($user->hasProfileImage())
                        <label for="profile_image" class="profile-image-upload-label" title="Click to change profile image">
                            <i class="fa fa-camera"></i>
                        </label>
                        <button type="button" class="profile-image-delete-label" id="deleteImageBtn" title="Click to remove profile image" onclick="removeProfileImage()" style="display: none;">
                            <i class="fa fa-trash"></i>
                        </button>
                    @else
                        <label for="profile_image" class="profile-image-upload-label" title="Click to upload profile image">
                            <i class="fa fa-camera"></i>
                        </label>
                    @endif
                </div>
                <div style="flex: 1;">
                    <h2 style="margin: 0 0 5px 0; font-size: 24px; color: #111827;">{{ $user->name }}</h2>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">{{ $user->email }}</p>
                    @if(!empty($roles))
                        <p style="margin: 5px 0 0 0; color: #1e88e5; font-size: 13px; font-weight: 600;">
                            {{ implode(', ', array_map('translateRoleNameProfile', $roles)) }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="profile-info">
                <div class="info-item">
                    <label>{{ __('messages.name') }}</label>
                    <div class="value">{{ $user->name }}</div>
                </div>
                <div class="info-item">
                    <label>{{ __('messages.email') }}</label>
                    <div class="value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <label>{{ __('messages.role') }}</label>
                    <div class="value">
                        @if(!empty($roles))
                            {{ implode(', ', array_map('translateRoleNameProfile', $roles)) }}
                        @else
                            <span style="color: #9ca3af;">{{ __('messages.no_role_assigned') }}</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <label>{{ __('messages.account_created') }}</label>
                    <div class="value">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="profile-card">
            <h3>
                <i class="fa fa-edit"></i>
                {{ __('messages.update_profile_information') }}
            </h3>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileUpdateForm">
                @csrf
                @method('patch')
                
                <!-- Hidden file input for profile image (triggered by camera icon) -->
                <input 
                    type="file" 
                    id="profile_image" 
                    name="profile_image" 
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    style="display: none;"
                    onchange="previewProfileImage(this)"
                >
                
                <!-- Hidden input for removing profile image -->
                <input 
                    type="hidden" 
                    id="remove_profile_image" 
                    name="remove_profile_image" 
                    value="0"
                >

                <div class="form-group">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autocomplete="name"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="email"
                    >
                    @if($user->email_verified_at)
                        <small style="color: #10b981; margin-top: 5px; display: block;">
                            <i class="fa fa-check-circle"></i> {{ __('messages.email_verified') }}
                        </small>
                    @else
                        <small style="color: #f59e0b; margin-top: 5px; display: block;">
                            <i class="fa fa-exclamation-circle"></i> {{ __('messages.email_not_verified') }}
                        </small>
                    @endif
                </div>

            </form>
        </div>

    </div>

    <!-- Global Dark Mode Script - Syncs with dashboard toggle -->
    <script src="{{ asset('js/global-dark-mode.js') }}"></script>

    <script>
        function previewProfileImage(input) {
            const currentImage = document.getElementById('currentProfileImage');
            const placeholder = document.getElementById('profileImagePlaceholder');
            const container = document.querySelector('.profile-image-container');
            const removeInput = document.getElementById('remove_profile_image');
            const form = document.getElementById('profileUpdateForm');
            
            if (input.files && input.files[0]) {
                // Reset remove flag when new image is selected
                if (removeInput) {
                    removeInput.value = '0';
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // If placeholder exists, hide it
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                    
                    // Update or create image element
                    if (currentImage) {
                        currentImage.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.id = 'currentProfileImage';
                        img.src = e.target.result;
                        img.alt = 'Profile Image';
                        img.className = 'profile-image-display';
                        if (container) {
                            if (placeholder) {
                                container.insertBefore(img, placeholder);
                            } else {
                                container.appendChild(img);
                            }
                        }
                    }
                    
                    // Show delete button if image exists
                    const deleteBtn = document.getElementById('deleteImageBtn');
                    if (deleteBtn) {
                        deleteBtn.style.display = 'flex';
                    }
                    
                    // Auto-submit form to save the image immediately
                    if (form) {
                        form.submit();
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeProfileImage() {
            if (confirm('کیا آپ واقعی اپنی پروفائل تصویر حذف کرنا چاہتے ہیں؟')) {
                const form = document.getElementById('profileUpdateForm');
                const removeInput = document.getElementById('remove_profile_image');
                const fileInput = document.getElementById('profile_image');
                
                // Set remove flag
                if (removeInput) {
                    removeInput.value = '1';
                }
                
                // Clear file input
                if (fileInput) {
                    fileInput.value = '';
                }
                
                // Submit the form to save the change
                if (form) {
                    form.submit();
                }
            }
        }

        function toggleDeleteButton(event) {
            // Only show delete button if user has an image
            const currentImage = document.getElementById('currentProfileImage');
            if (!currentImage) {
                return; // No image to delete
            }
            
            // Stop event propagation to prevent triggering other click handlers
            if (event) {
                event.stopPropagation();
            }
            
            const deleteBtn = document.getElementById('deleteImageBtn');
            if (deleteBtn) {
                if (deleteBtn.style.display === 'none' || deleteBtn.style.display === '') {
                    deleteBtn.style.display = 'flex';
                } else {
                    deleteBtn.style.display = 'none';
                }
            }
        }

        // Hide delete button when clicking outside
        document.addEventListener('click', function(event) {
            const deleteBtn = document.getElementById('deleteImageBtn');
            const imageContainer = document.querySelector('.profile-image-container');
            const uploadLabel = document.querySelector('.profile-image-upload-label');
            
            if (deleteBtn && imageContainer && deleteBtn.style.display === 'flex') {
                // Check if click is outside the image container, delete button, and upload label
                if (!imageContainer.contains(event.target) && 
                    !deleteBtn.contains(event.target) && 
                    !uploadLabel.contains(event.target)) {
                    deleteBtn.style.display = 'none';
                }
            }
        });

        // Auto-hide success/error alerts after 7 seconds (within 5-10 seconds as requested)
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            if (!alerts.length) return;

            setTimeout(function () {
                alerts.forEach(function (alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function () {
                        if (alert && alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                });
            }, 7000); // 7 seconds
        });
    </script>
</body>
</html>
