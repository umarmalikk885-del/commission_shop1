@include('components.sidebar-styles')

@php
    $currentUser = auth()->user();
    $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
    $isAdmin = $currentUser && ($currentUser->hasRole('Admin') || $currentUser->hasRole('Super Admin'));
    $isUser = $currentUser && $currentUser->hasRole('User');
    $isOperator = $currentUser && $currentUser->hasRole('Operator');
@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h2><i class="fa fa-store" style="margin-right: 8px;"></i>کمیشن شاپ</h2>
    <ul>
        {{-- Dashboard: Always visible to all authenticated users --}}
        <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="fa fa-dashboard"></i> {{ __('messages.dashboard') }}</a></li>
        
        {{-- Inventory: Quick link below Dashboard --}}
        <li><a href="/inventory" class="{{ request()->is('inventory*') ? 'active' : '' }}"><i class="fa fa-box"></i> {{ __('messages.inventory') ?? 'انوینٹری' }}</a></li>
        
        {{-- Trader: New link --}}
        <li><a href="/index" class="{{ request()->is('index') ? 'active' : '' }}"><i class="fa fa-home"></i> {{ __('messages.trader') }}</a></li>
        
        {{-- Product Owner link removed --}}
        
        {{-- Items (Inventory) --}}
        <li><a href="{{ route('items.index') }}" class="{{ request()->is('items*') ? 'active' : '' }}"><i class="fa fa-list"></i> {{ __('messages.add_items') ?? 'اشیاء شامل کریں' }}</a></li>

        {{-- بیکری: تمام لاگ اِن صارفین کے لیے --}}
        <li><a href="/bakery" class="{{ request()->is('bakery*') ? 'active' : '' }}"><i class="fa fa-bread-slice"></i> {{ __('messages.bakery') ?? 'بیکری' }}</a></li>
        

        {{-- Reports: Only for Admin and Super Admin --}}
        @if($isAdmin || $isSuperAdmin)
            <li><a href="/reports" class="{{ request()->is('reports*') ? 'active' : '' }}"><i class="fa fa-chart-line"></i> {{ __('messages.reports') }}</a></li>
            {{-- Rokad --}}
            <li><a href="/rokad" class="{{ request()->is('rokad*') ? 'active' : '' }}"><i class="fa fa-book"></i> {{ __('messages.rokad') }}</a></li>
        @endif

        {{-- ادائیگی: تمام لاگ اِن صارفین کے لیے --}}
        <li><a href="/payment" class="{{ request()->is('payment*') ? 'active' : '' }}"><i class="fa fa-money-bill"></i> {{ __('messages.payment') ?? 'ادائیگی' }}</a></li>

        {{-- Recovery (Wasooli): Available to all authenticated users --}}
        <li><a href="/recovery" class="{{ request()->is('recovery*') ? 'active' : '' }}"><i class="fa fa-receipt"></i> {{ __('messages.recovery') ?? 'وصولی' }}</a></li>

        @if($isAdmin || $isSuperAdmin)
            <li><a href="/balance-sheet" class="{{ request()->is('balance-sheet*') ? 'active' : '' }}"><i class="fa fa-balance-scale"></i> {{ __('messages.balance_sheet') ?? 'بیلنس شیٹ' }}</a></li>
        @endif

        {{-- Backup: Only for Admin and Super Admin --}}
        @if($isAdmin || $isSuperAdmin)
            <li><a href="/backup" class="{{ request()->is('backup*') ? 'active' : '' }}"><i class="fa fa-database"></i> {{ __('messages.backup') ?? 'بیک اپ' }}</a></li>
        @endif
        
        {{-- Bank/Cash: Only for Admin and Super Admin --}}
        @if($isAdmin || $isSuperAdmin)
            <li><a href="/bank-cash" class="{{ request()->is('bank-cash*') ? 'active' : '' }}"><i class="fa fa-university"></i> {{ __('messages.bank_cash') }}</a></li>
        @endif
        
        {{-- Settings: For Admin, Super Admin, and User --}}
        @if($isAdmin || $isSuperAdmin || $isUser)
            <li><a href="/settings" class="{{ request()->is('settings*') && !request()->is('settings/roles') && !request()->is('settings/users') ? 'active' : '' }}"><i class="fa fa-gear"></i> {{ __('messages.settings') }}</a></li>
        @endif

    </ul>
</div>

<script src="{{ asset('js/global-dark-mode.js') }}"></script>
