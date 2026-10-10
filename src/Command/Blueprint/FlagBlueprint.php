<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

/**
 * Blueprint del programa Flag Mastery (8 niveles).
 * Especialización del Master v4: progresión específica hacia la bandera humana
 * desde la tracción y el core lateral hasta Human Flag de 3-5 s.
 * Estructura: 4 sesiones por fase (A=Strength, B=Bandera, C=Core lateral, D=Skill).
 * Fases: base, progression, intensification, deload.
 *
 * Reglas tendinosas del Master (§1.7) aplicadas a hombro, muñeca y oblicuos:
 * - Los holds de bandera van con descanso completo, nunca en circuito.
 * - Si el tiempo aprieta se recorta volumen (series), nunca descanso.
 * - Nada de bandera en frío: 2-3 series de movilidad/activación antes de cada intento.
 * - Molestia matinal >2/10 en hombro, muñeca u oblicuos = congela ese gesto un ciclo.
 */
class FlagBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => ['name' => 'Nivel 1: Base de Tracción y Core Lateral', 'title' => 'Base de Tracción y Core Lateral', 'description' => 'Fortalecer la tracción vertical y el core lateral como base de la bandera humana', 'objective' => 'Dominar dominadas estrictas, colgados y side plank antes de cargar la posición de bandera', 'difficultyRating' => 1, 'color' => '#4ade80', 'requirementsSummary' => 'Pull Up(8), Dead Hang(45s)', 'skillFocus' => 'Standard Pull Up, Side Plank, Dead Hang'],
            2 => ['name' => 'Nivel 2: Primer Vertical', 'title' => 'Primer Vertical', 'description' => 'Primer contacto con la bandera vertical asistida y dragon flag intro', 'objective' => 'Sostener Vertical Flag asistida 5-10 s y controlar negativas de dragon flag', 'difficultyRating' => 2, 'color' => '#4ade80', 'requirementsSummary' => 'Vertical Flag 5s, Dragon Flag Negative 3, Pull Up 10', 'skillFocus' => 'Vertical Flag, Dragon Flag Negative, Human Flag Raise'],
            3 => ['name' => 'Nivel 3: Bandera Vertical', 'title' => 'Bandera Vertical', 'description' => 'Consolidar la bandera vertical y entrar en Tuck Flag asistido', 'objective' => 'Sostener Vertical Flag 10-15 s y Tuck Flag 3-5 s con asistencia', 'difficultyRating' => 3, 'color' => '#60a5fa', 'requirementsSummary' => 'Vertical Flag 10s, Human Flag Raise 3, Tuck Flag 3s', 'skillFocus' => 'Vertical Flag, Tuck Flag, Human Flag Raise'],
            4 => ['name' => 'Nivel 4: Tuck Flag', 'title' => 'Tuck Flag', 'description' => 'Bandera en tuck sólida y vertical estable como escalón intermedio', 'objective' => 'Sostener Tuck Flag 3-5 s y Vertical Flag 15-20 s', 'difficultyRating' => 4, 'color' => '#60a5fa', 'requirementsSummary' => 'Tuck Flag 3s, Vertical Flag 15s, Human Flag Raise 5', 'skillFocus' => 'Tuck Flag, Vertical Flag, Human Flag Raise'],
            5 => ['name' => 'Nivel 5: Bandera 45°', 'title' => 'Bandera 45°', 'description' => 'Primeras posiciones intermedias de bandera humana a 45 grados', 'objective' => 'Sostener Human Flag 45 Degree 3-5 s y Tuck Flag 5-8 s', 'difficultyRating' => 5, 'color' => '#f472b6', 'requirementsSummary' => 'Human Flag 45 Degree 3s, Tuck Flag 5s, Dragon Flag 5', 'skillFocus' => 'Human Flag 45 Degree, Tuck Flag, Dragon Flag'],
            6 => ['name' => 'Nivel 6: 45° Sólida', 'title' => '45° Sólida', 'description' => 'Consolidar la bandera a 45 grados y preparar straddle asistida', 'objective' => 'Sostener Human Flag 45 Degree 5-10 s y Tuck Flag 8-10 s', 'difficultyRating' => 6, 'color' => '#f472b6', 'requirementsSummary' => 'Human Flag 45 Degree 8s, Tuck Flag 8s, Human Flag Raise 8', 'skillFocus' => 'Human Flag 45 Degree, Tuck Flag, Human Flag Raise'],
            7 => ['name' => 'Nivel 7: Straddle Flag', 'title' => 'Straddle Flag', 'description' => 'Peld intermedio hacia la bandera horizontal: straddle flag asistida', 'objective' => 'Sostener straddle flag asistida 3-5 s y Human Flag 45 Degree 10 s', 'difficultyRating' => 7, 'color' => '#a78bfa', 'requirementsSummary' => 'Human Flag (straddle) 3s, Human Flag 45 Degree 10s, Side Plank Leg Lift 8', 'skillFocus' => 'Human Flag straddle, Human Flag 45 Degree, Side Plank Leg Lift'],
            8 => ['name' => 'Nivel 8: Bandera Horizontal', 'title' => 'Bandera Horizontal', 'description' => 'Bandera humana horizontal: objetivo final de 3-5 s', 'objective' => 'Sostener Human Flag 3-5 s y completar el programa Flag Mastery', 'difficultyRating' => 8, 'color' => '#a78bfa', 'requirementsSummary' => 'Human Flag 3s, Human Flag 45 Degree 15s, Human Flag Raise 10', 'skillFocus' => 'Human Flag, Human Flag 45 Degree, Human Flag Raise'],
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
    // SESSION A — STRENGTH (Tracción vertical)
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
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Base de tracción vertical y remo para soportar la carga de la bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 3), self::ex('Chin Up', '5-6', 3), self::ex('Ring Row - Elevated Feet', '8-10', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Aumentar reps de tracción', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 3), self::ex('Chin Up', '6-8', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen máximo de base', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 4), self::ex('Chin Up', '8-10', 4), self::ex('Ring Row - Elevated Feet', '12', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5', 2), self::ex('Chin Up', '5', 2), self::ex('Ring Row - Elevated Feet', '8', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción asimétrica con archer y base de pull up', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 3), self::ex('Archer Pull Up', '2/lado', 3), self::ex('Ring Row - Elevated Feet', '10-12', 3)], 90)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Archer Pull Up', '2-3/lado', 3), self::ex('Ring Row - Elevated Feet', '12', 3)], 90)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 4), self::ex('Archer Pull Up', '3/lado', 4), self::ex('Ring Row - Elevated Feet', '12-15', 4)], 90)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '5-6', 2), self::ex('Archer Pull Up', '2/lado', 2), self::ex('Ring Row - Elevated Feet', '8-10', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Entra la tracción lastrada para acelerar fuerza de agarre', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '8-10', 3), self::ex('Chin Up', '8-10', 3), self::ex('Weighted Pull Up', '5', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '10-12', 3), self::ex('Chin Up', '10-12', 3), self::ex('Weighted Pull Up', '5-6', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '12-15', 4), self::ex('Chin Up', '12-15', 4), self::ex('Weighted Pull Up', '6-8', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Standard Pull Up', '6-8', 2), self::ex('Chin Up', '6-8', 2), self::ex('Weighted Pull Up', '3-4', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción estricta y remo pesado horizontal', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 3), self::ex('Archer Pull Up', '2-3/lado', 3), self::ex('Weighted Ring Row', '8-10', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Archer Pull Up', '3/lado', 3), self::ex('Weighted Ring Row', '10-12', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 4), self::ex('Archer Pull Up', '3-4/lado', 4), self::ex('Weighted Ring Row', '12', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '5-6', 2), self::ex('Archer Pull Up', '2/lado', 2), self::ex('Weighted Ring Row', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción alta con peso y asimetría para la bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 3), self::ex('Weighted Pull Up', '5-6', 3), self::ex('Archer Pull Up', '3/lado', 3), self::ex('Weighted Ring Row', '10-12', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Pull Up', '6-8', 3), self::ex('Archer Pull Up', '3-4/lado', 3), self::ex('Weighted Ring Row', '12', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 4), self::ex('Weighted Pull Up', '8-10', 4), self::ex('Archer Pull Up', '4/lado', 4), self::ex('Weighted Ring Row', '12-15', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '6-8', 2), self::ex('Weighted Pull Up', '4-5', 2), self::ex('Archer Pull Up', '2/lado', 2), self::ex('Weighted Ring Row', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción máxima fresca antes del trabajo de bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '10-12', 3), self::ex('Weighted Pull Up', '6-8', 3), self::ex('Archer Pull Up', '3-4/lado', 3), self::ex('Weighted Ring Row', '10-12', 3)], 120)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '12-15', 3), self::ex('Weighted Pull Up', '8-10', 3), self::ex('Archer Pull Up', '4/lado', 3), self::ex('Weighted Ring Row', '12-15', 3)], 120)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '15-18', 4), self::ex('Weighted Pull Up', '10-12', 4), self::ex('Archer Pull Up', '4-5/lado', 4), self::ex('Weighted Ring Row', '15', 4)], 120)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Strict Pull Up', '8-10', 2), self::ex('Weighted Pull Up', '5-6', 2), self::ex('Archer Pull Up', '2-3/lado', 2), self::ex('Weighted Ring Row', '8-10', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción lastrada como base del gesto final de bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '5-6', 3), self::ex('Strict Pull Up', '8-10', 3), self::ex('Archer Pull Up', '3-4/lado', 3), self::ex('Weighted Ring Row', '10-12', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '6-8', 3), self::ex('Strict Pull Up', '10-12', 3), self::ex('Archer Pull Up', '4/lado', 3), self::ex('Weighted Ring Row', '12-15', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '8-10', 4), self::ex('Strict Pull Up', '12-15', 4), self::ex('Archer Pull Up', '4-5/lado', 4), self::ex('Weighted Ring Row', '15', 4)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '4-5', 2), self::ex('Strict Pull Up', '6-8', 2), self::ex('Archer Pull Up', '2-3/lado', 2), self::ex('Weighted Ring Row', '8-10', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionA_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión A: Strength Base', 'goal' => 'Tracción elite para sostener la bandera horizontal', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '6-8', 3), self::ex('Strict Pull Up', '10-12', 3), self::ex('Archer Pull Up', '4/lado', 3), self::ex('Weighted Ring Row', '12-15', 3)], 150)]],
            'progression' => ['name' => 'Sesión A: Strength Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '8-10', 3), self::ex('Strict Pull Up', '12-15', 3), self::ex('Archer Pull Up', '4-5/lado', 3), self::ex('Weighted Ring Row', '15', 3)], 150)]],
            'intensification' => ['name' => 'Sesión A: Strength Intensificación', 'goal' => 'Intensificación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '10-12', 4), self::ex('Strict Pull Up', '15-18', 4), self::ex('Archer Pull Up', '5/lado', 4), self::ex('Weighted Ring Row', '15-18', 4)], 150)]],
            'deload' => ['name' => 'Sesión A: Strength Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Weighted Pull Up', '5-6', 2), self::ex('Strict Pull Up', '8-10', 2), self::ex('Archer Pull Up', '3/lado', 2), self::ex('Weighted Ring Row', '10-12', 2)], 150)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION B — BANDERA (progresión específica del nivel)
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
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Primer Vertical Flag asistido y colgados para construir agarre de bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '5-8s', 3), self::ex('Dead Hang', '20s', 2), self::ex('Scapular Pull Up', '8', 2)], 120)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Aumentar tiempo en vertical asistida', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '8-10s', 3), self::ex('Dead Hang', '25s', 2), self::ex('Scapular Pull Up', '8', 2)], 120)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen de vertical y colgado', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '10-12s', 3), self::ex('Dead Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 2)], 120)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '5s', 2), self::ex('Dead Hang', '20s', 2), self::ex('Scapular Pull Up', '6', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Vertical Flag asistida y dragon flag intro para el core', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '8-10s', 3), self::ex('Dragon Flag Negative', '3', 3), self::ex('Human Flag Raise', '3', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '10-12s', 3), self::ex('Dragon Flag Negative', '3-4', 3), self::ex('Human Flag Raise', '3-5', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen: marca de 12-15 s en vertical', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '12-15s', 3), self::ex('Dragon Flag Negative', '4-5', 3), self::ex('Human Flag Raise', '5', 3)], 150)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '8s', 2), self::ex('Dragon Flag Negative', '2', 2), self::ex('Human Flag Raise', '3', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Bandera vertical sólida y primer Tuck Flag asistido', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '10-12s', 3), self::ex('Tuck Flag', '3-5s', 3), self::ex('Human Flag Raise', '3-5', 3)], 150)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '12-15s', 3), self::ex('Tuck Flag', '3-5s', 3), self::ex('Human Flag Raise', '5', 3)], 150)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen: vertical 15 s y tuck 5 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '15s', 3), self::ex('Tuck Flag', '5s', 3), self::ex('Human Flag Raise', '5-8', 3)], 150)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Vertical Flag', '10s', 2), self::ex('Tuck Flag', '3s', 2), self::ex('Human Flag Raise', '3', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Tuck Flag como objetivo y vertical como mantenimiento', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Flag', '3-5s', 3), self::ex('Vertical Flag', '12-15s', 3), self::ex('Human Flag Raise', '5-8', 3)], 180)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Flag', '3-5s', 3), self::ex('Vertical Flag', '15-18s', 3), self::ex('Human Flag Raise', '5-8', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen: tuck 5 s y vertical 20 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Flag', '5s', 3), self::ex('Vertical Flag', '18-20s', 3), self::ex('Human Flag Raise', '8', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Tuck Flag', '3s', 2), self::ex('Vertical Flag', '12s', 2), self::ex('Human Flag Raise', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Primer Human Flag 45 Degree y consolidar Tuck Flag', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '3-5s', 3), self::ex('Tuck Flag', '5-8s', 3), self::ex('Human Flag Raise', '5-8', 3)], 180)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '5-8s', 3), self::ex('Tuck Flag', '5-8s', 3), self::ex('Human Flag Raise', '8', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen: 45° 8 s y tuck 8 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '8s', 3), self::ex('Tuck Flag', '8s', 3), self::ex('Human Flag Raise', '8-10', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '3-5s', 2), self::ex('Tuck Flag', '5s', 2), self::ex('Human Flag Raise', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Human Flag 45 Degree sólido y transición a straddle asistida', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '5-8s', 3), self::ex('Tuck Flag', '5-8s', 3), self::ex('Human Flag Raise', '8-10', 3)], 180)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '8-10s', 3), self::ex('Tuck Flag', '8-10s', 3), self::ex('Human Flag Raise', '8-10', 3)], 180)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen: 45° 10 s y tuck 10 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '10s', 3), self::ex('Tuck Flag', '10s', 3), self::ex('Human Flag Raise', '10', 3)], 180)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag 45 Degree', '5-8s', 2), self::ex('Tuck Flag', '5-8s', 2), self::ex('Human Flag Raise', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Straddle flag asistida como peldaño hacia la bandera horizontal', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '3-5s', 3), self::ex('Human Flag 45 Degree', '8-10s', 3), self::ex('Human Flag Raise', '8-10', 3)], 240)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '3-5s', 3), self::ex('Human Flag 45 Degree', '10-12s', 3), self::ex('Human Flag Raise', '8-10', 3)], 240)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Volumen: straddle 5 s y 45° 12-15 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '5s', 3), self::ex('Human Flag 45 Degree', '12-15s', 3), self::ex('Human Flag Raise', '10', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '3s', 2), self::ex('Human Flag 45 Degree', '8s', 2), self::ex('Human Flag Raise', '5', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionB_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión B: Bandera Base', 'goal' => 'Human Flag horizontal y straddle asistida como soporte', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '3-5s', 3), self::ex('Human Flag', '5-8s', 3), self::ex('Human Flag 45 Degree', '12-15s', 3), self::ex('Human Flag Raise', '10-12', 3)], 240)]],
            'progression' => ['name' => 'Sesión B: Bandera Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '3-5s', 3), self::ex('Human Flag', '5-8s', 3), self::ex('Human Flag 45 Degree', '15-18s', 3), self::ex('Human Flag Raise', '10-15', 3)], 240)]],
            'intensification' => ['name' => 'Sesión B: Bandera Intensificación', 'goal' => 'Pico: bandera 5 s, straddle 8 s, 45° 15-20 s', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '5s', 3), self::ex('Human Flag', '8s', 3), self::ex('Human Flag 45 Degree', '15-20s', 3), self::ex('Human Flag Raise', '12-15', 3)], 240)]],
            'deload' => ['name' => 'Sesión B: Bandera Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Human Flag', '3s', 2), self::ex('Human Flag', '5s', 2), self::ex('Human Flag 45 Degree', '10s', 2), self::ex('Human Flag Raise', '8', 2)], 120)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION C — CORE LATERAL (side plank, dragon flag, copenhagen)
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
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Activar oblicuos y cadera para la posición de bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '20s', 2), self::ex('Side Plank Leg Lift', '6', 2), self::ex('Lateral Plank Raise', '6', 2), self::ex('Copenhagen Plank', '15s', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Aumentar tiempo y reps', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '25-30s', 2), self::ex('Side Plank Leg Lift', '8', 2), self::ex('Lateral Plank Raise', '8', 2), self::ex('Copenhagen Plank', '20s', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen de core lateral', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '30-45s', 2), self::ex('Side Plank Leg Lift', '10', 2), self::ex('Lateral Plank Raise', '10', 2), self::ex('Copenhagen Plank', '25s', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '20s', 2), self::ex('Side Plank Leg Lift', '6', 2), self::ex('Lateral Plank Raise', '6', 2), self::ex('Copenhagen Plank', '15s', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Core lateral con dragon flag intro para control global', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '30s', 2), self::ex('Side Plank Leg Lift', '8', 2), self::ex('Lateral Plank Raise', '8', 2), self::ex('Dragon Flag Negative', '3', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '35-40s', 2), self::ex('Side Plank Leg Lift', '10', 2), self::ex('Lateral Plank Raise', '10', 2), self::ex('Dragon Flag Negative', '3-4', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '40-50s', 2), self::ex('Side Plank Leg Lift', '12', 2), self::ex('Lateral Plank Raise', '12', 2), self::ex('Dragon Flag Negative', '4-5', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '25s', 2), self::ex('Side Plank Leg Lift', '6', 2), self::ex('Lateral Plank Raise', '6', 2), self::ex('Dragon Flag Negative', '2', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Core lateral estable con dragon flag negativa', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '35-40s', 2), self::ex('Side Plank Leg Lift', '10', 2), self::ex('Lateral Plank Raise', '10', 2), self::ex('Dragon Flag Negative', '3-4', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '40-50s', 2), self::ex('Side Plank Leg Lift', '12', 2), self::ex('Lateral Plank Raise', '12', 2), self::ex('Dragon Flag Negative', '4-5', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '50-60s', 2), self::ex('Side Plank Leg Lift', '12-15', 2), self::ex('Lateral Plank Raise', '12-15', 2), self::ex('Dragon Flag Negative', '5', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '30s', 2), self::ex('Side Plank Leg Lift', '8', 2), self::ex('Lateral Plank Raise', '8', 2), self::ex('Dragon Flag Negative', '2-3', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Dragon flag completo y side plank prolongado', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '45-50s', 2), self::ex('Side Plank Leg Lift', '12', 2), self::ex('Lateral Plank Raise', '12', 2), self::ex('Dragon Flag', '3-4', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '50-60s', 2), self::ex('Side Plank Leg Lift', '12-15', 2), self::ex('Lateral Plank Raise', '12-15', 2), self::ex('Dragon Flag', '4-5', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60s', 2), self::ex('Side Plank Leg Lift', '15', 2), self::ex('Lateral Plank Raise', '15', 2), self::ex('Dragon Flag', '5-6', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '30-40s', 2), self::ex('Side Plank Leg Lift', '8', 2), self::ex('Lateral Plank Raise', '8', 2), self::ex('Dragon Flag', '3', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Core lateral de alto volumen para sostener 45°', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '50-60s', 2), self::ex('Side Plank Leg Lift', '12-15', 2), self::ex('Lateral Plank Raise', '12-15', 2), self::ex('Dragon Flag', '4-5', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60s', 2), self::ex('Side Plank Leg Lift', '15', 2), self::ex('Lateral Plank Raise', '15', 2), self::ex('Dragon Flag', '5-6', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60-75s', 2), self::ex('Side Plank Leg Lift', '15-18', 2), self::ex('Lateral Plank Raise', '15-18', 2), self::ex('Dragon Flag', '6-8', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '40-50s', 2), self::ex('Side Plank Leg Lift', '8-10', 2), self::ex('Lateral Plank Raise', '8-10', 2), self::ex('Dragon Flag', '3-4', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Core lateral máximo para la transición a straddle', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60s', 2), self::ex('Side Plank Leg Lift', '15', 2), self::ex('Lateral Plank Raise', '15', 2), self::ex('Dragon Flag', '5-6', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60-75s', 2), self::ex('Side Plank Leg Lift', '15-18', 2), self::ex('Lateral Plank Raise', '15-18', 2), self::ex('Dragon Flag', '6-8', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '75-90s', 2), self::ex('Side Plank Leg Lift', '18-20', 2), self::ex('Lateral Plank Raise', '18-20', 2), self::ex('Dragon Flag', '8', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '45-60s', 2), self::ex('Side Plank Leg Lift', '10', 2), self::ex('Lateral Plank Raise', '10', 2), self::ex('Dragon Flag', '4-5', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Core lateral elite para straddle flag asistida', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60-75s', 2), self::ex('Side Plank Leg Lift', '15-18', 2), self::ex('Lateral Plank Raise', '15-18', 2), self::ex('Dragon Flag', '6-8', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '75-90s', 2), self::ex('Side Plank Leg Lift', '18-20', 2), self::ex('Lateral Plank Raise', '18-20', 2), self::ex('Dragon Flag', '8', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '90s', 2), self::ex('Side Plank Leg Lift', '20', 2), self::ex('Lateral Plank Raise', '20', 2), self::ex('Dragon Flag', '8-10', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '45-60s', 2), self::ex('Side Plank Leg Lift', '10-12', 2), self::ex('Lateral Plank Raise', '10-12', 2), self::ex('Dragon Flag', '4-5', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionC_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión C: Core Lateral Base', 'goal' => 'Core lateral máximo para bandera horizontal completa', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '75-90s', 2), self::ex('Side Plank Leg Lift', '18-20', 2), self::ex('Lateral Plank Raise', '18-20', 2), self::ex('Dragon Flag', '8-10', 2)], 60)]],
            'progression' => ['name' => 'Sesión C: Core Lateral Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '90s', 2), self::ex('Side Plank Leg Lift', '20', 2), self::ex('Lateral Plank Raise', '20', 2), self::ex('Dragon Flag', '10', 2)], 60)]],
            'intensification' => ['name' => 'Sesión C: Core Lateral Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '90-120s', 2), self::ex('Side Plank Leg Lift', '20-25', 2), self::ex('Lateral Plank Raise', '20-25', 2), self::ex('Dragon Flag', '10-12', 2)], 60)]],
            'deload' => ['name' => 'Sesión C: Core Lateral Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Side Plank', '60s', 2), self::ex('Side Plank Leg Lift', '12-15', 2), self::ex('Lateral Plank Raise', '12-15', 2), self::ex('Dragon Flag', '5-6', 2)], 60)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    // ========================================================================
    // SESSION D — SKILL (agarre y entradas)
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
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Agarre completo, activación escapular y hollow body para la bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15s', 2), self::ex('Active Hang', '15s', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Diaphragmatic Breathing', '60s', 1)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 2), self::ex('Active Hang', '20s', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Diaphragmatic Breathing', '60s', 1)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '25-30s', 2), self::ex('Active Hang', '20-25s', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Diaphragmatic Breathing', '90s', 1)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '15s', 2), self::ex('Active Hang', '15s', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Diaphragmatic Breathing', '60s', 1)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L2(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Agarre activo, escápulas y elevaciones de piernas para el core', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 2), self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hanging Leg Raise', '6', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '25-30s', 2), self::ex('Active Hang', '25s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hanging Leg Raise', '8', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '30-45s', 2), self::ex('Active Hang', '25-30s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hanging Leg Raise', '8-10', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Dead Hang', '20s', 2), self::ex('Active Hang', '15s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '6', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L3(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Agarre activo, hollow body y tuck L-sit para la entrada de bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '25s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hollow Body Hold', '25s', 2), self::ex('Tuck L-Sit', '10s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hollow Body Hold', '30s', 2), self::ex('Tuck L-Sit', '10-15s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hollow Body Hold', '30-40s', 2), self::ex('Tuck L-Sit', '15s', 2)], 90)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '20s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hollow Body Hold', '20s', 2), self::ex('Tuck L-Sit', '8s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L4(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Agarre activo, German hang y elevaciones para salud de hombro y codo', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hanging Leg Raise', '8', 2), self::ex('German Hang', '15-20s', 2)], 90)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('German Hang', '20-25s', 2)], 90)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '45-60s', 2), self::ex('Scapular Pull Up', '12', 2), self::ex('Hanging Leg Raise', '10', 2), self::ex('German Hang', '25-30s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '20-30s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '6', 2), self::ex('German Hang', '15s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L5(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Entradas de bandera y Skin The Cat para movilidad de hombro', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '10', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '45-60s', 2), self::ex('Scapular Pull Up', '12', 2), self::ex('Hanging Leg Raise', '10', 2), self::ex('Skin The Cat', '2-3', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '60s', 2), self::ex('Scapular Pull Up', '12', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Skin The Cat', '3', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30s', 2), self::ex('Scapular Pull Up', '6', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('Skin The Cat', '2', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L6(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'German hang y Skin The Cat: salud de hombro para la bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '45-60s', 2), self::ex('Scapular Pull Up', '12', 2), self::ex('Hanging Leg Raise', '10', 2), self::ex('German Hang', '20-25s', 2), self::ex('Skin The Cat', '2', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '60s', 2), self::ex('Scapular Pull Up', '12', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('German Hang', '25-30s', 2), self::ex('Skin The Cat', '2-3', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '60-75s', 2), self::ex('Scapular Pull Up', '12-15', 2), self::ex('Hanging Leg Raise', '12', 2), self::ex('German Hang', '30s', 2), self::ex('Skin The Cat', '3', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '30-45s', 2), self::ex('Scapular Pull Up', '6-8', 2), self::ex('Hanging Leg Raise', '6-8', 2), self::ex('German Hang', '15-20s', 2), self::ex('Skin The Cat', '2', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L7(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Tuck L-Sit y German hang para control de entrada y salida de bandera', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '60s', 2), self::ex('Scapular Pull Up', '12', 2), self::ex('Hanging Leg Raise', '10-12', 2), self::ex('Tuck L-Sit', '15s', 2), self::ex('German Hang', '20-25s', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '60-75s', 2), self::ex('Scapular Pull Up', '12-15', 2), self::ex('Hanging Leg Raise', '12', 2), self::ex('Tuck L-Sit', '15-20s', 2), self::ex('German Hang', '25-30s', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '75-90s', 2), self::ex('Scapular Pull Up', '15', 2), self::ex('Hanging Leg Raise', '12-15', 2), self::ex('Tuck L-Sit', '20s', 2), self::ex('German Hang', '30s', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '45-60s', 2), self::ex('Scapular Pull Up', '8', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '10s', 2), self::ex('German Hang', '15-20s', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }

    private static function sessionD_L8(string $phase): array
    {
        return match ($phase) {
            'base' => ['name' => 'Sesión D: Skill Base', 'goal' => 'Control elite de agarre y core para la bandera horizontal', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '60-75s', 2), self::ex('Scapular Pull Up', '12-15', 2), self::ex('Hanging Leg Raise', '12', 2), self::ex('Tuck L-Sit', '20s', 2), self::ex('Skin The Cat', '3', 2)], 120)]],
            'progression' => ['name' => 'Sesión D: Skill Progresión', 'goal' => 'Progresión', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '75-90s', 2), self::ex('Scapular Pull Up', '15', 2), self::ex('Hanging Leg Raise', '12-15', 2), self::ex('Tuck L-Sit', '20-25s', 2), self::ex('Skin The Cat', '3-4', 2)], 120)]],
            'intensification' => ['name' => 'Sesión D: Skill Intensificación', 'goal' => 'Volumen', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '90s', 2), self::ex('Scapular Pull Up', '15-18', 2), self::ex('Hanging Leg Raise', '15', 2), self::ex('Tuck L-Sit', '25-30s', 2), self::ex('Skin The Cat', '4', 2)], 120)]],
            'deload' => ['name' => 'Sesión D: Skill Deload', 'goal' => 'Recuperación', 'blocks' => [self::block(1, 1, 45, [self::ex('Active Hang', '45-60s', 2), self::ex('Scapular Pull Up', '8-10', 2), self::ex('Hanging Leg Raise', '8-10', 2), self::ex('Tuck L-Sit', '10-15s', 2), self::ex('Skin The Cat', '2', 2)], 90)]],
            default => throw new \InvalidArgumentException(),
        };
    }
}
