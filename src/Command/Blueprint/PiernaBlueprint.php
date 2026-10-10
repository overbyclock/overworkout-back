<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Pierna Mastery (8 niveles).
 * Especialización del Master v4: la pieza de tren inferior que faltaba.
 * Dosis tomadas de las sesiones C de N3-N5 (pistol asistida y box pistol),
 * hito de pistol del N5, test N10 (pistol 3/pierna), N12-N13 (dragon pistol,
 * shrimp y Nordic curl 3 como mínimo de test) y rangos N16/Olimpo.
 * Estructura: 4 sesiones por fase (A=Strength, B=Pistol, C=Posterior y Nordic,
 * D=Core y movilidad). Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a rodilla y tobillo:
 * - Molestia rotuliana = regresión inmediata un peldaño (pistol -> box pistol).
 * - El Nordic se construye con excéntrica primero, nunca forzando la concentrica.
 * - La movilidad de tobillo es puerta, no opción: sin ella el pistol se rompe.
 */
class PiernaBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Pierna a Una Pierna', 'title' => 'Base de Pierna a Una Pierna', 'description' => 'Construir la base de fuerza bilateral y unilateral inicial: sentadillas, lunges, step-ups y RDL a una pierna', 'objective' => 'Consolidar sentadilla aéreo 20-30 reps, lunge y step-up limpios y primer RDL a una pierna sin apoyos', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Air Squat(20), Stationary Lunge(10/lado)', 'skillFocus' => 'Air Squat, Stationary Lunge, Single Leg Romanian Deadlift'],
            2 => ['name' => 'Nivel 2: Pistol Asistido', 'title' => 'Pistol Asistido', 'description' => 'Primer trabajo serio a una pierna: pistol al cajón, step-ups altos y movilidad de tobillo (dosis N3-N5 del Master)', 'objective' => 'Box pistol 5-8/pierna con asistencia decreciente y step-up alto 10-12/pierna', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Air Squat(30), Stationary Lunge(15/lado), Single Leg Romanian Deadlift(8/lado)', 'skillFocus' => 'Box Pistol Squat, High Step Up, Knee-to-Wall Rocks'],
            3 => ['name' => 'Nivel 3: Primer Pistol', 'title' => 'Primer Pistol', 'description' => 'Del pistol asistido mínimo al primer pistol completo (hito N5 del Master); entra el shrimp asistido', 'objective' => 'Conseguir el primer pistol estricto por pierna y consolidar box pistol 8-10', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Box Pistol Squat(5/lado), High Step Up(10/lado)', 'skillFocus' => 'Pistol Squat, Box Pistol Squat, Shrimp Squat'],
            4 => ['name' => 'Nivel 4: Pistol Sólido', 'title' => 'Pistol Sólido', 'description' => 'Pistol 3-5 por pierna (marca del test N10 del Master), shrimp asistido y primera introducción al Nordic negativo', 'objective' => 'Pistol 3-5/pierna estricto, shrimp 5-8 asistido y 2-3 negativas de Nordic con rango controlado', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Pistol Squat(1/lado), Box Pistol Squat(8/lado), Shrimp Squat(3/lado)', 'skillFocus' => 'Pistol Squat, Shrimp Squat, Nordic Curl Negative'],
            5 => ['name' => 'Nivel 5: Pistol Volumen', 'title' => 'Pistol Volumen', 'description' => 'Volumen de pistol 5-8, entrada del dragon pistol con asistencia de cajón y Nordic negatives 3-5', 'objective' => 'Pistol 5-8/pierna con volumen real, dragon pistol 3-5 al cajón y negativas de Nordic 3-5', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Pistol Squat(3/lado), Shrimp Squat(5/lado), Nordic Curl Negative(2)', 'skillFocus' => 'Pistol Squat, Dragon Pistol Squat, Nordic Curl Negative'],
            6 => ['name' => 'Nivel 6: Primer Nordic', 'title' => 'Primer Nordic', 'description' => 'Del Nordic negativo al primer Nordic curl estricto (mínimo de test del N13 del Master); pistol 8-10', 'objective' => 'Conseguir el primer Nordic curl estricto y sostener pistol 8-10/pierna', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Pistol Squat(5/lado), Nordic Curl Negative(3), Dragon Pistol Squat(3/lado)', 'skillFocus' => 'Nordic Curl, Pistol Squat, Dragon Pistol Squat'],
            7 => ['name' => 'Nivel 7: Fuerza Unilateral Pesada', 'title' => 'Fuerza Unilateral Pesada', 'description' => 'Nordic 3-5, dragon pistol consolidado y RDL a una pierna pesado', 'objective' => 'Nordic curl 3-5 estrictos, dragon pistol 5-8 y RDL a una pierna 12-15 con carga', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Nordic Curl(1), Pistol Squat(8/lado), Dragon Pistol Squat(5/lado)', 'skillFocus' => 'Nordic Curl, Dragon Pistol Squat, Single Leg Romanian Deadlift'],
            8 => ['name' => 'Nivel 8: Dominio', 'title' => 'Dominio', 'description' => 'Élite del tren inferior: Nordic 5-8, dragon pistol 3-5 y pistol 10-15 (rangos N16/Olimpo del Master)', 'objective' => 'Nordic curl 5-8, dragon pistol 3-5 libre y pistol 12-15/pierna', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Nordic Curl(3), Pistol Squat(10/lado), Dragon Pistol Squat(3/lado)', 'skillFocus' => 'Nordic Curl, Dragon Pistol Squat, Pistol Squat'],
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
    // SESSION A — STRENGTH (básicos pesados bilaterales y unilaterales)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Base de fuerza bilateral: sentadilla, lunge y step-up con técnica impecable', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '15-20', 3), self::ex('Stationary Lunge', '8/lado', 3), self::ex('High Step Up', '8/lado', 3), self::ex('Wall Sit', '20-30s', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de los básicos', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '20-25', 3), self::ex('Stationary Lunge', '10/lado', 3), self::ex('High Step Up', '10/lado', 3), self::ex('Wall Sit', '30-40s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '25-30', 4), self::ex('Stationary Lunge', '12/lado', 4), self::ex('High Step Up', '10-12/lado', 4), self::ex('Wall Sit', '40-45s', 2)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '12-15', 2), self::ex('Stationary Lunge', '6/lado', 2), self::ex('High Step Up', '6/lado', 2), self::ex('Wall Sit', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Básicos pesados unilaterales: lunge alto y RDL a una pierna', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '25-30', 3), self::ex('Stationary Lunge', '12-15/lado', 3), self::ex('Single Leg Romanian Deadlift', '8/lado', 3), self::ex('Bulgarian Split Squat', '6-8/lado', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '30-35', 3), self::ex('Stationary Lunge', '15/lado', 3), self::ex('Single Leg Romanian Deadlift', '10/lado', 3), self::ex('Bulgarian Split Squat', '8-10/lado', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '35-40', 4), self::ex('Stationary Lunge', '15-18/lado', 4), self::ex('Single Leg Romanian Deadlift', '12/lado', 4), self::ex('Bulgarian Split Squat', '10-12/lado', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Air Squat', '15', 2), self::ex('Stationary Lunge', '8/lado', 2), self::ex('Single Leg Romanian Deadlift', '6/lado', 2), self::ex('Bulgarian Split Squat', '6/lado', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'RDL unilateral y búlgaras: la fuerza posterior que sostiene el pistol', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '8-10/lado', 3), self::ex('Single Leg Romanian Deadlift', '10-12/lado', 3), self::ex('Side Lunge', '8/lado', 3), self::ex('Wall Sit', '45-60s', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '10-12/lado', 3), self::ex('Single Leg Romanian Deadlift', '12/lado', 3), self::ex('Side Lunge', '10/lado', 3), self::ex('Single Leg Wall Sit', '30s/lado', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '12-15/lado', 4), self::ex('Single Leg Romanian Deadlift', '12-15/lado', 4), self::ex('Side Lunge', '10-12/lado', 4), self::ex('Single Leg Wall Sit', '40s/lado', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '6-8/lado', 2), self::ex('Single Leg Romanian Deadlift', '8/lado', 2), self::ex('Side Lunge', '6/lado', 2), self::ex('Wall Sit', '30s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Unilateral pesado: búlgaras y RDL como prioridad (dosis C de N6-N7 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '10-12/lado', 3), self::ex('Single Leg Romanian Deadlift', '12/lado', 3), self::ex('Side Lunge Slide', '8/lado', 2), self::ex('Glute Bridge', '12-15', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '12-15/lado', 3), self::ex('Single Leg Romanian Deadlift', '12-15/lado', 3), self::ex('Side Lunge Slide', '10/lado', 2), self::ex('Glute Bridge', '15', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '15/lado', 4), self::ex('Single Leg Romanian Deadlift', '15/lado', 4), self::ex('Side Lunge Slide', '10-12/lado', 2), self::ex('Single Leg Glute Bridge', '10-12/lado', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '6-8/lado', 2), self::ex('Single Leg Romanian Deadlift', '8-10/lado', 2), self::ex('Side Lunge', '6/lado', 2), self::ex('Glute Bridge', '10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Básicos pesados para sostener el volumen de pistol del nivel', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '12-15/lado', 3), self::ex('Single Leg Romanian Deadlift', '12-15/lado', 3), self::ex('Cossack Squat', '8/lado', 2), self::ex('Single Leg Glute Bridge', '10-12/lado', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '15/lado', 3), self::ex('Single Leg Romanian Deadlift', '15/lado', 3), self::ex('Cossack Squat', '10/lado', 2), self::ex('Single Leg Glute Bridge', '12/lado', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '15-18/lado', 4), self::ex('Single Leg Romanian Deadlift', '15-18/lado', 4), self::ex('Cossack Squat', '10-12/lado', 3), self::ex('Single Leg Glute Bridge', '12-15/lado', 2)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '8/lado', 2), self::ex('Single Leg Romanian Deadlift', '10/lado', 2), self::ex('Cossack Squat', '6/lado', 2), self::ex('Glute Bridge', '10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza unilateral pesada que sostiene pistol 8-10 y el primer Nordic', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '15/lado', 3), self::ex('Single Leg Romanian Deadlift', '15/lado', 3), self::ex('Cossack Squat', '10/lado', 2), self::ex('Glute Ham Raise Negative', '3-4', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '15-18/lado', 3), self::ex('Single Leg Romanian Deadlift', '15-18/lado', 3), self::ex('Cossack Squat', '10-12/lado', 2), self::ex('Glute Ham Raise Negative', '4', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '18/lado', 4), self::ex('Single Leg Romanian Deadlift', '18/lado', 4), self::ex('Cossack Squat', '12/lado', 3), self::ex('Glute Ham Raise Negative', '4-5', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '8-10/lado', 2), self::ex('Single Leg Romanian Deadlift', '10-12/lado', 2), self::ex('Cossack Squat', '6/lado', 2), self::ex('Glute Ham Raise Negative', '2-3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'RDL a una pierna pesado y búlgaras altas (dosis de N12 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '15-18/lado', 3), self::ex('Single Leg Romanian Deadlift', '15-18/lado', 3), self::ex('Single Leg Glute Bridge', '12/lado', 2), self::ex('Glute Ham Raise Negative', '4-5', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '18/lado', 3), self::ex('Single Leg Romanian Deadlift', '18-20/lado', 3), self::ex('Single Leg Glute Bridge', '12-15/lado', 2), self::ex('Glute Ham Raise Negative', '5', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '18-20/lado', 4), self::ex('Single Leg Romanian Deadlift', '20/lado', 4), self::ex('Single Leg Glute Bridge', '15/lado', 2), self::ex('Glute Ham Raise Negative', '5-6', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '10/lado', 2), self::ex('Single Leg Romanian Deadlift', '12/lado', 2), self::ex('Single Leg Glute Bridge', '8/lado', 2), self::ex('Glute Ham Raise Negative', '3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza unilateral élite: básicos pesados del N15-N16 del Master', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '18-20/lado', 3), self::ex('Single Leg Romanian Deadlift', '20/lado', 3), self::ex('Cossack Squat with Load', '8/lado', 2), self::ex('Glute Ham Raise Negative', '5-6', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '20/lado', 3), self::ex('Single Leg Romanian Deadlift', '20-22/lado', 3), self::ex('Cossack Squat with Load', '10/lado', 2), self::ex('Glute Ham Raise Negative', '6', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Intensificación: pico del programa', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '20-22/lado', 4), self::ex('Single Leg Romanian Deadlift', '22/lado', 4), self::ex('Cossack Squat with Load', '10-12/lado', 3), self::ex('Glute Ham Raise Negative', '6-8', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Bulgarian Split Squat', '10-12/lado', 2), self::ex('Single Leg Romanian Deadlift', '12/lado', 2), self::ex('Cossack Squat', '6/lado', 2), self::ex('Glute Ham Raise Negative', '3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — PISTOL (progresión del pistol squat)
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
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Primer box pistol al cajón alto y step-ups como escalera hacia el pistol (dosis N3 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '3-4/lado', 3), self::ex('High Step Up', '8/lado', 3), self::ex('Air Squat', '15', 2), self::ex('Side Lunge', '6/lado', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Bajar el cajón y subir el step-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '4-5/lado', 3), self::ex('High Step Up', '10/lado', 3), self::ex('Air Squat', '18-20', 2), self::ex('Side Lunge', '8/lado', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Volumen: box pistol 5-6 y step-up alto', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '5-6/lado', 4), self::ex('High Step Up', '10-12/lado', 4), self::ex('Air Squat', '20', 2), self::ex('Side Lunge', '10/lado', 2)], 90)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '3/lado', 2), self::ex('High Step Up', '6/lado', 2), self::ex('Air Squat', '12', 2), self::ex('Side Lunge', '5/lado', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Box pistol 5-6 con asistencia decreciente y step-ups altos (dosis N4 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '5-6/lado', 3), self::ex('High Step Up', '10/lado', 3), self::ex('Pistol Squat Negative', '2-3/lado', 2), self::ex('Wall Sit', '30s', 2)], 90)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión: asistencia mínima y negativas limpias', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '6-7/lado', 3), self::ex('High Step Up', '12/lado', 3), self::ex('Pistol Squat Negative', '3/lado', 2), self::ex('Single Leg Wall Sit', '20s/lado', 2)], 90)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Volumen: box pistol 8 y step-up 12', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '8/lado', 4), self::ex('High Step Up', '12/lado', 4), self::ex('Pistol Squat Negative', '3-4/lado', 3), self::ex('Single Leg Wall Sit', '25s/lado', 2)], 120)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Box Pistol Squat', '4/lado', 2), self::ex('High Step Up', '6/lado', 2), self::ex('Pistol Squat Negative', '2/lado', 2), self::ex('Wall Sit', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Del pistol asistido mínimo al primer pistol (hito del N5 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '1/lado', 3), self::ex('Box Pistol Squat', '5-6/lado', 3), self::ex('Pistol Squat Negative', '3-4/lado', 2), self::ex('High Step Up', '10/lado', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión: primer pistol consolidado', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '1-2/lado', 3), self::ex('Box Pistol Squat', '6-8/lado', 3), self::ex('Pistol Squat Negative', '4/lado', 2), self::ex('High Step Up', '12/lado', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Volumen: 2-3 pistols y box pistol 8-10', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '2-3/lado', 4), self::ex('Box Pistol Squat', '8-10/lado', 3), self::ex('Pistol Squat Negative', '4-5/lado', 3), self::ex('Shrimp Squat', '2-3/lado', 2)], 120)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '1/lado', 2), self::ex('Box Pistol Squat', '4-5/lado', 2), self::ex('Pistol Squat Negative', '2/lado', 2), self::ex('High Step Up', '6/lado', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Pistol 3-5 (marca del test N10 del Master) con shrimp asistido', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '3-4/lado', 3), self::ex('Shrimp Squat', '4-5/lado', 3), self::ex('Box Pistol Squat', '5-6/lado', 2), self::ex('Single Leg Wall Sit', '25-30s/lado', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '4-5/lado', 3), self::ex('Shrimp Squat', '5-6/lado', 3), self::ex('Box Pistol Squat', '6-8/lado', 2), self::ex('Single Leg Wall Sit', '30s/lado', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Volumen: 5 pistols y shrimp 8', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '5/lado', 4), self::ex('Shrimp Squat', '6-8/lado', 4), self::ex('Box Pistol Squat', '8/lado', 2), self::ex('Single Leg Wall Sit', '30-35s/lado', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '2/lado', 2), self::ex('Shrimp Squat', '3/lado', 2), self::ex('Box Pistol Squat', '4/lado', 2), self::ex('Wall Sit', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Volumen de pistol 5-8 y entrada del dragon pistol con asistencia de cajón', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '5-6/lado', 3), self::ex('Dragon Pistol Squat', '2-3/lado', 3), self::ex('Shrimp Squat', '5-6/lado', 2), self::ex('Box Pistol Squat', '6-8/lado', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '6-8/lado', 3), self::ex('Dragon Pistol Squat', '3-5/lado', 3), self::ex('Shrimp Squat', '6-8/lado', 2), self::ex('Box Pistol Squat', '8/lado', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Volumen: 8 pistols y dragon pistol 5', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '8/lado', 4), self::ex('Dragon Pistol Squat', '5/lado', 4), self::ex('Shrimp Squat', '8/lado', 2), self::ex('Single Leg Wall Sit', '35s/lado', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '3-4/lado', 2), self::ex('Dragon Pistol Squat', '2/lado', 2), self::ex('Shrimp Squat', '4/lado', 2), self::ex('Box Pistol Squat', '5/lado', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Pistol 8-10 con volumen real mientras el Nordic estricto llega en la sesión C', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '8-10/lado', 3), self::ex('Dragon Pistol Squat', '3-5/lado', 3), self::ex('Shrimp Squat', '5-8/lado', 2), self::ex('Jump Squat', '8-10', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '10/lado', 3), self::ex('Dragon Pistol Squat', '5/lado', 3), self::ex('Shrimp Squat', '6-8/lado', 2), self::ex('Jump Squat', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Volumen: 10 pistols y dragon pistol 8', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '10/lado', 4), self::ex('Dragon Pistol Squat', '5-8/lado', 4), self::ex('Shrimp Squat', '8/lado', 2), self::ex('Jump Squat', '10-12', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '4-5/lado', 2), self::ex('Dragon Pistol Squat', '3/lado', 2), self::ex('Shrimp Squat', '4/lado', 2), self::ex('Air Squat', '10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Pistol 10-12 y dragon pistol consolidado (dosis de N12-N13 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '10-12/lado', 3), self::ex('Dragon Pistol Squat', '5-8/lado', 3), self::ex('Shrimp Squat', '8/lado', 2), self::ex('Jumping Pistol Squat', '2-3/lado', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '12/lado', 3), self::ex('Dragon Pistol Squat', '8/lado', 3), self::ex('Shrimp Squat', '8-10/lado', 2), self::ex('Jumping Pistol Squat', '3/lado', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '12/lado', 4), self::ex('Dragon Pistol Squat', '8-10/lado', 4), self::ex('Shrimp Squat', '10/lado', 2), self::ex('Jumping Pistol Squat', '3-4/lado', 2)], 150)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '5-6/lado', 2), self::ex('Dragon Pistol Squat', '4/lado', 2), self::ex('Shrimp Squat', '5/lado', 2), self::ex('Air Squat', '12', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Pistol Base', 'goal' => 'Dominio: pistol 12-15 y dragon pistol 3-5 libre (rangos N16/Olimpo)', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '12-15/lado', 3), self::ex('Dragon Pistol Squat', '3-5/lado', 3), self::ex('Shrimp Squat', '10/lado', 2), self::ex('Jumping Pistol Squat', '3-5/lado', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Pistol Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '15/lado', 3), self::ex('Dragon Pistol Squat', '4-5/lado', 3), self::ex('Shrimp Squat', '10-12/lado', 2), self::ex('Jumping Pistol Squat', '4-5/lado', 2)], 150)]],
            'intensification' => ['name' => 'Sesión B: Pistol Intensificación', 'goal' => 'Pico: 15 pistols y dragon pistol 5', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '15/lado', 4), self::ex('Dragon Pistol Squat', '5/lado', 4), self::ex('Shrimp Squat', '12/lado', 2), self::ex('Jumping Pistol Squat', '5/lado', 2)], 180)]],
            'deload' => ['name' => 'Sesión B: Pistol Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Pistol Squat', '6-8/lado', 2), self::ex('Dragon Pistol Squat', '2-3/lado', 2), self::ex('Shrimp Squat', '5/lado', 2), self::ex('Air Squat', '12', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — POSTERIOR Y NORDIC (isquios, glúteos y progresión del Nordic)
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
            'base' => ['name' => 'Sesión C: Posterior Base', 'goal' => 'Cadena posterior fuerte: RDL a una pierna y puente de glúteos (dosis C de N3 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '8/lado', 3), self::ex('Glute Bridge', '12', 3), self::ex('Standing Calf Raise', '12-15', 3), self::ex('Single Leg Calf Raise', '8/lado', 2)], 90)]],
            'progression' => ['name' => 'Sesión C: Posterior Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '10/lado', 3), self::ex('Glute Bridge', '15', 3), self::ex('Standing Calf Raise', '15', 3), self::ex('Single Leg Calf Raise', '10/lado', 2)], 90)]],
            'intensification' => ['name' => 'Sesión C: Posterior Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '12/lado', 4), self::ex('Glute Bridge', '15-18', 4), self::ex('Standing Calf Raise', '15-18', 3), self::ex('Single Leg Calf Raise', '12/lado', 2)], 90)]],
            'deload' => ['name' => 'Sesión C: Posterior Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '6/lado', 2), self::ex('Glute Bridge', '10', 2), self::ex('Standing Calf Raise', '10', 2), self::ex('Single Leg Calf Raise', '6/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Posterior Base', 'goal' => 'RDL a una pierna 8-12 como test del nivel; gemelos fuertes para el pistol', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '10/lado', 3), self::ex('Single Leg Glute Bridge', '10/lado', 3), self::ex('Standing Calf Raise', '15', 3), self::ex('Calf Raise on Step', '10-12', 2)], 90)]],
            'progression' => ['name' => 'Sesión C: Posterior Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '12/lado', 3), self::ex('Single Leg Glute Bridge', '12/lado', 3), self::ex('Standing Calf Raise', '15-18', 3), self::ex('Calf Raise on Step', '12', 2)], 90)]],
            'intensification' => ['name' => 'Sesión C: Posterior Intensificación', 'goal' => 'Volumen: RDL 12-15 por lado', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '12-15/lado', 4), self::ex('Single Leg Glute Bridge', '12-15/lado', 3), self::ex('Standing Calf Raise', '18-20', 3), self::ex('Calf Raise on Step', '12-15', 2)], 90)]],
            'deload' => ['name' => 'Sesión C: Posterior Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '8/lado', 2), self::ex('Single Leg Glute Bridge', '8/lado', 2), self::ex('Standing Calf Raise', '10', 2), self::ex('Calf Raise on Step', '8', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Posterior Base', 'goal' => 'Posterior en mantenimiento: el foco del nivel es el primer pistol (sesión B)', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '12/lado', 3), self::ex('Single Leg Glute Bridge', '12/lado', 2), self::ex('Standing Calf Raise', '15-18', 3), self::ex('Eccentric Calf Raise', '8', 2)], 90)]],
            'progression' => ['name' => 'Sesión C: Posterior Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '12-15/lado', 3), self::ex('Single Leg Glute Bridge', '12-15/lado', 2), self::ex('Standing Calf Raise', '18', 3), self::ex('Eccentric Calf Raise', '8-10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión C: Posterior Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '15/lado', 4), self::ex('Single Leg Glute Bridge', '15/lado', 3), self::ex('Standing Calf Raise', '20', 3), self::ex('Eccentric Calf Raise', '10', 2)], 90)]],
            'deload' => ['name' => 'Sesión C: Posterior Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Single Leg Romanian Deadlift', '8-10/lado', 2), self::ex('Single Leg Glute Bridge', '8/lado', 2), self::ex('Standing Calf Raise', '10', 2), self::ex('Eccentric Calf Raise', '6', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Posterior Base', 'goal' => 'Primera introducción al Nordic negativo: excéntrica primero, rango corto y controlado', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '2', 3), self::ex('Single Leg Romanian Deadlift', '12/lado', 3), self::ex('Single Leg Glute Bridge', '12/lado', 2), self::ex('Standing Calf Raise', '15-18', 2)], 120)]],
            'progression' => ['name' => 'Sesión C: Posterior Progresión', 'goal' => 'Progresión: negativas 2-3 con bajada de 4-5 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '2-3', 3), self::ex('Single Leg Romanian Deadlift', '12-15/lado', 3), self::ex('Single Leg Glute Bridge', '12-15/lado', 2), self::ex('Standing Calf Raise', '18', 2)], 120)]],
            'intensification' => ['name' => 'Sesión C: Posterior Intensificación', 'goal' => 'Volumen: 3 negativas a rango casi completo', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '3', 4), self::ex('Single Leg Romanian Deadlift', '15/lado', 3), self::ex('Single Leg Glute Bridge', '15/lado', 2), self::ex('Eccentric Calf Raise', '8', 2)], 120)]],
            'deload' => ['name' => 'Sesión C: Posterior Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '1-2', 2), self::ex('Single Leg Romanian Deadlift', '8/lado', 2), self::ex('Single Leg Glute Bridge', '8/lado', 2), self::ex('Standing Calf Raise', '10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Posterior Base', 'goal' => 'Nordic negatives 3-5 consolidados (dosis N12 del Master: excéntrica conservadora)', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '3', 3), self::ex('Glute Ham Raise Negative', '3-4', 3), self::ex('Single Leg Romanian Deadlift', '15/lado', 3), self::ex('Standing Calf Raise', '18-20', 2)], 120)]],
            'progression' => ['name' => 'Sesión C: Posterior Progresión', 'goal' => 'Progresión: negativas 3-5 a rango total', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '3-4', 3), self::ex('Glute Ham Raise Negative', '4', 3), self::ex('Single Leg Romanian Deadlift', '15-18/lado', 3), self::ex('Standing Calf Raise', '20', 2)], 120)]],
            'intensification' => ['name' => 'Sesión C: Posterior Intensificación', 'goal' => 'Volumen: 5 negativas de 5 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '5', 4), self::ex('Glute Ham Raise Negative', '4-5', 3), self::ex('Single Leg Romanian Deadlift', '18/lado', 3), self::ex('Eccentric Calf Raise', '10', 2)], 150)]],
            'deload' => ['name' => 'Sesión C: Posterior Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl Negative', '2', 2), self::ex('Glute Ham Raise Negative', '2-3', 2), self::ex('Single Leg Romanian Deadlift', '10/lado', 2), self::ex('Standing Calf Raise', '10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Nordic Base', 'goal' => 'Del Nordic negativo al primer Nordic curl estricto (mínimo del test N13 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '1', 3), self::ex('Nordic Curl Negative', '3-4', 3), self::ex('Nordic Curl with Band', '3-5', 3), self::ex('Glute Ham Raise Negative', '4-5', 2)], 150)]],
            'progression' => ['name' => 'Sesión C: Nordic Progresión', 'goal' => 'Progresión: 1-2 estrictos', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '1-2', 3), self::ex('Nordic Curl Negative', '4', 3), self::ex('Nordic Curl with Band', '4-6', 3), self::ex('Glute Ham Raise Negative', '5', 2)], 150)]],
            'intensification' => ['name' => 'Sesión C: Nordic Intensificación', 'goal' => 'Volumen: 3 estrictos (marca de test del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '3', 4), self::ex('Nordic Curl Negative', '4-5', 3), self::ex('Nordic Curl with Band', '5-6', 3), self::ex('Glute Ham Raise Negative', '5-6', 2)], 180)]],
            'deload' => ['name' => 'Sesión C: Nordic Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '1', 2), self::ex('Nordic Curl Negative', '2', 2), self::ex('Nordic Curl with Band', '3', 2), self::ex('Glute Ham Raise Negative', '3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Nordic Base', 'goal' => 'Nordic curl 3-5 estrictos con descanso completo', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '3-4', 3), self::ex('Nordic Curl Negative', '3-4', 3), self::ex('Single Leg Romanian Deadlift', '15-18/lado', 3), self::ex('Eccentric Calf Raise', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesión C: Nordic Progresión', 'goal' => 'Progresión: 4-5 estrictos', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '4-5', 3), self::ex('Nordic Curl Negative', '4', 3), self::ex('Single Leg Romanian Deadlift', '18/lado', 3), self::ex('Eccentric Calf Raise', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión C: Nordic Intensificación', 'goal' => 'Intensificación: 5 estrictos + RDL 18', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '5', 4), self::ex('Nordic Curl Negative', '4-5', 3), self::ex('Single Leg Romanian Deadlift', '18-20/lado', 3), self::ex('Eccentric Calf Raise', '10-12', 2)], 150)]],
            'deload' => ['name' => 'Sesión C: Nordic Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '1-2', 2), self::ex('Nordic Curl Negative', '2', 2), self::ex('Single Leg Romanian Deadlift', '10/lado', 2), self::ex('Eccentric Calf Raise', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Nordic Base', 'goal' => 'Nordic curl 5-8 estrictos: élite de isquios (rangos N16 del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '5-6', 3), self::ex('Nordic Curl Negative', '4-5', 3), self::ex('Single Leg Romanian Deadlift', '20/lado', 3), self::ex('Eccentric Calf Raise', '10-12', 2)], 150)]],
            'progression' => ['name' => 'Sesión C: Nordic Progresión', 'goal' => 'Progresión: 6-8 estrictos', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '6-8', 3), self::ex('Nordic Curl Negative', '5', 3), self::ex('Single Leg Romanian Deadlift', '20-22/lado', 3), self::ex('Eccentric Calf Raise', '12', 2)], 180)]],
            'intensification' => ['name' => 'Sesión C: Nordic Intensificación', 'goal' => 'Pico: 8 estrictos + RDL 22', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '8', 4), self::ex('Nordic Curl Negative', '5-6', 3), self::ex('Single Leg Romanian Deadlift', '22/lado', 3), self::ex('Eccentric Calf Raise', '12-15', 2)], 180)]],
            'deload' => ['name' => 'Sesión C: Nordic Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Nordic Curl', '2-3', 2), self::ex('Nordic Curl Negative', '2-3', 2), self::ex('Single Leg Romanian Deadlift', '12/lado', 2), self::ex('Eccentric Calf Raise', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — CORE Y MOVILIDAD (tobillo, cadera y Copenhagen)
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
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Tobillo y cadera: la movilidad es la puerta del pistol (Bloque 0 del Master)', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8/lado'), self::ex('Bent-Knee Soleus Stretch', '30s/lado'), self::ex('Calf Stretch Wall', '30s/lado'), self::ex('Kneeling Sit', '30s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Plank', '30s', 2), self::ex('Glute Bridge', '12', 2), self::ex('Miniband Lateral Walk', '10/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Calf Stretch Wall', '40s/lado'), self::ex('Kneeling Sit', '40s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Plank', '40s', 2), self::ex('Glute Bridge', '15', 2), self::ex('Miniband Lateral Walk', '12/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '40-45s/lado'), self::ex('Calf Stretch Wall', '45s/lado'), self::ex('Kneeling Sit', '45s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 3), self::ex('Plank', '45s', 2), self::ex('Glute Bridge', '15-18', 2), self::ex('Miniband Lateral Walk', '12-15/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8/lado'), self::ex('Bent-Knee Soleus Stretch', '30s/lado'), self::ex('Calf Stretch Wall', '30s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15s', 2), self::ex('Plank', '30s', 2), self::ex('Glute Bridge', '10', 2), self::ex('Miniband Lateral Walk', '8/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Tobillo como puerta del pistol: rocks 10-12 por lado', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '30-40s/lado'), self::ex('Calf Stretch Wall', '40s/lado'), self::ex('Kneeling Sit', '40s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Side Plank', '20s/lado', 2), self::ex('Glute Bridge', '15', 2), self::ex('Miniband Lateral Walk', '12/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Calf Stretch Wall', '45s/lado'), self::ex('Kneeling Sit', '45s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Side Plank', '25s/lado', 2), self::ex('Glute Bridge', '15-18', 2), self::ex('Miniband Lateral Walk', '15/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Volumen: rocks 12 por lado (test)', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45s/lado'), self::ex('Calf Stretch Wall', '45-60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Side Plank', '30s/lado', 2), self::ex('Glute Bridge', '18', 2), self::ex('Miniband Lateral Walk', '15/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8/lado'), self::ex('Bent-Knee Soleus Stretch', '30s/lado'), self::ex('Calf Stretch Wall', '30s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Side Plank', '15s/lado', 2), self::ex('Glute Bridge', '10-12', 2), self::ex('Miniband Lateral Walk', '8-10/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Movilidad en mantenimiento y primer Copenhagen: aductores del pistol', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Kneeling Sit', '40s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '15-20s/lado', 2), self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Side Plank', '20-25s/lado', 2), self::ex('Miniband Lateral Walk', '12/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45s/lado'), self::ex('Kneeling Sit', '45s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '20-25s/lado', 2), self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Side Plank', '25-30s/lado', 2), self::ex('Miniband Lateral Walk', '15/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45-60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '25-30s/lado', 3), self::ex('Hollow Body Hold', '30s', 2), self::ex('Side Plank', '30s/lado', 2), self::ex('Miniband Lateral Walk', '15/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8/lado'), self::ex('Bent-Knee Soleus Stretch', '30s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '10-15s/lado', 2), self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Side Plank', '15-20s/lado', 2), self::ex('Miniband Lateral Walk', '8-10/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Copenhagen y aductores fuertes: la rodilla del pistol viaja con ellos', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Calf Stretch Wall', '40s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '20-25s/lado', 2), self::ex('Copenhagen Plank Raise', '6/lado', 2), self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Side Plank', '25-30s/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45s/lado'), self::ex('Calf Stretch Wall', '45s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '25-30s/lado', 3), self::ex('Copenhagen Plank Raise', '6-8/lado', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Side Plank', '30s/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45-60s/lado'), self::ex('Calf Stretch Wall', '60s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '30-40s/lado', 3), self::ex('Copenhagen Plank Raise', '8/lado', 2), self::ex('Hollow Body Hold', '30-35s', 3), self::ex('Side Plank', '30-40s/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8/lado'), self::ex('Bent-Knee Soleus Stretch', '30s/lado'), self::ex('Calf Stretch Wall', '30s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '15-20s/lado', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Side Plank', '20s/lado', 2), self::ex('Miniband Lateral Walk', '10/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Copenhagen pesado y movilidad de cadera para el dragon pistol', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Kneeling Sit', '45s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '25-30s/lado', 3), self::ex('Copenhagen Plank Raise', '8-10/lado', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Side Plank', '30s/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '30-40s/lado', 3), self::ex('Copenhagen Plank Raise', '10/lado', 2), self::ex('Hollow Body Hold', '30-35s', 2), self::ex('Side Plank', '35-40s/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '40s/lado', 3), self::ex('Copenhagen Plank Raise', '10-12/lado', 3), self::ex('Hollow Body Hold', '35-40s', 3), self::ex('Side Plank', '40s/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8/lado'), self::ex('Bent-Knee Soleus Stretch', '30s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '20s/lado', 2), self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Side Plank', '20-25s/lado', 2), self::ex('Miniband Lateral Walk', '10/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Copenhagen y core: la base que protege la rodilla del Nordic', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '45s/lado'), self::ex('Kneeling Sit', '45s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '30-40s/lado', 3), self::ex('Copenhagen Plank Raise', '10/lado', 2), self::ex('Hollow Body Hold', '30-35s', 2), self::ex('Side Plank', '35-40s/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '45-60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '40s/lado', 3), self::ex('Copenhagen Plank Raise', '10-12/lado', 2), self::ex('Hollow Body Hold', '35-40s', 3), self::ex('Side Plank', '40-45s/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '40-45s/lado', 3), self::ex('Copenhagen Plank Raise', '12/lado', 3), self::ex('Hollow Body Hold', '40-45s', 3), self::ex('Side Plank', '45s/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8-10/lado'), self::ex('Bent-Knee Soleus Stretch', '30-40s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '25s/lado', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Side Plank', '25-30s/lado', 2), self::ex('Miniband Lateral Walk', '10-12/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Core y aductores en mantenimiento pesado (dosis D de N12-N13 del Master)', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '45s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '40s/lado', 3), self::ex('Copenhagen Plank Raise', '12/lado', 2), self::ex('Hollow Body Hold', '35-40s', 2), self::ex('Side Plank', '40-45s/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '45s/lado', 3), self::ex('Copenhagen Plank Raise', '12-15/lado', 3), self::ex('Hollow Body Hold', '40-45s', 3), self::ex('Side Plank', '45-60s/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '45-60s/lado', 3), self::ex('Copenhagen Plank Raise', '15/lado', 3), self::ex('Hollow Body Hold', '45s', 3), self::ex('Side Plank', '60s/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8-10/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '30s/lado', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Side Plank', '30s/lado', 2), self::ex('Miniband Lateral Walk', '10/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Core y Movilidad Base', 'goal' => 'Dominio de core y movilidad: el seguro de vida del tren inferior completo', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '10/lado'), self::ex('Bent-Knee Soleus Stretch', '45-60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '45s/lado', 3), self::ex('Copenhagen Plank Raise', '12-15/lado', 3), self::ex('Hollow Body Hold', '40-45s', 2), self::ex('Side Plank', '45-60s/lado', 2)], 60)]],
            'progression' => ['name' => 'Sesión D: Core y Movilidad Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '60s/lado', 3), self::ex('Copenhagen Plank Raise', '15/lado', 3), self::ex('Hollow Body Hold', '45s', 3), self::ex('Side Plank', '60s/lado', 2)], 60)]],
            'intensification' => ['name' => 'Sesión D: Core y Movilidad Intensificación', 'goal' => 'Pico: Copenhagen y core al máximo', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '12/lado'), self::ex('Bent-Knee Soleus Stretch', '60s/lado'), self::ex('Kneeling Sit', '60s'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '60-75s/lado', 3), self::ex('Copenhagen Plank Raise', '15-18/lado', 3), self::ex('Hollow Body Hold', '45-60s', 3), self::ex('Side Plank', '60-75s/lado', 2)], 60)]],
            'deload' => ['name' => 'Sesión D: Core y Movilidad Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 5, [self::ex('Knee-to-Wall Rocks', '8-10/lado'), self::ex('Bent-Knee Soleus Stretch', '40s/lado'), self::ex('Diaphragmatic Breathing', '5')], 5), self::block(1, 1, 45, [self::ex('Copenhagen Plank', '30-40s/lado', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Side Plank', '30-40s/lado', 2), self::ex('Miniband Lateral Walk', '10-12/lado', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
