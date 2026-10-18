<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Double Under Mastery v1 (8 niveles).
 *
 * Especialización de skill (dobles con cuerda): el criterio de progresión es
 * la serie seguida sin fallo, de menor a mayor volumen/velocidad/fatiga.
 * Estructura: 4 sesiones por fase (A=Skill DU, B=Volumen e intervalos,
 * C=Prehab tobillo/pantorrilla + core, D=Mixta suave + movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N6 y 90 s en N7-N8 (regla del proyecto).
 *
 * Regla §1.7 (tobillo/pantorrilla/rodilla): dolor matinal >2/10 en gemelo/
 * tobillo = bajar volumen un ciclo; la tensión crónica de gemelo es la lesión
 * típica de las dobles. Tips de técnica: muñeca antes que brazo, rebote bajo
 * (1-2 cm), codos cerca del cuerpo, mirada al frente, respiración rítmica.
 *
 * [SIN FICHA]: 'Tibialis Raise' no existe en el catálogo (verificado 2026;
 * tampoco hay nada tipo 'Shin Raise'). El tren tibial se cubre con Pogo Jumps
 * (carga excéntrica dorsiflexión), Calf Raise on Step y Single Leg Calf Raise.
 *
 * Dosis por tiempo ('60s') = maxTimeForReps; las dobles se modelan en reps.
 */
class DoubleUnderBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base de salto',
                'title' => 'Base de salto — Postura y rebote sin fallo',
                'description' => 'Construir la postura de las dobles (cuerpo alto, muñecas cerca de las caderas, rebote bajo de 1-2 cm, codos pegados al cuerpo, mirada al frente), saltos sueltos de 30-60 s sin fallo, pogo jumps y el prehab de pantorrilla/tren tibial que sostendrá todo el programa. Aquí no se persigue la doble: se construye la base que la hace barata.',
                'objective' => 'Postura y rebote bajo sin fallo (saltos sueltos 30-60 s), pogo jumps y prehab de pantorrilla alto; primeras tentativas 1-1-2',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero',
                'skillFocus' => 'Postura de salto, rebote bajo y saltos sueltos sin fallo',
            ],
            2 => [
                'name' => 'Nivel 2: Primeras dobles',
                'title' => 'Primeras dobles — El patrón 1-1-2',
                'description' => 'Aprender el patrón 1-1-2 (salto, salto, doble), saltos altos con muñeca rápida (la muñeca marca el ritmo, no el brazo) y conseguir las primeras dobles aisladas con técnica: rebote bajo, codos cerca del cuerpo, respiración rítmica.',
                'objective' => 'Patrón 1-1-2, saltos altos con muñeca rápida y primeras dobles aisladas (2-3 seguidas)',
                'difficultyRating' => 2,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (saltos sueltos ×100 en 60 s, plank 45 s, 1 doble)',
                'skillFocus' => 'Patrón 1-1-2, muñeca rápida y primeras dobles aisladas',
            ],
            3 => [
                'name' => 'Nivel 3: Series cortas',
                'title' => 'Series cortas — Consistencia 5×3 → 5×5',
                'description' => 'Convertir la doble aislada en serie corta consistente: bloques de 5×3 y 5×5 con descanso completo, consistencia de rebote entre series y la regla del re-enganche: si la cuerda falla, 5 segundos y a volver, nunca perseguir la serie rota.',
                'objective' => 'Series cortas consistentes (5×3 → 5×5) con re-enganche en 5 s y rebote estable entre series',
                'difficultyRating' => 3,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (3 dobles seguidas)',
                'skillFocus' => 'Series cortas 5×3-5×5 y consistencia de rebote',
            ],
            4 => [
                'name' => 'Nivel 4: Volumen base',
                'title' => 'Volumen base — Series de 10-20 y las primeras 50',
                'description' => 'Subir el volumen por serie (10-20 seguidas) y acumular las primeras "50 en el día" repartidas en series limpias, con el prehab de gemelo/soleo ya como hábito fijo. La doble deja de ser un evento: es trabajo.',
                'objective' => 'Series de 10-20 seguidas, acumulación de 50 dobles en el día y prehab de pantorrilla consolidado',
                'difficultyRating' => 4,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (10 dobles seguidas)',
                'skillFocus' => 'Series de 10-20, acumulación de 50 en el día y tolerancia de volumen',
            ],
            5 => [
                'name' => 'Nivel 5: Series largas',
                'title' => 'Series largas — Sets de 30-50 y gestión del fallo',
                'description' => 'Series largas de 30-50 dobles con gestión del fallo: re-enganche en 5 s, respiración rítmica bajo fatiga y eficiencia de muñeca (menos giro, más precisión). El gemelo empieza a ser el cuello de botella: el prehab sube un peldaño.',
                'objective' => 'Series de 30-50 seguidas con re-enganche en 5 s, eficiencia de muñeca y prehab reforzado',
                'difficultyRating' => 5,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (25 dobles seguidas)',
                'skillFocus' => 'Series de 30-50, gestión del fallo y eficiencia de muñeca',
            ],
            6 => [
                'name' => 'Nivel 6: Velocidad',
                'title' => 'Velocidad — Dobles rápidas con rebote bajo',
                'description' => 'Intervalos cronometrados de velocidad (30 s on / 30 s off × 6-8), dobles rápidas con rebote bajo y cadencia alta, y la economía de giro: muñeca antes que brazo, saltos de 1-2 cm incluso a ritmo alto.',
                'objective' => 'Intervalos 30/30 × 6-8, dobles rápidas con rebote bajo y cadencia sostenida',
                'difficultyRating' => 6,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (50 dobles seguidas)',
                'skillFocus' => 'Intervalos de velocidad, rebote bajo rápido y economía de giro',
            ],
            7 => [
                'name' => 'Nivel 7: En fatiga',
                'title' => 'En fatiga — Dobles cuando el cuerpo ya trabaja',
                'description' => 'Dobles tras air bike, remo o burpees: la transición WOD real (10 cal bike + 20 DU × rondas) que exige la competición. La doble cansado es otra doble: misma muñeca, mismo rebote, pulso más alto.',
                'objective' => 'Dobles en transición real (tras bike/row/burpees), 20-30 seguidas con el pulso alto',
                'difficultyRating' => 7,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (60 dobles en 60 s)',
                'skillFocus' => 'Dobles en fatiga, transiciones WOD y técnica bajo pulso alto',
            ],
            8 => [
                'name' => 'Nivel 8: Dominio',
                'title' => 'Dominio — Sets de 100+ y dobles en parejas de WODs',
                'description' => 'Sets de 100+, AMRAP de 2 min y dobles dentro de parejas de WODs (estilo Karen + dobles): la doble como herramienta de competición. Cierre del programa con series de 100+ y la gestión del esfuerzo largo dominada.',
                'objective' => 'Sets de 100+, AMRAP 2 min y dobles integradas en parejas de WODs',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (30 dobles tras 50 saltos sueltos)',
                'skillFocus' => 'Sets de 100+, AMRAP 2 min y dobles en parejas de WODs',
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
            1 => 'Postura y rebote bajo sin fallo',
            2 => 'Patrón 1-1-2 y primeras dobles',
            3 => 'Series cortas 5×3 → 5×5',
            4 => 'Series de 10-20 y 50 en el día',
            5 => 'Series de 30-50 y gestión del fallo',
            6 => 'Intervalos de velocidad y rebote bajo rápido',
            7 => 'Dobles en fatiga y transiciones WOD',
            8 => 'Sets de 100+, AMRAP 2 min y parejas de WODs',
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };

        return match ($sessionKey) {
            'session_a' => ['name' => 'Sesión A: Skill de dobles', 'goal' => 'Progresión técnica del nivel: '.$goal.'. WOD corto de saltos', 'sessionType' => 'skill'],
            'session_b' => ['name' => 'Sesión B: Volumen e intervalos', 'goal' => 'Intervalos de cuerda, pogo jumps y pantorrilla pesada para sostener el volumen', 'sessionType' => 'skill'],
            'session_c' => ['name' => 'Sesión C: Prehab tobillo + core', 'goal' => 'Prehab de pantorrilla/soleo/tren tibial y core anti-extensión: el pilar del programa', 'sessionType' => 'skill'],
            'session_d' => ['name' => 'Sesión D: Mixta suave + movilidad', 'goal' => 'Zancadas, bike suave y movilidad de tobillo/cadera: recuperación activa', 'sessionType' => 'skill'],
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida; énfasis tobillo)
    // ========================================================================
    private static function block0(int $level, string $phase, string $sessionKey): array
    {
        // Tobillo: dosis suben con el nivel (la skill castiga gemelo/soleo)
        [$calf, $soleus] = match ($level) {
            1, 2 => ['20s/lado', '20s/lado'],
            3, 4 => ['25s/lado', '25s/lado'],
            5, 6 => ['30s/lado', '30s/lado'],
            7, 8 => ['35s/lado', '35s/lado'],
            default => throw new \InvalidArgumentException(),
        };
        $calfRaise = match ($phase) {
            'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };
        [$deepSquat, $dislocate, $pullApart] = match ($phase) {
            'base' => ['30s', '10', '10'],
            'progression' => ['40s', '12', '12'],
            'intensification' => ['45s', '15', '12'],
            'deload' => ['45s', '12', '10'],
            default => throw new \InvalidArgumentException(),
        };
        $wristPrayer = match ($phase) {
            'base', 'progression' => '20s', 'intensification' => '25s', 'deload' => '20s',
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
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Glute Bridge', '12'),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (skill): activación del rebote antes de la cuerda
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Pogo Jumps', '10');
        }
        // Variante B (volumen): hombro extra antes de los intervalos
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
        }
        // Variante C (prehab): énfasis tobillo
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
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
                self::ex('Prone Y Raise', '10', 2),
                self::ex('Face Pull', '12', 2),
            ],
            'session_b' => [
                self::ex('Dead Bug', '10/lado', 2),
                self::ex('Bird Dog', '8/lado', 2),
                self::ex('Pallof Press', '12/lado', 2),
            ],
            'session_c' => [
                self::ex('Calf Raise on Step', '12', 2),
                self::ex('Single Leg Calf Raise', '10', 2),
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
            self::ex('Calf Stretch Wall', '30s/lado'),
        ];

        if ('deload' === $phase) {
            $exercises[] = self::ex('Bent-Knee Soleus Stretch', '30s/lado');
            $exercises[] = self::ex('Kneeling Hip Flexor Stretch', '30s/lado');
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
                // Saltos sueltos sin fallo: el objetivo es el rebote, no la altura
                self::ex('Jump Rope', match ($phase) {
                    'base' => '40s', 'progression' => '50s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Pogo Jumps', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                // Tentativas del patrón 1-1-2: saltos altos con muñeca rápida
                self::ex('Double Unders', '1', 3),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '45s', 'progression' => '60s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Patrón 1-1-2: la muñeca marca el ritmo, no el brazo
                self::ex('Double Unders', match ($phase) {
                    'base' => '2', 'progression' => '3', 'intensification' => '3', 'deload' => '2',
                    default => throw new \InvalidArgumentException(),
                }, 5),
                self::ex('Pogo Jumps', '15', 3),
            ], 90),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', '60s', 3),
                // Series cortas con re-enganche: fallo = 5 s y a volver
                self::ex('Double Unders', match ($phase) {
                    'base' => '3', 'progression' => '5', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 5, 'progression' => 5, 'intensification' => 6, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Pogo Jumps', '15', 3),
            ], 90),
            4 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', '60s', 2),
                self::ex('Double Unders', match ($phase) {
                    'base' => '10', 'progression' => '15', 'intensification' => '20', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Tuck Jump', '5', 2),
            ], 120),
            5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', '60s', 2),
                // Series largas: eficiencia de muñeca (menos giro, más precisión)
                self::ex('Double Unders', match ($phase) {
                    'base' => '30', 'progression' => '40', 'intensification' => '50', 'deload' => '25',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 3, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 180),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Velocidad: 30 s on / 30 s off, cadencia alta con rebote bajo
                self::ex('Double Unders', '30s', match ($phase) {
                    'base' => 6, 'progression' => 7, 'intensification' => 8, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Jump Rope', '30s', 3),
            ], 30),
            7 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Dobles en fatiga: misma muñeca, mismo rebote, pulso más alto
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '60s', 'progression' => '90s', 'intensification' => '60s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Double Unders', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '20', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                ...('intensification' === $phase ? [self::ex('Burpee', '10', 3)] : []),
            ], 90),
            8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', '60s', 2),
                // Sets de 100+: la serie larga como unidad de trabajo
                self::ex('Double Unders', match ($phase) {
                    'base' => '50', 'progression' => '75', 'intensification' => '100', 'deload' => '50',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 2, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Pogo Jumps', '20', 2),
            ], 180),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 4, 'intensification' => 5, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Calf Raise on Step', match ($phase) {
                    'base' => '12', 'progression' => '15', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Single Leg Calf Raise', '8', 2),
            ], 60),
            2 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '60s', 'progression' => '60s', 'intensification' => '90s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Calf Raise on Step', match ($phase) {
                    'base' => '15', 'progression' => '15', 'intensification' => '20', 'deload' => '12',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Jump Rope Calf Raises', match ($phase) {
                    'base' => '20', 'progression' => '25', 'intensification' => '30', 'deload' => '20',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            3 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '90s', 'progression' => '90s', 'intensification' => '120s', 'deload' => '60s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Calf Raise on Step', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '20', 'deload' => '12',
                    default => throw new \InvalidArgumentException(),
                }, 4),
                self::ex('Jump Rope Calf Raises', match ($phase) {
                    'base' => '25', 'progression' => '30', 'intensification' => '30', 'deload' => '20',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            4, 5 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '120s', 'progression' => '120s', 'intensification' => '150s', 'deload' => '60s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Calf Raise on Step', match ($phase) {
                    'base' => '20', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Single Leg Calf Raise', match ($phase) {
                    'base' => '12', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 90),
            6 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Intervalos rápidos: cadencia alta, rebote de 1-2 cm
                self::ex('Jump Rope', match ($phase) {
                    'base' => '60s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 6, 'progression' => 6, 'intensification' => 8, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Calf Raise on Step', match ($phase) {
                    'base' => '20', 'progression' => '25', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Pogo Jumps', '25', 3),
            ], 60),
            7, 8 => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Jump Rope', match ($phase) {
                    'base' => '120s', 'progression' => '60s', 'intensification' => '90s', 'deload' => '60s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 6, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Calf Raise on Step', match ($phase) {
                    'base' => '20', 'progression' => '25', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 3),
                self::ex('Single Leg Calf Raise', match ($phase) {
                    'base' => '12', 'progression' => '15', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 90),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillC(int $level, string $phase): array
    {
        // Prehab tobillo/pantorrilla/rodilla: la skill castiga gemelos/soleo/tibialis
        [$calf, $slowRaise, $singleRaise] = match ($level) {
            1, 2 => ['20s/lado', '10', '8'],
            3, 4 => ['25s/lado', '12', '8'],
            5, 6 => ['30s/lado', '12', '10'],
            7, 8 => ['30s/lado', '15', '12'],
            default => throw new \InvalidArgumentException(),
        };
        [$clamshell, $gluteBridge] = match ($phase) {
            'base' => ['10/lado', '12'], 'progression' => ['12/lado', '15'],
            'intensification' => ['12/lado', '15'], 'deload' => ['10/lado', '12'],
            default => throw new \InvalidArgumentException(),
        };

        return self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 45, [
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $calf),
            self::ex('Calf Raise on Step', $slowRaise, 3),
            self::ex('Single Leg Calf Raise', $singleRaise, 2),
            self::ex('Clamshell', $clamshell, 2),
            self::ex('Glute Bridge', $gluteBridge, 2),
            // Tren tibial: no existe ficha de tibialis raise en catálogo ([SIN FICHA]):
            // la carga excéntrica del tibial se cubre con pogo suaves
            self::ex('Pogo Jumps', '15', 2),
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
            self::ex('Deep Squat Hold', match ($phase) {
                'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
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
                    self::ex('Jump Rope', '30s'),
                    self::ex('Pogo Jumps', '10'),
                ]) + ['description' => '3 rondas: 30 s de cuerda suelta + 10 pogo jumps. 60 s entre rondas; rebote bajo, mirada al frente.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Jump Rope', '40s'),
                    self::ex('Pogo Jumps', '12'),
                ]) + ['description' => '4 rondas: 40 s de cuerda suelta + 12 pogo jumps. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Jump Rope', '45s'),
                    self::ex('Tuck Jump', '5'),
                ]) + ['description' => '4 rondas: 45 s de cuerda + 5 tuck jumps. 60 s entre rondas; primer contacto con saltos más altos.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '30s'),
                    self::ex('Pogo Jumps', '8'),
                ]) + ['description' => '2 rondas suaves: 30 s de cuerda + 8 pogo jumps. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Jump Rope', '30s'),
                    self::ex('Double Unders', '2'),
                    self::ex('Tuck Jump', '5'),
                ]) + ['description' => '3 rondas: 30 s de cuerda + 2 dobles (patrón 1-1-2) + 5 tuck jumps. 60 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Jump Rope', '30s'),
                    self::ex('Double Unders', '3'),
                ]) + ['description' => '4 rondas: 30 s de cuerda + 3 dobles. 60 s entre rondas; la serie corta se consolida.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Jump Rope', '30s'),
                    self::ex('Double Unders', '3'),
                    self::ex('Pogo Jumps', '10'),
                ]) + ['description' => '4 rondas: 30 s de cuerda + 3 dobles + 10 pogo jumps. 60 s entre rondas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Jump Rope', '20s'),
                    self::ex('Double Unders', '1'),
                ]) + ['description' => '2 rondas suaves: 20 s de cuerda + 1 doble. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Double Unders', '3'),
                    self::ex('Pogo Jumps', '10'),
                ]) + ['description' => '5 rondas: 3 dobles + 10 pogo jumps. 60 s entre rondas; re-enganche de 5 s si falla la cuerda.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Double Unders', '5'),
                    self::ex('Jump Rope', '20s'),
                ]) + ['description' => '5 rondas: 5 dobles + 20 s de cuerda suelta. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Double Unders', '5'),
                    self::ex('Tuck Jump', '4'),
                ]) + ['description' => '6 rondas: 5 dobles + 4 tuck jumps. 60 s entre rondas; la dosis del test integrada.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Double Unders', '3'),
                    self::ex('Pogo Jumps', '8'),
                ]) + ['description' => '3 rondas suaves: 3 dobles + 8 pogo jumps. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Double Unders', '8'),
                    self::ex('Jump Rope', '20s'),
                ]) + ['description' => '4 rondas: 8 dobles + 20 s de cuerda. 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 15, [
                    self::ex('Double Unders', '12'),
                    self::ex('Pogo Jumps', '10'),
                ]) + ['description' => '4 rondas: 12 dobles + 10 pogo jumps. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 90, 15, [
                    self::ex('Double Unders', '10'),
                    self::ex('Tuck Jump', '5'),
                ]) + ['description' => "5 rondas: 10 dobles + 5 tuck jumps. 90 s entre rondas; 'las 50 del día' repartidas en series limpias."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Double Unders', '5'),
                    self::ex('Jump Rope', '20s'),
                ]) + ['description' => '2 rondas suaves: 5 dobles + 20 s de cuerda. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Double Unders', '20'),
                    self::ex('Pogo Jumps', '10'),
                ]) + ['description' => '3 rondas: 20 dobles + 10 pogo jumps. 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Double Unders', '25'),
                    self::ex('Tuck Jump', '5'),
                ]) + ['description' => '3 rondas: 25 dobles + 5 tuck jumps. 90 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 15, [
                    self::ex('Double Unders', '30'),
                    self::ex('Jump Rope', '20s'),
                ]) + ['description' => '3 rondas: 30 dobles + 20 s de cuerda. 90 s entre rondas; si la cuerda falla: 5 s y se reengancha, nunca perseguir la serie rota.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Double Unders', '10'),
                ]) + ['description' => '2 rondas suaves: 10 dobles. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 30, 15, [
                    self::ex('Double Unders', '30s'),
                    self::ex('Pogo Jumps', '10'),
                ], 30) + ['description' => '4 × (30 s de dobles / 30 s de descanso) + 10 pogo jumps tras cada intervalo. Cadencia alta, rebote de 1-2 cm.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 30, 15, [
                    self::ex('Double Unders', '30s'),
                    self::ex('Jump Rope', '30s'),
                ], 30) + ['description' => '5 × (30 s de dobles / 30 s de cuerda suelta). La recuperación es cuerda suelta, no parada.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 30, 15, [
                    self::ex('Double Unders', '30s'),
                    self::ex('Mountain Climber', '30s'),
                ], 30) + ['description' => '6 × (30 s de dobles / 30 s de mountain climbers). El formato del test en versión corta.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 3, 30, 15, [
                    self::ex('Double Unders', '20s'),
                ], 30) + ['description' => '3 × (20 s de dobles / 30 s de descanso). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Assault Bike Sprint', '60s'),
                    self::ex('Double Unders', '15'),
                ]) + ['description' => '4 rondas: 60 s de air bike + 15 dobles. 90 s entre rondas; la transición del WOD real.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Burpee', '10'),
                    self::ex('Double Unders', '20'),
                ]) + ['description' => '4 rondas: 10 burpees + 20 dobles. 90 s entre rondas; la doble cansado es otra doble.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Assault Bike Sprint', '90s'),
                    self::ex('Double Unders', '20'),
                ]) + ['description' => '3 rondas: 90 s de air bike + 20 dobles. 90 s entre rondas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Jump Rope', '30s'),
                    self::ex('Double Unders', '10'),
                ]) + ['description' => '2 rondas suaves: 30 s de cuerda + 10 dobles. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Double Unders', '30'),
                    self::ex('Pogo Jumps', '10'),
                ]) + ['description' => '3 rondas: 30 dobles + 10 pogo jumps. 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Kettlebell Swing Russian', '21'),
                    self::ex('Double Unders', '30'),
                ]) + ['description' => "3 rondas: 21 KB swings (24/16 kg) + 30 dobles. 2 min entre rondas; pareja de WOD estilo 'Karen + dobles'."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 15, [
                    self::ex('Double Unders', '120s'),
                ]) + ['description' => '2 rondas de AMRAP: 2 min de dobles sin parar. 3 min entre rondas; el formato del test de cierre.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Double Unders', '15'),
                ]) + ['description' => '2 rondas suaves: 15 dobles. Movimiento; el test cierra el nivel.'],
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
                self::ex('Sit-up', '8'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '4 rondas: 8 KB swings (16/12 kg) + 8 sit-ups + 30 s Hollow Body Hold. 60-90 s entre rondas; core básico.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                1, 2 => 4, 3, 4, 5 => 5, default => 6,
            }, 60, 15, [
                self::ex('Pogo Jumps', match ($level) {
                    1, 2 => '10', 3, 4 => '15', default => '15',
                }),
                self::ex('Mountain Climber', '8'),
            ]) + ['description' => 'AMRAP '.(match ($level) {
                1, 2 => '8', 3, 4, 5 => '10', default => '12',
            })."': pogo jumps + mountain climbers. Rondas estimadas para llenar la ventana del AMRAP."],
            'intensification' => match ($level) {
                1 => self::block(TrainingRound::BLOCK_WOD, 4, 30, 15, [
                    self::ex('Jump Rope', '30s'),
                    self::ex('Mountain Climber', '30s'),
                ], 30) + ['description' => '4 × (30 s de cuerda / 30 s de mountain climbers). Umbral suave con fatiga de transición.'],
                default => self::block(TrainingRound::BLOCK_WOD, match ($level) {
                    2, 3 => 5, 4, 5 => 6, 6, 7, 8 => 6,
                }, 60, 10, [
                    self::ex('Jump Rope', '60s'),
                ], 60) + ['description' => 'Intervalos de cuerda: 5-6 × (60 s de cuerda / 60 s de descanso). El volumen del nivel en formato umbral.'],
            },
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Sit-up', '8'),
                self::ex('Glute Bridge', '8'),
            ]) + ['description' => '2 rondas suaves: 8 sit-ups + 8 glute bridges. Movimiento; el test cierra el nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function wodC(int $level, string $phase): array
    {
        return match ($phase) {
            'base' => self::block(TrainingRound::BLOCK_WOD, 2, 60, 15, [
                self::ex('Dead Bug', '8/lado'),
                self::ex('Bird Dog', '6/lado'),
                self::ex('Plank', match ($level) {
                    1, 2, 3 => '30s', default => '45s',
                }),
            ]) + ['description' => '2 rondas: dead bug + bird dog + plank. 60 s entre rondas; core anti-extensión para sostener el rebote.'],
            'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                self::ex('Dead Bug', '10/lado'),
                self::ex('Bird Dog', '8/lado'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '3 rondas: dead bug + bird dog + hollow body hold. 60 s entre rondas.'],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                self::ex('Dead Bug', '10/lado'),
                self::ex('Bird Dog', '8/lado'),
                self::ex('Plank', match ($level) {
                    1, 2, 3 => '40s', default => '60s',
                }),
            ]) + ['description' => '3 rondas: dead bug + bird dog + plank. 60 s entre rondas; el core del que cuelgan las dobles.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Dead Bug', '8/lado'),
                self::ex('Bird Dog', '6/lado'),
            ]) + ['description' => '2 rondas suaves: dead bug + bird dog. Movimiento; el test cierra el nivel.'],
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
            ]) + ['description' => '4 rondas: 10 step ups + 10 mountain climbers + 30 s hollow body hold. 60 s entre rondas.'],
            'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                self::ex('Step Up', '10'),
                self::ex('Kettlebell Swing Russian', '10'),
                self::ex('Hollow Body Hold', '30s'),
            ]) + ['description' => '3 rondas: 10 step ups + 10 KB swings (16/12 kg) + 30 s hollow body hold. 60 s entre rondas.'],
            'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                self::ex('Step Up', '8'),
                self::ex('Sit-up', '8'),
            ]) + ['description' => '2 rondas suaves: 8 step ups + 8 sit-ups. Movimiento; el test cierra el nivel.'],
            default => throw new \InvalidArgumentException(),
        };
    }
}
