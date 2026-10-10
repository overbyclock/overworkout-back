<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa One-Arm Pull-Up Mastery (8 niveles).
 * Especialización del Master v4: tracción unilateral élite.
 * Estructura: 4 sesiones por fase (A=Strength, B=Unilateral, C=Prehab, D=Skill).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a codo y hombro:
 * - Los intentos de one-arm pull-up van en fresco, descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de tracción unilateral en frío: hombro y muñeca calientes antes de cada serie.
 * - Molestia matinal >2/10 en codo (epicondilo) o hombro = congela ese gesto un ciclo.
 */
class OneArmPullUpMasteryBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Tracción', 'title' => 'Base de Tracción', 'description' => 'Construir la base de tracción, arquería y contrapeso horizontal necesarias para entrar al camino unilateral', 'objective' => 'Dominar 10-12 pull-ups estrictas, archer pull-up 2-3/lado y filas pesadas con técnica sólida', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(10), Dead Hang(60s)', 'skillFocus' => 'Standard Pull Up, Archer Pull Up, Weighted Ring Row'],
            2 => ['name' => 'Nivel 2: Fuerza Máxima Prono', 'title' => 'Fuerza Máxima Prono', 'description' => 'Desarrollar fuerza máxima en dominada lastrada y arquería cargada', 'objective' => 'Lastrar +10-15% BW, consolidar archer pull-up 3-5/lado y one-arm scapular pulls', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Weighted Pull Up +15% BW(3), Archer Pull Up(5/lado), Pull Up(12)', 'skillFocus' => 'Weighted Pull Up, Archer Pull Up, One-Arm Scapular Pull'],
            3 => ['name' => 'Nivel 3: Lastrada Sólida', 'title' => 'Lastrada Sólida', 'description' => 'Solidificar la dominada lastrada al nivel del test N13 del Master e introducir el typewriter', 'objective' => 'Conseguir +25% BW ×3, typewriter limpio y colgado a un brazo 10-20 s', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Weighted Pull Up +25% BW(3), Typewriter Pull Up(2/lado), Dead Hang un brazo(10s)', 'skillFocus' => 'Weighted Pull Up, Typewriter Pull Up, One-Arm Hang'],
            4 => ['name' => 'Nivel 4: Unilateral Asistida', 'title' => 'Unilateral Asistida', 'description' => 'Primer contacto con el patrón completo de one-arm pull-up usando asistencia y negativas', 'objective' => 'Dominar assisted one-arm pull-up 3-5/lado y negativas de 5 s controladas', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Assisted One Arm Pull Up(3/lado), One Arm Pull Up Negative(5s), Weighted Pull Up +30% BW(2)', 'skillFocus' => 'Assisted One Arm Pull Up, One Arm Pull Up Negative, Archer Hold'],
            5 => ['name' => 'Nivel 5: Camino al OAC', 'title' => 'Camino al OAC', 'description' => 'Construir el one-arm chin-up mediante negativas supinas, asistencia mínima y parciales de rango', 'objective' => 'Conseguir negativa supina de 5-8 s, parcial top half 1-2/lado y +40% BW en lastrada', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'One Arm Pull Up Negative supino(5s/lado), One Arm Pull Up parcial top(1/lado), Weighted Pull Up +40% BW(1)', 'skillFocus' => 'One Arm Chin Up Negative, One Arm Pull Up Parcial Top, Weighted Pull Up'],
            6 => ['name' => 'Nivel 6: Primer One-Arm Chin-Up', 'title' => 'Primer One-Arm Chin-Up', 'description' => 'Conseguir el primer one-arm chin-up (OAC) y consolidar la fuerza a dos brazos con +50% BW', 'objective' => 'OAC ×1 por lado, +50% BW ×1 y arquería profunda 8-10/lado', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'One Arm Chin Up(1/lado), Weighted Pull Up +50% BW(1), Archer Pull Up(8/lado)', 'skillFocus' => 'One Arm Chin Up, Weighted Pull Up +50% BW, Archer Pull Up'],
            7 => ['name' => 'Nivel 7: OAC → OAP', 'title' => 'OAC → OAP', 'description' => 'Transferir la fuerza del OAC al OAP mediante negativas prona lentas y simetría', 'objective' => 'OAC ×3-5, negativa prona 10-15 s y +50% BW ×3', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'One Arm Chin Up(3/lado), One Arm Pull Up Negative(10s), Weighted Pull Up +50% BW(3)', 'skillFocus' => 'One Arm Chin Up, One Arm Pull Up Negative, Weighted Pull Up'],
            8 => ['name' => 'Nivel 8: One-Arm Pull-Up', 'title' => 'One-Arm Pull-Up', 'description' => 'Dominio del one-arm pull-up con volumen real, OAC sólido y lastre élite', 'objective' => 'OAP ×1-3 por lado, OAC ×3-5 y +50% BW ×5', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'One Arm Pull Up(1/lado), One Arm Chin Up(5/lado), Weighted Pull Up +50% BW(5)', 'skillFocus' => 'One Arm Pull Up, One Arm Chin Up, Weighted Pull Up'],
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
    // SESSION A — STRENGTH (Tracción lastrada y variedades de agarre)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Base de tracción con énfasis en dominadas estrictas, arquería y contrapeso horizontal', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Archer Pull Up', '2/lado', 3), self::ex('Weighted Ring Row', '10', 3), self::ex('Close Grip Pull Up', '8-10', 2)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de base antes de entrar lastre', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 3), self::ex('Archer Pull Up', '2-3/lado', 3), self::ex('Weighted Ring Row', '10-12', 3), self::ex('Close Grip Pull Up', '10', 2)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base de tracción', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '12', 4), self::ex('Archer Pull Up', '3/lado', 4), self::ex('Weighted Ring Row', '12', 4), self::ex('Close Grip Pull Up', '10-12', 3)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación activa de tracción', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 2), self::ex('Archer Pull Up', '2/lado', 2), self::ex('Weighted Ring Row', '8', 2), self::ex('Close Grip Pull Up', '6-8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Introducir la dominada lastrada ligera y mantener volumen en prono', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3-4', 3), self::ex('Strict Pull Up', '6-8', 3), self::ex('Chin Up', '6-8', 3), self::ex('L-Sit Pull Up', '3-5', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Subir el lastre al 10-15% BW y mantener dominadas limpias', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3-5', 4), self::ex('Strict Pull Up', '8-10', 3), self::ex('Chin Up', '8', 3), self::ex('L-Sit Pull Up', '4-5', 2)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico de fuerza a dos brazos con lastre moderado', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '5', 4), self::ex('Strict Pull Up', '10', 4), self::ex('Chin Up', '10', 3), self::ex('L-Sit Pull Up', '5', 3)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Descarga de tracción pesada', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3', 2), self::ex('Strict Pull Up', '5-6', 2), self::ex('Chin Up', '5-6', 2), self::ex('L-Sit Pull Up', '3', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Lastre creciente hacia +25% BW y variedad de agarres', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3', 4), self::ex('Weighted Chin Up', '5-6', 3), self::ex('L-Sit Pull Up', '4-5', 3), self::ex('Close Grip Pull Up', '8-10', 2)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar lastre y volumen en dominadas lastradas', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3-4', 4), self::ex('Weighted Chin Up', '6-8', 3), self::ex('L-Sit Pull Up', '5-6', 3), self::ex('Close Grip Pull Up', '10', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: +25% BW ×3 y L-sit pull-up sólido', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3-5', 5), self::ex('Weighted Chin Up', '8', 4), self::ex('L-Sit Pull Up', '6', 4), self::ex('Close Grip Pull Up', '10-12', 3)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 2), self::ex('Weighted Chin Up', '5', 2), self::ex('L-Sit Pull Up', '3-4', 2), self::ex('Close Grip Pull Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza máxima a dos brazos con lastre pesado antes del trabajo unilateral asistido', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 4), self::ex('Chin Up', '8-10', 3), self::ex('Weighted Chin Up', '5-6', 3), self::ex('Strict Pull Up', '6-8', 2)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión hacia +30% BW manteniendo técnica', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 5), self::ex('Chin Up', '10', 3), self::ex('Weighted Chin Up', '6-8', 3), self::ex('Strict Pull Up', '8', 2)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico de lastre a dos brazos del nivel', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3', 5), self::ex('Chin Up', '10-12', 4), self::ex('Weighted Chin Up', '8', 4), self::ex('Strict Pull Up', '8-10', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2', 2), self::ex('Chin Up', '6-8', 2), self::ex('Weighted Chin Up', '4-5', 2), self::ex('Strict Pull Up', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Lastre élite a dos brazos: la fuerza de base del OAC se construye aquí', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1-2', 4), self::ex('L-Sit Pull Up', '5-6', 3), self::ex('Close Grip Pull Up', '8-10', 3), self::ex('Chin Up', '8-10', 2)], 180)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar lastre hacia +40% BW', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2', 4), self::ex('L-Sit Pull Up', '6', 3), self::ex('Close Grip Pull Up', '10', 3), self::ex('Chin Up', '10', 2)], 180)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: +40% BW ×1-3 con técnica impecable', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 5), self::ex('L-Sit Pull Up', '6-8', 4), self::ex('Close Grip Pull Up', '10-12', 4), self::ex('Chin Up', '10-12', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1', 2), self::ex('L-Sit Pull Up', '4', 2), self::ex('Close Grip Pull Up', '6-8', 2), self::ex('Chin Up', '6-8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Fuerza máxima a dos brazos mientras llega el primer OAC', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1-2', 4), self::ex('Weighted Chin Up', '3-5', 3), self::ex('Strict Pull Up', '8-10', 3), self::ex('L-Sit Pull Up', '5-6', 2)], 180)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión hacia +50% BW y dominadas limpias', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2', 4), self::ex('Weighted Chin Up', '5', 3), self::ex('Strict Pull Up', '10', 3), self::ex('L-Sit Pull Up', '6', 2)], 180)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: +50% BW ×1-3 y volumen en chin-up lastrada', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 5), self::ex('Weighted Chin Up', '5-6', 4), self::ex('Strict Pull Up', '10-12', 4), self::ex('L-Sit Pull Up', '6-8', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1', 2), self::ex('Weighted Chin Up', '3', 2), self::ex('Strict Pull Up', '6', 2), self::ex('L-Sit Pull Up', '4', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Mantener la fuerza a dos brazos con +50% BW mientras se transfiere al OAP', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1-3', 4), self::ex('Chin Up', '8-10', 3), self::ex('L-Sit Pull Up', '5-8', 3), self::ex('Close Grip Pull Up', '10-12', 2)], 180)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Volumen en +50% BW y variedad de agarres', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 5), self::ex('Chin Up', '10', 3), self::ex('L-Sit Pull Up', '6-8', 3), self::ex('Close Grip Pull Up', '10-12', 3)], 180)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico: +50% BW ×3 y volumen en L-sit pull-up', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3', 5), self::ex('Chin Up', '10-12', 4), self::ex('L-Sit Pull Up', '8', 4), self::ex('Close Grip Pull Up', '12', 3)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1-2', 2), self::ex('Chin Up', '6-8', 2), self::ex('L-Sit Pull Up', '5', 2), self::ex('Close Grip Pull Up', '8-10', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Volumen élite a dos brazos para sostener el OAP', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1-3', 5), self::ex('Weighted Chin Up', '3-5', 4), self::ex('Close Grip Pull Up', '10-12', 3), self::ex('L-Sit Pull Up', '5-8', 3)], 180)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión hacia +50% BW ×5', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '2-3', 5), self::ex('Weighted Chin Up', '5', 4), self::ex('Close Grip Pull Up', '12', 3), self::ex('L-Sit Pull Up', '6-8', 3)], 180)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Pico del programa a dos brazos: +50% BW ×5', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '3-5', 5), self::ex('Weighted Chin Up', '5-6', 4), self::ex('Close Grip Pull Up', '12-15', 4), self::ex('L-Sit Pull Up', '8', 4)], 180)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '1-2', 2), self::ex('Weighted Chin Up', '3', 2), self::ex('Close Grip Pull Up', '8-10', 2), self::ex('L-Sit Pull Up', '5', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — UNILATERAL (progresión archer → typewriter → assisted →
    // negatives → parciales → OAC → OAP)
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
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Primer arquería y trabajo escapular unilateral: simetría estricta desde el inicio', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '2/lado', 3), self::ex('Scapular Pull Up', '6', 3), self::ex('Ring Row - Elevated Feet', '10', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar reps de archer manteniendo la simetría', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '2-3/lado', 3), self::ex('Scapular Pull Up', '8', 3), self::ex('Ring Row - Elevated Feet', '10-12', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Volumen de archer pull-up y control escapular', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '3/lado', 4), self::ex('Scapular Pull Up', '8', 4), self::ex('Ring Row - Elevated Feet', '12', 3)], 150)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '2/lado', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Ring Row - Elevated Feet', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Arquería cargada y primer one-arm scapular pull con control', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '3-5/lado', 3), self::ex('Scapular Pull Up', '6', 3), self::ex('Typewriter Pull Up', '1/lado', 2)], 150)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar typewriter y mantener archer limpio', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '4-5/lado', 3), self::ex('Scapular Pull Up', '8', 3), self::ex('Typewriter Pull Up', '1-2/lado', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico: archer 5/lado y typewriter 2/lado', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '5/lado', 4), self::ex('Scapular Pull Up', '8', 4), self::ex('Typewriter Pull Up', '2/lado', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Archer Pull Up', '3/lado', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Typewriter Pull Up', '1/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Typewriter como protagonista y primer colgado a un brazo', 'blocks' => [self::block(1, 1, 45, [self::ex('Typewriter Pull Up', '2/lado', 3), self::ex('Archer Pull Up', '3-5/lado', 3), self::ex('Dead Hang', '10-15s', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar typewriter y colgado unilateral', 'blocks' => [self::block(1, 1, 45, [self::ex('Typewriter Pull Up', '2-3/lado', 3), self::ex('Archer Pull Up', '5/lado', 3), self::ex('Dead Hang', '15s', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico: typewriter 3-4/lado y dead hang un brazo 20 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Typewriter Pull Up', '3-4/lado', 4), self::ex('Archer Pull Up', '5-6/lado', 3), self::ex('Dead Hang', '20s', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Typewriter Pull Up', '2/lado', 2), self::ex('Archer Pull Up', '3/lado', 2), self::ex('Dead Hang', '10s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Primer patrón completo asistido y negativas controladas a un brazo', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted One Arm Pull Up', '3/lado', 3), self::ex('One Arm Pull Up Negative', '5s', 3), self::ex('Archer Pull Up', '3-5/lado', 2)], 180)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar asistencia mínima y negativas largas', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted One Arm Pull Up', '4/lado', 3), self::ex('One Arm Pull Up Negative', '5-6s', 3), self::ex('Archer Pull Up', '5/lado', 2)], 180)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico: assisted 5/lado y negativa de 5 s controlada', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted One Arm Pull Up', '5/lado', 4), self::ex('One Arm Pull Up Negative', '5-6s', 4), self::ex('Archer Pull Up', '5-6/lado', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('Assisted One Arm Pull Up', '3/lado', 2), self::ex('One Arm Pull Up Negative', '3s', 2), self::ex('Archer Pull Up', '3/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Camino al OAC: negativas supinas largas y parciales de rango', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up Negative', '5s', 3), self::ex('One Arm Pull Up', '1/lado', 3), self::ex('Assisted One Arm Chin Up', '3/lado', 2)], 180)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar negativas y parciales top half', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up Negative', '5-6s', 3), self::ex('One Arm Pull Up', '1-2/lado', 3), self::ex('Assisted One Arm Chin Up', '4/lado', 2)], 180)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico: negativa 8 s y parcial top 2/lado', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up Negative', '8s', 4), self::ex('One Arm Pull Up', '2/lado', 4), self::ex('Assisted One Arm Chin Up', '5/lado', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up Negative', '3s', 2), self::ex('One Arm Pull Up', '1/lado', 2), self::ex('Assisted One Arm Chin Up', '3/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Primer OAC por lado y negativas prona de mantenimiento', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '1/lado', 4), self::ex('One Arm Pull Up Negative', '5s', 3), self::ex('Archer Pull Up', '5-8/lado', 2)], 240)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar OAC y negativas largas', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '1-2/lado', 4), self::ex('One Arm Pull Up Negative', '5-8s', 3), self::ex('Archer Pull Up', '8/lado', 2)], 240)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico: OAC 2/lado y negativa 8 s', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '2/lado', 5), self::ex('One Arm Pull Up Negative', '8s', 4), self::ex('Archer Pull Up', '8-10/lado', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '1/lado', 2), self::ex('One Arm Pull Up Negative', '3s', 2), self::ex('Archer Pull Up', '5/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'Transferencia OAC → OAP: negativas prona lentas y asistencia mínima', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '2-3/lado', 4), self::ex('One Arm Pull Up Negative', '10s', 3), self::ex('Assisted One Arm Pull Up', '1-2/lado', 2)], 240)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar OAC y negativas de 10-12 s', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '3/lado', 4), self::ex('One Arm Pull Up Negative', '10-12s', 3), self::ex('Assisted One Arm Pull Up', '2/lado', 2)], 240)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico: OAC 3-5/lado y negativa prona 15 s', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '3-5/lado', 5), self::ex('One Arm Pull Up Negative', '15s', 4), self::ex('Assisted One Arm Pull Up', '2/lado', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Chin Up', '1-2/lado', 2), self::ex('One Arm Pull Up Negative', '5s', 2), self::ex('Assisted One Arm Pull Up', '1/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Unilateral Base', 'goal' => 'One-arm pull-up completo con volumen y variedades', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Pull Up', '1/lado', 4), self::ex('One Arm Chin Up', '3-5/lado', 3), self::ex('Typewriter Pull Up', '2/lado', 2)], 240)]],
            'progression' => ['name' => 'Sesión B: Unilateral Progresión', 'goal' => 'Aumentar OAP y mantener OAC sólido', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Pull Up', '1-2/lado', 4), self::ex('One Arm Chin Up', '5/lado', 3), self::ex('Typewriter Pull Up', '2-3/lado', 2)], 240)]],
            'intensification' => ['name' => 'Sesión B: Unilateral Intensificación', 'goal' => 'Pico del programa: OAP 3/lado y OAC 5/lado', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Pull Up', '3/lado', 5), self::ex('One Arm Chin Up', '5/lado', 4), self::ex('Typewriter Pull Up', '3-4/lado', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Unilateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 60, [self::ex('One Arm Pull Up', '1/lado', 2), self::ex('One Arm Chin Up', '3/lado', 2), self::ex('Typewriter Pull Up', '2/lado', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (codo, hombro y muñeca)
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
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Fortalecer muñeca y rotadores externos: base para soportar cargas de tracción', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20-30s', 2), self::ex('Wrist Curl', '12', 2), self::ex('Reverse Wrist Curl', '12', 2), self::ex('Face Pull', '12', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar rango y reps de prehab', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '12-15', 2), self::ex('Reverse Wrist Curl', '12-15', 2), self::ex('Face Pull', '12-15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de muñeca y hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '30s', 2), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Face Pull', '15', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Wrist Prayer', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Añadir German hang y band pull-apart para proteger codo y hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 3), self::ex('Wrist Curl', '12-15', 2), self::ex('Reverse Wrist Curl', '12-15', 2), self::ex('Face Pull', '12-15', 2), self::ex('Band Pull-Apart', '12', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar tiempo de German hang y reps de band pull-apart', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20s', 3), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Face Pull', '12-15', 2), self::ex('Band Pull-Apart', '12-15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de prehab de codo', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Face Pull', '15', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Face Pull', '10', 2), self::ex('Band Pull-Apart', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Añadir wrist roller para fortalecer agarre y extensores de muñeca', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Wrist Curl', '15', 2), self::ex('Reverse Wrist Curl', '15', 2), self::ex('Wrist Roller', '1', 2), self::ex('Face Pull', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar dosis de wrist roller y German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25s', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Wrist Roller', '1-2', 2), self::ex('Face Pull', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen máximo de prehab del nivel', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Wrist Roller', '2', 2), self::ex('Face Pull', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Wrist Roller', '1', 2), self::ex('Face Pull', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab completo: German hang, muñeca y band pull-apart', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Wrist Curl', '15-18', 2), self::ex('Reverse Wrist Curl', '15-18', 2), self::ex('Wrist Roller', '2', 2), self::ex('Face Pull', '15-18', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar German hang y muñeca', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Wrist Curl', '18', 2), self::ex('Reverse Wrist Curl', '18', 2), self::ex('Wrist Roller', '2', 2), self::ex('Face Pull', '18', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen de prehab', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Wrist Roller', '2-3', 2), self::ex('Face Pull', '18-20', 2), self::ex('Band Pull-Apart', '15-18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Wrist Roller', '1', 2), self::ex('Face Pull', '10', 2), self::ex('Band Pull-Apart', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de codo intenso: el epicondilo es la lesión típica del OAP', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Wrist Prayer', '30s', 2), self::ex('Face Pull', '18', 2), self::ex('Band Pull-Apart', '15', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar dosis de muñeca y hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Wrist Prayer', '30-45s', 2), self::ex('Face Pull', '18-20', 2), self::ex('Band Pull-Apart', '15-18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen máximo de prehab', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35s', 4), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Wrist Prayer', '45s', 2), self::ex('Face Pull', '20', 2), self::ex('Band Pull-Apart', '18', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Wrist Prayer', '20s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Band Pull-Apart', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Mantener prehab robusto mientras llega el primer OAC', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Wrist Curl', '18-20', 2), self::ex('Reverse Wrist Curl', '18-20', 2), self::ex('Wrist Roller', '2-3', 2), self::ex('Face Pull', '18-20', 2), self::ex('Band Pull-Apart', '15-18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar dosis de muñeca y German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35s', 3), self::ex('Wrist Curl', '20', 2), self::ex('Reverse Wrist Curl', '20', 2), self::ex('Wrist Roller', '3', 2), self::ex('Face Pull', '20', 2), self::ex('Band Pull-Apart', '18', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen máximo de prehab', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 4), self::ex('Wrist Curl', '20-22', 2), self::ex('Reverse Wrist Curl', '20-22', 2), self::ex('Wrist Roller', '3', 2), self::ex('Face Pull', '20-22', 2), self::ex('Band Pull-Apart', '18-20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20s', 2), self::ex('Wrist Curl', '10', 2), self::ex('Reverse Wrist Curl', '10', 2), self::ex('Wrist Roller', '1-2', 2), self::ex('Face Pull', '10-12', 2), self::ex('Band Pull-Apart', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de élite para sostener OAC y OAP con volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35s', 4), self::ex('Wrist Curl', '20-22', 2), self::ex('Reverse Wrist Curl', '20-22', 2), self::ex('Wrist Prayer', '30-45s', 2), self::ex('Face Pull', '20', 2), self::ex('Band Pull-Apart', '18', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar German hang y muñeca', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 4), self::ex('Wrist Curl', '20-22', 2), self::ex('Reverse Wrist Curl', '20-22', 2), self::ex('Wrist Prayer', '45s', 2), self::ex('Face Pull', '20-22', 2), self::ex('Band Pull-Apart', '18-20', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen máximo de prehab', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40s', 4), self::ex('Wrist Curl', '22', 2), self::ex('Reverse Wrist Curl', '22', 2), self::ex('Wrist Prayer', '45-60s', 2), self::ex('Face Pull', '22', 2), self::ex('Band Pull-Apart', '20', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 2), self::ex('Wrist Curl', '10-12', 2), self::ex('Reverse Wrist Curl', '10-12', 2), self::ex('Wrist Prayer', '20s', 2), self::ex('Face Pull', '10-12', 2), self::ex('Band Pull-Apart', '10', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Prehab Base', 'goal' => 'Prehab de mantenimiento de élite: nunca saltar aunque el OAP esté cerca', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 4), self::ex('Wrist Curl', '22', 2), self::ex('Reverse Wrist Curl', '22', 2), self::ex('Wrist Roller', '3', 2), self::ex('Face Pull', '20-22', 2), self::ex('Band Pull-Apart', '18-20', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Prehab Progresión', 'goal' => 'Aumentar dosis de muñeca y German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40s', 4), self::ex('Wrist Curl', '22', 2), self::ex('Reverse Wrist Curl', '22', 2), self::ex('Wrist Roller', '3-4', 2), self::ex('Face Pull', '22', 2), self::ex('Band Pull-Apart', '20', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Prehab Intensificación', 'goal' => 'Volumen máximo de prehab del programa', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40-45s', 4), self::ex('Wrist Curl', '22-25', 2), self::ex('Reverse Wrist Curl', '22-25', 2), self::ex('Wrist Roller', '4', 2), self::ex('Face Pull', '22-25', 2), self::ex('Band Pull-Apart', '20-22', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Prehab Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25s', 2), self::ex('Wrist Curl', '12', 2), self::ex('Reverse Wrist Curl', '12', 2), self::ex('Wrist Roller', '2', 2), self::ex('Face Pull', '12', 2), self::ex('Band Pull-Apart', '10-12', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL (colgados, hangs, core y respiración)
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
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Colgados limpios, activación escapular y hollow body: base del OAP', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-30s', 3), self::ex('Active Hang', '15-20s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hollow Body Hold', '15-20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar tiempo de hangs y control de core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30s', 3), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hollow Body Hold', '20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs y core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30-45s', 3), self::ex('Active Hang', '20-30s', 2), self::ex('Scapular Pull Up', '8', 3), self::ex('Hollow Body Hold', '20-25s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 2), self::ex('Active Hang', '15s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Introducir colgado a un brazo y hanging leg raise', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-30s', 3), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hanging Leg Raise', '6', 2), self::ex('Hollow Body Hold', '20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar dead hang y leg raises', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30s', 3), self::ex('Active Hang', '20-30s', 2), self::ex('Scapular Pull Up', '8', 3), self::ex('Hanging Leg Raise', '8', 2), self::ex('Hollow Body Hold', '20-25s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs y core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30-45s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 3), self::ex('Hanging Leg Raise', '8', 3), self::ex('Hollow Body Hold', '25s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 2), self::ex('Active Hang', '15s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '6', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Colgado a un brazo y skin the cat como skill de hombro y codo', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '10-15s', 3), self::ex('Active Hang', '20-30s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Skin The Cat', '2', 2), self::ex('Hollow Body Hold', '20-25s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar one-arm hang y skin the cat', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 3), self::ex('Skin The Cat', '3', 2), self::ex('Hollow Body Hold', '25s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs unilaterales y movilidad', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15-20s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 3), self::ex('Skin The Cat', '3', 3), self::ex('Hollow Body Hold', '25-30s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '10s', 2), self::ex('Active Hang', '15-20s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Skin The Cat', '2', 2), self::ex('Hollow Body Hold', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'One-arm hang estable, scapular pull a un brazo y tuck L-sit', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15-20s', 3), self::ex('Active Hang', '20-30s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar one-arm hang y tuck L-sit', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '15s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs unilaterales y core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-25s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 3), self::ex('Hanging Leg Raise', '10', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '10s', 2), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '6', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'One-arm hang de 20 s y core más exigente', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Skin The Cat', '3', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar one-arm hang y skin the cat', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-25s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 3), self::ex('Skin The Cat', '3', 3), self::ex('Tuck L-Sit', '20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs unilaterales y movilidad', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '25-30s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 3), self::ex('Skin The Cat', '4', 3), self::ex('Tuck L-Sit', '20s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '10-15s', 2), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Skin The Cat', '2', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'One-arm hang de 20-30 s y hanging leg raise sólido', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-30s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar one-arm hang y leg raises', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '25-30s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '8', 3), self::ex('Hanging Leg Raise', '10', 2), self::ex('Tuck L-Sit', '20s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs unilaterales y core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30s', 3), self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '10', 3), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Tuck L-Sit', '20s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15s', 2), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'One-arm hang de 30 s y movilidad de hombro avanzada', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Skin The Cat', '3-4', 2), self::ex('Tuck L-Sit', '20s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar one-arm hang y skin the cat', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30-35s', 3), self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '10', 3), self::ex('Skin The Cat', '4', 3), self::ex('Tuck L-Sit', '20-25s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen de hangs unilaterales y movilidad', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '35-45s', 3), self::ex('Active Hang', '45s', 2), self::ex('Scapular Pull Up', '12', 3), self::ex('Skin The Cat', '4-5', 3), self::ex('Tuck L-Sit', '25s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15-20s', 2), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '6-8', 2), self::ex('Skin The Cat', '2', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Dominio de hangs unilaterales, core y respiración diafragmática', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30-45s', 3), self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Tuck L-Sit', '20-30s', 2), self::ex('Diaphragmatic Breathing', '60-120s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Aumentar one-arm hang y core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '45s', 3), self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '10', 3), self::ex('Hanging Leg Raise', '12', 2), self::ex('Tuck L-Sit', '30s', 2), self::ex('Diaphragmatic Breathing', '120s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen máximo de skill y respiración', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '45s', 3), self::ex('Active Hang', '45s', 2), self::ex('Scapular Pull Up', '12', 3), self::ex('Hanging Leg Raise', '12-15', 2), self::ex('Tuck L-Sit', '30s', 2), self::ex('Diaphragmatic Breathing', '120s', 3)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20-30s', 2), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '6-8', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2), self::ex('Diaphragmatic Breathing', '60s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
