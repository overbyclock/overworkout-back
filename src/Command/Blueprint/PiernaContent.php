<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Pierna Mastery.
 * Especialización del Master v4: la pieza de tren inferior que faltaba.
 * Consejos tomados de las sesiones C de N3-N5 (pistol asistida y box pistol),
 * hito de pistol del N5, test N10 (pistol 3/pierna), N12-N13 (dragon pistol,
 * shrimp y Nordic curl 3 como mínimo de test) y rangos N16/Olimpo.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 *
 * Reglas tendinosas §1.7 aplicadas a rodilla y tobillo:
 * - Molestia rotuliana = regresión inmediata un peldaño (pistol -> box pistol).
 * - El Nordic se construye con excéntrica primero, nunca forzando la concéntrica.
 * - La movilidad de tobillo es puerta, no opción: sin ella el pistol se rompe.
 */
class PiernaContent
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
            3 => self::EXERCISE_NOTES_L3,
            5 => self::EXERCISE_NOTES_L5,
            6 => self::EXERCISE_NOTES_L6,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Pierna a Una Pierna
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Los básicos bilaterales mandan: sentadilla, lunge y step-up con técnica impecable antes de tocar el pistol.',
                'Fase 2 (Progresión): Aumenta reps de sentadilla y lunge. La rodilla viaja alineada con el pie, nunca hacia dentro.',
                'Fase 3 (Intensificación): Volumen máximo de base. Pie completo en el suelo: la presión repartida entre talón y metatarsianos.',
                'Fase 4 (Recuperación): Descarga de pierna con movilidad suave de tobillo y cadera.',
                'Los ciclos se repiten hasta superar el test. Sin prisa: la rodilla madura lento y castiga las prisas.',
                '⚠️ Regla tendinosa §1.7: molestia rotuliana = regresión inmediata un peldaño y revisa la técnica.',
                'Dolor articular ≠ molestia muscular: cualquier dolor punzante en rodilla detiene el ejercicio y se retrocede un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Base de fuerza bilateral y unilateral', 'note' => 'Air squat 3×15-20, stationary lunge 3×8/lado, high step up 3×8/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar reps de los básicos', 'note' => 'Air squat 3×20-25, lunge 3×10/lado, wall sit 2×30-40 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Air squat 4×25-30, lunge 4×12/lado, high step up 4×10-12/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de tobillo', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Air Squat', 'minimum' => 20, 'target' => 30, 'unit' => 'repeticiones', 'form' => 'Rango completo, rodilla alineada con el pie, pie completo en el suelo'],
                        ['name' => 'Stationary Lunge', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Por lado, tronco erguido, rodilla trasera sin apoyarse'],
                        ['name' => 'Single Leg Romanian Deadlift', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Por lado, cadera atrás, espalda neutra, sin apoyos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Pistol Asistido
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El box pistol al cajón alto es el pistol asistido: asistencia decreciente (cajón bajo/punta de dedos).',
                'Fase 2 (Progresión): Baja el cajón y sube el step-up. La movilidad de tobillo es puerta, no opción: sin ella el pistol se rompe.',
                'Fase 3 (Intensificación): Box pistol 8 y step-up alto 12. La asistencia se recorta por semana, nunca se fuerza.',
                'Fase 4 (Recuperación): Vuelve a sentadillas y movilidad suave de tobillo.',
                'En el pistol, la rodilla delantera viaja sobre el pie, no hacia dentro: la alineación lo es todo.',
                'Los rocks de rodilla a pared miden tu tobillo: si no llega, el pistol se romperá antes de tiempo.',
                '⚠️ Regla tendinosa §1.7: molestia rotuliana = regresión inmediata un peldaño (box pistol alto) y revisa la movilidad de tobillo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Box pistol asistido y step-up alto', 'note' => 'Box pistol 3×5-6/lado, high step up 3×10/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Asistencia decreciente', 'note' => 'Box pistol 3×6-7/lado, pistol negative 2×3/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen', 'note' => 'Box pistol 4×8/lado, high step up 4×12/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de tobillo', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Box Pistol Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Por lado, cajón alto, asistencia decreciente (cajón bajo/punta de dedos)'],
                        ['name' => 'High Step Up', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Por lado, cajón alto, empuja con la pierna de arriba sin impulso'],
                        ['name' => 'Knee-to-Wall Rocks', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Por lado, talón pegado al suelo, rodilla alineada con el segundo dedo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Primer Pistol
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El pistol asistido mínimo (puntas de dedos) prepara el primer pistol: la asistencia es solo equilibrio, no fuerza.',
                'Fase 2 (Progresión): Primer pistol completo: cadera atrás, pie completo, rodilla alineada. Hito del N5 del Master.',
                'Fase 3 (Intensificación): 2-3 pistols y box pistol 8-10. La técnica manda: mejor 2 limpios que 4 bailados.',
                'Fase 4 (Recuperación): Regresión activa: box pistol y movilidad de tobillo.',
                'Si el pistol se resiste, revisa el tobillo: rocks de rodilla a pared por debajo de 10 = vuelve a trabajar movilidad.',
                'El shrimp asistido entra como comodín: mismo patrón unilateral, menos demanda de movilidad de tobillo.',
                '⚠️ Regla tendinosa §1.7: molestia rotuliana = regresión inmediata a box pistol un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Asistencia mínima y primer pistol', 'note' => 'Pistol squat 3×1/lado asistido, box pistol 3×5-6/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primer pistol completo (hito)', 'note' => 'Pistol squat 3×1-2/lado, pistol negative 2×4/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 2-3 pistols', 'note' => 'Pistol squat 4×2-3/lado, box pistol 3×8-10/lado, shrimp 2×2-3/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Box pistol, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4. El primer pistol es el hito del N5 del Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Pistol Squat', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Por lado, asistencia mínima (puntas de dedos), rango completo, rodilla alineada'],
                        ['name' => 'Box Pistol Squat', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Por lado, cajón bajo, control en la bajada'],
                        ['name' => 'Shrimp Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Por lado, asistido (mano en pared), rodilla trasera sin golpear el suelo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Pistol Sólido
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El pistol 3-5 por pierna es la marca del test N10 del Master: aquí se consigue de forma aislada.',
                'Fase 2 (Progresión): El shrimp asistido suma fuerza unilateral sin tanta demanda de tobillo: mismo número cada lado.',
                'Fase 3 (Intensificación): 5 pistols, shrimp 8 y primeras negativas de Nordic. Un solo pico de rodilla por fase.',
                'Fase 4 (Recuperación): La movilidad de tobillo es el seguro de vida del pistol: nunca la saltes.',
                'Las negativas de Nordic entran aquí: excéntrica primero, bajada de 4-5 s, rango corto y controlado.',
                'En el Nordic, el anclaje firme y la cadera extendida lo son todo: sin ellos no hay excéntrica, hay caída.',
                '⚠️ Regla tendinosa §1.7: molestia rotuliana = regresión inmediata un peldaño (box pistol) y revisa la alineación.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Pistol 3-5 por pierna', 'note' => 'Pistol squat 3×3-4/lado, shrimp 3×4-5/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Shrimp asistido', 'note' => 'Pistol squat 3×4-5/lado, shrimp 3×5-6/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 5 pistols y Nordic negative', 'note' => 'Pistol squat 4×5/lado, shrimp 4×6-8/lado, nordic negative 3×2-3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Box pistol, movilidad de tobillo', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5. El pistol 3/pierna es el mínimo del test N10 del Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Por lado, estricto, rango completo, rodilla alineada con el pie'],
                        ['name' => 'Shrimp Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Por lado, asistido, control en la bajada'],
                        ['name' => 'Nordic Curl Negative', 'minimum' => 2, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Bajada controlada de 4-5 s, cadera extendida, anclaje firme'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Pistol Volumen
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El dragon pistol entra con asistencia de cajón: la pierna libre va por delante, no cae al suelo.',
                'Fase 2 (Progresión): Pistol 5-8 con volumen real: series completas, descanso completo entre series.',
                'Fase 3 (Intensificación): 8 pistols, dragon pistol 5 al cajón y 5 negativas de Nordic de 5 s (dosis N12 del Master).',
                'Fase 4 (Recuperación): Nordic y movilidad suaves: el isquio agradece la descarga.',
                'El dragon pistol al cajón exige más movilidad de cadera que el pistol: si no llega, quédate en pistol y shrimp.',
                'Las negativas de Nordic se dosifican: 4-6 excéntricas totales por sesión, la carga la marca el rango.',
                '⚠️ Regla tendinosa §1.7: molestia rotuliana = regresión inmediata un peldaño; el dragon pistol al cajón es la primera regresión.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Dragon pistol al cajón', 'note' => 'Pistol squat 3×5-6/lado, dragon pistol 3×2-3/lado al cajón', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Pistol 6-8 y Nordic negatives', 'note' => 'Pistol squat 3×6-8/lado, dragon pistol 3×3-5/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 8 pistols y 5 negativas', 'note' => 'Pistol squat 4×8/lado, dragon pistol 4×5/lado, nordic negative 4×5', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Nordic negatives suaves, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Pistol Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Por lado, estricto, pie completo, rodilla alineada'],
                        ['name' => 'Dragon Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Por lado, asistencia de cajón, pierna libre por delante sin apoyo'],
                        ['name' => 'Nordic Curl Negative', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Bajada de 5 s a rango casi completo, cadera extendida'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Primer Nordic
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Las negativas de Nordic de 5 s a rango total preparan el primer estricto: la excéntrica construye el mismo camino.',
                'Fase 2 (Progresión): El Nordic con banda es el puente: asistencia mínima y cadera extendida en todo el recorrido.',
                'Fase 3 (Intensificación): 3 estrictos (marca de test del N13 del Master) y pistol 8-10. Un solo pico de isquio por fase.',
                'Fase 4 (Recuperación): Volumen mínimo de Nordic; el pistol se mantiene con series cortas.',
                'En el Nordic, el anclaje firme y la cadera extendida lo son todo: si la cadera flexiona, la serie ha terminado.',
                'El primer Nordic estricto se gana fresca: hazlo al principio de la sesión C, nunca al final.',
                '⚠️ Regla tendinosa §1.7: el Nordic castiga los isquios: ante cualquier tirón, vuelve a negativas un ciclo completo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Nordic negatives a rango total', 'note' => 'Nordic curl negative 3×3-4, nordic curl with band 3×3-5', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primer Nordic estricto', 'note' => 'Nordic curl 3×1-2, nordic negative 3×4', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 3 estrictos (hito N13)', 'note' => 'Nordic curl 4×3, pistol squat 3×8-10/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Nordic negatives suaves, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7. El Nordic curl 3 es el mínimo del test N13 del Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Nordic Curl', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Estricto, anclaje firme, cadera extendida, sin impulso'],
                        ['name' => 'Pistol Squat', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Por lado, estricto, rango completo'],
                        ['name' => 'Dragon Pistol Squat', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Por lado, asistencia de cajón, control en la bajada'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Fuerza Unilateral Pesada
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Nordic 3-5 estrictos con descanso completo: la serie se gana fresca o no se gana.',
                'Fase 2 (Progresión): Dragon pistol 5-8 y RDL a una pierna 15-18: fuerza unilateral pesada.',
                'Fase 3 (Intensificación): 5 Nordic, RDL 18-20 y dragon pistol 8. Pico de fuerza del programa.',
                'Fase 4 (Recuperación): El Copenhagen y la movilidad son el seguro de vida: mantenlos incluso en deload.',
                'El RDL a una pierna pesado construye los isquios que el Nordic no alcanza: son complementarios, no rivales.',
                'Si el Nordic se estanca, revisa el rango: mejor 3 estrictos completos que 5 a medias.',
                '⚠️ Regla tendinosa §1.7: a esta altura la rodilla carga mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Nordic 3-4 estrictos', 'note' => 'Nordic curl 3×3-4, RDL 3×15-18/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Nordic 4-5 y dragon pistol', 'note' => 'Nordic curl 3×4-5, dragon pistol 3×5-8/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 5 Nordic y RDL 18-20', 'note' => 'Nordic curl 4×5, RDL 3×18-20/lado, pistol 3×10-12/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Copenhagen y movilidad mantenidos', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Nordic Curl', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Estrictos, cadera extendida, descanso completo entre series'],
                        ['name' => 'Pistol Squat', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Por lado, estricto, pie completo, rodilla alineada'],
                        ['name' => 'Single Leg Romanian Deadlift', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Por lado, cadera atrás, espalda neutra, con carga'],
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
                'Fase 1 (Base): Nordic 5-8 y pistol 12-15: rangos de N16/Olimpo del Master. Volumen real con técnica impecable.',
                'Fase 2 (Progresión): Dragon pistol 3-5 libre y RDL 20-22: la fuerza unilateral élite se consolida.',
                'Fase 3 (Intensificación): 8 Nordic, 15 pistols y dragon pistol 5. Pico máximo del programa.',
                'Fase 4 (Recuperación): El bloque D (core y movilidad) es el seguro de vida: mantenlo incluso en deload.',
                'El dominio del tren inferior no es un destino: es la plataforma para todo lo que venga después.',
                'Aquí la rodilla y el tobillo ya no son la limitación: la técnica y la paciencia lo son.',
                '⚠️ Regla tendinosa §1.7: a esta altura del programa la rodilla carga mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Nordic 5-6 y pistol 12-15', 'note' => 'Nordic curl 3×5-6, pistol squat 3×12-15/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Nordic 6-8 y dragon pistol libre', 'note' => 'Nordic curl 3×6-8, dragon pistol 3×3-5/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: 8 Nordic y 15 pistols', 'note' => 'Nordic curl 4×8, pistol squat 4×15/lado, RDL 3×20-22/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Core y movilidad mantenidos, volumen mínimo', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de tobillo']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Nordic Curl', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Estrictos, anclaje firme, cadera extendida, sin impulso'],
                        ['name' => 'Dragon Pistol Squat', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Por lado, libre o asistencia mínima, pierna libre por delante'],
                        ['name' => 'Pistol Squat', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Por lado, estricto, rango completo, rodilla alineada'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L3 = [
        'Pistol Squat' => 'Asistencia mínima (puntas de dedos): es solo equilibrio, no fuerza; baja el cajón cada semana',
        'Shrimp Squat' => 'Asistido con mano en pared: rodilla trasera sin golpear el suelo',
    ];

    private const EXERCISE_NOTES_L5 = [
        'Dragon Pistol Squat' => 'Asistencia de cajón: la pierna libre va por delante, no cae al suelo',
    ];

    private const EXERCISE_NOTES_L6 = [
        'Nordic Curl' => 'Anclaje firme, cadera extendida: si la cadera flexiona, la serie ha terminado',
        'Nordic Curl with Band' => 'Asistencia mínima: la banda solo ayuda en la subida, la bajada es excéntrica',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Box Pistol Squat' => 'Asistencia decreciente: cajón bajo/punta de dedos; baja el cajón cada semana',
        'Pistol Squat' => 'Pie completo en el suelo, rodilla alineada con el segundo dedo, cadera atrás',
        'Pistol Squat Negative' => 'Bajada de 4-5 s controlada: la excéntrica construye el pistol',
        'Shrimp Squat' => 'Mismo número cada lado: simetría estricta',
        'Dragon Pistol Squat' => 'Pierna libre por delante: si toca el suelo, sube el cajón',
        'Nordic Curl' => 'Anclaje firme, cadera extendida, sin impulso: la serie se gana fresca',
        'Nordic Curl Negative' => 'Bajada de 4-5 s, cadera extendida: la excéntrica primero, siempre',
        'Single Leg Romanian Deadlift' => 'Cadera atrás, espalda neutra, pie completo: el RDL unilateral construye los isquios del Nordic',
        'High Step Up' => 'Empuja con la pierna de arriba sin impulso: el step-up alto es la escalera del pistol',
        'Copenhagen Plank' => 'Cadera alta y alineada: el Copenhagen protege la rodilla del pistol',
        'Knee-to-Wall Rocks' => 'Talón pegado al suelo, rodilla alineada con el segundo dedo: mide tu tobillo',
        'Wall Sit' => 'Rodilla alineada, espalda pegada a la pared: base isométrica del pistol',
        'Miniband Lateral Walk' => 'Cadera baja, banda tensa: los glúteos medios estabilizan la rodilla',
        'Diaphragmatic Breathing' => 'Respiración diafragmática 360°: recupera entre bloques',
    ];
}
