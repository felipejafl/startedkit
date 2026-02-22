<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SetupAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update the super-admin user interactively';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Super-Admin Setup ===');
        $this->newLine();

        // Check if super-admin role exists
        if (! Role::where('name', 'super-admin')->exists()) {
            $this->error('Super-admin role does not exist. Run seeders first: php artisan db:seed');

            return 1;
        }

        // Get admin details
        $email = $this->ask('Admin email', 'admin@example.com');

        // Validate email format
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email format');

            return 1;
        }

        $name = $this->ask('Admin name', 'Administrator');

        // Check if user already exists and ask for confirmation
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->warn("User with email {$email} already exists");
            if (! $this->confirm('Do you want to update it?')) {
                $this->info('Cancelled');

                return 0;
            }
        }

        // Get password securely
        $password = $this->secret('Admin password (at least 8 characters)');

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters');

            return 1;
        }

        // Confirm password
        $passwordConfirm = $this->secret('Confirm password');
        if ($password !== $passwordConfirm) {
            $this->error('Passwords do not match');

            return 1;
        }

        // Create or update user
        try {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Assign super-admin role
            $user->syncRoles(['super-admin']);

            $this->info('✅ Super-admin user created/updated successfully!');
            $this->newLine();
            $this->table(
                ['Property', 'Value'],
                [
                    ['Email', $user->email],
                    ['Name', $user->name],
                    ['Role', 'super-admin'],
                    ['Status', $user->is_active ? 'Active' : 'Inactive'],
                ]
            );
            $this->newLine();
            $this->info('You can now login at /login with these credentials');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error creating user: '.$e->getMessage());

            return 1;
        }
    }
}
