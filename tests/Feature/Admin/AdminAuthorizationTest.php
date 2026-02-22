<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

// Setup: Seed database before tests
beforeEach(function () {
    $this->seed(\Database\Seeders\PermissionSeeder::class);
    $this->seed(\Database\Seeders\RoleSeeder::class);
});

// Admin access authorization tests
test('guest cannot access admin panel', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('login');
});

test('user without admin.access permission cannot access admin panel', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatusIn([302, 403]);
});

test('inactive user cannot access admin panel', function () {
    $user = User::factory()->create(['is_active' => false]);
    $user->givePermissionTo('admin.access');

    $response = $this->actingAs($user)->get('/admin');
    $response->assertRedirect('login');
});

test('user with admin.access can access admin panel', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.access');

    $response = $this->actingAs($user)->get('/admin');
    $response->assertSuccessful();
});

test('super admin can access admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $response = $this->actingAs($user)->get('/admin');
    $response->assertSuccessful();
});

// Users management authorization tests
test('user without users.view cannot access users list', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.access');

    $response = $this->actingAs($user)->get('/admin/users');
    $response->assertStatusIn([302, 403]);
});

test('user with users.view can access users list', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'users.view']);

    $response = $this->actingAs($user)->get('/admin/users');
    $response->assertSuccessful();
});

test('user without users.create cannot create users', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'users.view']);

    expect($user->can('users.create'))->toBeFalse();
});

test('user with users.create can create users', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'users.create']);

    expect($user->can('users.create'))->toBeTrue();
});

// Role assignment authorization tests
test('admin can assign roles to users', function () {
    $admin = User::factory()->create();
    $admin->givePermissionTo(['admin.access', 'users.assign_roles']);

    $targetUser = User::factory()->create();

    expect($admin->can('users.assign_roles', $targetUser))->toBeTrue();
});

test('super-admin can assign any role', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $targetUser = User::factory()->create();

    expect($superAdmin->can('users.assign_roles', $targetUser))->toBeTrue();
});

// Roles management authorization tests
test('user without roles.view cannot access roles list', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.access');

    $response = $this->actingAs($user)->get('/admin/roles');
    $response->assertStatusIn([302, 403]);
});

test('user with roles.view can access roles list', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'roles.view']);

    $response = $this->actingAs($user)->get('/admin/roles');
    $response->assertSuccessful();
});

test('user without roles.create cannot create roles', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'roles.view']);

    expect($user->can('roles.create'))->toBeFalse();
});

test('user with roles.create can create roles', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'roles.create']);

    expect($user->can('roles.create'))->toBeTrue();
});

test('user without roles.delete cannot delete roles', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'roles.view']);

    expect($user->can('roles.delete'))->toBeFalse();
});

// Permissions management authorization tests
test('user without permissions.view cannot access permissions list', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('admin.access');

    $response = $this->actingAs($user)->get('/admin/permissions');
    $response->assertStatusIn([302, 403]);
});

test('user with permissions.view can access permissions list', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['admin.access', 'permissions.view']);

    $response = $this->actingAs($user)->get('/admin/permissions');
    $response->assertSuccessful();
});

test('only super-admin can delete critical permissions', function () {
    $adminUser = User::factory()->create();
    $adminUser->givePermissionTo(['admin.access', 'permissions.view', 'permissions.delete']);

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    expect($superAdmin->can('permissions.delete'))->toBeTrue();
});

test('super admin has all permissions', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    expect($user->hasPermissionTo('admin.access'))->toBeTrue();
    expect($user->hasPermissionTo('users.view'))->toBeTrue();
    expect($user->hasPermissionTo('roles.delete'))->toBeTrue();
    expect($user->hasPermissionTo('permissions.delete'))->toBeTrue();
});

test('admin role has specific permissions', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    expect($user->hasPermissionTo('admin.access'))->toBeTrue();
    expect($user->hasPermissionTo('users.view'))->toBeTrue();
    expect($user->hasPermissionTo('roles.delete'))->toBeFalse();
});

test('manager role has limited permissions', function () {
    $user = User::factory()->create();
    $user->assignRole('manager');

    expect($user->hasPermissionTo('admin.access'))->toBeFalse();
    expect($user->hasPermissionTo('users.view'))->toBeTrue();
    expect($user->hasPermissionTo('roles.delete'))->toBeFalse();
});

test('gate before super admin returns true for all abilities', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    expect($user->can('admin.access'))->toBeTrue();
    expect($user->can('users.view'))->toBeTrue();
    expect($user->can('any.random.ability'))->toBeTrue();
});
