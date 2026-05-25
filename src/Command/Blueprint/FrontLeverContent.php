<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Front Lever Mastery.
 * Tips, notas por fase, progresion y tests para cada nivel.
 * Estructura: 6 niveles, 4 fases (Base -> Progresion -> Intensificacion -> Recuperacion).
 */
class FrontLeverContent
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
    // LEVEL 1: Fundamentos de Traccion
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Construye agarre y escapulas. Active hang y scapular pulls son prioridad.',
                'Fase 2 (Progresion): Aumenta reps en Australian Pull Up. Core activo en todo momento.',
                'Fase 3 (Intensificacion): Maximo volumen de traccion. Introduce Tuck FL holds cortos.',
                'Fase 4 (Recuperacion): Descarga dedos y codos. German hang suave.',
                'Los ciclos se repiten hasta superar el test. Sin prisa: una base solida evita lesiones de codo.',
                '⚠️ Codo de tenista: Si sientes dolor en el exterior del codo, reduce volumen y masajea extensores.',
                'El core es 50% del front lever. Sin hollow body solido, la progresion se rompe.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Activacion escapular y agarre', 'note' => 'Active hang, scapular pulls, core basico', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar reps de traccion', 'note' => 'Australian PU 12-15 reps, plank 40s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Volumen maximo', 'note' => 'Standard pull-ups, tuck FL intro 3-5s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad hombros', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Active Hang 20s, Scapular Pull Up 8 reps, Australian PU 10 reps',
                'week1' => 'Progresion: Active Hang 30s, Scapular Pull Up 10 reps, Australian PU 12 reps',
                'week2' => 'Intensificacion: Active Hang 35s, Scapular Pull Up 12 reps, Standard PU 6 reps',
                'week3' => 'Recuperacion: Active Hang 20s, Scapular Pull Up 8 reps, Australian PU 8 reps',
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
                        ['name' => 'Active Hang', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Brazos rectos, omoplatos activados, hombros lejos de orejas'],
                        ['name' => 'Scapular Pull Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Omoplatos juntos al subir, control total'],
                        ['name' => 'Dead Hang', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Relajado pero activo, sin dolor de hombros'],
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
                'Fase 1 (Base): Tuck FL hold con espalda horizontal. Rodillas al pecho no al pecho alto.',
                'Fase 2 (Progresion): Tuck FL rows desarrollan traccion desde posicion horizontal.',
                'Fase 3 (Intensificacion): Maximo tiempo en tuck. Empuja omoplatos hacia abajo (depress).',
                'Fase 4 (Recuperacion): Descarga codos y munecas. German hang y movilidad.',
                'Los ciclos se repiten hasta superar el test. No avances si la espalda se redondea.',
                '⚠️ Si el codo interior duele (epitrocleitis), deten rows y trabaja solo holds.',
                'Standard pull-ups paralelos al tuck FL: necesitas fuerza de traccion vertical y horizontal.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introduccion al tuck FL', 'note' => 'Tuck FL hold 10-15s, rows 5-6 reps', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar tiempo y reps', 'note' => 'Tuck FL hold 15-20s, rows 8 reps', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Maximo hold y volumen', 'note' => 'Tuck FL hold 20-25s, rows 10 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga activa', 'note' => 'Tuck FL hold 10s, rows 5 reps', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Tuck FL Hold 10s, Tuck FL Row 5 reps, Standard PU 6 reps',
                'week1' => 'Progresion: Tuck FL Hold 15s, Tuck FL Row 8 reps, Standard PU 8 reps',
                'week2' => 'Intensificacion: Tuck FL Hold 20s, Tuck FL Row 10 reps, Chin Up 8 reps',
                'week3' => 'Recuperacion: Tuck FL Hold 10s, Tuck FL Row 5 reps, Standard PU 6 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Tuck Front Lever Hold', 'minimum' => 15, 'target' => 30, 'unit' => 'segundos', 'form' => 'Rodillas al pecho, espalda horizontal, omoplatos juntos, brazos rectos'],
                        ['name' => 'Tuck Front Lever Row', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Desde tuck FL, barra al pecho bajo, controlado'],
                        ['name' => 'Standard Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Advanced Tuck
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Abre rodillas a 90°. Manten espalda perfectamente horizontal.',
                'Fase 2 (Progresion): Advanced tuck rows exigen mas traccion. No dejes caer cadera.',
                'Fase 3 (Intensificacion): Tiempo maximo en advanced tuck. Visualiza el full FL.',
                'Fase 4 (Recuperacion): Deload activo. Back lever hold como compensacion.',
                'Los ciclos se repiten hasta superar el test. La apertura de rodillas es gradual.',
                '⚠️ Dolor lumbar: si aparece, revisa hollow body hold. El core debe estar prendido 100%.',
                'Wide grip pull-ups desarrollan fuerza de espalda ancha para sostener posiciones abiertas.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Apertura de tuck', 'note' => 'Advanced tuck hold 10-15s, rows 5-6 reps', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar tiempo', 'note' => 'Advanced tuck hold 15-20s, rows 8 reps', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Maximo tiempo', 'note' => 'Advanced tuck hold 20-25s, rows 10 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga', 'note' => 'Advanced tuck hold 10s, rows 5 reps', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Advanced Tuck FL Hold 10s, Advanced Tuck FL Row 5 reps, Wide Grip PU 5 reps',
                'week1' => 'Progresion: Advanced Tuck FL Hold 15s, Advanced Tuck FL Row 8 reps, Wide Grip PU 6 reps',
                'week2' => 'Intensificacion: Advanced Tuck FL Hold 20s, Advanced Tuck FL Row 10 reps, Wide Grip PU 8 reps',
                'week3' => 'Recuperacion: Advanced Tuck FL Hold 10s, Advanced Tuck FL Row 5 reps, Chin Up 6 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Advanced Tuck Front Lever Hold', 'minimum' => 15, 'target' => 25, 'unit' => 'segundos', 'form' => 'Rodillas separadas 90°, espalda horizontal, brazos rectos'],
                        ['name' => 'Advanced Tuck Front Lever Row', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Desde advanced tuck, barra al abdomen, omoplatos juntos'],
                        ['name' => 'Wide Grip Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Agarre amplio, pecho a barra, controlado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Straddle Front Lever
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Abre piernas en V desde advanced tuck. Flexibilidad de cadera es clave.',
                'Fase 2 (Progresion): Straddle rows desde posicion horizontal. Controla la inclinacion.',
                'Fase 3 (Intensificacion): One-leg front lever como puente. Alterna piernas.',
                'Fase 4 (Recuperacion): Recupera isquiotibiales y cadera. Dragon flag negatives suaves.',
                'Los ciclos se repiten hasta superar el test. El straddle acorta la palanca: aprovechalo.',
                '⚠️ Si los isquiotibiales se tensan, anade estiramientos de pierna trasera antes de entrenar.',
                'Dragon flag negative desarrolla el mismo patron de core que el front lever.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Apertura straddle', 'note' => 'Straddle FL hold 5-8s, one-leg FL 5s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar tiempo', 'note' => 'Straddle FL hold 8-10s, straddle rows 5 reps', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Maximo hold', 'note' => 'Straddle FL hold 10-15s, straddle rows 8 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga', 'note' => 'Straddle FL hold 5s, one-leg FL 3s', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Straddle FL Hold 5s, One Leg FL Hold 5s, Straddle FL Row 3 reps',
                'week1' => 'Progresion: Straddle FL Hold 8s, One Leg FL Hold 8s, Straddle FL Row 5 reps',
                'week2' => 'Intensificacion: Straddle FL Hold 12s, One Leg FL Hold 10s, Straddle FL Row 8 reps',
                'week3' => 'Recuperacion: Straddle FL Hold 5s, Advanced Tuck FL Hold 10s, Straddle FL Row 3 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad caderas y hombros']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Straddle Front Lever Hold', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Piernas abiertas en V, espalda horizontal, brazos rectos'],
                        ['name' => 'Straddle Front Lever Row', 'minimum' => 3, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Desde straddle FL, barra al abdomen, control total'],
                        ['name' => 'Wide Grip Pull Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Agarre amplio, controlado, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Full Front Lever
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Cuerpo recto completo. Omoplatos juntos, brazos rectos. Visualiza una tabla.',
                'Fase 2 (Progresion): Front lever raises desde dead hang. Controla la elevacion.',
                'Fase 3 (Intensificacion): Maximo hold libre. Full FL rows con rango completo.',
                'Fase 4 (Recuperacion): Deload activo. Manten el skill sin maximos.',
                'Los ciclos se repiten hasta superar el test. 10s de full FL es un hito enorme.',
                '⚠️ La traccion + front lever exige mucho codo. Nunca entrenar FL con dolor articular.',
                'Weighted chin-ups consolidan la fuerza de traccion que sostiene el full FL.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Full FL intro', 'note' => 'Full FL hold 3-5s, raises 3 reps, weighted chin-up', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar hold', 'note' => 'Full FL hold 5-8s, raises 5 reps', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Maximo hold y volumen', 'note' => 'Full FL hold 8-10s, raises 8 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Descarga', 'note' => 'Full FL hold 3-5s, raises 3 reps', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Full FL Hold 3s, Front Lever Raise 3 reps, Weighted Chin Up 3 reps',
                'week1' => 'Progresion: Full FL Hold 5s, Front Lever Raise 5 reps, Weighted Chin Up 5 reps',
                'week2' => 'Intensificacion: Full FL Hold 8s, Front Lever Raise 8 reps, Weighted Chin Up 6 reps',
                'week3' => 'Recuperacion: Full FL Hold 3s, Front Lever Raise 3 reps, Full FL Row 5 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Full Front Lever Hold', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal, omoplatos juntos, brazos rectos, sin banda'],
                        ['name' => 'Full Front Lever Row', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Desde full FL, barra al abdomen bajo, control total'],
                        ['name' => 'Front Lever Raise', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Desde dead hang, eleva hasta full FL, baja controlado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Dominio del Front Lever
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Consistencia. Full FL hold 20s+. Transiciones suaves.',
                'Fase 2 (Progresion): Front lever pulls desde dead hang. Fuerza dinamica.',
                'Fase 3 (Intensificacion): Shapes: tuck FL -> full FL -> straddle FL. Combos.',
                'Fase 4 (Recuperacion): Freestyle. Disfruta el dominio. Grabar video ayuda.',
                'Los ciclos se repiten hasta superar el test. 30s de full FL = maestria.',
                '⚠️ Si llegaste aqui, eres un atleta de front lever. Los siguientes programas: Human Flag Mastery o Planche Mastery.',
                'La calidad supera al tiempo. Mejor 30s perfecto que 45s con cadera baja.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Consistencia', 'note' => 'Full FL hold 20s, pulls 5 reps, combos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Front lever pulls', 'note' => 'Full FL hold 25s, pulls 8 reps', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Shapes y combos', 'note' => 'Full FL hold 30s, raises 10 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Disfrute', 'note' => 'Full FL hold 15s, pulls 5 reps', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Full FL Hold 20s, Front Lever Raise 8 reps, Full FL Row 8 reps',
                'week1' => 'Progresion: Full FL Hold 25s, Front Lever Raise 10 reps, Archer Pull Up 5 reps',
                'week2' => 'Intensificacion: Full FL Hold 30s, Front Lever Raise 12 reps, Full FL Row 10 reps',
                'week3' => 'Recuperacion: Full FL Hold 15s, Front Lever Raise 6 reps, Full FL Row 6 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluacion Final',
                'description' => 'Evaluacion final del programa Front Lever Mastery',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6 - GRADUACION',
                    'description' => 'Dominio completo del front lever',
                    'requirements' => [
                        ['name' => 'Full Front Lever Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Cuerpo recto horizontal perfecto, omoplatos juntos, brazos rectos, sin oscilacion'],
                        ['name' => 'Front Lever Raise', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Desde dead hang hasta full FL, sin impulso, control total'],
                        ['name' => 'Full Front Lever Row', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Desde full FL, barra al abdomen bajo, omoplatos juntos, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // EXERCISE NOTES
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Active Hang' => 'Omoplatos activados, hombros lejos de orejas. No cuelgues pasivo.',
        'Scapular Pull Up' => 'Junta omoplatos antes de doblar codos. Movimiento puro de escapula.',
        'Australian Pull Up' => 'Cuerpo recto, pecho a barra. Regulacion de inclinacion con pies.',
        'Standard Pull Up' => 'Rango completo, pecho a barra, controlado.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Hombros y piernas elevados.',
        'Dead Bug' => 'Lumbar pegado. Movimientos opuestos lentos.',
        'High Plank' => 'Cuerpo recto, core apretado, codos debajo hombros.',
        'German Hang' => 'Relajacion pasiva en anillas. Estira hombros y pecho.',
        'Skin The Cat' => 'Rotacion completa por detras. Controla el movimiento.',
        'Dead Hang' => 'Agarre relajado pero activo. Sin dolor de hombros.',
        'Tuck Front Lever Hold' => 'Rodillas al pecho, espalda horizontal. Omoplatos juntos.',
        'Inverted Hang Hold' => 'Colgado boca arriba, cuerpo recto. Preparacion para entradas.',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Tuck Front Lever Hold' => 'Rodillas al pecho, espalda horizontal. Junta omoplatos.',
        'Tuck Front Lever Row' => 'Desde tuck FL, tira barra al pecho bajo. Omoplatos juntos.',
        'Standard Pull Up' => 'Rango completo. Pecho a barra sin kip.',
        'Chin Up' => 'Palmas hacia ti. Enfasis en biceps y espalda baja.',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Advanced Tuck Front Lever Hold' => 'Rodillas separadas 90°. Espalda horizontal. Brazos rectos.',
        'Advanced Tuck Front Lever Row' => 'Desde advanced tuck FL, tira barra al pecho. Omoplatos juntos.',
        'Wide Grip Pull Up' => 'Agarre amplio. Enfasis en dorsal ancho.',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Active Hang' => 'Omoplatos activados, hombros lejos de orejas. No cuelgues pasivo.',
        'Dead Hang' => 'Agarre relajado pero activo. Sin dolor de hombros.',
        'Scapular Pull Up' => 'Junta omoplatos antes de doblar codos. Movimiento puro de escapula.',
        'Australian Pull Up' => 'Cuerpo recto, pecho a barra. Regulacion de inclinacion con pies.',
        'Standard Pull Up' => 'Rango completo, pecho a barra, sin kip.',
        'Chin Up' => 'Palmas hacia ti. Enfasis en biceps y espalda baja.',
        'Wide Grip Pull Up' => 'Agarre amplio. Enfasis en dorsal ancho.',
        'Close Grip Pull Up' => 'Agarre cerrado. Enfasis en biceps y espalda baja.',
        'Archer Pull Up' => 'Un brazo se extiende lateralmente. Fuerza unilateral de espalda.',
        'Weighted Chin Up' => 'Anade peso controlado. Rango completo, sin balanceo.',
        'Tuck Front Lever Hold' => 'Rodillas al pecho, espalda horizontal. Omoplatos juntos, brazos rectos.',
        'Tuck Front Lever Row' => 'Desde tuck FL, tira barra al pecho bajo. Control total.',
        'Advanced Tuck Front Lever Hold' => 'Rodillas separadas 90°. Espalda horizontal. Brazos rectos.',
        'Advanced Tuck Front Lever Row' => 'Desde advanced tuck FL, tira barra al pecho. Omoplatos juntos.',
        'Straddle Front Lever Hold' => 'Piernas abiertas en V. Espalda horizontal, cadera alta.',
        'Straddle Front Lever Row' => 'Desde straddle FL, tira barra al abdomen. Control total.',
        'Full Front Lever Hold' => 'Cuerpo recto horizontal. Omoplatos juntos, brazos rectos.',
        'Full Front Lever Row' => 'Desde full FL, tira barra al abdomen bajo. Sin kip.',
        'Front Lever Raise' => 'Desde dead hang, eleva hasta full FL. Sin impulso, control total.',
        'One Leg Front Lever Hold' => 'Una pierna extendida, la otra en tuck. Transicion hacia full FL.',
        'Front Lever Negative' => 'Desde inversion, baja lentamente a front lever. Minimo 5 segundos.',
        'Ice Cream Maker' => 'Desde L-sit tuck en barra, eleva cadera hasta que la barra toque el abdomen. Extiende piernas a front lever y vuelve. Conexion vertical-horizontal.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Hombros y piernas elevados.',
        'Hollow Body Rock' => 'Balanceo desde hollow body sin tocar suelo. Core prendido.',
        'Dead Bug' => 'Lumbar pegado. Movimientos opuestos lentos.',
        'High Plank' => 'Cuerpo recto, core apretado, codos debajo hombros.',
        'Tuck L-Sit' => 'Rodillas al pecho, gluteos elevados. Brazos rectos, hombros bajados.',
        'L-Sit' => 'Piernas rectas elevadas. Espalda recta, hombros bajados.',
        'Dragon Flag Negative' => 'Bajada controlada desde vertical. Minimo 5 segundos.',
        'Dragon Flag' => 'Cuerpo recto. Baja en 5 segundos. Core maximo.',
        'Hollow Body Press' => 'Desde hollow body, presiona brazos hacia arriba. Compresion de core.',
        'Face Pull' => 'Tira hacia cara. Omoplatos juntos. Controlado.',
        'German Hang' => 'Relajacion pasiva en anillas. Estira hombros y pecho.',
        'Skin The Cat' => 'Rotacion completa por detras. Controla el movimiento.',
        'Back Lever Hold' => 'Cuerpo recto boca abajo. Omoplatos juntos, brazos rectos.',
        'Back Lever Row' => 'Desde back lever, tira barra al pecho. Control total.',
        'Human Flag' => 'Cuerpo horizontal en poste. Empuje y traccion combinados.',
        'Inverted Hang Hold' => 'Colgado boca arriba, cuerpo recto. Preparacion para entradas.',
    ];
}
