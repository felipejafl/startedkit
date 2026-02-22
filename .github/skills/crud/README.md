# CRUD Development Standard

**Complete, production-ready system for building CRUDs in Laravel 12 + Livewire 4 + Flux UI.**

> Every CRUD in this project follows the same architecture, naming, UI, validation, and authorization patterns. This ensures consistency, maintainability, and faster development.

---

## 📚 Documentation Files

### Core

| File | Purpose | Read Time |
|------|---------|-----------|
| **[SKILL.md](./SKILL.md)** | **Full CRUD Standard** - Complete specifications (8 sections, naming, structure, UI, validation, authorization, checklist) | 15 min |
| **[AGENTS.md](./AGENTS.md)** | **Quick Reference** - Skill activation, key files, when to use | 5 min |
| **[SETUP.md](./SETUP.md)** | **Getting Started** - How to use this, file manifest, support | 10 min |

### Examples & Guides

| File | Purpose | Read Time |
|------|---------|-----------|
| **[rules/EXAMPLES.md](./rules/EXAMPLES.md)** | **Product CRUD Complete Example** - Working code: model, migration, policy, components, views, routes, tests (7 sections) | 20 min |
| **[rules/README.md](./rules/README.md)** | **Permissions & Authorization Guide** - Setup, usage, troubleshooting | 10 min |

---

## 🚀 Quick Start

### Create a CRUD in 3 commands:

```bash
# 1. Generate all structure
php artisan make:crud Product --all

# 2. Follow printed instructions (update migration, routes, gates)
# 3. Run tests
php artisan test --compact
```

See [SETUP.md](./SETUP.md) for full walkthrough.

---

## 📋 What's Included

### ✅ Shared Components (Blade/Flux)
- Page header (title, subtitle, action)
- Filter toolbar (search, select, reset)
- Data table
- Empty state

→ Located: `resources/views/components/crud/`

### ✅ Livewire Base Traits
- `WithCrudListing` - Pagination, filtering, sorting
- `WithCrudForm` - Form handling, validation, modal
- `WithCrudDelete` - Delete confirmation

→ Located: `app/Livewire/Concerns/`

### ✅ Authorization System
- Enhanced `PermissionHelper` for CRUD gates
- `CrudPermissionSeeder` base class
- Policy pattern with 5 standard methods

→ Located: `app/Helpers/`, `database/seeders/Permissions/`

### ✅ Artisan Generator
```bash
php artisan make:crud Product --all
```
Generates: Model, Migration, Factory, Seeder, Policy, Livewire components, Views, Tests

→ Located: `app/Console/Commands/MakeCrudCommand.php`

---

## 📖 Standard Summary

### Naming (Don't Negotiate)
```
Routes:      /products (kebab-case)
Route names: products.index (camelCase)
Models:      Product (PascalCase)
Permissions: products.viewAny (snake_case)
Components:  Index, Form, DeleteConfirm (PascalCase)
```

### Structure
```
app/Livewire/Admin/{Resource}/
├── Index.php
├── Form.php
└── DeleteConfirm.php

resources/views/livewire/admin/{kebab-resource}/
├── index.blade.php
├── form.blade.php
└── delete-confirm.blade.php
```

### Authorization (Always)
```php
// Policy with 5 methods
viewAny(), view(), create(), update(), delete()

// Spatie permissions
{resource}.viewAny, view, create, update, delete

// Gates auto-registered via helper
PermissionHelper::registerCrudGates('products');
```

### UI (Flux Only)
```blade
<x-crud::page-header>
<x-crud::filter-toolbar>
<x-crud::table>
<x-crud::empty-state>
```

See [SKILL.md](./SKILL.md) for complete details.

---

## 🔍 How to Use

### I'm a Developer

1. **First time?** Read [SKILL.md](./SKILL.md#1-naming-conventions) (5 min)
2. **Creating a CRUD?** Copy code from [rules/EXAMPLES.md](./rules/EXAMPLES.md) or use `php artisan make:crud`
3. **Need to verify?** Check against [CRUD_SETUP_CHECKLIST.md](../../CRUD_SETUP_CHECKLIST.md) (30 items)

### I'm a Senior/Lead

1. **Reviewing a PR?** Use [CRUD_SETUP_CHECKLIST.md](../../CRUD_SETUP_CHECKLIST.md)
2. **Need to approve?** All items in checklist ✅ = approved

### I'm an AI Agent/Copilot

1. **Reference:** [SKILL.md](./SKILL.md) for complete standard
2. **Example:** [rules/EXAMPLES.md](./rules/EXAMPLES.md) for working code
3. **Base Classes:** `WithCrudListing`, `WithCrudForm`, `WithCrudDelete` traits
4. **Helper:** `PermissionHelper` for authorization
5. **Real Example:** `app/Livewire/Admin/Users/` (existing Users CRUD)

---

## 🗂️ File Map

### Documentation (This Folder)
```
.github/skills/crud/
├── README.md ..................... (you are here)
├── SKILL.md ...................... Full standard
├── AGENTS.md ..................... Quick reference
├── SETUP.md ...................... Getting started
└── rules/
    ├── EXAMPLES.md ............... Product CRUD example
    └── README.md ................. Permissions setup
```

### Implementation (Project Root)
```
CRUD_IMPLEMENTATION_SUMMARY.md ... Full implementation details
CRUD_SETUP_CHECKLIST.md .......... Pre-PR verification (30 items)
CRUD_ARCHITECTURE_VISUAL.md ...... Visual architecture guide
```

### Code (Active in Project)
```
app/
├── Console/Commands/MakeCrudCommand.php
├── Helpers/PermissionHelper.php (enhanced)
└── Livewire/Concerns/
    ├── WithCrudListing.php
    ├── WithCrudForm.php
    └── WithCrudDelete.php

resources/views/components/crud/
├── page-header.blade.php
├── filter-toolbar.blade.php
├── table.blade.php
└── empty-state.blade.php

database/seeders/Permissions/
├── CrudPermissionSeeder.php
└── README.md
```

---

## ❓ Common Questions

**Q: Do I have to use the MakeCrudCommand?**  
A: No, but it saves time. You can manually follow [EXAMPLES.md](./rules/EXAMPLES.md).

**Q: Can I change the structure?**  
A: Only if you follow [SKILL.md](./SKILL.md) naming conventions.

**Q: What if I need a custom field/action?**  
A: Add to your model + form + validation. Refer to [SKILL.md#6-validation-rules](./SKILL.md#6-validation-rules).

**Q: How do I know if my CRUD is ready?**  
A: Follow [CRUD_SETUP_CHECKLIST.md](../../CRUD_SETUP_CHECKLIST.md) (30 items).

**Q: What about relationships (belongsTo, hasMany)?**  
A: Documented in [SKILL.md#eloquent-relationships](./SKILL.md). See [EXAMPLES.md](./rules/EXAMPLES.md) for patterns.

---

## 📞 Support

| Need | Reference |
|------|-----------|
| "What's the standard?" | [SKILL.md](./SKILL.md) |
| "How do I create a CRUD?" | [EXAMPLES.md](./rules/EXAMPLES.md) or `php artisan make:crud --help` |
| "How do permissions work?" | [rules/README.md](./rules/README.md) |
| "Is my CRUD ready?" | [CRUD_SETUP_CHECKLIST.md](../../CRUD_SETUP_CHECKLIST.md) |
| "Show me real code" | `app/Livewire/Admin/Users/` (existing) |
| "Tests?" | [EXAMPLES.md tests section](./rules/EXAMPLES.md#6-tests) |

---

## 🎯 Next Steps

1. **Read:** [SKILL.md](./SKILL.md) (section 1-3) - understand standard
2. **Locate:** [rules/EXAMPLES.md](./rules/EXAMPLES.md) - see working example
3. **Create:** `php artisan make:crud YourResource --all`
4. **Verify:** vs [CRUD_SETUP_CHECKLIST.md](../../CRUD_SETUP_CHECKLIST.md)
5. **Test:** `php artisan test --compact`
6. **Merge:** following the standard ✅

---

## 📊 Statistics

- **Documentation:** 1800+ lines
- **Components:** 4 Blade shared components
- **Traits:** 3 Livewire base traits
- **Helper methods:** 4 for CRUD gates/permissions
- **Artisan command:** 1 full CRUD generator
- **Example:** 1 complete Product CRUD (600+ lines)
- **Verification items:** 30-item checklist

---

**Status:** ✅ Production Ready  
**Last Updated:** 22 de Febrero, 2026  
**Version:** 1.0  

Start creating CRUDs now! 🚀
