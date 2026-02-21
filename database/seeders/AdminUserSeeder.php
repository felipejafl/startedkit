<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super-admin user from environment or defaults
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminName = env('ADMIN_NAME', 'Administrator');
        $adminPassword = env('ADMIN_PASSWORD', 'password123');

        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign super-admin role
        $user->assignRole('super-admin');
    }
}
