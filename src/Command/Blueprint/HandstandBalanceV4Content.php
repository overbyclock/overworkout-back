<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Contenido educativo del programa Handstand Balance Mastery v4.
 * Especialización del Master v4: equilibrio de pared (N4-N5), freestanding
 * (N10-N12) y dominio (N16), con la pirueta de caída como condición previa
 * y reglas tendinosas §1.7 aplicadas a muñeca y hombro.
 * Estructura: 8 niveles, 4 fases (Base -> Progresión -> Intensificación -> Recuperación).
 */
class HandstandBalanceV4Content
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
            7 => self::EXERCISE_NOTES_L7,
            default => [],
        };

        if (isset($specific[$exerciseName])) {
            return $specific[$exerciseName];
        }

        return self::GENERIC_EXERCISE_NOTES[$exerciseName] ?? null;
    }

    // ========================================================================
    // LEVEL 1: Base de Hombro y Muñeca
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El handstand empieza en el suelo: hombro fuerte (wall slides), muñeca tolerante (oraciones) y core hollow.',
                'Fase 2 (Progresión): Aumenta rango en dislocates y tiempo en oraciones. La muñeca se construye por ciclos, nunca al fallo.',
                'Fase 3 (Intensificación): Máximo volumen de base. Baja siempre antes de que la muñeca proteste: caerse de cara es la lesión típica.',
                'Fase 4 (Recuperación): Descarga de muñecas y hombros. Oraciones suaves y movilidad.',
                'Los holds de handstand van con descanso completo entre series (2-3 min), nunca en circuito.',
                'Si el tiempo aprieta se recorta volumen (series), nunca el descanso entre series.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Hombro y muñeca de base', 'note' => 'Wall slides 2×10-12, wrist prayer 2×20-30 s, pike push-up 3×4-6', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar rango y reps', 'note' => 'Wall slides 2×12-15, wrist prayer 30-45 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen máximo', 'note' => 'Push up 4×15, pike push-up 4×6, dislocates 2×12-15', 'intensity' => '95%'],
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
                        ['name' => 'Wall Slide', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Espalda y pelvis pegadas a la pared, sin arquear lumbar'],
                        ['name' => 'Wrist Push-Up', 'minimum' => 6, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Apoyo sobre el dorso de las manos, rango sin dolor en muñeca'],
                        ['name' => 'Standard Push Up', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'form' => 'Pecho al suelo, cuerpo en línea, muñecas cómodas'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2: Chest-to-Wall
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): El chest-to-wall es el profesor de la postura: costillas abajo, hombros activos, mirada entre las manos.',
                'Fase 2 (Progresión): Acostumbra la posición invertida: respira diafragmáticamente dentro del hold.',
                'Fase 3 (Intensificación): 30-45 s (dosis N4/N5 del Master). Un solo pico de muñeca por fase (regla §1.7).',
                'Fase 4 (Recuperación): Vuelve a oraciones y movilidad: la muñeca agradece la descarga.',
                'Camina hacia la pared con las manos lo bastante cerca: cuanto más cerca, mejor la línea.',
                'Los holds van con descanso completo. Si el tiempo aprieta, quita series, no descanso.',
                '⚠️ Dolor punzante en muñeca al apoyar = retrocede al Nivel 1 un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Chest-to-wall 20-30 s', 'note' => 'CTW 3×20-30 s, pike push-up 3×5-8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Aumentar tiempo', 'note' => 'CTW 3×30-40 s, wall HS 30 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 45 s', 'note' => 'CTW 4×30-45 s, hollow 30-45 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Oraciones suaves, movilidad', 'intensity' => '50%'],
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
                        ['name' => 'Chest to Wall Handstand', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Vientre a la pared, línea recta, respiración continua'],
                        ['name' => 'Pike Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Cadera alta, cabeza hacia el suelo, rango completo'],
                        ['name' => 'Wrist Prayer', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Oración con mecimiento suave hacia extensión, sin dolor'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3: Wall Handstand Sólido
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Wall handstand 45-60 s (dosis N4/N5 del Master) con los dedos como volante: empuja el suelo para corregir.',
                'Fase 2 (Progresión): Practica correcciones de dedos a propósito: inclínate y recupera.',
                'Fase 3 (Intensificación): 60 s de pared + hollow 45 s. El pico vive en la sesión B.',
                'Fase 4 (Recuperación): Movilidad de muñeca y hombro.',
                'El peso debe caer sobre la base de los dedos y la palma, no solo en el talón de la mano.',
                'Graba una serie por semana: el handstand se corrige viéndose, no sintiéndose.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Wall HS 45-60 s', 'note' => 'Wall HS 3×30-45 s, correcciones de dedos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Equilibrio de dedos', 'note' => 'Wall HS 3×45-60 s, CTW 30-45 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 60 s', 'note' => 'Wall HS 4×45-60 s, hollow 45 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Movilidad, volumen reducido', 'intensity' => '50%'],
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
                        ['name' => 'Wall Handstand Hold', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Línea recta, hombros activos, sin apoyar la cabeza'],
                        ['name' => 'Elevated Pike Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Pies elevados, rango completo de cabeza'],
                        ['name' => 'Hollow Body Hold', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Lumbar pegada al suelo, costillas abajo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4: Equilibrio en Pared
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): Separaciones táctiles: un pie se separa unos centímetros de la pared mientras los dedos corrigen.',
                'Fase 2 (Progresión): Wall handstand 60-90 s con cada vez menos apoyo real de la pared.',
                'Fase 3 (Intensificación): 90 s de pared + primer HSPU con volumen. Un solo pico por fase.',
                'Fase 4 (Recuperación): Muñeca y hombro: el empuje vertical carga mucho.',
                'Las separaciones cortas enseñan al cuerpo lo que es sostenerse sin pared; no las fuerces, acumúlalas.',
                'El HSPU se gana con el rango: si no llegas, usa toalla bajo la cabeza (deficit reducido).',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Separaciones táctiles', 'note' => 'Wall HS 3×45-60 s con separaciones, shoulder taps 6-8', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Wall HS 60-75 s', 'note' => 'Wall HS 3×60-75 s, taps 8-10', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 90 s', 'note' => 'Wall HS 4×60-90 s, HSPU 4×2-3', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Muñeca, movilidad, volumen reducido', 'intensity' => '50%'],
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
                        ['name' => 'Wall Handstand Hold', 'minimum' => 60, 'target' => 90, 'unit' => 'segundos', 'form' => 'Con separaciones táctiles cortas, línea recta'],
                        ['name' => 'Chest to Wall Handstand', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Vientre a la pared, postura impecable'],
                        ['name' => 'Handstand Push Up', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'form' => 'Contra la pared, rango completo, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5: Pirueta y Caída
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): La pirueta (bail out) se domina ANTES de alejarse de la pared: giro de 180° con un brazo, aterrizaje en cuadrupedia.',
                'Fase 2 (Progresión): Kick-ups consistentes: la entrada decide el hold. Entra fuerte y termina suave.',
                'Fase 3 (Intensificación): FS HS 5-10 s (hito N10 del Master). El intento libre va siempre al principio, en fresco.',
                'Fase 4 (Recuperación): Piruetas suaves y movilidad: la confianza también descansa.',
                'Regla de oro: nunca sostengas un FS HS que no sepas caer. La pirueta dominada es tu licencia para equilibrar sin miedo.',
                'Practica la pirueta en ambos lados: la caída natural solo ocurre hacia tu lado fuerte.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Pirueta y kick-ups', 'note' => 'Bail out 3×3-5/lado, kick-ups 4×3-5, FS HS 3×3-5 s', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Entradas consistentes', 'note' => 'Kick-ups 4×5, FS HS 3×5-8 s', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: FS HS 10 s', 'note' => 'FS HS 4×8-10 s, wall HS 90 s', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Piruetas suaves, movilidad', 'intensity' => '50%'],
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
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 6. El FS HS 10 s es el hito del N10 del Master',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand', 'minimum' => 5, 'target' => 10, 'unit' => 'segundos', 'form' => 'Sin pared, línea recta, entrada controlada'],
                        ['name' => 'Wall Handstand Hold', 'minimum' => 90, 'target' => 120, 'unit' => 'segundos', 'form' => 'Respaldo sólido de pared'],
                        ['name' => 'Handstand Push Up', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'form' => 'Contra la pared, rango completo, sin kip'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6: Handstand Libre
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): FS HS 10-15 s: la consistencia manda. Cinco entradas de 12 s valen más que una de 20.',
                'Fase 2 (Progresión): El hito de 15 s (N11/N12 del Master) se consolida con descanso completo entre intentos.',
                'Fase 3 (Intensificación): 15 s libres + HSPU 5-8. Un solo pico por fase.',
                'Fase 4 (Recuperación): Shapes en pared y movilidad.',
                'Cuando el FS HS se sienta fácil, añade shapes (tuck/straddle) antes de añadir tiempo.',
                'Si las entradas fallan, vuelve a la pared un ciclo: el equilibrio sin entrada es imposible.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'FS HS 10-15 s', 'note' => 'FS HS 3×10-15 s, kick-ups 3×5', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Consistencia', 'note' => 'FS HS 3×12-15 s, shapes en pared', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 15 s', 'note' => 'FS HS 4×15 s, HSPU 4×6-8', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Shapes suaves, movilidad', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'week' => 4,
                'name' => 'Evaluación de Ciclo',
                'description' => 'Evaluación para pasar al Nivel 7',
                'preparation' => [
                    ['session' => '2-3 días antes', 'activities' => ['Descanso completo', 'Movilidad suave de muñeca y hombro']],
                ],
                'tests' => [
                    'name' => 'TESTS NIVEL 6',
                    'description' => 'Debes superar los 3 tests para avanzar al Nivel 7. Los 15 s son el hito del N11/N12 del Master',
                    'requirements' => [
                        ['name' => 'Freestanding Handstand', 'minimum' => 15, 'target' => 20, 'unit' => 'segundos', 'form' => 'Hito del N11/N12 del Master: línea recta, sin pared'],
                        ['name' => 'Handstand Push Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'form' => 'Contra la pared, rango completo'],
                        ['name' => 'Freestanding Handstand Push Up', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'form' => 'Sin pared; si aún no sale, sustituye por 8 HSPU en pared'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7: Freestanding Sólido
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): FS HS 15-30 s: entradas por kick-up y primer press de straddle.',
                'Fase 2 (Progresión): El press como entrada de lujo: requiere movilidad de pike, no solo fuerza.',
                'Fase 3 (Intensificación): 30 s libres + HSPU 8-10. El intento más largo va al principio de la sesión.',
                'Fase 4 (Recuperación): Movilidad de pike y cadera: el press vive de la movilidad.',
                'Diversifica entradas (kick-up, press, pirueta invertida): cada entrada enseña algo distinto del equilibrio.',
                'Los shapes (tuck/straddle) son tus frenos y aceleradores: úsalos para salvar holds malos.',
                '⚠️ Regla tendinosa §1.7: molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'FS HS 15-20 s', 'note' => 'FS HS 3×15-20 s, press 3×1-2', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Entradas por press', 'note' => 'FS HS 3×20-30 s, straddle press 3×2-3', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Volumen: 30 s', 'note' => 'FS HS 4×20-30 s, HSPU 4×8-10', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Movilidad de pike, volumen reducido', 'intensity' => '50%'],
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
                        ['name' => 'Freestanding Handstand', 'minimum' => 20, 'target' => 30, 'unit' => 'segundos', 'form' => 'Sin pared, línea recta, entrada controlada'],
                        ['name' => 'Handstand Push Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Contra la pared, rango completo'],
                        ['name' => 'Elevated Pike Push Up', 'minimum' => 8, 'target' => 10, 'unit' => 'repeticiones', 'form' => 'Volumen de empuje vertical'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8: Dominio del Handstand
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): FS HS 30-45 s (N16 del Master): el hold deja de ser el objetivo y pasa a ser la plataforma.',
                'Fase 2 (Progresión): Shapes (tuck/straddle pike) y caminata: el dominio es moverse dentro del equilibrio.',
                'Fase 3 (Intensificación): 45 s + caminata 10-15 m + HSPU 10-15. Pico máximo del programa.',
                'Fase 4 (Recuperación): El bloque C (prehab de muñeca-hombro) es el seguro de vida: mantenlo incluso en deload.',
                'La caminata enseña transferencia de peso: camina lento y con los brazos rectos.',
                'El handstand ya no es una postura: es un vocabulario. Combina holds, shapes, press y caminata.',
                '⚠️ Regla tendinosa §1.7: a esta altura la muñeca carga mucho volumen: ante cualquier señal, baja un peldaño y completa el ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'FS HS 30-45 s', 'note' => 'FS HS 3×30-45 s, caminata 3×5-10 m', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Shapes y caminata', 'note' => 'FS HS 3×30-45 s, caminata 3×10 m', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Pico: 45 s + caminata', 'note' => 'FS HS 4×45 s, caminata 4×10-15 m, HSPU 10-15', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Descarga activa', 'note' => 'Prehab mantenido, movilidad larga', 'intensity' => '50%'],
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
                        ['name' => 'Freestanding Handstand', 'minimum' => 30, 'target' => 45, 'unit' => 'segundos', 'form' => 'Dosis N16 del Master: línea recta, sin pared'],
                        ['name' => 'Handstand Push Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'form' => 'Contra la pared, rango completo, sin kip'],
                        ['name' => 'Freestanding Handstand', 'minimum' => 45, 'target' => 60, 'unit' => 'segundos', 'form' => 'Marca de duración: réplica del mejor hold del ciclo'],
                    ],
                ],
            ],
        ];
    }

    // ========================================================================
    // NOTAS DE EJERCICIOS
    // ========================================================================

    private const EXERCISE_NOTES_L5 = [
        'Handstand Bail Out' => 'Pirueta de 180° hacia un lado, aterrizaje en cuadrupedia: domínala en ambos lados antes de sostener lejos de la pared',
        'Kick-up to Handstand' => 'Entra fuerte y termina suave: la entrada decide el hold',
    ];

    private const EXERCISE_NOTES_L7 = [
        'Straddle Press Handstand' => 'Requiere movilidad de pike: piernas juntas solo cuando el straddle salga limpio',
        'Freestanding Handstand' => 'El intento más largo va al principio de la sesión, en fresco',
    ];

    private const GENERIC_EXERCISE_NOTES = [
        'Wall Handstand Hold' => 'Dedos como volante: empuja el suelo para corregir el equilibrio',
        'Chest to Wall Handstand' => 'Vientre a la pared, costillas abajo, respiración diafragmática',
        'Freestanding Handstand' => 'El intento libre siempre al principio de la sesión, en fresco',
        'Handstand Push Up' => 'Rango completo, sin kip; usa toalla bajo la cabeza si falta recorrido',
        'Freestanding Handstand Push Up' => 'Sin pared: si aún no sale, sustituye por HSPU en pared',
        'Kick-up to Handstand' => 'Entra fuerte, termina suave',
        'Handstand Bail Out' => 'La pirueta es tu licencia para equilibrar sin miedo',
        'Handstand Shoulder Taps' => 'Pies en la pared, peso sobre una mano, cadera quieta',
        'Handstand Tuck Hold' => 'Rodillas al pecho en línea, hombros activos',
        'Handstand Straddle Hold' => 'Piernas abiertas en línea, cadera sobre las manos',
        'Handstand Walk' => 'Caminata lenta, brazos rectos, transferencia de peso',
        'Handstand Walk Practice' => 'Práctica controlada: unos pocos pasos perfectos',
        'Straddle Press Handstand' => 'Requiere movilidad de pike además de fuerza',
        'Wall Walk' => 'Sube caminando con la espalda a la pared, parada en vertical',
        'Pike Push Up' => 'Cadera alta, cabeza hacia el suelo',
        'Elevated Pike Push Up' => 'Pies elevados para más rango de hombro',
        'Wrist Prayer' => 'Movilidad activa sin dolor: mece suave hacia extensión',
        'Wrist Curl' => 'Rango completo suave; nunca al fallo',
        'Reverse Wrist Curl' => 'El seguro del codo: extensores fuertes equilibran el apoyo',
        'Wrist Push-Up' => 'Extensión de muñeca bajo carga progresiva; rango sin dolor',
        'Wall Slide' => 'Espalda y pelvis pegadas a la pared, sin arquear lumbar',
        'Shoulder Dislocate' => 'Banda o palo ancho, costillas abajo',
        'Face Pull' => 'Codo alto, rotación externa completa',
        'Band Pull-Apart' => 'Escápulas activas, pecho abierto',
        'Hollow Body Hold' => 'Lumbar pegada al suelo, costillas abajo',
        'Tuck L-Sit' => 'Empuje fuerte de los brazos, hombros lejos de las orejas',
        'Seated Pike Stretch' => 'Respira en el estiramiento; la movilidad de pike alimenta el press',
        'Kneeling Hip Flexor Stretch' => 'Glúteo contrario activado, cadera hacia adelante',
        'Diaphragmatic Breathing' => 'Respira dentro del hold invertido: el aire estabiliza',
    ];
}
