<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa DEKA ATLAS v1 (8 niveles).
 *
 * Test de fuerza de 10 zonas SIN correr (DEKA Rules of Competition v20260210).
 * Arco: base híbrida → primeras cargas (50% Foundation) → media ATLAS →
 * zonas Foundation completas por separado → ATLAS Foundation → transición
 * Peak → ATLAS Peak parcial → ATLAS Peak final.
 * Estructura: 4 sesiones por fase (A=Fuerza de zonas, B=Capacidad de trabajo,
 * C=Simulacro ATLAS / circuitos, D=Aeróbico suave + movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación → fuerza_skill → wod → postural → descarga.
 * restAfterBlock = 60 s en N1-N6 y 90 s en N7-N8.
 *
 * Regla §1.7 (hombro/muñeca/lumbar): técnica antes que carga; los thrusters y
 * press cargados exigen hombro y muñeca listos; dolor matinal >2/10 =
 * congelar la carga un ciclo.
 *
 * Dosis de distancia como tiempo estimado (spec §5): bear crawl 40 m ≈ '45s',
 * farmers 100 m ≈ '60s', shoulder carry 100 m ≈ '75s'; distancia en notas.
 * Sin puertas de movilidad (strict_gates evalúa los mínimos del test).
 */
class DekaAtlasBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base de fuerza híbrida',
                'title' => 'Base híbrida — Técnica de zonas sin carga',
                'description' => 'Enseñar la técnica de las 10 zonas ATLAS con barra vacía, kettlebells y mancuernas ligeras: thruster, burpee over bar, surrender lunge, bear crawl sin carga, weighted sit-up con disco ligero, farmers corto y shoulder carry con mancuerna. Aquí no hay Foundation: hay líneas.',
                'objective' => 'Técnica de las 10 zonas con barra vacía/KB ligero, bear crawl sin carga y movilidad de hombro/muñeca/lumbar',
                'difficultyRating' => 4,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida',
                'skillFocus' => 'Técnica de las 10 zonas ATLAS con barra vacía y movilidad de hombro/muñeca',
            ],
            2 => [
                'name' => 'Nivel 2: Primeras cargas',
                'title' => 'Primeras cargas — El 50% de Foundation entra en escena',
                'description' => 'Introducir el 50% de los pesos Foundation en las zonas de barra y mancuernas (thruster con KB 16/12, DB lunge 2×11/2×8, press 2×8/2×6), primeras cargas en bear crawl y farmers, y capacidad de trabajo en pares de zonas con fatiga.',
                'objective' => 'Zonas al 50% de Foundation, bear crawl y farmers cargados, y pares de zonas con fatiga',
                'difficultyRating' => 5,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (thruster barra vacía, sit-up)',
                'skillFocus' => 'Zonas al 50% de Foundation y pares de zonas con fatiga',
            ],
            3 => [
                'name' => 'Nivel 3: Media ATLAS',
                'title' => 'Media ATLAS — Cinco zonas conectadas',
                'description' => 'El primer recorrido serio: circuito de media ATLAS (5 zonas al 50% conectadas, sin parar el reloj) como sesión C, capacidad de trabajo en pares y la transición hacia las cargas Foundation completas en las zonas de fuerza.',
                'objective' => 'Circuito de media ATLAS (5 zonas al 50%) y construcción hacia los pesos Foundation completos',
                'difficultyRating' => 6,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (DB thruster y lunge 50%)',
                'skillFocus' => 'Media ATLAS: 5 zonas conectadas y transición a pesos Foundation',
            ],
            4 => [
                'name' => 'Nivel 4: Zonas Foundation',
                'title' => 'Zonas Foundation — Cada zona completa, una a una',
                'description' => 'Llevar cada zona a los pesos Foundation completos por separado (thruster 43/29,5, G2OH 22,5/15, farmers 2×22,5/2×15, press 2×17,5/2×11) con series largas y descanso completo: fuerza pura de zonas antes de la primera ATLAS completa.',
                'objective' => 'Las 10 zonas al 100% de los pesos Foundation por separado, con series largas y técnica de cristal',
                'difficultyRating' => 7,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (media ATLAS en rondas)',
                'skillFocus' => 'Zonas al 100% Foundation por separado y gestión del fallo',
            ],
            5 => [
                'name' => 'Nivel 5: ATLAS Foundation',
                'title' => 'ATLAS Foundation — La primera completa',
                'description' => 'Ejecutar la DEKA ATLAS Foundation completa (10 zonas, cap 30:00) como benchmark embebido y test del nivel, entrenando parejas de zonas con fatiga y la gestión del tiempo bajo cap.',
                'objective' => 'DEKA ATLAS Foundation completa (benchmark embebido) bajo el cap de 30:00',
                'difficultyRating' => 7,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (thruster y press Foundation)',
                'skillFocus' => 'ATLAS Foundation completa y gestión del tiempo bajo cap',
            ],
            6 => [
                'name' => 'Nivel 6: Transición Peak',
                'title' => 'Transición Peak — El 75% hacia Peak',
                'description' => 'Subir las cargas al 75% entre Foundation y Peak (thruster 48/32, G2OH 25/18, farmers 2×25/2×17,5), mantener la técnica bajo cargas mayores y preparar el cuerpo para los pesos Peak.',
                'objective' => 'Zonas al ~75% hacia Peak con técnica intacta y capacidad en parejas pesadas',
                'difficultyRating' => 8,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (ATLAS Foundation bajo cap)',
                'skillFocus' => 'Transición a cargas Peak (75%) y técnica bajo carga mayor',
            ],
            7 => [
                'name' => 'Nivel 7: ATLAS Peak parcial',
                'title' => 'ATLAS Peak parcial — Primera toma de contacto',
                'description' => 'Ejecutar la DEKA ATLAS Peak completa (benchmark embebido, mínimo generoso bajo el cap) como primera toma de contacto con los pesos más pesados, con mínimo generoso: terminar entero es la victoria.',
                'objective' => 'Primera DEKA ATLAS Peak completa (mínimo generoso bajo cap) con pesos más pesados',
                'difficultyRating' => 8,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (thruster 48 kg)',
                'skillFocus' => 'Primera ATLAS Peak completa: terminar entero bajo cap',
            ],
            8 => [
                'name' => 'Nivel 8: ATLAS Peak',
                'title' => 'ATLAS Peak — La marca de competición',
                'description' => 'Cierre: la DEKA ATLAS Peak como test final con mínimos exigentes bajo el cap de 30:00. Todo lo construido en N1-N7 se junta en una sola marca: la de competición.',
                'objective' => 'DEKA ATLAS Peak como test final: mínimos exigentes bajo el cap de 30:00',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (ATLAS Peak bajo cap)',
                'skillFocus' => 'ATLAS Peak a ritmo de competición: mínimos exigentes bajo cap',
            ],
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getSessionData(int $levelNumber, string $sessionKey, string $phase): array
    {
        $meta = self::sessionMeta($levelNumber, $sessionKey);

        $fuerzaSkill = self::fuerzaSkillBlock($levelNumber, $sessionKey, $phase);
        $wod = self::wodBlock($levelNumber, $sessionKey, $phase);

        $blocks = [
            self::block0($levelNumber, $phase, $sessionKey),
            $fuerzaSkill,
            $wod,
            self::posturalBlock($sessionKey),
            self::descargaBlock($sessionKey, $phase),
        ];

        if (isset($wod['benchmark'])) {
            // El bloque WOD es el benchmark embebido: el round ignora configs
            $blocks[2] = array_merge([
                'blockType' => TrainingRound::BLOCK_WOD,
                'rounds' => 1,
                'restBetweenRounds' => 1,
                'restBetweenExercises' => 5,
                'restBetweenSets' => 0,
                'exercises' => [],
            ], $wod);
        }

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
            'session_a' => ['name' => 'Sesión A: Fuerza de zonas', 'goal' => 'Barra y mancuernas de las zonas (pesos en notas): fuerza pura con series largas', 'sessionType' => 'strength'],
            'session_b' => ['name' => 'Sesión B: Capacidad de trabajo', 'goal' => 'Pares de zonas con fatiga y EMOMs: el trabajo denso del ATLAS', 'sessionType' => 'circuit'],
            'session_c' => ['name' => 'Sesión C: Simulacro ATLAS / circuito', 'goal' => 'Circuitos de zonas (F1/F2/F4) o benchmark embebido ATLAS (F3)', 'sessionType' => 'circuit'],
            'session_d' => ['name' => 'Sesión D: Aeróbico suave + movilidad', 'goal' => 'Z2 suave, movilidad de hombro/cadera/agarre y core: recuperación activa', 'sessionType' => 'skill'],
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function addRestBetweenBlocks(array $blocks, int $level): array
    {
        if (\count($blocks) <= 1) {
            return $blocks;
        }

        // N1-N6: transición breve (60 s); N7-N8: 90 s.
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida; énfasis hombro/muñeca/lumbar)
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
        [$deepSquat, $wristPrayer] = match ($phase) {
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
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Glute Bridge', '12'),
        ];

        // Variante A (fuerza de zonas): rack y muñecas antes de la barra
        if ('session_a' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Deep Squat Hold', '20s');
            $exercises[] = self::ex('Wrist Prayer', '20s');
        }
        // Variante C (simulacro): activación del agarre
        if ('session_c' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Wrist Push-Up', '6');
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
                self::ex('Dead Bug', '10/lado', 2),
                self::ex('Bird Dog', '8/lado', 2),
                self::ex('Pallof Press', '12/lado', 2),
            ],
            'session_c' => [
                self::ex('Dead Bug', '10/lado', 2),
                self::ex('Bird Dog', '8/lado', 2),
                self::ex('Plank', '45s', 2),
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
            $exercises[] = self::ex('Wrist Prayer', '30s');
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
                self::ex('Thruster', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Goblet Squat', '10', 3),
                self::ex('Strict Press', '8', 3),
            ], 90),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Dumbbell Lunge', '10/lado', 3),
                self::ex('Standing Dumbbell Press', '8', 3),
            ], 90),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Dumbbell Thruster', '8/lado', 3),
                self::ex('Bear Crawl', '30s', 3),
            ], 90),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Dumbbell Thruster', '6/lado', 4),
                self::ex('Standing Dumbbell Press', '10', 3),
            ], 120),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Dumbbell Lunge', '10/lado', 3),
                self::ex('Farmers Walk', '30s', 3),
            ], 120),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Dumbbell Thruster', '6/lado', 3),
                self::ex('Standing Dumbbell Press', '8', 3),
            ], 120),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                self::ex('Thruster', match ($phase) {
                    'base' => '6', 'progression' => '8', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Bear Crawl', '45s', 3),
                self::ex('Farmer Walk', '30s', 3),
            ], 120),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Peak: afilar, no construir
                self::ex('Thruster', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Standing Dumbbell Press', '10', 2),
                self::ex('Farmer Walk', '45s', 2),
            ], 120),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Burpee Over Bar', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Sit-up', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Jump Rope', '60s', 3),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Burpee Over Bar', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Bear Crawl', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Farmers Walk', '20s', 3),
            ], 60),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Dumbbell Thruster', match ($phase) {
                    'base' => '10/lado', 'progression' => '12/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '12/lado', 'progression' => '15/lado', 'intensification' => '20/lado', 'deload' => '12/lado',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 90),
            4, 5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Dumbbell Thruster', match ($phase) {
                    'base' => '10/lado', 'progression' => '12/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '15/lado', 'progression' => '18/lado', 'intensification' => '20/lado', 'deload' => '12/lado',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '40s', 'progression' => '50s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 90),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Thruster', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Dumbbell Thruster', '8/lado', 3),
                self::ex('Farmers Walk', '45s', 3),
            ], 90),
            7, 8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Thruster', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Bear Crawl', '45s', 3),
                self::ex('Farmer Walk', '45s', 3),
            ], 90),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        // Calentamiento específico de las zonas del simulacro (corto)
        [$zona1, $zona2] = match ($level) {
            1, 2 => ['Thruster', 'Burpee Over Bar'],
            3, 4 => ['Dumbbell Thruster', 'Dumbbell Lunge'],
            5 => ['Thruster', 'Dumbbell Lunge'],
            6, 7 => ['Thruster', 'Dumbbell Thruster'],
            8 => ['Thruster', 'Burpee Over Bar'],
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex($zona1, match ($phase) {
                'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '5',
                default => throw new \InvalidArgumentException(),
            }, 2),
            self::ex($zona2, match ($phase) {
                'base' => '6', 'progression' => '8', 'intensification' => '10', 'deload' => '6',
                default => throw new \InvalidArgumentException(),
            }, 2),
            self::ex('Shoulder Dislocate', '10'),
        ], 45);
    }

    private static function fuerzaSkillD(int $level, string $phase): array
    {
        // Aeróbico suave + movilidad hombro/cadera/agarre
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
            self::ex('Calf Stretch Wall', '30s/lado'),
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
            'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                self::ex('Kettlebell Swing Russian', '10'),
                self::ex('Sit-up', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '3 rondas: 10 KB swings (24/16 kg) + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas; bisagra y core para las zonas.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Kettlebell Swing Russian', match ($level) {
                    1, 2 => '10', 3, 4 => '15', default => '12',
                }),
                self::ex('Burpee', '5'),
            ]) + ['description' => "AMRAP 8': KB swings + 5 burpees. Rondas estimadas; el agarre y la bisagra bajo fatiga."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                self::ex('Thruster', match ($level) {
                    1, 2 => '5', 3, 4 => '7', default => '5',
                }),
                self::ex('Box Jump', '8'),
            ]) + ['description' => '4 rondas: thrusters (cycling de zona) + 8 box jumps. 60 s entre rondas; transiciones rápidas.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Sit-up', '8'),
                self::ex('Glute Bridge', '8'),
            ]) + ['description' => '2 rondas suaves: sit-ups + glute bridges. Movimiento; el test cierra el nivel.'],
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
            ]) + ['description' => '3 rondas: 10 push ups + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Burpee Over Bar', match ($level) {
                    1, 2 => '8', 3, 4 => '10', default => '12',
                }),
                self::ex('Jump Rope', '30s'),
            ]) + ['description' => "AMRAP 10': burpees over bar + 30 s de cuerda. Rondas estimadas; el par del ATLAS."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 8, 1, 10, [
                self::ex('Thruster', match ($level) {
                    1, 2 => '5', 3, 4 => '6', default => '5',
                }),
                self::ex('Wall Ball Shot', match ($level) {
                    1, 2 => '8', 3, 4 => '10', default => '10',
                }),
            ]) + ['description' => "EMOM 8': minutos impares thrusters, pares wall balls. La densidad del ATLAS: sin breaks."],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Band Pull-Apart', '12'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas suaves: band pull-aparts + wrist prayer. El agarre descansa.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodC(int $level, string $phase): array
    {
        return match ($level) {
            1 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Thruster', '5'),
                    self::ex('Burpee Over Bar', '5'),
                ]) + ['description' => '3 rondas: 5 thrusters + 5 burpees over bar. 60 s entre rondas; las dos primeras zonas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Sit-up', '15'),
                ]) + ['description' => '3 rondas: 10 lunges/lado + 15 sit-ups. 60 s entre rondas; zonas 3 y 6.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Thruster', '10'),
                    self::ex('Burpee Over Bar', '10'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Dumbbell Thruster', '8/lado'),
                    self::ex('Sit-up', '20'),
                ]) + ['description' => '2 rondas: mini-ATLAS sin carga (thruster, burpee, lunge, G2OH, sit-up). 60 s entre rondas; el mapa de las zonas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Thruster', '8'),
                    self::ex('Burpee Over Bar', '8'),
                ]) + ['description' => '3 rondas: 8 thrusters + 8 burpees over bar. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Dumbbell Thruster', '8/lado'),
                    self::ex('Bear Crawl', '30s'),
                ]) + ['description' => '3 rondas: 8 G2OH/lado + 40 m de bear crawl. 60 s entre rondas; zonas 4 y 5.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Thruster', '8'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Sit-up', '15'),
                ]) + ['description' => '3 rondas: thruster + lunge + sit-up al 50%. 60 s entre rondas; la mitad del ATLAS con carga.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Thruster', '10'),
                    self::ex('Burpee Over Bar', '10'),
                ]) + ['description' => '3 rondas: 10 thrusters + 10 burpees. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Dumbbell Thruster', '10/lado'),
                    self::ex('Bear Crawl', '40s'),
                    self::ex('Farmers Walk', '40s'),
                ]) + ['description' => '3 rondas: G2OH + bear crawl + farmers al 50%. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Thruster', '10'),
                    self::ex('Burpee Over Bar', '10'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Dumbbell Thruster', '8/lado'),
                    self::ex('Sit-up', '20'),
                ]) + ['description' => 'MEDIA ATLAS: AMRAP estimado: 10 thrusters + 10 burpees over bar + 10 lunges/lado + 8 G2OH/lado + 20 sit-ups al 50%. Rondas estimadas; anota rondas completas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Thruster', '10'),
                    self::ex('Dumbbell Thruster', '8/lado'),
                ]) + ['description' => '3 rondas: 10 thrusters Foundation + 8 G2OH Foundation. 90 s entre rondas; las zonas de barra.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Standing Dumbbell Press', '10'),
                    self::ex('Sit-up', '20'),
                ]) + ['description' => '3 rondas: lunge + press + sit-up Foundation. 90 s entre rondas; las zonas de mancuerna.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Farmer Walk', '60s'),
                    self::ex('Jump Rope', '100'),
                ]) + ['description' => '2 rondas: farmers 100 m + shoulder carry 100 m + 100 single unders, Foundation. 90 s entre rondas; las zonas de carga y cuerda.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Thruster', '12'),
                    self::ex('Burpee Over Bar', '10'),
                ]) + ['description' => '3 rondas: 12 thrusters + 10 burpees Foundation. 90 s entre rondas; primeras zonas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Dumbbell Thruster', '10/lado'),
                    self::ex('Bear Crawl', '45s'),
                    self::ex('Farmers Walk', '45s'),
                ]) + ['description' => '3 rondas: G2OH + bear crawl + farmers Foundation. 90 s entre rondas; zonas medias.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA ATLAS Foundation',
                    'benchmarkNote' => 'Benchmark embebido: DEKA ATLAS Foundation (10 zonas, división Foundation, cap 30:00). Stopwatch + cap; la marca alimenta el test. Plan: dividir thrusters 10+10 desde el principio; burpees en ritmo constante; carries sin soltar; anota el tiempo y el reparto por zonas.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 1, 1, 15, [
                    self::ex('Thruster', '20'),
                    self::ex('Burpee Over Bar', '20'),
                ]) + ['description' => 'Media ATLAS Foundation: 20 thrusters + 20 burpees over bar, por tiempo. Compara el ritmo por zona con el benchmark.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Thruster', '10'),
                    self::ex('Dumbbell Thruster', '8/lado'),
                ]) + ['description' => '3 rondas: thruster y G2OH al 75% hacia Peak. 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Bear Crawl', '45s'),
                ]) + ['description' => '3 rondas: lunge + bear crawl cargados. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Thruster', '10'),
                    self::ex('Dumbbell Lunge', '12/lado'),
                    self::ex('Farmers Walk', '50s'),
                    self::ex('Standing Dumbbell Press', '10'),
                ]) + ['description' => '3 rondas: cuatro zonas al 75% (thruster, lunge, farmers, press). 90 s entre rondas; la transición a Peak se entrena así.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Thruster', '8'),
                    self::ex('Dumbbell Thruster', '8/lado'),
                ]) + ['description' => '3 rondas: thruster y G2OH Peak. 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Bear Crawl', '45s'),
                    self::ex('Farmer Walk', '45s'),
                ]) + ['description' => '2 rondas: lunge + bear crawl + shoulder carry Peak. 90 s entre rondas; las zonas más pesadas.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA ATLAS Peak',
                    'benchmarkNote' => 'Benchmark embebido: DEKA ATLAS Peak (10 zonas, división Peak, cap 30:00), PRIMERA toma de contacto con mínimo generoso. Stopwatch + cap; la marca alimenta el test. Plan: terminar entero es la victoria; divide cada zona desde el principio.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 1, 1, 15, [
                    self::ex('Thruster', '20'),
                    self::ex('Burpee Over Bar', '20'),
                ]) + ['description' => 'Media ATLAS Peak: 20 thrusters Peak + 20 burpees over bar, por tiempo. Compara con la Foundation.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Thruster', '8'),
                    self::ex('Burpee Over Bar', '10'),
                ]) + ['description' => '2 rondas: thruster + burpee Peak. 90 s entre rondas; afilar.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Dumbbell Thruster', '8/lado'),
                    self::ex('Farmer Walk', '45s'),
                ]) + ['description' => '2 rondas: G2OH + shoulder carry Peak. 90 s entre rondas.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA ATLAS Peak',
                    'benchmarkNote' => 'Benchmark embebido: DEKA ATLAS Peak (10 zonas, división Peak, cap 30:00), TEST FINAL con mínimos exigentes. Stopwatch + cap; la marca alimenta el test y cierra el programa. Plan: el reparto por zonas escrito se ejecuta; últimas dos zonas a fondo.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '2 rondas suaves: cuerda + sit-ups. Cierre del programa.'],
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
            ]) + ['description' => '2 rondas: band pull-aparts + wrist prayer. Hombro y muñeca para el simulacro.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Band Pull-Apart', '12'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas suaves: band pull-aparts + wrist prayer. Cierre del nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }
}
