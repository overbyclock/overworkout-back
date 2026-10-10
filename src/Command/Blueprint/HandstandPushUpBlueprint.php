<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Handstand Push-Up Mastery (8 niveles).
 * Especialización del Master v4: empuje vertical de N8-N10 (pike), N10-N11
 * (HSPU en pared) y N12 (HSPU libre), con dosis de los detalles 10-12 y 13-15.
 * Estructura: 4 sesiones por fase (A=Strength empuje, B=HSPU progresión,
 * C=Prehab muñeca-hombro, D=Equilibrio y skill handstand).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a muñeca y hombro:
 * - Las HSPU van con descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de HSPU con muñeca fría: muñeca caliente antes de cada serie.
 * - El déficit se progresa con prudencia (2,5-5 cm por bloque, no más).
 * - Molestia matinal >2/10 en muñeca u hombro = congela ese gesto un ciclo.
 */
class HandstandPushUpBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Empuje Vertical', 'title' => 'Base de Empuje Vertical', 'description' => 'Construir la base de empuje vertical con pike push-ups, elevated pikes y soportes de pared', 'objective' => 'Consolidar pike push-up y elevated pike con rango completo y ganar 30-45 s de wall handstand hold sin dolor de muñeca', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push Up(15), Pike Push Up(5)', 'skillFocus' => 'Pike Push Up, Elevated Pike Push Up, Wall Handstand Hold'],
            2 => ['name' => 'Nivel 2: Pike Pesado', 'title' => 'Pike Pesado', 'description' => 'Elevated pike en volumen y wall handstand extendido (dosis N8 del Master)', 'objective' => 'Dominar el elevated pike pesado (5-8 reps) y sostener el handstand en pared 45-60 s', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Pike Push Up(5), Elevated Pike Push Up(3), Wall Handstand Hold(30s)', 'skillFocus' => 'Elevated Pike Push Up, Wall Handstand Hold, Pike Push Up'],
            3 => ['name' => 'Nivel 3: HSPU en Pared', 'title' => 'HSPU en Pared', 'description' => 'Primeras handstand push-ups en pared: la técnica del gesto completo con negativas', 'objective' => 'Conseguir las primeras 1-3 HSPU en pared con rango controlado y chest to wall 45-60 s', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Elevated Pike Push Up(5), Wall Handstand Hold(45s)', 'skillFocus' => 'Handstand Push Up, HSPU Negative, Chest to Wall Handstand'],
            4 => ['name' => 'Nivel 4: HSPU Pared Volumen', 'title' => 'HSPU Pared Volumen', 'description' => 'Volumen de HSPU en pared y entrada al déficit (test N10 del Master: 3/5)', 'objective' => 'Llegar al test del N10 del Master: HSPU 3/5 en pared, wall handstand 60-90 s y déficit 3/5', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Handstand Push Up(1), Elevated Pike Push Up(8), Chest to Wall Handstand(45s)', 'skillFocus' => 'Handstand Push Up 3/5, Wall Handstand Hold, Deficit Handstand Push Up'],
            5 => ['name' => 'Nivel 5: HSPU Pared Sólido', 'title' => 'HSPU Pared Sólido', 'description' => 'HSPU sólidas en pared con déficit y primer handstand libre (hito N11: ×8)', 'objective' => 'Alcanzar el hito N11 del Master: HSPU ×8 en pared, déficit 5/8 y handstand libre 8-12 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Handstand Push Up(3), Wall Handstand Hold(60s)', 'skillFocus' => 'Handstand Push Up ×8, Deficit Handstand Push Up, Freestanding Handstand'],
            6 => ['name' => 'Nivel 6: HSPU Libre Entrada', 'title' => 'HSPU Libre Entrada', 'description' => 'El pino libre se estabiliza (hito N12: 15 s) y empiezan los despegues de HSPU libre', 'objective' => 'Sostener el handstand libre 15 s, mantener 8-10 HSPU en pared y conseguir los primeros despegues de HSPU libre', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Handstand Push Up(5), Freestanding Handstand(8s)', 'skillFocus' => 'Freestanding Handstand 15s, Despegues HSPU libre, Handstand Push Up'],
            7 => ['name' => 'Nivel 7: HSPU Libre', 'title' => 'HSPU Libre', 'description' => 'HSPU libres consolidadas y press a pino por negativas (dosis N14 del Master)', 'objective' => 'Dominar 1-3 HSPU libres estables, pino libre 20-30 s y press a pino con negativas 3/5', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Freestanding Handstand(15s), Handstand Push Up(8)', 'skillFocus' => 'Freestanding Handstand Push Up, Pike Press Negative, Freestanding Handstand'],
            8 => ['name' => 'Nivel 8: Dominio', 'title' => 'Dominio', 'description' => 'El HSPU libre como dominio: 3-5 reps, press a pino y pino libre 30-45 s (dosis Olimpo)', 'objective' => 'HSPU libre 3/5, pino libre 30-45 s y press a pino consolidado, con HSPU en pared a 10/15', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Freestanding Handstand Push Up(1), Freestanding Handstand(20s)', 'skillFocus' => 'Freestanding Handstand Push Up, Handstand Press, Freestanding Handstand'],
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
    // SESSION A — STRENGTH (Empuje horizontal y vertical de base)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje horizontal sólido y primer pike con énfasis en línea de empuje', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Diamond Push Up', '6-8', 2), self::ex('Pike Push Up', '4-6', 3), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar volumen de empuje', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Diamond Push Up', '8-10', 3), self::ex('Pike Push Up', '6-8', 3), self::ex('Hollow Body Hold', '20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base (entrada del programa: 15 push-ups + 5 pikes)', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 4), self::ex('Diamond Push Up', '10-12', 3), self::ex('Pike Push Up', '8', 4), self::ex('Hollow Body Hold', '25s', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Diamond Push Up', '6', 2), self::ex('Pike Push Up', '5', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Empuje pesado hacia el elevated pike (dosis N8 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15', 3), self::ex('Decline Push Up', '8-10', 3), self::ex('Pike Push Up', '8', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '18', 3), self::ex('Decline Push Up', '10-12', 3), self::ex('Pike Push Up', '10', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '20', 4), self::ex('Decline Push Up', '12-15', 4), self::ex('Pike Push Up', '12', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10', 2), self::ex('Decline Push Up', '8', 2), self::ex('Pike Push Up', '6-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Primeras HSPU completas con técnica impecable (dosis N10 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '1-2', 4), self::ex('Elevated Pike Push Up', '8', 3), self::ex('Pseudo Planche Push Up', '5-6', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '2', 4), self::ex('Elevated Pike Push Up', '10', 3), self::ex('Pseudo Planche Push Up', '6', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen: 3 HSPU en pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '3', 5), self::ex('Elevated Pike Push Up', '12', 3), self::ex('Pseudo Planche Push Up', '8', 3)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '1-2', 2), self::ex('Elevated Pike Push Up', '6', 2), self::ex('Pike Push Up', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Volumen de HSPU en pared con rango completo (marcas de test N10)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '2-3', 4), self::ex('Deficit Handstand Push Up', '2-3', 3), self::ex('Wide Push Up', '12-15', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión: entra el déficit con prudencia', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '3-4', 4), self::ex('Deficit Handstand Push Up', '3', 3), self::ex('Wide Push Up', '15', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen: marcas de test (HSPU 3/5, déficit 3/5)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '4-5', 5), self::ex('Deficit Handstand Push Up', '3-4', 4), self::ex('Wide Push Up', '15-18', 2)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '2', 2), self::ex('Pike Push Up', '8-10', 2), self::ex('Wide Push Up', '10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'HSPU pesadas en pared y empuje unilateral (camino al hito ×8)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '5', 4), self::ex('One Arm Push Up', '2-3/lado', 3), self::ex('Decline Push Up', '12-15', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '6-7', 4), self::ex('One Arm Push Up', '3/lado', 3), self::ex('Decline Push Up', '15', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Hito: HSPU ×8 en pared (prerequisito del HSPU libre)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '8', 4), self::ex('One Arm Push Up', '3/lado', 4), self::ex('Decline Push Up', '15-18', 2)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '4', 2), self::ex('One Arm Push Up', '2/lado', 2), self::ex('Standard Push Up', '10-12', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Despegues de HSPU libre frescos y volumen de HSPU en pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1', 5), self::ex('Handstand Push Up', '6-8', 3), self::ex('Archer Push Up', '3-4/lado', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1-2', 5), self::ex('Handstand Push Up', '8-10', 3), self::ex('Archer Push Up', '4-5/lado', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: 2 despegues limpios y 10 HSPU en pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '2', 5), self::ex('Handstand Push Up', '10', 4), self::ex('Archer Push Up', '5/lado', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1', 2), self::ex('Handstand Push Up', '5', 2), self::ex('Pike Push Up', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'HSPU libre con volumen y déficit pesado en pared', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1-2', 5), self::ex('Deficit Handstand Push Up', '8', 3), self::ex('Pike Handstand Push Up', '5-6', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '2', 5), self::ex('Deficit Handstand Push Up', '10', 3), self::ex('Pike Handstand Push Up', '6-8', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: 3 HSPU libres y déficit 12', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '3', 5), self::ex('Deficit Handstand Push Up', '12', 4), self::ex('Pike Handstand Push Up', '8', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1', 2), self::ex('Deficit Handstand Push Up', '6-8', 2), self::ex('Pike Push Up', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'HSPU libre en volumen y press a pino asistido (dominio)', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '2-3', 5), self::ex('Handstand Press', '1-2', 3), self::ex('Handstand Push Up', '10', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '3', 5), self::ex('Handstand Press', '2-3', 3), self::ex('Handstand Push Up', '12', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: 5 HSPU libres y press consolidado', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '4-5', 5), self::ex('Handstand Press', '3', 4), self::ex('Handstand Push Up', '15', 3), self::ex('One-Arm Handstand Push Up', '1', 2)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1-2', 2), self::ex('Pike Press Negative', '3', 2), self::ex('Handstand Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — HSPU (progresión vertical específica)
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
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'Pike push-up como gesto principal: rango completo y proyección de hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '4-6', 3), self::ex('Pike Push Up', '5', 2), self::ex('Hollow Body Hold', '15s', 2), self::ex('Hanging Leg Raise', '5-6', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Aumentar reps y amplitud del pike', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '6-8', 3), self::ex('Pike Push Up', '8', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Hanging Leg Raise', '6-8', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Volumen: pike 8 reps y primer elevated pike', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '8', 4), self::ex('Elevated Pike Push Up', '3-5', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Hanging Leg Raise', '8', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '5', 2), self::ex('Hollow Body Hold', '15s', 2), self::ex('Hanging Leg Raise', '5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'Elevated pike como ejercicio estrella (dosis N8 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '5-6', 3), self::ex('Pike Push Up', '8-10', 3), self::ex('Hollow Body Hold', '20s', 2), self::ex('Hanging Leg Raise', '6-8', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '6-8', 3), self::ex('Pike Push Up', '10-12', 3), self::ex('Hollow Body Hold', '25s', 2), self::ex('Hanging Leg Raise', '8-10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Volumen: elevated pike 8 reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '8', 4), self::ex('Pike Push Up', '12', 3), self::ex('Hollow Body Hold', '30s', 2), self::ex('Hanging Leg Raise', '10', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '5', 2), self::ex('Pike Push Up', '6-8', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'Negativas de HSPU y primeras completas en pared (dosis N10 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '1', 4), self::ex('HSPU Negative', '3', 4), self::ex('Elevated Pike Push Up', '8-10', 3)], 120)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '2', 4), self::ex('HSPU Negative', '3', 4), self::ex('Elevated Pike Push Up', '10', 3)], 120)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Volumen: 3 HSPU y chest to wall 60 s', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '3', 5), self::ex('Elevated Pike Push Up', '12', 3), self::ex('Wall Handstand Hold', '45-60s', 3)], 150)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('HSPU Negative', '2', 2), self::ex('Elevated Pike Push Up', '6-8', 2), self::ex('Wall Handstand Hold', '30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'Volumen de HSPU en pared con descanso completo (regla §1.7)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '3', 4), self::ex('Handstand Push Up', '2', 3), self::ex('Deficit Handstand Push Up', '3', 3), self::ex('Wall Handstand Hold', '45-60s', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '3-4', 4), self::ex('Handstand Push Up', '3', 3), self::ex('Deficit Handstand Push Up', '4', 3), self::ex('Wall Handstand Hold', '60s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Volumen: marcas de test (HSPU 3/5, wall HS 90 s)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '5', 4), self::ex('Handstand Push Up', '3-4', 3), self::ex('Deficit Handstand Push Up', '5', 3), self::ex('Wall Handstand Hold', '60-90s', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '3', 2), self::ex('Pike Push Up', '8-10', 2), self::ex('Wall Handstand Hold', '45s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'HSPU en pared hacia el hito ×8 y déficit consolidado (dosis N11 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '5', 4), self::ex('Deficit Handstand Push Up', '3-5', 3), self::ex('Freestanding Handstand', '5-8s', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '6-7', 4), self::ex('Deficit Handstand Push Up', '5', 3), self::ex('Freestanding Handstand', '8-10s', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Hito: HSPU ×8 y déficit 5/8', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '8', 4), self::ex('Deficit Handstand Push Up', '6-8', 4), self::ex('Freestanding Handstand', '10-12s', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '4', 2), self::ex('Wall Handstand Hold', '45s', 2), self::ex('Freestanding Handstand', '5s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'Despegues de HSPU libre con roce mínimo y press asistido (dosis N12 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1', 5), self::ex('Handstand Push Up', '8', 3), self::ex('Pike Press Negative', '2', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1-2', 5), self::ex('Handstand Push Up', '8-10', 3), self::ex('Pike Press Negative', '2-3', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Pico: 2 HSPU libres por despegue y press asistido 3 negativas', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '2', 5), self::ex('Handstand Push Up', '10', 4), self::ex('Pike Press Negative', '3', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Handstand Push Up', '5', 2), self::ex('Pike Press Negative', '2', 2), self::ex('Wall Handstand Hold', '45s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'HSPU libre estable y negativas de press a pino (dosis N14 del Master)', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1', 5), self::ex('Pike Press Negative', '3', 4), self::ex('Handstand Push Up', '10', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '2', 5), self::ex('Pike Press Negative', '4', 4), self::ex('Handstand Push Up', '12', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Pico: 3 HSPU libres y press negativo 3/5', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '3', 5), self::ex('Pike Press Negative', '5', 4), self::ex('Handstand Push Up', '12-15', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1', 2), self::ex('Elevated Pike Push Up', '5', 2), self::ex('Wall Handstand Hold', '45s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: HSPU Base', 'goal' => 'HSPU libre con volumen real y press a pino (dosis Olimpo)', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '2-3', 5), self::ex('Handstand Press', '1-2', 3), self::ex('Deficit Handstand Push Up', '10', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: HSPU Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '3', 5), self::ex('Handstand Press', '2', 3), self::ex('Deficit Handstand Push Up', '12', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: HSPU Intensificación', 'goal' => 'Pico: 5 HSPU libres y press a pino consolidado', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '5', 5), self::ex('Handstand Press', '2-3', 4), self::ex('Deficit Handstand Push Up', '12-15', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: HSPU Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Freestanding Handstand Push Up', '1-2', 2), self::ex('Handstand Push Up', '6-8', 2), self::ex('Pike Press Negative', '3', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (muñeca, manguito rotador y salud de hombro)
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
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Muñeca preparada para el peso del pike (dosis N1 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '12', 2), self::ex('Reverse Wrist Curl', '12', 2), self::ex('Wall Slide', '8-10', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar rango y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '12-15', 2), self::ex('Reverse Wrist Curl', '12-15', 2), self::ex('Wall Slide', '10-12', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de muñeca y activación escapular', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Wall Slide', '12', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Wall Slide', '8', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Wrist push-ups y manguito rotador para el elevated pike', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '5', 2), self::ex('Face Pull', '12', 2), self::ex('External Rotation', '10/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '6-8', 2), self::ex('Face Pull', '15', 2), self::ex('External Rotation', '12/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Push-Up', '8', 2), self::ex('Face Pull', '15', 3), self::ex('External Rotation', '12/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Push-Up', '5', 2), self::ex('Face Pull', '10', 2), self::ex('External Rotation', '8/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Hombro sano para las primeras HSPU: manguito y muñeca', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 3), self::ex('External Rotation', '10-12/lado', 2), self::ex('Wall Slide', '10-12', 2), self::ex('Wrist Prayer', '30s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15', 3), self::ex('External Rotation', '12/lado', 2), self::ex('Wall Slide', '12', 2), self::ex('Wrist Curl', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('External Rotation', '12-15/lado', 2), self::ex('Shoulder Dislocate', '10', 2), self::ex('Wrist Curl', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10', 2), self::ex('External Rotation', '8-10/lado', 2), self::ex('Wall Slide', '8', 2), self::ex('Wrist Prayer', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Protección de hombro bajo volumen de HSPU y entrada al déficit', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15', 3), self::ex('External Rotation', '12/lado', 2), self::ex('Shoulder Dislocate', '10', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('External Rotation', '12-15/lado', 2), self::ex('Shoulder Dislocate', '12', 2), self::ex('Cuban Rotation', '8', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('External Rotation', '15/lado', 2), self::ex('Cuban Rotation', '8-10', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('External Rotation', '10/lado', 2), self::ex('Wall Slide', '10', 2), self::ex('Wrist Prayer', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Manguito y muñeca para el hito HSPU ×8 (regla §1.7)', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15', 3), self::ex('External Rotation', '12/lado', 2), self::ex('Cuban Rotation', '8', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('External Rotation', '12-15/lado', 2), self::ex('Cuban Rotation', '8-10', 2), self::ex('Wrist Curl', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18', 3), self::ex('External Rotation', '15/lado', 2), self::ex('Cuban Rotation', '10', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('External Rotation', '10/lado', 2), self::ex('Wrist Prayer', '20s', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Equilibrio de hombro con despegues: manguito fuerte y muñeca cuidada', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15', 3), self::ex('External Rotation', '12-15/lado', 2), self::ex('Cuban Rotation', '8', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('External Rotation', '15/lado', 2), self::ex('Cuban Rotation', '8-10', 2), self::ex('Shoulder Dislocate', '12', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('External Rotation', '15/lado', 3), self::ex('Cuban Rotation', '10', 2), self::ex('Wrist Curl', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('External Rotation', '10/lado', 2), self::ex('Wrist Prayer', '20s', 2), self::ex('Band Pull-Apart', '12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Manguito élite y muñeca para HSPU libre (dosis N14)', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('External Rotation', '12-15/lado', 2), self::ex('Cuban Rotation', '8', 2), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18', 3), self::ex('External Rotation', '15/lado', 2), self::ex('Cuban Rotation', '10', 2), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('External Rotation', '15-18/lado', 3), self::ex('Cuban Rotation', '10-12', 2), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('External Rotation', '10-12/lado', 2), self::ex('Wrist Prayer', '20s', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Resistencia de manguito y muñeca al nivel del Olimpo', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('External Rotation', '12-15/lado', 2), self::ex('Cuban Rotation', '8-10', 2), self::ex('Shoulder Dislocate', '10-12', 2), self::ex('Wrist Curl', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('External Rotation', '15/lado', 3), self::ex('Cuban Rotation', '10', 2), self::ex('Shoulder Dislocate', '12', 2), self::ex('Wrist Curl', '18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20', 3), self::ex('External Rotation', '15-18/lado', 3), self::ex('Cuban Rotation', '10-12', 2), self::ex('Shoulder Dislocate', '12-15', 2), self::ex('Wrist Curl', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12', 2), self::ex('External Rotation', '10-12/lado', 2), self::ex('Wrist Prayer', '20-30s', 2), self::ex('Back-of-Hand Stretch', '20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL (equilibrio de handstand)
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
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Primer wall handstand hold y técnica de línea (cabeza neutra, hombros proyectados)', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '15-20s', 3), self::ex('Wall Slide', '8-10', 2), self::ex('Fingertip Hold', '10s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar tiempo en pared', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '20-30s', 3), self::ex('Wall Slide', '10-12', 2), self::ex('Fingertip Hold', '15s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 30-45 s de pared', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '30-45s', 3), self::ex('Wall Slide', '12', 2), self::ex('Fingertip Hold', '15-20s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '15-20s', 2), self::ex('Wall Slide', '8', 2), self::ex('Wrist Prayer', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Wall handstand extendido (20-45 s) y primeras entradas con kick-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '20-30s', 3), self::ex('Kick-up to Handstand', '3-5', 2), self::ex('Chest to Wall Handstand', '15-20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '30-40s', 3), self::ex('Kick-up to Handstand', '4-5', 3), self::ex('Chest to Wall Handstand', '20-30s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 45-60 s de pared', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '45-60s', 3), self::ex('Kick-up to Handstand', '5', 3), self::ex('Chest to Wall Handstand', '30s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '20-30s', 2), self::ex('Chest to Wall Handstand', '15s', 2), self::ex('Wrist Extension Stretch', '30s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Chest to wall como referencia de línea y apoyo para las HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '30-40s', 3), self::ex('Wall Handstand Hold', '45s', 2), self::ex('Hollow Body Hold', '20-30s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '40-60s', 3), self::ex('Wall Handstand Hold', '50-60s', 2), self::ex('Hollow Body Hold', '30s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 60 s de chest to wall', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '60s', 3), self::ex('Wall Handstand Hold', '60s', 2), self::ex('Handstand Tuck Hold', '10-15s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '30-45s', 2), self::ex('Wall Slide', '8-10', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Pared sólida: wall handstand 45-60 s con línea perfecta', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '45-60s', 3), self::ex('Chest to Wall Handstand', '60s', 2), self::ex('Handstand Tuck Hold', '12-15s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '60-75s', 3), self::ex('Chest to Wall Handstand', '60-75s', 2), self::ex('Handstand Tuck Hold', '15s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 90 s de pared acumulada', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '75-90s', 3), self::ex('Chest to Wall Handstand', '75-90s', 2), self::ex('Handstand Shoulder Taps', '6-8', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '45s', 2), self::ex('Chest to Wall Handstand', '45s', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Primer handstand libre: despegues cortos contra pared suave', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '5-8s', 4), self::ex('Kick-up to Handstand', '3-5', 2), self::ex('Wall Handstand Hold', '60s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '8-10s', 4), self::ex('Kick-up to Handstand', '4-5', 3), self::ex('Wall Handstand Hold', '60-75s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen: 8-12 s de equilibrio libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10-12s', 5), self::ex('Kick-up to Handstand', '5', 3), self::ex('Wall Handstand Hold', '75-90s', 2)], 150)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '5s', 2), self::ex('Wall Handstand Hold', '45s', 2), self::ex('Handstand Tuck Hold', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Hito del nivel: pino libre 15 s (dosis N12 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10-12s', 4), self::ex('Freestanding Handstand', '8-10s', 3), self::ex('Handstand Walk Practice', '5-8s', 2)], 150)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión hacia el hito', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '12-15s', 4), self::ex('Freestanding Handstand', '10s', 3), self::ex('Handstand Walk Practice', '8-10s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Hito: 15 s de pino libre consolidados', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '15s', 5), self::ex('Handstand Walk Practice', '10s', 2), self::ex('Handstand Shoulder Taps', '6-8', 2)], 150)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '8-10s', 2), self::ex('Kick-up to Handstand', '3', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Pino libre 20 s y desplazamientos cortos', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '15-20s', 4), self::ex('Handstand Walk', '5-8s', 3), self::ex('Handstand Shoulder Taps', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '20-25s', 4), self::ex('Handstand Walk', '8-10s', 3), self::ex('Handstand Shoulder Taps', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Pico: 30 s de pino libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '25-30s', 5), self::ex('Handstand Walk', '10-12s', 3), self::ex('Handstand Straddle Hold', '15-20s', 2)], 180)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10-15s', 2), self::ex('Handstand Walk Practice', '5s', 2), self::ex('Kick-up to Handstand', '3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Pino libre 30 s y caminata de handstand (dosis Olimpo)', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '25-30s', 4), self::ex('Handstand Walk', '10-12s', 3), self::ex('Handstand Straddle Hold', '20s', 2)], 150)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '30-40s', 4), self::ex('Handstand Walk', '12-15s', 3), self::ex('Handstand Straddle Hold', '20-30s', 2)], 150)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Pico: 45 s de pino libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '40-45s', 5), self::ex('Handstand Walk', '15-20s', 3), self::ex('Handstand Straddle Hold', '30s', 2)], 180)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '15-20s', 2), self::ex('Handstand Walk Practice', '5-8s', 2), self::ex('Hollow Body Hold', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
