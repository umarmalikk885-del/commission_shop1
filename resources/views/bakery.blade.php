<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ __('بیکری بک') }} | کمیشن شاپ</title>
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
        /* Hide number input arrows/spinners */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type="number"] {
            -moz-appearance: textfield;
        }
        
        /* Remove focus color change from table fields */
        .purchaser-input:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #d1d5db !important;
            background-color: #ffffff !important;
        }
        
        .purchaser-input {
            transition: none !important;
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #d1d5db !important;
            padding: 4px 8px !important;
            font-size: 0.85rem !important;
        }
        
        .purchaser-input[readonly] {
            background-color: #f3f4f6 !important;
            color: #6b7280 !important;
            cursor: default !important;
        }
        
        /* Make amount field look normal (not readonly) */
        input[name*="[daily_amount]"] {
            background-color: #ffffff !important;
            color: #000000 !important;
            cursor: text !important;
        }
        
        /* Make total field look normal (not readonly) */
        input[name*="[row_total]"] {
            background-color: #ffffff !important;
            color: #000000 !important;
            cursor: text !important;
        }
        
        /* Ensure text is visible */
        .urdu-text.purchaser-input {
            color: #000000 !important;
            font-family: 'Noto Nastaliq Urdu', sans-serif !important;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(2px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease-in-out;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-window {
            background: #d4d0c8;
            border: 2px outset #fff;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
            width: 600px;
            max-width: 95%;
            display: flex;
            flex-direction: column;
            font-family: 'Tahoma', sans-serif;
            transform: scale(0.95);
            transition: transform 0.2s ease-in-out;
        }

        .modal-overlay.active .modal-window {
            transform: scale(1);
        }

        .modal-title-bar {
            background: #d07070; /* Salmon/Reddish color from image */
            padding: 4px 8px;
            display: flex;
            justify-content: center; /* Centered title */
            align-items: center;
            color: black;
            font-weight: normal;
            border-bottom: 1px solid #808080;
            font-family: 'Tahoma', sans-serif;
            position: relative;
        }

        .modal-close-btn {
            display: none; /* No close button in title bar in the reference image */
        }

        .modal-content {
            padding: 10px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .modal-search-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .modal-search-bar label {
            font-weight: normal;
            font-family: 'Times New Roman', serif;
            font-size: 16px;
        }

        .modal-search-bar input {
            width: 200px;
            border: 1px inset #fff;
            padding: 2px;
            background: white;
            box-shadow: inset 1px 1px 2px #888;
        }

        .modal-table-container {
            border: 1px solid #808080;
            background: white;
            height: 350px;
            overflow-y: auto;
            margin-bottom: 10px;
            flex-grow: 1;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            font-family: 'Times New Roman', serif;
        }

        .modal-table th {
            background: #d4d0c8;
            border-bottom: 1px solid #000;
            border-right: 1px solid #d4d0c8; /* Minimal vertical separator in header */
            padding: 2px 5px;
            text-align: left;
            position: sticky;
            top: 0;
            font-weight: bold;
            color: black;
        }

        .modal-table td {
            padding: 2px 5px;
            border: none;
            cursor: pointer;
        }
        
        /* Selected Row Style */
        .modal-table tr.selected {
            background-color: #000000 !important;
            color: white !important;
        }

        .modal-table tr:hover {
            background-color: #eee;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        
        .modal-btn {
            min-width: 80px;
            padding: 4px 15px;
            background: #d4d0c8;
            border: 2px outset #fff;
            border-right-color: #404040;
            border-bottom-color: #404040;
            font-family: 'Times New Roman', serif;
            font-size: 14px;
            cursor: pointer;
        }
        
        .modal-btn:active {
            border: 2px inset #fff;
            border-top-color: #404040;
            border-left-color: #404040;
        }

        /* Packing popup (Add Items page only) - classic reference layout */
        #packagingModal {
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: none;
        }

        #packagingModal .modal-window {
            width: 650px;
            height: 500px;
            max-width: 95%;
            background: #d4d0c8;
            border: 2px outset #fff;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.45);
            transform: scale(1);
        }

        #packagingModal .modal-title-bar {
            background: #d07070;
            padding: 3px 10px;
            min-height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f2f2f;
            border-bottom: 1px solid #808080;
            position: relative;
            font-size: 13px;
        }

        #packagingModal .packaging-close-btn {
            position: absolute;
            top: 3px;
            right: 5px;
            width: 15px;
            height: 15px;
            border: 1px solid #9a9a9a;
            border-top-color: #f4d6d6;
            border-left-color: #f4d6d6;
            border-right-color: #7a4a4a;
            border-bottom-color: #7a4a4a;
            background: #c96767;
            color: #fdf7f7;
            font-size: 11px;
            line-height: 1;
            padding: 0;
            cursor: pointer;
        }

        #packagingModal .packaging-close-btn:active {
            border-top-color: #7a4a4a;
            border-left-color: #7a4a4a;
            border-right-color: #f4d6d6;
            border-bottom-color: #f4d6d6;
        }

        #packagingModal .modal-content {
            padding: 8px 10px 10px;
            background: #d4d0c8;
        }

        #packagingModal .modal-search-bar {
            margin-bottom: 8px;
            justify-content: center;
            gap: 10px;
        }

        #packagingModal .modal-search-bar label {
            font-size: 28px;
            line-height: 1;
            font-weight: normal;
            font-family: 'Times New Roman', serif;
            color: #3a3a3a;
        }

        #packagingModal .modal-search-bar input {
            width: 140px;
            height: 22px;
            border: 2px inset #fff;
            padding: 0 6px;
            background: #fdfdfd;
            box-shadow: inset 1px 1px 2px #888;
            font-size: 14px;
            font-family: 'Times New Roman', serif;
            text-align: left;
            direction: ltr;
        }

        #packagingModal .modal-table-container {
            border: 1px solid #808080;
            background: #fff;
            height: 360px;
            overflow-y: auto;
            margin-bottom: 8px;
            box-shadow: inset 1px 1px 0 #f5f5f5;
        }

        #packagingModal .modal-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
            font-family: 'Times New Roman', serif;
        }

        #packagingModal .modal-table th {
            background: #d4d0c8;
            border-bottom: 1px solid #808080;
            padding: 2px 5px;
            text-align: left;
            position: sticky;
            top: 0;
            font-weight: 700;
            color: #4a4a4a;
            z-index: 1;
        }

        #packagingModal .modal-table td {
            padding: 2px 5px;
            border: none;
            cursor: pointer;
            color: #2f2f2f;
        }

        #packagingModal .modal-table td:nth-child(2) {
            text-align: right;
            font-family: 'Noto Nastaliq Urdu', serif;
        }

        #packagingModal .modal-table td:nth-child(3) {
            text-align: right;
        }

        #packagingModal .modal-table td:nth-child(4) {
            text-align: center;
        }

        #packagingModal .modal-table tr.selected {
            background-color: #1d2630 !important;
            color: #fff !important;
        }

        #packagingModal .modal-table tr.selected td {
            color: #fff !important;
        }

        #packagingModal .modal-table tr:hover {
            background-color: #efefef;
        }

        #packagingModal .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            padding: 0 8px;
            margin-top: 4px;
        }

        #packagingModal .modal-footer .modal-btn:nth-child(1) { justify-self: start; }
        #packagingModal .modal-footer .modal-btn:nth-child(2) { justify-self: center; }
        #packagingModal .modal-footer .modal-btn:nth-child(3) { justify-self: end; }

        #packagingModal .modal-btn {
            min-width: 62px;
            height: 24px;
            padding: 2px 14px;
            background: #d4d0c8;
            border: 2px outset #fff;
            border-right-color: #666;
            border-bottom-color: #666;
            font-family: 'Times New Roman', serif;
            font-size: 14px;
            color: #222;
            cursor: pointer;
        }

        #packagingModal .modal-btn:active {
            border: 2px inset #fff;
            border-top-color: #666;
            border-left-color: #666;
        }

        #packagingModal .delete-btn {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease;
            color: #7f1d1d !important;
        }

        #packagingModal .modal-table tr:hover .delete-btn,
        #packagingModal .modal-table tr.selected .delete-btn {
            opacity: 0.85;
            pointer-events: auto;
        }

        body.dark-mode #packagingModal .modal-window,
        body.dark-mode #packagingModal .modal-content,
        body.dark-mode #packagingModal .modal-title-bar,
        body.dark-mode #packagingModal .modal-table-container,
        body.dark-mode #packagingModal .modal-table th,
        body.dark-mode #packagingModal .modal-table td,
        body.dark-mode #packagingModal .modal-search-bar label,
        body.dark-mode #packagingModal .modal-search-bar input,
        body.dark-mode #packagingModal .modal-btn {
            background: unset !important;
            color: unset !important;
            border-color: unset !important;
            box-shadow: unset !important;
        }

        body.dark-mode #packagingModal .modal-window {
            background: #d4d0c8 !important;
            border: 2px outset #fff !important;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.45) !important;
        }

        body.dark-mode #packagingModal .modal-content {
            background: #d4d0c8 !important;
        }

        body.dark-mode #packagingModal .modal-title-bar {
            background: #d07070 !important;
            color: #2f2f2f !important;
            border-bottom: 1px solid #808080 !important;
        }

        body.dark-mode #packagingModal .modal-search-bar label {
            color: #3a3a3a !important;
        }

        body.dark-mode #packagingModal .modal-search-bar input {
            background: #fdfdfd !important;
            color: #111 !important;
            border: 2px inset #fff !important;
            box-shadow: inset 1px 1px 2px #888 !important;
        }

        body.dark-mode #packagingModal .modal-table-container {
            background: #fff !important;
            border: 1px solid #808080 !important;
            box-shadow: inset 1px 1px 0 #f5f5f5 !important;
        }

        body.dark-mode #packagingModal .modal-table th {
            background: #d4d0c8 !important;
            color: #4a4a4a !important;
            border-bottom: 1px solid #808080 !important;
        }

        body.dark-mode #packagingModal .modal-table td {
            color: #2f2f2f !important;
        }

        body.dark-mode #packagingModal .modal-table tr.selected,
        body.dark-mode #packagingModal .modal-table tr.selected td {
            background-color: #1d2630 !important;
            color: #fff !important;
        }

        body.dark-mode #packagingModal .modal-btn {
            background: #d4d0c8 !important;
            color: #222 !important;
            border: 2px outset #fff !important;
            border-right-color: #666 !important;
            border-bottom-color: #666 !important;
        }

        body.dark-mode #packagingModal .modal-btn:active {
            border: 2px inset #fff !important;
            border-top-color: #666 !important;
            border-left-color: #666 !important;
        }
        
        :root {
            --bg-color: #f0f9ff;
            --input-bg: #e8f4fd;
            --border-light: #bae6fd;
            --border-dark: #3b82f6;
            --border-darker: #2563eb;
            --header-bg: #3b82f6;
            --text-color: #3f2513;
        }

        body {
            font-family: 'Noto Nastaliq Urdu', 'Tahoma', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            font-size: 14px;
            background-image:
                radial-gradient(circle at top left, #ffedd5 0, transparent 55%),
                radial-gradient(circle at bottom right, #fee2e2 0, transparent 55%);
        }

        .main { 
            padding: 16px; 
            margin-left: 260px;
            width: calc(100% - 260px);
            box-sizing: border-box;
        }

        [dir="rtl"] .main {
            margin-left: 0;
            margin-right: 260px;
        }

        /* Classic Windows 3D Borders */
        .inset-border {
            border-top: 2px solid var(--border-dark);
            border-left: 2px solid var(--border-dark);
            border-right: 2px solid var(--border-light);
            border-bottom: 2px solid var(--border-light);
            background: white;
        }
        
        .outset-border {
            border-top: 2px solid var(--border-light);
            border-left: 2px solid var(--border-light);
            border-right: 2px solid var(--border-dark);
            border-bottom: 2px solid var(--border-dark);
            background: var(--bg-color);
        }

        /* Header */
        .legacy-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding: 12px 18px;
            border-bottom: 2px solid #3b82f6;
            background: linear-gradient(135deg, #3b82f6, #2563eb, #1e40af);
            border-radius: 18px;
            box-shadow: 0 12px 22px rgba(59, 130, 246, 0.4);
        }

        .bakery-title {
            text-align: center;
            font-family: 'Noto Nastaliq Urdu', serif;
            font-size: 2.6rem;
            font-weight: 800;
            color: #1e40af;
            line-height: 1.1;
            text-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        }

        .legacy-form-container {
            border: 1px solid #3b82f6;
            padding: 14px;
            margin-bottom: 16px;
            border-radius: 14px;
            background: #e8f4fd;
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.25);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            align-items: center;
        }

        .legacy-label {
            font-size: 0.9rem;
            font-weight: bold;
            color: #000;
            margin-bottom: 2px;
        }

        .legacy-input {
            border: 2px inset #d4d0c8; /* Windows 95/98/2000 style inset */
            border-top-color: #404040;
            border-left-color: #404040;
            border-right-color: #fff;
            border-bottom-color: #fff;
            padding: 4px;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
            background: #fff;
            font-family: 'Outfit', 'Noto Nastaliq Urdu', sans-serif;
            height: 32px;
        }

        .legacy-input:focus {
            outline: 1px dotted #000;
            background: #fff;
        }

        /* Layout */
        .page-layout {
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 14px;
        }

        .legacy-table-section {
            border: 1px solid #3b82f6;
            margin-bottom: 12px;
            background: #e8f4fd;
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.25);
        }

        .legacy-table-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            padding: 8px 12px;
            font-weight: 700;
            border-bottom: 1px solid #2563eb;
            border-top: 1px solid #1e40af;
            font-family: 'Noto Nastaliq Urdu', serif;
            font-size: 1.1rem;
            color: #ffffff;
            text-align: right;
            border-radius: 14px 14px 0 0;
        }

        .legacy-table-container {
            overflow-x: auto;
            border: 2px inset var(--bg-color);
        }

        .legacy-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            table-layout: fixed;
        }

        .legacy-table th {
            background: #ffedd5;
            color: #3f2513;
            font-weight: 600;
            padding: 4px;
            border: 1px solid #fed7aa;
            text-align: center;
            font-family: 'Noto Nastaliq Urdu', sans-serif;
            white-space: nowrap;
            height: 30px;
        }

        .legacy-table td {
            border: 1px solid #bae6fd;
            padding: 0;
            background: #e8f4fd;
            height: 28px;
        }

        .legacy-table tbody tr:nth-child(even) td {
            background-color: #dbeafe;
        }

        .is-invalid {
            border: 2px solid red !important;
            background-color: #ffe6e6 !important;
        }

        .legacy-table input {
            width: 100%;
            height: 100%;
            border: none;
            padding: 0 4px;
            font-size: 0.95rem;
            text-align: center;
            background: transparent;
            font-family: 'Outfit', sans-serif;
            color: #000;
            margin: 0;
            box-sizing: border-box; /* Fix: Prevent padding from increasing width */
            display: block; /* Fix: Ensure input fills the cell */
        }

        .legacy-table input:focus {
            background-color: #fff;
            color: #000;
            outline: none;
        }
        
        .legacy-table input.urdu-text {
            font-family: 'Noto Nastaliq Urdu', serif;
        }

        .page-layout .tables-area .legacy-table-section:first-of-type .legacy-table th {
            height: 24px;
            font-size: 0.85rem;
        }

        .page-layout .tables-area .legacy-table-section:first-of-type .legacy-table td {
            height: 24px;
        }

        .page-layout .tables-area .legacy-table-section:first-of-type .legacy-table input {
            font-size: 0.8rem;
            padding: 0 2px;
        }

        /* Summary Panel (Left Side in RTL) */
        .summary-box {
            background: #e8f4fd;
            border: 1px solid #3b82f6;
            padding: 12px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        
        .summary-box h3 {
            font-size: 1.25rem;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #2563eb;
            text-align: center;
            font-family: 'Noto Nastaliq Urdu', serif;
            color: #1e40af;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 0.9rem;
            font-weight: bold;
            color: #000;
        }
        
        .summary-input {
            width: 80px;
            border: 2px inset #dbeafe;
            border-top-color: #3b82f6;
            border-left-color: #3b82f6;
            border-right-color: #f0f9ff;
            border-bottom-color: #f0f9ff;
            background: #f0f9ff;
            color: #1e40af;
            padding: 2px 4px;
            text-align: right;
            font-family: 'Noto Nastaliq Urdu', sans-serif;
            height: 28px;
            font-size: 0.9rem;
            border-radius: 4px;
        }
        
        .summary-input:focus {
            outline: none;
            background: #f0f9ff;
            color: #1e40af;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            padding: 5px;
            margin-top: 10px;
            border: 2px outset #f0f9ff;
            border-radius: 8px;
        }

        .total-label {
            font-size: 0.9rem;
            font-weight: bold;
        }

        .total-amount {
            /* Match .summary-input styling exactly */
            width: 80px;
            border: 2px inset #dbeafe;
            border-top-color: #3b82f6;
            border-left-color: #3b82f6;
            border-right-color: #f0f9ff;
            border-bottom-color: #f0f9ff;
            background: #f0f9ff;
            color: #1e40af;
            padding: 2px 4px;
            text-align: right;
            font-family: 'Noto Nastaliq Urdu', sans-serif;
            height: 28px;
            font-size: 0.9rem;
            border-radius: 4px;
        }

        #total-expenses-row {
            background: #e8f4fd;
            border: 1px solid #3b82f6;
            color: #000;
            margin-top: 5px;
            padding: 8px;
            border-radius: 8px;
        }

        .btn-legacy {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-legacy:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.4);
        }

        .btn-legacy:active {
            transform: translateY(0);
            box-shadow: 0 4px 8px rgba(15, 23, 42, 0.4);
        }

        .btn-legacy.bakery-print-btn {
            position: relative;
            isolation: isolate;
            min-width: 145px;
            height: 36px;
            padding: 0 18px;
            border-radius: 10px;
            border: 1px solid #2563eb;
            background: linear-gradient(180deg, #4f8cff 0%, #2f6fe8 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.95rem;
            font-weight: 700;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn-legacy.bakery-print-btn::before {
            content: '';
            position: absolute;
            inset: 1px 1px auto 1px;
            height: 45%;
            border-radius: 9px 9px 6px 6px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            z-index: -1;
            pointer-events: none;
        }

        .btn-legacy.bakery-print-btn:hover,
        .btn-legacy.bakery-print-btn:focus {
            color: #ffffff;
            transform: translateY(-1px);
            filter: brightness(1.04);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.38);
        }

        .btn-legacy.bakery-print-btn:active {
            transform: translateY(0);
            box-shadow: 0 5px 10px rgba(37, 99, 235, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.28);
        }

        .btn-legacy.bakery-print-btn:focus-visible {
            outline: 2px solid #93c5fd;
            outline-offset: 2px;
        }

        .btn-legacy.bakery-print-btn:disabled {
            cursor: not-allowed;
            opacity: 0.75;
            transform: none;
            filter: grayscale(0.15);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.25);
        }

        /* Scrollbars */
        ::-webkit-scrollbar {
            width: 16px;
            height: 16px;
        }
        ::-webkit-scrollbar-track {
            background: #e8f4fd; 
        }
        ::-webkit-scrollbar-thumb {
            background: #3b82f6; 
            border: 1px solid #f0f9ff;
            box-shadow: inset 1px 1px 0 #dbeafe;
        }
        ::-webkit-scrollbar-button {
            background: #2563eb;
            display: block;
            height: 16px;
            width: 16px;
            border: 1px solid #3b82f6;
        }

        .purchaser-print-container {
            display: none;
        }

        /* Bakery Page Dark Mode */
        body.dark-mode {
            background: #0f172a !important;
            background-image: none !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .legacy-header {
            background: linear-gradient(135deg, #1e293b, #0f172a, #020617) !important;
            border-bottom-color: #334155 !important;
            box-shadow: 0 12px 22px rgba(2, 6, 23, 0.6) !important;
        }

        body.dark-mode .legacy-title,
        body.dark-mode .bakery-title {
            color: #e2e8f0 !important;
            text-shadow: none !important;
        }

        body.dark-mode .legacy-form-container,
        body.dark-mode .legacy-table-section,
        body.dark-mode .summary-box,
        body.dark-mode .legacy-table-container,
        body.dark-mode #total-expenses-row {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 8px 18px rgba(2, 6, 23, 0.45) !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .legacy-table-header {
            background: linear-gradient(135deg, #334155, #1e293b) !important;
            border-bottom-color: #334155 !important;
            border-top-color: #475569 !important;
            color: #f1f5f9 !important;
        }

        body.dark-mode .legacy-table th {
            background: #111827 !important;
            color: #94a3b8 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .legacy-table td {
            background: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-mode .legacy-table tbody tr:nth-child(even) td {
            background: #243244 !important;
        }

        body.dark-mode .legacy-label,
        body.dark-mode .summary-label,
        body.dark-mode .total-label,
        body.dark-mode .summary-box h3,
        body.dark-mode #purchaser-dues-text {
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .legacy-input,
        body.dark-mode .summary-input,
        body.dark-mode .total-amount,
        body.dark-mode .legacy-table input,
        body.dark-mode .purchaser-input,
        body.dark-mode input[name*="[daily_amount]"],
        body.dark-mode input[name*="[row_total]"] {
            background: #0f172a !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .legacy-input:focus,
        body.dark-mode .summary-input:focus,
        body.dark-mode .total-amount:focus,
        body.dark-mode .legacy-table input:focus,
        body.dark-mode .purchaser-input:focus {
            background: #0f172a !important;
            color: #f8fafc !important;
            border-color: #60a5fa !important;
            outline: none !important;
        }

        body.dark-mode .purchaser-input[readonly] {
            background: #243244 !important;
            color: #94a3b8 !important;
        }

        body.dark-mode .total-row {
            background: linear-gradient(135deg, #334155, #1e293b) !important;
            border-color: #475569 !important;
            color: #f8fafc !important;
        }

        body.dark-mode .modal-window,
        body.dark-mode .modal-content {
            background: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .modal-title-bar {
            background: #334155 !important;
            color: #f8fafc !important;
            border-bottom-color: #475569 !important;
        }

        body.dark-mode .modal-search-bar label,
        body.dark-mode .modal-table th,
        body.dark-mode .modal-table td {
            color: #e2e8f0 !important;
        }

        body.dark-mode .modal-search-bar input,
        body.dark-mode .modal-table-container {
            background: #0f172a !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .modal-table th {
            background: #111827 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .modal-table tr:hover {
            background: #334155 !important;
        }

        body.dark-mode .modal-table tr.selected {
            background-color: #1d4ed8 !important;
            color: #f8fafc !important;
        }

        body.dark-mode .modal-btn {
            background: #334155 !important;
            color: #e2e8f0 !important;
            border-color: #475569 !important;
        }

        body.dark-mode .modal-btn:active {
            border-color: #64748b !important;
        }

        body.dark-mode .modal-close-btn-x,
        body.dark-mode .modal-close-btn {
            color: #f8fafc !important;
        }

        body.dark-mode .modal-overlay [style*="background: #eee"],
        body.dark-mode .modal-overlay [style*="background:#eee"] {
            background: #0f172a !important;
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }

        body.dark-mode .modal-overlay [style*="color: black"],
        body.dark-mode .modal-overlay [style*="color:black"],
        body.dark-mode .modal-overlay [style*="color: #666"],
        body.dark-mode .modal-overlay [style*="color:#666"] {
            color: #e2e8f0 !important;
        }

        body.dark-mode #bakeryForm [style*="background: #d4d0c8"],
        body.dark-mode #bakeryForm [style*="background:#d4d0c8"],
        body.dark-mode #bakeryForm [style*="background: #e8f4fd"],
        body.dark-mode #bakeryForm [style*="background:#e8f4fd"],
        body.dark-mode #bakeryForm [style*="background: #dbeafe"],
        body.dark-mode #bakeryForm [style*="background:#dbeafe"],
        body.dark-mode #bakeryForm [style*="background: #f0f9ff"],
        body.dark-mode #bakeryForm [style*="background:#f0f9ff"],
        body.dark-mode #bakeryForm [style*="background: #eee"],
        body.dark-mode #bakeryForm [style*="background:#eee"] {
            background: #1e293b !important;
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        body.dark-mode #bakeryForm [style*="color: #000"],
        body.dark-mode #bakeryForm [style*="color:#000"],
        body.dark-mode #bakeryForm [style*="color: #1e40af"],
        body.dark-mode #bakeryForm [style*="color:#1e40af"] {
            color: #e2e8f0 !important;
        }

         /* Print Specific Styles */
         @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
            body * {
                visibility: hidden;
            }
            
            /* Reference Table Print Mode */
            body.print-mode-reference .print-items-only,
            body.print-mode-reference .print-items-only * {
                visibility: visible;
            }
            body.print-mode-reference .print-items-only {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                z-index: 9999;
            }
            body.print-mode-reference .print-items-only button {
                display: none;
            }
            body.print-mode-reference .print-items-only table {
                width: 100%;
                border-collapse: collapse;
                border: 2px solid #000;
            }
            body.print-mode-reference .print-items-only th,
            body.print-mode-reference .print-items-only td {
                border: 1px solid #000 !important;
                padding: 5px;
                color: #000;
            }
            body.print-mode-reference .card-header {
                background: transparent !important;
                color: #000 !important;
                border-bottom: 2px solid #000 !important;
                font-size: 1.5rem;
            }
            body.print-mode-reference .card {
                border: none !important;
            }

            /* Items Detail Print Mode */
             body.print-mode-detail .legacy-table-section:first-of-type, 
             body.print-mode-detail .legacy-table-section:first-of-type * {
                 visibility: visible;
             }
             body.print-mode-detail .legacy-table-section:first-of-type {
                 position: absolute;
                 left: 0;
                 top: 0;
                 width: 100%;
                 margin: 0;
                 padding: 0;
             }
             body.print-mode-detail .legacy-table-header {
                 background: transparent !important;
                 color: #000 !important;
                 border-bottom: 2px solid #000;
                 text-align: center;
                 font-size: 1.5rem;
                 margin-bottom: 20px;
             }
             body.print-mode-detail table {
                 width: 100%;
                 border-collapse: collapse;
                 border: 2px solid #000;
             }
             body.print-mode-detail th,
             body.print-mode-detail td {
                 border: 1px solid #000 !important;
                 padding: 8px;
                 color: #000;
                 text-align: center;
             }
             body.print-mode-detail input {
                 border: none;
                 background: transparent;
                 width: 100%;
                 text-align: center;
                 color: #000;
                 font-weight: bold;
             }

            /* Purchaser Receipt Print Mode */
            body.print-mode-purchaser > :not(.purchaser-print-container) {
                display: none !important;
            }

            body.print-mode-purchaser .purchaser-print-container,
            body.print-mode-purchaser .purchaser-print-container * {
                visibility: visible !important;
            }

            body.print-mode-purchaser .purchaser-print-container {
                display: block !important;
                position: relative;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                background: #fff;
                color: #000;
                z-index: 9999;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body.print-mode-purchaser .purchaser-receipt-sheet {
                width: 108mm;
                min-height: 250mm;
                margin: 0 auto;
                border: 1px solid #d8b38f;
                box-sizing: border-box;
                font-family: 'Noto Nastaliq Urdu', serif;
            }

            body.print-mode-purchaser .purchaser-receipt-header {
                display: flex;
                gap: 8px;
                align-items: stretch;
                padding: 8px 10px;
                border-bottom: 1px solid #d6a173;
                background: linear-gradient(180deg, #f4cd99 0%, #e58b45 100%);
            }

            body.print-mode-purchaser .purchaser-logo-box {
                width: 66px;
                min-width: 66px;
                border: 1px solid #d8b38f;
                background: #fff;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-family: 'Outfit', sans-serif;
                color: #1f2937;
                line-height: 1.1;
            }

            body.print-mode-purchaser .purchaser-logo-box .logo-main {
                font-size: 1.35rem;
                font-weight: 700;
                letter-spacing: 0.05em;
            }

            body.print-mode-purchaser .purchaser-logo-box .logo-sub {
                font-size: 0.55rem;
                font-weight: 600;
            }

            body.print-mode-purchaser .purchaser-shop-info {
                flex: 1;
                text-align: center;
                color: #fff;
            }

            body.print-mode-purchaser .purchaser-shop-info h2 {
                margin: 0;
                font-size: 1.9rem;
                font-weight: 700;
                line-height: 1.05;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
            }

            body.print-mode-purchaser .purchaser-shop-info p {
                margin: 2px 0 4px;
                font-size: 0.92rem;
                font-weight: 600;
                color: #fff7ed;
            }

            body.print-mode-purchaser .purchaser-shop-phones {
                display: flex;
                justify-content: center;
                gap: 10px;
                flex-wrap: wrap;
                font-family: 'Outfit', sans-serif;
                font-size: 0.74rem;
                font-weight: 600;
                color: #fff7ed;
            }

            body.print-mode-purchaser .purchaser-meta-strip,
            body.print-mode-purchaser .purchaser-name-strip {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px 10px;
                border-bottom: 1px solid #d8b38f;
                font-size: 1rem;
            }

            body.print-mode-purchaser .purchaser-name-strip {
                justify-content: flex-start;
                gap: 8px;
            }

            body.print-mode-purchaser .purchaser-meta-value,
            body.print-mode-purchaser .purchaser-name-value {
                font-family: 'Outfit', sans-serif;
                font-weight: 600;
                color: #1f2937;
            }

            body.print-mode-purchaser .purchaser-print-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            body.print-mode-purchaser .purchaser-print-table th {
                border: 1px solid #d8b38f;
                background: #e8bb90;
                color: #4c1d06;
                padding: 5px 4px;
                font-size: 0.95rem;
                font-weight: 700;
                text-align: center;
            }

            body.print-mode-purchaser .purchaser-print-table td {
                border-left: 1px solid #e4c8ae;
                border-right: 1px solid #e4c8ae;
                border-bottom: 1px solid #edd5bf;
                height: 24px;
                padding: 2px 4px;
                font-size: 0.92rem;
                text-align: center;
                vertical-align: middle;
            }

            body.print-mode-purchaser .purchaser-print-table .p-col-desc {
                text-align: right;
                padding-right: 8px;
                font-family: 'Noto Nastaliq Urdu', serif;
                font-size: 1rem;
            }

            body.print-mode-purchaser .purchaser-print-table tfoot td {
                border-top: 2px solid #8f5b31;
                border-bottom: 2px solid #8f5b31;
                font-weight: 700;
                background: #f8e7d8;
            }

            body.print-mode-purchaser .purchaser-print-footer {
                border-top: 1px solid #d8b38f;
                padding: 8px 10px;
                text-align: center;
                font-size: 0.86rem;
                color: #7c3f12;
                font-weight: 700;
            }

             /* Bill Print Mode (Faheem Khan & Co) */
            body.print-mode-bill > :not(.bill-print-container) {
                display: none !important;
            }

            body.print-mode-bill .bill-print-container,
            body.print-mode-bill .bill-print-container * {
                visibility: visible !important;
            }

            body.print-mode-bill .bill-print-container {
                display: block !important;
                position: relative;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                z-index: 9999;
                font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', serif;
                padding: 0;
                margin: 0;
                min-height: auto;
                box-sizing: border-box;
            }

            /* Print Container Internal Styles */
            .bill-header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid black; padding-bottom: 10px; }
            .bill-header h1 { margin: 0; font-size: 2.5rem; font-weight: bold; }
            .bill-header p { margin: 5px 0 0; font-size: 1.2rem; }
            .contact-info { margin-top: 5px; font-size: 1rem; }
            .bill-info-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #000; padding-bottom: 5px; }
            .bill-grid { display: flex; border: 2px solid black; min-height: auto; }
            .expenses-column { width: 30%; border-left: 2px solid black; display: flex; flex-direction: column; }
            .expenses-list { padding: 10px; flex-grow: 1; font-size: 1.1rem; }
            .items-column { width: 70%; display: flex; flex-direction: column; }
            .bill-table { width: 100%; border-collapse: collapse; flex-grow: 1; }
            .bill-table th { border-bottom: 2px solid black; border-left: 1px solid black; padding: 5px; background: #eee; font-weight: bold; }
            .bill-table td { border-left: 1px solid black; padding: 5px; text-align: center; border-bottom: 1px solid #ddd; }

            /* Underline only money cells in bill print */
             .bill-print-container .bill-money,
             .bill-print-container .bill-money-cell {
                 text-decoration: underline;
                 text-decoration-thickness: 1px;
                 text-underline-offset: 2px;
             }
            body.print-mode-bill .bill-info-row,
            body.print-mode-bill .expenses-column,
            body.print-mode-bill .bill-footer {
                display: none !important;
            }
            body.print-mode-bill .bill-grid {
                display: block !important;
            }
            body.print-mode-bill .bill-grid .items-column {
                width: 100% !important;
                display: block !important;
            }
            .bill-footer { margin-top: 10px; text-align: center; font-size: 0.9rem; font-weight: bold; border-top: 1px solid black; padding-top: 5px; }
         }
</head>
<body class="rtl">

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    @include('components.sidebar')

    <div class="main">
    
    <!-- Purchaser Modal -->
    <div id="purchaser-modal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="purchaserModalTitle" onclick="handlePurchaserOverlayClick(event)">
        <div class="modal-window" style="width: 600px; height: 550px;" onclick="event.stopPropagation()">
            <div class="modal-title-bar" style="display: flex; justify-content: space-between; align-items: center; padding-right: 10px;">
                <span id="purchaserModalTitle" style="flex-grow: 1; text-align: center;">خریدار منتخب کریں</span>
                <button type="button" class="modal-close-btn-x" onclick="closePurchaserModal()" aria-label="بند کریں" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: black; font-weight: bold;">&times;</button>
            </div>
            <div class="modal-content">
                <div class="modal-search-bar">
                    <label for="purchaser-search">تلاش</label>
                    <input type="text" id="purchaser-search" onkeyup="populatePurchaserModal(this.value)" value="" autocomplete="off" placeholder="تلاش..." style="flex: 1; padding: 5px;">
                </div>
                
                <div class="modal-table-container" style="flex: 1; overflow-y: auto; border: 1px solid #ccc;">
                    <table class="modal-table" id="purchaser-search-table">
                        <thead style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th style="width: 20%;">کوڈ</th>
                                <th style="width: 40%;">نام</th>
                                <th style="width: 40%;">فون</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows populated by JS -->
                        </tbody>
                    </table>
                </div>
                
                <div class="modal-footer" style="margin-top: 10px;">
                    <button type="button" class="modal-btn" id="select-purchaser-btn">منتخب کریں</button>
                    <button type="button" class="modal-btn" onclick="closePurchaserModal()">بند کریں</button>
                </div>
            </div>
        </div>
    </div>
        
        <!-- Legacy Header -->
        <div class="legacy-header">
            <h1 class="legacy-title">{{ __('بیکری بک') }}</h1>
            <div style="display: flex; gap: 10px; align-items: center;">
                @include('components.user-role-display')
            </div>
        </div>

        <form id="bakeryForm" method="POST" action="{{ $currentRecord ? route('payment.update', $currentRecord->id) : route('payment.store') }}">
            @csrf
            <input type="hidden" name="record_id" id="bakeryRecordId" value="{{ $currentRecord->id ?? '' }}">
            <input type="hidden" name="item1_code" value="">
            <input type="hidden" name="item1_name" value="">
            <input type="hidden" name="item1_date" value="">
            <input type="hidden" name="item1_packing" value="">
            <input type="hidden" name="item1_packing_code" value="">
            <input type="hidden" name="item1_quantity" value="">
            <input type="hidden" name="item1_labor" value="">
            <input type="hidden" name="item1_commission_rate" value="">
            <input type="hidden" name="item1_total" value="">
            
            <input type="hidden" name="item2_code" value="">
            <input type="hidden" name="item2_name" value="">
            <input type="hidden" name="item2_date" value="">
            <input type="hidden" name="item2_packing" value="">
            <input type="hidden" name="item2_packing_code" value="">
            <input type="hidden" name="item2_quantity" value="">
            <input type="hidden" name="item2_labor" value="">
            <input type="hidden" name="item2_commission_rate" value="">
            <input type="hidden" name="item2_total" value="">

            <input type="hidden" name="item2_code" value="">
            <input type="hidden" name="item2_name" value="">
            <input type="hidden" name="item2_date" value="">
            <input type="hidden" name="item2_packing" value="">
            <input type="hidden" name="item2_packing_code" value="">
            <input type="hidden" name="item2_quantity" value="">
            <input type="hidden" name="item2_labor" value="">
            <input type="hidden" name="item2_commission_rate" value="">
            <input type="hidden" name="item2_total" value="">
            
            <input type="hidden" name="item3_code" value="">
            <input type="hidden" name="item3_name" value="">
            <input type="hidden" name="item3_date" value="">
            <input type="hidden" name="item3_packing" value="">
            <input type="hidden" name="item3_packing_code" value="">
            <input type="hidden" name="item3_quantity" value="">
            <input type="hidden" name="item3_labor" value="">
            <input type="hidden" name="item3_commission_rate" value="">
            <input type="hidden" name="item3_total" value="">

            <!-- Top Basic Info Grid -->
            <div class="legacy-form-container">
                <div class="info-grid">
                    <div class="legacy-input-group">
                        <label class="legacy-label">{{ __('تاریخ') }} </label>
                        <input type="date" name="record_date" class="legacy-input" value="{{ $currentRecord && $currentRecord->record_date ? $currentRecord->record_date->format('Y-m-d') : date('Y-m-d') }}">
                    </div>
                    <div class="legacy-input-group">
                        <label class="legacy-label">{{ __('بیوپاری') }} </label>
                        <input type="text" name="trader" class="legacy-input urdu-text" value="{{ $currentRecord->trader ?? '' }}" data-urdu="true" placeholder="نام لکھیں">
                    </div>
                    <div class="legacy-input-group">
                        <label class="legacy-label">{{ __('بکری نمبر') }} </label>
                        <input type="text" name="goat_number" class="legacy-input" value="{{ $currentRecord->goat_number ?? '' }}" placeholder="123">
                    </div>
                    <div class="legacy-input-group">
                        <label class="legacy-label">{{ __('ٹرک نمبر') }} </label>
                        <input type="text" name="truck_number" class="legacy-input" value="{{ $currentRecord->truck_number ?? '' }}" placeholder="ABC-1234">
                    </div>
                </div>
            </div>

            <div class="page-layout">
                
                <!-- Right Side: Tables -->
                <div class="tables-area">
                    
                    <!-- Items Table -->
                    <div class="legacy-table-section" style="margin: 0; padding: 0;">
                        <div class="legacy-table-header">
                            <span>{{ __('اشیاء کی تفصیل') }} </span>
                        </div>
                        <div class="legacy-table-container" style="overflow-x: auto; width: 100%; margin: 0; padding: 0;">
                            <table class="legacy-table dense-table" style="width: 95%; margin: 0 auto;">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">{{ __('کوڈ') }}</th>
                                        <th style="width: 10%;">{{ __('تاریخ') }}</th>
                                        <th style="width: 10%;">{{ __('اشیاء') }}</th>
                                        <th style="width: 10%;">{{ __('پیکنگ') }}</th>
                                        <th style="width: 8%;">{{ __('تعداد') }}</th>
                                        <th style="width: 8%;">{{ __('مزدوری') }}</th>
                                        <th style="width: 8%;">{{ __('کمیشن') }}</th>
                                        <th style="width: 8%;">{{ __('ٹوٹل') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="bakery-items-tbody">
                                    @for($i=0; $i<6; $i++)
                                        @php $item = $currentRecord && isset($currentRecord->items[$i]) ? $currentRecord->items[$i] : null; @endphp
                                        <tr data-row-index="{{$i}}">
                                            <td><input type="text" name="items[{{$i}}][code]" value="{{$item->code ?? ''}}" class="text-center" style="width: 90%; font-size: 0.8rem;"></td>
                                            <td><input type="date" name="items[{{$i}}][item_date]" value="{{$item && $item->item_date ? $item->item_date->format('Y-m-d') : ''}}" style="width: 90%; font-size: 0.8rem;" class="text-center"></td>
                                            <td><input type="text" name="items[{{$i}}][item_type]" class="urdu-text item-type-input" value="{{$item->item_type ?? ''}}" data-urdu="true" readonly onclick="openItemTypeModal(this, {{$i}}, event)" style="cursor: pointer; width: 85%;"></td>
                                            <td>
                                                <input type="text" name="items[{{$i}}][packing]" class="urdu-text packing-input" value="{{$item->packing ?? ''}}" data-urdu="true" readonly onclick="openPackagingModal(this, {{$i}})" style="cursor: pointer; width: 85%;">
                                                <input type="hidden" name="items[{{$i}}][packing_code]" class="packing-code-input" value="{{$item->packing_code ?? '' }}">
                                            </td>
                                            <td><input type="number" name="items[{{$i}}][quantity]" value="{{$item->quantity ?? ''}}" style="width: 85%; font-size: 0.8rem;" class="text-center"></td>
                                            <td><input type="number" name="items[{{$i}}][labor]" value="{{$item->labor ?? ''}}" style="width: 85%; font-size: 0.8rem;" class="text-center"></td>
                                            <td><input type="number" name="items[{{$i}}][commission_rate]" value="{{$item->commission_rate ?? ''}}" style="width: 85%; font-size: 0.8rem;" class="text-center"></td>
                                            <td><input type="number"  name="items[{{$i}}][total]" value="" readonly style="background: #f8fafc; width: 85%;"></td>
                                        </tr>
                                    @endfor
                                </tbody>
                                <tfoot>
                                    <tr style="background: #d4d0c8; font-weight: bold;">
                                        <td colspan="4" style="text-align: right; padding-right: 10px;">{{ __('ٹوٹل') }}</td>
                                        <td><input type="text" id="total-qty" readonly class="text-center" style="width: 90%; background: #eee; border: none; font-weight: bold;"></td>
                                        <td colspan="2"></td>
                                        <td><input type="text" id="total-amount" readonly class="text-center" style="width: 90%; background: #eee; border: none; font-weight: bold;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- Unified Save + Print Button -->
                    <div class="text-center no-print-screen" style="margin: 0; padding: 0; display: flex; justify-content: center; gap: 10px;">
                        <button id="bakerySavePrintBtn" type="button" class="btn-legacy bakery-print-btn" onclick="saveAndPrintBakery()">
                            <i class="fa fa-print"></i> {{ __('محفوظ اور پرنٹ') }}
                        </button>
                    </div>
<!-- Purchaser Table -->
                    <div class="legacy-table-section" style="margin: 0; padding: 0;">
                        <div class="legacy-table-header">
                            <span>{{ __('خریدار کی تفصیل') }}</span>
                        </div>
                        <div class="legacy-table-container" style="overflow-x: auto; min-width: 100%; max-width: 100%; margin: 0; padding: 0;">
                            <table class="legacy-table dense-table" style="min-width: 1080px; margin: 0;">
                                <thead>
                                    <tr>
                                        <th style="width: 6%;">{{ __('کوڈ ') }}</th>
                                        

                                        <th style="width: 15%;">{{ __('نام') }}</th>
                                        <th style="width: 8%;">{{ __('نرخ خریدار') }}</th>
                                        <th style="width: 8%;">{{ __('رقم خریدار') }}</th>
                                          <th style="width: 10%;">{{ __('آئٹم ریٹ') }}</th>
                                        <th style="width: 10%;">{{ __('لاگا ریٹ') }}</th>
                                         <th style="width: 10%;">{{ __('ٹوٹل') }}</th>

                                    </tr>
                                </thead>
                                <tbody id="purchaser-tbody">
                                    @for($i=0; $i<6; $i++)
                                        @php $trans = $currentRecord && isset($currentRecord->transactions[$i]) ? $currentRecord->transactions[$i] : null; @endphp
                                        <tr class="purchaser-row" data-row-index="{{$i}}">
                                            <td><input type="text" name="transactions[{{$i}}][book_code]" value="{{$trans->book_code ?? ''}}" style="width: 84%; font-size: 0.85rem; cursor: pointer;" class="text-center purchaser-input required-field" list="purchaserCodesList" autocomplete="off" readonly onclick="openPurchaserModal(this.closest('tr'))"></td>
                                            <td><input type="text" name="transactions[{{$i}}][book]" class="urdu-text purchaser-input required-field" value="{{$trans->book ?? ''}}" data-urdu="true" style="width: 84%; font-size: 0.85rem; cursor: pointer;" onclick="openPurchaserModal(this.closest('tr'))"></td>
                                            <td><input type="number" name="transactions[{{$i}}][purchaser_rate]" value="{{$trans->purchaser_rate ?? ''}}" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                                            <td><input type="number" name="transactions[{{$i}}][purchaser_amount]" value="{{$trans->purchaser_amount ?? ''}}" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                                            <td><input type="number" name="transactions[{{$i}}][item_rate]" value="{{$trans->item_rate ?? ''}}" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                                            <td><input type="number" name="transactions[{{$i}}][laga_rate]" value="{{$trans->laga_rate ?? ''}}" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                                            <td><input type="number" name="transactions[{{$i}}][row_total]" value="{{$trans->row_total ?? ''}}" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input"></td>
                                            <td style="text-align: center;">
                                                <button type="button" onclick="addNewPurchaserRow()" style="padding: 4px 8px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                <button type="button" onclick="removePurchaserRow(this)" style="padding: 4px 8px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8rem; margin-right: 5px;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                                <tfoot>
                                    <tr style="background: #d4d0c8; font-weight: bold;">
                                        <td colspan="6" style="text-align: right; padding-right: 10px;">{{ __('ٹوٹل') }}</td>
                                        <td><input type="text" id="total-laga-amount" readonly class="text-center" style="width: 84%; background: #eee; border: none; font-weight: bold;"></td>
                                        <td><input type="text" id="total-row-total" readonly class="text-center" style="width: 84%; background: #eee; border: none; font-weight: bold;"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- Purchaser Codes Datalist -->
                        <datalist id="purchaserCodesList">
                            @if(isset($lagas) && $lagas->count() > 0)
                                @foreach($lagas as $laga)
                                    <option value="{{ $laga->code }}" data-name="{{ $laga->name }}" data-mobile="{{ $laga->mobile }}"></option>
                                @endforeach
                            @endif
                        </datalist>
                    </div>
                    <div class="text-center no-print-screen" style="margin: 10px 0 6px 0;">
                        <button type="button" class="btn-legacy bakery-print-btn" onclick="printPurchaserDetailOnly()">
                            <i class="fa fa-print"></i> {{ __('پرنٹ خریدار تفصیل') }}
                        </button>
                    </div>
                </div>

                <!-- Left Side: Summary -->
                <div class="summary-area">
                    <div class="summary-box">
                        <h3 style="margin-top:0; border-bottom: 1px solid white; padding-bottom: 10px; margin-bottom: 15px;">{{ __('اخراجات') }}</h3>
                        
                        <div class="summary-row">
                            <span class="summary-label">{{ __('خام رقم') }}</span>
                            <input type="number" step="" name="raw_goat" class="summary-input" value="{{ $currentRecord->raw_goat ?? 0 }}">
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">{{ __('کرایہ') }}</span>
                            <input type="number" step="" name="fare" class="summary-input" value="{{ $currentRecord->fare ?? 0 }}">
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">{{ __('کمیشن') }}</span>
                            <input type="number" step="" name="commission" class="summary-input" value="{{ $currentRecord->commission ?? 0 }}">
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">{{ __('مزدوری') }}</span>
                            <input type="number" step="" name="labor" class="summary-input" value="{{ $currentRecord->labor ?? 0 }}">
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">{{ __('منشیانہ') }}</span>
                            <input type="number" step="" name="mashiana" class="summary-input" value="{{ $currentRecord->mashiana ?? 0 }}">
                        </div>
                        {{-- <div class="summary-row">
                            <span class="summary-label">{{ __('اسٹامپ') }}</span>
                            <input type="number" step="0.01" name="stamp" class="summary-input" value="{{ $currentRecord->stamp ?? 0 }}">
                        </div> --}}
                        <div class="summary-row">
                            <span class="summary-label">{{ __('نقد') }}</span>
                            <input type="number" step="" name="other_expenses" class="summary-input" value="{{ $currentRecord->other_expenses ?? 0 }}">
                        </div>

                        <div class="total-row" id="total-expenses-row">
                            <span class="total-label">{{ __('ٹوٹل اخراجات') }}</span>
                            <input type="text" id="total-expenses" readonly class="total-amount" style="background: #eee; color: #000; border: 2px inset #d4d0c8; text-align: right; padding: 2px 4px; font-weight: bold;">
                        </div>

                        <div class="total-row">
                            <span class="total-label">{{ __('صافی بیکری') }}</span>
                            <input type="text" id="net-bakery" readonly class="total-amount" style="background: #eee; color: #000; border: 2px inset #d4d0c8; text-align: right; padding: 2px 4px; font-weight: bold;">
                        </div>
                    </div>

                    <div id="purchaser-dues-alert" style="display:none; margin-top: 15px;">
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 8px 10px; border-radius: 6px; border: 2px solid #b91c1c; background: #fee2e2;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <i class="fa fa-exclamation-triangle" style="color:#b91c1c;"></i>
                                <span id="purchaser-dues-text" class="urdu-text" style="font-weight:bold; color:#b91c1c;"></span>
                            </div>
                            <div id="purchaser-dues-breakdown" style="font-size:0.9rem; color:#374151;"></div>
                        </div>
                    </div>
</div>

            </div>
        </form>
    </div>

    <!-- Datalists for Auto-complete -->
    <datalist id="itemsList">
        @foreach($items as $item)
            <option value="{{ $item->urdu_name ?? $item->name }}" data-code="{{ $item->code }}">{{ $item->code }}</option>
        @endforeach
    </datalist>

    <datalist id="purchasersList">
        @foreach($lagas as $laga)
            <option value="{{ $laga->name }}" data-code="{{ $laga->id }}">{{ $laga->address ?? '' }}</option>
        @endforeach
    </datalist>

    <!-- JavaScript for Calculations & Logic -->
    <script>
        // Test global scope

        // Print Functions
        function printItemsTable() {
            document.body.classList.add('print-mode-reference');
            window.print();
            setTimeout(() => {
                document.body.classList.remove('print-mode-reference');
            }, 500);
        }

        function printItemsDetailOnly() {
            document.body.classList.add('print-mode-detail');
            window.print();
            setTimeout(() => {
                document.body.classList.remove('print-mode-detail');
            }, 500);
        }

        function formatNumberForPrint(value) {
            const number = Number(value);
            if (!Number.isFinite(number)) {
                return '';
            }
            const isWhole = Math.abs(number - Math.trunc(number)) < 0.00001;
            return number.toLocaleString('en-US', {
                minimumFractionDigits: isWhole ? 0 : 2,
                maximumFractionDigits: 2
            });
        }

        function formatDateForPrint(value) {
            if (!value) {
                return '-';
            }
            const parts = value.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0].slice(-2)}`;
            }
            const parsed = new Date(value);
            if (Number.isNaN(parsed.getTime())) {
                return value;
            }
            const day = String(parsed.getDate()).padStart(2, '0');
            const month = String(parsed.getMonth() + 1).padStart(2, '0');
            const year = String(parsed.getFullYear()).slice(-2);
            return `${day}-${month}-${year}`;
        }

        function buildPurchaserPrintLayout() {
            const tbody = document.getElementById('purchaser-print-items-body');
            const totalQtyEl = document.getElementById('purchaser-print-total-qty');
            const totalAmountEl = document.getElementById('purchaser-print-total-amount');
            const printDateEl = document.getElementById('purchaser-print-date');
            const printBillNoEl = document.getElementById('purchaser-print-bill-no');
            const printTraderEl = document.getElementById('purchaser-print-trader');

            if (!tbody || !totalQtyEl || !totalAmountEl || !printDateEl || !printBillNoEl || !printTraderEl) {
                return false;
            }

            tbody.innerHTML = '';

            const topDate = document.querySelector('input[name="record_date"]')?.value || '';
            const topBillNo = document.querySelector('input[name="goat_number"]')?.value || '';
            const topTrader = document.querySelector('input[name="trader"]')?.value || '';

            printDateEl.textContent = formatDateForPrint(topDate);
            printBillNoEl.textContent = topBillNo.trim() || '_____';
            printTraderEl.textContent = topTrader.trim() || '_____';

            let totalQty = 0;
            let totalAmount = 0;
            let rowCount = 0;

            document.querySelectorAll('.purchaser-row').forEach(row => {
                const code = row.querySelector('input[name*="[book_code]"]')?.value?.trim() || '';
                const name = row.querySelector('input[name*="[book]"]')?.value?.trim() || '';
                const purchaserRateRaw = row.querySelector('input[name*="[purchaser_rate]"]')?.value?.trim() || '';
                const purchaserAmountRaw = row.querySelector('input[name*="[purchaser_amount]"]')?.value?.trim() || '';
                const itemRateRaw = row.querySelector('input[name*="[item_rate]"]')?.value?.trim() || '';
                const lagaRateRaw = row.querySelector('input[name*="[laga_rate]"]')?.value?.trim() || '';
                const totalRaw = row.querySelector('input[name*="[row_total]"]')?.value?.trim() || '';

                const purchaserRate = parseFloat(purchaserRateRaw);
                const purchaserAmount = parseFloat(purchaserAmountRaw);
                const itemRate = parseFloat(itemRateRaw);
                const lagaRate = parseFloat(lagaRateRaw);
                let total = parseFloat(totalRaw);
                
                // Calculate total if not provided
                if (!Number.isFinite(total) && Number.isFinite(purchaserAmount) && Number.isFinite(lagaRate)) {
                    total = purchaserAmount + lagaRate;
                }

                const hasData = name || code || purchaserRateRaw || purchaserAmountRaw || itemRateRaw || lagaRateRaw || totalRaw;
                if (!hasData) {
                    return;
                }

                const description = [name, code ? `(${code})` : ''].filter(Boolean).join(' ');
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="p-col-qty">${purchaserAmountRaw || ''}</td>
                    <td class="p-col-desc">${description}</td>
                    <td class="p-col-rate">${itemRateRaw || ''}</td>
                    <td class="p-col-amount">${Number.isFinite(total) ? formatNumberForPrint(total) : ''}</td>
                `;
                tbody.appendChild(tr);

                rowCount += 1;
                if (Number.isFinite(purchaserAmount)) {
                    totalQty += purchaserAmount;
                }
                if (Number.isFinite(total)) {
                    totalAmount += total;
                }
            });

            if (rowCount === 0) {
                return false;
            }

            const minRows = 24;
            for (let i = rowCount; i < minRows; i += 1) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="p-col-qty">&nbsp;</td>
                    <td class="p-col-desc">&nbsp;</td>
                    <td class="p-col-rate">&nbsp;</td>
                    <td class="p-col-amount">&nbsp;</td>
                `;
                tbody.appendChild(tr);
            }

            totalQtyEl.textContent = formatNumberForPrint(totalQty);
            totalAmountEl.textContent = formatNumberForPrint(totalAmount);

            return true;
        }

        function printPurchaserDetailOnly() {
            if (!buildPurchaserPrintLayout()) {
                alert('پرنٹ کے لیے خریدار کی تفصیل موجود نہیں۔');
                return;
            }
            document.body.classList.add('print-mode-purchaser');
            window.print();
            setTimeout(() => {
                document.body.classList.remove('print-mode-purchaser');
            }, 500);
        }

        // Make printPurchaserDetailOnly globally accessible immediately
        window.printPurchaserDetailOnly = printPurchaserDetailOnly;

        let bakerySavePrintInProgress = false;

        // Function to refresh purchaser table data after save
        async function refreshPurchaserTableData(recordId) {
            try {
                const response = await fetch(`{{ url('/payment') }}/${recordId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data && data.transactions) {
                        // Update existing rows with saved data
                        const tbody = document.getElementById('purchaser-tbody');
                        if (tbody) {
                            const rows = tbody.querySelectorAll('.purchaser-row');
                            data.transactions.forEach((transaction, index) => {
                                if (rows[index]) {
                                    const row = rows[index];
                                    // Update each field with saved data
                                    const codeInput = row.querySelector(`input[name*="[book_code]"]`);
                                    const nameInput = row.querySelector(`input[name*="[book]"]`);
                                    const purchaserRateInput = row.querySelector(`input[name*="[purchaser_rate]"]`);
                                    const purchaserAmountInput = row.querySelector(`input[name*="[purchaser_amount]"]`);
                                    const itemRateInput = row.querySelector(`input[name*="[item_rate]"]`);
                                    const lagaRateInput = row.querySelector(`input[name*="[laga_rate]"]`);
                                    const totalInput = row.querySelector(`input[name*="[row_total]"]`);

                                    if (codeInput) codeInput.value = transaction.book_code || '';
                                    if (nameInput) nameInput.value = transaction.book || '';
                                    if (purchaserRateInput) purchaserRateInput.value = transaction.purchaser_rate || '';
                                    if (purchaserAmountInput) purchaserAmountInput.value = transaction.purchaser_amount || '';
                                    if (itemRateInput) itemRateInput.value = transaction.item_rate || '';
                                    if (lagaRateInput) lagaRateInput.value = transaction.laga_rate || '';
                                    if (totalInput) totalInput.value = transaction.row_total || '';
                                }
                            });
                        }
                    }
                }
            } catch (error) {
                console.error('Error refreshing table data:', error);
            }
        }

        // Function to clear items table after successful save
        function clearItemsTable() {
            try {
                const itemsTbody = document.getElementById('bakery-items-tbody');
                if (itemsTbody) {
                    // Remove legacy placeholder row if it exists
                    const emptyRow = document.getElementById('bakery-items-empty-row');
                    if (emptyRow) {
                        emptyRow.remove();
                    }

                    // Clear saved values so previously saved records are not visible
                    const rows = itemsTbody.querySelectorAll('tr[data-row-index]');
                    rows.forEach(row => {
                        row.style.display = '';
                        const rowInputs = row.querySelectorAll('input[name^="items["]');
                        rowInputs.forEach(input => {
                            input.value = '';
                        });
                    });

                    const totalQtyEl = document.getElementById('total-qty');
                    const totalAmountEl = document.getElementById('total-amount');
                    if (totalQtyEl) totalQtyEl.value = '';
                    if (totalAmountEl) totalAmountEl.value = '';
                }
            } catch (error) {
                console.error('Error clearing items table:', error);
            }
        }

        // Function to clear purchaser table after successful save
        function clearPurchaserTable() {
            try {
                const purchaserTbody = document.getElementById('purchaser-tbody');
                if (!purchaserTbody) {
                    return;
                }

                const rows = purchaserTbody.querySelectorAll('.purchaser-row');
                rows.forEach((row) => {
                    const rowInputs = row.querySelectorAll('input[name^="transactions["]');
                    rowInputs.forEach((input) => {
                        input.value = '';
                    });
                    row.style.display = '';
                });

                const totalLagaAmountEl = document.getElementById('total-laga-amount');
                const totalRowTotalEl = document.getElementById('total-row-total');
                if (totalLagaAmountEl) totalLagaAmountEl.value = '';
                if (totalRowTotalEl) totalRowTotalEl.value = '';

                const duesAlert = document.getElementById('purchaser-dues-alert');
                if (duesAlert) duesAlert.style.display = 'none';
            } catch (error) {
                console.error('Error clearing purchaser table:', error);
            }
        }

        async function saveAndPrintBakery() {
            if (bakerySavePrintInProgress) {
                return;
            }

            const form = document.getElementById('bakeryForm');
            const button = document.getElementById('bakerySavePrintBtn');
            const recordIdInput = document.getElementById('bakeryRecordId');

            if (!form || !button) {
                return;
            }

            // Collect all form data
            const formData = new FormData(form);
            
            // Show loading state
            const originalButtonHtml = button.innerHTML;
            bakerySavePrintInProgress = true;
            button.disabled = true;
            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('messages.saving') }}';

            try {
                const recordId = (recordIdInput?.value || '').trim();
                const requestUrl = recordId
                    ? `{{ url('/payment') }}/${encodeURIComponent(recordId)}`
                    : form.action;

                const response = await fetch(requestUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || data.success === false) {
                    let errorMessage = data.message || '{{ __('messages.save_failed_validation') }}';

                    if (data.errors && typeof data.errors === 'object') {
                        const firstError = Object.values(data.errors).flat()[0];
                        if (firstError) {
                            errorMessage = firstError;
                        }
                    }

                    alert(errorMessage);
                    return;
                }

                const savedRecordId = (data.id || '').toString().trim() || (recordIdInput?.value || '').trim();

                // Update record ID if new record created
                if (data.id) {
                    if (recordIdInput) {
                        recordIdInput.value = data.id;
                    }
                    form.action = `{{ url('/payment') }}/${data.id}`;
                }

                // Clear entry tables after successful save so saved data is hidden from input grid
                clearItemsTable();
                clearPurchaserTable();

                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'محفوظ ہو گیا!',
                        text: 'ریکارڈ کامیابی سے محفوظ ہو گیا ہے',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // After successful save, open print page
                        if (savedRecordId) {
                            window.open('{{ url("/payment/print") }}/${savedRecordId}', '_blank');
                        }
                    });
                } else {
                    alert('ریکارڈ کامیابی سے محفوظ ہو گیا ہے');
                    // Fallback: open print page directly
                    if (savedRecordId) {
                        window.open('{{ url("/payment/print") }}/${savedRecordId}', '_blank');
                    }
                }
            } catch (error) {
                console.error('Save/Print Error:', error);
                alert('{{ __('messages.unable_to_save_before_print') }}');
            } finally {
                bakerySavePrintInProgress = false;
                button.disabled = false;
                button.innerHTML = originalButtonHtml;
            }
        }

        // Make saveAndPrintBakery globally accessible immediately
        window.saveAndPrintBakery = saveAndPrintBakery;

        function printBillLayout() {
            try {
                const tbody = document.getElementById('bill-items-body');
                tbody.innerHTML = '';
                
                let hasItems = false;
                let itemsSubtotal = 0;
                const rows = document.querySelectorAll('tr[data-row-index]');
                
                rows.forEach(row => {
                    const qtyInput = row.querySelector('input[name*="[quantity]"]');
                    const nameInput = row.querySelector('input[name*="[item_type]"]');
                    const rateInput = row.querySelector('input[name*="[commission_rate]"]');
                    const totalInput = row.querySelector('input[name*="[total]"]');
                    
                    if (qtyInput && nameInput && totalInput) {
                        const qty = (qtyInput.value || '').trim();
                        const name = (nameInput.value || '').trim();
                        const rate = (rateInput?.value || '').trim();
                        const total = parseFloat(totalInput.value) || 0;
                        const qtyNumber = parseFloat(qty) || 0;
                        const rateNumber = parseFloat(rate) || 0;
                        const computedTotal = total > 0 ? total : (qtyNumber * rateNumber);
                        
                        if (name && (qty || rate || computedTotal > 0)) {
                            itemsSubtotal += computedTotal;
                            hasItems = true;
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td style="padding: 5px; border-left: 1px solid black; border-bottom: 1px solid #ddd; text-align: center;">${qty}</td>
                                <td style="padding: 5px; border-left: 1px solid black; border-bottom: 1px solid #ddd; text-align: center;">${name}</td>
                                <td style="padding: 5px; border-left: 1px solid black; border-bottom: solid #ddd; text-align: center;">${rate}</td>
                                <td class="bill-money-cell" style="padding: 5px; border-bottom: 1px solid #ddd; text-align: center;">${computedTotal ? computedTotal.toLocaleString() : ''}</td>
                            `;
                            tbody.appendChild(tr);
                        }
                    }
                });

                if (!hasItems) {
                    alert('{{ __('messages.no_items_found_to_print') }}');
                    return;
                }

                const MIN_ROWS = 20;
                const currentRows = tbody.children.length;
                if (currentRows < MIN_ROWS) {
                    const rowsToAdd = MIN_ROWS - currentRows;
                    for (let i = 0; i < rowsToAdd; i++) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td style="padding: 5px; border-left: 1px solid black; border-bottom: 1px solid #ddd; height: 28px;"></td>
                            <td style="padding: 5px; border-left: 1px solid black; border-bottom: 1px solid #ddd;"></td>
                            <td style="padding: 5px; border-left: 1px solid black; border-bottom: 1px solid #ddd;"></td>
                            <td class="bill-money-cell" style="padding: 5px; border-bottom: 1px solid #ddd;"></td>
                        `;
                        tbody.appendChild(tr);
                    }
                }

                const billGrossEl = document.getElementById('bill-gross-total');
                if (billGrossEl) {
                    billGrossEl.textContent = itemsSubtotal > 0 ? itemsSubtotal.toLocaleString() : '0';
                }

                document.body.classList.add('print-mode-bill');
                window.print();
                setTimeout(() => {
                    document.body.classList.remove('print-mode-bill');
                }, 500);

            } catch (e) {
                console.error('Print Logic Error:', e);
                alert('{{ __('messages.unexpected_print_error') }}');
            }
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main').classList.toggle('active');
        }

        // Aggressive purchaser table visibility enforcement function
        function forcePurchaserTableVisible() {
            // Force show the entire purchaser table section
            const purchaserSections = document.querySelectorAll('.legacy-table-section');
            purchaserSections.forEach(section => {
                if (section.innerHTML.includes('خریدار کی تفصیل')) {
                    section.style.display = '';
                    section.style.visibility = 'visible';
                    section.style.opacity = '1';
                    section.style.height = 'auto';
                    section.style.overflow = 'visible';
                }
            });
            
            // Force show all purchaser rows
            const purchaserRows = document.querySelectorAll('.purchaser-row');
            purchaserRows.forEach((row, index) => {
                row.style.display = '';
                row.style.visibility = 'visible';
                row.style.opacity = '1';
                row.style.height = 'auto';
                
                // Force show all inputs in the row
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    input.style.display = '';
                    input.style.visibility = 'visible';
                    input.style.opacity = '1';
                });
                
                // Force show buttons in the row
                const buttons = row.querySelectorAll('button');
                buttons.forEach(button => {
                    button.style.display = '';
                    button.style.visibility = 'visible';
                    button.style.opacity = '1';
                });
            });
            
            // Force show the table body
            const purchaserTbody = document.querySelector('#purchaser-tbody');
            if (purchaserTbody) {
                purchaserTbody.style.display = '';
                purchaserTbody.style.visibility = 'visible';
                purchaserTbody.style.opacity = '1';
                purchaserTbody.style.height = 'auto';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Data and Globals
            const peopleData = @json($people);
            const lagaData = @json($lagas);
            let activePurchaserRow = null;
            let currentDuesPurchaserCode = null;
            let currentPurchaserDues = null;
            window.currentPurchaserDues = null;

            // Immediate visibility enforcement
            forcePurchaserTableVisible();
            
            // Multiple delayed attempts to override any hiding
            setTimeout(() => {
                forcePurchaserTableVisible();
            }, 100);
            
            setTimeout(() => {
                forcePurchaserTableVisible();
            }, 500);
            
            setTimeout(() => {
                forcePurchaserTableVisible();
            }, 1000);

            function calculateItemRow(row) {
                const qtyInput = row.querySelector('input[name*="[quantity]"]');
                const rateInput = row.querySelector('input[name*="[commission_rate]"]');
                const totalInput = row.querySelector('input[name*="[total]"]');
                const qty = parseFloat(qtyInput?.value || 0);
                const rate = parseFloat(rateInput?.value || 0);
                const total = qty * rate;
                if (totalInput) totalInput.value = total.toFixed(2);
                calculateItemTotals();
            }

            function calculateItemTotals() {
                let totalQty = 0;
                let totalAmount = 0;
                let totalLabor = 0;
                document.querySelectorAll('.legacy-table-section:first-of-type tbody tr').forEach(row => {
                    if (row.querySelector('input')) {
                        const qtyInput = row.querySelector('input[name*="[quantity]"]');
                        const totalInput = row.querySelector('input[name*="[total]"]');
                        const laborInput = row.querySelector('input[name*="[labor]"]');
                        totalQty += parseFloat(qtyInput?.value || 0);
                        totalAmount += parseFloat(totalInput?.value || 0);
                        totalLabor += parseFloat(laborInput?.value || 0);
                    }
                });
                const qtyEl = document.getElementById('total-qty');
                const amtEl = document.getElementById('total-amount');
                const summaryLaborInput = document.querySelector('input[name="labor"]');
                const rawGoatInput = document.querySelector('input[name="raw_goat"]');
                if (qtyEl) qtyEl.value = totalQty;
                if (amtEl) amtEl.value = totalAmount.toFixed(2);
                if (summaryLaborInput) summaryLaborInput.value = totalLabor.toFixed(2);
                if (rawGoatInput) rawGoatInput.value = totalAmount.toFixed(2);
                calculateExpenses();
            }

            function calculatePurchaserRow(row) {
                const qtyInput = row.querySelector('input[name*="[daily_quantity]"]');
                const rateInput = row.querySelector('input[name*="[daily_rate]"]');
                const dailyAmountInput = row.querySelector('input[name*="[daily_amount]"]');
                const lagaRateInput = row.querySelector('input[name*="[laga_rate]"]');
                const lagaAmountInput = row.querySelector('input[name*="[laga_amount]"]');
                const rowTotalInput = row.querySelector('input[name*="[row_total]"]');

                const dailyQty = parseFloat(qtyInput?.value || 0);
                const dailyRate = parseFloat(rateInput?.value || 0);
                const dailyAmount = dailyQty * dailyRate;
                if (dailyAmountInput) dailyAmountInput.value = dailyAmount.toFixed(2);

                const lagaRate = parseFloat(lagaRateInput?.value || 0);
                const lagaAmount = (dailyAmount * lagaRate) / 100;
                if (lagaAmountInput) lagaAmountInput.value = lagaAmount.toFixed(2);

                const rowTotal = dailyAmount + lagaAmount;
                if (rowTotalInput) rowTotalInput.value = rowTotal.toFixed(2);
                window.calculateItemsTotals();
            }

            function calculateItemsTotals() {
                let totalDailyAmount = 0;
                let totalRowTotal = 0;
                document.querySelectorAll('.legacy-table-section:last-of-type tbody tr').forEach(row => {
                    if (row.querySelector('input')) {
                        const dailyAmtInput = row.querySelector('input[name*="[daily_amount]"]');
                        const rowTotalInput = row.querySelector('input[name*="[row_total]"]');
                        totalDailyAmount += parseFloat(dailyAmtInput?.value || 0);
                        totalRowTotal += parseFloat(rowTotalInput?.value || 0);
                    }
                });
                const totalLagaAmount = totalRowTotal - totalDailyAmount;
                const dailyAmtEl = document.getElementById('total-daily-amount');
                const lagaAmtEl = document.getElementById('total-laga-amount');
                const rowTotalEl = document.getElementById('total-row-total');
                if (dailyAmtEl) dailyAmtEl.value = totalDailyAmount.toFixed(2);
                if (lagaAmtEl) lagaAmtEl.value = totalLagaAmount.toFixed(2);
                if (rowTotalEl) rowTotalEl.value = totalRowTotal.toFixed(2);
                calculateNetBakery();
            }

            function calculateExpenses() {
                const namesToInclude = ['fare', 'commission', 'labor', 'mashiana', 'other_expenses'];
                let totalExpenses = 0;

                namesToInclude.forEach(name => {
                    const input = document.querySelector(`input[name="${name}"]`);
                    if (!input) return;
                    const value = parseFloat(input.value);
                    if (!Number.isNaN(value) && Number.isFinite(value)) {
                        totalExpenses += value;
                    }
                });

                const expEl = document.getElementById('total-expenses');
                if (expEl) expEl.value = totalExpenses.toFixed(2);
                calculateNetBakery();
            }

            function calculateNetBakery() {
                const totalAmount = parseFloat(document.getElementById('total-amount')?.value) || 0;
                const totalRowTotal = parseFloat(document.getElementById('total-row-total')?.value) || 0;
                const totalExpenses = parseFloat(document.getElementById('total-expenses')?.value) || 0;
                const netBakery = totalAmount - totalRowTotal - totalExpenses;
                const displayValue = Math.abs(netBakery);
                const netEl = document.getElementById('net-bakery');
                if (netEl) netEl.value = displayValue.toFixed(2);
            }

            // --- Modal Functions ---
            function openPurchaserModal(row) {
                activePurchaserRow = row;
                const modal = document.getElementById('purchaser-modal');
                if (modal) {
                    modal.classList.add('active');
                    populatePurchaserModal();
                    document.getElementById('purchaser-search').focus();
                }
            }

            function closePurchaserModal() {
                const modal = document.getElementById('purchaser-modal');
                if (modal) modal.classList.remove('active');
            }

            function handlePurchaserOverlayClick(event) {
                const modal = document.getElementById('purchaser-modal');
                if (event.target === modal) {
                    closePurchaserModal();
                }
            }

            function populatePurchaserModal(filter = '') {
                const tbody = document.getElementById('purchaser-search-table')?.querySelector('tbody');
                if (!tbody) return;
                tbody.innerHTML = '';
                const q = (filter || '').toLowerCase();
                const tf = document.querySelector('input[name="trader"]')?.value.toLowerCase() || '';
                const applied = q || tf;
                const filteredData = peopleData.filter(p => p.name.toLowerCase().includes(applied) || p.id.toString().includes(applied));

                if (filteredData.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">کوئی خریدار نہیں ملا۔</td></tr>';
                    return;
                }

                filteredData.forEach(person => {
                    const tr = document.createElement('tr');
                    tr.dataset.personId = String(person.id);
                    tr.innerHTML = `<td>${person.id}</td><td>${person.name}</td><td>${person.phone}</td>`;
                    tr.addEventListener('click', () => {
                        document.querySelectorAll('#purchaser-search-table tr.selected').forEach(r => r.classList.remove('selected'));
                        tr.classList.add('selected');
                    });
                    tr.addEventListener('dblclick', () => selectPurchaser(tr));
                    tbody.appendChild(tr);
                });
            }

            function selectPurchaser(selectedRow) {
                if (selectedRow && activePurchaserRow) {
                    const codeCell = selectedRow.cells[0].textContent.trim();
                    const name = selectedRow.cells[1].textContent.trim();
                    const phone = selectedRow.cells[2].textContent.trim();

                    let matchedLagaCode = null;
                    if (Array.isArray(lagaData)) {
                        const personId = selectedRow.dataset.personId;
                        const personObj = peopleData.find(p => String(p.id) === String(personId));
                        const personName = (personObj?.name || name || '').toLowerCase();
                        const personPhone = personObj?.phone || phone || '';

                        let lagaMatch = lagaData.find(l => (l.name || '').toLowerCase() === personName);
                        if (!lagaMatch && personPhone) {
                            lagaMatch = lagaData.find(l => (l.mobile || '') === personPhone);
                        }
                        if (lagaMatch && lagaMatch.code) {
                            matchedLagaCode = String(lagaMatch.code);
                        }
                    }

                    const finalCode = matchedLagaCode || codeCell;
                    const codeInput = activePurchaserRow.querySelector('input[name*="[book_code]"]');
                    const nameInput = activePurchaserRow.querySelector('input[name*="[book]"]');
                    if (codeInput) codeInput.value = finalCode;
                    if (nameInput) nameInput.value = name;
                    closePurchaserModal();
                    const amountInput = activePurchaserRow.querySelector('input[name*="[daily_amount]"]');
                    if (amountInput) { amountInput.focus(); amountInput.select(); }
                    if (matchedLagaCode) {
                        loadPurchaserDues(matchedLagaCode, name);
                    } else {
                        currentPurchaserDues = null;
                        if (duesAlertEl) duesAlertEl.style.display = 'none';
                    }
                }
            }

            window.openPurchaserModal = openPurchaserModal;
            window.closePurchaserModal = closePurchaserModal;
            window.handlePurchaserOverlayClick = handlePurchaserOverlayClick;

            // --- Event Listeners ---
            document.getElementById('close-modal-btn')?.addEventListener('click', closePurchaserModal);
            document.getElementById('select-purchaser-btn')?.addEventListener('click', () => {
                selectPurchaser(document.querySelector('#purchaser-search-table tr.selected'));
            });
            document.getElementById('purchaser-search')?.addEventListener('input', (e) => populatePurchaserModal(e.target.value));

            document.getElementById('purchaser-modal')?.addEventListener('keydown', function(e) {
                const table = document.getElementById('purchaser-search-table');
                const selected = table.querySelector('tr.selected');
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    let next = selected ? selected.nextElementSibling : table.querySelector('tbody tr:first-child');
                    if (next) { if(selected) selected.classList.remove('selected'); next.classList.add('selected'); next.scrollIntoView({ block: 'nearest' }); }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    let prev = selected ? selected.previousElementSibling : table.querySelector('tbody tr:last-child');
                    if (prev) { if(selected) selected.classList.remove('selected'); prev.classList.add('selected'); prev.scrollIntoView({ block: 'nearest' }); }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selected) selectPurchaser(selected);
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closePurchaserModal();
                }
            });

            function filterPurchaserTable() {
                const traderFilter = document.querySelector('input[name="trader"]')?.value.toLowerCase() || '';
                document.querySelectorAll('.purchaser-row').forEach(row => {
                    const bookVal = row.querySelector('input[name*="[book]"]')?.value.toLowerCase() || '';
                    if (traderFilter && !bookVal.includes(traderFilter)) {
                        row.style.display = 'none';
                    } else {
                        row.style.display = '';
                    }
                });
            }

            const topTrader = document.querySelector('input[name="trader"]');
            const topGoat = document.querySelector('input[name="goat_number"]');
            const topTruck = document.querySelector('input[name="truck_number"]');
            topTrader?.addEventListener('input', () => {
                filterPurchaserTable();
                if (document.getElementById('purchaser-modal')?.classList.contains('active')) {
                    const s = document.getElementById('purchaser-search')?.value || '';
                    populatePurchaserModal(s);
                }
            });
            topGoat?.addEventListener('input', () => { filterPurchaserTable(); });
            topTruck?.addEventListener('input', () => { filterPurchaserTable(); });

            document.querySelectorAll('.legacy-table-section:first-of-type tbody input').forEach(input => {
                input.addEventListener('input', function() { calculateItemRow(this.closest('tr')); });
            });

            document.querySelectorAll('.legacy-table-section:last-of-type tbody input').forEach(input => {
                input.addEventListener('input', function() {
                    const row = this.closest('tr');
                    const dateInput = row.querySelector('input[name*="[transaction_date]"]');
                    if (dateInput && !dateInput.value) dateInput.value = new Date().toISOString().split('T')[0];
                    const lagaRateInput = row.querySelector('input[name*="[laga_rate]"]');
                    if (lagaRateInput && !lagaRateInput.value) { lagaRateInput.value = '15'; lagaRateInput.dispatchEvent(new Event('input', { bubbles: true })); }
                    calculatePurchaserRow(row);
                });
            });

            document.querySelectorAll('.summary-input').forEach(input => {
                input.addEventListener('input', calculateExpenses);
            });

            const duesAlertEl = document.getElementById('purchaser-dues-alert');
            const duesTextEl = document.getElementById('purchaser-dues-text');
            const duesBreakdownEl = document.getElementById('purchaser-dues-breakdown');

            async function loadPurchaserDues(code, name) {
                if (!duesAlertEl || !duesTextEl || !duesBreakdownEl) return;
                currentDuesPurchaserCode = code;
                try {
                    const url = new URL('{{ route('rokad.balance') }}', window.location.origin);
                    url.searchParams.set('code', code);
                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!response.ok) {
                        duesAlertEl.style.display = 'none';
                        return;
                    }
                    const data = await response.json();
                    if (!data || !data.success || !data.dues) {
                        duesAlertEl.style.display = 'none';
                        return;
                    }
                    if (currentDuesPurchaserCode !== code) {
                        return;
                    }
                    renderPurchaserDues(name, data);
                } catch (e) {
                    duesAlertEl.style.display = 'none';
                }
            }

            function renderPurchaserDues(name, payload) {
                if (!duesAlertEl || !duesTextEl || !duesBreakdownEl) return;
                const dues = payload.dues || {};
                const currency = payload.currency || 'Rs';
                const original = parseFloat(dues.original_madi ?? 0);
                const paid = parseFloat(dues.total_paid ?? 0);
                const interest = parseFloat(dues.interest ?? 0);
                const penalties = parseFloat(dues.penalties ?? 0);
                const balance = parseFloat(dues.remaining_balance ?? 0);

                if (!original || balance <= 0) {
                    duesAlertEl.style.display = 'none';
                    currentPurchaserDues = null;
                    window.currentPurchaserDues = null;
                    return;
                }

                duesAlertEl.style.display = 'block';
                duesTextEl.textContent = name + ' پر بقایا رقم موجود ہے';

                let parts = [];
                parts.push(currency + ' ' + original.toFixed(2) + ' اصل مدی');
                parts.push(currency + ' ' + paid.toFixed(2) + ' ادا شدہ');
                if (interest || penalties) {
                    const extra = interest + penalties;
                    parts.push(currency + ' ' + extra.toFixed(2) + ' منافع/جرمانہ');
                }
                parts.push(currency + ' ' + balance.toFixed(2) + ' بقایا رقم');

                duesBreakdownEl.textContent = parts.join(' | ');

                currentPurchaserDues = {
                    name,
                    currency,
                    original,
                    paid,
                    interest,
                    penalties,
                    balance
                };
                window.currentPurchaserDues = currentPurchaserDues;
            }

            // Initial Calculations
            document.querySelectorAll('.legacy-table-section:first-of-type tbody tr').forEach(row => { if(row.querySelector('input')) calculateItemRow(row); });
            document.querySelectorAll('.legacy-table-section:last-of-type tbody tr').forEach(row => { if(row.querySelector('input')) calculatePurchaserRow(row); });
            calculateExpenses();
            filterPurchaserTable();
        });

        // Dynamic Purchaser Table Row Management
        let purchaserRowCount = 6;

        function addNewPurchaserRow() {
            const tbody = document.getElementById('purchaser-tbody');
            if (!tbody) {
                return;
            }

            // Check if all existing rows have data
            const existingRows = tbody.querySelectorAll('.purchaser-row');
            let allRowsHaveData = true;
            
            existingRows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                const hasData = Array.from(inputs).some(input => input.value.trim() !== '');
                if (!hasData) {
                    allRowsHaveData = false;
                }
            });

            if (!allRowsHaveData && purchaserRowCount < 10) {
                const newRow = document.createElement('tr');
                newRow.className = 'purchaser-row';
                newRow.setAttribute('data-row-index', purchaserRowCount);
                
                const currentDate = new Date().toISOString().split('T')[0];
                
                newRow.innerHTML = `
                    <td><input type="text" name="transactions[${purchaserRowCount}][book_code]" value="" style="width: 84%; font-size: 0.85rem; cursor: pointer;" class="text-center purchaser-input required-field" list="purchaserCodesList" autocomplete="off" readonly onclick="openPurchaserModal(this.closest('tr'))"></td>
                    <td><input type="text" name="transactions[${purchaserRowCount}][book]" class="urdu-text purchaser-input required-field" value="" data-urdu="true" style="width: 84%; font-size: 0.85rem; cursor: pointer;" onclick="openPurchaserModal(this.closest('tr'))"></td>
                    <td><input type="number" name="transactions[${purchaserRowCount}][purchaser_rate]" value="" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                    <td><input type="number" name="transactions[${purchaserRowCount}][purchaser_amount]" value="" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                    <td><input type="number" name="transactions[${purchaserRowCount}][item_rate]" value="" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                    <td><input type="number" name="transactions[${purchaserRowCount}][laga_rate]" value="" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input required-field"></td>
                    <td><input type="number" name="transactions[${purchaserRowCount}][row_total]" value="" style="width: 84%; font-size: 0.85rem;" class="text-center purchaser-input"></td>
                    <td style="text-align: center;">
                        <button type="button" onclick="addNewPurchaserRow()" style="padding: 4px 8px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button type="button" onclick="removePurchaserRow(this)" style="padding: 4px 8px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.8rem; margin-right: 5px;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(newRow);
                purchaserRowCount++;
                
                // Update row indices
                updatePurchaserRowIndices();
            }
        }

        function removePurchaserRow(button) {
            const row = button.closest('tr');
            if (row) {
                row.remove();
                updatePurchaserRowIndices();
            }
        }

        function updatePurchaserRowIndices() {
            const tbody = document.getElementById('purchaser-tbody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('.purchaser-row');
            rows.forEach((row, index) => {
                row.setAttribute('data-row-index', index);
            });
        }

        // Make row management functions globally accessible immediately
        window.addNewPurchaserRow = addNewPurchaserRow;
        window.removePurchaserRow = removePurchaserRow;
        window.updatePurchaserRowIndices = updatePurchaserRowIndices;

        // Verify all functions are properly assigned
        const functionTypes = {
            saveAndPrintBakery: typeof window.saveAndPrintBakery,
            printPurchaserDetailOnly: typeof window.printPurchaserDetailOnly,
            calculatePurchaserTotals: typeof window.calculatePurchaserTotals,
            addNewPurchaserRow: typeof window.addNewPurchaserRow,
            removePurchaserRow: typeof window.removePurchaserRow,
            updatePurchaserRowIndices: typeof window.updatePurchaserRowIndices
        };

        // Auto-add new row when last row gets data
        document.addEventListener('input', function(event) {
            if (event.target.matches('.purchaser-input')) {
                setTimeout(() => {
                    const tbody = document.getElementById('purchaser-tbody');
                    const rows = tbody.querySelectorAll('.purchaser-row');
                    const lastRow = rows[rows.length - 1];
                    
                    if (lastRow) {
                        const inputs = lastRow.querySelectorAll('input');
                        const allFilled = Array.from(inputs).every(input => input.value.trim() !== '');
                        
                        if (allFilled && purchaserRowCount < 10) {
                            addNewPurchaserRow();
                        }
                    }
                }, 100);
            }
        });

        // Function to calculate and show totals
        function calculatePurchaserTotals() {
            const purchaserTable = document.getElementById('purchaser-tbody');
            if (!purchaserTable) {
                return;
            }
            
            let totalLagaAmount = 0;
            let totalRowTotal = 0;
            
            const rows = purchaserTable.querySelectorAll('.purchaser-row');
            
            rows.forEach((row, index) => {
                const lagaRateInput = row.querySelector('input[name*="[laga_rate]"]');
                const totalInput = row.querySelector('input[name*="[row_total]"]');
                
                const lagaRate = parseFloat(lagaRateInput?.value) || 0;
                const rowTotal = parseFloat(totalInput?.value) || 0;
                
                totalLagaAmount += lagaRate;
                totalRowTotal += rowTotal;
            });
            
            // Update total fields
            const totalLagaAmountEl = document.getElementById('total-laga-amount');
            const totalRowTotalEl = document.getElementById('total-row-total');
            
            if (totalLagaAmountEl) {
                totalLagaAmountEl.value = totalLagaAmount.toFixed(2);
            }
            
            if (totalRowTotalEl) {
                totalRowTotalEl.value = totalRowTotal.toFixed(2);
            }
        }

        // Make calculatePurchaserTotals globally accessible immediately
        window.calculatePurchaserTotals = calculatePurchaserTotals;

        // Calculate totals when input changes
        document.addEventListener('input', function(event) {
            if (event.target.matches('.purchaser-row input')) {
                setTimeout(() => window.calculatePurchaserTotals(), 100);
            }
        });

        // Initial calculation on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                window.calculatePurchaserTotals();
            }, 500);
        });

        // Make all functions globally accessible
        window.saveAndPrintBakery = saveAndPrintBakery;
        window.printPurchaserDetailOnly = printPurchaserDetailOnly;
        window.calculatePurchaserTotals = calculatePurchaserTotals;
        window.addNewPurchaserRow = addNewPurchaserRow;
        window.removePurchaserRow = removePurchaserRow;
        window.updatePurchaserRowIndices = updatePurchaserRowIndices;

        // Alternative button event listeners to ensure buttons work
        document.addEventListener('DOMContentLoaded', function() {
            const mainPrintBtn = document.getElementById('bakerySavePrintBtn');
            const purchaserPrintBtn = document.querySelector('button[onclick*="printPurchaserDetailOnly"]');
            
            if (mainPrintBtn) {
                mainPrintBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    saveAndPrintBakery();
                });
            }
            
            if (purchaserPrintBtn) {
                purchaserPrintBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    printPurchaserDetailOnly();
                });
            }
        });
        
        // Global safety wrapper for openPurchaserModal to prevent undefined errors
        window.openPurchaserModal = function(row) {
            if (typeof openPurchaserModal === 'function') {
                openPurchaserModal(row);
            } else {
                console.error('openPurchaserModal function not available');
            }
        };
        
        // Global safety wrapper for closePurchaserModal to prevent undefined errors
        window.closePurchaserModal = function() {
            if (typeof closePurchaserModal === 'function') {
                closePurchaserModal();
            } else {
                console.error('closePurchaserModal function not available');
            }
        };
        
        // Final global function assignments - ensure all functions are accessible
        window.saveAndPrintBakery = saveAndPrintBakery;
        window.printPurchaserDetailOnly = printPurchaserDetailOnly;
        window.calculatePurchaserTotals = calculatePurchaserTotals;
        window.addNewPurchaserRow = addNewPurchaserRow;
        window.removePurchaserRow = removePurchaserRow;
        window.updatePurchaserRowIndices = updatePurchaserRowIndices;
        window.closePurchaserModal = closePurchaserModal;
        window.clearItemsTable = clearItemsTable;
        window.forcePurchaserTableVisible = forcePurchaserTableVisible;
        
        // Final verification
        const finalFunctionTypes = {
            saveAndPrintBakery: typeof window.saveAndPrintBakery,
            printPurchaserDetailOnly: typeof window.printPurchaserDetailOnly,
            calculatePurchaserTotals: typeof window.calculatePurchaserTotals,
            addNewPurchaserRow: typeof window.addNewPurchaserRow,
            removePurchaserRow: typeof window.removePurchaserRow,
            updatePurchaserRowIndices: typeof window.updatePurchaserRowIndices,
            openPurchaserModal: typeof window.openPurchaserModal,
            closePurchaserModal: typeof window.closePurchaserModal,
            clearItemsTable: typeof window.clearItemsTable,
            forcePurchaserTableVisible: typeof window.forcePurchaserTableVisible
        };

    </script>
    <!-- Packaging Modal -->
    <div id="packagingModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle" onclick="handleOverlayClick(event)">
        <div class="modal-window" style="width: 650px; height: 500px;" onclick="event.stopPropagation()">
            <div class="modal-title-bar">
                <span id="modalTitle">Packing</span>
                <button type="button" class="packaging-close-btn" onclick="closePackagingModal()" aria-label="Close">&times;</button>
            </div>
            <div class="modal-content">
                <div class="modal-search-bar">
                    <label for="packagingSearch">Find</label>
                    <input type="text" id="packagingSearch" onkeyup="filterPackaging()" value="" autocomplete="off">
                </div>
                
                <div class="modal-table-container">
                    <table class="modal-table" id="packagingTable">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Packing</th>
                                <th style="width: 34%;">Urdu Name</th>
                                <th style="width: 18%;">Labour</th>
                                <th style="width: 18%; text-align: center;">Main</th>
                            </tr>
                        </thead>
                        <tbody id="packagingTableBody">
                            <!-- Rows populated by JS -->
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="modal-btn" onclick="filterPackaging()">Find</button>
                    <button type="button" class="modal-btn" onclick="confirmSelection()">OK</button>
                    <button type="button" class="modal-btn" onclick="closePackagingModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Add Items page packing popup: fixed dataset as shown in reference image.
        const FIXED_PACKAGING_OPTIONS = [
            { code: 'BORI', name: '\u0628\u0648\u0631\u06cc', labor: '2.0' },
            { code: 'CHALY', name: '\u0686\u06be\u0644\u06cc', labor: '5' },
            { code: 'CHEELI', name: '\u0686\u06be\u06cc\u0644\u06cc', labor: '4' },
            { code: 'DANA', name: '\u062f\u0627\u0646\u06c1', labor: '' },
            { code: 'GIFT', name: '\u06af\u0641\u0679', labor: '' },
            { code: 'JAAL', name: '\u062c\u0627\u0644', labor: '1' },
            { code: 'KARET', name: '\u06a9\u0631\u06cc\u0679', labor: '10' },
            { code: 'KATON', name: '\u06a9\u0627\u0631\u0679\u0646', labor: '5' },
            { code: 'KATON', name: '\u06a9\u0627\u0631\u0679\u0646', labor: '5' },
            { code: 'KIWI', name: '\u06a9\u06cc\u0648\u06cc', labor: '5' },
            { code: 'KOJA KPNO', name: '\u06a9\u0648\u062c\u0627 \u06a9\u067e\u0646\u0648', labor: '' }
        ];

        let packagingOptions = [];
        let activeRowIndex = null;
        let selectedOption = null;
        let selectedOptionKey = null;

        const modal = document.getElementById('packagingModal');
        const tableBody = document.getElementById('packagingTableBody');
        const searchInput = document.getElementById('packagingSearch');

        function normalizePackingOption(pkg, index) {
            const source = pkg || {};
            const laborValue = source.labor ?? source.labour ?? source.labor_rate ?? '';
            return {
                id: source.id ?? null,
                key: String(source.key ?? `packing-${index}`),
                code: String(source.code ?? source.packing_code ?? '').trim(),
                name: String(source.name ?? source.urdu_name ?? source.packing ?? '').trim(),
                labor: laborValue === null || laborValue === undefined ? '' : String(laborValue).trim()
            };
        }

        function loadFixedPackings() {
            packagingOptions = FIXED_PACKAGING_OPTIONS.map((pkg, index) => normalizePackingOption({
                ...pkg,
                key: `fixed-${index}`
            }, index));
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderEmptyPackagingState(message) {
            if (!tableBody) return;
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align:center; padding: 12px 6px; color: #666;">${escapeHtml(message)}</td>
                </tr>
            `;
        }

        function renderPackagingTable(data = packagingOptions) {
            if (!tableBody) return;

            const rows = Array.isArray(data) ? data : [];
            tableBody.innerHTML = '';

            if (!rows.length) {
                renderEmptyPackagingState('No packing data found');
                return;
            }

            rows.forEach((pkg) => {
                const tr = document.createElement('tr');
                tr.onclick = () => selectModalRow(tr, pkg);
                tr.ondblclick = () => {
                    selectModalRow(tr, pkg);
                    confirmSelection();
                };

                if (selectedOptionKey && selectedOptionKey === pkg.key) {
                    tr.classList.add('selected');
                }

                tr.innerHTML = `
                    <td>${escapeHtml(pkg.code)}</td>
                    <td class="urdu-text">${escapeHtml(pkg.name)}</td>
                    <td>${escapeHtml(pkg.labor)}</td>
                    <td style="text-align: center;"></td>
                `;
                tableBody.appendChild(tr);
            });

            if (!selectedOption && rows.length) {
                selectedOption = rows[0];
                selectedOptionKey = rows[0].key;
                const firstRow = tableBody.querySelector('tr');
                if (firstRow) {
                    firstRow.classList.add('selected');
                }
            }
        }

        function selectModalRow(tr, pkg) {
            if (!tableBody) return;
            tableBody.querySelectorAll('tr').forEach((row) => row.classList.remove('selected'));
            tr.classList.add('selected');
            selectedOption = pkg;
            selectedOptionKey = pkg.key;
        }

        function openPackagingModal(element, index) {
            if (!modal || !searchInput) return;

            activeRowIndex = index;
            selectedOption = null;
            selectedOptionKey = null;

            const activeRow = document.querySelector(`tr[data-row-index="${activeRowIndex}"]`);
            const currentCode = activeRow?.querySelector('.packing-code-input')?.value?.trim()
                || activeRow?.querySelector('.packing-input')?.value?.trim()
                || '';
            if (currentCode) {
                const matched = packagingOptions.find((pkg) => pkg.code === currentCode);
                if (matched) {
                    selectedOption = matched;
                    selectedOptionKey = matched.key;
                }
            }

            searchInput.value = '';
            renderPackagingTable(packagingOptions);
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                searchInput.focus();
                searchInput.select();
            }, 100);
        }

        function confirmSelection() {
            if (!selectedOption && packagingOptions.length) {
                selectedOption = packagingOptions[0];
                selectedOptionKey = packagingOptions[0].key;
            }

            if (activeRowIndex === null || !selectedOption) {
                closePackagingModal();
                return;
            }

            const row = document.querySelector(`tr[data-row-index="${activeRowIndex}"]`);
            if (row) {
                const packingInput = row.querySelector('.packing-input');
                const packingCodeInput = row.querySelector('.packing-code-input');
                const laborInput = row.querySelector(`input[name="items[${activeRowIndex}][labor]"]`);

                if (packingInput) {
                    packingInput.value = selectedOption.code;
                    packingInput.dispatchEvent(new Event('input', { bubbles: true }));
                }

                if (packingCodeInput) {
                    packingCodeInput.value = selectedOption.code;
                    packingCodeInput.dispatchEvent(new Event('change', { bubbles: true }));
                }

                if (laborInput && selectedOption.labor !== '') {
                    laborInput.value = selectedOption.labor;
                    laborInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }

            closePackagingModal();
        }

        function closePackagingModal() {
            if (!modal) return;
            modal.classList.remove('active');
            activeRowIndex = null;
            document.body.style.overflow = '';
        }

        function handleOverlayClick(event) {
            if (event.target === modal) {
                closePackagingModal();
            }
        }

        function filterPackaging() {
            if (!searchInput) return;
            const query = searchInput.value.toLowerCase().replace(/%/g, '');
            const filtered = packagingOptions.filter((pkg) => {
                const name = String(pkg.name ?? '').toLowerCase();
                const code = String(pkg.code ?? '').toLowerCase();
                return name.includes(query) || code.includes(query);
            });
            selectedOption = null;
            selectedOptionKey = null;
            renderPackagingTable(filtered);
        }

        document.addEventListener('keydown', function(event) {
            if (!modal || !modal.classList.contains('active')) return;

            if (event.key === 'Escape') {
                closePackagingModal();
            } else if (event.key === 'Enter') {
                event.preventDefault();
                confirmSelection();
            }
        });

        window.openPackagingModal = openPackagingModal;
        window.closePackagingModal = closePackagingModal;
        window.confirmSelection = confirmSelection;
        window.filterPackaging = filterPackaging;
        window.handleOverlayClick = handleOverlayClick;

        loadFixedPackings();
        renderPackagingTable(packagingOptions);
    </script>
    <!-- 
        Component: Item Type Selection Modal
        Description: A responsive popup modal for selecting item types (Finance Department).
        Features:
        - View Mode: Searchable table of items with code, English name, and Urdu name.
        - Add Mode: Form to add new items temporarily (English & Urdu names).
        - Interactions: 
          - Click to select row.
          - Double-click or 'Select' button to confirm.
          - 'Esc' key to close.
          - Backdrop click to close.
        - Accessibility: ARIA roles, focus management.
        - Validation: Prevents adding empty items.
    -->
    <!-- Hidden Purchaser Detail Print Layout -->
    <div id="purchaser-print-container" class="purchaser-print-container" style="display: none;">
        <div class="purchaser-receipt-sheet">
            <div class="purchaser-receipt-header">
                <div class="purchaser-logo-box">
                    <span class="logo-main">FS</span>
                    <span class="logo-sub">SHOP 1</span>
                </div>
                <div class="purchaser-shop-info">
                    <h2>فہیم خان اینڈ کمپنی</h2>
                    <p>سبزی فروٹ کمیشن ایجنٹس شاہ پور منڈی لاہور</p>
                    <div class="purchaser-shop-phones">
                        <span>0300-4847252</span>
                        <span>0321-4847252</span>
                        <span>0300-4008548</span>
                    </div>
                </div>
            </div>

            <div class="purchaser-meta-strip">
                <div><strong>تاریخ:</strong> <span id="purchaser-print-date" class="purchaser-meta-value">-</span></div>
                <div><strong>بل نمبر:</strong> <span id="purchaser-print-bill-no" class="purchaser-meta-value">-</span></div>
            </div>
            <div class="purchaser-name-strip">
                <strong>نام بیوپاری:</strong>
                <span id="purchaser-print-trader" class="purchaser-name-value">-</span>
            </div>

            <table class="purchaser-print-table">
                <thead>
                    <tr>
                        <th style="width: 18%;">تعداد</th>
                        <th style="width: 40%;">تفصیل</th>
                        <th style="width: 18%;">نرخ</th>
                        <th style="width: 24%;">روپے</th>
                    </tr>
                </thead>
                <tbody id="purchaser-print-items-body"></tbody>
                <tfoot>
                    <tr>
                        <td id="purchaser-print-total-qty">0</td>
                        <td style="text-align: right; padding-right: 8px;">ٹوٹل</td>
                        <td></td>
                        <td id="purchaser-print-total-amount">0</td>
                    </tr>
                </tfoot>
            </table>

            <div class="purchaser-print-footer">
                دکان نمبر 1 ۔ نیو اسماعیل خان فروٹ مارکیٹ شاہ پور منڈی لاہور
            </div>
        </div>
    </div>

    <!-- Hidden Bill Print Layout (Faheem Khan & Co) -->
    <div id="bill-print-container" class="bill-print-container" style="display: none;">
        <!-- Header -->
        <div class="bill-header">
            <div class="company-title">
                <h1>فہیم خان اینڈ کمپنی</h1>
                <p>سبزی فروٹ کمیشن ایجنٹس شاہ پور منڈی لاہور</p>
            </div>
            <div class="contact-info">
                <span>0300-4847252</span> | <span>0321-4847252</span> | <span>0300-4008548</span>
            </div>
        </div>
        
        <!-- Info Row -->
        <div class="bill-info-row">
            <div class="info-item" style="flex: 1;">
                <strong>نام بیوپاری:</strong> <span id="bill-trader-name">________________</span>
            </div>
            <div class="info-item" style="flex: 1; text-align: center;">
                <strong>تاریخ:</strong> <span id="bill-date">________________</span>
            </div>
            <div class="info-item" style="flex: 1; text-align: left;">
                <strong>بکری نمبر:</strong> <span id="bill-goat-no">____</span>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="bill-grid" style="display: flex; direction: rtl; border: 2px solid black;">
            <!-- Right Column: Expenses (Tafseel Ikhrajat) -->
            <div class="expenses-column" style="width: 25%; border-left: 2px solid black; padding: 0;">
                <div class="column-header" style="border-bottom: 2px solid black; text-align: center; font-weight: bold; padding: 5px; background: #ddd;">تفصیل اخراجات</div>
                    <div class="expenses-list" style="padding: 10px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>کمیشن:</span><span id="bill-comm" class="bill-money">0</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>مزدوری:</span><span id="bill-labor" class="bill-money">0</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>کرایہ:</span><span id="bill-rent" class="bill-money">0</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>منشیانہ:</span><span id="bill-munshiana" class="bill-money">0</span></div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;"><span>نقد:</span><span id="bill-other" class="bill-money">0</span></div>
                    <div style="border-top: 2px solid black; margin: 5px 0;"></div>
                    <div style="display: flex; justify-content: space-between; font-weight: bold;"><span>اخراجات</span><span id="bill-total-exp" class="bill-money">0</span></div>
                    <div style="margin-top: 20px; border: 2px solid black; padding: 5px; text-align: center;">
                        <div style="font-weight: bold;">صافی بیکری</div>
                        <div id="bill-net-bakery" class="bill-money" style="font-size: 1.2rem; font-weight: bold;">0</div>
                    </div>
                    <div style="margin-top: 10px; border: 2px dashed black; padding: 5px; text-align: center;">
                        <div style="font-weight: bold;">پرانا بقایا (روکڑ)</div>
                        <div id="bill-prev-balance" class="bill-money" style="font-size: 1.1rem; font-weight: bold;">0</div>
                    </div>
                    <div style="margin-top: 10px; border: 2px solid black; padding: 5px; text-align: center;">
                        <div style="font-weight: bold;">تازہ رقم (آج کا بل)</div>
                        <div id="bill-new-subtotal" class="bill-money" style="font-size: 1.1rem; font-weight: bold;">0</div>
                    </div>
                    <div style="margin-top: 10px; border: 2px solid black; padding: 5px; text-align: center;">
                        <div style="font-weight: bold;">کل بقایا رقم</div>
                        <div id="bill-total-outstanding" class="bill-money" style="font-size: 1.2rem; font-weight: bold;">0</div>
                    </div>
                </div>
            </div>

            <!-- Left Column: Items Table -->
            <div class="items-column" style="width: 75%;">
                <table class="bill-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid black; background: #ddd;">
                            <th style="border-left: 1px solid black; padding: 5px;">تعداد</th>
                            <th style="border-left: 1px solid black; padding: 5px;">تفصیل بیکری</th>
                            <th style="border-left: 1px solid black; padding: 5px;">نرخ</th>
                            <th style="padding: 5px;">روپے</th>
                        </tr>
                    </thead>
                    <tbody id="bill-items-body">
                        <!-- Rows -->
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid black;">
                            <td colspan="3" style="text-align: left; padding: 5px; font-weight: bold;">کل میزان (گراس ٹوٹل):</td>
                            <td id="bill-gross-total" class="bill-money" style="padding: 5px; font-weight: bold; text-align: center;">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="bill-footer">
            <p>نوٹ: مال فروخت ہونے کے بعد دکاندار ذمہ دار نہ ہوگا۔</p>
        </div>
    </div>

    <!-- Item Type Modal -->
    <div id="itemTypeModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="itemModalTitle" onclick="handleItemOverlayClick(event)">
        <div class="modal-window" style="width: 600px; height: 550px;" onclick="event.stopPropagation()">
            <div class="modal-title-bar" style="display: flex; justify-content: space-between; align-items: center; padding-right: 10px;">
                <span id="itemModalTitle" class="urdu-text" style="flex-grow: 1; text-align: center;">آئٹم منتخب کریں</span>
                <button type="button" class="modal-close-btn-x" onclick="closeItemTypeModal()" aria-label="بند کریں" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: black; font-weight: bold;">&times;</button>
            </div>
            <div class="modal-content">
                <!-- View Mode -->
                <div id="itemViewMode" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="modal-search-bar">
                        <label for="itemSearch" class="urdu-text">تلاش</label>
                        <input type="text" id="itemSearch" onkeyup="filterItems()" value="" autocomplete="off" placeholder="نام لکھیں..." style="flex: 1; padding: 5px;" class="urdu-text">
                        <button type="button" class="modal-btn urdu-text" onclick="toggleItemAddMode()" style="margin-left: 10px; background: #10b981; color: white;">نیا آئٹم</button>
                    </div>
                    
                    <div class="modal-table-container" style="flex: 1; overflow-y: auto; border: 1px solid #ccc;">
                        <table class="modal-table" id="itemTable">
                            <thead style="position: sticky; top: 0; z-index: 1;">
                                <tr>
                                    <th class="urdu-text" style="width: 70%;">نام (اردو)</th>
                                    <th class="urdu-text" style="width: 30%;">کوڈ</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody">
                                <!-- Rows populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="modal-footer" id="itemFooter" style="margin-top: 10px;">
                        <button type="button" class="modal-btn urdu-text" onclick="confirmItemSelection()">منتخب کریں</button>
                        <button type="button" class="modal-btn urdu-text" onclick="closeItemTypeModal()">بند کریں</button>
                    </div>
                </div>

                <!-- Add Mode -->
                <div id="itemAddMode" style="display: none; padding: 20px;">
                    <h4 class="urdu-text" style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">نیا آئٹم شامل کریں</h4>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label class="urdu-text" style="display: block; margin-bottom: 5px;">آئٹم نام (انگلش)</label>
                        <input type="text" id="newItemName" class="form-control" placeholder="مثال: کیک رسک">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label class="urdu-text" style="display: block; margin-bottom: 5px;">آئٹم نام (اردو)</label>
                        <input type="text" id="newItemUrduName" class="form-control urdu-text" placeholder="مثال: کیک رس" dir="rtl" data-urdu="true">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label class="urdu-text" style="display: block; margin-bottom: 5px;">کوڈ (خود کار)</label>
                        <input type="text" id="newItemCode" class="form-control" readonly placeholder="محفوظ کرنے پر خودکار بنے گا" style="background: #eee; color: #666;">
                    </div>
                    
                    <div style="text-align: right; margin-top: 20px; border-top: 1px solid #ccc; padding-top: 15px;">
                         <button type="button" class="modal-btn urdu-text" onclick="saveNewItem()" style="background: #10b981; color: white;">محفوظ کریں</button>
                         <button type="button" class="modal-btn urdu-text" onclick="toggleItemAddMode()">منسوخ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Item Modal Logic
        (function() {
            // Define globals explicitly on window to ensure visibility
            window.existingItems = [];
            try {
                window.existingItems = @json($items ?? [], JSON_HEX_TAG) || [];
            } catch (e) {
                console.error('Failed to load items data:', e);
                window.existingItems = [];
            }

            window.activeItemRowIndex = null;
            window.selectedItem = null;
            
            // Helper to get elements safely
            function getEl(id) { return document.getElementById(id); }

            window.renderItemTable = function(data) {
                data = data || window.existingItems;
                const tbody = getEl('itemTableBody');
                if (!tbody) return;
                
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="2" class="urdu-text" style="text-align:center; padding: 10px;">کوئی آئٹم نہیں ملا۔ "نیا آئٹم" پر کلک کریں۔</td></tr>';
                    return;
                }
                
                data.forEach((item) => {
                    const tr = document.createElement('tr');
                    tr.onclick = () => window.selectItemModalRow(tr, item);
                    tr.ondblclick = () => {
                        window.selectItemModalRow(tr, item);
                        window.confirmItemSelection();
                    };
                    
                    if (window.selectedItem && (window.selectedItem.id === item.id || (window.selectedItem.code === item.code && item.code))) {
                        tr.classList.add('selected');
                    }

                    tr.style.cursor = 'pointer';
                    tr.innerHTML = `
                        <td class="urdu-text">${item.urdu_name || item.name || ''}</td>
                        <td>${item.code || '-'}</td>
                    `;
                    tbody.appendChild(tr);
                });
            };

            window.selectItemModalRow = function(tr, item) {
                const tbody = getEl('itemTableBody');
                const rows = tbody.querySelectorAll('tr');
                rows.forEach(r => r.classList.remove('selected'));
                tr.classList.add('selected');
                window.selectedItem = item;
            };

            window.openItemTypeModal = function(element, index, event) {
                if (event && typeof event.stopPropagation === 'function') {
                    event.stopPropagation();
                }
                const itemTypeModal = getEl('itemTypeModal');
                if (itemTypeModal && itemTypeModal.classList.contains('active')) {
                    return;
                }
                window.activeItemRowIndex = index;
                window.selectedItem = null;
                
                // Try to pre-select based on current value
                const currentValue = element.value;
                if (currentValue) {
                    const match = window.existingItems.find(i => i.name === currentValue || i.urdu_name === currentValue);
                    if (match) window.selectedItem = match;
                }

                // Reset UI
                const viewMode = getEl('itemViewMode');
                const addMode = getEl('itemAddMode');
                const modal = getEl('itemTypeModal');
                const searchInput = getEl('itemSearch');
                
                if (viewMode) viewMode.style.display = 'flex';
                if (addMode) addMode.style.display = 'none';
                
                window.renderItemTable();
                if (searchInput) searchInput.value = '';
                if (modal) modal.classList.add('active');
                
                // Focus search
                setTimeout(() => {
                    if (searchInput) searchInput.focus();
                }, 100);
                
                document.body.style.overflow = 'hidden';
            };

            window.closeItemTypeModal = function() {
                const modal = getEl('itemTypeModal');
                if (modal) modal.classList.remove('active');
                window.activeItemRowIndex = null;
                document.body.style.overflow = '';
            };

            window.handleItemOverlayClick = function(event) {
                const modal = getEl('itemTypeModal');
                if (event.target === modal) {
                    window.closeItemTypeModal();
                }
            };

            window.filterItems = function() {
                const searchInput = getEl('itemSearch');
                if (!searchInput) return;
                
                const query = searchInput.value.toLowerCase();
                const filtered = window.existingItems.filter(item => 
                    (item.name && item.name.toLowerCase().includes(query)) || 
                    (item.urdu_name && item.urdu_name.toLowerCase().includes(query)) ||
                    (item.code && item.code.toLowerCase().includes(query))
                );
                window.renderItemTable(filtered);
            };

            window.toggleItemAddMode = function() {
                const viewMode = getEl('itemViewMode');
                const addMode = getEl('itemAddMode');
                const nameInput = getEl('newItemName');
                const urduInput = getEl('newItemUrduName');
                const searchInput = getEl('itemSearch');

                if (addMode.style.display === 'none') {
                    viewMode.style.display = 'none';
                    addMode.style.display = 'block';
                    
                    if (nameInput) nameInput.value = '';
                    if (urduInput) urduInput.value = '';
                    
                    setTimeout(() => { if(nameInput) nameInput.focus(); }, 100);
                } else {
                    viewMode.style.display = 'flex';
                    addMode.style.display = 'none';
                    setTimeout(() => { if(searchInput) searchInput.focus(); }, 100);
                }
            };

            window.saveNewItem = function() {
                const nameInput = getEl('newItemName');
                const urduInput = getEl('newItemUrduName');
                
                const name = nameInput ? nameInput.value.trim() : '';
                const urduName = urduInput ? urduInput.value.trim() : '';
                
                if (!name && !urduName) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'توثیقی خرابی',
                            text: 'براہ کرم کم از کم نام یا اردو نام درج کریں۔'
                        });
                    } else {
                        alert('براہ کرم کم از کم نام یا اردو نام درج کریں۔');
                    }
                    return;
                }

                const newItem = {
                    id: 'new_' + Date.now(),
                    code: 'NEW', 
                    name: name,
                    urdu_name: urduName
                };

                window.existingItems.push(newItem);
                window.selectedItem = newItem;
                
                window.toggleItemAddMode();
                window.renderItemTable(); 
                window.confirmItemSelection();
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'آئٹم شامل ہو گیا',
                        text: 'نیا آئٹم عارضی طور پر شامل کر دیا گیا ہے۔',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            };

            window.confirmItemSelection = function() {
                if (window.activeItemRowIndex !== null && window.selectedItem) {
                    const row = document.querySelector(`tr[data-row-index="${window.activeItemRowIndex}"]`);
                    if (row) {
                        const typeInput = row.querySelector('.item-type-input');
                        const codeInput = row.querySelector('input[name="items[' + window.activeItemRowIndex + '][code]"]');
                        
                        if (typeInput) {
                            typeInput.value = window.selectedItem.urdu_name || window.selectedItem.name;
                            typeInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        
                        if (codeInput && window.selectedItem.code !== 'NEW') {
                            codeInput.value = window.selectedItem.code;
                            codeInput.dispatchEvent(new Event('input', { bubbles: true }));
                        } else if (codeInput) {
                            codeInput.value = ''; 
                        }
                    }
                    window.closeItemTypeModal();
                } else {
                    window.closeItemTypeModal();
                }
            };
            
            // Keyboard Navigation
            document.addEventListener('keydown', function(event) {
                const modal = getEl('itemTypeModal');
                if (modal && modal.classList.contains('active')) {
                    if (event.key === 'Escape') {
                        window.closeItemTypeModal();
                    }
                }
            });

        })();

    </script>

 

    
<!-- Final Global Function Assignments -->
    <script>
        // Ensure all critical functions are globally accessible
        if (typeof window.saveAndPrintBakery !== 'function') {
            console.error('saveAndPrintBakery not assigned');
        }
        if (typeof window.printPurchaserDetailOnly !== 'function') {
            console.error('printPurchaserDetailOnly not assigned');
        }
        if (typeof window.calculatePurchaserTotals !== 'function') {
            console.error('calculatePurchaserTotals not assigned');
        }
        
        const finalFunctionStatus = {
            saveAndPrintBakery: typeof window.saveAndPrintBakery,
            printPurchaserDetailOnly: typeof window.printPurchaserDetailOnly,
            calculatePurchaserTotals: typeof window.calculatePurchaserTotals,
            addNewPurchaserRow: typeof window.addNewPurchaserRow,
            removePurchaserRow: typeof window.removePurchaserRow,
            updatePurchaserRowIndices: typeof window.updatePurchaserRowIndices
        };
    </script>

</body>
</html>



