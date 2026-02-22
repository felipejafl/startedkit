# Mail Accounts CRUD - Guía Completa

## Resumen

Crud completo para gestionar cuentas de correo con configuración IMAP y SMTP.

## Estructura Implementada

### 📁 Modelo & Base de Datos
- **Modelo**: `app/Models/MailAccount.php`
  - Password encriptado automáticamente
  - Campos: name, email, server, password, imap_port, imap_security, smtp_port, smtp_security, is_active, last_synced_at

- **Migración**: `database/migrations/2026_02_22_214250_create_mail_accounts_table.php`
  - Tabla con campos validados
  - Índices en email e is_active

### 🧪 Factory & Tests
- **Factory**: `database/factories/MailAccountFactory.php`
  - Genera datos realistas para testing
  - Incluye servidores reales (Gmail, Outlook, Yahoo, iCloud)
  - Puertos y seguridad válidos

- **Tests**: `tests/Feature/Admin/MailAccountsTest.php`
  - 18 tests pasando ✅
  - Cobertura: Acceso, modelo, policy, factory

### 🔐 Autorización
- **Policy**: `app/Policies/MailAccountPolicy.php`
  - viewAny, view, create, update, delete

- **Permissions** (registradas en `database/seeders/PermissionSeeder.php`):
  - mail-accounts.viewAny
  - mail-accounts.view
  - mail-accounts.create
  - mail-accounts.update
  - mail-accounts.delete

- **Gates** (registrados en `app/Providers/AuthorizationServiceProvider.php`):
  - Autorización mediante Spatie Permission
  - Super-admin bypass automático

### 🎨 Interfaz Livewire
- **Componente**: `app/Livewire/Admin/MailAccounts/Index.php`
  - Gestión completa: crear, editar, eliminar, toggle activo
  - Búsqueda por nombre/email
  - Filtro por estado (activo/inactivo)
  - Paginación automática
  - Validaciones con mensajes personalizados

- **Vista**: `resources/views/livewire/admin/mail-accounts/index.blade.php`
  - Tabla con listado
  - Modal para crear/editar
  - Modal de confirmación para eliminar
  - Componentes Flux UI
  - Dark mode soportado

- **Ruta**: `/admin/mail-accounts` → `routes/admin.php`

## Campos del Formulario

### Información Básica
- **Nombre de Cuenta** (required, max 255): etiqueta amigable
- **Email** (required, email, unique): dirección de correo
- **Servidor** (required, max 255): ej. imap.gmail.com

### Configuración IMAP
- **Puerto IMAP** (required, 1-65535): default 993
- **Seguridad IMAP** (required): none, ssl, tls (default ssl)

### Configuración SMTP
- **Puerto SMTP** (required, 1-65535): default 587
- **Seguridad SMTP** (required): none, ssl, tls (default tls)

### Estado
- **Activo** (boolean): checkbox para habilitar/deshabilitar
- **Última Sincronización** (auto): gestionada por la app

## Validaciones

```php
// Nombre requerido
'formName' => 'required|string|max:255'

// Email único
'formEmail' => 'required|email|unique:mail_accounts,email'

// Servidor requerido
'formServer' => 'required|string|max:255'

// Contraseña (6+ caracteres, opcional en edición)
'formPassword' => 'required|string|min:6'  // crear
'formPassword' => 'nullable|string|min:6'  // editar

// Puertos (1-65535)
'formImapPort' => 'required|integer|between:1,65535'
'formSmtpPort' => 'required|integer|between:1,65535'

// Seguridad
'formImapSecurity' => 'required|in:none,ssl,tls'
'formSmtpSecurity' => 'required|in:none,ssl,tls'
```

## Ejemplo de Uso

### Crear Cuenta
```php
MailAccount::create([
    'name' => 'Support Team',
    'email' => 'support@example.com',
    'server' => 'imap.gmail.com',
    'password' => 'securePassword',  // encriptado automáticamente
    'imap_port' => 993,
    'imap_security' => 'ssl',
    'smtp_port' => 587,
    'smtp_security' => 'tls',
    'is_active' => true,
]);
```

### Usar en Tests
```php
$account = MailAccount::factory()->create();
$account = MailAccount::factory()->create([
    'name' => 'Custom Name',
    'is_active' => false,
]);
```

### Verificar Permisos
```php
// En Livewire/Controller
$this->authorize('mail-accounts.create');

// En Blade
@can('mail-accounts.view')
    ...
@endcan

// En Gate
Gate::allows('mail-accounts.update')
```

## Rutas y URLs

### Panel Admin
- Acceso: `/admin/mail-accounts`
- Nombre de ruta: `admin.mail-accounts.index`

### Ejemplos de Generación de URLs
```blade
<a href="{{ route('admin.mail-accounts.index') }}">Cuentas de Correo</a>
```

## Permisos por Rol

### Super Admin
- Acceso total automático (Gate::before)

### Admin
- Necesita permisos explícitos:
  ```sql
  admin.access
  mail-accounts.viewAny
  mail-accounts.view
  mail-accounts.create
  mail-accounts.update
  mail-accounts.delete
  ```

### Manager (Ejemplo)
- Puede asignar solo:
  ```sql
  mail-accounts.viewAny
  mail-accounts.view
  ```

## Flujo de Creación/Edición

1. Usuario hace clic en "Add Account" o "Edit"
2. Modal abre con formulario vacío o pre-llenado
3. Usuario completa campos
4. Validación en vivo con Livewire
5. Al guardar:
   - Validación del lado del servidor
   - Encriptación automática de password
   - Toast de éxito
   - Modal cierra, tabla actualiza

## Deletar Cuenta

1. Usuario hace clic en botón de trash
2. Modal de confirmación
3. Al confirmar: elimina registro, toast de éxito
4. Tabla se actualiza

## Búsqueda y Filtros

**Búsqueda**: Por nombre o email (debounce 500ms)
**Filtro**: Por estado (Todos, Activo, Inactivo)

## Línea de Tiempo

- **Creación**: 22 Feb 2026
- **Estado**: ✅ Completo y probado
- **Tests**: 18/18 ✅
- **Código**: Formateado con Pint ✅

## Archivos Creados/Modificados

### Creados
- `app/Models/MailAccount.php`
- `app/Policies/MailAccountPolicy.php`
- `app/Livewire/Admin/MailAccounts/Index.php`
- `database/factories/MailAccountFactory.php`
- `database/migrations/2026_02_22_214250_create_mail_accounts_table.php`
- `resources/views/admin/mail-accounts/index.blade.php`
- `resources/views/livewire/admin/mail-accounts/index.blade.php`
- `tests/Feature/Admin/MailAccountsTest.php`

### Modificados
- `routes/admin.php` - Ruta agregada
- `database/seeders/PermissionSeeder.php` - Permisos agregados
- `app/Providers/AuthorizationServiceProvider.php` - Gates agregados
- `tests/TestCase.php` - Seeder agregado al setup

## Próximos Pasos Opcionales

1. **Integración con Email**: Crear comando artisan para sincronizar
2. **CRUD Histórico**: Registrar cambios en mail_account_logs
3. **Prueba de Conexión**: Botón para validar credenciales IMAP/SMTP
4. **Rate Limiting**: Limitar sincronización por cuenta
5. **Webhooks**: Notificar cuando fallan sincronizaciones

## Referencia Rápida

| Acción | Ruta | Permiso |
|--------|------|---------|
| Listar | GET `/admin/mail-accounts` | mail-accounts.viewAny |
| Ver Detalle | UI Modal | mail-accounts.view |
| Crear | POST Livewire | mail-accounts.create |
| Editar | PATCH Livewire | mail-accounts.update |
| Eliminar | DELETE Livewire | mail-accounts.delete |
