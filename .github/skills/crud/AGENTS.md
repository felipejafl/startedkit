# CRUD Development Guide - AGENTS.md Integration

This file activates the CRUD development skill autointegratedmatically when:
- User mentions "create crud", "new crud", "build crud", "product development"
- User asks for "consistent crud", "standard crud pattern", "crud architecture"
- User needs to generate a resource with livewire forms, permissions, policies
- User wants to understand the CRUD standard in this project

---

## Quick Reference

### Create a new CRUD in 5 commands:

```bash
# 1. Generate CRUD structure (all files)
php artisan make:crud ProductCategory --all

# 2. Update migration with your fields
nano database/migrations/202X_XX_XX_XXXXXX_create_product_categories_table.php

# 3. Add routes (routes/admin.php):
# Route::get('/product-categories', fn() => view('admin.product-categories.index'))
#     ->name('product-categories.index');

# 4. Add gates in app/Providers/AuthorizationServiceProvider.php:
# PermissionHelper::registerCrudGates('product_categories');

# 5. Create and run permission seeder
php artisan make:seeder Permissions/ProductCategoryPermissionSeeder
php artisan migrate
php artisan db:seed --class=Permissions/ProductCategoryPermissionSeeder
```

---

## Key Files

- **Documentation:** `.github/skills/crud/SKILL.md` - Complete standard
- **Examples:** `.github/skills/crud/rules/EXAMPLES.md` - Product CRUD full example
- **Shared Components:** `resources/views/components/crud/` - Reusable Blade components
- **Base Traits:** `app/Livewire/Concerns/` - WithCrudListing, WithCrudForm, WithCrudDelete
- **Helpers:** `app/Helpers/PermissionHelper.php` - Permission registration utilities
- **Command:** `app/Console/Commands/MakeCrudCommand.php` - CRUD generator
- **Permissions:** `database/seeders/Permissions/CrudPermissionSeeder.php` - Base seeder

---

## Standard Applied

Every CRUD in this project MUST follow:

✅ **Naming:** kebab-case routes, snake_case permissions, PascalCase components  
✅ **Authorization:** Policy + Gates + Spatie Permissions  
✅ **UI:** Flux components only (modals, buttons, forms, tables)  
✅ **Validation:** Centralized rules() and messages() methods  
✅ **Structure:** Index/Form/Delete components + Blade views in consistent folders  
✅ **Testing:** Authorization + validation + render tests minimum  
✅ **Code Quality:** Formatted with Pint, no console errors

---

## What NOT to Do

❌ Don't mix UI libraries (no Bootstrap, no custom CSS in Livewire)
❌ Don't create non-standard routes or permission names
❌ Don't skip authorization checks
❌ Don't use inline validation
❌ Don't create components without corresponding views
❌ Don't forget tests (at least 5 per CRUD)

---

## Skills Integration

Automatically load these skills when creating CRUDs:
- **livewire-development** - Livewire components
- **fluxui-development** - UI components
- **pest-testing** - Write tests
- **eloquent-best-practices** - Model relationships
- **clean-code-principles** - Architecture review

---

## File References

When creating a CRUD, reference these existing implementations:
- **Users CRUD:** `app/Livewire/Admin/Users/` - Full working example (Index, Form, Delete)
- **Authorization:** `app/Providers/AuthorizationServiceProvider.php` - Gate patterns
- **Migrations:** `database/migrations/` - Field patterns
- **Tests:** `tests/Feature/Admin/` - Test patterns

---

## Integration with Existing Code

The CRUD standard is fully integrated with:
- **Routes:** Follows `routes/admin.php` pattern with middleware
- **Layout:** Uses `resources/views/layouts/app.blade.php` with sidebar
- **Permissions:** Compatible with existing Spatie setup
- **Authentication:** Works with Fortify + Laravel auth

---

## When to Use

Use this standard for:
- ✅ Admin management features (Products, Categories, Orders, etc.)
- ✅ Any RBAC-protected resource
- ✅ Features requiring modal/form workflows
- ✅ Features needing consistent UI/UX

Do NOT use for:
- ❌ API endpoints (use API Resources instead)
- ❌ Simple frontend features (no CRUD)
- ❌ Features outside admin panel

---

## Support & Updates

- Ask "Show CRUD standard" to get full documentation
- Ask "Create CRUD for {resource}" to generate new resource
- Ask "Review CRUD" to audit existing implementation
- Refer to `.github/skills/crud/SKILL.md` for detailed specifications
