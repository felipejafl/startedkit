# Sistema de CRUD - Estructura Visual

## 📐 ARQUITECTURA COMPLETA

```
┌─────────────────────────────────────────────────────────────────┐
│                   CRUD DEVELOPMENT SYSTEM                        │
│              (Laravel 12 + Livewire 4 + Flux UI)                │
└─────────────────────────────────────────────────────────────────┘

┌─── DOCUMENTACIÓN (1800+ líneas) ──────────────────────────────┐
│                                                                 │
│  .github/skills/crud/                                          │
│  ├── SKILL.md ........................ Estándar completo       │
│  ├── AGENTS.md ....................... Quick reference         │
│  ├── SETUP.md ........................ Implementation guide    │
│  └── rules/                                                    │
│      ├── EXAMPLES.md ................. Product CRUD (completo) │
│      └── README.md ................... Permissions setup       │
│                                                                 │
│  CRUD_IMPLEMENTATION_SUMMARY.md .... Este resumen             │
│  CRUD_SETUP_CHECKLIST.md ........... Verificación (30 items) │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── UI COMPONENTS (Blade/Flux - 4 componentes) ────────────────┐
│                                                                 │
│  resources/views/components/crud/                              │
│  ├── 📦 page-header.blade.php                                 │
│  │   └─ <x-crud::page-header> (title, subtitle, action)      │
│  ├── 📦 filter-toolbar.blade.php                             │
│  │   └─ <x-crud::filter-toolbar> (search, select, reset)     │
│  ├── 📦 table.blade.php                                       │
│  │   └─ <x-crud::table> (responsive Flux table)              │
│  └── 📦 empty-state.blade.php                                │
│      └─ <x-crud::empty-state> (no records found)             │
│                                                                 │
│  ✅ Reutilizables en cualquier CRUD                           │
│  ✅ Dise­ño consistente (Flux)                              │
│  ✅ Responsive + dark mode                                    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── LIVEWIRE BASE TRAITS (3 traits - 300+ líneas) ──────────────┐
│                                                                 │
│  app/Livewire/Concerns/                                        │
│  ├── WithCrudListing.php (pagination, filtering, sorting)     │
│  │   ├─ Properties: search, perPage, sortBy, sortDirection    │
│  │   ├─ Methods:                                               │
│  │   │  ├─ sort($column)         ← Sort toggle               │
│  │   │  ├─ resetFilters()        ← Clear all filters         │
│  │   │  └─ setPerPage($count)    ← Change pagination         │
│  │   └─ Query string binding: search, sort*, page            │
│  │                                                              │
│  ├── WithCrudForm.php (form: validate, save, modal)           │
│  │   ├─ Properties: show, isSubmitting                        │
│  │   ├─ Methods:                                               │
│  │   │  ├─ rules()               ← Override per component    │
│  │   │  ├─ messages()            ← Override per component    │
│  │   │  ├─ validate()            ← Auto validation           │
│  │   │  ├─ close()               ← Close modal               │
│  │   │  └─ save()                ← Save with flash messaging │
│  │   └─ Events: itemSaved, flash                             │
│  │                                                              │
│  └── WithCrudDelete.php (delete: confirm, delete, feedback)   │
│      ├─ Properties: show, isDeleting, model               │
│      ├─ Methods:                                               │
│      │  ├─ open($model)          ← Open confirm modal        │
│      │  ├─ delete()              ← Delete with validation    │
│      │  ├─ close()               ← Close modal               │
│      │  └─ performDelete()       ← Override per component    │
│      └─ Events: itemDeleted, flash                           │
│                                                                 │
│  ✅ Copy-paste ready para Index, Form, DeleteConfirm         │
│  ✅ Métodos abstractos para override                         │
│  ✅ Flash events automáticos                                 │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── AUTHORIZATION (Permisos + Gates + Policies) ────────────────┐
│                                                                 │
│  app/Helpers/PermissionHelper.php (enhanced)                   │
│  ├─ registerCrudGates($resource)                              │
│  │  └─ Define gates: {resource}.viewAny, create, update...  │
│  ├─ registerCrudGatesForMany($resources)                      │
│  │  └─ Register multiple at once                              │
│  ├─ ensurePermissionsExist($resource)                         │
│  │  └─ Create permissions in database                         │
│  ├─ getPermissionNames($resource)                             │
│  │  └─ Return array of 5 permissions                          │
│  └─ [Legacy] getGroupedPermissions(), getPermissionLabel()   │
│                                                                 │
│  database/seeders/Permissions/CrudPermissionSeeder.php        │
│  ├─ class {Resource}PermissionSeeder extends CrudPermissionSeeder │
│  ├─ protected $resource = '{resource}'; // REQUIRED          │
│  └─ Auto-creates: viewAny, view, create, update, delete      │
│                                                                 │
│  Usage in AuthorizationServiceProvider.php:                   │
│  ├─ PermissionHelper::registerCrudGates('products');          │
│  ├─ PermissionHelper::registerCrudGates('categories');        │
│  └─ Or manual Gate::define for custom logic                   │
│                                                                 │
│  ✅ Seeding automático                                       │
│  ✅ Gates auto-registered                                    │
│  ✅ Super-admin bypass integrado                             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── ARTISAN COMMAND GENERATOR ─────────────────────────────────┐
│                                                                 │
│  app/Console/Commands/MakeCrudCommand.php                      │
│                                                                 │
│  Usage:                                                        │
│  ├─ php artisan make:crud Product --all                       │
│  ├─ php artisan make:crud Category --model --factory --policy │
│  └─ php artisan make:crud Order --force                       │
│                                                                 │
│  Generates:                                                    │
│  ├─ ✅ Model (app/Models/)                                    │
│  ├─ ✅ Migration (database/migrations/)                       │
│  ├─ ✅ Factory (database/factories/)                          │
│  ├─ ✅ Seeder (database/seeders/)                             │
│  ├─ ✅ Policy (app/Policies/)                                 │
│  ├─ ✅ Livewire components (Index, Form, Delete)             │
│  ├─ ✅ Blade views (index, form, delete-confirm)             │
│  ├─ ✅ Test skeleton (tests/Feature/Admin/)                   │
│  └─ ✅ Next steps printed in console                          │
│                                                                 │
│  ✅ Templates editables                                      │
│  ✅ One-command scaffolding                                  │
│  ✅ Sigue el estándar SKILL.md                               │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── CÓMO CREAR UN CRUD (FLOW) ────────────────────────────────┐
│                                                                 │
│  1️⃣  GENERAR                                                  │
│      $ php artisan make:crud Product --all                    │
│                                                                 │
│  2️⃣  EDITAR (migration, rutas, gates)                        │
│      ├─ database/migrations/* ... ADD FIELDS                  │
│      ├─ routes/admin.php .............. ADD ROUTE             │
│      ├─ AuthorizationServiceProvider .. ADD GATE              │
│      └─ database/seeders/Permissions.. NEW SEEDER             │
│                                                                 │
│  3️⃣  EJECUTAR                                               │
│      $ php artisan migrate                                    │
│      $ php artisan db:seed --class=Permissions/ProductSeeder │
│      $ php artisan test --compact                             │
│      $ composer lint                                          │
│                                                                 │
│  4️⃣  VERIFICAR (vs CRUD_SETUP_CHECKLIST.md - 30 items)      │
│                                                                 │
│  5️⃣  MERGEAR                                                 │
│      $ git commit -m "feat: add product management CRUD"     │
│                                                                 │
│  ⏱️  Tiempo total: 5-10 minutos por CRUD                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── PROYECTO ESTRUCTURA (FINAL) ────────────────────────────────┐
│                                                                 │
│  app/                                                           │
│  ├── Console/Commands/                                         │
│  │   └── 🎯 MakeCrudCommand.php .... CRUD Generator          │
│  ├── Helpers/                                                  │
│  │   └── 🎯 PermissionHelper.php ... Enhanced (CRUD methods) │
│  ├── Livewire/                                                 │
│  │   ├── Concerns/                                            │
│  │   │   ├── 🎯 WithCrudListing.php ......... Pagination    │
│  │   │   ├── 🎯 WithCrudForm.php ............ Form logic    │
│  │   │   └── 🎯 WithCrudDelete.php ......... Delete logic   │
│  │   └── Admin/                                              │
│  │       ├── Users/               (✅ Existente - Referencia)│
│  │       ├── Roles/               (✅ Existente - Referencia)│
│  │       └── {NewResource}/       (🆕 Next CRUDs)           │
│  ├── Models/                                                   │
│  │   └── {Resource}.php           (Generated por make:model) │
│  └── Policies/                                                 │
│      └── 🎯 {Resource}Policy.php (Generated por MakeCrudCmd)│
│                                                                 │
│  database/                                                     │
│  ├── factories/                                                │
│  │   └── 🎯 {Resource}Factory.php                            │
│  ├── migrations/                                               │
│  │   └── 🎯 *_create_{resources}_table.php                   │
│  └── seeders/                                                  │
│      ├── 🎯 {Resource}Seeder.php                             │
│      └── Permissions/                                         │
│          ├── 🎯 CrudPermissionSeeder.php ... Base class     │
│          ├── 🎯 {Resource}PermissionSeeder.php ... Specific │
│          └── 📖 README.md ..................... Setup guide   │
│                                                                 │
│  resources/views/                                              │
│  ├── components/crud/                                         │
│  │   ├── 🎯 page-header.blade.php                           │
│  │   ├── 🎯 filter-toolbar.blade.php                        │
│  │   ├── 🎯 table.blade.php                                 │
│  │   └── 🎯 empty-state.blade.php                           │
│  └── livewire/admin/{resource}/                              │
│      ├── 🎯 index.blade.php                                 │
│      ├── 🎯 form.blade.php                                  │
│      └── 🎯 delete-confirm.blade.php                        │
│                                                                 │
│  routes/                                                       │
│  └── admin.php (Updated: routes para nuevo CRUD)             │
│                                                                 │
│  tests/Feature/Admin/                                          │
│  ├── 🎯 {Resource}Test.php (Generated por MakeCrudCommand) │
│  └── ... (+ tests existentes Users, Roles, Permissions)     │
│                                                                 │
│  .github/skills/crud/                                         │
│  ├── 📖 SKILL.md ........................ Full standard      │
│  ├── 📖 AGENTS.md ....................... Quick ref         │
│  ├── 📖 SETUP.md ........................ Setup guide       │
│  └── rules/                                                   │
│      ├── 📖 EXAMPLES.md ................. Product CRUD      │
│      └── 📖 README.md ................... Permissions setup │
│                                                                 │
│  📋 CRUD_IMPLEMENTATION_SUMMARY.md .. This summary           │
│  📋 CRUD_SETUP_CHECKLIST.md ........... PR verification     │
│                                                                 │
│  🎯 = Nueva o modificada                                     │
│  📖 = Documentación                                           │
│  ✅ = Existente (referencia)                                 │
│  📋 = Checklist/Meta                                         │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─── NAMING STANDARD (NO DEBATIR) ──────────────────────────────┐
│                                                                 │
│  Routes                                                        │
│  ├─ Paths:      /products, /product-categories (kebab-case)  │
│  └─ Names:      products.index, products.create (camelCase)  │
│                                                                 │
│  Database/Models                                               │
│  ├─ Tables:     products, product_categories (snake_case)    │
│  ├─ Models:     Product, ProductCategory (PascalCase)        │
│  └─ Migrations: 202X_create_products_table.php               │
│                                                                 │
│  Livewire Components                                           │
│  ├─ Namespace:  App\Livewire\Admin\Products                  │
│  ├─ Classes:    Index, Form, DeleteConfirm (PascalCase)      │
│  └─ Views:      livewire.admin.products.index (dot notation) │
│                                                                 │
│  Permissions / Gates                                           │
│  ├─ Format:     {resource}.{action}                          │
│  ├─ Actions:    viewAny, view, create, update, delete        │
│  ├─ Examples:   products.viewAny, products.create            │
│  └─ Guard:      'web'                                         │
│                                                                 │
│  Policies                                                      │
│  ├─ File:       app/Policies/ProductPolicy.php              │
│  └─ Methods:    viewAny(), view(), create(), update(), delete()│
│                                                                 │
│  ✅ Estándar NO es negociable                                │
│  ✅ Todos los CRUDs DEBEN seguir esto                       │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 FLUJO DE AUTORIZACIÓN

```
USER REQUEST
    ↓
┌─────────────────────────────────┐
│ Middleware: auth + admin        │
├─────────────────────────────────┤
│ ✅ User is logged in?           │
│ ✅ Admin.access permission?     │
│ ✅ is_active = true?            │
└─────────────────────────────────┘
    ↓
┌─────────────────────────────────┐
│ Livewire Component              │
├─────────────────────────────────┤
│ $this->authorize(               │
│   'products.viewAny'            │
│ )                               │
└─────────────────────────────────┘
    ↓
┌────────────────────────────────────────────────────────┐
│ AuthorizationServiceProvider::boot()                   │
├────────────────────────────────────────────────────────┤
│ ✅ Super-admin? Gate::before() return TRUE            │
│ ✅ User has 'products.viewAny' permission?             │
│    (Check: user.id in model_has_permissions)          │
│    OR Has role with permission?                        │
│    (Check: user.role in role_has_permissions)         │
└────────────────────────────────────────────────────────┘
    ↓
✅ ALLOWED / ❌ FORBIDDEN
```

---

## 🧪 TEST STRUCTURE POR CRUD

```
tests/Feature/Admin/

{Resource}Test.php
├── setUp()                              ← Create test user + grant permissions
│
├─ Authorization Tests
│  ├── test_unauthorized_cannot_view()
│  ├── test_authorized_can_view_list()
│  ├── test_authorized_can_create()
│  ├── test_authorized_can_update()
│  └── test_authorized_can_delete()
│
├─ Validation Tests
│  └── test_validation_name_required()
│  └── test_validation_email_unique()
│
├─ Action Tests
│  ├── test_can_create_resource()
│  ├── test_can_update_resource()
│  ├── test_can_delete_resource()
│  └── test_soft_delete_or_is_active_flag()
│
└─ Render Tests
   └── test_index_component_renders()
```

**Mínimo:** 6 tests por CRUD

---

## 📚 DOCUMENTACIÓN MAP

```
¿Pregunta?                              ¿Dónde buscar?
────────────────────────────────────────────────────────────────
¿Cuál es el estándar?                   → SKILL.md (8 secciones)
¿Cómo creo un CRUD nuevo?               → EXAMPLES.md (copy-paste)
¿Qué hace el comando?                   → MakeCrudCommand --help
¿Cómo funcionan permisos?               → Permissions/README.md
¿Qué es WithCrudListing?                → app/Livewire/Concerns/
¿Cómo hago el test?                     → EXAMPLES.md tests section
¿Debo cambiar algo?                     → CRUD_SETUP_CHECKLIST.md
¿Cómo verifico que esté bien?           → CRUD_SETUP_CHECKLIST.md (30 items)
¿Me falta algo?                         → AGENTS.md (quick ref)
¿Ejemplo de código real?                → app/Livewire/Admin/Users/ (existente)
```

---

## ✅ TODO LISTO PARA USAR

**Estado:** Production Ready  
**Versión:** 1.0  
**Fecha:** 22 de Febrero, 2026  

Próxima acción:
```bash
php artisan make:crud Product --all
```

¿Dudas? Ver `.github/skills/crud/SKILL.md` 📖
