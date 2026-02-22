# CRUD Development Standard - Setup Checklist

Use this checklist to verify the CRUD implementation is complete and follows the standard.

## A. Documentation ✅

- [x] `.github/skills/crud/SKILL.md` - Full CRUD standard documentation
- [x] `.github/skills/crud/rules/EXAMPLES.md` - Complete Product CRUD example
- [x] `.github/skills/crud/rules/README.md` - Permissions setup guide
- [x] `.github/skills/crud/AGENTS.md` - Quick reference and skill integration

## B. Shared UI Components ✅

Located in: `resources/views/components/crud/`

- [x] `page-header.blade.php` - Standard page header (title, subtitle, actions)
- [x] `filter-toolbar.blade.php` - Filter search and actions toolbar
- [x] `table.blade.php` - Data table with Flux styling
- [x] `empty-state.blade.php` - Empty state when no records

**Usage:** `<x-crud::page-header>`, `<x-crud::filter-toolbar>`, `<x-crud::table>`, `<x-crud::empty-state>`

## C. Livewire Base Traits ✅

Located in: `app/Livewire/Concerns/`

- [x] `WithCrudListing.php` - Pagination, filtering, sorting for Index components
- [x] `WithCrudForm.php` - Form handling for Create/Edit modals
- [x] `WithCrudDelete.php` - Delete confirmation and action

**Usage:**
```php
use App\Livewire\Concerns\WithCrudListing; // For Index
use App\Livewire\Concerns\WithCrudForm;   // For Form
use App\Livewire\Concerns\WithCrudDelete; // For Delete
```

## D. Authorization & Permissions ✅

- [x] `app/Helpers/PermissionHelper.php` - Enhanced with CRUD gate/permission registration
- [x] `database/seeders/Permissions/CrudPermissionSeeder.php` - Base permission seeder
- [x] `database/seeders/Permissions/README.md` - Setup guide

**Usage:**
```php
// In AuthorizationServiceProvider.php
PermissionHelper::registerCrudGates('products');

// In permission seeder
class ProductPermissionSeeder extends CrudPermissionSeeder {
    protected string $resource = 'products';
}
```

## E. Artisan Command ✅

- [x] `app/Console/Commands/MakeCrudCommand.php` - Full CRUD generator command

**Usage:**
```bash
php artisan make:crud Product --all
php artisan make:crud Category --model --factory --policy
```

## F. Code Quality ✅

- [x] Pint configured in `pint.json` (using Laravel preset)
- [x] Tests ready (Pest 4 available)
- [x] Standard enforced via documentation

**Run formatting:**
```bash
composer lint
```

---

## Implementation Status

### Standard Components (Ready to Use)

| Component | File | Status |
|-----------|------|--------|
| Index Listing | Users/Index.php | ✅ Working |
| Create/Edit Modal | Users/Form pattern | ✅ Documented |
| Delete Confirm | Users pattern | ✅ Documented |
| Permissions Seeding | Permissions/PermissionSeeder | ✅ Working |
| Gates Registration | AuthorizationServiceProvider | ✅ Working |
| CRUD Generator | MakeCrudCommand | ✅ Ready |

### Next CRUDs to Create (Examples)

1. **Products** - Basic CRUD with name, description, price, stock
2. **Categories** - Parent/relation example
3. **Orders** - More complex form with line items

Use the EXAMPLES.md file as a template for Products.

---

## How to Create Your First CRUD

### Quick Start (5 minutes)

```bash
# Step 1: Generate all files
php artisan make:crud Product --all

# Step 2: Edit migration to add fields you need
# nano database/migrations/202X_XX_XX_create_products_table.php

# Step 3: Add route to routes/admin.php
Route::get('/products', fn() => view('admin.products.index'))->name('products.index');

# Step 4: Add gate to AuthorizationServiceProvider
PermissionHelper::registerCrudGates('products');

# Step 5: Run migrations and seed permissions
php artisan migrate
php artisan db:seed --class=Permissions/ProductPermissionSeeder

# Step 6: Test
php artisan test tests/Feature/Admin/ProductTest.php --compact
```

### Complete Walkthrough

See `.github/skills/crud/rules/EXAMPLES.md` for a complete Product CRUD with:
- Migration
- Model + Factory + Seeder
- Policy + Gates
- Livewire Components (Index, Form, DeleteConfirm)
- Blade Views
- Routes
- Tests
- Test examples

---

## Verification Checklist for New CRUDs

Before submitting a PR for a new CRUD:

### Structure
- [ ] Model in `app/Models/{Resource}.php`
- [ ] Migration in `database/migrations/`
- [ ] Factory in `database/factories/{Resource}Factory.php`
- [ ] Seeder in `database/seeders/{Resource}Seeder.php`
- [ ] Policy in `app/Policies/{Resource}Policy.php`
- [ ] Permission Seeder in `database/seeders/Permissions/{Resource}PermissionSeeder.php`

### Livewire Components
- [ ] Index in `app/Livewire/Admin/{Resource}/Index.php`
- [ ] Form in `app/Livewire/Admin/{Resource}/Form.php` (if using modal)
- [ ] DeleteConfirm in `app/Livewire/Admin/{Resource}/DeleteConfirm.php`
- [ ] Views in `resources/views/livewire/admin/{kebab-resource}/`

### Authorization
- [ ] Policy has 5 methods: viewAny, view, create, update, delete
- [ ] Gates defined in `AuthorizationServiceProvider.php`
- [ ] Permissions created in seeder (5 permissions)
- [ ] Super-admin bypass works (tested)

### Code Quality
- [ ] Formatted with `composer lint`
- [ ] Passes all tests: `php artisan test --compact`
- [ ] No validation errors

### UI/UX
- [ ] Uses Flux components (no custom HTML forms)
- [ ] Uses shared `x-crud::*` components
- [ ] Empty state implemented
- [ ] Loading states on buttons
- [ ] Flash messages on success/error
- [ ] Responsive (works on mobile)

### Documentation
- [ ] Routes in `routes/admin.php` are commented
- [ ] Permissions documented in resource seeder
- [ ] README if special setup needed

### Tests
Minimum 6 tests:
- [ ] `test_unauthorized_user_cannot_view()` - Authorization
- [ ] `test_{resource}_viewAny_authorized()` - Can view list
- [ ] `test_{resource}_create_authorized()` - Can create
- [ ] `test_{resource}_update_authorized()` - Can update
- [ ] `test_{resource}_delete_authorized()` - Can delete
- [ ] `test_validation_{field}_required()` - Validation

---

## File Structure Overview

```
app/
├── Console/Commands/
│   └── MakeCrudCommand.php ........................... CRUD generator
├── Helpers/
│   └── PermissionHelper.php .......................... Permissions/Gates helper
├── Livewire/
│   ├── Concerns/
│   │   ├── WithCrudListing.php ....................... Index listing trait
│   │   ├── WithCrudForm.php .......................... Create/Edit trait
│   │   └── WithCrudDelete.php ........................ Delete confirmation trait
│   └── Admin/
│       ├── Users/ ................................... ✅ Example CRUD
│       └── {Resource}/ ............................... New CRUDs go here
├── Models/
│   └── {Resource}.php ................................ Models
└── Policies/
    └── {Resource}Policy.php .......................... Authorization policies

database/
├── factories/
│   └── {Resource}Factory.php ......................... Model factories
├── migrations/
│   └── *_create_{resources}_table.php ............... Migrations
└── seeders/
    ├── {Resource}Seeder.php .......................... Data seeders
    └── Permissions/
        ├── CrudPermissionSeeder.php ................. Base class
        ├── {Resource}PermissionSeeder.php ........... Resource permissions
        └── README.md ................................ Setup guide

resources/views/
├── components/crud/ .................................. ✅ Shared CRUD components
│   ├── page-header.blade.php
│   ├── filter-toolbar.blade.php
│   ├── table.blade.php
│   └── empty-state.blade.php
└── livewire/admin/{resource}/ ........................ Component views
    ├── index.blade.php
    ├── form.blade.php
    └── delete-confirm.blade.php

tests/Feature/Admin/
└── {Resource}Test.php ................................ Feature tests

.github/skills/crud/
├── SKILL.md ............................................ ✅ Full standard
├── AGENTS.md ............................................ ✅ Quick ref
└── rules/
    ├── EXAMPLES.md .................................... ✅ Product example
    └── README.md ....................................... Permissions guide
```

---

## Common Questions

**Q: Do I have to use the MakeCrudCommand?**
A: No, you can manually create files. But the command gives a template and saves time.

**Q: Can I customize the structure?**
A: Yes, but only if you follow the naming conventions from SKILL.md. Discuss with team before changing.

**Q: What if I need custom authorization logic?**
A: Extend the Policy with additional methods. Use Policy methods + Gates for consistency.

**Q: How do I add custom fields to the form?**
A: Update the Livewire Form component + validation rules + migration. Components update automatically.

**Q: What about relationships (belongsTo, hasMany)?**
A: Add to Model with proper return types. Eager load in Index component to avoid N+1 queries.

---

## Support

- **Full docs:** `.github/skills/crud/SKILL.md`
- **Example:** `.github/skills/crud/rules/EXAMPLES.md`
- **Existing code:** `app/Livewire/Admin/Users/` (Users CRUD reference)
- **Tests:** `tests/Feature/Admin/` (Test patterns)

Ask your team lead or senior dev for questions on specific implementations.
