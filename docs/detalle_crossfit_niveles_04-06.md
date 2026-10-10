# Detalle de contenido — Niveles 4, 5 y 6 — CrossFit Master v1

> Segundo lote del documento fuente del programa CrossFit Master (comando
> `app:create-crossfit-master`). Continuidad directa de
> `docs/detalle_crossfit_niveles_01-03.md` (mismas convenciones, mismo
> calendario de puertas fijado allí). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-10-crossfit-master-design.md` (§4 arco —
> tramo "RX escalado", §5 bloques, §7 tests con benchmarks, §8 puertas CF,
> §9 logros, §10 regla §1.7-CrossFit).
>
> **Estado de redacción:** COMPLETO. Niveles 4, 5 y 6 con sus 16 sesiones
> cada uno (4 fases × A/B/C/D), tests (incl. puertas CF2/CF3 en N4 y CF6/CF7
> en N6), skillFocus y logros. Pendiente la validación de contenido con el
> usuario (gate humano).

**Convenciones del documento** (heredadas del lote 01-03, sin cambios):

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (§5): A — Fuerza (básico de barra + fuerza + prehab hombro); B — Skill/técnica (gimnástico u O-lift + prehab muñeca); C — Metcon/WOD (WOD de la sesión + prehab lumbar/core); D — Mixta (combinación suave + movilidad + prehab de trío completo).
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab muñeca/hombro/lumbar rotado por sesión: A hombro, B muñeca, C lumbar, D trío suave) → `descarga`. `restAfterBlock` = 60 s en N4-N6 (mismo criterio del tramo anterior; sube a 90 s a partir de N7 por volumen, según blueprint v4).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Singles/series máximas → reps `1`. Dosis `/lado` se computan una vez.
- **RIR:** en N4-N6 se trabaja con 1-3 RIR en fuerza; nunca al fallo. En WODs, el fallo técnico marca el escalado (regla §1.7-CrossFit §10.4).
- **Equipamiento asumido N4-N6:** todo lo de N1-N3 + pull-up bar + comba + wall ball + bumper plates. Rack de squat recomendado desde N4.
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta 2026-10-10, `SELECT name FROM exercises ORDER BY name`). Nombres literales.
- **Convención de nombres de ítems de test:** todo ítem que corresponda a un ejercicio de catálogo usa su grafía exacta, para que el test, los logros (`requirementKey`) y las referencias casen exacto. Los benchmarks se nombran como nombre propio ("Cindy (benchmark escalado)", "Helen (benchmark RX)").
- **Duraciones estimadas:** salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.

---

## Puertas de movilidad del programa (definición completa — copia textual del lote 01-03)

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| CF1 | Sentadilla profunda pegada | squat, wall ball, cleans | `Deep Squat Hold` 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor | N4 (squat cargado progresivo) | **N3** ✔ |
| CF2 | Overhead (hombro) | press, S2OH, wall ball target | `Shoulder Dislocate` con banda/PVC ×15 sin compensar la lumbar; `Overhead Squat` con PVC/barra vacía ×10 en profundidad | N5 (S2OH, wall ball a diana) | **N4** (re-test N7) |
| CF3 | Muñeca en front rack | clean, front squat | Front rack con barra vacía 30 s/lado sin dolor ni desviación activa | N5 (cleans) | **N4** (re-test N7) |
| CF4 | Dorsiflexión de tobillo | lunges, wall ball | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón | N4 (lunges, wall ball) | **N3** ✔ |
| CF5 | Cadera/bisagra | deadlift, swings, lunges | Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada | N3 (deadlift con carga, KB swing progresivo) | **N2** ✔ |
| CF6 | Muñeca en extensión | HSPU, handstand | `Wrist Push-Up` 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10 | N7 (HSPU, handstand) | **N6** (re-test N9) |
| CF7 | Bisagra lumbar controlada | deadlifts, O-lifts | `Dead Bug` 2×10/lado + `Bird Dog` 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra | N7 (O-lifts, deadlifts en fatiga) | **N6** (re-test N9) |

Evaluación en este documento: **N4: CF2 y CF3** (ítems `type: puerta_movilidad` con `gateCode` en el test). **N5: ninguna evaluada** (se ensayan CF6/CF7 en Bloque 0, sin evaluación). **N6: CF6 y CF7**. CF1/CF4/CF5 superadas en lotes anteriores: se mantienen en Bloque 0 de forma acumulativa (§1.8), sin re-evaluar salvo que aparezca dolor.

---

## Regla §1.7-CrossFit en tips (spec §10) — aplicación en N4-N6

1. **Primero estricto, luego dinámico:** nada balístico sobre un hombro sin base estricta. El kipping pull-up se introduce en N5 **solo si el test N4 salió con ≥5 estrictas**; si no, el usuario sigue en progresión estricta (`Band Assisted Pull Up` → `Pull Up`) hasta conseguirlas. En N4-N6 no hay kipping HSPU ni muscle-up (eso es N7+).
2. **Lumbar:** O-lifts y deadlifts pesados nunca en fatiga alta; el `Power Clean` de N5-N6 es técnico y con barra ligera-moderada; dolor matinal >2/10 = congelar el gesto un ciclo.
3. **Muñeca:** progresión de extensión soportada antes que volumen (holds antes que reps); dolor matinal >2/10 en extensión = congelar cleans y el work de handstand que se siembra en N6.
4. **Técnica rota antes que músculo:** en WODs con carga, cuando falla la forma se baja la dosis; el escalado también es protección.
5. Descanso entre exposiciones pesadas de bisagra: dejar pasar al menos una sesión completa entre bisagras cargadas exigentes.

---

# NIVEL 4 — Primera Tracción

- **Título:** Primera Tracción — La primera dominada estricta
- **Objetivo:** llevar la progresión de tracción de N3 (`Jumping Pull Up` / `Negative Pull Up`) hasta la **primera dominada estricta** y sus primeras series; introducir la progresión de `Toes to Bar` (`Knee Raise` → `Hanging Leg Raise` → `Toes to Bar` estricto); consolidar la sentadilla trasera con carga de trabajo y estrenar `Bench Press`; meter `Wall Ball` y `Box Jump` en los metcons. Al superar N4, CF2 (overhead) y CF3 (front rack) quedan evaluadas y la puerta del N5 (cleans, S2OH) está abierta.
- **Requisitos de entrada:** mínimos del test N3 (incl. CF1 y CF4 superadas).
- **skillFocus:** `Primera dominada estricta, progresión a Toes to Bar y sentadilla/press de trabajo con barra`
- **Puertas evaluadas:** **CF2** (overhead) y **CF3** (front rack) — requisito de entrada efectivo del N5 (`Power Clean`, wall ball a diana, S2OH). Ítems `type: puerta_movilidad` con `gateCode` CF2/CF3 en el test. Ensayos integrados: `Shoulder Dislocate` + `Overhead Squat` con PVC en Bloque 0 de las sesiones A/B (CF2); front rack con barra vacía en Bloque 0 de las sesiones B/C (CF3).

## Bloques comunes del Nivel 4

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N3 (§1.8: se mantiene toda la movilidad anterior — CF1/CF4/CF5 incluidas) con foco nuevo: overhead/front rack (ensayo CF2/CF3) y hang de barra (activación de tracción). Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 10 + 8/lado + 8 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 45 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, foco nuevo) | `Shoulder Dislocate` (banda/PVC) + `Band Pull-Apart` + `Overhead Squat` (PVC) | 12 + 12 + 5 | 15 + 12 + 8 | 15 + 15 + 10 (ensayo CF2) | 12 + 12 + 8 suave |
| Muñeca (siembra CF6) | `Wrist Prayer` + `Wrist Extension Stretch` + `Back-of-Hand Stretch` | 30 s cada uno | 30 s cada uno | 45 s cada uno | 30 s cada uno suave |
| Hang (foco nuevo) | `Dead Hang` + `Scapular Pull Up` | 20 s + 5 | 30 s + 6 | 40 s + 8 | 30 s + 5 suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en hombro/overhead antes del press y bench; `Deep Squat Hold` justo antes del `Back Squat`.
- **B (Skill):** `Shoulder Dislocate` extra ×5 y `Dead Hang` extra ×10 s antes de la barra; ensayo CF3 (front rack con barra vacía 20-30 s/lado); respiración diafragmática ×5 ciclos al final.
- **C (Metcon):** énfasis en tobillo (wall ball en el WOD) y hang; activación `Clamshell` ×10/lado.
- **D (Mixta):** énfasis en cadera y torácica; la descarga se amplía.

### Bloque postural (prehab — selección N4, dosis mantenidas de N3)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 2×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. El prehab se mantiene denso: es el seguro de hombro/muñeca/lumbar del tramo RX escalado.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática tumbado (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N4)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Back squat 50/35 de trabajo; primer `Bench Press`; negativas de 5 s + banda en tracción; primeros `Knee Raise`; wall ball ligero (6/4 kg) y `Box Jump` bajo en metcons |
| 2 — Progresión | 80% | Banda de asistencia más fina o primeras `Pull Up` sueltas; `Hanging Leg Raise` → primeros `Toes to Bar Strict`; primer AMRAP de 14' con wall ball; ensayo de CF2/CF3 en Bloque 0 |
| 3 — Intensificación | 95% | Back squat 60/40 ensayando el test; series de pull-ups con volumen moderado (tope de 15-20 reps totales de tracción estricta por sesión); `Toes to Bar Strict` en AMRAP; Cindy escalada como hito de sesión D |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (CF2/CF3 a dosis F4); test de nivel al final |

## Sesiones del Nivel 4

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Fuerza: squat, bench y bisagra (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 55-65 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (50/35 kg) | 4×5 (55/40 kg) | 5×5 (60/40 kg, ensayo test) | 2×5 suave (50/35 kg) | 120 s | Profundidad con talones pegados (CF1); si la lumbar se va, baja carga antes que profundidad |
| `Bench Press` | 3×5 (barra 30/20 kg) | 4×5 (35/25 kg) | 4×5 (40/30 kg) | 2×5 suave | 120 s | Primer banco del programa; escápulas retraídas, pies en el suelo, glúteo apretado; muñecas neutras |
| `Kettlebell Romanian Deadlift` | 3×8 (16/12 kg) | 3×10 (16/12 kg) | 3×10 (24/16 kg) | 2×8 suave | 90 s | Bisagra de mantenimiento CF5; nunca en fatiga alta (§10.2); el deadlift pesado ya pasó a técnico-descarga |

Ensayo de test en Fase 3: última serie de back squat a 60/40 ×5 con calidad de CF1.

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 8' | 10 `Wall Ball` (6/4 kg) + 8 `Box Jump` (bajo) | Primer wall ball del programa; a una diana accesible, sin exigir altura RX todavía |
| F2 | AMRAP 10' | 10 `Wall Ball` (6/4 kg) + 6 `Box Jump` + 8 `Sit-up` | Tres movimientos; transiciones rápidas |
| F3 | AMRAP 10' | 12 `Wall Ball` (6-9/4-6 kg) + 6 `Box Jump` + 6 `Push Up` | Ritmo alto; si el throw se acorta, baja el peso de la bola (§10.4) |
| F4 | AMRAP 6' suave | 8 `Wall Ball` (6/4 kg) + 6 `Sit-up` | Movimiento, no fatiga; test al final del nivel |

**Tips de sesión:** "El wall ball es sentadilla + press de piernas + throw: si CF1 o CF2 no están hoy, la bola baja de peso o de diana. La puerta manda sobre el ego."

### Sesión B — Skill: tracción estricta y T2B (todas las fases)

- Tipo: técnica, series seguidas, mayor demanda neural primero. Duración estimada: 55-65 min.
- Bloque 0: variante B (ensayo CF3). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Negative Pull Up` (bajada controlada) | 4×3 (bajada 5 s) | 4×3 (bajada 5-6 s) | 4×3 (bajada 6 s) | 2×2 suave | 90 s | Continúa la línea de N3; pecho a la barra, bajada sin desplomarse; si ya haces estrictas, este slot es `Pull Up` ×3-5 |
| `Band Assisted Pull Up` | 3×5 | 3×5 (banda más fina) | 3×3 (banda fina) | 2×5 suave | 90 s | Progresión: bajar asistencia (banda gruesa → fina) antes que subir reps; alternar agarre prono/supino |
| `Knee Raise` | 3×8 | 3×10 | 3×12 | 2×8 suave | 60 s | Primer peldaño T2B: rodillas al pecho sin balanceo; si domina, pasar a `Hanging Leg Raise` |
| `Hanging Leg Raise` | — | 2×6 | 3×8 | 2×6 | 60 s | Piernas rectas a la altura de la cadera o más; es el peldaño previo a `Toes to Bar Strict` |

Volumen de tracción por sesión: tope 20 reps estrictas-asistidas entre los dos primeros ejercicios; cuando salgan las primeras `Pull Up` sueltas, sustitúyelas en el slot de negativas (decisión de dosificación, no de contenido).

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 6 `Band Assisted Pull Up` + 8 `Push Press` (20/15 kg) | 60-90 s entre rondas; agarre que no rescinda el hombro |
| F2 | 4 rondas | 6 `Band Assisted Pull Up` + 8 `Knee Raise` + 6 `Push Press` (20/15 kg) | 60 s entre rondas |
| F3 | EMOM 10' | Impares: 3-5 `Pull Up` (estricta o asistencia mínima); pares: 6 `Hanging Leg Raise` | Primer EMOM de tracción; descansa el tiempo que sobre |
| F4 | 2 rondas suaves | 5 `Band Assisted Pull Up` + 6 `Knee Raise` | Técnica relajada |

**Tips de sesión:** "La primera estricta se construye despacio: negativas de 5-6 s, banda cada vez más fina, y paciencia. Primero estricto, luego dinámico — el kiping de N5 no llega sin esta base (§1.7-CrossFit §10.1)."

### Sesión C — Metcon: wall ball y tracción asistida (todas las fases)

- Tipo: WOD + core. Duración estimada: 50-60 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Wall Ball Practice` | 2×8 (6/4 kg) | 2×10 | 3×8 | 2×8 suave | 60 s | Técnica de throw: sentadilla profunda, extensión, lanzar con las yemas; la diana RX se negocia en N6 |
| `Active Hang` | 2×20 s | 2×30 s | 3×30 s | 2×20 s | 45 s | Hombros activos, lejos de las orejas; base de todo lo colgado |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 10' | 8 `Wall Ball` (6/4 kg) + 8 `Box Step Over` + 8 `Sit-up` | Ritmo conversacional |
| F2 | AMRAP 12' | 8 `Wall Ball` (6/4 kg) + 6 `Band Assisted Pull Up` + 10 `Air Squat` | Primer AMRAP de 14' con tracción; reparte el agarre |
| F3 | AMRAP 14' | 10 `Wall Ball` (6-9/4-6 kg) + 5 `Pull Up` (estricta o banda) + 10 `Box Jump Over` | Ensayo del motor del test; si las pull-ups mueren, cambia a banda sin parar el reloj |
| F4 | AMRAP 8' suave | 8 `Wall Ball` (6/4 kg) + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Tracción en metcon = agarre primero: si los antebrazos explotan antes que la espalda, alterna pull-up con box jump over y vuelve. Rondas completas > reps heroicas."

### Sesión D — Mixta: primer T2B y movilidad CF2/CF3 (todas las fases)

- Tipo: mixta + movilidad overhead/front rack. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Front Squat` | 3×5 (35/25 kg) | 3×5 (40/25 kg) | 4×5 (45/30 kg) | 2×5 suave | 120 s | Front rack con CF3 validada en caliente; codos altos todo el recorrido |
| `Toes to Bar Strict` | 3×3 | 3×5 | 4×5 | 2×3 suave | 90 s | Primer T2B estricto del programa; si no llega, `Hanging Leg Raise` + toque de pies a la altura posible; sin balanceo de kipping |
| `Farmer Carry` | 3×40 m | 4×40 m | 4×40 m (carga +) | 2×30 m | 60 s | Grip que reta sin deformar la postura |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 8 `Dumbbell Thruster` (ligero) + 8 `Knee Raise` + 10 `Air Squat` | 60-90 s entre rondas |
| F2 | Benchmark: Cindy escalada (hito) | `Cindy` AMRAP 20' en Scaled: 5 `Band Assisted Pull Up` (o `Jumping Pull Up` controlado) + 10 `Push Up` + 15 `Air Squat` | Primera ejecución del benchmark en sesión: logro "Cindy escalada"; la marca que alimenta el test es la del envío de test |
| F3 | AMRAP 12' | 6 `Toes to Bar Strict` + 8 `Wall Ball` (6/4 kg) + 8 `Box Jump` | Primer AMRAP con T2B; core bajo fatiga |
| F4 | 2 rondas suaves | 5 `Knee Raise` + 8 `Air Squat` | Movimiento; test al final del nivel |

**Tips de sesión:** "Cindy escalada de F2 es el ensayo general del test: anota rondas y escalado usado. Y en el T2B manda el core, no el columpio — todavía."

## Test de nivel N4 (resumen)

Ítems auto-evaluados al final de la Fase 4 (Recuperación), con 2-3 min de descanso entre pruebas, de menor a mayor interferencia. Superar todos los mínimos de fuerza + benchmark + **ambas puertas** (modo del programa: `strict_gates` para puertas; regla §1.2/§8).

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Pull Up (estricta) | 2 reps | 5 reps | gte | reps | fuerza |
| 2 | Back Squat (60/40 kg) | 5 reps | 8 reps | gte | reps | fuerza |
| 3 | Toes to Bar Strict | 5 reps | 10 reps | gte | reps | fuerza |
| 4 | Cindy (benchmark escalado) | 8 rondas | 12 rondas | gte | rounds | benchmark |
| 5 | Puerta CF2 (overhead) | superada | — | — | bool | puerta_movilidad (gateCode CF2) |
| 6 | Puerta CF3 (front rack) | superada | — | — | bool | puerta_movilidad (gateCode CF3) |

Notas del test:
- Ítem 1: la prueba admite asistencia mínima con banda fina si el usuario aún no enlaza 2 estrictas limpias, pero **el logro de "primera dominada estricta" exige banda cero**; el objetivo de 5 estrictas es la base mínima que N5 exige antes de introducir kipping (§1.7-CrossFit §10.1).
- Ítem 4: `type: benchmark` sobre `Cindy` (id 4, AMRAP 20', timeCap 1200 s: 5 `Pull Up` + 10 `Push Up` + 15 `Air Squat` por ronda). Modalidad **Scaled**: `Band Assisted Pull Up` o `Jumping Pull Up` controlado. Marca en `UserBenchmarkAttempt` con `scalingUsed = scaled` (auto-evaluación, spec §7).
- Contraste con referencias de catálogo (consulta 2026-10-10): Cindy RX beginner = 8 rondas M / 7 rondas F; intermediate = 12 M / 11 F. Al ser escalada, el mínimo se fija en beginner RX M (8 rondas) y el objetivo en intermediate RX M (12 rondas) — coherente con el criterio del lote 01-03 (mínimo laxo, objetivo en intermediate).
- Ítems 5-6: criterios textuales exactos de la tabla de puertas de arriba; `failedGates` se registra en `TestResult` si fallan (fuerza superada + puerta fallada = `mobility_blocked`, ciclo corto de movilidad sin repetir fuerza).

## Logros del nivel N4

**De test:**
- "Primera dominada estricta" — `type: test`, `tier: epico`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Pull Up (estricta)`. Celebra la primera dominada libre de la vida del usuario.
- "Sentadilla de 60/40 kg" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Back Squat (60/40 kg)`.
- "Primer T2B estricto de test" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Toes to Bar Strict`.
- "Cindy escalada registrada" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Cindy (benchmark escalado)`.
- "Puerta CF2 superada" / "Puerta CF3 superada" — `type: test` vinculado a puerta (mobility gate passed). `tier: raro`.

**De hito:**
- "Banda fina, dominada cerca" — primer `Band Assisted Pull Up` con la banda más fina disponible, recorrido completo. `tier: comun`.
- "Negativa de 6 segundos" — primer `Negative Pull Up` con bajada de 6 s controlada. `tier: comun`.
- "Primer Toes to Bar" — primer `Toes to Bar Strict` con contacto pies-barra, sin balanceo. `tier: raro`.
- "Primer wall ball" — primer `Wall Ball` a diana con sentadilla profunda válida (CF1). `tier: comun`.
- "Primer bench press" — primer `Bench Press` con barra de 30/20 kg y técnica validada. `tier: comun`.

---

# NIVEL 5 — Motor y Gimnásticos

- **Título:** Motor y Gimnásticos — Kipping, dobles y power clean
- **Objetivo:** introducir el kipping pull-up **solo sobre base estricta** (regla §1.7: ≥5 estrictas; si el test N4 salió por debajo, el usuario sigue en progresión estricta hasta conseguirlas), consolidar `Toes to Bar`, estrenar la progresión de dobles (`Single Unders` → `Double Unders`), trabajar `Power Clean` técnico con barra ligera (requiere CF3 superada) y meter metcons de 3 movimientos con AMRAP de 12-15'. Al superar N5, el usuario tiene motor, tracción dinámica y primer O-lift: está a un nivel del umbral RX.
- **Requisitos de entrada:** mínimos del test N4 (incl. CF2 y CF3 superadas).
- **skillFocus:** `Kipping pull-up sobre base estricta, progresión a Double Unders y primer Power Clean técnico`
- **Puertas evaluadas:** ninguna. Se siembra CF6 (`Wrist Push-Up` con más volumen + apoyo en extensión) y CF7 (bisagra bajo fatiga controlada en WODs con swing) en Bloque 0 y sesiones, sin evaluación — se evalúan en N6.

## Bloques comunes del Nivel 5

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N4 con foco nuevo: muñeca en extensión (siembra CF6) y cadera/puente para kipping (control, no hiperextensión). Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 10 + 8/lado + 8 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 45 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` | 15 + 15 | 15 + 15 | 15 + 15 | 12 + 12 suave |
| Muñeca (CF6, foco nuevo) | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` + apoyo en extensión | 2×8 + 30 s + 30 s + 30 s | 2×10 + 30 s + 30 s + 45 s | 2×10 + 45 s + 45 s + 60 s | 2×8 suave + 30 s + 30 s + 45 s |
| Comba (foco nuevo) | `Single Unders` | 30 s | 45 s | 60 s | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en muñeca y hombro antes del press; `Deep Squat Hold` antes del squat.
- **B (Skill):** `Shoulder Dislocate` extra ×5 + `Dead Hang` ×20 s antes de la barra; ensayo CF3 (front rack con barra vacía) antes del `Power Clean`.
- **C (Metcon):** énfasis en muñeca (C&J en el WOD) y en comba (`Single Unders` en caliente).
- **D (Mixta):** énfasis en cadera y torácica; la descarga se amplía.

### Bloque postural (prehab — selección N5)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. Progresión: subir una serie al ejercicio más fácil cuando la calidad se mantiene dos sesiones seguidas; nunca al fallo.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones con comba, antebrazo y gemelos). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N5)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Power clean de técnica con 30/20 kg (tall clean + `Clean Practice`); kipping en bloques cortos (tope 20 reps/sesión) solo si hay ≥5 estrictas; `Double Under Practice` en dosis de 10-15 intentos; T2B estricto consolidado |
| 2 — Progresión | 80% | Power clean 40/25 kg; primeros EMOM con kipping pull-up; primeros sets de 2-3 `Double Unders`; primer AMRAP de 15'; Annie escalada como hito de sesión C |
| 3 — Intensificación | 95% | Power clean 50/35 kg ensayando el gesto del test N6; kipping en AMRAP con tope de volumen; `Double Unders` en singles sueltos dentro de WODs cortos; ensayo de test integrado |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4); test de nivel al final |

Dosificación del kipping (regla §1.7 §10.1): volumen total de kipping por sesión limitado a 20-30 reps, con al menos una sesión completa entre exposiciones de kipping intenso (traducción de 48-72 h a lógica sin calendario). Si aparece dolor de hombro matinal >2/10, se congela el kipping un ciclo y se vuelve a estrictas + negativas.

## Sesiones del Nivel 5

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: squat, press y bisagra de trabajo (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 55-65 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (60/40 kg) | 4×5 (65/45 kg) | 5×3 (70/50 kg) | 2×5 suave (55/40 kg) | 150 s | Sigue la línea de N4; pausa de 1 s abajo en F2-F3 para honestidad de profundidad |
| `Shoulder Press` (también `Strict Press`) | 4×5 (30/20 kg) | 4×5 (32,5/22,5 kg) | 5×3 (35/25 kg) | 2×5 suave | 120 s | Estricto siempre: sin impulso de piernas; prepara el overhead del N7 (S2OH) |
| `Romanian Deadlift` | 3×8 (50/35 kg) | 3×8 (55/40 kg) | 3×6 (60/45 kg) | 2×6 suave | 120 s | Bisagra de trabajo con barra; CF5+CF7 en cada rep; nunca en fatiga alta (§10.2) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 10' | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Box Jump` + 8 `Sit-up` | Bisagra + cadera; ritmo conversacional |
| F2 | AMRAP 12' | 10 `Wall Ball` (6-9/4-6 kg) + 6 `Knee Raise` + 8 `Box Jump` | Tres movimientos; core bajo fatiga |
| F3 | AMRAP 15' | 10 `Kettlebell Swing Russian` (24/16 kg) + 6 `Toes to Bar Strict` + 8 `Box Jump Over` | Primer AMRAP de 15'; bisagra bajo fatiga = ensayo CF7 |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Hoy el RDL trabaja la bisagra que el test N6 te pedirá bajo fatiga (CF7): si la lumbar se redondea en la serie, termina la serie. Calidad por encima de kilos."

### Sesión B — Skill: power clean, kipping y dobles (todas las fases)

- Tipo: técnica, series seguidas, barra primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF3). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Power Clean` (de `Tall Clean`/técnica) | 5×3 (30/20 kg) | 5×3 (40/25 kg) | 6×2 (50/35 kg) | 3×3 suave (30/20 kg) | 120 s | Primer O-lift del programa; requiere CF3 superada; recepción alta (power), codos rápidos, front rack sin dolor; si la muñeca protesta, baja carga (§10.3) |
| `Kipping Pull Up` (o `Pull Up` si aún sin base) | 4×3 | 5×3 | 5×5 | 3×3 suave | 90 s | Solo con ≥5 estrictas validadas (§10.1); hollow-arch controlado, sin cuello de cisne; tope de volumen por sesión |
| `Double Under Practice` | 5×10 intentos | 5×12 intentos | 6×15 intentos | 3×10 suave | 60 s | Técnica de muñeca, saltos bajos; acumular intentos buenos, no fallos; `Single Unders` como calentamiento siempre |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 5 `Power Clean` (30/20 kg) + 8 `Knee Raise` | 60-90 s entre rondas; carga técnica, no de fatiga |
| F2 | EMOM 10' | Impares: 3 `Power Clean` (40/25 kg); pares: 8 `Single Unders` + 5 `Double Under Practice` | Primer EMOM con O-lift ligero; descansa el tiempo que sobre |
| F3 | EMOM 12' | Impares: 4-6 `Kipping Pull Up`; pares: 10 `Single Unders` + 6 `Double Under Practice` | Volumen de kipping acotado; si el arco se rompe, vuelve a estrictas |
| F4 | 2 rondas suaves | 4 `Power Clean` (30/20 kg) + 6 `Single Unders` | Técnica relajada |

**Tips de sesión:** "El power clean no se gana con la espalda: es extensión de cadera + tirón rápido + recepción. Con la barra ligera de hoy construye el gesto que N6 cargará y N10+ pesará (§1.7-CrossFit §10.2)."

### Sesión C — Metcon: tripletes y comba (todas las fases)

- Tipo: WOD + core. Duración estimada: 50-60 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Kettlebell Swing Russian` | 2×10 (16/12 kg) | 2×10 (24/16 kg) | 3×8 (24/16 kg) | 2×8 suave | 60 s | Calienta la bisagra para el WOD |
| `Hollow Body Hold` | 3×20 s | 3×30 s | 3×40 s | 2×20 s | 45 s | La postura del kipping y del T2B: costillas abajo, lumbar pegada |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 10 `Kettlebell Swing Russian` (16/12 kg) + 8 `Box Jump` + 8 `Single Unders` | Ritmo conversacional; primer WOD con comba |
| F2 | AMRAP 14' | 8 `Wall Ball` (6-9/4-6 kg) + 6 `Pull Up` (estricta) + 12 `Single Unders` | Tracción estricta en metcon; reparte el agarre |
| F3 | Benchmark: Annie escalada (hito) | `Annie` FOR_TIME en Scaled: 50-40-30-20-10 de `Single Unders` + `Sit-up` | Primera ejecución del benchmark: logro "Annie escalada"; la marca del test se registra en el envío de test |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Annie escalada de F3 es un test de ritmo, no de héroe: los singles deben ser automáticos para que los dobles de F2-F3 de las próximas sesiones salgan solos."

### Sesión D — Mixta: T2B kipping y movilidad CF6/CF7 (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Toes to Bar` | 4×5 | 4×8 | 5×8 | 3×5 suave | 90 s | Ahora con kipping controlado (la base estricta ya está); si el balanceo sustituye al core, vuelve a `Toes to Bar Strict` |
| `Front Squat` | 3×5 (45/30 kg) | 3×5 (50/35 kg) | 4×5 (55/35 kg) | 2×5 suave | 120 s | Mantiene el front rack del power clean; codos altos |
| `Glute Bridge` | 2×15 | 3×15 | 3×15 (pausa 2 s) | 2×12 | 60 s | Glúteo fuerte = cadera que controla el arco del kipping |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 8 `Dumbbell Thruster` (ligero) + 8 `Toes to Bar` + 10 `Air Squat` | 60-90 s entre rondas |
| F2 | 4 rondas | 6 `Power Clean` (40/25 kg) + 8 `Box Jump Over` + 8 `Single Unders` | 60 s entre rondas |
| F3 | AMRAP 15' | 8 `Toes to Bar` + 10 `Kettlebell Swing Russian` (24/16 kg) + 12 `Single Unders` | Motor del umbral RX; bisagra bajo fatiga = CF7 en vivo |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El T2B con kipping nace del hollow: si al acabar el AMRAP no puedes mantener un `Hollow Body Hold` de 20 s, el kipping era columpio, no técnica."

## Test de nivel N5 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Kipping Pull Up | 10 reps | 15 reps | gte | reps |
| 2 | Toes to Bar | 12 reps | 20 reps | gte | reps |
| 3 | Power Clean (40/25 kg) | 5 reps | 8 reps | gte | reps |
| 4 | Annie (benchmark escalado) | 16:00 (960 s) | 12:00 (720 s) | lte | seconds |

Notas del test:
- Ítem 1: si el usuario llegó a N5 sin las 5 estrictas (mínimo de base), este ítem se evalúa como `Pull Up` (estricta) ×5-8 y el kipping sigue bloqueado hasta superarlo (§10.1) — la base estricta es requisito previo, no opcional.
- Ítem 4: `type: benchmark` sobre `Annie` (id 6, FOR_TIME, timeCap 900 s: 50-40-30-20-10 de dobles + `Sit-up`). Modalidad **Scaled**: `Single Unders`. Marca en `UserBenchmarkAttempt` con `scalingUsed = scaled`.
- Contraste con referencias de catálogo (consulta 2026-10-10): Annie RX beginner = 14:00 M / 16:00 F; intermediate = 10:00 M / 11:30 F. Escalada con singles, el mínimo se fija en beginner RX F (16:00) y el objetivo entre intermediate RX F y M (12:00), coherente con el criterio del lote 01-03.
- Ítem 3: criterio de validez = recepción en power (sin squat clean) y front rack sin dolor (CF3); si la técnica se rompe, la rep no cuenta aunque se levante (§10.4).

## Logros del nivel N5

**De test:**
- "Kipping de 10" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Kipping Pull Up`.
- "T2B de 12" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Toes to Bar`.
- "Primer power clean de test" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Power Clean (40/25 kg)`.
- "Annie escalada registrada" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Annie (benchmark escalado)`.

**De hito:**
- "Primer kipping pull-up" — primer `Kipping Pull Up` conectado, arco controlado, sin dolor de hombro. `tier: epico`.
- "Primer doble" — primer `Double Unders` conectado (1-2 seguidos cuentan). `tier: comun`.
- "Dobles de 5" — primer set de 5 `Double Unders` sin fallo. `tier: raro`.
- "T2B kipping en AMRAP" — primer AMRAP completado con `Toes to Bar` (kipping) sin romper el hollow. `tier: comun`.
- "Clean técnico validado" — primer `Power Clean` con recepción y front rack validados por el criterio del test. `tier: comun`.

---

# NIVEL 6 — Umbral RX

- **Título:** Umbral RX — El primer WOD completo RX
- **Objetivo:** consolidar el power clean y estrenar el `Push Jerk` con carga de trabajo, llevar el wall ball a diana RX de referencia, sumar volumen de tracción dinámica y cerrar el tramo con el **primer benchmark ejecutado en RX completo** como test (Helen). Evalúa las últimas puertas del arco medio: CF6 (muñeca en extensión, protege HSPU/handstand de N7+) y CF7 (bisagra lumbar bajo fatiga, protege O-lifts pesados de N10+). Al superar N6, el usuario entra en el tramo RX (N7-N9) con todas las puertas CF1-CF7 superadas.
- **Requisitos de entrada:** mínimos del test N5.
- **skillFocus:** `Push Jerk y power clean de trabajo, wall ball a diana y primer WOD RX completo`
- **Puertas evaluadas:** **CF6** (muñeca en extensión) y **CF7** (bisagra lumbar controlada) — requisito de entrada efectivo del N7 (HSPU, handstand, deadlifts en fatiga, O-lifts). Ítems `type: puerta_movilidad` con `gateCode` CF6/CF7 en el test. Ensayos integrados: bloque muñeca CF6 en Bloque 0 de todas las sesiones (dosis máximas en F3) y bisagra bajo fatiga controlada en los WODs con swing (sesiones A/C).

## Bloques comunes del Nivel 6

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N5 con foco nuevo: extensión de muñeca soportada (ensayo CF6) y bisagra bajo fatiga (ensayo CF7). Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 10 + 8/lado + 8 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 45 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` | 15 + 15 | 15 + 15 | 15 + 15 | 12 + 12 suave |
| Muñeca (CF6, foco nuevo) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 45 s + 45 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×8 suave + 30 s + 45 s |
| Bisagra bajo fatiga (CF7, foco nuevo) | `Dead Bug` + `Bird Dog` + `Kettlebell Swing Russian` (ligero, controlado) | 2×10/lado + 2×8/lado + 2×10 | 2×10/lado + 2×8/lado + 2×12 | 2×12/lado + 2×10/lado + 2×15 | 2×8 suave + 2×8 suave + 2×8 suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en muñeca y hombro antes del jerk; `Deep Squat Hold` antes del squat.
- **B (Skill):** `Shoulder Dislocate` extra ×5 y `Wrist Push-Up` extra ×5 antes de la barra; front rack con barra vacía antes de cleans/jerks (CF3 en caliente).
- **C (Metcon):** énfasis en bisagra bajo fatiga (ensayo CF7) y en tobillo para wall ball; `Single Unders` de calentamiento.
- **D (Mixta):** énfasis en cadera y torácica; apoyo en extensión de muñeca como cierre del Bloque 0 (preparación CF6).

### Bloque postural (prehab — selección N6)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. CF6 se trabaja en doble vía (Bloque 0 + prehab B) porque es la puerta que más bloquea el N7.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N6)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Push jerk de técnica con 30/20 kg; power clean 45/30 kg; wall ball a diana de referencia (2,75/2,50 m aprox., bola 9/6 kg en F3); Helen RX como hito de sesión C (primera toma de contacto, sin exigencia) |
| 2 — Progresión | 80% | Push jerk 40/25 kg; power clean 50/35 kg; primer metcon con wall ball RX; Helen RX repetida como hito con objetivo de mejora de marca |
| 3 — Intensificación | 95% | Jerk + clean a cargas de test; ensayo de CF6/CF7 a dosis máximas; Helen RX como último ensayo antes del test; volumen de tracción dinámica alto pero acotado |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4); test de nivel al final |

## Sesiones del Nivel 6

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: squat, jerk y bisagra (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 55-65 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (65/45 kg) | 4×5 (70/50 kg) | 5×3 (75/55 kg) | 2×5 suave (60/40 kg) | 150 s | La base de todo; pausa de 1 s abajo en F2-F3 |
| `Push Jerk` | 4×3 (30/20 kg) | 4×3 (40/25 kg) | 5×3 (45/30 kg, ensayo test) | 3×3 suave (30/20 kg) | 120 s | Segundo O-lift del programa; impulso de piernas + caída bajo la barra con codos rápidos; bloqueo overhead estable (CF2); si el jerk se convierte en press roto, baja la carga |
| `Romanian Deadlift` | 3×8 (55/40 kg) | 3×6 (60/45 kg) | 3×6 (65/50 kg) | 2×6 suave | 120 s | Bisagra de trabajo; CF7 en cada rep; nunca al fallo ni en fatiga alta (§10.2) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 10' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Wall Ball` (9/6 kg) + 8 `Sit-up` | Primer wall ball a peso RX |
| F2 | AMRAP 12' | 10 `Wall Ball` (9/6 kg) + 6 `Pull Up` (estricta) + 8 `Box Jump` | Tracción estricta en metcon; reparte agarre |
| F3 | AMRAP 15' | 10 `Wall Ball` (9/6 kg) + 6 `Kipping Pull Up` + 10 `Kettlebell Swing Russian` (24/16 kg) | El motor del umbral: bisagra + overhead + tracción dinámica |
| F4 | AMRAP 8' suave | 8 `Wall Ball` (9/6 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El push jerk es el S2OH que el N7 usará con carga: hoy se aprende con barra ligera. Lumbar neutra, codos rápidos, y si la muñeca en recepción protesta >2/10 matinal, se congela el gesto (§10.3)."

### Sesión B — Skill: clean & jerk gimnástico y tracción (todas las fases)

- Tipo: técnica, series seguidas, barra primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF3 en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Power Clean` | 5×2 (45/30 kg) | 5×2 (50/35 kg) | 5×2 (55/40 kg) | 3×2 suave (40/25 kg) | 120 s | Carga de trabajo; dobles limpios y rápidos; front rack sin dolor |
| `Push Jerk` | 4×3 (35/25 kg) | 4×3 (40/25 kg) | 5×3 (45/30 kg) | 3×3 suave | 120 s | Segundo slot del jerk del ciclo; combinación clean+jerk ligera en F3 como semilla de Grace/Elizabeth |
| `Kipping Pull Up` | 4×5 | 5×5 | 5×8 | 3×4 suave | 90 s | Volumen moderado (tope 40 reps/sesión en F3); si el hombro avisa, vuelve a estrictas (§10.1) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 4 `Power Clean` (45/30 kg) + 6 `Push Jerk` (35/25 kg) + 8 `Knee Raise` | 60-90 s entre rondas |
| F2 | EMOM 12' | Impares: 2 `Power Clean` + 2 `Push Jerk` (50/35 kg); pares: 6 `Kipping Pull Up` | Primer C&J ligero en formato reloj; descansa el tiempo que sobre |
| F3 | 21-15-9 | `Dumbbell Clean` (moderado) + `Toes to Bar` + `Single Unders` ×30-20-10 | Semilla de Grace escalada con dobles opcionales en los singles |
| F4 | 2 rondas suaves | 4 `Power Clean` (40/25 kg) + 6 `Toes to Bar` | Técnica relajada |

**Tips de sesión:** "Clean y jerk en el mismo metcon es la letra pequeña de todos los WODs RX que vienen: hoy se practica la transición con cargas que perdonan."

### Sesión C — Metcon: Helen RX y tripletes (todas las fases)

- Tipo: WOD + core. Duración estimada: 50-60 min.
- Bloque 0: variante C (ensayo CF7). Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Kettlebell Swing Russian` | 2×10 (24/16 kg) | 3×8 (24/16 kg) | 3×10 (24/16 kg) | 2×8 suave | 60 s | El peso RX de Helen; calienta la bisagra (CF7) |
| `Kipping Pull Up` | 2×5 | 3×5 | 3×8 | 2×4 | 60 s | Tracción dinámica en caliente |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 8 `Wall Ball` (9/6 kg) + 8 `Box Jump` | Los tres ingredientes de Helen, sin correr aún |
| F2 | Benchmark: Helen RX (hito) | `Helen` en modalidad RX: 3 rondas de 400 m Run + `Kettlebell Swing Russian` ×21 (24/16 kg) + `Pull Up` ×12 | Primera ejecución RX del programa: logro "Helen RX registrada"; marca orientativa, el test la valida en el envío final |
| F3 | AMRAP 15' | 6 `Kipping Pull Up` + 10 `Wall Ball` (9/6 kg) + 10 `Kettlebell Swing Russian` (24/16 kg) | Ensayo del esfuerzo del test; bisagra bajo fatiga = CF7 |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Helen RX de F2 es el hito del tramo entero: si las pull-ups se rompen técnicamente a mitad, la marca RX no cuenta — escala a banda y deja constancia. Primero estricto, luego dinámico, y siempre técnica (§1.7-CrossFit §10.1/§10.4)."

### Sesión D — Mixta: jerks, tracción volumen y movilidad CF6/CF7 (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D (cierre de extensión de muñeca). Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Push Jerk` | 4×3 (35/25 kg) | 4×3 (40/30 kg) | 5×3 (45/30 kg) | 3×3 suave | 120 s | Tercer slot del jerk del ciclo: volumen de técnica |
| `Toes to Bar` | 4×8 | 4×10 | 5×10 | 3×6 suave | 90 s | T2B kipping consolidado; tope de volumen igual que el kipping pull-up |
| `Farmer Carry` | 3×40 m | 4×40 m | 4×40 m (carga +) | 2×30 m | 60 s | Grip y postura; cierra el tramo de carries del programa |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 5 `Power Clean` (45/30 kg) + 10 `Wall Ball` (9/6 kg) + 10 `Air Squat` | 60-90 s entre rondas |
| F2 | 4 rondas | 6 `Kipping Pull Up` + 8 `Wall Ball` (9/6 kg) + 12 `Single Unders` | 60 s entre rondas |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (24/16 kg) + 6 `Kipping Pull Up` + 8 `Box Jump Over` | Último ensayo de motor antes del test |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Cierra el tramo como empezaste: movilidad densa, carries honestos y un metcon que se disfruta. El test de N6 es una celebración del trabajo de N1 a N6, no un examen sorpresa."

## Test de nivel N6 (resumen)

Superar todos los mínimos de fuerza + benchmark RX + **ambas puertas** (modo `strict_gates` para puertas).

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Power Clean (50/35 kg) | 5 reps | 8 reps | gte | reps | fuerza |
| 2 | Push Jerk (45/30 kg) | 5 reps | 8 reps | gte | reps | fuerza |
| 3 | Wall Ball (9/6 kg) | 15 reps | 25 reps | gte | reps | fuerza |
| 4 | Helen (benchmark RX) | 20:00 (1200 s) | 16:00 (960 s) | lte | seconds | benchmark |
| 5 | Puerta CF6 (muñeca extensión) | superada | — | — | bool | puerta_movilidad (gateCode CF6) |
| 6 | Puerta CF7 (bisagra lumbar) | superada | — | — | bool | puerta_movilidad (gateCode CF7) |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Helen` (id 3, FOR_TIME: 3 rondas de 400 m Run + `Kettlebell Swing Russian` ×21 con 24/16 kg + `Pull Up` ×12). Modalidad **RX** (`scalingUsed = rx`): es el primer benchmark RX completo del programa; en pull-ups se admite kipping controlado o estricta, sin banda.
- Contraste con referencias de catálogo (consulta 2026-10-10): Helen RX beginner = 17:00 M / 20:00 F; intermediate = 13:00 M / 15:30 F. Para un RX recién estrenado, el mínimo se fija en beginner RX F (20:00, igual que el timeCap implícito de tolerancia de recién llegado) y el objetivo entre beginner RX M y intermediate RX F (16:00) — coherente con "mínimos conservadores" (§1.6) y con el criterio del lote 01-03.
- Ítems 1-2: criterio de validez = recepción y front rack/overhead sin dolor y sin lumbar rota (CF3/CF2/CF7); rep con técnica rota no cuenta (§10.4).
- Ítem 5-6: criterios textuales exactos de la tabla de puertas; si fuerza superada + puerta fallada → `mobility_blocked` (ciclo corto de movilidad).

## Logros del nivel N6

**De test:**
- "Power clean de 50/35 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Power Clean (50/35 kg)`.
- "Push jerk de 45/30 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Push Jerk (45/30 kg)`.
- "Wall ball RX de 15" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Wall Ball (9/6 kg)`.
- "Primer WOD RX" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Helen (benchmark RX)`. Celebra el primer benchmark completo en RX del usuario.
- "Puerta CF6 superada" / "Puerta CF7 superada" — `type: test` vinculado a puerta. `tier: raro`.

**De hito:**
- "Primer push jerk" — primer `Push Jerk` con recepción bajo la barra y bloqueo overhead estable. `tier: comun`.
- "Clean & jerk ligero" — primer metcon completado con `Power Clean` + `Push Jerk` en la misma sesión (EMOM de sesión B F2). `tier: comun`.
- "Helen RX registrada" — primera ejecución completa de `Helen` en RX en sesión (hito de sesión C F2, antes del test). `tier: epico`.
- "Muñeca de extensión 60 s" — primer apoyo en extensión de 60 s sin dolor matinal >2/10 (ensayo CF6). `tier: comun`.

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N4-N6: todos los ejercicios citados existen en el catálogo con la grafía usada (verificación por SQL 2026-10-10, 833 ejercicios).
- Mejora sugerida (no bloquea): alias didáctico "PVC Overhead Squat" para el `Overhead Squat` con PVC usado en el ensayo CF2 — el ejercicio existe, solo el texto del criterio de puerta menciona el implemento.
- Mejora sugerida (no bloquea): el catálogo tiene `Child` (no `Child's Pose`); el lote 01-03 ya usa `Child's Pose` en descarga, así que este documento mantiene la misma grafía por consistencia. Alinear en el seeder o añadir alias al catálogo.
- Mejora sugerida (no bloquea): la diana de wall ball no es un dato estructurado del ejercicio `Wall Ball`/`Wall Ball Shot`; la altura de referencia (2,75/2,50 m) se describe en texto de nivel y tips. Si el front quiere validarlo, añadir metadata al ejercicio.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Kipping condicionado a base estricta:** el test N4 fija `Pull Up` (estricta) mínimo 2 / objetivo 5, y N5 introduce `Kipping Pull Up` solo si el usuario llegó con ≥5 estrictas (si no, sigue en progresión estricta). El umbral de 5 estrictas es la traducción conservadora de la regla §1.7 §10.1 ("5-8 estrictas") al mínimo de test.
2. **Benchmarks del tramo:** Cindy escalada (N4, continuando el hito de N3), Annie escalada (N5, cubre la progresión de comba) y Helen RX (N6, primer RX completo — el mismo benchmark que N2 en escalado, cerrando el arco del tramo con simetría didáctica). Tiempos/rondas ajustados tras contrastar con las referencias de la BD (ver notas de cada test).
3. **Wall ball a diana RX aplazado a N6:** en N4-N5 se entrena wall ball con diana accesible y bola 6-9/4-6 kg; la diana de referencia RX entra en test en N6 (coherente con CF2: el overhead que la protege se evalúa en N4).
4. **T2B progresión:** `Knee Raise` → `Hanging Leg Raise` → `Toes to Bar Strict` (N4) → `Toes to Bar` con kipping (N5, ya con base estricta) → volumen en N6. El catálogo además ofrece `Toes to Bar Practice` y `Toes to Bar Kipping`, no usados porque la progresión propia ya cubre el camino con menos grafías.
5. **Power clean sin Clean Practice en sesión B de N5:** se usa `Tall Clean` como referencia técnica en el texto pero el ejercicio cargado es `Power Clean` (existe `Clean Practice`, reservado para warm-ups técnicos si el seeder lo prefiere). Decisión: un solo slot de O-lift por sesión para no competir con el volumen de tracción.
6. **Topes de volumen balístico:** 20-30 reps de kipping por sesión en N5, hasta 40 en N6 F3, con una sesión completa mínima entre exposiciones intensas (traducción sin calendario del 48-72 h de §10.1).
7. **Helen RX mínimo 20:00:** coincide con beginner RX F y con el timeCap tolerable de recién RX; el objetivo 16:00 queda entre beginner M (17:00) e intermediate F (15:30). Alternativa considerada y descartada: Grace escalada (carga 60/43 kg RX, demasiado para primer RX de O-lifts) y Cindy RX (volumen de pull-ups estrictas aún no consolidado en N4-N5).
