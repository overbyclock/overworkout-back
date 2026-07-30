<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Calistenia Master v4.0 (niveles 1-6).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_niveles_01-03.md,
 * docs/detalle_niveles_04-06.md y de
 * docs/arquitectura_niveles_calistenia.md.
 *
 * Los nombres de los ítems de testRequirements se usan como requirementKey
 * de los logros desbloqueados por ítem (deben coincidir EXACTAMENTE).
 */
class CalisteniaMasterContentV4
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
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    /**
     * Nombre visible del bloque de sesión según su tipo (y la sesión, para el bloque principal).
     */
    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_PRINCIPAL => match ($sessionKey) {
                'session_a' => 'Fuerza — Empuje',
                'session_b' => 'Fuerza — Tracción',
                'session_c' => 'Circuito — Pierna y Core',
                'session_d' => 'Skill y Técnica',
                default => 'Bloque principal',
            },
            TrainingRound::BLOCK_POSTURAL => 'Salud postural',
            TrainingRound::BLOCK_CADENA_POSTERIOR => 'Cadena posterior y cadera',
            TrainingRound::BLOCK_DESCARGA => 'Descarga',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Descripción del bloque de sesión. Para el bloque principal, el comando
     * usa el objetivo (goal) de la sesión cuando está disponible.
     */
    public static function getBlockDescription(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Activación articular, muñecas y movilidad específica para preparar la sesión.',
            TrainingRound::BLOCK_PRINCIPAL => 'Trabajo principal de la sesión.',
            TrainingRound::BLOCK_POSTURAL => 'Trabajo de manguito rotador, retracción escapular y cuidado del hombro.',
            TrainingRound::BLOCK_CADENA_POSTERIOR => 'Isquiosurales, glúteo, bisagra de cadera y gemelo/sóleo.',
            TrainingRound::BLOCK_DESCARGA => 'Vuelta a la calma, respiración y estiramientos suaves.',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Puertas de movilidad G1-G6 del programa (arquitectura v4 §1).
     * N1-N3 no las testean: se crean ligadas al programa para niveles posteriores.
     */
    public static function getMobilityGates(): array
    {
        return [
            [
                'code' => 'G1',
                'name' => 'Flexión de hombro completa',
                'description' => 'Puerta de movilidad de hombro en flexión. Se testea en el Nivel 4 y se re-testea en el Nivel 9.',
                'criteria' => 'De pie contra pared, espalda y lumbar pegadas, brazos suben hasta tocar la pared sin arquear ni separar.',
            ],
            [
                'code' => 'G2',
                'name' => 'Extensión de muñeca 90°',
                'description' => 'Puerta de movilidad de muñeca. Se testea en el Nivel 4 y se re-testea en el Nivel 9.',
                'criteria' => 'Palma en suelo, dedos hacia rodillas, antebrazo vertical sin dolor, 20 s.',
            ],
            [
                'code' => 'G3',
                'name' => 'Dorsiflexión de tobillo',
                'description' => 'Puerta de movilidad de tobillo. Se testea en el Nivel 8 y se re-testea en el Nivel 12.',
                'criteria' => 'Knee-to-wall ≥10 cm sin despegar talón (objetivo 12 cm, diferencia entre lados <2 cm).',
            ],
            [
                'code' => 'G4',
                'name' => 'Extensión de hombro (German hang)',
                'description' => 'Puerta de movilidad de hombro en extensión. Se testea en el Nivel 6 y se re-testea en el Nivel 10.',
                'criteria' => 'German hang asistido (anillas, pies apoyados) 15 s sin dolor ni pinzamiento.',
            ],
            [
                'code' => 'G5',
                'name' => 'Compresión de cadera',
                'description' => 'Puerta de movilidad de cadera. Se testea en el Nivel 6 y se re-testea en el Nivel 12.',
                'criteria' => 'Sentado, piernas extendidas y juntas, manos como apoyo ligero: elevar ambas piernas y mantener 10 s sin flexionar rodillas ni redondear lumbar.',
            ],
            [
                'code' => 'G6',
                'name' => 'Pike/isquiosurales',
                'description' => 'Puerta de movilidad de cadena posterior. Se testea en el Nivel 9 y se re-testea en el Nivel 12.',
                'criteria' => 'De pie, piernas juntas extendidas, tocar suelo con palmas completas (no yemas) sin flexionar rodillas; alternativa sentado: pecho a muslos con agarre de pies.',
            ],
        ];
    }

    /**
     * Devuelve la nota técnica para un ejercicio específico en un nivel.
     */
    public static function getExerciseNote(int $levelNumber, string $exerciseName): ?string
    {
        $specific = match ($levelNumber) {
            1 => self::EXERCISE_NOTES_L1,
            2 => self::EXERCISE_NOTES_L2,
            3 => self::EXERCISE_NOTES_L3,
            4 => self::EXERCISE_NOTES_L4,
            5 => self::EXERCISE_NOTES_L5,
            6 => self::EXERCISE_NOTES_L6,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1 — DESPERTAR
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): graba una serie de cada gesto con el móvil o entrena frente a un espejo. En N1 no buscas cansarte: buscas que el movimiento se parezca al vídeo de referencia. La fatiga es un subproducto, no el objetivo.',
                'Fase 2 (Progresión): sube primero repeticiones y solo después dificultad (inclinación). Nunca ambas el mismo ciclo.',
                'Fase 3 (Intensificación): los simulacros de test en sesión son informativos: anota los números. Si superas los mínimos en los ensayos, el test de Fase 4 es un trámite.',
                'Fase 4 (Recuperación): no conviertas el deload en un test continuo. Los 50% se respetan; el único máximo de la fase es el test oficial, descansado.',
                'Si fallas el test: casi siempre es la plancha o el colgado (agarre). No subas la dificultad: añade una serie más de cada uno en las sesiones A/B y mantén la fase Base un ciclo extra. El agarre se construye colgándose, no apretando.',
                'Seguridad N1: (1) dolor articular ≠ molestia muscular: cualquier dolor punzante en muñeca, hombro o rodilla detiene el ejercicio y se retrocede un peldaño; (2) en los colgados, baja antes de que el agarre falle del todo; (3) molestia matinal >2/10 en un tendón = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Aprender patrones; variantes más fáciles', 'note' => 'Técnica por encima de volumen', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir reps/rondas o bajar la inclinación', 'note' => 'Primera exposición completa a los gestos del test', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Variantes más duras del nivel, calidad máxima', 'note' => 'Ensayo de test integrado en sesión', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Flexión inclinada', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Manos elevadas en apoyo firme, pecho al apoyo, cuerpo en línea de cabeza a talones', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fila horizontal alta', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Barra baja/anillas altas, pecho a la barra, cuerpo rígido en línea', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Sentadilla al aire', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'form' => 'Profundidad cómoda, talón pegado, rodillas siguen la punta de los pies', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Plancha frontal', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Antebrazos en suelo, costillas abajo, glúteo apretado, respiración continua', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Colgado pasivo', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Agarre completo con pulgar cerrado, brazos extendidos, sin balanceo', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL1(): array
    {
        return [
            [
                'code' => 'v4-n1-primera-plancha-30s',
                'name' => 'Primera plancha 30 s',
                'description' => 'Supera el mínimo de plancha frontal (30 s) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🧱', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Plancha frontal',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n1-primer-colgado-20s',
                'name' => 'Primer colgado 20 s',
                'description' => 'Supera el mínimo de dead hang (20 s) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🤝', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Colgado pasivo',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n1-15-sentadillas',
                'name' => '15 sentadillas seguidas',
                'description' => 'Supera el mínimo de sentadilla al aire (15 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Sentadilla al aire',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n1-primera-flexion-inclinada',
                'name' => 'Primera flexión inclinada completa',
                'description' => 'Primera flexión inclinada con pecho al apoyo y cuerpo en línea.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n1-primera-fila-horizontal',
                'name' => 'Primera fila horizontal',
                'description' => 'Primera fila alta con pecho a la barra/anillas y cuerpo rígido.',
                'category' => 'skill', 'icon' => '🚣', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2 — CIMIENTOS
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): no saltes la excéntrica. La mayoría de usuarios consigue su primera flexión completa por el freno excéntrico, no por empujar más fuerte desde abajo.',
                'Fase 2 (Progresión): la primera flexión completa suele salir en la segunda serie de intentos, no en la primera. Calienta el patrón con excéntricas antes de intentarla.',
                'Fase 3 (Intensificación): series cortas de flexión completa (3-5) con descansos completos rinden más que una serie larga al fallo. Anota el total acumulado: debería rondar las 15-20.',
                'Fase 4 (Recuperación): el hollow y el support se degradan rápido si se abandonan; mantén las exposiciones suaves aunque la fase sea de descarga.',
                'Si fallas el test: el punto débil típico es la flexión completa (falta de fondo): trabaja excéntricas lentas en la fase Intensificación en lugar de más repeticiones a medias. Si el support hold falla, son trapecios dormidos: más face pulls y wall slides, no más tiempo colgado.',
                'Seguridad N2: (1) codos a 45° en todas las flexiones desde la primera sesión — abrir a 90° es el camino corto a la molestia de hombro; (2) en el hollow, dolor lumbar = retrocede un peldaño sin penalización; (3) en las paralelas del support hold, verifica la estabilidad del aparato antes de cargar; (4) molestia matinal de tendón >2/10 congela el gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Peldaños intermedios', 'note' => 'Flexión de rodillas + excéntricas; hollow tuck; support asistido', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primeras flexiones completas acumuladas', 'note' => 'Hollow brazos abajo; support semiasistido', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Flexiones completas en series; excéntricas lentas', 'note' => 'Ensayos de test integrados', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Flexión completa en suelo', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, codos a 45°, línea intacta de cabeza a talones', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fila horizontal baja', 'minimum' => 10, 'target' => 14, 'unit' => 'repeticiones', 'form' => 'Cuerpo casi horizontal, pecho a la barra en cada rep', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Hollow body hold', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Lumbar sellada al suelo en todo momento, brazos junto al cuerpo o arriba', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Support hold', 'minimum' => 15, 'target' => 25, 'unit' => 'segundos', 'form' => 'Paralelas/parallettes, hombros deprimidos, codos extendidos, cuerpo quieto', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Sentadilla profunda', 'minimum' => 25, 'target' => 30, 'unit' => 'repeticiones', 'form' => 'Cadera por debajo de la rodilla, talón pegado en todo el recorrido', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            [
                'code' => 'v4-n2-primera-flexion-completa',
                'name' => 'PRIMERA FLEXIÓN COMPLETA',
                'description' => 'Primera flexión completa en suelo con pecho al suelo y línea intacta. Hito mayor del nivel: se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n2-primer-hollow-20s',
                'name' => 'Primer hollow 20 s',
                'description' => 'Supera el mínimo de hollow body hold (20 s) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🥄', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Hollow body hold',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n2-25-sentadillas',
                'name' => '25 sentadillas',
                'description' => 'Supera el mínimo de sentadilla profunda (25 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Sentadilla profunda',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n2-12-flexiones',
                'name' => '12 flexiones',
                'description' => 'Alcanza la marca objetivo de flexión completa en suelo (12 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_OBJ, 'requirementKey' => 'Flexión completa en suelo',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n2-primer-support-hold',
                'name' => 'Primer support hold',
                'description' => 'Primer apoyo sobre paralelas/parallettes con hombros deprimidos y codos extendidos, aunque sea asistido con pies.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3 — PRIMERA TRACCIÓN
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): las tres vías de dominada se sienten distintas y es normal progresar desigual: la mayoría domina antes la banda que la negativa. No cambies el plan por ello; el test las exige a todas.',
                'Fase 2 (Progresión): la negativa de 5 s se gana en el último segundo. Cuando cuentes, cuenta despacio y con el freno puesto: "5 segundos" rápidos no son la marca.',
                'Fase 3 (Intensificación): respeta los descansos de 150 s en negativas: el excéntrico pesado con descansos cortos es la receta del codo irritado. El tuck L-sit mejora más con 5 series frescas que con 3 agotadas.',
                'Fase 4 (Recuperación): las negativas se reducen a 2×2 suaves incluso si "te sientes fuerte": el tendón del bíceps se recupera más despacio que el músculo.',
                'Si fallas el test: si la negativa se desploma antes de 3 s, el freno es el problema: baja en 2-3 s y alarga solo cuando controles. Si el scapular pull no sale, cuelga más y empuja la barra "hacia abajo sin doblar codos". Si la banda ligera no da 5, retrocede a banda media un ciclo y añade una serie.',
                'Seguridad N3: (1) las negativas cargan bíceps y codo: volumen bajo, control total, nunca al fallo excéntrico; (2) molestia en la cara interna del codo → reduce la negativa y sube el trabajo con banda; (3) dolor matinal de tendón >2/10 = congela esa escalera un ciclo; (4) ninguna negativa en frío: Bloque 0 completo siempre antes; (5) en step-up y split squat, la rodilla sigue al segundo dedo: el valgo bajo carga unilateral es la lesión silenciosa del nivel.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tres vías de dominada en dosis de aprendizaje', 'note' => 'Scapular pulls, negativas de 3-4 s, banda gruesa; diamond inclinada; L-sit una pierna', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Negativas de 5 s; banda media', 'note' => 'Diamond de rodillas → primera completa; tuck L-sit alterno', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Negativas de 5-6 s en series dobles; banda ligera (banda del test)', 'note' => 'Diamond completa en series; tuck L-sit 5-10 s; ensayos de test', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen: las negativas se reducen a 2×2 suaves', 'note' => 'Movilidad protagonista; test al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Negativa de dominada (5 s)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Bajada de 5 s continuos desde barbilla sobre barra; cada rep termina con control, nunca en desplome', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dominada con banda ligera', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a la barra, sin impulso de pierna ni balanceo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Scapular pull', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Codos totalmente rectos, rango escapular completo y control en la bajada', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Diamond push-up', 'minimum' => 6, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Manos en rombo bajo el esternón, pecho a las manos, codos pegados al tronco', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Tuck L-sit', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Parallettes/paralelas, ambos pies despegados, rodillas al pecho, brazos rectos', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            [
                'code' => 'v4-n3-primera-negativa-5s',
                'name' => 'Primera negativa de 5 s',
                'description' => 'Primera negativa de dominada con 5 s completos de freno en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🐢', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Negativa de dominada (5 s)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n3-5-dominadas-banda',
                'name' => '5 dominadas con banda',
                'description' => 'Supera el mínimo de dominada con banda ligera (5 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🎗️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominada con banda ligera',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n3-primer-scapular-pull',
                'name' => 'Primer scapular pull',
                'description' => 'Primer scapular pull con codos rectos y rango escapular completo.',
                'category' => 'skill', 'icon' => '🦅', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n3-primera-diamond',
                'name' => 'Primera diamond',
                'description' => 'Primera diamond push-up completa con pecho a las manos.',
                'category' => 'skill', 'icon' => '💎', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n3-primer-tuck-lsit',
                'name' => 'Primer tuck L-sit',
                'description' => 'Primer tuck L-sit con ambos pies despegados, aunque sea 2 s.',
                'category' => 'skill', 'icon' => '🪑', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n3-primer-split-squat',
                'name' => 'Primer split squat',
                'description' => 'Primer split squat libre con rodilla trasera rozando el suelo y control.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n3-primer-step-up',
                'name' => 'Primer step-up a cajón',
                'description' => 'Primer step-up completo sin impulso de la pierna trasera.',
                'category' => 'skill', 'icon' => '📦', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4 — LA PRIMERA DOMINADA
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): no persigas la dominada todavía: persigue negativas perfectas y bandas cada vez más ligeras. La dominada es una consecuencia, no un objetivo de sesión.',
                'Fase 2 (Progresión): los primeros intentos completos se hacen SIEMPRE frescos, al inicio de la sesión B, con descansos largos. Un intento fallido con buena salida enseña más que tres con banda de más.',
                'Fase 3 (Intensificación): anota tu primera serie de dominadas en frío-controlado: es tu referencia de test. No conviertas cada sesión en un test; la acumulación construye más que el máximo.',
                'Fase 4 (Recuperación): aprovecha para ganar movilidad: G1 y G2 se consolidan aquí, no en el pico de fuerza. Llega al test con las puertas ya verificadas en sesión.',
                'Si fallas el test: si la dominada no sale, el fallo casi nunca es de espalda: es de agarre o de salida (primeros 10 cm). Más scapular pulls y más colgado activo. Si el pike push-up falla, sube primero el rango de flexión de hombro (puerta G1) antes de añadir repeticiones. Si los fondos asistidos fallan en el fondo del recorrido, reduce profundidad antes que añadir banda.',
                'Seguridad N4: la primera dominada suele llegar antes en supino (chin-up): úsala de motivación, pero no abandones la vía prona. Las negativas cargan el tendón del bíceps y el codo: si aparece molestia en la cara interna del codo, reduce la negativa y sube el trabajo con banda. Nunca entrenes la dominada con el cuello o los codos doloridos (regla §1.7).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Dominadas con banda + negativas; puertas G1/G2 en Bloque 0', 'note' => 'Técnica y volumen base; G1/G2 con trabajo diario de Bloque 0', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primeros intentos de dominada/chin-up completa (singles con descansos largos)', 'note' => 'Fondos asistidos con menos ayuda', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de dominada completa; pike push-up al máximo rango', 'note' => 'Máxima especificidad; sin picos coincidentes de hombro (sesión A)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad protagonista', 'note' => 'Re-test informal de G1 y G2 antes del test de nivel', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Dominada estricta (pull-up)', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Agarre prono, desde colgado completo a barbilla sobre barra, sin kipping ni balanceo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Chin-up estricto', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Agarre supino, rango completo, extensión completa de codos abajo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Pike push-up', 'minimum' => 6, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Cadera alta, cabeza entre las manos, codos a 45°, rango completo si G1 superada', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Tuck L-sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Brazos rectos, hombros deprimidos, rodillas al pecho, sin balanceo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fondo asistido en paralelas (banda/pies)', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Hombro a la altura del codo como profundidad máxima, escápulas activas', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Puerta G1 — Flexión de hombro', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'De pie contra pared, espalda y lumbar pegadas, brazos tocan la pared sin arquear ni separar', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'G1'],
                        ['name' => 'Puerta G2 — Extensión de muñeca 90°', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Palma en suelo, dedos hacia rodillas, antebrazo vertical, 20 s sin dolor', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'G2'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            [
                'code' => 'v4-n4-primera-dominada',
                'name' => '¡PRIMERA DOMINADA!',
                'description' => 'Primera dominada estricta con barbilla sobre la barra y sin impulso. El gran hito del programa: celebración máxima.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominada estricta (pull-up)',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n4-primer-chin-up',
                'name' => 'Primer chin-up',
                'description' => 'Supera el mínimo de chin-up estricto (1 rep) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🦾', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Chin-up estricto',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n4-tuck-lsit-10s',
                'name' => 'Tuck L-sit 10 s',
                'description' => 'Supera el mínimo de tuck L-sit (10 s) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🪑', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Tuck L-sit',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n4-pike-push-up-6',
                'name' => 'Pike push-up 6 reps',
                'description' => 'Supera el mínimo de pike push-up (6 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🔺', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pike push-up',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n4-puerta-hombro-g1',
                'name' => 'Puerta de hombro superada (G1)',
                'description' => 'Flexión de hombro completa verificada: brazos tocan la pared sin arquear la lumbar.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta G1 — Flexión de hombro',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n4-munecas-de-acero-g2',
                'name' => 'Muñecas de acero (G2)',
                'description' => 'Extensión de muñeca 90° verificada: 20 s sin dolor con la palma en el suelo.',
                'category' => 'progress', 'icon' => '🤛', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta G2 — Extensión de muñeca 90°',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n4-primer-bulgarian',
                'name' => 'Primer Bulgarian split squat',
                'description' => 'Primera serie limpia de Bulgarian split squat: torso erguido, pelvis neutra, sin rebote. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5 — MUNDO INVERTIDO
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): aprende las salidas antes que los holds: pirueta en wall HS, rodada, bajada controlada del headstand. Quien sabe salir no tiene miedo, y quien no tiene miedo aprende el doble de rápido.',
                'Fase 2 (Progresión): en wall HS y headstand, la respiración es el skill oculto: si aguantas la respiración, el hold muere a los 15 s. Cuenta respiraciones, no segundos.',
                'Fase 3 (Intensificación): los holds máximos se hacen frescos, al inicio del bloque principal de la sesión D, nunca al final. Un hold de test con hombros fatigados miente.',
                'Fase 4 (Recuperación): la muñeca manda: si hay molestia matinal >2/10 en muñeca, congela las inversiones un ciclo (regla §1.7) y mantén solo el resto de familias.',
                'Si fallas el test: el wall HS falla por respiración y por miedo, no por hombros: practica holds más cortos con salidas limpias. Si los fondos fallan en el fondo del recorrido, reduce profundidad antes que añadir banda: el hombro agradece rangos ganados, no forzados. Si las dominadas se estancan en 2, revisa el agarre y la salida (scapular pull) antes de culpar al dorsal.',
                'Seguridad N5: ninguna inversión se entrena sin las puertas G1 y G2 superadas (test de N4) ni sin el Bloque 0 completo. El crow se aprende con almohadilla. El headstand carga el cuello solo en un 20-30%: si el cuello duele, esa sesión no hay headstand. German hang y skin the cat: nunca a sangre fría, siempre con pies apoyados disponibles.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Inversiones con holds cortos y muchas salidas limpias', 'note' => 'Fondos con banda ligera; volumen de dominadas acumulado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Holds más largos en wall HS/headstand; entran los fondos completos', 'note' => 'Frog stand y crow con más tiempo', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Picos de hold (wall HS 20-30 s, headstand 20-30 s); dominadas al máximo limpio', 'note' => 'Sin picos coincidentes de muñeca/hombro (sesión D)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Inversiones a mitad de tiempo y solo las ya dominadas', 'note' => 'Movilidad de muñeca protagonista', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Wall handstand hold (pecho a pared)', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Manos a 10-20 cm de la pared, cuerpo en línea sin bananeo, respiración continua', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Headstand (trípode)', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => '70-80% del peso en brazos/antebrazos, cuello neutro, salida controlada', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Crow pose', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Pies despegados, rodillas en la parte alta de los brazos, mirada al suelo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dominadas estrictas', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Rango completo, sin kipping, barbilla clara sobre la barra', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fondo completo en paralelas', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Hombro a la altura del codo, escápulas activas, sin balanceo de piernas', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            [
                'code' => 'v4-n5-primer-wall-handstand',
                'name' => 'Primer wall handstand 20 s',
                'description' => 'Supera el mínimo de wall handstand pecho a pared (20 s) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Wall handstand hold (pecho a pared)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-primer-headstand',
                'name' => 'Primer headstand 20 s',
                'description' => 'Supera el mínimo de headstand trípode (20 s) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🙃', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Headstand (trípode)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-primer-crow',
                'name' => 'Primer crow pose 10 s',
                'description' => 'Supera el mínimo de crow pose (10 s) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🐦‍⬛', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Crow pose',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-3-dominadas',
                'name' => '3 dominadas estrictas',
                'description' => 'Supera el mínimo de dominadas estrictas (3 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominadas estrictas',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-primer-fondo-completo',
                'name' => 'Primer fondo completo',
                'description' => 'Supera el mínimo de fondo completo en paralelas (5 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Fondo completo en paralelas',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-primer-frog-stand',
                'name' => 'Primer frog stand',
                'description' => 'Primer equilibrio sobre brazos con rodillas en los codos y pies despegados. Primer peldaño de la línea planche. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🐸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-skin-the-cat',
                'name' => 'Skin the cat con pies apoyados',
                'description' => 'Primera rotación completa entre anillas con los pies apoyados: primer peldaño real hacia la puerta G4. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🔄', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n5-primer-cossack',
                'name' => 'Primer cossack squat',
                'description' => 'Primera sentadilla lateral profunda con el talón pegado al suelo. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6 — PROYECCIÓN
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): el planche lean se gana con paciencia de milímetros: fija un ángulo que puedas sostener 15 s perfectos y vive ahí un ciclo antes de pedir más. El bíceps distal marca el ritmo, no el ánimo.',
                'Fase 2 (Progresión): en tuck FL, graba tu retroversión pélvica: la mayoría de los levers que "se caen" son levers con la pelvis en neutro. 5 s perfectos enseñan más que 10 s feos.',
                'Fase 3 (Intensificación): respeta la anti-coincidencia: el lean sube, la tuck planche mantiene. Si el bíceps amanece >2/10, congela la escalera de planche ese ciclo (regla §1.7) y sigue con tracción y pierna con normalidad.',
                'Fase 4 (Recuperación): las puertas G4 y G5 se consolidan aquí: German hang como movilidad (no como fuerza) y compresión sentada tranquila. Llega al test con ambas ya verificadas en sesión.',
                'Si fallas el test: si el tuck FL se desploma, el fallo suele ser la retracción (no el dorsal): más scapular pulls y holds de 5 s perfectos en lugar de 10 s feos. Si el ring support tiembla, vuelve a paralelas fijas y refuerza YTW. Si el lean/pseudo planche molesta en el bíceps distal, retrocede ángulo (nunca "aguantes"): es el tendón que más tiempo tarda en adaptarse de todo el nivel. Si el German hang pinza por delante, vuelve al skin the cat con pies apoyados un ciclo y sube dislocates.',
                'Seguridad N6: falso agarre, planche, German hang y skin the cat SOLO tras el Bloque 0 completo (regla §1.7). El German hang nunca se testea como primera exposición: es ejercicio de sesión durante todo el nivel. La compresión (G5) se trabaja sentado en el suelo, nunca forzando la lumbar: si se redondea, apoya más las manos y gana el rango en ciclos.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Planche lean con ángulo conservador; tuck FL con banda', 'note' => 'Anillas: support y rows; primer falso agarre (hito)', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Lean más profundo; tuck FL sin banda corto; false grip support', 'note' => 'Pistol asistida consolida; peldaños tuck planche suben', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Picos: lean 20-30 s, tuck FL 8-12 s, dominadas 5-8', 'note' => 'Peldaños tuck planche MANTIENEN (anti-coincidencia bíceps); G4/G5 al criterio de test', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Estáticos a mitad de tiempo con ángulo cómodo', 'note' => 'Movilidad de hombro y compresión protagonistas; re-test informal de G4 y G5', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Planche lean (brazos rectos)', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Hombros por delante de muñecas, codos extendidos sin hiperextender, protracción activa', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Tuck front lever', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Brazos rectos, retracción escapular, retroversión pélvica, espalda horizontal', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dominadas estrictas', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Rango completo, sin kipping, primera serie fresca como referencia', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Pseudo planche push-up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Manos junto a caderas, proyección constante, pecho entre las manos', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Ring support hold (giro externo al final)', 'minimum' => 15, 'target' => 25, 'unit' => 'segundos', 'form' => 'Brazos rectos, hombros deprimidos, turnout firme al final del hold', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Puerta G4 — Extensión de hombro', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'German hang asistido (anillas, pies apoyados) 15 s sin dolor ni pinzamiento', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'G4'],
                        ['name' => 'Puerta G5 — Compresión de cadera', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Sentado, elevar ambas piernas extendidas 10 s, manos solo apoyo ligero, lumbar neutra', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'G5'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            [
                'code' => 'v4-n6-tuck-front-lever',
                'name' => 'TUCK FRONT LEVER',
                'description' => 'Primer tuck front lever limpio de 8 s: brazos rectos, retracción y retroversión. El primer lever del programa.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Tuck front lever',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n6-primer-planche-lean',
                'name' => 'Primer planche lean 20 s',
                'description' => 'Supera el mínimo de planche lean (20 s) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '📐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Planche lean (brazos rectos)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-5-dominadas',
                'name' => '5 dominadas estrictas',
                'description' => 'Supera el mínimo de dominadas estrictas (5 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominadas estrictas',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-primer-ring-support',
                'name' => 'Primer ring support 15 s',
                'description' => 'Supera el mínimo de ring support hold con giro externo (15 s) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '💍', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Ring support hold (giro externo al final)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-primer-pseudo-planche',
                'name' => 'Primer pseudo planche push-up',
                'description' => 'Supera el mínimo de pseudo planche push-up (5 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pseudo planche push-up',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-hombro-libre-g4',
                'name' => 'Hombro libre (G4)',
                'description' => 'Puerta de extensión de hombro superada: German hang asistido 15 s sin dolor.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta G4 — Extensión de hombro',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-compresion-g5',
                'name' => 'Compresión de acero (G5)',
                'description' => 'Puerta de compresión de cadera superada: elevación de piernas extendidas sentado 10 s.',
                'category' => 'progress', 'icon' => '🧘', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta G5 — Compresión de cadera',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-tuck-planche-banda',
                'name' => 'Tuck planche asistida 10 s',
                'description' => 'Tuck planche con banda sostenida 10 s: último peldaño antes de la tuck planche libre. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🛡️', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-primer-falso-agarre',
                'name' => 'Primer falso agarre',
                'description' => 'Primera false grip ring row o false grip support con la muñeca en posición correcta: inicio de la escalera del muscle-up. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤝', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n6-pistol-asistida',
                'name' => 'Pistol asistida 3/pierna',
                'description' => 'Tres pistol squats asistidas limpias por pierna con apoyo mínimo. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // NOTAS TÉCNICAS POR EJERCICIO (fichas del documento de detalle)
    // ========================================================================

    /** Notas de los ejercicios principales de N1. */
    private const EXERCISE_NOTES_L1 = [
        'Wall Push Up' => 'Cuerpo en línea de cabeza a talones; codos a ~45°; el pecho se acerca a la pared sin adelantar la cadera.',
        'Incline Push Up' => 'Manos bajo hombros; abdomen firme; baja el pecho hasta rozar el borde del apoyo.',
        'Plank' => 'Antebrazos en suelo; costillas abajo; glúteo apretado; respira sin soltar la tensión.',
        'Australian Pull Up' => 'Fila horizontal: cuerpo rígido en línea, pecho a la barra, codos a 45°; baja controlado. La inclinación marca el peldaño del nivel.',
        'Dead Hang' => 'Agarre completo, pulgares cerrados; respira; hombros lejos de las orejas si es cómodo. Baja antes de que el agarre falle del todo.',
        'Active Hang' => 'Sin doblar codos: "crece" alejando los hombros de las orejas y vuelve con control.',
        'Box Squat' => 'Toca el cajón sin desplomarte ni relajarte; sube empujando el suelo con todo el pie.',
        'Air Squat' => 'Talón pegado; profundidad cómoda; rodillas siguen la punta de los pies.',
        'Glute Bridge' => 'Pausa 1 s arriba; sube con glúteo, no con lumbar.',
        'Glute March' => 'Cadera alta y nivelada mientras alternas rodillas.',
        'Single Leg Glute Bridge' => 'Versión asistida: un pie apenas apoya; cadera nivelada.',
        'Clamshell' => 'Pies juntos, pelvis quieta, abre la rodilla sin rodar la cadera.',
        'Band Monster Walk' => 'Rodillas semiflexionadas, banda tensa todo el recorrido.',
        'Side Plank' => 'Versión de rodillas: cadera alta, línea hombro-cadera-rodilla.',
        'Dead Bug' => 'Lumbar pegada al suelo; exhala al extender.',
        'Bird Dog' => 'Brazo y pierna contrarios; pelvis quieta.',
    ];

    /** Notas de los ejercicios principales de N2. */
    private const EXERCISE_NOTES_L2 = [
        'Knee Push Up' => 'Línea hombro-cadera-rodilla; codos a 45°; pecho al suelo en cada rep.',
        'Tempo Push Up' => 'Flexión excéntrica en suelo: baja en 4-6 s continuos con línea perfecta; sube de rodillas si es preciso. El freno construye el fondo.',
        'Standard Push Up' => 'Pecho al suelo, codos a 45°, extensión completa arriba, cadera sin hundir ni picar.',
        'Support Hold' => 'Hombros deprimidos ("hazte alto"), codos extendidos, brazos verticales, cuerpo quieto.',
        'Hollow Body Hold' => 'Lumbar sellada al suelo en todo momento; progresión: tuck → brazos abajo → brazos arriba. Dolor lumbar = retrocede un peldaño.',
        'Dumbbell Romanian Deadlift' => 'Bisagra de cadera: cadera atrás, espalda neutra, isquios tensos; la carga viaja cerca del cuerpo. Ligero significa ligero.',
        'Kneeling Hip Flexor Stretch' => 'Aprieta el glúteo del lado trasero y retrovierte la pelvis; el estiramiento se siente delante de la cadera, no en la lumbar.',
        'Couch Stretch' => 'Retroversión pélvica activa: sin ella solo se tensa la lumbar.',
        'Deep Squat' => 'Cadera por debajo de la rodilla con talón pegado; si se levanta, reduce rango y trabaja tobillo en el Bloque 0.',
        'Standing Calf Raise' => 'Subida 1 s, bajada 2 s, rango completo, sin rebote.',
        'Single Leg Calf Raise' => 'Apoyo de pared si es preciso; rango completo en cada rep.',
        'Wrist Push-Up' => 'Apoyo dorsal completo o transición palma-dorso; dosificación conservadora, nunca al fallo.',
        'Australian Pull Up' => 'Fila baja: cuerpo casi horizontal, escápulas inician el tirón, pecho a la barra en cada rep; si no llega, sube un punto la inclinación.',
    ];

    /** Notas de los ejercicios principales de N3. */
    private const EXERCISE_NOTES_L3 = [
        'Diamond Push Up' => 'Manos formando rombo bajo el esternón; codos pegados al tronco; pecho a las manos.',
        'Pike Push Up' => 'Cadera alta, cabeza entre los brazos hacia el suelo; codos hacia atrás ~45°; rango creciente con los ciclos.',
        'Scapular Pull Up' => 'Codos totalmente rectos: "empuja la barra hacia abajo sin doblar codos"; rango escapular completo y control en la bajada.',
        'Negative Pull Up' => 'Sube con salto/cajón a barbilla sobre la barra; baja continuos sin tramos de caída; nunca al fallo excéntrico. Ninguna negativa en frío.',
        'Band Assisted Pull Up' => 'Banda al pie/rodilla; rango completo (de extensión a barbilla sobre barra); progresión gruesa → media → ligera.',
        'Chin Up Hold' => 'Salta a la posición alta y sostén: barbilla sobre la barra todo el tiempo, pecho cerca, escápulas activas.',
        'Tuck L-Sit' => 'Brazos rectos, hombros deprimidos, rodillas al pecho, cero balanceo; progresión: una pierna → alterno → ambas.',
        'Stationary Lunge' => 'Split squat: tronco erguido, rodilla trasera baja vertical hasta rozar el suelo; rodilla delantera alineada al segundo dedo.',
        'Step Up' => 'Sube empujando solo con la pierna del cajón, sin impulso de la trasera; bajada controlada.',
        'Ring Row - Elevated Feet' => 'Pies sobre cajón; pecho a las anillas; escápulas inician el tirón. Contrapeso permanente del empuje.',
        'Ring Row - Feet on Ground' => 'Cuerpo inclinado; pecho a las anillas; escápulas inician.',
        'Shoulder Dislocate' => 'Agarre amplio con banda o palo; arco completo sin doblar codos ni dolor.',
        'Reverse Plank' => 'Cadera alta, empuja el suelo; extensión de hombro activa.',
        'Active Straight Leg Raise' => 'Pierna extendida, sube sin despegar la lumbar; rango activo, no impulso.',
        'Seated Calf Raise' => 'Sóleo con rodilla flexionada: el que trabaja en el fondo del pistol.',
        'Knuckle Push-Up' => 'Puños cerrados (nudillos de índice y medio cargan), muñeca recta en línea con el antebrazo; nunca al fallo.',
    ];

    /** Notas de los ejercicios principales de N4. */
    private const EXERCISE_NOTES_L4 = [
        'Strict Pull Up' => 'Agarre prono algo más abierto que hombros; escápulas inician el tirón; barbilla clara sobre la barra; bajada controlada en 2-3 s. Sin kipping ni balanceo.',
        'Chin Up' => 'Agarre supino a la anchura de hombros; rango completo con extensión total abajo; suele salir antes que la prona: celébralo, pero el test exige la dominada.',
        'Assisted Pull Up - Supino' => 'Chin-up con banda al pie/rodilla; rango completo sin impulso; suele ser la primera vía a la tracción completa.',
        'Band Assisted Pull Up' => 'Un escalón más ligera que en N3; salida desde escápulas activas, sin impulso de piernas; progresión hasta la banda ligera del test.',
        'Negative Pull Up' => 'Negativas de 5-8 s (hasta 10 s en Intensificación); salta a barbilla sobre barra; nunca al fallo excéntrico ni en frío.',
        'Scapular Pull Up' => 'Brazos rectos: solo se mueven las escápulas; la pausa de 2 s arriba enseña la posición de salida de la dominada.',
        'Pike Push Up' => 'Cadera alta, cabeza entre las manos formando triángulo; codos a 45°; rango completo solo con la puerta G1 superada.',
        'Assisted Dips' => 'Pies apoyados delante o banda; hombro a la altura del codo como profundidad máxima; escápulas activas. Progresa reduciendo asistencia, nunca reps y asistencia a la vez.',
        'Bulgarian Split Squat' => 'Pie trasero elevado; torso erguido, pelvis neutra, rodilla alineada al segundo dedo; sin rebote en el fondo. Primera serie limpia = logro.',
        'High Step Up' => 'Sube empujando solo con la pierna del cajón alto, sin impulso de la de abajo; bajada controlada.',
        'Tuck L-Sit' => 'Brazos rectos, hombros deprimidos, rodillas al pecho; el hold solo cuenta limpio: 10 s limpios valen más que 12 temblorosos.',
        'Explosive Push Up' => 'Flexión explosiva a media altura (manos apenas despegan); potencia suave sin fatiga: preparación lejana del clap push-up.',
        'Neck Isometric Hold' => 'Empuja la cabeza contra tu propia mano al 50-70% sin moverla, en 4 direcciones; sin dolor. Preparación directa del headstand de N5.',
        'Seated Leg Raise' => 'Elevaciones de piernas sentado (tuck): trabajo de flexores de cadera con las manos como apoyo y la espalda erguida.',
        'Wrist Extension Stretch' => 'Palma en suelo con dedos hacia las rodillas, antebrazo vertical; extensión progresiva 20 s sin dolor (puerta G2).',
        'Scapular Push Up' => 'En plancha, hunde el pecho juntando las escápulas y empuja separándolas, sin flexionar los codos.',
    ];

    /** Notas de los ejercicios principales de N5. */
    private const EXERCISE_NOTES_L5 = [
        'Chest to Wall Handstand' => 'Manos a 10-20 cm de la pared, dedos abiertos; empuja el suelo, costillas abajo, sin bananeo; salida SIEMPRE controlada (pirueta a ambos lados).',
        'Tripod Headstand' => 'Coronilla y manos en triángulo equilátero; 70-80% del peso en brazos/antebrazos, nunca en la cabeza; dolor cervical = retrocede un peldaño.',
        'Crow Stand' => 'Rodillas en la parte alta de los brazos, codos como estante; frente cerca del suelo y almohadilla delante: caer hacia delante es parte del aprendizaje.',
        'Frog Stand' => 'Rodillas en la cara externa de los codos, pies despegados, protracción escapular activa; primer peldaño de la línea planche (§1.6).',
        'Parallel Bar Dips' => 'Hombro a la altura del codo, no más abajo en este nivel; escápulas activas; si falla en el fondo, reduce profundidad antes que añadir banda.',
        'Skin The Cat' => 'Anillas bajas, rotación lenta con los pies apoyados en todo momento; nunca a sangre fría; primer peldaño real de la puerta G4.',
        'German Hang' => 'Asistido con pies apoyados, 10-15 s; estiramiento intenso sin dolor ni pinzamiento; regula la carga con los pies, nunca en frío.',
        'Cossack Squat' => 'Sentadilla lateral profunda; el talón de la pierna que carga permanece PEGADO al suelo; si se levanta, menos rango y más tobillo en Bloque 0.',
        'Archer Ring Row' => 'Un brazo tira y el otro se extiende al lado como arquero; cuerpo en línea sin rotar; contrapeso horizontal pesado permanente.',
        'Weighted Ring Row' => 'Fila horizontal con mochila lastrada y pies elevados; pecho a las anillas con pausa de 1 s; la carga sube solo cuando las 12 reps salen limpias.',
        'Fingertip Hold' => 'Plancha apoyando solo las yemas (de rodillas si hace falta); las cinco yemas cargan parejo; prepara el salvado con dedos del handstand.',
        'Back-of-Hand Stretch' => 'Dorso de la mano en el suelo, dedos hacia ti; estiramiento leve-moderado de 20-30 s, nunca dolor.',
        'Goblet Squat' => 'Sentadilla profunda con mochila al pecho; talones pegados y pecho alto; mantenimiento bilateral, nunca de test.',
        'Hip Thrust' => 'Omóplatos en el banco y mochila sobre la cadera; extensión completa con pausa de 1-2 s arriba sin hiperextender la lumbar.',
        'Strict Pull Up' => 'Acumula 10-15 reps limpias por sesión; la primera serie fresca al máximo limpio es la referencia del test (3-5 reps).',
    ];

    /** Notas de los ejercicios principales de N6. */
    private const EXERCISE_NOTES_L6 = [
        'Planche Lean' => 'Hombros por delante de las muñecas, codos extendidos sin hiperextender, protracción activa; progresión de milímetros: molestia en bíceps distal = retrocede ángulo.',
        'Pseudo Planche Push Up' => 'Manos junto a las caderas, dedos hacia los pies o fuera; proyección constante, pecho entre las manos; mismo tendón que el lean: no compiten por pico.',
        'Tuck Front Lever' => 'Brazos rectos, retracción escapular y retroversión pélvica: sin retroversión el lever no existe; 5 s perfectos valen más que 10 s feos.',
        'Ring Support Hold' => 'Brazos rectos, hombros deprimidos; las anillas quieren abrirse: tú decides; termina cada hold con giro externo (turnout) firme.',
        'Ring Push Up' => 'Pecho entre las anillas sin que se abran, codos a 45°; la inestabilidad se controla con manguito, no con temblor.',
        'False Grip Ring Row' => 'Muñeca SOBRE la anilla con el antebrazo apoyado; nunca en frío; molestia en la cara anterior de la muñeca = reduce volumen ese ciclo.',
        'False Grip Support Hold' => 'Apoyo en anillas con el falso agarre intacto 5-12 s; baja antes de que el agarre se degrade: no entrenes el fallo.',
        'Tuck Planche Toe Support' => 'Rodillas al pecho, brazos rectos, solo las puntas de los pies equilibran; si los dedos se clavan, hay demasiado peso atrás.',
        'Band Assisted Tuck Planche' => 'Banda por la cadera que asiste pero no sostiene; 10 s limpios = hito del nivel; nunca coincida su intensificación con el pico de planche lean.',
        'Assisted Pistol Squat' => 'El apoyo es solo para el equilibrio, no para colgarse; bajada de 3 s con el talón pegado; hito a 3 reps limpias por pierna.',
        'Single Leg Romanian Deadlift' => 'Bisagra a una pierna: cadera cuadrada, espalda neutra, el rango lo marca el isquiotibial, no la lumbar.',
        'Prone Y Raise' => 'Boca abajo, brazos en Y con pulgares arriba y carga muy ligera; pausa de 2-3 s arriba; seguro anti-temblor del ring support.',
        'Seated Leg Raise' => 'Compresión sentada con piernas extendidas y manos como apoyo ligero; lumbar neutra: pista directa de la puerta G5 (10 s).',
        'Wrist Curl' => 'Antebrazo apoyado, palma arriba, carga muy ligera (0,5-3 kg); rango completo sin rebote; base de flexores del falso agarre.',
        'Reverse Wrist Curl' => 'Palma abajo y misma dosis que el curl; equilibra flexores/extensores y prepara la muñeca en extensión bajo carga.',
        'Fingertip Push Up' => 'Flexión sobre las yemas (de rodillas al inicio); escalón de fuerza de muñeca del nivel; dosificación conservadora, nunca al fallo.',
    ];

    /** Notas genéricas de movilidad y descarga (comunes a todos los niveles). */
    private const GENERIC_EXERCISE_NOTES = [
        'Active March' => 'Marcha suave elevando rodillas: activación general.',
        'Arm Circles' => 'Círculos controlados de brazos, ambos sentidos.',
        'Hip Circles' => 'Círculos amplios de cadera, ambos sentidos.',
        'Wrist Prayer' => 'Mecido suave hacia extensión de muñeca; estiramiento leve-moderado, nunca dolor.',
        'Knee Wrist Push-Up' => 'Apoyo dorsal progresivo de la muñeca; nunca al fallo; duele = retrocede a oraciones.',
        'Cat-Camel' => 'Movimiento lento, vértebra a vértebra, coordinado con la respiración.',
        'Foam Roll Thoracic Extension' => 'Rodillo bajo los omóplatos; deja caer cabeza y pecho; nunca sobre la lumbar baja.',
        'Open Books' => 'Las rodillas no se mueven; sigue la mano con la mirada; exhala al abrir.',
        'Knee-to-Wall Rocks' => 'Rodilla a la pared con el talón siempre pegado; mide la distancia para registrar tu progreso.',
        'Calf Stretch Wall' => 'Pierna trasera extendida, talón pegado, tensión leve-moderada.',
        'Bent-Knee Soleus Stretch' => 'Rodilla trasera flexionada, talón siempre en el suelo; tirantez, nunca pinchazo en el Aquiles.',
        'Kneeling Sit' => 'Tronco erguido sentado sobre los talones; ante dolor de rodilla, coloca un cojín y reduce el tiempo.',
        'Wall Slide' => 'Espalda y lumbar pegadas a la pared; sube los brazos sin despegar.',
        'Band Pull-Apart' => 'Brazos rectos, abre hasta tocar el pecho con la banda, escápulas juntas.',
        'Face Pull' => 'Tira hacia la frente, codos altos, rota al final.',
        'External Rotation' => 'Codo pegado al cuerpo, giro lento.',
        'YTW' => 'Tumbado prono, pulgares arriba, hombros lejos de las orejas; una serie por letra.',
        'Arch Hold' => 'Costillas abajo: la extensión nace de la torácica, no de la lumbar. Dolor lumbar = baja y reduce el rango.',
        'Seated Pike Stretch' => 'Piernas extendidas, alarga desde la cadera; sensación de tensión en isquios, no en lumbar.',
        'Chest Doorway Stretch' => 'Codo a 90° en el marco, giro suave del tronco.',
        'Standing Quad Stretch' => 'Pelvis neutra, rodilla apuntando al suelo.',
        'Lat Stretch' => 'Alarga el costado sin rotar el tronco.',
        'Diaphragmatic Breathing' => 'Inhala por la nariz expandiendo costillas a los lados y atrás; exhala por la boca dejándolas caer.',
        "Child's Pose" => 'Respiración lateral hacia la espalda baja; relaja hombros y frente.',
    ];
}
