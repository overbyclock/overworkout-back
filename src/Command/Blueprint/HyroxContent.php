<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Hyrox Mastery v1 (niveles 1-3 de 8).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_hyrox_niveles_01-03.md y de la
 * regla §1.7-Hyrox (5 puntos) del mismo documento.
 *
 * Los nombres de los ítems de testRequirements se usan como requirementKey
 * de los logros desbloqueados por ítem (deben coincidir EXACTAMENTE).
 *
 * Los ítems de test de tipo benchmark llevan 'benchmarkName' (nombre en el
 * catálogo) en lugar de 'benchmarkId': el comando resuelve el id en ejecución.
 */
class HyroxContent
{
    public static function getLevelContent(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => self::level1(),
            2 => self::level2(),
            3 => self::level3(),
            4 => self::level4(),
            5 => self::level5(),
            6 => self::level6(),
            7 => self::level7(),
            8 => self::level8(),
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    /**
     * Logros del nivel (definición completa; el comando los crea/actualiza).
     */
    public static function getAchievements(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => self::achievementsL1(),
            2 => self::achievementsL2(),
            3 => self::achievementsL3(),
            4 => self::achievementsL4(),
            5 => self::achievementsL5(),
            6 => self::achievementsL6(),
            7 => self::achievementsL7(),
            8 => self::achievementsL8(),
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    /**
     * Nombre visible del bloque de sesión según su tipo (y la sesión).
     */
    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Run + Estaciones de fuerza',
                'session_b' => 'Ergómetros',
                'session_c' => 'Simulacro / Circuito',
                'session_d' => 'Aeróbico + Movilidad',
                default => 'Fuerza y skill',
            },
            TrainingRound::BLOCK_WOD => 'WOD / Circuito',
            TrainingRound::BLOCK_POSTURAL => 'Prehab postural',
            TrainingRound::BLOCK_DESCARGA => 'Descarga',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Descripción del bloque de sesión. El comando añade el objetivo/tip de la
     * sesión y las notas de escalado de los benchmarks embebidos.
     */
    public static function getBlockDescription(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, tobillo, rodilla, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s. Siembra permanente de las puertas HY1-HY4.',
            TrainingRound::BLOCK_FUERZA_SKILL => 'Trabajo principal de fuerza de estación y carrera de la sesión (2-4 RIR, nunca al fallo).',
            TrainingRound::BLOCK_WOD => 'WOD / circuito de la sesión con fatiga de transición (lógica de Roxzone). El escalado es decisión del usuario en el player y también es protección (regla §1.7-Hyrox §5).',
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado: tobillo/core, hombro, lumbar/core o trío). Calidad por encima de fatiga; descanso 45-60 s entre ejercicios; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática tumbado (90 s), Child's Pose (45 s) y estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía a 6-8 min con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (docs/detalle_hyrox_niveles_01-03.md, sección de cada sesión).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'Hoy la estrella es el run de 15-25 minutos: si terminas y aún podrías conversar, el ritmo era el correcto. La wall ball de hoy es un squat con una bola encima, no un press (§1.7-Hyrox §3).',
                'session_b' => 'En el SkiErg y el remo el orden de la remada manda: piernas-core-brazos a la ida, brazos-espalda-piernas a la vuelta. Un ergómetro mal remado es lumbar de regalo (§1.7-Hyrox §5).',
                'session_c' => 'Los pares de hoy imitan la carrera: una estación te quita el aire, la otra lo devuelve. Transiciones caminando y contadas: el Roxzone también se entrena (§1.7-Hyrox §4).',
                'session_d' => 'La sesión D parece fácil y es la que te deja correr todo el programa: 30 minutos Z2 hoy son el kilómetro 6 de tu futura Hyrox. No la saltes.',
            ],
            2 => [
                'session_a' => 'Los intervals de hoy son el ADN de la Hyrox: nada de sprintar el primer 400 y arrastrar el último. Ritmo parejo, técnica pareja (§1.7-Hyrox §4).',
                'session_b' => 'El SkiErg del PFT son 1.000 m: hoy acumulas ese volumen por intervalos. Si la remada se acorta y solo tiran los brazos, baja el ritmo — espalda de regalo no (§1.7-Hyrox §5).',
                'session_c' => 'El EMOM de F3 es tu ensayo de HY3: 20 burpees limpios en menos de 2:00 empiezan por burpees limpios en contexto. Aterrizaje silencioso, siempre (§1.7-Hyrox §2).',
                'session_d' => 'El kilómetro final de F3 se corre sin caminar y ya está: no hace falta rápido, hace falta continuo. HY1 se gana así, en frío y con sueño (§1.7-Hyrox §4).',
            ],
            3 => [
                'session_a' => 'El kilómetro Z3 de hoy es el run que repetirás 8 veces en la carrera: aprende su ritmo de memoria. Y si el farmers de F3 se siente en la espalda baja, revisa el paso — corto y erguido (§1.7-Hyrox §1/§5).',
                'session_b' => 'SkiErg y remo en umbral son el corazón silencioso de la Hyrox: no se ven en las fotos, pero deciden los últimos 2 km de carrera. Remada larga hasta el final (§1.7-Hyrox §3).',
                'session_c' => 'El simulacro de F3 es la media Hyrox: cada run se sale caminando 3 pasos y corriendo; cada transición se piensa antes de llegar. Terminar bajo 45:00 es el plan; terminar entero es la victoria (§1.7-Hyrox §4).',
                'session_d' => 'Los 100 m de farmers de F3 se caminan sin soltar o no se caminan: es la puerta HY4 entera. Si el agarre falla a los 70 m, la respuesta es más farmers en las próximas fases, no más ganas hoy (§1.7-Hyrox §1).',
            ],
            4 => [
                'session_a' => 'Hoy el trineo es gimnasio, no carrera: carga alta, descanso completo, técnica de cristal. El día que el sled Open salga limpio con 2 minutos de descanso, la carrera ya solo será cuestión de ritmo (§1.7-Hyrox §1).',
                'session_b' => 'El SkiErg de 2.000 m se rema con la cabeza, no con los brazos: divide en bloques mentales de 500 m y mantén la remada larga cuando ardan los hombros (§1.7-Hyrox §3).',
                'session_c' => 'Una estación a la vez, pero siempre con el run delante: la wall ball de 6 kg cansado es otra wall ball. Series de 10-15 planificadas desde el primer rep (§1.7-Hyrox §3).',
                'session_d' => 'Los 100 m de farmers de F3 se hacen con la carga de la puerta y sin soltar: es el re-test HY4 entero. Si el agarre tiembla a los 80 m, el plan es volver a F2, no forzar los últimos 20 (§1.7-Hyrox §5).',
            ],
            5 => [
                'session_a' => 'El par push→pull se gana en la transición: camina del empuje al tirón sin sentarte, sin mirar el móvil. Sesenta segundos de Roxzone los pone cualquiera; menos de treinta, solo los que entrenaron (§1.7-Hyrox §1/§4).',
                'session_b' => 'Row→Ski con fatiga es donde se rompen las remadas: la espalda baja se redondea y los hombros suben a las orejas. Si eso pasa, para la serie y vuelve con remada larga — el volumen de hoy es el del L5, no más (§1.7-Hyrox §5).',
                'session_c' => 'El L5 son 6 km de carrera con 6 estaciones: se gana o se pierde en los 30 segundos después de cada estación. Camina 3 pasos, respira, corre. Los que salen disparados del SkiErg pagan el run 6 (§1.7-Hyrox §4).',
                'session_d' => 'Los 200 m de farmers de F3 son la estación 6 del L5 en versión descansada: si salen sin soltar hoy, saldrán igual el día del simulacro. Lo que no sale descansado, no sale cansado (§1.7-Hyrox §1).',
            ],
            6 => [
                'session_a' => 'El tren enseña lo que la carrera cobra: la wall ball del km 7 llega con los hombros fundidos por el farmers y el lunge. Entrena las series de 10 planificadas hoy, y el día del simulacro no negociarás con la fatiga (§1.7-Hyrox §3).',
                'session_b' => '5.000 m de ergómetro a umbral son el SkiErg y el Row del simulacro juntos, sin correr por medio: si hoy salen parejos, el día del L6 los remarás con los ojos cerrados. Si la lumbar protesta, congela el gesto un ciclo (§1.7-Hyrox §5).',
                'session_c' => 'El L6 son 8 km y 8 estaciones: piensa en dos mitades. La primera se gana con cabeza (SkiErg y sleds sin heroísmos); la segunda con lo que entrenaste en los trenes (farmers, lunge y wall ball por bloques). Bajo 90:00 primero; el ritmo llega solo (§1.7-Hyrox §4).',
                'session_d' => 'Los re-tests de hoy se hacen con la misma humildad que en N2: 1 km sin caminar, 30 wall balls limpias, 20 burpees en 2 minutos. Cuatro niveles después, lo difícil no es pasarlos — es no menospreciarlos (§1.7-Hyrox §5).',
            ],
            7 => [
                'session_a' => 'Hoy cada estación tiene un reloj propio: el sled Open no se siente, se cronometra. Si el 50 m sale en 2:10 con técnica limpia, ese es tu split de carrera; si sale en 3:20, tu plan de carrera empieza por él (§1.7-Hyrox §1).',
                'session_b' => 'SkiErg y Row del Open no se sprintan: se ejecutan. El split de 4:30 se repite hoy cinco veces; el día del L7 saldrá una sexta con el corazón a 170 y ahí no habrá negociación posible (§1.7-Hyrox §3).',
                'session_c' => 'El L7 se corre con la cabeza primero: runs 1-4 conservadores, runs 5-8 de carrera. Cada transición es caminar 3 pasos, 3 respiraciones y arrancar. Los últimos 2 km se ganan en los primeros 6 (estrategia de ritmo negativo, §1.7-Hyrox §4).',
                'session_d' => 'Los 100 m de farmers de hoy son el re-test HY4 con su carga nueva: 2×24/2×16 kg, sin soltar, postura erguida. La puerta creció contigo — de 2×16 kg en N3 a los pesos de la carrera. Ciérrala con la misma técnica de siempre (§1.7-Hyrox §1).',
            ],
            8 => [
                'session_a' => 'En N8 no se entrena fuerza, se entrena carrera: cada serie de hoy tiene un split y cada split se anota. El atleta que llega al L8 con ocho splits escritos en la cabeza no corre la carrera, la ejecuta (estrategia, §1.7-Hyrox §1).',
                'session_b' => 'El SkiErg del L8 se gana por 10 segundos: 4:20 hoy, 4:10 el día de la carrera, y esos 10 segundos son los que separan el objetivo del mínimo. Remada larga, hombros lejos de las orejas (§1.7-Hyrox §3).',
                'session_c' => 'El L8 es la misma carrera del L7 con un solo cambio: el reloj. Cada split escrito es una decisión ya tomada; en la carrera no hay que decidir nada, solo ejecutar. Corre los primeros 4 km como si sobrara tiempo — porque sobra (estrategia de ritmo negativo).',
                'session_d' => 'El PFT de F2 es el mismo test que hiciste en N1, cuando el farmers pesaba la mitad y los wall balls iban a diana baja. La marca de hoy contra la de entonces es el balance de todo el programa: guárdala, te la vas a encontrar en el test de cierre.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Puertas de movilidad HY1-HY4 del programa (spec §4.2 y tabla del doc).
     * N1 no evalúa ninguna (se siembran en Bloque 0); N2 evalúa HY1-HY3; N3 evalúa HY4.
     * Se re-verifican como re_test en niveles 4/6/8 (lotes posteriores).
     */
    public static function getMobilityGates(): array
    {
        return [
            [
                'code' => 'HY1',
                'name' => 'Kilómetro continuo',
                'description' => 'Puerta de movilidad de tobillo, rodilla y talón de Aquiles (base del volumen de carrera). Se evalúa en N2; exigida a partir de N2 (intervals de run 400-800 m y carrera como trabajo principal). Siembra en N1.',
                'criteria' => 'Correr 1 km completo sin caminar ni parar, a ritmo conversacional, sin dolor matinal >2/10 en tobillo/rodilla al día siguiente',
            ],
            [
                'code' => 'HY2',
                'name' => 'Hombro de wall ball',
                'description' => 'Puerta de movilidad de hombro, muñeca y columna torácica. Se evalúa en N2; exigida a partir de N2 (wall balls 4 kg como dosis principal). Siembra en N1.',
                'criteria' => 'Wall Ball Shot con WB 4 kg ×30 sin romper series (descansos solo con la bola abajo, max 5 s), cada rep a diana baja válida',
            ],
            [
                'code' => 'HY3',
                'name' => 'Motor de burpee',
                'description' => 'Puerta de movilidad de hombro, muñeca y lumbar. Se evalúa en N2; exigida a partir de N2 (burpees en circuitos con fatiga). Siembra en N1.',
                'criteria' => 'Burpee ×20 completos (pecho al suelo, salto con extensión) en menos de 2:00, sin perder la línea lumbar',
            ],
            [
                'code' => 'HY4',
                'name' => 'Agarre de farmers',
                'description' => 'Puerta de movilidad de agarre, trapecio y cadena posterior. Se evalúa en N3; exigida a partir de N3 (farmers 2×16 kg como dosis de estación). Siembra en N1.',
                'criteria' => 'Farmers Walk con 2×16 kg ×100 m sin soltar las cargas, con postura erguida y pasos cortos',
            ],
        ];
    }

    // ========================================================================
    // LEVEL 1 — BASE HÍBRIDA
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): técnica de las 8 estaciones sin carga o con carga mínima (SkiErg/Row con intervalos cortos de 100-250 m, wall ball 4 kg a diana baja, sled sin carga si el gimnasio permite); run Z2 de 15 min. Aquí no se corre deprisa ni se carga nada: se aprende.',
                'Fase 2 (Progresión): run Z2 de 20 min; wall ball 4 kg a diana baja; sled sin carga técnico; primeras transiciones run→estación (100-150 m).',
                'Fase 3 (Intensificación): run Z2 de 25-30 min; primer 1 km continuo (ensayo HY1); circuitos de 2 estaciones con run 200 m.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; el PFT como marca base al final del nivel, descansado.',
                'Regla §1.7-Hyrox: el trineo se progresa por carga antes que por velocidad; el burpee broad jump se valida por la recepción, no por los metros; los runs en fatiga se entrenan primero cortos (100-200 m), luego medios (400-500 m), luego largos (1 km), nunca al revés.',
                'Si fallas el PFT: no pasa nada, es una marca de base — repite la fase Base, sube el volumen de Bloque 0 y vuelve a intentarlo. El test solo se da por superado con el tiempo registrado.',
                'Equipamiento: gimnasio con SkiErg, remo, sled (push/pull), wall balls, kettlebells/mancuernas y cinta o exterior. Sin SkiErg/remo: Assault Bike Sprint o Echo Bike Sprint por el mismo tiempo. Sin sled: Step Up cargado (push) o Kettlebell Swing Russian pesado (pull).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Técnica de estaciones sin carga; run Z2 de 15 min; intervalos de ergómetro muy cortos (100-250 m)', 'note' => 'Aquí no se falla: se aprende; movilidad densa', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Run Z2 de 20 min; wall ball 4 kg a diana baja; sled sin carga técnico; primeras transiciones run→estación (100-150 m)', 'note' => 'Primer contacto con la lógica de Roxzone', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Run Z2 de 25-30 min; primer 1 km continuo (ensayo HY1); circuitos de 2 estaciones con run 200 m', 'note' => 'La tercera fase valida el volumen del kilómetro continuo', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'PFT como marca base al final del nivel', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'HYROX PFT', 'minimum' => 1800, 'target' => 1440, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark HYROX PFT en modalidad Scaled del nivel (farmers 2×12/2×8 kg, WB 4 kg a diana baja, sled push sin carga o sustituido — ver scalingOptions de la ficha); marca registrada con scalingUsed = scaled. Mínimo 30:00 bajo el cap de 40:00; objetivo 24:00 entre beginner M y advanced M. En N1 el PFT es hito, no filtro: el test solo se da por superado con el tiempo registrado', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'HYROX PFT'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL1(): array
    {
        return [
            [
                'code' => 'hyrox-n1-primera-marca-pft',
                'name' => 'Primera marca PFT',
                'description' => 'Registra tu primera marca del HYROX PFT (mínimo 30:00) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HYROX PFT',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n1-pft-bajo-24',
                'name' => 'PFT bajo 24 minutos',
                'description' => 'Completa el HYROX PFT en menos de 24:00 (objetivo del test de Nivel 1).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'HYROX PFT', 'requirementValue' => 1440,
                'xpReward' => 150,
            ],
            [
                'code' => 'hyrox-n1-primer-kilometro',
                'name' => 'Primer kilómetro sin parar',
                'description' => 'Primer Sprint de 1 km completo sin caminar (siembra HY1).',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n1-primer-ski-erg',
                'name' => 'Primer SkiErg continuo',
                'description' => 'Primer SkiErg Sprint de 1.000 m completado sin parar la máquina.',
                'category' => 'skill', 'icon' => '🎿', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n1-primer-trineo',
                'name' => 'Primer trineo empujado',
                'description' => 'Primer Sled Push completado (aunque sea sin carga), técnica validada.',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n1-primer-wall-ball',
                'name' => 'Primer wall ball a diana',
                'description' => 'Primer Wall Ball Shot de 4 kg válido a diana baja desde sentadilla profunda.',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2 — PRIMERAS CARGAS
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): pesos 50% Open por estación con series largas y descanso completo; intervals 400 m; farmers 2×12 kg; sled 60/40 kg.',
                'Fase 2 (Progresión): intervals 600 m; sled 75/50 kg; lunge con KB 8-12 kg; primer circuito run→estación de 300 m.',
                'Fase 3 (Intensificación): intervals 800 m; dosis de test integradas (WB 4 kg ×25, Burpee ×15); ensayo de HY1 en sesión D (kilómetro final cronometrado).',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox: los intervals se corren a ritmo parejo (nada de sprintar el primero y arrastrar el último); la fatiga de hombros en wall balls se gestiona con series planificadas; el dolor matinal >2/10 en rodilla/lumbar/hombro = congelar el gesto un ciclo.',
                'Si fallas el test: revisa las puertas — HY1 (kilómetro sin caminar), HY2 (30 wall balls sin romper) e HY3 (20 burpees <2:00) son el requisito efectivo del N3. Fallar fuerza + puerta = ciclo corto de movilidad (mobility_blocked), sin repetir fuerza.',
                'Puertas evaluadas en este nivel: HY1, HY2 y HY3. Orden recomendado de ejecución del test (menor a mayor interferencia): HY1 → Wall Ball Shot → HY2 → HY3 → Burpee, con 2-3 min de descanso entre pruebas.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Pesos 50% Open por estación con series largas y descanso completo; intervals 400 m; farmers 2×12 kg; sled 60/40 kg', 'note' => 'Primera transición con carga real', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Intervals 600 m; sled 75/50 kg; lunge con KB 8-12 kg; primer circuito run→estación de 300 m', 'note' => 'El farmers tras correr es la estación más Roxzone que existe', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Intervals 800 m; dosis de test integradas (WB 4 kg ×25, Burpee ×15); ensayo de HY1 en sesión D', 'note' => 'Los 800 m de F3 preparan el kilómetro Z3 de N3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Wall Ball Shot (WB 4 kg)', 'minimum' => 25, 'target' => 40, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Cada rep desde sentadilla profunda a diana válida (§3); rep con técnica rota no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Burpee', 'minimum' => 15, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Pecho al suelo y salto con extensión (§2); rep con técnica rota no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'HY1 — Correr 1 km sin caminar', 'minimum' => 1, 'target' => 1, 'unit' => 'km', 'direction' => 'gte', 'form' => 'Completar el km sin caminar ni parar; objetivo de progreso: 1 km en <6:00', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'HY1'],
                        ['name' => 'HY2 — 30 wall balls 4 kg sin romper series', 'minimum' => 30, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => '30 reps sin romper series (descansos solo con la bola abajo, max 5 s), cada rep a diana baja válida; objetivo de progreso: 30 reps en <2:30', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'HY2'],
                        ['name' => 'HY3 — 20 burpees completos <2:00', 'minimum' => 20, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => '20 burpees completos (pecho al suelo, salto con extensión) en menos de 2:00, sin perder la línea lumbar; objetivo de progreso: 20 reps en <1:30', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'HY3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            [
                'code' => 'hyrox-n2-wall-ball-25',
                'name' => 'Wall ball de 25',
                'description' => 'Supera el mínimo de Wall Ball Shot con WB 4 kg (25 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🎯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Wall Ball Shot (WB 4 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n2-quince-burpees',
                'name' => 'Quince burpees de test',
                'description' => 'Supera el mínimo de Burpee (15 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Burpee',
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n2-puerta-hy1',
                'name' => 'Puerta HY1 superada',
                'description' => 'Supera la puerta de movilidad HY1 (Kilómetro continuo) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY1 — Correr 1 km sin caminar',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n2-puerta-hy2',
                'name' => 'Puerta HY2 superada',
                'description' => 'Supera la puerta de movilidad HY2 (Hombro de wall ball) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY2 — 30 wall balls 4 kg sin romper series',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n2-puerta-hy3',
                'name' => 'Puerta HY3 superada',
                'description' => 'Supera la puerta de movilidad HY3 (Motor de burpee) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY3 — 20 burpees completos <2:00',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n2-primer-farmers-carga',
                'name' => 'Primer farmers con carga',
                'description' => 'Primer Farmers Walk con 2×12/2×8 kg sin soltar.',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n2-primer-sled-cargado',
                'name' => 'Primer sled cargado',
                'description' => 'Primer Sled Push con carga ≥60/40 kg completado.',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n2-interval-800',
                'name' => 'Interval de 800 m completado',
                'description' => 'Primer intervalo de 800 m de la sesión A terminado al ritmo previsto.',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n2-primer-lunge-cargado',
                'name' => 'Primer lunge cargado',
                'description' => 'Primer Kettlebell Goblet Lunge con 8-12 kg válido.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3 — MEDIA HYROX
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): runs 1 km a Z3 con descanso; estaciones al 50% Open con series largas; farmers 2×16 kg introducido.',
                'Fase 2 (Progresión): pares de estaciones con fatiga (push→pull, WB→burpee); runs 500 m tras estación; ensayo de HY4.',
                'Fase 3 (Intensificación): benchmark embebido Hyrox Simulacro L3 en sesión C; runs 1 km Z3 consecutivos; nada de sled pesado en la sesión previa del simulacro (interferencia controlada).',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox: el par push→pull es el más exigente de la carrera — zancada corta y torso alto siempre; el simulacro se sale caminando 3 pasos de cada run; y si el farmers se siente en la lumbar, se revisa el paso antes de subir carga.',
                'Si fallas el test: el mínimo del simulacro (35:00) está bajo el cap de 45:00 y HY4 (farmers 2×16 kg ×100 m sin soltar) protege los niveles con farmers al 100% Open. Fallar benchmark + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Puerta evaluada en este nivel: HY4. Orden recomendado: HY4 primero (descansado) y simulacro después, o en sesiones separadas del envío de test.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Runs 1 km a Z3 con descanso; estaciones al 50% Open con series largas; farmers 2×16 kg introducido', 'note' => 'Primera transición larga run→estación', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Pares de estaciones con fatiga (push→pull, WB→burpee); runs 500 m tras estación; ensayo de HY4', 'note' => 'El par push→pull es el más exigente de la carrera', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: Hyrox Simulacro L3 en sesión C; runs 1 km Z3 consecutivos', 'note' => 'Nada de sled pesado en la sesión previa del simulacro', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hyrox Simulacro L3', 'minimum' => 2100, 'target' => 1680, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark Hyrox Simulacro L3 en modalidad del nivel (pesos 50% Open de la ficha): 4×(run 500 m + estación: SkiErg 500, Sled Push 25 m, Sled Pull 25 m, WB ×25); timeCap 45:00. Mínimo 35:00 (regla F-Elizabeth, bajo el cap y por encima de beginner M 38:00); objetivo 28:00 en torno a advanced M. La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Hyrox Simulacro L3'],
                        ['name' => 'HY4 — Farmers 2×16 kg ×100 m sin soltar', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => '100 m con 2×16 kg sin soltar, postura erguida y pasos cortos; objetivo de progreso: 100 m en <2:00', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'HY4'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4 — ESTACIONES OPEN
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): cada estación al 75-85% de Open con series largas y descanso completo; run 1 km Z3; ergómetros 1.500 m.',
                'Fase 2 (Progresión): estaciones al 90-100% de Open; wall ball 6 kg en dosis de test (×30); farmers 2×24/2×16 kg ×50-75 m.',
                'Fase 3 (Intensificación): todas las estaciones al 100% Open; ergómetros 2.000 m; ensayo de HY4 en sesión D.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox: la lógica del nivel es "una estación a la vez" — fuerza pura con series largas, 1-3 RIR y descanso completo; el trineo se progresa por carga, nunca por velocidad.',
                'Si fallas el test: revisa el re-test HY4 (farmers 2×16 kg ×100 m sin soltar — la carga histórica de la puerta, menor que la del ítem de fuerza) y la wall ball desde sentadilla profunda a diana oficial. Fallar fuerza + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Orden recomendado del test (menor a mayor interferencia): HY4 re-test (descansado) → Wall Ball Shot → Farmers Walk.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Cada estación al 75-85% de Open con series largas y descanso completo; run 1 km Z3; ergómetros 1.500 m', 'note' => 'Fuerza pura: la carga sube por fase, nunca la velocidad', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Estaciones al 90-100% de Open; wall ball 6 kg en dosis de test (×30); farmers 2×24/2×16 kg ×50-75 m', 'note' => 'El farmers tras correr llega en N5 — aquí el orden se invierte a propósito', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Todas las estaciones al 100% Open; ergómetros 2.000 m; ensayo de HY4 en sesión D', 'note' => '"Mantener, no construir" en el sled: el run sí sube', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Wall Ball Shot (WB 6 kg)', 'minimum' => 30, 'target' => 50, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Wall ball desde sentadilla profunda a diana oficial (§3); series planificadas, rep con técnica rota no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Farmers Walk (2×24/2×16 kg)', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => 'Pesos Open oficiales por primera vez; postura erguida y sin soltar; objetivo de progreso: 100 m en <2:30', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'HY4 (re-test) — Farmers 2×16 kg ×100 m sin soltar', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => 'Re-test de la puerta con su carga histórica de definición (2×16 kg), menor que la del ítem de fuerza (2×24/2×16 kg): verifica que el gesto sigue siendo seguro. Criterio: 100 m sin soltar, postura erguida y pasos cortos; objetivo de progreso: 100 m en <1:45', 'type' => TestItemType::ReTest->value, 'gateCode' => 'HY4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            [
                'code' => 'hyrox-n4-wall-ball-6kg',
                'name' => 'Wall ball de 6 kg',
                'description' => 'Supera el mínimo de Wall Ball Shot con WB 6 kg (30 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🎯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Wall Ball Shot (WB 6 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n4-cincuenta-wb-open',
                'name' => 'Cincuenta wall balls Open',
                'description' => 'Completa 50 reps de Wall Ball Shot con WB 6 kg (objetivo del test de Nivel 4).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Wall Ball Shot (WB 6 kg)', 'requirementValue' => 50,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n4-farmers-open',
                'name' => 'Farmers Open',
                'description' => 'Supera el mínimo de Farmers Walk con 2×24/2×16 kg (100 m) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Farmers Walk (2×24/2×16 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n4-puerta-hy4-revalidada',
                'name' => 'Puerta HY4 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad HY4 (Farmers 2×16 kg ×100 m sin soltar) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY4 (re-test) — Farmers 2×16 kg ×100 m sin soltar',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n4-primer-sled-open-push',
                'name' => 'Primer sled Open empujado',
                'description' => 'Primer Sled Push con 152/102 kg completado.',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n4-primer-sled-open-pull',
                'name' => 'Primer sled Open tirado',
                'description' => 'Primer Sled Pull con 103/78 kg completado.',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n4-lunge-20kg',
                'name' => 'Lunge de 20 kg',
                'description' => 'Primer Kettlebell Goblet Lunge con 20/12 kg válido (torso alto, rodilla alineada).',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n4-umbral-2000m',
                'name' => 'Umbral de 2.000 m',
                'description' => 'Primer SkiErg Sprint o Rowing Machine Sprint continuo de 2.000 m completado.',
                'category' => 'skill', 'icon' => '🎿', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5 — BLOQUES DOBLES
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): pares con fatiga moderada (push→pull ligero, farmers→lunges); transiciones sin cronómetro; run 1 km Z3-Z4.',
                'Fase 2 (Progresión): pares al 75% Open; transiciones Roxzone cronometradas y anotadas; wall ball 6 kg después de run.',
                'Fase 3 (Intensificación): benchmark embebido Hyrox Simulacro L5 en sesión C; sesiones A y D a "mantener, no construir".',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox: el par push→pull se gana en la transición (caminando, sin descanso extra); la hidratación y la estrategia de transición deciden un formato de 6 km con 6 estaciones.',
                'Si fallas el test: el mínimo (50:00) está bajo el cap de 60:00 y en torno a intermediate M (52:00). Repite la fase Base con las transiciones cronometradas: los 30 s después de cada estación son el propio entrenamiento.',
                'Es el primer benchmark del programa con 6 km de carrera acumulados: hidrátate antes de la sesión C de Fase 3 y planifica los Roxzone desde la primera estación.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Pares con fatiga moderada (push→pull ligero, farmers→lunges); transiciones sin cronómetro; run 1 km Z3-Z4', 'note' => 'Primer par completo saliendo de correr', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Pares al 75% Open; transiciones Roxzone cronometradas; wall ball 6 kg después de run', 'note' => 'Anota cada transición: los datos mandan sobre las sensaciones', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: Hyrox Simulacro L5 en sesión C (6× run 1 km + estación)', 'note' => 'Sesiones A y D a "mantener, no construir"', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hyrox Simulacro L5', 'minimum' => 3000, 'target' => 2700, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark Hyrox Simulacro L5 en modalidad RX de la ficha (75% Open): 6×(run 1 km + estación: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 60 m, Row 1 km, Farmers 200 m); timeCap 60:00. Mínimo 50:00 (regla F-Elizabeth, bajo el cap y en torno a intermediate M 52:00); objetivo 45:00 entre advanced F (51:00) y advanced M (46:00). La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Hyrox Simulacro L5'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            [
                'code' => 'hyrox-n5-simulacro-l5-completado',
                'name' => 'Simulacro L5 completado',
                'description' => 'Completa el Hyrox Simulacro L5 dentro del cap de 60:00 (mínimo 50:00) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Hyrox Simulacro L5',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n5-l5-bajo-45',
                'name' => 'L5 bajo 45',
                'description' => 'Completa el Hyrox Simulacro L5 en menos de 45:00 (objetivo del test de Nivel 5).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Hyrox Simulacro L5', 'requirementValue' => 2700,
                'xpReward' => 150,
            ],
            [
                'code' => 'hyrox-n5-seis-kilometros',
                'name' => 'Seis kilómetros con estaciones',
                'description' => 'Termina el Hyrox Simulacro L5 en menos de 60:00: el formato completo de 6 km y 6 estaciones dentro del cap.',
                'category' => 'progress', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Hyrox Simulacro L5', 'requirementValue' => 3600,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n5-par-push-pull-fatiga',
                'name' => 'Par push-pull con fatiga',
                'description' => 'Primera ronda de Sled Push → Sled Pull del circuito sin romper técnica (§1).',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n5-transicion-bajo-30',
                'name' => 'Transición bajo 30 segundos',
                'description' => 'Primera transición estación→run cronometrada en menos de 30 s manteniendo el ritmo del run.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n5-kilometro-z4',
                'name' => 'Kilómetro a Z4',
                'description' => 'Primer Sprint de 1 km a ritmo Z4 completado dentro del ritmo previsto.',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n5-farmers-despues-de-correr',
                'name' => 'Farmers después de correr',
                'description' => 'Primer Farmers Walk de 200 m con 2×18/2×12 kg completado tras run de 1 km en circuito/sesión.',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6 — HYROX 3/4
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): trenes de 3 estaciones con fatiga moderada; run 1 km Z3-Z4; umbrales ergómetro 4.000 m.',
                'Fase 2 (Progresión): trenes al ~65% Open; run 1 km ×3 Z4; umbrales 5.000 m; wall ball 6 kg ×50-60.',
                'Fase 3 (Intensificación): benchmark embebido Hyrox Simulacro L6 en sesión C; sesiones A, B y D a "mantener, no construir"; re-tests HY1-HY3 en D.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox: 8 estaciones son 8 patrones de fatiga distintos — el Bloque 0 es aquí especialmente estratégico y el prehab no se omite ni se recorta en ninguna sesión (solo el Bloque 0 se acorta en F3).',
                'Si fallas el test: el mínimo del simulacro (75:00) está bajo el cap de 90:00 y los re-tests HY1-HY3 verifican que la base de movilidad sigue intacta tras 4 niveles de trabajo cargado. Fallar benchmark + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Orden recomendado del test: re-tests primero (descansado, en sesión separada si hace falta) y el simulacro al final, con al menos una sesión de movilidad entre medias.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Trenes de 3 estaciones con fatiga moderada; run 1 km Z3-Z4; umbrales ergómetro 4.000 m', 'note' => 'El Bloque 0 es estratégico: 8 patrones de fatiga distintos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Trenes al ~65% Open; run 1 km ×3 Z4; umbrales 5.000 m; wall ball 6 kg ×50-60', 'note' => 'La sesión B acumula 5.000 m entre SkiErg y Row', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: Hyrox Simulacro L6 en sesión C (8× run 1 km + estación)', 'note' => 'Sesiones A/B/D a "mantener, no construir"; re-tests HY1-HY3 en D', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hyrox Simulacro L6', 'minimum' => 4500, 'target' => 3600, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark Hyrox Simulacro L6 en modalidad RX de la ficha (~65% Open): 8×(run 1 km + estación en orden oficial: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 80 m, Row 1 km, Farmers 200 m, Goblet Lunge 100 m, WB ×75); timeCap 90:00. Mínimo 75:00 (regla F-Elizabeth, bajo el cap, en torno a intermediate M 70:00-beginner M 78:00); objetivo 60:00 cerca de elite F (60:00). La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Hyrox Simulacro L6'],
                        ['name' => 'HY1 (re-test) — Correr 1 km sin caminar', 'minimum' => 1, 'target' => 1, 'unit' => 'km', 'direction' => 'gte', 'form' => 'Re-test con el criterio histórico de la puerta: 1 km completo sin caminar ni parar; objetivo de progreso: 1 km en <5:30', 'type' => TestItemType::ReTest->value, 'gateCode' => 'HY1'],
                        ['name' => 'HY2 (re-test) — 30 wall balls 4 kg sin romper series', 'minimum' => 30, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Re-test con la carga histórica de la puerta (WB 4 kg, menor que los 6 kg de trabajo): 30 reps sin romper series (descansos solo con la bola abajo, max 5 s), cada rep a diana baja válida; objetivo de progreso: 30 reps en <2:00', 'type' => TestItemType::ReTest->value, 'gateCode' => 'HY2'],
                        ['name' => 'HY3 (re-test) — 20 burpees completos <2:00', 'minimum' => 20, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Re-test con el criterio histórico de la puerta: 20 burpees completos (pecho al suelo, salto con extensión) en menos de 2:00, sin perder la línea lumbar; objetivo de progreso: 20 reps en <1:30', 'type' => TestItemType::ReTest->value, 'gateCode' => 'HY3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            [
                'code' => 'hyrox-n6-simulacro-l6-completado',
                'name' => 'Simulacro L6 completado',
                'description' => 'Completa el Hyrox Simulacro L6 dentro del cap de 90:00 (mínimo 75:00) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Hyrox Simulacro L6',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n6-hyrox-34-bajo-60',
                'name' => 'Hyrox 3/4 bajo 60',
                'description' => 'Completa el Hyrox Simulacro L6 en menos de 60:00 (objetivo del test de Nivel 6).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Hyrox Simulacro L6', 'requirementValue' => 3600,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n6-puerta-hy1-revalidada',
                'name' => 'Puerta HY1 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad HY1 (Kilómetro continuo) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY1 (re-test) — Correr 1 km sin caminar',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n6-puerta-hy2-revalidada',
                'name' => 'Puerta HY2 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad HY2 (Hombro de wall ball) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY2 (re-test) — 30 wall balls 4 kg sin romper series',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n6-puerta-hy3-revalidada',
                'name' => 'Puerta HY3 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad HY3 (Motor de burpee) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY3 (re-test) — 20 burpees completos <2:00',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n6-ocho-estaciones',
                'name' => 'Ocho estaciones, ocho kilómetros',
                'description' => 'Primera ejecución completa del Hyrox Simulacro L6 en sesión C (F3), dentro del cap de 90:00.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n6-tren-de-tres',
                'name' => 'Tren de tres completado',
                'description' => 'Primer tren de 3 estaciones (SkiErg→Sled Push→Sled Pull o Row→Farmers→Lunge) del circuito de sesión A sin romper técnica.',
                'category' => 'skill', 'icon' => '🚂', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n6-wb-setentaicinco',
                'name' => 'Wall ball setentaicinco',
                'description' => 'Primer Wall Ball Shot de 75 reps acumuladas en una sesión (6/4 kg).',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n6-umbral-5000m',
                'name' => 'Umbral de 5.000 m',
                'description' => 'Primera sesión B con 5.000 m acumulados entre SkiErg Sprint y Rowing Machine Sprint completados al ritmo previsto.',
                'category' => 'skill', 'icon' => '🎿', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            [
                'code' => 'hyrox-n3-simulacro-l3-completado',
                'name' => 'Simulacro L3 completado',
                'description' => 'Completa el Hyrox Simulacro L3 dentro del cap de 45:00 (mínimo 35:00) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Hyrox Simulacro L3',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n3-media-hyrox-bajo-28',
                'name' => 'Media Hyrox bajo 28',
                'description' => 'Completa el Hyrox Simulacro L3 en menos de 28:00 (objetivo del test de Nivel 3).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Hyrox Simulacro L3', 'requirementValue' => 1680,
                'xpReward' => 150,
            ],
            [
                'code' => 'hyrox-n3-puerta-hy4',
                'name' => 'Puerta HY4 superada',
                'description' => 'Supera la puerta de movilidad HY4 (Agarre de farmers) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY4 — Farmers 2×16 kg ×100 m sin soltar',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n3-primer-simulacro',
                'name' => 'Primer simulacro completado',
                'description' => 'Primera ejecución completa del Hyrox Simulacro L3 en sesión C (F3), dentro del cap de 45:00.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n3-kilometro-z3',
                'name' => 'Kilómetro a Z3',
                'description' => 'Primer Sprint de 1 km a ritmo Z3 completado dentro del ritmo previsto.',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n3-farmers-2x16',
                'name' => 'Farmers de 2×16 kg',
                'description' => 'Primer Farmers Walk/Farmer Walk con 2×16/2×12 kg sin soltar.',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'hyrox-n3-par-push-pull',
                'name' => 'Par push-pull completado',
                'description' => 'Primera ronda de Sled Push → Sled Pull con fatiga del circuito de sesión C sin romper técnica (§1).',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7 — HYROX OPEN
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): splits por estación introducidos (runs 1 km a ritmo objetivo + estación con split); pesos Open con volumen moderado; wall balls ×100 en series de 10.',
                'Fase 2 (Progresión): circuitos de ritmo: 4-6 estaciones seguidas con splits; runs 1 km a Z4 con ritmo negativo; ensayo HY4 a carga Open en D.',
                'Fase 3 (Intensificación): benchmark embebido Hyrox Simulacro L7 en sesión C; sesiones A/B/D a "afilar, no construir": volumen bajo, ritmo exacto, nada de carga nueva.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox + estrategia de carrera: ritmo negativo (la segunda mitad más rápida que la primera), Roxzone como transición activa (3-5 pasos + 3 respiraciones, nunca sprint de recuperación), series planificadas en wall balls (10-15 desde el rep 1) y burpee broad jumps (bloques de 10 m), y calor e hidratación en simulacros largos (agua en cada transición).',
                'Si fallas el test: el mínimo (1:30:00) está bajo el cap de 1:45:00. Revisa los splits escritos de cada estación: el plan de carrera se entrena, no se improvisa. Fallar benchmark + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Orden recomendado: HY4 descansado primero y el simulacro después (o en sesiones separadas del envío de test). El L7 es el esfuerzo más largo del programa: hidratación y plan de splits antes de empezar.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Splits por estación introducidos (runs 1 km a ritmo objetivo + estación con split); pesos Open con volumen moderado; wall balls ×100 en series de 10', 'note' => 'Se aprende el ritmo objetivo de cada estación', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Circuitos de ritmo: 4-6 estaciones seguidas con splits; runs 1 km a Z4 con ritmo negativo; ensayo HY4 a carga Open en D', 'note' => 'Cada transición se cronometra y se anota', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: Hyrox Simulacro L7 en sesión C (8× run 1 km + estación, 100% Open)', 'note' => 'Sesiones A/B/D a "afilar, no construir"', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hyrox Simulacro L7', 'minimum' => 5400, 'target' => 4500, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark Hyrox Simulacro L7 en modalidad RX (100% Open): 8×(run 1 km + estación en orden oficial: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 80 m, Row 1 km, Farmers 200 m, Goblet Lunge 100 m, WB ×100); timeCap 1:45:00. Mínimo 1:30:00 (regla F-Elizabeth, bajo el cap, en torno a beginner M 78:00 + margen de primer Open); objetivo 1:15:00 entre advanced M (62:00) e intermediate M (70:00) con margen de Roxzone real. La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Hyrox Simulacro L7'],
                        ['name' => 'HY4 (re-test) — Farmers 2×24/2×16 kg ×100 m sin soltar', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => 'Re-test de la puerta HY4 con re-validación de CARGA OPEN: farmers 2×24/2×16 kg ×100 m sin soltar (la puerta madura de su carga histórica 2×16 kg a la carga de la estación 6 de la carrera; la entidad de puerta conserva su criteria de definición — la re-validación a carga Open vive en este ítem de test). Criterio: 100 m sin soltar, postura erguida y pasos cortos; objetivo de progreso: 100 m en <2:00 (si compites Pro, objetivo de nota: 2×32/2×24 kg)', 'type' => TestItemType::ReTest->value, 'gateCode' => 'HY4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            [
                'code' => 'hyrox-n7-primera-open',
                'name' => 'Primera Hyrox Open completa',
                'description' => 'Completa el Hyrox Simulacro L7 dentro del cap de 1:45:00 (mínimo 1:30:00) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Hyrox Simulacro L7',
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n7-open-bajo-115',
                'name' => 'Open bajo 1:15',
                'description' => 'Completa el Hyrox Simulacro L7 en menos de 1:15:00 (objetivo del test de Nivel 7).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Hyrox Simulacro L7', 'requirementValue' => 4500,
                'xpReward' => 300,
            ],
            [
                'code' => 'hyrox-n7-puerta-hy4-carga-open',
                'name' => 'Puerta HY4 a carga Open',
                'description' => 'Supera el re-test de la puerta HY4 a carga Open (Farmers 2×24/2×16 kg ×100 m sin soltar) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HY4 (re-test) — Farmers 2×24/2×16 kg ×100 m sin soltar',
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n7-cien-wall-balls',
                'name' => 'Cien wall balls Open',
                'description' => 'Primer acumulado de 100 Wall Ball Shot a 6/4 kg en una sola sesión (series planificadas, §3).',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n7-split-sled',
                'name' => 'Split de carrera en el sled',
                'description' => 'Primer Sled Push de 50 m a 152/102 kg bajo el split objetivo (2:30 M / 3:00 F) con técnica válida.',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n7-roxzone-20s',
                'name' => 'Roxzone de 20 segundos',
                'description' => 'Primera transición estación→run del circuito de ritmo en menos de 20 s sin elevar el ritmo cardíaco por encima de lo sostenible.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n7-farmers-open-100m',
                'name' => 'Farmers Open de cien metros',
                'description' => 'Primer Farmers Walk de 100 m con 2×24/2×16 kg sin soltar (ensayo del re-test).',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8 — RITMO DE COMPETICIÓN
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primera repetición del simulacro L7 a ritmo (circuitos de 8 estaciones con splits); PFT en RX como toma de contacto; estrategia de carrera en cada WOD.',
                'Fase 2 (Progresión): circuitos de ritmo con transiciones a 20 s; runs 1 km a ritmo objetivo con ritmo negativo; wall balls ×100 sub-6:00 en circuito.',
                'Fase 3 (Intensificación): benchmark embebido Hyrox Simulacro L8 en sesión C; sesiones A/B/D a "afilar, no construir"; PFT de cierre en D.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-Hyrox + estrategia: en N8 cada serie tiene un split y cada split se anota; ritmo negativo (primeros 4 km conservadores), Roxzone de 15-20 s (3-5 pasos + 3 respiraciones), wall balls en 4×25 planificadas y BBJ en bloques de 10 m con aterrizaje válido; hidratación en cada transición.',
                'Si fallas el test: el objetivo (1:00:00) exige runs a ~4:15 y Roxzone bajo control; el mínimo (1:15:00) está bajo el cap de 1:30:00. Revisa los splits escritos: la carrera no se corre, se ejecuta.',
                'Orden recomendado: PFT primero (descansado, 20-30 min de esfuerzo) y el L8 en sesión separada del envío de test, con al menos una sesión de movilidad entre medias. Los dos en el mismo día solo para atletas con experiencia de competición. Superar ambos benchmarks cierra el programa (logro "Hyrox Mastery completada").',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primera repetición del simulacro a ritmo (circuitos de 8 estaciones con splits); PFT en RX como toma de contacto; estrategia de carrera en cada WOD', 'note' => 'restAfterBlock 90 s: sesiones densas en calidad', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Circuitos de ritmo con transiciones a 20 s; runs 1 km a ritmo objetivo con ritmo negativo; wall balls ×100 sub-6:00 en circuito', 'note' => 'Cada split escrito es una decisión ya tomada', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: Hyrox Simulacro L8 en sesión C (8× run 1 km + estación, 100% Open, a ritmo)', 'note' => 'Sesiones A/B/D a "afilar, no construir"; PFT de cierre en D', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final — cierre del programa', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hyrox Simulacro L8', 'minimum' => 4500, 'target' => 3600, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark Hyrox Simulacro L8 en modalidad RX (mismas dosis Open que L7): 8×(run 1 km + estación en orden oficial); timeCap 1:30:00. Mínimo 1:15:00 (regla F-Elizabeth, bajo el cap); objetivo 1:00:00 entre elite F (55:00) y advanced M (56:00) — exige runs a ~4:15 y Roxzone bajo control. La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Hyrox Simulacro L8'],
                        ['name' => 'HYROX PFT', 'minimum' => 1080, 'target' => 840, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark HYROX PFT (ficha del lote 1) en modalidad RX si el atleta la sostiene (pesos Open de la ficha) o scaled siempre disponible. Es el cierre del programa: la marca se compara con la base registrada en N1 (base típica ~24:00-30:00 scaled); objetivo 14:00 en torno a advanced M. La comparación es narrativa (logro), no automática', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'HYROX PFT'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            [
                'code' => 'hyrox-n8-ritmo-competicion',
                'name' => 'Ritmo de competición',
                'description' => 'Completa el Hyrox Simulacro L8 dentro del cap de 1:30:00 (mínimo 1:15:00) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Hyrox Simulacro L8',
                'xpReward' => 300,
            ],
            [
                'code' => 'hyrox-n8-hyrox-bajo-la-hora',
                'name' => 'Hyrox bajo la hora',
                'description' => 'Completa el Hyrox Simulacro L8 en menos de 1:00:00 (objetivo del test de Nivel 8).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Hyrox Simulacro L8', 'requirementValue' => 3600,
                'xpReward' => 300,
            ],
            [
                'code' => 'hyrox-n8-pft-cierre',
                'name' => 'PFT de cierre',
                'description' => 'Registra la marca del HYROX PFT de cierre (mínimo 18:00) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HYROX PFT',
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n8-pft-sub-14',
                'name' => 'PFT sub-14',
                'description' => 'Completa el HYROX PFT en menos de 14:00 (objetivo del test de Nivel 8).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'HYROX PFT', 'requirementValue' => 840,
                'xpReward' => 300,
            ],
            [
                'code' => 'hyrox-n8-hyrox-mastery-completada',
                'name' => 'Hyrox Mastery completada',
                'description' => 'Supera el test de Nivel 8 (Hyrox Simulacro L8 + HYROX PFT de cierre): cierre del programa Hyrox Mastery.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 500,
            ],
            [
                'code' => 'hyrox-n8-repeticion-carrera',
                'name' => 'Repetición de carrera',
                'description' => 'Segunda ejecución completa del simulacro L7/L8 dentro del cap en una misma fase (circuitos F1/F2/F4 de la sesión C).',
                'category' => 'skill', 'icon' => '🔁', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n8-wall-balls-sub-5',
                'name' => 'Wall balls sub-5',
                'description' => 'Wall Ball Shot de 100 reps (6/4 kg) en menos de 5:00 dentro de un simulacro o circuito.',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'hyrox-n8-splits-carrera',
                'name' => 'Splits de carrera',
                'description' => 'Simulacro o circuito completo con los 8 splits de estación bajo su objetivo (sled push <2:30, pull <3:30, BBJ <5:00, farmers <3:00, lunge <4:00).',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'hyrox-n8-preparado-pro',
                'name' => 'Preparado para Pro',
                'description' => 'Primera sesión A ejecutada con variantes Pro (sled 202/152, pull 153/103, farmers 2×32/2×24, WB 9/6 kg) completa. Objetivo opcional.',
                'category' => 'skill', 'icon' => '💎', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 300,
            ],
        ];
    }
}
