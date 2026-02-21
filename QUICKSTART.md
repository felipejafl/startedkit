# 🚀 GUÍA RÁPIDA: INICIAR EL ADMIN PANEL

## Paso 1: Preparar Base de Datos

```bash
# Limpiar y migrar (desarrollo)
php artisan migrate:fresh

# O solo migrar (producción)
php artisan migrate
```

## Paso 2: Crear Permisos y Roles

```bash
# Ejecutar seeders de permisos y roles
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder

# Verificar (opcional)
php artisan tinker
>>> Spatie\Permission\Models\Permission::count() // Debe mostrar 16
>>> Spatie\Permission\Models\Role::count() // Debe mostrar 3
>>> exit
```

## Paso 3: Crear Super-Admin (RECOMENDADO)

```bash
# Ejecutar comando interactivo
php artisan admin:setup

# Te pedirá:
# Email: admin@ejemplo.com
# Name: Mi Administrador
# Password: (escribir, no se ve)
# Confirm Password: (escribir de nuevo)
```

## Paso 4: Iniciar Servidor

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Assets watch (si necesitas cambios CSS/JS)
npm run dev
```

## Paso 5: Acceder

- **URL**: http://localhost:8000
- **Login**: http://localhost:8000/login
- **Admin Panel**: http://localhost:8000/admin

**Credenciales:**
- Email: admin@ejemplo.com (la que creaste)
- Password: La que ingresaste

---

## ✨ Lo Que Ves en el Admin Panel

### 📊 Dashboard (`/admin`)
- Cards con estadísticas (usuarios totales, activos, roles, permisos)
- Lista de usuarios recientes
- Quick links a usuarios, roles, permisos

### 👥 Usuarios (`/admin/users`)
- Tabla de usuarios con búsqueda y filtros
- Crear usuario (botón "Create User")
- Editar usuario (lápiz)
- Activar/desactivar (icono de check/x)
- Asignar roles y permisos (escudo)

### 🎭 Roles (`/admin/roles`)
- Tabla de roles
- Crear rol (botón "Create Role")
- Ver cuántos usuarios y permisos tienen
- Edit/Delete
- Asignar permisos a rol (candado)

### 🔑 Permisos (`/admin/permissions`)
- Tabla de permisos
- Crear permiso (botón "Create Permission")
- Ver cuántos roles tienen el permiso
- Edit/Delete

---

## 🧪 Pruebas Rápidas

### 1. Crear Usuario de Prueba
1. Ir a `/admin/users`
2. Click "Create User"
3. Llenar: Name, Email, marcar "Active"
4. Click "Create"
5. ✅ Usuario creado

### 2. Asignar Rol a Usuario
1. En `/admin/users`, click escudo en la fila del usuario
2. Marcar "admin" (o "manager")
3. Click "Save"
4. ✅ Rol asignado

### 3. Crear Rol Personalizado
1. Ir a `/admin/roles`
2. Click "Create Role"
3. Ingresar nombre (ej: "moderator")
4. Click "Create"
5. Click candado para asignar permisos
6. ✅ Rol creado

### 4. Login con Usuario Nuevo
1. Logout (top right)
2. Login con el nuevo usuario
3. Intentar acceder a `/admin`
4. ✅ Si usuario no tiene admin.access → No puede acceder
5. ✅ Si usuario tiene admin.access → Puede acceder

---

## 🔒 Seguridad

### ✅ Implementado
- Super-admin tiene acceso a todo (Gate bypass)
- Usuarios inactivos no pueden acceder (`is_active = false`)
- No se pueden modificar permisos sin permiso correspondiente
- No se puede asignar super-admin sin serlo
- No se pueden borrar roles críticos (super-admin, admin)
- Cada acción requiere permiso específico

### 🚨 Nunca Hagas Esto
```bash
# ❌ No: Commiteares credenciales
git add .env
git commit "Add admin password"

# ✅ Sí: Usa .env.example sin credenciales
git add .env.example
```

---

## 📚 Más Información

- **Setup Detallado**: Ver `SETUP_SUPERADMIN.md`
- **Resumen Módulo**: Ver `ADMIN_MODULE_SUMMARY.md`
- **Estado Tests**: Ver `ADMIN_TESTS_STATUS.md`

---

## ⚠️ Troubleshooting

### ❌ "Route not defined: admin.dashboard"
**Solución**: Clearar cache de rutas
```bash
php artisan route:clear
php artisan config:clear
```

### ❌ "User has no admin.access permission"
**Solución**: Asegurar que el usuario tenga el rol super-admin O el permiso admin.access
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->givePermissionTo('admin.access');
>>> exit
```

### ❌ "No permissions found" en modal
**Solución**: Los permisos no fueron creados. Ejecutar
```bash
php artisan db:seed --class=PermissionSeeder
```

### ❌ "Cannot access /admin" (403 Forbidden)
**Solución**: El usuario no está activo. En DB cambiar `is_active = 1`
```bash
php artisan tinker
>>> User::find(1)->update(['is_active' => true]);
```

---

## 🎯 Próximos Pasos (Opcional)

Después de verificar que funciona:

1. **Agregar feedback visual (Toasts)**
   - Cuando creas usuario: "✅ Usuario creado"
   - Cuando falla: "❌ Error: Email ya existe"

2. **Implementar auditoría**
   - Quién cambió qué y cuándo
   - Activity log

3. **Agregar reset de contraseña**
   - Enviar link de reset a nuevos usuarios
   - En lugar de contraseña temporal

4. **Exportar datos**
   - Descargar usuarios a CSV
   - Descargar roles/permisos

---

## ☑️ Checklist Final

- [ ] Ejecuté `php artisan migrate`
- [ ] Ejecuté `php artisan admin:setup`
- [ ] Ejecuté `php artisan serve`
- [ ] Accedí a http://localhost:8000/login
- [ ] Hice login con el super-admin
- [ ] Accedí a http://localhost:8000/admin
- [ ] Creé un usuario desde el panel
- [ ] Asigné un rol al usuario
- [ ] Ahora puedo usar el admin panel

✅ **Si todo pasó: ¡LISTO!**

---

**Fecha**: 22 Febrero, 2026  
**Status**: 🟢 Operacional
