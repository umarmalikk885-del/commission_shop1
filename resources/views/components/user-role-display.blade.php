<div class="user-role-display" style="position: relative; display: inline-block;">
    <div id="userDropdownTrigger" onclick="toggleUserDropdown()" style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer; padding: 5px 10px; border-radius: 6px; transition: background-color 0.2s; position: relative; z-index: 1101;">
        <i class="fa fa-user"></i>
        <span>
            @auth
                {{ auth()->user()->name }}
            @else
                {{ __('messages.guest') }}
            @endauth
        </span>
        <i class="fa fa-caret-down" style="font-size: 12px; margin-left: 4px;"></i>
    </div>

    <!-- Dropdown Menu -->
    <div id="userDropdownMenu" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 5px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); min-width: 150px; z-index: 1102;">
        @auth
            <a href="{{ route('profile.edit') }}" class="user-menu-item" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 16px; font-size: 14px; color: #374151; text-decoration: none; transition: background-color 0.2s; border-radius: 8px 8px 0 0; text-align: center;">
                <i class="fa fa-user"></i>
                {{ __('messages.profile') }}
            </a>
            <div style="height: 1px; background: #e5e7eb; margin: 4px 0;"></div>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="user-menu-item" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 16px; font-size: 14px; color: #374151; background: none; border: none; cursor: pointer; transition: background-color 0.2s; border-radius: 0 0 8px 8px; text-align: center;">
                    <i class="fa fa-sign-out"></i>
                    {{ __('messages.logout') }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" style="display: block; width: 100%; text-align: left; padding: 10px 16px; font-size: 14px; color: #374151; text-decoration: none; transition: background-color 0.2s; border-radius: 8px;">
                <i class="fa fa-sign-in" style="margin-right: 8px;"></i>
                {{ __('messages.log_in') }}
            </a>
        @endauth
    </div>
</div>

<style>
    /* Dark mode support for dropdown */
    body.dark-mode #userDropdownMenu {
        background: #1f2937 !important;
        border-color: #374151 !important;
    }
    body.dark-mode #userDropdownMenu button,
    body.dark-mode #userDropdownMenu a {
        color: #e5e7eb !important;
    }
    body.dark-mode #userDropdownMenu button:hover,
    body.dark-mode #userDropdownMenu a:hover {
        background-color: #374151 !important;
    }
    body.dark-mode #userDropdownMenu div {
        background: #374151 !important;
    }
    
    /* Hover effect for light mode */
    #userDropdownMenu button:hover,
    #userDropdownMenu a:hover {
        background-color: #f3f4f6;
    }

    /* Center text & icon in dropdown items (both languages) */
    #userDropdownMenu .user-menu-item {
        justify-content: center;
    }
    
    /* Hover effect for trigger */
    #userDropdownTrigger:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    body.dark-mode #userDropdownTrigger:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<script>
    function toggleUserDropdown() {
        const menu = document.getElementById('userDropdownMenu');
        if (menu.style.display === 'none') {
            menu.style.display = 'block';
            // Close dropdown when clicking outside
            document.addEventListener('click', closeUserDropdownOutside);
        } else {
            menu.style.display = 'none';
            document.removeEventListener('click', closeUserDropdownOutside);
        }
    }

    function closeUserDropdownOutside(event) {
        const trigger = document.getElementById('userDropdownTrigger');
        const menu = document.getElementById('userDropdownMenu');
        if (!trigger.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = 'none';
            document.removeEventListener('click', closeUserDropdownOutside);
        }
    }
</script>
