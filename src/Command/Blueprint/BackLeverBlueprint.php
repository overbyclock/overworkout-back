<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Back Lever Mastery (8 niveles).
 * Especializacion del Master v4: extension de hombro, German hang, tuck,
 * advanced tuck, straddle y full back lever. Estructura: 4 sesiones por fase
 * (A=Strength, B=Back Lever, C=Prehab, D=Core).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas al back lever:
 * - Los holds de back lever siempre tras German hang (hombro caliente).
 * - Descanso completo entre series de back lever (120-240 s).
 * - Negativas controladas nunca al fallo.
 * - Molestia matinal >2/10 en hombro = congela el gesto un ciclo.
 */
class BackLeverBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Extension de Hombro', 'title' => 'Base de Extension de Hombro', 'description' => 'Preparar la extension de hombro, German hang y pilar antes de cargar el back lever', 'objective' => 'Ganar confianza en extension de hombro con German hang, skin the cat y hollow/arch para sostener el back lever futuro', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(5), Active Hang(30s)', 'skillFocus' => 'German Hang, Skin The Cat, Hollow Body Hold'],
            2 => ['name' => 'Nivel 2: Primer Tuck', 'title' => 'Primer Tuck', 'description' => 'Primer contacto con el tuck back lever asistido y corto', 'objective' => 'Sostener tuck back lever 5-8 s con buena linea y hombros calientes', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(5), Active Hang(30s), German Hang(20s)', 'skillFocus' => 'Tuck Back Lever, German Hang, Dead Hang'],
            3 => ['name' => 'Nivel 3: Tuck Consolidado', 'title' => 'Tuck Consolidado', 'description' => 'Tuck back lever solido y primeras back lever rows', 'objective' => 'Consolidar tuck 8-10 s y remar en posicion de back lever sin perder la extension', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Back Lever(5s), German Hang(25s), Back Lever Row(3)', 'skillFocus' => 'Tuck Back Lever, Back Lever Row, German Hang'],
            4 => ['name' => 'Nivel 4: Advanced Tuck', 'title' => 'Advanced Tuck', 'description' => 'Transicion al advanced tuck back lever con mantenimiento de tuck', 'objective' => 'Sostener advanced tuck 5-8 s manteniendo tuck solido por encima de 10 s', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Back Lever(8s), Back Lever Row(5), German Hang(30s)', 'skillFocus' => 'Advanced Tuck Back Lever, Tuck Back Lever, Back Lever Row'],
            5 => ['name' => 'Nivel 5: Straddle Entrada', 'title' => 'Straddle Entrada', 'description' => 'Primer straddle back lever y profundidad de German hang', 'objective' => 'Sostener straddle back lever 4-6 s con German hang de 30-40 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Advanced Tuck Back Lever(5s), German Hang(40s), Back Lever Row(8)', 'skillFocus' => 'Straddle Back Lever, Advanced Tuck Back Lever, German Hang'],
            6 => ['name' => 'Nivel 6: Straddle Solido', 'title' => 'Straddle Solido', 'description' => 'Straddle back lever estable, back lever rows fuertes y primer contacto con full', 'objective' => 'Sostener straddle 6-8 s, remar 10-12 reps y tocar el full con control', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Straddle Back Lever(4s), Advanced Tuck Back Lever(8s), German Hang(45s)', 'skillFocus' => 'Straddle Back Lever, Back Lever Row, Back Lever Hold'],
            7 => ['name' => 'Nivel 7: Full Back Lever', 'title' => 'Full Back Lever', 'description' => 'Full back lever con asistencia de banda y straddle de mantenimiento', 'objective' => 'Sostener full back lever 3-5 s limpio y 5-8 s asistido con banda', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Straddle Back Lever(6s), Back Lever Row(10), German Hang(50s)', 'skillFocus' => 'Back Lever Hold, Straddle Back Lever, Back Lever Row'],
            8 => ['name' => 'Nivel 8: Dominio', 'title' => 'Dominio', 'description' => 'Back lever completo solido, rows pesadas y German hang de 60 s', 'objective' => 'Sostener full back lever 8-10 s, remar 12-15 reps y German hang 60 s', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Back Lever Hold(5s), Back Lever Row(10), German Hang(60s)', 'skillFocus' => 'Back Lever Hold, Back Lever Row, German Hang'],
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
    // SESSION A — STRENGTH (Traccion y empuje de soporte)
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
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza basica de empuje y traccion; activacion de escapula antes de la extension de hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '3-5', 3), self::ex('Standard Push Up', '10-12', 3), self::ex('Ring Row - Elevated Feet', '8-10', 3), self::ex('Active Hang', '15s', 2)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Aumentar reps de empuje y traccion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '5-6', 3), self::ex('Standard Push Up', '12-15', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3), self::ex('Active Hang', '20s', 2)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen maximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '6-8', 4), self::ex('Standard Push Up', '15', 4), self::ex('Ring Row - Elevated Feet', '12-15', 4), self::ex('Active Hang', '20-30s', 2)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '3-4', 2), self::ex('Standard Push Up', '8-10', 2), self::ex('Ring Row - Elevated Feet', '8-10', 2), self::ex('Active Hang', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion estricta y empuje estable para soportar el primer tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '5-6', 3), self::ex('Standard Push Up', '12-15', 3), self::ex('Weighted Ring Row', '8-10', 3), self::ex('Active Hang', '20s', 2)], 90)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '6-8', 3), self::ex('Standard Push Up', '15', 3), self::ex('Weighted Ring Row', '10-12', 3), self::ex('Active Hang', '20-30s', 2)], 90)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '8-10', 4), self::ex('Standard Push Up', '15-18', 4), self::ex('Weighted Ring Row', '12-15', 4), self::ex('Active Hang', '30s', 2)], 90)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Pull Up', '4-5', 2), self::ex('Standard Push Up', '10-12', 2), self::ex('Weighted Ring Row', '8-10', 2), self::ex('Active Hang', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion estricta con variedad de agarres y empuje de soporte', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 3), self::ex('Chin Up', '5-6', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3), self::ex('Standard Push Up', '12-15', 2)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 3), self::ex('Chin Up', '6-8', 3), self::ex('Ring Row - Elevated Feet', '12-15', 3), self::ex('Standard Push Up', '15', 2)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 4), self::ex('Chin Up', '8-10', 4), self::ex('Ring Row - Elevated Feet', '15', 4), self::ex('Standard Push Up', '15-18', 2)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '4-5', 2), self::ex('Chin Up', '4-5', 2), self::ex('Ring Row - Elevated Feet', '8-10', 2), self::ex('Standard Push Up', '10-12', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion estricta y contrapeso horizontal pesado', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 3), self::ex('Weighted Ring Row', '8-10', 3), self::ex('Standard Push Up', '15', 3), self::ex('Ring Dip', '5-6', 2)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Weighted Ring Row', '10-12', 3), self::ex('Standard Push Up', '15-18', 3), self::ex('Ring Dip', '6-8', 2)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 4), self::ex('Weighted Ring Row', '12-15', 4), self::ex('Standard Push Up', '18-20', 3), self::ex('Ring Dip', '8-10', 3)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '4-5', 2), self::ex('Weighted Ring Row', '8-10', 2), self::ex('Standard Push Up', '10-12', 2), self::ex('Ring Dip', '4-5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion alta y empuje en anillas para la straddle back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Weighted Ring Row', '10-12', 3), self::ex('Standard Push Up', '15-18', 3), self::ex('Ring Dip', '6-8', 2)], 120)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Ring Row', '12-15', 3), self::ex('Standard Push Up', '18-20', 3), self::ex('Ring Dip', '8-10', 2)], 120)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 4), self::ex('Weighted Ring Row', '12-15', 4), self::ex('Standard Push Up', '20-22', 3), self::ex('Ring Dip', '10-12', 3)], 120)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 2), self::ex('Weighted Ring Row', '8-10', 2), self::ex('Standard Push Up', '10-12', 2), self::ex('Ring Dip', '5-6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion maxima fresca y empuje en anillas', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Ring Row', '12-15', 3), self::ex('Ring Dip', '8-10', 3), self::ex('Chin Up', '6-8', 2)], 150)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Ring Row', '12-15', 3), self::ex('Ring Dip', '10-12', 3), self::ex('Chin Up', '8-10', 2)], 150)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 4), self::ex('Weighted Ring Row', '15', 4), self::ex('Ring Dip', '12-15', 4), self::ex('Chin Up', '10-12', 2)], 150)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Weighted Ring Row', '8-10', 2), self::ex('Ring Dip', '6-8', 2), self::ex('Chin Up', '5-6', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Fuerza de traccion y empuje en anillas para el full back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Ring Row', '10-12', 3), self::ex('Ring Dip', '8-10', 3), self::ex('Chin Up', '6-8', 2)], 150)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Ring Row', '12-15', 3), self::ex('Ring Dip', '10-12', 3), self::ex('Chin Up', '8-10', 2)], 150)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 4), self::ex('Weighted Ring Row', '12-15', 4), self::ex('Ring Dip', '12-15', 4), self::ex('Chin Up', '10-12', 2)], 150)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Weighted Ring Row', '8-10', 2), self::ex('Ring Dip', '6-8', 2), self::ex('Chin Up', '5-6', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion A: Strength Base', 'goal' => 'Traccion elite y empuje en anillas para el dominio del back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Ring Row', '12-15', 3), self::ex('Ring Dip', '10-12', 3), self::ex('Chin Up', '8-10', 2)], 150)]],
            'progression' => ['name' => 'Sesion A: Strength Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 3), self::ex('Weighted Ring Row', '15', 3), self::ex('Ring Dip', '12-15', 3), self::ex('Chin Up', '10-12', 2)], 150)]],
            'intensification' => ['name' => 'Sesion A: Strength Intensificacion', 'goal' => 'Intensificacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '15', 4), self::ex('Weighted Ring Row', '15-18', 4), self::ex('Ring Dip', '15', 4), self::ex('Chin Up', '12-15', 2)], 150)]],
            'deload' => ['name' => 'Sesion A: Strength Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Weighted Ring Row', '8-10', 2), self::ex('Ring Dip', '6-8', 2), self::ex('Chin Up', '5-6', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — BACK LEVER (progresion especifica del nivel)
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
            'base' => ['name' => 'Sesion B: Extension de Hombro Base', 'goal' => 'German hang y skin the cat: preparar la extension de hombro antes del tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '10-15s', 3), self::ex('Skin The Cat', '3', 2), self::ex('Shoulder Dislocate', '8', 2), self::ex('Dead Hang', '20-30s', 2)], 90)]],
            'progression' => ['name' => 'Sesion B: Extension de Hombro Progresion', 'goal' => 'Aumentar tiempo de German hang', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 3), self::ex('Skin The Cat', '3-4', 2), self::ex('Shoulder Dislocate', '10', 2), self::ex('Dead Hang', '30s', 2)], 90)]],
            'intensification' => ['name' => 'Sesion B: Extension de Hombro Intensificacion', 'goal' => 'Volumen de extension', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Skin The Cat', '4-5', 2), self::ex('Shoulder Dislocate', '10', 2), self::ex('Dead Hang', '30-45s', 2)], 90)]],
            'deload' => ['name' => 'Sesion B: Extension de Hombro Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '10-12s', 2), self::ex('Skin The Cat', '2', 2), self::ex('Shoulder Dislocate', '8', 2), self::ex('Dead Hang', '20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Primer Tuck Base', 'goal' => 'Primer tuck back lever asistido y corto (dosis N8-D del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '5-8s', 3), self::ex('German Hang', '15-20s', 2), self::ex('Skin The Cat', '3', 2), self::ex('Dead Hang', '30s', 2)], 120)]],
            'progression' => ['name' => 'Sesion B: Primer Tuck Progresion', 'goal' => 'Aumentar tiempo en tuck back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '6-10s', 3), self::ex('German Hang', '20-25s', 2), self::ex('Skin The Cat', '3-4', 2), self::ex('Dead Hang', '30-45s', 2)], 120)]],
            'intensification' => ['name' => 'Sesion B: Primer Tuck Intensificacion', 'goal' => 'Volumen: 8-10 s en tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '8-10s', 4), self::ex('German Hang', '25-30s', 2), self::ex('Skin The Cat', '4-5', 2), self::ex('Dead Hang', '45s', 2)], 120)]],
            'deload' => ['name' => 'Sesion B: Primer Tuck Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '5-8s', 2), self::ex('German Hang', '12-15s', 2), self::ex('Skin The Cat', '2', 2), self::ex('Dead Hang', '20-30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Tuck Consolidado Base', 'goal' => 'Tuck back lever solido y primeras back lever rows', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '8-10s', 3), self::ex('Back Lever Row', '5-6', 2), self::ex('German Hang', '20-25s', 2), self::ex('Skin The Cat', '3', 2)], 150)]],
            'progression' => ['name' => 'Sesion B: Tuck Consolidado Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '10-12s', 3), self::ex('Back Lever Row', '6-8', 3), self::ex('German Hang', '25-30s', 2), self::ex('Skin The Cat', '3-4', 2)], 150)]],
            'intensification' => ['name' => 'Sesion B: Tuck Consolidado Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '10-12s', 4), self::ex('Back Lever Row', '8', 3), self::ex('German Hang', '30s', 2), self::ex('Skin The Cat', '4', 2)], 150)]],
            'deload' => ['name' => 'Sesion B: Tuck Consolidado Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Back Lever', '6-8s', 2), self::ex('Back Lever Row', '4-5', 2), self::ex('German Hang', '15-20s', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Advanced Tuck Base', 'goal' => 'Entrar al advanced tuck back lever manteniendo el tuck solido', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Back Lever', '4-6s', 3), self::ex('Tuck Back Lever', '8-10s', 2), self::ex('Back Lever Row', '6-8', 2), self::ex('German Hang', '25-30s', 2)], 150)]],
            'progression' => ['name' => 'Sesion B: Advanced Tuck Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Back Lever', '5-8s', 3), self::ex('Tuck Back Lever', '10-12s', 2), self::ex('Back Lever Row', '8', 3), self::ex('German Hang', '30s', 2)], 150)]],
            'intensification' => ['name' => 'Sesion B: Advanced Tuck Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Back Lever', '6-8s', 4), self::ex('Tuck Back Lever', '10-12s', 2), self::ex('Back Lever Row', '8-10', 3), self::ex('German Hang', '30-35s', 2)], 180)]],
            'deload' => ['name' => 'Sesion B: Advanced Tuck Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Advanced Tuck Back Lever', '4-5s', 2), self::ex('Tuck Back Lever', '6-8s', 2), self::ex('Back Lever Row', '5-6', 2), self::ex('German Hang', '15-20s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Straddle Entrada Base', 'goal' => 'Primer straddle back Lever y German hang profundo', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '4-6s', 4), self::ex('Advanced Tuck Back Lever', '5-8s', 2), self::ex('Back Lever Row', '6-8', 2), self::ex('German Hang', '30-35s', 2)], 150)]],
            'progression' => ['name' => 'Sesion B: Straddle Entrada Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '4-6s', 4), self::ex('Advanced Tuck Back Lever', '6-8s', 2), self::ex('Back Lever Row', '8-10', 3), self::ex('German Hang', '35-40s', 2)], 150)]],
            'intensification' => ['name' => 'Sesion B: Straddle Entrada Intensificacion', 'goal' => 'Volumen: straddle 6-8 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '6-8s', 4), self::ex('Advanced Tuck Back Lever', '6-8s', 2), self::ex('Back Lever Row', '8-10', 3), self::ex('German Hang', '40-45s', 2)], 180)]],
            'deload' => ['name' => 'Sesion B: Straddle Entrada Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '4s', 2), self::ex('Advanced Tuck Back Lever', '5s', 2), self::ex('Back Lever Row', '5-6', 2), self::ex('German Hang', '20-25s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Straddle Solido Base', 'goal' => 'Straddle estable, back lever rows fuertes y primer contacto con full (dosis N10-D del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '6-8s', 4), self::ex('Back Lever Row', '8-10', 3), self::ex('Back Lever Hold', '4-6s', 2), self::ex('German Hang', '40-45s', 2)], 180)]],
            'progression' => ['name' => 'Sesion B: Straddle Solido Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '6-8s', 4), self::ex('Back Lever Row', '10-12', 3), self::ex('Back Lever Hold', '5-8s', 2), self::ex('German Hang', '45-50s', 2)], 180)]],
            'intensification' => ['name' => 'Sesion B: Straddle Solido Intensificacion', 'goal' => 'Volumen: straddle 8-10 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '8-10s', 4), self::ex('Back Lever Row', '10-12', 4), self::ex('Back Lever Hold', '5-8s', 3), self::ex('German Hang', '50-60s', 2)], 240)]],
            'deload' => ['name' => 'Sesion B: Straddle Solido Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Straddle Back Lever', '4-5s', 2), self::ex('Back Lever Row', '6-8', 2), self::ex('Back Lever Hold', '4s', 2), self::ex('German Hang', '25-30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Full Back Lever Base', 'goal' => 'Full back lever con asistencia de banda y straddle de mantenimiento (dosis N11-D del Master)', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '3-5s', 4), self::ex('Straddle Back Lever', '6-8s', 2), self::ex('Back Lever Row', '8-10', 3), self::ex('German Hang', '45-50s', 2)], 180)]],
            'progression' => ['name' => 'Sesion B: Full Back Lever Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '4-6s', 4), self::ex('Straddle Back Lever', '6-8s', 2), self::ex('Back Lever Row', '10-12', 3), self::ex('German Hang', '50-60s', 2)], 180)]],
            'intensification' => ['name' => 'Sesion B: Full Back Lever Intensificacion', 'goal' => 'Volumen: full asistido 6-8 s tras los libres', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '3-5s', 4), self::ex('Back Lever Hold', '6-8s', 2), self::ex('Straddle Back Lever', '8-10s', 2), self::ex('Back Lever Row', '10-12', 4), self::ex('German Hang', '55-65s', 2)], 240)]],
            'deload' => ['name' => 'Sesion B: Full Back Lever Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '4s', 2), self::ex('Straddle Back Lever', '4-5s', 2), self::ex('Back Lever Row', '6-8', 2), self::ex('German Hang', '25-30s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion B: Dominio Base', 'goal' => 'Full back lever solido y back lever rows pesadas', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '6-8s', 4), self::ex('Back Lever Row', '10-12', 4), self::ex('Straddle Back Lever', '6-8s', 2), self::ex('German Hang', '55-65s', 2)], 180)]],
            'progression' => ['name' => 'Sesion B: Dominio Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '8-10s', 4), self::ex('Back Lever Row', '12-15', 4), self::ex('Straddle Back Lever', '6-8s', 2), self::ex('German Hang', '60-70s', 2)], 180)]],
            'intensification' => ['name' => 'Sesion B: Dominio Intensificacion', 'goal' => 'Pico: full 8-10 s y rows 12-15', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '8-10s', 5), self::ex('Back Lever Row', '12-15', 5), self::ex('Straddle Back Lever', '8-10s', 2), self::ex('German Hang', '60-75s', 2)], 240)]],
            'deload' => ['name' => 'Sesion B: Dominio Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Back Lever Hold', '4-5s', 2), self::ex('Back Lever Row', '6-8', 2), self::ex('Straddle Back Lever', '4-5s', 2), self::ex('German Hang', '30-40s', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — PREHAB (hombro: German hang, dislocate, face pull, skin the cat)
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
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Movilidad de hombro y control escapular antes de cargar la extension', 'blocks' => [self::block(1, 1, 45, [self::ex('Shoulder Dislocate', '8', 2), self::ex('Face Pull', '12', 2), self::ex('Skin The Cat', '2', 2), self::ex('German Hang', '10-15s', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Aumentar rango y tiempo', 'blocks' => [self::block(1, 1, 45, [self::ex('Shoulder Dislocate', '10', 2), self::ex('Face Pull', '12-15', 2), self::ex('Skin The Cat', '2-3', 2), self::ex('German Hang', '15-20s', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen de prehab', 'blocks' => [self::block(1, 1, 45, [self::ex('Shoulder Dislocate', '10', 2), self::ex('Face Pull', '15', 2), self::ex('Skin The Cat', '3', 2), self::ex('German Hang', '20-25s', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Shoulder Dislocate', '8', 2), self::ex('Face Pull', '10', 2), self::ex('Skin The Cat', '2', 2), self::ex('German Hang', '10-12s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'German hang progresivo y salud de hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 3), self::ex('Shoulder Dislocate', '10', 2), self::ex('Face Pull', '12-15', 2), self::ex('Skin The Cat', '2-3', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Shoulder Dislocate', '10', 2), self::ex('Face Pull', '15', 2), self::ex('Skin The Cat', '3', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '15-18', 2), self::ex('Skin The Cat', '3-4', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '12-15s', 2), self::ex('Shoulder Dislocate', '8', 2), self::ex('Face Pull', '10', 2), self::ex('Skin The Cat', '2', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'German hang como seguro de vida del codo y hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 3), self::ex('Shoulder Dislocate', '10', 2), self::ex('Face Pull', '15', 2), self::ex('Skin The Cat', '3', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '15-18', 2), self::ex('Skin The Cat', '3-4', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '18', 2), self::ex('Skin The Cat', '4', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Shoulder Dislocate', '8', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Profundidad de German hang y rotacion externa', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 3), self::ex('Shoulder Dislocate', '10-12', 2), self::ex('Face Pull', '15-18', 2), self::ex('Skin The Cat', '3', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '18', 2), self::ex('Skin The Cat', '3-4', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '18-20', 2), self::ex('Skin The Cat', '4', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '15-20s', 2), self::ex('Shoulder Dislocate', '8-10', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'German hang de 30-35 s y rotacion externa para la straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-35s', 3), self::ex('Shoulder Dislocate', '10-12', 2), self::ex('Face Pull', '15-18', 2), self::ex('Skin The Cat', '3-4', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '35-40s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '18-20', 2), self::ex('Skin The Cat', '4', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40-45s', 3), self::ex('Shoulder Dislocate', '12-15', 2), self::ex('Face Pull', '20', 2), self::ex('Skin The Cat', '4-5', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '20-25s', 2), self::ex('Shoulder Dislocate', '8-10', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2-3', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'German hang profundo y salud de hombro para el full', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '40-45s', 3), self::ex('Shoulder Dislocate', '12', 2), self::ex('Face Pull', '18-20', 2), self::ex('Skin The Cat', '4', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '45-50s', 3), self::ex('Shoulder Dislocate', '12-15', 2), self::ex('Face Pull', '20', 2), self::ex('Skin The Cat', '4-5', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '50-60s', 3), self::ex('Shoulder Dislocate', '15', 2), self::ex('Face Pull', '20-22', 2), self::ex('Skin The Cat', '5', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 2), self::ex('Shoulder Dislocate', '8-10', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2-3', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'German hang de 45-50 s y control escapular para el full back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '45-50s', 3), self::ex('Shoulder Dislocate', '12-15', 2), self::ex('Face Pull', '18-20', 2), self::ex('Skin The Cat', '4-5', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '50-60s', 3), self::ex('Shoulder Dislocate', '15', 2), self::ex('Face Pull', '20-22', 2), self::ex('Skin The Cat', '5', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '55-65s', 3), self::ex('Shoulder Dislocate', '15', 2), self::ex('Face Pull', '22', 2), self::ex('Skin The Cat', '5-6', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '25-30s', 2), self::ex('Shoulder Dislocate', '8-10', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2-3', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion C: Prehab Base', 'goal' => 'Resistencia profunda de extension de hombro para el dominio', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '55-65s', 3), self::ex('Shoulder Dislocate', '12-15', 2), self::ex('Face Pull', '20', 2), self::ex('Skin The Cat', '5', 2)], 60)]],
            'progression' => ['name' => 'Sesion C: Prehab Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '60-70s', 3), self::ex('Shoulder Dislocate', '15', 2), self::ex('Face Pull', '20-22', 2), self::ex('Skin The Cat', '5-6', 2)], 60)]],
            'intensification' => ['name' => 'Sesion C: Prehab Intensificacion', 'goal' => 'Volumen: German hang 60-75 s', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '60-75s', 3), self::ex('Shoulder Dislocate', '15-18', 2), self::ex('Face Pull', '22-25', 2), self::ex('Skin The Cat', '6', 2)], 60)]],
            'deload' => ['name' => 'Sesion C: Prehab Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('German Hang', '30-40s', 2), self::ex('Shoulder Dislocate', '8-10', 2), self::ex('Face Pull', '10-12', 2), self::ex('Skin The Cat', '2-3', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — CORE (hollow, arch, leg raise, tuck L-sit)
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
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar basico para mantener la linea en back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Arch Hold', '15-20s', 2), self::ex('Hanging Leg Raise', '5-6', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Arch Hold', '20-25s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 3), self::ex('Arch Hold', '25-30s', 3), self::ex('Hanging Leg Raise', '8-10', 3), self::ex('Tuck L-Sit', '15s', 3)], 90)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Arch Hold', '15-20s', 2), self::ex('Hanging Leg Raise', '5-6', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar estable para el primer tuck', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Arch Hold', '20-25s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Arch Hold', '25-30s', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '15s', 2)], 90)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Arch Hold', '30s', 3), self::ex('Hanging Leg Raise', '8-10', 3), self::ex('Tuck L-Sit', '15-20s', 3)], 90)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '15-20s', 2), self::ex('Arch Hold', '15-20s', 2), self::ex('Hanging Leg Raise', '5-6', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar solido para mantener la linea en tuck back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 3), self::ex('Arch Hold', '25-30s', 3), self::ex('Hanging Leg Raise', '8-10', 3), self::ex('Tuck L-Sit', '15s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Arch Hold', '30s', 3), self::ex('Hanging Leg Raise', '10-12', 3), self::ex('Tuck L-Sit', '15-20s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 3), self::ex('Arch Hold', '30-35s', 3), self::ex('Hanging Leg Raise', '10-12', 3), self::ex('Tuck L-Sit', '20s', 3)], 90)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Arch Hold', '20-25s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar para advanced tuck y primera linea plana', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30s', 3), self::ex('Arch Hold', '30s', 3), self::ex('Hanging Leg Raise', '10-12', 3), self::ex('Tuck L-Sit', '15-20s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 3), self::ex('Arch Hold', '30-35s', 3), self::ex('Hanging Leg Raise', '10-12', 3), self::ex('Tuck L-Sit', '20s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '35-45s', 3), self::ex('Arch Hold', '35-45s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '20-25s', 3)], 90)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '20-25s', 2), self::ex('Arch Hold', '20-25s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar para sostener la straddle back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-35s', 3), self::ex('Arch Hold', '30-35s', 3), self::ex('Hanging Leg Raise', '10-12', 3), self::ex('Tuck L-Sit', '20s', 3)], 90)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '35-45s', 3), self::ex('Arch Hold', '35-45s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '20-25s', 3)], 90)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '45s', 3), self::ex('Arch Hold', '45s', 3), self::ex('Hanging Leg Raise', '12-15', 4), self::ex('Tuck L-Sit', '25s', 3)], 90)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Arch Hold', '25-30s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar fuerte para straddle solido y transicion al full', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '35-45s', 3), self::ex('Arch Hold', '35-45s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '20-25s', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '45s', 3), self::ex('Arch Hold', '45s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '25s', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '45-60s', 4), self::ex('Arch Hold', '45-60s', 4), self::ex('Hanging Leg Raise', '15', 4), self::ex('Tuck L-Sit', '25-30s', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '25-30s', 2), self::ex('Arch Hold', '25-30s', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar elite para el full back lever', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '45-60s', 3), self::ex('Arch Hold', '45-60s', 3), self::ex('Hanging Leg Raise', '12-15', 3), self::ex('Tuck L-Sit', '25-30s', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Arch Hold', '60s', 3), self::ex('Hanging Leg Raise', '15', 3), self::ex('Tuck L-Sit', '30s', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60s', 4), self::ex('Arch Hold', '60s', 4), self::ex('Hanging Leg Raise', '15-18', 4), self::ex('Tuck L-Sit', '30s', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30s', 2), self::ex('Arch Hold', '30s', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesion D: Core Base', 'goal' => 'Pilar de dominio para sostener el back lever con peso', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Arch Hold', '60s', 3), self::ex('Hanging Leg Raise', '15', 3), self::ex('Tuck L-Sit', '30s', 3)], 120)]],
            'progression' => ['name' => 'Sesion D: Core Progresion', 'goal' => 'Progresion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60s', 3), self::ex('Arch Hold', '60s', 3), self::ex('Hanging Leg Raise', '15-18', 3), self::ex('Tuck L-Sit', '30-45s', 3)], 120)]],
            'intensification' => ['name' => 'Sesion D: Core Intensificacion', 'goal' => 'Pico de pilar', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '60s', 4), self::ex('Arch Hold', '60s', 4), self::ex('Hanging Leg Raise', '18', 4), self::ex('Tuck L-Sit', '45s', 4)], 120)]],
            'deload' => ['name' => 'Sesion D: Core Deload', 'goal' => 'Recuperacion', 'blocks' => [self::block(1, 1, 45, [self::ex('Hollow Body Hold', '30-45s', 2), self::ex('Arch Hold', '30-45s', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
