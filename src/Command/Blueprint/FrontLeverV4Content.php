<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Front Lever Mastery v4.
 * Especialización del Master v4: progresión de front lever de los detalles
 * de N6 (tuck) a N12 (full), con reglas tendinosas §1.7 aplicadas a
 * codo y hombro.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class FrontLeverV4Content
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
            7 => self::EXERCISE_NOTES_L7,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Tracción y Escápulas
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La base del front lever es la dominada estricta con volumen. Sin 8 dominadas limpias, el tuck será un sufrimiento inútil.',
                'Fase 2 (Progresión): Aumenta reps de dominadas y filas. Las escápulas activas en colgado son el interruptor del front lever.',
                'Fase 3 (Intensificación): Máximo volumen de base. El core hollow/arch sostiene la línea: entrena la rigidez, no solo la fuerza.',
                'Fase 4 (Recuperación): Descarga de tracción. Movilidad de hombro y German hang suave.',
                'Los holds de front lever se construyen con descanso completo entre series (2-3 min), nunca en circuito.',
                'Si el tiempo aprieta se recorta volumen (series), nunca el descanso entre series de front lever.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tracción estricta y escápulas', 'note' => 'Dominadas 3×6-8, scapular pulls 2×6, hollow 15-20 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar reps', 'note' => 'Dominadas 3×8-10, filas elevadas 3×10-12', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Dominadas 4×8-10, active hang 30-40 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                        ['name' => 'Scapular Pull Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Brazos extendidos, depresión escapular clara, sin flexión de codo'],
                        ['name' => 'Weighted Ring Row', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, cuerpo en línea, carga controlada'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Tuck Front Lever
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El tuck con banda enseña la posición: cadera flexionada, escápulas activas, codo recto. La banda no es trampolín, es maestra.',
                'Fase 2 (Progresión): Reduce la asistencia de la banda antes de añadir tiempo. Un tuck de 8 s sin banda vale más que 15 s con banda gruesa.',
                'Fase 3 (Intensificación): 5-8 s en tuck (dosis N6 del Master). Un solo pico de codo por fase (regla §1.7).',
                'Fase 4 (Recuperación): German hang y movilidad: el codo agradece la descarga.',
                'La cadera se plega completa: rodillas al pecho. La tentación de abrir el tuck antes de tiempo es la causa nº1 de estancamiento.',
                'Los holds van con descanso completo (2-3 min). Si el tiempo aprieta, quita series, no descanso.',
                '⚠️ Dolor punzante en el codo (epicondilo) es la señal de alarma: reduce volumen y refuerza extensores (reverse wrist curl).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tuck con banda 5-8 s', 'note' => 'Tuck FL con banda 3×5-8 s, skin the cat 2×2-3', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Reducir asistencia', 'note' => 'Tuck FL 3×8 s, tuck hold 8-10 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen en tuck', 'note' => 'Tuck FL 4×8-10 s, German hang 20-30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang suave, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Tuck Front Lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, escápulas activas, codo recto, sin balanceo'],
                        ['name' => 'Pull Up', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                        ['name' => 'German Hang', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Desde skin the cat, hombro relajado, respiración continua'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Tuck Front Lever Sólido
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Tuck de 8-10 s sin asistencia: este es el gate que el Master exige en su N6. Aquí se consigue de forma aislada.',
                'Fase 2 (Progresión): Entra el advanced tuck: cadera un poco más abierta, espalda paralela al suelo. Poco a poco: 3-5 s bastan.',
                'Fase 3 (Intensificación): Tuck 10-12 s + advanced tuck 5 s. El pico del nivel vive en la sesión B.',
                'Fase 4 (Recuperación): Regresión activa: tucks cortos y German hang.',
                'Si el tuck se degrada antes de tiempo, acumula volumen con series de 6-8 s y descanso completo en lugar de forzar series al fallo.',
                'El advanced tuck se gana con la cadera, no con los brazos: piensa en "empujar la barra hacia abajo" con los lats.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tuck sólido 8-10 s', 'note' => 'Tuck hold 3×8-10 s, advanced tuck 2×3-5 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Advanced tuck', 'note' => 'Tuck hold 10 s, advanced tuck 3×3-5 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Tuck 10-12 s, advanced tuck 5 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4. El tuck 10 s es el gate del N6 del Master',
                    'requirements' => [
                        ['name' => 'Tuck Front Lever', 'minimum' => 10, 'target' => 12, 'unit' => 'segundos', 'form' => 'Gate del Master v4 (N6): rodillas al pecho, sin balanceo'],
                        ['name' => 'Advanced Tuck Front Lever', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cadera parcialmente abierta, espalda paralela al suelo'],
                        ['name' => 'False Grip Ring Row', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, agarre estable'],
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
                'Fase 1 (Base): Advanced tuck 5-8 s (dosis N7 del Master): cadera más abierta que el tuck, espalda paralela.',
                'Fase 2 (Progresión): Lleva el advanced tuck a 8-10 s manteniendo el tuck de 12-15 s como respaldo.',
                'Fase 3 (Intensificación): Advanced tuck 8-10 s + tuck 15 s. Un solo pico de codo por fase.',
                'Fase 4 (Recuperación): German hang largo y movilidad: salud de codo para el nivel de carga que viene.',
                'La clave del advanced tuck es la cadera: empuja la cadera hacia arriba y adelante mientras los lats "cierran" la espalda.',
                'Graba una serie por semana: el front lever se corrige viéndose, no sintiéndose.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Advanced tuck 5-8 s', 'note' => 'Adv tuck 3×5-8 s, tuck hold 12 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Advanced tuck 8-10 s', 'note' => 'Adv tuck 3×8-10 s, tuck 12-15 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Adv tuck 8-10 s, tuck 15 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang suave, muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5. Las dosis siguen el N7 del Master',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Front Lever', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Dosis N7 del Master: espalda paralela, cadera alineada, sin balanceo'],
                        ['name' => 'Tuck Front Lever', 'minimum' => 12, 'target' => 15, 'unit' => 'segundos', 'form' => 'Respaldo sólido, rodillas al pecho'],
                        ['name' => 'Weighted Ring Row', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, cuerpo en línea'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: One-Leg Front Lever
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): One-leg 4-6 s por lado (dosis N8/N10 del Master): una pierna extendida, la otra plegada.',
                'Fase 2 (Progresión): Simetría estricta: mismo tiempo con cada pierna. La asimetría aquí se paga en el straddle.',
                'Fase 3 (Intensificación): One-leg 6 s + advanced tuck 10-12 s. El pico vive en la sesión B.',
                'Fase 4 (Recuperación): German hang largo: el seguro del codo.',
                'Alterna la pierna de apertura entre series: el cuerpo tiende a "escapar" hacia el lado de la pierna plegada.',
                'Mantén el advanced tuck de 10-12 s como referencia: si se degrada, retrocede volumen de one-leg.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'One-leg 4-6 s por lado', 'note' => 'One-leg 3×4-6 s/lado, adv tuck 8-10 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Simetría entre lados', 'note' => 'One-leg 3×6 s/lado, adv tuck 10 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'One-leg 4×6 s, adv tuck 10-12 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang largo, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'One Leg Front Lever', 'minimum' => 4, 'target' => 6, 'unit' => 'segundos', 'form' => 'Por lado: una pierna extendida, cuerpo en línea, sin rotación de cadera'],
                        ['name' => 'Advanced Tuck Front Lever', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Espalda paralela, cadera alineada'],
                        ['name' => 'Pull Up', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Straddle Front Lever
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Straddle 4-6 s (dosis N10/N11 del Master): piernas abiertas, cadera activa. La apertura de straddle se ajusta a tu movilidad.',
                'Fase 2 (Progresión): Cierra el straddle poco a poco a medida que ganas fuerza: menos palanca = más carga real.',
                'Fase 3 (Intensificación): Straddle 6 s + one-leg 8 s. La sesión B concentra todo el pico del nivel.',
                'Fase 4 (Recuperación): German hang 40-50 s y muñeca: el codo sigue siendo el activo crítico.',
                'El ancho de straddle es una herramienta de progresión: aprieta el straddle cuando 6 s sean cómodos.',
                'No abandones el one-leg: es el escalón de seguridad si el straddle se resiste.',
                '⚠️ Regla tendinosa §1.7: a esta altura la carga de codo es alta: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Straddle 4-6 s', 'note' => 'Straddle 3×4-6 s, one-leg 6-8 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Cerrar el straddle', 'note' => 'Straddle 3×6 s, one-leg 8 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Straddle 4×6 s, one-leg 3×8 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang 40-50 s, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Straddle Front Lever', 'minimum' => 4, 'target' => 6, 'unit' => 'segundos', 'form' => 'Piernas abiertas, cadera activa, cuerpo en línea'],
                        ['name' => 'One Leg Front Lever', 'minimum' => 6, 'target' => 8, 'unit' => 'segundos', 'form' => 'Por lado, sin rotación de cadera'],
                        ['name' => 'German Hang', 'minimum' => 40, 'target' => 50, 'unit' => 'segundos', 'form' => 'Hombro relajado, respiración continua'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Full Front Lever
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Full asistido 3-5 s con banda (dosis N11/N12 del Master): la banda fina solo corrige, no sostiene.',
                'Fase 2 (Progresión): Suma front lever raises: subir desde colgado a la línea del front lever construye la fuerza del gesto completo.',
                'Fase 3 (Intensificación): Full 5 s + straddle 10 s + raises 3-5. El intento de full va siempre al principio de la sesión, en fresco.',
                'Fase 4 (Recuperación): Patrón sin carga completa; movilidad larga.',
                'El straddle de 8-10 s es tu seguro: si el full asistido se estanca, vuelve a él un ciclo.',
                'Los raises se hacen sin balanceo: la excéntrica (bajada) vale tanto como la subida.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Full asistido 3-5 s', 'note' => 'Full asistido 3×3-5 s, straddle 8 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Raises y menos banda', 'note' => 'Full asistido 3-5 s, raises 3×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Full 5 s, straddle 10 s, raises 3-5', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Raises ligeros, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Full Front Lever', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Cuerpo completo en línea horizontal, codo recto, sin balanceo (asistencia mínima permitida)'],
                        ['name' => 'Straddle Front Lever', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Piernas abiertas, línea limpia'],
                        ['name' => 'Front Lever Raise', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Subida controlada desde colgado hasta la línea del FL, sin impulso'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8: Dominio del Front Lever
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Full front lever 5-10 s (N12 del Master): el intento libre va siempre al principio de la sesión B, en fresco.',
                'Fase 2 (Progresión): Straddle 10-12 s y raises con volumen: el dominio se consolida con variedades.',
                'Fase 3 (Intensificación): Full 10 s + straddle 15 s + raises 5-8. Pico máximo del programa.',
                'Fase 4 (Recuperación): El bloque C (German hang + prehab) es el seguro de vida: mantenlo incluso en deload.',
                'Aquí el front lever ya no es el objetivo: es la plataforma. El juego pasa a las raises, los straddle presses y las combinaciones.',
                'La consistencia manda: un full de 8 s todas las semanas vale más que un 12 s esporádico.',
                '⚠️ Regla tendinosa §1.7: a esta altura la carga de codo es máxima: ante cualquier señal, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Full 5-8 s', 'note' => 'Full 3×5-8 s, straddle 10 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Full 8-10 s', 'note' => 'Full 3×8-10 s, raises 3×5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: full 10 s', 'note' => 'Full 4×10 s, straddle 12-15 s, raises 5-8', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Prehab mantenido, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Full Front Lever', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cuerpo completo en línea horizontal, codo recto, sin balanceo'],
                        ['name' => 'Straddle Front Lever', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Piernas abiertas, línea limpia'],
                        ['name' => 'Pull Up', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'form' => 'Estrictas, rango completo, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L2 = [
        'Tuck Front Lever' => 'Con banda ligera: aprende la posición antes de pelear por el tiempo; reduce la banda antes de añadir segundos',
        'Tuck Front Lever Hold' => 'Rodillas al pecho, escápulas activas, codo recto; baja antes de que la cadera caiga',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Full Front Lever' => 'Asistido con banda fina: la banda corrige, no sostiene; el intento libre va siempre al principio de la sesión',
        'Front Lever Raise' => 'Sin balanceo: subida controlada y excéntrica igual de lenta',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Tuck Front Lever' => 'Cadera completamente plegada; escápulas activas; codo recto',
        'Tuck Front Lever Hold' => 'Baja antes de que la cadera caiga: la calidad del hold manda sobre los segundos',
        'Advanced Tuck Front Lever' => 'Cadera parcialmente abierta, espalda paralela al suelo',
        'Advanced Tuck Front Lever Hold' => 'Cadera alineada, sin abrir más de lo que controlas',
        'One Leg Front Lever Hold' => 'Por lado, sin rotación de cadera; alterna la pierna de apertura entre series',
        'Straddle Front Lever Hold' => 'Ajusta la apertura a tu movilidad: aprieta el straddle cuando ganes fuerza',
        'Full Front Lever Hold' => 'Línea completa, codo recto; el intento libre siempre en fresco',
        'Front Lever Raise' => 'Subida controlada desde colgado; sin impulso ni balanceo',
        'German Hang' => 'Salud de codo y hombro: hombro relajado, respiración continua; nunca lo saltes',
        'Skin The Cat' => 'Rango completo suave, hombros calientes',
        'Scapular Pull Up' => 'Depresión escapular clara con brazos extendidos: el interruptor del front lever',
        'Hollow Body Hold' => 'Lumbar pegada al suelo, costillas abajo',
        'Arch Hold' => 'Glúteos activos, mirada al suelo',
        'Hanging Leg Raise' => 'Sin balanceo; lleva los pies a la barra o a 90°',
        'Tuck L-Sit' => 'Empuje fuerte de los brazos, hombros lejos de las orejas',
        'Face Pull' => 'Codo alto, rotación externa completa',
    ];
}
