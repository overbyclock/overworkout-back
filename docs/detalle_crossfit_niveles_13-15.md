# Detalle de contenido — Niveles 13, 14 y 15 — CrossFit Master v1

> Quinto lote del documento fuente del programa CrossFit Master (comando
> `app:create-crossfit-master`). Continuidad directa de
> `docs/detalle_crossfit_niveles_10-12.md` (mismas convenciones, mismas
> puertas CF1-CF7 ya evaluadas). Especificación de autoridad:
> `docs/superpowers/specs/2026-10-10-crossfit-master-design.md` (§4 arco —
> tramo "Competición", §5 bloques, §7 tests con benchmarks, §8 puertas CF,
> §9 logros, §10 regla §1.7-CrossFit).
>
> **Estado de redacción:** COMPLETO. Niveles 13, 14 y 15 con sus 16 sesiones
> cada uno (4 fases × A/B/C/D), tests con benchmarks RX (Amanda, Daniel, DT
> a fondo), skillFocus y logros. Sin puertas formales en test: CF1-CF7 ya
> están todas superadas (N2-N6); se mantienen en Bloque 0 (acumulativo,
> §1.8) a dosis de mantenimiento, con re-tests informales (decisión 1).
> Pendiente la validación de contenido con el usuario (gate humano).

**Convenciones del documento** (heredadas de los lotes 01-03, 04-06, 07-09 y 10-12, sin cambios):

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (§5): A — Fuerza (básico de barra + fuerza + prehab hombro); B — Skill/técnica (gimnástico u O-lift + prehab muñeca); C — Metcon/WOD (WOD de la sesión + prehab lumbar/core); D — Mixta (combinación suave + movilidad + prehab de trío completo).
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab muñeca/hombro/lumbar rotado por sesión: A hombro, B muñeca, C lumbar, D trío suave) → `descarga`. `restAfterBlock` = 60 s en N13-N15 (decisión 2: se mantiene 60 s por indicación del proyecto, mismo criterio que N1-N12).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas. Singles/series máximas → reps `1`. Dosis `/lado` se computan una vez.
- **RIR:** en N13-N15 se trabaja con 1-2 RIR en fuerza pesada (back squat, front squat, deadlift, C&J, squat snatch); nunca al fallo. En WODs, el fallo técnico marca el escalado (regla §1.7-CrossFit §10.4). En O-lifts bajo metcon solo carga técnica moderada — nunca pesado en fatiga (§10.2), con la única excepción heredada de DT RX ya justificada en el lote 10-12.
- **Equipamiento asumido N13-N15:** todo lo de N1-N12 + rack de squat + anillas + barras con rodamiento + GHD (opcional: puede sustituirse por `Sit-up` + `Back Extension`). Sin plataformas/paras necesarias.
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta 2026-10-10, `SELECT name FROM exercises ORDER BY name`). Nombres literales.
- **Convención de nombres de ítems de test:** todo ítem que corresponda a un ejercicio de catálogo usa su grafía exacta, para que el test, los logros (`requirementKey`) y las referencias casen exacto. Los benchmarks se nombran como nombre propio ("Amanda (benchmark RX)").
- **Duraciones estimadas:** salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.

---

## Puertas de movilidad del programa (estado al entrar en N13 — copia textual del lote 01-03)

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| CF1 | Sentadilla profunda pegada | squat, wall ball, cleans | `Deep Squat Hold` 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor | N4 (squat cargado progresivo) | **N3** ✔ |
| CF2 | Overhead (hombro) | press, S2OH, wall ball target, snatch, OHS, HSPU | `Shoulder Dislocate` con banda/PVC ×15 sin compensar la lumbar; `Overhead Squat` con PVC/barra vacía ×10 en profundidad | N5 (S2OH, wall ball a diana) | **N4** ✔ |
| CF3 | Muñeca en front rack | clean, front squat | Front rack con barra vacía 30 s/lado sin dolor ni desviación activa | N5 (cleans) | **N4** ✔ |
| CF4 | Dorsiflexión de tobillo | lunges, wall ball | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón | N4 (lunges, wall ball) | **N3** ✔ |
| CF5 | Cadera/bisagra | deadlift, swings, lunges | Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada | N3 (deadlift con carga, KB swing progresivo) | **N2** ✔ |
| CF6 | Muñeca en extensión | HSPU, handstand | `Wrist Push-Up` 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10 | N7 (HSPU, handstand) | **N6** ✔ |
| CF7 | Bisagra lumbar controlada | deadlifts, O-lifts | `Dead Bug` 2×10/lado + `Bird Dog` 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra | N7 (O-lifts, deadlifts en fatiga) | **N6** ✔ |

Evaluación en este documento: **ninguna puerta formal.** CF1-CF7 están todas superadas al entrar en N13 (la última, CF6/CF7, en el test N6, con re-tests informales superados desde entonces). Se mantienen en Bloque 0 de forma acumulativa (§1.8) a dosis de mantenimiento, con dos re-tests **informales** (ensayo a dosis máximas en Bloque 0, sin ítems `puerta_movilidad` en el test ni estado `mobility_blocked`): CF2/CF6 en N13 (antes del snatch de 80/60 y los HSPU deficit), CF1/CF3 en N14 (antes del volumen de squat y thrusters de los Heroes), CF6/CF7 en N15 (antes del test combinado). Decisión 1.

---

## Regla §1.7-CrossFit en tips (spec §10) — aplicación en N13-N15

1. **Primero estricto, luego dinámico:** la base ya está construida (kipping HSPU con tope desde N10, MU estricta antes que kipping desde N10). Lo nuevo del tramo respeta la jerarquía: el **HSPU en deficit** llega en N13 solo tras cuatro niveles de HSPU estricto/kipping, y el volumen de MU en sets de 2-3 (nunca al fallo técnico); el **Bar Muscle Up** se estrena como volumen en N14 (la base de MU en anillas está consolidada desde N12) — es la variante dinámica aplicada a un patrón ya dominado.
2. **Lumbar:** el deadlift vuelve al test en N13 (170/120 kg) tras dos niveles de mantenimiento — progresión desde 100/70 del test N9 a través del RDL y el `Deficit Deadlift` de fuerza. En metcon, bisagra solo a cargas técnicas (swings, SDLHP, deadlifts ≤ 100/70 en fatiga) con CF7 vigente.
3. **Muñeca:** el volumen de HSPU (kipping + deficit) y los O-lifts pesados heredan CF6; dolor matinal >2/10 en extensión congela HSPU y jerks un ciclo (§10.3). El false grip del trabajo de anillas vigila los flexores: dolor >2/10 congela la progresión de MU un ciclo.
4. **Técnica rota antes que músculo:** en Amanda, Daniel y DT, si la recepción del snatch se pierde, el thruster no bloquea, la MU se convierte en pez o el HSPU pierde el hollow, la rep/marca RX no cuenta: se escala y se anota (§10.4). En tramo de competición esto es además la regla oficial del deporte.
5. **Descanso entre exposiciones pesadas de bisagra y hombro:** dejar pasar al menos una sesión completa entre exposiciones exigentes (relevante en N13 con deadlift + snatch, y en N15 con C&J pesado + MU volumen + DT el mismo test — se gestiona con 48 h reales entre el ensayo del F3 y el test del F4, sin tocar la estructura por fases).

---

# NIVEL 13 — Fuerza Competición

- **Título:** Fuerza Competición — Deadlift de test, Snatch 80/60, C&J 90/60 y Amanda RX
- **Objetivo:** el nivel de la barra más pesada del programa: el **deadlift vuelve al test (170/120 kg)** tras la progresión de fuerza del tramo avanzado, el **`Squat Snatch` sube a 80/60 kg** y el **`Clean and Jerk` a 90/60 kg** — las marcas élite de potencia olímpica del programa —, el **HSPU en deficit** (`Deficit Handstand Push Up`) añade rango al overhead ya consolidado, y el primer benchmark del tramo de competición: **Amanda RX** (9-7-5 de `Ring Muscle Up` + `Squat Snatch` 60/43 kg). Al superar N13, el usuario compite en la modalidad de fuerza: levanta pesado y sostiene técnica RX bajo presión de cronómetro.
- **Requisitos de entrada:** mínimos del test N12 (incl. Nate RX 5 rondas, MU ×5 y C&J 70/47.5 ×5).
- **skillFocus:** `Fuerza máxima: deadlift de test, squat snatch 80/60, clean & jerk 90/60, HSPU deficit y Amanda RX`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF2/CF6:** ensayo de `Shoulder Dislocate` ×15 + `Overhead Squat` PVC ×10 y `Wrist Push-Up` 2×10 + apoyo en extensión 60 s en Bloque 0 de F3, antes del snatch de 80/60 y el primer bloque de HSPU deficit. Informativo: si falla CF2, el snatch pesado se recibe en power un ciclo; si falla CF6, el HSPU deficit baja a kipping sin deficit; no genera `mobility_blocked`.

## Bloques comunes del Nivel 13

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N12 con foco nuevo: bisagra cargada para el deadlift de test (preparación de cadera y agarre) y apertura dorsal para el overhead pesado. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 10/lado + 10 | 12 + 10/lado + 12 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 40 s/lado + 40 s/lado | 45 s/lado + 45 s/lado | 45 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, re-test informal F3) | `Shoulder Dislocate` + `Band Pull-Apart` + `Overhead Squat` (PVC) | 15 + 15 + 10 | 15 + 15 + 10 | 15 + 15 + 10 (ensayo CF2) | 12 + 12 + 8 suave |
| Muñeca (CF6, re-test informal F3) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×8 suave + 30 s + 45 s |
| Bisagra y agarre (foco nuevo: deadlift de test) | `Jefferson Curl` (ligero) + `Towel Hang` | 2×5 + 2×20 s | 2×5 + 2×25 s | 2×5 + 2×30 s | 2×5 suave + 2×15 s |
| Anillas (mantenido) | `False Grip Hang` + `Ring Support Hold` | 3×20 s + 3×20 s | 3×20 s + 3×20 s | 3×20 s + 3×20 s | 2×15 s + 2×15 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 45 s + 2×10 | 45 s + 2×10 | 60 s + 2×12 | 45 s suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en bisagra (`Jefferson Curl` + `Towel Hang`) y sentadilla antes del deadlift y el back squat; `Deep Squat Hold` justo antes.
- **B (Skill):** `Shoulder Dislocate` extra ×5 y `Snatch Balance` ligero extra ×2 antes del snatch; `Wrist Push-Up` extra ×5 antes del C&J (CF6 en caliente).
- **C (Metcon):** énfasis en overhead (snatch del WOD) y muñeca; comba de calentamiento.
- **D (Mixta):** énfasis en movilidad completa y descarga ampliada; `Overhead Squat` suave con PVC como cierre.

### Bloque postural (prehab — selección N13)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×10 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×8/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×8 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En el nivel de más carga absoluta del programa (deadlift 170/120 kg), el prehab no sube volumen: su trabajo es sostener hombro/muñeca/lumbar a través del ciclo.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones con deadlift/snatch, añadir `Jefferson Curl` suave y `Lat Stretch`). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N13)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Deadlift 140/100 kg; squat snatch 60/45 kg; C&J 70/50 kg; `Deficit Deadlift` técnico 100/70 kg; HSPU deficit ligero (placa fina) ×3; MU en mantenimiento (3×2/sesión en B); Amanda descompuesta (MU sueltos + snatch 50/35 kg) |
| 2 — Progresión | 80% | Deadlift 155/110 kg; squat snatch 70/52 kg; C&J 80/55 kg; `Deficit Deadlift` 120/85 kg; HSPU deficit ×5 con tope 20; primer Amanda escalado (snatch 55/40, MU asistidas) en sesión C; `Deficit Handstand Push Up` entra en WODs |
| 3 — Intensificación | 95% | Deadlift 170/120 kg ensayando el test; squat snatch 80/60 kg ensayo; C&J 90/60 kg ensayo; re-test informal CF2/CF6; **Amanda RX como hito de sesión C** (segunda y última toma antes del test) |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista (bisagra y overhead a dosis F4); test de nivel al final |

Dosificación: 1-2 RIR en todo el trabajo pesado; los singles de squat snatch y C&J van con descanso completo (150-180 s en F3). Una sesión completa entre bisagras pesadas (deadlift A / `Deficit Deadlift` D) y entre overheads pesados (snatch B / C&J C) — §10.5.

## Sesiones del Nivel 13

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: deadlift pesado y back squat (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Deadlift` | 4×5 (140/100 kg) | 4×3 (155/110 kg) | 5×1 (170/120 kg, ensayo test) | 2×3 suave (120/90 kg) | 180 s | El pesado del nivel; bloqueo de rodillas-cadera arriba simultáneo, barra pegada (CF5+CF7); agarre mixto o gancho permitido en F3; 1-2 RIR, nunca al fallo |
| `Back Squat` | 4×5 (100/70 kg) | 4×3 (105/75 kg) | 4×2 (110/80 kg) | 2×3 suave (90/65 kg) | 150 s | Fuerza de mantenimiento sobre la línea del test N11; pausa de 1 s abajo; CF1 en cada rep |
| `Romanian Deadlift` | 3×6 (90/65 kg) | 3×5 (100/70 kg) | 3×5 (110/80 kg) | 2×5 suave | 120 s | Bisagra de mantenimiento; una sesión completa entre bisagras exigentes (§10.5) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 5 `Deadlift` (80/60 kg) + 8 `Box Jump` + 6 `Wall Ball` (9/6 kg) | Bisagra en fatiga a carga técnica (CF7); ritmo conversacional |
| F2 | 4 rondas | 6 `Hang Power Clean` (60/43 kg) + 8 `Air Squat` + 10 `Double Unders` | 60-90 s entre rondas; la barra del deadlift aplicada a la recepción |
| F3 | AMRAP 14' | 3 `Deadlift` (100/70 kg) + 5 `Push Jerk` (60/43 kg) + 9 `Kettlebell Swing Russian` (24/16 kg) | Bisagra pesada-técnica en metcon; tope de deadlift en fatiga 100/70 (§10.2) |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El deadlift de 170/120 kg no se levanta el día del test: se levanta hoy, con 155 y la espalda impecable. La barra pesada premia la paciencia y castiga la prisa: cada rep de hoy es un voto a favor del single del test (§1.7-CrossFit §10.4)."

### Sesión B — Skill: snatch pesado, C&J y anillas (todas las fases)

- Tipo: técnica, series seguidas, barra primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (ensayo CF2/CF6 en caliente en F3). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Squat Snatch` | 5×2 (60/45 kg) | 5×1-2 (70/52 kg) | 6×1 (80/60 kg, ensayo test) | 3×2 suave (50/35 kg) | 150-180 s | La marca élite del snatch: recepción en `Overhead Squat` profundo (CF1+CF2), sin press-out; recepción cortada = `Power Snatch`, no cuenta (§10.4); singles con descanso completo |
| `Clean and Jerk` | 5×1+1 (70/50 kg) | 5×1+1 (80/55 kg) | 6×1 (90/60 kg, ensayo test) | 3×1+1 suave (60/43 kg) | 150 s | `Squat Clean` + `Split Jerk` (o `Push Jerk` si es más sólido) en el mismo minuto; bloqueo overhead decisivo (CF2); 1-2 RIR |
| `Ring Muscle Up` (mantenimiento) | 4×2 | 4×2-3 | 5×2 (tope 12) | 2×2 suave | 120 s | La MU se mantiene a volumen bajo: el foco del nivel es la barra; series de 2 limpias, transición completa (§10.4) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | EMOM 12' | Impares: 2 `Power Snatch` (50/35 kg); pares: 8 `Air Squat` + 8 `Single Unders` | Snatch ligero de suelo en metcon; §10.2 |
| F2 | EMOM 14' | Impares: 1 `Squat Snatch` (60/45 kg); pares: 3 `Ring Muscle Up` + 8 `Double Unders` | El dúo de Amanda a carga de trabajo; si la recepción se pierde, power (§10.4) |
| F3 | 4 rondas | 2 `Squat Snatch` (60/43 kg) + 2 `Ring Muscle Up` + 12 `Double Unders` | 90 s entre rondas; último ensayo del formato Amanda antes del hito de sesión C |
| F4 | 2 rondas suaves | 3 `Hang Snatch` (30/20 kg) + 6 `Single Unders` | Técnica relajada |

**Tips de sesión:** "80/60 en el snatch no es un número: es la prueba de que el overhead (CF2), la sentadilla (CF1) y la bisagra (CF7) llevan once niveles trabajando juntos. Si hoy la barra se siente ligera en el tirón y pesada solo en la mente, estás listo; si al revés, baja 5 kg y reconstruye (§1.7-CrossFit)."

### Sesión C — Metcon: Amanda técnica y Amanda RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 55-65 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Squat Snatch` (caliente) | 2×3 (45/30 kg) | 2×2 (55/40 kg) | 2×2 (60/43 kg) | 2×3 suave | 60 s | Progresión hasta la carga RX del benchmark; recepción completa |
| `Ring Muscle Up` (caliente) | 2×2 | 2×2 | 2×2 | 1×2 | 60 s | La transición en caliente; sin llegar al tope |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 4 `Power Snatch` (50/35 kg) + 4 `Ring Dip` + 10 `Wall Ball` (9/6 kg) | El sabor de Amanda con recepción en power; ritmo alto |
| F2 | Por tiempo (hito intermedio) | `Amanda` escalado: 9-7-5 de `Power Snatch` (55/40 kg) + `Assisted Muscle Up Transition` (o `Ring Muscle Up` suelta) | Primer Amanda completo a carga de trabajo; anota tiempo — es la referencia del RX |
| F3 | Benchmark: **Amanda RX (hito)** | `Amanda` en modalidad RX (id 9, FOR_TIME, timeCap 600 s: 9-7-5 de `Ring Muscle Up` + `Squat Snatch` 60/43 kg, ver definición del benchmark) | **Hito del tramo de competición: logro "Amanda RX registrada".** Los MU en singles rápidos desde el principio; el snatch RX con recepción en squat o se anula (§10.4) |
| F4 | AMRAP 8' suave | 8 `Kettlebell Swing Russian` (16/12 kg) + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Amanda es el benchmark más desigual de los Girls: 21 reps totales, y cada una puede romperse. Divide los muscle-ups 5-4 / 4-3 / 3-2 desde el minuto cero y trata el snatch como un ritual: tirón, recepción, arriba. Los que hacen 9 MU seguidos terminan el snatch de 5 agarrando la rodilla (§1.7-CrossFit §10.4)."

### Sesión D — Mixta: HSPU deficit, OHS y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Deficit Handstand Push Up` | 4×3 (placa fina) | 4×5 (placa fina, tope 20) | 5×4 (2 placas, tope 25) | 3×3 suave (sin deficit) | 90 s | Nuevo: HSPU con rango ampliado sobre placas/paralelas; solo tras la base estricta+kipping de N8-N12 (§10.1); si el rango muere, vuelve a `Kipping Handstand Push Up` plano |
| `Overhead Squat` | 3×3 (60/43 kg) | 3×2 (70/52 kg) | 4×2 (75/55 kg) | 3×3 suave | 120 s | La recepción del snatch como ejercicio; posición, posición, posición |
| `Deficit Deadlift` | 3×3 (100/70 kg) | 3×3 (120/85 kg) | 4×2 (130/90 kg) | 2×3 suave | 150 s | Bisagra con recorrido ampliado (plataforma o discos): construye la salida de suelo del deadlift de test; CF7 impecable |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 5 `Kipping Handstand Push Up` + 8 `Dumbbell Snatch` (alterno, moderado) + 10 `Single Unders` | 60-90 s entre rondas |
| F2 | 4 rondas | 4 `Deficit Handstand Push Up` (o kipping plano) + 6 `Wall Ball` (9/6 kg) + 12 `Double Unders` | 60 s entre rondas; el overhead en rango completo bajo fatiga |
| F3 | AMRAP 15' | 5 `Deficit Handstand Push Up` + 8 `Toes to Bar` + 12 `Double Unders` | El overhead del Amanda con rango extra; tope de kipping 30 en el WOD |
| F4 | 2 rondas suaves | 5 `Push Up` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El HSPU en deficit es honestidad pura: las placas quitan el colchón del suelo y cualquier recorte de rango se paga al momento. Entra solo cuando el kipping plano sea aburrido de lo sólido — y mantén el estricto semanal: es el seguro de vida (§1.7-CrossFit §10.1)."

## Test de nivel N13 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Deadlift (170/120 kg) | 1 rep | 3 reps | gte | reps | fuerza |
| 2 | Squat Snatch (80/60 kg) | 1 rep | 3 reps | gte | reps | fuerza |
| 3 | Clean and Jerk (90/60 kg) | 1 rep | 2 reps | gte | reps | fuerza |
| 4 | Amanda (benchmark RX) | 9:30 (570 s) | 7:30 (450 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Amanda` (id 9, FOR_TIME, timeCap 600 s: 9-7-5 de `Ring Muscle Up` + `Squat Snatch` 60/43 kg, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`): MU con transición completa (pez sin rotación no cuenta, §10.4); snatch con recepción en squat (recepción cortada = `Power Snatch`, no válida).
- Contraste con referencias de catálogo (consulta 2026-10-10): Amanda RX beginner = 14:00 M / 17:00 F; intermediate = 8:00 M / 10:30 F. **Conflicto timeCap detectado:** el timeCap del benchmark es 600 s (10:00) y las referencias beginner (14:00/17:00) quedan por encima — imposibles de registrar en la plataforma (mismo defecto ya detectado en Fran/Elizabeth). Resuelto con umbrales estrictamente bajo el timeCap: mínimo 9:30 (570 s), objetivo 7:30 (450 s). El objetivo queda por debajo del intermediate M (8:00) para no duplicar la exigencia del mismo día que tres ítems de fuerza máxima (criterio §1.6); el intermediate F (10:30) también queda fuera por encima del timeCap.
- Ítem 1: bloqueo completo, barra pegada, sin rebote (CF5+CF7); agarre libre; el objetivo de 3 reps acumuladas en series de 1 con 60 s entre singles.
- Ítem 2: recepción en `Overhead Squat` profundo (CF1+CF2), sin press-out marcado (§10.4).
- Ítem 3: recepción del clean en squat (CF1+CF3) + jerk bloqueado (CF2); la unidad completa en un solo gesto encadenado.
- El re-test informal CF2/CF6 ya se hizo en Bloque 0 de F3; no es ítem del test (decisión 1).

## Logros del nivel N13

**De test:**
- "Amanda RX registrada" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Amanda (benchmark RX)`. El primer benchmark del tramo de competición: habilidad gimnástica + potencia olímpica bajo cronómetro.
- "Deadlift de 170/120 kg" — `type: test`, `tier: legendario`, `requirementType: test_item_min`, `requirementKey: Deadlift (170/120 kg)`. La carga absoluta más alta del programa.
- "Snatch de 80/60 kg" — `type: test`, `tier: legendario`, `requirementType: test_item_min`, `requirementKey: Squat Snatch (80/60 kg)`. La marca élite de snatch del programa.
- "Clean & jerk de 90/60 kg" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Clean and Jerk (90/60 kg)`.

**De hito:**
- "Primer snatch de 80/60 kg" — primer `Squat Snatch` completo con la carga élite fuera de test. `tier: legendario`.
- "Primer C&J de 90/60 kg" — primer `Clean and Jerk` unificado con la carga élite fuera de test. `tier: legendario`.
- "Primer deadlift de 170/120 kg" — primer single del deadlift de test en sesión (A F3). `tier: epico`.
- "Primer HSPU en deficit" — primer `Deficit Handstand Push Up` con rango completo y hollow controlado. `tier: epico`.
- "Primera Amanda escalada" — primer `Amanda` completo a carga de trabajo (sesión C F2). `tier: raro`.
- "Primera Amanda RX" — primera ejecución de `Amanda` en RX (sesión C F3, antes del test). `tier: legendario`.
- "Muscle-up triple" — primer set de 3 `Ring Muscle Up` sin bajar de las anillas. `tier: raro`.
- "Re-test CF2/CF6 superado" — re-test informal de overhead y muñeca a dosis máximas en F3, sin síntomas. `tier: comun`.

---

# NIVEL 14 — Motor Competición

- **Título:** Motor Competición — Heroes pesados, gimnásticos en fatiga extrema y Daniel RX
- **Objetivo:** el nivel del motor: capacidad de trabajo sostenida bajo fatiga creciente con WODs largos de 30-40 minutos, los **Heroes pesados del catálogo** (`Gunny` RX con chaleco 20/15 kg, `Jerry` RX como monoestructural pesado y `Daniel` RX como Hero de gimnasia + barra + carrera), los **gimnásticos en fatiga extrema** (MU en sets de 2-3 dentro de WODs largos, C2B en volumen, T2B altos, primer `Bar Muscle Up` en volumen) y las **dobles/triples en fatiga** como hábito de competición. Al superar N14, el usuario rinde al máximo cuando el cuerpo ya está fundido: ese es el rasgo que separa al competidor del practicante.
- **Requisitos de entrada:** mínimos del test N13 (incl. Amanda RX y deadlift 170/120).
- **skillFocus:** `Motor: Heroes RX (Gunny, Jerry, Daniel), gimnásticos en fatiga extrema, Bar Muscle Up en volumen y dobles/triples bajo fatiga`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF1/CF3:** ensayo de `Deep Squat Hold` 60 s + front rack con barra vacía 30 s/lado en Bloque 0 de F3, antes del volumen de squat y thrusters de los Heroes. Informativo: si falla, los thrusters de Daniel se escalan 5 kg y se anota; no genera `mobility_blocked`.

## Bloques comunes del Nivel 14

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N13 con foco nuevo: cadera y dorsiflexión para el volumen de squat/thruster de los Heroes, y hombro en fatiga (rotación externa) para los gimnásticos largos. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 10/lado + 12 | 12 + 12/lado + 12 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, re-test informal F3) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 6/lado | 60 s + 8/lado | 60 s + 8/lado (ensayo CF1) | 60 s + 6/lado suave |
| Tobillo (CF4, reforzado para carrera) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 45 s/lado + 45 s/lado | 45 s/lado + 45 s/lado | 60 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `External Rotation` | 15 + 15 + 2×12 | 15 + 15 + 2×12 | 15 + 15 + 2×12 | 12 + 12 + 2×10 suave |
| Muñeca (CF6, mantenido) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×8 suave + 30 s + 45 s |
| Front rack (CF3, re-test informal F3) | front rack con barra vacía + `Deep Squat Hold` con apoyo | 30 s/lado + 60 s | 30 s/lado + 60 s | 30 s/lado + 60 s (ensayo CF3) | 20 s/lado + 45 s suave |
| Anillas (mantenido) | `False Grip Hang` + `Ring Support Hold` | 3×20 s + 3×20 s | 3×20 s + 3×20 s | 3×20 s + 3×20 s | 2×15 s + 2×15 s suave |
| Comba (reforzada) | `Single Unders` + `Double Under Practice` | 60 s + 2×10 | 60 s + 2×12 | 60 s + 2×15 | 45 s + 2×8 suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en sentadilla profunda y bisagra antes del squat volumen; `Deep Squat Hold` antes del `Back Squat`.
- **B (Skill):** `False Grip Hang` extra ×10 s antes del trabajo de anillas; `Band Pull-Apart` extra ×5 antes del `Bar Muscle Up` (escápula en caliente).
- **C (Metcon):** énfasis en hombro y tobillo (el Hero del día manda); comba de calentamiento.
- **D (Mixta):** énfasis en movilidad completa y descarga ampliada; el trío suave de movilidad cierra el Bloque 0.

### Bloque postural (prehab — selección N14)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×10/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×10 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En el nivel de más volumen del programa, el prehab sube una serie en hombro y lumbar: es el seguro contra la fatiga técnica de los Heroes largos.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; tras los Heroes, añadir `Lat Stretch`, `Standing Quad Stretch` y extensión suave de muñeca). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N14)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Volumen gimnástico con tope: `Ring Muscle Up` 3×2 + `Bar Muscle Up` técnica (primer contacto), C2B ×10 en fatiga, T2B ×12; primer WOD largo de 25'; Gunny escalado (chaleco 10/7.5 kg o sin chaleco) como hito intermedio |
| 2 — Progresión | 80% | MU en sets de 2-3 en WODs largos (tope 12/sesión); `Bar Muscle Up` en volumen (sets de 2-3); C2B ×12 en AMRAPs; **Gunny RX como hito de sesión C** (chaleco 20/15 kg, timeCap 2400 s); primeros dobles/triples de `Hang Power Clean` en fatiga (§10.2) |
| 3 — Intensificación | 95% | Toques de test: C2B ×25 acumuladas, T2B ×30, MU ×8 en sets de 2; re-test informal CF1/CF3; **Daniel RX como hito de sesión C** (segunda y última toma antes del test); Jerry RX como WOD de mantenimiento de motor |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

Dosificación del volumen balístico: tope 100 reps/sesión entre MU, kipping HSPU, C2B y T2B en F2-F3; una sesión completa entre exposiciones intensas de hombro. Regla §10.1: dolor matinal >2/10 → vuelta a variantes estrictas/asistidas un ciclo. Los O-lifts en metcon nunca superan 60/43 kg (§10.2). Los WODs largos (30-40') se dosifican a ritmo de competición: 80-85% del ritmo de carrera, negativas solo en la última tercera parte.

## Sesiones del Nivel 14

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: squat volumen, thruster y gimnástico de mantenimiento (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Back Squat` (volumen) | 4×8 (90/65 kg) | 4×8 (95/70 kg) | 5×5 (100/70 kg) | 2×5 suave (80/60 kg) | 150 s | El squat del thruster y del wall ball de los Heroes en volumen real; CF1 en cada rep; 1-2 RIR |
| `Thruster` | 4×6 (43/29 kg) | 5×5 (43/29 kg) | 5×4 (50/35 kg) | 3×4 suave | 120 s | La unidad front squat + push press que Daniel castiga; bloqueo overhead con las piernas (CF2); la carga RX de Daniel es 43/29 — se ensaya en F2 |
| `Kipping Handstand Push Up` | 4×6 | 5×6 (tope 35) | 5×8 (tope 45) | 3×5 suave | 90 s | El kipping HSPU como carga de trabajo de motor; si el hollow se rompe, vuelve a `Strict HSPU` |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 15' | 6 `Thruster` (40/28 kg) + 8 `Pull Up` (o C2B si sale suelto) + 10 `Box Jump` | El thruster bajo reloj a carga de trabajo; ritmo sostenido |
| F2 | AMRAP 20' | 5 `Thruster` (43/29 kg) + 6 `Chest to Bar Pull Up` + 12 `Double Unders` | El esqueleto de Daniel en versión AMRAP; dobles en fatiga |
| F3 | AMRAP 25' | 4 `Thruster` (43/29 kg) + 6 `Toes to Bar` + 400 m `Run` | Tres modalidades, cero descanso: el motor del test; ritmo de carrera conservador |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El motor no se entrena con héroes: se entrena con AMRAPs de 25 minutos que nadie aplaude. El día del Hero solo se recoge lo sembrado en sesiones como esta — tres modalidades seguidas sin parar, a un ritmo que puedas defender en el minuto 20 (§1.7-CrossFit)."

### Sesión B — Skill: Bar Muscle Up, MU en fatiga y C2B volumen (todas las fases)

- Tipo: técnica, series seguidas, gimnástico primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (false grip en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Bar Muscle Up` | 5×1-2 (técnica) | 5×2-3 | 6×3 (tope 15) | 3×2 suave | 120 s | Nuevo en el programa: la MU de barra como volumen; el patrón de transición ya está dominado en anillas (N10-N13, §10.1) — aquí se aplica a la barra: castiga menos los flexores y permite series largas; si el throw-over se pierde, vuelve a `Chest to Bar Pull Up` (§10.4) |
| `Ring Muscle Up` (en fatiga) | 4×2 | 5×2-3 (tope 10) | 5×3 (tope 12, ensayo test) | 2×2 suave | 120 s | MU de anillas en sets de 2-3 tras trabajo previo: es la habilidad en fatiga extrema del test; series pequeñas y limpias (§10.4) |
| `Chest to Bar Pull Up` (volumen) | 5×10 | 5×12 | 5×12 | 3×6 suave | 90 s | C2B con volumen real: la tracción del Daniel (50 pull-ups) y el descanso activo de las MU; barbilla/pecho sobre la barra siempre |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | EMOM 16' | Impares: 2 `Bar Muscle Up` + 4 `Kipping Pull Up`; pares: 10 `Air Squat` + 8 `Single Unders` | La BMU bajo reloj con margen; si el throw-over falla, C2B (§10.4) |
| F2 | EMOM 18' | Impares: 3 `Ring Muscle Up`; pares: 6 `Chest to Bar Pull Up` + 12 `Double Unders` | 9 MU acumuladas + 18 C2B: gimnástico puro en fatiga |
| F3 | AMRAP 20' | 2 `Ring Muscle Up` + 4 `Kipping Handstand Push Up` + 6 `Kettlebell Swing American` (24/16 kg) + 8 `Double Unders` | El Nate alargado: cuatro modalidades, tope MU 10; ensayo del motor gimnástico del test |
| F4 | 2 rondas suaves | 3 `Ring Row - Elevated Feet` + 5 `Push Up` | Técnica relajada |

**Tips de sesión:** "El Bar Muscle Up es la recompensa de cuatro niveles de anillas: la transición ya la tienes, solo cambia el apoyo. No la fuerces el primer día: la barra no perdona la prisa. Y cuando las MU de anillas entren en fatiga, piensa en series de 2-3 desde el principio — la serie de 5 que mueres en la 4 te la cobra el minuto siguiente (§1.7-CrossFit §10.4)."

### Sesión C — Metcon: Gunny RX y Daniel RX (todas las fases)

- Tipo: WOD + core. Duración estimada: 60-75 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Run` (caliente) | 400 m suave | 400 m suave | 400 m a ritmo de carrera | 400 m muy suave | — | Despertar el ritmo de carrera antes del Hero; tobillo CF4 en caliente |
| `Thruster` + `Pull Up` (caliente) | 2×4 (30/20 kg) + 2×6 | 2×4 (43/29 kg) + 2×6 | 2×5 (43/29 kg) + 2×8 | 2×3 suave + 2×4 | 60 s | Los dos pesados de Daniel en caliente; sin llegar al fallo |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 20' | 200 m `Run` + 10 `Wall Ball` (9/6 kg) + 10 `Kettlebell Swing American` (24/16 kg) + 5 `Burpee` | El sabor de Gunny/Jerry a volumen moderado; ritmo de competición sostenido |
| F2 | Hero (hito): **Gunny RX** | `Gunny` en modalidad RX (id 67, FOR_TIME, timeCap 2400 s: 1 milla `Run` con chaleco 20/15 kg + 50 `Push Up` + 50 `Sit-up` + 1 milla `Run` con chaleco 20/15 kg) | **Hito del nivel: logro "Primer Hero con chaleco RX".** Primera milla conservadora (es la mitad del WOD, no el calentamiento); los push-ups en 4 series desde el principio; los sit-ups sin prisa |
| F3 | Hero (hito): **Daniel RX** | `Daniel` en modalidad RX (id 65, FOR_TIME, timeCap 1200 s: 50 `Pull Up` + 400 m `Run` + 21 `Thruster` (43/29 kg) + 800 m `Run` + 21 `Thruster` (43/29 kg) + 400 m `Run` + 50 `Pull Up`) | **Hito del nivel: logro "Daniel RX registrado".** Los 50 pull-ups de entrada en series de 5-8; los thrusters con la carga RX y bloqueo completo; la segunda mitad de pull-ups es donde se gana |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Los Heroes no se ganan con el primer 50%: se ganan con lo que te queda para el segundo. En Gunny, corre la primera milla como si aún te faltara todo — porque te falta. En Daniel, los pull-ups de salida son un depósito: quita series pequeñas y deja el crédito para los de la cola. El Hero RX se completa entero, no rápido: esa es la marca (§1.7-CrossFit)."

### Sesión D — Mixta: gimnástico pareado, monoestructural y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Toes to Bar` (volumen) | 4×12 | 5×12 | 5×15 (ensayo test) | 3×8 suave | 90 s | T2B kipping en volumen: el core del motor; el test exige 30 acumuladas — se ensaya en F3 |
| `Bar Muscle Up` | 4×2 | 5×3 | 5×3 (tope 15) | 3×2 suave | 120 s | Segundo slot de BMU del ciclo; throw-over limpio o C2B (§10.4) |
| `Double Unders` (volumen) | 4×30 | 5×30 | 5×50 | 3×30 suave | 60 s | Dobles en fatiga: el hito de 100 se gana aquí; muñecas relajadas, saltos bajos |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 6 `Toes to Bar` + 8 `Dumbbell Snatch` (alterno, moderado) + 200 m `Run` | 60-90 s entre rondas; core + unilateral + carrera |
| F2 | 4 rondas | 3 `Bar Muscle Up` + 5 `Kipping Handstand Push Up` + 12 `Double Unders` | 60 s entre rondas; el dúo gimnástico en fatiga |
| F3 | AMRAP 30' (monoestructural) | 1 milla `Run` + 2K `Row` (estilo Jerry suave) + 1 milla `Run` | WOD largo de motor puro: ritmo de 80-85%, negativa en la última parte; el 2K de remo a ritmo sostenido, no a paseo |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El WOD largo de hoy es la caja negra del nivel: 30 minutos sin nadie que te diga si vas bien. Aprende a escuchar el ritmo, no el reloj: si en el minuto 20 puedes hablar en frases cortas, vas bien; si no, ya lo sabes. El motor se construye exactamente ahí, en el silencio entre el minuto 15 y el 25."

## Test de nivel N14 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Chest to Bar Pull Up | 25 reps | 35 reps | gte | reps | fuerza |
| 2 | Toes to Bar | 30 reps | 40 reps | gte | reps | fuerza |
| 3 | Ring Muscle Up | 8 reps | 12 reps | gte | reps | fuerza |
| 4 | Daniel (benchmark RX) | 19:00 (1140 s) | 15:30 (930 s) | lte | seconds | benchmark |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Daniel` (id 65, FOR_TIME, timeCap 1200 s: 50 `Pull Up` + 400 m `Run` + 21 `Thruster` (43/29 kg) + 800 m `Run` + 21 `Thruster` (43/29 kg) + 400 m `Run` + 50 `Pull Up`, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`): thrusters a 43/29 kg con bloqueo overhead (CF2); los pull-ups pueden ser `Pull Up` o `Chest to Bar Pull Up` (RX permite ambos; el catálogo lista "pull-up").
- Contraste con referencias de catálogo (consulta 2026-10-10): Daniel tiene referencias **unificadas M/F**: beginner = 19:30, intermediate = 18:00, advanced = 15:00, elite = 12:00/13:00. Chequeo timeCap: el timeCap es 1200 s (20:00); el mínimo se fija en 19:00 (1140 s), estrictamente bajo el timeCap y justo por debajo del beginner unificado (19:30) — conservador (§1.6) al combinarse con tres ítems de volumen gimnástico el mismo test; el objetivo en 15:30 (930 s), en banda advanced (15:00). El elite (12:00/13:00) queda para el Olimpo (N16).
- Ítem 1: acumuladas en series de 5-8 con descanso breve; pecho sobre la barra en cada rep (§10.4).
- Ítem 2: acumuladas en series de 8-10; los pies tocan la barra, no la carga de impulso.
- Ítem 3: acumuladas en series de 2-3 con descanso completo entre series; transición completa con bloqueo en soporte (pez sin rotación no cuenta, §10.4).
- El re-test informal CF1/CF3 ya se hizo en Bloque 0 de F3; no es ítem del test (decisión 1).

## Logros del nivel N14

**De test:**
- "Daniel RX registrado" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Daniel (benchmark RX)`. El Hero grande del tramo de motor: 50 pull-ups + barra + carrera en formato piramidal.
- "C2B de 25 en test" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Chest to Bar Pull Up`.
- "T2B de 30 en test" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Toes to Bar`.
- "Muscle-up de 8 en fatiga" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Ring Muscle Up`.

**De hito:**
- "Primer Hero con chaleco RX" — primera ejecución completa de `Gunny` en RX (sesión C F2): dos millas con chaleco 20/15 kg. `tier: legendario`.
- "Primer Daniel RX" — primera ejecución de `Daniel` en RX (sesión C F3, antes del test). `tier: legendario`.
- "Primer Jerry RX" — primera ejecución de `Jerry` en RX (estilo sesión D F3 o hito libre): 1 milla + 2K remo + 1 milla. `tier: epico`.
- "Primer Hero sub-intermediate" — primer Hero RX por debajo de la referencia intermediate del catálogo (Gunny < 32:30, Daniel < 18:00 o Jerry < 27:00). `tier: epico`.
- "Primera Bar Muscle Up" — primer `Bar Muscle Up` completo con throw-over limpio. `tier: epico`.
- "Dobles de muscle-up en WOD" — primer set de 2 `Ring Muscle Up` dentro de un WOD de más de 15 minutos. `tier: raro`.
- "Dobles de 100" — primer set continuo de 100 `Double Unders` sin fallo. `tier: raro`.
- "Re-test CF1/CF3 superado" — re-test informal de sentadilla profunda y front rack a dosis máximas en F3, sin síntomas. `tier: comun`.

---

# NIVEL 15 — Pico

- **Título:** Pico — Evento combinado de Games: C&J élite, MU volumen y DT a fondo
- **Objetivo:** la consolidación élite previa al Olimpo (N16): los **eventos combinados tipo Games** (fuerza + metcon + gimnástico en la misma sesión, como se compite), la barra más pesada del programa — **`Clean and Jerk` 100/65 kg** —, el **`Ring Muscle Up` en volumen real (12+ acumuladas)** y el benchmark más duro al que se ha llegado por progresión: **DT RX a fondo** (objetivo por debajo del intermediate M del catálogo). Al superar N15, el usuario es atleta de competición: puede levantar pesado, hacer gimnásticos en volumen y sostener un Hero RX en el mismo evento — el formato exacto de un torneo.
- **Requisitos de entrada:** mínimos del test N14 (incl. Daniel RX y MU ×8 en fatiga).
- **skillFocus:** `Evento combinado: clean & jerk 100/65 kg, muscle-up en volumen, DT RX a fondo y gestión de torneo`
- **Puertas evaluadas:** ninguna (todas superadas). **Re-test informal CF6/CF7:** ensayo de `Wrist Push-Up` 2×10 + apoyo en extensión 60 s y `Dead Bug` 2×12/lado + `Bird Dog` 2×10/lado + swing ligero en Bloque 0 de F3, antes del test combinado (C&J + MU + DT el mismo día). Informativo: si CF6 falla, los HSPU del WOD de preparación bajan a estricto; si CF7 falla, los deadlifts del DT se dosifican más fino; no genera `mobility_blocked`.

## Bloques comunes del Nivel 15

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N14 con foco nuevo: el trío completo (hombro/muñeca/bisagra) como ritual de torneo — el test combinado exige las tres estructuras el mismo día. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 12/lado + 12 | 12 + 12/lado + 12 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 8/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 45 s/lado + 45 s/lado | 45 s/lado + 45 s/lado | 60 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `External Rotation` | 15 + 15 + 2×12 | 15 + 15 + 2×12 | 15 + 15 + 2×12 | 12 + 12 + 2×10 suave |
| Muñeca (CF6, re-test informal F3) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s (ensayo CF6) | 2×8 suave + 30 s + 45 s |
| Bisagra (CF7, re-test informal F3) | `Dead Bug` + `Bird Dog` + `Kettlebell Swing Russian` (ligero, controlado) | 2×12/lado + 2×10/lado + 2×15 | 2×12/lado + 2×10/lado + 2×15 | 2×12/lado + 2×10/lado + 2×15 (ensayo CF7) | 2×8 suave + 2×8 suave + 2×10 suave |
| Anillas (reforzado) | `False Grip Hang` + `Ring Support Hold` | 3×25 s + 3×20 s | 3×25 s + 3×25 s | 3×30 s + 3×25 s | 2×15 s + 2×15 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 60 s + 2×10 | 60 s + 2×12 | 60 s + 2×15 | 45 s + 2×8 suave |

Variantes por sesión:
- **A (Fuerza):** énfasis en bisagra (ensayo CF7) y front rack antes del C&J; `Deep Squat Hold` antes del `Front Squat`.
- **B (Skill):** `False Grip Hang` extra ×10 s y `Scapular Pull Up` ×5 antes del trabajo de anillas; `Wrist Push-Up` extra ×5 antes de los HSPU (CF6 en caliente).
- **C (Metcon):** énfasis en hombro y bisagra (el evento del día manda); comba de calentamiento.
- **D (Mixta):** énfasis en movilidad completa y descarga ampliada; el trío suave de movilidad cierra el Bloque 0.

### Bloque postural (prehab — selección N15)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×10/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×10 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En el nivel del evento combinado, el prehab es parte del rendimiento: hombro/muñeca/lumbar serán los tres frentes del test, y cada uno tiene su ensayo diario aquí.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones de evento combinado, añadir `Lat Stretch`, extensión de muñeca y dorsiflexión). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N15)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Primer **evento combinado simulado** (formato Games): C&J moderado (70/50 kg, primer evento) + WOD gimnástico (segundo) + DT escalado (tercero); MU en sets de 2-3 (tope 12/sesión); C&J 80/55 kg en fuerza |
| 2 — Progresión | 80% | C&J 90/60 kg; MU en sets de 3-4 (tope 15); **segundo evento combinado** con C&J 80/55 + MU + DT a 60/43 kg; HSPU deficit en metcons (tope 40/sesión) |
| 3 — Intensificación | 95% | C&J 100/65 kg ensayando el test; re-test informal CF6/CF7; **tercer evento combinado de competición** (formato exacto del test: C&J pesado + MU volumen + DT RX) como hito de sesión C |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; test de nivel al final |

Dosificación del evento combinado: en las simulaciones F1-F2 los tres eventos van en la misma sesión con los `restAfterBlock` habituales entre bloques y el orden de competición (fuerza → gimnástico → metcon). En F3 se respeta el orden del test con pausas reales entre eventos anotadas como nota (los ensayos del F3 se separan del test del F4 por al menos 48 h reales de recuperación activa — §10.5). Tope de volumen balístico 120 reps/sesión en F2-F3.

## Sesiones del Nivel 15

16 sesiones = 4 fases × tipos A/B/C/D. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza: clean & jerk élite y squat de competición (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 60-70 min.
- Bloque 0: variante A (ensayo CF7 en F3). Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Clean and Jerk` | 5×1+1 (80/55 kg) | 5×1 (90/60 kg) | 6×1 (100/65 kg, ensayo test) | 3×2 suave (70/50 kg) | 180 s | La barra más pesada del programa: recepción del `Squat Clean` en profundidad (CF1+CF3), `Split Jerk` con bloqueo decisivo (CF2); singles con descanso completo; 1 RIR en F3 — el test exige un single, el objetivo dos |
| `Front Squat` | 4×3 (90/65 kg) | 4×3 (95/70 kg) | 5×2 (100/72.5 kg) | 2×3 suave (80/60 kg) | 150 s | El squat del clean a carga de competición; CF3 en el rack; pausa de 1 s abajo en F3 |
| `Kipping Handstand Push Up` | 4×6 | 5×8 (tope 40) | 5×8 (tope 45) | 3×5 suave | 90 s | El overhead del evento combinado; si el hollow se rompe, vuelve a `Strict HSPU` |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 14' | 3 `Power Clean` (70/50 kg) + 6 `Kipping Handstand Push Up` + 9 `Air Squat` | El primer evento del combinado a carga de trabajo; ritmo de torneo, no de gimnasio |
| F2 | AMRAP 16' | 2 `Clean and Jerk` (80/55 kg) + 4 `Ring Muscle Up` + 10 `Double Unders` | Fuerza + gimnástico en el mismo reloj: el corazón del evento combinado |
| F3 | AMRAP 18' | 2 `Clean and Jerk` (80/55 kg) + 3 `Ring Muscle Up` + 6 `Kettlebell Swing American` (32/24 kg) + 12 `Double Unders` | El evento de ensayo completo; bisagra CF7 en los swings a carga RX |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 8 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "El C&J de 100/65 no se negocia con la barra: tiras, recibes en sentadilla y empujas con todo, o no cuenta. En el evento combinado, el primer evento (la barra) se gana en la cabeza: un single limpio y a descansar. El que celebra el C&J pierde el WOD que viene detrás (§1.7-CrossFit §10.4)."

### Sesión B — Skill: muscle-up volumen y HSPU deficit (todas las fases)

- Tipo: técnica, series seguidas, gimnástico primero. Duración estimada: 60-70 min.
- Bloque 0: variante B (false grip en caliente). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Ring Muscle Up` (volumen) | 5×2-3 (tope 12) | 5×3-4 (tope 15) | 6×3 (tope 18, ensayo test) | 3×2 suave | 120 s | La MU a volumen de competición: sets de 3-4 limpios, descanso completo entre series; el test exige 12 acumuladas — se ensaya en F3; transición completa siempre (§10.4) |
| `Deficit Handstand Push Up` | 4×4 (placa fina) | 5×4 (2 placas) | 5×5 (2 placas, tope 30) | 3×3 suave (sin deficit) | 90 s | El overhead en rango amplio como carga de trabajo; si el rango muere, kipping plano (§10.4) |
| `Strict HSPU` | 3×4 | 3×5 | 4×4 (tope estricto) | 2×3 suave | 90 s | El ancla estricta se mantiene hasta el Olimpo: es lo que sostiene todo el volumen kipping (§10.1) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | EMOM 16' | Impares: 2 `Ring Muscle Up` + 3 `Kipping Pull Up`; pares: 8 `Air Squat` + 8 `Single Unders` | La MU bajo reloj con margen; tope 8 en el EMOM |
| F2 | EMOM 18' | Impares: 3 `Ring Muscle Up`; pares: 4 `Deficit Handstand Push Up` (o kipping plano) + 10 `Double Unders` | 9 MU + 8 HSPU deficit por sesión: gimnástico puro en fatiga |
| F3 | AMRAP 20' | 3 `Ring Muscle Up` + 5 `Kipping Handstand Push Up` + 8 `Kettlebell Swing American` (24/16 kg) + 12 `Double Unders` | El evento gimnástico del test en versión AMRAP; tope MU 15 |
| F4 | 2 rondas suaves | 3 `Ring Row - Elevated Feet` + 5 `Push Up` | Técnica relajada |

**Tips de sesión:** "La muscle-up de competición es una administración: sets pequeños, descanso corto, cero héroes. Doce MU acumuladas son 4 series de 3 o 6 de 2 — elige antes de empezar y cumple. El día que necesites una serie de 5 para salvar el test, el test ya se perdió (§1.7-CrossFit §10.4)."

### Sesión C — Metcon: eventos combinados y DT a fondo (todas las fases)

- Tipo: WOD + core (formato evento). Duración estimada: 60-75 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación corta del evento):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Clean and Jerk` (caliente) | 2×2 (60/43 kg) | 2×1 (70/50 kg) | 2×1 (80/55 kg) | 2×2 suave | 90 s | El primer evento en caliente; unidades limpias sin fatiga |
| `Ring Muscle Up` (caliente) | 2×2 | 2×2 | 2×3 | 1×2 | 60 s | El segundo evento en caliente; sin llegar al tope |

**Bloque `wod` (evento del día):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | Evento combinado 1 | E1: 1 `Clean and Jerk` (70/50 kg) al minuto ×6 min; E2: AMRAP 8' de 3 `Ring Muscle Up` + 6 `Wall Ball` (9/6 kg); E3: `DT` escalado (60/45 kg, deadlift + hang power clean + push jerk) | Primer formato de torneo completo: tres eventos en una sesión, con los 60 s de transición habituales; anota cada resultado por separado |
| F2 | Evento combinado 2 | E1: `Clean and Jerk` 5×1 (80/55 kg) con 90 s; E2: AMRAP 10' de 4 `Ring Muscle Up` + 8 `Toes to Bar`; E3: `DT` a 60/43 kg (carga de trabajo) | Segundo torneo simulado; el DT a carga de trabajo es la referencia de gestión antes del RX |
| F3 | Evento combinado 3 (hito) | E1: 1 `Clean and Jerk` (85/60 kg) + 1 (90/60 kg) + 1 (95/65 kg); E2: AMRAP 10' de 3 `Ring Muscle Up` + 5 `Kipping Handstand Push Up`; E3: **Benchmark: DT RX** (id 14, FOR_TIME, sin timeCap en catálogo: 5 rondas de 12 `Deadlift` + 9 `Hang Power Clean` + 6 `Push Jerk` a 70/52 kg) | **Hito del nivel: logro "DT RX a fondo" — el tercer torneo simulado con DT en RX como cierre.** Es el formato exacto del test del nivel: pesado → gimnástico → metcon RX |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; test al final del nivel |

**Tips de sesión:** "Un evento combinado no es un WOD duro: es tres WODs con la misma cabeza. Entra al E1 sin recordar el E3, y al E3 sin recordar el E1 — cada evento se juega solo. Esa es la habilidad que se entrena hoy y la que el torneo de verdad exigirá: borrar y empezar de cero tres veces en una mañana (§1.7-CrossFit)."

### Sesión D — Mixta: gimnástico pareado, OHS y movilidad (todas las fases)

- Tipo: mixta + movilidad. Duración estimada: 50-60 min.
- Bloque 0: variante D. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Overhead Squat` | 3×3 (65/45 kg) | 3×2 (75/55 kg) | 4×2 (80/60 kg) | 3×3 suave | 120 s | El overhead más pesado del programa como ejercicio; la posición que sostiene el snatch del Olimpo |
| `Toes to Bar` (volumen) | 4×15 | 5×15 | 5×18 | 3×8 suave | 90 s | T2B en volumen de competición: el core del evento combinado |
| `Chest to Bar Pull Up` (mantenimiento) | 4×10 | 4×12 | 5×10 | 3×6 suave | 90 s | La tracción se mantiene a volumen alto: es el fondo de armario del que salen las MU |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 6 `Power Snatch` (50/35 kg) + 6 `Toes to Bar` + 10 `Single Unders` | 60-90 s entre rondas; snatch a carga técnica (§10.2) |
| F2 | 4 rondas | 4 `Chest to Bar Pull Up` + 4 `Deficit Handstand Push Up` (o kipping plano) + 12 `Double Unders` | 60 s entre rondas; el dúo gimnástico en rango amplio |
| F3 | AMRAP 25' | 5 `Thruster` (50/35 kg) + 7 `Chest to Bar Pull Up` + 12 `Double Unders` | WOD largo de transición: el motor del evento combinado a carga moderada |
| F4 | 2 rondas suaves | 5 `Toes to Bar` + 10 `Single Unders` | Movimiento; test al final del nivel |

**Tips de sesión:** "El OHS de 80/60 kg es la prueba de fuego invisible del nivel: si la barra vuela sobre la cabeza con las sentadillas vacías, todo el overhead del test está asegurado. La posición es el rendimiento — el día que dejes de trabajarla, dejará de trabajar para ti."

## Test de nivel N15 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit | Tipo |
|---|----------------------------------|--------|----------|-----------|------|------|
| 1 | Clean and Jerk (100/65 kg) | 1 rep | 2 reps | gte | reps | fuerza |
| 2 | Ring Muscle Up | 12 reps | 18 reps | gte | reps | fuerza |
| 3 | DT (benchmark RX) | 16:00 (960 s) | 12:30 (750 s) | lte | seconds | benchmark |

Notas del test:
- Formato **evento combinado** (orden de competición): el ítem 1 (C&J) se ejecuta primero; tras 5-8 min de transición, el ítem 2 (MU acumuladas); tras otros 5-8 min, el ítem 3 (DT RX). Es el mismo formato de la sesión C F3 — el usuario ya lo ha competido dos veces en el nivel.
- Ítem 3: `type: benchmark` sobre `DT` (id 14, FOR_TIME, **sin timeCap en catálogo**: 5 rondas de 12 `Deadlift` + 9 `Hang Power Clean` + 6 `Push Jerk` a 70/52 kg, ver definición del benchmark). Modalidad **RX** (`scalingUsed = rx`): deadlift táctil-and-go sin rebote (CF7), hang power clean recibido en power, push jerk en bloqueo. El test N11 exigía DT en 22:00/18:00 (beginner F/M); aquí el benchmark vuelve "a fondo": el mismo WOD, ya sin la fatiga de la novedad.
- Contraste con referencias de catálogo (consulta 2026-10-10): DT RX beginner = 18:00 M / 22:00 F; intermediate = 12:00 M / 15:00 F. `DT` no tiene `time_cap_seconds` en catálogo (NULL): no hay techo duro de registro, pero se mantienen los umbrales calibrados en referencias beginner/intermediate. El mínimo se fija en 16:00 (960 s), entre beginner M (18:00) e intermediate F (15:00), conservador (§1.6) por combinarse con el C&J élite y las MU el mismo evento; el objetivo en 12:30 (750 s), justo por encima del intermediate M (12:00) — el DT "a fondo" del que habla el encargo. El advanced (8:00/10:00) queda para el Olimpo (N16).
- Ítem 1: recepción del clean en squat (CF1+CF3) + jerk bloqueado (CF2); el objetivo de 2 reps acumuladas en series de 1 con 60-90 s entre singles.
- Ítem 2: acumuladas en series de 2-4 con descanso completo entre series; transición completa con bloqueo en soporte (pez sin rotación no cuenta, §10.4); el re-test informal CF6/CF7 de F3 ya validó la muñeca y la bisagra para este formato.
- Ítem 3: tercero en el orden del evento — la marca se hace con el C&J y las MU ya en las piernas y el hombro: es la señal del tramo de competición.

## Logros del nivel N15

**De test:**
- "DT RX a fondo" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: DT (benchmark RX)`. El DT RX por debajo de 16:00 tras un C&J de 100/65 y 12 muscle-ups: la marca del atleta de competición.
- "Clean & jerk de 100/65 kg" — `type: test`, `tier: legendario`, `specialCelebration: true`, `requirementType: test_item_min`, `requirementKey: Clean and Jerk (100/65 kg)`. La barra más pesada del programa.
- "Muscle-up de 12 en volumen" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Ring Muscle Up`.

**De hito:**
- "Primer C&J de 100/65 kg" — primer `Clean and Jerk` completo con la carga élite fuera de test. `tier: legendario`, `specialCelebration: true`.
- "Primer evento combinado completado" — primer formato de tres eventos en una sesión (sesión C F1) con los tres resultados anotados. `tier: epico`.
- "Primer DT RX tras C&J pesado" — primer DT RX ejecutado como tercer evento de un combinado (sesión C F3). `tier: legendario`.
- "Muscle-up de 15 acumuladas" — primer entrenamiento con 15+ `Ring Muscle Up` acumuladas en sesión (tope superado). `tier: epico`.
- "OHS de 80/60 kg" — primer `Overhead Squat` completo con la carga del nivel. `tier: raro`.
- "Re-test CF6/CF7 superado" — re-test informal de muñeca y bisagra a dosis máximas en F3, sin síntomas. `tier: comun`.

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N13-N15: todos los ejercicios citados existen en el catálogo con la grafía usada (verificación por SQL 2026-10-10, `SELECT name FROM exercises ORDER BY name`): `Ring Muscle Up`, `Bar Muscle Up`, `Muscle Up`, `Strict Muscle Up`, `Assisted Muscle Up Transition`, `Ring Dip`, `Ring Support Hold`, `False Grip Hang`, `Squat Clean`, `Squat Snatch`, `Power Clean`, `Hang Power Clean`, `Clean Pull`, `Snatch Balance`, `Power Snatch`, `Clean and Jerk`, `Push Jerk`, `Split Jerk`, `Back Squat`, `Front Squat`, `Deadlift`, `Deficit Deadlift`, `Romanian Deadlift`, `Strict HSPU`, `Strict Handstand Push Up`, `Kipping Handstand Push Up`, `Deficit Handstand Push Up` (id 41 — existe; ojo al duplicado `deficit HSPU` id 489: este documento usa la grafía canónica), `Handstand Push Up`, `Overhead Squat`, `Kipping Pull Up`, `Chest to Bar Pull Up`, `Pull Up`, `Toes to Bar`, `Kettlebell Swing American`, `Kettlebell Swing Russian`, `Thruster`, `Wall Ball`, `Box Jump`, `Burpee`, `Push Up`, `Sit-up`, `Run`, `Double Unders`, `Single Unders`, `Row`, `Rowing Machine`, benchmarks `Amanda` (id 9), `DT` (id 14), `Daniel` (id 65), `Gunny` (id 67), `Jerry` (id 68), `Nate` (id 60), `Whitten` (id 69).
- FALTA (no bloquea): `Chaleco` como ejercicio no existe en catálogo — los WODs con chaleco (Gunny, Murph) se modelan como nota del benchmark/WOD, no como ejercicio cargable. Si el seeder necesita representar la carga del chaleco, habría que crear un ejercicio `Weighted Run`/`Run with Weight Vest` (RUN con equipamiento chaleco 20/15 kg en `rx_weight`).
- FALTA (no bloquea): el ejercicio `Clean` a secas sigue sin existir en catálogo (solo variantes) — `Linda` (id 25, "Clean 0.75×BW" + deadlift 1.5×BW + bench press BW) necesitaría mapeo a `Squat Clean`/`Power Clean` y pesos basados en BW en el seeder. Se descarta para N13-N15 por imposibilidad de fijar cargas absolutas de test (mismo razonamiento que en el lote 10-12); queda documentada como candidata al Olimpo (N16) si se resuelve el mapeo.
- Conflicto timeCap detectado y resuelto en `Amanda` (id 9): timeCap 600 s (10:00) con referencias beginner 14:00 M / 17:00 F — por encima del timeCap, imposibles de registrar (mismo defecto ya conocido en Fran/Elizabeth). Umbrales del test fijados estrictamente bajo 600 s: mínimo 570 s (9:30), objetivo 450 s (7:30), el primero en banda intermedia beginner/intermediate y el segundo por debajo del intermediate M (8:00). El precedente no se propaga: cualquier futuro benchmark del tramo élite debe re-chequearse contra su timeCap antes de fijar umbrales lte.
- Chequeo timeCap en `Daniel` (id 65): timeCap 1200 s (20:00); mínimo del test 1140 s (19:00) estrictamente bajo el timeCap y bajo el beginner unificado (19:30) — OK.
- Chequeo timeCap en `DT` (id 14): `time_cap_seconds` NULL en catálogo — sin techo duro; umbrales 960 s / 750 s calibrados en beginner/intermediate. OK, con la salvedad de que la plataforma no cortará el registro por tiempo: el timeCap efectivo es el del propio atleta.
- Corrección respecto al encargo: `Daniel` tiene referencias unificadas M/F en el catálogo (19:30/18:00/15:00/12:00-13:00) y su timeCap es 20:00, no 30-40' — el "Hero grande" del nivel es grande por estructura (50 pull-ups + 42 thrusters + 2K de carrera), no por duración de catálogo. Los WODs largos de 30-40' del tramo viven en sesiones A/D (AMRAP 25-30') y en Gunny/Jerry como hitos.
- Mejora sugerida (no bloquea): duplicados de grafía heredados (`Deficit Handstand Push Up`/`deficit HSPU`, `Ring Dip`/`Ring Dips`, `Face Pull`/`face pull`, `Strict HSPU`/`Strict Handstand Push Up`). Este documento usa la primera grafía canónica de cada par.
- Mejora sugerida (no bloquea): `Gunny` (id 67) y `Jerry` (id 68) tienen referencias unificadas M/F (38:00/32:30/27:00/20:00-22:00 y 32:30/27:00/22:30/17:00-19:00) — coherente con su uso aquí. `Fight Gone Bad` (id 35, interval_stations, timeCap 1020 s) y `Filthy 50` (id 36, chipper) quedan documentados como candidatos al Olimpo (N16) por formato de estaciones/chipper, distinto al de los eventos combinados de N15.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Re-tests informales de puertas (CF2/CF6 en N13 F3, CF1/CF3 en N14 F3, CF6/CF7 en N15 F3):** las siete puertas están superadas al entrar en el tramo; se mantienen los ensayos a dosis máximas en Bloque 0 con consecuencia de ajuste de carga (informativo), por el mismo razonamiento que los lotes anteriores (decisión 1 de los lotes 10-12 heredada).
2. **`restAfterBlock` = 60 s en N13-N15:** se mantiene por indicación explícita del proyecto (mismo criterio que N1-N12). En los WODs largos (25-30') y los Heroes, la salida ante desbordes del `TrainingTimeCalculator` es recortar volumen de WOD, no subir el descanso. En los eventos combinados de sesión C, los 60 s marcan la transición entre eventos dentro de la misma sesión; los 5-8 min entre eventos del test del N15 van en la nota del test (es un formato de competición, no un bloque).
3. **Benchmarks del tramo:** Amanda RX (N13 — squat snatch 60/43 + MU ×9-7-5: el candidato natural del encargo; valida habilidad + potencia bajo cronómetro con umbrales resueltos contra el timeCap defectuoso, ver Pendientes), Daniel RX (N14 — el Hero grande de gimnasia + barra + carrera con referencias unificadas y timeCap sano) y DT RX a fondo (N15 — el benchmark de la línea de fuerza del programa, re-testeado a ritmo intermediate tras el C&J élite y las MU: el "a fondo" del encargo). Alternativas consideradas y descartadas: Linda (id 25, pesos BW — imposible fijar cargas de test; queda al Olimpo si se resuelve el mapeo), Jerry/Gunny como tests (monostructural/cortos para cerrar un arco; correctos como hitos de sesión, insuficientes como medida única del nivel), Fight Gone Bad (formato interval_stations con puntuación propia — candidato al Olimpo, no encaja en el patrón lte/gte del test).
4. **Test N15 en formato evento combinado (fuerza → gimnástico → metcon):** el encargo pedía "combo exigente (C&J pesado + benchmark a fondo + MU volumen)". Se adopta el orden real de competición (los Games abren con un lift pesado), documentado en las notas del test y ensayado tres veces en sesión C (F1-F3). Alternativa descartada: ítems separados en sesiones distintas — pierde la señal distintiva del tramo (rendir con todo lo anterior en el cuerpo).
5. **Bar Muscle Up solo en N14:** el encargo sugería MU en anillas para N13 y se añade BMU en N14 como volumen (el patrón de transición lleva cuatro niveles consolidado; §10.1). La MU de anillas sigue siendo la del test en N14-N15; `Bar Muscle Up` no entra como ítem de test en este tramo (la barra del test la ocupa el C&J).
6. **HSPU en deficit en N13 (no antes):** cuatro niveles de base estricta/kipping (N8-N12) antes de añadir rango; el deficit entra como ejercicio de sesión D y se consolida en N15, sin llegar a ítem de test en este tramo (§10.1). Grafía verificada: existe `Deficit Handstand Push Up` (id 41).
7. **Deadlift de test en N13 (170/120 kg):** el deadlift vuelve al test tras dos niveles de mantenimiento, construido por `Deficit Deadlift` y RDL de fuerza. La progresión 100/70 (N9) → 170/120 (N13) es la línea de fuerza máxima del tramo de competición; no sube más en N14-N15 (el pesado pasa al C&J).
8. **Cargas femeninas según el catálogo:** Amanda 60/43, DT 70/52, Daniel 43/29, Gunny chaleco 20/15 — la autoridad es el catálogo (verificación SQL 2026-10-10), no las referencias externas. El C&J de test N15 (100/65) es marca de programa, no de benchmark.
9. **Logros de hito élite según el encargo:** primer C&J 90/60 (N13) y 100/65 (N15), primer snatch 80/60 (N13), primer Hero sub-referencia-intermediate (N14: Gunny < 32:30 / Daniel < 18:00 / Jerry < 27:00) — todos `tier` legendario/epico según la tabla de tiers del spec §9.
