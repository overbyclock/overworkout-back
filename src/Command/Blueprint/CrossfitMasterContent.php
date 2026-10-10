<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa CrossFit Master v1 (niveles 1-3).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_crossfit_niveles_01-03.md.
 *
 * Los nombres de los ítems de testRequirements se usan como requirementKey
 * de los logros desbloqueados por ítem (deben coincidir EXACTAMENTE).
 *
 * Los ítems de test de tipo benchmark llevan 'benchmarkName' (nombre en el
 * catálogo) en lugar de 'benchmarkId': el comando resuelve el id en ejecución.
 */
class CrossfitMasterContent
{
    public static function getLevelContent(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => self::level1(),
            2 => self::level2(),
            3 => self::level3(),
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
                'session_a' => 'Fuerza básica',
                'session_b' => 'Skill con barra',
                'session_c' => 'Activación técnica',
                'session_d' => 'Fuerza mixta',
                default => 'Fuerza y skill',
            },
            TrainingRound::BLOCK_WOD => 'WOD / Metcon',
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab de hombro',
                'session_b' => 'Prehab de muñeca',
                'session_c' => 'Prehab lumbar y core',
                'session_d' => 'Prehab de trío',
                default => 'Prehab',
            },
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
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, torácica, tobillo, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s.',
            TrainingRound::BLOCK_FUERZA_SKILL => 'Trabajo principal de fuerza y técnica de la sesión.',
            TrainingRound::BLOCK_WOD => 'WOD / Metcon de la sesión. El escalado es decisión del usuario en el player y también es protección (§1.7-CrossFit §10.4).',
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (hombro, muñeca, lumbar/core o trío). Calidad por encima de fatiga; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => 'Vuelta a la calma: respiración diafragmática, Child\'s Pose y estiramiento suave de la zona trabajada.',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (docs/detalle_crossfit_niveles_01-03.md, sección de cada sesión).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'La sentadilla y el hinge de hoy son la base de todo lo que viene: si la técnica se rompe, baja la carga, no las repes buenas (§1.7-CrossFit).',
                'session_b' => 'Barra vacía es un peso de verdad: si el press o el deadlift de hoy salen redondos, el cuerpo ya está aprendiendo lo que el N3 pedirá con carga (§1.2 encadenamiento).',
                'session_c' => 'En el metcon manda el ritmo que te deja repetir bien: rondas lentas y limpias > rondas rápidas y feas. El escalado también es protección (§1.7-CrossFit §10.4).',
                'session_d' => 'Los carries son el prehab más honesto que existe: si no puedes caminar erguido con la carga, todavía no la llevas a la barra.',
            ],
            2 => [
                'session_a' => 'El swing no es un squat frontal: es una bisagra. Si las rodillas dominan el gesto, vuelve al Kettlebell Romanian Deadlift pausado del Bloque 0.',
                'session_b' => 'El push press es el primer gesto dinámico del programa: la potencia sale de las piernas, no de la espalda baja. Lumbar neutra o no cuenta (§1.7-CrossFit §10.2).',
                'session_c' => 'El EMOM enseña a gestionar el reloj: termina las reps rápido y usa el resto del minuto para respirar. Rondas completas > reps heroicas.',
                'session_d' => 'La bisagra se entrena también cansado: en F3 mira que la lumbar no se redondee al final del AMRAP; ahí es donde CF5 se gana o se pierde.',
            ],
            3 => [
                'session_a' => 'Hoy la barra pesa lo que pesa: si CF1 no está (sentadilla profunda con talones pegados), el back squat se queda en goblet. La puerta manda sobre el ego.',
                'session_b' => 'Las negativas de F2 son el primer peldaño hacia la dominada estricta de N4-N6: baja en 3 segundos sin desplomarte. Primero estricto, luego dinámico (§1.7-CrossFit §10.1).',
                'session_c' => 'Fran escalada de F3 es el ensayo general del test: anota el tiempo y el escalado usado, y compáralo con las referencias del catálogo.',
                'session_d' => 'Cindy escalada enseña lo que un AMRAP largo pide: repartir, no fundirse. Anota tus rondas; en N4-N6 volverá como test.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Puertas de movilidad CF1-CF7 del programa (spec §8).
     * N1 no evalúa ninguna; N2 evalúa CF5; N3 evalúa CF1 y CF4.
     * CF2/CF3/CF6/CF7 se definen ahora para lotes N4-N6 y posteriores.
     */
    public static function getMobilityGates(): array
    {
        return [
            [
                'code' => 'CF1',
                'name' => 'Sentadilla profunda pegada',
                'description' => 'Puerta de movilidad de sentadilla. Se evalúa en el Nivel 3; exigida a partir de N4 (squat cargado progresivo).',
                'criteria' => 'Deep Squat Hold 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor',
            ],
            [
                'code' => 'CF2',
                'name' => 'Overhead (hombro)',
                'description' => 'Puerta de movilidad de hombro overhead. Se evalúa en el Nivel 4 (re-test N7); exigida a partir de N5 (press, S2OH, wall ball a diana).',
                'criteria' => 'Shoulder Dislocate con banda/PVC ×15 sin compensar la lumbar; Overhead Squat con PVC/barra vacía ×10 en profundidad',
            ],
            [
                'code' => 'CF3',
                'name' => 'Muñeca en front rack',
                'description' => 'Puerta de movilidad de muñeca en front rack. Se evalúa en el Nivel 4 (re-test N7); exigida a partir de N5 (cleans).',
                'criteria' => 'Front rack con barra vacía 30 s/lado sin dolor ni desviación activa',
            ],
            [
                'code' => 'CF4',
                'name' => 'Dorsiflexión de tobillo',
                'description' => 'Puerta de movilidad de tobillo. Se evalúa en el Nivel 3; exigida a partir de N4 (lunges, wall ball).',
                'criteria' => 'Calf Stretch Wall + Bent-Knee Soleus Stretch 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón',
            ],
            [
                'code' => 'CF5',
                'name' => 'Cadera/bisagra',
                'description' => 'Puerta de movilidad de bisagra de cadera. Se evalúa en el Nivel 2; exigida a partir de N3 (deadlift con carga, KB swing progresivo).',
                'criteria' => 'Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada',
            ],
            [
                'code' => 'CF6',
                'name' => 'Muñeca en extensión',
                'description' => 'Puerta de movilidad de muñeca en extensión. Se evalúa en el Nivel 6 (re-test N9); exigida a partir de N7 (HSPU, handstand).',
                'criteria' => 'Wrist Push-Up 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10',
            ],
            [
                'code' => 'CF7',
                'name' => 'Bisagra lumbar controlada',
                'description' => 'Puerta de control lumbar en bisagra. Se evalúa en el Nivel 6 (re-test N9); exigida a partir de N7 (O-lifts, deadlifts en fatiga).',
                'criteria' => 'Dead Bug 2×10/lado + Bird Dog 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra',
            ],
        ];
    }

    // ========================================================================
    // LEVEL 1 — ON-RAMP
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): aprende los patrones (sentadilla, bisagra, empuje vertical, core) con PVC/barra vacía y KB ligero. Aquí no se falla: se aprende. Técnica por encima de volumen.',
                'Fase 2 (Progresión): sube reps o carga mínima (KB +2-4 kg); primer AMRAP de 8\' y primeros intervalos cortos.',
                'Fase 3 (Intensificación): calidad máxima con la carga más alta del nivel; ensayo de test integrado en las sesiones A y D.',
                'Fase 4 (Recuperación): mitad de volumen e intensidad; movilidad protagonista; el test de nivel se ejecuta al final, descansado.',
                'Regla §1.7-CrossFit: en N1 todo es estricto, no hay kipping; nada balístico sobre un hombro sin base estricta. El deadlift es técnico y de carga ligera-moderada; dolor matinal >2/10 = congelar el gesto un ciclo.',
                'Si fallas el test: revisa la profundidad de sentadilla (cadera bajo la rodilla, talones pegados) y la espalda neutra en la bisagra: la técnica rota no cuenta (§10.4). Repite la fase Base sin subir cargas.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Aprender patrones con PVC/barra vacía y KB ligero', 'note' => 'Variantes más fáciles; técnica por encima de volumen', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir reps o carga mínima (KB +2-4 kg)', 'note' => 'Primer AMRAP 8\'; primeros intervalos cortos', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Calidad máxima con la carga más alta del nivel', 'note' => 'Ensayo de test integrado en sesión A y D', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Air Squat', 'minimum' => 20, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad con cadera bajo la rodilla y talones pegados; rep con técnica rota no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Plank', 'minimum' => 40, 'target' => 60, 'unit' => 'seconds', 'direction' => 'gte', 'form' => 'Costillas abajo, glúteo apretado, respiración continua bajo tensión', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Goblet Squat (KB 8/6 kg)', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Cadera bajo la rodilla, talones pegados (siembra CF1); peso orientativo ±2 kg según KB disponible', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Kettlebell Deadlift (16/12 kg, técnico)', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Espalda neutra y cadera-bisagra (CF5) visible en cada rep; peso orientativo ±2 kg', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL1(): array
    {
        return [
            [
                'code' => 'cf-n1-20-air-squat',
                'name' => '20 sentadillas al aire',
                'description' => 'Supera el mínimo de Air Squat (20 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Air Squat',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-plancha-40s',
                'name' => 'Plancha de 40 segundos',
                'description' => 'Supera el mínimo de Plank (40 s) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🧱', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Plank',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primer-goblet-test',
                'name' => 'Primer Goblet Squat de test',
                'description' => 'Supera el mínimo de Goblet Squat con KB 8/6 kg (10 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Goblet Squat (KB 8/6 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-bisagra-impecable',
                'name' => 'Bisagra impecable',
                'description' => 'Supera el mínimo de Kettlebell Deadlift técnico (10 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🔩', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Kettlebell Deadlift (16/12 kg, técnico)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-sentadilla-profunda',
                'name' => 'Primera sentadilla profunda pegada',
                'description' => 'Primera Air Squat con cadera bajo la rodilla y talones en el suelo.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primer-amrap',
                'name' => 'Primer AMRAP completado',
                'description' => 'Primera sesión C con el AMRAP completo sin parar el reloj.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primera-bisagra-barra',
                'name' => 'Primera bisagra con barra',
                'description' => 'Primer Kettlebell Deadlift / deadlift con barra vacía con 3 puntos de contacto y espalda neutra validados.',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primer-press-barra',
                'name' => 'Primer press con barra',
                'description' => 'Primer Strict Press con barra vacía en línea, sin hiperextensión lumbar.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2 — PRIMER IMPULSO
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primer EMOM y primeros WODs de 2 movimientos; swings con KB de trabajo ligero (16/12 kg); técnica de bisagra bajo la puerta CF5.',
                'Fase 2 (Progresión): sube carga mínima (goblet 12/8); swing sigue en 16/12 y salta a 24/16 solo en F3; primer AMRAP 10\'; Helen escalada como hito de sesión C.',
                'Fase 3 (Intensificación): swings a 24/16 ensayando el test; WODs de 2 movimientos a ritmo alto; ensayo de test integrado en sesión A.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-CrossFit: el swing americano no se usa en fatiga alta — en test y dosis, "Kettlebell Swing" siempre significa Kettlebell Swing Russian (§10.2). Deja pasar al menos una sesión completa entre bisagras cargadas exigentes.',
                'Puerta CF5: se evalúa en el test de este nivel (requisito efectivo del N3). Re-test implícito en cada envío de test; ensayo integrado en el Bloque 0 de la sesión C.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer EMOM y primeros WODs de 2 movimientos', 'note' => 'Swings con KB 16/12 kg; técnica de bisagra bajo la puerta CF5', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir carga mínima (goblet 12/8); primer AMRAP 10\'', 'note' => 'Helen escalada como hito de sesión C', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Swings a 24/16 ensayando el test; WODs a ritmo alto', 'note' => 'Ensayo de test integrado en sesión A', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Goblet Squat (KB 12/8 kg)', 'minimum' => 15, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Cadera bajo la rodilla, talones pegados; objetivo del test con 12/8 kg', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Kettlebell Swing Russian (24/16 kg)', 'minimum' => 15, 'target' => 21, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Bisagra visible en cada rep (criterio CF5); el swing americano no se usa en test (§10.2)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Plank', 'minimum' => 60, 'target' => 90, 'unit' => 'seconds', 'direction' => 'gte', 'form' => 'Costillas abajo, glúteo apretado, respiración continua', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Helen (benchmark escalado)', 'minimum' => 1080, 'target' => 840, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Helen en modalidad Scaled: KB menor (16/12 kg) y Pull Up asistidos con banda o Ring Row - Feet on Ground; marca registrada con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Helen'],
                        ['name' => 'Puerta CF5 — Cadera/bisagra', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF5'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            [
                'code' => 'cf-n2-goblet-15',
                'name' => 'Goblet de 15',
                'description' => 'Supera el mínimo de Goblet Squat con KB 12/8 kg (15 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Goblet Squat (KB 12/8 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-plancha-60s',
                'name' => 'Plancha de 60 segundos',
                'description' => 'Supera el mínimo de Plank (60 s) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🧱', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Plank',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-primer-benchmark',
                'name' => 'Primer benchmark registrado',
                'description' => 'Registra tu primer benchmark (Helen escalada) dentro de un envío de test del Nivel 2.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Helen (benchmark escalado)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n2-primer-emom',
                'name' => 'Primer EMOM completado',
                'description' => 'Primer EMOM del nivel terminado con todas las rondas.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-swing-24-16',
                'name' => 'Swing de 24/16 kg',
                'description' => 'Primer Kettlebell Swing Russian con 24/16 kg y técnica validada (ensayo CF5).',
                'category' => 'skill', 'icon' => '🔔', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-helen-escalada',
                'name' => 'Helen escalada',
                'description' => 'Primera ejecución completa del benchmark Helen en sesión (hito de sesión, antes de ser test).',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n2-cargador-hombro',
                'name' => 'Cargador de hombro',
                'description' => 'Primer Kettlebell Halo de 10/lado sin dolor.',
                'category' => 'skill', 'icon' => '💫', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3 — CONSOLIDACIÓN
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primer back squat y front squat con barra ligera; deadlift 50/35 técnico; WODs de 2 movimientos con barra/mancuernas.',
                'Fase 2 (Progresión): subir barra +5 kg; primeras negativas de dominada; primer AMRAP de 12\'; Cindy escalada como hito de sesión D.',
                'Fase 3 (Intensificación): ensayo de test en A (deadlift 60/40 ×5, push press 30/20 ×8); Fran escalada como hito de sesión C.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF1/CF4 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit: primero estricto, luego dinámico; O-lifts y deadlifts pesados nunca en fatiga alta; si la lumbar se redondea en el push press, se valida como estricto y se anota.',
                'Puertas CF1 y CF4: se evalúan en el test de este nivel (requisito efectivo del N4). Ensayos integrados: Deep Squat Hold creciente en Bloque 0 de todas las sesiones (CF1) y bloque tobillo completo en sesión D (CF4).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer back squat y front squat con barra ligera', 'note' => 'Deadlift 50/35 técnico; WODs de 2 movimientos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir barra +5 kg; primer AMRAP 12\'', 'note' => 'Primeras negativas de dominada; Cindy escalada como hito de sesión D', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Ensayo de test en A; WODs a ritmo alto', 'note' => 'Fran escalada como hito de sesión C', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF1/CF4 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Goblet Squat (KB 16/12 kg)', 'minimum' => 20, 'target' => 25, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad CF1: cadera bajo la rodilla, talones pegados', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Deadlift (60/40 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Carga de trabajo moderada, nunca en fatiga alta (§10.2); criterio de validez = CF5 visible en cada rep', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Push Press (30/20 kg)', 'minimum' => 8, 'target' => 12, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Gesto dinámico piernas-impulso; si la lumbar se redondea al bajar, se valida como estricto y se anota', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fran (benchmark escalado)', 'minimum' => 780, 'target' => 600, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Fran en modalidad Scaled: thruster con 20-30/15-20 kg o mancuernas, Pull Up asistidos con banda o jumping pull-ups controlados; marca con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Fran'],
                        ['name' => 'Puerta CF1 — Sentadilla profunda pegada', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Deep Squat Hold 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF1'],
                        ['name' => 'Puerta CF4 — Dorsiflexión de tobillo', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Calf Stretch Wall + Bent-Knee Soleus Stretch 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            [
                'code' => 'cf-n3-goblet-20',
                'name' => 'Goblet de 16/12 kg ×20',
                'description' => 'Supera el mínimo de Goblet Squat con KB 16/12 kg (20 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Goblet Squat (KB 16/12 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-deadlift-60-40',
                'name' => 'Deadlift de 60/40 kg',
                'description' => 'Supera el mínimo de Deadlift a 60/40 kg (5 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🔩', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Deadlift (60/40 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-press-potencia',
                'name' => 'Primer press de potencia',
                'description' => 'Supera el mínimo de Push Press a 30/20 kg (8 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Push Press (30/20 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-fran-registrada',
                'name' => 'Fran escalada registrada',
                'description' => 'Registra Fran en modalidad Scaled dentro de un envío de test del Nivel 3.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Fran (benchmark escalado)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n3-sentadilla-60s',
                'name' => 'Primera sentadilla profunda de 60 s',
                'description' => 'Primer Deep Squat Hold de 60 s pegado (ensayo CF1).',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-front-squat',
                'name' => 'Sentadilla frontal ligera',
                'description' => 'Primer Front Squat con 30/20 kg y criterio de profundidad.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-cindy-escalada',
                'name' => 'Cindy escalada',
                'description' => 'Primera ejecución completa de Cindy en modalidad Scaled en sesión D.',
                'category' => 'skill', 'icon' => '🏅', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n3-puerta-cf1',
                'name' => 'Puerta CF1 superada',
                'description' => 'Supera la puerta de movilidad CF1 (Sentadilla profunda pegada) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF1 — Sentadilla profunda pegada',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n3-puerta-cf4',
                'name' => 'Puerta CF4 superada',
                'description' => 'Supera la puerta de movilidad CF4 (Dorsiflexión de tobillo) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF4 — Dorsiflexión de tobillo',
                'xpReward' => 100,
            ],
        ];
    }
}
