<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Spartan OCR Prep v1 (8 niveles).
 *
 * Preparación para Spartan Race (Sprint 5 km/20 obs · Super 10 km/25 ·
 * Beast 21 km/30). Arco: base trail → agarre y mono (SP1) → cuerda y
 * carries (SP2) → Sprint (Sim + SP3) → Super volumen → Super (Sim) → Beast
 * volumen → Beast/Trifecta (Sim). Penalización oficial: obstáculo fallado =
 * 30 burpees. Estructura: 4 sesiones por fase (A=Trail run + fuerza,
 * B=Obstáculos técnica + grip, C=Simulacro, D=Z2 largo + movilidad + core).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación → fuerza_skill → wod → postural → descarga.
 * restAfterBlock = 60 s en N1-N6 y 90 s en N7-N8.
 *
 * Regla §1.7 adaptada: obstáculo con técnica antes que con fuerza bruta; en
 * carrera un fallo cuesta 30 burpees — entrena el estándar antes que la
 * velocidad; dolor matinal >2/10 en hombro/muñeca (cuerda/mono) = bajar
 * volumen un ciclo. Tips de carrera: zapatilla de trail, anti-rozaduras,
 * hidratación en Super/Beast, burpees de penalización en series de 10.
 *
 * Dosis de distancia como tiempo estimado (spec §5): run Z2 30 min = '1800s';
 * crawls/carries 50-100 m ≈ '40s'-'60s'; trail del simulacro embebido en las
 * transiciones (nota del finish, criterio PFT de Hyrox).
 */
class SpartanOcrPrepBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base trail',
                'title' => 'Base trail — El motor sobre terreno',
                'description' => 'Construir la base del OCR: runs Z2 de 30-40 min sobre trail (o cinta con desnivel simulado), fuerza de agarre (dead hang, farmers) y los patrones de gateo y muros. Aquí no hay obstáculo que no se pueda caminar.',
                'objective' => 'Base trail Z2 (30-40 min), fuerza de agarre y patrones de gateo/muros',
                'difficultyRating' => 4,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida',
                'skillFocus' => 'Base trail Z2, agarre y patrones de obstáculos',
            ],
            2 => [
                'name' => 'Nivel 2: Agarre y mono',
                'title' => 'Agarre y mono — SP1: agarre de mono',
                'description' => 'Dominar el agarre que cuelga de todo el OCR: dead hang largo, progresión de Monkey Bars asistidas (columpio de un brazo) y el lanzamiento de lanza con paso cruzado. Evalúa la puerta SP1.',
                'objective' => 'Dead hang 60 s, monkey bars asistidas con columpio y spear throw técnico; puerta SP1',
                'difficultyRating' => 5,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (run Z2 30 min, farmers 40 m)',
                'skillFocus' => 'Agarre colgado, monkey bars y spear throw (SP1)',
            ],
            3 => [
                'name' => 'Nivel 3: Cuerda y carries',
                'title' => 'Cuerda y carries — SP2: la cuerda',
                'description' => 'La cuerda y las cargas: Rope Climb desde negativa controlada hasta la primera subida completa, bucket carry y sandbag carry con postura erguida, y barbed wire crawl rítmico. Evalúa la puerta SP2 con el primer simulacro parcial.',
                'objective' => 'Rope Climb completo (o negativa), bucket/sandbag carry y barbed wire crawl; puerta SP2',
                'difficultyRating' => 6,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (puerta SP1 superada)',
                'skillFocus' => 'Cuerda, carries de carga y gateo bajo (SP2)',
            ],
            4 => [
                'name' => 'Nivel 4: Sprint',
                'title' => 'Sprint — El primer simulador completo',
                'description' => 'El test del Sprint: simulador completo (5 km trail + 8 obstáculos) como benchmark embebido y la puerta SP3 (30 burpees de penalización bajo 2:30). La estrategia de carrera se escribe aquí.',
                'objective' => 'Spartan Sprint Sim (benchmark embebido) bajo el cap de 90:00 y burpees de penalización (SP3)',
                'difficultyRating' => 6,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (puerta SP2 superada)',
                'skillFocus' => 'Spartan Sprint Sim y gestión de la penalización',
            ],
            5 => [
                'name' => 'Nivel 5: Super volumen',
                'title' => 'Super volumen — El doble de trail',
                'description' => 'El salto al Super: runs de 50-60 min, tire flip y carries pesados, obstáculos en circuitos de fatiga y la hidratación en carrera practicada. El volumen manda; la velocidad espera.',
                'objective' => 'Volumen de Super: runs 50-60 min, tire flip, carries pesados y circuitos de obstáculos',
                'difficultyRating' => 7,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (Sprint Sim + SP3)',
                'skillFocus' => 'Volumen de Super y obstáculos bajo fatiga',
            ],
            6 => [
                'name' => 'Nivel 6: Super',
                'title' => 'Super — El segundo simulador',
                'description' => 'El test del Super: simulador completo (10 km + 10 obstáculos) como benchmark embebido con re-tests de SP1 y SP2. La nutrición en carrera (60-90 g carb/h) se practica en los simulacros largos.',
                'objective' => 'Spartan Super Sim (benchmark embebido) bajo el cap de 3:00 y re-tests SP1/SP2',
                'difficultyRating' => 7,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (farmers 100 m, cuerda ×3)',
                'skillFocus' => 'Spartan Super Sim y re-validación de puertas',
            ],
            7 => [
                'name' => 'Nivel 7: Beast volumen',
                'title' => 'Beast volumen — La resistencia del 21 km',
                'description' => 'La resistencia del Beast: runs largos de 90-120 min, sesiones de 2-3 h con nutrición por hora, y los obstáculos pesados en fatiga real. La cabeza del km 15 se entrena aquí.',
                'objective' => 'Volumen de Beast: runs 90-120 min, sesiones largas con nutrición y obstáculos pesados',
                'difficultyRating' => 8,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (Super Sim bajo cap)',
                'skillFocus' => 'Volumen de Beast y nutrición en sesión larga',
            ],
            8 => [
                'name' => 'Nivel 8: Beast / Trifecta',
                'title' => 'Beast / Trifecta — El cierre',
                'description' => 'Cierre del programa: el Spartan Beast Sim (21 km + 12 obstáculos, cap 6 h) como test final con re-test de SP3. La marca de hoy cierra la Trifecta del programa.',
                'objective' => 'Spartan Beast Sim (benchmark embebido) bajo el cap de 6:00 y re-test SP3',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (volumen de Beast)',
                'skillFocus' => 'Spartan Beast Sim y cierre de Trifecta',
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
            'session_a' => ['name' => 'Sesión A: Trail run + fuerza', 'goal' => 'Run Z2 por tiempo + cuerda y carries: el motor y las cargas del OCR', 'sessionType' => 'strength'],
            'session_b' => ['name' => 'Sesión B: Obstáculos técnica', 'goal' => 'Mono, lanza, gateo, muros y grip: la técnica antes que la fuerza bruta', 'sessionType' => 'skill'],
            'session_c' => ['name' => 'Sesión C: Simulacro', 'goal' => 'Circuitos de obstáculos (F1/F2/F4) o benchmark embebido (F3)', 'sessionType' => 'circuit'],
            'session_d' => ['name' => 'Sesión D: Z2 largo + movilidad', 'goal' => 'Ergómetro o trail suave, movilidad de tobillo/cadera/hombro y core', 'sessionType' => 'skill'],
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
        $wristPrayer = match ($phase) {
            'base', 'progression' => '20s', 'intensification' => '30s', 'deload' => '20s',
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
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Glute Bridge', '12'),
        ];

        // Variante A (trail): activación de agarre
        if ('session_a' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Dead Hang', '20s');
        }
        // Variante B (obstáculos): muñecas del mono y la cuerda
        if ('session_b' === $sessionKey && 'deload' !== $phase) {
            $exercises[] = self::ex('Wrist Push-Up', '6');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    // ========================================================================
    // BLOQUE POSTURAL / DESCARGA
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
                self::ex('Wrist Push-Up', '8', 2),
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
        // Trail Z2 por tiempo + obstáculo de fuerza (cuerda/carries)
        $run = match ($level) {
            1 => ['1800s', '2100s', '2400s', '1500s'],
            2 => ['2100s', '2400s', '2700s', '1500s'],
            3 => ['2400s', '2700s', '3000s', '1800s'],
            4 => ['2700s', '3000s', '3300s', '1800s'],
            5 => ['3000s', '3300s', '3600s', '2100s'],
            6 => ['3000s', '3300s', '3600s', '2100s'],
            7 => ['3300s', '3600s', '4500s', '2400s'],
            8 => ['2700s', '3000s', '3300s', '1800s'],
            default => throw new \InvalidArgumentException(),
        };
        $index = match ($phase) {
            'base' => 0, 'progression' => 1, 'intensification' => 2, 'deload' => 3,
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
            self::ex('Sprint', $run[$index]),
            ...match ($level) {
                1 => [self::ex('Dead Hang', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3), self::ex('Farmers Walk', '20s', 3)],
                2 => [self::ex('Monkey Bars', match ($phase) {
                    'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 3), self::ex('Farmers Walk', '30s', 3)],
                3, 4 => [self::ex('Rope Climb', match ($level) {
                    3 => match ($phase) {
                        'base' => '1', 'progression' => '1', 'intensification' => '2', 'deload' => '1',
                        default => throw new \InvalidArgumentException(),
                    },
                    default => match ($phase) {
                        'base' => '2', 'progression' => '3', 'intensification' => '3', 'deload' => '2',
                        default => throw new \InvalidArgumentException(),
                    },
                }, 3), self::ex('Bucket Carry', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3)],
                5, 7 => [self::ex('Bucket Carry', match ($phase) {
                    'base' => '40s', 'progression' => '50s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3), self::ex('Tire Flip', match ($phase) {
                    'base' => '5', 'progression' => '8', 'intensification' => '10', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 3)],
                default => [self::ex('Rope Climb', match ($phase) {
                    'base' => '3', 'progression' => '3', 'intensification' => '4', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 2), self::ex('Farmers Walk', '50s', 3)],
            },
        ], 90);
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Dead Hang', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Bear Crawl', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Wall Walk', '2', 3),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Monkey Bars', match ($phase) {
                    'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Dead Hang', '40s', 3),
                self::ex('Spear Throw', '5', 3),
            ], 60),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Rope Climb', match ($phase) {
                    'base' => '1', 'progression' => '2', 'intensification' => '3', 'deload' => '1',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Spear Throw', '5', 3),
                self::ex('Bear Crawl', '40s', 3),
            ], 90),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Monkey Bars', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Rope Climb', '2', 3),
                self::ex('Spear Throw', '8', 2),
            ], 90),
            5, 7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Tire Flip', match ($phase) {
                    'base' => '5', 'progression' => '8', 'intensification' => '10', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Bucket Carry', match ($phase) {
                    'base' => '40s', 'progression' => '50s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Monkey Bars', '5', 3),
            ], 90),
            default => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Spear Throw', '10', 2),
                self::ex('Rope Climb', '2', 2),
                self::ex('Wall Walk', '2', 2),
            ], 90),
        };
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        [$obstaculo] = match ($level) {
            1, 2 => ['Bear Crawl'],
            3 => ['Rope Climb'],
            4 => ['Monkey Bars'],
            5, 7 => ['Tire Flip'],
            default => ['Spear Throw'],
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex('Sprint', match ($phase) {
                'base' => '120s', 'progression' => '120s', 'intensification' => '120s', 'deload' => '60s',
                default => throw new \InvalidArgumentException(),
            }),
            self::ex($obstaculo, match ($phase) {
                'base' => '3', 'progression' => '4', 'intensification' => '5', 'deload' => '3',
                default => throw new \InvalidArgumentException(),
            }, 2),
            self::ex('Shoulder Dislocate', '10'),
        ], 45);
    }

    private static function fuerzaSkillD(int $level, string $phase): array
    {
        $erg = match ($level) {
            1, 2 => ['600s', '720s', '900s', '480s'],
            3, 4 => ['900s', '1200s', '1500s', '600s'],
            5, 6 => ['1200s', '1500s', '1800s', '900s'],
            default => ['1500s', '1800s', '2100s', '900s'],
        };
        $index = match ($phase) {
            'base' => 0, 'progression' => 1, 'intensification' => 2, 'deload' => 3,
            default => throw new \InvalidArgumentException(),
        };
        $ergExercise = match ($level % 2) {
            0 => 'Rowing Machine Sprint',
            default => 'Assault Bike Sprint',
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex($ergExercise, $erg[$index]),
            self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
            self::ex('Calf Stretch Wall', '30s/lado'),
            self::ex('Deep Squat Hold', '45s'),
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
            ]) + ['description' => '3 rondas: 10 KB swings (24/16 kg) + 10 sit-ups + 30 s de hollow. 60-90 s entre rondas; la bisagra de las cargas.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                self::ex('Dumbbell Lunge', match ($level) {
                    1, 2 => '10/lado', default => '12/lado',
                }),
                self::ex('Step Up', '10'),
            ]) + ['description' => "AMRAP 10': lunges + step ups. Rondas estimadas; las piernas de los carries."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                self::ex('Farmers Walk', match ($level) {
                    1, 2 => '30s', default => '45s',
                }),
                self::ex('Box Jump', '8'),
            ]) + ['description' => '4 rondas: farmers (agarre) + 8 box jumps (muro alto). 60 s entre rondas.'],
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
                self::ex('Bear Crawl', match ($level) {
                    1, 2 => '30s', default => '40s',
                }),
                self::ex('Mountain Climber', '10'),
            ]) + ['description' => "AMRAP 10': barbed wire crawl + mountain climbers. Rondas estimadas; el gateo bajo fatiga."],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 1, 10, [
                self::ex('Wall Walk', match ($level) {
                    1, 2 => '1', default => '2',
                }),
                self::ex('Box Jump', match ($level) {
                    1, 2 => '8', default => '10',
                }),
            ]) + ['description' => "EMOM 6': minutos impares muro (wall walks), pares muro alto (box jumps). Los muros del OCR."],
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
                    self::ex('Bear Crawl', '30s'),
                    self::ex('Box Jump', '8'),
                ]) + ['description' => '3 rondas: barbed wire crawl + 8 box jumps. 60 s entre rondas; los obstáculos de suelo y muro.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Wall Walk', '1'),
                    self::ex('Farmers Walk', '30s'),
                ]) + ['description' => '3 rondas: muro + sandbag carry. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Bear Crawl', '40s'),
                    self::ex('Box Jump', '10'),
                    self::ex('Wall Walk', '1'),
                ]) + ['description' => '3 rondas: mini-circuito de obstáculos (gateo + muros). 60 s entre rondas; el mapa del Sprint.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Monkey Bars', '3'),
                    self::ex('Spear Throw', '3'),
                ]) + ['description' => '3 rondas: mono + lanza. 60 s entre rondas; los obstáculos técnicos.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Dead Hang', '40s'),
                    self::ex('Bear Crawl', '40s'),
                ]) + ['description' => '3 rondas: dead hang + gateo. 60 s entre rondas; el agarre del SP1.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Monkey Bars', '5'),
                    self::ex('Spear Throw', '5'),
                    self::ex('Box Jump', '10'),
                ]) + ['description' => '4 rondas: circuito técnico (mono + lanza + muro). 60 s entre rondas; la dosis del SP1 en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Rope Climb', '1'),
                    self::ex('Bucket Carry', '30s'),
                ]) + ['description' => '3 rondas: cuerda + bucket carry. 60 s entre rondas; el SP2 en construcción.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Rope Climb', '2'),
                    self::ex('Farmers Walk', '40s'),
                ]) + ['description' => '3 rondas: cuerda + sandbag carry. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Rope Climb', '2'),
                    self::ex('Bucket Carry', '40s'),
                    self::ex('Bear Crawl', '40s'),
                ]) + ['description' => '4 rondas: cuerda + carry + gateo. 60 s entre rondas; el SP2 entero en contexto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Monkey Bars', '5'),
                    self::ex('Rope Climb', '2'),
                ]) + ['description' => '2 rondas: mono + cuerda. 60 s entre rondas; afinar los obstáculos del Sprint.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Spear Throw', '5'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 rondas: lanza + 10 burpees (penalización practicada). 60 s entre rondas.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Spartan Sprint Sim',
                    'benchmarkNote' => 'Benchmark embebido: Spartan Sprint Sim (5 km trail + 8 obstáculos, cap 1:30). Stopwatch + cap; la marca alimenta el test. Plan: trail constante, obstáculos con técnica; un fallo = 30 burpees anotados en el finish.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 1, 1, 15, [
                    self::ex('Burpee', '30'),
                ]) + ['description' => 'Los 30 burpees de penalización, por tiempo: la SP3 entera. Series de 10 planificadas.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Tire Flip', '5'),
                    self::ex('Box Jump', '10'),
                ]) + ['description' => '2 rondas: tire flip + muro alto. 60 s entre rondas; los obstáculos pesados del Super.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Bucket Carry', '50s'),
                    self::ex('Tire Flip', '8'),
                ]) + ['description' => '2 rondas: bucket carry + tire flip. 90 s entre rondas; volumen del Super.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Monkey Bars', '5'),
                    self::ex('Rope Climb', '2'),
                    self::ex('Tire Flip', '8'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '3 rondas: circuito de obstáculos del Super con burpees de penalización practicados. 60 s entre rondas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Rope Climb', '3'),
                    self::ex('Monkey Bars', '5'),
                ]) + ['description' => '2 rondas: cuerda + mono (re-tests del nivel). 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Tire Flip', '10'),
                    self::ex('Bucket Carry', '60s'),
                ]) + ['description' => '2 rondas: tire flip + bucket carry. 90 s entre rondas; el volumen del Super.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Spartan Super Sim',
                    'benchmarkNote' => 'Benchmark embebido: Spartan Super Sim (10 km trail + 10 obstáculos, cap 3:00). Stopwatch + cap; la marca alimenta el test. Plan: hidratación en carrera desde el km 5; obstáculo fallado = 30 burpees en series de 10.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Burpee', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '2 rondas suaves: burpees + sit-ups. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Bucket Carry', '60s'),
                    self::ex('Tire Flip', '8'),
                ]) + ['description' => '2 rondas: carries pesados del Beast. 90 s entre rondas; el km 15 se entrena aquí.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Rope Climb', '3'),
                    self::ex('Bear Crawl', '45s'),
                ]) + ['description' => '2 rondas: cuerda + gateo en fatiga. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Tire Flip', '10'),
                    self::ex('Bucket Carry', '60s'),
                    self::ex('Monkey Bars', '8'),
                    self::ex('Burpee', '15'),
                ]) + ['description' => '3 rondas: circuito del Beast con burpees de penalización. 90 s entre rondas; nutrición practicada entre rondas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '40s'),
                ]) + ['description' => '2 rondas suaves: cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                    self::ex('Spear Throw', '5'),
                    self::ex('Rope Climb', '2'),
                ]) + ['description' => '2 rondas: lanza + cuerda. 60 s entre rondas; afinar.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 15, [
                    self::ex('Burpee', '30'),
                    self::ex('Box Jump', '10'),
                ]) + ['description' => '2 rondas: los 30 burpees de penalización (re-test SP3) + muros. 90 s entre rondas; series de 10.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Spartan Beast Sim',
                    'benchmarkNote' => 'Benchmark embebido: Spartan Beast Sim (21 km trail + 12 obstáculos, cap 6:00). Stopwatch + cap; la marca alimenta el test y cierra el programa. Plan: nutrición 60-90 g carb/h desde la 1ª hora, hidratación + electrolitos, burpees de penalización en series de 10. ¡AROO!',
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
