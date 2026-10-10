# Detalle de contenido — Niveles 7, 8 y 9 — CrossFit Master v1

> Tercer lote del documento fuente del programa CrossFit Master (comando
> `app:create-crossfit-master`). Continuidad directa de
> `docs/detalle_crossfit_niveles_04-06.md` (mismas convenciones, mismas
> puertas CF1-CF7 ya evaluadas). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-10-crossfit-master-design.md` (§4 arco —
> tramo "RX", §5 bloques, §7 tests con benchmarks, §8 puertas CF, §9
> logros, §10 regla §1.7-CrossFit).
>
> **Estado de redacción:** COMPLETO. Niveles 7, 8 y 9 con sus 16 sesiones
> cada uno (4 fases × A/B/C/D), tests con benchmarks RX (Fran, Grace, Josh),
> skillFocus y logros. Sin puertas formales en test: CF1-CF7 ya están todas
> superadas (N2-N6); en este tramo se mantienen en Bloque 0 (acumulativo,
> §1.8) con re-tests informales (decisión 1). Pendiente la validación de
> contenido con el usuario (gate humano).

**Convenciones del documento** (heredadas de los lotes 01-03 y 04-06, sin cambios):

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (§5): A — Fuerza (básico de barra + fuerza + prehab hombro); B — Skill/técnica (gimnástico u O-lift + prehab muñeca); C — Metcon/WOD (WOD de la sesión + prehab lumbar/core); D — Mixta (combinación suave + movilidad + prehab de trío completo).
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab muñeca/hombro/lumbar rotado por sesión: A hombro, B muñeca, C lumbar, D trío suave) → `descarga`. `restAfterBlock` = 60 s en N7-N9 (decisión 2: se mantiene 60 s en este tramo por indicación del proyecto; el blueprint v4 sugería subir a 90 s desde N7).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Singles/series máximas → reps `1`. Dosis `/lado` se computan una vez.
- **RIR:** en N7-N9 se trabaja con 1-2 RIR en fuerza pesada (snatch, deadlift de trabajo); nunca al fallo. En WODs, el fallo técnico marca el escalado (regla §1.7-CrossFit §10.4).
- **Equipamiento asumido N7-N9:** todo lo de N1-N6 + rack de squat + anillas colgantes + barras con rodamiento para O-lifts. Plataformas/paras no necesarias: los O-lifts se reciben en power.
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta 2026-10-10, `SELECT name FROM exercises ORDER BY name`). Nombres literales.
- **Convención de nombres de ítems de test:** todo ítem que corresponda a un ejercicio de catálogo usa su grafía exacta, para que el test, los logros (`requirementKey`) y las referencias casen exacto. Los benchmarks se nombran como nombre propio ("Fran (benchmark RX)").
- **Duraciones estimadas:** salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.

---

## Puertas de movilidad del programa (estado al entrar en N7 — copia textual del lote 01-03)

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| CF1 | Sentadilla profunda pegada | squat, wall ball, cleans | `Deep Squat Hold` 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor | N4 (squat cargado progresivo) | **N3** ✔ |
| CF2 | Overhead (hombro) | press, S2OH, wall ball target, snatch, OHS, HSPU | `Shoulder Dislocate` con banda/PVC ×15 sin compensar la lumbar; `Overhead Squat` con PVC/barra vacía ×10 en profundidad | N5 (S2OH, wall ball a diana) | **N4** ✔ (re-test informal N7) |
| CF3 | Muñeca en front rack | clean, front squat | Front rack con barra vacía 30 s/lado sin dolor ni desviación activa | N5 (cleans) | **N4** ✔ (re-test informal N7) |
| CF4 | Dorsiflexión de tobillo | lunges, wall ball | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón | N4 (lunges, wall ball) | **N3** ✔ |
| CF5 | Cadera/bisagra | deadlift, swings, lunges | Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada | N3 (deadlift con carga, KB swing progresivo) | **N2** ✔ |
| CF6 | Muñeca en extensión | HSPU, handstand | `Wrist Push-Up` 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10 | N7 (HSPU, handstand) | **N6** ✔ (re-test informal N9) |
| CF7 | Bisagra lumbar controlada | deadlifts, O-lifts | `Dead Bug` 2×10/lado + `Bird Dog` 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra | N7 (O-lifts, deadlifts en fatiga) | **N6** ✔ (re-test informal N9) |

Evaluación en este documento: **ninguna puerta formal.** CF1-CF7 están todas superadas al entrar en N7 (la última, CF6/CF7, en el test N6). Se mantienen en Bloque 0 de forma acumulativa (§1.8) a dosis de mantenimiento, con dos re-tests **informales** (ensayo a dosis máximas en Bloque 0, sin ítems `puerta_movilidad` en el test ni estado `mobility_blocked`): CF2/CF3 en N7 (al subir la carga de S2OH con thruster/clean de 60/45 y sembrar snatch) y CF6/CF7 en N9 (antes del deadlift de 100/70 y el Josh RX). Decisión 1.

---

## Regla §1.7-CrossFit en tips (spec §10) — aplicación en N7-N9

1. **Primero estricto, luego dinámico:** la base estricta ya está construida (N4-N5), así que el kipping pull-up y el T2B kipping trabajan con volumen en N7-N9. Lo nuevo que se introduce sigue la misma regla: HSPU kipping **no** aparece en este tramo (exige `Strict HSPU` consolidado — eso es N10+); el chest-to-bar se introduce como patrón antes que como volumen; el muscle-up solo existe como progresión asistida en anillas (transiciones, negativas, false grip), nunca kipping.
2. **Lumbar:** el deadlift de trabajo vuelve a N9 (100/70 kg ensayo) tras dos niveles de bisagra moderada; sigue sin aparecer en fatiga alta, y los O-lifts (snatch) nunca se hacen bajo metcon pesado — en WODs entra como `Power Snatch` ligero o `Dumbbell Snatch` (§10.2).
3. **Muñeca:** el overhead de N8 (OHS, HSPU, snatch) hereda CF6 superada, pero el dolor matinal >2/10 en extensión sigue congelando HSPU y snatch un ciclo (§10.3).
4. **Técnica rota antes que músculo:** en Fran RX, si el thruster se corta o la pull-up se convierte en columpio sin hollow, la marca RX no cuenta: se escala y se anota (§10.4).
5. Descanso entre exposiciones pesadas de bisagra: dejar pasar al menos una sesión completa entre bisagras cargadas exigentes (relevante en N9 con deadlift).

---

# NIVEL 7 — Consolidación RX

- **Título:** Consolidación RX — Fran por primera vez
- **Objetivo:** convertir el umbral RX de N6 en la nueva normalidad: estrenar el **`Thruster` RX (43/29 kg)** y ejecutar **Fran RX**, el WOD más icónico de CrossFit, como hito con celebración; subir el `Power Clean` a 60/45 kg y el `Push Jerk` a 55/40 kg como cargas de trabajo; llevar el kipping pull-up a volumen real (tope 50 reps/sesión) y meter los `Double Unders` en metcons sin tope estricto. Al superar N7, el usuario hace WODs RX de referencia sin escalado.
- **Requisitos de entrada:** mínimos del test N6 (incl. CF6 y CF7 superadas — puertas del arco medio cerradas).
- **skillFocus:** `Fran RX, power clean/push jerk de 60/45 y kipping con volumen real`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF2/CF3:** ensayo a dosis máximas en Bloque 0 de F3 (`Shoulder Dislocate` ×15 + `Overhead Squat` PVC ×10; front rack con barra vacía 30 s/lado), al entrar la barra de 60/45 en el S2OH. Es informativo: si falla, se baja la carga de trabajo y se repite el ensayo; no genera `mobility_blocked`.

## Bloques comunes del Nivel 7

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo de N1-N6 (§1.8: se mantiene toda la movilidad anterior — CF1-CF7 incluidas) a dosis de mantenimiento, con foco nuevo: apertura de cadera para thruster y hombro/overhead reforzado. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 10 + 8/lado + 8 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, re-test informal F3) | `Shoulder Dislocate` + `Band Pull-Apart` + `Overhead Squat` (PVC) | 15 + 15 + 8 | 15 + 15 + 10 | 15 + 15 + 10 (ensayo CF2) | 12 + 12 + 8 suave |
| Muñeca (CF6, mantenido) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 45 s + 45 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×8 suave + 30 s + 45 s |
| Comba (foco nuevo) | `Single Unders` + `Double Under Practice` | 30 s + 3×5 intentos | 45 s + 3×8 intentos | 60 s + 3×10 intentos | 45 s + 2×5 suave |
| Hang (mantenido) | `Dead Hang` + `Scapular Pull Up` | 30 s + 6 | 30 s + 8 | 40 s + 8 | 30 s + 5 suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en sentadilla profunda y apertura de cadera antes del squat y el thruster; `Deep Squat Hold` justo antes del `Back Squat`.
- **B (Skill):** `Shoulder Dislocate` extra ×5 y front rack con barra vacía 20 s/lado antes de la barra (ensayo CF3 en caliente); respiración diafragmática ×5 ciclos al final.
- **C (Metcon):** énfasis en overhead (thruster en el WOD) y comba (`Double Unders` en caliente si ya salen).
- **D (Mixta):** énfasis en cadera y torácica; la descarga se amplía.

### Bloque postural (prehab — selección N7)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. Dosis mantenidas: el prehab ya no sube volumen; su trabajo es sostener el hombro/muñeca/lumbar a través del tramo RX.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática tumbado (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N7)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Thruster 35/25 kg de técnica; power clean 55/40 kg; push jerk 50/35 kg; kipping pull-up en bloques de 5-8 (tope 40/sesión); `Double Unders` en sets sueltos dentro de WODs cortos; primer `Thruster` en metcon |
| 2 — Progresión | 80% | Thruster 40/30 kg; power clean 60/45 kg; push jerk 55/40 kg; primer Fran parcial (thruster 40/30 + `Kipping Pull Up` 21-15-9); dobles en sets de 5-8 dentro de AMRAPs; tope de kipping 50/sesión |
| 3 — Intensificación | 95% | Thruster RX 43/29 kg ensayando el gesto de Fran; cargas de test en PC/PJ; re-test informal CF2/CF3 a dosis máximas; **Fran RX como hito de sesión C**; últimos ensayos de dobles en fatiga |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

Dosificación del kipping: tope 50 reps/sesión en F2-F3, con una sesión completa entre exposiciones intensas; si aparece dolor de hombro matinal >2/10, se vuelve a `Pull Up` (estricta) un ciclo (§10.1).

## Sesiones del Nivel 7

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: bloques comunes con variante y dosis de fase; postural: tabla de la sesión.

### Sesión A — Fuerza: squat, thruster y bisagra (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (70/50 kg) | 4×5 (75/55 kg) | 5×3 (80/60 kg, ensayo test) | 2×5 suave (65/45 kg) | 150 s | Base de todo; pausa de 1 s abajo en F2-F3; CF1 en cada rep |
| `Thruster` | 4×5 (35/25 kg) | 4×5 (40/30 kg) | 5×3 (43/29 kg, ensayo Fran) | 3×3 suave (30/20 kg) | 120 s | Front squat + `Push Press` en un gesto fluido; rack limpio (CF3), bloqueo overhead (CF2); es el movimiento de Fran |
| `Romanian Deadlift` | 3×8 (60/45 kg) | 3×6 (65/50 kg) | 3×6 (70/50 kg) | 2×6 suave | 120 s | Bisagra de mantenimiento CF5+CF7; nunca al fallo (§10.2) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 10' | 8 `Thruster` (35/25 kg) + 8 `Box Jump` + 8 `Sit-up` | Primer thruster en metcon; ritmo conversacional |
| F2 | AMRAP 12' | 10 `Wall Ball` (9/6 kg) + 6 `Kipping Pull Up` + 10 `Double Unders` | Primer WOD con dobles "reales"; si fallan, 10 `Single Unders` sin parar |
| F3 | Fran parcial | 21-15-9 de `Thruster` (40/30 kg) + `Kipping Pull Up` | Ensayo del formato Fran antes de cargar RX; anota tiempo y sensaciones |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El thruster es donde se gana o se pierde Fran: rack que no ahoga, extensión de cadera completa y la barra vuela. Si el press se convierte en grind, la carga es del pectoral, no de Fran."

### Sesión B — Skill: clean & jerk de carga y kipping (todas las fases)

- Tipo: técnica, series seguidas, barra primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF3 en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Power Clean` | 5×2 (55/40 kg) | 5×2 (60/45 kg) | 6×1 (65/45 kg) | 3×2 suave (45/30 kg) | 120 s | Carga de trabajo; singles rápidos y limpios; front rack sin dolor (CF3); si la muñeca protesta, baja carga (§10.3) |
| `Push Jerk` | 4×3 (50/35 kg) | 4×3 (55/40 kg) | 5×2 (60/45 kg) | 3×3 suave (40/25 kg) | 120 s | Impulso + caída bajo la barra; bloqueo overhead estable (CF2); si se convierte en press roto, baja la carga |
| `Kipping Pull Up` | 4×8 | 5×8 | 5×10 (tope 50) | 3×5 suave | 90 s | Volumen real del RX; hollow-arch controlado; reparte agarre con supino ocasional |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 3 `Power Clean` (55/40 kg) + 5 `Push Jerk` (50/35 kg) + 10 `Air Squat` | 60-90 s entre rondas; C&J en metcon con carga que perdona |
| F2 | EMOM 12' | Impares: 2 `Power Clean` (60/45 kg); pares: 10 `Kipping Pull Up` | Descansa el tiempo que sobre; ritmo sostenible |
| F3 | EMOM 14' | Impares: 1 `Power Clean` + 2 `Push Jerk` (60/45 kg); pares: 12 `Double Unders` | Primer EMOM con dobles oficiales; si fallan, 12 `Single Unders` |
| F4 | 2 rondas suaves | 4 `Power Clean` (45/30 kg) + 6 `Toes to Bar` | Técnica relajada |

**Tips de sesión:** "Clean y jerk a 60/45 es exactamente la mitad de Grace: cada rep de hoy es una rep que Grace no te cobrará cara. Técnica primero, el reloj después (§1.7-CrossFit §10.4)."

### Sesión C — Metcon: Fran RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 50-60 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Thruster` (caliente) | 2×5 (35/25 kg) | 2×3 (40/30 kg) | 2×3 (43/29 kg) | 2×5 suave (30/20 kg) | 60 s | Progresión hasta la carga del WOD de la sesión |
| `Hollow Body Hold` | 3×30 s | 3×40 s | 3×40 s | 2×20 s | 45 s | La postura que protege el kipping bajo fatiga |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 10 `Wall Ball` (9/6 kg) + 8 `Toes to Bar` + 8 `Box Jump Over` | Triple RX sin barra; ritmo alto |
| F2 | Benchmark: **Fran RX (hito)** | `Fran` en modalidad RX: 21-15-9 de `Thruster` (43/29 kg) + `Pull Up` | **Primera ejecución RX del WOD más icónico de CrossFit: logro "Primera Fran RX" con celebración especial.** Pull-ups admitidas estrictas o kipping controlado; si el thruster se corta o la pull-up muere, escala carga y deja constancia (§10.4) |
| F3 | Fran RX (re-ensayo) | Igual que F2 | Segunda toma para mejorar marca antes del test; solo si F2 salió RX válida |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Fran es corta y mentirosa: 21-15-9 suena a nada y pesa como ningún AMRAP. Divide 8-7-6 / 5-4-2 si es tu primera vez, y recuerda: la marca solo cuenta si la técnica aguanta (§1.7-CrossFit §10.4)."

### Sesión D — Mixta: T2B, dobles y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Toes to Bar` | 4×10 | 4×12 | 5×12 | 3×6 suave | 90 s | T2B kipping consolidado en WODs; tope compartido con el kipping pull-up |
| `Clean and Jerk` (ligero) | 3×2+2 (45/30 kg) | 3×2+2 (50/35 kg) | 4×1+1 (55/40 kg) | 2×2 suave | 120 s | Primer `Clean and Jerk` completo (unidad del Grace futuro); clean recibido en power + `Push Jerk` |
| `Double Unders` | 5×5 | 5×8 | 6×10 | 3×5 suave | 60 s | Sets cortos sin fallo; acumular volumen bueno, no tropezones |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 6 `Dumbbell Thruster` (ligero) + 8 `Toes to Bar` + 10 `Single Unders` | 60-90 s entre rondas |
| F2 | AMRAP 14' | 8 `Kettlebell Swing Russian` (24/16 kg) + 6 `Kipping Pull Up` + 12 `Double Unders` | Primer AMRAP largo con dobles; bisagra bajo fatiga (CF7) |
| F3 | 21-15-9 | `Dumbbell Clean` (moderado) + `Toes to Bar` + `Double Unders` ×15-10-5 | Formato Fran con variedad; los dobles descuentan en cada ronda |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los dobles se ganan en la sesión D: sets cortos, cuerda corta, muñeca relajada. Cuando 10 salgan como 5, Fran deja de tener secretos."

## Test de nivel N7 (resumen)

Ítems auto-evaluados al final de la Fase 4 (Recuperación), con 2-3 min de descanso entre pruebas, de menor a mayor interferencia.

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Power Clean (60/45 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 2 | Push Jerk (55/40 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 3 | Back Squat (80/60 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 4 | Fran (benchmark RX) | 13:00 (780 s) | 10:00 (600 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Fran` (id 1, FOR_TIME, timeCap 600 s: 21-15-9 de `Thruster` 43/29 kg + `Pull Up`). Modalidad **RX** (`scalingUsed = rx`): pull-ups admitidas estrictas o kipping controlado, sin banda.
- Contraste con referencias de catálogo (consulta 2026-10-10): Fran RX beginner = 10:00 M / 13:00 F; intermediate = 6:30 M / 8:30 F. Para una primera Fran RX tras estrenar el thruster 43/29, el mínimo se fija en beginner RX F (13:00 — el timeCap 600 s del benchmark queda como techo duro) y el objetivo en beginner RX M (10:00). El intermediate (6:30/8:30) queda fuera de este tramo: es terreno del tramo élite (N10+). Coherente con "mínimos conservadores" (§1.6) y con el criterio del lote 04-06.
- Ítems 1-3: criterio de validez = recepción y front rack/overhead sin dolor y sin lumbar rota (CF2/CF3/CF7); rep con técnica rota no cuenta (§10.4).
- El re-test informal CF2/CF3 ya se hizo en Bloque 0 de F3; aquí no se repite como ítem.

## Logros del nivel N7

**De test:**
- "Primera Fran RX" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Fran (benchmark RX)`. El hito central del nivel: el WOD más icónico de CrossFit completado en RX.
- "Power clean de 60/45 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Power Clean (60/45 kg)`.
- "Push jerk de 55/40 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Push Jerk (55/40 kg)`.
- "Sentadilla de 80/60 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Back Squat (80/60 kg)`.

**De hito:**
- "Primer thruster RX" — primer `Thruster` con 43/29 kg y técnica válida (rack CF3 + bloqueo CF2). `tier: epico`.
- "Dobles en metcon sin tope" — primer AMRAP completado con `Double Unders` oficiales (sets acotados dentro del WOD, sin volver a singles). `tier: raro`.
- "Kipping de 20 seguidas" — primer set de 20 `Kipping Pull Up` sin romper el hollow. `tier: raro`.
- "Clean & jerk completo" — primer `Clean and Jerk` con clean recibido en power y jerk bloqueado en la misma unidad. `tier: comun`.
- "Re-test CF2/CF3 superado" — re-test informal de overhead y front rack a dosis máximas en F3, sin síntomas. `tier: comun`.

---

# NIVEL 8 — Potencia

- **Título:** Potencia — Snatch, Overhead Squat y primeros HSPU
- **Objetivo:** abrir el overhead pesado del programa con criterio: introducir el **snatch técnico** (`Tall Snatch` → `Hang Snatch` → `Power Snatch`, dejando el `Snatch` completo de suelo para el tramo élite), el `Overhead Squat` con carga de trabajo (requiere CF2 superada — ya validada), los **primeros Handstand Push Up progresivos** (`Wall Walk` → apoyo en pared → `Wall Handstand Push Up`/`Strict HSPU` parciales — CF6 superada) y el `Thruster` RX 43/29 como carga estándar de metcons. Benchmark: **Grace RX** (30 C&J a 60/43 kg). Al superar N8, el usuario empuja overhead con carga RX y domina el segundo O-lift.
- **Requisitos de entrada:** mínimos del test N7.
- **skillFocus:** `Snatch técnico, Overhead Squat, primeros Handstand Push Up y Thruster RX como carga estándar`
- **Puertas evaluadas:** ninguna (CF2/CF3/CF6/CF7 superadas y re-chequeadas informalmente). El overhead de N8 hereda CF2 (re-test informal superado en N7); si aparece dolor matinal en muñeca/hombro >2/10, se congela snatch y HSPU un ciclo (§10.3).

## Bloques comunes del Nivel 8

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N7 con foco nuevo: overhead profundo para OHS/snatch (torácica + hombro) y muñeca en extensión reforzada (semilla HSPU). Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, foco nuevo) | `Shoulder Dislocate` + `Band Pull-Apart` + `Overhead Squat` (PVC) | 15 + 15 + 10 | 15 + 15 + 10 | 15 + 15 + 12 | 12 + 12 + 8 suave |
| Muñeca (CF6, foco nuevo) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×12 + 60 s + 60 s | 2×8 suave + 30 s + 45 s |
| Handstand suave (semilla HSPU) | `Wall Walk` (a pared) + `Chest to Wall Handstand` | 2×3 + 20 s | 3×3 + 30 s | 3×4 + 40 s | 2×2 + 20 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 45 s + 2×5 | 45 s + 2×8 | 60 s + 2×10 | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en overhead profundo antes del OHS; `Deep Squat Hold` antes del squat.
- **B (Skill):** `Shoulder Dislocate` extra ×5 y ensayo de `Overhead Squat` con barra vacía antes del snatch; `Wrist Push-Up` extra ×5 antes del HSPU (CF6 en caliente).
- **C (Metcon):** énfasis en hombro (thruster en el WOD) y muñeca; `Double Unders` de calentamiento.
- **D (Mixta):** énfasis en handstand suave y descarga ampliada; apoyo en extensión como cierre (preparación HSPU).

### Bloque postural (prehab — selección N8)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En sesiones con HSPU, el prehab de muñeca/hombro es condición, no opción: es lo que permite que el overhead pesado sea sostenible.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones con snatch/HSPU, añadir `Lat Stretch`). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N8)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Snatch de posiciones (`Tall Snatch` → `Hang Snatch`) con 20/15 kg; OHS con barra vacía → 30/20 kg; HSPU progresión: `Wall Walk` y apoyo en pared; thruster RX 43/29 en metcons cortos; dobles en sets de 8-10 |
| 2 — Progresión | 80% | `Power Snatch` 30/25 kg; OHS 35/25 kg; `Wall Handstand Push Up` (negativa lenta o parciales con colchoneta); primer metcon con OHS ligero; primer `Power Snatch` en EMOM |
| 3 — Intensificación | 95% | Power snatch 40/30 kg ensayando el test; OHS 43/30 kg (carga de Nancy) en fuerza; primeros `Strict HSPU` parciales o `Wall Handstand Push Up` completos; **Grace RX como hito de sesión C**; push jerk 60/43 kg ensayando la carga del test |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (overhead y muñeca a dosis F4); test de nivel al final |

Dosificación del HSPU (regla §1.7 §10.1): solo progresiones estrictas/negativas en este nivel — **no hay kipping HSPU** (exige base estricta consolidada: eso es N10+). Tope de volumen de pared: 30-40 reps/sesión entre `Wall Walk`, holds y HSPU parciales.

## Sesiones del Nivel 8

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: squat, overhead squat y bisagra (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` | 4×5 (75/55 kg) | 4×5 (80/60 kg) | 5×3 (85/65 kg) | 2×5 suave (65/45 kg) | 150 s | Sigue la línea de N7; la base que sostiene el OHS |
| `Overhead Squat` | 4×5 (barra vacía) | 4×5 (30/20 kg) | 5×3 (43/30 kg, ensayo test) | 3×3 suave (20/15 kg) | 120 s | Overhead profundo con CF2 validada; barra sobre el plano medio, activación escapular activa; si la barra se tira hacia delante, baja carga antes que profundidad |
| `Romanian Deadlift` | 3×6 (65/50 kg) | 3×6 (70/55 kg) | 3×5 (75/55 kg) | 2×5 suave | 120 s | Bisagra de mantenimiento; CF7; nunca al fallo (§10.2) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 10' | 8 `Thruster` (43/29 kg) + 8 `Box Jump` + 8 `Sit-up` | Thruster RX como carga estándar; ritmo conversacional |
| F2 | AMRAP 12' | 6 `Overhead Squat` (30/20 kg) + 8 `Toes to Bar` + 10 `Single Unders` | Primer metcon con OHS; carga técnica, agarre ancho estable |
| F3 | AMRAP 14' | 6 `Thruster` (43/29 kg) + 6 `Kipping Pull Up` + 12 `Double Unders` | Triple RX; dobles en fatiga real |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El OHS es el detector de mentiras del overhead: si la barra baila a 30 kg, no es fuerza lo que falta, es posición. PVC y paciencia antes que kilos (§1.7-CrossFit)."

### Sesión B — Skill: snatch, HSPU progresivos y dobles (todas las fases)

- Tipo: técnica, series seguidas, barra primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF2/CF6 en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Power Snatch` (de `Tall Snatch`/`Hang Snatch`) | 5×3 (20/15 kg) | 5×3 (30/25 kg) | 6×2 (40/30 kg, ensayo test) | 3×3 suave (20/15 kg) | 120 s | Tercer O-lift del programa; de posiciones primero (tall → hang → suelo parcial); recepción overhead amplia y estable; **el `Snatch` completo de suelo queda para N10+** (decisión 3); si la muñeca u hombro avisan, baja carga (§10.3) |
| `Wall Handstand Push Up` (progresión: `Wall Walk` → negativa → parcial → completo) | 4×3 (`Wall Walk`) | 4×3-5 (negativas 3-5 s) | 5×2-4 (parciales/completos) | 3×2 suave | 90 s | Primeros HSPU del programa, sobre CF6 superada; rango según nivel (colchoneta/colchonetas apiladas); estricto o negativo — nada de kipping HSPU (§10.1) |
| `Double Unders` | 5×8 | 5×10 | 6×12 | 3×6 suave | 60 s | Sets consistentes sin fallo; el objetivo del nivel es enlazar 20 |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 4 `Hang Snatch` (20/15 kg) + 6 `Wall Walk` + 10 `Air Squat` | 60-90 s entre rondas; snatch ligero en metcon |
| F2 | EMOM 12' | Impares: 2 `Power Snatch` (30/25 kg); pares: 4 `Wall Walk` + 8 `Double Unders` | Primer EMOM con snatch; descansa el tiempo que sobre |
| F3 | 21-15-9 | `Dumbbell Snatch` (alterno, moderado) + `Push Up` + `Double Unders` ×15-10-5 | Semilla de Amanda escalada; un brazo descansa mientras el otro trabaja |
| F4 | 2 rondas suaves | 4 `Hang Snatch` (20/15 kg) + 6 `Single Unders` | Técnica relajada |

**Tips de sesión:** "El snatch es el gesto más técnico del programa: hoy es tall y hang, no suelo. La paciencia de hoy es el Randy del N12. Y en el HSPU manda la muñeca: dolor matinal >2/10 = HSPU fuera un ciclo (§1.7-CrossFit §10.3)."

### Sesión C — Metcon: Grace RX y tripletes (todas las fases)

- Tipo: WOD + core. Duración estimada: 50-60 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Push Jerk` (caliente) | 2×3 (50/35 kg) | 2×3 (55/40 kg) | 2×2 (60/43 kg) | 2×3 suave (40/25 kg) | 60 s | Progresión hasta la carga del WOD; clean ligero previo en F2-F3 |
| `Hollow Body Hold` | 3×40 s | 3×40 s | 3×40 s | 2×20 s | 45 s | Postura bajo fatiga |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 10 `Wall Ball` (9/6 kg) + 6 `Thruster` (43/29 kg) + 8 `Box Jump Over` | El "clean" de Grace practicado como thruster; ritmo alto |
| F2 | 5 rondas | 3 `Power Clean` + 3 `Push Jerk` (55/40 kg) + 12 `Double Unders` | 60-90 s entre rondas; C&J con carga de trabajo |
| F3 | Benchmark: **Grace RX (hito)** | `Grace` en modalidad RX: 30 `Clean and Jerk` (60/43 kg) por tiempo | Primera ejecución RX: logro "Grace RX registrada". Según el propio benchmark, se admite power clean + push jerk; el clean se recibe en power (no hace falta `Squat Clean`) |
| F4 | AMRAP 8' suave | 6 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Grace es 30 repeticiones del ejercicio más caro del mundo: singles rápidos desde el principio ganan a series heroicas que mueren a la 15. Y 60/43 kg es la carga de test del push jerk — cada uno cuenta doble (§1.7-CrossFit §10.4)."

### Sesión D — Mixta: OHS volumen, carries overhead y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D (handstand suave). Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Overhead Squat` (volumen) | 3×5 (30/20 kg) | 4×5 (35/25 kg) | 4×3 (40/30 kg) | 3×3 suave | 120 s | Segundo slot de OHS del ciclo; volumen en posición antes que carga |
| `Wall Handstand Push Up` (o `HSPU Negative`) | 3×4 (negativas) | 4×3 (negativas 5 s) | 4×3 (parciales/completos) | 2×3 suave | 90 s | Segundo slot de HSPU del ciclo; tope compartido de volumen de pared |
| `Overhead Carry` | 3×30 m (20/15 kg) | 3×40 m (25/20 kg) | 4×30 m (30/20 kg) | 2×30 m suave | 60 s | Overhead cargado en marcha; escápulas activas, costillas abajo |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 5 `Dumbbell Thruster` (moderado) + 6 `Toes to Bar` + 8 `Single Unders` | 60-90 s entre rondas |
| F2 | 4 rondas | 6 `Kipping Pull Up` + 8 `Thruster` (40/30 kg) + 10 `Double Unders` | 60 s entre rondas; dobles en fatiga |
| F3 | AMRAP 15' | 8 `Kettlebell Swing Russian` (24/16 kg) + 6 `Wall Ball` (9/6 kg) + 12 `Double Unders` | Motor RX consolidado; bisagra CF7 en el swing |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El carry overhead es el OHS en movimiento: si la barra (o la mancuerna) no se sostiene en marcha, el OHS de mañana tampoco. Construye el overhead en todas las direcciones."

## Test de nivel N8 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Power Snatch (40/30 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 2 | Overhead Squat (43/30 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 3 | Push Jerk (60/43 kg) | 1 rep | 3 reps | gte | reps | fuerza |
| 4 | Grace (benchmark RX) | 10:00 (600 s) | 8:00 (480 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Grace` (id 2, FOR_TIME, timeCap 600 s: 30 `Clean and Jerk` a 60/43 kg). Modalidad **RX** (`scalingUsed = rx`) con power clean + push jerk admitidos (según `scaling_options` del propio benchmark, esa combinación es la ejecución RX estándar).
- Contraste con referencias de catálogo (consulta 2026-10-10): Grace RX beginner = 8:00 M / 10:00 F; intermediate = 5:00 M / 6:30 F. El mínimo se fija en beginner RX F (10:00 — coincidente con el timeCap del benchmark como techo duro) y el objetivo en beginner RX M (8:00). El intermediate (5:00/6:30) queda para el tramo élite: hacer 30 C&J a 60/43 por debajo de 6:30 exige un S2OH que este nivel no construye todavía.
- Ítem 1: criterio de validez = recepción overhead amplia y estable, sin press-out marcado y sin dolor (CF2/CF6); desde hang o suelo, indistinto en este nivel (decisión 3: el snatch completo de suelo es N10+; el test acepta `Power Snatch` recibida en power).
- Ítem 2: profundidad con CF1+CF2 válidas; si la barra se tira hacia delante fuera del plano medio, la rep no cuenta.
- Ítem 3: la carga coincide con el peso RX de Grace: es la validación de que el benchmark es ejecutable rep a rep.

## Logros del nivel N8

**De test:**
- "Grace RX registrada" — `type: test`, `tier: epico`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Grace (benchmark RX)`. Segundo Girl RX completo del programa.
- "Power snatch de 40/30 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Power Snatch (40/30 kg)`.
- "Overhead squat de 43/30 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Overhead Squat (43/30 kg)`.
- "Push jerk de 60/43 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Push Jerk (60/43 kg)`.

**De hito:**
- "Primer snatch" — primer `Power Snatch` recibido en power con bloqueo overhead estable. `tier: epico`.
- "Primer HSPU" — primer `Wall Handstand Push Up` completo (o `Strict HSPU`) con rango mínimo validado y muñeca sin dolor. `tier: legendario`.
- "Dobles de 20" — primer set de 20 `Double Unders` sin fallo. `tier: raro`.
- "OHS de 43/30 en sesión" — primer `Overhead Squat` con la carga RX de referencia (43/30 kg) fuera de test. `tier: raro`.
- "HSPU negativa de 5 segundos" — primera `HSPU Negative` (o `Wall Handstand Push Up` negativa) con bajada de 5 s controlada. `tier: comun`.

---

# NIVEL 9 — Motor Élite

- **Título:** Motor Élite — WODs épicos y camino a las anillas
- **Objetivo:** cerrar el tramo RX con el motor más grande del programa hasta aquí: WODs épicos escalados (Daniel y **Josh RX** como test — OHS 43/29 + pull-ups), volumen alto de tracción dinámica con introducción del **chest-to-bar**, dobles consistentes bajo fatiga (hito "Aguja de Dobles" del catálogo) y la **progresión inicial al muscle-up en anillas** (`False Grip Hang`, ring rows elevados, soporte en anillas, transiciones asistidas y negativas — nunca kipping, eso exige base estricta). Re-test informal de CF6/CF7 en Bloque 0 (la bisagra del deadlift de 100/70 y la muñeca del Josh RX). Al superar N9, el usuario es un atleta RX completo y entra en el tramo élite (N10+).
- **Requisitos de entrada:** mínimos del test N8.
- **skillFocus:** `Motor élite: Hero WODs RX, chest-to-bar y progresión a muscle-up en anillas`
- **Puertas evaluadas:** ninguna formal. **Re-test informal CF6/CF7:** ensayo a dosis máximas en Bloque 0 de F3 (`Wrist Push-Up` 2×10 + apoyo en extensión 60 s; `Dead Bug` 2×10/lado + `Bird Dog` 2×8/lado + swing ligero controlado), antes del deadlift pesado y del Josh RX. Informativo: si falla CF7, el deadlift de trabajo baja 10 kg hasta re-superarla.

## Bloques comunes del Nivel 9

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N8 con foco nuevo: tracción en anillas (false grip) y bisagra reforzada para el deadlift de trabajo. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 30 s/lado + 30 s/lado | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` | 15 + 15 | 15 + 15 | 15 + 15 | 12 + 12 suave |
| Muñeca (CF6, re-test informal F3) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×8 suave + 30 s + 45 s |
| Bisagra (CF7, re-test informal F3) | `Dead Bug` + `Bird Dog` + `Kettlebell Swing Russian` (ligero, controlado) | 2×10/lado + 2×8/lado + 2×10 | 2×10/lado + 2×8/lado + 2×12 | 2×12/lado + 2×10/lado + 2×15 (ensayo CF7) | 2×8 suave + 2×8 suave + 2×8 suave |
| Anillas (foco nuevo) | `False Grip Hang` + `Ring Support Hold` | 2×15 s + 2×15 s | 3×20 s + 2×20 s | 3×20 s + 3×20 s | 2×15 s + 2×15 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 45 s + 2×8 | 60 s + 2×10 | 60 s + 2×12 | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en bisagra (ensayo CF7) antes del deadlift; `Deep Squat Hold` antes del squat.
- **B (Skill):** `False Grip Hang` extra ×10 s y `Scapular Pull Up` ×5 antes de la tracción; `Wrist Push-Up` extra ×5 antes del C2B (agarre).
- **C (Metcon):** énfasis en hombro (OHS/thruster del WOD) y anillas; `Double Unders` de calentamiento.
- **D (Mixta):** énfasis en tracción y descarga ampliada; la movilidad de anillas (false grip) cierra el Bloque 0.

### Bloque postural (prehab — selección N9)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. El trío suave de D sostiene hombro+muñeca+lumbar en la fase de más volumen del tramo.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones con anillas, añadir `Lat Stretch` y extensión de muñeca). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N9)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Deadlift de trabajo 80/60 kg vuelve a la sesión A (tras N7-N8 de bisagra moderada); C2B progresión (`Chest to Bar Practice`/agarre amplio); anillas: false grip, soporte, `Ring Row - Elevated Feet`; Daniel escalado como primer Hero (hito de sesión C); dobles en sets de 10-15 |
| 2 — Progresión | 80% | Deadlift 90/65 kg; `Chest to Bar Pull Up` primeros reps; anillas: `Ring Dip` y `Assisted Muscle Up Transition`/`Muscle Up Negative`; primer WOD con OHS RX en metcon largo; **Aguja de Dobles como hito de sesión D** (benchmark de comba del catálogo) |
| 3 — Intensificación | 95% | Deadlift 100/70 kg ensayando el test; re-test informal CF6/CF7 a dosis máximas; C2B en AMRAP con tope; **Josh RX como hito de sesión C** (segunda y última toma antes del test); volumen de anillas acotado |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (anillas y bisagra a dosis F4); test de nivel al final |

Dosificación del volumen balístico: tope 60 reps/sesión entre kipping pull-up, C2B y T2B en F2-F3; la progresión a anillas sustituye, no suma: cuando entra C2B volumen, baja una serie de kipping pull-up. Regla §10.1: si aparece dolor de hombro matinal >2/10, se vuelve a `Pull Up` estricta un ciclo.

## Sesiones del Nivel 9

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: deadlift, thruster y bisagra pesada (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A (ensayo CF7 en F3). Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Deadlift` | 4×5 (80/60 kg) | 4×3 (90/65 kg) | 5×1-3 (100/70 kg, ensayo test) | 2×3 suave (70/50 kg) | 150 s | Vuelve el pesado del suelo tras dos niveles de bisagra moderada; CF7 validada en cada serie (ensayo informal F3); barra pegada, bloqueo sin hiperextensión; nunca en fatiga alta (§10.2) |
| `Thruster` | 4×5 (43/29 kg) | 4×5 (43/29 kg) | 5×3 (45/30 kg) | 3×3 suave (35/25 kg) | 120 s | Thruster RX como carga de trabajo; el gesto de Fran/Grace aplicado a sets |
| `Romanian Deadlift` | 3×6 (70/55 kg) | 3×6 (75/55 kg) | 3×5 (80/60 kg) | 2×5 suave | 120 s | Bisagra de mantenimiento tras el pesado; una sesión completa entre bisagras exigentes (§10.5) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 8 `Kettlebell Swing Russian` (24/16 kg) + 6 `Thruster` (43/29 kg) + 8 `Box Jump` | Bisagra + overhead; ritmo alto |
| F2 | AMRAP 14' | 8 `Wall Ball` (9/6 kg) + 6 `Chest to Bar Pull Up` (o `Kipping Pull Up` si C2B aún no) + 10 `Double Unders` | Primer metcon con C2B oficial |
| F3 | AMRAP 15' | 9 `Kettlebell Swing Russian` (24/16 kg) + 7 `Kipping Pull Up` + 15 `Double Unders` | El motor del test; bisagra bajo fatiga (CF7 en vivo) |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El deadlift pesado es un visitante de honor, no un inquilino: entra, trabaja a 1-2 RIR y se va. Si la lumbar negocia con la barra en la serie 3, la serie terminó (§1.7-CrossFit §10.2)."

### Sesión B — Skill: chest-to-bar y anillas (todas las fases)

- Tipo: técnica, series seguidas, tracción primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (false grip en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Chest to Bar Pull Up` (de agarre amplio/`Chest to Bar Practice`) | 4×3 | 5×3 | 5×4 | 3×2 suave | 90 s | Primer C2B del programa: misma base kipping, barbilla SOBRE la barra (pecho cerca); si solo llega la barbilla, cuenta como kipping pull-up, no C2B (§10.4) |
| `Ring Row - Elevated Feet` + `False Grip Ring Row` | 3×8 + 3×6 | 4×8 + 3×8 | 4×8 + 4×8 | 3×6 suave | 90 s | Fuerza horizontal en anillas con false grip: el agarre del futuro muscle-up; cuerpo en línea, pecho a los anillas |
| `Assisted Muscle Up Transition` (o `Muscle Up Negative`) | 3×3 (asistida lenta) | 3×4 | 4×3 (o 3×2 negativas 5 s) | 2×2 suave | 120 s | Solo transición asistida/negativa: el `Muscle Up` completo es del tramo élite (§10.1: base estricta primero); pies en el suelo o banda, énfasis en la rotación de muñecas sobre las anillas |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 5 `Kipping Pull Up` + 8 `Ring Row - Feet on Ground` + 10 `Air Squat` | 60-90 s entre rondas; volumen de tracción mixto |
| F2 | EMOM 12' | Impares: 3-4 `Chest to Bar Pull Up`; pares: 5 `Ring Support Hold` (seg) + 8 `Double Unders` | Descansa el tiempo que sobre; soporte en anillas bajo el reloj |
| F3 | EMOM 14' | Impares: 2 `Assisted Muscle Up Transition` + 4 `Kipping Pull Up`; pares: 12 `Double Unders` | Primer EMOM con transición de muscle-up; si los hombros se van, baja a ring rows |
| F4 | 2 rondas suaves | 4 `Kipping Pull Up` + 6 `Ring Row - Feet on Ground` | Técnica relajada |

**Tips de sesión:** "El muscle-up no se persigue: se siembra. False grip, transición lenta, negativa de 5 segundos — durante ciclos y ciclos. Cuando las piezas estén listas, la transición estará tan ensayada que será casi aburrida (§1.7-CrossFit §10.1)."

### Sesión C — Metcon: Daniel escalado y Josh RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 55-65 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Overhead Squat` (caliente) | 2×5 (30/20 kg) | 2×3 (35/25 kg) | 2×3 (43/29 kg) | 2×5 suave | 60 s | Progresión hasta la carga del WOD de la sesión; la primera mitad del Josh |
| `Kipping Pull Up` (caliente) | 2×5 | 2×6 | 2×8 | 2×4 | 45 s | Tracción dinámica en caliente; la segunda mitad del Josh |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 14' | 10 `Wall Ball` (9/6 kg) + 6 `Thruster` (43/29 kg) + 10 `Double Unders` | El motor del OHS RX bajo fatiga |
| F2 | Hero escalado (hito): Daniel | `Daniel` escalado según el benchmark (id 65, FOR_TIME, timeCap 1200 s: carrera + `Pull Up` + `Thruster` 43/29 kg — ver definición del benchmark): pull-ups con banda o volumen reducido y thruster 35/25 kg | Primer Hero WOD del programa: logro "Primer Hero WOD"; la distancia de carrera se corre completa |
| F3 | Benchmark: **Josh RX (hito)** | `Josh` en modalidad RX (id 66, FOR_TIME, timeCap 1800 s: 21-15-9 de `Overhead Squat` 43/29 kg + pull-ups ×42-30-18) | **Hito del nivel: logro "Josh RX registrada".** Segunda y última toma antes del test; pull-ups admitidas kipping controlado |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Josh es Fran con la barra overhead: mismas rondas, mismo dolor, una dificultad más. Divide las pull-ups desde el principio —30-12, 20-10, 12-6 son marcas honestas— y guarda los hombros para la barra."

### Sesión D — Mixta: dobles en fatiga, anillas y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D (false grip como cierre). Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Double Unders` (volumen) | 5×12 | 6×15 | 6×20 | 3×8 suave | 60 s | Sets consistentes; el objetivo del nivel es 50 sin fallo acumulando sets |
| `Ring Dip` (o `Bench Dips` elevadas si aún no) | 3×5 | 4×5 | 4×6 | 2×4 suave | 90 s | Empuje en anillas: el otro pilar del muscle-up; rango honesto, hombros bajo los anillas |
| `Toes to Bar` | 4×10 | 4×12 | 5×12 | 3×6 suave | 90 s | T2B kipping en volumen; tope compartido con kipping y C2B |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 6 `Dumbbell Snatch` (alterno, moderado) + 8 `Toes to Bar` + 10 `Single Unders` | 60-90 s entre rondas |
| F2 | 4 rondas | 6 `Chest to Bar Pull Up` (o kipping) + 8 `Box Jump Over` + 15 `Double Unders` | 60 s entre rondas; dobles con piernas ya cargadas |
| F3 | Benchmark de comba (hito): **Aguja de Dobles** | `Aguja de Dobles` (id 59, `interval_stations`, timeCap 960 s): 4 rondas × 45 s por estación (máx. reps) de `Devil Press` (2×15/10 kg), `Toes to Bar` y `Double Unders`, con 45 s de descanso entre rondas; puntuación = total de reps (dobles = 1) | Hito de comba del catálogo en formato de estaciones: los dobles entran como una de las 3 estaciones bajo fatiga real (tras Devil Press y T2B), no como comba pura. Logro "Aguja de Dobles registrada"; marca en `UserBenchmarkAttempt` |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los dobles bajo fatiga se entrenan con las piernas cansadas: box jumps y C2B antes, comba después. El que salta recto cuando está fresco no sabe combar; el que mantiene la técnica con el lactato alto, sí."

## Test de nivel N9 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Deadlift (100/70 kg) | 3 reps | 5 reps | gte | reps | fuerza |
| 2 | Thruster (43/29 kg) | 5 reps | 10 reps | gte | reps | fuerza |
| 3 | Chest to Bar Pull Up | 3 reps | 8 reps | gte | reps | fuerza |
| 4 | Josh (benchmark RX) | 28:30 (1710 s) | 23:30 (1410 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Josh` (id 66, FOR_TIME, timeCap 1800 s: 21-15-9 de `Overhead Squat` 43/29 kg + pull-ups ×42-30-18, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`): pull-ups admitidas estrictas o kipping controlado; el OHS exige CF1+CF2 válidas en profundidad.
- Contraste con referencias de catálogo (consulta 2026-10-10): Josh RX beginner = 28:30 M / 28:30 F; intermediate = 23:30 M / 23:30 F (referencias unificadas en este benchmark del catálogo). El mínimo se fija en beginner (28:30) y el objetivo en intermediate (23:30) — coherente con el criterio del lote 04-06 (mínimo laxo, objetivo en intermediate), aquí alcanzable porque el usuario ya entrenó OHS RX durante todo el nivel.
- Ítem 1: CF7 validada en el ensayo informal de F3; barra pegada, bloqueo sin hiperextensión; si la lumbar se va, la rep no cuenta (§10.4).
- Ítem 3: criterio de validez = barbilla claramente sobre la barra (contacto pecho-barra o inmediatamente por encima); barbilla en la barra = kipping pull-up, no cuenta como C2B.
- Los re-tests informales CF6/CF7 se hicieron en Bloque 0 de F3; no son ítems del test ni generan `mobility_blocked` (decisión 1). Si CF7 saliera negativa, se baja el deadlift de trabajo 10 kg y se repite el ensayo en el siguiente ciclo.

## Logros del nivel N9

**De test:**
- "Josh RX registrada" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Josh (benchmark RX)`. Cierra el tramo RX con un Hero WOD completo: el usuario es atleta RX.
- "Deadlift de 100/70 kg" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Deadlift (100/70 kg)`.
- "Thruster RX de 10" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Thruster (43/29 kg)`.
- "Primer chest-to-bar de test" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Chest to Bar Pull Up`.

**De hito:**
- "Primer chest-to-bar" — primer `Chest to Bar Pull Up` con la barbilla claramente sobre la barra. `tier: epico`.
- "Primer Hero WOD" — primera ejecución completa de un Hero WOD (Daniel escalado de sesión C F2). `tier: epico`.
- "Aguja de Dobles registrada" — primera ejecución del benchmark `Aguja de Dobles` con dobles oficiales. `tier: raro`.
- "False grip de 20 segundos" — primer `False Grip Hang` de 20 s sin dolor de muñeca/flexores. `tier: comun`.
- "Primer soporte en anillas de 20 segundos" — primer `Ring Support Hold` de 20 s con anillas quietas y hombros activos. `tier: comun`.
- "Primera transición de muscle-up asistida" — primer `Assisted Muscle Up Transition` completo (pie al pecho sobre las anillas, rotación de muñecas incluida). `tier: raro`.
- "Dobles de 50 acumulados" — primer WOD completado acumulando 50+ `Double Unders` sin fallo grave de técnica. `tier: raro`.

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N7-N9: todos los ejercicios citados existen en el catálogo con la grafía usada (verificación por SQL 2026-10-10, `SELECT name FROM exercises ORDER BY name`): `Thruster`, `Power Snatch`, `Tall Snatch`, `Hang Snatch`, `Snatch Practice`, `Snatch Balance`, `Overhead Squat`, `Wall Walk`, `Chest to Wall Handstand`, `Wall Handstand Hold`, `Wall Handstand Push Up`, `Strict HSPU`, `HSPU Negative`, `Chest to Bar Pull Up`, `Chest to Bar Practice`, `False Grip Hang`, `False Grip Ring Row`, `Ring Support Hold`, `Ring Row - Elevated Feet`, `Ring Row - Feet on Ground`, `Ring Dip`, `Assisted Muscle Up Transition`, `Muscle Up Negative`, `Ring Muscle Up Transition`, `Double Unders`, `Double Under Practice`, `Single Unders`, benchmarks `Fran` (id 1), `Grace` (id 2), `Daniel` (id 65), `Josh` (id 66), `Aguja de Dobles` (id 59).
- Mejora sugerida (no bloquea): duplicados de grafía en catálogo detectados al elegir nombres — `Burpee Pull Up`/`Burpee Pull-up`, `Face Pull`/`face pull`, `Dip`/`Parallel Bar Dips`, `Ring Dip`/`Ring Dips`, `Handstand Push Up`/`Strict HSPU`. Este documento usa la primera grafía canónica de cada par; convendría normalizar el catálogo (o aceptar alias) antes de que el seeder fije `requirementKey`.
- Mejora sugerida (no bloquea): los `movements` JSON de `Fran` y `Grace` están vacíos en la tabla `benchmark` (la estructura vive en `description`). Si el front consume `movements`, conviene poblarlo; si consume `description`, sin impacto.
- Mejora sugerida (no bloquea): `Hang Power Snatch` no existe en catálogo (sí `Hang Snatch` y `Power Snatch`); este documento usa `Hang Snatch` como peldaño técnico. Si el seeder prefiere la grafía combinada, habría que crear el ejercicio.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Re-tests informales de puertas (CF2/CF3 en N7, CF6/CF7 en N9):** las siete puertas están superadas al entrar en el tramo; en vez de re-test formales con `mobility_blocked`, se programan ensayos a dosis máximas en Bloque 0 de F3 con consecuencia de ajuste de carga (informativo). Alternativa descartada: re-test formal en el test de N9 — añadiría dos ítems `puerta_movilidad` a un test ya largo y confundiría el cierre del tramo RX.
2. **`restAfterBlock` = 60 s en N7-N9:** se mantiene por indicación explícita del proyecto (mismo criterio que N1-N6). El blueprint v4 sugería 90 s desde N7 por volumen; si al generar los niveles el `TrainingTimeCalculator` devuelve duraciones desbordadas, la salida es recortar volumen de WOD, no subir el descanso.
3. **Snatch técnico sin `Snatch` completo de suelo:** N8 introduce el snatch por posiciones (`Tall Snatch` → `Hang Snatch` → `Power Snatch`), dejando el `Snatch`/`Squat Snatch` completo de suelo para el tramo élite (N10+, donde también viven `Randy` y `Amanda`). El test N8 acepta `Power Snatch` desde hang o suelo. Razón: el snatch de suelo es el O-lift más técnico y no aporta al objetivo del nivel (overhead RX) tanto como el OHS y el HSPU.
4. **HSPU sin kipping en todo el tramo:** la regla §1.7 §10.1 exige base estricta antes que dinámico; el HSPU estricto se estrena en N8 como progresión y el kipping HSPU queda para N10+. El `Kipping Handstand Push Up` existe en catálogo pero no se usa en este documento.
5. **Benchmarks del tramo:** Fran RX (N7 — el icónico, mínimo 13:00/objetivo 10:00, contrastado con beginner F/M), Grace RX (N8 — coherente con la línea C&J de N6-N7, mínimo 10:00/objetivo 8:00, contrastado con beginner F/M) y Josh RX (N9 — Hero con OHS 43/29, que cierra el arco del overhead abierto en N8; mínimo 28:30/objetivo 23:30, beginner/intermediate del propio catálogo). Alternativas consideradas y descartadas: Diane (exige HSPU RX recién estrenados en N8 — injusto como test), Karen (150 wall balls: motor sí, pero no cierra el arco de overhead), Angie (100 pull-ups/push-ups: volumen extremo para cerrar tramo), Elizabeth (clean 60/43 con barra desde suelo: técnica aún no trabajada).
6. **Hito "Aguja de Dobles" en N9:** se usa el benchmark de comba del propio catálogo (id 59, `interval_stations`: 4 rondas × 45 s por estación de `Devil Press` 2×15/10, `Toes to Bar` y `Double Unders`, 45 s entre rondas) como cierre de la progresión de dobles introducida en N5, en vez de inventar un WOD de comba ad hoc. Dobles consistentes en fatiga = el objetivo del tramo, y el benchmark da referencias y timeCap gratis.
7. **Muscle-up solo como progresión asistida en anillas (N9):** `False Grip Hang`, ring rows, soporte, `Ring Dip`, `Assisted Muscle Up Transition` y `Muscle Up Negative`; el `Muscle Up`/`Ring Muscle Up` completo es del tramo élite. El catálogo ofrece además `Muscle Up Progression` y `Ring Muscle Up Transition` (no usados para no duplicar grafías de transición — decisión reversible en el seeder).
8. **Deadlift pesado solo en N9:** tras N6 el deadlift quedó en mantenimiento (RDL) durante N7-N8 para dar espacio a O-lifts y overhead; vuelve a 100/70 kg en N9 como cierre de fuerza del tramo, con CF7 re-chequeada informalmente la misma fase.
9. **Josh RX en N9 validado por el controller (ruling):** defensible frente al arco §4 porque §4 es no rígido, el mínimo 28:30 = beginner del propio benchmark y OHS RX se consolida en N8.
