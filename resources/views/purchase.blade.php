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
    <title>{{ __('خریداری') }} | کمیشن شاپ</title>
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

        .purchase-layout {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1200px) {
            .purchase-layout { grid-template-columns: 1fr; }
        }

        /* Mandi Card Styling */
        .mandi-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-color);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Styling */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
        }

        .form-control {
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
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

        /* Items Section */
        .items-section {
            grid-column: 1 / -1;
            background: #f8fafc;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-top: 10px;
        }

        .item-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1.2fr 1fr 1fr auto;
            gap: 8px;
            margin-bottom: 10px;
            align-items: end;
        }

        .btn-add-item {
            background: var(--primary-gradient);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        .btn-remove {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-remove:hover { background: #fee2e2; transform: scale(1.1); }

        /* Summary Section */
        .summary-grid {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
        }

        .summary-box {
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            background: #f8fafc;
        }

        .summary-label { font-size: 0.85rem; color: #64748b; margin-bottom: 4px; }
        .summary-value { font-size: 1.25rem; font-weight: 800; }

        /* Table Styling */
        .mandi-table-container {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .mandi-table { width: 100%; border-collapse: collapse; }
        .mandi-table th { background: #f8fafc; padding: 14px; text-align: inherit; font-weight: 700; font-size: 0.8rem; color: #475569; text-transform: uppercase; border-bottom: 2px solid #edf2f7; }
        .mandi-table td { padding: 14px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .mandi-table tr:hover { background: #f8fafc; cursor: pointer; }

        /* Utility */
        .urdu-text { font-family: 'Noto Nastaliq Urdu', serif; line-height: 2; }
        .btn-mandi { padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: var(--transition); border: none; color: white; display: inline-flex; align-items: center; gap: 8px; }
        .btn-save { background: var(--success-gradient); box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); }
        .btn-reset { background: #64748b; }

        /* Dark Mode */
        body.dark-mode { background: #0f172a; color: #f1f5f9; }
        body.dark-mode .topbar, body.dark-mode .mandi-card, body.dark-mode .items-section, body.dark-mode .summary-box { background: #1e293b; border-color: #334155; color: #f1f5f9; }
        body.dark-mode .form-control { background: #0f172a; border-color: #334155; color: #f1f5f9; }
        body.dark-mode .mandi-table th { background: #1e293b; color: #94a3b8; border-bottom-color: #334155; }
        body.dark-mode .mandi-table td { border-bottom-color: #334155; }

    </style>
</head>
<body class="{{ $lang === 'ur' ? 'rtl' : '' }}">

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    @include('components.sidebar')

    <div class="main">
        <!-- Topbar -->
        <div class="topbar">
            <h2 style="margin: 0;" class="urdu-text">
                <i class="fa fa-cart-shopping text-primary" style="margin-left: 10px;"></i>
                {{ __('منڈی خریداری') }}
            </h2>
            <div style="display: flex; align-items: center; gap: 15px;">
                @include('components.user-role-display')
            </div>
        </div>

        <div class="purchase-layout">
            
            <!-- Left: Purchase Form -->
            <div class="mandi-card">
                <div class="card-header">
                    <h3><i class="fa fa-plus-circle" style="color: #6366f1;"></i> {{ __('نیا لاگا') }}</h3>
                </div>

                <form id="purchaseForm" action="/purchase" method="POST">
                    @csrf
                    <input type="hidden" id="purchase_id" name="purchase_id" value="{{ isset($purchase) ? $purchase->id : '' }}">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>{{ __('تاریخ') }}</label>
                            <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="{{ old('purchase_date', isset($purchase) ? $purchase->purchase_date->toDateString() : now()->toDateString()) }}" required>
                        </div>

                        <div class="form-group">
                            <label>{{ __('وینڈر') }}</label>
                            <select id="vendor_id" name="vendor_id" class="form-control" required>
                                <option value="">{{ __('وینڈر منتخب کریں') }}</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ (string)old('vendor_id', isset($purchase) ? $purchase->vendor_id : '') === (string)$vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }} ({{ $vendor->status === 'active' ? 'فعال' : 'بلاک' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('لاگا کا نام') }}</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control urdu-text" value="{{ old('customer_name', isset($purchase) ? $purchase->customer_name : '') }}" data-urdu="true" placeholder="لاگا کا نام لکھیں">
                        </div>

                        <div class="items-section">
                            <label style="font-weight: 700; color: #475569; display: block; margin-bottom: 12px;">{{ __('اشیاء کی تفصیل') }}</label>
                            <div id="itemsList">
                                @php
                                    $oldItems = old('items');
                                    if (empty($oldItems) || !is_array($oldItems)) {
                                        if (isset($purchase) && $purchase->items && $purchase->items->count() > 0) {
                                            $oldItems = $purchase->items->map(function($item) {
                                                return ['item_name' => $item->item_name, 'quantity' => $item->quantity, 'unit' => $item->unit, 'rate' => $item->rate, 'amount' => $item->amount];
                                            })->toArray();
                                        } elseif (isset($purchase) && $purchase->item_name) {
                                            $oldItems = [['item_name' => $purchase->item_name, 'quantity' => $purchase->quantity, 'unit' => $purchase->unit, 'rate' => $purchase->rate, 'amount' => $purchase->total_amount]];
                                        } else {
                                            $oldItems = [];
                                        }
                                    }
                                @endphp
                                @foreach($oldItems as $index => $item)
                                    <div class="item-row fade-in">
                                        <div class="form-group"><label style="font-size: 0.75rem;">{{ __('آئٹم') }}</label>
                                            <input type="text" name="items[{{ $index }}][item_name]" value="{{ $item['item_name'] ?? '' }}" class="item-name form-control urdu-text" required list="itemsListOptions" data-urdu="true"></div>
                                        <div class="form-group"><label style="font-size: 0.75rem;">{{ __('مقدار') }}</label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? '' }}" class="item-qty form-control" required></div>
                                        <div class="form-group"><label style="font-size: 0.75rem;">{{ __('یونٹ') }}</label>
                                            <select name="items[{{ $index }}][unit]" class="item-unit form-control">
                                                <option value="">{{ __('یونٹ') }}</option>
                                                <option value="kg" {{ ($item['unit'] ?? '') == 'kg' ? 'selected' : '' }}>کلو</option>
                                                <option value="bag" {{ ($item['unit'] ?? '') == 'bag' ? 'selected' : '' }}>تھیلا</option>
                                                <option value="crate" {{ ($item['unit'] ?? '') == 'crate' ? 'selected' : '' }}>کریٹ</option>
                                                <option value="piece" {{ ($item['unit'] ?? '') == 'piece' ? 'selected' : '' }}>عدد</option>
                                                <option value="dozen" {{ ($item['unit'] ?? '') == 'dozen' ? 'selected' : '' }}>درجن</option>
                                            </select></div>
                                        <div class="form-group"><label style="font-size: 0.75rem;">{{ __('ریٹ') }}</label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][rate]" value="{{ $item['rate'] ?? '' }}" class="item-rate form-control" required></div>
                                        <div class="form-group"><label style="font-size: 0.75rem;">{{ __('رقم') }}</label>
                                            <input type="number" step="0.01" name="items[{{ $index }}][amount]" value="{{ $item['amount'] ?? '' }}" class="item-amount form-control" readonly></div>
                                        <button type="button" class="btn-remove" onclick="removeItem(this)"><i class="fa fa-times"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn-add-item" onclick="addItem()">
                                <i class="fa fa-plus-circle"></i> {{ __('مزید شامل کریں') }}
                            </button>
                        </div>

                        <div class="summary-grid">
                            <div class="summary-box">
                                <div class="summary-label">{{ __('کل رقم') }}</div>
                                <div class="summary-value" id="total_display">Rs. 0.00</div>
                            </div>
                            <div class="summary-box" style="background: #fffbeb;">
                                <div class="summary-label" style="color: #92400e;">{{ __('کمیشن') }}</div>
                                <input type="number" step="0.01" id="commission_amount" name="commission_amount" class="form-control" value="{{ old('commission_amount', 0) }}" readonly style="text-align: center; font-weight: 800; font-size: 1rem; border: none; background: transparent;">
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label>{{ __('ادا شدہ رقم') }}</label>
                                <input type="number" step="0.01" id="paid_amount" name="paid_amount" class="form-control" value="{{ old('paid_amount', 0) }}" style="font-weight: 700; font-size: 1.1rem; color: #10b981;">
                            </div>
                            <div class="summary-box" style="grid-column: 1 / -1; background: #fee2e2;">
                                <div class="summary-label" style="color: #b91c1c;">{{ __('بقایا') }}</div>
                                <div class="summary-value" id="dues_display" style="color: #ef4444;">Rs. 0.00</div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; justify-content: flex-end;">
                        <button type="button" class="btn-mandi btn-reset" onclick="resetForm()">
                            <i class="fa fa-rotate-right"></i> {{ __('ری سیٹ') }}
                        </button>
                        <button type="submit" class="btn-mandi btn-save">
                            <i class="fa fa-save"></i> {{ __('خریداری محفوظ کریں') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Purchases Register -->
            <div id="searchResultsContainer" style="display: none;"></div>
            <div class="mandi-card" id="defaultRegisterCard">
                <div class="card-header">
                    <h3><i class="fa fa-list-ul" style="color: #4f46e5;"></i> {{ __('لاگا رجسٹر') }}</h3>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <input id="purchaseSearch" type="text" class="form-control" placeholder="{{ __('رجسٹر میں تلاش کریں...') }}">
                </div>

                <!-- Search Stats Panel -->
                <div id="purchaseStats" style="display: none; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <div style="text-align: center;">
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">{{ __('کل ریکارڈز') }}</div>
                        <div id="stat_count" style="font-weight: 700; font-size: 1.1rem; color: #1e293b;">0</div>
                    </div>
                    <div style="text-align: center; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">{{ __('کل رقم') }}</div>
                        <div id="stat_total" style="font-weight: 700; font-size: 1.1rem; color: #4f46e5;">0</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">{{ __('بقایا') }}</div>
                        <div id="stat_dues" style="font-weight: 700; font-size: 1.1rem; color: #ef4444;">0</div>
                    </div>
                </div>

                <div class="mandi-table-container">
                    <table class="mandi-table" id="purchaseTable">
                        <thead>
                            <tr>
                                <th>{{ __('بل نمبر') }}</th>
                                <th>{{ __('تاریخ') }}</th>
                                <th>{{ __('وینڈر') }}</th>
                                <th>{{ __('اشیاء') }}</th>
                                <th>{{ __('کل رقم') }}</th>
                                <th>{{ __('ایکشن') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $p)
                                <tr onclick="fillFormFromRow(this)" 
                                    data-id="{{ $p->id }}" 
                                    data-date="{{ $p->purchase_date->format('Y-m-d') }}"
                                    data-vendor="{{ $p->vendor_id }}"
                                    data-customer="{{ $p->customer_name }}"
                                    data-laga-code="{{ $p->laga_code }}"
                                    data-commission="{{ $p->commission_amount }}"
                                    data-paid="{{ $p->paid_amount }}"
                                    data-total="{{ $p->total_amount }}">
                                    <td><span class="badge-mandi" style="background: #f1f5f9; color: #475569;">#{{ $p->bill_number }}</span></td>
                                    <td>{{ $p->purchase_date->format('d/m/Y') }}</td>
                                    <td class="urdu-text" style="font-weight: 600;">{{ $p->vendor->name }}</td>
                                    <td style="font-size: 0.8rem; color: #64748b;">
                                        @if($p->items->count()) {{ $p->items->pluck('item_name')->implode(', ') }} @else {{ $p->item_name }} @endif
                                    </td>
                                    <td style="font-weight: 700;">Rs. {{ number_format($p->total_amount, 2) }}</td>
                                    <td>
                                        <button class="btn-remove" style="width: 30px; height: 30px; background: #e0f2fe; color: #0369a1;" onclick="window.open('/purchase/{{ $p->id }}/print', '_blank')">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 15px;">
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>
    </div>

    <datalist id="itemsListOptions">
        @foreach($items as $i) <option value="{{ $i->name }}">{{ $i->code }}</option> @endforeach
    </datalist>

    <script>
        let itemCount = document.querySelectorAll('#itemsList .item-row').length;
        const vendorCommissions = {@foreach($vendors as $v) "{{ $v->id }}": {{ $v->commission_rate ?? 0 }}, @endforeach};

        function addItem() {
            const list = document.getElementById('itemsList');
            const row = document.createElement('div');
            row.className = 'item-row fade-in';
            row.innerHTML = `
                <div class="form-group"><label style="font-size: 0.75rem;">{{ __('آئٹم') }}</label>
                <input type="text" name="items[${itemCount}][item_name]" placeholder="{{ __('آئٹم کا نام') }}" class="item-name form-control urdu-text" required list="itemsListOptions" data-urdu="true"></div>
                <div class="form-group"><label style="font-size: 0.75rem;">{{ __('مقدار') }}</label>
                <input type="number" step="0.01" name="items[${itemCount}][quantity]" placeholder="0.00" class="item-qty form-control" required></div>
                <div class="form-group"><label style="font-size: 0.75rem;">{{ __('یونٹ') }}</label>
                <select name="items[${itemCount}][unit]" class="item-unit form-control">
                    <option value="">{{ __('یونٹ') }}</option><option value="kg">کلو</option><option value="bag">تھیلا</option><option value="crate">کریٹ</option><option value="piece">عدد</option><option value="dozen">درجن</option>
                </select></div>
                <div class="form-group"><label style="font-size: 0.75rem;">{{ __('ریٹ') }}</label>
                <input type="number" step="0.01" name="items[${itemCount}][rate]" placeholder="0.00" class="item-rate form-control" required></div>
                <div class="form-group"><label style="font-size: 0.75rem;">{{ __('رقم') }}</label>
                <input type="number" step="0.01" name="items[${itemCount}][amount]" placeholder="0.00" class="item-amount form-control" readonly></div>
                <button type="button" class="btn-remove" onclick="removeItem(this)"><i class="fa fa-times"></i></button>
            `;
            list.appendChild(row);
            itemCount++;
            attachEvents(row);
            // Auto-focus the first input of the new row
            setTimeout(() => row.querySelector('input').focus(), 50);
        }

        function removeItem(btn) {
            if (document.querySelectorAll('#itemsList .item-row').length > 0) {
                btn.closest('.item-row').remove();
                calculateFinal();
            }
        }

        function attachEvents(row) {
            const qty = row.querySelector('.item-qty');
            const rate = row.querySelector('.item-rate');
            const unit = row.querySelector('.item-unit');
            [qty, rate, unit].forEach(el => el.addEventListener('input', () => calculateItem(row)));
        }

        function calculateItem(row) {
            const q = parseFloat(row.querySelector('.item-qty').value) || 0;
            const r = parseFloat(row.querySelector('.item-rate').value) || 0;
            const u = row.querySelector('.item-unit').value;
            let amt = q * r;
            if (u === 'dozen') amt = (q * 12) * r;
            row.querySelector('.item-amount').value = amt.toFixed(2);
            calculateFinal();
        }

        function calculateFinal() {
            let total = 0;
            document.querySelectorAll('.item-amount').forEach(el => total += parseFloat(el.value) || 0);
            document.getElementById('total_display').innerText = 'Rs. ' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
            
            const vId = document.getElementById('vendor_id').value;
            const commRate = vendorCommissions[vId] || 0;
            const commAmt = (total * commRate) / 100;
            document.getElementById('commission_amount').value = commAmt.toFixed(2);

            const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            const dues = total - paid;
            document.getElementById('dues_display').innerText = 'Rs. ' + dues.toLocaleString(undefined, {minimumFractionDigits: 2});
        }

        document.getElementById('vendor_id').addEventListener('change', calculateFinal);
        document.getElementById('paid_amount').addEventListener('input', calculateFinal);
        document.querySelectorAll('#itemsList .item-row').forEach(row => attachEvents(row));

        function fillFormFromRow(row) {
            // Complex fill logic here if needed, or just redirect to edit
            // For now, let's just highlight it or populate basic fields
            document.getElementById('purchase_id').value = row.dataset.id;
            document.getElementById('purchase_date').value = row.dataset.date;
            document.getElementById('vendor_id').value = row.dataset.vendor;
            document.getElementById('customer_name').value = row.dataset.customer;
            document.getElementById('paid_amount').value = row.dataset.paid;
            // Note: Populating items dynamically requires more JS, usually better to reload page or use partials
        }

        function resetForm() {
            window.location.href = '/purchase';
        }

        // Live Search & Stats
        const searchInput = document.getElementById('purchaseSearch');
        const defaultCard = document.getElementById('defaultRegisterCard');
        const resultsContainer = document.getElementById('searchResultsContainer');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();

            if (q === '') {
                resultsContainer.style.display = 'none';
                defaultCard.style.display = 'block';
                resultsContainer.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                const isCode = /^\d+$/.test(q);
                const params = new URLSearchParams();
                
                if (isCode) {
                    params.append('laga_code', q);
                } else {
                    params.append('laga_query', q);
                }
                
                // Add common params to match report structure
                params.append('laga_search', '1'); 

                fetch(`/reports?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    if (html.trim()) {
                        defaultCard.style.display = 'none';
                        resultsContainer.innerHTML = html;
                        resultsContainer.style.display = 'block';
                    } else {
                        // If no results or empty response, maybe show "No results" or keep default?
                        // Reports controller usually returns the partial if ajax.
                        // If empty, it might mean no match? 
                        // Actually the partial handles "No records found".
                        resultsContainer.innerHTML = '<div class="alert alert-info">کوئی نتیجہ نہیں ملا</div>';
                    }
                })
                .catch(err => console.error('Search failed:', err));
            }, 500); // 500ms debounce
        });

        // Enter key navigation for Purchase Form
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.closest('#purchaseForm')) {
                // Only hijack Enter for inputs and selects, not buttons (buttons should retain click behavior)
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
                    e.preventDefault();
                    
                    const form = document.getElementById('purchaseForm');
                    // Select all focusable inputs/selects and the specific action buttons we want to navigate to
                    // Exclude readonly fields and remove buttons
                    const focusable = Array.from(form.querySelectorAll('input:not([disabled]):not([readonly]), select:not([disabled]), button.btn-add-item, button.btn-save, button.btn-reset'));
                    
                    const index = focusable.indexOf(e.target);
                    if (index > -1 && index < focusable.length - 1) {
                        focusable[index + 1].focus();
                    }
                }
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
