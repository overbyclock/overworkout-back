<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa False Grip Mastery (8 niveles).
 * Especialización del Master v4: muñeca de N1-N2, falso agarre de N6-N11
 * y preparación profunda de N15-N16; dosis de MU Mastery (anillas).
 * Estructura: 4 sesiones por fase (A=Strength, B=False Grip, C=Prehab, D=Skill).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a muñeca y codo:
 * - Los hangs y soportes en falso agarre van con descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de falso agarre en frío: muñeca caliente antes de cada serie.
 * - Molestia matinal >2/10 en muñeca/codo = congela ese gesto un ciclo.
 */
class FalseGripBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Muñeca y Agarre', 'title' => 'Base de Muñeca y Agarre', 'description' => 'Preparar muñecas, antebrazos y agarre de barra antes de tocar el falso agarre', 'objective' => 'Fortalecer flexores y extensores de muñeca y el agarre en colgado para soportar el falso agarre sin dolor', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push Up(10), Active Hang(20s)', 'skillFocus' => 'Dead Hang, Wrist Prayer, Knee Wrist Push-Up'],
            2 => ['name' => 'Nivel 2: Introducción al Falso Agarre', 'title' => 'Introducción al Falso Agarre', 'description' => 'Primer contacto con el falso agarre colgado y en apoyo', 'objective' => 'Colgarse y apoyarse en falso agarre con técnica correcta y sin dolor (hito)', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Dead Hang(30s), Standard Push Up(10), Knee Wrist Push-Up(10)', 'skillFocus' => 'False Grip Hang, False Grip Support Hold, Wrist Push-Up'],
            3 => ['name' => 'Nivel 3: Falso Agarre Básico', 'title' => 'Falso Agarre Básico', 'description' => 'Consolidar el falso agarre colgado y en apoyo; primeras filas en falso agarre', 'objective' => 'Sostener 15 s colgado en falso agarre y remar 6-8 reps sin perder el agarre', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'False Grip Hang(10s), Wrist Push-Up(6), Standard Pull Up(5)', 'skillFocus' => 'False Grip Hang, False Grip Ring Row, False Grip Support Hold'],
            4 => ['name' => 'Nivel 4: Falso Agarre Estable', 'title' => 'Falso Agarre Estable', 'description' => 'Falso agarre sólido: 20 s colgado (gate del N10 del Master) y apoyo en anillas', 'objective' => 'Llegar al gate del Master: false grip hang 20 s con apoyo estable en anillas', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'False Grip Hang(15s), False Grip Ring Row(6), False Grip Support Hold(8s)', 'skillFocus' => 'False Grip Hang 20s, False Grip Ring Row, Ring Support Hold'],
            5 => ['name' => 'Nivel 5: Tracción con Falso Agarre', 'title' => 'Tracción con Falso Agarre', 'description' => 'Sumar el gesto de tracción completo en falso agarre: negativas y primeras asistidas', 'objective' => 'Traccionar en falso agarre (negativas y asistidas) sin perder el colgado de 25 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'False Grip Hang(20s), False Grip Ring Row(8), False Grip Support Hold(10s)', 'skillFocus' => 'False Grip Pull Up negativa, False Grip Ring Row, German Hang'],
            6 => ['name' => 'Nivel 6: Primer False Grip Pull-Up', 'title' => 'Primer False Grip Pull-Up', 'description' => 'Unir agarre y tracción: primera dominada estricta en falso agarre', 'objective' => 'Conseguir la primera false grip pull-up estricta y sostener 30 s colgado', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'False Grip Hang(25s), False Grip Ring Row(10), German Hang(30s)', 'skillFocus' => 'False Grip Pull Up, False Grip Hang 30s, Ring Support'],
            7 => ['name' => 'Nivel 7: Volumen y Anillas', 'title' => 'Volumen y Anillas', 'description' => 'Volumen en falso agarre en barra y anillas; construir la transición de ring MU', 'objective' => 'Dominadas en falso agarre con volumen y transición de ring MU sobre falso agarre profundo', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'False Grip Pull Up(1), False Grip Hang(30s), Ring Muscle Up Transition(3)', 'skillFocus' => 'Ring Pull Up - False Grip, False Grip Pull Up, Ring MU Transition'],
            8 => ['name' => 'Nivel 8: Dominio del Falso Agarre', 'title' => 'Dominio del Falso Agarre', 'description' => 'Élite del falso agarre: ring muscle-up y resistencia profunda (dosis N15 del Master)', 'objective' => 'Ring muscle up en falso agarre profundo y resistencia de agarre a la altura del N15 del Master', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Ring Pull Up - False Grip(5), False Grip Pull Up(5), Ring Muscle Up Transition(5)', 'skillFocus' => 'Ring Muscle Up, Ring Pull Up - False Grip, False Grip Support'],
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
    // SESSION A — STRENGTH (Tracción y empuje de soporte)
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
            7 => self::sessionA_L7($phase),
            8 => self::sessionA_L8($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionA_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza básica de empuje y tracción; énfasis en muñeca antes de los apoyos', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Ring Row - Feet on Ground', '8-10', 3), self::ex('Scapular Push Up', '8', 2), self::ex('Active Hang', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de empuje y tracción', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Ring Row - Feet on Ground', '10-12', 3), self::ex('Scapular Push Up', '10', 2), self::ex('Active Hang', '20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 4), self::ex('Ring Row - Feet on Ground', '12-15', 4), self::ex('Scapular Push Up', '10', 3), self::ex('Active Hang', '20-30s', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Ring Row - Feet on Ground', '8-10', 2), self::ex('Scapular Push Up', '8', 2), self::ex('Active Hang', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Entra la dominada estricta: base de la tracción en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '3-5', 3), self::ex('Standard Push Up', '12-15', 3), self::ex('Ring Row - Feet on Ground', '10-12', 3), self::ex('Active Hang', '20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 3), self::ex('Standard Push Up', '15', 3), self::ex('Ring Row - Feet on Ground', '12', 3), self::ex('Active Hang', '20-30s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 4), self::ex('Standard Push Up', '15-18', 4), self::ex('Ring Row - Feet on Ground', '12-15', 4), self::ex('Active Hang', '30s', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '3-4', 2), self::ex('Standard Push Up', '8-10', 2), self::ex('Ring Row - Feet on Ground', '8-10', 2), self::ex('Active Hang', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta con peso corporal', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 3), self::ex('Chin Up', '5-6', 3), self::ex('Ring Row - Elevated Feet', '8-10', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 3), self::ex('Chin Up', '6-8', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 4), self::ex('Chin Up', '8-10', 4), self::ex('Ring Row - Elevated Feet', '12', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '4-5', 2), self::ex('Chin Up', '4-5', 2), self::ex('Ring Row - Elevated Feet', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta y contrapeso horizontal pesado', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 3), self::ex('Weighted Ring Row', '8-10', 3), self::ex('Standard Push Up', '15', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Weighted Ring Row', '10-12', 3), self::ex('Standard Push Up', '15-18', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 4), self::ex('Weighted Ring Row', '12', 4), self::ex('Standard Push Up', '18-20', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '4-5', 2), self::ex('Weighted Ring Row', '8', 2), self::ex('Standard Push Up', '10-12', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción alta y archer: la tracción del falso agarre se gana también en prono', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '3-4', 3), self::ex('Archer Pull Up', '2/lado', 3), self::ex('Weighted Ring Row', '8-10', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '4-6', 3), self::ex('Archer Pull Up', '2-3/lado', 3), self::ex('Weighted Ring Row', '10', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '6-8', 4), self::ex('Archer Pull Up', '3/lado', 4), self::ex('Weighted Ring Row', '10-12', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Bar Pull Up', '3', 2), self::ex('Archer Pull Up', '2/lado', 2), self::ex('Weighted Ring Row', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción máxima fresca antes del trabajo en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Weighted Chin Up', '5-8', 3), self::ex('Weighted Ring Row', '10-12', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Chin Up', '8-10', 3), self::ex('Weighted Ring Row', '12', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 4), self::ex('Weighted Chin Up', '10-12', 4), self::ex('Weighted Ring Row', '12-15', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 2), self::ex('Weighted Chin Up', '5-6', 2), self::ex('Weighted Ring Row', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza en falso agarre en anillas (dosis L5 de MU Mastery)', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '5-6', 3), self::ex('False Grip Pull Up', '5-6', 3), self::ex('Weighted Chin Up', '5-8', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '6-8', 3), self::ex('False Grip Pull Up', '6-8', 3), self::ex('Weighted Chin Up', '8-10', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '8-10', 4), self::ex('False Grip Pull Up', '8-10', 4), self::ex('Weighted Chin Up', '10-12', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '4-5', 2), self::ex('False Grip Pull Up', '4-5', 2), self::ex('Weighted Chin Up', '5-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción elite en falso agarre (dosis L6 de MU Mastery)', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '8-10', 3), self::ex('False Grip Pull Up', '8-10', 3), self::ex('Weighted Chin Up', '8-10', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '10-12', 3), self::ex('False Grip Pull Up', '10-12', 3), self::ex('Weighted Chin Up', '10-12', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '12-15', 4), self::ex('False Grip Pull Up', '12-15', 4), self::ex('Weighted Chin Up', '12-15', 4)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '6-8', 2), self::ex('False Grip Pull Up', '6-8', 2), self::ex('Weighted Chin Up', '5-6', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — FALSE GRIP (hangs, soportes y filas en falso agarre)
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
            7 => self::sessionB_L7($phase),
            8 => self::sessionB_L8($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionB_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Agarre Base', 'goal' => 'Colgados limpios con agarre completo: la base del falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '10-15s', 3), self::ex('Active Hang', '10-15s', 2), self::ex('Scapular Pull Up', '6', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Agarre Progresión', 'goal' => 'Aumentar tiempo de colgado', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15-20s', 3), self::ex('Active Hang', '15-20s', 2), self::ex('Scapular Pull Up', '8', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Agarre Intensificación', 'goal' => 'Volumen de colgado', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-30s', 3), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '8', 3)], 90)]],
            'deload' => ['name' => 'Sesión B: Agarre Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15s', 2), self::ex('Active Hang', '10-15s', 2), self::ex('Scapular Pull Up', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: False Grip Base', 'goal' => 'Primer falso agarre correcto colgado (hito): anilla apoyada en la muñeca, codo apuntando al suelo', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '5-8s', 3), self::ex('Dead Hang', '20s', 2), self::ex('Scapular Pull Up', '8', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: False Grip Progresión', 'goal' => 'Aumentar tiempo en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '8-10s', 3), self::ex('Dead Hang', '25s', 2), self::ex('Scapular Pull Up', '8', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: False Grip Intensificación', 'goal' => 'Volumen: 10-12 s en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '10-12s', 3), self::ex('Dead Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 2)], 120)]],
            'deload' => ['name' => 'Sesión B: False Grip Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '5s', 2), self::ex('Dead Hang', '20s', 2), self::ex('Scapular Pull Up', '6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: False Grip Base', 'goal' => 'Hang 10-15 s y primeras filas en falso agarre (dosis N8-N9 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '10-15s', 3), self::ex('False Grip Ring Row', '5-6', 2), self::ex('Active Hang', '20s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: False Grip Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '12-15s', 3), self::ex('False Grip Ring Row', '6-8', 3), self::ex('Active Hang', '20-30s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: False Grip Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '15s', 3), self::ex('False Grip Ring Row', '8', 3), self::ex('Ring Row - Elevated Feet', '10', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: False Grip Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '10s', 2), self::ex('False Grip Ring Row', '5', 2), self::ex('Active Hang', '15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: False Grip Base', 'goal' => 'Hang 15-20 s y filas consolidadas (dosis N11-B del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '15-20s', 3), self::ex('False Grip Ring Row', '8', 3), self::ex('Ring Row - Elevated Feet', '10-12', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: False Grip Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '15-20s', 3), self::ex('False Grip Ring Row', '8-10', 3), self::ex('Archer Ring Row', '8/lado', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: False Grip Intensificación', 'goal' => 'Volumen: marca de 20 s', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '20s', 3), self::ex('False Grip Ring Row', '10', 3), self::ex('Weighted Ring Row', '10', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: False Grip Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Hang', '12s', 2), self::ex('False Grip Ring Row', '6', 2), self::ex('Ring Row - Elevated Feet', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Tracción FG Base', 'goal' => 'Negativas y primeras dominadas asistidas en falso agarre sin perder el hang de 20 s', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '8-10', 3), self::ex('False Grip Pull Up', '1-2', 3), self::ex('False Grip Hang', '20s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Tracción FG Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '10', 3), self::ex('False Grip Pull Up', '2-3', 3), self::ex('False Grip Hang', '22-25s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Tracción FG Intensificación', 'goal' => 'Volumen: 3 dominadas asistidas y hang 25 s', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '10-12', 3), self::ex('False Grip Pull Up', '3', 3), self::ex('False Grip Hang', '25s', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Tracción FG Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '6-8', 2), self::ex('False Grip Pull Up', '1-2', 2), self::ex('False Grip Hang', '15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Tracción FG Base', 'goal' => 'Primeras dominadas estrictas en falso agarre (dosis C de L4 de MU Mastery)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '3-5', 3), self::ex('False Grip Hang', '25s', 3), self::ex('False Grip Ring Row', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Tracción FG Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '4-6', 3), self::ex('False Grip Hang', '25-30s', 3), self::ex('False Grip Ring Row', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Tracción FG Intensificación', 'goal' => 'Volumen: 5-6 dominadas y hang 30 s (objetivo del gate del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '5-6', 4), self::ex('False Grip Hang', '30s', 3), self::ex('Archer Ring Row', '8/lado', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: Tracción FG Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Pull Up', '2-3', 2), self::ex('False Grip Hang', '20s', 2), self::ex('False Grip Ring Row', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: False Grip Base', 'goal' => 'Filas en falso agarre al pecho con contrapeso horizontal pesado (dosis N11-B del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '8-10', 3), self::ex('Archer Ring Row', '8/lado', 3), self::ex('False Grip Hang', '25-30s', 2), self::ex('Weighted Ring Row', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: False Grip Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '10', 3), self::ex('Archer Ring Row', '8-10/lado', 3), self::ex('False Grip Hang', '30s', 2), self::ex('Weighted Ring Row', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: False Grip Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '10-12', 3), self::ex('Archer Ring Row', '10/lado', 3), self::ex('False Grip Hang', '30s', 2), self::ex('Weighted Ring Row', '10-12', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: False Grip Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Ring Row', '6-8', 2), self::ex('Archer Ring Row', '6-8/lado', 2), self::ex('False Grip Hang', '20s', 2), self::ex('Weighted Ring Row', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Muscle-Up Base', 'goal' => 'Ring muscle-up sobre falso agarre profundo (dosis B de L6 de MU Mastery)', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1-2', 3), self::ex('False Grip Pull Up', '5-6', 3), self::ex('False Grip Hang', '30s', 2)], 180)]],
            'progression' => ['name' => 'Sesión B: Muscle-Up Progresión', 'goal' => 'Progresión: dobles en anillas', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '2-3', 4), self::ex('False Grip Pull Up', '6-8', 3), self::ex('False Grip Hang', '30-35s', 2)], 180)]],
            'intensification' => ['name' => 'Sesión B: Muscle-Up Intensificación', 'goal' => 'Pico: 3-5 ring MU y 8-10 dominadas en falso agarre', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '3-5', 5), self::ex('False Grip Pull Up', '8-10', 4), self::ex('False Grip Hang', '35s', 2)], 240)]],
            'deload' => ['name' => 'Sesión B: Muscle-Up Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1', 2), self::ex('False Grip Pull Up', '4-5', 2), self::ex('False Grip Hang', '20-25s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (muñeca, German hang y salud de codo)
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
            7 => self::sessionC_L7($phase),
            8 => self::sessionC_L8($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionC_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Fuerza de muñeca: oraciones + wrist push-ups de rodillas (dosis N1 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Knee Wrist Push-Up', '6', 2), self::ex('Wrist Curl', '12', 2), self::ex('Reverse Wrist Curl', '12', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar rango y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Knee Wrist Push-Up', '8', 2), self::ex('Wrist Curl', '12-15', 2), self::ex('Reverse Wrist Curl', '12-15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de muñeca', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Knee Wrist Push-Up', '8-10', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Knee Wrist Push-Up', '6', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Wrist push-ups completos de suelo (progresión N2 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '5', 2), self::ex('Wrist Curl', '12-15', 2), self::ex('Reverse Wrist Curl', '12', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '6-8', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '12-15', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '8', 2), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Back-of-Hand Stretch', '20-30s', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Push-Up', '5', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca en mantenimiento de fuerza; entra la extensión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Wrist Extension Stretch', '30-60s', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'German hang y muñeca: salud de codo para el falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 3), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Face Pull', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Face Pull', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Protección de codo: German hang y antebrazo (dosis C de L4 de MU Mastery)', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Face Pull', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Face Pull', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de muñeca y hombro para soportar dominadas en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Face Pull', '18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab en anillas (dosis C de L5 de MU Mastery)', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '3-5', 3), self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '18', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '5-6', 3), self::ex('German Hang', '30-35s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Face Pull', '18-20', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '6-8', 4), self::ex('German Hang', '35-40s', 4), self::ex('Wrist Curl', '20-22', 2), self::ex('Face Pull', '20-22', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Ring Pull Up - False Grip', '3-4', 2), self::ex('German Hang', '15-20s', 2), self::ex('Wrist Curl', '8-10', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Resistencia profunda de falso agarre (dosis C de L6 de MU Mastery / N15 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10-15s', 3), self::ex('False Grip Ring Row', '8-10', 3), self::ex('False Grip Hang', '20-25s', 3), self::ex('German Hang', '25-30s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '15-20s', 3), self::ex('False Grip Ring Row', '10-12', 3), self::ex('False Grip Hang', '25-30s', 3), self::ex('German Hang', '30-35s', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '20-25s', 4), self::ex('False Grip Ring Row', '12-15', 4), self::ex('False Grip Hang', '30-35s', 4), self::ex('German Hang', '35-40s', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '8-10s', 2), self::ex('False Grip Ring Row', '6-8', 2), self::ex('False Grip Hang', '12-15s', 2), self::ex('German Hang', '15-20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL (apoyos, soportes y técnica de anillas)
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
            7 => self::sessionD_L7($phase),
            8 => self::sessionD_L8($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionD_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Puntas de dedos y apoyo: tolerancia de extensión de muñeca (base del falso agarre, N6 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Fingertip Hold', '10s', 2), self::ex('Support Hold', '10-15s', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Fingertip Hold', '15s', 2), self::ex('Support Hold', '15-20s', 2), self::ex('Hollow Body Hold', '20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Fingertip Hold', '15-20s', 2), self::ex('Support Hold', '20s', 2), self::ex('Hollow Body Hold', '25s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Fingertip Hold', '10s', 2), self::ex('Support Hold', '10-15s', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Primer false grip support hold (dosis N6-D del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '5-8s', 3), self::ex('Support Hold', '15s', 2), self::ex('Fingertip Hold', '15s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '8-10s', 3), self::ex('Support Hold', '15-20s', 2), self::ex('Fingertip Hold', '15-20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 10 s de apoyo en falso agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10s', 3), self::ex('Support Hold', '20s', 2), self::ex('Fingertip Hold', '15-20s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '5s', 2), self::ex('Support Hold', '10-15s', 2), self::ex('Fingertip Hold', '10s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Apoyo en anillas: ring support y falso agarre en soporte (dosis N6-D del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '8-10s', 3), self::ex('Ring Support Hold', '8-12s', 3), self::ex('Support Hold', '20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10s', 3), self::ex('Ring Support Hold', '12-15s', 3), self::ex('Ring Push Up', '5-8', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10-12s', 3), self::ex('Ring Support Hold', '15s', 3), self::ex('Ring Push Up', '8', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '8s', 2), self::ex('Ring Support Hold', '10s', 2), self::ex('Support Hold', '15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Soporte profundo: FG support 12-15 s con ring support y primer ring push-up', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '12-15s', 3), self::ex('Ring Support Hold', '12-15s', 3), self::ex('Ring Push Up', '8-10', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '15s', 3), self::ex('Ring Support Hold', '15-20s', 3), self::ex('Ring Push Up', '8-10', 2), self::ex('Skin The Cat', '3', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '15-20s', 3), self::ex('Ring Support Hold', '20s', 3), self::ex('Ring Push Up', '10', 2), self::ex('Skin The Cat', '3', 3)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10s', 2), self::ex('Ring Support Hold', '10-12s', 2), self::ex('Ring Push Up', '6', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'FG support 15-20 s y anillas (dosis N7-D del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '15-20s', 3), self::ex('Ring Support Hold', '15s', 3), self::ex('Ring Push Up', '8-10', 2), self::ex('Skin The Cat', '3', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '15-20s', 3), self::ex('Ring Support Hold', '15-20s', 3), self::ex('Ring Push Up', '10', 2), self::ex('Skin The Cat', '3', 3)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 20 s de soporte', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '20s', 3), self::ex('Ring Support Hold', '20s', 3), self::ex('Ring Push Up', '10-12', 2), self::ex('Skin The Cat', '4', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('False Grip Support Hold', '10s', 2), self::ex('Ring Support Hold', '10-15s', 2), self::ex('Ring Push Up', '6-8', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Transición de ring MU sobre falso agarre profundo (dosis B de L5 de MU Mastery)', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '3-5', 3), self::ex('Ring Support Hold', '15-20s', 3), self::ex('False Grip Support Hold', '15-20s', 2), self::ex('Ring Dip', '6', 2)], 150)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '4-6', 3), self::ex('Ring Support Hold', '20s', 3), self::ex('False Grip Support Hold', '15-20s', 2), self::ex('Ring Dip', '6-8', 2)], 150)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: transición 5-8 reps', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '5-8', 4), self::ex('Ring Support Hold', '20-25s', 3), self::ex('False Grip Support Hold', '20s', 2), self::ex('Ring Dip', '8', 2)], 180)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '3-4', 2), self::ex('Ring Support Hold', '15s', 2), self::ex('False Grip Support Hold', '10-15s', 2), self::ex('Ring Dip', '4-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Ring MU asistida sobre falso agarre profundo revisado en cada serie (dosis D de L11 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '3-5', 3), self::ex('Ring Muscle Up', '1', 3), self::ex('False Grip Support Hold', '15-20s', 2), self::ex('Ring Dip', '6-8', 2)], 180)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión: ring MU con asistencia mínima', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '4-6', 4), self::ex('Ring Muscle Up', '1-2', 4), self::ex('False Grip Support Hold', '15-20s', 2), self::ex('Ring Dip', '8', 2)], 180)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Pico: 2-3 ring MU frescas y primeras (el intento libre siempre al principio)', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '5-8', 4), self::ex('Ring Muscle Up', '2-3', 5), self::ex('False Grip Support Hold', '20s', 2), self::ex('Ring Dip', '8-10', 2)], 240)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación: patrón sin carga completa', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up Transition', '3-4', 2), self::ex('Ring Muscle Up', '1', 2), self::ex('False Grip Support Hold', '10-15s', 2), self::ex('Ring Dip', '4-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Dominio: ring MU con volumen y variedades (dosis B/D de L6 de MU Mastery)', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1-2', 3), self::ex('Ring Muscle Up Transition', '5-8', 2), self::ex('Ring Dip', '8-10', 2), self::ex('Tuck L-Sit', '10s', 2)], 150)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '2-3', 4), self::ex('Ring Muscle Up Transition', '5-8', 2), self::ex('Ring Dip', '10', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Pico: 3-5 ring MU y dips en anillas', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '3-5', 5), self::ex('Ring Muscle Up Transition', '8-10', 2), self::ex('Ring Dip', '10-12', 2), self::ex('Tuck L-Sit', '15s', 2)], 180)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Ring Muscle Up', '1', 2), self::ex('Ring Muscle Up Transition', '3-4', 2), self::ex('Ring Dip', '6-8', 2), self::ex('Tuck L-Sit', '8s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
