<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Handstand Balance Mastery (6 niveles).
 * Estructura: 4 sesiones por fase (A=Strength, B=Core, C=Mobility, D=Play).
 * Fases: base, progression, intensification, deload.
 */
class HandstandBalanceBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Confianza Invertida', 'title' => 'Confianza Invertida', 'description' => 'Perder el miedo a la posicion invertida', 'objective' => 'Construir confianza en pared y en el suelo', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Push-up(10), High Plank(30s)', 'skillFocus' => 'Headstand, Frog Stand, Chest-to-Wall HS'],
            2 => ['name' => 'Nivel 2: Alineacion en Pared', 'title' => 'Alineacion en Pared', 'description' => 'Linea recta en handstand y caida segura', 'objective' => 'Dominar alineacion y bail out', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Wall HS pecho(60s), Pike Push-up(10)', 'skillFocus' => 'Back-to-Wall HS, Bail Out'],
            3 => ['name' => 'Nivel 3: Despegue de Pared', 'title' => 'Despegue de Pared', 'description' => 'Separar pies del muro de forma controlada', 'objective' => 'Cargar peso sobre manos sin depender del muro', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Wall HS espalda(45s), Bail Out(5)', 'skillFocus' => 'Wall HS puntas, Wall Scissors'],
            4 => ['name' => 'Nivel 4: Primeros Segundos Libres', 'title' => 'Primeros Segundos Libres', 'description' => 'Entradas controladas y equilibrio libre', 'objective' => 'Lograr freestanding y kick-ups controlados', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Wall HS puntas(30s), Wall Scissors(10), Pike PU(10)', 'skillFocus' => 'Kick-up to wall, Freestanding attempts'],
            5 => ['name' => 'Nivel 5: Consistencia', 'title' => 'Consistencia', 'description' => 'Freestanding repetible y entradas fiables', 'objective' => 'Convertir freestanding en skill repetible', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Kick-up to wall(3), Freestanding HS(3s)', 'skillFocus' => 'Freestanding HS, Consistent entries'],
            6 => ['name' => 'Nivel 6: Forma y Estilo', 'title' => 'Forma y Estilo', 'description' => 'Dominio completo con shapes y caminata', 'objective' => 'Handstand como lenguaje corporal', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Freestanding HS(10s), Consecutive Entries(3), HSPU(8)', 'skillFocus' => 'Shapes, Handstand Walks, Transitions'],
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
    // SESSION A — STRENGTH (hombros/empuje)
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
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza basica hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '5-6', 3), self::ex('Elevated Pike Push Up', '5-6', 3), self::ex('Wall Push Up', '8-10', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '8-10', 3), self::ex('Elevated Pike Push Up', '6-8', 3), self::ex('Standard Push Up', '8-10', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '10-12', 4), self::ex('Elevated Pike Push Up', '8-10', 4), self::ex('Standard Push Up', '10-12', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '5-6', 2), self::ex('Elevated Pike Push Up', '4-5', 2), self::ex('Wall Push Up', '6-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '8-10', 3), self::ex('Pike Push Up', '10-12', 3), self::ex('Standard Push Up', '10-12', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '10-12', 3), self::ex('Pike Push Up', '12-15', 3), self::ex('Diamond Push Up', '8-10', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '12-15', 4), self::ex('Pike Push Up', '15-18', 4), self::ex('Decline Push Up', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Elevated Pike Push Up', '6-8', 2), self::ex('Pike Push Up', '8-10', 2), self::ex('Standard Push Up', '8-10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '12-15', 3), self::ex('Feet on Wall Pike Push Up', '5-6', 3), self::ex('Standard Push Up', '12-15', 3)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '15-18', 3), self::ex('Feet on Wall Pike Push Up', '6-8', 3), self::ex('Diamond Push Up', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '18-20', 4), self::ex('Feet on Wall Pike Push Up', '8-10', 4), self::ex('Decline Push Up', '10-12', 4)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '8-10', 2), self::ex('Feet on Wall Pike Push Up', '4-5', 2), self::ex('Standard Push Up', '8-10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza + HSPU intro', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '15-18', 3), self::ex('Handstand Push Up', '3-5', 3), self::ex('Feet on Wall Pike Push Up', '8-10', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion HSPU', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '18-20', 3), self::ex('Handstand Push Up', '5-6', 3), self::ex('Feet on Wall Pike Push Up', '10-12', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '20-22', 4), self::ex('Handstand Push Up', '6-8', 4), self::ex('Deficit Handstand Push Up', '3-5', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pike Push Up', '10-12', 2), self::ex('Handstand Push Up', '3-4', 2), self::ex('Feet on Wall Pike Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'HSPU consolidado', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '8-10', 3), self::ex('Pike Push Up', '20-22', 3), self::ex('Feet on Wall Pike Push Up', '10-12', 3)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '10-12', 3), self::ex('Pike Push Up', '22-25', 3), self::ex('Deficit Handstand Push Up', '3-5', 3)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '12-15', 4), self::ex('Pike Push Up', '25-28', 4), self::ex('Deficit Handstand Push Up', '5-6', 4)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '6-8', 2), self::ex('Pike Push Up', '12-15', 2), self::ex('Feet on Wall Pike Push Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza elite hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '12-15', 3), self::ex('Deficit Handstand Push Up', '5-6', 3), self::ex('Pike Push Up', '25-28', 3)], 150)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '15-18', 3), self::ex('Deficit Handstand Push Up', '6-8', 3), self::ex('Pike Push Up', '28-30', 3)], 150)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '18-20', 4), self::ex('Deficit Handstand Push Up', '8-10', 4), self::ex('Pike Push Up', '30-35', 4)], 150)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Handstand Push Up', '8-10', 2), self::ex('Deficit Handstand Push Up', '4-5', 2), self::ex('Pike Push Up', '15-18', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — CORE (hollow/compression)
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
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core + L-sit intro', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 3), self::ex('L-Sit', '10-15s', 3), self::ex('High Plank', '40-45s', 3)], 60)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Aumentar tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '35-40s', 3), self::ex('L-Sit', '15-20s', 3), self::ex('High Plank', '45-50s', 3)], 60)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '40-45s', 4), self::ex('L-Sit', '20-25s', 4), self::ex('High Plank', '50-55s', 4)], 60)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('L-Sit', '8-10s', 2), self::ex('High Plank', '30-35s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core + compresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '40-45s', 3), self::ex('L-Sit', '20-25s', 3), self::ex('Hollow Body Rock', '12-15', 3)], 60)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '45-50s', 3), self::ex('L-Sit', '25-30s', 3), self::ex('Hollow Body Rock', '15-18', 3)], 60)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '50-55s', 4), self::ex('L-Sit', '30-35s', 4), self::ex('Hollow Body Rock', '18-20', 4)], 60)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('L-Sit', '10-15s', 2), self::ex('Hollow Body Rock', '8-10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core avanzado', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '50-55s', 3), self::ex('L-Sit', '30-35s', 3), self::ex('Dragon Flag Negative', '3-5', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '55-60s', 3), self::ex('L-Sit', '35-40s', 3), self::ex('Dragon Flag Negative', '5-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60-65s', 4), self::ex('L-Sit', '40-45s', 4), self::ex('Dragon Flag', '3-5', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 2), self::ex('L-Sit', '15-20s', 2), self::ex('Dragon Flag Negative', '3-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core elite', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60-65s', 3), self::ex('L-Sit', '40-45s', 3), self::ex('Dragon Flag', '3-5', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '65-70s', 3), self::ex('L-Sit', '45-50s', 3), self::ex('Dragon Flag', '5-8', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '70-75s', 4), self::ex('L-Sit', '50-55s', 4), self::ex('Dragon Flag', '8-10', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '35-40s', 2), self::ex('L-Sit', '20-25s', 2), self::ex('Dragon Flag', '3-5', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Core Base', 'goal' => 'Core maximo', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '70-75s', 3), self::ex('L-Sit', '50-55s', 3), self::ex('Dragon Flag', '8-10', 3)], 90)]],
            'progression' => ['name' => 'Sesion B: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '75-80s', 3), self::ex('L-Sit', '55-60s', 3), self::ex('Dragon Flag', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesion B: Core Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '80-85s', 4), self::ex('L-Sit', '60-65s', 4), self::ex('Dragon Flag', '12-15', 4)], 90)]],
            'deload' => ['name' => 'Sesion B: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '40-45s', 2), self::ex('L-Sit', '25-30s', 2), self::ex('Dragon Flag', '5-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — MOBILITY/PREHAB (munecas, hombros, prehab)
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
            'base' => ['name' => 'Sesion C: Mobility Base', 'goal' => 'Prehab basico', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 3), self::ex('Wrist Curl', '15-20', 3), self::ex('Reverse Wrist Curl', '15-20', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Mobility Progresion', 'goal' => 'Aumentar reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('Wrist Curl', '18-20', 3), self::ex('Reverse Wrist Curl', '18-20', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Mobility Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 4), self::ex('Wrist Curl', '20-25', 4), self::ex('Reverse Wrist Curl', '20-25', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Mobility Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '8-10', 2), self::ex('Wrist Curl', '10-12', 2), self::ex('Reverse Wrist Curl', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Mobility Base', 'goal' => 'Prehab hombros', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '15-18', 3), self::ex('Landmine Press', '10-12', 3), self::ex('Wrist Curl', '18-20', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Mobility Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('Landmine Press', '12-15', 3), self::ex('Reverse Wrist Curl', '18-20', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Mobility Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20-22', 4), self::ex('Landmine Press', '15-18', 4), self::ex('Kettlebell Waiter Walk', '20m', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Mobility Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '8-10', 2), self::ex('Landmine Press', '6-8', 2), self::ex('Wrist Curl', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Mobility Base', 'goal' => 'Prehab + overhead', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '18-20', 3), self::ex('Landmine Press', '12-15', 3), self::ex('Overhead Carry', '30m', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Mobility Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20-22', 3), self::ex('Landmine Press', '15-18', 3), self::ex('Overhead Carry', '40m', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Mobility Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '22-25', 4), self::ex('Landmine Press', '18-20', 4), self::ex('Overhead Carry', '50m', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Mobility Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('Landmine Press', '8-10', 2), self::ex('Overhead Carry', '20m', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Mobility Base', 'goal' => 'Prehab avanzado', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '20-22', 3), self::ex('Landmine Press', '15-18', 3), self::ex('Overhead Carry', '50m', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Mobility Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '22-25', 3), self::ex('Landmine Press', '18-20', 3), self::ex('Overhead Carry', '60m', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Mobility Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '25-28', 4), self::ex('Landmine Press', '20-22', 4), self::ex('Overhead Carry', '70m', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Mobility Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '10-12', 2), self::ex('Landmine Press', '8-10', 2), self::ex('Overhead Carry', '30m', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Mobility Base', 'goal' => 'Prehab elite', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '22-25', 3), self::ex('Landmine Press', '18-20', 3), self::ex('Overhead Carry', '60m', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Mobility Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '25-28', 3), self::ex('Landmine Press', '20-22', 3), self::ex('Overhead Carry', '70m', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Mobility Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '28-30', 4), self::ex('Landmine Press', '22-25', 4), self::ex('Overhead Carry', '80m', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Mobility Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 2), self::ex('Landmine Press', '10-12', 2), self::ex('Overhead Carry', '35m', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Mobility Base', 'goal' => 'Prehab maximo', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '25-28', 3), self::ex('Landmine Press', '20-22', 3), self::ex('Overhead Carry', '70m', 3)], 60)]],
            'progression' => ['name' => 'Sesion C: Mobility Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '28-30', 3), self::ex('Landmine Press', '22-25', 3), self::ex('Overhead Carry', '80m', 3)], 60)]],
            'intensification' => ['name' => 'Sesion C: Mobility Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '30-35', 4), self::ex('Landmine Press', '25-28', 4), self::ex('Overhead Carry', '90m', 4)], 60)]],
            'deload' => ['name' => 'Sesion C: Mobility Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Face Pull', '12-15', 2), self::ex('Landmine Press', '10-12', 2), self::ex('Overhead Carry', '40m', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — PLAY/EXPLORACION (drills + libre)
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
            'base' => ['name' => 'Sesion D: Play Base', 'goal' => 'Explorar invertido', 'blocks' => [self::block(1, 1, 45, [self::ex('Headstand Hold', '20-30s', 3), self::ex('Frog Stand', '15-20s', 3), self::ex('Chest to Wall Handstand', '15-20s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Play Progresion', 'goal' => 'Aumentar tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Headstand Hold', '30-40s', 3), self::ex('Frog Stand', '20-25s', 3), self::ex('Chest to Wall Handstand', '25-30s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Play Intensificacion', 'goal' => 'Maximo tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Headstand Hold', '40-50s', 4), self::ex('Frog Stand', '25-30s', 4), self::ex('Chest to Wall Handstand', '30-35s', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Play Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Headstand Hold', '20s', 2), self::ex('Frog Stand', '15s', 2), self::ex('Chest to Wall Handstand', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Play Base', 'goal' => 'Alineacion y seguridad', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '20-25s', 3), self::ex('Handstand Bail Out', '2/side', 3), self::ex('Kick-up to Handstand', '3', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Play Progresion', 'goal' => 'Bail out fluido', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '30-35s', 3), self::ex('Handstand Bail Out', '3/side', 3), self::ex('Kick-up to Handstand', '5', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Play Intensificacion', 'goal' => 'Control lateral', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '35-40s', 4), self::ex('Handstand Shoulder Taps', '5/side', 4), self::ex('Kick-up to Handstand', '5-6', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Play Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Wall Handstand Hold', '15s', 2), self::ex('Handstand Bail Out', '1/side', 2), self::ex('Kick-up to Handstand', '2', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Play Base', 'goal' => 'Despegue de pared', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '25-30s', 3), self::ex('Wall Scissors', '8/side', 3), self::ex('Kick-up to Handstand', '3/side', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Play Progresion', 'goal' => 'Puntas y scissors', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '30-35s', 3), self::ex('Wall Scissors', '10/side', 3), self::ex('Kick-up to Handstand', '5/side', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Play Intensificacion', 'goal' => 'Casi libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '35-40s', 4), self::ex('Wall Scissors', '12/side', 4), self::ex('Kick-up to Handstand', '5-6/side', 4)], 90)]],
            'deload' => ['name' => 'Sesion D: Play Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Chest to Wall Handstand', '20s', 2), self::ex('Wall Scissors', '5/side', 2), self::ex('Kick-up to Handstand', '2/side', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Play Base', 'goal' => 'Kick-up a pared', 'blocks' => [self::block(1, 1, 45, [self::ex('Kick-up to Handstand', '5', 3), self::ex('Freestanding Handstand', '3-5s', 3), self::ex('Handstand Bail Out', '3', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Play Progresion', 'goal' => 'Primeros segundos libres', 'blocks' => [self::block(1, 1, 45, [self::ex('Kick-up to Handstand', '8', 3), self::ex('Freestanding Handstand', '5-8s', 3), self::ex('Handstand Bail Out', '3', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Play Intensificacion', 'goal' => 'Maximo hold libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Kick-up to Handstand', '10', 4), self::ex('Freestanding Handstand', '8-10s', 4), self::ex('Handstand Walk', '5-8m', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Play Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Kick-up to Handstand', '5', 2), self::ex('Freestanding Handstand', '3-5s', 2), self::ex('Handstand Bail Out', '2', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Play Base', 'goal' => 'Consistencia', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10-15s', 3), self::ex('Kick-up to Handstand', '5', 3), self::ex('Handstand Walk', '10-12m', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Play Progresion', 'goal' => 'Entradas fiables', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '15-20s', 3), self::ex('Kick-up to Handstand', '8', 3), self::ex('Handstand Walk', '12-15m', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Play Intensificacion', 'goal' => 'Volumen libre', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '20-25s', 4), self::ex('Kick-up to Handstand', '10', 4), self::ex('Handstand Walk', '15-18m', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Play Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '10s', 2), self::ex('Kick-up to Handstand', '5', 2), self::ex('Handstand Walk', '8m', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Play Base', 'goal' => 'Shapes', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '25-30s', 3), self::ex('Handstand Tuck Hold', '3-5s', 3), self::ex('Handstand Straddle Hold', '3-5s', 3)], 120), self::block(1, 1, 45, [self::ex('Handstand Walk', '15-18m', 3), self::ex('Kick-up to Handstand', '5', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Play Progresion', 'goal' => 'Transiciones', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '30-35s', 3), self::ex('Handstand Tuck Hold', '5-8s', 3), self::ex('Handstand Straddle Hold', '5-8s', 3)], 120), self::block(1, 1, 45, [self::ex('Handstand Walk', '18-20m', 3), self::ex('Kick-up to Handstand', '8', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Play Intensificacion', 'goal' => 'Freestyle', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '35-40s', 4), self::ex('Handstand Tuck Hold', '8-10s', 4), self::ex('Handstand Straddle Hold', '8-10s', 4)], 120), self::block(1, 1, 45, [self::ex('Handstand Walk', '20-25m', 4), self::ex('Kick-up to Handstand', '10', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Play Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Freestanding Handstand', '20s', 2), self::ex('Handstand Tuck Hold', '3-5s', 2), self::ex('Handstand Straddle Hold', '3-5s', 2)], 120), self::block(1, 1, 45, [self::ex('Handstand Walk', '10m', 2), self::ex('Kick-up to Handstand', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
