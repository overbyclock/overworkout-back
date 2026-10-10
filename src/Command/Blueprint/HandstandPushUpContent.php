<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Handstand Push-Up Mastery.
 * Especialización del Master v4: empuje vertical de N8-N12 (pike, HSPU en
 * pared, HSPU libre) y press a pino de N14, con reglas tendinosas §1.7
 * aplicadas a muñeca y hombro.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class HandstandPushUpContent
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
    // LEVEL 1: Base de Empuje Vertical
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La entrada del programa exige 15 push-ups y 5 pikes limpios. Si no, quédate aquí otro ciclo: la prisa es la lesión típica del empuje vertical.',
                'Fase 2 (Progresión): En el pike, proyecta los hombros hacia delante (proyección escapular): es la misma mecánica que la futura HSPU.',
                'Fase 3 (Intensificación): Máximo volumen de pike y elevated pike. Baja antes de que la técnica se rompa: cabeza neutra, espalda plana.',
                'Fase 4 (Recuperación): Descarga de empuje vertical. Muñeca y hombro agradecen la pausa.',
                'Línea de empuje: apila cabeza-espalda-cadera. Si la cadera cae o el cuello se extiende, la serie ha terminado.',
                'La muñeca carga mucho en el pike y más aún en el handstand: muñeca caliente (oraciones, wrist curls) antes de cada sesión de empuje.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Pike push-up con rango completo', 'note' => 'Pike push up 4-6, standard push up 10-12, wall handstand 15-20 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar reps del pike', 'note' => 'Pike push up 6-8, elevated pike 3-5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Pike push up 8, elevated pike 3-5, wall handstand 30-45 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad de muñeca', 'intensity' => '50%'],
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
                        ['name' => 'Pike Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Cabeza entre las manos, hombros proyectados, cuerpo en línea desde la cadera'],
                        ['name' => 'Elevated Pike Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Pies elevados 20-40 cm, rango completo sin arquear la espalda'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Espalda a pared, brazos rectos, hombros lejos de las orejas, cabeza neutra'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Pike Pesado
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El elevated pike es tu HSPU a medias: cuanto más altos los pies, más peso viaja a los hombros (dosis N8 del Master).',
                'Fase 2 (Progresión): Sube los pies 5-10 cm por bloque, no más: el salto brusco de altura es la causa típica de dolor de hombro.',
                'Fase 3 (Intensificación): Elevated pike 8 reps y wall handstand 45-60 s. El pared es tu referencia de línea: graba un video lateral.',
                'Fase 4 (Recuperación): Vuelve al pike de suelo y muñeca: descarga activa.',
                'Proyección de hombros: en cada rep empuja el suelo "lejos" y deja que la cabeza viaje hacia delante: sin esto no hay HSPU en el futuro.',
                'Los holds en pared van frescos, al principio de la sesión D, con descanso completo entre series.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Elevated pike 5-6 reps', 'note' => 'Elevated pike 5-6, wall handstand 20-30 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar altura y reps', 'note' => 'Elevated pike 6-8, kick-up 3-5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: pike pesado', 'note' => 'Elevated pike 8, wall handstand 45-60 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad', 'intensity' => '50%'],
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
                        ['name' => 'Elevated Pike Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Pies elevados, rango completo, hombros proyectados en cada rep'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Línea recta, brazos extendidos, respiración continua'],
                        ['name' => 'Standard Push Up', 'minimum' => 20, 'target' => 25, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, cuerpo en línea, sin cortar el rango'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: HSPU en Pared
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La primera HSPU se construye con negativas: baja en 3-5 s con la cabeza viajando hacia delante, no cayendo vertical.',
                'Fase 2 (Progresión): En pared, apoya la punta de los pies y aleja suavemente la panza: menos fricción = más trabajo real de hombros.',
                'Fase 3 (Intensificación): 3 HSPU limpias > 5 HSPU cortas. El rango completo con profundidad honesta es el objetivo.',
                'Fase 4 (Recuperación): Negativas suaves y holds de pared: descarga activa del hombro.',
                'Cabeza neutra: mira el suelo entre las manos, no adelante. El cuello extendido rompe la línea y sobrecarga el trapecio.',
                'Apoya las manos a 10-15 cm de la pared: demasiado cerca es imposible, demasiado lejos convierte la HSPU en fondo inclinado.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Negativas de HSPU', 'note' => 'HSPU negative 3, primera HSPU 1-2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Primeras completas', 'note' => 'Handstand push up 2, elevated pike 8-10', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 3 HSPU en pared', 'note' => 'Handstand push up 3, wall handstand 45-60 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Negativas suaves, holds de pared', 'intensity' => '50%'],
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
                        ['name' => 'Handstand Push Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Cabeza al suelo, rango completo, sin impulso de piernas'],
                        ['name' => 'Elevated Pike Push Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Rango completo con hombros proyectados'],
                        ['name' => 'Chest to Wall Handstand', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Panza a pared, línea recta, hombros activos'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: HSPU Pared Volumen
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Este nivel replica el test del N10 del Master: HSPU 3/5 en pared. Las series cortas de calidad mandan.',
                'Fase 2 (Progresión): Entra el déficit con prudencia: 2,5-5 cm por bloque como máximo (regla §1.7 aplicada al rango).',
                'Fase 3 (Intensificación): Marcas de test: HSPU 3/5, wall handstand 90 s acumulados, déficit 3/5.',
                'Fase 4 (Recuperación): Volumen mínimo y movilidad larga: el hombro trabaja mucho este nivel.',
                'Las HSPU en pared van con descanso completo (2-3 min), nunca en circuito: es trabajo de fuerza máxima relativa.',
                'Si el tiempo aprieta se recorta volumen (series), nunca el descanso entre series de HSPU.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'HSPU 3 en series', 'note' => 'Handstand push up 3, wall handstand 45-60 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Entrada al déficit', 'note' => 'Deficit handstand push up 3, handstand push up 3-4', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Marcas de test', 'note' => 'Handstand push up 3/5, déficit 3/5, wall handstand 60-90 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, movilidad larga de hombro', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 5. Coincide con el test del N10 del Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 4',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 5',
                    'requirements' => [
                        ['name' => 'Handstand Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'En pared, rango completo, sin kip ni impulso de piernas'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 60, 'target' => 90, 'unit' => 'segundos', 'form' => 'Línea recta, hombros activos, respiración continua'],
                        ['name' => 'Deficit Handstand Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Manos sobre paralelas/discos bajos, profundidad controlada sin dolor de hombro'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: HSPU Pared Sólido
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El hito del nivel es la HSPU ×8 del N11 del Master: fuerza de sobra para empezar a pensar en el aire.',
                'Fase 2 (Progresión): El déficit ya es tu rango normal: sube altura solo cuando 8 reps sean limpias.',
                'Fase 3 (Intensificación): HSPU ×8 y déficit 5/8. La sesión D abre el camino al pino libre (8-12 s).',
                'Fase 4 (Recuperación): Descarga de hombro: la transición de pared a libre exige manguito fresco.',
                'Primer equilibrio libre: practica despegues de un pie contra la pared; el objetivo es el equilibrio, no la duración.',
                'El pino libre se gana con muchos intentos cortos y frescos: 10 entradas de 5 s valen más que 2 de 25 s.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'HSPU 5 en series', 'note' => 'Handstand push up 5, freestanding handstand 5-8 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Camino al hito ×8', 'note' => 'Handstand push up 6-7, déficit 5', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Hito: HSPU ×8', 'note' => 'Handstand push up 8, déficit 5/8, freestanding handstand 8-12 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Volumen reducido, equilibrio suave', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 6',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 5',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6',
                    'requirements' => [
                        ['name' => 'Handstand Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Hito del N11 del Master: rango completo en pared, descanso completo entre reps'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 8, 'target' => 12, 'unit' => 'segundos', 'form' => 'Equilibrio libre sin pared, línea recta, entrada limpia'],
                        ['name' => 'Deficit Handstand Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Profundidad completa controlada, hombros proyectados'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: HSPU Libre Entrada
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El hito del nivel es el pino libre 15 s del N12 del Master: la base de equilibrio para todo lo aéreo.',
                'Fase 2 (Progresión): Los despegues de HSPU libre empiezan con roce mínimo de un pie: la pared sigue ahí, solo que ya no la necesitas.',
                'Fase 3 (Intensificación): 8-10 HSPU en pared + 2 despegues limpios + press asistido con negativas.',
                'Fase 4 (Recuperación): Patrón de equilibrio sin fatiga; muñeca con movilidad extra.',
                'Despegue: la HSPU libre empieza desde un pino libre sólido. Si el equilibrio dura 5 s, la HSPU libre aún no existe.',
                'El press asistido es 80% compresión y hombros, 20% puntas: las negativas de pike press enseñan el camino sin trampas.',
                '⚠️ Regla tendinosa §1.7: la combinación pino libre + HSPU carga mucho el hombro: ante cualquier señal, baja un peldaño.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Pino libre 10-12 s', 'note' => 'Freestanding handstand 10-12 s, primer despegue 1', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Despegues con roce mínimo', 'note' => 'Freestanding handstand push up 1-2, pike press negative 2-3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Hito: pino libre 15 s', 'note' => 'Freestanding handstand 15 s, handstand push up 8-10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Equilibrio suave, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7. Coincide con el hito del N12 del Master',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Hito del N12 del Master: equilibrio libre estable, sin apoyos'],
                        ['name' => 'Handstand Push Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'En pared, rango completo, descanso completo entre reps'],
                        ['name' => 'Freestanding Handstand Push Up', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Despegue con roce mínimo de un pie; la HSPU libre empieza con un despegue honesto'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: HSPU Libre
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La HSPU libre se consolida con 1 rep perfecta repetida muchas veces, no con 3 reps temblando.',
                'Fase 2 (Progresión): Las negativas de press a pino (dosis N14 del Master) abren el camino al press completo.',
                'Fase 3 (Intensificación): 3 HSPU libres, pino libre 20-30 s y press negativo 3/5.',
                'Fase 4 (Recuperación): Descarga de equilibrio y muñeca: el nivel siguiente es el dominio total.',
                'Equilibrio en el aire: dedos abiertos y activos como "frenos" de pequeños ajustes; el cuerpo rígido, solo las muñecas trabajan.',
                'El press a pino asistido se practica con puntas ligeras: 80% compresión y hombros, 20% de puntas.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'HSPU libre estable', 'note' => 'Freestanding handstand push up 1, pike press negative 3', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Dobles y press negativo', 'note' => 'Freestanding handstand push up 2, pike press negative 4', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: 3 HSPU libres', 'note' => 'Freestanding handstand push up 3, freestanding handstand 20-30 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Equilibrio suave, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 8',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 7',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 8',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand Push Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Despegue limpio del suelo, rango completo, equilibrio mantenido al final'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Equilibrio libre estable con correcciones suaves de muñecas'],
                        ['name' => 'Pike Press Negative', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Bajada controlada de 5 s desde el pike, sin colapsar en hombro'],
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
                'Fase 1 (Base): El dominio es HSPU libre 3/5 con volumen real en sesión B: descanso completo y técnica impecable.',
                'Fase 2 (Progresión): El press a pino se consolida: primero straddle, después piernas juntas, siempre con la línea intacta.',
                'Fase 3 (Intensificación): Pico del programa: 5 HSPU libres, press consolidado y pino libre 45 s.',
                'Fase 4 (Recuperación): Cierra el ciclo con movilidad larga y volumen mínimo: la muñeca y el hombro se lo han ganado.',
                'La one-arm HSPU (Olimpo) empieza aquí: un peldaño asistido en la intensificación, sin prisa.',
                'El pino libre 30-45 s ya es dominio del N15 del Master: mantenlo como referencia semanal.',
                '⚠️ Regla tendinosa §1.7: a esta altura del programa muñeca y hombro cargan volumen máximo: ante cualquier señal de alarma, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'HSPU libre 2-3 reps', 'note' => 'Freestanding handstand push up 2-3, press 1-2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Press a pino consolidado', 'note' => 'Handstand press 2-3, handstand push up 12', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: 5 HSPU libres', 'note' => 'Freestanding handstand push up 4-5, freestanding handstand 40-45 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Mantenimiento, movilidad larga', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación final del programa',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 8',
                    'description' => 'Debes superar los 3 tests para completar el programa',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Despegue limpio, rango completo, equilibrio mantenido tras cada rep'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Dosis del N15 del Master: pino libre estable y relajado'],
                        ['name' => 'Handstand Push Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'En pared con rango completo, descanso completo entre reps'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L3 = [
        'Handstand Push Up' => 'Primeras completas: intento fresco al principio de la sesión; si falla, negativa de 5 s',
        'HSPU Negative' => 'Bajada de 3-5 s, cabeza viajando hacia delante, sin colapsar en el fondo',
    ];

    private const EXERCISE_NOTES_L6 = [
        'Freestanding Handstand Push Up' => 'Despegue con roce mínimo de un pie: la pared sigue disponible pero ya no necesaria',
        'Pike Press Negative' => 'Press a pino asistido: 80% compresión y hombros, 20% de puntas',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Pike Press Negative' => 'Bajada de 5 s desde el pike más alto posible: el camino al press a pino completo',
    ];

    private const EXERCISE_NOTES_L8 = [
        'One-Arm Handstand Push Up' => 'Primer peldaño del Olimpo: asistida (pared suave o dedos del segundo brazo), simetría estricta',
        'Handstand Press' => 'Primero straddle, después piernas juntas: la línea manda sobre la velocidad',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Pike Push Up' => 'Hombros proyectados hacia delante, cabeza entre las manos; la cadera es el eje, no el punto débil',
        'Elevated Pike Push Up' => 'Sube la altura de los pies 5-10 cm por bloque, nunca más de golpe (regla §1.7 del rango)',
        'Handstand Push Up' => 'Cabeza neutra (mira el suelo), manos a 10-15 cm de la pared, descanso completo entre series',
        'Deficit Handstand Push Up' => 'Déficit de 2,5-10 cm sobre paralelas o discos; progresión prudente del rango',
        'HSPU Negative' => 'La excéntrica enseña el camino de la cabeza: viaja hacia delante, no caiga vertical',
        'Freestanding Handstand' => 'Dedos abiertos como frenos: correcciones suaves de muñecas, cuerpo rígido',
        'Freestanding Handstand Push Up' => 'Despegue limpio del suelo: un peldaño honesto vale más que tres con trampas',
        'Wall Handstand Hold' => 'Línea recta, hombros lejos de las orejas, respiración continua',
        'Chest to Wall Handstand' => 'Panza a pared: es la referencia de línea para el HSPU en pared',
        'Pike Press Negative' => 'Bajada de 5 s: 80% compresión y hombros, 20% de puntas',
        'Handstand Press' => 'Primero straddle: la línea manda sobre la velocidad',
        'Hollow Body Hold' => 'La posición de línea del handstand: lumbar pegada al suelo, costillas abajo',
        'Wrist Prayer' => 'Movilidad activa sin dolor: mece suave hacia extensión',
        'Wrist Curl' => 'Rango completo suave; nunca al fallo',
        'Reverse Wrist Curl' => 'El seguro del codo: extensores fuertes equilibran el empuje vertical',
        'Wrist Push-Up' => 'Extensión de muñeca bajo carga progresiva; rango sin dolor',
        'Back-of-Hand Stretch' => 'Estira suave después del trabajo de muñeca; nunca fuerces',
        'Wall Slide' => 'Escápulas activas, lumbar neutra: el hombro que empuja arriba sano',
        'Shoulder Dislocate' => 'Banda ancha al principio: rango creciente sin dolor',
        'Face Pull' => 'Codos altos, rotación externa al final: el manguito del empujador vertical',
        'External Rotation' => 'Codo pegado al cuerpo, banda ligera, control total',
        'Cuban Rotation' => 'Transición externa controlada: prevención del pinzamiento en HSPU',
        'Fingertip Hold' => 'Tolerancia de extensión de muñeca: dedos activos, sin dolor',
    ];
}
