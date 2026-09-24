# Catálogo de Benchmarks CrossFit — Propuesta de contenido

> **Propósito**: ampliar el catálogo de benchmarks de OverWorkout con WODs que den vida a todos
> los filtros de formato (hoy: 32 for_time, 3 AMRAP, 1 EMOM, 1 Tabata, 1 Chipper, 1 Interval, 0 Ladder).
>
> **Fecha**: 2026-09-30 · **WODs propuestos**: 32 (7 Hero, 5 Ladder, 5 EMOM, 3 Tabata, 4 Chipper, 4 Interval stations, 4 AMRAP genéricos)
>
> **Convenciones**
> - Peso RX en kg (M/F). Conversiones oficiales: 95/65 lb → 43/29 kg · 53/35 lb → 24/16 kg · 135/95 lb → 61/43 kg · 155/105 lb → 70/47,5 kg · 185/125 lb → 84/56 kg · chaleco 50/35 lb → 20/15 kg.
> - Los WODs **oficiales** (Girls/Heroes/benchmarks reconocidos) están verificados contra fuentes públicas (CrossFit.com, WODwell y listados de la comunidad).
> - Los WODs **originales** (marcados con ⭐) están diseñados para cubrir formatos sin tradición de WODs con nombre.
> - **Ejercicios**: todos los nombres entre comillas existen en el catálogo (`Exercises`) verificado el 2026-09-30 (832 ejercigos). Ojo con la grafía exacta: `Sit-up`, `V-Up`, `Jump Lunge`, `Hollow Body Rock`, `Box Step Over` (no existe "Box Step Up" ni "Jumping Lunge").
> - **Tiempos de referencia**: orientativos (basados en datos de comunidad para oficiales; estimaciones de diseño para originales). Pueden ajustarse con datos reales de uso.
>
> **Campos por ficha** (mapeados a la entidad `Benchmark` / `BenchmarkExercise`):
> `tipo` = hero | girl | benchmark · `formato` = BenchmarkFormat · `rondas` = rounds (null si no aplica) ·
> `time cap` = timeCapSeconds · estructura = exercises[] (position, exercise, reps, rxWeight M/F, durationSeconds/calories) ·
> `referencia` = elite/advanced/intermediate/beginner time (M/F) · `escalado` = scalingOptions · `timer` = timerConfig sugerido.

---

## 1. LADDER (0 → 5 fichas)

Formato "escalera": las repeticiones suben o bajan por niveles. Los benchmarks oficiales con nombre
casi no existen en este formato (por eso el catálogo estaba vacío); todos son originales ⭐.

### 1.1 Escalera Ascendente 1-10 ⭐ — Tipo: benchmark · Formato: ladder

**Estructura:** For time · 10 niveles (1-2-3-...-10 de cada ejercicio)
**Time cap:** 18 min
**Descripción:** Couplet clásico ascendente. Cada nivel se completa antes de subir: 1 thruster + 1 pull-up, 2+2, ... hasta 10+10.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Thruster | 1→10 | 43/29 kg |
| 2 | Pull Up | 1→10 | — |

**Referencia (orientativo):** Élite M 6-8' / F 7-9' · Avanzado 9-11' · Intermedio 12-15' · Principiante 16-18'
**Escalado:** Thruster 30/20 kg o `Dumbbell Thruster` 2×15/10; `Australian Pull Up` o banda.
**Movements:** thruster, pull-up
**Timer:** `{"type": "stopwatch"}` · partitioningAllowed: false

### 1.2 Escalera Descendente 10-1 ⭐ — Tipo: benchmark · Formato: ladder

**Estructura:** For time · 10 niveles (10-9-8-...-1)
**Time cap:** 15 min
**Descripción:** Descendente de wall balls y box jumps. Ritmo sostenido; el último nivel debe ser un sprint.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Wall Ball | 10→1 | 9/6 kg |
| 2 | Box Jump | 10→1 | 24"/20" |

**Referencia (orientativo):** Élite M 5-6' / F 6-7' · Avanzado 7-9' · Intermedio 10-12' · Principiante 13-15'
**Escalado:** Wall ball 6/4 kg; `Box Step Over` en vez de salto.
**Movements:** wall-ball, box-jump
**Timer:** `{"type": "stopwatch"}`

### 1.3 Pirámide Deadlift — Burpee ⭐ — Tipo: benchmark · Formato: ladder

**Estructura:** For time · 9 niveles (2-4-6-8-10-8-6-4-2)
**Time cap:** 20 min
**Descripción:** Pirámide de peso muerto con burpee over bar. El pico (10-10) es el punto de inflexión; gestiona el agarre.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Deadlift | 2-4-6-8-10-8-6-4-2 | 100/70 kg |
| 2 | Burpee Over Bar | 2-4-6-8-10-8-6-4-2 | — |

**Referencia (orientativo):** Élite M 8-10' / F 9-11' · Avanzado 12-14' · Intermedio 15-17' · Principiante 18-20'
**Escalado:** Deadlift 70/50 kg; burpee sin salto sobre barra (lateral).
**Movements:** deadlift, burpee
**Timer:** `{"type": "stopwatch"}`

### 1.4 Escalera Kettlebell 1-10 ⭐ — Tipo: benchmark · Formato: ladder

**Estructura:** For time · 10 niveles (1→10 de cada ejercicio)
**Time cap:** 15 min
**Descripción:** Doblete de kettlebell: swing americano + goblet squat. Piernas y sistema metabólico.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Kettlebell Swing American | 1→10 | 24/16 kg |
| 2 | Kettlebell Goblet Squat | 1→10 | 24/16 kg |

**Referencia (orientativo):** Élite M 5-7' / F 6-8' · Avanzado 8-10' · Intermedio 11-13' · Principiante 14-15'
**Escalado:** KB 16/12 kg; `Kettlebell Swing Russian` (peor alcance).
**Movements:** kettlebell-swing, squat
**Timer:** `{"type": "stopwatch"}`

### 1.5 Escalera Gimnástica 2-12 ⭐ — Tipo: benchmark · Formato: ladder

**Estructura:** For time · 6 niveles (2-4-6-8-10-12 de cada ejercicio)
**Time cap:** 20 min
**Descripción:** Escalera de habilidad gimnástica: toes to bar + chest to bar. Exige core y agarre; rompe en series cortas planificadas.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Toes to Bar | 2-4-6-8-10-12 | — |
| 2 | Chest to Bar Pull Up | 2-4-6-8-10-12 | — |

**Referencia (orientativo):** Élite M 8-10' / F 9-11' · Avanzado 12-14' · Intermedio 15-17' · Principiante 18-20'
**Escalado:** `Toes to Bar Kipping` o `Knees to Chest` si existe; `Pull Up` normal o `Australian Pull Up`.
**Movements:** toes-to-bar, pull-up
**Timer:** `{"type": "stopwatch"}`

---

## 2. EMOM (1 → 5 fichas)

Every Minute On the Minute: al inicio de cada minuto se ejecuta la serie; el resto del minuto es descanso.

### 2.1 Death by Burpee ⭐ — Tipo: benchmark · Formato: emom

**Estructura:** EMOM abierto · minuto 1: 1 burpee, minuto 2: 2 burpees, +1 cada minuto hasta no poder completar
**Time cap:** 20 min
**Descripción:** El clásico "death by". La puntuación es el último minuto completado con éxito (p. ej. "14+12").

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Burpee | +1 cada minuto | — |

**Referencia (orientativo):** Élite 18+ min · Avanzado 14-17 · Intermedio 10-13 · Principiante 6-9
**Escalado:** Empezar en 3-4 burpees el minuto 1, o burpee sin flexión (`Jump Squat` + plancha).
**Movements:** burpee
**Timer:** `{"type": "emom", "intervalSeconds": 60}` · rondas: null (hasta fallo)

### 2.2 EMOM 12 Wall Ball / Toes to Bar ⭐ — Tipo: benchmark · Formato: emom

**Estructura:** 12 minutos · minutos impares: 15 wall balls · minutos pares: 12 toes to bar
**Time cap:** 12 min (fijo)

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Wall Ball | 15 (impares) | 9/6 kg |
| 2 | Toes to Bar | 12 (pares) | — |

**Referencia (orientativo):** Élite sin romper series · Avanzado ≤2 pausas · Intermedio WB 6/4 kg · Principiante 10 WB / 8 TTB
**Escalado:** Wall ball 6/4 kg; TTB kipping o `V-Up`.
**Movements:** wall-ball, toes-to-bar
**Timer:** `{"type": "emom", "intervalSeconds": 60}` · rondas: 12

### 2.3 EMOM 10 Barra ⭐ — Tipo: benchmark · Formato: emom

**Estructura:** 10 minutos · minutos impares: 3 power cleans · minutos pares: 3 push jerks
**Time cap:** 10 min (fijo)

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Power Clean | 3 (impares) | 70/47,5 kg |
| 2 | Push Jerk | 3 (pares) | 70/47,5 kg |

**Referencia (orientativo):** Élite 90/60 kg o unbroken · Avanzado RX · Intermedio 60/40 kg · Principiante 50/30 kg
**Escalado:** Reducir carga antes que reps; `Hang Power Clean` si la técnica del suelo falla.
**Movements:** power-clean, push-jerk
**Timer:** `{"type": "emom", "intervalSeconds": 60}` · rondas: 10

### 2.4 EMOM 14 Gimnástico ⭐ — Tipo: benchmark · Formato: emom

**Estructura:** 14 minutos · impares: 10 chest to bar · pares: 8 handstand push-ups estrictos
**Time cap:** 14 min (fijo)

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Chest to Bar Pull Up | 10 (impares) | — |
| 2 | Strict Handstand Push Up | 8 (pares) | — |

**Referencia (orientativo):** Élite sin romper · Avanzado HSPU kipping permitido (nota) · Intermedio `Pull Up` + `Pike Handstand Push Up` · Principiante `Australian Pull Up` + flexiones
**Escalado:** C2B → `Pull Up`; estricto → `Kipping Handstand Push Up` o pike; reducir a 6/6.
**Movements:** pull-up, handstand-push-up
**Timer:** `{"type": "emom", "intervalSeconds": 60}` · rondas: 14

### 2.5 EMOM 16 Motor ⭐ — Tipo: benchmark · Formato: emom

**Estructura:** 16 minutos · impares: 12 cal assault bike · pares: 10 kettlebell swings 24/16
**Time cap:** 16 min (fijo)

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Assault Bike | 12 cal (impares) | — |
| 2 | Kettlebell Swing | 10 (pares) | 24/16 kg |

**Referencia (orientativo):** Élite 15 cal / unbroken · Avanzado RX · Intermedio 10 cal · Principiante bike 8 cal + `Kettlebell Swing Russian` 16/12
**Escalado:** Ajustar calorías antes que swings; `Ski Erg` como alternativa al bike.
**Movements:** assault-bike, kettlebell-swing
**Timer:** `{"type": "emom", "intervalSeconds": 60}` · rondas: 16

---

## 3. TABATA (1 → 3 fichas)

20 segundos de trabajo / 10 de descanso × 8 rondas por ejercicio. Puntuación típica: suma de las repeticiones del intervalo más bajo de cada ejercicio.

### 3.1 Tabata This! — Tipo: benchmark · Formato: tabata — **OFICIAL**

**Estructura:** 8 rondas Tabata (20s/10s) de cada movimiento, con 1 min de descanso entre movimientos: Air Squat → Rowing Machine (cal) → Pull Up → Push Up → Sit-up
**Time cap:** ~24 min (fijo, según estructura)
**Descripción:** Benchmark oficial de CrossFit. Registra el mínimo de reps de cada bloque; la puntuación final es la suma de los cinco mínimos.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Air Squat | máx. en 20s | — |
| 2 | Rowing Machine | máx. cal en 20s | — |
| 3 | Pull Up | máx. en 20s | — |
| 4 | Push Up | máx. en 20s | — |
| 5 | Sit-up | máx. en 20s | — |

**Referencia (comunidad):** Élite 90+ puntos · Avanzado 70-89 · Intermedio 50-69 · Principiante <50
**Escalado:** `Australian Pull Up`, flexiones de rodillas, `GHD Sit Up` solo si está habituado.
**Movements:** squat, row, pull-up, push-up, core
**Timer:** `{"type": "tabata", "workSeconds": 20, "restSeconds": 10, "rounds": 8}` · rondas: 8

### 3.2 Tabata Core ⭐ — Tipo: benchmark · Formato: tabata

**Estructura:** 8 rondas Tabata de Hollow Body Rock, descanso 1 min, 8 rondas Tabata de V-Up
**Time cap:** ~10 min
**Descripción:** Trabajo de core anti-extensión + flexión. Puntuación: suma de mínimos de ambos bloques.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Hollow Body Rock | máx. en 20s | — |
| 2 | V-Up | máx. en 20s | — |

**Referencia (orientativo):** Élite 20+ / 25+ · Avanzado 15-19 / 20-24 · Intermedio 10-14 / 14-19 · Principiante 6-9 / 8-13
**Escalado:** `Hollow Body Hold` (estático) en vez de rock; `Sit-up` en vez de V-Up.
**Movements:** core
**Timer:** `{"type": "tabata", "workSeconds": 20, "restSeconds": 10, "rounds": 8}` · rondas: 8

### 3.3 Tabata Pistón ⭐ — Tipo: benchmark · Formato: tabata

**Estructura:** 8 rondas Tabata alternando cada intervalo: Double Unders (20s) / Air Squat (20s)
**Time cap:** ~8 min
**Descripción:** 16 intervalos en total (8 de cada movimiento). Puntuación: total de reps (dobles cuentan como 1).

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Double Unders | máx. en 20s | — |
| 2 | Air Squat | máx. en 20s | — |

**Referencia (orientativo):** Élite 300+ total · Avanzado 220-299 · Intermedio 140-219 · Principiante <140
**Escalado:** `Jump Rope` simples (cuenta doble) o saltos sin cuerda; air squat a `Box Step Over` bajo si hay molestias de rodilla.
**Movements:** jump-rope, squat
**Timer:** `{"type": "tabata", "workSeconds": 20, "restSeconds": 10, "rounds": 8}` · rondas: 8

---

## 4. CHIPPER (1 → 4 fichas)

Una sola vuelta larga, todo hacia adelante ("chip away"). El descanso entre ejercicios es el tiempo que tardas en cambiar de estación.

### 4.1 Filthy 30 ⭐ — Tipo: benchmark · Formato: chipper

**Estructura:** 1 vuelta: 30 box jumps, 30 jump squats, 30 kettlebell swings, 30 push-ups, 30 sit-ups, 30 cal assault bike
**Time cap:** 25 min
**Descripción:** Homenaje al Filthy 50 ya existente en el catálogo. Volumen moderado, ritmo constante; el bike final es la prueba.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Box Jump | 30 | 24"/20" |
| 2 | Jump Squat | 30 | — |
| 3 | Kettlebell Swing American | 30 | 24/16 kg |
| 4 | Push Up | 30 | — |
| 5 | Sit-up | 30 | — |
| 6 | Assault Bike | 30 cal | — |

**Referencia (orientativo):** Élite M 12-14' / F 13-15' · Avanzado 15-18' · Intermedio 19-22' · Principiante 23-25'
**Escalado:** Box step; `Kettlebell Swing Russian` 16/12; flexiones de rodillas.
**Movements:** box-jump, squat, kettlebell-swing, push-up, core, assault-bike
**Timer:** `{"type": "stopwatch"}` · rondas: 1

### 4.2 Big Wave ⭐ — Tipo: benchmark · Formato: chipper

**Estructura:** 1 vuelta: 50 wall balls → 40 box jump overs → 30 toes to bar → 20 power cleans → 10 bar muscle-ups → 400m run
**Time cap:** 25 min
**Descripción:** De lo metabólico a la barra y la gimnasia; termina corriendo. Ritmo conservador en los primeros 100 reps.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Wall Ball | 50 | 9/6 kg |
| 2 | Box Jump Over | 40 | 24"/20" |
| 3 | Toes to Bar | 30 | — |
| 4 | Power Clean | 20 | 60/40 kg |
| 5 | Bar Muscle Up | 10 | — |
| 6 | 400m Run | 1 | — |

**Referencia (orientativo):** Élite M 14-16' / F 16-18' · Avanzado 19-22' · Intermedio 23-25' · Principiante cap
**Escalado:** WB 6/4; `Pull Up` + `Box Jump Over` bajo en vez de BMU; power clean 50/35.
**Movements:** wall-ball, box-jump, toes-to-bar, power-clean, muscle-up, run
**Timer:** `{"type": "stopwatch"}` · rondas: 1

### 4.3 El Descenso ⭐ — Tipo: benchmark · Formato: chipper

**Estructura:** 1 vuelta: 50 kettlebell swings → 40 sit-ups → 30 box jumps → 20 power cleans → 10 strict handstand push-ups
**Time cap:** 22 min
**Descripción:** Pirámide de volumen invertido: empieza fuerte en swings y termina con habilidad de hombro bajo fatiga.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Kettlebell Swing American | 50 | 24/16 kg |
| 2 | Sit-up | 40 | — |
| 3 | Box Jump | 30 | 24"/20" |
| 4 | Power Clean | 20 | 60/40 kg |
| 5 | Strict Handstand Push Up | 10 | — |

**Referencia (orientativo):** Élite M 12-14' / F 14-16' · Avanzado 17-19' · Intermedio 20-22' · Principiante cap
**Escalado:** `Kipping Handstand Push Up` o `Pike Handstand Push Up`; cleans 50/35; `Box Step Over`.
**Movements:** kettlebell-swing, core, box-jump, power-clean, handstand-push-up
**Timer:** `{"type": "stopwatch"}` · rondas: 1

### 4.4 Tritón ⭐ — Tipo: benchmark · Formato: chipper

**Estructura:** 1 vuelta: 60 cal rowing → 50 wall balls → 40 kettlebell swings → 30 box jump overs → 20 push press → 10 bar muscle-ups
**Time cap:** 30 min
**Descripción:** Chipper largo de tres bloques: remo/metabólico, kettlebell/pierna y barra/gimnasia. Pensado para el bucket de duración larga (>40' en intermedio).

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Rowing Machine | 60 cal | — |
| 2 | Wall Ball | 50 | 9/6 kg |
| 3 | Kettlebell Swing American | 40 | 24/16 kg |
| 4 | Box Jump Over | 30 | 24"/20" |
| 5 | Push Press | 20 | 43/29 kg |
| 6 | Bar Muscle Up | 10 | — |

**Referencia (orientativo):** Élite M 18-21' / F 20-23' · Avanzado 24-28' · Intermedio 29-33' · Principiante 34-40'
**Escalado:** `Pull Up` en vez de BMU; wall ball 6/4; remo por calorías a ritmo sostenible (~70% esfuerzo).
**Movements:** row, wall-ball, kettlebell-swing, box-jump, push-press, muscle-up
**Timer:** `{"type": "stopwatch"}` · rondas: 1

---

## 5. INTERVAL STATIONS (1 → 4 fichas)

Estaciones rotativas con trabajo por tiempo (estilo Fight Gone Bad): rondas de 1 minuto por estación, descanso entre rondas. Puntuación: total de reps + calorías.

### 5.1 Tríplice ⭐ — Tipo: benchmark · Formato: interval_stations

**Estructura:** 3 rondas × 1 min por estación: Push Press → Sumo Deadlift High Pull → Box Jump → Push Up → Air Squat. Descanso 1 min entre rondas.
**Time cap:** ~20 min (fijo según estructura)
**Descripción:** Variante del Fight Gone Bad (ya en catálogo) con barra más ligera y box jump. Mide potencia de salida sostenida.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Push Press | máx. en 60s | 43/29 kg |
| 2 | Sumo Deadlift High Pull | máx. en 60s | 43/29 kg |
| 3 | Box Jump | máx. en 60s | 20" |
| 4 | Push Up | máx. en 60s | — |
| 5 | Air Squat | máx. en 60s | — |

**Referencia (orientativo):** Élite 400+ · Avanzado 320-399 · Intermedio 240-319 · Principiante <240
**Escalado:** Barra 30/20 kg; `Box Step Over`; flexiones de rodillas.
**Movements:** push-press, deadlift, box-jump, push-up, squat
**Timer:** `{"type": "interval", "workSeconds": 60, "restBetweenStationsSeconds": 0, "restBetweenRoundsSeconds": 60}` · rondas: 3

### 5.2 Estaciones de Remo ⭐ — Tipo: benchmark · Formato: interval_stations

**Estructura:** 3 rondas × (1 min Rowing Machine cal / 1 min Wall Ball / 1 min Box Jump Over). Descanso 1 min entre rondas.
**Time cap:** ~14 min (fijo según estructura)
**Descripción:** Tres estaciones metabólicas. La transición cuenta: no hay descanso entre estaciones dentro de la ronda.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Rowing Machine | máx. cal en 60s | — |
| 2 | Wall Ball | máx. en 60s | 9/6 kg |
| 3 | Box Jump Over | máx. en 60s | 24"/20" |

**Referencia (orientativo):** Élite 55+ cal y 90+ reps · Avanzado 45-54 / 75-89 · Intermedio 35-44 / 55-74 · Principiante <35 / <55
**Escalado:** `Ski Erg` o `Assault Bike` en vez de remo; wall ball 6/4.
**Movements:** row, wall-ball, box-jump
**Timer:** `{"type": "interval", "workSeconds": 60, "restBetweenRoundsSeconds": 60}` · rondas: 3

### 5.3 Aguja de Dobles ⭐ — Tipo: benchmark · Formato: interval_stations

**Estructura:** 4 rondas × 45s: Devil Press → Toes to Bar → Double Unders. Descanso 45s entre rondas.
**Time cap:** ~16 min (fijo según estructura)
**Descripción:** Intervalos cortos de alta densidad. Puntuación: total de reps (dobles = 1).

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Devil Press | máx. en 45s | 2×15/10 kg |
| 2 | Toes to Bar | máx. en 45s | — |
| 3 | Double Unders | máx. en 45s | — |

**Referencia (orientativo):** Élite 180+ · Avanzado 140-179 · Intermedio 100-139 · Principiante <100
**Escalado:** `Dumbbell Snatch` alterno ligero (2×10/6) en vez de devil press; `V-Up`; saltos simples.
**Movements:** devil-press, toes-to-bar, jump-rope
**Timer:** `{"type": "interval", "workSeconds": 45, "restBetweenStationsSeconds": 0, "restBetweenRoundsSeconds": 45}` · rondas: 4

---

## 6. AMRAP (3 → 7 fichas)

As Many Rounds As Possible en el tiempo fijado. Puntuación: rondas + reps.

### 6.1 Nate — Tipo: hero · Formato: amrap — **OFICIAL**

**Estructura:** AMRAP 20 min: 2 muscle-ups, 4 handstand push-ups, 8 kettlebell swings
**Time cap:** 20 min (fijo)
**Descripción:** Hero oficial en honor al CPO Nathan Hardy (US Navy SEAL). Gimnasia de alta habilidad + potencia; el muscle-up es el cuello de botella.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Muscle Up | 2 | — |
| 2 | Handstand Push Up | 4 | — |
| 3 | Kettlebell Swing American | 8 | 32/24 kg |

**Referencia (comunidad):** Élite 25+ rondas · Avanzado 17-24 · Intermedio 11-16 · Principiante 5-10
**Escalado:** `Bar Muscle Up` o `Chest to Bar Pull Up`; `Kipping Handstand Push Up` o `Pike Handstand Push Up`; KB 24/16.
**Movements:** muscle-up, handstand-push-up, kettlebell-swing
**Timer:** `{"type": "amrap", "durationSeconds": 1200}` · rondas: null

### 6.2 Motor City ⭐ — Tipo: benchmark · Formato: amrap

**Estructura:** AMRAP 12 min: 12 cal assault bike, 12 wall balls, 12 toes to bar
**Time cap:** 12 min (fijo)
**Descripción:** Triplete metabólico/gimnástico. Ritmo objetivo: completar cada vuelta en ~2 min.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Assault Bike | 12 cal | — |
| 2 | Wall Ball | 12 | 9/6 kg |
| 3 | Toes to Bar | 12 | — |

**Referencia (orientativo):** Élite 7+ rondas · Avanzado 5,5-6,5 · Intermedio 4-5 · Principiante 3
**Escalado:** 10 cal; WB 6/4; `V-Up` o `Toes to Bar Kipping`.
**Movements:** assault-bike, wall-ball, toes-to-bar
**Timer:** `{"type": "amrap", "durationSeconds": 720}` · rondas: null

### 6.3 Tres Amigos ⭐ — Tipo: benchmark · Formato: amrap

**Estructura:** AMRAP 15 min: 5 deadlifts, 10 bar-facing burpees, 15 air squats
**Time cap:** 15 min (fijo)
**Descripción:** Barra, burpee y sentadilla: el ciclo básico de CrossFit. Carga moderada, sin paradas en el deadlift.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Deadlift | 5 | 100/70 kg |
| 2 | Bar Facing Burpee | 10 | — |
| 3 | Air Squat | 15 | — |

**Referencia (orientativo):** Élite 9+ rondas · Avanzado 7-8 · Intermedio 5-6 · Principiante 3-4
**Escalado:** Deadlift 70/50 kg; burpee sin saltar la barra (lateral).
**Movements:** deadlift, burpee, squat
**Timer:** `{"type": "amrap", "durationSeconds": 900}` · rondas: null

### 6.4 Manivela ⭐ — Tipo: benchmark · Formato: amrap

**Estructura:** AMRAP 10 min: 10 kettlebell swings, 10 goblet squats, 10 sit-ups
**Time cap:** 10 min (fijo)
**Descripción:** Solo kettlebell y core. El goblet squat con la misma pesa que el swing lo convierte en retorno de inversión inmediato.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Kettlebell Swing American | 10 | 24/16 kg |
| 2 | Kettlebell Goblet Squat | 10 | 24/16 kg |
| 3 | Sit-up | 10 | — |

**Referencia (orientativo):** Élite 8+ rondas · Avanzado 6-7 · Intermedio 4-5 · Principiante 3
**Escalado:** KB 16/12 kg; `Kettlebell Swing Russian`.
**Movements:** kettlebell-swing, squat, core
**Timer:** `{"type": "amrap", "durationSeconds": 600}` · rondas: null

### 6.5 Aguja ⭐ — Tipo: benchmark · Formato: amrap

**Estructura:** AMRAP 20 min: 400m run, 15 push-ups, 10 pull-ups
**Time cap:** 20 min (fijo)
**Descripción:** Contraste carrera/gimnasia. Correr rápido pero dejando aire para el pull-up.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | 400m Run | 1 | — |
| 2 | Push Up | 15 | — |
| 3 | Pull Up | 10 | — |

**Referencia (orientativo):** Élite 9+ rondas · Avanzado 7-8 · Intermedio 5-6 · Principiante 3-4
**Escalado:** `Australian Pull Up`; flexiones de rodillas; 300m si no hay espacio.
**Movements:** run, push-up, pull-up
**Timer:** `{"type": "amrap", "durationSeconds": 1200}` · rondas: null

---

## 7. HEROES for_time (16 → 22 fichas)

Los Heroes oficiales más reconocidos que aún no están en el catálogo (los existentes: Abbate, Badger, Clovis, DT, Holleyman, Jason, JT, Kalsu, Michael, Murph, Randy, Roy, Ryan, Tommy V, Zachary).

### 7.1 Daniel — Tipo: hero · Formato: for_time — **OFICIAL**

**Estructura:** For time: 50 pull-ups, 400m run, 21 thrusters, 800m run, 21 thrusters, 400m run, 50 pull-ups
**Time cap:** 20 min (oficial)
**Descripción:** Hero oficial en honor al SFC Daniel Crabtree (US Army). Estructura piramidal: gimnasia → carrera → barra → carrera larga → barra → carrera → gimnasia.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Pull Up | 50 | — |
| 2 | 400m Run | 1 | — |
| 3 | Thruster | 21 | 43/29 kg |
| 4 | 800m Run | 1 | — |
| 5 | Thruster | 21 | 43/29 kg |
| 6 | 400m Run | 1 | — |
| 7 | Pull Up | 50 | — |

**Referencia (comunidad):** Élite M 11-13' / F 12-14' · Avanzado 14-16' · Intermedio 17-19' · Principiante 19-20' (cap)
**Escalado:** Thruster 35/25 kg; `Australian Pull Up`; series de 10 en los pull-ups.
**Movements:** pull-up, run, thruster
**Timer:** `{"type": "stopwatch"}` · rondas: 1 · partitioningAllowed: false

### 7.2 Josh — Tipo: hero · Formato: for_time — **OFICIAL**

**Estructura:** For time: 21 overhead squats + 42 pull-ups, 15 OHS + 30 pull-ups, 9 OHS + 18 pull-ups
**Time cap:** 30 min
**Descripción:** Hero oficial (US Coast Guard CPO Fernando Jorge). Pirámide descendente 21-15-9 sobre 42-30-18. La movilidad de hombro en OHS manda.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Overhead Squat | 21-15-9 | 43/29 kg |
| 2 | Pull Up | 42-30-18 | — |

**Referencia (comunidad):** Élite M 10-13' / F 12-15' · Avanzado 16-20' · Intermedio 21-26' · Principiante 27-30'
**Escalado:** OHS 30/20 kg o `Dumbbell Thruster`? No: OHS con mancuernas o front squat; `Australian Pull Up`.
**Movements:** overhead-squat, pull-up
**Timer:** `{"type": "stopwatch"}` · rondas: 1

### 7.3 Gunny — Tipo: hero · Formato: for_time — **OFICIAL**

**Estructura:** For time: 1 milla con chaleco 20/15 kg, 50 push-ups, 50 sit-ups, 1 milla con chaleco
**Time cap:** 40 min
**Descripción:** Hero oficial (Carl "Gunny" Hardin, USMC). Dos millas lastradas con gimnasia al centro. Correr conservador la primera milla es clave.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | 1 Mile Run (chaleco) | 1 | 20/15 kg |
| 2 | Push Up | 50 | — |
| 3 | Sit-up | 50 | — |
| 4 | 1 Mile Run (chaleco) | 1 | 20/15 kg |

**Referencia (comunidad):** Élite M 18-22' / F 20-24' · Avanzado 25-29' · Intermedio 30-35' · Principiante 36-40'
**Escalado:** Chaleco 10 kg o sin peso; `800m Run` ida y vuelta si no hay espacio; flexiones de rodillas.
**Movements:** run, push-up, core
**Timer:** `{"type": "stopwatch"}` · rondas: 1

### 7.4 Jerry — Tipo: hero · Formato: for_time — **OFICIAL**

**Estructura:** For time: 1 milla de carrera, 2K de remo, 1 milla de carrera
**Time cap:** 35 min
**Descripción:** Hero oficial (Sgt. Jerry Zangli). Monostructural puro: el 2K de remo se hace a ritmo de 2K test, no a paseo.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | 1 Mile Run | 1 | — |
| 2 | Row 2K | 1 | — |
| 3 | 1 Mile Run | 1 | — |

**Referencia (comunidad):** Élite M 16-18' / F 18-20' · Avanzado 21-24' · Intermedio 25-29' · Principiante 30-35'
**Escalado:** `Rowing Machine` por calorías equivalentes (p. ej. 45 cal) si no hay concepto de distancia; 1500m.
**Movements:** run, row
**Timer:** `{"type": "stopwatch"}` · rondas: 1

### 7.5 Whitten — Tipo: hero · Formato: for_time — **OFICIAL**

**Estructura:** 5 rondas for time: 22 kettlebell swings, 22 box jumps, 400m run, 22 burpees, 22 wall balls
**Time cap:** 50 min
**Descripción:** Hero oficial (Capt. Derek Whitten). Cinco rondas densas; el burpee después del run es el punto crítico. Rompe wall balls en 11-11 desde el principio.

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Kettlebell Swing American | 22 | 24/16 kg |
| 2 | Box Jump | 22 | 24"/20" |
| 3 | 400m Run | 1 | — |
| 4 | Burpee | 22 | — |
| 5 | Wall Ball | 22 | 9/6 kg |

**Referencia (comunidad):** Élite M 25-30' / F 28-33' · Avanzado 34-40' · Intermedio 41-47' · Principiante 48-50' (cap)
**Escalado:** KB 16/12; `Box Step Over`; WB 6/4. Mantener 5 rondas aunque reduciendo reps a 15.
**Movements:** kettlebell-swing, box-jump, run, burpee, wall-ball
**Timer:** `{"type": "stopwatch"}` · rondas: 5

### 7.6 Wittman — Tipo: hero · Formato: for_time — **OFICIAL**

**Estructura:** 7 rondas for time: 15 kettlebell swings, 15 power cleans, 15 box jumps
**Time cap:** 30 min
**Descripción:** Hero oficial (Lt. Justin "Wittman" Legg... registrado en comunidad como homenaje a un soldado caído). Triplete corto × 7: gestión de agarre (swing + clean) y pantorrilla (box jump).

| # | Ejercicio | Reps | Peso RX (M/F) |
|---|-----------|------|----------------|
| 1 | Kettlebell Swing American | 15 | 24/16 kg |
| 2 | Power Clean | 15 | 43/29 kg |
| 3 | Box Jump | 15 | 24"/20" |

**Referencia (comunidad):** Élite M 13-16' / F 15-18' · Avanzado 19-23' · Intermedio 24-28' · Principiante 29-30' (cap)
**Escalado:** KB 16/12; cleans 35/25 kg; `Box Step Over`.
**Movements:** kettlebell-swing, power-clean, box-jump
**Timer:** `{"type": "stopwatch"}` · rondas: 7

---

## Resumen: impacto en los filtros

| Formato | Antes | Nuevos | Después |
|---|---|---|---|
| for_time | 32 | 6 | 38 |
| amrap | 3 | 5 | 8 |
| emom | 1 | 5 | 6 |
| tabata | 1 | 3 | 4 |
| chipper | 1 | 4 | 5 |
| interval_stations | 1 | 4 | 5 |
| **ladder** | **0** | **5** | **5** |
| **Total** | **39** | **32** | **71** |

**Buckets de duración** tras la incorporación (según time cap): <15 min: escaleras 1-10/descendente/KB, EMOM 10-12-14 parcial, tabatas, Manivela, Motor City, Aguja (parcial) · 15-40 min: pirámide deadlift, gimnástica, Death by Burpee, EMOM 16, Filthy 30, Big Wave, Descenso, Tríplice, Estaciones, Dobles, Nate, Tres Amigos, Daniel, Josh, Wittman, Jerry · >40 min: Tritón, Gunny, Whitten.

## Notas de alta

1. Los WODs oficiales conviene introducir sus descripciones con el nombre de la persona a la que honran (campo `description`).
2. `timerConfig` es sugerencia de apoyo visual para el futuro reproductor; los valores exactos del JSON deben confirmarse con la implementación del player de benchmarks.
3. Para los tiempos de referencia (`*TimeMale/Female`) es mejor empezar con los rangos "intermedio/principiante" del documento y ajustar con datos reales tras unas semanas de uso.
4. Los pesos RX en kg son la conversión oficial estándar; si el box usa placas americanas, 43→40/45 según disponibilidad (registrar el valor real usado en `rxWeightMale/Female`).
5. ⭐ = WOD original OverWorkout; el resto son WODs oficiales de CrossFit verificados contra fuentes públicas (crossfit.com/heroes, WODwell, listados de comunidad) el 2026-09-30.
