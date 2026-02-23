<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>لاگ اِن - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 40%, #0f172a 100%);
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
            background: #0f172a;
            border-radius: 16px;
            padding: 28px 26px 26px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.7);
            border: 1px solid #1e293b;
        }

        .card-header {
            text-align: center;
            margin-bottom: 22px;
        }

        .card-header h1 {
            margin: 0;
            font-size: 22px;
            color: #f9fafb;
        }

        .card-header p {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #9ca3af;
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

        .app-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: radial-gradient(circle at 30% 20%, #60a5fa, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: #eff6ff;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.6);
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

        /* Shared styling for username + password */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #1f2937;
            background: #020617;
            color: #e5e7eb;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
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
            margin-top: 6px;
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.55);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 38px rgba(37, 99, 235, 0.7);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 10px 22px rgba(30, 64, 175, 0.7);
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
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="app-icon">
                <i class="fa fa-store"></i>
            </div>
            <h1>کمیشن شاپ</h1>
            <p>اپنے اکاؤنٹ میں سائن اِن کریں</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <i class="fa fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            <div class="form-group username-group">
                <label for="username">صارف نام</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value=""
                    required
                    autocomplete="off"
                    placeholder="صارف نام درج کریں"
                >
            </div>

            <div class="form-group">
                <label for="password">پاس ورڈ</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    value=""
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn-login">
                <i class="fa fa-lock"></i>
                <span>سائن اِن کریں</span>
            </button>
            </form>
    </div>
</div>

</body>
</html>

