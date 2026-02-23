<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بل نمبر {{ $purchase->bill_number ?? 'دستیاب نہیں' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Noto Nastaliq Urdu', 'Arial', sans-serif;
            background: #eee;
            margin: 0;
            padding: 20px;
            color: #000;
            font-size: 12pt; /* Increased base font size */
            line-height: 1.5;
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            background: #333;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-family: sans-serif;
            font-size: 14px;
            display: inline-block;
        }

        /* Bill Container - A4 Size */
        .bill-container {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
            border: none;
        }

        /* Mandai Header Styling */
        .header {
            text-align: center;
            position: relative;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .shop-badge {
            position: absolute;
            left: 0;
            top: 10px;
            border: 3px solid rgb(124, 68, 68);
            color: red;
            padding: 5px 15px;
            font-weight: bold;
            transform: rotate(-10deg);
            font-size: 14pt;
            z-index: 10;
        }

        .company-name {
            font-size: 28pt;
            font-weight: 800;
            color: #1a73e8;
            text-shadow: 1px 1px 0 #fff;
            margin: 0;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 16pt;
            color: #555;
            margin-top: 5px;
            font-weight: bold;
        }

        .address {
            font-size: 12pt;
            color: #333;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .phone-strip {
            background: #f8931f;
            color: white;
            font-size: 12pt;
            padding: 8px 15px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            font-weight: bold;
            margin-top: 10px;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        /* Info Row: Date, Bill No */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            font-size: 14pt;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 10px;
        }
        
        .customer-row {
            display: flex;
            align-items: center;
            font-size: 14pt;
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            margin-left: 10px;
        }
        
        .value {
            border-bottom: 1px solid #000;
            flex: 1;
            text-align: center;
        }

        /* Table */
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12pt;
        }

        .bill-table th {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 5px;
            font-weight: bold;
            background: #f0f0f0;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .bill-table td {
            padding: 10px 5px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        /* Column Widths */
        .col-qty { width: 15%; text-align: center; border-left: 1px solid #ccc; }
        .col-desc { width: 50%; text-align: right; padding-right: 10px; }
        .col-rate { width: 15%; text-align: center; border-right: 1px solid #ccc; }
        .col-amt { width: 20%; text-align: center; border-right: 1px solid #ccc; font-weight: bold; }

        /* Summary Box */
        .summary-box {
            margin-top: 20px;
            border-top: 2px solid #000;
            width: 60%; /* Adjusted for better A4 layout */
            margin-right: auto; /* Aligns to left in RTL */
        }
        
        .summary-row {
            display: flex;
            border-bottom: 1px solid #000;
        }
        
        .summary-label {
            width: 60%;
            text-align: right;
            padding: 10px;
            font-weight: bold;
            font-size: 14pt;
            border-left: 1px solid #000;
        }
        
        .summary-value {
            width: 40%;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            font-size: 15pt;
        }

        .total-row .summary-label { background: #eee; }
        .total-row .summary-value { background: #eee; border-top: 2px double #000; }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        
        /* Handwriting style lines */
        .notes-area {
            margin-top: 40px;
            border-top: 1px dashed #aaa;
            padding-top: 10px;
        }
        .line {
            border-bottom: 1px solid #ddd;
            height: 30px;
            margin-bottom: 10px;
        }

        @media print {
            body { 
                background: white; 
                padding: 0; 
                margin: 0;
            }
            .no-print { display: none; }
            
            @page {
                size: A4; /* Force A4 size */
                margin: 0; /* Handle margins in container */
            }
            
            .bill-container {
                width: 210mm;
                min-height: auto; /* Let content dictate height to prevent spillover */
                height: auto;
                margin: 0;
                padding: 15mm;
                box-shadow: none;
                border: none;
                page-break-after: auto; /* Avoid forced blank pages */
                box-sizing: border-box; /* Ensure padding is included in dimensions */
            }
            
            .shop-badge {
                border-color: red !important;
                color: red !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn" onclick="window.print()">پرنٹ</button>
    <a href="{{ route('purchase') }}" class="btn">واپس</a>
</div>

<div class="bill-container">
    
    <!-- Mandai Header -->
    <div class="header">
        <!-- Shop Number Badge -->
        <div class="shop-badge">
            دکان نمبر 1
        </div>

        <h1 class="company-name">{{ $company ? $company->translated_company_name : 'فہیم خان اینڈ سنز' }}</h1>
        <div class="subtitle">سبزی فروٹ کمیشن ایجنٹ</div>
        <div class="address">{{ $company ? $company->translated_address : 'نیو اسماعیل خان فروٹ مارکیٹ ٹوٹل کے مٹہ سوات' }}</div>
        
        <div class="phone-strip">
            <span>{{ $company->mobile ?? '0345-9459656' }} :فہیم خان</span>
            <span>{{ $company->phone ?? '0344-9778888' }} :پروپرائٹر</span>
        </div>
    </div>

    <!-- Bill Info -->
    <div class="info-row">
        <div>
            <span class="label">بل نمبر:</span>
            <span style="font-weight: bold; font-family: monospace; font-size: 16px;">{{ $purchase->bill_number }}</span>
        </div>
        <div>
            <span class="label">تاریخ:</span>
            <span>{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : date('d-m-Y') }}</span>
        </div>
    </div>
    


    <!-- Items Table -->
    <table class="bill-table">
        <thead>
            <tr>
                <th class="col-qty">تعداد</th>
                <th class="col-desc">تفصیل</th>
                <th class="col-rate">نرخ</th>
                <th class="col-amt">روپے</th>
            </tr>
        </thead>
        <tbody>
            @if($purchase->items && $purchase->items->count() > 0)
                @foreach($purchase->items as $item)
                <tr>
                    <td class="col-qty">{{ number_format($item->quantity, 0) }}</td>
                    <td class="col-desc">{{ $item->item_name }}</td>
                    <td class="col-rate">{{ number_format($item->rate, 0) }}</td>
                    <td class="col-amt">{{ number_format($item->amount, 0) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="col-qty">{{ number_format($purchase->quantity, 0) }}</td>
                    <td class="col-desc">{{ $purchase->item_name }}</td>
                    <td class="col-rate">{{ number_format($purchase->rate, 0) }}</td>
                    <td class="col-amt">{{ number_format($purchase->total_amount, 0) }}</td>
                </tr>
            @endif
            
            <!-- Empty rows to fill space matching image -->
            @for($i = 0; $i < max(0, 5 - ($purchase->items ? $purchase->items->count() : 1)); $i++)
            <tr>
                <td style="height: 30px;"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor

        </tbody>
    </table>

    <!-- Calculated Totals -->
    @php
        $currentTotal = $purchase->total_amount;
        $commission = $purchase->commission_amount ?? 0;
        // Taza Banam = Subtotal + Commission (Basically the new bill amount)
        $tazaBanam = $currentTotal + $commission;
        
        // Sabiqa Raqam - Placeholder for now as it needs a ledger system
        $sabiqaRaqam = 0; 
        
        $totalRaqam = $tazaBanam + $sabiqaRaqam;
    @endphp

    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-row">
            <div class="summary-label">تازہ بنام</div>
            <div class="summary-value">{{ number_format($tazaBanam, 0) }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">سابقہ ​​رقم</div>
            <div class="summary-value">{{ $sabiqaRaqam > 0 ? number_format($sabiqaRaqam, 0) : '-' }}</div>
        </div>
        <div class="summary-row total-row">
            <div class="summary-label">ٹوٹل رقم</div>
            <div class="summary-value">{{ number_format($totalRaqam, 0) }}</div>
        </div>
    </div>

    <!-- Notes / Lines Area -->
    <div class="notes-area">
        @for($i=0; $i<6; $i++)
        <div class="line"></div>
        @endfor
    </div>
    
    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div style="font-size: 16pt; color: blue; font-family: 'Brush Script MT', cursive; transform: rotate(-5deg);">
            <!-- Signature placeholder matching blue scribble in image -->
            دستخط...
        </div>
        <div style="font-size: 12pt; font-weight: bold;">
            {{ $company->mobile ?? '0336-0001702' }}
        </div>
    </div>

    <div class="footer">
        نیو اسماعیل خان فروٹ مارکیٹ ٹوٹکے مٹہ سوات
    </div>

</div>

</body>
</html>
