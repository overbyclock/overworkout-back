<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Calistenia Master v4.0 (niveles 1-6).
 *
 * Contenido transcrito literalmente de docs/detalle_niveles_01-03.md (niveles 1-3)
 * y docs/detalle_niveles_04-06.md (niveles 4-6).
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

    // ========================================================================
    // DISPATCHERS DE SESIÓN
    // ========================================================================

    private static function sessionA(int $level, string $phase): array
    {
        return match ($level) {
            1 => self::sessionA_L1($phase),
            2 => self::sessionA_L2($phase),
            3 => self::sessionA_L3($phase),
            4 => self::sessionA_L4($phase),
            5 => self::sessionA_L5($phase),
            6 => self::sessionA_L6($phase),
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
}
