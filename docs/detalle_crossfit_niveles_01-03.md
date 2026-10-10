# Detalle de contenido — Niveles 1, 2 y 3 — CrossFit Master v1

> Documento fuente desde el que se generará el programa CrossFit Master (comando
> `app:create-crossfit-master`), réplica del papel de `detalle_niveles_01-03.md`
> en Calistenia Master v4. Especificación de autoridad:
> `docs/superpowers/specs/2026-10-10-crossfit-master-design.md` (§4 arco, §5
> bloques, §8 puertas CF, §9 logros, §10 regla §1.7-CrossFit).
>
> **Estado de redacción:** Nivel 1 completo (16 sesiones). Niveles 2 y 3:
> objetivo, test, puertas, skillFocus y logros redactados; **sesiones N2 y N3
> PENDIENTES de redactar** (siguiente lote, tras validación del Nivel 1).

**Convenciones del documento:**

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados hasta superar el test. Los textos del programa no usan esas palabras.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **Sesiones** (§5): A — Fuerza (básico de barra + fuerza + prehab hombro); B — Skill/técnica (gimnástico u O-lift + prehab muñeca); C — Metcon/WOD (WOD de la sesión + prehab lumbar/core); D — Mixta (combinación suave + movilidad + prehab de trío completo).
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab muñeca/hombro/lumbar rotado por sesión: A hombro, B muñeca, C lumbar, D trío suave) → `descarga`. `restAfterBlock` = 60 s en N1-N3.
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas (decisión 4 del proyecto). Singles/series máximas → reps `1`. Dosis `/lado` se computan una vez.
- **RIR:** en N1-N3 se trabaja con 2-4 RIR en fuerza; nunca al fallo. En WODs, el fallo técnico marca el escalado (regla §1.7-CrossFit §10.4).
- **Equipamiento asumido N1-N3:** gimnasio con barras y bumpers (trabajo con barra vacía desde N1) + kettlebells/mancuernas ligeros + cajón + banda ligera + comba. Ver pregunta abierta §14 del spec.
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta `SELECT name FROM exercises`). Nombres literales.
- **Duraciones estimadas:** incluyen los 5 bloques con descansos reales; salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.

---

## Puertas de movilidad del programa (definición completa, spec §8)

Se evalúan en el nivel **anterior** al que exigen. Fallar fuerza + puerta = `mobility_blocked` (ciclo corto de movilidad, sin repetir fuerza). CF2/CF3/CF6/CF7 son barreras de seguridad: sin ellas no se sube de nivel aunque la fuerce sobre.

| Código | Nombre | Zona que protege | Criterio de superación (texto para `criteria`) | Se exige en | Se evalúa en |
|--------|--------|------------------|-----------------------------------------------|-------------|--------------|
| CF1 | Sentadilla profunda pegada | squat, wall ball, cleans | `Deep Squat Hold` 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor | N4 (squat cargado progresivo) | **N3** |
| CF2 | Overhead (hombro) | press, S2OH, wall ball target | `Shoulder Dislocate` con banda/PVC ×15 sin compensar la lumbar; `Overhead Squat` con PVC/barra vacía ×10 en profundidad | N5 (S2OH, wall ball a diana) | N4 (re-test N7) |
| CF3 | Muñeca en front rack | clean, front squat | Front rack con barra vacía 30 s/lado sin dolor ni desviación activa | N5 (cleans) | N4 (re-test N7) |
| CF4 | Dorsiflexión de tobillo | lunges, wall ball | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón | N4 (lunges, wall ball) | **N3** |
| CF5 | Cadera/bisagra | deadlift, swings, lunges | Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada | N3 (deadlift con carga, KB swing progresivo) | **N2** |
| CF6 | Muñeca en extensión | HSPU, handstand | `Wrist Push-Up` 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10 | N7 (HSPU, handstand) | N6 (re-test N9) |
| CF7 | Bisagra lumbar controlada | deadlifts, O-lifts | `Dead Bug` 2×10/lado + `Bird Dog` 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra | N7 (O-lifts, deadlifts en fatiga) | N6 (re-test N9) |

Evaluación en este documento: **N1: ninguna** (se siembra CF1/CF4/CF5 en Bloque 0 sin evaluar). **N2: CF5.** **N3: CF1 y CF4.** CF2/CF3/CF6/CF7 quedan definidas para lotes N4-N6 y posteriores.

---

## Regla §1.7-CrossFit en tips (spec §10)

Aplicar en `tips` de niveles y sesiones:

1. **Primero estricto, luego dinámico:** nada balístico (kipping) sobre un hombro sin base estricta (kipping pull-up exige 5-8 strict; kipping HSPU exige HSPU estricto; muscle-up kipping exige base estricta y transición asistida). En N1-N3 todo es estricto: no hay kipping.
2. **Lumbar:** O-lifts y deadlifts pesados nunca en fatiga alta; en N1-N3 el deadlift es técnico y de carga ligera-moderada; dolor matinal >2/10 = congelar el gesto un ciclo.
3. **Muñeca:** progresión de extensión soportada antes que volumen (holds antes que reps); dolor matinal >2/10 en extensión = congelar cleans y HSPU.
4. **Técnica rota antes que músculo:** en WODs con carga, cuando falla la forma se baja la dosis; el escalado también es protección.
5. Descanso entre exposiciones pesadas de bisagra: dejar pasar al menos una sesión completa entre bisagras cargadas exigentes (traducción de 48-72 h a lógica sin calendario).

---

# NIVEL 1 — On-Ramp

- **Título:** On-Ramp — Técnica antes que carga
- **Objetivo:** enseñar los patrones fundamentales de CrossFit (sentadilla, bisagra, empuje vertical, core) con PVC/barra vacía y kettlebell ligero, con movilidad densa de tobillo/cadera/hombro/muñeca y prehab alto. Aquí no se falla: se aprende. N1 es un nivel de aprendizaje: su test de salida es **suave y sin benchmarks con nombre**.
- **Requisitos de entrada:** ninguno. Diseñado para quien no ha tocado una barra ni hecho un metcon.
- **skillFocus (obligatorio):** `Patrones básicos: sentadilla, bisagra, empuje vertical y core anti-extensión`

**Puertas de movilidad activas:** ninguna evaluada. Se siembra el terreno de CF1 (sentadilla profunda), CF4 (dorsiflexión) y CF5 (bisagra) como trabajo permanente de Bloque 0, sin evaluación.

## Test de nivel N1 (resumen)

Ítems de tipo fuerza, auto-evaluados al final de la Fase 4 (Recuperación), con 2-3 min de descanso entre pruebas, en este orden (de menor a mayor interferencia). Superar todos los mínimos (modo `legacy`, 3 de 4) habilita el Nivel 2. `direction` = `gte` en todos (a más reps/tiempo, mejor).

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Air Squat | 20 reps | 30 reps | gte | reps |
| 2 | Plancha frontal | 40 s | 60 s | gte | seconds |
| 3 | Goblet Squat (KB 8/6 kg) | 10 reps | 15 reps | gte | reps |
| 4 | Kettlebell Deadlift (16/12 kg, técnico) | 10 reps | 15 reps | gte | reps |

Notas del test:
- Criterio técnico de validez: en Air Squat y Goblet Squat, profundidad con cadera bajo la rodilla y talones pegados; en Kettlebell Deadlift, espalda neutra y cadera-bisagra (CF5) visible en cada rep; rep con técnica rota no cuenta (§1.7-CrossFit §10.4).
- El peso de los ítems 3-4 es orientativo (KB disponible en el gimnasio); el usuario puede usar ±2 kg si es lo disponible, anotándolo.

## Logros del nivel N1

**De test (por ítem de test, se evalúan en cualquier envío de test):**
- "20 sentadillas al aire" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Air Squat`.
- "Plancha de 40 segundos" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Plancha frontal`.
- "Primer Goblet Squat de test" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Goblet Squat (KB 8/6 kg)`.
- "Bisagra impecable" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Kettlebell Deadlift (16/12 kg, técnico)`.

**De hito (primera ejecución válida en sesión):**
- "Primera sentadilla profunda pegada" — primera Air Squat con cadera bajo la rodilla y talones en el suelo. `tier: comun`.
- "Primer AMRAP completado" — primera sesión C con el AMRAP completo sin parar el reloj. `tier: comun`.
- "Primera bisagra con barra" — primer Kettlebell Deadlift / deadlift con barra vacía con 3 puntos de contacto y espalda neutra validados. `tier: comun`.
- "Primer press con barra" — primer `Strict Press` con barra vacía en línea (sin hiperextensión lumbar). `tier: comun`.

## Bloques comunes del Nivel 1

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 6-10 min)

Transiciones de ~5 s entre ejercicios. Contenido base de toda sesión N1 (§1.8 movilidad acumulativa):

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | Jumping Jack + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 8 + 6/lado + 6 | 10 + 8/lado + 8 | 10 + 8/lado + 8 | 8 + 8/lado + 8 suave |
| Tobillo (siembra CF4) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Deep Squat Hold` | 20 s/lado + 20 s/lado + 20 s | 25 s/lado + 25 s/lado + 30 s | 30 s/lado + 30 s/lado + 45 s | 30 s/lado + 30 s/lado + 60 s (ensayo CF4) |
| Cadera (siembra CF5) | `Glute Bridge` + `Kneeling Hip Flexor Stretch` + `Adductor Rockback` | 10 + 20 s/lado + 8 | 12 + 25 s/lado + 10 | 15 + 30 s/lado + 10 | 10 suave + 30 s/lado + 8 suave |
| Hombro (siembra CF2) | `Shoulder Dislocate` (banda ligera o PVC) + `Band Pull-Apart` | 10 + 10 | 12 + 12 | 15 + 12 | 12 suave + 10 |
| Muñeca (siembra CF6) | `Wrist Prayer` + `Wrist Extension Stretch` + `Back-of-Hand Stretch` | 20 s + 20 s + 20 s | 25 s + 25 s + 25 s | 30 s + 30 s + 30 s | 30 s cada uno suave |

Variantes por tipo de sesión:
- **A (Fuerza):** tal cual la tabla; énfasis en muñeca y hombro antes del press.
- **B (Skill):** añade `Shoulder Dislocate` extra ×5 antes de la barra; entra respiración diafragmática 360 ×5 ciclos al final.
- **C (Metcon):** énfasis en tobillo (Calf Stretch Wall 30 s/lado) y activación `Clamshell` ×10/lado como primera estación del WOD si incluye sentadilla.
- **D (Mixta):** énfasis en cadera y torácica; la descarga se amplía.

### Bloque postural (prehab — selección N1, dosificado por sesión, nunca eliminado)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 2×8-10 / 2×8 / 2×12 |
| B | Muñeca | `Wrist Push-Up` (de rodillas si hace falta) + `Wrist Prayer` + `Wrist Extension Stretch` | 2×6-8 / 2×30 s / 2×30 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` (banda ligera) | 2×8/lado / 2×6/lado / 2×10/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×12 / 2×6 / 2×6/lado |

Descanso entre ejercicios posturales: 45-60 s. Criterio: calidad por encima de fatiga; es prehab, no trabajo principal.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

- Respiración diafragmática tumbado (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada en la sesión (30-45 s por zona, de la selección de Bloque 0). En Fase 4 la descarga se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N1)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Aprender patrones con PVC/barra vacía y KB ligero; variantes más fáciles; técnica por encima de volumen |
| 2 — Progresión | 80% | Subir reps o carga mínima (KB +2-4 kg); primer AMRAP 8'; primeros intervalos cortos |
| 3 — Intensificación | 95% | Calidad máxima con la carga más alta del nivel; ensayo de test integrado en sesión A y D |
| 4 — Recuperación | 50% | Mitad de volumen e intensidad; movilidad protagonista; test de nivel al final |

## Sesiones del Nivel 1

Las 16 sesiones = 4 fases × tipos A/B/C/D. Cada sesión tiene los 5 bloques en orden (`preparacion` → `fuerza_skill` → `wod` → `postural` → `descarga`), `restAfterBlock` 60 s. Bloque 0 y descarga: ver "Bloques comunes" con la variante y dosis de fase indicadas. Bloque postural: tabla de la sesión correspondiente.

### Sesión A — Fuerza básica (todas las fases)

- Tipo: fuerza, series seguidas. Duración estimada: 45-55 min.
- Bloque 0: variante A, dosis de fase. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Goblet Squat` | 3×8 | 4×8 | 4×10 | 2×8 suave | 90 s | KB 8/6 kg; cadera bajo la rodilla; talones pegados (siembra CF1); codos dentro de rodillas |
| `Standing Dumbbell Press` | 3×8 | 3×10 | 4×8 | 2×8 | 90 s | Mancuernas ligeras; glúteo apretado; barbilla atrás; no hiperextender la lumbar |
| `Kettlebell Deadlift` | 3×8 | 3×10 | 4×10 | 2×8 suave | 90 s | 16/12 kg; empuja cadera atrás; espalda neutra; barra/KB pegado a las tibias (siembra CF5) |

Ensayo de test en Fase 3: última serie de cada ejercicio con la dosis del test (Goblet ×10, KBDL ×10). En Fase 4 todo a dosis F4.

**Bloque `wod` (metcon suave post-fuerza):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 6' | 5 `Air Squat` + 5 `Push Up` (de rodillas si hace falta) | Ritmo conversacional; técnica antes que rondas |
| F2 | AMRAP 8' | 6 `Air Squat` + 4 `Push Up` + 6 `Jumping Jack` | Primera subida de duración |
| F3 | AMRAP 8' | 8 `Air Squat` + 5 `Push Up` + 8 `Jumping Jack` | Ensayo del esfuerzo del test |
| F4 | AMRAP 5' suave | 5 `Air Squat` + 5 `Push Up` | Movimiento, no fatiga; test al final del nivel |

**Tips de sesión:** "La sentadilla y el hinge de hoy son la base de todo lo que viene: si la técnica se rompe, baja la carga, no las repes buenas (§1.7-CrossFit)."

### Sesión B — Skill con barra (todas las fases)

- Tipo: técnica, series seguidas, orden de mayor a menor demanda neural (barra primero). Duración estimada: 45-55 min.
- Bloque 0: variante B, dosis de fase. Postural: tabla sesión B (muñeca). Descarga: estándar.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Strict Press` (barra vacía, 20/15 kg) | 4×5 | 5×5 | 5×5 | 3×5 suave | 90 s | Trayectoria vertical junto a la cara; glúteo y costillas abajo; extensión completa sin reclinar |
| `Deadlift` (barra vacía o 30/20 kg) | 4×5 | 4×5 | 5×5 | 3×5 suave | 90 s | Técnico siempre: 3 puntos de contacto, cadera atrás, lumbares neutros; aquí se ensaya CF5 |
| `Plank` | 3×20-30 s | 3×30-40 s | 3×40-50 s | 2×30 s | 60 s | Costillas abajo, glúteo apretado; respiración bajo tensión |

**Bloque `wod` (gimnástico suave):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas suaves | 6 `Ring Row - Feet on Ground` + 8 `Sit-up` | 60-90 s entre rondas; pecho a los anillos, cuerpo rígido |
| F2 | 4 rondas | 8 `Ring Row - Feet on Ground` + 10 `Sit-up` | 60 s entre rondas |
| F3 | 4 rondas | 8 `Ring Row - Feet on Ground` + 10 `Sit-up` + 30 s `Hollow Body Hold` | Core integral; ensayo de control bajo fatiga |
| F4 | 2 rondas suaves | 6 `Ring Row - Feet on Ground` + 8 `Sit-up` | Técnica relajada |

**Tips de sesión:** "Barra vacía es un peso de verdad: si el press o el deadlift de hoy salen redondos, el cuerpo ya está aprendiendo lo que el N3 pedirá con carga (§1.2 encadenamiento)."

### Sesión C — Metcon suave (todas las fases)

- Tipo: WOD corto + core. Duración estimada: 40-50 min.
- Bloque 0: variante C, dosis de fase. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación técnica, corto):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Air Squat` (técnica pausada) | 2×10 | 2×12 | 3×15 | 2×10 suave | 60 s | 2 s abajo; talones pegados (siembra CF1) |
| `Kettlebell Romanian Deadlift` (16/12 kg) | 2×8 | 2×10 | 3×10 | 2×8 | 60 s | Bisagra con KB; estira y vuelve sin perder neutra (siembra CF5) |

**Bloque `wod` (protagonista de la sesión):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 6' | 10 `Air Squat` + 5 `Sit-up` + 10 `Jumping Jack` | Un solo bloque continuo; ritmo conversacional |
| F2 | AMRAP 8' | 10 `Air Squat` + 5 `Push Up` + 10 `Jumping Jack` | Primer AMRAP de 8' del nivel |
| F3 | 4 × (1' trabajo / 1' descanso) | 30 s `Kettlebell Swing Russian` (16/12 kg) + 30 s `Air Squat` en cada intervalo | Primer formato por intervalos; swing ruso a la altura del pecho, nunca americano en fatiga alta (§1.7-CrossFit §10.2) |
| F4 | AMRAP 6' suave | 10 `Air Squat` + 5 `Sit-up` | Movimiento, no fatiga; test al final del nivel |

**Tips de sesión:** "En el metcon manda el ritmo que te deja repetir bien: rondas lentas y limpias > rondas rápidas y feas. El escalado también es protección (§1.7-CrossFit §10.4)."

### Sesión D — Mixta: movilidad + carries + core (todas las fases)

- Tipo: mixta suave + movilidad extra. Duración estimada: 40-50 min.
- Bloque 0: variante D, dosis de fase. Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Farmer Carry` (KB/mancuernas moderadas) | 3×20 m | 3×30 m | 4×30 m | 2×20 m | 60 s | Postura erguida, hombros lejos de orejas, pasos cortos |
| `Glute Bridge` | 2×12 | 3×15 | 3×15 | 2×12 | 60 s | Pausa 1 s arriba; sube con glúteo, no con lumbar |
| `Suitcase Carry` | 2×15 m/lado | 2×20 m/lado | 3×20 m/lado | 2×15 m/lado | 60 s | Carga a un lado; no te inclines; anti-flexión lateral |

**Bloque `wod` (mixto suave):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 3 rondas | 8 `Box Step Over` (bajo) + 8 `Mountain Climber` + 8 `Glute Bridge` | 60-90 s entre rondas; paso controlado, sin saltar |
| F2 | 4 rondas | 10 `Box Step Over` + 10 `Mountain Climber` + 10 `Glute Bridge` | 60 s entre rondas |
| F3 | 4 rondas | 10 `Box Step Over` + 8 `Dumbbell Thruster` (mancuernas ligeras) + 10 `Sit-up` | Primer gesto de thruster con carga mínima (semblanza de Fran) |
| F4 | 2 rondas suaves | 8 `Box Step Over` + 8 `Mountain Climber` | Movimiento, no fatiga; test al final del nivel |

**Tips de sesión:** "Los carries son el prehab más honesto que existe: si no puedes caminar erguido con la carga, todavía no la llevas a la barra."

---

# NIVEL 2 — Primer Impulso

- **Título:** Primer Impulso — Metcons con estructura
- **Objetivo:** primeros metcons con estructura (intervalos cortos y primer EMOM), primer benchmark escalado como hito de sesión (sin ser test aún), bisagra potente con `Kettlebell Swing` progresivo. Consolidar los patrones de N1 con carga ligera-moderada.
- **Requisitos de entrada:** mínimos del test N1 (Air Squat ×20, Plancha 40 s, Goblet ×10, KBDL técnico ×10).
- **skillFocus:** `Bisagra potente con kettlebell, metcon estructurado y primer benchmark escalado`
- **Puertas evaluadas:** **CF5** (cadera/bisagra) — requisito de entrada efectivo del N3 (deadlift con carga y swing progresivo). Re-test implícito en cada envío de test. Las sesiones N2 se redactarán con ensayo de CF5 integrado en Bloque 0 de la sesión C.
- **SESIONES N2: PENDIENTES DE REDACTAR** (lote siguiente).

## Test de nivel N2 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Goblet Squat (KB 12/8 kg) | 15 reps | 20 reps | gte | reps |
| 2 | Kettlebell Swing Russian (24/16 kg) | 15 reps | 21 reps | gte | reps |
| 3 | `Plank` | 60 s | 90 s | gte | seconds |
| 4 | Helen (benchmark escalado) | 15:00 (900 s) | 12:00 (720 s) | lte | seconds |

Notas del test:
- Ítem 4: primer ítem `type: benchmark` del programa (spec §7). El usuario ejecuta Helen en modalidad **Scaled** (selección del player); el benchmark del catálogo es `Helen` (id 3, `for_time`: 400 m Run + KB Swing ×21 con 24/16 kg + Pull Up ×12 por ronda, 3 rondas). En escalado: KB permitido menor (16/12 kg) y `Pull Up` asistidos con banda o `Ring Row`. La marca se registra en `UserBenchmarkAttempt` con `scalingUsed = scaled` y alimenta el ítem del test (auto-evaluación).
- Kettlebell Swing: criterio de validez = bisagra visible; el swing americano no se usa en test (§1.7-CrossFit §10.2).

## Logros del nivel N2

**De test:**
- "Goblet de 15" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Goblet Squat (KB 12/8 kg)`.
- "Plancha de 60 segundos" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Plank`.
- "Primer benchmark registrado" — `type: test`, `tier: raro`, `requirementType: test_item_min`, `requirementKey: Helen (benchmark escalado)` (incluye ítems `type: benchmark`).

**De hito:**
- "Primer EMOM completado" — primer EMOM del nivel terminado con todas las rondas. `tier: comun`.
- "Swing de 24/16 kg" — primer `Kettlebell Swing Russian` con 24/16 kg y técnica validada (ensayo CF5). `tier: comun`.
- "Helen escalada" — primera ejecución completa del benchmark Helen en sesión (hito de sesión, antes de ser test). `tier: raro`.

---

# NIVEL 3 — Consolidación

- **Título:** Consolidación — Preparado para RX escalado
- **Objetivo:** consolidar squat/press/hinge con carga ligera-moderada, introducir press de potencia (`Push Press`), `Front Squat` ligero y dobles de mancuernas como semilla de thruster; terminar con al menos un benchmark escalado con marca registrada. Al superar N3, el usuario está listo para el tramo RX escalado (N4-N6) y sus puertas CF1/CF4.
- **Requisitos de entrada:** mínimos del test N2 (incl. CF5 superada).
- **skillFocus:** `Consolidación de squat/press/hinge con carga y preparación al RX escalado`
- **Puertas evaluadas:** **CF1** (sentadilla profunda pegada) y **CF4** (dorsiflexión de tobillo) — requisito de entrada efectivo del N4 (squat cargado progresivo, lunges y wall ball). Las sesiones N3 se redactarán con ensayo de CF1 (Deep Squat Hold creciente) y CF4 (Bloque 0) integrados.
- **SESIONES N3: PENDIENTES DE REDACTAR** (lote siguiente).

## Test de nivel N3 (resumen)

| # | Prueba (nombre literal del ítem) | Mínimo | Objetivo | Direction | Unit |
|---|----------------------------------|--------|----------|-----------|------|
| 1 | Goblet Squat (KB 16/12 kg) | 20 reps | 25 reps | gte | reps |
| 2 | Deadlift (60/40 kg) | 5 reps | 8 reps | gte | reps |
| 3 | Push Press (30/20 kg) | 8 reps | 12 reps | gte | reps |
| 4 | Fran (benchmark escalado) | 12:00 (720 s) | 8:00 (480 s) | lte | seconds |

Notas del test:
- Ítem 4: `type: benchmark` sobre `Fran` (id 1, `for_time`, 21-15-9 de `Thruster` 43/29 kg + `Pull Up`). En escalado: thruster con 20-30/15-20 kg o mancuernas, `Pull Up` asistidos con banda o jumping pull-ups controlados. Marca en `UserBenchmarkAttempt` con `scalingUsed = scaled` (auto-evaluación del test, spec §7).
- Ítem 2: deadlift a carga de trabajo moderada, nunca en fatiga alta (§1.7-CrossFit §10.2); criterio de validez = CF5 visible en cada rep.
- Ítem 3: primer gesto dinámico de piernas-impulso del programa; si la lumbar se redondea al bajar la barra, se valida como estricto y se anota (la potencia llega completa en N4-N6).

## Logros del nivel N3

**De test:**
- "Goblet de 16/12 kg ×20" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Goblet Squat (KB 16/12 kg)`.
- "Deadlift de 60/40 kg" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Deadlift (60/40 kg)`.
- "Primer press de potencia" — `type: test`, `tier: comun`, `requirementType: test_item_min`, `requirementKey: Push Press (30/20 kg)`.
- "Fran escalada registrada" — `type: test`, `tier: epico`, `requirementType: test_item_min`, `requirementKey: Fran (benchmark escalado)`.

**De hito:**
- "Primera sentadilla profunda de 60 s" — primer `Deep Squat Hold` de 60 s pegado (ensayo CF1). `tier: comun`.
- "Sentadilla frontal ligera" — primer `Front Squat` con 30/20 kg y criterio de profundidad. `tier: comun`.
- "Puerta CF1 superada" / "Puerta CF4 superada" — `type: test` vinculado a puerta (mobility gate passed). `tier: raro`.

---

## Pendientes de catálogo (FALTA)

- Nada crítico para N1-N3: todos los ejercicios citados existen en el catálogo con la grafía usada.
- Mejora sugerida (no bloquea): ejercicio explícito de dorsiflexión tipo "Knee-to-Wall Rocks" — cubierto de momento con `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` + `Deep Squat Hold`.
- Mejora sugerida (no bloquea): "PVC Pass Through" como alias didáctico de `Shoulder Dislocate` para los textos de N1.
- Los pesos RX de Helen/Fran en catálogo son los oficiales; el escalado se resuelve por `scalingOptions` del benchmark + elección RX/Scaled del player, sin crear copias del benchmark (decisión D2, spec §6.1).

## Decisiones de contenido tomadas (para revisión del controller)

1. **Mapeo de cuándo se evalúa cada puerta:** CF5 en N2; CF1 y CF4 en N3; CF2/CF3 en N4; CF6/CF7 en N6 (con re-tests posteriores). El spec solo dice "en el nivel anterior al que exigen"; este calendario concreto es propuesta mía.
2. **Benchmarks de test:** Helen escalada (N2) y Fran escalada (N3) — los dos existen en catálogo y son los clásicos de iniciación; los tiempos mínimos/objetivo (Helen 15:00/12:00 scaled; Fran 12:00/8:00 scaled) son estimaciones de dominio a contrastar con las referencias beginner de la BD en el lote de N4-N6.
3. **Pesos de los ítems de fuerza** (goblet 8→16 kg, KBDL 16 kg, deadlift 60/40, push press 30/20): progresión conservadora coherente con "mínimos conservadores" (§1.6).
4. **Sessiones N2/N3 no redactadas** a propósito (brief Step 1); se redactan tras validar N1 y con el feedback (brief Step 3).
5. N1 usa `Standing Dumbbell Press` en sesión A y `Strict Press` con barra vacía en sesión B (redundancia deliberada: mancuerna primero por seguridad del hombro, barra después por técnica olímpica).
