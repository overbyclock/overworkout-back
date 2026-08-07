<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Calistenia Master v4.0 (niveles 1-16, incluyendo el
 * Bloque Élite Abierto "Olimpo" como nivel 16).
 *
 * Contenido transcrito literalmente de docs/detalle_niveles_01-03.md (niveles 1-3),
 * docs/detalle_niveles_04-06.md (niveles 4-6),
 * docs/detalle_niveles_07-09.md (niveles 7-9),
 * docs/detalle_niveles_10-12.md (niveles 10-12) y
 * docs/detalle_niveles_13-15_olimpo.md (niveles 13-15 y Bloque Élite Olimpo).
 * Estructura: 4 sesiones por fase (A=Empuje, B=Tracción, C=Circuito, D=Skill).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Diferencia con v3: toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*)
 * en orden fijo: preparación (Bloque 0) → principal → salud postural →
 * cadena posterior/cadera → descarga. Los bloques comunes del nivel se
 * materializan en cada sesión con la dosificación de la fase.
 *
 * Nota de mapeo de ejercicios (catálogo `exercises`):
 * - Las filas horizontales (muy alta/alta/media/baja) usan 'Australian Pull Up';
 *   la inclinación exacta va en la nota técnica del nivel.
 * - La flexión excéntrica en suelo usa 'Tempo Push Up' (bajada 4-6 s en nota).
 * - El split squat no existe en el catálogo: se usa 'Stationary Lunge'
 *   (zancada estática, mismo gesto) y queda reportado por el comando.
 */
class CalisteniaMasterBlueprintV4
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Despertar',
                'title' => 'Despertar',
                'description' => 'Sal del sedentarismo con patrones correctos y confianza: aquí no se falla, se aprende',
                'objective' => 'Aprender los patrones base (flexión inclinada, fila alta, sentadilla, plancha y colgado) con técnica limpia',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero',
                'skillFocus' => 'Plancha frontal, Colgado pasivo (Dead Hang)',
            ],
            2 => [
                'name' => 'Nivel 2: Cimientos',
                'title' => 'Cimientos',
                'description' => 'Consigue la flexión completa en suelo y los primeros isométricos de core y apoyo',
                'objective' => 'Primera flexión completa, hollow body hold 20 s y support hold 15 s',
                'difficultyRating' => 2,
                'color' => '#4ade80',
                'requirementsSummary' => 'Mínimos del test N1: Flexión inclinada(8), Fila horizontal alta(8), Sentadilla al aire(15), Plancha frontal(30s), Colgado pasivo(20s)',
                'skillFocus' => 'Flexión completa, Hollow Body Hold, Support Hold',
            ],
            3 => [
                'name' => 'Nivel 3: Primera Tracción',
                'title' => 'Primera Tracción',
                'description' => 'Construye la dominada desde tres vías (negativas, banda, escápulas) y abre el empuje cerrado y el L-sit',
                'objective' => 'Negativa de dominada de 5 s, dominada con banda ligera, scapular pull, diamond push-up y tuck L-sit',
                'difficultyRating' => 3,
                'color' => '#4ade80',
                'requirementsSummary' => 'Mínimos del test N2: Flexión completa en suelo(8), Fila horizontal baja(10), Hollow body hold(20s), Support hold(15s), Sentadilla profunda(25)',
                'skillFocus' => 'Dominada (tres vías), Diamond Push Up, Tuck L-Sit',
            ],
            4 => [
                'name' => 'Nivel 4: La Primera Dominada',
                'title' => 'La Primera Dominada',
                'description' => 'El primer gran hito emocional del programa: la dominada estricta y el inicio del empuje overhead',
                'objective' => 'Dominada estricta, chin-up, pike push-up, tuck L-sit 10 s y fondo asistido en paralelas, con las puertas de movilidad G1 (flexión de hombro) y G2 (extensión de muñeca) superadas',
                'difficultyRating' => 4,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N3: Negativa de dominada 5 s(3), Dominada con banda ligera(5), Scapular pull(5), Diamond push-up(6), Tuck L-sit(5s)',
                'skillFocus' => 'Dominada estricta (Pull Up), Chin Up, Pike Push Up',
            ],
            5 => [
                'name' => 'Nivel 5: Mundo Invertido',
                'title' => 'Mundo Invertido',
                'description' => 'Entrada al trabajo invertido con progresión segura (headstand → pared), equilibrio básico de brazos y consolidación del volumen de dominadas',
                'objective' => 'Wall handstand 20 s, headstand 20 s, crow pose 10 s, 3 dominadas y fondo completo en paralelas',
                'difficultyRating' => 5,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N4 + puertas G1 y G2: Dominada estricta(1), Chin-up(1), Pike push-up(6), Tuck L-sit(10s), Fondo asistido(5)',
                'skillFocus' => 'Wall Handstand, Headstand, Crow Pose',
            ],
            6 => [
                'name' => 'Nivel 6: Proyección',
                'title' => 'Proyección',
                'description' => 'Proyección escapular (planche lean), primer lever (tuck front lever), entrada formal a las anillas y 5 dominadas',
                'objective' => 'Planche lean 20 s, tuck front lever 8 s, 5 dominadas, pseudo planche push-up y ring support hold, con las puertas G4 (extensión de hombro) y G5 (compresión de cadera) superadas',
                'difficultyRating' => 6,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N5 + puerta G2: Wall handstand(20s), Headstand(20s), Crow pose(10s), Dominadas(3), Fondos(5)',
                'skillFocus' => 'Planche Lean, Tuck Front Lever, Ring Support Hold',
            ],
            7 => [
                'name' => 'Nivel 7: Tuck y Extensión',
                'title' => 'Tuck y Extensión',
                'description' => 'Consolidación de los estáticos tuck (planche y front lever avanzado), el L-sit completo y la movilidad de hombro aplicada (skin the cat, German hang)',
                'objective' => 'Tuck planche 5 s, advanced tuck front lever 8 s, L-sit completo 5 s, skin the cat, German hang libre 10 s y ring push-up 8',
                'difficultyRating' => 7,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N6 + puertas G4 y G5: Planche lean(20s), Tuck front lever(8s), Dominadas(5), Pseudo planche push-up(5), Ring support hold(15s)',
                'skillFocus' => 'Tuck Planche, L-Sit, Skin the Cat',
            ],
            8 => [
                'name' => 'Nivel 8: Potencia',
                'title' => 'Potencia',
                'description' => 'Conversión de fuerza en potencia (chest-to-bar, dips explosivos), cierre del tuck back lever y apertura del trabajo unilateral de tracción (archer) y de pierna',
                'objective' => 'Chest-to-bar 3, advanced tuck planche 5 s, tuck back lever 8 s, ring dip 5, straight bar dip 5 y archer pull-up 2/lado, con la puerta G3 (dorsiflexión de tobillo) superada',
                'difficultyRating' => 8,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N7: Tuck planche(5s), Advanced tuck front lever(8s), L-sit(5s), Skin the cat(2), German hang libre(10s), Ring push-up(8)',
                'skillFocus' => 'Chest-to-Bar, Tuck Back Lever, Ring Dip',
            ],
            9 => [
                'name' => 'Nivel 9: Muscle-Up',
                'title' => 'Muscle-Up',
                'description' => 'El segundo gran hito emocional: el muscle-up en barra; en paralelo, one-leg front lever, pistol squat y dragon flag',
                'objective' => 'Muscle-up en barra, one-leg front lever 5 s, pistol squat por pierna y dragon flag 3, con la puerta G6 (pike) y los re-tests de G1 y G2 superados',
                'difficultyRating' => 9,
                'color' => '#991b1b',
                'requirementsSummary' => 'Mínimos del test N8 + puerta G3: Chest-to-bar(3), Advanced tuck planche(5s), Tuck back lever(8s), Ring dip(5), Straight bar dip(5), Archer pull-up(2/lado)',
                'skillFocus' => 'Muscle-Up, Pistol Squat, One-Leg Front Lever',
            ],
            10 => [
                'name' => 'Nivel 10: Vertical Fuerte',
                'title' => 'Vertical Fuerte',
                'description' => 'El empuje vertical completo: HSPU en pared, handstand libre, muscle-up consolidado ×3 y primeras progresiones de press to handstand',
                'objective' => 'HSPU en pared 3, handstand libre 5 s, muscle-up 3, pistol 3/pierna, pike press negativo 3 y archer push-up 3/lado, con el gate de false grip hang 20 s y el re-test G4 superados',
                'difficultyRating' => 10,
                'color' => '#7f1d1d',
                'requirementsSummary' => 'Mínimos del test N9 + puerta G6 y re-tests G1/G2: Muscle-up(1), One-leg front lever(5s), Pistol squat(1/pierna), Dragon flag(3)',
                'skillFocus' => 'HSPU en pared, Handstand libre, Pike press negativo',
            ],
            11 => [
                'name' => 'Nivel 11: Straddle y Anillas',
                'title' => 'Straddle y Anillas',
                'description' => 'Estáticos straddle sólidos, el muscle-up en anillas (segunda cima de anillas), full back lever y tracción unilateral horizontal (typewriter)',
                'objective' => 'Straddle front lever 5 s, straddle planche 5 s, muscle-up en anillas, full back lever 5 s y typewriter 3, con mantenimiento religioso de todas las puertas G1-G6',
                'difficultyRating' => 11,
                'color' => '#7f1d1d',
                'requirementsSummary' => 'Mínimos del test N10 + re-test G4: HSPU en pared(3), Handstand libre(5s), Muscle-up(3), Pistol squat(3/pierna), Pike press negativo(3), Archer push-up(3/lado), False grip hang(20s)',
                'skillFocus' => 'Straddle planche, Muscle-up en anillas, Full back lever',
            ],
            12 => [
                'name' => 'Nivel 12: Full Levers',
                'title' => 'Full Levers',
                'description' => 'Las dos cimas estáticas del programa (full front lever y full planche), el HSPU libre, el press to handstand y el one-arm push-up',
                'objective' => 'Full front lever 5 s, full planche 3 s, HSPU libre, press to handstand, one-arm push-up 3/lado y dragon pistol 3, con los re-tests G3, G5 y G6 superados',
                'difficultyRating' => 12,
                'color' => '#7f1d1d',
                'requirementsSummary' => 'Mínimos del test N11: Straddle front lever(5s), Straddle planche(5s), Muscle-up en anillas(1), Full back lever(5s), Typewriter pull-up(3)',
                'skillFocus' => 'Full front lever, Full planche, HSPU libre',
            ],
            13 => [
                'name' => 'Nivel 13: Fuerza Unilateral',
                'title' => 'Fuerza Unilateral',
                'description' => 'Abre la recta final de tracción (lastre + L-sit pull-up), el human flag y la compresión avanzada',
                'objective' => 'Dominada lastrada +25% BW 3 reps, L-sit pull-up 5, human flag 3 s, V-sit 10 s, straddle press 1, Nordic curl 3',
                'difficultyRating' => 13,
                'color' => '#7f1d1d',
                'requirementsSummary' => 'Mínimos del test N12: full FL 5 s, full planche 3 s, HSPU libre 1, press 1, one-arm push-up 3/lado, dragon pistol 3; G5 y G6 re-verificadas',
                'skillFocus' => 'Dominada lastrada, Human flag, Straddle press',
            ],
            14 => [
                'name' => 'Nivel 14: One-Arm Chin-Up',
                'title' => 'One-Arm Chin-Up',
                'description' => 'La cima unilateral de tracción por la vía supina, más el empuje de élite (planche push-up, 90° push-up) y los front lever raises',
                'objective' => 'One-arm chin-up 1, planche push-up 3, 90° push-up 1, front lever raises 3, dominada lastrada +50% BW 1',
                'difficultyRating' => 14,
                'color' => '#7f1d1d',
                'requirementsSummary' => 'Mínimos del test N13: dominada lastrada +25% BW 3, L-sit pull-up 5, human flag 3 s, V-sit 10 s, straddle press 1, Nordic curl 3',
                'skillFocus' => 'One-arm chin-up, Planche push-up, 90° push-up',
            ],
            15 => [
                'name' => 'Nivel 15: One-Arm Pull-Up',
                'title' => 'One-Arm Pull-Up',
                'description' => 'El último nivel cerrado: la dominada a una mano (prona), la puerta de entrada a la élite de anillas (iron cross, maltese) y los movimientos raros (tiger bend, impossible dip)',
                'objective' => 'One-arm pull-up 1, dominada lastrada +50% BW 3, tiger bend push-up 1, impossible dip 1, iron cross asistido 5 s, maltese asistido 3 s',
                'difficultyRating' => 15,
                'color' => '#7f1d1d',
                'requirementsSummary' => 'Mínimos del test N14: one-arm chin-up 1, planche push-up 3, 90° push-up 1, front lever raises 3, dominada lastrada +50% BW 1',
                'skillFocus' => 'One-arm pull-up, Iron cross, Maltese',
            ],
            16 => [
                'name' => 'Nivel 16: Olimpo',
                'title' => 'Olimpo',
                'description' => 'Bloque élite abierto sin graduación final, diseñado para décadas de práctica tras completar el Nivel 15',
                'objective' => 'Mantener todo el catálogo de élite mientras se persiguen 1-2 objetivos legendarios por ciclo (iron cross, maltese, OAP múltiple, lastre legendario, etc.)',
                'difficultyRating' => 16,
                'color' => '#171717',
                'requirementsSummary' => 'Mínimos del test N15: one-arm pull-up 1, dominada lastrada +50% BW 3, tiger bend 1, impossible dip 1, iron cross asistido 5 s, maltese asistido 3 s',
                'skillFocus' => 'Especialización rotativa, Mantenimiento de élite',
            ],
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getSessionData(int $levelNumber, string $sessionKey, string $phase): array
    {
        $method = match ($sessionKey) {
            'session_a' => 'sessionA',
            'session_b' => 'sessionB',
            'session_c' => 'sessionC',
            'session_d' => 'sessionD',
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };

        $sessionType = match ($sessionKey) {
            'session_a', 'session_b' => 'strength',
            'session_c' => 'circuit',
            'session_d' => 'skill',
        };

        $data = self::{$method}($levelNumber, $phase);
        $data['sessionType'] = $sessionType;
        $data['blocks'] = self::addRestBetweenBlocks($data['blocks'], $levelNumber);

        return $data;
    }

    private static function addRestBetweenBlocks(array $blocks, int $level): array
    {
        if (\count($blocks) <= 1) {
            return $blocks;
        }

        // Transición entre bloques: una pausa breve para cambiar de contexto,
        // no un descanso de recuperación (eso vive dentro de cada bloque).
        $rest = match (true) {
            $level <= 6 => 60,
            $level <= 9 => 90,
            default => 120,
        };

        foreach ($blocks as $index => &$block) {
            if ($index < \count($blocks) - 1) {
                $block['restAfterBlock'] = $rest;
            }
        }

        return $blocks;
    }

    /**
     * Bloque de sesión v4: igual que v3 pero con tipo de bloque obligatorio.
     */
    private static function block(string $blockType, int $rounds, int $restRounds, int $restEx, array $exercises, int $restSets = 0): array
    {
        return [
            'blockType' => $blockType,
            'rounds' => $rounds,
            'restBetweenRounds' => $restRounds,
            'restBetweenExercises' => $restEx,
            'restBetweenSets' => $restSets,
            'exercises' => $exercises,
        ];
    }

    private static function ex(string $name, string $reps, int $sets = 1): array
    {
        return ['name' => $name, 'reps' => $reps, 'sets' => $sets];
    }

    // ========================================================================
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (común por nivel, dosificado por fase)
    // Variantes: A (tal cual), B (+ colgados suaves/activos), C (énfasis tobillo),
    // D (+ respiración diafragmática 360).
    // ========================================================================

    private static function block0(int $level, string $phase, string $sessionKey): array
    {
        return match ($level) {
            1 => self::block0L1($phase, $sessionKey),
            2 => self::block0L2($phase, $sessionKey),
            3 => self::block0L3($phase, $sessionKey),
            4 => self::block0L4($phase, $sessionKey),
            5 => self::block0L5($phase, $sessionKey),
            6 => self::block0L6($phase, $sessionKey),
            7 => self::block0L7($phase, $sessionKey),
            8 => self::block0L8($phase, $sessionKey),
            9 => self::block0L9($phase, $sessionKey),
            10 => self::block0L10($phase, $sessionKey),
            11 => self::block0L11($phase, $sessionKey),
            12 => self::block0L12($phase, $sessionKey),
            13 => self::block0L13($phase, $sessionKey),
            14 => self::block0L14($phase, $sessionKey),
            15 => self::block0L15($phase, $sessionKey),
            16 => self::block0L16($phase, $sessionKey),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function block0L1(string $phase, string $sessionKey): array
    {
        // Dosis de fuerza de muñeca: oraciones + wrist push-ups de rodillas
        [$prayer, $wristReps] = match ($phase) {
            'base' => ['20-30s', '6'],
            'progression' => ['30s', '8'],
            'intensification' => ['30s', '8-10'],
            'deload' => ['30s', '6'],
            default => throw new \InvalidArgumentException(),
        };
        // Columna torácica: cat-camel + foam roller + open books
        [$cat, $foam, $books] = match ($phase) {
            'base' => ['8', '6', '6/lado'],
            'progression' => ['8', '8', '6/lado'],
            'intensification' => ['10', '8', '8/lado'],
            'deload' => ['10', '10', '8/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Dorsiflexión: knee-to-wall + gemelo + sóleo + sentarse en talones
        [$ktw, $calf, $soleus, $heels] = match ($phase) {
            'base' => ['8/lado', '20s/lado', '20s/lado', '20s'],
            'progression' => ['10/lado', '25s/lado', '25s/lado', '25s'],
            'intensification' => ['10/lado', '30s/lado', '30s/lado', '30s'],
            'deload' => ['10/lado', '30s/lado', '30s/lado', '30s'],
            default => throw new \InvalidArgumentException(),
        };

        // Variante C: énfasis en tobillo antes de la sentadilla
        if ('session_c' === $sessionKey) {
            $ktw = \in_array($phase, ['intensification', 'deload'], true) ? '12/lado' : '10/lado';
        }

        $exercises = [
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Wrist Prayer', $prayer, 2),
            self::ex('Knee Wrist Push-Up', $wristReps, 2),
            self::ex('Cat-Camel', $cat),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Open Books', $books),
            self::ex('Knee-to-Wall Rocks', $ktw),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Kneeling Sit', $heels),
        ];

        // Variante B: descolgado progresivo como activación de agarre y hombro
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Dead Hang', 'base' === $phase ? '10-15s' : '15s', 2);
        }
        // Variante D: respiración 360 como preparación del trabajo isométrico
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L2(string $phase, string $sessionKey): array
    {
        // Fuerza de muñeca: oraciones (mantenimiento) + wrist push-ups completos
        $wristReps = match ($phase) {
            'base', 'deload' => '5',
            'progression' => '6-8',
            'intensification' => '8',
            default => throw new \InvalidArgumentException(),
        };
        // Torácica (mantenimiento N1)
        [$cat, $foam, $books] = match ($phase) {
            'base' => ['8', '6', '6/lado'],
            'progression' => ['8', '8', '8/lado'],
            'intensification' => ['10', '8', '8/lado'],
            'deload' => ['10', '10', '8/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro (nuevo N2): wall slides + colgado pasivo
        [$slides, $hang] = match ($phase) {
            'base' => ['8', '20s'],
            'progression' => ['10', '25s'],
            'intensification' => ['10', '30s'],
            'deload' => ['10', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Dorsiflexión (mantenimiento N1)
        [$ktw, $calf, $soleus] = match ($phase) {
            'base' => ['10/lado', '25s/lado', '25s/lado'],
            'progression' => ['10/lado', '30s/lado', '30s/lado'],
            'intensification' => ['12/lado', '30s/lado', '30s/lado'],
            'deload' => ['12/lado', '40s/lado', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Wrist Prayer', '20s', 2),
            self::ex('Wrist Push-Up', $wristReps, 2),
            self::ex('Cat-Camel', $cat),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Open Books', $books),
            self::ex('Wall Slide', $slides, 2),
            self::ex('Dead Hang', $hang),
            self::ex('Knee-to-Wall Rocks', $ktw),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
        ];

        // Variante B: colgados activos suaves de activación
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Active Hang', '5', 2);
        }
        // Variante D: respiración 360 antes del hollow
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L3(string $phase, string $sessionKey): array
    {
        // Fuerza de muñeca: oraciones + wrist push-ups completos + knuckle push-ups de rodillas
        [$wristReps, $knuckle] = match ($phase) {
            'base' => ['5', '4'],
            'progression' => ['6', '5'],
            'intensification' => ['6', '6'],
            'deload' => ['5', '4'],
            default => throw new \InvalidArgumentException(),
        };
        // Torácica (mantenimiento)
        [$cat, $foam, $books] = match ($phase) {
            'base', 'progression' => ['8', '8', '8/lado'],
            'intensification' => ['10', '8', '8/lado'],
            'deload' => ['10', '10', '10/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro (mantenimiento N2)
        [$slides, $hang] = match ($phase) {
            'base' => ['8', '25s'],
            'progression', 'intensification', 'deload' => ['10', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro (nuevo N3 — pista G4): dislocates + reverse plank
        [$dislocates, $reversePlank] = match ($phase) {
            'base' => ['10', '15s'],
            'progression', 'intensification' => ['12', '20s'],
            'deload' => ['12', '25s'],
            default => throw new \InvalidArgumentException(),
        };
        // Isquiosurales/pike (nuevo N3 — pista L-sit/press): pike sentado + ASLR
        [$pike, $aslr] = match ($phase) {
            'base' => ['25s', '6/lado'],
            'progression', 'intensification' => ['30s', '8/lado'],
            'deload' => ['40s', '8/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Dorsiflexión (mantenimiento)
        [$ktw, $calf, $soleus] = match ($phase) {
            'base' => ['10/lado', '30s/lado', '30s/lado'],
            'progression', 'intensification' => ['12/lado', '30s/lado', '30s/lado'],
            'deload' => ['12/lado', '40s/lado', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Wrist Prayer', '20s'),
            self::ex('Wrist Push-Up', $wristReps),
            self::ex('Knuckle Push-Up', $knuckle, 2),
            self::ex('Cat-Camel', $cat),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Open Books', $books),
            self::ex('Wall Slide', $slides, 2),
            self::ex('Dead Hang', $hang),
            self::ex('Shoulder Dislocate', $dislocates, 2),
            self::ex('Reverse Plank', $reversePlank, 2),
            self::ex('Seated Pike Stretch', $pike, 2),
            self::ex('Active Straight Leg Raise', $aslr, 2),
            self::ex('Knee-to-Wall Rocks', $ktw),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
        ];

        // Variante B: colgado activo de activación (ninguna negativa en frío)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Active Hang', '5', 2);
        }
        // Variante D: respiración 360 antes del L-sit
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L4(string $phase, string $sessionKey): array
    {
        // Muñeca — fuerza: wrist push-ups completos + primeras knuckle push-ups
        [$wristReps, $knuckle] = match ($phase) {
            'base', 'progression', 'intensification' => ['8-10', '6-8'],
            'deload' => ['8', '6'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca — movilidad (pista G2): oraciones + extensión palma en suelo
        [$prayer, $wristExt] = match ($phase) {
            'base', 'progression', 'intensification' => ['20s', '20s'],
            'deload' => ['30s', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro (pista G1): wall slides + colgado pasivo + dorsal
        [$slides, $hang, $lat] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '20-30s', '30s/lado'],
            'deload' => ['12', '30s', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Preparación cervical (NUEVO en N4): isométricos en 4 direcciones
        $neck = match ($phase) {
            'base', 'progression', 'intensification' => '10-15s',
            'deload' => '15-20s',
            default => throw new \InvalidArgumentException(),
        };
        // Torácica (transversal)
        [$cat, $foam, $books] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '8', '6/lado'],
            'deload' => ['10', '10', '8/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro (pista G4, desde N3)
        [$dislocates, $reversePlank] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '20s'],
            'deload' => ['12', '25s'],
            default => throw new \InvalidArgumentException(),
        };
        // Pectoral (era de fondos asistidos)
        $chest = match ($phase) {
            'base', 'progression', 'intensification' => '30s/lado',
            'deload' => '40s/lado',
            default => throw new \InvalidArgumentException(),
        };
        // Isquiosurales/pike (desde N3)
        [$aslr, $pike] = match ($phase) {
            'base', 'progression', 'intensification' => ['8/lado', '30s'],
            'deload' => ['8/lado', '40s'],
            default => throw new \InvalidArgumentException(),
        };
        // Tobillo (transversal)
        [$ktw, $soleus] = match ($phase) {
            'base', 'progression', 'intensification' => ['10/lado', '30s'],
            'deload' => ['12/lado', '40s'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Wrist Push-Up', $wristReps, 2),
            self::ex('Knuckle Push-Up', $knuckle, 2),
            self::ex('Wrist Prayer', $prayer, 2),
            self::ex('Wrist Extension Stretch', $wristExt, 2),
            self::ex('Neck Isometric Hold', $neck, 2),
        ];

        // Variante C: versión corta (activación + muñeca + cervical + tobillo)
        if ('session_c' !== $sessionKey) {
            $exercises = array_merge($exercises, [
                self::ex('Wall Slide', $slides, 2),
                self::ex('Dead Hang', $hang),
                self::ex('Lat Stretch', $lat),
                self::ex('Cat-Camel', $cat),
                self::ex('Foam Roll Thoracic Extension', $foam),
                self::ex('Open Books', $books),
                self::ex('Shoulder Dislocate', $dislocates, 2),
                self::ex('Reverse Plank', $reversePlank, 2),
                self::ex('Chest Doorway Stretch', $chest, 2),
                self::ex('Active Straight Leg Raise', $aslr, 2),
                self::ex('Seated Pike Stretch', $pike, 2),
            ]);
        }

        $exercises[] = self::ex('Knee-to-Wall Rocks', $ktw);
        $exercises[] = self::ex('Bent-Knee Soleus Stretch', $soleus);

        // Variantes A/D: empuje escapular en plancha de activación
        if (\in_array($sessionKey, ['session_a', 'session_d'], true)) {
            $exercises[] = self::ex('Scapular Push Up', '8', 2);
        }
        // Variante B: colgado activo + scapular pulls suaves
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Active Hang', '15s');
            $exercises[] = self::ex('Scapular Pull Up', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L5(string $phase, string $sessionKey): array
    {
        // Muñeca — fuerza: knuckle push-ups + fingertip holds progresivos
        [$knuckle, $fingertip] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '10-15s'],
            'deload' => ['6', '10s'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca — mantenimiento G2 + era invertida (back-of-hand + finger saves)
        [$wristExt, $backHand] = match ($phase) {
            'base', 'progression', 'intensification' => ['20s', '20-30s'],
            'deload' => ['30s', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro — mantenimiento G1
        [$slides, $hang, $lat] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '20-30s', '30s/lado'],
            'deload' => ['12', '30s', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cervical (continuación N4, sube dosis)
        $neck = match ($phase) {
            'base', 'progression', 'intensification' => '15-20s',
            'deload' => '20s',
            default => throw new \InvalidArgumentException(),
        };
        // Torácica (NUEVO): extensión activa contra pared + open books
        [$wallThoracic, $books] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '6/lado'],
            'deload' => ['10', '8/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro (pista G4)
        [$dislocates, $reversePlank] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '25s'],
            'deload' => ['12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Tobillo — intensificado
        [$ktw, $soleus, $heels] = match ($phase) {
            'base', 'progression', 'intensification' => ['10/lado', '30s', '30s'],
            'deload' => ['12/lado', '40s', '40s'],
            default => throw new \InvalidArgumentException(),
        };
        // Isquiosurales/pike
        [$aslr, $pike] = match ($phase) {
            'base', 'progression', 'intensification' => ['8/lado', '30s'],
            'deload' => ['8/lado', '40s'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Knuckle Push-Up', $knuckle, 2),
            self::ex('Fingertip Hold', $fingertip, 2),
            self::ex('Wrist Extension Stretch', $wristExt, 2),
            self::ex('Back-of-Hand Stretch', $backHand, 2),
            self::ex('Fingertip Hold', '5-8', 2),
            self::ex('Neck Isometric Hold', $neck, 2),
        ];

        // Variante C: versión corta (activación + muñeca + cervical + tobillo)
        if ('session_c' !== $sessionKey) {
            $exercises = array_merge($exercises, [
                self::ex('Wall Slide', $slides, 2),
                self::ex('Dead Hang', $hang),
                self::ex('Lat Stretch', $lat),
                self::ex('Wall Slide', $wallThoracic, 2),
                self::ex('Open Books', $books),
                self::ex('Shoulder Dislocate', $dislocates, 2),
                self::ex('Reverse Plank', $reversePlank, 2),
                self::ex('Active Straight Leg Raise', $aslr, 2),
                self::ex('Seated Pike Stretch', $pike, 2),
            ]);
        }

        $exercises[] = self::ex('Knee-to-Wall Rocks', $ktw, 2);
        $exercises[] = self::ex('Bent-Knee Soleus Stretch', $soleus);
        $exercises[] = self::ex('Kneeling Sit', $heels);

        // Variante D (inversión): Bloque 0 innegociable + salvado con dedos extra
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Fingertip Hold', '15s');
        }
        // Variante A: empuje escapular en plancha de activación
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Scapular Push Up', '8', 2);
        }
        // Variante B: colgado activo de activación
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Active Hang', '15s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L6(string $phase, string $sessionKey): array
    {
        // Muñeca — fuerza: fingertip holds + wrist curls (base del falso agarre)
        [$fingertip, $wristCurl] = match ($phase) {
            'base', 'progression', 'intensification' => ['15-20s', '12-15'],
            'deload' => ['15s', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca — mantenimiento G2 + era invertida
        [$wristExt, $backHand] = match ($phase) {
            'base', 'progression', 'intensification' => ['20s', '20-30s'],
            'deload' => ['30s', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro — mantenimiento G1
        [$slides, $hang, $lat] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '20-30s', '30s/lado'],
            'deload' => ['12', '30s', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro — APLICADA (pista G4): protagonista en deload
        [$dislocates, $reversePlank, $chest] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '25-30s', '30s/lado'],
            'deload' => ['12', '30s', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Compresión sentada (pista G5, NUEVO protagonista)
        $compression = match ($phase) {
            'base', 'progression', 'intensification' => '5-8s',
            'deload' => '8-10s',
            default => throw new \InvalidArgumentException(),
        };
        // Torácica: extensión activa contra pared + open books
        [$wallThoracic, $books] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '6/lado'],
            'deload' => ['10', '8/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cervical (mantenimiento permanente)
        $neck = '15s';
        // Tobillo
        [$ktw, $soleus] = match ($phase) {
            'base', 'progression', 'intensification' => ['10/lado', '30s'],
            'deload' => ['12/lado', '40s'],
            default => throw new \InvalidArgumentException(),
        };
        // Isquiosurales/pike
        [$aslr, $pike] = match ($phase) {
            'base', 'progression', 'intensification' => ['8/lado', '30s'],
            'deload' => ['8/lado', '40s'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Fingertip Hold', $fingertip, 2),
            self::ex('Fingertip Push Up', '6-8'),
            self::ex('Wrist Curl', $wristCurl, 2),
            self::ex('Reverse Wrist Curl', $wristCurl, 2),
            self::ex('Wrist Extension Stretch', $wristExt, 2),
            self::ex('Back-of-Hand Stretch', $backHand, 2),
            self::ex('Fingertip Hold', '5-8', 1),
            self::ex('Neck Isometric Hold', $neck, 2),
        ];

        // Variante C: versión corta (activación + muñeca + cervical + tobillo)
        if ('session_c' !== $sessionKey) {
            $exercises = array_merge($exercises, [
                self::ex('Wall Slide', $slides, 2),
                self::ex('Dead Hang', $hang),
                self::ex('Lat Stretch', $lat),
                self::ex('Shoulder Dislocate', $dislocates, 2),
                self::ex('Reverse Plank', $reversePlank, 2),
                self::ex('Chest Doorway Stretch', $chest, 2),
                self::ex('Seated Leg Raise', $compression, 3),
                self::ex('Wall Slide', $wallThoracic, 2),
                self::ex('Open Books', $books),
                self::ex('Active Straight Leg Raise', $aslr, 2),
                self::ex('Seated Pike Stretch', $pike, 2),
            ]);
        }

        $exercises[] = self::ex('Knee-to-Wall Rocks', $ktw, 2);
        $exercises[] = self::ex('Bent-Knee Soleus Stretch', $soleus);

        // Variantes A/D: scapular push-ups en proyección suave (activación específica)
        if (\in_array($sessionKey, ['session_a', 'session_d'], true)) {
            $exercises[] = self::ex('Scapular Push Up', '6', 2);
        }
        // Variante B: colgado activo + scapular pulls
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Active Hang', '15s');
            $exercises[] = self::ex('Scapular Pull Up', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L7(string $phase, string $sessionKey): array
    {
        // Muñeca — fuerza (dosis N7): fingertip holds + knuckle push-ups + giros con carga ligera
        [$fingertip, $knuckle] = match ($phase) {
            'base', 'progression', 'intensification' => ['10-15s', '8-10'],
            'deload' => ['10s', '8'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca — mantenimiento G2
        [$wristExt, $backHand] = match ($phase) {
            'base', 'progression', 'intensification' => ['15s', '15s'],
            'deload' => ['20s', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro — mantenimiento G1
        [$slides, $hang] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '20-30s'],
            'deload' => ['10', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro — mantenimiento G4
        [$dislocates, $reversePlank] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '15-20s'],
            'deload' => ['12', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        // Compresión — mantenimiento G5 (protagonista hacia el L-sit)
        $compression = match ($phase) {
            'base', 'progression', 'intensification' => '5-8',
            'deload' => '8',
            default => throw new \InvalidArgumentException(),
        };
        // Foco nuevo N7 (pista G6): Jefferson curl ligero + pike con pies en cajón
        [$jefferson, $pike] = match ($phase) {
            'base', 'progression', 'intensification' => ['5', '20s'],
            'deload' => ['5', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Torácica
        [$books, $foam] = match ($phase) {
            'base', 'progression', 'intensification' => ['6/lado', '8'],
            'deload' => ['8/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Dorsiflexión (pista G3)
        [$ktw, $soleus] = match ($phase) {
            'base', 'progression', 'intensification' => ['10/lado', '12'],
            'deload' => ['12/lado', '12'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Fingertip Hold', $fingertip, 3),
            self::ex('Knuckle Push-Up', $knuckle, 2),
            self::ex('Wrist Curl', '8', 2),
            self::ex('Wrist Extension Stretch', $wristExt, 2),
            self::ex('Back-of-Hand Stretch', $backHand, 2),
            self::ex('Neck Isometric Hold', '10s', 2),
            self::ex('Wall Slide', $slides, 2),
            self::ex('Dead Hang', $hang),
            self::ex('Shoulder Dislocate', $dislocates, 2),
            self::ex('Reverse Plank', $reversePlank),
            self::ex('Seated Leg Raise', $compression, 2),
            self::ex('Jefferson Curl', $jefferson, 2),
            self::ex('Seated Pike Stretch', $pike, 2),
            self::ex('Open Books', $books),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Knee-to-Wall Rocks', $ktw, 2),
            self::ex('Bent-Knee Soleus Stretch', $soleus, 2),
        ];

        // Variante A: proyección escapular en plancha de activación
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Scapular Push Up', '10');
        }
        // Variante B: scapular pulls de activación
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Scapular Pull Up', '8');
        }
        // Variante C: sentadilla al aire de activación
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Air Squat', '15');
        }
        // Variante D: activación específica de skill (ningún estático pesado en frío, §1.7)
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Scapular Push Up', '10');
            $exercises[] = self::ex('Scapular Pull Up', '8');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L8(string $phase, string $sessionKey): array
    {
        // Muñeca — fuerza (dosis N8): wrist curls + fingertip push-ups progresivos
        [$wristCurl, $fingertipPu] = match ($phase) {
            'base', 'progression', 'intensification' => ['12', '5-8'],
            'deload' => ['10', '5'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca — mantenimiento G2
        [$wristExt, $backHand] = match ($phase) {
            'base', 'progression', 'intensification' => ['15s', '15s'],
            'deload' => ['20s', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro — mantenimiento G1
        [$slides, $hang] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '20-30s'],
            'deload' => ['10', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro — mantenimiento G4
        [$dislocates, $reversePlank] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '20s'],
            'deload' => ['12', '25s'],
            default => throw new \InvalidArgumentException(),
        };
        // Compresión — mantenimiento G5
        $compression = match ($phase) {
            'base', 'progression', 'intensification' => '6-8',
            'deload' => '8',
            default => throw new \InvalidArgumentException(),
        };
        // Foco nuevo N8: apertura straddle (pista straddle FL/planche)
        [$frog, $fold, $rockback] = match ($phase) {
            'base', 'progression', 'intensification' => ['30s', '20s', '8/lado'],
            'deload' => ['40s', '30s', '10/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Tobillo afinando G3 (puerta del nivel): knee-to-wall medido + sóleo excéntrico + gemelo en pared
        [$ktw, $soleus, $calf] = match ($phase) {
            'base', 'progression', 'intensification' => ['10/lado', '10/lado', '30s/lado'],
            'deload' => ['12/lado', '10/lado', '40s/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Pike (pista G6): Jefferson curl ligero + pike en cajón
        [$jefferson, $pike] = match ($phase) {
            'base', 'progression', 'intensification' => ['5', '20s'],
            'deload' => ['5', '30s'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Wrist Curl', $wristCurl, 2),
            self::ex('Reverse Wrist Curl', $wristCurl, 2),
            self::ex('Fingertip Push Up', $fingertipPu, 2),
            self::ex('Wrist Extension Stretch', $wristExt, 2),
            self::ex('Back-of-Hand Stretch', $backHand, 2),
            self::ex('Neck Isometric Hold', '10s', 2),
            self::ex('Wall Slide', $slides, 2),
            self::ex('Dead Hang', $hang),
            self::ex('Shoulder Dislocate', $dislocates, 2),
            self::ex('Reverse Plank', $reversePlank),
            self::ex('Seated Leg Raise', $compression, 2),
            self::ex('Frog Stretch', $frog, 2),
            self::ex('Straddle Fold', $fold, 2),
            self::ex('Adductor Rockback', $rockback, 2),
            self::ex('Jefferson Curl', $jefferson, 2),
            self::ex('Seated Pike Stretch', $pike, 2),
            self::ex('Open Books', '6/lado'),
            self::ex('Foam Roll Thoracic Extension', '8'),
            self::ex('Knee-to-Wall Rocks', $ktw, 2),
            self::ex('Eccentric Calf Raise', $soleus, 2),
            self::ex('Calf Stretch Wall', $calf, 2),
        ];

        // Variante A: proyección escapular en plancha de activación
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Scapular Push Up', '10');
        }
        // Variante B: scapular pulls de activación
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Scapular Pull Up', '8');
        }
        // Variante C: sentadilla al aire de activación
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Air Squat', '15');
        }
        // Variante D: activación de skill + German hang asistido suave (base del back lever)
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Scapular Push Up', '10');
            $exercises[] = self::ex('Scapular Pull Up', '8');
            $exercises[] = self::ex('German Hang', '10s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L9(string $phase, string $sessionKey): array
    {
        // Muñeca — fuerza (dosis N9, con ojo en el re-test G2)
        [$wristCurl, $fingertipPu] = match ($phase) {
            'base', 'progression', 'intensification' => ['12', '6-8'],
            'deload' => ['10', '6'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca — movilidad (re-test G2)
        [$wristExt, $backHand] = match ($phase) {
            'base', 'progression', 'intensification' => ['20s', '15s'],
            'deload' => ['25s', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        // Flexión de hombro (re-test G1): extensión torácica activa + wall slides + colgado
        [$slides, $hang, $foam] = match ($phase) {
            'base', 'progression', 'intensification' => ['8', '20-30s', '8'],
            'deload' => ['10', '30s', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Extensión de hombro — mantenimiento G4
        [$dislocates, $reversePlank] = match ($phase) {
            'base', 'progression', 'intensification' => ['10', '20s'],
            'deload' => ['12', '25s'],
            default => throw new \InvalidArgumentException(),
        };
        // Foco N9: pike intensificado (puerta G6 del test)
        [$pikeStand, $pikeSeat, $jefferson] = match ($phase) {
            'base', 'progression', 'intensification' => ['30s', '30s', '5'],
            'deload' => ['40s', '40s', '5'],
            default => throw new \InvalidArgumentException(),
        };
        // Straddle continuado
        [$frog, $fold, $rockback] = match ($phase) {
            'base', 'progression', 'intensification' => ['30s', '20s', '8/lado'],
            'deload' => ['40s', '30s', '10/lado'],
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Wrist Curl', $wristCurl, 2),
            self::ex('Reverse Wrist Curl', $wristCurl, 2),
            self::ex('Fingertip Push Up', $fingertipPu, 2),
            self::ex('Wrist Extension Stretch', $wristExt, 2),
            self::ex('Back-of-Hand Stretch', $backHand, 2),
            self::ex('Neck Isometric Hold', '10s', 2),
            self::ex('Wall Slide', $slides, 2),
            self::ex('Dead Hang', $hang),
            self::ex('Foam Roll Thoracic Extension', $foam),
            self::ex('Shoulder Dislocate', $dislocates, 2),
            self::ex('Reverse Plank', $reversePlank),
            self::ex('Seated Leg Raise', '8', 2),
            self::ex('Standing Pike Stretch', $pikeStand, 2),
            self::ex('Seated Pike Stretch', $pikeSeat, 2),
            self::ex('Jefferson Curl', $jefferson, 2),
            self::ex('Frog Stretch', $frog, 2),
            self::ex('Straddle Fold', $fold, 2),
            self::ex('Adductor Rockback', $rockback, 2),
            self::ex('Open Books', '6/lado'),
            self::ex('Knee-to-Wall Rocks', '10/lado', 2),
            self::ex('Bent-Knee Soleus Stretch', '12', 2),
        ];

        // Variante A: proyección escapular en plancha de activación
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Scapular Push Up', '10');
        }
        // Variante C: sentadilla al aire de activación
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Air Squat', '15');
        }
        // Variantes B/D: activación específica de muscle-up (dominadas suaves;
        // los patrones de transición se ensayan de pie, sin carga)
        if (\in_array($sessionKey, ['session_b', 'session_d'], true)) {
            $exercises[] = self::ex('Strict Pull Up', '5');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L10(string $phase, string $sessionKey): array
    {
        // El doc no dosifica el Bloque 0 por fase: misma dosis en las cuatro.
        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — movilidad (G2 mantenimiento): oraciones + extensión asistida
            // (los círculos de muñeca se pliegan en la activación general)
            self::ex('Wrist Prayer', '30s', 2),
            self::ex('Wrist Extension Stretch', '30s', 2),
            // Muñeca — FUERZA (era overhead + gate de falso agarre)
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            // Omisión: "giros con carga" 2×8/lado (sin equivalente en el catálogo)
            self::ex('Back-of-Hand Stretch', '20s', 2),
            // Torácica + G1 (mantenimiento)
            self::ex('Foam Roll Thoracic Extension', '8'),
            self::ex('Open Books', '6/lado'),
            self::ex('Wall Slide', '8', 2),
            // G3 (mantenimiento)
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G5 (mantenimiento) + nuevo N10 (compresión en pike): mismo ejercicio
            // de catálogo, se funden en la dosis mayor del doc (3×10 s)
            self::ex('Seated Leg Raise', '10s', 3),
            // G6 (mantenimiento): Jefferson curl ligero (alternativa del doc: pike en cajón)
            self::ex('Jefferson Curl', '5', 2),
        ];

        // Fuerza de muñeca solo en A y D (doc): fingertip holds + salvado con dedos
        if (\in_array($sessionKey, ['session_a', 'session_d'], true)) {
            $exercises[] = self::ex('Fingertip Hold', '10s', 2);
            $exercises[] = self::ex('Chest to Wall Handstand', '5/lado', 2);
        }
        // G4 (mantenimiento, re-test en el test): dislocates en A, German hang en B y D
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '10');
        }
        if (\in_array($sessionKey, ['session_b', 'session_d'], true)) {
            $exercises[] = self::ex('German Hang', '10-15s');
        }
        // Específico por sesión
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Band Pull-Apart', '15');
            $exercises[] = self::ex('Pike Push Up', '5');
        }
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('False Grip Hang', '10s', 2);
            $exercises[] = self::ex('Scapular Pull Up', '6');
        }
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Band Monster Walk', '10/lado');
            $exercises[] = self::ex('Dumbbell Romanian Deadlift', '6');
        }
        if ('session_d' === $sessionKey) {
            // Entrada invertida: wall HS pecho a pared (además del salvado con dedos)
            $exercises[] = self::ex('Chest to Wall Handstand', '15s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L11(string $phase, string $sessionKey): array
    {
        // El doc no dosifica el Bloque 0 por fase: misma dosis en las cuatro.
        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — movilidad + FUERZA (era de falso agarre profundo)
            self::ex('Wrist Prayer', '30s', 2),
            self::ex('Back-of-Hand Stretch', '20s', 2),
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            // Omisión: "giros con carga" 2×8/lado (sin equivalente en el catálogo)
            // Torácica (omisión: "stick overhead reach" ×8, sin equivalente en el catálogo)
            self::ex('Foam Roll Thoracic Extension', '8'),
            self::ex('Open Books', '6/lado'),
            // G1-G2 (mantenimiento)
            self::ex('Wall Slide', '8', 2),
            self::ex('Wrist Extension Stretch', '60s'),
            // G3 (mantenimiento)
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G5-G6 (mantenimiento)
            self::ex('Seated Leg Raise', '8s', 2),
            self::ex('Jefferson Curl', '5', 2),
            // Nuevo N11: straddle bajo tensión (pancake activo + adductor rockbacks);
            // las elevaciones alternas de piernas se pliegan en el Straddle Fold
            self::ex('Straddle Fold', '30s', 2),
            self::ex('Adductor Rockback', '8/lado', 2),
        ];

        // Fuerza de muñeca solo en A y D (doc): fingertip holds + salvado con dedos
        if (\in_array($sessionKey, ['session_a', 'session_d'], true)) {
            $exercises[] = self::ex('Fingertip Hold', '10s', 2);
            $exercises[] = self::ex('Chest to Wall Handstand', '5/lado', 2);
        }
        // G4 (mantenimiento crítico: full BL este nivel): dislocates en A,
        // German hang asistido en B y D (siempre antes del back lever)
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '10');
        }
        if (\in_array($sessionKey, ['session_b', 'session_d'], true)) {
            $exercises[] = self::ex('German Hang', '10-15s');
        }
        // Específico por sesión
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Band Pull-Apart', '15');
            $exercises[] = self::ex('Pike Push Up', '5');
        }
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('False Grip Hang', '10s', 2);
            $exercises[] = self::ex('Scapular Pull Up', '6');
        }
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Band Monster Walk', '10/lado');
            $exercises[] = self::ex('Dumbbell Romanian Deadlift', '6');
        }
        if ('session_d' === $sessionKey) {
            // Entrada invertida: wall HS pecho a pared (el German hang ya va arriba)
            $exercises[] = self::ex('Chest to Wall Handstand', '15s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L12(string $phase, string $sessionKey): array
    {
        // El Bloque 0 más largo del programa (mayor carga tendinosa absoluta);
        // el doc no lo dosifica por fase: misma dosis en las cuatro.
        $exercises = [
            self::ex('Jumping Jacks', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — movilidad + FUERZA (dosis máxima: HSPU libre + full planche)
            self::ex('Wrist Prayer', '30s', 2),
            self::ex('Back-of-Hand Stretch', '20s', 2),
            self::ex('Wrist Extension Stretch', '60s'),
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            // Omisión: "giros con carga" 2×8/lado (sin equivalente en el catálogo)
            self::ex('Fingertip Hold', '12s', 2),
            // Torácica (omisión: "stick overhead reach" ×8, sin equivalente en el catálogo)
            self::ex('Foam Roll Thoracic Extension', '8'),
            self::ex('Open Books', '6/lado'),
            // G1-G2-G3 (mantenimiento; la extensión de muñeca ya va arriba)
            self::ex('Wall Slide', '8', 2),
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G4 (mantenimiento): dislocates en todas las sesiones
            self::ex('Shoulder Dislocate', '10'),
            // G5-G6 (re-test en el test): compresión sentada 3×10 s + compresión
            // en pike 2×10 s fundidas (mismo ejercicio de catálogo) + Jefferson curl
            self::ex('Seated Leg Raise', '10s', 3),
            self::ex('Jefferson Curl', '5', 2),
        ];

        // G4 (mantenimiento): German hang asistido en B y D
        if (\in_array($sessionKey, ['session_b', 'session_d'], true)) {
            $exercises[] = self::ex('German Hang', '10s');
        }
        // Salvado con dedos contra pared: obligatorio en A y D (en D el doc añade
        // salvado extra dentro del específico de sesión: se funde en esta dosis)
        if (\in_array($sessionKey, ['session_a', 'session_d'], true)) {
            $exercises[] = self::ex('Chest to Wall Handstand', '5/lado', 2);
        }
        // Específico por sesión
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Band Pull-Apart', '15');
            $exercises[] = self::ex('Pike Push Up', '5');
        }
        if ('session_b' === $sessionKey) {
            // La retracción en anillas ×5 se pliega en los scapular pulls
            $exercises[] = self::ex('Scapular Pull Up', '6');
        }
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Band Monster Walk', '10/lado');
            // Tobillo: dorsiflexión bajo carga (acondicionamiento del dragon pistol)
            $exercises[] = self::ex('Eccentric Calf Raise', '8/lado');
        }
        if ('session_d' === $sessionKey) {
            // Entrada invertida: wall HS pecho a pared (además del salvado con dedos)
            $exercises[] = self::ex('Chest to Wall Handstand', '15s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L13(string $phase, string $sessionKey): array
    {
        // Nivel 13 — Fuerza unilateral. Focos de movilidad: extensión torácica
        // (preparación para human flag) y apertura straddle (preparación para press).
        // El documento no dosifica el Bloque 0 por fase: misma dosis en las cuatro.
        $exercises = [
            // Activación general
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — movilidad + fuerza (dosis alta: planche dinámico + flag)
            self::ex('Wrist Prayer', '60s'),
            self::ex('Back-of-Hand Stretch', '20s', 2),
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            // Omisión: "giros con carga" 2×8/lado no tiene equivalente directo en catálogo
            self::ex('Fingertip Hold', '10s', 2),
            // Torácica en extensión (foco del nivel)
            self::ex('Foam Roll Thoracic Extension', '8'),
            self::ex('Open Books', '8/lado'),
            // Omisión: "stick overhead reach" ×8 y "extensión torácica activa contra pared"
            // 2×6 no tienen equivalente directo en catálogo
            // Apertura straddle (foco del nivel)
            self::ex('Frog Stretch', '30s', 2),
            self::ex('Straddle Fold', '30s', 2),
            self::ex('Adductor Rockback', '8/lado', 2),
            // G1-G2-G3 (mantenimiento)
            self::ex('Wall Slide', '8', 2),
            self::ex('Wrist Extension Stretch', '30s'),
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G4 (mantenimiento): dislocates en todas las sesiones
            self::ex('Shoulder Dislocate', '10'),
            // G5-G6 (mantenimiento)
            self::ex('Seated Leg Raise', '10s', 3),
            self::ex('Jefferson Curl', '5', 2),
            self::ex('Standing Pike Stretch', '30s', 2),
        ];

        // G4: German hang asistido en B y D
        if (\in_array($sessionKey, ['session_b', 'session_d'], true)) {
            $exercises[] = self::ex('German Hang', '10s');
        }
        // Pancake activo: sesiones C y D, diario si el press se resiste
        if (\in_array($sessionKey, ['session_c', 'session_d'], true)) {
            $exercises[] = self::ex('Active Pancake', '20s', 2);
        }
        // Específico por sesión
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Band Pull-Apart', '15');
            $exercises[] = self::ex('Planche Lean', '15s');
        }
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Scapular Pull', '6');
            $exercises[] = self::ex('Dead Hang', '20s');
            $exercises[] = self::ex('Retract Row', '8');
        }
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Miniband Lateral Walk', '12');
            $exercises[] = self::ex('Nordic Curl Negative', '2');
        }
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Lateral Plank Raise', '8/lado');
            $exercises[] = self::ex('Vertical Flag', '10s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L14(string $phase, string $sessionKey): array
    {
        // Nivel 14 — Era del one-arm. Foco: salud capsular del hombro y primer
        // contacto con antebrazo en suelo (tiger bend). Mantenimiento G1-G6.
        $exercises = [
            // Activación general
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — movilidad + fuerza (dosis máxima: planche PU + 90° PU + falso agarre)
            self::ex('Wrist Prayer', '60s'),
            self::ex('Back-of-Hand Stretch', '20s', 2),
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            // Omisión: "giros con carga" 2×8/lado no tiene equivalente directo en catálogo
            self::ex('Fingertip Hold', '10s', 2),
            // Primer contacto con antebrazo en suelo
            self::ex('Forearm Floor Wrist Stretch', '15s', 2),
            // Hombro — salud capsular (foco del nivel)
            self::ex('Shoulder ER Band', '12/lado', 2),
            self::ex('Cuban Rotation', '8/lado', 2),
            self::ex('Cross-Body Shoulder Stretch', '30s/lado', 2),
            self::ex('Shoulder Dislocate', '10'),
            // G1-G3 (mantenimiento)
            self::ex('Wall Slide', '8', 2),
            self::ex('Wrist Extension Stretch', '30s'),
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G5-G6 (mantenimiento)
            self::ex('Seated Leg Raise', '10s', 2),
            self::ex('Standing Pike Stretch', '30s', 2),
        ];

        // G4: German hang asistido en B y D
        if (\in_array($sessionKey, ['session_b', 'session_d'], true)) {
            $exercises[] = self::ex('German Hang', '10s');
        }
        // Antebrazo en suelo: dolphin stretch en todas las sesiones; presión dorsal extra en D
        $exercises[] = self::ex('Dolphin Stretch', '20s', 2);
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Forearm Floor Wrist Stretch', '15s', 2);
        }
        // Straddle fold (mantenimiento G6) en D
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Straddle Fold', '30s', 2);
        }
        // Específico por sesión
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Band Pull-Apart', '15');
            $exercises[] = self::ex('Planche Lean', '15s');
        }
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Scapular Pull', '6');
            $exercises[] = self::ex('Dead Hang', '20s');
            $exercises[] = self::ex('Strict Pull Up', '3');
        }
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Miniband Lateral Walk', '12');
            $exercises[] = self::ex('Nordic Curl Negative', '2');
        }
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('Chest to Wall Handstand', '5/lado', 2);
            $exercises[] = self::ex('Chest to Wall Handstand', '15s');
            $exercises[] = self::ex('False Grip Hang', '10s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L15(string $phase, string $sessionKey): array
    {
        // Nivel 15 — Élite de anillas. Foco: falso agarre profundo + salud capsular.
        // Mantenimiento integral G1-G6.
        $exercises = [
            // Activación general
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — movilidad + fuerza (dosis máxima del programa)
            self::ex('Wrist Prayer', '60s'),
            self::ex('Back-of-Hand Stretch', '30s', 2),
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            // Omisión: "giros con carga" 2×8/lado no tiene equivalente directo en catálogo
            self::ex('Fingertip Hold', '10s', 2),
            self::ex('Forearm Floor Wrist Stretch', '20s', 2),
            // Hombro — salud capsular (era OAP + anillas élite)
            self::ex('Shoulder ER Band', '12/lado', 2),
            self::ex('Cuban Rotation', '8/lado', 2),
            self::ex('Cross-Body Shoulder Stretch', '30s/lado', 2),
            self::ex('Shoulder Dislocate', '10'),
            self::ex('Ring Chest Stretch', '30s', 2),
            // G1-G3 (mantenimiento)
            self::ex('Wall Slide', '8', 2),
            self::ex('Wrist Extension Stretch', '30s'),
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G4: German hang asistido en todas las sesiones a esta altura del programa
            self::ex('German Hang', '10s'),
            // G5-G6 (mantenimiento)
            self::ex('Seated Leg Raise', '10s', 2),
            self::ex('Standing Pike Stretch', '30s', 2),
            self::ex('Straddle Fold', '30s', 2),
        ];

        // Falso agarre: activación previa a toda sesión D
        if ('session_d' === $sessionKey) {
            $exercises[] = self::ex('False Grip Hang', '15s', 2);
            $exercises[] = self::ex('False Grip Support Hold', '10s', 2);
        }
        // Específico por sesión
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Band Pull-Apart', '15');
            $exercises[] = self::ex('Ring Support Hold RTO', '15s');
        }
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Scapular Pull', '6');
            $exercises[] = self::ex('Dead Hang', '20s');
            $exercises[] = self::ex('Strict Pull Up', '3');
        }
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Miniband Lateral Walk', '12');
            $exercises[] = self::ex('Nordic Curl Negative', '2');
        }
        if ('session_d' === $sessionKey) {
            // Activación extra de manguito: se pliega en más ER de banda
            $exercises[] = self::ex('Shoulder ER Band', '12/lado', 2);
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L16(string $phase, string $sessionKey): array
    {
        // Bloque Élite Abierto "Olimpo" (nivel 16). Calentamiento genérico de élite
        // basado en el Nivel 15, sin sesiones específicas. Cubre los focos de
        // movilidad comunes a las especializaciones legendarias (falso agarre,
        // extensión de hombro, muñeca y mantenimiento G1-G6).
        $exercises = [
            // Activación general
            self::ex('Active March', '120s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            // Muñeca — dosis máxima
            self::ex('Wrist Prayer', '60s'),
            self::ex('Back-of-Hand Stretch', '30s', 2),
            self::ex('Wrist Curl', '12', 2),
            self::ex('Reverse Wrist Curl', '12', 2),
            self::ex('Fingertip Hold', '10s', 2),
            self::ex('Forearm Floor Wrist Stretch', '20s', 2),
            // Hombro — salud capsular y extensión
            self::ex('Shoulder ER Band', '12/lado', 2),
            self::ex('Cuban Rotation', '8/lado', 2),
            self::ex('Cross-Body Shoulder Stretch', '30s/lado', 2),
            self::ex('Shoulder Dislocate', '10'),
            self::ex('Ring Chest Stretch', '30s', 2),
            self::ex('German Hang', '10s'),
            // Falso agarre profundo (común en especializaciones de anillas)
            self::ex('False Grip Hang', '15s', 2),
            self::ex('False Grip Support Hold', '10s', 2),
            // G1-G3
            self::ex('Wall Slide', '8', 2),
            self::ex('Wrist Extension Stretch', '30s'),
            self::ex('Knee-to-Wall Rocks', '8/lado', 2),
            // G5-G6
            self::ex('Seated Leg Raise', '10s', 2),
            self::ex('Standing Pike Stretch', '30s', 2),
            self::ex('Straddle Fold', '30s', 2),
            self::ex('Jefferson Curl', '5', 2),
        ];

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function sessionA(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::sessionA_L1($phase),
            2 => self::sessionA_L2($phase),
            3 => self::sessionA_L3($phase),
            4 => self::sessionA_L4($phase),
            5 => self::sessionA_L5($phase),
            6 => self::sessionA_L6($phase),
            7 => self::sessionA_L7($phase),
            8 => self::sessionA_L8($phase),
            9 => self::sessionA_L9($phase),
            10 => self::sessionA_L10($phase),
            11 => self::sessionA_L11($phase),
            12 => self::sessionA_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionB(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::sessionB_L1($phase),
            2 => self::sessionB_L2($phase),
            3 => self::sessionB_L3($phase),
            4 => self::sessionB_L4($phase),
            5 => self::sessionB_L5($phase),
            6 => self::sessionB_L6($phase),
            7 => self::sessionB_L7($phase),
            8 => self::sessionB_L8($phase),
            9 => self::sessionB_L9($phase),
            10 => self::sessionB_L10($phase),
            11 => self::sessionB_L11($phase),
            12 => self::sessionB_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionC(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::sessionC_L1($phase),
            2 => self::sessionC_L2($phase),
            3 => self::sessionC_L3($phase),
            4 => self::sessionC_L4($phase),
            5 => self::sessionC_L5($phase),
            6 => self::sessionC_L6($phase),
            7 => self::sessionC_L7($phase),
            8 => self::sessionC_L8($phase),
            9 => self::sessionC_L9($phase),
            10 => self::sessionC_L10($phase),
            11 => self::sessionC_L11($phase),
            12 => self::sessionC_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionD(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::sessionD_L1($phase),
            2 => self::sessionD_L2($phase),
            3 => self::sessionD_L3($phase),
            4 => self::sessionD_L4($phase),
            5 => self::sessionD_L5($phase),
            6 => self::sessionD_L6($phase),
            7 => self::sessionD_L7($phase),
            8 => self::sessionD_L8($phase),
            9 => self::sessionD_L9($phase),
            10 => self::sessionD_L10($phase),
            11 => self::sessionD_L11($phase),
            12 => self::sessionD_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    // ========================================================================
    // NIVEL 1 — DESPERTAR
    // ========================================================================

    private static function sessionA_L1(string $phase): array
    {
        $b0 = self::block0(1, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje inclinado y plancha', 'goal' => 'Aprender los patrones de empuje y plancha',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Wall Push Up', '10-12', 3),
                        self::ex('Incline Push Up', '6-8', 2),
                        self::ex('Plank', '15-20s', 3),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '12', 2),
                        self::ex('Wall Slide', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute Bridge', '12', 2),
                        self::ex('Arch Hold', '12-15s', 2),
                        self::ex('Bird Dog', '6/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '45s'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje inclinado y plancha', 'goal' => 'Subir reps; primera exposición completa a los gestos del test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Incline Push Up', '8-10', 3),
                        self::ex('Wall Push Up', '8', 2),
                        self::ex('Plank', '25-30s', 3),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute Bridge', '12', 3),
                        self::ex('Arch Hold', '15-20s', 2),
                        self::ex('Bird Dog', '8/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '45s'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje inclinado y plancha', 'goal' => 'Variante más dura del nivel + ensayo de test integrado',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Incline Push Up', '6-8', 4),
                        self::ex('Incline Push Up', '12', 1),
                        self::ex('Plank', '30-40s', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute Bridge', '15', 3),
                        self::ex('Arch Hold', '20-25s', 2),
                        self::ex('Bird Dog', '8/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave y movilidad', 'goal' => 'Mitad de volumen; movilidad protagonista',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Incline Push Up', '8', 2),
                        self::ex('Plank', '20s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '12', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute Bridge', '12', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Foam Roll Thoracic Extension', '10'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L1(string $phase): array
    {
        $b0 = self::block0(1, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Fila muy alta y colgado', 'goal' => 'Primer gesto de tracción y agarre',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8-10', 3),
                        self::ex('Dead Hang', '10-15s', 3),
                        self::ex('Active Hang', '5', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('External Rotation', '10/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute March', '8/lado', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Dead Hang', '20s'),
                        self::ex('Lat Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Fila alta y colgado', 'goal' => 'Fila un punto más inclinada; consolidar el colgado del test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '10', 3),
                        self::ex('Dead Hang', '20s', 3),
                        self::ex('Active Hang', '5-6', 3),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute March', '8/lado', 3),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Dead Hang', '20s'),
                        self::ex('Lat Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Fila alta y colgado', 'goal' => 'Fila media + ensayo de test integrado',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8', 4),
                        self::ex('Australian Pull Up', '12', 1),
                        self::ex('Dead Hang', '25-30s', 3),
                        self::ex('Active Hang', '6', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute March', '10/lado', 3),
                        self::ex('Arch Hold', '20-25s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Dead Hang', '25s'),
                        self::ex('Lat Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave y descolgado', 'goal' => 'Mitad de volumen; sensación de facilidad',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8', 2),
                        self::ex('Dead Hang', '15s', 2),
                        self::ex('Active Hang', '5', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('External Rotation', '10/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Glute Bridge', '12', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L1(string $phase): array
    {
        $b0 = self::block0(1, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito de pierna, core y glúteo medio', 'goal' => 'Patrón de sentadilla y core en circuito (3 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 75, 20, [
                        self::ex('Clamshell', '10/lado'),
                        self::ex('Air Squat', '10'),
                        self::ex('Glute Bridge', '12'),
                        self::ex('Dead Bug', '6/lado'),
                        self::ex('Plank', '20s'),
                        self::ex('Wall Slide', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '12s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                        self::ex('Kneeling Sit', '30s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito de pierna, core y glúteo medio', 'goal' => 'Más rondas y plancha lateral (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 20, [
                        self::ex('Band Monster Walk', '10'),
                        self::ex('Air Squat', '12'),
                        self::ex('Glute Bridge', '15'),
                        self::ex('Dead Bug', '8/lado'),
                        self::ex('Side Plank', '15s/lado'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                        self::ex('Kneeling Sit', '30s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito de pierna, core y glúteo medio', 'goal' => 'Pausa en el fondo + ensayo de sentadilla (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 20, [
                        self::ex('Band Monster Walk', '12'),
                        self::ex('Air Squat', '10'),
                        self::ex('Air Squat', '15'),
                        self::ex('Single Leg Glute Bridge', '8/lado'),
                        self::ex('Plank', '30s'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                        self::ex('Seated Pike Stretch', '30s'),
                        self::ex('Kneeling Sit', '30s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito suave + movilidad protagonista', 'goal' => '2 rondas suaves + movilidad completa del nivel',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 75, 20, [
                        self::ex('Clamshell', '10/lado'),
                        self::ex('Air Squat', '10'),
                        self::ex('Glute Bridge', '12'),
                        self::ex('Dead Bug', '6/lado'),
                        self::ex('Plank', '20s'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    // Movilidad protagonista (tras las rondas): torácica y muñeca
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 20, [
                        self::ex('Cat-Camel', '10'),
                        self::ex('Foam Roll Thoracic Extension', '10'),
                        self::ex('Open Books', '10/lado'),
                        self::ex('Wrist Prayer', '30s', 2),
                    ], 30),
                    // Movilidad protagonista: tobillo y cadera
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 20, [
                        self::ex('Knee-to-Wall Rocks', '12/lado'),
                        self::ex('Calf Stretch Wall', '40s/lado'),
                        self::ex('Bent-Knee Soleus Stretch', '40s/lado'),
                        self::ex('Kneeling Sit', '45s'),
                    ], 30),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L1(string $phase): array
    {
        $b0 = self::block0(1, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — colgado, plancha y patrón de sentadilla', 'goal' => 'Agarre fresco primero; técnica sobre fatiga',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Dead Hang', '10-15s', 3),
                        self::ex('Active Hang', '5', 2),
                        self::ex('Box Squat', '8', 3),
                        self::ex('Plank', '20s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 2),
                        self::ex('Band Pull-Apart', '12', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Bird Dog', '6/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Open Books', '6/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — colgado, plancha y patrón de sentadilla', 'goal' => 'Consolidar las marcas del test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Dead Hang', '20s', 3),
                        self::ex('Active Hang', '6', 3),
                        self::ex('Box Squat', '10', 3),
                        self::ex('Plank', '25s', 3),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Bird Dog', '8/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — colgado, plancha y patrón de sentadilla', 'goal' => 'Ensayos de agarre y plancha (máximos técnicos)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Dead Hang', '30s', 2),
                        self::ex('Active Hang', '5', 3),
                        self::ex('Air Squat', '12', 3),
                        self::ex('Plank', '45s', 1),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Bird Dog', '8/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Técnica suave + revisión de patrones', 'goal' => 'Repasar la mecánica sin fatiga',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Dead Hang', '15s', 2),
                        self::ex('Box Squat', '8', 2),
                        self::ex('Plank', '20s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 2),
                        self::ex('Band Pull-Apart', '12', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Bird Dog', '6/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '90s'),
                        self::ex('Open Books', '8/lado'),
                        self::ex('Foam Roll Thoracic Extension', '6'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 2 — CIMIENTOS
    // ========================================================================

    private static function sessionA_L2(string $phase): array
    {
        $b0 = self::block0(2, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Camino a la flexión — rodillas + excéntrica', 'goal' => 'Peldaños intermedios hacia la flexión completa',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Knee Push Up', '8-10', 3),
                        self::ex('Incline Push Up', '8', 2),
                        self::ex('Support Hold', '8-10s', 3),
                        self::ex('Plank', '30s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '45s'),
                        self::ex('Couch Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Primeras flexiones completas', 'goal' => 'Intento técnico en fresco tras excéntricas de activación',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tempo Push Up', '3', 2),
                        self::ex('Standard Push Up', '1-2', 2),
                        self::ex('Tempo Push Up', '3-4', 2),
                        self::ex('Knee Push Up', '10-12', 3),
                        self::ex('Support Hold', '12s', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '35s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Couch Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Flexiones completas en series + excéntrica lenta', 'goal' => 'Series cortas de calidad + ensayo de test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Standard Push Up', '3-5', 5),
                        self::ex('Tempo Push Up', '3', 3),
                        self::ex('Standard Push Up', '12', 1),
                        self::ex('Support Hold', '10-15s', 3),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25-30s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Mitad de volumen; solo mantener el patrón',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Knee Push Up', '8', 2),
                        self::ex('Standard Push Up', '2-3', 2),
                        self::ex('Support Hold', '10s', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '12', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Foam Roll Thoracic Extension', '10'),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        $b0 = self::block0(2, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Fila baja y colgado activo', 'goal' => 'Cuerpo más horizontal; escápulas inician el tirón',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8', 3),
                        self::ex('Dead Hang', '25s', 3),
                        self::ex('Active Hang', '6', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Couch Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Dead Hang', '25s'),
                        self::ex('Lat Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Fila baja con pecho a barra', 'goal' => 'Pecho a barra en cada rep',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8-10', 4),
                        self::ex('Dead Hang', '30s', 3),
                        self::ex('Active Hang', '5', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '12', 2),
                        self::ex('Couch Stretch', '35s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '30s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Fila baja al máximo técnico', 'goal' => 'Última serie al máximo técnico (anotar)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8-10', 5),
                        self::ex('Active Hang', '4-5', 3),
                        self::ex('Dead Hang', '30s', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '12', 2),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Regala los últimos reps; descarga',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Australian Pull Up', '8', 2),
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Active Hang', '5', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('External Rotation', '10/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '25s', 2),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        $b0 = self::block0(2, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — sentadilla profunda, RDL y gemelo', 'goal' => 'Profundidad completa cómoda (3 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 75, 20, [
                        self::ex('Clamshell', '10/lado'),
                        self::ex('Deep Squat', '12'),
                        self::ex('Dumbbell Romanian Deadlift', '10'),
                        self::ex('Standing Calf Raise', '12'),
                        self::ex('Hollow Body Hold', '12-15s'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                        self::ex('Seated Pike Stretch', '30s'),
                        self::ex('Kneeling Sit', '30s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — sentadilla profunda, RDL y gemelo', 'goal' => 'Transición a gemelo unilateral (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 20, [
                        self::ex('Band Monster Walk', '12'),
                        self::ex('Deep Squat', '15'),
                        self::ex('Dumbbell Romanian Deadlift', '12'),
                        self::ex('Standing Calf Raise', '10'),
                        self::ex('Single Leg Calf Raise', '6/lado'),
                        self::ex('Hollow Body Hold', '15-20s'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '35s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex('Standing Quad Stretch', '30s/lado'),
                        self::ex('Seated Pike Stretch', '30s'),
                        self::ex('Kneeling Sit', '40s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — sentadilla profunda al volumen del test', 'goal' => 'Ronda 1: máximo técnico — ensayo del test de 25 (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 20, [
                        self::ex('Band Monster Walk', '12'),
                        self::ex('Deep Squat', '20'),
                        self::ex('Dumbbell Romanian Deadlift', '12'),
                        self::ex('Single Leg Calf Raise', '10/lado'),
                        self::ex('Hollow Body Hold', '20s'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '40s'),
                        self::ex('Kneeling Sit', '45s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito suave + movilidad protagonista', 'goal' => '2 rondas suaves + movilidad completa del nivel',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 75, 20, [
                        self::ex('Clamshell', '10/lado'),
                        self::ex('Deep Squat', '12'),
                        self::ex('Dumbbell Romanian Deadlift', '10'),
                        self::ex('Standing Calf Raise', '12'),
                        self::ex('Hollow Body Hold', '15s'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    // Movilidad protagonista: torácica y flexión de hombro
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 20, [
                        self::ex('Cat-Camel', '10'),
                        self::ex('Foam Roll Thoracic Extension', '10'),
                        self::ex('Open Books', '10/lado'),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Dead Hang', '30s', 2),
                    ], 30),
                    // Movilidad protagonista: tobillo y cadera
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 20, [
                        self::ex('Knee-to-Wall Rocks', '12/lado'),
                        self::ex('Calf Stretch Wall', '40s/lado'),
                        self::ex('Bent-Knee Soleus Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 30),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        $b0 = self::block0(2, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — hollow y support hold', 'goal' => 'Isométrico más exigente primero (orden neural)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Hollow Body Hold', '12-15s', 4),
                        self::ex('Support Hold', '10s', 3),
                        self::ex('Tempo Push Up', '3', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Bird Dog', '8/lado', 2),
                        self::ex('Couch Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — hollow brazos abajo + support semiasistido', 'goal' => 'Progresión hacia el apoyo libre',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Hollow Body Hold', '15-20s', 4),
                        self::ex('Support Hold', '12-15s', 3),
                        self::ex('Standard Push Up', '2-4', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Bird Dog', '8/lado', 2),
                        self::ex('Couch Stretch', '35s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '90s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — hollow brazos arriba + support libre', 'goal' => 'Ensayo directo del test (últimas series al máximo técnico)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Hollow Body Hold', '20s', 4),
                        self::ex('Support Hold', '15-20s', 4),
                        self::ex('Standard Push Up', '3-4', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Isométricos suaves + revisión', 'goal' => 'Técnica sin lucha; mantener el gesto',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Hollow Body Hold', '15s', 2),
                        self::ex('Support Hold', '10s', 2),
                        self::ex('Standard Push Up', '2-3', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 2),
                        self::ex('Band Pull-Apart', '12', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '90s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 3 — PRIMERA TRACCIÓN
    // ========================================================================

    private static function sessionA_L3(string $phase): array
    {
        $b0 = self::block0(3, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje cerrado — camino a la diamond + pike inicial', 'goal' => 'Diamond inclinada y primer empuje vertical',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Diamond Push Up', '8', 3),
                        self::ex('Pike Push Up', '5-6', 3),
                        self::ex('Standard Push Up', '5-6', 2),
                        self::ex('Support Hold', '15-20s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '10', 3),
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '40s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Diamond de rodillas → primera completa', 'goal' => 'Intento técnico de diamond completa',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Diamond Push Up', '6-8', 4),
                        self::ex('Diamond Push Up', '1-3', 2),
                        self::ex('Pike Push Up', '6-8', 3),
                        self::ex('Standard Push Up', '6', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Diamond completa en series', 'goal' => 'Series cortas de calidad; última serie: máximo técnico (anotar)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Diamond Push Up', '3-5', 5),
                        self::ex('Pike Push Up', '6-8', 4),
                        self::ex('Standard Push Up', '6-8', 2),
                        self::ex('Support Hold', '20s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Arch Hold', '30s', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Mantener el gesto, sin fatiga',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Diamond Push Up', '8', 2),
                        self::ex('Diamond Push Up', '2-3', 2),
                        self::ex('Pike Push Up', '5', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('YTW', '8', 1),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '60s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Shoulder Dislocate', '12'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        $b0 = self::block0(3, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tres vías de dominada — escápulas, negativa, banda', 'goal' => 'Dosis de aprendizaje en las tres vías',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Scapular Pull Up', '4-5', 3),
                        self::ex('Negative Pull Up', '3', 3),
                        self::ex('Band Assisted Pull Up', '5', 3),
                        self::ex('Ring Row - Elevated Feet', '8-10', 4),
                    ], 135),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '10', 3),
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Negativas de 5 s y banda media', 'goal' => 'El freno de 5 s es la marca del test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Scapular Pull Up', '5-6', 4),
                        self::ex('Negative Pull Up', '3', 4),
                        self::ex('Band Assisted Pull Up', '5-6', 3),
                        self::ex('Ring Row - Elevated Feet', '10-12', 4),
                    ], 135),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Couch Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Negativas de 5-6 s y banda ligera (banda del test)', 'goal' => 'Pico excéntrico del ciclo: control total, nunca al fallo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Scapular Pull Up', '6-8', 3),
                        self::ex('Negative Pull Up', '2-3', 5),
                        self::ex('Band Assisted Pull Up', '5', 3),
                        self::ex('Ring Row - Elevated Feet', '10-12', 4),
                    ], 135),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Couch Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Única exposición excéntrica de la fase: suave y sin molestia',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Scapular Pull Up', '5', 2),
                        self::ex('Negative Pull Up', '2', 2),
                        self::ex('Band Assisted Pull Up', '5', 2),
                        self::ex('Ring Row - Elevated Feet', '8', 2),
                    ], 135),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('External Rotation', '10/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Couch Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s', 2),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        $b0 = self::block0(3, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — arranque unilateral de pierna + bisagra', 'goal' => 'Primer split squat y step-up (3 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 75, 20, [
                        self::ex('Band Monster Walk', '12'),
                        self::ex('Stationary Lunge', '8/lado'),
                        self::ex('Step Up', '8/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '10'),
                        self::ex('Single Leg Calf Raise', '10/lado'),
                        self::ex('Seated Calf Raise', '10/lado'),
                        self::ex('Hollow Body Hold', '20-25s'),
                        self::ex('Ring Row - Feet on Ground', '10-12'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '40s'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — unilateral libre + volumen de bisagra', 'goal' => 'Split squat libre con rodilla trasera rozando el suelo (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 20, [
                        self::ex('Band Monster Walk', '12'),
                        self::ex('Stationary Lunge', '10/lado'),
                        self::ex('Step Up', '10/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '12'),
                        self::ex('Single Leg Calf Raise', '10/lado'),
                        self::ex('Seated Calf Raise', '12/lado'),
                        self::ex('Hollow Body Hold', '25-30s'),
                        self::ex('Ring Row - Feet on Ground', '12'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — unilateral con pausa + picos de bisagra', 'goal' => 'Pausa 2 s abajo: fuerza real en el fondo (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 20, [
                        self::ex('Band Monster Walk', '12'),
                        self::ex('Stationary Lunge', '10-12/lado'),
                        self::ex('Step Up', '10-12/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '12'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '12/lado'),
                        self::ex('Hollow Body Hold', '30s'),
                        self::ex('Ring Row - Feet on Ground', '12'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Standing Quad Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito suave + movilidad protagonista', 'goal' => '2 rondas suaves + movilidad completa del nivel',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 75, 20, [
                        self::ex('Clamshell', '10/lado'),
                        self::ex('Stationary Lunge', '8/lado'),
                        self::ex('Step Up', '8/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '10'),
                        self::ex('Standing Calf Raise', '12'),
                        self::ex('Hollow Body Hold', '20s'),
                        self::ex('Ring Row - Feet on Ground', '10'),
                    ], 1),
                    // Movilidad protagonista: hombro completo y colgado
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 20, [
                        self::ex('Shoulder Dislocate', '12', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                        self::ex('Dead Hang', '30s', 2),
                    ], 30),
                    // Movilidad protagonista: pike, tobillo y cadera
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 20, [
                        self::ex('Reverse Plank', '25s', 2),
                        self::ex('Seated Pike Stretch', '45s', 2),
                        self::ex('Active Straight Leg Raise', '10/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '12/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 30),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        $b0 = self::block0(3, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — camino al tuck L-sit + técnica de tracción', 'goal' => 'Apoyo isométrico fresco primero (orden neural)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Support Hold', '15-20s', 3),
                        self::ex('Tuck L-Sit', '6-8s', 3),
                        self::ex('Scapular Pull Up', '5', 2),
                        self::ex('Chin Up Hold', '5s', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — tuck L-sit alterno + isométrico', 'goal' => 'Transición al tuck completo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck L-Sit', '8-10s', 4),
                        self::ex('Tuck L-Sit', '2-4s', 2),
                        self::ex('Scapular Pull Up', '5', 2),
                        self::ex('Chin Up Hold', '5-8s', 3),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — tuck L-sit 5-10 s (mantenimiento de tracción)', 'goal' => 'Ensayo del test de 5 s; descansos largos',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck L-Sit', '5-8s', 5),
                        self::ex('Chin Up Hold', '6-8s', 2),
                        self::ex('Scapular Pull Up', '5', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Skill suave + revisión', 'goal' => 'Sin buscar máximos; patrón fresco',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck L-Sit', '5s', 3),
                        self::ex('Support Hold', '15s', 2),
                        self::ex('Scapular Pull Up', '5', 2),
                    ], 105),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 45),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '60s'),
                        self::ex('Open Books', '8/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 4 — LA PRIMERA DOMINADA
    // ========================================================================

    private static function sessionA_L4(string $phase): array
    {
        $b0 = self::block0(4, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — base overhead y fondos asistidos', 'goal' => 'Pike push-up de calidad y fondos asistidos con profundidad controlada',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Pike Push Up', '4-6', 4),
                        self::ex('Assisted Dips', '5-6', 3),
                        self::ex('Diamond Push Up', '8', 3),
                        self::ex('Standard Push Up', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — más rango, menos asistencia', 'goal' => 'Pike a rango completo si G1 superada; fondos con menos ayuda',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Pike Push Up', '5-6', 5),
                        self::ex('Assisted Dips', '6-8', 4),
                        self::ex('Diamond Push Up', '10', 3),
                        self::ex('Standard Push Up', '10', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '18s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — pico de pike y fondos', 'goal' => 'Pike al máximo rango; fondos mantienen reps con asistencia mínima',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Pike Push Up', '6-8', 5),
                        self::ex('Assisted Dips', '6-8', 4),
                        self::ex('Diamond Push Up', '10-12', 3),
                        self::ex('Explosive Push Up', '5', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('External Rotation', '15/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex("Child's Pose", '60s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Mantener el gesto, sin fatiga; re-test informal de G1/G2',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Pike Push Up', '5', 2),
                        self::ex('Assisted Dips', '6', 2),
                        self::ex('Standard Push Up', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        $b0 = self::block0(4, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — la escalera de la dominada', 'goal' => 'Negativas perfectas y banda cada vez más ligera',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Negative Pull Up', '3', 4),
                        self::ex('Band Assisted Pull Up', '5', 4),
                        self::ex('Scapular Pull Up', '6-8', 3),
                        self::ex('Assisted Pull Up - Supino', '4-6', 3),
                        self::ex('Ring Row - Elevated Feet', '8-10', 3),
                        self::ex('Active Hang', '15-20s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Single Leg Glute Bridge', '10/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex('Dead Hang', '20s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — primeros intentos completos', 'goal' => 'Singles frescos de dominada y chin-up con descansos largos',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '1', 5),
                        self::ex('Negative Pull Up', '3', 3),
                        self::ex('Band Assisted Pull Up', '5-6', 3),
                        self::ex('Chin Up', '1', 3),
                        self::ex('Ring Row - Elevated Feet', '10', 3),
                        self::ex('Active Hang', '20s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '18s', 3),
                        self::ex('Single Leg Glute Bridge', '10/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex('Dead Hang', '20s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — series de dominada', 'goal' => 'Acumular 5-8 dominadas limpias; la primera serie es la referencia del test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '1-2', 5),
                        self::ex('Chin Up', '1-2', 3),
                        self::ex('Negative Pull Up', '2', 2),
                        self::ex('Scapular Pull Up', '8', 3),
                        self::ex('Ring Row - Elevated Feet', '10', 3),
                        self::ex('Active Hang', '20-30s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Glute Bridge', '12/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Sin negativas: descanso excéntrico del bíceps',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Band Assisted Pull Up', '5', 3),
                        self::ex('Scapular Pull Up', '6', 2),
                        self::ex('Ring Row - Elevated Feet', '10', 2),
                        self::ex('Dead Hang', '20-30s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s', 2),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        $b0 = self::block0(4, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — pierna unilateral y core', 'goal' => 'Primer Bulgarian split squat: hito del nivel (3 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 150, 50, [
                        self::ex('Bulgarian Split Squat', '8/lado'),
                        self::ex('High Step Up', '10/lado'),
                        self::ex('Hollow Body Hold', '20-25s'),
                        self::ex('Tuck L-Sit', '5-8s', 2),
                        self::ex('Single Leg Glute Bridge', '10/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '10-12'),
                        self::ex('Single Leg Calf Raise', '10-12/lado'),
                        self::ex('Seated Calf Raise', '12/lado'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('YTW', '6'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '25s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '40s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — pierna con carga progresiva', 'goal' => 'Bulgarian libre o con mochila ligera (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 150, 50, [
                        self::ex('Bulgarian Split Squat', '8-10/lado'),
                        self::ex('High Step Up', '10/lado'),
                        self::ex('Hollow Body Hold', '25-30s'),
                        self::ex('Tuck L-Sit', '8-10s', 2),
                        self::ex('Single Leg Glute Bridge', '10/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '12'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '12-15/lado'),
                        self::ex('Face Pull', '12'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '45s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — pierna fuerte, core exigente', 'goal' => 'Tuck L-sit igualando el mínimo de test dentro del circuito (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 150, 50, [
                        self::ex('Bulgarian Split Squat', '10/lado'),
                        self::ex('High Step Up', '10-12/lado'),
                        self::ex('Hollow Body Hold', '30s'),
                        self::ex('Tuck L-Sit', '10-12s', 2),
                        self::ex('Single Leg Glute Bridge', '12/lado'),
                        self::ex('Dumbbell Romanian Deadlift', '12-15'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '30s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '45s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito regenerativo + movilidad protagonista', 'goal' => '2 rondas suaves + movilidad completa del nivel',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 150, 50, [
                        self::ex('Bulgarian Split Squat', '6/lado'),
                        self::ex('Air Squat', '10'),
                        self::ex('Hollow Body Hold', '15-20s'),
                        self::ex('Tuck L-Sit', '5s', 2),
                        self::ex('Glute Bridge', '12'),
                        self::ex('Standing Calf Raise', '15'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Wall Slide', '10'),
                        self::ex('Active Straight Leg Raise', '8/lado'),
                    ], 1),
                    // Movilidad protagonista: hombro completo y colgado
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 20, [
                        self::ex('Shoulder Dislocate', '12', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                        self::ex('Dead Hang', '30s', 2),
                    ], 30),
                    // Movilidad protagonista: pike, tobillo y cadera
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 20, [
                        self::ex('Reverse Plank', '25s', 2),
                        self::ex('Seated Pike Stretch', '45s', 2),
                        self::ex('Active Straight Leg Raise', '10/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '12/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 30),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Seated Pike Stretch', '60s'),
                        self::ex("Child's Pose", '60s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        $b0 = self::block0(4, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — dominada técnica y L-sit', 'goal' => 'Técnica de la salida de la dominada, de mayor a menor demanda neural',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Negative Pull Up', '2', 3),
                        self::ex('Scapular Pull Up', '5', 3),
                        self::ex('Tuck L-Sit', '5-8s', 5),
                        self::ex('Hollow Body Hold', '20s', 3),
                        self::ex('Support Hold', '15-20s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Dead Hang', '20s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — consolidación de la dominada', 'goal' => 'Singles y dobles estrictos; tuck L-sit camino a los 10 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '1-2', 4),
                        self::ex('Chin Up', '1-2', 3),
                        self::ex('Tuck L-Sit', '8-10s', 5),
                        self::ex('Scapular Pull Up', '6', 3),
                        self::ex('Hollow Body Rock', '8-10', 3),
                        self::ex('Support Hold', '20s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '18s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Seated Leg Raise', '10', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Dead Hang', '20s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — simulacro de test', 'goal' => 'Serie máxima limpia de dominada y hold máximo de tuck L-sit (anotar)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '1', 1),
                        self::ex('Strict Pull Up', '1-2', 3),
                        self::ex('Chin Up', '1', 1),
                        self::ex('Tuck L-Sit', '1', 2),
                        self::ex('Scapular Pull Up', '6', 2),
                        self::ex('Support Hold', '20-25s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '10', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '60s'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Dead Hang', '20s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Técnica y movilidad', 'goal' => 'Dominada con banda a técnica perfecta; re-test informal de G1/G2',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Band Assisted Pull Up', '3-4', 3),
                        self::ex('Tuck L-Sit', '8-10s', 3),
                        self::ex('Hollow Body Hold', '20s', 2),
                        self::ex('Support Hold', '15s', 2),
                    ], 90),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 5 — MUNDO INVERTIDO
    // ========================================================================

    private static function sessionA_L5(string $phase): array
    {
        $b0 = self::block0(5, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — del fondo asistido al fondo completo', 'goal' => 'Primeros fondos completos o con banda ligera, escápulas activas',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Parallel Bar Dips', '3-5', 4),
                        self::ex('Pike Push Up', '8-10', 4),
                        self::ex('Standard Push Up', '12', 3),
                        self::ex('Support Hold', '20-25s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — fondos completos', 'goal' => 'Series de 4-5 fondos completos con profundidad constante',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Parallel Bar Dips', '4-5', 5),
                        self::ex('Pike Push Up', '10', 4),
                        self::ex('Standard Push Up', '10', 3),
                        self::ex('Support Hold', '25-30s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '20', 3),
                        self::ex('External Rotation', '15/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — pico de fondos', 'goal' => 'Serie máxima limpia de fondos (referencia de test); pike mantiene',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Parallel Bar Dips', '1', 1),
                        self::ex('Parallel Bar Dips', '5-6', 4),
                        self::ex('Pike Push Up', '10-12', 4),
                        self::ex('Standard Push Up', '12', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '20', 3),
                        self::ex('External Rotation', '15/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Dumbbell Romanian Deadlift', '12', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Sin fatiga; muñeca y cervical protagonistas',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Parallel Bar Dips', '5', 2),
                        self::ex('Pike Push Up', '8', 2),
                        self::ex('Standard Push Up', '10', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Dumbbell Romanian Deadlift', '10', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        $b0 = self::block0(5, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — volumen de dominadas + contrapeso horizontal', 'goal' => 'Acumular 10-15 dominadas limpias + filas pesadas permanentes',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '2-3', 5),
                        self::ex('Chin Up', '2-3', 3),
                        self::ex('Archer Ring Row', '6/lado', 4),
                        self::ex('Weighted Ring Row', '8-10', 3),
                        self::ex('Scapular Pull Up', '8', 3),
                        self::ex('Active Hang', '20s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Glute Bridge', '12/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — densidad de dominadas', 'goal' => 'Acumular 12-18 reps; primera serie al máximo limpio',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '2-3', 6),
                        self::ex('Chin Up', '3', 3),
                        self::ex('Archer Ring Row', '8/lado', 4),
                        self::ex('Weighted Ring Row', '10', 3),
                        self::ex('Scapular Pull Up', '6', 3),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Glute Bridge', '12/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — series de 3-5 dominadas', 'goal' => 'Serie máxima limpia (referencia de test) + contrapeso mantenido',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '1', 1),
                        self::ex('Strict Pull Up', '3', 5),
                        self::ex('Chin Up', '3-4', 3),
                        self::ex('Archer Ring Row', '8-10/lado', 4),
                        self::ex('Weighted Ring Row', '10-12', 3),
                        self::ex('Scapular Pull Up', '6', 3),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Band Pull-Apart', '15', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Glute Bridge', '12/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Técnica perfecta lejos del fallo; contrapeso reducido pero presente',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '2-3', 3),
                        self::ex('Archer Ring Row', '6/lado', 2),
                        self::ex('Weighted Ring Row', '8', 2),
                        self::ex('Dead Hang', '30s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s', 2),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        $b0 = self::block0(5, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — cossack y fuerza lateral', 'goal' => 'Primer cossack squat: hito del nivel (3 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 150, 50, [
                        self::ex('Cossack Squat', '5-6/lado'),
                        self::ex('Bulgarian Split Squat', '8/lado'),
                        self::ex('Goblet Squat', '10-12'),
                        self::ex('Hip Thrust', '10-12'),
                        self::ex('Hollow Body Hold', '25-30s'),
                        self::ex('Walking Lunge', '8/lado'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Frog Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '40s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — cossack profundo y bilateral con carga', 'goal' => 'Rango amplio con el talón pegado (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 150, 50, [
                        self::ex('Cossack Squat', '6-8/lado'),
                        self::ex('Bulgarian Split Squat', '8/lado'),
                        self::ex('Goblet Squat', '12-15'),
                        self::ex('Hip Thrust', '12'),
                        self::ex('Hollow Body Rock', '10'),
                        self::ex('Walking Lunge', '10/lado'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15/lado'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Wall Slide', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Frog Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — pierna fuerte', 'goal' => 'Cossack a rango máximo controlado con pausa (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 150, 50, [
                        self::ex('Cossack Squat', '8/lado'),
                        self::ex('Bulgarian Split Squat', '10/lado'),
                        self::ex('Goblet Squat', '12'),
                        self::ex('Hip Thrust', '12'),
                        self::ex('Hollow Body Hold', '30s'),
                        self::ex('Walking Lunge', '10/lado'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15-20/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Frog Stretch', '40s/lado'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito regenerativo + movilidad protagonista', 'goal' => '2 rondas suaves + movilidad completa del nivel',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 150, 50, [
                        self::ex('Cossack Squat', '5/lado'),
                        self::ex('Bulgarian Split Squat', '6/lado'),
                        self::ex('Air Squat', '8'),
                        self::ex('Hollow Body Hold', '20s'),
                        self::ex('Glute Bridge', '12'),
                        self::ex('Standing Calf Raise', '15'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Wall Slide', '10'),
                        self::ex('Frog Stretch', '8/lado'),
                    ], 1),
                    // Movilidad protagonista: hombro completo y colgado
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 20, [
                        self::ex('Shoulder Dislocate', '12', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                        self::ex('Dead Hang', '30s', 2),
                    ], 30),
                    // Movilidad protagonista: pike, tobillo y cadera
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 20, [
                        self::ex('Reverse Plank', '25s', 2),
                        self::ex('Seated Pike Stretch', '45s', 2),
                        self::ex('Active Straight Leg Raise', '10/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '12/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 30),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Frog Stretch', '60s/lado'),
                        self::ex('Seated Pike Stretch', '60s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        $b0 = self::block0(5, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — entrada al mundo invertido', 'goal' => 'Primer crow y primer frog stand (hitos); holds cortos con salidas limpias',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Crow Stand', '5-10s', 4),
                        self::ex('Frog Stand', '5-10s', 2),
                        self::ex('Chest to Wall Handstand', '15-20s', 3),
                        self::ex('Tripod Headstand', '15-20s', 3),
                        self::ex('Skin The Cat', '2-3', 2),
                        self::ex('German Hang', '10s', 2),
                        self::ex('Tuck L-Sit', '10s', 2),
                        self::ex('Weighted Ring Row', '10-12', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '10', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '20s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — holds largos y primeras salidas', 'goal' => 'Wall HS con salida a pirueta a ambos lados',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Crow Stand', '10-15s', 5),
                        self::ex('Frog Stand', '10-15s', 3),
                        self::ex('Chest to Wall Handstand', '20-25s', 4),
                        self::ex('Tripod Headstand', '15-20s', 3),
                        self::ex('Skin The Cat', '3', 3),
                        self::ex('German Hang', '12-15s', 2),
                        self::ex('Tuck L-Sit', '10-12s', 2),
                        self::ex('Weighted Ring Row', '10-12', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '10', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '20s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — picos de hold (simulacro de test)', 'goal' => 'Holds máximos frescos de crow, wall HS y headstand (referencia de test)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Crow Stand', '1', 2),
                        self::ex('Frog Stand', '15-20s', 3),
                        self::ex('Chest to Wall Handstand', '1', 2),
                        self::ex('Chest to Wall Handstand', '20s', 2),
                        self::ex('Tripod Headstand', '20-30s', 3),
                        self::ex('Skin The Cat', '3', 2),
                        self::ex('German Hang', '15s', 2),
                        self::ex('Tuck L-Sit', '10-12s', 2),
                        self::ex('Weighted Ring Row', '10-12', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '10', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Inversión regenerativa', 'goal' => 'Solo variantes dominadas a mitad de tiempo; salidas perfectas',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Crow Stand', '8-10s', 3),
                        self::ex('Chest to Wall Handstand', '15s', 3),
                        self::ex('Tripod Headstand', '15-20s', 2),
                        self::ex('German Hang', '10s', 2),
                        self::ex('Tuck L-Sit', '8-10s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Back-of-Hand Stretch', '30s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 6 — PROYECCIÓN
    // ========================================================================

    private static function sessionA_L6(string $phase): array
    {
        $b0 = self::block0(6, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — proyección escapular', 'goal' => 'Primer planche lean y primer pseudo planche (hitos), ángulo conservador',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Planche Lean', '10-15s', 5),
                        self::ex('Pseudo Planche Push Up', '3-5', 4),
                        self::ex('Parallel Bar Dips', '6-8', 4),
                        self::ex('Pike Push Up', '8-10', 3),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Prone Y Raise', '10', 2),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Back-of-Hand Stretch', '20s', 2),
                        self::ex('Wrist Extension Stretch', '20s', 2),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — lean más profundo', 'goal' => 'Progresión de milímetros de ángulo, mismo tiempo de calidad',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Planche Lean', '12-15s', 5),
                        self::ex('Pseudo Planche Push Up', '5-6', 4),
                        self::ex('Parallel Bar Dips', '8', 4),
                        self::ex('Pike Push Up', '10', 3),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Prone Y Raise', '10', 2),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Back-of-Hand Stretch', '20s', 2),
                        self::ex('Wrist Extension Stretch', '20s', 2),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — pico de proyección', 'goal' => 'Hold máximo de lean y serie máxima de pseudo planche (referencias de test)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Planche Lean', '1', 2),
                        self::ex('Planche Lean', '15-20s', 3),
                        self::ex('Pseudo Planche Push Up', '1', 1),
                        self::ex('Pseudo Planche Push Up', '5-6', 3),
                        self::ex('Parallel Bar Dips', '8-10', 3),
                        self::ex('Pike Push Up', '10', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Prone Y Raise', '10', 2),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Back-of-Hand Stretch', '20s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Lean con ángulo cómodo: sensación de ligereza, no de límite',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Planche Lean', '10s', 3),
                        self::ex('Pseudo Planche Push Up', '4', 2),
                        self::ex('Parallel Bar Dips', '6', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '15/lado', 2),
                        self::ex('Prone Y Raise', '10', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Back-of-Hand Stretch', '20s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        $b0 = self::block0(6, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — primer tuck front lever y falso agarre', 'goal' => 'Tuck FL con banda y primer falso agarre correcto (hito)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '3-4', 5),
                        self::ex('Tuck Front Lever', '5-8s', 5),
                        self::ex('False Grip Ring Row', '8', 3),
                        self::ex('Archer Ring Row', '8/lado', 3),
                        self::ex('Scapular Pull Up', '6', 3),
                        self::ex('Active Hang', '20s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '15/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — tuck FL sin banda', 'goal' => '5 s perfectos valen más que 10 s feos: primer tuck FL limpio (logro)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '3-4', 6),
                        self::ex('Tuck Front Lever', '5-8s', 5),
                        self::ex('False Grip Ring Row', '8-10', 3),
                        self::ex('Weighted Ring Row', '10', 3),
                        self::ex('Scapular Pull Up', '6', 3),
                        self::ex('Active Hang', '20-30s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '15/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — pico de tuck FL y dominadas', 'goal' => 'Serie máxima de dominadas y hold máximo de tuck FL (referencias de test)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '1', 1),
                        self::ex('Strict Pull Up', '4', 5),
                        self::ex('Tuck Front Lever', '1', 2),
                        self::ex('Tuck Front Lever', '6-8s', 3),
                        self::ex('False Grip Ring Row', '8', 3),
                        self::ex('Weighted Ring Row', '10-12', 3),
                        self::ex('Scapular Pull Up', '6', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '15/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Single Leg Romanian Deadlift', '8/lado', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20s', 2),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Tuck FL con banda a técnica perfecta, lejos del fallo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '3', 3),
                        self::ex('Tuck Front Lever', '5s', 3),
                        self::ex('False Grip Ring Row', '6', 2),
                        self::ex('Weighted Ring Row', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '15/lado', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Seated Leg Raise', '6', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s', 2),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        $b0 = self::block0(6, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — pistol asistida y single-leg RDL', 'goal' => 'Pistol asistida hacia el hito de 3/pierna (3 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 150, 50, [
                        self::ex('Assisted Pistol Squat', '3-4/lado'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Cossack Squat', '6/lado'),
                        self::ex('Goblet Squat', '12'),
                        self::ex('Seated Leg Raise', '5-8s', 2),
                        self::ex('Hip Thrust', '12'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('Prone Y Raise', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '40s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — pistol con menos apoyo', 'goal' => 'Asistencia de 1-2 dedos con bajada de 3 s (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 150, 50, [
                        self::ex('Assisted Pistol Squat', '4-5/lado'),
                        self::ex('Single Leg Romanian Deadlift', '8-10/lado'),
                        self::ex('Cossack Squat', '6/lado'),
                        self::ex('Goblet Squat', '12-15'),
                        self::ex('Seated Leg Raise', '8-10s', 2),
                        self::ex('Hip Thrust', '12'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — pistol casi libre', 'goal' => 'Apoyo mínimo y compresión al criterio G5 dentro del circuito (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 150, 50, [
                        self::ex('Assisted Pistol Squat', '4-5/lado'),
                        self::ex('Single Leg Romanian Deadlift', '10/lado'),
                        self::ex('Cossack Squat', '8/lado'),
                        self::ex('Goblet Squat', '12'),
                        self::ex('Seated Leg Raise', '1', 2),
                        self::ex('Hip Thrust', '12'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Seated Calf Raise', '15-20/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('Prone Y Raise', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                        self::ex('Standing Quad Stretch', '40s/lado'),
                        self::ex('Seated Pike Stretch', '45s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito regenerativo + movilidad protagonista', 'goal' => '2 rondas suaves + movilidad completa del nivel',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 150, 50, [
                        self::ex('Assisted Pistol Squat', '3/lado'),
                        self::ex('Single Leg Romanian Deadlift', '6/lado'),
                        self::ex('Cossack Squat', '5/lado'),
                        self::ex('Seated Leg Raise', '5-8s', 2),
                        self::ex('Hollow Body Hold', '20s'),
                        self::ex('Standing Calf Raise', '15'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Wall Slide', '10'),
                        self::ex('Active Straight Leg Raise', '8/lado'),
                    ], 1),
                    // Movilidad protagonista: hombro completo y colgado
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 20, [
                        self::ex('Shoulder Dislocate', '12', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '40s/lado', 2),
                        self::ex('Dead Hang', '30s', 2),
                    ], 30),
                    // Movilidad protagonista: pike, tobillo y cadera
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 20, [
                        self::ex('Reverse Plank', '25s', 2),
                        self::ex('Seated Pike Stretch', '45s', 2),
                        self::ex('Active Straight Leg Raise', '10/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '12/lado'),
                        self::ex('Kneeling Hip Flexor Stretch', '45s/lado'),
                    ], 30),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Kneeling Hip Flexor Stretch', '60s/lado'),
                        self::ex('Seated Pike Stretch', '60s'),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        $b0 = self::block0(6, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — peldaños de tuck planche, anillas y G4', 'goal' => 'Frog avanzado y primer ring support (hito); orden neural',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Frog Stand', '5-10s', 5),
                        self::ex('Ring Support Hold', '8-12s', 4),
                        self::ex('Ring Push Up', '5-8', 3),
                        self::ex('False Grip Support Hold', '5-8s', 3),
                        self::ex('Skin The Cat', '3', 3),
                        self::ex('German Hang', '10-12s', 3),
                        self::ex('Tuck L-Sit', '10-12s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '5-8s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Wrist Extension Stretch', '20s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — puntas de pies y false grip support', 'goal' => 'Tuck planche con puntas; ring support cerrando con turnout',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Planche Toe Support', '5-10s', 5),
                        self::ex('Ring Support Hold', '12-15s', 4),
                        self::ex('Ring Push Up', '8-10', 3),
                        self::ex('False Grip Support Hold', '8-10s', 3),
                        self::ex('Skin The Cat', '3', 3),
                        self::ex('German Hang', '12-15s', 3),
                        self::ex('Tuck L-Sit', '12s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Wrist Extension Stretch', '20s', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — simulacro de test (anillas + G4)', 'goal' => 'Tuck planche MANTIENE asistencia; hold máximo de ring support; hito 10 s con banda',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Band Assisted Tuck Planche', '8-10s', 4),
                        self::ex('Ring Support Hold', '1', 2),
                        self::ex('Ring Support Hold', '15s', 2),
                        self::ex('Ring Push Up', '10', 3),
                        self::ex('False Grip Support Hold', '10-12s', 3),
                        self::ex('German Hang', '15s', 2),
                        self::ex('Skin The Cat', '3', 2),
                        self::ex('Seated Leg Raise', '10s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('External Rotation', '12/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Wrist Prayer', '20s'),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Skills regenerativas + re-test de puertas', 'goal' => 'Sin progresión; re-test informal de G4 y G5 al final',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Frog Stand', '5-8s', 3),
                        self::ex('Ring Support Hold', '10-12s', 3),
                        self::ex('Ring Push Up', '6', 2),
                        self::ex('German Hang', '10s', 2),
                        self::ex('Seated Leg Raise', '8s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '15/lado', 2),
                        self::ex('Prone Y Raise', '10', 2),
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('German Hang', '15s'),
                        self::ex('Seated Leg Raise', '10s'),
                        self::ex('Wrist Prayer', '20s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 7 — TUCK Y EXTENSIÓN
    // ========================================================================

    private static function sessionA_L7(string $phase): array
    {
        $b0 = self::block0(7, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje y apoyos — tuck planche con puntas', 'goal' => 'Peldaño §1.6 con apoyo de puntas: proyección máxima, las puntas solo equilibran',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Planche Toe Support', '5-8s', 5),
                        self::ex('Ring Push Up', '5-6', 4),
                        self::ex('Pseudo Planche Push Up', '5-6', 3),
                        self::ex('Tuck L-Sit', '6-10s', 4),
                        self::ex('Elevated Pike Push Up', '5-8', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Prone Y Raise', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Seated Leg Raise', '6', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Seated Pike Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje y apoyos — primeros intentos libres', 'goal' => 'Tuck planche libre 2-4 s y L-sit una pierna: calidad > tiempo, descansos de verdad',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Planche', '2-4s', 5),
                        self::ex('Ring Push Up', '7-9', 4),
                        self::ex('Pseudo Planche Push Up', '7-8', 3),
                        self::ex('L-Sit One Leg', '4-8s', 4),
                        self::ex('Elevated Pike Push Up', '8-10', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Prone Y Raise', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Seated Leg Raise', '6', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Seated Pike Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje y apoyos — pico de tuck planche y L-sit', 'goal' => 'Pico del nivel: tuck planche 5 s limpios (gate) y L-sit 5 s; cada intento perfecto',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Planche', '3-5s', 6),
                        self::ex('Ring Push Up', '9-11', 4),
                        self::ex('Pseudo Planche Push Up', '8', 3),
                        self::ex('L-Sit', '3-6s', 5),
                        self::ex('Elevated Pike Push Up', '8-10', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Prone Y Raise', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Seated Leg Raise', '6', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Seated Pike Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Mantenimiento sin fatiga; movilidad protagonista de camino al test',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Band Assisted Tuck Planche', '4-5s', 3),
                        self::ex('Ring Push Up', '6', 3),
                        self::ex('Tuck L-Sit', '8-10s', 3),
                        self::ex('Elevated Pike Push Up', '6', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Seated Pike Stretch', '40s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        $b0 = self::block0(7, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — adv tuck front lever', 'goal' => 'Tuck FL con espalda plana y retroversión pélvica constante; base de dominadas',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '4-6', 5),
                        self::ex('Advanced Tuck Front Lever', '5-8s', 5),
                        self::ex('Ring Row - Elevated Feet', '8-10', 4),
                        self::ex('False Grip Ring Row', '6-8', 3),
                        self::ex('Scapular Pull Up', '6-8', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Hanging Leg Raise', '8-10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — adv tuck FL sostenido', 'goal' => 'Espalda plana sostenida 6-10 s; la retroversión pélvica manda',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '6-7', 5),
                        self::ex('Advanced Tuck Front Lever', '6-10s', 5),
                        self::ex('Ring Row - Elevated Feet', '10-12', 4),
                        self::ex('False Grip Ring Row', '8', 3),
                        self::ex('Scapular Pull Up', '8', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Hanging Leg Raise', '8-10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — adv tuck FL consolida', 'goal' => 'Consolidación (no pico): 8 s limpios; el pico de bíceps es de la tuck planche en A (§1.7)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '7-8', 4),
                        self::ex('Advanced Tuck Front Lever', '6-8s', 4),
                        self::ex('Ring Row - Elevated Feet', '10-12', 4),
                        self::ex('False Grip Ring Row', '8', 3),
                        self::ex('Scapular Pull Up', '8', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 3),
                        self::ex('Hanging Leg Raise', '8-10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Regresión activa a tuck FL, cero fatiga',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Strict Pull Up', '4-5', 3),
                        self::ex('Tuck Front Lever', '8-10s', 3),
                        self::ex('Ring Row - Elevated Feet', '10', 3),
                        self::ex('False Grip Ring Row', '6', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        $b0 = self::block0(7, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — pistol al cajón alto y single-leg RDL', 'goal' => 'Bajar al cajón sin desplomarse y subir sin impulso (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 50, [
                        self::ex('Box Pistol Squat', '4-5/lado'),
                        self::ex('Goblet Squat', '10-12'),
                        self::ex('Single Leg Romanian Deadlift', '6-8/lado'),
                        self::ex('Single Leg Calf Raise', '10-12/lado'),
                        self::ex('Hollow Body Hold', '20-30s'),
                        self::ex('Hanging Leg Raise', '8-10'),
                        self::ex('Band Pull-Apart', '15'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — checkpoint de hitos de pierna', 'goal' => 'Pistol al cajón alto 5/pierna y single-leg RDL con carga limpia (5 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 5, 75, 50, [
                        self::ex('Box Pistol Squat', '5/lado'),
                        self::ex('Goblet Squat', '12-15'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Single Leg Calf Raise', '12/lado'),
                        self::ex('Hollow Body Hold', '30-40s'),
                        self::ex('Hanging Leg Raise', '10-12'),
                        self::ex('Band Pull-Apart', '15-20'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — cajón más bajo y compresión', 'goal' => 'El cajón baja de altura; L-sit como estación extra de compresión (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 90, 50, [
                        self::ex('Box Pistol Squat', '4-5/lado'),
                        self::ex('Goblet Squat', '10-12'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Single Leg Calf Raise', '12-15/lado'),
                        self::ex('Hollow Body Hold', '30s'),
                        self::ex('L-Sit', '5-8s', 2),
                        self::ex('Band Pull-Apart', '15'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito regenerativo', 'goal' => '3 rondas suaves; descarga larga con couch stretch',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 90, 50, [
                        self::ex('Box Pistol Squat', '3/lado'),
                        self::ex('Goblet Squat', '10'),
                        self::ex('Single Leg Romanian Deadlift', '6/lado'),
                        self::ex('Standing Calf Raise', '15'),
                        self::ex('Hollow Body Hold', '20s'),
                        self::ex('Hanging Leg Raise', '8'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('YTW', '6'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '40s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        $b0 = self::block0(7, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — clap push-up, planche técnica y German hang', 'goal' => 'Trabajo balístico primero y en fresco; skin the cat y German hang siempre calientes',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Explosive Push Up', '3-5', 4),
                        self::ex('Band Assisted Tuck Planche', '4-6s', 5),
                        self::ex('Skin The Cat', '2', 4),
                        self::ex('German Hang', '8-12s', 3),
                        self::ex('Chest to Wall Handstand', '20-25s', 3),
                        self::ex('False Grip Support Hold', '10-15s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Seated Leg Raise', '6', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — primer clap push-up (hito)', 'goal' => 'Checkpoint de hito: primer clap push-up; menos asistencia en la planche',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Clap Push Up', '3-4', 5),
                        self::ex('Band Assisted Tuck Planche', '5-8s', 5),
                        self::ex('Skin The Cat', '3', 4),
                        self::ex('German Hang', '12-16s', 3),
                        self::ex('Chest to Wall Handstand', '25-30s', 3),
                        self::ex('False Grip Support Hold', '15-20s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Seated Leg Raise', '6', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — German hang hacia 20 s (hito)', 'goal' => 'Checkpoint de hito: German hang libre 20 s; planche solo mantenimiento técnico',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Clap Push Up', '4-5', 4),
                        self::ex('Band Assisted Tuck Planche', '4-6s', 4),
                        self::ex('Skin The Cat', '4', 3),
                        self::ex('German Hang', '15-20s', 3),
                        self::ex('Chest to Wall Handstand', '25-30s', 2),
                        self::ex('False Grip Support Hold', '15-20s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Seated Leg Raise', '6', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Movilidad aplicada y técnica ligera', 'goal' => 'Movilidad protagonista (pista G6) + re-verificación de G4 y G5 al final',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Skin The Cat', '2', 3),
                        self::ex('German Hang', '10-12s', 2),
                        self::ex('Clap Push Up', '3', 2),
                        self::ex('Chest to Wall Handstand', '20s', 2),
                        self::ex('Jefferson Curl', '5', 3),
                        self::ex('Seated Pike Stretch', '30s', 3),
                        self::ex('Shoulder Dislocate', '10', 2),
                        self::ex('Seated Leg Raise', '6', 3),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 8 — POTENCIA
    // ========================================================================

    private static function sessionA_L8(string $phase): array
    {
        $b0 = self::block0(8, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — adv tuck planche y ring dip', 'goal' => 'Ring dip protagonista (F1-F2); SBD solo técnico; profundidad: hombro al nivel del codo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Advanced Tuck Planche', '5-8s', 5),
                        self::ex('Ring Dip', '4-6', 4),
                        self::ex('Pseudo Planche Push Up', '6-8', 3),
                        self::ex('Elevated Pike Push Up', '8-10', 3),
                        self::ex('Explosive Dips', '3-5', 4),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Prone Y Raise', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Seated Leg Raise', '8', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Straddle Fold', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — pico de volumen de ring dip', 'goal' => 'Ring dip 6-8 (su fase de pico); planche con menos asistencia y primeros intentos libres',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Advanced Tuck Planche', '4-6s', 5),
                        self::ex('Ring Dip', '6-8', 4),
                        self::ex('Pseudo Planche Push Up', '8', 3),
                        self::ex('Elevated Pike Push Up', '10', 3),
                        self::ex('Explosive Dips', '4-5', 4),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Prone Y Raise', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Seated Leg Raise', '8', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Straddle Fold', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — rotación de picos por ciclo', 'goal' => 'Ciclos impares pica la adv tuck planche, ciclos pares el SBD (modelo N14); ring dip MANTIENE (§1.7)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Advanced Tuck Planche', '3-5s', 5),
                        self::ex('Straight Bar Dips', '5-6', 3),
                        self::ex('Ring Dip', '5-6', 3),
                        self::ex('Elevated Pike Push Up', '8-10', 3),
                        self::ex('Explosive Dips', '4', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                        self::ex('Prone Y Raise', '8', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Seated Leg Raise', '8', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Straddle Fold', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Mantenimiento sin fatiga; movilidad straddle y tobillo protagonistas',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Advanced Tuck Planche', '4-5s', 3),
                        self::ex('Ring Dip', '5', 2),
                        self::ex('Straight Bar Dips', '5', 2),
                        self::ex('Elevated Pike Push Up', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Straddle Fold', '40s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        $b0 = self::block0(8, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción y potencia — dominadas explosivas', 'goal' => 'Velocidad máxima de salida; archer con asistencia; filas pesadas como contrapeso horizontal',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Chest to Bar Practice', '3-4', 5),
                        self::ex('Archer Pull Up', '2/lado', 4),
                        self::ex('Weighted Ring Row', '8-10', 5),
                        self::ex('Ring Row - Elevated Feet', '10-12', 3),
                        self::ex('False Grip Hang', '8-12s', 3),
                        self::ex('Scapular Pull Up', '8', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Hanging Leg Raise', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — primer chest-to-bar y false grip hang (hito)', 'goal' => 'C2B real o explosivas a máxima altura; checkpoint de hito: false grip hang 10-15 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Chest to Bar Pull Up', '2-3', 5),
                        self::ex('Archer Pull Up', '2/lado', 4),
                        self::ex('Weighted Ring Row', '8-10', 5),
                        self::ex('Ring Row - Elevated Feet', '12', 3),
                        self::ex('False Grip Hang', '10-15s', 3),
                        self::ex('False Grip Ring Row', '5-6', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Hanging Leg Raise', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — pico de chest-to-bar', 'goal' => 'Pico del C2B (meta 3-5); archer y false grip mantienen: un solo pico de codo por fase',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Chest to Bar Pull Up', '2-4', 6),
                        self::ex('Archer Pull Up', '2/lado', 3),
                        self::ex('Weighted Ring Row', '8-10', 5),
                        self::ex('Ring Row - Elevated Feet', '10', 3),
                        self::ex('False Grip Hang', '12-15s', 3),
                        self::ex('Scapular Pull Up', '8', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Hanging Leg Raise', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Velocidad sin fatiga; técnica de archer y falso agarre en mantenimiento',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Chest to Bar Practice', '3', 3),
                        self::ex('Archer Pull Up', '2/lado', 2),
                        self::ex('Weighted Ring Row', '10', 3),
                        self::ex('False Grip Hang', '8-10s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        $b0 = self::block0(8, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — pistol al cajón bajo y tuck dragon flag', 'goal' => 'Último peldaño antes del pistol libre; primer peldaño del dragon flag (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 50, [
                        self::ex('Box Pistol Squat', '3-4/lado'),
                        self::ex('Pistol Squat Negative', '2-3/lado'),
                        self::ex('Goblet Squat', '10-12'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Eccentric Calf Raise', '8-10/lado'),
                        self::ex('Dragon Flag', '6-8'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Prone Y Raise', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — hito doble del pistol y dragon flag excéntrico', 'goal' => 'Checkpoints: pistol al cajón bajo + excéntrica 5 s; dragon flag excéntrico 3-4 s (5 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 5, 75, 50, [
                        self::ex('Box Pistol Squat', '4-5/lado'),
                        self::ex('Pistol Squat Negative', '3/lado'),
                        self::ex('Goblet Squat', '12'),
                        self::ex('Single Leg Romanian Deadlift', '8-10/lado'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Dragon Flag Negative', '3-5'),
                        self::ex('Band Pull-Apart', '15-20'),
                        self::ex('Prone Y Raise', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — consolidación de hitos', 'goal' => 'Cajón a su altura mínima del nivel; excéntrica de pistol consolidada (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 90, 50, [
                        self::ex('Box Pistol Squat', '4/lado'),
                        self::ex('Pistol Squat Negative', '2/lado'),
                        self::ex('Goblet Squat', '10-12'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Dragon Flag Negative', '4'),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('YTW', '6'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Frog Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito regenerativo', 'goal' => '3 rondas suaves + knee-to-wall medido (práctica de la puerta G3)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 90, 50, [
                        self::ex('Box Pistol Squat', '3/lado'),
                        self::ex('Goblet Squat', '10'),
                        self::ex('Single Leg Romanian Deadlift', '6/lado'),
                        self::ex('Standing Calf Raise', '15'),
                        self::ex('Dragon Flag', '5'),
                        self::ex('L-Sit', '8s', 2),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Prone Y Raise', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Knee-to-Wall Rocks', '10/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '40s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        $b0 = self::block0(8, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — tuck back lever asistido y SBD técnico', 'goal' => 'Entrada por skin the cat; SBD solo técnico con profundidad limitada (F1-F2)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Back Lever', '5-8s', 5),
                        self::ex('Advanced Tuck Planche', '4-6s', 4),
                        self::ex('Chest to Wall Handstand', '30-35s', 3),
                        self::ex('Straight Bar Dips', '4-6', 3),
                        self::ex('German Hang', '10-15s', 1),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — tuck back lever hacia el gate y wall HS 30-40 s (hito)', 'goal' => 'Menos asistencia camino al gate de 8 s; checkpoint de hito: wall handstand 30-40 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Back Lever', '6-10s', 5),
                        self::ex('Advanced Tuck Planche', '4-6s', 4),
                        self::ex('Chest to Wall Handstand', '35-40s', 3),
                        self::ex('Straight Bar Dips', '6', 3),
                        self::ex('German Hang', '15-20s', 1),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — tuck BL consolida y adv tuck FL viva (hito)', 'goal' => 'Checkpoint de hito: adv tuck FL 10-12 s; tuck BL consolida 8-10 s sin máximos',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Back Lever', '8-10s', 4),
                        self::ex('Advanced Tuck Planche', '4-6s', 4),
                        self::ex('Chest to Wall Handstand', '35-40s', 2),
                        self::ex('Advanced Tuck Front Lever', '8-10s', 3),
                        self::ex('German Hang', '15s', 1),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Movilidad aplicada y técnica ligera', 'goal' => 'Movilidad protagonista (straddle + tobillo); práctica explícita de la puerta G3',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Tuck Back Lever', '6-8s', 3),
                        self::ex('Chest to Wall Handstand', '30s', 2),
                        self::ex('German Hang', '12s', 1),
                        self::ex('Skin The Cat', '2', 2),
                        self::ex('Frog Stretch', '30s', 3),
                        self::ex('Straddle Fold', '20s', 3),
                        self::ex('Adductor Rockback', '10/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '8/lado', 3),
                        self::ex('Jefferson Curl', '5', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('External Rotation', '12/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 9 — MUSCLE-UP
    // ========================================================================

    private static function sessionA_L9(string $phase): array
    {
        $b0 = self::block0(9, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — negativas de HSPU y SBD a rango completo', 'goal' => 'Peldaño previo del HSPU: bajada parcial 3-4 s; desde N9 el SBD abre el rango completo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('HSPU Negative', '2-3', 5),
                        self::ex('Straight Bar Dips', '5-6', 4),
                        self::ex('Advanced Tuck Planche', '6-8s', 4),
                        self::ex('Elevated Pike Push Up', '8-10', 3),
                        self::ex('Ring Push Up', '8-10', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Seated Leg Raise', '8', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — hitos de negativa HSPU y adv tuck planche 10 s', 'goal' => 'Checkpoints: negativa a rango completo 4-6 s y adv tuck planche 10 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('HSPU Negative', '2-3', 5),
                        self::ex('Straight Bar Dips', '6-8', 4),
                        self::ex('Advanced Tuck Planche', '8-10s', 4),
                        self::ex('Elevated Pike Push Up', '10', 3),
                        self::ex('Ring Push Up', '10', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Seated Leg Raise', '8', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — mantenimiento (el pico de codo es del MU)', 'goal' => 'SBD MANTIENE por §1.7; negativas y planche en volumen técnico',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('HSPU Negative', '2-3', 4),
                        self::ex('Straight Bar Dips', '5-6', 3),
                        self::ex('Advanced Tuck Planche', '8-10s', 3),
                        self::ex('Elevated Pike Push Up', '8-10', 3),
                        self::ex('Ring Push Up', '10', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Seated Leg Raise', '8', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje suave', 'goal' => 'Mantenimiento sin fatiga; pike y G1/G2 protagonistas',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('HSPU Negative', '2', 3),
                        self::ex('Straight Bar Dips', '5', 2),
                        self::ex('Advanced Tuck Planche', '6-8s', 2),
                        self::ex('Elevated Pike Push Up', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Standing Pike Stretch', '40s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L9(string $phase): array
    {
        $b0 = self::block0(9, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción y muscle-up — las tres piezas', 'goal' => 'C2B alto + transición asistida + SBD: el MU se gana en las piezas, no en los intentos',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Chest to Bar Pull Up', '3-4', 5),
                        self::ex('Assisted Muscle Up Transition', '3-5', 4),
                        self::ex('Straight Bar Dips', '5', 2),
                        self::ex('One Leg Front Lever Hold', '4-6s', 5),
                        self::ex('Strict Pull Up', '5', 3),
                        self::ex('False Grip Hang', '12-15s', 3),
                        self::ex('Weighted Ring Row', '8-10', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Hanging Leg Raise', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — primeros intentos de muscle-up', 'goal' => 'Intentos libres frescos y espaciados (6×1); checkpoint: false grip hang 15-18 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Bar Muscle Up', '1', 6),
                        self::ex('Chest to Bar Pull Up', '3-4', 4),
                        self::ex('Assisted Muscle Up Transition', '3-4', 3),
                        self::ex('One Leg Front Lever Hold', '4-6s', 5),
                        self::ex('Strict Pull Up', '5-6', 3),
                        self::ex('False Grip Hang', '15-18s', 3),
                        self::ex('Weighted Ring Row', '8-10', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Hanging Leg Raise', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — pico de muscle-up', 'goal' => 'Pico del nivel: MU ×1 limpio (gate) → ×2; descanso completo, si aprieta el tiempo se recorta volumen, nunca descanso',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Bar Muscle Up', '1', 5),
                        self::ex('Chest to Bar Pull Up', '3-5', 4),
                        self::ex('One Leg Front Lever Hold', '5-7s', 5),
                        self::ex('Strict Pull Up', '5-6', 3),
                        self::ex('False Grip Hang', '15s', 2),
                        self::ex('Weighted Ring Row', '8', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('YTW', '6-8', 2),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 3),
                        self::ex('Hanging Leg Raise', '10', 3),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción suave', 'goal' => 'Velocidad sin fatiga; el patrón de transición no se pierde en el deload',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Chest to Bar Pull Up', '3', 3),
                        self::ex('Assisted Muscle Up Transition', '3', 2),
                        self::ex('One Leg Front Lever Hold', '4-5s', 3),
                        self::ex('Strict Pull Up', '5', 2),
                        self::ex('False Grip Hang', '10-12s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('YTW', '8', 2),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L9(string $phase): array
    {
        $b0 = self::block0(9, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — pistol asistido mínimo y dragon flag excéntrico', 'goal' => 'La G3 está superada: apoyo de un dedo si hace falta; excéntrico largo de 4-5 s (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 75, 50, [
                        self::ex('Assisted Pistol Squat', '3-4/lado'),
                        self::ex('Bulgarian Split Squat', '6-8/lado'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Eccentric Calf Raise', '8-10/lado'),
                        self::ex('Dragon Flag Negative', '3-4'),
                        self::ex('L-Sit', '10s', 2),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Prone Y Raise', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — primeras reps de pistol y dragon flag', 'goal' => 'Intentos libres aunque sean 1 rep con un dedo de apoyo; equilibrio, no fuerza bruta (5 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 5, 75, 50, [
                        self::ex('Assisted Pistol Squat', '3/lado'),
                        self::ex('Bulgarian Split Squat', '8/lado'),
                        self::ex('Single Leg Romanian Deadlift', '8-10/lado'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Dragon Flag', '3-4'),
                        self::ex('L-Sit', '10s', 2),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('YTW', '6'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — pico de pistol y dragon flag', 'goal' => 'Pico: pistol libre (meta 1/pierna → 3) y dragon flag completo (meta 3-5), frescos al inicio de la ronda',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 90, 50, [
                        self::ex('Pistol Squat', '1-2/lado'),
                        self::ex('Bulgarian Split Squat', '6/lado'),
                        self::ex('Single Leg Romanian Deadlift', '8/lado'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Dragon Flag', '2-4'),
                        self::ex('L-Sit', '8-10s', 2),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('Prone Y Raise', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Couch Stretch', '30s/lado', 2),
                        self::ex('Frog Stretch', '30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito regenerativo', 'goal' => '3 rondas suaves; pike suave en la descarga (puerta G6)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 3, 90, 50, [
                        self::ex('Box Pistol Squat', '3/lado'),
                        self::ex('Bulgarian Split Squat', '6/lado'),
                        self::ex('Single Leg Romanian Deadlift', '6/lado'),
                        self::ex('Standing Calf Raise', '15'),
                        self::ex('Dragon Flag Negative', '3'),
                        self::ex('L-Sit', '8s', 2),
                        self::ex('Band Pull-Apart', '15'),
                        self::ex('YTW', '6'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado'),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Seated Pike Stretch', '30s', 2),
                        self::ex('Couch Stretch', '40s/lado', 2),
                        self::ex('Diaphragmatic Breathing', '120s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L9(string $phase): array
    {
        $b0 = self::block0(9, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skill — transición, MU negativo y one-leg FL', 'goal' => 'Trabajo técnico puro: la banda quita el 20-30% del peso; entrada al adv tuck BL por skin the cat',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Assisted Muscle Up Transition', '3', 4),
                        self::ex('Muscle Up Negative', '2-3', 3),
                        self::ex('One Leg Front Lever Hold', '4-6s', 4),
                        self::ex('Advanced Tuck Back Lever', '4-6s', 3),
                        self::ex('False Grip Ring Row', '5', 3),
                        self::ex('German Hang', '15s', 1),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skill — MU con banda fina y adv tuck BL (hito)', 'goal' => 'Checkpoints: adv tuck back lever 5-8 s y false grip ring rows con pausa',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Assisted Muscle Up Transition', '2-3', 4),
                        self::ex('Muscle Up Negative', '3', 3),
                        self::ex('One Leg Front Lever Hold', '5-6s', 4),
                        self::ex('Advanced Tuck Back Lever', '5-8s', 3),
                        self::ex('False Grip Ring Row', '5', 3),
                        self::ex('German Hang', '15s', 1),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skill — técnica de MU sin cargar el codo', 'goal' => 'Refinar la transición; si el codo está cargado del pico de B, solo banda (§1.7)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Assisted Muscle Up Transition', '1-2', 4),
                        self::ex('One Leg Front Lever Hold', '5-6s', 3),
                        self::ex('Advanced Tuck Back Lever', '5-8s', 2),
                        self::ex('HSPU Negative', '2', 2),
                        self::ex('False Grip Ring Row', '5', 2),
                        self::ex('German Hang', '15s', 1),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Seated Leg Raise', '8', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Shoulder Dislocate', '10'),
                        self::ex('Dead Hang', '20-30s'),
                        self::ex('Diaphragmatic Breathing', '60s'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Movilidad aplicada y técnica ligera', 'goal' => 'Pike protagonista (G6); ensayo de las puertas tal como se evaluarán (G6, G1, G2)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Assisted Muscle Up Transition', '3', 3),
                        self::ex('Advanced Tuck Back Lever', '5s', 2),
                        self::ex('HSPU Negative', '2', 2),
                        self::ex('German Hang', '12s', 1),
                        self::ex('Standing Pike Stretch', '30s', 3),
                        self::ex('Seated Pike Stretch', '30s', 3),
                        self::ex('Jefferson Curl', '5', 2),
                        self::ex('Wall Slide', '8', 2),
                        self::ex('Wrist Extension Stretch', '20s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 3),
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Cuban Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Dead Hang', '30s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 10 — VERTICAL FUERTE
    // ========================================================================

    private static function sessionA_L10(string $phase): array
    {
        $b0 = self::block0(10, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje vertical — negativas de HSPU y adv tuck planche', 'goal' => 'Técnica y acumulación: la única exposición pesada de planche del microciclo; archer con mano asistente elevada',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('HSPU Negative', '3', 4),
                        self::ex('Elevated Pike Push Up', '6-8', 3),
                        self::ex('Advanced Tuck Planche', '5-8s', 4),
                        self::ex('Pseudo Planche Push Up', '5-6', 3),
                        self::ex('Archer Push Up', '3/lado', 3),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje vertical — primeras reps completas de HSPU', 'goal' => 'Primeras HSPU completas; opción "cadera abierta" en adv tuck si el hold base está sólido (pre-peldaño straddle)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Handstand Push Up', '1-2', 4),
                        self::ex('HSPU Negative', '2', 2),
                        self::ex('Elevated Pike Push Up', '8', 3),
                        self::ex('Advanced Tuck Planche', '6-8s', 5),
                        self::ex('Pseudo Planche Push Up', '6', 3),
                        self::ex('Archer Push Up', '2-3/lado', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('External Rotation', '10/lado', 3),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 3),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje vertical — marcas de test de HSPU y pico de planche', 'goal' => 'Marcas de test (HSPU 3/5); pico de planche (hito 10-12 s) con series recortadas por convivencia con HSPU; pseudo planche solo mantenimiento (anti-coincidencia)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Handstand Push Up', '2-3', 5),
                        self::ex('Elevated Pike Push Up', '5-6', 3),
                        self::ex('Advanced Tuck Planche', '8-12s', 4),
                        self::ex('Pseudo Planche Push Up', '4', 2),
                        self::ex('Archer Push Up', '3-4/lado', 4),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje ligero', 'goal' => 'Volumen al 50% sin fatiga; planche lean sustituye a la adv tuck; Bloque 0 protagonista',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('HSPU Negative', '2', 2),
                        self::ex('Elevated Pike Push Up', '6', 2),
                        self::ex('Planche Lean', '15s', 2),
                        self::ex('Archer Push Up', '2/lado', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Couch Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Lat Stretch', '45s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L10(string $phase): array
    {
        $b0 = self::block0(10, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — MU técnico, falso agarre y contrapeso horizontal', 'goal' => 'Reps de MU perfectas y frescas; contrapeso horizontal pesado permanente (6-10 series por microciclo, no negociable)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Bar Muscle Up', '1', 5),
                        self::ex('Archer Pull Up', '2-3/lado', 3),
                        self::ex('Weighted Ring Row', '6-8', 3),
                        self::ex('Archer Ring Row', '6/lado', 3),
                        self::ex('Strict Pull Up', '4-5', 2),
                        self::ex('False Grip Hang', '10-15s', 4),
                        self::ex('One Leg Front Lever Hold', '5-6s', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — MU dobles y false grip a un paso del gate', 'goal' => 'Series dobles limpias de MU; hitos al alcance: archer ×3-4/lado y one-leg FL 8 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Bar Muscle Up', '2', 4),
                        self::ex('Archer Pull Up', '3/lado', 3),
                        self::ex('Weighted Ring Row', '8', 3),
                        self::ex('Archer Ring Row', '7/lado', 3),
                        self::ex('Strict Pull Up', '4-5', 3),
                        self::ex('False Grip Hang', '15-18s', 4),
                        self::ex('One Leg Front Lever Hold', '6-8s', 4),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Prone Y Raise', '8', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 3),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — rotación de picos MU / gate de falso agarre', 'goal' => 'Rotación por ciclo: ciclo impar → marca de MU (3) con false grip hang contenido (15-20 s); ciclo par → intento del gate (20-25 s) en fresco con MU técnico (2). Dosificación intermedia en el bloque',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Bar Muscle Up', '2-3', 4),
                        self::ex('Archer Pull Up', '3-4/lado', 3),
                        self::ex('Weighted Ring Row', '6-8', 3),
                        self::ex('Archer Ring Row', '8/lado', 3),
                        self::ex('Strict Pull Up', '5', 2),
                        self::ex('False Grip Hang', '17-22s', 3),
                        self::ex('One Leg Front Lever Hold', '8s', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción ligera', 'goal' => 'Mantenimiento sin lastre ni fatiga; reps de MU de calidad sin perseguir volumen',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Bar Muscle Up', '1', 2),
                        self::ex('Ring Row - Elevated Feet', '8', 3),
                        self::ex('Strict Pull Up', '3', 2),
                        self::ex('False Grip Hang', '10s', 2),
                        self::ex('One Leg Front Lever Hold', '4s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Hanging Leg Raise', '6', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Lat Stretch', '45s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L10(string $phase): array
    {
        $b0 = self::block0(10, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — pistol, shrimp asistida e introducción al Nordic', 'goal' => 'Nordic conservador: 4-6 excéntricas totales por sesión, la carga la marca el rango (4 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 90, 50, [
                        self::ex('Pistol Squat', '3/lado'),
                        self::ex('Shrimp Squat', '4/lado'),
                        self::ex('Nordic Curl Negative', '1-2'),
                        self::ex('Dragon Flag', '5'),
                        self::ex('Seated Leg Raise', '15s'),
                        self::ex('Eccentric Calf Raise', '8/lado'),
                        self::ex('Face Pull', '12'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Standing Quad Stretch', '30s/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — shrimp casi libre y Nordic de rango casi completo', 'goal' => 'El doc da 4-5 rondas: se fijan 4; sube la quinta solo si la técnica se mantiene limpia',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 90, 50, [
                        self::ex('Pistol Squat', '4/lado'),
                        self::ex('Shrimp Squat', '5/lado'),
                        self::ex('Nordic Curl Negative', '2-3'),
                        self::ex('Dragon Flag', '6'),
                        self::ex('Seated Leg Raise', '20s'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Standing Quad Stretch', '30s/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — marcas de test de pistol y Nordic completo', 'goal' => 'Pico: pistol ×5/pierna (marca de test), shrimp libre y Nordic con bajada de 5 s a rango total (5 rondas)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 5, 90, 50, [
                        self::ex('Pistol Squat', '5/lado'),
                        self::ex('Shrimp Squat', '5/lado'),
                        self::ex('Nordic Curl Negative', '3'),
                        self::ex('Dragon Flag', '8'),
                        self::ex('Seated Leg Raise', '25s'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Standing Quad Stretch', '30s/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito suave', 'goal' => '2 rondas regenerativas; descarga extendida con movilidad de cadera y tobillo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 120, 50, [
                        self::ex('Pistol Squat', '2/lado'),
                        self::ex('Shrimp Squat', '3/lado'),
                        self::ex('Nordic Curl Negative', '2'),
                        self::ex('Dragon Flag', '3'),
                        self::ex('Seated Leg Raise', '12s'),
                        self::ex('Standing Calf Raise', '12'),
                        self::ex('Face Pull', '12'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                        self::ex('Standing Quad Stretch', '40s/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '8/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L10(string $phase): array
    {
        $b0 = self::block0(10, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skills — pirueta, handstand libre y one-leg back lever', 'goal' => 'La pirueta automática es requisito innegociable antes de soltar la pared; variante one-leg del back lever (única pesada de BL del microciclo); false grip pull-up con banda o pies en cajón',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Handstand Bail Out', '5/lado', 2),
                        self::ex('Freestanding Handstand', '3-5s', 6),
                        self::ex('Pike Press Negative', '2', 4),
                        self::ex('Straddle Back Lever', '4-6s', 4),
                        self::ex('False Grip Pull Up', '2-3', 3),
                        self::ex('L-Sit', '10-12s', 3),
                        self::ex('Straddle Fold', '30s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 2),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skills — press negativo desde el suelo y primer false grip pull-up', 'goal' => 'Hito del nivel: primera dominada completa en falso agarre; press negativo sin cajón (peldaño directo del test); back lever en variante straddle',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Freestanding Handstand', '4-8s', 6),
                        self::ex('Pike Press Negative', '2-3', 4),
                        self::ex('Straddle Back Lever', '4-6s', 4),
                        self::ex('False Grip Pull Up', '1-2', 3),
                        self::ex('L-Sit', '12-15s', 3),
                        self::ex('Straddle Fold', '40s', 2),
                    ], 150),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 3),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 3),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skills — marcas de test de HS libre y press negativo', 'goal' => 'Marcas de test (HS libre 5/10 s, press negativo 3/5); straddle BL consolida 6-8 s sin récord (anti-coincidencia con el pico de planche)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Freestanding Handstand', '5-10s', 8),
                        self::ex('Pike Press Negative', '3', 4),
                        self::ex('Straddle Back Lever', '6-8s', 4),
                        self::ex('False Grip Pull Up', '2', 3),
                        self::ex('L-Sit', '15s', 3),
                        self::ex('Straddle Fold', '45s', 2),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 2),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Lat Stretch', '40s/lado'),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Skills ligero + RE-TEST G4 (German hang)', 'goal' => 'Re-test G4 integrado: German hang asistido 15 s sin dolor ni pinzamiento; si falla, ciclo corto de movilidad sin repetir fuerza; movilidad larga protagonista',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Freestanding Handstand', '3-5s', 4),
                        self::ex('Pike Press Negative', '2', 2),
                        self::ex('German Hang', '15s', 2),
                        self::ex('Straddle Back Lever', '4s', 2),
                        self::ex('L-Sit', '8s', 2),
                        self::ex('Straddle Fold', '45s', 2),
                        self::ex('Standing Pike Stretch', '45s', 2),
                        self::ex('Shoulder Dislocate', '10', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '8s', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Lat Stretch', '45s/lado'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 11 — STRADDLE Y ANILLAS
    // ========================================================================

    private static function sessionA_L11(string $phase): array
    {
        $b0 = self::block0(11, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — straddle planche con puntas y vertical', 'goal' => 'Única exposición pesada de planche del microciclo: peldaño §1.6 con apoyo de puntas y apertura cerrada al inicio; one-arm asistida con mano en superficie elevada (cuerpo en tabla, sin rotar); camino del hito HSPU ×8 (prerequisito de N12)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Planche', '5-8s', 5),
                        self::ex('Handstand Push Up', '5', 4),
                        // One-arm push-up asistida (mano en superficie elevada): se mapea a 'One Arm Push Up'
                        self::ex('One Arm Push Up', '3/lado', 3),
                        self::ex('Ring Dip', '6-8', 4),
                        self::ex('Pseudo Planche Push Up', '5', 2),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — hito straddle planche con banda', 'goal' => 'Hito del nivel: straddle planche asistida con banda a la cadera (las puntas sirven de entrada neural); HSPU a un paso del hito ×8; superficie asistente más baja; hito ring dip ×10 al alcance (alternativa: lastre +10% ×5)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        // Entrada neural con puntas antes de las series con banda
                        self::ex('Straddle Planche', '5s', 2),
                        self::ex('Straddle Planche', '5-8s', 5),
                        self::ex('Handstand Push Up', '6-7', 4),
                        self::ex('One Arm Push Up', '3/lado', 4),
                        self::ex('Ring Dip', '8-10', 4),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('External Rotation', '10/lado', 3),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 3),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — marcas de test de straddle planche e hito HSPU ×8', 'goal' => 'Marcas de test (straddle planche 5/8 s, apertura cerrado → abierto: un cerrado sólido vale más que un abierto temblando); la straddle resta una serie a la vertical (anti-coincidencia); alternativa consolidada: archer ×5/lado; ring dip ×10 consolidado o lastre +10%',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Planche', '4-6s', 4),
                        // Finisher con banda: acumulación de tiempo bajo tensión
                        self::ex('Straddle Planche', '6-8s', 2),
                        self::ex('Handstand Push Up', '8', 4),
                        self::ex('One Arm Push Up', '3/lado', 4),
                        self::ex('Ring Dip', '10', 3),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje ligero', 'goal' => 'Volumen al 50%: regresión a adv tuck planche, sin straddle esta fase; técnica limpia sin fatiga; Bloque 0 protagonista',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Advanced Tuck Planche', '8s', 2),
                        self::ex('Handstand Push Up', '4', 2),
                        self::ex('One Arm Push Up', '2/lado', 2),
                        self::ex('Ring Dip', '6', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Couch Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Standing Pike Stretch', '40s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L11(string $phase): array
    {
        $b0 = self::block0(11, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — straddle FL cerrada, typewriter y falso agarre', 'goal' => 'Única pesada de FL del microciclo (apertura mínima desde la one-leg de N10, retroversión pélvica activa); simetría obligatoria en typewriter (mismo número a cada lado); falso agarre ligero-moderado: el pico vive en D con el MU; contrapeso horizontal pesado permanente (2/2)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Front Lever Hold', '4-6s', 5),
                        self::ex('Typewriter Pull Up', '2', 4),
                        self::ex('False Grip Hang', '15-20s', 3),
                        self::ex('Archer Ring Row', '6-8/lado', 3),
                        self::ex('Weighted Ring Row', '8', 3),
                        // Omisión: dominada explosiva 2×3 (potencia, opcional §5) — sin equivalente en el catálogo
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — straddle FL de apertura media y typewriter ×3', 'goal' => 'Apertura progresiva de la straddle FL; typewriter ×3 alternando el lado de inicio, simetría estricta; false grip rows al pecho (profundidad + retracción, ligero-moderado); contrapeso horizontal 2/2',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Front Lever Hold', '5-7s', 5),
                        self::ex('Typewriter Pull Up', '3', 4),
                        self::ex('False Grip Ring Row', '5', 3),
                        self::ex('Archer Ring Row', '8/lado', 3),
                        self::ex('Weighted Ring Row', '8', 3),
                        // Omisión: dominada explosiva 2×3 — sin equivalente en el catálogo
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Prone Y Raise', '8', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 3),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — marcas de test de straddle FL y typewriter', 'goal' => 'Marcas de test: straddle FL de apertura completa (5/8 s, fresca y primer ejercicio) y typewriter ×3 (objetivo 5, simetría estricta); false grip hang solo mantenimiento del gate de N10 (el pico de falso agarre está en D)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Front Lever Hold', '5-8s', 5),
                        self::ex('Typewriter Pull Up', '3', 5),
                        self::ex('False Grip Hang', '20s', 2),
                        self::ex('Archer Ring Row', '6-8/lado', 3),
                        self::ex('Weighted Ring Row', '6-8', 3),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción ligera', 'goal' => 'Volumen al 50%: regresión a one-leg FL; typewriter técnico con banda ligera si hace falta (patrón sin fatiga); rows sin lastre; mantenimiento de falso agarre',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('One Leg Front Lever Hold', '5s', 2),
                        self::ex('Typewriter Pull Up', '2', 2),
                        self::ex('Ring Row - Elevated Feet', '8', 3),
                        self::ex('False Grip Hang', '12s', 2),
                        self::ex('Scapular Pull Up', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Hanging Leg Raise', '6', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L11(string $phase): array
    {
        $b0 = self::block0(11, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — skater asistida, cossack con carga y Nordic', 'goal' => '4 rondas; skater asistida y cossack con carga (hito del nivel: carga que permita la misma técnica que sin carga); Nordic con bajada de 4-5 s; pancake activo: la apertura straddle ya es posición de trabajo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 120, 50, [
                        self::ex('Skater Squat', '4/lado'),
                        self::ex('Cossack Squat with Load', '5/lado'),
                        self::ex('Pistol Squat', '2/lado'),
                        self::ex('Nordic Curl Negative', '3'),
                        self::ex('Dragon Flag', '5'),
                        // Pancake activo (20 s + 6 elevaciones/lado): las elevaciones se pliegan en el Straddle Fold
                        self::ex('Straddle Fold', '20s'),
                        self::ex('Eccentric Calf Raise', '8/lado'),
                        self::ex('Face Pull', '12'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — skater libre y Nordic de rango casi completo', 'goal' => 'El doc da 4-5 rondas: se fijan 4; sube la quinta solo si la técnica se mantiene limpia',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 120, 50, [
                        self::ex('Skater Squat', '5/lado'),
                        self::ex('Cossack Squat with Load', '6/lado'),
                        self::ex('Pistol Squat', '3/lado'),
                        self::ex('Nordic Curl Negative', '4'),
                        self::ex('Dragon Flag', '6'),
                        self::ex('Straddle Fold', '25s'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — skater libre ×6 y Nordic completa', 'goal' => 'Pico: 5 rondas con skater libre ×6/pierna, cossack con carga pesada y Nordic completa con bajada de 5 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 5, 120, 50, [
                        self::ex('Skater Squat', '6/lado'),
                        self::ex('Cossack Squat with Load', '6/lado'),
                        self::ex('Pistol Squat', '3/lado'),
                        self::ex('Nordic Curl Negative', '3'),
                        self::ex('Dragon Flag', '8'),
                        self::ex('Straddle Fold', '30s'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito suave', 'goal' => '2 rondas regenerativas; cossack sin carga como movilidad; descarga extendida con movilidad de cadera',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 120, 50, [
                        self::ex('Skater Squat', '3/lado'),
                        self::ex('Cossack Squat', '5/lado'),
                        self::ex('Nordic Curl Negative', '2'),
                        self::ex('Dragon Flag', '3'),
                        self::ex('Straddle Fold', '30s'),
                        self::ex('Standing Calf Raise', '12'),
                        self::ex('Face Pull', '12'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                        self::ex('Standing Quad Stretch', '40s/lado', 2),
                        self::ex('Straddle Fold', '45s'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L11(string $phase): array
    {
        $b0 = self::block0(11, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skills — transición de MU en anillas, full BL asistida y equilibrio', 'goal' => 'De mayor a menor demanda neural: transición de MU con pies en el suelo sobre falso agarre profundo, fresca y primera; full BL asistida con banda a la cadera = única pesada de BL del microciclo (German hang obligatorio en el Bloque 0); camino del hito HS libre 15 s',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Ring Muscle Up Transition', '2-3', 4),
                        // Full back lever asistida con banda (peldaño §1.6): se mapea a 'Back Lever Hold'
                        self::ex('Back Lever Hold', '4-6s', 4),
                        self::ex('Freestanding Handstand', '8-12s', 5),
                        self::ex('L-Sit', '10s', 3),
                        self::ex('Straddle Fold', '40s', 2),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 2),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skills — MU en anillas asistido y full BL con holds largos', 'goal' => 'MU en anillas asistido (banda o punta de pies en cajón) con asistencia mínima y falso agarre profundo revisado en cada serie; full BL asistida con holds largos, hombro anterior vigilado (G4 mantenida en el Bloque 0); hito HS libre 15 s al alcance',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        // MU asistido (banda/puntas en cajón): se mapea a 'Ring Muscle Up'
                        self::ex('Ring Muscle Up', '1-2', 4),
                        self::ex('Back Lever Hold', '5-8s', 4),
                        self::ex('Freestanding Handstand', '12-15s', 5),
                        self::ex('Ring Dip', '6', 2),
                        self::ex('L-Sit', '12-15s', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 3),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 3),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skills — marcas de test de MU en anillas y full back lever', 'goal' => 'Marcas de test: MU en anillas (1/2) fresco y primero — un intento de MU tras el full BL es un intento muerto; si la transición se ensucia, volver a asistido —; full BL 3-5 s con la 1.ª serie libre al máximo limpio ≥5 s y volumen contenido (anti-coincidencia con el pico de planche); hito HS libre 15 s consolidado',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Ring Muscle Up', '1', 5),
                        // Acumulación asistida tras los intentos libres: refuerzo del patrón
                        self::ex('Ring Muscle Up', '1-2', 2),
                        self::ex('Back Lever Hold', '3-5s', 4),
                        // Finisher asistido con banda: tiempo bajo tensión adicional
                        self::ex('Back Lever Hold', '6-8s', 2),
                        self::ex('Freestanding Handstand', '15s', 5),
                        self::ex('L-Sit', '15s', 2),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 2),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Skills ligero + revisión personal de puertas G1-G6', 'goal' => 'Patrón técnico sin carga completa; sin re-tests formales en este nivel: revisión personal de las seis puertas (cubiertas por el Bloque 0 completo, 1 exposición suave por puerta) — todo en verde para entrar al siguiente ciclo; movilidad straddle larga protagonista',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Ring Muscle Up Transition', '2', 3),
                        self::ex('Back Lever Hold', '4s', 2),
                        self::ex('Freestanding Handstand', '8-10s', 4),
                        self::ex('L-Sit', '10s', 2),
                        // Movilidad larga: pancake + straddle fold (protagonista de la fase)
                        self::ex('Straddle Fold', '60s', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '8s', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 12 — FULL LEVERS
    // ========================================================================

    private static function sessionA_L12(string $phase): array
    {
        $b0 = self::block0(12, $phase, 'session_a');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión A: Empuje — consolidación de straddle planche y empuje unilateral', 'goal' => 'Única pesada de planche del microciclo: consolidación de straddle camino del hito 8-10 s, con adv tuck como entrada neural; último peldaño asistido del one-arm (superficie baja, cero rotación); ring dip profundo RTO (rango máximo controlado, giro externo completo)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Planche', '6-8s', 5),
                        self::ex('Advanced Tuck Planche', '8s', 2),
                        // One-arm push-up asistida (superficie baja): se mapea a 'One Arm Push Up'
                        self::ex('One Arm Push Up', '2/lado', 4),
                        self::ex('Ring Dip', '5', 3),
                        self::ex('Pseudo Planche Push Up', '5', 2),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión A: Empuje — hito full planche con banda y primeras one-arm', 'goal' => 'Hito del nivel: full planche asistida con banda a la cadera y piernas juntas; la straddle 8-10 s no se abandona; primeras one-arm completas frescas (si rota la cadera, volver a asistida en superficie baja); hito Bulgarian dip / ring dip profundo RTO al alcance',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        // Full planche asistida con banda: se mapea a 'Planche'
                        self::ex('Planche', '3-5s', 5),
                        self::ex('Straddle Planche', '8-10s', 2),
                        self::ex('One Arm Push Up', '1/lado', 3),
                        // Acumulación asistida tras los intentos libres
                        self::ex('One Arm Push Up', '3/lado', 2),
                        self::ex('Bulgarian Ring Dip', '5-6', 4),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 3),
                        self::ex('External Rotation', '10/lado', 3),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 3),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión A: Empuje — intentos de marca de full planche y one-arm push-up', 'goal' => 'Intentos de marca de full planche (3/5 s), frescos y primeros, cada intento cuenta, sin grinders; marca de one-arm push-up (3/5 por lado); máximo un frente estático pesado por sesión; molestia matinal de bíceps >2/10 = congelar planche un ciclo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Planche', '2-4s', 4),
                        // Acumulación asistida con banda tras los intentos
                        self::ex('Planche', '5s', 2),
                        self::ex('Straddle Planche', '8-10s', 2),
                        self::ex('One Arm Push Up', '2-3/lado', 4),
                        self::ex('Ring Dip', '6', 3),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Single Leg Romanian Deadlift', '6/lado', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión A: Empuje ligero', 'goal' => 'Volumen al 50%: straddle ligera de mantenimiento, sin full esta fase; rango cómodo; Bloque 0 protagonista y muñeca con descarga extra',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Planche', '5s', 2),
                        self::ex('One Arm Push Up', '2/lado', 2),
                        self::ex('Ring Dip', '6', 2),
                        self::ex('Pseudo Planche Push Up', '5', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '15', 2),
                        self::ex('External Rotation', '10/lado', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                        self::ex('Couch Stretch', '40s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L12(string $phase): array
    {
        $b0 = self::block0(12, $phase, 'session_b');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión B: Tracción — consolidación de straddle FL e introducción al full', 'goal' => 'Única pesada de FL del microciclo: consolidación de straddle camino del hito 8 s, con introducción de full asistida con banda (peldaño §1.6, carga moderada dentro de la misma exposición); contrapeso horizontal pesado permanente (2/2, nivel press-dominante); dominada estricta como base viva para el lastre de N13',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Front Lever Hold', '6-8s', 5),
                        // Full FL asistida con banda (introducción): se mapea a 'Full Front Lever Hold'
                        self::ex('Full Front Lever Hold', '3-4s', 2),
                        self::ex('Archer Ring Row', '6-8/lado', 3),
                        self::ex('Weighted Ring Row', '8', 3),
                        self::ex('Strict Pull Up', '4-6', 3),
                        self::ex('Scapular Pull Up', '6', 2),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Lat Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión B: Tracción — hito full front lever con banda', 'goal' => 'Hito del nivel: full front lever asistida con banda, piernas juntas y extendidas; hito straddle FL 8 s consolidado; la vía unilateral (typewriter) no se enfría antes de N13',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Full Front Lever Hold', '4-5s', 5),
                        self::ex('Straddle Front Lever Hold', '8s', 2),
                        self::ex('Archer Ring Row', '8/lado', 3),
                        self::ex('Weighted Ring Row', '6-8', 3),
                        self::ex('Strict Pull Up', '5', 3),
                        self::ex('Typewriter Pull Up', '3', 2),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 3),
                        self::ex('Prone Y Raise', '8', 3),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 3),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Lat Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión B: Tracción — intentos de marca de full front lever', 'goal' => 'Intentos de marca de full front lever (5/8 s), frescos y primeros: la 1.ª serie es un hold libre al máximo limpio con objetivo ≥5 s; volumen contenido y sin coincidencia de picos con la planche (anti-coincidencia §1.7)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Full Front Lever Hold', '3-5s', 4),
                        // Acumulación asistida con banda tras los intentos
                        self::ex('Full Front Lever Hold', '5s', 2),
                        self::ex('Straddle Front Lever Hold', '8s', 2),
                        self::ex('Archer Ring Row', '6-8/lado', 3),
                        self::ex('Weighted Ring Row', '6-8', 3),
                        self::ex('Strict Pull Up', '5', 2),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                        self::ex('Hanging Leg Raise', '8', 2),
                        self::ex('Couch Stretch', '30s/lado', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Lat Stretch', '40s/lado'),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión B: Tracción ligera', 'goal' => 'Volumen al 50%: straddle FL ligera de mantenimiento, sin full esta fase; rows sin lastre; typewriter técnico',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Straddle Front Lever Hold', '5s', 2),
                        self::ex('Ring Row - Elevated Feet', '8', 3),
                        self::ex('Strict Pull Up', '4', 2),
                        self::ex('Typewriter Pull Up', '2', 2),
                        self::ex('Scapular Pull Up', '8', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Band Pull-Apart', '15', 2),
                        self::ex('Prone Y Raise', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Hanging Leg Raise', '6', 2),
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Lat Stretch', '45s/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L12(string $phase): array
    {
        $b0 = self::block0(12, $phase, 'session_c');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión C: Circuito — dragon pistol asistida y V-sit asistido', 'goal' => '4 rondas; dragon pistol asistida con agarre de apoyo (la dorsiflexión bajo carga unilateral exige la G3); V-sit asistido = peldaño del V-sit de N13; Nordic completa con bajada de 5 s como puente al Nordic de N13',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 120, 50, [
                        // Dragon pistol asistida (agarre de apoyo): se mapea a 'Dragon Pistol Squat'
                        self::ex('Dragon Pistol Squat', '3/lado'),
                        self::ex('Pistol Squat', '3/lado'),
                        self::ex('Nordic Curl Negative', '3'),
                        self::ex('V-Sit Hold', '8-10s'),
                        self::ex('Dragon Flag', '5'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Face Pull', '12'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Standing Quad Stretch', '30s/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión C: Circuito — dragon pistol con asistencia mínima y Nordic semicompleto', 'goal' => 'El doc da 4-5 rondas: se fijan 4; dragon pistol con asistencia mínima + excéntrica libre; Nordic con tirón asistido por banda (semicompleto)',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 4, 120, 50, [
                        self::ex('Dragon Pistol Squat', '4/lado'),
                        self::ex('Pistol Squat', '3/lado'),
                        self::ex('Nordic Curl Negative', '3'),
                        self::ex('V-Sit Hold', '10-12s'),
                        self::ex('Dragon Flag', '6'),
                        self::ex('Eccentric Calf Raise', '10/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Standing Quad Stretch', '30s/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión C: Circuito — marca de test de dragon pistol', 'goal' => 'Marca de test de dragon pistol (3/5 por pierna) en 5 rondas; Nordic completa con asistencia mínima solo al final',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 5, 120, 50, [
                        self::ex('Dragon Pistol Squat', '3/lado'),
                        self::ex('Pistol Squat', '3/lado'),
                        self::ex('Nordic Curl Negative', '3'),
                        self::ex('V-Sit Hold', '12-15s'),
                        self::ex('Dragon Flag', '8'),
                        self::ex('Eccentric Calf Raise', '12/lado'),
                        self::ex('Face Pull', '15'),
                        self::ex('YTW', '10'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '30s'),
                        self::ex('Standing Quad Stretch', '30s/lado', 2),
                        self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión C: Circuito suave', 'goal' => '2 rondas regenerativas; dragon pistol asistida; descarga extendida con movilidad de cadera y tobillo',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 2, 120, 50, [
                        self::ex('Dragon Pistol Squat', '2/lado'),
                        self::ex('Pistol Squat', '2/lado'),
                        self::ex('Nordic Curl Negative', '2'),
                        self::ex('V-Sit Hold', '8s'),
                        self::ex('Dragon Flag', '3'),
                        self::ex('Standing Calf Raise', '12'),
                        self::ex('Face Pull', '12'),
                        self::ex('YTW', '8'),
                    ], 1),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Face Pull', '12', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado'),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Kneeling Hip Flexor Stretch', '40s/lado', 2),
                        self::ex('Standing Quad Stretch', '40s/lado', 2),
                        self::ex('Knee-to-Wall Rocks', '8/lado'),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L12(string $phase): array
    {
        $b0 = self::block0(12, $phase, 'session_d');

        return match ($phase) {
            'base' => [
                'name' => 'Sesión D: Skills — despegues de HSPU, press con impulso mínimo y bandera', 'goal' => 'Peldaños del HSPU libre: la fuerza (HSPU ×8) y el equilibrio (HS 15 s) de N11 empiezan a unirse con despegues de un pie/roce mínimo; press con el 80% del trabajo hecho por compresión y hombros y 20% de puntas; hitos: bandera vertical (primer peldaño del human flag de N13) y V-sit asistido',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        // HSPU en pared con despegues (un pie/roce mínimo): se mapea a 'Handstand Push Up'
                        self::ex('Handstand Push Up', '2', 4),
                        self::ex('Freestanding Handstand', '10-15s', 4),
                        // Press con impulso mínimo de puntas: se mapea a 'Pike Press to Handstand'
                        self::ex('Pike Press to Handstand', '2', 4),
                        self::ex('Pike Press Negative', '2', 2),
                        self::ex('Vertical Flag', '10-15s', 4),
                        self::ex('V-Sit Hold', '10s', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 2),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'progression' => [
                'name' => 'Sesión D: Skills — HSPU libre asistido y press asistido', 'goal' => 'HSPU libre asistido: el roce de pared es la red de seguridad, no el motor — cada rep con equilibrio real; press asistido (banda ligera/spot), peldaño final antes del press libre (compresión máxima, cadera alta); hitos consolidados a ambos lados',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Freestanding Handstand Push Up', '1', 5),
                        self::ex('Freestanding Handstand', '15s', 4),
                        self::ex('Pike Press to Handstand', '1-2', 4),
                        self::ex('Vertical Flag', '15-20s', 4),
                        self::ex('V-Sit Hold', '12s', 3),
                    ], 180),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 3),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 3),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'intensification' => [
                'name' => 'Sesión D: Skills — rotación de picos HSPU libre / press', 'goal' => 'Rotación de pico por ciclo (modelo N14): ciclo impar → marca de HSPU libre (1/3) y press contenido; ciclo par → marca de press (1/2) y HSPU contenido — los dos gestos comparten hombro en flexión y muñeca en extensión máxima, y ambos llegan frescos al test; dosificación intermedia en el bloque; caída = pirueta limpia y siguiente intento',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Freestanding Handstand Push Up', '1-2', 5),
                        self::ex('Freestanding Handstand', '10-15s', 3),
                        self::ex('Pike Press to Handstand', '1', 4),
                        self::ex('Vertical Flag', '15-20s', 3),
                        self::ex('V-Sit Hold', '12s', 2),
                    ], 240),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Seated Leg Raise', '10s', 2),
                        self::ex('Arch Hold', '20s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '40s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            'deload' => [
                'name' => 'Sesión D: Skills ligero + RE-TESTS G3, G5 y G6', 'goal' => 'RE-TESTS integrados: G3 (knee-to-wall ≥10 cm sin despegar el talón, simetría <2 cm — el dragon pistol la exige), G5 (compresión sentada 10 s, manos solo apoyo ligero, lumbar neutra) y G6 (palmas completas al suelo con piernas extendidas); si una puerta falla: ciclo corto de movilidad de esa puerta sin repetir la fuerza ya superada; juego de equilibrio sin HSPU; movilidad larga protagonista',
                'blocks' => [
                    $b0,
                    self::block(TrainingRound::BLOCK_PRINCIPAL, 1, 1, 45, [
                        self::ex('Freestanding Handstand', '8-12s', 4),
                        self::ex('Pike Press Negative', '2', 2),
                        // RE-TEST G3 — Dorsiflexión de tobillo (verificación ≥10 cm, simetría <2 cm)
                        self::ex('Knee-to-Wall Rocks', '8/lado', 2),
                        // RE-TEST G5 — Compresión de cadera (10 s)
                        self::ex('Seated Leg Raise', '10s', 2),
                        // RE-TEST G6 — Pike / isquiosurales (palmas al suelo, verificación)
                        self::ex('Standing Pike Stretch', '30s', 2),
                        self::ex('Vertical Flag', '10s/lado', 2),
                        self::ex('V-Sit Hold', '8s', 2),
                        // Movilidad larga: pike + pancake + dislocates (protagonista de la fase)
                        self::ex('Standing Pike Stretch', '45s', 2),
                        self::ex('Straddle Fold', '45s', 2),
                        self::ex('Shoulder Dislocate', '10', 2),
                    ], 120),
                    self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, [
                        self::ex('Wall Slide', '10', 2),
                        self::ex('Cuban Rotation', '8', 2),
                        self::ex('Chest Doorway Stretch', '30s/lado', 2),
                    ], 50),
                    self::block(TrainingRound::BLOCK_CADENA_POSTERIOR, 1, 1, 45, [
                        self::ex('Arch Hold', '15s', 2),
                    ], 60),
                    self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, [
                        self::ex('Diaphragmatic Breathing', '120s'),
                        self::ex('Standing Pike Stretch', '40s'),
                        self::ex('Straddle Fold', '45s'),
                        self::ex('Wrist Extension Stretch', '30s', 2),
                    ], 20),
                ],
            ],
            default => throw new \InvalidArgumentException(),
        };
    }
}
