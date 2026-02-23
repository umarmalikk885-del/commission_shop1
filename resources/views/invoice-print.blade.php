<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انوائس {{ $invoice->bill_no }} - پرنٹ</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Screen styles */
        @media screen {
            body {
                font-family: Arial, sans-serif;
                background: #f5f5f5;
                padding: 20px;
            }
            .print-container {
                max-width: 210mm;
                margin: 0 auto;
                background: white;
                padding: 20mm;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .no-print {
                text-align: center;
                margin-bottom: 20px;
            }
            .no-print button {
                padding: 12px 24px;
                background: #1e88e5;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                cursor: pointer;
                margin: 0 10px;
            }
            .no-print button:hover {
                background: #1565c0;
            }
        }

        /* Print styles - A4 size */
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background: white;
            }
            
            .no-print {
                display: none;
            }
            
            .print-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            
            /* Prevent page breaks inside important sections */
            .invoice-header,
            .invoice-details,
            .invoice-items,
            .invoice-footer {
                page-break-inside: avoid;
            }
            
            /* Allow page breaks between items if needed */
            .invoice-items tr {
                page-break-inside: avoid;
            }
        }

        /* Common styles */
        .print-container {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #1e88e5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            color: #1e88e5;
            font-weight: bold;
        }

        .invoice-header .company-info {
            font-size: 13px;
            color: #666;
            line-height: 1.8;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 6px;
        }

        .invoice-details .detail-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .invoice-details .detail-group label {
            font-weight: 600;
            color: #555;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-details .detail-group span {
            font-size: 14px;
            color: #111;
        }

        .invoice-items {
            margin-bottom: 25px;
        }

        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-items table thead {
            background: #1e88e5;
            color: white;
        }

        .invoice-items table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid #1565c0;
        }

        .invoice-items table th:last-child,
        .invoice-items table td:last-child {
            text-align: right;
        }

        .invoice-items table td {
            padding: 10px;
            border: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .invoice-items table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .invoice-total {
            margin-top: 20px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 6px;
        }

        .invoice-total table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-total table td {
            padding: 8px 10px;
            font-size: 14px;
        }

        .invoice-total table td:first-child {
            text-align: right;
            font-weight: 600;
            width: 70%;
        }

        .invoice-total table td:last-child {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            color: #1e88e5;
        }

        .invoice-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .invoice-footer p {
            margin: 5px 0;
        }

        /* Responsive for screen view */
        @media screen and (max-width: 768px) {
            .print-container {
                padding: 15mm;
            }
            
            .invoice-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">
            <i class="fa fa-print"></i> انوائس پرنٹ کریں
        </button>
        <button onclick="window.close()">
            <i class="fa fa-times"></i> بند کریں
        </button>
    </div>

    <div class="print-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1>{{ $company ? $company->translated_company_name : 'کمیشن شاپ' }}</h1>
            <div class="company-info">
                @if($company)
                    <div>{{ $company->translated_address }}</div>
                    <div>فون: {{ $company->phone }}</div>
                @else
                    <div>کمیشن شاپ کا پتہ</div>
                    <div>فون: +92 XXX XXXXXXX</div>
                @endif
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-group">
                <label>{{ __('messages.bill_number') ?? __('بل نمبر') }}</label>
                <span><strong>{{ $invoice->bill_no }}</strong></span>
            </div>
            <div class="detail-group">
                <label>{{ __('messages.date') ?? __('تاریخ') }}</label>
                <span>{{ $invoice->invoice_date ? $invoice->invoice_date->translatedFormat('D, d/m/Y') : 'دستیاب نہیں' }}</span>
            </div>
            <div class="detail-group">
                <label>{{ __('messages.customer_name') ?? __('کسٹمر کا نام') }}</label>
                <span><strong>{{ $invoice->customer }}</strong></span>
            </div>
            <div class="detail-group">
                <label>{{ __('messages.total_items') ?? __('کل آئٹمز') }}</label>
                <span>{{ $invoice->items->count() }}</span>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="invoice-items">
            <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #333;">{{ __('messages.items') ?? __('اشیاء') }}</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 40%;">{{ __('messages.item_name') ?? __('آئٹم نام') }}</th>
                        <th style="width: 15%;">{{ __('messages.quantity') ?? __('مقدار') }}</th>
                        <th style="width: 15%;">{{ __('messages.unit') ?? __('یونٹ') }}</th>
                        <th style="width: 15%;">{{ __('messages.rate') ?? __('ریٹ') }}</th>
                        <th style="width: 15%;">{{ __('messages.amount') ?? __('رقم') }} (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item->item_name }}</strong></td>
                            <td>{{ $item->qty ?? ($item->quantity ? explode(' ', $item->quantity)[0] : '-') }}</td>
                            <td>{{ $item->unit ? ucfirst($item->unit) : '-' }}</td>
                            <td>{{ $item->rate ? number_format($item->rate, 2) : '-' }}</td>
                            <td><strong>Rs. {{ number_format($item->amount, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 20px;">
                                کوئی آئٹم نہیں ملا
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Invoice Total -->
        <div class="invoice-total">
            <table>
                <tr>
                    <td>{{ __('messages.subtotal') ?? __('ذیلی کل') }}:</td>
                    <td>Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __('messages.total_amount') ?? __('کل رقم') }}:</td>
                    <td>Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <p><strong>آپ کے کاروبار کا شکریہ!</strong></p>
            <p>یہ کمپیوٹر سے تیار کردہ انوائس ہے۔</p>
            <p>پرنٹ کی تاریخ: {{ now()->translatedFormat('d/m/Y h:i A') }}</p>
        </div>
    </div>

    <!-- Auto print on load (optional - can be removed if not needed) -->
    <script>
        // Optional: Auto-print when page loads (uncomment if needed)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>
