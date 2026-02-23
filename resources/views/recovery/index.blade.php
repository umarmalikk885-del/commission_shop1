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
    <title>{{ __('وصولی') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.urdu-input-support')
    @include('components.main-content-spacing')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Noto Nastaliq Urdu', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .main {
            padding: 24px;
            transition: var(--transition);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 16px 24px;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        .recovery-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Sabzi Mandi Style Card */
        .mandi-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-color);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--text-color);
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #64748b;
        }

        .form-control {
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #6366f1;
            background: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Action Buttons */
        .btn-mandi {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            color: white;
        }

        .btn-primary-mandi {
            background: var(--primary-gradient);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-primary-mandi:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
        }

        /* Dense Table */
        .mandi-table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .mandi-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .mandi-table th {
            background: #f8fafc;
            padding: 14px;
            text-align: right;
            font-weight: 700;
            font-size: 0.85rem;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f7;
        }

        .mandi-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }

        .mandi-table tr:hover {
            background: #f1f5f9;
        }

        .badge-mandi {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-info { background: #e0f2fe; color: #075985; }

        /* Dark Mode */
        body.dark-mode {
            background-color: #0f172a;
            color: #f1f5f9;
        }

        body.dark-mode .topbar,
        body.dark-mode .mandi-card,
        body.dark-mode .mandi-table {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }

        body.dark-mode .card-header {
            border-bottom-color: #334155;
        }

        body.dark-mode .card-header h3 {
            color: #f1f5f9;
        }

        body.dark-mode .form-control {
            background: #0f172a;
            border-color: #334155;
            color: #f1f5f9;
        }

        body.dark-mode .mandi-table th {
            background: #1e293b;
            color: #94a3b8;
            border-bottom-color: #334155;
        }

        body.dark-mode .mandi-table td {
            border-bottom-color: #334155;
        }

        body.dark-mode .mandi-table tr:hover {
            background: #334155;
        }

        /* Auto-Suggest List */
        #lagaListContainer {
            position: relative;
        }
        
        .suggest-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .suggest-item {
            padding: 10px 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .suggest-item:hover {
            background: #f1f5f9;
        }

        .suggest-item .balance {
            font-weight: 700;
            color: #ef4444;
        }

        body.dark-mode .suggest-box {
            background: #1e293b;
            border-color: #334155;
        }

        body.dark-mode .suggest-item:hover {
            background: #334155;
        }

        /* Animation overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
</head>
<body class="{{ $lang === 'ur' ? 'rtl' : '' }}">
    
    @include('components.sidebar')

    <div class="main">
        <div class="topbar">
            <h2 style="margin: 0; font-family: 'Noto Nastaliq Urdu', sans-serif;">
                <i class="fa fa-receipt text-primary" style="margin-left: 10px;"></i>
                {{ __('وصولی') }}
            </h2>
            <div style="display: flex; align-items: center; gap: 15px;">
                @include('components.user-role-display')
                <button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>

        <div class="recovery-container">
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'کامیابی',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'ٹھیک ہے'
                    });
                </script>
            @endif

            <!-- Quick Recovery Form -->
            <div class="mandi-card">
                <div class="card-header">
                    <i class="fa fa-plus-circle" style="color: #6366f1; font-size: 1.5rem;"></i>
                    <h3>{{ __('نئی وصولی کا اندراج') }}</h3>
                </div>

                <form action="{{ route('recovery.store') }}" method="POST" id="recoveryForm">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group" id="lagaListContainer">
                            <label>{{ __('لاگا تلاش کریں') }}</label>
                            <input type="text" id="lagaSearch" class="form-control urdu-text" placeholder="{{ __('نام یا کوڈ لکھیں...') }}" autocomplete="off" data-urdu="true">
                            <input type="hidden" name="laga_id" id="laga_id">
                            <div id="suggestBox" class="suggest-box"></div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('موجودہ بیلنس') }}</label>
                            <input type="text" id="currentBalance" class="form-control" readonly style="background: #fee2e2; color: #b91c1c; font-weight: bold;">
                        </div>

                        <div class="form-group">
                            <label>{{ __('وصول شدہ رقم') }}</label>
                            <input type="number" name="amount" id="amount" class="form-control" required placeholder="0.00" step="0.01">
                        </div>

                        <div class="form-group">
                            <label>{{ __('تاریخ') }}</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('طریقہ ادائیگی') }}</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Cash">{{ __('نقد') }}</option>
                                <option value="Bank">{{ __('بینک') }}</option>
                                <option value="Cheque">{{ __('چیک') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label>{{ __('نوٹس / تفصیل') }}</label>
                        <textarea name="notes" class="form-control urdu-text" rows="2" placeholder="{{ __('اضافی معلومات...') }}" data-urdu="true"></textarea>
                    </div>

                    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-mandi btn-primary-mandi" style="min-width: 200px;">
                            <i class="fa fa-save"></i>
                            {{ __('محفوظ کریں') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Recoveries Table -->
            <div class="mandi-card">
                <div class="card-header">
                    <i class="fa fa-history" style="color: #64748b; font-size: 1.5rem;"></i>
                    <h3>{{ __('حالیہ وصولیاں') }}</h3>
                </div>

                <div class="mandi-table-container">
                    <table class="mandi-table">
                        <thead>
                            <tr>
                                <th>{{ __('تاریخ') }}</th>
                                <th>{{ __('لاگا') }}</th>
                                <th>{{ __('رپورٹ کوڈ') }}</th>
                                <th>{{ __('رقم') }}</th>
                                <th>{{ __('طریقہ') }}</th>
                                <th>{{ __('تفصیل') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRecoveries as $recovery)
                            <tr>
                                <td>{{ $recovery->payment_date ? $recovery->payment_date->format('d-m-Y') : '-' }}</td>
                                <td class="urdu-text" style="font-weight: 600;">{{ $recovery->laga->name ?? '-' }}</td>
                                <td><span class="badge-mandi badge-info">{{ $recovery->laga->code ?? '-' }}</span></td>
                                <td style="font-weight: 700; color: #16a34a;">Rs. {{ number_format($recovery->amount, 2) }}</td>
                                <td><span class="badge-mandi badge-warning">{{ $recovery->payment_method }}</span></td>
                                <td class="urdu-text">{{ $recovery->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                                    <i class="fa fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                    {{ __('کوئی ریکارڈ نہیں ملا') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data for auto-suggest
        const lagas = [
            @foreach($lagas as $p)
            { id: {{ $p->id }}, name: "{{ $p->name }}", code: "{{ $p->code }}", balance: {{ $p->balance }} },
            @endforeach
        ];

        const searchInput = document.getElementById('lagaSearch');
        const suggestBox = document.getElementById('suggestBox');
        const lagaIdInput = document.getElementById('laga_id');
        const balanceInput = document.getElementById('currentBalance');

        searchInput.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            if (!val) {
                suggestBox.style.display = 'none';
                return;
            }

            const filtered = lagas.filter(p => 
                p.name.toLowerCase().includes(val) || 
                p.code.toLowerCase().includes(val)
            );

            if (filtered.length > 0) {
                suggestBox.innerHTML = '';
                filtered.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'suggest-item';
                    div.innerHTML = `
                        <span><strong>${p.name}</strong> (${p.code})</span>
                        <span class="balance">Rs. ${parseFloat(p.balance).toLocaleString()}</span>
                    `;
                    div.onclick = () => selectLaga(p);
                    suggestBox.appendChild(div);
                });
                suggestBox.style.display = 'block';
            } else {
                suggestBox.style.display = 'none';
            }
        });

        function selectLaga(p) {
            searchInput.value = p.name;
            lagaIdInput.value = p.id;
            balanceInput.value = "Rs. " + parseFloat(p.balance).toLocaleString();
            suggestBox.style.display = 'none';
            document.getElementById('amount').focus();
        }

        // Close suggest box when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
                suggestBox.style.display = 'none';
            }
        });

        // Form validation
        document.getElementById('recoveryForm').addEventListener('submit', function(e) {
            if (!lagaIdInput.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'براہ کرم لاگا منتخب کریں',
                    text: 'پہلے لاگا کا نام لکھ کر فہرست سے منتخب کریں۔',
                    confirmButtonText: 'ٹھیک ہے'
                });
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
        }
    </script>
</body>
</html>
