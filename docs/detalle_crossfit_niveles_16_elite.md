# Detalle de contenido — Nivel 16 (Élite ∞) — CrossFit Master v1

> Sexto y último lote del documento fuente del programa CrossFit Master (comando
> `app:create-crossfit-master`). Continuidad directa de
> `docs/detalle_crossfit_niveles_13-15.md` (mismas convenciones, mismas
> puertas CF1-CF7 ya superadas, mismo formato estético). Especificación de
> autoridad: `docs/superpowers/specs/2026-10-10-crossfit-master-design.md`
> (§4 arco — tramo "Élite ∞", §5 bloques, §9 logros de rangos, §10 regla
> §1.7-CrossFit). Referencia de formato de rangos:
> `docs/detalle_niveles_13-15_olimpo.md` (bloque Olimpo de calistenia, §6).
>
> **Estado de redacción:** COMPLETO. N16 "Élite ∞" es un nivel de estructura
> propia (tipo Olimpo): `isInfinite = true`, sin test de salida, 16 sesiones
> de rotación (4 fases × A/B/C/D), logros de rangos Bronce→Diamante con
> umbrales contrastados contra las referencias beginner/intermediate/
> advanced/elite de la BD, récords eternos y tabla de decisiones. Pendiente
> la validación de contenido con el usuario (gate humano).

**Convenciones del documento** (heredadas de los lotes 01-15, sin cambios salvo lo indicado):

- **Sin calendario:** prohibido modelar días/semanas/meses. El progreso es por fases y ciclos ilimitados. En N16 los ciclos no terminan nunca: se habita el nivel, no se supera.
- **Fases del ciclo** (§1.3): Base (55%) → Progresión (80%) → Intensificación (95%) → Recuperación (50%). 4 sesiones por fase: A/B/C/D.
- **5 bloques por sesión, en este orden, ninguno opcional:** `preparacion` (Bloque 0, movilidad fluida, sin descansos de fuerza — transiciones de ~5 s) → `fuerza_skill` → `wod` → `postural` (prehab muñeca/hombro/lumbar rotado por sesión: A hombro, B muñeca, C lumbar, D trío suave) → `descarga`. `restAfterBlock` = 60 s (decisión 2 de los lotes anteriores, se mantiene).
- **Rangos:** el valor base es el mínimo cargable; el rango completo va en notas. Singles/series máximas → reps `1`. Dosis `/lado` se computan una vez.
- **RIR:** 1-2 RIR en fuerza pesada; nunca al fallo. En WODs, el fallo técnico marca el escalado (regla §1.7-CrossFit §10.4).
- **Grafía de ejercicios:** resuelta contra el catálogo real de la BD (consulta 2026-10-10, `SELECT name FROM exercises ORDER BY name`). Nombres literales.
- **Convención de nombres de ítems y logros:** todo ítem que corresponda a un ejercicio de catálogo usa su grafía exacta, para que los logros (`requirementKey`) y las referencias casen exacto. Los benchmarks se nombran como nombre propio ("Fran (benchmark RX)").
- **Duraciones estimadas:** salen de `TrainingTimeCalculator`, nunca se hardcodean. Si el tiempo aprieta, se recorta volumen, nunca descanso ni Bloque 0.

---

## Regla §1.7-CrossFit en tips (spec §10) — aplicación en N16

1. **En N16 la regla §1.7 es AÚN más importante (cargas élite):** el deadlift pesado (línea 170/120 kg) y la carga de competición para single/1RM (C&J 100/65, snatch 80/60) viven **solo en `fuerza_skill`**, frescos, con Bloque 0 completo y descansos completos. En WODs las cargas de competición con la técnica rota = **parar y anotar** (§10.4): una recepción de clean que se corta, un jerk que se press-outea o un deadlift que redondea bajo el reloj no son "una mala rep", son la señal de detención del evento. El atleta de élite se diferencia del amateur exactamente aquí: sabe cuándo no contar.
2. **Primero estricto, luego dinámico:** toda la base está construida (N1-N15). El volumen balístico de N16 (MU en sets, HSPU deficit en metcons, BMU en fatiga) hereda siete niveles de progresión; la jerarquía no se negocia en élite, se presume.
3. **Lumbar y hombro en la era de los rangos:** la tentación de N16 es perseguir el rango Oro/Platino en cada benchmark del tirón. Regla: **una marca dura por sesión B** (la del benchmark del día) y **una exposición pesada por sesión A**; el resto es mantenimiento. 48-72 h entre bisagras pesadas y entre overheads pesados (§10.5).
4. **Muñeca y tendones:** dolor matinal >2/10 congela el gesto un ciclo (§10.3), igual que en todos los niveles. En el nivel infinito esto no es una limitación: es el mecanismo que permite seguir entrenando dentro de veinte años.
5. **El test vacío no es ausencia de evaluación:** cada benchmark RX registrado en sesión B es una medición real con referencias de catálogo. El nivel no se "pasa"; se mide, ciclo a ciclo, contra uno mismo y contra los rangos.

---

# NIVEL 16 — Élite ∞

- **Título:** Élite ∞ — El Olimpo del CrossFit Master: rangos, récords y eventos para siempre
- **Tipo:** nivel infinito (tipo Olimpo, misma arquitectura conceptual que el bloque Olimpo de calistenia). `isInfinite = true`, **sin test de salida**: `testRequirements` vacío (decisión 1 — replica el criterio de Olimpo: el nivel no se supera, se habita). No hay ítems obligatorios, no hay estado `mobility_blocked` posible por test, no hay graduación.
- **Objetivo:** que el programa nunca se acabe. Superado el N15 (C&J 100/65, `Ring Muscle Up` 12/18, DT RX 960/750 s, deadlift 170/120), el usuario es atleta de competición y entra en un bloque sin graduación final: **16 sesiones de rotación** (4 fases × A/B/C/D) que combinan fuerza élite de competición, benchmarks RX "a fondo" con logros de rangos (Bronce/Plata/Oro/Platino/Diamante según marcas reales), eventos combinados tipo Games y gimnásticos élite con motor. El usuario repite ciclos ilimitados; la progresión se mide en récords personales y rangos, no en un test de salida.
- **Requisitos de entrada (regla §1.2, literales):** mínimos del test N15 (incl. `Clean and Jerk` 100/65 kg ×1, `Ring Muscle Up` ×12, DT RX en 16:00 y `Deadlift` 170/120 kg del N13 vigente como marca de programa).
- **skillFocus:** `Élite: rangos en benchmarks RX (Fran, Helen, Grace, DT, Amanda, Elizabeth, Nate, Daniel), fuerza de competición (C&J 100/65, snatch 80/60, deadlift 170/120), eventos tipo Games y récords personales`
- **Puertas evaluadas:** ninguna (CF1-CF7 superadas desde N2-N6). Mantenimiento integral permanente en Bloque 0 (acumulativo N1-N15, §1.8). Re-tests informales opcionales antes de picos concretos (mismo criterio del lote 13-15, decisión 1 heredada).

## Estructura del nivel: rotación de 16 sesiones

Las 4 fases conservan el esqueleto del programa (Base 55% / Progresión 80% / Intensificación 95% / Recuperación 50%), pero los **tipos de sesión cambian de significado** respecto a N1-N15: ya no son "fuerza / skill / metcon / mixta", sino cuatro estaciones de competición que rotan:

| Sesión | Estación | Contenido |
|--------|----------|-----------|
| **A — Fuerza élite** | Barra de competición | `Back Squat` / `Deadlift` / `Clean and Jerk` / `Snatch` de competición: 1RM de trabajo y volumen pesado con autoregulación RPE (§4 de esta sección). Los O-lifts y el deadlift pesado viven aquí y solo aquí (§1.7, punto 1). |
| **B — Benchmarks a fondo** | La casa de los rangos | Rotación de benchmarks RX del programa (Fran, Helen, Grace, Elizabeth, DT, Amanda, Daniel, Nate, Cindy, Murph, Fight Gone Bad) intentando mejorar marca. Cada benchmark del día se ejecuta fresco, como primer bloque `wod`, y la marca se registra: aquí es donde los logros de rangos cobran vida (§6 de esta sección). |
| **C — Eventos Games** | Torneo simulado | Eventos combinados en formato competición (fuerza → gimnástico → metcon, heredados del N15) con benchmarks largos/Heroes pesados (Murph, Gunny, Jerry, Filthy 50, Fight Gone Bad) como eventos estrella. |
| **D — Gimnásticos élite y motor** | Habilidad + fondo | `Ring Muscle Up` y `Bar Muscle Up` en volumen, `Deficit Handstand Push Up` en fatiga controlada, `Chest to Bar Pull Up` / `Toes to Bar` altos, y monoestructural largo (`Run`, `Rowing Machine`, `Assault Bike`) como fondo de competición. |

El usuario recorre las 16 sesiones en orden (F1A→F1B→F1C→F1D→F2A→…) y, terminada la Fase 4, **reinicia el ciclo**. No hay "pasar de nivel": hay ciclos contados (Ciclo 1, Ciclo 2, …) con el histórico de marcas como progresión. En la Fase 4 de cada ciclo la intensidad baja al 50% y la movilidad es protagonista — no es opcional (regla 6 de Olimpo, aplicada: un ciclo sin su fase de recuperación es un ciclo incompleto).

## Bloques comunes del Nivel 16

### Bloque 0 — Preparación y movilidad (fluida, sin descansos de fuerza; 8-12 min)

Acumulativo N1-N15 con foco propio del nivel infinito: el trío completo (hombro/muñeca/bisagra) como ritual de competición diario — en N16 cualquier sesión puede exigir las tres estructuras. Transiciones de ~5 s.

| Componente | Ejercicios (grafía catálogo) | F1 | F2 | F3 | F4 |
|------------|------------------------------|----|----|----|----|
| Activación general | `Jumping Jack` + `Active March` + `Arm Circles` + `Hip Circles` | 2 min | 2 min | 2 min | 2 min |
| Torácica | `Cat-Camel` + `Open Books` + `Foam Roll Thoracic Extension` | 12 + 10/lado + 10 | 12 + 12/lado + 12 | 12 + 12/lado + 12 | 10 + 8/lado + 8 suave |
| Sentadilla profunda (CF1, mantenido) | `Deep Squat Hold` + `Cossack Squat` | 60 s + 8/lado | 60 s + 8/lado | 60 s + 8/lado | 60 s + 6/lado suave |
| Tobillo (CF4, mantenido) | `Calf Stretch Wall` + `Bent-Knee Soleus Stretch` | 45 s/lado + 45 s/lado | 45 s/lado + 45 s/lado | 60 s/lado + 45 s/lado | 30 s/lado + 30 s/lado suave |
| Hombro/overhead (CF2, mantenido) | `Shoulder Dislocate` + `Band Pull-Apart` + `External Rotation` | 15 + 15 + 2×12 | 15 + 15 + 2×12 | 15 + 15 + 2×12 | 12 + 12 + 2×10 suave |
| Muñeca (CF6, mantenido) | `Wrist Push-Up` + `Wrist Extension Stretch` + apoyo en extensión | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×10 + 60 s + 60 s | 2×8 suave + 30 s + 45 s |
| Bisagra (CF7, mantenido) | `Dead Bug` + `Bird Dog` + `Kettlebell Swing Russian` (ligero) | 2×12/lado + 2×10/lado + 2×15 | 2×12/lado + 2×10/lado + 2×15 | 2×12/lado + 2×10/lado + 2×15 | 2×8 suave + 2×8 suave + 2×10 suave |
| Anillas (mantenido) | `False Grip Hang` + `Ring Support Hold` | 3×25 s + 3×20 s | 3×25 s + 3×25 s | 3×30 s + 3×25 s | 2×15 s + 2×15 s suave |
| Comba (mantenido) | `Single Unders` + `Double Under Practice` | 60 s + 2×10 | 60 s + 2×12 | 60 s + 2×15 | 45 s + 2×8 suave |

Variantes por sesión:
- **A (Fuerza élite):** énfasis en bisagra y front rack antes de la barra del día; `Deep Squat Hold` justo antes del squat; `Towel Hang` si el día es deadlift.
- **B (Benchmarks):** énfasis en las dos estructuras del benchmark del día (la tabla de rangos §6 indica, por benchmark, qué preparar); comba si el benchmark lleva dobles.
- **C (Eventos Games):** trío completo en caliente: el evento combinado exige hombro, muñeca y bisagra el mismo día.
- **D (Gimnásticos/motor):** `False Grip Hang` extra ×10 s y `Scapular Pull Up` ×5 antes del trabajo de anillas; tobillo reforzado si el monoestructural es `Run`.

### Bloque postural (prehab — selección N16)

| Sesión | Zona | Ejercicios | Dosis |
|--------|------|------------|-------|
| A | Hombro | `Scapular Push Up` + `Prone Y Raise` + `Face Pull` | 3×12 / 2×12 / 2×15 |
| B | Muñeca | `Wrist Push-Up` + `Wrist Prayer` + `Wrist Extension Stretch` | 3×10 / 2×30 s / 2×45 s |
| C | Lumbar/core | `Dead Bug` + `Bird Dog` + `Pallof Press` | 2×12/lado / 2×10/lado / 2×12/lado |
| D | Trío suave | `Band Pull-Apart` + `Wrist Push-Up` + `Dead Bug` | 2×15 / 2×10 / 2×10/lado |

Descanso entre ejercicios posturales: 45-60 s. En el nivel infinito el prehab es la infraestructura: hombro, muñeca y lumbar son los tres frentes de cada ciclo, y el histórico de ciclos sin dolor es el verdadero rango Diamante del que nadie habla.

### Bloque final — Descarga (3-5 min; en Fase 4 sube a 6-8 min y es protagonista)

Respiración diafragmática (90 s), `Child's Pose` (45 s), estiramiento suave de la zona trabajada (30-45 s por zona; en sesiones de barra pesada añadir `Jefferson Curl` suave y `Lat Stretch`; tras monoestructural largo, `Standing Quad Stretch` y dorsiflexión). En Fase 4 se amplía con toda la movilidad del nivel a dosis F4.

## Fases del ciclo (N16)

| Fase | Intensidad | Nota |
|------|-----------|------|
| 1 — Base | 55% | Barra a 70-80% de las marcas N15 (C&J 80/55, snatch 65/45, deadlift 145/105, squat 110/80); benchmarks a ritmo de "vuelta a casa" (un punto por encima del rango actual); volumen gimnástico con topes (MU 15/sesión); evento combinado simulado suave; primer monoestructural largo del ciclo |
| 2 — Progresión | 80% | Barra al 85-92% (C&J 90/60, snatch 75/55, deadlift 160/115); benchmarks del rango actual (intento de marca controlado, uno por sesión B); MU en sets de 3-4 (tope 18); segundo evento combinado con carga de competición moderada |
| 3 — Intensificación | 95% | Barra a marca: single pesado del ciclo (C&J 100/65+, snatch 80/60+, deadlift 170/120+); sesión B = intentos serios de rango (el benchmark del día se ejecuta como en torneo: fresco, una toma); evento combinado de competición completo; tope de volumen balístico 120 reps/sesión |
| 4 — Recuperación | 50% | Mitad de volumen; movilidad protagonista; **cierre de ciclo**: revisión del histórico de marcas, elección del benchmark prioritario del próximo ciclo, descanso activo. Sin intentos de marca |

Autoregulación RPE en la barra (nuevo respecto a N1-N15, decisión 3): en F3 de cada ciclo, el single pesado se trabaja a **RPE 9** (una rep en recámara, sin grinder). Si el RPE previsto se supera en el calentamiento (la barra de 90% se siente como 100%), el single del día baja 2.5-5 kg y se anota: el histórico de "singles honestos" vale más que cualquier número forzado. Una exposición pesada por línea (bisagra / overhead) por microciclo, con 48-72 h reales entre ellas (§10.5).

## Sesiones del Nivel 16

16 sesiones = 4 fases × tipos A/B/C/D, en orden. 5 bloques en orden, `restAfterBlock` 60 s.

### Sesión A — Fuerza élite: la barra de competición (todas las fases)

- Tipo: fuerza, series seguidas, autoregulación RPE. Duración estimada: 60-75 min.
- Bloque 0: variante A. Postural: tabla sesión A (hombro). Descarga: estándar.

**Bloque `fuerza_skill` (la barra del día rota por fase; cargas con autoregulación sobre las referencias de la tabla de fases):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Clean and Jerk` (F1, F3) / `Back Squat` (F2, F4) | C&J 5×1+1 (80/55 kg) / squat 4×3 (110/80 kg) | C&J 5×1 (90/60 kg) / squat 4×3 (115/82.5 kg) | C&J 6×1 a RPE 9 (100/65+ kg) / squat 4×2 suave (100/72.5 kg) | C&J 3×2 suave (70/50 kg) / squat 2×3 suave (90/65 kg) | 180 s | La barra del programa: recepción del `Squat Clean` en profundidad (CF1+CF3), `Split Jerk` con bloqueo decisivo (CF2); singles con descanso completo; 1 RIR en F3 — la marca se sube en kg, nunca en dudas |
| `Deadlift` (F1, F3) / `Snatch` (F2, F4) | DL 4×3 (145/105 kg) / snatch 5×2 (65/45 kg) | DL 4×3 (160/115 kg) / snatch 5×1-2 (75/55 kg) | DL 5×1 a RPE 9 (170/120+ kg) / snatch 6×1 (80/60 kg) | DL 2×3 suave (130/95 kg) / snatch 3×2 suave (55/40 kg) | 180 s (DL) / 150-180 s (snatch) | Bisagra y overhead en días alternos (§10.5): la bisagra pesada y el snatch nunca pican el mismo día en F3; barra pegada, bloqueo simultáneo (CF5+CF7); recepción del snatch en `Overhead Squat` profundo sin press-out (§10.4) |
| `Romanian Deadlift` | 3×5 (100/70 kg) | 3×5 (110/80 kg) | 3×4 (120/85 kg) | 2×5 suave | 120 s | Bisagra de mantenimiento con la carga de trabajo; CF7 impecable — la espalda del deadlift de ciclo se construye aquí |

**Bloque `wod` (metcon posterior a la barra — nunca carga de competición; §10.2):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | AMRAP 12' | 5 `Power Clean` (60/43 kg) + 10 `Air Squat` + 15 `Double Unders` | Carga técnica moderada en fatiga; recepción en power siempre disponible (§10.4) |
| F2 | AMRAP 14' | 3 `Hang Power Clean` (60/43 kg) + 6 `Kipping Handstand Push Up` + 9 `Kettlebell Swing Russian` (24/16 kg) | La barra del C&J aplicada bajo reloj; tope HSPU 30 |
| F3 | AMRAP 15' | 2 `Power Clean` (70/50 kg) + 4 `Chest to Bar Pull Up` + 12 `Double Unders` | Último metcon del ciclo con carga seria; si la recepción se corta, se baja y se anota (§10.4) |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 8 `Sit-up` | Movimiento; cierre de fase |

**Tips de sesión:** "La barra de hoy no se discute con el ego: el RPE 9 es un contrato — si la de calentamiento mintió, la del día baja. El histórico de singles honestos es lo único que envejece bien; el single forzado es una deuda que el tendón cobra con intereses (§1.7-CrossFit §10.3)."

### Sesión B — Benchmarks a fondo: el corazón del nivel (todas las fases)

- Tipo: benchmark RX + registro de marca. Duración estimada: 55-70 min.
- Bloque 0: variante B (preparación específica del benchmark del día). Postural: tabla sesión B (muñeca). Descarga: estándar.

**Rotación de benchmarks por fase** (ciclo base; el usuario puede sustituir por su benchmark prioritario del ciclo, decisión 4):

| Fase | Benchmark del día (RX) | Definición (catálogo) | Preparación específica en Bloque 0 |
|------|------------------------|-----------------------|------------------------------------|
| F1 | `Helen` (id 3) | FOR_TIME: 3 rondas de 400 m `Run` + 21 `Kettlebell Swing American` (24/16 kg) + 12 `Pull Up`; sin timeCap | Tobillo CF4 reforzado + comba; `Kettlebell Swing Russian` ligero en caliente |
| F2 | `Fran` (id 1) | FOR_TIME, timeCap 600 s: 21-15-9 de `Thruster` (43/29 kg) + `Pull Up` | Hombro/overhead (CF2) + `Thruster` ligero en caliente |
| F3 | `DT` (id 14) + `Amanda` (id 9) | DT: FOR_TIME sin timeCap: 5 rondas de 12 `Deadlift` + 9 `Hang Power Clean` + 6 `Push Jerk` (70/52 kg). Amanda: FOR_TIME, timeCap 600 s: 9-7-5 de `Ring Muscle Up` + `Squat Snatch` (60/43 kg) | Toma dura de marca: SOLO una de las dos (la que el atleta elige como objetivo del ciclo; la otra se hace "a fondo controlado" sin registro de marca). Trío completo en caliente (bisagra para DT, muñeca/anillas para Amanda); comba |
| F4 | `Nate` (id 60) o `Cindy` (id 4) | Nate: AMRAP 1200 s: 2 `Ring Muscle Up` + 4 `Kipping Handstand Push Up` + 8 `Kettlebell Swing American` (32/24 kg). Cindy: AMRAP 1200 s: 5 `Pull Up` + 10 `Push Up` + 15 `Air Squat` | Anillas + hombro en caliente; versión suave a dosis F4 (un punto por encima del rango, sin intento de marca) |

Otros benchmarks de la bolsa de rotación (sesiones B libres y cierres de sesión C): `Grace` (id 2), `Elizabeth` (id 8), `Daniel` (id 65), `Karen` (id 24), `Annie` (id 6), `Fight Gone Bad` (id 35), `Murph` (id 13, evento estrella de sesión C). Cada benchmark RX ejecutado y registrado alimenta su cadena de logros de rangos (§6).

**Bloque `fuerza_skill` (activación corta específica):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| Movimientos del benchmark en caliente | 2×5 de cada movimiento a carga ligera/moderada | 2×4 a carga de trabajo | 2×3 a carga RX | 2×5 suave | 60 s | Sin llegar al fallo ni al tope: el benchmark es el esfuerzo; esto es el encendido |

**Bloque `wod` (el benchmark del día):** el benchmark RX se ejecuta como round embebido apuntando al benchmark (formato §6 del spec), con el estándar RX del catálogo. Marca registrada en `UserBenchmarkAttempt` con `scalingUsed = rx`. **Una sola toma** en F2-F3 (formato torneo); en F1 y F4 puede repetirse a ritmo suave.

**Tips de sesión:** "El benchmark no se entrena hoy: se mide. Llegas con el trabajo de las sesiones A y D hecho, y lo que sale es lo que hay — esa honestidad es lo que hace comparable tu histórico ciclo a ciclo. Y recuerda: la marca RX con técnica rota no existe. Recepción cortada, pez sin rotación, rango muerto: se escala, se anota y se vuelve (§1.7-CrossFit §10.4). El rango que importa es el que sobrevive a la revisión de la cámara."

### Sesión C — Eventos Games: el torneo simulado (todas las fases)

- Tipo: evento combinado (formato competición). Duración estimada: 60-75 min.
- Bloque 0: variante C. Postural: tabla sesión C (lumbar). Descarga: estándar.

**Bloque `fuerza_skill` (activación del evento):**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas |
|-----------|----|----|----|----|----------|-------|
| `Clean and Jerk` (caliente) | 2×2 (60/43 kg) | 2×1 (70/50 kg) | 2×1 (80/55 kg) | 2×2 suave | 90 s | El primer evento en caliente; unidades limpias sin fatiga |
| `Ring Muscle Up` (caliente) | 2×2 | 2×3 | 2×3 | 1×2 | 60 s | El segundo evento en caliente; sin llegar al tope |

**Bloque `wod` (evento del día — orden de competición, tres eventos por sesión):**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | Evento combinado 1 (suave) | E1: 1 `Clean and Jerk` (70/50 kg) al minuto ×6 min; E2: AMRAP 8' de 3 `Ring Muscle Up` + 6 `Wall Ball` (9/6 kg); E3: AMRAP 8' de 5 `Chest to Bar Pull Up` + 10 `Air Squat` + 15 `Double Unders` | Apertura del ciclo: tres eventos con los 60 s de transición habituales; cada resultado anotado por separado |
| F2 | Evento combinado 2 | E1: `Clean and Jerk` 5×1 (80/55 kg) con 90 s; E2: AMRAP 10' de 4 `Ring Muscle Up` + 8 `Toes to Bar`; E3: `Grace` (id 2) a ritmo controlado — 30 `Clean and Jerk` (60/43 kg) FOR_TIME | Segundo torneo del ciclo; Grace como evento con marca (referencia de rangos §6 si se ejecuta RX 60/43) |
| F3 | Evento combinado 3 (competición) | E1: 1 `Clean and Jerk` (85/60 kg) + 1 (90/60 kg) + 1 (95/65 kg); E2: AMRAP 12' de 3 `Ring Muscle Up` + 5 `Kipping Handstand Push Up` + 8 `Kettlebell Swing American` (32/24 kg); E3: **Hero del ciclo** (rotación: `Murph` id 13, `Gunny` id 67, `Jerry` id 68, `Filthy 50` id 36) | El torneo completo del ciclo: pesado → gimnástico → Hero. Cargas élite solo en E1 (§10.2); en E2-E3 técnica RX siempre |
| F4 | AMRAP 8' suave | 8 `Air Squat` + 6 `Sit-up` | Movimiento; cierre de ciclo |

**Tips de sesión:** "Los Games no se ganan en el primer evento: se ganan en la capacidad de olvidarlo. Entra al E2 sin recordar el E1 y al E3 sin recordar nada. Tres veces en una mañana tienes que ser otra persona nueva — esa es la habilidad de competición, y se entrena aquí, ciclo a ciclo, con el mismo formato (§1.7-CrossFit)."

### Sesión D — Gimnásticos élite y motor (todas las fases)

- Tipo: gimnástico en volumen + monoestructural largo. Duración estimada: 55-70 min.
- Bloque 0: variante D (false grip en caliente). Postural: tabla sesión D (trío). Descarga: ampliada en F4.

**Bloque `fuerza_skill`:**

| Ejercicio | F1 | F2 | F3 | F4 | Descanso | Notas técnicas |
|-----------|----|----|----|----|----------|----------------|
| `Ring Muscle Up` (volumen) | 5×3 (tope 15) | 5×3-4 (tope 18) | 6×3 (tope 20) | 3×2 suave | 120 s | La MU de anillas como fondo de élite: sets limpios, descanso completo; transición completa siempre (§10.4); el volumen que sostiene los rangos de `Amanda` y `Nate` |
| `Bar Muscle Up` (volumen) | 4×2-3 | 5×3 (tope 15) | 5×3-4 (tope 18) | 3×2 suave | 120 s | La MU de barra en volumen de competición; throw-over limpio o `Chest to Bar Pull Up` (§10.4) |
| `Deficit Handstand Push Up` | 4×4 (placa fina) | 5×4 (2 placas) | 5×5 (2 placas, tope 30) | 3×3 suave (sin deficit) | 90 s | El overhead en rango amplio como carga de trabajo; si el rango muere, kipping plano (§10.4) |

**Bloque `wod`:**

| Fase | Formato | Contenido | Notas |
|------|---------|-----------|-------|
| F1 | 4 rondas | 3 `Bar Muscle Up` + 6 `Kipping Handstand Push Up` + 12 `Double Unders` | 60 s entre rondas; el dúo gimnástico en fatiga controlada |
| F2 | EMOM 18' | Impares: 3 `Ring Muscle Up` + 4 `Chest to Bar Pull Up`; pares: 10 `Toes to Bar` + 12 `Double Unders` | 9 MU + 12 C2B + 30 T2B por sesión: gimnástico puro en fatiga |
| F3 | AMRAP 25' (el motor del ciclo) | 5 `Thruster` (50/35 kg) + 7 `Chest to Bar Pull Up` + 12 `Double Unders` + 400 m `Run` | Cuatro modalidades con carrera: el fondo del evento combinado; ritmo de competición, negativa en la última parte |
| F4 | Monoestructural largo suave | 1 milla `Run` + `Rowing Machine` 2K + 1 milla `Run` (estilo `Jerry` suave) | Motor puro a ritmo conversacional; la base sobre la que todo lo demás flota |

**Tips de sesión:** "El gimnástico de élite es administración, no heroísmo: sets de 3 con 2 en recámara rinden más que sets de 6 con 3 buenas. Y el monoestructural largo es la cuenta bancaria del atleta: nadie lo ve, todos lo notan en el minuto 20 del Hero (§1.7-CrossFit)."

## Test de nivel N16: vacío (sin test de salida)

`testRequirements`: **vacío** (array sin ítems). Decisión 1 — replica el criterio de Olimpo en calistenia (`docs/detalle_niveles_13-15_olimpo.md`): el nivel infinito no se supera, se habita. La evaluación del nivel es continua y vive en tres lugares:

1. **Marcas de benchmarks RX registradas** (sesión B y eventos de sesión C) contra los rangos de §6 y las referencias de catálogo (beginner/intermediate/advanced/elite).
2. **Marcas de barra del ciclo** (sesión A F3): el single RPE 9 de C&J, snatch y deadlift, con histórico por ciclos.
3. **Re-tests informales de puertas** (opcionales, mismo criterio decisión 1 de los lotes 13-15): antes de un pico concreto (p. ej., CF2/CF6 antes de una sesión B con Amanda RX si hubo síntomas), como ensayo en Bloque 0. Nunca generan `mobility_blocked`.

El nivel permanece en estado activo dentro de `UserLevelProgress::ACTIVE_STATUSES` (el usuario vive en N16 para siempre; puede tener simultáneamente progreso en otros programas).

## Logros del nivel N16

### De rangos (el corazón del nivel)

Cada benchmark clave tiene 5 rangos ligados a marcas reales RX. Los umbrales se fijaron contrastados contra las referencias de catálogo (consulta 2026-10-10, tabla `benchmark`: beginner/intermediate/advanced/elite M/F) y siempre estrictamente bajo el `timeCapSeconds` del benchmark cuando existe. Tiers según spec §9: rangos bajos = `comun`/`poco_comun`, Oro = `raro`, Platino = `epico`, Diamante = `legendario`. Un logro por rango por benchmark (`type: hito`, desbloqueo manual/registrado vía marca; `requirementType`/`requirementKey` = el benchmark con su umbral).

> **Convención de umbral:** los rangos se anclan a la columna masculina de referencias por ser la más exigente; la columna femenina queda implícitamente cubierta con holgura en todos los casos (las referencias F son 15-40% más lentas). Ver contraste por benchmark en la última columna.

| Benchmark | Bronce (comun) | Plata (poco_comun) | Oro (raro) | Platino (epico) | Diamante (legendario) | Contraste con referencias BD (M / F) |
|-----------|----------------|--------------------|------------|-----------------|-----------------------|--------------------------------------|
| `Fran` RX (id 1, cap 600 s) | < 9:30 | < 7:30 | < 6:00 | < 4:30 | < 3:30 | beginner 10:00/13:00 · intermediate 6:30/8:30 · advanced 4:00/5:30 · elite 2:30/3:30. Bronce-Plata en banda beginner-intermediate; Oro bajo intermediate M; Platino en banda advanced; Diamante = elite F (3:30). Todos bajo el timeCap 600 s (beginner F 13:00 queda fuera por encima — conflicto conocido del lote 01-03) |
| `Helen` RX (id 3, sin cap) | < 16:00 | < 13:00 | < 11:00 | < 9:00 | < 7:30 | beginner 17:00/20:00 · intermediate 13:00/15:30 · advanced 9:30/11:30 · elite 7:00/8:30. Plata = intermediate M exacto; Platino entre advanced M y F; Diamante justo por encima de elite M |
| `Grace` RX (id 2, cap 600 s) | < 9:30 | < 6:30 | < 5:00 | < 3:30 | < 2:15 | beginner 8:00/10:00 · intermediate 5:00/6:30 · advanced 3:30/4:30 · elite 2:00/2:45. Plata = intermediate F exacto; Oro = intermediate M; Diamante en banda elite (2:00-2:45) |
| `DT` RX (id 14, sin cap) | < 18:00 | < 15:00 | < 12:00 | < 9:30 | < 6:30 | beginner 18:00/22:00 · intermediate 12:00/15:00 · advanced 8:00/10:00 · elite 5:00/6:30. Bronce = beginner M; Oro = intermediate M; Diamante = elite F exacto (el N15 pedía < 16:00/12:30: aquí empieza el juego de verdad) |
| `Amanda` RX (id 9, cap 600 s) | < 10:00 | < 8:30 | < 6:30 | < 4:30 | < 3:30 | beginner 14:00/17:00 (¡por encima del timeCap! conflicto documentado en el lote 13-15) · intermediate 8:00/10:30 · advanced 5:00/6:30 · elite 3:00/4:00. Bronce bajo el timeCap y bajo beginner M truncado; Oro bajo advanced F; Diamante entre elite M y F |
| `Elizabeth` RX (id 8, cap 900 s) | < 15:00 | < 11:00 | < 8:00 | < 5:30 | < 3:30 | beginner 15:00/19:00 · intermediate 10:00/13:00 · advanced 6:00/8:00 · elite 3:30/5:00. Bronce = beginner M exacto; Oro = advanced F; Platino entre advanced M y elite F; Diamante = elite M exacto |
| `Nate` RX (id 60, AMRAP cap 1200 s) | 6 rondas | 10 rondas | 15 rondas | 20 rondas | 25+ rondas | beginner 5-10 · intermediate 11-16 · advanced 17-24 · elite 25+. Bronce-Plata calzados en las bandas del catálogo; Diamante = elite exacto |
| `Cindy` RX (id 4, AMRAP cap 1200 s) | 8 rondas | 12 rondas | 15 rondas | 18 rondas | 20+ rondas | beginner 8/7 · intermediate 12/11 · advanced 15/14 · elite 20/18. Bronce = beginner M; Plata = intermediate M; Oro = advanced M; Diamante = elite M exacto |
| `Daniel` RX (id 65, cap 1200 s) | < 19:00 | < 16:30 | < 14:00 | < 12:00 | < 10:30 | beginner 19:30 · intermediate 18:00 · advanced 15:00 · elite 12:00/13:00 (unificado M/F salvo elite). Bronce bajo el timeCap 1200 s y bajo beginner; Platino = elite M exacto; Diamante por debajo del elite F |
| `Murph` RX (id 13, cap 4800 s, chaleco 9/6 kg catálogo) | < 65:00 | < 55:00 | < 45:00 | < 36:00 | < 32:00 | beginner 70:00/75:00 · intermediate 52:00/58:00 · advanced 40:00/46:00 · elite 32:00/36:00. Plata = intermediate F; Oro entre advanced F y M; Diamante = elite M exacto. **Nota:** el chaleco RX del catálogo es 9/6 kg, no el 20/15 de Gunny — ver FALTA |

**Criterio de fijación (decisión 2):** los umbrales se anclan a las referencias M (columna más exigente) y se calibran para que Oro ≈ intermediate M, Platino ≈ advanced, Diamante ≈ elite (la marca que "casi nadie tiene", regla de Olimpo §6). El Bronce es la puerta de entrada al juego de rangos (beginner M o truncado al timeCap). En AMRAPs (Nate, Cindy) los umbrales calzan exacto con las bandas del catálogo. Todos los umbrales lte fueron chequeados contra `timeCapSeconds`: Fran 600 ✔, Grace 600 ✔, Amanda 600 ✔ (con conflicto beginner documentado), Elizabeth 900 ✔, Daniel 1200 ✔, Murph 4800 ✔; Helen y DT no tienen timeCap (NULL) — sin techo duro, mismas salvedades que el lote 13-15.

### De récord/hito

- "Primer 2RM de C&J 100/65 kg" — primer doble de `Clean and Jerk` con la carga élite (acumulado o unificado) fuera de test. `tier: legendario`, `specialCelebration: true`.
- "Primer C&J de 105/70 kg" — primer single por encima de la marca N15. `tier: legendario`.
- "Primer snatch de 85/60 kg" — primer `Snatch` completo por encima de la marca N15. `tier: legendario`.
- "Primer deadlift de 180/125 kg" — primer single por encima de la marca N13. `tier: legendario`.
- "Primer evento Games completado" — primer evento combinado de tres estaciones en una sesión con los tres resultados anotados (heredado del N15, permanece desbloqueable para quien llegue sin él). `tier: epico`.
- "Primer Hero RX del Olimpo" — primer `Murph`/`Gunny`/`Jerry`/`Filthy 50` RX completado dentro de un evento de sesión C. `tier: legendario`.
- "Primer Fight Gone Bad completo" — primer registro de `Fight Gone Bad` (id 35, interval_stations, puntuación total) en sesión B/C. `tier: epico`.
- "Rango Oro en cualquier benchmark" — primer rango Oro desbloqueado. `tier: raro`.
- "Rango Platino en cualquier benchmark" — primer rango Platino desbloqueado. `tier: epico`.
- "Rango Diamante en cualquier benchmark" — primer rango Diamante desbloqueado. `tier: legendario`, `specialCelebration: true`.
- "Nuevo récord personal" — repetible infinitamente: cada marca propia batida en cualquier benchmark registrado (histórico en `UserBenchmarkAttempt`). `tier: comun`. Igual que los récords eternos de Olimpo §7: cada récord se registra con su contexto (ciclo, fase, modalidad) para que sea comparable.
- "Ciclo completo" — primer ciclo de 16 sesiones terminado con su Fase 4. `tier: comun`. Repetible (Ciclo 5, 10, 25… con tiers crecientes poco_comun/raro).

---

## Pendientes de catálogo (FALTA)

- Todos los ejercicios citados existen en el catálogo con la grafía usada (verificación por SQL 2026-10-10, `SELECT name FROM exercises ORDER BY name`): `Clean and Jerk`, `Snatch`, `Power Clean`, `Squat Clean`, `Hang Power Clean`, `Power Snatch`, `Hang Snatch`, `Push Jerk`, `Split Jerk`, `Push Press`, `Thruster`, `Back Squat`, `Front Squat`, `Deadlift`, `Romanian Deadlift`, `Overhead Squat`, `Bench Press`, `Ring Muscle Up`, `Bar Muscle Up`, `Muscle Up`, `Strict Handstand Push Up`, `Kipping Handstand Push Up`, `Deficit Handstand Push Up`, `Pull Up`, `Kipping Pull Up`, `Chest to Bar Pull Up`, `Toes to Bar`, `Ring Dip`, `Ring Support Hold`, `False Grip Hang`, `Scapular Pull Up`, `Kettlebell Swing American`, `Kettlebell Swing Russian`, `Wall Ball`, `Box Jump`, `Air Squat`, `Sit-up`, `Push Up`, `Burpee`, `Run`, `400m Run`, `1 Mile Run`, `5K Run`, `Rowing Machine`, `Assault Bike`, `Double Unders`, `Single Unders`, `Double Under Practice`, `Jumping Jack`, `Active March`, `Arm Circles`, `Hip Circles`, `Cat-Camel`, `Open Books`, `Foam Roll Thoracic Extension`, `Deep Squat Hold`, `Cossack Squat`, `Calf Stretch Wall`, `Bent-Knee Soleus Stretch`, `Shoulder Dislocate`, `Band Pull-Apart`, `External Rotation`, `Wrist Push-Up`, `Wrist Extension Stretch`, `Wrist Prayer`, `Dead Bug`, `Bird Dog`, `Pallof Press`, `Kettlebell Swing Russian`, `Towel Hang`, `Jefferson Curl`, `Scapular Push Up`, `Prone Y Raise`, `Face Pull`, `Child's Pose`, `Lat Stretch`, `Standing Quad Stretch`, benchmarks `Fran` (id 1), `Grace` (id 2), `Helen` (id 3), `Cindy` (id 4), `Annie` (id 6), `Elizabeth` (id 8), `Amanda` (id 9), `Murph` (id 13), `DT` (id 14), `Karen` (id 24), `Fight Gone Bad` (id 35), `Filthy 50` (id 36), `Nate` (id 60), `Daniel` (id 65), `Gunny` (id 67), `Jerry` (id 68).
- FALTA (no bloquea): `Chaleco` como ejercicio cargable no existe (heredado del lote 13-15): los WODs con chaleco se modelan como nota del benchmark. Ojo a la inconsistencia de catálogo: `Murph` (id 13) declara rx chaleco 9/6 kg mientras `Gunny` (id 67) usa chaleco 20/15 kg en descripción — los rangos de Murph §6 asumen el RX de catálogo (9/6 kg). Si el seeder quiere un Murph " Games" con chaleco 20/15, haría falta un ejercicio `Weighted Run`/`Run with Weight Vest` (misma conclusión que el lote 13-15).
- FALTA (no bloquea): `Clean` a secas sigue sin existir (solo variantes) — `Elizabeth` RX del catálogo pesa "Clean 60kg" y `Linda` (id 25) sigue sin poder mapearse a cargas absolutas. Elizabeth se ejecuta con `Squat Clean` o `Power Clean` según la recepción del atleta (RX permite ambas si el estándar se define así en el seeder; documentar la decisión al sembrar).
- FALTA (no bloquea): los EMOMs genéricos de catálogo (ids 46-49) y las escaleras/tabatas (ids 40-45, 50-52) no tienen referencias de tiempo — no entran en la tabla de rangos; quedan como material de sesiones libres.
- Conflicto timeCap conocido, heredado sin cambios: `Fran` (beginner F 13:00 > cap 600 s), `Amanda` (beginner 14:00/17:00 > cap 600 s), `Grace` (beginner F 10:00 > cap 600 s). Los umbrales de rangos quedan todos estrictamente bajo el timeCap; el conflicto de las referencias es de catálogo, no de este documento (precedente del lote 13-15).
- Chequeo timeCap en rangos nuevos: `Helen` y `DT` tienen `time_cap_seconds` NULL — sin corte duro de registro (misma salvedad que el lote 13-15: el timeCap efectivo es el del atleta). `Murph` cap 4800 s: todos los umbrales Diamante→Bronce (32:00-65:00) bajo el cap ✔.

## Decisiones de contenido tomadas (para revisión del controller)

1. **Test vacío (replica del criterio Olimpo):** `testRequirements` vacío, sin ítems obligatorios. El nivel no se supera: se habita en ciclos ilimitados y se mide con marcas registradas (benchmarks y barra) contra rangos y referencias. Sin `mobility_blocked` posible por test; los re-tests de puertas son informales y opcionales (herencia decisión 1 del lote 13-15). La entrada al nivel exige los mínimos N15 (regla §1.2), no un test propio.
2. **Anclaje de umbrales de rangos a la columna M:** las referencias del catálogo son M/F separadas; se fijaron los rangos en la columna M (más exigente) con el contraste documentado por benchmark en la tabla §6. El Bronce es puerta de entrada (beginner M o truncado al timeCap), Oro ≈ intermediate M, Platino ≈ advanced, Diamante ≈ elite (regla de Olimpo: "siempre hay un Diamante que casi nadie tiene").
3. **Autoregulación RPE en la barra (nuevo en N16):** los singles de F3 trabajan a RPE 9 con rebaja automática (2.5-5 kg) si el calentamiento miente. Criterio: en el nivel infinito la consistencia del histórico pesa más que el pico. Una exposición pesada por línea tendinosa por microciclo (§10.5).
4. **Rotación de benchmarks en sesión B con bolsa de sustitución:** la rotación base (Helen→Fran→DT+Amanda→Nate/Cindy) garantiza variedad de dominios (motor/barra corta/barra+anillas/gimnástico AMRAP); el usuario puede sustituir por su benchmark prioritario del ciclo (el elegido en la Fase 4 anterior), manteniendo la regla de **una marca dura por sesión B**.
5. **Hero rotativo en el evento 3 de sesión C F3:** Murph/Gunny/Jerry/Filthy 50 rotan por ciclo, dando al torneo simulado un "Hero del ciclo" distinto cada vuelta (decisión heredada del lote 13-15, que ya los documentaba como candidatos al Olimpo).
6. **§1.7 reforzada para cargas élite:** deadlift pesado y O-lifts de competición solo en `fuerza_skill`; en WODs, carga de competición con técnica rota = parar y anotar (no escalar a la baja y seguir: parar el evento). Es la versión élite de la regla §10.4, coherente con el estándar de competición.
7. **Logros de rango como `type: hito` con marca registrada** (no `test_item_*`, porque no hay test): el desbloqueo se dispara al registrar la marca RX en `UserBenchmarkAttempt` por debajo del umbral, igual que el desbloqueo por ítem de test pero con origen benchmark. Los rangos bajos (Bronce/Plata) usan tiers comun/poco_comun, Oro raro, Platino epico, Diamante legendario (tabla de tiers del spec §9).
8. **`isInfinite = true` y Fase 4 como cierre de ciclo (no de nivel):** la Fase 4 baja al 50%, cierra el ciclo con revisión de histórico y elección del benchmark prioritario del siguiente, y el usuario reinicia en Fase 1. Los logros "Ciclo completo" y sus múltiplos sostienen la motivación a largo plazo (récords eternos, regla 6 de Olimpo: la Fase 4 no es opcional).

---

## Resumen final del documento

- **N16 "Élite ∞":** nivel infinito tipo Olimpo (`isInfinite = true`), test vacío, 16 sesiones de rotación (4 fases × A/B/C/D: fuerza élite / benchmarks a fondo / eventos Games / gimnásticos élite y motor), ciclos ilimitados con autoregulación RPE.
- **Rangos Bronce→Diamante definidos para 10 benchmarks** (Fran, Helen, Grace, DT, Amanda, Elizabeth, Nate, Cindy, Daniel, Murph) con umbrales contrastados y documentados contra beginner/intermediate/advanced/elite de la BD y chequeados contra timeCap.
- **Logros:** ~50 logros de rango (5 × 10 benchmarks) + ~11 de récord/hito + "Nuevo récord personal" repetible.
- **FALTA:** chaleco como ejercicio cargable (Murph 9/6 vs Gunny 20/15), `Clean` a secas (Elizabeth/Linda), referencias por encima de timeCap en catálogo (defecto heredado, no bloqueante).
- **Continuidad desde N15:** C&J 100/65, RMU 12/18, DT RX 960/750 s y deadlift 170/120 asumidos como punto de partida y mantenidos/subidos por autoregulación.
