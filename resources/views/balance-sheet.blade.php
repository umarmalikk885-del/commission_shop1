<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بیلنس شیٹ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.main-content-spacing')
    @include('components.global-dark-mode-styles')
    <style>
        .balance-sheet-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        @media (max-width: 992px) {
            .balance-sheet-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .assets-card { border-top: 4px solid #10b981; }
        .liabilities-card { border-top: 4px solid #ef4444; }
        
        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--text-color);
        }
        
        .assets-header { background-color: #ecfdf5; color: #065f46; }
        .liabilities-header { background-color: #fef2f2; color: #991b1b; }
        
        body.dark-mode .assets-header { background-color: #064e3b; color: #d1fae5; }
        body.dark-mode .liabilities-header { background-color: #7f1d1d; color: #fee2e2; }

        .amount-col {
            text-align: right !important;
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
        }
        
        [dir="rtl"] .amount-col { text-align: left !important; }
        
        .total-row {
            background-color: var(--bg-color);
            font-weight: 700;
            font-size: 1.05rem;
        }
        
        .total-row td {
            border-top: 2px solid var(--border-color);
            color: var(--text-color);
        }
        
        .net-balance-amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
        }
        
        .positive-balance { color: #10b981; }
        .negative-balance { color: #ef4444; }
    </style>
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
        <h2 style="margin: 0;"><i class="fa fa-balance-scale" style="margin-right:8px;"></i> بیلنس شیٹ</h2>
        @include('components.user-role-display')
    </div>

    <!-- Content -->
    <div class="content">
        
        <!-- Filter Form -->
        <div class="card" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 15px;">
            <form action="{{ route('balance-sheet') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; flex: 1; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>تاریخ آغاز</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label>تاریخ اختتام</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> فلٹر
                    </button>
                </div>
            </form>
            
            <div class="form-group" style="margin-bottom: 0;">
                <a href="{{ route('balance-sheet', ['export' => 'csv', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">
                    <i class="fa fa-download"></i> ایکسپورٹ CSV
                </a>
            </div>
        </div>
        
        <!-- Net Balance Summary -->
        <div class="card" style="text-align: center; padding: 20px;">
            <div style="color: #6b7280; font-size: 1rem; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">کل بچت</div>
            <div class="net-balance-amount {{ $netBalance >= 0 ? 'positive-balance' : 'negative-balance' }}">
                {{ number_format($netBalance, 2) }}
            </div>
            <p style="margin: 5px 0 0; color: #6b7280; font-size: 0.9rem;">
                (آمدنی - اخراجات)
            </p>
        </div>
        
        <br>

        <div class="balance-sheet-grid">
            
            <!-- Assets / Income Section -->
            <div class="card assets-card" style="padding: 0; overflow: hidden;">
                <div class="card-header assets-header">
                    <h3>آمدنی</h3>
                </div>
                <div class="table-responsive">
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>تفصیل</th>
                                <th class="amount-col">رقم</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding: 10px 0;">
                                    <form action="{{ route('balance-sheet.income.add') }}" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                        @csrf
                                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                                        <input type="date" name="transaction_date" class="form-control" value="{{ $endDate }}" style="width: 150px;">
                                        <input id="income-amount" type="number" name="amount" step="0.01" min="0.01" class="form-control" placeholder="0.00" style="width: 120px;" required>
                                        <select name="type" class="form-control" style="width: 110px;">
                                            <option value="cash">نقد</option>
                                            <option value="bank">بینک</option>
                                        </select>
                                        <select id="income-description" name="description" class="form-control urdu-text" style="width: 200px;">
                                            <option value="کمیشن (بھیڑ/بکری)" data-amount="{{ number_format(($autoAmounts['commission'] ?? ($incomes['commission'] ?? 0)), 2, '.', '') }}">کمیشن (بھیڑ/بکری)</option>
                                            <option value="لاگا" data-amount="{{ number_format(($autoAmounts['laagaa'] ?? ($incomes['laagaa'] ?? 0)), 2, '.', '') }}">لاگا</option>
                                            <option value="منشیانہ" data-amount="{{ number_format(($autoAmounts['mashiana'] ?? ($incomes['mashiana'] ?? 0)), 2, '.', '') }}">منشیانہ</option>
                                            <option value="مزدوری" data-amount="{{ number_format(($autoAmounts['labor'] ?? ($incomes['labor'] ?? 0)), 2, '.', '') }}">مزدوری</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="margin-inline-start: auto;">شامل کریں</button>
                                    </form>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <strong>کمیشن (بھیڑ/بکری)</strong>
                                </td>
                            
                            <tr>
                                <td>
                                    <strong>لاگا</strong>
                                </td>
                                <td class="amount-col">{{ number_format($incomes['laagaa'], 2) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>منشیانہ</strong>
                                </td>
                                <td class="amount-col">{{ number_format($incomes['mashiana'], 2) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>مزدوری</strong>
                                </td>
                                <td class="amount-col">{{ number_format($incomes['labor'], 2) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="amount-col"></td>
                            </tr>
                            
                            <tr class="total-row">
                                <td>کل آمدنی</td>
                                <td class="amount-col">{{ number_format($totalIncome, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Liabilities / Expenses Section -->
            <div class="card liabilities-card" style="padding: 0; overflow: hidden;">
                <div class="card-header liabilities-header">
                    <h3>اخراجات</h3>
                </div>
                <div style="padding: 10px 20px; border-bottom: 1px solid var(--border-color); display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <form action="{{ route('balance-sheet') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <div>
                            <label style="font-size: 0.85rem; color: #6b7280;">تاریخ سے</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" style="max-width: 160px;">
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; color: #6b7280;">تاریخ تک</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" style="max-width: 160px;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="height: 36px;">فلٹر</button>
                    </form>
                </div>
                <div style="padding: 10px 20px;">
                    <form action="{{ route('balance-sheet.expense.add') }}" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <input type="date" name="transaction_date" class="form-control" value="{{ $endDate }}" style="width: 150px;">
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control" placeholder="0.00" style="width: 120px;" required>
                        <select name="type" class="form-control" style="width: 110px;">
                            <option value="cash">نقد</option>
                            <option value="bank">بینک</option>
                        </select>
                        <select name="description" class="form-control urdu-text" style="width: 200px;">
                            <option value="Shop Rent (Monthly)">دکان کرایہ (ماہانہ)</option>
                            <option value="خوراک">خوراک</option>
                            <option value="کل کسان ایڈوانس رقم">کل کسان ایڈوانس رقم</option>
                            <option value="Other Expense">دیگر اخراجات</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="margin-inline-start: auto;">شامل کریں</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>تفصیل</th>
                                <th class="amount-col">رقم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenseItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst($item->description) }}</strong>
                                    </td>
                                    <td class="amount-col">{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #9ca3af; padding: 20px;">
                                        اس مدت کے لیے کوئی اخراجات نہیں ملے
                                    </td>
                                </tr>
                            @endforelse
                            
                            <tr class="total-row">
                                <td>کل اخراجات</td>
                                <td class="amount-col">{{ number_format($totalExpenses, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    (function(){
        var sel=document.getElementById('income-description');var amt=document.getElementById('income-amount');if(sel&&amt){var f=function(){var o=sel.options[sel.selectedIndex];var v=o.getAttribute('data-amount')||'0.00';amt.value=v;};sel.addEventListener('change',f);f();}
    })();
</script>

</body>
</html>
