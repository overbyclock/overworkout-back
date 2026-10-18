<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Spartan OCR Prep v1 (8 niveles).
 *
 * Puertas SP1-SP3 (agarre de mono, cuerda, burpees de penalización).
 * Penalización oficial: obstáculo fallado = 30 burpees. Regla §1.7 adaptada:
 * técnica antes que fuerza bruta; entrena el estándar antes que la velocidad;
 * dolor matinal >2/10 en hombro/muñeca (cuerda/mono) = bajar volumen un ciclo.
 */
class SpartanOcrPrepContent
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

    /**
     * Puertas de movilidad SP1-SP3 del programa (criteria literal).
     * N1 no evalúa ninguna (se siembran en Bloque 0); N2 evalúa SP1; N3 SP2;
     * N4 SP3; re-tests en N6 (SP1/SP2) y N8 (SP3).
     */
    public static function getMobilityGates(): array
    {
        return [
            [
                'code' => 'SP1',
                'name' => 'Agarre de mono',
                'description' => 'Puerta de agarre colgado (monkey bars, obstáculos de travesía). Se evalúa en N2 (re-test N6); exigida a partir de N2. Siembra en N1.',
                'criteria' => 'Dead Hang 60 s + 5 travesías cortas de Monkey Bars asistidas con columpio controlado',
            ],
            [
                'code' => 'SP2',
                'name' => 'Cuerda',
                'description' => 'Puerta de cuerda (rope climb, obstáculo de Spartan Race). Se evalúa en N3 (re-test N6); exigida a partir de N3. Siembra en N1.',
                'criteria' => 'Rope Climb ×1 completo (o negativa controlada de 5 s si aún no llega, anotándolo)',
            ],
            [
                'code' => 'SP3',
                'name' => 'Burpees de penalización',
                'description' => 'Puerta de motor de penalización (obstáculo fallado = 30 burpees). Se evalúa en N4 (re-test N8); exigida a partir de N4. Siembra en N1.',
                'criteria' => 'Burpee ×30 sin romper series, en menos de 2:30, con pecho al suelo y salto con extensión',
            ],
        ];
    }

    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Trail + fuerza de obstáculos',
                'session_b' => 'Técnica de obstáculos',
                'session_c' => 'Calentamiento del simulacro',
                'session_d' => 'Z2 largo + movilidad',
                default => 'Trail y técnica',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Simulacro / Benchmark',
                'session_d' => 'Core suave',
                default => 'WOD',
            },
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab lumbar y core',
                'session_b' => 'Prehab de muñeca y hombro',
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
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, tobillo, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s. El agarre se despierta antes de cada sesión de obstáculos.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Trail Z2 por tiempo + cuerda y carries: el motor y las cargas del OCR.',
                'session_b' => 'Técnica de obstáculos (mono, lanza, gateo, muros) + grip: la técnica antes que la fuerza bruta.',
                'session_c' => 'Calentamiento específico del simulacro con dosis cortas.',
                default => 'Z2 largo + movilidad de tobillo/cadera/hombro y core: la base silenciosa del OCR.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Circuitos de obstáculos (F1/F2/F4) o el benchmark embebido (F3). En carrera, un fallo cuesta 30 burpees: entrena el estándar antes que la velocidad.',
                'session_d' => 'Core suave: el tronco quieto de los carries.',
                default => 'WOD de la sesión: técnica primero, siempre.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado). Calidad por encima de fatiga; descanso 45-60 s.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de tobillo y cadera. En Fase 4 se amplía con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (estrategia OCR y regla §1.7).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'El OCR se corre en Z2: si puedes conversar en el trail, vas al ritmo de un Beast. El agarre de hoy es el mono de dentro de dos niveles.',
                'session_b' => 'El gateo bajo se gana en la cadera: si tu espalda toca el alambre imaginario, baja más. Rítmico, no rápido.',
                'session_c' => 'El mini-circuito de hoy es el mapa: gateo, muros y carries. Aprende dónde te cuesta cada uno antes del Sprint.',
                'session_d' => 'Tobillo y cadera: el trail con barro castiga lo que no movilizas. No lo saltes.',
            ],
            2 => [
                'session_a' => 'El mono se gana en el columpio: un brazo suelta en el punto alto, el otro agarra. Si cruzas estático brazo a brazo, el agarre muere a la mitad.',
                'session_b' => 'La lanza es técnica, no fuerza: paso cruzado, cadera abierta, suelta alta. El que entrena el estándar no paga burpees.',
                'session_c' => 'SP1 en contexto: mono + lanza + muro con fatiga. Si la muñeca amanece >2/10 mañana, el volumen baja un ciclo (§1.7).',
                'session_d' => 'El agarre descansa hoy: muñeca y antebrazo al cien para la puerta de mañana.',
            ],
            3 => [
                'session_a' => 'La cuerda se sube con pies, no con brazos: pisada, rodilla, otra pisada. Si no llegas, la negativa de 5 s cuenta como siembra.',
                'session_b' => 'El bucket carry se camina con el cubo pegado al pecho y la espalda quieta: levantar con la lumbar redondeada es pagar la factura mañana.',
                'session_c' => 'SP2 entera en contexto: cuerda, carry y gateo. Anota si subiste la cuerda completa o en negativa.',
                'session_d' => 'Recupera el agarre: el nivel que viene tiene el primer simulador completo.',
            ],
            4 => [
                'session_a' => 'Semana del Sprint: el trail de hoy es movimiento, no construcción. Llega fresco al simulador.',
                'session_b' => 'Los 30 burpees de penalización se entrenan como se corren: series de 10, ritmo constante, sin parar entre series.',
                'session_c' => 'SPARTAN SPRINT SIM: 5 km trail + 8 obstáculos, cap 90:00. Zapatilla de trail, calcetines anti-rozadura, y un fallo = 30 burpees anotados. Divide los burpees 10+10+10.',
                'session_d' => 'Después del Sprint: revisa los pies, hidrata y anota dónde fallaron los obstáculos. El Super está a dos niveles.',
            ],
            5 => [
                'session_a' => 'El volumen del Super sube en el trail: 50-60 minutos Z2. El ego se queda en casa; el ritmo conversacional también.',
                'session_b' => 'El tire flip se levanta con la bisagra, no con la espalda: rodillas fuera, pecho alto, empuja el mundo.',
                'session_c' => 'Circuito del Super con burpees de penalización: practica la gestión del fallo antes de que el fallo te pille.',
                'session_d' => 'Después del volumen: tobillo, cadera y mucha agua. El Super de la semana que viene pide todo.',
            ],
            6 => [
                'session_a' => 'Re-tests del nivel: cuerda y mono frescos. Un intento limpio vale por cinco cansados.',
                'session_b' => 'El volumen del Super se mantiene con los carries pesados: la espalda neutra es innegociable.',
                'session_c' => 'SPARTAN SUPER SIM: 10 km + 10 obstáculos, cap 3:00. Hidratación desde el km 5; un fallo = 30 burpees en series de 10. La marca de hoy es la base del Beast.',
                'session_d' => 'Después del Super: pies, hidratación y la lista de fallos. El Beast se construye con esa lista.',
            ],
            7 => [
                'session_a' => 'El Beast empieza en el trail largo: 90-120 minutos Z2. La cabeza del km 15 se entrena en el minuto 60 de hoy.',
                'session_b' => 'Los obstáculos pesados en fatiga: técnica primero siempre. Un burpee de penalización bien hecho cuesta menos que una lesión.',
                'session_c' => 'Circuito del Beast con nutrición practicada entre rondas: 60-90 g de carbohidratos por hora desde la primera. El estómago se entrena como las piernas.',
                'session_d' => 'Después del volumen del Beast: movilidad completa y sueño. La semana que viene se cierra el programa.',
            ],
            8 => [
                'session_a' => 'Semana del cierre: trail suave y obstáculos técnicos. El trabajo ya está hecho; hoy se afila.',
                'session_b' => 'Los 30 burpees del re-test SP3: series de 10, ritmo de metrónomo. Y la lanza: paso cruzado y suelta alta.',
                'session_c' => 'SPARTAN BEAST SIM: 21 km + 12 obstáculos, cap 6:00. Nutrición por hora, electrolitos, burpees de penalización en series de 10. Cierra el programa. ¡AROO!',
                'session_d' => 'Cierre: movilidad, respiración y las tres marcas (Sprint/Super/Beast) en la cabeza.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y nivel.
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Sprint' => 'Trail Z2 por tiempo (cinta con desnivel simulado si no hay trail): 30-40 min, ritmo conversacional.',
                'Bear Crawl' => 'Barbed Wire Crawl: cadera al mínimo, espalda plana, avance rítmico.',
            ],
            2 => [
                'Monkey Bars' => 'Travesías asistidas con columpio: un brazo suelta en el punto alto. Criterio SP1.',
                'Spear Throw' => 'Paso cruzado y suelta alta; sin lanza, balón medicinal a diana.',
            ],
            3 => [
                'Rope Climb' => 'Pisada + rodilla + pisada; si no llegas, negativa controlada de 5 s (siembra SP2).',
                'Bucket Carry' => 'Cubo 20-30 kg (50% en aprendizaje): a pecho, espalda neutra.',
            ],
            4 => [
                'Burpee' => 'Penalización oficial: ×30 en series de 10, bajo 2:30 (criterio SP3). Pecho al suelo y salto con extensión.',
                'Spear Throw' => 'Diana a 7,6 m; un solo intento en carrera: entrena el estándar.',
            ],
            5 => [
                'Tire Flip' => 'Bisagra + empuje: rodillas fuera, pecho alto. Neumático pesado del gimnasio.',
            ],
            6 => [
                'Sprint' => 'Re-tests SP1/SP2 en la sesión A; simulador Super en la C.',
            ],
            7 => [
                'Bucket Carry' => 'Volumen del Beast: 50 m cargados con postura erguida y pasos cortos.',
            ],
            8 => [
                'Burpee' => 'Re-test SP3: ×30 bajo 2:30, series de 10.',
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
            'Fase 2 (Progresión): sube el volumen; los obstáculos se densifican.',
            'Fase 3 (Intensificación): la sesión C con el mayor estímulo (benchmark embebido en N4/N6/N8).',
            'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
            'Regla §1.7 (OCR): obstáculo con técnica antes que con fuerza bruta; en carrera un fallo cuesta 30 burpees — entrena el estándar antes que la velocidad; dolor matinal >2/10 en hombro/muñeca (cuerda/mono) = bajar volumen un ciclo. Zapatilla de trail, calcetines anti-rozaduras e hidratación en Super/Beast.',
            'Si fallas el test: '.$fallo,
        ];
    }

    private static function phases(string $f1, string $f2, string $f3, string $f4): array
    {
        return [
            ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => $f1, 'note' => 'Volumen Z2', 'intensity' => '55%'],
            ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => $f2, 'note' => 'Obstáculos densificados', 'intensity' => '80%'],
            ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => $f3, 'note' => 'El mayor estímulo del nivel', 'intensity' => '95%'],
            ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => $f4, 'note' => 'Test de nivel al final', 'intensity' => '50%'],
        ];
    }

    private static function level1(): array
    {
        return [
            'tips' => self::baseTips(
                'Base trail: runs Z2 de 30-40 min y fuerza de agarre',
                'baja el trail a 20 min Z2 y repite; la base se construye sin prisa.'
            ),
            'trainingWeeks' => self::phases('Base trail Z2 (30-40 min) y agarre', 'Gateo y muros técnicos', 'Mini-circuito de obstáculos', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Sprint', 'minimum' => 30, 'target' => 40, 'unit' => 'min', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Trail run Z2 continuo en minutos (cinta con desnivel simulado si no hay trail): ritmo conversacional, sin caminar'],
                        ['name' => 'Farmers Walk', 'minimum' => 40, 'target' => 60, 'unit' => 'meters', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Sandbag carry ligero: 40-60 m sin soltar, postura erguida'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => self::baseTips(
                'Agarre y mono: dead hang largo y monkey bars asistidas',
                'vuelve al dead hang de 40 s y al columpio de un brazo; SP1 llega cuando el agarre no se negocia.'
            ),
            'trainingWeeks' => self::phases('Agarre y mono (dead hang, MB asistidas)', 'Spear throw con paso cruzado', 'Circuito técnico con SP1 en contexto', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'SP1 — Agarre de mono (Dead Hang)', 'minimum' => 60, 'target' => 60, 'unit' => 'seconds', 'direction' => 'gte', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'SP1', 'form' => 'Dead Hang 60 s + 5 travesías cortas de Monkey Bars asistidas con columpio controlado (criterio entero de la puerta SP1)'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => self::baseTips(
                'Cuerda y carries: rope climb y bucket/sandbag carry',
                'vuelve a la negativa controlada de 5 s y al carry ligero; SP2 llega con la pisada correcta.'
            ),
            'trainingWeeks' => self::phases('Cuerda y carries (rope climb, bucket carry)', 'Barbed wire crawl rítmico', 'SP2 entera en contexto (cuerda + carry + gateo)', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'SP2 — Cuerda (Rope Climb)', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'SP2', 'form' => 'Rope Climb completo ×1 (criterio entero de la puerta SP2); el objetivo añade una segunda subida; si aún no llega, negativa controlada de 5 s anotada'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => self::baseTips(
                'Sprint: el primer simulador completo y la penalización',
                'vuelve a los circuitos de obstáculos y a los 30 burpees en series de 10; el Sprint llega con el fallo ya entrenado.'
            ),
            'trainingWeeks' => self::phases('Afinar obstáculos del Sprint', 'Penalización practicada (lanza + burpees)', 'Benchmark embebido: Spartan Sprint Sim (cap 1:30)', 'Los 30 burpees por tiempo; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Spartan Sprint Sim', 'minimum' => 4500, 'target' => 3600, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Spartan Sprint Sim', 'form' => 'Benchmark Spartan Sprint Sim (5 km trail + 8 obstáculos, cap 1:30). Mínimo 75:00 (regla F-Elizabeth, bajo el cap y en torno a beginner M 1:25); objetivo 60:00 (advanced-intermediate). Obstáculo fallado = 30 burpees anotados. La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                        ['name' => 'SP3 — Burpees de penalización (×30 <2:30)', 'minimum' => 30, 'target' => 30, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'SP3', 'form' => '30 burpees completos (pecho al suelo, salto con extensión) en menos de 2:30, sin romper series (criterio entero de la puerta SP3)'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => self::baseTips(
                'Super volumen: runs 50-60 min y obstáculos pesados',
                'vuelve a 40 min de trail y a los carries ligeros; el Super llega con el volumen hecho.'
            ),
            'trainingWeeks' => self::phases('Volumen del Super (runs 50-60 min)', 'Tire flip y carries pesados', 'Circuito del Super con burpees de penalización', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Farmers Walk', 'minimum' => 100, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Sandbag carry 2×22,5/2×15 kg: 100 m sin soltar'],
                        ['name' => 'Rope Climb', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => '3-5 subidas completas con descanso completo entre ellas'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => self::baseTips(
                'Super: el segundo simulador con re-tests de puertas',
                'vuelve al Sprint Sim; el Super llega cuando los re-tests salen frescos.'
            ),
            'trainingWeeks' => self::phases('Re-tests SP1/SP2 y volumen', 'Obstáculos pesados del Super', 'Benchmark embebido: Spartan Super Sim (cap 3:00)', 'Recuperación; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Spartan Super Sim', 'minimum' => 9000, 'target' => 7200, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Spartan Super Sim', 'form' => 'Benchmark Spartan Super Sim (10 km trail + 10 obstáculos, cap 3:00). Mínimo 2:30:00 (regla F-Elizabeth, bajo el cap); objetivo 2:00:00. Hidratación desde el km 5; fallo = 30 burpees. La marca se registra en UserBenchmarkAttempt y alimenta el test'],
                        ['name' => 'SP1 (re-test) — Agarre de mono (Dead Hang)', 'minimum' => 60, 'target' => 60, 'unit' => 'seconds', 'direction' => 'gte', 'type' => TestItemType::ReTest->value, 'gateCode' => 'SP1', 'form' => 'Re-test con el criterio histórico de la puerta (ruling "carga histórica"): Dead Hang 60 s + travesías asistidas'],
                        ['name' => 'SP2 (re-test) — Cuerda (Rope Climb)', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::ReTest->value, 'gateCode' => 'SP2', 'form' => 'Re-test con el criterio histórico de la puerta: Rope Climb ×1 completo'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => self::baseTips(
                'Beast volumen: la resistencia del 21 km',
                'vuelve al volumen del Super con nutrición; el Beast llega cuando la sesión larga sale sola.'
            ),
            'trainingWeeks' => self::phases('Volumen del Beast (runs 90-120 min)', 'Obstáculos pesados en fatiga', 'Circuito del Beast con nutrición practicada', 'Mitad de volumen; test de nivel'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Bucket Carry', 'minimum' => 50, 'target' => 100, 'unit' => 'meters', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Cubo 20-30 kg: 50-100 m sin soltar, espalda neutra'],
                        ['name' => 'Sprint', 'minimum' => 60, 'target' => 90, 'unit' => 'min', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Trail Z2 continuo en minutos: 60-90 min (el mayor del programa)'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => self::baseTips(
                'Beast/Trifecta: el cierre del programa',
                'las tres marcas (Sprint/Super/Beast) se comparan: la mejor es la tuya. ¡AROO!'
            ),
            'trainingWeeks' => self::phases('Afilar el cierre', 'Re-test SP3 y lanza', 'Benchmark embebido: Spartan Beast Sim (cap 6:00)', 'Cierre del programa'),
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Spartan Beast Sim', 'minimum' => 18000, 'target' => 14400, 'unit' => 'seconds', 'direction' => 'lte', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Spartan Beast Sim', 'form' => 'Benchmark Spartan Beast Sim (21 km trail + 12 obstáculos, cap 6:00). Mínimo 5:00:00 (regla F-Elizabeth, bajo el cap, en torno a intermediate M); objetivo 4:00:00. Nutrición 60-90 g carb/h desde la 1ª hora; fallo = 30 burpees. La marca se registra en UserBenchmarkAttempt y alimenta el test; cierra el programa'],
                        ['name' => 'SP3 (re-test) — Burpees de penalización (×30 <2:30)', 'minimum' => 30, 'target' => 30, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::ReTest->value, 'gateCode' => 'SP3', 'form' => 'Re-test con el criterio histórico de la puerta: 30 burpees completos en menos de 2:30, sin romper series'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LOGROS (~6 por nivel, codes spartan-nX-)
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
            ...self::testAchievements('spartan-n1', [
                ['Trail de 30', 'Supera el mínimo de trail run Z2 (30 min) en el test de Nivel 1.', '🏃', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Sprint', null, 50],
                ['Sandbag de 40 m', 'Supera el mínimo de Farmers Walk (40 m) en el test de Nivel 1.', '🧳', Achievement::TIER_COMUN, Achievement::REQ_TEST_ITEM_MIN, 'Farmers Walk', null, 50],
            ]),
            self::hito('spartan-n1-mapa-ocr', 'Mapa del OCR', 'Primer mini-circuito de obstáculos (gateo + muros) completado.', '🗺️', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n1-primer-crawl', 'Primer barbed wire', 'Primer Barbed Wire Crawl de 20 m con la cadera baja y ritmo rítmico.', '🪖', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n1-zapatilla', 'Zapatilla de trail', 'Primer trail de 30+ minutos con zapatilla y calcetines de carrera.', '👟', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            ...self::testAchievements('spartan-n2', [
                ['Puerta SP1 superada', 'Supera la puerta SP1 (Agarre de mono: Dead Hang 60 s + travesías asistidas) en el test de Nivel 2.', '🐒', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'SP1 — Agarre de mono (Dead Hang)', null, 100],
            ]),
            self::hito('spartan-n2-columpio', 'Columpio de un brazo', 'Primer columpio de un brazo en el monkey bars con control.', '🌀', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n2-primera-lanza', 'Primera lanza a diana', 'Primer Spear Throw válido a diana con paso cruzado.', '🎯', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n2-hang-60', 'Colgador de 60', 'Primer Dead Hang de 60 segundos.', '✊', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            ...self::testAchievements('spartan-n3', [
                ['Puerta SP2 superada', 'Supera la puerta SP2 (Cuerda: Rope Climb ×1) en el test de Nivel 3.', '🪢', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'SP2 — Cuerda (Rope Climb)', null, 100],
            ]),
            self::hito('spartan-n3-primera-cuerda', 'Primera cuerda', 'Primer Rope Climb completo (o negativa controlada de 5 s).', '🧗', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n3-bucket', 'Primer bucket carry', 'Primer Bucket Carry de 50 m con el cubo pegado al pecho.', '🪣', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n3-gateo-ritmo', 'Gateo rítmico', 'Primer Barbed Wire Crawl de 30 m sin parar el ritmo.', '🪖', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            ...self::testAchievements('spartan-n4', [
                ['Sprint Sim completado', 'Completa el Spartan Sprint Sim dentro del cap de 1:30 (mínimo 75:00) en el test de Nivel 4.', '🏆', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'Spartan Sprint Sim', null, 200],
                ['Sprint bajo 60', 'Completa el Spartan Sprint Sim en menos de 60:00 (objetivo del test de Nivel 4).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'Spartan Sprint Sim', 3600, 300],
                ['Puerta SP3 superada', 'Supera la puerta SP3 (Burpees de penalización ×30 <2:30) en el test de Nivel 4.', '🔥', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'SP3 — Burpees de penalización (×30 <2:30)', null, 100],
            ]),
            self::hito('spartan-n4-cap-sprint', 'Cap del Sprint respetado', 'Primer Spartan Sprint Sim terminado bajo el cap de 90:00.', '⏱️', Achievement::TIER_RARO, 100, true),
            self::hito('spartan-n4-treinta-burpees', 'Treinta burpees', 'Primer set de 30 burpees de penalización en series de 10.', '💀', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            ...self::testAchievements('spartan-n5', [
                ['Sandbag de 100 m', 'Completa 100 m de Farmers Walk (2×22,5/2×15 kg) en el test de Nivel 5.', '🧳', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'Farmers Walk', null, 100],
                ['Cuerda de 5', 'Supera el objetivo de Rope Climb (5 subidas) en el test de Nivel 5.', '⚡', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_OBJ, 'Rope Climb', 5, 150],
            ]),
            self::hito('spartan-n5-tire-flip', 'Primer tire flip', 'Primer Tire Flip pesado con la bisagra correcta.', '🛞', Achievement::TIER_COMUN, 50),
            self::hito('spartan-n5-volumen-super', 'Volumen del Super', 'Primer trail de 60 minutos Z2 del programa.', '🏃', Achievement::TIER_RARO, 100),
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            ...self::testAchievements('spartan-n6', [
                ['Super Sim completado', 'Completa el Spartan Super Sim dentro del cap de 3:00 (mínimo 2:30) en el test de Nivel 6.', '🏆', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'Spartan Super Sim', null, 200],
                ['Super bajo 2:00', 'Completa el Spartan Super Sim en menos de 2:00 (objetivo del test de Nivel 6).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'Spartan Super Sim', 7200, 300],
                ['SP1 re-validada', 'Supera el re-test de la puerta SP1 en el test de Nivel 6.', '🚪', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'SP1 (re-test) — Agarre de mono (Dead Hang)', null, 100],
                ['SP2 re-validada', 'Supera el re-test de la puerta SP2 en el test de Nivel 6.', '🚪', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'SP2 (re-test) — Cuerda (Rope Climb)', null, 100],
            ]),
            self::hito('spartan-n6-hidratacion', 'Hidratación en carrera', 'Primera carrera simulada de 10 km con hidratación desde el km 5.', '💧', Achievement::TIER_COMUN, 50),
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            ...self::testAchievements('spartan-n7', [
                ['Bucket de 100 m', 'Supera el objetivo de Bucket Carry (100 m) en el test de Nivel 7.', '🪣', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_OBJ, 'Bucket Carry', 100, 200],
                ['Trail de 90', 'Supera el mínimo de trail Z2 (90 min) en el test de Nivel 7.', '🏃', Achievement::TIER_EPICO, Achievement::REQ_TEST_ITEM_MIN, 'Sprint', null, 200],
            ]),
            self::hito('spartan-n7-nutricion', 'Nutrición por hora', 'Primera sesión larga con 60-90 g de carbohidratos/hora practicada.', '🍌', Achievement::TIER_RARO, 100),
            self::hito('spartan-n7-km15', 'La cabeza del km 15', 'Primer trail de 90+ minutos completado con ritmo constante.', '🧠', Achievement::TIER_RARO, 100),
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            ...self::testAchievements('spartan-n8', [
                ['Beast Sim completado', 'Completa el Spartan Beast Sim dentro del cap de 6:00 (mínimo 5:00) en el test de Nivel 8.', '🏆', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_MIN, 'Spartan Beast Sim', null, 300],
                ['Beast bajo 4:00', 'Completa el Spartan Beast Sim en menos de 4:00 (objetivo del test de Nivel 8).', '⚡', Achievement::TIER_LEGENDARIO, Achievement::REQ_TEST_ITEM_OBJ, 'Spartan Beast Sim', 14400, 300],
                ['SP3 re-validada', 'Supera el re-test de la puerta SP3 en el test de Nivel 8.', '🚪', Achievement::TIER_RARO, Achievement::REQ_TEST_ITEM_MIN, 'SP3 (re-test) — Burpees de penalización (×30 <2:30)', null, 100],
            ]),
            self::hito('spartan-n8-trifecta', 'Trifecta del programa', 'Las tres marcas del programa (Sprint/Super/Beast) registradas.', '🏅', Achievement::TIER_EPICO, 200, true),
            self::hito('spartan-n8-aroo', '¡AROO!', 'Cierre del programa Spartan OCR Prep: el Beast bajo el cap. ¡AROO!', '🏔️', Achievement::TIER_LEGENDARIO, 500, true),
        ];
    }
}
