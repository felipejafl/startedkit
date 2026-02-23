# CRUD EXAMPLE: Products

This is a complete, working example of a CRUD following the platform standard.
Use this as a template for creating new CRUDs.

---

## 1. MODEL & DATABASE

### Migration: `database/migrations/2026_02_22_120000_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Audit trail: soft delete for archiving
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Model: `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
```

### Factory: `database/factories/ProductFactory.php`

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->slug(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'stock' => fake()->numberBetween(0, 1000),
        ];
    }

    public function archived(): static
    {
        return $this->state(fn() => [
            'deleted_at' => now(),
        ]);
    }
}
```

### Seeder: `database/seeders/ProductSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(15)->create();
    }
}
```

---

## 2. AUTHORIZATION

### Policy: `app/Policies/ProductPolicy.php`

```php
<?php

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

### Gates: Update `app/Providers/AuthorizationServiceProvider.php`

Add to the `boot()` method:

```php
// Product management gates
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

### Permissions: `database/seeders/Permissions/ProductPermissionSeeder.php`

```php
<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'products.viewAny',
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Assign to admin role
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);
    }
}
```

---

## 3. ROUTES

### File: `routes/admin.php` (or dedicated `routes/products.php`)

```php
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // Products Management
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');

    Route::get('/products/{product}', function () {
        return view('admin.products.show');
    })->name('products.show');
});
```

> **Note:** If using separate `routes/products.php`, include it in `routes/admin.php`:
> ```php
> Route::prefix('products')->name('products.')->group(base_path('routes/products.php'));
> ```

---

## 4. LIVEWIRE COMPONENTS

### Index Component: `app/Livewire/Admin/Products/Index.php`

```php
<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    public bool $showForm = false;

    public bool $showDelete = false;

    public ?Product $editingProduct = null;

    public ?Product $deletingProduct = null;

    protected $queryString = ['search'];

    public function mount(): void
    {
        $this->authorize('products.viewAny');
    }

    public function openCreateForm(): void
    {
        $this->authorize('products.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(Product $product): void
    {
        $this->authorize('products.update');
        $this->editingProduct = $product;
        $this->showForm = true;
    }

    public function openDeleteForm(Product $product): void
    {
        $this->authorize('products.delete');
        $this->deletingProduct = $product;
        $this->showDelete = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showDelete = false;
        $this->resetForm();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        }

        // Soft delete: automatically excludes deleted_at != null
        // Use ->withTrashed() or ->onlyTrashed() if needed for admin views

        $products = $query->latest()->paginate(15);

        return view('livewire.admin.products.index', [
            'products' => $products,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingProduct = null;
    }
}
```

### Form Component: `app/Livewire/Admin/Products/Form.php`

```php
<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Form extends Component
{
    use AuthorizesRequests;

    public bool $show = false;

    public ?Product $product = null;

    public string $formName = '';

    public string $formDescription = '';

    public string $formPrice = '';

    public string $formStock = '';

    protected $listeners = ['openForm' => 'open'];

    protected function rules(): array
    {
        return [
            'formName' => ['required', 'string', 'max:255'],
            'formDescription' => ['nullable', 'string', 'max:1000'],
            'formPrice' => ['required', 'numeric', 'min:0.01'],
            'formStock' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function messages(): array
    {
        return [
            'formName.required' => 'Product name is required.',
            'formPrice.required' => 'Price is required.',
            'formPrice.numeric' => 'Price must be a valid number.',
            'formStock.required' => 'Stock quantity is required.',
        ];
    }

    public function open(Product $product = null): void
    {
        if ($product) {
            $this->authorize('products.update');
            $this->product = $product;
            $this->formName = $product->name;
            $this->formDescription = $product->description ?? '';
            $this->formPrice = (string) $product->price;
            $this->formStock = (string) $product->stock;
        } else {
            $this->authorize('products.create');
        }

        $this->show = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->product) {
            $this->authorize('products.update');
            $this->product->update([
                'name' => $this->formName,
                'description' => $this->formDescription ?: null,
                'price' => (float) $this->formPrice,
                'stock' => (int) $this->formStock,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Product updated successfully!');
        } else {
            $this->authorize('products.create');
            Product::create([
                'name' => $this->formName,
                'slug' => str($this->formName)->slug(),
                'description' => $this->formDescription ?: null,
                'price' => (float) $this->formPrice,
                'stock' => (int) $this->formStock,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Product created successfully!');
        }

        $this->dispatch('productSaved');
        $this->close();
    }

    public function close(): void
    {
        $this->show = false;
        $this->reset(['product', 'formName', 'formDescription', 'formPrice', 'formStock']);
    }

    public function render()
    {
        return view('livewire.admin.products.form');
    }
}
```

### DeleteConfirm Component: `app/Livewire/Admin/Products/DeleteConfirm.php`

```php
<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DeleteConfirm extends Component
{
    use AuthorizesRequests;

    public bool $show = false;

    public ?Product $product = null;

    protected $listeners = ['openDelete' => 'open'];

    public function open(Product $product): void
    {
        $this->authorize('products.delete');
        $this->product = $product;
        $this->show = true;
    }

    public function delete(): void
    {
        if (! $this->product) {
            return;
        }

        $this->authorize('products.delete');
        $this->product->delete();
        $this->dispatch('flash', type: 'success', message: 'Product deleted successfully!');
        $this->dispatch('productDeleted');
        $this->close();
    }

    public function close(): void
    {
        $this->show = false;
        $this->product = null;
    }

    public function render()
    {
        return view('livewire.admin.products.delete-confirm');
    }
}
```

---

## 5. BLADE VIEWS

### Index View: `resources/views/livewire/admin/products/index.blade.php`

```blade
<div class="space-y-6">
    <!-- Page Header -->
    <x-crud::page-header>
        <x-slot:title>Products</x-slot:title>
        <x-slot:subtitle>Manage your product catalog</x-slot:subtitle>
        <x-slot:action>
            @can('products.create')
                <flux:button 
                    wire:click="openCreateForm" 
                    variant="primary"
                >
                    Create Product
                </flux:button>
            @endcan
        </x-slot:action>
    </x-crud::page-header>

    <!-- Filter Toolbar -->
    <x-crud::filter-toolbar>
        <flux:input 
            wire:model.live.debounce-500ms="search"
            type="search"
            placeholder="Search products..."
            icon="magnifying-glass"
        />

        @if($search)
            <flux:button 
                wire:click="resetFilters"
                variant="ghost"
            >
                Reset Filters
            </flux:button>
        @endif
    </x-crud::filter-toolbar>

    <!-- Products Table -->
    @forelse($products as $product)
        <div class="space-y-2">
            <x-crud::table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="font-medium">{{ $product->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <div class="flex gap-2">
                                    @can('products.update')
                                        <flux:button 
                                            wire:click="openEditForm({{ $product->id }})"
                                            size="sm"
                                            variant="ghost"
                                        >
                                            Edit
                                        </flux:button>
                                    @endcan

                                    @can('products.delete')
                                        <flux:button 
                                            wire:click="openDeleteForm({{ $product->id }})"
                                            size="sm"
                                            variant="ghost"
                                            color="red"
                                        >
                                            Delete
                                        </flux:button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-crud::table>
        </div>
    @empty
        <x-crud::empty-state>
            <x-slot:title>No products found</x-slot:title>
            <x-slot:description>
                @if($search || $filterStatus)
                    Try adjusting your search or filters
                @else
                    Get started by creating your first product
                @endif
            </x-slot:description>
            @can('products.create')
                <flux:button 
                    wire:click="openCreateForm"
                    variant="primary"
                >
                    Create Product
                </flux:button>
            @endcan
        </x-crud::empty-state>
    @endforelse

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links('pagination::flux') }}
    </div>

    <!-- Modals -->
    <livewire:admin.products.form />
    <livewire:admin.products.delete-confirm />
</div>
```

### Form Modal: `resources/views/livewire/admin/products/form.blade.php`

```blade
<flux:modal 
    name="product-form"
    :show="$show"
    @close="close"
>
    <div class="space-y-4">
        <flux:heading level="2">
            {{ $product ? 'Edit Product' : 'Create Product' }}
        </flux:heading>

        <form wire:submit="save" class="space-y-4">
            <flux:input 
                wire:model="formName"
                label="Product Name"
                placeholder="Enter product name"
                error="{{ $errors->first('formName') }}"
                required
            />

            <flux:textarea 
                wire:model="formDescription"
                label="Description"
                placeholder="Product description (optional)"
                error="{{ $errors->first('formDescription') }}"
            />

            <flux:input 
                wire:model="formPrice"
                label="Price"
                type="number"
                step="0.01"
                placeholder="0.00"
                error="{{ $errors->first('formPrice') }}"
                required
            />

            <flux:input 
                wire:model="formStock"
                label="Stock Quantity"
                type="number"
                placeholder="0"
                error="{{ $errors->first('formStock') }}"
                required
            />

            <flux:checkbox 
                wire:model="formIsActive"
                label="Active"
            />

            <div class="flex gap-2 pt-4">
                <flux:button type="submit" variant="primary">
                    {{ $product ? 'Update' : 'Create' }}
                </flux:button>
                <flux:button wire:click="close" variant="ghost">
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>
```

### Delete Confirm Modal: `resources/views/livewire/admin/products/delete-confirm.blade.php`

```blade
<flux:modal 
    name="product-delete"
    :show="$show"
    @close="close"
>
    <div class="space-y-4">
        <flux:heading level="2" color="red">
            Delete Product
        </flux:heading>

        <flux:text>
            Are you sure you want to delete <strong>{{ $product?->name }}</strong>?
            This action cannot be undone.
        </flux:text>

        <div class="flex gap-2 pt-4">
            <flux:button 
                wire:click="delete" 
                variant="danger"
                wire:loading.attr="disabled"
            >
                Delete Product
            </flux:button>
            <flux:button wire:click="close" variant="ghost">
                Cancel
            </flux:button>
        </div>
    </div>
</flux:modal>
```

---

## 6. TESTS

### Feature Test: `tests/Feature/Admin/ProductsTest.php`

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
        $this->grantPermissionsToAdmin();
    }

    private function grantPermissionsToAdmin(): void
    {
        $this->adminUser->givePermissionTo([
            'products.viewAny',
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
        ]);
    }

    // Authorization Tests

    public function test_unauthorized_user_cannot_view_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.products.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_view_products()
    {
        Product::factory(5)->create();
        $this->actingAs($this->adminUser);

        $response = $this->get(route('admin.products.index'));
        $response->assertOk();
        $response->assertViewHas('products');
    }

    // Create Tests

    public function test_admin_can_create_product()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
        ]);
    }

    public function test_validation_required_name()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post(route('admin.products.store'), [
            'price' => 99.99,
            'stock' => 10,
        ]);

        $response->assertSessionHasErrors('name');
    }

    // Update Tests

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create();
        $this->actingAs($this->adminUser);

        $response = $this->patch(route('admin.products.update', $product), [
            'name' => 'Updated Product',
            'price' => 149.99,
            'stock' => 20,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
        ]);
    }

    // Delete Tests

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();
        $this->actingAs($this->adminUser);

        $response = $this->delete(route('admin.products.destroy', $product));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_user_without_delete_permission_cannot_delete()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $user->givePermissionTo(['products.viewAny']);

        $this->actingAs($user);
        $response = $this->delete(route('admin.products.destroy', $product));
        $response->assertForbidden();

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}
```

---

## 7. QUICK SETUP

To create this Products CRUD from scratch:

```bash
# 1. Create model with factory and migration
php artisan make:model Product -mf

# 2. Create policy
php artisan make:policy ProductPolicy

# 3. Create Livewire components
php artisan make:livewire admin.products.index
php artisan make:livewire admin.products.form
php artisan make:livewire admin.products.delete-confirm

# 4. Create seeder
php artisan make:seeder ProductSeeder

# 5. Update migration with columns (from Migration section above)

# 6. Create test
php artisan make:test Admin/ProductsTest

# 7. Add routes to routes/admin.php (from Routes section above)

# 8. Add gates to AuthorizationServiceProvider (from Permissions section)

# 9. Create and run permission seeder

# 10. Format code
composer lint

# 11. Run tests
php artisan test --compact

# 12. Run migrations
php artisan migrate

# 13. Seed permissions
php artisan db:seed --class=Permissions/ProductPermissionSeeder
```

---

## References

- Full documentation: [SKILL.md](../../SKILL.md)
- Existing Users CRUD: `app/Livewire/Admin/Users/`
- Flux UI Docs: https://flux.laravel.com
- Livewire Docs: https://livewire.laravel.com
- Laravel Policies: https://laravel.com/docs/policies
