<?php

namespace App\Helpers;

/**
 * Permission groups and human-readable names.
 */
class PermissionHelper
{
    public static function getGroupedPermissions(): array
    {
        return [
            'Admin' => [
                'admin.access' => 'Admin Panel Access',
            ],
            'Users' => [
                'users.view' => 'View Users',
                'users.create' => 'Create Users',
                'users.update' => 'Update Users',
                'users.deactivate' => 'Deactivate Users',
                'users.assign_roles' => 'Assign Roles to Users',
                'users.assign_permissions' => 'Assign Direct Permissions to Users',
            ],
            'Roles' => [
                'roles.view' => 'View Roles',
                'roles.create' => 'Create Roles',
                'roles.update' => 'Update Roles',
                'roles.delete' => 'Delete Roles',
                'roles.assign_permissions' => 'Assign Permissions to Roles',
            ],
            'Permissions' => [
                'permissions.view' => 'View Permissions',
                'permissions.create' => 'Create Permissions',
                'permissions.update' => 'Update Permissions',
                'permissions.delete' => 'Delete Permissions',
            ],
        ];
    }

    public static function getPermissionLabel(string $permission): string
    {
        $grouped = self::getGroupedPermissions();
        foreach ($grouped as $group => $permissions) {
            if (isset($permissions[$permission])) {
                return $permissions[$permission];
            }
        }

        return str_replace('_', ' ', $permission);
    }

    public static function getPermissionGroup(string $permission): string
    {
        $grouped = self::getGroupedPermissions();
        foreach ($grouped as $group => $permissions) {
            if (isset($permissions[$permission])) {
                return $group;
            }
        }

        return 'Other';
    }
}
