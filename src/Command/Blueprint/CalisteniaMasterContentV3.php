<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Calistenia Master v3.0.
 * Tips, notas por fase, progresion y tests para cada nivel.
 * v3 usa ciclos con 4 fases (Base->Progresion->Intensificacion->Recuperacion) en lugar de semanas fijas.
 * Separado del blueprint de ejercicios para facilitar edicion y revision.
 */
class CalisteniaMasterContentV3
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
     * Devuelve la nota tecnica para un ejercicio especifico en un nivel.
     * Niveles 1-3 tienen notas detalladas; niveles 4-12 usan notas genericas por ejercicio.
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
                'Fase 1 (Base): Aprende la tecnica sin prisa. Haz solo la mitad de repeticiones, enfocate en la forma.',
                'Fase 2 (Progresion): Prioriza el rango completo de movimiento sobre la cantidad de repeticiones.',
                'Fase 3 (Intensificacion): Aumenta 1-2 repeticiones por ejercicio solo si mantienes tecnica perfecta.',
                'Fase 4 (Recuperacion): Descansa activamente. Movilidad suave y tecnica a baja intensidad. No es parar.',
                'Los ciclos se repiten hasta superar el test. No hay calendario fijo: avanza a tu ritmo con tecnica perfecta.',
                'Las sesiones A y B son fuerza pura: descansa 90-150s entre series. El hollow body hold es la base de todos los levers.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Aprender movimientos basicos', 'note' => 'Mitad de repeticiones, enfoque en tecnica perfecta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Mismos ejercicios, aumentar volumen', 'note' => 'Rango completo de repeticiones, control total', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Nuevas variantes, mas especificas', 'note' => 'Variantes mas cercanas a los tests', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad suave, tecnica a baja intensidad', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende la tecnica, haz solo la mitad de reps, sin presion',
                'week1' => 'Progresion: Enfoque en tecnica perfecta, rango bajo-moderado de repeticiones',
                'week2' => 'Intensificacion: Aumenta 1-2 repeticiones por ejercicio si la tecnica es buena',
                'week3' => 'Recuperacion: Volumen reducido, movilidad suave, manten buena forma',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Visualizacion positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar al menos 4 de 5 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Push-ups', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, core apretado, extension completa'],
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
                'Fase 1 (Base): Los ejercicios sin ayuda son mas dificiles. Prioriza la tecnica sobre las reps.',
                'Fase 2 (Progresion): Aumenta volumen gradualmente. No sacrifiques forma por repeticiones.',
                'Fase 3 (Intensificacion): Simula los tests. Maxima intensidad con control.',
                'Fase 4 (Recuperacion): Descansa activamente. El deload no es parar, es recuperar para el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. Si no pasas, identifica el punto debil y practica ese ejercicio especifico.',
                'Las sesiones A y B requieren descansos de 90-150s entre series. El wall handstand hold es tu base para todo el entrenamiento invertido. Practica diario.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a peso corporal puro', 'note' => 'Mitad de reps, tecnica perfecta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar ejercicios nuevos', 'note' => 'Aumenta volumen gradualmente', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Nuevas variantes y mas reps', 'note' => 'Introduce pike push-up y negativa de dominada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad suave, prepara el siguiente ciclo', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende los ejercicios sin ayudas, mitad de reps, tecnica perfecta',
                'week1' => 'Progresion: Completa el rango de repeticiones, control total',
                'week2' => 'Intensificacion: Introduce variantes mas dificiles, aumenta estabilidad',
                'week3' => 'Recuperacion: Volumen reducido, movilidad suave, prepara el siguiente ciclo',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Dormir bien', 'Hidratacion']],
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
                'Fase 1 (Base): Los wall walks preparan tu cuerpo para el handstand. No te apoyes contra la pared, usala como guia.',
                'Fase 2 (Progresion): El L-sit tuck es la base del L-sit completo. Manten la espalda recta y los hombros bajados.',
                'Fase 3 (Intensificacion): Las diamond push-ups desarrollan triceps fuertes, necesarios para el handstand push-up futuro.',
                'Fase 4 (Recuperacion): Descansa activamente. Movilidad de hombros y munecas para preparar el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. La paciencia con los skills supera la prisa por avanzar.',
                'Las sesiones A y B requieren descansos de 90-150s entre series. Las band assisted pull-ups son la mejor forma de aprender la dominada.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a skills tecnicos', 'note' => 'Mitad de reps, tecnica perfecta de wall walks y L-sit', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar nuevos ejercicios', 'note' => 'Aumenta volumen gradualmente, controla la bajada', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Nuevas variantes y mas reps', 'note' => 'Introduce diamond push-up y band assisted pull-up', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad de hombros y munecas', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende wall walks y L-sit tuck, mitad de reps, tecnica perfecta',
                'week1' => 'Progresion: Completa el rango de repeticiones, control total en negativas',
                'week2' => 'Intensificacion: Introduce diamond push-up y band assisted pull-up, aumenta estabilidad',
                'week3' => 'Recuperacion: Volumen reducido, movilidad de hombros y munecas',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Chin-ups', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Palmas hacia ti, barbilla sobre barra, control total'],
                        ['name' => 'Diamond Push-ups', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Manos en diamante, codos pegados, pecho toca manos'],
                        ['name' => 'Tuck L-Sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Manos en suelo, rodillas al pecho, sin tocar suelo'],
                        ['name' => 'Wall Walks', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Desde plancha, camina manos hacia pared hasta posicion vertical'],
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
                'Fase 1 (Base): El planche lean es pura proyeccion de hombros. Empuja el suelo con fuerza, codos rectos.',
                'Fase 2 (Progresion): El wall handstand hold de 30s+ es tu base para el HSPU. Respira tranquilo.',
                'Fase 3 (Intensificacion): Ahora entrenas en 2 bloques: fuerza primero, skills despues. Descansa bien entre bloques (3 min).',
                'Fase 4 (Recuperacion): Descansa activamente. Movilidad de hombros y munecas. Recuperacion no es sinonimo de parar.',
                'Los ciclos se repiten hasta superar el test. La proyeccion de hombros se gana con paciencia. Practica holds de 10-15s con descansos largos.',
                'Las sesiones A y B son fuerza pura: descansa 2-3 min entre series para maxima calidad. Las pistol assisted son la clave para dominadas de pierna.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a proyeccion y holds', 'note' => 'Mitad de tiempo en holds, tecnica sobre tiempo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar planche lean y wall HS', 'note' => 'Aumenta tiempo en holds gradualmente', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Nuevas variantes de proyeccion', 'note' => 'Introduce pseudo planche push-up y pistol assisted', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad de hombros y munecas', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende planche lean y wall HS, holds cortos, proyeccion maxima',
                'week1' => 'Progresion: Consolida proyeccion, aumenta tiempo en holds 2-3 segundos',
                'week2' => 'Intensificacion: Introduce pseudo planche push-up y pistol assisted, control total',
                'week3' => 'Recuperacion: Volumen reducido, movilidad de hombros y munecas',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y munecas', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Planche Lean', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Inclinacion maxima, hombros sobre manos, codos rectos'],
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
                'Fase 1 (Base): El tuck planche requiere proyeccion de hombros maxima. Empuja el suelo con fuerza.',
                'Fase 2 (Progresion): El tuck front lever es pura retraccion escapular. Junta omoplatos antes de levantar cadera.',
                'Fase 3 (Intensificacion): Ahora entrenas skills en bloque 2. Descansa 3 min entre bloques. Calidad sobre cantidad.',
                'Fase 4 (Recuperacion): Recuperacion activa. Movilidad y tecnica a baja intensidad. Prepara el cuerpo para el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. No compares tu dia 1 con el dia 100 de otro. La progresion de skills requiere paciencia.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. El dragon flag negative desarrolla core extremo. Baja en 5 segundos minimo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a tuck planche y tuck FL', 'note' => 'Mitad de tiempo en holds, tecnica perfecta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar tuck planche y tuck FL', 'note' => 'Aumenta tiempo en holds 2-3 segundos por sesion', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Nuevas variantes de skills', 'note' => 'Introduce tuck FL row y dragon flag negative', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad de hombros y caderas', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende tuck planche y tuck FL, holds cortos, proyeccion maxima',
                'week1' => 'Progresion: Consolida skills basicos, aumenta tiempo en holds 2-3 segundos',
                'week2' => 'Intensificacion: Introduce tuck FL row y dragon flag negative, control total',
                'week3' => 'Recuperacion: Volumen reducido, movilidad de hombros y caderas',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y caderas', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Tuck Planche', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, hombros proyectados, brazos rectos'],
                        ['name' => 'Tuck Front Lever', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, espalda horizontal, omoplatos juntos'],
                        ['name' => 'Box Pistol Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Pierna atras en caja, baja controlado, pie completo en suelo'],
                        ['name' => 'Pike Push-up Profundo', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Cadera maxima altura, cabeza toca suelo entre manos'],
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
                'Fase 1 (Base): El advanced tuck planche es mas dificil de lo que parece. Manten la espalda plana, no arquees.',
                'Fase 2 (Progresion): Practica el back lever con cuidado. Si sientes dolor en hombros, detente y moviliza.',
                'Fase 3 (Intensificacion): La muscle-up negativa te ensena la transicion. Es el momento mas dificil: de pull a dip.',
                'Fase 4 (Recuperacion): Recuperacion activa. Movilidad completa. Deja que el sistema nervioso se recupere.',
                'Los ciclos se repiten hasta superar el test. En el segundo bloque, prioriza calidad sobre cantidad. Mejor 2 rondas perfectas que 3 malas.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. El active hang con 40s+ demuestra que tu agarre y escapulas estan listos para front lever.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a advanced tuck y back lever', 'note' => 'Holds cortos, sin arquear espalda', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar advanced tuck y back lever', 'note' => 'Aumenta tiempo progresivamente', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Nuevas variantes y mas volumen', 'note' => 'Introduce muscle-up negativa y planche lean maximo', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad de hombros y munecas', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende advanced tuck y back lever, holds cortos, sin arquear espalda',
                'week1' => 'Progresion: Consolida skills intermedios, aumenta tiempo progresivamente',
                'week2' => 'Intensificacion: Introduce muscle-up negativa y planche lean maximo',
                'week3' => 'Recuperacion: Volumen reducido, movilidad de hombros y munecas',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad de hombros y munecas', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Rodillas separadas 90 grados, espalda plana, brazos rectos'],
                        ['name' => 'Back Lever', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal boca abajo, brazos rectos, hombros abiertos'],
                        ['name' => 'Muscle-up Negativa', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Sube de cualquier forma, bajada controlada minimo 5 segundos'],
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
                'Fase 1 (Base): El handstand push-up contra pared es tu primer HSPU. Practica primero aqui para confianza.',
                'Fase 2 (Progresion): La pistol squat completa requiere movilidad de tobillo. Estira antes de entrenar piernas.',
                'Fase 3 (Intensificacion): Ahora tienes 3 bloques: fuerza, skills y accesorios. No descuides el bloque 3, previene lesiones.',
                'Fase 4 (Recuperacion): Recuperacion activa. Estira tobillos y hombros. El deload mantiene el progreso a largo plazo.',
                'Los ciclos se repiten hasta superar el test. El full front lever es pura fuerza de traccion. Si no llegas, vuelve a advanced tuck.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. Los dips asistidos preparan tus triceps para dips libres.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a HSPU y pistol libre', 'note' => 'HSPU contra pared, pistol con apoyo minimo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar HSPU y pistol squat', 'note' => 'Aumenta reps en HSPU, pistol sin apoyo', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen en 3 bloques', 'note' => 'Introduce dips asistidos y front lever rows', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, estira tobillos y hombros', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende HSPU contra pared y pistol con apoyo minimo, tecnica perfecta',
                'week1' => 'Progresion: Consolida HSPU y pistol libre, aumenta reps progresivamente',
                'week2' => 'Intensificacion: Introduce dips asistidos y front lever rows, volumen en 3 bloques',
                'week3' => 'Recuperacion: Volumen reducido, estira tobillos y hombros',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad de tobillos y hombros', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Piernas abiertas moderadas, espalda plana, brazos rectos'],
                        ['name' => 'Full Front Lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, omoplatos juntos, brazos rectos'],
                        ['name' => 'Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Una pierna, cadera abajo de rodilla, pie completo en suelo'],
                        ['name' => 'Handstand Push-up (pared)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Contra pared, cuerpo recto, cabeza toca suelo, extension completa'],
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
                'Fase 1 (Base): El straddle planche requiere flexibilidad de cadera. Estira splits antes de practicarlo.',
                'Fase 2 (Progresion): El full front lever consolidado es pura fuerza de traccion. Si no llegas, vuelve a advanced tuck.',
                'Fase 3 (Intensificacion): El human flag necesita empuje y traccion simultaneos. Empuja con el brazo de abajo, tira con el de arriba.',
                'Fase 4 (Recuperacion): Recuperacion activa. Flexibilidad de caderas y hombros. Recupera para el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. El freestanding handstand es mental tanto como fisico. Practica caidas controladas.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. Ahora el bloque 2 incluye skills muy avanzados. Descansa 3-4 min entre intentos.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a straddle y full FL consolidado', 'note' => 'Straddle con piernas abiertas moderadas, banda si es necesario', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar straddle y full FL', 'note' => 'Aumenta tiempo en holds, human flag con piernas tuck', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen y nuevas variantes', 'note' => 'Introduce freestanding handstand y archer chin-ups', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, flexibilidad de caderas y hombros', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende straddle planche y full FL, flexibilidad de cadera, banda si es necesario',
                'week1' => 'Progresion: Consolida straddle y full FL, human flag con piernas tuck',
                'week2' => 'Intensificacion: Introduce freestanding handstand y archer chin-ups',
                'week3' => 'Recuperacion: Volumen reducido, flexibilidad de caderas y hombros',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 9',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad de caderas y hombros', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 9',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Piernas abiertas 180 grados, espalda plana, brazos rectos'],
                        ['name' => 'Full Front Lever', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, omoplatos juntos, brazos rectos'],
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
                'Fase 1 (Base): El muscle-up limpio es tecnica pura. La transicion de pull a dip es el punto critico.',
                'Fase 2 (Progresion): El freestanding handstand push-up combina equilibrio y fuerza. Practica contra pared si fallas.',
                'Fase 3 (Intensificacion): Ahora trabajas skills completos en todos los bloques. La recuperacion es tan importante como el entreno.',
                'Fase 4 (Recuperacion): Recuperacion activa. Movilidad completa y tecnica ligera. No pierdas el ritmo.',
                'Los ciclos se repiten hasta superar el test. El archer push-up desarrolla estabilidad unilateral. Un brazo trabaja mas que el otro.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. La traccion unilateral desarrolla desequilibrios si no equilibras. No ignores el bloque de accesorios.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a muscle-up limpio y freestanding HSPU', 'note' => 'Muscle-up con asistencia minima, HSPU con caidas controladas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar muscle-up y freestanding HSPU', 'note' => 'Aumenta reps en muscle-up, HSPU controlado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen en skills completos', 'note' => 'Introduce human flag intro y one-arm progresion', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad completa', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende muscle-up limpio y freestanding HSPU, caidas controladas',
                'week1' => 'Progresion: Consolida muscle-up y freestanding HSPU, aumenta reps progresivamente',
                'week2' => 'Intensificacion: Introduce human flag intro y one-arm progresion, volumen en skills',
                'week3' => 'Recuperacion: Volumen reducido, movilidad completa',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 10',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Dormir bien', 'Hidratacion']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 9',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 10',
                    'requirements' => [
                        ['name' => 'Muscle-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Dominada a fondo + fondo completo, sin impulso excesivo'],
                        ['name' => 'Archer Push-up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Un brazo estirado lateral, el otro flexiona, alterna'],
                        ['name' => 'Human Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo horizontal lateral, empuja abajo, tira arriba'],
                        ['name' => 'Freestanding Handstand Push-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Sin pared, cuerpo recto, cabeza toca suelo, extension completa'],
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
                'Fase 1 (Base): El planche push-up requiere mantener la posicion mientras empujas. No dejes caer cadera.',
                'Fase 2 (Progresion): El weighted pull-up con +30 por ciento peso corporal es fuerza bruta. Aumenta el peso gradualmente.',
                'Fase 3 (Intensificacion): Ahora entrenas con 3 bloques de alta intensidad. La nutricion y el sueno son criticos.',
                'Fase 4 (Recuperacion): Recuperacion activa. Nutricion y sueno prioritarios. El deload prepara para el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. El freestanding handstand de 30s+ es tu base para todo el entrenamiento de handstand.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. No entrenes hasta el fallo todos los dias. Deja 1-2 reps en reserva.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a planche push-up y weighted pull-up', 'note' => 'Planche push-up con cadera alta, peso ligero en dominadas', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar planche push-up y weighted pull-up', 'note' => 'Aumenta reps en planche push-up, peso en dominadas', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen en skills de elite', 'note' => 'Introduce one-arm progresion y 90 degree push-up', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, nutricion y sueno prioritarios', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende planche push-up y weighted pull-up, cadera alta, peso ligero',
                'week1' => 'Progresion: Consolida planche push-up y weighted pull-up, aumenta peso progresivamente',
                'week2' => 'Intensificacion: Introduce one-arm progresion y 90 degree push-up, volumen en skills',
                'week3' => 'Recuperacion: Volumen reducido, nutricion y sueno prioritarios',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 11',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion adecuada', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 10',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 11',
                    'requirements' => [
                        ['name' => 'Planche Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Posicion de planche, baja controlado, empuje sin doblar codos'],
                        ['name' => 'Weighted Pull-up (+30% BW)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Peso extra en cintura/cadenas, barbilla sobre barra, control total'],
                        ['name' => 'Dragon Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Pierna trasera en dragon, baja controlado, rodilla estable'],
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
                'Fase 1 (Base): El full planche es pura proyeccion de hombros. Empuja el suelo con fuerza maxima.',
                'Fase 2 (Progresion): El one-arm pull-up es el pinaculo de la traccion unilateral. Practica archer pulls amplios.',
                'Fase 3 (Intensificacion): Ahora entrenas skills completos en todos los bloques. La recuperacion es critica.',
                'Fase 4 (Recuperacion): Recuperacion activa completa. Movilidad, nutricion y descanso para el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. El 90 degree push-up es una fusion de planche y push-up vertical. Requiere hombros de acero.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. La nutricion, sueno y movilidad son tan importantes como el entreno.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a full planche y human flag', 'note' => 'Full planche con banda si es necesario, human flag con piernas straddle', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar full planche y human flag', 'note' => 'Aumenta tiempo en holds, control total', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen en skills completos', 'note' => 'Introduce one-arm HSPU progresion y front lever row completo', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, recuperacion activa entre dias', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Aprende full planche y human flag, banda si es necesario, calidad sobre tiempo',
                'week1' => 'Progresion: Consolida full planche y human flag, aumenta tiempo progresivamente',
                'week2' => 'Intensificacion: Introduce one-arm HSPU progresion y front lever row completo',
                'week3' => 'Recuperacion: Volumen reducido, recuperacion activa entre dias',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 12',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion adecuada', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 11',
                    'description' => 'Debes superar los 4 tests para avanzar al Nivel 12',
                    'requirements' => [
                        ['name' => 'Full Planche', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, hombros proyectados maximo, brazos rectos'],
                        ['name' => 'One-Arm Pull-up (negativa)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Un brazo, bajada controlada minimo 5 segundos, sin rotacion excesiva'],
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
                'Fase 1 (Base): El freestanding HSPU consolidado requiere equilibrio perfecto. Practica caidas controladas.',
                'Fase 2 (Progresion): El one-arm chin-up es mas dificil que el one-arm pull-up. La supinacion anade complejidad.',
                'Fase 3 (Intensificacion): Ahora entrenas con 3 bloques de intensidad maxima. La recuperacion es tan importante como el entreno.',
                'Fase 4 (Recuperacion): Recuperacion activa. Movilidad completa. Deja que el cuerpo se recupere para el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. Llegar al Nivel 12 es un logro increible. Disfruta el proceso, no solo el destino.',
                'Las sesiones A y B requieren descansos de 2-3 min entre series. La consistencia supera la intensidad extrema.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion a elite completo', 'note' => 'Todos los skills a intensidad moderada', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Consolidar todos los skills elite', 'note' => 'Aumenta volumen progresivamente', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen en elite', 'note' => 'Introduce maltese push-up y front lever raise completo', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, descansa bien entre bloques', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Todos los skills a intensidad moderada, tecnica perfecta',
                'week1' => 'Progresion: Consolida todos los skills elite, aumenta volumen progresivamente',
                'week2' => 'Intensificacion: Introduce maltese push-up y front lever raise completo',
                'week3' => 'Recuperacion: Volumen reducido, descansa bien entre bloques',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion final del programa Calistenia Master',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion adecuada', 'Dormir bien', 'Visualizacion positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 12',
                    'description' => 'Evaluacion final. Superar estos tests demuestra dominio de calistenia elite.',
                    'requirements' => [
                        ['name' => 'Full Planche', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, hombros proyectados maximo, brazos rectos'],
                        ['name' => 'Weighted Pull-up (+50% BW)', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Peso extra en cintura/cadenas, barbilla sobre barra, control total'],
                        ['name' => 'Freestanding Handstand Push-up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Sin pared, cuerpo recto, cabeza toca suelo, extension completa'],
                        ['name' => 'One-Arm Chin-up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Palma hacia ti, un brazo, barbilla sobre barra, sin rotacion excesiva'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // EXERCISE NOTES
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Wall Push Up' => 'Manten el cuerpo recto, codos a 45 grados del torso. Acerca pies a la pared para mas dificultad.',
        'Knee Push Up' => 'Rodillas en suelo, cuerpo recto desde hombros a rodillas. Pecho toca suelo.',
        'Incline Push Up' => 'Manos elevadas. Cuanto mas baja la inclinacion, mas dificil.',
        'High Plank' => 'Cuerpo recto, codos debajo hombros. Activa gluteos y core.',
        'Australian Pull Up' => 'Barra a altura de cadera. Pecho a la barra, cuerpo recto.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 3-5 segundos controlado.',
        'Active Hang' => 'Omoplatos activados, hombros lejos de orejas. No balancees.',
        'Dead Hang' => 'Relaja hombros, deja que el peso estire hombros y espalda.',
        'Air Squat' => 'Talones en suelo, caderas abajo de rodillas, pecho erguido.',
        'Box Squat' => 'Sientate en caja/banco, manten core apretado, levantate sin balanceo.',
        'Calf Raise on Step' => 'Baja el talon por debajo del escalon para estiramiento completo.',
        'Glute Bridge' => 'Empuja con talones, aprieta gluteos arriba. No arquees lumbar.',
        'Dead Bug' => 'Lumbar pegado al suelo. Baja brazo y pierna opuestos lentamente.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Levanta hombros y piernas al mismo tiempo.',
        'Cat Cow' => 'Movimiento lento y controlado. Inhala arqueando, exhala redondeando.',
        'Scapular Push Up' => 'Sin flexionar codos, solo mueve omoplatos hacia adentro y afuera. Core apretado.',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Standard Push Up' => 'Cuerpo recto, pecho al suelo, extension completa. Codos a 45 grados.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados al cuerpo. Triceps trabajan mas.',
        'Wall Handstand Hold' => 'Manos a 20-30cm de pared. Hombros abiertos, mira entre manos.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared. Manten cuerpo recto.',
        'Standard Pull Up' => 'Palmas hacia afuera, barbilla sobre barra, cuerpo recto.',
        'Scapular Pull Up' => 'Sin flexionar codos, solo activa omoplatos. Junta omoplatos abajo.',
        'Dumbbell Lunge' => 'Paso largo, rodilla trasera casi toca suelo. Torso erguido.',
        'Side Plank' => 'Codo debajo hombro, cuerpo recto. Activa oblicuos.',
        'Plank' => 'Cuerpo recto, codos debajo hombros. Activa gluteos y core. Sin hundir caderas.',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Chin Up' => 'Palmas hacia ti, barbilla sobre barra. Biceps trabajan mas que en dominada.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Cabeza toca suelo entre manos.',
        'Band Assisted Pull Up' => 'Usa banda elastica en barra. Pie en banda para asistencia.',
        'Bulgarian Split Squat' => 'Pie trasero en banco. Baja controlado, rodilla delantera estable.',
        'Tuck L-Sit' => 'Manos en suelo o soportes. Rodillas al pecho, espalda recta.',
        'Leg Raise' => 'Colgado de barra, levanta piernas rectas hasta 90 grados. Sin balanceo.',
        'Single Leg Glute Bridge' => 'Un pie en suelo, otro extendido. Empuja con talon.',
        'Wide Push Up' => 'Manos mas anchas que hombros. Enfocate en pecho. Codos a 45 grados.',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        // Push
        'Wall Push Up' => 'Manten el cuerpo recto, codos a 45 grados del torso. Acerca pies a la pared para mas dificultad.',
        'Knee Push Up' => 'Rodillas en suelo, cuerpo recto desde hombros a rodillas. Pecho toca suelo.',
        'Incline Push Up' => 'Manos elevadas. Cuanto mas baja la inclinacion, mas dificil.',
        'Standard Push Up' => 'Cuerpo recto, pecho al suelo, extension completa. Codos a 45 grados del torso.',
        'Wide Push Up' => 'Manos mas anchas que hombros. Enfocate en pecho. Codos a 45 grados.',
        'Decline Push Up' => 'Pies elevados. Mas inclinacion = mas dificultad.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados al cuerpo. Triceps trabajan mas.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Cabeza toca suelo entre manos.',
        'Pseudo Planche Push Up' => 'Manos en cintura. Cuerpo inclinado adelante. Codos pegados al bajar.',
        'Archer Push Up' => 'Un brazo estirado lateral, el otro flexiona. Alterna.',
        'Archer Push Up - Wide' => 'Apertura amplia. Un brazo recto lateral, el otro flexiona. Alterna.',
        'Deficit Handstand Push Up' => 'Manos en plataformas. Cabeza baja de nivel de manos. Empuje completo.',
        'Handstand Push Up' => 'Contra pared o libre. Cabeza toca suelo. Extension completa.',
        'Freestanding Handstand Push Up' => 'Sin pared. Equilibrio + fuerza. Caidas controladas.',
        'One-Arm Handstand Push Up' => 'Una mano en suelo. Equilibrio extremo. Empieza contra pared. Baja controlado.',
        'One Arm Handstand Push Up' => 'Una mano en suelo. Equilibrio extremo. Empieza contra pared. Baja controlado.',
        '90 Degree Push Up' => 'Desde planche, empuje vertical. Uno de los movimientos mas dificiles. Practica la transicion desde planche. Control total.',
        '90 Degree Hold to Chin Up' => 'Desde 90 degree hold, tira hasta chin up. Fuerza extrema.',
        'Planche Push Up' => 'Manten posicion de planche mientras empujas. No dejes caer cadera.',
        'Maltese Push Up' => 'Empuja hacia abajo y afuera. Base del iron cross.',
        'One-Arm Push Up' => 'Un brazo, cuerpo recto. Empieza con pies anchos o asistencia.',

        // Pull
        'Australian Pull Up' => 'Barra a altura de cadera. Pecho a la barra, cuerpo recto.',
        'Negative Pull Up' => 'Sube de cualquier forma, baja en 3-5 segundos controlado.',
        'Standard Pull Up' => 'Palmas hacia afuera, barbilla sobre barra, cuerpo recto.',
        'Chin Up' => 'Palmas hacia ti, barbilla sobre barra. Biceps trabajan mas que en dominada.',
        'Band Assisted Pull Up' => 'Usa banda elastica en barra. Pie en banda para asistencia.',
        'Assisted Pull Up' => 'Usa banda o maquina de asistencia. Enfocate en la tecnica de dominada completa.',
        'Scapular Pull Up' => 'Sin flexionar codos, solo activa omoplatos. Junta omoplatos abajo.',
        'Wide Grip Pull Up' => 'Agarre amplio. Enfocate en la espalda, no en biceps.',
        'Archer Pull Up' => 'Un brazo estirado lateral, el otro tira. Alterna.',
        'Archer Pull Up - Wide' => 'Apertura amplia. Un brazo recto, el otro tira con fuerza.',
        'Archer Chin Up' => 'Palma hacia ti, un brazo estirado lateral. Alterna.',
        'Muscle Up Negative' => 'Sube de cualquier forma, baja controlado por la transicion.',
        'Muscle Up Negativa' => 'Sube de cualquier forma, baja controlado por la transicion.',
        'Muscle Up Progression' => 'Usa banda o asistencia. Enfocate en la transicion.',
        'Muscle Up' => 'Dominada a fondo + fondo completo. La transicion es el punto critico.',
        'Weighted Chin Up' => 'Peso extra en cintura o mochila. Aumenta peso gradualmente. Prioriza tecnica sobre peso.',
        'One Arm Pull Up' => 'Un brazo. Empieza con negativas y asistencia.',
        'One-Arm Pull Up' => 'Un brazo, bajada controlada. Minimo 5 segundos.',
        'One Arm Chin Up' => 'Palma hacia ti, un brazo. Mas dificil que OAP.',
        'One-Arm Chin Up' => 'Palma hacia ti, un brazo. Mas dificil que OAP.',
        'One Arm Chin Up Negative' => 'Palma hacia ti, bajada controlada. Minimo 5 segundos.',
        'One-Arm Chin Up Negative' => 'Palma hacia ti, bajada controlada. Minimo 5 segundos.',

        // Front Lever / Back Lever
        'Tuck Front Lever' => 'Rodillas al pecho. Espalda horizontal. Junta omoplatos. Brazos rectos.',
        'Tuck Front Lever Hold' => 'Rodillas al pecho. Espalda horizontal. Junta omoplatos.',
        'Tuck Front Lever Row' => 'Desde tuck FL, tira barra al pecho bajo. Omoplatos juntos.',
        'Advanced Tuck Front Lever' => 'Rodillas separadas 90 grados. Espalda horizontal. Brazos rectos.',
        'Advanced Tuck Front Lever Hold' => 'Rodillas separadas 90 grados. Espalda horizontal. Brazos rectos.',
        'Advanced Tuck Front Lever Row' => 'Desde advanced tuck FL, tira barra al pecho. Omoplatos juntos.',
        'Full Front Lever' => 'Cuerpo recto horizontal. Omoplatos juntos. Brazos rectos.',
        'Full Front Lever Hold' => 'Cuerpo recto horizontal. Omoplatos juntos. Brazos rectos.',
        'Full Front Lever Row' => 'Desde full FL, tira barra al pecho bajo. Omoplatos juntos.',
        'Front Lever Raise' => 'Desde dead hang, eleva cuerpo hasta front lever. Control total.',
        'Back Lever' => 'Cuerpo horizontal boca abajo. Brazos rectos. Hombros abiertos. Baja controlado.',
        'Back Lever Hold' => 'Cuerpo horizontal boca abajo. Brazos rectos. Hombros abiertos.',
        'Back Lever Raise' => 'Desde tuck, extiende piernas hacia back lever. Control total.',
        'Back Lever Row' => 'Desde back lever, tira barra al pecho. Control total.',

        // Planche / Handstand
        'Planche Lean' => 'Inclinate hacia adelante todo lo posible sin doblar codos. Proyeccion maxima.',
        'Tuck Planche' => 'Rodillas al pecho. Empuja el suelo con fuerza. Brazos rectos siempre.',
        'Advanced Tuck Planche' => 'Rodillas separadas 90 grados. Espalda plana. No arquees.',
        'Straddle Planche' => 'Piernas abiertas. Espalda plana. Brazos rectos. Proyeccion maxima.',
        'Full Planche' => 'Cuerpo recto horizontal. Proyeccion maxima. Brazos rectos.',
        'Planche' => 'Cuerpo recto horizontal. Proyeccion maxima. Brazos rectos.',
        'Frog Stand' => 'Rodillas apoyadas en codos. Manten equilibrio, espalda recta.',
        'Wall Plank' => 'Pies en pared, plancha inclinada. Cuerpo recto, core apretado.',
        'Wall Handstand Hold' => 'Manos a 20-30cm de pared. Hombros abiertos, mira entre manos.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared. Manten cuerpo recto.',
        'Freestanding Handstand' => 'Sin pared. Equilibrio activo con dedos. Hombros abiertos.',
        'Freestanding Handstand Hold' => 'Sin pared. Caidas controladas. Respira tranquilo.',
        'Human Flag' => 'Cuerpo horizontal lateral. Empuja con brazo de abajo, tira con el de arriba. Piernas tuck o straddle. Enfocate en la posicion.',

        // Legs
        'Air Squat' => 'Talones en suelo, caderas abajo de rodillas, pecho erguido. Control total.',
        'Box Squat' => 'Sientate en caja/banco, manten core apretado, levantate sin balanceo.',
        'Jump Squat' => 'Salta explosivo desde sentadilla. Aterriza suave.',
        'Bulgarian Split Squat' => 'Pie trasero en banco. Baja controlado, rodilla delantera estable.',
        'Dumbbell Lunge' => 'Paso largo, rodilla trasera casi toca suelo. Torso erguido.',
        'Assisted Pistol Squat' => 'Usa agarre o banda. Baja controlado, no saltes.',
        'Box Pistol Squat' => 'Pierna atras en caja/banco. Baja controlado, pie completo en suelo.',
        'Pistol Squat' => 'Una pierna. Cadera abajo de rodilla. Pie completo en suelo.',
        'Dragon Pistol Squat' => 'Pierna trasera en dragon. Baja controlado, rodilla estable.',
        'Shrimp Squat' => 'Pierna trasera agarrada, baja profundo. Equilibrio y fuerza de pierna.',
        'Cossack Squat' => 'Pierna recta lateral, otra en sentadilla profunda. Movilidad de cadera.',
        'Deep Squat Hold' => 'Sentadilla profunda mantenida. Espalda recta, talones suelo.',

        // Posterior chain
        'Glute Bridge' => 'Empuja con talones, aprieta gluteos arriba. No arquees lumbar.',
        'Single Leg Glute Bridge' => 'Un pie en suelo, otro extendido. Empuja con talon.',
        'Calf Raise on Step' => 'Baja el talon por debajo del escalon para estiramiento completo.',
        'Nordic Curl Negative' => 'Anca en suelo, baja controlado. Minimo 5 segundos.',
        'Nordic Curl with Band' => 'Usa banda o manos. Controla la bajada. Intenta subir con control.',
        'Nordic Curl' => 'Sin asistencia. Bajada y subida controladas.',

        // Core
        'High Plank' => 'Cuerpo recto, codos debajo hombros. Activa gluteos y core.',
        'Plank' => 'Cuerpo recto, codos debajo hombros. Activa gluteos y core. Sin hundir caderas.',
        'Side Plank' => 'Codo debajo hombro, cuerpo recto. Activa oblicuos.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Levanta hombros y piernas al mismo tiempo.',
        'Hollow Body Rock' => 'Desde hollow body, balancea adelante y atras sin tocar suelo.',
        'Hollow Body Press' => 'Desde hollow body, empuja manos hacia arriba. Core extremo.',
        'Dead Bug' => 'Lumbar pegado al suelo. Baja brazo y pierna opuestos lentamente.',
        'Leg Raise' => 'Colgado de barra, levanta piernas rectas hasta 90 grados. Sin balanceo.',
        'Tuck L-Sit' => 'Manos en suelo o soportes. Rodillas al pecho, espalda recta.',
        'L-Sit' => 'Piernas rectas, elevadas del suelo. Espalda recta, hombros bajados.',
        'L-Sit One Leg' => 'Una pierna recta, otra en tuck. Transicion hacia L-sit completo.',
        'Dragon Flag' => 'Cuerpo recto desde hombros a pies. Baja en 5 segundos.',
        'Dragon Flag Negative' => 'Cuerpo recto, baja controlado. Minimo 5 segundos.',
        'Active Hang' => 'Omoplatos activados, hombros lejos de orejas. No balancees.',
        'Dead Hang' => 'Relaja hombros, deja que el peso estire hombros y espalda.',
        'Cat Cow' => 'Movimiento lento y controlado. Inhala arqueando, exhala redondeando.',
        'Scapular Push Up' => 'Sin flexionar codos, solo mueve omoplatos hacia adentro y afuera. Core apretado.',

        // Other
        'Assisted Dips' => 'Usa banda o banco. Baja hasta 90 grados de codo.',
        'deficit HSPU' => 'Manos en plataformas. Cabeza baja de nivel de manos. Empuje completo.',
    ];
}
