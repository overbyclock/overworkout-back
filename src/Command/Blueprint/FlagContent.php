<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Flag Mastery.
 * Especialización del Master v4: progresión específica hacia la bandera humana,
 * con técnica de agarre, oblicuos y reglas tendinosas §1.7 aplicadas a hombro,
 * muñeca y core lateral.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class FlagContent
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
            1 => self::EXERCISE_NOTES_L1,
            2 => self::EXERCISE_NOTES_L2,
            3 => self::EXERCISE_NOTES_L3,
            4 => self::EXERCISE_NOTES_L4,
            5 => self::EXERCISE_NOTES_L5,
            6 => self::EXERCISE_NOTES_L6,
            7 => self::EXERCISE_NOTES_L7,
            8 => self::EXERCISE_NOTES_L8,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Tracción y Core Lateral
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La bandera se construye desde la tracción y el core lateral. No intentes sostener una bandera sin base: primero dominadas limpias y side plank sólido.',
                'Fase 2 (Progresión): Aumenta reps de dominadas y tiempo de side plank. El oblicuo debe aguantar 45-60 s antes de cargar la posición de bandera.',
                'Fase 3 (Intensificación): Volumen máximo de base. Los colgados llegan a 30-45 s y las dominadas a 8-10 reps estrictas.',
                'Fase 4 (Recuperación): Descarga de tracción y core lateral. Mantén movilidad de hombros y cadera.',
                'Técnica de bandera: agarre inferior prono (palma hacia adentro) y superior supino o neutro. El brazo inferior empuja; el superior tira ligeramente.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
                'La cadera debe quedar alta: bandera no es un front lever lateral. Activa oblicuos y glúteos para mantener la línea.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tracción y core lateral de base', 'note' => 'Pull up 5-6×3, side plank 20 s, dead hang 15 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar reps y tiempo', 'note' => 'Pull up 6-8×3, side plank 25-30 s, active hang 20 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo de base', 'note' => 'Pull up 8-10×4, side plank 30-45 s, dead hang 25-30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombros y cadera', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros y cadera']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Standard Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                        ['name' => 'Side Plank', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Cuerpo alineado, cadera alta, sin rotar'],
                        ['name' => 'Dead Hang', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Agarre completo, brazos extendidos, sin balanceo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Primer Vertical
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El Vertical Flag asistido es el primer peldaño: pies apoyados en cajón o suelo, cuerpo vertical, brazo inferior empuja.',
                'Fase 2 (Progresión): Aumenta el tiempo en vertical y controla negativas de dragon flag. El core comienza a sentir la posición de bandera.',
                'Fase 3 (Intensificación): Marca de 12-15 s en Vertical Flag y 4-5 negativas de dragon flag. Descanso completo entre series.',
                'Fase 4 (Recuperación): Regresa a colgados y side plank suaves. Nunca entrenar bandera con fatiga de hombro.',
                'Técnica de bandera: empuja con el brazo inferior como si quisieras alejar la barra del cuerpo; el brazo superior estabiliza.',
                'Los holds de bandera van con descanso completo (2-3 min), nunca en circuito. Si el tiempo aprieta, recorta series, no descanso.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer Vertical Flag asistido', 'note' => 'Vertical flag 5-8 s, dragon flag negative 3×3, human flag raise 3×3', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo en vertical', 'note' => 'Vertical flag 8-12 s, human flag raise 3-5×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen de vertical', 'note' => 'Vertical flag 12-15 s, dragon flag negative 4-5×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombros', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Vertical Flag', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Asistencia admisible (pies en cajón/suelo); cuerpo vertical, brazo inferior empuja'],
                        ['name' => 'Dragon Flag Negative', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Bajada controlada, cuerpo en línea, solo hombros en el suelo'],
                        ['name' => 'Standard Pull Up', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rango completo, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Bandera Vertical
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Bandera vertical sólida 10-12 s sin asistencia (o mínima) antes de cargar Tuck Flag.',
                'Fase 2 (Progresión): Entra Tuck Flag asistido: rodillas al pecho, cadera alta. Mantén Vertical Flag como mantenimiento.',
                'Fase 3 (Intensificación): Vertical 15 s + Tuck Flag 5 s + Human Flag Raise 5-8 reps. El primer pico real de bandera.',
                'Fase 4 (Recuperación): Regresa a Vertical Flag asistido y core lateral suave.',
                'Técnica de bandera: cadera arriba es la regla de oro. Si la cadera cae, el gesto se convierte en un front lever torcido y el hombro sufre.',
                'El agarre inferior debe estar prono y activo; el superior puede ser supino o neutro. El brazo inferior hace la mayor parte del trabajo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Bandera vertical sólida', 'note' => 'Vertical flag 10-12 s, tuck flag asistido 3-5 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Tuck Flag asistido', 'note' => 'Vertical flag 12-15 s, tuck flag 3-5 s, human flag raise 5×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: vertical 15 s y tuck 5 s', 'note' => 'Vertical flag 15 s, tuck flag 5 s, human flag raise 5-8×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Vertical flag asistido, core lateral suave', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros y cadera']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Vertical Flag', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo vertical, brazo inferior empuja, sin balanceo'],
                        ['name' => 'Human Flag Raise', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Subida controlada desde vertical hasta 45° o más; bajada lenta'],
                        ['name' => 'Tuck Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, cadera alta, brazo inferior empuja'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Tuck Flag
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Tuck Flag es el objetivo principal. Vertical Flag pasa a ser mantenimiento y Human Flag Raise aporta volumen.',
                'Fase 2 (Progresión): Aumenta el tiempo en tuck y la calidad del raise. Cada repetición de raise debe terminar con cadera alta.',
                'Fase 3 (Intensificación): Tuck 5 s, Vertical 18-20 s, Raise 8 reps. Descanso completo entre intentos de bandera.',
                'Fase 4 (Recuperación): German hang y movilidad de hombro para recuperar del pico de tuck.',
                'Técnica de Tuck Flag: rodillas al pecho, cadera alta, brazo inferior empuja. No dejes que la cadera se abra hacia atrás.',
                'El Dragon Flag completo refuerza el patrón de línea recta que necesitas en la bandera horizontal.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tuck Flag como objetivo', 'note' => 'Tuck flag 3-5 s, vertical flag 12-15 s, dragon flag 3-4×2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo en tuck', 'note' => 'Tuck flag 3-5 s, vertical flag 15-18 s, human flag raise 5-8×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: tuck 5 s y vertical 20 s', 'note' => 'Tuck flag 5 s, vertical flag 18-20 s, human flag raise 8×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang, side plank suave', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Tuck Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, cadera alta, brazo inferior empuja'],
                        ['name' => 'Vertical Flag', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Cuerpo vertical estable, brazo inferior empuja'],
                        ['name' => 'Human Flag Raise', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Subida controlada hasta 45° o más; bajada lenta'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Bandera 45°
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Human Flag 45 Degree es el nuevo objetivo. Mantén Tuck Flag como escalón cercano.',
                'Fase 2 (Progresión): Aumenta el tiempo en 45° y el control de la bajada. Cada fallo debe ser técnico, no por dolor.',
                'Fase 3 (Intensificación): 45° 8 s, Tuck 8 s, Raise 8-10 reps. El core lateral debe aguantar la carga.',
                'Fase 4 (Recuperación): Skin The Cat y movilidad de hombro. El German Hang regresa en el nivel 6.',
                'Técnica de 45°: el ángulo intermedio exige más empuje del brazo inferior. Mantén oblicuos activos y cadera alta.',
                'Human Flag Raise sigue siendo tu mejor volumen: sube controlado hasta el ángulo que domines y baja lento.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer 45°', 'note' => 'Human flag 45 degree 3-5 s, tuck flag 5-8 s, dragon flag 4-5×2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo en 45°', 'note' => 'Human flag 45 degree 5-8 s, tuck flag 5-8 s, human flag raise 8×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 45° 8 s y tuck 8 s', 'note' => 'Human flag 45 degree 8 s, tuck flag 8 s, human flag raise 8-10×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Skin the cat, side plank suave', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Human Flag 45 Degree', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Ángulo intermedio, brazo inferior empuja, oblicuos activos'],
                        ['name' => 'Tuck Flag', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, cadera alta'],
                        ['name' => 'Dragon Flag', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Cuerpo en línea, solo hombros en el suelo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: 45° Sólida
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Consolida 45° 5-8 s y prepara straddle asistida en los raises y en Human Flag.',
                'Fase 2 (Progresión): 45° 8-10 s, Tuck 8-10 s. El objetivo es estabilidad, no tiempo máximo.',
                'Fase 3 (Intensificación): 45° 10 s, Tuck 10 s, Raise 10 reps. El pico del nivel.',
                'Fase 4 (Recuperación): German hang y movilidad larga. El hombro necesita descarga tras la carga de 45°.',
                'Técnica de straddle asistida: abre las piernas en straddle mientras mantienes el empuje del brazo inferior. Usa banda o apoyo si es necesario.',
                'Si 45° se siente inestable, vuelve a Tuck Flag un ciclo antes de forzar straddle.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => '45° sólido y straddle asistida', 'note' => 'Human flag 45 degree 5-8 s, tuck flag 5-8 s, human flag raise 8-10×3', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo', 'note' => 'Human flag 45 degree 8-10 s, tuck flag 8-10 s, human flag raise 8-10×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 45° 10 s y tuck 10 s', 'note' => 'Human flag 45 degree 10 s, tuck flag 10 s, human flag raise 10×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang, movilidad de hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Human Flag 45 Degree', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Ángulo intermedio sólido, cadera alta, sin balanceo'],
                        ['name' => 'Tuck Flag', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, cadera alta'],
                        ['name' => 'Human Flag Raise', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Subida controlada hasta 45° o más'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Straddle Flag
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Straddle Flag asistida: usa Human Flag con piernas en straddle y banda/apoyo. El objetivo es 3-5 s.',
                'Fase 2 (Progresión): Mantén 45° 10-12 s mientras aumentas el tiempo en straddle. Side Plank Leg Lift refuerza los oblicuos.',
                'Fase 3 (Intensificación): Straddle 5 s, 45° 12-15 s, Raise 10 reps. Primer acercamiento real a la bandera horizontal.',
                'Fase 4 (Recuperación): Tuck L-Sit y German hang: recupera hombros y muñecas sin perder core.',
                'Técnica de straddle: piernas abiertas reducen la palanca y hacen el gesto más accesible. Mantén el empuje del brazo inferior en todo momento.',
                'El test de Human Flag 3 s en este nivel se hace con straddle asistida: se mapea al ejercicio Human Flag con nota de asistencia.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Straddle flag asistida', 'note' => 'Human flag (straddle) 3-5 s, human flag 45 degree 8-10 s, side plank leg lift 15-18×2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo en straddle', 'note' => 'Human flag (straddle) 3-5 s, human flag 45 degree 10-12 s, human flag raise 8-10×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: straddle 5 s y 45° 12-15 s', 'note' => 'Human flag (straddle) 5 s, human flag 45 degree 12-15 s, human flag raise 10×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Tuck L-sit, German hang, side plank suave', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Human Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Straddle flag asistida (piernas en straddle, banda o apoyo); brazo inferior empuja, cadera arriba'],
                        ['name' => 'Human Flag 45 Degree', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Ángulo intermedio sólido, oblicuos activos'],
                        ['name' => 'Side Plank Leg Lift', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Sin rotar cadera, pierna recta, oblicuos activos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8: Bandera Horizontal
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Human Flag horizontal 3-5 s es el objetivo final. Mantén straddle asistida como soporte y 45° como mantenimiento.',
                'Fase 2 (Progresión): Trabaja bandera fresca al principio de la sesión B, luego straddle y 45° como volumen.',
                'Fase 3 (Intensificación): Pico del programa: Human Flag 5 s, Straddle 8 s, 45° 15-20 s, Raise 12-15 reps.',
                'Fase 4 (Recuperación): Mantén el patrón sin carga completa. La bandera requiere ciclos de recuperación para consolidarse.',
                'Técnica de bandera horizontal: agarre inferior prono y superior supino/neutro; empuja con el brazo inferior; oblicuos y glúteos mantienen la línea recta.',
                'Cadera alta es la clave: si la cadera cae, el gesto se rompe y el hombro carga de más.',
                '⚠️ Regla tendinosa §1.7: a esta altura del programa el hombro y la muñeca cargan mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Human Flag horizontal', 'note' => 'Human flag 3-5 s, straddle asistida 5-8 s, human flag 45 degree 12-15 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Consolidar bandera', 'note' => 'Human flag 3-5 s, straddle 5-8 s, human flag raise 10-15×3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: bandera 5 s, straddle 8 s, 45° 15-20 s', 'note' => 'Human flag 5 s, straddle 8 s, human flag 45 degree 15-20 s, human flag raise 12-15×3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Patrón sin carga completa, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Human Flag', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Bandera horizontal completa: brazo inferior empuja, cadera arriba, cuerpo en línea'],
                        ['name' => 'Human Flag 45 Degree', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Ángulo intermedio sólido, sin asistencia'],
                        ['name' => 'Human Flag Raise', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Subida controlada hasta 45° o más; bajada lenta'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Vertical Flag' => 'Pies apoyados en cajón/suelo al inicio; busca línea vertical y brazo inferior empujando',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Vertical Flag' => 'Asistencia permitida; brazo inferior empuja activamente para mantener la vertical',
        'Dragon Flag Negative' => 'Bajada controlada, cuerpo en línea; solo los hombros tocan el suelo',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Tuck Flag' => 'Rodillas al pecho, cadera alta; asistencia de puntas si es necesario',
    ];

    private const EXERCISE_NOTES_L4 = [
        'Tuck Flag' => 'Tuck cerrado; empuja con el brazo inferior para mantener la horizontal parcial',
    ];

    private const EXERCISE_NOTES_L5 = [
        'Human Flag 45 Degree' => 'Ángulo intermedio; empuja con el brazo inferior y mantén oblicuos activos',
    ];

    private const EXERCISE_NOTES_L6 = [
        'Human Flag 45 Degree' => '45° sólido; cadera alta y hombros lejos de las orejas',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Human Flag' => 'Straddle flag asistida (piernas en straddle, banda o apoyo); brazo inferior empuja, cadera arriba',
    ];

    private const EXERCISE_NOTES_L8 = [
        'Human Flag' => 'Bandera horizontal completa: brazo inferior empuja, cadera arriba, cuerpo en línea recta',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Vertical Flag' => 'Cuerpo vertical, brazo inferior empuja, agarre inferior prono y superior supino/neutro',
        'Human Flag' => 'Brazo inferior empuja, cadera arriba; agarre inferior prono y superior supino/neutro',
        'Tuck Flag' => 'Rodillas al pecho, cadera alta, brazo inferior empuja activamente',
        'Human Flag 45 Degree' => 'Ángulo intermedio, empuja con el brazo inferior, oblicuos activos',
        'Human Flag Raise' => 'Subida controlada desde vertical hasta 45° o más; bajada lenta y controlada',
        'Dragon Flag' => 'Cuerpo en línea recta, solo los hombros tocan el suelo',
        'Dragon Flag Negative' => 'Bajada lenta y controlada; mantén la línea recta del cuerpo',
        'Side Plank' => 'Cuerpo alineado, cadera alta, hombro estable sobre el codo o la mano',
        'Side Plank Leg Lift' => 'Sin rotar la cadera, pierna recta, oblicuos activos',
        'Lateral Plank Raise' => 'Eleva la cadera con control, apretando el oblicuo del lado de abajo',
        'Copenhagen Plank' => 'Rodilla arriba alineada con la cadera, cuerpo en línea recta',
        'Standard Pull Up' => 'Rango completo, pecho a barra, sin kip',
        'Strict Pull Up' => 'Estricto, sin impulso, control en excéntrica',
        'Chin Up' => 'Palmas hacia ti, rango completo, control en la bajada',
        'Weighted Pull Up' => 'Peso que permita 5-8 reps limpias; no sacrificar forma',
        'Archer Pull Up' => 'Simetría estricta, mismo número de reps a cada lado',
        'Ring Row - Elevated Feet' => 'Pies elevados, pecho a las anillas, cuerpo en línea',
        'Weighted Ring Row' => 'Pecho a las anillas, escápulas retraídas al final',
        'Dead Hang' => 'Agarre completo, brazos extendidos, hombros relajados; baja antes del fallo',
        'Active Hang' => 'Escápulas retraídas y bajas, brazos rectos, pecho abierto',
        'Scapular Pull Up' => 'Solo movimiento escapular, brazos rectos, control total',
        'Hollow Body Hold' => 'Zona lumbar pegada al suelo, hombros y piernas elevadas',
        'Hanging Leg Raise' => 'Sin balanceo, eleva las piernas con control, baja lento',
        'Tuck L-Sit' => 'Hombros lejos de las orejas, rodillas cerca del pecho, brazos rectos',
        'German Hang' => 'Hombro relajado, respiración continua, salud de hombro y codo',
        'Skin The Cat' => 'Movimiento lento y controlado por completo rango de hombro',
        'Diaphragmatic Breathing' => 'Respiración diafragmática profunda para recuperar y estabilizar el core',
    ];
}
