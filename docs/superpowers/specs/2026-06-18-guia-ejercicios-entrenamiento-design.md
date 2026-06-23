# Diseño: Guía de ejercicios durante el entrenamiento

## Resumen

Conectar la vista `TrainView` del frontend con los datos reales del backend para que, cuando el usuario inicie una sesión de entrenamiento, pueda ver **cómo realizar cada ejercicio** y acceder a **ejemplos en YouTube/Google** desde un drawer de guía reutilizado.

## Contexto

- El backend ya almacena guía detallada en la entidad `Exercises`:
  - `description`
  - `setupInstructions`
  - `executionInstructions`
  - `movementStandards`
  - `commonFaults`
  - `safetyTips`
- El endpoint `GET /trainings/{id}` devuelve el entrenamiento con sus rounds y configuraciones de ejercicios.
- Actualmente esos campos de guía pertenecen solo al grupo `exercise:read:detail`, por lo que **no se incluyen** al serializar un training.
- En el frontend existe `ExerciseGuideDrawer.vue` en `components/admin/` que muestra toda la guía y enlaces a YouTube/Google.
- `TrainView.vue` (vista de usuario durante el entrenamiento) tiene los ejercicios hardcodeados y no usa el drawer.

## Alcance

### Dentro del alcance

1. Backend: exponer los campos de guía de `Exercises` en la respuesta de `GET /trainings/{id}`.
2. Frontend: reutilizar `ExerciseGuideDrawer.vue` en `TrainView.vue`.
3. Frontend: cargar el training real desde el backend usando el `sessionId` de la ruta.
4. Frontend: transformar la estructura de rounds/configuraciones en una lista plana de ejercicios para la UI.
5. Frontend: añadir un botón "Ver guía" que abra el drawer con el ejercicio actual.

### Fuera del alcance

- Crear nuevos endpoints en el backend.
- Modificar la estructura de la base de datos.
- Añadir campos nuevos a los ejercicios (por ejemplo, URL de vídeo propia).
- Cambiar la lógica de completar/saltar ejercicios o el timer.

## Cambios en backend

### Archivo: `src/Entity/Exercises.php`

Añadir `Training::GROUP_READ_DETAIL` a los grupos de serialización de los siguientes campos:

- `description`
- `setupInstructions`
- `executionInstructions`
- `movementStandards`
- `commonFaults`
- `safetyTips`

Ejemplo de cambio:

```php
#[ORM\Column(type: 'text', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?string $description = null;
```

Esto permite que al pedir un training, los ejercicios incluyan su guía completa sin afectar a listados como `GET /exercises`.

## Cambios en frontend

### 1. Reubicar `ExerciseGuideDrawer.vue`

- **Origen:** `src/components/admin/ExerciseGuideDrawer.vue`
- **Destino:** `src/components/common/ExerciseGuideDrawer.vue`
- **Motivo:** El componente es genérico y se usa tanto en administración como en la vista de entrenamiento del usuario.
- **Imports a actualizar:**
  - `src/views/admin/ExercisesView.vue`
  - `src/views/user/TrainView.vue`

### 2. Modificar `src/views/user/TrainView.vue`

#### Cargar training real

- Leer `route.params.sessionId`.
- Al montar la vista, llamar a `trainingService.getById(sessionId)`.
- Mostrar estados de carga y error.

#### Transformar ejercicios

A partir de `training.trainingRounds`, generar una lista plana de ejercicios. Cada elemento contendrá:

- `id`: identificador único de la configuración.
- `name`: nombre del ejercicio.
- `detail`: texto resumen (p. ej. "3 sets x 12 reps").
- `sets`: número de sets.
- `reps`: número de reps o texto para tiempo.
- `hasTimer`: `true` si el ejercicio es por tiempo.
- `targetSeconds`: segundos objetivo (si aplica).
- `exercise`: objeto completo del ejercicio (con guía) para pasar al drawer.

#### Añadir botón "Ver guía"

En la tarjeta del ejercicio actual, añadir un botón secundario "Ver guía" que:

- Establezca el ejercicio seleccionado.
- Abra el `ExerciseGuideDrawer`.

#### Integrar drawer

```vue
<ExerciseGuideDrawer
  v-model="guideOpen"
  :exercise="selectedExercise"
  :loading="false"
/>
```

### 3. Actualizar import en `src/views/admin/ExercisesView.vue`

Cambiar la ruta de import del drawer de `components/admin/` a `components/common/`.

## Data flow

```
LevelView
  │
  ▼
router.push({ name: 'user-train', params: { sessionId: training.id } })
  │
  ▼
TrainView monta
  │
  ▼
trainingService.getById(sessionId)
  │
  ▼
Backend: GET /trainings/{id}
  │
  ▼
Respuesta incluye Training → Rounds → Configs → Exercises (con guía)
  │
  ▼
TrainView transforma a lista plana y renderiza ejercicio actual
  │
  ▼
Usuario pulsa "Ver guía"
  │
  ▼
ExerciseGuideDrawer muestra descripción, preparación, ejecución,
                       estándares, errores comunes, seguridad y
                       enlaces a YouTube/Google
```

## Manejo de errores

| Escenario | Comportamiento |
|-----------|----------------|
| Fallo al cargar el training | Mostrar estado de error con mensaje y botón "Reintentar". |
| `sessionId` inválido o inaccesible | Mostrar error y opción de volver atrás. |
| Ejercicio sin guía | El drawer oculta automáticamente las secciones vacías. |
| Sin conexión a internet para enlaces externos | Los enlaces se abren en navegador externo; el SO/Quasar gestiona el error. |

## Testing

### Backend

- Ejecutar `php bin/phpunit` para verificar que los cambios en grupos de serialización no rompen tests existentes.
- Verificar manualmente con `php bin/console debug:serializer` o una petición a `GET /trainings/{id}` que los campos de guía aparecen.

### Frontend

- Navegar desde `LevelView` a `TrainView` con una sesión activa.
- Comprobar que se cargan los ejercicios reales del backend.
- Pulsar "Ver guía" y comprobar que el drawer muestra la información del ejercicio.
- Comprobar que los botones "Buscar en YouTube" y "Buscar en Google" abren la búsqueda correspondiente.
- Verificar que `ExercisesView` (admin) sigue funcionando tras mover el drawer.

## Dependencias

- Cambio coordinado en frontend y backend.
- No requiere migraciones de base de datos.
- No requiere nuevos endpoints.

## Notas

- Los enlaces a YouTube/Google se generan dinámicamente a partir del nombre del ejercicio usando el componente `ExerciseGuideDrawer` existente.
- Si en el futuro se quieren añadir URLs de vídeo propias, se podrá extender la entidad `Exercises` con un campo nuevo sin modificar este flujo.
