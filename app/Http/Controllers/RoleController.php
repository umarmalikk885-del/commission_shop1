<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Get current authenticated user (cached for request)
     */
    protected function currentUser(): ?User
    {
        static $user = null;
        if ($user === null) {
            $user = auth()->user();
        }
        return $user;
    }

    /*
      Check if current user is Super Admin
     */
    protected function isSuperAdmin(): bool
    {
        $user = $this->currentUser();
        return $user && $user->hasRole('Super Admin');
    }

    /**
     * Check if current user is Admin (includes Super Admin)
     */
    protected function isAdmin(): bool
    {
        $user = $this->currentUser();
        return $user && ($user->hasRole('Admin') || $user->hasRole('Super Admin'));
    }

    /**
     * Check if current user has permission to manage roles
     */
    protected function canManageRoles(): bool
    {
        $user = $this->currentUser();
        return $user && $user->hasPermissionTo('manage roles');
    }

    /**
     * Check if current user has permission to manage users
     */
    protected function canManageUsers(): bool
    {
        $user = $this->currentUser();
        return $user && $user->hasPermissionTo('manage users');
    }

    /**
     * Show role management page
     */
    public function index()
    {
        // Check both role and permission - only users with 'manage roles' permission can access
        if (!$this->canManageRoles() && !$this->isSuperAdmin()) {
            return redirect('/settings')->with('error', 'You do not have permission to access role management.');
        }

        // Optimize: Eager load permissions to avoid N+1 queries
        $roles = Role::with('permissions')->orderBy('name')->get();
        $allPermissions = Permission::orderBy('name')->get();

        return view('settings.roles', compact('roles', 'allPermissions'));
    }

    /**
     * Create a new role (requires 'manage roles' permission)
     */
    public function createRole(Request $request)
    {
        if (!$this->canManageRoles() && !$this->isSuperAdmin()) {
            return back()->with('error', 'You do not have permission to create roles.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        $role = Role::create(['name' => $data['name']]);

        return back()->with('success', "Role '{$role->name}' created successfully.");
    }

    /**
     * Update role permissions (requires 'manage roles' permission)
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        if (!$this->canManageRoles() && !$this->isSuperAdmin()) {
            return back()->with('error', 'You do not have permission to update role permissions.');
        }

        // Prevent modifying Super Admin role permissions
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Cannot modify Super Admin role permissions.');
        }

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        // Use transaction for data integrity
        DB::transaction(function () use ($role, $request) {
            $permissions = $request->input('permissions', []);
            if (!empty($permissions)) {
                $permissionModels = Permission::whereIn('name', $permissions)->get();
                $role->syncPermissions($permissionModels);
            } else {
                // Clear all permissions if none selected
                $role->syncPermissions([]);
            }
        });

        return back()->with('success', "Permissions updated for role '{$role->name}'.");
    }

    /**
     * Delete a role (requires 'manage roles' permission)
     */
    public function deleteRole(Role $role)
    {
        if (!$this->canManageRoles() && !$this->isSuperAdmin()) {
            return back()->with('error', 'You do not have permission to delete roles.');
        }

        // Prevent deleting Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Cannot delete Super Admin role.');
        }

        $roleName = $role->name;
        $role->delete();

        return back()->with('success', "Role '{$roleName}' deleted successfully.");
    }

    /**
     * Show user management page
     */
    public function users()
    {
        // Check both role and permission - only users with 'manage users' permission can access
        if (!$this->canManageUsers() && !$this->isAdmin()) {
            return redirect('/settings')->with('error', 'You do not have permission to manage users.');
        }

        // Optimize: Eager load roles with permissions to avoid N+1 queries
        $users = User::with(['roles.permissions', 'permissions'])->orderBy('name')->get();
        
        // Filter roles based on current user's responsibilities
        // - Super Admin: manages only Admin accounts → can assign Admin role
        // - Admin: manages non-admin users → can assign any role except Super Admin/Admin
        $allRoles = Role::orderBy('name')->get();
        if ($this->isSuperAdmin()) {
            // Super Admin can only assign Admin role
            $roles = $allRoles->filter(function ($role) {
                return $role->name === 'Admin';
            });
        } else {
            // Admin can assign any role except Super Admin and Admin
            $roles = $allRoles->filter(function ($role) {
                return !in_array($role->name, ['Super Admin', 'Admin']);
            });
        }

        return view('settings.users', compact('users', 'roles'));
    }

    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        // Check both role and permission
        if (!$this->canManageUsers() && !$this->isAdmin()) {
            return back()->with('error', 'You do not have permission to create users.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            // Allow passwords between 4 and 10 characters for admin-created users
            'password' => ['required', 'string', 'min:4', 'max:10', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        // Enforce responsibilities:
        // - Super Admin: can only create Admin accounts
        // - Admin: can create non-admin users (cannot create Super Admin or Admin)
        if ($this->isSuperAdmin() && $data['role'] !== 'Admin') {
            return back()->with('error', 'Super Admin can only create Admin accounts.');
        }

        if (!$this->isSuperAdmin() && in_array($data['role'], ['Super Admin', 'Admin'])) {
            return back()->with('error', 'Admins cannot create Super Admin or Admin accounts.');
        }

        // Use transaction for data integrity
        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $role = Role::where('name', $data['role'])->firstOrFail();
            $user->assignRole($role);

            return $user;
        });

        return back()->with('success', "User '{$user->name}' created successfully with role '{$data['role']}'.");
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        // Check both role and permission
        if (!$this->canManageUsers() && !$this->isAdmin()) {
            return back()->with('error', 'You do not have permission to update users.');
        }

        // Admin cannot modify Super Admin or Admin users
        // Optimize: Check roles once and cache (ensure roles are loaded)
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }
        $userRoles = $user->roles->pluck('name')->toArray();
        if (!$this->isSuperAdmin() && (in_array('Super Admin', $userRoles) || in_array('Admin', $userRoles))) {
            return back()->with('error', 'Admin can only modify User accounts.');
        }

        $data = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        // Enforce responsibilities on role changes:
        // - Super Admin: can only assign Admin role
        // - Admin: cannot assign Super Admin or Admin roles
        if ($this->isSuperAdmin() && $data['role'] !== 'Admin') {
            return back()->with('error', 'Super Admin can only assign Admin role.');
        }

        if (!$this->isSuperAdmin() && in_array($data['role'], ['Super Admin', 'Admin'])) {
            return back()->with('error', 'Admins cannot assign Super Admin or Admin roles.');
        }

        // Prevent removing Super Admin role from Super Admin users
        if (in_array('Super Admin', $userRoles) && $data['role'] !== 'Super Admin' && !$this->isSuperAdmin()) {
            return back()->with('error', 'Cannot modify Super Admin role.');
        }

        // Use transaction for data integrity
        DB::transaction(function () use ($user, $data) {
            $role = Role::where('name', $data['role'])->firstOrFail();
            $user->syncRoles([$role]);
        });

        return back()->with('success', "User '{$user->name}' role updated to '{$data['role']}'.");
    }

    /**
     * Update user permissions (requires 'manage users' permission)
     */
    public function updateUserPermissions(Request $request, User $user)
    {
        // Check both role and permission
        if (!$this->canManageUsers() && !$this->isAdmin()) {
            return back()->with('error', 'You do not have permission to update user permissions.');
        }

        // Admin cannot modify Super Admin or Admin permissions
        // Optimize: Use already loaded roles (ensure roles are loaded)
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }
        $userRoles = $user->roles->pluck('name')->toArray();
        if (!$this->isSuperAdmin() && (in_array('Super Admin', $userRoles) || in_array('Admin', $userRoles))) {
            return back()->with('error', 'Admins can only modify permissions for non-admin users.');
        }

        // Super Admin should only manage Admin accounts
        if ($this->isSuperAdmin() && !in_array('Admin', $userRoles)) {
            return back()->with('error', 'Super Admin can only update permissions for Admin accounts.');
        }

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        // Use transaction for data integrity
        DB::transaction(function () use ($user, $request) {
            $permissions = $request->input('permissions', []);
            if (!empty($permissions)) {
                $permissionModels = Permission::whereIn('name', $permissions)->get();
                $user->syncPermissions($permissionModels);
            } else {
                // Clear all permissions if none selected
                $user->syncPermissions([]);
            }
        });

        return back()->with('success', "Permissions updated for user '{$user->name}'.");
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user)
    {
        // Check both role and permission
        if (!$this->canManageUsers() && !$this->isAdmin()) {
            return back()->with('error', 'You do not have permission to delete users.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Admin cannot delete Super Admin or Admin users
        // Optimize: Use already loaded roles (ensure roles are loaded)
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }
        $userRoles = $user->roles->pluck('name')->toArray();
        if (!$this->isSuperAdmin() && (in_array('Super Admin', $userRoles) || in_array('Admin', $userRoles))) {
            return back()->with('error', 'Admins can only delete non-admin users.');
        }

        // Super Admin should only delete Admin accounts
        if ($this->isSuperAdmin() && !in_array('Admin', $userRoles)) {
            return back()->with('error', 'Super Admin can only delete Admin accounts.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "User '{$userName}' deleted successfully.");
    }
}
