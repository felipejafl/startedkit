Aquí está un prompt completo para crear CRUDs correctamente en tu proyecto:

# Crear un CRUD en Laravel 12 + Livewire 4 + Flux UI

Soy un desarrollador trabajando en un proyecto Laravel 12 con Livewire 4 y Flux UI.
Necesito crear un CRUD y quiero que sigas RIGUROSAMENTE el estándar del proyecto.

## ANTES de empezar, DEBES:

1. **Revisar el estándar CRUD**: Lee `.github/skills/crud/SKILL.md` para entender la estructura exacta
2. **Revisar ejemplos existentes**: 
   - Admin module: `app/Livewire/Admin/Users/` (si es un módulo admin)
   - Modulo independiente: `app/Livewire/Rgpd/Contacts/` (si es un módulo separado)

## Estructura que DEBES crear:

### Para un MÓDULO SEPARADO (ej: RGPD, Inventory, etc):

app/Livewire/{ModuleName}/{Resource}/Index.php
resources/views/livewire/{kebab-module}/{kebab-resource}/index.blade.php
resources/views/{kebab-module}/{kebab-resource}.blade.php
routes/{kebab-module}.php
database/factories/{Resource}Factory.php
database/seeders/Permissions/{Resource}PermissionSeeder.php
app/Policies/{Resource}Policy.php
tests/Feature/{Module}/{Resource}Test.php


### Para un CRUD dentro de ADMIN:

app/Livewire/Admin/{Resource}/Index.php
resources/views/livewire/admin/{kebab-resource}/index.blade.php
admin.php (agregar ruta aquí)


## Pasos OBLIGATORIOS que NO debo olvidar:

✅ **1. CREAR MODELO Y MIGRACIÓN**
- Usar: `php artisan make:model {Resource} -m`
- Campos: id, datos del recurso, is_active, timestamps
- Índices en campos que se filtren

✅ **2. CREAR FACTORY Y POLICY**
- Factory: `php artisan make:factory {Resource}Factory --model={Resource}`
- Policy: `php artisan make:policy {Resource}Policy --model={Resource}`

✅ **3. CREAR RUTAS**
- SI ES MÓDULO SEPARADO: Crear `routes/{kebab-module}.php` y registrarlo en `routes/web.php`
  ```php
  Route::prefix('{kebab-module}')->name('{kebab-module}.')->group(base_path('routes/{kebab-module}.php'));

SI ES ADMIN: Agregar ruta en routes/admin.php
Usar kebab-case para rutas: /rgpd/contacts, /admin/users
Usar camelCase para nombres: rgpd.contacts.index, admin.users.index
✅ 4. CREAR COMPONENTE LIVEWIRE

Namespace: App\Livewire\{Module}\{Resource}
Archivo: Index.php (solo componente Index para listing + modales)
Vista: resources/views/livewire/{kebab-module}/{kebab-resource}/index.blade.php
Vista de layout: resources/views/{kebab-module}/{kebab-resource}.blade.php
Usar Flux UI components SOLO, no Bootstrap
Usar @can('permission') en las vistas
✅ 5. CREAR PERMISOS

Formato: {resource}.{action} en snake_case
Ejemplo: contacts.viewAny, contacts.create, contacts.update, contacts.delete
Crear PermissionSeeder extendiendo CrudPermissionSeeder
Registrar Gates en AuthorizationServiceProvider
✅ 6. ACTUALIZAR SIDEBAR ⭐ (NO OLVIDES ESTO)

Editar: resources/views/layouts/app/sidebar.blade.php
Agregar nuevo grupo o agregar item al grupo existente
Usar: @can('permission.viewAny')
Ruta correcta: route('module.resource.index')
:current="request()->routeIs('module.resource.*')"
Ejemplo:

@if(auth()->user()->can('contacts.viewAny'))
    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('RGPD')">
            <flux:sidebar.item icon="shield" :href="route('rgpd.contacts.index')" 
                :current="request()->routeIs('rgpd.contacts.*')" wire:navigate>
                {{ __('Contacts') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>
@endif

✅ 7. CREAR TESTS MÍNIMOS

18 tests: acceso, modelo, factory, policy, autorización
Tests en tests/Feature/{Module}/{Resource}Test.php
Todos deben PASAR: php artisan test --compact --filter={Resource}
✅ 8. EJECUTAR PINT

vendor/bin/pint --dirty --format agent
Debe estar sin errores antes de terminar
✅ 9. COMPILAR ASSETS

npm run build si hay cambios en CSS/JS
Checklist FINAL:
 Modelo con fillable y casts correctos
 Migration con campos indexados (is_active)
 Factory generando datos realistas
 Policy con métodos viewAny, view, create, update, delete
 Componente Livewire en namespace correcto
 Vista con Flux UI (botones, inputs, modales, tablas)
 Rutas registradas en web.php o admin.php
 Permisos en PermissionSeeder o PermissionSeeder task
 Gates registrados en AuthorizationServiceProvider
 SIDEBAR ACTUALIZADO (con @can y ruta correcta)
 Tests pasando (18 tests)
 Pint formateado sin errores
 npm run build ejecutado
RECUERDA:
Kebab-case para rutas: /rgpd/contacts, /inventory/items
Snake_case para permisos: contacts.viewAny
PascalCase para clases: RgpdContact, InventoryItem
SIEMPRE actualizar sidebar cuando creas un nuevo CRUD
SIEMPRE usar Flux UI, nunca Bootstrap
SIEMPRE extender CrudPermissionSeeder para permisos
SIEMPRE crear factory y policy
SIEMPRE 18+ tests como mínimo


---

**Cómo usarlo:**
1. En tu próximo chat, copia este prompt completo
2. Reemplaza `{ModuleName}`, `{Resource}`, `{kebab-module}`, etc. con tus valores
3. Usa como checklist durante el desarrollo
4. Antes de terminar, verifica cada ✅ del checklist final

Este prompt previene los errores que cometiste: crear dentro de Admin en lugar de módulo separado, olvidar el sidebar, no seguir la estructura estándar. 🎯