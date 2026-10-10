<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Handstand Balance Mastery v4 (8 niveles).
 * Especialización del Master v4: equilibrio de pared de N4-N5, handstand
 * libre de N10-N12 y dominio de N16; la pirueta (caída segura) va primero.
 * Estructura: 4 sesiones por fase (A=Strength empuje, B=Equilibrio handstand,
 * C=Prehab muñeca-hombro, D=Skill y movilidad). Fases: base, progression,
 * intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a muñeca y hombro:
 * - Los holds de handstand van con descanso completo, nunca en circuito.
 * - La pirueta (caída segura) se domina ANTES de sostener el FS HS lejos de la pared.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de handstand en frío: muñeca y hombro calientes antes de cada serie.
 * - Molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.
 */
class HandstandBalanceV4Blueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Hombro y Muñeca', 'title' => 'Base de Hombro y Muñeca', 'description' => 'Preparar hombro, muñeca y empuje vertical antes de invertirse', 'objective' => 'Wall slides y dislocates sin dolor, muñecas tolerantes en extensión y pike push-ups con técnica', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push Up(10), Wall Slide(10)', 'skillFocus' => 'Wall Slide, Wrist Prayer, Pike Push Up'],
            2 => ['name' => 'Nivel 2: Chest-to-Wall', 'title' => 'Chest-to-Wall', 'description' => 'Primer chest-to-wall handstand: 30-45 s (dosis N4/N5 del Master)', 'objective' => 'Sostener el chest-to-wall 30-45 s con línea y hombros activos', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push Up(10), Wall Slide(10)', 'skillFocus' => 'Chest to Wall Handstand, Wall Slide, Wrist Push-Up'],
            3 => ['name' => 'Nivel 3: Wall Handstand Sólido', 'title' => 'Wall Handstand Sólido', 'description' => 'Handstand de pared sólido 45-60 s con equilibrio de dedos (dosis N4/N5 del Master)', 'objective' => 'Sostener el wall handstand 45-60 s y corregir el equilibrio con los dedos', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Chest to Wall Handstand(30s), Standard Push Up(15), Wrist Prayer(30s)', 'skillFocus' => 'Wall Handstand Hold, Chest to Wall Handstand, Hollow Body Hold'],
            4 => ['name' => 'Nivel 4: Equilibrio en Pared', 'title' => 'Equilibrio en Pared', 'description' => 'Separaciones táctiles de la pared: 60-90 s y primer handstand push-up', 'objective' => 'Wall handstand 60-90 s con separaciones cortas y primer HSPU', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Wall Handstand Hold(45s), Pike Push Up(5), Wrist Prayer(30s)', 'skillFocus' => 'Wall Handstand Hold 60-90s, Chest to Wall Handstand, Handstand Push Up'],
            5 => ['name' => 'Nivel 5: Pirueta y Caída', 'title' => 'Pirueta y Caída', 'description' => 'Pirueta de caída dominada y primer freestanding handstand 5-10 s (hito N10 del Master)', 'objective' => 'Caer siempre con pirueta controlada y sostener el FS HS 5-10 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Wall Handstand Hold(60s), Handstand Push Up(1), Hollow Body Hold(30s)', 'skillFocus' => 'Freestanding Handstand, Handstand Bail Out, Wall Handstand Hold'],
            6 => ['name' => 'Nivel 6: Handstand Libre', 'title' => 'Handstand Libre', 'description' => 'Freestanding handstand 10-15 s (hito de 15 s del N11/N12 del Master)', 'objective' => 'Sostener el FS HS 10-15 s con entradas consistentes y HSPU 5-8', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Freestanding Handstand(5s), Wall Handstand Hold(90s), Handstand Push Up(3)', 'skillFocus' => 'Freestanding Handstand 10-15s, Handstand Push Up, Kick-up to Handstand'],
            7 => ['name' => 'Nivel 7: Freestanding Sólido', 'title' => 'Freestanding Sólido', 'description' => 'FS HS 15-30 s con entradas por press y pirueta; variedades de shapes', 'objective' => 'Sostener el FS HS 15-30 s y entrar por press o kick-up con consistencia', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Freestanding Handstand(15s), Handstand Push Up(5), Elevated Pike Push Up(5)', 'skillFocus' => 'Freestanding Handstand 15-30s, Handstand Push Up, Freestanding Handstand Push Up'],
            8 => ['name' => 'Nivel 8: Dominio del Handstand', 'title' => 'Dominio del Handstand', 'description' => 'FS HS 30-45 s (N16 del Master): shapes, caminata y dominio completo del equilibrio', 'objective' => 'Sostener el FS HS 30-45 s con shapes (tuck/straddle pike) y caminata', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Freestanding Handstand(20s), Handstand Push Up(8), Freestanding Handstand(30s)', 'skillFocus' => 'Freestanding Handstand 30-45s, Handstand Walk, Handstand Push Up'],
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
    // SESSION A — STRENGTH (Empuje vertical)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje de base: el hombro fuerte sostiene el handstand', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Pike Push Up', '4-6', 3), self::ex('Wall Slide', '10-12', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de empuje', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Pike Push Up', '5-6', 3), self::ex('Wall Slide', '12-15', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 4), self::ex('Pike Push Up', '6', 4), self::ex('Wall Slide', '15', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Pike Push Up', '4', 2), self::ex('Wall Slide', '10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje vertical: pike push-ups para el chest-to-wall', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Pike Push Up', '5-8', 3), self::ex('Decline Push Up', '8-10', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 3), self::ex('Pike Push Up', '6-8', 3), self::ex('Decline Push Up', '10-12', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 4), self::ex('Pike Push Up', '8', 4), self::ex('Decline Push Up', '12', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Pike Push Up', '4-5', 2), self::ex('Decline Push Up', '6-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje vertical elevado: la base del HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 3), self::ex('Elevated Pike Push Up', '4-6', 3), self::ex('Decline Push Up', '10-12', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 3), self::ex('Elevated Pike Push Up', '5-8', 3), self::ex('Decline Push Up', '12', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '18-20', 4), self::ex('Elevated Pike Push Up', '6-8', 4), self::ex('Decline Push Up', '12-15', 3)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10', 2), self::ex('Elevated Pike Push Up', '4-5', 2), self::ex('Decline Push Up', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Primer handstand push-up contra la pared', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '5-8', 3), self::ex('Handstand Push Up', '1', 3), self::ex('Decline Push Up', '10-12', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '8', 3), self::ex('Handstand Push Up', '1-2', 3), self::ex('Decline Push Up', '12-15', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen: 2-3 HSPU y pike elevado', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '8-10', 4), self::ex('Handstand Push Up', '2-3', 4), self::ex('Decline Push Up', '15', 2)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '5', 2), self::ex('Handstand Push Up', '1', 2), self::ex('Decline Push Up', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'HSPU con volumen: el empuje vertical acompaña al equilibrio libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '2-3', 3), self::ex('Elevated Pike Push Up', '6-8', 3), self::ex('Wide Push Up', '12-15', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '3-4', 3), self::ex('Elevated Pike Push Up', '8-10', 3), self::ex('Wide Push Up', '15', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen: 5 HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '4-5', 4), self::ex('Elevated Pike Push Up', '10', 4), self::ex('Wide Push Up', '15-18', 2)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '2', 2), self::ex('Elevated Pike Push Up', '5-6', 2), self::ex('Wide Push Up', '10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'HSPU 5-8 y primeras freestanding HSPU asistidas', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '4-6', 3), self::ex('Strict Handstand Push Up', '1-2', 3), self::ex('Elevated Pike Push Up', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '5-8', 3), self::ex('Strict Handstand Push Up', '2-3', 3), self::ex('Elevated Pike Push Up', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen: 8 HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '6-8', 4), self::ex('Strict Handstand Push Up', '3', 3), self::ex('Elevated Pike Push Up', '10-12', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '3', 2), self::ex('Strict Handstand Push Up', '1', 2), self::ex('Elevated Pike Push Up', '6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'HSPU 8-10: el empuje elite acompaña al FS HS sólido', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '6-8', 3), self::ex('Freestanding Handstand Push Up', '1', 3), self::ex('Elevated Pike Push Up', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '8-10', 3), self::ex('Freestanding Handstand Push Up', '1-2', 3), self::ex('Elevated Pike Push Up', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen: 10 HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '8-10', 4), self::ex('Freestanding Handstand Push Up', '2', 3), self::ex('Elevated Pike Push Up', '10-12', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '4-5', 2), self::ex('Freestanding Handstand Push Up', '1', 2), self::ex('Elevated Pike Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje elite: HSPU con volumen y variedades', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '8-10', 3), self::ex('Strict Handstand Push Up', '3-5', 3), self::ex('Elevated Pike Push Up', '10', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '10-12', 3), self::ex('Strict Handstand Push Up', '5', 3), self::ex('Elevated Pike Push Up', '10-12', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: 10-15 HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '10-15', 4), self::ex('Strict Handstand Push Up', '5-8', 4), self::ex('Elevated Pike Push Up', '12-15', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '5', 2), self::ex('Strict Handstand Push Up', '2-3', 2), self::ex('Elevated Pike Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — EQUILIBRIO HANDSTAND (progresión principal)
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
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'Primeros pino de pared: línea y respiración invertida', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '15-20s', 3), self::ex('Wall Walk', '2-3', 2), self::ex('Hollow Body Hold', '15-20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Aumentar tiempo de pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '20-30s', 3), self::ex('Wall Walk', '3', 2), self::ex('Hollow Body Hold', '20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: 30 s de pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '25-30s', 4), self::ex('Wall Walk', '3-4', 3), self::ex('Hollow Body Hold', '25s', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '15s', 2), self::ex('Wall Walk', '2', 2), self::ex('Hollow Body Hold', '15s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'Chest-to-wall 30-45 s (dosis N4/N5 del Master): la postura correcta', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Wall Handstand', '20-30s', 3), self::ex('Wall Handstand Hold', '20-30s', 2), self::ex('Hollow Body Hold', '20-30s', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Wall Handstand', '30-40s', 3), self::ex('Wall Handstand Hold', '30s', 2), self::ex('Hollow Body Hold', '30s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: 45 s chest-to-wall', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Wall Handstand', '30-45s', 4), self::ex('Wall Handstand Hold', '30-40s', 2), self::ex('Hollow Body Hold', '30-45s', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Chest to Wall Handstand', '20s', 2), self::ex('Wall Handstand Hold', '20s', 2), self::ex('Hollow Body Hold', '20s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'Wall handstand 45-60 s con correcciones de dedos (dosis N4/N5 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '30-45s', 3), self::ex('Chest to Wall Handstand', '30s', 2), self::ex('Hollow Body Hold', '30s', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '45-60s', 3), self::ex('Chest to Wall Handstand', '30-45s', 2), self::ex('Hollow Body Hold', '30-45s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: 60 s de pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '45-60s', 4), self::ex('Chest to Wall Handstand', '45s', 2), self::ex('Hollow Body Hold', '45s', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '30s', 2), self::ex('Chest to Wall Handstand', '20s', 2), self::ex('Hollow Body Hold', '20-30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'Separaciones táctiles de la pared: 60-90 s', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '45-60s', 3), self::ex('Handstand Shoulder Taps', '6-8', 2), self::ex('Chest to Wall Handstand', '30-45s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '60-75s', 3), self::ex('Handstand Shoulder Taps', '8-10', 2), self::ex('Chest to Wall Handstand', '45s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: 90 s de pared con separaciones', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '60-90s', 4), self::ex('Handstand Shoulder Taps', '10-12', 3), self::ex('Chest to Wall Handstand', '45-60s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Wall Handstand Hold', '30-45s', 2), self::ex('Handstand Shoulder Taps', '6', 2), self::ex('Chest to Wall Handstand', '30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'Kick-ups y piruetas: el FS HS 5-10 s llega solo si la caída está dominada', 'blocks' => [self::block(1, 1, 60, [self::ex('Kick-up to Handstand', '3-5', 4), self::ex('Handstand Bail Out', '3-5', 3), self::ex('Freestanding Handstand', '3-5s', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión: entradas consistentes', 'blocks' => [self::block(1, 1, 60, [self::ex('Kick-up to Handstand', '5', 4), self::ex('Handstand Bail Out', '4-5', 3), self::ex('Freestanding Handstand', '5-8s', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: FS HS 10 s', 'blocks' => [self::block(1, 1, 60, [self::ex('Kick-up to Handstand', '5-8', 5), self::ex('Handstand Bail Out', '5', 3), self::ex('Freestanding Handstand', '8-10s', 4)], 180)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Kick-up to Handstand', '3', 2), self::ex('Handstand Bail Out', '3', 2), self::ex('Freestanding Handstand', '3-5s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'FS HS 10-15 s (hito 15 s del N11/N12 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '10-15s', 3), self::ex('Kick-up to Handstand', '5', 3), self::ex('Handstand Tuck Hold', '10-15s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '12-15s', 3), self::ex('Kick-up to Handstand', '5-8', 3), self::ex('Handstand Tuck Hold', '15s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: 15 s libres', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '15s', 4), self::ex('Kick-up to Handstand', '8', 3), self::ex('Handstand Tuck Hold', '15-20s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '8-10s', 2), self::ex('Kick-up to Handstand', '3', 2), self::ex('Handstand Tuck Hold', '10s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'FS HS 15-30 s y entradas por press', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '15-20s', 3), self::ex('Kick-up to Handstand', '5', 3), self::ex('Handstand Straddle Hold', '10-15s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '20-30s', 3), self::ex('Straddle Press Handstand', '1-2', 3), self::ex('Handstand Straddle Hold', '15-20s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Volumen: 30 s libres', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '20-30s', 4), self::ex('Straddle Press Handstand', '2-3', 3), self::ex('Handstand Straddle Hold', '20s', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '10-15s', 2), self::ex('Straddle Press Handstand', '1', 2), self::ex('Handstand Straddle Hold', '10s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Equilibrio Base', 'goal' => 'FS HS 30-45 s (N16 del Master) con shapes y caminata', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '30-45s', 3), self::ex('Handstand Walk', '5-10m', 3), self::ex('Handstand Tuck Hold', '15s', 2)], 180)]],
            'progression' => ['name' => 'Sesión B: Equilibrio Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '30-45s', 3), self::ex('Handstand Walk', '10m', 3), self::ex('Handstand Straddle Hold', '15-20s', 2)], 180)]],
            'intensification' => ['name' => 'Sesión B: Equilibrio Intensificación', 'goal' => 'Pico: 45 s libres y caminata', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '45s', 4), self::ex('Handstand Walk', '10-15m', 4), self::ex('Handstand Straddle Hold', '20-30s', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Equilibrio Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand', '15-20s', 2), self::ex('Handstand Walk', '5m', 2), self::ex('Handstand Tuck Hold', '10s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (muñeca y hombro)
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
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca y hombro: la condición previa de todo handstand', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20-30s', 2), self::ex('Wall Slide', '10-12', 2), self::ex('Shoulder Dislocate', '8-10', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar rango y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wall Slide', '12-15', 2), self::ex('Shoulder Dislocate', '10-12', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wall Slide', '15', 3), self::ex('Shoulder Dislocate', '12-15', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wall Slide', '10', 2), self::ex('Shoulder Dislocate', '8', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca tolerante en extensión para el chest-to-wall', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '5-6', 2), self::ex('Wrist Curl', '12-15', 2), self::ex('Band Pull-Apart', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wrist Push-Up', '6-8', 2), self::ex('Wrist Curl', '15', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Push-Up', '8', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Band Pull-Apart', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20-30s', 2), self::ex('Wrist Push-Up', '5', 2), self::ex('Wrist Curl', '10', 2), self::ex('Band Pull-Apart', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Mantenimiento de muñeca y rotadores con más tiempo invertido', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '12-15', 2), self::ex('Face Pull', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Face Pull', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45-60s', 2), self::ex('Wrist Curl', '18', 3), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '10-12', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca y hombro fuertes: el HSPU carga mucho más', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Push-Up', '6-8', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Shoulder Dislocate', '10-12', 2), self::ex('Face Pull', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Push-Up', '8', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Push-Up', '8-10', 3), self::ex('Reverse Wrist Curl', '18', 3), self::ex('Shoulder Dislocate', '12-15', 2), self::ex('Face Pull', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Push-Up', '5-6', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Shoulder Dislocate', '8-10', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab para el FS HS: muñeca y hombro sin rechistar (regla §1.7)', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '18-20', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45-60s', 2), self::ex('Wrist Curl', '18-20', 3), self::ex('Reverse Wrist Curl', '18-20', 3), self::ex('Face Pull', '20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '10-12', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Salud de muñeca y hombro bajo el handstand libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Push-Up', '8', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Face Pull', '18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45-60s', 2), self::ex('Wrist Push-Up', '8-10', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Face Pull', '18-20', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Push-Up', '10', 3), self::ex('Reverse Wrist Curl', '20', 3), self::ex('Face Pull', '20-22', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '6', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Face Pull', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de mantenimiento: el FS HS sólido exige muñecas fiables', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45s', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Shoulder Dislocate', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Shoulder Dislocate', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Curl', '20', 3), self::ex('Reverse Wrist Curl', '20', 3), self::ex('Shoulder Dislocate', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30-45s', 2), self::ex('Wrist Curl', '10-12', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Shoulder Dislocate', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Mantenimiento profundo: el dominio vive de muñecas y hombros sanos', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '45-60s', 2), self::ex('Wrist Push-Up', '8-10', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Face Pull', '18-20', 2), self::ex('Shoulder Dislocate', '12-15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Push-Up', '10', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Face Pull', '20', 2), self::ex('Shoulder Dislocate', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '60s', 2), self::ex('Wrist Push-Up', '10-12', 3), self::ex('Reverse Wrist Curl', '20-22', 3), self::ex('Face Pull', '20-25', 2), self::ex('Shoulder Dislocate', '15-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '6', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Face Pull', '10-12', 2), self::ex('Shoulder Dislocate', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL Y MOVILIDAD (entrada, core y estiramientos)
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
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Core y movilidad: la línea del handstand se entrena en el suelo', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '15-20s', 3), self::ex('Tuck L-Sit', '8-10s', 2), self::ex('Seated Pike Stretch', '30-45s', 2), self::ex('Diaphragmatic Breathing', '5', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20-30s', 3), self::ex('Tuck L-Sit', '10s', 2), self::ex('Seated Pike Stretch', '45s', 2), self::ex('Diaphragmatic Breathing', '5', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 4), self::ex('Tuck L-Sit', '10-15s', 2), self::ex('Seated Pike Stretch', '45-60s', 2), self::ex('Diaphragmatic Breathing', '5-8', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Tuck L-Sit', '8s', 2), self::ex('Seated Pike Stretch', '30s', 2), self::ex('Diaphragmatic Breathing', '5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Core y apertura de pike para la línea invertida', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20-30s', 3), self::ex('Tuck L-Sit', '10-15s', 2), self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Tuck L-Sit', '15s', 2), self::ex('Kneeling Hip Flexor Stretch', '45s/lado', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-45s', 4), self::ex('Tuck L-Sit', '15-20s', 2), self::ex('Kneeling Hip Flexor Stretch', '45-60s/lado', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20s', 2), self::ex('Tuck L-Sit', '10s', 2), self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Core de resistencia y primera práctica de kick-up', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Kick-up to Handstand', '3-5', 3), self::ex('Seated Pike Stretch', '45s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-45s', 3), self::ex('Kick-up to Handstand', '5', 3), self::ex('Seated Pike Stretch', '60s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45s', 4), self::ex('Kick-up to Handstand', '5-8', 3), self::ex('Seated Pike Stretch', '60s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '20-30s', 2), self::ex('Kick-up to Handstand', '3', 2), self::ex('Seated Pike Stretch', '30-45s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Core y técnica de entrada: el kick-up consistente', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-45s', 3), self::ex('Kick-up to Handstand', '5', 3), self::ex('Handstand Bail Out', '3', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45s', 3), self::ex('Kick-up to Handstand', '5-8', 3), self::ex('Handstand Bail Out', '4', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 4), self::ex('Kick-up to Handstand', '8', 4), self::ex('Handstand Bail Out', '5', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Kick-up to Handstand', '3-4', 2), self::ex('Handstand Bail Out', '3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Pirueta de caída dominada en ambos lados: la libertad del FS HS', 'blocks' => [self::block(1, 1, 30, [self::ex('Handstand Bail Out', '4-5/lado', 3), self::ex('Hollow Body Hold', '30-45s', 3), self::ex('Tuck L-Sit', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Handstand Bail Out', '5/lado', 3), self::ex('Hollow Body Hold', '45s', 3), self::ex('Tuck L-Sit', '15-20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Handstand Bail Out', '5-6/lado', 4), self::ex('Hollow Body Hold', '45-60s', 4), self::ex('Tuck L-Sit', '20s', 3)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Handstand Bail Out', '3/lado', 2), self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Core y shapes: tuck y straddle en pared para el FS HS', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45s', 3), self::ex('Handstand Tuck Hold', '10-15s', 3), self::ex('Seated Pike Stretch', '45-60s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 3), self::ex('Handstand Tuck Hold', '15-20s', 3), self::ex('Seated Pike Stretch', '60s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 4), self::ex('Handstand Tuck Hold', '20s', 3), self::ex('Seated Pike Stretch', '60-90s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30s', 2), self::ex('Handstand Tuck Hold', '10s', 2), self::ex('Seated Pike Stretch', '45s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Shapes y movilidad para el FS HS sólido', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '45-60s', 3), self::ex('Handstand Straddle Hold', '10-15s', 3), self::ex('Seated Pike Stretch', '60s', 2), self::ex('Kneeling Hip Flexor Stretch', '45s/lado', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Handstand Straddle Hold', '15-20s', 3), self::ex('Seated Pike Stretch', '60-90s', 2), self::ex('Kneeling Hip Flexor Stretch', '60s/lado', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60-75s', 4), self::ex('Handstand Straddle Hold', '20-30s', 3), self::ex('Seated Pike Stretch', '90s', 2), self::ex('Kneeling Hip Flexor Stretch', '60s/lado', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '30-40s', 2), self::ex('Handstand Straddle Hold', '10s', 2), self::ex('Seated Pike Stretch', '45s', 2), self::ex('Kneeling Hip Flexor Stretch', '30s/lado', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill de dominio: shapes, caminata y respiración', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Handstand Walk Practice', '5-10m', 3), self::ex('Handstand Tuck Hold', '15s', 2), self::ex('Diaphragmatic Breathing', '8-10', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '60-75s', 3), self::ex('Handstand Walk Practice', '10m', 3), self::ex('Handstand Tuck Hold', '15-20s', 2), self::ex('Diaphragmatic Breathing', '10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '75-90s', 4), self::ex('Handstand Walk Practice', '10-15m', 4), self::ex('Handstand Tuck Hold', '20-30s', 3), self::ex('Diaphragmatic Breathing', '10-12', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 30, [self::ex('Hollow Body Hold', '40s', 2), self::ex('Handstand Walk Practice', '5m', 2), self::ex('Handstand Tuck Hold', '10-15s', 2), self::ex('Diaphragmatic Breathing', '5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
