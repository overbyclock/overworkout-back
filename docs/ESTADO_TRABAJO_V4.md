# Estado del trabajo — Calistenia Master v4.0 (7 ago 2026)

> Nota de contexto para retomar el trabajo en una sesión nueva. Léela junto a
> `docs/arquitectura_niveles_calistenia.md` (fuente de verdad) y la sección
> "Plataforma v4" de `AGENTS.md`.

## Hecho y verificado (tests en verde, PHPStan/CS-Fixer limpios, todo SIN COMMIT)

**Bloque 1 — Plataforma v4 (capacidades genéricas del sistema de programas):**
- Entidad `MobilityGate` (G1-G6, ligada a programa) + `GET /mobility-gates?program={id}`.
- Ítems de test tipados: `type` = `fuerza | puerta_movilidad | re_test` + `gateCode` (enum `TestItemType`).
- `TrainingProgram.testEvaluationMode`: `legacy` (defecto, v3 intacto) | `strict_gates` (v4: todos los mínimos + todas las puertas).
- Estado `mobility_blocked` en `UserLevelProgress`: fuerza superada + puerta fallada → ciclo corto de movilidad; re-test solo de puertas.
- Logros: `Achievement` + `tier`, `type` (test|hito), `level`, `specialCelebration`, `requirementKey`. Desbloqueo: por nivel (tipo test), por ítem de test (`test_item_min`/`test_item_obj`, se evalúa en CADA envío de test) y manual por hito (`POST /user/achievements/unlock`). Nueva tabla `user_achievement`. `GET /user/achievements` y flag `unlocked` en logros del programa.
- `TrainingRound.blockType`: preparacion | principal | postural | cadena_posterior | descarga (DTO, mapper y player incluidos).
- Migraciones aplicadas en dev y test: `Version20260725122007`, `Version20260725151508`.
- Fix: `GET /exercises/{id}` ahora serializa `media`, `equipment` y `disciplines` (avisar al frontend).

**Bloque 2 — Programa v4 con niveles 1-3 GENERADO y visible en el front:**
- Comando `php bin/console app:create-calistenia-master-v4` (opciones `--delete-existing`, `--levels`, `--slug`; idempotente). Archivos:
  - `src/Command/CreateCalisteniaMasterV4Command.php`
  - `src/Command/Blueprint/CalisteniaMasterBlueprintV4.php` (48 sesiones: 3 niveles × 4 fases × A/B/C/D, transcritas de `detalle_niveles_01-03.md`)
  - `src/Command/Blueprint/CalisteniaMasterContentV4.php` (tips, trainingWeeks, testRequirements, `getAchievements`, `getMobilityGates`, `getBlockName`/`getBlockDescription`, `getExerciseNote`)
- En BD: programa `calistenia-master-v4` (strict_gates, totalLevels 15, sin calendario → `estimatedDurationWeeks` null), niveles 1-3 ("Despertar", "Cimientos", "Primera Tracción"), 48 trainings, 240 rounds (5 bloques tipados por sesión), 6 puertas G1-G6, 17 logros (5+5+7, codes `v4-nX-...`; "PRIMERA FLEXIÓN COMPLETA" con specialCelebration; los logros por ítem llevan `requirementKey` = nombre literal del ítem del test).
- `skillFocus` relleno en los 3 niveles (si no, el front muestra "Sin skill definido" en SkillTracker): N1 "Plancha frontal, Colgado pasivo (Dead Hang)", N2 "Flexión completa, Hollow Body Hold, Support Hold", N3 "Dominada (tres vías), Diamond Push Up, Tuck L-Sit". **Al generar niveles 4-6, dar siempre skillFocus.**
- Sustitución documentada: "Split squat" no existe en catálogo → se usa `Stationary Lunge` con nota aclaratoria (ver cabecera del blueprint).

**Bloque 3 — Bloques con nombre y descripción (decisión del usuario, 27 jul):**
- `TrainingRound` tiene `name` (VARCHAR 100) y `description` (TEXT), ambos nullable, expuestos en API (mismos grupos que `blockType`), en `TrainingRoundDto`/`TrainingMapper` y como `blockName` en el payload de pasos del player (`TrainingSessionExpander`). Migración `Version20260727125311` (dev y test).
- Convención de nombres por blockType (helper en `CalisteniaMasterContentV4`): `preparacion` → "Preparación y movilidad"; `principal` según sesión → A "Fuerza — Empuje", B "Fuerza — Tracción", C "Circuito — Pierna y Core", D "Skill y Técnica" (su descripción = `goal` de la sesión); `postural` → "Salud postural"; `cadena_posterior` → "Cadena posterior y cadera"; `descarga` → "Descarga". **Mantener esta convención en niveles 4-6.**
- Naming en front: los tabs se muestran como "Bloque N" y las repeticiones internas del bloque (`setsForRound`) siguen llamándose **rondas** (decisión del usuario).
- Cambios en `overworkout-front` (sin commit): `api-adapters.js` (Bloque N + `title`/`description`/`blockType`), `SessionBlock.vue` (cabecera de bloque con nombre+descripción; pie "Descanso entre bloques" para `restAfterBlock`), `TrainingBuilder.vue` (preview "Bloque N").

**Bloque 4 — Niveles 4-6 GENERADOS (28 jul 2026):**
- Mismo comando con `--levels=4,5,6`. En BD: niveles 4 ("La Primera Dominada"), 5 ("Mundo Invertido") y 6 ("Proyección"), 48 trainings (240 rounds, 5 bloques tipados por sesión), tests con puertas G1+G2 en N4 y G4+G5 en N6 (N5 sin puertas), 25 logros nuevos (7+8+10; "¡PRIMERA DOMINADA!" y "TUCK FRONT LEVER" épicos con specialCelebration; los de puerta con tier poco_comun). skillFocus relleno en los 3.
- 13 ejercicios nuevos en el seeder (Neck Isometric Hold, Wrist Extension Stretch, Scapular Push Up, Seated Leg Raise, Archer Ring Row, Weighted Ring Row, Fingertip Hold, Ring Support Hold, False Grip Support Hold, Tuck Planche Toe Support, Band Assisted Tuck Planche, Single Leg Romanian Deadlift, Prone Y Raise) + 23 fichas de catálogo enriquecidas con el detalle del doc. `Fingertip Push Up` ya existía en catálogo (usado en Bloque 0 de N6).
- Mapeos destacados: chin-up con banda → `Assisted Pull Up - Supino`; step-up alto → `High Step Up`; "extensión torácica activa contra pared" → `Wall Slide` duplicado; singles/series máximas → reps `1`; omisiones sin equivalente en catálogo: estiramientos de tríceps/bíceps/glúteo y auto-estiramiento cervical de las descargas (igual que en N1-N3).

**Bloque 5 — Corrección de tiempos calculados (28 jul 2026):**
- Los `estimatedDurationMin/Max` salían 50-100% por encima de las duraciones del doc. Causas: el Bloque 0 (movilidad) se calculaba con descansos de fuerza (20 s entre ejercicios + 30 s entre series) y `addRestBetweenBlocks` fijaba 180 s tras CADA bloque (12 min fijos por sesión).
- Fix en `CalisteniaMasterBlueprintV4`: los 6 `block0L*` devuelven ahora `block(BLOCK_PREPARACION, 1, 1, 5, $exercises, 5)` (movilidad fluida) y `addRestBetweenBlocks` baja a 60/90/120 s (transición breve entre bloques, no descanso de recuperación). Aplicado regenerando niveles 1-6 con el comando (idempotente).
- Resultado: N1 30-54 min, N2 34-63, N3 38-79, N4 47-93, N5 48-97, N6 47-104. Quedan ~15-25% sobre el doc porque las duraciones del propio documento son optimistas (el contenido prescrito con sus descansos no cabe en ellas; p. ej. el Bloque 0 de N4 son ~21 ítems ≈ 15-19 min de trabajo real aunque el doc diga "8-10 min").
- Aproximación conocida: las dosis `/lado` se computan una sola vez (no ×2 lados) en `TrainingTimeCalculator`; aceptado como compensación razonable, no corregir sin decisión del usuario.

**Bloque 6 — Player v4 (29 jul 2026):**
- Back (commits `1223804..6744780`): el payload de pasos del player (`TrainingSessionExpander`) incluye `blockDescription` y, en los descansos `after_block`, metadatos del bloque destino (`nextBlockNumber/Type/Name/Description`). Los sobres de `start`/`getSession` exponen `skillFocus` + `estimatedDurationMin/Max`. `GET /user/trainings/next` devuelve duraciones, `skillFocus` y la lista de bloques del entrenamiento.
- Front (commits `f6f25d3..b2f06bc`): store con getters de bloques (`blocks/hasBlocks/currentBlock/blockProgress`) + `trainingService.getNext()`; barra segmentada por bloques y etiqueta "Bloque X de Y" en `TrainView`; `PlayerExerciseStep` con chip de bloque (con modificador de movilidad), notas inline, media inline y resumen de ficha (estándar + primer error común); `PlayerRestStep` con tarjeta enriquecida para descansos `after_block`; pantalla previa `SessionIntroView` (ruta `user-train-intro`, CTA del Home redirigido).
- Verificación: back verde (805 tests, PHPStan y CS-Fixer limpios); front verde (360 tests, 40 archivos).
- Follow-up pendiente (decisión del usuario): la intro (`user-train-intro`) hoy solo se alcanza desde el Home; falta decidir si LevelView/ProgramDetailView llevan también a la intro con training id.

**Bloque 7 — Niveles 7-9 GENERADOS (30 jul 2026):**
- Mismo comando con `--levels=7,8,9` (sin `--delete-existing`; niveles 1-6 y v3 intactos). En BD: niveles 7 ("Tuck y Extensión"), 8 ("Potencia") y 9 ("Muscle-Up"), 48 trainings (240 rounds, 5 bloques tipados por sesión), 32 logros nuevos (11+12+9; "TUCK PLANCHE", "¡L-SIT!" y "CHEST-TO-BAR" épicos; "¡MUSCLE-UP EN BARRA!" legendario con 200 XP; "¡PISTOL SQUAT!" épico). skillFocus relleno en los 3.
- Tests: N7 6 ítems fuerza sin puertas (G4/G5 se re-verifican informalmente); N8 6 fuerza + puerta G3; N9 4 fuerza + puerta G6 + re-tests de G1/G2 (ítems `type: re_test` + `gateCode`). Puertas G1-G6 reutilizadas, sin duplicados.
- 7 ejercicios nuevos en el seeder (Hanging Leg Raise, Tuck Back Lever, Advanced Tuck Back Lever, Assisted Muscle Up Transition, Straddle Fold, Adductor Rockback, Standing Pike Stretch). Ningún ejercicio omitido: todos los nombres del blueprint resuelven contra el catálogo.
- Mapeos destacados: pistol al cajón → `Box Pistol Squat`; dominadas explosivas → `Chest to Bar Practice`; muscle-up → `Bar Muscle Up`; tuck dragon flag → `Dragon Flag`; pike push-up elevado → `Elevated Pike Push Up`; wall handstand → `Chest to Wall Handstand`. Los "5 patrones de transición de pie" del Bloque 0 (N8/N9) se omiten como ejercicio (drill sin carga, anotado en comentario del código).
- Fase 3 de N8 (rotación de picos por ciclo): el blueprint es estático → sesión A con dosificación intermedia y la rotación impar/par explicada en `goal` y tips.
- Duraciones calculadas: N7 55-110 min, N8 59-112, N9 57-130 (la sesión B de N9 es la más larga del programa: singles de MU a 3 min, fiel al doc). Inflación sobre el doc similar al precedente aceptado de N6 (~+45-55% en las sesiones pesadas; los Bloque 0 de ~20 ítems con holds pesan en el cálculo). Ordenación relativa correcta (B > A > D > C, F4 más corta).
- Pendiente de commit: niveles 7-9 (blueprint + content + seeder) y este documento.

**Bloque 8 — Niveles 10-12 GENERADOS (7 ago 2026):**
- Mismo comando con `--levels=10,11,12` (sin `--delete-existing`; niveles 1-9 y v3 intactos). En BD: niveles 10 ("Vertical Fuerte"), 11 ("Straddle y Anillas") y 12 ("Full Levers"), 48 trainings (240 rounds, 5 bloques tipados por sesión), 45 logros nuevos (17+12+16). skillFocus relleno en los 3.
- Tests: N10 7 fuerza (HSPU pared 3/5, HS libre 5/10 s, MU 3/5, pistol 3/5 por pierna, press negativo 3/5, archer 3/5 por lado, false grip hang 20/30 s) + re-test G4; N11 5 fuerza (straddle FL 5 s, straddle planche 5 s, MU anillas 1, full BL 5 s, typewriter 3); N12 6 fuerza + re-tests G3/G5/G6. Los re-tests son ítems `type: re_test` + `gateCode`.
- ~20 ejercicios nuevos en el seeder (Pike Press Negative, False Grip Pull Up, Straddle Back Lever, Ring Muscle Up Transition, Vertical Flag, Handstand Push Up, Freestanding Handstand, Archer Push Up, Shrimp Squat, Nordic Curl Negative, Straddle Front Lever Hold, Straddle Planche, Ring Muscle Up, Back Lever Hold, Full Front Lever Hold, Planche, Freestanding Handstand Push Up, One Arm Push Up, Dragon Pistol Squat, Bulgarian Ring Dip). 0 nombres del blueprint sin resolver contra el catálogo.
- Omisión documentada (comentario en el código): "dominada explosiva" opcional de N11-B (sin equivalente en catálogo). Variantes asistidas del doc mapeadas al ejercicio base del catálogo con la asistencia explicada en el `goal` (p. ej. full planche con banda → `Planche`); "pancake activo" → `Straddle Fold`.
- Migración nueva `Version20260807084654`: amplía el CHECK `training_levels_chk_1` de `difficulty_rating` 1-10 a 1-15 (los niveles 11-12 violaban el CHECK; la arquitectura prevé 15 niveles). Aplicada en dev. **Ojo:** la BD de test NO se construye desde migraciones (las de 2024 no son replayables desde cero: falla `Version20241112220658` por columna `sets`); se recrea con `doctrine:database:drop/create --env=test` + `doctrine:schema:create --env=test` + `migrations:sync-metadata-storage` + `migrations:version --add --all`, por lo que el CHECK no existe en test (igual que antes).
- Duraciones calculadas coherentes con N7-N9 (misma escala): N10 61-118 min, N11 45-113, N12 44-112. Ordenación relativa correcta (B/D más largas, C circuito más corta, F4 la más corta).
- Verificación: back verde (805 tests, PHPStan y CS-Fixer limpios).
- Commit hecho: niveles 7-12 + migración + este documento (`a0b10bf` en back). Los cambios pendientes del front también están commiteados (`1a4370a` bloque 3, `6f61260` fixes del player).

## Decisiones tomadas por el usuario

1. v4 = programa nuevo en paralelo (`calistenia-master-v4`); v3 no se toca. Estructura universal y flexible.
2. Puertas = entidad `MobilityGate` propia. Logros = extender `Achievement` + tabla usuario. Bloques = `blockType`.
3. Movilidad en sesiones = duplicar ítems en `Exercises` (no FK polimórfica).
4. Rangos ("8-10 reps") = valor base en el campo + rango completo en `notes`.
5. Logros de test = desbloqueo por ítem, no solo por nivel.
6. (27 jul) Bloques con `name`+`description` desde backend; front pinta "Bloque N — nombre" + descripción; las repeticiones internas se llaman "rondas".

## Siguiente paso (pendiente de decidir al retomar)

- Todo commiteado (back y front). Queda pendiente de decisión: si LevelView/ProgramDetailView llevan también a la intro (`user-train-intro`) con training id (hoy solo se entra desde el Home).
- Después: niveles 13-15 cuando el usuario pase su detalle (la arquitectura prevé 15 niveles + Olimpo; el CHECK de `difficulty_rating` ya admite 1-15). Mismo procedimiento: leer "Convenciones del blueprint v4" en `AGENTS.md`, extender blueprint/content/seeder, generar con `--levels=13,14,15` y revisar duraciones.

## Notas sueltas

- Discrepancia pectoral en marco de puerta: resuelta a favor del detalle (entra en N3; N4 solo lo refuerza/dosifica).
- Pendientes de §7 de la arquitectura (5 preguntas abiertas: calibración de mínimos, gamificación, estado mobility_blocked en la app, G7 straddle, estructura global).
- Olimpo (bloque élite ∞) se deja para el final.
