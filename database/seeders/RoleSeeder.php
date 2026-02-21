<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        Cache::forget('spatie.permission.cache');

        // Create super-admin role with all permissions
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['name' => 'super-admin']
        );
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);

        // Create admin role with all permissions except deleting critical items
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin']
        );
        $adminPermissions = Permission::whereIn('name', [
            'admin.access',
            'users.view', 'users.create', 'users.update', 'users.deactivate', 'users.assign_roles', 'users.assign_permissions',
            'roles.view', 'roles.create', 'roles.update', 'roles.assign_permissions',
            'permissions.view', 'permissions.create', 'permissions.update',
        ])->get();
        $admin->syncPermissions($adminPermissions);

        // Create manager role (limited)
        $manager = Role::firstOrCreate(
            ['name' => 'manager', 'guard_name' => 'web'],
            ['name' => 'manager']
        );
        $managerPermissions = Permission::whereIn('name', [
            'users.view',
        ])->get();
        $manager->syncPermissions($managerPermissions);
    }
}
