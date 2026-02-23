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
    <title>{{ __('بکری بُک') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .bakri-container { max-width: 1400px; margin: 0 auto; }

        .mandi-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--bg-color);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Compact Grid */
        .header-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-group label { font-size: 0.8rem; font-weight: 600; color: #64748b; }
        
        .form-control {
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            background: #f8fafc;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: #6366f1;
            background: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Dense Tables */
        .mandi-table-container { border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 15px; }
        .mandi-table { width: 100%; border-collapse: collapse; background: white; }
        .mandi-table th { background: #f8fafc; padding: 10px; text-align: center; font-weight: 700; font-size: 0.75rem; color: #475569; border-bottom: 2px solid #edf2f7; text-transform: uppercase; }
        .mandi-table td { padding: 6px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .mandi-table input { width: 100%; border: none; background: transparent; text-align: center; font-size: 0.9rem; padding: 4px; }
        .mandi-table input:focus { background: #f1f5f9; outline: none; border-radius: 4px; }

        /* Expense Box */
        .expense-panel {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }

        .expense-item { text-align: center; }
        .expense-item label { display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 4px; }
        .expense-value { font-weight: 700; font-size: 1rem; color: #1e293b; }

        /* Navigation Bar */
        .nav-footer {
            position: sticky;
            bottom: 20px;
            background: white;
            border-radius: 50px;
            padding: 10px 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            border: 1px solid #e2e8f0;
        }

        .btn-nav {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            color: #475569;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-nav:hover { background: #6366f1; color: white; transform: scale(1.1); }
        .btn-action { padding: 8px 20px; border-radius: 25px; border: none; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; color: white; }
        .btn-save { background: var(--success-gradient); }
        .btn-print { background: var(--accent-gradient); }
        .btn-new { background: #64748b; }

        .urdu-text { font-family: 'Noto Nastaliq Urdu', serif; line-height: 2; }
        
        /* Dark Mode */
        body.dark-mode { background: #0f172a; color: #f1f5f9; }
        body.dark-mode .topbar, body.dark-mode .mandi-card, body.dark-mode .nav-footer, body.dark-mode .expense-panel { background: #1e293b; border-color: #334155; color: #f1f5f9; }
        body.dark-mode .form-control, body.dark-mode .mandi-table { background: #0f172a; border-color: #334155; color: #f1f5f9; }
        body.dark-mode .mandi-table th { background: #1e293b; color: #94a3b8; }
        body.dark-mode #bakriBookForm .btn-nav {
            background: #334155 !important;
            color: #e2e8f0 !important;
            border: 1px solid #475569 !important;
        }
        body.dark-mode #bakriBookForm .btn-nav:hover {
            background: #6366f1 !important;
            color: #ffffff !important;
        }
        body.dark-mode #bakriBookForm .mandi-table .btn-nav[style*="fee2e2"] {
            background: #7f1d1d !important;
            color: #fecaca !important;
            border-color: #991b1b !important;
        }
        body.dark-mode #bakriBookForm .summary-box {
            background: #0f172a !important;
            border-color: #334155 !important;
        }
        body.dark-mode #bakriBookForm .summary-box label {
            color: #cbd5e1 !important;
        }
        body.dark-mode #bakriBookForm #totalExpensesDisplay {
            color: #f9a8d4 !important;
        }
        body.dark-mode #bakriBookForm #netGoatDisplay {
            color: #86efac !important;
        }
        body.dark-mode #bakriBookForm .expense-value,
        body.dark-mode #bakriBookForm .card-header h3,
        body.dark-mode #bakriBookForm .card-header h3 * {
            color: #f1f5f9 !important;
        }
        body.dark-mode #bakriBookForm .nav-footer div[style*="color: #64748b"],
        body.dark-mode .bakri-container .badge-mandi {
            color: #cbd5e1 !important;
        }
        body.dark-mode .bakri-container .badge-mandi {
            background: #1e3a8a !important;
            border: 1px solid #3b82f6 !important;
        }
        body.dark-mode .topbar .urdu-text,
        body.dark-mode .topbar .urdu-text * {
            color: #f1f5f9 !important;
        }
        
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: slideUp 0.4s ease forwards; }

        .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); display: none; justify-content: center; align-items: center; z-index: 9999; }
        .spinner { width: 50px; height: 50px; border: 5px solid #f1f5f9; border-top-color: #6366f1; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="{{ $lang === 'ur' ? 'rtl' : '' }}">

    <div class="loading-overlay" id="loadingOverlay"><div class="spinner"></div></div>

    @include('components.sidebar')

    <div class="main">
        <div class="topbar">
            <h2 style="margin: 0;" class="urdu-text">
                <i class="fa fa-file-invoice-dollar text-primary" style="margin-left: 10px;"></i>
                {{ __('بکری بُک لیجر') }}
            </h2>
            <div style="display: flex; align-items: center; gap: 15px;">
                @include('components.user-role-display')
                <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
            </div>
        </div>

        <form id="bakriBookForm" method="POST" action="{{ $currentRecord ? route('payment.update', $currentRecord->id) : route('payment.store') }}">
            @csrf
            @if($currentRecord) @method('POST') @endif
            <input type="hidden" name="record_id" id="recordId" value="{{ $currentRecord->id ?? '' }}">

            <div class="bakri-container">
                
                <!-- Section 1: Header Info & Expenses -->
                <div class="mandi-card animate-up">
                    <div class="card-header">
                        <h3><i class="fa fa-info-circle text-primary"></i> {{ __('بنیادی معلومات') }}</h3>
                        <div class="badge-mandi" style="background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 8px;">
                            ریکارڈ #<span id="currentIndexSpan">{{ $currentIndex }}</span> / {{ $totalRecords }}
                        </div>
                    </div>
                    
                    <div class="header-grid">
                        <div class="form-group">
                            <label>{{ __('تاریخ') }}</label>
                            <input type="date" name="record_date" id="recordDate" class="form-control" value="{{ $currentRecord ? $currentRecord->record_date->format('Y-m-d') : date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('وینڈر/ٹریڈر') }}</label>
                            <input type="text" name="trader" id="trader" class="form-control urdu-text" value="{{ $currentRecord->trader ?? '' }}" data-urdu="true" placeholder="بیوپاری کا نام لکھیں">
                        </div>
                        <div class="form-group">
                            <label>{{ __('بکری نمبر') }}</label>
                            <input type="text" name="goat_number" id="goatNumber" class="form-control" value="{{ $currentRecord->goat_number ?? '' }}" data-no-urdu="true">
                        </div>
                        <div class="form-group">
                            <label>{{ __('ٹرک نمبر') }}</label>
                            <input type="text" name="truck_number" id="truckNumber" class="form-control" value="{{ $currentRecord->truck_number ?? '' }}" data-no-urdu="true">
                        </div>
                    </div>

                    <div class="expense-panel">
                        <div class="form-group"><label>{{ __('کرایہ') }}</label>
                            <input type="number" step="0.01" name="fare" id="fare" class="form-control expense-input" value="{{ $currentRecord->fare ?? 0 }}"></div>
                        <div class="form-group"><label>{{ __('کمیشن') }}</label>
                            <input type="number" step="0.01" name="commission" id="commission" class="form-control expense-input" value="{{ $currentRecord->commission ?? 0 }}"></div>
                        <div class="form-group"><label>{{ __('مزدوری') }}</label>
                            <input type="number" step="0.01" name="labor" id="labor" class="form-control expense-input" value="{{ $currentRecord->labor ?? 0 }}"></div>
                        <div class="form-group"><label>{{ __('ماشیانہ') }}</label>
                            <input type="number" step="0.01" name="mashiana" id="mashiana" class="form-control expense-input" value="{{ $currentRecord->mashiana ?? 0 }}"></div>
                        <div class="form-group"><label>{{ __('سٹامپ') }}</label>
                            <input type="number" step="0.01" name="stamp" id="stamp" class="form-control expense-input" value="{{ $currentRecord->stamp ?? 0 }}"></div>
                        <div class="form-group"><label>{{ __('دیگر') }}</label>
                            <input type="number" step="0.01" name="other_expenses" id="other_expenses" class="form-control expense-input" value="{{ $currentRecord->other_expenses ?? 0 }}"></div>
                    </div>
                </div>

                <!-- Section 2: Items Table -->
                <div class="mandi-card animate-up" style="animation-delay: 0.1s;">
                    <div class="card-header">
                        <h3><i class="fa fa-boxes-stacked text-primary"></i> {{ __('اشیاء کی تفصیل') }}</h3>
                        <button type="button" class="btn-action btn-new" style="padding: 4px 12px; font-size: 0.8rem;" onclick="addRow('itemsTableBody', 'item')">
                            <i class="fa fa-plus"></i> {{ __('رو شامل کریں') }}
                        </button>
                    </div>
                    <div class="mandi-table-container">
                        <table class="mandi-table">
                            <thead>
                                <tr>
                                    <th>{{ __('کوڈ') }}</th>
                                    <th>{{ __('آئٹم کی قسم') }}</th>
                                    <th>{{ __('پیکنگ') }}</th>
                                    <th>{{ __('مقدار') }}</th>
                                    <th>{{ __('مزدوری شرح') }}</th>
                                    <th>{{ __('کل مزدوری') }}</th>
                                    <th>{{ __('کمیشن شرح') }}</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                @if($currentRecord && $currentRecord->items->count())
                                    @foreach($currentRecord->items as $idx => $item)
                                        <tr>
                                            <td><input type="text" name="items[{{$idx}}][code]" value="{{$item->code}}" data-no-urdu="true"></td>
                                            <td><input type="text" name="items[{{$idx}}][item_type]" class="urdu-text" value="{{$item->item_type}}" data-urdu="true"></td>
                                            <td><input type="text" name="items[{{$idx}}][packing]" class="urdu-text" value="{{$item->packing}}" data-urdu="true"></td>
                                            <td><input type="number" name="items[{{$idx}}][quantity]" value="{{$item->quantity}}"></td>
                                            <td><input type="number" step="0.01" name="items[{{$idx}}][labor_rate]" value="{{$item->labor_rate}}"></td>
                                            <td><input type="number" step="0.01" name="items[{{$idx}}][labor]" value="{{$item->labor}}" readonly></td>
                                            <td><input type="number" step="0.01" name="items[{{$idx}}][commission_rate]" value="{{$item->commission_rate}}"></td>
                                            <td><button type="button" class="btn-nav" style="width: 24px; height: 24px; background: #fee2e2; color: #ef4444;" onclick="removeRow(this)"><i class="fa fa-times" style="font-size: 0.7rem;"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 3: Summary & Balance -->
                <div class="mandi-card animate-up" style="animation-delay: 0.2s;">
                    <div class="header-grid" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="summary-box" style="background: #fdf2f8; border-radius: 12px; padding: 15px; text-align: center; border: 1px solid #fbcfe8;">
                            <label style="color: #9d174d; font-weight: 700;">{{ __('کل اخراجات') }}</label>
                            <div id="totalExpensesDisplay" style="font-size: 1.5rem; font-weight: 900; color: #be185d;">Rs. {{ number_format($currentRecord->total_expenses ?? 0, 2) }}</div>
                        </div>
                        <div class="summary-box" style="background: #f0fdf4; border-radius: 12px; padding: 15px; text-align: center; border: 1px solid #bbf7d0;">
                            <label style="color: #166534; font-weight: 700;">{{ __('نیٹ وصولی') }}</label>
                            <div id="netGoatDisplay" style="font-size: 1.5rem; font-weight: 900; color: #15803d;">Rs. {{ number_format($currentRecord->net_goat ?? 0, 2) }}</div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('بیلنس') }} 1</label>
                            <input type="number" step="0.01" name="balance1" id="balance1" class="form-control" value="{{ $currentRecord->balance1 ?? 0 }}" style="font-size: 1.2rem; font-weight: 700; color: #6366f1;">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 20px;">
                        <label>{{ __('اضافی تفصیلات / نوٹس') }}</label>
                        <textarea name="additional_details" id="additionalDetails" class="form-control urdu-text" rows="3" data-urdu="true"></textarea>
                    </div>
                </div>

                <!-- Fixed Navigation Footer -->
                <div class="nav-footer animate-up" style="animation-delay: 0.3s;">
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn-nav" onclick="navigate('first')" title="پہلا"><i class="fa fa-angles-right"></i></button>
                        <button type="button" class="btn-nav" onclick="navigate('prev')" title="پچھلا"><i class="fa fa-angle-right"></i></button>
                        <div style="padding: 0 10px; font-weight: 700; color: #64748b; align-self: center;">ریکارڈ {{ $currentIndex }} از {{ $totalRecords }}</div>
                        <button type="button" class="btn-nav" onclick="navigate('next')" title="اگلا"><i class="fa fa-angle-left"></i></button>
                        <button type="button" class="btn-nav" onclick="navigate('last')" title="آخری"><i class="fa fa-angles-left"></i></button>
                    </div>
                    
                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="btn-action btn-new" onclick="createNew()"><i class="fa fa-plus-circle"></i> {{ __('نیا اندراج') }}</button>
                        <button type="button" class="btn-action btn-print" onclick="window.print()"><i class="fa fa-print"></i> {{ __('پرنٹ') }}</button>
                        <button type="submit" class="btn-action btn-save"><i class="fa fa-save"></i> {{ __('محفوظ کریں') }}</button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let recordId = '{{ $currentRecord->id ?? '' }}';
        let itemCount = {{ $currentRecord ? $currentRecord->items->count() : 0 }};

        function addRow(containerId, type) {
            const container = document.getElementById(containerId);
            const row = document.createElement('tr');
            if (type === 'item') {
                row.innerHTML = `
                    <td><input type="text" name="items[${itemCount}][code]"></td>
                    <td><input type="text" name="items[${itemCount}][item_type]" class="urdu-text"></td>
                    <td><input type="text" name="items[${itemCount}][packing]"></td>
                    <td><input type="number" name="items[${itemCount}][quantity]"></td>
                    <td><input type="number" step="0.01" name="items[${itemCount}][labor_rate]"></td>
                    <td><input type="number" step="0.01" name="items[${itemCount}][labor]"></td>
                    <td><input type="number" step="0.01" name="items[${itemCount}][commission_rate]"></td>
                    <td><button type="button" class="btn-nav" style="width: 24px; height: 24px; background: #fee2e2; color: #ef4444;" onclick="removeRow(this)"><i class="fa fa-times" style="font-size: 0.7rem;"></i></button></td>
                `;
                itemCount++;
            }
            container.appendChild(row);
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
        }

        function navigate(dir) {
            document.getElementById('loadingOverlay').style.display = 'flex';
            fetch('{{ route("payment.navigate") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ direction: dir, current_id: recordId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.record) {
                    populateForm(data.record);
                    recordId = data.record.id;
                    document.getElementById('currentIndexSpan').innerText = data.index;
                    document.getElementById('bakriBookForm').action = `/payment/${recordId}`;
                }
                document.getElementById('loadingOverlay').style.display = 'none';
            });
        }

        function populateForm(r) {
            document.getElementById('recordId').value = r.id;
            document.getElementById('recordDate').value = r.record_date.split('T')[0];
            document.getElementById('trader').value = r.trader || '';
            document.getElementById('goatNumber').value = r.goat_number || '';
            document.getElementById('truckNumber').value = r.truck_number || '';
            document.getElementById('fare').value = r.fare || 0;
            document.getElementById('commission').value = r.commission || 0;
            document.getElementById('labor').value = r.labor || 0;
            document.getElementById('mashiana').value = r.mashiana || 0;
            document.getElementById('stamp').value = r.stamp || 0;
            document.getElementById('other_expenses').value = r.other_expenses || 0;
            document.getElementById('balance1').value = r.balance1 || 0;
            document.getElementById('additionalDetails').value = r.additional_details || '';
            
            // Rebuild items table
            const tbody = document.getElementById('itemsTableBody');
            tbody.innerHTML = '';
            itemCount = 0;
            if (r.items) {
                r.items.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><input type="text" name="items[${itemCount}][code]" value="${item.code || ''}"></td>
                        <td><input type="text" name="items[${itemCount}][item_type]" class="urdu-text" value="${item.item_type || ''}"></td>
                        <td><input type="text" name="items[${itemCount}][packing]" value="${item.packing || ''}"></td>
                        <td><input type="number" name="items[${itemCount}][quantity]" value="${item.quantity || 0}"></td>
                        <td><input type="number" step="0.01" name="items[${itemCount}][labor_rate]" value="${item.labor_rate || 0}"></td>
                        <td><input type="number" step="0.01" name="items[${itemCount}][labor]" value="${item.labor || 0}"></td>
                        <td><input type="number" step="0.01" name="items[${itemCount}][commission_rate]" value="${item.commission_rate || 0}"></td>
                        <td><button type="button" class="btn-nav" style="width: 24px; height: 24px; background: #fee2e2; color: #ef4444;" onclick="removeRow(this)"><i class="fa fa-times" style="font-size: 0.7rem;"></i></button></td>
                    `;
                    tbody.appendChild(row);
                    itemCount++;
                });
            }
            updateDisplays();
        }

        function createNew() {
            window.location.reload(); // Simplest way to clear
        }

        function updateDisplays() {
            const fare = parseFloat(document.getElementById('fare').value) || 0;
            const comm = parseFloat(document.getElementById('commission').value) || 0;
            const labor = parseFloat(document.getElementById('labor').value) || 0;
            const mash = parseFloat(document.getElementById('mashiana').value) || 0;
            const stamp = parseFloat(document.getElementById('stamp').value) || 0;
            const other = parseFloat(document.getElementById('other_expenses').value) || 0;
            
            const totalExp = fare + comm + labor + mash + stamp + other;
            document.getElementById('totalExpensesDisplay').innerText = 'Rs. ' + totalExp.toLocaleString(undefined, {minimumFractionDigits: 2});
            
            // Note: netGoat depends on internal 'raw_goat' logic which might be hidden here, matching model logic
            const rawGoat = parseFloat('{{ $currentRecord->raw_goat ?? 0 }}') || 0;
            const netGoat = rawGoat - totalExp;
            document.getElementById('netGoatDisplay').innerText = 'Rs. ' + netGoat.toLocaleString(undefined, {minimumFractionDigits: 2});
        }

        document.querySelectorAll('.expense-input').forEach(el => el.addEventListener('input', updateDisplays));

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
