<!DOCTYPE html>
<html lang="{{ $appLanguage ?? 'ur' }}" dir="{{ ($appLanguage ?? 'ur') === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>وینڈرز فہرست - کمیشن شاپ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @include('components.prevent-back-button')
    @include('components.admin-layout-styles')
    @include('components.sidebar-styles')
    
    <style>
        .action a {
            margin-right: 8px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
        }
        .action a:hover {
            text-decoration: underline;
        }
    </style>
    @include('components.urdu-input-support')
    @php
        // Effective role for UI: admin user always sees full interface,
        // others follow the application role from settings.
        $settingsRole = optional(\App\Models\CompanySetting::current())->role ?? 'admin';
        $appRole = (auth()->check() && auth()->user()->email === 'admin') ? 'admin' : $settingsRole;
    @endphp
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
        <input
            type="text"
            id="vendorSearchInput"
            placeholder="{{ __('messages.search_vendors') }}">
        @include('components.user-role-display')
    </div>

    <!-- Content -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2 style="margin: 0;">{{ __('messages.vendors') }}</h2>
            <a href="/vendors/create" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('messages.add_vendor') }}</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('messages.id') }}</th>
                        <th>{{ __('messages.vendor_name') }}</th>
                        <th>{{ __('messages.mobile') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.commission_percent') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->mobile }}</td>
                            <td>
                                <span class="pill {{ $vendor->status == 'active' ? 'pill-success' : 'pill-danger' }}">
                                    {{ $vendor->status == 'active' ? __('messages.active') : __('messages.blocked') }}
                                </span>
                            </td>
                            <td>{{ $vendor->commission_rate }}%</td>
                            <td class="action">
                                <a href="/vendors/{{ $vendor->id }}/edit"><i class="fa fa-pen"></i> {{ __('messages.edit') }}</a>
                                <a href="/vendors/{{ $vendor->id }}/delete"
                                   onclick="return confirm('{{ __('messages.are_you_sure') }}')" class="text-danger" style="color: #ef4444;"><i class="fa fa-trash"></i> {{ __('messages.delete') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">{{ __('messages.no_vendors_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    (function () {
        const searchInput = document.getElementById('vendorSearchInput');
        const table = document.querySelector('.main table');
        if (!searchInput || !table) return;

        // All table rows except the header
        const rows = Array.from(table.querySelectorAll('tbody tr'));

        searchInput.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                if (!cells.length) return;

                // Search across main columns: ID, Vendor Name, Mobile, Status, Commission
                const text = Array.from(cells)
                    .slice(0, 5)
                    .map(td => (td.innerText || '').toLowerCase())
                    .join(' ');

                row.style.display = term === '' || text.includes(term) ? '' : 'none';
            });
        });
    })();
</script>

<!-- Global Dark Mode Script -->
<script src="{{ asset('js/global-dark-mode.js') }}"></script>

</body>
</html>
