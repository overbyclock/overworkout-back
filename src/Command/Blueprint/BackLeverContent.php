<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Back Lever Mastery.
 * Especializacion del Master v4: consejos de extension de hombro,
 * German hang, tuck, advanced tuck, straddle y full back lever,
 * y reglas tendinosas §1.7 aplicadas al hombro.
 * Estructura: 8 niveles, 4 fases (Base -> Progresion -> Intensificacion -> Recuperacion).
 */
class BackLeverContent
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
    // LEVEL 1: Base de Extension de Hombro
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La extension de hombro se construye con German hang y skin the cat suaves. Nunca fuerces el fondo del rango.',
                'Fase 2 (Progresion): Aumenta el tiempo de German hang y las repeticiones de skin the cat manteniendo la respiracion continua.',
                'Fase 3 (Intensificacion): Maximo volumen de extension. Baja antes de que el hombro se fatigue del todo: caer del control es la lesion tipica.',
                'Fase 4 (Recuperacion): Descarga de hombros y pilar. German hang corto y movilidad suave.',
                'El hollow body hold y el arch hold son la base de la linea en back lever: manten la lumbar controlada en ambos.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro = congela ese gesto un ciclo.',
                'Dolor articular ≠ molestia muscular: cualquier dolor punzante en el hombro detiene el ejercicio y se retrocede un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Extension de hombro y pilar', 'note' => 'German hang 10-15 s, skin the cat 2-3, hollow/arch 15-20 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar rango y tiempo', 'note' => 'German hang 15-20 s, shoulder dislocate 10, face pull 12-15', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen maximo', 'note' => 'German hang 20-25 s, skin the cat 4-5, active hang 20-30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'German Hang', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Desde skin the cat, hombro relajado, brazos extendidos, sin dolor ni pinzamiento'],
                        ['name' => 'Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                        ['name' => 'Skin The Cat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Rango controlado, hombro relajado en la extension'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Primer Tuck
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El tuck back lever empieza desde skin the cat: entra controlado y manten la espalda redonda y las rodillas al pecho.',
                'Fase 2 (Progresion): Aumenta el tiempo de hold sin perder la posicion de las escapulas. Si se abren los hombros, termina la serie.',
                'Fase 3 (Intensificacion): 8-10 s en tuck. Un solo pico de hombro por fase.',
                'Fase 4 (Recuperacion): Vuelve a German hang y skin the cat suaves; el hombro descarga.',
                'Los holds de back lever van siempre tras German hang: nunca en frio.',
                'Si el tiempo aprieta se recorta volumen (series), nunca el descanso entre series de back lever.',
                '⚠️ Dolor punzante en el hombro al entrar al tuck = hombro no preparado: vuelve al Nivel 1 un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer tuck back lever', 'note' => 'Tuck back lever 5-8 s, German hang 15-20 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar tiempo en tuck', 'note' => 'Tuck back lever 6-10 s, dead hang 30-45 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen', 'note' => 'Tuck back lever 8-10 s, German hang 25-30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Tuck Back Lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Espalda redonda, rodillas al pecho, brazos rectos, hombros bajos'],
                        ['name' => 'German Hang', 'minimum' => 25, 'target' => 35, 'unit' => 'segundos', 'form' => 'Hombro relajado, respiracion continua, sin dolor'],
                        ['name' => 'Dead Hang', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Agarre completo, brazos extendidos, sin balanceo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Tuck Consolidado
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Tuck 8-10 s comodo antes de añadir back lever rows. La fila enseña a remar sin perder la extension del hombro.',
                'Fase 2 (Progresion): En la back lever row, manten la linea del cuerpo paralela al suelo y los hombros lejos de las orejas.',
                'Fase 3 (Intensificacion): Tuck 10-12 s + rows 8 reps + German hang 30 s. Un solo pico de hombro por fase.',
                'Fase 4 (Recuperacion): Regresion activa: tuck corto y rows sin fatiga.',
                'El German hang sigue siendo el seguro de vida del hombro: nunca lo saltes.',
                'Alterna anillas y barra si tienes ambas: las anillas son mas inestables y exigen mas control de extension.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tuck 8-10 s y primeras rows', 'note' => 'Tuck back lever 8-10 s, back lever row 5-6', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Back lever rows', 'note' => 'Back lever row 6-8, German hang 25-30 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen', 'note' => 'Tuck back lever 10-12 s, back lever row 8', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Tuck Back Lever', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Linea estable, rodillas al pecho, brazos rectos'],
                        ['name' => 'Back Lever Row', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Cuerpo paralelo al suelo, pecho a las anillas/barra'],
                        ['name' => 'German Hang', 'minimum' => 30, 'target' => 40, 'unit' => 'segundos', 'form' => 'Hombro relajado, extension completa sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Advanced Tuck
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El advanced tuck abre la cadera manteniendo la espalda redonda: siente el cambio de palanca en los hombros.',
                'Fase 2 (Progresion): Manten el tuck solido por encima de 10 s mientras el advanced tuck crece.',
                'Fase 3 (Intensificacion): Advanced tuck 6-8 s + tuck 10-12 s + rows 8-10. El pico de hombro vive en la sesion B.',
                'Fase 4 (Recuperacion): German hang largo y movilidad: salud de hombro para el nivel de straddle que viene.',
                'Si el advanced tuck se resiste, no fuerces series al fallo: acumula volumen con series de 4-5 s y descanso completo.',
                'La progresion del advanced tuck es mental: el cuerpo quiere abrirse, tu manten la tension del pilar.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Advanced tuck 4-6 s', 'note' => 'Advanced tuck back lever 4-6 s, tuck back lever 8-10 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar advanced tuck', 'note' => 'Advanced tuck back lever 5-8 s, back lever row 8', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen', 'note' => 'Advanced tuck back lever 6-8 s, back lever row 8-10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'German hang suave, movilidad de hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Back Lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cadera abierta, espalda redonda, brazos rectos'],
                        ['name' => 'Tuck Back Lever', 'minimum' => 10, 'target' => 12, 'unit' => 'segundos', 'form' => 'Linea estable, hombros lejos de las orejas'],
                        ['name' => 'Back Lever Row', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Cuerpo paralelo al suelo, rango completo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Straddle Entrada
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El straddle back lever exige movilidad de apertura y hombros calientes. Entra por skin the cat y abre lentamente.',
                'Fase 2 (Progresion): Manten el advanced tuck como referencia mientras el straddle gana tiempo.',
                'Fase 3 (Intensificacion): Straddle 6-8 s + advanced tuck 6-8 s + rows 8-10. Un solo pico de hombro por fase.',
                'Fase 4 (Recuperacion): German hang largo y movilidad de apertura: el hombro agradece la descarga.',
                'La asistencia con banda en straddle se explica en el objetivo del ejercicio: apoya la cadera, no los pies.',
                'El German hang de 30-40 s es mantenimiento: no lo conviertas en un segundo pico.',
                '⚠️ Dolor anterior de hombro o pinzamiento al abrir el straddle = reduce la apertura o usa banda un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer straddle back lever', 'note' => 'Straddle back lever 4-6 s, advanced tuck 5-8 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Straddle estable', 'note' => 'Straddle back lever 4-6 s, back lever row 8-10', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen: straddle 6-8 s', 'note' => 'Straddle back lever 6-8 s, German hang 40-45 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'German hang largo, movilidad de apertura', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Straddle Back Lever', 'minimum' => 4, 'target' => 6, 'unit' => 'segundos', 'form' => 'Apertura controlada, brazos rectos, espalda plana'],
                        ['name' => 'Advanced Tuck Back Lever', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cadera abierta, linea estable'],
                        ['name' => 'German Hang', 'minimum' => 40, 'target' => 50, 'unit' => 'segundos', 'form' => 'Hombro relajado, respiracion continua'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Straddle Solido
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Straddle 6-8 s estable y back lever rows fuertes. Primer contacto con full: solo excéntrica controlada.',
                'Fase 2 (Progresion): Full back lever 5-8 s asistido con banda a la cadera; straddle sigue siendo el pilar.',
                'Fase 3 (Intensificacion): Straddle 8-10 s + rows 10-12 + full asistido. El pico de hombro sigue en el straddle.',
                'Fase 4 (Recuperacion): Volumen minimo; la excéntrica desde full se practica sin carga completa.',
                'Las excéntricas desde full nunca se hacen al fallo: baja con control y conserva el rango.',
                'Si el straddle se estanca, revisa la movilidad de apertura: 5 min de pancake pueden cambiar mas que forzar.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Straddle 6-8 s y rows fuertes', 'note' => 'Straddle back lever 6-8 s, back lever row 8-10', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Contacto con full asistido', 'note' => 'Back lever hold 5-8 s asistido, straddle 6-8 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen: straddle 8-10 s', 'note' => 'Straddle back lever 8-10 s, back lever row 10-12', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Excéntrica desde full sin carga, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Straddle Back Lever', 'minimum' => 6, 'target' => 8, 'unit' => 'segundos', 'form' => 'Apertura completa, linea plana, brazos rectos'],
                        ['name' => 'Back Lever Row', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Cuerpo paralelo, pecho a la barra/anillas'],
                        ['name' => 'German Hang', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Hombro relajado, extension profunda sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Full Back Lever
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Full back lever 3-5 s limpio. Antes, German hang 45-50 s para calentar la extension.',
                'Fase 2 (Progresion): Full asistido con banda a la cadera 6-8 s tras los libres. La banda no es trampa: es volumen seguro.',
                'Fase 3 (Intensificacion): Full 3-5 s×4 + asistido 6-8 s×2 + straddle 8-10 s. El pico de hombro maximo del programa.',
                'Fase 4 (Recuperacion): Patron tecnico sin carga completa; movilidad larga.',
                'El full asistido con banda a la cadera se explica en el objetivo del ejercicio: ajusta la tension para mantener la linea.',
                'Mantén el straddle 8-10 s como referencia: si se degrada, retrocede volumen de full.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro = congela el full un ciclo y vuelve a straddle.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Full 3-5 s limpio', 'note' => 'Back lever hold 3-5 s, straddle 6-8 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Full asistido 6-8 s', 'note' => 'Back lever hold 4-6 s + 6-8 s asistido, back lever row 10-12', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen de full', 'note' => 'Back lever hold 3-5 s×4 + 6-8 s×2, straddle 8-10 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Straddle tecnico sin carga, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Back Lever Hold', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo completamente recto, brazos extendidos, linea horizontal'],
                        ['name' => 'Straddle Back Lever', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Apertura controlada, linea plana'],
                        ['name' => 'Skin The Cat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Rango controlado, hombro relajado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8: Dominio
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Full back lever con volumen real: 6-8 s frescas, descanso completo (3-4 min) y linea impecable.',
                'Fase 2 (Progresion): Full 8-10 s: la segunda serie se gana en la extension del hombro, no en la fuerza.',
                'Fase 3 (Intensificacion): Full 8-10 s×5 + rows 12-15 + German hang 60-75 s. Pico maximo del programa.',
                'Fase 4 (Recuperacion): El bloque C (German hang profundo) es el seguro de vida: mantenlo incluso en deload.',
                'La resistencia profunda de extension de hombro (German hang 60-75 s) es la base del dominio.',
                'Aqui el back lever ya no es el objetivo: es la plataforma. El juego pasa a las combinaciones y añadir carga.',
                '⚠️ Regla tendinosa §1.7: a esta altura del programa el hombro carga mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Full con volumen', 'note' => 'Back lever hold 6-8 s, back lever row 10-12', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Full 8-10 s', 'note' => 'Back lever hold 8-10 s, straddle 6-8 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Pico: full 8-10 s×5', 'note' => 'Back lever hold 8-10 s, back lever row 12-15, German hang 60-75 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Resistencia profunda mantenida, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion final del programa',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Back Lever Hold', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Linea horizontal, brazos rectos, cuerpo rigido'],
                        ['name' => 'Back Lever Row', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Cuerpo paralelo, pecho a la barra/anillas'],
                        ['name' => 'German Hang', 'minimum' => 60, 'target' => 75, 'unit' => 'segundos', 'form' => 'Extension profunda, hombro relajado, sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L5 = [
        'Straddle Back Lever' => 'Asistencia con banda explicada en goal: apoya la banda a la cadera, no a los pies',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Back Lever Hold' => 'Full asistida con banda a la cadera en series de volumen: ajusta la tension para mantener la linea horizontal',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Tuck Back Lever' => 'Espalda redonda, rodillas al pecho, brazos rectos; baja antes de que se abran los hombros',
        'Advanced Tuck Back Lever' => 'Cadera abierta manteniendo la espalda redonda; controla el cambio de palanca',
        'Straddle Back Lever' => 'Apertura controlada, brazos rectos, espalda plana; usa banda si pierdes la linea',
        'Back Lever Hold' => 'Cuerpo completamente recto, brazos extendidos, linea horizontal; baja con control',
        'Back Lever Row' => 'Cuerpo paralelo al suelo; si se rompe la linea, termina la serie',
        'German Hang' => 'Salud de hombro: hombro relajado, respiracion continua, nunca al dolor',
        'Skin The Cat' => 'Rango controlado; no dejes caer el peso sobre la extension del hombro',
        'Shoulder Dislocate' => 'Movilidad activa sin dolor; aumenta el ancho de agarre progresivamente',
        'Dead Hang' => 'Agarre completo con pulgar cerrado; baja con control antes de que el agarre falle',
        'Active Hang' => 'Escapulas activas y controladas; sin balanceo',
        'Face Pull' => 'Codos altos, rotacion externa; controla el retorno',
        'Hollow Body Hold' => 'Lumbar pegada al suelo, costillas abajo, hombros elevados',
        'Arch Hold' => 'Gluteos y espalda activos; mira hacia el suelo',
        'Hanging Leg Raise' => 'Sin balanceo; eleva las piernas con control desde el pilar',
        'Tuck L-Sit' => 'Escapulas deprimidas, rodillas al pecho; manten los hombros lejos de las orejas',
    ];
}
