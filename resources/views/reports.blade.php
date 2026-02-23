<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __('رپورٹس') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.urdu-input-support')
    @include('components.main-content-spacing')

    <style>
        body {
            font-family: 'Outfit', 'Noto Nastaliq Urdu', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .main { padding: 24px; }

        .topbar {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
        }

        .tabs-nav {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            padding: 10px;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            flex-wrap: wrap;
        }

        .tab-link {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            background: #f1f5f9;
            color: var(--muted-text-color);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
        }

        .tab-link.active {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }

        .mandi-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            text-align: right;
        }
        .form-control {
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            background: #f8fafc;
        }

        .btn-mandi {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            color: white;
        }

        .btn-primary { background: var(--accent-gradient); }
        .btn-success { background: var(--success-gradient); }
        .btn-secondary { background: #64748b; }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 24px;
        }

        .summary-box {
            padding: 20px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .summary-box .label { font-size: 0.8rem; color: #64748b; margin-bottom: 5px; }
        .summary-box .value { font-size: 1.4rem; font-weight: 800; color: #0f172a; }
        .filter-error { color: #b91c1c; font-size: 0.8rem; margin: 4px 0 0; }

        .mandi-table-container { border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; }
        .mandi-table { width: 100%; border-collapse: collapse; }
        .mandi-table th { background: #f8fafc; padding: 14px; text-align: inherit; font-size: 0.8rem; font-weight: 700; color: #475569; border-bottom: 2px solid #edf2f7; }
        .mandi-table td { padding: 14px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }

        .urdu-text { font-family: 'Noto Nastaliq Urdu', serif; line-height: 2; }

        @media print {
            .tabs-nav, .sidebar, .mobile-menu-btn, .topbar, .no-print { display: none !important; }
            .main { margin: 0 !important; padding: 0 !important; }
            .mandi-card { box-shadow: none; border: 1px solid #eee; }
        }
    </style>
</head>
<body class="rtl">

    <!-- Mobile Menu -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    @include('components.sidebar')

    <div class="main">
        <div class="topbar">
            <h2 class="urdu-text" style="margin: 0;"><i class="fa fa-chart-pie text-primary" style="margin-left: 10px;"></i>{{ __('رپورٹس') }}</h2>
            @include('components.user-role-display')
        </div>

        <div class="tabs-nav no-print">
            <button class="tab-link active" onclick="showTab('bakri')"><i class="fa fa-book"></i> {{ __('بکری بُک') }}</button>
            <button class="tab-link" onclick="showTab('farmer')"><i class="fa fa-tractor"></i> {{ __('وینڈر رپورٹ') }}</button>
            <button class="refresh-btn btn-mandi btn-success" style="margin-right: auto;" onclick="window.location.href='{{ route('reports') }}'"><i class="fa fa-refresh"></i> {{ __('ریفریش') }}</button>
        </div>

        <!-- Bakri Book Tab -->
        <div id="bakri-tab" class="tab-content active">
            <div class="mandi-card no-print">
                <div class="card-header">
                    <h3><i class="fa fa-calendar-check"></i> {{ __('تاریخ وار بکری بُک تلاش') }}</h3>
                </div>
                <form action="{{ route('reports') }}" method="GET" id="bakri-form">
                    <div class="search-grid">
                        <div class="form-group">
                            <label>{{ __('آغاز تاریخ') }}</label>
                            <input type="date" name="from_date" class="form-control" value="{{ $fromDate ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('آخری تاریخ') }}</label>
                            <input type="date" name="to_date" class="form-control" value="{{ $toDate ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('تلاش (بکری نمبر / بیوپاری)') }}</label>
                            <input type="text" name="search_query" class="form-control" placeholder="نمبر یا نام..." value="{{ $searchQuery ?? '' }}">
                        </div>
                        <button type="submit" class="btn-mandi btn-primary"><i class="fa fa-search"></i> {{ __('تلاش کریں') }}</button>
                    </div>
                </form>
            </div>

            @if(isset($dateResults) && $dateResults !== null)
                <div class="mandi-card">
                    <div class="card-header">
                        <h3><i class="fa fa-table"></i> {{ __('بکری بُک نتائج (تاریخ وار)') }}</h3>
                        <div class="no-print" style="display: flex; gap: 10px;">
                            <button class="btn-mandi btn-success" onclick="exportBakriBook('date')"><i class="fa fa-file-csv"></i> سی ایس وی</button>
                            <button class="btn-mandi btn-secondary" onclick="window.print()"><i class="fa fa-print"></i> {{ __('پرنٹ') }}</button>
                        </div>
                    </div>

                    <div class="summary-grid">
                        <div class="summary-box"><div class="label">{{ __('کل خام بکری') }}</div><div class="value">{{ number_format($dateTotals['raw_goat'] ?? 0, 0) }}</div></div>
                        <div class="summary-box"><div class="label">{{ __('کل اخراجات') }}</div><div class="value">{{ number_format($dateTotals['total_expenses'] ?? 0, 0) }}</div></div>
                        <div class="summary-box" style="background: #ecfdf5;"><div class="label" style="color: #047857;">{{ __('صافی بکری') }}</div><div class="value" style="color: #059669;">{{ number_format($dateTotals['net_goat'] ?? 0, 0) }}</div></div>
                        <div class="summary-box" style="background: #fffbeb;"><div class="label" style="color: #b45309;">{{ __('کل کمیشن') }}</div><div class="value" style="color: #d97706;">{{ number_format($dateTotals['commission'] ?? 0, 0) }}</div></div>
                    </div>

                    <div class="mandi-table-container">
                        <table class="mandi-table">
                            <thead>
                                <tr>
                                    <th>{{ __('تاریخ') }}</th>
                                    <th>{{ __('بکری نمبر') }}</th>
                                    <th>{{ __('بیوپاری') }}</th>
                                    <th>{{ __('خام بکری') }}</th>
                                    <th>{{ __('کل اخراجات') }}</th>
                                    <th>{{ __('صافی بکری') }}</th>
                                    <th>{{ __('کمیشن') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dateResults as $r)
                                    <tr>
                                        <td>{{ $r->record_date->format('d/m/Y') }}</td>
                                        <td><span class="badge-mandi" style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px;">{{ $r->goat_number }}</span></td>
                                        <td class="urdu-text" style="font-weight: 600;">{{ $r->trader }}</td>
                                        <td>{{ number_format($r->raw_goat, 0) }}</td>
                                        <td>{{ number_format($r->total_expenses, 0) }}</td>
                                        <td style="color: #059669; font-weight: 700;">{{ number_format($r->net_goat, 0) }}</td>
                                        <td style="color: #d97706; font-weight: 700;">{{ number_format($r->commission, 0) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">{{ __('کوئی ریکارڈ نہیں ملا') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(false)
            @endif
        </div>

        <!-- Laga Tab -->
        <div id="laga-tab" class="tab-content" style="display: none;">
            <div class="mandi-card no-print">
                <div class="card-header">
                    <h3><i class="fa fa-filter"></i> {{ __('لاگا رپورٹ تلاش') }}</h3>
                </div>
                <form action="{{ route('reports') }}" method="GET" id="laga-form">
                    <input type="hidden" name="laga_search" value="1">
                    <div class="search-grid">
                        <div class="form-group">
                            <label>{{ __('لاگا کا نام') }}</label>
                            <input type="text" name="laga_query" class="form-control" placeholder="نام تلاش کریں..." value="{{ $lagaQuery ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('کوڈ') }}</label>
                            <input type="text" name="laga_code" class="form-control" placeholder="کوڈ..." value="{{ $lagaCode ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('آئٹم نام') }}</label>
                            <input type="text" name="item_name" class="form-control" placeholder="{{ __('آئٹم تلاش کریں...') }}" value="{{ request('item_name') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('آغاز تاریخ') }}</label>
                            <input type="date" name="laga_from_date" class="form-control" value="{{ $lagaFromDate ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('آخری تاریخ') }}</label>
                            <input type="date" name="laga_to_date" class="form-control" value="{{ $lagaToDate ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('ادائیگی کی صورتحال') }}</label>
                            <select name="payment_status" class="form-control">
                                <option value="">{{ __('تمام') }}</option>
                                <option value="paid" {{ (request('payment_status') === 'paid') ? 'selected' : '' }}>{{ __('مکمل ادائیگی') }}</option>
                                <option value="partial" {{ (request('payment_status') === 'partial') ? 'selected' : '' }}>{{ __('جزوی ادائیگی') }}</option>
                                <option value="unpaid" {{ (request('payment_status') === 'unpaid') ? 'selected' : '' }}>{{ __('بقیہ') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('کم از کم رقم') }}</label>
                            <input type="number" step="0.01" name="min_total" class="form-control" value="{{ request('min_total') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('زیادہ سے زیادہ رقم') }}</label>
                            <input type="number" step="0.01" name="max_total" class="form-control" value="{{ request('max_total') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('ریکارڈ فی صفحہ') }}</label>
                            <select name="per_page" class="form-control" onchange="this.form.submit()">
                                <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: flex-end;">
                            <button type="submit" class="btn-mandi btn-primary"><i class="fa fa-search"></i> {{ __('تلاش') }}</button>
                            <a href="{{ route('reports') }}?laga_search=1" class="btn-mandi btn-secondary" style="text-decoration: none;"><i class="fa fa-times"></i> {{ __('صاف') }}</a>
                            @if(isset($lagaResults) && count($lagaResults) > 0)
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'laga_csv']) }}" class="btn-mandi btn-success"><i class="fa fa-download"></i> سی ایس وی</a>
                            @endif
                        </div>
                    </div>
                </form>
                <div id="laga-filter-error" class="filter-error" aria-live="polite"></div>
            </div>

            <div id="loading-spinner" style="display: none; text-align: center; padding: 40px;">
                <i class="fa fa-spinner fa-spin" style="font-size: 3rem; color: #4f46e5;"></i>
                <p style="margin-top: 10px; color: #64748b;">{{ __('تلاش جاری ہے...') }}</p>
            </div>

            <div id="laga-results-container">
                @include('partials.laga-report-results')
            </div>
        </div>

        <!-- Farmer Tab -->
        <div id="farmer-tab" class="tab-content" style="display: none;">
            <div class="mandi-card no-print">
                <div class="card-header">
                    <h3><i class="fa fa-tractor"></i> {{ __('وینڈر / فارمر رپورٹ تلاش') }}</h3>
                </div>
                <form action="{{ route('reports') }}" method="GET" id="farmer-form">
                    <input type="hidden" name="farmer_search" value="1">
                    <div class="search-grid">
                        <div class="form-group">
                            <label>{{ __('وینڈر / فارمر') }}</label>
                            <select name="farmer_id" class="form-control">
                                <option value="">{{ __('منتخب کریں') }}</option>
                                @foreach($farmers as $farmer)
                                    <option value="{{ $farmer->id }}" {{ ($farmerId ?? '') == $farmer->id ? 'selected' : '' }}>{{ $farmer->name }} ({{ $farmer->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('آغاز تاریخ') }}</label>
                            <input type="date" name="farmer_from_date" class="form-control" value="{{ $farmerFromDate ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('آخری تاریخ') }}</label>
                            <input type="date" name="farmer_to_date" class="form-control" value="{{ $farmerToDate ?? '' }}">
                        </div>
                        <button type="submit" class="btn-mandi btn-primary"><i class="fa fa-search"></i> {{ __('تلاش کریں') }}</button>
                    </div>
                </form>
            </div>

            @if(isset($farmerResults) && $farmerResults !== null)
                <div class="mandi-card">
                    <div class="card-header">
                        <h3><i class="fa fa-list"></i> {{ __('وینڈر رپورٹ نتائج') }}</h3>
                        <div class="no-print" style="display: flex; gap: 10px;">
                            <button class="btn-mandi btn-success" onclick="exportFarmer('farmer_csv')"><i class="fa fa-file-csv"></i> سی ایس وی</button>
                            <button class="btn-mandi btn-secondary" onclick="window.print()"><i class="fa fa-print"></i> {{ __('پرنٹ') }}</button>
                        </div>
                    </div>

                    <div class="summary-grid">
                        <div class="summary-box"><div class="label">{{ __('کل آئٹمز') }}</div><div class="value">{{ number_format($farmerTotals['items_sold'] ?? 0) }}</div></div>
                        <div class="summary-box" style="background: #eff6ff;"><div class="label" style="color: #1e40af;">{{ __('کل رقم') }}</div><div class="value" style="color: #1d4ed8;">{{ number_format($farmerTotals['total_amount'] ?? 0) }}</div></div>
                        <div class="summary-box" style="background: #ecfdf5;"><div class="label" style="color: #047857;">{{ __('ادائیگی') }}</div><div class="value" style="color: #059669;">{{ number_format($farmerTotals['paid_amount'] ?? 0) }}</div></div>
                    </div>

                    <div class="mandi-table-container">
                        <table class="mandi-table">
                            <thead>
                                <tr>
                                    <th>{{ __('تاریخ') }}</th>
                                    <th>{{ __('بل #') }}</th>
                                    <th>{{ __('خریدار') }}</th>
                                    <th>{{ __('آئٹم') }}</th>
                                    <th>{{ __('تعداد') }}</th>
                                    <th>{{ __('ریٹ') }}</th>
                                    <th>{{ __('کل رقم') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($farmerResults as $f)
                                    <tr>
                                        <td>{{ $f->purchase_date->format('d/m/Y') }}</td>
                                        <td>{{ $f->bill_number }}</td>
                                        <td class="urdu-text">{{ $f->customer_name }}</td>
                                        <td>{{ $f->item_name }}</td>
                                        <td>{{ number_format($f->quantity, 2) }}</td>
                                        <td>{{ number_format($f->rate, 2) }}</td>
                                        <td>{{ number_format($f->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" style="text-align: center;">{{ __('کوئی ریکارڈ نہیں ملا') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <script>
        function showTab(id) {
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            document.getElementById(id + '-tab').style.display = 'block';
            // Find the button that calls this function with this id
            const btn = document.querySelector(`button[onclick="showTab('${id}')"]`);
            if(btn) btn.classList.add('active');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }

        function exportBakriBook(type) {
             const form = document.querySelector('#bakri-tab form');
             const url = new URL(form.action);
             const params = new URLSearchParams(new FormData(form));
             
             if(type === 'date') {
                 params.set('export', 'bakri_date_csv');
             } else if(type === 'search') {
                 params.set('export', 'bakri_search_csv');
             }
             
             window.location.href = url.toString() + '?' + params.toString();
        }

        function exportFarmer(type) {
            const form = document.getElementById('farmer-form');
            const url = new URL(form.action);
            const params = new URLSearchParams(new FormData(form));
            params.set('export', type);
            window.location.href = url.toString() + '?' + params.toString();
        }

        @if(request()->has('farmer_id') || request()->has('farmer_search'))
            showTab('farmer');
        @endif

        function exportLaga(type) {
            const form = document.getElementById('laga-form');
            const url = new URL(form.action);
            const params = new URLSearchParams(new FormData(form));
            params.set('export', type);
            window.location.href = url.toString() + '?' + params.toString();
        }

        @if(request()->has('laga_search') || request()->has('laga_query') || request()->has('laga_code'))
            showTab('laga');
        @endif

        // Debounce Utility
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        // AJAX Search Function
        function fetchLagaResults(url = null) {
            const form = document.getElementById('laga-form');
            const container = document.getElementById('laga-results-container');
            const spinner = document.getElementById('loading-spinner');
            const errorBox = document.getElementById('laga-filter-error');
            
            if (errorBox) {
                errorBox.textContent = '';
            }

            const fromInput = form.querySelector('input[name="laga_from_date"]');
            const toInput = form.querySelector('input[name="laga_to_date"]');
            const nameInput = form.querySelector('input[name="laga_query"]');

            const fromVal = fromInput ? fromInput.value : '';
            const toVal = toInput ? toInput.value : '';
            const nameVal = nameInput ? nameInput.value.trim() : '';

            const errors = [];

            if (fromVal && toVal && fromVal > toVal) {
                errors.push('آغاز تاریخ آخری تاریخ سے بڑی نہیں ہو سکتی');
            }

            if (nameVal && nameVal.length < 2) {
                errors.push('نام کی تلاش کم از کم دو حروف پر مشتمل ہونی چاہیے');
            }

            if (errors.length > 0) {
                if (errorBox) {
                    errorBox.textContent = errors.join('، ');
                }
                return;
            }

            // If url is not provided, build it from form
            let fetchUrl;
            if (url) {
                fetchUrl = url;
            } else {
                const params = new URLSearchParams(new FormData(form));
                fetchUrl = form.action + '?' + params.toString();
            }

            // Show Spinner
            spinner.style.display = 'block';
            container.style.opacity = '0.5';

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                spinner.style.display = 'none';
                container.style.opacity = '1';
                
                // Re-attach pagination listeners
                attachPaginationListeners();
            })
            .catch(error => {
                console.error('Error:', error);
                spinner.style.display = 'none';
                container.style.opacity = '1';
            });
        }

        function attachPaginationListeners() {
            document.querySelectorAll('#laga-results-container .pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchLagaResults(this.href);
                });
            });
        }

        // Attach Debounced Listeners
        document.addEventListener('DOMContentLoaded', function() {
            const debouncedFetch = debounce(() => fetchLagaResults(), 300);
            
            const inputs = document.querySelectorAll('#laga-form input, #laga-form select');
            inputs.forEach(input => {
                input.addEventListener('input', debouncedFetch);
                input.addEventListener('change', debouncedFetch); // For date/select
            });

            // Handle Form Submit (prevent default and use AJAX)
            document.getElementById('laga-form').addEventListener('submit', function(e) {
                e.preventDefault();
                fetchLagaResults();
            });
            
            // Initial Attach
            attachPaginationListeners();
        });
    </script>
</body>
</html>
