<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('روکڑ') }} | کمیشن شاپ</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
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

        .main {
            padding: 24px;
        }

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

        .rokad-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Mandi Style Card */
        .mandi-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .mandi-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--bg-color);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Search Bar Grid */
        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            background: #f8fafc;
            width: 100%;
        }

        .form-control:focus {
            border-color: #6366f1;
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Custom Table Style */
        .mandi-table-container {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            margin-top: 10px;
        }

        .mandi-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mandi-table th {
            background: #f8fafc;
            padding: 16px;
            text-align: inherit;
            font-weight: 700;
            font-size: 0.85rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #edf2f7;
        }

        .mandi-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 1rem;
            vertical-align: middle;
        }

        .mandi-table tr:last-child td {
            border-bottom: none;
        }

        .mandi-table tr:hover {
            background: #f1f5f9;
        }

        /* Action Buttons */
        .btn-mandi {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            color: white;
            text-decoration: none;
        }

        .btn-primary-mandi { background: var(--accent-gradient); box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2); }
        .btn-success-mandi { background: var(--success-gradient); box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); }
        .btn-danger-mandi { background: var(--danger-gradient); box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2); }
        .btn-secondary-mandi { background: #64748b; color: white; }

        .btn-mandi:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        /* Status Badges */
        .badge-mandi {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        /* Ledger Row Styling */
        .ledger-opening { background: #f8fafc; font-weight: 700; color: #64748b; }
        .ledger-purchase { color: #f59e0b; }
        .ledger-payment { color: #10b981; }
        .ledger-balance { font-weight: 800; }

        /* Dark Mode Overrides */
        body.dark-mode { background: #0f172a; color: #f1f5f9; }
        body.dark-mode .topbar,
        body.dark-mode .mandi-card {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }
        body.dark-mode .card-header { border-bottom-color: #334155; }
        body.dark-mode .card-header h3 { color: #f1f5f9; }
        body.dark-mode .form-control {
            background: #0f172a;
            border-color: #334155;
            color: #f1f5f9;
        }
        body.dark-mode .mandi-table th { background: #1e293b; color: #94a3b8; border-bottom-color: #334155; }
        body.dark-mode .mandi-table td { border-bottom-color: #334155; }
        body.dark-mode .mandi-table tr:hover { background: #334155; }
        body.dark-mode .ledger-opening { background: #0f172a; }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeIn 0.4s ease forwards; }

        .urdu-text { font-family: 'Noto Nastaliq Urdu', serif; line-height: 2; }

    </style>
</head>
<body class="{{ $appLanguage === 'ur' ? 'rtl' : '' }}">

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
        <i class="fa fa-bars"></i>
    </button>

    @include('components.sidebar')

    <div class="main">
        <!-- Topbar -->
        <div class="topbar">
            <h2 style="margin: 0;" class="urdu-text">
                <i class="fa fa-book-journal-whills text-primary" style="margin-left: 10px;"></i>
                {{ __('روکڑ لیجر') }}
            </h2>
            <div style="display: flex; align-items: center; gap: 15px;">
                @include('components.user-role-display')
            </div>
        </div>

        <div class="rokad-container">
            @if(session('success'))
                <script>Swal.fire({ icon: 'success', title: 'کامیابی', text: '{{ session('success') }}', confirmButtonText: 'ٹھیک ہے' });</script>
            @endif

            <!-- Filter Card -->
            <div class="mandi-card fade-in">
                <div class="card-header">
                    <h3><i class="fa fa-search" style="color: #6366f1;"></i> {{ __('تلاش اور فلٹر') }}</h3>
                    <a href="{{ route('rokad') }}" class="btn-mandi btn-secondary-mandi"><i class="fa fa-refresh"></i> {{ __('ری سیٹ') }}</a>
                </div>
                <form action="{{ route('rokad') }}" method="GET" id="searchForm" onsubmit="return validateDates()">
                    <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'name') }}">
                    <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'asc') }}">
                    <div class="search-grid">
                        <div class="form-group">
                            <label>{{ __('نام لکھیں') }}</label>
                            <input type="text" id="name_search" name="name_search" class="form-control urdu-text" placeholder="{{ __('تلاش کریں...') }}" value="{{ request('name_search') }}" list="purchaser_names" data-urdu="true">
                            <datalist id="purchaser_names">
                                @foreach($allPurchaserNames as $p)
                                    <option value="{{ $p->name }}">{{ $p->code }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>{{ __('کوڈ') }}</label>
                            <input type="text" id="code_search" name="code_search" class="form-control" placeholder="{{ __('کوڈ...') }}" value="{{ request('code_search') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('تاریخ سے') }}</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('تاریخ تک') }}</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="form-group" style="flex-direction: row; gap: 10px;">
                            <button type="submit" class="btn-mandi btn-primary-mandi" style="flex: 1;"><i class="fa fa-search"></i> {{ __('تلاش') }}</button>
                            <button type="submit" name="export" value="1" class="btn-mandi btn-success-mandi"><i class="fa fa-download"></i></button>
                        </div>
                    </div>
                </form>
                <div id="date-error" style="color: #ef4444; display: none; margin-top: 10px; font-size: 0.9rem;">{{ __('آغاز کی تاریخ اختتام سے بعد نہیں ہو سکتی۔') }}</div>
            </div>

            <!-- List Card -->
            <div class="mandi-card fade-in" style="animation-delay: 0.1s;">
                <div class="card-header">
                    <h3><i class="fa fa-users" style="color: #4f46e5;"></i> {{ __('خریدار کی فہرست') }}</h3>
                </div>
                <div class="mandi-table-container">
                    <table class="mandi-table">
                        <thead>
                            <tr>
                                <th onclick="toggleSort('code')" style="cursor: pointer;">{{ __('کوڈ') }} <i class="fa fa-sort"></i></th>
                                <th onclick="toggleSort('name')" style="cursor: pointer;">{{ __('نام') }} <i class="fa fa-sort"></i></th>
                                <th>{{ __('موبائل') }}</th>
                                <th>{{ __('کل واجبات') }}</th>
                                <th>{{ __('کل ادا شدہ') }}</th>
                                <th>{{ __('بقایا') }}</th>
                                <th>{{ __('اسٹیٹس') }}</th>
                                <th style="text-align: center;">{{ __('ایکشن') }}</th>
                            </tr>
                        </thead>
                        <tbody id="purchasers-table-body">
                            @include('partials.rokad-table-rows')
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $purchasers->withQueryString()->links() }}
                </div>
            </div>

            <!-- Transaction Details / Ledger -->
            @if(isset($purchasers) && $purchasers->count() == 1 && (request('search') || request('name_search') || request('code_search')))
                @php $purchaser = $purchasers->first(); @endphp
                <div class="mandi-card fade-in" style="animation-delay: 0.2s;">
                    <div class="card-header">
                        <div style="display: flex; flex-direction: column;">
                            <h3 class="urdu-text">{{ __('لیجر') }}: {{ $purchaser->name }}</h3>
                            <span style="font-size: 0.9rem; color: #64748b;">کوڈ: <strong>{{ $purchaser->code }}</strong> | موبائل: <strong>{{ $purchaser->mobile }}</strong></span>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'ledger']) }}" class="btn-mandi btn-success-mandi">
                                <i class="fa fa-file-csv"></i> {{ __('لیجر ڈاون لوڈ') }}
                            </a>
                            <button onclick="document.getElementById('paymentModal').style.display='block'" class="btn-mandi btn-primary-mandi">
                                <i class="fa fa-plus"></i> {{ __('ادائیگی شامل کریں') }}
                            </button>
                            <button onclick="document.getElementById('advanceModal').style.display='block'" class="btn-mandi btn-secondary-mandi">
                                <i class="fa fa-hand-holding-usd"></i> {{ __('ایڈوانس شامل کریں') }}
                            </button>
                        </div>
                    </div>

                    <div class="mandi-table-container">
                        <table class="mandi-table">
                            <thead>
                                <tr>
                                    <th>{{ __('تاریخ') }}</th>
                                    <th>{{ __('قسم') }}</th>
                                    <th>{{ __('تفصیل') }}</th>
                                    <th>{{ __('ڈیبٹ (خریداری)') }}</th>
                                    <th>{{ __('کریڈٹ (ادائیگی)') }}</th>
                                    <th>{{ __('بقایا بیلنس') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="ledger-opening">
                                    <td colspan="3" style="text-align: center;">{{ __('ابتدائی بیلنس') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="ledger-balance">Rs. {{ number_format($openingBalance ?? 0, 2) }}</td>
                                </tr>

                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                                        <td>
                                            @if($transaction->type == 'purchase')
                                                <span class="badge-mandi badge-warning">{{ __('خریداری') }}</span>
                                            @elseif($transaction->type == 'advance')
                                                <span class="badge-mandi badge-danger">{{ __('ایڈوانس') }}</span>
                                            @else
                                                <span class="badge-mandi badge-success">{{ __('ادائیگی') }}</span>
                                            @endif
                                        </td>
                                        <td class="urdu-text">{{ $transaction->description }}</td>
                                        <td class="ledger-purchase">{{ $transaction->debit > 0 ? 'Rs. '.number_format($transaction->debit, 2) : '-' }}</td>
                                        <td class="ledger-payment">{{ $transaction->credit > 0 ? 'Rs. '.number_format($transaction->credit, 2) : '-' }}</td>
                                        <td class="ledger-balance" style="color: {{ $transaction->running_balance > 0 ? '#ef4444' : '#10b981' }}">
                                            Rs. {{ number_format($transaction->running_balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr class="ledger-opening">
                                    <td colspan="3" style="text-align: center;">{{ __('اختتامی بیلنس') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="ledger-balance" style="color: {{ ($closingBalance ?? 0) > 0 ? '#ef4444' : '#10b981' }}">
                                        Rs. {{ number_format($closingBalance ?? 0, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Modal -->
                <div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.8); z-index: 1000; backdrop-filter: blur(4px);">
                    <div class="mandi-card" style="width: 90%; max-width: 550px; margin: 60px auto; position: relative; animation: fadeIn 0.3s ease;">
                        <button onclick="document.getElementById('paymentModal').style.display='none'" style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #64748b;">&times;</button>
                        <div class="card-header">
                            <h3><i class="fa fa-hand-holding-dollar" style="color: #10b981;"></i> {{ __('بقایا کی ادائیگی') }}</h3>
                        </div>
                        <form action="{{ route('rokad.payment.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="purchaser_id" value="{{ $purchaser->id }}">
                            
                            <div class="form-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label>{{ __('تاریخ') }}</label>
                                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('رقم') }}</label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label>{{ __('ادائیگی کا طریقہ') }}</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="Cash">نقد</option>
                                    <option value="Online Transfer">آن لائن ٹرانسفر</option>
                                    <option value="Check">چیک</option>
                                    <option value="Other">دیگر</option>
                                </select>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label>{{ __('نوٹس / تفصیل') }}</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="تفصیل یہاں لکھیں..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn-mandi btn-success-mandi" style="width: 100%;">
                                <i class="fa fa-check"></i> {{ __('محفوظ کریں') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Advance Modal -->
                <div id="advanceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.8); z-index: 1000; backdrop-filter: blur(4px);">
                    <div class="mandi-card" style="width: 90%; max-width: 550px; margin: 60px auto; position: relative; animation: fadeIn 0.3s ease;">
                        <button onclick="document.getElementById('advanceModal').style.display='none'" style="position: absolute; right: 20px; top: 20px; background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #64748b;">&times;</button>
                        <div class="card-header">
                            <h3><i class="fa fa-hand-holding-usd" style="color: #f97316;"></i> {{ __('کسان ایڈوانس رقم') }}</h3>
                        </div>
                        <form action="{{ route('rokad.advance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="purchaser_id" value="{{ $purchaser->id }}">
                            
                            <div class="form-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label>{{ __('تاریخ') }}</label>
                                    <input type="date" name="advance_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('ایڈوانس رقم') }}</label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label>{{ __('نوٹس / تفصیل') }}</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="تفصیل یہاں لکھیں..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn-mandi btn-primary-mandi" style="width: 100%;">
                                <i class="fa fa-check"></i> {{ __('محفوظ کریں') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }

        function validateDates() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const errorDiv = document.getElementById('date-error');

            if (start && end && start > end) {
                errorDiv.style.display = 'block';
                return false;
            }
            errorDiv.style.display = 'none';
            return true;
        }

        function toggleSort(field) {
            const currentSort = document.getElementById('sort_by').value;
            const currentOrder = document.getElementById('sort_order').value;
            
            document.getElementById('sort_by').value = field;
            document.getElementById('sort_order').value = (currentSort === field && currentOrder === 'asc') ? 'desc' : 'asc';
            document.getElementById('searchForm').submit();
        }
    </script>
</body>
</html>
