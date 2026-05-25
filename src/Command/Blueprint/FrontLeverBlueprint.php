<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Front Lever Mastery (6 niveles).
 * Estructura: 4 sesiones por fase (A=Strength, B=Core, C=Prehab/Mobility, D=Skill/Play).
 * Fases: base, progression, intensification, deload.
 */
class FrontLeverBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Fundamentos de Traccion', 'title' => 'Fundamentos de Traccion', 'description' => 'Construir la base de fuerza de traccion y control escapular', 'objective' => 'Desarrollar fuerza de traccion, core y agarre suficientes para iniciar progresiones de front lever', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push-up(10), High Plank(30s)', 'skillFocus' => 'Active Hang, Scapular Pull Up, Core'],
            2 => ['name' => 'Nivel 2: Tuck Front Lever', 'title' => 'Tuck Front Lever', 'description' => 'Primer contacto con la progresion de front lever en posicion tuck', 'objective' => 'Dominar el tuck front lever hold y row con control total', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Active Hang(30s), Scapular Pull Up(10)', 'skillFocus' => 'Tuck Front Lever Hold, Tuck Front Lever Row'],
            3 => ['name' => 'Nivel 3: Advanced Tuck', 'title' => 'Advanced Tuck', 'description' => 'Abrir la posicion tuck y aumentar tiempo bajo tension', 'objective' => 'Transicionar de tuck cerrado a advanced tuck con espalda horizontal', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck FL Hold(15s), Tuck FL Row(8), Pull Up(8)', 'skillFocus' => 'Advanced Tuck FL Hold, Advanced Tuck FL Row'],
            4 => ['name' => 'Nivel 4: Straddle Front Lever', 'title' => 'Straddle Front Lever', 'description' => 'Introducir formas abiertas: straddle y one-leg', 'objective' => 'Lograr el straddle front lever hold y row con alineacion correcta', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Advanced Tuck FL Hold(15s), Advanced Tuck FL Row(8)', 'skillFocus' => 'Straddle FL Hold, Straddle FL Row'],
            5 => ['name' => 'Nivel 5: Full Front Lever', 'title' => 'Full Front Lever', 'description' => 'Cuerpo recto completo horizontal', 'objective' => 'Conseguir y consolidar el full front lever hold, row y raise', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle FL Hold(10s), Straddle FL Row(5)', 'skillFocus' => 'Full FL Hold, Full FL Row, Front Lever Raise'],
            6 => ['name' => 'Nivel 6: Dominio del Front Lever', 'title' => 'Dominio del Front Lever', 'description' => 'Consistencia, shapes avanzadas y transiciones', 'objective' => 'Convertir el front lever en un skill dominado con variedades y volumen', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Full FL Hold(10s), Full FL Row(5), Front Lever Raise(5)', 'skillFocus' => 'Full FL Hold extendido, Transiciones, Front Lever Pull'],
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
    // SESSION A — STRENGTH (Pull/Strength)
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
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza basica de traccion', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '10-12', 3), self::ex('Scapular Pull Up', '8-10', 3), self::ex('Standard Pull Up', '5-6', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '12-15', 3), self::ex('Scapular Pull Up', '10-12', 3), self::ex('Standard Pull Up', '6-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '15-18', 4), self::ex('Scapular Pull Up', '12-15', 4), self::ex('Standard Pull Up', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Australian Pull Up', '8-10', 2), self::ex('Scapular Pull Up', '6-8', 2), self::ex('Standard Pull Up', '4-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza de traccion', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Chin Up', '6-8', 3), self::ex('Tuck Front Lever Row', '5-6', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 3), self::ex('Chin Up', '8-10', 3), self::ex('Tuck Front Lever Row', '6-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '12-15', 4), self::ex('Chin Up', '10-12', 4), self::ex('Tuck Front Lever Row', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 2), self::ex('Chin Up', '5-6', 2), self::ex('Tuck Front Lever Row', '4-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza de traccion avanzada', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '6-8', 3), self::ex('Chin Up', '8-10', 3), self::ex('Advanced Tuck Front Lever Row', '5-6', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '8-10', 3), self::ex('Chin Up', '10-12', 3), self::ex('Advanced Tuck Front Lever Row', '6-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '10-12', 4), self::ex('Chin Up', '12-15', 4), self::ex('Advanced Tuck Front Lever Row', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '5-6', 2), self::ex('Chin Up', '6-8', 2), self::ex('Advanced Tuck Front Lever Row', '4-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza + shapes abiertas', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '8-10', 3), self::ex('Close Grip Pull Up', '6-8', 3), self::ex('Straddle Front Lever Row', '3-5', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '10-12', 3), self::ex('Close Grip Pull Up', '8-10', 3), self::ex('Straddle Front Lever Row', '5-6', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '12-15', 4), self::ex('Close Grip Pull Up', '10-12', 4), self::ex('Straddle Front Lever Row', '6-8', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Wide Grip Pull Up', '6-8', 2), self::ex('Close Grip Pull Up', '4-5', 2), self::ex('Straddle Front Lever Row', '3-5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Full front lever consolidado', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '5-6', 3), self::ex('Full Front Lever Row', '3-5', 3), self::ex('Front Lever Raise', '3-5', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '6-8', 3), self::ex('Full Front Lever Row', '5-6', 3), self::ex('Front Lever Raise', '5-6', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '8-10', 4), self::ex('Full Front Lever Row', '6-8', 4), self::ex('Front Lever Raise', '6-8', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Chin Up', '4-5', 2), self::ex('Full Front Lever Row', '3-5', 2), self::ex('Front Lever Raise', '3-5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza elite de traccion', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '5-6', 3), self::ex('Full Front Lever Row', '5-6', 3), self::ex('Front Lever Raise', '5-6', 3)], 150)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '6-8', 3), self::ex('Full Front Lever Row', '6-8', 3), self::ex('Front Lever Raise', '6-8', 3)], 150)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '8-10', 4), self::ex('Full Front Lever Row', '8-10', 4), self::ex('Front Lever Raise', '8-10', 4)], 150)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '4-5', 2), self::ex('Full Front Lever Row', '4-5', 2), self::ex('Front Lever Raise', '4-5', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — CORE (Core/Compression)
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
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Activacion core', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 3), self::ex('Dead Bug', '10-12', 3), self::ex('High Plank', '30-35s', 3)], 60)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Aumentar tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 3), self::ex('Dead Bug', '12-15', 3), self::ex('High Plank', '35-40s', 3)], 60)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 4), self::ex('Dead Bug', '12-15', 4), self::ex('High Plank', '40-45s', 4)], 60)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Dead Bug', '8-10', 2), self::ex('High Plank', '25-30s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core + compresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 3), self::ex('Tuck L-Sit', '10-15s', 3), self::ex('High Plank', '40-45s', 3)], 60)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Aumentar tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '35-40s', 3), self::ex('Tuck L-Sit', '15-20s', 3), self::ex('High Plank', '45-50s', 3)], 60)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '40-45s', 4), self::ex('Tuck L-Sit', '20-25s', 4), self::ex('High Plank', '50-55s', 4)], 60)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Tuck L-Sit', '8-10s', 2), self::ex('High Plank', '30-35s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core + compresion avanzada', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '40-45s', 3), self::ex('L-Sit', '20-25s', 3), self::ex('Hollow Body Rock', '12-15', 3)], 60)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '45-50s', 3), self::ex('L-Sit', '25-30s', 3), self::ex('Hollow Body Rock', '15-18', 3)], 60)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '50-55s', 4), self::ex('L-Sit', '30-35s', 4), self::ex('Hollow Body Rock', '18-20', 4)], 60)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('L-Sit', '10-15s', 2), self::ex('Hollow Body Rock', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core avanzado', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag Negative', '3-5', 3), self::ex('L-Sit', '20-25s', 3), self::ex('Hollow Body Rock', '12-15', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag Negative', '5-6', 3), self::ex('L-Sit', '25-30s', 3), self::ex('Hollow Body Rock', '15-18', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag Negative', '6-8', 4), self::ex('L-Sit', '30-35s', 4), self::ex('Hollow Body Rock', '18-20', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag Negative', '3-5', 2), self::ex('L-Sit', '15-20s', 2), self::ex('Hollow Body Rock', '8-10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core elite', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '3-5', 3), self::ex('L-Sit', '30-35s', 3), self::ex('Front Lever Raise', '3-5', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '5-8', 3), self::ex('L-Sit', '35-40s', 3), self::ex('Front Lever Raise', '5-6', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '8-10', 4), self::ex('L-Sit', '40-45s', 4), self::ex('Front Lever Raise', '6-8', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '3-5', 2), self::ex('L-Sit', '20-25s', 2), self::ex('Front Lever Raise', '3-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core maximo', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '8-10', 3), self::ex('L-Sit', '40-45s', 3), self::ex('Hollow Body Press', '5-8', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '10-12', 3), self::ex('L-Sit', '45-50s', 3), self::ex('Hollow Body Press', '6-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '12-15', 4), self::ex('L-Sit', '50-55s', 4), self::ex('Hollow Body Press', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Dragon Flag', '5-8', 2), self::ex('L-Sit', '25-30s', 2), self::ex('Hollow Body Press', '4-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB/MOBILITY (Hombros, codos, escapulas)
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
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab basico', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 3), self::ex('German Hang', '15-20s', 3), self::ex('Skin The Cat', '5-8', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Aumentar reps y tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('German Hang', '20-25s', 3), self::ex('Skin The Cat', '8-10', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 4), self::ex('German Hang', '25-30s', 4), self::ex('Skin The Cat', '10-12', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '8-10', 2), self::ex('German Hang', '10-15s', 2), self::ex('Skin The Cat', '5-6', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('German Hang', '20-25s', 3), self::ex('Skin The Cat', '8-10', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('German Hang', '25-30s', 3), self::ex('Skin The Cat', '10-12', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20-22', 4), self::ex('German Hang', '30-35s', 4), self::ex('Skin The Cat', '12-15', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '8-10', 2), self::ex('German Hang', '15-20s', 2), self::ex('Skin The Cat', '5-8', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab basico', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('German Hang', '20-25s', 3), self::ex('Skin The Cat', '10-12', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20-22', 3), self::ex('German Hang', '25-30s', 3), self::ex('Skin The Cat', '12-15', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '22-25', 4), self::ex('German Hang', '30-35s', 4), self::ex('Skin The Cat', '15-18', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('German Hang', '15-20s', 2), self::ex('Skin The Cat', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab avanzado', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20-22', 3), self::ex('Back Lever Hold', '15-20s', 3), self::ex('German Hang', '25-30s', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '22-25', 3), self::ex('Back Lever Hold', '20-25s', 3), self::ex('German Hang', '30-35s', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '25-28', 4), self::ex('Back Lever Hold', '25-30s', 4), self::ex('German Hang', '35-40s', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('Back Lever Hold', '10-15s', 2), self::ex('German Hang', '15-20s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab elite', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '22-25', 3), self::ex('Back Lever Row', '5-8', 3), self::ex('Skin The Cat', '12-15', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '25-28', 3), self::ex('Back Lever Row', '8-10', 3), self::ex('Skin The Cat', '15-18', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '28-30', 4), self::ex('Back Lever Row', '10-12', 4), self::ex('Skin The Cat', '18-20', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 2), self::ex('Back Lever Row', '5-8', 2), self::ex('Skin The Cat', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Prehab maximo', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '25-28', 3), self::ex('Back Lever Row', '8-10', 3), self::ex('Human Flag', '5-10s', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '28-30', 3), self::ex('Back Lever Row', '10-12', 3), self::ex('Human Flag', '10-15s', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '30-35', 4), self::ex('Back Lever Row', '12-15', 4), self::ex('Human Flag', '15-20s', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 2), self::ex('Back Lever Row', '5-8', 2), self::ex('Human Flag', '5-10s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL/PLAY (Practica directa de FL)
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
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Primer contacto con progresiones', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '20-25s', 3), self::ex('Dead Hang', '15-20s', 3), self::ex('Inverted Hang Hold', '10-15s', 3), self::ex('Tuck Front Lever Hold', '3-5s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '25-30s', 3), self::ex('Dead Hang', '20-25s', 3), self::ex('Inverted Hang Hold', '15-20s', 3), self::ex('Tuck Front Lever Hold', '5-8s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Maximo tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30-35s', 4), self::ex('Dead Hang', '25-30s', 4), self::ex('Inverted Hang Hold', '20-25s', 4), self::ex('Tuck Front Lever Hold', '8-10s', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '15-20s', 2), self::ex('Dead Hang', '10-15s', 2), self::ex('Inverted Hang Hold', '8-10s', 2), self::ex('Tuck Front Lever Hold', '3-5s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Tuck front lever controlado', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '8-10s', 3), self::ex('Tuck Front Lever Row', '5-6', 3), self::ex('Active Hang', '25-30s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '10-12s', 3), self::ex('Tuck Front Lever Row', '6-8', 3), self::ex('Active Hang', '30-35s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '12-15s', 4), self::ex('Tuck Front Lever Row', '8-10', 4), self::ex('Active Hang', '35-40s', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Front Lever Hold', '5-8s', 2), self::ex('Tuck Front Lever Row', '4-5', 2), self::ex('Active Hang', '20-25s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Advanced tuck controlado', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '8-10s', 3), self::ex('Advanced Tuck Front Lever Row', '5-6', 3), self::ex('Tuck Front Lever Hold', '10-12s', 3), self::ex('Ice Cream Maker', '2-3', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '10-12s', 3), self::ex('Advanced Tuck Front Lever Row', '6-8', 3), self::ex('Tuck Front Lever Hold', '12-15s', 3), self::ex('Ice Cream Maker', '3-4', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '12-15s', 4), self::ex('Advanced Tuck Front Lever Row', '8-10', 4), self::ex('Tuck Front Lever Hold', '15-18s', 4), self::ex('Ice Cream Maker', '4-5', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Front Lever Hold', '5-8s', 2), self::ex('Advanced Tuck Front Lever Row', '4-5', 2), self::ex('Tuck Front Lever Hold', '8-10s', 2), self::ex('Ice Cream Maker', '2-3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Straddle front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Front Lever Hold', '5-8s', 3), self::ex('Straddle Front Lever Row', '3-5', 3), self::ex('One Leg Front Lever Hold', '5-8s', 3), self::ex('Advanced Tuck Front Lever Hold', '10-12s', 3)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '3-5', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Front Lever Hold', '8-10s', 3), self::ex('Straddle Front Lever Row', '5-6', 3), self::ex('One Leg Front Lever Hold', '8-10s', 3), self::ex('Advanced Tuck Front Lever Hold', '12-15s', 3)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '4-6', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Front Lever Hold', '10-12s', 4), self::ex('Straddle Front Lever Row', '6-8', 4), self::ex('One Leg Front Lever Hold', '10-12s', 4), self::ex('Advanced Tuck Front Lever Hold', '15-18s', 4)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '5-8', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Front Lever Hold', '5-8s', 2), self::ex('Straddle Front Lever Row', '3-5', 2), self::ex('One Leg Front Lever Hold', '5-8s', 2), self::ex('Advanced Tuck Front Lever Hold', '8-10s', 2)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '2-4', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Full front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '5-8s', 3), self::ex('Full Front Lever Row', '3-5', 3), self::ex('Straddle Front Lever Hold', '8-10s', 3)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '3-5', 3), self::ex('Front Lever Negative', '2-3', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '8-10s', 3), self::ex('Full Front Lever Row', '5-6', 3), self::ex('Straddle Front Lever Hold', '10-12s', 3)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '4-6', 3), self::ex('Front Lever Negative', '3-4', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '10-12s', 4), self::ex('Full Front Lever Row', '6-8', 4), self::ex('Straddle Front Lever Hold', '12-15s', 4)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '5-8', 4), self::ex('Front Lever Negative', '4-5', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '5-8s', 2), self::ex('Full Front Lever Row', '3-5', 2), self::ex('Straddle Front Lever Hold', '8-10s', 2)], 120), self::block(1, 1, 45, [self::ex('Ice Cream Maker', '2-4', 2), self::ex('Front Lever Negative', '2-3', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Skill Base', 'goal' => 'Dominio del front lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '20-25s', 3), self::ex('Front Lever Raise', '6-8', 3), self::ex('Full Front Lever Row', '6-8', 3), self::ex('Front Lever Negative', '3-5', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Skill Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '25-30s', 3), self::ex('Front Lever Raise', '8-10', 3), self::ex('Full Front Lever Row', '8-10', 3), self::ex('Front Lever Negative', '4-6', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Skill Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '30-35s', 4), self::ex('Front Lever Raise', '10-12', 4), self::ex('Full Front Lever Row', '10-12', 4), self::ex('Front Lever Negative', '5-8', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Skill Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Full Front Lever Hold', '15-20s', 2), self::ex('Front Lever Raise', '5-6', 2), self::ex('Full Front Lever Row', '5-6', 2), self::ex('Front Lever Negative', '2-4', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
