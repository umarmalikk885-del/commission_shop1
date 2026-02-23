<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FixAuthSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin user
        $user = User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('superadmin'),
            ]
        );

        // Get or create Super Admin role
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        
        // Assign role to user
        $user->syncRoles([$role]);

        echo "Super Admin user created/updated: superadmin / superadmin\n";
    }
}
