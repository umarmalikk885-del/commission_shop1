<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>سیلز - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.main-content-spacing')

    <style>
        /* Page-specific layout */
        .content {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 15px;
            align-items: start;
        }

        @media (max-width: 992px) {
            .content {
                grid-template-columns: 1fr;
            }
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 15px;
        }

        .summary-card {
            background: var(--card-bg, #fff);
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: var(--radius-md);
            padding: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .summary-card h4 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
        }

        .summary-card p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: var(--text-color, #111827);
        }

        .summary-card.today p { color: #10b981; }
        .summary-card.week p { color: #1e88e5; }
        .summary-card.monthly p { color: #8b5cf6; }
        .summary-card.total p { color: #f59e0b; }

        .dark-mode .summary-card h4 { color: #94a3b8; }

        @media (max-width: 992px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .summary-card p { font-size: 18px; }
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
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
            color: var(--text-color, #111827);
        }

        .row-span-2 { grid-column: 1 / -1; }

        @media (max-width: 992px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        /* Items List */
        .items-container {
            margin-top: 10px;
            width: 100%;
            overflow-x: auto;
        }

        .item-row {
            display: grid;
            grid-template-columns: minmax(0, 1.8fr) minmax(0, 0.9fr) minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr) auto;
            gap: 8px;
            margin-bottom: 8px;
            align-items: end;
            min-width: 600px; /* Ensure minimum width for scrolling */
        }
        
        @media (min-width: 1201px) {
            .item-row { min-width: 0; }
        }

        .item-row input[readonly] {
            background: #f9fafb;
            font-weight: 600;
        }
        
        .dark-mode .item-row input[readonly] {
            background: #334155;
        }

        .btn-add-item {
            padding: 7px 12px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            margin-top: 8px;
            width: 100%;
        }

        .btn-remove {
            padding: 7px 10px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            min-width: 35px;
            height: 38px; /* Match form-control height approx */
        }

        /* Misc */
        .total-preview {
            margin-top: 10px;
            padding: 10px 12px;
            background: #f3f4f6;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            color: #1f2937;
        }
        
        .dark-mode .total-preview {
            background: #1e293b;
            color: #e2e8f0;
        }

        .btn-row {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .btn-row .btn { flex: 1; }

        .muted {
            color: #6b7280;
            font-size: 12px;
            margin-top: 4px;
        }
        
        /* Table specific overrides */
        .invoice-row.highlighted-row {
            background-color: #fff3cd !important;
            border: 2px solid #ffc107 !important;
        }
        
        .dark-mode .invoice-row.highlighted-row {
            background-color: #856404 !important;
            border: 2px solid #ffc107 !important;
        }
        
        .invoice-row.selected {
            background: #e3f2fd !important;
            border-left: 3px solid #1e88e5;
        }
        
        .dark-mode .invoice-row.selected {
            background: #1e3a8a !important;
            border-left-color: #60a5fa;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
        }
        .badge-success { background: #e6f4ea; color: #1e7e34; }
        
        .dark-mode .badge-success { background: #059669; color: #fff; }

        @media (max-width: 768px) {
            .btn-row { flex-direction: column; }
            .btn-row .btn { width: 100%; }
        }
    </style>
    @include('components.urdu-input-support')
    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
        $isAdmin = $currentUser && ($currentUser->hasRole('Admin') || $currentUser->hasRole('Super Admin'));
        $isUser = $currentUser && $currentUser->hasRole('User');
        $isOperator = $currentUser && $currentUser->hasRole('Operator');
    @endphp
</head>
<body>

<!-- Mobile Menu Button -->
<button class="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
    <i class="fa fa-bars"></i>
</button>

<!-- Sidebar -->
@include('components.sidebar')

<!-- Main -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <input id="salesSearch" type="text" placeholder="{{ __('messages.search_bills') }}">
        @include('components.user-role-display')
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card today">
            <h4>{{ __('messages.today_sales') }}</h4>
            <p>Rs. {{ number_format($todaySales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card week">
            <h4>{{ __('messages.week_sales') }}</h4>
            <p>Rs. {{ number_format($weekSales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card monthly">
            <h4>{{ __('messages.monthly_sales') }}</h4>
            <p>Rs. {{ number_format($monthlySales ?? 0, 2) }}</p>
        </div>
        <div class="summary-card total">
            <h4>{{ __('messages.total_sales') }}</h4>
            <p>Rs. {{ number_format($totalSales ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Content -->
    <div class="content">

        <!-- Add Sale Form (Available to all authenticated users) -->
        @php
            $currentUser = auth()->user();
            $canManageSales = $currentUser && !$currentUser->hasRole('User');
        @endphp
        
        @if($canManageSales)
        <div class="card">
            <h3>{{ __('messages.add_sale') }}</h3>
            <div class="muted">{{ __('messages.record_new_sale') }}</div>

            @if(session('success'))
                <div class="alert alert-success flash-message" style="margin-top:12px; padding: 10px; border-radius: 6px; background: #d1fae5; color: #065f46;">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-error flash-message" style="margin-top:12px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #991b1b;">
                    <strong>{{ __('messages.please_fix_following') }}</strong>
                    <ul style="margin:8px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/sales" method="POST" id="salesForm" style="margin-top:12px;">
                @csrf
                <input type="hidden" id="invoice_id" name="invoice_id" value="">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="invoice_date">{{ __('messages.sale_date') }}</label>
                        <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', now()->toDateString()) }}" required autocomplete="off" class="form-control">
                    </div>

                    <div class="form-group row-span-2">
                        <label for="customer">{{ __('messages.customer') }}</label>
                        <input type="text" id="customer" name="customer" value="{{ old('customer') }}" placeholder="{{ __('messages.customer_name') }}" required autocomplete="name" class="form-control">
                    </div>
                </div>

                <datalist id="itemsListOptions">
                    @if(isset($items))
                        @foreach($items as $item)
                            <option value="{{ $item->name }}">{{ $item->code }} - {{ $item->urdu_name }} - {{ ucfirst(__($item->type)) }}</option>
                        @endforeach
                    @endif
                </datalist>

                <div class="items-container">
                    <div style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 8px;">{{ __('messages.items') }}</div>
                    <div id="itemsList">
                        @php
                            $oldItems = old('items');
                            if (empty($oldItems) || !is_array($oldItems)) {
                                $oldItems = [['item_name' => '', 'qty' => '', 'unit' => '', 'rate' => '', 'amount' => '']];
                            }
                        @endphp
                        @foreach($oldItems as $index => $item)
                            <div class="item-row">
                                <input type="text" id="item_name_{{ $index }}" name="items[{{ $index }}][item_name]" value="{{ $item['item_name'] ?? '' }}" placeholder="{{ __('messages.item_name_placeholder') }}" required autocomplete="off" class="form-control" list="itemsListOptions">
                                <input type="number" step="0.01" min="0.01" id="item_qty_{{ $index }}" name="items[{{ $index }}][qty]" value="{{ $item['qty'] ?? '' }}" placeholder="{{ __('messages.qty_placeholder') }}" class="item-qty form-control" required autocomplete="off">
                                <select id="item_unit_{{ $index }}" name="items[{{ $index }}][unit]" class="item-unit form-control" autocomplete="off">
                                    <option value="">{{ __('messages.unit') }}</option>
                                    <option value="piece" {{ ($item['unit'] ?? '') == 'piece' ? 'selected' : '' }}>{{ __('messages.piece') }}</option>
                                    <option value="dozen" {{ ($item['unit'] ?? '') == 'dozen' ? 'selected' : '' }}>{{ __('messages.dozen') }}</option>
                                    <option value="kilo" {{ ($item['unit'] ?? '') == 'kilo' ? 'selected' : '' }}>{{ __('messages.kilo') }}</option>
                                    <option value="kg" {{ ($item['unit'] ?? '') == 'kg' ? 'selected' : '' }}>{{ __('messages.kg') }}</option>
                                    <option value="bag" {{ ($item['unit'] ?? '') == 'bag' ? 'selected' : '' }}>{{ __('messages.bag') }}</option>
                                    <option value="crate" {{ ($item['unit'] ?? '') == 'crate' ? 'selected' : '' }}>{{ __('messages.crate') }}</option>
                                    <option value="box" {{ ($item['unit'] ?? '') == 'box' ? 'selected' : '' }}>{{ __('messages.box') }}</option>
                                    <option value="pack" {{ ($item['unit'] ?? '') == 'pack' ? 'selected' : '' }}>{{ __('messages.pack') }}</option>
                                </select>
                                <input type="number" step="0.01" min="0" id="item_rate_{{ $index }}" name="items[{{ $index }}][rate]" value="{{ $item['rate'] ?? '' }}" placeholder="{{ __('messages.rate_unit_placeholder') }}" class="item-rate form-control" required autocomplete="off">
                                <input type="number" step="0.01" min="0.01" id="item_amount_{{ $index }}" name="items[{{ $index }}][amount]" value="{{ $item['amount'] ?? '' }}" placeholder="{{ __('messages.amount_placeholder') }}" class="item-amount form-control" readonly autocomplete="off">
                                <button type="button" class="btn-remove" onclick="removeItem(this)" style="display:{{ count($oldItems) > 1 ? 'block' : 'none' }};">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-item" onclick="addItem()">
                        <i class="fa fa-plus"></i> {{ __('messages.add_item') }}
                    </button>
                </div>

                <div class="total-preview">
                    {{ __('messages.total') }}: Rs. <span id="totalAmount">0.00</span>
                </div>

                <div class="btn-row">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> {{ __('messages.save_sale') }}
                    </button>
                    <a class="btn btn-secondary" href="/sales">
                        <i class="fa fa-rotate-right"></i> {{ __('messages.reset') }}
                    </a>
                </div>
            </form>
        </div>
        @endif

        <!-- Sales Register -->
            <div class="card">
            <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:8px; flex-wrap:wrap;">
                <button id="printRegisterBtn" class="btn btn-secondary" onclick="printRegister()" disabled style="display:inline-flex; align-items:center; gap:6px; flex-shrink:0;">
                    <i class="fa fa-print"></i> {{ __('messages.print') }}
                </button>
                @if($canManageSales)
                <button id="editInvoiceBtn" class="btn btn-secondary" type="button" onclick="loadInvoiceForEdit()" disabled style="display:inline-flex; align-items:center; gap:6px; flex-shrink:0;">
                    <i class="fa fa-pen"></i> {{ __('messages.update') }}
                </button>
                <!-- Delete button removed -->
                @endif
                <div style="flex:1; min-width:200px;">
                    <h3 style="margin-bottom:4px; margin-top:0;">{{ __('messages.sales_register') }}</h3>
                </div>
            </div>

            @if(isset($invoices) && $invoices->count())
                <div class="table-responsive">
                    <table id="salesTable">
                        <thead>
                        <tr>
                            <th class="nowrap">{{ __('messages.date') }}</th>
                            <th>{{ __('messages.bill_number') }}</th>
                            <th>{{ __('messages.customer') }}</th>
                            <th class="text-right nowrap">{{ __('messages.items') }}</th>
                            <th class="text-right nowrap">{{ __('messages.total') }} (Rs.)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="invoice-row" data-invoice-id="{{ $invoice->id }}" id="invoice-row-{{ $invoice->id }}" style="cursor: pointer;" onclick="selectInvoice(this, {{ $invoice->id }})">
                                <td class="nowrap">{{ $invoice->invoice_date ? $invoice->invoice_date->translatedFormat('D, d/m/Y') : '—' }}</td>
                                <td>{{ $invoice->bill_no }}</td>
                                <td>{{ $invoice->customer }}</td>
                                <td class="text-right nowrap">{{ $invoice->items_count ?? $invoice->items->count() }}</td>
                                <td class="text-right nowrap"><strong>Rs. {{ number_format($invoice->total_amount, 2) }}</strong></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer" style="margin-top:10px;">
                    <div class="small-text">
                        {{ __('messages.showing_bills', ['first' => $invoices->firstItem(), 'last' => $invoices->lastItem(), 'total' => $invoices->total()]) }}
                    </div>
                    <div class="sales-pagination-wrapper">
                        {{ $invoices->links() }}
                    </div>
                </div>
            @else
                <div style="margin-top:14px; text-align:center; color:#888; padding:26px 10px;">
                    <p style="margin:0;">{{ __('messages.no_sales_recorded') }}</p>
                    <p class="muted" style="margin:8px 0 0 0;">{{ __('messages.add_first_sale_entry') }}</p>
                </div>
            @endif
        </div>

    </div>
</div>

<form id="deleteInvoiceForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // Translations for JavaScript
    const translations = {
        item_name_placeholder: @json(__('messages.item_name_placeholder')),
        qty_placeholder: @json(__('messages.qty_placeholder')),
        unit: @json(__('messages.unit')),
        piece: @json(__('messages.piece')),
        dozen: @json(__('messages.dozen')),
        kilo: @json(__('messages.kilo')),
        kg: @json(__('messages.kg')),
        bag: @json(__('messages.bag')),
        crate: @json(__('messages.crate')),
        box: @json(__('messages.box')),
        pack: @json(__('messages.pack')),
        rate_unit_placeholder: @json(__('messages.rate_unit_placeholder')),
        amount_placeholder: @json(__('messages.amount_placeholder')),
        unable_to_load_sale: @json(__('messages.unable_to_load_sale')),
        select_invoice_to_print: @json(__('messages.select_invoice_to_print')),
        no_sales_data_to_print: @json(__('messages.no_sales_data_to_print')),
        select_sale_to_delete: @json(__('messages.select_sale_to_delete')),
        select_sale_to_update: @json(__('messages.select_sale_to_update')),
        confirm_delete_sale: @json(__('messages.confirm_delete_sale')),
        sales_register: @json(__('messages.sales_register')),
        sales_register_print: @json(__('messages.sales_register_print')),
        printed_on: @json(__('messages.printed_on'))
    };

    const itemsData = {};
    @if(isset($items))
        @foreach($items as $item)
            itemsData['{{ $item->name }}'] = {
                code: '{{ $item->code }}',
                urdu_name: '{{ $item->urdu_name }}',
                unit: '{{ $item->unit }}',
                rate: {{ $item->rate ?? 0 }},
                type: '{{ $item->type }}'
            };
        @endforeach
    @endif

    let itemCount = {{ count(old('items', [['item_name' => '', 'qty' => '', 'unit' => '', 'rate' => '', 'amount' => '']])) ?: 1 }};

    function addItem() {
        const itemsList = document.getElementById('itemsList');
        const newItem = document.createElement('div');
        newItem.className = 'item-row';
        newItem.innerHTML = `
            <input type="text" id="item_name_${itemCount}" name="items[${itemCount}][item_name]" placeholder="${translations.item_name_placeholder}" required autocomplete="off" class="form-control" list="itemsListOptions">
            <input type="number" step="0.01" min="0.01" id="item_qty_${itemCount}" name="items[${itemCount}][qty]" placeholder="${translations.qty_placeholder}" class="item-qty form-control" required autocomplete="off">
            <select id="item_unit_${itemCount}" name="items[${itemCount}][unit]" class="item-unit form-control" autocomplete="off">
                <option value="">${translations.unit}</option>
                <option value="piece">${translations.piece}</option>
                <option value="dozen">${translations.dozen}</option>
                <option value="kilo">${translations.kilo}</option>
                <option value="kg">${translations.kg}</option>
                <option value="bag">${translations.bag}</option>
                <option value="crate">${translations.crate}</option>
                <option value="box">${translations.box}</option>
                <option value="pack">${translations.pack}</option>
            </select>
            <input type="number" step="0.01" min="0" id="item_rate_${itemCount}" name="items[${itemCount}][rate]" placeholder="${translations.rate_unit_placeholder}" class="item-rate form-control" required autocomplete="off">
            <input type="number" step="0.01" min="0.01" id="item_amount_${itemCount}" name="items[${itemCount}][amount]" placeholder="${translations.amount_placeholder}" class="item-amount form-control" readonly autocomplete="off">
            <button type="button" class="btn-remove" onclick="removeItem(this)">
                <i class="fa fa-times"></i>
            </button>
        `;
        itemsList.appendChild(newItem);
        itemCount++;

        // Show remove buttons if more than one item
        updateRemoveButtons();
        
        // Add event listeners to new inputs
        const qtyInput = newItem.querySelector('.item-qty');
        const unitSelect = newItem.querySelector('.item-unit');
        const rateInput = newItem.querySelector('.item-rate');
        
        qtyInput.addEventListener('input', () => calculateItemAmount(newItem));
        unitSelect.addEventListener('change', () => calculateItemAmount(newItem));
        rateInput.addEventListener('input', () => calculateItemAmount(newItem));

        const nameInput = newItem.querySelector('input[name*="[item_name]"]');
        if (nameInput) {
            nameInput.addEventListener('change', function() {
                const val = this.value;
                if (itemsData[val]) {
                    const data = itemsData[val];
                    const row = this.closest('.item-row');
                    const unitSelect = row.querySelector('.item-unit');
                    const rateInput = row.querySelector('.item-rate');
                    
                    if (unitSelect && data.unit) {
                        unitSelect.value = data.unit; 
                    }
                    if (rateInput) {
                        rateInput.value = data.rate;
                        calculateItemAmount(row);
                    }
                }
            });
        }
    }

    function removeItem(btn) {
        const itemRow = btn.closest('.item-row');
        itemRow.remove();
        updateTotal();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const itemsList = document.getElementById('itemsList');
        const itemRows = itemsList.querySelectorAll('.item-row');
        itemRows.forEach((row, index) => {
            const removeBtn = row.querySelector('.btn-remove');
            if (itemRows.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    function calculateItemAmount(itemRow) {
        const qtyInput = itemRow.querySelector('.item-qty');
        const unitSelect = itemRow.querySelector('.item-unit');
        const rateInput = itemRow.querySelector('.item-rate');
        const amountInput = itemRow.querySelector('.item-amount');
        
        const qty = parseFloat(qtyInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const unit = unitSelect.value.toLowerCase();
        
        // Handle dozen conversion: 1 dozen = 12 pieces
        let effectiveQty = qty;
        if (unit === 'dozen') {
            effectiveQty = qty * 12; // Convert dozen to pieces
        }
        
        const amount = effectiveQty * rate;
        amountInput.value = amount.toFixed(2);
        
        updateTotal();
    }

    function updateTotal() {
        const amountInputs = document.querySelectorAll('.item-amount');
        let total = 0;
        amountInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        document.getElementById('totalAmount').textContent = total.toFixed(2);
    }

    // Add event listeners to existing inputs
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight row if highlight parameter is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        const highlightId = urlParams.get('highlight');
        if (highlightId) {
            const highlightRow = document.getElementById('invoice-row-' + highlightId);
            if (highlightRow) {
                // Add highlight class
                highlightRow.classList.add('highlighted-row');
                
                // Scroll to the row
                setTimeout(() => {
                    highlightRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
                
                // Remove highlight after 8 seconds with fade-out
                setTimeout(() => {
                    highlightRow.style.transition = 'all 0.5s ease-out';
                    highlightRow.style.opacity = '0.5';
                    setTimeout(() => {
                        highlightRow.classList.remove('highlighted-row');
                        highlightRow.style.opacity = '';
                        highlightRow.style.transition = '';
                    }, 500);
                }, 8000);
            }
        }

        const itemRows = document.querySelectorAll('.item-row');
        itemRows.forEach(row => {
            const qtyInput = row.querySelector('.item-qty');
            const unitSelect = row.querySelector('.item-unit');
            const rateInput = row.querySelector('.item-rate');
            
            if (qtyInput) qtyInput.addEventListener('input', () => calculateItemAmount(row));
            if (unitSelect) unitSelect.addEventListener('change', () => calculateItemAmount(row));
            if (rateInput) rateInput.addEventListener('input', () => calculateItemAmount(row));
        });
        updateTotal();

        // Auto-hide flash messages (success / error) after 5 seconds
        const flashMessages = document.querySelectorAll('.flash-message');
        if (flashMessages.length) {
            setTimeout(() => {
                flashMessages.forEach(msg => {
                    msg.style.transition = 'opacity 0.5s ease';
                    msg.style.opacity = '0';
                    setTimeout(() => {
                        msg.style.display = 'none';
                    }, 500);
                });
            }, 5000);
        }

        // Sidebar dropdown functionality
        const submenuItems = document.querySelectorAll('.sidebar .has-submenu');
        submenuItems.forEach(function(menu) {
            const menuLink = menu.querySelector('a');
            menuLink.addEventListener('click', function(e) {
                if (e.target.closest('.submenu') || e.target.closest('.submenu-items')) {
                    return;
                }
                e.preventDefault();
                menu.classList.toggle('open');
            });
        });

        // Client-side search functionality for sales table
        const search = document.getElementById('salesSearch');
        const table = document.getElementById('salesTable');

        function filterRows() {
            if (!table) return;
            const q = (search.value || '').toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        }

        search?.addEventListener('input', filterRows);

        // Invoice selection functionality
        let selectedInvoiceId = null;

        window.selectInvoice = function(row, invoiceId) {
            // Remove previous selection
            document.querySelectorAll('.invoice-row').forEach(r => {
                r.classList.remove('selected');
            });
            
            // Add selection to clicked row
            row.classList.add('selected');
            selectedInvoiceId = invoiceId;
            
            // Enable print & edit buttons in Sales Register
            const printBtn = document.getElementById('printRegisterBtn');
            if (printBtn) {
                printBtn.disabled = false;
            }
            const editBtn = document.getElementById('editInvoiceBtn');
            if (editBtn) {
                editBtn.disabled = false;
            }
            const deleteBtn = document.getElementById('deleteInvoiceBtn');
            if (deleteBtn) {
                deleteBtn.disabled = false;
            }
        };

        window.loadInvoiceForEdit = function() {
            if (!selectedInvoiceId) {
                alert(translations.select_sale_to_update);
                return;
            }

            fetch('/sales/' + selectedInvoiceId + '/json')
                .then(response => response.json())
                .then(data => {
                    // Fill basic fields
                    const invoiceIdInput = document.getElementById('invoice_id');
                    const dateInput = document.getElementById('invoice_date');
                    const customerInput = document.getElementById('customer');
                    const itemsList = document.getElementById('itemsList');

                    if (!invoiceIdInput || !dateInput || !customerInput || !itemsList) {
                        return;
                    }

                    invoiceIdInput.value = data.id;
                    // Always set date to today when updating
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    dateInput.value = `${yyyy}-${mm}-${dd}`;
                    customerInput.value = data.customer;

                    // Clear existing items
                    itemsList.innerHTML = '';
                    itemCount = 0;

                    // Rebuild items from response
                    (data.items || []).forEach(function(item) {
                        const newItem = document.createElement('div');
                        newItem.className = 'item-row';
                        const index = itemCount;

                        const unitValue = item.unit || '';

                        newItem.innerHTML = `
                            <input type="text" name="items[${index}][item_name]" value="${item.item_name || ''}" placeholder="${translations.item_name_placeholder}" required class="form-control">
                            <input type="number" step="0.01" min="0.01" name="items[${index}][qty]" value="${item.qty || ''}" placeholder="${translations.qty_placeholder}" class="item-qty form-control" required>
                            <select name="items[${index}][unit]" class="item-unit form-control">
                                <option value="">${translations.unit}</option>
                                <option value="piece" ${unitValue === 'piece' ? 'selected' : ''}>${translations.piece}</option>
                                <option value="dozen" ${unitValue === 'dozen' ? 'selected' : ''}>${translations.dozen}</option>
                                <option value="kilo" ${unitValue === 'kilo' ? 'selected' : ''}>${translations.kilo}</option>
                                <option value="kg" ${unitValue === 'kg' ? 'selected' : ''}>${translations.kg}</option>
                                <option value="bag" ${unitValue === 'bag' ? 'selected' : ''}>${translations.bag}</option>
                                <option value="crate" ${unitValue === 'crate' ? 'selected' : ''}>${translations.crate}</option>
                                <option value="box" ${unitValue === 'box' ? 'selected' : ''}>${translations.box}</option>
                                <option value="pack" ${unitValue === 'pack' ? 'selected' : ''}>${translations.pack}</option>
                            </select>
                            <input type="number" step="0.01" min="0" name="items[${index}][rate]" value="${item.rate || ''}" placeholder="${translations.rate_unit_placeholder}" class="item-rate form-control" required>
                            <input type="number" step="0.01" min="0.01" name="items[${index}][amount]" value="${item.amount || ''}" placeholder="${translations.amount_placeholder}" class="item-amount form-control" readonly>
                            <button type="button" class="btn-remove" onclick="removeItem(this)">
                                <i class="fa fa-times"></i>
                            </button>
                        `;

                        itemsList.appendChild(newItem);
                        itemCount++;

                        // Attach listeners
                        const qtyInput = newItem.querySelector('.item-qty');
                        const unitSelect = newItem.querySelector('.item-unit');
                        const rateInput = newItem.querySelector('.item-rate');
                        if (qtyInput) qtyInput.addEventListener('input', () => calculateItemAmount(newItem));
                        if (unitSelect) unitSelect.addEventListener('change', () => calculateItemAmount(newItem));
                        if (rateInput) rateInput.addEventListener('input', () => calculateItemAmount(newItem));
                    });

                    updateRemoveButtons();
                    updateTotal();
                })
                .catch(() => {
                    alert(translations.unable_to_load_sale);
                });
        };

        window.printSelectedInvoice = function() {
            if (selectedInvoiceId) {
                // Open print view in new window
                window.open('/sales/' + selectedInvoiceId + '/print', '_blank');
            } else {
                alert(translations.select_invoice_to_print);
            }
        };

        window.printRegister = function() {
            // If a record is selected, print that specific invoice
            if (selectedInvoiceId) {
                window.printSelectedInvoice();
                return;
            }
            
            // Otherwise, print the entire register
            const printWindow = window.open('', '_blank');
            const table = document.getElementById('salesTable');
            
            if (!table) {
                alert(translations.no_sales_data_to_print);
                return;
            }

            // Get the table HTML
            const tableHTML = table.outerHTML;
            
            // Create print document
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${translations.sales_register_print}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                        }
                        h2 {
                            margin-top: 0;
                            color: #333;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        th, td {
                            padding: 10px;
                            border: 1px solid #ddd;
                            text-align: left;
                        }
                        th {
                            background-color: #f3f4f6;
                            font-weight: 600;
                        }
                        tr:nth-child(even) {
                            background-color: #f9fafb;
                        }
                        .text-right {
                            text-align: right;
                        }
                        @media print {
                            body { padding: 10px; }
                            @page { margin: 1cm; }
                        }
                    </style>
                </head>
                <body>
                    <h2>${translations.sales_register}</h2>
                    <p>${translations.printed_on} ${new Date().toLocaleString()}</p>
                    ${tableHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            
            // Wait for content to load, then print
            setTimeout(() => {
                printWindow.print();
            }, 250);
        };

        window.deleteSelectedInvoice = function() {
            if (!selectedInvoiceId) {
                alert(translations.select_sale_to_delete);
                return;
            }

            if (!confirm(translations.confirm_delete_sale)) {
                return;
            }

            const form = document.getElementById('deleteInvoiceForm');
            if (!form) return;

            form.action = '/sales/' + selectedInvoiceId;
            form.submit();
        };

        const salesTable = document.getElementById('salesTable');
        const salesTableContainer = salesTable ? salesTable.closest('.table-responsive') : null;
        const salesTableBody = salesTable ? salesTable.querySelector('tbody') : null;
        const salesPaginationWrapper = document.querySelector('.sales-pagination-wrapper');

        let salesCurrentPage = {{ $invoices->currentPage() }};
        const salesLastPage = {{ $invoices->lastPage() }};
        let salesIsLoading = false;
        const salesMaxRows = 500;

        if (salesPaginationWrapper) {
            salesPaginationWrapper.style.display = 'none';
        }

        let salesLoadingIndicator = null;
        if (salesTableContainer) {
            salesLoadingIndicator = document.createElement('div');
            salesLoadingIndicator.className = 'sales-loading-indicator';
            salesLoadingIndicator.style.textAlign = 'center';
            salesLoadingIndicator.style.padding = '8px 0';
            salesLoadingIndicator.style.color = '#6b7280';
            salesLoadingIndicator.style.display = 'none';
            salesLoadingIndicator.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('messages.loading') }}';
            salesTableContainer.parentNode.insertBefore(salesLoadingIndicator, salesTableContainer.nextSibling);
        }

        function showSalesLoading(show) {
            if (!salesLoadingIndicator) return;
            salesLoadingIndicator.style.display = show ? 'block' : 'none';
        }

        async function loadMoreSales() {
            if (salesIsLoading) return;
            if (!salesTableContainer || !salesTableBody) return;
            if (salesCurrentPage >= salesLastPage) return;

            salesIsLoading = true;
            showSalesLoading(true);

            try {
                const nextPage = salesCurrentPage + 1;
                const url = new URL(window.location.href);
                url.searchParams.set('page', String(nextPage));
                url.searchParams.set('ajax', '1');

                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load more sales');
                }

                const payload = await response.json();
                const rows = payload.data || [];

                rows.forEach(function(inv) {
                    const tr = document.createElement('tr');
                    tr.className = 'invoice-row';
                    tr.dataset.invoiceId = String(inv.id);
                    tr.id = 'invoice-row-' + inv.id;
                    tr.style.cursor = 'pointer';
                    tr.onclick = function() {
                        window.selectInvoice(tr, inv.id);
                    };
                    tr.innerHTML = `
                        <td class="nowrap">${inv.invoice_date}</td>
                        <td>${inv.bill_no}</td>
                        <td>${inv.customer}</td>
                        <td class="text-right nowrap">${inv.items_count}</td>
                        <td class="text-right nowrap"><strong>Rs. ${inv.total_amount}</strong></td>
                    `;
                    salesTableBody.appendChild(tr);
                });

                salesCurrentPage = payload.current_page || nextPage;

                if (salesTableBody.children.length > salesMaxRows) {
                    while (salesTableBody.children.length > salesMaxRows) {
                        salesTableBody.removeChild(salesTableBody.firstElementChild);
                    }
                }

                if (search) {
                    filterRows();
                }
            } catch (e) {
            } finally {
                salesIsLoading = false;
                showSalesLoading(false);
            }
        }

        function handleSalesScroll() {
            if (!salesTableContainer) return;
            const scrollPosition = salesTableContainer.scrollTop + salesTableContainer.clientHeight;
            const threshold = salesTableContainer.scrollHeight - 80;
            if (scrollPosition >= threshold) {
                loadMoreSales();
            }
        }

        if (salesTableContainer && salesCurrentPage < salesLastPage) {
            salesTableContainer.addEventListener('scroll', handleSalesScroll);
        }
    });

    // Mobile menu toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('mobile-open');
        }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.querySelector('.mobile-menu-btn');
        
        if (window.innerWidth <= 768 && sidebar && menuBtn) {
            if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });
</script>

<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

</body>
</html>
