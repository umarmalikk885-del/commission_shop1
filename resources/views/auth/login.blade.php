<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>لاگ اِن - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @include('components.prevent-back-button')

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top left, #1e3a8a 0%, #0f172a 45%, #020617 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #e5e7eb;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .card {
            background: linear-gradient(145deg, #020617 0%, #020617 60%, #0b1120 100%);
            border-radius: 18px;
            padding: 28px 26px 26px;
            box-shadow: 0 22px 45px rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.18);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.35), transparent 55%);
            opacity: 0.9;
            pointer-events: none;
        }

        .card-inner {
            position: relative;
            z-index: 1;
        }

        .card-header {
            text-align: center;
            margin-bottom: 22px;
        }

        .card-header h1 {
            margin: 0;
            font-size: 22px;
            color: #f9fafb;
            letter-spacing: 0.02em;
        }

        .card-header p {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #9ca3af;
        }

        .app-icon {
            width: 48px;
            height: 48px;
            border-radius: 15px;
            background: conic-gradient(from 160deg, #38bdf8, #6366f1, #22c55e, #38bdf8);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: #eff6ff;
            box-shadow: 0 16px 35px rgba(79, 70, 229, 0.75);
        }

        .app-icon i {
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #e5e7eb;
        }

        /* Username field special styling */
        .form-group.username-group label {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .username-chip {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 2px 6px;
            border-radius: 999px;
            background: rgba(59,130,246,0.18);
            color: #bfdbfe;
            border: 1px solid rgba(59,130,246,0.45);
        }

        /* Shared styling for username + password */
        /* Bootstrap form control overrides for dark theme */
        .form-control {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid #1f2937;
            color: #e5e7eb;
            padding: 10px 12px;
            border-radius: 9px;
            font-size: 14px;
        }

        .form-control::placeholder {
            color: #6b7280;
        }

        .form-control:focus {
            background: #020617;
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            color: #e5e7eb;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
            margin-top: 6px;
        }

        .form-check-label {
            font-size: 12px;
            font-weight: 500;
            color: #9ca3af;
        }

        .form-check-input {
            accent-color: #3b82f6;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .forgot-link {
            font-size: 12px;
            color: #93c5fd;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 11px 16px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #0ea5e9 100%);
            color: #f9fafb;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            box-shadow: 0 16px 34px rgba(37, 99, 235, 0.7);
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.85);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 10px 22px rgba(30, 64, 175, 0.8);
        }

        .hint {
            margin-top: 14px;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }

        .hint code {
            background: rgba(15, 23, 42, 0.8);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            border: 1px solid #1e293b;
        }

        .error-message {
            background: rgba(248, 113, 113, 0.15);
            border: 1px solid #f87171;
            color: #fecaca;
            padding: 9px 11px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-message i {
            color: #fca5a5;
        }

        .status-message {
            background: rgba(34, 197, 94, 0.18);
            border: 1px solid #22c55e;
            color: #bbf7d0;
            padding: 9px 11px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-message i {
            color: #4ade80;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="card">
        <div class="card-inner">
            <div class="card-header">
                <div class="app-icon">
                    <i class="fa fa-store"></i>
                </div>
                <h1>????? ???</h1>
                <p>اپنے اکاؤنٹ میں سائن اِن کریں</p>
            </div>

            @if (session('status'))
                <div class="status-message">
                    <i class="fa fa-circle-check"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <i class="fa fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf

                <div class="form-group username-group">
                    <label for="username" class="form-label">صارف نام</label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        class="form-control"
                        value="{{ old('username') }}"
                        required
                        autocomplete="off"
                        autofocus
                        placeholder="صارف نام درج کریں"
                    >
                    @error('username')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">پاس ورڈ</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        required
                        autocomplete="current-password"
                        placeholder="پاس ورڈ درج کریں"
                    >
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            مجھے یاد رکھیں
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            پاس ورڈ بھول گئے؟
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa fa-lock"></i>
                    <span>سائن اِن کریں</span>
                </button>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>

