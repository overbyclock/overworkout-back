<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa CrossFit Open Prep v1 (8 semanas).
 *
 * Preparación de 8 semanas hacia el Open: cada nivel ES una semana
 * (estimatedDurationWeeks 1 por nivel; el programa completo 8).
 * Semanas: Engine → Gimnástico denso → Barbell cycling → Chipper →
 * AMRAP con carga → Ladder → Redo + percentil → Peak.
 * Estructura: 4 sesiones por fase (A=Fuerza/barbells, B=Gymnastics
 * densidad, C=Open WOD + redo, D=Recuperación activa).
 * Fases semanales: base (volumen) → progression (intensidad) →
 * intensification (Open WOD + redo) → deload (recuperación).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N4 y 90 s en N5-N8.
 *
 * Regla §1.7 / estrategia Open: dividir desde el principio, tiebreak temprano,
 * redos planificados, calentamiento específico por modalidad; técnica antes
 * que carga en los barbells. Las cargas (% de 1RM, kg de los WOD) van en
 * notas: ajustar al nivel del atleta.
 */
class CrossfitOpenPrepBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Semana 1: Engine',
                'title' => 'Engine — Base aeróbica del Open',
                'description' => 'Construir el motor: AMRAP 12 de dobles, box jumps y remo como formato semanal, base aeróbica Z2/Z3 y la primera toma de contacto con el formato Open. La estrategia empieza aquí: dividir desde el principio, no cuando ya arde.',
                'objective' => 'Base aeróbica del Open: AMRAP 12 du + box + row, densidad gimnástica y primer Open WOD de referencia',
                'difficultyRating' => 5,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — semana 1 del prep',
                'skillFocus' => 'Engine: base aeróbica y ritmo de AMRAP',
            ],
            2 => [
                'name' => 'Semana 2: Gimnástico denso',
                'title' => 'Gimnástico denso — T2B, pull-ups y wall balls en parejas cortas',
                'description' => 'Densidad gimnástica: toes to bar, pull-ups y wall balls en parejas cortas con transiciones rápidas. El formato del Open castiga los breaks largos: se entrena a repartir.',
                'objective' => 'Densidad gimnástica: T2B/pull-up/wall ball en parejas cortas y reps acumuladas',
                'difficultyRating' => 6,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Semana 1 superada (engine y Open WOD de referencia)',
                'skillFocus' => 'Densidad gimnástica y transiciones sin breaks',
            ],
            3 => [
                'name' => 'Semana 3: Barbell cycling',
                'title' => 'Barbell cycling — Grace/Isabel a ritmo',
                'description' => 'Cycling de barra ligera: clean & jerk y thrusters al ritmo de Grace/Isabel, series de 3-5 con descanso corto y la barra que no se detiene. El tiebreak se gana aquí.',
                'objective' => 'Barbell cycling: C&J 60/40 y thruster 40/30 en series al ritmo del Open',
                'difficultyRating' => 6,
                'color' => '#facc15',
                'requirementsSummary' => 'Semana 2 superada',
                'skillFocus' => 'Barbell cycling ligero y ritmo de Grace/Isabel',
            ],
            4 => [
                'name' => 'Semana 4: Chipper',
                'title' => 'Chipper — Filthy 50 y gestión del orden descendente',
                'description' => 'El chipper largo: 50-40-30-20-10 con estaciones mixtas. La estrategia es el plan: dividir cada estación desde el rep 1, transiciones caminando rápido y el último tercio con el ritmo que queda.',
                'objective' => 'Chipper: 50-40-30-20-10 mixto, gestión de estaciones y ritmo descendente',
                'difficultyRating' => 7,
                'color' => '#fb923c',
                'requirementsSummary' => 'Semana 3 superada',
                'skillFocus' => 'Chipper y gestión de estaciones largas',
            ],
            5 => [
                'name' => 'Semana 5: AMRAP con carga',
                'title' => 'AMRAP con carga — Couplet thruster/pull-up a la Fran',
                'description' => 'El couplet con carga: thruster/pull-up al ritmo de Fran con tiebreak temprano. Dividir desde el principio: 4-5 series planificadas valen más que el héroe del minuto 1.',
                'objective' => 'AMRAP con carga: couplet thruster/pull-up a la Fran y tiebreak temprano',
                'difficultyRating' => 7,
                'color' => '#f87171',
                'requirementsSummary' => 'Semana 4 superada',
                'skillFocus' => 'AMRAP con carga, tiebreak temprano y splits planificados',
            ],
            6 => [
                'name' => 'Semana 6: Ladder',
                'title' => 'Ladder — Barra ascendente con dobles y burpees',
                'description' => 'El ladder ascendente (16.2-style): barra que sube, dobles y burpees entre rondas. Cada ronda se gana con la cabeza: dobles sin fallo y burpees que no se aceleran.',
                'objective' => 'Ladder ascendente con barra, dobles y burpees: gestión de rondas',
                'difficultyRating' => 8,
                'color' => '#ef4444',
                'requirementsSummary' => 'Semana 5 superada',
                'skillFocus' => 'Ladder ascendente y gestión de rondas bajo presión',
            ],
            7 => [
                'name' => 'Semana 7: Redo + percentil',
                'title' => 'Redo + percentil — Repetir la semana 5 mejorado',
                'description' => 'El redo estratégico: repetir el WOD de la semana 5 con el plan escrito — dividir igual, transiciones más rápidas, tiebreak más temprano. La mejora se entrena, no se espera.',
                'objective' => 'Redo estratégico del WOD de la semana 5: mismo plan, mejores transiciones',
                'difficultyRating' => 8,
                'color' => '#dc2626',
                'requirementsSummary' => 'Semana 6 superada',
                'skillFocus' => 'Redo estratégico, percentil y gestión del esfuerzo repetido',
            ],
            8 => [
                'name' => 'Semana 8: Peak',
                'title' => 'Peak — Semana del Open: afinar y test final',
                'description' => 'La semana del Open: volumen bajo, intensidad alta, movilidad diaria y el test final corto e intenso tipo 15.5 (thruster/burpee). El trabajo ya está hecho: hoy se afila.',
                'objective' => 'Peak: afilar la semana del Open con test final corto e intenso',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Semana 7 superada',
                'skillFocus' => 'Peak: afinado, calentamiento específico y test final',
            ],
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getSessionData(int $levelNumber, string $sessionKey, string $phase): array
    {
        $meta = self::sessionMeta($levelNumber, $sessionKey);

        $blocks = [
            self::block0($levelNumber, $phase, $sessionKey),
            self::fuerzaSkillBlock($levelNumber, $sessionKey, $phase),
            self::wodBlock($levelNumber, $sessionKey, $phase),
            self::posturalBlock($sessionKey),
            self::descargaBlock($sessionKey, $phase),
        ];

        return [
            'name' => $meta['name'],
            'goal' => $meta['goal'],
            'sessionType' => $meta['sessionType'],
            'blocks' => self::addRestBetweenBlocks($blocks, $levelNumber),
        ];
    }

    private static function sessionMeta(int $level, string $sessionKey): array
    {
        return match ($sessionKey) {
            'session_a' => ['name' => 'Sesión A: Fuerza y barbells', 'goal' => 'Cycling y fuerza de barra (% 1RM en notas): la base del Open WOD de la semana', 'sessionType' => 'strength'],
            'session_b' => ['name' => 'Sesión B: Gymnastics densidad', 'goal' => 'EMOMs y densidad de T2B/DU/wall ball/pull-up: repartir sin breaks', 'sessionType' => 'skill'],
            'session_c' => ['name' => 'Sesión C: Open WOD + redo', 'goal' => 'El WOD de la semana (F3, con redo comparado en F4): estrategia Open aplicada', 'sessionType' => 'circuit'],
            'session_d' => ['name' => 'Sesión D: Recuperación activa', 'goal' => 'Z2 suave, movilidad de hombro/muñeca/lumbar y core suave', 'sessionType' => 'skill'],
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function addRestBetweenBlocks(array $blocks, int $level): array
    {
        if (\count($blocks) <= 1) {
            return $blocks;
        }

        // N1-N4: transición breve (60 s); N5-N8: 90 s.
        $rest = $level <= 4 ? 60 : 90;
        foreach ($blocks as $index => &$block) {
            if ($index < \count($blocks) - 1) {
                $block['restAfterBlock'] = $rest;
            }
        }

        return $blocks;
    }

    private static function block(string $blockType, int $rounds, int $restRounds, int $restEx, array $exercises, int $restSets = 0): array
    {
        return [
            'blockType' => $blockType,
            'rounds' => $rounds,
            'restBetweenRounds' => $restRounds,
            'restBetweenExercises' => $restEx,
            'restBetweenSets' => $restSets,
            'exercises' => $exercises,
        ];
    }

    private static function ex(string $name, string $reps, int $sets = 1): array
    {
        return ['name' => $name, 'reps' => $reps, 'sets' => $sets];
    }

    // ========================================================================
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida; énfasis hombro/muñeca/core)
    // ========================================================================

    private static function block0(int $level, string $phase, string $sessionKey): array
    {
        [$dislocate, $pullApart, $scapPushUp] = match ($phase) {
            'base' => ['10', '10', '8'],
            'progression' => ['12', '12', '10'],
            'intensification' => ['15', '12', '12'],
            'deload' => ['12', '10', '10'],
            default => throw new \InvalidArgumentException(),
        };
        [$deepSquat, $wristExt] = match ($phase) {
            'base' => ['30s', '20s'],
            'progression' => ['40s', '25s'],
            'intensification' => ['45s', '30s'],
            'deload' => ['45s', '20s'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Scapular Push Up', $scapPushUp),
            self::ex('Wrist Extension Stretch', $wristExt),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Glute Bridge', '12'),
        ];

        // Variante A (barbells): despertar la bisagra y el rack
        if ('session_a' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Glute Bridge', '10');
            $exercises[] = self::ex('Deep Squat Hold', '20s');
        }
        // Variante B (gymnastics): muñeca y hombro del agarre
        if ('session_b' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Wrist Push-Up', '8');
        }
        // Variante C (Open WOD): calentamiento específico de la modalidad
        if ('session_c' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    // ========================================================================
    // BLOQUE POSTURAL (prehab rotado por sesión)
    // ========================================================================

    private static function posturalBlock(string $sessionKey): array
    {
        return self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, match ($sessionKey) {
            'session_a' => [
                self::ex('Scapular Push Up', '10', 2),
                self::ex('Band Pull-Apart', '12', 2),
                self::ex('Face Pull', '12', 2),
            ],
            'session_b' => [
                self::ex('Wrist Push-Up', '8', 2),
                self::ex('Wrist Prayer', '30s', 2),
                self::ex('Prone Y Raise', '10', 2),
            ],
            'session_c' => [
                self::ex('Dead Bug', '10/lado', 2),
                self::ex('Bird Dog', '8/lado', 2),
                self::ex('Pallof Press', '12/lado', 2),
            ],
            'session_d' => [
                self::ex('Band Pull-Apart', '15', 2),
                self::ex('Glute Bridge', '15', 2),
                self::ex('Dead Bug', '10/lado', 2),
            ],
            default => throw new \InvalidArgumentException(),
        }, 50);
    }

    // ========================================================================
    // BLOQUE DESCARGA
    // ========================================================================

    private static function descargaBlock(string $sessionKey, string $phase): array
    {
        $exercises = [
            self::ex('Diaphragmatic Breathing', 'deload' === $phase ? '120s' : '90s'),
            self::ex("Child's Pose", 'deload' === $phase ? '60s' : '45s'),
            self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
        ];

        if ('deload' === $phase) {
            $exercises[] = self::ex('Shoulder Dislocate', '10');
            $exercises[] = self::ex('Wrist Extension Stretch', '30s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, $exercises, 20);
    }

    // ========================================================================
    // BLOQUE FUERZA_SKILL
    // ========================================================================

    private static function fuerzaSkillBlock(int $level, string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::fuerzaSkillA($level, $phase),
            'session_b' => self::fuerzaSkillB($level, $phase),
            'session_c' => self::fuerzaSkillC($level, $phase),
            'session_d' => self::fuerzaSkillD($level, $phase),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillA(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Deadlift', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Power Clean', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Front Squat', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Power Clean', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Push Press', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Back Squat', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Clean and Jerk', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Thruster', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Deadlift', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Deadlift', match ($phase) {
                    'base' => '10', 'progression' => '10', 'intensification' => '8', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Clean and Jerk', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Front Squat', match ($phase) {
                    'base' => '8', 'progression' => '8', 'intensification' => '6', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '5', 'progression' => '7', 'intensification' => '9', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Power Clean', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Overhead Squat', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Power Clean', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '1', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Push Jerk', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '1', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Snatch', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '1', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '7', 'progression' => '7', 'intensification' => '9', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Clean and Jerk', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Front Squat', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Peak: afilar, no construir: cargas bajas y técnica
                self::ex('Thruster', match ($phase) {
                    'base' => '3', 'progression' => '5', 'intensification' => '9', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Push Press', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Snatch', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '1', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '60s', 'progression' => '75s', 'intensification' => '60s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Pull Up', match ($phase) {
                    'base' => '5', 'progression' => '8', 'intensification' => '10', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 60),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Pull Up', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Box Jump', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Toes to Bar', '10', 3),
            ], 60),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Box Jump', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Pull Up', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 60),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Pull Up', match ($phase) {
                    'base' => '9', 'progression' => '12', 'intensification' => '15', 'deload' => '9',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Double Unders', match ($phase) {
                    'base' => '30', 'progression' => '40', 'intensification' => '50', 'deload' => '30',
                    default => throw new \InvalidArgumentException(),
                }, 4),
            ], 60),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Burpee', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Double Unders', match ($phase) {
                    'base' => '20', 'progression' => '30', 'intensification' => '40', 'deload' => '20',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Box Jump', '10', 3),
            ], 60),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Pull Up', match ($phase) {
                    'base' => '9', 'progression' => '12', 'intensification' => '15', 'deload' => '9',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 60),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Double Unders', match ($phase) {
                    'base' => '30', 'progression' => '40', 'intensification' => '50', 'deload' => '30',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Burpee', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Wall Ball Shot', '15', 2),
            ], 60),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        // Calentamiento específico del WOD de la semana (corto)
        [$erg, $gymnastic] = match ($level) {
            1 => ['Rowing Machine Sprint', 'Double Unders'],
            2 => ['Wall Ball Shot', 'Toes to Bar'],
            3 => ['Clean and Jerk', 'Thruster'],
            4 => ['Kettlebell Swing American', 'Wall Ball Shot'],
            5 => ['Thruster', 'Pull Up'],
            6 => ['Power Clean', 'Double Unders'],
            7 => ['Thruster', 'Pull Up'],
            8 => ['Thruster', 'Burpee'],
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex($erg, match ($phase) {
                'base' => '60s', 'progression' => '60s', 'intensification' => '90s', 'deload' => '45s',
                default => throw new \InvalidArgumentException(),
            }),
            self::ex($gymnastic, match ($phase) {
                'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '6',
                default => throw new \InvalidArgumentException(),
            }, 2),
            self::ex('Shoulder Dislocate', '10'),
        ], 45);
    }

    private static function fuerzaSkillD(int $level, string $phase): array
    {
        // Recuperación activa: Z2 suave + movilidad hombro/muñeca/lumbar
        $bike = match ($phase) {
            'base' => '90s', 'progression' => '120s', 'intensification' => '90s', 'deload' => '60s',
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex('Assault Bike Sprint', $bike, 2),
            self::ex('Shoulder Dislocate', match ($phase) {
                'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                default => throw new \InvalidArgumentException(),
            }),
            self::ex('Wrist Prayer', '30s'),
            self::ex('Deep Squat Hold', '45s'),
            self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
            self::ex('Glute Bridge', '15'),
            self::ex('Dead Bug', '10/lado'),
        ], 45);
    }

    // ========================================================================
    // BLOQUE WOD
    // ========================================================================

    private static function wodBlock(int $level, string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::wodA($level, $phase),
            'session_b' => self::wodB($level, $phase),
            'session_c' => self::wodC($level, $phase),
            'session_d' => self::wodD($level, $phase),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function wodA(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                self::ex('Kettlebell Swing American', '8'),
                self::ex('Sit-up', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '4 rondas: 8 KB swings americanos (24/16 kg) + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas; bisagra y core para la barra.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Power Clean', match ($level) {
                    1, 2 => '3', 3, 4 => '5', default => '2',
                }),
                self::ex('Burpee', '5'),
            ]) + ['description' => "AMRAP 8': power cleans (cycling ligero, kg en notas) + 5 burpees. Rondas estimadas para llenar la ventana; la barra no se detiene."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                self::ex('Clean and Jerk', match ($level) {
                    3 => '3', 5, 7 => '3', default => '2',
                }),
                self::ex('Box Jump', '8'),
            ]) + ['description' => '4 rondas: clean & jerk (ritmo del Open) + 8 box jumps. 60 s entre rondas; cycling con barra cansada.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Sit-up', '8'),
                self::ex('Glute Bridge', '8'),
            ]) + ['description' => '2 rondas suaves: sit-ups + glute bridges. Movimiento; el test cierra la semana.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodB(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                self::ex('Push Up', '10'),
                self::ex('Sit-up', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '3 rondas: 10 push ups + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas; empuje y core.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Jump Rope', '60s'),
                self::ex('Mountain Climber', '10'),
            ]) + ['description' => "AMRAP 10': 60 s de cuerda + 10 mountain climbers. Rondas estimadas; ritmo aeróbico del engine."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                1, 2 => 8, default => 10,
            }, 1, 10, [
                self::ex('Toes to Bar', match ($level) {
                    1, 2 => '6', 3, 4 => '8', default => '8',
                }),
                self::ex('Wall Ball Shot', match ($level) {
                    1, 2 => '8', 3, 4 => '10', default => '10',
                }),
            ]) + ['description' => "EMOM 8-10': minutos impares toes to bar, pares wall balls. La densidad del Open: sin breaks, transición directa."],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Band Pull-Apart', '12'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas suaves: band pull-aparts + wrist prayer. Movimiento; el test cierra la semana.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodC(int $level, string $phase): array
    {
        return match ($level) {
            1 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Double Unders', '20'),
                    self::ex('Box Jump', '8'),
                    self::ex('Rowing Machine Sprint', '50s'),
                ]) + ['description' => '3 rondas: 20 dobles + 8 box jumps + 200 m de remo. 60 s entre rondas; ritmo del AMRAP.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Double Unders', '30'),
                    self::ex('Box Jump', '10'),
                ]) + ['description' => "AMRAP 10' estimado: 30 dobles + 10 box jumps. Rondas estimadas para llenar la ventana."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 7, 60, 15, [
                    self::ex('Double Unders', '30'),
                    self::ex('Box Jump', '10'),
                    self::ex('Rowing Machine Sprint', '60s'),
                ]) + ['description' => "OPEN WOD 1: AMRAP 12' de 30 dobles + 10 box jumps + 250 m de remo. Divide las dobles desde el principio; anota rondas y tiebreak."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Double Unders', '20'),
                    self::ex('Box Jump', '8'),
                    self::ex('Rowing Machine Sprint', '50s'),
                ]) + ['description' => "REDO: AMRAP 12' del Open WOD 1 con el plan ajustado. Compara rondas y tiebreak con el F3."],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Toes to Bar', '8'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '4 rondas: 8 T2B + 10 wall balls. 60 s entre rondas; parejas cortas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Pull Up', '10'),
                    self::ex('Toes to Bar', '8'),
                ]) + ['description' => "AMRAP 10' estimado: 10 pull-ups + 8 T2B. Rondas estimadas; divide las dominadas."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Toes to Bar', '10'),
                    self::ex('Wall Ball Shot', '15'),
                    self::ex('Pull Up', '5'),
                ]) + ['description' => "OPEN WOD 2: AMRAP 12' de 10 T2B + 15 wall balls + 5 pull-ups. Reps acumuladas = T2B + wall balls; divide desde el rep 1."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Toes to Bar', '8'),
                    self::ex('Wall Ball Shot', '12'),
                    self::ex('Pull Up', '4'),
                ]) + ['description' => 'REDO: AMRAP 12 del Open WOD 2 con transiciones más rápidas. Compara reps acumuladas.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Clean and Jerk', '5'),
                ]) + ['description' => '4 rondas: 5 clean & jerk ligeros. 90 s entre rondas; el cycling se afila.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Clean and Jerk', '3'),
                ]) + ['description' => "AMRAP 5' de clean & jerk (60/40 kg): ritmo de Grace. Rondas estimadas."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Clean and Jerk', '5'),
                ]) + ['description' => 'OPEN WOD 3: máximas reps de clean & jerk en 5:00 a 60/40 kg. Divide en series de 3-5 desde el principio; anota reps totales.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Clean and Jerk', '3'),
                ]) + ['description' => 'REDO ligero: 3 reps por serie, ritmo más rápido. Compara el ritmo por serie.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Wall Ball Shot', '25'),
                    self::ex('Box Jump', '20'),
                ]) + ['description' => '2 rondas: 25 wall balls + 20 box jumps. 90 s entre rondas; la primera mitad del chipper.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 1, 1, 15, [
                    self::ex('Rowing Machine Sprint', '90s'),
                    self::ex('Kettlebell Swing American', '30'),
                    self::ex('Deadlift', '20'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => 'Segunda mitad del chipper (escalada): 500 m de remo + 30 KB swings + 20 deadlifts + 10 burpees, por tiempo.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 1, 1, 15, [
                    self::ex('Wall Ball Shot', '50'),
                    self::ex('Box Jump', '40'),
                    self::ex('Rowing Machine Sprint', '150s'),
                    self::ex('Kettlebell Swing American', '30'),
                    self::ex('Deadlift', '20'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => 'OPEN WOD 4 (chipper estilo Filthy 50, escala): 50 wall balls + 40 box jumps + 500 m de remo + 30 KB swings + 20 deadlifts + 10 burpees, por tiempo. Divide cada estación desde el rep 1.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 1, 1, 15, [
                    self::ex('Wall Ball Shot', '30'),
                    self::ex('Box Jump', '25'),
                    self::ex('Kettlebell Swing American', '15'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => 'REDO suave: chipper reducido, ritmo constante. Compara el tiempo con el F3.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Thruster', '7'),
                    self::ex('Pull Up', '7'),
                ]) + ['description' => '3 rondas: 7 thrusters + 7 pull-ups. 60 s entre rondas; el couplet del nivel.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Thruster', '9'),
                    self::ex('Pull Up', '9'),
                ]) + ['description' => "AMRAP 10' estimado: 9 thrusters + 9 pull-ups. Rondas estimadas; divide 5+4 desde el principio."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Thruster', '9'),
                    self::ex('Pull Up', '9'),
                ]) + ['description' => "OPEN WOD 5: AMRAP 12' de 9 thrusters (43/30 kg) + 9 pull-ups. Tiebreak temprano (tras ronda 3); anota rondas. Fran real como benchmark opcional."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Thruster', '6'),
                    self::ex('Pull Up', '6'),
                ]) + ['description' => 'REDO: AMRAP 12 del Open WOD 5 con splits planificados. Compara rondas y tiebreak.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Power Clean', '3'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '3 rondas: 3 power cleans + 10 burpees. 90 s entre rondas; el par del ladder.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Power Clean', '2'),
                    self::ex('Push Jerk', '1'),
                    self::ex('Double Unders', '20'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 rondas del ladder ascendente (carga sube por ronda, kg en notas): 2 power cleans + 1 push jerk + 20 dobles + 10 burpees.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Power Clean', '2'),
                    self::ex('Push Jerk', '1'),
                    self::ex('Double Unders', '20'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => 'OPEN WOD 6 (ladder 16.2-style): rondas con barra ascendente + 20 dobles + 10 burpees. Anota carga alcanzada y burpees; dobles sin fallo.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Power Clean', '2'),
                    self::ex('Double Unders', '15'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => 'REDO: 3 rondas ligeras con ritmo alto. Compara burpees por ronda.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Thruster', '7'),
                    self::ex('Pull Up', '7'),
                ]) + ['description' => '3 rondas: 7 thrusters + 7 pull-ups. 60 s entre rondas; reactivar el couplet.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Thruster', '9'),
                    self::ex('Pull Up', '9'),
                ]) + ['description' => "AMRAP 10' del couplet: 9 thrusters + 9 pull-ups. Rondas estimadas; mismo plan que la semana 5."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Thruster', '9'),
                    self::ex('Pull Up', '9'),
                ]) + ['description' => "REDO ESTRATÉGICO (Open WOD de la semana 5): AMRAP 12' de 9 thrusters + 9 pull-ups, mismo plan con transiciones más rápidas y tiebreak más temprano. La mejora se entrena."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Thruster', '6'),
                    self::ex('Pull Up', '6'),
                ]) + ['description' => 'REDO final: una repetición más del WOD, frescura controlada. Compara las tres marcas.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Thruster', '5'),
                    self::ex('Burpee', '7'),
                ]) + ['description' => '2 rondas: 5 thrusters + 7 burpees. 60 s entre rondas; el par del final.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Thruster', '9'),
                    self::ex('Burpee', '15'),
                ]) + ['description' => "AMRAP 7' estimado: 9 thrusters + 15 burpees (estilo 15.5). Rondas estimadas; ritmo de sprint largo."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Thruster', '9'),
                    self::ex('Burpee', '15'),
                ]) + ['description' => "TEST FINAL (estilo 15.5): AMRAP 8' de 9 thrusters (43/30 kg) + 15 burpees. Divide los thrusters 5+4 y los burpees en ritmo constante; anota thrusters completados y tiempo."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: movimiento de cierre del programa.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function wodD(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                self::ex('Dead Bug', '10/lado'),
                self::ex('Bird Dog', '8/lado'),
                self::ex('Glute Bridge', '12'),
            ]) + ['description' => '3 rondas: dead bug + bird dog + glute bridges. 60-90 s entre rondas; core suave.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                self::ex('Plank', '40s'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '2 rondas: plank + hollow. 60 s entre rondas; tronco quieto.'],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                self::ex('Band Pull-Apart', '15'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas: band pull-aparts + wrist prayer. Hombro y muñeca para el Open WOD.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Band Pull-Apart', '12'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas suaves: band pull-aparts + wrist prayer. Cierre de la semana.'],
            default => throw new \InvalidArgumentException(),
        };
    }
}
