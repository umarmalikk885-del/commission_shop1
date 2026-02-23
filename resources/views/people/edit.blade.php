@php
    $lang = app()->getLocale();
    if ($lang === null) {
        $lang = 'ur';
    } elseif (str_starts_with($lang, 'ur')) {
        $lang = 'ur';
    } else {
        $lang = 'ur';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('شخص میں ترمیم') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
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
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('شخص میں ترمیم') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('people.update', $person) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('Name') }} *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $person->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">{{ __('Phone') }} *</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" value="{{ old('phone', $person->phone) }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">{{ __('Address') }} *</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" name="address" rows="3" required>{{ old('address', $person->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> {{ __('Update') }}
                                        </button>
                                        <a href="{{ route('people.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
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
        
        // Form submission with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const lang = '{{ app()->getLocale() }}';

                    // Validate form
                    const name = document.getElementById('name').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    const address = document.getElementById('address').value.trim();
                    
                    if (!name || !phone || !address) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خرابی!',
                            text: 'براہ کرم تمام مطلوبہ خانے پُر کریں۔',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }
                    
                    // Show loading
                    Swal.fire({
                        title: 'اپ ڈیٹ ہو رہا ہے...',
                        text: 'براہ کرم انتظار کریں جب تک ہم تاجر کا ریکارڈ اپ ڈیٹ کر رہے ہیں۔',
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
                                title: 'کامیابی!',
                                text: 'ریکارڈ کامیابی سے اپ ڈیٹ ہو گیا۔',
                                confirmButtonColor: '#3085d6',
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = data.redirect || '{{ route("people.index") }}';
                            });
                        }
                    })
                    .catch(error => {
                        let errorTitle = 'توثیق کی خرابی!';
                        let errorText = 'تاجر کو اپ ڈیٹ کرنے میں ناکام۔ براہ کرم دوبارہ کوشش کریں۔';

                        if (error.errors) {
                            errorText = Object.values(error.errors).map(err => err.join('<br>')).join('<br>');
                        } else if (error.message) {
                            errorText = error.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: errorTitle,
                            html: errorText,
                            confirmButtonColor: '#3085d6'
                        });
                    });
                });
            }
        });
    </script>
</body>
</html>
