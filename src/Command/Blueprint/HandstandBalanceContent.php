<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Handstand Balance Mastery.
 * Tips, notas por fase, progresion y tests para cada nivel.
 * Estructura: 6 niveles, 4 fases (Base->Progresion->Intensificacion->Recuperacion).
 */
class HandstandBalanceContent
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
    // LEVEL 1: Confianza Invertida
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Aprende a estar boca abajo sin miedo. Headstand y frog stand son tu base.',
                'Fase 2 (Progresion): Aumenta tiempo en wall HS pecho. Nariz casi tocando pared, hombros abiertos.',
                'Fase 3 (Intensificacion): Mantén línea recta en wall HS. No arquees lumbar. Respira tranquilo.',
                'Fase 4 (Recuperacion): Volumen reducido. Explora invertido sin presión. Recupera muñecas.',
                'Los ciclos se repiten hasta superar el test. No hay calendario: avanza a tu ritmo.',
                '⚠️ Seguridad muñecas: Si sientes dolor agudo, detente 48h. Nunca más de 3 días seguidos.',
                'El pike push-up desarrolla fuerza de hombros para sostener el peso invertido.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Perder el miedo a invertido', 'note' => 'Headstand, frog stand, wall HS pecho cortos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Aumentar tiempo en pared', 'note' => 'Wall HS pecho 25-30s, headstand 30-40s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Línea recta y control', 'note' => 'Máximo tiempo, transiciones suaves, caídas controladas', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad muñecas y hombros', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Headstand 20s, Frog stand 15s, Wall HS pecho 15s',
                'week1' => 'Progresion: Headstand 30s, Frog stand 20s, Wall HS pecho 25s',
                'week2' => 'Intensificacion: Headstand 40s, Frog stand 25s, Wall HS pecho 30s',
                'week3' => 'Recuperacion: Headstand 20s, Frog stand 15s, Wall HS pecho 15s',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 2',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad suave', 'Visualizacion positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 1',
                    'description' => 'Debes superar los 2 tests para avanzar al Nivel 2',
                    'requirements' => [
                        ['name' => 'Wall Handstand Hold', 'minimum' => 60, 'target' => 75, 'unit' => 'segundos', 'form' => 'Pecho a pared, nariz casi tocando, linea recta, sin arquear'],
                        ['name' => 'Pike Push-up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Cabeza al suelo, controlado, sin arquear lumbar'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Alineacion en Pared
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Wall HS espalda a pared es diferente. Cambia la referencia visual. Practica sin miedo.',
                'Fase 2 (Progresion): El bail out es obligatorio. Sin él, tendrás miedo. Gira lateral, aterriza de pie.',
                'Fase 3 (Intensificacion): Shoulder taps en wall HS desarrollan control lateral. No dejes caer cadera.',
                'Fase 4 (Recuperacion): Recupera hombros y muñecas. Movilidad suave. Prepara el siguiente ciclo.',
                'Los ciclos se repiten hasta superar el test. El bail out debe ser automático y sin pensar.',
                '⚠️ Nunca más de 3 días seguidos. Si las muñecas duelen, descansa o usa parallettes en sesión C.',
                'Tucked hops al muro enseñan la entrada. Rodillas al pecho, patea suave, aterriza en pared.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Alineacion y seguridad', 'note' => 'Wall HS espalda 20s, bail out lento, wall walks', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Bail out fluido y alineacion', 'note' => 'Wall HS espalda 30s, bail out ambos lados, tucked hops', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Control lateral y entradas', 'note' => 'Shoulder taps, wall HS espalda 35s, tucked hops progresivos', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad hombros, bail out suave', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Wall HS espalda 20s, Bail out 2/side, Wall walk 2 reps',
                'week1' => 'Progresion: Wall HS espalda 30s, Bail out 3/side, Tucked hops 3 reps',
                'week2' => 'Intensificacion: Wall HS espalda 35s, Shoulder taps 5/side, Tucked hops 5 reps',
                'week3' => 'Recuperacion: Wall HS espalda 15s, Bail out 1/side, Tucked hops 2 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 3',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad hombros y muñecas', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 2',
                    'description' => 'Debes superar los 2 tests para avanzar al Nivel 3',
                    'requirements' => [
                        ['name' => 'Wall Handstand Hold', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Espalda a pared, linea recta, hombros abiertos cubriendo orejas'],
                        ['name' => 'Handstand Bail Out', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Caida controlada en pirueta desde wall HS espalda, sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Despegue de Pared
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Despegar un pie del muro es el primer paso. Mantén alineación, no gires cadera.',
                'Fase 2 (Progresion): Wall scissors alternan peso. Control total. No dejes caer hombros.',
                'Fase 3 (Intensificacion): Solo puntas tocando = casi libre. Empuja fuerte con manos. Respira.',
                'Fase 4 (Recuperacion): Descarga muñecas. Parallettes opcional. Movilidad suave.',
                'Los ciclos se repiten hasta superar el test. El despegue de pared requiere confianza, no solo fuerza.',
                '⚠️ One-leg kick-ups al muro preparan la entrada libre. Practica ambos lados por igual.',
                'Crow stand desarrolla equilibrio en manos sin pared. Concentración máxima.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Despegar pies del muro', 'note' => 'Un pie despegado 25s, wall scissors iniciales, crow stand', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Wall scissors y puntas', 'note' => 'Solo puntas 20s, scissors 10/side, one-leg kick-ups', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Casi libre en pared', 'note' => 'Solo puntas 25s, scissors rápidos, crow stand extendido', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, movilidad muñecas, parallettes opcional', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Un pie 25s, Puntas 15s, Scissors 8/side, Crow stand 15s',
                'week1' => 'Progresion: Un pie 30s, Puntas 20s, Scissors 10/side, Crow stand 20s',
                'week2' => 'Intensificacion: Un pie 35s, Puntas 25s, Scissors 12/side, Crow stand 25s',
                'week3' => 'Recuperacion: Un pie 20s, Puntas 10s, Scissors 5/side, Crow stand 15s',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 4',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad muñecas y hombros', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 3',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 4',
                    'requirements' => [
                        ['name' => 'Wall Handstand Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Pecho a pared, ambos pies despegados, solo puntas pueden rozar'],
                        ['name' => 'Wall Scissors', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Alternar pies despegados 10 veces por lado sin caer, control total'],
                        ['name' => 'Pike Push-up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Cabeza al suelo, controlado, fuerza de hombros mantenida'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Primeros Segundos Libres
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Kick-up a pared primero. Aterriza suave, hold 3s, bail out. Sin miedo.',
                'Fase 2 (Progresion): Intenta freestanding por 3-5 segundos. Caída controlada siempre.',
                'Fase 3 (Intensificacion): Máximo hold libre. Registra tu PR. Intenta 6 veces por sesión.',
                'Fase 4 (Recuperacion): Recupera. El sistema nervioso trabaja mucho en equilibrio. Descansa.',
                'Los ciclos se repiten hasta superar el test. 3s libres es un hito enorme. Celebra.',
                '⚠️ La caída es parte del proceso. Nunca caigas rígido. Gira, flexiona rodillas, rueda.',
                'Handstand walks en pared desarrollan control de peso sobre manos con movimiento.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Kick-up a pared controlado', 'note' => 'Kick-up a pared 5 reps, freestanding attempts 3s, walks pared', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Primeros segundos libres', 'note' => 'Kick-up a pared 8 reps, freestanding 5s, walks pared 5m', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Maximo hold libre', 'note' => 'Freestanding attempts máximos, 6 intentos, registrar PR', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, kick-ups suaves, movilidad completa', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Kick-up pared 5 reps, Freestanding 3s, Walks pared 3m',
                'week1' => 'Progresion: Kick-up pared 8 reps, Freestanding 5s, Walks pared 5m',
                'week2' => 'Intensificacion: Kick-up pared 10 reps, Freestanding 8s, Walks pared 8m',
                'week3' => 'Recuperacion: Kick-up pared 5 reps, Freestanding 3s, Walks pared 3m',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 5',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Visualizacion positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 2 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Kick-up to Handstand', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Kick-up, aterrizar en wall HS pecho, hold 3s, bail out controlado'],
                        ['name' => 'Freestanding Handstand Hold', 'minimum' => 3, 'target' => 5, 'unit' => 'segundos', 'form' => 'Sin pared, sin asistencia, linea recta, cualquier entrada valida'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Consistencia
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Consistencia = repetible. 3 entradas limpias seguidas. Sin caídas bruscas.',
                'Fase 2 (Progresion): Recuperación de equilibrio con dedos/palmas. Corrige desviaciones.',
                'Fase 3 (Intensificacion): Entrada straddle. Piernas abiertas al patear. Control total.',
                'Fase 4 (Recuperacion): Deload activo. Mantén el ritmo pero sin máximos.',
                'Los ciclos se repiten hasta superar el test. 10s libre = ya no es suerte, es skill.',
                '⚠️ HSPU en pared test fuerza de hombros. Sin fuerza, la alineación se rompe (banana back).',
                'Handstand walks libres desarrollan equilibrio dinámico. Empieza en pared, avanza.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Entradas consistentes', 'note' => 'Freestanding 10s, 3 entradas seguidas, recuperacion básica', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Recuperacion de equilibrio', 'note' => 'Freestanding 15s, recuperacion dedos/palmas, walks 12m', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Entrada straddle y volumen', 'note' => 'Freestanding 20s, straddle entries, walks 15m, HSPU 8 reps', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa', 'note' => 'Volumen reducido, entradas suaves, movilidad', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Freestanding 10s, 3 entries, Walks 10m, HSPU 5 reps',
                'week1' => 'Progresion: Freestanding 15s, Recuperacion 5 reps, Walks 12m, HSPU 6 reps',
                'week2' => 'Intensificacion: Freestanding 20s, Straddle entries 5 reps, Walks 15m, HSPU 8 reps',
                'week3' => 'Recuperacion: Freestanding 10s, Entries 3 reps, Walks 8m, HSPU 4 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluacion para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion adecuada', 'Dormir bien']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand Hold', 'minimum' => 10, 'target' => 15, 'unit' => 'segundos', 'form' => 'Sin pared, sin pasos, linea recta, sin caída'],
                        ['name' => 'Consecutive Entries', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => '3 entradas consecutivas libres con hold de 3s+ cada una'],
                        ['name' => 'Handstand Push Up', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'form' => 'Contra pared, rango completo, sin arquear, cabeza toca suelo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Forma y Estilo
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Shapes (tuck, straddle) requieren compresión de core. Rodillas al pecho fuerte.',
                'Fase 2 (Progresion): Transiciones entre shapes. Tuck → straddle sin caer. Control total.',
                'Fase 3 (Intensificacion): Handstand walks libres. Equilibrio en movimiento. Máxima distancia.',
                'Fase 4 (Recuperacion): Freestyle. Disfruta el proceso. Grabar vídeo ayuda a ver progreso.',
                'Los ciclos se repiten hasta superar el test. 30s libre + shapes = dominio del equilibrio.',
                '⚠️ Si llegaste aquí, eres un atleta de handstand. Los siguientes programas: HSPU Mastery y Press to HS.',
                'La calidad supera al tiempo. Mejor 30s perfecto que 45s banana back.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Shapes básicos', 'note' => 'Tuck 3s, straddle 3s, freestanding 25s, walks 15m', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresion', 'focus' => 'Transiciones de shapes', 'note' => 'Tuck→straddle, freestanding 30s, walks 18m, HSPU 12 reps', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificacion', 'focus' => 'Caminata libre y combos', 'note' => 'Freestyle combos, walks máximo recorrido, shapes 8s cada uno', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperacion', 'focus' => 'Recuperacion activa y disfrute', 'note' => 'Freestyle suave, movilidad, celebrar progreso', 'intensity' => '50%'],
            ],
            'progression' => [
                'week0' => 'Base: Freestanding 25s, Tuck 3s, Straddle 3s, Walks 15m, HSPU 10 reps',
                'week1' => 'Progresion: Freestanding 30s, Tuck 5s, Straddle 5s, Walks 18m, HSPU 12 reps',
                'week2' => 'Intensificacion: Freestanding 35s, Tuck 8s, Straddle 8s, Walks 20m, HSPU 15 reps',
                'week3' => 'Recuperacion: Freestanding 20s, Tuck 3s, Straddle 3s, Walks 10m, HSPU 6 reps',
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación Final',
                'description' => 'Evaluacion final del programa Handstand Balance Mastery',
                'preparation' => [
                    ['session' => '2-3 dias antes', 'activities' => ['Descanso completo', 'Movilidad completa', 'Nutricion adecuada', 'Dormir bien', 'Visualizacion positiva']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6 - GRADUACION',
                    'description' => 'Dominio completo del equilibrio en handstand',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Linea recta perfecta, sin pasos, sin caída, alineacion impecable'],
                        ['name' => 'Handstand Tuck Hold', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Desde freestanding, rodillas al pecho, sin caer'],
                        ['name' => 'Handstand Straddle Hold', 'minimum' => 5, 'target' => 8, 'unit' => 'segundos', 'form' => 'Desde freestanding, piernas abiertas en V, sin caer'],
                        ['name' => 'Handstand Push Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Contra pared, rango completo, alineacion perfecta'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // EXERCISE NOTES
    // ========================================================================

    private const EXERCISE_NOTES_L1 = [
        'Headstand Hold' => 'Forma triangular con cabeza y manos. Peso distribuido entre cabeza y manos. No apoyes todo el peso en cabeza.',
        'Frog Stand' => 'Rodillas apoyadas en codos. Mantén equilibrio, espalda recta. Mira adelante.',
        'Wall Handstand Hold' => 'Pecho a pared. Manos a 20-30cm de pared. Hombros abiertos, nariz casi tocando.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Cabeza toca suelo entre manos. Control total.',
        'Elevated Pike Push Up' => 'Pies elevados en banco/step. Más inclinación = más dificultad.',
        'Wall Push Up' => 'Manten el cuerpo recto, codos a 45 grados. Acerca pies a pared para más dificultad.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Levanta hombros y piernas al mismo tiempo.',
        'Dead Bug' => 'Lumbar pegado al suelo. Baja brazo y pierna opuestos lentamente.',
        'High Plank' => 'Cuerpo recto, codos debajo hombros. Activa glúteos y core.',
    ];

    private const EXERCISE_NOTES_L2 = [
        'Wall Handstand Hold' => 'Espalda a pared. Manos a 10-15cm de pared. Hombros abiertos, no dejes caer cadera.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared. Mantén cuerpo recto. Llega a vertical.',
        'Handstand Bail Out' => 'Desde wall HS espalda, gira lateralmente 90 grados. Aterriza de pie, no en rodillas.',
        'Handstand Shoulder Taps' => 'Toca hombro contrario sin desplazar peso excesivamente. Cadera quieta.',
        'Diamond Push Up' => 'Manos en diamante, codos pegados al cuerpo. Tríceps trabajan más.',
        'Landmine Press' => 'Empuje en diagonal. Desarrolla estabilidad escapular para handstand.',
    ];

    private const EXERCISE_NOTES_L3 = [
        'Wall Handstand Hold' => 'Pecho a pared, ambos pies despegados. Solo puntas pueden rozar. Empuja fuerte con manos.',
        'Wall Scissors' => 'Alterna pies despegados rápidamente sin tocar pared. Hombros quietos.',
        'Crow Stand' => 'Rodillas sobre tríceps. Mantén equilibrio sin pared. Concentración total.',
        'Feet on Wall Pike Push Up' => 'Pies en pared, cadera alta. Bajada controlada, cabeza toca suelo.',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Headstand Hold' => 'Forma triangular con cabeza y manos. Peso distribuido entre cabeza y manos.',
        'Frog Stand' => 'Rodillas apoyadas en codos. Mantén equilibrio, espalda recta.',
        'Crow Stand' => 'Rodillas sobre tríceps. Equilibrio sin pared. Concentración total.',
        'Wall Handstand Hold' => 'Manos a distancia adecuada de pared. Hombros abiertos, línea recta.',
        'Handstand Hold' => 'Sin pared. Equilibrio activo con dedos/palmas. Hombros abiertos.',
        'Freestanding Handstand Hold' => 'Sin pared. Caídas controladas. Respira tranquilo.',
        'Handstand Tuck Hold' => 'Rodillas al pecho desde freestanding. Compresión de core máxima.',
        'Handstand Straddle Hold' => 'Piernas abiertas en V. Requiere flexibilidad de cadera.',
        'Wall Walk' => 'Desde plancha, camina manos hacia pared. Cuerpo recto.',
        'Handstand Walks' => 'Desplazamiento en handstand. Control de peso lateral.',
        'Handstand Bail Out' => 'Gira lateral desde invertido. Aterriza de pie. Skill de seguridad.',
        'Handstand Shoulder Taps' => 'Toca hombro contrario. Cadera quieta. Control lateral.',
        'Kick-up to Handstand' => 'Una pierna impulsa, la otra sigue. Alineación en vertical.',
        'Wall Scissors' => 'Alterna pies despegados en wall HS. Hombros quietos.',
        'Pike Push Up' => 'Cadera alta, hombros sobre manos. Cabeza toca suelo.',
        'Elevated Pike Push Up' => 'Pies elevados. Más inclinación = más dificultad.',
        'Feet on Wall Pike Push Up' => 'Pies en pared. Bajada controlada.',
        'Handstand Push Up' => 'Contra pared. Cabeza toca suelo. Extensión completa.',
        'Deficit Handstand Push Up' => 'Manos en plataformas. Rango extra.',
        'Standard Push Up' => 'Cuerpo recto, pecho al suelo. Codos a 45 grados.',
        'Diamond Push Up' => 'Manos en diamante. Codos pegados.',
        'Decline Push Up' => 'Pies elevados. Más énfasis en hombros.',
        'Hollow Body Hold' => 'Lumbar pegado al suelo. Hombros y piernas elevados.',
        'Hollow Body Rock' => 'Balanceo desde hollow body sin tocar suelo.',
        'Dead Bug' => 'Lumbar pegado. Movimientos opuestos lentos.',
        'High Plank' => 'Cuerpo recto, core apretado. Sin hundir caderas.',
        'L-Sit' => 'Piernas rectas elevadas. Espalda recta, hombros bajados.',
        'Dragon Flag' => 'Cuerpo recto. Baja en 5 segundos.',
        'Dragon Flag Negative' => 'Bajada controlada desde vertical. Mínimo 5 segundos.',
        'Face Pull' => 'Tira hacia cara. Omóplatos juntos. Controlado.',
        'Landmine Press' => 'Empuje en diagonal. Estabilidad escapular.',
        'Wrist Curl' => 'Muñecas en banco. Control total, peso ligero.',
        'Reverse Wrist Curl' => 'Extensores de muñeca. Previene desequilibrios.',
        'Overhead Carry' => 'Carga sobre cabeza. Camina controlado. Core activo.',
        'Kettlebell Waiter Walk' => 'Pesa sobre cabeza con una mano. Equilibrio + estabilidad.',
        'Active Hang' => 'Omóplatos activados. Hombros lejos de orejas.',
    ];
}
