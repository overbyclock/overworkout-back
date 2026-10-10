<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa CrossFit Master v1 (niveles 1-3 de 16).
 *
 * Contenido transcrito literalmente de docs/detalle_crossfit_niveles_01-03.md.
 * Estructura: 4 sesiones por fase (A=Fuerza, B=Skill, C=Metcon/WOD, D=Mixta).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N3 (transición entre bloques).
 *
 * Los AMRAP/EMOM se modelan como rondas estimadas para llenar la ventana del
 * formato: la duración sale de TrainingTimeCalculator, nunca se hardcodea.
 * El formato exacto (p. ej. "AMRAP 8'") va en la descripción del bloque.
 * Los WODs que son benchmarks del catálogo (Helen/Fran/Cindy) se devuelven
 * como ['benchmark' => '<nombre catálogo>'] y el comando los resuelve.
 *
 * Omisiones justificadas (cabecera):
 * - Ninguna: todos los ejercicios citados existen en el catálogo con la grafía
 *   usada (verificado contra SELECT name FROM exercises, consulta 2026-10-10).
 */
class CrossfitMasterBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: On-Ramp',
                'title' => 'On-Ramp — Técnica antes que carga',
                'description' => 'Enseñar los patrones fundamentales de CrossFit (sentadilla, bisagra, empuje vertical, core) con PVC/barra vacía y kettlebell ligero, con movilidad densa y prehab alto. Aquí no se falla: se aprende.',
                'objective' => 'Patrones fundamentales (sentadilla, bisagra, empuje vertical, core anti-extensión) con PVC/barra vacía y KB ligero; movilidad de tobillo/cadera/hombro/muñeca y prehab alto',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero',
                'skillFocus' => 'Patrones básicos: sentadilla, bisagra, empuje vertical y core anti-extensión',
            ],
            2 => [
                'name' => 'Nivel 2: Primer Impulso',
                'title' => 'Primer Impulso — Metcons con estructura',
                'description' => 'Primeros metcons con estructura (intervalos cortos y primer EMOM), primer benchmark escalado como hito de sesión (sin ser test aún) y bisagra potente con Kettlebell Swing progresivo. Se consolidan los patrones de N1 con carga ligera-moderada.',
                'objective' => 'Primeros metcons con estructura (intervalos y primer EMOM), Helen escalada como hito de sesión y bisagra potente con KB swing progresivo',
                'difficultyRating' => 2,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1: Air Squat(20), Plank(40 s), Goblet Squat KB 8/6 kg(10), Kettlebell Deadlift técnico(10)',
                'skillFocus' => 'Bisagra potente con kettlebell, metcon estructurado y primer benchmark escalado',
            ],
            3 => [
                'name' => 'Nivel 3: Consolidación',
                'title' => 'Consolidación — Preparado para RX escalado',
                'description' => 'Consolidar squat/press/hinge con carga ligera-moderada, introducir press de potencia (Push Press), Front Squat ligero y dobles de mancuernas como semilla de thruster; terminar con al menos un benchmark escalado con marca registrada. Al superar N3, el usuario está listo para el tramo RX escalado (N4-N6).',
                'objective' => 'Consolidar squat/press/hinge con carga, introducir Push Press y Front Squat ligero, y terminar con al menos un benchmark escalado con marca registrada',
                'difficultyRating' => 3,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (incl. puerta CF5 superada): Goblet Squat KB 12/8 kg(15), KB Swing Russian 24/16 kg(15), Plank(60 s), Helen escalada ≤18:00',
                'skillFocus' => 'Consolidación de squat/press/hinge con carga y preparación al RX escalado',
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
            self::posturalBlock($levelNumber, $sessionKey),
            self::descargaBlock($levelNumber, $sessionKey, $phase),
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

        $data = [
            'name' => $meta['name'],
            'goal' => $meta['goal'],
            'sessionType' => $meta['sessionType'],
            'blocks' => self::addRestBetweenBlocks($blocks),
        ];

        if (isset($wod['benchmark'])) {
            $data['benchmark'] = $wod['benchmark'];
        }

        return $data;
    }

    private static function sessionMeta(int $level, string $sessionKey): array
    {
        return match ($level) {
            1 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Fuerza básica', 'goal' => 'Sentadilla, press y bisagra con técnica antes que carga', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Skill con barra', 'goal' => 'Press y deadlift con barra vacía más core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Metcon suave', 'goal' => 'Primer metcon con ritmo conversacional', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Mixta — movilidad, carries y core', 'goal' => 'Carries, glúteo y metcon suave con movilidad extra', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            2 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Fuerza básica progresiva', 'goal' => 'Goblet, press y swing progresivo: la puerta CF5 se juega en el swing', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Skill — press de potencia y thruster', 'goal' => 'Primer gesto dinámico (push press) y semilla del thruster de Fran', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Metcon estructurado — primer EMOM', 'goal' => 'Primer EMOM del nivel y primer benchmark (Helen escalada) como hito', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Mixta — carries, bisagra y core', 'goal' => 'Carries, bisagra espejo del swing y halo de hombro', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            3 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Fuerza — squat y bisagra con barra', 'goal' => 'Primer back squat y deadlift con carga: CF1 manda sobre el ego', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Skill — push press, front squat y dominadas', 'goal' => 'Push press, primera sentadilla frontal y primeras negativas de dominada', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Metcon con barra ligera', 'goal' => 'WODs con barra/mancuernas y Fran escalada como hito de fase 3', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Mixta — sentadilla profunda y motor', 'goal' => 'Goblet pesado, carries, halo y Cindy escalada como hito', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function addRestBetweenBlocks(array $blocks): array
    {
        if (\count($blocks) <= 1) {
            return $blocks;
        }

        // N1-N3: transición breve entre bloques (60 s), no descanso de recuperación.
        foreach ($blocks as $index => &$block) {
            if ($index < \count($blocks) - 1) {
                $block['restAfterBlock'] = 60;
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida, sin descansos de fuerza)
    // Variantes: A (tal cual), B (+ dislocates extra y respiración diafragmática),
    // C (énfasis tobillo / ensayo CF5), D (énfasis cadera).
    // ========================================================================

    private static function block0(int $level, string $phase, string $sessionKey): array
    {
        return match ($level) {
            1 => self::block0L1($phase, $sessionKey),
            2 => self::block0L2($phase, $sessionKey),
            3 => self::block0L3($phase, $sessionKey),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function block0L1(string $phase, string $sessionKey): array
    {
        // Torácica
        [$cat, $books, $foam] = match ($phase) {
            'base' => ['8', '6/lado', '6'],
            'progression' => ['10', '8/lado', '8'],
            'intensification' => ['10', '8/lado', '8'],
            'deload' => ['8', '8/lado', '8'],
            default => throw new \InvalidArgumentException(),
        };
        // Tobillo (siembra CF4)
        [$calf, $soleus, $deepSquat] = match ($phase) {
            'base' => ['20s/lado', '20s/lado', '20s'],
            'progression' => ['25s/lado', '25s/lado', '30s'],
            'intensification' => ['30s/lado', '30s/lado', '45s'],
            'deload' => ['30s/lado', '30s/lado', '60s'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera (siembra CF5)
        [$gluteBridge, $hipFlexor, $rockback] = match ($phase) {
            'base' => ['10', '20s/lado', '8'],
            'progression' => ['12', '25s/lado', '10'],
            'intensification' => ['15', '30s/lado', '10'],
            'deload' => ['10', '30s/lado', '8'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro (siembra CF2)
        [$dislocate, $pullApart] = match ($phase) {
            'base' => ['10', '10'],
            'progression' => ['12', '12'],
            'intensification' => ['15', '12'],
            'deload' => ['12', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca (siembra CF6)
        $wrist = match ($phase) {
            'base' => '20s',
            'progression' => '25s',
            'intensification', 'deload' => '30s',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Cat-Camel', $cat),
            self::ex('Open Books', $books),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wrist),
            self::ex('Wrist Extension Stretch', $wrist),
            self::ex('Back-of-Hand Stretch', $wrist),
        ];

        // Variante B (skill): dislocates extra antes de la barra + respiración
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (metcon): énfasis en tobillo + activación de glúteo medio
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Clamshell', '10/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L2(string $phase, string $sessionKey): array
    {
        // Torácica
        [$cat, $books, $foam] = match ($phase) {
            'base', 'progression' => ['10', '8/lado', '8'],
            'intensification' => ['12', '10/lado', '10'],
            'deload' => ['10', '8/lado', '8'],
            default => throw new \InvalidArgumentException(),
        };
        // Tobillo (CF4, siembra)
        [$calf, $soleus, $deepSquat] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '30s'],
            'progression' => ['30s/lado', '30s/lado', '40s'],
            'intensification' => ['30s/lado', '30s/lado', '50s'],
            'deload' => ['30s/lado', '30s/lado', '60s'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/bisagra (CF5, foco nuevo)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base', 'progression' => ['30s/lado', '10', '15'],
            'intensification' => ['30s/lado', '12', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro
        [$dislocate, $pullApart] = match ($phase) {
            'base', 'progression' => ['15', '12'],
            'intensification' => ['15', '15'],
            'deload' => ['12', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca
        $wrist = match ($phase) {
            'base', 'progression', 'deload' => '30s',
            'intensification' => '45s',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Cat-Camel', $cat),
            self::ex('Open Books', $books),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wrist),
            self::ex('Wrist Extension Stretch', $wrist),
            self::ex('Back-of-Hand Stretch', $wrist),
        ];

        // Variante B (skill): dislocates extra + respiración diafragmática
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (metcon): ensayo CF5 (bisagra con barra vacía + RDL pausado)
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Deadlift', '5', 2);
            $exercises[] = self::ex('Kettlebell Romanian Deadlift', '8', 2);
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L3(string $phase, string $sessionKey): array
    {
        // Torácica
        [$cat, $books, $foam] = match ($phase) {
            'base' => ['10', '8/lado', '8'],
            'progression', 'intensification' => ['12', '10/lado', '10'],
            'deload' => ['10', '8/lado', '8'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (CF1, foco nuevo)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['30s', '6/lado'],
            'progression' => ['45s', '8/lado'],
            'intensification' => ['60s', '8/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Tobillo (CF4, foco nuevo)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '10'],
            'progression' => ['40s/lado', '40s/lado', '12'],
            'intensification' => ['45s/lado', '45s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/bisagra (CF5, mantenido)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base', 'progression' => ['30s/lado', '10', '15'],
            'intensification' => ['30s/lado', '12', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro
        [$dislocate, $pullApart] = match ($phase) {
            'base' => ['15', '12'],
            'progression', 'intensification' => ['15', '15'],
            'deload' => ['12', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca
        $wrist = match ($phase) {
            'base', 'progression', 'deload' => '30s',
            'intensification' => '45s',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Cat-Camel', $cat),
            self::ex('Open Books', $books),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wrist),
            self::ex('Wrist Extension Stretch', $wrist),
            self::ex('Back-of-Hand Stretch', $wrist),
        ];

        // Variante B (skill): dislocates extra + respiración diafragmática
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    // ========================================================================
    // BLOQUE POSTURAL (prehab rotado por sesión: A hombro, B muñeca,
    // C lumbar/core, D trío suave)
    // ========================================================================

    private static function posturalBlock(int $level, string $sessionKey): array
    {
        $exercises = match ($level) {
            1 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Scapular Push Up', '8', 2),
                    self::ex('Prone Y Raise', '8', 2),
                    self::ex('Face Pull', '12', 2),
                ],
                'session_b' => [
                    self::ex('Wrist Push-Up', '6', 2),
                    self::ex('Wrist Prayer', '30s', 2),
                    self::ex('Wrist Extension Stretch', '30s', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '8/lado', 2),
                    self::ex('Bird Dog', '6/lado', 2),
                    self::ex('Pallof Press', '10/lado', 2),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '12', 2),
                    self::ex('Wrist Push-Up', '6', 2),
                    self::ex('Dead Bug', '6/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Scapular Push Up', '10', 2),
                    self::ex('Prone Y Raise', '10', 2),
                    self::ex('Face Pull', '15', 2),
                ],
                'session_b' => [
                    self::ex('Wrist Push-Up', '8', 2),
                    self::ex('Wrist Prayer', '30s', 2),
                    self::ex('Wrist Extension Stretch', '30s', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '10/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Pallof Press', '12/lado', 2),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '15', 2),
                    self::ex('Wrist Push-Up', '6', 2),
                    self::ex('Dead Bug', '8/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Scapular Push Up', '10', 3),
                    self::ex('Prone Y Raise', '12', 2),
                    self::ex('Face Pull', '15', 2),
                ],
                'session_b' => [
                    self::ex('Wrist Push-Up', '10', 2),
                    self::ex('Wrist Prayer', '30s', 2),
                    self::ex('Wrist Extension Stretch', '45s', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '12/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Pallof Press', '12/lado', 2),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '15', 2),
                    self::ex('Wrist Push-Up', '8', 2),
                    self::ex('Dead Bug', '10/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };

        return self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, $exercises, 50);
    }

    // ========================================================================
    // BLOQUE DESCARGA (respiración + Child's Pose + estiramiento de la zona)
    // ========================================================================

    private static function descargaBlock(int $level, string $sessionKey, string $phase): array
    {
        $stretch = match ($sessionKey) {
            'session_a' => self::ex('Chest Doorway Stretch', '30s/lado'),
            'session_b' => self::ex('Wrist Extension Stretch', '30s'),
            'session_c' => self::ex('Standing Quad Stretch', '30s/lado'),
            'session_d' => self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Diaphragmatic Breathing', 'deload' === $phase ? '120s' : '90s'),
            self::ex("Child's Pose", 'deload' === $phase ? '60s' : '45s'),
            $stretch,
        ];

        // En Fase 4 (Recuperación) la descarga se amplía con toda la movilidad
        if ('deload' === $phase) {
            $exercises[] = self::ex('Foam Roll Thoracic Extension', '10');
            $exercises[] = self::ex('Open Books', '8/lado');
        }

        return self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, $exercises, 20);
    }

    // ========================================================================
    // BLOQUE FUERZA_SKILL
    // ========================================================================

    private static function fuerzaSkillBlock(int $level, string $sessionKey, string $phase): array
    {
        return match ($level) {
            1 => self::fuerzaSkillL1($sessionKey, $phase),
            2 => self::fuerzaSkillL2($sessionKey, $phase),
            3 => self::fuerzaSkillL3($sessionKey, $phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillL1(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '8', 3),
                    self::ex('Standing Dumbbell Press', '8', 3),
                    self::ex('Kettlebell Deadlift', '8', 3),
                ], 90),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '8', 4),
                    self::ex('Standing Dumbbell Press', '10', 3),
                    self::ex('Kettlebell Deadlift', '10', 3),
                ], 90),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '10', 4),
                    self::ex('Standing Dumbbell Press', '8', 4),
                    self::ex('Kettlebell Deadlift', '10', 4),
                ], 90),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '8', 2),
                    self::ex('Standing Dumbbell Press', '8', 2),
                    self::ex('Kettlebell Deadlift', '8', 2),
                ], 90),
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Strict Press', '5', 4),
                    self::ex('Deadlift', '5', 4),
                    self::ex('Plank', '20s', 3),
                ], 90),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Strict Press', '5', 5),
                    self::ex('Deadlift', '5', 4),
                    self::ex('Plank', '30s', 3),
                ], 90),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Strict Press', '5', 5),
                    self::ex('Deadlift', '5', 5),
                    self::ex('Plank', '40s', 3),
                ], 90),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Strict Press', '5', 3),
                    self::ex('Deadlift', '5', 3),
                    self::ex('Plank', '30s', 2),
                ], 90),
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Air Squat', '10', 2),
                    self::ex('Kettlebell Romanian Deadlift', '8', 2),
                ], 60),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Air Squat', '12', 2),
                    self::ex('Kettlebell Romanian Deadlift', '10', 2),
                ], 60),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Air Squat', '15', 3),
                    self::ex('Kettlebell Romanian Deadlift', '10', 3),
                ], 60),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Air Squat', '10', 2),
                    self::ex('Kettlebell Romanian Deadlift', '8', 2),
                ], 60),
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '20m', 3),
                    self::ex('Glute Bridge', '12', 2),
                    self::ex('Suitcase Carry', '15m/lado', 2),
                ], 60),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '30m', 3),
                    self::ex('Glute Bridge', '15', 3),
                    self::ex('Suitcase Carry', '20m/lado', 2),
                ], 60),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '30m', 4),
                    self::ex('Glute Bridge', '15', 3),
                    self::ex('Suitcase Carry', '20m/lado', 3),
                ], 60),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '20m', 2),
                    self::ex('Glute Bridge', '12', 2),
                    self::ex('Suitcase Carry', '15m/lado', 2),
                ], 60),
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillL2(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '10', 3),
                    self::ex('Strict Press', '5', 3),
                    self::ex('Kettlebell Swing Russian', '8', 4),
                ], 90),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '10', 4),
                    self::ex('Strict Press', '5', 4),
                    self::ex('Kettlebell Swing Russian', '10', 4),
                ], 90),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '12', 4),
                    self::ex('Strict Press', '5', 4),
                    self::ex('Kettlebell Swing Russian', '10', 5),
                ], 90),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '10', 2),
                    self::ex('Strict Press', '5', 2),
                    self::ex('Kettlebell Swing Russian', '8', 2),
                ], 90),
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 4),
                    self::ex('Dumbbell Thruster', '6', 3),
                    self::ex('Plank', '40s', 3),
                ], 90),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 4),
                    self::ex('Dumbbell Thruster', '8', 3),
                    self::ex('Plank', '50s', 3),
                ], 90),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 5),
                    self::ex('Dumbbell Thruster', '6', 4),
                    self::ex('Plank', '60s', 3),
                ], 90),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 3),
                    self::ex('Dumbbell Thruster', '6', 2),
                    self::ex('Plank', '40s', 2),
                ], 90),
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Kettlebell Swing Russian', '8', 2),
                    self::ex('Air Squat', '12', 2),
                ], 60),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Kettlebell Swing Russian', '10', 2),
                    self::ex('Air Squat', '15', 2),
                ], 60),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Kettlebell Swing Russian', '8', 3),
                    self::ex('Air Squat', '15', 3),
                ], 60),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Kettlebell Swing Russian', '8', 2),
                    self::ex('Air Squat', '10', 2),
                ], 60),
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '30m', 3),
                    self::ex('Kettlebell Romanian Deadlift', '8', 3),
                    self::ex('Kettlebell Halo', '8/lado', 2),
                ], 60),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '40m', 3),
                    self::ex('Kettlebell Romanian Deadlift', '10', 3),
                    self::ex('Kettlebell Halo', '10/lado', 2),
                ], 60),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '40m', 4),
                    self::ex('Kettlebell Romanian Deadlift', '10', 4),
                    self::ex('Kettlebell Halo', '8/lado', 3),
                ], 60),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Farmer Carry', '30m', 2),
                    self::ex('Kettlebell Romanian Deadlift', '8', 2),
                    self::ex('Kettlebell Halo', '8/lado', 2),
                ], 60),
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillL3(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Back Squat', '5', 3),
                    self::ex('Strict Press', '5', 3),
                    self::ex('Deadlift', '5', 3),
                ], 120),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Back Squat', '5', 4),
                    self::ex('Strict Press', '5', 3),
                    self::ex('Deadlift', '5', 3),
                ], 120),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Back Squat', '5', 5),
                    self::ex('Strict Press', '5', 4),
                    self::ex('Deadlift', '3', 3),
                ], 120),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Back Squat', '5', 2),
                    self::ex('Strict Press', '5', 2),
                    self::ex('Deadlift', '5', 2),
                ], 120),
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 4),
                    self::ex('Front Squat', '5', 3),
                    self::ex('Jumping Pull Up', '5', 3),
                ], 90),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 4),
                    self::ex('Front Squat', '5', 3),
                    self::ex('Jumping Pull Up', '5', 3),
                ], 90),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 5),
                    self::ex('Front Squat', '5', 4),
                    self::ex('Jumping Pull Up', '5', 3),
                ], 90),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Push Press', '5', 3),
                    self::ex('Front Squat', '5', 2),
                    self::ex('Jumping Pull Up', '5', 2),
                ], 90),
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Deadlift', '5', 2),
                    self::ex('Kettlebell Swing Russian', '10', 2),
                ], 60),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Deadlift', '5', 2),
                    self::ex('Kettlebell Swing Russian', '10', 2),
                ], 60),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Deadlift', '3', 3),
                    self::ex('Kettlebell Swing Russian', '10', 3),
                ], 60),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Deadlift', '5', 2),
                    self::ex('Kettlebell Swing Russian', '8', 2),
                ], 60),
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '12', 3),
                    self::ex('Farmer Carry', '40m', 3),
                    self::ex('Kettlebell Halo', '10/lado', 2),
                ], 90),
                'progression' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '15', 3),
                    self::ex('Farmer Carry', '40m', 4),
                    self::ex('Kettlebell Halo', '10/lado', 2),
                ], 90),
                'intensification' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '15', 4),
                    self::ex('Farmer Carry', '40m', 4),
                    self::ex('Kettlebell Halo', '10/lado', 3),
                ], 90),
                'deload' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
                    self::ex('Goblet Squat', '10', 2),
                    self::ex('Farmer Carry', '30m', 2),
                    self::ex('Kettlebell Halo', '8/lado', 2),
                ], 90),
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // BLOQUE WOD
    //
    // AMRAP: rondas estimadas para llenar la ventana (4-5 s/rep según el
    // calculador); el formato exacto va en la descripción del bloque.
    // EMOM: 1 ronda por minuto, los dos ejercicios alternos en la misma ronda.
    // Benchmark: ['benchmark' => nombre del catálogo] + nota de escalado.
    // ========================================================================

    private static function wodBlock(int $level, string $sessionKey, string $phase): array
    {
        return match ($level) {
            1 => self::wodL1($sessionKey, $phase),
            2 => self::wodL2($sessionKey, $phase),
            3 => self::wodL3($sessionKey, $phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function wodL1(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::amrapBlock(6, [
                    self::ex('Air Squat', '5'),
                    self::ex('Push Up', '5'),
                ]),
                'progression' => self::amrapBlock(8, [
                    self::ex('Air Squat', '6'),
                    self::ex('Push Up', '4'),
                    self::ex('Jumping Jack', '6'),
                ]),
                'intensification' => self::amrapBlock(8, [
                    self::ex('Air Squat', '8'),
                    self::ex('Push Up', '5'),
                    self::ex('Jumping Jack', '8'),
                ]),
                'deload' => self::amrapBlock(5, [
                    self::ex('Air Squat', '5'),
                    self::ex('Push Up', '5'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::roundsBlock(3, 75, [
                    self::ex('Ring Row - Feet on Ground', '6'),
                    self::ex('Sit-up', '8'),
                ]),
                'progression' => self::roundsBlock(4, 60, [
                    self::ex('Ring Row - Feet on Ground', '8'),
                    self::ex('Sit-up', '10'),
                ]),
                'intensification' => self::roundsBlock(4, 60, [
                    self::ex('Ring Row - Feet on Ground', '8'),
                    self::ex('Sit-up', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]),
                'deload' => self::roundsBlock(2, 75, [
                    self::ex('Ring Row - Feet on Ground', '6'),
                    self::ex('Sit-up', '8'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::amrapBlock(6, [
                    self::ex('Air Squat', '10'),
                    self::ex('Sit-up', '5'),
                    self::ex('Jumping Jack', '10'),
                ]),
                'progression' => self::amrapBlock(8, [
                    self::ex('Air Squat', '10'),
                    self::ex('Push Up', '5'),
                    self::ex('Jumping Jack', '10'),
                ]),
                // 4 × (1' trabajo / 1' descanso): 30 s swing + 30 s sentadilla
                'intensification' => self::roundsBlock(4, 60, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Air Squat', '8'),
                ], "Intervalos 4 × (1' trabajo / 1' descanso): 30 s Kettlebell Swing Russian (16/12 kg) + 30 s Air Squat en cada intervalo. Swing ruso a la altura del pecho, nunca americano en fatiga alta (§10.2)."),
                'deload' => self::amrapBlock(6, [
                    self::ex('Air Squat', '10'),
                    self::ex('Sit-up', '5'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::roundsBlock(3, 75, [
                    self::ex('Box Step Over', '8'),
                    self::ex('Mountain Climber', '8'),
                    self::ex('Glute Bridge', '8'),
                ]),
                'progression' => self::roundsBlock(4, 60, [
                    self::ex('Box Step Over', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Glute Bridge', '10'),
                ]),
                'intensification' => self::roundsBlock(4, 60, [
                    self::ex('Box Step Over', '10'),
                    self::ex('Dumbbell Thruster', '8'),
                    self::ex('Sit-up', '10'),
                ], '4 rondas: 10 Box Step Over + 8 Dumbbell Thruster (mancuernas ligeras) + 10 Sit-up. Primer gesto de thruster con carga mínima (semblanza de Fran).'),
                'deload' => self::roundsBlock(2, 75, [
                    self::ex('Box Step Over', '8'),
                    self::ex('Mountain Climber', '8'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function wodL2(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::amrapBlock(8, [
                    self::ex('Air Squat', '10'),
                    self::ex('Sit-up', '8'),
                ]),
                'progression' => self::amrapBlock(10, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '5'),
                ]),
                'intensification' => self::amrapBlock(10, [
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Push Up', '6'),
                ], "AMRAP 10': si la bisagra se rompe, baja KB (§10.4)."),
                'deload' => self::amrapBlock(8, [
                    self::ex('Air Squat', '8'),
                    self::ex('Sit-up', '8'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::roundsBlock(4, 75, [
                    self::ex('Dumbbell Thruster', '8'),
                    self::ex('Ring Row - Feet on Ground', '10'),
                ]),
                'progression' => self::roundsBlock(4, 60, [
                    self::ex('Dumbbell Thruster', '10'),
                    self::ex('Ring Row - Feet on Ground', '10'),
                ]),
                'intensification' => self::roundsBlock(5, 60, [
                    self::ex('Dumbbell Thruster', '8'),
                    self::ex('Ring Row - Feet on Ground', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]),
                'deload' => self::roundsBlock(2, 75, [
                    self::ex('Dumbbell Thruster', '6'),
                    self::ex('Ring Row - Feet on Ground', '8'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                // Primer EMOM del nivel: minutos impares swing, pares sentadilla
                'base' => self::emomBlock(6, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Air Squat', '6'),
                ]),
                'progression' => self::emomBlock(10, [
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Box Step Over', '5'),
                ], 'Minutos impares 10 Kettlebell Swing Russian (16/12 kg); pares 5 Box Step Over. Si no llegas, reduce reps, no la técnica.'),
                // Benchmark: Helen escalada (hito de sesión, no test aún)
                'intensification' => [
                    'benchmark' => 'Helen',
                    'benchmarkNote' => 'Helen en modalidad Scaled: KB permitido menor (16/12 kg) y Pull Up asistidos con banda o Ring Row - Feet on Ground. Primera ejecución del benchmark en sesión (logro "Helen escalada"); el tiempo no cuenta aún para el test.',
                ],
                'deload' => self::amrapBlock(8, [
                    self::ex('Air Squat', '8'),
                    self::ex('Sit-up', '6'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::roundsBlock(3, 75, [
                    self::ex('Box Step Over', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Sit-up', '10'),
                ]),
                'progression' => self::roundsBlock(4, 60, [
                    self::ex('Box Step Over', '10'),
                    self::ex('Mountain Climber', '12'),
                    self::ex('Glute Bridge', '10'),
                ]),
                'intensification' => self::amrapBlock(12, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Box Step Over', '8'),
                    self::ex('Sit-up', '8'),
                ], "Primer AMRAP de 3 movimientos y 12' del nivel. Bisagra bajo fatiga = examen de CF5."),
                'deload' => self::roundsBlock(2, 75, [
                    self::ex('Box Step Over', '8'),
                    self::ex('Mountain Climber', '8'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function wodL3(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::amrapBlock(8, [
                    self::ex('Air Squat', '10'),
                    self::ex('Push Up', '8'),
                ]),
                'progression' => self::forTimeBlock([
                    self::ex('Dumbbell Thruster', '21'),
                    self::ex('Ring Row - Feet on Ground', '21'),
                    self::ex('Dumbbell Thruster', '15'),
                    self::ex('Ring Row - Feet on Ground', '15'),
                    self::ex('Dumbbell Thruster', '9'),
                    self::ex('Ring Row - Feet on Ground', '9'),
                ], '21-15-9: Dumbbell Thruster (ligero) + Ring Row - Feet on Ground. Semilla de Fran sin ser benchmark; descansa solo lo necesario.'),
                'intensification' => self::forTimeBlock([
                    self::ex('Dumbbell Thruster', '21'),
                    self::ex('Jumping Pull Up', '21'),
                    self::ex('Dumbbell Thruster', '15'),
                    self::ex('Jumping Pull Up', '15'),
                    self::ex('Dumbbell Thruster', '9'),
                    self::ex('Jumping Pull Up', '9'),
                ], '21-15-9: Dumbbell Thruster (ligero) + Jumping Pull Up. Mismo esquema que Fran escalada; mide el tiempo por curiosidad.'),
                'deload' => self::amrapBlock(6, [
                    self::ex('Air Squat', '8'),
                    self::ex('Sit-up', '6'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::roundsBlock(4, 75, [
                    self::ex('Push Press', '6'),
                    self::ex('Jumping Pull Up', '8'),
                ]),
                'progression' => self::roundsBlock(4, 60, [
                    self::ex('Push Press', '6'),
                    self::ex('Negative Pull Up', '6'),
                ], '4 rondas: 6 Push Press (20/15 kg) + 6 Negative Pull Up (bajada 3 s). Primeras negativas de dominada del programa.'),
                'intensification' => self::roundsBlock(5, 60, [
                    self::ex('Push Press', '8'),
                    self::ex('Jumping Pull Up', '8'),
                ]),
                'deload' => self::roundsBlock(2, 75, [
                    self::ex('Push Press', '5'),
                    self::ex('Jumping Pull Up', '5'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::amrapBlock(10, [
                    self::ex('Dumbbell Thruster', '8'),
                    self::ex('Ring Row - Feet on Ground', '8'),
                ]),
                'progression' => self::amrapBlock(12, [
                    self::ex('Push Press', '6'),
                    self::ex('Box Step Over', '8'),
                    self::ex('Sit-up', '8'),
                ], "Primer AMRAP de 12' con barra."),
                // Benchmark: Fran escalada (hito de sesión)
                'intensification' => [
                    'benchmark' => 'Fran',
                    'benchmarkNote' => 'Fran en modalidad Scaled: thruster con 20-30/15-20 kg o mancuernas, Pull Up asistidos con banda o jumping pull-ups controlados. Primera ejecución del benchmark (logro "Fran escalada registrada" cuando además se haga en test); aquí el tiempo es orientativo.',
                ],
                'deload' => self::amrapBlock(8, [
                    self::ex('Air Squat', '8'),
                    self::ex('Sit-up', '6'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::roundsBlock(3, 75, [
                    self::ex('Box Step Over', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]),
                // Benchmark: Cindy escalada (hito de sesión)
                'progression' => [
                    'benchmark' => 'Cindy',
                    'benchmarkNote' => 'Cindy AMRAP 20\' en Scaled: 5 Ring Row - Feet on Ground + 10 Push Up (de rodillas si hace falta) + 15 Air Squat. Primer AMRAP con nombre propio del programa; logro de hito "Cindy escalada".',
                ],
                'intensification' => self::amrapBlock(12, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Box Step Over', '8'),
                    self::ex('Mountain Climber', '8'),
                ], 'Ritmo alto; bisagra bajo fatiga = examen de CF5.'),
                'deload' => self::roundsBlock(2, 75, [
                    self::ex('Box Step Over', '8'),
                    self::ex('Sit-up', '8'),
                ]),
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    /**
     * AMRAP: rondas estimadas para llenar la ventana (el calculador usa 3-5 s/rep;
     * se ancla el máximo a la ventana). El formato va en la descripción.
     */
    private static function amrapBlock(int $minutes, array $exercises, ?string $note = null): array
    {
        $totalReps = 0;
        foreach ($exercises as $exercise) {
            if (preg_match('/(\d+)(?:-(\d+))?/', $exercise['reps'], $m)) {
                $totalReps += (int) ($m[2] ?? $m[1]);
            }
        }

        $rounds = max(1, (int) round(($minutes * 60) / max(1, $totalReps * 5)));

        $block = self::block(TrainingRound::BLOCK_WOD, $rounds, 1, 5, $exercises, 0);
        $block['description'] = "AMRAP {$minutes}'".($note ? ' — '.$note : '');

        return $block;
    }

    /**
     * N rondas con descanso entre rondas (formato tradicional).
     */
    private static function roundsBlock(int $rounds, int $restBetweenRounds, array $exercises, ?string $note = null): array
    {
        $block = self::block(TrainingRound::BLOCK_WOD, $rounds, $restBetweenRounds, 5, $exercises, 0);
        $block['description'] = $note ?? "{$rounds} rondas con {$restBetweenRounds} s entre rondas.";

        return $block;
    }

    /**
     * EMOM: 1 ronda por minuto; los dos ejercicios se alternan en minutos impares/pares.
     */
    private static function emomBlock(int $minutes, array $exercises, ?string $note = null): array
    {
        $block = self::block(TrainingRound::BLOCK_WOD, $minutes, 1, 5, $exercises, 0);
        $block['description'] = "EMOM {$minutes}'".($note ? ' — '.$note : ' — minutos impares y pares alternando los ejercicios; descansa el tiempo que sobre cada minuto.');

        return $block;
    }

    /**
     * 21-15-9 u otro esquema For Time: una ronda con las dosis descendentes.
     */
    private static function forTimeBlock(array $exercises, string $note): array
    {
        $block = self::block(TrainingRound::BLOCK_WOD, 1, 1, 5, $exercises, 0);
        $block['description'] = $note;

        return $block;
    }
}
