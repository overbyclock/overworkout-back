# Detalle de contenido — Niveles 7 y 8 — DEKA Mastery v1

> Documento fuente desde el que se generará el programa DEKA Mastery (comando
> `app:create-deka-mastery`), lote 3 de 3 (niveles 7-8, cierre del programa).
> Continuidad total con `docs/detalle_deka_niveles_01-03.md` (lote 1: niveles
> 1-3, benchmark DEKA Media, puertas DK1-DK4) y
> `docs/detalle_deka_niveles_04-06.md` (lote 2: niveles 4-6, benchmarks DEKA
> Strong/Mile/Fit, bloque ZONAS RX compartido, re-tests DK4 y DK1-DK3).
> Especificación de autoridad:
> `docs/superpowers/specs/2026-10-17-deka-mastery-design.md` (§2 pesos
> oficiales, §4.1 benchmarks y referencias, §4.2 puertas y arco, §4.3 regla
> §1.7-DEKA).
>
> **Estado de redacción:** COMPLETO. Niveles 7 y 8 con sus 8 sesiones
> (4 fases × A/B/C/D por nivel), tests, logros y el cierre del programa. Con
> este lote el programa queda completo: 8 niveles × 16 sesiones = 128
> trainings, 5 fichas de benchmark (DEKA Media, Strong, Mile, Fit) y las 4
> puertas DK1-DK4 con su calendario de evaluación (N2, N3, re-tests N4/N6/N7).
> Pendiente la validación de contenido con el usuario (gate humano).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (spec §4.2): A — Run + zonas de fuerza (zancada/RAM, farmers, sled, bola); B — Ergómetros (row/ski/bike umbrales) + core; C — Simulacro/circuito de zonas (benchmark embebido en C-F3 de N7 y N8: DEKA Fit); D — Aeróbico Z2 + movilidad.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab hombro/lumbar/tobillo rotado por sesión) → `descarga`. `restAfterBlock` = **60 s en N7** y **90 s en N8** (regla del proyecto: niveles ≤6 → 60 s, resto → 90 s).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Dosis `/lado` se computan una vez.
- **RIR:** en N7-N8 el trabajo de zona es de ritmo y potencia repetida, no de fuerza máxima: 1-2 RIR en las series de mantenimiento; en simulacros y circuitos de ritmo manda el objetivo de carrera, y el fallo técnico sigue marcando el escalado (regla §1.7-DEKA §5).
- **Equipamiento asumido N7-N8:** remo, SkiErg, air bike (assault/echo), caja plyo de 24", kettlebells/mancuernas (hasta 2×27 kg), slam ball pesada (27/18 kg o mancuerna), sled de peso (sustituto del Torque/Xebex magnético, nota), chaleco lastrado opcional para el burpee cargado (nota), y cinta o exterior para correr. Sustitutos: sin air bike → `Echo Bike Sprint` (misma familia) o `Rowing Machine Sprint`/`SkiErg Sprint` 500 m anotados; sin sled → `Step Up` cargado (push) y `Kettlebell Swing Russian` pesado (pull); sin RAM → mancuernas en back rack o a los lados (nota: "la carga real de RAM va en back rack, la mancuerna es sustituto").
- **Mapeos de zonas con nota** (spec §3, "0 fichas nuevas"): iguales que el lote 2 — RAM lunge → `Dumbbell Lunge`/`Kettlebell Goblet Lunge` pesadas + nota; dead ball shoulder over → `Medicine Ball Slam` pesada + nota (en Fit es yoke over, nota de la ficha); RAM burpee → `Burpee` + carga en nota; sled magnético → `Sled Push`/`Sled Pull` de peso + nota "5×10 m ida y vuelta en competición"; MB sit-ups en Fit → throws a diana (nota).
- **Dosis de distancia/calorías:** como convención establecida (spec §5), van como `durationSeconds`/reps estimados + dosis exacta en la descripción (160 m ≈ 40-50 s, 500 m ≈ 2:00-2:30 según nivel y fase).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (verificada en los lotes 1-2, 17-oct-2026). Duplicados conocidos: se usa `Farmers Walk` (340), `Face Pull` (482), `Pallof Press` (314).
- **Convención de nombres de ítems de test:** los benchmarks se nombran como nombre propio ("DEKA Fit"); los re-tests de puerta llevan el código DK n como nombre literal.
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.
- **Carrera:** DEKA Fit añade 5 km de carrera a las 10 zonas. Volumen semanal: ~65-75 min en N7 (progresión 2.500 → 4.000 m en sesiones clave), ~55-65 min en N8 (mantenimiento + intensidad; el volumen no sube: en el tramo final se afila, no se acumula).

---

## Regla §1.7-DEKA en tips (referenciada, spec §4.3 — misma regla de los lotes 1-2)

Aplicar en `tips` de niveles y sesiones (texto íntegro en `detalle_deka_niveles_01-03.md`, §"Regla §1.7-DEKA"):

1. **La zancada cargada se progresa por carga antes que por velocidad:** rodilla de atrás al suelo, torso alto.
2. **Box overs: se salta o se sube, nunca se tropieza:** la caja 24" se domina primero en step over.
3. **Farmers: pasos cortos y agarre muerto antes que prisa:** la zona 6 se gana en el agarre, no en las piernas.
4. **Dead ball / RAM: extensión completa de cadera antes que altura del lanzamiento:** lumbar neutra siempre.
5. **Dolor matinal >2/10 = congelar el gesto un ciclo.**

Añadir en este tramo la capa de estrategia de carrera del Fit: **ritmo negativo en los 500 m** (los últimos 3 runs más rápidos que los primeros 3), **transición run→zona en 3-5 pasos** (el "Roxzone" DEKA: no sprintar la entrada ni pausar la salida), **series planificadas** en lunge (bloques de 10), burpee (bloques de 5) y box (series de 5-10), **gestión del bike y el slam bajo fatiga** (el bike se pedalea sentado y continuo; el slam sale de cadera aunque los hombros ardan), e **hidratación** en simulacros largos (agua en las transiciones de los runs pares, 2-3 sorbos, sin parar el reloj en entrenamiento).

---

## Puertas de movilidad activas en este lote

- **N7: re-test DK4** con carga histórica: `Farmers Walk` 2×16 kg ×100 m sin soltar (ruling "carga histórica" de Hyrox: la puerta conserva su carga de definición pese a que el atleta entrena farmers a 2×27/2×18 kg). Es el último re-test del programa.
- **N8: ninguna puerta.** DK1-DK4 cerradas (DK1-DK3 re-validadas en N6, DK4 en N7). El test de N8 se cierra con el benchmark de cierre.

---

## Benchmarks del lote

No se siembra ninguna ficha nueva: la ficha **DEKA Fit** (placeholder `ID_FIT`, sembrada en el lote 2) es el benchmark de ambos niveles de este lote. Definición íntegra (tabla de zonas + referencias) en `detalle_deka_niveles_04-06.md`, §"Ficha 3 — DEKA Fit". Resumen operativo:

| Campo | Valor |
|-------|-------|
| name | DEKA Fit |
| format / cap | for_time / 5400 (90:00) |
| Estructura | 10×(`Sprint` 500 m + zona del array ZONAS RX en orden) — runs de 500 m como `durationSeconds` (~2:00-2:30) + distancia en descripción |
| rxWeight | ZONAS RX oficiales M/F (lunge 2×16/2×10 kg nota RAM; farmers 2×27/2×18 kg; slam 27/18 kg nota yoke over; MB sit-up 9/6.5 kg nota throw a diana; sled según gimnasio nota Torque/Xebex) |
| Referencias | elite 28:00 M/31:00 F · advanced 33:00/37:00 · intermediate 40:00/45:00 · beginner 48:00/55:00 |
| scalingOptions | **scaled** — runs 250-400 m (según nivel del atleta) y/o pesos del lote anterior (zancada 2×8/2×6 kg, farmers 2×12/2×8 kg); la modalidad usada se registra en el intento |

Usos en test:
- **N7 (transición):** lte, mínimo 60:00 (3600 s) / objetivo 48:00 (2880 s) — mínimo generoso bajo el cap de 90:00 (regla F-Elizabeth); el atleta puede ejecutarlo scaled con runs 250-400 m.
- **N8 (cierre):** lte, mínimo 48:00 (2880 s) / objetivo 40:00 (2400 s) — la misma carrera como marca de competición.

**Nota de diseño (explícita):** N7 y N8 comparten **una sola ficha** (`ID_FIT`) con dos niveles de test sobre ella — análogo a L7/L8 de Hyrox pero sin duplicar ficha. Antes de transcribir, **verificar que la plataforma tolera el mismo `benchmarkId` en dos niveles distintos** (mismos ítems de test con mínimos/objetivos distintos). Si hay riesgo, la alternativa propuesta es crear una ficha duplicada "DEKA Fit (transición)" con cap 5400 y referencias iguales, solo para el test de N7 (ver §Decisiones).

---

# NIVEL 7 — Transición Fit

- **Título:** Transición Fit — Cinco kilómetros, sin quemarse
- **Objetivo:** preparar los 5 km de carrera de la DEKA Fit sin quemar al atleta: runs de 500 m a ritmo con 1-2 zonas intercaladas por bloque (volumen progresivo de 2.500 a 4.000 m semanales en las sesiones clave), zonas RX en trenes de 2-3 con fatiga y umbrales ergómetros largos. El test es la DEKA Fit ejecutada en fase de transición (misma ficha, mínimo generoso, runs scaled opcionales).
- **Requisitos de entrada:** mínimos del test N6 (DEKA Mile + re-tests DK1-DK3).
- **skillFocus (obligatorio):** `Transición a DEKA Fit: runs de 500 m a ritmo con zonas intercaladas y volumen progresivo de carrera`
- **Puertas evaluadas:** **re-test DK4** con carga histórica (2×16 kg ×100 m). Última puerta del programa.

## Test de nivel N7 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | DEKA Fit | 1:00:00 (3600 s) | 48:00 (2880 s) | lte | seconds |
| 2 | DK4 (re-test) — Farmers 2×16 kg ×100 m sin soltar | 100 m | 100 m en <1:45 | gte | meters |

Notas del test:
- Ítem 1: `type: benchmark` sobre `DEKA Fit` (placeholder `ID_FIT`), en fase de transición: el atleta elige RX (runs 500 m) o scaled (runs 250-400 m según scalingOptions); la modalidad se registra en `UserBenchmarkAttempt`. Mínimo (60:00) muy por debajo del cap de 90:00 y de beginner M (48:00) — es una toma de contacto con el formato largo, no una exigencia de ritmo. Objetivo (48:00) = beginner M. Regla F-Elizabeth.
- Ítem 2: re-test de puerta (`type: re_test`, `gateCode: DK4`) con carga histórica (2×16 kg, menor que la de trabajo 2×27/2×18 kg) — el re-test verifica que el gesto sigue siendo seguro con la carga de definición.
- Modo de evaluación: `strict_gates` — superar el benchmark **y** DK4 habilita el N8.
- Orden recomendado: DK4 descansado primero y la DEKA Fit después (o en sesiones separadas del envío de test). El Fit es el esfuerzo más largo del programa: hidratación y plan de ritmo negativo en los tips.

## Logros del nivel N7

**De test:**
- "Primera DEKA Fit completa" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: DEKA Fit`.
- "Fit bajo 48" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: DEKA Fit`, `requirementValue: 2880`.
- "Puerta DK4 re-validada" — `type: test` vinculado a re-test de puerta (`re_test`, `gateCode: DK4`). `tier: raro`.

**De hito:**
- "Cinco mil metros de carrera en zonas" — primera sesión con 5.000 m de runs acumulados junto a zonas (circuito de sesión C en F2). `tier: epico`.
- "500 m a ritmo" — primer run de 500 m bajo 2:30 con zona intercalada ejecutada con técnica completa. `tier: comun`.
- "Tren de tres zonas" — primer tren de 3 zonas RX (zancada→remo→caja o slam→sled→burpee) completo con fatiga. `tier: raro`.
- "Segunda mitad fuerte" — circuito completo con los 3 últimos runs más rápidos que los 3 primeros (ritmo negativo entrenado). `tier: raro`.

## Bloques comunes del Nivel 7

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N6 a dosis de mantenimiento alto; con 5 km de carrera y las 10 zonas RX en juego, el Bloque 0 es la primera línea de defensa. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 45 s/lado + 45 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, mantenido) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 60 s + 10/lado + 15 | 60 s + 10/lado + 15 | 75 s + 10/lado + 15 | 60 s + 6/lado suave + 12 suave |
| Cadena posterior/agarre (DK4, re-test camino) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 45 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (slam/burpee) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 45 s | 20 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 15 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** activación de agarre antes del farmers; énfasis en tobillo antes de los 500 m.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y agarre; en F3 (DEKA Fit) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico de las 10 zonas.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; ensayo del re-test DK4.

### Bloque postural (prehab — selección N7)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 3×10/lado / 2×10/lado / 2×15/lado |
| B | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| C | Tobillo/core | `Dead Bug` + `Bird Dog` + `Plank` | 2×12/lado / 2×10/lado / 3×60 s |
| D | Trío suave | `Band Pull-Apart` + `Glute Bridge` + `Dead Bug` | 2×15 / 2×15 / 2×12/lado |

Descanso entre ejercicios posturales: 45-60 s. En el tramo Fit el prehab se blinda: con 5 km de carrera acumulada, es la única carga que no se negocia.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Igual estructura que niveles anteriores: respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N7)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Runs 500 m a ritmo con 1 zona intercalada (2.500 m semanales en sesiones); trenes de 2 zonas |
| 2 — Progresión | 80% | Runs 500 m con 2 zonas (4.000 m semanales); trenes de 3; ensayo DK4 en D |
| 3 — Intensificación | 95% | **Benchmark embebido: DEKA Fit en sesión C**; sesiones A/B/D a "afilar, no construir" |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 7

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido y las sesiones A/B/D pasan a "afilar, no construir".

### Sesión A — Runs 500 m a ritmo + zonas intercaladas (todas las fases)

- Tipo: carrera + zonas con fatiga. Duración estimada: 65-85 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (500 m a ritmo) | 4×500 m (2:30) | 5×500 m (ritmo negativo: los 2 últimos más rápidos) | 4×500 m (ritmo exacto, afilar) | 2×500 m suave | 90 s | El ADN del Fit: 10 runs a ~2:15-2:30; F1 aprende el ritmo, F2 entrena el ritmo negativo, F3 lo repite sin novedad |
| `Dumbbell Lunge` → `Farmers Walk` (tren con run) | 3×(500 m + 15/lado 2×16/2×10 kg) | 3×(500 m + 15/lado + 100 m 2×27/2×18 kg) | 2×(500 m + 15/lado) | 2×(500 m suave + 10/lado + 40 m) | 90 s | Zonas 1-6 con el 500 m delante: la zona se ejecuta con las piernas del run (§1/§3) |
| `Medicine Ball Slam` → `Burpee` (tren) | 3×(10 slam 27/18 kg + 10 burpee) | 3×(15 slam + 12 burpee) | 2×(10 + 10, mantener) | 2×(8 + 6 suave) | 90 s | Zonas 8-10: §4 en el slam; el burpee en bloques de 5 |

**Bloque `wod` (run→zona→run):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 500 m + `Dumbbell Lunge` ×15/lado (2×16/2×10 kg) + `Sprint` 500 m | 2 min entre rondas; zona 1 entre runs, como en la carrera |
| F2 | 3 rondas | `Sprint` 500 m + `Farmers Walk` 100 m (2×27/2×18 kg) + `Sprint` 500 m + `Assault Bike Sprint` 25 cal | 2 min entre rondas; zonas 6-7 con runs por medio; §3 |
| F3 | 2 rondas | `Sprint` 500 m a ritmo exacto + `Box Jump` ×15 | "Afilar, no construir": volumen mínimo, ritmo exacto |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Dumbbell Lunge` ×6/lado | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 500 m del Fit no se corren, se administran: 2:30 con la respiración controlada y la zona que viene después se ejecuta. El ritmo negativo se entrena hoy: últimos runs más rápidos que los primeros (estrategia, §1.7-DEKA §1)."

### Sesión B — Ergómetros umbrales largos (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 60-80 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` (umbral largo) | 3×750 m | 2×1.000 m | 2×750 m (mantener) | 1×500 m suave | 2-3 min | La zona 2 con volumen doble: lo que corre cómodo en el entrenamiento sale solo en la carrera |
| `SkiErg Sprint` (umbral largo) | 3×750 m | 2×1.000 m | 1×1.000 m (mantener) | 1×500 m suave | 2-3 min | La zona 5 llega con 2,5 km en las piernas en el Fit — aquí se construye esa sensación |
| `Assault Bike Sprint` | 3×40 cal | 3×50 cal | 2×40 cal (mantener) | 2×25 cal suave | 2-3 min | El bike del Fit se paga con las piernas del run: el 50 cal sentado de F2 construye ese motor |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 2×(750 m `SkiErg Sprint` a umbral + 10 `Burpee`) | SkiErg con salida inmediata de burpees | Ensayo de la zona 5-10 con fatiga de transición |
| F3 | 4 × (1' trabajo / 1' descanso) | `Assault Bike Sprint` a umbral | Afilar: ritmo exacto, sin novedad |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los ergómetros del Fit llegan con 3 km acumulados: entrena el umbral cansado de hoy. Si la remada se acorta y solo tiran los brazos, baja el ritmo — espalda de regalo no (§1.7-DEKA §5)."

### Sesión C — Circuito run→zona / DEKA Fit (todas las fases)

- Tipo: circuito run→zona (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 65-90 min (F1/F2/F4); 90-105 min (F3 con la DEKA Fit en fase de transición).
- Bloque 0: variante C (mínima fluida en F3). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Dumbbell Lunge` (2×16/2×10 kg) | 2×12/lado | 2×15/lado | 1×8/lado (calentamiento del simulacro) | 2×10 suave | 60 s | En F3 es calentamiento específico: toca las 10 zonas con dosis mínimas |
| `Burpee` (carga opcional) | 2×8 | 2×10 | — (el simulacro es el protagonista) | 2×6 | 60 s | La zona 10 se afila entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 500 m + `Dumbbell Lunge` ×15/lado + `Rowing Machine Sprint` 500 m + `Sprint` 500 m + `Box Jump` ×15 | 2 min entre rondas; zonas 1-3 con runs (2.500 m de carrera) |
| F2 | 3 rondas | `Sprint` 500 m + `Sit-up` ×20 (MB) + `SkiErg Sprint` 500 m + `Sprint` 500 m + `Farmers Walk` 10 m i/v ×5 + `Sprint` 500 m + `Assault Bike Sprint` 25 cal | 2 min entre rondas; zonas 4-7 con runs (4.000 m acumulados en la sesión) |
| F3 | **Benchmark embebido: DEKA Fit (transición)** | `DEKA Fit` (ficha del lote 2) en modalidad elegida: RX o scaled con runs 250-400 m | Stopwatch + cap 90:00; la marca alimenta el test; logro "Primera DEKA Fit completa". Plan: ritmo negativo, transiciones de 3-5 pasos, hidratación en los runs pares |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Box Jump` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "La Fit de transición se corre para terminar, no para brillar: 5 km y 10 zonas con la cabeza fría. Los runs 1-3 conservadores, los 8-10 de carrera; el agua se toma caminando 3 pasos, nunca parado (estrategia, §1.7-DEKA §2)."

### Sesión D — Aeróbico + movilidad + re-test DK4 (todas las fases)

- Tipo: aeróbico + movilidad + re-test. Duración estimada: 60-80 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques) | 25 min (3×500 m) | 30 min (4×500 m) | 20 min suave (afilar) | 15 min Z2 | — | El volumen aeróbico del nivel con los 500 m integrados; en F3 baja el volumen — el Fit está cerca |
| `Farmers Walk` (DK4, re-test camino) | 2×50 m (2×16 kg) | 2×75 m | 2×100 m (ensayo re-test) | 2×40 m | 90 s | La carga histórica de la puerta (2×16 kg); F3 ensaya los 100 m exactos del re-test |
| `Plank` | 3×60 s | 3×60 s | 2×60 s | 2×45 s | 60 s | Mantenimiento del tronco para los runs tardíos |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Afilar, no construir: el Fit está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los 100 m de farmers de F3 son el re-test DK4 entero con su carga histórica: 2×16 kg, sin soltar. La puerta no cambió desde N3 — lo que cambiaste tú (§1.7-DEKA §3)."

---

# NIVEL 8 — DEKA Fit

- **Título:** DEKA Fit — La carrera completa, cierre del programa
- **Objetivo:** ejecutar la DEKA Fit completa (10×500 m + 10 zonas RX) como cierre del programa (benchmark embebido), afilando el ritmo de carrera en las repeticiones de circuito, la estrategia de transición y la gestión del esfuerzo largo. La marca se compara con la de N7 (transición) como cierre narrativo del progreso. `restAfterBlock` sube a **90 s**: sesiones más densas en calidad y más cortas en volumen.
- **Requisitos de entrada:** mínimos del test N7 (DEKA Fit en transición + DK4 re-validada).
- **skillFocus (obligatorio):** `DEKA Fit completa a ritmo de competición: ritmo negativo, transiciones de 3-5 pasos y gestión del esfuerzo largo`
- **Puertas evaluadas:** ninguna. DK1-DK4 cerradas (re-validación final de DK4 en N7).

## Test de nivel N8 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | DEKA Fit | 48:00 (2880 s) | 40:00 (2400 s) | lte | seconds |

Notas del test:
- `type: benchmark` sobre `DEKA Fit` (placeholder `ID_FIT`, misma ficha que N7), modalidad RX (runs 500 m). Mínimo (48:00) bajo el cap de 90:00, en torno a beginner M (48:00); objetivo (40:00) = intermediate M (40:00). Regla F-Elizabeth.
- **Cierre narrativo:** la marca de N8 se compara con la de N7 (mismo benchmark, distinta fase): el progreso del programa se mide en minutos ahorrados en el mismo recorrido. El PFT no existe en DEKA — el Fit transición → Fit RX es el arco de comparación.
- Modo de evaluación: `strict_gates` — superar el benchmark cierra el programa (logro de hito "DEKA Mastery completada").

## Logros del nivel N8

**De test:**
- "Fit sub-40" — `type: test`, `tier: legendario`, `requirementType: test_item_min`, `requirementKey: DEKA Fit`.
- "Ritmo de competición" — `type: test`, `tier: legendario`, `requirementType: test_item_obj`, `requirementKey: DEKA Fit`, `requirementValue: 2400`.

**De hito:**
- "DEKA Mastery completada" — superación del test de N8 (cierre del programa). `tier: legendario`, `specialCelebration: true`.
- "Primera DEKA Fit RX completa" — primera ejecución completa del `DEKA Fit` en modalidad RX (runs 500 m), dentro del cap. `tier: epico`.
- "Repetición de carrera" — segunda ejecución completa del simulacro (circuitos F1/F2/F4 de la sesión C) dentro del cap en una misma fase. `tier: epico`.
- "Diez mil metros en carrera" — primera semana de entrenamiento con 10.000 m de runs acumulados (solo posible con las repeticiones de la sesión C). `tier: raro`.
- "Ritmo negativo ejecutado" — simulacro o circuito completo con los 3 últimos runs más rápidos que los 3 primeros. `tier: raro`.

## Bloques comunes del Nivel 8

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo completo del programa a dosis de mantenimiento alto; en el nivel de competición el Bloque 0 también es ritual de preparación: idéntico antes de cada simulacro para que el cuerpo lo reconozca como señal de carrera. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Tobillo/rodilla (DK1/DK2, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` | 35 s/lado + 35 s/lado + 15 | 40 s/lado + 40 s/lado + 15 | 45 s/lado + 45 s/lado + 15 | 30 s/lado + 30 s/lado + 10 suave |
| Cadera/zancada (DK1, mantenido) | `Deep Squat Hold` + `Cossack Squat` + `Glute Bridge` | 60 s + 10/lado + 15 | 60 s + 10/lado + 15 | 75 s + 10/lado + 15 | 60 s + 6/lado suave + 12 suave |
| Cadena posterior/agarre (DK4, mantenido) | `Kneeling Hip Flexor Stretch` + `Adductor Rockback` + `Glute Bridge` | 40 s/lado + 12 + 15 | 40 s/lado + 15 + 15 | 45 s/lado + 15 + 15 | 30 s/lado + 10 + 12 suave |
| Hombro/muñeca (slam/burpee) | `Shoulder Dislocate` + `Band Pull-Apart` + `Wrist Prayer` | 15 + 15 + 45 s | 20 + 15 + 45 s | 20 + 15 + 45 s | 12 + 12 + 30 s suave |
| Activación escápula | `Scapular Push Up` | 15 | 15 | 15 | 10 suave |

Variantes por sesión:
- **A (Run + zonas):** activación de agarre antes del farmers; énfasis en tobillo antes de los 500 m.
- **B (Ergómetros):** `Shoulder Dislocate` extra ×5 antes del SkiErg; respiración diafragmática ×5 ciclos.
- **C (Simulacro/circuito):** énfasis en muñeca y agarre; en F3 (DEKA Fit RX) el Bloque 0 se recorta al mínimo fluido y entra rápido al calentamiento específico — es el ritual de carrera.
- **D (Aeróbico):** énfasis en cadena posterior y cadera; la descarga se amplía.

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
| 1 — Base | 55% | Primera repetición RX del Fit a ritmo (circuitos de 5 zonas con 500 m); estrategia de carrera en cada WOD |
| 2 — Progresión | 80% | Circuitos completos de 10 zonas con runs; ritmo negativo cronometrado; transiciones de 3-5 pasos |
| 3 — Intensificación | 95% | **Benchmark embebido: DEKA Fit RX en sesión C**; sesiones A/B/D a "afilar, no construir" |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 8

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 90 s. En Fase 3, la sesión C sustituye su WOD por el benchmark embebido y las sesiones A/B/D pasan a "afilar, no construir".

### Sesión A — Intervals a ritmo de carrera con zona intercalada (todas las fases)

- Tipo: ritmo de carrera (no fuerza). Duración estimada: 60-80 min.
- Bloque 0: variante A. Postural: tabla sesión A (lumbar/core). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Sprint` (500 m a ritmo de carrera) | 5×500 m (2:20) | 6×500 m (ritmo negativo) | 4×500 m (ritmo exacto, afilar) | 2×500 m suave | 90 s | El ADN del Fit RX: 10 runs a ~2:15-2:20; F2 entrena el ritmo negativo; F3 repite el ritmo exacto sin novedad |
| `Dumbbell Lunge` (mantenimiento RX) | 3×15/lado (2×16/2×10 kg) | 3×20/lado | 2×15/lado (mantener) | 2×10/lado suave | 90 s | Volumen bajo, técnica exacta: bloques de 10 en carrera (§1) |
| `Farmers Walk` (split de carrera) | 3×100 m (2×27/2×18 kg, cronometrado) | 3×100 m (sub-2:30) | 2×100 m (ritmo exacto) | 2×50 m | 90 s | La zona 6 con split: pasos cortos, agarre firme (§3) |

**Bloque `wod` (run→zona→run a ritmo):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | `Sprint` 500 m + `Dumbbell Lunge` ×15/lado (2×16/2×10 kg) + `Sprint` 500 m | 2 min entre rondas; la zona 1 entre runs, como en la carrera |
| F2 | 3 rondas | `Sprint` 500 m + `Assault Bike Sprint` 25 cal + `Sprint` 500 m (más rápido que el primero) | Ritmo negativo explícito: el segundo run se corre más rápido |
| F3 | 2 rondas | `Sprint` 500 m a ritmo exacto + `Farmers Walk` 100 m (2×27/2×18 kg, split) | "Afilar, no construir": el par exacto del km 6, una vez bien hecho |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Dumbbell Lunge` ×6/lado | Movimiento; test al final del nivel |

**Tips de sesión:** "En N8 no se entrena fuerza, se entrena carrera: cada 500 m de hoy tiene un ritmo y cada ritmo se anota. El atleta que llega al Fit con diez tiempos escritos en la cabeza no corre la carrera, la ejecuta (estrategia, §1.7-DEKA §1)."

### Sesión B — Ergómetros a ritmo objetivo (todas las fases)

- Tipo: ergómetros + core. Duración estimada: 55-75 min.
- Bloque 0: variante B. Postural: tabla sesión B (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Rowing Machine Sprint` (split de carrera) | 3×500 m (sub-2:00) | 3×500 m (sub-1:55) | 2×500 m (ritmo exacto) | 1×500 m suave | 90 s | La zona 2 con split afinado: 10-15 s por debajo de la transición |
| `SkiErg Sprint` (split de carrera) | 3×500 m (sub-2:05) | 3×500 m (sub-2:00) | 1×500 m (ritmo exacto) | 1×500 m suave | 90 s | La zona 5 llega con 2,5 km en las piernas: se afila cansado |
| `Assault Bike Sprint` | 3×50 cal | 3×50 cal (sub-2:30) | 2×40 cal (mantener) | 2×25 cal suave | 90 s | El bike del Fit se paga con las piernas del run: sentado y continuo |

**Bloque `wod` (ergómetro + core):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Push Up` + 30 s `Hollow Body Hold` | 60-90 s entre rondas |
| F2 | 2×(500 m `Rowing Machine Sprint` a split + 10 `Burpee`) + 500 m `SkiErg Sprint` a split | Row-burpee ×2 y SkiErg solo | Ensayo de zonas 2-5-10 con fatiga de transición |
| F3 | 4 × (1' trabajo / 1' descanso) | `Assault Bike Sprint` a umbral | Afilar: volumen mínimo, ritmo exacto |
| F4 | 2 rondas suaves | 8 `Sit-up` + 30 s `Plank` | Movimiento; test al final del nivel |

**Tips de sesión:** "El remo del Fit se gana por diez segundos: 1:55 hoy, 1:45 el día de la carrera. Remada larga, tronco quieto, cierre fuerte — los últimos 200 m se reman con la cabeza (§1.7-DEKA §5)."

### Sesión C — Repeticiones de simulacro / DEKA Fit (todas las fases)

- Tipo: circuito de carrera completa o parcial (F1, F2, F4) o **benchmark embebido** (F3). Duración estimada: 70-90 min (F1/F2/F4); **100-120 min (F3 con la DEKA Fit RX — la sesión más larga del programa)**.
- Bloque 0: variante C (mínima fluida en F3 — ritual de carrera). Postural: tabla sesión C (tobillo/core). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Dumbbell Lunge` (2×16/2×10 kg) | 2×15/lado | 2×20/lado | 1×10/lado (calentamiento del simulacro) | 2×10 suave | 60 s | En F3 es calentamiento específico: toca las 10 zonas con dosis mínimas |
| `Box Jump` (caja 24") | 2×10 | 2×15 | — (el simulacro es el protagonista) | 2×8 suave | 60 s | La zona 3 se afila entre simulacros |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | "Primera mitad": 2×(run 500 m + zona ×5) | `Sprint` 500 m + `Dumbbell Lunge` ×15/lado + `Sprint` 500 m + `Rowing Machine Sprint` 500 m + `Sprint` 500 m + `Box Jump` ×20 + `Sprint` 500 m + `Sit-up` ×25 (MB) + `Sprint` 500 m + `SkiErg Sprint` 500 m, y se repite el bloque | 3 min entre bloques; zonas 1-5 con runs; primera repetición del formato |
| F2 | "Segunda mitad": 2×(run 500 m + zona ×5) | `Sprint` 500 m + `Farmers Walk` 100 m + `Sprint` 500 m + `Assault Bike Sprint` 25 cal + `Sprint` 500 m + `Medicine Ball Slam` ×20 + `Sprint` 500 m + `Sled Push` 10 m + `Sled Pull` 10 m + `Sprint` 500 m + `Burpee` ×20 | 3 min entre bloques; zonas 6-10 con runs; logro "Diez mil metros en carrera" si se suman ambas mitades de la fase |
| F3 | **Benchmark embebido: DEKA Fit (cierre)** | `DEKA Fit` en modalidad RX: 10×(run 500 m + zona del array ZONAS RX en orden) | Stopwatch + cap 90:00; la marca alimenta el test y cierra el programa. Plan: ritmo negativo escrito, transiciones de 3-5 pasos, series planificadas (lunge 10+10+10, burpee 4×5, box 4×5), hidratación en los runs pares |
| F4 | 2 rondas suaves | `Sprint` 300 m + `Box Jump` ×6 | Movimiento; test al final del nivel |

**Tips de sesión:** "La Fit es la carrera que entrenaste ocho niveles: 5 km y 10 zonas con todo lo aprendido — zancadas con rodilla al suelo, caja sin tropiezo, farmers con agarre muerto, slams de cadera. Corre los primeros 2 km como si sobrara tiempo, porque sobra (estrategia de ritmo negativo, §1.7-DEKA §1-§4)."

### Sesión D — Aeróbico + movilidad + cierre (todas las fases)

- Tipo: aeróbico + movilidad. Duración estimada: 55-75 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Sprint` (run Z2 con bloques de carrera) | 25 min (4×500 m) | 30 min (5×500 m, ritmo negativo) | 20 min suave (afilar) | 15 min Z2 | — | Mantenimiento aeróbico del nivel más largo del programa; en F3 baja el volumen — el Fit RX está cerca |
| `Farmers Walk` (mantenimiento) | 2×100 m (2×27/2×18 kg) | 2×100 m (sub-2:30) | 2×50 m | 2×30 m | 90 s | Mantiene la zona 6 con split, sin fatiga nueva |
| `Broad Jump Burpee` (técnica) | 2×5 | 3×5 | 2×5 | 2×3 suave | 60 s | Variante explosiva opcional para la zona 10; aterrizaje controlado |

**Bloque `wod` (aeróbico con estación ligera):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 10 `Step Up` + 10 `Kettlebell Swing Russian` (16/12 kg) + 10 `Sit-up` | 60-90 s entre rondas |
| F2 | 4 rondas | 10 `Dumbbell Lunge` (ligero) + 10 `Mountain Climber` + 30 s `Hollow Body Hold` | 60 s entre rondas |
| F3 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Afilar, no construir: el Fit RX está cerca |
| F4 | 2 rondas suaves | 8 `Step Up` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "La sesión D del último nivel es la más tranquila del programa: 30 minutos suaves y movilidad. El cuerpo que llega descansado al Fit es el que corre la segunda mitad como si empezara (§1.7-DEKA §5)."

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N7-N8: todos los ejercicios citados existen en el catálogo con la grafía usada (verificados contra BD dev en los lotes 1-2, 17-oct-2026; este lote no introduce ejercicios nuevos).
- `[SIN FICHA]` heredados de los lotes anteriores (menores, no bloquean): sin ficha de movilidad dinámica de tobillo tipo "ankle rock / knee-to-wall" (cubierto con `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Calf Raise on Step` + `Step Up` controlado).
- Mapeos con nota (spec §3, "0 fichas nuevas"): iguales que los lotes 1-2 — RAM lunge → `Dumbbell Lunge`/`Kettlebell Goblet Lunge` pesadas + nota "simula RAM 25/15 kg en back rack"; dead ball → `Medicine Ball Slam` pesada + nota (en Fit: yoke over); RAM burpee → `Burpee` + carga en nota; sled magnético → `Sled Push`/`Sled Pull` de peso + nota; MB sit-ups en Fit → throws a diana (nota).
- Duplicados conocidos (heredados): `Farmers Walk` (340) / `Farmer Walk` (280), `Face Pull` (482) / `Face pull` (756), `Pallof Press` (314) / `Pallof press` (782). Este documento usa siempre la grafía principal.
- Con este lote el programa queda completo: 8 niveles × 16 sesiones = 128 trainings, 5 fichas de benchmark (DEKA Media, Strong, Mile, Fit) y las 4 puertas DK1-DK4 con calendario de evaluación (N2: DK1-DK3, N3: DK4, N4: re-test DK4, N6: re-tests DK1-DK3, N7: re-test DK4) y ~55 logros.

## Decisiones de contenido tomadas (para revisión del controller)

1. **DEKA Fit como benchmark compartido entre N7 y N8** (una sola ficha `ID_FIT`, dos niveles de test con mínimos/objetivos distintos: N7 transición 60:00/48:00 con runs scaled opcionales, N8 cierre 48:00/40:00 RX) — análogo a L7/L8 de Hyrox pero **sin duplicar ficha**. **Antes de transcribir, verificar que la plataforma tolera el mismo `benchmarkId` en dos niveles distintos.** Si hay riesgo, la alternativa propuesta: crear ficha duplicada "DEKA Fit (transición)" (cap 5400, mismas referencias, solo para N7). Decisión preferida: mantener una sola ficha y verificar la plataforma.
2. **El mínimo de N7 (60:00) se fija deliberadamente por encima de beginner F (55:00)**: la transición permite runs scaled de 250-400 m, así que el mínimo generoso no castiga al atleta que aún construye el volumen de 5 km. El mínimo de N8 (48:00) = beginner M RX: el cierre exige la carrera completa.
3. **Cierre narrativo sin PFT:** DEKA no tiene un "test corto" como el PFT de Hyrox; el arco de comparación del programa es Fit transición (N7) → Fit RX (N8), más las marcas intermedias (Media, Strong, Mile). El logro de cierre "DEKA Mastery completada" (`tier: legendario`, `specialCelebration: true`) se vincula a la superación del test de N8.
4. **Calendario de re-tests completado:** DK4 re-test en N7 con carga histórica (2×16 kg) es el último re-test del programa; N8 queda sin puertas. Coherente con spec §4.2 ("re-tests N6/N8") con la salvedad documentada en el lote 1: la evaluación de DK4 se adelantó a N3 y sus re-tests cayeron en N4 y N7.
5. **`restAfterBlock` 60 s en N7 y 90 s en N8** (regla del proyecto: ≤6 → 60 s, resto → 90 s), coherente con la convención de los lotes anteriores.
6. **Interferencia "afilar, no construir" en F3 de N7/N8:** las sesiones A/B/D de la fase del simulacro repiten ritmos exactos con volumen mínimo y nada de carga nueva.
7. **Volumen de carrera del lote:** N7 progresa de 2.500 m (F1) a 4.000 m (F2) semanales en las sesiones clave, con el test de F3 como toma de contacto larga; N8 mantiene 55-65 min con intensidad (ritmo negativo cronometrado) en vez de volumen.
8. **Duración estimada de la sesión C de N8 F3: 100-120 min** — la sesión más larga del programa (DEKA Fit RX con cap 90:00 + bloques); consistente con la convención de revisar las estimaciones tras generar (`TrainingTimeCalculator` estima 60% del cap como mínimo).
9. **Estrategia de carrera explícita en tips:** ritmo negativo en los 500 m (últimos 3 runs más rápidos que los primeros 3), transición run→zona en 3-5 pasos, series planificadas (lunge 10+10+10, burpee 4×5, box 4×5), gestión del bike y el slam bajo fatiga, hidratación en los runs pares.
