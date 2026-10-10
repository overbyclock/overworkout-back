<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa L-Sit & Press Mastery.
 * Especialización del Master v4: compresión y tuck L-sit de N6/N7, L-sit de
 * N8/N9/N11, V-sit y straddle press del test de N13, press negativo de
 * N13-N14 y reglas tendinosas §1.7 aplicadas a muñeca y cadera.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class LSitPressContent
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
    // LEVEL 1: Base de Compresión y Core
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La compresión se gana con paciencia: codos bloqueados, escápulas bajas y rodillas al pecho. No persigas tiempo, persigue posición.',
                'Fase 2 (Progresión): Aumenta el hollow antes que el leg raise: sin core no hay compresión. La lumbar siempre pegada al suelo.',
                'Fase 3 (Intensificación): Volumen máximo de hollow y leg raises. Baja antes de que la lumbar se despegue.',
                'Fase 4 (Recuperación): Descarga de core: hollows suaves y movilidad de pike larga.',
                'Los holds van con descanso completo (90 s), nunca en circuito: el L-sit se construye en series limpias.',
                'La cadera (pike) es una de las dos limitaciones típicas: los estiramientos de pike son entrenamiento, no relleno.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Compresión y core de base', 'note' => 'Hollow 20-30 s, hanging leg raise 6-8, tuck L-sit 5 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo y reps', 'note' => 'Hollow 30 s, hanging leg raise 8, tuck L-sit 5-8 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Hollow 30-45 s, hanging leg raise 10, tuck L-sit 8-10 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de pike', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Hollow Body Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Lumbar pegada al suelo, hombros elevados, codos bloqueados, respiración continua'],
                        ['name' => 'Hanging Leg Raise', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rodillas al pecho sin balanceo, cadera cerrada en cada rep'],
                        ['name' => 'Tuck L-Sit', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Codos bloqueados, escápulas bajas, rodillas al pecho'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Tuck L-Sit
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El tuck L-sit se gana en el suelo y en paralelas: 8-10 s cómodos antes de subir la dosis (compresión sentada N6).',
                'Fase 2 (Progresión): Codos bloqueados y hombros lejos de las orejas: si el codo se flexiona, el hold no cuenta.',
                'Fase 3 (Intensificación): Tuck 12-15 s y compresión sentada 12 reps. Un solo pico de compresión por fase.',
                'Fase 4 (Recuperación): Regresa a soportes suaves y movilidad; la muñeca agradece la descarga.',
                'Practica el L-sit tanto en suelo como en paralelas: el suelo exige más compresión, las paralelas más empuje.',
                'Si el hold se resiste, no fuerces series al fallo: acumula volumen con series de 8-10 s y descanso completo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Tuck de 8-10 s', 'note' => 'Tuck L-sit 8-10 s, hanging leg raise 8-10, seated leg raise 8-10', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo', 'note' => 'Tuck L-sit 10-12 s, support hold 20-30 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Tuck L-sit 12-15 s, seated leg raise 12', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Tuck L-Sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Codos bloqueados, rodillas al pecho, sin balanceo'],
                        ['name' => 'Seated Leg Raise', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Piernas extendidas, manos al lado de las caderas, sin apoyar los pies'],
                        ['name' => 'Support Hold', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Brazos rectos, hombros lejos de las orejas, anillas/paralelas estables'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Primer L-Sit
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Las patadas al L-sit se hacen desde el tuck sólido: extiende una pierna, luego la otra, luego ambas.',
                'Fase 2 (Progresión): Las anillas enseñan a comprimir de verdad: la inestabilidad castiga la holgura.',
                'Fase 3 (Intensificación): Marca de 10 s en el primer L-sit completo. El pico vive en la sesión B.',
                'Fase 4 (Recuperación): Regresión activa: tucks y compresión sentada sin fatiga.',
                'En el primer L-sit la cadera decide: si las rodillas caen, el problema es compresión; si los codos ceden, es empuje.',
                'Alterna suelo y paralelas: el L-sit en suelo es más duro en compresión, en paralelas más duro en apoyo.',
                '⚠️ Dolor punzante en muñeca al apoyar = muñeca no preparada: refuerza la sesión C un ciclo antes de seguir.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Patadas al L-sit desde tuck', 'note' => 'Tuck L-sit 10-15 s, L-sit 3-5 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'L-sit 5-8 s, hanging leg raise 12', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: primer L-sit de 10 s', 'note' => 'L-sit 8-10 s, tuck L-sit 15-20 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4. El L-sit es el hito del Master N6-N7',
                    'requirements' => [
                        ['name' => 'L-Sit', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Piernas extendidas paralelas al suelo, codos bloqueados, sin balanceo'],
                        ['name' => 'Tuck L-Sit', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Consolidación: rodillas al pecho, hombros lejos de las orejas'],
                        ['name' => 'Hanging Leg Raise', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Cadera cerrada, sin balanceo, control en la bajada'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: L-Sit Sólido
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El L-sit de 10-15 s es el estándar del Master en su N8-N9: aquí se consolida de forma aislada.',
                'Fase 2 (Progresión): El L-Sit One Leg enseña la transición al V-sit: una pierna sube, la cadera sigue.',
                'Fase 3 (Intensificación): Marca de 15 s en L-sit + press negativo 3-5. El pico de compresión vive en la sesión B.',
                'Fase 4 (Recuperación): Muñeca y cadera largas: el press negativo exige pike profundo.',
                'El press negativo se gana con paciencia: baja en 5 s con los hombros empujados al suelo y la cadera cerrada.',
                'Si el L-sit de 15 s se resiste, acumula volumen con series de 10-12 s y descanso completo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'L-sit de 10 s', 'note' => 'L-sit 5-8 s, seated leg raise 12-15', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'L-sit 8-10 s, L-sit one leg, press negativo 3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: L-sit 10-15 s', 'note' => 'L-sit 10-15 s, press negativo 3-5', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, pike y muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5. El L-sit 10 s es el estándar N8-N9 del Master',
                    'requirements' => [
                        ['name' => 'L-Sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Suelo o paralelas, piernas extendidas, codos bloqueados'],
                        ['name' => 'Seated Leg Raise', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'form' => 'Manos al lado de las caderas, cadera cerrada, sin apoyo'],
                        ['name' => 'Pike Press Negative', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Bajada en 5 s, hombros empujados al suelo, sin desplomar'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: V-Sit
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El V-sit es un L-sit con la cadera más cerrada y las piernas más altas: gana altura poco a poco.',
                'Fase 2 (Progresión): El straddle fold y el pike de pie desbloquean el ángulo: la cadera es el límite, no el core.',
                'Fase 3 (Intensificación): V-sit 5-8 s + press negativo 4-5. Un solo pico de cada gesto por fase.',
                'Fase 4 (Recuperación): Movilidad larga de cadera; los holds altos castigan la cadera y la muñeca.',
                'En el V-sit no aceptes codos flexionados: el ángulo se gana con la cadera, no doblando los brazos.',
                'El straddle fold de 60 s es la puerta al straddle press: no lo saltes nunca.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'V-sit de 3-5 s', 'note' => 'L-sit 10-15 s, V-sit 3-5 s, straddle fold 30-45 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'V-sit 5-6 s, press negativo 4', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: V-sit 5-8 s', 'note' => 'V-sit 5-8 s, straddle fold 60 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'V-Sit', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Codos bloqueados, piernas elevadas por encima de la horizontal, cadera cerrada'],
                        ['name' => 'L-Sit', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Suelo o paralelas, consolidación del nivel anterior'],
                        ['name' => 'Straddle Fold', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Piernas abiertas, pecho al suelo, respiración continua'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Straddle Press
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El straddle press asistido se practica con puntas de pies: asistencia mínima y compresión máxima.',
                'Fase 2 (Progresión): El V-sit de 10 s es el test del N13 del Master: aquí se consigue de forma aislada.',
                'Fase 3 (Intensificación): Straddle press asistido 3 reps + V-sit 10 s + pike de pie 90 s.',
                'Fase 4 (Recuperación): Patrón sin carga completa; movilidad de pancake larga.',
                'El press sale de la compresión, no del impulso: si necesitas impulso de piernas, añade más asistencia de puntas.',
                'La muñeca y la cadera son los limitadores típicos: la sesión C es obligatoria, nunca la cambies por más holds.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Straddle press asistido a puntas', 'note' => 'Straddle press asistido 1-3, V-sit 5-8 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'V-sit 8-10 s, pike de pie 60-90 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: V-sit 10 s (test N13)', 'note' => 'V-sit 10 s, straddle press asistido 3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Patrón sin carga, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7. El V-sit 10 s es parte del test de N13 del Master',
                    'requirements' => [
                        ['name' => 'Straddle Press asistido', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Con puntas de pies en el suelo: asistencia mínima, compresión máxima, sin impulso de piernas'],
                        ['name' => 'V-Sit', 'minimum' => 8, 'target' => 10, 'unit' => 'segundos', 'form' => 'Codos bloqueados, piernas por encima de la horizontal'],
                        ['name' => 'Standing Pike Stretch', 'minimum' => 60, 'target' => 90, 'unit' => 'segundos', 'form' => 'Piernas extendidas, pecho hacia muslos, sin flexionar rodillas'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Press a Pino
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El press negativo sin cajón exige pike profundo: sin movilidad, el press no existe.',
                'Fase 2 (Progresión): El intento de straddle press va siempre al principio de la sesión D, en fresco.',
                'Fase 3 (Intensificación): Press negativo 5-8 + V-sit 10-15 s + HSPU 3-5. Pico del programa.',
                'Fase 4 (Recuperación): Patrón sin carga completa; movilidad larga de muñeca y cadera.',
                'El HSPU construye el empuje vertical que el press necesita: no lo trates como accesorio.',
                'Si el press se estanca, revisa el pike: 5 cm más de movilidad valen más que 5 series más.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Press negativo sin cajón', 'note' => 'Pike press negative 5, V-sit 8-10 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'Straddle press asistido mínimo, V-sit 10-12 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: press negativo 5-8', 'note' => 'Pike press negative 5-8, V-sit 12-15 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Pike Press Negative', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Bajada controlada de 5 s, sin cajón, hombros empujados'],
                        ['name' => 'V-Sit', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Codos bloqueados, cadera cerrada, piernas altas'],
                        ['name' => 'Handstand Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Rango completo a pared, cuerpo en línea, sin arqueo'],
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
                'Fase 1 (Base): Straddle press limpio: una rep perfecta vale más que tres con asistencia encubierta.',
                'Fase 2 (Progresión): El V-sit de 15-20 s y el L-sit de 20-30 s cierran la compresión a la altura del Olimpo.',
                'Fase 3 (Intensificación): Pico máximo: straddle press 3 reps limpias, V-sit 15-20 s, L-sit 25-30 s.',
                'Fase 4 (Recuperación): El bloque C (muñeca-cadera) es el seguro de vida: mantenlo incluso en deload.',
                'Aquí el L-sit ya no es el objetivo: es la plataforma. El juego pasa al press a pino y sus variedades.',
                'La muñeca carga mucho volumen de soportes a esta altura: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Straddle press limpio', 'note' => 'Straddle press 1-3, V-sit 10-15 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Progresión', 'note' => 'V-sit 15 s, L-sit 20-25 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: press 3, V-sit 20 s, L-sit 30 s', 'note' => 'Straddle press 3, V-sit 15-20 s, L-sit 25-30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Prehab mantenido, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de cadera y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Straddle Press', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Limpio, sin asistencia: compresión máxima, sin impulso de piernas'],
                        ['name' => 'V-Sit', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Codos bloqueados, piernas a la altura de la cara, cadera cerrada'],
                        ['name' => 'L-Sit', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Suelo o paralelas, piernas extendidas, control total'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L5 = [
        'V-Sit Hold' => 'V-sit: codos bloqueados y piernas por encima de la horizontal; el ángulo se gana con la cadera, no doblando los brazos',
    ];

    private const EXERCISE_NOTES_L6 = [
        'Straddle Press Handstand' => 'Straddle press asistido: puntas de pies en el suelo, asistencia mínima y compresión máxima; sin impulso de piernas',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Straddle Press Handstand' => 'Straddle press con asistencia mínima de puntas: el intento va siempre al principio de la sesión, en fresco',
    ];

    private const EXERCISE_NOTES_L8 = [
        'Straddle Press Handstand' => 'Straddle press limpio: sin asistencia; una rep perfecta vale más que tres con ayuda encubierta',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Hollow Body Hold' => 'Lumbar pegada al suelo y codos bloqueados; baja antes de que la lumbar se despegue',
        'Hanging Leg Raise' => 'Cadera cerrada en cada rep; sin balanceo ni momentum',
        'Tuck L-Sit' => 'Codos bloqueados, escápulas bajas, rodillas al pecho',
        'L-Sit' => 'Piernas extendidas paralelas al suelo; en anillas, anillas pegadas al cuerpo',
        'L-Sit One Leg' => 'Transición al V-sit: una pierna sube primero, la cadera sigue',
        'V-Sit Hold' => 'V-sit: piernas elevadas a la altura de la cara, codos bloqueados, cadera cerrada',
        'Seated Leg Raise' => 'Manos al lado de las caderas, piernas extendidas; no apoyes los pies',
        'Support Hold' => 'Brazos rectos, hombros lejos de las orejas; anillas/paralelas estables',
        'Ring Support Hold' => 'La inestabilidad de las anillas castiga la holgura: anillas pegadas al cuerpo',
        'Pike Press Negative' => 'Bajada en 5 s con hombros empujados al suelo y cadera cerrada',
        'Straddle Press Handstand' => 'Press con asistencia de puntas según el nivel: compresión máxima, sin impulso',
        'Standing Pike Stretch' => 'El pike es la mitad del press: pecho hacia muslos, rodillas extendidas',
        'Seated Pike Stretch' => 'Desde sentado, pecho al suelo; respiración continua',
        'Straddle Fold' => 'Pancake: piernas abiertas, pecho al suelo; la puerta del straddle press',
        'Adductor Rockback' => 'Movilidad activa de aductores para el straddle; rango sin dolor',
        'Kneeling Hip Flexor Stretch' => 'Glúteo activado para estirar el flexor; la cadera cerrada es clave',
        'Wrist Prayer' => 'Movilidad activa sin dolor: mece suave hacia extensión',
        'Wrist Curl' => 'Rango completo suave; nunca al fallo',
        'Reverse Wrist Curl' => 'El seguro del codo: extensores fuertes equilibran los soportes',
        'Wrist Push-Up' => 'Extensión de muñeca bajo carga progresiva; rango sin dolor',
        'Shoulder Dislocate' => 'Movilidad de hombro con banda/palo; rango cómodo y creciente',
        'Wall Slide' => 'Escápulas activas contra la pared; sin dolor de hombro',
        'Face Pull' => 'Hombro sano: escápulas que rotan, codos altos',
        'Jefferson Curl' => 'Movilidad de cadena posterior con carga mínima; redondeo controlado',
        'Chest to Wall Handstand' => 'Línea estricta, dedos activos empujando el suelo',
        'Handstand Hold' => 'Hombros empujados al suelo, glúteos y abdomen activos',
        'Arch Hold' => 'Contrapeso del hollow: extensión activa, sin dolor lumbar',
        'Diaphragmatic Breathing' => 'Respiración diafragmática para recuperar; abdomen en cada exhalar',
    ];
}
