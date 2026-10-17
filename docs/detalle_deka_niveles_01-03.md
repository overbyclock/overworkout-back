# Detalle de contenido — Niveles 1, 2 y 3 — DEKA Mastery v1

> Documento fuente desde el que se generará el programa DEKA Mastery (comando
> `app:create-deka-mastery`), réplica de la arquitectura Hyrox Mastery (3 lotes:
> este documento cubre niveles 1-3). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-17-deka-mastery-design.md` (§2 formato de
> competición — Rules of Competition v20260210 + deka.fit/zones, §3 cobertura
> de catálogo, §4.1 benchmarks, §4.2 puertas y arco de niveles, §4.2 sesiones
> A/B/C/D, §4.3 regla §1.7-DEKA). Formato, tono y convenciones: misma plantilla
> que `docs/detalle_hyrox_niveles_01-03.md`.
>
> **Estado de redacción:** COMPLETO. Niveles 1, 2 y 3 con sus 12 sesiones
> (4 fases × A/B/C/D por nivel), tests, puertas DK1-DK4, skillFocus, logros y
> la ficha de benchmark del lote (DEKA Media). Las 3 fichas oficiales (DEKA
> Strong, DEKA Mile, DEKA Fit) se siembran y especifican en el lote de niveles
> 4-6. Pendiente la validación de contenido con el usuario (gate humano).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (spec §4.2): A — Run + zonas de fuerza (zancada/RAM, farmers, sled, bola); B — Ergómetros (row/ski/bike umbrales) + core; C — Simulacro/circuito de zonas (benchmark embebido solo en N3 C-F3); D — Aeróbico Z2 + movilidad.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab hombro/lumbar/tobillo rotado por sesión) → `descarga`. `restAfterBlock` = 60 s en N1-N3.
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Dosis `/lado` se computan una vez.
- **RIR:** en N1-N3 se trabaja con 2-4 RIR en fuerza de zonas; nunca al fallo. En circuitos, el fallo técnico marca el escalado (regla §1.7-DEKA §5).
- **Equipamiento asumido N1-N3:** gimnasio con remo, SkiErg, air bike (assault/echo), caja plyo de 20-24", kettlebells/mancuernas, sled de peso (sustituto del Torque/Xebex magnético de competición, nota), slam ball/medicine ball, y cinta o exterior para correr. Sustitutos: sin air bike → `Rowing Machine Sprint` o `SkiErg Sprint` por tiempo equivalente (~25 cal ≈ 1:30-2:00 al nivel de este programa); sin sled → `Step Up` cargado (push) y `Kettlebell Swing Russian` pesado (pull); sin RAM → mancuernas en back rack o goblet (nota).
- **Mapeos de zonas con nota** (spec §3, "0 fichas nuevas"): RAM lunge → `Reverse Lunge` (al peso corporal) / `Dumbbell Lunge` / `Kettlebell Goblet Lunge` pesadas con nota "simula RAM 25/15 kg en back rack"; box overs → `Box Jump` o `Step Up`/`Box Step Over` sobre caja 24" con nota "box over permitido"; MB sit-ups → `Sit-up` con medicine ball (toca/peso en nota); dead ball → `Medicine Ball Slam` pesada con nota (simula el dead ball shoulder over 27/18 kg); RAM burpees → `Burpee` con carga en nota; sled magnético → `Sled Push`/`Sled Pull` de peso con nota "sustituto del Torque/Xebex".
- **Dosis de distancia:** como convención ya establecida (spec §5), las distancias de carrera van como `durationSeconds` estimado + distancia en la descripción (160 m ≈ 40-50 s, 500 m ≈ 2:00-2:30, según nivel).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta `SELECT name FROM exercises`, 17-oct-2026). Nombres literales. Duplicados conocidos: se usa `Farmers Walk` (340), `Face Pull` (482), `Pallof Press` (314).
- **Convención de nombres de ítems de test:** los ítems que corresponden a ejercicio de catálogo usan su grafía exacta con la carga entre paréntesis (p. ej. `Dumbbell Lunge (2×8/2×6 kg)`); los benchmarks se nombran como nombre propio ("DEKA Media").
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.
- **Carrera:** DEKA exige menos carrera que Hyrox — runs cortos de 100-500 m. Volumen semanal: ~20-30 min en N1, ~30-40 en N2, ~40-50 en N3, repartidos entre las sesiones A, C y D.

---

## Puertas de movilidad del programa (definición completa, spec §4.2)

Se evalúan en el nivel **anterior** al que exigen. Fallar fuerza + puerta = `mobility_blocked` (ciclo corto de movilidad, sin repetir fuerza). DK1-DK4 son barreras de seguridad de las zonas: sin ellas no se sube de nivel aunque la fuerce sobre. Se re-verifican como `re_test` en niveles 6 (DK1-DK3) y 8 (DK4), fuera de este lote. Criterio "carga histórica" (mismo ruling que Hyrox): la puerta conserva su carga de definición en los re-tests.

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| DK1 | Zancada continua | rodilla, cadera, tobillo (zona 1: RAM alternating reverse lunges) | 30 `Reverse Lunge` alternos sin parar, rodilla de atrás al suelo en cada rep, torso alto, sin dolor matinal >2/10 en rodilla al día siguiente | N2 (zancada con carga al 50% de RAM) | **N1** (semilla) / **N2** |
| DK2 | Caja rítmica | tobillo, rodilla, muñeca (zona 3: box jump/step overs sobre caja 24") | 20 box overs fluidas sobre caja 20-24" (salto o step over) sin tropiezo, sin doble bote y sin dolor; transición arriba-abajo continua | N2 (box overs en circuitos con fatiga) | **N1** (semilla) / **N2** |
| DK3 | Bike 25 | lumbar, rodilla, sistema cardiovascular base (zona 7: air bike 25 cal) | 25 cal en `Assault Bike Sprint`/`Echo Bike Sprint` de forma continua, sentado todo el rato, sin parar los pedales | N2 (bike como umbral y en circuitos) | **N1** (semilla) / **N2** |
| DK4 | Agarre farmers | agarre, trapecio, cadena posterior (zona 6: farmers carry) | `Farmers Walk` con 2×16 kg ×100 m sin soltar las cargas, con postura erguida y pasos cortos | N3 (farmers al 50-75% como zona del simulacro) | **N3** |

**Justificación de las puertas.** Las 10 zonas DEKA se disputan una tras otra sin respiro entre medias: la rodilla y la cadera (DK1) soportan 30 zancadas cargadas que abren la carrera y otras 20 en el burpee cargado que la cierra; la caja 24" (DK2) castiga cualquier tobillo rígido o miedo al salto; el air bike (DK3) es la zona más cardiovascularmente bruta de las 10 y exige una base de tronco estable sentado; y el agarre (DK4) decide la zona 6, que en Strong/Mile se hace en 10 m ida y vuelta donde soltar una mancuerna cuesta segundos. Cada puerta se siembra en el Bloque 0 un nivel antes de exigirse.

Evaluación en este documento: **N1: ninguna** (se siembra DK1-DK4 en Bloque 0 sin evaluar). **N2: DK1, DK2 y DK3.** **N3: DK4.**

---

## Regla §1.7-DEKA en tips (spec §4.3)

Aplicar en `tips` de niveles y sesiones:

1. **La zancada cargada se progresa por carga antes que por velocidad:** rodilla de atrás al suelo, torso alto; nunca correr las zancadas de la zona 1.
2. **Box overs: se salta o se sube, nunca se tropieza:** la caja 24" se domina primero en step over; el salto llega cuando el step over es fluido.
3. **Farmers: pasos cortos y agarre muerto antes que prisa:** la zona 6 se gana en el agarre, no en las piernas.
4. **Dead ball / RAM: extensión completa de cadera antes que altura del lanzamiento:** lumbar neutra siempre; lo que no sale con cadera, no sale con espalda.
5. **Dolor matinal >2/10 = congelar el gesto un ciclo** (mismo criterio general): sustituir la zona que lo provoca por su variante asistida y retomar la progresión en el ciclo siguiente.

---

## Benchmarks del lote (fichas completas, spec §4.1)

Una ficha se siembra con este lote: `DEKA Media` (test de N3). `for_time`, `timerConfig: null` (stopwatch con cap), `partitioningAllowed: false`. El jugador registra cada zona como "ronda" (dosis por reps/distancia/tiempo; distancia no editable en v1 → se anota en notas del finish, riesgo aceptado). Las 3 fichas oficiales (DEKA Strong, DEKA Mile, DEKA Fit — zonas RX, spec §2) se siembran y especifican en el lote de niveles 4-6.

### Benchmark — "DEKA Media"

Media DEKA: 5 zonas (las 5 primeras del orden oficial) con runs de 160 m, al 50% de los pesos de competición. Es el primer simulacro del programa y el test de N3.

| Campo | Valor |
|-------|-------|
| name | DEKA Media |
| type | benchmark |
| format | for_time |
| timeCapSeconds | 1800 (30:00) |
| rxWeightMale | lunge 2×8 kg (nota RAM 25 kg), farmers 2×12 kg (nota Open 2×27 kg), MB 4,5 kg en nota |
| rxWeightFemale | lunge 2×6 kg (nota RAM 15 kg), farmers 2×8 kg (nota Open 2×18 kg), MB 3 kg en nota |
| partitioningAllowed | false |
| timerConfig | null (stopwatch con cap) |

Ejercicios (5 rondas run→zona, orden oficial 1-5; el jugador registra cada zona como "ronda"):

| # | Ejercicio (grafía catálogo) | Dosis | Nota |
|---|------------------------------|-------|------|
| 1 | `Sprint` | 160 m | durationSeconds ~40-45 s; Run 1 (tras él, zancada) |
| 2 | `Reverse Lunge` | 15/lado | con 2×8/2×6 kg en mancuernas (nota: "simula RAM 25/15 kg en back rack"); rodilla de atrás al suelo, torso alto (§1) |
| 3 | `Sprint` | 160 m | Run 2 (tras él, remo) |
| 4 | `Rowing Machine Sprint` | 250 m | ritmo Z3; remada larga |
| 5 | `Sprint` | 160 m | Run 3 (tras él, caja) |
| 6 | `Box Jump` | 10 reps | caja 24"; box over permitido: `Step Up`/`Box Step Over` en nota (§2); sin doble bote |
| 7 | `Sprint` | 160 m | Run 4 (tras él, farmers) |
| 8 | `Farmers Walk` | 50 m | 2×12/2×8 kg; pasos cortos, agarre firme (§3) |
| 9 | `Sprint` | 160 m | Run 5 (tras él, burpees) |
| 10 | `Burpee` | 10 reps | completos; aterrizaje controlado; nota: burpee cargado de competición (RAM 20/10 kg) llega en niveles altos |

scalingOptions: **scaled** — zancada y farmers al peso corporal/mancuernas ligeras disponibles, `Step Up` en lugar de `Box Jump`, runs a 100 m; **subida (N4+)** — pesos Open por separado y DEKA Strong/Mile en sus fichas (lote 4-6). Referencias de tiempo (internas, coherentes con el cap de 30:00 — formato de 5 zonas cortas sin referencia pública directa; ajustadas al contenido: 5 runs de 160 m + 5 zonas de dosis cortas):

| Nivel | Tiempo M | Tiempo F |
|-------|----------|----------|
| elite | 10:00 | 11:00 |
| advanced | 13:00 | 15:00 |
| intermediate | 17:00 | 20:00 |
| beginner | 24:00 | 27:00 |

Uso en test: N3 (lte, mínimo 20:00 (1200 s) / objetivo 15:00 (900 s), ambos bajo el cap de 30:00 — regla F-Elizabeth).

**Nota sobre rxWeight:** las dosis de test de este lote (N3 en 50%) se resuelven por `scalingOptions` + elección RX/Scaled del player, sin crear copias del benchmark (mismo criterio que Hyrox). La ficha guarda los pesos 50% de simulacro definidos; las subidas a Open van en las fichas del lote 4-6.

---

# NIVEL 1 — Base DEKA

- **Título:** Base DEKA — Técnica de las 10 zonas sin carga
- **Objetivo:** enseñar la técnica de las 10 zonas al peso corporal o con carga mínima (reverse lunge al peso corporal, box step overs bajas, burpees completos, farmers muy ligeros, row/ski/bike técnica con intervalos cortos), construir el motor base y acostumbrar a los runs cortos de 100-200 m que preceden cada zona. Aquí no se compite: se aprende el mapa de las 10 zonas.
- **Requisitos de entrada:** ninguno. Diseñado para quien nunca ha hecho una DEKA ni tocado un air bike.
- **skillFocus (obligatorio):** `Técnica de las 10 zonas al peso corporal, ergómetros básicos y runs cortos`

**Puertas de movilidad activas:** ninguna evaluada. Se siembra DK1 (zancada continua), DK2 (caja rítmica), DK3 (bike 25) y DK4 (agarre farmers) como trabajo permanente de Bloque 0, sin evaluación.

## Test de nivel N1 (resumen)

Ítems de fuerza al peso corporal, auto-evaluados al final de la Fase 4 (Recuperación), con 2-3 min de descanso entre pruebas, en este orden (de menor a mayor interferencia). Superar todos los mínimos habilita el Nivel 2. `direction` = `gte` en todos (a más reps/tiempo, mejor).

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Reverse Lunge | 20 reps | 30 reps | gte | reps |
| 2 | `Burpee` | 15 reps | 20 reps | gte | reps |
| 3 | `Box Jump` (caja 20") | 10 reps | 15 reps | gte | reps |
| 4 | `Plank` | 45 s | 60 s | gte | seconds |

Notas del test:
- Criterio de validez: en `Reverse Lunge`, rodilla de atrás al suelo en cada rep y torso alto (§1); en `Burpee`, pecho al suelo y salto con extensión; en `Box Jump`, extensión completa arriba sin doble bote (§2); rep con técnica rota no cuenta (§5).
- La caja de 20" es orientativa: el usuario puede usar la caja disponible más cercana (18-24"), anotándolo. La diana de N2+ es 24".

## Logros del nivel N1

**De test (por ítem de test, se evalúan en cualquier envío de test):**
- "Veinte zancadas" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Reverse Lunge`.
- "Quince burpees" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Burpee`.
- "Primera caja superada" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Box Jump (caja 20")`.
- "Plancha de 45 segundos" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Plank`.

**De hito (primera ejecución válida en sesión):**
- "Primera zona 10 completada" — primera sesión C con el circuito de las zonas completo sin parar el reloj. `tier: comun`.
- "Primer farmers" — primer `Farmers Walk` de 50 m con mancuernas ligeras sin soltar. `tier: comun`.
- "Primeras 25 cal" — primer bloque de 25 cal en `Assault Bike Sprint`/`Echo Bike Sprint` completado (siembra DK3). `tier: comun`.
- "Primer slam" — primer `Medicine Ball Slam` con balón de 6/4 kg y extensión de cadera completa (§4). `tier: comun`.

## Bloques comunes del Nivel 1

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 6-10 min)

Transiciones de ~5 s entre ejercicios. Contenido base de toda sesión N1 (movilidad acumulativa; siembra de las cuatro puertas):

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (siembra DK1/DK2) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 20 s/lado + 20 s/lado + 10 | 25 s/lado + 25 s/lado + 12 | 30 s/lado + 30 s/lado + 15 | 30 s/lado + 30 s/lado + 12 suave |
| Cadera/zancada (DK1, foco) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 20 s + 4/lado + 10 | 30 s + 6/lado + 12 | 40 s + 6/lado + 15 | 45 s + 6/lado suave + 10 suave |
| Caja/tobillo (DK2, foco) | `Step Up` (baja, control) + `Ankle Rock` — ver nota | 6/lado + 10 | 8/lado + 12 | 10/lado + 12 | 8/lado + 10 suave |
| Hombro/muñeca | `Shoulder Dislocate` (banda ligera) + `Band Pull-Apart` + `Wrist Prayer` | 10 + 10 + 20 s | 12 + 12 + 20 s | 15 + 12 + 25 s | 12 + 10 + 20 s suave |
| Activación escápula | `Scapular Push Up` | 8 | 10 | 12 | 10 suave |

Nota de catálogo: el componente "caja" usa `Step Up` sobre caja baja como preparación del DK2; el ejercicio de movilidad dinámica de tobillo se cubre con `Calf Raise on Step` + el propio `Step Up` controlado (no existe ficha específica de "ankle rock" en catálogo; reportado en §Pendientes, no bloquea).

Variantes por tipo de sesión:
- **A (Run + zonas):** añade `Active March` extra ×30 s; `Deep Squat Hold` final antes de la zancada.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del remo/SkiErg; respiración diafragmática 360 ×5 ciclos al final.
- **C (Circuito):** énfasis en muñeca (burpees en el circuito) y tobillo; `Clamshell` ×10/lado si el circuito incluye zancadas.
- **D (Aeróbico):** énfasis en cadera y rodilla; la descarga se amplía.

### Bloque postural (prehab — selección N1, dosificado por sesión, nunca eliminado)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` (banda ligera) | 2×8/lado / 2×6/lado / 2×10/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 2×8 / 2×8 / 2×12 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×8/lado / 2×6/lado / 2×30 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×12 / 2×12 / 2×8/lado |

Descanso entre ejercicios posturales: 45-60 s. Criterio: calidad por encima de fatiga; es prehab, no trabajo principal. Progresión: subir +1 serie al ejercicio más fácil cuando la calidad se mantiene dos sesiones seguidas.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

- Respiración diafragmática tumbado (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada en la sesión (30-45 s por zona, de la selección de Bloque 0). En Fase 4 la descarga se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N1)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Técnica de las 10 zonas sin carga; ergómetros muy cortos (100-250 m); runs 100 m |
| 2 — Progresión | 80% | Farmers ligeros introducido; bike en intervalos cortos; primer circuito de 4 zonas |
| 3 — Intensificación | 95% | Circuito de 6 zonas con runs 150-200 m; ensayo de test integrado (zancada ×20, burpee ×15) |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 1

Las 16 sesiones = 4 fases × tipos A/B/C/D. Cada sesión tiene los 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: ver "Bloques comunes" con la variante y dosis de fase indicadas. Bloque postural: tabla de la sesión correspondiente.

### Sesión A — Run corto + zonas de fuerza al peso corporal (todas las fases)

- Tipo: fuerza + carrera corta. Duración estimada: 45-60 min.
- Bloque 0: variante A, dosis de fase. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (run corto por tiempo, cinta o exterior) | 8 min Z2 | 10 min Z2 | 12 min Z2 | 6 min suave | — | Ritmo conversacional; runs de 100-200 m dentro de la sesión C, aquí el motor base continuo |
| `Reverse Lunge` (técnica) | 3×8/lado | 3×10/lado | 4×12/lado | 2×8/lado suave | 90 s | Siembra DK1: rodilla de atrás al suelo, torso alto, paso largo controlado (§1); la carga llega en N2 |
| `Box Step Over` (caja baja) | 3×8 | 3×10 | 4×10 | 2×8 suave | 90 s | Siembra DK2: step over fluido sobre caja 18-20", sin doble bote; el salto llega cuando esto sea rítmico (§2) |
| `Farmers Walk` (muy ligero) | 3×30 m | 3×40 m | 4×40 m | 2×20 m | 90 s | Mancuernas ligeras; siembra DK4/§3: pasos cortos, postura erguida, agarre firme |

**Bloque `wod` (run→zona, primer sabor DEKA):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 100 m + `Reverse Lunge` ×6/lado | 60-90 s entre rondas; transición caminando, sin prisa |
| F2 | 4 rondas | `Sprint` 150 m + `Box Step Over` ×8 | 60 s entre rondas; la caja tras correr es la lógica DEKA |
| F3 | 4 rondas | `Sprint` 200 m + `Reverse Lunge` ×8/lado + `Burpee` ×4 | Ensayo de la zona 1-10 conectadas |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Box Step Over` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "Hoy aprendes el idioma de las 10 zonas: zancada con rodilla al suelo, caja sin tropiezo, farmers con agarre firme. La DEKA se gana en la técnica antes que en el motor (§1.7-DEKA §1/§2)."

### Sesión B — Ergómetros: row, ski y bike técnica (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 45-60 min.
- Bloque 0: variante B, dosis de fase. Postural: tabla sesión B (hombro). Descarga: estándar. Sustitutos: sin remo/SkiErg → `Assault Bike Sprint` por el mismo tiempo; sin air bike → remo o SkiErg.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` (intervalos técnica) | 4×250 m | 4×250 m | 3×500 m | 3×250 m suave | 60 s | Drive con piernas, luego espalda, luego brazos; ritmo Z2-Z3 |
| `SkiErg Sprint` (intervalos técnica) | 4×100 m | 5×100 m | 4×250 m | 3×100 m suave | 60 s | Remada larga piernas-core-brazos; zona 5 se construye aquí |
| `Assault Bike Sprint` (intervalos) | 4×10 cal | 5×10 cal | 4×15 cal | 3×10 cal suave | 60 s | Siembra DK3: pedaleo continuo sentado; las 25 cal del test se construyen por intervalos |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Sit-up` + 10 `Glute Bridge` + 30 s `Plank` | 60 s entre rondas; core básico |
| F2 | AMRAP 8' | 6 `Medicine Ball Slam` (6/4 kg) + 8 `Mountain Climber` | Primera bola: extensión de cadera completa, lumbar neutra (§4) |
| F3 | 4 × (30 s trabajo / 30 s descanso) | `Assault Bike Sprint` + `Mountain Climber` alternando | Umbral suave; el bike con fatiga de transición |
| F4 | 2 rondas suaves | 8 `Sit-up` + 8 `Glute Bridge` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los tres ergómetros de la DEKA tienen una cosa en común: se reman/pedalean con el tronco quieto. Un ergómetro mal hecho es lumbar de regalo; la zona se ejecuta, no se sobrevive (§1.7-DEKA §5)."

### Sesión C — Circuito de zonas (todas las fases)

- Tipo: circuito + core. **Sin simulacro** en N1. Duración estimada: 45-60 min.
- Bloque 0: variante C, dosis de fase. Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación técnica, corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Reverse Lunge` (técnica pausada) | 2×6/lado | 2×8/lado | 3×10/lado | 2×6/lado suave | 60 s | 1 s de pausa abajo; rodilla al suelo (§1) |
| `Burpee` (técnica, sin prisa) | 2×5 | 2×6 | 3×8 | 2×5 suave | 60 s | Pecho al suelo, salto corto controlado; la carga llega en niveles altos |

**Bloque `wod` (circuito de zonas creciente):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Reverse Lunge` ×8/lado + `Rowing Machine Sprint` 200 m + `Box Step Over` ×8 | 60-90 s entre rondas; zonas 1-3 |
| F2 | 4 rondas | `Reverse Lunge` ×10/lado + `Rowing Machine Sprint` 250 m + `Box Step Over` ×10 + `Sit-up` ×10 | Zonas 1-4; el sit-up con bola llega en N2 |
| F3 | 4 rondas | Zonas 1-4 + `Assault Bike Sprint` 15 cal + `Burpee` ×6 | Seis zonas consecutivas; primer "recorrido DEKA" serio |
| F4 | 2 rondas suaves | `Reverse Lunge` ×6/lado + `Box Step Over` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "Cada ronda de hoy es un mini-recorrido DEKA: las zonas se encadenan sin descanso entre ellas, solo el que planificas entre rondas. Así se corre la DEKA, en pequeño (§1.7-DEKA §2)."

### Sesión D — Aeróbico Z2 + movilidad (todas las fases)

- Tipo: aeróbico + movilidad extra. Duración estimada: 45-60 min.
- Bloque 0: variante D, dosis de fase. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 continuo) | 12 min | 15 min | 18 min | 10 min suave | — | El run largo del nivel; DEKA exige poca carrera pero la exige siempre |
| `Goblet Squat` | 3×8 | 3×10 | 3×12 | 2×8 suave | 90 s | KB 8/6 kg; base de la zancada cargada de N2 |
| `Plank` | 3×30 s | 3×40 s | 3×45 s | 2×30 s | 60 s | Costillas abajo; respiración bajo tensión |

**Bloque `wod` (aeróbico suave):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 8 `Step Up` (bajo, ritmo suave) + 10 `Mountain Climber` + 8 `Glute Bridge` | 60-90 s entre rondas; sin carga |
| F2 | 4 rondas | 10 `Step Up` + 10 `Mountain Climber` + 10 `Sit-up` | 60 s entre rondas |
| F3 | 3 rondas | 10 `Step Up` (con mancuernas ligeras) + 10 `Medicine Ball Slam` (6/4 kg) + 30 s `Hollow Body Hold` | Primer gesto con carga sostenida; control lumbar (§4) |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "La sesión D parece fácil y es la que te deja hacer todo lo demás: 15-18 minutos Z2 hoy son los 160 m de cada zona corriendo en vez de arrastrándose. No la saltes."

---

# NIVEL 2 — Primeras cargas

- **Título:** Primeras cargas — El 50% de los pesos entra en las zonas
- **Objetivo:** llevar las zonas al 50% de los pesos de competición (zancada con 2×8/2×6 kg en back rack simulando el RAM — nota "simula RAM 15/10 kg"; farmers 2×12/2×8 kg; medicine ball 4/3 kg; slam ball pesada 10/6 kg con nota; burpee con carga ligera opcional), umbrales de bike (25 cal como referencia de umbral), box overs a caja 20-24" y runs de 160-250 m. El test mezcla ítems de fuerza con las puertas DK1-DK3.
- **Requisitos de entrada:** mínimos del test N1 (zancada ×20, burpee ×15, box jump ×10, plancha 45 s).
- **skillFocus:** `Zonas al 50% de los pesos de competición, umbrales de bike y box overs a caja 24"`
- **Puertas evaluadas:** **DK1** (zancada continua), **DK2** (caja rítmica) y **DK3** (bike 25) — requisito de entrada efectivo del N3 (zonas con carga y ritmo Mile). DK4 queda sembrada; se evalúa en N3. Ensayos integrados: DK1 en el Bloque 0 de la sesión A (zancada continua), DK2 en la sesión C (box overs en contexto), DK3 en la sesión B (25 cal acumuladas).

## Bloques comunes del Nivel 2

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 7-10 min)

Acumulativo de N1 con dosis subidas y foco nuevo: dorsiflexión y cadera para la zancada cargada. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, foco) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 12 | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, foco) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 30 s + 6/lado + 12 | 40 s + 8/lado + 15 | 45 s + 8/lado + 15 | 45 s + 6/lado suave + 12 suave |
| Caja/tobillo (DK2, soporte) | `Step Up` + `Box Step Over` (caja 20") | 6/lado + 6 | 8/lado + 8 | 10/lado + 8 | 8/lado + 6 suave |
| Hombro/muñeca | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 12 + 25 s | 15 + 15 + 25 s | 15 + 15 + 30 s | 12 + 12 + 20 s suave |
| Activación escápula | `Scapular Push Up` | 10 | 12 | 12 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** ensayo DK1: `Reverse Lunge` continuo ×20 al peso corporal; énfasis en tobillo antes de la zancada cargada.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; ensayo DK3: `Assault Bike Sprint` 10 cal suave.
- **C (Circuito):** ensayo DK2: box overs continuas ×10 suaves; énfasis en muñeca (burpees en el circuito).
- **D (Aeróbico):** énfasis en cadera y tobillo; la descarga se amplía.

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
| 1 — Base | 55% | Zancada 2×8/2×6 kg con series largas; box overs a caja 20"; bike en intervalos de 10-15 cal |
| 2 — Progresión | 80% | Farmers 2×12/2×8 kg; slam ball 10/6 kg; box overs a caja 24"; primeras 25 cal acumuladas |
| 3 — Intensificación | 95% | Dosis de test integradas (zancada ×20, box jump ×15); ensayos DK1-DK3 en las sesiones A/B/C |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 2

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Run + zonas de fuerza al 50% (todas las fases)

- Tipo: carrera + fuerza de zonas. Duración estimada: 50-70 min.
- Bloque 0: variante A (con ensayo DK1). Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (run Z2 con intervals) | 8 min Z2 | 10 min Z2 (3×1 min más rápido) | 12 min Z2 (4×1 min) | 6 min suave | — | Los intervals enseñan los runs 160 m del ritmo Mile |
| `Dumbbell Lunge` (50% RAM) | 3×10/lado (2×8/2×6 kg) | 3×12/lado | 4×15/lado | 2×10/lado suave | 90 s | Mancuernas a los lados o en back rack (nota: "simula RAM 15/10 kg"); rodilla al suelo, torso alto (§1) |
| `Farmers Walk` | 3×40 m (2×12/2×8 kg) | 4×50 m | 4×60 m | 2×30 m | 90 s | 2×12/2×8 kg; §3: pasos cortos, agarre firme; la carga de la puerta DK4 (2×16 kg) llega en N3 |
| `Medicine Ball Slam` (pesada) | 3×8 (10/6 kg) | 3×10 | 4×10 | 2×6 suave | 90 s | Balón pesado; extensión completa de cadera antes que altura (§4); simula el dead ball shoulder over de la zona 8 (nota) |

**Bloque `wod` (run→zona con carga):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×8/lado (2×8/2×6 kg) | 60-90 s entre rondas; primera zona cargada tras correr |
| F2 | 4 rondas | `Sprint` 160 m + `Farmers Walk` 40 m (2×12/2×8 kg) | 60 s entre rondas; §3 en el farmers |
| F3 | 4 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×10/lado + `Medicine Ball Slam` ×6 | Zona 1 → zona 8, las dos extremas de la carrera |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Dumbbell Lunge` ×6/lado | Movimiento; test al final del nivel |

**Tips de sesión:** "La zancada con mancuernas no es una caminata con peso: la rodilla de atrás besa el suelo y el torso se mantiene alto. Si la rodilla no baja, la rep no cuenta (§1.7-DEKA §1)."

### Sesión B — Ergómetros umbrales (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 50-65 min.
- Bloque 0: variante B (con ensayo DK3). Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` | 4×500 m | 4×500 m | 3×750 m | 2×500 m suave | 90 s | Ritmo Z3-Z4; la zona 2 de competición es 500 m — aquí se construye |
| `SkiErg Sprint` | 4×250 m | 4×250 m | 4×500 m | 3×250 m suave | 90 s | La zona 5 es 500 m; umbral controlado |
| `Assault Bike Sprint` (25 cal camino) | 5×10 cal | 4×15 cal | 3×(10 cal + 15 cal) | 3×10 cal suave | 90 s | Ensayo DK3: acumular 25 cal continuas en F3; pedaleo sentado, sin parar (sustituto: `Echo Bike Sprint`) |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | AMRAP 10' | 6 `Medicine Ball Slam` (10/6 kg) + 8 `Mountain Climber` | §4: cadera completa, lumbar neutra |
| F3 | 3×(25 cal `Assault Bike Sprint` continuas + 10 `Push Up`) | Bike sin parar los pedales (DK3 entera) | El DK3 completo en contexto de fatiga; si no llega, reduce a 15+10 cal |
| F4 | 2 rondas suaves | 8 `Sit-up` + 8 `Glute Bridge` | Movimiento; test al final del nivel |

**Tips de sesión:** "Las 25 cal del bike no se negocian con el reloj: se pedalean sentado, con el tronco quieto y sin parar. El día que salgan continuas en contexto, DK3 es tuya (§1.7-DEKA §5)."

### Sesión C — Circuito de zonas con carga (todas las fases)

- Tipo: circuito + core. **Sin simulacro** en N2 (el primer benchmark embebido llega en N3). Duración estimada: 50-70 min.
- Bloque 0: variante C (con ensayo DK2). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Box Jump` (caja 20-24") | 2×8 | 2×10 | 3×10 | 2×6 suave | 60 s | Ensayo DK2: fluidas, sin doble bote (§2); step over válido si el salto no va |
| `Burpee` | 2×6 | 2×8 | 3×10 | 2×6 | 60 s | Completos; la carga ligera (chaleco o balón) es opcional en F3 — nota |

**Bloque `wod` (zonas con fatiga de transición):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Box Jump` ×8 + `Sit-up` ×10 (MB 4/3 kg, toque) + `Burpee` ×6 | 60-90 s entre rondas; zonas 3-4-10 en reducido |
| F2 | 4 rondas | `Dumbbell Lunge` ×8/lado (2×8/2×6 kg) + `Rowing Machine Sprint` 250 m + `Box Jump` ×8 | Zonas 1-2-3 con fatiga real |
| F3 | 3 rondas | `Assault Bike Sprint` 15 cal + `Medicine Ball Slam` ×8 (10/6 kg) + `Farmers Walk` 40 m (2×12/2×8 kg) + `Burpee` ×8 | Zonas 7-8-6-10: el final de la carrera en serio |
| F4 | 2 rondas suaves | `Box Jump` ×6 + `Burpee` ×5 | Movimiento; test al final del nivel |

**Tips de sesión:** "La caja se salta o se sube, pero nunca se tropieza: si hoy la dominas en step over rítmico, mañana saltas; si hoy tropiezas, vuelves al step (§1.7-DEKA §2)."

### Sesión D — Aeróbico + movilidad + DK4 sembrada (todas las fases)

- Tipo: aeróbico + movilidad. Duración estimada: 50-65 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 continuo) | 15 min | 18 min | 20 min | 12 min suave | — | El run largo del nivel; poca carrera pero constante |
| `Farmers Walk` (DK4 sembrada) | 2×50 m (2×12/2×8 kg) | 2×75 m | 2×100 m | 2×50 m | 90 s | Kilómetro de farmers segmentado hacia los 2×16 kg de DK4; §3 |
| `Plank` | 3×45 s | 3×50 s | 3×60 s | 2×40 s | 60 s | Costillas abajo; base del tronco en el bike |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Box Step Over` + 8 `Push Up` | Primer AMRAP con carga sostenida |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de F3 son la antesala de DK4: si el agarre aguanta sin que la postura se caiga, el 2×16 kg de N3 será solo un paso más (§1.7-DEKA §3)."

## Test de nivel N2 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Dumbbell Lunge (2×8/2×6 kg) | 20 reps | 30 reps | gte | reps |
| 2 | `Box Jump` (caja 24") | 15 reps | 20 reps | gte | reps |
| 3 | Assault Bike 25 cal (tiempo) | 2:30 (150 s) | 1:45 (105 s) | lte | seconds |
| 4 | DK1 — 30 reverse lunges sin parar | 30 reps | 30 reps sin pausa >3 s | gte | reps |
| 5 | DK2 — 20 box overs fluidas | 20 reps | 20 reps sin tropiezo | gte | reps |
| 6 | DK3 — 25 cal bike continuas | 25 cal | 25 cal en <2:00 | gte | cal |

Notas del test:
- Ítems 1-3: fuerza (`type: fuerza`). Validez: en la zancada, rodilla de atrás al suelo y torso alto (§1); en la caja, extensión completa sin doble bote (§2); el bike se cronometra (25 cal continuas, sentado; si no hay air bike: `Echo Bike Sprint`, `Rowing Machine Sprint` 500 m o `SkiErg Sprint` 500 m como sustituto anotado).
- Ítems 4-6: puertas (`type: puerta_movilidad`, `gateCode` DK1/DK2/DK3). Mínimos = criterio entero de la puerta; objetivos añaden fluidez/tiempo como referencia.
- Modo de evaluación: `strict_gates` (spec §4.2) — superar los ítems 1-3 **y** DK1-DK3 habilita el N3. Fallar fuerza + puerta = `mobility_blocked` (ciclo corto de movilidad).
- Orden recomendado (menor a mayor interferencia): DK1 → ítem 1 → DK2 → ítem 2 → DK3 → ítem 3, con 2-3 min entre pruebas.

## Logros del nivel N2

**De test:**
- "Zancada de 20 con carga" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Dumbbell Lunge (2×8/2×6 kg)`.
- "Caja de 24 pulgadas" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Box Jump (caja 24")`.
- "Bike 25 cronometrada" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Assault Bike 25 cal (tiempo)`.
- "Puerta DK1 superada" / "Puerta DK2 superada" / "Puerta DK3 superada" — `type: test` vinculado a puerta (mobility gate passed). `tier: raro`.

**De hito:**
- "25 cal continuas" — primeras 25 cal en `Assault Bike Sprint`/`Echo Bike Sprint` sin parar los pedales (DK3 entera). `tier: comun`.
- "Primera zancada cargada" — primer `Dumbbell Lunge` con 2×8/2×6 kg válido (rodilla al suelo). `tier: comun`.
- "Primer farmers de 60 m" — primer `Farmers Walk` de 60 m sin soltar con 2×12/2×8 kg. `tier: comun`.
- "Primer slam pesado" — primer `Medicine Ball Slam` de 10/6 kg con extensión de cadera completa (§4). `tier: comun`.

---

# NIVEL 3 — Media DEKA

- **Título:** Media DEKA — Cinco zonas a ritmo de carrera
- **Objetivo:** ejecutar el primer simulacro embebido (5 zonas con runs de 160 m, pesos 50%) como test del nivel, entrenar transiciones zona→run con ritmo de Mile, umbrales ergómetros y superar la puerta DK4 (farmers 2×16 kg ×100 m). Al superar N3, el atleta está listo para las zonas al 100% Open y las carreras completas (N4-N6).
- **Requisitos de entrada:** mínimos del test N2 (incl. DK1-DK3 superadas).
- **skillFocus:** `Simulacro de media DEKA, transiciones zona→run a ritmo Mile y umbrales ergómetros`
- **Puertas evaluadas:** **DK4** (agarre farmers) — primera evaluación; re-test en N8 (fuera de este lote).

## Test de nivel N3 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | DEKA Media | 20:00 (1200 s) | 15:00 (900 s) | lte | seconds |
| 2 | DK4 — Farmers 2×16 kg ×100 m sin soltar | 100 m | 100 m en <1:45 | gte | meters |

Notas del test:
- Ítem 1: `type: benchmark` sobre `DEKA Media` (placeholder `ID_DMEDIA`, ficha de este lote), modalidad RX (50%). Marca en `UserBenchmarkAttempt` → alimenta el test. Mínimo (20:00) bajo el cap de 30:00, en torno a intermediate F (20:00); objetivo (15:00) entre advanced F (15:00) e intermediate M (17:00). Regla F-Elizabeth.
- Ítem 2: puerta DK4 (`type: puerta_movilidad`, `gateCode: DK4`). Criterio: 100 m con 2×16 kg sin soltar, postura erguida, pasos cortos (§3); el objetivo añade tiempo como referencia.
- Modo de evaluación: `strict_gates` — superar el benchmark **y** DK4 habilita el N4. Fallar benchmark + puerta = `mobility_blocked`.
- Orden recomendado: DK4 primero (descansado) y la DEKA Media después, o en sesiones separadas del envío de test.

## Logros del nivel N3

**De test:**
- "Media DEKA completada" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: DEKA Media`.
- "Media DEKA bajo 15" — `type: test`, `tier: raro`, `requirementType: test_item_obj`, `requirementKey: DEKA Media`, `requirementValue: 900`.
- "Puerta DK4 superada" — `type: test` vinculado a puerta (mobility gate passed). `tier: raro`.

**De hito:**
- "Primer simulacro completado" — primera ejecución completa de la `DEKA Media` en sesión C (F3), dentro del cap. `tier: epico`.
- "Farmers de 2×16 kg" — primer `Farmers Walk` de 100 m con 2×16 kg sin soltar (DK4 entera). `tier: comun`.
- "Cinco zonas seguidas" — primer circuito de 5 zonas consecutivas del nivel sin parar el reloj. `tier: comun`.
- "Ritmo Mile" — primer run de 160 m a ritmo de carrera (bajo 45 s) saliendo de una zona, manteniendo el ritmo en la siguiente transición. `tier: raro`.

## Bloques comunes del Nivel 3

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1+N2 con foco nuevo: cadena posterior y agarre (DK4) y tobillo para el ritmo Mile. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 30 s/lado + 30 s/lado + 15 | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, mantenido) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 40 s + 8/lado + 15 | 45 s + 8/lado + 15 | 60 s + 10/lado + 15 | 45 s + 6/lado suave + 12 suave |
| Cadena posterior/agarre (DK4, foco nuevo) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 30 s/lado + 12 + 15 | 30 s/lado + 12 + 15 | 40 s/lado + 12 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 30 s | 15 + 15 + 30 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 12 | 12 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** ensayo DK1 (zancada continua ×30); activación de agarre (`Farmer Walk` ligero 20 m) antes del farmers fuerte.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y tobillo; en F3 (DEKA Media) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico de las 5 zonas.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; ensayo de DK4 a dosis exacta.

### Bloque postural (prehab — selección N3)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×8/lado / 3×45 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. El prehab N3 es la última capa densa del tramo de fundamentos: en N4-N6 se mantiene a estas dosis y crece el volumen de las carreras.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que N1-N2: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N3)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | 5 zonas al 50-60% con runs 160 m; ergómetros 500-750 m; farmers 2×16 kg introducido |
| 2 — Progresión | 80% | Transiciones zona→run cronometradas; umbrales de bike; ensayo de DK4 (farmers ×100 m) |
| 3 — Intensificación | 95% | **Benchmark embebido: DEKA Media en sesión C**; nada de carga nueva en las sesiones previas del simulacro |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 3

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido.

### Sesión A — Run + zonas de fuerza al 50-75% (todas las fases)

- Tipo: carrera + fuerza de zonas. Duración estimada: 55-75 min.
- Bloque 0: variante A (con ensayo DK1). Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (run con intervals 160 m) | 6×160 m (ritmo Z3) | 8×160 m (ritmo Mile ~45 s) | 8×160 m (ritmo exacto) | 4×160 m suave | 60-90 s | El ritmo Mile se aprende aquí: 160 m con recuperación caminando de vuelta; F2-F3 fijan el ritmo objetivo |
| `Dumbbell Lunge` (subida a 75%) | 3×12/lado (2×8/2×6 kg) | 3×15/lado | 4×15/lado (2×10/2×8 kg) | 2×10/lado suave | 90 s | La carga sube a 2×10/2×8 kg en F3 (75% del RAM 25/15 kg, nota); §1 siempre |
| `Farmers Walk` (DK4, camino) | 3×50 m (2×16/2×12 kg) | 3×75 m | 2×100 m (ensayo DK4) | 2×40 m | 90 s | El 2×16 kg entra en F1; F3 ensaya los 100 m exactos del re-test; §3 |
| `Medicine Ball Slam` | 3×10 (10/6 kg) | 3×12 | 4×12 | 2×8 suave | 90 s | §4: cadera completa, lumbar neutra; la zona 8 de la carrera real se construye aquí |

**Bloque `wod` (transición run→zona a ritmo):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×8/lado (2×8/2×6 kg) | 60-90 s entre rondas; la zona 1 saliendo de correr |
| F2 | 4 rondas | `Sprint` 160 m + `Farmers Walk` 50 m (2×16/2×12 kg) + `Sprint` 160 m | 90 s entre rondas; zona 6 con run por medio, como en la carrera |
| F3 | 3 rondas | `Sprint` 160 m a ritmo exacto + `Medicine Ball Slam` ×8 | "Mantener, no construir": la DEKA Media está cerca y no lleva slam — el volumen se recorta |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Dumbbell Lunge` ×6/lado | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 160 m del ritmo Mile no se corren, se administran: bajo 45 segundos con la respiración controlada, y la zona que viene después se ejecuta, no se sobrevive. Ese es todo el secreto (§1.7-DEKA §1)."

### Sesión B — Ergómetros umbrales (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 50-70 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` (umbral) | 3×500 m | 4×500 m | 3×750 m | 2×500 m suave | 90 s | La zona 2 es 500 m: F2 la ensaya exacta; umbral sostenible |
| `SkiErg Sprint` (umbral) | 3×500 m | 3×500 m | 4×500 m | 2×500 m suave | 90 s | La zona 5 es 500 m; remada larga hasta el final |
| `Assault Bike Sprint` | 3×20 cal | 3×25 cal | 2×(25 cal + 15 cal) | 2×15 cal suave | 90 s | Las 25 cal como umbral, no como puerta: ritmo sostenido de DK3 |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 5 × (1' trabajo / 1' descanso) | `Assault Bike Sprint` a umbral | Acumula 20-25 cal por intervalo |
| F3 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Mountain Climber` + 8 `Sit-up` | Umbral sostenido; control lumbar (§5) |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los tres ergómetros de la DEKA Media son 250 m de remo dentro del simulacro, pero aquí se entrenan al doble: lo que corre cómodo en el entrenamiento, sale solo en la carrera (§1.7-DEKA §5)."

### Sesión C — Circuito de zonas / DEKA Media (todas las fases)

- Tipo: circuito de zonas (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 55-75 min (F1/F2/F4); 75-90 min (F3 con la DEKA Media).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Dumbbell Lunge` (2×8/2×6 kg) | 2×8/lado | 2×10/lado | 1×6/lado (calentamiento del simulacro) | 2×8 suave | 60 s | En F3 es calentamiento específico: toca las 5 zonas del benchmark con dosis mínimas (zancada ×4/lado, row 100 m, box ×3, farmers 20 m, burpee ×3) |
| `Box Jump` (caja 24") | 2×8 | 2×10 | — (el simulacro es el protagonista) | 2×6 | 60 s | Mantiene el patrón de la zona 3 entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | `Sprint` 160 m + `Dumbbell Lunge` ×8/lado (2×8/2×6 kg) + `Rowing Machine Sprint` 250 m + `Box Jump` ×8 | 90 s entre rondas; las zonas 1-3 con run, el inicio del benchmark |
| F2 | 4 rondas | `Sprint` 160 m + `Box Jump` ×10 + `Farmers Walk` 50 m (2×12/2×8 kg) + `Burpee` ×8 | 90 s entre rondas; las zonas 3-4-6-10 con transiciones cronometradas |
| F3 | **Benchmark embebido: DEKA Media** | `DEKA Media` en modalidad RX (ver ficha): 5×(run 160 m + zona: zancada ×15, row 250 m, box ×10, farmers 50 m, burpees ×10) | Stopwatch + cap 30:00; la marca alimenta el test; logro "Primer simulacro completado". Empezar fresco: nada de carga nueva en las sesiones previas |
| F4 | 2 rondas suaves | `Sprint` 100 m + `Box Jump` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "La DEKA Media son cinco estaciones cortas que se encadenan: la recuperación es el run de 160 m, y el run se corre con la respiración de la zona anterior. Terminar bajo 20 minutos es el plan; terminar entero es la victoria (§1.7-DEKA §2/§3)."

### Sesión D — Aeróbico + movilidad + DK4 (todas las fases)

- Tipo: aeróbico + movilidad + re-test. Duración estimada: 55-75 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con intervals) | 18 min (4×160 m dentro) | 20 min (6×160 m) | 15 min suave (afilar) | 12 min Z2 | — | El volumen aeróbico del nivel con los 160 m del ritmo Mile integrados |
| `Farmers Walk` (DK4 exacto) | 3×50 m (2×16 kg) | 3×75 m | 2×100 m (ensayo re-test) | 2×40 m | 90 s | La carga de la puerta (2×16 kg) desde F1; F3 ensaya los 100 m exactos sin soltar |
| `Plank` | 3×45 s | 3×60 s | 2×60 s | 2×40 s | 60 s | Costillas abajo; tronco estable para el bike y el slam |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Mantener, no construir: el simulacro está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de F3 son la puerta DK4 entera: 2×16 kg, sin soltar, pasos cortos. Si el agarre falla a los 80 m, la respuesta es más farmers en las próximas fases, no más ganas hoy (§1.7-DEKA §3)."

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N1-N3: todos los ejercicios citados existen en el catálogo con la grafía usada (verificados contra BD dev, 17-oct-2026: `Reverse Lunge` 150, `Dumbbell Lunge` 164, `Kettlebell Goblet Lunge` 165, `Box Jump` 159, `Medicine Ball Slam` 338, `Assault Bike Sprint` 333, `Echo Bike Sprint` 548, `Box Step Over` 533, más todos los de movilidad/core de los bloques comunes).
- `[SIN FICHA]` detectado (menor, no bloquea): no existe ficha de movilidad dinámica de tobillo tipo "ankle rock / knee-to-wall" — cubierto con `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` + `Step Up` controlado. Se reporta para una futura ficha de movilidad (misma situación que el "Knee-to-Wall Rocks" reportado en CrossFit).
- Mapeos con nota (spec §3, "0 fichas nuevas"): RAM lunge → `Dumbbell Lunge`/`Kettlebell Goblet Lunge` pesadas con nota "simula RAM 25/15 kg en back rack"; RAM burpee → `Burpee` + carga en nota (opcional en N2, estándar en niveles altos); dead ball shoulder over → `Medicine Ball Slam` pesada con nota (extensión de cadera, §4); sled magnético (Torque/Xebex) → `Sled Push`/`Sled Pull` de peso con nota "sustituto"; MB sit-ups → `Sit-up` con medicine ball en nota.
- Duplicados conocidos (reportados en los lotes Hyrox): `Farmers Walk` (340) / `Farmer Walk` (280), `Face Pull` (482) / `Face pull` (756), `Pallof Press` (314) / `Pallof press` (782). Este documento usa siempre la grafía principal.
- Las 3 fichas oficiales (DEKA Strong, DEKA Mile, DEKA Fit) se siembran y especifican en el lote de niveles 4-6.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Test N1 = solo ítems de fuerza al peso corporal** (zancada, burpee, box jump, plancha), sin puertas ni benchmark: N1 es marca de base y aprendizaje, coherente con el arco del spec ("fuerza (zonas al peso corporal)").
2. **Test N2 = 3 ítems de fuerza + DK1-DK3** (6 ítems totales) y **test N3 = DEKA Media + DK4** (2 ítems): coherente con spec §4.2 (evaluación N2 DK1-DK3, DK4 sembrada). La instrucción del lote fija la evaluación de DK4 en N3 (primera evaluación), anticipando en un nivel la propuesta original del spec (N4) — el arco de N4 queda entonces como "zonas Open + DEKA Strong", sin puerta nueva. A confirmar con el usuario.
3. **Re-test DK4 en N8** con carga histórica (mismo ruling que Hyrox): la puerta conserva su carga de definición (2×16 kg) en el re-test, aunque en N7-N8 el atleta entrena farmers a 2×27/2×18 kg. Fuera de este lote.
4. **Sustituto del air bike** para gimnasios sin máquina: `Echo Bike Sprint` (misma ficha de familia), `Rowing Machine Sprint` 500 m o `SkiErg Sprint` 500 m anotados — se registra el sustituto usado en el intento (mismo criterio que los sustitutos de Hyrox).
5. **Pesos del DEKA Media = 50% de Open** (lunge 2×8/2×6 kg vs RAM 25/15 kg; farmers 2×12/2×8 kg vs 2×27/2×18 kg) según spec §4.1; la ficha guarda esos pesos con los pesos Open en nota.
6. **Carrera reducida respecto a Hyrox** (runs de 100-250 m; ~20-50 min semanales): coherente con el formato DEKA (160 m en Mile, 500 m en Fit) frente a los 1 km de Hyrox. El volumen sube en los lotes 4-6 con los runs 500 m del Fit.
7. **Ensayos de puertas integrados:** DK1 en el Bloque 0 de la sesión A (zancada continua ×30), DK2 en la sesión C (box overs en contexto), DK3 en la sesión B (25 cal acumuladas) y DK4 en la sesión D (farmers segmentado hacia 2×16 kg ×100 m).
8. **Interferencia en F3 de N3:** las sesiones A/B/D bajan a "mantener, no construir" (la DEKA Media no incluye slam, así que el slam se recorta en la fase del simulacro).
9. **Logros de puerta** (DK1-DK4) modelados como logros `type: test` vinculados a puerta, igual que el patrón Hyrox ("Puerta HY1 superada").
