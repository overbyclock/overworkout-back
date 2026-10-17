# Detalle de contenido — Niveles 7 y 8 — Hyrox Mastery v1

> Documento fuente desde el que se generará el programa Hyrox Mastery (comando
> `app:create-hyrox-mastery`), lote 3 de 3 (niveles 7-8, cierre del programa).
> Continuidad total con `docs/detalle_hyrox_niveles_01-03.md` (lote 1:
> niveles 1-3, benchmarks PFT y Simulacro L3, puertas HY1-HY4) y
> `docs/detalle_hyrox_niveles_04-06.md` (lote 2: niveles 4-6, benchmarks
> Simulacro L5 y L6, re-tests HY4 e HY1-HY3). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-17-hyrox-mastery-design.md` (§2 formato de
> carrera temporada 26/27, §4.1 benchmarks, §4.2 puertas y tests, §4.3 patrón
> de sesiones A/B/C/D, §5 arco de niveles: N7 Hyrox Open, N8 Ritmo de
> competición).
>
> **Estado de redacción:** COMPLETO. Niveles 7 y 8 con sus 8 sesiones
> (4 fases × A/B/C/D por nivel), tests, re-test final de puerta, logros y las
> 2 fichas de benchmark del lote (Hyrox Simulacro L7 y L8). Con este lote el
> programa queda completo: 8 niveles, 8 fichas de benchmark (PFT, L3, L5, L6,
> L7, L8) y las 4 puertas HY1-HY4 con su calendario de re-tests. Pendiente la
> validación de contenido con el usuario (gate humano).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (spec §4.3): A — Run + estaciones de fuerza; B — Ergómetros (SkiErg/Row) + core; C — Simulacro/circuito (la sesión C de Fase 3 = benchmark embebido del simulacro del nivel); D — Aeróbico Z2 + movilidad.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab hombro/lumbar/tobillo rotado por sesión) → `descarga`. `restAfterBlock` = **60 s en N7** y **90 s en N8** (regla del proyecto: niveles ≤6 → 60 s, resto → 90 s).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Dosis `/lado` se computan una vez.
- **RIR:** en N7-N8 el trabajo de estación es de ritmo y potencia repetida, no de fuerza máxima: 1-2 RIR en las series de fuerza de mantenimiento; en simulacros y circuitos de ritmo manda el split objetivo, y el fallo técnico sigue marcando el escalado (regla §1.7-Hyrox §5).
- **Equipamiento asumido N7-N8:** gimnasio con SkiErg, remo, sled (push/pull), wall balls (4 y 6 kg), kettlebells/mancuernas, y cinta o exterior para correr. Si no hay SkiErg ni remo, sustituto calórico: `Assault Bike Sprint` o `Echo Bike Sprint` en dosis por tiempo equivalentes (nota en la sesión). Si no hay sled: `Step Up` cargado para el push y `Kettlebell Swing Russian` pesado para el pull (mismo criterio que los lotes 1-2).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (verificada en los lotes 1-2, 17-oct-2026). Nombres literales.
- **Convención de nombres de ítems de test:** los ítems que corresponden a ejercicio de catálogo usan su grafía exacta con la carga entre paréntesis; los benchmarks se nombran como nombre propio ("HYROX PFT", "Hyrox Simulacro L7", "Hyrox Simulacro L8"); los re-tests de puerta llevan el código HYn como nombre literal.
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.
- **Pesos:** N7 introduce la carrera completa al **100% de pesos Open** (temporada 26/27). N8 repite las mismas dosis a **ritmo de competición**; las variantes **Pro** (sled push 202/152, pull 153/103, farmers 2×32/2×24, goblet 28/20, WB 9/6 kg ×100) son objetivo opcional que aparece **solo en notas**.
- **Running semanal:** ~85-100 min mixtos en N7, ~80-95 en N8 (mantenimiento + intensidad; el volumen no sube respecto a N6: en el tramo final se afila, no se acumula).
- **Benchmarks de niveles anteriores:** `HYROX PFT` (placeholder `ID_PFT`), `Hyrox Simulacro L3` (`ID_L3`), `Hyrox Simulacro L5` (`ID_L5`) y `Hyrox Simulacro L6` (`ID_L6`) en lotes anteriores; este lote añade `Hyrox Simulacro L7` (`ID_L7`) y `Hyrox Simulacro L8` (`ID_L8`). El controller resuelve los IDs.

---

## Regla §1.7-Hyrox en tips (referenciada, spec — misma regla de los lotes 1-2)

Aplicar en `tips` de niveles y sesiones (texto íntegro en `detalle_hyrox_niveles_01-03.md`, §"Regla §1.7-Hyrox"):

1. **El trineo se progresa por carga antes que por velocidad:** nunca sprintar un sled con la espalda redondeada.
2. **Burpee broad jump = aterrizaje silencioso antes que distancia.**
3. **Wall balls: la fatiga de hombros se gestiona con series planificadas, nunca con rebotes descontrolados.**
4. **Los runs en fatiga se entrenan progresivamente:** primero estación→run corto, luego largo.
5. **Dolor matinal >2/10 en rodilla/lumbar/hombro = congelar el gesto un ciclo.**

En este tramo se añade la capa de estrategia de carrera en los tips: **ritmo negativo** (la segunda mitad más rápida que la primera), **Roxzone como transición activa** (3-5 pasos de caminata rápida, respiración de 3 ciclos, y a correr — nunca sprint de recuperación), **series planificadas** en wall balls (10-15 desde el rep 1) y burpee broad jumps (bloques de 10 m con aterrizaje válido), **calor e hidratación** en simulacros largos (agua en cada transición, 2-3 sorbos, sin parar el reloj en los entrenamientos).

---

## Puertas de movilidad activas en este lote

- **N7: re-test HY4 con re-validación de carga.** Es la última puerta del programa y **madura a su carga Open**: el criterio de re-test pasa de farmers 2×16 kg ×100 m (carga histórica de definición, N3/N4-N6) a **farmers 2×24/2×16 kg ×100 m sin soltar** — la carga real de la estación 6 de la carrera. La puerta se actualiza en su definición (`criteria`) al subir de nivel; el re-test verifica el gesto exacto de competición. Es el cierre del arco HY1-HY4: la puerta crece con el atleta.
- **N8: ninguna puerta.** HY1-HY4 ya re-validadas (HY1-HY3 en N6, HY4 en N7 con carga Open). El test de N8 se cierra con dos benchmarks.

---

## Benchmarks del lote (fichas completas, spec §4.1)

Dos fichas se siembran con este lote. Ambas `for_time`, `timerConfig: null` (stopwatch con cap), `partitioningAllowed: false`. El jugador registra cada estación como "ronda" (dosis por distancia/reps; distancia no editable en v1 → se anota en notas del finish, riesgo aceptado en spec §7).

**Nota de diseño (explícita):** L7 y L8 **comparten dosis** — 8×1 km + las 8 estaciones al 100% de pesos Open. La diferencia es el cap y las referencias: **L7 = completar la carrera Open** (cap 1:45, mínimo generoso); **L8 = completarla a ritmo de competición** (cap 1:30, referencias un escalón más exigentes). Son marcas distintas de test (construcción vs. afilado); si al transcribir a la plataforma dos benchmarks casi iguales resulta redundante, es aceptable — se mantienen como fichas separadas por decisión del lote (ver §Decisiones).

### Benchmark 5 — "Hyrox Simulacro L7"

La carrera completa al 100% de pesos Open: el objetivo de todo el programa.

| Campo | Valor |
|-------|-------|
| name | Hyrox Simulacro L7 |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 6300 (1:45:00) |
| rxWeightMale | sled push 152 kg, sled pull 103 kg, farmers 2×24 kg, KB goblet lunge 20 kg, WB 6 kg |
| rxWeightFemale | sled push 102 kg, sled pull 78 kg, farmers 2×16 kg, KB goblet lunge 12 kg, WB 4 kg |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios (8 rondas run→estación, orden oficial completo; el jugador registra cada estación como "ronda"):

| # | Ejercicio (grafía catálogo) | Dosis | Nota |
|---|------------------------------|-------|------|
| 1 | `Sprint` | 1 km | Run 1 (tras él, SkiErg) |
| 2 | `SkiErg Sprint` | 1 km | split objetivo M ~4:30 / F ~5:00 |
| 3 | `Sprint` | 1 km | Run 2 (tras él, Sled Push) |
| 4 | `Sled Push` | 50 m (4×12,5 m) | 152/102 kg; zancada corta, torso alto (§1) |
| 5 | `Sprint` | 1 km | Run 3 (tras él, Sled Pull) |
| 6 | `Sled Pull` | 50 m | 103/78 kg; peso atrás, pasos cortos |
| 7 | `Sprint` | 1 km | Run 4 (tras él, Burpee Broad Jumps) |
| 8 | `Broad Jump Burpee` | 80 m | distancia completa; aterrizaje silencioso (§2); bloques de 10 m |
| 9 | `Sprint` | 1 km | Run 5 (tras él, Row) |
| 10 | `Rowing Machine Sprint` | 1 km | split objetivo M ~4:00 / F ~4:30 |
| 11 | `Sprint` | 1 km | Run 6 (tras él, Farmers) |
| 12 | `Farmers Walk` | 200 m | 2×24/2×16 kg; sin soltar; pasos cortos |
| 13 | `Sprint` | 1 km | Run 7 (tras él, Sandbag Lunges) |
| 14 | `Kettlebell Goblet Lunge` | 100 m | 20/12 kg en goblet; simula el sandbag de competición (nota; §1.7-Hyrox §1) |
| 15 | `Sprint` | 1 km | Run 8 (tras él, Wall Balls) |
| 16 | `Wall Ball Shot` | 100 reps | 6/4 kg a diana oficial; series de 10-15 planificadas (§3) |

scalingOptions: **scaled** — pesos del `Hyrox Simulacro L6` (sled push 100/68, pull 67/50, farmers 2×16/2×12, goblet 16/12 kg), WB ×75, BBJ 60 m; **variantes Pro (notas, no RX de esta ficha):** sled push 202/152 kg, pull 153/103 kg, farmers 2×32/2×24 kg, goblet 28/20 kg, WB 9/6 kg ×100. Referencias de tiempo (coherentes con el cap; tiempos de carrera Open reales):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 55:00 | 60:00 |
| advanced | 62:00 | 68:00 |
| intermediate | 70:00 | 77:00 |
| beginner | 78:00 | 85:00 |

Uso en test: N7 (lte, mínimo 1:30:00 (5400 s) / objetivo 1:15:00 (4500 s), mínimo bajo el cap de 1:45:00 — regla F-Elizabeth).

### Benchmark 6 — "Hyrox Simulacro L8"

La misma carrera, un escalón de ritmo por encima: la ficha de competición.

| Campo | Valor |
|-------|-------|
| name | Hyrox Simulacro L8 |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 5400 (1:30:00) |
| rxWeightMale | sled push 152 kg, sled pull 103 kg, farmers 2×24 kg, KB goblet lunge 20 kg, WB 6 kg |
| rxWeightFemale | sled push 102 kg, sled pull 78 kg, farmers 2×16 kg, KB goblet lunge 12 kg, WB 4 kg |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios: idénticos a la ficha L7 (misma tabla de 16 filas: 8×(`Sprint` 1 km + estación) con las 8 estaciones a 100% de pesos Open). La transcripción puede compartir la definición de ejercicios con L7 en el seeder (mismo array de estaciones) y solo variar name, timeCapSeconds y referencias.

scalingOptions: **scaled** — pesos L6 (como L7); **variantes Pro (nota "si compites Pro, entrena estos pesos en las sesiones A"):** sled push 202/152 kg, pull 153/103 kg, farmers 2×32/2×24 kg, goblet 28/20 kg, WB 9/6 kg ×100. Referencias de tiempo (un escalón por encima de L7, coherentes con el cap de 1:30:00):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 50:00 | 55:00 |
| advanced | 56:00 | 62:00 |
| intermediate | 63:00 | 70:00 |
| beginner | 70:00 | 77:00 |

Uso en test: N8 (lte, mínimo 1:15:00 (4500 s) / objetivo 1:00:00 (3600 s), bajo el cap de 1:30:00 — regla F-Elizabeth).

**Cuadre de tiempos del objetivo L7 (1:15:00 M):** 8 runs × ~4:45 = 38:00 + SkiErg 4:30 + Sled Push 2:30 + Sled Pull 3:30 + BBJ 5:00 + Row 4:00 + Farmers 3:00 + Lunge 4:00 + WB ×100 5:00 = estaciones 31:30 + Roxzone/transiciones ~5:30 = **75:00**. Los splits objetivo por estación de las sesiones de N7/N8 usan esta tabla; el objetivo L8 (1:00:00) exige runs a ~4:15 y Roxzone bajo control.

---

# NIVEL 7 — Hyrox Open

- **Título:** Hyrox Open — La carrera completa, pesos oficiales
- **Objetivo:** completar el simulacro completo de 8 estaciones al 100% de pesos Open como test del nivel (benchmark L7), entrenando con **ritmo por estaciones** (cada estación con su split objetivo), runs de 1 km a Z4 y wall balls ×100 en series planificadas. Es el nivel de la consagración: todo lo construido en N1-N6 se junta en una sola carrera.
- **Requisitos de entrada:** mínimos del test N6 (Simulacro L6 + re-tests HY1-HY3).
- **skillFocus (obligatorio):** `Carrera completa de Hyrox al 100% de pesos Open, ritmo por estaciones y estrategia de Roxzone`
- **Puertas evaluadas:** **re-test HY4 con re-validación de carga** — la puerta madura a farmers 2×24/2×16 kg ×100 m sin soltar (carga Open de la estación 6). Última puerta del programa.

## Test de nivel N7 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Hyrox Simulacro L7 | 1:30:00 (5400 s) | 1:15:00 (4500 s) | lte | seconds |
| 2 | HY4 (re-test) — Farmers 2×24/2×16 kg ×100 m sin soltar | 100 m | 100 m en <2:00 | gte | meters |

Notas del test:
- Ítem 1: `type: benchmark` sobre `Hyrox Simulacro L7` (placeholder `ID_L7`, ficha de este lote), modalidad RX (100% Open). Marca en `UserBenchmarkAttempt` → alimenta el test. Mínimo (1:30:00) bajo el cap de 1:45:00, en torno a beginner M (78:00)+margen de primer Open; objetivo (1:15:00) entre advanced M (62:00) e intermediate M (70:00) con margen de Roxzone real. Regla F-Elizabeth.
- Ítem 2: re-test de puerta (`type: re_test`, `gateCode: HY4`) **con carga re-validada**: farmers 2×24/2×16 kg ×100 m sin soltar (antes de N7 la puerta exigía 2×16 kg). La `criteria` de la puerta se actualiza al superar N6; el re-test verifica el gesto exacto de competición. Si el atleta compite en categoría Pro, el objetivo de nota son 2×32/2×24 kg.
- Modo de evaluación: `strict_gates` — superar el benchmark **y** HY4 habilita el N8.
- Orden recomendado: HY4 descansado primero y el simulacro después (o en sesiones separadas del envío de test). El simulacro L7 es el esfuerzo más largo del programa: hidratación y plan de splits en los tips.

## Logros del nivel N7

**De test:**
- "Primera Hyrox Open completa" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Hyrox Simulacro L7`.
- "Open bajo 1:15" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: Hyrox Simulacro L7`, `requirementValue: 4500`.
- "Puerta HY4 a carga Open" — `type: test` vinculado a re-test de puerta (`re_test`, `gateCode: HY4`). `tier: raro`.

**De hito:**
- "Cien wall balls Open" — primer acumulado de 100 `Wall Ball Shot` a 6/4 kg en una sola sesión (series planificadas, §3). `tier: epico`.
- "Split de carrera en el sled" — primer `Sled Push` de 50 m a 152/102 kg bajo el split objetivo (2:30 M / 3:00 F) con técnica válida. `tier: raro`.
- "Roxzone de 20 segundos" — primera transición estación→run del circuito de ritmo en menos de 20 s sin elevar el ritmo cardíaco por encima de lo sostenible. `tier: raro`.
- "Farmers Open de cien metros" — primer `Farmers Walk` de 100 m con 2×24/2×16 kg sin soltar (ensayo del re-test). `tier: comun`.

## Bloques comunes del Nivel 7

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N6 a dosis de mantenimiento alto; con la carga Open completa en juego, el Bloque 0 es la primera línea de defensa: nada entra en el simulacro sin tobillo, hombro y cadena posterior despiertos. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 45 s/lado + 45 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 10/lado | 60 s + 10/lado | 75 s + 10/lado | 60 s + 6/lado suave |
| Cadera/cadena posterior (HY4, re-test camino) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 45 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (HY2/HY3, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 45 s | 20 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 15 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + fuerza):** `Deep Squat Hold` antes del lunge; activación de agarre (`Farmer Walk` ligero 20 m) antes del farmers Open.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y tobillo; en F3 (simulacro L7) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico de las 8 estaciones.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; ensayo del re-test HY4 a carga Open.

### Bloque postural (prehab — selección N7)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. En el tramo Open el prehab es mantenimiento de alta gama: ya no crece, pero su omisión es la primera causa de lesión de los que llegan aquí.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N7)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Splits por estación introducidos (runs 1 km a ritmo objetivo + estación con split); pesos Open con volumen moderado; wall balls ×100 en series de 10 |
| 2 — Progresión | 80% | Circuitos de ritmo: 4-6 estaciones seguidas con splits; runs 1 km a Z4 con ritmo negativo; ensayo HY4 a carga Open en D |
| 3 — Intensificación | 95% | **Benchmark embebido: Hyrox Simulacro L7 en sesión C**; sesiones A/B/D a "afilar, no construir": volumen bajo, ritmo exacto, nada de carga nueva |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 7

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido y las sesiones A/B/D pasan a "afilar, no construir".

### Sesión A — Run a ritmo de carrera + estación Open con split (todas las fases)

- Tipo: carrera + estación con ritmo objetivo. Duración estimada: 75-90 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (1 km a ritmo de carrera) | 3×1 km a ritmo objetivo (~4:45 M) | 4×1 km (ritmo negativo: el último más rápido) | 3×1 km (ritmo exacto, afilar) | 2×1 km suave | 2 min | F1 aprende el ritmo objetivo; F2 entrena el ritmo negativo (§ estrategia); F3 repite el ritmo exacto sin novedad — el L7 está cerca |
| `Sled Push` (Open, con split) | 3×50 m (152/102 kg, cronometrados) | 3×50 m (bajo split) | 2×50 m (ritmo exacto) | 2×25 m suave | 2 min | Split objetivo M 2:30 / F 3:00 por 50 m; series largas de descanso: es potencia, no metcon (§1) |
| `Kettlebell Goblet Lunge` (Open) | 3×20 m (20/12 kg) | 3×30 m | 2×30 m (ritmo) | 2×15 m suave | 90 s | Split objetivo M 4:00 / F 4:45 por 100 m; torso alto, pasos medidos; el lunge decide la carrera de la segunda mitad |
| `Farmers Walk` (Open, re-test camino) | 2×50 m (2×24/2×16 kg) | 2×100 m (ensayo re-test) | 2×100 m (ritmo exacto) | 2×40 m | 90 s | F2-F3 ensayan los 100 m exactos del re-test HY4 a carga Open; pasos cortos, agarre firme |

**Bloque `wod` (run→estación a ritmo con transición cronometrada):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 1 km + `Sled Push` 12,5 m (152/102 kg) | 2 min entre rondas; primer contacto con el Open en fatiga; transiciones de 20-30 s anotadas |
| F2 | 4 rondas | `Sprint` 1 km + `Wall Ball Shot` ×25 (6 kg) + transición cronometrada | Series de 25 dentro del plan de ×100 (4×25); el run sale a ritmo, no a sprint (§4) |
| F3 | 2 rondas | `Sprint` 1 km a ritmo exacto + `Farmers Walk` 100 m (2×24/2×16 kg) | "Afilar, no construir": el par exacto del km 6-7 de la carrera, una sola vez bien hecho |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Wall Ball Shot` ×10 (6 kg) | Movimiento; test al final del nivel |

**Tips de sesión:** "Hoy cada estación tiene un reloj propio: el sled Open no se siente, se cronometra. Si el 50 m sale en 2:10 con técnica limpia, ese es tu split de carrera; si sale en 3:20, tu plan de carrera empieza por él (§1.7-Hyrox §1)."

### Sesión B — Ergómetros a ritmo de carrera (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 65-80 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` (ritmo de carrera) | 2×1.000 m (split objetivo 4:30 M) | 3×1.000 m (bajo split) | 2×1.000 m (ritmo exacto) | 1×1.000 m suave | 2-3 min | La estación 1 se gana o se pierde aquí: salida controlada, remada larga, cierre fuerte; §3 en los hombros |
| `Rowing Machine Sprint` (ritmo de carrera) | 2×1.000 m (split 4:00 M) | 2×1.000 m (bajo split) | 1×1.000 m (ritmo exacto) | 1×1.000 m suave | 2-3 min | La estación 5 llega con 5 km en las piernas: entrena el split cansado con los bloques Z3 del WOD |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 2×(1.000 m `SkiErg Sprint` a ritmo objetivo + 10 `Burpee`) | SkiErg con split exacto y salida inmediata de burpees | Ensayo de la estación 1 con fatiga de transición; §2 en cada burpee |
| F3 | 6 × (1' trabajo / 1' descanso) | `SkiErg Sprint` a umbral | Afilar: 1.500 m acumulados a ritmo, sin novedad |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "SkiErg y Row del Open no se sprintan: se ejecutan. El split de 4:30 se repite hoy cinco veces; el día del L7 saldrá una sexta con el corazón a 170 y ahí no habrá negociación posible (§1.7-Hyrox §3)."

### Sesión C — Simulacro/circuito de ritmo (todas las fases)

- Tipo: circuito de ritmo (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 75-90 min (F1/F2/F4); 110-135 min (F3 con el simulacro L7 — la sesión más larga del programa).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (6 kg) | 2×20 | 2×25 | 1×15 (calentamiento del simulacro) | 2×15 suave | 60 s | F1-F2 acumulan hacia las ×100 (objetivo: 4 series de 25); en F3 es calentamiento específico: toca las 8 estaciones con dosis mínimas |
| `Broad Jump Burpee` (técnica) | 3×5 | 3×6 | — (el simulacro es el protagonista) | 2×4 | 60 s | Bloques de 10 m con aterrizaje válido (§2); el BBJ de 80 m son 8 bloques mentales |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 1 km a ritmo + `SkiErg Sprint` 500 m (split) + `Sled Push` 25 m (152/102 kg) + `Sled Pull` 25 m (103/78 kg) | 2 min entre rondas; el tren 1-3 con splits y transiciones anotadas |
| F2 | 3 rondas | `Sprint` 1 km a ritmo + `Farmers Walk` 100 m (2×24/2×16 kg) + `Kettlebell Goblet Lunge` 30 m (20/12 kg) + `Wall Ball Shot` ×25 (6 kg) | 2 min entre rondas; el tren 6-8 con splits; la WB llega fundida — 4×25 planificadas (§3) |
| F3 | **Benchmark embebido: Hyrox Simulacro L7** | `Hyrox Simulacro L7` en modalidad RX (ver ficha): 8×(run 1 km + estación en orden oficial, 100% Open) | Stopwatch + cap 1:45:00; la marca alimenta el test; logro de hito "Primera Hyrox Open completa". Plan: primeros 4 runs 5 s más lentos que el objetivo (ritmo negativo), Roxzone de 20-30 s, agua en cada transición |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Wall Ball Shot` ×10 (6 kg) | Movimiento; test al final del nivel |

**Tips de sesión:** "El L7 se corre con la cabeza primero: runs 1-4 conservadores, runs 5-8 de carrera. Cada transición es caminar 3 pasos, 3 respiraciones y arrancar. Los últimos 2 km se ganan en los primeros 6 (estrategia de ritmo negativo, §1.7-Hyrox §4)."

### Sesión D — Aeróbico + movilidad + re-test HY4 a carga Open (todas las fases)

- Tipo: aeróbico + movilidad + re-test. Duración estimada: 70-85 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques de carrera) | 50 min (5×3 min a ritmo objetivo) | 55 min (6×3 min) | 45 min suave (afilar) | 40 min Z2 suave | — | Volumen alto Z2 con bloques a ritmo de carrera; en F3 baja el volumen — el L7 está cerca |
| `Farmers Walk` (HY4 Open) | 2×100 m (2×24/2×16 kg, cronometrado) | 2×100 m (bajo 2:00) | 2×100 m (ritmo exacto) | 2×50 m | 90 s | El re-test entero: 100 m a carga Open sin soltar; F2 exige el objetivo de 2:00; si el agarre falla antes que la postura, añade series de `Farmer Walk` ligero al final de la sesión |
| `Plank` | 3×60 s | 3×60 s | 2×60 s | 2×45 s | 60 s | Mantenimiento del core anti-extensión para los runs tardíos |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Afilar, no construir |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de hoy son el re-test HY4 con su carga nueva: 2×24/2×16 kg, sin soltar, postura erguida. La puerta creció contigo — de 2×16 kg en N3 a los pesos de la carrera. Ciérrala con la misma técnica de siempre (§1.7-Hyrox §1)."

---

# NIVEL 8 — Ritmo de competición

- **Título:** Ritmo de competición — La carrera, a objetivo
- **Objetivo:** ejecutar el simulacro completo a **ritmo de competición** (benchmark L8: mismas dosis Open que L7, cap más exigente y splits afinados), repetir el simulacro en las fases de circuito para afilar transiciones y wall balls, y cerrar el programa con el **PFT final** comparado con la marca base de N1. Las variantes Pro pesan en las sesiones A como objetivo opcional para quien compite en esa categoría. `restAfterBlock` sube a **90 s**: las sesiones son más densas en calidad y más cortas en volumen.
- **Requisitos de entrada:** mínimos del test N7 (Simulacro L7 + HY4 a carga Open).
- **skillFocus (obligatorio):** `Ritmo de competición en la Hyrox completa, repeticiones de simulacro y estrategia de carrera`
- **Puertas evaluadas:** ninguna. HY1-HY4 cerradas (re-validación final de HY4 en N7).

## Test de nivel N8 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Hyrox Simulacro L8 | 1:15:00 (4500 s) | 1:00:00 (3600 s) | lte | seconds |
| 2 | HYROX PFT | 18:00 (1080 s) | 14:00 (840 s) | lte | seconds |

Notas del test:
- Ítem 1: `type: benchmark` sobre `Hyrox Simulacro L8` (placeholder `ID_L8`, ficha de este lote), modalidad RX. Mínimo (1:15:00) bajo el cap de 1:30:00; objetivo (1:00:00) entre elite F (55:00) y advanced M (56:00) — exige runs a ~4:15 y Roxzone bajo control. Regla F-Elizabeth.
- Ítem 2: `type: benchmark` sobre `HYROX PFT` (placeholder `ID_PFT`, ficha del lote 1), en modalidad RX si el atleta la sostiene (pesos Open de la ficha) o scaled siempre disponible. Es el **cierre del programa**: la marca se compara con la base registrada en N1 (mínimo 18:00 vs. base ~24:00-30:00 de N1; objetivo 14:00, en torno a advanced M). El progreso del PFT es la radiografía del programa completo.
- Modo de evaluación: `strict_gates` — superar ambos benchmarks cierra el programa (logro de hito "Hyrox Mastery completada").
- Orden recomendado: PFT primero (descansado, 20-30 min de esfuerzo) y el L8 en sesión separada del envío de test, con al menos una sesión de movilidad entre medias. Los dos en el mismo día solo para atletas con experiencia de competición.

## Logros del nivel N8

**De test:**
- "Ritmo de competición" — `type: test`, `tier: legendario`, `requirementType: test_item_min`, `requirementKey: Hyrox Simulacro L8`.
- "Hyrox bajo la hora" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: Hyrox Simulacro L8`, `requirementValue: 3600`.
- "PFT de cierre" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: HYROX PFT`.
- "PFT sub-14" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: HYROX PFT`, `requirementValue: 840`.

**De hito:**
- "Hyrox Mastery completada" — superación del test de N8 (cierre del programa). `tier: legendario`, `specialCelebration: true`.
- "Repetición de carrera" — segunda ejecución completa del simulacro L7/L8 dentro del cap en una misma fase (circuitos F1/F2/F4 de la sesión C). `tier: epico`.
- "Wall balls sub-5" — `Wall Ball Shot` de 100 reps (6/4 kg) en menos de 5:00 dentro de un simulacro o circuito. `tier: epico`.
- "Splits de carrera" — simulacro o circuito completo con los 8 splits de estación bajo su objetivo (sled push <2:30, pull <3:30, BBJ <5:00, farmers <3:00, lunge <4:00). `tier: raro`.
- "Preparado para Pro" (opcional) — primera sesión A ejecutada con variantes Pro (sled 202/152, pull 153/103, farmers 2×32/2×24, WB 9/6 kg) completa. `tier: legendario`.

## Bloques comunes del Nivel 8

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo completo del programa a dosis de mantenimiento alto; en el nivel de competición el Bloque 0 también es ritual de preparación: idéntico antes de cada simulacro para que el cuerpo lo reconozca como señal de carrera. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 45 s/lado + 45 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 10/lado | 60 s + 10/lado | 75 s + 10/lado | 60 s + 6/lado suave |
| Cadera/cadena posterior (HY4, mantenido) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 45 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (HY2/HY3, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 45 s | 20 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 15 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + ritmo):** `Deep Squat Hold` antes de la wall ball; activación de agarre antes del farmers.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y tobillo; en F3 (simulacro L8) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico — es el ritual de carrera.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; ensayo del PFT en F3.

### Bloque postural (prehab — selección N8)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. En N8 el prehab se blinda: con la densidad de simulacros del nivel, es la única carga que no se negocia.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del programa a dosis F4.

## Fases del ciclo (N8)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Primera repetición del simulacro L7 a ritmo (circuitos de 8 estaciones con splits); PFT en RX como toma de contacto; estrategia de carrera en cada WOD |
| 2 — Progresión | 80% | Circuitos de ritmo con transiciones a 20 s; runs 1 km a ritmo objetivo con ritmo negativo; wall balls ×100 sub-6:00 en circuito |
| 3 — Intensificación | 95% | **Benchmark embebido: Hyrox Simulacro L8 en sesión C**; sesiones A/B/D a "afilar, no construir"; PFT de cierre en D |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 8

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 90 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido y las sesiones A/B/D pasan a "afilar, no construir".

### Sesión A — Intervals a ritmo de carrera con estación intercalada (todas las fases)

- Tipo: ritmo de carrera (no fuerza). Duración estimada: 70-85 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (intervals a ritmo de carrera) | 4×1 km a ritmo objetivo (~4:30 M) | 5×1 km (ritmo negativo: los dos últimos más rápidos) | 3×1 km (ritmo exacto, afilar) | 2×1 km suave | 90 s | El ADN del L8: 8 km a 4:15-4:30; F2 entrena el ritmo negativo; F3 repite el ritmo exacto sin novedad |
| `Wall Ball Shot` (6 kg, ×100 en series) | 4×25 (series planificadas) | 4×25 (sub-6:00 total) | 2×25 (ritmo exacto) | 2×15 suave | 90 s | La estación 8 se decide en el entrenamiento: 4 series de 25 con descansos de 10-15 s; objetivo sub-5:00 en carrera (§3) |
| `Sled Push` (Open, mantenimiento de split) | 2×50 m (split 2:30 M) | 2×50 m (bajo split) | 2×25 m (ritmo exacto) | 2×25 m suave | 2 min | Volumen bajo, split exacto: la fuerza ya está construida — se afila; **variante Pro en nota:** 202/152 kg si compites Pro |

**Bloque `wod` (run→estación→run, ritmo continuo):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 1 km + `Sled Push` 50 m (152/102 kg, split) + `Sprint` 400 m | 2 min entre rondas; el km 2-3 de la carrera con la estación 2 por medio |
| F2 | 3 rondas | `Sprint` 1 km + `Wall Ball Shot` ×25 + `Sprint` 400 m (más rápido que el primero) | Ritmo negativo explícito: el 400 m de cierre se corre más rápido que el del inicio |
| F3 | 2 rondas | `Sprint` 1 km a ritmo exacto + `Farmers Walk` 200 m (2×24/2×16 kg, split 3:00) | "Afilar, no construir": el par exacto del km 6 con split; **nota Pro:** farmers 2×32/2×24 kg |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Wall Ball Shot` ×10 | Movimiento; test al final del nivel |

**Tips de sesión:** "En N8 no se entrena fuerza, se entrena carrera: cada serie de hoy tiene un split y cada split se anota. El atleta que llega al L8 con ocho splits escritos en la cabeza no corre la carrera, la ejecuta (estrategia, §1.7-Hyrox §1)."

### Sesión B — Ergómetros a ritmo objetivo (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 65-80 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` (split de carrera) | 3×1.000 m (4:20 M) | 3×1.000 m (sub-4:15) | 2×1.000 m (ritmo exacto) | 1×1.000 m suave | 90 s | Split afilado del L8: 10-15 s por debajo del L7; la estación 1 se ejecuta igual que en la sesión de ritmo del lote 2 pero ahora con reloj de competición |
| `Rowing Machine Sprint` (split de carrera) | 2×1.000 m (3:50 M) | 2×1.000 m (sub-3:45) | 1×1.000 m (ritmo exacto) | 1×1.000 m suave | 90 s | La estación 5 con 5 km en las piernas: salida controlada desde el primer drive |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 2×(1.000 m `SkiErg Sprint` a split + 10 `Burpee`) + 1.000 m `Rowing Machine Sprint` a split | SkiErg-burpee ×2 y Row solo | Ensayo de estaciones 1 y 5 con fatiga de transición; §2 en los burpees |
| F3 | 4 × (1' trabajo / 1' descanso) | `SkiErg Sprint` a umbral | Afilar: volumen mínimo, ritmo exacto |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "El SkiErg del L8 se gana por 10 segundos: 4:20 hoy, 4:10 el día de la carrera, y esos 10 segundos son los que separan el objetivo del mínimo. Remada larga, hombros lejos de las orejas (§1.7-Hyrox §3)."

### Sesión C — Repeticiones de simulacro (todas las fases)

- Tipo: circuito de carrera completa o parcial (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 80-95 min (F1/F2/F4); 105-120 min (F3 con el simulacro L8).
- Bloque 0: variante C (mínima fluida en F3 — ritual de carrera). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (6 kg) | 2×25 | 2×25 (sub-1:20 por serie) | 1×15 (calentamiento del simulacro) | 2×15 suave | 60 s | F1-F2 afilan las series de 25 del ×100; en F3 es calentamiento específico: toca las 8 estaciones con dosis mínimas |
| `Broad Jump Burpee` (técnica) | 3×6 | 3×8 | — (el simulacro es el protagonista) | 2×4 | 60 s | Bloques de 10 m con aterrizaje válido (§2); en F2 se cronometra el bloque de 10 m para extrapolar el split de 80 m |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | "Media carrera": 2×(run 1 km + estación ×4) | `Sprint` 1 km + `SkiErg Sprint` 1 km + `Sprint` 1 km + `Sled Push` 50 m + `Sprint` 1 km + `Sled Pull` 50 m + `Sprint` 1 km + `Broad Jump Burpee` 80 m, y se repite el bloque | 3 min entre bloques; primera repetición completa del formato con splits; logro "Repetición de carrera" si se completa en ambas |
| F2 | "Segunda mitad": 2×(run 1 km + estación ×4) | `Sprint` 1 km + `Rowing Machine Sprint` 1 km + `Sprint` 1 km + `Farmers Walk` 200 m + `Sprint` 1 km + `Kettlebell Goblet Lunge` 100 m + `Sprint` 1 km + `Wall Ball Shot` ×100 | 3 min entre bloques; la mitad que decide la carrera; WB ×100 con series de 25 (§3) |
| F3 | **Benchmark embebido: Hyrox Simulacro L8** | `Hyrox Simulacro L8` en modalidad RX (ver ficha): 8×(run 1 km + estación en orden oficial, 100% Open) a ritmo de competición | Stopwatch + cap 1:30:00; la marca alimenta el test. Plan: splits por estación escritos, ritmo negativo, Roxzone de 15-20 s, hidratación en cada transición; **nota Pro:** si compites Pro, esta sesión es tu simulacro con pesos Pro (202/152, 153/103, 2×32/2×24, goblet 28/20, WB 9/6 kg ×100) |
| F4 | 2 rondas suaves | `Sprint` 800 m + `Wall Ball Shot` ×10 | Movimiento; test al final del nivel |

**Tips de sesión:** "El L8 es la misma carrera del L7 con un solo cambio: el reloj. Cada split escrito es una decisión ya tomada; en la carrera no hay que decidir nada, solo ejecutar. Corre los primeros 4 km como si sobrara tiempo — porque sobra (estrategia de ritmo negativo)."

### Sesión D — Aeróbico + PFT de cierre + movilidad (todas las fases)

- Tipo: aeróbico + PFT + movilidad. Duración estimada: 70-85 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques de carrera) | 50 min (6×3 min a ritmo objetivo) | 55 min (6×3 min, ritmo negativo) | 45 min suave (afilar) | 40 min Z2 suave | — | Mantenimiento aeróbico del nivel más rápido del programa; en F3 baja el volumen — el L8 y el PFT están cerca |
| `Farmers Walk` (PFT en contexto) | 2×100 m (2×24/2×16 kg) | 2×100 m (sub-2:00) | 2×100 m (ritmo exacto) | 2×50 m | 90 s | Los 100 m del PFT de cierre; **nota Pro:** 2×32/2×24 kg |
| `Broad Jump Burpee` (ritmo) | 3×10 m (cronometrado) | 3×10 m (sub-0:40) | 2×10 m (ritmo exacto) | 2×5 suave | 60 s | El bloque de 10 m cronometrado extrapola el split de 80 m; aterrizaje silencioso siempre (§2) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | **PFT en RX (toma de contacto)** | `HYROX PFT` en modalidad RX (ficha del lote 1): run 1 km → burpees ×50 → farmers 100 m → WB ×50 → row 1 km → sled push 50 m → SkiErg 1 km → BBJ 50 m | Primera ejecución del PFT a pesos Open con marca registrada; referencia del cierre |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Afilar, no construir: el L8 está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El PFT de F2 es el mismo test que hiciste en N1, cuando el farmers pesaba la mitad y los wall balls iban a diana baja. La marca de hoy contra la de entonces es el balance de todo el programa: guárdala, te la vas a encontrar en el test de cierre."

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N7-N8: todos los ejercicios citados existen en el catálogo con la grafía usada (verificados contra BD dev en los lotes 1-2, 17-oct-2026; este lote no introduce ejercicios nuevos).
- Duplicados conocidos (heredados, reportados en el lote 2): `Farmers Walk` (340) / `Farmer Walk` (280), `Face Pull` (482) / `Face pull` (756), `Pallof Press` (314) / `Pallof press` (782). Este documento usa siempre la grafía capitalizada. Consolidación pendiente (fuera del alcance del programa).
- Sandbag lunge: se resuelve con `Kettlebell Goblet Lunge` + nota (simula el sandbag), coherente con spec §3 ("0 ejercicios nuevos previstos"). En N7-N8 la dosis es 100 m a 20/12 kg (el peso Open W; el sandbag real de 20/10 kg se simula con goblet por disponibilidad de implementos).
- Sustitutos sin máquinas documentados en notas (mismo criterio que los lotes 1-2): `Assault Bike Sprint`/`Echo Bike Sprint` para SkiErg/Row; `Step Up` cargado para Sled Push; `Kettlebell Swing Russian` pesado para Sled Pull.
- Con este lote el programa queda completo: 8 niveles × 16 sesiones = 128 trainings, 6 fichas de benchmark (PFT, L3, L5, L6, L7, L8), 4 puertas HY1-HY4 con calendario de evaluación (N2, N3, re-tests N4/N6/N7) y ~55 logros.

## Decisiones de contenido tomadas (para revisión del controller)

1. **L7 y L8 comparten dosis y array de ejercicios; solo varían name, timeCapSeconds y referencias:** L7 = completar la carrera Open (cap 1:45), L8 = completarla a ritmo de competición (cap 1:30, referencias un escalón por encima). Se mantienen como fichas separadas por ser marcas distintas de test (construcción vs. afilado), aunque se acepta la redundancia si la plataforma la tolera mal (nota de diseño explícita en §Benchmarks).
2. **Re-validación de HY4 en N7 con carga Open (2×24/2×16 kg ×100 m):** la puerta "madura" a su carga de competición al entrar en el tramo Open; la `criteria` de la puerta se actualiza al superar N6. Cierra el arco HY1-HY4 (crecimiento de carga: N3 2×16 kg → N4 re-test 2×16 kg → N7 re-test 2×24/2×16 kg). A confirmar con el usuario.
3. **Test N8 con dos ítems benchmark** (L8 + PFT): el PFT como cierre comparado con la marca base de N1 (placeholder `ID_PFT`, misma ficha; la comparación es narrativa/logro, no automática — limitación conocida de rangos, spec §4.2).
4. **`restAfterBlock` 60 s en N7 y 90 s en N8** (regla del proyecto: ≤6 → 60 s, resto → 90 s), coherente con la convención de los lotes anteriores.
5. **Volumen de sled reducido respecto a N6:** la fuerza está construida; en N7-N8 el sled se entrena con splits (50 m cronometrados, series largas de descanso, volumen bajo). Las sesiones A de N8 son de ritmo, no de fuerza.
6. **Variantes Pro solo en notas de sesiones A y C y en scalingOptions** (nunca RX): sled 202/152, pull 153/103, farmers 2×32/2×24, goblet 28/20, WB 9/6 kg ×100. Logro opcional "Preparado para Pro" (`tier: legendario`) para quien entrena una sesión completa con pesos Pro.
7. **Interferencia en F3 de N7/N8 ("afilar, no construir"):** las sesiones A/B/D de la fase del simulacro repiten ritmos exactos con volumen mínimo y nada de carga nueva; el PFT de N8 se ejecuta en F2 de la sesión D, nunca en la fase del L8.
8. **Splits objetivo cuadrados con los objetivos de test:** el objetivo L7 (1:15:00 M) = 8 runs × 4:45 (38:00) + estaciones 31:30 + Roxzone ~5:30 (ver §Benchmarks); el objetivo L8 (1:00:00) exige runs a ~4:15 y Roxzone de 15-20 s. Los splits de las sesiones usan esta tabla.
9. **Estrategia de carrera explícita en tips:** ritmo negativo, Roxzone como transición activa (3-5 pasos + 3 respiraciones), series planificadas en WB (4×25) y BBJ (bloques de 10 m), hidratación en cada transición de los simulacros largos.
10. **Duración estimada de la sesión C de N7 F3: 110-135 min** — la sesión más larga del programa (simulacro L7 con cap 1:45:00 + bloques); consistente con la convención del spec §4.3 de revisar las estimaciones tras generar.
