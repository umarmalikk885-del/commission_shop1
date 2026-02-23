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
    <title>{{ __('اشیاء') }} | کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    @include('components.global-dark-mode-styles')
    @include('components.urdu-input-support')
    @include('components.main-content-spacing')

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .items-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
    </style>
</head>
<body class="{{ $lang === 'ur' ? 'rtl' : '' }}">

    <!-- Mobile Menu -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    @include('components.sidebar')

    <div class="main">
        <div class="topbar">
            <div style="display: flex; gap: 15px; align-items: center; justify-content: space-between; width: 100%;">
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-mandi btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="fa fa-plus-circle"></i> {{ __('نیا آئٹم') }}
                    </button>
                    <button type="button" class="btn-mandi btn-secondary" data-bs-toggle="modal" data-bs-target="#packingModal" style="background: #64748b; color: white;">
                        <i class="fa fa-box-open"></i> {{ __('پیکنگ') }}
                    </button>
                </div>
                @include('components.user-role-display')
            </div>
        </div>

        <div class="items-grid">

            <div class="mandi-card">
                <div class="mandi-table-container">
                    <table class="mandi-table">
                        <thead>
                            <tr>
                                <th>{{ __('کوڈ') }}</th>
                                <th>{{ __('نام') }}</th>
                                <th>{{ __('قسم') }}</th>
                                <th>{{ __('کارروائی') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td><span class="badge-code">{{ $item->code }}</span></td>
                                    <td class="urdu-text">{{ $item->name }}</td>
                                    <td>{{ ucfirst(__($item->type)) }}</td>
                                    <td>
                                        <button class="nav-btn" title="ترمیم کریں" onclick='openEditModal(@json($item))' aria-label="آئٹم میں ترمیم کریں"><i class="fa fa-edit"></i></button>
                                        <button class="nav-btn" style="color: #ef4444;" title="حذف کریں" onclick="deleteItem({{ $item->id }})" aria-label="آئٹم حذف کریں"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">{{ __('کوئی آئٹم نہیں ملا') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content mandi-modal-content">
                <div class="modal-header mandi-modal-header">
                    <h5 class="modal-title urdu-text" id="addItemModalLabel"><i class="fa fa-plus-circle text-primary"></i> {{ __('نیا آئٹم شامل کریں') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
                </div>
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label urdu-text mandi-label">{{ __('نام (اردو)') }}</label>
                            <input type="text" name="name" class="form-control urdu-text mandi-input" required placeholder="سیب، آم وغیرہ" oninput="this.value = this.value.replace(/[a-zA-Z]/g, '')">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label mandi-label">{{ __('کوڈ') }}</label>
                                <input type="text" name="code" class="form-control mandi-input" placeholder="P001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label mandi-label">{{ __('قسم') }}</label>
                                <select name="type" class="form-select mandi-input">
                                    <option value="fruit">{{ __('پھل') }}</option>
                                    <option value="vegetable">{{ __('سبزی') }}</option>
                                    <option value="other">{{ __('دیگر') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mandi-modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('بند کریں') }}</button>
                        <button type="submit" class="btn btn-primary btn-mandi"><i class="fa fa-save"></i> {{ __('محفوظ کریں') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content mandi-modal-content">
                <div class="modal-header mandi-modal-header">
                    <h5 class="modal-title urdu-text" id="editItemModalLabel"><i class="fa fa-edit text-primary"></i> {{ __('آئٹم میں ترمیم کریں') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
                </div>
                <form id="editItemForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label urdu-text mandi-label">{{ __('نام (اردو)') }}</label>
                            <input type="text" name="name" id="edit_name" class="form-control urdu-text mandi-input" required oninput="this.value = this.value.replace(/[a-zA-Z]/g, '')">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label mandi-label">{{ __('کوڈ') }}</label>
                                <input type="text" name="code" id="edit_code" class="form-control mandi-input" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label mandi-label">{{ __('قسم') }}</label>
                                <select name="type" id="edit_type" class="form-select mandi-input">
                                    <option value="fruit">{{ __('پھل') }}</option>
                                    <option value="vegetable">{{ __('سبزی') }}</option>
                                    <option value="other">{{ __('دیگر') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mandi-modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('بند کریں') }}</button>
                        <button type="submit" class="btn btn-primary btn-mandi"><i class="fa fa-save"></i> {{ __('محفوظ کریں') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteItemForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Packing Modal -->
    <div class="modal fade" id="packingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content mandi-modal-content">
                <div class="modal-header mandi-modal-header position-relative">
                    <h5 class="modal-title urdu-text" id="packingModalLabel"><i class="fa fa-box-open text-primary"></i> {{ __('پیکنگ اندراج') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); margin: 0;"></button>
                </div>
                <form id="packingForm" onsubmit="handlePackingSubmit(event)">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label urdu-text mandi-label">{{ __('پیکنگ کا نام') }}</label>
                            <input type="text" name="name" class="form-control urdu-text mandi-input" placeholder="مثال: بوری، کریٹ" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label mandi-label">{{ __('کوڈ') }}</label>
                            <input type="text" name="code" class="form-control mandi-input" placeholder="P-001" required>
                        </div>
                    </div>
                    <div class="modal-footer mandi-modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('بند کریں') }}</button>
                        <button type="submit" class="btn btn-primary btn-mandi"><i class="fa fa-save"></i> {{ __('محفوظ کریں') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("کامیابی!") }}',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'ٹھیک ہے',
                    background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("خرابی!") }}',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'ٹھیک ہے',
                    background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
                });
            @endif

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ' + '{{ __("براہ کرم انتظار کریں...") }}';
                    }
                });
            });

            // Accessibility Fix: Handle focus and inert state for Packing Modal
            const packingModal = document.getElementById('packingModal');
            const mainContent = document.querySelector('.main');
            
            if (packingModal && mainContent) {
                packingModal.addEventListener('show.bs.modal', function () {
                    // Prevent "descendant retained focus" error by blurring the trigger button
                    if (document.activeElement && mainContent.contains(document.activeElement)) {
                        document.activeElement.blur();
                    }
                    // Use inert to properly hide background content as recommended
                    mainContent.setAttribute('inert', '');
                });

                packingModal.addEventListener('hidden.bs.modal', function () {
                    mainContent.removeAttribute('inert');
                });
            }

            const params = new URLSearchParams(window.location.search || '');
            if (params.get('from') === 'stock' && window.sessionStorage) {
                try {
                    const raw = window.sessionStorage.getItem('lastStockItem');
                    if (raw) {
                        const last = JSON.parse(raw);
                        window.sessionStorage.removeItem('lastStockItem');
                        const modalEl = document.getElementById('addItemModal');
                        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            const nameInput = modalEl.querySelector('input[name="name"]');
                            if (nameInput && last.item_name) {
                                nameInput.value = last.item_name;
                            }
                            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                            modal.show();
                        }
                    }
                } catch (e) {}
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }

        function openEditModal(item) {
            document.getElementById('editItemForm').action = `/items/${item.id}`;
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_code').value = item.code;
            document.getElementById('edit_type').value = item.type;
            
            new bootstrap.Modal(document.getElementById('editItemModal')).show();
        }

        function deleteItem(id) {
            Swal.fire({
                title: '{{ __("کیا آپ یقین رکھتے ہیں؟") }}',
                text: '{{ __("کیا آپ واقعی اس آئٹم کو حذف کرنا چاہتے ہیں؟") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '{{ __("ہاں، اسے حذف کریں") }}',
                cancelButtonText: '{{ __("منسوخ کریں") }}',
                background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteItemForm');
                    form.action = `/items/${id}`;
                    form.submit();
                }
            });
        }

        function handlePackingSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Store original content if not already stored
            if (!submitBtn.dataset.originalContent) {
                submitBtn.dataset.originalContent = submitBtn.innerHTML;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __("محفوظ ہو رہا ہے...") }}';

            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                code: formData.get('code'),
                labor: formData.get('labor') || 0,
                details: formData.get('details'),
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("packings.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 201) {
                    const modalEl = document.getElementById('packingModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("کامیابی!") }}',
                        text: '{{ __("پیکنگ کی تفصیلات محفوظ ہو گئیں۔") }}',
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'ٹھیک ہے',
                        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                        color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
                    });
                    
                    form.reset();
                } else {
                    // Validation or other errors
                    let errorMessage = body.message || '{{ __("کوئی خرابی پیش آئی") }}';
                    if (body.errors) {
                        errorMessage = Object.values(body.errors).flat().join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("خرابی!") }}',
                        text: errorMessage,
                        confirmButtonColor: '#ef4444',
                        background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                        color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("خرابی!") }}',
                    text: '{{ __("سرور سے رابطہ نہیں ہو سکا۔") }}',
                    confirmButtonColor: '#ef4444',
                    background: document.body.classList.contains('dark-mode') ? '#1e293b' : '#fff',
                    color: document.body.classList.contains('dark-mode') ? '#fff' : '#000'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalContent;
            });
        }
    </script>
</body>
</html>
