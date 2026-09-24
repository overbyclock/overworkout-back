<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Muscle Up Mastery (6 niveles).
 * Especialización del Master v4 (N8 Potencia, N9 Muscle-Up, N11 anillas, N13+ lastrada).
 * Estructura: 4 sesiones por fase (A=Strength, B=Muscle-up, C=Prehab, D=Skill/Técnica).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas al codo:
 * - Los intentos de muscle-up van con descanso completo (3-4 min), nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de muscle-up, falso agarre ni estáticos en frío: solo en caliente.
 * - El kipping desesperado es la primera causa de dolor de codo.
 */
class MuscleUpBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Traccion y Empuje', 'title' => 'Base de Traccion y Empuje', 'description' => 'Construir la fuerza de traccion, empuje y core que exige la muscle-up', 'objective' => 'Desarrollar dominadas estrictas, dips y base de core suficientes para empezar a montar la muscle-up por piezas', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push-up(10), Active Hang(20s)', 'skillFocus' => 'Pull Up, Dip, Hollow Body'],
            2 => ['name' => 'Nivel 2: Piezas de la Muscle-Up', 'title' => 'Piezas de la Muscle-Up', 'description' => 'Entrenar por separado las tres piezas: traccion alta, transicion y empuje sobre barra', 'objective' => 'Dominar el C2B alto, la transicion asistida y el straight bar dip antes de intentar el movimiento completo', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(8), Dip(10), Active Hang(30s)', 'skillFocus' => 'Chest to Bar alto, Transicion asistida, Straight Bar Dip'],
            3 => ['name' => 'Nivel 3: Primera Muscle-Up', 'title' => 'Primera Muscle-Up', 'description' => 'Unir las piezas: primer muscle-up estricta en barra', 'objective' => 'Conseguir la primera muscle-up limpia y repetible con descansos completos', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Chest to Bar Pull Up(8), Straight Bar Dips(8), Transicion con banda(3)', 'skillFocus' => 'Muscle Up, Muscle Up Negativo, Transicion'],
            4 => ['name' => 'Nivel 4: Consistencia', 'title' => 'Consistencia', 'description' => 'Convertir el single en un skill fiable: dobles, series y volumen', 'objective' => 'Muscle-ups unbroken y volumen en series sin perder la tecnica de la transicion', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Muscle Up(1), Chest to Bar Pull Up(10), Straight Bar Dips(10)', 'skillFocus' => 'Muscle Up unbroken, Volumen en series, Kipping opcional'],
            5 => ['name' => 'Nivel 5: Muscle-Up en Anillas', 'title' => 'Muscle-Up en Anillas', 'description' => 'Trasladar el skill a anillas con falso agarre', 'objective' => 'Conseguir la ring muscle up y alternar barra y anillas en el mismo entrenamiento', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Muscle Up(3), Muscle Up Negativo(3), Chest to Bar Pull Up(12)', 'skillFocus' => 'False Grip, Ring Muscle Up, Ring MU Transition'],
            6 => ['name' => 'Nivel 6: Dominio de la Muscle-Up', 'title' => 'Dominio de la Muscle-Up', 'description' => 'Muscle-up lastrada, L-sit, variedades y potencia', 'objective' => 'Dominar la muscle-up en todas sus formas: lastrada, a L-sit, en anillas y con variantes de agarre', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Ring Muscle Up(1), Muscle Up(5) en 10 min, False Grip Pull Up(5)', 'skillFocus' => 'Muscle Up lastrada, L-Sit MU, Variedades de agarre'],
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
            default => throw new \InvalidArgumentException("Sesion {$sessionKey} no valida"),
        };

        $sessionType = match ($sessionKey) {
            'session_a' => 'strength',
            'session_b' => 'skill',
            'session_c' => 'skill',
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

        $rest = match (true) {
            $level <= 3 => 120,
            default => 150,
        };

        foreach ($blocks as $index => &$block) {
            if ($index < \count($blocks) - 1) {
                $block['restAfterBlock'] = $rest;
            }
        }

        return $blocks;
    }

    private static function block(int $rounds, int $restRounds, int $restEx, array $exercises, int $restSets = 0): array
    {
        return [
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
    // SESSION A — STRENGTH (Traccion y empuje)
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

    private static function sessionA_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza basica de traccion y empuje', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '5-6', 3), self::ex('Chin Up', '5-6', 3), self::ex('Dip', '8-10', 3), self::ex('Push Up', '10-12', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '7-8', 3), self::ex('Chin Up', '6-8', 3), self::ex('Dip', '10-12', 3), self::ex('Push Up', '12-15', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '8-10', 4), self::ex('Chin Up', '8-10', 4), self::ex('Dip', '12-15', 4), self::ex('Push Up', '15-18', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '4-5', 2), self::ex('Chin Up', '4-5', 2), self::ex('Dip', '6-8', 2), self::ex('Push Up', '8-10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion alta y empuje', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '5-6', 3), self::ex('Pull Up', '8-10', 3), self::ex('Dip', '10-12', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '6-8', 3), self::ex('Pull Up', '10-12', 3), self::ex('Dip', '12-15', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '8-10', 4), self::ex('Pull Up', '12-15', 4), self::ex('Dip', '15-18', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '4-5', 2), self::ex('Pull Up', '6-8', 2), self::ex('Dip', '8-10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion maxima fresca', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '8-10', 3), self::ex('Weighted Chin Up', '3-5', 3), self::ex('Pull Up', '10-12', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '10-12', 3), self::ex('Weighted Chin Up', '5-6', 3), self::ex('Pull Up', '12-15', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '12-15', 4), self::ex('Weighted Chin Up', '6-8', 4), self::ex('Pull Up', '15-18', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '5-6', 2), self::ex('Weighted Chin Up', '3-4', 2), self::ex('Pull Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion pesada y unilateral', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '5-6', 3), self::ex('Chest to Bar Pull Up', '10-12', 3), self::ex('Archer Pull Up', '3-5', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '6-8', 3), self::ex('Chest to Bar Pull Up', '12-15', 3), self::ex('Archer Pull Up', '5-6', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '8-10', 4), self::ex('Chest to Bar Pull Up', '15-18', 4), self::ex('Archer Pull Up', '6-8', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '4-5', 2), self::ex('Chest to Bar Pull Up', '6-8', 2), self::ex('Archer Pull Up', '3-4', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '5-6', 3), self::ex('False Grip Pull Up', '5-6', 3), self::ex('Weighted Chin Up', '5-8', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '6-8', 3), self::ex('False Grip Pull Up', '6-8', 3), self::ex('Weighted Chin Up', '8-10', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '8-10', 4), self::ex('False Grip Pull Up', '8-10', 4), self::ex('Weighted Chin Up', '10-12', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '4-5', 2), self::ex('False Grip Pull Up', '4-5', 2), self::ex('Weighted Chin Up', '5-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion elite', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '8-10', 3), self::ex('Archer Pull Up', '6-8', 3), self::ex('Ring Pull Up - False Grip', '8-10', 3)], 150)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '10-12', 3), self::ex('Archer Pull Up', '8-10', 3), self::ex('Ring Pull Up - False Grip', '10-12', 3)], 150)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '12-15', 4), self::ex('Archer Pull Up', '10-12', 4), self::ex('Ring Pull Up - False Grip', '12-15', 4)], 150)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '5-6', 2), self::ex('Archer Pull Up', '4-6', 2), self::ex('Ring Pull Up - False Grip', '6-8', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — MUSCLE-UP (Piezas, transicion e intentos)
    // ========================================================================

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

    private static function sessionB_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Muscle-Up Base', 'goal' => 'Puente mecanico: empuje sobre la barra', 'blocks' => [self::block(1, 1, 60, [self::ex('Straight Bar Dips', '5-6', 3), self::ex('Chest to Bar Pull Up', '3-5', 3), self::ex('Scapular Push Up', '8-10', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Muscle-Up Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Straight Bar Dips', '6-8', 3), self::ex('Chest to Bar Pull Up', '5-6', 3), self::ex('Scapular Push Up', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Muscle-Up Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Straight Bar Dips', '8-10', 4), self::ex('Chest to Bar Pull Up', '6-8', 4), self::ex('Scapular Push Up', '12-15', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Muscle-Up Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Straight Bar Dips', '4-5', 2), self::ex('Chest to Bar Pull Up', '3-5', 2), self::ex('Scapular Push Up', '6-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Muscle-Up Base', 'goal' => 'Las tres piezas por separado', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted Muscle Up Transition', '3-5', 3), self::ex('Muscle Up Negative', '2-3', 3), self::ex('Straight Bar Dips', '6-8', 3)], 120)]],
            'progression' => ['name' => 'Sesion B: Muscle-Up Progresion', 'goal' => 'Transicion mas ligera', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted Muscle Up Transition', '4-6', 3), self::ex('Muscle Up Negative', '3-4', 3), self::ex('Straight Bar Dips', '8-10', 3)], 120)]],
            'intensification' => ['name' => 'Sesion B: Muscle-Up Intensificacion', 'goal' => 'Volumen tecnico', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted Muscle Up Transition', '5-8', 4), self::ex('Muscle Up Negative', '4-5', 4), self::ex('Straight Bar Dips', '10-12', 4)], 120)]],
            'deload' => ['name' => 'Sesion B: Muscle-Up Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted Muscle Up Transition', '3-4', 2), self::ex('Muscle Up Negative', '2-3', 2), self::ex('Straight Bar Dips', '5-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Muscle-Up Base', 'goal' => 'Primeros intentos, frescos y espaciados', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 4), self::ex('Assisted Muscle Up Transition', '3-5', 3), self::ex('Muscle Up Negative', '3-4', 3)], 180)]],
            'progression' => ['name' => 'Sesion B: Muscle-Up Progresion', 'goal' => 'Un intento mas', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 5), self::ex('Assisted Muscle Up Transition', '4-6', 3), self::ex('Muscle Up Negative', '3-4', 3)], 180)]],
            'intensification' => ['name' => 'Sesion B: Muscle-Up Intensificacion', 'goal' => 'Pico del nivel: cada intento plenamente recuperado', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 6), self::ex('Assisted Muscle Up Transition', '5-8', 3), self::ex('Muscle Up Negative', '4-5', 3)], 240)]],
            'deload' => ['name' => 'Sesion B: Muscle-Up Deload', 'goal' => 'Solo tecnica con banda', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 2), self::ex('Assisted Muscle Up Transition', '3-4', 2), self::ex('Muscle Up Negative', '2-3', 2)], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Muscle-Up Base', 'goal' => 'De single a dobles', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1-2', 4), self::ex('Bar Muscle Up', '1-2', 3), self::ex('Muscle Up Negative', '3-4', 3)], 180)]],
            'progression' => ['name' => 'Sesion B: Muscle-Up Progresion', 'goal' => 'Dobles consistentes', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1-2', 5), self::ex('Bar Muscle Up', '2-3', 3), self::ex('Muscle Up Negative', '4-5', 3)], 180)]],
            'intensification' => ['name' => 'Sesion B: Muscle-Up Intensificacion', 'goal' => 'Volumen unbroken', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '2-3', 5), self::ex('Bar Muscle Up', '3-5', 4), self::ex('Muscle Up Negative', '5-6', 4)], 240)]],
            'deload' => ['name' => 'Sesion B: Muscle-Up Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 3), self::ex('Bar Muscle Up', '1-2', 2), self::ex('Muscle Up Negative', '2-3', 2)], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Muscle-Up Base', 'goal' => 'Transicion en anillas', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '3-5', 3), self::ex('Ring Muscle Up', '1', 3), self::ex('Muscle Up', '2-3', 3)], 180)]],
            'progression' => ['name' => 'Sesion B: Muscle-Up Progresion', 'goal' => 'Ring MU consistente', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '4-6', 3), self::ex('Ring Muscle Up', '1-2', 4), self::ex('Muscle Up', '3-5', 3)], 180)]],
            'intensification' => ['name' => 'Sesion B: Muscle-Up Intensificacion', 'goal' => 'Dobles en anillas', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '5-8', 4), self::ex('Ring Muscle Up', '2-3', 5), self::ex('Muscle Up', '5-8', 4)], 240)]],
            'deload' => ['name' => 'Sesion B: Muscle-Up Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '3-4', 2), self::ex('Ring Muscle Up', '1', 2), self::ex('Muscle Up', '2-3', 2)], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Muscle-Up Base', 'goal' => 'Muscle-up lastrada', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 4), self::ex('Ring Muscle Up', '2-3', 3), self::ex('Muscle Up to L-Sit', '1-2', 3)], 180)]],
            'progression' => ['name' => 'Sesion B: Muscle-Up Progresion', 'goal' => 'Mas lastre y L-sit', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1-2', 5), self::ex('Ring Muscle Up', '3-5', 3), self::ex('Muscle Up to L-Sit', '2-3', 3)], 240)]],
            'intensification' => ['name' => 'Sesion B: Muscle-Up Intensificacion', 'goal' => 'Pico: lastre maximo y formas', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '2', 5), self::ex('Ring Muscle Up', '5-8', 4), self::ex('Muscle Up to L-Sit', '3-5', 4)], 240)]],
            'deload' => ['name' => 'Sesion B: Muscle-Up Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up', '1', 3), self::ex('Ring Muscle Up', '2-3', 2), self::ex('Muscle Up to L-Sit', '1-2', 2)], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (Codos, hombros, munecas y core)
    // ========================================================================

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

    private static function sessionC_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Core y salud de hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 3), self::ex('Hanging Leg Raise', '6-8', 3), self::ex('Face Pull', '12-15', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 3), self::ex('Hanging Leg Raise', '8-10', 3), self::ex('Face Pull', '15-18', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 4), self::ex('Hanging Leg Raise', '10-12', 4), self::ex('Face Pull', '18-20', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Face Pull', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Falso agarre y munecas', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '10-15s', 3), self::ex('German Hang', '15-20s', 3), self::ex('Wrist Curl', '12-15', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Aumentar tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '15-20s', 3), self::ex('German Hang', '20-25s', 3), self::ex('Wrist Curl', '15-18', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '20-25s', 4), self::ex('German Hang', '25-30s', 4), self::ex('Wrist Curl', '18-20', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '8-10s', 2), self::ex('German Hang', '10-15s', 2), self::ex('Wrist Curl', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Proteccion de codo', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '3-5', 3), self::ex('German Hang', '20-25s', 3), self::ex('Wrist Curl', '12-15', 3), self::ex('Face Pull', '12-15', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '5-6', 3), self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '15-18', 3), self::ex('Face Pull', '15-18', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '6-8', 4), self::ex('German Hang', '30-35s', 4), self::ex('Wrist Curl', '18-20', 4), self::ex('Face Pull', '18-20', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '3-4', 2), self::ex('German Hang', '15-20s', 2), self::ex('Wrist Curl', '8-10', 2), self::ex('Face Pull', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Falso agarre en anillas', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '3-5', 3), self::ex('False Grip Pull Up', '5-6', 3), self::ex('German Hang', '25-30s', 3), self::ex('Face Pull', '15-18', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '5-6', 3), self::ex('False Grip Pull Up', '6-8', 3), self::ex('German Hang', '30-35s', 3), self::ex('Face Pull', '18-20', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '6-8', 4), self::ex('False Grip Pull Up', '8-10', 4), self::ex('German Hang', '35-40s', 4), self::ex('Face Pull', '20-22', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '3-4', 2), self::ex('False Grip Pull Up', '4-5', 2), self::ex('German Hang', '15-20s', 2), self::ex('Face Pull', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Fuerza de agarre en anillas', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10-15s', 3), self::ex('False Grip Ring Row', '5-8', 3), self::ex('False Grip Hang', '20-25s', 3), self::ex('German Hang', '25-30s', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '15-20s', 3), self::ex('False Grip Ring Row', '8-10', 3), self::ex('False Grip Hang', '25-30s', 3), self::ex('German Hang', '30-35s', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '20-25s', 4), self::ex('False Grip Ring Row', '10-12', 4), self::ex('False Grip Hang', '30-35s', 4), self::ex('German Hang', '35-40s', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '8-10s', 2), self::ex('False Grip Ring Row', '5-6', 2), self::ex('False Grip Hang', '12-15s', 2), self::ex('German Hang', '15-20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab elite', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '8-10', 3), self::ex('Skin The Cat', '8-10', 3), self::ex('Wrist Curl', '15-18', 3), self::ex('Face Pull', '18-20', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '10-12', 3), self::ex('Skin The Cat', '10-12', 3), self::ex('Wrist Curl', '18-20', 3), self::ex('Face Pull', '20-22', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '12-15', 4), self::ex('Skin The Cat', '12-15', 4), self::ex('Wrist Curl', '20-22', 4), self::ex('Face Pull', '22-25', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '6-8', 2), self::ex('Skin The Cat', '6-8', 2), self::ex('Wrist Curl', '8-10', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL/TÉCNICA (Practica directa del movimiento)
    // ========================================================================

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

    private static function sessionD_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Primer contacto con la barra', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '20-25s', 3), self::ex('Scapular Pull Up', '5-6', 3), self::ex('Jumping Pull Up', '3-5', 3), self::ex('Dead Hang', '20-25s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '25-30s', 3), self::ex('Scapular Pull Up', '6-8', 3), self::ex('Jumping Pull Up', '5-6', 3), self::ex('Dead Hang', '25-30s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Maximo control', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30-35s', 4), self::ex('Scapular Pull Up', '8-10', 4), self::ex('Jumping Pull Up', '6-8', 4), self::ex('Dead Hang', '30-35s', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '15-20s', 2), self::ex('Scapular Pull Up', '4-5', 2), self::ex('Jumping Pull Up', '3-4', 2), self::ex('Dead Hang', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Traccion alta y swing', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Bar Practice', '3-5', 3), self::ex('Muscle Up Progression', '3-5', 3), self::ex('Kipping Pull Up', '3-5', 3), self::ex('Dip', '8-10', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Bar Practice', '5-6', 3), self::ex('Muscle Up Progression', '5-6', 3), self::ex('Kipping Pull Up', '5-8', 3), self::ex('Dip', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Bar Practice', '6-8', 4), self::ex('Muscle Up Progression', '6-8', 4), self::ex('Kipping Pull Up', '8-10', 4), self::ex('Dip', '12-15', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Bar Practice', '3-5', 2), self::ex('Muscle Up Progression', '3-4', 2), self::ex('Kipping Pull Up', '3-5', 2), self::ex('Dip', '6-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Refinar la transicion', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up Progression', '5-6', 3), self::ex('Kipping Pull Up', '5-8', 3), self::ex('Chest to Bar Practice', '5-8', 3), self::ex('Straight Bar Dips', '6-8', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up Progression', '6-8', 3), self::ex('Kipping Pull Up', '8-10', 3), self::ex('Chest to Bar Practice', '8-10', 3), self::ex('Straight Bar Dips', '8-10', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up Progression', '8-10', 4), self::ex('Kipping Pull Up', '10-12', 4), self::ex('Chest to Bar Practice', '10-12', 4), self::ex('Straight Bar Dips', '10-12', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up Progression', '4-5', 2), self::ex('Kipping Pull Up', '4-6', 2), self::ex('Chest to Bar Practice', '5-6', 2), self::ex('Straight Bar Dips', '5-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Kipping y series', 'blocks' => [self::block(1, 1, 60, [self::ex('Kipping Pull Up', '8-10', 3), self::ex('Chest to Bar Practice', '8-10', 3), self::ex('Muscle Up', '1', 4), self::ex('Straight Bar Dips', '8-10', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Kipping Pull Up', '10-12', 3), self::ex('Chest to Bar Practice', '10-12', 3), self::ex('Muscle Up', '1-2', 4), self::ex('Straight Bar Dips', '10-12', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Kipping Pull Up', '12-15', 4), self::ex('Chest to Bar Practice', '12-15', 4), self::ex('Muscle Up', '2', 5), self::ex('Straight Bar Dips', '12-15', 4)], 180)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Kipping Pull Up', '5-8', 2), self::ex('Chest to Bar Practice', '5-8', 2), self::ex('Muscle Up', '1', 2), self::ex('Straight Bar Dips', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Practicar en anillas', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1', 3), self::ex('Bar Muscle Up', '1-2', 3), self::ex('Muscle Up Negative', '3-4', 3), self::ex('Straight Bar Dips', '8-10', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1-2', 4), self::ex('Bar Muscle Up', '2-3', 3), self::ex('Muscle Up Negative', '4-5', 3), self::ex('Straight Bar Dips', '10-12', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '2-3', 5), self::ex('Bar Muscle Up', '3-5', 4), self::ex('Muscle Up Negative', '5-6', 4), self::ex('Straight Bar Dips', '12-15', 4)], 180)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1', 2), self::ex('Bar Muscle Up', '1-2', 2), self::ex('Muscle Up Negative', '2-3', 2), self::ex('Straight Bar Dips', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Variedades de agarre', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up - Close', '1-2', 3), self::ex('Muscle Up - Wide', '1-2', 3), self::ex('Kipping Pull Up', '10-12', 3), self::ex('L-Sit', '15-20s', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up - Close', '2-3', 3), self::ex('Muscle Up - Wide', '2-3', 3), self::ex('Kipping Pull Up', '12-15', 3), self::ex('L-Sit', '20-25s', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Potencia y variedades', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up Burpee', '3-5', 3), self::ex('Muscle Up - Close', '3-5', 4), self::ex('Kipping Pull Up', '15-18', 4), self::ex('L-Sit', '25-30s', 4)], 150)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 60, [self::ex('Muscle Up - Close', '1-2', 2), self::ex('Muscle Up - Wide', '1-2', 2), self::ex('Kipping Pull Up', '6-8', 2), self::ex('L-Sit', '10-15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
