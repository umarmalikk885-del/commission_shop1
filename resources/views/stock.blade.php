@php
    $lang = app()->getLocale();
    if ($lang === null) {
        $lang = 'ur';
    } elseif (str_starts_with($lang, 'ur')) {
        $lang = 'ur';
    } else {
        $lang = 'ur';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>اسٹاک / انوینٹری - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    </style>
    @include('components.main-content-spacing')
    <style>
        /* Main Content */
        .main {
            padding: 20px;
        }

        /* Top Bar */
        .topbar {
            background: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 6px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .topbar-left,
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar input {
            padding: 8px;
            width: 280px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
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

        body.dark-mode .card {
            background: #1e293b;
            border: 1px solid #334155;
        }

        body.dark-mode h2, 
        body.dark-mode h3,
        body.dark-mode h4 {
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

        body.dark-mode a {
            color: #60a5fa;
        }

        body.dark-mode a:hover {
            color: #93c5fd;
        }

        body.dark-mode label {
            color: #e2e8f0;
        }

        body.dark-mode table th {
            background: #0f172a;
            color: #e2e8f0;
        }

        body.dark-mode table td {
            color: #e2e8f0;
            border-color: #334155;
        }

        body.dark-mode .low-stock-alert {
            background: #1e293b;
            border-left-color: #fbbf24;
            color: #e2e8f0;
        }

        body.dark-mode .low-stock-alert.danger {
            background: #7f1d1d;
            border-left-color: #dc2626;
            color: #fecaca;
        }

        body.dark-mode .low-stock-alert i {
            color: inherit;
        }

        body.dark-mode .alert-success {
            background: #1e4d2b;
            color: #a3d5a8;
        }

        body.dark-mode .badge {
            color: #e2e8f0;
        }

        body.dark-mode .badge-success {
            background: #059669;
            color: #fff;
        }

        body.dark-mode .badge-warning {
            background: #d97706;
            color: #fff;
        }

        body.dark-mode .badge-danger {
            background: #dc2626;
            color: #fff;
        }

        .content {
            margin-top: 20px;
            display: grid;
            grid-template-columns: minmax(0, 1.5fr);
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
            gap: 20px;
            align-items: start;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
        }

        .card h3 {
            margin: 0 0 12px 0;
            font-size: 18px;
            color: #111827;
            font-weight: 600;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
            margin-top: 4px;
        }

        .alert {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Stock modal header: move close button to far left without affecting other pages */
        #stockModal .modal-header {
            position: relative;
        }

        #stockModal .modal-header .btn-close {
            position: absolute;
            left: 12px;
            right: auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: #111827;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: #fff;
            color: #111827;
        }

        .form-group textarea {
            min-height: 90px;
            resize: vertical;
            grid-column: 1 / -1;
        }

        .row-span-2 {
            grid-column: 1 / -1;
        }

        .btn {
            padding: 10px 14px;
            background: #1e88e5;
            color: #fff;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table th, table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eef2f7;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background: #f3f4f6;
            font-weight: 700;
            color: #111827;
        }

        .text-right { text-align: right; }
        .nowrap { white-space: nowrap; }

        /* Make stock table wider with horizontal scroll if needed (stock page only) */
        .stock-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        #stockTable {
            width: 100%;
            min-width: 900px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .low-stock-alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
        }
        
        .low-stock-alert.danger {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        
        .low-stock-alert i {
            font-size: 18px;
        }

        .stock-row-critical {
            background-color: #b91c1c;
            color: #ffffff;
        }

        .stock-row-critical td,
        .stock-row-critical th {
            color: #ffffff;
        }

        .stock-row-out {
            background-color: #fee2e2;
        }

        .stock-row-out:hover {
            background-color: #fecaca;
        }

        .low-stock-badge {
            color: #ffffff;
            font-size: 0.9rem;
        }
    </style>
    @include('components.urdu-input-support')
    @php
        // Simple public UI (no role-based visibility).
    @endphp
</head>
<body>

@include('components.sidebar')

@php
    $currentUser = auth()->user();
    $canManageStock = $currentUser && !$currentUser->hasRole('User');
    $lowStockPercent = $lowStockPercent ?? 15;
@endphp

<!-- Main -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            @if($canManageStock)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockModal" style="padding:8px 12px;" onclick="resetForm()">
                    <i class="fa fa-plus-circle"></i> {{ __('نیا اسٹاک') }}
                </button>
            @endif
            <input id="stockSearch" type="text" placeholder="{{ __('messages.search_stock') ?? 'اسٹاک تلاش کریں' }}" data-urdu="true" data-no-urdu-badge="true" class="urdu-text">
        </div>
        @include('components.user-role-display')
    </div>

    @if(session('success'))
        <div id="successMessage" class="alert alert-success" style="margin-top:12px; margin-bottom:0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="margin-top:12px; margin-bottom:0;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error" style="margin-top:12px; margin-bottom:0;">
            <strong>{{ __('messages.please_fix_following') }}</strong>
            <ul style="margin:8px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content">

        <!-- Stock List -->
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                <div>
                    <h3 style="margin-bottom:4px;">{{ __('messages.stock_inventory_title') }}</h3>
                    <div class="muted">{{ __('messages.stock_inventory_subtitle') }}</div>
                    <div class="muted">{{ __('messages.stock_threshold_label', ['percent' => $lowStockPercent]) }}</div>
                </div>
                <div class="form-check form-switch" style="margin:0;">
                    <input class="form-check-input" type="checkbox" id="lowStockOnlyToggle">
                    <label class="form-check-label" for="lowStockOnlyToggle" style="font-size:12px;">
                        {{ __('messages.low_stock_only_label') }}
                    </label>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            @if(isset($outOfStockItems) && $outOfStockItems->count() > 0)
                <div class="low-stock-alert danger" style="margin-top:15px;" role="alert" aria-live="polite">
                    <i class="fa fa-exclamation-triangle"></i>
                    <div>
                        <strong>{{ __('messages.out_of_stock_alert_title') }}</strong>
                        <div style="font-size:12px; margin-top:4px;">
                            {{ __('messages.out_of_stock_alert_body', ['count' => $outOfStockItems->count()]) }}
                        </div>
                    </div>
                </div>
            @endif
            
            @if(isset($lowStockItems) && $lowStockItems->count() > 0)
                <div class="low-stock-alert" style="margin-top:15px;" role="alert" aria-live="polite">
                    <i class="fa fa-exclamation-circle"></i>
                    <div>
                        <strong>{{ __('messages.low_stock_alert') }}</strong>
                        <div style="font-size:12px; margin-top:4px;">
                            {{ __('messages.low_stock_alert_body', ['count' => $lowStockItems->count(), 'percent' => $lowStockPercent]) }}
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="showLowStockOnlyBtn">
                        {{ __('messages.view_low_stock_only') }}
                    </button>
                </div>
            @endif
        
            @if(isset($stocks) && $stocks->count() > 0)
            <div class="stock-table-wrapper">
            <table id="stockTable" style="margin-top:15px;">
                <thead>
                    <tr>
                        <th>{{ __('messages.item_name') }}</th>
                        <th class="text-right">{{ __('messages.quantity') }}</th>
                        <th>{{ __('messages.unit') }}</th>
                        <th class="text-right">{{ __('messages.min_level') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        @if($canManageStock)
                        <th>{{ __('messages.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                        @php
                            $isOutOfStock = $stock->quantity <= 0;
                            $isLowStock = $stock->isLowStock();
                            $isCriticalRow = $isLowStock;
                            $formattedQty = rtrim(rtrim(number_format($stock->quantity, 2), '0'), '.');
                            $lowStockMessage = $isLowStock
                                ? __('messages.low_stock_alert_body', ['count' => 1, 'percent' => $lowStockPercent]) . ' (' . $formattedQty . ' ' . ($stock->unit ?? '') . ')'
                                : '';
                            $quantityColor = '#10b981';
                            $quantityBold = false;
                            if ($isOutOfStock || $isCriticalRow) {
                                $quantityColor = '#ffffff';
                                $quantityBold = true;
                            }
                            $descriptionText = $stock->description ?? '';
                            $isTestRow = \Illuminate\Support\Str::startsWith($descriptionText, '[TEST]');
                        @endphp
                        <tr class="stock-row {{ $isOutOfStock ? 'stock-row-out' : '' }} {{ $isCriticalRow ? 'stock-row-critical' : '' }}" 
                            data-stock-id="{{ $stock->id }}"
                            data-item-name="{{ $stock->item_name }}"
                            data-quantity="{{ $stock->quantity }}"
                            data-unit="{{ $stock->unit ?? '' }}"
                            data-min-stock-level="{{ $stock->min_stock_level }}"
                            data-rate="{{ $stock->rate ?? '' }}"
                            data-description="{{ $descriptionText }}"
                            data-low-stock="{{ $isLowStock ? '1' : '0' }}"
                            data-test-row="{{ $isTestRow ? '1' : '0' }}"
                            @if($isLowStock)
                                title="{{ $lowStockMessage }}"
                                aria-label="{{ $lowStockMessage }}"
                            @endif
                        >
                            <td><strong>{{ $stock->item_name }}</strong></td>
                            <td class="text-right nowrap">
                                <strong style="color: {{ $quantityColor }};{{ $quantityBold ? ' font-weight:700;' : '' }}">
                                    {{ number_format($stock->quantity, 2) }}
                                </strong>
                                @if($isLowStock)
                                    <span
                                        class="ms-1 low-stock-badge"
                                        role="img"
                                        aria-label="{{ $lowStockMessage }}"
                                        title="{{ $lowStockMessage }}"
                                    >
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </span>
                                @endif
                            </td>
                            <td>{{ $stock->unit ?? '—' }}</td>
                            <td class="text-right nowrap">{{ number_format($stock->min_stock_level, 2) }}</td>
                            <td>
                                @if($isOutOfStock)
                                    <span class="badge badge-danger">{{ __('messages.out_of_stock_alert_title') }}</span>
                                @elseif($isLowStock)
                                    <span class="badge badge-warning">{{ __('messages.low_stock_alert') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('messages.status') }}</span>
                                @endif
                            </td>
                            @if($canManageStock)
                            <td>
                                <button class="btn" style="padding:6px 10px; font-size:12px; background:#28a745;" onclick="editStock(this)">
                                    <i class="fa fa-edit"></i> {{ __('messages.edit') }}
                                </button>
                                <button type="button" class="btn btn-danger" style="padding:6px 10px; font-size:12px;" onclick="deleteStock({{ $stock->id }}, '{{ addslashes($stock->item_name) }}')">
                                    <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                                </button>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
            <div style="text-align:center; padding:40px 20px; color:#999;">
                <p>{{ __('messages.no_stock_items_found') }}</p>
            </div>
            @endif
        </div>

    </div>
</div>

@if($canManageStock)
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title urdu-text" id="stockModalLabel" style="font-weight:600;"><span id="formTitle">{{ ($stock ?? null) ? __('messages.edit_stock_item') : __('messages.add_stock_item') }}</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
      </div>
      <form id="stockForm" action="/stock" method="POST">
        @csrf
        <input type="hidden" id="stock_id" name="stock_id" value="">
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group row-span-2">
                    <label for="item_name" class="urdu-text">{{ __('messages.item_name') ?? 'آئٹم کا نام' }}</label>
                    <input type="text" id="item_name" name="item_name" placeholder="مثلاً: آلو، پیاز، ٹماٹر" value="{{ old('item_name') }}" required autocomplete="off" data-urdu="true" class="form-control urdu-text">
                </div>
                <div class="form-group">
                    <label for="quantity">{{ __('messages.current_quantity') }}</label>
                    <input type="number" step="0.01" min="0" id="quantity" name="quantity" value="{{ old('quantity') }}" required autocomplete="off" class="form-control">
                </div>
                <div class="form-group">
                    <label for="unit" class="urdu-text">{{ __('messages.unit') ?? 'اکائی' }}</label>
                    <input type="text" id="unit" name="unit" placeholder="کلو / بوری / کریٹ / پیسز" value="{{ old('unit') }}" autocomplete="off" data-urdu="true" class="form-control urdu-text">
                </div>
                <div class="form-group">
                    <label for="min_stock_level" class="urdu-text">کم از کم اسٹاک سطح</label>
                    <input type="number" step="0.01" min="0" id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 0) }}" required autocomplete="off" class="form-control">
                    <div class="muted urdu-text">اس سطح سے کم ہونے پر الرٹ</div>
                </div>
                <div class="form-group">
                    <label for="rate" class="urdu-text">ریٹ (روپے)</label>
                    <input type="number" step="0.01" min="0" id="rate" name="rate" value="{{ old('rate') }}" placeholder="0.00" autocomplete="off" class="form-control">
                </div>
                <div class="form-group row-span-2">
                    <label for="description" class="urdu-text">{{ __('messages.description') ?? 'تفصیل' }}</label>
                    <textarea id="description" name="description" placeholder="اضافی نوٹس..." autocomplete="off" data-urdu="true" class="form-control urdu-text">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelEditBtn" data-bs-dismiss="modal">
            <i class="fa fa-rotate-right"></i> <span id="resetBtnText">{{ __('messages.reset') ?? 'ری سیٹ' }}</span>
          </button>
          <button id="submitBtn" class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> <span id="submitBtnText">{{ __('messages.add_stock_item') }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<!-- SweetAlert2 - Load before Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let initialFormState = null;

    function getFormState() {
        const form = document.getElementById('stockForm');
        if (!form) {
            return null;
        }
        const formData = new FormData(form);
        const state = {};
        formData.forEach((value, key) => {
            state[key] = value == null ? '' : String(value);
        });
        return state;
    }

    function captureInitialFormState() {
        initialFormState = getFormState();
    }

    function isFormDirty() {
        if (!initialFormState) {
            return false;
        }
        const current = getFormState();
        if (!current) {
            return false;
        }
        const keys = new Set([...Object.keys(initialFormState), ...Object.keys(current)]);
        for (const key of keys) {
            const a = initialFormState[key] ?? '';
            const b = current[key] ?? '';
            if (a !== b) {
                return true;
            }
        }
        return false;
    }

    function showRedirectOverlay(text) {
        const existing = document.getElementById('redirectOverlay');
        if (existing) {
            existing.remove();
        }
        const overlay = document.createElement('div');
        overlay.id = 'redirectOverlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.background = 'rgba(15,23,42,0.6)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = '9999';
        const box = document.createElement('div');
        box.style.background = '#111827';
        box.style.padding = '12px 20px';
        box.style.borderRadius = '8px';
        box.style.display = 'flex';
        box.style.alignItems = 'center';
        box.style.gap = '10px';
        box.style.color = '#e5e7eb';
        const icon = document.createElement('i');
        icon.className = 'fa fa-spinner fa-spin';
        const span = document.createElement('span');
        span.textContent = text;
        box.appendChild(icon);
        box.appendChild(span);
        overlay.appendChild(box);
        document.body.appendChild(overlay);
    }

    // Global functions (must be accessible from HTML onclick)
    function deleteStock(stockId, itemName) {
        const rowSelector = 'tr[data-stock-id="' + stockId + '"]';
        Swal.fire({
            title: 'کیا آپ حذف کرنا چاہتے ہیں؟',
            text: 'آپ "' + itemName + '" کو حذف کرنے والے ہیں۔ یہ عمل غیر قابل واپسی ہے۔',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ہاں، حذف کریں',
            cancelButtonText: 'منسوخ کریں'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }
            Swal.fire({
                title: 'حذف ہو رہا ہے...',
                text: '"' + itemName + '" کو حذف کیا جا رہا ہے',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            fetch('/stock/' + stockId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async (response) => {
                const isJson = response.headers.get('content-type') && response.headers.get('content-type').includes('application/json');
                const data = isJson ? await response.json().catch(() => ({})) : {};
                if (response.ok && data.success) {
                    const row = document.querySelector(rowSelector);
                    if (row) {
                        row.remove();
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'کامیابی!',
                        text: data.message || 'اسٹاک آئٹم کامیابی سے حذف ہو گیا۔',
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'ٹھیک ہے',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        const tbody = document.querySelector('#stockTable tbody');
                        if (!tbody || tbody.children.length === 0) {
                            window.location.reload();
                        }
                    });
                    return;
                }
                let message = data.message;
                if (!message && data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    if (firstKey && Array.isArray(data.errors[firstKey]) && data.errors[firstKey].length > 0) {
                        message = data.errors[firstKey][0];
                    }
                }
                if (!message) {
                    message = 'اسٹاک حذف نہیں ہو سکا۔ براہ کرم دوبارہ کوشش کریں۔';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خرابی!',
                    text: message,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'ٹھیک ہے'
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'خرابی!',
                    text: 'سرور سے رابطہ نہیں ہو سکا۔ براہ کرم دوبارہ کوشش کریں۔',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'ٹھیک ہے'
                });
            });
        });
    }

    function editStock(button) {
        const row = button.closest('tr');
        const stockId = row.dataset.stockId;
        const itemName = row.dataset.itemName || '';
        const quantity = row.dataset.quantity || '';
        const unit = row.dataset.unit || '';
        const minStockLevel = row.dataset.minStockLevel || '';
        const rate = row.dataset.rate || '';
        const description = row.dataset.description || '';

        document.getElementById('stock_id').value = stockId;
        document.getElementById('item_name').value = itemName;
        document.getElementById('quantity').value = quantity;
        document.getElementById('unit').value = unit;
        document.getElementById('min_stock_level').value = minStockLevel;
        document.getElementById('rate').value = rate;
        document.getElementById('description').value = description;

        captureInitialFormState();

        const form = document.getElementById('stockForm');
        form.action = '/stock/' + stockId;

        document.getElementById('submitBtnText').textContent = 'اسٹاک اپڈیٹ کریں';
        document.getElementById('resetBtnText').textContent = 'منسوخ کریں';
        document.getElementById('formTitle').textContent = 'اسٹاک آئٹم میں ترمیم';

        const modalEl = document.getElementById('stockModal');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }
    }

    function resetForm() {
        const form = document.getElementById('stockForm');
        if (form) {
            form.action = '/stock';
            form.reset();
            document.getElementById('stock_id').value = '';
            document.getElementById('submitBtnText').textContent = 'اسٹاک شامل کریں';
            document.getElementById('resetBtnText').textContent = 'ری سیٹ';
            document.getElementById('formTitle').textContent = 'نیا اسٹاک آئٹم شامل کریں';
            captureInitialFormState();
        }

        const modalEl = document.getElementById('stockModal');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }
    }
    // Search functionality
    (function() {
        const search = document.getElementById('stockSearch');
        const table = document.getElementById('stockTable');
        const lowStockToggle = document.getElementById('lowStockOnlyToggle');
        const showLowStockBtn = document.getElementById('showLowStockOnlyBtn');
        
        function filterRows() {
            if (!table) return;
            const q = (search?.value || '').toLowerCase().trim();
            const lowOnly = lowStockToggle && lowStockToggle.checked;
            const rows = table.querySelectorAll('tbody tr.stock-row');
            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = !q || text.includes(q);
                const isLow = row.dataset.lowStock === '1';
                const isTestRow = row.dataset.testRow === '1';
                const matchesLow = !lowOnly || isLow;
                row.style.display = (matchesSearch && matchesLow) ? '' : 'none';
            });
        }
        
        search?.addEventListener('input', filterRows);
        lowStockToggle?.addEventListener('change', filterRows);
        showLowStockBtn?.addEventListener('click', function() {
            if (lowStockToggle) {
                lowStockToggle.checked = true;
                filterRows();
            }
        });
        document.addEventListener('DOMContentLoaded', filterRows);
    })();

    // Update form submit to handle both create and update
    document.getElementById('stockForm')?.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default submission
        
        const stockId = document.getElementById('stock_id').value;
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        
        // Basic validation
        const itemName = document.getElementById('item_name').value.trim();
        const quantity = document.getElementById('quantity').value;
        const unit = document.getElementById('unit').value.trim();
        
        if (!itemName || !quantity || !unit) {
            Swal.fire({
                icon: 'error',
                title: 'خرابی!',
                text: 'براہ کرم تمام ضروری خانے پُر کریں',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'ٹھیک ہے'
            });
            return;
        }
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        const isUpdate = stockId !== '';
        
        if (isUpdate) {
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> اپڈیٹ ہو رہا ہے...';
        } else {
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> شامل ہو رہا ہے...';
        }
        
        // Determine URL for create or update
        const url = isUpdate ? '/stock/' + stockId : '/stock';
        const formData = new FormData(form);
        
        // Submit via fetch (expects JSON response)
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const currentState = getFormState();
                if (!isUpdate && currentState && window.sessionStorage) {
                    try {
                        window.sessionStorage.setItem('lastStockItem', JSON.stringify(currentState));
                    } catch (e) {}
                }
                Swal.fire({
                    icon: 'success',
                    title: 'کامیابی!',
                    text: data.message,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'ٹھیک ہے',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    const modalEl = document.getElementById('stockModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    if (!isUpdate) {
                        showRedirectOverlay('آئٹم پیج لوڈ ہو رہا ہے...');
                        window.location.href = '/items?from=stock';
                    } else {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خرابی!',
                    text: data.message || 'کوئی خرابی پیش آئی',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'ٹھیک ہے'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خرابی!',
                text: 'سرور سے رابطہ نہیں ہو سکا۔ براہ کرم دوبارہ کوشش کریں۔',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'ٹھیک ہے'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Auto-hide success message and fix modal ARIA behavior
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.transition = 'opacity 0.5s ease-out';
                successMessage.style.opacity = '0';
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 500);
            }, 7000);
        }

        if (document.getElementById('stockForm')) {
            captureInitialFormState();
        }
        
        let lastStockUpdateTs = 0;
        
        function checkForUpdates() {
            fetch('/stock/updates', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data || !data.latest_update) {
                    return;
                }
                
                if (!lastStockUpdateTs) {
                    lastStockUpdateTs = data.latest_update;
                    return;
                }
                
                if (data.latest_update > lastStockUpdateTs) {
                    lastStockUpdateTs = data.latest_update;
                    
                    const notification = document.createElement('div');
                    notification.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 8px 12px; border-radius: 6px; font-size: 12px; z-index: 9999; opacity: 0; transition: opacity 0.3s;';
                    notification.textContent = 'ڈیٹا اپڈیٹ ہو گئی';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.style.opacity = '1';
                    }, 100);
                    
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        setTimeout(() => {
                            document.body.removeChild(notification);
                            window.location.reload();
                        }, 300);
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error checking for updates:', error);
            });
        }
        
        setInterval(checkForUpdates, 30000);
        
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(checkForUpdates, 1000);
            }
        });
    });
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

</body>
</html>
