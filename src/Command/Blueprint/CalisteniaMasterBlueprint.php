<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint completo del programa Calistenia Master (12 niveles).
 */
class CalisteniaMasterBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Fundamentos', 'title' => 'Fundamentos', 'description' => 'Construye la base para superar los tests', 'objective' => 'Desarrollar fuerza básica y técnica correcta', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Push-ups(10), Aus.Pull-ups(12), Squats(20), Plank(45s), Hollow(20s)'],
            2 => ['name' => 'Nivel 2: Consolidación', 'title' => 'Consolidación', 'description' => 'Refuerza fundamentales e introduce progresiones', 'objective' => 'Consolidar técnica y aumentar volumen', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Tests nivel 2'],
            3 => ['name' => 'Nivel 3: Transición', 'title' => 'Transición', 'description' => 'Primer contacto con movimientos intermedios', 'objective' => 'Transición principiante→intermedio', 'difficultyRating' => 3, 'color' => '#4ade80', 'requirementsSummary' => 'Tests nivel 3'],
            4 => ['name' => 'Nivel 4: Intermedio I', 'title' => 'Intermedio I', 'description' => 'Skills: tuck planche, tuck FL, pistol assisted', 'objective' => 'Desarrollar fuerza intermedia y primeros skills', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Tests nivel 4'],
            5 => ['name' => 'Nivel 5: Intermedio II', 'title' => 'Intermedio II', 'description' => 'Progresión de skills y aumento de complejidad', 'objective' => 'Dominar skills básicos', 'difficultyRating' => 5, 'color' => '#60a5fa', 'requirementsSummary' => 'Tests nivel 5'],
            6 => ['name' => 'Nivel 6: Intermedio III', 'title' => 'Intermedio III', 'description' => 'Preparación avanzada: muscle-up, planche lean', 'objective' => 'Cerrar brecha hacia avanzado', 'difficultyRating' => 6, 'color' => '#60a5fa', 'requirementsSummary' => 'Tests nivel 6'],
            7 => ['name' => 'Nivel 7: Avanzado I', 'title' => 'Avanzado I', 'description' => 'Muscle-ups, HSPU, pistol squats, front lever', 'objective' => 'Dominar movimientos avanzados', 'difficultyRating' => 7, 'color' => '#f472b6', 'requirementsSummary' => 'Tests nivel 7'],
            8 => ['name' => 'Nivel 8: Avanzado II', 'title' => 'Avanzado II', 'description' => 'Straddle planche, front lever, human flag', 'objective' => 'Control corporal avanzado', 'difficultyRating' => 8, 'color' => '#f472b6', 'requirementsSummary' => 'Tests nivel 8'],
            9 => ['name' => 'Nivel 9: Avanzado III', 'title' => 'Avanzado III', 'description' => 'Unilateral y skills completos', 'objective' => 'Preparación para élite', 'difficultyRating' => 9, 'color' => '#f472b6', 'requirementsSummary' => 'Tests nivel 9'],
            10 => ['name' => 'Nivel 10: Experto I', 'title' => 'Experto I', 'description' => 'Movimientos de élite: OAPU, full planche', 'objective' => 'Fuerza de élite', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Tests nivel 10'],
            11 => ['name' => 'Nivel 11: Experto II', 'title' => 'Experto II', 'description' => 'Dominio avanzado: OA-HSPU, iron cross prog', 'objective' => 'Aproximación al máximo nivel', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Tests nivel 11'],
            12 => ['name' => 'Nivel 12: Master', 'title' => 'Master', 'description' => 'Calistenia de élite: todos los skills', 'objective' => 'Dominar calistenia a nivel élite', 'difficultyRating' => 10, 'color' => '#ef4444', 'requirementsSummary' => 'Tests nivel 12'],
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getDayData(int $levelNumber, string $dayKey, string $phase): array
    {
        $method = match ($dayKey) {
            'day1_push' => 'dayPush',
            'day2_pull' => 'dayPull',
            'day3_legs' => 'dayLegs',
            'day4_core' => 'dayCore',
            default => throw new \InvalidArgumentException("Día {$dayKey} no válido"),
        };
        $data = self::{$method}($levelNumber, $phase);
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
            $level <= 9 => 180,
            default => 240,
        };

        foreach ($blocks as $index => &$block) {
            if ($index < \count($blocks) - 1) {
                $block['restAfterBlock'] = $rest;
            }
        }

        return $blocks;
    }

    // ========================================================================
    // HELPERS
    // ========================================================================

    private static function block(int $rounds, int $restRounds, int $restEx, array $exercises): array
    {
        return [
            'rounds' => $rounds,
            'restBetweenRounds' => $restRounds,
            'restBetweenExercises' => $restEx,
            'exercises' => $exercises,
        ];
    }

    private static function ex(string $name, string $reps): array
    {
        return ['name' => $name, 'reps' => $reps];
    }

    // ========================================================================
    // PUSH
    // ========================================================================

    private static function dayPush(int $level, string $phase): array
    {
        return match ($level) {
            // --- NIVEL 1 ---
            1 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Preparar Push-up test', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Wall Push Up', '10-15'), self::ex('Knee Push Up', '8-10'),
                    self::ex('Incline Push Up', '8-10'), self::ex('High Plank', '20-30s'),
                ])]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Acercarse al push-up', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Knee Push Up', '10-12'), self::ex('Incline Push Up', '10-12'),
                    self::ex('Standard Push Up', '3-5'), self::ex('High Plank', '30-40s'),
                ])]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Simular test', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Incline Push Up', '12-15'), self::ex('Knee Push Up', '12-15'),
                    self::ex('Standard Push Up', '5-8'), self::ex('High Plank', '45-60s'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 2 ---
            2 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Consolidar push-up', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Incline Push Up', '10-12'), self::ex('Standard Push Up', '5-8'),
                    self::ex('Knee Push Up', '10-12'), self::ex('Wall Handstand Hold', '15-20s'),
                ])]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Aumentar reps', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Standard Push Up', '8-10'), self::ex('Incline Push Up', '12-15'),
                    self::ex('Diamond Push Up', '3-5'), self::ex('Wall Handstand Hold', '20-30s'),
                ])]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Standard Push Up', '10-12'), self::ex('Incline Push Up', '15-18'),
                    self::ex('Diamond Push Up', '5-8'), self::ex('Wall Handstand Hold', '30-40s'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 3 ---
            3 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Dominar push-up', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Standard Push Up', '8-10'), self::ex('Diamond Push Up', '5-8'),
                    self::ex('Incline Push Up', '10-12'), self::ex('Pike Push Up', '3-5'),
                ])]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Introducir declives', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Standard Push Up', '10-12'), self::ex('Diamond Push Up', '8-10'),
                    self::ex('Decline Push Up', '5-8'), self::ex('Pike Push Up', '5-8'),
                ])]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Standard Push Up', '12-15'), self::ex('Diamond Push Up', '8-10'),
                    self::ex('Decline Push Up', '8-10'), self::ex('Pike Push Up', '8-10'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 4 (2 bloques) ---
            4 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Standard Push Up', '10-12'), self::ex('Diamond Push Up', '8-10'),
                        self::ex('Wide Push Up', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Pseudo Planche Push Up', '5-8'), self::ex('Wall Handstand Hold', '20-30s'),
                        self::ex('Planche Lean', '10-15s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Diamond Push Up', '10-12'), self::ex('Wide Push Up', '10-12'),
                        self::ex('Decline Push Up', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Pseudo Planche Push Up', '8-10'), self::ex('Wall Handstand Hold', '25-35s'),
                        self::ex('Planche Lean', '15-20s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Diamond Push Up', '12-15'), self::ex('Wide Push Up', '12-15'),
                        self::ex('Decline Push Up', '10-12'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Pseudo Planche Push Up', '10-12'), self::ex('Wall Handstand Hold', '30-40s'),
                        self::ex('Planche Lean', '20-25s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 5 (2 bloques) ---
            5 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Diamond Push Up', '10-12'), self::ex('Wide Push Up', '10-12'),
                        self::ex('Decline Push Up', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Pseudo Planche Push Up', '8-10'), self::ex('Pike Push Up', '8-10'),
                        self::ex('Wall Handstand Hold', '25-35s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Wide Push Up', '12-15'), self::ex('Decline Push Up', '10-12'),
                        self::ex('Pike Push Up', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Pseudo Planche Push Up', '10-12'), self::ex('Pike Push Up', '10-12'),
                        self::ex('Wall Handstand Hold', '30-40s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Decline Push Up', '12-15'), self::ex('Pike Push Up', '10-12'),
                        self::ex('Pseudo Planche Push Up', '10-12'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Pseudo Planche Push Up', '12-15'), self::ex('Pike Push Up', '12-15'),
                        self::ex('Wall Handstand Hold', '35-45s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 6 (2 bloques) ---
            6 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Wide Push Up', '12-15'), self::ex('Decline Push Up', '10-12'),
                        self::ex('Pike Push Up', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Planche Lean', '15-20s'), self::ex('Deficit Handstand Push Up', '3-5'),
                        self::ex('Wall Handstand Hold', '30-40s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Decline Push Up', '12-15'), self::ex('Pike Push Up', '10-12'),
                        self::ex('Deficit Handstand Push Up', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Planche Lean', '20-25s'), self::ex('Deficit Handstand Push Up', '5-8'),
                        self::ex('Wall Handstand Hold', '35-45s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Pike Push Up', '12-15'), self::ex('Deficit Handstand Push Up', '8-10'),
                        self::ex('Decline Push Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Planche Lean', '25-30s'), self::ex('Deficit Handstand Push Up', '8-10'),
                        self::ex('Wall Handstand Hold', '40-50s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 7 (3 bloques) ---
            7 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Decline Push Up', '12-15'), self::ex('Pike Push Up', '10-12'),
                        self::ex('Deficit Handstand Push Up', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Planche Lean', '20-25s'), self::ex('Pseudo Planche Push Up', '10-12'),
                        self::ex('Wall Handstand Hold', '35-45s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Assisted Dips', '8-10'), self::ex('Diamond Push Up', '10-12'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Pike Push Up', '12-15'), self::ex('Deficit Handstand Push Up', '8-10'),
                        self::ex('Handstand Push Up', '3-5'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Planche Lean', '25-30s'), self::ex('Pseudo Planche Push Up', '12-15'),
                        self::ex('Wall Handstand Hold', '40-50s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Assisted Dips', '10-12'), self::ex('Diamond Push Up', '12-15'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Deficit Handstand Push Up', '10-12'), self::ex('Handstand Push Up', '5-8'),
                        self::ex('Pike Push Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Planche Lean', '30-35s'), self::ex('Pseudo Planche Push Up', '12-15'),
                        self::ex('Wall Handstand Hold', '45-55s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Assisted Dips', '12-15'), self::ex('Archer Push Up', '5-8'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 8 (3 bloques) ---
            8 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Pike Push Up', '12-15'), self::ex('Deficit Handstand Push Up', '8-10'),
                        self::ex('Handstand Push Up', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Planche Lean', '25-30s'), self::ex('Advanced Tuck Planche', '10-15s'),
                        self::ex('Wall Handstand Hold', '40-50s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Push Up', '5-8'), self::ex('Wide Push Up', '12-15'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Deficit Handstand Push Up', '10-12'), self::ex('Handstand Push Up', '8-10'),
                        self::ex('Archer Push Up', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Planche', '15-20s'), self::ex('Planche Lean', '30-35s'),
                        self::ex('Wall Handstand Hold', '45-55s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Push Up', '8-10'), self::ex('Wide Push Up', '15-18'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Handstand Push Up', '10-12'), self::ex('Archer Push Up', '8-10'),
                        self::ex('Deficit Handstand Push Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Advanced Tuck Planche', '20-25s'), self::ex('Planche Lean', '35-40s'),
                        self::ex('Wall Handstand Hold', '50-60s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Push Up', '10-12'), self::ex('Freestanding Handstand', '15-20s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 9 (3 bloques) ---
            9 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Deficit Handstand Push Up', '10-12'), self::ex('Handstand Push Up', '8-10'),
                        self::ex('Archer Push Up', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Straddle Planche', '10-15s'), self::ex('Pseudo Planche Push Up', '12-15'),
                        self::ex('Planche Lean', '35-40s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Freestanding Handstand', '20-25s'), self::ex('Pike Push Up', '12-15'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Handstand Push Up', '10-12'), self::ex('Archer Push Up', '10-12'),
                        self::ex('Freestanding Handstand Push Up', '3-5'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Straddle Planche', '15-20s'), self::ex('Pseudo Planche Push Up', '15-18'),
                        self::ex('Planche Lean', '40-45s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Freestanding Handstand', '25-30s'), self::ex('Planche Push Up', '3-5'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Handstand Push Up', '12-15'), self::ex('Freestanding Handstand Push Up', '5-8'),
                        self::ex('Archer Push Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Straddle Planche', '20-25s'), self::ex('Planche Push Up', '5-8'),
                        self::ex('Planche Lean', '45-50s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Freestanding Handstand', '30-35s'), self::ex('Planche Push Up', '5-8'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 10 (3 bloques) ---
            10 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Handstand Push Up', '10-12'), self::ex('Archer Push Up', '10-12'),
                        self::ex('Freestanding Handstand Push Up', '5-8'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Straddle Planche', '20-25s'), self::ex('Planche Push Up', '5-8'),
                        self::ex('Planche Lean', '45-50s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Push Up', '3-5'), self::ex('90 Degree Push Up', '3-5'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Archer Push Up', '12-15'), self::ex('Freestanding Handstand Push Up', '8-10'),
                        self::ex('Planche Push Up', '5-8'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Straddle Planche', '25-30s'), self::ex('Planche Push Up', '8-10'),
                        self::ex('Planche Lean', '50-55s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Push Up', '5-8'), self::ex('90 Degree Push Up', '5-8'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Freestanding Handstand Push Up', '10-12'), self::ex('Planche Push Up', '8-10'),
                        self::ex('Archer Push Up', '12-15'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Planche', '5-10s'), self::ex('Straddle Planche', '30-35s'),
                        self::ex('Planche Lean', '55-60s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Push Up', '8-10'), self::ex('90 Degree Push Up', '8-10'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 11 (3 bloques) ---
            11 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Archer Push Up', '12-15'), self::ex('Freestanding Handstand Push Up', '10-12'),
                        self::ex('Planche Push Up', '8-10'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Planche', '10-15s'), self::ex('Straddle Planche', '30-35s'),
                        self::ex('Planche Lean', '50-55s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Handstand Push Up', '1-3'), self::ex('One-Arm Push Up', '8-10'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Freestanding Handstand Push Up', '12-15'), self::ex('Planche Push Up', '10-12'),
                        self::ex('Archer Push Up', '15-18'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Planche', '15-20s'), self::ex('Straddle Planche', '35-40s'),
                        self::ex('Planche Lean', '55-60s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Handstand Push Up', '3-5'), self::ex('One-Arm Push Up', '10-12'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Planche Push Up', '12-15'), self::ex('Freestanding Handstand Push Up', '15-18'),
                        self::ex('One-Arm Push Up', '12-15'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Planche', '20-25s'), self::ex('Straddle Planche', '40-45s'),
                        self::ex('Planche Lean', '60s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Handstand Push Up', '5-8'), self::ex('90 Degree Push Up', '10-12'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            // --- NIVEL 12 (3 bloques) ---
            12 => match ($phase) {
                'base' => ['name' => 'Día 1: Push Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Freestanding Handstand Push Up', '12-15'), self::ex('Planche Push Up', '10-12'),
                        self::ex('One-Arm Push Up', '10-12'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Planche', '20-25s'), self::ex('Planche Push Up', '8-10'),
                        self::ex('Planche', '15-20s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Handstand Push Up', '5-8'), self::ex('90 Degree Push Up', '10-12'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 1: Push Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Planche Push Up', '12-15'), self::ex('One-Arm Push Up', '12-15'),
                        self::ex('Freestanding Handstand Push Up', '15-18'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Planche', '25-30s'), self::ex('Planche Push Up', '10-12'),
                        self::ex('Planche', '20-25s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Handstand Push Up', '8-10'), self::ex('90 Degree Push Up', '12-15'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 1: Push Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('One-Arm Push Up', '15-18'), self::ex('Planche Push Up', '15-18'),
                        self::ex('Freestanding Handstand Push Up', '18-20'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Planche', '30-35s'), self::ex('Planche Push Up', '12-15'),
                        self::ex('Planche', '25-30s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('One-Arm Handstand Push Up', '10-12'), self::ex('90 Degree Push Up', '15-18'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    // ========================================================================
    // PULL
    // ========================================================================

    private static function dayPull(int $level, string $phase): array
    {
        return match ($level) {
            1 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Preparar Australian Pull-up', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Australian Pull Up', '8-10'), self::ex('Negative Pull Up', '3-5'),
                    self::ex('Active Hang', '15-20s'), self::ex('Dead Hang', '20-30s'),
                ])]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Aumentar reps', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Australian Pull Up', '10-12'), self::ex('Negative Pull Up', '5-8'),
                    self::ex('Assisted Pull Up', '3-5'), self::ex('Active Hang', '20-30s'),
                ])]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Simular test', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Australian Pull Up', '12-15'), self::ex('Negative Pull Up', '5-8'),
                    self::ex('Assisted Pull Up', '5-8'), self::ex('Active Hang', '30-40s'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Introducir pull-up', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Australian Pull Up', '10-12'), self::ex('Negative Pull Up', '5-8'),
                    self::ex('Assisted Pull Up', '5-8'), self::ex('Standard Pull Up', '1-3'),
                ])]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Aumentar pull-ups', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Australian Pull Up', '12-15'), self::ex('Standard Pull Up', '3-5'),
                    self::ex('Assisted Pull Up', '5-8'), self::ex('Active Hang', '25-35s'),
                ])]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Standard Pull Up', '5-8'), self::ex('Australian Pull Up', '12-15'),
                    self::ex('Assisted Pull Up', '8-10'), self::ex('Active Hang', '30-40s'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Consolidar pull-up', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Standard Pull Up', '5-8'), self::ex('Chin Up', '3-5'),
                    self::ex('Australian Pull Up', '10-12'), self::ex('Tuck Front Lever Hold', '5-10s'),
                ])]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Introducir chin-ups', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Standard Pull Up', '8-10'), self::ex('Chin Up', '5-8'),
                    self::ex('Australian Pull Up', '12-15'), self::ex('Tuck Front Lever Hold', '10-15s'),
                ])]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Standard Pull Up', '8-10'), self::ex('Chin Up', '8-10'),
                    self::ex('Australian Pull Up', '15-18'), self::ex('Tuck Front Lever Hold', '15-20s'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Standard Pull Up', '8-10'), self::ex('Chin Up', '5-8'),
                        self::ex('Wide Grip Pull Up', '3-5'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Tuck Front Lever Hold', '10-15s'), self::ex('Tuck Front Lever Row', '5-8'),
                        self::ex('Active Hang', '20-30s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Standard Pull Up', '10-12'), self::ex('Chin Up', '8-10'),
                        self::ex('Wide Grip Pull Up', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Tuck Front Lever Hold', '15-20s'), self::ex('Tuck Front Lever Row', '8-10'),
                        self::ex('Active Hang', '25-35s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Standard Pull Up', '12-15'), self::ex('Chin Up', '10-12'),
                        self::ex('Wide Grip Pull Up', '8-10'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Tuck Front Lever Hold', '20-25s'), self::ex('Tuck Front Lever Row', '10-12'),
                        self::ex('Active Hang', '30-40s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Chin Up', '8-10'), self::ex('Wide Grip Pull Up', '5-8'),
                        self::ex('Archer Pull Up', '3-5'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Hold', '10-15s'), self::ex('Tuck Front Lever Row', '8-10'),
                        self::ex('Active Hang', '25-35s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Wide Grip Pull Up', '8-10'), self::ex('Archer Pull Up', '5-8'),
                        self::ex('Standard Pull Up', '10-12'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Hold', '15-20s'), self::ex('Tuck Front Lever Row', '10-12'),
                        self::ex('Active Hang', '30-40s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Archer Pull Up', '8-10'), self::ex('Wide Grip Pull Up', '10-12'),
                        self::ex('Chin Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Advanced Tuck Front Lever Hold', '20-25s'), self::ex('Advanced Tuck Front Lever Row', '8-10'),
                        self::ex('Active Hang', '35-45s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Wide Grip Pull Up', '8-10'), self::ex('Archer Pull Up', '5-8'),
                        self::ex('Muscle Up', '1-3'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Row', '8-10'), self::ex('Back Lever Hold', '10-15s'),
                        self::ex('Active Hang', '30-40s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Archer Pull Up', '8-10'), self::ex('Muscle Up', '3-5'),
                        self::ex('Wide Grip Pull Up', '10-12'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Row', '10-12'), self::ex('Back Lever Hold', '15-20s'),
                        self::ex('Active Hang', '35-45s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Muscle Up', '5-8'), self::ex('Archer Pull Up', '10-12'),
                        self::ex('Wide Grip Pull Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Advanced Tuck Front Lever Row', '12-15'), self::ex('Back Lever Row', '8-10'),
                        self::ex('Active Hang', '40-50s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Archer Pull Up', '8-10'), self::ex('Muscle Up', '5-8'),
                        self::ex('Standard Pull Up', '12-15'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Hold', '20-25s'), self::ex('Back Lever Hold', '15-20s'),
                        self::ex('Active Hang', '35-45s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Chin Up', '10-12'), self::ex('Australian Pull Up', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Muscle Up', '8-10'), self::ex('Archer Pull Up', '10-12'),
                        self::ex('Weighted Chin Up', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Row', '10-12'), self::ex('Back Lever Hold', '20-25s'),
                        self::ex('Active Hang', '40-50s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Chin Up', '12-15'), self::ex('Australian Pull Up', '18-20'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Weighted Chin Up', '8-10'), self::ex('Muscle Up', '10-12'),
                        self::ex('Archer Pull Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Advanced Tuck Front Lever Row', '12-15'), self::ex('Back Lever Row', '10-12'),
                        self::ex('Active Hang', '45-55s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Chin Up', '15-18'), self::ex('Archer Chin Up', '5-8'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Muscle Up', '8-10'), self::ex('Weighted Chin Up', '8-10'),
                        self::ex('Archer Pull Up', '10-12'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Row', '10-12'), self::ex('Full Front Lever Hold', '5-10s'),
                        self::ex('Back Lever Hold', '20-25s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Chin Up', '5-8'), self::ex('Wide Grip Pull Up', '12-15'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Weighted Chin Up', '10-12'), self::ex('Archer Pull Up', '12-15'),
                        self::ex('One Arm Pull Up', '1-3'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Full Front Lever Hold', '10-15s'), self::ex('Back Lever Row', '10-12'),
                        self::ex('Back Lever Hold', '25-30s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Chin Up', '8-10'), self::ex('Wide Grip Pull Up', '15-18'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('One Arm Pull Up', '3-5'), self::ex('Weighted Chin Up', '12-15'),
                        self::ex('Muscle Up', '12-15'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Full Front Lever Hold', '15-20s'), self::ex('Back Lever Row', '12-15'),
                        self::ex('Back Lever Hold', '30-35s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Chin Up', '10-12'), self::ex('One Arm Chin Up', '1-3'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            9 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Weighted Chin Up', '10-12'), self::ex('One Arm Pull Up', '3-5'),
                        self::ex('Muscle Up', '12-15'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Full Front Lever Hold', '15-20s'), self::ex('Human Flag', '5-10s'),
                        self::ex('Back Lever Hold', '30-35s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('One Arm Chin Up', '3-5'), self::ex('Archer Chin Up', '10-12'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('One Arm Pull Up', '5-8'), self::ex('One Arm Chin Up', '5-8'),
                        self::ex('Weighted Chin Up', '12-15'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Full Front Lever Hold', '20-25s'), self::ex('Human Flag', '10-15s'),
                        self::ex('Back Lever Hold', '35-40s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Pull Up - Wide', '5-8'), self::ex('Archer Chin Up', '12-15'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('One Arm Pull Up', '8-10'), self::ex('One Arm Chin Up', '8-10'),
                        self::ex('Weighted Chin Up', '15-18'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Full Front Lever Hold', '25-30s'), self::ex('Human Flag', '15-20s'),
                        self::ex('Back Lever Hold', '40-45s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Archer Pull Up - Wide', '8-10'), self::ex('90 Degree Hold to Chin Up', '3-5'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            10 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('One Arm Pull Up', '8-10'), self::ex('One Arm Chin Up', '8-10'),
                        self::ex('Archer Pull Up', '12-15'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Full Front Lever Hold', '25-30s'), self::ex('Full Front Lever Row', '8-10'),
                        self::ex('Human Flag', '20-25s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Muscle Up', '15-18'), self::ex('Weighted Chin Up', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('One Arm Pull Up', '10-12'), self::ex('One Arm Chin Up', '10-12'),
                        self::ex('Archer Pull Up - Wide', '8-10'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Full Front Lever Hold', '30-35s'), self::ex('Full Front Lever Row', '10-12'),
                        self::ex('Human Flag', '25-30s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Muscle Up', '18-20'), self::ex('90 Degree Hold to Chin Up', '5-8'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('One Arm Pull Up', '12-15'), self::ex('One Arm Chin Up', '12-15'),
                        self::ex('Archer Pull Up - Wide', '10-12'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Full Front Lever Hold', '5-10s'), self::ex('Full Front Lever Row', '12-15'),
                        self::ex('Human Flag', '30-35s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Muscle Up', '20-25'), self::ex('90 Degree Hold to Chin Up', '8-10'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            11 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('One Arm Pull Up', '12-15'), self::ex('One Arm Chin Up', '12-15'),
                        self::ex('Archer Pull Up - Wide', '10-12'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Full Front Lever Hold', '10-15s'), self::ex('Full Front Lever Row', '12-15'),
                        self::ex('Human Flag', '30-35s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('90 Degree Hold to Chin Up', '8-10'), self::ex('Muscle Up', '20-25'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('One Arm Pull Up', '15-18'), self::ex('One Arm Chin Up', '15-18'),
                        self::ex('Archer Pull Up - Wide', '12-15'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Full Front Lever Hold', '15-20s'), self::ex('Front Lever Raise', '8-10'),
                        self::ex('Human Flag', '35-40s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('90 Degree Hold to Chin Up', '10-12'), self::ex('Muscle Up', '25-30'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('One Arm Pull Up', '18-20'), self::ex('One Arm Chin Up', '18-20'),
                        self::ex('Archer Pull Up - Wide', '15-18'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Full Front Lever Hold', '20-25s'), self::ex('Front Lever Raise', '10-12'),
                        self::ex('Human Flag', '40-45s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('90 Degree Hold to Chin Up', '12-15'), self::ex('Back Lever Raise', '8-10'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            12 => match ($phase) {
                'base' => ['name' => 'Día 2: Pull Base', 'goal' => 'Fuerza + Skill + Accesorio', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('One Arm Pull Up', '15-18'), self::ex('One Arm Chin Up', '15-18'),
                        self::ex('90 Degree Hold to Chin Up', '10-12'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Full Front Lever Hold', '20-25s'), self::ex('Front Lever Raise', '10-12'),
                        self::ex('Human Flag', '40-45s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Muscle Up', '25-30'), self::ex('Archer Pull Up - Wide', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 2: Pull Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('One Arm Pull Up', '18-20'), self::ex('One Arm Chin Up', '18-20'),
                        self::ex('90 Degree Hold to Chin Up', '12-15'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Full Front Lever Hold', '25-30s'), self::ex('Front Lever Raise', '12-15'),
                        self::ex('Human Flag', '45-50s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Muscle Up', '30-35'), self::ex('Archer Pull Up - Wide', '18-20'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 2: Pull Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('One Arm Pull Up', '20-25'), self::ex('One Arm Chin Up', '20-25'),
                        self::ex('90 Degree Hold to Chin Up', '15-18'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Full Front Lever Hold', '30-35s'), self::ex('Front Lever Raise', '15-18'),
                        self::ex('Human Flag', '50-55s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Muscle Up', '35-40'), self::ex('Archer Pull Up - Wide', '20-25'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    // ========================================================================
    // LEGS
    // ========================================================================

    private static function dayLegs(int $level, string $phase): array
    {
        return match ($level) {
            1 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Preparar Air Squat test', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Air Squat', '10-12'), self::ex('Box Squat', '8-10'),
                    self::ex('Calf Raise on Step', '12-15'), self::ex('Glute Bridge', '12-15'),
                ])]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Llegar a 20 squats', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Air Squat', '15-18'), self::ex('Box Squat', '10-12'),
                    self::ex('Calf Raise on Step', '15-18'), self::ex('Glute Bridge', '15-18'),
                ])]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Simular test', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Air Squat', '18-20'), self::ex('Box Squat', '12-15'),
                    self::ex('Calf Raise on Step', '18-20'), self::ex('Glute Bridge', '18-20'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Introducir lunges', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Air Squat', '12-15'), self::ex('Dumbbell Lunge', '8-10'),
                    self::ex('Calf Raise on Step', '12-15'), self::ex('Glute Bridge', '12-15'),
                ])]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Aumentar dificultad', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Air Squat', '15-18'), self::ex('Dumbbell Lunge', '10-12'),
                    self::ex('Calf Raise on Step', '15-18'), self::ex('Single Leg Glute Bridge', '8-10'),
                ])]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Air Squat', '18-20'), self::ex('Dumbbell Lunge', '12-15'),
                    self::ex('Calf Raise on Step', '18-20'), self::ex('Single Leg Glute Bridge', '10-12'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Introducir split squats', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Air Squat', '15-18'), self::ex('Bulgarian Split Squat', '6-8'),
                    self::ex('Calf Raise on Step', '15-18'), self::ex('Single Leg Glute Bridge', '8-10'),
                ])]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Aumentar split squats', 'blocks' => [self::block(3, 120, 30, [
                    self::ex('Air Squat', '18-20'), self::ex('Bulgarian Split Squat', '8-10'),
                    self::ex('Jump Squat', '8-10'), self::ex('Single Leg Glute Bridge', '10-12'),
                ])]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Volumen y potencia', 'blocks' => [self::block(4, 180, 45, [
                    self::ex('Air Squat', '20-25'), self::ex('Bulgarian Split Squat', '10-12'),
                    self::ex('Jump Squat', '10-12'), self::ex('Single Leg Glute Bridge', '12-15'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Bulgarian Split Squat', '8-10'), self::ex('Jump Squat', '8-10'),
                        self::ex('Assisted Pistol Squat', '3-5'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '3-5'), self::ex('Calf Raise on Step', '15-18'),
                        self::ex('Glute Bridge', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Bulgarian Split Squat', '10-12'), self::ex('Jump Squat', '10-12'),
                        self::ex('Assisted Pistol Squat', '5-8'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '5-8'), self::ex('Calf Raise on Step', '18-20'),
                        self::ex('Single Leg Glute Bridge', '12-15'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Bulgarian Split Squat', '12-15'), self::ex('Jump Squat', '12-15'),
                        self::ex('Assisted Pistol Squat', '8-10'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Nordic Curl', '8-10'), self::ex('Calf Raise on Step', '20-25'),
                        self::ex('Single Leg Glute Bridge', '15-18'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Jump Squat', '10-12'), self::ex('Assisted Pistol Squat', '5-8'),
                        self::ex('Bulgarian Split Squat', '10-12'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '5-8'), self::ex('Calf Raise on Step', '18-20'),
                        self::ex('Single Leg Glute Bridge', '12-15'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Assisted Pistol Squat', '8-10'), self::ex('Jump Squat', '12-15'),
                        self::ex('Bulgarian Split Squat', '12-15'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '8-10'), self::ex('Calf Raise on Step', '20-25'),
                        self::ex('Single Leg Glute Bridge', '15-18'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Assisted Pistol Squat', '10-12'), self::ex('Jump Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '15-18'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Nordic Curl', '10-12'), self::ex('Calf Raise on Step', '25-30'),
                        self::ex('Single Leg Glute Bridge', '18-20'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Assisted Pistol Squat', '8-10'), self::ex('Jump Squat', '10-12'),
                        self::ex('Bulgarian Split Squat', '12-15'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '8-10'), self::ex('Calf Raise on Step', '20-25'),
                        self::ex('Single Leg Glute Bridge', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Box Pistol Squat', '5-8'), self::ex('Jump Squat', '12-15'),
                        self::ex('Bulgarian Split Squat', '15-18'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '10-12'), self::ex('Calf Raise on Step', '25-30'),
                        self::ex('Single Leg Glute Bridge', '18-20'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Box Pistol Squat', '8-10'), self::ex('Jump Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '18-20'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Nordic Curl', '12-15'), self::ex('Calf Raise on Step', '30-35'),
                        self::ex('Single Leg Glute Bridge', '20-25'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Box Pistol Squat', '8-10'), self::ex('Jump Squat', '12-15'),
                        self::ex('Bulgarian Split Squat', '15-18'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '10-12'), self::ex('Calf Raise on Step', '25-30'),
                        self::ex('Single Leg Glute Bridge', '18-20'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '20-25'), self::ex('Glute Bridge', '20-25'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Pistol Squat', '3-5'), self::ex('Jump Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '18-20'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '12-15'), self::ex('Calf Raise on Step', '30-35'),
                        self::ex('Single Leg Glute Bridge', '20-25'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '25-30'), self::ex('Glute Bridge', '25-30'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Pistol Squat', '5-8'), self::ex('Jump Squat', '18-20'),
                        self::ex('Bulgarian Split Squat', '20-25'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Nordic Curl', '15-18'), self::ex('Calf Raise on Step', '35-40'),
                        self::ex('Single Leg Glute Bridge', '25-30'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '30-35'), self::ex('Glute Bridge', '30-35'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Pistol Squat', '5-8'), self::ex('Jump Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '18-20'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '12-15'), self::ex('Calf Raise on Step', '30-35'),
                        self::ex('Single Leg Glute Bridge', '20-25'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '25-30'), self::ex('Glute Bridge', '25-30'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Dragon Pistol Squat', '3-5'), self::ex('Jump Squat', '18-20'),
                        self::ex('Bulgarian Split Squat', '20-25'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '15-18'), self::ex('Calf Raise on Step', '35-40'),
                        self::ex('Single Leg Glute Bridge', '25-30'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '30-35'), self::ex('Glute Bridge', '30-35'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Dragon Pistol Squat', '5-8'), self::ex('Jump Squat', '20-25'),
                        self::ex('Bulgarian Split Squat', '25-30'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Nordic Curl', '18-20'), self::ex('Calf Raise on Step', '40-45'),
                        self::ex('Single Leg Glute Bridge', '30-35'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '35-40'), self::ex('Glute Bridge', '35-40'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            9 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab + Accesorio', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Pistol Squat', '8-10'), self::ex('Dragon Pistol Squat', '5-8'),
                        self::ex('Bulgarian Split Squat', '20-25'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '15-18'), self::ex('Calf Raise on Step', '35-40'),
                        self::ex('Single Leg Glute Bridge', '25-30'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '30-35'), self::ex('Glute Bridge', '30-35'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 30, [
                        self::ex('Dragon Pistol Squat', '8-10'), self::ex('Pistol Squat', '10-12'),
                        self::ex('Bulgarian Split Squat', '25-30'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Nordic Curl', '18-20'), self::ex('Calf Raise on Step', '40-45'),
                        self::ex('Single Leg Glute Bridge', '30-35'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '35-40'), self::ex('Glute Bridge', '35-40'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Dragon Pistol Squat', '10-12'), self::ex('Pistol Squat', '12-15'),
                        self::ex('Bulgarian Split Squat', '30-35'),
                    ]),
                    self::block(2, 120, 45, [
                        self::ex('Nordic Curl', '20-25'), self::ex('Calf Raise on Step', '45-50'),
                        self::ex('Single Leg Glute Bridge', '35-40'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Air Squat', '40-45'), self::ex('Glute Bridge', '40-45'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            10 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab + Accesorio', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Dragon Pistol Squat', '8-10'), self::ex('Pistol Squat', '10-12'),
                        self::ex('Bulgarian Split Squat', '25-30'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Nordic Curl', '18-20'), self::ex('Calf Raise on Step', '40-45'),
                        self::ex('Single Leg Glute Bridge', '30-35'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '35-40'), self::ex('Glute Bridge', '35-40'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Dragon Pistol Squat', '10-12'), self::ex('Pistol Squat', '12-15'),
                        self::ex('Bulgarian Split Squat', '30-35'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Nordic Curl', '20-25'), self::ex('Calf Raise on Step', '45-50'),
                        self::ex('Single Leg Glute Bridge', '35-40'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '40-45'), self::ex('Glute Bridge', '40-45'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Dragon Pistol Squat', '12-15'), self::ex('Pistol Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '35-40'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Nordic Curl', '25-30'), self::ex('Calf Raise on Step', '50-55'),
                        self::ex('Single Leg Glute Bridge', '40-45'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '45-50'), self::ex('Glute Bridge', '45-50'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            11 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab + Accesorio', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Dragon Pistol Squat', '10-12'), self::ex('Pistol Squat', '12-15'),
                        self::ex('Bulgarian Split Squat', '30-35'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Nordic Curl', '20-25'), self::ex('Calf Raise on Step', '45-50'),
                        self::ex('Single Leg Glute Bridge', '35-40'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '40-45'), self::ex('Glute Bridge', '40-45'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 45, [
                        self::ex('Dragon Pistol Squat', '12-15'), self::ex('Pistol Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '35-40'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Nordic Curl', '25-30'), self::ex('Calf Raise on Step', '50-55'),
                        self::ex('Single Leg Glute Bridge', '40-45'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '45-50'), self::ex('Glute Bridge', '45-50'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Dragon Pistol Squat', '15-18'), self::ex('Pistol Squat', '18-20'),
                        self::ex('Bulgarian Split Squat', '40-45'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Nordic Curl', '30-35'), self::ex('Calf Raise on Step', '55-60'),
                        self::ex('Single Leg Glute Bridge', '45-50'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '50-55'), self::ex('Glute Bridge', '50-55'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            12 => match ($phase) {
                'base' => ['name' => 'Día 3: Legs Base', 'goal' => 'Fuerza + Prehab + Accesorio', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Dragon Pistol Squat', '12-15'), self::ex('Pistol Squat', '15-18'),
                        self::ex('Bulgarian Split Squat', '35-40'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Nordic Curl', '25-30'), self::ex('Calf Raise on Step', '50-55'),
                        self::ex('Single Leg Glute Bridge', '40-45'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '45-50'), self::ex('Glute Bridge', '45-50'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 3: Legs Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Dragon Pistol Squat', '15-18'), self::ex('Pistol Squat', '18-20'),
                        self::ex('Bulgarian Split Squat', '40-45'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Nordic Curl', '30-35'), self::ex('Calf Raise on Step', '55-60'),
                        self::ex('Single Leg Glute Bridge', '45-50'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '50-55'), self::ex('Glute Bridge', '50-55'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 3: Legs Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 60, [
                        self::ex('Dragon Pistol Squat', '18-20'), self::ex('Pistol Squat', '20-25'),
                        self::ex('Bulgarian Split Squat', '45-50'),
                    ]),
                    self::block(3, 150, 45, [
                        self::ex('Nordic Curl', '35-40'), self::ex('Calf Raise on Step', '60-65'),
                        self::ex('Single Leg Glute Bridge', '50-55'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Air Squat', '55-60'), self::ex('Glute Bridge', '55-60'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    // ========================================================================
    // CORE
    // ========================================================================

    private static function dayCore(int $level, string $phase): array
    {
        return match ($level) {
            1 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Preparar Plank y Hollow Body', 'blocks' => [self::block(3, 90, 20, [
                    self::ex('Dead Bug', '8-10'), self::ex('High Plank', '15-20s'),
                    self::ex('Hollow Body Hold', '10-15s'), self::ex('Knee Raise', '8-10'),
                ])]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Resistencia plank', 'blocks' => [self::block(3, 120, 25, [
                    self::ex('High Plank', '30-40s'), self::ex('Hollow Body Hold', '15-20s'),
                    self::ex('Dead Bug', '10-12'), self::ex('Knee Raise', '10-12'),
                ])]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Simular tests', 'blocks' => [self::block(4, 180, 30, [
                    self::ex('High Plank', '45-60s'), self::ex('Hollow Body Hold', '20-30s'),
                    self::ex('Dead Bug', '12-15'), self::ex('Knee Raise', '12-15'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Introducir L-sit', 'blocks' => [self::block(3, 90, 20, [
                    self::ex('High Plank', '30-40s'), self::ex('Hollow Body Hold', '15-20s'),
                    self::ex('Dead Bug', '10-12'), self::ex('L-Sit', '5-10s'),
                ])]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Aumentar holds', 'blocks' => [self::block(3, 120, 25, [
                    self::ex('High Plank', '40-50s'), self::ex('Hollow Body Hold', '20-25s'),
                    self::ex('L-Sit', '10-15s'), self::ex('Leg Raise', '8-10'),
                ])]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Volumen core', 'blocks' => [self::block(4, 180, 30, [
                    self::ex('High Plank', '50-60s'), self::ex('Hollow Body Hold', '25-30s'),
                    self::ex('L-Sit', '15-20s'), self::ex('Leg Raise', '10-12'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Introducir dragon flag', 'blocks' => [self::block(3, 90, 20, [
                    self::ex('Hollow Body Hold', '20-25s'), self::ex('L-Sit', '10-15s'),
                    self::ex('Leg Raise', '8-10'), self::ex('Side Plank', '15-20s'),
                ])]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Aumentar dificultad', 'blocks' => [self::block(3, 120, 25, [
                    self::ex('Hollow Body Hold', '25-30s'), self::ex('L-Sit', '15-20s'),
                    self::ex('Leg Raise', '10-12'), self::ex('Side Plank', '20-25s'),
                ])]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(4, 180, 30, [
                    self::ex('Hollow Body Hold', '30-35s'), self::ex('L-Sit', '20-25s'),
                    self::ex('Leg Raise', '12-15'), self::ex('Side Plank', '25-30s'),
                ])]],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Prehab', 'blocks' => [
                    self::block(3, 90, 20, [
                        self::ex('L-Sit', '15-20s'), self::ex('Leg Raise', '10-12'),
                        self::ex('Dragon Flag Negative', '3-5'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Tuck Planche', '5-10s'), self::ex('Back Lever Hold', '10-15s'),
                        self::ex('Hollow Body Hold', '25-30s'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('L-Sit', '20-25s'), self::ex('Leg Raise', '12-15'),
                        self::ex('Dragon Flag Negative', '5-8'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Tuck Planche', '10-15s'), self::ex('Back Lever Hold', '15-20s'),
                        self::ex('Hollow Body Hold', '30-35s'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('L-Sit', '25-30s'), self::ex('Leg Raise', '15-18'),
                        self::ex('Dragon Flag Negative', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Tuck Planche', '15-20s'), self::ex('Back Lever Hold', '20-25s'),
                        self::ex('Hollow Body Hold', '35-40s'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            5 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Prehab', 'blocks' => [
                    self::block(3, 90, 20, [
                        self::ex('L-Sit', '20-25s'), self::ex('Dragon Flag Negative', '5-8'),
                        self::ex('Tuck Planche', '10-15s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Advanced Tuck Front Lever Hold', '15-20s'), self::ex('Back Lever Hold', '15-20s'),
                        self::ex('Hollow Body Rock', '8-10'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('L-Sit', '25-30s'), self::ex('Dragon Flag Negative', '8-10'),
                        self::ex('Tuck Planche', '15-20s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Advanced Tuck Front Lever Hold', '20-25s'), self::ex('Back Lever Hold', '20-25s'),
                        self::ex('Hollow Body Rock', '10-12'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('L-Sit', '30-35s'), self::ex('Dragon Flag Negative', '3-5'),
                        self::ex('Tuck Planche', '20-25s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Hold', '25-30s'), self::ex('Back Lever Row', '8-10'),
                        self::ex('Hollow Body Rock', '12-15'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            6 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Prehab', 'blocks' => [
                    self::block(3, 90, 20, [
                        self::ex('L-Sit', '25-30s'), self::ex('Dragon Flag Negative', '5-8'),
                        self::ex('Advanced Tuck Planche', '10-15s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Advanced Tuck Front Lever Hold', '20-25s'), self::ex('Back Lever Row', '8-10'),
                        self::ex('Hollow Body Rock', '10-12'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('L-Sit', '30-35s'), self::ex('Dragon Flag Negative', '8-10'),
                        self::ex('Advanced Tuck Planche', '15-20s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Advanced Tuck Front Lever Row', '8-10'), self::ex('Back Lever Row', '10-12'),
                        self::ex('Hollow Body Rock', '12-15'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('L-Sit', '35-40s'), self::ex('Dragon Flag', '3-5'),
                        self::ex('Advanced Tuck Planche', '20-25s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Advanced Tuck Front Lever Row', '10-12'), self::ex('Back Lever Row', '12-15'),
                        self::ex('Hollow Body Rock', '15-18'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            7 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Estático + Finisher', 'blocks' => [
                    self::block(3, 90, 20, [
                        self::ex('L-Sit', '30-35s'), self::ex('Dragon Flag', '3-5'),
                        self::ex('Straddle Planche', '10-15s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Advanced Tuck Front Lever Hold', '25-30s'), self::ex('Human Flag', '5-10s'),
                        self::ex('Back Lever Hold', '25-30s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Rock', '15-18'), self::ex('Leg Raise', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('L-Sit', '35-40s'), self::ex('Dragon Flag', '5-8'),
                        self::ex('Straddle Planche', '15-20s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Advanced Tuck Front Lever Row', '10-12'), self::ex('Human Flag', '10-15s'),
                        self::ex('Back Lever Hold', '30-35s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Rock', '18-20'), self::ex('Leg Raise', '18-20'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('L-Sit', '40-45s'), self::ex('Dragon Flag', '8-10'),
                        self::ex('Straddle Planche', '20-25s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Full Front Lever Hold', '5-10s'), self::ex('Human Flag', '15-20s'),
                        self::ex('Back Lever Hold', '35-40s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Rock', '20-25'), self::ex('Ab Wheel Rollout', '8-10'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            8 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Estático + Finisher', 'blocks' => [
                    self::block(3, 90, 20, [
                        self::ex('Dragon Flag', '5-8'), self::ex('Straddle Planche', '15-20s'),
                        self::ex('Full Front Lever Hold', '10-15s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Human Flag', '10-15s'), self::ex('Planche Lean', '10-15s'),
                        self::ex('Back Lever Row', '10-12'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '8-10'), self::ex('Leg Raise', '18-20'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('Dragon Flag', '8-10'), self::ex('Straddle Planche', '20-25s'),
                        self::ex('Full Front Lever Hold', '15-20s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Human Flag', '15-20s'), self::ex('Planche Lean', '15-20s'),
                        self::ex('Back Lever Row', '12-15'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '10-12'), self::ex('Ab Wheel Rollout', '10-12'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('Dragon Flag', '10-12'), self::ex('Straddle Planche', '25-30s'),
                        self::ex('Full Front Lever Hold', '20-25s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Human Flag', '20-25s'), self::ex('Planche Lean', '20-25s'),
                        self::ex('Back Lever Raise', '8-10'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '12-15'), self::ex('Ab Wheel Rollout', '12-15'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            9 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Estático + Finisher', 'blocks' => [
                    self::block(3, 90, 20, [
                        self::ex('Dragon Flag', '8-10'), self::ex('Full Front Lever Hold', '15-20s'),
                        self::ex('Human Flag', '15-20s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Planche Lean', '15-20s'), self::ex('Planche Lean', '40-45s'),
                        self::ex('Back Lever Hold', '35-40s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '10-12'), self::ex('Ab Wheel Rollout', '10-12'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('Dragon Flag', '10-12'), self::ex('Full Front Lever Hold', '20-25s'),
                        self::ex('Human Flag', '20-25s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Planche Lean', '20-25s'), self::ex('Planche Lean', '45-50s'),
                        self::ex('Back Lever Hold', '40-45s'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '12-15'), self::ex('Ab Wheel Rollout', '12-15'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('Dragon Flag', '12-15'), self::ex('Full Front Lever Hold', '25-30s'),
                        self::ex('Human Flag', '25-30s'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Maltese Push Up', '3-5'), self::ex('Planche Lean', '50-55s'),
                        self::ex('Back Lever Raise', '8-10'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '15-18'), self::ex('Ab Wheel Rollout', '15-18'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            10 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Estático + Finisher', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('Full Front Lever Hold', '25-30s'), self::ex('Human Flag', '25-30s'),
                        self::ex('Maltese Push Up', '5-8'),
                    ]),
                    self::block(3, 90, 30, [
                        self::ex('Planche', '10-15s'), self::ex('Planche Lean', '50-55s'),
                        self::ex('Full Front Lever Row', '8-10'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '12-15'), self::ex('Ab Wheel Rollout', '12-15'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('Full Front Lever Hold', '30-35s'), self::ex('Human Flag', '30-35s'),
                        self::ex('Maltese Push Up', '8-10'),
                    ]),
                    self::block(3, 90, 30, [
                        self::ex('Planche', '15-20s'), self::ex('Planche Lean', '55-60s'),
                        self::ex('Full Front Lever Row', '10-12'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '15-18'), self::ex('Ab Wheel Rollout', '15-18'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('Full Front Lever Hold', '35-40s'), self::ex('Human Flag', '35-40s'),
                        self::ex('Maltese Push Up', '10-12'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Planche', '20-25s'), self::ex('Planche Push Up', '5-8'),
                        self::ex('Front Lever Raise', '8-10'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Hollow Body Press', '18-20'), self::ex('Ab Wheel Rollout', '18-20'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            11 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Estático + Finisher', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('Human Flag', '30-35s'), self::ex('Maltese Push Up', '8-10'),
                        self::ex('Planche', '20-25s'),
                    ]),
                    self::block(3, 90, 30, [
                        self::ex('Advanced Tuck Planche', '5-10s'), self::ex('Planche Push Up', '8-10'),
                        self::ex('Front Lever Raise', '10-12'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '15-18'), self::ex('Ab Wheel Rollout', '15-18'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 120, 25, [
                        self::ex('Human Flag', '35-40s'), self::ex('Maltese Push Up', '10-12'),
                        self::ex('Planche', '25-30s'),
                    ]),
                    self::block(3, 90, 30, [
                        self::ex('Advanced Tuck Planche', '10-15s'), self::ex('Planche Push Up', '10-12'),
                        self::ex('Front Lever Raise', '12-15'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '18-20'), self::ex('Ab Wheel Rollout', '18-20'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('Human Flag', '40-45s'), self::ex('Maltese Push Up', '12-15'),
                        self::ex('Planche', '30-35s'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Advanced Tuck Planche', '15-20s'), self::ex('Planche Push Up', '12-15'),
                        self::ex('Front Lever Raise', '15-18'),
                    ]),
                    self::block(2, 90, 30, [
                        self::ex('Hollow Body Press', '20-25'), self::ex('Ab Wheel Rollout', '20-25'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            12 => match ($phase) {
                'base' => ['name' => 'Día 4: Core Base', 'goal' => 'Skill + Estático + Finisher', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('Human Flag', '40-45s'), self::ex('Maltese Push Up', '10-12'),
                        self::ex('Planche Push Up', '8-10'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Advanced Tuck Planche', '15-20s'), self::ex('Full Front Lever Hold', '25-30s'),
                        self::ex('Front Lever Raise', '10-12'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '18-20'), self::ex('Ab Wheel Rollout', '18-20'),
                    ]),
                ]],
                'progression' => ['name' => 'Día 4: Core Progresión', 'goal' => 'Progresión', 'blocks' => [
                    self::block(3, 150, 30, [
                        self::ex('Human Flag', '45-50s'), self::ex('Maltese Push Up', '12-15'),
                        self::ex('Planche Push Up', '10-12'),
                    ]),
                    self::block(3, 120, 45, [
                        self::ex('Advanced Tuck Planche', '20-25s'), self::ex('Full Front Lever Hold', '30-35s'),
                        self::ex('Front Lever Raise', '12-15'),
                    ]),
                    self::block(2, 60, 20, [
                        self::ex('Hollow Body Press', '20-25'), self::ex('Ab Wheel Rollout', '20-25'),
                    ]),
                ]],
                'intensification' => ['name' => 'Día 4: Core Intensificación', 'goal' => 'Intensificación', 'blocks' => [
                    self::block(3, 180, 30, [
                        self::ex('Human Flag','50-55s'), self::ex('Maltese Push Up','15-18'),
                        self::ex('Planche Push Up','12-15'),
                    ]),
                    self::block(3,150,45,[
                        self::ex('Advanced Tuck Planche','25-30s'), self::ex('Full Front Lever Hold','35-40s'),
                        self::ex('Front Lever Raise','15-18'),
                    ]),
                    self::block(2,90,30,[
                        self::ex('Hollow Body Press','25-30'), self::ex('Ab Wheel Rollout','25-30'),
                    ]),
                ]],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }
}
