<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Calistenia Master.
 * Tips, notas por semana, progresión y tests para cada nivel.
 * Separado del blueprint de ejercicios para facilitar edición y revisión.
 */
class CalisteniaMasterContent
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
            9 => self::level9(),
            10 => self::level10(),
            11 => self::level11(),
            12 => self::level12(),
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    /**
     * Devuelve la nota técnica para un ejercicio específico en un nivel.
     * Niveles 1-3 tienen notas detalladas; niveles 4-12 usan notas genéricas por ejercicio.
     */
    public static function getExerciseNote(int $levelNumber, string $exerciseName): ?string
    {
        $specific = match ($levelNumber) {
            1 => self::EXERCISE_NOTES_L1,
            2 => self::EXERCISE_NOTES_L2,
            3 => self::EXERCISE_NOTES_L3,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Semana 0: Aprende la técnica sin prisa, haz solo la mitad de repeticiones',
                'Semana 1: Enfoque en técnica perfecta, rango bajo-moderado de repeticiones',
                'Semana 2: Aumenta 1-2 repeticiones por ejercicio si la técnica es buena',
                'Semana 3: Busca el rango alto de repeticiones, mantén buena forma',
                'Tests: Descansa 48h antes, hidratación y mentalidad positiva',
                'Si fallas un test, no pasa nada, repite el nivel con más ganas',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Aprender movimientos básicos', 'note' => 'Mitad de repeticiones, enfoque en técnica perfecta', 'intensity' => '50%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Mismos ejercicios, aumentar volumen', 'note' => 'Rango completo de repeticiones', 'intensity' => '75%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevos ejercicios, más específicos', 'note' => 'Variantes más cercanas a los tests', 'intensity' => '85%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, preparación tests', 'note' => 'Ejercicios avanzados de nivel 1', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende la técnica, haz solo la mitad de repeticiones, sin presión',
                'week1' => 'Enfoque en técnica perfecta, rango bajo-moderado de repeticiones',
                'week2' => 'Aumenta 1-2 repeticiones por ejercicio si la técnica es buena',
                'week3' => 'Busca el rango alto de repeticiones, mantén buena forma',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Visualización positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar al menos 4 de 5 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Push-ups', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, core apretado, extensión completa'],
                        ['name' => 'Australian Pull-ups', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Pecho a la barra, cuerpo recto, control total'],
                        ['name' => 'Air Squats', 'minimum' => 20, 'target' => 25, 'unit' => 'repeticiones', 'form' => 'Caderas abajo de rodillas, sin perder lumbar, talones suelo'],
                        ['name' => 'Plank', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Cuerpo recto, codos debajo hombros, sin hundir caderas'],
                        ['name' => 'Hollow Body Hold', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Lumbar pegado al suelo, piernas y hombros elevados'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Semana 0: Los ejercicios sin ayuda son más difíciles, prioriza la técnica',
                'Semana 1: Aumenta volumen gradualmente, no sacrifiques forma por reps',
                'Semana 2: Introduce pike push-up y negativa de dominada, sé paciente',
                'Semana 3: Simula los tests, máxima intensidad, descansa bien entre sesiones',
                'Si no pasas algún test, no pasa nada, practica esa habilidad más',
                'El hollow body hold es la base de todos los levers, domínalo bien',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a peso corporal puro', 'note' => 'Mitad de reps, enfocado en técnica perfecta', 'intensity' => '50%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar ejercicios nuevos', 'note' => 'Aumenta volumen gradualmente', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes y más reps', 'note' => 'Introduce pike push-up y negativa de dominada', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, evaluación', 'note' => 'Máxima intensidad, simulacro de tests. Descansa bien entre sesiones.', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende los ejercicios sin ayudas, mitad de reps, técnica perfecta',
                'week1' => 'Completa el rango de repeticiones, control total',
                'week2' => 'Introduce variantes más difíciles, aumenta estabilidad',
                'week3' => 'Máxima intensidad, simula los tests, descansa bien',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 3: Principiante II',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 3 (Principiante II)',
                    'requirements' => [
                        ['name' => 'Flexiones perfectas', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Completos, pecho al suelo, extensión total, core apretado'],
                        ['name' => 'Plank', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Cuerpo recto, codos bajo hombros, caderas alineadas'],
                        ['name' => 'Pike Push-ups', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Cadera alta, hombros sobre manos, cabeza toca suelo'],
                        ['name' => 'Sentadillas profundas', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'form' => 'Talones en suelo, glúteos abajo de rodillas, torso erguido'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Los wall walks preparan tu cuerpo para el handstand. No te apoyes contra la pared, solo úsala como guía.',
                'El L-sit tuck es la base del L-sit completo. Mantén la espalda recta y los hombros bajados.',
                'Las diamond push-ups desarrollan tríceps fuertes, necesarios para el handstand push-up futuro.',
                'El planche lean no es un ejercicio de reps, es de posición. Inclínate todo lo que puedas sin doblar codos.',
                'Las band assisted pull-ups son la mejor forma de aprender la dominada. Usa una banda que te permita 5-8 reps.',
                'Si no pasas algún test, practica esa habilidad específica durante la semana de transición.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a skills técnicos', 'note' => 'Mitad de reps, enfocado en técnica perfecta de wall walks y L-sit', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar nuevos ejercicios', 'note' => 'Aumenta volumen gradualmente, controla la bajada en dominadas', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes y más reps', 'note' => 'Introduce diamond push-up y band assisted pull-up', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, evaluación', 'note' => 'Máxima intensidad, simulacro de tests. Descansa bien entre sesiones.', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende wall walks y L-sit tuck, mitad de reps, técnica perfecta',
                'week1' => 'Completa el rango de repeticiones, control total en negativas',
                'week2' => 'Introduce diamond push-up y band assisted pull-up, aumenta estabilidad',
                'week3' => 'Máxima intensidad, simula los tests, descansa bien',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 3',
                'description' => 'Evalúa si estás listo para el Nivel 4: Intermedio I',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 4 (Intermedio I)',
                    'requirements' => [
                        ['name' => 'Dominadas negativas', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Sube de cualquier forma, bajada controlada de 5 segundos mínimo'],
                        ['name' => 'Flexiones diamante', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Manos en diamante, codos pegados al cuerpo, pecho toca manos'],
                        ['name' => 'L-sit tuck', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Manos en suelo o soportes, rodillas al pecho, sin tocar el suelo'],
                        ['name' => 'Wall walks', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde plancha, camina manos hacia la pared hasta posición casi vertical'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'El tuck planche requiere proyección de hombros máxima. Empuja el suelo con fuerza.',
                'El tuck front lever es pura retracción escapular. Junta omóplatos antes de levantar cadera.',
                'Las pistol assisted son la clave para dominadas de pierna. No saltes, baja controlado.',
                'Ahora entrenas en 2 bloques: fuerza primero, skills después. Descansa bien entre bloques.',
                'El pseudo planche push-up prepara tus muñecas para cargas horizontales futuras.',
                'Si los skills te frustran, practica holds de 5-10s con descansos largos.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a skills básicos', 'note' => 'Mitad de reps en skills, técnica sobre tiempo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar tuck planche y tuck FL', 'note' => 'Aumenta tiempo en holds gradualmente', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes de skills', 'note' => 'Introduce pistol assisted y planche lean', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills', 'note' => 'Simulacro de tests, máxima intensidad en ambos bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende tuck planche y tuck FL, holds cortos, proyección máxima',
                'week1' => 'Consolida skills básicos, aumenta tiempo en holds 2-3 segundos',
                'week2' => 'Introduce pistol assisted y planche lean, control total',
                'week3' => 'Máxima intensidad en skills, simula tests, descansa entre bloques',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 4',
                'description' => 'Evalúa si estás listo para el Nivel 5: Intermedio II',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y muñecas', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 5 (Intermedio II)',
                    'requirements' => [
                        ['name' => 'Tuck Planche', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, hombros proyectados, brazos rectos'],
                        ['name' => 'Tuck Front Lever', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, espalda horizontal, omóplatos juntos'],
                        ['name' => 'Pistol Assisted', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Baja controlado, pie trasero en bajo apoyo, rodilla estable'],
                        ['name' => 'Pseudo Planche Push-up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Manos en cintura, cuerpo inclinado adelante, codos pegados'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'El advanced tuck planche es más difícil que parece. Mantén la espalda plana, no arquees.',
                'Practica el back lever con cuidado. Si sientes dolor en hombros, detente y moviliza.',
                'Las archer push-ups desarrollan estabilidad unilateral. Un brazo trabaja más que el otro.',
                'El wall handstand hold de 30s+ es tu base para el HSPU. Respira tranquilo, no contengas.',
                'En el segundo bloque, prioriza calidad sobre cantidad. Mejor 2 rondas perfectas que 3 malas.',
                'La progresión de skills requiere paciencia. No compares tu día 1 con el día 100 de otro.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a skills intermedios', 'note' => 'Mitad de tiempo en holds avanzados, técnica perfecta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar advanced tuck y back lever', 'note' => 'Aumenta tiempo en holds 2-3 segundos por sesión', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes y más volumen', 'note' => 'Introduce archer push-ups y handstand más vertical', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en ambos bloques', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende advanced tuck y back lever, holds cortos, sin arquear espalda',
                'week1' => 'Consolida skills intermedios, aumenta tiempo progresivamente',
                'week2' => 'Introduce archer push-ups, busca handstand más vertical',
                'week3' => 'Máxima intensidad, simula tests, prioriza calidad en skills',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 5',
                'description' => 'Evalúa si estás listo para el Nivel 6: Intermedio III',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y caderas', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 6 (Intermedio III)',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Planche', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Rodillas separadas 90°, espalda plana, brazos rectos'],
                        ['name' => 'Back Lever', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal boca abajo, brazos rectos, hombros abiertos'],
                        ['name' => 'Archer Push-up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Un brazo estirado lateral, el otro flexiona, alterna'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Cuerpo recto, hombros abiertos, mira entre manos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'La muscle-up es técnica, no solo fuerza. Practica el movimiento con banda antes de intentar libre.',
                'El planche lean de 25s+ significa que tus muñecas están listas para cargas avanzadas.',
                'El deficit handstand push-up desarrolla rango de movimiento extra. Baja más allá de la horizontal.',
                'Ahora separas fuerza y skills en 2 bloques claros. No mezcles intensidades.',
                'El active hang con 40s+ demuestra que tu agarre y escápulas están listos para front lever.',
                'Si la muscle-up te cuesta, enfócate en la transición: el momento más difícil es pasar de pull a dip.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a muscle-up y deficit HSPU', 'note' => 'Practica muscle-up con banda, deficit HSPU con rango corto', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar muscle-up y deficit', 'note' => 'Aumenta reps en muscle-up, profundiza en deficit HSPU', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen y nuevas variantes', 'note' => 'Introduce muscle-up negativa y planche lean máximo', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills avanzados', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende muscle-up con banda y deficit HSPU, rango corto, técnica perfecta',
                'week1' => 'Consolida muscle-up y deficit, aumenta reps progresivamente',
                'week2' => 'Introduce muscle-up negativa y planche lean máximo',
                'week3' => 'Máxima intensidad, simula tests, separa fuerza y skills claramente',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 6',
                'description' => 'Evalúa si estás listo para el Nivel 7: Avanzado I',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y muñecas', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 7 (Avanzado I)',
                    'requirements' => [
                        ['name' => 'Muscle-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Dominada a fondo + fondo completo, sin impulso excesivo'],
                        ['name' => 'Deficit Handstand Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Manos en plataformas, cabeza baja de nivel de manos, empuje completo'],
                        ['name' => 'Planche Lean', 'minimum' => 25, 'target' => 30, 'unit' => 'segundos', 'form' => 'Inclinación máxima, hombros sobre manos, codos rectos'],
                        ['name' => 'Active Hang', 'minimum' => 40, 'target' => 50, 'unit' => 'segundos', 'form' => 'Omóplatos activados, agarre fuerte, cuerpo quieto'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'El handstand push-up libre es un hito. Practica primero contra pared para confianza.',
                'La pistol squat completa requiere movilidad de tobillo. Estira antes de entrenar piernas.',
                'Ahora tienes 3 bloques: fuerza, skills y accesorios. No descuides el bloque 3, previene lesiones.',
                'El advanced tuck front lever row desarrolla fuerza de tracción horizontal. Tira con omóplatos.',
                'Las assisted dips preparan tus tríceps para dips libres. Baja hasta 90° de codo.',
                'El bloque de accesorios es clave para equilibrar musculatura y evitar descompensaciones.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a HSPU y pistol libre', 'note' => 'HSPU contra pared, pistol con apoyo mínimo, técnica sobre reps', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar HSPU y pistol squat', 'note' => 'Aumenta reps en HSPU, pistol sin apoyo', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en 3 bloques', 'note' => 'Introduce dips asistidos y front lever rows', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en todos los bloques', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende HSPU contra pared y pistol con apoyo mínimo, técnica perfecta',
                'week1' => 'Consolida HSPU y pistol libre, aumenta reps progresivamente',
                'week2' => 'Introduce dips asistidos y front lever rows, volumen en 3 bloques',
                'week3' => 'Máxima intensidad, simula tests, no descuides accesorios',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 7',
                'description' => 'Evalúa si estás listo para el Nivel 8: Avanzado II',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de tobillos y hombros', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 8 (Avanzado II)',
                    'requirements' => [
                        ['name' => 'Handstand Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Cuerpo recto, cabeza toca suelo, extensión completa'],
                        ['name' => 'Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Una pierna, cadera abajo de rodilla, pie completo en suelo'],
                        ['name' => 'Muscle-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Dominada a fondo + fondo completo, control total'],
                        ['name' => 'Advanced Tuck Front Lever', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Rodillas separadas 90°, espalda horizontal, brazos rectos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'El straddle planche requiere flexibilidad de cadera. Estira splits antes de practicarlo.',
                'El full front lever es pura fuerza de tracción. Si no llegas, vuelve a advanced tuck.',
                'El human flag necesita empuje y tracción simultáneos. Empuja con el brazo de abajo, tira con el de arriba.',
                'Ahora el bloque 2 incluye skills muy avanzados. Descansa 3-4 min entre intentos.',
                'El freestanding handstand es mental tanto como físico. Practica caídas controladas.',
                'No ignores el bloque de accesorios. Los archer chin-ups equilibran la tracción unilateral.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a straddle y full front lever', 'note' => 'Straddle con piernas abiertas moderadas, full FL con banda si es necesario', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar straddle y full FL', 'note' => 'Aumenta tiempo en holds, human flag con piernas tuck', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen y nuevas variantes', 'note' => 'Introduce freestanding handstand y archer chin-ups', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills élite', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende straddle planche y full FL, flexibilidad de cadera, banda si es necesario',
                'week1' => 'Consolida straddle y full FL, human flag con piernas tuck',
                'week2' => 'Introduce freestanding handstand y archer chin-ups',
                'week3' => 'Máxima intensidad, simula tests, descansa 3-4 min entre skills',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 8',
                'description' => 'Evalúa si estás listo para el Nivel 9: Avanzado III',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de caderas y hombros', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 9 (Avanzado III)',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Piernas abiertas 180°, espalda plana, brazos rectos'],
                        ['name' => 'Full Front Lever', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, omóplatos juntos, brazos rectos'],
                        ['name' => 'Human Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal lateral, empuja abajo, tira arriba'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 10, 'target' => 20, 'unit' => 'segundos', 'form' => 'Sin pared, equilibrio activo, hombros abiertos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 9
    // ========================================================================

    private static function level9(): array
    {
        return [
            'tips' => [
                'El one-arm pull-up es el pináculo de la tracción unilateral. Practica primero archer pulls amplios.',
                'El planche push-up requiere que mantengas la posición mientras empujas. No dejes caer cadera.',
                'Ahora trabajas skills completos en todos los bloques. La recuperación es tan importante como el entreno.',
                'El freestanding handstand push-up combina equilibrio y fuerza. Practica contra pared si fallas el equilibrio.',
                'El iron cross progresión en anillas es brutal para hombros. No más de 3-5 intentos por sesión.',
                'La tracción unilateral desarrolla desequilibrios si no equilibras. No ignores el bloque de accesorios.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a unilateral y planche push-up', 'note' => 'One-arm con asistencia, planche push-up con cadera alta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar unilateral y planche push-up', 'note' => 'Aumenta reps en one-arm progresión, planche push-up controlado', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en skills completos', 'note' => 'Introduce freestanding HSPU e iron cross progresión', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills de élite', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende one-arm con asistencia y planche push-up, cadera alta, control total',
                'week1' => 'Consolida unilateral y planche push-up, aumenta reps progresivamente',
                'week2' => 'Introduce freestanding HSPU e iron cross progresión, volumen en skills',
                'week3' => 'Máxima intensidad, simula tests, recuperación activa entre días',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 9',
                'description' => 'Evalúa si estás listo para el Nivel 10: Experto I',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 9',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 10 (Experto I)',
                    'requirements' => [
                        ['name' => 'One-Arm Pull-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Un brazo, cuerpo recto, barbilla sobre barra, sin rotación excesiva'],
                        ['name' => 'Planche Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Posición de planche, baja controlado, empuje sin doblar codos'],
                        ['name' => 'Freestanding Handstand Push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Sin pared, cuerpo recto, cabeza toca suelo, extensión completa'],
                        ['name' => 'Iron Cross Progression', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Anillas, brazos en cruz, cuerpo vertical, hombros bajados'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 10
    // ========================================================================

    private static function level10(): array
    {
        return [
            'tips' => [
                'El one-arm push-up requiere estabilidad de core extremo. No dejes caer la cadera del lado libre.',
                'El full planche es pura proyección de hombros. Empuja el suelo con fuerza máxima.',
                'El 90 degree push-up combina planche y push-up. Es uno de los movimientos más difíciles de calistenia.',
                'Ahora entrenas con 3 bloques de alta intensidad. La nutrición y el sueño son críticos.',
                'El freestanding handstand de 30s+ es tu base para todo el entrenamiento de handstand.',
                'No entrenes hasta el fallo muscular todos los días. Deja 1-2 reps en reserva en el bloque 1.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a movimientos de élite', 'note' => 'One-arm push-up con pies amplios, full planche con banda', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar one-arm y full planche', 'note' => 'Aumenta reps en one-arm, tiempo en full planche', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en movimientos de élite', 'note' => 'Introduce 90 degree push-up y planche push-up profundo', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en élite', 'note' => 'Simulacro de tests, nutrición y sueño perfectos', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende one-arm con pies amplios y full planche con banda, proyección máxima',
                'week1' => 'Consolida one-arm y full planche, aumenta reps y tiempo progresivamente',
                'week2' => 'Introduce 90 degree push-up y planche push-up profundo',
                'week3' => 'Máxima intensidad, simula tests, deja 1-2 reps en reserva',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 10',
                'description' => 'Evalúa si estás listo para el Nivel 11: Experto II',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutrición óptima', 'Dormir 8h']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 10',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 11 (Experto II)',
                    'requirements' => [
                        ['name' => 'One-Arm Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Un brazo, cadera estable, pecho al suelo, extensión completa'],
                        ['name' => 'Full Planche', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, piernas juntas, brazos rectos, hombros proyectados'],
                        ['name' => '90 Degree Push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Posición de planche, baja, empuje con cuerpo recto'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Sin pared, equilibrio activo, cuerpo recto'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 11
    // ========================================================================

    private static function level11(): array
    {
        return [
            'tips' => [
                'El one-arm handstand push-up es la combinación más difícil de equilibrio y fuerza. Practica en paralletes.',
                'La planche de 15s+ requiere años de proyección de hombros. No te rindas si tarda meses.',
                'El iron cross completo en anillas es brutal. Empieza con progresiones en banda elástica.',
                'Ahora el entrenamiento es de élite. Considera trabajar con un coach o grabar tus intentos.',
                'El 90 degree push-up de 5 reps es nivel mundial. Celebra cada rep como un logro.',
                'La recuperación activa (natación, yoga, movilidad) es obligatoria a este nivel.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a OA-HSPU y iron cross', 'note' => 'OA-HSPU con asistencia, iron cross con banda gruesa', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar OA-HSPU e iron cross', 'note' => 'Aumenta reps en OA-HSPU, tiempo en iron cross progresión', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en movimientos de élite', 'note' => 'Introduce planche máxima y 90 degree push-up avanzado', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, aproximación al máximo nivel', 'note' => 'Simulacro de tests, recuperación activa obligatoria', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende OA-HSPU con asistencia e iron cross con banda, control total',
                'week1' => 'Consolida OA-HSPU e iron cross, aumenta reps y tiempo progresivamente',
                'week2' => 'Introduce planche máxima y 90 degree push-up avanzado',
                'week3' => 'Máxima intensidad, simula tests, recuperación activa entre días',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests de Nivel 11',
                'description' => 'Evalúa si estás listo para el Nivel 12: Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutrición óptima', 'Dormir 8h', 'Mentalización']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 11',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 12 (Master)',
                    'requirements' => [
                        ['name' => 'One-Arm Handstand Push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Un brazo, sin pared, cuerpo recto, cabeza toca suelo'],
                        ['name' => 'Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, piernas juntas, brazos rectos'],
                        ['name' => 'Iron Cross', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Anillas, brazos horizontales, cuerpo vertical, hombros bajados'],
                        ['name' => '90 Degree Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Posición de planche, baja, empuje completo, cuerpo recto'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 12
    // ========================================================================

    private static function level12(): array
    {
        return [
            'tips' => [
                'Has llegado al nivel más alto de calistenia. Cada entrenamiento es una celebración de tu progreso.',
                'El one-arm handstand push-up de 10 reps es nivel olímpico. No compares, disfruta el proceso.',
                'La planche de 30s+ requiere dedicación extrema. Mantén la proyección de hombros como prioridad.',
                'El iron cross completo en anillas es el símbolo máximo de fuerza en calistenia.',
                'A este nivel, el entrenamiento es arte. Graba tus sesiones, comparte tu progreso, inspira a otros.',
                'Incluso en el nivel 12, hay margen de mejora: planche press, victorian cross, maltese. El cielo es el límite.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a élite absoluto', 'note' => 'Todos los skills con máxima dificultad, técnica perfecta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar todos los skills de élite', 'note' => 'Volumen alto en todos los bloques, control absoluto', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen máximo en skills completos', 'note' => 'Introduce variaciones aún más difíciles si es posible', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo rendimiento en calistenia', 'note' => 'Simulacro de tests finales, celebra cada logro', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Todos los skills con máxima dificultad, técnica perfecta, sin prisa',
                'week1' => 'Consolida todos los skills de élite, volumen alto, control absoluto',
                'week2' => 'Introduce variaciones aún más difíciles, explora nuevos límites',
                'week3' => 'Máximo rendimiento, simula tests finales, celebra tu progreso',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Finales Master',
                'description' => 'Evaluación final del programa Calistenia Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutrición óptima', 'Dormir 8h', 'Mentalización positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 12 MASTER',
                    'description' => 'Has completado el programa Calistenia Master. Estás en el 1% de la calistenia mundial.',
                    'requirements' => [
                        ['name' => 'One-Arm Handstand Push-up', 'minimum' => 5, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Un brazo, sin pared, cuerpo recto, control total'],
                        ['name' => 'Planche', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, piernas juntas, brazos rectos, hombros proyectados'],
                        ['name' => 'Iron Cross', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Anillas, brazos horizontales, cuerpo vertical, hombros bajados, control total'],
                        ['name' => '90 Degree Push-up', 'minimum' => 5, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Posición de planche, baja, empuje completo, cuerpo recto, sin doblar codos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // EXERCISE NOTES - LEVEL SPECIFIC (Niveles 1-3 con datos exactos de estáticos)
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Wall Push Up' => 'Manos en pared, cuerpo inclinado. Control total.',
        'Knee Push Up' => 'Rodillas en suelo, pecho toca el suelo.',
        'Incline Push Up' => 'Manos en banco elevado, cuerpo recto.',
        'High Plank' => 'Cuerpo recto, aguanta posición.',
        'Bench Dips' => 'Banco bajo, codos hacia atrás.',
        'Plank' => 'Cuerpo perfectamente recto, codos bajo hombros, caderas alineadas.',
        'Australian Pull Up - Wide' => 'Barra baja, agarre ancho, pecho a barra.',
        'Dead Hang' => 'Simplemente cuelga, agarre cómodo.',
        'Australian Pull Up' => 'Agarre normal, codos al cuerpo.',
        'Superman Hold' => 'Eleva pecho y piernas ligeramente.',
        'Active Hang' => 'Omóplatos activados, agarre fuerte.',
        'Sit To Stand' => 'De silla, sin impulso, controlado.',
        'Box Squat' => 'Sentadilla a caja, glúteos tocan, subes.',
        'Glute Bridge' => 'Espalda en suelo, eleva caderas.',
        'Standing Calf Raise' => 'De pie, sube en puntas.',
        'Dead Bug' => 'Contralateral lento, lumbar pegada.',
        'Reverse Plank' => 'Apoyo en manos, eleva caderas.',
        'Plank with Leg Raise' => 'En plank, levanta pierna alterna.',
        'Air Squat' => 'Sin caja, caderas abajo, técnica pura.',
        'Stationary Lunge' => 'Zancada fija, alterna piernas.',
        'Hip Thrust' => 'Espalda en banco, más rango.',
        'Single Leg Glute Bridge' => 'Una pierna, más intensidad.',
        'Side Plank' => 'Por lado, caderas altas.',
        'Hollow Body Hold' => 'Lumbar pegada, piernas extendidas.',
        'Standard Push Up (asistido)' => 'Intenta completo, si falla haz negativo.',
        'Assisted Pull Up' => 'Banda en pies o máquina, tira completo.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 5 segundos.',
        'Ring Row - Feet on Ground' => 'Si tienes anillas, alternativa a barra.',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Standard Push Up' => 'Push-up completo, cuerpo recto, pecho al suelo.',
        'Incline Push Up' => 'Banco bajo, cuerpo recto, control total.',
        'Bench Dips' => 'Pies en otro banco, más rango.',
        'Plank' => 'Cuerpo perfectamente recto, aguanta.',
        'Australian Pull Up - Normal' => 'Pecho a barra, escápulas juntas.',
        'Active Hang' => 'Omóplatos activados, agarre fuerte.',
        'Australian Pull Up - Wide' => 'Agarre ancho, más espalda.',
        'Dead Hang' => 'Agarre pasivo, relaja hombros.',
        'Air Squat' => 'Talones en suelo, glúteos abajo de rodillas.',
        'Dumbbell Lunge' => 'Zancada profunda, rodilla trasera casi toca.',
        'Calf Raise on Step' => 'En escalón, rango completo.',
        'Glute Bridge' => 'Cadera alta, contracción glúteo 2s arriba.',
        'Single Leg Glute Bridge' => 'Por pierna, cadera alta y estable.',
        'Hip Thrust' => 'Cadera alta, contracción 2s arriba.',
        'Dead Bug' => 'Lumbar pegada, movimiento lento y controlado.',
        'Hollow Body Hold' => 'Lumbar pegada al suelo, piernas extendidas.',
        'Side Plank' => 'Por lado, cadera alta, sin rotar.',
        'Superman Hold' => 'Brazos y piernas elevadas, contracción lumbar.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Prepara HSPU.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados, tríceps.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 5 segundos.',
        'Assisted Pull Up' => 'Banda en los pies, tirada completa, barbilla sobre barra.',
        'Australian Pull Up' => 'Control total, escápulas juntas.',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Standard Push Up' => 'Push-up completo, cuerpo recto, pecho al suelo.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados. Baja controlado.',
        'Wide Push Up' => 'Agarre ancho, enfocado en pecho, codos a 45 grados.',
        'Planche Lean' => 'Manos en suelo, cuerpo inclinado adelante, proyección de hombros.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 5 segundos mínimo.',
        'Band Assisted Pull Up' => 'Banda en los pies, tirada completa, barbilla sobre barra.',
        'Australian Pull Up' => 'Pecho a barra, escápulas juntas, control total.',
        'Active Hang' => 'Omóplatos activados, agarre fuerte, resistencia.',
        'Bulgarian Split Squat' => 'Pie trasero en banco, zancada profunda, torso erguido.',
        'Air Squat' => 'Talones en suelo, glúteos abajo de rodillas, rápido y controlado.',
        'Stationary Lunge' => 'Zancada profunda, rodilla trasera casi toca el suelo.',
        'Single Leg Glute Bridge' => 'Por pierna, cadera alta y estable, sin rotar.',
        'Tuck L-Sit' => 'Manos en suelo o soportes, rodillas al pecho, piernas off ground.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared. Baja controlado.',
        'Hollow Body Hold' => 'Lumbar pegada al suelo, piernas extendidas, brazos detrás.',
        'Plank' => 'Cuerpo perfectamente recto, codos bajo hombros, caderas alineadas.',
    ];

    // ========================================================================
    // GENERIC EXERCISE NOTES (Niveles 4-12 y fallback)
    // ========================================================================

    private const GENERIC_EXERCISE_NOTES = [
        // Push
        'Wall Push Up' => 'Manos en pared, cuerpo inclinado. Control total.',
        'Knee Push Up' => 'Rodillas en suelo, pecho toca el suelo.',
        'Incline Push Up' => 'Manos en banco elevado, cuerpo recto.',
        'Standard Push Up' => 'Cuerpo recto, pecho al suelo, extensión completa.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados al cuerpo.',
        'Wide Push Up' => 'Agarre ancho, codos a 45°, enfocado en pecho.',
        'Decline Push Up' => 'Pies elevados, más énfasis en hombros y pecho alto.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Prepara HSPU.',
        'Pseudo Planche Push Up' => 'Manos en cintura, cuerpo inclinado, codos pegados.',
        'Deficit Handstand Push Up' => 'Manos en plataformas, rango extra, baja controlado.',
        'Handstand Push Up' => 'Cuerpo recto, cabeza toca suelo, extensión completa.',
        'Archer Push Up' => 'Un brazo estirado lateral, el otro flexiona.',
        'Freestanding Handstand Push Up' => 'Sin pared, equilibrio activo, control total.',
        'Planche Push Up' => 'Mantén posición de planche mientras empujas.',
        'One-Arm Push Up' => 'Cadera estable, no dejes caer del lado libre.',
        'One-Arm Handstand Push Up' => 'Nivel olímpico. Un brazo, sin pared, control total.',
        '90 Degree Push Up' => 'Posición de planche, baja, empuje. Movimiento extremo.',
        'Wall Handstand Hold' => 'Cuerpo recto, hombros abiertos, mira entre manos.',
        'Planche Lean' => 'Proyección de hombros máxima, inclínate sin doblar codos.',
        'Advanced Tuck Planche' => 'Rodillas separadas 90°, espalda plana, brazos rectos.',
        'Straddle Planche' => 'Piernas abiertas 180°, espalda plana, brazos rectos.',
        'Planche' => 'Cuerpo recto horizontal, piernas juntas, proyección máxima.',
        'Freestanding Handstand' => 'Sin pared, equilibrio activo, hombros abiertos.',

        // Pull
        'Dead Hang' => 'Agarre pasivo, relaja hombros.',
        'Active Hang' => 'Omóplatos activados, agarre fuerte.',
        'Australian Pull Up' => 'Pecho a barra, escápulas juntas, control total.',
        'Australian Pull Up - Wide' => 'Barra baja, agarre ancho, pecho a barra.',
        'Australian Pull Up - Normal' => 'Agarre normal, codos al cuerpo.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 5 segundos mínimo.',
        'Assisted Pull Up' => 'Banda o máquina, tira completo, barbilla sobre barra.',
        'Band Assisted Pull Up' => 'Banda en pies, tirada completa, barbilla sobre barra.',
        'Standard Pull Up' => 'Barbilla sobre barra, control en bajada, cuerpo recto.',
        'Chin Up' => 'Agarre supino, más énfasis en bíceps.',
        'Wide Grip Pull Up' => 'Agarre ancho, más énfasis en dorsal.',
        'Archer Pull Up' => 'Un brazo estirado lateral, el otro tira.',
        'Muscle Up' => 'Técnica: transición de pull a dip es la clave.',
        'Weighted Chin Up' => 'Añade peso, control total, barbilla sobre barra.',
        'One Arm Pull Up' => 'Un brazo, cuerpo recto, barbilla sobre barra.',
        'One Arm Chin Up' => 'Un brazo supino, control máximo.',
        'Archer Chin Up' => 'Variante supina unilateral.',
        'Archer Pull Up - Wide' => 'Variante ancha unilateral.',
        '90 Degree Hold to Chin Up' => 'Hold en 90° + chin up completo.',
        'Tuck Front Lever Hold' => 'Rodillas al pecho, espalda horizontal, brazos rectos.',
        'Tuck Front Lever Row' => 'Rodillas al pecho, tira con omóplatos.',
        'Advanced Tuck Front Lever Hold' => 'Rodillas separadas 90°, espalda plana.',
        'Advanced Tuck Front Lever Row' => 'Rodillas separadas, tira hacia barra.',
        'Full Front Lever Hold' => 'Cuerpo recto horizontal, omóplatos juntos.',
        'Full Front Lever Row' => 'Cuerpo recto, tira con omóplatos.',
        'Front Lever Raise' => 'Desde dead hang a front lever, control.',
        'Back Lever Hold' => 'Cuerpo horizontal boca abajo, brazos rectos.',
        'Back Lever Row' => 'Desde back lever, tira hacia arriba.',
        'Back Lever Raise' => 'Desde hang a back lever, control.',
        'Human Flag' => 'Empuja abajo, tira arriba, cuerpo horizontal lateral.',

        // Legs
        'Air Squat' => 'Caderas abajo de rodillas, talones en suelo, torso erguido.',
        'Box Squat' => 'Sentadilla a caja, glúteos tocan, subes.',
        'Sit To Stand' => 'De silla, sin impulso, controlado.',
        'Jump Squat' => 'Explosivo, salta alto, aterriza suave.',
        'Bulgarian Split Squat' => 'Pie trasero en banco, zancada profunda, torso erguido.',
        'Stationary Lunge' => 'Zancada profunda, rodilla trasera casi toca.',
        'Dumbbell Lunge' => 'Con carga, zancada profunda, control.',
        'Glute Bridge' => 'Cadera alta, contracción glúteo 2s arriba.',
        'Single Leg Glute Bridge' => 'Por pierna, cadera alta, sin rotar.',
        'Hip Thrust' => 'Espalda en banco, cadera alta, rango completo.',
        'Calf Raise on Step' => 'En escalón, rango completo, pausa arriba.',
        'Standing Calf Raise' => 'De pie, sube en puntas, pausa arriba.',
        'Assisted Pistol Squat' => 'Con apoyo, baja controlado, pie completo.',
        'Nordic Curl' => 'Tobillos fijos, baja controlado, usa manos si es necesario.',

        // Core
        'Plank' => 'Cuerpo perfectamente recto, codos bajo hombros.',
        'High Plank' => 'Cuerpo recto, aguanta posición.',
        'Reverse Plank' => 'Apoyo en manos, eleva caderas.',
        'Side Plank' => 'Por lado, cadera alta, sin rotar.',
        'Plank with Leg Raise' => 'En plank, levanta pierna alterna.',
        'Dead Bug' => 'Contralateral lento, lumbar pegada.',
        'Hollow Body Hold' => 'Lumbar pegada al suelo, piernas extendidas.',
        'Superman Hold' => 'Brazos y piernas elevadas, contracción lumbar.',
        'Tuck L-Sit' => 'Manos en suelo, rodillas al pecho, piernas off ground.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared.',
        'L-Sit' => 'Piernas extendidas, manos en suelo/soportes.',
    ];
}
