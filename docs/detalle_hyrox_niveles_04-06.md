# Detalle de contenido — Niveles 4, 5 y 6 — Hyrox Mastery v1

> Documento fuente desde el que se generará el programa Hyrox Mastery (comando
> `app:create-hyrox-mastery`), lote 2 de 2 (niveles 4-6). Continuidad total con
> `docs/detalle_hyrox_niveles_01-03.md` (lote 1: niveles 1-3, benchmarks PFT y
> Simulacro L3, puertas HY1-HY4). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-17-hyrox-mastery-design.md` (§2 formato de
> carrera temporada 26/27, §4.1 benchmarks, §4.2 puertas y tests, §4.3 patrón
> de sesiones A/B/C/D, §5 arco de niveles: N4 Estaciones Open, N5 Bloques
> dobles, N6 Hyrox 3/4).
>
> **Estado de redacción:** COMPLETO. Niveles 4, 5 y 6 con sus 12 sesiones
> (4 fases × A/B/C/D por nivel), tests, re-tests de puertas, skillFocus,
> logros y las 2 fichas de benchmark del lote (Hyrox Simulacro L5 y L6).
> Los simulacros L7-L8 (Open a ritmo, competición) se especifican en el lote
> de niveles 7-8. Pendiente la validación de contenido con el usuario
> (gate humano).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (spec §4.3): A — Run + estaciones de fuerza; B — Ergómetros (SkiErg/Row) + core; C — Simulacro/circuito (en N4-N6 la sesión C de Fase 3 = benchmark embebido del simulacro del nivel); D — Aeróbico Z2 + movilidad.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab hombro/lumbar/tobillo rotado por sesión) → `descarga`. `restAfterBlock` = 60 s en N4-N6 (regla del proyecto: niveles ≤6 → 60 s).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Dosis `/lado` se computan una vez.
- **RIR:** en N4-N6 la fuerza de estación se trabaja con 1-3 RIR (series largas y descanso completo en N4; pares y trenes con fatiga en N5-N6); en circuitos y simulacros el fallo técnico marca el escalado (regla §1.7-Hyrox §5).
- **Equipamiento asumido N4-N6:** gimnasio con SkiErg, remo, sled (push/pull), wall balls (4 y 6 kg), kettlebells/mancuernas, y cinta o exterior para correr. Si no hay SkiErg ni remo, sustituto calórico: `Assault Bike Sprint` o `Echo Bike Sprint` en dosis por tiempo equivalentes (nota en la sesión). Si no hay sled: `Step Up` cargado para el push y `Kettlebell Swing Russian` pesado para el pull (mismo criterio que el lote 1).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (verificada en el lote 1 y re-verificada para este lote, 17-oct-2026). Nombres literales.
- **Convención de nombres de ítems de test:** los ítems que corresponden a ejercicio de catálogo usan su grafía exacta con la carga entre paréntesis (p. ej. `Wall Ball Shot (WB 6 kg)`); los benchmarks se nombran como nombre propio ("HYROX PFT", "Hyrox Simulacro L5", "Hyrox Simulacro L6"); los re-tests de puerta llevan el código HYn como nombre literal.
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.
- **Pesos:** N4 introduce los pesos **Open** oficiales (temporada 26/27) estación a estación; N5 trabaja al 75% Open en pares; N6 al ~65% Open en el simulacro completo de 8 estaciones. Las variantes **Pro** siguen siendo objetivo opcional de niveles 7-8 (fuera de este lote).
- **Running semanal:** ~60-70 min mixtos (Z2/Z3/Z4) en N4, ~70-80 en N5, ~75-90 en N6, repartidos entre las sesiones A, C y D.
- **Benchmarks de niveles anteriores:** `HYROX PFT` (placeholder `ID_PFT`) y `Hyrox Simulacro L3` (placeholder `ID_L3`) se definieron en el lote 1; este lote añade `Hyrox Simulacro L5` (`ID_L5`) y `Hyrox Simulacro L6` (`ID_L6`). El controller resuelve los IDs.

---

## Regla §1.7-Hyrox en tips (referenciada, spec — misma regla del lote 1)

Aplicar en `tips` de niveles y sesiones (texto íntegro en `detalle_hyrox_niveles_01-03.md`, §"Regla §1.7-Hyrox"):

1. **El trineo se progresa por carga antes que por velocidad:** nunca sprintar un sled con la espalda redondeada.
2. **Burpee broad jump = aterrizaje silencioso antes que distancia.**
3. **Wall balls: la fatiga de hombros se gestiona con series planificadas, nunca con rebotes descontrolados.**
4. **Los runs en fatiga se entrenan progresivamente:** primero estación→run corto, luego largo.
5. **Dolor matinal >2/10 en rodilla/lumbar/hombro = congelar el gesto un ciclo.**

---

## Puertas de movilidad activas en este lote

Ninguna puerta nueva. Este lote ejecuta los **re-tests** programados (spec §4.2: "se re-verifican como `re_test` en niveles 4/6/8"):

- **N4: re-test HY4** (única puerta del test de N4). HY1-HY3 ya fueron superadas en N2 y su re-test llega en N6. Criterio de re-test: mismo que la puerta original (`Farmers Walk` 2×16 kg ×100 m sin soltar); la carga del re-test es **menor** que la del ítem de fuerza del test (2×24/2×16 kg) — se documenta en el test de N4.
- **N5: ninguna puerta** (test cerrado por benchmark).
- **N6: re-test HY1, HY2 y HY3** (los tres como ítems `type: re_test` con `gateCode`). Fallar fuerza + puerta en re-test = `mobility_blocked` (ciclo corto de movilidad), mismo ruling que N2.

---

## Benchmarks del lote (fichas completas, spec §4.1)

Dos fichas se siembran con este lote. Ambas `for_time`, `timerConfig: null` (stopwatch con cap), `partitioningAllowed: false`. El jugador registra cada estación como "ronda" (dosis por distancia/reps; distancia no editable en v1 → se anota en notas del finish, riesgo aceptado en spec §7).

### Benchmark 3 — "Hyrox Simulacro L5"

Bloques dobles: 6 estaciones (las 6 primeras del orden oficial) con runs de 1 km, al 75% de los pesos Open.

| Campo | Valor |
|-------|-------|
| name | Hyrox Simulacro L5 |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 3600 (60:00) |
| rxWeightMale | sled push 114 kg, sled pull 77 kg, farmers 2×18 kg |
| rxWeightFemale | sled push 76 kg, sled pull 58 kg, farmers 2×12 kg |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios (6 rondas run→estación, orden oficial 1-6; el jugador registra cada estación como "ronda"):

| # | Ejercicio (grafía catálogo) | Dosis | Nota |
|---|------------------------------|-------|------|
| 1 | `Sprint` | 1 km | Run 1 (tras él, SkiErg) |
| 2 | `SkiErg Sprint` | 1 km | ritmo Z3-Z4; transición corta |
| 3 | `Sprint` | 1 km | Run 2 (tras él, Sled Push) |
| 4 | `Sled Push` | 50 m (4×12,5 m) | 114/76 kg; zancada corta, torso alto (§1) |
| 5 | `Sprint` | 1 km | Run 3 (tras él, Sled Pull) |
| 6 | `Sled Pull` | 50 m | 77/58 kg; peso atrás, pasos cortos |
| 7 | `Sprint` | 1 km | Run 4 (tras él, Burpee Broad Jumps) |
| 8 | `Broad Jump Burpee` | 60 m | aterrizaje silencioso (§2); distancia reducida al 75% |
| 9 | `Sprint` | 1 km | Run 5 (tras él, Row) |
| 10 | `Rowing Machine Sprint` | 1 km | ritmo sostenido, cierre fuerte |
| 11 | `Sprint` | 1 km | Run 6 (tras él, Farmers) |
| 12 | `Farmers Walk` | 200 m | 2×18/2×12 kg; sin soltar; postura erguida |

scalingOptions: **scaled** — sled push 57/38 kg, sled pull 39/29 kg, farmers 2×12/2×8 kg, BBJ 40 m; **subida (L6)** — pesos ~65% Open y BBJ 80 m, en la ficha del `Hyrox Simulacro L6`. Referencias de tiempo (estimadas internas, coherentes con el cap de 60:00 — no hay tabla pública del formato 6 estaciones):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 40:00 | 44:00 |
| advanced | 46:00 | 51:00 |
| intermediate | 52:00 | 57:00 |
| beginner | 58:00 | 63:00 (fuera de cap: mínimo válido = bajo 60:00) |

Uso en test: N5 (lte, mínimo 50:00 (3000 s) / objetivo 45:00 (2700 s), ambos bajo el cap de 60:00 — regla F-Elizabeth).

### Benchmark 4 — "Hyrox Simulacro L6"

Hyrox 3/4: 8 estaciones en orden oficial completo con runs de 1 km, al ~65% de los pesos Open.

| Campo | Valor |
|-------|-------|
| name | Hyrox Simulacro L6 |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 5400 (90:00) |
| rxWeightMale | sled push 100 kg, sled pull 67 kg, farmers 2×16 kg, KB goblet lunge 16 kg, WB 6 kg |
| rxWeightFemale | sled push 68 kg, sled pull 50 kg, farmers 2×12 kg, KB goblet lunge 12 kg, WB 4 kg |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios (8 rondas run→estación, orden oficial completo; el jugador registra cada estación como "ronda"):

| # | Ejercicio (grafía catálogo) | Dosis | Nota |
|---|------------------------------|-------|------|
| 1 | `Sprint` | 1 km | Run 1 (tras él, SkiErg) |
| 2 | `SkiErg Sprint` | 1 km | ritmo Z3; salida controlada |
| 3 | `Sprint` | 1 km | Run 2 (tras él, Sled Push) |
| 4 | `Sled Push` | 50 m (4×12,5 m) | 100/68 kg; zancada corta, torso alto (§1) |
| 5 | `Sprint` | 1 km | Run 3 (tras él, Sled Pull) |
| 6 | `Sled Pull` | 50 m | 67/50 kg; peso atrás, pasos cortos |
| 7 | `Sprint` | 1 km | Run 4 (tras él, Burpee Broad Jumps) |
| 8 | `Broad Jump Burpee` | 80 m | distancia completa de carrera; aterrizaje silencioso (§2) |
| 9 | `Sprint` | 1 km | Run 5 (tras él, Row) |
| 10 | `Rowing Machine Sprint` | 1 km | ritmo sostenido |
| 11 | `Sprint` | 1 km | Run 6 (tras él, Farmers) |
| 12 | `Farmers Walk` | 200 m | 2×16/2×12 kg; sin soltar |
| 13 | `Sprint` | 1 km | Run 7 (tras él, Sandbag Lunges) |
| 14 | `Kettlebell Goblet Lunge` | 100 m | 16/12 kg en goblet; simula el sandbag de competición (nota; §1.7-Hyrox §1) |
| 15 | `Sprint` | 1 km | Run 8 (tras él, Wall Balls) |
| 16 | `Wall Ball Shot` | 75 reps | 6/4 kg a diana oficial; series planificadas de 10-15 (§3) |

scalingOptions: **scaled** — pesos del `Hyrox Simulacro L5` (sled push 114/76, pull 77/58, farmers 2×18/2×12, goblet 12/8 kg), WB ×50; **subida (L7)** — 100% de los pesos Open y WB ×100, en el lote de niveles 7-8. Referencias de tiempo (estimadas internas, coherentes con el cap de 90:00):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 55:00 | 60:00 |
| advanced | 62:00 | 68:00 |
| intermediate | 70:00 | 77:00 |
| beginner | 78:00 | 85:00 |

Uso en test: N6 (lte, mínimo 75:00 (4500 s) / objetivo 60:00 (3600 s), bajo el cap de 90:00 — regla F-Elizabeth).

**Nota sobre rxWeight:** los pesos de estas fichas son los pesos de simulacro definidos (75% Open en L5, ~65% Open en L6 según spec §4.1), no los Open completos — las subidas de carga van en las fichas L7-L8 del lote final. Las dosis de test de este lote se ejecutan en modalidad RX de cada ficha; el escalado se resuelve por `scalingOptions` + elección RX/Scaled del player (mismo criterio que los lotes anteriores).

---

# NIVEL 4 — Estaciones Open

- **Título:** Estaciones Open — Cada estación al 100%, una a una
- **Objetivo:** llevar cada estación a los pesos oficiales Open por separado (sled push 152/102 kg, sled pull 103/78 kg, farmers 2×24/2×16 kg, wall ball 6/4 kg, sandbag lunge → `Kettlebell Goblet Lunge` 20/12 kg), con umbrales ergómetros largos (SkiErg/Row 1.500-2.000 m) y run de 1 km a Z3-Z4 en intervals. La lógica del nivel es "una estación a la vez": fuerza pura con series largas y descanso completo; los pares con fatiga llegan en N5.
- **Requisitos de entrada:** mínimos del test N3 (Simulacro L3 + HY4).
- **skillFocus (obligatorio):** `Estaciones al 100% de los pesos Open por separado y umbrales ergómetros de 1.500-2.000 m`
- **Puertas evaluadas:** **re-test HY4** (única puerta del test). HY1-HY3 superadas en N2; su re-test llega en N6.

## Test de nivel N4 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Wall Ball Shot (WB 6 kg) | 30 reps | 50 reps | gte | reps |
| 2 | Farmers Walk (2×24/2×16 kg) | 100 m | 100 m en <2:30 | gte | meters |
| 3 | HY4 (re-test) — Farmers 2×16 kg ×100 m sin soltar | 100 m | 100 m en <1:45 | gte | meters |

Notas del test:
- Ítems 1-2: fuerza (`type: fuerza`, `direction: gte`) con los pesos Open oficiales por primera vez. Criterio de validez: wall ball desde sentadilla profunda a diana oficial (§3); farmers con postura erguida y sin soltar.
- Ítem 3: re-test de puerta (`type: re_test`, `gateCode: HY4`). **Ojo a la asimetría:** la carga del re-test (2×16 kg) es la carga original de la puerta, **menor** que la del ítem de fuerza (2×24/2×16 kg) — el re-test verifica que el gesto sigue siendo seguro con la carga histórica de la puerta, no la nueva. En re-tests posteriores (N6+) se mantiene este criterio: la puerta conserva su carga de definición.
- Modo de evaluación: `strict_gates` — superar los dos ítems de fuerza **y** HY4 habilita el N5.
- Orden recomendado (menor a mayor interferencia): HY4 (descansado) → ítem 1 → ítem 2.

## Logros del nivel N4

**De test:**
- "Wall ball de 6 kg" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Wall Ball Shot (WB 6 kg)`.
- "Cincuenta wall balls Open" — `type: test`, `tier: raro`, `requirementType: test_item_obj`, `requirementKey: Wall Ball Shot (WB 6 kg)`, `requirementValue: 50`.
- "Farmers Open" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Farmers Walk (2×24/2×16 kg)`.
- "Puerta HY4 re-validada" — `type: test` vinculado a re-test de puerta (`re_test`, `gateCode: HY4`). `tier: raro`.

**De hito:**
- "Primer sled Open empujado" — primer `Sled Push` con 152/102 kg completado. `tier: raro`.
- "Primer sled Open tirado" — primer `Sled Pull` con 103/78 kg completado. `tier: raro`.
- "Lunge de 20 kg" — primer `Kettlebell Goblet Lunge` con 20/12 kg válido (torso alto, rodilla alineada). `tier: comun`.
- "Umbral de 2.000 m" — primer `SkiErg Sprint` o `Rowing Machine Sprint` continuo de 2.000 m completado. `tier: raro`.

## Bloques comunes del Nivel 4

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N3 con dosis de mantenimiento alto y foco nuevo: dorsiflexión y hombro para wall ball 6 kg a diana oficial. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 45 s + 8/lado | 60 s + 8/lado | 60 s + 10/lado | 60 s + 6/lado suave |
| Cadena posterior/agarre (HY4, mantenido) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 12 + 15 | 30 s/lado + 12 + 15 | 40 s/lado + 12 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro (foco nuevo: WB 6 kg) | `Shoulder Dislocate` + `Band Pull-Apart` + `Scapular Push Up` | 15 + 15 + 12 | 15 + 15 + 12 | 20 + 15 + 15 | 12 + 12 + 10 suave |
| Muñeca (HY3, mantenido) | `Wrist Prayer` + `Wrist Extension Stretch` | 30 s + 30 s | 30 s + 30 s | 45 s + 45 s | 30 s + 30 s suave |

Variantes por sesión:
- **A (Run + fuerza):** `Deep Squat Hold` justo antes de la wall ball; activación de agarre (`Farmer Walk` ligero 20 m) antes del farmers fuerte.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Circuito):** énfasis en muñeca (burpees/BBJ en el circuito) y tobillo; ensayo de BBJ con aterrizaje perfecto (§2).
- **D (Aeróbico):** énfasis en cadena posterior y cadera; la descarga se amplía.

### Bloque postural (prehab — selección N4)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×8/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. Progresión: subir una serie al ejercicio más fácil cuando la calidad se mantiene dos sesiones seguidas.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que N1-N3: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N4)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Cada estación al 75-85% de Open con series largas y descanso completo; run 1 km Z3; ergómetros 1.500 m |
| 2 — Progresión | 80% | Estaciones al 90-100% de Open; wall ball 6 kg en dosis de test (×30); farmers 2×24/2×16 kg ×50-75 m |
| 3 — Intensificación | 95% | Todas las estaciones al 100% Open; ergómetros 2.000 m; ensayo de HY4 en sesión D |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 4

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Run intervals Z3-Z4 + fuerza Open del trineo y el farmers (todas las fases)

- Tipo: carrera + fuerza pura de estación. Duración estimada: 60-75 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (1 km a Z3-Z4) | 3×1 km | 3×1 km | 4×1 km | 2×1 km suave | 2 min | Z3 en F1-F2, primera aproximación a Z4 en F3; entre kilómetros, trote suave; el último kilómetro siempre al ritmo más sostenible |
| `Sled Push` (progresión a Open) | 4×12,5 m (90/60 kg) | 4×12,5 m (120/80 kg) | 5×12,5 m (152/102 kg) | 2×12,5 m suave | 2 min | Fuerza pura: series largas con descanso completo; zancada corta y torso alto siempre (§1); la carga sube por fase, nunca la velocidad |
| `Farmers Walk` (progresión a Open) | 3×50 m (2×20/2×12 kg) | 4×50 m (2×24/2×16 kg) | 3×100 m (2×24/2×16 kg) | 2×40 m | 90 s | F3 ensaya los 100 m de test; postura erguida, agarre firme, pasos cortos; si el agarre falla antes que la postura, es trabajo de agarre lo que falta, no más peso |

**Bloque `wod` (transición estación→run):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sled Push` 12,5 m (75% carga del bloque) + `Sprint` 400 m | 90 s entre rondas; primer run saliendo del trineo |
| F2 | 4 rondas | `Farmers Walk` 40 m (2×24/2×16 kg) + `Sprint` 400 m | 90 s entre rondas; los farmers tras correr llegan en N5 — aquí el orden se invierte a propósito |
| F3 | 4 rondas | `Sled Push` 12,5 m (152/102 kg) + `Sprint` 500 m | "Mantener, no construir": el sled no sube de carga esta fase; el run sí |
| F4 | 2 rondas suaves | `Farmers Walk` 30 m ligero + `Sprint` 300 m | Movimiento; test al final del nivel |

**Tips de sesión:** "Hoy el trineo es gimnasio, no carrera: carga alta, descanso completo, técnica de cristal. El día que el sled Open salga limpio con 2 minutos de descanso, la carrera ya solo será cuestión de ritmo (§1.7-Hyrox §1)."

### Sesión B — Ergómetros umbrales largos (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 60-70 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar. Sustituto sin máquinas: `Assault Bike Sprint`/`Echo Bike Sprint` por el mismo tiempo total.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` (umbral largo) | 3×1.000 m | 2×1.500 m | 2×2.000 m | 1×1.000 m suave | 2-3 min | Ritmo umbral sostenible: el último intervalo al mismo ritmo que el primero; remada larga (piernas-core-brazos) |
| `Rowing Machine Sprint` (umbral largo) | 3×1.000 m | 2×1.500 m | 1×2.000 m | 1×1.000 m suave | 2-3 min | Salida controlada, ritmo parejo, cierre fuerte; el 2.000 m continuo de F3 es el umbral más largo del nivel |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 5 × (45 s trabajo / 45 s descanso) | `SkiErg Sprint` a umbral | Acumula 1.000-1.250 m; transiciones rápidas |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | Umbral sostenido; control lumbar (§5) |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "El SkiErg de 2.000 m se rema con la cabeza, no con los brazos: divide en bloques mentales de 500 m y mantén la remada larga cuando ardan los hombros (§1.7-Hyrox §3)."

### Sesión C — Circuito: la estación Open en soledad (todas las fases)

- Tipo: circuito + core. **Sin simulacro** en N4 (el primer benchmark embebido del tramo medio fue L3; el siguiente es L5 en N5). Duración estimada: 60-75 min.
- Bloque 0: variante C. Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta del par del día):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (6 kg) | 2×10 | 2×15 | 2×20 | 2×10 suave | 60 s | Primera wall ball a 6 kg: calienta el gesto del circuito; series planificadas (§3) |
| `Kettlebell Goblet Lunge` (progresión a 20/12) | 2×8 (12/8 kg) | 2×10 (16/12 kg) | 3×10 (20/12 kg) | 2×8 suave | 60 s | Progresión al peso sandbag Open (20/10 kg en carrera; aquí goblet 20/12 kg por simetría de implementos, §1.7-Hyrox §1) |

**Bloque `wod` (estación Open única con run, creciendo por fases):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 400 m + `Wall Ball Shot` ×10 (6 kg) | 90 s entre rondas; primera wall ball Open en fatiga |
| F2 | 4 rondas | `Sprint` 400 m + `Wall Ball Shot` ×15 (6 kg) + `Burpee` ×5 | La dosis WB sube hacia la del test (×30) |
| F3 | 5 rondas | `Sprint` 400 m + `Kettlebell Goblet Lunge` 10/lado (20/12 kg) + `Burpee` ×5 | La estación del día es el lunge Open; aterrizajes silenciosos (§2) |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Wall Ball Shot` ×8 (6 kg) | Movimiento; test al final del nivel |

**Tips de sesión:** "Una estación a la vez, pero siempre con el run delante: la wall ball de 6 kg cansado es otra wall ball. Series de 10-15 planificadas desde el primer rep (§1.7-Hyrox §3)."

### Sesión D — Aeróbico Z2 + movilidad + ensayo HY4 (todas las fases)

- Tipo: aeróbico + movilidad. Duración estimada: 60-75 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques Z3) | 40 min (4×3 min Z3) | 45 min (5×3 min Z3) | 45 min (6×3 min Z3) | 30 min suave Z2 | — | Volumen aeróbico máximo del nivel; los bloques Z3 enseñan a cambiar de ritmo sin fundirse (§4) |
| `Farmers Walk` (HY4, re-test camino) | 2×50 m (2×16/2×12 kg) | 2×75 m | 2×100 m (ensayo re-test) | 2×40 m | 90 s | La carga es la de la puerta (2×16/2×12 kg), no la del test; en F3 se ensayan los 100 m exactos del re-test |
| `Broad Jump Burpee` (volumen moderado) | 3×5 | 3×6 | 4×6 | 2×4 suave | 60 s | Aterrizaje silencioso siempre (§2); el BBJ de 80 m del simulacro L6 se construye aquí |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Step Up` + 8 `Push Up` | Control de ritmo: la última ronda como la primera |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de F3 se hacen con la carga de la puerta y sin soltar: es el re-test HY4 entero. Si el agarre tiembla a los 80 m, el plan es volver a F2, no forzar los últimos 20 (§1.7-Hyrox §5)."

---

# NIVEL 5 — Bloques dobles

- **Título:** Bloques dobles — Pares de estaciones con fatiga real
- **Objetivo:** unir las estaciones en pares con fatiga acumulada (push→pull, row→ski, farmers→lunges, wall ball después de run), cronometrar las transiciones de Roxzone y correr 1 km ×2-3 a Z4. El test es el primer simulacro embebido del tramo: `Hyrox Simulacro L5` (6 estaciones al 75% Open).
- **Requisitos de entrada:** mínimos del test N4 (WB 6 kg ×30, farmers Open ×100 m, HY4 re-validada).
- **skillFocus:** `Pares de estaciones con fatiga, transiciones de Roxzone cronometradas y carrera de 1 km a Z4`
- **Puertas evaluadas:** ninguna nueva ni re-test en este nivel (el test se cierra con el benchmark; HY1-HY3 se re-testan en N6, HY4 ya re-validada en N4).

## Test de nivel N5 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Hyrox Simulacro L5 | 50:00 (3000 s) | 45:00 (2700 s) | lte | seconds |

Notas del test:
- `type: benchmark` sobre `Hyrox Simulacro L5` (placeholder `ID_L5`, ficha de este lote). Se ejecuta en modalidad RX de la ficha (75% Open); la marca se registra en `UserBenchmarkAttempt` y alimenta el test.
- Mínimo (50:00) bajo el cap de 60:00 y en torno a intermediate M (52:00); objetivo (45:00) entre advanced F (51:00) y advanced M (46:00). Regla F-Elizabeth: mínimo siempre bajo el cap.
- Es el primer benchmark del programa con 6 km de carrera acumulados: hidratación y estrategia de transición van en los tips del nivel.

## Logros del nivel N5

**De test:**
- "Simulacro L5 completado" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Hyrox Simulacro L5`.
- "L5 bajo 45" — `type: test`, `tier: raro`, `requirementType: test_item_obj`, `requirementKey: Hyrox Simulacro L5`, `requirementValue: 2700`.
- "Seis kilómetros con estaciones" — `type: test`, `tier: epico`, `requirementType: test_item_obj`, `requirementKey: Hyrox Simulacro L5`, `requirementValue: 3600` (bajo 60:00 — terminar el formato completo dentro del cap).

**De hito:**
- "Par push-pull con fatiga" — primera ronda de `Sled Push` → `Sled Pull` del circuito sin romper técnica (§1). `tier: comun`.
- "Transición bajo 30 segundos" — primera transición estación→run cronometrada en menos de 30 s manteniendo el ritmo del run. `tier: raro`.
- "Kilómetro a Z4" — primer `Sprint` de 1 km a ritmo Z4 completado dentro del ritmo previsto. `tier: comun`.
- "Farmers después de correr" — primer `Farmers Walk` de 200 m con 2×18/2×12 kg completado tras run de 1 km en circuito/sesión. `tier: comun`.

## Bloques comunes del Nivel 5

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N4 con foco nuevo: cadera y agarre para los pares farmers→lunges. Dosis de mantenimiento alto. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 8/lado | 60 s + 10/lado | 60 s + 10/lado | 60 s + 6/lado suave |
| Cadera/agarre (foco: farmers→lunges) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 12 + 15 | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (HY2/HY3, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 30 s | 15 + 15 + 30 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 12 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + fuerza):** `Deep Squat Hold` antes del lunge; activación de agarre antes del farmers fuerte.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y tobillo; en F3 (simulacro) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico.
- **D (Aeróbico):** énfasis en cadera y cadena posterior; la descarga se amplía.

### Bloque postural (prehab — selección N5)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. En N5 el prehab se vuelve obligatorio después de los pares: es el antídoto del sled con fatiga (§5).

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N5)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Pares con fatiga moderada (push→pull ligero, farmers→lunges); transiciones sin cronómetro; run 1 km Z3-Z4 |
| 2 — Progresión | 80% | Pares al 75% Open; transiciones Roxzone cronometradas; wall ball 6 kg después de run |
| 3 — Intensificación | 95% | **Benchmark embebido: Hyrox Simulacro L5 en sesión C**; sesiones A y D a "mantener, no construir" |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 5

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido.

### Sesión A — Run 1 km Z4 + pares de fuerza con fatiga (todas las fases)

- Tipo: carrera + pares de estación. Duración estimada: 65-80 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (1 km a Z3-Z4) | 2×1 km (Z3) | 3×1 km (Z3-Z4) | 3×1 km (Z4) | 2×1 km suave | 2 min | F3 acerca el ritmo al Z4 del simulacro; el último kilómetro siempre parejo (§4) |
| `Sled Push` → `Sled Pull` (par con fatiga) | 3×(12,5 m push 90/60 kg + 12,5 m pull 60/45 kg) | 3×(12,5 m push 114/76 kg + 12,5 m pull 77/58 kg) | 3×(12,5 m push 114/76 kg + 12,5 m pull 77/58 kg) | 2×(12,5 m + 12,5 m suave) | 2 min entre series | El par más exigente de la carrera: transición push→pull caminando, sin descanso extra; §1 en ambos gestos; en F3 no sube carga — el simulacro está cerca |
| `Farmers Walk` → `Kettlebell Goblet Lunge` | 3×(40 m farmers 2×18/2×12 kg + 8/lado lunge 16/12 kg) | 3×(50 m + 10/lado) | 3×(50 m + 10/lado) | 2×(40 m + 8/lado suave) | 90 s entre series | Segundo par: agarre → patrón unilateral; el lunge con las piernas ya cargadas enseña la estación 7 de la carrera |

**Bloque `wod` (transición run→par):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 1 km + `Sled Push` 12,5 m (90/60 kg) + `Sled Pull` 12,5 m (60/45 kg) | 2 min entre rondas; primer par completo saliendo de correr |
| F2 | 3 rondas | `Sprint` 1 km + `Farmers Walk` 50 m (2×18/2×12 kg) + `Kettlebell Goblet Lunge` 10/lado (16/12 kg) | 2 min entre rondas; transiciones cronometradas y anotadas |
| F3 | 2 rondas | `Sprint` 1 km + `Sled Push` 12,5 m (114/76 kg) | "Mantener, no construir": volumen corto, nada de carga nueva antes del simulacro |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Farmers Walk` 30 m ligero | Movimiento; test al final del nivel |

**Tips de sesión:** "El par push→pull se gana en la transición: camina del empuje al tirón sin sentarte, sin mirar el móvil. Sesenta segundos de Roxzone los pone cualquiera; menos de treinta, solo los que entrenaron (§1.7-Hyrox §1/§4)."

### Sesión B — Ergómetros en par: row→ski (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 65-80 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` → `SkiErg Sprint` (par) | 3×(500 m row + 250 m ski) | 3×(750 m row + 500 m ski) | 3×(1.000 m row + 500 m ski) | 2×(500 m + 250 m suave) | 90 s entre series | El par de la segunda mitad de carrera (Row→Ski separados por farmers, aquí unidos por fatiga); cambio de máquina rápido, sin descanso extra; remadas largas hasta el final (§3) |
| `SkiErg Sprint` (umbral suelto) | 2×1.000 m | 2×1.000 m | 1×2.000 m | 1×1.000 m suave | 2-3 min | El 2.000 m de F3 mantiene el umbral largo construido en N4 |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 6 × (1' trabajo / 1' descanso) | `SkiErg Sprint` a umbral | Acumula 1.500 m; el minuto de descanso se usa respirando, no sentado |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | El AMRAP más largo del nivel; última ronda como la primera |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "Row→Ski con fatiga es donde se rompen las remadas: la espalda baja se redondea y los hombros suben a las orejas. Si eso pasa, para la serie y vuelve con remada larga — el volumen de hoy es el del L5, no más (§1.7-Hyrox §5)."

### Sesión C — Circuito de pares con Roxzone (todas las fases)

- Tipo: circuito de pares (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 65-80 min (F1/F2/F4); 100-115 min (F3 con el simulacro).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (6 kg) | 2×12 | 2×15 | 1×10 (calentamiento del simulacro) | 2×10 suave | 60 s | En F3 es calentamiento específico: toca las estaciones del L5 con dosis mínimas (SkiErg 100 m, sled 5 m, BBJ ×3, farmers 20 m) |
| `Broad Jump Burpee` (técnica) | 2×5 | 3×5 | — (el simulacro es el protagonista) | 2×4 | 60 s | Mantiene el patrón de aterrizaje (§2) entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 1 km + `Wall Ball Shot` ×15 (6 kg) + `Burpee` ×8 | 2 min entre rondas; transiciones cronometradas y anotadas |
| F2 | 3 rondas | `Sprint` 1 km + `Sled Push` 12,5 m (114/76 kg) + `Sled Pull` 12,5 m (77/58 kg) + `Broad Jump Burpee` 30 m | El triple más largo antes del simulacro; §1 y §2 en cada gesto |
| F3 | **Benchmark embebido: Hyrox Simulacro L5** | `Hyrox Simulacro L5` en modalidad RX (ver ficha): 6×(run 1 km + estación: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 60 m, Row 1 km, Farmers 200 m) | Se ejecuta con el paso benchmark del player (stopwatch + cap 60:00); la marca alimenta el test del nivel; empezar fresco: nada de sled pesado en la sesión anterior |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Wall Ball Shot` ×8 (6 kg) | Movimiento; test al final del nivel |

**Tips de sesión:** "El L5 son 6 km de carrera con 6 estaciones: se gana o se pierde en los 30 segundos después de cada estación. Camina 3 pasos, respira, corre. Los que salen disparados del SkiErg pagan el run 6 (§1.7-Hyrox §4)."

### Sesión D — Aeróbico Z2 + movilidad (todas las fases)

- Tipo: aeróbico + movilidad. Duración estimada: 65-80 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques Z4) | 45 min (5×3 min Z4) | 45 min (6×3 min Z4) | 50 min (6×3 min Z4) | 35 min suave Z2 | — | Volumen aeróbico máximo del nivel; los bloques Z4 dentro del Z2 enseñan ritmo de competición (§4); en F3 se mantiene el volumen pero sin novedad de carga |
| `Farmers Walk` (200 m, dosis de carrera) | 2×100 m (2×18/2×12 kg) | 2×150 m | 2×200 m (ensayo dosis L5) | 2×60 m | 90 s | F3 ensaya los 200 m completos de la estación 6 del simulacro; agarre firme, pasos cortos |
| `Kettlebell Goblet Lunge` | 3×10/lado (16/12 kg) | 3×12/lado (16/12 kg) | 3×12/lado (20/12 kg) | 2×8/lado suave | 60 s | El lunge aguanta la progresión de carga del nivel aunque el simulacro esté cerca: es trabajo técnico, no fatiga nueva |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Step Up` + 8 `Push Up` | Mantener, no construir: igual que F2 de otras fases, a ritmo moderado |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 200 m de farmers de F3 son la estación 6 del L5 en versión descansada: si salen sin soltar hoy, saldrán igual el día del simulacro. Lo que no sale descansado, no sale cansado (§1.7-Hyrox §1)."

---

# NIVEL 6 — Hyrox 3/4

- **Título:** Hyrox 3/4 — El simulacro completo de 8 estaciones
- **Objetivo:** ejecutar el simulacro completo de 8 estaciones al ~65% de Open como test del nivel (benchmark embebido), entrenar trenes de 3 estaciones seguidas con fatiga acumulada, umbrales ergómetros largos (5.000 m totales entre SkiErg y Row en la sesión B) y run Z2 de 50-60 min en la sesión D. Es el nivel más largo del programa hasta aquí: la sesión C de Fase 3 con el simulacro puede rozar las 2 horas.
- **Requisitos de entrada:** mínimos del test N5 (Simulacro L5 bajo 50:00).
- **skillFocus:** `Simulacro completo de 8 estaciones al 65% Open, trenes de 3 estaciones con fatiga y umbral ergómetro de 5.000 m`
- **Puertas evaluadas:** **re-test HY1, HY2 y HY3** (los tres como ítems `type: re_test` con `gateCode`). Tras 4 niveles de trabajo cargado, el programa verifica que la base de movilidad sigue intacta antes del tramo Open de N7-N8.

## Test de nivel N6 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Hyrox Simulacro L6 | 75:00 (4500 s) | 60:00 (3600 s) | lte | seconds |
| 2 | HY1 (re-test) — Correr 1 km sin caminar | 1 km | 1 km en <5:30 | gte | km |
| 3 | HY2 (re-test) — 30 wall balls 4 kg sin romper series | 30 reps | 30 reps en <2:00 | gte | reps |
| 4 | HY3 (re-test) — 20 burpees completos <2:00 | 20 reps | 20 reps en <1:30 | gte | reps |

Notas del test:
- Ítem 1: `type: benchmark` sobre `Hyrox Simulacro L6` (placeholder `ID_L6`, ficha de este lote), modalidad RX (~65% Open). Marca en `UserBenchmarkAttempt` → alimenta el test. Mínimo (75:00) bajo el cap de 90:00, en torno a intermediate M (70:00)-beginner M (78:00); objetivo (60:00) cerca de elite F (60:00). Regla F-Elizabeth.
- Ítems 2-4: re-tests de puerta (`type: re_test` con `gateCode` HY1/HY2/HY3), mismos criterios que en N2: HY1 = 1 km sin caminar; HY2 = 30 wall balls 4 kg sin romper series; HY3 = 20 burpees completos <2:00. Las cargas son las históricas de definición de cada puerta (WB 4 kg), menores que las de trabajo del nivel (WB 6 kg) — mismo criterio que el re-test HY4 de N4.
- Modo de evaluación: `strict_gates` — superar el benchmark **y** los tres re-tests habilita el N7. Fallar benchmark + puerta = `mobility_blocked` (ciclo corto de movilidad).
- Orden recomendado: re-tests primero (descansado, en sesión separada si hace falta) y el simulacro al final, con al menos una sesión de movilidad entre medias.

## Logros del nivel N6

**De test:**
- "Simulacro L6 completado" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Hyrox Simulacro L6`.
- "Hyrox 3/4 bajo 60" — `type: test`, `tier: epico`, `requirementType: test_item_obj`, `requirementKey: Hyrox Simulacro L6`, `requirementValue: 3600`.
- "Puerta HY1 re-validada" / "Puerta HY2 re-validada" / "Puerta HY3 re-validada" — `type: test` vinculado a re-test de puerta (`re_test`, `gateCode` HY1/HY2/HY3). `tier: raro`.

**De hito:**
- "Ocho estaciones, ocho kilómetros" — primera ejecución completa del `Hyrox Simulacro L6` en sesión C (F3), dentro del cap. `tier: epico`.
- "Tren de tres completado" — primer tren de 3 estaciones (SkiErg→Sled Push→Sled Pull o Row→Farmers→Lunge) del circuito de sesión A sin romper técnica. `tier: raro`.
- "Wall ball setentaicinco" — primer `Wall Ball Shot` de 75 reps acumuladas en una sesión (6/4 kg). `tier: raro`.
- "Umbral de 5.000 m" — primera sesión B con 5.000 m acumulados entre `SkiErg Sprint` y `Rowing Machine Sprint` completados al ritmo previsto. `tier: raro`.

## Bloques comunes del Nivel 6

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N5 a dosis de mantenimiento alto; el Bloque 0 es aquí especialmente estratégico: 8 estaciones significan 8 patrones de fatiga distintos y el re-test de HY1-HY3 exige movilidad intacta. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, re-test camino) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 45 s/lado + 45 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, re-test camino) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 10/lado | 60 s + 10/lado | 75 s + 10/lado | 60 s + 6/lado suave |
| Cadera/cadena posterior (HY4, mantenido) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 45 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (HY2/HY3, re-test camino) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 45 s | 20 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 15 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + fuerza):** `Deep Squat Hold` antes del lunge; activación de agarre antes del farmers fuerte.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y tobillo; en F3 (simulacro) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico de las 8 estaciones.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; en F3 la sesión completa baja a "mantener, no construir".

### Bloque postural (prehab — selección N6)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. Con 8 estaciones en juego, el prehab de N6 es el seguro de la casa: ninguna sesión lo omite ni lo recorta (solo el Bloque 0 se acorta en F3).

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N6)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Trenes de 3 estaciones con fatiga moderada; run 1 km Z3-Z4; umbrales ergómetro 4.000 m |
| 2 — Progresión | 80% | Trenes al ~65% Open; run 1 km ×3 Z4; umbrales 5.000 m; wall ball 6 kg ×50-60 |
| 3 — Intensificación | 95% | **Benchmark embebido: Hyrox Simulacro L6 en sesión C**; sesiones A, B y D a "mantener, no construir"; re-tests HY1-HY3 en D |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 6

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido y las sesiones A/B/D reducen carga y novedad.

### Sesión A — Run Z4 + trenes de 3 estaciones (todas las fases)

- Tipo: carrera + trenes de estación. Duración estimada: 70-85 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (1 km a Z3-Z4) | 3×1 km (Z3) | 3×1 km (Z3-Z4) | 3×1 km (Z4, mantener) | 2×1 km suave | 2 min | F3 mantiene el ritmo Z4 sin subir volumen: el simulacro de la sesión C está cerca y la interferencia manda |
| Tren 1: `SkiErg Sprint` → `Sled Push` → `Sled Pull` | 3×(250 m ski + 12,5 m push 90/60 kg + 12,5 m pull 60/45 kg) | 3×(500 m ski + 12,5 m push 100/68 kg + 12,5 m pull 67/50 kg) | 3×(250 m + 12,5 m + 12,5 m, cargas F1) | 2×(250 m + 12,5 m + 12,5 m suave) | 2 min entre trenes | El tren de la primera mitad de carrera (estaciones 1-3); transiciones caminando, sin sentarse; §1 en el sled siempre |
| Tren 2: `Farmers Walk` → `Kettlebell Goblet Lunge` → `Wall Ball Shot` | 3×(40 m farmers 2×16/2×12 kg + 8/lado lunge 16/12 kg + 10 WB 6/4 kg) | 3×(50 m + 10/lado + 15 WB) | 3×(40 m + 8/lado + 10 WB, mantener) | 2×(40 m + 8/lado + 8 WB suave) | 2 min entre trenes | El tren de la segunda mitad (estaciones 6-8); el farmers pesa sobre el lunge y la wall ball llega con los hombros ya cargados — series planificadas (§3) |

**Bloque `wod` (run saliendo del tren):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 1 km + Tren 1 reducido (SkiErg 250 m + `Sled Push` 12,5 m 90/60 kg) | 2 min entre rondas; primer kilómetro saliendo de las estaciones 1-3 |
| F2 | 3 rondas | `Sprint` 1 km + Tren 2 reducido (`Farmers Walk` 50 m + `Wall Ball Shot` ×15) | 2 min entre rondas; el run tras el tren pesado es la estampa del km 7 de carrera |
| F3 | 2 rondas | `Sprint` 1 km + `Sled Pull` 12,5 m (67/50 kg) | "Mantener, no construir": volumen mínimo antes del simulacro |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Farmers Walk` 30 m ligero | Movimiento; test al final del nivel |

**Tips de sesión:** "El tren enseña lo que la carrera cobra: la wall ball del km 7 llega con los hombros fundidos por el farmers y el lunge. Entrena las series de 10 planificadas hoy, y el día del simulacro no negociarás con la fatiga (§1.7-Hyrox §3)."

### Sesión B — Ergómetros: 5.000 m de umbral (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 65-80 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` (bloque de umbral) | 2×1.500 m | 2×2.000 m | 2×1.500 m (mantener) | 1×1.000 m suave | 3 min | Acumula 3.000-4.000 m; ritmo umbral parejo; en F3 se mantiene el volumen de F1 — nada de novedad |
| `Rowing Machine Sprint` (bloque de umbral) | 2×1.000 m | 1×1.500 m + 1×1.000 m | 1×1.500 m (mantener) | 1×1.000 m suave | 3 min | Acumula 2.000-2.500 m; entre SkiErg y Row suman 5.000 m en F2; salida controlada y cierre fuerte |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 6 × (1' trabajo / 1' descanso) | `SkiErg Sprint` a umbral | Acumula 1.500 m; transiciones respirando de pie |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | Volumen contenido; el simulacro está cerca |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "5.000 m de ergómetro a umbral son el SkiErg y el Row del simulacro juntos, sin correr por medio: si hoy salen parejos, el día del L6 los remarás con los ojos cerrados. Si la lumbar protesta, congela el gesto un ciclo (§1.7-Hyrox §5)."

### Sesión C — Simulacro/trenes (todas las fases)

- Tipo: circuito de trenes (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 70-85 min (F1/F2/F4); 105-120 min (F3 con el simulacro).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (6 kg) | 2×15 | 2×20 | 1×10 (calentamiento del simulacro) | 2×10 suave | 60 s | En F3 es calentamiento específico: toca las 8 estaciones con dosis mínimas (SkiErg 100 m, sled 5 m, BBJ ×3, farmers 20 m, lunge 5/lado, WB ×5) |
| `Broad Jump Burpee` (técnica) | 3×5 | 3×6 | — (el simulacro es el protagonista) | 2×4 | 60 s | Mantiene el aterrizaje silencioso (§2) entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 1 km + `SkiErg Sprint` 500 m + `Sled Push` 12,5 m (100/68 kg) + `Sled Pull` 12,5 m (67/50 kg) | 2 min entre rondas; el tren de la primera mitad con run |
| F2 | 3 rondas | `Sprint` 1 km + `Farmers Walk` 100 m (2×16/2×12 kg) + `Kettlebell Goblet Lunge` 20 m (16/12 kg) + `Wall Ball Shot` ×20 (6/4 kg) | 2 min entre rondas; el tren de la segunda mitad con run; la wall ball llega fundida — series de 10 (§3) |
| F3 | **Benchmark embebido: Hyrox Simulacro L6** | `Hyrox Simulacro L6` en modalidad RX (ver ficha): 8×(run 1 km + estación en orden oficial: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 80 m, Row 1 km, Farmers 200 m, Goblet Lunge 100 m, WB ×75) | Se ejecuta con el paso benchmark del player (stopwatch + cap 90:00); la marca alimenta el test; logro de hito "Ocho estaciones, ocho kilómetros". Empezar fresco: las sesiones A/B/D de esta fase ya redujeron carga |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Wall Ball Shot` ×8 (6 kg) | Movimiento; test al final del nivel |

**Tips de sesión:** "El L6 son 8 km y 8 estaciones: piensa en dos mitades. La primera se gana con cabeza (SkiErg y sleds sin heroísmos); la segunda con lo que entrenaste en los trenes (farmers, lunge y wall ball por bloques). Bajo 90:00 primero; el ritmo llega solo (§1.7-Hyrox §4)."

### Sesión D — Aeróbico Z2 + movilidad + re-tests HY1-HY3 (todas las fases)

- Tipo: aeróbico + movilidad + re-tests. Duración estimada: 70-85 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 largo) | 50 min Z2 | 55 min (4×3 min Z3) | 45 min suave Z2 (mantener) | 40 min Z2 suave | — | El run largo del nivel; en F3 baja el volumen y la novedad — el simulacro está cerca; §4 en los bloques Z3 de F2 |
| Re-tests HY1-HY3 (finales de F1-F3) | HY1: 1 km sin caminar tras el run | HY2: 30 WB 4 kg sin romper | HY3: 20 `Burpee` <2:00 | — (en F4 va en el test) | 3 min | Ensayos exactos de los re-tests del test: HY1 al final del run largo (la versión más dura), HY2/HY3 frescos en el punto de mínima interferencia |
| `Farmer Walk` (agarre, mantenimiento) | 2×50 m (2×24/2×16 kg) | 2×50 m | 2×40 m | 2×30 m | 90 s | Mantiene el agarre de competición sin fatiga nueva |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Mantener, no construir: el simulacro está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los re-tests de hoy se hacen con la misma humildad que en N2: 1 km sin caminar, 30 wall balls limpias, 20 burpees en 2 minutos. Cuatro niveles después, lo difícil no es pasarlos — es no menospreciarlos (§1.7-Hyrox §5)."

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N4-N6: todos los ejercicios citados existen en el catálogo con la grafía usada (verificados contra BD dev, 17-oct-2026, lote 1 y re-verificación de este lote).
- Duplicado conocido (heredado del lote 1): `Farmers Walk` (id 340) y `Farmer Walk` (id 280) — el documento usa `Farmers Walk` como grafía principal y `Farmer Walk` solo en la sesión D de N6 (mantenimiento de agarre) como alias aceptado. Consolidación de catálogo pendiente (fuera de este lote).
- Duplicados menores detectados en la verificación (no afectan al contenido): `Face pull` (756) vs `Face Pull` (482) y `Pallof press` (782) vs `Pallof Press` (314) — este documento usa siempre la grafía capitalizada. Se reportan para limpieza futura.
- Sandbag lunge: sigue resolviéndose con `Kettlebell Goblet Lunge` + nota (simula el sandbag), coherente con spec §3 ("0 ejercicios nuevos previstos"). En el L6 la dosis es 100 m y 16/12 kg (el recorrido real del sandbag de competición se introduce al 100% Open en N7).
- Sustitutos sin máquinas documentados en notas (mismo criterio que el lote 1): `Assault Bike Sprint`/`Echo Bike Sprint` para SkiErg/Row; `Step Up` cargado para Sled Push; `Kettlebell Swing Russian` pesado para Sled Pull.
- Los simulacros L7-L8 (fichas 5-6 de spec §4.1: Open a ritmo y competición) se especifican en el lote de niveles 7-8.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Re-test de HY4 en N4 con carga histórica de la puerta (2×16 kg), no la del ítem de fuerza (2×24/2×16 kg):** el re-test verifica que el gesto sigue siendo seguro con la carga de definición. Mismo criterio aplicado a los re-tests HY1-HY3 de N6 (WB 4 kg histórica vs 6 kg de trabajo). A confirmar con el usuario.
2. **Calendario de re-tests:** HY4 en N4 (spec §4.2 menciona re-tests en 4/6/8; HY1-HY3 se re-testan en N6 y queda el re-test final de HY4 para N8, fuera de este lote). N5 queda sin puertas por ser nivel de benchmark puro.
3. **Test N5 = solo Simulacro L5** (un ítem benchmark) y **test N6 = Simulacro L6 + re-tests HY1-HY3** — coherente con la instrucción de diseño y con spec §5.
4. **Progresión del wall ball:** 4 kg (N1-N3) → 6 kg ×30-50 (N4) → 6 kg ×15-20 en fatiga y simulacro L5 sin WB (N5) → 6/4 kg ×75 en el L6 (N6) → ×100 al 100% Open en N7. El salto de reps ×50 (N4) a ×75 (N6) se prepara con los trenes de la sesión A de N5-N6.
5. **Sled push N4 fuerza pura (series largas, 2 min de descanso, carga por fase) → N5 parejas con fatiga → N6 trenes de 3 con fatiga acumulada:** la progresión de interferencia es la columna del tramo medio.
6. **Interferencia controlada en F3 de N5/N6:** las sesiones A/B/D de la fase del simulacro bajan a "mantener, no construir" (cargas sin novedad, volumen reducido). Sin calendario, la regla queda escrita en las propias sesiones.
7. **Running semanal:** N4 ~60-70 min (A: 3-4 km Z3-Z4 + D: 40-45 min Z2), N5 ~70-80 (A: 2-3 km Z4 + C: 3 km en circuito + D: 45-50), N6 ~75-90 (A: 3 km Z4 + C: 8 km simulacro en F3 + D: 45-55; en F3 la suma real supera 90 min, pero A y D bajan a mantenimiento).
8. **Sesión B de N6 acumula 5.000 m entre SkiErg y Row** en F2 (2×2.000 + 1.500 + 1.000): es el umbral ergómetro más alto del programa hasta N8 y prepara el L6 (2 km de ergómetros dentro del simulacro).
9. **Duración estimada de la sesión C de N6 F3: 105-120 min** — el mayor valor estimado del programa (incluye simulacro completo con cap 90:00 + bloques); consistente con la convención del spec §4.3 (revisar tras generar, `TrainingTimeCalculator` estima 60% del cap como mínimo).
