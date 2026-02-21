# 🔐 Cómo Autorizar el Primer Super-Admin

## 3 Formas de Crear el Super-Admin

### ✅ **Opción 1: Comando Interactivo (RECOMENDADO)**

```bash
# 1. Primero ejecutar seeders para crear roles y permisos
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder

# 2. Luego crear el super-admin de forma segura e interactiva
php artisan admin:setup

# Te preguntará por:
# - Email del admin
# - Nombre del admin
# - Contraseña (solicitada 2 veces, sin mostrar texto)
```

**Ventajas:**
- ✅ Contraseña NO se almacena en archivos (.env)
- ✅ Validación de email y contraseña
- ✅ Confirmación de contraseña
- ✅ Puede ejecutarse múltiples veces (actualiza si existe)
- ✅ Muestra tabla de confirmación
- ✅ Recomendado para **PRODUCCIÓN**

---

### 📋 **Opción 2: Variables de Entorno (Desarrollo)**

Configura el archivo `.env`:

```env
# .env
ADMIN_EMAIL=admin@tuempresa.com
ADMIN_NAME=Mi Administrador
ADMIN_PASSWORD=Contraseña123!Segura
```

Luego ejecuta todos los seeders:

```bash
php artisan migrate
php artisan db:seed
```

**Ventajas:**
- ✅ Automatizado
- ✅ Fácil para desarrollo

**Desventajas:**
- ❌ Contraseña en .env (inseguro para producción)
- ❌ Si commiteas .env, queda la contraseña en histórico git

---

### 🔗 **Opción 3: Página de Setup (Sin Auth)**

Para producción cuando NO tienes acceso a terminal, puedes crear una ruta de instalación:

```php
// routes/web.php - SOLO para primera ejecución
Route::get('/install', function() {
    // Verificar que no exista super-admin
    if (\App\Models\User::role('super-admin')->exists()) {
        abort(404);
    }
    return view('install');
})->name('install');
```

Luego eliminar la ruta después de crear el admin (seguridad).

---

## 🚀 FLUJO RECOMENDADO

### En Desarrollo

```bash
# 1. Ejecutar migraciones
php artisan migrate:fresh

# 2. Crear roles y permisos
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder

# 3. Crear super-admin de forma segura
php artisan admin:setup

# 4. Iniciar servidor
php artisan serve
```

### En Producción

```bash
# 1. Ejecutar migraciones
php artisan migrate

# 2. Ejecutar seeders de permisos y roles
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder

# 3. Crear super-admin POR SSH/TERMINAL
php artisan admin:setup

# La contraseña NUNCA se ve en logs o .env
```

---

## 🛡️ Variables de Seguridad

Si usas Opción 2 (variables de entorno), **NUNCA**:

```bash
# ❌ MAL - No commits credenciales
git add .env
git commit -m "Add admin credentials"
git push

# ✅ BIEN - Mantener .env.example sin credenciales
git add .env.example
git commit

# ✅ BIEN - Agregar .env a .gitignore
echo ".env" >> .gitignore
```

---

## 📱 Después de Crear el Super-Admin

1. **Acceder al panel:**
   ```
   http://localhost:8000/login
   ```

2. **Usar credenciales creadas:**
   ```
   Email: admin@example.com (o tu email)
   Password: Tu contraseña
   ```

3. **Acceder al admin panel:**
   ```
   http://localhost:8000/admin
   ```

4. **Crear más usuarios/roles desde el panel**

---

## ⚠️ Recuperación si Olvidas Contraseña

Si olvidaste la contraseña del super-admin, puedes:

### Opción A: Resetear via Commands
```bash
# Ejecutar nuevamente para cambiar contraseña
php artisan admin:setup

# Ingresa el mismo email, contraseña nueva
```

### Opción B: Via Tinker
```bash
php artisan tinker

# Cambiar contraseña
>>> $user = \App\Models\User::where('email', 'admin@example.com')->first();
>>> $user->update(['password' => bcrypt('nuevacontraseña')]);
>>> exit
```

### Opción C: Via Migration (Último recurso)
```bash
# Crear migration que resetea admin
php artisan make:migration reset_admin_password

# En la migration:
\App\Models\User::where('email', 'admin@example.com')
    ->update(['password' => bcrypt('temporal123')]);
```

---

## ✅ Checklist de Setup

- [ ] Ejecutar `php artisan migrate`
- [ ] Ejecutar `php artisan admin:setup`
- [ ] Verificar usuario creado: `SELECT * FROM users WHERE email='admin@example.com'`
- [ ] Login en `/login` ✅
- [ ] Acceder a `/admin` ✅
- [ ] Crear un usuario de prueba desde `/admin/users` ✅

---

## 🔍 Debugging

Si tienes problemas:

```bash
# Ver todos los usuarios
php artisan tinker
>>> \App\Models\User::all();

# Ver roles de un usuario
>>> $user = \App\Models\User::find(1);
>>> $user->roles;

# Ver permisos de un usuario
>>> $user->permissions;

# Dar permiso manualmente
>>> $user->givePermissionTo('admin.access');
```

---

**Recomendación Final:** Usa **Opción 1 (admin:setup)** para producción. Es segura, interactiva y no expone credenciales.
