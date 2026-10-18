<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa CrossFit Open Prep v1 (8 semanas).
 *
 * Sin puertas y sin logros (mismo criterio que las mastery): el progreso se
 * mide con los tests semanales (legacy: 3 de 4 ítems) = el resultado del
 * Open-style WOD de la semana. Estrategia Open en tips: dividir desde el
 * principio, tiebreak temprano, redos planificados y calentamiento específico
 * por modalidad.
 */
class CrossfitOpenPrepContent
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
     * Nombre visible del bloque de sesión según su tipo (y la sesión).
     */
    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Fuerza y barbells',
                'session_b' => 'Gymnastics densidad',
                'session_c' => 'Calentamiento del Open WOD',
                'session_d' => 'Recuperación activa',
                default => 'Fuerza y técnica',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Open WOD / Redo',
                'session_d' => 'Core y prehab suave',
                default => 'WOD',
            },
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab de hombro',
                'session_b' => 'Prehab de muñeca y escápula',
                'session_c' => 'Prehab lumbar y core',
                'session_d' => 'Prehab de trío',
                default => 'Prehab',
            },
            TrainingRound::BLOCK_DESCARGA => 'Descarga',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Descripción del bloque de sesión.
     */
    public static function getBlockDescription(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, hombro, muñeca, escápula y cadera) sin descansos de fuerza: transiciones de ~5 s. El calentamiento específico de la modalidad va antes del WOD, nunca dentro.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Fuerza y cycling de barra (% de 1RM en notas): la base que sostiene el Open WOD de la semana.',
                'session_b' => 'Densidad gimnástica en EMOMs y series: repartir sin breaks es la estrategia del Open.',
                'session_c' => 'Calentamiento específico del WOD de la semana: tocar cada movimiento con dosis cortas antes del intento.',
                default => 'Recuperación activa: Z2 suave, movilidad y prehab de hombro/muñeca/lumbar.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'El Open WOD de la semana (F3) y su redo comparado (F4). Divide desde el principio, tiebreak temprano, anota rondas y reps.',
                'session_d' => 'Core y prehab suave: el tronco quieto de la semana.',
                default => 'WOD de la sesión: ritmo por rondas, no por héroes.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado: hombro, muñeca/escápula, lumbar/core o trío). Calidad por encima de fatiga; descanso 45-60 s.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de hombro y cadera. En Fase 4 se amplía con toda la movilidad de la semana.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (estrategia Open).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'El engine se construye con el ritmo que puedes repetir: la barbell de hoy es técnica de cycling, no de récord. Apunta la carga que usas.',
                'session_b' => 'Las dobles en EMOM son la prueba de fuego: 60 s sin fallo vale más que 90 s con tres paradas. Divide antes de que la cuerda mande.',
                'session_c' => 'Open WOD 1: AMRAP de dobles, box y remo. Divide las dobles 15+15 desde el primer round; el remo es recuperación activa, no sprint.',
                'session_d' => 'Recupera el hombro y la muñeca: la semana que viene hay densidad gimnástica. Z2 suave y movilidad.',
            ],
            2 => [
                'session_a' => 'Power clean y push press ligeros: el cycling que el WOD de la semana pedirá. Técnica de recepción antes que carga.',
                'session_b' => 'La densidad se entrena así: T2B y wall balls en parejas cortas sin break. El break de 10 s que planeas cuesta 30: divide antes.',
                'session_c' => 'Open WOD 2: parejas de T2B, wall ball y pull-up. Divide las dominadas 3+2 desde el round 1; anota las reps acumuladas de T2B + wall ball.',
                'session_d' => 'El gimnástico denso castiga el agarre: muñeca y antebrazo hoy, wall balls mañana.',
            ],
            3 => [
                'session_a' => 'Clean & jerk y thruster al ritmo del Open: series de 3-5 con descanso corto. La barra que no se detiene gana al atleta que levanta más.',
                'session_b' => 'Pull-ups en series planificadas: 10-15 seguidas hoy para que 5+5+5 salgan solas el sábado.',
                'session_c' => 'Open WOD 3: máximas reps de clean & jerk en 5:00. Divide en series de 3-5 desde el primer rep; el tiebreak es el ritmo, no el héroe.',
                'session_d' => 'Recupera la espalda y el agarre: el chipper de la semana que viene pide todo.',
            ],
            4 => [
                'session_a' => 'Deadlifts de 10 y front squats de 8: el chipper se gana en las estaciones grandes. Divide cada una desde el rep 1.',
                'session_b' => 'Volumen de box jump y wall ball: las piernas del chipper. Ritmo constante, sin saltos héroes.',
                'session_c' => 'Open WOD 4: el chipper estilo Filthy 50. Plan: wall balls 25+25, box jumps 20+20, remo firme, swings 15+15, deadlifts 10+10, burpees sin parar. Anota el tiempo.',
                'session_d' => 'Después del chipper, movilidad de cadera y lumbar: la semana que viene hay thrusters.',
            ],
            5 => [
                'session_a' => 'Thrusters al ritmo de Fran: la carga que puedes hacer 9 seguidas cansado es la carga del WOD. Divide 5+4 desde el round 1.',
                'session_b' => 'Pull-ups en Fran-pace: 15 seguidas hoy para que 5+4+... salgan solas. El tiebreak temprano se entrena aquí.',
                'session_c' => 'Open WOD 5: couplet thruster/pull-up a la Fran. Tiebreak tras el round 3: anota rondas y tiempo. Fran real como benchmark opcional si la plataforma lo permite.',
                'session_d' => 'Recupera el hombro: la semana del redo empieza ya.',
            ],
            6 => [
                'session_a' => 'Power clean y push jerk pesados: el ladder castiga la recepción cansada. Singles y dobles con técnica.',
                'session_b' => 'Burpees y dobles: el par que decide el ladder. Ritmo constante en los burpees, dobles sin fallo.',
                'session_c' => 'Open WOD 6: ladder ascendente con dobles y burpees. Cada ronda: barra más pesada. Anota la carga alcanzada y los burpees completados.',
                'session_d' => 'La semana del redo: movilidad y frescura son el entrenamiento.',
            ],
            7 => [
                'session_a' => 'Reactivar el couplet: thrusters y front squats ligeros. La frescura es la habilidad de la semana.',
                'session_b' => 'Pull-ups y wall balls en Fran-pace: mismo plan, menos volumen. Afilar, no construir.',
                'session_c' => 'REDO ESTRATÉGICO: mismo WOD de la semana 5, plan escrito: divide igual, transiciones más rápidas, tiebreak más temprano. La mejora se entrena, no se espera.',
                'session_d' => 'Antes del peak: hombro y muñeca al cien. La semana que viene se cierra el programa.',
            ],
            8 => [
                'session_a' => 'Peak: cargas bajas, técnica alta. El trabajo ya está hecho; hoy se afila. Thrusters al 50-60% para el ritmo.',
                'session_b' => 'Dobles y burpees frescos: el par del test final. Ritmo de sprint largo, no de pistoletazo.',
                'session_c' => 'TEST FINAL (estilo 15.5): thrusters y burpees, 8 minutos. Divide 5+4 los thrusters y ritmo constante los burpees. Anota thrusters completados y tiempo: es tu marca del programa.',
                'session_d' => 'Cierre: movilidad y respiración. El Open empieza pronto.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y semana (cargas % 1RM / kg de los WOD).
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Deadlift' => '60-70% 1RM: técnica de bisagra para el engine, no récord.',
                'Power Clean' => '50-60% 1RM: cycling ligero, recepción firme.',
                'Front Squat' => '60% 1RM: el rack del thruster que viene.',
            ],
            2 => [
                'Power Clean' => '50-55% 1RM: cycling para el gimnástico denso.',
                'Push Press' => 'Moderado: el overhead del wall ball cargado.',
                'Wall Ball Shot' => '9/6 kg a diana de referencia; divide 10+10 en el WOD.',
            ],
            3 => [
                'Clean and Jerk' => 'Open WOD a 60/40 kg: divide 3+2 desde el rep 1; anota reps totales.',
                'Thruster' => '40/30 kg al ritmo del cycling.',
                'Deadlift' => '70-75% 1RM: la fuerza del tirón.',
            ],
            4 => [
                'Deadlift' => '60/40 kg en el chipper: divide 10+10.',
                'Wall Ball Shot' => '9/6 kg: 25+25 en el chipper.',
                'Box Jump' => 'Step-down obligatorio en el chipper: no se valora el rebote.',
            ],
            5 => [
                'Thruster' => '43/30 kg (carga de Fran RX de referencia; ajustar al nivel): divide 5+4 desde el round 1.',
                'Pull Up' => 'Divide 5+4 (o 3+3+3) desde el round 1; tiebreak temprano.',
            ],
            6 => [
                'Power Clean' => 'El ladder sube por rondas (p. ej. 60→70→80→90 kg M / 40→50→55→60 kg F): anota la carga alcanzada.',
                'Push Jerk' => 'Tras el power clean del ladder: 1 rep por ronda.',
                'Double Unders' => '20 por ronda sin fallo: el fallo cuesta más que la pausa.',
            ],
            7 => [
                'Thruster' => 'Misma carga que la semana 5 (43/30 kg de referencia): mismo plan, mejores transiciones.',
                'Pull Up' => 'Splits idénticos a la semana 5: la mejora está en el tiebreak.',
            ],
            8 => [
                'Thruster' => '43/30 kg de referencia (estilo 15.5): divide 5+4.',
                'Burpee' => '15 por ronda a ritmo constante: no se aceleran hasta el último minuto.',
            ],
            default => [],
        };
    }

    // ========================================================================
    // LEVELS (semanas) — tips, fases y tests (legacy: ítems fuerza, direction gte)
    // ========================================================================

    private static function baseTips(string $focus): array
    {
        return [
            'Fase 1 (Base): '.$focus.' con volumen; la estrategia se planifica antes del WOD.',
            'Fase 2 (Progresión): sube la intensidad; las series se acortan y el ritmo sube.',
            'Fase 3 (Intensificación): el Open WOD de la semana (sesión C). Divide desde el principio, tiebreak temprano.',
            'Fase 4 (Recuperación/Redo): redo comparado o descarga; anota la marca de la semana.',
            'Estrategia Open: divide cada movimiento desde el rep 1, anota rondas/reps/tiebreak en cada intento, y calienta la modalidad específica antes del WOD (en la sesión C, nunca dentro).',
        ];
    }

    private static function weeks(int $week, string $nameBase, string $nameProg, string $nameInt, string $note): array
    {
        return [
            ['week' => 0, 'name' => 'Fase 1: Base (volumen)', 'focus' => $nameBase, 'note' => 'El plan antes que el WOD', 'intensity' => '55%'],
            ['week' => 1, 'name' => 'Fase 2: Progresión (intensidad)', 'focus' => $nameProg, 'note' => 'Series cortas, ritmo arriba', 'intensity' => '80%'],
            ['week' => 2, 'name' => 'Fase 3: Intensificación (Open WOD)', 'focus' => $nameInt, 'note' => 'Open WOD: divide desde el principio', 'intensity' => '95%'],
            ['week' => 3, 'name' => 'Fase 4: Recuperación (redo)', 'focus' => $note, 'note' => 'Redo comparado o descarga', 'intensity' => '50%'],
        ];
    }

    private static function level1(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Base aeróbica: cuerda, T2B y wall balls con volumen'),
                'Si fallas el test: el engine se construye con ritmo repetible; baja las dobles a series de 15 sin fallo y repite.',
            ],
            'trainingWeeks' => self::weeks(1, 'Base aeróbica: cuerda, T2B y wall balls con volumen', 'Intensidad: EMOMs y ritmo de AMRAP', 'OPEN WOD 1: AMRAP 12 de dobles + box + remo', 'REDO del Open WOD 1 comparado'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'AMRAP 12 (rondas)', 'minimum' => 6, 'target' => 9, 'unit' => 'rondas', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Open WOD 1 (30 dobles + 10 box jumps + 250 m de remo): rondas completas en 12 minutos; anota también el tiebreak (tiempo de la última ronda completa)'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Densidad gimnástica: T2B, wall ball y pull-up en parejas cortas'),
                'Si fallas el test: divide más desde el principio; el break largo es lo que mata las reps acumuladas.',
            ],
            'trainingWeeks' => self::weeks(2, 'Densidad gimnástica: T2B, wall ball y pull-up en parejas cortas', 'EMOMs de densidad y transiciones rápidas', 'OPEN WOD 2: AMRAP 12 de T2B + wall ball + pull-up', 'REDO del Open WOD 2 comparado'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Reps acumuladas (T2B + WB)', 'minimum' => 120, 'target' => 160, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Open WOD 2 (10 T2B + 15 wall balls + 5 pull-ups): suma de toes to bar y wall balls completados en 12 minutos'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Barbell cycling: clean & jerk y thruster ligeros en series'),
                'Si fallas el test: baja la carga 5 kg y repite el ritmo; el cycling se construye sin romper series.',
            ],
            'trainingWeeks' => self::weeks(3, 'Barbell cycling: C&J y thruster ligeros en series', 'AMRAP cortos de C&J al ritmo de Grace', 'OPEN WOD 3: máximas reps de C&J 60/40 kg en 5:00', 'REDO ligero del Open WOD 3'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Clean and Jerk (5 min)', 'minimum' => 25, 'target' => 35, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Open WOD 3: máximas repeticiones de clean & jerk en 5:00 a 60/40 kg (escalable); divide en series de 3-5 desde el principio'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Chipper: estaciones de 50-40-30-20-10 con volumen'),
                'Si fallas el test: divide cada estación a la mitad desde el rep 1; el tiempo se gana en las transiciones, no en los singles.',
            ],
            'trainingWeeks' => self::weeks(4, 'Chipper: estaciones de 50-40-30-20-10 con volumen', 'Mitades del chipper por tiempo', 'OPEN WOD 4: chipper estilo Filthy 50 por tiempo', 'REDO suave del chipper comparado'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Chipper (reps acumuladas)', 'minimum' => 150, 'target' => 200, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Open WOD 4 (chipper estilo Filthy 50, escala): 50 wall balls + 40 box jumps + 500 m de remo + 30 KB swings + 20 deadlifts + 10 burpees. Anota también el tiempo total (objetivo lte 20:00)'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => [
                ...self::baseTips('AMRAP con carga: couplet thruster/pull-up a la Fran'),
                'Si fallas el test: el tiebreak temprano lo cambia todo: divide 5+4 desde el round 1 y anota el tiempo del round 3.',
                'Fran real (21-15-9 de thruster 43/29 kg + pull-ups) como benchmark opcional si se quiere una marca comparativa en el catálogo.',
            ],
            'trainingWeeks' => self::weeks(5, 'AMRAP con carga: couplet thruster/pull-up a la Fran', 'AMRAP 10 del couplet con tiebreak temprano', 'OPEN WOD 5: AMRAP 12 de thrusters + pull-ups (estilo Fran)', 'REDO del Open WOD 5 con splits planificados'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Fran (rondas)', 'minimum' => 4, 'target' => 6, 'unit' => 'rondas', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Open WOD 5 (9 thrusters 43/30 kg + 9 pull-ups, AMRAP 12): rondas completas; anota también el tiebreak tras el round 3. Fran real (21-15-9) como benchmark opcional'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Ladder: barra ascendente con dobles y burpees'),
                'Si fallas el test: las dobles sin fallo valen más que la carga; falla una doble y la ronda se cobra el triple.',
            ],
            'trainingWeeks' => self::weeks(6, 'Ladder: barra ascendente con dobles y burpees', 'Rondas del ladder con carga progresiva', 'OPEN WOD 6: ladder ascendente (16.2-style)', 'REDO ligero del ladder comparado'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Ladder (reps totales)', 'minimum' => 60, 'target' => 90, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Open WOD 6 (ladder ascendente): anota la CARGA ALCANZADA en kg (p. ej. 60→70→80→90 kg M / 40→50→55→60 kg F) y las reps totales = burpees completados + dobles'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Redo estratégico: repetir la semana 5 mejorado'),
                'El redo se gana con el plan, no con las ganas: mismo split, transiciones 3-5 s más rápidas, tiebreak más temprano. Compara las tres marcas.',
            ],
            'trainingWeeks' => self::weeks(7, 'Reactivar el couplet: thruster/pull-up ligeros', 'AMRAP 10 del couplet con el plan escrito', 'REDO ESTRATÉGICO del Open WOD de la semana 5', 'REDO final comparado (tercera marca)'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Fran redo (rondas)', 'minimum' => 5, 'target' => 7, 'unit' => 'rondas', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Redo estratégico del Open WOD 5: mismo WOD con plan escrito (splits iguales, transiciones más rápidas, tiebreak más temprano); la mejora de rondas respecto a la semana 5 es la marca del nivel'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => [
                ...self::baseTips('Peak: afilar con volumen bajo e intensidad alta'),
                'El test final se corre como se entrenó: 5+4 en los thrusters, ritmo constante en los burpees, último minuto a fondo.',
            ],
            'trainingWeeks' => self::weeks(8, 'Peak: afilar con volumen bajo e intensidad alta', 'AMRAP 7 del par thruster/burpee', 'TEST FINAL (estilo 15.5): thrusters + burpees 8 min', 'Cierre y movilidad'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Open Final (thrusters)', 'minimum' => 45, 'target' => 60, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Test final estilo 15.5 (9 thrusters 43/30 kg + 15 burpees, AMRAP 8): thrusters completados acumulados; anota también el tiempo de la última ronda completa (lte en comparaciones)'],
                    ],
                ],
            ],
        ];
    }
}
