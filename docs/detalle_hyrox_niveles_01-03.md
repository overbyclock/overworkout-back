# Detalle de contenido — Niveles 1, 2 y 3 — Hyrox Mastery v1

> Documento fuente desde el que se generará el programa Hyrox Mastery (comando
> `app:create-hyrox-mastery`), en la línea de `detalle_crossfit_niveles_01-03.md`.
> Especificación de autoridad:
> `docs/superpowers/specs/2026-10-17-hyrox-mastery-design.md` (§2 formato de
> carrera temporada 26/27, §3 cobertura de catálogo, §4.1 benchmarks, §4.2
> puertas y tests, §4.3 patrón de sesiones A/B/C/D).
>
> **Estado de redacción:** COMPLETO. Niveles 1, 2 y 3 con sus 12 sesiones
> (4 fases × A/B/C/D por nivel), tests, puertas HY1-HY4, skillFocus, logros y
> las 2 fichas de benchmark del lote (HYROX PFT y Hyrox Simulacro L3).
> Los simulacros L5-L8 se especifican en el lote de niveles 4-6/7-8. Pendiente
> la validación de contenido con el usuario (gate humano).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (spec §4.3): A — Run + estaciones de fuerza; B — Ergómetros (SkiErg/Row) + core; C — Simulacro/circuito (en N1-N2 siempre circuitos de pares de estaciones; en N3 la sesión C de Fase 3 = benchmark embebido del simulacro); D — Aeróbico Z2 + movilidad.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab hombro/lumbar/tobillo rotado por sesión) → `descarga`. `restAfterBlock` = 60 s en N1-N3.
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Dosis `/lado` se computan una vez.
- **RIR:** en N1-N3 se trabaja con 2-4 RIR en fuerza de estación; nunca al fallo. En circuitos, el fallo técnico marca el escalado (regla §1.7-Hyrox §5).
- **Equipamiento asumido N1-N3:** gimnasio con SkiErg, remo, sled (push/pull), wall balls, kettlebells/mancuernas, y cinta o exterior para correr. Si el gimnasio no tiene SkiErg ni remo, sustituto calórico: `Assault Bike Sprint` o `Echo Bike Sprint` (ambos en catálogo) en dosis por tiempo equivalentes (nota en la sesión). Si no hay sled, sustituto de push: `Step Up` con carga; de pull: `Kettlebell Swing Russian` pesado (notas de sesión).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta `SELECT name FROM exercises`, 17-oct-2026). Nombres literales.
- **Convención de nombres de ítems de test:** los ítems que corresponden a ejercicio de catálogo usan su grafía exacta con la carga entre paréntesis (p. ej. `Wall Ball Shot (WB 4 kg)`); los benchmarks se nombran como nombre propio ("HYROX PFT", "Hyrox Simulacro L3").
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.
- **Pesos:** el programa se divide con pesos **Open** como base (temporada 26/27). Las variantes **Pro** aparecen solo como objetivo opcional en notas de niveles 7-8 (fuera de este lote).
- **Running semanal:** el volumen de carrera sube de ~25 min Z2 semanales en N1 a ~50-60 min mixtos (Z2 + intervals + 1 km Z3) en N3, repartidos entre las sesiones A y D.

---

## Puertas de movilidad del programa (definición completa, spec §4.2)

Se evalúan en el nivel **anterior** al que exigen. Fallar fuerza + puerta = `mobility_blocked` (ciclo corto de movilidad, sin repetir fuerza). HY1-HY4 son barreras de seguridad: sin ellas no se sube de nivel aunque la fuerce sobre. Se re-verifican como `re_test` en niveles 4/6/8 (fuera de este lote).

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| HY1 | Kilómetro continuo | tobillo, rodilla, talón de Aquiles (base del volumen de carrera) | Correr 1 km completo sin caminar ni parar, a ritmo conversacional, sin dolor matinal >2/10 en tobillo/rodilla al día siguiente | N2 (intervals de run 400-800 m y carrera como trabajo principal) | **N1** (semilla) / **N2** |
| HY2 | Hombro de wall ball | hombro, muñeca, columna torácica | `Wall Ball Shot` con WB 4 kg ×30 sin romper series (descansos solo con la bola abajo, max 5 s), cada rep a diana baja válida | N2 (wall balls 4 kg como dosis principal) | **N2** |
| HY3 | Motor de burpee | hombro, muñeca, lumbar | `Burpee` ×20 completos (pecho al suelo, salto con extensión) en menos de 2:00, sin perder la línea lumbar | N2 (burpees en circuitos con fatiga) | **N2** |
| HY4 | Agarre de farmers | agarre, trapecio, cadena posterior | `Farmers Walk` con 2×16 kg ×100 m sin soltar las cargas, con postura erguida y pasos cortos | N3 (farmers 2×16 kg como dosis de estación) | **N3** |

**Justificación de las puertas.** El formato Hyrox es 8 km de carrera intercalados con 8 estaciones: el tobillo y la rodilla (HY1) soportan el 50% del tiempo de carrera; el hombro (HY2) acumula ~200 reps de wall ball en carrera completa más el SkiErg; la cadena lumbar (HY3) decide si el burpee broad jump de 80 m se hace con cadera o con espalda; y el agarre/lumbar posterior (HY4) es el cuello de botella real del farmers carry de 200 m. Cada puerta se siembra en el Bloque 0 un nivel antes de exigirse y se evalúa en el nivel que la lista indica.

Evaluación en este documento: **N1: ninguna** (se siembra HY1-HY4 en Bloque 0 sin evaluar). **N2: HY1, HY2, HY3.** **N3: HY4.**

---

## Regla §1.7-Hyrox en tips (análoga a §1.7-CrossFit)

Aplicar en `tips` de niveles y sesiones:

1. **El trineo se progresa por carga antes que por velocidad:** nunca sprintar un sled con la espalda redondeada; la técnica del push (zancada corta, torso alto, brazos extendidos) y del pull (peso atrás, pasos cortos) se aprende sin carga o ligera antes de cronometrar nada.
2. **Burpee broad jump = aterrizaje silencioso antes que distancia:** cada salto se valida por la recepción (rodillas alineadas, cadera atrás), no por los metros; la distancia sin control es rodilla y lumbar.
3. **Wall balls: la fatiga de hombros se gestiona con series planificadas, nunca con rebotes descontrolados:** si la bola rebota en la cara o cae de las manos, la serie ya terminó — descansa 5 s y vuelve con la misma técnica.
4. **Los runs en fatiga se entrenan progresivamente:** primero estación→run corto (100-200 m), luego medio (400-500 m), luego largo (1 km); no invertir el orden. El Roxzone se gana con transiciones entrenadas, no con sprint de recuperación.
5. **Dolor matinal >2/10 en rodilla/lumbar/hombro = congelar el gesto un ciclo:** sustituir la estación que lo provoca por su variante asistida y retomar la progresión en el ciclo siguiente.

---

## Benchmarks del lote (fichas completas, spec §4.1)

Dos fichas se siembran con este lote. Ambas `for_time`, `timerConfig: null` (stopwatch con cap), `partitioningAllowed: false`. Las referencias de tiempo van en `beginner/intermediate_time_*` (M/F) contrastadas con tablas públicas del PFT (Hyrox) para la ficha 1 y coherencia interna con el cap para la ficha 2. Los pesos de los simulacros L5-L8 se especifican en lotes posteriores.

### Benchmark 1 — "HYROX PFT"

El Physical Fitness Test oficial de clasificación (dosis cortas), usado como marca de base del programa.

| Campo | Valor |
|-------|-------|
| name | HYROX PFT |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 2400 (40:00) |
| rxWeightMale | farmers 2×24 kg, WB 6 kg, sled push 152 kg |
| rxWeightFemale | farmers 2×16 kg, WB 4 kg, sled push 102 kg |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios (orden fijo, en el orden del PFT oficial: estación → run corto de transición embebido en la nota del finish):

| # | Ejercicio (grafía catálogo) | Dosis | Nota |
|---|------------------------------|-------|------|
| 1 | `Sprint` | 1 km | nota + durationSeconds (~4:30-6:00 según nivel) |
| 2 | `Burpee` | 50 reps | completos; validez §1.7-Hyrox §5 (HY3) |
| 3 | `Farmers Walk` | 100 m | RX 2×24/2×16 kg; sin soltar (§HY4) |
| 4 | `Wall Ball Shot` | 50 reps | RX 6/4 kg a diana oficial; series planificadas (§3) |
| 5 | `Rowing Machine Sprint` | 1 km | ritmo sostenido, no sprint inicial |
| 6 | `Sled Push` | 50 m | RX 152/102 kg; zancada corta, torso alto (§1) |
| 7 | `SkiErg Sprint` | 1 km | ritmo Z3-Z4; remada larga de piernas-brazos |
| 8 | `Broad Jump Burpee` | 50 m | aterrizaje silencioso (§2); si no hay espacio, `Burpee` ×25 en su lugar |

scalingOptions (progresiones): **scaled N1** — farmers 2×12/2×8 kg, WB 4 kg a diana baja, sled sin carga o ausente (se anota), BJJ por `Burpee` si HY3 no está; **progresión 50% Open** — farmers 2×12/2×8 kg, WB 4 kg, sled push 76/51 kg; **variantes Pro (notas, niveles 7-8)** — farmers 2×32/2×24 kg, WB 9/6 kg, sled push 202/152 kg.

Referencias de tiempo (contraste con tablas públicas PFT, 2026):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 09:00 | 10:00 |
| advanced | 13:00 | 15:00 |
| intermediate | 17:00 | 20:00 |
| beginner | 22:00 | 26:00 |

Uso en test: N1 (marca base, lte, mínimo 30:00 / objetivo 24:00, ambos bajo el cap de 40:00). Re-test en niveles 3/5/8 según spec §5.

### Benchmark 2 — "Hyrox Simulacro L3"

Media Hyrox: 4 estaciones con runs 500 m intercalados, pesos ligeros (50% Open). Es el primer simulacro del programa y el test de N3.

| Campo | Valor |
|-------|-------|
| name | Hyrox Simulacro L3 |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 2700 (45:00) |
| rxWeightMale | sled push 76 kg, sled pull 52 kg, WB 4 kg |
| rxWeightFemale | sled push 51 kg, sled pull 39 kg, WB 4 kg |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios (estructura run→estación, 4 rondas; el jugador registra cada estación como "ronda"):

| # | Ejercicio (grafía catálogo) | Dosis | Nota |
|---|------------------------------|-------|------|
| 1 | `Sprint` | 500 m | Run 1 (tras él, SkiErg) |
| 2 | `SkiErg Sprint` | 500 m | ritmo Z3; transición corta |
| 3 | `Sprint` | 500 m | Run 2 (tras él, Sled Push) |
| 4 | `Sled Push` | 25 m | 76/51 kg; zancada corta (§1) |
| 5 | `Sprint` | 500 m | Run 3 (tras él, Sled Pull) |
| 6 | `Sled Pull` | 25 m | 52/39 kg; peso atrás, pasos cortos |
| 7 | `Sprint` | 500 m | Run 4 (tras él, Wall Balls) |
| 8 | `Wall Ball Shot` | 25 reps | WB 4 kg a diana baja; series de 5-10 (§3) |

scalingOptions: **scaled** — sled push sin carga/pull con banda, WB 4 kg a diana baja, runs a 400 m; **subida (N5+)** — pesos 75% Open y runs 1 km (se resuelve en el lote L5). Referencias de tiempo (estimadas, sin tabla pública; coherentes con el cap de 45:00):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 24:00 | 27:00 |
| advanced | 28:00 | 32:00 |
| intermediate | 32:00 | 37:00 |
| beginner | 38:00 | 42:00 |

Uso en test: N3 (lte, mínimo 35:00 / objetivo 28:00, bajo el cap de 45:00 — regla F-Elizabeth).

**Nota sobre rxWeight:** las dosis de test de este lote (N1 PFT escalado, N3 simulacro al 50%) se resuelven por `scalingOptions` + elección RX/Scaled del player, sin crear copias del benchmark (mismo criterio que CrossFit, decisión D2). Las fichas guardan los pesos Open oficiales (PFT) y los pesos de simulacro definidos (L3, 50% Open según spec §4.1); las subidas de carga de L5+ van en sus fichas.

---

# NIVEL 1 — Base Híbrida

- **Título:** Base Híbrida — Motor aeróbico y técnica de estaciones sin carga
- **Objetivo:** construir el motor aeróbico (run Z2 de 15 a 30 min por tiempo), enseñar la técnica de las 8 estaciones sin carga o con carga mínima (SkiErg/Row con intervalos cortos, wall ball 4 kg a diana baja, sled sin carga si el gimnasio permite, lunges al peso corporal, burpees parciales) y tejer el core y la movilidad densa que sostendrán el volumen de N2-N3. Aquí no se corre deprisa ni se carga nada: se aprende. El test de salida es el PFT como **marca base**, con mínimo generoso.
- **Requisitos de entrada:** ninguno. Diseñado para quien nunca ha tocado un SkiErg, un sled ni ha corrido 30 minutos seguidos.
- **skillFocus (obligatorio):** `Técnica de estaciones sin carga, carrera Z2 y core anti-extensión`

**Puertas de movilidad activas:** ninguna evaluada. Se siembra HY1 (kilómetro continuo), HY2 (hombro de wall ball), HY3 (motor de burpee) y HY4 (agarre de farmers) como trabajo permanente de Bloque 0, sin evaluación.

## Test de nivel N1 (resumen)

Un único ítem de tipo benchmark, auto-evaluado al final de la Fase 4 (Recuperación), tras un día de movilidad. Es una marca de base: el objetivo es terminarlo, registrar el tiempo y sentir el formato completo.

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | HYROX PFT | 30:00 (1800 s) | 24:00 (1440 s) | lte | seconds |

Notas del test:
- Se ejecuta el benchmark `HYROX PFT` en modalidad **Scaled del nivel** (farmers 2×12/2×8 kg, WB 4 kg a diana baja, sled push sin carga o sustituido — ver scalingOptions de la ficha). La marca se registra en `UserBenchmarkAttempt` con `scalingUsed = scaled` y alimenta el ítem del test.
- El mínimo (30:00) está **bajo el cap de la ficha (40:00)** y muy por encima de beginner F (26:00): cualquier persona que haya completado el nivel terminando el PFT lo supera. El objetivo (24:00) se sitúa entre beginner M y advanced M.
- En N1 el PFT es hito, no filtro: no superarlo no bloquea la progresión al re-test, pero el test solo se da por superado con el tiempo registrado.

## Logros del nivel N1

**De test (por ítem de test, se evalúan en cualquier envío de test):**
- "Primera marca PFT" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: HYROX PFT`.
- "PFT bajo 24 minutos" — `type: test`, `tier: raro`, `requirementType: test_item_obj`, `requirementKey: HYROX PFT`, `requirementValue: 1440`.

**De hito (primera ejecución válida en sesión):**
- "Primer kilómetro sin parar" — primer `Sprint` de 1 km completo sin caminar (siembra HY1). `tier: comun`.
- "Primer SkiErg continuo" — primer `SkiErg Sprint` de 1.000 m completado sin parar la máquina. `tier: comun`.
- "Primer trineo empujado" — primer `Sled Push` completado (aunque sea sin carga), técnica validada. `tier: comun`.
- "Primer wall ball a diana" — primer `Wall Ball Shot` de 4 kg válido a diana baja desde sentadilla profunda. `tier: comun`.

## Bloques comunes del Nivel 1

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 6-10 min)

Transiciones de ~5 s entre ejercicios. Contenido base de toda sesión N1 (movilidad acumulativa; siembra de las cuatro puertas):

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo (siembra HY1) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 20 s/lado + 20 s/lado + 10 | 25 s/lado + 25 s/lado + 12 | 30 s/lado + 30 s/lado + 15 | 30 s/lado + 30 s/lado + 12 suave |
| Rodilla/cuádriceps (siembra HY1) | `Deep Squat Hold` + `Cossack Squat` | 20 s + 4/lado | 30 s + 6/lado | 40 s + 6/lado | 45 s + 6/lado suave |
| Cadera/bisagra (siembra HY4) | `Glute Bridge` + `Kneeling Hip Flexor Stretch` + `Adductor Rockback` | 10 + 20 s/lado + 8 | 12 + 25 s/lado + 10 | 15 + 30 s/lado + 10 | 10 suave + 30 s/lado + 8 suave |
| Hombro/muñeca (siembra HY2/HY3) | `Shoulder Dislocate` (banda ligera) + `Band Pull-Apart` + `Wrist Prayer` | 10 + 10 + 20 s | 12 + 12 + 20 s | 15 + 12 + 25 s | 12 + 10 + 20 s suave |
| Activación escápula (siembra HY2) | `Scapular Push Up` | 8 | 10 | 12 | 10 suave |

Variantes por tipo de sesión:
- **A (Run + estaciones):** añade `Active March` extra ×30 s y `Deep Squat Hold` final; prepara piernas para correr.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática 360 ×5 ciclos al final.
- **C (Circuito):** énfasis en tobillo (`Calf Stretch Wall` 30 s/lado) y muñeca (HY3); `Clamshell` ×10/lado si el circuito incluye lunges.
- **D (Aeróbico):** énfasis en cadera y rodilla; la descarga se amplía.

### Bloque postural (prehab — selección N1, dosificado por sesión, nunca eliminado)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Tobillo/core | `Dead Bug` + `Bird Dog` + `Pallof Press` (banda ligera) | 2×8/lado / 2×6/lado / 2×10/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 2×8 / 2×8 / 2×12 |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×8/lado / 2×6/lado / 2×30 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×12 / 2×12 / 2×8/lado |

Descanso entre ejercicios posturales: 45-60 s. Criterio: calidad por encima de fatiga; es prehab, no trabajo principal. Progresión: subir +1 serie al ejercicio más fácil cuando la calidad se mantiene dos sesiones seguidas.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

- Respiración diafragmática tumbado (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada en la sesión (30-45 s por zona, de la selección de Bloque 0). En Fase 4 la descarga se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N1)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Técnica de estaciones sin carga; run Z2 de 15 min; intervalos de ergómetro muy cortos (100-250 m) |
| 2 — Progresión | 80% | Run Z2 de 20 min; wall ball 4 kg a diana baja; sled sin carga técnico; primeras transiciones run→estación (100-150 m) |
| 3 — Intensificación | 95% | Run Z2 de 25-30 min; primer 1 km continuo (ensayo HY1); circuitos de 2 estaciones con run 200 m |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; PFT al final del nivel |

## Sesiones del Nivel 1

Las 16 sesiones = 4 fases × tipos A/B/C/D. Cada sesión tiene los 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: ver "Bloques comunes" con la variante y dosis de fase indicadas. Bloque postural: tabla de la sesión correspondiente.

### Sesión A — Run + estaciones de fuerza ligera (todas las fases)

- Tipo: fuerza + carrera. Duración estimada: 50-60 min.
- Bloque 0: variante A, dosis de fase. Postural: tabla sesión A (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (run Z2 por tiempo, cinta o exterior) | 15 min | 20 min | 25 min | 12 min suave | — | Ritmo conversacional (Z2); si hay que caminar, se camina y se anota — el objetivo de N1 es llegar a 30 min seguidos en F3-F4 |
| `Goblet Squat` | 3×8 | 3×10 | 3×12 | 2×8 suave | 90 s | KB 8/6 kg; cadera bajo la rodilla, talones pegados; base del lunge de N2 y del wall ball |
| `Wall Ball Shot` (4 kg, diana baja) | 3×6 | 3×8 | 4×8 | 2×6 suave | 90 s | Siembra HY2: la bola parte de sentadilla profunda y sube con las piernas, no con los brazos solos; series limpias (§3) |
| `Sled Push` (sin carga) | 3×10 m | 3×15 m | 4×15 m | 2×10 m | 90 s | Solo si el gimnasio permite sled sin carga: zancada corta, torso alto, brazos extendidos (§1). Si no hay sled: `Step Up` 3×8/lado con mancuernas ligeras |

**Bloque `wod` (primeras transiciones run→estación):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 100 m + `Wall Ball Shot` ×5 (4 kg, diana baja) | 60-90 s entre rondas; transición caminando, sin prisa |
| F2 | 4 rondas | `Sprint` 150 m + `Wall Ball Shot` ×8 | 60 s entre rondas; primer contacto con la lógica de Roxzone |
| F3 | 4 rondas | `Sprint` 200 m + `Burpee` ×5 | Ensayo de HY3 en contexto; burpees completos pero lentos |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Wall Ball Shot` ×5 | Movimiento, no fatiga; PFT al final del nivel |

**Tips de sesión:** "Hoy la estrella es el run de 15-25 minutos: si terminas y aún podrías conversar, el ritmo era el correcto. La wall ball de hoy es un squat con una bola encima, no un press (§1.7-Hyrox §3)."

### Sesión B — Ergómetros: SkiErg + Row (todas las fases)

- Tipo: técnica ergómetros + core. Duración estimada: 45-55 min.
- Bloque 0: variante B, dosis de fase. Postural: tabla sesión B (hombro). Descarga: estándar. Si no hay SkiErg/remo: `Assault Bike Sprint` por el mismo tiempo (nota en sesión).

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` (intervalos técnica) | 4×100 m | 5×100 m | 4×250 m | 3×100 m suave | 60 s | Técnica primero: bisagra de cadera, brazos largos, remada de piernas-core-brazos; ritmo Z2-Z3, nunca sprint (§1 aplica también al ergómetro) |
| `Rowing Machine Sprint` (intervalos técnica) | 4×250 m | 4×250 m | 3×500 m | 3×250 m suave | 60 s | Drive con piernas, luego espalda, luego brazos; recuperación al revés; ritmo que permite hablar |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Sit-up` + 10 `Glute Bridge` + 30 s `Plank` | 60 s entre rondas; core básico |
| F2 | AMRAP 8' | 6 `Kettlebell Swing Russian` (16/12 kg) + 8 `Mountain Climber` | Primer WOD del nivel; bisagra con KB (preparación del sled pull) |
| F3 | 4 × (30 s trabajo / 30 s descanso) | `SkiErg Sprint` + `Mountain Climber` alternando | Umbral suave; transiciones rápidas entre máquina y suelo |
| F4 | 2 rondas suaves | 8 `Sit-up` + 8 `Glute Bridge` | Movimiento; PFT al final del nivel |

**Tips de sesión:** "En el SkiErg y el remo el orden de la remada manda: piernas-core-brazos a la ida, brazos-espalda-piernas a la vuelta. Un ergómetro mal remado es lumbar de regalo (§1.7-Hyrox §5)."

### Sesión C — Circuito de pares de estaciones (todas las fases)

- Tipo: circuito + core. **Sin simulacro** en N1. Duración estimada: 45-55 min.
- Bloque 0: variante C, dosis de fase. Postural: tabla sesión C (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación técnica, corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Walking Lunge` (técnica pausada) | 2×10 | 2×12 | 3×12 | 2×10 suave | 60 s | Paso largo, rodilla alineada, torso alto; semilla del sandbag lunge (carga llega en N2-N3) |
| `Burpee` (técnica, sin prisa) | 2×5 | 2×6 | 3×8 | 2×5 suave | 60 s | Siembra HY3: pecho al suelo, salto corto con control; aterrizaje silencioso (§2) |

**Bloque `wod` (pares de estaciones con fatiga de transición):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 8 `Wall Ball Shot` (4 kg, diana baja) + 6 `Burpee` | 60-90 s entre rondas; primer par empuje-motora |
| F2 | 4 rondas | 10 `Wall Ball Shot` + 8 `Burpee` | 60 s entre rondas; series planificadas en la WB (§3) |
| F3 | 4 rondas | 10 `Wall Ball Shot` + 10 `Walking Lunge` + 40 m `Farmers Walk` (mancuernas ligeras) | Primer triple estación→carga; siembra HY4 con carga mínima |
| F4 | 2 rondas suaves | 6 `Wall Ball Shot` + 6 `Burpee` | Movimiento; PFT al final del nivel |

**Tips de sesión:** "Los pares de hoy imitan la carrera: una estación te quita el aire, la otra lo devuelve. Transiciones caminando y contadas: el Roxzone también se entrena (§1.7-Hyrox §4)."

### Sesión D — Aeróbico Z2 + movilidad (todas las fases)

- Tipo: aeróbico + movilidad extra. Duración estimada: 45-60 min.
- Bloque 0: variante D, dosis de fase. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 continuo) | 15 min | 20 min | 25 min (ensayo 1 km final) | 12 min suave | — | El run largo del nivel; en F3 se cierra con 1 km continuo sin caminar (ensayo HY1) si el cuerpo lo permite |
| `Broad Jump Burpee` (técnica, pocas reps) | 2×4 | 2×5 | 3×5 | 2×3 suave | 60 s | Solo técnica de aterrizaje (§2): distancia corta, recepción perfecta; volumen de verdad llega en niveles altos |
| `Plank` | 3×30 s | 3×40 s | 3×45 s | 2×30 s | 60 s | Costillas abajo; respiración bajo tensión; base del core anti-extensión del PFT |

**Bloque `wod` (aeróbico suave):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 8 `Step Up` (bajo, ritmo suave) + 10 `Mountain Climber` + 8 `Glute Bridge` | 60-90 s entre rondas; sin carga |
| F2 | 4 rondas | 10 `Step Up` + 10 `Mountain Climber` + 10 `Sit-up` | 60 s entre rondas |
| F3 | 3 rondas | 10 `Step Up` (con mancuernas ligeras) + 10 `Kettlebell Swing Russian` (16/12 kg) + 30 s `Hollow Body Hold` | Primer gesto con carga sostenida; control lumbar al final |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; PFT al final del nivel |

**Tips de sesión:** "La sesión D parece fácil y es la que te deja correr todo el programa: 30 minutos Z2 hoy son el kilómetro 6 de tu futura Hyrox. No la saltes."

---

# NIVEL 2 — Primeras cargas

- **Título:** Primeras cargas — El 50% de Open entra en escena
- **Objetivo:** introducir los pesos de estación al ~50% de Open (farmers 2×12 kg, wall ball 4 kg a diana media, sandbag/KB lunge 8-12 kg, sled con carga ligera ~60-75 kg), progresar los intervals de run de 400 a 800 m y entrenar las primeras transiciones run→estación con lógica de Roxzone. El test mezcla ítems de fuerza (wall ball y burpee) con las puertas HY1-HY3.
- **Requisitos de entrada:** test N1 superado (PFT con marca registrada) o, para atletas de entrada directa, correr 15 min Z2 sin parar.
- **skillFocus:** `Pesos 50% Open por estación, intervals de carrera 400-800 m y transiciones de Roxzone`
- **Puertas evaluadas:** **HY1** (kilómetro continuo), **HY2** (hombro de wall ball) y **HY3** (motor de burpee) — requisito de entrada efectivo del N3 (runs de 1 km a Z3 y estaciones con fatiga). Re-test implícito en cada envío de test. Ensayo de HY1 integrado en la sesión D (run largo con kilómetro final cronometrado); ensayo de HY3 en la sesión C (burpees en contexto).

## Bloques comunes del Nivel 2

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 7-10 min)

Acumulativo de N1 con dosis subidas y foco nuevo: dorsiflexión y sentadilla profunda (técnica de wall ball y lunge cargado). Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, foco) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 12 | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, soporte) | `Deep Squat Hold` + `Cossack Squat` | 30 s + 6/lado | 40 s + 8/lado | 45 s + 8/lado | 60 s (ensayo) + 6/lado suave |
| Cadera/bisagra (HY4, soporte) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 10 + 15 | 30 s/lado + 12 + 15 | 30 s/lado + 12 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (HY2/HY3) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 12 + 25 s | 15 + 15 + 25 s | 15 + 15 + 30 s | 12 + 12 + 20 s suave |
| Activación escápula | `Scapular Push Up` | 10 | 12 | 12 | 10 suave |

Variantes por sesión:
- **A (Run + fuerza):** `Deep Squat Hold` justo antes de la wall ball; énfasis en tobillo antes de los intervals.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Circuito):** añade ensayo HY3: `Burpee` 2×5 lento + `Wrist Push-Up` 2×6; énfasis en muñeca y tobillo.
- **D (Aeróbico):** énfasis en cadera y sentadilla profunda; la descarga se amplía.

### Bloque postural (prehab — selección N2)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×10/lado / 2×8/lado / 2×12/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 2×10 / 2×10 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×10/lado / 2×8/lado / 2×45 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. Progresión: subir una serie al ejercicio más fácil cuando la calidad se mantiene dos sesiones seguidas; nunca al fallo.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que N1: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N2)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Pesos 50% Open por estación con series largas y descanso completo; intervals 400 m; farmers 2×12 kg; sled 60/40 kg |
| 2 — Progresión | 80% | Intervals 600 m; sled 75/50 kg; lunge con KB 8-12 kg; primer circuito run→estación de 300 m |
| 3 — Intensificación | 95% | Intervals 800 m; dosis de test integradas (WB 4 kg ×25, Burpee ×15); ensayo de HY1 en sesión D |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 2

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Run intervals + fuerza de estación (todas las fases)

- Tipo: carrera + fuerza. Duración estimada: 55-65 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (intervals Z3) | 6×400 m | 5×600 m | 4×800 m | 4×400 m suave | 90 s | Entre intervalos, trote muy suave o caminata; ritmo Z3 (incómodo pero sostenible); los 800 m de F3 preparan el kilómetro Z3 de N3 |
| `Farmers Walk` | 3×40 m | 4×40 m | 4×50 m | 2×30 m | 90 s | 2×12/2×8 kg; postura erguida, agarre firme, pasos cortos; ensayo camino de HY4 (los 2×16 kg llegan en N3) |
| `Wall Ball Shot` (4 kg) | 3×10 | 3×12 | 4×15 | 2×10 suave | 90 s | Diana media; series planificadas sin romper (§3); la dosis del test es ×25 — aquí se construye |
| `Sled Push` | 3×4×12,5 m (60/40 kg) | 4×12,5 m (75/50 kg) | 4×12,5 m (75/50 kg) | 2×12,5 m suave | 90 s | Zancada corta y torso alto siempre (§1); progresa carga, no velocidad; sin sled: `Step Up` cargado |

**Bloque `wod` (transición run→estación):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 200 m + `Wall Ball Shot` ×8 (4 kg) | 60-90 s entre rondas; primera transición con carga real |
| F2 | 4 rondas | `Sprint` 300 m + `Farmers Walk` 30 m (2×12/2×8 kg) | 60 s entre rondas; el farmers tras correr es la estación más Roxzone que existe |
| F3 | 4 rondas | `Sprint` 300 m + `Wall Ball Shot` ×10 + `Burpee` ×5 | Ensayo de la lógica del test; series de WB sin romper (§3) |
| F4 | 2 rondas suaves | `Sprint` 200 m + `Wall Ball Shot` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "Los intervals de hoy son el ADN de la Hyrox: nada de sprintar el primer 400 y arrastrar el último. Ritmo parejo, técnica pareja (§1.7-Hyrox §4)."

### Sesión B — Ergómetros umbrales (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 50-60 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar. Sustituto sin máquinas: `Assault Bike Sprint` por el mismo tiempo total.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` | 4×250 m | 4×250 m | 5×250 m | 3×250 m suave | 60 s | Ritmo Z3-Z4; últimos 250 m de cada serie a umbral controlado; remada larga (piernas-core-brazos) |
| `Rowing Machine Sprint` | 3×500 m | 4×500 m | 3×750 m | 2×500 m suave | 90 s | Ritmo de umbral sostenible; el row del PFT es 1 km — aquí se construye la media |

**Bloque `wod` (ergómetro + core anti-rotación):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | AMRAP 10' | 6 `Kettlebell Swing Russian` (16/12 kg) + 6 `Mountain Climber` | Bisagra bajo fatiga; control lumbar (§5) |
| F3 | 5 × (1' trabajo / 1' descanso) | `SkiErg Sprint` (ritmo umbral) | 1.000 m acumulados a ritmo alto; ensayo del SkiErg del PFT |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "El SkiErg del PFT son 1.000 m: hoy acumulas ese volumen por intervalos. Si la remada se acorta y solo tiran los brazos, baja el ritmo — espalda de regalo no (§1.7-Hyrox §5)."

### Sesión C — Circuitos de pares con Roxzone (todas las fases)

- Tipo: circuito + core. **Sin simulacro** en N2. Duración estimada: 50-60 min.
- Bloque 0: variante C (con ensayo HY3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (4 kg, activación) | 2×8 | 2×10 | 2×12 | 2×8 | 60 s | Series cortas limpias; calienta el patrón del circuito |
| `Kettlebell Goblet Lunge` (8/6 kg) | 2×8 | 2×10 | 3×10 | 2×8 | 60 s | Primer lunge cargado; torso alto, rodilla alineada; semilla del sandbag lunge |

**Bloque `wod` (pares con transición run→estación):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 200 m + `Wall Ball Shot` ×10 (4 kg) + `Burpee` ×5 | 60-90 s entre rondas; par empuje-motora |
| F2 | 4 rondas | `Sprint` 300 m + `Kettlebell Goblet Lunge` 10/lado (8-12 kg) + `Sled Push` 12,5 m (60-75 kg) | Par de piernas con fatiga; §1 en el sled |
| F3 | EMOM 12' | Impares: `Sprint` 100 m + `Wall Ball Shot` ×10; pares: `Burpee` ×8 | Ensayo HY3 en contexto; si no llegas al minuto, reduce reps, no técnica |
| F4 | 2 rondas suaves | `Sprint` 200 m + `Wall Ball Shot` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "El EMOM de F3 es tu ensayo de HY3: 20 burpees limpios en menos de 2:00 empiezan por burpees limpios en contexto. Aterrizaje silencioso, siempre (§1.7-Hyrox §2)."

### Sesión D — Aeróbico Z2 + movilidad (todas las fases)

- Tipo: aeróbico + movilidad + HY1. Duración estimada: 50-65 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 continuo) | 25 min | 30 min | 35 min (kilómetro final cronometrado) | 20 min suave | — | En F3 el último kilómetro se cronometra sin caminar: ensayo explícito de HY1 |
| `Dumbbell Lunge` | 2×8/lado | 2×10/lado | 3×10/lado | 2×8/lado suave | 60 s | Mancuernas ligeras; alternativa al goblet lunge con carga en cada mano |
| `Plank` | 3×45 s | 3×50 s | 3×60 s | 2×40 s | 60 s | Costillas abajo; base del core de los ergómetros |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Step Up` + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Dumbbell Lunge` (ligero) + 8 `Push Up` | Primer AMRAP de 12' con carga; bisagra bajo fatiga |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El kilómetro final de F3 se corre sin caminar y ya está: no hace falta rápido, hace falta continuo. HY1 se gana así, en frío y con sueño (§1.7-Hyrox §4)."

## Test de nivel N2 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Wall Ball Shot (WB 4 kg) | 25 reps | 40 reps | gte | reps |
| 2 | `Burpee` | 15 reps | 20 reps | gte | reps |
| 3 | HY1 — Correr 1 km sin caminar | 1 km | 1 km en <6:00 | gte | km |
| 4 | HY2 — 30 wall balls 4 kg sin romper series | 30 reps | 30 reps en <2:30 | gte | reps |
| 5 | HY3 — 20 burpees completos <2:00 | 20 reps | 20 reps en <1:30 | gte | reps |

Notas del test:
- Ítems 1-2: fuerza clásica (`type: fuerza`, `direction: gte`). Criterio de validez: reps completas y limpias — en la wall ball, cada rep desde sentadilla profunda a diana válida (§3); en el burpee, pecho al suelo y salto con extensión (§2). Rep con técnica rota no cuenta.
- Ítems 3-5: puertas (`type: puerta_movilidad` con `gateCode` HY1/HY2/HY3). Mínimos: HY1 = completar el km sin caminar (gte 1 km); HY2 = 30 reps sin romper series; HY3 = 20 reps bajo 2:00. Los objetivos añaden tiempo como referencia de progreso.
- Modo de evaluación del programa: `strict_gates` (spec §4.2) — superar los ítems 1-2 **y** las puertas HY1-HY3 habilita el N3. Fallar fuerza + puerta = `mobility_blocked` (ciclo corto de movilidad).
- El orden recomendado de ejecución (menor a mayor interferencia): HY1 → ítem 1 → HY2 → HY3 → ítem 2, con 2-3 min de descanso entre pruebas.

## Logros del nivel N2

**De test:**
- "Wall ball de 25" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Wall Ball Shot (WB 4 kg)`.
- "Quince burpees de test" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Burpee`.
- "Puerta HY1 superada" / "Puerta HY2 superada" / "Puerta HY3 superada" — `type: test` vinculado a puerta (mobility gate passed). `tier: raro`.

**De hito:**
- "Primer farmers con carga" — primer `Farmers Walk` con 2×12/2×8 kg sin soltar. `tier: comun`.
- "Primer sled cargado" — primer `Sled Push` con carga ≥60/40 kg completado. `tier: comun`.
- "Interval de 800 m completado" — primer intervalo de 800 m de la sesión A terminado al ritmo previsto. `tier: comun`.
- "Primer lunge cargado" — primer `Kettlebell Goblet Lunge` con 8-12 kg válido. `tier: comun`.

---

# NIVEL 3 — Media Hyrox

- **Título:** Media Hyrox — Simulacro y ritmo de Roxzone
- **Objetivo:** ejecutar el primer simulacro embebido (4 estaciones con runs 500 m, pesos 50% Open) como test del nivel, entrenar pares de estaciones con fatiga real, correr 1 km a ritmo Z3 y superar la puerta HY4 (farmers 2×16 kg ×100 m). Al superar N3, el atleta está listo para el tramo de estaciones al 100% Open (N4-N8).
- **Requisitos de entrada:** mínimos del test N2 (incl. HY1-HY3 superadas).
- **skillFocus:** `Simulacro de media Hyrox, pares de estaciones con fatiga y carrera de 1 km a Z3`
- **Puertas evaluadas:** **HY4** (agarre de farmers) — requisito de entrada efectivo de los niveles con farmers al 100% Open. Ensayo integrado en la sesión A (farmers 2×16 kg en el WOD) y en la sesión D (kilómetro de farmers segmentado).

## Bloques comunes del Nivel 3

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1+N2 con foco nuevo: cadena posterior y agarre (HY4) y cadera para lunge cargado. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (HY1, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Sentadilla profunda (HY2, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 40 s + 8/lado | 45 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Cadena posterior/agarre (HY4, foco nuevo) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 12 + 15 | 30 s/lado + 12 + 15 | 40 s/lado + 12 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (HY2/HY3, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 30 s | 15 + 15 + 30 s | 15 + 15 + 30 s | 12 + 12 + 20 s suave |
| Activación escápula | `Scapular Push Up` | 12 | 12 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + fuerza):** `Deep Squat Hold` antes de la wall ball; activación de agarre (`Farmer Walk` ligero 20 m) antes del farmers fuerte.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en tobillo y muñeca; si la fase es la 3 (simulacro), el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; la descarga se amplía.

### Bloque postural (prehab — selección N3)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×8/lado / 3×45 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. El prehab se mantiene a estas dosis en N4-N6 mientras crece el volumen principal del simulacro.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que N1-N2: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N3)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Runs 1 km a Z3 con descanso; estaciones al 50% Open con series largas; farmers 2×16 kg introducido |
| 2 — Progresión | 80% | Pares de estaciones con fatiga (push→pull, WB→burpee); runs 500 m tras estación; ensayo de HY4 |
| 3 — Intensificación | 95% | **Benchmark embebido: Hyrox Simulacro L3 en sesión C**; runs 1 km Z3 consecutivos; nada de sled pesado en la sesión previa del simulacro |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 3

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido.

### Sesión A — Run 1 km Z3 + estaciones con fatiga (todas las fases)

- Tipo: carrera + fuerza de estación. Duración estimada: 60-75 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (1 km a Z3) | 2×1 km | 2×1 km | 3×1 km | 1 km suave | 2 min | Ritmo Z3 sostenible (más rápido que conversar, sin ahogarse); entre kilómetros, trote suave 2 min; la tercera repetición de F3 valida el volumen del simulacro |
| `Sled Push` (50% Open) | 4×12,5 m (75/50 kg) | 4×12,5 m (75/50 kg) | 5×12,5 m (75/50 kg) | 2×12,5 m suave | 90 s | Zancada corta, torso alto (§1); en Fase 3 la carga no sube — el volumen del simulacro está cerca y la interferencia manda |
| `Farmers Walk` (2×16/2×12 kg) | 3×50 m | 4×50 m | 3×100 m (ensayo HY4) | 2×40 m | 90 s | Los 2×16/2×12 kg son la dosis del test; en F3 se ensayan los 100 m completos sin soltar |
| `Wall Ball Shot` (4 kg) | 3×15 | 3×20 | 4×20 | 2×12 suave | 90 s | Series planificadas (§3); acumular volumen de hombro sin llegar al fallo |

**Bloque `wod` (transición larga run→estación):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 400 m + `Wall Ball Shot` ×12 (4 kg) | 90 s entre rondas; primera transición larga |
| F2 | 4 rondas | `Sprint` 500 m + `Sled Push` 12,5 m (75/50 kg) + `Burpee` ×6 | Par push-motora con fatiga real; §1 en el sled |
| F3 | 3 rondas | `Sprint` 500 m + `Farmers Walk` 50 m (2×16/2×12 kg) | Mantener, no construir: el simulacro de la sesión C está cerca |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Wall Ball Shot` ×8 | Movimiento; test al final del nivel |

**Tips de sesión:** "El kilómetro Z3 de hoy es el run que repetirás 8 veces en la carrera: aprende su ritmo de memoria. Y si el farmers de F3 se siente en la espalda baja, revisa el paso — corto y erguido (§1.7-Hyrox §1/§5)."

### Sesión B — Ergómetros umbrales (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 50-60 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `SkiErg Sprint` | 4×500 m | 4×500 m | 3×750 m | 2×500 m suave | 90 s | Ritmo umbral; la dosis del simulacro es 500 m — F1-F2 la ensayan exacta; en F3 sube el volumen por intervalo |
| `Rowing Machine Sprint` | 3×750 m | 2×1 km | 2×1 km | 1×1 km suave | 120 s | El 1 km de Row del PFT se construye aquí; salida controlada, cierre fuerte |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas; bisagra pesada, control lumbar |
| F2 | 5 × (1' trabajo / 1' descanso) | `SkiErg Sprint` a umbral | Acumula 1.000-1.250 m; transiciones rápidas sentado-pie |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | Umbral sostenido; §5 si la lumbar protesta |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "SkiErg y remo en umbral son el corazón silencioso de la Hyrox: no se ven en las fotos, pero deciden los últimos 2 km de carrera. Remada larga hasta el final (§1.7-Hyrox §3)."

### Sesión C — Simulacro/circuito (todas las fases)

- Tipo: circuito de pares (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 55-65 min (F1/F2/F4); 80-90 min (F3 con el simulacro).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Shot` (4 kg) | 2×10 | 2×12 | 1×8 (calentamiento del simulacro) | 2×8 | 60 s | En F3 es calentamiento específico: toca las 4 estaciones del benchmark con dosis mínimas (SkiErg 100 m, sled 5 m, WB ×5) |
| `Kettlebell Goblet Lunge` (12/8 kg) | 2×10 | 3×10 | — (el simulacro es el protagonista) | 2×8 | 60 s | Mantiene el patrón de lunge cargado entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 500 m + `SkiErg Sprint` 250 m + `Wall Ball Shot` ×10 (4 kg) | 90 s entre rondas; par ergómetro-empuje con fatiga |
| F2 | 4 rondas | `Sprint` 500 m + `Sled Push` 12,5 m (75/50 kg) + `Sled Pull` 12,5 m (52/39 kg) | El par push→pull es el más exigente de la carrera; §1 siempre |
| F3 | **Benchmark embebido: Hyrox Simulacro L3** | `Hyrox Simulacro L3` en modalidad del nivel (ver ficha): 4×(run 500 m + estación: SkiErg 500, Sled Push 25 m, Sled Pull 25 m, WB ×25) | Se ejecuta con el paso benchmark del player (stopwatch + cap 45:00); la marca alimenta el test del nivel; logro de hito "Primer simulacro completado". Empezar fresco: nada de sled pesado en la sesión anterior |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Wall Ball Shot` ×8 | Movimiento; test al final del nivel |

**Tips de sesión:** "El simulacro de F3 es la media Hyrox: cada run se sale caminando 3 pasos y corriendo; cada transición se piensa antes de llegar. Terminar bajo 45:00 es el plan; terminar entero es la victoria (§1.7-Hyrox §4)."

### Sesión D — Aeróbico Z2 + movilidad + HY4 (todas las fases)

- Tipo: aeróbico + movilidad. Duración estimada: 55-70 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques Z3) | 30 min (3×3 min Z3 dentro) | 35 min (4×3 min Z3) | 40 min (5×3 min Z3) | 25 min suave Z2 | — | El volumen aeróbico máximo del nivel; los bloques Z3 dentro del Z2 enseñan a correr rápido ya fatigado (§4) |
| `Farmer Walk` (2×16/2×12 kg, HY4) | 3×50 m | 3×75 m | 2×100 m (ensayo HY4 completo) | 2×40 m | 90 s | Kilómetro de farmers segmentado: en F3 se ensayan los 100 m completos sin soltar — el criterio exacto de la puerta |
| `Broad Jump Burpee` (técnica) | 2×5 | 3×5 | 3×6 | 2×4 suave | 60 s | Volumen moderado con aterrizaje perfecto (§2); el BBJ de 50 m del PFT empieza aquí |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Step Up` + 8 `Push Up` | El AMRAP más largo del nivel; control de ritmo de principio a fin |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de F3 se caminan sin soltar o no se caminan: es la puerta HY4 entera. Si el agarre falla a los 70 m, la respuesta es más farmers en las próximas fases, no más ganas hoy (§1.7-Hyrox §1)."

## Test de nivel N3 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Hyrox Simulacro L3 | 35:00 (2100 s) | 28:00 (1680 s) | lte | seconds |
| 2 | HY4 — Farmers 2×16 kg ×100 m sin soltar | 100 m | 100 m en <2:00 | gte | meters |

Notas del test:
- Ítem 1: `type: benchmark` sobre `Hyrox Simulacro L3` (ficha de este lote). Se ejecuta en modalidad del nivel (pesos 50% Open de la ficha); la marca se registra en `UserBenchmarkAttempt` y alimenta el test. Mínimo (35:00) bajo el cap de 45:00 y por encima de beginner M (38:00) — regla F-Elizabeth; objetivo (28:00) en torno a advanced M.
- Ítem 2: puerta HY4 (`type: puerta_movilidad`, `gateCode: HY4`). Criterio: 100 m con 2×16 kg sin soltar, postura erguida; el objetivo añade tiempo como referencia.
- Modo de evaluación: `strict_gates` — superar el benchmark **y** HY4 habilita el N4. Fallar benchmark + puerta = `mobility_blocked` (ciclo corto de movilidad).
- Orden recomendado: HY4 primero (descansado) y simulacro después, o en sesiones separadas del envío de test.

## Logros del nivel N3

**De test:**
- "Simulacro L3 completado" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Hyrox Simulacro L3`.
- "Media Hyrox bajo 28" — `type: test`, `tier: raro`, `requirementType: test_item_obj`, `requirementKey: Hyrox Simulacro L3`, `requirementValue: 1680`.
- "Puerta HY4 superada" — `type: test` vinculado a puerta (mobility gate passed). `tier: raro`.

**De hito:**
- "Primer simulacro completado" — primera ejecución completa del `Hyrox Simulacro L3` en sesión C (F3), dentro del cap. `tier: epico`.
- "Kilómetro a Z3" — primer `Sprint` de 1 km a ritmo Z3 completado dentro del ritmo previsto. `tier: comun`.
- "Farmers de 2×16 kg" — primer `Farmers Walk`/`Farmer Walk` con 2×16/2×12 kg sin soltar. `tier: comun`.
- "Par push-pull completado" — primera ronda de `Sled Push` → `Sled Pull` con fatiga del circuito de sesión C sin romper técnica (§1). `tier: comun`.

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N1-N3: todos los ejercicios citados existen en el catálogo con la grafía usada (verificados contra BD dev, 17-oct-2026).
- Duplicado conocido en catálogo: `Farmers Walk` (id 340) y `Farmer Walk` (id 280) — el documento usa `Farmers Walk` como grafía principal (matches de los nombres de las estaciones) y `Farmer Walk` solo en el logro como alias aceptado. Se recomienda consolidar en catálogo en limpieza futura (fuera de este lote).
- Sustitutos cuando el gimnasio no tiene máquinas: `Assault Bike Sprint` / `Echo Bike Sprint` (calórico) para SkiErg/Row; `Step Up` cargado para Sled Push; `Kettlebell Swing Russian` pesado para Sled Pull — anotados en las notas de sesión correspondientes.
- Los simulacros L5-L8 (fichas 3-6 de spec §4.1) se especifican en el lote de niveles 4-6/7-8.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Mapeo de cuándo se evalúa cada puerta:** HY1/HY2/HY3 en N2; HY4 en N3; re-tests como `re_test` en N4/N6/N8 (fuera de este lote). El spec fija "en el nivel anterior al que exigen" y esta propuesta es coherente: HY1-HY3 protegen el trabajo con carga de N2 y se evalúan al final de N2; HY4 protege el farmers al 100% Open de N4+ y se evalúa en N3.
2. **Test N1 = solo PFT** (marca base, lte 30:00/24:00): no se exige ninguna puerta en N1; el PFT no bloquea la progresión al re-test pero el test solo se da por superado con marca registrada. Coherente con el arco del spec (N1 = "PFT (marca base) + HY1" interpretado como siembra de HY1, no evaluación).
3. **Test N3 = Simulacro L3 + HY4** (sin PFT re-test explícito en este lote): el arco del spec menciona "Simulacro L3 + PFT re-test" para N3; se resuelve dejando el PFT como benchmark disponible para re-test voluntario (`UserBenchmarkAttempt`) pero el test del nivel se cierra con los dos ítems indicados. A confirmar con el usuario.
4. **Pesos del simulacro L3 = 50% Open** (sled push 76/51, sled pull 52/39, WB 4 kg) según spec §4.1; la ficha guarda esos pesos y las subidas van en las fichas L5+.
5. **Sesión C de N1-N2 siempre circuito** (nunca simulacro); el primer benchmark embebido aparece en N3 Fase 3. En F3 de N3 las sesiones A y D reducen la carga del sled ("mantener, no construir") para no interferir con el simulacro — interferencia controlada sin calendario.
6. **Sustitutos sin SkiErg/remo/sled** resueltos con ejercicios existentes del catálogo (`Assault Bike Sprint`, `Echo Bike Sprint`, `Step Up`, `Kettlebell Swing Russian`) en notas, sin crear fichas nuevas — cumple spec §3 ("0 ejercicios nuevos previstos").
7. **Running semanal:** ~25 min Z2 en N1 (A: 15-25 min + D: 15-25 min) → ~50-60 min mixtos en N3 (A: 2-3 km Z3 + D: 30-40 min Z2/Z3 + C: 2 km de runs en circuito). Progresión conservadora coherente con un atleta híbrido novel.
8. **Logros de puerta** (HY1-HY4) modelados como logros `type: test` vinculados a puerta, igual que el patrón CF de los docs CrossFit ("Puerta CF1 superada").
