<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa DEKA ATLAS v1 (8 niveles).
 *
 * Sin puertas de movilidad: strict_gates evalúa todos los mínimos del test.
 * Tests por nivel: ítems de fuerza (cargas kg en `form`, reps gte) + ítems
 * benchmark (lte, bajo el cap de 30:00 — regla F-Elizabeth) en N5/N7/N8.
 * Regla §1.7 (hombro/muñeca/lumbar): técnica antes que carga en los
 * thrusters y press cargados; dolor matinal >2/10 = congelar la carga.
 */
class DekaAtlasContent
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
     * Logros del nivel (definición completa; el comando los crea/actualiza).
     */
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
                'session_a' => 'Fuerza de zonas',
                'session_b' => 'Capacidad de trabajo',
                'session_c' => 'Calentamiento de zonas',
                'session_d' => 'Aeróbico suave + Movilidad',
                default => 'Fuerza y técnica',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Simulacro ATLAS / Circuito',
                'session_d' => 'Core suave',
                default => 'WOD de zonas',
            },
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab de hombro',
                'session_b' => 'Prehab lumbar y core',
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
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, hombro, muñeca, escápula y cadera) sin descansos de fuerza: transiciones de ~5 s. Los thrusters y press cargados exigen hombro y muñeca listos.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Fuerza de las zonas con barra y mancuernas (pesos en notas): series largas, descanso completo, técnica de cristal.',
                'session_b' => 'Capacidad de trabajo: pares de zonas con fatiga y EMOMs, el trabajo denso del ATLAS.',
                'session_c' => 'Calentamiento específico de las zonas del simulacro con dosis cortas.',
                default => 'Aeróbico Z2 suave y movilidad de hombro/cadera/agarre: recuperación activa.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Circuitos de zonas (F1/F2/F4) o el benchmark embebido ATLAS (F3). Divide cada zona desde el principio; carries sin soltar.',
                'session_d' => 'Core suave: el tronco quieto de las zonas.',
                default => 'WOD de la sesión: ritmo por zonas, no por héroes.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado). Calidad por encima de fatiga; descanso 45-60 s; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de hombro y cadera. En Fase 4 se amplía con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (regla §1.7 y estrategia ATLAS).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'Hoy solo importa la línea: thruster y press con barra vacía, muñecas listas, espalda quieta. La carga llegará cuando la técnica no se negocie.',
                'session_b' => 'Burpees over bar y sit-ups: el par que abre el ATLAS. Ritmo constante desde el primer rep.',
                'session_c' => 'El mini-ATLAS de hoy es el mapa: recorre las cinco primeras zonas sin parar el reloj y aprende dónde te cuesta cada una.',
                'session_d' => 'Recupera el hombro y la muñeca: la semana que viene entran las mancuernas.',
            ],
            2 => [
                'session_a' => 'Primeras cargas: el 50% de Foundation. Si la recepción del thruster se rompe, la carga baja, no la técnica (§1.7).',
                'session_b' => 'Bear crawl y farmers cargados: el core y el agarre del ATLAS. Camina erguido, mira al frente.',
                'session_c' => 'Media ATLAS al 50%: cinco zonas con carga. Divide los thrusters 6+4; los burpees nunca se aceleran.',
                'session_d' => 'El agarre descansa hoy: muñeca y antebrazo al cien para la semana del media ATLAS seria.',
            ],
            3 => [
                'session_a' => 'Thrusters de 10 y G2OH por brazo: la transición a Foundation se gana aquí, serie a serie.',
                'session_b' => 'Lunge, bear crawl y farmers: tres zonas seguidas con fatiga. Así se siente el ATLAS de verdad.',
                'session_c' => 'MEDIA ATLAS: cinco zonas al 50% sin parar el reloj. Anota las rondas completas: es tu marca de referencia del nivel.',
                'session_d' => 'Recupera la cadena posterior: los carries de la semana que viene pesan el doble.',
            ],
            4 => [
                'session_a' => 'Zonas Foundation: thruster 43/29,5 y press 2×17,5/2×11. Series largas, descanso completo: fuerza pura antes de la primera ATLAS.',
                'session_b' => 'Farmers y shoulder carry Foundation: 100 m sin soltar. Si el agarre falla antes que la postura, es trabajo de agarre lo que falta.',
                'session_c' => 'Las zonas de carga y cuerda: farmers + shoulder carry + 100 single unders. El carry en hombro se camina con el cuerpo erguido y pasos cortos.',
                'session_d' => 'La última semana antes de la ATLAS Foundation: movilidad y frescura son el entrenamiento.',
            ],
            5 => [
                'session_a' => 'Thrusters Foundation de 12 y farmers de 30 s: las zonas que deciden el benchmark. Divide 10+10 desde el principio.',
                'session_b' => 'EMOM de densidad: thrusters y wall balls sin breaks. La densidad es la estrategia del ATLAS.',
                'session_c' => 'ATLAS FOUNDATION: 10 zonas, cap 30:00. Plan: thrusters 10+10, burpees en ritmo, carries sin soltar, cuerda en dos series de 50. Anota el tiempo y el reparto por zonas.',
                'session_d' => 'Después de la primera ATLAS: hombro, muñeca y espalda. La transición a Peak empieza ya.',
            ],
            6 => [
                'session_a' => 'El 75% hacia Peak: cargas mayores, misma línea. Si la recepción adelanta la barra, la carga baja (§1.7).',
                'session_b' => 'Lunge y bear crawl cargados: las zonas de mancuerna del Peak. Core firme, lumbar quieta.',
                'session_c' => 'Cuatro zonas al 75%: la transición se entrena así, con las zonas que más pesan juntas.',
                'session_d' => 'Recupera el agarre: el Peak de la semana que viene pide todo.',
            ],
            7 => [
                'session_a' => 'Peak: thruster 52/34 y carries pesados. Afina la técnica con la carga que pesa.',
                'session_b' => 'Lunge, bear crawl y shoulder carry Peak: las zonas más pesadas juntas. Divide cada una desde el principio.',
                'session_c' => 'ATLAS PEAK (primera): 10 zonas con los pesos más pesados, mínimo generoso. Terminar entero bajo el cap es la victoria; anota el tiempo.',
                'session_d' => 'Después de la primera Peak: movilidad completa. La semana que viene se cierra el programa.',
            ],
            8 => [
                'session_a' => 'Peak de cierre: poco volumen, mucha calidad. El trabajo ya está hecho; hoy se afila.',
                'session_b' => 'G2OH y shoulder carry frescos: las últimas zonas se entrenan ligeras.',
                'session_c' => 'ATLAS PEAK (test final): el reparto por zonas escrito se ejecuta; últimas dos zonas a fondo. La marca de hoy cierra el programa.',
                'session_d' => 'Cierre: movilidad, respiración y la satisfacción de las 10 zonas completas.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y nivel (pesos kg, divisiones Foundation/Peak).
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Thruster' => 'Barra vacía (20/15 kg): línea de recepción, muñecas listas. La carga llega después.',
                'Burpee Over Bar' => 'Sobre barra olímpica: saltar con los dos pies, mirada al frente.',
                'Bear Crawl' => 'Sin carga: rodillas cerca del suelo, cadera baja, espalda plana.',
            ],
            2 => [
                'Thruster' => 'KB 16/12 kg (50% Foundation): recepción limpia, 6+4 en series.',
                'Dumbbell Lunge' => '2×11/2×8 kg (50%): rodilla de atrás al suelo, torso alto.',
                'Standing Dumbbell Press' => '2×8/2×6 kg (50%): strict, sin piernas.',
            ],
            3 => [
                'Thruster' => '2×15/2×10 kg o 30/20 kg: transición a Foundation.',
                'Dumbbell Thruster' => 'G2OH unilateral alternando (10+10 en el ATLAS): recepción en hombro.',
                'Farmers Walk' => '2×12/2×8 kg (50%): pasos cortos, postura erguida.',
            ],
            4 => [
                'Thruster' => 'Foundation 43/29,5 kg (95/65 lb): divide 10+10.',
                'Dumbbell Thruster' => 'G2OH Foundation 22,5/15 kg (50/35 lb), alternando brazos.',
                'Standing Dumbbell Press' => '2×17,5/2×11 kg (~35/25 lb, Standards Chart): strict press.',
                'Farmers Walk' => '100 lb total del rulebook ≈ 2×22,5/2×15 kg: 100 m sin soltar.',
                'Farmer Walk' => 'Shoulder carry Foundation 45/32 kg (100/70 lb) en hombro: 100 m.',
                'Jump Rope' => '100 single unders (cantidad del Standards Chart).',
            ],
            5 => [
                'Thruster' => 'Foundation 43/29,5 kg: el benchmark empieza y termina aquí.',
                'Farmers Walk' => 'Foundation 2×22,5/2×15 kg: 100 m sin soltar.',
            ],
            6 => [
                'Thruster' => '48/32 kg (75% hacia Peak): técnica intacta antes que carga.',
                'Dumbbell Thruster' => '25/18 kg (75%): recepción firme.',
            ],
            7 => [
                'Thruster' => 'Peak 52/34 kg (aprox +20% Foundation; confirmar en el evento).',
                'Bear Crawl' => 'Peak 2×28/2×18 kg: core firme, lumbar quieta.',
                'Farmer Walk' => 'Shoulder carry Peak 57/40 kg (aprox): 100 m en hombro.',
            ],
            8 => [
                'Thruster' => 'Peak 52/34 kg: el test final se ejecuta con el reparto escrito.',
                'Standing Dumbbell Press' => 'Peak 2×22,5/2×14 kg: strict hasta el final.',
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
            'Fase 1 (Base): '.$f1.' con volumen y técnica.',
            'Fase 2 (Progresión): sube la intensidad; los pares de zonas se densifican.',
            'Fase 3 (Intensificación): la sesión C con el mayor estímulo del nivel (benchmark embebido en N5/N7/N8).',
            'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
            'Regla §1.7 (hombro/muñeca/lumbar): técnica antes que carga; si la recepción pierde la línea, la carga baja; dolor matinal >2/10 = congelar la carga un ciclo.',
            'Si fallas el test: '.$fallo,
        ];
    }

    private static function phases(string $f1, string $f2, string $f3, string $f4): array
    {
        return [
            ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => $f1, 'note' => 'Técnica y volumen', 'intensity' => '55%'],
            ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => $f2, 'note' => 'Densidad arriba', 'intensity' => '80%'],
            ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => $f3, 'note' => 'El mayor estímulo del nivel', 'intensity' => '95%'],
            ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => $f4, 'note' => 'Test de nivel al final', 'intensity' => '50%'],
        ];
    }

    private static function level1(): array
    {
        return [
            'tips' => self::baseTips(
                'Técnica de las 10 zonas con barra vacía y KB ligero',
                'vuelve a la técnica con barra vacía: 15 thrusters limpios valen más que 20 forzados.'
            ),
            'trainingWeeks' => self::phases('Técnica de zonas con barra vacía/KB; movilidad hombro/muñeca', 'Primeras cargas ligeras (KB 8/6 kg)', 'Mini-ATLAS sin carga: mapa de las 5 primeras zonas', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Thruster', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Con barra vacía (20/15 kg): recepción en línea, muñecas listas, 2-3 RIR'],
                        ['name' => 'Sit-up', 'minimum' => 20, 'target' => 30, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Con disco ligero (6/4 kg): extensión completa arriba, sin tirón del cuello'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => self::baseTips(
                'Zonas al 50% de Foundation (thruster KB 16/12, lunge 2×11/2×8)',
                'baja al 40% y repite la técnica; el 50% llega cuando la recepción no se negocia.'
            ),
            'trainingWeeks' => self::phases('Zonas al 50% de Foundation', 'Bear crawl y farmers cargados', 'Media ATLAS al 50%: thruster, lunge y sit-up', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Dumbbell Thruster', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '2×11/2×8 kg (50% Foundation): G2OH unilateral alternando, recepción en hombro'],
                        ['name' => 'Dumbbell Lunge', 'minimum' => 20, 'target' => 30, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '2×11/2×8 kg (50%): rodilla de atrás al suelo, torso alto; dosis /lado computada una vez'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => self::baseTips(
                'Transición de cargas: thruster 2×15/2×10 y G2OH por brazo',
                'vuelve a dos zonas por sesión; la media ATLAS completa se gana con calma.'
            ),
            'trainingWeeks' => self::phases('Transición de cargas hacia Foundation', 'Pares de zonas con fatiga real', 'MEDIA ATLAS: 5 zonas al 50% sin parar el reloj', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Media ATLAS (rondas)', 'minimum' => 3, 'target' => 4, 'unit' => 'rondas', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Circuito de media ATLAS al 50% (10 thrusters + 10 burpees over bar + 10 lunges/lado + 8 G2OH/lado + 20 sit-ups): rondas completas en 12 minutos'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => self::baseTips(
                'Zonas Foundation por separado: thruster 43/29,5 y G2OH 22,5/15',
                'vuelve a series de 8 Foundation limpias; los 20 del benchmark se construyen con calma.'
            ),
            'trainingWeeks' => self::phases('Zonas Foundation por separado (fuerza pura)', 'Carries y cuerda Foundation', 'Zonas de carga y cuerda: farmers + shoulder carry + 100 singles', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Thruster', 'minimum' => 20, 'target' => 20, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Foundation 43/29,5 kg (95/65 lb): 20 reps acumuladas en series de 10; divide desde el principio'],
                        ['name' => 'Standing Dumbbell Press', 'minimum' => 20, 'target' => 20, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Foundation 2×17,5/2×11 kg (~35/25 lb, Standards Chart): strict press, sin piernas'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => self::baseTips(
                'Parejas de zonas Foundation con fatiga',
                'vuelve a las zonas por separado y afina el reparto; el cap de 30:00 se respeta dividiendo desde el principio.'
            ),
            'trainingWeeks' => self::phases('Parejas de zonas Foundation', 'EMOMs de densidad con pesos Foundation', 'Benchmark embebido: DEKA ATLAS Foundation (cap 30:00)', 'Media ATLAS Foundation por tiempo; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA ATLAS Foundation', 'minimum' => 1500, 'target' => 1200, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA ATLAS Foundation', 'form' => 'Benchmark DEKA ATLAS Foundation (10 zonas, división Foundation, cap 30:00). Mínimo 25:00 (regla F-Elizabeth, bajo el cap y por encima de beginner M 25:00); objetivo 20:00 (intermediate M). La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => self::baseTips(
                'Zonas al 75% hacia Peak (thruster 48/32, G2OH 25/18)',
                'vuelve al 65-70% y consolida; el Peak de la semana 7 pide técnica intacta.'
            ),
            'trainingWeeks' => self::phases('Zonas al 75% hacia Peak', 'Lunge y bear crawl cargados', 'Cuatro zonas al 75% juntas', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Thruster', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '48/32 kg (75% hacia Peak): recepción en línea; si la barra adelanta, baja la carga (§1.7)'],
                        ['name' => 'Farmers Walk', 'minimum' => 60, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '2×25/2×17,5 kg (75%): 60-100 m sin soltar, postura erguida'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => self::baseTips(
                'Zonas Peak por separado (thruster 52/34, carries pesados)',
                'vuelve a las zonas Peak por separado; la primera completa se gana con el reparto escrito.'
            ),
            'trainingWeeks' => self::phases('Zonas Peak por separado', 'Lunge, bear crawl y shoulder carry Peak', 'Benchmark embebido: DEKA ATLAS Peak (PRIMERA, mínimo generoso)', 'Media ATLAS Peak por tiempo; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA ATLAS Peak', 'minimum' => 1700, 'target' => 1400, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA ATLAS Peak', 'form' => 'Benchmark DEKA ATLAS Peak (10 zonas, división Peak, cap 30:00), primera toma de contacto. Mínimo generoso 28:20 (bajo el cap, por encima de beginner M 28:00); objetivo 23:00 (intermediate M). La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => self::baseTips(
                'Peak de cierre: poco volumen, mucha calidad',
                'vuelve a la Peak de la semana 7 con el reparto ajustado; el cierre exige el cap respetado.'
            ),
            'trainingWeeks' => self::phases('Peak de cierre: afilar', 'G2OH y shoulder carry frescos', 'Benchmark embebido: DEKA ATLAS Peak (TEST FINAL, mínimos exigentes)', 'Cierre del programa'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'DEKA ATLAS Peak', 'minimum' => 1500, 'target' => 1200, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DEKA ATLAS Peak', 'form' => 'Benchmark DEKA ATLAS Peak (10 zonas, división Peak, cap 30:00), test final. Mínimo exigente 25:00 (regla F-Elizabeth, bajo el cap y en torno a beginner-intermediate); objetivo 20:00 (intermediate M). La marca se registra en UserBenchmarkAttempt y alimenta el test; cierra el programa'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LOGROS (~6 por nivel, codes atlas-nX-)
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
            ...self::testAchievements('atlas-n1', [
                ['Veinte thrusters técnicos', 'Supera el mínimo de Thruster con barra vacía (15 reps) en el test de Nivel 1.', '🏋️', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Thruster', null, 50],
                ['Sit-up de zona', 'Supera el mínimo de Sit-up con disco ligero (20 reps) en el test de Nivel 1.', '🔥', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Sit-up', null, 50],
            ]),
            self::hito('atlas-n1-mapa-zonas', 'Mapa de las 10 zonas', 'Primer mini-ATLAS (5 zonas) completado en la sesión C.', '🗺️', Achievement::TIER_COMUN, 50),
            self::hito('atlas-n1-primer-burpee-bar', 'Primer burpee over bar', 'Primer burpee over bar válido: salto con los dos pies sobre la barra olímpica.', '📦', Achievement::TIER_COMUN, 50),
            self::hito('atlas-n1-bear-crawl', 'Primer bear crawl', 'Primer bear crawl de 40 m sin carga con la espalda plana.', '🐻', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            ...self::testAchievements('atlas-n2', [
                ['G2OH de 15', 'Supera el mínimo de Dumbbell Thruster al 50% (15 reps) en el test de Nivel 2.', '🏋️', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Dumbbell Thruster', null, 50],
                ['Lunge de 20', 'Supera el mínimo de Dumbbell Lunge al 50% (20 reps) en el test de Nivel 2.', '🦵', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Dumbbell Lunge', null, 50],
            ]),
            self::hito('atlas-n2-farmers-cargado', 'Primer farmers cargado', 'Primer Farmers Walk con mancuernas (50%) sin soltar.', '🧳', Achievement::TIER_COMUN, 50),
            self::hito('atlas-n2-bear-crawl-cargado', 'Bear crawl cargado', 'Primer bear crawl con mancuernas (50%) con la lumbar quieta.', '🐻', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            ...self::testAchievements('atlas-n3', [
                ['Media ATLAS en 3 rondas', 'Supera el mínimo de Media ATLAS (3 rondas) en el test de Nivel 3.', '🏁', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Media ATLAS (rondas)', null, 100],
                ['Media ATLAS bajo 12', 'Completa 4 rondas de la Media ATLAS (objetivo del test de Nivel 3).', '⚡', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_OBJ, 'Media ATLAS (rondas)', 4, 150],
            ]),
            self::hito('atlas-n3-cinco-zonas', 'Cinco zonas seguidas', 'Primer circuito de 5 zonas conectadas sin parar el reloj.', '🔁', Achievement::TIER_COMUN, 50),
            self::hito('atlas-n3-agarre', 'Agarre de media ATLAS', 'Farmers y G2OH de la media ATLAS sin soltar las mancuernas.', '✊', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            ...self::testAchievements('atlas-n4', [
                ['Thruster Foundation', 'Completa 20 reps de Thruster Foundation (43/29,5 kg) en el test de Nivel 4.', '🏋️', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'Thruster', null, 100],
                ['Press Foundation', 'Completa 20 reps de Standing Dumbbell Press Foundation en el test de Nivel 4.', '💪', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'Standing Dumbbell Press', null, 100],
            ]),
            self::hito('atlas-n4-carries-foundation', 'Carries Foundation', 'Farmers 100 m + shoulder carry 100 m Foundation sin soltar.', '🧳', Achievement::TIER_RARO, 100),
            self::hito('atlas-n4-cien-singles', 'Cien singles', 'Primer set de 100 single unders sin fallo.', '🪢', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            ...self::testAchievements('atlas-n5', [
                ['Primera ATLAS Foundation', 'Completa la DEKA ATLAS Foundation dentro del cap de 30:00 (mínimo 25:00) en el test de Nivel 5.', '🏆', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA ATLAS Foundation', null, 200],
                ['Foundation bajo 20', 'Completa la DEKA ATLAS Foundation en menos de 20:00 (objetivo del test de Nivel 5).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA ATLAS Foundation', 1200, 300],
            ]),
            self::hito('atlas-n5-cap-respetado', 'Cap respetado', 'Primera ATLAS Foundation terminada con el reloj bajo 30:00.', '⏱️', Achievement::TIER_RARO, 100, true),
            self::hito('atlas-n5-carry-sin-soltar', 'Carries sin soltar', 'Farmers y shoulder carry de la ATLAS Foundation sin soltar la carga.', '✊', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            ...self::testAchievements('atlas-n6', [
                ['Thruster de 48', 'Supera el mínimo de Thruster al 75% (48/32 kg, 15 reps) en el test de Nivel 6.', '🏋️', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'Thruster', null, 100],
                ['Farmers de 100 m', 'Supera el mínimo de Farmers Walk al 75% (60 m) en el test de Nivel 6.', '🧳', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Farmers Walk', null, 50],
            ]),
            self::hito('atlas-n6-transicion', 'Transición Peak', 'Primeras cuatro zonas al 75% completadas en una sola sesión.', '🔀', Achievement::TIER_RARO, 100),
            self::hito('atlas-n6-tecnica-intacta', 'Técnica intacta', 'Semana de transición completada sin romper la línea en ninguna recepción (§1.7).', '🎯', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            ...self::testAchievements('atlas-n7', [
                ['Primera ATLAS Peak', 'Completa la primera DEKA ATLAS Peak dentro del cap de 30:00 (mínimo generoso 28:20) en el test de Nivel 7.', '🏆', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA ATLAS Peak', null, 200],
                ['Peak bajo 23', 'Completa la DEKA ATLAS Peak en menos de 23:00 (objetivo del test de Nivel 7).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA ATLAS Peak', 1400, 300],
            ]),
            self::hito('atlas-n7-terminar-entero', 'Terminar entero', 'Primera ATLAS Peak completa: las 10 zonas con los pesos más pesados.', '🏛️', Achievement::TIER_RARO, 100, true),
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            ...self::testAchievements('atlas-n8', [
                ['ATLAS Peak completada', 'Completa la DEKA ATLAS Peak del test final dentro del cap de 30:00 (mínimo 25:00) en el Nivel 8.', '🏆', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_MIN, 'DEKA ATLAS Peak', null, 300],
                ['Peak bajo 20', 'Completa la DEKA ATLAS Peak en menos de 20:00 (objetivo del test de Nivel 8).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'DEKA ATLAS Peak', 1200, 300],
            ]),
            self::hito('atlas-n8-deka-atlas', 'DEKA ATLAS dominada', 'Cierre del programa DEKA ATLAS: las 10 zonas Peak bajo el cap de 30:00.', '🏛️', Achievement::TIER_LEGENDARIO, 500, true),
        ];
    }
}
