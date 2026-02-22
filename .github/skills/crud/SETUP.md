# CRUD Skill Setup - Instructions

This document explains how to integrate the CRUD skill into your Copilot agents.

## What is Included

A complete, production-ready CRUD development standard for Laravel 12 + Livewire 4 + Flux UI with:

1. **Full documentation** - Conventions, architecture, naming, patterns
2. **Reusable components** - Shared Blade/Flux components
3. **Base traits** - Livewire listing, form, delete traits
4. **Permission system** - Helpers + base seeders for RBAC
5. **Artisan command** - `php artisan make:crud` generator
6. **Complete example** - Working Product CRUD
7. **Tests** - Pest test patterns and examples

## Files Created

### Documentation
- `.github/skills/crud/SKILL.md` - Full standard (700+ lines)
- `.github/skills/crud/rules/EXAMPLES.md` - Complete Product example
- `.github/skills/crud/rules/README.md` - Permissions setup guide
- `.github/skills/crud/AGENTS.md` - Quick reference

### Components
- `resources/views/components/crud/page-header.blade.php`
- `resources/views/components/crud/filter-toolbar.blade.php`
- `resources/views/components/crud/table.blade.php`
- `resources/views/components/crud/empty-state.blade.php`

### Livewire Base Classes
- `app/Livewire/Concerns/WithCrudListing.php` - Pagination, filtering
- `app/Livewire/Concerns/WithCrudForm.php` - Form handling
- `app/Livewire/Concerns/WithCrudDelete.php` - Delete confirmation

### Authorization
- `app/Helpers/PermissionHelper.php` - Enhanced with CRUD gates/permissions
- `database/seeders/Permissions/CrudPermissionSeeder.php` - Base seeder

### Generator
- `app/Console/Commands/MakeCrudCommand.php` - Full CRUD generator

### Checklists & References
- `CRUD_SETUP_CHECKLIST.md` - Verification checklist

## How to Use

### As a Developer

#### Create a new CRUD (fastest):
```bash
php artisan make:crud Product --all
```

Then follow the printed next steps.

#### Or manually:

Read `.github/skills/crud/SKILL.md` for full standard, then follow `.github/skills/crud/rules/EXAMPLES.md` as a template.

### As an AI Agent / Copilot

When a user asks to:
- "Create a CRUD for..."
- "Build admin feature for..."
- "Make a resource with permissions..."
- "Set up {resource} management..."

References:
1. **SKILL.md** - Full specification of expectations
2. **EXAMPLES.md** - Shows complete implementation
3. **Existing Users CRUD** - `app/Livewire/Admin/Users/` for patterns
4. **Traits** - `WithCrudListing`, `WithCrudForm`, `WithCrudDelete`
5. **PermissionHelper** - For authorization setup

## Key Standards Enforced

✅ **Naming**: kebab-case routes, snake_case permissions, PascalCase components
✅ **UI**: Flux components only (no Bootstrap or custom CSS)
✅ **Authorization**: Policy + Gates + Spatie Permissions
✅ **Validation**: Centralized rules() and messages() methods
✅ **Structure**: Index/Form/Delete components, consistent folder layout
✅ **Testing**: Minimum authorization + validation + render tests
✅ **Quality**: Pint formatted, no warnings

## Integration with Project

The CRUD standard is fully compatible with:
- ✅ Existing Users/Roles/Permissions admin module
- ✅ Fortify authentication system
- ✅ Spatie permission library
- ✅ Livewire 4 + Flux UI
- ✅ Pest testing framework
- ✅ Pint code formatting

## Quick Reference: Create a CRUD

### Command (Fastest)
```bash
php artisan make:crud Product --all
# Updates migration
# Updates routes
# Updates AuthorizationServiceProvider
# Creates permission seeder
# Run: php artisan migrate && php artisan db:seed
```

### Manual Steps (What the command does)

1. Create Model: `app/Models/Product.php`
2. Create Migration: `database/migrations/*_create_products_table.php`
3. Create Factory: `database/factories/ProductFactory.php`
4. Create Seeder: `database/seeders/ProductSeeder.php`
5. Create Policy: `app/Policies/ProductPolicy.php`
6. Create Livewire components in: `app/Livewire/Admin/Products/`
   - Index.php (uses WithCrudListing)
   - Form.php (uses WithCrudForm)
   - DeleteConfirm.php (uses WithCrudDelete)
7. Create views in: `resources/views/livewire/admin/products/`
8. Add routes to: `routes/admin.php`
9. Register gates in: `app/Providers/AuthorizationServiceProvider.php`
10. Create permission seeder: `database/seeders/Permissions/ProductPermissionSeeder.php`
11. Create tests: `tests/Feature/Admin/ProductTest.php`

All done! Then run:
```bash
php artisan migrate
php artisan db:seed --class=Permissions/ProductPermissionSeeder
php artisan test --compact
```

## Implementation Notes

- The `MakeCrudCommand` is a generator - it creates templates, not production code
- Always customize the generated migration with your actual fields
- The command creates a minimal feature test - expand it for your needs
- Review `.github/skills/crud/rules/EXAMPLES.md` for a complete, production-ready example

## Support/Questions

- How do I...? → See `.github/skills/crud/SKILL.md`
- Can I...? → Check CRUD_SETUP_CHECKLIST.md verification section
- Example code? → See `.github/skills/crud/rules/EXAMPLES.md`
- Existing pattern? → Look at `app/Livewire/Admin/Users/`

## API Reference

### Components
```blade
<x-crud::page-header>
<x-crud::filter-toolbar>
<x-crud::table>
<x-crud::empty-state>
```

### Traits
```php
use App\Livewire\Concerns\WithCrudListing;   // Pagination, filtering
use App\Livewire\Concerns\WithCrudForm;      // Form validation, save
use App\Livewire\Concerns\WithCrudDelete;    // Delete confirmation
```

### Helper
```php
PermissionHelper::registerCrudGates('products');
PermissionHelper::ensurePermissionsExist('products');
PermissionHelper::getPermissionNames('products');
```

## File Manifest

```
✅ Documentation (4 files)
   ├── .github/skills/crud/SKILL.md
   ├── .github/skills/crud/rules/EXAMPLES.md
   ├── .github/skills/crud/rules/README.md
   └── .github/skills/crud/AGENTS.md

✅ Components (4 files)
   └── resources/views/components/crud/
       ├── page-header.blade.php
       ├── filter-toolbar.blade.php
       ├── table.blade.php
       └── empty-state.blade.php

✅ Livewire Base (3 files)
   └── app/Livewire/Concerns/
       ├── WithCrudListing.php
       ├── WithCrudForm.php
       └── WithCrudDelete.php

✅ Authorization (3 files)
   ├── app/Helpers/PermissionHelper.php (enhanced)
   └── database/seeders/Permissions/
       ├── CrudPermissionSeeder.php
       └── README.md

✅ Generator (1 file)
   └── app/Console/Commands/MakeCrudCommand.php

✅ Checklists (1 file)
   └── CRUD_SETUP_CHECKLIST.md

Total: 17 files created/updated
```

## Next: Create Your First CRUD

Ready? Run:
```bash
php artisan make:crud Product --all
```

And follow the printed instructions!

Questions? See `.github/skills/crud/SKILL.md` 📖
