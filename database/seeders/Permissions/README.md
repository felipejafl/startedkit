# CRUD Permissions Setup Guide

## Overview

This guide explains how to set up and manage CRUD permissions for new resources in the application.

The permission system uses a combination of:
1. **Spatie Laravel Permission** - For storing permissions in database
2. **Laravel Gates** - For checking permissions in code
3. **Helper classes** - For quick registration

---

## Basic Setup for a New CRUD

### Step 1: Create Permission Seeder

Create a new seeder that extends `CrudPermissionSeeder`:

```php
// database/seeders/Permissions/ProductPermissionSeeder.php

namespace Database\Seeders\Permissions;

use Spatie\Permission\Models\Role;

class ProductPermissionSeeder extends CrudPermissionSeeder
{
    protected string $resource = 'products';

    public function run(): void
    {
        parent::run();

        // Assign to admin role (optional)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($this->getPermissionNames());
    }
}
```

### Step 2: Register Gates

Update `app/Providers/AuthorizationServiceProvider.php`:

```php
public function boot(): void
{
    // Super-admin bypass
    Gate::before(function (User $user) {
        if ($user->hasRole('super-admin')) {
            return true;
        }
    });

    // Register CRUD gates using helper
    PermissionHelper::registerCrudGates('products');
    PermissionHelper::registerCrudGates('categories');
    // ... more resources
}
```

Or manually:

```php
Gate::define('products.viewAny', fn(User $user) => 
    $user->hasPermissionTo('products.viewAny', 'web')
);

Gate::define('products.view', fn(User $user) => 
    $user->hasPermissionTo('products.view', 'web')
);

Gate::define('products.create', fn(User $user) => 
    $user->hasPermissionTo('products.create', 'web')
);

Gate::define('products.update', fn(User $user) => 
    $user->hasPermissionTo('products.update', 'web')
);

Gate::define('products.delete', fn(User $user) => 
    $user->hasPermissionTo('products.delete', 'web')
);
```

### Step 3: Create Policy

Create a policy file:

```php
// app/Policies/ProductPolicy.php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('products.viewAny');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->can('products.view');
    }

    public function create(User $user): bool
    {
        return $user->can('products.create');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('products.update');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('products.delete');
    }
}
```

### Step 4: Run Seeder

```bash
php artisan db:seed --class=Permissions/ProductPermissionSeeder
```

---

## Usage in Code

### In Livewire Components

```php
class ProductIndex extends Component
{
    use AuthorizesRequests;

    public function mount(): void
    {
        $this->authorize('products.viewAny');
    }

    public function create(): void
    {
        $this->authorize('products.create');
        // ...
    }

    public function update(Product $product): void
    {
        $this->authorize('products.update');
        // ...
    }
}
```

### In Blade Views

```blade
@can('products.create')
    <flux:button>Create Product</flux:button>
@endcan

@foreach($products as $product)
    @can('products.update')
        <flux:button>Edit</flux:button>
    @endcan

    @can('products.delete')
        <flux:button color="red">Delete</flux:button>
    @endcan
@endforeach
```

### In Controllers (if needed)

```php
public function index()
{
    $this->authorize('products.viewAny');
    // ...
}

public function create()
{
    $this->authorize('products.create');
    // ...
}
```

---

## Permission Naming Convention

All CRUD permissions follow this pattern:

```
{resource}.{action}
```

**Actions:**
- `viewAny` - See all records (list view)
- `view` - See single record (detail view)
- `create` - Create new record
- `update` - Edit existing record
- `delete` - Delete record

**Examples:**
```
products.viewAny
products.view
products.create
products.update
products.delete

categories.viewAny
categories.view
categories.create
categories.update
categories.delete
```

---

## Role Assignment

Typically, permissions are assigned to roles:

```php
$admin = Role::findByName('admin');
$admin->syncPermissions([
    'products.viewAny',
    'products.view',
    'products.create',
    'products.update',
    'products.delete',
]);

$manager = Role::findByName('manager');
$manager->syncPermissions([
    'products.viewAny',
    'products.view',
]);
```

---

## Helper Functions

The `PermissionHelper` class provides utilities:

```php
use App\Helpers\PermissionHelper;

// Register gates for one resource
PermissionHelper::registerCrudGates('products');

// Register gates for multiple resources
PermissionHelper::registerCrudGatesForMany(['products', 'categories', 'orders']);

// Ensure permissions exist in database
PermissionHelper::ensurePermissionsExist('products');

// Get all permission names for a resource
$permissions = PermissionHelper::getPermissionNames('products');
// Returns: ['products.viewAny', 'products.view', 'products.create', 'products.update', 'products.delete']
```

---

## Super-Admin Bypass

In `AuthorizationServiceProvider`, super-admin users bypass all permission checks:

```php
Gate::before(function (User $user) {
    if ($user->hasRole('super-admin')) {
        return true;  // Allow everything
    }
});
```

This means users with the `super-admin` role can perform any action without specific permissions.

---

## Testing Permissions

When writing tests, grant specific permissions to test users:

```php
$user = User::factory()->create();
$user->givePermissionTo('products.viewAny');
$user->givePermissionTo('products.create');

$this->actingAs($user);
$response = $this->get(route('admin.products.index'));
$response->assertOk();
```

---

## Troubleshooting

### Permissions Not Working?

1. **Ensure permission is created** - Check `permissions` table in database
2. **Ensure user has role** - Check `model_has_roles` table
3. **Ensure role has permission** - Check `role_has_permissions` table
4. **Clear cache** - Run `php artisan cache:clear`
5. **Check gate is registered** - Verify in `AuthorizationServiceProvider.boot()`

### Clear Permission Cache

```bash
php artisan permission:cache-reset
php artisan cache:clear
```

---

## References

- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- [CRUD Development Guide](./SKILL.md)
