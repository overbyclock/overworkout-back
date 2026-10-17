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

**Bloque 9 — Fix rendimiento detalle de programa (7 ago 2026):**
- Síntoma: la app no podía abrir el programa v4 ("Error al cargar los niveles del programa"): `GET /training-programs/{id}` tardaba ~15 s en v4 (timeout de axios del front: 10 s). v3 tardaba ~4 s.
- Causa raíz (medida con un comando temporal, ya borrado): NO era la BD — era el serializer. El endpoint serializaba el grafo completo (levels → trainings → rondas → configuraciones → ejercicios): 9,4 MB y ~12 s solo de serialización en v4 (6.400 configs), más ~3,5 s de lazy loading.
- Fix: `getById`/`getBySlug` ya no piden el grupo `training:read:detail` (usan `training:read`): el payload son niveles + datos básicos de trainings (123 KB, ~0,8 s). El detalle de rondas/ejercicios lo siguen sirviendo `/training-levels/{id}` y `/trainings/{id}` (de ahí lo sacan TrainingBuilder/SessionBlock/LevelTrainingsView; verificado que nadie leía `trainingRounds` desde el detalle de programa). Repositorio: `findDetailById/findDetailBySlug` con fetch join de levels+trainings.
- Test de contrato nuevo: `tests/Functional/Api/TrainingProgramDetailTest.php` (niveles + trainings básicos presentes, `trainingRounds` ausente).
- **Pendiente conocido:** `GET /training-programs/{id}/levels` (admin) sigue serializando el grafo completo (9,4 MB); no lo usa el front actual. Si se usa algún día, aplicar el mismo criterio.

**Bloque 10 — Niveles 13-15 + Bloque Élite Olimpo (nivel 16) GENERADOS:**
- Campo `isInfinite` añadido a `TrainingLevel` (con DTOs, mapper, tests y migración `Version20260831121316`) para modelar Olimpo como nivel 16 sin test de salida.
- Comando `php bin/console app:create-calistenia-master-v4 --levels=13,14,15,16` generó sin omisiones: niveles 13 ("Fuerza Unilateral"), 14 ("One-Arm Chin-Up"), 15 ("One-Arm Pull-Up") y 16 ("Olimpo"), 64 trainings en total (16 por nivel), 320 rounds con 5 bloques tipados por sesión. Olimpo guarda `isInfinite = true`.
- 11 fichas nuevas añadidas al seeder (`Advanced Tuck Planche Push Up`, `Retract Row`, `Miniband Lateral Walk`, `Lateral Plank Raise`, `Human Flag 45 Degree`, `Straddle Planche Push Up`, `One Arm Pull Up Negative`, `Assisted One Arm Pull Up`, `Partial 90 Degree Push Up`, `Partial Impossible Dip`, `Assisted Tiger Bend Push Up`); el resto de ejercicios del blueprint resolvieron contra ejercicios ya existentes del catálogo (variantes asistidas/parciales mapeadas a fichas madre con la asistencia explicada en el `goal` de la sesión).
- Tests: N13 6 fuerza (dominada +25% BW, L-sit pull-up, human flag, V-sit, straddle press, Nordic curl); N14 5 fuerza (OAC, planche PU, 90° PU, front lever raises, +50% BW) + hitos de peldaño; N15 6 fuerza (OAP, +50% ×3, tiger bend, impossible dip, iron cross asistido, maltese asistido); N16 sin test de salida, con logros de rangos Bronce→Diamante, combos de estilo y hitos.
- 88 logros nuevos en total: 10 (N13) + 12 (N14) + 7 (N15) + 59 (Olimpo, incluyendo rangos de 9 skills legendarios).
- Duraciones calculadas coherentes con la escala N10-N12: N13 ~40-113 min, N14 ~38-124 min, N15 ~39-107 min, N16 ~39-111 min. Ordenación relativa correcta (B/D más largas, C más corta, F4 la más corta).
- Verificación: back verde (809 tests, PHPStan y CS-Fixer limpios).
- Commit pendiente de merge a `main`.

## Decisiones tomadas por el usuario

1. v4 = programa nuevo en paralelo (`calistenia-master-v4`); v3 no se toca. Estructura universal y flexible.
2. Puertas = entidad `MobilityGate` propia. Logros = extender `Achievement` + tabla usuario. Bloques = `blockType`.
3. Movilidad en sesiones = duplicar ítems en `Exercises` (no FK polimórfica).
4. Rangos ("8-10 reps") = valor base en el campo + rango completo en `notes`.
5. Logros de test = desbloqueo por ítem, no solo por nivel.
6. (27 jul) Bloques con `name`+`description` desde backend; front pinta "Bloque N — nombre" + descripción; las repeticiones internas se llaman "rondas".

## Siguiente paso (pendiente de decidir al retomar)

- Todo commiteado (back y front). Queda pendiente de decisión: si LevelView/ProgramDetailView llevan también a la intro (`user-train-intro`) con training id (hoy solo se entra desde el Home).
- La arquitectura v4 de calistenia está completa hasta el Bloque Élite Olimpo (15 niveles + Olimpo). Nuevos programas o ajustes de gamificación quedan para futuras iteraciones.

## Bloque 11 — Benchmarks CrossFit (oct 2026)

- Nuevo seeder `scripts/seeders/seed_benchmarks_crossfit_v2.php`: 31 fichas transcritas de `docs/catalogo_benchmarks_crossfit.md` (5 Ladder, 5 EMOM, 3 Tabata, 4 Chipper, 3 Interval stations, 5 AMRAP —incl. Nate hero—, 6 Heroes for_time: Daniel, Josh, Gunny, Jerry, Whitten, Wittman). Ejecutado en dev e idempotente (re-corrible, find-or-update por nombre).
- Ojo: el resumen del doc anuncia 32 WODs/71 totales, pero solo hay 31 fichas especificadas (interval_stations cuenta 4 y lista 3). En BD queda: for_time 38, amrap 8, emom 6, tabata 4, chipper 5, interval_stations 4, ladder 5 = **70**.
- Convenciones aplicadas: tiempos de referencia = punto medio del rango del doc en 'MM:SS'; puntuaciones no temporales (rondas/pts/reps) como texto ('25+ rounds', '400+ pts'); ladder y pirámides desplegados en filas por nivel (criterio JT); `timerConfig` del doc para emom/tabata/amrap/interval y null para stopwatch (consistente con el catálogo existente); partitioningAllowed false en todos.
- 34 ejercicios resueltos estrictos contra el catálogo (ninguno creado). Front sin cambios: los filtros por formato ya funcionan con los datos.

## Bloque 12 — Eliminación de Calistenia Master v3 (sep 2026)

- Decisión del usuario: **v4 es la versión definitiva; v3 eliminada por completo** (revierte la decisión histórica "v3 no se toca" de AGENTS.md/§decisiones).
- Datos: script `scripts/migrations/delete_calistenia_master_v3.php` (con `backup_pre_v3_delete.php` ejecutado antes; dump en `var/backups/`). Borró 2.345 registros (12 niveles, 192 trainings, 416 rondas, 1.647 configs, 48 week info, 9 skills, 21 logros + programa). Protección anti-datos-de-usuario: aborta si detecta progresos/tests/logs/favoritos/logros desbloqueados (en dev había 0). Programas en BD: handstand-balance-mastery, front-lever-mastery, calistenia-master-v4.
- Código eliminado: 9 comandos legacy (`app:create-calistenia-master{,-level1,-v2,-v3}`, `app:patch-calistenia-master-v3`, `app:fix-calistenia-master-program`, `app:setup-calistenia-schema`, `app:fix-training-phase-names`, `app:seed-program-skills-achievements`), 6 clases Blueprint/Content v1-v3 + 2 tests unitarios, `scripts/generate_calistenia_report.php`. Recuperables desde git si hiciera falta.
- **Fix funcional**: `UserProfileService::PROGRAM_SLUG_CALISTHENICS` apuntaba a `calisthenia-master-v3` (recomendación del onboarding) → ahora `calistenia-master-v4`. Sin este fix el recomendador devolvía null.
- Se mantiene: comando/blueprints v4, `testEvaluationMode: legacy` (lo usan handstand y front-lever), migraciones históricas.

## Bloque 13 — Muscle Up Mastery (sep 2026)

- Decisión del usuario: los programas de skill son **especializaciones pequeñas del Master v4** para usuarios que no quieren el master completo (mismo nivel de detalle, formato corto). Contenido minado del Master, no inventado.
- Nuevo programa `muscle-up-mastery` ("Muscle Up Mastery", slug `muscle-up-mastery`): comando `app:create-muscle-up-mastery` + `MuscleUpBlueprint` + `MuscleUpContent` (réplica exacta de la arquitectura Front Lever: 6 niveles × 4 fases × 4 sesiones = 96 trainings, nivel = 4 semanas, legacy mode, sin calendario, icono `accessibility_new`).
- Niveles: 1 Base de tracción/empuje · 2 Piezas (C2B alto + transición + SBD, del peldaño del N9) · 3 Primera MU (6×1 a 3-4 min, fiel al N9) · 4 Consistencia · 5 Anillas (false grip, del N11) · 6 Dominio (lastrada/L-sit/variedades, del N13+). Reglas §1.7 importadas al codo en tips y descansos.
- 1 ejercicio nuevo en catálogo: `Dip` (no existía en plano; solo Ring Dip). Todo lo demás resolvió contra catálogo v4 (MU exercises sembrados en su día).
- En BD dev: 6 niveles, 96 trainings, duraciones 6-58 min (la B de L3 con singles a 3-4 min es la más larga, coherente con el propio N9 del Master). Tests de dominio con mínimos/objetivos por nivel.
- Verificación: suite verde, PHPStan/CS-Fixer limpios.

## Bloque 14 — False Grip Mastery (oct 2026)

- Decisión del usuario: nueva especialización de falso agarre, **8 niveles** (más detalle que MU Mastery) × 4 fases × 4 sesiones = 128 trainings (~32 semanas). Misma filosofía: contenido minado del Master v4 y de MU Mastery, no inventado.
- Nuevo programa `false-grip-mastery` ("False Grip Mastery", difficulty `advanced`): comando `app:create-false-grip-mastery` + `FalseGripBlueprint` + `FalseGripContent` (réplica de la arquitectura MU Mastery: sesiones A=Strength / B=False Grip / C=Prehab muñeca / D=Skill anillas; legacy mode, sin calendario).
- Arco: 1 Base de muñeca y agarre (oraciones + wrist push-ups de rodillas + dead hangs, dosis N1-N2) · 2 Introducción al falso agarre (hang 5-10 s + support 5-8 s, dosis N6/N8) · 3 Falso agarre básico (hang 15 s + filas FG 5-8, dosis N8-N9) · 4 Falso agarre estable (hang 20 s = gate N10 + support 10 s, dosis N11-B/N6-D) · 5 Tracción con falso agarre (negativas/asistidas + hang 25 s + German hang 30 s, dosis MU L3/L4) · 6 Primer False Grip Pull-Up (3-5, dosis MU L4-C) · 7 Volumen y anillas (Ring Pull Up FG 5-8 + transición/pimeras ring MU, dosis MU L5/N11-D) · 8 Dominio (ring MU 1-3 + FG PU 8-12 + hang 45-60 s, dosis MU L6/N15-N16). Reglas §1.7 aplicadas a muñeca/codo en tips y descansos.
- **0 ejercicios nuevos**: todo resolvió contra catálogo (MU Mastery ya sembró `Dip`, `Ring Pull Up - False Grip`, `Ring Muscle Up Transition`, etc.). 0 omisiones (ningún warning al generar).
- En BD dev: 8 niveles, 128 trainings, duraciones 8.6-45 min, ordenación correcta (intensificación > progresión > base > deload; B/D más largas que A/C; C prehab la más corta). Nivel 8 verificado sesión a sesión.
- Sin logros (igual que MU Mastery), sin migraciones (solo datos), sin cambios de front.
- Verificación: 511 tests OK, PHPStan nivel 5 limpio, CS-Fixer 0 fix.

## Bloque 15 — Back Lever Mastery + Flag Mastery (oct 2026)

- Decisión del usuario: dos especializaciones **separadas** de 8 niveles × 4 fases × 4 sesiones (128 trainings, ~32 semanas cada una), con la filosofía redefinida: **base y estructura del Master pero ampliada y más específica** (no solo minería). Verificación automatizada vía agentes; los dos cayeron por límite de cuota API tras escribir los ficheros, la verificación se completó inline.
- Nuevo programa `back-lever-mastery` ("Back Lever Mastery"): comando `app:create-back-lever-mastery` + `BackLeverBlueprint` + `BackLeverContent`. Arco: 1 Base de extensión de hombro (GH 10-20 s) · 2 Primer tuck (5-8 s) · 3 Tuck consolidado (8-10 s, gate N8) · 4 Advanced tuck (4-8 s, dosis N9) · 5 Straddle entrada (4-6 s, N10) · 6 Straddle sólido (6-8 s) · 7 Full back lever (3-5 s + asistida, N11) · 8 Dominio (full 8-10 s, GH 60 s). Dosis minadas de sessionD de N8-N11 del Master. Duraciones estrictamente crecientes: 8.7→52 min por nivel (min y max monotónicos).
- Nuevo programa `flag-mastery` ("Flag Mastery"): comando `app:create-flag-mastery` + `FlagBlueprint` + `FlagContent`. El Master casi no desarrolla la bandera (vertical en N12, 3 s en test N13) → ampliación mayor: 1 Base tracción/core lateral · 2 Primer vertical (asistida 5-10 s) · 3 Bandera vertical (10-15 s + raises) · 4 Tuck flag (3-5 s) · 5 Bandera 45° (3-5 s) · 6 45° sólida (5-10 s) · 7 Straddle flag (Human Flag 3-5 s con nota de asistencia) · 8 Bandera horizontal (3-5 s, meta del N13). Duraciones 25→50 min crecientes; anomalía aceptada: la A de intensificación de N3 (pull-up 12-15×4 + chin 12-15×4 + lastrada 6-8×4) pesa más que la de N4, coherente con la progresión de tracción del programa.
- **0 ejercicios nuevos**: BL resolvió contra catálogo existente (Tuck/Adv Tuck/Straddle Back Lever, Back Lever Hold, Back Lever Row); la escalera completa de bandera ya estaba (Vertical Flag, Tuck Flag, Human Flag 45 Degree, Human Flag, Human Flag Raise, Dragon Flag, side planks, Copenhagen). 0 omisiones en ambos.
- Sin logros, sin migraciones, sin cambios de front. Reglas §1.7 (brazo recto: hombro/muñeca) en tips de ambos.
- Verificación: 511 tests OK, PHPStan nivel 5 limpio, CS-Fixer 0 fix, ambos comandos re-ejecutables (idempotentes).

## Bloque 16 — One-Arm Pull-Up Mastery (oct 2026)

- Decisión del usuario: especialización de tracción unilateral élite, 8 niveles × 4 fases × 4 sesiones (128 trainings, ~32 semanas). Filosofía reafirmada por el usuario: **no solo minar el Master, sino ampliar y ser más específico** en la skill concreta (peldaños intermedios que en el Master no son protagonistas).
- Nuevo programa `one-arm-pull-up-mastery` ("One-Arm Pull-Up Mastery"): comando `app:create-one-arm-pull-up-mastery` + `OneArmPullUpMasteryBlueprint` + `OneArmPullUpMasteryContent`. Sesiones: A=Strength lastrada / B=Unilateral / C=Prehab codo-hombro (codo de golfista = lesión típica del OAP: German hang, face pull, Wrist Roller) / D=Skill.
- Arco: 1 Base · 2 Fuerza máxima (+15% BW) · 3 Lastrada sólida (+25% ×3, N13) · 4 Unilateral asistida · 5 Camino al OAC (negativas, parciales top/bottom half) · 6 Primer OAC (+50% ×1, N14) · 7 OAC → OAP (negativas 10-15 s) · 8 **One-Arm Pull-Up 1/3 por brazo** (N15) con simetría estricta.
- Peldaños ampliados mapeados a catálogo con notas (sin inventar ejercicios): colgado a un brazo → `Dead Hang`+nota, one-arm scapular pull → `Scapular Pull Up`+nota, archer hold, parciales de rango → `One Arm Pull Up`+nota. Confirmados en catálogo: `One Arm Chin Up`, `One Arm Pull Up`, `One Arm Pull Up Negative`, `Assisted One Arm Pull Up`. **0 ejercicios nuevos, 0 omisiones.**
- Duraciones: intensificación > progresión > base > deload en todos los niveles; pico A de N8 ajustado a ~48-53 min (restSets 180 s en N7-8).
- Verificación: 511 tests OK, PHPStan limpio, CS-Fixer 0 fix.

## Bloque 17 — Planche Mastery (oct 2026)

- Especialización `planche-mastery` ("Planche Mastery", `app:create-planche-mastery` + `PlancheBlueprint` + `PlancheContent`): 8 niveles × 4 fases × 4 sesiones (128 trainings, ~32 semanas, advanced). Sesiones: A=Strength empuje / B=Planche / C=Prehab muñeca-hombro / D=Skill (lean, L-sit, compresión).
- Arco: 1 Base de empuje y muñeca · 2 Frog y primer tuck (toe-support, dosis N6) · 3 Tuck planche 5-10 s + lean (gate N6/N7) · 4 Advanced tuck + PPPU (N7/N8) · 5 Straddle entrada 4-6 s (N10) · 6 Straddle planche 5-8 s + press (N11) · 7 Full planche con banda 3-5 s (N14) · 8 **Dominio: planche 5-8 s + planche push-up 1-3 + maltese asistido** (N15/Olimpo). Test N1: Push Up 15/20, Pike Push Up 5/8, Wrist Push-Up 5/8.
- 0 ejercicios nuevos (variantes asistidas → ficha base + nota; `90 Degree Push Up` existe pero se usó `Planche Push Up` con nota). Duraciones: intensificación > progresión > base > deload en los 8 niveles (pico N8 ~127-139 min por fase completa). §1.7 muñeca/hombro en tips.
- Verificación: 511 tests OK, PHPStan limpio, CS-Fixer 0 fix.

## Bloque 18 — 4 especializaciones v4 + rebuild de legacy (oct 2026)

- Fases ejecutadas por agentes en secuencia, todas con 8 niveles x 4 fases x 4 sesiones (128 trainings, ~32 semanas, advanced, legacy, sin calendario) y verificacion completa (0 omisiones, ordenacion de duraciones OK, 511 tests):
  - **L-Sit & Press Mastery** (`app:create-l-sit-press-mastery`): compresion -> tuck L-sit -> L-sit -> V-sit -> straddle press -> press a pino. Bonus de catalogo: `V-Sit Hold`, `Straddle Press Handstand`, `L-Sit One Leg`, `Pike Press to Handstand`, `Wall Handstand Hold`.
  - **Handstand Push-Up Mastery** (`app:create-handstand-push-up-mastery`): pike -> HSPU pared (1->3->5->8) -> despegues libres -> FS HSPU 1->3->5. Bonus: `HSPU Negative`, `One-Arm Handstand Push Up` (L8 asistida).
  - **Pierna Mastery** (`app:create-pierna-mastery`): base unilateral -> pistol (asistido->1->3->5->8->10->12) -> **primer Nordic** (N13) -> Nordic 5-8 + dragon pistol. Bonus: `Assisted Pistol Squat`, `Nordic Curl with Band`, `Glute Ham Raise Negative`, `Jumping Pistol Squat`. Tibialis: omitido (sin ficha).
  - **Rebuild legacy a v4** (mismos slugs, sin borrar programas; `removeTrainingLogs()` limpia logs residuales de dev antes de recrear niveles): **Front Lever Mastery v4** (`app:create-front-lever-mastery-v4`, slug `front-lever-mastery`): tuck -> adv tuck -> one-leg -> straddle -> full 5-10 s. **Handstand Balance Mastery v4** (`app:create-handstand-balance-mastery-v4`, slug `handstand-balance-mastery`): chest-to-wall -> pirueta -> FS HS 5->15->30->45-60 s. Bonus: `Handstand Bail Out`, `Kick-up to Handstand`, `Front Lever Raise`.
- Catalogo de programas final: Calistenia Master v4 + 10 especializaciones (False Grip, Back Lever, Flag, OAP, Planche, L-Sit&Press, HSPU, Pierna, Front Lever v4, Handstand Balance v4).
- Verificacion: 511 tests OK, PHPStan limpio, CS-Fixer 0 fix.

## Notas sueltas

- Discrepancia pectoral en marco de puerta: resuelta a favor del detalle (entra en N3; N4 solo lo refuerza/dosifica).
- Pendientes de §7 de la arquitectura (5 preguntas abiertas: calibración de mínimos, gamificación, estado mobility_blocked en la app, G7 straddle, estructura global).
- Olimpo (bloque élite ∞) se deja para el final.

## Bloque 19 — CrossFit Master v1: plataforma benchmarks embebidos + N1-N3 (oct 2026)

- Spec: `docs/superpowers/specs/2026-10-10-crossfit-master-design.md` · plan de fase 1: `docs/superpowers/plans/2026-10-10-crossfit-master-fase1-plataforma.md` (en `docs/superpowers/`, git-ignorado). Rama `feature/crossfit-master-fase1` (sin merge a main aún).
- **Plataforma back** (7 piezas, suite 536 tests/PHPStan 5/CS-Fixer verdes):
  - `BenchmarkExercise.repsPattern` (VARCHAR 50, nullable; ladders/pirámides/21-15-9 como string; el parser es cosa del front).
  - `blockType` nuevos: `fuerza_skill`, `wod` (los 5 bloques crossfit: preparacion → fuerza_skill → wod → postural → descarga).
  - `TrainingRound.benchmark` (FK nullable a Benchmark): un round PUEDE ser un WOD embebido; DTO valida "benchmarkId O exercises" (`Assert\Callback`); el mapper lanza `InvalidArgumentException` si no existe.
  - Expander: round con benchmark → UN paso `type: 'benchmark'` con payload expandido (timerConfig, timeCap, ejercicios con dosis y repsPattern, scalingOptions, rxWeights, metadatos de bloque). Ignora configs del round.
  - Tests: `TestItemType::Benchmark` (no gate) + `direction: 'lte'|'gte'` en `TestResult::evaluate/evaluateStrict` (lte = marcas de tiempo: menor es mejor). Retrocompatible (default gte).
  - Auto-evaluación: al cerrar un `UserBenchmarkAttempt` completado, `LevelProgressService::recordBenchmarkResult` alimenta el TestResult del nivel en curso si el benchmark es su test (lte→totalTimeSeconds, gte→scoreValue parseado; marca scaled CUENTA para el mínimo). Si no es test de ningún nivel activo: no-op.
  - `TrainingTimeCalculator` estima rounds benchmark: min 60% del timeCap (null→720 s), max timeCap (null→1200 s).
- **Programa generado** (`app:create-crossfit-master`, idempotente, réplica arquitectura v4): `crossfit-master` (strict_gates — ruling: las puertas CF son barreras vinculantes, spec §8; legacy las dejaría sin efecto —, totalLevels 16, sin calendario). N1-N3 en BD dev: 48 trainings, 240 rounds (5 bloques tipados), benchmarks embebidos Helen/Fran/Cindy, 7 puertas CF1-CF7, 24 logros, 0 omisiones. Duraciones N1 26-71 / N2 29-74 / N3 32-84 min (monótonas).
- **Contenido**: `docs/detalle_crossfit_niveles_01-03.md` (aprobado por el usuario tras 2 revisiones). N1 On-Ramp (test suave sin benchmarks), N2 Primer Impulso (Helen escalada lte 18:00/14:00 + puerta CF5), N3 Consolidación (Fran escalada lte 13:00/10:00 + CF1/CF4). Mínimos escalados contrastados con referencias beginner/intermediate de la BD.
- **Front**: imagen placeholder del programa (`public/programs/crossfit-master.png` GD + entrada en `programImages.js`), commit `28d9c8b` — **sustituir por arte final**. SIN cambios de player: el paso benchmark ya viaja en el payload de pasos pero el front aún no lo renderiza (lo renderiza como paso desconocido o lo ignora según el player actual — verificar antes de que un usuario lo use).
- **Pendiente de esta línea de trabajo**: N4-N16 (comando ya admite `--levels`), player front del paso benchmark (timer según timerConfig + selector RX/Scaled + registro simple/detallado — decisión UX aplazada por el usuario), Hyrox Mastery y otras competiciones (specs separados), arte final de la imagen.
- **Pendiente repo front (sin commit)**: thumbnails de imagen en la lista de programas del admin (`TrainingProgramsView.vue`, hecho en la sesión del 10 oct junto al inicio de esta fase).

## Bloque 20 — CrossFit Master v1: N4-N16 completos (oct 2026)

- Continuación del Bloque 19 (mismo spec/plan). Programa completo en BD dev: **16 niveles, 256 trainings (16×16), 210 logros, 7 puertas CF1-CF7**, `strict_gates`, 0 omisiones en todos los lotes. Suite 538 tests/PHPStan 5/CS-Fixer verdes.
- **Lotes** (cada uno: documento de detalle aprobado por revisión → transcripción a blueprint → generación → revisión de transcripción):
  - N4-N6 "RX escalado": Primera Tracción (CF2+CF3) / Motor y Gimnásticos / Umbral RX (Helen RX + CF6+CF7).
  - N7-N9 "RX": Consolidación RX (Fran RX 13:00/10:00) / Potencia (snatch progresiones, HSPU estrictos, Grace RX) / Motor Élite (Josh RX 28:30/23:30).
  - N10-N12 "Avanzado": Primera Muscle-Up (Elizabeth 14:00/11:00 — umbrales bajo timeCap tras fix) / Pesado (DT RX 22:00/18:00) / Heroes (Nate RX 5/8 rondas).
  - N13-N15 "Competición": Fuerza (Amanda RX 9:30/7:30 bajo cap 600) / Motor (Daniel RX + Gunny/Jerry hitos) / Pico (evento combinado tipo Games: C&J 100/65 → RMU ×12 → DT RX).
  - N16 "Élite ∞" (tipo Olimpo): `is_infinite=1`, test vacío, 16 sesiones rotativas (fuerza/benchmarks/eventos/gimnásticos), 50 logros de rangos Bronce→Diamante en 10 benchmarks (umbrales anclados a referencias M de la BD, todos bajo timeCap) + 12 récords.
- **Documentos fuente**: `docs/detalle_crossfit_niveles_{01-03,04-06,07-09,10-12,13-15,16_elite}.md` (todos aprobados por revisión de contenido; umbrales de test contrastados contra referencias y timeCaps de la BD — el precedente "mínimo por encima del timeCap" de la fase 1 quedó marcado como defectuoso y no se repite).
- **Regla de diseño aprendida (fix F-Elizabeth)**: ningún mínimo `lte` puede superar el `timeCapSeconds` del benchmark: a los `timeCap` el intent registra rondas, no tiempo.
- **Rulings de contenio de la fase**: Josh RX aceptado en N9 (defensible: mínimo = beginner propio, OHS RX en N8); "apoyo en extensión" mapeado a `Back-of-Hand Stretch` (equivalencia aceptada); eventos combinados tipo Games modelados como 1 WOD con nota (limitación de plataforma); desbloqueo automático de logros de rango por marca = trabajo futuro de plataforma (hoy manual).
- **Nota de entorno**: generar más de 3 niveles de golpe requiere `php -d memory_limit=1G` (OOM con 128M en CLI). Las tablas SINGULARES (`training_program`, `training_level`) son LEGACY vacías; las REALES son las PLURALES (`training_programs`, `training_levels`, `achievements` con columna `level_id`, `mobility_gate` singular).
- **Pendiente de esta línea**: desbloqueo automático de rangos, Hyrox Mastery y otras competiciones (specs separados).

## Bloque 21 — Player front del paso benchmark (17 oct 2026)

- Decisión UX del usuario: registro **detallado** (log por ejercicio/ronda) y timer con **todos los formatos** (countup for_time/chipper/ladder, countdown AMRAP, intervalos EMOM/Tabata/interval_stations; timerConfig desconocido degrada a countup, nunca rompe).
- **Back** (commit pendiente): filtro opcional `status` en `GET /benchmarks/attempts` (para retomar un WOD a mitad sin duplicar intents; status inválido → 400 `BadRequestHttpException`). Fix de integración: el payload del paso benchmark ahora incluye `benchmarkExerciseId` por ejercicio (el `log-exercise` exige el id de la fila `BenchmarkExercise`, no el del ejercicio de catálogo). Tests nuevos `BenchmarkAttemptsListTest` (3) + assertion en `TrainingSessionExpanderTest`.
- **Front** (`overworkout-front`, sin commit): `services/benchmarkAttempts.js`, `utils/repsPattern.js` (parser de patterns + matriz rondas×ejercicios), `composables/useWorkoutTimer.js` (timestamps sin deriva, segmentos materializados), `components/mobile/player/PlayerBenchmarkStep.vue` (fases setup/run/finish/summary; selector RX/Scaled/Beginner, retomar/descartar intent en progreso, board con ronda actual, diálogo de registro de ronda, DNF al abandonar sesión con WOD abierto). Integración: `TrainView` (rama `v-else-if` + fallback seguro), getters de bloques del store (`isBlockStep`), `PlayerSessionOverview` (WOD visible). 34 tests nuevos (520 totales en verde), lint y build OK.
- **Verificación e2e real** (servidor dev `php -S localhost:8000`): sesión N2 del CrossFit Master (Helen) → paso benchmark con `benchmarkExerciseId` → start RX → log ronda 1 → filtro `status=in_progress` devuelve 1 intent con su log → finish (700 s → auto-display "11:40") → filas correctas en `user_benchmark_attempt`/`user_benchmark_exercise_log` → PATCH del paso a `completed` (200). Auto-eval de test no aplica (usuario e2e sin progreso de nivel; cubierto por tests funcionales).
- Datos residuales de la verificación: usuario dev `e2e-bench@test.com` (id 23) con 1 log de sesión e intent Helen.

## Bloque 22 — Hyrox Mastery: spec + N1-N3 generados (17 oct 2026)

- **Spec aprobado**: `docs/superpowers/specs/2026-10-17-hyrox-mastery-design.md`. Decisiones del usuario: 8 niveles × 4 fases (~32 semanas, 128 trainings), pesos **Open** con variantes Pro en notas (N7-N8), simulacros como **benchmarks embebidos** (aprovechan el paso benchmark del Bloque 21), strict_gates con puertas HY1-HY4. Formato de carrera 26/27 verificado por web (8×1 km + 8 estaciones fijas; pesos Open/Pro oficiales).
- **Detalle de contenido aprobado**: `docs/detalle_hyrox_niveles_01-03.md` (709 líneas, plantilla CrossFit). N1 Base Híbrida (PFT como marca base) / N2 Primeras cargas (50% Open, puertas HY1-HY3) / N3 Media Hyrox (simulacro embebido + HY4). 0 ejercicios nuevos (catálogo cubre las 8 estaciones + running; duplicado conocido `Farmers Walk` 340 / `Farmer Walk` 280 — usar 340). Punto confirmado por el usuario: test N3 = simulacro + HY4 sin PFT re-test explícito.
- **Benchmarks sembrados** (`scripts/seeders/seed_hyrox_benchmarks.php`, idempotente): #71 HYROX PFT (for_time, cap 2400, 8 estaciones oficiales, referencias 09:00-26:00 M/F) y #72 Hyrox Simulacro L3 (for_time, cap 2700, 4×run 500m + estaciones 50% Open, referencias 24:00-42:00).
- **Comando** `app:create-hyrox-mastery` (`--delete-existing`, `--levels`): `CreateHyroxMasteryCommand` + `HyroxBlueprint` (48 sesiones transcritas) + `HyroxContent` (tips §1.7-Hyrox, tests, puertas, 22 logros `hyrox-nX-…`, skillFocus). Programa #44 `hyrox-mastery` (strict_gates, totalLevels 8, advanced, sin calendario): 3 niveles, 48 trainings, 240 rounds (5 blockType), sesión C-F3 de N3 con benchmark #72, 4 puertas, 22 logros. 0 omisiones, idempotente.
- **Duraciones calculadas**: N1 27-89 min, N2 31-100, N3 32-108; ordenación correcta (F3>F2>F1>F4). Nota: C-F3 de N3 calcula 46-66 min (la calculadora estima el round benchmark como 60% del cap; el cap de 45:00 acota el máximo) — comportamiento de plataforma, no hardcodeado.
- **Desviaciones aplanadas** (documentadas por el agente): dosis de distancia en sesiones normales como `durationSeconds` estimados (spec §3: "1 km ≈ nota + durationSeconds"; con distancia como reps la calculadora inflaba a 4 h); objetivos temporales de puertas dentro del campo `form` del ítem; EMOM 12' como 6 rondas de 2 min; icono `directions_run` a nivel de TrainingLevel (la entidad Programa no tiene icono).
- **Verificación**: suite 541/541, PHPStan 5 y CS-Fixer limpios. E2E real: sesión `7930` (N3 C-F3) → paso benchmark con 8 estaciones + `benchmarkExerciseId` → intent RX → log ronda 3 (sled push, distancia) → finish 1920 s → display "32:00" → filas correctas en BD.
- **Pendiente de esta línea**: lote N7-N8 (documento de detalle + fichas L7-L8), imagen placeholder del programa (`public/programs/hyrox-mastery.png` + `programImages.js`), commit de todo (back y front del Bloque 21).

## Bloque 23 — Hyrox Mastery: N4-N6 generados (17 oct 2026)

- **Detalle aprobado**: `docs/detalle_hyrox_niveles_04-06.md` (715 líneas). N4 Estaciones Open (fuerza pura Open + re-test HY4) / N5 Bloques dobles (Simulacro L5) / N6 Hyrox 3/4 (Simulacro L6 de 8 estaciones + re-tests HY1-HY3). Punto aprobado: los re-tests conservan la carga histórica de la puerta (menor que la de trabajo).
- **Benchmarks sembrados**: #73 Hyrox Simulacro L5 (for_time, cap 3600, 6×run 1 km + 6 estaciones 75% Open) y #74 Hyrox Simulacro L6 (for_time, cap 5400, 8×run 1 km + 8 estaciones ~65% Open, incluye goblet lunge 100 m y WB ×75).
- **Generación** (`--levels=4,5,6`, niveles 1-3 intactos): 6 niveles, 96 trainings, 480 rounds, 46 logros; benchmark_id solo en C-F3 de N3/N5/N6; testRequirements con re_tests (gateCode) y benchmarkId real; mínimos bajo cap. Duraciones: N4 33-117, N5 37-123, N6 37-129 min; C-F3 de N6 la más larga (max 116 min), F3 de N5/N6 con A/B/D en "mantener, no construir" (bajan a propósito).
- **Fix en seeder**: al re-sembrar, actualización de `BenchmarkExercise` por `position` sin borrar (los intents de prueba en dev tienen FK a `user_benchmark_exercise_log` y `clearExercises()` fallaba).
- **Verificación**: suite 541/541, PHPStan 5 y CS-Fixer limpios. E2E real: sesión `7978` (N6 C-F3) → paso benchmark con 16 filas en orden oficial, dosis y rxWeights correctos → intent RX → finish 1:10:00 → BD OK.
- **Pendiente**: commits (back: Bloques 21-24; front: Bloque 21 + imagen Hyrox).

## Bloque 24 — Hyrox Mastery: N7-N8 + programa completo (17 oct 2026)

- **Detalle aprobado**: `docs/detalle_hyrox_niveles_07-08.md` (525 líneas). N7 Hyrox Open (100% pesos Open, re-test HY4 a carga Open descrita en el ítem de test — la entidad de puerta conserva su criteria histórica 2×16 kg) / N8 Ritmo de competición (L8 con cap 1:30 + PFT de cierre lte 18:00/14:00; variantes Pro solo en notas).
- **Benchmarks sembrados**: #75 Hyrox Simulacro L7 (for_time, cap 6300, 16 filas 100% Open, refs 55:00→85:00) y #76 Hyrox Simulacro L8 (misma carrera, cap 5400, refs un escalón arriba 50:00→77:00). L7/L8 comparten dosis por decisión de diseño (completar vs. ritmo).
- **Programa COMPLETO en BD dev**: 8 niveles, 128 trainings, 640 rounds, 62 logros, 4 puertas HY1-HY4, 6 fichas de benchmark (PFT #71 + simulacros #72-#76). Benchmarks embebidos: C-F3 de N3/N5/N6/N7/N8 + D-F2 de N8 (PFT). restAfterBlocks 60 s (N1-N7) / 90 s (N8). Duraciones: N7 39-134 (C-F3 la más larga del programa, max 133), N8 39-143 (D-F2 supera a C-F3 por la regla calculadora max=cap del PFT — plataforma, no hardcodeado).
- **Front**: placeholder `public/programs/hyrox-mastery.png` (GD, estilo crossfit-master) + entrada en `programImages.js`. Build OK, 520 tests verdes. **Sustituir por arte final.**
- **Verificación**: suite 541/541, PHPStan 5, CS-Fixer limpios. E2E real: sesión `8010` (N8 C-F3) → paso benchmark L8 (16 ejercicios, cap 5400, rx 152/102 kg…) → intent RX → finish 4000 s → 200 OK.
- **Pendiente**: arte final de las imágenes, desbloqueo automático de logros de rango (limitación de plataforma), otras competiciones (specs separados).

## Bloque 25 — DEKA Mastery: programa completo (17 oct 2026)

- **Spec aprobado**: `docs/superpowers/specs/2026-10-17-deka-mastery-design.md`. Decisiones del usuario: los 3 formatos en progresión (Strong → Mile → Fit, cada uno benchmark de test), 8 niveles × 4 fases, mapeo RAM/dead ball con notas (0 fichas nuevas). Zonas oficiales verificadas (Rules of Competition v20260210 + deka.fit/zones): 10 zonas fijas con pesos M/F oficiales.
- **Detalle aprobado por lotes**: `docs/detalle_deka_niveles_{01-03,04-06,07-08}.md`. N1 Base DEKA / N2 Primeras cargas (DK1-DK3) / N3 Media DEKA + DK4 / N4 Zonas Open (re-test DK4) / N5 DEKA Strong / N6 DEKA Mile (re-tests DK1-DK3) / N7 Transición Fit (Fit a mínimo generoso + re-test DK4) / N8 DEKA Fit RX (cierre; Fit transición → Fit RX como arco de comparación, sin PFT en DEKA).
- **Benchmarks sembrados** (`scripts/seeders/seed_deka_benchmarks.php`, idempotente, helper `$zonesRx()` compartido): #77 DEKA Media (cap 1800) · #78 DEKA Strong (10 zonas RX sin runs, cap 1800) · #79 DEKA Mile (10×160 m, cap 3600) · #80 DEKA Fit (10×500 m, cap 5400). **Fit #80 compartido entre N7 y N8** (misma ficha, distintos mínimos/objetivos por nivel — verificado seguro: la auto-evaluación solo mira el nivel activo).
- **Programa COMPLETO en BD dev**: #45 `deka-mastery` (strict_gates, totalLevels 8): 8 niveles, 128 trainings, 640 rounds, 63 logros, 4 puertas DK1-DK4 (calendario: N2 DK1-DK3, N3 DK4, N4/N6/N7 re-tests), benchmarks embebidos en C-F3 de N3/N5/N6/N7/N8. restAfterBlocks 60 s (N1-N7) / 90 s (N8). Duraciones: N1 28-82 → N8 34-122; máximos del programa N6 A-F2 (123) y N8 C-F3 (122, la sesión C más larga — la regla 60 %-cap de la calculadora acota los benchmarks, comportamiento de plataforma documentado).
- **Mapeos con nota (0 fichas nuevas)**: RAM lunge → `Dumbbell Lunge`/`Kettlebell Goblet Lunge` pesada (nota back rack); RAM burpee → `Burpee` + carga en nota; dead ball → `Medicine Ball Slam` pesada; sled magnético → `Sled Push`/`Sled Pull` + nota Torque/Xebex; zona 9 como fila única `Sled Push` 50 m con el pull en descripción (1 ejercicio por fila en la plataforma); calorías del bike como reps.
- **Front**: placeholder `public/programs/deka-mastery.png` + `programImages.js`. Build OK, 520 tests verdes. **Sustituir por arte final.**
- **Verificación**: suite 541/541, PHPStan 5, CS-Fixer limpios. E2E real: sesión `8186` (N8 C-F3) → paso benchmark DEKA Fit (20 filas, cap 5400) → intent RX → finish 3500 s → 200 OK.
- **Pendiente**: commits del Bloque 25 (back y front), arte final, desbloqueo automático de rangos, otras competiciones.
