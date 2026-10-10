<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa L-Sit & Press Mastery (8 niveles).
 * Especialización del Master v4: compresión de N6/N7 (tuck L-sit), L-sit de
 * N8/N9/N11 (10-15 s), V-sit y straddle press del test de N13, press negativo
 * de N13-N14 y dosis de prehab muñeca-hombro de N1-N3.
 * Estructura: 4 sesiones por fase (A=Strength, B=Compresión y L-sit,
 * C=Prehab muñeca-hombro-movilidad, D=Press y skill).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a muñeca y cadera:
 * - Los holds de L-sit/V-sit van con descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de press ni L-sit en frío: muñeca y cadera calientes antes de cada serie.
 * - Molestia matinal >2/10 en muñeca o cadera = congela ese gesto un ciclo.
 */
class LSitPressBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Compresión y Core', 'title' => 'Base de Compresión y Core', 'description' => 'Despertar la compresión activa: hollow, leg raises, primer tuck hold y movilidad de pike', 'objective' => 'Dominar el hollow body 30 s, hanging leg raise 8 reps y sostener el tuck L-sit 5 s', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Hollow Body Hold(30s), Hanging Leg Raise(8)', 'skillFocus' => 'Hollow Body Hold, Hanging Leg Raise, Tuck L-Sit'],
            2 => ['name' => 'Nivel 2: Tuck L-Sit', 'title' => 'Tuck L-Sit', 'description' => 'Consolidar el tuck L-sit a 8-12 s en suelo y paralelas con compresión sentada', 'objective' => 'Sostener tuck L-sit 10 s, 15 seated leg raises y 20-30 s de support hold', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Hollow Body Hold(30s), Hanging Leg Raise(8), Tuck L-Sit(5s)', 'skillFocus' => 'Tuck L-Sit, Seated Leg Raise, Support Hold'],
            3 => ['name' => 'Nivel 3: Primer L-Sit', 'title' => 'Primer L-Sit', 'description' => 'Unir fuerza y compresión: las primeras patadas hacia el L-sit completo, también en anillas', 'objective' => 'Conseguir el primer L-sit de 5-10 s (hito N6-N7 del Master)', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck L-Sit(10s), Seated Leg Raise(10), Support Hold(20s)', 'skillFocus' => 'L-Sit, Tuck L-Sit, Hanging Leg Raise'],
            4 => ['name' => 'Nivel 4: L-Sit Sólido', 'title' => 'L-Sit Sólido', 'description' => 'L-sit de 10-15 s, primeras transiciones al V-sit tucked y press a pino asistido', 'objective' => 'L-sit estable de 10-15 s (dosis N8-N9) y los primeros press negativos', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'L-Sit(5s), Tuck L-Sit(15s), Hanging Leg Raise(12)', 'skillFocus' => 'L-Sit 10s, Seated Leg Raise, Pike Press Negative'],
            5 => ['name' => 'Nivel 5: V-Sit', 'title' => 'V-Sit', 'description' => 'Abrir la cadera hacia el V-sit: holds altos, compresión straddle y press negativo', 'objective' => 'Sostener el V-sit 5-8 s y construir el straddle fold de 45-60 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'L-Sit(10s), Seated Leg Raise(15), Pike Press Negative(3)', 'skillFocus' => 'V-Sit, L-Sit 15s, Straddle Fold'],
            6 => ['name' => 'Nivel 6: Straddle Press', 'title' => 'Straddle Press', 'description' => 'El straddle press asistido a pies y el V-sit de 10 s (test N13 del Master)', 'objective' => 'Primeros straddle press asistidos, V-sit 10 s y pike de pie de 90 s', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'V-Sit(5s), L-Sit(15s), Straddle Fold(45s)', 'skillFocus' => 'Straddle Press asistido, V-Sit, Standing Pike Stretch'],
            7 => ['name' => 'Nivel 7: Press a Pino', 'title' => 'Press a Pino', 'description' => 'Press negativo sin cajón y V-sit de 10-15 s: la fuerza de empuje vertical completa', 'objective' => 'Press negativo limpio de 5-8 reps, V-sit 10-15 s y handstand push up 3-5', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Pike Press Negative(5), V-Sit(8s), Handstand Push Up(3)', 'skillFocus' => 'Pike Press Negative, V-Sit, Handstand Push Up'],
            8 => ['name' => 'Nivel 8: Dominio', 'title' => 'Dominio', 'description' => 'Élite: straddle press limpio, V-sit 15 s+ y L-sit 20 s a la altura del Olimpo', 'objective' => 'Straddle press limpio 1-3 reps, V-sit 15-20 s y L-sit 20-30 s', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Straddle Press(1), V-Sit(15s), L-Sit(20s)', 'skillFocus' => 'Straddle Press, V-Sit, L-Sit'],
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
            default => 90,
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
    // SESSION A — STRENGTH (tracción y empuje de soporte)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza básica de empuje y tracción para sostener la compresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Standard Pull Up', '3-5', 3), self::ex('Dip', '6-8', 2), self::ex('Scapular Pull Up', '6', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de empuje y tracción', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Standard Pull Up', '5-6', 3), self::ex('Dip', '8-10', 2), self::ex('Scapular Pull Up', '8', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 4), self::ex('Standard Pull Up', '6-8', 4), self::ex('Dip', '10', 3), self::ex('Scapular Pull Up', '8', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Standard Pull Up', '4', 2), self::ex('Dip', '6', 2), self::ex('Scapular Pull Up', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza de empuje y tracción para el tuck sólido', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Standard Pull Up', '5-6', 3), self::ex('Dip', '8-10', 3), self::ex('Scapular Pull Up', '8', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 3), self::ex('Standard Pull Up', '6-8', 3), self::ex('Dip', '10', 3), self::ex('Scapular Pull Up', '10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 4), self::ex('Standard Pull Up', '8', 4), self::ex('Dip', '10-12', 3), self::ex('Scapular Pull Up', '10', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Standard Pull Up', '4-5', 2), self::ex('Dip', '6-8', 2), self::ex('Scapular Pull Up', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta y empuje inclinado para el primer L-sit', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 3), self::ex('Pike Push Up', '6-8', 3), self::ex('Chin Up', '5-6', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Pike Push Up', '8', 3), self::ex('Chin Up', '6-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 4), self::ex('Pike Push Up', '8-10', 4), self::ex('Chin Up', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '4-5', 2), self::ex('Pike Push Up', '5-6', 2), self::ex('Chin Up', '4-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta y empuje vertical hacia el press', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Elevated Pike Push Up', '5-8', 3), self::ex('Chin Up', '6-8', 3), self::ex('Dip', '10', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Elevated Pike Push Up', '8', 3), self::ex('Chin Up', '8-10', 3), self::ex('Dip', '10-12', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 4), self::ex('Elevated Pike Push Up', '8-10', 4), self::ex('Chin Up', '10-12', 4), self::ex('Dip', '12', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 2), self::ex('Elevated Pike Push Up', '5-6', 2), self::ex('Chin Up', '5-6', 2), self::ex('Dip', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción con volumen y empuje vertical elevado', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Elevated Pike Push Up', '8-10', 3), self::ex('Chin Up', '8', 3), self::ex('Ring Dip', '6-8', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Elevated Pike Push Up', '10', 3), self::ex('Chin Up', '8-10', 3), self::ex('Ring Dip', '8', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12', 4), self::ex('Elevated Pike Push Up', '10-12', 4), self::ex('Chin Up', '10', 4), self::ex('Ring Dip', '8-10', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6', 2), self::ex('Elevated Pike Push Up', '6-8', 2), self::ex('Chin Up', '5-6', 2), self::ex('Ring Dip', '4-6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje vertical hacia el pino y tracción con L-sit', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Handstand Push Up', '1-3', 3), self::ex('Chin Up', '8-10', 3), self::ex('Ring Dip', '8', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Handstand Push Up', '2-4', 3), self::ex('Chin Up', '10', 3), self::ex('Ring Dip', '8-10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12', 4), self::ex('Handstand Push Up', '3-5', 4), self::ex('Chin Up', '10-12', 4), self::ex('Ring Dip', '10', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6', 2), self::ex('Handstand Push Up', '1-2', 2), self::ex('Chin Up', '6', 2), self::ex('Ring Dip', '5-6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza vertical completa: HSPU y tracción con L-sit', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Handstand Push Up', '3-5', 3), self::ex('L-Sit Pull Up', '3-5', 3), self::ex('Dip', '10-12', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12', 3), self::ex('Handstand Push Up', '4-6', 4), self::ex('L-Sit Pull Up', '4-6', 3), self::ex('Dip', '12', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 4), self::ex('Handstand Push Up', '5-6', 4), self::ex('L-Sit Pull Up', '5-6', 4), self::ex('Dip', '12-15', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Handstand Push Up', '2-3', 2), self::ex('L-Sit Pull Up', '2-3', 2), self::ex('Dip', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Élite: tracción con L-sit y empuje vertical de Olimpo', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit Pull Up', '5-8', 3), self::ex('Handstand Push Up', '5-8', 3), self::ex('Strict Pull Up', '12-15', 3), self::ex('Ring Dip', '10-12', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit Pull Up', '6-8', 4), self::ex('Handstand Push Up', '6-8', 4), self::ex('Strict Pull Up', '15', 3), self::ex('Ring Dip', '12', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen pico del programa', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit Pull Up', '8-10', 4), self::ex('Handstand Push Up', '8-10', 4), self::ex('Strict Pull Up', '15-18', 4), self::ex('Ring Dip', '12-15', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit Pull Up', '4-5', 2), self::ex('Handstand Push Up', '4-5', 2), self::ex('Strict Pull Up', '8', 2), self::ex('Ring Dip', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — COMPRESIÓN Y L-SIT (núcleo del programa)
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
            'base' => ['name' => 'Sesión B: Compresión Base', 'goal' => 'Despertar la compresión activa: hollow y leg raises con cadera cerrada', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-30s', 3), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '5s', 2), self::ex('Arch Hold', '20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Compresión Progresión', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Hanging Leg Raise', '8', 3), self::ex('Tuck L-Sit', '5-8s', 2), self::ex('Arch Hold', '25s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Compresión Intensificación', 'goal' => 'Volumen: hollow 30-45 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-45s', 4), self::ex('Hanging Leg Raise', '10', 3), self::ex('Tuck L-Sit', '8-10s', 3), self::ex('Arch Hold', '30s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: Compresión Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20s', 2), self::ex('Hanging Leg Raise', '6', 2), self::ex('Tuck L-Sit', '5s', 2), self::ex('Arch Hold', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Tuck L-Sit Base', 'goal' => 'Tuck L-sit de 8-10 s en suelo y paralelas (dosis N6 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '8-10s', 3), self::ex('Hanging Leg Raise', '8-10', 3), self::ex('Hollow Body Hold', '30-40s', 2), self::ex('Seated Leg Raise', '8-10', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Tuck L-Sit Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '10-12s', 3), self::ex('Hanging Leg Raise', '10', 3), self::ex('Hollow Body Hold', '40s', 2), self::ex('Seated Leg Raise', '10-12', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Tuck L-Sit Intensificación', 'goal' => 'Volumen: tuck 12-15 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '12-15s', 4), self::ex('Hanging Leg Raise', '12', 3), self::ex('Hollow Body Hold', '45s', 2), self::ex('Seated Leg Raise', '12', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: Tuck L-Sit Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '8s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Seated Leg Raise', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: L-Sit Base', 'goal' => 'Primeras patadas al L-sit completo desde el tuck sólido (hito N6-N7)', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '10-15s', 3), self::ex('L-Sit', '3-5s', 3), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Hollow Body Hold', '40s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: L-Sit Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '12-15s', 3), self::ex('L-Sit', '5-8s', 3), self::ex('Hanging Leg Raise', '12', 2), self::ex('Hollow Body Hold', '45s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: L-Sit Intensificación', 'goal' => 'Volumen: primer L-sit de 10 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '15-20s', 3), self::ex('L-Sit', '8-10s', 4), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Hollow Body Hold', '45-60s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: L-Sit Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck L-Sit', '10s', 2), self::ex('L-Sit', '3-5s', 2), self::ex('Hanging Leg Raise', '8', 2), self::ex('Hollow Body Hold', '30s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: L-Sit Sólido Base', 'goal' => 'L-sit de 10-15 s en suelo y anillas (dosis N8-N9 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '5-8s', 3), self::ex('Tuck L-Sit', '12-15s', 2), self::ex('Seated Leg Raise', '12-15', 3), self::ex('Hollow Body Hold', '45s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: L-Sit Sólido Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '8-10s', 3), self::ex('Tuck L-Sit', '15s', 2), self::ex('Seated Leg Raise', '15', 3), self::ex('Hollow Body Hold', '50s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: L-Sit Sólido Intensificación', 'goal' => 'Volumen: L-sit 10-15 s', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '10-15s', 4), self::ex('L-Sit One Leg', '10-15s', 2), self::ex('Seated Leg Raise', '15-18', 3), self::ex('Hollow Body Hold', '60s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: L-Sit Sólido Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '8s', 2), self::ex('Tuck L-Sit', '10s', 2), self::ex('Seated Leg Raise', '10', 2), self::ex('Hollow Body Hold', '30s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: V-Sit Base', 'goal' => 'Abrir la cadera hacia el V-sit con compresión straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '10-15s', 3), self::ex('V-Sit Hold', '3-5s', 3), self::ex('Seated Leg Raise', '15-18', 2), self::ex('Straddle Fold', '30-45s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: V-Sit Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '12-15s', 3), self::ex('V-Sit Hold', '5-6s', 3), self::ex('Seated Leg Raise', '18', 2), self::ex('Straddle Fold', '45-60s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: V-Sit Intensificación', 'goal' => 'Volumen: V-sit de 5-8 s', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '15s', 3), self::ex('V-Sit Hold', '5-8s', 4), self::ex('Seated Leg Raise', '18-20', 3), self::ex('Straddle Fold', '60s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: V-Sit Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('L-Sit', '10s', 2), self::ex('V-Sit Hold', '3-5s', 2), self::ex('Seated Leg Raise', '12', 2), self::ex('Straddle Fold', '30s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: V-Sit Fuerte Base', 'goal' => 'V-sit de 8-10 s (test N13 del Master) con compresión straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '5-8s', 3), self::ex('L-Sit', '15s', 3), self::ex('Seated Leg Raise', '18-20', 2), self::ex('Straddle Fold', '45-60s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: V-Sit Fuerte Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '8-10s', 3), self::ex('L-Sit', '15-20s', 3), self::ex('Seated Leg Raise', '20', 2), self::ex('Straddle Fold', '60s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: V-Sit Fuerte Intensificación', 'goal' => 'Volumen: marca de V-sit 10 s', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '10s', 4), self::ex('L-Sit', '20s', 3), self::ex('Seated Leg Raise', '20-22', 3), self::ex('Straddle Fold', '60-90s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: V-Sit Fuerte Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '5s', 2), self::ex('L-Sit', '12s', 2), self::ex('Seated Leg Raise', '12-15', 2), self::ex('Straddle Fold', '45s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Dominio Base', 'goal' => 'V-sit 10-15 s manteniendo el L-sit de 20 s', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '8-10s', 3), self::ex('L-Sit', '15-20s', 3), self::ex('Seated Leg Raise', '20', 2), self::ex('Standing Pike Stretch', '60-90s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Dominio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '10-12s', 3), self::ex('L-Sit', '20s', 3), self::ex('Seated Leg Raise', '20-22', 2), self::ex('Standing Pike Stretch', '90s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Dominio Intensificación', 'goal' => 'Volumen: V-sit 12-15 s', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '12-15s', 4), self::ex('L-Sit', '20-25s', 3), self::ex('Seated Leg Raise', '22-25', 3), self::ex('Standing Pike Stretch', '90-120s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: Dominio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '6-8s', 2), self::ex('L-Sit', '15s', 2), self::ex('Seated Leg Raise', '15', 2), self::ex('Standing Pike Stretch', '60s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Olimpo Base', 'goal' => 'Élite de compresión: V-sit 15 s+, L-sit 20 s+', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '10-15s', 3), self::ex('L-Sit', '20s', 3), self::ex('Seated Leg Raise', '20-25', 2), self::ex('Straddle Fold', '60-90s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Olimpo Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '15s', 3), self::ex('L-Sit', '20-25s', 3), self::ex('Seated Leg Raise', '25', 2), self::ex('Straddle Fold', '90s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Olimpo Intensificación', 'goal' => 'Volumen pico: V-sit 15-20 s, L-sit 25-30 s', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '15-20s', 4), self::ex('L-Sit', '25-30s', 4), self::ex('Seated Leg Raise', '25', 3), self::ex('Straddle Fold', '90-120s', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: Olimpo Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('V-Sit Hold', '10s', 2), self::ex('L-Sit', '15-20s', 2), self::ex('Seated Leg Raise', '15', 2), self::ex('Straddle Fold', '60s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (muñeca, hombro y movilidad de cadera)
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
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca y hombro saludables: la compresión se apoya en ellos (dosis N1 del Master)', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Diaphragmatic Breathing', '5'),
                self::ex('Wrist Prayer', '20-30s', 2),
                self::ex('Wall Slide', '8-10', 2),
                self::ex('Shoulder Dislocate', '8', 2),
                self::ex('Wrist Curl', '12', 2),
                self::ex('Reverse Wrist Curl', '12', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar rango y reps', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Diaphragmatic Breathing', '5'),
                self::ex('Wrist Prayer', '30s', 2),
                self::ex('Wall Slide', '10', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Wrist Curl', '12-15', 2),
                self::ex('Reverse Wrist Curl', '12-15', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de prehab', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Diaphragmatic Breathing', '5'),
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Wall Slide', '10-12', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Wrist Curl', '15', 2),
                self::ex('Reverse Wrist Curl', '15', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Diaphragmatic Breathing', '5'),
                self::ex('Wrist Prayer', '20s', 2),
                self::ex('Wall Slide', '8', 2),
                self::ex('Shoulder Dislocate', '8', 2),
                self::ex('Wrist Curl', '10', 2),
                self::ex('Reverse Wrist Curl', '10', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca tolerante a la extensión y hombro móvil para los soportes', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30s', 2),
                self::ex('Wrist Push-Up', '5', 2),
                self::ex('Wall Slide', '10', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Wrist Curl', '12-15', 2),
                self::ex('Face Pull', '12', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Wrist Push-Up', '6-8', 2),
                self::ex('Wall Slide', '10-12', 2),
                self::ex('Shoulder Dislocate', '10-12', 2),
                self::ex('Wrist Curl', '15', 2),
                self::ex('Face Pull', '12-15', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45s', 2),
                self::ex('Wrist Push-Up', '8', 2),
                self::ex('Wall Slide', '12', 2),
                self::ex('Shoulder Dislocate', '12', 2),
                self::ex('Wrist Curl', '15-18', 2),
                self::ex('Face Pull', '15', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30s', 2),
                self::ex('Wrist Push-Up', '5', 2),
                self::ex('Wall Slide', '8', 2),
                self::ex('Shoulder Dislocate', '8', 2),
                self::ex('Wrist Curl', '10', 2),
                self::ex('Face Pull', '10', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca y hombro para apoyos largos; entra la cadera', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Wrist Push-Up', '6-8', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Face Pull', '12-15', 2),
                self::ex('Kneeling Hip Flexor Stretch', '30s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45s', 2),
                self::ex('Wrist Push-Up', '8', 2),
                self::ex('Shoulder Dislocate', '12', 2),
                self::ex('Face Pull', '15', 2),
                self::ex('Kneeling Hip Flexor Stretch', '30-45s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45-60s', 2),
                self::ex('Wrist Push-Up', '8-10', 2),
                self::ex('Shoulder Dislocate', '12', 2),
                self::ex('Face Pull', '15-18', 2),
                self::ex('Kneeling Hip Flexor Stretch', '45s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30s', 2),
                self::ex('Wrist Push-Up', '5-6', 2),
                self::ex('Shoulder Dislocate', '8', 2),
                self::ex('Face Pull', '10-12', 2),
                self::ex('Kneeling Hip Flexor Stretch', '30s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca para el press y cadera para la compresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Wrist Push-Up', '6-8', 2),
                self::ex('Shoulder Dislocate', '10-12', 2),
                self::ex('Face Pull', '12-15', 2),
                self::ex('Standing Pike Stretch', '30-45s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45s', 2),
                self::ex('Wrist Push-Up', '8-10', 2),
                self::ex('Shoulder Dislocate', '12', 2),
                self::ex('Face Pull', '15', 2),
                self::ex('Standing Pike Stretch', '45-60s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45-60s', 2),
                self::ex('Wrist Push-Up', '10', 2),
                self::ex('Shoulder Dislocate', '12-15', 2),
                self::ex('Face Pull', '15-18', 2),
                self::ex('Standing Pike Stretch', '60-90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30s', 2),
                self::ex('Wrist Push-Up', '5-6', 2),
                self::ex('Shoulder Dislocate', '8-10', 2),
                self::ex('Face Pull', '10-12', 2),
                self::ex('Standing Pike Stretch', '30-45s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'La cadera (pancake/pike) y la muñeca son los dos cuellos de botella', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45s', 2),
                self::ex('Shoulder Dislocate', '10-12', 2),
                self::ex('Face Pull', '15', 2),
                self::ex('Adductor Rockback', '8-10', 2),
                self::ex('Standing Pike Stretch', '60s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45-60s', 2),
                self::ex('Shoulder Dislocate', '12', 2),
                self::ex('Face Pull', '15-18', 2),
                self::ex('Adductor Rockback', '10-12', 2),
                self::ex('Standing Pike Stretch', '60-90s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60s', 2),
                self::ex('Shoulder Dislocate', '12-15', 2),
                self::ex('Face Pull', '18', 2),
                self::ex('Adductor Rockback', '12', 2),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Face Pull', '10-12', 2),
                self::ex('Adductor Rockback', '8', 2),
                self::ex('Standing Pike Stretch', '45s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Pancake y pike profundos para que el straddle press no choque', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45s', 2),
                self::ex('Shoulder Dislocate', '12', 2),
                self::ex('Face Pull', '15-18', 2),
                self::ex('Adductor Rockback', '10-12', 2),
                self::ex('Jefferson Curl', '5-8', 2),
                self::ex('Straddle Fold', '60s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45-60s', 2),
                self::ex('Shoulder Dislocate', '12-15', 2),
                self::ex('Face Pull', '18', 2),
                self::ex('Adductor Rockback', '12', 2),
                self::ex('Jefferson Curl', '6-8', 2),
                self::ex('Straddle Fold', '60-90s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60s', 2),
                self::ex('Shoulder Dislocate', '15', 2),
                self::ex('Face Pull', '18-20', 2),
                self::ex('Adductor Rockback', '12-15', 2),
                self::ex('Jefferson Curl', '8', 2),
                self::ex('Straddle Fold', '90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Face Pull', '10-12', 2),
                self::ex('Adductor Rockback', '8-10', 2),
                self::ex('Jefferson Curl', '5', 2),
                self::ex('Straddle Fold', '60s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Hombro sano bajo el volumen de press y pike profundo', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '45-60s', 2),
                self::ex('Shoulder Dislocate', '12-15', 2),
                self::ex('Face Pull', '18', 2),
                self::ex('Wall Slide', '10-12', 2),
                self::ex('Jefferson Curl', '6-8', 2),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60s', 2),
                self::ex('Shoulder Dislocate', '15', 2),
                self::ex('Face Pull', '18-20', 2),
                self::ex('Wall Slide', '12', 2),
                self::ex('Jefferson Curl', '8', 2),
                self::ex('Standing Pike Stretch', '90-120s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60s', 2),
                self::ex('Shoulder Dislocate', '15', 2),
                self::ex('Face Pull', '20-22', 2),
                self::ex('Wall Slide', '12-15', 2),
                self::ex('Jefferson Curl', '8-10', 2),
                self::ex('Standing Pike Stretch', '120s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Shoulder Dislocate', '10', 2),
                self::ex('Face Pull', '12', 2),
                self::ex('Wall Slide', '8-10', 2),
                self::ex('Jefferson Curl', '5-6', 2),
                self::ex('Standing Pike Stretch', '60s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab élite: muñeca y hombro aguantan el pico del programa', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60s', 2),
                self::ex('Shoulder Dislocate', '12-15', 2),
                self::ex('Face Pull', '18-20', 2),
                self::ex('Jefferson Curl', '8', 2),
                self::ex('Straddle Fold', '90s', 2),
                self::ex('Diaphragmatic Breathing', '5', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60s', 2),
                self::ex('Shoulder Dislocate', '15', 2),
                self::ex('Face Pull', '20', 2),
                self::ex('Jefferson Curl', '8-10', 2),
                self::ex('Straddle Fold', '90-120s', 2),
                self::ex('Diaphragmatic Breathing', '5', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '60-90s', 2),
                self::ex('Shoulder Dislocate', '15', 2),
                self::ex('Face Pull', '20-22', 2),
                self::ex('Jefferson Curl', '10', 2),
                self::ex('Straddle Fold', '120s', 2),
                self::ex('Diaphragmatic Breathing', '5', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Wrist Prayer', '30-45s', 2),
                self::ex('Shoulder Dislocate', '10-12', 2),
                self::ex('Face Pull', '12-15', 2),
                self::ex('Jefferson Curl', '5-6', 2),
                self::ex('Straddle Fold', '60-90s', 2),
                self::ex('Diaphragmatic Breathing', '5', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — PRESS Y SKILL (soportes, press a pino y movilidad)
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
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Pike y soporte: la movilidad de cadera es la mitad del L-sit', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Standing Pike Stretch', '30-45s', 2),
                self::ex('Seated Pike Stretch', '30s', 2),
                self::ex('Support Hold', '15-20s', 2),
                self::ex('Hollow Body Hold', '20s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Standing Pike Stretch', '45s', 2),
                self::ex('Seated Pike Stretch', '30-45s', 2),
                self::ex('Support Hold', '20s', 2),
                self::ex('Hollow Body Hold', '25s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Standing Pike Stretch', '45-60s', 2),
                self::ex('Seated Pike Stretch', '45s', 2),
                self::ex('Support Hold', '20-25s', 3),
                self::ex('Hollow Body Hold', '30s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Standing Pike Stretch', '30s', 2),
                self::ex('Seated Pike Stretch', '30s', 2),
                self::ex('Support Hold', '15s', 2),
                self::ex('Hollow Body Hold', '20s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Support hold y tuck en paralelas: el apoyo estable precede al hold', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Support Hold', '20-25s', 3),
                self::ex('Tuck L-Sit', '8-10s', 3),
                self::ex('Standing Pike Stretch', '45-60s', 2),
                self::ex('Seated Pike Stretch', '30-45s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Support Hold', '25-30s', 3),
                self::ex('Tuck L-Sit', '10-12s', 3),
                self::ex('Standing Pike Stretch', '60s', 2),
                self::ex('Seated Pike Stretch', '45s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: support 30 s', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Support Hold', '30s', 3),
                self::ex('Tuck L-Sit', '12-15s', 4),
                self::ex('Standing Pike Stretch', '60-90s', 2),
                self::ex('Seated Pike Stretch', '45-60s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Support Hold', '20s', 2),
                self::ex('Tuck L-Sit', '8s', 2),
                self::ex('Standing Pike Stretch', '45s', 2),
                self::ex('Seated Pike Stretch', '30s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'L-sit en anillas y soporte: la inestabilidad suma transferencia', 'blocks' => [self::block(1, 1, 5, [
                self::ex('L-Sit', '3-5s', 3),
                self::ex('Ring Support Hold', '8-12s', 3),
                self::ex('Support Hold', '20-30s', 2),
                self::ex('Standing Pike Stretch', '60s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('L-Sit', '5-8s', 3),
                self::ex('Ring Support Hold', '12-15s', 3),
                self::ex('Support Hold', '30s', 2),
                self::ex('Standing Pike Stretch', '60-90s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: L-sit 8-10 s en anillas', 'blocks' => [self::block(1, 1, 5, [
                self::ex('L-Sit', '8-10s', 4),
                self::ex('Ring Support Hold', '15s', 3),
                self::ex('Ring Dip', '5-6', 2),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('L-Sit', '3-5s', 2),
                self::ex('Ring Support Hold', '10s', 2),
                self::ex('Support Hold', '20s', 2),
                self::ex('Standing Pike Stretch', '60s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Press Base', 'goal' => 'Primer press a pino asistido: press negativo y pino a pared (dosis N8-N9)', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '2-3', 3),
                self::ex('Chest to Wall Handstand', '20-30s', 3),
                self::ex('Elevated Pike Push Up', '6-8', 2),
                self::ex('Standing Pike Stretch', '60-90s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Press Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '3', 3),
                self::ex('Chest to Wall Handstand', '30-45s', 3),
                self::ex('Elevated Pike Push Up', '8-10', 2),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Press Intensificación', 'goal' => 'Volumen: press negativo 3-5', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '3-5', 4),
                self::ex('Chest to Wall Handstand', '45s', 3),
                self::ex('Elevated Pike Push Up', '10', 2),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Press Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '2', 2),
                self::ex('Chest to Wall Handstand', '20-30s', 2),
                self::ex('Elevated Pike Push Up', '5-6', 2),
                self::ex('Standing Pike Stretch', '60s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Press Base', 'goal' => 'V-sit tucked y press negativo consolidado (dosis N13)', 'blocks' => [self::block(1, 1, 5, [
                self::ex('V-Sit Hold', '3-5s', 3),
                self::ex('Pike Press Negative', '3-4', 3),
                self::ex('Wall Handstand Hold', '20-30s', 3),
                self::ex('Standing Pike Stretch', '60-90s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Press Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('V-Sit Hold', '5-6s', 3),
                self::ex('Pike Press Negative', '4', 3),
                self::ex('Wall Handstand Hold', '30-45s', 3),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Press Intensificación', 'goal' => 'Volumen: V-sit 5-8 s y press negativo 4-5', 'blocks' => [self::block(1, 1, 5, [
                self::ex('V-Sit Hold', '5-8s', 4),
                self::ex('Pike Press Negative', '4-5', 4),
                self::ex('Wall Handstand Hold', '45s', 3),
                self::ex('Standing Pike Stretch', '90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Press Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('V-Sit Hold', '3-5s', 2),
                self::ex('Pike Press Negative', '2-3', 2),
                self::ex('Wall Handstand Hold', '20-30s', 2),
                self::ex('Standing Pike Stretch', '60s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Straddle Press Base', 'goal' => 'Primeros straddle press asistidos a puntas y pino libre', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '1-3', 3),
                self::ex('Pike Press Negative', '4-5', 3),
                self::ex('Handstand Hold', '30-45s', 3),
                self::ex('Straddle Fold', '60s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Straddle Press Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '2-3', 4),
                self::ex('Pike Press Negative', '5', 3),
                self::ex('Handstand Hold', '45s', 3),
                self::ex('Straddle Fold', '60-90s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Straddle Press Intensificación', 'goal' => 'Volumen: straddle press asistido 3 reps', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '3', 4),
                self::ex('Pike Press Negative', '5-6', 3),
                self::ex('Handstand Hold', '45-60s', 3),
                self::ex('Straddle Fold', '90s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Straddle Press Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '1', 2),
                self::ex('Pike Press Negative', '3', 2),
                self::ex('Handstand Hold', '30s', 2),
                self::ex('Straddle Fold', '45-60s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Press a Pino Base', 'goal' => 'Press negativo sin cajón y straddle press con asistencia mínima (dosis N14)', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '5', 3),
                self::ex('Straddle Press Handstand', '2-3', 3),
                self::ex('Handstand Hold', '45-60s', 3),
                self::ex('Chest to Wall Handstand', '30-45s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Press a Pino Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '5-6', 4),
                self::ex('Straddle Press Handstand', '3', 4),
                self::ex('Handstand Hold', '60s', 3),
                self::ex('Chest to Wall Handstand', '45s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Press a Pino Intensificación', 'goal' => 'Volumen: press negativo 5-8', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '5-8', 4),
                self::ex('Straddle Press Handstand', '3-4', 4),
                self::ex('Handstand Hold', '60s', 4),
                self::ex('Chest to Wall Handstand', '45-60s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Press a Pino Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Pike Press Negative', '3', 2),
                self::ex('Straddle Press Handstand', '1-2', 2),
                self::ex('Handstand Hold', '30-45s', 2),
                self::ex('Chest to Wall Handstand', '30s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Dominio Base', 'goal' => 'Straddle press limpio y transferencia al pino (dosis N13-N14 de Olimpo)', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '1-3', 3),
                self::ex('Pike Press to Handstand', '1-2', 3),
                self::ex('Handstand Hold', '60s', 3),
                self::ex('V-Sit Hold', '15s', 2),
            ], 5)]],
            'progression' => ['name' => 'Sesión D: Dominio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '2-3', 4),
                self::ex('Pike Press to Handstand', '1-2', 3),
                self::ex('Handstand Hold', '60s', 3),
                self::ex('V-Sit Hold', '15-20s', 2),
            ], 5)]],
            'intensification' => ['name' => 'Sesión D: Dominio Intensificación', 'goal' => 'Pico: straddle press 3 reps limpias', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '3', 4),
                self::ex('Pike Press to Handstand', '2-3', 3),
                self::ex('Handstand Hold', '60-90s', 3),
                self::ex('V-Sit Hold', '20s', 2),
            ], 5)]],
            'deload' => ['name' => 'Sesión D: Dominio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [
                self::ex('Straddle Press Handstand', '1', 2),
                self::ex('Pike Press to Handstand', '1', 2),
                self::ex('Handstand Hold', '45s', 2),
                self::ex('V-Sit Hold', '10s', 2),
            ], 5)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
