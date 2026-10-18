<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa DEKA FIT Ultra v1 (8 niveles).
 *
 * Sin puertas: strict_gates evalúa los mínimos (benchmarks lte N3/N6-N8 y
 * fuerza en los demás). Tips de ultra: nutrición (60-90 g carb/hora tras la
 * 1ª hora), hidratación + electrolitos, pit stops de ≤2 min, ritmo negativo,
 * zapatilla y anti-rozaduras, "caminar las subidas y los runs rotos es legal;
 * parar no".
 */
class DekaFitUltraContent
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

    public static function getAchievements(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => self::achievementsL1(),
            2 => self::achievementsL2(),
            3 => self::achievementsL3(),
            4 => self::achievementsL4(),
            5 => self::achievementsL5(),
            6 => self::achievementsL6(),
            7 => self::achievementsL7(),
            8 => self::achievementsL8(),
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Run de volumen + fuerza',
                'session_b' => 'Ergómetros largos',
                'session_c' => 'Calentamiento del simulacro',
                'session_d' => 'Recuperación + Movilidad',
                default => 'Volumen y técnica',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Simulacro Ultra / Benchmark',
                'session_d' => 'Core suave',
                default => 'WOD',
            },
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab lumbar y core',
                'session_b' => 'Prehab de hombro',
                'session_c' => 'Prehab de core',
                'session_d' => 'Prehab de trío',
                default => 'Prehab',
            },
            TrainingRound::BLOCK_DESCARGA => 'Descarga',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    public static function getBlockDescription(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, tobillo, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s. El tobillo es el del run 25 km: se prepara antes de cada sesión.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Run Z2 por tiempo (distancia en notas) + lunge y farmers: el motor y el agarre del Ultra.',
                'session_b' => 'Ergómetros largos Z2 (45-60 min): el volumen aeróbico sin impacto.',
                'session_c' => 'Calentamiento específico del simulacro con dosis cortas.',
                default => 'Recuperación activa: movilidad de cadera/hombro, agarre y core suave.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Rondas parciales (2×-4× Fit) o el benchmark embebido (F3). Caminar los runs rotos es legal; parar no. En el Ultra real: pausas de pit stop ≤2 min.',
                'session_d' => 'Core suave: el tronco quieto de 25 km.',
                default => 'WOD de la sesión: ritmo sostenible, siempre.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado). Calidad por encima de fatiga; descanso 45-60 s.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de tobillo y cadera. En Fase 4 se amplía con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (estrategia ultra y regla §1.7).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'El Ultra se construye en Z2: si puedes conversar, vas bien. El run de hoy es el km 20 de dentro de unas semanas.',
                'session_b' => 'Los ergómetros largos son el corazón silencioso del programa: nadie los aplaude y todos los notan en el km 18.',
                'session_c' => 'La lógica del Fit: run + zona, run + zona. Aprende el mapa hoy; vivirás en él 25 km.',
                'session_d' => 'Tobillo, cadera y agarre: las tres cosas que te dejan caminar mañana. No los saltes.',
            ],
            2 => [
                'session_a' => 'La media Fit duplicada empieza con las piernas frescas: no gastes el run de hoy, adminístralo.',
                'session_b' => 'Practica comer en el esfuerzo: 60-90 g de carbohidratos por hora después de la primera. El estómago se entrena como las piernas.',
                'session_c' => 'Primer pit stop de verdad: para, comes, bebes y sales en menos de 2 minutos. El reloj no perdona los descansos largos.',
                'session_d' => 'Recupera el tobillo: la semana que viene hay test Doble.',
            ],
            3 => [
                'session_a' => 'Semana del test: el run de hoy es movimiento, no construcción. Llega fresco al Doble.',
                'session_b' => 'Último ergómetro largo antes del test: Z2 pura y la mochila de nutrición preparada.',
                'session_c' => 'DEKA FIT DOBLE: 2× el circuito, cap 3 h. Plan: pit stop entre rondas ≤2 min, ritmo constante, nada de héroes en el km 1. La marca de hoy es la base de todo.',
                'session_d' => 'Después del Doble: tobillo, cadera y mucha agua con electrolitos.',
            ],
            4 => [
                'session_a' => 'El triple volumen empieza con el run más largo del nivel: Z2 siempre, el ego se queda en casa.',
                'session_b' => '60 minutos de ergómetro son el domingo del Ultra: aburrido y decisivo.',
                'session_c' => 'Sesión larga: 3× el Fit con pit stops planificados. La segunda vuelta se siente igual; la tercera es otra historia: ahí se entrena la cabeza.',
                'session_d' => 'Después de la sesión larga, movilidad completa: lo que estiras hoy no se rompe mañana.',
            ],
            5 => [
                'session_a' => 'El umbral del Ultra: run Z2 y farmers. El agarre del km 20 se construye hoy.',
                'session_b' => 'Último ergómetro largo fuerte: de aquí en adelante, mantener.',
                'session_c' => '4× Fit parcial con pit stops de ≤2 min: la práctica del Ultra sin el riesgo. Anota tiempos por vuelta.',
                'session_d' => 'Semana previa al test Ultra: movilidad, sueño y carbos. El trabajo ya está hecho.',
            ],
            6 => [
                'session_a' => 'Run suave y farmers cortos: llegar fresco vale más que entrenar fuerte esta semana.',
                'session_b' => 'Ergómetro moderado: mantener el motor encendido, no gastarlo.',
                'session_c' => 'DEKA FIT ULTRA: 50 zonas + 25 km, cap 8 h. Nutrición por hora, pit stops ≤2 min, ritmo negativo. Caminar los runs rotos es legal; parar no. La Media Ultra (3×) es el scaled documentado.',
                'session_d' => 'Después del Ultra: lo que haces hoy decide cómo entrenas la semana que viene: agua, electrolitos, movilidad y sueño.',
            ],
            7 => [
                'session_a' => 'Segundo Ultra: el run de hoy es activación. El cuerpo recuerda; la cabeza decide.',
                'session_b' => 'Ergómetro suave: frescura para el ritmo negativo del sábado.',
                'session_c' => 'SEGUNDO ULTRA con ritmo negativo planificado: la segunda mitad más rápida que la primera. Compara la marca con la del N6: la mejora está en los pit stops y en el km 20.',
                'session_d' => 'Recupera como si fuera la semana 1: la diferencia entre repetir y romperse está aquí.',
            ],
            8 => [
                'session_a' => 'Semana del cierre: run suave, farmers técnicos. Llega entero.',
                'session_b' => 'Último ergómetro del programa: 45 minutos de gratitud.',
                'session_c' => 'ULTRA FINAL: la marca de competición. El reparto escrito se ejecuta; los pit stops son tuyos; el último run se corre como el primero. Cierra el programa.',
                'session_d' => 'Cierre: movilidad, respiración y las tres marcas del programa en la cabeza.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y nivel (distancias y nutrición).
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Sprint' => 'Run Z2 por tiempo: 25-35 min (distancia según ritmo). Ritmo conversacional.',
            ],
            2 => [
                'Sprint' => 'Run Z2: 30-40 min. Nutrición practicada: 60-90 g carb/hora tras la 1ª hora.',
            ],
            3 => [
                'Sprint' => 'Run Z2: 35-45 min. Semana del test Doble.',
            ],
            4 => [
                'Sprint' => 'Run Z2: 40-50 min en F3. La sesión larga completa 3× DEKA Fit con el benchmark #80.',
            ],
            5 => [
                'Sprint' => 'Run Z2: hasta 50 min. El 4× Fit parcial se completa con el benchmark #80 (notas).',
            ],
            6, 7, 8 => [
                'Sprint' => 'Run Z2 suave (mantener). En el Ultra: cada run de 500 m se administra; caminar los rotos es legal.',
            ],
            default => [],
        };
    }

    // ========================================================================
    // LEVELS — tips, fases y tests
    // ========================================================================

    private static function baseTips(string $f1, string $fallo): array
    {
        return [
            'Fase 1 (Base): '.$f1,
            'Fase 2 (Progresión): sube el volumen; los pit stops se practican.',
            'Fase 3 (Intensificación): la sesión C con el mayor estímulo (benchmark embebido en N3/N6-N8).',
            'Fase 4 (Recuperación): mitad de volumen; movilidad y test de nivel.',
            'Regla §1.7 (ultra): caminar las subidas y los runs rotos es legal; parar no. Nutrición 60-90 g carb/hora tras la 1ª hora; hidratación + electrolitos; pit stops de ≤2 min; ritmo negativo; zapatilla y anti-rozaduras.',
            'Si fallas el test: '.$fallo,
        ];
    }

    private static function phases(string $f1, string $f2, string $f3, string $f4): array
    {
        return [
            ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => $f1, 'note' => 'Volumen Z2', 'intensity' => '55%'],
            ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => $f2, 'note' => 'Pit stops practicados', 'intensity' => '80%'],
            ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => $f3, 'note' => 'El mayor estímulo del nivel', 'intensity' => '95%'],
            ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => $f4, 'note' => 'Test de nivel al final', 'intensity' => '50%'],
        ];
    }

    private static function level1(): array
    {
        return [
            'tips' => self::baseTips(
                'Base de kilometraje: runs Z2 de 25-40 min y zonas ligeras',
                'baja el volumen a 20 min Z2 y repite; la base se construye sin prisa.'
            ),
            'trainingWeeks' => self::phases('Base de kilometraje Z2 (runs 25-40 min, ergómetros 45-60 min)', 'Media DEKA Fit ligera', 'Media vuelta del Fit: la lógica run + zona', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Sprint', 'minimum' => 30, 'target' => 40, 'unit' => 'min', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Run Z2 continuo en minutos (la unidad del ítem son minutos): ritmo conversacional, sin caminar'],
                        ['name' => 'Farmers Walk', 'minimum' => 40, 'target' => 60, 'unit' => 'meters', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '2×20/2×12 kg: 40-60 m sin soltar, postura erguida'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => self::baseTips(
                'Doble formato: media DEKA Fit ×2 con nutrición practicada',
                'vuelve a una media Fit con pit stop de 2 min; el doble llega cuando el estómago aguanta.'
            ),
            'trainingWeeks' => self::phases('Media DEKA Fit ×2 (nutrición practicada)', 'Pit stops de ≤2 min entre medias', '2× media Fit con pit stop real', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Sprint', 'minimum' => 35, 'target' => 45, 'unit' => 'min', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Run Z2 continuo en minutos'],
                        ['name' => 'Dumbbell Lunge', 'minimum' => 40, 'target' => 60, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Media DEKA Fit ×2 (zonas en notas): 40-60 lunges acumulados sin romper técnica'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => self::baseTips(
                'DEKA Fit Doble: 2× el circuito con pit stop entre rondas',
                'vuelve a 1× DEKA Fit completa; el Doble llega con el ritmo constante.'
            ),
            'trainingWeeks' => self::phases('Preparación del Doble (ritmo constante)', 'Vuelta del Fit completa por tiempo', 'Benchmark embebido: DEKA Fit Doble (cap 3:00)', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Fit Doble', 'minimum' => 11700, 'target' => 10500, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Fit Doble', 'form' => 'Benchmark DEKA Fit Doble (2× DEKA Fit = 20 zonas + 10 km, cap 3:00). Mínimo generoso 3:15 (11700 s, por encima de beginner M 3:20 — regla F-Elizabeth); objetivo 2:50 (intermediate M). La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => self::baseTips(
                'Triple volumen: 3× DEKA Fit en la sesión larga',
                'vuelve a 2× Fit; el triple llega cuando los pit stops son automáticos.'
            ),
            'trainingWeeks' => self::phases('Triple volumen: 3× Fit en sesión larga', 'Walks cargados y runs largos', 'Sesión larga: 3× Fit con pit stops', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Sprint', 'minimum' => 50, 'target' => 60, 'unit' => 'min', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Run Z2 continuo en minutos: 50-60 min, ritmo conversacional'],
                        ['name' => 'Farmers Walk', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '2×22,5/2×15 kg (Foundation): 100 m sin soltar'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => self::baseTips(
                '4× Fit parcial con pit stops planificados y ritmo negativo',
                'vuelve a 3× Fit; el 4× llega cuando el ritmo negativo sale solo.'
            ),
            'trainingWeeks' => self::phases('4× Fit parcial: volumen con gestión del pit', 'Ritmo negativo ensayado', '4× Fit con pit stops de ≤2 min', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Sprint', 'minimum' => 60, 'target' => 60, 'unit' => 'min', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Run Z2 continuo en minutos: 60 min (el mayor del programa)'],
                        ['name' => 'Dumbbell Lunge', 'minimum' => 100, 'target' => 150, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Zona 1 del Fit al 75%: 100-150 lunges acumulados en la sesión larga'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => self::baseTips(
                'DEKA Fit Ultra: 50 zonas + 25 km, cap 8 h, con nutrición y pit stops',
                'la Media Ultra (3×) es el scaled documentado: regístralo y repite el nivel.'
            ),
            'trainingWeeks' => self::phases('Mantenimiento del volumen', 'Frescura para el Ultra', 'Benchmark embebido: DEKA FIT ULTRA (cap 8:00)', 'Recuperación del Ultra; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Fit Ultra', 'minimum' => 27000, 'target' => 22500, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Fit Ultra', 'form' => 'Benchmark DEKA FIT ULTRA (5× DEKA Fit = 50 zonas + 25 km, cap 8:00). Mínimo realista 7:30 (27000 s, beginner M; regla F-Elizabeth bajo el cap); objetivo 6:15 (intermediate M). Scaled documentado: Media Ultra 3×. La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => self::baseTips(
                'Segundo Ultra con ritmo negativo planificado',
                'compara la marca con la del N6: si empeoró, el descanso (no el esfuerzo) fue el problema.'
            ),
            'trainingWeeks' => self::phases('Frescura para el segundo Ultra', 'Ritmo negativo ensayado', 'Benchmark embebido: DEKA FIT ULTRA (segundo intento)', 'Recuperación; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Fit Ultra', 'minimum' => 27000, 'target' => 21600, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Fit Ultra', 'form' => 'Benchmark DEKA FIT ULTRA (5× Fit, cap 8:00), segundo intento con ritmo negativo: la segunda mitad más rápida que la primera. Objetivo 6:00 (advanced-intermediate). Compara con la marca del N6. La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => self::baseTips(
                'Ultra final: la marca de competición',
                'las tres marcas del programa (N6/N7/N8) se comparan: la mejor es la tuya.'
            ),
            'trainingWeeks' => self::phases('Afilar el cierre', 'Frescura final', 'Benchmark embebido: DEKA FIT ULTRA FINAL', 'Cierre del programa'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA Fit Ultra', 'minimum' => 27000, 'target' => 22500, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA Fit Ultra', 'form' => 'Benchmark DEKA FIT ULTRA (5× Fit, cap 8:00), test final y cierre del programa. Mínimo 7:30 (regla F-Elizabeth); objetivo 6:15 (intermediate M), en torno al cutoff de age group 9:00 y el élite 3:45/4:15 como referencia de tabla. La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LOGROS (~6 por nivel, codes ultra-nX-)
    // ========================================================================

    private static function hito(string $code, string $name, string $description, string $icon, string $tier, int $xp, bool $celebration = false): array
    {
        return [
            'code' => $code, 'name' => $name, 'description' => $description,
            'category' => 'skill', 'icon' => $icon, 'tier' => $tier, 'type' => Achievement::TYPE_HITO,
            'specialCelebration' => $celebration,
            'requirementType' => null, 'requirementKey' => null,
            'xpReward' => $xp,
        ];
    }

    private static function testAchievements(string $prefix, array $items): array
    {
        $out = [];
        foreach ($items as $i => $item) {
            $out[] = [
                'code' => $prefix.'-'.$i, 'name' => $item[0], 'description' => $item[1],
                'category' => 'progress', 'icon' => $item[2], 'tier' => $item[3], 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => $item[4], 'requirementKey' => $item[5],
                'requirementValue' => $item[6] ?? null,
                'xpReward' => $item[7],
            ];
        }

        return $out;
    }

    private static function achievementsL1(): array
    {
        return [
            ...self::testAchievements('ultra-n1', [
                ['Run de 30', 'Supera el mínimo de run Z2 (30 min) en el test de Nivel 1.', '🏃', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Sprint', null, 50],
                ['Farmers de 40 m', 'Supera el mínimo de Farmers Walk (40 m) en el test de Nivel 1.', '🧳', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Farmers Walk', null, 50],
            ]),
            self::hito('ultra-n1-primer-km', 'Primer kilómetro Z2', 'Primer run Z2 de 25+ minutos a ritmo conversacional.', '🏃', Achievement::TIER_COMUN, 50),
            self::hito('ultra-n1-mapa-fit', 'Mapa del Fit', 'Primera media vuelta del circuito Fit (run + zona) completada.', '🗺️', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            ...self::testAchievements('ultra-n2', [
                ['Run de 35', 'Supera el mínimo de run Z2 (35 min) en el test de Nivel 2.', '🏃', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Sprint', null, 50],
            ]),
            self::hito('ultra-n2-primer-carb', 'Primer carb en carrera', 'Primera toma de 60-90 g de carbohidratos/hora practicada en el esfuerzo.', '🍌', Achievement::TIER_COMUN, 50),
            self::hito('ultra-n2-pit-stop', 'Pit stop de 2 minutos', 'Primer pit stop de ≤2 minutos practicado (comer, beber, salir).', '⏱️', Achievement::TIER_COMUN, 50),
            self::hito('ultra-n2-media-fit', 'Media Fit', 'Primera media DEKA Fit completada en la sesión C.', '🏁', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            ...self::testAchievements('ultra-n3', [
                ['Doble Fit completado', 'Completa la DEKA Fit Doble dentro del cap de 3:00 (mínimo 3:15) en el test de Nivel 3.', '🏆', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA Fit Doble', null, 200],
                ['Doble bajo 2:50', 'Completa la DEKA Fit Doble en menos de 2:50 (objetivo del test de Nivel 3).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA Fit Doble', 10500, 300],
            ]),
            self::hito('ultra-n3-cap-doble', 'Cap del Doble respetado', 'Primera DEKA Fit Doble terminada bajo el cap de 3 horas.', '⏱️', Achievement::TIER_RARO, 100, true),
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            ...self::testAchievements('ultra-n4', [
                ['Run de 50', 'Supera el mínimo de run Z2 (50 min) en el test de Nivel 4.', '🏃', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'Sprint', null, 100],
                ['Farmers Foundation', 'Completa 100 m de Farmers Walk Foundation (2×22,5/2×15 kg) sin soltar.', '🧳', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'Farmers Walk', null, 100],
            ]),
            self::hito('ultra-n4-triple', 'Triple Fit', 'Primera sesión de 3× DEKA Fit completada en la sesión larga.', '🔁', Achievement::TIER_EPICO, 200),
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            ...self::testAchievements('ultra-n5', [
                ['Run de 60', 'Supera el mínimo de run Z2 (60 min) en el test de Nivel 5.', '🏃', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'Sprint', null, 200],
                ['Ciento cincuenta lunges', 'Supera el objetivo de Dumbbell Lunge (150 reps) en el test de Nivel 5.', '⚡', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_OBJ, 'Dumbbell Lunge', 150, 150],
            ]),
            self::hito('ultra-n5-ritmo-negativo', 'Ritmo negativo ensayado', 'Primera sesión con la segunda mitad más rápida que la primera.', '📉', Achievement::TIER_RARO, 100),
            self::hito('ultra-n5-pit-automatico', 'Pit automático', 'Pit stops de ≤2 min sin mirar el reloj: comer, beber, salir.', '⏱️', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            ...self::testAchievements('ultra-n6', [
                ['Primera Ultra completada', 'Completa la DEKA FIT ULTRA (50 zonas + 25 km) dentro del cap de 8:00 en el test de Nivel 6.', '🏆', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA Fit Ultra', null, 300],
                ['Ultra bajo 6:15', 'Completa la DEKA FIT ULTRA en menos de 6:15 (objetivo del test de Nivel 6).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA Fit Ultra', 22500, 300],
            ]),
            self::hito('ultra-n6-25km', '25 kilómetros', 'Primer Ultra con los 25 km de carrera completados (rondas del benchmark).', '🏃', Achievement::TIER_EPICO, 200, true),
            self::hito('ultra-n6-media-ultra', 'Media Ultra', 'Primera Media Ultra (3× DEKA Fit) como scaled documentado.', '🔁', Achievement::TIER_RARO, 100),
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            ...self::testAchievements('ultra-n7', [
                ['Segunda Ultra', 'Completa el segundo DEKA FIT ULTRA dentro del cap de 8:00 en el test de Nivel 7.', '🏁', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA Fit Ultra', null, 200],
                ['Ritmo negativo ejecutado', 'Segundo Ultra con la segunda mitad más rápida que la primera (objetivo de marcas).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA Fit Ultra', 21600, 300],
            ]),
            self::hito('ultra-n7-comparacion', 'Comparación de marcas', 'Las dos marcas del Ultra (N6/N7) registradas y comparadas.', '📊', Achievement::TIER_RARO, 100),
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            ...self::testAchievements('ultra-n8', [
                ['Ultra de cierre', 'Completa la DEKA FIT ULTRA final dentro del cap de 8:00 en el test de Nivel 8.', '🏆', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA Fit Ultra', null, 300],
                ['La marca de competición', 'Completa la DEKA FIT ULTRA final en menos de 6:15 (objetivo del test de Nivel 8).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA Fit Ultra', 22500, 300],
            ]),
            self::hito('ultra-n8-deka-fit-ultra', 'DEKA FIT Ultra dominada', 'Cierre del programa: las 50 zonas + 25 km del Ultra bajo el cap de 8:00.', '🏁', Achievement::TIER_LEGENDARIO, 500, true),
        ];
    }
}
