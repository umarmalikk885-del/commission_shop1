<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>بینک / کیش - کمیشن شاپ</title>
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
            grid-template-columns: 0.95fr 1.05fr;
            gap: 20px;
            align-items: start;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }

        .summary-card h4 {
            margin: 0 0 0.5rem 0;
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
        }

        .summary-card p {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .summary-card.bank p { color: #3b82f6; }
        .summary-card.cash p { color: #10b981; }
        .summary-card.total p { color: #8b5cf6; }

        .dark-mode .summary-card h4 { color: #94a3b8; }
        .dark-mode .summary-card.bank p { color: #60a5fa; }
        .dark-mode .summary-card.cash p { color: #34d399; }
        .dark-mode .summary-card.total p { color: #a78bfa; }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .row-span-2 { grid-column: 1 / -1; }

        .btn-row {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .btn-row { flex-direction: column; }
            .btn-row .btn { width: 100%; }
        }
    </style>
    @include('components.urdu-input-support')
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
        <label for="transactionSearch" class="sr-only">{{ __('messages.search_transactions') }}</label>
        <input id="transactionSearch" type="search" name="search" placeholder="{{ __('messages.search_transactions') }}" autocomplete="off" class="form-control" style="max-width: 300px;">
        <div style="display: flex; gap: 15px; align-items: center;">
            <form method="GET" action="/bank-cash" style="display: flex; gap: 10px; align-items: center;">
                <label for="period" style="font-size: 14px; white-space: nowrap;">{{ __('messages.filter') }}</label>
                <select name="period" id="period" onchange="this.form.submit()" class="form-control" style="padding: 6px 10px; width: auto;">
                    <option value="all" {{ request('period') == 'all' || !request('period') ? 'selected' : '' }}>{{ __('messages.all_records') }}</option>
                    <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>{{ __('messages.weekly') }}</option>
                    <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>{{ __('messages.monthly') }}</option>
                    <option value="three_months" {{ request('period') == 'three_months' ? 'selected' : '' }}>{{ __('messages.three_months') }}</option>
                    <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>{{ __('messages.year') }}</option>
                </select>
            </form>
            @include('components.user-role-display')
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card bank">
            <h4>{{ __('messages.bank_balance') }}</h4>
            <p>Rs. {{ number_format($bankBalance ?? 0, 2) }}</p>
        </div>
        <div class="summary-card cash">
            <h4>{{ __('messages.cash_balance') }}</h4>
            <p>Rs. {{ number_format($cashBalance ?? 0, 2) }}</p>
        </div>
        <div class="summary-card total">
            <h4>{{ __('messages.total_balance') }}</h4>
            <p>Rs. {{ number_format(($bankBalance ?? 0) + ($cashBalance ?? 0), 2) }}</p>
        </div>
    </div>

    <div class="content">

        <!-- Add Transaction -->
        <div class="card">
            <h3>{{ isset($editingTransaction) ? __('messages.edit_transaction') : __('messages.add_transaction') }}</h3>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-error">
                    <strong>{{ __('messages.please_fix_following') }}</strong>
                    <ul style="margin:8px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(isset($editingTransaction))
            <form action="{{ route('transactions.update', $editingTransaction->id) }}" method="POST" style="margin-top:12px;">
                @csrf
                @method('PUT')
            @else
            <form action="/bank-cash" method="POST" style="margin-top:12px;">
                @csrf
            @endif
                @if(request('period'))
                    <input type="hidden" name="period" value="{{ request('period') }}">
                @endif

                <div class="form-grid">
                    <div class="form-group">
                        <label for="transaction_date">{{ __('messages.date') }}</label>
                        <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', isset($editingTransaction) ? optional($editingTransaction->transaction_date)->toDateString() : now()->toDateString()) }}" required autocomplete="off" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="type">{{ __('messages.type') }}</label>
                        <select id="type" name="type" required autocomplete="off" class="form-control">
                            <option value="">{{ __('messages.select_type') }}</option>
                            @php
                                $selectedType = old('type', isset($editingTransaction) ? $editingTransaction->type : '');
                            @endphp
                            <option value="bank" {{ $selectedType === 'bank' ? 'selected' : '' }}>{{ __('messages.bank') }}</option>
                            <option value="cash" {{ $selectedType === 'cash' ? 'selected' : '' }}>{{ __('messages.cash') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="transaction_type">{{ __('messages.transaction_type') }}</label>
                        <select id="transaction_type" name="transaction_type" required autocomplete="off" class="form-control">
                            <option value="">{{ __('messages.select') }}...</option>
                            @php
                                $selectedTxnType = old('transaction_type', isset($editingTransaction) ? $editingTransaction->transaction_type : '');
                            @endphp
                            <option value="deposit" {{ $selectedTxnType === 'deposit' ? 'selected' : '' }}>{{ __('messages.deposit') }}</option>
                            <option value="withdrawal" {{ $selectedTxnType === 'withdrawal' ? 'selected' : '' }}>{{ __('messages.withdrawal') }}</option>
                            <option value="transfer" {{ $selectedTxnType === 'transfer' ? 'selected' : '' }}>{{ __('messages.transfer') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">{{ __('messages.amount') }} (Rs.)</label>
                        <input type="number" step="0.01" min="0.01" id="amount" name="amount" value="{{ old('amount', isset($editingTransaction) ? $editingTransaction->amount : '') }}" required autocomplete="transaction-amount" class="form-control">
                    </div>

                    <div class="form-group row-span-2">
                        <label for="description">{{ __('messages.description') }}</label>
                        <input type="text" id="description" name="description" placeholder="{{ __('messages.description_placeholder') }}" value="{{ old('description', isset($editingTransaction) ? $editingTransaction->description : '') }}" required autocomplete="off" class="form-control">
                    </div>

                    <div class="form-group row-span-2">
                        <label for="notes">{{ __('messages.notes_optional') }}</label>
                        <textarea id="notes" name="notes" placeholder="{{ __('messages.notes_placeholder') }}" autocomplete="off" class="form-control">{{ old('notes', isset($editingTransaction) ? $editingTransaction->notes : '') }}</textarea>
                    </div>
                </div>

                <div class="btn-row">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> {{ isset($editingTransaction) ? __('messages.update_transaction') : __('messages.save_transaction') }}
                    </button>
                    <a class="btn btn-secondary" href="/bank-cash{{ request('period') ? '?period=' . request('period') : '' }}">
                        <i class="fa fa-rotate-right"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom: 15px;">
                <div>
                    <h3 style="margin:0;">{{ __('messages.transaction_register') }}</h3>
                </div>
            </div>

            @if(isset($transactions) && $transactions->count())
                <div class="table-responsive">
                    <table id="transactionTable">
                        <thead>
                        <tr>
                            <th class="nowrap">{{ __('messages.date') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th>{{ __('messages.transaction') }}</th>
                            <th>{{ __('messages.description') }}</th>
                            <th class="text-right nowrap">{{ __('messages.amount') }} (Rs.)</th>
                            <th>{{ __('messages.purchase_record') }}</th>
                            <th>{{ __('messages.sales_record') }}</th>
                            <th>{{ __('messages.notes') }}</th>
                            <th class="text-right nowrap">{{ __('messages.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td class="nowrap">
                                    @php
                                        $displayDate = $transaction->transaction_date ?? $transaction->created_at;
                                    @endphp
                                    {{ $displayDate ? $displayDate->translatedFormat('D, d/m/Y') : '—' }}
                                </td>
                                <td>
                                    <span class="pill {{ $transaction->type == 'bank' ? 'pill-success' : 'pill-danger' }}">
                                        {{ $transaction->type == 'bank' ? __('messages.bank') : __('messages.cash') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="pill">
                                        @if($transaction->transaction_type == 'deposit')
                                            {{ __('messages.deposit') }}
                                        @elseif($transaction->transaction_type == 'withdrawal')
                                            {{ __('messages.withdrawal') }}
                                        @elseif($transaction->transaction_type == 'transfer')
                                            {{ __('messages.transfer') }}
                                        @else
                                            —
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $transaction->description ?? '—' }}</td>
                                <td class="text-right nowrap">
                                    <strong>Rs. {{ number_format($transaction->amount ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    @if($transaction->purchase)
                                        <a href="/purchase?highlight={{ $transaction->purchase->id }}" style="color: #1e88e5; text-decoration: none; font-weight: 500;">
                                            {{ __('messages.view_purchase') }}
                                        </a>
                                    @else
                                        <span style="color: #888;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->invoice)
                                        <a href="/sales?highlight={{ $transaction->invoice->id }}" style="color: #10b981; text-decoration: none; font-weight: 500;">
                                            {{ __('messages.view_sales') }}
                                        </a>
                                    @else
                                        <span style="color: #888;">—</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->notes ?? '—' }}</td>
                                <td class="text-right nowrap">
                                    <a href="{{ route('transactions.edit', ['transaction' => $transaction->id, 'period' => $period ?? 'all']) }}" class="btn btn-secondary" style="padding:6px 10px; font-size:12px; margin-right:4px;">
                                        <i class="fa fa-pen"></i> {{ __('messages.edit') }}
                                    </a>
                                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('messages.delete_transaction_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding:6px 10px; font-size:12px; border:none;">
                                            <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align:center; padding:20px; color:#6b7280;">
                    {{ __('messages.no_records_found') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('transactionSearch').addEventListener('keyup', function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("transactionSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("transactionTable");
        if (!table) return;
        tr = table.getElementsByTagName("tr");
        
        for (i = 0; i < tr.length; i++) {
            // Search in description, notes, date
            // Indices: 0(Date), 3(Desc), 7(Notes)
            var tdDate = tr[i].getElementsByTagName("td")[0];
            var tdDesc = tr[i].getElementsByTagName("td")[3];
            var tdNotes = tr[i].getElementsByTagName("td")[7];
            
            if (tdDate || tdDesc || tdNotes) {
                var txtDate = tdDate ? (tdDate.textContent || tdDate.innerText) : "";
                var txtDesc = tdDesc ? (tdDesc.textContent || tdDesc.innerText) : "";
                var txtNotes = tdNotes ? (tdNotes.textContent || tdNotes.innerText) : "";
                
                if (txtDate.toUpperCase().indexOf(filter) > -1 || 
                    txtDesc.toUpperCase().indexOf(filter) > -1 || 
                    txtNotes.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    });
</script>

</body>
</html>
