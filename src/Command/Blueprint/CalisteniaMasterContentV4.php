<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Calistenia Master v4.0 (niveles 1-16).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_niveles_01-03.md,
 * docs/detalle_niveles_04-06.md, docs/detalle_niveles_07-09.md,
 * docs/detalle_niveles_10-12.md, docs/detalle_niveles_13-15_olimpo.md
 * y de docs/arquitectura_niveles_calistenia.md.
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
            7 => self::level7(),
            8 => self::level8(),
            9 => self::level9(),
            10 => self::level10(),
            11 => self::level11(),
            12 => self::level12(),
            13 => self::level13(),
            14 => self::level14(),
            15 => self::level15(),
            16 => self::level16(),
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
            9 => self::achievementsL9(),
            10 => self::achievementsL10(),
            11 => self::achievementsL11(),
            12 => self::achievementsL12(),
            13 => self::achievementsL13(),
            14 => self::achievementsL14(),
            15 => self::achievementsL15(),
            16 => self::achievementsL16(),
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
            7 => self::EXERCISE_NOTES_L7,
            8 => self::EXERCISE_NOTES_L8,
            9 => self::EXERCISE_NOTES_L9,
            10 => self::EXERCISE_NOTES_L10,
            11 => self::EXERCISE_NOTES_L11,
            12 => self::EXERCISE_NOTES_L12,
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
    // LEVEL 7 — TUCK Y EXTENSIÓN
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): graba tus holds de tuck planche y adv tuck FL; en brazo recto la forma se degrada antes que la fuerza y el vídeo es el único juez honesto. Acumula tiempo bajo tensión con asistencia generosa: los tendones aprenden por exposición, no por heroicas.',
                'Fase 2 (Progresión): los primeros intentos libres de tuck planche y L-sit serán de 1-3 s. Es lo esperado: cada intento corto y perfecto vale más que uno largo y roto. Descansa de verdad los 2-4 min.',
                'Fase 3 (Intensificación): respeta la anti-coincidencia: no intentes récord de adv tuck FL el mismo microciclo que el récord de tuck planche. El bíceps distal solo admite un pico.',
                'Fase 4 (Recuperación): no conviertas el deload en un test encubierto. La movilidad de esta fase (Jefferson curl, pike) es tu seguro para la puerta G6 de N9.',
                'Regla de sobrecarga entre ciclos: al repetir un ciclo, la Fase 1 no debe ser una fotocopia del ciclo anterior: progrésala ligeramente (+1 rep por serie en el trabajo dinámico, +2-3 s en los holds, o un peldaño menos de asistencia) manteniendo la técnica limpia. Las Fases 2-4 heredan ese pequeño avance.',
                'Si fallas el test: (a) tuck planche <5 s: retrocede un peldaño §1.6 (banda o puntas) y acumula tiempo allí — el tendón del bíceps marca el ritmo, no el ánimo. (b) L-sit que no despega: no es core, son flexores de cadera y tríceps de apoyo: más compresión sentada y más support hold. (c) Adv tuck FL que falla: revisa la retroversión pélvica — sin ella el lever no existe. (d) German hang con pinzamiento: vuelve al asistido y a los dislocates un ciclo; nunca "empujes" a través del pinzamiento.',
                'Seguridad N7: skin the cat y German hang siempre con anillas a altura de escape y tras el Bloque 0 completo; la tuck planche pesada (A) y el adv tuck FL pesado (B) nunca en sesiones consecutivas — intercala la sesión C o un descanso entre ambas; dolor matinal de tendón >2/10 = congelar esa escalera un ciclo (§1.7) y seguir el resto con normalidad.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Volumen técnico; peldaños con asistencia generosa', 'note' => 'Tuck planche con puntas, tuck FL espalda plana, L-sit en tuck avanzado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Suben holds y reps; entran los intentos libres de tuck planche y L-sit', 'note' => 'Hitos: pistol al cajón alto 5/pierna, single-leg RDL, primer clap push-up', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: tuck planche + L-sit + German hang', 'note' => 'Adv tuck FL consolida (anti-coincidencia de bíceps); hito German hang 20 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Deload; movilidad protagonista (Jefferson curl, pike)', 'note' => 'Re-verificación G4/G5 y test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Tuck planche', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Brazos rectos, proyección escapular máxima, rodillas al pecho, cadera a la altura de los hombros (gate conservador §1.6)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Advanced tuck front lever', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Espalda paralela al suelo en tuck, retroversión pélvica activa, brazos rectos', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'L-sit completo', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Brazos rectos, hombros deprimidos, piernas extendidas y juntas sin tocar el suelo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Skin the cat', 'minimum' => 2, 'target' => 4, 'unit' => 'repeticiones', 'form' => 'Anillas, ida y vuelta controladas = 1 rep; paso lento por el punto de máxima extensión', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'German hang libre', 'minimum' => 10, 'target' => 20, 'unit' => 'segundos', 'form' => 'Pies libres, respiración continua, hombros relajados, cero pinzamiento anterior', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Ring push-up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Cuerpo en línea, pecho a la altura de las anillas, turnout claro arriba', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            [
                'code' => 'v4-n7-tuck-planche',
                'name' => 'TUCK PLANCHE',
                'description' => 'Tuck planche libre de 5 s limpios: brazos rectos, proyección máxima, rodillas al pecho. El primer estático de brazo recto en apoyo del programa.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Tuck planche',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n7-l-sit',
                'name' => '¡L-SIT!',
                'description' => 'L-sit completo de 5 s: piernas extendidas y juntas, brazos rectos. La compresión de la puerta G5 convertida en skill.',
                'category' => 'skill', 'icon' => '🪑', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'L-sit completo',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n7-adv-tuck-fl',
                'name' => 'Advanced tuck FL',
                'description' => 'Supera el mínimo de advanced tuck front lever (8 s) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🛡️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Advanced tuck front lever',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-primer-skin-the-cat',
                'name' => 'Primer skin the cat',
                'description' => 'Supera el mínimo de skin the cat (2 reps controladas) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🔄', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Skin the cat',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-german-hang-10s',
                'name' => 'German hang libre 10 s',
                'description' => 'Supera el mínimo de German hang libre (10 s) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🧗', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'German hang libre',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-tuck-planche-puntas',
                'name' => 'Tuck planche con apoyo de puntas',
                'description' => 'Tuck planche con apoyo de puntas de pies: brazos rectos y proyección máxima, las puntas solo equilibran. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🐸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-german-hang-20s',
                'name' => 'German hang libre 20 s',
                'description' => 'German hang libre de 20 s con respiración continua: consolida el objetivo del test. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-primer-clap-push-up',
                'name' => 'Primer clap push-up',
                'description' => 'Primera flexión con palmada: despegue claro de ambas manos y aterrizaje suave con los codos amortiguando. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '👏', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-pike-push-up-elevado',
                'name' => 'Pike push-up elevado (pies en cajón)',
                'description' => 'Primera serie limpia de pike push-up con los pies en cajón: continuidad de la línea vertical hacia el HSPU de N10. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🔺', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-pistol-cajon-alto',
                'name' => 'Pistol al cajón alto 5/pierna',
                'description' => 'Cinco pistol al cajón alto limpias por pierna: tocar el cajón sin desplomarse y subir sin impulso. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n7-single-leg-rdl',
                'name' => 'Single-leg RDL',
                'description' => 'Single-leg RDL con carga y técnica limpia: cadera cuadrada y rango sin redondear la lumbar. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⚖️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8 — POTENCIA
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): la potencia se construye con descansos largos y pocas reps: si tus C2B se convierten en dominadas normales, la serie ha terminado. La velocidad es el objetivo, no el agotamiento.',
                'Fase 2 (Progresión): respeta el orden de picos: es el ciclo del ring dip; el SBD aún es técnico. Quien intensifica los dos a la vez se gana una molestia de hombro anterior, no un test superado.',
                'Fase 3 (Intensificación): fase del C2B y de la rotación SBD/adv tuck planche: un solo pico de codo/empuje por ciclo (impares = planche, pares = SBD, modelo N14). El ring dip, el archer y el tuck BL se mantienen sin vergüenza: mantener también es entrenar. Revisa el tendón del bíceps cada mañana (>2/10 = congelar esa escalera, §1.7).',
                'Fase 4 (Recuperación): la puerta G3 se gana aquí tanto como en las fases duras: knee-to-wall medido en cada sesión D. Si estás en 8-9 cm, repite un ciclo corto de movilidad de tobillo antes de presentarte al test.',
                'Regla de sobrecarga entre ciclos: al repetir un ciclo, la Fase 1 no debe ser una fotocopia del ciclo anterior: progrésala ligeramente (+1 rep por serie en el trabajo dinámico, +2-3 s en los holds, o un peldaño menos de asistencia) manteniendo la técnica limpia. Las Fases 2-4 heredan ese pequeño avance.',
                'Si fallas el test: (a) C2B que no llega: el problema es velocidad de salida, no fuerza máxima — dominadas explosivas con menos reps y más descanso. (b) Adv tuck planche <5 s: retrocede un peldaño §1.6 (banda o puntas con cadera abierta) y acumula holds de calidad. (c) Archer torcido: baja el volumen y usa banda — el archer mal hecho enseña asimetrías que cuestan ciclos corregir. (d) Tuck BL con pinzamiento: vuelve al German hang y a los dislocates; la puerta G4 está ganada, úsala. (e) G3 no superada: ciclo corto de movilidad de tobillo, no de fuerza.',
                'Seguridad N8: el chest-to-bar es el primer gesto balístico del programa: nunca con fatiga acumulada de codos. Profundidad de fondos limitada (hombro al nivel del codo) durante todo el nivel: el rango completo llega en N9, cuando el hombro está listo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Técnica de los gestos nuevos; ring dip protagonista en A', 'note' => 'SBD solo técnico; explosivas a media altura; archer asistido', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Suben C2B, ring dip y tuck BL; archer con menos asistencia', 'note' => 'Hitos: dips explosivos, false grip hang 10-15 s, pistol cajón bajo + excéntrica, dragon flag excéntrico', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico de C2B + rotación por ciclo del pico de codo/empuje (modelo N14)', 'note' => 'Ciclos impares pica la adv tuck planche, ciclos pares el SBD; ring dip, archer y tuck BL mantienen', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Deload; movilidad protagonista (straddle + tobillo)', 'note' => 'Test de nivel + puerta G3 al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Chest-to-bar pull-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Contacto real pecho-barra (no cuello), sin pataleo ni kipping, bajada controlada', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Advanced tuck planche', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Espalda paralela al suelo, brazos rectos, rodillas fuera del pecho (gate conservador §1.6)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Tuck back lever', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal boca abajo, brazos rectos pegados al cuerpo, cero pinzamiento anterior', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Ring dip', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Profundidad hombro a la altura del codo (nunca más abajo en este nivel), turnout claro arriba', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Straight bar dip', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Rango seguro: hombro a la altura del codo; cuerpo rígido, transición de peso por delante de la barra', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Archer pull-up', 'minimum' => 2, 'target' => 3, 'unit' => 'repeticiones/lado', 'form' => 'El brazo recto guía pero no empuja; reps simétricas a ambos lados', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Puerta G3 — Dorsiflexión de tobillo', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Knee-to-wall ≥10 cm sin despegar el talón (ideal 12 cm, diferencia entre lados <2 cm)', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'G3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            [
                'code' => 'v4-n8-chest-to-bar',
                'name' => 'CHEST-TO-BAR',
                'description' => 'Primer chest-to-bar estricto en el test: el pecho toca la barra, no solo la barbilla la supera. El primer gesto balístico del programa.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Chest-to-bar pull-up',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n8-adv-tuck-planche',
                'name' => 'Advanced tuck planche',
                'description' => 'Supera el mínimo de advanced tuck planche (5 s) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '📐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Advanced tuck planche',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-tuck-back-lever',
                'name' => 'Tuck back lever',
                'description' => 'Supera el mínimo de tuck back lever (8 s) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🛡️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Tuck back lever',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-primer-ring-dip',
                'name' => 'Primer ring dip',
                'description' => 'Supera el mínimo de ring dip (5 reps) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '💍', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Ring dip',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-primer-archer-pull-up',
                'name' => 'Primer archer pull-up',
                'description' => 'Supera el mínimo de archer pull-up (2 reps/lado) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🏹', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Archer pull-up',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-tobillo-libre-g3',
                'name' => 'Tobillo libre (G3)',
                'description' => 'Puerta de dorsiflexión superada: knee-to-wall ≥10 cm sin despegar el talón. Desbloquea el pistol de N9.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta G3 — Dorsiflexión de tobillo',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-dips-explosivos',
                'name' => 'Dips explosivos',
                'description' => 'Fondos en paralelas con subida balística y bajada controlada (despegue de manos opcional). Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '💥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-false-grip-hang',
                'name' => 'False grip hang 10-15 s',
                'description' => 'Colgado en falso agarre de 10-15 s con la muñeca sobre la anilla: la llave de los skills de anillas. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤝', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-pistol-cajon-bajo-excentrica',
                'name' => 'Pistol al cajón bajo + excéntrica 5 s',
                'description' => 'Hito doble: pistol al cajón bajo y excéntrica de 5 s controlada por pierna. Último peldaño antes del pistol libre. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-adv-tuck-fl-10s',
                'name' => 'Adv tuck front lever 10-12 s',
                'description' => 'Advanced tuck front lever de 10-12 s: consolida el objetivo de N7 y mantiene viva la línea FL antes del one-leg de N9. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-wall-handstand-40s',
                'name' => 'Wall handstand 30-40 s',
                'description' => 'Wall handstand de 30-40 s: consolida el objetivo de N5 y mantiene viva la línea vertical. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n8-dragon-flag-excentrico',
                'name' => 'Tuck dragon flag / dragon flag excéntrico',
                'description' => 'Primer peldaño del dragon flag: versión tuck y bajada excéntrica de 3-4 s sin colapsar la lumbar. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🐉', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 9 — MUSCLE-UP
    // ========================================================================

    private static function level9(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): el muscle-up se gana en las piezas, no en los intentos. Un ciclo entero de C2B alto + transición + SBD vale más que cincuenta intentos fallidos: cada intento fallido con kipping desesperado entrena el patrón equivocado y castiga el codo.',
                'Fase 2 (Progresión): cuando lleguen los primeros intentos completos, limítalos (6×1, descanso largo). El MU aparece "de repente", en una sesión cualquiera, para quien acumuló piezas con paciencia. Igual con el pistol: el primer rep libre suele salir tras el apoyo de un dedo, no desde cero.',
                'Fase 3 (Intensificación): respeta la anti-coincidencia: el pico de codo es del MU; el SBD se mantiene aunque te sientas fuerte. Si el codo amanece cargado (>2/10), congela la escalera del MU ese microciclo y trabaja pierna y FL (§1.7).',
                'Fase 4 (Recuperación): ensaya las puertas tal como se evaluarán (G6, G1, G2). La G6 se construye desde N3: si falla, no es falta de esfuerzo, es falta de exposición — ciclo corto de isquiosurales, no de fuerza.',
                'Regla de sobrecarga entre ciclos: al repetir un ciclo, la Fase 1 no debe ser una fotocopia del ciclo anterior: progrésala ligeramente (+1 rep por serie en el trabajo dinámico, +2-3 s en los holds, o un peldaño menos de asistencia) manteniendo la técnica limpia. Las Fases 2-4 heredan ese pequeño avance.',
                'Si fallas el test: (a) MU que no sale: casi siempre muere en la transición (el pecho no supera la barra) — más C2B altos y transiciones asistidas con banda, no más intentos completos. (b) Pistol que no sale: tres sospechosos — (1) dorsiflexión (vuelve al trabajo de tobillo y reintenta G3; mientras, cajón bajo), (2) fuerza de cuádriceps unilateral (Bulgarian pesado y step-up alto), (3) equilibrio/control (pistol asistida y excéntrica de 5 s). (c) One-leg FL que cae: revisa la retroversión y acumula adv tuck con una pierna. (d) Dragon flag con dolor lumbar: regresa al excéntrico; la lumbar que se arquea es aviso, no molestia a ignorar.',
                'Seguridad N9: el MU completo solo tras el Bloque 0 + activación específica, nunca como primera actividad; negativas de HSPU siempre con colchoneta/parallettes (el cuello no toca el suelo); dragon flag con apoyo en hombros, jamás cargando el cuello.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Construcción del muscle-up por piezas (C2B alto + transición + SBD)', 'note' => 'Peldaños de one-leg FL y dragon flag; negativas de HSPU parciales', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primeros intentos completos de MU; pistol a asistencia mínima', 'note' => 'Hitos: negativas HSPU rango completo, adv tuck planche 10 s, adv tuck BL 5-8 s, false grip 15-18 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Picos: MU + pistol + dragon flag', 'note' => 'Mantienen SBD (anti-coincidencia de codo) y adv tuck BL', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Deload; pike protagonista (puerta G6)', 'note' => 'Test de nivel + G6 + re-tests G1/G2 al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Muscle-up en barra', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Sin kipping desesperado, transición continua y simétrica, final en apoyo estable sobre la barra', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'One-leg front lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Espalda paralela, brazos rectos, retroversión pélvica; la pierna extendida empuja la línea', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Pistol squat (cada pierna)', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Talón pegado, bajada controlada, arranque sin impulso del brazo ni rebote', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dragon flag', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Cuerpo en línea apoyado en hombros (nunca en el cuello), lumbar sin arquear', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Puerta G6 — Pike / isquiosurales', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'De pie, piernas extendidas, palmas completas al suelo; alternativa sentado: pecho a muslos', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'G6'],
                        ['name' => 'Re-test G1 — Flexión de hombro', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'De pie contra pared, espalda y lumbar pegadas, brazos tocan la pared sin arquear ni separar', 'type' => TestItemType::ReTest->value, 'gateCode' => 'G1'],
                        ['name' => 'Re-test G2 — Extensión de muñeca 90°', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Palma en suelo, dedos hacia rodillas, antebrazo vertical, 20 s sin dolor', 'type' => TestItemType::ReTest->value, 'gateCode' => 'G2'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL9(): array
    {
        return [
            [
                'code' => 'v4-n9-muscle-up',
                'name' => '¡MUSCLE-UP EN BARRA!',
                'description' => 'Primer muscle-up en barra: tracción explosiva, transición limpia y fondo hasta el apoyo. El segundo gran hito emocional del programa: celebración máxima.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Muscle-up en barra',
                'xpReward' => 200,
            ],
            [
                'code' => 'v4-n9-pistol-squat',
                'name' => '¡PISTOL SQUAT!',
                'description' => 'Pistol squat libre por pierna: talón pegado, bajada controlada y arranque sin impulso. Hito cultural de pierna.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pistol squat (cada pierna)',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n9-one-leg-front-lever',
                'name' => 'One-leg front lever',
                'description' => 'Supera el mínimo de one-leg front lever (5 s) en el test de Nivel 9.',
                'category' => 'progress', 'icon' => '🛡️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'One-leg front lever',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n9-primer-dragon-flag',
                'name' => 'Primer dragon flag',
                'description' => 'Supera el mínimo de dragon flag (3 reps) en el test de Nivel 9.',
                'category' => 'progress', 'icon' => '🐉', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dragon flag',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n9-puerta-pike-g6',
                'name' => 'Puerta de pike superada (G6)',
                'description' => 'Puerta de pike/isquiosurales superada: palmas completas al suelo de pie con las piernas extendidas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta G6 — Pike / isquiosurales',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n9-adv-tuck-planche-10s',
                'name' => 'Adv tuck planche 10 s',
                'description' => 'Advanced tuck planche de 10 s: consolida el objetivo de N8 y mantiene viva la línea planche. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '📐', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n9-adv-tuck-back-lever',
                'name' => 'Adv tuck back lever 5-8 s',
                'description' => 'Advanced tuck back lever de 5-8 s: primer peldaño de la escalera hacia el full back lever de N11. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🛡️', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n9-negativas-hspu',
                'name' => 'Negativas de HSPU en pared',
                'description' => 'Negativa de HSPU a rango completo de 4-6 s con colchoneta/parallettes: peldaño previo real del HSPU de N10. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n9-false-grip-hang-15s',
                'name' => 'False grip hang 15-18 s',
                'description' => 'False grip hang de 15-18 s (o false grip ring rows con pausa): continuidad de la línea de falso agarre hacia el gate de N10. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤝', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 10 — VERTICAL FUERTE
    // ========================================================================

    private static function level10(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): enamórate de las negativas. El HSPU se gana bajando lento y con la línea cerrada mucho antes de subir. No persigas la rep completa antes de tiempo: cada negativa perfecta es un ladrillo.',
                'Fase 2 (Progresión): es la fase de los "primeros": primera HSPU completa, primer false grip pull-up, primer straddle BL. Celébralos (son logros de hito) y no los conviertas en volumen el mismo ciclo: un primer logro se consolida, no se machaca.',
                'Fase 3 (Intensificación): los estáticos se testean frescos, nunca al final de la sesión. Si el false grip hang de 20 s tiembla al segundo intento, ya no cuenta: descansa más o déjalo para el próximo ciclo.',
                'Fase 4 (Recuperación): no te saltes el re-test G4. El full back lever de N11 es la máxima exigencia de extensión de hombro del programa y llega cinco niveles después de su puerta: si el German hang ha involucionado, el ciclo corto de movilidad es obligatorio, no opcional.',
                'Regla de sobrecarga entre ciclos: al repetir ciclo, la Fase 1 arranca con una progresión ligera respecto al ciclo anterior: +1 rep por serie, o −1 nivel de asistencia (banda, agarre, altura de apoyo), o +2 s por hold — solo una palanca a la vez y siempre con técnica limpia. Si ninguna es posible sin perder calidad, se mantiene la dosis: el estímulo se gana con otro ciclo, no con grinders.',
                'Si fallas el test: (a) HSPU que no cierra los últimos centímetros: trabaja medias reps en la zona débil en lugar de más intentos completos. (b) HS libre que no pasa de 2-3 s: el fallo es de dedos, no de hombros — vuelve al salvado con dedos contra pared hasta que sea reflejo. (c) False grip hang bajo 20 s: casi nunca es espalda, es antebrazo/agarre — hangs cortos y frecuentes (8-12 s) + wrist curls en el Bloque 0, nunca intentos máximos a diario. (d) Press negativo que colapsa: retrocede al cajón y refuerza la compresión en pike; es un problema de compresión, no de hombros.',
                'Seguridad N10: nivel press-dominante extremo — el contrapeso horizontal pesado de la sesión B no es negociable (6-10 series por microciclo). Molestia matinal en el tendón del bíceps >2/10 = congelar planche y pseudo planche un ciclo. Nunca HSPU con el cuello fatigado ni falso agarre sin el Bloque 0 completo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Técnica y acumulación: negativas de HSPU, peldaños asistidos, holds cortos', 'note' => 'Pirueta de salida automática antes de soltar la pared', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primeras reps completas de HSPU y false grip pull-up; holds se alargan', 'note' => 'Hitos: straddle/one-leg BL, L-sit 10-15 s, one-leg FL 8 s, false grip pull-up', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Marcas de test; pico de planche SIN pico de back lever (anti-coincidencia bíceps)', 'note' => 'Rotación de picos por ciclo en B: marca de MU (impares) / gate de false grip (pares)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad protagonista', 'note' => 'Test de nivel + re-test G4 integrado en sesión D al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'HSPU en pared (rango completo)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Pecho a pared, parallettes o colchoneta entre las manos; coronilla roza sin cargar peso, codos a ~45°, extensión completa', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Handstand libre', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Sin apoyo de pared, línea recta, corrección con dedos y hombros; solo cuentan holds con salida controlada (pirueta)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Muscle-up en barra', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Reps frescas y sin kip, transición continua; la segunda rep sale del impulso de la primera sin balanceo extra', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Pistol squat (cada pierna)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Talón pegado, bajada controlada, arranque sin impulso ni balanceo del torso', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Pike press negativo (bajada 5 s)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Piernas extendidas bajan en 5 s sin caída libre, cadera alta el máximo tiempo, codos ceden sincronizados', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Archer push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones por lado', 'form' => 'Brazo asistente extendido solo equilibra, torso sin rotación, pecho cerca de la mano que trabaja', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'False grip hang (gate)', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Muñeca SOBRE la anilla, hombros activos, respiración nasal bajo tensión; se testea fresco, nunca en frío', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Re-test G4 — Extensión de hombro', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'German hang asistido (anillas, pies apoyados) 15 s sin dolor ni pinzamiento', 'type' => TestItemType::ReTest->value, 'gateCode' => 'G4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL10(): array
    {
        return [
            [
                'code' => 'v4-n10-primer-hspu-pared',
                'name' => 'Primer HSPU en pared',
                'description' => 'Primera flexión vertical completa en pared: rango completo con la línea cerrada y el cuello siempre libre de carga. La cima del empuje vertical del programa.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HSPU en pared (rango completo)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-primer-handstand-libre',
                'name' => 'PRIMER HANDSTAND LIBRE',
                'description' => 'Primer handstand sin pared de 5 s con salida controlada: el equilibrio libre deja de ser un miedo y pasa a ser un skill. Celebración máxima.',
                'category' => 'skill', 'icon' => '🙌', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Handstand libre',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n10-muscle-up-x3',
                'name' => 'Muscle-up ×3',
                'description' => 'Tres muscle-ups en barra en el test: el gesto conquistado en N9 convertido en marca consolidada.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Muscle-up en barra',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-pistol-x3-por-pierna',
                'name' => 'Pistol ×3 por pierna',
                'description' => 'Tres pistol squats con cada pierna: fuerza unilateral de pierna consolidada.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pistol squat (cada pierna)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-primer-press-negativo',
                'name' => 'Primer press negativo',
                'description' => 'Pike press negativo de 5 s sin caída libre: la compresión y el empuje empiezan a hablar el mismo idioma camino del press to handstand.',
                'category' => 'skill', 'icon' => '⬇️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pike press negativo (bajada 5 s)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-archer-push-up',
                'name' => 'Archer push-up',
                'description' => 'Archer push-up completa en el test: torso sin rotación y el brazo asistente solo equilibrando. Puente hacia la one-arm.',
                'category' => 'skill', 'icon' => '🏹', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Archer push-up',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-false-grip-hang-20s',
                'name' => 'False grip hang 20 s',
                'description' => 'Gate conservador de falso agarre superado: 20 s con la muñeca sobre la anilla. El billete de entrada al muscle-up en anillas de N11.',
                'category' => 'skill', 'icon' => '🤝', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'False grip hang (gate)',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-hombro-libre-retest-g4',
                'name' => 'Hombro libre re-verificado (G4)',
                'description' => 'Re-test de extensión de hombro superado: German hang asistido 15 s sin dolor, listo para la línea back lever que viene.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Re-test G4 — Extensión de hombro',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-shrimp-squat',
                'name' => 'Shrimp squat',
                'description' => 'Shrimp squat libre: la rodilla trasera roza el suelo sin golpearlo y el talón delantero pegado. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🦐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-false-grip-pull-up',
                'name' => 'False grip pull-up',
                'description' => 'Primera dominada en anillas con falso agarre profundo: peldaño directo del muscle-up en anillas de N11. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤝', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-nordic-excentrica',
                'name' => 'Nordic excéntrica',
                'description' => 'Primera Nordic excéntrica completa de 5 s: el puente al Nordic curl de N13 empieza aquí. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-one-leg-fl-8s',
                'name' => 'One-leg front lever 8 s',
                'description' => 'One-leg front lever de 8 s: consolida el objetivo de N9 y deja la línea lista para el straddle FL de N11. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🛡️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-adv-tuck-planche-abierta',
                'name' => 'Adv tuck planche 10-12 s / adv tuck con cadera abierta',
                'description' => 'Adv tuck planche de 10-12 s o con la cadera abierta: continuidad de la línea planche y pre-peldaño del straddle de N11. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '📐', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-straddle-one-leg-back-lever',
                'name' => 'Straddle o one-leg back lever',
                'description' => 'Segundo peldaño hacia el full back lever de N11: horizontal con una pierna o en straddle, sin pinzamiento anterior. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⚓', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-l-sit-10-15s',
                'name' => 'L-sit 10-15 s',
                'description' => 'L-sit de 10-15 s limpios: la línea de compresión que desembocará en el V-sit de N13. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🪑', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-archer-pull-up-x3-4',
                'name' => 'Archer pull-up ×3-4/lado',
                'description' => 'Archer pull-up de 3-4 reps por lado: la vía unilateral que alimenta el typewriter de N11. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n10-primer-pancake',
                'name' => 'Primer pancake con pecho al suelo',
                'description' => 'Primer pancake con el pecho al suelo: hito opcional de movilidad straddle que pagará dividendos en N11. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🥞', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 11 — STRADDLE Y ANILLAS
    // ========================================================================

    private static function level11(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): las bandas y las puntas de pies no son trampa, son el programa. La straddle planche y el full BL se ganan acumulando segundos asistidos de calidad; quien se salta los peldaños §1.6 vuelve a ellos en dos ciclos, con el tendón quejándose.',
                'Fase 2 (Progresión): fase de banderas: straddle con banda, MU asistido, full BL asistida. Registra cuánta banda usas (color/grosor): la progresión se mide en asistencia retirada, no solo en segundos.',
                'Fase 3 (Intensificación): los cinco gates se testean frescos. En D, el MU en anillas va primero: un intento de MU tras el full BL es un intento muerto. Respeta los descansos de 3-4 min: en esta fase el descanso ES el entrenamiento.',
                'Fase 4 (Recuperación): revisión personal de las seis puertas. Si alguna amarillea (molestia, rango perdido), añade el protocolo corto de esa puerta al siguiente ciclo: el mantenimiento religioso de este nivel es el seguro del HSPU libre y del press de N12.',
                'Regla de sobrecarga entre ciclos: al repetir ciclo, la Fase 1 arranca con una progresión ligera respecto al ciclo anterior: +1 rep por serie, o −1 nivel de asistencia (banda, puntas de pies, altura de apoyo), o +2 s por hold — solo una palanca a la vez y siempre con técnica limpia. Si ninguna es posible sin perder calidad, se mantiene la dosis: el estímulo se gana con otro ciclo, no con grinders.',
                'Si fallas el test: (a) MU en anillas que muere en la transición: falso agarre demasiado superficial — agarre más profundo y anillas más bajas para ensayar; más transiciones con pies en suelo, menos intentos completos. (b) Straddle planche bajo 5 s: retrocede un peldaño §1.6 (banda o puntas) o cierra la apertura; el mínimo bajo existe para que este estático no frene tu camino al MU y al full BL. (c) Straddle FL inestable: abre menos y revisa la retroversión pélvica; sin pelvis posterior no hay lever. (d) Full BL con pinzamiento: German hang en cada Bloque 0 y regresión a straddle BL un ciclo; el hombro anterior no negocia. (e) Typewriter asimétrico: baja el volumen y trabaja con banda; la asimetría aprendida aquí cuesta ciclos en la vía one-arm.',
                'Seguridad N11: tres líneas de brazo recto conviven en este nivel — la alternancia pesada/ligera del mapa tendinoso es innegociable. Dolor matinal de bíceps >2/10 = congelar planche un ciclo. Nunca MU en anillas ni full BL sin el Bloque 0 completo (falso agarre y German hang incluidos).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Peldaños de entrada: straddle con puntas/banda, transición de MU con pies en suelo', 'note' => 'Una sola exposición pesada por línea de brazo recto por microciclo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Bandas se retiran; primeras straddle libres y MU asistido completo', 'note' => 'Hitos: straddle planche y full BL con banda, HS libre 15 s, HSPU 8, ring dip ×10', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Marcas de test; picos de straddle planche y MU anillas SIN pico de back lever (anti-coincidencia bíceps)', 'note' => 'Los cinco gates se testean frescos; el MU en anillas va primero en D', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad straddle protagonista', 'note' => 'Test de nivel al final + revisión personal de puertas G1-G6 (mantenimiento, sin re-tests formales)', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Straddle front lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Espalda paralela, brazos rectos, retroversión pélvica, apertura estable durante todo el hold; progresa por apertura, no por tiempo a la desesperada', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Straddle planche (gate conservador)', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal, brazos rectos sin hiperextensión, proyección máxima; un straddle cerrado sólido vale más que uno abierto temblando', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Muscle-up en anillas', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Falso agarre profundo (muñeca sobre la anilla), jalón explosivo, transición continua sin kip, fondo final con giro externo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Full back lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Entrada por skin the cat/German hang, horizontal real sin pike ni arco lumbar, brazos rectos pegados al cuerpo, sin pinzamiento anterior', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Typewriter pull-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Barbilla sobre la barra durante todo el desplazamiento lateral, simetría estricta (mismo número a cada lado), sin balanceo', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL11(): array
    {
        return [
            [
                'code' => 'v4-n11-straddle-front-lever',
                'name' => 'STRADDLE FRONT LEVER',
                'description' => 'Straddle front lever de 5 s en el test: horizontal con apertura estable y retroversión innegociable. Primer estático straddle del programa.',
                'category' => 'skill', 'icon' => '🛡️', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Straddle front lever',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n11-straddle-planche',
                'name' => 'STRADDLE PLANCHE',
                'description' => 'Straddle planche de 5 s (gate conservador): la línea planche iniciada en N5 alcanza su primer estático de élite.',
                'category' => 'skill', 'icon' => '📐', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Straddle planche (gate conservador)',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n11-muscle-up-anillas',
                'name' => '¡MUSCLE-UP EN ANILLAS!',
                'description' => 'Muscle-up en anillas sobre falso agarre profundo: la segunda cima de anillas del programa. Celebración máxima.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Muscle-up en anillas',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n11-full-back-lever',
                'name' => 'FULL BACK LEVER',
                'description' => 'Full back lever de 5 s: la máxima exigencia de extensión de hombro del programa, superada con la G4 vigilada.',
                'category' => 'skill', 'icon' => '⚓', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Full back lever',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n11-primer-typewriter',
                'name' => 'Primer typewriter',
                'description' => 'Primer typewriter pull-up con simetría estricta: la barbilla viaja de lado a lado sin bajar de la barra.',
                'category' => 'skill', 'icon' => '⌨️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Typewriter pull-up',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-straddle-planche-banda',
                'name' => 'Straddle planche asistida con banda',
                'description' => 'Straddle planche con banda a la cadera: el peldaño §1.6 donde se acumulan los segundos que luego serán libres. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '📐', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-full-back-lever-banda',
                'name' => 'Full back lever asistida con banda',
                'description' => 'Full back lever con banda: la horizontal completa ensayada con red de seguridad antes del estático libre. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⚓', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-skater-cossack-carga',
                'name' => 'Skater squat / cossack con carga',
                'description' => 'Skater squat libre y cossack con carga: pierna unilateral y apertura straddle bajo carga, base del dragon pistol de N12. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⛸️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-handstand-libre-15s',
                'name' => 'Handstand libre 15 s',
                'description' => 'Handstand libre de 15 s: el prerequisito de equilibrio del HSPU libre de N12. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🙌', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-hspu-pared-8',
                'name' => 'HSPU en pared 8 reps',
                'description' => 'Ocho HSPU en pared: el prerequisito de fuerza del HSPU libre de N12. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-ring-dip-x10',
                'name' => 'Ring dip ×10 / ring dip lastrado +10%',
                'description' => 'Diez fondos en anillas con RTO o lastre del +10%: continuidad de la familia de fondos hacia el impossible dip. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⭕', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n11-one-arm-asistida-archer-x5',
                'name' => 'One-arm push-up asistida / archer push-up ×5/lado',
                'description' => 'One-arm push-up asistida con mano elevada o archer ×5 por lado: el peldaño previo del one-arm push-up de N12. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 12 — FULL LEVERS
    // ========================================================================

    private static function level12(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): no tengas prisa por quitar la straddle. Los hitos de 8-10 s de straddle planche y 8 s de straddle FL son el combustible del full: cada segundo de straddle limpio son centímetros de full futuro.',
                'Fase 2 (Progresión): la banda es tu laboratorio. Registra grosor y punto de anclaje (cadera vs. pies) en cada sesión: la retirada de asistencia es la progresión real de esta fase, aunque los segundos parezcan iguales.',
                'Fase 3 (Intensificación): los intentos de full van primeros, frescos y con 4 min de descanso. Un intento de full planche tras la sesión de empuje no mide tu planche: mide tu fatiga. Si la marca no llega, NO añadas intentos: añade ciclos.',
                'Fase 4 (Recuperación): los re-tests G3/G5/G6 son el peaje del dragon pistol (G3) y del V-sit y el straddle press de N13 (G5/G6). Una dorsiflexión, una compresión o un pike deteriorados convierten el pistol y el press en ejercicios de rodilla y lumbar: si fallan, ciclo corto de movilidad sin repetir fuerza.',
                'Regla de sobrecarga entre ciclos: al repetir ciclo, la Fase 1 arranca con una progresión ligera respecto al ciclo anterior: +1 rep por serie, o −1 nivel de asistencia (grosor de banda, roce de pared, apoyo de puntas), o +2 s por hold — solo una palanca a la vez y siempre con técnica limpia. Si ninguna es posible sin perder calidad, se mantiene la dosis: en este nivel el estímulo se gana con otro ciclo, no con grinders.',
                'Si fallas el test: (a) Full planche que no pasa de 1-2 s: vuelve a straddle con holds más largos, no a más intentos de full; este estático escala mal con el peso corporal y el mínimo bajo (3 s) existe para no frenarte. (b) Full FL que se desploma: revisa retroversión y retracción; retrocede al straddle 8 s y al full con banda. (c) HSPU libre que no cierra: separa equilibrio y fuerza — HS libre 15 s y HSPU pared 8 deben estar vivos; entrénalos por separado un ciclo y reúnelos después. (d) Press que no despega: es compresión, no hombros — más V-sit asistido, compresión en pike y revisión honesta de G5/G6. (e) One-arm con rotación: baja un peldaño y gana reps limpias; la rotación aprendida se paga en hombro. (f) Dragon pistol que pierde el arco: más skater y más excéntrica de gemelo; el fallo suele ser tobillo/cadera, no cuádriceps.',
                'Seguridad N12: es el nivel de mayor carga tendinosa absoluta del programa (bíceps en planche, hombro en FL, muñecas en HSPU libre). Máximo un frente estático pesado por sesión. Molestia matinal de bíceps >2/10 = congelar planche un ciclo (el resto sigue). Ningún estático pesado en frío. La descarga extra de muñeca no es opcional.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Consolidación straddle (hitos 8-10 s) + peldaños asistidos de full', 'note' => 'Máximo un frente estático pesado por sesión', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Full con banda como protagonista; HSPU libre asistido; press con asistencia', 'note' => 'Hitos: full planche y full FL con banda, Bulgarian dip / ring dip RTO', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Intentos de full FL y full planche frescos; marcas de test', 'note' => 'Rotación de pico por ciclo en D: HSPU libre (impares) / press (pares)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad protagonista', 'note' => 'Test de nivel + re-tests G3, G5 y G6 integrados en sesión D al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Full front lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Piernas juntas y extendidas, horizontal real (hombros-cadera-tobillos en línea), brazos rectos, retroversión pélvica; intentos frescos', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Full planche', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Piernas juntas, cuerpo horizontal (ni cadera alta ni caída), brazos rectos, proyección escapular máxima; cada intento cuenta, sin grinders', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'HSPU libre (sin pared)', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Estabiliza 1-2 s antes de bajar; rango completo sin apoyo de pared en ningún punto; la rep solo cuenta con equilibrio mantenido o salida en pirueta limpia', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Press to handstand (pike)', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Desde el suelo, sin salto ni impulso visible de piernas; la compresión hace el 80% del trabajo, cadera alta y brazos que empujan desde el primer grado', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'One-arm push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones por lado', 'form' => 'Pies abiertos, cero rotación de cadera/torso, rango completo, cuerpo en línea de cabeza a talones', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dragon pistol squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'La pierna libre dibuja el arco completo por detrás sin tocar el suelo, talón del apoyo pegado, subida controlada sin rebote', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Re-test G3 — Dorsiflexión de tobillo', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Knee-to-wall ≥10 cm sin despegar el talón, simetría <2 cm entre lados', 'type' => TestItemType::ReTest->value, 'gateCode' => 'G3'],
                        ['name' => 'Re-test G5 — Compresión de cadera', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Sentado, piernas extendidas y juntas despegadas 10 s, manos solo apoyo ligero, lumbar neutra', 'type' => TestItemType::ReTest->value, 'gateCode' => 'G5'],
                        ['name' => 'Re-test G6 — Pike / isquiosurales', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'De pie, palmas completas al suelo con piernas extendidas; alternativa sentado: pecho a muslos', 'type' => TestItemType::ReTest->value, 'gateCode' => 'G6'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 13 — FUERZA UNILATERAL
    // ========================================================================

    private static function level13(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): el lastre entra despacio a propósito. +10-15% con técnica de dominada estricta vale más que +25% con tirón: estás construyendo el codo que el one-arm necesitará en N14-N15, y los tendones maduran en una escala de años, no de ciclos (§1.6).',
                'Fase 2 (Progresión): la negativa a un brazo es tu primera cita con el one-arm: trátala como skill, no como ejercicio más. Fresco (tras el lastre, nunca al final), pocas reps, descansos de 3 min, y si el codo protesta a la mañana siguiente, la vía unilateral se congela un ciclo y todo lo demás sigue.',
                'Fase 3 (Intensificación): las marcas van primeras y frescas. Un intento de flag tras cuarenta minutos de sesión mide tu fatiga, no tu flag. Registra el lado: el flag se testea en tu mejor lado, pero se entrena en los dos — cuando quieras el flag a ambos lados (Olimpo) lo agradecerás.',
                'Fase 4 (Recuperación): la torácica en extensión y la apertura straddle son el trabajo real de esta fase. El straddle press de tu próximo ciclo depende más de lo que abras aquí que de lo que empujes en F3.',
                'Si fallas el test — diagnóstico: (a) El flag cae del lado del brazo inferior: el fallo es de empuje (serrato/dorsal del brazo de abajo), no de core: retrocede a bandera vertical y 45° con holds más largos y añade support press laterales. (b) El +25% se atranca: alterna un ciclo de volumen con peso corporal alto (8-10 reps estrictas) con un ciclo de lastre: la tracción responde a la alternancia. (c) La L-sit pull-up pierde la L: el freno es la compresión, no la tracción: más V-sit, compresión sentada y la variante con una pierna. (d) El straddle press no despega: revisión honesta de apertura straddle (Bloque 0) y de G5/G6; el 80% del press es compresión y cadera alta. (e) El Nordic se "rompe" a media subida: normal: la cima del rango es lo último en llegar. Sigue con el semicompleto con banda y reduce la asistencia, no el rango.',
                'Advertencias de seguridad: el lastre y la vía unilateral cargan el codo y el bíceps como nunca hasta ahora: dolor matinal >2/10 = congelar esa escalera un ciclo (§1.7). El flag exige hombros perfectamente calientes y el bloque postural religioso: ningún intento sin el Bloque 0 completo. El Nordic es la máxima carga excéntrica de isquiosural del programa: nunca en frío, nunca al fallo excéntrico total, y la molestia detrás de la rodilla es una señal de stop, no de esfuerzo.',
                'Mapa tendinoso del nivel (§1.7): la línea de codo en tracción (lastre + L-sit PU + vía unilateral) pica SOLO en B, una exposición pesada por microciclo — y dentro de B, en F3, el lastre y el L-sit pull-up rotan el pico por ciclo (nunca dos marcas de codo en la misma sesión; la negativa a un brazo, ya conseguida en F2, baja a mantenimiento) · la línea de apoyo/bíceps distal (planche dinámico) pica SOLO en A · el flag (hombro en abducción lateral + oblicuos) y la línea vertical/press (hombro en flexión) viven en D, con el flag como estático pesado único de la sesión · C es la sesión tendinosa de pierna e isquiosural (Nordic: nunca en frío, nunca al fallo excéntrico) · la negativa a un brazo se introduce en F2, nunca antes de que el typewriter pesado esté limpio · molestia matinal en codo o bíceps >2/10 = congelar la escalera de lastre/unilateral un ciclo (el resto sigue).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Lastre entra conservador (+10-15%); bandera 45° (hito); V-sit y straddle press asistidos; Nordic excéntrica larga', 'note' => 'Máximo un frente estático pesado por sesión; línea de codo en tracción pica SOLO en B', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Lastre +15-20%; negativa a un brazo (hito); flag horizontal con apoyo; straddle press con asistencia mínima', 'note' => 'Entrena el flag en ambos lados aunque el test se mida en el mejor lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Marcas de test: +25%, L-sit PU, flag horizontal, V-sit, straddle press, Nordic completo — siempre frescos', 'note' => 'En B rotan los picos de codo por ciclo: impares lastre +25%, pares L-sit PU', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Movilidad protagonista (torácica + straddle); dosis mínimas de mantenimiento; sin intentos de marca', 'note' => 'El trabajo real de esta fase es la apertura, no el empuje', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Dominada lastrada +25% BW', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Lastre asegurado en la cadera, dominada estricta desde brazos extendidos hasta barbilla sobre la barra, cero impulso de piernas', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'L-sit pull-up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Desde colgado activo con piernas extendidas en L, tracciona hasta barbilla sobre la barra sin perder la L ni balancearte', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Human flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal lateral a la barra, brazos rectos, hombro inferior empuja, core rígido, sin arqueo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'V-sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'En el suelo, manos a los lados de las caderas, piernas extendidas y despegadas del suelo, compresión máxima, lumbar neutra', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Straddle press to handstand', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Desde straddle stand, compresión y elevación controlada al pino sin impulso de piernas; piernas abiertas hasta juntarlas en vertical', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Nordic curl completo', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde rodillas, caída controlada hacia adelante con talones fijos, recuperación completa sin ayuda de manos', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 14 — ONE-ARM CHIN-UP
    // ========================================================================

    private static function level14(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): el OAC asistido con banda gruesa parece ridículo de fácil: perfecto. Estás enseñando al sistema nervioso el patrón unilateral completo, no demostrando nada. La fuerza ya la tienes (o la tendrás del lastre); lo que se construye aquí es el mapa motor del gesto.',
                'Fase 2 (Progresión): registra cada asistencia (grosor de banda, nº de dedos) como si fuera un lastre: la retirada de asistencia ES la progresión del nivel. Y respeta el orden de B: OAC primero, negativa después, lastre al final. El skill siempre en fresco.',
                'Fase 3 (Intensificación): respeta las rotaciones de pico sin excepciones: en ciclo impar no se intenta el 90° máximo ni el lastre +50%, en ciclo par no se intenta el planche PU máximo ni el OAC. Cada par de gestos cuelga del mismo tendón; intentar los dos picos en el mismo microciclo es la receta más rápida para una tendinopatía de bíceps o de codo. La sesión B de marcas es larga (100-120 min) precisamente porque los descansos no se negocian: si el tiempo aprieta, se recorta volumen de accesorios, nunca descanso.',
                'Fase 4 (Recuperación): es la única fase sin exposición unilateral pesada: apróvechala. La salud capsular (cross-body, ER a 90°, rotaciones cubanas) y el antebrazo en suelo son el entrenamiento real de esta fase.',
                'Si fallas el test — diagnóstico: (a) El OAC se queda a media altura: la negativa a un brazo es tu mejor aliada (5-8 s de bajada controlada); retrocede a banda media y negativas largas un ciclo. (b) El OAC no arranca desde abajo: más lastre pesado a dos brazos (el techo del one-arm es la fuerza a dos brazos) y asistencia con dedos en lugar de banda (enseña el arranque real). (c) El planche push-up no sale: casi siempre es un full planche demasiado corto: alarga el hold (4-5 s) antes de añadir flexiones. (d) El 90° se queda en el fondo: más parciales de medio rango y más descenso a straddle planche; el fondo del 90° es fuerza de planche, no de tríceps. (e) Los FL raises se balancean: reduce el rango (raise a straddle) y gana control; el balanceo aprendido invalida la rep.',
                'Advertencias de seguridad: el one-arm chin-up es el ejercicio más agresivo del programa para el tendón del bíceps y el hombro: una sola exposición pesada por microciclo, nunca en frío, nunca con molestia previa. Cualquier dolor en la cara anterior del hombro detiene la vía unilateral y los estáticos de anillas (se sigue entrenando todo lo demás). El tiger bend y el impossible dip tienen umbral de dolor cero en el codo: a la primera punzada se retrocede un peldaño sin negociación. Los estáticos de anillas (cross, maltese) solo tras el Bloque 0 completo y con asistencias honestas: aquí la progresión se mide en milímetros por ciclo.',
                'Mapa tendinoso del nivel (§1.7 — la zona más delicada del programa): el one-arm chin-up es el ejercicio más agresivo del catálogo para el tendón del bíceps y el hombro: una sola exposición pesada unilateral por microciclo (sesión B), nunca en sesiones consecutivas, nunca en frío · el planche PU (bíceps distal) y el 90° PU (hombro en flexión + bíceps) comparten tendón: nunca se intensifican en la misma fase — de ahí la rotación de picos por ciclo en F3 · el OAC y el lastre +50% también rotan el pico por ciclo en F3 (comparten sesión B y el mismo codo en tracción: nunca dos marcas pesadas de tracción en la misma sesión) · el tiger bend hold (codo/antebrazo) vive en D, separado del impossible dip asistido (A): los dos gestos más lesivos para el codo del catálogo no coinciden nunca en la misma sesión, y en ciclo par de F3 (pico de 90°) el tiger bend se queda en hold, sin negativos · anillas élite (cross hold, maltese con banda): brazo recto, volumen bajo, descansos largos, solo tras el Bloque 0 completo, y rotan el foco pesado por ciclo como en N15 (mismo tendón: hombro/bíceps en brazo recto lateral — nunca progresan los dos a la vez; en ciclo par de F3 ambos trabajan técnicos porque el 90° es el pico de D) · cualquier dolor en la cara anterior del hombro detiene la vía unilateral y los estáticos de anillas (se sigue entrenando el resto con normalidad).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Vía OAC: archer pesado + typewriter + OAC asistido técnico; hitos de peldaño (90° asistido, cross con banda, tiger bend hold, impossible asistido)', 'note' => 'Bloque 0 más largo del programa; salud capsular y antebrazo en suelo innegociables', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'OAC asistido con menos banda + negativa larga; lastre +40%; FL raises; 90° parcial (hito); tiger bend negativo (hito)', 'note' => 'La retirada de asistencia es la progresión real del nivel', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Rotación de picos por ciclo: impares pica planche PU (A) y OAC (B); pares pica 90° PU (D) y +50% (B)', 'note' => 'Nunca dos picos del mismo tendón en la misma fase; descansos de 4-5 min', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Movilidad protagonista (cápsula de hombro + antebrazo); dosis mínimas; sin intentos de marca', 'note' => 'Aprovecha para salud capsular y antebrazo en suelo', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'One-arm chin-up', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Cuelga de una mano en supinación con el hombro activo; tracciona hasta barbilla sobre la barra, torso apenas rotado, sin impulso de piernas; descenso controlado', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Planche push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde full planche estable, flexiona manteniendo la horizontal y la protracción; la rep que rompe la posición no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => '90° push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Desde handstand libre, descenso controlado hasta straddle planche y empuje de vuelta al pino, sin impulso de piernas; rango completo en ambas direcciones', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Front lever raises', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde colgado activo con brazos rectos y retroversión, eleva el cuerpo extendido hasta full front lever, mantén 1 s y baja controlado; sin balanceo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dominada lastrada +50% BW', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Lastre asegurado en la cadera, dominada estricta, barbilla sobre la barra, cero impulso de piernas', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 15 — ONE-ARM PULL-UP
    // ========================================================================

    private static function level15(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): el OAP en prona se aprende desde cero aunque el OAC sea tuyo: acepta la banda gruesa sin vergüenza. Lo que estás comprando con esa asistencia es un codo sano dentro de diez años.',
                'Fase 2 (Progresión): cambia la asistencia del OAP a dedos lo antes posible: 1-2 dedos en tu propia muñeca enseñan el arranque real que la banda no enseña. Registra dedos y grosores como registrarías kilos.',
                'Fase 3 (Intensificación): tres rotaciones de pico, cero excepciones: impossible/tiger bend, cross/maltese y OAP/lastre +50% — ninguna pareja pica junta. En este nivel la disciplina de NO hacer es lo que te lleva al test: el que lo intenta todo cada sesión no llega a Olimpo, llega al fisio. La sesión B de marcas dura 100-120 min porque los descansos de 4-5 min no se negocian: si el tiempo aprieta, se recorta volumen de accesorios, nunca descanso.',
                'Fase 4 (Recuperación): es la fase más importante del nivel más lesivo. La movilidad de recuperación es el entrenamiento: cápsula posterior, pectoral, antebrazo en suelo, dislocates. Sal de esta fase mejor de lo que entraste, no solo menos cansado.',
                'Si fallas el test — diagnóstico: (a) El OAP no arranca desde abajo: cambia la asistencia a dedos (patrón real) y sube el lastre a dos brazos; el arranque es fuerza absoluta de dorsal/braquial. (b) El OAP muere a media altura: negativa prona de 8 s y más typewriter prono; el tramo medio se construye excéntricamente. (c) El tiger bend no empuja de vuelta: más asistido y más hold; la salida del fondo es tríceps en un ángulo que solo se construye con reps asistidas perfectas. (d) El impossible dip duele por delante: sube la asistencia y baja el rango; la cápsula anterior no perdona la prisa — la progresión se mide en centímetros por ciclo. (e) El cross o el maltese tiemblan en exceso: demasiado ángulo o poca banda; sube la asistencia y alarga los descansos a 4 min. Un estático tembloroso no construye: solo fatiga.',
                'Advertencias de seguridad: es el nivel más lesivo del catálogo. Reglas innegociables: dolor matinal en codo u hombro >2/10 = congelar esa escalera un ciclo · ningún estático de anillas ni unilateral sin el Bloque 0 completo · tiger bend e impossible dip: umbral de dolor cero · iron cross y maltese, incluso asistidos, son los movimientos más lesivos del programa si se fuerzan: bandas honestas, rangos parciales, hombros perfectamente calientes · la descarga larga no es opcional. El ONE-ARM PULL-UP es la celebración máxima del programa cerrado: llega entero, no lesionado.',
                'Mapa tendinoso del nivel (§1.7 — máxima alerta del catálogo): el OAP carga el codo en prona (menos ayuda del bíceps, más braquial/dorsal, pero el codo sigue siendo el eslabón débil): una exposición pesada por microciclo (B), siempre primero y fresco · la sesión B (OAP + +50% ×3) es la de mayor carga de codo del programa: en F3 sus dos marcas rotan el pico por ciclo (impar: OAP; par: +50% — nunca las dos en la misma sesión), su descarga es obligatoria y nunca va seguida de otra sesión pesada de codo · iron cross y maltese, incluso asistidos, son los movimientos más lesivos del catálogo si se fuerzan: bandas honestas, rangos parciales, hombros perfectamente calientes, y rotan el foco pesado por ciclo en F3 (nunca los dos al máximo en la misma sesión) · tiger bend (D) vs. impossible dip (A): anti-coincidencia explícita §1.7 — nunca se intensifican en la misma fase (mismo tendón, mismo vector) · el falso agarre se mantiene vivo en toda sesión D (la línea iniciada en N6 nunca se abandona) · dolor matinal >2/10 en codo u hombro = congelar esa escalera un ciclo; aquí no se negocia.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Vía OAP: asistido banda gruesa + negativa; cross/maltese con bandas generosas; impossible con banda media; tiger bend hold', 'note' => 'Bloque 0 más largo del catálogo; mantenimiento integral G1-G6', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'OAP asistido con dedos; +50% ×2; cross/maltese con banda media; tiger bend asistido (hito)', 'note' => 'Cambia la asistencia a dedos lo antes posible para enseñar el arranque real', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Rotación de picos por ciclo: impares pica impossible (A), tiger bend contenido (D), OAP (B); pares pica tiger bend (D), impossible mantiene (A), +50% (B); cross/maltese rotan foco', 'note' => 'Tres rotaciones de pico, cero excepciones; descansos 4-5 min', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Movilidad de recuperación como protagonista; la fase más importante del nivel más lesivo', 'note' => 'Dosis mínimas; movilidad de recuperación es el entrenamiento', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'One-arm pull-up (prona)', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Cuelga de una mano en pronación con el hombro activo; tracciona con dorsal y braquial, torso apenas rotado, hasta barbilla sobre la barra; descenso controlado, sin impulso', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Dominada lastrada +50% BW', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Lastre asegurado en la cadera, dominada estricta, barbilla sobre la barra, cero impulso; la base de fuerza a dos brazos del OAP', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Tiger bend push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Desde handstand libre, descenso controlado hasta apoyo sobre antebrazos y empuje de vuelta al pino, codos al frente, sin impulso de piernas; rango completo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Impossible dip', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'En anillas, descenso inclinando el cuerpo hacia atrás hasta que las anillas quedan por delante del torso y empuje de vuelta al apoyo; rango ganado progresivamente, sin dolor anterior', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Iron cross asistido (banda mínima)', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'En anillas con falso agarre y banda mínima bajo los pies, brazos extendidos lateralmente hasta la cruz; hold limpio, simétrico, sin temblor extremo', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Maltese asistido (banda)', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'En anillas con banda a la cadera, cuerpo proyectado hacia delante y brazos rectos abiertos lateralmente hasta la horizontal ganada; hold limpio, sin arqueo', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 16 — OLIMPO (BLOQUE ÉLITE ABIERTO)
    // ========================================================================

    private static function level16(): array
    {
        return [
            'tips' => [
                'Filosofía del bloque: elige 1-2 objetivos legendarios por ciclo (nunca más), respetando siempre la matriz de compatibilidad tendinosa.',
                'El resto del catálogo pasa a modo mantenimiento de élite: cada skill ya conseguido se conserva con dosis mínimas efectivas.',
                'Al superar el test parcial del objetivo se desbloquea el siguiente peldaño de ese micro-programa y se puede rotar de especialización.',
                'La movilidad del Bloque 0 se personaliza según la especialización activa (iron cross → falso agarre profundo + pectoral; maltese → extensión de hombro + muñeca; OAP múltiple → salud capsular + bíceps; lastre legendario → agarre + dorsal).',
                'Al entrar a Olimpo: resiste la tentación de elegir dos objetivos grandes (cross + OAP múltiple es la trampa clásica). Elige UNO durante tu primer ciclo: aprende el ritmo de especialización antes de doblar la apuesta.',
                'Al elegir especialización: prioriza la que más te ilusiona, no la que deberías; la adherencia es el motor del bloque infinito. La matriz de compatibilidad solo vetó combinaciones lesivas, no preferencias.',
                'En cada Fase 3: los tests parciales van frescos y primeros. Un TP intentado al final de la sesión mide tu fatiga. Si fallas un TP dos ciclos seguidos, el peldaño anterior necesita más tiempo bajo tensión: retrocede sin drama.',
                'En la rotación: cambiar de especialización tras superar un TP no es abandono, es el diseño. El mantenimiento de élite conserva lo construido mientras atacas otra montaña.',
                'Los combos son descanso activo de élite: el slot de estilo es la recompensa lúdica del nivel más duro. Úsalo: la maestría también se entrena jugando.',
                'Advertencia final: Olimpo es el bloque donde los egos se lesionan. Todas las reglas tendinosas que te trajeron hasta aquí valen el doble ahora. El objetivo no es conseguirlo todo: es seguir entrenando dentro de veinte años.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Peldaño actual del objetivo con asistencia/volumen técnico; mantenimiento de élite completo; movilidad personalizada', 'note' => 'Mantenimiento de élite del resto del catálogo con dosis mínimas efectivas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Peldaño actual con carga real; reducción de asistencias; mantenimiento de élite', 'note' => 'Reduce asistencia antes de aumentar volumen', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Test parcial del peldaño, fresco y primer ejercicio de su sesión; si se supera → siguiente peldaño desbloqueado; mantenimiento', 'note' => 'Nunca intentar el TP al final de la sesión', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Movilidad protagonista; dosis mínimas; ventana de rotación para cambiar de especialización', 'note' => 'Fase más importante del bloque infinito; decide las próximas décadas de entrenamiento', 'intensity' => '50%'],
            ],
        ];
    }

    private static function achievementsL12(): array
    {
        return [
            [
                'code' => 'v4-n12-full-front-lever',
                'name' => '¡FULL FRONT LEVER!',
                'description' => 'Full front lever de 5 s con las piernas juntas: una de las dos cimas estáticas del programa. Años de línea FL condensados en un hold: celebración máxima.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Full front lever',
                'xpReward' => 200,
            ],
            [
                'code' => 'v4-n12-full-planche',
                'name' => '¡FULL PLANCHE!',
                'description' => 'Full planche de 3 s: la cima de la línea planche y el estático más exigente para el bíceps del programa. Celebración máxima.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Full planche',
                'xpReward' => 200,
            ],
            [
                'code' => 'v4-n12-hspu-libre',
                'name' => 'HSPU LIBRE',
                'description' => 'HSPU sin pared: la fuerza del empuje vertical y el equilibrio libre unidos en un solo gesto. El skill que define el nivel.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'HSPU libre (sin pared)',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n12-press-to-handstand',
                'name' => 'PRESS TO HANDSTAND',
                'description' => 'Press to handstand en pike: subir al pino desde el suelo sin impulso, pura compresión y hombros.',
                'category' => 'skill', 'icon' => '🚀', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Press to handstand (pike)',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n12-one-arm-push-up',
                'name' => 'ONE-ARM PUSH-UP',
                'description' => 'One-arm push-up completa por lado con cero rotación: la cima del empuje horizontal unilateral.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'One-arm push-up',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n12-dragon-pistol',
                'name' => 'Dragon pistol',
                'description' => 'Dragon pistol squat: la pierna libre dibuja el arco completo sin tocar el suelo. Fuerza y movilidad de pierna en un solo gesto.',
                'category' => 'skill', 'icon' => '🐉', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dragon pistol squat',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-tobillo-retest-g3',
                'name' => 'Tobillo re-verificado (G3)',
                'description' => 'Re-test de dorsiflexión superado: knee-to-wall ≥10 cm con simetría, seguro del dragon pistol.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Re-test G3 — Dorsiflexión de tobillo',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-compresion-retest-g5',
                'name' => 'Compresión re-verificada (G5)',
                'description' => 'Re-test de compresión superado: piernas extendidas despegadas 10 s, listo para el V-sit de N13.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Re-test G5 — Compresión de cadera',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-pike-retest-g6',
                'name' => 'Pike re-verificado (G6)',
                'description' => 'Re-test de pike superado: palmas completas al suelo, seguro del press y del straddle press de N13.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Re-test G6 — Pike / isquiosurales',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-straddle-planche-8-10s',
                'name' => 'Straddle planche 8-10 s',
                'description' => 'Straddle planche de 8-10 s consolidado: el combustible del full, peldaño §1.6 previo a los intentos libres. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '📐', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-full-planche-banda',
                'name' => 'Full planche asistida con banda',
                'description' => 'Full planche con banda a la cadera y piernas juntas: el laboratorio donde se gana el estático libre. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🎗️', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-straddle-fl-8s',
                'name' => 'Straddle front lever 8 s',
                'description' => 'Straddle front lever de 8 s: la consolidación que precede siempre al full FL con banda. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🛡️', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-full-fl-banda',
                'name' => 'Full front lever asistida con banda',
                'description' => 'Full front lever con banda: la horizontal completa con piernas juntas, ensayada antes del estático libre. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⚓', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-v-sit-asistido',
                'name' => 'V-sit asistido',
                'description' => 'V-sit asistido (una mano, una pierna o manos apoyadas): peldaño previo del V-sit de N13. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🪑', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-bandera-vertical',
                'name' => 'Bandera vertical / support press laterales',
                'description' => 'Bandera vertical con apoyo o support press laterales a ambos lados: primer peldaño del human flag de N13. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '🚩', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n12-bulgarian-dip-rto',
                'name' => 'Bulgarian dip / ring dip profundo RTO',
                'description' => 'Fondo profundo en anillas con RTO completo o Bulgarian dip: continuidad hacia el impossible dip de N15. Se desbloquea en sesión.',
                'category' => 'skill', 'icon' => '⭕', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // ========================================================================
    // LEVEL 13 — FUERZA UNILATERAL
    // ========================================================================

    private static function achievementsL13(): array
    {
        return [
            [
                'code' => 'v4-n13-dominada-mas-25pct-bw',
                'name' => 'Dominada +25% BW',
                'description' => 'Dominada lastrada con el 25% del peso corporal añadido: fuerza absoluta de tracción a dos brazos.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominada lastrada +25% BW',
                'xpReward' => 75,
            ],
            [
                'code' => 'v4-n13-l-sit-pull-up',
                'name' => 'L-sit pull-up',
                'description' => 'Dominada en posición L-sit: tracción y compresión de cadera simultáneas.',
                'category' => 'skill', 'icon' => '🪑', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'L-sit pull-up',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-human-flag',
                'name' => '¡HUMAN FLAG!',
                'description' => 'Bandera humana horizontal: uno de los estáticos laterales más espectaculares de la calistenia.',
                'category' => 'skill', 'icon' => '🚩', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Human flag',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n13-v-sit',
                'name' => 'V-sit',
                'description' => 'V-sit limpio con piernas extendidas y compresión máxima de cadera.',
                'category' => 'skill', 'icon' => '🪑', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'V-sit',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-straddle-press',
                'name' => 'STRADDLE PRESS',
                'description' => 'Press to handstand desde straddle: compresión, hombros y apertura en un solo gesto.',
                'category' => 'skill', 'icon' => '🚀', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Straddle press to handstand',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-nordic-curl',
                'name' => 'Nordic curl',
                'description' => 'Nordic curl completo: control excéntrico total de la cadena posterior.',
                'category' => 'skill', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Nordic curl completo',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-bandera-45',
                'name' => 'Bandera a 45°',
                'description' => 'Bandera humana a 45° consolidada en ambos lados: peldaño clásico hacia la horizontal.',
                'category' => 'skill', 'icon' => '🚩', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-negativa-un-brazo-5s',
                'name' => 'Negativa a un brazo 5 s',
                'description' => 'Bajada controlada de 5 s a un brazo: peldaño adelantado de la vía one-arm.',
                'category' => 'skill', 'icon' => '⬇️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-hs-lower-straddle-planche-hspu-deficit',
                'name' => 'Handstand lower to straddle planche / HSPU con déficit',
                'description' => 'Descenso controlado desde pino a straddle planche, o HSPU con déficit: primer peldaño hacia el 90° push-up.',
                'category' => 'skill', 'icon' => '⬇️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n13-adv-tuck-straddle-planche-pu',
                'name' => 'Adv tuck/straddle planche PU',
                'description' => 'Planche push-up desde tuck avanzado o straddle asistido: cierre de la escalera planche dinámica.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 14 — ONE-ARM CHIN-UP
    // ========================================================================

    private static function achievementsL14(): array
    {
        return [
            [
                'code' => 'v4-n14-one-arm-chin-up',
                'name' => '¡ONE-ARM CHIN-UP!',
                'description' => 'Dominada completa a un brazo en supinación: la cima unilateral de la tracción.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'One-arm chin-up',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n14-planche-push-up',
                'name' => 'PLANCHE PUSH-UP',
                'description' => 'Flexión en planche completa: la dinámica más dura de la línea de apoyo.',
                'category' => 'skill', 'icon' => '📐', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Planche push-up',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n14-90-push-up',
                'name' => '90° PUSH-UP',
                'description' => '90° push-up completo: desde handstand hasta planche y de vuelta, hombro en su máxima flexión.',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => '90° push-up',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n14-front-lever-raises',
                'name' => 'Front lever raises',
                'description' => 'Elevaciones controladas hasta front lever: dominio dinámico de la horizontal.',
                'category' => 'skill', 'icon' => '🛡️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Front lever raises',
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-mas-50pct-bw',
                'name' => '+50% BW',
                'description' => 'Dominada lastrada con el 50% del peso corporal añadido: techo de fuerza a dos brazos.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominada lastrada +50% BW',
                'xpReward' => 75,
            ],
            [
                'code' => 'v4-n14-90-push-up-asistido',
                'name' => '90° push-up asistido',
                'description' => '90° push-up con banda a la cadera: primer peldaño de la escalera hacia el libre.',
                'category' => 'skill', 'icon' => '🎗️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-90-push-up-parcial',
                'name' => '90° push-up parcial',
                'description' => '90° push-up en medio rango: control del tramo más exigente del recorrido.',
                'category' => 'skill', 'icon' => '➖', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-tiger-bend-hold-negativo',
                'name' => 'Tiger bend hold / negativo',
                'description' => 'Apoyo sobre antebrazos desde handstand o negativo controlado: base del tiger bend.',
                'category' => 'skill', 'icon' => '🐯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-impossible-dip-asistido',
                'name' => 'Impossible dip asistido',
                'description' => 'Impossible dip con banda generosa: primer peldaño del fondo más profundo en anillas.',
                'category' => 'skill', 'icon' => '⭕', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-cross-hold-banda',
                'name' => 'Cross hold con banda',
                'description' => 'Hold en cruz con banda generosa: primer contacto con la escalera del iron cross.',
                'category' => 'skill', 'icon' => '⚜️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-ring-planche-lean-maltese',
                'name' => 'Ring planche lean / maltese',
                'description' => 'Proyección máxima en anillas con banda: base de la planche de anillas y el maltese.',
                'category' => 'skill', 'icon' => '🦅', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'v4-n14-dolphin-forearm-stand',
                'name' => 'Dolphin / forearm stand',
                'description' => 'Equilibrio sobre antebrazos o dolphin pose: base de muñeca y codo para el tiger bend.',
                'category' => 'skill', 'icon' => '🐬', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 15 — ONE-ARM PULL-UP
    // ========================================================================

    private static function achievementsL15(): array
    {
        return [
            [
                'code' => 'v4-n15-one-arm-pull-up',
                'name' => '¡ONE-ARM PULL-UP!',
                'description' => 'Dominada completa a un brazo en pronación: la celebración máxima del programa cerrado.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'One-arm pull-up (prona)',
                'xpReward' => 200,
            ],
            [
                'code' => 'v4-n15-mas-50pct-x3',
                'name' => '+50% ×3',
                'description' => 'Tres repeticiones limpias de dominada lastrada con el 50% del peso corporal.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Dominada lastrada +50% BW',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n15-tiger-bend',
                'name' => 'TIGER BEND',
                'description' => 'Tiger bend push-up completo: descenso y empuje desde antebrazos en handstand.',
                'category' => 'skill', 'icon' => '🐯', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Tiger bend push-up',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n15-impossible-dip',
                'name' => 'IMPOSSIBLE DIP',
                'description' => 'Impossible dip completo en anillas: el fondo más profundo y exigente del programa.',
                'category' => 'skill', 'icon' => '⭕', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Impossible dip',
                'xpReward' => 100,
            ],
            [
                'code' => 'v4-n15-iron-cross-asistido',
                'name' => 'Iron cross asistido',
                'description' => 'Iron cross con banda mínima: la puerta de entrada a la cruz completa.',
                'category' => 'skill', 'icon' => '⚜️', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Iron cross asistido (banda mínima)',
                'xpReward' => 200,
            ],
            [
                'code' => 'v4-n15-maltese-asistido',
                'name' => 'Maltese asistido',
                'description' => 'Maltese con banda: la evolución definitiva de la planche sobre anillas.',
                'category' => 'skill', 'icon' => '🦅', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Maltese asistido (banda)',
                'xpReward' => 200,
            ],
            [
                'code' => 'v4-n15-tiger-bend-asistido',
                'name' => 'Tiger bend asistido',
                'description' => 'Tiger bend push-up asistido con banda o rango parcial: peldaño previo al completo.',
                'category' => 'skill', 'icon' => '🎗️', 'tier' => Achievement::TIER_POCO_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 16 — OLIMPO
    // ========================================================================

    private static function achievementsL16(): array
    {
        $achievements = [];

        $rangeSkills = [
            ['key' => 'iron-cross', 'name' => 'Iron cross', 'icon' => '⚜️'],
            ['key' => 'maltese', 'name' => 'Maltese', 'icon' => '🦅'],
            ['key' => 'oap-multiple', 'name' => 'One-arm pull-up múltiple', 'icon' => '💪'],
            ['key' => 'lastre-dominada', 'name' => 'Dominada lastrada', 'icon' => '🏋️'],
            ['key' => '90-push-up', 'name' => '90° push-up', 'icon' => '🔥'],
            ['key' => 'planche-push-up', 'name' => 'Planche push-up', 'icon' => '📐'],
            ['key' => 'hspu-libre', 'name' => 'HSPU libre', 'icon' => '🤸'],
            ['key' => 'human-flag', 'name' => 'Human flag', 'icon' => '🚩'],
            ['key' => 'front-lever', 'name' => 'Front lever', 'icon' => '🛡️'],
        ];
        $ranges = [
            'Bronce' => ['key' => 'bronce', 'tier' => Achievement::TIER_COMUN, 'xp' => 50],
            'Plata' => ['key' => 'plata', 'tier' => Achievement::TIER_POCO_COMUN, 'xp' => 75],
            'Oro' => ['key' => 'oro', 'tier' => Achievement::TIER_RARO, 'xp' => 100],
            'Platino' => ['key' => 'platino', 'tier' => Achievement::TIER_EPICO, 'xp' => 150],
            'Diamante' => ['key' => 'diamante', 'tier' => Achievement::TIER_LEGENDARIO, 'xp' => 200],
        ];

        foreach ($rangeSkills as $skill) {
            foreach ($ranges as $rangeName => $rangeData) {
                $achievements[] = [
                    'code' => 'v4-n16-'.$skill['key'].'-'.$rangeData['key'],
                    'name' => $skill['name'].' — '.$rangeName,
                    'description' => 'Rango '.$rangeName.' de '.$skill['name'].' en Olimpo.',
                    'category' => 'skill', 'icon' => $skill['icon'], 'tier' => $rangeData['tier'], 'type' => Achievement::TYPE_HITO,
                    'specialCelebration' => false,
                    'requirementType' => null, 'requirementKey' => null,
                    'xpReward' => $rangeData['xp'],
                ];
            }
        }

        $combos = [
            ['code' => 'v4-n16-mu-fl', 'name' => 'MU + FL', 'icon' => '🔗', 'description' => 'Muscle-up en barra enlazado a front lever sin soltar.'],
            ['code' => 'v4-n16-giro-completo', 'name' => 'GIRO COMPLETO', 'icon' => '🔄', 'description' => 'Giro completo alrededor de la barra (360° / giant).'],
            ['code' => 'v4-n16-la-cadena-real', 'name' => 'LA CADENA REAL', 'icon' => '👑', 'description' => 'Handstand → press → planche encadenados sin tocar el suelo.'],
            ['code' => 'v4-n16-bandera-total', 'name' => 'BANDERA TOTAL', 'icon' => '🚩', 'description' => 'Human flag 3+ s ejecutado a ambos lados en la misma sesión.'],
            ['code' => 'v4-n16-coreografo', 'name' => 'COREÓGRAFO', 'icon' => '🎭', 'description' => 'Secuencia libre de ≥4 skills encadenados sin tocar el suelo.'],
        ];
        foreach ($combos as $combo) {
            $achievements[] = [
                'code' => $combo['code'],
                'name' => $combo['name'],
                'description' => $combo['description'],
                'category' => 'style', 'icon' => $combo['icon'], 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ];
        }

        $hitoLegendarios = [
            ['code' => 'v4-n16-iron-cross-completo', 'name' => 'Iron cross completo', 'icon' => '⚜️', 'tier' => Achievement::TIER_LEGENDARIO, 'xp' => 200],
            ['code' => 'v4-n16-maltese-completo', 'name' => 'Maltese completo', 'icon' => '🦅', 'tier' => Achievement::TIER_LEGENDARIO, 'xp' => 200],
            ['code' => 'v4-n16-oap-x2', 'name' => 'OAP ×2', 'icon' => '💪', 'tier' => Achievement::TIER_EPICO, 'xp' => 150],
            ['code' => 'v4-n16-oap-x3', 'name' => 'OAP ×3', 'icon' => '💪', 'tier' => Achievement::TIER_LEGENDARIO, 'xp' => 200],
            ['code' => 'v4-n16-tu-peso-encima', 'name' => 'TU PESO ENCIMA (+100%)', 'icon' => '🏋️', 'tier' => Achievement::TIER_LEGENDARIO, 'xp' => 200],
            ['code' => 'v4-n16-dominada-mas-75pct', 'name' => 'Dominada +75%', 'icon' => '🏋️', 'tier' => Achievement::TIER_EPICO, 'xp' => 150],
            ['code' => 'v4-n16-90-push-up-x3', 'name' => '90° push-up ×3', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'xp' => 150],
            ['code' => 'v4-n16-planche-push-up-x10', 'name' => 'Planche push-up ×10', 'icon' => '📐', 'tier' => Achievement::TIER_EPICO, 'xp' => 150],
            ['code' => 'v4-n16-hspu-libre-x10', 'name' => 'HSPU libre ×10', 'icon' => '🤸', 'tier' => Achievement::TIER_EPICO, 'xp' => 150],
        ];
        foreach ($hitoLegendarios as $hito) {
            $achievements[] = [
                'code' => $hito['code'],
                'name' => $hito['name'],
                'description' => $hito['name'].' desbloqueado en sesión de Olimpo.',
                'category' => 'skill', 'icon' => $hito['icon'], 'tier' => $hito['tier'], 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => $hito['xp'],
            ];
        }

        return $achievements;
    }

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

    /** Notas de los ejercicios principales de N7. */
    private const EXERCISE_NOTES_L7 = [
        'Tuck Planche Toe Support' => 'Brazos rectos, proyección máxima, rodillas al pecho: las puntas solo equilibran, no empujan. Si los dedos se clavan, hay demasiado peso atrás.',
        'Tuck Planche' => 'Gate conservador §1.6: cada intento corto y perfecto vale más que uno largo y roto; meta 5 s limpios. Molestia de bíceps distal = peldaño atrás (banda o puntas).',
        'Band Assisted Tuck Planche' => 'Trabajo técnico ligero con banda generosa y forma perfecta (la pesada va en A). En deload: mantenimiento sin fatiga.',
        'Advanced Tuck Front Lever' => 'Espalda paralela al suelo en tuck, retroversión pélvica constante, brazos rectos; si la espalda cae, se recorta el hold, no se "aguanta feo". En F3 consolida: el pico de bíceps es de la planche.',
        'Tuck Front Lever' => 'Regresión activa de deload: cero fatiga, técnica perfecta.',
        'L-Sit' => 'Brazos rectos, hombros deprimidos, piernas extendidas y juntas; intentos completos aunque sean 1-2 s al inicio de cada serie. Si falla, series mixtas L-sit + tuck avanzado.',
        'L-Sit One Leg' => 'Peldaño hacia el L-sit: una pierna extendida o alternas; empuja el suelo lejos y deprime los hombros.',
        'Tuck L-Sit' => 'Peldaño de entrada (avanzado): rodillas al pecho, una pierna extendida si se puede; en deload es la versión cómoda del L-sit.',
        'Skin The Cat' => 'Siempre caliente, anillas a altura que permita tocar el suelo con los pies; ida y vuelta controladas = 1 rep; nunca forzar el punto de máxima extensión.',
        'German Hang' => 'Libre y escalonado: progresión de segundos (10→20 s), respiración continua, hombros relajados; pinzamiento anterior = volver al asistido.',
        'Clap Push Up' => 'Primero, en fresco: el trabajo balístico y su recepción se entrenan sin fatiga de hombro. Despegue claro, palmada delante del pecho, aterrizaje con codos amortiguando.',
        'Explosive Push Up' => 'Peldaño del clap push-up: flexión explosiva con despegue de manos; cuerpo rígido durante el vuelo.',
        'Elevated Pike Push Up' => 'Pies en cajón, cadera alta, cabeza entre las manos (colchoneta o parallettes); codos a ~45°. Sube la altura del cajón solo con 10 reps limpias. Hito del nivel camino al HSPU.',
        'Ring Push Up' => 'Turnout estricto arriba, cuerpo en línea, pecho a la altura de las anillas; meta de test 8-12.',
        'Pseudo Planche Push Up' => 'Manos junto a la cadera, hombros por delante de las manos; aumenta el lean antes que las reps.',
        'Box Pistol Squat' => 'Baja controlado 2-3 s hasta tocar el cajón (nunca desplomarte), sube sin impulso con el talón pegado. La altura del cajón baja ciclo a ciclo: 5/pierna en el cajón alto = hito.',
        'Single Leg Romanian Deadlift' => 'Hito del nivel: cadera cuadrada, rango sin redondear la lumbar; la carga (mochila) sube +2-4 kg por fase.',
        'Hanging Leg Raise' => 'Colgado activo, piernas juntas y extendidas hasta la horizontal, bajada controlada de 2-3 s sin balanceo: camino de compresión hacia el L-sit.',
        'Jefferson Curl' => 'Movilidad con carga ligera (mochila 2-5 kg): enrolla vértebra a vértebra, lento y controlado, sin rebote. Pista de la puerta G6.',
        'Seated Pike Stretch' => 'Con los pies en cajón (pista G6); alarga desde la cadera, tensión en isquios, no en lumbar.',
        'Strict Pull Up' => 'Rango completo, sin balanceo: mantén la base de 5+.',
        'Ring Row - Elevated Feet' => 'Contrapeso horizontal del volumen de apoyos; eleva más los pies o añade mochila para progresar.',
        'False Grip Ring Row' => 'Mantenimiento de la línea de falso agarre: muñeca sobre la anilla, pecho a las anillas.',
        'False Grip Support Hold' => 'Mantenimiento del falso agarre en apoyo; baja antes de que el agarre se degrade.',
        'Chest to Wall Handstand' => 'Mantenimiento de la línea vertical (testada en N5): cuerpo en línea, respiración continua.',
    ];

    /** Notas de los ejercicios principales de N8. */
    private const EXERCISE_NOTES_L8 = [
        'Advanced Tuck Planche' => 'Desde la tuck planche de N7, abre la cadera gradualmente (banda o puntas, cada vez menos asistencia). Gate conservador §1.6: 5 s limpios; progresa por apertura y asistencia, nunca por intentos máximos. En F3 rota el pico por ciclo (impares).',
        'Ring Dip' => 'Protagonista F1-F2, pico de volumen en F2 y MANTIENE en F3 (§1.7: nunca intensifica junto al SBD). Profundidad: hombro a la altura del codo, nunca más abajo; turnout arriba.',
        'Straight Bar Dips' => 'Solo técnico en F1-F2 con profundidad limitada (hombro al nivel del codo); pica en F3 en los ciclos pares (rotación modelo N14). Cuerpo detrás de la barra, transición de peso hacia delante arriba: puente mecánico al muscle-up.',
        'Chest to Bar Pull Up' => 'Pecho a la barra de verdad (no el cuello), sin pataleo; pocas reps, máxima velocidad, descanso largo. Si la velocidad cae, termina la serie. Pico en F3: meta 3-5.',
        'Chest to Bar Practice' => 'Dominadas explosivas (peldaño C2B a media altura): velocidad máxima de salida y bajada controlada; si no llega al pecho, máxima altura posible.',
        'Archer Pull Up' => 'El brazo recto guía pero no empuja; alterna lados y empieza por el débil. Si sale torcido, más banda y menos ego. Meta de test: 2/lado.',
        'Weighted Ring Row' => 'Filas pesadas con mochila (o archer rows): contrapeso horizontal pesado permanente del nivel press-dominante (6-10 series por microciclo).',
        'Ring Row - Elevated Feet' => 'Segundo bloque de tracción horizontal; pecho a las anillas.',
        'False Grip Hang' => 'Camino al hito 10-15 s (F2): muñeca sobre la anilla, hombros activos, nunca en frío. Molestia en la cara anterior de la muñeca = reduce volumen ese ciclo.',
        'False Grip Ring Row' => 'Pecho a las anillas manteniendo el falso agarre profundo.',
        'Explosive Dips' => 'Hito del nivel: subida balística, bajada controlada (despegue de manos opcional); series cortas con descanso suficiente.',
        'Tuck Back Lever' => 'Entrada siempre por skin the cat (usa la puerta G4); brazos rectos pegados al cuerpo, rodillas al pecho, cero pinzamiento anterior. Asistencia decreciente (banda o pies); en F3 consolida 8-10 s sin máximos.',
        'Box Pistol Squat' => 'Último peldaño antes del pistol libre: el cajón bajo casi no quita rango; toca sin desplomarte, talón pegado. Cajón a su altura mínima en F3.',
        'Pistol Squat Negative' => 'Hito del nivel (junto al cajón bajo): bajada lenta de 5 s hasta el fondo, talón pegado y rodilla alineada; sube con ayuda si hace falta.',
        'Eccentric Calf Raise' => 'Nuevo en N8: bajada de 3 s por debajo del nivel del escalón, una pierna; sube con las dos. Acondiciona el tobillo para la dorsiflexión extrema unilateral del pistol.',
        'Dragon Flag' => 'Versión tuck (rodillas al pecho): cuerpo rígido apoyado solo en hombros/cuello alto, bajada sin colapsar la lumbar. Primer peldaño del dragon flag de N9.',
        'Dragon Flag Negative' => 'Bajada de 3-4 s desde la vertical con el cuerpo extendido; apoyo en hombros, nunca en el cuello. Si la lumbar se arquea, vuelve al tuck.',
        'Advanced Tuck Front Lever' => 'Mantenimiento de la línea FL (hito 10-12 s en F3): consolida el objetivo de N7 y la deja viva para el one-leg de N9.',
        'Chest to Wall Handstand' => 'Camino al hito 30-40 s (F2): consolida el objetivo de N5 y mantiene viva la línea vertical.',
        'German Hang' => 'Mantenimiento de la línea de extensión (base del back lever): 10-20 s según fase.',
        'Frog Stretch' => 'Apertura straddle progresiva: 30-40 s con respiración profunda, sin rebotes.',
        'Straddle Fold' => 'Plegaria sentado en straddle: la flexión nace de la cadera, rodillas y puntas al techo.',
        'Adductor Rockback' => 'Movilidad activa de aductores: balanceo lento atrás con la pierna extendida al lado, sin rebotes.',
    ];

    /** Notas de los ejercicios principales de N9. */
    private const EXERCISE_NOTES_L9 = [
        'Bar Muscle Up' => 'Intentos completos: series de 1 rep, frescos y espaciados (3-4 min). Si el pecho no supera la barra, vuelve a C2B alto esa serie; 3 fallos seguidos = cerrar con transiciones. Meta: 1 rep limpia (gate) → 2 (objetivo).',
        'Assisted Muscle Up Transition' => 'La pieza central del MU: banda sobre la barra (quita el 20-30%) o barra baja con salto controlado; codos de abajo a arriba, lento y técnico; practica también la bajada. Reduce la asistencia entre ciclos.',
        'Muscle Up Negative' => 'Desde el apoyo sobre la barra, baja 3-4 s por el camino del MU: la transición también se aprende de vuelta.',
        'Chest to Bar Pull Up' => 'C2B alto: pecho sobre la barra, cadera a la barra; el MU nace de la altura, no solo del contacto.',
        'Straight Bar Dips' => 'Desde N9 se abre el rango completo (hombro por debajo del codo con control). En F3 MANTIENE: el pico de codo es del MU (§1.7).',
        'One Leg Front Lever Hold' => 'Desde el adv tuck, extiende una pierna (alterna cada serie): espalda paralela, retroversión pélvica, la pierna extendida empuja la línea. Asegura el gate de 5 s sin buscar máximos.',
        'Advanced Tuck Back Lever' => 'Abre la cadera gradualmente desde el tuck BL; entrada por skin the cat; hito 5-8 s en F2, luego se conserva (anti-coincidencia con el MU). Pinzamiento = German hang ese ciclo.',
        'HSPU Negative' => 'Parallettes o colchoneta entre las manos: el cuello no toca el suelo jamás. Bajada uniforme (parcial 3-4 s → completa 4-6 s), codos a ~45°. Hito en F2; técnica consolidada en F3.',
        'Advanced Tuck Planche' => 'Modo mantenimiento-consolidación camino al hito de 10 s (F2); la línea planche se mantiene viva sin exigirla en test.',
        'Elevated Pike Push Up' => 'Cajón alto: base del HSPU de N10.',
        'Ring Push Up' => 'Con pausa abajo: mantenimiento de anillas.',
        'Pistol Squat' => 'Pico del nivel junto al MU: al inicio de la ronda, fresco. Meta 1/pierna (gate) → 3 (objetivo). Talón pegado, bajada controlada, arranque sin impulso.',
        'Assisted Pistol Squat' => 'Cajón muy bajo o apoyo de un dedo: la G3 está superada, se acabó el margen; equilibrio, no fuerza bruta.',
        'Box Pistol Squat' => 'Regresión de deload al cajón bajo.',
        'Bulgarian Split Squat' => 'Accesorio de fuerza unilateral para el arranque del pistol (alternativa: step-up alto).',
        'Dragon Flag' => 'Completo: cuerpo en línea apoyado en hombros (nunca en el cuello), baja hasta rozar la horizontal sin tocar el banco. Primeras reps en F2, pico en F3 (meta 3-5).',
        'Dragon Flag Negative' => 'Excéntrico largo de 4-5 s: peldaño previo a las reps completas; dolor lumbar = regresa aquí.',
        'False Grip Hang' => 'Camino al hito 15-18 s (F2): continuidad de la línea hacia el gate de 20 s de N10.',
        'False Grip Ring Row' => 'Con pausa de 2 s con el pecho en las anillas (hito del nivel en camino).',
        'Strict Pull Up' => 'Mantenimiento de la dominada estricta (§1.3): la base que el lastre de niveles posteriores necesita.',
        'Weighted Ring Row' => 'Contrapeso horizontal de la sesión de tracción.',
        'German Hang' => 'Mantenimiento de la línea de extensión.',
        'Standing Pike Stretch' => 'Puerta G6: de pie, piernas extendidas, palmas completas al suelo con pausa; ensáyala tal como se evaluará.',
        'Seated Pike Stretch' => 'Alternativa de la puerta G6: pecho a muslos sentado con agarre de pies.',
        'Jefferson Curl' => 'Pike intensificado con carga (5-8 kg en N9): lento y segmentario.',
        'Cuban Rotation' => 'Nuevo desde N9 (era overhead): brazo en abducción de 90°, rota el antebrazo de abajo a arriba con banda o 1-3 kg; codo fijo a la altura del hombro. Es la póliza del trabajo overhead: nunca se recorta.',
    ];

    /** Notas de los ejercicios principales de N10. */
    private const EXERCISE_NOTES_L10 = [
        'Handstand Push Up' => 'HSPU en pared (test 3/5): parallettes o colchoneta entre las manos, la coronilla roza sin cargar peso, codos a ~45°, extensión completa. Primeras reps completas en F2; nunca con el cuello fatigado.',
        'HSPU Negative' => 'Continuidad del peldaño de N9: en F1 es el trabajo pesado del empuje vertical, bajada uniforme con el cuello siempre libre de carga. Si los últimos centímetros se atrancan, trabaja medias reps en esa zona en lugar de más intentos completos.',
        'Elevated Pike Push Up' => 'Base de fuerza del HSPU: cajón alto y rango completo; la altura sube solo con las reps limpias. En intensificación cede volumen al HSPU.',
        'Advanced Tuck Planche' => 'Pico del nivel (hito 10-12 s en F3) con series recortadas por convivencia con el HSPU; opción de cadera abierta como pre-peldaño del straddle de N11. Molestia matinal de bíceps >2/10 = congelar planche un ciclo.',
        'Pseudo Planche Push Up' => 'En F3 solo mantenimiento (anti-coincidencia con el pico de planche): la proyección manda sobre las reps.',
        'Archer Push Up' => 'Test del nivel (3/5 por lado): el brazo asistente extendido solo equilibra, torso sin rotación, pecho cerca de la mano que trabaja. Puente hacia la one-arm de N12.',
        'Bar Muscle Up' => 'De la conquista (N9) a la marca: 3/5 reps frescas y sin kip, la segunda rep sale del impulso limpio de la primera. Rotación de picos por ciclo en F3: impares marca de MU, pares gate de falso agarre.',
        'Archer Pull Up' => 'Camino del hito 3-4/lado (F2): la vía unilateral que alimentará el typewriter de N11. El brazo recto guía pero no empuja; empieza por el lado débil.',
        'Weighted Ring Row' => 'Contrapeso horizontal pesado permanente (6-10 series por microciclo): no negociable en un nivel press-dominante.',
        'Archer Ring Row' => 'Segunda pieza del contrapeso horizontal: un brazo tira y el otro guía extendido, cuerpo en línea sin rotar.',
        'Strict Pull Up' => 'Mantenimiento de la base de dominada (§1.3): reps limpias y sin balanceo.',
        'False Grip Hang' => 'Gate del nivel: 20 s (objetivo 30) con la muñeca SOBRE la anilla, hombros activos y respiración nasal; se testea fresco en los ciclos pares de F3. Si falla, casi nunca es espalda: hangs cortos y frecuentes + wrist curls, nunca máximos a diario.',
        'One Leg Front Lever Hold' => 'Camino del hito 8 s (F2): consolida el gate de N9 y deja la línea lista para el straddle FL de N11. Retroversión pélvica constante; la pierna extendida empuja la línea.',
        'Pistol Squat' => 'Marca de test 5/pierna en F3 (pico del circuito C): talón pegado, bajada controlada, arranque sin impulso.',
        'Shrimp Squat' => 'Nuevo en N10 (logro de hito): la rodilla trasera roza el suelo sin golpearlo y el talón delantero pegado; primero asistida, libre al final del ciclo.',
        'Nordic Curl Negative' => 'Introducción conservadora: 4-6 excéntricas totales por sesión en F1, la carga la marca el rango; en F3, bajada de 5 s a rango completo (logro de hito). Puente al Nordic curl de N13.',
        'Dragon Flag' => 'Continuidad del anti-extensión: 5-8 reps en el circuito, cuerpo en línea apoyado en hombros (nunca en el cuello), lumbar sellada.',
        'Seated Leg Raise' => 'Mantenimiento de la compresión (G5): 15-25 s en el circuito, lumbar neutra.',
        'Handstand Bail Out' => 'La pirueta de salida a ambos lados, automática e innegociable antes de soltar la pared: se entrena hasta que es reflejo, no habilidad.',
        'Freestanding Handstand' => 'Test del nivel (5/10 s): línea recta, corrección con dedos y hombros; solo cuentan holds con salida controlada. Si no pasa de 2-3 s, el fallo es de dedos: vuelve al salvado con dedos contra pared.',
        'Pike Press Negative' => 'Test del nivel (3/5 reps, bajada 5 s): cadera alta el máximo tiempo, codos ceden sincronizados, sin caída libre. Si colapsa, es compresión, no hombros: cajón y pike.',
        'Straddle Back Lever' => 'Peldaño del full BL de N11: entrada siempre por skin the cat, horizontal con apertura, cero pinzamiento anterior. En F3 consolida 6-8 s sin récord (anti-coincidencia con el pico de planche).',
        'False Grip Pull Up' => 'Hito del nivel en F2 (primera dominada en falso agarre): con banda o pies en cajón al inicio; peldaño directo del muscle-up en anillas de N11.',
        'L-Sit' => 'Camino del hito 10-15 s: brazos rectos y hombros deprimidos; la línea de compresión que desembocará en el V-sit de N13.',
        'German Hang' => 'Re-test G4 integrado en el deload: 15 s asistido sin dolor ni pinzamiento; si falla, ciclo corto de movilidad sin repetir fuerza — el full BL de N11 lo exige.',
        'Single Leg Romanian Deadlift' => 'Mantenimiento de la bisagra unilateral en el bloque de cadena posterior: cadera cuadrada, espalda neutra.',
        'Planche Lean' => 'Sustituto de la adv tuck en deload: proyección máxima sin fatiga del estático.',
    ];

    /** Notas de los ejercicios principales de N11. */
    private const EXERCISE_NOTES_L11 = [
        'Straddle Planche' => 'Gate conservador §1.6 (test 5/8 s): escalera puntas → banda → cerrado → abierto; la banda se reduce antes de aumentar la apertura. Única exposición pesada de planche del microciclo (sesión A); dolor matinal de bíceps = congelar un ciclo.',
        'Straddle Front Lever Hold' => 'Test 5/8 s: desde el one-leg de N10, apertura mínima al inicio y progresiva por ciclos, retroversión pélvica activa. Si el hold se desploma, cierra 5 cm la apertura antes que añadir intentos. Única pesada de FL (sesión B).',
        'Ring Muscle Up' => 'La cima del nivel (test 1/2): falso agarre profundo revisado en cada serie, jalón explosivo, transición continua sin kip, fondo con giro externo. Siempre fresco y primero en D: un intento tras el full BL es un intento muerto.',
        'Ring Muscle Up Transition' => 'Drill de patrón con anillas bajas y pies en el suelo: codos atrás-arriba y pecho sobre las manos con ayuda mínima de piernas; 4×2-3 perfectas, no es trabajo de fatiga. Reduce la ayuda de pies ciclo a ciclo.',
        'Back Lever Hold' => 'Full back lever (test 5/8 s): entrada siempre por skin the cat/German hang, horizontal sin pike ni arco lumbar. Única pesada de BL del microciclo; pinzamiento anterior = German hang y regresión a straddle un ciclo.',
        'Typewriter Pull Up' => 'Test 3/5: barbilla sobre la barra durante todo el viaje lateral, simetría estricta (el volumen lo marca el lado débil) y lado de inicio alterno por serie. Asimetría = banda, no reps feas.',
        'Skater Squat' => 'Hito del nivel: la pierna libre viaja atrás hasta rozar el suelo con la rodilla (cojín en las primeras exposiciones), talón delantero pegado, sin rebote. Base del dragon pistol de N12.',
        'Cossack Squat with Load' => 'Cossack con mochila/mancuerna: apertura straddle bajo carga, la otra mitad del hito de pierna del nivel. La carga solo sube si la técnica es idéntica a la versión libre.',
        'One Arm Push Up' => 'Versión asistida con la mano en superficie elevada (peldaño del test de N12): cuerpo en tabla, cero rotación de cadera; la progresión es bajar la superficie, no la velocidad.',
        'Handstand Push Up' => 'Camino del hito 8 reps en pared (prerequisito de fuerza del HSPU libre de N12): rango completo y técnica limpia; en deload, técnica sin fatiga.',
        'Freestanding Handstand' => 'Camino del hito 15 s (prerequisito de equilibrio del HSPU libre de N12): salida en pirueta siempre disponible; en F4 es juego de equilibrio.',
        'Ring Dip' => 'Hito ×10 o lastre +10% ×5-6: profundidad hombro-codo con control y giro externo completo arriba en cada rep. Continuidad hacia el impossible dip de N15.',
        'False Grip Hang' => 'Consolidación de la profundidad del agarre (15-20 s): ligero-moderado en B — el pico de falso agarre vive en D con el MU. En F3, mantenimiento ligero del gate de N10.',
        'False Grip Ring Row' => 'Filas con falso agarre y pecho a las anillas: profundidad de agarre más retracción, siempre ligero-moderadas.',
        'Archer Ring Row' => 'Contrapeso horizontal 1/2 del nivel press-dominante (6-10 series por microciclo).',
        'Weighted Ring Row' => 'Contrapeso horizontal 2/2: filas con pies elevados y mochila.',
        'Chest to Bar Pull Up' => 'Dominada explosiva opcional (§5): potencia que alimenta el typewriter y el MU; series cortas a máxima velocidad, si la velocidad cae, termina la serie.',
        'Pistol Squat' => 'Mantenimiento en el circuito (2-3/pierna): la fuerza de pierna ya testada no se enfría.',
        'Nordic Curl Negative' => 'Continuidad N9-N11: bajada de 4-5 s hasta rango casi completo en F2 y completo en F3.',
        'Dragon Flag' => 'En circuito, 5-8 reps: cuerpo en línea apoyado en hombros, nunca en el cuello.',
        'L-Sit' => '10-15 s limpios; opción §5: ring L-sit como hito. Empuja el suelo lejos y deprime los hombros.',
        'Advanced Tuck Planche' => 'Regresión ligera de la línea en deload: sin straddle esta fase, técnica perfecta sin fatiga.',
        'One Leg Front Lever Hold' => 'Regresión ligera de la línea FL en deload.',
        'Pseudo Planche Push Up' => 'Mantenimiento dinámico ligero de la proyección.',
        'Single Leg Romanian Deadlift' => 'Bloque de cadena posterior: bisagra unilateral con cadera cuadrada.',
        'Straddle Fold' => 'Foco nuevo del nivel (§1.8): straddle bajo tensión — pancake activo con elevaciones alternas de piernas; la apertura ya no es solo movilidad, es posición de trabajo.',
        'German Hang' => 'Mantenimiento crítico de la G4 (full BL este nivel): asistido en el Bloque 0 de B y D, siempre antes del back lever.',
    ];

    /** Notas de los ejercicios principales de N12. */
    private const EXERCISE_NOTES_L12 = [
        'Planche' => 'Full planche, cima de la línea (test 3/5 s): intentos frescos y primeros de la sesión con 4 min de descanso; cada intento cuenta, sin grinders. Si no pasa de 1-2 s, la respuesta es más straddle de calidad, no más full. Molestia matinal de bíceps >2/10 = ciclo congelado.',
        'Full Front Lever Hold' => 'Cima estática (test 5/8 s): la primera serie es un hold libre al máximo limpio con objetivo ≥5 s, el resto acumula con banda; retroversión y retracción innegociables. Si se desploma, vuelve al straddle de 8 s.',
        'Straddle Planche' => 'Consolidación del hito 8-10 s: el combustible del full; precede siempre al primer full con banda y no se abandona aunque el full llegue.',
        'Straddle Front Lever Hold' => 'Consolidación del hito 8 s: la base que precede siempre al full FL con banda.',
        'Freestanding Handstand Push Up' => 'HSPU libre (test 1/3): solo con los dos prerequisitos vivos (HS libre 15 s + HSPU pared 8); estabiliza 1-2 s antes de bajar, la rep cuenta con equilibrio mantenido o pirueta limpia. Rotación de pico por ciclo en D: impares marca de HSPU, pares contenido.',
        'Handstand Push Up' => 'Peldaño con despegues (un pie / roce mínimo de pared): la fuerza de N11 empieza a unirse al equilibrio; el roce es la red de seguridad, no el motor.',
        'Pike Press to Handstand' => 'Press en pike (test 1/2): la compresión hace el 80% del trabajo, cadera alta y brazos que empujan desde el primer grado; se apoya en las G5/G6 re-verificadas. Rotación de pico: pares marca de press, impares contenido. Si no despega, más V-sit y compresión, no más intentos.',
        'Pike Press Negative' => 'Mantenimiento de la línea press (bajada 4-5 s): la base nunca se pierde.',
        'Freestanding Handstand' => 'Mantenimiento del hito de N11 y recalibración de equilibrio entre intentos de HSPU; en F4, juego de equilibrio sin HSPU.',
        'One Arm Push Up' => 'Test 3/5 por lado: pies abiertos, cero rotación de cadera/torso, rango completo. Una rep limpia vale más que cinco con rotación; si rota, vuelve a la asistida en superficie baja.',
        'Dragon Pistol Squat' => 'Test 3/5: la pierna libre dibuja el arco completo por detrás sin tocar el suelo, talón del apoyo pegado. Progresión asistida → excéntrica libre → completa; exige la G3 viva y el acondicionamiento de gemelo de las sesiones C.',
        'Pistol Squat' => 'Mantenimiento en circuito (2-3/pierna).',
        'V-Sit Hold' => 'Versión asistida (manos apoyadas / una pierna), hito del nivel y peldaño del V-sit de N13: pecho alto, lumbar neutra, las manos asisten, no empujan. Se apoya en la G5.',
        'Vertical Flag' => 'Hito del nivel (10-20 s, ambos lados desde la primera sesión): el brazo inferior empuja y el superior tracciona, línea lateral estricta; primer peldaño del human flag de N13. Progresa por tiempo, no por ángulo.',
        'Bulgarian Ring Dip' => 'Hito del nivel (alternativa al ring dip profundo RTO): rango máximo SIN dolor anterior, giro externo completo arriba, sin rebote en el fondo; el rango se gana en ciclos, no en sesiones. Camino al impossible dip de N15.',
        'Ring Dip' => 'Fondo profundo RTO como variante del hito de fondos; en deload, rango cómodo sin fatiga.',
        'Nordic Curl Negative' => 'Excéntrica completa con asistencia mínima al final (tirón con banda en F2): puente al Nordic curl de N13.',
        'Dragon Flag' => 'Mantenimiento en circuito (5-8 reps).',
        'Archer Ring Row' => 'Contrapeso horizontal 1/2: pesado permanente (6-10 series por microciclo).',
        'Weighted Ring Row' => 'Contrapeso horizontal 2/2: filas lastradas con pies elevados.',
        'Strict Pull Up' => 'Mantenimiento (§1.3): la base viva que el lastre de N13 necesita.',
        'Typewriter Pull Up' => 'Mantenimiento ligero: la vía unilateral no se enfría antes de N13.',
        'Scapular Pull Up' => 'Salud escapular de la línea FL: brazos rectos, solo se mueven las escápulas.',
        'Single Leg Romanian Deadlift' => 'Bloque de cadena posterior: bisagra unilateral con cadera cuadrada.',
        'Eccentric Calf Raise' => 'Acondicionamiento del tobillo para la dorsiflexión extrema bajo carga del dragon pistol: nunca se recorta en las sesiones C.',
        'Knee-to-Wall Rocks' => 'Re-test G3 en el test del nivel: ≥10 cm sin despegar el talón y simetría <2 cm; ensáyalo tal como se evaluará.',
        'Seated Leg Raise' => 'Re-test G5 en el test del nivel: piernas extendidas y juntas despegadas 10 s, manos solo apoyo ligero, lumbar neutra.',
        'Standing Pike Stretch' => 'Re-test G6 en el test del nivel: palmas completas al suelo con piernas extendidas (o pecho a muslos sentado).',
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
        'Frog Stretch' => 'Rodillas abiertas y pies juntos, cadera atrás con respiración profunda; apertura progresiva sin rebotes.',
        'Straddle Fold' => 'Sentado en straddle, pliega desde la cadera con la espalda larga; rodillas y puntas al techo.',
        'Adductor Rockback' => 'Pierna extendida al lado, cadera atrás con control; movilidad activa sin rebotes.',
        'Standing Pike Stretch' => 'Piernas extendidas, pliega desde la cadera buscando las palmas completas al suelo; sin rebotes.',
        'Jefferson Curl' => 'Enrolla vértebra a vértebra con carga ligera, lento y sin rebote; movilidad con carga, no fuerza.',
        'Eccentric Calf Raise' => 'Bajada de 3 s por debajo del escalón sobre una pierna; sube con las dos. Sin rebote en el fondo.',
        'Hanging Leg Raise' => 'Colgado activo, piernas juntas hasta la horizontal, bajada controlada sin balanceo.',
        'Air Squat' => 'Talón pegado; profundidad cómoda; rodillas siguen la punta de los pies.',
        'Jumping Jacks' => 'Pulsos ligeros de activación general: ritmo suave y aterrizaje silencioso.',
        'Back-of-Hand Stretch' => 'Dorso de la mano en el suelo, dedos hacia ti; estiramiento leve-moderado de 20-30 s, nunca dolor.',
        'Wrist Curl' => 'Antebrazo apoyado, palma arriba, carga muy ligera; rango completo sin rebote.',
        'Reverse Wrist Curl' => 'Palma abajo, misma dosis que el curl; equilibra flexores y extensores.',
        'Wrist Extension Stretch' => 'Palma en suelo con dedos hacia las rodillas; extensión progresiva sin dolor.',
        'Seated Leg Raise' => 'Compresión sentada con piernas extendidas; lumbar neutra, manos solo apoyo ligero.',
        'Fingertip Hold' => 'Plancha sobre las yemas, las cinco cargando parejo; dosificación conservadora.',
        'Chest to Wall Handstand' => 'Pecho a pared, dedos abiertos, costillas abajo; salida siempre controlada en pirueta.',
        'Shoulder Dislocate' => 'Agarre amplio con banda o palo; arco completo sin doblar codos ni dolor.',
        'German Hang' => 'Extensión de hombro en anillas, asistida con los pies si hace falta; nunca en frío ni con pinzamiento.',
        'Pike Push Up' => 'Cadera alta, cabeza entre las manos, codos a ~45°; activación del empuje vertical.',
        'Scapular Pull Up' => 'Brazos rectos: solo se mueven las escápulas; activación de la tracción.',
        'Band Monster Walk' => 'Rodillas semiflexionadas, banda tensa todo el recorrido; activación de glúteo medio.',
        'Dumbbell Romanian Deadlift' => 'Bisagra de cadera con carga ligera: espalda neutra, isquios tensos.',
    ];
}
