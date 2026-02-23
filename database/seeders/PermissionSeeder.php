<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        Cache::forget('spatie.permission.cache');

        // Admin access
        Permission::firstOrCreate(['name' => 'admin.access', 'guard_name' => 'web']);

        // Users permissions
        $userPermissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.deactivate',
            'users.assign_roles',
            'users.assign_permissions',
        ];
        foreach ($userPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles permissions
        $rolePermissions = [
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.assign_permissions',
        ];
        foreach ($rolePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Permissions permissions
        $permissionsPermissions = [
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
        ];
        foreach ($permissionsPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Mail Accounts permissions
        $mailAccountPermissions = [
            'mail-accounts.viewAny',
            'mail-accounts.view',
            'mail-accounts.create',
            'mail-accounts.update',
            'mail-accounts.delete',
        ];
        foreach ($mailAccountPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // RGPD Contacts permissions
        $contactPermissions = [
            'contacts.viewAny',
            'contacts.view',
            'contacts.create',
            'contacts.update',
            'contacts.delete',
        ];
        foreach ($contactPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
