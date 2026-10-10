<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Front Lever Mastery v4 (8 niveles).
 * Especialización del Master v4: progresión de front lever de N6 (tuck)
 * a N12 (full), con la base de tracción y escápulas previa.
 * Estructura: 4 sesiones por fase (A=Strength tracción, B=Front lever,
 * C=Prehab codo-hombro, D=Core y skill). Fases: base, progression,
 * intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a codo y hombro:
 * - Los holds de front lever van con descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de front lever en frío: escápulas activas y German hang antes.
 * - Molestia matinal >2/10 en codo u hombro = congela ese gesto un ciclo.
 */
class FrontLeverV4Blueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Tracción y Escápulas', 'title' => 'Base de Tracción y Escápulas', 'description' => 'Preparar la tracción estricta, las escápulas activas y el core antes de tocar el front lever', 'objective' => 'Dominadas estrictas con volumen, escápulas activas en colgado y core hollow/arch sólido', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(8), Active Hang(30s)', 'skillFocus' => 'Standard Pull Up, Scapular Pull Up, Hollow Body Hold'],
            2 => ['name' => 'Nivel 2: Tuck Front Lever', 'title' => 'Tuck Front Lever', 'description' => 'Primer tuck front lever: 5-8 s con asistencia de banda (dosis N6 del Master)', 'objective' => 'Sostener el tuck front lever 5-8 s con técnica limpia y sin dolor en codo', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(8), Active Hang(30s)', 'skillFocus' => 'Tuck Front Lever, Scapular Pull Up, German Hang'],
            3 => ['name' => 'Nivel 3: Tuck Front Lever Sólido', 'title' => 'Tuck Front Lever Sólido', 'description' => 'Consolidar el tuck 8-10 s (gate del N6 del Master) e introducir el advanced tuck', 'objective' => 'Tuck front lever 8-10 s sin asistencia y primeras entradas al advanced tuck', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Front Lever(5s), Pull Up(10), German Hang(20s)', 'skillFocus' => 'Tuck Front Lever Hold, Advanced Tuck Front Lever, False Grip Ring Row'],
            4 => ['name' => 'Nivel 4: Advanced Tuck', 'title' => 'Advanced Tuck', 'description' => 'Advanced tuck 5-10 s (dosis N7 del Master) con tuck sólido de 12-15 s', 'objective' => 'Sostener el advanced tuck front lever 5-10 s con cadera alineada', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Front Lever(10s), Pull Up(10), German Hang(20s)', 'skillFocus' => 'Advanced Tuck Front Lever Hold, Tuck Front Lever Hold, Weighted Ring Row'],
            5 => ['name' => 'Nivel 5: One-Leg Front Lever', 'title' => 'One-Leg Front Lever', 'description' => 'One-leg front lever 4-6 s (dosis N8/N10 del Master); advanced tuck 10-12 s', 'objective' => 'Sostener el one-leg front lever 4-6 s por lado y el advanced tuck 10-12 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Advanced Tuck Front Lever(5s), Pull Up(12), Weighted Ring Row(10)', 'skillFocus' => 'One Leg Front Lever Hold, Advanced Tuck Front Lever, Pull Up'],
            6 => ['name' => 'Nivel 6: Straddle Front Lever', 'title' => 'Straddle Front Lever', 'description' => 'Straddle front lever 4-6 s (dosis N10/N11 del Master); one-leg 6-8 s', 'objective' => 'Sostener el straddle front lever 4-6 s y el one-leg 6-8 s por lado', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'One Leg Front Lever Hold(4s), Advanced Tuck Front Lever(8s), Pull Up(12)', 'skillFocus' => 'Straddle Front Lever Hold, One Leg Front Lever Hold, German Hang'],
            7 => ['name' => 'Nivel 7: Full Front Lever', 'title' => 'Full Front Lever', 'description' => 'Full front lever asistido 3-5 s (dosis N11/N12 del Master) con front lever raises; straddle 8-10 s', 'objective' => 'Primer full front lever (asistido) 3-5 s y front lever raises controladas', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Straddle Front Lever Hold(4s), One Leg Front Lever Hold(6s), Pull Up(12)', 'skillFocus' => 'Full Front Lever Hold (asistido), Front Lever Raise, Straddle Front Lever Hold'],
            8 => ['name' => 'Nivel 8: Dominio del Front Lever', 'title' => 'Dominio del Front Lever', 'description' => 'Full front lever 5-10 s (N12 del Master) con straddle 10 s+ y raises con volumen', 'objective' => 'Sostener el full front lever 5-10 s y dominar raises y straddle 10 s+', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Full Front Lever Hold(3s), Straddle Front Lever Hold(8s), Pull Up(15)', 'skillFocus' => 'Full Front Lever Hold, Front Lever Raise, Straddle Front Lever Hold'],
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
    // SESSION A — STRENGTH (Tracción estricta)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Dominadas y filas con técnica estricta: la tracción que sostiene el front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 3), self::ex('Ring Row - Elevated Feet', '8-10', 3), self::ex('Scapular Pull Up', '6-8', 2), self::ex('Active Hang', '20-30s', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de dominadas y filas', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3), self::ex('Scapular Pull Up', '8-10', 2), self::ex('Active Hang', '30s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 4), self::ex('Ring Row - Elevated Feet', '10-12', 4), self::ex('Scapular Pull Up', '8-10', 3), self::ex('Active Hang', '30-40s', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 2), self::ex('Ring Row - Elevated Feet', '8', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Active Hang', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta: el front lever se gana también en prono', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3), self::ex('Scapular Pull Up', '8-10', 2), self::ex('Arch Hold', '20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 3), self::ex('Ring Row - Elevated Feet', '12', 3), self::ex('Scapular Pull Up', '8-10', 2), self::ex('Arch Hold', '20-30s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 4), self::ex('Ring Row - Elevated Feet', '12-15', 4), self::ex('Scapular Pull Up', '10', 3), self::ex('Arch Hold', '30s', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 2), self::ex('Ring Row - Elevated Feet', '8-10', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Arch Hold', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta con contrapeso horizontal', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('False Grip Ring Row', '6-8', 3), self::ex('Chin Up', '6-8', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 3), self::ex('False Grip Ring Row', '8-10', 3), self::ex('Chin Up', '8', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 4), self::ex('False Grip Ring Row', '8-10', 4), self::ex('Chin Up', '8-10', 3)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 2), self::ex('False Grip Ring Row', '6', 2), self::ex('Chin Up', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción alta y filas con contrapeso: base del advanced tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Weighted Ring Row', '8-10', 3), self::ex('Chin Up', '6-8', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Ring Row', '10', 3), self::ex('Chin Up', '8', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 4), self::ex('Weighted Ring Row', '10-12', 4), self::ex('Chin Up', '8-10', 3)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 2), self::ex('Weighted Ring Row', '8', 2), self::ex('Chin Up', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción máxima: archers y peso añadido para el one-leg front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Chin Up', '5-8', 3), self::ex('Archer Ring Row', '6-8/lado', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Chin Up', '8-10', 3), self::ex('Archer Ring Row', '8/lado', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 4), self::ex('Weighted Chin Up', '10', 4), self::ex('Archer Ring Row', '8-10/lado', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6', 2), self::ex('Weighted Chin Up', '5-6', 2), self::ex('Archer Ring Row', '6/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción elite antes del straddle: dominadas altas y peso añadido', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Pull Up', '5-8', 3), self::ex('Weighted Ring Row', '10-12', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Pull Up', '8-10', 3), self::ex('Weighted Ring Row', '12', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 4), self::ex('Weighted Pull Up', '10', 4), self::ex('Weighted Ring Row', '12-15', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Weighted Pull Up', '5', 2), self::ex('Weighted Ring Row', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción de élite: el full front lever exige dominadas de sobra', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Pull Up', '8-10', 3), self::ex('Front Lever Raise', '2-3', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 3), self::ex('Weighted Pull Up', '10-12', 3), self::ex('Front Lever Raise', '3', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 4), self::ex('Weighted Pull Up', '12', 4), self::ex('Front Lever Raise', '3-5', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Weighted Pull Up', '5-6', 2), self::ex('Front Lever Raise', '2', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción elite con volumen y raises: plataforma del dominio', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 3), self::ex('Weighted Pull Up', '8-10', 3), self::ex('Front Lever Raise', '3-5', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '15', 3), self::ex('Weighted Pull Up', '10-12', 3), self::ex('Front Lever Raise', '5', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico de fuerza del programa', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '15-20', 4), self::ex('Weighted Pull Up', '12', 4), self::ex('Front Lever Raise', '5-8', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 2), self::ex('Weighted Pull Up', '5-6', 2), self::ex('Front Lever Raise', '3', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — FRONT LEVER (progresión principal)
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
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Primeras entradas al tuck con banda: patrón y colocación de cadera', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '3-5s', 3), self::ex('Tuck Front Lever Hold', '5s', 2), self::ex('Skin The Cat', '2-3', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Aumentar tiempo en tuck asistido', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '5s', 3), self::ex('Tuck Front Lever Hold', '5-8s', 2), self::ex('Skin The Cat', '3', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: 5-8 s en tuck', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '5-8s', 4), self::ex('Tuck Front Lever Hold', '8s', 2), self::ex('Skin The Cat', '3', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '3-5s', 2), self::ex('Tuck Front Lever Hold', '5s', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Tuck front lever 5-8 s con banda ligera (dosis N6 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '5-8s', 3), self::ex('Tuck Front Lever Hold', '5-8s', 2), self::ex('German Hang', '15-20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Reducir asistencia de la banda', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '8s', 3), self::ex('Tuck Front Lever Hold', '8-10s', 2), self::ex('German Hang', '20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: 8-10 s en tuck', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '8-10s', 4), self::ex('Tuck Front Lever Hold', '10s', 2), self::ex('German Hang', '20-30s', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever', '5s', 2), self::ex('Tuck Front Lever Hold', '5-8s', 2), self::ex('German Hang', '15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Tuck sólido 8-10 s (gate del N6 del Master) e introducción al advanced tuck', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever Hold', '8-10s', 3), self::ex('Advanced Tuck Front Lever', '3-5s', 2), self::ex('German Hang', '20s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Progresión hacia advanced tuck', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever Hold', '10s', 3), self::ex('Advanced Tuck Front Lever', '3-5s', 3), self::ex('German Hang', '20-30s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: tuck 10-12 s + advanced tuck 5 s', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever Hold', '10-12s', 4), self::ex('Advanced Tuck Front Lever', '5s', 3), self::ex('German Hang', '30s', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Tuck Front Lever Hold', '8s', 2), self::ex('Advanced Tuck Front Lever', '3s', 2), self::ex('German Hang', '15-20s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Advanced tuck 5-10 s (dosis N7 del Master) con tuck de respaldo', 'blocks' => [self::block(1, 1, 60, [self::ex('Advanced Tuck Front Lever Hold', '5-8s', 3), self::ex('Tuck Front Lever Hold', '12s', 2), self::ex('German Hang', '20-30s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Advanced Tuck Front Lever Hold', '8-10s', 3), self::ex('Tuck Front Lever Hold', '12-15s', 2), self::ex('German Hang', '30s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: advanced tuck 8-10 s', 'blocks' => [self::block(1, 1, 60, [self::ex('Advanced Tuck Front Lever Hold', '8-10s', 4), self::ex('Tuck Front Lever Hold', '15s', 2), self::ex('German Hang', '30-40s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Advanced Tuck Front Lever Hold', '5s', 2), self::ex('Tuck Front Lever Hold', '10s', 2), self::ex('German Hang', '20s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'One-leg front lever 4-6 s por lado (dosis N8/N10 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('One Leg Front Lever Hold', '4-6s', 3), self::ex('Advanced Tuck Front Lever Hold', '8-10s', 2), self::ex('German Hang', '30s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Progresión: simetría entre lados', 'blocks' => [self::block(1, 1, 60, [self::ex('One Leg Front Lever Hold', '6s', 3), self::ex('Advanced Tuck Front Lever Hold', '10s', 2), self::ex('German Hang', '30-40s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: one-leg 6 s + adv tuck 10-12 s', 'blocks' => [self::block(1, 1, 60, [self::ex('One Leg Front Lever Hold', '6s', 4), self::ex('Advanced Tuck Front Lever Hold', '10-12s', 3), self::ex('German Hang', '40s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('One Leg Front Lever Hold', '4s', 2), self::ex('Advanced Tuck Front Lever Hold', '8s', 2), self::ex('German Hang', '20-30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Straddle front lever 4-6 s (dosis N10/N11 del Master); one-leg de respaldo', 'blocks' => [self::block(1, 1, 60, [self::ex('Straddle Front Lever Hold', '4-6s', 3), self::ex('One Leg Front Lever Hold', '6-8s', 2), self::ex('German Hang', '30-40s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Straddle Front Lever Hold', '6s', 3), self::ex('One Leg Front Lever Hold', '8s', 2), self::ex('German Hang', '40s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: straddle 6 s + one-leg 8 s', 'blocks' => [self::block(1, 1, 60, [self::ex('Straddle Front Lever Hold', '6s', 4), self::ex('One Leg Front Lever Hold', '8s', 3), self::ex('German Hang', '40-50s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Straddle Front Lever Hold', '4s', 2), self::ex('One Leg Front Lever Hold', '6s', 2), self::ex('German Hang', '25-30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Full front lever asistido 3-5 s (dosis N11/N12 del Master) con banda', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '3-5s', 3), self::ex('Straddle Front Lever Hold', '8s', 2), self::ex('Front Lever Raise', '2-3', 2)], 180)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Progresión: reducir banda, añadir raises', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '3-5s', 3), self::ex('Straddle Front Lever Hold', '8-10s', 2), self::ex('Front Lever Raise', '3', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Volumen: full 5 s + straddle 10 s + raises', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '5s', 4), self::ex('Straddle Front Lever Hold', '10s', 3), self::ex('Front Lever Raise', '3-5', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '3s', 2), self::ex('Straddle Front Lever Hold', '6-8s', 2), self::ex('Front Lever Raise', '2', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: FL Base', 'goal' => 'Full front lever 5-10 s (N12 del Master); el intento libre va primero', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '5-8s', 3), self::ex('Straddle Front Lever Hold', '10s', 2), self::ex('Front Lever Raise', '3-5', 3)], 180)]],
            'progression' => ['name' => 'Sesión B: FL Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '8-10s', 3), self::ex('Straddle Front Lever Hold', '10-12s', 2), self::ex('Front Lever Raise', '5', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: FL Intensificación', 'goal' => 'Pico: full 10 s + straddle 12 s + raises', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '10s', 4), self::ex('Straddle Front Lever Hold', '12-15s', 3), self::ex('Front Lever Raise', '5-8', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: FL Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Full Front Lever Hold', '5s', 2), self::ex('Straddle Front Lever Hold', '8s', 2), self::ex('Front Lever Raise', '3', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (codo, hombro y German hang)
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
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'German hang suave y salud de hombro: el seguro del front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '10-15s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Scapular Pull Up', '6', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar tiempo de German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 3), self::ex('Face Pull', '12-15', 2), self::ex('Scapular Pull Up', '8', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20s', 3), self::ex('Face Pull', '15', 3), self::ex('Scapular Pull Up', '8-10', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '10s', 2), self::ex('Face Pull', '10', 2), self::ex('Scapular Pull Up', '6', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'German hang y rotadores de hombro para el tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 3), self::ex('Face Pull', '12-15', 2), self::ex('Skin The Cat', '2', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Face Pull', '15', 2), self::ex('Skin The Cat', '3', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen: 20-30 s de German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-30s', 3), self::ex('Face Pull', '15-18', 3), self::ex('Skin The Cat', '3', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Salud de codo y hombro bajo la primera carga real de front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20s', 3), self::ex('Face Pull', '15', 2), self::ex('Wrist Curl', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-30s', 3), self::ex('Face Pull', '15-18', 2), self::ex('Wrist Curl', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Face Pull', '18', 3), self::ex('Wrist Curl', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Wrist Curl', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'German hang largo y extensores de muñeca: el codo agradece la prevención', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-30s', 3), self::ex('Face Pull', '15', 2), self::ex('Reverse Wrist Curl', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Face Pull', '15-18', 2), self::ex('Reverse Wrist Curl', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-40s', 3), self::ex('Face Pull', '18-20', 3), self::ex('Reverse Wrist Curl', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Reverse Wrist Curl', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de codo y hombro para el one-leg (regla §1.7: un solo pico por fase)', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Face Pull', '15-18', 2), self::ex('Reverse Wrist Curl', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Face Pull', '18-20', 2), self::ex('Reverse Wrist Curl', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 3), self::ex('Face Pull', '20', 3), self::ex('Reverse Wrist Curl', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Reverse Wrist Curl', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Resistencia de German hang y salud de codo para el straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-40s', 3), self::ex('Face Pull', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40s', 3), self::ex('Face Pull', '18-20', 2), self::ex('Reverse Wrist Curl', '18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen: 40-50 s de German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40-50s', 3), self::ex('Face Pull', '20-22', 3), self::ex('Reverse Wrist Curl', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Reverse Wrist Curl', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'El seguro de vida del full: German hang largo y rotadores', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 3), self::ex('Face Pull', '18-20', 2), self::ex('Reverse Wrist Curl', '18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40-45s', 3), self::ex('Face Pull', '20', 2), self::ex('Reverse Wrist Curl', '18-20', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '45-50s', 3), self::ex('Face Pull', '20-22', 3), self::ex('Reverse Wrist Curl', '20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-30s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Reverse Wrist Curl', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Mantenimiento profundo: el dominio del front lever vive de codo sano', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40s', 3), self::ex('Face Pull', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Skin The Cat', '3', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40-50s', 3), self::ex('Face Pull', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Skin The Cat', '3-4', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '50s', 3), self::ex('Face Pull', '20-25', 3), self::ex('Reverse Wrist Curl', '20-22', 2), self::ex('Skin The Cat', '4', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Skin The Cat', '2', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — CORE Y SKILL (hollow, arch y L-sit)
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
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core hollow/arch: la rigidez que sostiene el front lever', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '15-20s', 3), self::ex('Arch Hold', '15-20s', 3), self::ex('Hanging Leg Raise', '6-8', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20-25s', 3), self::ex('Arch Hold', '20-25s', 3), self::ex('Hanging Leg Raise', '8-10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '25-30s', 4), self::ex('Arch Hold', '25-30s', 4), self::ex('Hanging Leg Raise', '10', 3)], 90)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '15s', 2), self::ex('Arch Hold', '15s', 2), self::ex('Hanging Leg Raise', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core más largo: hollow 20-30 s para sostener el tuck', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20-30s', 3), self::ex('Hanging Leg Raise', '8-10', 3), self::ex('Tuck L-Sit', '8-10s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Hanging Leg Raise', '10', 3), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-40s', 4), self::ex('Hanging Leg Raise', '10-12', 4), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20s', 2), self::ex('Hanging Leg Raise', '8', 2), self::ex('Tuck L-Sit', '8s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core y compresión: el advanced tuck exige cadera plegada fuerte', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Hanging Leg Raise', '10-12', 3), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-40s', 3), self::ex('Hanging Leg Raise', '12', 3), self::ex('Tuck L-Sit', '15s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '40-45s', 4), self::ex('Hanging Leg Raise', '12-15', 4), self::ex('Tuck L-Sit', '15-20s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Resistencia de hollow y compresión para el advanced tuck', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-40s', 3), self::ex('Hanging Leg Raise', '12', 3), self::ex('Tuck L-Sit', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '40-45s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '15-20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 4), self::ex('Hanging Leg Raise', '15', 4), self::ex('Tuck L-Sit', '20s', 3)], 90)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Hanging Leg Raise', '10', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core de resistencia: sostener la línea en el one-leg', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '40s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '15-20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45s', 3), self::ex('Hanging Leg Raise', '15', 3), self::ex('Tuck L-Sit', '20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 4), self::ex('Hanging Leg Raise', '15-18', 4), self::ex('Tuck L-Sit', '20-25s', 3)], 90)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Hanging Leg Raise', '10', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core y compresión para el straddle: piernas activas', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45s', 3), self::ex('Hanging Leg Raise', '15', 3), self::ex('Tuck L-Sit', '20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 3), self::ex('Hanging Leg Raise', '15-18', 3), self::ex('Tuck L-Sit', '20-25s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 4), self::ex('Hanging Leg Raise', '18', 4), self::ex('Tuck L-Sit', '25-30s', 3)], 90)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Tuck L-Sit', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core elite: la línea del full se sostiene desde el hollow', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 3), self::ex('Hanging Leg Raise', '15-18', 3), self::ex('Tuck L-Sit', '20-25s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Hanging Leg Raise', '18', 3), self::ex('Tuck L-Sit', '25-30s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 4), self::ex('Hanging Leg Raise', '18-20', 4), self::ex('Tuck L-Sit', '30s', 3)], 120)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core Base', 'goal' => 'Core de dominio: resistencia total para el full front lever', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Hanging Leg Raise', '18', 3), self::ex('Tuck L-Sit', '25-30s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Core Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60-75s', 3), self::ex('Hanging Leg Raise', '20', 3), self::ex('Tuck L-Sit', '30s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '75-90s', 4), self::ex('Hanging Leg Raise', '20-25', 4), self::ex('Tuck L-Sit', '30-45s', 3)], 120)]],
            'deload' => ['name' => 'Sesión D: Core Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-40s', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
