<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa Double Under Mastery v1 (8 niveles).
 *
 * Sin puertas y sin logros (mismo criterio que MU/False Grip mastery):
 * el progreso se mide con los tests de nivel (legacy: 3 de 4 ítems).
 * Tips de técnica y regla §1.7 (tobillo/pantorrilla/rodilla).
 */
class DoubleUnderContent
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
                'session_a' => 'Skill de dobles',
                'session_b' => 'Volumen e intervalos',
                'session_c' => 'Prehab tobillo y pantorrilla',
                'session_d' => 'Mixta suave + movilidad',
                default => 'Skill y técnica',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Core anti-extensión',
                default => 'WOD de saltos',
            },
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab de hombro',
                'session_b' => 'Prehab lumbar y core',
                'session_c' => 'Prehab de tobillo',
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
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, tobillo, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s. El tobillo es la estrella: se prepara antes de cada sesión de cuerda.',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Trabajo técnico principal de la sesión: la progresión de dobles del nivel, con descanso completo entre series y técnica de cristal.',
                'session_b' => 'Volumen de cuerda e intervalos con la pantorrilla pesada que sostiene el rebote.',
                'session_c' => 'Prehab de tobillo/pantorrilla/rodilla: gemelo, soleo y tren tibial. Es la sesión que permite todo lo demás.',
                default => 'Trabajo suave y movilidad: recuperación activa del nivel.',
            },
            TrainingRound::BLOCK_WOD => match ($sessionKey) {
                'session_c' => 'Core anti-extensión: el tronco quieto de la que cuelga la técnica de la cuerda.',
                default => 'WOD corto de la sesión. Si la cuerda falla: 5 s y a reenganchar, nunca perseguir la serie rota.',
            },
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (rotado: hombro, lumbar/core, tobillo o trío). Calidad por encima de fatiga; descanso 45-60 s; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => "Vuelta a la calma: respiración diafragmática (90 s), Child's Pose y estiramiento suave de pantorrilla y tobillo. En Fase 4 se amplía con toda la movilidad del nivel.",
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (técnica de la cuerda y regla §1.7).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'Hoy solo importa el rebote: cuerpo alto, muñecas cerca de las caderas, saltos de 1-2 cm y mirada al frente. La cuerda es un metrónomo, no un látigo.',
                'session_b' => 'Los intervalos se ganan con la respiración rítmica: nariz dentro, boca fuera, en compás con el rebote. Si los hombros suben a las orejas, baja el ritmo.',
                'session_c' => 'El gemelo y el soleo son el motor de las dobles: hoy empieza el prehab que te va a permitir llegar al nivel 8 sin romperte. No lo saltes nunca.',
                'session_d' => 'La sesión suave es la que te deja saltar mañana: bike cómodo, zancadas ligeras y movilidad de tobillo. Dolor matinal >2/10 en gemelo/tobillo = bajar volumen un ciclo.',
            ],
            2 => [
                'session_a' => 'La doble nace de la muñeca, no del brazo: salto alto tranquilo y un giro rápido de muñeca. Si saltas más alto para "tener tiempo", la cuerda te castiga.',
                'session_b' => 'El patrón 1-1-2 se aprende cansado de repetirlo bien: salto, salto, doble. Cada serie corta limpia vale por diez forzadas.',
                'session_c' => 'Los pogo jumps de hoy cargan el tren tibial: es el freno del rebote y la lesión típica de las dobles es su gemelo. Fortalécelo ahora.',
                'session_d' => 'Rodilla de atrás al suelo en la zancada y tobillo largo en el step up: las articulaciones que aguantan tus 10.000 rebotes.',
            ],
            3 => [
                'session_a' => 'Regla del re-enganche: la cuerda falla, 5 segundos y a volver. Perseguir la serie rota con prisa es como echarle sal al gemelo.',
                'session_b' => 'Los 5×5 se construyen igual que los 5×3: mismo rebote, misma muñeca, misma cara. Solo cambia el número.',
                'session_c' => 'La tensión crónica de gemelo es la lesión típica de las dobles: si el gemelo amanece cargado, la respuesta es más prehab y menos volumen, no más ganas.',
                'session_d' => 'Bike suave y movilidad: hoy el trabajo es invisible pero es el que te deja saltar el resto de la semana.',
            ],
            4 => [
                'session_a' => 'Las series de 10-20 se ganan en los hombros quietos: muñeca pequeña y rápida, codo pegado al cuerpo. Menos giro, más precisión.',
                'session_b' => 'Las primeras "50 en el día" se reparten en series limpias: 5×10 perfectas vale; 50 arrastradas no entrenan nada.',
                'session_c' => 'Soleo profundo con la rodilla flexionada en el estiramiento: es el que trabaja en el rebote bajo, el gemelo solo no basta.',
                'session_d' => 'Recupera el tobillo: movilidad completa arriba y abajo. Un tobillo rígido roba altura de rebote y fuerza la muñeca.',
            ],
            5 => [
                'session_a' => 'En las series largas la respiración manda: ritmo constante, hombros lejos de las orejas. La serie 30-50 es un test de calma, no de valentía.',
                'session_b' => 'El fallo en la serie larga se gestiona antes de llegar: si el ritmo se acelera y la muñeca se vuelve grande, para antes de que la cuerda lo haga por ti.',
                'session_c' => 'El gemelo empieza a ser el cuello de botella: calf raises lentos (3 s abajo) y estiramientos largos son tu seguro de vida.',
                'session_d' => 'Suave hoy es fuerte mañana: la fase del volumen se sobrevive con las sesiones de movilidad hechas.',
            ],
            6 => [
                'session_a' => 'La velocidad no es saltar más fuerte: es saltar igual de bajo con la muñeca más rápida. Cadencia alta, rebote de 1-2 cm, siempre.',
                'session_b' => 'Los intervalos 30/30 enseñan a recuperar en movimiento: la cuerda suelta entre series no es descanso, es técnica.',
                'session_c' => 'A ritmo alto el tren tibial trabaja el doble: sigue con los pogos controlados y escucha la señal matinal del gemelo.',
                'session_d' => 'La velocidad sin movilidad es una lesión a plazo fijo: tobillo completo hoy, dobles rápidas mañana.',
            ],
            7 => [
                'session_a' => 'La doble cansado es otra doble: misma muñeca, mismo rebote, pulso más alto. Si cambia la técnica cuando cambia el cansancio, entrena la técnica.',
                'session_b' => 'Las transiciones del WOD real no se improvisan: salir del bike y coger la cuerda en 3-5 pasos, sin sprintar la entrada ni pausar la salida.',
                'session_c' => 'El pulso alto esconde la fatiga del gemelo: escucha el cuerpo por encima del reloj. Dolor matinal >2/10 = volumen a la mitad un ciclo.',
                'session_d' => 'Después de la semana de fatiga, la movilidad de hoy es la diferencia entre progresar y sobrevivir.',
            ],
            8 => [
                'session_a' => 'Los sets de 100+ se administran como carrera: primeros 30 fáciles, medios sostenidos, últimos de oficio. La serie larga es calma con ritmo.',
                'session_b' => 'El AMRAP de 2 min no se gana en el primer minuto: ritmo de crucero el minuto 1, el minuto 2 es donde se cobra.',
                'session_c' => 'Ocho niveles después, la sesión C sigue siendo la más importante: el gemelo que entrena hoy es el que no se rompe en la competición.',
                'session_d' => 'La última sesión suave del programa: descansa, moviliza y deja el cuerpo listo para el cierre.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Notas por ejercicio y nivel (técnica de la cuerda y regla §1.7).
     *
     * @return array<string, string>
     */
    public static function getExerciseNotes(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'Jump Rope' => 'Postura: cuerpo alto, muñecas cerca de las caderas, rebote de 1-2 cm, codos pegados al cuerpo, mirada al frente. El objetivo es no fallar.',
                'Pogo Jumps' => 'Rebote de pantorrilla con rodillas casi rectas: el motor del salto bajo. Aterrizaje silencioso.',
                'Double Unders' => 'Tentativas del patrón 1-1-2: salto alto tranquilo + giro rápido de muñeca. No persigas la doble: deja que pase.',
            ],
            2 => [
                'Double Unders' => 'La doble nace de la muñeca, no del brazo: salto alto tranquilo y giro rápido. Patrón 1-1-2 hasta que sea automático.',
                'Jump Rope' => 'Saltos sueltos de rebote bajo entre series de dobles: la recuperación es técnica.',
            ],
            3 => [
                'Double Unders' => 'Regla del re-enganche: fallo = 5 s y a volver, nunca perseguir la serie rota. La serie se construye con calma.',
            ],
            4 => [
                'Double Unders' => 'Hombros quietos: muñeca pequeña y rápida, codo pegado al cuerpo. Menos giro, más precisión.',
            ],
            5 => [
                'Double Unders' => 'Respiración rítmica bajo fatiga: ritmo constante, hombros lejos de las orejas. La serie larga es un test de calma.',
                'Calf Raise on Step' => 'Eccéntrico lento de 3 s: es el seguro de vida del gemelo en las series largas.',
            ],
            6 => [
                'Double Unders' => 'Cadencia alta con rebote de 1-2 cm: la velocidad es muñeca más rápida, no salto más fuerte.',
            ],
            7 => [
                'Double Unders' => 'La doble cansado es otra doble: misma muñeca, mismo rebote, pulso más alto. Transición al cuerda en 3-5 pasos.',
            ],
            8 => [
                'Double Unders' => 'Sets de 100+ administrados como carrera: primeros fáciles, medios sostenidos, últimos de oficio. Series planificadas (10+10+10 / 4×5).',
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
                'Fase 1 (Base): postura del rebote (cuerpo alto, muñecas cerca de las caderas, 1-2 cm de salto) y saltos sueltos de 30-40 s sin fallo; pogo jumps.',
                'Fase 2 (Progresión): saltos sueltos de 50 s y primeras tentativas del patrón 1-1-2 (salto, salto, doble).',
                'Fase 3 (Intensificación): 60 s de cuerda sin fallo, tuck jumps y la primera doble de tentativa.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad de tobillo protagonista; test de nivel al final.',
                'Regla §1.7 (tobillo/pantorrilla/rodilla): dolor matinal >2/10 en gemelo/tobillo = bajar volumen un ciclo; la tensión crónica de gemelo es la lesión típica de las dobles. El prehab de la sesión C no se salta nunca.',
                'Si fallas el test: vuelve a la fase Base y afina el rebote (60 s de cuerda sin fallo es más técnica que resistencia).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Postura del rebote y saltos sueltos 30-40 s sin fallo; pogo jumps', 'note' => 'Aquí no se persigue la doble: se construye la base', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Saltos sueltos de 50 s y tentativas del patrón 1-1-2', 'note' => 'La muñeca empieza a marcar el ritmo', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '60 s de cuerda sin fallo, tuck jumps y primera doble', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad de tobillo', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Jump Rope', 'minimum' => 100, 'target' => 130, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Saltos sueltos en 60 s cronometrados, rebote bajo (1-2 cm), sin fallo; postura alta, mirada al frente'],
                        ['name' => 'Plank', 'minimum' => 45, 'target' => 60, 'unit' => 'seconds', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Costillas abajo, glúteo apretado, respiración continua bajo tensión'],
                        ['name' => 'Double Unders', 'minimum' => 1, 'target' => 3, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Primera doble válida: giro de muñeca rápido, rebote controlado, aterrizaje silencioso'],
                    ],
                ],
            ],
        ];
    }

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): patrón 1-1-2 con dobles de 2 seguidas y saltos altos con muñeca rápida.',
                'Fase 2 (Progresión): series de 3 dobles con descanso completo; consistencia del rebote entre series.',
                'Fase 3 (Intensificación): 3 dobles seguidas en múltiples series y cuerda suelta como recuperación activa.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: dolor matinal >2/10 en gemelo/tobillo = bajar volumen un ciclo. La muñeca antes que el brazo: si la doble sale del brazo entero, la cuerda se acorta y castiga.',
                'Si fallas el test: vuelve al patrón 1-1-2 hasta que la doble salga sola; no fuerces series que aún no existen.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Patrón 1-1-2 con 2 dobles seguidas; saltos altos con muñeca rápida', 'note' => 'La muñeca marca el ritmo, no el brazo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Series de 3 dobles con descanso completo', 'note' => 'Consistencia del rebote entre series', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '3 dobles seguidas en múltiples series', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 3, 'target' => 5, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles seguidas sin fallo: rebote controlado, muñeca rápida, aterrizaje silencioso'],
                    ],
                ],
            ],
        ];
    }

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): bloques 5×3 con re-enganche en 5 s; la serie corta se consolida.',
                'Fase 2 (Progresión): bloques 5×5 con descanso completo entre series.',
                'Fase 3 (Intensificación): 5×5+ con la dosis del test integrada en la sesión A.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el re-enganche en 5 s protege el gemelo; perseguir la serie rota con prisa es la primera causa de tensión crónica.',
                'Si fallas el test: repite la fase Base con 5×3 perfecto antes de volver a por las 10.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Bloques 5×3 con re-enganche en 5 s', 'note' => 'La consistencia antes que el volumen', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Bloques 5×5 con descanso completo', 'note' => 'Mismo rebote, misma muñeca, misma cara', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => '5×5+ con la dosis del test integrada', 'note' => 'Re-enganche: 5 s y a volver', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 10, 'target' => 15, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles seguidas sin fallo; respiración rítmica, hombros quietos'],
                    ],
                ],
            ],
        ];
    }

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): series de 10 seguidas con eficiencia de muñeca.',
                'Fase 2 (Progresión): series de 15 y las primeras acumulaciones grandes.',
                'Fase 3 (Intensificación): series de 20 y "las 50 del día" repartidas en series limpias.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: con el volumen subiendo, el prehab de la sesión C pasa de recomendación a requisito. El gemelo amanece cargado = menos volumen y más estiramiento.',
                'Si fallas el test: vuelve a series de 10-15 perfectas; 25 seguidas salen solas cuando las de 15 salen siempre.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Series de 10 seguidas con eficiencia de muñeca', 'note' => 'Menos giro, más precisión', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Series de 15 y primeras acumulaciones grandes', 'note' => 'El volumen se tolera, no se sufre', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de 20 y "las 50 del día" en series limpias', 'note' => '5×10 perfectas mejor que 50 arrastradas', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 25, 'target' => 40, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles seguidas sin fallo; rebote bajo sostenido, muñeca pequeña y rápida'],
                    ],
                ],
            ],
        ];
    }

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): series de 30 con gestión del fallo (re-enganche 5 s).',
                'Fase 2 (Progresión): series de 40 y respiración rítmica bajo fatiga.',
                'Fase 3 (Intensificación): series de 50 y eficiencia de muñeca cansado.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: el cuello de botella de las series largas es el gemelo, no el pulmón. Calf raises lentos y estiramientos largos son parte del entrenamiento, no un extra.',
                'Si fallas el test: vuelve a las series de 30 administradas como carrera (primeras fáciles, últimas de oficio).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Series de 30 con gestión del fallo (re-enganche 5 s)', 'note' => 'La serie larga es un test de calma', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Series de 40 y respiración rítmica bajo fatiga', 'note' => 'Hombros lejos de las orejas', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Series de 50 y eficiencia de muñeca cansado', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 50, 'target' => 75, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles seguidas sin fallo; si falla, re-enganche en 5 s y vuelta'],
                    ],
                ],
            ],
        ];
    }

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): intervalos 30 s on / 30 s off × 6 con cadencia alta y rebote de 1-2 cm.',
                'Fase 2 (Progresión): intervalos × 7-8 y cuerda suelta como recuperación activa.',
                'Fase 3 (Intensificación): el formato del test (máximo de dobles en 60 s) integrado en la sesión A.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la velocidad pone el tren tibial a trabajar el doble; los pogo jumps controlados de la sesión C son su seguro.',
                'Si fallas el test: vuelve a los intervalos 30/30 con la muñeca pequeña; la cadencia se construye sin prisa.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Intervalos 30 s on / 30 s off × 6, cadencia alta, rebote 1-2 cm', 'note' => 'La velocidad es muñeca más rápida, no salto más fuerte', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Intervalos × 7-8 con cuerda suelta de recuperación', 'note' => 'Recuperar en movimiento', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'El formato del test integrado: máximo de dobles en 60 s', 'note' => 'La dosis del test integrada', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 60, 'target' => 90, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles en 60 s cronometrados; cadencia alta con rebote bajo, muñeca rápida'],
                    ],
                ],
            ],
        ];
    }

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): dobles tras 60 s de air bike; la transición se entrena como la zona.',
                'Fase 2 (Progresión): dobles tras bike largo y burpees; 20 seguidas con el pulso alto.',
                'Fase 3 (Intensificación): la dosis del test (dobles tras 50 saltos sueltos) integrada.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: la fatiga esconde la del gemelo; escucha el cuerpo por encima del reloj. Dolor matinal >2/10 = volumen a la mitad un ciclo.',
                'Si fallas el test: vuelve a los pares bike + cuerda de la fase Base hasta que la doble cansado sea la misma doble.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Dobles tras 60 s de air bike; la transición como la zona', 'note' => 'Salir del bike y coger la cuerda en 3-5 pasos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Dobles tras bike largo y burpees; 20 seguidas con pulso alto', 'note' => 'La doble cansado es otra doble', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'La dosis del test integrada: dobles tras 50 saltos sueltos', 'note' => 'Sin sprintar la entrada a la cuerda', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 30, 'target' => 50, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles seguidas tras 50 saltos sueltos de activación; la técnica no cambia con el cansancio'],
                    ],
                ],
            ],
        ];
    }

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): sets de 50-75 como carrera administrada.',
                'Fase 2 (Progresión): parejas de WODs (estilo Karen + dobles) y sets de 75.',
                'Fase 3 (Intensificación): sets de 100+ y el AMRAP de 2 min del cierre.',
                'Fase 4 (Recuperación): mitad de volumen; test de nivel al final.',
                'Regla §1.7: ocho niveles después, la sesión C sigue siendo la más importante del programa. El gemelo que entrena hoy es el que no se rompe mañana.',
                'Si fallas el test: el cierre exige 100+ seguidas; vuelve a los sets de 50 administrados antes de volver a por las 100.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Sets de 50-75 administrados como carrera', 'note' => 'Primeros fáciles, medios sostenidos, últimos de oficio', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Parejas de WODs (Karen + dobles) y sets de 75', 'note' => 'La doble como herramienta de competición', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Sets de 100+ y AMRAP de 2 min', 'note' => 'El cierre del programa', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Double Unders', 'minimum' => 100, 'target' => 150, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'Máximo de dobles seguidas sin fallo; series planificadas en carrera (10+10+10 / 4×5)'],
                        ['name' => 'Jump Rope', 'minimum' => 120, 'target' => 180, 'unit' => 'repeticiones', 'direction' => 'gte', 'type' => TestItemType::Fuerza->value, 'form' => 'AMRAP de 2 min de dobles (alternativa de cierre): cuenta las dobles completadas en 120 s cronometrados'],
                    ],
                ],
            ],
        ];
    }
}
