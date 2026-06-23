# Guía de ejercicios durante el entrenamiento — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Conectar `TrainView` con datos reales del backend y permitir al usuario abrir la guía de cada ejercicio (descripción, preparación, ejecución, errores, seguridad, enlaces a YouTube/Google) durante la sesión de entrenamiento.

**Architecture:** Reutilizar el componente `ExerciseGuideDrawer` existente, moverlo a componentes comunes, exponer los campos de guía de `Exercises` en la serialización de `Training::GROUP_READ_DETAIL`, y transformar la respuesta del backend en una lista plana de ejercicios dentro de `TrainView`.

**Tech Stack:** Symfony 7 (backend), Vue 3 + Quasar (frontend), PHPUnit, Vitest.

---

## File Structure

| File | Responsibility |
|------|----------------|
| `src/Entity/Exercises.php` | Añadir `Training::GROUP_READ_DETAIL` a los grupos de serialización de los campos de guía. |
| `src/components/common/ExerciseGuideDrawer.vue` | Componente reutilizable que muestra la guía del ejercicio (movido desde `admin/`). |
| `src/views/admin/ExercisesView.vue` | Actualizar el import del drawer a la nueva ruta común. |
| `src/views/user/TrainView.vue` | Cargar training real, transformar ejercicios, añadir botón "Ver guía" e integrar el drawer. |

---

## Task 1: Backend — Exponer guía en la serialización de training

**Files:**
- Modify: `src/Entity/Exercises.php:55-77`

**Context:** Los campos de guía actualmente solo se serializan con el grupo `exercise:read:detail`. Al pedir un training, los ejercicios se incluyen con el grupo `training:read:detail`, por lo que la guía no llega al frontend.

- [ ] **Step 1: Añadir `Training::GROUP_READ_DETAIL` a `description`**

```php
#[ORM\Column(type: 'text', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?string $description = null;
```

- [ ] **Step 2: Añadir `Training::GROUP_READ_DETAIL` a `setupInstructions`**

```php
#[ORM\Column(type: 'text', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?string $setupInstructions = null;
```

- [ ] **Step 3: Añadir `Training::GROUP_READ_DETAIL` a `executionInstructions`**

```php
#[ORM\Column(type: 'text', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?string $executionInstructions = null;
```

- [ ] **Step 4: Añadir `Training::GROUP_READ_DETAIL` a `movementStandards`**

```php
#[ORM\Column(type: 'text', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?string $movementStandards = null;
```

- [ ] **Step 5: Añadir `Training::GROUP_READ_DETAIL` a `commonFaults`**

```php
#[ORM\Column(type: 'json', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?array $commonFaults = [];
```

- [ ] **Step 6: Añadir `Training::GROUP_READ_DETAIL` a `safetyTips`**

```php
#[ORM\Column(type: 'text', nullable: true)]
#[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
private ?string $safetyTips = null;
```

- [ ] **Step 7: Verificar que `Training` está importado**

Asegurarse de que la parte superior del archivo tiene:

```php
use App\Entity\Training;
```

Ya debería estar importado porque `Exercises.php` ya referencia `Training::GROUP_READ_DETAIL` en otros atributos.

- [ ] **Step 8: Ejecutar tests del backend**

Run:
```bash
php bin/phpunit
```

Expected: `OK (679 tests, 5401 assertions)` o similar. Si falla algún test de serialización, revisar que los nuevos campos no afecten aserciones existentes.

- [ ] **Step 9: Commit backend**

```bash
git add src/Entity/Exercises.php
git commit -m "feat(api): incluir guía de ejercicios en serialización de training"
```

---

## Task 2: Frontend — Mover `ExerciseGuideDrawer` a componentes comunes

**Files:**
- Move: `src/components/admin/ExerciseGuideDrawer.vue` → `src/components/common/ExerciseGuideDrawer.vue`

**Context:** El componente es genérico y se usará tanto en administración como en la vista de entrenamiento del usuario.

- [ ] **Step 1: Mover el archivo**

Run:
```bash
cd C:/Projects/overworkout-front
mv src/components/admin/ExerciseGuideDrawer.vue src/components/common/ExerciseGuideDrawer.vue
```

- [ ] **Step 2: Verificar que el componente no tiene referencias hardcodeadas a admin**

Revisar que no hay imports ni rutas específicas de `admin` dentro del componente. El componente actual ya es genérico, no debería necesitar cambios.

- [ ] **Step 3: Commit del movimiento**

```bash
git add src/components/admin/ExerciseGuideDrawer.vue src/components/common/ExerciseGuideDrawer.vue
git commit -m "refactor(front): mover ExerciseGuideDrawer a componentes comunes"
```

---

## Task 3: Frontend — Actualizar import en `ExercisesView.vue`

**Files:**
- Modify: `src/views/admin/ExercisesView.vue`

**Context:** La vista de administración debe apuntar a la nueva ubicación del drawer.

- [ ] **Step 1: Leer el import actual**

Localizar la línea similar a:

```js
import ExerciseGuideDrawer from '@/components/admin/ExerciseGuideDrawer.vue'
```

- [ ] **Step 2: Cambiar la ruta del import**

```js
import ExerciseGuideDrawer from '@/components/common/ExerciseGuideDrawer.vue'
```

- [ ] **Step 3: Verificar que el componente sigue registrado/usado correctamente**

Buscar en el archivo `<ExerciseGuideDrawer` y confirmar que las props (`v-model`, `:exercise`, `:loading`) no cambian.

- [ ] **Step 4: Commit**

```bash
git add src/views/admin/ExercisesView.vue
git commit -m "refactor(front): actualizar import de ExerciseGuideDrawer en admin"
```

---

## Task 4: Frontend — Conectar `TrainView.vue` con datos reales y añadir guía

**Files:**
- Modify: `src/views/user/TrainView.vue`

**Context:** Actualmente `TrainView` tiene un array `exercises` hardcodeado y no carga el training desde el backend. Hay que reemplazarlo por datos reales y añadir el drawer.

### Paso 4.1: Añadir imports necesarios

- [ ] **Step 1: Reemplazar el bloque de imports `<script setup>`**

Cambiar:

```js
import { computed, ref, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
```

Por:

```js
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { trainingService } from '@/services/trainings'
import ExerciseGuideDrawer from '@/components/common/ExerciseGuideDrawer.vue'
```

### Paso 4.2: Añadir estado y helpers

- [ ] **Step 2: Añadir variables de estado después de `const router = useRouter()`**

```js
const route = useRoute()
const $q = useQuasar()

const sessionId = computed(() => Number(route.params.sessionId))
const training = ref(null)
const isLoading = ref(true)
const error = ref('')
const guideOpen = ref(false)
const selectedExercise = ref(null)
```

- [ ] **Step 3: Reemplazar el array hardcodeado `exercises` por una propiedad computada**

Eliminar:

```js
const exercises = [
  { name: 'Flexiones', detail: '3 sets x 12 reps', reps: '3x12', hasTimer: false },
  // ...
]
```

Añadir:

```js
const exercises = computed(() => {
  if (!training.value?.trainingRounds) return []

  const list = []
  training.value.trainingRounds.forEach((round) => {
    round.trainingExerciseConfigurations?.forEach((config) => {
      const exercise = config.exercise
      if (!exercise) return

      const isTimeBased = config.reps === null && config.maxTimeForReps !== null
      const repsText = isTimeBased
        ? `${config.sets}x${config.maxTimeForReps}"`
        : `${config.sets}x${config.reps}`

      list.push({
        id: config.id,
        name: exercise.name,
        detail: `${config.sets} sets x ${isTimeBased ? config.maxTimeForReps + ' seg' : config.reps + ' reps'}`,
        sets: config.sets,
        reps: isTimeBased ? config.maxTimeForReps : config.reps,
        hasTimer: isTimeBased,
        targetSeconds: isTimeBased ? config.maxTimeForReps : null,
        exercise,
      })
    })
  })

  return list
})
```

### Paso 4.3: Añadir función de carga

- [ ] **Step 4: Añadir `fetchTraining` antes de los handlers de eventos**

```js
const fetchTraining = async () => {
  isLoading.value = true
  error.value = ''

  try {
    const data = await trainingService.getById(sessionId.value)
    training.value = data
  } catch (err) {
    error.value = err.response?.data?.error || 'Error al cargar la sesión de entrenamiento'
    console.error('Error cargando training:', err)
    $q.notify({
      type: 'negative',
      message: error.value,
      position: 'top',
    })
  } finally {
    isLoading.value = false
  }
}
```

- [ ] **Step 5: Añadir `onMounted`**

Al final del script, antes de `onUnmounted`:

```js
onMounted(() => {
  fetchTraining()
})
```

### Paso 4.4: Añadir función para abrir guía

- [ ] **Step 6: Añadir `openGuide` después de `fetchTraining`**

```js
const openGuide = () => {
  selectedExercise.value = currentExercise.value?.exercise || null
  if (selectedExercise.value) {
    guideOpen.value = true
  }
}
```

### Paso 4.5: Añadir estados de carga y error en el template

- [ ] **Step 7: Añadir estados de carga/error en `<main>`**

Dentro de `<main class="train-view__content">`, antes del `<div v-if="!hasStarted"`, añadir:

```vue
<div v-if="isLoading" class="train-start animate-fadeIn">
  <q-spinner color="primary" size="48px" />
  <p class="mobile-body q-mt-md">Cargando sesión...</p>
</div>

<div v-else-if="error" class="train-start animate-fadeIn">
  <q-icon name="error_outline" size="48px" color="negative" />
  <p class="mobile-body q-mt-md" style="text-align: center">{{ error }}</p>
  <button class="btn-mobile btn-mobile--primary" @click="fetchTraining">Reintentar</button>
  <button class="btn-mobile btn-mobile--ghost" @click="goBack">Volver</button>
</div>

<div v-else-if="!hasStarted" class="train-start animate-fadeIn">
```

Y cambiar el cierre del `v-if="!hasStarted"` para que el `v-else` (entrenamiento activo) use `v-else-if="hasStarted"`.

### Paso 4.6: Añadir botón "Ver guía" en la tarjeta de ejercicio

- [ ] **Step 8: Añadir botón en `exercise-card__actions`**

Después del botón "Saltar", añadir:

```vue
<button class="btn-mobile btn-mobile--ghost" @click="openGuide">
  <q-icon name="help_outline" size="18px" class="q-mr-sm" />
  Ver guía
</button>
```

### Paso 4.7: Integrar el drawer

- [ ] **Step 9: Añadir `<ExerciseGuideDrawer>` al final del template**

Después de cerrar `</main>` y antes de `</div>` del `train-view`:

```vue
<ExerciseGuideDrawer
  v-model="guideOpen"
  :exercise="selectedExercise"
  :loading="false"
/>
```

### Paso 4.8: Ajustar summary dinámico en pantalla de inicio (opcional pero recomendado)

- [ ] **Step 10: Hacer dinámico el resumen de la sesión**

Cambiar:

```vue
<li><q-icon name="sports_gymnastics" size="20px" /> 6 ejercicios</li>
```

Por:

```vue
<li><q-icon name="sports_gymnastics" size="20px" /> {{ exercises.length }} ejercicios</li>
```

Y opcionalmente mostrar duración estimada si existe:

```vue
<li>
  <q-icon name="schedule" size="20px" />
  ~{{ training?.estimatedDurationMin || 45 }} minutos
</li>
```

### Paso 4.9: Verificar y ejecutar linter

- [ ] **Step 11: Ejecutar lint del frontend**

Run:
```bash
cd C:/Projects/overworkout-front
npm run lint
```

Expected: sin errores. Si hay warnings, corregir.

- [ ] **Step 12: Commit**

```bash
git add src/views/user/TrainView.vue
git commit -m "feat(front): conectar TrainView con training real y añadir guía de ejercicios"
```

---

## Task 5: Verificación manual del backend

- [ ] **Step 1: Verificar que los campos de guía aparecen en la respuesta**

Si la base de datos local está disponible y migrada, ejecutar:

```bash
php bin/console doctrine:migrations:migrate
```

Luego iniciar el servidor:

```bash
cd C:/Projects/overworkout-back
symfony server:start
```

O:

```bash
php -S localhost:8000 -t public/
```

Hacer una petición autenticada a:

```http
GET http://localhost:8000/trainings/{id}
Authorization: Bearer <token>
```

Expected: en `trainingRounds[].trainingExerciseConfigurations[].exercise` deben aparecer `description`, `setupInstructions`, `executionInstructions`, `movementStandards`, `commonFaults` y `safetyTips`.

- [ ] **Step 2: Si no hay datos de ejercicios en BD, usar datos de prueba**

Si la BD local no tiene ejercicios con guía, ejecutar los seeders pertinentes o insertar un ejercicio de prueba con esos campos rellenos.

---

## Task 6: Verificación manual del frontend

- [ ] **Step 1: Iniciar el frontend**

Run:
```bash
cd C:/Projects/overworkout-front
npm run dev
```

- [ ] **Step 2: Navegar a un nivel con sesiones activas**

Ir a `user/programs/:programId/levels/:levelId`, desbloquear una fase activa y pulsar una sesión.

- [ ] **Step 3: Comprobar carga real**

Verificar que `TrainView` muestra los ejercicios reales del backend en lugar de los datos hardcodeados.

- [ ] **Step 4: Abrir guía**

Pulsar "Ver guía" en un ejercicio. Comprobar que el drawer se abre por la derecha y muestra:
- Descripción
- Preparación
- Ejecución
- Estándares del movimiento
- Errores comunes
- Consejos de seguridad
- Botones "Buscar en YouTube" y "Buscar en Google"

- [ ] **Step 5: Comprobar enlaces externos**

Pulsar "Buscar en YouTube" y verificar que se abre una nueva pestaña con la búsqueda del nombre del ejercicio.
Pulsar "Buscar en Google" y verificar que se abre una búsqueda de vídeos.

- [ ] **Step 6: Verificar admin**

Ir a la vista de ejercicios del panel de administración y comprobar que el drawer sigue funcionando tras mover el componente.

---

## Self-Review Checklist

- [ ] **Spec coverage:** ¿Cada sección del spec tiene una tarea asociada?
  - Exponer guía en backend → Task 1.
  - Reutilizar drawer → Tasks 2 y 3.
  - Conectar TrainView → Task 4.
  - Testing → Tasks 5 y 6.
- [ ] **Placeholder scan:** No hay TBDs, TODOs ni pasos vagos.
- [ ] **Type consistency:** Las props del drawer (`exercise`, `loading`, `v-model`) se usan igual en admin y en TrainView. La estructura transformada de ejercicios incluye el campo `exercise` completo para el drawer.
- [ ] **Path consistency:** Todas las rutas de archivos usan barras normales y apuntan a `C:/Projects/overworkout-front/src/...`.
