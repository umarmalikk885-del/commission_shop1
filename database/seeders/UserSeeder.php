<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Get or create Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->assignRole($superAdminRole);

        // Create Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Get or create Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $admin->assignRole($adminRole);

        // Create regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
        ]);

        // Get or create User role
        $userRole = Role::firstOrCreate(['name' => 'User']);
        $user->assignRole($userRole);

        echo "Users created successfully!\n";
        echo "Super Admin: admin@admin.com / password\n";
        echo "Admin: admin2@admin.com / password\n";
        echo "User: user@user.com / password\n";
    }
}
