# CRUD Development Standard

**Purpose:** Unified approach to building CRUDs in Laravel 12 + Livewire 4 + Flux UI.  
Every CRUD must follow this standard to ensure consistency in architecture, naming, validation, authorization, and UI.

---

## 1. NAMING CONVENTIONS

### Models & Files
- **Model:** PascalCase (`Product`, `Category`, `Order`)
- **Migration:** snake_case with timestamp (`2026_02_22_120000_create_products_table.php`)
- **Factory:** PascalCase + Factory (`ProductFactory`)
- **Policy:** PascalCase + Policy (`ProductPolicy`)

### Routes & URLs
- **Route path:** kebab-case (`/products`, `/product-categories`)
- **Route name:** camelCase (`products.index`, `products.create`)
- **Route parameters:** camelCase (`{productId}`)

### Permissions & Gates
- **Format:** `{resource}.{action}` in snake_case
  - `products.viewAny` - list all
  - `products.view` - view single
  - `products.create` - create new
  - `products.update` - update existing
  - `products.delete` - delete
- **Guard:** Always 'web'

### Livewire Components
- **Namespace:** `App\Livewire\{Module}\{Resource}`
- **Class naming:** PascalCase (`Index`, `Form`, `DeleteConfirm`)
- **View path:** `livewire.{kebab-module}.{kebab-resource}.{view-name}`

Example for Product in Admin module:
```php
// Component
App\Livewire\Admin\Products\Index
App\Livewire\Admin\Products\Form
App\Livewire\Admin\Products\DeleteConfirm

// View paths
resources/views/livewire/admin/products/index.blade.php
resources/views/livewire/admin/products/form.blade.php
resources/views/livewire/admin/products/delete-confirm.blade.php
```

---

## 2. FOLDER STRUCTURE

```
app/
├── Livewire/
│   └── {Module}/
│       └── {Resource}/
│           ├── Index.php           (listing + filters)
│           ├── Form.php            (create/edit modal)
│           └── DeleteConfirm.php   (delete confirmation modal)
├── Models/
│   └── {Resource}.php
├── Policies/
│   └── {Resource}Policy.php
└── Actions/
    └── {Module}/
        ├── Create{Resource}Action.php  (optional, for complex logic)
        └── Update{Resource}Action.php

database/
├── migrations/
│   └── {timestamp}_create_{resources}_table.php
├── factories/
│   └── {Resource}Factory.php
└── seeders/
    └── {Resource}Seeder.php

resources/views/
├── livewire/
│   └── {kebab-module}/
│       └── {kebab-resource}/
│           ├── index.blade.php
│           ├── form.blade.php
│           └── delete-confirm.blade.php
└── components/
    └── {kebab-module}/
        └── shared/
            ├── page-header.blade.php
            └── ...

routes/
└── {kebab-module}.php

tests/Feature/
└── {Module}/
    └── {Resource}Test.php
```

---

## 3. STANDARD CRUD FLOW

### Index Component (Listing)
1. **Initialize:** Load records with filters from query string
2. **Filter:** Search, sort, status, date range (all query string bound)
3. **Display:** Table with Flux components
4. **Actions per row:** Edit modal, Delete confirm modal, View detail
5. **Header:** Title, Create button, Reset filters link
6. **Empty state:** When no records, show "No products found"

### Form Component (Create/Edit Modal)
1. **Initialize:** Reset form on create, pre-fill on edit
2. **Validate:** Rules defined in `rules()` method
3. **Save:** Create new or update existing
4. **Feedback:** Dispatch flash event on success
5. **Close:** Modal closes after success
6. **Errors:** Display validation errors in-line

### DeleteConfirm Component (Delete Modal)
1. **Confirm:** Show resource name and confirm message
2. **Delete:** Call delete action
3. **Protect:** Check authorization first
4. **Feedback:** Success/error toast
5. **Close:** Close modal after deletion

---

## 4. UI STANDARDS (Flux Components)

### Page Header
- Title, subtitle, actions button (Create)
- Component: `<x-crud::page-header>`
- Usage: Always at top of index view

### Table/Listado
- Columns with headers
- Sorting (if applicable)
- Skeleton loading state
- Selection checkboxes (optional)
- Actions menu (Edit, Delete) per row
- Pagination footer
- Component: `<x-crud::table>`

### Toolbar de Filtros
- Search input with debounce
- Select filters (status, category, etc.)
- Date range picker (if applicable)
- Reset button
- Component: `<x-crud::filter-toolbar>`

### Empty State
- Icon, title, subtitle
- Create button
- Component: `<x-crud::empty-state>`

### Modal (Create/Edit)
- Title: "Create Product" or "Edit Product"
- Form fields with labels and errors
- Submit button (disabled while loading)
- Close button
- Component: `<flux:modal>`

### Modal (Delete Confirm)
- Title: "Delete Product"
- Message with resource name
- Danger delete button
- Cancel button
- Component: `<flux:modal>`

### Toast/Flash Feedback
- Success: Green badge + message
- Error: Red badge + message
- Dispatch via Livewire: `$this->dispatch('flash', type: 'success', message: 'Created!')`

---

## 5. VALIDATION RULES

### Standard
```php
protected function rules(): array
{
    return [
        'formField' => ['required', 'string', 'max:255'],
        'formEmail' => ['required', 'email', 'unique:table,column'],
        'formPrice' => ['required', 'numeric', 'min:0.01'],
    ];
}
```

### Messages
```php
protected function messages(): array
{
    return [
        'formField.required' => 'Field name is required.',
        'formEmail.unique' => 'This email is already in use.',
    ];
}
```

### Implementation
- Define in component or Form Request
- Call `$this->validate()` in save method
- Catch ValidationException for error handling
- Display errors via `$errors` in Blade

---

## 6. AUTHORIZATION STANDARDS

### Policy Pattern
Every CRUD must have a Policy in `app/Policies/{Resource}Policy.php`:

```php
namespace App\Policies;

use App\Models\User;
use App\Models\{Resource};

class {Resource}Policy
{
    public function viewAny(User $user): bool
    {
        return $user->can('{resource}.viewAny');
    }

    public function view(User $user, {Resource} ${resource}): bool
    {
        return $user->can('{resource}.view');
    }

    public function create(User $user): bool
    {
        return $user->can('{resource}.create');
    }

    public function update(User $user, {Resource} ${resource}): bool
    {
        return $user->can('{resource}.update');
    }

    public function delete(User $user, {Resource} ${resource}): bool
    {
        return $user->can('{resource}.delete');
    }
}
```

### Gates (in AuthorizationServiceProvider)
```php
// Define gates for each permission
Gate::define('{resource}.viewAny', fn(User $user) => 
    $user->hasPermissionTo('{resource}.viewAny', 'web')
);

Gate::define('{resource}.create', fn(User $user) => 
    $user->hasPermissionTo('{resource}.create', 'web')
);
// ... etc for view, update, delete
```

### Usage in Livewire
```php
// Check authorization
$this->authorize('{resource}.viewAny');

// Protect action
$this->authorize('{resource}.create');
```

### Super-Admin Bypass
Always add to `AuthorizationServiceProvider` boot:
```php
Gate::before(function (User $user) {
    if ($user->hasRole('super-admin')) {
        return true;
    }
});
```

### Spatie Permissions Seeding
When creating a CRUD, register permissions in seeder:
```php
// In CrudPermissionsSeeder or dedicated seeder
Permission::create(['name' => '{resource}.viewAny', 'guard_name' => 'web']);
Permission::create(['name' => '{resource}.view', 'guard_name' => 'web']);
Permission::create(['name' => '{resource}.create', 'guard_name' => 'web']);
Permission::create(['name' => '{resource}.update', 'guard_name' => 'web']);
Permission::create(['name' => '{resource}.delete', 'guard_name' => 'web']);

// Assign to role (e.g., admin)
$admin = Role::findByName('admin');
$admin->syncPermissions([
    '{resource}.viewAny',
    '{resource}.view',
    '{resource}.create',
    '{resource}.update',
    '{resource}.delete',
]);
```

---

## 7. CHECKLIST BEFORE PR

- [ ] Model created with factory and seeder
- [ ] Migration files created and runnable
- [ ] Policy created with all 5 methods (viewAny, view, create, update, delete)
- [ ] Gates defined in AuthorizationServiceProvider
- [ ] Permissions created and seeded (5 per resource)
- [ ] Routes defined in appropriate `routes/{module}.php`
- [ ] Routes follow naming convention: `{resource}.{action}`
- [ ] Index component:
  - [ ] Uses WithPagination
  - [ ] Uses AuthorizesRequests
  - [ ] Has query string filters
  - [ ] Calls $this->authorize() for sensitive actions
  - [ ] Dispatches flash events on success/error
- [ ] Form component:
  - [ ] Has rules() and messages() methods
  - [ ] Validates before save
  - [ ] Handles create vs edit
  - [ ] Closes modal on success
  - [ ] Shows validation errors
- [ ] DeleteConfirm component:
  - [ ] Authorizes delete before action
  - [ ] Shows resource name in confirm message
  - [ ] Dispatches flash on success
- [ ] Blade views:
  - [ ] Use Flux components only
  - [ ] Consistent spacing and padding
  - [ ] Empty states implemented
  - [ ] Loading states on buttons
  - [ ] Error messages displayed
  - [ ] Accessibility attributes (aria-label, etc.)
- [ ] Tests (minimum):
  - [ ] 1 test for viewAny authorization
  - [ ] 1 test for create authorization
  - [ ] 1 test for update authorization
  - [ ] 1 test for delete authorization
  - [ ] 1 test for validation (required field)
  - [ ] 1 test for index render
- [ ] Code formatted with `composer lint` (Pint)
- [ ] All tests pass: `php artisan test --compact`
- [ ] No console errors/warnings in browser
- [ ] UI matches Flux design system

---

## 8. SOFT DELETE PATTERN

Soft deletes provide audit trails by archiving records instead of permanently deleting them.

### Model Setup
```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime', // Required for soft delete casting
        ];
    }
}
```

### Migration Setup
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name')->index();
    // ... other fields
    $table->timestamps();
    $table->softDeletes(); // Adds deleted_at column, nullable timestamp
});
```

### Query Behavior
```php
// Automatically excludes soft-deleted records
Product::all(); // Only returns non-deleted records

// Include soft-deleted records
Product::withTrashed()->get();

// Only soft-deleted records
Product::onlyTrashed()->get();

// Restore a soft-deleted record
$product = Product::withTrashed()->find($id);
$product->restore(); // Sets deleted_at = null

// Permanently delete (hard delete)
$product->forceDelete();
```

### Delete Permission Behavior
The `{resource}.delete` gate/policy should:
1. Show "Delete" button in UI only if user has permission
2. Use `$product->delete()` which sets `deleted_at = now()` (soft delete)
3. Listing queries automatically hide soft-deleted records
4. Optionally show "Archived" or "Deleted At" column with restore button for admins

---

## 9. SIDEBAR INTEGRATION

Dynamically show/hide menu items based on permissions using `@can`.

### Sidebar Component
```blade
{{-- resources/views/components/sidebar.blade.php --}}
<x-sidebar>
    @can('products.viewAny')
        <x-nav-link 
            href="{{ route('products.index') }}"
            :active="request()->routeIs('products.*')"
        >
            Products
        </x-nav-link>
    @endcan

    @can('categories.viewAny')
        <x-nav-link 
            href="{{ route('categories.index') }}"
            :active="request()->routeIs('categories.*')"
        >
            Categories
        </x-nav-link>
    @endcan
    
    {{-- More menu items --}}
</x-sidebar>
```

### Nav Link Component
```blade
{{-- resources/views/components/nav-link.blade.php --}}
@props(['href' => '#', 'active' => false])

<a 
    href="{{ $href }}"
    {{ $attributes->class([
        'block px-4 py-2 rounded-lg text-sm font-medium transition-colors',
        'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100' => $active,
        'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800' => !$active,
    ]) }}
>
    {{ $slot }}
</a>
```

### Action Button Visibility
Show action buttons only if user has permission:
```blade
<div class="flex gap-2">
    @can('products.update')
        <flux:button wire:click="openEditForm({{ $product->id }})" size="sm">
            Edit
        </flux:button>
    @endcan

    @can('products.delete')
        <flux:button 
            wire:click="openDeleteForm({{ $product->id }})" 
            size="sm" 
            variant="danger"
        >
            Delete
        </flux:button>
    @endcan
</div>
```

---

## 10. HELPFUL COMMANDS

```bash
# Create model with factory and migration
php artisan make:model Product -mf

# Create policy
php artisan make:policy ProductPolicy

# Create Livewire component
php artisan make:livewire admin.products.index
php artisan make:livewire admin.products.form
php artisan make:livewire admin.products.delete-confirm

# Create seeder
php artisan make:seeder ProductSeeder

# Format code
composer lint

# Run tests
php artisan test --compact
php artisan test --compact --filter=ProductTest

# Create migration
php artisan make:migration create_products_table

# Register permissions (via seeder)
php artisan db:seed --class=CrudPermissionsSeeder
```

---

## REFERENCES

- See [EXAMPLES.md](./rules/EXAMPLES.md) for a complete working example (Product resource)
- See `app/Livewire/Admin/Users/` for existing User CRUD pattern
- See `app/Providers/AuthorizationServiceProvider.php` for gate definitions
