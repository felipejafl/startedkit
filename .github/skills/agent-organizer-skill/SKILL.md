---
name: agent-organizer
description: Experto en el diseño, orquestación y gestión de sistemas multi-agente (MAS). Especializado en patrones de colaboración de agentes, estructuras jerárquicas e inteligencia de enjambre. Usar al construir equipos de agentes, diseñar la comunicación entre agentes u orquestar flujos de trabajo autónomos.
---

# Organizador de Agentes

## Propósito
Proporciona experiencia en arquitectura de sistemas multi-agente, patrones de coordinación y diseño de flujos de trabajo autónomos. Maneja la descomposición de agentes, protocolos de comunicación y estrategias de colaboración para sistemas de IA complejos.

## Cuándo usar
- Diseñar arquitecturas multi-agente o equipos de agentes
- Implementar protocolos de comunicación entre agentes
- Construir sistemas de agentes jerárquicos o basados en enjambres
- Orquestar flujos de trabajo autónomos entre agentes
- Depurar fallos de coordinación de agentes
- Escalar sistemas de agentes para producción
- Diseñar estrategias de compartición de memoria entre agentes

## Inicio Rápido
**Invoque esta skill cuando:**
- Diseñe arquitecturas multi-agente o equipos de agentes
- Implemente protocolos de comunicación entre agentes
- Construya sistemas de agentes jerárquicos o basados en enjambres
- Orqueste flujos de trabajo autónomos entre agentes
- Escale sistemas de agentes para producción

**NO la invoque cuando:**
- Construya aplicaciones LLM de un solo agente (use ai-engineer)
- Optimice prompts para agentes individuales (use prompt-engineer)
- Gestione ventanas de contexto de agentes (use context-manager)
- Maneje fallos y recuperación de agentes (use error-coordinator)

## Marco de Decisión
```
Diseño de Sistema de Agentes:
├── Tarea única, sin coordinación → Agente único
├── Tareas independientes paralelas → Patrón de pool de trabajadores
├── Tareas dependientes secuenciales → Patrón de pipeline
├── Tareas interdependientes complejas
│   ├── Jerarquía clara → Orquestación jerárquica
│   ├── Colaboración entre pares → Patrón de enjambre/consenso
│   └── Roles dinámicos → Malla de agentes adaptable
└── Humano en el bucle → Patrón de supervisor
```

## Flujos de Trabajo Principales

### 1. Diseño de Equipos de Agentes
1. Descomponer el problema en responsabilidades de agentes
2. Definir capacidades e interfaces de los agentes
3. Diseñar la topología de comunicación (hub, malla, jerarquía)
4. Implementar el protocolo de coordinación
5. Añadir monitorización y observabilidad
6. Probar escenarios de fallo

### 2. Configuración de Comunicación entre Agentes
1. Elegir el formato del mensaje (estructurado, lenguaje natural, híbrido)
2. Definir la estrategia de enrutamiento de mensajes
3. Implementar protocolos de traspaso (handoff)
4. Añadir manejo de reintentos y tiempos de espera (timeouts)
5. Registrar todos los mensajes entre agentes

### 3. Escalar Sistemas de Agentes
1. Analizar cuellos de botella en la arquitectura actual
2. Identificar oportunidades de paralelización
3. Implementar equilibrio de carga entre agentes
4. Añadir pooling de agentes para capacidad de ráfaga
5. Monitorizar la utilización de recursos por agente

## Mejores Prácticas
- Mantener las responsabilidades de los agentes con un único propósito y bien definidas
- Usar protocolos de traspaso explícitos entre agentes
- Implementar interruptores de circuito (circuit breakers) para agentes que fallan
- Registrar toda la comunicación entre agentes para depuración
- Diseñar para una degradación elegante cuando los agentes fallan
- Versionar las interfaces de los agentes para compatibilidad hacia atrás

## Anti-patrones
| Anti-patrón | Problema | Enfoque Correcto |
|--------------|---------|------------------|
| Agente Dios | Un solo agente haciendo todo | Descomponer en agentes especializados |
| Agentes parlanchines | Excesivos mensajes entre agentes | Agrupar comunicaciones, asíncrono donde sea posible |
| Acoplamiento fuerte | Los agentes dependen del estado interno | Usar contratos e interfaces |
| Sin supervisión | Agentes ejecutándose sin supervisión | Añadir supervisor o humano en el bucle |
| Estado mutable compartido | Condiciones de carrera y conflictos | Usar paso de mensajes o event sourcing |

