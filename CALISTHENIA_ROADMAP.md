# ROADMAP CALISTENIA - OverWorkout

## OBJETIVOS POR NIVEL

### 🟢 PRINCIPIANTE (0-6 meses)
**Objetivo:** Construir base de fuerza, aprender patrones de movimiento, desarrollar conciencia corporal

**Metas:**
- Dominar 10-15 flexiones perfectas
- 5-10 dominadas (pull-ups)
- 20-30 sentadillas
- 30-60 segundos plank
- Aprender hollow body position

**Frecuencia:** 3x semana, full body

---

### 🟡 INTERMEDIO (6-18 meses)
**Objetivo:** Progresar a movimientos unilaterales, aumentar volumen, introducir skills básicos

**Metas:**
- 5-10 dominadas con peso / L-sit pull-ups
- Pistol squat (sentadilla una pierna)
- Muscle up en barra
- L-sit 10-20 segundos
- Handstand contra pared

**Frecuencia:** 4x semana, upper/lower split o push/pull/legs

---

### 🔴 EXPERTO (18+ meses)
**Objetivo:** Dominar skills avanzados, fuerza máxima relativa, combinaciones complejas

**Metas:**
- Muscle up con peso / anillas
- Handstand push ups libres
- Front lever / Back lever
- Planche progresiones
- One arm pull-up / One arm push-up
- Human flag

**Frecuencia:** 4-6x semana, split especializado

---

## ESTRUCTURA DE ENTRENAMIENTOS

### FULL BODY (Principiante)
```
A - Push (Empuje)
  - Flexiones 3x8-12
  - Dips asistidos 3x6-10
  - Pike push ups 3x6-10

B - Pull (Tracción)
  - Australian rows 3x10-15
  - Negative pull ups 3x3-5
  - Dead hangs 3x20-30s

C - Legs (Piernas)
  - Sentadillas 3x15-20
  - Lunges 3x10/leg
  - Glute bridges 3x15

D - Core
  - Plank 3x30-45s
  - Dead bugs 3x10/side
  - Hollow body hold 3x15-20s
```

### UPPER/LOWER SPLIT (Intermedio)
**Upper A (Push emphasis):**
- Push ups variantes 4x8-12
- Dips 4x6-10
- Pike push ups / HSPU progresiones 4x5-8
- Core: L-sit practice

**Upper B (Pull emphasis):**
- Pull ups 4x5-10
- Rows 4x8-12
- Muscle up negativos / trabajo técnico
- Core: Dragon flag / windshield wipers

**Lower:**
- Pistol squat progresiones 4x5/leg
- Bulgarian split squats 3x8-12
- Hamstring curls nordic negativos 3x3-5
- Sissy squats 3x8-12

### PUSH/PULL/LEGS (Experto)
**Push:**
- Pseudo planche push ups / Planche lean
- Dips anillas / ponderados
- HSPU (libres o asistidos)
- Planche / L-sit work

**Pull:**
- Front lever / Back lever holds
- Muscle ups (barra o anillas)
- One arm pull up progresiones
- Archer pull ups

**Legs:**
- Pistol squats ponderados
- Nordic curls completos
- Shrimp squats
- Sprints / plyometría

---

## PROGRESIONES CLAVE

### Progresión Dominadas (Pull-ups)
1. Australian rows
2. Negative pull ups (5 segundos bajada)
3. Band assisted pull ups
4. Standard pull ups (3-5 reps)
5. Close/wide/neutral grip variations
6. L-sit pull ups
7. Weighted pull ups
8. Archer / One arm progressions

### Progresión Flexiones (Push-ups)
1. Wall push ups
2. Incline push ups
3. Knee push ups
4. Standard push ups
5. Diamond / Wide variations
6. Archer push ups
7. One arm push up progressions

### Progresión Pistol Squat
1. Air squats profundos
2. Bulgarian split squats
3. Assisted pistol (agarre)
4. Box pistol squat
5. Partial pistol
6. Full pistol squat
7. Weighted pistol

### Progresión Muscle Up
1. High pull ups (pecho a barra)
2. False grip hangs
3. Muscle up negativos
4. Band assisted muscle ups
5. Strict muscle ups
6. Weighted / L-sit muscle ups

### Progresión Handstand
1. Pike push ups
2. Wall walks
3. Chest to wall hold
4. Back to wall hold
5. Freestanding practice
6. Handstand walks
7. HSPU estrictos

---

## RUTINAS DE EJEMPLO

### RUTINA A: "Fuerza Base" (Principiante)
- Calentamiento: 5 min movilidad + 2 rounds circuito ligero
- Fuerza: 4 ejercicios, 3 sets, 8-12 reps
- Core: 3 ejercicios, 2-3 sets
- Enfriamiento: Estiramientos 5-10 min

### RUTINA B: "Skills & Strength" (Intermedio)
- Skill work: 10-15 min (handstand / L-sit practice)
- Fuerza: 4-5 ejercicios, 4 sets, 5-8 reps
- Accesorios: 2-3 ejercicios aislamiento
- Core avanzado: 15 min

### RUTINA C: "High Intensity" (Experto)
- Warm up específico: 10 min
- Fuerza máxima: 5-6 ejercicios, 3-5 sets, 3-6 reps
- Skills: 20 min (planche / front lever)
- Conditioning: 10-15 min circuito metabolico

---

## CATEGORÍAS DE ENTRENAMIENTOS EN BD

Necesitamos crear tabla `training_programs`:

```sql
training_programs:
  - id
  - name (ej: "Fuerza Base Calistenia")
  - discipline (calisthenics)
  - level (beginner/intermediate/expert)
  - goal (strength/hypertrophy/skills/endurance)
  - frequency (3x, 4x, 5x semana)
  - duration_weeks (4, 8, 12 semanas)
  - description
  - is_active

training_sessions:
  - id
  - program_id
  - name ("Día A - Push", "Día 1 - Fuerza")
  - day_number
  - type (strength/skill/cardio/mixed)
  - estimated_duration (45, 60, 90 min)

session_exercises:
  - id
  - session_id
  - exercise_id
  - order_position
  - sets
  - reps_min
  - reps_max
  - rest_seconds
  - notes
```
