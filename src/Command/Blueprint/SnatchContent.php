<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Snatch Mastery v1 (8 niveles).
 *
 * Sin puertas y sin logros (mismo criterio que las otras mastery):
 * el progreso se mide con los tests de nivel (legacy: 3 de 4 ítems).
 * Regla §1.7: técnica antes que carga; la muñeca y el hombro antes que el
 * peso; dolor matinal >2/10 = congelar la carga un ciclo.
 *
 * Las cargas (kg) son orientativas (referencia M): se ajustan a % BW según
 * el atleta en las notas; el valor del test es la carga en kg.
 */
class SnatchContent
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

    /**
     * Nombre visible del bloque de sesión según su tipo (y la sesión).
     */
    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Técnica olímpica',
                'session_b' => 'Fuerza de soporte',
                'session_c' => 'Overhead + prehab',
                'session_d' => 'Técnica suave + movilidad',
                default => 'Técnica y fuerza',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Prehab y core',
                default => 'WOD técnico',
            },
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab de hombro',
                'session_b' => 'Prehab de muñeca y escápula',
                'session_c' => 'Prehab lumbar y core',
                'session_d' => 'Prehab de trío',
                default => 'Prehab',
            },
            TrainingRound::BLOCK_DESCARGA => 'Descarga',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Descripción del bloque de sesión.
     */
    public static function getBlockDescription(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, hombro, muñeca, escápula y tobillo) sin descansos de fuerza: transiciones de ~5 s. La movilidad es la plataforma del snatch.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Trabajo técnico principal con barra: la progresión del nivel, series cortas con descanso generoso y técnica de cristal.',
                'session_b' => 'Fuerza de soporte: squat, pull y press. La base que sostiene la carga del snatch.',
                'session_c' => 'Overhead y prehab de hombro/muñeca/lumbar: la recepción segura se construye aquí.',
                default => 'Técnica suave con barra vacía y movilidad: recuperación activa del nivel.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Prehab y core: hombro, muñeca y tronco quieto.',
                default => 'WOD técnico corto. Si la recepción pierde la línea (barra adelante, codo blando), la carga baja.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado: hombro, muñeca/escápula, lumbar/core o trío). Calidad por encima de fatiga; descanso 45-60 s; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de hombro y cadera. En Fase 4 se amplía con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (técnica del snatch y regla §1.7).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'Las posiciones son el programa: OHS con barra vacía, bisagra y recepción. Si la barra te fuerza a ir hacia adelante, la movilidad es la carga que falta.',
                'session_b' => 'La sentadilla y el peso muerto de hoy son el piso y el techo del snatch: bisagra sólida, espalda quieta.',
                'session_c' => 'El overhead se gana en la movilidad: dislocates diarios y muñecas listas. La recepción del nivel 5 se construye hoy.',
                'session_d' => 'Barra vacía y movilidad: técnica suave, sin fatiga. El snatch se aprende descansado.',
            ],
            2 => [
                'session_a' => 'El snatch pull enseña dónde termina el tirón: rodillas fuera, codos altos, barra rozando el cuerpo. La barra sube recta porque las posiciones son rectas.',
                'session_b' => 'El muscle snatch es velocidad de brazos sin ayuda de cadera: si no llega, el tirón es el que falta, no el press.',
                'session_c' => 'El tall snatch es la recepción sin excusas: caída a la zancada rápida y firme. La muñeca antes que el peso.',
                'session_d' => 'PVC work y movilidad: la técnica se afila sin carga.',
            ],
            3 => [
                'session_a' => 'La power snatch castiga la pereza: si la cadera no llega al estirón completo, la recepción alta se convierte en press. Estirón primero, brazos después.',
                'session_b' => 'La fuerza de soporte sostiene la carga: sentadilla y push press de hoy son el techo de mañana.',
                'session_c' => 'El overhead pesado ligero de hoy es la confianza de la recepción: profundiza sin miedo.',
                'session_d' => 'Suave y movilidad: el hombro que se estira hoy kipea (y snatchea) mañana.',
            ],
            4 => [
                'session_a' => 'El hang snatch no perdona el tirón con brazos: desde la rodilla o la cadera, la extensión es la única estrategia. El snatch balance afila la recepción profunda.',
                'session_b' => 'Sentadilla frontal pesada: la postura del torso que necesita la recepción.',
                'session_c' => 'El snatch balance es la recepción ensayada: bájate a la zancada rápido y firme, la barra te espera.',
                'session_d' => 'Movilidad de tobillo y hombro: la profundidad de la recepción se gana aquí.',
            ],
            5 => [
                'session_a' => 'El squat snatch completo es la suma de todo: posiciones, tirón y recepción. Si la barra adelanta en la recepción, la carga baja, no la técnica.',
                'session_b' => 'El snatch pull pesado de hoy enseña a la espalda lo que la recepción va a pedirle.',
                'session_c' => 'La recepción profunda es confianza más que fuerza: el prehab y el OHS de hoy la construyen.',
                'session_d' => 'Barra vacía y movilidad: técnica suave para llegar entero al test.',
            ],
            6 => [
                'session_a' => 'La consistencia es la skill: series de 3-5 al 70-80% sin fallo técnico. El snatch deja de ser un intento y se convierte en hábito.',
                'session_b' => 'El complejo hang + full castiga la técnica cansada: lo que sale cansado es lo que tienes.',
                'session_c' => 'El volumen sube y el hombro manda: dolor matinal >2/10 = congelar la carga un ciclo.',
                'session_d' => 'Suave y movilidad: la semana de consistencia se sobrevive con las sesiones suaves hechas.',
            ],
            7 => [
                'session_a' => 'Los singles al 85-90% se ganan con cabeza: la carga que no puedes repetir técnica, no la subas. El OHS pesado es tu seguro.',
                'session_b' => 'La fuerza de soporte pesada sostiene los singles: sentadilla y push press del día.',
                'session_c' => 'El hombro y la muñeca se progresan antes que el peso: el prehab de hoy es la carga de mañana.',
                'session_d' => 'Movilidad y barra vacía: el cuerpo que llega descansado al 90% es el que lo levanta.',
            ],
            8 => [
                'session_a' => 'El 100% BW se ejecuta como el 50%: mismas posiciones, mismo ritmo, más peso. La técnica ya no se negocia.',
                'session_b' => 'El complejo en fatiga controlada separa al atleta del héroe: lo que sale cansado es lo que tienes.',
                'session_c' => 'Ocho niveles después, la sesión C sigue siendo la que te deja snatchear: hombro y muñeca no se negocian.',
                'session_d' => 'La última sesión suave del programa: movilidad y barra vacía. El cierre se entrena descansado.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y nivel (cargas en kg, orientativas M; ajustar a % BW).
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Overhead Squat' => 'Con PVC o barra vacía (20/15 kg). La movilidad manda sobre la carga: barra en línea, pies estables.',
                'Snatch' => 'Técnico con barra vacía (20/15 kg): posiciones, no peso. Si la barra adelanta, baja a PVC.',
                'Tall Snatch' => 'Velocidad de brazos y recepción: de muslos, sin estirón de cadera. Barra vacía.',
            ],
            2 => [
                'Snatch Pull' => '40-50 kg (ajustar a % BW). Termina en rodillas fuera, codos altos, barra rozando el cuerpo.',
                'Muscle Snatch' => '25-30 kg. Velocidad de brazos sin ayuda de cadera: si no llega, el tirón falta.',
                'Tall Snatch' => 'Barra vacía o ligera: recepción rápida y firme, muñecas listas.',
            ],
            3 => [
                'Power Snatch' => '35-45 kg (40-50% BW aprox.). Recepción alta: estirón completo antes que brazos.',
                'Hang Snatch' => 'Ligero, recepción alta: primer contacto con el hang.',
                'Overhead Squat' => 'Ligero-moderado: confianza de recepción.',
            ],
            4 => [
                'Hang Snatch' => '40-50 kg. Desde rodilla o cadera: extensión de cadera, nunca tirón con brazos.',
                'Snatch Balance' => 'Ligero-moderado: recepción profunda ensayada. Bájate rápido, la barra te espera.',
                'Overhead Squat' => 'Moderado: profundidad de recepción.',
            ],
            5 => [
                'Squat Snatch' => '45-55 kg (60-70% BW aprox.). Desde suelo, recepción en sentadilla profunda; si la barra adelanta, baja la carga.',
                'Snatch Pull' => 'Pesado: la espalda aprende lo que la recepción pedirá.',
            ],
            6 => [
                'Snatch' => '55-65 kg (70-80% BW aprox.): series de 3-5 sin fallo técnico. La consistencia es la skill.',
                'Hang Snatch' => 'Complejo: 1 rep tras el snatch full, misma carga.',
                'Snatch Balance' => 'Ligero: la recepción se afila.',
            ],
            7 => [
                'Snatch' => '65-75 kg (80-90% BW aprox.): singles y dobles con cabeza. La técnica no se negocia.',
                'Overhead Squat' => 'Pesado: el seguro de vida de los singles.',
                'Push Press' => 'Moderado-pesado: el overhead fuerte sostiene la carga.',
            ],
            8 => [
                'Snatch' => '75-85 kg (100-110% BW aprox.): se ejecuta como el 50%, mismas posiciones, más peso.',
                'Kettlebell Snatch' => '16/12 kg: el complejo en fatiga controlada.',
                'Overhead Squat' => 'Moderado-pesado: la confianza del cierre.',
            ],
            default => [],
        };
    }

    // ========================================================================
    // LEVELS — tips, fases y tests (legacy: ítems fuerza, direction gte)
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): bisagra y OHS con PVC/barra vacía (5 reps); movilidad overhead y tobillo.',
                'Fase 2 (Progresión): OHS de 8 con barra vacía y tall snatch; posiciones de arranque.',
                'Fase 3 (Intensificación): snatch técnico de 8 y OHS de 10; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7: técnica antes que carga. La movilidad es la plataforma: si la barra adelanta en la recepción, la carga baja. Dolor matinal >2/10 = congelar la carga un ciclo.',
                'Si fallas el test: vuelve al OHS con PVC (10 limpios valen más que 20 forzados).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Bisagra y OHS con PVC/barra vacía; movilidad overhead y tobillo', 'note' => 'Las posiciones son el programa', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'OHS de 8 y tall snatch; posiciones de arranque', 'note' => 'La movilidad manda sobre la carga', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Snatch técnico de 8 y OHS de 10', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Overhead Squat', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Con barra vacía (20/15 kg ajustable): profundidad completa, barra en línea, pies estables'],
                        ['name' => 'Snatch', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Snatch técnico con barra vacía (20/15 kg): posiciones correctas, recepción estable; la técnica manda sobre la velocidad'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): snatch pull y muscle snatch ligeros (25-40 kg); tall snatch con barra vacía.',
                'Fase 2 (Progresión): muscle snatch de 4 y snatch pull de 3; velocidad de brazos.',
                'Fase 3 (Intensificación): muscle snatch de 5 y la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la barra sube recta porque las posiciones son rectas; si el muscle snatch no llega, el tirón es lo que falta. Muñeca y hombro antes que peso.',
                'Si fallas el test: vuelve al snatch pull ligero con codos altos; el tirón sostiene el muscle snatch.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Snatch pull y muscle snatch ligeros; tall snatch', 'note' => 'El tirón enseña dónde termina la extensión', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Muscle snatch de 4 y snatch pull de 3', 'note' => 'Velocidad de brazos', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Muscle snatch de 5', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Muscle Snatch', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 25/30 kg (ajustar a % BW): velocidad de brazos sin ayuda de cadera, recepción firme'],
                        ['name' => 'Snatch Pull', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 40/50 kg (ajustar a % BW): rodillas fuera, codos altos, barra rozando el cuerpo'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): power snatch ligero (35-40% BW), recepción alta.',
                'Fase 2 (Progresión): power snatch de 2 con estirón completo; squat jumps de potencia.',
                'Fase 3 (Intensificación): power snatch de 3; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la power snatch castiga la pereza: cadera al estirón completo antes que brazos. Si la recepción se convierte en press, la carga baja.',
                'Si fallas el test: vuelve a la power snatch ligera con estirón completo; la velocidad se construye sin peso.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Power snatch ligero, recepción alta', 'note' => 'Estirón primero, brazos después', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Power snatch de 2 y squat jumps', 'note' => 'Potencia de estirón', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Power snatch de 3', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Power Snatch', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 35/45 kg (40-50% BW aprox.): estirón completo, recepción alta firme, sin press-out'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): hang snatch desde rodilla, snatch balance ligero.',
                'Fase 2 (Progresión): hang snatch de 2 con extensión completa; OHS de apoyo.',
                'Fase 3 (Intensificación): hang snatch de 3; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el hang no perdona el tirón con brazos: extensión de cadera o nada. La recepción profunda se ensaya en el snatch balance.',
                'Si fallas el test: vuelve al hang ligero con extensión completa; el snatch balance afila la recepción.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Hang snatch desde rodilla y snatch balance', 'note' => 'Extensión, nunca brazos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Hang snatch de 2 y OHS', 'note' => 'La extensión completa', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Hang snatch de 3', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hang Snatch', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 40/50 kg (ajustar a % BW): desde rodilla o cadera, extensión de cadera, recepción estable'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): squat snatch ligero (45 kg), recepción profunda.',
                'Fase 2 (Progresión): squat snatch de 2 y snatch pull pesado.',
                'Fase 3 (Intensificación): squat snatch de 3; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: si la barra adelanta en la recepción, la carga baja, no la técnica. El squat snatch completo es la suma de todo lo aprendido.',
                'Si fallas el test: vuelve a la power snatch con OHS profundo; la profundidad se gana con confianza.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Squat snatch ligero, recepción profunda', 'note' => 'La suma de las posiciones y el tirón', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Squat snatch de 2 y snatch pull pesado', 'note' => 'La fuerza del tirón sostiene la recepción', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Squat snatch de 3', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Squat Snatch', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 45/55 kg (60-70% BW aprox.): desde suelo, recepción en sentadilla profunda, barra en línea'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): snatch (full) de 3 al 70%; complejo hang + full.',
                'Fase 2 (Progresión): series de 3 con toes to bar de apoyo; consistencia.',
                'Fase 3 (Intensificación): series de 5 al 75-80%; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la consistencia es la skill: series sin fallo técnico. El complejo cansado castiga la técnica; lo que sale cansado es lo que tienes.',
                'Si fallas el test: vuelve a series de 3 al 70% perfectas; las 5 salen cuando las 3 salen siempre.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Snatch de 3 al 70%; complejo hang + full', 'note' => 'La consistencia es la skill', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Series de 3 con apoyo de core', 'note' => 'Sin fallo técnico', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de 5 al 75-80%', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Snatch', 'minimum' => 3, 'target' => 3, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 55/65 kg (70-80% BW aprox.): 3 reps seguidas sin fallo técnico; si la recepción pierde la línea, la carga baja'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): snatch de 2 al 85% con OHS pesado.',
                'Fase 2 (Progresión): single al 90% y push press de soporte.',
                'Fase 3 (Intensificación): doble + single progresivos; la dosis del test en el camino.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la carga que no puedes repetir técnica, no la subas. El OHS pesado es el seguro de vida de los singles.',
                'Si fallas el test: vuelve a los dobles al 80% con cabeza; el 90% llega cuando el 80% sale siempre.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Snatch de 2 al 85% con OHS pesado', 'note' => 'Singles con cabeza', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Single al 90% y push press', 'note' => 'El overhead fuerte sostiene el single', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Doble + single progresivos', 'note' => 'La dosis del test en el camino', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Snatch', 'minimum' => 1, 'target' => 2, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 65/75 kg (80/90% BW aprox.): single o doble con técnica completa; la recepción no se negocia'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): snatch al 100% BW y kettlebell snatch de apoyo.',
                'Fase 2 (Progresión): complejo en fatiga controlada (KB swings + OHS).',
                'Fase 3 (Intensificación): singles al 100-110% BW; el cierre del programa.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el 100% BW se ejecuta como el 50%: mismas posiciones, mismo ritmo, más peso. Ocho niveles después, la técnica ya no se negocia.',
                'Si fallas el test: vuelve a los singles al 90% descansados; el 100% llega cuando el 90% sale siempre.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Snatch al 100% BW y kettlebell snatch', 'note' => 'Se ejecuta como el 50%', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Complejo en fatiga controlada', 'note' => 'Lo que sale cansado es lo que tienes', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Singles al 100-110% BW', 'note' => 'El cierre del programa', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Snatch', 'minimum' => 1, 'target' => 1, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'A 75/85 kg (100/110% BW aprox.): single con técnica completa; doble al 90% como objetivo alternativo'],
                    ],
                ],
            ],
        ];
    }
}
