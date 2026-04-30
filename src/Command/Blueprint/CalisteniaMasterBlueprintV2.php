<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint completo del programa Calistenia Master V2 (12 niveles).
 */
class CalisteniaMasterBlueprintV2
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Fundamentos', 'title' => 'Fundamentos', 'description' => 'Construye la base de fuerza y técnica', 'objective' => 'Desarrollar fuerza básica y patrones de movimiento correctos', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Push-ups(10), Aus.Pull-ups(12), Squats(20), Plank(45s), Hollow(20s)', 'skillFocus' => null],
            2 => ['name' => 'Nivel 2: Consolidación', 'title' => 'Consolidación', 'description' => 'Consolida fundamentales e introduce primeros skills', 'objective' => 'Consolidar técnica y primeros skills estáticos', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Standard Pull-up(5), Pike Push-up(8), Plank(60s), Wall HS(15s)', 'skillFocus' => 'Wall Handstand'],
            3 => ['name' => 'Nivel 3: Transición', 'title' => 'Transición', 'description' => 'Primer contacto con skills técnicos', 'objective' => 'Transición de principiante a intermedio', 'difficultyRating' => 3, 'color' => '#4ade80', 'requirementsSummary' => 'Chin-up(5), Diamond Push-up(10), L-sit Tuck(10s), Wall Walk(3)', 'skillFocus' => 'L-Sit Tuck, Band Assisted Pull Up'],
            4 => ['name' => 'Nivel 4: Proyección', 'title' => 'Proyección', 'description' => 'Base de skills: planche lean, wall HS, primeros isométricos', 'objective' => 'Desarrollar proyección de hombros y base para skills', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Planche Lean(20s), Wall HS(30s), Assisted Pistol(5), Pseudo Planche PU(5)', 'skillFocus' => 'Planche Lean, Wall Handstand'],
            5 => ['name' => 'Nivel 5: Tuck Skills', 'title' => 'Tuck Skills', 'description' => 'Introducción a tuck planche y tuck front lever', 'objective' => 'Dominar skills básicos de isométricos', 'difficultyRating' => 5, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Planche(8s), Tuck FL(8s), Box Pistol(5), Pike Push-up profundo(8)', 'skillFocus' => 'Tuck Planche, Tuck Front Lever'],
            6 => ['name' => 'Nivel 6: Advanced Tuck', 'title' => 'Advanced Tuck', 'description' => 'Consolidación de skills intermedios', 'objective' => 'Cerrar brecha hacia avanzado', 'difficultyRating' => 6, 'color' => '#60a5fa', 'requirementsSummary' => 'Adv Tuck Planche(10s), Back Lever(5s), Muscle-up negativa(3), Wall HS(45s)', 'skillFocus' => 'Advanced Tuck Planche, Back Lever'],
            7 => ['name' => 'Nivel 7: Straddle & Pistol', 'title' => 'Straddle & Pistol', 'description' => 'Introducción a straddle planche, full FL y pistol completo', 'objective' => 'Dominar movimientos avanzados', 'difficultyRating' => 7, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Planche(3s), Full FL(5s), Pistol Squat(3), HSPU pared(3)', 'skillFocus' => 'Straddle Planche (intro), Full FL (intro), Pistol Squat'],
            8 => ['name' => 'Nivel 8: Consolidación', 'title' => 'Consolidación', 'description' => 'Consolidación de skills avanzados', 'objective' => 'Control corporal avanzado', 'difficultyRating' => 8, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Planche(10s), Full FL(10s), Pistol Squat(5), Freestanding HS(15s)', 'skillFocus' => 'Straddle Planche, Full FL, Freestanding HS'],
            9 => ['name' => 'Nivel 9: Dominio Dinámico', 'title' => 'Dominio Dinámico', 'description' => 'Muscle-up limpio, freestanding HSPU, unilateral', 'objective' => 'Preparación para élite', 'difficultyRating' => 9, 'color' => '#f472b6', 'requirementsSummary' => 'Muscle-up(3), Archer Push-up(5), Human Flag(3s), Freestanding HSPU(1)', 'skillFocus' => 'Muscle-up limpio, Freestanding HSPU'],
            10 => ['name' => 'Nivel 10: Fuerza Élite', 'title' => 'Fuerza Élite', 'description' => 'Movimientos de élite: planche push-up, weighted pull-up', 'objective' => 'Fuerza de élite', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Planche PU(3), Weighted PU +30% BW(3), Dragon Pistol(3), Freestanding HS(30s)', 'skillFocus' => 'Planche Push-up, Weighted Pull-up'],
            11 => ['name' => 'Nivel 11: Skills Completos', 'title' => 'Skills Completos', 'description' => 'Full planche, human flag, unilateral completo', 'objective' => 'Aproximación al máximo nivel', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Full Planche(5s), One-arm PU negativa(3), Human Flag(5s), 90° PU(3)', 'skillFocus' => 'Full Planche, Human Flag'],
            12 => ['name' => 'Nivel 12: Experto', 'title' => 'Experto', 'description' => 'Calistenia de élite: dominio completo', 'objective' => 'Dominar calistenia a nivel élite', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Full Planche(10s), Weighted PU +50% BW(3), Freestanding HSPU(3), One-arm Chin-up(1)', 'skillFocus' => 'Freestanding HSPU consolidado, One-arm Chin-up'],
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getDayData(int $levelNumber, string $dayKey, string $phase): array
    {
        $method = match ($dayKey) {
            'day1_strength' => 'day1StrengthN'.$levelNumber,
            'day2_strength' => 'day2StrengthN'.$levelNumber,
            'day3_circuit' => 'day3CircuitN'.$levelNumber,
            'day4_circuit' => 'day4CircuitN'.$levelNumber,
            default => throw new \InvalidArgumentException("Día {$dayKey} no válido"),
        };
        $sessionType = match ($dayKey) {
            'day1_strength', 'day2_strength' => 'strength',
            'day3_circuit', 'day4_circuit' => 'circuit',
        };
        $data = self::{$method}($phase);
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
    // NIVEL 1
    // ========================================================================

    private static function day1StrengthN1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Aprender push-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Wall Push Up', '5-8', 3), self::ex('Knee Push Up', '5-8', 3),
                self::ex('Incline Push Up', '5-8', 3), self::ex('High Plank', '20-30s', 3),
            ], 90)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Aprender push-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Knee Push Up', '5-8', 3), self::ex('Incline Push Up', '5-8', 3),
                self::ex('Standard Push Up', '5-8', 3), self::ex('High Plank', '30-40s', 3),
            ], 90)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Aprender push-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Incline Push Up', '6-8', 3), self::ex('Standard Push Up', '6-8', 3),
                self::ex('Knee Push Up', '6-8', 3), self::ex('High Plank', '40-50s', 3),
            ], 90)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Aprender push-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Wall Push Up', '4-6', 2), self::ex('Knee Push Up', '4-6', 2),
                self::ex('Incline Push Up', '4-6', 2), self::ex('High Plank', '15-20s', 2),
            ], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Aprender pull-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Australian Pull Up', '5-8', 3), self::ex('Negative Pull Up', '5-8', 3),
                self::ex('Active Hang', '20-30s', 3), self::ex('Dead Hang', '20-30s', 3),
            ], 90)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Aprender pull-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Australian Pull Up', '5-8', 3), self::ex('Negative Pull Up', '5-8', 3),
                self::ex('Active Hang', '30-40s', 3), self::ex('Dead Hang', '30-40s', 3),
            ], 90)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Aprender pull-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Australian Pull Up', '6-8', 3), self::ex('Negative Pull Up', '6-8', 3),
                self::ex('Active Hang', '40-50s', 3), self::ex('Dead Hang', '40-50s', 3),
            ], 90)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Aprender pull-up', 'blocks' => [self::block(1, 1, 30, [
                self::ex('Australian Pull Up', '4-6', 2), self::ex('Negative Pull Up', '4-6', 2),
                self::ex('Active Hang', '15-20s', 2), self::ex('Dead Hang', '15-20s', 2),
            ], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Air Squat', '15'), self::ex('Glute Bridge', '12'),
                self::ex('Calf Raise on Step', '15'), self::ex('Dead Bug', '10/side'),
                self::ex('High Plank', '20s'), self::ex('Hollow Body Hold', '15s'),
            ])]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Air Squat', '18'), self::ex('Glute Bridge', '15'),
                self::ex('Calf Raise on Step', '18'), self::ex('Dead Bug', '12/side'),
                self::ex('High Plank', '25s'), self::ex('Hollow Body Hold', '20s'),
            ])]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Air Squat', '20'), self::ex('Glute Bridge', '15'),
                self::ex('Calf Raise on Step', '20'), self::ex('Dead Bug', '12/side'),
                self::ex('High Plank', '30s'), self::ex('Hollow Body Hold', '25s'),
            ])]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(2, 60, 15, [
                self::ex('Air Squat', '10'), self::ex('Glute Bridge', '8'),
                self::ex('Calf Raise on Step', '10'), self::ex('Dead Bug', '6/side'),
                self::ex('High Plank', '15s'), self::ex('Hollow Body Hold', '10s'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN1(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Box Squat', '12'), self::ex('Glute Bridge', '12'),
                self::ex('Australian Pull Up', '8'), self::ex('Knee Push Up', '8'),
                self::ex('High Plank', '20s'), self::ex('Wall Push Up', '10'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Box Squat', '15'), self::ex('Glute Bridge', '15'),
                self::ex('Australian Pull Up', '10'), self::ex('Standard Push Up', '5'),
                self::ex('High Plank', '25s'), self::ex('Wall Push Up', '12'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Box Squat', '15'), self::ex('Glute Bridge', '15'),
                self::ex('Australian Pull Up', '10'), self::ex('Standard Push Up', '8'),
                self::ex('High Plank', '30s'), self::ex('Incline Push Up', '10'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(2, 60, 15, [
                self::ex('Box Squat', '8'), self::ex('Glute Bridge', '8'),
                self::ex('Australian Pull Up', '5'), self::ex('Knee Push Up', '5'),
                self::ex('High Plank', '15s'), self::ex('Wall Push Up', '6'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 2
    // ========================================================================

    private static function day1StrengthN2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Incline Push Up', '6-8', 3), self::ex('Standard Push Up', '5-6', 3),
                    self::ex('Knee Push Up', '6-8', 3),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Wall Handstand Hold', '15-20s', 3), self::ex('Wall Walk', '2-3', 3),
                ], 90),
            ]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Standard Push Up', '6-8', 3), self::ex('Incline Push Up', '6-8', 3),
                    self::ex('Diamond Push Up', '5-6', 3),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Wall Handstand Hold', '20-30s', 3), self::ex('Wall Walk', '3-4', 3),
                ], 90),
            ]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Standard Push Up', '6-8', 3), self::ex('Incline Push Up', '6-8', 3),
                    self::ex('Diamond Push Up', '5-6', 3),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Wall Handstand Hold', '25-35s', 3), self::ex('Wall Walk', '4-5', 3),
                ], 90),
            ]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Incline Push Up', '5-6', 2), self::ex('Standard Push Up', '4-5', 2),
                    self::ex('Knee Push Up', '5-6', 2),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Wall Handstand Hold', '10-15s', 2), self::ex('Wall Walk', '2', 2),
                ], 90),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Australian Pull Up', '6-8', 3), self::ex('Standard Pull Up', '5-6', 3),
                    self::ex('Negative Pull Up', '5-6', 3),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Active Hang', '15-20s', 3), self::ex('Scapular Pull Up', '5-6', 3),
                ], 90),
            ]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Australian Pull Up', '6-8', 3), self::ex('Standard Pull Up', '6-8', 3),
                    self::ex('Negative Pull Up', '6-8', 3),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Active Hang', '20-30s', 3), self::ex('Scapular Pull Up', '6-8', 3),
                ], 90),
            ]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Australian Pull Up', '6-8', 3), self::ex('Standard Pull Up', '6-8', 3),
                    self::ex('Negative Pull Up', '6-8', 3),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Active Hang', '25-35s', 3), self::ex('Scapular Pull Up', '6-8', 3),
                ], 90),
            ]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 30, [
                    self::ex('Australian Pull Up', '5-6', 2), self::ex('Standard Pull Up', '4-5', 2),
                    self::ex('Negative Pull Up', '4-5', 2),
                ], 90),
                self::block(1, 1, 30, [
                    self::ex('Active Hang', '10-15s', 2), self::ex('Scapular Pull Up', '4-5', 2),
                ], 90),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Core', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Air Squat', '20'), self::ex('Dumbbell Lunge', '8/leg'),
                self::ex('Calf Raise on Step', '15'), self::ex('Hollow Body Hold', '20s'),
                self::ex('Dead Bug', '10/side'), self::ex('High Plank', '30s'),
            ])]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Core', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Air Squat', '25'), self::ex('Dumbbell Lunge', '10/leg'),
                self::ex('Calf Raise on Step', '18'), self::ex('Hollow Body Hold', '25s'),
                self::ex('Dead Bug', '12/side'), self::ex('High Plank', '35s'),
            ])]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Core', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Air Squat', '25'), self::ex('Dumbbell Lunge', '10/leg'),
                self::ex('Calf Raise on Step', '20'), self::ex('Hollow Body Hold', '30s'),
                self::ex('Dead Bug', '12/side'), self::ex('High Plank', '40s'),
            ])]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Core', 'blocks' => [self::block(2, 60, 15, [
                self::ex('Air Squat', '12'), self::ex('Dumbbell Lunge', '5/leg'),
                self::ex('Calf Raise on Step', '10'), self::ex('Hollow Body Hold', '15s'),
                self::ex('Dead Bug', '6/side'), self::ex('High Plank', '20s'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Box Squat', '15'), self::ex('Glute Bridge', '15'),
                self::ex('Australian Pull Up', '10'), self::ex('Standard Push Up', '5'),
                self::ex('High Plank', '30s'), self::ex('Wall Push Up', '10'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Box Squat', '15'), self::ex('Glute Bridge', '15'),
                self::ex('Australian Pull Up', '12'), self::ex('Standard Push Up', '8'),
                self::ex('High Plank', '35s'), self::ex('Incline Push Up', '10'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Box Squat', '18'), self::ex('Glute Bridge', '15'),
                self::ex('Australian Pull Up', '12'), self::ex('Standard Push Up', '8'),
                self::ex('High Plank', '40s'), self::ex('Incline Push Up', '12'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Full Body Conditioning', 'blocks' => [self::block(2, 60, 15, [
                self::ex('Box Squat', '8'), self::ex('Glute Bridge', '8'),
                self::ex('Australian Pull Up', '6'), self::ex('Standard Push Up', '4'),
                self::ex('High Plank', '20s'), self::ex('Wall Push Up', '6'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 3
    // ========================================================================

    private static function day1StrengthN3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '6-8', 3), self::ex('Diamond Push Up', '5-6', 3),
                    self::ex('Incline Push Up', '6-8', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Pike Push Up', '5-6', 3), self::ex('Wall Handstand Hold', '20-30s', 3),
                ], 120),
            ]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '6-8', 3), self::ex('Diamond Push Up', '6-8', 3),
                    self::ex('Incline Push Up', '6-8', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Pike Push Up', '5-6', 3), self::ex('Wall Handstand Hold', '25-35s', 3),
                ], 120),
            ]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '6-8', 4), self::ex('Diamond Push Up', '6-8', 4),
                    self::ex('Decline Push Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Pike Push Up', '5-6', 4), self::ex('Wall Handstand Hold', '30-40s', 4),
                ], 120),
            ]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Push + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '5-6', 3), self::ex('Diamond Push Up', '4-5', 3),
                    self::ex('Incline Push Up', '5-6', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Pike Push Up', '4-5', 3), self::ex('Wall Handstand Hold', '15-20s', 3),
                ], 120),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '6-8', 3), self::ex('Chin Up', '5-6', 3),
                    self::ex('Australian Pull Up', '6-8', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Band Assisted Pull Up', '6-8', 3), self::ex('Scapular Pull Up', '6-8', 3),
                ], 120),
            ]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '6-8', 3), self::ex('Chin Up', '6-8', 3),
                    self::ex('Australian Pull Up', '6-8', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Band Assisted Pull Up', '6-8', 3), self::ex('Scapular Pull Up', '6-8', 3),
                ], 120),
            ]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '6-8', 4), self::ex('Chin Up', '6-8', 4),
                    self::ex('Wide Grip Pull Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Band Assisted Pull Up', '6-8', 4), self::ex('Scapular Pull Up', '6-8', 4),
                ], 120),
            ]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Pull + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '5-6', 3), self::ex('Chin Up', '4-5', 3),
                    self::ex('Australian Pull Up', '5-6', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Band Assisted Pull Up', '5-6', 3), self::ex('Scapular Pull Up', '5-6', 3),
                ], 120),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Air Squat', '20'), self::ex('Bulgarian Split Squat', '5/leg'),
                self::ex('Calf Raise on Step', '15'), self::ex('Tuck L-Sit', '10s'),
                self::ex('Leg Raise', '8'), self::ex('Side Plank', '20s/side'),
            ])]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Air Squat', '25'), self::ex('Bulgarian Split Squat', '6/leg'),
                self::ex('Calf Raise on Step', '18'), self::ex('Tuck L-Sit', '12s'),
                self::ex('Leg Raise', '10'), self::ex('Side Plank', '25s/side'),
            ])]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Air Squat', '25'), self::ex('Bulgarian Split Squat', '6/leg'),
                self::ex('Calf Raise on Step', '20'), self::ex('Tuck L-Sit', '15s'),
                self::ex('Leg Raise', '12'), self::ex('Side Plank', '30s/side'),
            ])]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Core + Prehab', 'blocks' => [self::block(2, 60, 15, [
                self::ex('Air Squat', '12'), self::ex('Bulgarian Split Squat', '3/leg'),
                self::ex('Calf Raise on Step', '10'), self::ex('Tuck L-Sit', '8s'),
                self::ex('Leg Raise', '6'), self::ex('Side Plank', '15s/side'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Full Body + Prehab', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Box Squat', '15'), self::ex('Single Leg Glute Bridge', '8/leg'),
                self::ex('Australian Pull Up', '10'), self::ex('Standard Push Up', '8'),
                self::ex('Hollow Body Hold', '25s'), self::ex('Wall Walk', '2-3'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Full Body + Prehab', 'blocks' => [self::block(3, 60, 15, [
                self::ex('Box Squat', '15'), self::ex('Single Leg Glute Bridge', '10/leg'),
                self::ex('Australian Pull Up', '12'), self::ex('Standard Push Up', '10'),
                self::ex('Hollow Body Hold', '30s'), self::ex('Wall Walk', '3-4'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Full Body + Prehab', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Box Squat', '18'), self::ex('Single Leg Glute Bridge', '10/leg'),
                self::ex('Australian Pull Up', '12'), self::ex('Standard Push Up', '10'),
                self::ex('Hollow Body Hold', '35s'), self::ex('Wall Walk', '4-5'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Full Body + Prehab', 'blocks' => [self::block(2, 60, 15, [
                self::ex('Box Squat', '8'), self::ex('Single Leg Glute Bridge', '5/leg'),
                self::ex('Australian Pull Up', '6'), self::ex('Standard Push Up', '5'),
                self::ex('Hollow Body Hold', '15s'), self::ex('Wall Walk', '2'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 4
    // ========================================================================

    private static function day1StrengthN4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '5-6', 4), self::ex('Diamond Push Up', '5-6', 4),
                    self::ex('Wide Push Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Planche Lean', '15-20s', 4), self::ex('Wall Handstand Hold', '20-30s', 4),
                    self::ex('Pseudo Planche Push Up', '5-6', 4),
                ], 120),
            ]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '5-6', 4), self::ex('Diamond Push Up', '5-6', 4),
                    self::ex('Wide Push Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Planche Lean', '15-20s', 4), self::ex('Wall Handstand Hold', '25-35s', 4),
                    self::ex('Pseudo Planche Push Up', '5-6', 4),
                ], 120),
            ]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '5-6', 4), self::ex('Diamond Push Up', '5-6', 4),
                    self::ex('Decline Push Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Planche Lean', '20-25s', 4), self::ex('Wall Handstand Hold', '30-40s', 4),
                    self::ex('Pseudo Planche Push Up', '5-6', 4),
                ], 120),
            ]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Push Up', '4-5', 3), self::ex('Diamond Push Up', '4-5', 3),
                    self::ex('Wide Push Up', '4-5', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Planche Lean', '10-15s', 3), self::ex('Wall Handstand Hold', '15-20s', 3),
                    self::ex('Pseudo Planche Push Up', '4-5', 3),
                ], 120),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '5-6', 4), self::ex('Chin Up', '5-6', 4),
                    self::ex('Wide Grip Pull Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Active Hang', '20-30s', 4), self::ex('Scapular Pull Up', '5-6', 4),
                    self::ex('Dead Hang', '30-40s', 4),
                ], 120),
            ]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '5-6', 4), self::ex('Chin Up', '5-6', 4),
                    self::ex('Wide Grip Pull Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Active Hang', '25-35s', 4), self::ex('Scapular Pull Up', '5-6', 4),
                    self::ex('Dead Hang', '40-50s', 4),
                ], 120),
            ]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '5-6', 4), self::ex('Chin Up', '5-6', 4),
                    self::ex('Wide Grip Pull Up', '5-6', 4),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Active Hang', '30-40s', 4), self::ex('Scapular Pull Up', '5-6', 4),
                    self::ex('Dead Hang', '50-60s', 4),
                ], 120),
            ]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill', 'blocks' => [
                self::block(1, 1, 45, [
                    self::ex('Standard Pull Up', '4-5', 3), self::ex('Chin Up', '4-5', 3),
                    self::ex('Wide Grip Pull Up', '4-5', 3),
                ], 120),
                self::block(1, 1, 45, [
                    self::ex('Active Hang', '15-20s', 3), self::ex('Scapular Pull Up', '4-5', 3),
                    self::ex('Dead Hang', '20-30s', 3),
                ], 120),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Bulgarian Split Squat', '6/leg'), self::ex('Jump Squat', '8'),
                self::ex('Assisted Pistol Squat', '3/leg'), self::ex('Calf Raise on Step', '15'),
                self::ex('Glute Bridge', '12'), self::ex('Dead Bug', '10/side'),
            ])]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Jump Squat', '10'),
                self::ex('Assisted Pistol Squat', '4/leg'), self::ex('Calf Raise on Step', '18'),
                self::ex('Glute Bridge', '15'), self::ex('Dead Bug', '12/side'),
            ])]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Jump Squat', '10'),
                self::ex('Assisted Pistol Squat', '5/leg'), self::ex('Calf Raise on Step', '20'),
                self::ex('Glute Bridge', '15'), self::ex('Dead Bug', '12/side'),
            ])]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [
                self::ex('Bulgarian Split Squat', '4/leg'), self::ex('Jump Squat', '5'),
                self::ex('Assisted Pistol Squat', '2/leg'), self::ex('Calf Raise on Step', '10'),
                self::ex('Glute Bridge', '8'), self::ex('Dead Bug', '6/side'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Tuck L-Sit', '15s'), self::ex('Leg Raise', '10'),
                self::ex('Hollow Body Hold', '30s'), self::ex('High Plank', '40s'),
                self::ex('Glute Bridge', '12'), self::ex('Wall Walk', '2-3'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Tuck L-Sit', '18s'), self::ex('Leg Raise', '12'),
                self::ex('Hollow Body Hold', '35s'), self::ex('High Plank', '45s'),
                self::ex('Glute Bridge', '15'), self::ex('Wall Walk', '3-4'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Tuck L-Sit', '20s'), self::ex('Leg Raise', '12'),
                self::ex('Hollow Body Hold', '40s'), self::ex('High Plank', '50s'),
                self::ex('Glute Bridge', '15'), self::ex('Wall Walk', '4-5'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('Tuck L-Sit', '10s'), self::ex('Leg Raise', '6'),
                self::ex('Hollow Body Hold', '20s'), self::ex('High Plank', '25s'),
                self::ex('Glute Bridge', '8'), self::ex('Wall Walk', '2'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 5
    // ========================================================================

    private static function day1StrengthN5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Planche', '4-6s', 4), self::ex('Pike Push Up', '4-6', 4),
                self::ex('Diamond Push Up', '4-6', 4), self::ex('Decline Push Up', '4-6', 4),
                self::ex('Wall Handstand Hold', '20-30s', 4),
            ], 120)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Planche', '5-6s', 4), self::ex('Pike Push Up', '5-6', 4),
                self::ex('Diamond Push Up', '5-6', 4), self::ex('Decline Push Up', '5-6', 4),
                self::ex('Wall Handstand Hold', '25-30s', 4),
            ], 120)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Planche', '6-8s', 4), self::ex('Pike Push Up', '5-6', 4),
                self::ex('Diamond Push Up', '5-6', 4), self::ex('Decline Push Up', '5-6', 4),
                self::ex('Wall Handstand Hold', '30-35s', 4),
            ], 120)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Planche', '3-4s', 3), self::ex('Pike Push Up', '3-4', 3),
                self::ex('Diamond Push Up', '3-4', 3), self::ex('Decline Push Up', '3-4', 3),
                self::ex('Wall Handstand Hold', '15-20s', 3),
            ], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Front Lever Hold', '4-6s', 4), self::ex('Tuck Front Lever Row', '3-4', 4),
                self::ex('Chin Up', '4-6', 4), self::ex('Archer Pull Up', '3-4', 4),
                self::ex('Active Hang', '20-30s', 4),
            ], 120)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Front Lever Hold', '5-6s', 4), self::ex('Tuck Front Lever Row', '4-5', 4),
                self::ex('Chin Up', '5-6', 4), self::ex('Archer Pull Up', '4-5', 4),
                self::ex('Active Hang', '25-30s', 4),
            ], 120)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Front Lever Hold', '6-8s', 4), self::ex('Tuck Front Lever Row', '4-5', 4),
                self::ex('Chin Up', '5-6', 4), self::ex('Archer Pull Up', '4-5', 4),
                self::ex('Active Hang', '30-35s', 4),
            ], 120)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Tuck Front Lever Hold', '3-4s', 3), self::ex('Tuck Front Lever Row', '2-3', 3),
                self::ex('Chin Up', '3-4', 3), self::ex('Archer Pull Up', '2-3', 3),
                self::ex('Active Hang', '15-20s', 3),
            ], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Jump Squat', '10'), self::ex('Assisted Pistol Squat', '5/leg'),
                self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Nordic Curl Negative', '3'),
                self::ex('Calf Raise on Step', '15'), self::ex('Single Leg Glute Bridge', '8/leg'),
            ])]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Jump Squat', '12'), self::ex('Assisted Pistol Squat', '6/leg'),
                self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Nordic Curl Negative', '4'),
                self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg'),
            ])]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Jump Squat', '12'), self::ex('Assisted Pistol Squat', '6/leg'),
                self::ex('Bulgarian Split Squat', '10/leg'), self::ex('Nordic Curl Negative', '4'),
                self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '10/leg'),
            ])]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [
                self::ex('Jump Squat', '6'), self::ex('Assisted Pistol Squat', '3/leg'),
                self::ex('Bulgarian Split Squat', '5/leg'), self::ex('Nordic Curl Negative', '2'),
                self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Tuck L-Sit', '10s'), self::ex('Dragon Flag Negative', '3'),
                self::ex('Tuck Planche', '5s'), self::ex('Tuck Front Lever Hold', '5s'),
                self::ex('Hollow Body Rock', '10'), self::ex('Glute Bridge', '12'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Tuck L-Sit', '12s'), self::ex('Dragon Flag Negative', '4'),
                self::ex('Tuck Planche', '8s'), self::ex('Tuck Front Lever Hold', '8s'),
                self::ex('Hollow Body Rock', '12'), self::ex('Glute Bridge', '15'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Tuck L-Sit', '15s'), self::ex('Dragon Flag Negative', '4'),
                self::ex('Tuck Planche', '10s'), self::ex('Tuck Front Lever Hold', '10s'),
                self::ex('Hollow Body Rock', '12'), self::ex('Glute Bridge', '15'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('Tuck L-Sit', '8s'), self::ex('Dragon Flag Negative', '2'),
                self::ex('Tuck Planche', '5s'), self::ex('Tuck Front Lever Hold', '5s'),
                self::ex('Hollow Body Rock', '6'), self::ex('Glute Bridge', '8'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 6
    // ========================================================================

    private static function day1StrengthN6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Planche', '4-6s', 4), self::ex('Wall Handstand Hold', '25-35s', 4),
                self::ex('Wide Push Up', '3-5', 4), self::ex('Decline Push Up', '3-5', 4),
                self::ex('Pike Push Up', '3-5', 4),
            ], 120)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Planche', '5-6s', 4), self::ex('Wall Handstand Hold', '30-35s', 4),
                self::ex('Wide Push Up', '4-5', 4), self::ex('Decline Push Up', '4-5', 4),
                self::ex('Pike Push Up', '4-5', 4),
            ], 120)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Planche', '6-8s', 4), self::ex('Wall Handstand Hold', '35-40s', 4),
                self::ex('Wide Push Up', '4-5', 4), self::ex('Decline Push Up', '4-5', 4),
                self::ex('Pike Push Up', '4-5', 4),
            ], 120)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Planche', '3-4s', 3), self::ex('Wall Handstand Hold', '20-25s', 3),
                self::ex('Wide Push Up', '2-3', 3), self::ex('Decline Push Up', '2-3', 3),
                self::ex('Pike Push Up', '2-3', 3),
            ], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Front Lever Hold', '4-6s', 4), self::ex('Back Lever Hold', '4-6s', 4),
                self::ex('Wide Grip Pull Up', '3-5', 4), self::ex('Archer Pull Up', '3-4', 4),
                self::ex('Muscle Up Negative', '2-3', 4),
            ], 120)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Front Lever Hold', '5-6s', 4), self::ex('Back Lever Hold', '5-6s', 4),
                self::ex('Wide Grip Pull Up', '4-5', 4), self::ex('Archer Pull Up', '3-4', 4),
                self::ex('Muscle Up Negative', '3-4', 4),
            ], 120)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Front Lever Hold', '6-8s', 4), self::ex('Back Lever Hold', '6-8s', 4),
                self::ex('Wide Grip Pull Up', '4-5', 4), self::ex('Archer Pull Up', '3-4', 4),
                self::ex('Muscle Up Negative', '3-4', 4),
            ], 120)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Advanced Tuck Front Lever Hold', '3-4s', 3), self::ex('Back Lever Hold', '3-4s', 3),
                self::ex('Wide Grip Pull Up', '2-3', 3), self::ex('Archer Pull Up', '2-3', 3),
                self::ex('Muscle Up Negative', '1-2', 3),
            ], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Assisted Pistol Squat', '6/leg'), self::ex('Jump Squat', '10'),
                self::ex('Bulgarian Split Squat', '8/leg'), self::ex('Nordic Curl with Band', '3'),
                self::ex('Calf Raise on Step', '15'), self::ex('Single Leg Glute Bridge', '10/leg'),
            ])]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(3, 60, 20, [
                self::ex('Assisted Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'),
                self::ex('Bulgarian Split Squat', '10/leg'), self::ex('Nordic Curl with Band', '4'),
                self::ex('Calf Raise on Step', '18'), self::ex('Single Leg Glute Bridge', '10/leg'),
            ])]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(4, 60, 20, [
                self::ex('Assisted Pistol Squat', '8/leg'), self::ex('Jump Squat', '12'),
                self::ex('Bulgarian Split Squat', '10/leg'), self::ex('Nordic Curl with Band', '4'),
                self::ex('Calf Raise on Step', '20'), self::ex('Single Leg Glute Bridge', '12/leg'),
            ])]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [self::block(2, 60, 20, [
                self::ex('Assisted Pistol Squat', '3/leg'), self::ex('Jump Squat', '6'),
                self::ex('Bulgarian Split Squat', '5/leg'), self::ex('Nordic Curl with Band', '2'),
                self::ex('Calf Raise on Step', '10'), self::ex('Single Leg Glute Bridge', '5/leg'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit One Leg', '5-8s'), self::ex('Dragon Flag Negative', '3-5'),
                self::ex('Advanced Tuck Planche', '5s'), self::ex('Back Lever Hold', '5s'),
                self::ex('Hollow Body Rock', '12'), self::ex('Glute Bridge', '15'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit One Leg', '8-10s'), self::ex('Dragon Flag Negative', '4-5'),
                self::ex('Advanced Tuck Planche', '8s'), self::ex('Back Lever Hold', '8s'),
                self::ex('Hollow Body Rock', '15'), self::ex('Glute Bridge', '15'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit One Leg', '10-12s'), self::ex('Dragon Flag Negative', '4-5'),
                self::ex('Advanced Tuck Planche', '10s'), self::ex('Back Lever Hold', '10s'),
                self::ex('Hollow Body Rock', '15'), self::ex('Glute Bridge', '18'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit One Leg', '5s'), self::ex('Dragon Flag Negative', '2-3'),
                self::ex('Advanced Tuck Planche', '5s'), self::ex('Back Lever Hold', '5s'),
                self::ex('Hollow Body Rock', '8'), self::ex('Glute Bridge', '8'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 7
    // ========================================================================

    private static function day1StrengthN7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Straddle Planche', '3-4s', 4), self::ex('Deficit Handstand Push Up', '2-3', 4),
                self::ex('Pseudo Planche Push Up', '3-5', 4), self::ex('Decline Push Up', '3-5', 4),
                self::ex('Assisted Dips', '4-5', 4),
            ], 150)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Straddle Planche', '4-5s', 4), self::ex('Deficit Handstand Push Up', '3-4', 4),
                self::ex('Pseudo Planche Push Up', '4-5', 4), self::ex('Decline Push Up', '4-5', 4),
                self::ex('Assisted Dips', '5-6', 4),
            ], 150)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Straddle Planche', '5-6s', 5), self::ex('Deficit Handstand Push Up', '3-4', 5),
                self::ex('Pseudo Planche Push Up', '4-5', 5), self::ex('Decline Push Up', '4-5', 5),
                self::ex('Assisted Dips', '5-6', 5),
            ], 150)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Straddle Planche', '2-3s', 3), self::ex('Deficit Handstand Push Up', '1-2', 3),
                self::ex('Pseudo Planche Push Up', '2-3', 3), self::ex('Decline Push Up', '2-3', 3),
                self::ex('Assisted Dips', '3-4', 3),
            ], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Full Front Lever Hold', '3-4s', 4), self::ex('Back Lever Hold', '5-6s', 4),
                self::ex('Archer Pull Up', '3-4', 4), self::ex('Muscle Up Progression', '2-3', 4),
                self::ex('Chin Up', '4-5', 4),
            ], 150)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Full Front Lever Hold', '4-5s', 4), self::ex('Back Lever Hold', '6-8s', 4),
                self::ex('Archer Pull Up', '4-5', 4), self::ex('Muscle Up Progression', '3-4', 4),
                self::ex('Chin Up', '4-5', 4),
            ], 150)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Full Front Lever Hold', '5-6s', 5), self::ex('Back Lever Hold', '8-10s', 5),
                self::ex('Archer Pull Up', '4-5', 5), self::ex('Muscle Up Progression', '3-4', 5),
                self::ex('Chin Up', '4-5', 5),
            ], 150)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 45, [
                self::ex('Full Front Lever Hold', '2-3s', 3), self::ex('Back Lever Hold', '4-5s', 3),
                self::ex('Archer Pull Up', '2-3', 3), self::ex('Muscle Up Progression', '1-2', 3),
                self::ex('Chin Up', '3-4', 3),
            ], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Box Pistol Squat', '5/leg'), self::ex('Jump Squat', '10'),
                    self::ex('Bulgarian Split Squat', '8/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '3-5'), self::ex('Calf Raise on Step', '15'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Box Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '18'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(4, 60, 20, [
                    self::ex('Box Pistol Squat', '6/leg'), self::ex('Jump Squat', '12'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '20'),
                    self::ex('Single Leg Glute Bridge', '12/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '25s/side'),
                ]),
            ]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(2, 60, 20, [
                    self::ex('Box Pistol Squat', '3/leg'), self::ex('Jump Squat', '6'),
                    self::ex('Bulgarian Split Squat', '5/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '2-3'), self::ex('Calf Raise on Step', '10'),
                    self::ex('Single Leg Glute Bridge', '5/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'),
                    self::ex('Deep Squat Hold', '15s/side'),
                ]),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '5-8s'), self::ex('Dragon Flag', '2-3'),
                self::ex('Straddle Planche', '3s'), self::ex('Full Front Lever Hold', '3s'),
                self::ex('Hollow Body Rock', '12'), self::ex('Leg Raise', '10'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '8-10s'), self::ex('Dragon Flag', '3-5'),
                self::ex('Straddle Planche', '5s'), self::ex('Full Front Lever Hold', '5s'),
                self::ex('Hollow Body Rock', '15'), self::ex('Leg Raise', '12'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit', '10-12s'), self::ex('Dragon Flag', '3-5'),
                self::ex('Straddle Planche', '5s'), self::ex('Full Front Lever Hold', '5s'),
                self::ex('Hollow Body Rock', '15'), self::ex('Leg Raise', '12'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit', '5s'), self::ex('Dragon Flag', '2'),
                self::ex('Straddle Planche', '3s'), self::ex('Full Front Lever Hold', '3s'),
                self::ex('Hollow Body Rock', '8'), self::ex('Leg Raise', '6'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 8
    // ========================================================================

    private static function day1StrengthN8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '4-5s', 5), self::ex('Handstand Push Up', '2-3', 5),
                self::ex('deficit HSPU', '2-3', 5), self::ex('Archer Push Up', '2-3', 5),
                self::ex('Wide Push Up', '3-4', 5),
            ], 150)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '5-6s', 5), self::ex('Handstand Push Up', '3-4', 5),
                self::ex('deficit HSPU', '3-4', 5), self::ex('Archer Push Up', '3-4', 5),
                self::ex('Wide Push Up', '3-4', 5),
            ], 150)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '6-8s', 5), self::ex('Handstand Push Up', '3-4', 5),
                self::ex('deficit HSPU', '3-4', 5), self::ex('Archer Push Up', '3-4', 5),
                self::ex('Wide Push Up', '3-4', 5),
            ], 150)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '3-4s', 4), self::ex('Handstand Push Up', '1-2', 4),
                self::ex('deficit HSPU', '1-2', 4), self::ex('Archer Push Up', '2-3', 4),
                self::ex('Wide Push Up', '2-3', 4),
            ], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '4-5s', 5), self::ex('Back Lever Hold', '8-10s', 5),
                self::ex('Muscle Up', '2-3', 5), self::ex('Weighted Chin Up', '3-4', 5),
                self::ex('Archer Pull Up', '3-4', 5),
            ], 150)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '5-6s', 5), self::ex('Back Lever Hold', '10-12s', 5),
                self::ex('Muscle Up', '3-4', 5), self::ex('Weighted Chin Up', '3-4', 5),
                self::ex('Archer Pull Up', '3-4', 5),
            ], 150)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '6-8s', 5), self::ex('Back Lever Hold', '12-15s', 5),
                self::ex('Muscle Up', '3-4', 5), self::ex('Weighted Chin Up', '4-5', 5),
                self::ex('Archer Pull Up', '3-4', 5),
            ], 150)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '3-4s', 4), self::ex('Back Lever Hold', '6-8s', 4),
                self::ex('Muscle Up', '1-2', 4), self::ex('Weighted Chin Up', '2-3', 4),
                self::ex('Archer Pull Up', '2-3', 4),
            ], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Pistol Squat', '3/leg'), self::ex('Jump Squat', '10'),
                    self::ex('Bulgarian Split Squat', '8/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '3-5'), self::ex('Calf Raise on Step', '15'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Pistol Squat', '3/leg'), self::ex('Jump Squat', '12'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '18'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(4, 60, 20, [
                    self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '12'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '20'),
                    self::ex('Single Leg Glute Bridge', '12/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '25s/side'),
                ]),
            ]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(2, 60, 20, [
                    self::ex('Pistol Squat', '2/leg'), self::ex('Jump Squat', '6'),
                    self::ex('Bulgarian Split Squat', '5/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '2-3'), self::ex('Calf Raise on Step', '10'),
                    self::ex('Single Leg Glute Bridge', '5/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'),
                    self::ex('Deep Squat Hold', '15s/side'),
                ]),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '8-10s'), self::ex('Dragon Flag', '3-5'),
                self::ex('Straddle Planche', '5s'), self::ex('Full Front Lever Hold', '5s'),
                self::ex('Freestanding Handstand', '10s'), self::ex('Hollow Body Rock', '12'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '10-12s'), self::ex('Dragon Flag', '5-8'),
                self::ex('Straddle Planche', '8s'), self::ex('Full Front Lever Hold', '8s'),
                self::ex('Freestanding Handstand', '15s'), self::ex('Hollow Body Rock', '15'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit', '12-15s'), self::ex('Dragon Flag', '5-8'),
                self::ex('Straddle Planche', '10s'), self::ex('Full Front Lever Hold', '10s'),
                self::ex('Freestanding Handstand', '15s'), self::ex('Hollow Body Rock', '15'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit', '5-6s'), self::ex('Dragon Flag', '2-3'),
                self::ex('Straddle Planche', '5s'), self::ex('Full Front Lever Hold', '5s'),
                self::ex('Freestanding Handstand', '8s'), self::ex('Hollow Body Rock', '8'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 9
    // ========================================================================

    private static function day1StrengthN9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '3-4s', 4), self::ex('Handstand Push Up', '2-4', 4),
                self::ex('deficit HSPU', '2-4', 4), self::ex('Archer Push Up', '2-4', 4),
                self::ex('Pseudo Planche Push Up', '2-4', 4),
            ], 150)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '4-5s', 4), self::ex('Handstand Push Up', '2-4', 4),
                self::ex('deficit HSPU', '2-4', 4), self::ex('Archer Push Up', '2-4', 4),
                self::ex('Pseudo Planche Push Up', '2-4', 4),
            ], 150)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '5-6s', 5), self::ex('Handstand Push Up', '3-4', 5),
                self::ex('deficit HSPU', '3-4', 5), self::ex('Archer Push Up', '3-4', 5),
                self::ex('Pseudo Planche Push Up', '3-4', 5),
            ], 150)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '2-3s', 3), self::ex('Handstand Push Up', '2-3', 3),
                self::ex('deficit HSPU', '2-3', 3), self::ex('Archer Push Up', '2-3', 3),
                self::ex('Pseudo Planche Push Up', '2-3', 3),
            ], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '6-8s', 4), self::ex('Human Flag', '3-4s', 4),
                self::ex('One-Arm Pull Up', '1-2', 4), self::ex('Muscle Up', '2-4', 4),
                self::ex('Weighted Chin Up', '2-4', 4),
            ], 150)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '8-10s', 4), self::ex('Human Flag', '4-5s', 4),
                self::ex('One-Arm Pull Up', '1-2', 4), self::ex('Muscle Up', '2-4', 4),
                self::ex('Weighted Chin Up', '2-4', 4),
            ], 150)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '10-12s', 5), self::ex('Human Flag', '5-6s', 5),
                self::ex('One-Arm Pull Up', '2-3', 5), self::ex('Muscle Up', '3-4', 5),
                self::ex('Weighted Chin Up', '3-4', 5),
            ], 150)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '5-6s', 3), self::ex('Human Flag', '2-3s', 3),
                self::ex('One-Arm Pull Up', '1', 3), self::ex('Muscle Up', '2-3', 3),
                self::ex('Weighted Chin Up', '2-3', 3),
            ], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '10'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '3-5'), self::ex('Calf Raise on Step', '15'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '12'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '18'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(4, 60, 20, [
                    self::ex('Pistol Squat', '5/leg'), self::ex('Jump Squat', '12'),
                    self::ex('Bulgarian Split Squat', '12/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '20'),
                    self::ex('Single Leg Glute Bridge', '12/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '25s/side'),
                ]),
            ]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(2, 60, 20, [
                    self::ex('Pistol Squat', '3/leg'), self::ex('Jump Squat', '6'),
                    self::ex('Bulgarian Split Squat', '5/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '2-3'), self::ex('Calf Raise on Step', '10'),
                    self::ex('Single Leg Glute Bridge', '5/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'),
                    self::ex('Deep Squat Hold', '15s/side'),
                ]),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN9(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '10-12s'), self::ex('Dragon Flag', '5-8'),
                self::ex('Straddle Planche', '5s'), self::ex('Full Front Lever Hold', '8s'),
                self::ex('Freestanding Handstand', '15s'), self::ex('Hollow Body Rock', '15'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '12-15s'), self::ex('Dragon Flag', '5-8'),
                self::ex('Straddle Planche', '8s'), self::ex('Full Front Lever Hold', '10s'),
                self::ex('Freestanding Handstand', '20s'), self::ex('Hollow Body Rock', '15'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit', '15-18s'), self::ex('Dragon Flag', '8-10'),
                self::ex('Straddle Planche', '10s'), self::ex('Full Front Lever Hold', '12s'),
                self::ex('Freestanding Handstand', '20s'), self::ex('Hollow Body Rock', '18'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit', '8s'), self::ex('Dragon Flag', '3-4'),
                self::ex('Straddle Planche', '5s'), self::ex('Full Front Lever Hold', '6s'),
                self::ex('Freestanding Handstand', '10s'), self::ex('Hollow Body Rock', '8'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 10
    // ========================================================================

    private static function day1StrengthN10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '4-5s', 5), self::ex('Freestanding Handstand Push Up', '1-2', 5),
                self::ex('Planche Push Up', '2-3', 5), self::ex('One-Arm Push Up', '2-3', 5),
                self::ex('90 Degree Push Up', '1-2', 5),
            ], 180)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '5-6s', 5), self::ex('Freestanding Handstand Push Up', '1-2', 5),
                self::ex('Planche Push Up', '2-3', 5), self::ex('One-Arm Push Up', '2-3', 5),
                self::ex('90 Degree Push Up', '1-2', 5),
            ], 180)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '6-8s', 5), self::ex('Freestanding Handstand Push Up', '2-3', 5),
                self::ex('Planche Push Up', '3-4', 5), self::ex('One-Arm Push Up', '3-4', 5),
                self::ex('90 Degree Push Up', '2-3', 5),
            ], 180)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Straddle Planche', '3-4s', 4), self::ex('Freestanding Handstand Push Up', '1', 4),
                self::ex('Planche Push Up', '1-2', 4), self::ex('One-Arm Push Up', '1-2', 4),
                self::ex('90 Degree Push Up', '1', 4),
            ], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '8-10s', 5), self::ex('Human Flag', '4-5s', 5),
                self::ex('One-Arm Pull Up', '2-3', 5), self::ex('Muscle Up', '2-3', 5),
                self::ex('Weighted Chin Up', '2-3', 5),
            ], 180)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '10-12s', 5), self::ex('Human Flag', '6-8s', 5),
                self::ex('One-Arm Pull Up', '2-3', 5), self::ex('Muscle Up', '2-3', 5),
                self::ex('Weighted Chin Up', '2-3', 5),
            ], 180)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '12-15s', 5), self::ex('Human Flag', '8-10s', 5),
                self::ex('One-Arm Pull Up', '3-4', 5), self::ex('Muscle Up', '3-4', 5),
                self::ex('Weighted Chin Up', '3-4', 5),
            ], 180)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '6-8s', 4), self::ex('Human Flag', '3-4s', 4),
                self::ex('One-Arm Pull Up', '1-2', 4), self::ex('Muscle Up', '1-2', 4),
                self::ex('Weighted Chin Up', '1-2', 4),
            ], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Dragon Pistol Squat', '2/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '3-5'), self::ex('Calf Raise on Step', '15'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Dragon Pistol Squat', '2/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '18'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(4, 60, 20, [
                    self::ex('Dragon Pistol Squat', '3/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '12/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '4-5'), self::ex('Calf Raise on Step', '20'),
                    self::ex('Single Leg Glute Bridge', '12/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '25s/side'),
                ]),
            ]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(2, 60, 20, [
                    self::ex('Dragon Pistol Squat', '1/leg'), self::ex('Pistol Squat', '3/leg'),
                    self::ex('Bulgarian Split Squat', '5/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl with Band', '2-3'), self::ex('Calf Raise on Step', '10'),
                    self::ex('Single Leg Glute Bridge', '5/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'),
                    self::ex('Deep Squat Hold', '15s/side'),
                ]),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN10(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '10-15s'), self::ex('Dragon Flag', '5-8'),
                self::ex('Human Flag', '5s'), self::ex('Planche Push Up', '3'),
                self::ex('Full Front Lever Hold', '10s'), self::ex('Hollow Body Press', '5'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '15-18s'), self::ex('Dragon Flag', '8-10'),
                self::ex('Human Flag', '8s'), self::ex('Planche Push Up', '5'),
                self::ex('Full Front Lever Hold', '12s'), self::ex('Hollow Body Press', '5-8'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit', '18-20s'), self::ex('Dragon Flag', '8-10'),
                self::ex('Human Flag', '10s'), self::ex('Planche Push Up', '5'),
                self::ex('Full Front Lever Hold', '15s'), self::ex('Hollow Body Press', '5-8'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit', '8-10s'), self::ex('Dragon Flag', '3-4'),
                self::ex('Human Flag', '5s'), self::ex('Planche Push Up', '2'),
                self::ex('Full Front Lever Hold', '8s'), self::ex('Hollow Body Press', '3'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 11
    // ========================================================================

    private static function day1StrengthN11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Planche', '3-4s', 5), self::ex('Freestanding Handstand Push Up', '1-3', 5),
                self::ex('Planche Push Up', '1-3', 5), self::ex('One-Arm Push Up', '1-3', 5),
                self::ex('One-Arm Handstand Push Up', '1-2', 5),
            ], 180)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Planche', '4-5s', 5), self::ex('Freestanding Handstand Push Up', '1-3', 5),
                self::ex('Planche Push Up', '1-3', 5), self::ex('One-Arm Push Up', '1-3', 5),
                self::ex('One-Arm Handstand Push Up', '1-2', 5),
            ], 180)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Planche', '5-6s', 5), self::ex('Freestanding Handstand Push Up', '2-3', 5),
                self::ex('Planche Push Up', '2-3', 5), self::ex('One-Arm Push Up', '2-3', 5),
                self::ex('One-Arm Handstand Push Up', '2-3', 5),
            ], 180)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Planche', '2-3s', 4), self::ex('Freestanding Handstand Push Up', '1-2', 4),
                self::ex('Planche Push Up', '1-2', 4), self::ex('One-Arm Push Up', '1-2', 4),
                self::ex('One-Arm Handstand Push Up', '1', 4),
            ], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '10-12s', 5), self::ex('Human Flag', '6-8s', 5),
                self::ex('One Arm Pull Up', '1-3', 5), self::ex('One Arm Chin Up', '1-3', 5),
                self::ex('Muscle Up', '1-3', 5),
            ], 180)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '12-15s', 5), self::ex('Human Flag', '8-10s', 5),
                self::ex('One Arm Pull Up', '1-3', 5), self::ex('One Arm Chin Up', '1-3', 5),
                self::ex('Muscle Up', '1-3', 5),
            ], 180)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '15-18s', 5), self::ex('Human Flag', '10-12s', 5),
                self::ex('One Arm Pull Up', '2-3', 5), self::ex('One Arm Chin Up', '2-3', 5),
                self::ex('Muscle Up', '2-3', 5),
            ], 180)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '6-8s', 4), self::ex('Human Flag', '4-5s', 4),
                self::ex('One Arm Pull Up', '1-2', 4), self::ex('One Arm Chin Up', '1-2', 4),
                self::ex('Muscle Up', '1-2', 4),
            ], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Dragon Pistol Squat', '3/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '1-3'), self::ex('Calf Raise on Step', '15'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Dragon Pistol Squat', '3/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '2-3'), self::ex('Calf Raise on Step', '18'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(4, 60, 20, [
                    self::ex('Dragon Pistol Squat', '3-5/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '12/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '3-5'), self::ex('Calf Raise on Step', '20'),
                    self::ex('Single Leg Glute Bridge', '12/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '25s/side'),
                ]),
            ]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(2, 60, 20, [
                    self::ex('Dragon Pistol Squat', '2/leg'), self::ex('Pistol Squat', '3/leg'),
                    self::ex('Bulgarian Split Squat', '5/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '1'), self::ex('Calf Raise on Step', '10'),
                    self::ex('Single Leg Glute Bridge', '5/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'),
                    self::ex('Deep Squat Hold', '15s/side'),
                ]),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN11(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '15-20s'), self::ex('Dragon Flag', '8-10'),
                self::ex('Human Flag', '8s'), self::ex('Maltese Push Up', '3'),
                self::ex('Planche', '5s'), self::ex('Hollow Body Press', '5'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '18-22s'), self::ex('Dragon Flag', '8-10'),
                self::ex('Human Flag', '10s'), self::ex('Maltese Push Up', '3-5'),
                self::ex('Planche', '8s'), self::ex('Hollow Body Press', '5-8'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit', '20-25s'), self::ex('Dragon Flag', '10-12'),
                self::ex('Human Flag', '12s'), self::ex('Maltese Push Up', '3-5'),
                self::ex('Planche', '10s'), self::ex('Hollow Body Press', '5-8'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit', '10-12s'), self::ex('Dragon Flag', '4-5'),
                self::ex('Human Flag', '5s'), self::ex('Maltese Push Up', '2'),
                self::ex('Planche', '5s'), self::ex('Hollow Body Press', '3'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // NIVEL 12
    // ========================================================================

    private static function day1StrengthN12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Freestanding Handstand Push Up', '1-3', 5),
                self::ex('Planche Push Up', '1-3', 5),
                self::ex('One-Arm Push Up', '1-3', 5),
                self::ex('Planche', '4-5s', 5),
                self::ex('90 Degree Push Up', '1-3', 5),
            ], 180)]],
            'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Freestanding Handstand Push Up', '1-3', 5),
                self::ex('Planche Push Up', '1-3', 5),
                self::ex('One-Arm Push Up', '1-3', 5),
                self::ex('Planche', '5-6s', 5),
                self::ex('90 Degree Push Up', '1-3', 5),
            ], 180)]],
            'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Freestanding Handstand Push Up', '2-3', 5),
                self::ex('Planche Push Up', '2-3', 5),
                self::ex('One-Arm Push Up', '2-3', 5),
                self::ex('Planche', '6-8s', 5),
                self::ex('90 Degree Push Up', '2-3', 5),
            ], 180)]],
            'deload' => ['name' => 'Día 1: Push Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Freestanding Handstand Push Up', '1-2', 4),
                self::ex('Planche Push Up', '1-2', 4),
                self::ex('One-Arm Push Up', '1-2', 4),
                self::ex('Planche', '3-4s', 4),
                self::ex('90 Degree Push Up', '1-2', 4),
            ], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day2StrengthN12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '12-15s', 5), self::ex('Human Flag', '8-10s', 5),
                self::ex('One Arm Pull Up', '1-3', 5), self::ex('One Arm Chin Up', '1-3', 5),
                self::ex('Muscle Up', '1-3', 5),
            ], 180)]],
            'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '15-18s', 5), self::ex('Human Flag', '10-12s', 5),
                self::ex('One Arm Pull Up', '1-3', 5), self::ex('One Arm Chin Up', '1-3', 5),
                self::ex('Muscle Up', '1-3', 5),
            ], 180)]],
            'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '18-22s', 5), self::ex('Human Flag', '12-15s', 5),
                self::ex('One Arm Pull Up', '2-3', 5), self::ex('One Arm Chin Up', '2-3', 5),
                self::ex('Muscle Up', '2-3', 5),
            ], 180)]],
            'deload' => ['name' => 'Día 2: Pull Deload', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [self::block(1, 1, 60, [
                self::ex('Full Front Lever Hold', '8-10s', 4), self::ex('Human Flag', '5-6s', 4),
                self::ex('One Arm Pull Up', '1-2', 4), self::ex('One Arm Chin Up', '1-2', 4),
                self::ex('Muscle Up', '1-2', 4),
            ], 180)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day3CircuitN12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 3: Circuit Base', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Dragon Pistol Squat', '3-5/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '3-5'), self::ex('Calf Raise on Step', '15'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '30s'), self::ex('Cossack Squat', '5/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'progression' => ['name' => 'Día 3: Circuit Progresión', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(3, 60, 20, [
                    self::ex('Dragon Pistol Squat', '3-5/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '10/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '3-5'), self::ex('Calf Raise on Step', '18'),
                    self::ex('Single Leg Glute Bridge', '10/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '35s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '20s/side'),
                ]),
            ]],
            'intensification' => ['name' => 'Día 3: Circuit Intensificación', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(4, 60, 20, [
                    self::ex('Dragon Pistol Squat', '5/leg'), self::ex('Pistol Squat', '5/leg'),
                    self::ex('Bulgarian Split Squat', '12/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '3-5'), self::ex('Calf Raise on Step', '20'),
                    self::ex('Single Leg Glute Bridge', '12/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '40s'), self::ex('Cossack Squat', '6/side'),
                    self::ex('Deep Squat Hold', '25s/side'),
                ]),
            ]],
            'deload' => ['name' => 'Día 3: Circuit Deload', 'goal' => 'Piernas + Prehab', 'blocks' => [
                self::block(2, 60, 20, [
                    self::ex('Dragon Pistol Squat', '2/leg'), self::ex('Pistol Squat', '3/leg'),
                    self::ex('Bulgarian Split Squat', '5/leg'),
                ]),
                self::block(2, 60, 20, [
                    self::ex('Nordic Curl', '1-3'), self::ex('Calf Raise on Step', '10'),
                    self::ex('Single Leg Glute Bridge', '5/leg'),
                ]),
                self::block(2, 30, 10, [
                    self::ex('Deep Squat Hold', '20s'), self::ex('Cossack Squat', '3/side'),
                    self::ex('Deep Squat Hold', '15s/side'),
                ]),
            ]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function day4CircuitN12(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Día 4: Circuit Base', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '20-30s'), self::ex('Dragon Flag', '8-10'),
                self::ex('Human Flag', '10s'), self::ex('Maltese Push Up', '3-5'),
                self::ex('Planche Push Up', '5'), self::ex('Hollow Body Press', '5'),
            ])]],
            'progression' => ['name' => 'Día 4: Circuit Progresión', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(3, 60, 20, [
                self::ex('L-Sit', '25-30s'), self::ex('Dragon Flag', '10-12'),
                self::ex('Human Flag', '12s'), self::ex('Maltese Push Up', '3-5'),
                self::ex('Planche Push Up', '5-8'), self::ex('Hollow Body Press', '5-8'),
            ])]],
            'intensification' => ['name' => 'Día 4: Circuit Intensificación', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(4, 60, 20, [
                self::ex('L-Sit', '30s'), self::ex('Dragon Flag', '10-12'),
                self::ex('Human Flag', '15s'), self::ex('Maltese Push Up', '5'),
                self::ex('Planche Push Up', '8'), self::ex('Hollow Body Press', '8'),
            ])]],
            'deload' => ['name' => 'Día 4: Circuit Deload', 'goal' => 'Core + Conditioning', 'blocks' => [self::block(2, 60, 20, [
                self::ex('L-Sit', '15s'), self::ex('Dragon Flag', '5'),
                self::ex('Human Flag', '8s'), self::ex('Maltese Push Up', '3'),
                self::ex('Planche Push Up', '3'), self::ex('Hollow Body Press', '3'),
            ])]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
