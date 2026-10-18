<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa DEKA FIT Ultra v1 (8 niveles).
 *
 * DEKA FIT Ultra = 5× DEKA Fit = 50 zonas + 25 km. Arco de ultra
 * resistencia: el volumen manda y la intensidad baja.
 * Estructura: 4 sesiones por fase (A=Run de volumen + zona de fuerza,
 * B=Ergómetros largos Z2, C=Simulacro/benchmark, D=Recuperación + movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación → fuerza_skill → wod → postural → descarga.
 * restAfterBlock = 60 s en N1-N4 y 90 s en N5-N8.
 *
 * Regla §1.7 (ultra): caminar las subidas y los runs rotos es legal; parar
 * no. Nutrición 60-90 g carb/hora tras la 1ª hora; hidratación + electrolitos;
 * pit stops de ≤2 min; ritmo negativo; zapatilla y anti-rozaduras.
 *
 * Dosis de distancia como tiempo estimado (spec §5): 500 m ≈ '120s',
 * 1 km ≈ '240s', 2 km ≈ '480s'; distancia en descripción. Las sesiones C de
 * N5-N8 son entrenos de 3-6 h reales en competición: el benchmark Ultra
 * (cap 8 h) estima [60 % cap, cap] en la calculadora (comportamiento conocido).
 */
class DekaFitUltraBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base de kilometraje',
                'title' => 'Base de kilometraje — El motor del Ultra',
                'description' => 'Construir la base aeróbica del Ultra: runs Z2 de 5-8 km, ergómetros largos y las zonas DEKA con cargas ligeras. Sin base de kilometraje no hay Ultra: 25 km se caminan en la cabeza primero.',
                'objective' => 'Base aeróbica Z2 (runs 5-8 km, ergómetros 45-60 min) y zonas DEKA ligeras',
                'difficultyRating' => 5,
                'color' => '#4ade80',
                'requirementsSummary' => 'Mínimos de entrada: completar la DEKA Fit (ficha #80) o correr 5 km Z2 sin parar',
                'skillFocus' => 'Base de kilometraje Z2 y zonas DEKA ligeras',
            ],
            2 => [
                'name' => 'Nivel 2: Doble Fit I',
                'title' => 'Doble Fit I — Media DEKA Fit ×2 y nutrición',
                'description' => 'Primer contacto con el doble formato: media DEKA Fit por semana duplicada, nutrición e hidratación en entrenamiento (60-90 g carb/hora tras la primera hora) y los primeros pit stops de ≤2 minutos.',
                'objective' => 'Media DEKA Fit ×2 semanales, nutrición/hidratación practicada y pit stops ≤2 min',
                'difficultyRating' => 6,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (run Z2 y farmers)',
                'skillFocus' => 'Doble formato, nutrición en carrera y pit stops cortos',
            ],
            3 => [
                'name' => 'Nivel 3: Doble Fit II',
                'title' => 'Doble Fit II — Test: DEKA Fit Doble',
                'description' => 'El primer test del programa: DEKA Fit Doble (2× el circuito completo, 20 zonas + 10 km) como benchmark embebido con mínimo generoso bajo el cap de 3 h. La estrategia se escribe antes de empezar.',
                'objective' => 'DEKA Fit Doble (benchmark embebido) bajo el cap de 3:00 con mínimo generoso',
                'difficultyRating' => 6,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (media Fit ×2)',
                'skillFocus' => 'DEKA Fit Doble: 20 zonas con estrategia de ritmo',
            ],
            4 => [
                'name' => 'Nivel 4: Triple volumen',
                'title' => 'Triple volumen — 3× Fit y walks cargados',
                'description' => 'El salto de volumen: 3× DEKA Fit en la sesión larga, runs largos Z2 y walks cargados. El cuerpo aprende a seguir cuando la cabeza ya terminó: es la habilidad del Ultra.',
                'objective' => '3× DEKA Fit en sesión larga, runs largos Z2 y walks cargados',
                'difficultyRating' => 7,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (DEKA Fit Doble bajo cap)',
                'skillFocus' => 'Triple volumen y resistencia mental de sesión larga',
            ],
            5 => [
                'name' => 'Nivel 5: Ultra I',
                'title' => 'Ultra I — 4× Fit parcial y gestión del pit',
                'description' => 'El umbral del Ultra: 4× DEKA Fit parcial con pit stops planificados, nutrición por hora y la gestión del ritmo negativo (la segunda mitad más rápida que la primera).',
                'objective' => '4× DEKA Fit parcial con pit stops planificados y ritmo negativo',
                'difficultyRating' => 7,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (triple volumen)',
                'skillFocus' => '4× Fit, gestión del pit stop y ritmo negativo',
            ],
            6 => [
                'name' => 'Nivel 6: Ultra II',
                'title' => 'Ultra II — Test: DEKA Fit Ultra',
                'description' => 'El test central: DEKA Fit Ultra (5× el circuito, 50 zonas + 25 km, cap 8 h) como benchmark embebido con mínimo realista de 7:30 y objetivo 6:15. El atleta puede ejecutar la "Media Ultra" 3× como scaled documentado.',
                'objective' => 'DEKA Fit Ultra (benchmark embebido, cap 8 h) con mínimo realista; Media Ultra 3× como scaled',
                'difficultyRating' => 8,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (4× Fit parcial)',
                'skillFocus' => 'DEKA Fit Ultra: 50 zonas con nutrición y pit stops',
            ],
            7 => [
                'name' => 'Nivel 7: Repetición',
                'title' => 'Repetición — Segundo intento con ritmo negativo',
                'description' => 'El segundo intento del Ultra: mismo recorrido, estrategia de ritmo negativo afinada y la comparación de marcas como motor. La mejora en el Ultra se gana en los pit stops y en el km 20, no en el km 1.',
                'objective' => 'Segundo DEKA Fit Ultra con ritmo negativo planificado y comparación de marcas',
                'difficultyRating' => 8,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (DEKA Fit Ultra bajo cap)',
                'skillFocus' => 'Repetición del Ultra: ritmo negativo y comparación de marcas',
            ],
            8 => [
                'name' => 'Nivel 8: Cierre',
                'title' => 'Cierre — El Ultra final',
                'description' => 'Cierre del programa: la DEKA Fit Ultra final como marca de competición, con todo lo aprendido en N1-N7: nutrición, pit stops, ritmo negativo y la cabeza fría de 8 horas.',
                'objective' => 'DEKA Fit Ultra final: la marca de competición y el cierre del programa',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (segundo Ultra)',
                'skillFocus' => 'Cierre: la marca de competición del Ultra',
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
            'session_a' => ['name' => 'Sesión A: Run de volumen + fuerza', 'goal' => 'Run Z2 por tiempo/distancia + lunge y farmers: el motor y el agarre del Ultra', 'sessionType' => 'strength'],
            'session_b' => ['name' => 'Sesión B: Ergómetros largos', 'goal' => 'Row/SkiErg/Bike 45-60 min Z2: el volumen aeróbico sin impacto', 'sessionType' => 'skill'],
            'session_c' => ['name' => 'Sesión C: Simulacro Ultra', 'goal' => 'Rondas parciales (2×-4× Fit) o benchmark embebido (F3): la estrategia del Ultra', 'sessionType' => 'circuit'],
            'session_d' => ['name' => 'Sesión D: Recuperación + movilidad', 'goal' => 'Movilidad de cadera/hombro, agarre y core suave: recuperación activa', 'sessionType' => 'skill'],
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD
    // ========================================================================

    private static function block0(int $level, string $phase, string $sessionKey): array
    {
        [$calf, $dislocate, $pullApart] = match ($phase) {
            'base' => ['20s/lado', '10', '10'],
            'progression' => ['25s/lado', '12', '12'],
            'intensification' => ['30s/lado', '15', '12'],
            'deload' => ['30s/lado', '12', '10'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Deep Squat Hold', '40s'),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Scapular Push Up', $scapPushUp),
            self::ex('Glute Bridge', '12'),
        ];

        // Variante A (run + fuerza): activación de agarre antes de farmers
        if ('session_a' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Farmer Walk', '15s');
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
                self::ex('Dead Bug', '10/lado', 2),
                self::ex('Bird Dog', '8/lado', 2),
                self::ex('Pallof Press', '12/lado', 2),
            ],
            'session_b' => [
                self::ex('Scapular Push Up', '10', 2),
                self::ex('Band Pull-Apart', '12', 2),
                self::ex('Face Pull', '12', 2),
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
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
            $exercises[] = self::ex('Shoulder Dislocate', '10');
            $exercises[] = self::ex('Bent-Knee Soleus Stretch', '30s/lado');
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
        // Run Z2 por tiempo + zona de fuerza (lunge/farmers)
        $run = match ($level) {
            1 => ['1500s', '1800s', '2100s', '1200s'],   // 25-35 min
            2 => ['1800s', '2100s', '2400s', '1200s'],
            3 => ['2100s', '2400s', '2700s', '1500s'],
            4 => ['2400s', '2700s', '3000s', '1500s'],
            5 => ['2700s', '3000s', '3000s', '1500s'],
            6 => ['3000s', '3000s', '3300s', '1800s'],
            7 => ['3000s', '3300s', '3000s', '1800s'],
            8 => ['2700s', '3000s', '2400s', '1500s'],
            default => throw new \InvalidArgumentException(),
        };
        $index = match ($phase) {
            'base' => 0, 'progression' => 1, 'intensification' => 2, 'deload' => 3,
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
            self::ex('Sprint', $run[$index]),
            self::ex('Dumbbell Lunge', match ($level) {
                1, 2 => '10/lado', 3, 4 => '12/lado', default => '15/lado',
            }, match ($phase) {
                'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                default => throw new \InvalidArgumentException(),
            }),
            self::ex('Farmers Walk', match ($level) {
                1 => '20s', 2 => '30s', 3, 4 => '40s', default => '45s',
            }, 3),
        ], 90);
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        // Ergómetros largos Z2 (45-60 min) + core
        $erg = match ($level) {
            1 => ['2700s', '3000s', '3300s', '1800s'],
            2 => ['3000s', '3300s', '3600s', '1800s'],
            3 => ['3300s', '3600s', '3600s', '2100s'],
            4, 5 => ['3600s', '3600s', '3300s', '2100s'],
            default => ['3600s', '3600s', '3000s', '1800s'],
        };
        $index = match ($phase) {
            'base' => 0, 'progression' => 1, 'intensification' => 2, 'deload' => 3,
            default => throw new \InvalidArgumentException(),
        };
        $ergExercise = match ($level % 3) {
            0 => 'Rowing Machine Sprint',
            1 => 'SkiErg Sprint',
            default => 'Assault Bike Sprint',
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
            self::ex($ergExercise, $erg[$index]),
            self::ex('Hollow Body Hold', match ($phase) {
                'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                default => throw new \InvalidArgumentException(),
            }, 3),
            self::ex('Glute Bridge', '15', 2),
        ], 60);
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        // Calentamiento específico de las zonas del simulacro (corto)
        [$zona1, $zona2] = match ($level) {
            1, 2 => ['Sprint', 'Dumbbell Lunge'],
            3, 4 => ['Sprint', 'Rowing Machine Sprint'],
            default => ['Sprint', 'Toes to Bar'],
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex($zona1, match ($phase) {
                'base' => '120s', 'progression' => '120s', 'intensification' => '120s', 'deload' => '60s',
                default => throw new \InvalidArgumentException(),
            }),
            self::ex($zona2, match ($phase) {
                'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '6',
                default => throw new \InvalidArgumentException(),
            }, 2),
            self::ex('Shoulder Dislocate', '10'),
        ], 45);
    }

    private static function fuerzaSkillD(int $level, string $phase): array
    {
        $bike = match ($phase) {
            'base' => '90s', 'progression' => '120s', 'intensification' => '90s', 'deload' => '60s',
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex('Assault Bike Sprint', $bike, 2),
            self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
            self::ex('Calf Stretch Wall', '30s/lado'),
            self::ex('Bent-Knee Soleus Stretch', '30s/lado'),
            self::ex('Deep Squat Hold', '45s'),
            self::ex('Wrist Prayer', '30s'),
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
            ]) + ['description' => '3 rondas: 10 KB swings (24/16 kg) + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Dumbbell Lunge', match ($level) {
                    1, 2 => '10/lado', default => '12/lado',
                }),
                self::ex('Step Up', '10'),
            ]) + ['description' => "AMRAP 10': lunges + step ups. Rondas estimadas; las piernas del Ultra bajo fatiga."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                self::ex('Farmer Walk', match ($level) {
                    1, 2 => '30s', default => '45s',
                }),
                self::ex('Box Jump', '8'),
            ]) + ['description' => '4 rondas: farmer walk (agarre) + 8 box jumps. 60 s entre rondas; el agarre del km 20.'],
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
                self::ex('Plank', '30s'),
            ]) + ['description' => '3 rondas: 10 push ups + 10 sit-ups + 30 s de plank. 60-90 s entre rondas.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Rowing Machine Sprint', match ($level) {
                    1, 2 => '60s', default => '90s',
                }),
                self::ex('Mountain Climber', '10'),
            ]) + ['description' => "AMRAP 10': remo + mountain climbers. Rondas estimadas; ritmo aeróbico."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 10, [
                self::ex('SkiErg Sprint', '60s'),
            ], 60) + ['description' => '6 × (60 s de SkiErg / 60 s de descanso). Umbral sostenido para el Ultra.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Band Pull-Apart', '12'),
                self::ex('Wrist Prayer', '30s'),
            ]) + ['description' => '2 rondas suaves: band pull-aparts + wrist prayer. Cierre del nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodC(int $level, string $phase): array
    {
        return match ($level) {
            1 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                ]) + ['description' => '3 rondas: run 500 m + 10 lunges/lado ligeros. 60 s entre rondas; la lógica del Fit.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Box Jump', '10'),
                ]) + ['description' => '3 rondas: run 500 m + row 500 m + 10 box jumps. 60 s entre rondas; tres zonas del Fit.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Box Jump', '10'),
                    self::ex('Sit-up', '15'),
                ]) + ['description' => '4 rondas: media vuelta del Fit ligera (run + lunge + row + box + sit-up). 60 s entre rondas; el mapa del circuito.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '12/lado'),
                ]) + ['description' => '3 rondas: run 500 m + 12 lunges al 50%. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Wall Ball Shot', '15'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Toes to Bar', '10'),
                ]) + ['description' => '2× media DEKA Fit al 50% (run + wall ball + row + T2B + 4 zonas más en notas). 60 s entre medias; primer pit stop practicado.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('SkiErg Sprint', '120s'),
                    self::ex('Box Jump', '15'),
                ]) + ['description' => '3 rondas: run + SkiErg + box jumps al 50%. 60 s entre rondas; la segunda media.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                ]) + ['description' => '3 rondas: run 500 m + lunges al 75%. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 15, [
                    self::ex('Sprint', '240s'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Box Jump', '15'),
                ]) + ['description' => '2 rondas: run 1 km + row + lunge + box (zona del Fit completa). 2 min entre rondas; ritmo de Doble.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Fit Doble',
                    'benchmarkNote' => 'Benchmark embebido: DEKA Fit Doble (2× DEKA Fit = 20 zonas + 10 km, cap 3:00). Stopwatch + cap; la marca alimenta el test. Plan: pit stop de ≤2 min entre rondas; nutrición en el pit; ritmo constante.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                ]) + ['description' => '3 rondas: run 500 m + lunges. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('SkiErg Sprint', '120s'),
                    self::ex('Wall Ball Shot', '20'),
                    self::ex('Toes to Bar', '15'),
                ]) + ['description' => '3 rondas: run + SkiErg + wall ball + T2B. 60 s entre rondas; volumen del triple.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 15, [
                    self::ex('Sprint', '480s'),
                    self::ex('Dumbbell Lunge', '20/lado'),
                    self::ex('Farmers Walk', '60s'),
                ]) + ['description' => '3 rondas: run 2 km + lunges 20/lado + farmers 100 m. 2 min entre rondas; la sesión larga del triple volumen (3× Fit en notas, zonas completas en el benchmark #80).'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                ]) + ['description' => '3 rondas: run 500 m + lunges. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 15, [
                    self::ex('Sprint', '480s'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '20/lado'),
                    self::ex('Box Jump', '20'),
                ]) + ['description' => '2× vuelta del Fit (run 2 km + row + lunge + box + 6 zonas más en notas). 3 min entre vueltas con PIT STOP practicado: comida, hidratación, ≤2 min parados.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 120, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Wall Ball Shot', '25'),
                    self::ex('Toes to Bar', '20'),
                    self::ex('Burpee', '15'),
                ]) + ['description' => '4 rondas: run + wall ball + T2B + burpees. 2 min entre rondas; el 4× Fit parcial se completa con el benchmark #80 (notas).'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                ]) + ['description' => '3 rondas: run 500 m + lunges. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 15, [
                    self::ex('Sprint', '240s'),
                    self::ex('SkiErg Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '20/lado'),
                ]) + ['description' => '3 rondas: run 1 km + SkiErg + lunges. 2 min entre rondas; mantener, no construir.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Fit Ultra',
                    'benchmarkNote' => 'Benchmark embebido: DEKA FIT ULTRA (5× DEKA Fit = 50 zonas + 25 km, cap 8:00). Stopwatch + cap; la marca alimenta el test. EN COMPETICIÓN es una sesión de 6-8 h: usa el paso benchmark con pausas de pit stop (≤2 min, ahí comes/bebes). Scaled documentado: Media Ultra 3×. Nutrición: 60-90 g carb/hora tras la 1ª hora; electrolitos; ritmo negativo.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '12/lado'),
                ]) + ['description' => '2 rondas suaves: run + lunges. Mantener el ritmo.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 15, [
                    self::ex('Sprint', '240s'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                ]) + ['description' => '2 rondas: run 1 km + row + lunges. 2 min entre rondas; frescura para el segundo Ultra.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Fit Ultra',
                    'benchmarkNote' => 'Benchmark embebido: SEGUNDO DEKA FIT ULTRA (5× Fit, cap 8:00) con estrategia de ritmo negativo: la segunda mitad más rápida que la primera. Stopwatch + cap con pausas de pit stop; compara la marca con la del N6.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Sprint', '120s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                ]) + ['description' => '2 rondas suaves: run + lunges. Afilar.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 15, [
                    self::ex('Sprint', '240s'),
                    self::ex('SkiErg Sprint', '120s'),
                ]) + ['description' => '2 rondas: run 1 km + SkiErg. 2 min entre rondas; frescura del cierre.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Fit Ultra',
                    'benchmarkNote' => 'Benchmark embebido: DEKA FIT ULTRA FINAL (5× Fit, cap 8:00): la marca de competición y el cierre del programa. Stopwatch + cap con pausas de pit stop; ritmo negativo escrito; compara las tres marcas del programa.',
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
