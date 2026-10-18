<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Snatch Mastery v1 (8 niveles).
 *
 * Especialización olímpica: el snatch completo. Progresión técnica:
 * posiciones y movilidad → tirón → power → hang → squat → consistencia →
 * carga → dominio (100% BW). Estructura: 4 sesiones por fase
 * (A=Técnica olímpica, B=Fuerza de soporte, C=Overhead + prehab,
 * D=Técnica suave + movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N6 y 90 s en N7-N8 (regla del proyecto).
 *
 * Regla §1.7 (técnica antes que carga): si la recepción pierde la línea
 * (barra adelante, codo blando), bajar la carga; la muñeca y el hombro se
 * progresan antes que el peso; dolor matinal >2/10 = congelar la carga un
 * ciclo. Las cargas (kg) van en notas: ajustar a % BW según el atleta.
 *
 * [SIN FICHA]: 'Pendlay Row'/'Bent Over Row' no existen — el jalón horizontal
 * del bloque B se resuelve con 'T-Bar Row' (620) con nota.
 */
class SnatchBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Posiciones',
                'title' => 'Posiciones — Bisagra y recepción con barra vacía',
                'description' => 'Aprender las posiciones del snatch: bisagra de cadera, posición de arranque, recepción overhead (Overhead Squat con PVC/barra vacía) y la movilidad de hombro/tobillo/muñeca que la hace posible. Aquí no hay carga: hay líneas.',
                'objective' => 'Bisagra, recepción overhead (OHS con PVC/barra vacía) y movilidad de hombro/tobillo/muñeca',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero',
                'skillFocus' => 'Posiciones, bisagra y recepción overhead con barra vacía',
            ],
            2 => [
                'name' => 'Nivel 2: Tirón',
                'title' => 'Tirón — Snatch pull y muscle snatch',
                'description' => 'Construir el tirón: Snatch Pull con carga ligera-moderada, Muscle Snatch para la velocidad de brazos y Tall Snatch para el patrón de recepción. La barra sube recta porque las posiciones son rectas.',
                'objective' => 'Snatch Pull y Muscle Snatch ligeros, Tall Snatch y velocidad de brazos',
                'difficultyRating' => 2,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (OHS 10 con barra, snatch técnico ×5)',
                'skillFocus' => 'Tirón (snatch pull), muscle snatch y tall snatch',
            ],
            3 => [
                'name' => 'Nivel 3: Power snatch',
                'title' => 'Power snatch — Recepción alta',
                'description' => 'Power Snatch con recepción alta y carga ligera-moderada (40-50% BW): estirón completo, recepción firme arriba y primeras transiciones hacia la recepción profunda.',
                'objective' => 'Power Snatch (40-50% BW) con recepción alta y primeras recepciones profundas',
                'difficultyRating' => 3,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (muscle snatch 25 kg, snatch pull 40 kg)',
                'skillFocus' => 'Power snatch con recepción alta y carga ligera-moderada',
            ],
            4 => [
                'name' => 'Nivel 4: Hang + overhead',
                'title' => 'Hang + overhead — Rodilla, cadera y snatch balance',
                'description' => 'Hang Snatch desde rodilla y cadera, Snatch Balance para la recepción profunda y las primeras recepciones completas. La cadera llega al estirón: el hang castiga cualquier tirón con brazos.',
                'objective' => 'Hang Snatch (rodilla/cadera), Snatch Balance y primeras recepciones profundas',
                'difficultyRating' => 4,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (power snatch 35 kg)',
                'skillFocus' => 'Hang snatch, snatch balance y recepción profunda',
            ],
            5 => [
                'name' => 'Nivel 5: Squat snatch',
                'title' => 'Squat snatch — El snatch completo desde suelo',
                'description' => 'Squat Snatch completo desde suelo con profundidad consistente: estirón, recepción en sentadilla profunda y línea de barra recta. La primera carga real del programa.',
                'objective' => 'Squat Snatch completo desde suelo con profundidad consistente',
                'difficultyRating' => 5,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (hang snatch 40 kg)',
                'skillFocus' => 'Squat snatch desde suelo y profundidad de recepción',
            ],
            6 => [
                'name' => 'Nivel 6: Consistencia',
                'title' => 'Consistencia — Series de 3-5 al 70-80%',
                'description' => 'Series de 3-5 repeticiones al 70-80% sin fallo técnico, complexes (hang + full) y consistencia de recepción. El snatch deja de ser un intento: es un hábito.',
                'objective' => 'Series de 3-5 al 70-80% sin fallo técnico y complexes hang + full',
                'difficultyRating' => 6,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (squat snatch 45 kg)',
                'skillFocus' => 'Consistencia técnica al 70-80% y complexes',
            ],
            7 => [
                'name' => 'Nivel 7: Carga',
                'title' => 'Carga — Singles y dobles al 85-90%',
                'description' => 'Singles y dobles al 85-90% con fuerza de soporte pesada (OHS cargado, push press): la carga sube porque la técnica ya no se negocia.',
                'objective' => 'Singles y dobles al 85-90% con OHS pesado y push press de soporte',
                'difficultyRating' => 7,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (snatch 55 kg ×3)',
                'skillFocus' => 'Carga (85-90%), singles/dobles y fuerza de soporte overhead',
            ],
            8 => [
                'name' => 'Nivel 8: Dominio',
                'title' => 'Dominio — 100% BW y consistencia en fatiga',
                'description' => 'Cierre: snatch al 100% del peso corporal, complexes en fatiga controlada y la consistencia como bono. El snatch es ya una herramienta, no un proyecto.',
                'objective' => 'Snatch al 100% BW, complexes en fatiga y consistencia de competición',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (snatch 65 kg)',
                'skillFocus' => 'Snatch al 100% BW, complexes en fatiga y consistencia',
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
        $goal = match ($level) {
            1 => 'Posiciones y recepción con barra vacía',
            2 => 'Tirón: snatch pull y muscle snatch',
            3 => 'Power snatch con recepción alta',
            4 => 'Hang snatch y snatch balance',
            5 => 'Squat snatch completo desde suelo',
            6 => 'Series de 3-5 al 70-80% y complexes',
            7 => 'Singles y dobles al 85-90%',
            8 => 'Snatch al 100% BW y complexes en fatiga',
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };

        return match ($sessionKey) {
            'session_a' => ['name' => 'Sesión A: Técnica olímpica', 'goal' => 'Progresión con barra del nivel: '.$goal.'. Series cortas, descanso generoso', 'sessionType' => 'skill'],
            'session_b' => ['name' => 'Sesión B: Fuerza de soporte', 'goal' => 'Squat, pull y press: la base que sostiene la carga del snatch', 'sessionType' => 'strength'],
            'session_c' => ['name' => 'Sesión C: Overhead + prehab', 'goal' => 'OHS, snatch balance y prehab de hombro/muñeca/lumbar: la recepción segura', 'sessionType' => 'skill'],
            'session_d' => ['name' => 'Sesión D: Técnica suave + movilidad', 'goal' => 'PVC work y movilidad de hombro/tobillo/muñeca: recuperación activa', 'sessionType' => 'skill'],
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function addRestBetweenBlocks(array $blocks, int $level): array
    {
        if (\count($blocks) <= 1) {
            return $blocks;
        }

        // N1-N6: transición breve (60 s); N7-N8: 90 s (regla del proyecto).
        $rest = $level <= 6 ? 60 : 90;
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida; énfasis hombro/muñeca/tobillo)
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

        // Variante A (técnica): OHS con PVC para despertar la recepción
        if ('session_a' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Overhead Squat', '5');
        }
        // Variante B (fuerza): movilidad de tobillo antes de sentadilla
        if ('session_b' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }
        // Variante C (overhead/prehab): hombro extra
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
                self::ex('Wrist Extension Stretch', '30s', 2),
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
                // La recepción manda: OHS con barra vacía antes que cualquier carga
                self::ex('Overhead Squat', match ($phase) {
                    'base' => '5', 'progression' => '8', 'intensification' => '10', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 'deload' === $phase ? 3 : 4),
                self::ex('Snatch', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Tall Snatch', '3', 3),
            ], 120),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Snatch Pull', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '3', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Muscle Snatch', match ($phase) {
                    'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Tall Snatch', '3', 3),
            ], 120),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Power Snatch', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '3', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Overhead Squat', '3', 3),
                // Primer hang con recepción alta (nota técnica)
                self::ex('Hang Snatch', '2', 3),
            ], 120),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Hang Snatch', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '3', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Snatch Balance', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '4', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Overhead Squat', '3', 3),
            ], 120),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Squat Snatch', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '3', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Snatch Pull', '2', 3),
                self::ex('Overhead Squat', '3', 3),
            ], 150),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Complejo: hang + squat snatch en el mismo set
                self::ex('Snatch', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Hang Snatch', '1', 3),
                self::ex('Snatch Balance', '2', 3),
            ], 150),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Snatch', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '1', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Snatch', '1', 3),
                self::ex('Overhead Squat', '2', 3),
            ], 180),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Snatch', match ($phase) {
                    'base' => '1', 'progression' => '1', 'intensification' => '1', 'deload' => '1',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Overhead Squat', '2', 2),
                self::ex('Kettlebell Snatch', '8/lado', 3),
            ], 180),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Back Squat', match ($phase) {
                    'base' => '8', 'progression' => '8', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Deadlift', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '5', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Strict Press', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '6', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Front Squat', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '6', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Romanian Deadlift', match ($phase) {
                    'base' => '6', 'progression' => '6', 'intensification' => '8', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Push Press', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '6', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Back Squat', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '4', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Push Press', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '3', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('T-Bar Row', '8', 3),
            ], 120),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Front Squat', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '3', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Deadlift', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Strict Press', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '5', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 150),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Back Squat', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Romanian Deadlift', '5', 3),
                self::ex('Push Press', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
            ], 150),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Front Squat', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '2', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Deadlift', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '2', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Push Press', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '2', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 4),
            ], 150),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Back Squat', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '2', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Push Press', match ($phase) {
                    'base' => '2', 'progression' => '2', 'intensification' => '2', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Romanian Deadlift', '4', 3),
            ], 180),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Front Squat', '2', 3),
                self::ex('Deadlift', '1', 3),
                self::ex('Push Press', '1', 3),
                self::ex('T-Bar Row', '8', 3),
            ], 180),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        // Overhead + prehab: la recepción segura y el hombro/muñeca del snatcher
        [$ohs, $snatchBalanceSets] = match ($level) {
            1, 2, 3 => ['5', 0],
            4, 5 => ['5', 3],
            6, 7 => ['3', 3],
            8 => ['3', 2],
            default => throw new \InvalidArgumentException(),
        };
        [$facePull, $dislocate, $wristPushUp] = match ($phase) {
            'base' => ['12', '10', '8'],
            'progression' => ['15', '12', '10'],
            'intensification' => ['15', '15', '10'],
            'deload' => ['12', '10', '8'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Overhead Squat', $ohs, 2),
            self::ex('Shoulder Dislocate', $dislocate, 2),
            self::ex('Face Pull', $facePull, 2),
            self::ex('Wrist Push-Up', $wristPushUp, 2),
        ];

        if ($snatchBalanceSets > 0) {
            $exercises[] = self::ex('Snatch Balance', '3', $snatchBalanceSets);
        }

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, $exercises, 60);
    }

    private static function fuerzaSkillD(int $level, string $phase): array
    {
        // Técnica suave con barra vacía + movilidad: recuperación activa
        [$ohs, $tallSnatch] = match ($phase) {
            'base' => ['5', '3'],
            'progression' => ['8', '3'],
            'intensification' => ['10', '5'],
            'deload' => ['5', '3'],
            default => throw new \InvalidArgumentException(),
        };
        $dislocate = match ($phase) {
            'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
            self::ex('Overhead Squat', $ohs, 3),
            self::ex('Tall Snatch', $tallSnatch, 3),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Deep Squat Hold', '45s'),
            self::ex('Wrist Extension Stretch', '30s'),
            self::ex('Calf Stretch Wall', '30s/lado'),
            self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
        ], 60);
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
        return match ($level) {
            1 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Overhead Squat', '5'),
                    self::ex('Snatch', '3'),
                ]) + ['description' => '3 rondas: 5 OHS + 3 snatches técnicos con barra vacía. 90 s entre rondas; las posiciones manda.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Overhead Squat', '8'),
                    self::ex('Tall Snatch', '3'),
                ]) + ['description' => '3 rondas: 8 OHS + 3 tall snatches. 90 s entre rondas; recepción firme, muñecas listas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Snatch', '5'),
                    self::ex('Overhead Squat', '5'),
                ]) + ['description' => '4 rondas: 5 snatches + 5 OHS con barra vacía. 90 s entre rondas; la dosis del test en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Overhead Squat', '5'),
                ]) + ['description' => '2 rondas suaves: OHS con barra vacía. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch Pull', '3'),
                    self::ex('Muscle Snatch', '3'),
                ]) + ['description' => '3 rondas: 3 snatch pulls + 3 muscle snatches. 90 s entre rondas; barra recta, codos altos.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Snatch Pull', '3'),
                    self::ex('Tall Snatch', '3'),
                ]) + ['description' => '4 rondas: 3 snatch pulls + 3 tall snatches. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Muscle Snatch', '5'),
                    self::ex('Overhead Squat', '3'),
                ]) + ['description' => '4 rondas: 5 muscle snatches + 3 OHS. 90 s entre rondas; la dosis del test en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Snatch Pull', '3'),
                ]) + ['description' => '2 rondas suaves: snatch pulls ligeros. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Power Snatch', '2'),
                    self::ex('Overhead Squat', '3'),
                ]) + ['description' => '4 rondas: 2 power snatches + 3 OHS. 90 s entre rondas; recepción alta firme.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Power Snatch', '2'),
                    self::ex('Squat Jump', '5'),
                ]) + ['description' => '4 rondas: 2 power snatches + 5 squat jumps. 90 s entre rondas; potencia de estirón.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 90, 15, [
                    self::ex('Power Snatch', '3'),
                    self::ex('Overhead Squat', '3'),
                ]) + ['description' => '5 rondas: 3 power snatches + 3 OHS. 90 s entre rondas; la dosis del test integrada.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Power Snatch', '2'),
                ]) + ['description' => '2 rondas suaves: power snatches ligeros. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Hang Snatch', '2'),
                    self::ex('Snatch Balance', '3'),
                ]) + ['description' => '4 rondas: 2 hang snatches + 3 snatch balances. 90 s entre rondas; recepción profunda.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Hang Snatch', '2'),
                    self::ex('Overhead Squat', '3'),
                ]) + ['description' => '4 rondas: 2 hang snatches + 3 OHS. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 90, 15, [
                    self::ex('Hang Snatch', '3'),
                    self::ex('Snatch Balance', '3'),
                ]) + ['description' => '5 rondas: 3 hang snatches + 3 snatch balances. 90 s entre rondas; la dosis del test integrada.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Hang Snatch', '2'),
                ]) + ['description' => '2 rondas suaves: hang snatches ligeros. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Squat Snatch', '2'),
                    self::ex('Overhead Squat', '3'),
                ]) + ['description' => '4 rondas: 2 squat snatches + 3 OHS. 90 s entre rondas; profundidad consistente.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Squat Snatch', '2'),
                    self::ex('Snatch Pull', '2'),
                ]) + ['description' => '4 rondas: 2 squat snatches + 2 snatch pulls. 90 s entre rondas; la fuerza del tirón sostiene la recepción.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 90, 15, [
                    self::ex('Squat Snatch', '3'),
                    self::ex('Overhead Squat', '2'),
                ]) + ['description' => '5 rondas: 3 squat snatches + 2 OHS. 90 s entre rondas; la dosis del test integrada.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Squat Snatch', '2'),
                ]) + ['description' => '2 rondas suaves: squat snatches ligeros. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch', '3'),
                    self::ex('Hang Snatch', '1'),
                ]) + ['description' => '3 rondas: 3 snatches (full) + 1 hang snatch. 90 s entre rondas; el complejo del nivel.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Snatch', '3'),
                    self::ex('Toes to Bar', '6'),
                ]) + ['description' => '4 rondas: 3 snatches + 6 toes to bar (core del estirón). 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Snatch', '5'),
                    self::ex('Overhead Squat', '3'),
                ]) + ['description' => '4 rondas: 5 snatches + 3 OHS. 90 s entre rondas; la dosis del test en series.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Snatch', '3'),
                ]) + ['description' => '2 rondas suaves: 3 snatches ligeros. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch', '2'),
                    self::ex('Overhead Squat', '2'),
                ]) + ['description' => '3 rondas: 2 snatches al 85% + 2 OHS pesados. 90 s entre rondas; singles con cabeza.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch', '1'),
                    self::ex('Push Press', '3'),
                ]) + ['description' => '3 rondas: 1 snatch al 90% + 3 push press. 90 s entre rondas; el overhead fuerte sostiene el single.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch', '2'),
                    self::ex('Snatch', '1'),
                ]) + ['description' => '3 rondas: doble + single progresivos. 90 s entre rondas; la dosis del test en el camino.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Snatch', '2'),
                ]) + ['description' => '2 rondas suaves: snatches ligeros. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch', '1'),
                    self::ex('Kettlebell Snatch', '8/lado'),
                ]) + ['description' => '3 rondas: 1 snatch al 100% BW + 8 kettlebell snatches por lado. 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 15, [
                    self::ex('Kettlebell Swing Russian', '21'),
                    self::ex('Overhead Squat', '5'),
                ]) + ['description' => '3 rondas: 21 KB swings + 5 OHS. 2 min entre rondas; complejo en fatiga controlada.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Snatch', '1'),
                    self::ex('Snatch', '1'),
                ]) + ['description' => '3 rondas: singles al 100-110% BW. 90 s entre rondas; el cierre del programa.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Snatch', '1'),
                ]) + ['description' => '2 rondas suaves: snatches técnicos. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function wodB(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                self::ex('Kettlebell Swing Russian', '8'),
                self::ex('Sit-up', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '4 rondas: 8 KB swings (24/16 kg) + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas; bisagra y core para la barra.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                1, 2 => 4, default => 5,
            }, 60, 15, [
                self::ex('Kettlebell Swing Russian', match ($level) {
                    1, 2 => '10', default => '12',
                }),
                self::ex('Mountain Climber', '10'),
            ]) + ['description' => 'AMRAP '.(match ($level) {
                1, 2 => '8', default => '10',
            })."': KB swings + mountain climbers. Rondas estimadas para llenar la ventana del AMRAP."],
            'intensification' => match ($level) {
                1 => self::block(TrainingRound::BLOCK_WOD, 4, 30, 15, [
                    self::ex('Glute Bridge', '10'),
                    self::ex('Mountain Climber', '30s'),
                ], 30) + ['description' => '4 × (30 s de glute bridges / 30 s de mountain climbers). Bisagra bajo fatiga.'],
                default => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                    2, 3 => 5, default => 6,
                }, 60, 10, [
                    self::ex('Hollow Body Hold', '30s'),
                ], 60) + ['description' => 'Intervalos de hollow: 5-6 × (30 s de hollow / 60 s de descanso). El tronco del estirón.'],
            },
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Sit-up', '8'),
                self::ex('Glute Bridge', '8'),
            ]) + ['description' => '2 rondas suaves: sit-ups + glute bridges. Movimiento; el test cierra el nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodC(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                self::ex('Band Pull-Apart', '15'),
                self::ex('Wrist Prayer', '30s'),
                self::ex('Glute Bridge', '12'),
            ]) + ['description' => '2 rondas: band pull-aparts + wrist prayer + glute bridges. 60 s entre rondas; hombro y muñeca del snatcher.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                self::ex('Dead Bug', '10/lado'),
                self::ex('Bird Dog', '8/lado'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '3 rondas: dead bug + bird dog + hollow. 60 s entre rondas; el core anti-extensión.'],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                self::ex('Face Pull', '15'),
                self::ex('Wrist Push-Up', '10'),
                self::ex('Plank', match ($level) {
                    1, 2, 3 => '40s', default => '60s',
                }),
            ]) + ['description' => '3 rondas: face pulls + wrist push-ups + plank. 60 s entre rondas; la recepción segura se construye aquí.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Band Pull-Apart', '12'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas suaves: band pull-aparts + wrist prayer. Movimiento; el test cierra el nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodD(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                self::ex('Step Up', '10'),
                self::ex('Kettlebell Swing Russian', '10'),
                self::ex('Sit-up', '10'),
            ]) + ['description' => '3 rondas: 10 step ups + 10 KB swings (16/12 kg) + 10 sit-ups. 60-90 s entre rondas; sin carga nueva.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                self::ex('Step Up', '10'),
                self::ex('Mountain Climber', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '4 rondas: 10 step ups + 10 mountain climbers + 30 s de hollow. 60 s entre rondas.'],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                self::ex('Step Up', '10'),
                self::ex('Kettlebell Swing Russian', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '3 rondas: 10 step ups + 10 KB swings + 30 s de hollow. 60 s entre rondas.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Step Up', '8'),
                self::ex('Sit-up', '8'),
            ]) + ['description' => '2 rondas suaves: 8 step ups + 8 sit-ups. Movimiento; el test cierra el nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }
}
