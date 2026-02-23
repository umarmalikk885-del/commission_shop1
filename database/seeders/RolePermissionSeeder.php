<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage users',
            'view sales',
            'manage sales',
            'view purchases',
            'create purchases',
            'edit purchases',
            'delete purchases',
            'manage purchases',
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
            'manage payments',
            'view vendors',
            'manage vendors',
            'view reports',
            'export reports',
            'view stock',
            'manage stock',
            'view settings',
            'edit settings',
            'view bank-cash',
            'manage bank-cash',
            'view dues',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Super Admin role with all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // Create Admin role with specific permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions([
            'view dashboard',
            'view users',
            'create users',
            'edit users',
            'view purchases',
            'create purchases',
            'edit purchases',
            'view payments',
            'create payments',
            'edit payments',
            'view reports',
            'export reports',
            'view settings',
        ]);

        // Create User role with basic permissions
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->syncPermissions([
            'view dashboard',
            'view purchases',
            'create purchases',
            'view payments',
            'create payments',
            'view reports',
        ]);

        // Create Product Owner role with supplier management permissions
        $productOwnerRole = Role::firstOrCreate(['name' => 'Product Owner']);
        $productOwnerRole->syncPermissions([
            'view dashboard',
            'view vendors',
            'manage vendors',
            'view purchases',
            'create purchases',
            'edit purchases',
            'delete purchases',
            'manage purchases',
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',
            'manage payments',
            'view reports',
            'export reports',
            'view stock',
            'manage stock',
            'view settings',
            'edit settings',
        ]);

        // Create Super Admin user
        $superAdmin = User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('superadmin'),
            ]
        );
        $superAdmin->syncRoles([$superAdminRole]);

        // Create Admin user
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
            ]
        );
        $admin->syncRoles([$adminRole]);

        // Create regular User
        $user = User::updateOrCreate(
            ['username' => 'user'],
            [
                'name' => 'Regular User',
                'email' => 'user@user.com',
                'password' => Hash::make('user'),
            ]
        );
        $user->syncRoles([$userRole]);

        // Create Product Owner user
        $productOwner = User::updateOrCreate(
            ['username' => 'productowner'],
            [
                'name' => 'Product Owner User',
                'email' => 'productowner@admin.com',
                'password' => Hash::make('productowner'),
            ]
        );
        $productOwner->syncRoles([$productOwnerRole]);

        echo "Roles and permissions seeded successfully!\n";
        echo "Super Admin: superadmin / superadmin\n";
        echo "Admin: admin / admin\n";
        echo "User: user / user\n";
    }
}
