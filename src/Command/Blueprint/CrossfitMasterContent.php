<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa CrossFit Master v1 (niveles 1-6).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_crossfit_niveles_01-03.md y
 * docs/detalle_crossfit_niveles_04-06.md.
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
            4 => [
                'session_a' => 'El wall ball es sentadilla + press de piernas + throw: si CF1 o CF2 no están hoy, la bola baja de peso o de diana. La puerta manda sobre el ego.',
                'session_b' => 'La primera estricta se construye despacio: negativas de 5-6 s, banda cada vez más fina, y paciencia. Primero estricto, luego dinámico — el kipping de N5 no llega sin esta base (§1.7-CrossFit §10.1).',
                'session_c' => 'Tracción en metcon = agarre primero: si los antebrazos explotan antes que la espalda, alterna pull-up con box jump over y vuelve. Rondas completas > reps heroicas.',
                'session_d' => 'Cindy escalada de F2 es el ensayo general del test: anota rondas y escalado usado. Y en el T2B manda el core, no el columpio — todavía.',
            ],
            5 => [
                'session_a' => 'Hoy el RDL trabaja la bisagra que el test N6 te pedirá bajo fatiga (CF7): si la lumbar se redondea en la serie, termina la serie. Calidad por encima de kilos.',
                'session_b' => 'El power clean no se gana con la espalda: es extensión de cadera + tirón rápido + recepción. Con la barra ligera de hoy construye el gesto que N6 cargará y N10+ pesará (§1.7-CrossFit §10.2).',
                'session_c' => 'Annie escalada de F3 es un test de ritmo, no de héroe: los singles deben ser automáticos para que los dobles de F2-F3 de las próximas sesiones salgan solos.',
                'session_d' => 'El T2B con kipping nace del hollow: si al acabar el AMRAP no puedes mantener un Hollow Body Hold de 20 s, el kipping era columpio, no técnica.',
            ],
            6 => [
                'session_a' => 'El push jerk es el S2OH que el N7 usará con carga: hoy se aprende con barra ligera. Lumbar neutra, codos rápidos, y si la muñeca en recepción protesta >2/10 matinal, se congela el gesto (§10.3).',
                'session_b' => 'Clean y jerk en el mismo metcon es la letra pequeña de todos los WODs RX que vienen: hoy se practica la transición con cargas que perdonan.',
                'session_c' => 'Helen RX de F2 es el hito del tramo entero: si las pull-ups se rompen técnicamente a mitad, la marca RX no cuenta — escala a banda y deja constancia. Primero estricto, luego dinámico, y siempre técnica (§1.7-CrossFit §10.1/§10.4).',
                'session_d' => 'Cierra el tramo como empezaste: movilidad densa, carries honestos y un metcon que se disfruta. El test de N6 es una celebración del trabajo de N1 a N6, no un examen sorpresa.',
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

    // ========================================================================
    // LEVEL 4 — PRIMERA TRACCIÓN
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): back squat 50/35 de trabajo, primer Bench Press, negativas de 5 s + banda en tracción, primeros Knee Raise; wall ball ligero (6/4 kg) y Box Jump bajo en metcons.',
                'Fase 2 (Progresión): banda de asistencia más fina o primeras Pull Up sueltas; Hanging Leg Raise → primeros Toes to Bar Strict; primer AMRAP de 12\' con wall ball; ensayo de CF2/CF3 en Bloque 0.',
                'Fase 3 (Intensificación): back squat 60/40 ensayando el test; volumen de tracción estricta moderado (tope 15-20 reps totales por sesión); Toes to Bar Strict en AMRAP; Cindy escalada como hito de sesión D.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF2/CF3 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit: primero estricto, luego dinámico — el kipping de N5 exige base estricta (objetivo de 5 dominadas); en N4 no hay kipping. Nada balístico sobre un hombro sin base.',
                'Puertas CF2 y CF3: se evalúan en el test de este nivel (requisito efectivo del N5: Power Clean, wall ball a diana, S2OH). Ensayos integrados en Bloque 0 de las sesiones A/B (CF2) y B/C (CF3). Si fuerza superada + puerta fallada → mobility_blocked (ciclo corto de movilidad).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Back squat 50/35 de trabajo; primer Bench Press', 'note' => 'Negativas de 5 s + banda; primeros Knee Raise; wall ball ligero y Box Jump bajo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Banda más fina o primeras Pull Up sueltas', 'note' => 'Hanging Leg Raise → primeros Toes to Bar Strict; primer AMRAP 12\' con wall ball', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Back squat 60/40 ensayando el test', 'note' => 'Tope 15-20 reps de tracción estricta por sesión; Cindy escalada como hito', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF2/CF3 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Pull Up (estricta)', 'minimum' => 2, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Dominada estricta limpia; admite asistencia mínima con banda fina si aún no enlaza 2 limpias, pero el logro de primera dominada exige banda cero; el objetivo de 5 es la base mínima que N5 exige antes del kipping (§10.1)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Back Squat (60/40 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad con talones pegados (CF1); si la lumbar se va, baja carga antes que profundidad', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Toes to Bar Strict', 'minimum' => 5, 'target' => 10, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Contacto pies-barra sin balanceo de kipping; si no llega, Hanging Leg Raise + toque a la altura posible', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Cindy (benchmark escalado)', 'minimum' => 8, 'target' => 12, 'unit' => 'rounds', 'direction' => 'gte', 'form' => 'Cindy AMRAP 20\' en Scaled: 5 Band Assisted Pull Up (o Jumping Pull Up controlado) + 10 Push Up + 15 Air Squat por ronda; marca con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Cindy'],
                        ['name' => 'Puerta CF2 — Overhead (hombro)', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Shoulder Dislocate con banda/PVC ×15 sin compensar la lumbar; Overhead Squat con PVC/barra vacía ×10 en profundidad', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF2'],
                        ['name' => 'Puerta CF3 — Muñeca en front rack', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Front rack con barra vacía 30 s/lado sin dolor ni desviación activa', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            [
                'code' => 'cf-n4-primera-dominada-estricta',
                'name' => 'Primera dominada estricta',
                'description' => 'Primera dominada estricta libre (banda cero) en el test de Nivel 4. Celebra la primera dominada libre de la vida del usuario.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pull Up (estricta)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n4-sentadilla-60-40',
                'name' => 'Sentadilla de 60/40 kg',
                'description' => 'Supera el mínimo de Back Squat a 60/40 kg (5 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Back Squat (60/40 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-primer-t2b-test',
                'name' => 'Primer T2B estricto de test',
                'description' => 'Supera el mínimo de Toes to Bar Strict (5 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Toes to Bar Strict',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-cindy-registrada',
                'name' => 'Cindy escalada registrada',
                'description' => 'Registra Cindy en modalidad Scaled (mínimo 8 rondas) dentro de un envío de test del Nivel 4.',
                'category' => 'progress', 'icon' => '🏅', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Cindy (benchmark escalado)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-puerta-cf2',
                'name' => 'Puerta CF2 superada',
                'description' => 'Supera la puerta de movilidad CF2 (Overhead, hombro) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF2 — Overhead (hombro)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-puerta-cf3',
                'name' => 'Puerta CF3 superada',
                'description' => 'Supera la puerta de movilidad CF3 (Muñeca en front rack) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF3 — Muñeca en front rack',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-banda-fina',
                'name' => 'Banda fina, dominada cerca',
                'description' => 'Primer Band Assisted Pull Up con la banda más fina disponible, recorrido completo.',
                'category' => 'skill', 'icon' => '🎽', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-negativa-6s',
                'name' => 'Negativa de 6 segundos',
                'description' => 'Primer Negative Pull Up con bajada de 6 s controlada.',
                'category' => 'skill', 'icon' => '⏳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-primer-t2b',
                'name' => 'Primer Toes to Bar',
                'description' => 'Primer Toes to Bar Strict con contacto pies-barra, sin balanceo.',
                'category' => 'skill', 'icon' => '🏅', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-primer-wall-ball',
                'name' => 'Primer wall ball',
                'description' => 'Primer Wall Ball a diana con sentadilla profunda válida (CF1).',
                'category' => 'skill', 'icon' => '🏐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-primer-bench',
                'name' => 'Primer bench press',
                'description' => 'Primer Bench Press con barra de 30/20 kg y técnica validada.',
                'category' => 'skill', 'icon' => '🛋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5 — MOTOR Y GIMNÁSTICOS
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): power clean de técnica con 30/20 kg; kipping en bloques cortos (tope 20 reps/sesión) solo si hay ≥5 estrictas; Double Under Practice en dosis de 10-15 intentos; T2B estricto consolidado.',
                'Fase 2 (Progresión): power clean 40/25 kg; primeros EMOM con kipping pull-up; primeros sets de 2-3 Double Unders; primer AMRAP de 15\'; Annie escalada como hito de sesión C.',
                'Fase 3 (Intensificación): power clean 50/35 kg ensayando el gesto del test N6; kipping en AMRAP con tope de volumen; Double Unders en singles sueltos dentro de WODs cortos; ensayo de test integrado.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (kipping condicionado): el kipping pull-up solo con ≥5 estrictas validadas; si el test N4 salió por debajo, sigue en progresión estricta (Band Assisted Pull Up → Pull Up) hasta conseguirlas. Volumen de kipping por sesión limitado a 20-30 reps, con al menos una sesión completa entre exposiciones intensas; dolor de hombro matinal >2/10 = congelar kipping un ciclo.',
                'Puertas: ninguna evaluada en N5. Se siembra CF6 (Wrist Push-Up con más volumen + apoyo en extensión) y CF7 (bisagra bajo fatiga en WODs con swing); se evalúan en N6.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Power clean técnico 30/20 kg; T2B estricto consolidado', 'note' => 'Kipping corto (tope 20 reps/sesión) solo con ≥5 estrictas; Double Under Practice 10-15 intentos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Power clean 40/25 kg; primeros EMOM con kipping', 'note' => 'Primeros sets de 2-3 Double Unders; primer AMRAP 15\'; Annie escalada como hito', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Power clean 50/35 kg ensayando el gesto del test N6', 'note' => 'Kipping en AMRAP con tope de volumen; ensayo de test integrado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Kipping Pull Up', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Kipping pull-up con arco controlado; si llegaste a N5 sin las 5 estrictas de base, este ítem se evalúa como Pull Up estricta ×5-8 y el kipping sigue bloqueado hasta superarlo (§10.1)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Toes to Bar', 'minimum' => 12, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Toes to Bar con kipping controlado nacido del hollow; si el balanceo sustituye al core, se valida como Toes to Bar Strict', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Power Clean (40/25 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción en power (sin squat clean) y front rack sin dolor (CF3); si la técnica se rompe, la rep no cuenta aunque se levante (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Annie (benchmark escalado)', 'minimum' => 960, 'target' => 720, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Annie FOR_TIME en Scaled: 50-40-30-20-10 de Single Unders + Sit-up; marca con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Annie'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            [
                'code' => 'cf-n5-kipping-de-10',
                'name' => 'Kipping de 10',
                'description' => 'Supera el mínimo de Kipping Pull Up (10 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🌀', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Kipping Pull Up',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-t2b-de-12',
                'name' => 'T2B de 12',
                'description' => 'Supera el mínimo de Toes to Bar (12 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Toes to Bar',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-power-clean-test',
                'name' => 'Primer power clean de test',
                'description' => 'Supera el mínimo de Power Clean a 40/25 kg (5 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Power Clean (40/25 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-annie-registrada',
                'name' => 'Annie escalada registrada',
                'description' => 'Registra Annie en modalidad Scaled (mínimo 16:00) dentro de un envío de test del Nivel 5.',
                'category' => 'progress', 'icon' => '🏃', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Annie (benchmark escalado)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-primer-kipping',
                'name' => 'Primer kipping pull-up',
                'description' => 'Primer Kipping Pull Up conectado, arco controlado, sin dolor de hombro. Solo sobre base estricta (≥5 dominadas).',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n5-primer-doble',
                'name' => 'Primer doble',
                'description' => 'Primer Double Under conectado (1-2 seguidos cuentan).',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n5-dobles-de-5',
                'name' => 'Dobles de 5',
                'description' => 'Primer set de 5 Double Unders sin fallo.',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-t2b-amrap',
                'name' => 'T2B kipping en AMRAP',
                'description' => 'Primer AMRAP completado con Toes to Bar (kipping) sin romper el hollow.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n5-clean-tecnico',
                'name' => 'Clean técnico validado',
                'description' => 'Primer Power Clean con recepción y front rack validados por el criterio del test.',
                'category' => 'skill', 'icon' => '✅', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6 — UMBRAL RX
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): push jerk de técnica con 30/20 kg; power clean 45/30 kg; wall ball a diana de referencia (bola 9/6 kg en F3); Helen RX como hito de sesión C (primera toma de contacto, sin exigencia).',
                'Fase 2 (Progresión): push jerk 40/25 kg; power clean 50/35 kg; primer metcon con wall ball RX; Helen RX repetida como hito con objetivo de mejora de marca.',
                'Fase 3 (Intensificación): jerk + clean a cargas de test; ensayo de CF6/CF7 a dosis máximas; Helen RX como último ensayo antes del test; volumen de tracción dinámica alto pero acotado.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit: O-lifts y deadlifts pesados nunca en fatiga alta; el Power Clean de N5-N6 es técnico y con barra ligera-moderada; dolor matinal >2/10 = congelar el gesto un ciclo. En WODs con carga, cuando falla la forma se baja la dosis (§10.4).',
                'Puertas CF6 y CF7: se evalúan en el test de este nivel (requisito efectivo del N7: HSPU, handstand, deadlifts en fatiga, O-lifts). Ensayos integrados: bloque muñeca CF6 en Bloque 0 de todas las sesiones (dosis máximas en F3) y bisagra bajo fatiga en los WODs con swing (sesiones A/C).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Push jerk técnico 30/20 kg; power clean 45/30 kg', 'note' => 'Wall ball a diana de referencia; Helen RX como hito (sin exigencia)', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Push jerk 40/25 kg; power clean 50/35 kg', 'note' => 'Primer metcon con wall ball RX; Helen RX repetida con objetivo de mejora', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Jerk + clean a cargas de test', 'note' => 'Ensayo de CF6/CF7 a dosis máximas; Helen RX como último ensayo', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Power Clean (50/35 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción y front rack sin dolor y sin lumbar rota (CF3/CF7); rep con técnica rota no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Push Jerk (45/30 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Impulso de piernas + caída bajo la barra con codos rápidos; bloqueo overhead estable (CF2); si el jerk se convierte en press roto, la rep no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Wall Ball (9/6 kg)', 'minimum' => 15, 'target' => 25, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Wall ball a diana de referencia RX con sentadilla profunda válida (CF1/CF2); si el throw se acorta, baja el peso de la bola', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Helen (benchmark RX)', 'minimum' => 1200, 'target' => 960, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Helen en modalidad RX (scalingUsed = rx): 3 rondas de 400 m Run + Kettlebell Swing Russian ×21 (24/16 kg) + Pull Up ×12; admite kipping controlado o estricta, sin banda; primer benchmark RX completo del programa', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Helen'],
                        ['name' => 'Puerta CF6 — Muñeca en extensión', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Wrist Push-Up 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF6'],
                        ['name' => 'Puerta CF7 — Bisagra lumbar controlada', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Dead Bug 2×10/lado + Bird Dog 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF7'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            [
                'code' => 'cf-n6-power-clean-50-35',
                'name' => 'Power clean de 50/35 kg',
                'description' => 'Supera el mínimo de Power Clean a 50/35 kg (5 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Power Clean (50/35 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-push-jerk-45-30',
                'name' => 'Push jerk de 45/30 kg',
                'description' => 'Supera el mínimo de Push Jerk a 45/30 kg (5 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Push Jerk (45/30 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-wall-ball-rx-15',
                'name' => 'Wall ball RX de 15',
                'description' => 'Supera el mínimo de Wall Ball a 9/6 kg a diana RX (15 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🏐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Wall Ball (9/6 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n6-primer-wod-rx',
                'name' => 'Primer WOD RX',
                'description' => 'Registra Helen en modalidad RX dentro de un envío de test del Nivel 6. Celebra el primer benchmark completo en RX del usuario.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Helen (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n6-puerta-cf6',
                'name' => 'Puerta CF6 superada',
                'description' => 'Supera la puerta de movilidad CF6 (Muñeca en extensión) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF6 — Muñeca en extensión',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-puerta-cf7',
                'name' => 'Puerta CF7 superada',
                'description' => 'Supera la puerta de movilidad CF7 (Bisagra lumbar controlada) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF7 — Bisagra lumbar controlada',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-primer-push-jerk',
                'name' => 'Primer push jerk',
                'description' => 'Primer Push Jerk con recepción bajo la barra y bloqueo overhead estable.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n6-clean-jerk-ligero',
                'name' => 'Clean & jerk ligero',
                'description' => 'Primer metcon completado con Power Clean + Push Jerk en la misma sesión (EMOM de sesión B F2).',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n6-helen-rx-registrada',
                'name' => 'Helen RX registrada',
                'description' => 'Primera ejecución completa de Helen en RX en sesión (hito de sesión C F2, antes del test).',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n6-muneca-extension-60s',
                'name' => 'Muñeca de extensión 60 s',
                'description' => 'Primer apoyo en extensión de 60 s sin dolor matinal >2/10 (ensayo CF6).',
                'category' => 'skill', 'icon' => '🖐️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }
}
