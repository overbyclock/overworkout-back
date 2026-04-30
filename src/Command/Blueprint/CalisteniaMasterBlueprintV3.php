<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint completo del programa Calistenia Master v3.0 (12 niveles).
 * Estructura: 4 sesiones por fase (A=Strength Push, B=Strength Pull, C=Circuit, D=Skill).
 * Fases: base, progression, intensification, deload.
 */
class CalisteniaMasterBlueprintV3
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Fundamentos', 'title' => 'Fundamentos', 'description' => 'Construye la base de fuerza y técnica', 'objective' => 'Desarrollar fuerza básica y patrones de movimiento correctos', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Push-ups(10), Pull-ups(5), Air Squats(20), Plank(45s), Hollow Body Hold(20s)', 'skillFocus' => null],
            2 => ['name' => 'Nivel 2: Consolidación', 'title' => 'Consolidación', 'description' => 'Consolida fundamentales e introduce primeros skills', 'objective' => 'Consolidar técnica y primeros skills estáticos', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Pull-up(5), Pike Push-up(8), Plank(60s), Wall HS(15s)', 'skillFocus' => 'Wall Handstand'],
            3 => ['name' => 'Nivel 3: Transición', 'title' => 'Transición', 'description' => 'Primer contacto con skills técnicos', 'objective' => 'Transición de principiante a intermedio', 'difficultyRating' => 3, 'color' => '#4ade80', 'requirementsSummary' => 'Chin-up(5), Diamond Push-up(10), Tuck L-Sit(10s), Wall Walk(3)', 'skillFocus' => 'L-Sit Tuck, Band Assisted Pull Up'],
            4 => ['name' => 'Nivel 4: Proyección', 'title' => 'Proyección', 'description' => 'Base de skills: planche lean, wall HS, primeros isométricos', 'objective' => 'Desarrollar proyección de hombros y base para skills', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Planche Lean(20s), Wall HS(30s), Assisted Pistol(5), Pseudo Planche PU(5)', 'skillFocus' => 'Planche Lean, Wall Handstand'],
            5 => ['name' => 'Nivel 5: Tuck Skills', 'title' => 'Tuck Skills', 'description' => 'Introducción a tuck planche y tuck front lever', 'objective' => 'Dominar skills básicos de isométricos', 'difficultyRating' => 5, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Planche(8s), Tuck FL(8s), Box Pistol(5), Pike Push-up profundo(8)', 'skillFocus' => 'Tuck Planche, Tuck Front Lever'],
            6 => ['name' => 'Nivel 6: Advanced Tuck', 'title' => 'Advanced Tuck', 'description' => 'Consolidación de skills intermedios', 'objective' => 'Cerrar brecha hacia avanzado', 'difficultyRating' => 6, 'color' => '#60a5fa', 'requirementsSummary' => 'Adv Tuck Planche(10s), Back Lever Hold(5s), Muscle-up negativa(3), Wall HS(45s)', 'skillFocus' => 'Advanced Tuck Planche, Back Lever Hold'],
            7 => ['name' => 'Nivel 7: Straddle & Pistol', 'title' => 'Straddle & Pistol', 'description' => 'Introducción a straddle planche, full FL y pistol completo', 'objective' => 'Dominar movimientos avanzados', 'difficultyRating' => 7, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Planche(3s), Full FL(5s), Pistol Squat(3), HSPU pared(3)', 'skillFocus' => 'Straddle Planche (intro), Full FL (intro), Pistol Squat'],
            8 => ['name' => 'Nivel 8: Consolidación', 'title' => 'Consolidación', 'description' => 'Consolidación de skills avanzados', 'objective' => 'Control corporal avanzado', 'difficultyRating' => 8, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Planche(10s), Full FL(10s), Pistol Squat(5), Freestanding HS(15s)', 'skillFocus' => 'Straddle Planche, Full FL, Freestanding HS'],
            9 => ['name' => 'Nivel 9: Dominio Dinámico', 'title' => 'Dominio Dinámico', 'description' => 'Muscle-up limpio, freestanding HSPU, unilateral', 'objective' => 'Preparación para élite', 'difficultyRating' => 9, 'color' => '#f472b6', 'requirementsSummary' => 'Muscle-up(3), Archer Push-up(5), Human Flag(3s), Freestanding HSPU(1)', 'skillFocus' => 'Muscle-up limpio, Freestanding HSPU'],
            10 => ['name' => 'Nivel 10: Fuerza Élite', 'title' => 'Fuerza Élite', 'description' => 'Movimientos de élite: planche push-up, weighted pull-up', 'objective' => 'Fuerza de élite', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Planche PU(3), Weighted PU +30% BW(3), Dragon Pistol(3), Freestanding HS(30s)', 'skillFocus' => 'Planche Push-up, Weighted Pull-up'],
            11 => ['name' => 'Nivel 11: Skills Completos', 'title' => 'Skills Completos', 'description' => 'Full planche, human flag, unilateral completo', 'objective' => 'Aproximación al máximo nivel', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Planche(5s), One-arm PU negativa(3), Human Flag(5s), 90° PU(3)', 'skillFocus' => 'Planche, Human Flag'],
            12 => ['name' => 'Nivel 12: Experto', 'title' => 'Experto', 'description' => 'Calistenia de élite: dominio completo', 'objective' => 'Dominar calistenia a nivel élite', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Planche(10s), Weighted PU +50% BW(3), Freestanding HSPU(3), One-arm Chin-up(1)', 'skillFocus' => 'Freestanding HSPU consolidado, One-arm Chin-up'],
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

        $rest = match (true) {
            $level <= 6 => 180,
            $level <= 9 => 240,
            default => 300,
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
    // SESSION A — STRENGTH PUSH
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
            9 => self::sessionA_L9($phase),
            10 => self::sessionA_L10($phase),
            11 => self::sessionA_L11($phase),
            12 => self::sessionA_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionA_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Aprender push-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Push Up', '8-10', 3), self::ex('Knee Push Up', '8-10', 3), self::ex('Incline Push Up', '8-10', 3), self::ex('High Plank', '20-30s', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Knee Push Up', '10-12', 3), self::ex('Incline Push Up', '10-12', 3), self::ex('Standard Push Up', '5-8', 3), self::ex('High Plank', '30-40s', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Incline Push Up', '12-15', 4), self::ex('Standard Push Up', '8-10', 4), self::ex('Knee Push Up', '12-15', 4), self::ex('High Plank', '40-50s', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Push Up', '6-8', 2), self::ex('Knee Push Up', '6-8', 2), self::ex('Incline Push Up', '6-8', 2), self::ex('High Plank', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Consolidar push-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 3), self::ex('Incline Push Up', '10-12', 3), self::ex('Diamond Push Up', '5-8', 3)], 90), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '15-20s', 3), self::ex('Pike Push Up', '5-6', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Diamond Push Up', '8-10', 3), self::ex('Incline Push Up', '12-15', 3)], 90), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '20-30s', 3), self::ex('Pike Push Up', '6-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 4), self::ex('Diamond Push Up', '10-12', 4), self::ex('Incline Push Up', '15-18', 4)], 90), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '25-35s', 4), self::ex('Pike Push Up', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '6-8', 2), self::ex('Diamond Push Up', '5-6', 2), self::ex('Incline Push Up', '8-10', 2)], 90), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '10-15s', 2), self::ex('Pike Push Up', '4-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Dominar push-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 3), self::ex('Diamond Push Up', '8-10', 3), self::ex('Wide Push Up', '8-10', 3)], 90), self::block(1, 1, 45, [self::ex('Pike Push Up', '8-10', 3), self::ex('Wall Handstand Hold', '20-30s', 3), self::ex('Wall Walk', '2-3', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Introducir declives', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '12-15', 3), self::ex('Diamond Push Up', '10-12', 3), self::ex('Decline Push Up', '5-8', 3)], 90), self::block(1, 1, 45, [self::ex('Pike Push Up', '10-12', 3), self::ex('Wall Handstand Hold', '25-35s', 3), self::ex('Wall Walk', '3-4', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '15-18', 4), self::ex('Diamond Push Up', '12-15', 4), self::ex('Decline Push Up', '8-10', 4)], 90), self::block(1, 1, 45, [self::ex('Pike Push Up', '12-15', 4), self::ex('Wall Handstand Hold', '30-40s', 4), self::ex('Wall Walk', '4-5', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 2), self::ex('Diamond Push Up', '6-8', 2), self::ex('Wide Push Up', '6-8', 2)], 90), self::block(1, 1, 45, [self::ex('Pike Push Up', '6-8', 2), self::ex('Wall Handstand Hold', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '10-12', 4), self::ex('Diamond Push Up', '8-10', 4), self::ex('Wide Push Up', '8-10', 4)], 120), self::block(1, 1, 45, [self::ex('Pseudo Planche Push Up', '5-8', 4), self::ex('Wall Handstand Hold', '20-30s', 4), self::ex('Planche Lean', '15-20s', 4)], 120)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Diamond Push Up', '10-12', 4), self::ex('Wide Push Up', '10-12', 4), self::ex('Decline Push Up', '8-10', 4)], 120), self::block(1, 1, 45, [self::ex('Pseudo Planche Push Up', '8-10', 4), self::ex('Wall Handstand Hold', '25-35s', 4), self::ex('Planche Lean', '20-25s', 4)], 120)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Diamond Push Up', '12-15', 4), self::ex('Wide Push Up', '12-15', 4), self::ex('Decline Push Up', '10-12', 4)], 120), self::block(1, 1, 45, [self::ex('Pseudo Planche Push Up', '10-12', 4), self::ex('Wall Handstand Hold', '30-40s', 4), self::ex('Planche Lean', '25-30s', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Push Up', '8-10', 3), self::ex('Diamond Push Up', '6-8', 3), self::ex('Wide Push Up', '6-8', 3)], 120), self::block(1, 1, 45, [self::ex('Pseudo Planche Push Up', '5-6', 3), self::ex('Wall Handstand Hold', '15-20s', 3), self::ex('Planche Lean', '10-15s', 3)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche', '5-8s', 4), self::ex('Pike Push Up', '8-10', 4), self::ex('Diamond Push Up', '8-10', 4), self::ex('Decline Push Up', '6-8', 4)], 120), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '25-35s', 4), self::ex('Pseudo Planche Push Up', '8-10', 4)], 120)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche', '8-10s', 4), self::ex('Pike Push Up', '10-12', 4), self::ex('Diamond Push Up', '10-12', 4), self::ex('Decline Push Up', '8-10', 4)], 120), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '30-40s', 4), self::ex('Pseudo Planche Push Up', '10-12', 4)], 120)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche', '10-12s', 5), self::ex('Pike Push Up', '12-15', 5), self::ex('Diamond Push Up', '12-15', 5), self::ex('Decline Push Up', '10-12', 5)], 120), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '35-45s', 5), self::ex('Pseudo Planche Push Up', '12-15', 5)], 120)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Planche', '5-6s', 3), self::ex('Pike Push Up', '6-8', 3), self::ex('Diamond Push Up', '6-8', 3), self::ex('Decline Push Up', '5-6', 3)], 120), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '20-25s', 3), self::ex('Pseudo Planche Push Up', '6-8', 3)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '5-8s', 4), self::ex('Wall Handstand Hold', '30-40s', 4), self::ex('Wide Push Up', '10-12', 4), self::ex('Decline Push Up', '8-10', 4)], 120), self::block(1, 1, 45, [self::ex('Pike Push Up', '10-12', 4), self::ex('Pseudo Planche Push Up', '10-12', 4)], 120)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '8-10s', 4), self::ex('Wall Handstand Hold', '35-45s', 4), self::ex('Wide Push Up', '12-15', 4), self::ex('Decline Push Up', '10-12', 4)], 120), self::block(1, 1, 45, [self::ex('Pike Push Up', '12-15', 4), self::ex('Pseudo Planche Push Up', '12-15', 4)], 120)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '10-12s', 5), self::ex('Wall Handstand Hold', '40-50s', 5), self::ex('Wide Push Up', '15-18', 5), self::ex('Decline Push Up', '12-15', 5)], 120), self::block(1, 1, 45, [self::ex('Pike Push Up', '15-18', 5), self::ex('Pseudo Planche Push Up', '15-18', 5)], 120)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Planche', '5-6s', 3), self::ex('Wall Handstand Hold', '20-25s', 3), self::ex('Wide Push Up', '8-10', 3), self::ex('Decline Push Up', '6-8', 3)], 120), self::block(1, 1, 45, [self::ex('Pike Push Up', '8-10', 3), self::ex('Pseudo Planche Push Up', '8-10', 3)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '3-5s', 4), self::ex('Deficit Handstand Push Up', '3-5', 4), self::ex('Pseudo Planche Push Up', '8-10', 4), self::ex('Decline Push Up', '8-10', 4)], 150), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '35-45s', 4), self::ex('Assisted Dips', '8-10', 4)], 150)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '5-8s', 4), self::ex('Deficit Handstand Push Up', '5-6', 4), self::ex('Pseudo Planche Push Up', '10-12', 4), self::ex('Decline Push Up', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '40-50s', 4), self::ex('Assisted Dips', '10-12', 4)], 150)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '8-10s', 5), self::ex('Deficit Handstand Push Up', '6-8', 5), self::ex('Pseudo Planche Push Up', '12-15', 5), self::ex('Decline Push Up', '12-15', 5)], 150), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '45-55s', 5), self::ex('Assisted Dips', '12-15', 5)], 150)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '3-4s', 3), self::ex('Deficit Handstand Push Up', '3-4', 3), self::ex('Pseudo Planche Push Up', '6-8', 3), self::ex('Decline Push Up', '6-8', 3)], 150), self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '25-30s', 3), self::ex('Assisted Dips', '6-8', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '8-10s', 4), self::ex('Handstand Push Up', '5-8', 4), self::ex('Pseudo Planche Push Up', '10-12', 4), self::ex('Archer Push Up', '5-8', 4)], 150), self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10-15s', 4), self::ex('Deficit Handstand Push Up', '5-8', 4)], 150)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '10-12s', 4), self::ex('Handstand Push Up', '8-10', 4), self::ex('Archer Push Up', '8-10', 4), self::ex('Planche Push Up', '3-5', 4)], 150), self::block(1, 1, 45, [self::ex('Freestanding Handstand', '15-20s', 4), self::ex('Deficit Handstand Push Up', '8-10', 4)], 150)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '12-15s', 5), self::ex('Handstand Push Up', '10-12', 5), self::ex('Archer Push Up', '10-12', 5), self::ex('Planche Push Up', '5-8', 5)], 150), self::block(1, 1, 45, [self::ex('Freestanding Handstand', '20-25s', 5), self::ex('Deficit Handstand Push Up', '10-12', 5)], 150)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Planche', '6-8s', 3), self::ex('Handstand Push Up', '5-6', 3), self::ex('Archer Push Up', '5-6', 3), self::ex('Pseudo Planche Push Up', '6-8', 3)], 150), self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10-15s', 3), self::ex('Deficit Handstand Push Up', '5-6', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '8-10', 4), self::ex('Archer Push Up', '8-10', 4), self::ex('Freestanding Handstand Push Up', '1-3', 4), self::ex('Planche Push Up', '3-5', 4)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '12-15s', 4), self::ex('Freestanding Handstand', '20-25s', 4)], 150)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '10-12', 4), self::ex('Archer Push Up', '10-12', 4), self::ex('Freestanding Handstand Push Up', '3-5', 4), self::ex('Planche Push Up', '5-8', 4)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '15-18s', 4), self::ex('Freestanding Handstand', '25-30s', 4)], 150)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '12-15', 5), self::ex('Archer Push Up', '12-15', 5), self::ex('Freestanding Handstand Push Up', '5-8', 5), self::ex('Planche Push Up', '8-10', 5)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '18-20s', 5), self::ex('Freestanding Handstand', '30-35s', 5)], 150)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '6-8', 3), self::ex('Archer Push Up', '6-8', 3), self::ex('Freestanding Handstand Push Up', '1-3', 3), self::ex('Planche Push Up', '3-5', 3)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '8-10s', 3), self::ex('Freestanding Handstand', '15-20s', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Deficit Handstand Push Up', '8-10', 4), self::ex('Handstand Push Up', '10-12', 4), self::ex('Planche Push Up', '5-8', 4), self::ex('One-Arm Push Up', '1-3', 4)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '15-20s', 4), self::ex('Freestanding Handstand', '25-30s', 4)], 150)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Deficit Handstand Push Up', '10-12', 4), self::ex('Handstand Push Up', '12-15', 4), self::ex('Planche Push Up', '8-10', 4), self::ex('One-Arm Push Up', '3-5', 4)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '18-20s', 4), self::ex('Freestanding Handstand', '30-35s', 4)], 150)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Deficit Handstand Push Up', '12-15', 5), self::ex('Handstand Push Up', '15-18', 5), self::ex('Planche Push Up', '10-12', 5), self::ex('One-Arm Push Up', '5-8', 5)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '20-25s', 4), self::ex('Freestanding Handstand', '35-40s', 4)], 150)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Deficit Handstand Push Up', '6-8', 3), self::ex('Handstand Push Up', '8-10', 3), self::ex('Planche Push Up', '5-6', 3), self::ex('One-Arm Push Up', '1-2', 3)], 150), self::block(1, 1, 45, [self::ex('Straddle Planche', '10-12s', 3), self::ex('Freestanding Handstand', '20-25s', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Push Up', '8-10', 4), self::ex('Freestanding Handstand Push Up', '5-8', 4), self::ex('One-Arm Push Up', '1-3', 4), self::ex('90 Degree Push Up', '1-3', 4)], 150), self::block(1, 1, 45, [self::ex('Planche', '5-8s', 3), self::ex('Freestanding Handstand', '30-35s', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Push Up', '10-12', 4), self::ex('Freestanding Handstand Push Up', '8-10', 4), self::ex('One-Arm Push Up', '3-5', 4), self::ex('90 Degree Push Up', '3-5', 4)], 150), self::block(1, 1, 45, [self::ex('Planche', '8-10s', 3), self::ex('Freestanding Handstand', '35-40s', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Push Up', '12-15', 5), self::ex('Freestanding Handstand Push Up', '10-12', 5), self::ex('One-Arm Push Up', '5-8', 5), self::ex('90 Degree Push Up', '5-8', 5)], 150), self::block(1, 1, 45, [self::ex('Planche', '10-12s', 3), self::ex('Freestanding Handstand', '40-45s', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Planche Push Up', '6-8', 3), self::ex('Freestanding Handstand Push Up', '5-6', 3), self::ex('One-Arm Push Up', '1-3', 3), self::ex('90 Degree Push Up', '1-3', 3)], 150), self::block(1, 1, 45, [self::ex('Planche', '5-6s', 2), self::ex('Freestanding Handstand', '20-25s', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('One-Arm Push Up', '3-5', 4), self::ex('Planche Push Up', '10-12', 4), self::ex('Freestanding Handstand Push Up', '8-10', 4), self::ex('90 Degree Push Up', '3-5', 4)], 150), self::block(1, 1, 45, [self::ex('Planche', '10-12s', 3), self::ex('Freestanding Handstand', '35-40s', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Push Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('One-Arm Push Up', '5-8', 4), self::ex('Planche Push Up', '12-15', 4), self::ex('Freestanding Handstand Push Up', '10-12', 4), self::ex('90 Degree Push Up', '5-8', 4)], 150), self::block(1, 1, 45, [self::ex('Planche', '12-15s', 3), self::ex('Freestanding Handstand', '40-45s', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('One-Arm Push Up', '8-10', 5), self::ex('Planche Push Up', '15-18', 5), self::ex('Freestanding Handstand Push Up', '12-15', 5), self::ex('90 Degree Push Up', '8-10', 5)], 150), self::block(1, 1, 45, [self::ex('Planche', '15-18s', 3), self::ex('Freestanding Handstand', '45-50s', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Push Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('One-Arm Push Up', '3-5', 3), self::ex('Planche Push Up', '8-10', 3), self::ex('Freestanding Handstand Push Up', '6-8', 3), self::ex('90 Degree Push Up', '3-5', 3)], 150), self::block(1, 1, 45, [self::ex('Planche', '8-10s', 2), self::ex('Freestanding Handstand', '25-30s', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — STRENGTH PULL
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
            9 => self::sessionB_L9($phase),
            10 => self::sessionB_L10($phase),
            11 => self::sessionB_L11($phase),
            12 => self::sessionB_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionB_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Aprender pull-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '8-10', 3), self::ex('Negative Pull Up', '3-5', 3), self::ex('Active Hang', '15-20s', 3), self::ex('Dead Hang', '20-30s', 3)], 90)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '10-12', 3), self::ex('Negative Pull Up', '5-8', 3), self::ex('Assisted Pull Up', '5-8', 3), self::ex('Active Hang', '20-30s', 3)], 90)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '12-15', 4), self::ex('Negative Pull Up', '5-8', 4), self::ex('Assisted Pull Up', '8-10', 4), self::ex('Active Hang', '30-40s', 4)], 90)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '6-8', 2), self::ex('Negative Pull Up', '3-5', 2), self::ex('Active Hang', '15-20s', 2), self::ex('Dead Hang', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Introducir pull-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '10-12', 3), self::ex('Standard Pull Up', '3-5', 3), self::ex('Negative Pull Up', '5-8', 3)], 90), self::block(1, 1, 45, [self::ex('Active Hang', '20-30s', 3), self::ex('Scapular Pull Up', '5-8', 3)], 90)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Aumentar pull-ups', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '12-15', 3), self::ex('Standard Pull Up', '5-8', 3), self::ex('Negative Pull Up', '6-8', 3)], 90), self::block(1, 1, 45, [self::ex('Active Hang', '25-35s', 3), self::ex('Scapular Pull Up', '8-10', 3)], 90)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 4), self::ex('Australian Pull Up', '15-18', 4), self::ex('Negative Pull Up', '8-10', 4)], 90), self::block(1, 1, 45, [self::ex('Active Hang', '30-40s', 4), self::ex('Scapular Pull Up', '10-12', 4)], 90)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '3-5', 2), self::ex('Australian Pull Up', '8-10', 2), self::ex('Negative Pull Up', '5-6', 2)], 90), self::block(1, 1, 45, [self::ex('Active Hang', '15-20s', 2), self::ex('Scapular Pull Up', '5-6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Consolidar pull-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-8', 3), self::ex('Chin Up', '3-5', 3), self::ex('Australian Pull Up', '10-12', 3)], 90), self::block(1, 1, 45, [self::ex('Band Assisted Pull Up', '5-8', 3), self::ex('Scapular Pull Up', '8-10', 3), self::ex('Active Hang', '20-30s', 3)], 90)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Introducir chin-ups', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Chin Up', '5-8', 3), self::ex('Australian Pull Up', '12-15', 3)], 90), self::block(1, 1, 45, [self::ex('Band Assisted Pull Up', '6-8', 3), self::ex('Scapular Pull Up', '10-12', 3), self::ex('Active Hang', '25-35s', 3)], 90)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 4), self::ex('Chin Up', '8-10', 4), self::ex('Australian Pull Up', '15-18', 4)], 90), self::block(1, 1, 45, [self::ex('Band Assisted Pull Up', '8-10', 4), self::ex('Scapular Pull Up', '12-15', 4), self::ex('Active Hang', '30-40s', 4)], 90)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 2), self::ex('Chin Up', '3-5', 2), self::ex('Australian Pull Up', '8-10', 2)], 90), self::block(1, 1, 45, [self::ex('Band Assisted Pull Up', '5-6', 2), self::ex('Scapular Pull Up', '6-8', 2), self::ex('Active Hang', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 4), self::ex('Chin Up', '5-8', 4), self::ex('Wide Grip Pull Up', '3-5', 4)], 120), self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '5-10s', 4), self::ex('Tuck Front Lever Row', '5-8', 4), self::ex('Active Hang', '20-30s', 4)], 120)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 4), self::ex('Chin Up', '8-10', 4), self::ex('Wide Grip Pull Up', '5-8', 4)], 120), self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '10-15s', 4), self::ex('Tuck Front Lever Row', '8-10', 4), self::ex('Active Hang', '25-35s', 4)], 120)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '12-15', 4), self::ex('Chin Up', '10-12', 4), self::ex('Wide Grip Pull Up', '8-10', 4)], 120), self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '15-20s', 4), self::ex('Tuck Front Lever Row', '10-12', 4), self::ex('Active Hang', '30-40s', 4)], 120)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-8', 3), self::ex('Chin Up', '5-6', 3), self::ex('Wide Grip Pull Up', '3-5', 3)], 120), self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '5-8s', 3), self::ex('Tuck Front Lever Row', '5-6', 3), self::ex('Active Hang', '15-20s', 3)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [self::ex('Chin Up', '8-10', 4), self::ex('Wide Grip Pull Up', '5-8', 4), self::ex('Archer Pull Up', '3-5', 4)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '5-10s', 4), self::ex('Tuck Front Lever Row', '8-10', 4), self::ex('Active Hang', '25-35s', 4)], 120)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '8-10', 4), self::ex('Archer Pull Up', '5-8', 4), self::ex('Standard Pull Up', '10-12', 4)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '10-15s', 4), self::ex('Tuck Front Lever Row', '10-12', 4), self::ex('Active Hang', '30-40s', 4)], 120)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '8-10', 4), self::ex('Wide Grip Pull Up', '10-12', 4), self::ex('Chin Up', '12-15', 4)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '15-20s', 4), self::ex('Tuck Front Lever Row', '12-15', 4), self::ex('Active Hang', '35-45s', 4)], 120)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Chin Up', '5-8', 3), self::ex('Wide Grip Pull Up', '5-6', 3), self::ex('Archer Pull Up', '3-5', 3)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '5-8s', 3), self::ex('Tuck Front Lever Row', '6-8', 3), self::ex('Active Hang', '20-25s', 3)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '8-10', 4), self::ex('Archer Pull Up', '5-8', 4), self::ex('Muscle Up Negative', '2-3', 4)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Row', '8-10', 4), self::ex('Back Lever Hold', '5-10s', 4), self::ex('Active Hang', '30-40s', 4)], 120)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '8-10', 4), self::ex('Muscle Up Negative', '3-5', 4), self::ex('Wide Grip Pull Up', '10-12', 4)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Row', '10-12', 4), self::ex('Back Lever Hold', '10-15s', 4), self::ex('Active Hang', '35-45s', 4)], 120)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Muscle Up Negative', '5-8', 4), self::ex('Archer Pull Up', '10-12', 4), self::ex('Wide Grip Pull Up', '12-15', 4)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Row', '12-15', 4), self::ex('Back Lever Hold', '15-20s', 4), self::ex('Active Hang', '40-50s', 4)], 120)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '5-8', 3), self::ex('Archer Pull Up', '5-6', 3), self::ex('Muscle Up Negative', '2-3', 3)], 120), self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Row', '6-8', 3), self::ex('Back Lever Hold', '5-8s', 3), self::ex('Active Hang', '20-25s', 3)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '3-5s', 4), self::ex('Back Lever Hold', '5-8s', 4), self::ex('Archer Pull Up', '5-8', 4), self::ex('Muscle Up Progression', '3-5', 4)], 150), self::block(1, 1, 45, [self::ex('Chin Up', '8-10', 4), self::ex('Wide Grip Pull Up', '12-15', 4)], 150)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '5-8s', 4), self::ex('Back Lever Hold', '8-10s', 4), self::ex('Archer Pull Up', '8-10', 4), self::ex('Muscle Up Progression', '5-8', 4)], 150), self::block(1, 1, 45, [self::ex('Chin Up', '10-12', 4), self::ex('Wide Grip Pull Up', '15-18', 4)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '8-10s', 5), self::ex('Back Lever Hold', '10-12s', 5), self::ex('Archer Pull Up', '10-12', 5), self::ex('Muscle Up Progression', '5-8', 5)], 150), self::block(1, 1, 45, [self::ex('Chin Up', '12-15', 5), self::ex('Wide Grip Pull Up', '18-20', 5)], 150)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '3-4s', 3), self::ex('Back Lever Hold', '4-5s', 3), self::ex('Archer Pull Up', '5-6', 3), self::ex('Muscle Up Progression', '3-4', 3)], 150), self::block(1, 1, 45, [self::ex('Chin Up', '6-8', 3), self::ex('Wide Grip Pull Up', '8-10', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Muscle Up', '5-8', 4), self::ex('Weighted Chin Up', '5-8', 4), self::ex('Archer Pull Up', '8-10', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '8-10s', 4), self::ex('Advanced Tuck Front Lever Row', '10-12', 4), self::ex('Back Lever Hold', '15-20s', 4)], 150), self::block(1, 1, 45, [self::ex('Archer Chin Up', '5-8', 4), self::ex('Wide Grip Pull Up', '12-15', 4)], 150)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '8-10', 4), self::ex('Archer Pull Up', '10-12', 4), self::ex('One Arm Pull Up', '1-3', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '10-12s', 4), self::ex('Advanced Tuck Front Lever Row', '12-15', 4), self::ex('Back Lever Hold', '20-25s', 4)], 150), self::block(1, 1, 45, [self::ex('Archer Chin Up', '8-10', 4), self::ex('Wide Grip Pull Up', '15-18', 4)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '3-5', 4), self::ex('Weighted Chin Up', '10-12', 4), self::ex('Muscle Up', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '12-15s', 4), self::ex('Back Lever Row', '8-10', 4), self::ex('Back Lever Hold', '25-30s', 4)], 150), self::block(1, 1, 45, [self::ex('Archer Chin Up', '10-12', 4), self::ex('One Arm Chin Up', '1-3', 4)], 150)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Muscle Up', '5-6', 3), self::ex('Weighted Chin Up', '5-6', 3), self::ex('Archer Pull Up', '5-6', 3)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '5-6s', 3), self::ex('Back Lever Hold', '10-12s', 3), self::ex('Advanced Tuck Front Lever Row', '6-8', 3)], 150), self::block(1, 1, 45, [self::ex('Archer Chin Up', '5-6', 3), self::ex('Wide Grip Pull Up', '8-10', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '8-10', 4), self::ex('One Arm Pull Up', '3-5', 4), self::ex('Muscle Up', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '10-12s', 4), self::ex('Human Flag', '3-5s', 4), self::ex('Back Lever Hold', '25-30s', 4)], 150), self::block(1, 1, 45, [self::ex('One Arm Chin Up', '3-5', 4), self::ex('Archer Chin Up', '8-10', 4)], 150)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '5-8', 4), self::ex('One Arm Chin Up', '5-8', 4), self::ex('Weighted Chin Up', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '12-15s', 4), self::ex('Human Flag', '5-8s', 4), self::ex('Back Lever Hold', '30-35s', 4)], 150), self::block(1, 1, 45, [self::ex('Archer Pull Up - Wide', '5-8', 4), self::ex('Archer Chin Up', '10-12', 4)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '8-10', 4), self::ex('One Arm Chin Up', '8-10', 4), self::ex('Weighted Chin Up', '12-15', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '15-18s', 4), self::ex('Human Flag', '8-10s', 4), self::ex('Back Lever Hold', '35-40s', 4)], 150), self::block(1, 1, 45, [self::ex('Archer Pull Up - Wide', '8-10', 4), self::ex('90 Degree Hold to Chin Up', '3-5', 4)], 150)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Muscle Up', '8-10', 3), self::ex('Weighted Chin Up', '6-8', 3), self::ex('Archer Pull Up', '5-6', 3)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '8-10s', 3), self::ex('Human Flag', '3-5s', 3), self::ex('Back Lever Hold', '20-25s', 3)], 150), self::block(1, 1, 45, [self::ex('Archer Chin Up', '5-6', 3), self::ex('Wide Grip Pull Up', '8-10', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '8-10', 4), self::ex('One Arm Chin Up', '8-10', 4), self::ex('Archer Pull Up', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '15-18s', 4), self::ex('Full Front Lever Row', '8-10', 4), self::ex('Human Flag', '8-10s', 4)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '12-15', 4), self::ex('Weighted Chin Up', '12-15', 4)], 150)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '10-12', 4), self::ex('One Arm Chin Up', '10-12', 4), self::ex('Archer Pull Up - Wide', '8-10', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '18-20s', 4), self::ex('Full Front Lever Row', '10-12', 4), self::ex('Human Flag', '10-12s', 4)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '15-18', 4), self::ex('90 Degree Hold to Chin Up', '5-8', 4)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '12-15', 4), self::ex('One Arm Chin Up', '12-15', 4), self::ex('Archer Pull Up - Wide', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '20-25s', 4), self::ex('Front Lever Raise', '8-10', 4), self::ex('Human Flag', '12-15s', 4)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '18-20', 4), self::ex('90 Degree Hold to Chin Up', '8-10', 4)], 150)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '5-8', 3), self::ex('One Arm Chin Up', '5-8', 3), self::ex('Archer Pull Up', '6-8', 3)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '10-12s', 3), self::ex('Full Front Lever Row', '5-6', 3), self::ex('Human Flag', '5-8s', 3)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '10-12', 3), self::ex('Weighted Chin Up', '8-10', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '12-15', 4), self::ex('One Arm Chin Up', '12-15', 4), self::ex('Archer Pull Up - Wide', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '18-20s', 4), self::ex('Front Lever Raise', '10-12', 4), self::ex('Human Flag', '12-15s', 4)], 150), self::block(1, 1, 45, [self::ex('90 Degree Hold to Chin Up', '8-10', 4), self::ex('Muscle Up', '18-20', 4)], 150)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '15-18', 4), self::ex('One Arm Chin Up', '15-18', 4), self::ex('Archer Pull Up - Wide', '12-15', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '20-25s', 4), self::ex('Front Lever Raise', '12-15', 4), self::ex('Human Flag', '15-18s', 4)], 150), self::block(1, 1, 45, [self::ex('90 Degree Hold to Chin Up', '10-12', 4), self::ex('Muscle Up', '20-25', 4)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '18-20', 4), self::ex('One Arm Chin Up', '18-20', 4), self::ex('Archer Pull Up - Wide', '15-18', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '25-30s', 4), self::ex('Front Lever Raise', '15-18', 4), self::ex('Human Flag', '18-20s', 4)], 150), self::block(1, 1, 45, [self::ex('90 Degree Hold to Chin Up', '12-15', 4), self::ex('Back Lever Raise', '8-10', 4)], 150)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '8-10', 3), self::ex('One Arm Chin Up', '8-10', 3), self::ex('Archer Pull Up - Wide', '8-10', 3)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '12-15s', 3), self::ex('Front Lever Raise', '6-8', 3), self::ex('Human Flag', '8-10s', 3)], 150), self::block(1, 1, 45, [self::ex('90 Degree Hold to Chin Up', '6-8', 3), self::ex('Muscle Up', '12-15', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '15-18', 4), self::ex('One Arm Chin Up', '15-18', 4), self::ex('90 Degree Hold to Chin Up', '10-12', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '20-25s', 4), self::ex('Front Lever Raise', '12-15', 4), self::ex('Human Flag', '18-20s', 4)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '20-25', 4), self::ex('Archer Pull Up - Wide', '15-18', 4)], 150)]],
            'progression' => ['name' => 'Sesión B: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '18-20', 4), self::ex('One Arm Chin Up', '18-20', 4), self::ex('90 Degree Hold to Chin Up', '12-15', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '25-30s', 4), self::ex('Front Lever Raise', '15-18', 4), self::ex('Human Flag', '20-25s', 4)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '25-30', 4), self::ex('Archer Pull Up - Wide', '18-20', 4)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '20-25', 4), self::ex('One Arm Chin Up', '20-25', 4), self::ex('90 Degree Hold to Chin Up', '15-18', 4)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '30-35s', 4), self::ex('Front Lever Raise', '18-20', 4), self::ex('Human Flag', '25-30s', 4)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '30-35', 4), self::ex('Archer Pull Up - Wide', '20-25', 4)], 150)]],
            'deload' => ['name' => 'Sesión B: Pull Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('One Arm Pull Up', '10-12', 3), self::ex('One Arm Chin Up', '10-12', 3), self::ex('90 Degree Hold to Chin Up', '8-10', 3)], 150), self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '15-18s', 3), self::ex('Front Lever Raise', '8-10', 3), self::ex('Human Flag', '12-15s', 3)], 150), self::block(1, 1, 45, [self::ex('Muscle Up', '15-18', 3), self::ex('Archer Pull Up - Wide', '10-12', 3)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — FULL BODY CIRCUIT
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
            9 => self::sessionC_L9($phase),
            10 => self::sessionC_L10($phase),
            11 => self::sessionC_L11($phase),
            12 => self::sessionC_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionC_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [self::ex('Air Squat', '15'), self::ex('Glute Bridge', '12'), self::ex('Calf Raise on Step', '15'), self::ex('Dead Bug', '10/side'), self::ex('High Plank', '20s'), self::ex('Hollow Body Hold', '15s')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [self::ex('Air Squat', '18'), self::ex('Glute Bridge', '15'), self::ex('Calf Raise on Step', '18'), self::ex('Dead Bug', '12/side'), self::ex('High Plank', '25s'), self::ex('Hollow Body Hold', '20s')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Air Squat', '20'), self::ex('Glute Bridge', '15'), self::ex('Calf Raise on Step', '20'), self::ex('Dead Bug', '12/side'), self::ex('High Plank', '30s'), self::ex('Hollow Body Hold', '25s')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(2, 60, 15, [self::ex('Air Squat', '10'), self::ex('Glute Bridge', '8'), self::ex('Calf Raise on Step', '10'), self::ex('Dead Bug', '6/side'), self::ex('High Plank', '15s'), self::ex('Hollow Body Hold', '10s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Core', 'blocks' => [self::block(3, 60, 15, [self::ex('Air Squat', '20'), self::ex('Dumbbell Lunge', '8/leg'), self::ex('Calf Raise on Step', '15'), self::ex('Hollow Body Hold', '20s'), self::ex('Dead Bug', '10/side'), self::ex('High Plank', '30s')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Core', 'blocks' => [self::block(3, 60, 15, [self::ex('Air Squat', '25'), self::ex('Dumbbell Lunge', '10/leg'), self::ex('Calf Raise on Step', '18'), self::ex('Hollow Body Hold', '25s'), self::ex('Dead Bug', '12/side'), self::ex('High Plank', '35s')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Core', 'blocks' => [self::block(4, 60, 20, [self::ex('Air Squat', '25'), self::ex('Dumbbell Lunge', '10/leg'), self::ex('Calf Raise on Step', '20'), self::ex('Hollow Body Hold', '30s'), self::ex('Dead Bug', '12/side'), self::ex('High Plank', '40s')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Core', 'blocks' => [self::block(2, 60, 15, [self::ex('Air Squat', '12'), self::ex('Dumbbell Lunge', '5/leg'), self::ex('Calf Raise on Step', '10'), self::ex('Hollow Body Hold', '15s'), self::ex('Dead Bug', '6/side'), self::ex('High Plank', '20s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [self::ex('Air Squat', '20'), self::ex('Bulgarian Split Squat', '5/leg'), self::ex('Calf Raise on Step', '15'), self::ex('Tuck L-Sit', '10s'), self::ex('Leg Raise', '8'), self::ex('Side Plank', '20s/side')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [self::ex('Air Squat', '25'), self::ex('Bulgarian Split Squat', '6/leg'), self::ex('Calf Raise on Step', '18'), self::ex('Tuck L-Sit', '12s'), self::ex('Leg Raise', '10'), self::ex('Side Plank', '25s/side')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Air Squat', '25'), self::ex('Bulgarian Split Squat', '6/leg'), self::ex('Calf Raise on Step', '20'), self::ex('Tuck L-Sit', '15s'), self::ex('Leg Raise', '12'), self::ex('Side Plank', '30s/side')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(2, 60, 15, [self::ex('Air Squat', '12'), self::ex('Bulgarian Split Squat', '3/leg'), self::ex('Calf Raise on Step', '10'), self::ex('Tuck L-Sit', '8s'), self::ex('Leg Raise', '6'), self::ex('Side Plank', '15s/side')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Bulgarian Split Squat', '6/leg'), self::ex('Jump Squat', '8'), self::ex('Assisted Pistol Squat', '3/leg'), self::ex('Calf Raise on Step', '15'), self::ex('Glute Bridge', '12'), self::ex('Dead Bug', '10/side')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Jump Squat', '10'), self::ex('Assisted Pistol Squat', '4/leg'), self::ex('Calf Raise on Step', '18'), self::ex('Glute Bridge', '15'), self::ex('Dead Bug', '12/side')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Jump Squat', '10'), self::ex('Assisted Pistol Squat', '5/leg'), self::ex('Calf Raise on Step', '20'), self::ex('Glute Bridge', '15'), self::ex('Dead Bug', '12/side')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Bulgarian Split Squat', '4/leg'), self::ex('Jump Squat', '5'), self::ex('Assisted Pistol Squat', '2/leg'), self::ex('Calf Raise on Step', '10'), self::ex('Glute Bridge', '8'), self::ex('Dead Bug', '6/side')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Jump Squat', '10'), self::ex('Box Pistol Squat', '3/leg'), self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Nordic Curl Negative', '3'), self::ex('Calf Raise on Step', '15'), self::ex('Single Leg Glute Bridge', '8/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Jump Squat', '12'), self::ex('Box Pistol Squat', '4/leg'), self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Nordic Curl Negative', '4'), self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Jump Squat', '12'), self::ex('Box Pistol Squat', '5/leg'), self::ex('Bulgarian Split Squat', '10/leg'), self::ex('Nordic Curl Negative', '4'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '10/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Jump Squat', '6'), self::ex('Box Pistol Squat', '2/leg'), self::ex('Bulgarian Split Squat', '5/leg'), self::ex('Nordic Curl Negative', '2'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Box Pistol Squat', '4/leg'), self::ex('Jump Squat', '10'), self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Nordic Curl with Band', '3'), self::ex('Calf Raise on Step', '15'), self::ex('Single Leg Glute Bridge', '10/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Box Pistol Squat', '5/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg'), self::ex('Nordic Curl with Band', '4'), self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Box Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg'), self::ex('Nordic Curl with Band', '4'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Box Pistol Squat', '2/leg'), self::ex('Jump Squat', '6'), self::ex('Bulgarian Split Squat', '5/leg'), self::ex('Nordic Curl with Band', '2'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Box Pistol Squat', '5/leg'), self::ex('Jump Squat', '10'), self::ex('Bulgarian Split Squat', '8/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '3-5'), self::ex('Calf Raise on Step', '15'), self::ex('Single Leg Glute Bridge', '10/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'), self::ex('Deep Squat Hold', '20s/side')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Box Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'), self::ex('Deep Squat Hold', '20s/side')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Box Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'), self::ex('Deep Squat Hold', '25s/side')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Box Pistol Squat', '3/leg'), self::ex('Jump Squat', '6'), self::ex('Bulgarian Split Squat', '5/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '2-3'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'), self::ex('Deep Squat Hold', '15s/side')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '3/leg'), self::ex('Jump Squat', '10'), self::ex('Bulgarian Split Squat', '8/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl Negative', '3-5'), self::ex('Calf Raise on Step', '15'), self::ex('Single Leg Glute Bridge', '10/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'), self::ex('Shrimp Squat', '3/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '4/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl Negative', '4-5'), self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'), self::ex('Shrimp Squat', '4/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl Negative', '4-5'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'), self::ex('Shrimp Squat', '5/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Pistol Squat', '2/leg'), self::ex('Jump Squat', '6'), self::ex('Bulgarian Split Squat', '5/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl Negative', '2-3'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'), self::ex('Shrimp Squat', '2/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'), self::ex('Shrimp Squat', '4/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '6/leg'), self::ex('Jump Squat', '15'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '5-6'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'), self::ex('Shrimp Squat', '5/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Pistol Squat', '8/leg'), self::ex('Jump Squat', '15'), self::ex('Bulgarian Split Squat', '12/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '5-6'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '45s'), self::ex('Cossack Squat', '8/side'), self::ex('Shrimp Squat', '6/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Pistol Squat', '3/leg'), self::ex('Jump Squat', '8'), self::ex('Bulgarian Split Squat', '5/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '3-4'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '25s'), self::ex('Cossack Squat', '4/side'), self::ex('Shrimp Squat', '3/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'), self::ex('Bulgarian Split Squat', '10/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '3-5'), self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'), self::ex('Dragon Pistol Squat', '2/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '8/leg'), self::ex('Jump Squat', '15'), self::ex('Bulgarian Split Squat', '12/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '4-5'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '45s'), self::ex('Cossack Squat', '8/side'), self::ex('Dragon Pistol Squat', '3/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Pistol Squat', '10/leg'), self::ex('Jump Squat', '15'), self::ex('Bulgarian Split Squat', '12/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '5-6'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '50s'), self::ex('Cossack Squat', '8/side'), self::ex('Dragon Pistol Squat', '3/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Pistol Squat', '3/leg'), self::ex('Jump Squat', '8'), self::ex('Bulgarian Split Squat', '5/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '3-4'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '25s'), self::ex('Cossack Squat', '4/side'), self::ex('Shrimp Squat', '3/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '8/leg'), self::ex('Jump Squat', '15'), self::ex('Bulgarian Split Squat', '12/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '5-6'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '45s'), self::ex('Cossack Squat', '8/side'), self::ex('Weighted Pistol Squat', '3-5/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '10/leg'), self::ex('Jump Squat', '18'), self::ex('Bulgarian Split Squat', '12/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '6-8'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '50s'), self::ex('Cossack Squat', '10/side'), self::ex('Weighted Pistol Squat', '5-6/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Pistol Squat', '12/leg'), self::ex('Jump Squat', '18'), self::ex('Bulgarian Split Squat', '15/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '8-10'), self::ex('Calf Raise on Step', '25'), self::ex('Single Leg Glute Bridge', '15/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '60s'), self::ex('Cossack Squat', '10/side'), self::ex('Weighted Pistol Squat', '6-8/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Pistol Squat', '4/leg'), self::ex('Jump Squat', '8'), self::ex('Bulgarian Split Squat', '6/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'), self::ex('Weighted Pistol Squat', '3-4/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '10/leg'), self::ex('Jump Squat', '15'), self::ex('Bulgarian Split Squat', '12/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '8-10'), self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '15/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '50s'), self::ex('Cossack Squat', '10/side'), self::ex('Jumping Pistol Squat', '2-3/leg')])]],
            'progression' => ['name' => 'Sesión C: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [self::ex('Pistol Squat', '12/leg'), self::ex('Jump Squat', '18'), self::ex('Bulgarian Split Squat', '15/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '10-12'), self::ex('Calf Raise on Step', '25'), self::ex('Single Leg Glute Bridge', '15/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '60s'), self::ex('Cossack Squat', '12/side'), self::ex('Jumping Pistol Squat', '3-4/leg')])]],
            'intensification' => ['name' => 'Sesión C: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [self::ex('Pistol Squat', '15/leg'), self::ex('Jump Squat', '20'), self::ex('Bulgarian Split Squat', '15/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl', '12-15'), self::ex('Calf Raise on Step', '25'), self::ex('Single Leg Glute Bridge', '15/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '60s'), self::ex('Cossack Squat', '12/side'), self::ex('Jumping Pistol Squat', '4-5/leg')])]],
            'deload' => ['name' => 'Sesión C: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '10'), self::ex('Bulgarian Split Squat', '6/leg')]), self::block(2, 60, 20, [self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg')]), self::block(2, 30, 10, [self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '6/side'), self::ex('Jumping Pistol Squat', '2-3/leg')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL & CONDITIONING
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
            9 => self::sessionD_L9($phase),
            10 => self::sessionD_L10($phase),
            11 => self::sessionD_L11($phase),
            12 => self::sessionD_L12($phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function sessionD_L1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '15-20s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('High Plank', '30-35s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '10-15s'), self::ex('Dead Bug', '10-12')]), self::block(2, 90, 45, [self::ex('Active Hang', '15-20s'), self::ex('Reverse Plank', '20-25s'), self::ex('Frog Stand', '15-20s'), self::ex('Glute Bridge', '12'), self::ex('Side Plank', '20s/side'), self::ex('Hollow Body Hold', '20-25s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '20-25s'), self::ex('Hollow Body Hold', '25-30s'), self::ex('High Plank', '35-40s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '12-15s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Active Hang', '20-25s'), self::ex('Reverse Plank', '25-30s'), self::ex('Frog Stand', '20-25s'), self::ex('Glute Bridge', '15'), self::ex('Side Plank', '25s/side'), self::ex('Hollow Body Hold', '25-30s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '25-30s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('High Plank', '40-45s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Active Hang', '25-30s'), self::ex('Reverse Plank', '30-35s'), self::ex('Frog Stand', '25-30s'), self::ex('Glute Bridge', '15'), self::ex('Side Plank', '30s/side'), self::ex('Hollow Body Hold', '30-35s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '10-15s'), self::ex('Hollow Body Hold', '15-20s'), self::ex('High Plank', '25-30s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '8-10s'), self::ex('Dead Bug', '8-10')]), self::block(2, 90, 45, [self::ex('Active Hang', '10-15s'), self::ex('Reverse Plank', '15-20s'), self::ex('Frog Stand', '10-15s'), self::ex('Glute Bridge', '8'), self::ex('Side Plank', '15s/side'), self::ex('Hollow Body Hold', '15-20s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '20-25s'), self::ex('Hollow Body Hold', '25-30s'), self::ex('High Plank', '35-40s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '12-15s'), self::ex('Dead Bug', '10-12')]), self::block(2, 90, 45, [self::ex('Active Hang', '20-25s'), self::ex('Reverse Plank', '25-30s'), self::ex('Frog Stand', '20-25s'), self::ex('Glute Bridge', '15'), self::ex('Side Plank', '25s/side'), self::ex('Hollow Body Hold', '25-30s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '25-30s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('High Plank', '40-45s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Active Hang', '25-30s'), self::ex('Reverse Plank', '30-35s'), self::ex('Frog Stand', '25-30s'), self::ex('Glute Bridge', '15'), self::ex('Side Plank', '30s/side'), self::ex('Hollow Body Hold', '30-35s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '30-35s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('High Plank', '50-55s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Active Hang', '30-35s'), self::ex('Reverse Plank', '35-40s'), self::ex('Frog Stand', '30-35s'), self::ex('Glute Bridge', '15'), self::ex('Side Plank', '35s/side'), self::ex('Hollow Body Hold', '35-40s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core', 'blocks' => [self::block(2, 90, 45, [self::ex('Frog Stand', '15-20s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('High Plank', '30-35s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '10-15s'), self::ex('Dead Bug', '8-10')]), self::block(2, 90, 45, [self::ex('Active Hang', '15-20s'), self::ex('Reverse Plank', '20-25s'), self::ex('Frog Stand', '15-20s'), self::ex('Glute Bridge', '10'), self::ex('Side Plank', '20s/side'), self::ex('Hollow Body Hold', '20-25s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Tuck FL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '5-10s'), self::ex('Frog Stand', '20-25s'), self::ex('Hollow Body Hold', '25-30s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '12-15s'), self::ex('Dead Bug', '10-12')]), self::block(2, 90, 45, [self::ex('Active Hang', '20-25s'), self::ex('Tuck Front Lever', '5-10s'), self::ex('Reverse Plank', '25-30s'), self::ex('Frog Stand', '20-25s'), self::ex('Glute Bridge', '12'), self::ex('Hollow Body Hold', '25-30s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Tuck FL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '10-15s'), self::ex('Frog Stand', '25-30s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Active Hang', '25-30s'), self::ex('Tuck Front Lever', '10-15s'), self::ex('Reverse Plank', '30-35s'), self::ex('Frog Stand', '25-30s'), self::ex('Glute Bridge', '15'), self::ex('Hollow Body Hold', '30-35s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Tuck FL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '15-20s'), self::ex('Frog Stand', '30-35s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Active Hang', '30-35s'), self::ex('Tuck Front Lever', '15-20s'), self::ex('Reverse Plank', '35-40s'), self::ex('Frog Stand', '30-35s'), self::ex('Glute Bridge', '15'), self::ex('Hollow Body Hold', '35-40s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Tuck FL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '5-8s'), self::ex('Frog Stand', '15-20s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '10-15s'), self::ex('Dead Bug', '8-10')]), self::block(2, 90, 45, [self::ex('Active Hang', '15-20s'), self::ex('Tuck Front Lever', '5-8s'), self::ex('Reverse Plank', '20-25s'), self::ex('Frog Stand', '15-20s'), self::ex('Glute Bridge', '10'), self::ex('Hollow Body Hold', '20-25s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '10-15s'), self::ex('Tuck Planche', '5-8s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '12-15s'), self::ex('Dead Bug', '10-12')]), self::block(2, 90, 45, [self::ex('Tuck Planche', '5-8s'), self::ex('Active Hang', '25-30s'), self::ex('Tuck Front Lever', '10-15s'), self::ex('Reverse Plank', '30-35s'), self::ex('Frog Stand', '25-30s'), self::ex('Hollow Body Hold', '30-35s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '15-20s'), self::ex('Tuck Planche', '8-10s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Tuck Planche', '8-10s'), self::ex('Active Hang', '30-35s'), self::ex('Tuck Front Lever', '15-20s'), self::ex('Reverse Plank', '35-40s'), self::ex('Frog Stand', '30-35s'), self::ex('Hollow Body Hold', '35-40s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '20-25s'), self::ex('Tuck Planche', '10-12s'), self::ex('Hollow Body Hold', '40-45s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Tuck Planche', '10-12s'), self::ex('Active Hang', '35-40s'), self::ex('Tuck Front Lever', '20-25s'), self::ex('Reverse Plank', '40-45s'), self::ex('Frog Stand', '35-40s'), self::ex('Hollow Body Hold', '40-45s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Tuck Front Lever', '8-10s'), self::ex('Tuck Planche', '5-6s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '10-15s'), self::ex('Dead Bug', '8-10')]), self::block(2, 90, 45, [self::ex('Tuck Planche', '5-6s'), self::ex('Active Hang', '15-20s'), self::ex('Tuck Front Lever', '8-10s'), self::ex('Reverse Plank', '20-25s'), self::ex('Frog Stand', '15-20s'), self::ex('Hollow Body Hold', '20-25s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Adv Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '5-10s'), self::ex('Tuck Planche', '8-10s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dead Bug', '10-12')]), self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '5-10s'), self::ex('Tuck Planche', '8-10s'), self::ex('Active Hang', '30-35s'), self::ex('Reverse Plank', '35-40s'), self::ex('Frog Stand', '30-35s'), self::ex('Hollow Body Hold', '35-40s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Adv Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '10-15s'), self::ex('Tuck Planche', '10-12s'), self::ex('Hollow Body Hold', '40-45s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '10-15s'), self::ex('Tuck Planche', '10-12s'), self::ex('Active Hang', '35-40s'), self::ex('Reverse Plank', '40-45s'), self::ex('Frog Stand', '35-40s'), self::ex('Hollow Body Hold', '40-45s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Adv Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '15-20s'), self::ex('Tuck Planche', '12-15s'), self::ex('Hollow Body Hold', '45-50s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '20-25s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '15-20s'), self::ex('Tuck Planche', '12-15s'), self::ex('Active Hang', '40-45s'), self::ex('Reverse Plank', '45-50s'), self::ex('Frog Stand', '40-45s'), self::ex('Hollow Body Hold', '45-50s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Adv Tuck FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '5-8s'), self::ex('Tuck Planche', '5-6s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '10-15s'), self::ex('Dead Bug', '8-10')]), self::block(2, 90, 45, [self::ex('Advanced Tuck Front Lever', '5-8s'), self::ex('Tuck Planche', '5-6s'), self::ex('Active Hang', '15-20s'), self::ex('Reverse Plank', '20-25s'), self::ex('Frog Stand', '15-20s'), self::ex('Hollow Body Hold', '20-25s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Straddle FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '3-5s'), self::ex('Straddle Planche', '3-5s'), self::ex('Hollow Body Hold', '40-45s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dead Bug', '10-12')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '3-5s'), self::ex('Straddle Planche', '3-5s'), self::ex('Active Hang', '35-40s'), self::ex('Reverse Plank', '40-45s'), self::ex('Frog Stand', '35-40s'), self::ex('Hollow Body Hold', '40-45s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Straddle FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-8s'), self::ex('Straddle Planche', '5-8s'), self::ex('Hollow Body Hold', '45-50s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-8s'), self::ex('Straddle Planche', '5-8s'), self::ex('Active Hang', '40-45s'), self::ex('Reverse Plank', '45-50s'), self::ex('Frog Stand', '40-45s'), self::ex('Hollow Body Hold', '45-50s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Straddle FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Straddle Planche', '8-10s'), self::ex('Hollow Body Hold', '50-55s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '20-25s'), self::ex('Dead Bug', '12-15')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Straddle Planche', '8-10s'), self::ex('Active Hang', '45-50s'), self::ex('Reverse Plank', '50-55s'), self::ex('Frog Stand', '45-50s'), self::ex('Hollow Body Hold', '50-55s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Straddle FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '3-4s'), self::ex('Straddle Planche', '3-4s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '10-15s'), self::ex('Dead Bug', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '3-4s'), self::ex('Straddle Planche', '3-4s'), self::ex('Active Hang', '15-20s'), self::ex('Reverse Plank', '20-25s'), self::ex('Frog Stand', '15-20s'), self::ex('Hollow Body Hold', '20-25s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Full FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-8s'), self::ex('Planche', '3-5s'), self::ex('Hollow Body Hold', '45-50s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dragon Flag Negative', '3-5')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-8s'), self::ex('Planche', '3-5s'), self::ex('Active Hang', '40-45s'), self::ex('Reverse Plank', '45-50s'), self::ex('Freestanding Handstand', '10-15s'), self::ex('Hollow Body Hold', '45-50s')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Full FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '5-8s'), self::ex('Hollow Body Hold', '50-55s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '20-25s'), self::ex('Dragon Flag Negative', '5-8')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '5-8s'), self::ex('Active Hang', '45-50s'), self::ex('Reverse Plank', '50-55s'), self::ex('Freestanding Handstand', '15-20s'), self::ex('Hollow Body Hold', '50-55s')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Full FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '8-10s'), self::ex('Hollow Body Hold', '55-60s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '22-25s'), self::ex('Dragon Flag Negative', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '8-10s'), self::ex('Active Hang', '50-55s'), self::ex('Reverse Plank', '55-60s'), self::ex('Freestanding Handstand', '20-25s'), self::ex('Hollow Body Hold', '55-60s')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Full FL/PL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '3-5s'), self::ex('Planche', '3-4s'), self::ex('Hollow Body Hold', '25-30s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '12-15s'), self::ex('Dragon Flag Negative', '3-5')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '3-5s'), self::ex('Planche', '3-4s'), self::ex('Active Hang', '20-25s'), self::ex('Reverse Plank', '25-30s'), self::ex('Freestanding Handstand', '10-15s'), self::ex('Hollow Body Hold', '25-30s')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '5-8s'), self::ex('Hollow Body Hold', '50-55s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '20-25s'), self::ex('Dragon Flag Negative', '5-8')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '5-8s'), self::ex('Active Hang', '45-50s'), self::ex('Reverse Plank', '50-55s'), self::ex('Freestanding Handstand', '15-20s'), self::ex('Hollow Body Hold', '50-55s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '5-8s'), self::ex('Active Hang', '30-35s'), self::ex('Reverse Plank', '35-40s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Dragon Flag Negative', '5-8'), self::ex('Hollow Body Rock', '8-10')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '8-10s'), self::ex('Hollow Body Hold', '55-60s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '22-25s'), self::ex('Dragon Flag Negative', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '8-10s'), self::ex('Active Hang', '50-55s'), self::ex('Reverse Plank', '55-60s'), self::ex('Freestanding Handstand', '20-25s'), self::ex('Hollow Body Hold', '55-60s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '8-10s'), self::ex('Active Hang', '35-40s'), self::ex('Reverse Plank', '40-45s'), self::ex('Hollow Body Hold', '40-45s'), self::ex('Dragon Flag Negative', '8-10'), self::ex('Hollow Body Rock', '10-12')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '12-15s'), self::ex('Planche', '10-12s'), self::ex('Hollow Body Hold', '60-65s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '25-28s'), self::ex('Dragon Flag Negative', '10-12')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '12-15s'), self::ex('Planche', '10-12s'), self::ex('Active Hang', '55-60s'), self::ex('Reverse Plank', '60-65s'), self::ex('Freestanding Handstand', '25-30s'), self::ex('Hollow Body Hold', '60-65s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '10-12s'), self::ex('Active Hang', '40-45s'), self::ex('Reverse Plank', '45-50s'), self::ex('Hollow Body Hold', '45-50s'), self::ex('Dragon Flag Negative', '10-12'), self::ex('Hollow Body Rock', '10-12')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-6s'), self::ex('Planche', '3-5s'), self::ex('Hollow Body Hold', '25-30s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '12-15s'), self::ex('Dragon Flag Negative', '5-8')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-6s'), self::ex('Planche', '3-5s'), self::ex('Active Hang', '20-25s'), self::ex('Reverse Plank', '25-30s'), self::ex('Freestanding Handstand', '15-20s'), self::ex('Hollow Body Hold', '25-30s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '3-5s'), self::ex('Active Hang', '15-20s'), self::ex('Reverse Plank', '20-25s'), self::ex('Hollow Body Hold', '20-25s'), self::ex('Dragon Flag Negative', '5-8'), self::ex('Hollow Body Rock', '8-10')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '8-10s'), self::ex('Hollow Body Hold', '55-60s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '22-25s'), self::ex('Dragon Flag Negative', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '8-10s'), self::ex('Active Hang', '50-55s'), self::ex('Reverse Plank', '55-60s'), self::ex('Freestanding Handstand', '20-25s'), self::ex('Hollow Body Hold', '55-60s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '8-10s'), self::ex('Active Hang', '40-45s'), self::ex('Reverse Plank', '45-50s'), self::ex('Hollow Body Hold', '45-50s'), self::ex('Dragon Flag Negative', '8-10'), self::ex('Hollow Body Rock', '8-10')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '12-15s'), self::ex('Planche', '10-12s'), self::ex('Hollow Body Hold', '60-65s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '25-28s'), self::ex('Dragon Flag Negative', '10-12')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '12-15s'), self::ex('Planche', '10-12s'), self::ex('Active Hang', '55-60s'), self::ex('Reverse Plank', '60-65s'), self::ex('Freestanding Handstand', '25-30s'), self::ex('Hollow Body Hold', '60-65s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '10-12s'), self::ex('Active Hang', '45-50s'), self::ex('Reverse Plank', '50-55s'), self::ex('Hollow Body Hold', '50-55s'), self::ex('Dragon Flag Negative', '10-12'), self::ex('Hollow Body Rock', '10-12')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '15-18s'), self::ex('Planche', '12-15s'), self::ex('Hollow Body Hold', '65-70s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '28-30s'), self::ex('Dragon Flag Negative', '12-15')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '15-18s'), self::ex('Planche', '12-15s'), self::ex('Active Hang', '60-65s'), self::ex('Reverse Plank', '65-70s'), self::ex('Freestanding Handstand', '30-35s'), self::ex('Hollow Body Hold', '65-70s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '12-15s'), self::ex('Active Hang', '50-55s'), self::ex('Reverse Plank', '55-60s'), self::ex('Hollow Body Hold', '55-60s'), self::ex('Dragon Flag Negative', '12-15'), self::ex('Hollow Body Rock', '10-12')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-8s'), self::ex('Planche', '5-6s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dragon Flag Negative', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '5-8s'), self::ex('Planche', '5-6s'), self::ex('Active Hang', '25-30s'), self::ex('Reverse Plank', '30-35s'), self::ex('Freestanding Handstand', '20-25s'), self::ex('Hollow Body Hold', '30-35s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '5-6s'), self::ex('Active Hang', '20-25s'), self::ex('Reverse Plank', '25-30s'), self::ex('Hollow Body Hold', '25-30s'), self::ex('Dragon Flag Negative', '8-10'), self::ex('Hollow Body Rock', '8-10')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '12-15s'), self::ex('Planche', '10-12s'), self::ex('Hollow Body Hold', '60-65s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '25-28s'), self::ex('Dragon Flag', '3-5')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '12-15s'), self::ex('Planche', '10-12s'), self::ex('Active Hang', '55-60s'), self::ex('Reverse Plank', '60-65s'), self::ex('Freestanding Handstand', '25-30s'), self::ex('Hollow Body Hold', '60-65s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '10-12s'), self::ex('Active Hang', '45-50s'), self::ex('Reverse Plank', '50-55s'), self::ex('Hollow Body Hold', '50-55s'), self::ex('Dragon Flag', '3-5'), self::ex('Hollow Body Rock', '8-10')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '15-18s'), self::ex('Planche', '12-15s'), self::ex('Hollow Body Hold', '65-70s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '28-30s'), self::ex('Dragon Flag', '5-8')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '15-18s'), self::ex('Planche', '12-15s'), self::ex('Active Hang', '60-65s'), self::ex('Reverse Plank', '65-70s'), self::ex('Freestanding Handstand', '30-35s'), self::ex('Hollow Body Hold', '65-70s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '12-15s'), self::ex('Active Hang', '50-55s'), self::ex('Reverse Plank', '55-60s'), self::ex('Hollow Body Hold', '55-60s'), self::ex('Dragon Flag', '5-8'), self::ex('Hollow Body Rock', '10-12')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '18-20s'), self::ex('Planche', '15-18s'), self::ex('Hollow Body Hold', '70-75s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '30-32s'), self::ex('Dragon Flag', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '18-20s'), self::ex('Planche', '15-18s'), self::ex('Active Hang', '65-70s'), self::ex('Reverse Plank', '70-75s'), self::ex('Freestanding Handstand', '35-40s'), self::ex('Hollow Body Hold', '70-75s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '15-18s'), self::ex('Active Hang', '55-60s'), self::ex('Reverse Plank', '60-65s'), self::ex('Hollow Body Hold', '60-65s'), self::ex('Dragon Flag', '8-10'), self::ex('Hollow Body Rock', '10-12')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '5-8s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '15-20s'), self::ex('Dragon Flag', '3-5')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '5-8s'), self::ex('Active Hang', '30-35s'), self::ex('Reverse Plank', '35-40s'), self::ex('Freestanding Handstand', '25-30s'), self::ex('Hollow Body Hold', '35-40s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '5-8s'), self::ex('Active Hang', '25-30s'), self::ex('Reverse Plank', '30-35s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('Dragon Flag', '3-5'), self::ex('Hollow Body Rock', '8-10')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '15-18s'), self::ex('Planche', '12-15s'), self::ex('Hollow Body Hold', '65-70s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '28-30s'), self::ex('Dragon Flag', '5-8')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '15-18s'), self::ex('Planche', '12-15s'), self::ex('Active Hang', '60-65s'), self::ex('Reverse Plank', '65-70s'), self::ex('Freestanding Handstand', '30-35s'), self::ex('Hollow Body Hold', '65-70s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '12-15s'), self::ex('Active Hang', '50-55s'), self::ex('Reverse Plank', '55-60s'), self::ex('Hollow Body Hold', '55-60s'), self::ex('Dragon Flag', '5-8'), self::ex('Hollow Body Rock', '8-10')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '18-20s'), self::ex('Planche', '15-18s'), self::ex('Hollow Body Hold', '70-75s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '30-32s'), self::ex('Dragon Flag', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '18-20s'), self::ex('Planche', '15-18s'), self::ex('Active Hang', '65-70s'), self::ex('Reverse Plank', '70-75s'), self::ex('Freestanding Handstand', '35-40s'), self::ex('Hollow Body Hold', '70-75s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '15-18s'), self::ex('Active Hang', '55-60s'), self::ex('Reverse Plank', '60-65s'), self::ex('Hollow Body Hold', '60-65s'), self::ex('Dragon Flag', '8-10'), self::ex('Hollow Body Rock', '10-12')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '20-25s'), self::ex('Planche', '18-20s'), self::ex('Hollow Body Hold', '75-80s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '32-35s'), self::ex('Dragon Flag', '10-12')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '20-25s'), self::ex('Planche', '18-20s'), self::ex('Active Hang', '70-75s'), self::ex('Reverse Plank', '75-80s'), self::ex('Freestanding Handstand', '40-45s'), self::ex('Hollow Body Hold', '75-80s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '18-20s'), self::ex('Active Hang', '60-65s'), self::ex('Reverse Plank', '65-70s'), self::ex('Hollow Body Hold', '65-70s'), self::ex('Dragon Flag', '10-12'), self::ex('Hollow Body Rock', '10-12')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '8-10s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dragon Flag', '5-8')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '8-10s'), self::ex('Planche', '8-10s'), self::ex('Active Hang', '30-35s'), self::ex('Reverse Plank', '35-40s'), self::ex('Freestanding Handstand', '30-35s'), self::ex('Hollow Body Hold', '35-40s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '8-10s'), self::ex('Active Hang', '25-30s'), self::ex('Reverse Plank', '30-35s'), self::ex('Hollow Body Hold', '30-35s'), self::ex('Dragon Flag', '5-8'), self::ex('Hollow Body Rock', '8-10')])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '18-20s'), self::ex('Planche', '15-18s'), self::ex('Hollow Body Hold', '70-75s'), self::ex('Hollow Body Rock', '10-12'), self::ex('Pseudo Planche Hold', '30-32s'), self::ex('Dragon Flag', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '18-20s'), self::ex('Planche', '15-18s'), self::ex('Active Hang', '65-70s'), self::ex('Reverse Plank', '70-75s'), self::ex('Freestanding Handstand', '35-40s'), self::ex('Hollow Body Hold', '70-75s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '15-18s'), self::ex('Active Hang', '55-60s'), self::ex('Reverse Plank', '60-65s'), self::ex('Hollow Body Hold', '60-65s'), self::ex('Dragon Flag', '8-10'), self::ex('Hollow Body Rock', '8-10')])]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '20-25s'), self::ex('Planche', '18-20s'), self::ex('Hollow Body Hold', '75-80s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '32-35s'), self::ex('Dragon Flag', '10-12')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '20-25s'), self::ex('Planche', '18-20s'), self::ex('Active Hang', '70-75s'), self::ex('Reverse Plank', '75-80s'), self::ex('Freestanding Handstand', '40-45s'), self::ex('Hollow Body Hold', '75-80s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '18-20s'), self::ex('Active Hang', '60-65s'), self::ex('Reverse Plank', '65-70s'), self::ex('Hollow Body Hold', '65-70s'), self::ex('Dragon Flag', '10-12'), self::ex('Hollow Body Rock', '10-12')])]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '25-30s'), self::ex('Planche', '20-25s'), self::ex('Hollow Body Hold', '80-85s'), self::ex('Hollow Body Rock', '12-15'), self::ex('Pseudo Planche Hold', '35-38s'), self::ex('Dragon Flag', '12-15')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '25-30s'), self::ex('Planche', '20-25s'), self::ex('Active Hang', '75-80s'), self::ex('Reverse Plank', '80-85s'), self::ex('Freestanding Handstand', '45-50s'), self::ex('Hollow Body Hold', '80-85s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '20-25s'), self::ex('Active Hang', '65-70s'), self::ex('Reverse Plank', '70-75s'), self::ex('Hollow Body Hold', '70-75s'), self::ex('Dragon Flag', '12-15'), self::ex('Hollow Body Rock', '10-12')])]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Skill + Core + Full FL/PL + BL', 'blocks' => [self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '10-12s'), self::ex('Hollow Body Hold', '40-45s'), self::ex('Hollow Body Rock', '8-10'), self::ex('Pseudo Planche Hold', '18-20s'), self::ex('Dragon Flag', '8-10')]), self::block(2, 90, 45, [self::ex('Full Front Lever Hold', '10-12s'), self::ex('Planche', '10-12s'), self::ex('Active Hang', '35-40s'), self::ex('Reverse Plank', '40-45s'), self::ex('Freestanding Handstand', '35-40s'), self::ex('Hollow Body Hold', '40-45s')]), self::block(2, 90, 45, [self::ex('Back Lever Hold', '10-12s'), self::ex('Active Hang', '30-35s'), self::ex('Reverse Plank', '35-40s'), self::ex('Hollow Body Hold', '35-40s'), self::ex('Dragon Flag', '8-10'), self::ex('Hollow Body Rock', '8-10')])]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
