# Detalle de contenido — Niveles 10, 11 y 12 — CrossFit Master v1

> Cuarto lote del documento fuente del programa CrossFit Master (comando
> `app:create-crossfit-master`). Continuidad directa de
> `docs/detalle_crossfit_niveles_07-09.md` (mismas convenciones, mismas
> puertas CF1-CF7 ya evaluadas). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-10-crossfit-master-design.md` (§4 arco —
> tramo "Avanzado", §5 bloques, §7 tests con benchmarks, §8 puertas CF, §9
> logros, §10 regla §1.7-CrossFit).
>
> **Estado de redacción:** COMPLETO. Niveles 10, 11 y 12 con sus 16 sesiones
> cada uno (4 fases × A/B/C/D), tests con benchmarks RX (Elizabeth, DT, Nate),
> skillFocus y logros. Sin puertas formales en test: CF1-CF7 ya están todas
> superadas (N2-N6); se mantienen en Bloque 0 (acumulativo, §1.8) a dosis de
> mantenimiento, con re-tests informales (decisión 1). Pendiente la validación
> de contenido con el usuario (gate humano).

**Convenciones del documento** (heredadas de los lotes 01-03, 04-06 y 07-09, sin cambios):

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (§5): A — Fuerza (básico de barra + fuerza + prehab hombro); B — Skill/técnica (gimnástico u O-lift + prehab muñeca); C — Metcon/WOD (WOD de la sesión + prehab lumbar/core); D — Mixta (combinación suave + movilidad + prehab de trío completo).
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab muñeca/hombro/lumbar rotado por sesión: A hombro, B muñeca, C lumbar, D trío suave) → `descarga`. `restAfterBlock` = 60 s en N10-N12 (decisión 2: se mantiene 60 s por indicación del proyecto, mismo criterio que N1-N9).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas. Singles/series máximas → reps `1`. Dosis `/lado` se computan una vez.
- **RIR:** en N10-N12 se trabaja con 1-2 RIR en fuerza pesada (squat clean, squat snatch, C&J, back squat); nunca al fallo. En WODs, el fallo técnico marca el escalado (regla §1.7-CrossFit §10.4). En O-lifts bajo metcon solo carga técnica moderada — nunca pesado en fatiga (§10.2).
- **Equipamiento asumido N10-N12:** todo lo de N1-N9 + rack de squat + anillas + barras con rodamiento. Sin plataformas/paras necesarias (los O-lifts se reciben en power o squat según nivel).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta 2026-10-10, `SELECT name FROM exercises ORDER BY name`). Nombres literales.
- **Convención de nombres de ítems de test:** todo ítem que corresponda a un ejercicio de catálogo usa su grafía exacta, para que el test, los logros (`requirementKey`) y las referencias casen exacto. Los benchmarks se nombran como nombre propio ("DT (benchmark RX)").
- **Duraciones estimadas:** salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.

---

## Puertas de movilidad del programa (estado al entrar en N10 — copia textual del lote 01-03)

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| CF1 | Sentadilla profunda pegada | squat, wall ball, cleans | `Deep Squat Hold` 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor | N4 (squat cargado progresivo) | **N3** ✔ |
| CF2 | Overhead (hombro) | press, S2OH, wall ball target, snatch, OHS, HSPU | `Shoulder Dislocate` con banda/PVC ×15 sin compensar la lumbar; `Overhead Squat` con PVC/barra vacía ×10 en profundidad | N5 (S2OH, wall ball a diana) | **N4** ✔ |
| CF3 | Muñeca en front rack | clean, front squat | Front rack con barra vacía 30 s/lado sin dolor ni desviación activa | N5 (cleans) | **N4** ✔ |
| CF4 | Dorsiflexión de tobillo | lunges, wall ball | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón | N4 (lunges, wall ball) | **N3** ✔ |
| CF5 | Cadera/bisagra | deadlift, swings, lunges | Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada | N3 (deadlift con carga, KB swing progresivo) | **N2** ✔ |
| CF6 | Muñeca en extensión | HSPU, handstand | `Wrist Push-Up` 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10 | N7 (HSPU, handstand) | **N6** ✔ |
| CF7 | Bisagra lumbar controlada | deadlifts, O-lifts | `Dead Bug` 2×10/lado + `Bird Dog` 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra | N7 (O-lifts, deadlifts en fatiga) | **N6** ✔ |

Evaluación en este documento: **ninguna puerta formal.** CF1-CF7 están todas superadas al entrar en N10 (la última, CF6/CF7, en el test N6, con re-tests informales superados en N7 y N9). Se mantienen en Bloque 0 de forma acumulativa (§1.8) a dosis de mantenimiento, con dos re-tests **informales** (ensayo a dosis máximas en Bloque 0, sin ítems `puerta_movilidad` en el test ni estado `mobility_blocked`): CF3 (front rack reforzado, antes del squat clean de 60/43) y CF6 (muñeca en extensión, antes de la progresión de HSPU kipping) en N10; CF2/CF6 en N11 (antes del snatch de suelo y el overhead pesado); CF6/CF7 en N12 (antes de los gimnásticos invertidos bajo fatiga del test). Decisión 1.

---

## Regla §1.7-CrossFit en tips (spec §10) — aplicación en N10-N12

1. **Primero estricto, luego dinámico:** la base estricta está construida (N7-N9: kipping pull-up/C2B con volumen, HSPU estrictos con tope). Lo nuevo que se introduce la respeta: el **kipping HSPU** llega en N10 solo tras tres niveles de HSPU estricto/negativo, con tope de volumen de 30-40 reps/sesión; la **primera muscle-up en anillas** se estrena estricta (transición completa controlada) antes de permitir el kipping dentro de los WODs en N12.
2. **Lumbar:** el deadlift pesado quedó en 100/70 kg (test N9); en este tramo el pesado pasa a los O-lifts recibidos en squat (squat clean 60/43, squat snatch 55/40) y el DT RX (deadlift 70/52 kg en metcon — carga técnica moderada en fatiga, con CF7 vigente). Nunca O-lift pesado en metcon: en WODs entran a carga de trabajo o `Power Snatch`/`Hang Power Clean` ligeros (§10.2).
3. **Muñeca:** el volumen de HSPU (estricto + kipping) y el snatch de suero heredan CF6; dolor matinal >2/10 en extensión congela HSPU y snatch un ciclo (§10.3). El false grip del trabajo de anillas vigila los flexores: dolor >2/10 congela la progresión a muscle-up un ciclo.
4. **Técnica rota antes que músculo:** en Elizabeth, DT y Nate, si la recepción del clean/snatch se pierde, la transición del muscle-up se convierte en pez o el HSPU pierde el hollow, la rep/marca RX no cuenta: se escala y se anota (§10.4).
5. **Descanso entre exposiciones pesadas de bisagra y hombro:** dejar pasar al menos una sesión completa entre exposiciones exigentes (relevante en N11 con squat snatch + back squat, y en N12 con MU + HSPU kipping bajo fatiga).

---

# NIVEL 10 — Primera Muscle-Up

- **Título:** Primera Muscle-Up — Anillas, Squat Clean y primer Kipping HSPU
- **Objetivo:** el hito gimnástico central del programa: la **primera muscle-up en anillas** (`Ring Muscle Up`), construida desde la base de N9 (false grip, transiciones asistidas, negativas, ring dips) con la secuencia transición asistida → negativa controlada → primera estricta → (solo entonces) kipping en volumen; el **`Squat Clean` RX (60/43 kg)** como primer O-lift recibido en sentadilla profunda (requiere CF1+CF3+CF7 vigentes); el **kipping HSPU** introducido tras la base estricta de N8-N9, con tope de volumen; y el primer benchmark con barra recibida en squat: **Elizabeth RX** (squat cleans 60/43 + ring dips). Al superar N10, el usuario domina el gesto más codiciado del gimnástico CrossFit.
- **Requisitos de entrada:** mínimos del test N9 (incl. C2B ×3, anillas asistidas y CF6/CF7 re-chequeadas).
- **skillFocus:** `Primera muscle-up en anillas, Squat Clean RX y primer Kipping HSPU`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF3:** ensayo de front rack con barra vacía 30 s/lado + `Overhead Squat` PVC ×10 en Bloque 0 de F3 (al entrar el squat clean de 60/43). **Re-test informal CF6:** `Wrist Push-Up` 2×10 + apoyo en extensión 60 s en Bloque 0 de F2 (antes del primer bloque de kipping HSPU). Informativos: si fallan, se baja la carga de trabajo y se repite el ensayo; no generan `mobility_blocked`.

## Bloques comunes del Nivel 10

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N9 (§1.8) a dosis de mantenimiento, con foco nuevo: movilidad de muñeca en false grip y apertura dorsal para la transición del muscle-up. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica (foco nuevo: transición MU) | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 10 + 10/lado + 10 | 12 + 10/lado + 10 | 12 + 10/lado + 12 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` | 15 + 15 | 15 + 15 | 15 + 15 | 12 + 12 suave |
| Muñeca (CF6, re-test informal F2) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×10 + 60 s + 60 s | 2×8 suave + 30 s + 45 s |
| Front rack (CF3, re-test informal F3) | front rack con barra vacía + `Deep Squat Hold` con apoyo | 30 s/lado + 60 s | 30 s/lado + 60 s | 30 s/lado + 60 s (ensayo CF3) | 20 s/lado + 45 s suave |
| Anillas (foco nuevo: transición) | `False Grip Hang` + `Ring Support Hold` + rotación de muñecas sobre las anillas | 3×15 s + 3×15 s + 5 | 3×20 s + 3×20 s + 8 | 3×20 s + 3×20 s + 10 | 2×15 s + 2×15 s + 5 suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 45 s + 2×8 | 45 s + 2×10 | 60 s + 2×10 | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en sentadilla profunda y front rack antes del squat clean; `Deep Squat Hold` justo antes del `Back Squat`.
- **B (Skill):** `False Grip Hang` extra ×10 s y rotación de muñecas extra ×5 antes del trabajo de anillas; `Wrist Push-Up` extra ×5 antes del HSPU (CF6 en caliente).
- **C (Metcon):** énfasis en front rack y hombro (barra del WOD); comba de calentamiento.
- **D (Mixta):** énfasis en torácica y muñeca; el false grip suave cierra el Bloque 0.

### Bloque postural (prehab — selección N10)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. El prehab de hombro/muñeca es condición del muscle-up y del HSPU: la transición en anillas y el kipping cargan ambas estructuras en cada rep.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática tumbado (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones con anillas/HSPU, añadir `Lat Stretch` y extensión suave de muñeca/flexores). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N10)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Squat clean técnico con 50/35 kg (desde `Power Clean` + `Front Squat` combinados); anillas: `Assisted Muscle Up Transition` y `Muscle Up Negative` a dosis altas; HSPU estricto con tope (sin kipping); primer `Hang Power Clean` de 65/45 kg en fuerza |
| 2 — Progresión | 80% | Squat clean 55/40 kg; `Muscle Up Negative` 5 s + primeros intentos de `Ring Muscle Up` (estricta, pies asistidos si hace falta); re-test informal CF6 antes del primer kipping HSPU; `Kipping Handstand Push Up` con tope 20/sesión; hang power clean 70/50 kg |
| 3 — Intensificación | 95% | Squat clean 60/43 kg ensayando el test y la carga de Elizabeth; re-test informal CF3; **primera `Ring Muscle Up` completa como hito de sesión B**; kipping HSPU con tope 30/sesión; **Elizabeth RX como hito de sesión C** |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (anillas y muñeca a dosis F4); test de nivel al final |

Dosificación: tope de volumen invertido (HSPU + MU) de 40-50 reps/sesión en F2-F3; una sesión completa entre exposiciones intensas de hombro. Regla §10.1: dolor matinal de hombro/muñeca/flexores >2/10 → vuelta a estricto (HSPU) y transición asistida (MU) un ciclo.

## Sesiones del Nivel 10

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Fuerza: squat, squat clean y bisagra (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (80/60 kg) | 4×5 (85/65 kg) | 5×3 (90/70 kg) | 2×5 suave (70/50 kg) | 150 s | Base del squat clean; pausa de 1 s abajo en F2-F3; CF1 en cada rep |
| `Squat Clean` | 5×2 (50/35 kg) | 5×2 (55/40 kg) | 6×1-2 (60/43 kg, ensayo test) | 3×2 suave (45/30 kg) | 120 s | Primer O-lift recibido en sentadilla profunda: tirón + recepción en `Front Squat` (CF1+CF3+CF7); si la recepción se corta, cuenta como `Power Clean` y se repite (§10.4) |
| `Hang Power Clean` | 4×3 (65/45 kg) | 4×3 (70/50 kg) | 4×2 (70/52 kg) | 2×3 suave (50/35 kg) | 120 s | El segundo y tercer movimiento del futuro DT; técnica de hang antes que kilos |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 10' | 6 `Power Clean` (55/40 kg) + 8 `Front Squat` (55/40 kg, de la parada) + 8 `Box Jump` | El squat clean descompuesto en metcon; ritmo conversacional |
| F2 | 4 rondas | 5 `Hang Power Clean` (65/45 kg) + 6 `Push Jerk` (55/40 kg) + 10 `Air Squat` | 60-90 s entre rondas; la mitad de un round de DT con carga de trabajo |
| F3 | AMRAP 14' | 9 `Deadlift` (70/50 kg) + 6 `Hang Power Clean` (70/52 kg) + 3 `Push Jerk` (60/43 kg) | Primer "DT parcial" (12-9-6 escalado a 9-6-3); CF7 en vivo en el deadlift |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El squat clean no es un power clean con prisa: es un tirón, una recepción y UNA sentadilla, todo en el mismo latido. Si separas las partes en la F1, la F3 las unirá sola. Y recuerda: la recepción que se corta es power clean, no squat clean (§1.7-CrossFit §10.4)."

### Sesión B — Skill: anillas (camino a la MU) y HSPU (todas las fases)

- Tipo: técnica, series seguidas, anillas primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF6 en caliente en F2). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Assisted Muscle Up Transition` | 4×3 | 4×4 | 3×3 (solo refuerzo) | 2×2 suave | 120 s | La transición como gesto aislado, miles de veces: pie asistido, pecho sobre las anillas, rotación de muñecas; si el false grip duele >2/10, se congela un ciclo (§10.3) |
| `Muscle Up Negative` | 3×2 (bajada 3-5 s) | 4×2 (bajada 5 s) | 3×2 (bajada 5-8 s) | 2×1 suave | 120 s | Bajar de soporte alto a colgado por la transición, lento; es el otro 50% de la primera MU |
| `Ring Muscle Up` (o `Strict HSPU` en F1) | — (estricto: `Strict HSPU` 3×3) | 5×1 intentos (asistencia mínima) | 6×1 (primera completa como hito, luego repeticiones) | 2×1 suave | 180 s | F1 solo HSPU estricto; F2 primeros intentos de MU con asistencia mínima; F3 la primera `Ring Muscle Up` completa — estricta antes que kipping (§10.1); descanso completo entre intentos |
| `Kipping Handstand Push Up` | — (no aún) | 4×3 (tope 20) | 5×4 (tope 30) | 2×3 suave | 90 s | Se introduce en F2 SOLO tras la base estricta de N8-N9 y el ensayo CF6; kipping mínimo y controlado; si el hollow se rompe, vuelve a `Strict HSPU` |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | EMOM 12' | Impares: 4 `Chest to Bar Pull Up`; pares: 6 `Ring Dip` + 8 `Air Squat` | Tracción + empuje en anillas: los pilares de la MU bajo reloj suave |
| F2 | EMOM 14' | Impares: 2 `Assisted Muscle Up Transition` + 4 `Kipping Pull Up`; pares: 5 `Wall Handstand Push Up` + 8 `Double Unders` | Descansa el tiempo que sobre; transición fresca antes que C2B |
| F3 | EMOM 16' | Impares: 1 `Ring Muscle Up` (o 2 transiciones asistidas si aún no) + 3 `Kipping Pull Up`; pares: 4 `Kipping Handstand Push Up` + 10 `Double Unders` | Primer EMOM con MU oficial; si la transición se rompe, baja a asistida (§10.4) |
| F4 | 2 rondas suaves | 3 `Ring Row - Elevated Feet` + 5 `Push Up` | Técnica relajada |

**Tips de sesión:** "La muscle-up no se fuerza: se ensaya hasta que sale sola. Transición asistida, negativa lenta, un intento fresco con descanso completo — ese es el camino. El día que salga, nadie en el box lo sabrá antes que tú (§1.7-CrossFit §10.1)."

### Sesión C — Metcon: piezas de Elizabeth y Elizabeth RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 55-65 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Squat Clean` (caliente) | 2×3 (45/30 kg) | 2×2 (55/40 kg) | 2×2 (60/43 kg) | 2×3 suave (40/25 kg) | 60 s | Progresión hasta la carga del WOD; recepción completa en squat |
| `Ring Dip` (caliente) | 2×5 | 2×6 | 2×6 | 2×4 | 45 s | El empuje de Elizabeth; rango honesto, hombros bajo los anillas |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 6 `Squat Clean` (50/35 kg) + 8 `Ring Dip` (o `Bench Dips` elevadas si aún no) + 10 `Single Unders` | El sabor de Elizabeth con carga de técnica; ritmo alto |
| F2 | 21-15-9 | `Squat Clean` (55/40 kg) + `Ring Dip` | Ensayo del formato Elizabeth antes de cargar RX; anota tiempo y sensaciones |
| F3 | Benchmark: **Elizabeth RX (hito)** | `Elizabeth` en modalidad RX (id 8, FOR_TIME, timeCap 900 s: 21-15-9 de `Squat Clean` 60/43 kg + `Ring Dip`) | **Primera ejecución RX del benchmark de potencia con barra y anillas: logro "Elizabeth RX registrada".** Si la recepción se corta o el ring dip muere, escala y deja constancia (§10.4) |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Elizabeth castiga dos cosas a la vez: la recepción del clean y el empuje en anillas. Divide los ring dips desde el principio (7-7-7 / 5-5-5 / 4-3-2) y deja que el squat clean respire. Los que queman los ring dips en la ronda de 21 pagan la de 9 el triple."

### Sesión D — Mixta: HSPU kipping, OHS y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D (false grip como cierre). Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Strict HSPU` (o `Wall Handstand Push Up`) | 4×3 | 4×4 | 5×3 (tope estricto) | 2×3 suave | 90 s | La base estricta se mantiene aunque entre el kipping: es el seguro de vida del HSPU (§10.1) |
| `Kipping Handstand Push Up` | — (no aún) | 4×3 (tope 15) | 4×5 (tope 25) | 2×3 suave | 90 s | Segundo slot de kipping del ciclo; tope compartido con sesión B |
| `Overhead Squat` | 3×5 (40/30 kg) | 3×3 (45/32 kg) | 4×3 (50/35 kg) | 3×3 suave | 120 s | Overhead de mantenimiento; sostiene el snatch del N11 |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 6 `Dumbbell Thruster` (moderado) + 6 `Strict HSPU` (o pike) + 10 `Single Unders` | 60-90 s entre rondas |
| F2 | 4 rondas | 5 `Kipping Handstand Push Up` + 8 `Wall Ball` (9/6 kg) + 12 `Double Unders` | 60 s entre rondas; primer metcon con kipping HSPU |
| F3 | AMRAP 15' | 6 `Kipping Handstand Push Up` + 9 `Kettlebell Swing Russian` (24/16 kg) + 15 `Double Unders` | El overhead bajo fatiga del test; tope de kipping 30 en el WOD |
| F4 | 2 rondas suaves | 5 `Push Up` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El kipping HSPU es un préstamo que se paga con intereses: hoy suma reps, mañana cobra hombros. Por eso el estricto sigue en el programa aunque el kipping entre — es lo que permite que esto sea sostenible (§1.7-CrossFit §10.1)."

## Test de nivel N10 (resumen)

Ítems auto-evaluados al final de la Fase 4 (Recuperación), con 2-3 min de descanso entre pruebas, de menor a mayor interferencia.

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Ring Muscle Up | 1 rep | 3 reps | gte | reps | fuerza |
| 2 | Squat Clean (60/43 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 3 | Strict HSPU | 3 reps | 5 reps | gte | reps | fuerza |
| 4 | Elizabeth (benchmark RX) | 19:00 (1140 s) | 15:00 (900 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Elizabeth` (id 8, FOR_TIME, timeCap 900 s: 21-15-9 de `Squat Clean` 60/43 kg + `Ring Dip`, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`).
- Contraste con referencias de catálogo (consulta 2026-10-10): Elizabeth RX beginner = 15:00 M / 19:00 F; intermediate = 10:00 M / 13:00 F. El mínimo se fija en beginner RX F (19:00) y el objetivo en beginner RX M (15:00 — coincidente con el timeCap 900 s del benchmark, que queda como techo duro de registro; mismo tratamiento que Fran en N7). El intermediate (10:00/13:00) queda fuera: exigir ring dips y squat cleans a ritmo intermediate el mismo día que la primera muscle-up sería injusto (§1.6, mínimos conservadores).
- Ítem 1: criterio de validez = transición completa (pecho sobre las anillas, rotación de muñecas) con bloqueo en soporte; saltar la transición ("pez" sin rotación) no cuenta (§10.4). Se acepta la primera MU estricta o kipping controlada — la estricta es preferida pero el hito de test es la MU válida.
- Ítem 2: recepción en sentadilla profunda (CF1+CF3); recepción cortada = `Power Clean`, no cuenta (§10.4).
- Ítem 3: rango mínimo validado (cabeza al suelo/colchoneta del nivel de trabajo N8-N9); sin kipping en este ítem: es el ancla estricta (§10.1).
- Los re-tests informales CF3 (F3) y CF6 (F2) ya se hicieron en Bloque 0; no son ítems del test ni generan `mobility_blocked` (decisión 1).

## Logros del nivel N10

**De test:**
- "Primera muscle-up" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Ring Muscle Up`. El hito central del nivel y del gimnástico del programa: la primera `Ring Muscle Up` completa.
- "Squat clean de 60/43 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Squat Clean (60/43 kg)`.
- "HSPU estricto de test" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Strict HSPU`.
- "Elizabeth RX registrada" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Elizabeth (benchmark RX)`.

**De hito:**
- "Primera Ring Muscle Up (fuera de test)" — primer `Ring Muscle Up` completo en sesión (hito de sesión B F3). `tier: legendario`, `specialCelebration: true`.
- "Primera muscle-up estricta" — primer `Ring Muscle Up` sin kipping, con transición controlada de principio a fin. `tier: legendario`.
- "Primer kipping HSPU" — primer `Kipping Handstand Push Up` con rango mínimo y hollow controlado, tras la base estricta. `tier: epico`.
- "Primera Elizabeth RX" — primera ejecución completa de `Elizabeth` en RX (sesión C F3). `tier: epico`.
- "Ring dip de 10" — primer set de 10 `Ring Dip` sin romper el rango. `tier: raro`.
- "Transición asistida de 5 segundos" — primer `Assisted Muscle Up Transition` con 5 s de pausa con el pecho sobre las anillas. `tier: comun`.
- "Negativa de muscle-up de 8 segundos" — primera `Muscle Up Negative` con bajada de 8 s controlada. `tier: raro`.

---

# NIVEL 11 — Pesado

- **Título:** Pesado — Snatch de suelo, Clean & Jerk y DT RX
- **Objetivo:** el nivel de la barra pesada: el **snatch completo de suelo** (`Squat Snatch` 55/40 kg de trabajo, técnica consolidada desde N8-N10: tall → hang → power → squat), el **`Clean and Jerk` RX (70/47.5 kg)** como unidad completa de competición, el **`Back Squat` pesado (100/70 kg)** como base de fuerza, y el primer Hero con barra pesada como benchmark: **DT RX** (5 rondas de deadlift + hang power clean + push jerk a 70/52 kg). Al superar N11, el usuario levanta de suelo con técnica RX y sostiene una barra de Hero completa.
- **Requisitos de entrada:** mínimos del test N10 (incl. primera MU y squat clean 60/43).
- **skillFocus:** `Snatch de suelo, Clean & Jerk RX, squat fuerte y DT RX`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF2/CF6:** ensayo de `Shoulder Dislocate` ×15 + `Overhead Squat` PVC ×10 y `Wrist Push-Up` 2×10 + apoyo en extensión 60 s en Bloque 0 de F3, antes del snatch de suelo de 55/40 y el overhead pesado. Informativo: si falla CF2, el snatch de suelo se trabaja desde hang un ciclo más; no genera `mobility_blocked`.

## Bloques comunes del Nivel 11

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N10 con foco nuevo: overhead profundo para snatch de suelo (posición de recepción) y dorsiflexión/enganche de la primera fase. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, reforzado para snatch) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, re-test informal F3) | `Shoulder Dislocate` + `Band Pull-Apart` + `Overhead Squat` (PVC) | 15 + 15 + 10 | 15 + 15 + 10 | 15 + 15 + 10 (ensayo CF2) | 12 + 12 + 8 suave |
| Muñeca (CF6, re-test informal F3) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×8 suave + 30 s + 45 s |
| Enganche de barra (foco nuevo) | `Clean Pull` (técnico, ligero) + `Snatch Balance` (ligero) | 2×3 + 2×3 | 2×3 + 2×3 | 3×2 + 3×2 | 2×2 suave |
| Anillas (mantenido) | `False Grip Hang` + `Ring Support Hold` | 2×20 s + 2×20 s | 3×20 s + 2×20 s | 3×20 s + 3×20 s | 2×15 s + 2×15 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 45 s + 2×8 | 45 s + 2×10 | 60 s + 2×10 | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en enganche (`Clean Pull` técnico) y sentadilla antes del back squat; `Deep Squat Hold` justo antes.
- **B (Skill):** `Shoulder Dislocate` extra ×5 y `Snatch Balance` extra ×2 antes del snatch; `Wrist Push-Up` extra ×5 antes del C&J (CF6 en caliente).
- **C (Metcon):** énfasis en overhead (jerk del WOD) y dorsiflexión; comba de calentamiento.
- **D (Mixta):** énfasis en anillas y descarga ampliada; `Overhead Squat` suave con PVC como cierre.

### Bloque postural (prehab — selección N11)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En el nivel de más carga absoluta del programa, el prehab no sube volumen: su trabajo es sostener hombro/muñeca/lumbar a través del ciclo.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones con snatch/C&J, añadir `Lat Stretch` y dorsiflexión). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N11)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Snatch de suelo técnico: `Snatch Balance` + `Squat Snatch` con 40/30 kg; C&J 60/43 kg; back squat 90/70 kg; anillas a mantenimiento (1 MU suelto por sesión en B); DT descompuesto en piezas (deadlift + HPC + PJ separados) |
| 2 — Progresión | 80% | Squat snatch 45/35 kg; C&J 65/45 kg; back squat 95/70 kg; primer DT completo a 60/43 kg (escalado) en sesión C; `Ring Muscle Up` kipping admitida en volumen bajo |
| 3 — Intensificación | 95% | Squat snatch 55/40 kg ensayando el test; C&J 70/47.5 kg ensayando el test; re-test informal CF2/CF6; back squat 100/70 kg ensayo; **DT RX como hito de sesión C** |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (overhead y enganche a dosis F4); test de nivel al final |

Dosificación: 1-2 RIR en todo el trabajo pesado; los singles de squat snatch y C&J van con descanso completo (150-180 s en F3). Una sesión completa entre bisagras pesadas (back squat A / snatch B) y entre overheads pesados (C&J C / snatch B) — §10.5.

## Sesiones del Nivel 11

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: back squat pesado y clean & jerk (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (90/70 kg) | 4×3 (95/70 kg) | 5×2 (100/70 kg, ensayo test) | 2×3 suave (80/60 kg) | 150-180 s | El pesado del nivel; pausa de 1 s abajo en F2-F3; CF1 en cada rep; 1-2 RIR, nunca al fallo |
| `Clean and Jerk` | 5×1+1 (60/43 kg) | 5×1+1 (65/45 kg) | 6×1 (70/47.5 kg, ensayo test) | 3×1+1 suave (50/35 kg) | 150 s | La unidad completa: `Squat Clean` + `Push Jerk` (o `Split Jerk` si ya sale) en el mismo minuto; recepción en squat (CF1+CF3), bloqueo overhead estable (CF2); si la recepción se corta, rep no válida (§10.4) |
| `Romanian Deadlift` | 3×6 (80/60 kg) | 3×5 (85/65 kg) | 3×5 (90/70 kg) | 2×5 suave | 120 s | Bisagra de mantenimiento CF5+CF7; una sesión completa entre bisagras exigentes (§10.5) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 5 `Power Clean` (60/43 kg) + 5 `Push Jerk` (55/40 kg) + 8 `Box Jump` | El C&J descompuesto; carga de trabajo |
| F2 | 5 rondas | 3 `Clean and Jerk` (60/43 kg) + 10 `Air Squat` | 60-90 s entre rondas; primeras unidades completas en fatiga moderada |
| F3 | AMRAP 14' | 2 `Clean and Jerk` (65/45 kg) + 6 `Wall Ball` (9/6 kg) + 10 `Double Unders` | C&J con carga de trabajo bajo reloj; §10.2: carga técnica, nunca pesado en metcon |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El C&J es la verdad de la barra: si el clean llega al rack limpio y el jerk bloquea arriba, da igual lo que pese — es válido. Si hay que pelear la recepción o press-out el jerk, la carga miente (§1.7-CrossFit §10.4)."

### Sesión B — Skill: snatch de suelo y anillas (todas las fases)

- Tipo: técnica, series seguidas, barra primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF2/CF6 en caliente en F3). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Squat Snatch` (de `Snatch Balance` + `Hang Snatch`) | 5×2 (40/30 kg) | 5×2 (45/35 kg) | 6×1 (55/40 kg, ensayo test) | 3×2 suave (35/25 kg) | 150 s | El snatch completo de suelo llega al programa: primera fase desde el suelo con enganche (`Clean Pull` técnico como calentamiento), recepción en `Overhead Squat` profundo (CF1+CF2); del suelo, no de hang — eso ya quedó en N8 (decisión 3 del lote 07-09 cumplida); recepción cortada = `Power Snatch`, no cuenta (§10.4) |
| `Overhead Squat` | 3×3 (50/35 kg) | 3×3 (55/40 kg) | 4×2 (60/43 kg) | 3×3 suave | 120 s | La recepción del snatch como ejercicio: posición, posición, posición |
| `Ring Muscle Up` (volumen) | 4×1 | 5×1-2 | 5×2 (tope 10) | 2×1 suave | 120 s | La MU se consolida: aquí ya se admite kipping controlado (la primera estricta fue N10, §10.1); descanso completo entre series; si el false grip duele >2/10, se congela (§10.3) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | EMOM 12' | Impares: 2 `Power Snatch` (40/30 kg); pares: 10 `Air Squat` + 8 `Single Unders` | Snatch ligero de suelo en metcon; §10.2 |
| F2 | EMOM 14' | Impares: 1 `Squat Snatch` (45/35 kg); pares: 5 `Chest to Bar Pull Up` + 8 `Double Unders` | Primer EMOM con squat snatch; si la recepción se pierde, baja a power (§10.4) |
| F3 | 4 rondas | 2 `Squat Snatch` (50/35 kg) + 2 `Ring Muscle Up` (o 4 `Assisted Muscle Up Transition`) + 12 `Double Unders` | 90 s entre rondas; el dúo de habilidad del tramo avanzado |
| F4 | 2 rondas suaves | 3 `Hang Snatch` (30/20 kg) + 6 `Single Unders` | Técnica relajada |

**Tips de sesión:** "El snatch de suelo premia la paciencia acumulada desde N8: tall, hang, power, squat — cada peldaño de los últimos tres niveles está en la barra de hoy. Si la primera fase se desordená, vuelve a `Snatch Balance` y reconstruye (§1.7-CrossFit)."

### Sesión C — Metcon: DT técnico y DT RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 55-65 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Deadlift` (caliente) | 2×5 (60/45 kg) | 2×3 (70/52 kg) | 2×3 (70/52 kg) | 2×5 suave | 60 s | Progresión hasta la carga del WOD; CF7 en cada rep |
| `Hang Power Clean` + `Push Jerk` (caliente) | 2×3+3 (50/35 kg) | 2×2+2 (60/43 kg) | 2×2+2 (70/52 kg) | 2×3 suave | 60 s | El corazón del DT en caliente; sin prisa |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 6 `Deadlift` (60/45 kg) + 5 `Hang Power Clean` (60/43 kg) + 4 `Push Jerk` (55/40 kg) | Los tres movimientos del DT descompuestos; ritmo conversacional |
| F2 | Por tiempo (hito intermedio) | `DT` escalado: 5 rondas de 12 `Deadlift` (60/45 kg) + 9 `Hang Power Clean` (60/43 kg) + 6 `Push Jerk` (60/43 kg) | Primer DT completo con carga de trabajo; anota tiempo — es la referencia del RX |
| F3 | Benchmark: **DT RX (hito)** | `DT` en modalidad RX (id 14, FOR_TIME, sin timeCap en catálogo: 5 rondas de 12 `Deadlift` + 9 `Hang Power Clean` + 6 `Push Jerk` a 70/52 kg, ver definición del benchmark) | **Primer Hero con barra pesada RX: logro "DT RX registrada".** La cabeza manda: romper cada movimiento desde el principio; CF7 en el deadlift, recepciones limpias en el hang clean |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "DT es cinco rondas de un trato con la barra: 12 muertes suaves, 9 recepciones limpias, 6 bloqueos decisivos. Los que hacen singles rápidos de jerk ganan a los que hacen triples heroicos que mueren en la ronda 3. Y 70/52 kg es la carga del test — cada ronda de hoy es un ensayo (§1.7-CrossFit §10.4)."

### Sesión D — Mixta: C&J volumen, squat front y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Clean and Jerk` (volumen) | 4×2 (50/35 kg) | 4×2 (55/40 kg) | 5×1+1 (60/43 kg) | 3×2 suave | 120 s | Volumen de la unidad completa con carga moderada; unidades limpias, no pesadas |
| `Front Squat` | 3×5 (70/50 kg) | 3×5 (75/55 kg) | 4×3 (80/60 kg) | 3×3 suave | 120 s | El squat del clean; CF3 en el rack; posición vertical de torso |
| `Overhead Carry` | 3×30 m (30/20 kg) | 3×40 m (35/25 kg) | 4×30 m (40/30 kg) | 2×30 m suave | 60 s | Overhead cargado en marcha; escápulas activas — el seguro del snatch y el jerk |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 4 `Dumbbell Clean` (moderado) + 6 `Front Squat` (40/30 kg, mancuernas o barra) + 10 `Single Unders` | 60-90 s entre rondas |
| F2 | 4 rondas | 3 `Power Clean` (60/43 kg) + 6 `Push Jerk` (50/35 kg) + 10 `Double Unders` | 60 s entre rondas; el jerk con las piernas ya cargadas |
| F3 | AMRAP 15' | 5 `Kettlebell Swing Russian` (24/16 kg) + 5 `Wall Ball` (9/6 kg) + 5 `Box Jump Over` + 15 `Double Unders` | Motor RX de mantenimiento; bisagra CF7 |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El front squat es el detector del clean: si 75 kg en el rack delantero se siente como casa, el clean de 70/47.5 no tendrá secretos. Construye la posición y la carga llegará sola."

## Test de nivel N11 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Squat Snatch (55/40 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 2 | Clean and Jerk (70/47.5 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 3 | Back Squat (100/70 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 4 | DT (benchmark RX) | 22:00 (1320 s) | 18:00 (1080 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `DT` (id 14, FOR_TIME, **sin timeCap en catálogo**: 5 rondas de 12 `Deadlift` + 9 `Hang Power Clean` + 6 `Push Jerk` a 70/52 kg, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`): los tres movimientos con la barra a 70/52 kg; el deadlift puede ser táctil-and-go pero nunca rebote (CF7), el hang clean se recibe en power, el jerk en bloqueo.
- Contraste con referencias de catálogo (consulta 2026-10-10): DT RX beginner = 18:00 M / 22:00 F; intermediate = 12:00 M / 15:00 F. El mínimo se fija en beginner RX F (22:00) y el objetivo en beginner RX M (18:00). El intermediate (12:00/15:00) queda fuera: hacer DT a ritmo intermediate el mismo día que squat snatch y C&J pesados es terreno del tramo élite final.
- Ítem 1: criterio de validez = recepción en `Overhead Squat` profundo (CF1+CF2), sin press-out marcado; recepción cortada = `Power Snatch`, no cuenta (§10.4).
- Ítem 2: recepción del clean en squat (CF1+CF3) + jerk bloqueado (CF2); la unidad completa en un solo gesto encadenado.
- Ítem 3: CF1 en cada rep; si la profundidad se pierde en la serie, la serie termina.
- El re-test informal CF2/CF6 ya se hizo en Bloque 0 de F3; no es ítem del test (decisión 1).

## Logros del nivel N11

**De test:**
- "DT RX registrada" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: DT (benchmark RX)`. El primer Hero con barra pesada RX: el hito del nivel "Pesado".
- "Snatch de suelo de 55/40 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Squat Snatch (55/40 kg)`.
- "Clean & jerk de 70/47.5 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Clean and Jerk (70/47.5 kg)`.
- "Sentadilla de 100/70 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Back Squat (100/70 kg)`.

**De hito:**
- "Primer Squat Snatch completo" — primer `Squat Snatch` desde el suelo con recepción en profundidad y bloqueo overhead. `tier: epico`.
- "Primer Clean & Jerk RX completo" — primer `Clean and Jerk` unificado (squat clean + jerk) con la carga RX 70/47.5 fuera de test. `tier: epico`.
- "Primer DT escalado completo" — primer `DT` completo a carga de trabajo (sesión C F2). `tier: raro`.
- "Primer DT RX" — primera ejecución de `DT` en RX (sesión C F3, antes del test). `tier: legendario`.
- "Muscle-up doble" — primer set de 2 `Ring Muscle Up` sin bajar de las anillas entre rep y rep. `tier: raro`.
- "Re-test CF2/CF6 superado" — re-test informal de overhead y muñeca a dosis máximas en F3, sin síntomas. `tier: comun`.

---

# NIVEL 12 — Heroes

- **Título:** Heroes — Volumen alto, gimnásticos en WODs y O-lifts en fatiga
- **Objetivo:** el cierre del tramo avanzado: WODs épicos y Hero WODs con volumen alto (**Whitten RX** como hito de volumen de 5 rondas), los gimnásticos avanzados dentro de los metcons (muscle-up con tope en WODs, C2B en fatiga, HSPU kipping en metcons), los O-lifts en fatiga SOLO a carga técnica moderada (§10.2), y el benchmark de cierre del arco del muscle-up: **Nate RX** (AMRAP 20' de 2 MU + 4 HSPU + 8 KBS a 32/24 kg). Al superar N12, el usuario es atleta avanzado completo: gimnásticos de alta habilidad en WODs, barra pesada de suelo y Heroes RX.
- **Requisitos de entrada:** mínimos del test N11 (incl. DT RX y squat snatch 55/40).
- **skillFocus:** `Heroes: volumen alto, muscle-up y HSPU kipping en WODs, O-lifts en fatiga a carga técnica`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF6/CF7:** ensayo de `Wrist Push-Up` 2×10 + apoyo en extensión 60 s y `Dead Bug` 2×12/lado + `Bird Dog` 2×10/lado + swing ligero en Bloque 0 de F3, antes de los gimnásticos invertidos y la bisagra del Nate RX. Informativo: si CF6 falla, el kipping HSPU del test baja a `Strict HSPU` con tope; si CF7 falla, los swings del Nate se escalan a 24/16 kg; no genera `mobility_blocked`.

## Bloques comunes del Nivel 12

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N11 con foco nuevo: muñeca/hombro reforzados para el trabajo invertido bajo fatiga y bisagra para el volumen de swings. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 12 + 10/lado + 12 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` | 15 + 15 | 15 + 15 | 15 + 15 | 12 + 12 suave |
| Muñeca (CF6, re-test informal F3) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×8 suave + 30 s + 45 s |
| Bisagra (CF7, re-test informal F3) | `Dead Bug` + `Bird Dog` + `Kettlebell Swing Russian` (ligero, controlado) | 2×12/lado + 2×10/lado + 2×12 | 2×12/lado + 2×10/lado + 2×15 | 2×12/lado + 2×10/lado + 2×15 (ensayo CF7) | 2×8 suave + 2×8 suave + 2×10 suave |
| Anillas (mantenido) | `False Grip Hang` + `Ring Support Hold` | 3×20 s + 3×20 s | 3×20 s + 3×20 s | 3×20 s + 3×20 s | 2×15 s + 2×15 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 45 s + 2×10 | 45 s + 2×10 | 60 s + 2×12 | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en bisagra (ensayo CF7) antes de la barra; `Deep Squat Hold` antes del squat.
- **B (Skill):** `False Grip Hang` extra ×10 s y `Scapular Pull Up` ×5 antes del trabajo de anillas; `Wrist Push-Up` extra ×5 antes del HSPU (CF6 en caliente).
- **C (Metcon):** énfasis en hombro y bisagra (el WOD del día manda); comba de calentamiento.
- **D (Mixta):** énfasis en movilidad completa y descarga ampliada; el trío suave de movilidad cierra el Bloque 0.

### Bloque postural (prehab — selección N12)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En la fase de más volumen del tramo avanzado, el prehab protege la sesión siguiente: hombro/muñeca/lumbar son los tres frentes que más pagan un Hero largo.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; tras los Heroes, añadir `Lat Stretch`, extensión de muñeca y estiramiento suave de dorsiflexión). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N12)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Volumen gimnástico con tope: `Ring Muscle Up` en EMOMs (tope 8/sesión), `Kipping Handstand Push Up` en metcons (tope 30/sesión), C2B en fatiga; O-lifts en metcon a 50/35 kg máx. (§10.2); primer Hero de volumen moderado |
| 2 — Progresión | 80% | MU en WODs con tope 10-12/sesión; kipping HSPU con tope 40; C2B ×10+ en AMRAPs; **Whitten RX como hito de sesión C** (5 rondas densas, 49:00 beginner); primeros WODs con C&J ligero en fatiga |
| 3 — Intensificación | 95% | Toques de test: 5 MU acumuladas, 8 HSPU kipping, C&J 70/47.5; re-test informal CF6/CF7; **Nate RX como hito de sesión C** (segunda y última toma antes del test) |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

Dosificación del volumen balístico: tope 80 reps/sesión entre MU, kipping HSPU, C2B y T2B en F2-F3; una sesión completa entre exposiciones intensas de hombro. Regla §10.1: dolor matinal >2/10 → vuelta a variantes estrictas/asistidas un ciclo. Los O-lifts en metcon nunca superan 60/43 kg (carga de trabajo técnica; §10.2).

## Sesiones del Nivel 12

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: C&J pesado y gimnástico de mantenimiento (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A (ensayo CF7 en F3). Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Clean and Jerk` | 5×1+1 (65/45 kg) | 5×1+1 (70/47.5 kg) | 6×1 (70/47.5 kg, ensayo test) | 3×2 suave (55/40 kg) | 150 s | La barra pesada se mantiene: unidades limpias, 1-2 RIR; recepción en squat (CF1+CF3), bloqueo overhead (CF2) |
| `Back Squat` | 3×5 (90/70 kg) | 3×3 (95/70 kg) | 4×2 (100/70 kg) | 2×3 suave (80/60 kg) | 150 s | Fuerza de mantenimiento a carga de test; una sesión completa entre bisagras pesadas (§10.5) |
| `Kipping Handstand Push Up` | 4×5 | 5×5 (tope 30) | 5×6 (tope 40) | 3×4 suave | 90 s | El kipping HSPU ahora es carga de trabajo; si el hollow se rompe, vuelve a `Strict HSPU` |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 3 `Power Clean` (60/43 kg) + 6 `Kipping Handstand Push Up` + 9 `Air Squat` | O-lift en fatiga a carga de trabajo (§10.2); ritmo alto |
| F2 | AMRAP 14' | 5 `Kipping Handstand Push Up` + 7 `Wall Ball` (9/6 kg) + 9 `Kettlebell Swing Russian` (24/16 kg) | El overhead bajo fatiga del test; bisagra CF7 |
| F3 | AMRAP 15' | 4 `Clean and Jerk` (60/43 kg) + 8 `Box Jump Over` + 12 `Double Unders` | C&J en metcon, carga técnica; la unidad completa cansada pero limpia (§10.4) |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El C&J a 70/47.5 ya no es un evento: es la carga de trabajo. Hoy se hace cansado, con la zancada corta y el bloqueo seco — porque así llegará el día del test. Lo que no se hace cansado hoy, no existe cuando cuenta (§1.7-CrossFit §10.4)."

### Sesión B — Skill: muscle-up volumen y C2B en fatiga (todas las fases)

- Tipo: técnica, series seguidas, gimnástico primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (false grip en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Ring Muscle Up` | 5×1-2 (tope 8) | 5×2 (tope 10) | 6×2 (tope 12, ensayo test) | 3×1 suave | 120 s | La MU como ejercicio de volumen con tope; kipping controlado admitido (base estricta desde N10, §10.1); series pequeñas y limpias, descanso completo |
| `Chest to Bar Pull Up` (en fatiga) | 4×8 | 5×8 | 5×10 | 3×5 suave | 90 s | C2B con volumen real: es la tracción del futuro volume-work y el descanso de la MU; barbilla sobre la barra siempre (§10.4) |
| `Strict HSPU` | 3×3 | 3×4 | 4×3 (tope estricto) | 2×3 suave | 90 s | El ancla estricta se mantiene: es lo que sostiene el kipping bajo volumen (§10.1) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | EMOM 14' | Impares: 1-2 `Ring Muscle Up` + 4 `Kipping Pull Up`; pares: 8 `Air Squat` + 8 `Single Unders` | La MU bajo reloj con margen; si la transición se rompe, asistida (§10.4) |
| F2 | EMOM 16' | Impares: 2 `Ring Muscle Up`; pares: 5 `Chest to Bar Pull Up` + 10 `Double Unders` | 8 MU acumuladas por sesión; tope vigente |
| F3 | 5 rondas | 2 `Ring Muscle Up` + 4 `Kipping Handstand Push Up` + 8 `Kettlebell Swing American` (24/16 kg) | 90 s entre rondas; el esqueleto del Nate (§10.2: KB a carga técnica, no RX) |
| F4 | 2 rondas suaves | 3 `Ring Row - Elevated Feet` + 5 `Push Up` | Técnica relajada |

**Tips de sesión:** "El Nate se gana en sesiones como esta: 2 muscle-ups frescos, un push invertido, 8 swings — repetido hasta que el cuerpo lo haga sin pensar. El WOD del test no será más difícil que la suma de sus piezas; por eso las piezas se entrenan por separado (§1.7-CrossFit)."

### Sesión C — Metcon: Whitten RX y Nate RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 60-75 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Kettlebell Swing American` (caliente) | 2×10 (16/12 kg) | 2×10 (24/16 kg) | 2×8 (32/24 kg) | 2×10 suave | 60 s | Progresión hasta la carga del WOD; bisagra CF7 en cada rep |
| `Ring Muscle Up` + `Kipping Handstand Push Up` (caliente) | 2×1 + 2×3 | 2×1 + 2×3 | 2×2 + 2×4 | 1×1 + 1×3 | 60 s | Los dos gimnásticos del Nate en caliente, sin llegar al tope |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 16' | 8 `Kettlebell Swing American` (24/16 kg) + 6 `Box Jump` + 6 `Wall Ball` (9/6 kg) + 4 `Burpee` | El sabor de Whitten a carga moderada; ritmo sostenido |
| F2 | Hero (hito): **Whitten RX** | `Whitten` en modalidad RX (id 69, FOR_TIME, timeCap 3000 s: 5 rondas de 22 `Kettlebell Swing American` (24/16 kg) + 22 `Box Jump` + 400 m `Run` + 22 `Burpee` + 22 `Wall Ball` (9/6 kg)) | **Hito de volumen: logro "Primer Hero de 5 rondas".** Rompe wall balls en 11-11 desde el principio; el burpee después del run es el punto crítico — respira, no pareses |
| F3 | Benchmark: **Nate RX (hito)** | `Nate` en modalidad RX (id 60, AMRAP 20 min, timeCap 1200 s: 2 `Ring Muscle Up` + 4 `Handstand Push Up` + 8 `Kettlebell Swing American` (32/24 kg), máx. rondas) | **Hito del nivel: logro "Nate RX registrada".** Los HSPU admite kipping controlado; las MU en sets de 1-2 con transición limpia; los swings a 32/24 kg con bisagra CF7 impecable |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Whitten es el Hero que separa a los impacientes de los atletas: 110 reps de cada cosa y una milla escondida en el medio. Nadie hace Whitten rápido; todo el mundo hace Whitten entero — esa es la victoria. Y en el Nate, las rondas se ganan en los swings: muñeca relajada, bisagra que no negocia (§1.7-CrossFit §10.2)."

### Sesión D — Mixta: C2B fatiga, OHS y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Chest to Bar Pull Up` (volumen) | 4×8 | 5×8 | 5×10 | 3×5 suave | 90 s | La tracción del tramo avanzado en volumen; tope compartido con MU y kipping |
| `Overhead Squat` | 3×3 (50/35 kg) | 3×3 (55/40 kg) | 4×2 (60/43 kg) | 3×3 suave | 120 s | Overhead de mantenimiento; la posición que protege el snatch y el OHS de los Heroes |
| `Toes to Bar` | 4×10 | 4×12 | 5×12 | 3×6 suave | 90 s | T2B kipping en volumen; core del Nate y de todos los Heroes de por medio |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 6 `Chest to Bar Pull Up` + 8 `Dumbbell Snatch` (alterno, moderado) + 10 `Single Unders` | 60-90 s entre rondas; tracción + O-lift unilateral |
| F2 | 4 rondas | 5 `Chest to Bar Pull Up` + 6 `Kipping Handstand Push Up` + 12 `Double Unders` | 60 s entre rondas; el dúo gimnástico en fatiga |
| F3 | AMRAP 15' | 6 `Power Snatch` (50/35 kg) + 8 `Toes to Bar` + 10 `Wall Ball` (9/6 kg) | Snatch en metcon a carga técnica (§10.2); el overhead que respeta la técnica |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El volumen se acumula en sesiones aburridas: C2B de 8 en 8, T2B de 10 en 10, un snatch ligero que no se rompe nunca. Los Heroes se ganan aquí, no el día del WOD."

## Test de nivel N12 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Ring Muscle Up | 5 reps | 8 reps | gte | reps | fuerza |
| 2 | Kipping Handstand Push Up | 8 reps | 12 reps | gte | reps | fuerza |
| 3 | Clean and Jerk (70/47.5 kg) | 5 reps | 8 reps | gte | reps | fuerza |
| 4 | Nate (benchmark RX) | 5 rounds | 8 rounds | gte | rounds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Nate` (id 60, AMRAP 20 min, timeCap 1200 s: 2 `Ring Muscle Up` + 4 `Handstand Push Up` + 8 `Kettlebell Swing American` (32/24 kg) por ronda, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`): los HSPU admiten kipping controlado; las MU exigen transición completa (pez sin rotación no cuenta, §10.4); los swings a 32/24 kg con bisagra CF7 (si el re-test CF7 de F3 falló, se escalan a 24/16 y se anota).
- Contraste con referencias de catálogo (consulta 2026-10-10): Nate RX beginner = 5-10 rondas; intermediate = 11-16 rondas (referencias unificadas M/F, por rondas). El mínimo se fija en el suelo de beginner (5 rondas) y el objetivo en 8 rondas (intermedio de la banda beginner, conservador al combinarse con los ítems de fuerza del mismo test — criterio §1.6). El intermediate completo (11+ rondas) queda para el tramo élite final.
- Ítem 1: acumuladas en series de 1-2 con descanso entre series; la validez es la de siempre: transición completa con bloqueo en soporte (§10.4).
- Ítem 2: rango mínimo validado con hollow/kipping controlado; si el cuerpo se arquea o la cabeza no llega, la rep no cuenta.
- Ítem 3: la carga es la de DT/Grace (70/47.5 vs. 60/43); recepción en squat + jerk bloqueado; reps acumuladas en series de 1-2.
- El re-test informal CF6/CF7 ya se hizo en Bloque 0 de F3; no son ítems del test ni generan `mobility_blocked` (decisión 1).

## Logros del nivel N12

**De test:**
- "Nate RX registrada" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Nate (benchmark RX)`. El cierre del arco del muscle-up: el benchmark oficial de MU + HSPU + potencia, completado en RX.
- "Muscle-up de 5" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Ring Muscle Up`.
- "HSPU kipping de 8" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Kipping Handstand Push Up`.
- "Clean & jerk de 70/47.5 kg de 5" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Clean and Jerk (70/47.5 kg)`.

**De hito:**
- "Primer Hero de 5 rondas" — primera ejecución completa de `Whitten` en RX (sesión C F2): 5 rondas densas de 22 reps × 4 + 400 m run. `tier: legendario`.
- "Primera Nate RX" — primera ejecución del benchmark `Nate` en RX (sesión C F3, antes del test). `tier: epico`.
- "Muscle-up en WOD" — primer metcon completado con `Ring Muscle Up` oficiales (tope respetado, transición limpia). `tier: epico`.
- "Doble de muscle-ups" — primer set de 2 `Ring Muscle Up` sin bajar de las anillas (repetible). `tier: raro`.
- "HSPU kipping de 10" — primer set de 10 `Kipping Handstand Push Up` con rango y hollow controlados. `tier: raro`.
- "C2B de 10 en fatiga" — primer set de 10 `Chest to Bar Pull Up` dentro de un WOD, no en fuerza. `tier: raro`.
- "Re-test CF6/CF7 superado" — re-test informal de muñeca y bisagra a dosis máximas en F3, sin síntomas. `tier: comun`.

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N10-N12: todos los ejercicios citados existen en el catálogo con la grafía usada (verificación por SQL 2026-10-10, `SELECT name FROM exercises ORDER BY name`): `Ring Muscle Up`, `Muscle Up`, `Strict Muscle Up`, `Assisted Muscle Up Transition`, `Muscle Up Negative`, `Ring Muscle Up Transition`, `Ring Dip`, `Ring Dips`, `Ring Support Hold`, `False Grip Hang`, `Squat Clean`, `Squat Snatch`, `Power Clean`, `Hang Power Clean`, `Hang Clean`, `Clean Pull`, `Snatch Balance`, `Power Snatch`, `Hang Snatch`, `Tall Snatch`, `Muscle Snatch`, `Muscle Clean`, `Clean and Jerk`, `Push Jerk`, `Split Jerk`, `Jerk`, `Back Squat`, `Front Squat`, `Deadlift`, `Romanian Deadlift`, `Strict HSPU`, `Strict Handstand Push Up`, `Kipping Handstand Push Up`, `Wall Handstand Push Up`, `HSPU Negative`, `Handstand Push Up`, `Overhead Squat`, `Overhead Carry`, `Kipping Pull Up`, `Chest to Bar Pull Up`, `Kettlebell Swing American`, `Kettlebell Swing Russian`, `Thruster`, `Wall Ball`, `Box Jump`, `Box Jump Over`, `Burpee`, `Run`, `400m Run`, `1 Mile Run`, `Double Unders`, `Single Unders`, benchmarks `Elizabeth` (id 8), `DT` (id 14), `Nate` (id 60), `Whitten` (id 69).
- FALTA (no bloquea): `Isabel` no existe como benchmark en el catálogo (sí `Randy` id 18, snatch; y `Amanda` id 9, squat snatch + MU — candidatos naturales para el tramo élite). Se descartó para N11 porque el encargo pedía un benchmark pesado y `DT` cierra la línea de fuerza del nivel; si se quiere un benchmark de snatch puro antes del élite, habría que crear `Isabel` (30 `Snatch` 60/43 kg) — la grafía `Snatch` a secas tampoco existe como ejercicio (solo `Squat Snatch`/`Power Snatch`).
- FALTA (no bloquea): el ejercicio `Clean` a secas no existe en catálogo (solo `Squat Clean`, `Power Clean`, `Hang Clean`, `Hang Power Clean`, `Muscle Clean`, `Clean Practice`, `Clean Pull`). Benchmarks como `Linda` (id 25, "Clean 0.75×BW") o `Elizabeth` (id 8, "Squat Cleans") que referencian "Clean" genérico necesitarían mapeo a `Squat Clean`/`Power Clean` en el seeder.
- Corrección respecto al encargo: `DT` pesa **70/52 kg** en el catálogo (rx_weight_female = 52 kg), no 70/47.5 como se sugería. El test y las sesiones usan la carga real del benchmark. Además `DT` no tiene `time_cap_seconds` en catálogo (NULL): el mínimo 22:00/objetivo 18:00 van contra las referencias beginner F/M, sin techo duro de registro.
- Observación: `Elizabeth` sí tiene timeCap 900 s (15:00); el mínimo del test N10 (19:00, beginner F) queda por encima del timeCap — mismo tratamiento que Fran en N7 (timeCap como techo duro de registro, mínimo informe más laxo). Si el seeder necesita coherencia estricta timeCap/mínimo, fijar el mínimo en 15:00.
- Mejora sugerida (no bloquea): duplicados de grafía heredados de lotes anteriores (`Ring Dip`/`Ring Dips`, `Face Pull`/`face pull`, `Strict HSPU`/`Strict Handstand Push Up`, `Kettlebell Swing American` a doble entrada con variantes). Este documento usa la primera grafía canónica de cada par.
- Mejora sugerida (no bloquea): `Whitten` y `Nate` tienen referencias unificadas M/F en el catálogo (mismos tiempos/rondas para ambos) — coherente con su uso aquí; `Gunny` (id 67, chaleco 20/15 kg) y `Jerry` (id 68, mono estructural) quedan como alternativas documentadas para el tramo élite si se quiere variar el Hero de sesión C.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Re-tests informales de puertas (CF3 en N10 F3, CF6 en N10 F2, CF2/CF6 en N11 F3, CF6/CF7 en N12 F3):** las siete puertas están superadas al entrar en el tramo; en vez de re-test formales, se programan ensayos a dosis máximas en Bloque 0 con consecuencia de ajuste de carga (informativo). Alternativa descartada: re-test formal — añadiría ítems `puerta_movilidad` a tests ya largos y confundiría el cierre del tramo.
2. **`restAfterBlock` = 60 s en N10-N12:** se mantiene por indicación explícita del proyecto (mismo criterio que N1-N9). Si el `TrainingTimeCalculator` devuelve duraciones desbordadas en los Heroes (Whitten, Nate), la salida es recortar volumen de WOD, no subir el descanso.
3. **Benchmarks del tramo:** Elizabeth RX (N10 — squat clean 60/43 + ring dips: valida los dos pilares del nivel, squat clean y anillas; mínimo 19:00/objetivo 15:00, beginner F/M del propio catálogo), DT RX (N11 — el Hero pesado con barra: 70/52 kg, deadlift + hang power clean + push jerk; mínimo 22:00/objetivo 18:00, beginner F/M) y Nate RX (N12 — cierra el arco del muscle-up abierto en N9-N10: AMRAP de MU + HSPU + KBS; mínimo 5 rondas/objetivo 8, banda beginner). Alternativas consideradas y descartadas: Diane (id 21, deadlift 102/70 + HSPU: el deadlift RX pisa el test N9 sin progresión intermedia y el HSPU kipping es demasiado recién llegado en N10-N11), Linda (id 25, pesos basados en BW: imposible fijar cargas absolutas de test), Isabel (no existe en catálogo), Amanda (id 9, squat snatch 60/43 + MU ×9-7-5: exigencia de MU demasiado alta para cerrar el tramo — candidata al élite), Gunny/Jerry (monoestructurales: no cierran el arco gimnástico del tramo).
4. **Primera muscle-up: anillas (no barra) y estricta antes que kipping:** el catálogo ofrece `Bar Muscle Up` y `Ring Muscle Up`; se elige anillas (línea de N9: false grip, ring dips, transiciones) y la secuencia transición asistida → negativa → estricta → kipping en volumen (§10.1). El kipping en WODs se permite en N11 (volumen bajo) y N12 (WODs con tope). `Bar Muscle Up` queda como variante futura del élite.
5. **Kipping HSPU solo desde N10:** tres niveles de base estricta (N8 progresión, N9 tope, N10 mantenimiento) antes del primer kipping, con tope de volumen progresivo (20 → 30 → 40 reps/sesión) y el estricto como ancla permanente (§10.1).
6. **Snatch de suelo solo en N11:** N8 lo sembró por posiciones (tall → hang → power, decisión 3 del lote 07-09 cumplida); N11 introduce el `Squat Snatch` de suelo con `Snatch Balance` y `Clean Pull` técnico como peldaños. El test N11 exige recepción en squat; recepción cortada = `Power Snatch`, no cuenta.
7. **O-lifts en metcon solo a carga técnica (§10.2):** tope 60/43 kg en WODs de N12 (y 50/35 kg en la mayoría); el pesado vive en fuerza (C&J, squat snatch) y en el DT RX (70/52 kg, donde el deadlift y los movimientos se reciben con técnica vigilada). Nunca squat snatch pesado en metcon.
8. **Cargas femeninas del DT del catálogo (52 kg):** el test N11 usa la carga real del benchmark (70/52), no la referencia externa de 70/47.5 que venía del encargo — la autoridad es el catálogo (verificación SQL 2026-10-10).
9. **Whitten como hito de volumen y Nate como test (ruling):** Whitten (5 rondas × 22 reps × 4 + run, timeCap 3000 s, beginner 49:00) es el Hero de volumen del nivel, ejecutado en sesión C F2 sin presión de test; Nate es el benchmark del test porque cierra el arco del muscle-up con referencias por rondas (beginner 5-10) y timeCap 1200 s gratis. El controller validó esta asimetría: el hito enseña a gestionar un Hero largo; el test mide la habilidad acumulada.
