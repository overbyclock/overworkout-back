<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Calistenia Master v2.0.
 * Tips, notas por semana, progresión y tests para cada nivel.
 * Separado del blueprint de ejercicios para facilitar edición y revisión.
 */
class CalisteniaMasterContentV2
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
                'Semana 0: Aprende la técnica sin prisa. Haz solo la mitad de repeticiones, enfócate en la forma.',
                'Semana 1: Prioriza el rango completo de movimiento sobre la cantidad de repeticiones.',
                'Semana 2: Aumenta 1-2 repeticiones por ejercicio solo si mantienes técnica perfecta.',
                'Semana 3: Busca el rango alto de repeticiones. El volumen construye la base.',
                'Los tests son para medir progreso, no para juzgar. Si fallas, aprendiste qué mejorar.',
                'El hollow body hold es la base de todos los levers. Domínalo antes de avanzar.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Aprender movimientos básicos', 'note' => 'Mitad de repeticiones, enfoque en técnica perfecta', 'intensity' => '50%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Mismos ejercicios, aumentar volumen', 'note' => 'Rango completo de repeticiones, control total', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes, más específicas', 'note' => 'Variantes más cercanas a los tests', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, preparación tests', 'note' => 'Ejercicios más difíciles de nivel 1', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende la técnica, haz solo la mitad de reps, sin presión',
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
                'Semana 0: Los ejercicios sin ayuda son más difíciles. Prioriza la técnica sobre las reps.',
                'Semana 1: Aumenta volumen gradualmente. No sacrifiques forma por repeticiones.',
                'Semana 2: Introduce pike push-up y negativa de dominada. Sé paciente.',
                'Semana 3: Simula los tests. Máxima intensidad con control.',
                'El wall handstand hold es tu base para todo el entrenamiento invertido. Practica diario.',
                'Si no pasas algún test, identifica el punto débil y practica ese ejercicio específico.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a peso corporal puro', 'note' => 'Mitad de reps, técnica perfecta', 'intensity' => '50%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar ejercicios nuevos', 'note' => 'Aumenta volumen gradualmente', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes y más reps', 'note' => 'Introduce pike push-up y negativa de dominada', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, evaluación', 'note' => 'Máxima intensidad, simulacro de tests', 'intensity' => '95%'],
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
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Standard Pull-ups', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Barbilla sobre barra, cuerpo recto, sin balanceo'],
                        ['name' => 'Pike Push-ups', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Cadera alta, hombros sobre manos, cabeza toca suelo'],
                        ['name' => 'Plank', 'minimum' => 60, 'target' => 75, 'unit' => 'segundos', 'form' => 'Cuerpo recto, codos bajo hombros, caderas alineadas'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 15, 'target' => 25, 'unit' => 'segundos', 'form' => 'Cuerpo recto, hombros abiertos, mira entre manos'],
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
                'Los wall walks preparan tu cuerpo para el handstand. No te apoyes contra la pared, úsala como guía.',
                'El L-sit tuck es la base del L-sit completo. Mantén la espalda recta y los hombros bajados.',
                'Las diamond push-ups desarrollan tríceps fuertes, necesarios para el handstand push-up futuro.',
                'El planche lean no es de repeticiones, es de posición. Inclínate todo lo que puedas sin doblar codos.',
                'Las band assisted pull-ups son la mejor forma de aprender la dominada. Usa banda para 5-8 reps.',
                'Si no pasas algún test, practica esa habilidad específica durante la semana de transición.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a skills técnicos', 'note' => 'Mitad de reps, técnica perfecta de wall walks y L-sit', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar nuevos ejercicios', 'note' => 'Aumenta volumen gradualmente, controla la bajada', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes y más reps', 'note' => 'Introduce diamond push-up y band assisted pull-up', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen, evaluación', 'note' => 'Máxima intensidad, simulacro de tests', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende wall walks y L-sit tuck, mitad de reps, técnica perfecta',
                'week1' => 'Completa el rango de repeticiones, control total en negativas',
                'week2' => 'Introduce diamond push-up y band assisted pull-up, aumenta estabilidad',
                'week3' => 'Máxima intensidad, simula los tests, descansa bien',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Chin-ups', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Palmas hacia ti, barbilla sobre barra, control total'],
                        ['name' => 'Diamond Push-ups', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Manos en diamante, codos pegados, pecho toca manos'],
                        ['name' => 'Tuck L-Sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Manos en suelo, rodillas al pecho, sin tocar suelo'],
                        ['name' => 'Wall Walks', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde plancha, camina manos hacia pared hasta posición vertical'],
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
                'El planche lean es pura proyección de hombros. Empuja el suelo con fuerza, codos rectos.',
                'El wall handstand hold de 30s+ es tu base para el HSPU. Respira tranquilo.',
                'Ahora entrenas en 2 bloques: fuerza primero, skills después. Descansa bien entre bloques (3 min).',
                'El pseudo planche push-up prepara tus muñecas para cargas horizontales. No apresures el tuck planche.',
                'La proyección de hombros se gana con paciencia. Practica holds de 10-15s con descansos largos.',
                'Las pistol assisted son la clave para dominadas de pierna. No saltes, baja controlado.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a proyección y holds', 'note' => 'Mitad de tiempo en holds, técnica sobre tiempo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar planche lean y wall HS', 'note' => 'Aumenta tiempo en holds gradualmente', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes de proyección', 'note' => 'Introduce pseudo planche push-up y pistol assisted', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en proyección', 'note' => 'Simulacro de tests, máxima intensidad en ambos bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende planche lean y wall HS, holds cortos, proyección máxima',
                'week1' => 'Consolida proyección, aumenta tiempo en holds 2-3 segundos',
                'week2' => 'Introduce pseudo planche push-up y pistol assisted, control total',
                'week3' => 'Máxima intensidad en proyección, simula tests, descansa entre bloques',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y muñecas', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Planche Lean', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Inclinación máxima, hombros sobre manos, codos rectos'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Cuerpo recto, hombros abiertos, mira entre manos'],
                        ['name' => 'Assisted Pistol Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Baja controlado, pie trasero en bajo apoyo, rodilla estable'],
                        ['name' => 'Pseudo Planche Push-up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Manos en cintura, cuerpo inclinado adelante, codos pegados'],
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
                'El tuck planche requiere proyección de hombros máxima. Empuja el suelo con fuerza.',
                'El tuck front lever es pura retracción escapular. Junta omóplatos antes de levantar cadera.',
                'Ahora entrenas skills en bloque 2. Descansa 3 min entre bloques. Calidad sobre cantidad.',
                'El dragon flag negative desarrolla core extremo. Baja en 5 segundos mínimo.',
                'El nordic curl negative es la mejor preparación para el curl completo. Controla la bajada.',
                'No compares tu día 1 con el día 100 de otro. La progresión de skills requiere paciencia.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a tuck planche y tuck FL', 'note' => 'Mitad de tiempo en holds, técnica perfecta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar tuck planche y tuck FL', 'note' => 'Aumenta tiempo en holds 2-3 segundos por sesión', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes de skills', 'note' => 'Introduce tuck FL row y dragon flag negative', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende tuck planche y tuck FL, holds cortos, proyección máxima',
                'week1' => 'Consolida skills básicos, aumenta tiempo en holds 2-3 segundos',
                'week2' => 'Introduce tuck FL row y dragon flag negative, control total',
                'week3' => 'Máxima intensidad en skills, simula tests, descansa entre bloques',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y caderas', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Tuck Planche', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, hombros proyectados, brazos rectos'],
                        ['name' => 'Tuck Front Lever', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, espalda horizontal, omóplatos juntos'],
                        ['name' => 'Box Pistol Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Pierna atrás en caja, baja controlado, pie completo en suelo'],
                        ['name' => 'Pike Push-up Profundo', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Cadera máxima altura, cabeza toca suelo entre manos'],
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
                'El advanced tuck planche es más difícil de lo que parece. Mantén la espalda plana, no arquees.',
                'Practica el back lever con cuidado. Si sientes dolor en hombros, detente y moviliza.',
                'La muscle-up negativa te enseña la transición. Es el momento más difícil: de pull a dip.',
                'En el segundo bloque, prioriza calidad sobre cantidad. Mejor 2 rondas perfectas que 3 malas.',
                'El active hang con 40s+ demuestra que tu agarre y escápulas están listos para front lever.',
                'El planche lean de 25s+ significa que tus muñecas están listas para cargas avanzadas.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a advanced tuck y back lever', 'note' => 'Holds cortos, sin arquear espalda', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar advanced tuck y back lever', 'note' => 'Aumenta tiempo progresivamente', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Nuevas variantes y más volumen', 'note' => 'Introduce muscle-up negativa y planche lean máximo', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills avanzados', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende advanced tuck y back lever, holds cortos, sin arquear espalda',
                'week1' => 'Consolida skills intermedios, aumenta tiempo progresivamente',
                'week2' => 'Introduce muscle-up negativa y planche lean máximo',
                'week3' => 'Máxima intensidad, simula tests, separa fuerza y skills claramente',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y muñecas', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Rodillas separadas 90°, espalda plana, brazos rectos'],
                        ['name' => 'Back Lever', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal boca abajo, brazos rectos, hombros abiertos'],
                        ['name' => 'Muscle-up Negativa', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Sube de cualquier forma, bajada controlada mínimo 5 segundos'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Cuerpo recto, hombros abiertos, mira entre manos'],
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
                'El handstand push-up contra pared es tu primer HSPU. Practica primero aquí para confianza.',
                'La pistol squat completa requiere movilidad de tobillo. Estira antes de entrenar piernas.',
                'Ahora tienes 3 bloques: fuerza, skills y accesorios. No descuides el bloque 3, previene lesiones.',
                'El full front lever (intro) es pura fuerza de tracción. Si no llegas, vuelve a advanced tuck.',
                'Las assisted dips preparan tus tríceps para dips libres. Baja hasta 90° de codo.',
                'El bloque de accesorios y prehab es clave para equilibrar musculatura y evitar descompensaciones.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a HSPU y pistol libre', 'note' => 'HSPU contra pared, pistol con apoyo mínimo', 'intensity' => '55%'],
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
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de tobillos y hombros', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Piernas abiertas moderadas, espalda plana, brazos rectos'],
                        ['name' => 'Full Front Lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, omóplatos juntos, brazos rectos'],
                        ['name' => 'Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Una pierna, cadera abajo de rodilla, pie completo en suelo'],
                        ['name' => 'Handstand Push-up (pared)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Contra pared, cuerpo recto, cabeza toca suelo, extensión completa'],
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
                'El full front lever consolidado es pura fuerza de tracción. Si no llegas, vuelve a advanced tuck.',
                'El human flag necesita empuje y tracción simultáneos. Empuja con el brazo de abajo, tira con el de arriba.',
                'El freestanding handstand es mental tanto como físico. Practica caídas controladas.',
                'Ahora el bloque 2 incluye skills muy avanzados. Descansa 3-4 min entre intentos.',
                'No ignores el bloque de accesorios. Los archer chin-ups equilibran la tracción unilateral.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a straddle y full FL consolidado', 'note' => 'Straddle con piernas abiertas moderadas, banda si es necesario', 'intensity' => '55%'],
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
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 9',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad de caderas y hombros', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 9',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Piernas abiertas 180°, espalda plana, brazos rectos'],
                        ['name' => 'Full Front Lever', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, omóplatos juntos, brazos rectos'],
                        ['name' => 'Human Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal lateral, empuja abajo, tira arriba'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 15, 'target' => 25, 'unit' => 'segundos', 'form' => 'Sin pared, equilibrio activo, hombros abiertos'],
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
                'El muscle-up limpio es técnica pura. La transición de pull a dip es el punto crítico.',
                'El freestanding handstand push-up combina equilibrio y fuerza. Practica contra pared si fallas.',
                'Ahora trabajas skills completos en todos los bloques. La recuperación es tan importante como el entreno.',
                'El archer push-up desarrolla estabilidad unilateral. Un brazo trabaja más que el otro.',
                'La tracción unilateral desarrolla desequilibrios si no equilibras. No ignores el bloque de accesorios.',
                'El human flag intro es el primer paso hacia el flag completo. Empieza con piernas tuck.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a muscle-up limpio y freestanding HSPU', 'note' => 'Muscle-up con asistencia mínima, HSPU con caídas controladas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar muscle-up y freestanding HSPU', 'note' => 'Aumenta reps en muscle-up, HSPU controlado', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en skills completos', 'note' => 'Introduce human flag intro y one-arm progresión', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills de élite', 'note' => 'Simulacro de tests, recuperación activa entre días', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende muscle-up limpio y freestanding HSPU, caídas controladas',
                'week1' => 'Consolida muscle-up y freestanding HSPU, aumenta reps progresivamente',
                'week2' => 'Introduce human flag intro y one-arm progresión, volumen en skills',
                'week3' => 'Máxima intensidad, simula tests, recuperación activa entre días',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 10',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Dormir bien', 'Hidratación']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 9',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 10',
                    'requirements' => [
                        ['name' => 'Muscle-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Dominada a fondo + fondo completo, sin impulso excesivo'],
                        ['name' => 'Archer Push-up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Un brazo estirado lateral, el otro flexiona, alterna'],
                        ['name' => 'Human Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal lateral, empuja abajo, tira arriba'],
                        ['name' => 'Freestanding Handstand Push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Sin pared, cuerpo recto, cabeza toca suelo, extensión completa'],
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
                'El planche push-up requiere mantener la posición mientras empujas. No dejes caer cadera.',
                'El weighted pull-up con +30% peso corporal es fuerza bruta. Aumenta el peso gradualmente.',
                'Ahora entrenas con 3 bloques de alta intensidad. La nutrición y el sueño son críticos.',
                'El freestanding handstand de 30s+ es tu base para todo el entrenamiento de handstand.',
                'El 90 degree push-up combina planche y push-up. Es uno de los movimientos más difíciles.',
                'No entrenes hasta el fallo todos los días. Deja 1-2 reps en reserva en el bloque 1.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a planche push-up y weighted pull-up', 'note' => 'Planche push-up con cadera alta, peso ligero en dominadas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar planche push-up y weighted pull-up', 'note' => 'Aumenta reps en planche push-up, peso en dominadas', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en skills de élite', 'note' => 'Introduce one-arm progresión y 90 degree push-up', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en fuerza élite', 'note' => 'Simulacro de tests, nutrición y sueño prioritarios', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende planche push-up y weighted pull-up, cadera alta, peso ligero',
                'week1' => 'Consolida planche push-up y weighted pull-up, aumenta peso progresivamente',
                'week2' => 'Introduce one-arm progresión y 90 degree push-up, volumen en skills',
                'week3' => 'Máxima intensidad, simula tests, nutrición y sueño prioritarios',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 11',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutrición adecuada', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 10',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 11',
                    'requirements' => [
                        ['name' => 'Planche Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Posición de planche, baja controlado, empuje sin doblar codos'],
                        ['name' => 'Weighted Pull-up (+30% BW)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Peso extra en cintura/cadenas, barbilla sobre barra, control total'],
                        ['name' => 'Dragon Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Pierna trasera en dragón, baja controlado, rodilla estable'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Sin pared, equilibrio activo, hombros abiertos'],
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
                'El full planche es pura proyección de hombros. Empuja el suelo con fuerza máxima.',
                'El one-arm pull-up es el pináculo de la tracción unilateral. Practica archer pulls amplios.',
                'Ahora entrenas skills completos en todos los bloques. La recuperación es crítica.',
                'El 90 degree push-up es una fusión de planche y push-up vertical. Requiere hombros de acero.',
                'El human flag completo necesita empuje y tracción máximos. No abandones el bloque de accesorios.',
                'La nutrición, sueño y movilidad son tan importantes como el entreno en este nivel.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a full planche y human flag', 'note' => 'Full planche con banda si es necesario, human flag con piernas straddle', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar full planche y human flag', 'note' => 'Aumenta tiempo en holds, control total', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en skills completos', 'note' => 'Introduce one-arm HSPU progresión y front lever row completo', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen en skills completos', 'note' => 'Simulacro de tests, recuperación activa entre días', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Aprende full planche y human flag, banda si es necesario, calidad sobre tiempo',
                'week1' => 'Consolida full planche y human flag, aumenta tiempo progresivamente',
                'week2' => 'Introduce one-arm HSPU progresión y front lever row completo',
                'week3' => 'Máxima intensidad, simula tests, recuperación activa entre días',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación para pasar al Nivel 12',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutrición adecuada', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 11',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 12',
                    'requirements' => [
                        ['name' => 'Full Planche', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, hombros proyectados máximo, brazos rectos'],
                        ['name' => 'One-Arm Pull-up (negativa)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Un brazo, bajada controlada mínimo 5 segundos, sin rotación excesiva'],
                        ['name' => 'Human Flag', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal lateral, empuja abajo, tira arriba'],
                        ['name' => '90 Degree Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde planche, empuje vertical, cuerpo recto, codos rectos'],
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
                'El freestanding HSPU consolidado requiere equilibrio perfecto. Practica caídas controladas.',
                'El one-arm chin-up es más difícil que el one-arm pull-up. La supinación añade complejidad.',
                'Ahora entrenas con 3 bloques de intensidad máxima. La recuperación es tan importante como el entreno.',
                'El maltese push-up es la base del iron cross. Empuja hacia abajo y afuera simultáneamente.',
                'No entrenes hasta el fallo todos los días. La consistencia supera la intensidad extrema.',
                'Llegar al Nivel 12 es un logro increíble. Disfruta el proceso, no solo el destino.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Semana 0: Adaptación', 'focus' => 'Introducción a élite completo', 'note' => 'Todos los skills a intensidad moderada', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Semana 1: Base', 'focus' => 'Consolidar todos los skills élite', 'note' => 'Aumenta volumen progresivamente', 'intensity' => '70%'],
                ['week' => 2, 'name' => 'Semana 2: Progresión', 'focus' => 'Volumen en élite', 'note' => 'Introduce maltese push-up y front lever raise completo', 'intensity' => '80%'],
                ['week' => 3, 'name' => 'Semana 3: Intensificación', 'focus' => 'Máximo volumen élite', 'note' => 'Simulacro de tests, descansa bien entre bloques', 'intensity' => '95%'],
            ],
            'progression' => [
                'week0' => 'Todos los skills a intensidad moderada, técnica perfecta',
                'week1' => 'Consolida todos los skills élite, aumenta volumen progresivamente',
                'week2' => 'Introduce maltese push-up y front lever raise completo',
                'week3' => 'Máxima intensidad, simula tests, descansa bien entre bloques',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Semana 4: Tests Oficiales',
                'description' => 'Evaluación final del programa Calistenia Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutrición adecuada', 'Dormir bien', 'Visualización positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 12',
                    'description' => 'Evaluación final. Superar estos tests demuestra dominio de calistenia élite.',
                    'requirements' => [
                        ['name' => 'Full Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, hombros proyectados máximo, brazos rectos'],
                        ['name' => 'Weighted Pull-up (+50% BW)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Peso extra en cintura/cadenas, barbilla sobre barra, control total'],
                        ['name' => 'Freestanding Handstand Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Sin pared, cuerpo recto, cabeza toca suelo, extensión completa'],
                        ['name' => 'One-Arm Chin-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Palma hacia ti, un brazo, barbilla sobre barra, sin rotación excesiva'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // EXERCISE NOTES
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Wall Push Up' => 'Mantén el cuerpo recto, codos a 45° del torso. Acerca pies a la pared para más dificultad.',
        'Knee Push Up' => 'Rodillas en suelo, cuerpo recto desde hombros a rodillas. Pecho toca suelo.',
        'Incline Push Up' => 'Manos elevadas. Cuanto más baja la inclinación, más difícil.',
        'High Plank' => 'Cuerpo recto, codos debajo hombros. Activa glúteos y core.',
        'Australian Pull Up' => 'Barra a altura de cadera. Pecho a la barra, cuerpo recto.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 3-5 segundos controlado.',
        'Active Hang' => 'Omóplatos activados, hombros lejos de orejas. No balancees.',
        'Dead Hang' => 'Relaja hombros, deja que el peso estire hombros y espalda.',
        'Air Squat' => 'Talones en suelo, caderas abajo de rodillas, pecho erguido.',
        'Box Squat' => 'Siéntate en caja/banco, mantén core apretado, levántate sin balanceo.',
        'Calf Raise on Step' => 'Baja el talón por debajo del escalón para estiramiento completo.',
        'Glute Bridge' => 'Empuja con talones, aprieta glúteos arriba. No arquees lumbar.',
        'Dead Bug' => 'Lumbar pegado al suelo. Baja brazo y pierna opuestos lentamente.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Levanta hombros y piernas al mismo tiempo.',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Standard Push Up' => 'Cuerpo recto, pecho al suelo, extensión completa. Codos a 45°.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados al cuerpo. Tríceps trabajan más.',
        'Wall Handstand Hold' => 'Manos a 20-30cm de pared. Hombros abiertos, mira entre manos.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared. Mantén cuerpo recto.',
        'Standard Pull Up' => 'Palmas hacia afuera, barbilla sobre barra, cuerpo recto.',
        'Scapular Pull Up' => 'Sin flexionar codos, solo activa omóplatos. Junta omóplatos abajo.',
        'Dumbbell Lunge' => 'Paso largo, rodilla trasera casi toca suelo. Torso erguido.',
        'Side Plank' => 'Codo debajo hombro, cuerpo recto. Activa oblicuos.',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Chin Up' => 'Palmas hacia ti, barbilla sobre barra. Bíceps trabajan más que en dominada.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Cabeza toca suelo entre manos.',
        'Band Assisted Pull Up' => 'Usa banda elástica en barra. Pie en banda para asistencia.',
        'Bulgarian Split Squat' => 'Pie trasero en banco. Baja controlado, rodilla delantera estable.',
        'Tuck L-Sit' => 'Manos en suelo o soportes. Rodillas al pecho, espalda recta.',
        'Leg Raise' => 'Colgado de barra, levanta piernas rectas hasta 90°. Sin balanceo.',
        'Single Leg Glute Bridge' => 'Un pie en suelo, otro extendido. Empuja con talón.',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Standard Push Up' => 'Cuerpo recto, pecho al suelo, extensión completa. Codos a 45° del torso.',
        'Plank' => 'Cuerpo recto, codos debajo hombros. Activa glúteos y core. Sin hundir caderas.',
        'Air Squat' => 'Talones en suelo, caderas abajo de rodillas, pecho erguido. Control total.',
        'Australian Pull Up' => 'Barra a altura de cadera. Pecho a la barra, cuerpo recto.',
        'Incline Push Up' => 'Manos elevadas. Cuanto más baja la inclinación, más difícil.',
        'Knee Push Up' => 'Rodillas en suelo, cuerpo recto desde hombros a rodillas. Pecho toca suelo.',
        'Wall Push Up' => 'Mantén el cuerpo recto, codos a 45° del torso. Acerca pies a la pared para más dificultad.',
        'Box Squat' => 'Siéntate en caja/banco, mantén core apretado, levántate sin balanceo.',
        'Glute Bridge' => 'Empuja con talones, aprieta glúteos arriba. No arquees lumbar.',
        'Dead Bug' => 'Lumbar pegado al suelo. Baja brazo y pierna opuestos lentamente.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Levanta hombros y piernas al mismo tiempo.',
        'High Plank' => 'Cuerpo recto, codos debajo hombros. Activa glúteos y core.',
        'Planche Lean' => 'Inclínate hacia adelante todo lo posible sin doblar codos. Proyección máxima.',
        'Pseudo Planche Push Up' => 'Manos en cintura. Cuerpo inclinado adelante. Codos pegados al bajar.',
        'Tuck Planche' => 'Rodillas al pecho. Empuja el suelo con fuerza. Brazos rectos siempre.',
        'Tuck Front Lever Hold' => 'Rodillas al pecho. Espalda horizontal. Junta omóplatos.',
        'Tuck Front Lever Row' => 'Desde tuck FL, tira barra al pecho bajo. Omóplatos juntos.',
        'Advanced Tuck Planche' => 'Rodillas separadas 90°. Espalda plana. No arquees.',
        'Advanced Tuck Front Lever Hold' => 'Rodillas separadas 90°. Espalda horizontal. Brazos rectos.',
        'Back Lever Hold' => 'Cuerpo horizontal boca abajo. Brazos rectos. Hombros abiertos.',
        'Back Lever Hold' => 'Rodillas separadas. Cuerpo horizontal boca abajo. Control total.',
        'Straddle Planche' => 'Piernas abiertas. Espalda plana. Brazos rectos. Proyección máxima.',
        'Straddle Planche' => 'Piernas abiertas moderadamente. Mantén la forma del tuck planche.',
        'Full Front Lever Hold' => 'Cuerpo recto horizontal. Omóplatos juntos. Brazos rectos.',
        'Full Front Lever Hold' => 'Cuerpo recto horizontal. Usa banda si necesitas asistencia.',
        'Human Flag' => 'Cuerpo horizontal lateral. Empuja con brazo de abajo, tira con el de arriba.',
        'Human Flag' => 'Piernas tuck o straddle. Enfócate en la posición, no en el tiempo.',
        'Freestanding Handstand' => 'Sin pared. Equilibrio activo con dedos. Hombros abiertos.',
        'Freestanding Handstand Hold' => 'Sin pared. Caídas controladas. Respira tranquilo.',
        'Handstand Push Up' => 'Contra pared o libre. Cabeza toca suelo. Extensión completa.',
        'Deficit Handstand Push Up' => 'Manos en plataformas. Cabeza baja de nivel de manos. Emújese completo.',
        'deficit HSPU' => 'Manos en plataformas. Cabeza baja de nivel de manos. Emújese completo.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Cabeza toca suelo entre manos.',
        'Freestanding Handstand Push Up' => 'Sin pared. Equilibrio + fuerza. Caídas controladas.',
        'One-Arm Handstand Push Up' => 'Una mano en suelo. Equilibrio extremo. Empieza contra pared.',
        'One-Arm Handstand Push Up' => 'Usa pared o asistencia. Baja controlado.',
        'Muscle Up' => 'Dominada a fondo + fondo completo. La transición es el punto crítico.',
        'Muscle Up Negative' => 'Sube de cualquier forma, baja controlado por la transición.',
        'Muscle Up Progression' => 'Usa banda o asistencia. Enfócate en la transición.',
        'Weighted Chin Up' => 'Peso extra en cintura o mochila. Aumenta peso gradualmente.',
        'Weighted Chin Up' => 'Peso extra ligero. Prioriza técnica sobre peso.',
        'One Arm Pull Up' => 'Un brazo. Empieza con negativas y asistencia.',
        'One-Arm Pull Up' => 'Un brazo, bajada controlada. Mínimo 5 segundos.',
        'One Arm Chin Up' => 'Palma hacia ti, un brazo. Más difícil que OAP.',
        'One Arm Chin Up Negative' => 'Palma hacia ti, bajada controlada. Mínimo 5 segundos.',
        'Archer Push Up' => 'Un brazo estirado lateral, el otro flexiona. Alterna.',
        'Archer Pull Up' => 'Un brazo estirado lateral, el otro tira. Alterna.',
        'Archer Pull Up - Wide' => 'Apertura amplia. Un brazo recto, el otro tira con fuerza.',
        'Archer Chin Up' => 'Palma hacia ti, un brazo estirado lateral. Alterna.',
        'Wide Grip Pull Up' => 'Agarre amplio. Enfócate en la espalda, no en bíceps.',
        'Wide Push Up' => 'Manos más anchas que hombros. Enfócate en pecho.',
        'Decline Push Up' => 'Pies elevados. Más inclinación = más dificultad.',
        'Assisted Dips' => 'Usa banda o banco. Baja hasta 90° de codo.',
        'Box Pistol Squat' => 'Pierna atrás en caja/banco. Baja controlado.',
        'Assisted Pistol Squat' => 'Usa agarre o banda. Baja controlado, no saltes.',
        'Pistol Squat' => 'Una pierna. Cadera abajo de rodilla. Pie completo en suelo.',
        'Dragon Pistol Squat' => 'Pierna trasera en dragón. Baja controlado.',
        'Jump Squat' => 'Salta explosivo desde sentadilla. Aterriza suave.',
        'Nordic Curl Negative' => 'Anca en suelo, baja controlado. Mínimo 5 segundos.',
        'Nordic Curl with Band' => 'Usa banda o manos. Controla la bajada.',
        'Nordic Curl with Band' => 'Asistencia mínima. Intenta subir con control.',
        'Nordic Curl' => 'Sin asistencia. Bajada y subida controladas.',
        'Cossack Squat' => 'Pierna recta lateral, otra en sentadilla profunda. Movilidad de cadera.',
        'Deep Squat Hold' => 'Sentadilla profunda mantenida. Espalda recta, talones suelo.',
        'Deep Squat Hold' => 'Zancada profunda, empuja caderas hacia adelante. 20-30s por lado.',
        'Hollow Body Rock' => 'Desde hollow body, balancea adelante y atrás sin tocar suelo.',
        'Hollow Body Press' => 'Desde hollow body, empuja manos hacia arriba. Core extremo.',
        'Dragon Flag' => 'Cuerpo recto desde hombros a pies. Baja en 5 segundos.',
        'Dragon Flag Negative' => 'Cuerpo recto, baja controlado. Mínimo 5 segundos.',
        'L-Sit' => 'Piernas rectas, elevadas del suelo. Espalda recta, hombros bajados.',
        'Tuck L-Sit' => 'Rodillas al pecho. Mantén espalda recta y hombros bajados.',
        'L-Sit One Leg' => 'Una pierna recta, otra en tuck. Transición hacia L-sit completo.',
        'Planche Push Up' => 'Mantén posición de planche mientras empujas. No dejes caer cadera.',
        'Maltese Push Up' => 'Empuja hacia abajo y afuera. Base del iron cross.',
        '90 Degree Push Up' => 'Desde planche, empuje vertical. Uno de los movimientos más difíciles.',
        '90 Degree Push Up' => 'Practica la transición desde planche. Control total.',
        '90 Degree Hold to Chin Up' => 'Desde 90 degree hold, tira hasta chin up. Fuerza extrema.',
        'Front Lever Raise' => 'Desde dead hang, eleva cuerpo hasta front lever. Control total.',
        'Full Front Lever Row' => 'Desde full FL, tira barra al pecho bajo. Omóplatos juntos.',
        'Back Lever Row' => 'Desde back lever, tira barra al pecho. Control total.',
        'Advanced Tuck Front Lever Row' => 'Desde advanced tuck FL, tira barra al pecho. Omóplatos juntos.',
        'Planche' => 'Cuerpo recto horizontal. Proyección máxima. Brazos rectos.',
        'Planche Lean' => 'Inclínate hacia adelante todo lo posible sin doblar codos.',
    ];
}
