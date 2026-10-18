<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Kipping & T2B Mastery v1 (8 niveles).
 *
 * Sin puertas y sin logros (mismo criterio que las otras mastery):
 * el progreso se mide con los tests de nivel (legacy: 3 de 4 ítems).
 * Regla §1.7: primero estricto/base, luego dinámico; el kipping exige hollow
 * sólido; dolor matinal >2/10 en hombro = congelar kipping un ciclo.
 */
class KippingT2bContent
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
                'session_a' => 'Skill de barra',
                'session_b' => 'Tracción y fuerza',
                'session_c' => 'Core + prehab hombro',
                'session_d' => 'Mixta suave + movilidad',
                default => 'Skill y técnica',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Prehab y core',
                default => 'WOD de barra',
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
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, hombro, muñeca, escápula y cadera) sin descansos de fuerza: transiciones de ~5 s. Hombro y muñeca listos antes de la barra.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Trabajo técnico principal de la sesión: la progresión de swing/T2B del nivel, con descanso completo y técnica de cristal.',
                'session_b' => 'Tracción estricta, escápula y hollow/arch cargado: la base que hace seguro el kipping.',
                'session_c' => 'Core anti-extensión y prehab de hombro/muñeca: el kipping castiga el hombro.',
                default => 'Trabajo suave y movilidad: recuperación activa del nivel.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Prehab y core: hombro, muñeca y tronco quieto.',
                default => 'WOD corto de la sesión. El fallo técnico marca el escalado: colgar del swing cuando el core cede no entrena nada.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado: hombro, muñeca/escápula, lumbar/core o trío). Calidad por encima de fatiga; descanso 45-60 s; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de hombro y cadera. En Fase 4 se amplía con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (técnica de kipping/T2B y regla §1.7).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'El swing nace en el suelo: hollow y arch perfectos antes de colgarte. En el beat swing pasivo el cuerpo oscila suelto: hombres quietos, respiración rítmica.',
                'session_b' => 'La escápula tira antes que el brazo: scapular pull ups lentos y limpios. Esa es la base de toda tracción futura.',
                'session_c' => 'Hombro y muñeca del que cuelga: el grip del kipping carga la muñeca en extensión; el wrist push-up de hoy es la prevención de mañana.',
                'session_d' => 'Suave hoy, barra mañana: bike cómodo y movilidad de hombro. Dolor matinal >2/10 en hombro = congelar kipping un ciclo.',
            ],
            2 => [
                'session_a' => 'El swing activo es un cierre de cadera, no una dominada asistida: el hollow empuja atrás, el snap cierra al frente. Si tiras con los brazos, no es kipping.',
                'session_b' => 'La rodilla al pecho es hollow en el aire: si la espalda se arquea al subir la rodilla, vuelve al hollow del suelo.',
                'session_c' => 'Los face pulls equilibran todo el empuje del kipping: hombro sano, kipping largo.',
                'session_d' => 'Movilidad de hombro completo hoy: el rango que no usas es el que te lesionas.',
            ],
            3 => [
                'session_a' => 'La T2B empieza atrás: retroceso fuerte del hollow y luego la flexión de cadera. La tentativa que sale del solo lean back es la que se queda.',
                'session_b' => 'Tracción estricta primero, dinámica después: las dominadas estrictas de hoy son el seguro de vida de los kipping del nivel 6.',
                'session_c' => 'El hombro en el kipping trabaja en rango completo a velocidad: muñeca y escápula fuertes son innegociables.',
                'session_d' => 'Recupera el hombro: dislocates largos y suaves, sin dolor. El kipping se entrena descansado.',
            ],
            4 => [
                'session_a' => 'Cada T2B empieza atrás y termina arriba: extensión larga, cierre completo, pies a la barra. La rep corta no cuenta.',
                'session_b' => 'El tuck L-sit enseña a la cadera a cerrar sin columpio: el mismo cierre que la T2B, en el suelo.',
                'session_c' => 'La sesión C sigue siendo la que te deja hacer la A: core y hombro no se negocian.',
                'session_d' => 'Suave y movilidad: el nivel del primer T2B se gana también descansando.',
            ],
            5 => [
                'session_a' => 'El ritmo de rebote es un metrónomo: atrás-arriba, atrás-arriba. Cuando el metrónomo se acelera, la serie se rompe. Corre más lento para durar más.',
                'session_b' => 'El hollow cargado de hoy es el swing de las series largas: tronco quieto, todo desde la cadera.',
                'session_c' => 'Con el volumen subiendo, el hombro manda: cualquier pinchazo matinal >2/10 y el kipping se congela un ciclo.',
                'session_d' => 'La movilidad de hoy es la serie de mañana: hombro completo, cadera suelta.',
            ],
            6 => [
                'session_a' => 'El combo se gana en la transición: del swing del kipping PU al swing de la T2B sin perder tensión. Si el cuerpo se queda flojo entre ejercicios, el combo se rompe.',
                'session_b' => 'El chest to bar es kipping con ambición: mismo swing, más altura. Se construye con la tracción estricta de fondo.',
                'session_c' => 'Kipping con hombro cansado es préstamo a interés: el prehab de hoy paga la factura antes de que llegue.',
                'session_d' => 'Semana de combos: la sesión suave es la que permite encadenar mañana.',
            ],
            7 => [
                'session_a' => 'La T2B en fatiga es la misma T2B con el pulso alto: si cambia la técnica cuando sube el cansancio, no la has entrenado todavía. Ritmo antes que héroe.',
                'session_b' => 'El C2B cansado castiga el hombro: cualquier aviso matinal y el volumen baja un ciclo. La constancia gana al heroísmo.',
                'session_c' => 'El core cede antes que los flexores en los sets grandes: el plank y el hollow de hoy son el límite real de tus series.',
                'session_d' => 'Después de la fatiga, movilidad: el hombro que se estira hoy es el que kipea mañana.',
            ],
            8 => [
                'session_a' => 'Los sets de 30+ se administran como carrera: primeras reps fáciles, medias sostenidas, últimas de oficio. La serie larga es calma con ritmo.',
                'session_b' => 'El C2B consistente cierra el programa: kipping alto, pecho a la barra, hombro sano de fondo.',
                'session_c' => 'Ocho niveles después, la sesión C sigue siendo la más importante: el hombro que entrena hoy es el que no se rompe mañana.',
                'session_d' => 'La última sesión suave del programa: descansa, moviliza y deja el cuerpo listo para el cierre.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y nivel (técnica de kipping/T2B y regla §1.7).
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Hollow Body Hold' => 'El motor del kipping: costillas abajo, lumbar pegada al suelo, hombros y pies levantados. Sin hollow no hay swing.',
                'Arch Hold' => 'La otra mitad del swing: extensión controlada de la columna, mirada al frente, glúteo apretado.',
                'Dead Hang' => 'Beat swing pasivo: colgado, cuerpo suelto oscilando desde los hombres. Grip firme, respiración rítmica.',
            ],
            2 => [
                'Active Hang' => 'Swing activo: el hollow empuja atrás y el cierre de cadera (snap) cierra al frente. No tires con los brazos.',
                'Knee Raise' => 'Rodillas al pecho en colgado con la espalda quieta: hollow en el aire.',
            ],
            3 => [
                'Toes to Bar' => 'Primera T2B: retroceso fuerte del hollow (lean back) y luego flexión de cadera. La rep que sale del solo retroceso es la que se queda.',
                'Knee Raise' => 'Rodillas a codos/pecho alto: empuja el swing alto y sube las rodillas con el hollow, no con la espalda arqueada.',
            ],
            4 => [
                'Toes to Bar' => 'Cierre completo de cadera: extensión larga atrás, rodillas al pecho, pies a la barra. La rep corta no cuenta.',
            ],
            5 => [
                'Toes to Bar' => 'Ritmo de rebote estable: atrás-arriba como un metrónomo. No colgues del swing cuando el core cede.',
                'Hollow Body Rock' => 'Hollow en movimiento: el mismo tronco quieto del swing, en el suelo y cansado.',
            ],
            6 => [
                'Kipping Pull Up' => 'El combo se gana en la transición: del swing del kipping PU al de la T2B sin perder tensión corporal.',
                'Chest to Bar Practice' => 'Kipping con ambición: mismo swing, más altura. Pecho a la barra sin perder el cierre de cadera.',
            ],
            7 => [
                'Toes to Bar' => 'La T2B en fatiga es la misma T2B con el pulso alto: misma técnica, ritmo antes que héroe.',
            ],
            8 => [
                'Toes to Bar' => 'Sets de 30+ administrados como carrera: primeras fáciles, medias sostenidas, últimas de oficio.',
                'Kipping Pull Up' => 'Kipping alto y consistente: el swing que ya no se negocia.',
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
                'Fase 1 (Base): hollow y arch en el suelo (20-25 s) y colgado pasivo; grip firme.',
                'Fase 2 (Progresión): beat swing pasivo de 30 s con oscilación de hombros; arch en el suelo.',
                'Fase 3 (Intensificación): 40 s de colgado y las primeras rodillas; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad de hombro protagonista; test de nivel al final.',
                'Regla §1.7: primero estricto/base, luego dinámico — el kipping exige hollow sólido. Dolor matinal >2/10 en hombro = congelar kipping un ciclo y volver a swing pasivo.',
                'Si fallas el test: vuelve al hollow del suelo (20 s limpios valen más que 40 s arqueados).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Hollow/arch en el suelo (20-25 s) y colgado pasivo; grip', 'note' => 'El swing nace en el suelo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Beat swing pasivo de 30 s; arch en el suelo', 'note' => 'Oscilación de hombros, cuerpo suelto', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '40 s de colgado y primeras rodillas', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad de hombro', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Hollow Body Hold', 'minimum' => 20, 'target' => 30, 'unit' => 'seconds', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Costillas abajo, lumbar pegada al suelo, hombros y pies levantados; sin arquear'],
                        ['name' => 'Dead Hang', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Beat swings pasivos seguidos: oscilación de hombros en colgado, cuerpo suelto, grip firme'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): swing activo de 10 con cierre de cadera y rodillas al pecho.',
                'Fase 2 (Progresión): 12 swings activos y rodillas de 10 con la espalda quieta.',
                'Fase 3 (Intensificación): 15 swings activos; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el swing activo es un cierre de cadera, no una dominada asistida; si tiras con los brazos, vuelve al nivel 1.',
                'Si fallas el test: vuelve al swing pasivo y al hollow del suelo antes de más kipping.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Swing activo de 10 con cierre de cadera y rodillas al pecho', 'note' => 'El hollow empuja, el snap cierra', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => '12 swings activos y rodillas de 10', 'note' => 'Espalda quieta en el knee raise', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '15 swings activos', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Knee Raise', 'minimum' => 8, 'target' => 12, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Rodillas al pecho en colgado, espalda quieta, sin arquear; cierre de cadera visible'],
                        ['name' => 'Active Hang', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Swings activos seguidos: cierre de cadera (snap) al frente, cuerpo tenso en el arco'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): rodillas a codos (10) con hollow fuerte y primeras T2B de tentativa.',
                'Fase 2 (Progresión): 12 rodillas y T2B dobles.',
                'Fase 3 (Intensificación): 15 rodillas y 3 T2B; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la T2B empieza atrás (retroceso del hollow), no con la flexión de cadera aislada; el hombro descansa si amanece >2/10.',
                'Si fallas el test: vuelve a las rodillas altas con retroceso fuerte; la primera T2B llega sola.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Rodillas a codos (10) con hollow fuerte y T2B de tentativa', 'note' => 'El retroceso manda', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => '12 rodillas y T2B dobles', 'note' => 'Swing alto, espalda quieta', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '15 rodillas y 3 T2B', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Knee Raise', 'minimum' => 12, 'target' => 15, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Rodillas a codos/pecho alto con hollow fuerte, espalda quieta'],
                        ['name' => 'Toes to Bar', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Primera T2B válida: retroceso del hollow, cierre completo de cadera, pies a la barra'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): T2B en series de 3 con cierre completo.',
                'Fase 2 (Progresión): series de 4 y hollow rock de apoyo.',
                'Fase 3 (Intensificación): series de 5; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: cada rep empieza atrás y termina arriba; la rep corta no cuenta y el hombro manda sobre el ego.',
                'Si fallas el test: vuelve a series de 3 perfectas; las 5 salen solas cuando las 3 salen siempre.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'T2B en series de 3 con cierre completo', 'note' => 'Extensión atrás, cierre arriba', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Series de 4 y hollow rock', 'note' => 'El rebote se controla', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de 5', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Toes to Bar', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'T2B seguidas con cierre completo de cadera y rebote controlado; hombros lejos de las orejas'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): series de 8 T2B con ritmo de metrónomo.',
                'Fase 2 (Progresión): series de 10 y hollow rocks de 12.',
                'Fase 3 (Intensificación): series de 12; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el ritmo de rebote es un metrónomo; cuando se acelera, la serie se rompe. No cuelgues del swing cuando el core cede.',
                'Si fallas el test: vuelve a series de 8 administradas; la constancia construye las 12.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Series de 8 T2B con ritmo de metrónomo', 'note' => 'Atrás-arriba, siempre igual', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Series de 10 y hollow rocks de 12', 'note' => 'El core manda', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de 12', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Toes to Bar', 'minimum' => 12, 'target' => 18, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'T2B seguidas con ritmo de rebote estable; cada rep empieza atrás y termina arriba'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): kipping PU de 3-5 y T2B de 6 en el mismo set.',
                'Fase 2 (Progresión): pares 2+4 con la transición de swing entrenada.',
                'Fase 3 (Intensificación): 5 kipping PU y la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el combo se gana en la transición; el hombro cansado congela el kipping un ciclo. El chest to bar se construye con tracción estricta de fondo.',
                'Si fallas el test: vuelve a los pares 2+4; el combo se construye pieza a pieza.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Kipping PU de 3-5 y T2B de 6 en el mismo set', 'note' => 'El swing se mantiene entre ejercicios', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Pares 2 kipping PU + 4 T2B', 'note' => 'La transición entrenada', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '5 kipping PU y T2B de 8', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Toes to Bar', 'minimum' => 15, 'target' => 20, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'T2B seguidas con ritmo estable tras el combo; rebote controlado'],
                        ['name' => 'Kipping Pull Up', 'minimum' => 5, 'target' => 8, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Kipping pull-ups seguidas con cierre de cadera claro, sin tirar solo con los brazos'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): T2B de 10-12 tras 60 s de air bike.',
                'Fase 2 (Progresión): T2B de 12-15 tras bike largo; gestión del swing cansado.',
                'Fase 3 (Intensificación): sets de 15+ en fatiga; la dosis del test integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la T2B en fatiga es la misma T2B con el pulso alto; ritmo antes que héroe. El core cede antes que los flexores: el prehab decide.',
                'Si fallas el test: vuelve a los pares bike + cuerda frescos; la gestión se aprende descansado.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'T2B de 10-12 tras 60 s de air bike', 'note' => 'La transición del WOD real', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'T2B de 12-15 tras bike largo', 'note' => 'Gestión del swing cansado', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Sets de 15+ en fatiga', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Toes to Bar', 'minimum' => 20, 'target' => 30, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'T2B seguidas en fatiga (tras bike o en circuito); la técnica no cambia con el cansancio'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): series de 20-25 T2B administradas como carrera.',
                'Fase 2 (Progresión): parejas de WOD con sets de 25; C2B kipping consistente.',
                'Fase 3 (Intensificación): series de 30+ y el AMRAP de 2 min del cierre.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el cierre exige hombro sano: cualquier aviso matinal y el volumen baja. El kipping que dura es el que descansa.',
                'Si fallas el test: vuelve a los sets de 20 administrados; las 30+ llegan cuando las 20 salen siempre.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Series de 20-25 T2B administradas como carrera', 'note' => 'Primeras fáciles, medias sostenidas, últimas de oficio', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Parejas de WOD con sets de 25; C2B kipping', 'note' => 'La doble como herramienta de competición', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de 30+ y AMRAP de 2 min', 'note' => 'El cierre del programa', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Toes to Bar', 'minimum' => 30, 'target' => 40, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'T2B seguidas con ritmo de carrera; cada rep empieza atrás y termina arriba'],
                        ['name' => 'Kipping Pull Up', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Kipping pull-ups seguidas consistentes; opcional chest to bar en el objetivo'],
                    ],
                ],
            ],
        ];
    }
}
