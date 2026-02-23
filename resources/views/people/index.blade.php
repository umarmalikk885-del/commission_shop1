@php
    $lang = app()->getLocale();
    if ($lang === null) {
        $lang = 'ur';
    } elseif (str_starts_with($lang, 'ur')) {
        $lang = 'ur';
    } else {
        $lang = 'ur';
    }

    $currentUser = auth()->user();
    $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('تاجر') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.urdu-input-support')
    @include('components.main-content-spacing')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', 'Noto Nastaliq Urdu', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .main { padding: 24px; }
        .urdu-text { font-family: 'Noto Nastaliq Urdu', serif; line-height: 2; }
        .rtl .trader-header {
            flex-direction: row;
        }

        /* Trader table readability (page-scoped) */
        .trader-table-outer .table {
            --bs-table-bg: #ffffff;
            --bs-table-color: #1e293b;
            --bs-table-striped-bg: #f8fafc;
            --bs-table-striped-color: #1e293b;
            --bs-table-hover-bg: #eef2ff;
            --bs-table-hover-color: #0f172a;
        }

        .trader-table-outer .table tbody td {
            color: #1e293b;
        }

        body.dark-mode .trader-table-outer .table {
            --bs-table-bg: #1e293b;
            --bs-table-color: #e2e8f0;
            --bs-table-striped-bg: #243244;
            --bs-table-striped-color: #e2e8f0;
            --bs-table-hover-bg: #334155;
            --bs-table-hover-color: #f8fafc;
            border-color: #334155;
        }

        body.dark-mode .trader-table-outer .table thead.table-dark th {
            background: #111827 !important;
            color: #f1f5f9 !important;
            border-color: #334155 !important;
        }

        body.dark-mode .trader-table-outer .table tbody td {
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }
    </style>
</head>
<body class="{{ $lang === 'ur' ? 'rtl' : '' }}">

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    @include('components.sidebar')

    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                        <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center trader-header">
                            <div class="d-flex align-items-center gap-3">
                                @include('components.user-role-display')
                            </div>
                            <a href="{{ route('people.create') }}" class="btn btn-primary" style="{{ $lang === 'ur' ? 'order:-1;' : '' }}">
                                <i class="fas fa-plus"></i> {{ __('شخص شامل کریں') }}
                            </a>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="trader-table-outer" role="region" aria-label="تاجر فہرست">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>{{ __('Code') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Address') }}</th>
                                            <th>{{ __('موبائل نمبر') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($people as $person)
                                            <tr>
                                                <td>{{ $person->id }}</td>
                                                <td>{{ $person->name }}</td>
                                                <td>{{ $person->address }}</td>
                                                <td>{{ $person->phone }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('people.edit', $person) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('people.destroy', $person) }}" method="POST" style="display: inline;" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-name="{{ $person->name }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">{{ __('کوئی شخص نہیں ملا۔') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }
        
        // Show SweetAlert for success messages
        document.addEventListener('DOMContentLoaded', function() {
            // Check for Laravel session success message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'کامیابی!',
                    text: "{{ session('success') }}",
                    timer: 6000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    position: 'center',
                    toast: false
                });
            @endif
            
            // SweetAlert delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.delete-form');
                    const name = this.getAttribute('data-name');
                    const lang = '{{ app()->getLocale() }}';
                    
                    Swal.fire({
                        title: 'کیا آپ کو یقین ہے؟',
                        text: `آپ "${name}" کو حذف کرنے والے ہیں۔ یہ عمل واپس نہیں کیا جا سکتا!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'ہاں، اسے حذف کریں!',
                        cancelButtonText: 'منسوخ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'حذف کیا جا رہا ہے...',
                                text: 'براہ کرم انتظار کریں جب تک ہم تاجر کا ریکارڈ حذف کر رہے ہیں۔',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            // Submit form via AJAX
                            const formData = new FormData(form);
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => {
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    return response.json().then(data => {
                                        if (!response.ok) throw data;
                                        return data;
                                    });
                                } else {
                                    return response.text().then(text => {
                                        throw new Error('سرور سے غیر JSON جواب موصول ہوا۔ براہ کرم مزید تفصیلات کے لیے سرور لاگز کو چیک کریں۔');
                                    });
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'حذف کر دیا گیا!',
                                        text: data.message || 'تاجر کو کامیابی سے حذف کر دیا گیا ہے۔',
                                        confirmButtonColor: '#3085d6',
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        // Reload the page to reflect changes
                                        window.location.reload();
                                    });
                                }
                            })
                            .catch(error => {
                                let errorTitle = 'خرابی!';
                                let errorText = 'تاجر کو حذف کرنے میں ناکام۔ براہ کرم دوبارہ کوشش کریں.';

                                if (error.message) {
                                    errorText = error.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: errorTitle,
                                    text: errorText,
                                    confirmButtonColor: '#3085d6'
                                });
                            });
                        }
                    });
                });
            });
            
            // Auto-hide bootstrap success message after 6 seconds
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.transition = 'opacity 0.5s';
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500);
                }, 6000);
            }
        });
    </script>
</body>
</html>
