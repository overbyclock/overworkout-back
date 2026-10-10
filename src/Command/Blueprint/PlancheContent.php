<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Planche Mastery.
 * Especialización del Master v4: consejos de planche, lean, tuck,
 * advanced tuck, straddle, full planche y planche push-up extraídos de
 * los detalles de N6-N8, N10-N11, N14-N15 y Olimpo.
 * Reglas tendinosas §1.7 aplicadas a muñeca y hombro (la planche castiga
 * ambos articulaciones en brazo recto).
 */
class PlancheContent
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
    // LEVEL 1: Base de Empuje y Muñeca
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La planche se construye desde la muñeca y el hombro. No forces extensión de muñeca en frío: calienta con oraciones y movilidad suave.',
                'Fase 2 (Progresión): Aumenta reps de empuje y tiempo de lean solo si la técnica se mantiene: cadera alta, codos bloqueados, hombros por delante de las manos.',
                'Fase 3 (Intensificación): Máximo volumen de base. Si la muñeca o el hombro protestan, recorta series antes que descanso.',
                'Fase 4 (Recuperación): Descarga de muñeca y empuje. Movilidad suave y poco volumen.',
                'Los ciclos se repiten hasta superar el test. Sin prisa: la línea de planche madura lento y castiga las prisas.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
                'Dolor articular ≠ molestia muscular: cualquier dolor punzante en muñeca u hombro detiene el ejercicio y se retrocede un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Empuje y muñeca de base', 'note' => 'Push-ups 3×10-12, pike push-up 3×5-6, wrist prayer 2×20 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar reps y tiempo de lean', 'note' => 'Push-ups 3×12-15, planche lean 3×15 s, wrist curl 2×15', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Push-ups 4×15, planche lean 3×20 s, PPPU 2×6', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de muñeca y hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Standard Push Up', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, cuerpo en línea, codos a 45°, muñecas cómodas'],
                        ['name' => 'Pike Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Cadera alta, cabeza delante de las manos, rango completo'],
                        ['name' => 'Wrist Push-Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Flexión sobre el dorso de las manos, rango sin dolor en muñeca'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Frog y Primer Tuck
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El frog stand enseña a apoyar el peso adelante de las manos. Codos bloqueados, cadera alta, rodillas apoyadas en los tríceps.',
                'Fase 2 (Progresión): El tuck planche con puntas es el primer peldaño de la línea: las puntas solo equilibran, la cadera debe estar a la altura de los hombros.',
                'Fase 3 (Intensificación): Frog 20-25 s y tuck toe support 10 s. Un solo pico de muñeca/hombro por fase.',
                'Fase 4 (Recuperación): Vuelve a frog light, lean suave y movilidad de muñeca.',
                'Los holds de planche van con descanso completo (2-3 min), nunca en circuito.',
                'Si el tiempo aprieta se recorta volumen (series), nunca el descanso entre series de planche.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Frog stand y primer tuck', 'note' => 'Frog stand 3×15-20 s, tuck toe support 4×5-8 s, wrist prayer 2×30 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo en tuck asistido', 'note' => 'Frog stand 3×20 s, tuck toe support 4×8-10 s, PPPU 2×6-8', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Frog stand 3×20-25 s, tuck toe support 5×10 s, PPPU 3×8', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de muñeca y hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Frog Stand', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Codos bloqueados, cadera alta, rodillas en tríceps, sin dolor'],
                        ['name' => 'Tuck Planche Toe Support', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Puntas de apoyo mínimas, cadera a altura de hombros, codos bloqueados'],
                        ['name' => 'Pseudo Planche Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Manos a altura de cadera, cuerpo recto, codos a 45°'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Tuck Planche
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El tuck planche libre exige cadera alta y proyección de hombros. Si la cadera se hunde, vuelve a puntas un ciclo.',
                'Fase 2 (Progresión): Planche lean pesado: hombros por delante de las manos, dedos agarrando el suelo, codos completamente bloqueados.',
                'Fase 3 (Intensificación): Tuck 5-8 s y lean 20-30 s. La calidad del hold importa más que el tiempo bruto.',
                'Fase 4 (Recuperación): Regresión activa: tuck toe support y lean suave.',
                'El pseudo planche push-up debe mantener la línea: si las caderas se desploman, es un push-up normal con las manos atrás.',
                'Alterna puntas y libre para acumular volumen sin perder la posición.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tuck planche libre y lean', 'note' => 'Tuck planche 4×3-5 s, planche lean 3×12-15 s, PPPU 2×6-8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo de tuck', 'note' => 'Tuck planche 5×3-5 s, planche lean 3×15 s, L-sit 2×6-10 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Tuck planche 5×5-8 s, planche lean 3×15-20 s, PPPU 3×8', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Tuck toe support, lean suave, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Tuck Planche', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cadera alta, hombros proyectados, codos bloqueados, sin puntas'],
                        ['name' => 'Planche Lean', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Hombros por delante de las manos, línea recta, codos bloqueados'],
                        ['name' => 'Pseudo Planche Push Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Manos a altura de cadera, cuerpo recto, control excéntrico'],
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
                'Fase 1 (Base): El advanced tuck abre las rodillas hasta que la cadera quede alineada con los hombros. No dejes caer la cadera al abrir.',
                'Fase 2 (Progresión): Band assisted tuck planche para acumular tiempo con cadera alta antes de soltar la banda.',
                'Fase 3 (Intensificación): Advanced tuck 5-8 s, tuck 10 s y PPPU 10 reps. El pico de hombro vive en la sesión B.',
                'Fase 4 (Recuperación): Regresión a tuck y movilidad de hombro: el codo y la muñeca agradecen la descarga.',
                'El PPPU debe cargar la línea de planche: si sientes solo pecho, lleva las manos más atrás o inclina el cuerpo.',
                'No fuerces series al fallo en advanced tuck: acumula series limpias de 4-6 s con descanso completo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Advanced tuck con asistencia', 'note' => 'Band assisted tuck 4×8-10 s, advanced tuck 4×3-5 s, PPPU 3×6-8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Menos asistencia, más tiempo', 'note' => 'Advanced tuck 5×4-6 s, tuck 3×8-10 s, PPPU 3×8', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Advanced tuck 5×5-8 s, tuck 3×10 s, PPPU 3×10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Tuck toe support, lean suave, face pull y band pull-apart', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Planche', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cadera alineada con hombros, rodillas abiertas, codos bloqueados'],
                        ['name' => 'Tuck Planche', 'minimum' => 10, 'target' => 12, 'unit' => 'segundos', 'form' => 'Cadera alta, hombros proyectados, sin puntas'],
                        ['name' => 'Pseudo Planche Push Up', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Línea recta, control excéntrico, pecho entre las manos'],
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
                'Fase 1 (Base): La straddle planche exige apertura de cadera y compresión. Empieza con apertura cerrada: un straddle sólido vale más que uno abierto temblando.',
                'Fase 2 (Progresión): Mantén advanced tuck fuerte mientras sumas segundos de straddle. La cadera no debe hundirse al abrir.',
                'Fase 3 (Intensificación): Straddle 5-8 s y advanced tuck 10-12 s. El pico de hombro sigue en la sesión B.',
                'Fase 4 (Recuperación): Sin straddle esta fase: regresión a advanced tuck y movilidad de cadera.',
                'El planche lean de 30-45 s es el seguro de técnica: si se degrada, retrocede volumen de straddle.',
                'La apertura de straddle se gana en L-sit y compresión, no forzando la posición.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer straddle asistido', 'note' => 'Straddle planche 4×3-4 s, advanced tuck 4×6-8 s, planche lean 3×20-25 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo de straddle', 'note' => 'Straddle planche 4×4-6 s, advanced tuck 4×8-10 s, handstand push-up 3×4-5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Straddle planche 5×5-8 s, advanced tuck 4×10-12 s, HSPU 4×5-6', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Advanced tuck, HSPU ligero, movilidad de cadera', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca, hombro y cadera']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 4, 'target' => 6, 'unit' => 'segundos', 'form' => 'Apertura controlada, cadera alta, codos bloqueados'],
                        ['name' => 'Advanced Tuck Planche', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Cadera alineada, rodillas abiertas, proyección de hombros'],
                        ['name' => 'Planche Lean', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Hombros por delante de las manos, línea recta, sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Straddle Planche
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Straddle planche sólida: 4-6 s limpios antes de buscar más tiempo. La cadera alta es no negociable.',
                'Fase 2 (Progresión): Straddle 5-8 s con descanso completo. El advanced tuck pasa a ser mantenimiento técnico.',
                'Fase 3 (Intensificación): Straddle 6-8 s y compresión con tuck L-sit. Un solo pico de hombro por fase.',
                'Fase 4 (Recuperación): Volumen reducido y movilidad de cadera. No pierdas la técnica por fatiga.',
                'El tuck L-sit mide la compresión que sostiene la straddle: si falla, la straddle se rompe.',
                'Si la straddle se estanca, revisa la apertura de cadera y la proyección de hombros antes de forzar más tiempo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Straddle planche sólida', 'note' => 'Straddle planche 4×4-6 s, advanced tuck 4×8-10 s, tuck L-sit 3×10-12 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo de straddle', 'note' => 'Straddle planche 5×5-8 s, advanced tuck 4×10-12 s, tuck L-sit 3×12 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Straddle planche 5×6-8 s, advanced tuck 4×10-12 s, tuck L-sit 3×12-15 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Straddle planche ligera, L-sit suave, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca, hombro y cadera']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Straddle Planche', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Línea recta, cadera alta, apertura controlada, codos bloqueados'],
                        ['name' => 'Advanced Tuck Planche', 'minimum' => 10, 'target' => 12, 'unit' => 'segundos', 'form' => 'Cadera alineada, proyección de hombros, sin puntas'],
                        ['name' => 'Tuck L-Sit', 'minimum' => 12, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cadera cerca de las manos, rodillas juntas, escápulas elevadas'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Full Planche / Press
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Full planche asistida con banda a la cadera. La banda no es trampa: permite acumular tiempo con la línea correcta.',
                'Fase 2 (Progresión): Reduce la asistencia de la banda o aumenta el tiempo manteniendo cadera alta y codos bloqueados.',
                'Fase 3 (Intensificación): Full planche 3-5 s y straddle 8-10 s. El HSPU mantiene el empuje vertical.',
                'Fase 4 (Recuperación): Sin full planche esta fase: straddle ligera y HSPU técnico.',
                'La banda debe ayudar en la cadera, no en los hombros: si los hombros se abren, la asistencia está mal colocada.',
                'Mantén el straddle planche sólido: es la base sobre la que se construye la full.',
                '⚠️ Regla tendinosa §1.7: a esta altura la muñeca y el hombro cargan mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Full planche asistida', 'note' => 'Full planche (banda) 4×2-3 s, straddle 5×5-8 s, HSPU 4×3-4', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Menos asistencia, más tiempo', 'note' => 'Full planche (banda) 5×3-5 s, straddle 5×6-8 s, HSPU 4×4-5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Full planche (banda) 5×3-5 s, straddle 5×8-10 s, HSPU 5×5-6', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Straddle ligera, HSPU suave, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca, hombro y cadera']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Planche', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Asistencia con banda a la cadera permitida; cadera alta, codos bloqueados, línea recta'],
                        ['name' => 'Straddle Planche', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Línea recta, cadera alta, apertura controlada'],
                        ['name' => 'Handstand Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Cabeza delante de las manos, rango completo, controlado'],
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
                'Fase 1 (Base): Dominio de la full planche: 3-5 s limpios y primeros intentos de planche push-up.',
                'Fase 2 (Progresión): Planche push-up 1-2 reps y straddle planche push-up 2-3. Control excéntrico en cada repetición.',
                'Fase 3 (Intensificación): Planche 5-8 s, planche push-up 2-3 reps y maltese asistido técnico. Pico máximo del programa.',
                'Fase 4 (Recuperación): Mantén planche ligera, prehab de muñeca y hombro, y movilidad larga.',
                'El planche push-up se gana manteniendo la línea: si la cadera se hunde en la bajada, no es válido.',
                'El maltese asistido es el primer peldaño hacia la élite de anillas: banda máxima, técnico, sin ego.',
                '⚠️ Regla tendinosa §1.7: en el nivel de dominio la muñeca y el hombro trabajan al límite: cualquier señal de alarma exige bajar un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Dominio de full planche', 'note' => 'Planche 5×3-5 s, planche push-up 3×1, straddle planche push-up 4×1-2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Planche push-up y straddle planche push-up', 'note' => 'Planche 5×4-6 s, planche push-up 4×1-2, straddle planche push-up 4×2-3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico del programa', 'note' => 'Planche 5×5-8 s, planche push-up 5×2-3, maltese asistido 3×4-6 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Planche ligera, prehab, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca, hombro y cadera']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Planche', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Cadera alta, codos bloqueados, línea recta; banda ligera permitida'],
                        ['name' => 'Planche Push Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Desde planche, cadera alta en todo el recorrido, sin rebotes'],
                        ['name' => 'Straddle Planche', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Línea recta, cadera alta, apertura controlada'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Planche Lean' => 'Hombros por delante de las manos, dedos agarrando el suelo, codos bloqueados; empieza con poca inclinación',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Tuck Planche Toe Support' => 'Puntas de apoyo mínimas; cadera a la altura de los hombros, no dejes caer la cadera',
        'Frog Stand' => 'Rodillas apoyadas en los tríceps, codos bloqueados, cadera alta',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Tuck Planche' => 'Cadera alta, espalda redonda progresiva, hombros proyectados; baja si la cadera se hunde',
        'Planche Lean' => 'Proyección de hombros máxima sin dolor; mantén codos bloqueados',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Planche' => 'Asistencia con banda a la cadera; la banda debe sostener la cadera, no los hombros',
    ];

    private const EXERCISE_NOTES_L8 = [
        'Planche' => 'Banda ligera permitida; prioriza cadera alta y codos bloqueados sobre el tiempo bruto',
        'Planche Push Up' => 'Cadera alta en todo el recorrido; controla la bajada, sin rebotes',
        'Assisted Maltese' => 'Banda máxima, posición técnica; no sustituyas proyección de hombros por apertura excesiva',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Planche Lean' => 'Hombros por delante de las manos, codos bloqueados, dedos activos en el suelo',
        'Pseudo Planche Push Up' => 'Manos a altura de cadera, cuerpo recto, codos a 45°, control excéntrico',
        'Tuck Planche Toe Support' => 'Puntas de apoyo mínimas; cadera a altura de hombros',
        'Tuck Planche' => 'Cadera alta, rodillas juntas, hombros proyectados',
        'Band Assisted Tuck Planche' => 'Banda a la cadera o los muslos; mantén la línea sin arquear',
        'Advanced Tuck Planche' => 'Cadera alineada con hombros, rodillas abiertas, codos bloqueados',
        'Straddle Planche' => 'Apertura controlada, cadera alta, codos bloqueados; mejor cerrado y sólido que abierto y temblando',
        'Planche' => 'Línea recta, cadera alta, codos bloqueados, proyección de hombros',
        'Planche Push Up' => 'Desde planche, cadera alta en todo el recorrido, sin rebotes',
        'Straddle Planche Push Up' => 'Cadera alta, apertura estable, control en bajada y subida',
        'Advanced Tuck Planche Push Up' => 'Cadera alineada, codos a 45°, control total del movimiento',
        'Frog Stand' => 'Rodillas en tríceps, codos bloqueados, cadera alta',
        'Handstand Push Up' => 'Cabeza delante de las manos, cadera sobre manos, control en bajada',
        'Pike Push Up' => 'Cadera alta, cabeza al suelo, escápulas elevadas al final',
        'Elevated Pike Push Up' => 'Pies elevados; mismo criterio que pike push-up con más rango',
        'Decline Push Up' => 'Pies elevados, cuerpo recto, pecho bajo',
        'Standard Push Up' => 'Cuerpo en línea, pecho al suelo, codos a 45°',
        'Tuck L-Sit' => 'Cadera cerca de las manos, rodillas juntas, escápulas elevadas',
        'L-Sit' => 'Piernas extendidas, cadera elevada, escápulas elevadas',
        'Hollow Body Hold' => 'Zona lumbar pegada al suelo, hombros y piernas elevadas',
        'Ring Support Hold' => 'Anillas al cuerpo, brazos rectos, hombros lejos de las orejas',
        'Fingertip Hold' => 'Dedos activos, muñeca en extensión tolerable, sin dolor',
        'Wrist Prayer' => 'Movilidad activa sin dolor: mece suave hacia extensión',
        'Wrist Curl' => 'Rango completo suave; nunca al fallo',
        'Reverse Wrist Curl' => 'Seguro del codo: extensores fuertes equilibran la carga de planche',
        'Back-of-Hand Stretch' => 'Extensión pasiva suave; sin dolor punzante',
        'Shoulder Dislocate' => 'Agarre amplio, movimiento lento y controlado, pecho abierto',
        'Wall Slide' => 'Espalda y glúteos contra la pared, brazos en W → Y sin perder contacto',
        'Face Pull' => 'Codos altos, rotación externa, escápulas retraídas',
        'Band Pull-Apart' => 'Brazos rectos, rotación externa, escápulas juntas',
        'External Rotation' => 'Codo pegado al cuerpo, rotación externa controlada',
        'Cuban Rotation' => 'Codo a 90°, rotación externa hasta el límite cómodo',
        'Assisted Maltese' => 'Banda máxima, hombros por delante, cadera alta; trabajo técnico',
    ];
}
