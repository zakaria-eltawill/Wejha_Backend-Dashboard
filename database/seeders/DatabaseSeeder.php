<?php

namespace Database\Seeders;

use App\Models\User;
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
        // 1. Seed Roles
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Coordinator', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);

        // 2. Create default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@wejha.com'],
            [
                'name' => 'مدير النظام / Administrator',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'status' => 'active',
                'preferred_language' => 'ar',
                'preferred_theme' => 'system',
                'timezone' => config('app.timezone', 'Africa/Tripoli'),
            ]
        );

        if (empty($admin->username)) {
            $admin->update(['username' => 'admin']);
        }

        // 3. Assign Role
        $admin->assignRole($superAdminRole);
    }
}
