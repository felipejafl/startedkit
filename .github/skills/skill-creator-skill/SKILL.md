---
name: skill-creator
description: Guía para crear skills efectivas. Esta skill debe usarse cuando los usuarios desean crear una nueva skill (o actualizar una existente) que amplíe las capacidades del Agente con conocimientos especializados, flujos de trabajo o integraciones de herramientas.
---

# Creador de Skills

## Propósito

Una meta-skill que guía la creación de skills efectivas y de alta calidad. Proporciona plantillas, mejores prácticas y pautas estructurales para construir skills que mejoren las capacidades de un Agente IA con conocimientos especializados, flujos de trabajo o integraciones de herramientas.

## Cuándo usar

- El usuario quiere crear una nueva skill.
- El usuario quiere actualizar o mejorar una skill existente.
- El usuario pregunta cómo estructurar la documentación de una skill.
- Necesidad de diseñar una skill para un dominio o flujo de trabajo específico.
- Querer asegurar que la skill siga las mejores prácticas.

## Estructura Central de la Skill

### Componentes Requeridos

Cada skill debe tener estos elementos:

1. **Frontmatter**
   ```yaml
   ---
   name: nombre-de-la-skill
   description: Descripción de una línea sobre cuándo usar esta skill
   ---
   ```

2. **Título y Propósito**
   ```markdown
   # Nombre de la Skill
   
   ## Propósito
   Declaración clara y concisa de lo que hace esta skill
   ```

3. **Cuándo usar**
   ```markdown
   ## Cuándo usar
   - Activador específico 1
   - Activador específico 2
   - Contexto donde esto ayuda
   ```

4. **Capacidades Principales**
   ```markdown
   ## Capacidades Principales
   
   ### Experiencia en el Dominio
   - Área de conocimiento clave 1
   - Área de conocimiento clave 2
   
   ### Herramientas y Métodos
   - Técnicas específicas
   - Marcos de trabajo utilizados
   ```

### Componentes Opcionales pero Recomendados

5. **Flujo de Trabajo**
   ```markdown
   ## Flujo de Trabajo
   
   1. Paso 1: Qué hacer primero
   2. Paso 2: Siguiente acción
   3. Paso 3: Entregable final
   ```

6. **Mejores Prácticas**
   ```markdown
   ## Mejores Prácticas
   
   - Haz esto
   - Evita aquello
   - Recuerda esto
   ```

7. **Ejemplos**
   ```markdown
   ## Ejemplos
   
   ### Ejemplo 1: Caso de Uso Común
   **Entrada**: Solicitud del usuario
   **Enfoque**: Cómo manejarlo
   **Salida**: Resultado esperado
   ```

8. **Anti-patrones**
   ```markdown
   ## Anti-patrones
   
   ❌ **No hacer**: Mala práctica
   ✅ **Hacer**: Buena alternativa
   ```

## Flujo de Trabajo para la Creación de Skills

### Paso 1: Definir el Alcance

Pregúntate:
- ¿Qué problema resuelve esta skill?
- ¿Quién la usará?
- ¿Qué activa su uso?
- ¿Cuál es el resultado esperado?

### Paso 2: Identificar el Conocimiento Central

Documenta:
- Terminología específica del dominio
- Conceptos y principios clave
- Patrones comunes en este dominio
- Herramientas y tecnologías involucradas

### Paso 3: Estructurar el Flujo de Trabajo

Mapea:
- Condiciones de entrada
- Proceso paso a paso
- Puntos de decisión
- Criterios de salida y entregables

### Paso 4: Añadir Elementos Prácticos

Incluye:
- Ejemplos del mundo real
- Errores comunes a evitar
- Mejores prácticas del campo
- Criterios de calidad

### Paso 5: Escribir Activadores Claros

Haz que "Cuándo usar" sea específico:
- ✅ "El usuario necesita optimización de consultas SQL para bases de datos PostgreSQL"
- ❌ "El usuario necesita ayuda con la base de datos"

- ✅ "Depuración de interrupciones de producción en sistemas distribuidos"
- ❌ "Corrección de errores"

## Criterios de Calidad de la Skill

### Claridad
- [ ] El nombre se explica por sí mismo.
- [ ] La descripción establece claramente cuándo usarla.
- [ ] El propósito se indica en 1-2 oraciones.
- [ ] Sin jerga técnica sin explicación.

### Integridad
- [ ] Todas las secciones requeridas están presentes.
- [ ] El flujo de trabajo es accionable.
- [ ] Los ejemplos cubren los casos comunes.
- [ ] Se abordan los casos de borde (edge cases).

### Especificidad
- [ ] Los activadores son concretos.
- [ ] Los pasos son lo suficientemente detallados para seguirlos.
- [ ] Las herramientas/métodos se nombran explícitamente.
- [ ] Criterios de éxito definidos.

### Usabilidad
- [ ] Fácil de escanear y navegar.
- [ ] Formato consistente.
- [ ] Orden lógico de las secciones.
- [ ] Referencias cruzadas donde sea útil.

## Plantillas de Skills

### Plantilla de Skill de Dominio Técnico

```markdown
---
name: experto-en-dominio
description: Úselo cuando el usuario necesite [tarea técnica específica] en [tecnología/dominio]
---

# Experto en Dominio

## Propósito

Experto en [dominio] especializado en [áreas específicas]. Ayuda con [problemas clave resueltos].

## Cuándo usar

- El usuario necesita [tarea específica 1]
- Trabajando con [tecnología] y necesita [tipo de ayuda]
- Resolución de problemas de [tipo de problema específico]
- Diseñando [elemento arquitectónico]

## Capacidades Principales

### Experiencia en [Dominio]
- [Tecnología 1] - [versión/detalles]
- [Tecnología 2] - [qué aspectos]
- [Patrón/práctica] - [cuándo/cómo]

### Técnicas Clave
- **[Técnica 1]**: [Qué resuelve]
- **[Técnica 2]**: [Cuándo usar]
- **[Técnica 3]**: [Cómo ayuda]

## Flujo de Trabajo

1. **Entender los Requisitos**
   - Clarificar [aspectos específicos]
   - Identificar [restricciones]

2. **Aplicar Patrones de [Dominio]**
   - Usar [patrón 1] para [escenario]
   - Considerar [trade-off/compromiso]

3. **Implementar la Solución**
   - Seguir [mejor práctica]
   - Asegurar [criterios de calidad]

4. **Validar**
   - Probar [aspectos]
   - Verificar [requisitos cumplidos]

## Mejores Prácticas

- **[Práctica 1]**: [Razonamiento]
- **[Práctica 2]**: [Beneficio]
- **[Práctica 3]**: [Por qué es importante]

## Patrones Comunes

### [Patrón 1]
**Cuándo**: [Escenario]
**Cómo**: [Enfoque de implementación]
**Por qué**: [Beneficios]

### [Patrón 2]
**Cuándo**: [Escenario]
**Cómo**: [Enfoque de implementación]
**Por qué**: [Beneficios]

## Anti-patrones

❌ **No hacer**: [Mala práctica]
   - Por qué falla: [Razón]
   - Mejor enfoque: [Alternativa]

❌ **Evitar**: [Error común]
   - Problema: [Qué sale mal]
   - En su lugar: [Forma correcta]

## Ejemplos

### Ejemplo 1: [Escenario Común]
**Contexto**: [Situación]
**Enfoque**: [Pasos de la solución]
**Resultado**: [Resultado]

## Herramientas y Tecnologías

- **[Herramienta 1]**: [Versión] - [Para qué usarla]
- **[Herramienta 2]**: [Versión] - [Para qué usarla]
- **[Framework]**: [Versión] - [Características clave utilizadas]
```

### Plantilla de Skill de Proceso/Flujo de Trabajo

```markdown
---
name: especialista-en-procesos
description: Úselo cuando el usuario necesite [proceso/flujo de trabajo específico] para [resultado]
---

# Especialista en Procesos

## Propósito

Guía [proceso específico] para lograr [resultado específico]. Asegura [aspectos de calidad] a través de [metodología].

## Cuándo usar

- Necesidad de [ejecutar proceso]
- Querer asegurar [resultado de calidad]
- Trabajando en [escenario que requiere este proceso]

## Proceso Central

### Fase 1: [Nombre]
**Objetivo**: [Qué lograr]

Pasos:
1. [Acción 1]: [Detalles]
2. [Acción 2]: [Detalles]
3. [Acción 3]: [Detalles]

**Salidas**: [Lo que tienes después de esta fase]

### Fase 2: [Nombre]
**Objetivo**: [Qué lograr]

Pasos:
1. [Acción 1]: [Detalles]
2. [Acción 2]: [Detalles]

**Salidas**: [Lo que tienes después de esta fase]

### Fase 3: [Nombre]
**Objetivo**: [Qué lograr]

Pasos:
1. [Acción 1]: [Detalles]
2. [Acción 2]: [Detalles]

**Entregable**: [Producto final]

## Puntos de Decisión

### Cuándo [Decisión]
- Si [condición], entonces [opción A]
- Si [condición], entonces [opción B]

## Puertas de Calidad

Después de cada fase, verificar:
- [ ] [Criterio 1]
- [ ] [Criterio 2]
- [ ] [Criterio 3]

## Mejores Prácticas

- **[Práctica]**: [Por qué importa]
- **[Práctica]**: [Impacto en la calidad]

## Errores Comunes (Pitfalls)

- **Error**: [Qué hace mal la gente]
  - **Impacto**: [Qué sucede]
  - **Solución**: [Cómo evitarlo]
```

## Consejos de Escritura

### Sé Específico
❌ "Úselo cuando trabaje con bases de datos"
✅ "Úselo cuando optimice consultas SQL para bases de datos de producción PostgreSQL 14+"

### Sé Accionable
❌ "Piensa en la seguridad"
✅ "Ejecuta un escaneo OWASP ZAP y revisa todos los hallazgos de severidad ALTA"

### Sé Estructurado
Usa niveles de encabezado consistentes:
- `##` para secciones principales
- `###` para subsecciones
- `####` para desgloses detallados

### Usa Indicadores Visuales
- ✅ para buenas prácticas
- ❌ para anti-patrones
- 🔍 para pasos de investigación
- ⚠️ para advertencias
- 💡 para consejos

### Incluye Contexto
No solo enumeres qué hacer, explica por qué:
```markdown
## En lugar de:
- Usa pooling de conexiones

## Escribe:
- **Usa pooling de conexiones** (pg-pool para PostgreSQL)
  - Reduce la sobrecarga de conexión en un 80%
  - Crítico para aplicaciones con >100 usuarios concurrentes
  - Configura tamaño del pool = (conteo de núcleos × 2) + effective_spindle_count
```

## Mantenimiento de la Skill

### Cuándo Actualizar
- Se lanza una nueva versión de la tecnología central.
- Surgen mejores prácticas en el campo.
- La retroalimentación del usuario revela brechas.
- Se crean skills relacionadas (referencia cruzada).

### Control de Versiones
Considera añadir al frontmatter:
```yaml
---
name: nombre-de-la-skill
description: Descripción de una línea
---
```

## Integración de Skills

### Referencias Cruzadas
Enlace a skills relacionadas:
```markdown
## Skills Relacionadas
- Usa [[debugger-skill]] cuando surjan problemas
- Combina con [[performance-engineer-skill]] para optimización
- Precede con [[architect-reviewer-skill]] para validación de diseño
```

### Composición de Skills
Los flujos de trabajo complejos pueden encadenar skills:
```markdown
## Flujo de Trabajo
1. Usa [[requirement-analyst]] para reunir necesidades
2. Aplica esta skill para la implementación
3. Usa [[code-reviewer]] para el aseguramiento de la calidad
4. Usa [[deployment-engineer]] para el envío
```

## Ejemplos

### Ejemplo 1: Creación de una Skill de Pro de Python

**Contexto**: Necesidad de una skill para desarrollo avanzado en Python

**Proceso**:
1. Definir alcance: Python 3.11+ con enfoque en FastAPI y seguridad de tipos
2. Identificar activadores: "Python moderno", "type hints", "FastAPI"
3. Estructurar capacidades principales:
   - Características de Python 3.11+ (sentencias match, mejoras de tipado)
   - Patrones del framework FastAPI
   - Mejores prácticas de anotación de tipos
4. Añadir flujo de trabajo: Diseñar API → Modelar tipos → Implementar rutas → Probar
5. Incluir ejemplos: Ruta de FastAPI con anotaciones de tipo completas

**Resultado**: Una skill enfocada y accionable para el desarrollo moderno en Python

### Ejemplo 2: Creación de una Skill de Flujo de Trabajo de Git

**Contexto**: Necesidad de codificar la estrategia de ramificación del equipo en git

**Proceso**:
1. Definir alcance: Flujo de trabajo de Git para el desarrollo de funcionalidades
2. Identificar activadores: "crear rama", "hacer PR", "flujo de trabajo git"
3. Estructurar como fases:
   - Creación de ramas
   - Ciclo de desarrollo
   - Proceso de PR
   - Estrategia de fusión (merge)
4. Añadir puntos de decisión: Cuándo rebasar (rebase) vs fusionar (merge)
5. Incluir ejemplos: Flujo estándar de desarrollo de funcionalidades

**Resultado**: Guía procedimental clara para un uso consistente de git

## Lista de Verificación de Validación

Antes de finalizar una skill, verifica:

### Estructura
- [ ] Frontmatter completo (nombre, descripción).
- [ ] Título y propósito claros.
- [ ] La sección "Cuándo usar" tiene activadores específicos.
- [ ] Capacidades principales bien definidas.

### Contenido
- [ ] La información es precisa y actual.
- [ ] Los ejemplos son realistas y útiles.
- [ ] Las mejores prácticas están justificadas.
- [ ] Los anti-patrones muestran alternativas.

### Usabilidad
- [ ] Se puede escanear y encontrar información rápidamente.
- [ ] Las secciones fluyen lógicamente.
- [ ] El formato es consistente.
- [ ] Las referencias cruzadas son correctas.

### Calidad
- [ ] Sin errores ortográficos/gramaticales.
- [ ] Términos técnicos definidos.
- [ ] Los ejemplos de código (si los hay) son correctos.
- [ ] Cumple con todos los criterios de calidad anteriores.

## Meta: Acerca de esta Skill

Esta skill misma demuestra los principios que enseña:
- Frontmatter y estructura claros.
- Activadores específicos en "Cuándo usar".
- Flujos de trabajo accionables.
- Ejemplos concretos.
- Criterios de calidad.

Al crear skills, usa esto como guía y plantilla.
