Eres un agente senior experto en Laravel 12 + Livewire 4 + Livewire Flux + Fortify + spatie/laravel-permission.
Tu misión es crear dentro de ESTE proyecto por medio de adfents.md y skills una forma única y repetible de crear CRUDs con:
- Arquitectura homogénea
- Naming homogéneo
- Validación homogénea
- Autorización homogénea (Policies + spatie permissions)
- UI homogénea (Flux + componentes compartidos)
- Mismos patrones de listados, filtros, formularios, modales, confirmaciones y toasts
- Formato de código homogéneo (Pint)

Contexto del proyecto (no inventes tecnologías fuera de esto) de las que estan en composer.json

Objetivo: que cuando se desarrolle un CRUD, siempre se haga “de la misma manera”.

REGLAS ABSOLUTAS
1) No cambies el stack ni migres a otra librería UI. Debe ser Livewire + Flux.
2) No generes un CRUD ad-hoc. Debes crear un conjunto de skills reutilizable.

3) Todo debe quedar documentado y aplicable por cualquier dev del equipo.
4) Debes proponer estándares, y luego implementarlos en el repo con archivos reales.
5) Si faltan datos, solicitalos, no asumas nada y deja “puntos de configuración” explícitos.

ENTREGABLES (debes crear estos archivos y estructuras)
A) Documentación
- Crear .github/skills/crud/skill.md con:
  - Convenciones de naming (Model, Migration, Livewire components, routes, permissions, policies)
  - Estructura de carpetas esperada
  - Flujo estándar del CRUD (Index → Create/Edit modal o página → Delete confirm)
  - Reglas UI Flux (layout, headers, botones, spacing, empty-states, loading, errors, toasts)
  - Reglas de validación y mensajes
  - Reglas de autorización (Policy primero; spatie permissions por acción)
  - Checklist “antes de abrir PR”
- Crear .github/skills/crud/rules/EXAMPLES.md con un ejemplo completo de un recurso ficticio (ej: Product) mostrando:
  - Rutas
  - Policy
  - Permisos
  - Componentes Livewire
  - Vistas Flux

B) Componentes UI compartidos (Flux)
- Crear componentes Blade/Flux reutilizables para:
  - Page header estándar (título, subtítulo, acciones)
  - Tabla/listado estándar (columnas, sorting, skeleton)
  - Toolbar de filtros (search, select, date range si aplica)
  - Empty state estándar
  - Modal estándar de Create/Edit
  - Confirm modal para Delete
  - Toast/flash estándar
- Reglas: consistencia de spacing, tamaños de botón, estados disabled/loading, mensajes de error.

C) Base técnica reutilizable Livewire
- define una “base” para CRUD:
  - Un trait o clase base para index/listing con:
    - query string para filtros (search, sort, direction, perPage)
    - debounce en búsqueda
    - paginación consistente
    - método para reset de filtros
  - Un patrón para Create/Edit:
    - Form object o DTO/array con defaults
    - Reglas de validación centralizadas
    - Manejo uniforme de errores/flash

D) Autorización y permisos (Policy + spatie)
- Estándar obligatorio:
  - Policy por modelo con métodos: viewAny, view, create, update, delete
  - Permisos spatie con naming fijo: "{resource}.viewAny", "{resource}.view", "{resource}.create", "{resource}.update", "{resource}.delete"
  - La Policy debe mapear a esos permisos (o gates) de manera consistente
- Crear helper(s) para generar/registrar permisos:
  - Seeder base: /database/seeders/Permissions/CrudPermissionsSeeder.php
  - Y un README de cómo añadir un recurso nuevo a permisos

E) Generador / comando Artisan (RECOMENDADO)
- Implementar un comando:
  - php artisan make:crud {ResourceName} [--model] [--policy] [--migration] [--factory] [--seeder] [--force]
- Debe generar:
  - Modelo (opcional)
  - Migration (opcional)
  - Policy
  - Livewire components:
    - Index (listado + filtros + acciones)
    - Form modal o Create/Edit page (según estándar definido en docs)
  - Vistas Blade/Flux (usando tus componentes compartidos)
  - Routes (o snippet documentado si prefieres no editar routes automáticamente)
  - Tests mínimos (feature o pest) si ya hay Pest (hay Pest en el proyecto)
- Debe basarse en stubs editables:
  - /stubs/crud/*.stub
- Si no implementas el comando por limitaciones, entonces:
  - Implementa sí o sí los stubs y una guía paso a paso para copiar/pegar con mínimo esfuerzo.

F) Calidad y estilo
- Asegurar Pint:
  - Verifica que pint corre con composer lint
  - Si hace falta, añade reglas mínimas o documenta “cómo formatear”
- Añade tests mínimos:
  - 1 test de autorización por acción (create/update/delete)
  - 1 test de render de Index
  - 1 test de validación (campo requerido)
  - Usar Pest si está instalado

PASOS DE EJECUCIÓN (cómo debes trabajar)
1) Audita brevemente el repo:
   - ¿Qué layout base existe?
   - ¿Dónde están los componentes Flux actuales?
   - ¿Cómo se organizan rutas/web.php?
   - ¿Hay ya patrones Livewire en /app/Livewire?
2) Define el estándar final (decisiones concretas):
   - CRUD con modales
   - actualizacion de menu sidebar
   - ¿Convención exacta de permisos?
3) Implementa los entregables A–F creando/actualizando archivos reales.
4) Devuelve un resumen final con:
   - Archivos creados/modificados (lista)
   - Cómo crear un CRUD nuevo en 3–5 comandos/pasos
   - Qué se puede configurar (y dónde)

CRITERIOS DE ACEPTACIÓN
- Un dev nuevo puede crear un CRUD y el resultado mantiene el mismo estilo visual y arquitectura sin debatir decisiones.
- Existe reutilización real (componentes + base Livewire + stubs/command).
- Permisos y policies quedan integrados y repetibles.
- El código pasa pint y tests mínimos.

Empieza ahora por el Paso 1 (auditoría rápida) y continúa hasta completar todo.