<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa DEKA Mastery v1 (niveles 1-3 de 8).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_deka_niveles_01-03.md y de la
 * regla §1.7-DEKA (5 puntos) del mismo documento.
 *
 * Los nombres de los ítems de testRequirements se usan como requirementKey
 * de los logros desbloqueados por ítem (deben coincidir EXACTAMENTE).
 *
 * Los ítems de test de tipo benchmark llevan 'benchmarkName' (nombre en el
 * catálogo) en lugar de 'benchmarkId': el comando resuelve el id en ejecución.
 */
class DekaContent
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
                'session_a' => 'Run + Zonas de fuerza',
                'session_b' => 'Ergómetros',
                'session_c' => 'Simulacro / Circuito de zonas',
                'session_d' => 'Aeróbico + Movilidad',
                default => 'Fuerza y skill',
            },
            TrainingRound::BLOCK_WOD => 'WOD / Circuito de zonas',
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
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, tobillo, rodilla, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s. Siembra permanente de las puertas DK1-DK4.',
            TrainingRound::BLOCK_FUERZA_SKILL => 'Trabajo principal de fuerza de zonas y carrera corta de la sesión (2-4 RIR, nunca al fallo).',
            TrainingRound::BLOCK_WOD => 'WOD / circuito de zonas de la sesión con fatiga de transición (lógica DEKA: zona→run→zona). El escalado es decisión del usuario en el player y también es protección (regla §1.7-DEKA §5).',
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado: lumbar/core, hombro, tobillo/core o trío). Calidad por encima de fatiga; descanso 45-60 s entre ejercicios; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática tumbado (90 s), Child's Pose (45 s) y estiramiento suave de la zona trabajada (30-45 s por zona). En Fase 4 se amplía a 6-8 min con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (docs/detalle_deka_niveles_01-03.md, sección de cada sesión).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'Hoy aprendes el idioma de las 10 zonas: zancada con rodilla al suelo, caja sin tropiezo, farmers con agarre firme. La DEKA se gana en la técnica antes que en el motor (§1.7-DEKA §1/§2).',
                'session_b' => 'Los tres ergómetros de la DEKA tienen una cosa en común: se reman/pedalean con el tronco quieto. Un ergómetro mal hecho es lumbar de regalo; la zona se ejecuta, no se sobrevive (§1.7-DEKA §5).',
                'session_c' => 'Cada ronda de hoy es un mini-recorrido DEKA: las zonas se encadenan sin descanso entre ellas, solo el que planificas entre rondas. Así se corre la DEKA, en pequeño (§1.7-DEKA §2).',
                'session_d' => 'La sesión D parece fácil y es la que te deja hacer todo lo demás: 15-18 minutos Z2 hoy son los 160 m de cada zona corriendo en vez de arrastrándose. No la saltes.',
            ],
            2 => [
                'session_a' => 'La zancada con mancuernas no es una caminata con peso: la rodilla de atrás besa el suelo y el torso se mantiene alto. Si la rodilla no baja, la rep no cuenta (§1.7-DEKA §1).',
                'session_b' => 'Las 25 cal del bike no se negocian con el reloj: se pedalean sentado, con el tronco quieto y sin parar. El día que salgan continuas en contexto, DK3 es tuya (§1.7-DEKA §5).',
                'session_c' => 'La caja se salta o se sube, pero nunca se tropieza: si hoy la dominas en step over rítmico, mañana saltas; si hoy tropiezas, vuelves al step (§1.7-DEKA §2).',
                'session_d' => 'Los 100 m de farmers de F3 son la antesala de DK4: si el agarre aguanta sin que la postura se caiga, el 2×16 kg de N3 será solo un paso más (§1.7-DEKA §3).',
            ],
            3 => [
                'session_a' => 'Los 160 m del ritmo Mile no se corren, se administran: bajo 45 segundos con la respiración controlada, y la zona que viene después se ejecuta, no se sobrevive. Ese es todo el secreto (§1.7-DEKA §1).',
                'session_b' => 'Los tres ergómetros de la DEKA Media son 250 m de remo dentro del simulacro, pero aquí se entrenan al doble: lo que corre cómodo en el entrenamiento, sale solo en la carrera (§1.7-DEKA §5).',
                'session_c' => 'La DEKA Media son cinco estaciones cortas que se encadenan: la recuperación es el run de 160 m, y el run se corre con la respiración de la zona anterior. Terminar bajo 20 minutos es el plan; terminar entero es la victoria (§1.7-DEKA §2/§3).',
                'session_d' => 'Los 100 m de farmers de F3 son la puerta DK4 entera: 2×16 kg, sin soltar, pasos cortos. Si el agarre falla a los 80 m, la respuesta es más farmers en las próximas fases, no más ganas hoy (§1.7-DEKA §3).',
            ],
            4 => [
                'session_a' => 'Hoy las zonas son gimnasio, no carrera: carga oficial, descanso completo, técnica de cristal. El día que la zancada de 2×16 kg salga limpia con dos minutos de descanso, la zona 1 de la Strong ya solo será cuestión de ritmo (§1.7-DEKA §1).',
                'session_b' => 'Los 50 cal del bike de F3 son dos zonas 7 seguidas: si salen continuas y sentado, la zona 7 de la Strong será un trámite. El tronco quieto, siempre (§1.7-DEKA §5).',
                'session_c' => 'Una zona a la vez, pero siempre con el run delante: la caja de 24 pulgadas cansado es otra caja. Si el salto no va hoy, el step over rítmico es igual de válido y más listo (§1.7-DEKA §2).',
                'session_d' => 'Los 100 m de farmers de F3 se hacen con 2×16 kg y sin soltar: es el re-test DK4 entero. La carga de la puerta no cambió desde N3 — lo que cambiaste tú (§1.7-DEKA §3).',
            ],
            5 => [
                'session_a' => 'La Strong se gana en las transiciones: salir de la zancada, coger las mancuernas y caminar sin pausa. Sesenta segundos de transición los pone cualquiera; menos de treinta, solo los que entrenaron (§1.7-DEKA §3).',
                'session_b' => 'Row→Ski con fatiga es donde se rompen las remadas: espalda redondeada y solo brazos. Si eso pasa, para la serie y vuelve con remada larga — el volumen de hoy es el de la Strong, no más (§1.7-DEKA §5).',
                'session_c' => 'La Strong son 10 zonas que se encadenan: la recuperación es caminar al siguiente implemento, y se camina rápido. Los primeros 5 minutos se corren con la cabeza: si las zonas 1-4 salen limpias, las 6-10 solo son ejecutar (§1.7-DEKA §1-§4).',
                'session_d' => 'Los ida y vuelta de farmers de F2 son la zona 6 descansada: si salen sin soltar hoy, saldrán igual el día de la Strong. Lo que no sale descansado, no sale cansado (§1.7-DEKA §3).',
            ],
            6 => [
                'session_a' => 'Los 160 m del Mile son recuperación activa, no sprint: se corren a 45-50 segundos con la respiración controlada, y la zona que viene después se ejecuta, no se sobrevive. Ese es todo el ritmo de carrera (§1.7-DEKA §1).',
                'session_b' => 'Las zonas 2, 5 y 7 del Mile no se sienten: se cronometran. El split de hoy es el split de la carrera; si el 500 m sale en 2:10 con remada limpia, ese es tu ritmo (§1.7-DEKA §5).',
                'session_c' => 'El Mile son 10 estaciones cortas con un 160 m de regalo entre medias: el run se usa para bajar el pulso, no para subirlo. Los que sprintan el run 3 pagan la zona 7; los que lo administran, la cobran (estrategia, §1.7-DEKA §2).',
                'session_d' => 'Los re-tests de hoy se hacen con la misma humildad que en N2: 30 zancadas seguidas, 20 cajas limpias, 25 cal sentado. Cuatro niveles después, lo difícil no es pasarlos — es no menospreciarlos (§1.7-DEKA §5).',
            ],
            7 => [
                'session_a' => 'Los 500 m del Fit no se corren, se administran: 2:30 con la respiración controlada y la zona que viene después se ejecuta. El ritmo negativo se entrena hoy: últimos runs más rápidos que los primeros (estrategia, §1.7-DEKA §1).',
                'session_b' => 'Los ergómetros del Fit llegan con 3 km acumulados: entrena el umbral cansado de hoy. Si la remada se acorta y solo tiran los brazos, baja el ritmo — espalda de regalo no (§1.7-DEKA §5).',
                'session_c' => 'La Fit de transición se corre para terminar, no para brillar: 5 km y 10 zonas con la cabeza fría. Los runs 1-3 conservadores, los 8-10 de carrera; el agua se toma caminando 3 pasos, nunca parado (estrategia, §1.7-DEKA §2).',
                'session_d' => 'Los 100 m de farmers de F3 son el re-test DK4 entero con su carga histórica: 2×16 kg, sin soltar. La puerta no cambió desde N3 — lo que cambiaste tú (§1.7-DEKA §3).',
            ],
            8 => [
                'session_a' => 'En N8 no se entrena fuerza, se entrena carrera: cada 500 m de hoy tiene un ritmo y cada ritmo se anota. El atleta que llega al Fit con diez tiempos escritos en la cabeza no corre la carrera, la ejecuta (estrategia, §1.7-DEKA §1).',
                'session_b' => 'El remo del Fit se gana por diez segundos: 1:55 hoy, 1:45 el día de la carrera. Remada larga, tronco quieto, cierre fuerte — los últimos 200 m se reman con la cabeza (§1.7-DEKA §5).',
                'session_c' => 'La Fit es la carrera que entrenaste ocho niveles: 5 km y 10 zonas con todo lo aprendido — zancadas con rodilla al suelo, caja sin tropiezo, farmers con agarre muerto, slams de cadera. Corre los primeros 2 km como si sobrara tiempo, porque sobra (estrategia de ritmo negativo, §1.7-DEKA §1-§4).',
                'session_d' => 'La sesión D del último nivel es la más tranquila del programa: 30 minutos suaves y movilidad. El cuerpo que llega descansado al Fit es el que corre la segunda mitad como si empezara (§1.7-DEKA §5).',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Puertas de movilidad DK1-DK4 del programa (spec §4.2 y tabla del doc).
     * N1 no evalúa ninguna (se siembran en Bloque 0); N2 evalúa DK1-DK3; N3 evalúa DK4.
     * Se re-verifican como re_test en niveles 6 (DK1-DK3) y 8 (DK4) (lotes posteriores).
     */
    public static function getMobilityGates(): array
    {
        return [
            [
                'code' => 'DK1',
                'name' => 'Zancada continua',
                'description' => 'Puerta de movilidad de rodilla, cadera y tobillo (zona 1: RAM alternating reverse lunges). Se evalúa en N2; exigida a partir de N2 (zancada con carga al 50% de RAM). Siembra en N1.',
                'criteria' => '30 Reverse Lunge alternos sin parar, rodilla de atrás al suelo en cada rep, torso alto, sin dolor matinal >2/10 en rodilla al día siguiente',
            ],
            [
                'code' => 'DK2',
                'name' => 'Caja rítmica',
                'description' => 'Puerta de movilidad de tobillo, rodilla y muñeca (zona 3: box jump/step overs sobre caja 24"). Se evalúa en N2; exigida a partir de N2 (box overs en circuitos con fatiga). Siembra en N1.',
                'criteria' => '20 box overs fluidas sobre caja 20-24" (salto o step over) sin tropiezo, sin doble bote y sin dolor; transición arriba-abajo continua',
            ],
            [
                'code' => 'DK3',
                'name' => 'Bike 25',
                'description' => 'Puerta de lumbar, rodilla y sistema cardiovascular base (zona 7: air bike 25 cal). Se evalúa en N2; exigida a partir de N2 (bike como umbral y en circuitos). Siembra en N1.',
                'criteria' => '25 cal en Assault Bike Sprint/Echo Bike Sprint de forma continua, sentado todo el rato, sin parar los pedales',
            ],
            [
                'code' => 'DK4',
                'name' => 'Agarre farmers',
                'description' => 'Puerta de movilidad de agarre, trapecio y cadena posterior (zona 6: farmers carry). Se evalúa en N3; exigida a partir de N3 (farmers al 50-75% como zona del simulacro). Siembra en N1.',
                'criteria' => 'Farmers Walk con 2×16 kg ×100 m sin soltar las cargas, con postura erguida y pasos cortos',
            ],
        ];
    }

    // ========================================================================
    // LEVEL 1 — BASE DEKA
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): técnica de las 10 zonas sin carga; ergómetros muy cortos (100-250 m); runs 100 m.',
                'Fase 2 (Progresión): farmers ligeros introducido; bike en intervalos cortos; primer circuito de 4 zonas.',
                'Fase 3 (Intensificación): circuito de 6 zonas con runs 150-200 m; ensayo de test integrado (zancada ×20, burpee ×15).',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA: la zancada se progresa por carga antes que por velocidad (rodilla de atrás al suelo, torso alto); la caja 24" se domina primero en step over; farmers con pasos cortos y agarre muerto; extensión completa de cadera antes que altura en el slam.',
                'Si fallas el test: vuelve a la fase Base y afina la técnica — rep con técnica rota no cuenta (§5). En la zancada, rodilla al suelo; en el burpee, pecho al suelo y salto con extensión; en la caja, extensión completa arriba sin doble bote.',
                'Equipamiento: gimnasio con remo, SkiErg, air bike, caja plyo de 20-24", kettlebells/mancuernas, slam ball y cinta o exterior. Sin air bike: remo o SkiErg por tiempo equivalente (~25 cal ≈ 1:30-2:00); sin RAM: mancuernas en back rack o goblet.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Técnica de las 10 zonas sin carga; ergómetros muy cortos (100-250 m); runs 100 m', 'note' => 'Aquí no se compite: se aprende el mapa de las 10 zonas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Farmers ligeros introducido; bike en intervalos cortos; primer circuito de 4 zonas', 'note' => 'La caja tras correr es la lógica DEKA', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Circuito de 6 zonas con runs 150-200 m; ensayo de test integrado', 'note' => 'Zonas 1-4 + bike + burpee: primer recorrido serio', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Reverse Lunge', 'minimum' => 20, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Rodilla de atrás al suelo en cada rep y torso alto (§1); rep con técnica rota no cuenta (§5)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Burpee', 'minimum' => 15, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Pecho al suelo y salto con extensión; rep con técnica rota no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Box Jump (caja 20")', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Extensión completa arriba sin doble bote (§2); la caja de 20" es orientativa (18-24" según disponibilidad, anotándolo); la diana de N2+ es 24"', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Plank', 'minimum' => 45, 'target' => 60, 'unit' => 'seconds', 'direction' => 'gte', 'form' => 'Costillas abajo, glúteo apretado, respiración continua bajo tensión', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL1(): array
    {
        return [
            [
                'code' => 'deka-n1-veinte-zancadas',
                'name' => 'Veinte zancadas',
                'description' => 'Supera el mínimo de Reverse Lunge (20 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Reverse Lunge',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-quince-burpees',
                'name' => 'Quince burpees',
                'description' => 'Supera el mínimo de Burpee (15 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Burpee',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-primera-caja',
                'name' => 'Primera caja superada',
                'description' => 'Supera el mínimo de Box Jump (10 reps, caja 20") en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '📦', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Box Jump (caja 20")',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-plancha-45s',
                'name' => 'Plancha de 45 segundos',
                'description' => 'Supera el mínimo de Plank (45 s) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🧱', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Plank',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-primera-zona-10',
                'name' => 'Primera zona 10 completada',
                'description' => 'Primera sesión C con el circuito de las zonas completo sin parar el reloj.',
                'category' => 'skill', 'icon' => '🏁', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-primer-farmers',
                'name' => 'Primer farmers',
                'description' => 'Primer Farmers Walk de 50 m con mancuernas ligeras sin soltar.',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-primeras-25-cal',
                'name' => 'Primeras 25 cal',
                'description' => 'Primer bloque de 25 cal en Assault Bike Sprint/Echo Bike Sprint completado (siembra DK3).',
                'category' => 'skill', 'icon' => '🚲', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n1-primer-slam',
                'name' => 'Primer slam',
                'description' => 'Primer Medicine Ball Slam con balón de 6/4 kg y extensión de cadera completa (§4).',
                'category' => 'skill', 'icon' => '🏀', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
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
                'Fase 1 (Base): zancada 2×8/2×6 kg con series largas; box overs a caja 20"; bike en intervalos de 10-15 cal.',
                'Fase 2 (Progresión): farmers 2×12/2×8 kg; slam ball 10/6 kg; box overs a caja 24"; primeras 25 cal acumuladas.',
                'Fase 3 (Intensificación): dosis de test integradas (zancada ×20, box jump ×15); ensayos DK1-DK3 en las sesiones A/B/C.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA: la zancada cargada se progresa por carga antes que por velocidad; box overs sin tropiezo; farmers con pasos cortos y agarre muerto; slam con extensión completa de cadera y lumbar neutra.',
                'Si fallas el test: revisa las puertas — DK1 (30 zancadas sin parar), DK2 (20 box overs fluidas) y DK3 (25 cal continuas) son el requisito efectivo del N3. Fallar fuerza + puerta = ciclo corto de movilidad (mobility_blocked), sin repetir fuerza.',
                'Puertas evaluadas en este nivel: DK1, DK2 y DK3. Orden recomendado del test (menor a mayor interferencia): DK1 → zancada → DK2 → box jump → DK3 → bike 25 cal, con 2-3 min entre pruebas. Sustituto del air bike: Echo Bike Sprint, Row 500 m o SkiErg 500 m anotado.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Zancada 2×8/2×6 kg con series largas; box overs a caja 20"; bike en intervalos de 10-15 cal', 'note' => 'Primera zona cargada tras correr', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Farmers 2×12/2×8 kg; slam ball 10/6 kg; box overs a caja 24"; primeras 25 cal acumuladas', 'note' => 'La carga de la puerta DK4 (2×16 kg) llega en N3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Dosis de test integradas (zancada ×20, box jump ×15); ensayos DK1-DK3 en las sesiones A/B/C', 'note' => 'DK3 entera en contexto de fatiga', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Dumbbell Lunge (2×8/2×6 kg)', 'minimum' => 20, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Rodilla de atrás al suelo y torso alto (§1); mancuernas a los lados o en back rack (nota: simula RAM 15/10 kg)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Box Jump (caja 24")', 'minimum' => 15, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Extensión completa arriba sin doble bote (§2); step over válido si el salto no va', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Assault Bike 25 cal (tiempo)', 'minimum' => 150, 'target' => 105, 'unit' => 'seconds', 'direction' => 'lte', 'form' => '25 cal continuas, sentado, sin parar los pedales; si no hay air bike: Echo Bike Sprint, Rowing Machine Sprint 500 m o SkiErg Sprint 500 m como sustituto anotado', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'DK1 — 30 reverse lunges sin parar', 'minimum' => 30, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Rodilla de atrás al suelo en cada rep, torso alto, sin pausa >3 s entre reps (criterio entero de la puerta DK1)', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'DK1'],
                        ['name' => 'DK2 — 20 box overs fluidas', 'minimum' => 20, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Sobre caja 20-24" (salto o step over), sin tropiezo y sin doble bote; transición arriba-abajo continua (criterio entero de la puerta DK2)', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'DK2'],
                        ['name' => 'DK3 — 25 cal bike continuas', 'minimum' => 25, 'target' => 25, 'unit' => 'cal', 'direction' => 'gte', 'form' => '25 cal en Assault Bike Sprint/Echo Bike Sprint de forma continua, sentado, sin parar los pedales (criterio entero de la puerta DK3); objetivo de progreso: 25 cal en <2:00', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'DK3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            [
                'code' => 'deka-n2-zancada-20',
                'name' => 'Zancada de 20 con carga',
                'description' => 'Supera el mínimo de Dumbbell Lunge con 2×8/2×6 kg (20 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dumbbell Lunge (2×8/2×6 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n2-caja-24',
                'name' => 'Caja de 24 pulgadas',
                'description' => 'Supera el mínimo de Box Jump a caja 24" (15 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '📦', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Box Jump (caja 24")',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n2-bike-25',
                'name' => 'Bike 25 cronometrada',
                'description' => 'Completa las 25 cal del Assault Bike bajo el máximo de 2:30 en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚲', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Assault Bike 25 cal (tiempo)',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n2-puerta-dk1',
                'name' => 'Puerta DK1 superada',
                'description' => 'Supera la puerta de movilidad DK1 (Zancada continua) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK1 — 30 reverse lunges sin parar',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n2-puerta-dk2',
                'name' => 'Puerta DK2 superada',
                'description' => 'Supera la puerta de movilidad DK2 (Caja rítmica) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK2 — 20 box overs fluidas',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n2-puerta-dk3',
                'name' => 'Puerta DK3 superada',
                'description' => 'Supera la puerta de movilidad DK3 (Bike 25) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK3 — 25 cal bike continuas',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n2-25-cal-continuas',
                'name' => '25 cal continuas',
                'description' => 'Primeras 25 cal en Assault Bike Sprint/Echo Bike Sprint sin parar los pedales (DK3 entera).',
                'category' => 'skill', 'icon' => '🚲', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n2-primera-zancada-cargada',
                'name' => 'Primera zancada cargada',
                'description' => 'Primer Dumbbell Lunge con 2×8/2×6 kg válido (rodilla al suelo).',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n2-farmers-60m',
                'name' => 'Primer farmers de 60 m',
                'description' => 'Primer Farmers Walk de 60 m sin soltar con 2×12/2×8 kg.',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n2-slam-pesado',
                'name' => 'Primer slam pesado',
                'description' => 'Primer Medicine Ball Slam de 10/6 kg con extensión de cadera completa (§4).',
                'category' => 'skill', 'icon' => '🏀', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3 — MEDIA DEKA
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): 5 zonas al 50-60% con runs 160 m; ergómetros 500-750 m; farmers 2×16 kg introducido.',
                'Fase 2 (Progresión): transiciones zona→run cronometradas; umbrales de bike; ensayo de DK4 (farmers ×100 m).',
                'Fase 3 (Intensificación): benchmark embebido DEKA Media en sesión C; nada de carga nueva en las sesiones previas del simulacro.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA: los 160 m del ritmo Mile se administran con la respiración controlada; la recuperación entre zonas ES el run; farmers con pasos cortos y agarre muerto; si aparece dolor matinal >2/10, se congela el gesto un ciclo.',
                'Si fallas el test: el mínimo de la DEKA Media (20:00) está bajo el cap de 30:00 y DK4 (farmers 2×16 kg ×100 m) protege las zonas con farmers al 100% Open de N4+. Fallar benchmark + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Puerta evaluada en este nivel: DK4. Orden recomendado: DK4 primero (descansado) y la DEKA Media después, o en sesiones separadas del envío de test.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => '5 zonas al 50-60% con runs 160 m; ergómetros 500-750 m; farmers 2×16 kg introducido', 'note' => 'El ritmo Mile se aprende aquí: 160 m con recuperación caminando', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Transiciones zona→run cronometradas; umbrales de bike; ensayo de DK4 (farmers ×100 m)', 'note' => 'Cada transición se anota: los datos mandan', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: DEKA Media en sesión C (5× run 160 m + zona)', 'note' => 'Nada de carga nueva en las sesiones previas del simulacro', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Media', 'minimum' => 1200, 'target' => 900, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark DEKA Media en modalidad RX (50%): 5×(run 160 m + zona: zancada ×15 con 2×8/2×6 kg, row 250 m, box ×10 a caja 24", farmers 50 m con 2×12/2×8 kg, burpees ×10); timeCap 30:00. Mínimo 20:00 (regla F-Elizabeth, bajo el cap, en torno a intermediate F 20:00); objetivo 15:00 entre advanced F (15:00) e intermediate M (17:00). La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Media'],
                        ['name' => 'DK4 — Farmers 2×16 kg ×100 m sin soltar', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => '100 m con 2×16 kg sin soltar, postura erguida y pasos cortos (§3, criterio entero de la puerta DK4); objetivo de progreso: 100 m en <1:45', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'DK4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            [
                'code' => 'deka-n3-media-completada',
                'name' => 'Media DEKA completada',
                'description' => 'Completa la DEKA Media dentro del cap de 30:00 (mínimo 20:00) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DEKA Media',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n3-media-bajo-15',
                'name' => 'Media DEKA bajo 15',
                'description' => 'Completa la DEKA Media en menos de 15:00 (objetivo del test de Nivel 3).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'DEKA Media', 'requirementValue' => 900,
                'xpReward' => 150,
            ],
            [
                'code' => 'deka-n3-puerta-dk4',
                'name' => 'Puerta DK4 superada',
                'description' => 'Supera la puerta de movilidad DK4 (Agarre farmers) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK4 — Farmers 2×16 kg ×100 m sin soltar',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n3-primer-simulacro',
                'name' => 'Primer simulacro completado',
                'description' => 'Primera ejecución completa de la DEKA Media en sesión C (F3), dentro del cap de 30:00.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n3-farmers-2x16',
                'name' => 'Farmers de 2×16 kg',
                'description' => 'Primer Farmers Walk de 100 m con 2×16 kg sin soltar (DK4 entera).',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n3-cinco-zonas',
                'name' => 'Cinco zonas seguidas',
                'description' => 'Primer circuito de 5 zonas consecutivas del nivel sin parar el reloj.',
                'category' => 'skill', 'icon' => '🔁', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n3-ritmo-mile',
                'name' => 'Ritmo Mile',
                'description' => 'Primer run de 160 m a ritmo de carrera (bajo 45 s) saliendo de una zona, manteniendo el ritmo en la siguiente transición.',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4 — ZONAS OPEN
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): zonas al 75-85% oficial con series largas; umbrales 500-750 m; farmers 2×20/2×14 kg.',
                'Fase 2 (Progresión): zonas al 90-100%; dosis de test integradas (box ×20, sit-up ×25); farmers 2×27/2×18 kg ×50-75 m.',
                'Fase 3 (Intensificación): todas las zonas al 100%; umbrales 1.000 m; ensayo DK4 en sesión D.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA: la lógica del nivel es "una zona a la vez" — fuerza pura con series largas, 1-3 RIR y descanso completo; la zancada sube por carga antes que por velocidad; el slam con extensión completa de cadera.',
                'Si fallas el test: revisa el re-test DK4 (farmers 2×16 kg ×100 m sin soltar — carga histórica de la puerta, menor que los 2×27/2×18 kg del ítem de fuerza) y la técnica de cada zona. Fallar fuerza + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Orden recomendado del test (menor a mayor interferencia): DK4 → zancada → box jump → sit-up MB → farmers Open.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Zonas al 75-85% oficial con series largas; umbrales 500-750 m; farmers 2×20/2×14 kg', 'note' => 'Una zona a la vez: fuerza pura', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Zonas al 90-100%; dosis de test integradas (box ×20, sit-up ×25); farmers 2×27/2×18 kg ×50-75 m', 'note' => 'El farmers Open saliendo de correr llega en N5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Todas las zonas al 100%; umbrales 1.000 m; ensayo DK4 en sesión D', 'note' => '"Mantener, no construir" en la carga', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Dumbbell Lunge (2×16/2×10 kg)', 'minimum' => 20, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Rodilla de atrás al suelo y torso alto (§1); la carga real de RAM va en back rack, la mancuerna es sustituto (nota)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Farmers Walk (2×27/2×18 kg)', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => 'Pesos oficiales por primera vez; sin soltar y postura erguida (§3); objetivo de progreso: 100 m en <2:30', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Box Jump (caja 24")', 'minimum' => 20, 'target' => 25, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Extensión completa arriba sin doble bote (§2); step over válido si el salto no va', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Sit-up (MB 9/6.5 kg)', 'minimum' => 25, 'target' => 40, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Toque entre pies con medicine ball (la zona 4 de Strong/Mile)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'DK4 (re-test) — Farmers 2×16 kg ×100 m sin soltar', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => 'Re-test de la puerta con su CARGA HISTÓRICA de definición (2×16 kg), menor que la del ítem de fuerza (2×27/2×18 kg): verifica que el gesto sigue siendo seguro (ruling "carga histórica"). Criterio: 100 m sin soltar, postura erguida y pasos cortos; objetivo de progreso: 100 m en <1:45', 'type' => TestItemType::ReTest->value, 'gateCode' => 'DK4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            [
                'code' => 'deka-n4-zancada-open',
                'name' => 'Zancada Open',
                'description' => 'Supera el mínimo de Dumbbell Lunge con 2×16/2×10 kg (20 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dumbbell Lunge (2×16/2×10 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n4-farmers-open',
                'name' => 'Farmers Open',
                'description' => 'Supera el mínimo de Farmers Walk con 2×27/2×18 kg (100 m) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🧳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Farmers Walk (2×27/2×18 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n4-caja-competicion',
                'name' => 'Caja de competición',
                'description' => 'Supera el mínimo de Box Jump a caja 24" (20 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '📦', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Box Jump (caja 24")',
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n4-puerta-dk4-revalidada',
                'name' => 'Puerta DK4 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad DK4 (Farmers 2×16 kg ×100 m sin soltar) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK4 (re-test) — Farmers 2×16 kg ×100 m sin soltar',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n4-primer-sled-5x10',
                'name' => 'Primer sled 5×10',
                'description' => 'Primer bloque de Sled Push 5×10 m + Sled Pull 5×10 m con peso completado (§1).',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n4-slam-27kg',
                'name' => 'Slam de 27 kg',
                'description' => 'Primer Medicine Ball Slam de 27/18 kg con extensión de cadera completa (§4).',
                'category' => 'skill', 'icon' => '🏀', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n4-burpee-cargado',
                'name' => 'Burpee cargado',
                'description' => 'Primer Burpee con carga (chaleco 20/10 kg o balón) completo.',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n4-umbral-1000m',
                'name' => 'Umbral de 1.000 m',
                'description' => 'Primer Rowing Machine Sprint o SkiErg Sprint continuo de 1.000 m al ritmo previsto.',
                'category' => 'skill', 'icon' => '🎿', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5 — DEKA STRONG
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): parejas de zonas con fatiga moderada (1→2, 3→4); transiciones sin cronómetro; umbrales cortos.',
                'Fase 2 (Progresión): parejas y tríos al RX; transiciones cronometradas sub-45 s; farmers de ida y vuelta.',
                'Fase 3 (Intensificación): benchmark embebido DEKA Strong en sesión C; sesiones A/B/D a "mantener, no construir".',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA + estrategia Strong: la transición entre zonas es parte del tiempo de carrera — salir de cada zona sin pausa, material preparado, caminar rápido al siguiente implemento.',
                'Si fallas el test: el mínimo (24:00) está bajo el cap de 30:00. Anota tus transiciones: en Strong la carrera se gana caminando entre zonas, no en las zonas.',
                'Es la primera carrera completa del programa (10 zonas RX sin correr): hidrátate antes de la sesión C de Fase 3 y prepara el material de cada zona antes de empezar.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Parejas de zonas con fatiga moderada (1→2, 3→4); transiciones sin cronómetro; umbrales cortos', 'note' => 'Zonas 1-6 y 8-10 comprimidas en parejas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Parejas y tríos al RX; transiciones cronometradas sub-45 s; farmers de ida y vuelta', 'note' => 'Anota cada transición: los datos mandan', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: DEKA Strong en sesión C (10 zonas RX en orden, sin runs)', 'note' => 'Sesiones A/B/D a "mantener, no construir"', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Strong', 'minimum' => 1440, 'target' => 1080, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark DEKA Strong en modalidad RX (array ZONAS RX: 10 zonas oficiales en orden, sin runs; la transición entre zonas es caminando y forma parte del tiempo); timeCap 30:00. Mínimo 24:00 (regla F-Elizabeth, bajo el cap, en torno a beginner M 24:00); objetivo 18:00 = intermediate M (18:00). La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Strong'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            [
                'code' => 'deka-n5-strong-completada',
                'name' => 'Primera DEKA Strong completa',
                'description' => 'Completa la DEKA Strong dentro del cap de 30:00 (mínimo 24:00) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DEKA Strong',
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n5-strong-sub-18',
                'name' => 'Strong sub-18',
                'description' => 'Completa la DEKA Strong en menos de 18:00 (objetivo del test de Nivel 5).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'DEKA Strong', 'requirementValue' => 1080,
                'xpReward' => 300,
            ],
            [
                'code' => 'deka-n5-diez-zonas-seguidas',
                'name' => 'Diez zonas seguidas',
                'description' => 'Termina la DEKA Strong en menos de 30:00: las 10 zonas oficiales completas dentro del cap.',
                'category' => 'progress', 'icon' => '🔁', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'DEKA Strong', 'requirementValue' => 1800,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n5-zona-10-sin-romper',
                'name' => 'Zona 10 sin romper',
                'description' => 'Primer Burpee cargado (nota RAM 20/10 kg) ×20 de una tanda completa sin romper técnica.',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n5-transicion-sub-30',
                'name' => 'Transición sub-30',
                'description' => 'Primera transición entre zonas del circuito de ritmo en menos de 30 s (material listo, sin pausa).',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n5-trio-8-9-10',
                'name' => 'Trío 8-9-10',
                'description' => 'Primer tren Medicine Ball Slam → Sled Push/Sled Pull → Burpee completo con las dosis del circuito.',
                'category' => 'skill', 'icon' => '🛷', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n5-farmers-ida-vuelta',
                'name' => 'Farmers Open de ida y vuelta',
                'description' => 'Primer Farmers Walk 10 m ida y vuelta ×5 con 2×27/2×18 kg sin soltar (dosis exacta de la zona 6 en Strong/Mile).',
                'category' => 'skill', 'icon' => '🧳', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6 — DEKA MILE
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): runs 160 m a ritmo con zona intercalada; parejas de zonas RX; transiciones sub-30 s.',
                'Fase 2 (Progresión): circuitos de 5 zonas con runs (primera mitad y segunda mitad del Mile); re-tests DK1-DK3 ensayados.',
                'Fase 3 (Intensificación): benchmark embebido DEKA Mile en sesión C; sesiones A/B/D a "mantener, no construir".',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA + estrategia Mile: los 160 m son recuperación activa, no sprint — se corren a 45-50 s con la respiración controlada y la zona siguiente se ejecuta con el pulso bajado; transiciones sub-20 s.',
                'Si fallas el test: el mínimo (33:00) está bajo el cap de 60:00 y los re-tests DK1-DK3 verifican que la base de movilidad sigue intacta antes del tramo Fit. Fallar benchmark + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Orden recomendado del test: re-tests primero (descansado, en sesión separada si hace falta) y el Mile al final, con al menos una sesión de movilidad entre medias.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Runs 160 m a ritmo con zona intercalada; parejas de zonas RX; transiciones sub-30 s', 'note' => 'El run se usa para bajar el pulso, no para subirlo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Circuitos de 5 zonas con runs (primera y segunda mitad del Mile); re-tests DK1-DK3 ensayados', 'note' => 'Los splits de zona se cronometran', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: DEKA Mile en sesión C (10× run 160 m + zona RX)', 'note' => 'Sesiones A/B/D a "mantener, no construir"', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Mile', 'minimum' => 1980, 'target' => 1620, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark DEKA Mile en modalidad RX (array ZONAS RX): 10×(run 160 m + zona en orden oficial); timeCap 60:00. Mínimo 33:00 (regla F-Elizabeth, bajo el cap, en torno a beginner M 33:00); objetivo 27:00 = intermediate M (27:00). La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Mile'],
                        ['name' => 'DK1 (re-test) — 30 reverse lunges sin parar', 'minimum' => 30, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Re-test con el criterio histórico de la puerta: 30 Reverse Lunge alternos sin parar, rodilla de atrás al suelo en cada rep, torso alto; objetivo de progreso: sin pausa >3 s', 'type' => TestItemType::ReTest->value, 'gateCode' => 'DK1'],
                        ['name' => 'DK2 (re-test) — 20 box overs fluidas', 'minimum' => 20, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Re-test con el criterio histórico de la puerta: 20 box overs fluidas sobre caja 20-24" (salto o step over), sin tropiezo ni doble bote', 'type' => TestItemType::ReTest->value, 'gateCode' => 'DK2'],
                        ['name' => 'DK3 (re-test) — 25 cal bike continuas', 'minimum' => 25, 'target' => 25, 'unit' => 'cal', 'direction' => 'gte', 'form' => 'Re-test con el criterio histórico de la puerta: 25 cal en Assault Bike Sprint/Echo Bike Sprint continuas, sentado, sin parar los pedales; objetivo de progreso: 25 cal en <2:00', 'type' => TestItemType::ReTest->value, 'gateCode' => 'DK3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            [
                'code' => 'deka-n6-mile-completada',
                'name' => 'Primera DEKA Mile completa',
                'description' => 'Completa la DEKA Mile dentro del cap de 60:00 (mínimo 33:00) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DEKA Mile',
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n6-mile-sub-27',
                'name' => 'Mile sub-27',
                'description' => 'Completa la DEKA Mile en menos de 27:00 (objetivo del test de Nivel 6).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'DEKA Mile', 'requirementValue' => 1620,
                'xpReward' => 300,
            ],
            [
                'code' => 'deka-n6-puerta-dk1-revalidada',
                'name' => 'Puerta DK1 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad DK1 (Zancada continua) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK1 (re-test) — 30 reverse lunges sin parar',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n6-puerta-dk2-revalidada',
                'name' => 'Puerta DK2 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad DK2 (Caja rítmica) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK2 (re-test) — 20 box overs fluidas',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n6-puerta-dk3-revalidada',
                'name' => 'Puerta DK3 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad DK3 (Bike 25) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK3 (re-test) — 25 cal bike continuas',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n6-diez-veces-160',
                'name' => 'Diez veces ciento sesenta',
                'description' => 'Primera ejecución completa de la DEKA Mile en sesión C (F3), dentro del cap de 60:00.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n6-160-recuperacion',
                'name' => '160 m de recuperación',
                'description' => 'Primer run de 160 m a ritmo sostenible (45-50 s) saliendo de una zona y entrando en la siguiente sin romper técnica.',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n6-ritmo-mile',
                'name' => 'Ritmo de Mile',
                'description' => 'Circuito completo de 5 zonas con runs bajo 50 s cada uno y splits de zona controlados.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n6-transicion-sub-20',
                'name' => 'Transición sub-20',
                'description' => 'Primera transición zona→run del circuito en menos de 20 s.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7 — TRANSICIÓN FIT
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): runs 500 m a ritmo con 1 zona intercalada (2.500 m semanales en sesiones); trenes de 2 zonas.',
                'Fase 2 (Progresión): runs 500 m con 2 zonas (4.000 m semanales); trenes de 3; ensayo DK4 en D.',
                'Fase 3 (Intensificación): benchmark embebido DEKA Fit en sesión C; sesiones A/B/D a "afilar, no construir".',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA + estrategia del Fit: ritmo negativo en los 500 m (los últimos 3 runs más rápidos que los primeros 3), transición run→zona en 3-5 pasos (no sprintar la entrada ni pausar la salida), series planificadas en lunge (bloques de 10) y burpee (bloques de 5), y agua en las transiciones de los runs pares.',
                'Si fallas el test: el mínimo (60:00) está muy por debajo del cap de 90:00 y de beginner M (48:00) — es una toma de contacto con el formato largo, no una exigencia de ritmo; el atleta puede ejecutarlo scaled con runs 250-400 m. Fallar benchmark + puerta = ciclo corto de movilidad (mobility_blocked).',
                'Orden recomendado: DK4 descansado primero y la DEKA Fit después (o en sesiones separadas del envío de test). El Fit es el esfuerzo más largo del programa: hidratación y plan de ritmo negativo antes de empezar.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Runs 500 m a ritmo con 1 zona intercalada (2.500 m semanales en sesiones); trenes de 2 zonas', 'note' => 'Se aprende a administrar los 500 m', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Runs 500 m con 2 zonas (4.000 m semanales); trenes de 3; ensayo DK4 en D', 'note' => 'El ritmo negativo se entrena aquí', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: DEKA Fit en sesión C (10× run 500 m + zona RX)', 'note' => 'Sesiones A/B/D a "afilar, no construir"', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Fit', 'minimum' => 3600, 'target' => 2880, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark DEKA Fit (ficha del lote 2) en fase de transición: el atleta elige RX (runs 500 m) o scaled (runs 250-400 m según scalingOptions); la modalidad se registra en UserBenchmarkAttempt. 10×(run 500 m + zona del array ZONAS RX en orden); timeCap 90:00. Mínimo 60:00 (regla F-Elizabeth, muy por debajo del cap y de beginner M 48:00 — toma de contacto con el formato largo); objetivo 48:00 = beginner M. La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Fit'],
                        ['name' => 'DK4 (re-test) — Farmers 2×16 kg ×100 m sin soltar', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'form' => 'Re-test de la puerta con su CARGA HISTÓRICA de definición (2×16 kg, menor que la de trabajo 2×27/2×18 kg): verifica que el gesto sigue siendo seguro (ruling "carga histórica"). Criterio: 100 m sin soltar, postura erguida y pasos cortos; objetivo de progreso: 100 m en <1:45', 'type' => TestItemType::ReTest->value, 'gateCode' => 'DK4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            [
                'code' => 'deka-n7-primera-fit',
                'name' => 'Primera DEKA Fit completa',
                'description' => 'Completa la DEKA Fit dentro del cap de 90:00 (mínimo 60:00) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DEKA Fit',
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n7-fit-bajo-48',
                'name' => 'Fit bajo 48',
                'description' => 'Completa la DEKA Fit en menos de 48:00 (objetivo del test de Nivel 7).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'DEKA Fit', 'requirementValue' => 2880,
                'xpReward' => 300,
            ],
            [
                'code' => 'deka-n7-puerta-dk4-revalidada',
                'name' => 'Puerta DK4 re-validada',
                'description' => 'Supera el re-test de la puerta de movilidad DK4 (Farmers 2×16 kg ×100 m sin soltar) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DK4 (re-test) — Farmers 2×16 kg ×100 m sin soltar',
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n7-cinco-mil-metros',
                'name' => 'Cinco mil metros de carrera en zonas',
                'description' => 'Primera sesión con 5.000 m de runs acumulados junto a zonas (circuito de sesión C en F2).',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n7-500m-ritmo',
                'name' => '500 m a ritmo',
                'description' => 'Primer run de 500 m bajo 2:30 con zona intercalada ejecutada con técnica completa.',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'deka-n7-tren-tres-zonas',
                'name' => 'Tren de tres zonas',
                'description' => 'Primer tren de 3 zonas RX (zancada→remo→caja o slam→sled→burpee) completo con fatiga.',
                'category' => 'skill', 'icon' => '🚂', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n7-segunda-mitad-fuerte',
                'name' => 'Segunda mitad fuerte',
                'description' => 'Circuito completo con los 3 últimos runs más rápidos que los 3 primeros (ritmo negativo entrenado).',
                'category' => 'skill', 'icon' => '📈', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8 — DEKA FIT (CIERRE DEL PROGRAMA)
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primera repetición RX del Fit a ritmo (circuitos de 5 zonas con 500 m); estrategia de carrera en cada WOD.',
                'Fase 2 (Progresión): circuitos completos de 10 zonas con runs; ritmo negativo cronometrado; transiciones de 3-5 pasos.',
                'Fase 3 (Intensificación): benchmark embebido DEKA Fit RX en sesión C; sesiones A/B/D a "afilar, no construir".',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-DEKA + estrategia del Fit: ritmo negativo escrito (últimos 3 runs más rápidos que los primeros 3), transiciones de 3-5 pasos, series planificadas (lunge 10+10+10, burpee 4×5, box 4×5), gestión del bike y el slam bajo fatiga, e hidratación en los runs pares.',
                'Si fallas el test: el mínimo (48:00) = beginner M RX está bajo el cap de 90:00; el objetivo (40:00) = intermediate M. La marca se compara con la de N7 (mismo benchmark, distinta fase): el progreso del programa se mide en minutos ahorrados en el mismo recorrido.',
                'Cierre del programa: superar la DEKA Fit RX cierra el DEKA Mastery (logro "DEKA Mastery completada"). El cuerpo que llega descansado es el que corre la segunda mitad como si empezara.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primera repetición RX del Fit a ritmo (circuitos de 5 zonas con 500 m); estrategia de carrera en cada WOD', 'note' => 'restAfterBlock 90 s: sesiones densas en calidad', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Circuitos completos de 10 zonas con runs; ritmo negativo cronometrado; transiciones de 3-5 pasos', 'note' => 'Cada ritmo escrito es una decisión ya tomada', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Benchmark embebido: DEKA Fit RX en sesión C (10× run 500 m + zona RX)', 'note' => 'Sesiones A/B/D a "afilar, no construir"', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final — cierre del programa', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Fit', 'minimum' => 2880, 'target' => 2400, 'unit' => 'seconds', 'direction' => 'lte', 'form' => 'Benchmark DEKA Fit (misma ficha que N7) en modalidad RX (runs 500 m): 10×(run 500 m + zona del array ZONAS RX en orden); timeCap 90:00. Mínimo 48:00 (regla F-Elizabeth, bajo el cap, en torno a beginner M 48:00); objetivo 40:00 = intermediate M (40:00). Cierre narrativo: la marca se compara con la de N7 (mismo benchmark, distinta fase). La marca se registra en UserBenchmarkAttempt y alimenta el test', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Fit'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            [
                'code' => 'deka-n8-fit-sub-40',
                'name' => 'Fit sub-40',
                'description' => 'Completa la DEKA Fit dentro del cap de 90:00 (mínimo 48:00) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DEKA Fit',
                'xpReward' => 300,
            ],
            [
                'code' => 'deka-n8-ritmo-competicion',
                'name' => 'Ritmo de competición',
                'description' => 'Completa la DEKA Fit en menos de 40:00 (objetivo del test de Nivel 8).',
                'category' => 'progress', 'icon' => '⚡', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'DEKA Fit', 'requirementValue' => 2400,
                'xpReward' => 300,
            ],
            [
                'code' => 'deka-n8-deka-mastery-completada',
                'name' => 'DEKA Mastery completada',
                'description' => 'Supera el test de Nivel 8 (DEKA Fit RX de cierre): cierre del programa DEKA Mastery.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 500,
            ],
            [
                'code' => 'deka-n8-primera-fit-rx',
                'name' => 'Primera DEKA Fit RX completa',
                'description' => 'Primera ejecución completa de la DEKA Fit en modalidad RX (runs 500 m), dentro del cap.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n8-repeticion-carrera',
                'name' => 'Repetición de carrera',
                'description' => 'Segunda ejecución completa del simulacro (circuitos F1/F2/F4 de la sesión C) dentro del cap en una misma fase.',
                'category' => 'skill', 'icon' => '🔁', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'deka-n8-diez-mil-metros',
                'name' => 'Diez mil metros en carrera',
                'description' => 'Primera fase de entrenamiento con 10.000 m de runs acumulados (solo posible con las repeticiones de la sesión C).',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'deka-n8-ritmo-negativo-ejecutado',
                'name' => 'Ritmo negativo ejecutado',
                'description' => 'Simulacro o circuito completo con los 3 últimos runs más rápidos que los 3 primeros.',
                'category' => 'skill', 'icon' => '📈', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }
}
