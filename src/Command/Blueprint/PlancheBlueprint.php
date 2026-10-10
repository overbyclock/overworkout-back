<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Planche Mastery (8 niveles).
 * Especialización del Master v4: progresión de planche desde N6/N7
 * (frog, tuck, advanced tuck) hasta N14/N15/Olimpo (full planche,
 * planche push-up, straddle planche push-up, maltese asistido).
 * Estructura: 4 sesiones por fase (A=Strength, B=Planche, C=Prehab, D=Skill).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a muñeca y hombro:
 * - Los holds de planche van con descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de planche en frío: muñeca y hombro calientes antes de cada serie.
 * - Molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.
 */
class PlancheBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Empuje y Muñeca', 'title' => 'Base de Empuje y Muñeca', 'description' => 'Preparar muñecas, hombros y empuje horizontal antes de cargar la línea de planche', 'objective' => 'Fortalecer flexores/extensores de muñeca, estabilidad escapular y empuje de base para soportar el lean y el tuck sin dolor', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Push Up(15), Wall Slide(10)', 'skillFocus' => 'Standard Push Up, Pike Push Up, Wrist Push-Up'],
            2 => ['name' => 'Nivel 2: Frog y Primer Tuck', 'title' => 'Frog y Primer Tuck', 'description' => 'Primer contacto con la línea de planche: frog stand y tuck asistido con puntas', 'objective' => 'Sostener frog stand 15-20 s y tuck planche con apoyo de puntas con cadera alta y codos bloqueados', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Frog Stand(15s), Tuck Planche Toe Support(8s)', 'skillFocus' => 'Frog Stand, Tuck Planche Toe Support, Pseudo Planche Push Up'],
            3 => ['name' => 'Nivel 3: Tuck Planche', 'title' => 'Tuck Planche', 'description' => 'Consolidar el tuck planche libre y el planche lean como peldaños de la línea', 'objective' => 'Sostener tuck planche 5-8 s y planche lean 20-30 s con proyección de hombros y codos bloqueados', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Planche(5s), Planche Lean(15s), PPPU(6)', 'skillFocus' => 'Tuck Planche, Planche Lean, Pseudo Planche Push Up'],
            4 => ['name' => 'Nivel 4: Advanced Tuck', 'title' => 'Advanced Tuck', 'description' => 'Abrir el tuck hasta advanced tuck y aumentar la carga en pseudo planche push-up', 'objective' => 'Sostener advanced tuck planche 5-8 s con cadera alineada y ejecutar PPPU con técnica limpia', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Advanced Tuck Planche(5s), Tuck Planche(8s), PPPU(8)', 'skillFocus' => 'Advanced Tuck Planche, Tuck Planche, Pseudo Planche Push Up'],
            5 => ['name' => 'Nivel 5: Straddle Entrada', 'title' => 'Straddle Entrada', 'description' => 'Primeros segundos de straddle planche manteniendo la calidad del advanced tuck', 'objective' => 'Conseguir straddle planche 4-6 s y mantener advanced tuck planche 8-10 s con cadera alta', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Planche(4s), Advanced Tuck Planche(8s), Planche Lean(30s)', 'skillFocus' => 'Straddle Planche, Advanced Tuck Planche, Planche Lean'],
            6 => ['name' => 'Nivel 6: Straddle Planche', 'title' => 'Straddle Planche', 'description' => 'Consolidar straddle planche y mantener la fuerza de empuje vertical y compresión', 'objective' => 'Sostener straddle planche 5-8 s y controlar la apertura de cadera sin que la línea se rompa', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Planche(5s), Advanced Tuck Planche(10s), Tuck L-Sit(12s)', 'skillFocus' => 'Straddle Planche, Advanced Tuck Planche, Tuck L-Sit'],
            7 => ['name' => 'Nivel 7: Full Planche / Press', 'title' => 'Full Planche / Press', 'description' => 'Acercarse a la full planche asistida y mantener el straddle sólido', 'objective' => 'Conseguir planche asistida 3-5 s y mantener straddle planche 8-10 s; empuje vertical de soporte', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Planche(3s), Straddle Planche(8s), Handstand Push Up(3)', 'skillFocus' => 'Planche, Straddle Planche, Handstand Push Up'],
            8 => ['name' => 'Nivel 8: Dominio', 'title' => 'Dominio', 'description' => 'Élite de la planche: full planche, planche push-up y primer contacto con maltese asistido', 'objective' => 'Sostener planche 5-8 s, ejecutar planche push-up 1-3 reps y maltese asistido técnico', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Planche(5s), Planche Push Up(1), Straddle Planche(8s)', 'skillFocus' => 'Planche, Planche Push Up, Straddle Planche'],
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
    // SESSION A — STRENGTH (Empuje horizontal y vertical de soporte)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza básica de empuje y salud escapular antes de cargar la muñeca en extensión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Pike Push Up', '5-6', 3), self::ex('Wall Slide', '10', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de empuje horizontal y vertical', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Pike Push Up', '6-8', 3), self::ex('Wall Slide', '10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base con técnica impecable', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 4), self::ex('Pike Push Up', '8', 3), self::ex('Decline Push Up', '8', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Pike Push Up', '5-6', 2), self::ex('Wall Slide', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje de base sólido mientras entra el trabajo de planche en las sesiones B/D', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Pike Push Up', '6-8', 3), self::ex('Decline Push Up', '6-8', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 3), self::ex('Pike Push Up', '8-10', 3), self::ex('Decline Push Up', '8-10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 4), self::ex('Pike Push Up', '10', 3), self::ex('Decline Push Up', '10', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10', 2), self::ex('Pike Push Up', '6', 2), self::ex('Decline Push Up', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje horizontal y vertical para sostener la línea de planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 3), self::ex('Pike Push Up', '8-10', 3), self::ex('Decline Push Up', '8-10', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 3), self::ex('Pike Push Up', '10', 3), self::ex('Decline Push Up', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '18-20', 4), self::ex('Pike Push Up', '10-12', 4), self::ex('Decline Push Up', '12', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 2), self::ex('Pike Push Up', '6-8', 2), self::ex('Decline Push Up', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje pesado para soportar el advanced tuck y la carga de hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 3), self::ex('Pike Push Up', '10', 3), self::ex('Decline Push Up', '10-12', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '18-20', 3), self::ex('Pike Push Up', '10-12', 3), self::ex('Decline Push Up', '12-15', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '20-22', 4), self::ex('Pike Push Up', '12-15', 4), self::ex('Decline Push Up', '15', 3)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 2), self::ex('Pike Push Up', '8', 2), self::ex('Decline Push Up', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje vertical y horizontal para abrir la straddle planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '10-12', 3), self::ex('Pike Push Up', '10-12', 3), self::ex('Handstand Push Up', '3-4', 3), self::ex('Wall Slide', '10', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '12-15', 3), self::ex('Pike Push Up', '12', 3), self::ex('Handstand Push Up', '4-5', 3), self::ex('Wall Slide', '10', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '15', 4), self::ex('Pike Push Up', '12-15', 4), self::ex('Handstand Push Up', '5-6', 4), self::ex('Wall Slide', '12', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '8-10', 2), self::ex('Pike Push Up', '6-8', 2), self::ex('Handstand Push Up', '2-3', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Mantener el empuje vertical mientras la straddle planche pesa en la sesión B', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '12-15', 3), self::ex('Handstand Push Up', '4-5', 3), self::ex('Pike Push Up', '12', 2), self::ex('Wall Slide', '10', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '15', 3), self::ex('Handstand Push Up', '5-6', 3), self::ex('Pike Push Up', '12-15', 3), self::ex('Wall Slide', '10', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '15-18', 4), self::ex('Handstand Push Up', '6-8', 4), self::ex('Pike Push Up', '15', 3), self::ex('Wall Slide', '12', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Decline Push Up', '8-10', 2), self::ex('Handstand Push Up', '3', 2), self::ex('Pike Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje vertical de soporte y mantenimiento de la línea de planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '3-4', 4), self::ex('Decline Push Up', '12-15', 3), self::ex('Pike Push Up', '12', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '4-5', 4), self::ex('Decline Push Up', '15', 3), self::ex('Pike Push Up', '12-15', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '5-6', 5), self::ex('Decline Push Up', '15-18', 4), self::ex('Pike Push Up', '15', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '2-3', 2), self::ex('Decline Push Up', '8-10', 2), self::ex('Pike Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje de élite: planche push-up y handstand push-up de soporte', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche Push Up', '1', 3), self::ex('Handstand Push Up', '4-5', 4), self::ex('Decline Push Up', '12-15', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche Push Up', '1-2', 4), self::ex('Handstand Push Up', '5-6', 4), self::ex('Decline Push Up', '15', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche Push Up', '2-3', 5), self::ex('Handstand Push Up', '6-8', 5), self::ex('Decline Push Up', '15-18', 4)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche Push Up', '1', 2), self::ex('Handstand Push Up', '3', 2), self::ex('Decline Push Up', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — PLANCHE (progresión principal)
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
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Primer contacto con la línea de planche: frog stand y lean liviano', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '10-15s', 3), self::ex('Planche Lean', '10s', 3), self::ex('Pseudo Planche Push Up', '3-5', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Aumentar tiempo de frog y lean', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '15-20s', 3), self::ex('Planche Lean', '12-15s', 3), self::ex('Pseudo Planche Push Up', '5-6', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen de base con calidad', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '20s', 3), self::ex('Planche Lean', '15s', 3), self::ex('Pseudo Planche Push Up', '6', 3)], 120)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '10s', 2), self::ex('Planche Lean', '10s', 2), self::ex('Pseudo Planche Push Up', '4', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Frog stand sólido y primer tuck asistido con puntas', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '15-20s', 3), self::ex('Tuck Planche Toe Support', '5-8s', 4), self::ex('Pseudo Planche Push Up', '5-6', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '20s', 3), self::ex('Tuck Planche Toe Support', '8-10s', 4), self::ex('Pseudo Planche Push Up', '6-8', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '20-25s', 3), self::ex('Tuck Planche Toe Support', '10s', 5), self::ex('Pseudo Planche Push Up', '8', 3)], 150)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Frog Stand', '10-15s', 2), self::ex('Tuck Planche Toe Support', '5-6s', 3), self::ex('Pseudo Planche Push Up', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Tuck planche libre y planche lean como peldaños principales', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche Toe Support', '8-10s', 4), self::ex('Tuck Planche', '3-5s', 4), self::ex('Planche Lean', '12-15s', 3)], 120)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche', '3-5s', 5), self::ex('Planche Lean', '15s', 3), self::ex('Pseudo Planche Push Up', '6-8', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche', '5-8s', 5), self::ex('Planche Lean', '15-20s', 3), self::ex('Pseudo Planche Push Up', '8', 3)], 150)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche Toe Support', '5-6s', 3), self::ex('Planche Lean', '10s', 2), self::ex('Pseudo Planche Push Up', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Advanced tuck planche con asistencia de banda y pseudo planche push-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Band Assisted Tuck Planche', '8-10s', 4), self::ex('Advanced Tuck Planche', '3-5s', 4), self::ex('Pseudo Planche Push Up', '6-8', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '4-6s', 5), self::ex('Tuck Planche', '8-10s', 3), self::ex('Pseudo Planche Push Up', '8', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '5-8s', 5), self::ex('Tuck Planche', '10s', 3), self::ex('Pseudo Planche Push Up', '10', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Band Assisted Tuck Planche', '5-6s', 3), self::ex('Tuck Planche', '5-6s', 3), self::ex('Pseudo Planche Push Up', '6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Entrada a straddle planche manteniendo advanced tuck fuerte', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '6-8s', 4), self::ex('Straddle Planche', '3-4s', 4), self::ex('Planche Lean', '20-25s', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '8-10s', 4), self::ex('Straddle Planche', '4-6s', 4), self::ex('Planche Lean', '25-30s', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '10-12s', 4), self::ex('Straddle Planche', '5-8s', 5), self::ex('Planche Lean', '30s', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '6-8s', 3), self::ex('Straddle Planche', '3-4s', 3), self::ex('Planche Lean', '15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Consolidar straddle planche y compresión de cadera', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '4-6s', 4), self::ex('Advanced Tuck Planche', '8-10s', 4), self::ex('Tuck L-Sit', '10-12s', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '5-8s', 5), self::ex('Advanced Tuck Planche', '10-12s', 4), self::ex('Tuck L-Sit', '12s', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '6-8s', 5), self::ex('Advanced Tuck Planche', '10-12s', 4), self::ex('Tuck L-Sit', '12-15s', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '4s', 3), self::ex('Advanced Tuck Planche', '6-8s', 3), self::ex('Tuck L-Sit', '8s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Full planche asistida y straddle planche de mantenimiento', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '5-8s', 5), self::ex('Planche', '2-3s', 4), self::ex('Advanced Tuck Planche', '8-10s', 3)], 180)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '6-8s', 5), self::ex('Planche', '3-5s', 5), self::ex('Advanced Tuck Planche', '8s', 2)], 180)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '8-10s', 5), self::ex('Planche', '3-5s', 5), self::ex('Tuck Planche', '10s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '4-5s', 3), self::ex('Planche', '2-3s', 3), self::ex('Advanced Tuck Planche', '5-6s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Planche Base', 'goal' => 'Dominio: full planche, planche push-up y straddle planche push-up', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche', '3-5s', 5), self::ex('Straddle Planche Push Up', '1-2', 4), self::ex('Straddle Planche', '8-10s', 3)], 180)]],
            'progression' => ['name' => 'Sesión B: Planche Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche', '4-6s', 5), self::ex('Straddle Planche Push Up', '2-3', 4), self::ex('Straddle Planche', '8-10s', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: Planche Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche', '5-8s', 5), self::ex('Straddle Planche Push Up', '2-3', 5), self::ex('Straddle Planche', '8-10s', 2)], 240)]],
            'deload' => ['name' => 'Sesión B: Planche Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Planche', '3s', 3), self::ex('Straddle Planche Push Up', '1', 2), self::ex('Straddle Planche', '5-6s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (muñeca, hombro y salud de codo)
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
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Fuerza de muñeca y extensores para preparar el lean', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '12', 2), self::ex('Reverse Wrist Curl', '12', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar rango y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Back-of-Hand Stretch', '30s', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de muñeca', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Back-of-Hand Stretch', '30s', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca fuerte y movilidad de hombro para el primer tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '12-15', 2), self::ex('Reverse Wrist Curl', '12', 2), self::ex('Shoulder Dislocate', '10', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Shoulder Dislocate', '10', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Shoulder Dislocate', '12', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Shoulder Dislocate', '8', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Mantenimiento de muñeca y hombro ante la carga del tuck planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Shoulder Dislocate', '10', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Shoulder Dislocate', '10', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Shoulder Dislocate', '12', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Shoulder Dislocate', '8', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca, codo y postura escapular para el advanced tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Face Pull', '12', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '15', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '15-18', 2), self::ex('Band Pull-Apart', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2), self::ex('Band Pull-Apart', '12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Proteger muñeca y hombro en la straddle planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Face Pull', '12', 2), self::ex('External Rotation', '12/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '15', 2), self::ex('External Rotation', '12/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '15-18', 2), self::ex('External Rotation', '15/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2), self::ex('External Rotation', '10/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca y rotadores externos para soportar la carga de straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '15', 2), self::ex('Cuban Rotation', '8', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Face Pull', '15-18', 2), self::ex('Cuban Rotation', '8-10', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '18', 2), self::ex('Cuban Rotation', '10', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2), self::ex('Cuban Rotation', '6', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de muñeca, hombro y codo para full planche asistida', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '15', 2), self::ex('Cuban Rotation', '8', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '18', 2), self::ex('Cuban Rotation', '10', 2), self::ex('Band Pull-Apart', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20-22', 2), self::ex('Reverse Wrist Curl', '20-22', 2), self::ex('Face Pull', '18-20', 2), self::ex('Cuban Rotation', '10-12', 2), self::ex('Band Pull-Apart', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2), self::ex('Cuban Rotation', '6', 2), self::ex('Band Pull-Apart', '12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de élite para sostener planche push-up y maltese asistido', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '15', 2), self::ex('Cuban Rotation', '8', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20-22', 2), self::ex('Reverse Wrist Curl', '20-22', 2), self::ex('Face Pull', '18', 2), self::ex('Cuban Rotation', '10', 2), self::ex('Band Pull-Apart', '18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '22-25', 2), self::ex('Reverse Wrist Curl', '22-25', 2), self::ex('Face Pull', '18-20', 2), self::ex('Cuban Rotation', '10-12', 2), self::ex('Band Pull-Apart', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2), self::ex('Cuban Rotation', '6', 2), self::ex('Band Pull-Apart', '12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL (lean, L-sit, hollow, compresión)
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
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Lean, compresión y hollow: base técnica de la planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '10s', 3), self::ex('Hollow Body Hold', '15s', 2), self::ex('Fingertip Hold', '10s', 2), self::ex('Tuck L-Sit', '8s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '15s', 3), self::ex('Hollow Body Hold', '20s', 2), self::ex('Fingertip Hold', '15s', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '20s', 3), self::ex('Hollow Body Hold', '25s', 2), self::ex('Fingertip Hold', '15-20s', 2), self::ex('Tuck L-Sit', '12s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '10s', 2), self::ex('Hollow Body Hold', '15s', 2), self::ex('Fingertip Hold', '10s', 2), self::ex('Tuck L-Sit', '8s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Lean pesado, compresión y primeras puntas de planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '15s', 3), self::ex('Tuck L-Sit', '8-10s', 2), self::ex('Hollow Body Hold', '15s', 2), self::ex('Fingertip Hold', '10s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '20s', 3), self::ex('Tuck L-Sit', '10s', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Fingertip Hold', '15s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '25s', 3), self::ex('Tuck L-Sit', '12s', 3), self::ex('Hollow Body Hold', '25s', 2), self::ex('Fingertip Hold', '15-20s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '10s', 2), self::ex('Tuck L-Sit', '8s', 2), self::ex('Hollow Body Hold', '15s', 2), self::ex('Fingertip Hold', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Planche lean como peldaño fuerte y compresión de cadera', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '20s', 3), self::ex('Tuck L-Sit', '10s', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Fingertip Hold', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '25s', 3), self::ex('Tuck L-Sit', '12s', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Fingertip Hold', '15-20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '30s', 3), self::ex('Tuck L-Sit', '12-15s', 3), self::ex('Hollow Body Hold', '30s', 2), self::ex('Fingertip Hold', '20s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '15s', 2), self::ex('Tuck L-Sit', '8-10s', 2), self::ex('Hollow Body Hold', '15s', 2), self::ex('Fingertip Hold', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Lean profundo, soporte en anillas y compresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '25s', 3), self::ex('Tuck L-Sit', '12s', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Ring Support Hold', '10-12s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '30s', 3), self::ex('Tuck L-Sit', '12-15s', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Ring Support Hold', '12-15s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '30-45s', 3), self::ex('L-Sit', '8-10s', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Ring Support Hold', '15s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '15-20s', 2), self::ex('Tuck L-Sit', '10s', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Ring Support Hold', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Lean máximo, L-sit y soporte en anillas', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '30s', 3), self::ex('L-Sit', '6-10s', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Ring Support Hold', '12-15s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '35-40s', 3), self::ex('L-Sit', '8-12s', 2), self::ex('Hollow Body Hold', '35s', 2), self::ex('Ring Support Hold', '15s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '45s', 3), self::ex('L-Sit', '10-15s', 3), self::ex('Hollow Body Hold', '40s', 2), self::ex('Ring Support Hold', '15-20s', 2)], 150)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '20s', 2), self::ex('L-Sit', '6-8s', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Ring Support Hold', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Lean, L-sit y soporte en anillas para sostener straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '40-45s', 3), self::ex('L-Sit', '10-12s', 2), self::ex('Hollow Body Hold', '35s', 2), self::ex('Ring Support Hold', '15s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '45-50s', 3), self::ex('L-Sit', '12-15s', 2), self::ex('Hollow Body Hold', '40s', 2), self::ex('Ring Support Hold', '15-20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '60s', 3), self::ex('L-Sit', '15s', 3), self::ex('Hollow Body Hold', '45s', 2), self::ex('Ring Support Hold', '20s', 2)], 150)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '25s', 2), self::ex('L-Sit', '8-10s', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Ring Support Hold', '10-12s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Mantenimiento de lean, L-sit y compresión ante la full planche', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '45-50s', 3), self::ex('L-Sit', '12-15s', 2), self::ex('Hollow Body Hold', '40s', 2), self::ex('Ring Support Hold', '15-20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '50-60s', 3), self::ex('L-Sit', '15s', 2), self::ex('Hollow Body Hold', '45s', 2), self::ex('Ring Support Hold', '20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '60s', 3), self::ex('L-Sit', '15-20s', 3), self::ex('Hollow Body Hold', '45-60s', 2), self::ex('Ring Support Hold', '20-25s', 2)], 150)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '25-30s', 2), self::ex('L-Sit', '8-10s', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Ring Support Hold', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Dominio de lean, L-sit y soporte para mantener la línea de élite', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '60s', 3), self::ex('L-Sit', '15s', 2), self::ex('Hollow Body Hold', '45s', 2), self::ex('Ring Support Hold', '20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '60s', 3), self::ex('L-Sit', '15-20s', 2), self::ex('Hollow Body Hold', '60s', 2), self::ex('Ring Support Hold', '20-25s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '60s', 3), self::ex('L-Sit', '20s', 3), self::ex('Hollow Body Hold', '60s', 2), self::ex('Ring Support Hold', '25s', 2)], 150)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Lean', '30s', 2), self::ex('L-Sit', '10s', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Ring Support Hold', '12-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
