<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa False Grip Mastery.
 * Especialización del Master v4: consejos de muñeca y falso agarre de los
 * detalles de N1-N2 (muñeca), N8-N11 (falso agarre) y N15-N16 (profundo),
 * y reglas tendinosas §1.7 aplicadas a muñeca y codo.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class FalseGripContent
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

    public static function getExerciseNote(int $levelNumber, string $exerciseName): ?string
    {
        $specific = match ($levelNumber) {
            2 => self::EXERCISE_NOTES_L2,
            5 => self::EXERCISE_NOTES_L5,
            7 => self::EXERCISE_NOTES_L7,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Muñeca y Agarre
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La muñeca se construye por ciclos, nunca al fallo. Duele = retrocede a oraciones.',
                'Fase 2 (Progresión): Aumenta reps en wrist push-ups de rodillas y tiempo de colgado. El agarre se construye colgándose, no apretando.',
                'Fase 3 (Intensificación): Máximo volumen de muñeca y colgados. Baja siempre antes de que el agarre falle del todo: caer de la barra es la lesión típica.',
                'Fase 4 (Recuperación): Descarga de muñecas y agarre. Oraciones suaves y colgados cortos.',
                'Los ciclos se repiten hasta superar el test. Sin prisa: la muñeca madura lento y el falso agarre castiga las prisas.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o codo = congela ese gesto un ciclo.',
                'Dolor articular ≠ molestia muscular: cualquier dolor punzante en muñeca detiene el ejercicio y se retrocede un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Muñeca y agarre de base', 'note' => 'Oraciones 2×20-30 s, wrist push-ups de rodillas 2×6, dead hang 10-15 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar rango y tiempo', 'note' => 'Dead hang 15-20 s, knee wrist push-up 2×8', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Dead hang 20-30 s, wrist curl 15, fingertip hold 15-20 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Dead Hang', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Agarre completo con pulgar cerrado, brazos extendidos, sin balanceo; baja antes del fallo'],
                        ['name' => 'Standard Push Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, cuerpo en línea, muñecas cómodas en extensión'],
                        ['name' => 'Knee Wrist Push-Up', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Apoyo sobre el dorso de las manos, rango sin dolor en muñeca'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Introducción al Falso Agarre
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El falso agarre es agarre de "muñeca": el anillo (o la barra) apoya sobre la muñeca/antebrazo, no en los dedos.',
                'Fase 2 (Progresión): Dedos cerrados fuertes sobre el anillo y codo apuntando al suelo. Si el anillo "corta" la muñeca, revisa la colocación antes de añadir tiempo.',
                'Fase 3 (Intensificación): Hang 10-12 s y support 10 s. Nunca falso agarre en frío: muñeca caliente (oraciones) antes de cada serie.',
                'Fase 4 (Recuperación): Vuelve a colgados normales y wrist push-ups suaves.',
                'Los hangs y soportes en falso agarre van con descanso completo (2-3 min), nunca en circuito.',
                'Si el tiempo aprieta se recorta volumen (series), nunca el descanso entre series de falso agarre.',
                '⚠️ Dolor punzante en la muñeca al colocar el falso agarre = mal agarre o muñeca no preparada: vuelve al Nivel 1 un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer falso agarre colgado', 'note' => 'False grip hang 5-8 s, wrist push-up completo 2×5', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo', 'note' => 'False grip hang 8-10 s, false grip support 8-10 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'False grip hang 10-12 s, false grip support 10 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'False Grip Hang', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Anilla apoyada en muñeca/antebrazo, dedos cerrados, codo al suelo, sin dolor'],
                        ['name' => 'Wrist Push-Up', 'minimum' => 6, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Flexión completa sobre el dorso de las manos, rango sin dolor'],
                        ['name' => 'Standard Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Falso Agarre Básico
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Hang 10-15 s cómodo antes de añadir filas en falso agarre. La fila enseña a remar sin perder la colocación del agarre.',
                'Fase 2 (Progresión): En la false grip ring row, lleva el pecho a las anillas con la muñeca siempre apoyada: si se "abre" el agarre, termina la serie.',
                'Fase 3 (Intensificación): Hang 15 s + filas 8 reps + support 12 s. Un solo pico de muñeca por fase.',
                'Fase 4 (Recuperación): Regresión activa: hangs cortos y filas sin fatiga.',
                'El support hold en falso agarre prepara el fondo de anillas: anillas pegadas al cuerpo, hombros lejos de las orejas.',
                'Alterna barra y anillas si tienes ambas: la barra es más exigente con la muñeca, las anillas más inestables.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o codo = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Hang 10-15 s y primeras filas', 'note' => 'False grip hang 10-15 s, false grip ring row 5-6', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Filas en falso agarre', 'note' => 'False grip ring row 6-8, ring support 12-15 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'False grip hang 15 s, false grip ring row 8', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'False Grip Hang', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Colocación limpia, sin dolor, brazos extendidos'],
                        ['name' => 'False Grip Ring Row', 'minimum' => 6, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, muñeca apoyada en todo el recorrido'],
                        ['name' => 'False Grip Support Hold', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Anillas al cuerpo, brazos rectos, hombros bajos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Falso Agarre Estable
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El hang de 20 s es el gate que el Master exige en su N10: aquí se consigue de forma aislada.',
                'Fase 2 (Progresión): Las archer ring rows en falso agarre cargan un lado cada vez: simetría estricta, mismo número a cada lado.',
                'Fase 3 (Intensificación): Marca de 20 s en hang + filas 10 + soporte 20 s. El pico de muñeca vive en la sesión B.',
                'Fase 4 (Recuperación): German hang suave y muñeca: salud de codo para el nivel de tracción que viene.',
                'El German hang es el seguro de vida del codo en todo lo que queda de programa: nunca lo saltes.',
                'Si el hang de 20 s se resiste, no fuerces series al fallo: acumula volumen con series de 12-15 s y descanso completo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o codo = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Hang 15-20 s', 'note' => 'False grip hang 15-20 s, false grip ring row 8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Archer rows en falso agarre', 'note' => 'Archer ring row 8/lado, ring support 15-20 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: marca de 20 s', 'note' => 'False grip hang 20 s, false grip support 15-20 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang suave, movilidad de muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5. El hang 20 s es el gate del N10 del Master',
                    'requirements' => [
                        ['name' => 'False Grip Hang', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Gate del Master v4 (N10): colocación limpia, sin dolor'],
                        ['name' => 'False Grip Ring Row', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, agarre profundo estable'],
                        ['name' => 'False Grip Support Hold', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Anillas al cuerpo, hombros lejos de las orejas'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Tracción con Falso Agarre
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Las dominadas asistidas en falso agarre se hacen con puntas de pies en el suelo o banda: el objetivo es el patrón completo, no el ego.',
                'Fase 2 (Progresión): Si no llegas al rango completo, haz la bajada en negativa de 5 s: la excéntrica construye el mismo camino.',
                'Fase 3 (Intensificación): 3 dominadas asistidas + hang 25 s + filas 10-12. Un solo pico de codo por fase (regla §1.7).',
                'Fase 4 (Recuperación): German hang largo y muñeca: el codo agradece la descarga.',
                'No "arranques" la dominada en falso agarre desde el tendón: si el agarre se abre en la subida, la serie ha terminado.',
                'El hang de 25 s es mantenimiento: no lo conviertas en un segundo pico.',
                '⚠️ Dolor de codo anterior (epicondilo) es la señal de alarma del falso agarre mal dosificado: reduce volumen y refuerza extensores (reverse wrist curl).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Negativas y asistidas', 'note' => 'False grip pull up asistida 1-2, false grip ring row 8-10', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar asistencia mínima', 'note' => 'False grip pull up 2-3, hang 22-25 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'False grip pull up 3, hang 25 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang largo, muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'False Grip Hang', 'minimum' => 25, 'target' => 30, 'unit' => 'segundos', 'form' => 'Colocación profunda estable, sin dolor'],
                        ['name' => 'False Grip Ring Row', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, agarre profundo'],
                        ['name' => 'German Hang', 'minimum' => 30, 'target' => 40, 'unit' => 'segundos', 'form' => 'Desde skin the cat, hombro relajado, respiración continua'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Primer False Grip Pull-Up
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Primera dominada estricta en falso agarre: empieza la sesión B fresca, es el objetivo del nivel.',
                'Fase 2 (Progresión): Hang 25-30 s: el objetivo de 30 s es el target del gate del Master en su N10.',
                'Fase 3 (Intensificación): 5-6 dominadas + hang 30 s + archer rows. El pico de muñeca sigue viviendo en el hang, no en las dominadas.',
                'Fase 4 (Recuperación): Volumen mínimo; la transición de ring MU se practica sin carga completa.',
                'La transición de ring MU sobre falso agarre profundo es el puente al bloque de anillas: dedos cerrados, anillas pegadas a los antebrazos.',
                'Si la dominada en falso agarre se estanca, revisa el peso: 1-2 kg menos de grasa o un peldaño de asistencia cambian más que forzar.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o codo = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primeras estrictas', 'note' => 'False grip pull up 3-5, hang 25 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'False grip pull up 4-6, hang 25-30 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 5-6 dominadas', 'note' => 'False grip pull up 5-6, hang 30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Transición de ring MU sin carga, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'False Grip Pull Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Estricta, rango completo, sin asistencia ni impulso'],
                        ['name' => 'False Grip Hang', 'minimum' => 30, 'target' => 40, 'unit' => 'segundos', 'form' => 'Target del gate del Master v4 (N10)'],
                        ['name' => 'False Grip Support Hold', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Anillas al cuerpo, brazos rectos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Volumen y Anillas
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Las ring pull up en falso agarre son la transferencia definitiva: la anilla inestable exige un agarre aún más profundo.',
                'Fase 2 (Progresión): La ring MU asistida se practica con puntas en cajón o banda, asistencia mínima y falso agarre profundo revisado en cada serie.',
                'Fase 3 (Intensificación): El intento libre de ring MU va siempre al principio de la sesión D: un intento de MU tras fondos o holds es un intento muerto.',
                'Fase 4 (Recuperación): Patrón técnico sin carga completa; movilidad larga.',
                'La dominada en falso agarre en anillas suele ser más dura que en barra: no te frustres si bajan las reps.',
                'Mantén el hang de 30 s como referencia: si se degrada, retrocede volumen de anillas.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o codo = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Ring pull up en falso agarre', 'note' => 'Ring pull up - false grip 5-6, ring MU transition 3-5', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Ring MU asistida', 'note' => 'Ring muscle up 1-2 asistida, ring pull up FG 6-8', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen y primeros intentos libres', 'note' => 'Ring muscle up 2-3, false grip pull up 8-10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Transición sin carga, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Ring Pull Up - False Grip', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Agarre profundo estable, pecho a las anillas'],
                        ['name' => 'False Grip Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Estricta en barra, rango completo'],
                        ['name' => 'False Grip Hang', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Colocación profunda, respiración continua'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8: Dominio del Falso Agarre
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Ring MU con volumen real: 1-2 frescas, descanso completo (3-4 min) y técnica de transición impecable.',
                'Fase 2 (Progresión): Dobles en anillas: la segunda rep se gana en el agarre, no en el empuje.',
                'Fase 3 (Intensificación): 3-5 ring MU + 8-10 dominadas en falso agarre + support 20-25 s. Pico máximo del programa.',
                'Fase 4 (Recuperación): El bloque C (resistencia profunda) es el seguro de vida: mantenlo incluso en deload.',
                'La resistencia profunda de falso agarre (hang 30-35 s, support 20-25 s) viene de las dosis del N15 del Master: es la base del dominio.',
                'Aquí el falso agarre ya no es el objetivo: es la plataforma. El juego pasa a la muscle-up en anillas y sus variedades.',
                '⚠️ Regla tendinosa §1.7: a esta altura del programa la muñeca carga mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Ring MU con volumen', 'note' => 'Ring muscle up 1-2, ring pull up FG 8-10', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Dobles en anillas', 'note' => 'Ring muscle up 2-3, false grip pull up 10-12', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: 3-5 ring MU', 'note' => 'Ring muscle up 3-5, support 20-25 s, hang 30-35 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Resistencia profunda mantenida, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Ring Muscle Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Falso agarre profundo, transición limpia, sin kip'],
                        ['name' => 'False Grip Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Estrictas en barra, rango completo'],
                        ['name' => 'False Grip Hang', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Colocación profunda estable, sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L2 = [
        'False Grip Hang' => 'Anilla apoyada en la muñeca/antebrazo (no solo en los dedos); dedos cerrados fuertes; codo apuntando al suelo',
        'False Grip Support Hold' => 'Anillas pegadas al cuerpo, muñeca apoyada en el anillo; baja antes de que el agarre se abra',
    ];

    private const EXERCISE_NOTES_L5 = [
        'False Grip Pull Up' => 'Si no llegas al rango completo: negativa de 5 s o asistencia de puntas/banda',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Ring Muscle Up' => 'Asistida con puntas en cajón o banda: asistencia mínima y falso agarre profundo revisado en cada serie',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Dead Hang' => 'Agarre completo con pulgar cerrado; baja con control antes de que el agarre falle del todo',
        'False Grip Hang' => 'Colocación limpia antes de cada serie: anilla en la muñeca, dedos cerrados, sin dolor',
        'False Grip Support Hold' => 'Baja antes de que el agarre se abra: el support se gana con series limpias',
        'False Grip Ring Row' => 'Pecho a las anillas; si la muñeca se separa del anillo, termina la serie',
        'False Grip Pull Up' => 'Sin "arranque" desde el tendón: si el agarre se abre, la serie ha terminado',
        'Ring Pull Up - False Grip' => 'Agarre aún más profundo que en barra: la anilla inestable castiga la colocación floja',
        'Ring Muscle Up' => 'El intento libre va siempre al principio de la sesión, en fresco',
        'Wrist Prayer' => 'Movilidad activa sin dolor: mece suave hacia extensión',
        'Wrist Curl' => 'Rango completo suave; nunca al fallo',
        'Reverse Wrist Curl' => 'El seguro del codo: extensores fuertes equilibran el falso agarre',
        'Knee Wrist Push-Up' => 'Rango cómodo y creciente; duele = retrocede a oraciones',
        'Wrist Push-Up' => 'Extensión de muñeca bajo carga progresiva; rango sin dolor',
        'German Hang' => 'Salud de codo y hombro: hombro relajado, respiración continua',
    ];
}
