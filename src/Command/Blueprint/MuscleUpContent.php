<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Muscle Up Mastery.
 * Especializacion del Master v4: los consejos de codo y las progresiones
 * vienen del detalle de N8 (Potencia) y N9 (Muscle-Up) del Master.
 * Estructura: 6 niveles, 4 fases (Base -> Progresion -> Intensificacion -> Recuperacion).
 */
class MuscleUpContent
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
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getExerciseNote(int $levelNumber, string $exerciseName): ?string
    {
        $specific = match ($levelNumber) {
            2 => self::EXERCISE_NOTES_L2,
            3 => self::EXERCISE_NOTES_L3,
            5 => self::EXERCISE_NOTES_L5,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Traccion y Empuje
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Construye traccion estricta. La muscle-up se gana con dominadas perfectas, no con intentos.',
                'Fase 2 (Progresion): Aumenta reps en Pull Up y Dip. El straight bar dip te acostumbra al apoyo sobre la barra.',
                'Fase 3 (Intensificacion): Maximo volumen de traccion y empuje. Core fuerte: sin hollow body no hay MU limpia.',
                'Fase 4 (Recuperacion): Descarga codos y munecas. German hang suave y movilidad.',
                'Los ciclos se repiten hasta superar el test. Sin prisa: la base evita el dolor de codo mas adelante.',
                '⚠️ Dolor de codo: si aparece en fase de agarre, reduce volumen y trabaja munecas y extensores (Wrist Curl).',
                'El core es el 50% de la muscle-up. Si la cadera se queda atras, la transicion se bloquea.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Fuerza de traccion y empuje', 'note' => 'Pull Up 5-6 reps, Dip 8-10 reps, hollow body', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar reps', 'note' => 'Pull Up 7-8 reps, Dip 10-12 reps, SBD intro', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen maximo', 'note' => 'Pull Up 8-10 reps, Chest to Bar 3-5 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombros y munecas', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Pull Up 5 reps, Dip 8 reps, Hollow Body Hold 20s',
                'week1' => 'Progresion: Pull Up 7 reps, Dip 10 reps, Straight Bar Dips 5 reps',
                'week2' => 'Intensificacion: Pull Up 9 reps, Chest to Bar Pull Up 4 reps, Dip 12 reps',
                'week3' => 'Recuperacion: Pull Up 4 reps, Dip 6 reps, Hollow Body Hold 15s',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                        ['name' => 'Dip', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Brazos rectos arriba, hombros bajos, sin rebote'],
                        ['name' => 'Chest to Bar Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Pecho tocando la barra, sin impulso de cadera'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Piezas de la Muscle-Up
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El MU se gana en las piezas, no en los intentos. C2B alto + transicion + SBD por separado.',
                'Fase 2 (Progresion): Baja la asistencia de la banda en la transicion. Calidad antes que reps.',
                'Fase 3 (Intensificacion): Volumen tecnico. Cada rep de transicion debe ser limpia: codos rapidos, pecho alto.',
                'Fase 4 (Recuperacion): Descarga codos. Nada de transiciones pesadas en la semana de recuperacion.',
                'Los ciclos se repiten hasta superar el test. No intentes el MU completo aun: criterio de paso honesto.',
                '⚠️ Codo: si la parte interna del codo protesta, cambia banda por barra baja y reduce negativos.',
                'Criterio de paso: no intentes el MU completo hasta hacer C2B alto y transicion con banda con tecnica limpia.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Las tres piezas por separado', 'note' => 'Transicion asistida 3-5, negativos 2-3, SBD 6-8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Bajar asistencia', 'note' => 'Transicion con banda mas fina 4-6, C2B alto', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen tecnico', 'note' => 'Transicion 5-8, negativos 4-5, C2B 8-10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, falso agarre suave', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Transicion asistida 3 reps, MU negativo 2 reps, Straight Bar Dips 6 reps',
                'week1' => 'Progresion: Transicion asistida 4 reps, MU negativo 3 reps, C2B Pull Up 6 reps',
                'week2' => 'Intensificacion: Transicion asistida 6 reps, MU negativo 4 reps, Straight Bar Dips 10 reps',
                'week3' => 'Recuperacion: Transicion asistida 3 reps, MU negativo 2 reps, C2B Pull Up 4 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad hombros y munecas']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Chest to Bar Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Pecho a la barra, tirando alto, sin impulso de cadera'],
                        ['name' => 'Straight Bar Dips', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Cuerpo pegado a la barra, hombros bajos, brazos rectos arriba'],
                        ['name' => 'Muscle Up Negativo', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Bajada de 4s minimo por el camino de la transicion, control total'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Primera Muscle-Up
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Primeros intentos libres: frescos, espaciados y con descanso completo (3-4 min).',
                'Fase 2 (Progresion): Un intento mas por sesion. Si el pecho no supera la barra, vuelve a C2B alto esa serie.',
                'Fase 3 (Intensificacion): Pico del nivel: 5-6 intentos, cada uno plenamente recuperado. Meta: 1 rep limpia (minimo) -> 2 (objetivo).',
                'Fase 4 (Recuperacion): Si fallaron 3 seguidos en la fase anterior, cierra el ciclo con banda y recupera.',
                'Los ciclos se repiten hasta superar el test. La primera MU llega cuando las piezas estan dominadas.',
                '⚠️ Regla protectora: los intentos se hacen con descanso completo; si el tiempo aprieta, se recortan series, nunca el descanso.',
                '⚠️ El kipping desesperado es la primera causa de dolor de codo del programa. Construye con las piezas.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primeros intentos', 'note' => 'MU 4x1 con 3 min, transicion banda 3-5, negativos 3-4', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Mas intentos', 'note' => 'MU 5x1 con 3 min, banda fina 4-6', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Pico: intentos frescos', 'note' => 'MU 6x1 con 4 min, negativos 4-5', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'MU 2x1, solo tecnica con banda', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: MU 4x1 (3 min), Transicion banda 3 reps, MU negativo 3 reps',
                'week1' => 'Progresion: MU 5x1 (3 min), Transicion banda 4 reps, C2B Pull Up 10 reps',
                'week2' => 'Intensificacion: MU 6x1 (4 min), MU negativo 4 reps, Straight Bar Dips 10 reps',
                'week3' => 'Recuperacion: MU 2x1, Transicion banda 3 reps, MU negativo 2 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad', 'Nada de MU en frio']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Muscle Up', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Estricta o con kipping controlado, transicion clara, brazos rectos en el apoyo'],
                        ['name' => 'Chest to Bar Pull Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Pecho a la barra, sin impulso de cadera'],
                        ['name' => 'Straight Bar Dips', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Cuerpo pegado a la barra, control total'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Consistencia
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): De single a dobles. La segunda rep se gana en el apoyo: baja rapido pero con control.',
                'Fase 2 (Progresion): Dobles consistentes. Manten el descanso completo entre series de MU.',
                'Fase 3 (Intensificacion): Volumen unbroken. El kipping entra como opcion para series, nunca como sustituto del estricto.',
                'Fase 4 (Recuperacion): Deload activo. Recupera codos: muñecas, german hang y movilidad.',
                'Los ciclos se repiten hasta superar el test. La consistencia es un skill aparte: no todas las sesiones son para maximos.',
                '⚠️ Nunca dos dias seguidos de picos de MU. El codo necesita 48h entre sesiones duras.',
                'Graba tus series: la diferencia entre 3 MU limpias y 3 MU con comba es el riesgo de codo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'De single a dobles', 'note' => 'MU 4x1-2, Bar MU 1-2, negativos 3-4', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Dobles consistentes', 'note' => 'MU 5x1-2, Bar MU 2-3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen unbroken', 'note' => 'MU 5x2-3, Bar MU 3-5', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga', 'note' => 'MU 3x1, volumen reducido', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: MU 4x1-2, Bar Muscle Up 2 reps, Weighted Chin Up 5 reps',
                'week1' => 'Progresion: MU 5x2, Bar Muscle Up 3 reps, Chest to Bar 12 reps',
                'week2' => 'Intensificacion: MU 5x2-3, Bar Muscle Up 4 reps, MU negativo 5 reps',
                'week3' => 'Recuperacion: MU 3x1, Chest to Bar 6 reps, Weighted Chin Up 4 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Muscle Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Unbroken o 5 singles en menos de 10 min, tecnica limpia'],
                        ['name' => 'Muscle Up Negativo', 'minimum' => 4, 'target' => 6, 'unit' => 'repeticiones', 'form' => 'Bajada de 4s por la transicion, sin desplomarse'],
                        ['name' => 'Chest to Bar Pull Up', 'minimum' => 12, 'target' => 18, 'unit' => 'repeticiones', 'form' => 'Pecho a la barra, controlado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Muscle-Up en Anillas
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El falso agarre manda. Si el falso agarre falla, la ring MU no existe.',
                'Fase 2 (Progresion): Ring MU consistente. La transicion en anillas es mas larga: paciencia y codos altos.',
                'Fase 3 (Intensificacion): Dobles en anillas. Alterna barra y anillas en la misma sesion para transferir el skill.',
                'Fase 4 (Recuperacion): Descarga los flexores de muneca: llevan carga nueva con el falso agarre.',
                'Los ciclos se repiten hasta superar el test. Los anillos castigan la tecnica defectuosa: mas que la barra.',
                '⚠️ Las anillas se mueven: el balanceo hace el trabajo mas duro y el codo mas vulnerable. Descansos completos.',
                'Los straps deben quedar pegados a los antebrazos en el apoyo: eso es falso agarre completo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Falso agarre y transicion en anillas', 'note' => 'Ring MU Transition 3-5, Ring MU 1x3, falso agarre', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Ring MU consistente', 'note' => 'Ring MU 1-2x4, Bar MU volumen', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Dobles en anillas', 'note' => 'Ring MU 2-3x5, mix barra/anillas', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga munecas', 'note' => 'Volumen reducido, movilidad de munecas', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Ring MU Transition 3 reps, Ring MU 1 rep, False Grip Pull Up 5 reps',
                'week1' => 'Progresion: Ring MU 2 reps, Muscle Up 3 reps, Ring Pull Up - False Grip 5 reps',
                'week2' => 'Intensificacion: Ring MU 2-3 reps, Ring MU Transition 6 reps, Muscle Up 5 reps',
                'week3' => 'Recuperacion: Ring MU 1 rep, False Grip Ring Row 6 reps, movilidad',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad munecas y hombros', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Ring Muscle Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Con falso agarre, transicion completa, apoyo estable en anillas'],
                        ['name' => 'Muscle Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'En menos de 10 minutos, tecnica limpia'],
                        ['name' => 'False Grip Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Falso agarre mantenido todo el recorrido'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Dominio de la Muscle-Up
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Muscle-up lastrada. Poco lastre, mucha calidad: la tecnica no debe cambiar con peso.',
                'Fase 2 (Progresion): Sube el lastre y las formas: L-sit MU, agarres close y wide.',
                'Fase 3 (Intensificacion): Pico: lastre maximo con 2 reps, series de ring MU y variedades.',
                'Fase 4 (Recuperacion): Freestyle. Disfruta el dominio: la muscle-up burpee es la fiesta del programa.',
                'Los ciclos se repiten hasta superar el test. Has convertido el hito en una herramienta.',
                '⚠️ Con lastre, el codo se expone mas: calienta bien y no persigas records en dias frios.',
                'Siguientes pasos naturales: Muscle Up con dobles o el camino del anillo (iron cross via Olimpo del Master).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'MU lastrada', 'note' => 'MU lastrada 4x1, Ring MU 2-3, L-Sit MU 1-2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Mas lastre y L-sit', 'note' => 'MU lastrada 5x1-2, L-Sit MU 2-3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Pico de lastre y formas', 'note' => 'MU lastrada 5x2, Ring MU 5-8, variedades', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Disfrute', 'note' => 'Volumen libre suave, movilidad', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: MU lastrada 4x1, Ring Muscle Up 3 reps, Muscle Up to L-Sit 1 rep',
                'week1' => 'Progresion: MU lastrada 5x1-2, Ring Muscle Up 4 reps, L-Sit MU 2 reps',
                'week2' => 'Intensificacion: MU lastrada 5x2, Ring Muscle Up 6 reps, variedades close/wide',
                'week3' => 'Recuperacion: MU 3x1, Ring Muscle Up 2 reps, movilidad',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion Final',
                'description' => 'Evaluacion final del programa Muscle Up Mastery',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6 - GRADUACION',
                    'description' => 'Dominio completo de la muscle-up',
                    'requirements' => [
                        ['name' => 'Muscle Up lastrada', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Con 10-20% del peso corporal, tecnica identica a la sin lastre'],
                        ['name' => 'Muscle Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'En menos de 10 minutos, estrictas o kipping controlado'],
                        ['name' => 'Ring Muscle Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Con falso agarre, apoyo estable'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // EXERCISE NOTES
    // ========================================================================

    private const EXERCISE_NOTES_L2 = [
        'Assisted Muscle Up Transition' => 'La pieza central entrenada de forma aislada. Banda fina o barra baja: pasar de colgado a apoyo con codos rapidos.',
        'Muscle Up Negative' => 'Desde el apoyo sobre la barra, baja 4s por el camino de la transicion. Fuerza excentrica del MU.',
        'Chest to Bar Practice' => 'Dominadas explosivas buscando pecho alto sobre la barra. Sin cadera perezosa.',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Muscle Up' => 'Intentos frescos y espaciados (3-4 min). Codo rapido sobre la barra, pecho alto, cadera cruzando pronto.',
        'Assisted Muscle Up Transition' => 'Banda fina: quita solo el 20-30% del peso. Refina el momento exacto del giro de munecas.',
    ];

    private const EXERCISE_NOTES_L5 = [
        'Ring Muscle Up Transition' => 'En anillas la transicion es mas larga. Codos altos y adelantados antes de empujar.',
        'Ring Muscle Up' => 'Falso agarre completo, straps pegados al antebrazo. Apoyo estable: anillas quietas.',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Pull Up' => 'Rango completo, pecho a barra, sin kip.',
        'Standard Pull Up' => 'Rango completo, pecho a barra, sin kip.',
        'Chin Up' => 'Palmas hacia ti. Enfasis en biceps y espalda baja.',
        'Weighted Chin Up' => 'Anade peso controlado. Rango completo, sin balanceo.',
        'Archer Pull Up' => 'Un brazo se extiende lateralmente. Fuerza unilateral de espalda.',
        'Australian Pull Up' => 'Cuerpo recto, pecho a barra. Regulacion de inclinacion con pies.',
        'Chest to Bar Pull Up' => 'Pecho tocando la barra. Sin impulso de cadera.',
        'Chest to Bar Practice' => 'Dominada explosiva: busca altura, pecho por encima de la barra.',
        'Scapular Pull Up' => 'Junta omoplatos antes de doblar codos. Movimiento puro de escapula.',
        'Active Hang' => 'Omoplatos activados, hombros lejos de orejas. No cuelgues pasivo.',
        'Dead Hang' => 'Agarre relajado pero activo. Sin dolor de hombros.',
        'Jumping Pull Up' => 'Usa la piernas para saltar y termina con los codos. Controla la bajada.',
        'Kipping Pull Up' => 'Swing controlado desde los hombros, no desde la lumbar.',
        'Dip' => 'Brazos rectos arriba, hombros bajos, torso inclinado ligero.',
        'Ring Dip' => 'Anillas quietas, hombros bajos, brazos rectos arriba.',
        'Straight Bar Dips' => 'Fondo sobre la barra con el cuerpo pegado a ella. Puente mecanico al MU.',
        'Push Up' => 'Cuerpo recto, pecho al suelo, codos a 45 grados.',
        'Scapular Push Up' => 'Desplaza solo las escapulas al final del plank. Base del apoyo del MU.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Hombros y piernas elevados.',
        'Hanging Leg Raise' => 'Piernas rectas hasta la barra si puedes; si no, rodillas al pecho.',
        'L-Sit' => 'Piernas rectas elevadas. Espalda recta, hombros bajados.',
        'Wrist Curl' => 'Munecas sobre un banco, rango completo, peso ligero.',
        'Face Pull' => 'Tira hacia cara. Omoplatos juntos. Controlado.',
        'German Hang' => 'Relajacion pasiva en anillas. Estira hombros y pecho.',
        'Skin The Cat' => 'Rotacion completa por detras. Controla el movimiento.',
        'Muscle Up' => 'Traccion alta, transicion rapida de codos, apoyo firme. Descanso completo entre intentos.',
        'Strict Muscle Up' => 'Sin kip: traccion, transicion y empuje puramente estrictos.',
        'Bar Muscle Up' => 'MU en barra recta. El apoyo es mas exigente de triceps y hombro.',
        'Ring Muscle Up' => 'Con falso agarre. Transicion larga: codos altos antes de empujar.',
        'Ring Muscle Up Transition' => 'Solo la transicion en anillas, asistida si hace falta. Buscada con calma.',
        'Assisted Muscle Up Transition' => 'De colgado a apoyo con banda o barra baja. La pieza central del MU.',
        'Muscle Up Negative' => 'Desde apoyo, baja 4s por el camino de la transicion. Control total.',
        'Muscle Up Progression' => 'Secuencia completa asistida: traccion + transicion + empuje con soporte.',
        'Muscle Up to L-Sit' => 'Al llegar al apoyo, sube las piernas a L-sit antes de bajar.',
        'Muscle Up Burpee' => 'Potencia pura: MU + burpee bajo la barra. Ritmo alto, tecnica limpia.',
        'Muscle Up - Close' => 'MU con agarre cerrado. Mas biceps, transicion mas rapida.',
        'Muscle Up - Wide' => 'MU con agarre amplio. Mas espalda, transicion mas larga.',
        'False Grip Hang' => 'Colgado con el falso agarre: munecas sobre el borde, pecho abierto.',
        'False Grip Pull Up' => 'Dominada manteniendo el falso agarre. Base de la ring MU.',
        'False Grip Ring Row' => 'Remo en anillas con falso agarre. Codos pegados al cuerpo.',
        'False Grip Support Hold' => 'Apoyo en anillas con falso agarre. Straps pegados al antebrazo.',
        'Ring Pull Up - False Grip' => 'Dominada en anillas con falso agarre. Base de fuerza para la ring MU.',
    ];
}
