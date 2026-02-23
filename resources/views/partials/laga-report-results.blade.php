@if(isset($lagaResults) && $lagaResults !== null)
    <div class="mandi-card">
        <div class="card-header">
            <h3><i class="fa fa-list-alt"></i> {{ __('لاگا رپورٹ نتائج') }}</h3>
            <div class="no-print" style="display: flex; gap: 10px;">
                <button class="btn-mandi btn-success" onclick="exportLaga('laga_csv')"><i class="fa fa-file-csv"></i> سی ایس وی</button>
                <button class="btn-mandi btn-secondary" onclick="window.print()"><i class="fa fa-print"></i> {{ __('پرنٹ') }}</button>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-box"><div class="label">{{ __('کل لاگا') }}</div><div class="value">{{ number_format($lagaTotals['unique_lagas'] ?? 0) }}</div></div>
            <div class="summary-box"><div class="label">{{ __('کل آئٹمز') }}</div><div class="value">{{ number_format($lagaTotals['items_sold'] ?? 0) }}</div></div>
            <div class="summary-box"><div class="label">{{ __('کل کمیشن') }}</div><div class="value">{{ number_format($lagaTotals['commission'] ?? 0) }}</div></div>
            <div class="summary-box" style="background: #eff6ff;"><div class="label" style="color: #1e40af;">{{ __('کل رقم') }}</div><div class="value" style="color: #1d4ed8;">{{ number_format($lagaTotals['total_amount'] ?? 0) }}</div></div>
            <div class="summary-box" style="background: #ecfdf5;"><div class="label" style="color: #047857;">{{ __('وصول شدہ') }}</div><div class="value" style="color: #059669;">{{ number_format($lagaTotals['paid_amount'] ?? 0) }}</div></div>
            <div class="summary-box" style="background: #fef2f2;"><div class="label" style="color: #b91c1c;">{{ __('بقایا') }}</div><div class="value" style="color: #dc2626;">{{ number_format($lagaTotals['batta'] ?? 0) }}</div></div>
        </div>

        <div class="mandi-table-container">
            <table class="mandi-table">
                <thead>
                    <tr>
                        <th>{{ __('تاریخ') }}</th>
                        <th>{{ __('مالک') }}</th>
                        <th>{{ __('بل #') }}</th>
                        <th>{{ __('لاگا') }}</th>
                        <th>{{ __('کوڈ') }}</th>
                        <th>{{ __('آئٹم') }}</th>
                        <th>{{ __('یونٹ') }}</th>
                        <th>{{ __('تعداد') }}</th>
                        <th>{{ __('ریٹ') }}</th>
                        <th>{{ __('کمیشن') }}</th>
                        <th>{{ __('کل رقم') }}</th>
                        <th>{{ __('وصول') }}</th>
                        <th>{{ __('بقایا') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lagaResults as $p)
                        <tr>
                            <td>{{ $p->purchase_date->format('d/m/Y') }}</td>
                            <td class="urdu-text">{{ optional($p->vendor)->name }}</td>
                            <td><span class="badge-mandi" style="background: #e2e8f0; padding: 2px 8px; border-radius: 4px;">{{ $p->bill_number }}</span></td>
                            <td class="urdu-text" style="font-weight: 600;">{{ $p->customer_name }}</td>
                            <td>{{ $p->laga_code }}</td>
                            <td>{{ $p->item_name }}</td>
                            <td>{{ $p->unit }}</td>
                            <td>{{ number_format($p->quantity, 2) }}</td>
                            <td>{{ number_format($p->rate, 2) }}</td>
                            <td>{{ number_format($p->commission_amount ?? 0, 2) }}</td>
                            <td style="font-weight: 700;">{{ number_format($p->total_amount, 2) }}</td>
                            <td style="color: #059669;">{{ number_format($p->paid_amount ?? 0, 2) }}</td>
                            <td style="color: #dc2626; font-weight: 700;">{{ number_format($p->total_amount - ($p->paid_amount ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" style="text-align: center; padding: 40px; color: #94a3b8;">{{ __('کوئی ریکارڈ نہیں ملا') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 no-print" style="padding: 20px;">
            {{ $lagaResults->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
