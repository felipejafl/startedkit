<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Super-admin bypass: allow all actions for users with super-admin role
        Gate::before(function (User $user) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });

        // Define admin access gate
        Gate::define('admin.access', function (User $user) {
            return $user->hasPermissionTo('admin.access', 'web');
        });

        // User management gates
        Gate::define('users.view', function (User $user) {
            return $user->hasPermissionTo('users.view', 'web');
        });

        Gate::define('users.create', function (User $user) {
            return $user->hasPermissionTo('users.create', 'web');
        });

        Gate::define('users.update', function (User $user, User $targetUser) {
            return $user->hasPermissionTo('users.update', 'web');
        });

        Gate::define('users.deactivate', function (User $user, User $targetUser) {
            return $user->hasPermissionTo('users.deactivate', 'web');
        });

        Gate::define('users.assign_roles', function (User $user, User $targetUser) {
            // Prevent users from assigning roles they don't have
            if (! $user->hasPermissionTo('users.assign_roles', 'web')) {
                return false;
            }

            // Prevent assigning super-admin to users who aren't super-admin
            $targetRoles = request()->input('roles', []);
            if (in_array('super-admin', $targetRoles) && ! $user->hasRole('super-admin')) {
                return false;
            }

            return true;
        });

        Gate::define('users.assign_permissions', function (User $user, User $targetUser) {
            return $user->hasPermissionTo('users.assign_permissions', 'web');
        });

        // Role management gates
        Gate::define('roles.view', function (User $user) {
            return $user->hasPermissionTo('roles.view', 'web');
        });

        Gate::define('roles.create', function (User $user) {
            return $user->hasPermissionTo('roles.create', 'web');
        });

        Gate::define('roles.update', function (User $user) {
            return $user->hasPermissionTo('roles.update', 'web');
        });

        Gate::define('roles.delete', function (User $user) {
            return $user->hasPermissionTo('roles.delete', 'web');
        });

        Gate::define('roles.assign_permissions', function (User $user) {
            return $user->hasPermissionTo('roles.assign_permissions', 'web');
        });

        // Permission management gates
        Gate::define('permissions.view', function (User $user) {
            return $user->hasPermissionTo('permissions.view', 'web');
        });

        Gate::define('permissions.create', function (User $user) {
            return $user->hasPermissionTo('permissions.create', 'web');
        });

        Gate::define('permissions.update', function (User $user) {
            return $user->hasPermissionTo('permissions.update', 'web');
        });

        Gate::define('permissions.delete', function (User $user) {
            return $user->hasPermissionTo('permissions.delete', 'web');
        });
    }
}
