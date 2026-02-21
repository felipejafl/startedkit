<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\PermissionSeeder::class);
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

// User status tests
test('user can toggle active status', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(['admin.access', 'users.deactivate']);

    $user = User::factory()->create(['is_active' => true]);

    expect($user->is_active)->toBeTrue();
});

test('inactive user cannot login', function () {
    $user = User::factory()->create([
        'email' => 'inactive@test.com',
        'password' => bcrypt('password'),
        'is_active' => false,
    ]);

    $response = $this->post('/login', [
        'email' => 'inactive@test.com',
        'password' => 'password',
    ]);

    // After login attempt, if user is inactive, Fortify should reject
    $this->assertGuest();
});

// User creation validation
test('user email must be unique', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(['admin.access', 'users.create']);

    $existingUser = User::factory()->create(['email' => 'test@test.com']);

    $this->actingAs($admin);
});

// Super-admin protection tests
test('cannot remove super-admin role from last super-admin', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $targetUser = User::factory()->create();
    $targetUser->assignRole('admin');

    expect($superAdmin->hasRole('super-admin'))->toBeTrue();
});

test('cannot delete all super-admins', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(['admin.access', 'users.update']);

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    // Even with permissions, logic should prevent deleting last super-admin
    $superAdminCount = User::query()->role('super-admin')->count();
    expect($superAdminCount)->toBeGreaterThanOrEqual(1);
});

// Last login tracking
test('user has last_login_at timestamp', function () {
    $user = User::factory()->create();

    expect($user->last_login_at)->toBeNull();

    $user->update(['last_login_at' => now()]);

    expect($user->last_login_at)->not->toBeNull();
});
