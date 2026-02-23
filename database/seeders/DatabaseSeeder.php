<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // TEST CREDENTIALS FOR AUTHENTICATION
        // ============================================
        // These credentials use username-based login (name field)
        // Login with the username shown below and corresponding password
        
        // Super Admin User
        // Username: superadmin
        // Password: superadmin
        // Role: Super Admin (Full access including role management)
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@commission.com',
            'password' => bcrypt('superadmin'),
        ]);

        // Admin User
        // Username: admin
        // Password: admin
        // Role: Admin (Full access except role management)
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@commission.com',
            'password' => bcrypt('admin'),
        ]);

        // Regular User
        // Username: user
        // Password: user
        // Role: User (Limited access based on permissions)
        $user = User::factory()->create([
            'name' => 'Regular User',
            'username' => 'user',
            'email' => 'user@commission.com',
            'password' => bcrypt('user'),
        ]);

        // Operator User (for backward compatibility)
        // Username: operator
        // Password: operator
        // Role: Operator (Limited permissions)
        $operator = User::factory()->create([
            'name' => 'Operator User',
            'username' => 'operator',
            'email' => 'operator@commission.com',
            'password' => bcrypt('operator'),
        ]);

        // Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'Operator']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Permissions
        $permissions = [
            'view dashboard',
            'view sales',
            'manage sales',
            'view purchases',
            'manage purchases',
            'view vendors',
            'manage vendors',
            'view reports',
            'view stock',
            'manage stock',
            'view settings',
            'manage settings',
            'view bank-cash',
            'manage bank-cash',
            'view dues',
            'manage roles',
            'manage users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Super Admin gets everything including role management
        $superAdminRole->givePermissionTo(Permission::all());
        
        // Admin gets everything except role management
        $adminPermissions = Permission::whereNotIn('name', ['manage roles'])->get();
        $adminRole->syncPermissions($adminPermissions);
        
        // User role gets basic permissions (can be customized)
        $userPermissions = Permission::whereIn('name', [
            'view dashboard',
            'view sales',
            'view purchases',
            'view stock',
        ])->get();
        $userRole->syncPermissions($userPermissions);
        
        // Operator gets limited permissions
        $operatorRole->syncPermissions([
            'view dashboard',
            'view sales',
            'manage sales',
            'view purchases',
            'manage purchases',
            'view dues',
            'view stock',
        ]);
        
        // Assign roles to users
        $superAdmin->assignRole($superAdminRole);
        $admin->assignRole($adminRole);
        $user->assignRole($userRole);
        $operator->assignRole($operatorRole);

        $this->call([
            DashboardSeeder::class,
            StockSeeder::class,
        ]);
    }
}
