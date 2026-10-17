# Detalle de contenido — Niveles 4, 5 y 6 — DEKA Mastery v1

> Documento fuente desde el que se generará el programa DEKA Mastery (comando
> `app:create-deka-mastery`), lote 2 de 3 (niveles 4-6). Continuidad total con
> `docs/detalle_deka_niveles_01-03.md` (lote 1: niveles 1-3, benchmark DEKA
> Media, puertas DK1-DK4) y misma plantilla que los documentos Hyrox Mastery.
> Especificación de autoridad:
> `docs/superpowers/specs/2026-10-17-deka-mastery-design.md` (§2 pesos
> oficiales — Rules of Competition v20260210, §4.1 benchmarks y referencias,
> §4.2 puertas y arco, §4.3 regla §1.7-DEKA).
>
> **Estado de redacción:** COMPLETO. Niveles 4, 5 y 6 con sus 12 sesiones
> (4 fases × A/B/C/D por nivel), tests, re-tests de puertas, skillFocus,
> logros y las 3 fichas oficiales del lote (DEKA Strong, DEKA Mile, DEKA Fit —
> la Fit se siembra aquí y se usa en test en el lote 07-08). Pendiente la
> validación de contenido con el usuario (gate humano).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (spec §4.2): A — Run + zonas de fuerza (zancada/RAM, farmers, sled, bola); B — Ergómetros (row/ski/bike umbrales) + core; C — Simulacro/circuito de zonas (benchmark embebido en C-F3 de N5 y N6); D — Aeróbico Z2 + movilidad.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab hombro/lumbar/tobillo rotado por sesión) → `descarga`. `restAfterBlock` = 60 s en N4-N6.
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Dosis `/lado` se computan una vez.
- **RIR:** en N4 la fuerza de zonas Open se trabaja con 1-3 RIR (series largas y descanso completo); en N5-N6 manda el ritmo de competición: las transiciones se cronometran y el fallo técnico marca el escalado (regla §1.7-DEKA §5).
- **Equipamiento asumido N4-N6:** remo, SkiErg, air bike (assault/echo), caja plyo de 24", kettlebells/mancuernas (hasta 2×27 kg), slam ball pesada (27/18 kg o mancuerna), sled de peso (sustituto del Torque/Xebex magnético, nota), chaleco lastrado opcional para el burpee cargado (nota), y cinta o exterior para correr. Sustitutos: sin air bike → `Echo Bike Sprint` (misma familia) o `Rowing Machine Sprint`/`SkiErg Sprint` 500 m anotados; sin sled → `Step Up` cargado (push) y `Kettlebell Swing Russian` pesado (pull); sin RAM → mancuernas en back rack o a los lados (nota: "la carga real de RAM va en back rack, la mancuerna es sustituto").
- **Mapeos de zonas con nota** (spec §3, "0 fichas nuevas"): RAM lunge → `Dumbbell Lunge`/`Kettlebell Goblet Lunge` pesadas + nota "simula RAM 25/15 kg"; dead ball shoulder over → `Medicine Ball Slam` pesada + nota; RAM burpee → `Burpee` + carga en nota; sled magnético → `Sled Push`/`Sled Pull` de peso + nota "5×10 m ida y vuelta en competición".
- **Dosis de distancia/calorías:** como convención establecida (spec §5, mismo criterio que Hyrox), van como `durationSeconds`/reps estimados + dosis exacta en la descripción (160 m ≈ 40-50 s, 500 m ≈ 2:00-2:30).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (verificada en el lote 1 y re-verificada para este lote, 17-oct-2026). Duplicados conocidos: se usa `Farmers Walk` (340), `Face Pull` (482), `Pallof Press` (314).
- **Convención de nombres de ítems de test:** los ítems que corresponden a ejercicio de catálogo usan su grafía exacta con la carga entre paréntesis (p. ej. `Dumbbell Lunge (2×16/2×10 kg)`); los benchmarks se nombran como nombre propio ("DEKA Strong", "DEKA Mile"); los re-tests de puerta llevan el código DK n como nombre literal.
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.
- **Carrera:** DEKA exige menos carrera que Hyrox. Volumen semanal: ~45-55 min en N4, ~40-50 en N5 (Strong no corre: runs solo en calentamiento y transiciones), ~55-65 en N6, repartidos entre las sesiones A, C y D.

---

## Regla §1.7-DEKA en tips (referenciada, spec §4.3 — misma regla del lote 1)

Aplicar en `tips` de niveles y sesiones (texto íntegro en `detalle_deka_niveles_01-03.md`, §"Regla §1.7-DEKA"):

1. **La zancada cargada se progresa por carga antes que por velocidad:** rodilla de atrás al suelo, torso alto.
2. **Box overs: se salta o se sube, nunca se tropieza:** la caja 24" se domina primero en step over.
3. **Farmers: pasos cortos y agarre muerto antes que prisa:** la zona 6 se gana en el agarre, no en las piernas.
4. **Dead ball / RAM: extensión completa de cadera antes que altura del lanzamiento:** lumbar neutra siempre.
5. **Dolor matinal >2/10 = congelar el gesto un ciclo.**

Añadir en este tramo la capa de estrategia de transición entre zonas: **en Strong, salir de cada zona sin pausa** (la transición es parte del tiempo); **en Mile, los 160 m son recuperación activa, no sprint** (se corren a ritmo que permite ejecutar la siguiente zona).

---

## Puertas de movilidad activas en este lote

Ninguna puerta nueva. Este lote ejecuta re-tests del calendario (ruling "carga histórica" de Hyrox: la puerta conserva su carga de definición en los re-tests):

- **N4: re-test DK4** con carga histórica: `Farmers Walk` 2×16 kg ×100 m sin soltar (aunque el atleta entrena farmers a 2×27/2×18 kg — el re-test verifica el gesto con la carga original de la puerta).
- **N5: ninguna puerta** (test cerrado por benchmark puro).
- **N6: re-tests DK1, DK2 y DK3** (los tres como ítems `type: re_test` con `gateCode`), criterios idénticos a N2: 30 `Reverse Lunge` alternos sin parar · 20 box overs fluidas · 25 cal de bike continuas. Fallar benchmark + puerta en re-test = `mobility_blocked`, mismo ruling que N2.

---

## Benchmarks del lote (fichas completas, spec §4.1)

Tres fichas oficiales se siembran con este lote. Las tres `for_time`, `timerConfig: null` (stopwatch con cap), `partitioningAllowed: false`. El jugador registra cada zona como "ronda" (dosis por reps/distancia/calorías; distancia no editable en v1 → se anota en notas del finish, riesgo aceptado).

### ZONAS RX (array compartido de las 10 zonas oficiales, spec §2)

Bloque conceptual reutilizado por las 3 fichas. Orden fijo de competición, pesos oficiales M/F (14-64 años, temporada 26/27). Cada fila: ejercicio de catálogo + dosis + rxWeight + nota.

| # | Zona | Ejercicio (grafía catálogo) | Dosis | rxWeight M / F | Nota |
|---|------|------------------------------|-------|----------------|------|
| 1 | RAM Alternating Reverse Lunges | `Dumbbell Lunge` | 30 reps (15/lado) | 2×16 kg / 2×10 kg | simula RAM 25/15 kg en back rack (§1) |
| 2 | Row | `Rowing Machine Sprint` | 500 m | — | ritmo de umbral |
| 3 | Box Jump/Step Overs | `Box Jump` | 20 reps | — | caja 24"; box over permitido: `Step Up`/`Box Step Over` (§2) |
| 4 | Med Ball Sit-Ups (Strong/Mile) | `Sit-up` | 25 reps | MB 9 kg / 6.5 kg | en Strong/Mile es sit-up con toque entre pies; en Fit es throw a diana (nota de la ficha Fit) |
| 5 | SkiErg | `SkiErg Sprint` | 500 m | — | remada larga |
| 6 | Farmers Carry | `Farmers Walk` | 100 m | 2×27 kg / 2×18 kg | en Strong/Mile: 10 m ida y vuelta ×5; pasos cortos (§3) |
| 7 | Air Bike | `Assault Bike Sprint` | 25 cal | — | continuas, sentado; sustituto `Echo Bike Sprint` |
| 8 | Dead Ball Shoulder Over (Strong/Mile) | `Medicine Ball Slam` | 20 reps (10/lado) | 27 kg / 18 kg | simula dead ball shoulder over (§4); en Fit es yoke over (nota de la ficha Fit) |
| 9 | Sled Push/Pull | `Sled Push` + `Sled Pull` | 50 m + 50 m | peso de sled según gimnasio | sustituto del Torque lvl 3 / Xebex+73 kg; en competición: 5×10 m ida y vuelta (§1) |
| 10 | RAM Weighted Burpees | `Burpee` | 20 reps | RAM 20 kg / 10 kg (nota) | carga opcional en entrenamiento: chaleco o balón |

### Ficha 1 — "DEKA Strong"

Las 10 zonas RX como carrera completa **sin correr**: la prueba de fuerza-resistencia pura de la DEKA.

| Campo | Valor |
|-------|-------|
| name | DEKA Strong |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 1800 (30:00) |
| rxWeightMale | según array ZONAS RX (lunge 2×16 kg, farmers 2×27 kg, MB slam 27 kg, MB sit-up 9 kg) |
| rxWeightFemale | según array ZONAS RX (lunge 2×10 kg, farmers 2×18 kg, MB slam 18 kg, MB sit-up 6.5 kg) |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios: el array ZONAS RX completo, en orden, **sin runs entre zonas** (el jugador registra cada zona como "ronda"; la transición entre zonas es caminando, forma parte del tiempo). Referencias (spec §4.1, tablas públicas DEKA Strong):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 11:00 | 12:30 |
| advanced | 14:00 | 16:00 |
| intermediate | 18:00 | 21:00 |
| beginner | 24:00 | 28:00 |

Uso en test: N5 (lte, mínimo 24:00 (1440 s) / objetivo 18:00 (1080 s), ambos bajo el cap de 30:00 — regla F-Elizabeth).

### Ficha 2 — "DEKA Mile"

Las 10 zonas RX con 10×160 m: el formato clásico de competición.

| Campo | Valor |
|-------|-------|
| name | DEKA Mile |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 3600 (60:00) |
| rxWeightMale | según array ZONAS RX |
| rxWeightFemale | según array ZONAS RX |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios: **10×(`Sprint` 160 m + zona del array ZONAS RX en orden)** — run 1 → zona 1 → run 2 → zona 2 → … → run 10 → zona 10. El jugador registra cada zona como "ronda"; el run va como `durationSeconds` estimado (~40-50 s) + distancia en la descripción. Referencias (spec §4.1):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 18:00 | 20:00 |
| advanced | 22:00 | 25:00 |
| intermediate | 27:00 | 31:00 |
| beginner | 33:00 | 38:00 |

Uso en test: N6 (lte, mínimo 33:00 (1980 s) / objetivo 27:00 (1620 s), bajo el cap de 60:00 — regla F-Elizabeth).

### Ficha 3 — "DEKA Fit"

Las 10 zonas RX con 10×500 m: la carrera larga. Se siembra en este lote; su uso en test se decide en el lote 07-08 (N7 = transición Fit o DEKA Fit escalado).

| Campo | Valor |
|-------|-------|
| name | DEKA Fit |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 5400 (90:00) |
| rxWeightMale | según array ZONAS RX |
| rxWeightFemale | según array ZONAS RX |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios: **10×(`Sprint` 500 m + zona del array ZONAS RX en orden)** — misma estructura que Mile con runs de 500 m (~2:00-2:30 como `durationSeconds` + distancia en descripción). **Notas de formato Fit** (spec §2): la zona 4 es *Med Ball Sit-Up Throws* (throw a diana — mismo ejercicio `Sit-up` con MB 9/6.5 kg, dosis en nota), la zona 8 es *Yoke Over* (mismo ejercicio de catálogo `Medicine Ball Slam` como sustituto anotado, estándar distinto al dead ball) y la zona 6 farmers en recorrido directo de 100 m (sin ida y vuelta). Referencias (spec §4.1):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 28:00 | 31:00 |
| advanced | 33:00 | 37:00 |
| intermediate | 40:00 | 45:00 |
| beginner | 48:00 | 55:00 |

Uso: test del lote 07-08 (N7/N8; decisión pendiente en ese lote). Se siembra aquí para tener las 3 oficiales disponibles desde N5.

**Nota sobre rxWeight:** el peso del sled (zona 9) depende del gimnasio; en la ficha va como nota ("sustituto del Torque lvl 3 M / lvl 2 F, Xebex+73 kg") con el peso usado anotado en el intento. Las dosis de test de este lote se ejecutan en modalidad RX del array; el escalado se resuelve por `scalingOptions` + elección RX/Scaled del player (mismo criterio que los lotes anteriores: scaled = pesos del lote anterior / N3).

---

# NIVEL 4 — Zonas Open

- **Título:** Zonas Open — Cada zona al 100%, una a una
- **Objetivo:** llevar cada una de las 10 zonas a los pesos oficiales por separado como trabajo de fuerza (zancada 2×16/2×10 kg simulando el RAM 25/15 — nota: la carga real de RAM va en back rack, la mancuerna es sustituto; farmers 2×27/2×18 kg; slam ball 27/18 kg simulando el dead ball; burpee con 20/10 kg en nota; box overs caja 24" rítmicas; sled 5×10 m push/pull con peso), con umbrales ergómetros largos (row/ski 500-1.000 m, bike 25-50 cal) y runs de 160-250 m. La lógica del nivel es "una zona a la vez": fuerza pura con series largas; los trenes con fatiga llegan en N5.
- **Requisitos de entrada:** mínimos del test N3 (DEKA Media + DK4).
- **skillFocus (obligatorio):** `Las 10 zonas al 100% de los pesos oficiales por separado y umbrales ergómetros de 500-1.000 m`
- **Puertas evaluadas:** **re-test DK4** con carga histórica (2×16 kg ×100 m). DK1-DK3 superadas en N2; su re-test llega en N6.

## Test de nivel N4 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Dumbbell Lunge (2×16/2×10 kg) | 20 reps | 30 reps | gte | reps |
| 2 | Farmers Walk (2×27/2×18 kg) | 100 m | 100 m en <2:30 | gte | meters |
| 3 | `Box Jump` (caja 24") | 20 reps | 25 reps | gte | reps |
| 4 | Sit-up (MB 9/6.5 kg) | 25 reps | 40 reps | gte | reps |
| 5 | DK4 (re-test) — Farmers 2×16 kg ×100 m sin soltar | 100 m | 100 m en <1:45 | gte | meters |

Notas del test:
- Ítems 1-4: fuerza (`type: fuerza`, `direction: gte`) con pesos oficiales por primera vez. Validez: zancada con rodilla al suelo (§1), farmers sin soltar y postura erguida (§3), caja sin doble bote (§2), sit-up con toque entre pies y MB (nota).
- Ítem 5: re-test de puerta (`type: re_test`, `gateCode: DK4`) con **carga histórica** (ruling Hyrox): 2×16 kg, **menor** que la del ítem de fuerza (2×27/2×18 kg) — el re-test verifica que el gesto sigue siendo seguro con la carga de definición de la puerta, no la nueva.
- Modo de evaluación: `strict_gates` — superar los 4 ítems de fuerza **y** DK4 habilita el N5.
- Orden recomendado (menor a mayor interferencia): DK4 → ítem 1 → ítem 3 → ítem 4 → ítem 2.

## Logros del nivel N4

**De test:**
- "Zancada Open" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Dumbbell Lunge (2×16/2×10 kg)`.
- "Farmers Open" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Farmers Walk (2×27/2×18 kg)`.
- "Caja de competición" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Box Jump (caja 24")`.
- "Puerta DK4 re-validada" — `type: test` vinculado a re-test de puerta (`re_test`, `gateCode: DK4`). `tier: raro`.

**De hito:**
- "Primer sled 5×10" — primer bloque de `Sled Push` 5×10 m + `Sled Pull` 5×10 m con peso completado (§1). `tier: raro`.
- "Slam de 27 kg" — primer `Medicine Ball Slam` de 27/18 kg con extensión de cadera completa (§4). `tier: raro`.
- "Burpee cargado" — primer `Burpee` con carga (chaleco 20/10 kg o balón) completo. `tier: comun`.
- "Umbral de 1.000 m" — primer `Rowing Machine Sprint` o `SkiErg Sprint` continuo de 1.000 m al ritmo previsto. `tier: raro`.

## Bloques comunes del Nivel 4

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N3 con dosis de mantenimiento alto y foco nuevo: agarre (2×27/2×18 kg en juego) y cadera para la zancada Open. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, mantenido) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 40 s + 8/lado + 15 | 60 s + 8/lado + 15 | 60 s + 10/lado + 15 | 45 s + 6/lado suave + 12 suave |
| Cadena posterior/agarre (DK4, foco) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 12 + 15 | 30 s/lado + 12 + 15 | 40 s/lado + 12 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 30 s | 15 + 15 + 30 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 12 | 12 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** activación de agarre (`Farmer Walk` ligero 20 m) antes del farmers Open; `Deep Squat Hold` antes de la zancada.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Circuito):** énfasis en muñeca (burpees/slams en el circuito) y tobillo; ensayo de caja rítmica.
- **D (Aeróbico):** énfasis en cadena posterior y agarre; ensayo del re-test DK4.

### Bloque postural (prehab — selección N4)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. Progresión: subir una serie al ejercicio más fácil cuando la calidad se mantiene dos sesiones seguidas.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N4)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Zonas al 75-85% oficial con series largas; umbrales 500-750 m; farmers 2×20/2×14 kg |
| 2 — Progresión | 80% | Zonas al 90-100%; dosis de test integradas (box ×20, sit-up ×25); farmers 2×27/2×18 kg ×50-75 m |
| 3 — Intensificación | 95% | Todas las zonas al 100%; umbrales 1.000 m; ensayo DK4 en sesión D |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 4

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Run + zonas Open de fuerza (todas las fases)

- Tipo: carrera + fuerza pura de zonas. Duración estimada: 60-80 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (run con intervals 160-250 m) | 8×160 m (Z3) | 6×250 m (Z3-Z4) | 8×160 m (ritmo Mile ~45 s) | 4×160 m suave | 60-90 s | El ritmo Mile se consolida; los 250 m de F2 enseñan el cambio de ritmo |
| `Dumbbell Lunge` (progresión a Open) | 3×12/lado (2×12/2×8 kg) | 3×15/lado (2×16/2×10 kg) | 4×15/lado (2×16/2×10 kg) | 2×10/lado suave | 2 min | Fuerza pura: series largas con descanso completo; rodilla al suelo, torso alto (§1); nota RAM en back rack |
| `Farmers Walk` (progresión a Open) | 3×50 m (2×20/2×14 kg) | 3×75 m (2×27/2×18 kg) | 3×100 m (2×27/2×18 kg) | 2×40 m | 2 min | F3 ensaya los 100 m del test; §3: pasos cortos, agarre firme, postura erguida |
| `Medicine Ball Slam` (progresión a 27/18) | 3×8 (16/10 kg) | 3×10 (22/14 kg) | 4×10 (27/18 kg) | 2×6 suave | 2 min | §4: extensión de cadera completa antes que altura; el dead ball de la zona 8 se construye aquí |

**Bloque `wod` (run→zona):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×10/lado (2×12/2×8 kg) | 90 s entre rondas |
| F2 | 4 rondas | `Sprint` 160 m + `Farmers Walk` 50 m (2×27/2×18 kg) | 90 s entre rondas; el farmers Open saliendo de correr |
| F3 | 4 rondas | `Sprint` 160 m + `Medicine Ball Slam` ×8 (27/18 kg) + `Burpee` ×5 (carga opcional) | "Mantener, no construir": la carga no sube esta fase |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Dumbbell Lunge` ×6/lado | Movimiento; test al final del nivel |

**Tips de sesión:** "Hoy las zonas son gimnasio, no carrera: carga oficial, descanso completo, técnica de cristal. El día que la zancada de 2×16 kg salga limpia con dos minutos de descanso, la zona 1 de la Strong ya solo será cuestión de ritmo (§1.7-DEKA §1)."

### Sesión B — Ergómetros umbrales largos (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 55-75 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` (umbral largo) | 3×500 m | 2×750 m | 2×1.000 m | 1×500 m suave | 2-3 min | La zona 2 es 500 m; el 1.000 m continuo de F3 es el umbral más largo del nivel |
| `SkiErg Sprint` (umbral largo) | 3×500 m | 2×750 m | 1×1.000 m | 1×500 m suave | 2-3 min | La zona 5 es 500 m; remada larga hasta el final |
| `Assault Bike Sprint` (volumen cal) | 4×25 cal | 3×40 cal | 3×50 cal | 2×25 cal suave | 2-3 min | La zona 7 es 25 cal; el 50 cal continuo de F3 construye el motor de la segunda mitad de carrera |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 5 × (1' trabajo / 1' descanso) | `Assault Bike Sprint` a umbral | Acumula 25-30 cal por intervalo |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | Umbral sostenido; control lumbar (§5) |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 50 cal del bike de F3 son dos zonas 7 seguidas: si salen continuas y sentado, la zona 7 de la Strong será un trámite. El tronco quieto, siempre (§1.7-DEKA §5)."

### Sesión C — Circuito: la zona Open en soledad (todas las fases)

- Tipo: circuito + core. **Sin simulacro** en N4 (el siguiente benchmark embebido es la DEKA Strong en N5). Duración estimada: 55-80 min.
- Bloque 0: variante C. Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta del par del día):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Box Jump` (caja 24") | 2×10 | 2×15 | 2×20 | 2×10 suave | 60 s | Dosis de test (×20) integrada desde F2; §2: sin doble bote |
| `Sit-up` (MB 9/6.5 kg) | 2×15 | 2×20 | 2×25 | 2×15 suave | 60 s | Toque entre pies; la zona 4 de la carrera |

**Bloque `wod` (zona Open única con run, creciendo por fases):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 160 m + `Box Jump` ×10 (caja 24") | 90 s entre rondas; la caja en fatiga |
| F2 | 4 rondas | `Sprint` 160 m + `Box Jump` ×15 + `Sit-up` ×15 (MB) | La zona 3-4 del Mile en versión corta |
| F3 | 4 rondas | `Sprint` 160 m + `Sled Push` 10 m + `Sled Pull` 10 m + `Burpee` ×8 | Zona 9-10: el final de la carrera en serio; §1 en el sled |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Box Jump` ×8 | Movimiento; test al final del nivel |

**Tips de sesión:** "Una zona a la vez, pero siempre con el run delante: la caja de 24" cansado es otra caja. Si el salto no va hoy, el step over rítmico es igual de válido y más listo (§1.7-DEKA §2)."

### Sesión D — Aeróbico + movilidad + ensayo DK4 (todas las fases)

- Tipo: aeróbico + movilidad + re-test. Duración estimada: 55-75 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con intervals) | 18 min (4×160 m) | 20 min (6×160 m) | 20 min (6×160 m, ritmo Mile) | 12 min Z2 suave | — | Volumen aeróbico del nivel con ritmo Mile integrado |
| `Farmers Walk` (DK4, re-test camino) | 2×50 m (2×16 kg) | 2×75 m | 2×100 m (ensayo re-test) | 2×40 m | 90 s | La carga es la histórica de la puerta (2×16 kg), no la de test; F3 ensaya los 100 m exactos |
| `Plank` | 3×60 s | 3×60 s | 3×60 s | 2×45 s | 60 s | Mantenimiento del tronco para los ergómetros |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Step Up` + 8 `Push Up` | Control de ritmo de principio a fin |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de F3 se hacen con 2×16 kg y sin soltar: es el re-test DK4 entero. La carga de la puerta no cambió desde N3 — lo que cambiaste tú (§1.7-DEKA §3)."

---

# NIVEL 5 — DEKA Strong

- **Título:** DEKA Strong — Las 10 zonas como carrera
- **Objetivo:** ejecutar las 10 zonas RX en orden, sin correr, como carrera completa (benchmark DEKA Strong embebido), entrenando zonas en parejas y trenes con fatiga y **transiciones entre zonas cronometradas** — en Strong la transición es parte del tiempo de carrera y se entrena como tal. El test es el primer benchmark oficial del programa.
- **Requisitos de entrada:** mínimos del test N4 (zonas Open + DK4 re-validada).
- **skillFocus:** `Las 10 zonas RX como carrera continua y transiciones entre zonas cronometradas`
- **Puertas evaluadas:** ninguna nueva ni re-test en este nivel (el test se cierra con el benchmark; DK1-DK3 se re-testan en N6).

## Test de nivel N5 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | DEKA Strong | 24:00 (1440 s) | 18:00 (1080 s) | lte | seconds |

Notas del test:
- `type: benchmark` sobre `DEKA Strong` (placeholder `ID_STRONG`, ficha de este lote), modalidad RX (array ZONAS RX). Marca en `UserBenchmarkAttempt` → alimenta el test.
- Mínimo (24:00) bajo el cap de 30:00, en torno a beginner M (24:00); objetivo (18:00) = intermediate M (18:00). Regla F-Elizabeth: mínimo siempre bajo el cap.
- Es la primera carrera completa del programa: las transiciones entre zonas (caminar sin pausa, material listo) van en los tips del nivel.

## Logros del nivel N5

**De test:**
- "Primera DEKA Strong completa" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: DEKA Strong`.
- "Strong sub-18" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: DEKA Strong`, `requirementValue: 1080`.
- "Diez zonas seguidas" — `type: test`, `tier: raro`, `requirementType: test_item_obj`, `requirementKey: DEKA Strong`, `requirementValue: 1800` (dentro del cap).

**De hito:**
- "Zona 10 sin romper" — primer `Burpee` cargado (nota RAM 20/10 kg) ×20 de una tanda completa sin romper técnica. `tier: raro`.
- "Transición sub-30" — primera transición entre zonas del circuito de ritmo en menos de 30 s (material listo, sin pausa). `tier: comun`.
- "Trío 8-9-10" — primer tren `Medicine Ball Slam` → `Sled Push`/`Sled Pull` → `Burpee` completo con las dosis del circuito. `tier: raro`.
- "Farmers Open de ida y vuelta" — primer `Farmers Walk` 10 m ida y vuelta ×5 con 2×27/2×18 kg sin soltar (dosis exacta de la zona 6 en Strong/Mile). `tier: raro`.

## Bloques comunes del Nivel 5

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N4 con foco nuevo: muñeca/hombro para el slam y el burpee cargado, y agarre para el farmers de ida y vuelta. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, mantenido) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 60 s + 8/lado + 15 | 60 s + 10/lado + 15 | 60 s + 10/lado + 15 | 45 s + 6/lado suave + 12 suave |
| Cadena posterior/agarre (DK4, mantenido) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 12 + 15 | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (slam/burpee) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 30 s | 15 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 12 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** activación de agarre antes del farmers; énfasis en cadera antes de la zancada.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y agarre; en F3 (DEKA Strong) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico de las 10 zonas.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; la descarga se amplía.

### Bloque postural (prehab — selección N5)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. En N5 el prehab protege el slam y el burpee cargado: es el antídoto de la zona 8-10 con fatiga (§4/§5).

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N5)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Parejas de zonas con fatiga moderada (1→2, 3→4); transiciones sin cronómetro; umbrales cortos |
| 2 — Progresión | 80% | Parejas y tríos al RX; transiciones cronometradas sub-45 s; farmers de ida y vuelta |
| 3 — Intensificación | 95% | **Benchmark embebido: DEKA Strong en sesión C**; sesiones A/B/D a "mantener, no construir" |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 5

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido.

### Sesión A — Zonas de fuerza en parejas (todas las fases)

- Tipo: fuerza de zonas con fatiga controlada. Duración estimada: 55-80 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (calentamiento de carrera) | 6×100 m suave | 8×100 m | 6×160 m (ritmo suave) | 4×100 m suave | 60 s | Strong no corre, pero el calentamiento corre: sangre en las piernas antes de las zonas |
| `Dumbbell Lunge` → `Farmers Walk` (par) | 3×(15/lado 2×16/2×10 kg + 50 m 2×27/2×18 kg) | 3×(20/lado + 100 m) | 3×(15/lado + 50 m, mantener) | 2×(10/lado + 40 m suave) | 2 min entre series | Zonas 1-6: piernas → agarre; la transición sin pausa es la lección (§1/§3) |
| `Medicine Ball Slam` → `Burpee` (par) | 3×(10 slam 27/18 kg + 8 burpee) | 3×(15 slam + 12 burpee) | 2×(10 + 10, mantener) | 2×(8 + 6 suave) | 2 min entre series | Zonas 8-10: la mitad que decide la Strong; §4 en el slam, burpee completo |

**Bloque `wod` (parejas con run corto):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 100 m + `Dumbbell Lunge` ×15/lado (2×16/2×10 kg) + `Farmers Walk` 50 m | 90 s entre rondas; zonas 1-6 comprimidas |
| F2 | 3 rondas | `Sprint` 100 m + `Medicine Ball Slam` ×10 + `Burpee` ×10 | 90 s entre rondas; zonas 8-10 comprimidas |
| F3 | 2 rondas | `Sprint` 100 m + `Dumbbell Lunge` ×10/lado | "Mantener, no construir": volumen mínimo antes de la Strong |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Farmers Walk` 30 m ligero | Movimiento; test al final del nivel |

**Tips de sesión:** "La Strong se gana en las transiciones: salir de la zancada, coger las mancuernas y caminar sin pausa. Sesenta segundos de transición los pone cualquiera; menos de treinta, solo los que entrenaron (§1.7-DEKA §3)."

### Sesión B — Ergómetros de transición (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 50-70 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` → `SkiErg Sprint` (par) | 3×(500 m row + 250 m ski) | 3×(500 m + 500 m) | 2×(500 m + 500 m, mantener) | 2×(250 m + 250 m suave) | 90 s entre series | Zonas 2-5: el cambio de máquina sin descanso extra entrena la transición |
| `Assault Bike Sprint` (25 cal umbral) | 3×25 cal | 4×25 cal | 2×25 cal (mantener) | 2×15 cal suave | 90 s | La zona 7 exacta, acumulada |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 5 × (1' trabajo / 1' descanso) | `Assault Bike Sprint` a umbral | 25 cal por intervalo; transición de pie, respirando |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | Volumen contenido; la Strong está cerca |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "Row→Ski con fatiga es donde se rompen las remadas: espalda redondeada y solo brazos. Si eso pasa, para la serie y vuelve con remada larga — el volumen de hoy es el de la Strong, no más (§1.7-DEKA §5)."

### Sesión C — Circuito de zonas / DEKA Strong (todas las fases)

- Tipo: circuito de zonas (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 50-75 min (F1/F2/F4); 75-90 min (F3 con la DEKA Strong).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Box Jump` (caja 24") | 2×10 | 2×15 | 1×10 (calentamiento del simulacro) | 2×8 suave | 60 s | En F3 es calentamiento específico: toca las 10 zonas con dosis mínimas |
| `Burpee` (carga opcional) | 2×8 | 2×10 | — (el simulacro es el protagonista) | 2×6 | 60 s | La carga (20/10 kg) es opcional hasta el día del benchmark |

**Bloque `wod` (zonas con transiciones cronometradas):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Dumbbell Lunge` ×15/lado (2×16/2×10 kg) + `Rowing Machine Sprint` 500 m + `Box Jump` ×15 + `Sit-up` ×20 (MB) | 2 min entre rondas; zonas 1-4 con transiciones anotadas |
| F2 | 3 rondas | `Farmers Walk` 10 m ida y vuelta ×5 (2×27/2×18 kg) + `Assault Bike Sprint` 25 cal + `Medicine Ball Slam` ×15 + `Burpee` ×12 | 2 min entre rondas; zonas 6-10: la segunda mitad de la carrera |
| F3 | **Benchmark embebido: DEKA Strong** | `DEKA Strong` en modalidad RX (ver ficha): las 10 zonas del array en orden, sin runs | Stopwatch + cap 30:00; la marca alimenta el test; logro "Primera DEKA Strong completa". Plan: transiciones de menos de 30 s, material preparado, salir de cada zona sin pausa |
| F4 | 2 rondas suaves | `Box Jump` ×6 + `Sit-up` ×10 | Movimiento; test al final del nivel |

**Tips de sesión:** "La Strong son 10 zonas que se encadenan: la recuperación es caminar al siguiente implemento, y se camina rápido. Los primeros 5 minutos se corren con la cabeza: si las zonas 1-4 salen limpias, las 6-10 solo son ejecutar (§1.7-DEKA §1-§4)."

### Sesión D — Aeróbico + movilidad (todas las fases)

- Tipo: aeróbico + movilidad. Duración estimada: 50-70 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 continuo) | 20 min | 25 min | 20 min (mantener) | 15 min suave | — | El volumen aeróbico del nivel; Strong no corre, pero el motor sí se entrena |
| `Farmers Walk` (ida y vuelta) | 3×(10 m i/v ×3) | 3×(10 m i/v ×5) | 2×(10 m i/v ×5) | 2×40 m | 90 s | F2-F3 ensayan la dosis exacta de la zona 6; §3 |
| `Plank` | 3×60 s | 3×60 s | 2×60 s | 2×45 s | 60 s | Mantenimiento del tronco |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Mantener, no construir: la Strong está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los ida y vuelta de farmers de F2 son la zona 6 descansada: si salen sin soltar hoy, saldrán igual el día de la Strong. Lo que no sale descansado, no sale cansado (§1.7-DEKA §3)."

---

# NIVEL 6 — DEKA Mile

- **Título:** DEKA Mile — Diez zonas, diez veces ciento sesenta metros
- **Objetivo:** ejecutar las 10 zonas RX con 10×160 m como test del nivel (benchmark DEKA Mile embebido), entrenando runs de 160 m a ritmo con zona intercalada (la transición run→zona es la zona de competición), transiciones cronometradas y volumen de carrera subido a ~55-65 min semanales. Tras superar N6 queda la transición al formato Fit (lote 07-08).
- **Requisitos de entrada:** mínimos del test N5 (DEKA Strong bajo 24:00).
- **skillFocus:** `DEKA Mile completa: zonas RX con 10×160 m a ritmo y transición run→zona cronometrada`
- **Puertas evaluadas:** **re-tests DK1, DK2 y DK3** (los tres como ítems `type: re_test` con `gateCode`), criterios idénticos a N2 con cargas históricas.

## Test de nivel N6 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | DEKA Mile | 33:00 (1980 s) | 27:00 (1620 s) | lte | seconds |
| 2 | DK1 (re-test) — 30 reverse lunges sin parar | 30 reps | 30 reps sin pausa >3 s | gte | reps |
| 3 | DK2 (re-test) — 20 box overs fluidas | 20 reps | 20 reps sin tropiezo | gte | reps |
| 4 | DK3 (re-test) — 25 cal bike continuas | 25 cal | 25 cal en <2:00 | gte | cal |

Notas del test:
- Ítem 1: `type: benchmark` sobre `DEKA Mile` (placeholder `ID_MILE`, ficha de este lote), modalidad RX. Mínimo (33:00) bajo el cap de 60:00, en torno a beginner M (33:00); objetivo (27:00) = intermediate M (27:00). Regla F-Elizabeth.
- Ítems 2-4: re-tests de puerta (`type: re_test` con `gateCode` DK1/DK2/DK3), mismos criterios que en N2 (cargas históricas de definición: zancada al peso corporal, box sobre caja 20-24", 25 cal sentado). Tras 4 niveles de trabajo cargado, el programa verifica que la base de movilidad sigue intacta antes del tramo Fit.
- Modo de evaluación: `strict_gates` — superar el benchmark **y** los tres re-tests habilita el N7. Fallar benchmark + puerta = `mobility_blocked`.
- Orden recomendado: re-tests primero (descansado, en sesión separada si hace falta) y el Mile al final, con al menos una sesión de movilidad entre medias.

## Logros del nivel N6

**De test:**
- "Primera DEKA Mile completa" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: DEKA Mile`.
- "Mile sub-27" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: DEKA Mile`, `requirementValue: 1620`.
- "Puerta DK1 re-validada" / "Puerta DK2 re-validada" / "Puerta DK3 re-validada" — `type: test` vinculado a re-test de puerta (`re_test`, `gateCode` DK1/DK2/DK3). `tier: raro`.

**De hito:**
- "Diez veces ciento sesenta" — primera ejecución completa del `DEKA Mile` en sesión C (F3), dentro del cap. `tier: epico`.
- "160 m de recuperación" — primer run de 160 m a ritmo sostenible (45-50 s) saliendo de una zona y entrando en la siguiente sin romper técnica. `tier: comun`.
- "Ritmo de Mile" — circuito completo de 5 zonas con runs bajo 50 s cada uno y splits de zona controlados. `tier: raro`.
- "Transición sub-20" — primera transición zona→run del circuito en menos de 20 s. `tier: raro`.

## Bloques comunes del Nivel 6

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N5 a dosis de mantenimiento alto; con las 10 zonas RX y los 10 runs en juego, el Bloque 0 es la primera línea de defensa y el re-test de DK1-DK3 exige movilidad intacta. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, re-test camino) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 45 s/lado + 45 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, re-test camino) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 60 s + 10/lado + 15 | 60 s + 10/lado + 15 | 75 s + 10/lado + 15 | 60 s + 6/lado suave + 12 suave |
| Cadena posterior/agarre (DK4, mantenido) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 45 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (DK2/DK3 soporte) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 45 s | 20 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 15 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** ensayo DK1 (zancada continua ×30); activación de agarre antes del farmers.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; ensayo DK3 (10 cal suaves).
- **C (Simulacro/circuito):** ensayo DK2 (box overs continuas); en F3 (DEKA Mile) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico de las 10 zonas.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; re-tests DK1-DK3 en F3.

### Bloque postural (prehab — selección N6)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. Con 10 zonas y 10 runs en juego, el prehab de N6 se blinda: ninguna sesión lo omite ni lo recorta (solo el Bloque 0 se acorta en F3).

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N6)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Runs 160 m a ritmo con zona intercalada; parejas de zonas RX; transiciones sub-30 s |
| 2 — Progresión | 80% | Circuitos de 5 zonas con runs (primera mitad y segunda mitad del Mile); re-tests DK1-DK3 ensayados |
| 3 — Intensificación | 95% | **Benchmark embebido: DEKA Mile en sesión C**; sesiones A/B/D a "mantener, no construir" |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 6

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido y las sesiones A/B/D reducen carga y novedad.

### Sesión A — Run 160 m a ritmo + zonas RX intercaladas (todas las fases)

- Tipo: carrera + zonas con fatiga. Duración estimada: 60-85 min.
- Bloque 0: variante A (con ensayo DK1). Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (160 m a ritmo Mile) | 8×160 m (45-50 s) | 10×160 m (ritmo exacto) | 8×160 m (ritmo exacto, mantener) | 4×160 m suave | 60-90 s | El ADN del Mile: 10 runs a ritmo; F3 repite el ritmo exacto sin novedad — el benchmark está cerca |
| `Dumbbell Lunge` → `Rowing Machine Sprint` (par con run) | 3×(160 m + 15/lado 2×16/2×10 kg) | 3×(160 m + 15/lado + 500 m row) | 2×(160 m + 15/lado) | 2×(160 m suave + 10/lado) | 90 s | Zonas 1-2 con el run delante: la zona se ejecuta con la respiración del run (§1) |
| `Farmers Walk` → `Assault Bike Sprint` (par) | 3×(100 m 2×27/2×18 kg + 25 cal) | 3×(100 m + 25 cal) | 2×(50 m + 25 cal, mantener) | 2×(40 m + 15 cal suave) | 90 s | Zonas 6-7: agarre → motor; §3 en el farmers |

**Bloque `wod` (run→zona→run):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×10/lado (2×16/2×10 kg) + `Sprint` 160 m | 90 s entre rondas; zona 1 entre runs, como en la carrera |
| F2 | 4 rondas | `Sprint` 160 m + `Box Jump` ×15 + `Sprint` 160 m + `Sit-up` ×20 (MB) | 90 s entre rondas; zonas 3-4 con runs por medio |
| F3 | 3 rondas | `Sprint` 160 m a ritmo exacto + `Farmers Walk` 100 m (2×27/2×18 kg) | "Mantener, no construir": el par exacto del km 6, una vez bien hecho |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Dumbbell Lunge` ×6/lado | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 160 m del Mile son recuperación activa, no sprint: se corren a 45-50 segundos con la respiración controlada, y la zona que viene después se ejecuta, no se sobrevive. Ese es todo el ritmo de carrera (§1.7-DEKA §1)."

### Sesión B — Ergómetros a ritmo de carrera (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 55-75 min.
- Bloque 0: variante B (con ensayo DK3). Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` (split de carrera) | 3×500 m (ritmo umbral) | 3×500 m (sub-2:00 si puede) | 2×500 m (ritmo exacto, mantener) | 1×500 m suave | 90 s | La zona 2 con split: la Strong la entrenó, el Mile la cronometra |
| `SkiErg Sprint` (split de carrera) | 3×500 m | 3×500 m | 2×500 m (mantener) | 1×500 m suave | 90 s | La zona 5 llega con 5 km en las piernas en el Fit — aquí se afila |
| `Assault Bike Sprint` (zona 7 exacta) | 3×25 cal | 4×25 cal | 2×25 cal (mantener) | 2×15 cal suave | 90 s | 25 cal continuas, sentado; re-test DK3 en contexto |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 2×(500 m `Rowing Machine Sprint` a ritmo + 10 `Burpee`) | Row con split exacto y salida inmediata de burpees | Ensayo de la zona 2-10 con fatiga de transición |
| F3 | 4 × (1' trabajo / 1' descanso) | `Assault Bike Sprint` a umbral | Afilar: ritmo exacto, sin novedad |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "Las zonas 2, 5 y 7 del Mile no se sienten: se cronometran. El split de hoy es el split de la carrera; si el 500 m sale en 2:10 con remada limpia, ese es tu ritmo (§1.7-DEKA §5)."

### Sesión C — Circuito run→zona / DEKA Mile (todas las fases)

- Tipo: circuito de zonas con runs (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 60-85 min (F1/F2/F4); 85-95 min (F3 con la DEKA Mile — la sesión más larga del lote).
- Bloque 0: variante C (con ensayo DK2; mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Box Jump` (caja 24") | 2×10 | 2×15 | 1×10 (calentamiento del simulacro) | 2×8 suave | 60 s | En F3 es calentamiento específico: toca las 10 zonas con dosis mínimas |
| `Burpee` | 2×8 | 2×10 | — (el simulacro es el protagonista) | 2×6 | 60 s | La zona 10 se afila entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×15/lado (2×16/2×10 kg) + `Sprint` 160 m + `Rowing Machine Sprint` 500 m + `Sprint` 160 m + `Box Jump` ×15 | 2 min entre rondas; la primera mitad del Mile (zonas 1-3) con runs |
| F2 | 3 rondas | `Sprint` 160 m + `Sit-up` ×20 (MB) + `Sprint` 160 m + `SkiErg Sprint` 500 m + `Sprint` 160 m + `Farmers Walk` 10 m i/v ×5 | 2 min entre rondas; zonas 4-6: el tramo del farmers de ida y vuelta (§3) |
| F3 | **Benchmark embebido: DEKA Mile** | `DEKA Mile` en modalidad RX (ver ficha): 10×(run 160 m + zona del array en orden) | Stopwatch + cap 60:00; la marca alimenta el test; logro "Diez veces ciento sesenta". Plan: runs a 45-50 s, transiciones sub-20 s, salir de cada zona caminando 3 pasos y corriendo |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Box Jump` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "El Mile son 10 estaciones cortas con un 160 m de regalo entre medias: el run se usa para bajar el pulso, no para subirlo. Los que sprintan el run 3 pagan la zona 7; los que lo administran, la cobran (estrategia, §1.7-DEKA §2)."

### Sesión D — Aeróbico + movilidad + re-tests DK1-DK3 (todas las fases)

- Tipo: aeróbico + movilidad + re-tests. Duración estimada: 60-80 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques de ritmo) | 20 min (4×160 m) | 25 min (6×160 m) | 15 min suave (afilar) | 12 min Z2 | — | Volumen aeróbico del nivel; en F3 baja el volumen — el Mile está cerca |
| Re-tests DK1-DK3 (finales de F1-F3) | DK1: 30 `Reverse Lunge` continuas | DK2: 20 box overs fluidas | DK3: 25 cal bike <2:00 | — (en F4 va en el test) | 3 min | Ensayos exactos de los re-tests: DK1 tras el run (la versión más dura), DK2/DK3 frescos |
| `Farmer Walk` (agarre, mantenimiento) | 2×50 m (2×24/2×16 kg) | 2×50 m | 2×40 m | 2×30 m | 90 s | Mantiene el agarre de competición sin fatiga nueva |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Mantener, no construir: el Mile está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los re-tests de hoy se hacen con la misma humildad que en N2: 30 zancadas seguidas, 20 cajas limpias, 25 cal sentado. Cuatro niveles después, lo difícil no es pasarlos — es no menospreciarlos (§1.7-DEKA §5)."

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N4-N6: todos los ejercicios citados existen en el catálogo con la grafía usada (verificados contra BD dev, 17-oct-2026, lote 1 y re-verificación de este lote: `Reverse Lunge` 150, `Dumbbell Lunge` 164, `Kettlebell Goblet Lunge` 165, `Box Jump` 159, `Box Step Over` 533, `Step Up` 156, `Sit-up` 804, `Medicine Ball Slam` 338, `Assault Bike Sprint` 333, `Echo Bike Sprint` 548, `Rowing Machine Sprint` 334, `SkiErg Sprint` 358, `Farmers Walk` 340, `Sled Push` 193, `Sled Pull` 339, `Burpee` 315, `Sprint` 323, más movilidad/core).
- `[SIN FICHA]` heredado del lote 1 (menor, no bloquea): sin ficha de movilidad dinámica de tobillo tipo "ankle rock / knee-to-wall" — cubierto con `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` + `Step Up` controlado.
- Mapeos con nota (spec §3, "0 fichas nuevas"): RAM lunge → `Dumbbell Lunge`/`Kettlebell Goblet Lunge` pesadas + nota "simula RAM 25/15 kg en back rack" (la carga real de RAM va en back rack; la mancuerna es sustituto — nota explícita del diseño de N4); RAM burpee → `Burpee` + carga en nota (chalevo/balón); dead ball shoulder over → `Medicine Ball Slam` pesada + nota (en Fit es yoke over, nota de la ficha); sled magnético → `Sled Push`/`Sled Pull` de peso + nota "5×10 m ida y vuelta en competición".
- Peso del sled (zona 9): depende del gimnasio; la ficha lo documenta como nota (sustituto del Torque lvl 3 M / lvl 2 F, Xebex+73 kg).
- Duplicados conocidos (heredados): `Farmers Walk` (340) / `Farmer Walk` (280), `Face Pull` (482) / `Face pull` (756), `Pallof Press` (314) / `Pallof press` (782). Este documento usa siempre la grafía principal.
- DEKA Fit se siembra en este lote y se usa en test en el lote 07-08 (N7 = transición Fit o DEKA Fit escalado — se decide en ese lote).

## Decisiones de contenido tomadas (para revisión del controller)

1. **Re-test DK4 en N4 con carga histórica (2×16 kg)** pese al farmers Open de 2×27/2×18 kg en los ítems de fuerza: el re-test verifica el gesto con la carga de definición de la puerta (ruling "carga histórica" de Hyrox, heredado del lote 1). Mismo criterio para los re-tests DK1-DK3 de N6 (zancada al peso corporal, box 20-24", 25 cal).
2. **Calendario de re-tests del lote:** DK4 en N4, ninguna puerta en N5 (benchmark puro), DK1-DK3 en N6. Coherente con spec §4.2 ("re-tests N6/N8") y con la decisión del lote 1 (DK4 evaluada en N3).
3. **Test N4 = 4 ítems de fuerza + re-test DK4** (5 ítems); **test N5 = solo DEKA Strong** (un ítem benchmark); **test N6 = DEKA Mile + re-tests DK1-DK3** (4 ítems).
4. **Las 3 fichas oficiales comparten el array ZONAS RX** (helper conceptual del seeder, mismo criterio que L7/L8 de Hyrox): Strong sin runs, Mile con 10×160 m, Fit con 10×500 m. Solo varían name, estructura de runs, timeCapSeconds y referencias.
5. **Referencia de beginner F de DEKA Strong = 28:00** (spec §4.1) queda bajo el cap de 30:00; el mínimo de test (24:00) se fija en beginner M para que el test exigente no empiece en el borde del cap.
6. **Interferencia en F3 de N5/N6 ("mantener, no construir"):** las sesiones A/B/D de la fase del benchmark bajan a volumen mínimo y ritmo exacto, sin carga nueva. Sin calendario, la regla queda escrita en las propias sesiones.
7. **Carrera del lote:** N4 ~45-55 min (runs 160-250 m), N5 ~40-50 min (Strong no corre: runs solo en calentamiento y transiciones de la sesión A), N6 ~55-65 min (10×160 m en el circuito de C y los runs de A/D). El volumen sube en el lote 07-08 con los runs 500 m del Fit.
8. **Ensayos de re-tests integrados:** DK4 en la sesión D de N4 (farmers segmentado), DK1-DK3 en la sesión D de N6 (DK1 tras el run, la versión más dura; DK2/DK3 frescos).
9. **Duración estimada de la sesión C de N6 F3: 85-95 min** — la más larga del lote (DEKA Mile con cap 60:00 + bloques); consistente con la convención de revisar las estimaciones tras generar (`TrainingTimeCalculator` estima 60% del cap como mínimo).
