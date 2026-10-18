<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Kipping & T2B Mastery v1 (8 niveles).
 *
 * Especialización de skill: kipping y toes to bar. Progresión:
 * swing → rodillas → T2B → volumen → combos con tracción → dominio en fatiga.
 * Estructura: 4 sesiones por fase (A=Skill barra, B=Tracción y fuerza,
 * C=Core + prehab hombro/muñeca, D=Mixta suave + movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N6 y 90 s en N7-N8 (regla del proyecto).
 *
 * Regla §1.7 (hombro/core): primero estricto/base, luego dinámico — el kipping
 * exige hollow sólido; dolor matinal >2/10 en hombro = congelar kipping un
 * ciclo y volver a swing pasivo; nada de volumen de kipping con hombro cansado.
 *
 * Grafías de catálogo (verificadas 2026): 'Hanging Knee Raise' NO existe como
 * tal — se usa 'Knee Raise' (300) con nota de colgado. No existe ficha de
 * swing en barra ('Beat Swing'/'Kip Swing'): se modela con 'Dead Hang'
 * (swing pasivo) y 'Active Hang' (swing activo) + notas. 'Arch Body Hold'
 * existe como 'Arch Hold' (827) y 'Superman' como 'Superman Hold' (298).
 */
class KippingT2bBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base del swing',
                'title' => 'Base del swing — Hollow/arch y colgado',
                'description' => 'Construir la base del kipping: hollow body y arch en el suelo (el motor de la cadera), beat swing pasivo en colgado y el grip que sostendrá todo el programa. Aquí no se persigue el T2B: se construye el arco.',
                'objective' => 'Hollow/arch en el suelo, beat swing pasivo en colgado y grip; base de cadera del kipping',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero',
                'skillFocus' => 'Hollow/arch en el suelo, beat swing pasivo y grip',
            ],
            2 => [
                'name' => 'Nivel 2: Swing potente',
                'title' => 'Swing potente — Kipping activo de cadera',
                'description' => 'Convertir el swing pasivo en kipping activo: cierre de cadera (snap) al frente, cuerpo tenso en el arco, y las primeras rodillas al pecho en colgado. El hollow del suelo se traslada a la barra.',
                'objective' => 'Kipping activo de cadera (cierre hip) y primeras rodillas al pecho en colgado',
                'difficultyRating' => 2,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (hollow 20 s, swing pasivo 10)',
                'skillFocus' => 'Swing activo de cadera y rodillas al pecho',
            ],
            3 => [
                'name' => 'Nivel 3: Rodillas a codos',
                'title' => 'Rodillas a codos — Altura con hollow fuerte',
                'description' => 'Llevar las rodillas a codos/pecho alto con un hollow fuerte, empujando el swing alto, y tocar la primera toes to bar de tentativa. La clave: el retroceso (lean back) del hollow, no la flexión de cadera aislada.',
                'objective' => 'Rodillas a codos con hollow fuerte y primera Toes to Bar (1-3)',
                'difficultyRating' => 3,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (knee raise 8, swing activo 10)',
                'skillFocus' => 'Rodillas a codos, retroceso de hollow y primera T2B',
            ],
            4 => [
                'name' => 'Nivel 4: Primer T2B',
                'title' => 'Primer T2B — Series de 3-5 y cierre de cadera',
                'description' => 'Toes to Bar en series de 3-5 seguidas: pies a la barra con cierre completo de cadera (rodillas al pecho + extensión atrás larga), rebote controlado y hombros lejos de las orejas.',
                'objective' => 'Toes to Bar en series de 3-5 con cierre completo de cadera y rebote controlado',
                'difficultyRating' => 4,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (rodillas 12, primera T2B)',
                'skillFocus' => 'T2B en series de 3-5, cierre de cadera y rebote controlado',
            ],
            5 => [
                'name' => 'Nivel 5: Series cortas',
                'title' => 'Series cortas — 8-12 T2B seguidas',
                'description' => 'Series de 8-12 T2B seguidas con ritmo de rebote estable: cada rep empieza atrás (extensión) y termina arriba (cierre), sin colgar del swing. El fallo típico — colgar cuando el core cede — se entrena aquí.',
                'objective' => 'Series de 8-12 T2B seguidas con ritmo de rebote controlado',
                'difficultyRating' => 5,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (5 T2B)',
                'skillFocus' => 'Series de 8-12 T2B y ritmo de rebote estable',
            ],
            6 => [
                'name' => 'Nivel 6: Kipping pull-up + T2B',
                'title' => 'Combos en barra — Kipping PU + T2B',
                'description' => 'Unir tracción y core en la barra: pares de 2 kipping pull-ups + 4 T2B, ritmo de combo y gestión del swing entre ejercicios. El chest to bar kipping aparece como objetivo paralelo.',
                'objective' => 'Combos de kipping pull-up + T2B y chest to bar como objetivo paralelo',
                'difficultyRating' => 6,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (12 T2B)',
                'skillFocus' => 'Combos kipping PU + T2B y transición de swing entre ejercicios',
            ],
            7 => [
                'name' => 'Nivel 7: Volumen en fatiga',
                'title' => 'Volumen en fatiga — Sets grandes con el pulso alto',
                'description' => 'Sets grandes de T2B tras air bike y en circuitos: la gestión del swing bajo fatiga es la habilidad del nivel. El core cede antes que los flexores: el prehab y el ritmo deciden.',
                'objective' => 'Sets grandes de T2B en fatiga y gestión del swing con el pulso alto',
                'difficultyRating' => 7,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (T2B 15, kipping PU 5)',
                'skillFocus' => 'T2B en fatiga, gestión del swing bajo pulso alto y core cansado',
            ],
            8 => [
                'name' => 'Nivel 8: Dominio',
                'title' => 'Dominio — 30+ T2B y C2B kipping consistente',
                'description' => 'Cierre del programa: series de 30+ T2B, chest to bar kipping consistente y sets de 25 dentro de WODs. El kipping pasa de skill a herramienta de competición.',
                'objective' => 'Series de 30+ T2B, chest to bar kipping consistente y sets de 25 en WODs',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (20 T2B)',
                'skillFocus' => 'Series de 30+ T2B, chest to bar kipping y sets largos en WODs',
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
            1 => 'Hollow/arch y beat swing pasivo',
            2 => 'Swing activo de cadera y rodillas al pecho',
            3 => 'Rodillas a codos y primera T2B',
            4 => 'T2B en series de 3-5',
            5 => 'Series de 8-12 T2B',
            6 => 'Combos kipping PU + T2B',
            7 => 'T2B en fatiga y swing bajo pulso alto',
            8 => 'Series de 30+, C2B y sets largos',
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };

        return match ($sessionKey) {
            'session_a' => ['name' => 'Sesión A: Skill de barra', 'goal' => 'Progresión técnica del nivel: '.$goal.'. WOD corto de barra', 'sessionType' => 'skill'],
            'session_b' => ['name' => 'Sesión B: Tracción y fuerza', 'goal' => 'Tracción estricta, escápula y hollow/arch cargado que sostienen el kipping', 'sessionType' => 'skill'],
            'session_c' => ['name' => 'Sesión C: Core + prehab hombro/muñeca', 'goal' => 'Core anti-extensión y prehab de hombro/muñeca: el kipping castiga el hombro', 'sessionType' => 'skill'],
            'session_d' => ['name' => 'Sesión D: Mixta suave + movilidad', 'goal' => 'Bike suave, zancadas y movilidad de hombro/cadera: recuperación activa', 'sessionType' => 'skill'],
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida; énfasis hombro/muñeca)
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
            'progression' => ['40s', '20s'],
            'intensification' => ['45s', '25s'],
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
            self::ex('Wrist Push-Up', '6'),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Glute Bridge', '12'),
        ];

        // Variante A (skill barra): despertar el grip y el colgado
        if ('session_a' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Dead Hang', '20s');
        }
        // Variante B (tracción): escápula activa antes de tirar
        if ('session_b' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Scapular Pull Up', '5');
        }
        // Variante C (prehab): hombro extra
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
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // El motor del kipping nace en el suelo: hollow y arch perfectos
                self::ex('Hollow Body Hold', match ($phase) {
                    'base' => '20s', 'progression' => '25s', 'intensification' => '30s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, 'deload' === $phase ? 2 : 3),
                self::ex('Arch Hold', match ($phase) {
                    'base' => '20s', 'progression' => '25s', 'intensification' => '30s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, 'deload' === $phase ? 2 : 3),
                self::ex('Dead Hang', match ($phase) {
                    'base' => '20s', 'progression' => '30s', 'intensification' => '40s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, 'deload' === $phase ? 2 : 3),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Active Hang', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 'deload' === $phase ? 2 : 3),
                self::ex('Knee Raise', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 'deload' === $phase ? 2 : 3),
                self::ex('Hollow Body Hold', '30s', 'deload' === $phase ? 1 : 2),
            ], 90),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Active Hang', '10', 3),
                self::ex('Knee Raise', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                // Primera toes to bar de tentativa: retroceso de hollow + cierre
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '1', 'progression' => '2', 'intensification' => '3', 'deload' => '1',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 90),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Knee Raise', '12', 2),
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 4, 'intensification' => 5, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Hollow Body Rock', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Combos en barra: la transición de swing entre ejercicios
                self::ex('Kipping Pull Up', match ($phase) {
                    'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '6', 'progression' => '8', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3),
            ], 120),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '60s', 'progression' => '90s', 'intensification' => '60s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '12', 'progression' => '15', 'intensification' => '18', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Toes to Bar', match ($phase) {
                    'base' => '20', 'progression' => '25', 'intensification' => '30', 'deload' => '20',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 2, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Kipping Pull Up', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 180),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Scapular Pull Up', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Australian Pull Up', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Dead Hang', '30s', 2),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Scapular Pull Up', '8', 3),
                self::ex('Strict Pull Up', match ($phase) {
                    'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Australian Pull Up', '10', 2),
            ], 90),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Strict Pull Up', match ($phase) {
                    'base' => '4', 'progression' => '5', 'intensification' => '6', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Scapular Pull Up', '10', 3),
                self::ex('Tuck L-Sit', '10s', 3),
            ], 90),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Strict Pull Up', match ($phase) {
                    'base' => '6', 'progression' => '8', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 3, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Tuck L-Sit', match ($phase) {
                    'base' => '15s', 'progression' => '20s', 'intensification' => '20s', 'deload' => '15s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Hanging Leg Raise', '10', 2),
            ], 120),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Strict Pull Up', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 3, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('L-Sit', match ($phase) {
                    'base' => '10s', 'progression' => '15s', 'intensification' => '15s', 'deload' => '10s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Hollow Body Hold', '45s', 3),
            ], 120),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Kipping Pull Up', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Strict Pull Up', '8', 3),
                self::ex('Chest to Bar Practice', '5', 3),
            ], 120),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Kipping Pull Up', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 3, 'intensification' => 3, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Strict Pull Up', '10', 3),
                self::ex('Chest to Bar Practice', '8', 3),
            ], 120),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Kipping Pull Up', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 3, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Chest to Bar Practice', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Strict Pull Up', '12', 3),
            ], 150),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        // Core + prehab hombro/muñeca: el kipping castiga el hombro
        [$hollow, $plank] = match ($level) {
            1, 2 => ['25s', '30s'],
            3, 4 => ['30s', '40s'],
            5, 6 => ['40s', '50s'],
            7, 8 => ['45s', '60s'],
            default => throw new \InvalidArgumentException(),
        };
        [$facePull, $dislocate, $wristPushUp] = match ($phase) {
            'base' => ['12', '10', '8'],
            'progression' => ['15', '12', '10'],
            'intensification' => ['15', '15', '10'],
            'deload' => ['12', '10', '8'],
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex('Hollow Body Hold', $hollow, 2),
            self::ex('Plank', $plank, 2),
            self::ex('Dead Bug', match ($phase) {
                'base' => '8/lado', 'progression' => '10/lado', 'intensification' => '12/lado', 'deload' => '8/lado',
                default => throw new \InvalidArgumentException(),
            }, 2),
            self::ex('Bird Dog', '8/lado', 2),
            self::ex('Face Pull', $facePull, 2),
            self::ex('Shoulder Dislocate', $dislocate, 2),
            self::ex('Wrist Push-Up', $wristPushUp, 2),
        ], 45);
    }

    private static function fuerzaSkillD(int $level, string $phase): array
    {
        $bike = match ($level) {
            1 => ['720s', '900s', '1080s', '600s'],
            2 => ['900s', '1080s', '1200s', '720s'],
            3 => ['1080s', '1200s', '1500s', '900s'],
            4 => ['1200s', '1500s', '1800s', '900s'],
            5 => ['1500s', '1800s', '1800s', '900s'],
            6 => ['1500s', '1800s', '1500s', '900s'],
            7 => ['1800s', '1800s', '1500s', '900s'],
            8 => ['1500s', '1800s', '1200s', '900s'],
            default => throw new \InvalidArgumentException(),
        };
        $dumbbellLunge = match ($level) {
            1, 2 => ['8/lado', '10/lado', '10/lado', '8/lado'],
            3, 4 => ['10/lado', '12/lado', '12/lado', '8/lado'],
            default => ['12/lado', '12/lado', '12/lado', '10/lado'],
        };
        $index = match ($phase) {
            'base' => 0, 'progression' => 1, 'intensification' => 2, 'deload' => 3,
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
            self::ex('Assault Bike Sprint', $bike[$index]),
            self::ex('Dumbbell Lunge', $dumbbellLunge[$index], match ($phase) {
                'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                default => throw new \InvalidArgumentException(),
            }),
            self::ex('Shoulder Dislocate', match ($phase) {
                'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                default => throw new \InvalidArgumentException(),
            }),
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
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Dead Hang', '20s'),
                    self::ex('Hollow Body Hold', '20s'),
                ]) + ['description' => '3 rondas: 20 s de colgado + 20 s de hollow. 60 s entre rondas; el swing nace del suelo.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Dead Hang', '30s'),
                    self::ex('Arch Hold', '20s'),
                ]) + ['description' => '3 rondas: 30 s de colgado (swing pasivo: oscila el cuerpo) + 20 s de arch. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Dead Hang', '30s'),
                    self::ex('Knee Raise', '5'),
                ]) + ['description' => '4 rondas: 30 s de swing pasivo + 5 rodillas. 60 s entre rondas; la dosis del test en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Dead Hang', '20s'),
                    self::ex('Hollow Body Hold', '20s'),
                ]) + ['description' => '2 rondas suaves: colgado + hollow. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Active Hang', '10'),
                    self::ex('Knee Raise', '5'),
                ]) + ['description' => '3 rondas: 10 swings activos + 5 rodillas al pecho. 60 s entre rondas; el cierre de cadera manda.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Active Hang', '10'),
                    self::ex('Knee Raise', '8'),
                ]) + ['description' => '4 rondas: 10 swings activos + 8 rodillas. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Knee Raise', '8'),
                    self::ex('Hollow Body Rock', '10'),
                ]) + ['description' => '4 rondas: 8 rodillas en colgado + 10 hollow rocks. 60 s entre rondas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Active Hang', '8'),
                    self::ex('Knee Raise', '5'),
                ]) + ['description' => '2 rondas suaves: swing activo + rodillas. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Knee Raise', '8'),
                    self::ex('Toes to Bar', '1'),
                ]) + ['description' => '3 rondas: 8 rodillas + 1 T2B de tentativa. 60 s entre rondas; el retroceso de hollow primero, la flexión de cadera después.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Knee Raise', '10'),
                    self::ex('Toes to Bar', '1'),
                ]) + ['description' => '4 rondas: 10 rodillas + 1 T2B. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Knee Raise', '10'),
                    self::ex('Toes to Bar', '2'),
                ]) + ['description' => '5 rondas: 10 rodillas + 2 T2B. 60 s entre rondas; la dosis del test en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Knee Raise', '6'),
                ]) + ['description' => '2 rondas suaves: rodillas al pecho. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Toes to Bar', '3'),
                    self::ex('Knee Raise', '6'),
                ]) + ['description' => '3 rondas: 3 T2B + 6 rodillas. 90 s entre rondas; cierre completo de cadera en cada rep.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Toes to Bar', '4'),
                    self::ex('Hollow Body Rock', '10'),
                ]) + ['description' => '4 rondas: 4 T2B + 10 hollow rocks. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 90, 15, [
                    self::ex('Toes to Bar', '5'),
                    self::ex('Knee Raise', '5'),
                ]) + ['description' => '5 rondas: 5 T2B + 5 rodillas. 90 s entre rondas; la dosis del test integrada.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Toes to Bar', '3'),
                ]) + ['description' => '2 rondas suaves: 3 T2B. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Toes to Bar', '6'),
                    self::ex('Knee Raise', '8'),
                ]) + ['description' => '4 rondas: 6 T2B + 8 rodillas. 90 s entre rondas; cada rep empieza atrás y termina arriba.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Toes to Bar', '8'),
                    self::ex('Hollow Body Rock', '12'),
                ]) + ['description' => '4 rondas: 8 T2B + 12 hollow rocks. 90 s entre rondas; ritmo de rebote estable.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Toes to Bar', '10'),
                    self::ex('Knee Raise', '6'),
                ]) + ['description' => '4 rondas: 10 T2B + 6 rodillas. 90 s entre rondas; no colgar del swing cuando el core cede.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Toes to Bar', '5'),
                ]) + ['description' => '2 rondas suaves: 5 T2B. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Kipping Pull Up', '3'),
                    self::ex('Toes to Bar', '6'),
                ]) + ['description' => '3 rondas: 3 kipping PU + 6 T2B. 90 s entre rondas; el swing se mantiene entre ejercicios.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Kipping Pull Up', '2'),
                    self::ex('Toes to Bar', '4'),
                ]) + ['description' => '4 rondas: 2 kipping PU + 4 T2B (el par del combo). 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Kipping Pull Up', '3'),
                    self::ex('Toes to Bar', '6'),
                ]) + ['description' => '4 rondas: 3 kipping PU + 6 T2B. 90 s entre rondas; la dosis del test en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Kipping Pull Up', '2'),
                    self::ex('Toes to Bar', '3'),
                ]) + ['description' => '2 rondas suaves: kipping PU + T2B. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Assault Bike Sprint', '60s'),
                    self::ex('Toes to Bar', '10'),
                ]) + ['description' => '3 rondas: 60 s de air bike + 10 T2B. 90 s entre rondas; la transición del WOD real.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Assault Bike Sprint', '90s'),
                    self::ex('Toes to Bar', '12'),
                ]) + ['description' => '4 rondas: 90 s de air bike + 12 T2B. 90 s entre rondas; el core cede antes que los flexores: ritmo.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Assault Bike Sprint', '90s'),
                    self::ex('Toes to Bar', '15'),
                ]) + ['description' => '3 rondas: 90 s de air bike + 15 T2B. 90 s entre rondas; la dosis del test en fatiga.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Toes to Bar', '6'),
                ]) + ['description' => '2 rondas suaves: 6 T2B frescas. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Toes to Bar', '15'),
                    self::ex('Kipping Pull Up', '5'),
                ]) + ['description' => '3 rondas: 15 T2B + 5 kipping PU. 90 s entre rondas; los sets largos como unidad de trabajo.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Kettlebell Swing Russian', '21'),
                    self::ex('Toes to Bar', '15'),
                ]) + ['description' => '3 rondas: 21 KB swings (24/16 kg) + 15 T2B. 2 min entre rondas; pareja de WOD con sets de 25 en el horizonte.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 15, [
                    self::ex('Toes to Bar', '120s'),
                ]) + ['description' => '2 rondas de AMRAP: 2 min de T2B sin parar. 3 min entre rondas; el formato del cierre.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Toes to Bar', '10'),
                ]) + ['description' => '2 rondas suaves: 10 T2B. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function wodB(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                self::ex('Push Up', '8'),
                self::ex('Sit-up', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '4 rondas: 8 push ups + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas; empuje y core para la barra.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                1, 2 => 4, 3, 4, 5 => 5, default => 5,
            }, 60, 15, [
                self::ex('Australian Pull Up', match ($level) {
                    1, 2 => '6', 3, 4 => '8', default => '10',
                }),
                self::ex('Mountain Climber', '10'),
            ]) + ['description' => 'AMRAP '.(match ($level) {
                1, 2 => '8', default => '10',
            })."': Australian pull ups + mountain climbers. Rondas estimadas para llenar la ventana del AMRAP."],
            'intensification' => match ($level) {
                1 => self::block(TrainingRound::BLOCK_WOD, 4, 30, 15, [
                    self::ex('Scapular Pull Up', '5'),
                    self::ex('Mountain Climber', '30s'),
                ], 30) + ['description' => '4 × (30 s de scapular pull ups / 30 s de mountain climbers). Umbral suave de tracción.'],
                default => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                    2, 3 => 5, default => 6,
                }, 60, 10, [
                    self::ex('Hollow Body Hold', '30s'),
                ], 60) + ['description' => 'Intervalos de hollow: 5-6 × (30 s de hollow / 60 s de descanso). El motor del kipping bajo presión.'],
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
            ]) + ['description' => '2 rondas: band pull-aparts + wrist prayer + glute bridges. 60 s entre rondas; hombro y muñeca del que cuelga el kipping.'],
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
            ]) + ['description' => '3 rondas: face pulls + wrist push-ups + plank. 60 s entre rondas; el seguro de vida del hombro en el kipping.'],
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
            ]) + ['description' => '3 rondas: 10 step ups + 10 KB swings (16/12 kg) + 30 s de hollow. 60 s entre rondas.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Step Up', '8'),
                self::ex('Sit-up', '8'),
            ]) + ['description' => '2 rondas suaves: 8 step ups + 8 sit-ups. Movimiento; el test cierra el nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }
}
