<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سبزی منڈی انوینٹری | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

        .inventory-layout {
            padding: 24px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        @media (max-width: 992px) {
            .filters-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .filters-grid {
                grid-template-columns: 1fr;
            }
        }

        .table-responsive {
            overflow-x: auto;
        }

        table.inventory-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        table.inventory-table th,
        table.inventory-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        table.inventory-table th {
            background: #f8fafc;
            font-weight: 600;
        }

        .status-badge {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.75rem;
        }

        .status-available {
            background: #dcfce7;
            color: #166534;
        }

        .status-low {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-out {
            background: #fee2e2;
            color: #991b1b;
        }

        .low-stock-row {
            background: #fff7ed;
        }

        .actions-cell {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .btn-xs {
            padding: 4px 8px;
            font-size: 0.75rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            background: white;
            border: 1px solid #cbd5f5;
        }

        .form-grid-inline {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 8px;
        }

        @media (max-width: 992px) {
            .form-grid-inline {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .form-grid-inline {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
@include('components.sidebar')

<div class="main">
    <div class="topbar">
        <h2 class="urdu-text" style="margin: 0;">
            <i class="fa fa-boxes-stacked" style="margin-left: 10px;"></i>
            سبزی منڈی انوینٹری
        </h2>

        <div style="display:flex; gap:8px; align-items:center;">
            @if($canExport)
                <a href="{{ route('inventory.export') }}" class="btn-mandi btn-success">
                    <i class="fa fa-file-csv"></i> ایکسپورٹ
                </a>
            @endif
            @include('components.user-role-display')
        </div>
    </div>

    <div class="inventory-layout">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 16px;">
                <ul style="margin:0; padding-right: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('inventory.index') }}" class="card filters-grid" style="padding:12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="پروڈکٹ تلاش کریں..."
                   class="form-control urdu-text" data-urdu="true">

            <select name="type" class="form-control">
                <option value="">سب اقسام</option>
                <option value="sabzi" {{ request('type') === 'sabzi' ? 'selected' : '' }}>سبزی</option>
                <option value="phall" {{ request('type') === 'phall' ? 'selected' : '' }}>پھل</option>
            </select>

            <select name="supplier_id" class="form-control">
                <option value="">تمام سپلائر</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ (string)request('supplier_id') === (string)$supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-control">
                <option value="">تمام اسٹیٹس</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>دستیاب</option>
                <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>کم اسٹاک</option>
                <option value="out" {{ request('status') === 'out' ? 'selected' : '' }}>ختم</option>
            </select>
        </form>

        @if($canEditProducts)
            <div class="card" style="margin-bottom: 16px; padding:12px;">
                <h3 style="margin:0 0 8px 0;">نیا آئٹم شامل کریں</h3>
                <form method="POST" action="{{ route('inventory.store') }}" class="form-grid-inline">
                    @csrf
                    <input type="text" name="name" placeholder="پروڈکٹ نام" class="form-control urdu-text"
                           data-urdu="true" required>
                    <input type="text" name="type" placeholder="قسم (سبزی/پھل)" class="form-control urdu-text"
                           data-urdu="true">
                    <input type="number" step="0.01" name="quantity" placeholder="مقدار" class="form-control" min="0" required>
                    <input type="text" name="unit" value="kg" class="form-control" placeholder="یونٹ">
                    <input type="number" step="0.01" name="price_per_unit" placeholder="فی یونٹ قیمت" class="form-control" min="0" required>
                    <input type="datetime-local" name="available_from" class="form-control">
                    <button type="submit" class="btn-mandi btn-primary" style="grid-column: span 2;">
                        <i class="fa fa-save"></i> محفوظ کریں
                    </button>
                </form>
            </div>
        @endif

        <div class="card" style="padding:12px;">
            <div class="table-responsive">
                <table class="inventory-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>پروڈکٹ</th>
                        <th>قسم</th>
                        <th>سپلائر</th>
                        <th>اسٹاک</th>
                        <th>فی یونٹ قیمت</th>
                        <th>اسٹیٹس</th>
                        <th>دستیاب از</th>
                        <th>کارروائی</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $item)
                        @php
                            $isLow = $item->status === 'low';
                            $isOut = $item->status === 'out';
                        @endphp
                        <tr class="{{ $isLow ? 'low-stock-row' : '' }}">
                            <td>{{ $item->id }}</td>
                            <td class="urdu-text">{{ $item->name }}</td>
                            <td class="urdu-text">{{ $item->type }}</td>
                            <td>{{ optional($item->productOwner)->name }}</td>
                            <td>{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                            <td>Rs. {{ number_format($item->price_per_unit, 2) }}</td>
                            <td>
                                @if($isOut)
                                    <span class="status-badge status-out">ختم</span>
                                @elseif($isLow)
                                    <span class="status-badge status-low">کم اسٹاک</span>
                                @else
                                    <span class="status-badge status-available">دستیاب</span>
                                @endif
                            </td>
                            <td>{{ optional($item->available_from)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="actions-cell">
                                    @if($canAdjustStock)
                                        <form method="POST" action="{{ route('inventory.adjust', $item) }}" style="display:inline-flex; gap:4px;">
                                            @csrf
                                            <input type="number" name="quantity_delta" step="0.01" placeholder="+/-" style="width:80px; font-size:0.75rem;">
                                            <button type="submit" class="btn-xs btn-outline">
                                                <i class="fa fa-arrows-rotate"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:20px;">
                                کوئی آئٹم نہیں ملا۔
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 10px;">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
</body>
</html>
