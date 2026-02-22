<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

/**
 * Permission helper for CRUD authorization.
 * Handles permission registration, gate definition, and labeling.
 */
class PermissionHelper
{
    /**
     * Register standard CRUD gates for a resource.
     *
     * @param  string  $resource  Resource name (e.g., 'products')
     * @param  string  $guard  Guard name (default 'web')
     */
    public static function registerCrudGates(string $resource, string $guard = 'web'): void
    {
        $actions = ['viewAny', 'view', 'create', 'update', 'delete'];

        foreach ($actions as $action) {
            $gateName = "{$resource}.{$action}";

            Gate::define($gateName, function (User $user) use ($resource, $action, $guard) {
                return $user->hasPermissionTo("{$resource}.{$action}", $guard);
            });
        }
    }

    /**
     * Register multiple resources at once.
     *
     * @param  array<string>  $resources  Array of resource names
     * @param  string  $guard  Guard name (default 'web')
     */
    public static function registerCrudGatesForMany(array $resources, string $guard = 'web'): void
    {
        foreach ($resources as $resource) {
            static::registerCrudGates($resource, $guard);
        }
    }

    /**
     * Ensure CRUD permissions exist for a resource.
     *
     * @param  string  $resource  Resource name (e.g., 'products')
     * @param  string  $guard  Guard name (default 'web')
     */
    public static function ensurePermissionsExist(string $resource, string $guard = 'web'): void
    {
        $actions = ['viewAny', 'view', 'create', 'update', 'delete'];

        foreach ($actions as $action) {
            $name = "{$resource}.{$action}";

            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard],
                ['name' => $name, 'guard_name' => $guard]
            );
        }
    }

    /**
     * Get all CRUD permission names for a resource.
     *
     * @param  string  $resource  Resource name
     * @return array<int, string>
     */
    public static function getPermissionNames(string $resource): array
    {
        return [
            "{$resource}.viewAny",
            "{$resource}.view",
            "{$resource}.create",
            "{$resource}.update",
            "{$resource}.delete",
        ];
    }

    /**
     * Get all grouped permissions.
     * Used for displaying permissions in UI.
     *
     * @return array<string, array<string, string>>
     */
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

    /**
     * Get human-readable label for a permission.
     */
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

    /**
     * Get group name for a permission.
     */
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
