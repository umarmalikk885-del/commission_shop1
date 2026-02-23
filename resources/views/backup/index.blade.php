<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بیک اپ | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.prevent-back-button')
    @include('components.global-dark-mode-styles')
    @include('components.main-content-spacing')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap');
        
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Noto Nastaliq Urdu', 'Segoe UI', sans-serif; background: #f0f2f5; direction: rtl; }
        .main { padding: 0; }
        .header { background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%); color: white; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .page-title { font-size: 24px; font-weight: bold; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .btn-primary { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: background 0.2s; }
        .btn-primary:hover { background: #218838; }
        .btn-danger { background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .btn-danger:hover { background: #c82333; }
        .btn-download { background: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; margin-left: 5px; }
        .btn-download:hover { background: #0056b3; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; color: #555; }
        tr:hover { background-color: #f1f1f1; }
        
        .empty-state { text-align: center; padding: 40px; color: #777; font-size: 16px; }
    </style>
</head>
<body>
    @include('components.sidebar')
    
    <div class="main">
        <div class="header">
            <div style="font-size: 20px; font-weight: bold;">ڈیٹا بیک اپ</div>
            @include('components.user-role-display')
        </div>
        
        <div class="container">
            @if(session('success'))
                <script>
                    Swal.fire({ icon: 'success', title: 'کامیابی', text: @json(session('success')), confirmButtonText: 'ٹھیک ہے' });
                </script>
            @endif
            @if(session('error'))
                <script>
                    Swal.fire({ icon: 'error', title: 'خرابی', text: @json(session('error')), confirmButtonText: 'ٹھیک ہے' });
                </script>
            @endif

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="page-title">
                        <i class="fa fa-database text-primary"></i>
                        بیک اپ لسٹ
                    </div>
                    <form action="{{ route('backup.store') }}" method="POST" id="createBackupForm">
                        @csrf
                        <button type="button" onclick="confirmBackup()" class="btn-primary">
                            <i class="fa fa-plus-circle"></i> نیا بیک اپ بنائیں
                        </button>
                    </form>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>فائل کا نام</th>
                            <th>سائز</th>
                            <th>تاریخ</th>
                            <th style="text-align: left;">عمل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $backup)
                            <tr>
                                <td style="direction: ltr; text-align: right;">{{ $backup['name'] }}</td>
                                <td style="direction: ltr; text-align: right;">{{ $backup['size'] }}</td>
                                <td style="direction: ltr; text-align: right;">{{ $backup['date'] }}</td>
                                <td style="text-align: left;">
                                    <a href="{{ route('backup.download', $backup['name']) }}" class="btn-download">
                                        <i class="fa fa-download"></i> ڈاؤن لوڈ
                                    </a>
                                    <form action="{{ route('backup.destroy', $backup['name']) }}" method="POST" style="display: inline-block;" onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">
                                            <i class="fa fa-trash"></i> حذف کریں
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    کوئی بیک اپ موجود نہیں ہے۔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmBackup() {
            Swal.fire({
                title: 'تصدیق کریں',
                text: "کیا آپ نیا بیک اپ بنانا چاہتے ہیں؟",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ہاں، بنائیں',
                cancelButtonText: 'منسوخ کریں'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'بیک اپ بن رہا ہے...',
                        text: 'براہ کرم انتظار کریں',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('createBackupForm').submit();
                }
            });
        }

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'کیا آپ یقین رکھتے ہیں؟',
                text: "یہ فائل ہمیشہ کے لیے حذف ہو جائے گی!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ہاں، حذف کریں',
                cancelButtonText: 'منسوخ کریں'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
