<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa One-Arm Pull-Up Mastery.
 * Especialización del Master v4: tracción unilateral élite.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class OneArmPullUpMasteryContent
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
            4 => self::EXERCISE_NOTES_L4,
            5 => self::EXERCISE_NOTES_L5,
            6 => self::EXERCISE_NOTES_L6,
            7 => self::EXERCISE_NOTES_L7,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Tracción
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Construye la base de tracción con dominadas estrictas, arquería ligera y filas pesadas. Nunca saltes el calentamiento de hombro y muñeca.',
                'Fase 2 (Progresión): Aumenta reps manteniendo la simetría en archer pull-up: lo que hagas con un brazo, con el otro.',
                'Fase 3 (Intensificación): Volumen máximo de base. Si el agarre falla antes que la espalda, termina la serie: caer de la barra es la lesión típica.',
                'Fase 4 (Recuperación): Descarga activa con dominadas suaves, hangs cortos y movilidad de hombro.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo o hombro = congela ese gesto un ciclo.',
                'Técnica OAP: agarre completo (pulgar cerrado), codo cerca del cuerpo durante todo el recorrido, no rotes el hombro hacia adentro.',
                'Dolor articular ≠ molestia muscular: cualquier dolor punzante en codo u hombro detiene el ejercicio y se retrocede un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Base de tracción', 'note' => 'Pull up 3×8-10, archer pull-up 3×2/lado, weighted ring row 3×10', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar reps de base', 'note' => 'Pull up 3×10-12, archer pull-up 3×2-3/lado, close grip pull-up 2×10', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Pull up 4×12, archer pull-up 4×3/lado, weighted ring row 4×12', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de hombro y muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Standard Pull Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra, sin kip'],
                        ['name' => 'Archer Pull Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Brazo extendido bloqueado, simetría estricta por lado'],
                        ['name' => 'Weighted Ring Row', 'minimum' => 10, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Pecho a las anillas, cuerpo en línea, lastre controlado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Fuerza Máxima Prono
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Introduce la dominada lastrada ligera. La técnica importa más que el peso: rango completo sin balanceo.',
                'Fase 2 (Progresión): Sube el lastre al 10-15% BW manteniendo 3-5 reps limpias. La fuerza máxima a dos brazos es la base del OAP.',
                'Fase 3 (Intensificación): Pico de fuerza a dos brazos del nivel. Descanso completo entre series (2 min).',
                'Fase 4 (Recuperación): Vuelve a dominadas con peso corporal y movilidad de codo.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en codo (epicondilo) = congela ese gesto un ciclo.',
                'Técnica OAP: inicia el pull con el codo apuntando al suelo, no abiertos; el hombro permanece abajo y atrás.',
                'Simetría estricta: anota reps por lado en archer pull-up y one-arm scapular pull.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Introducir lastre', 'note' => 'Weighted pull-up 3×3-4 (+10-15% BW), strict pull-up 3×6-8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Lastre creciente', 'note' => 'Weighted pull-up 4×3-5, chin up 3×8, L-sit pull-up 2×4-5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico de fuerza a dos brazos', 'note' => 'Weighted pull-up 4×5, strict pull-up 4×10, chin up 3×10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, German hang suave', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Weighted Pull Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => '+15% BW, rango completo, sin kip'],
                        ['name' => 'Archer Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Simetría estricta, brazo extendido bloqueado'],
                        ['name' => 'Pull Up', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Rango completo, pecho a barra'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Lastrada Sólida
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Lastrada sólida hacia +25% BW. El objetivo no es el ego, sino la fuerza medible que el Master exige en N13.',
                'Fase 2 (Progresión): Introduce el typewriter pull-up como primer gesto de tracción unilateral dinámica.',
                'Fase 3 (Intensificación): Pico de +25% BW ×3-5 y typewriter limpio. El colgado a un brazo se mantiene como skill.',
                'Fase 4 (Recuperación): German hang largo y muñeca: el codo agradece la descarga.',
                '⚠️ Regla tendinosa §1.7: dolor de codo anterior (epicondilo) es la señal de alarma del OAP mal dosificado: reduce volumen y refuerza extensores (reverse wrist curl).',
                'Técnica OAP: no abandones el agarre completo en dominadas lastradas; el pulgar cerrado protege el codo.',
                'Simetría estricta: en typewriter y archer, mismo número de reps a cada lado, mismo rango.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Lastre hacia +25% BW', 'note' => 'Weighted pull-up 4×3, weighted chin up 3×5-6, typewriter 3×2/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Typewriter y lastre', 'note' => 'Weighted pull-up 4×3-4, L-sit pull-up 3×5-6, typewriter 3×2-3/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: +25% BW ×3-5', 'note' => 'Weighted pull-up 5×3-5, weighted chin up 4×8, typewriter 4×3-4/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'German hang largo, muñeca, volumen reducido', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Weighted Pull Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => '+25% BW, rango completo, sin kip'],
                        ['name' => 'Typewriter Pull Up', 'minimum' => 2, 'target' => 4, 'unit' => 'repeticiones', 'form' => 'Pausa en cada lado, simetría estricta'],
                        ['name' => 'Dead Hang', 'minimum' => 10, 'target' => 20, 'unit' => 'segundos', 'form' => 'Un brazo, alternando, hombro relajado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Unilateral Asistida
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Primer patrón completo de one-arm pull-up con asistencia generosa (banda o puntas). El objetivo es el recorrido, no el peso.',
                'Fase 2 (Progresión): Aumenta asistencia mínima y negativas controladas de 5 s. La excéntrica construye el camino.',
                'Fase 3 (Intensificación): Pico: assisted 5/lado y negativa de 5-6 s. Un solo pico de codo por fase.',
                'Fase 4 (Recuperación): Vuelve a asistencia generosa, hangs suaves y prehab de codo.',
                '⚠️ Regla tendinosa §1.7: nunca fuerces una negativa si el codo u hombro se quejan.',
                'Técnica OAP: en la assisted, mantén el cuerpo recto sin girar; el codo pasa cerca del cuerpo.',
                'Simetría estricta: anota asistencia usada y reps por lado.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Assisted one-arm pull-up', 'note' => 'Assisted OAP 3×3/lado, negativa 3×5 s, archer 3×3-5/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Asistencia mínima y negativas', 'note' => 'Assisted OAP 3×4/lado, negativa 3×5-6 s, archer hold', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico de unilateral asistida', 'note' => 'Assisted OAP 4×5/lado, negativa 4×5-6 s, archer 3×5-6/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Asistencia generosa, German hang, muñeca', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Assisted One Arm Pull Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Por lado, asistencia mínima, cuerpo recto sin rotar'],
                        ['name' => 'One Arm Pull Up Negative', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Bajada controlada por lado, sin caída libre'],
                        ['name' => 'Weighted Pull Up', 'minimum' => 2, 'target' => 4, 'unit' => 'repeticiones', 'form' => '+30% BW, rango completo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Camino al OAC
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Camino al OAC con negativas supinas largas y parciales de rango. El parcial top half enseña la parte más dura del recorrido.',
                'Fase 2 (Progresión): Aumenta negativas a 5-6 s y parciales a 1-2/lado. La asistencia en OAC es mínima.',
                'Fase 3 (Intensificación): Pico: negativa supina 8 s y parcial top 2/lado. El lastre a dos brazos se mantiene pesado.',
                'Fase 4 (Recuperación): Vuelve a negativas cortas y parciales sin fatiga. German hang largo.',
                '⚠️ Regla tendinosa §1.7: si el codo interior (epicondilo medial) se molesta, aumenta reverse wrist curl y reduce volumen de OAC un ciclo.',
                'Técnica OAC: supino (palmas hacia ti), codo cerca del cuerpo, subida recta sin balanceo.',
                'Simetría estricta: alterna lado iniciador en cada serie de negativas y parciales.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Negativas supinas y parciales', 'note' => 'OAC negative 3×5 s, OAP parcial top 3×1/lado, assisted OAC 2×3/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar negativas y parciales', 'note' => 'OAC negative 3×5-6 s, OAP parcial top 3×1-2/lado, assisted OAC 2×4/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: negativa 8 s y parcial top 2/lado', 'note' => 'OAC negative 4×8 s, OAP parcial top 4×2/lado, assisted OAC 3×5/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Negativas cortas, parciales sin fatiga, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'One Arm Pull Up Negative', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Supino, por lado, bajada controlada sin caída'],
                        ['name' => 'One Arm Pull Up', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Parcial top half: de flexión completa a 90°, por lado'],
                        ['name' => 'Weighted Pull Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => '+40% BW, rango completo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Primer One-Arm Chin-Up
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El primer OAC es el hito del nivel. Practica en fresco, con descanso completo entre intentos.',
                'Fase 2 (Progresión): Aumenta OAC a 1-2/lado y mantén lastre a dos brazos con +45-50% BW.',
                'Fase 3 (Intensificación): Pico: OAC 2/lado y +50% BW ×1-3. El codo vive protegido por prehab.',
                'Fase 4 (Recuperación): Volumen mínimo; la negativa prona se practica sin carga completa.',
                '⚠️ Regla tendinosa §1.7: un OAC forzado con rotación del hombro puede lesionar el bíceps/codo: prioriza la técnica sobre la rep.',
                'Técnica OAC: no "arranques" desde el tendón; subida controlada y bajada igual de lenta.',
                'Simetría estricta: anota OAC por lado; si hay desequilibrio >1 rep, trabaja primero el lado débil.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer OAC', 'note' => 'OAC 4×1/lado, OAP negative 3×5 s, archer 3×5-8/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar OAC y lastre', 'note' => 'OAC 4×1-2/lado, weighted pull-up 4×2 (+45-50% BW), OAP negative 3×5-8 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: OAC 2/lado y +50% BW', 'note' => 'OAC 5×2/lado, weighted pull-up 5×2-3 (+50% BW), OAP negative 4×8 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'OAC 2×1/lado, negativas cortas, German hang', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'One Arm Chin Up', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Por lado, supino, rango completo sin asistencia'],
                        ['name' => 'Weighted Pull Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => '+50% BW, rango completo'],
                        ['name' => 'Archer Pull Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Por lado, brazo extendido bloqueado'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: OAC → OAP
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Transferencia OAC → OAP mediante negativas prona lentas y asistencia mínima al OAP.',
                'Fase 2 (Progresión): Aumenta OAC a 3/lado y negativas prona de 10-12 s. La asistencia al OAP es mínima.',
                'Fase 3 (Intensificación): Pico: OAC 3-5/lado y negativa prona 15 s. El lastre +50% BW se mantiene.',
                'Fase 4 (Recuperación): Vuelve a OAC técnico y negativas cortas. Prehab de codo intenso.',
                '⚠️ Regla tendinosa §1.7: la transición de supino a prono aumenta la carga en codo: monitoriza epicondilo medial y lateral.',
                'Técnica OAP: prono (palmas hacia afuera), codo cerca del cuerpo, evita rotar el torso hacia el lado de trabajo.',
                'Simetría estricta: si un lado tiene 3 OAC y el otro 1, congela el lado fuerte un ciclo hasta equilibrar.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Transferencia OAC → OAP', 'note' => 'OAC 4×2-3/lado, OAP negative 3×10 s, assisted OAP 2×1-2/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Negativas prona 10-12 s', 'note' => 'OAC 4×3/lado, OAP negative 3×10-12 s, assisted OAP 2×2/lado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: OAC 3-5 y negativa 15 s', 'note' => 'OAC 5×3-5/lado, OAP negative 4×15 s, assisted OAP 3×2/lado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'OAC técnico 2×1-2/lado, negativas cortas, prehab', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'One Arm Chin Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Por lado, supino, rango completo'],
                        ['name' => 'One Arm Pull Up Negative', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Prono, por lado, bajada controlada'],
                        ['name' => 'Weighted Pull Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => '+50% BW, rango completo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8: One-Arm Pull-Up
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): One-arm pull-up completo con volumen real: 1-3 frescas por lado, descanso completo (3-4 min).',
                'Fase 2 (Progresión): Aumenta OAP a 1-2/lado y OAC a 5/lado. El lastre +50% BW se mantiene como seguro de fuerza.',
                'Fase 3 (Intensificación): Pico del programa: OAP 3/lado, OAC 5/lado y +50% BW ×5.',
                'Fase 4 (Recuperación): El bloque C (prehab) es el seguro de vida: mantenlo incluso en deload.',
                '⚠️ Regla tendinosa §1.7: a esta altura del programa el codo y hombro cargan mucho volumen: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
                'Técnica OAP: agarre completo, codo cerca del cuerpo, torso recto, bajada tan controlada como la subida.',
                'Simetría estricta: lo que hagas con un brazo, con el otro. Anota cada intento por lado.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'OAP con volumen', 'note' => 'OAP 4×1/lado, OAC 3×3-5/lado, typewriter 2×2/lado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar OAP y OAC', 'note' => 'OAP 4×1-2/lado, OAC 3×5/lado, weighted pull-up 4×2-3 (+50% BW)', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico del programa', 'note' => 'OAP 5×3/lado, OAC 4×5/lado, weighted pull-up 5×3-5 (+50% BW)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'OAP 2×1/lado, OAC 2×3/lado, prehab completo', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de hombro y muñeca']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'One Arm Pull Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Por lado, prono, rango completo sin asistencia'],
                        ['name' => 'One Arm Chin Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Por lado, supino, rango completo'],
                        ['name' => 'Weighted Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => '+50% BW, rango completo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L3 = [
        'Dead Hang' => 'Un brazo, alternando en cada serie; hombro relajado, respiración continua',
    ];

    private const EXERCISE_NOTES_L4 = [
        'One Arm Pull Up Negative' => 'Bajada controlada 5 s por lado; sin caída libre ni rotación del torso',
        'Archer Pull Up' => 'Hold 5-10 s en el lado extendido al final de cada rep alterna',
    ];

    private const EXERCISE_NOTES_L5 = [
        'One Arm Pull Up Negative' => 'Supino: bajada controlada 5 s por lado',
        'One Arm Pull Up' => 'Parcial top half: de flexión completa a 90° de extensión de codo, por lado',
    ];

    private const EXERCISE_NOTES_L6 = [
        'One Arm Pull Up Negative' => 'Bajada controlada 5-8 s por lado; mantén el codo cerca del cuerpo',
    ];

    private const EXERCISE_NOTES_L7 = [
        'One Arm Pull Up Negative' => 'Bajada controlada 10 s por lado, prono; torso recto sin rotar',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Dead Hang' => 'Un brazo, alternando; hombro relajado, baja antes de que el agarre falle',
        'Active Hang' => 'Escápulas retraídas y deprimidas, cuerpo quieto',
        'Scapular Pull Up' => 'A un brazo en los niveles avanzados: solo movimiento escapular, brazo extendido',
        'Standard Pull Up' => 'Rango completo, pecho a barra, sin kip',
        'Strict Pull Up' => 'Estricta, sin impulso, pausa breve arriba y abajo',
        'Chin Up' => 'Supino, rango completo, codo cerca del cuerpo',
        'Close Grip Pull Up' => 'Agarre estrecho, codos pegados al cuerpo',
        'L-Sit Pull Up' => 'Piernas extendidas o tucked en L, sin balanceo',
        'Weighted Pull Up' => 'Lastre controlado, rango completo, descanso completo entre series',
        'Weighted Chin Up' => 'Supino lastrado, codo cerca del cuerpo',
        'Weighted Ring Row' => 'Pecho a las anillas, cuerpo en línea, laste controlado',
        'Ring Row - Elevated Feet' => 'Pies elevados, cuerpo en línea, pecho a las anillas',
        'Archer Pull Up' => 'Simetría estricta, brazo extendido bloqueado, torso recto',
        'Typewriter Pull Up' => 'Pausa en cada lado, simetría estricta, control total',
        'Assisted One Arm Pull Up' => 'Asistencia mínima, cuerpo recto sin rotar, codo cerca',
        'Assisted One Arm Chin Up' => 'Asistencia mínima, supino, codo cerca del cuerpo',
        'One Arm Pull Up Negative' => 'Bajada controlada, sin caída libre, codo cerca del cuerpo',
        'One Arm Chin Up Negative' => 'Supino, bajada controlada, hombro estable',
        'One Arm Chin Up' => 'Supino, rango completo, sin asistencia, simetría estricta',
        'One Arm Pull Up' => 'Prono, rango completo, torso recto, codo cerca del cuerpo',
        'German Hang' => 'Salud de codo y hombro: hombro relajado, respiración continua',
        'Face Pull' => 'Codos altos, rotación externa, control en la vuelta',
        'Band Pull-Apart' => 'Brazos rectos, omóplatos juntos, control en la vuelta',
        'Wrist Curl' => 'Rango completo suave; nunca al fallo',
        'Reverse Wrist Curl' => 'El seguro del codo: extensores fuertes equilibran la tracción',
        'Wrist Roller' => 'Control total, sin balanceo; extensores y flexores de muñeca',
        'Wrist Prayer' => 'Movilidad activa sin dolor: mece suave hacia extensión',
        'Hollow Body Hold' => 'Lumbar pegado al suelo, piernas y hombros elevados',
        'Hanging Leg Raise' => 'Control del balanceo, piernas rectas o tucked',
        'Skin The Cat' => 'Movimiento lento y controlado, hombro relajado',
        'Tuck L-Sit' => 'Escápulas elevadas, rodillas juntas, control de 10-30 s',
        'Diaphragmatic Breathing' => 'Respiración diafragmática profunda para recuperación',
    ];
}
