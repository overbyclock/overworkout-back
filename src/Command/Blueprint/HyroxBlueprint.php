<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa Hyrox Mastery v1 (niveles 1-3 de 8).
 *
 * Contenido transcrito literalmente de docs/detalle_hyrox_niveles_01-03.md.
 * Estructura: 4 sesiones por fase (A=Run+Estaciones, B=Ergómetros,
 * C=Simulacro/Circuito, D=Aeróbico+Movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N7 y 90 s en N8 (transición entre bloques;
 * regla del proyecto: niveles ≤6 → 60 s, resto → 90 s).
 *
 * Los AMRAP/EMOM se modelan como rondas estimadas para llenar la ventana del
 * formato: la duración sale de TrainingTimeCalculator, nunca se hardcodea.
 * El formato exacto (p. ej. "AMRAP 8'") va en la descripción del bloque.
 * La sesión C de N3 Fase 3 (intensification) es el benchmark embebido
 * 'Hyrox Simulacro L3': el round ES el benchmark (sin configs de ejercicios).
 *
 * Omisiones: ninguna — todos los ejercicios citados existen en el catálogo
 * con la grafía usada (verificado contra SELECT name FROM exercises, 17-oct-2026;
 * doc §"Pendientes de catálogo"). Sled de "4×12,5 m" se aplana a 50 m por serie;
 * dosis "/lado" se computan una vez (convención v4).
 *
 * Dosis de distancia (runs, ergómetros, carries, sled): según spec §3
 * ("1 km ≈ nota + durationSeconds"), el campo de dosis lleva el TIEMPO estimado
 * en segundos ('90s' ≈ 400 m, '120s' ≈ 500 m, '270s' ≈ 1 km Z3...) y la
 * distancia exacta queda en la descripción del bloque. Así TrainingTimeCalculator
 * estima duraciones reales en vez de inflarlas a 3-5 s por metro.
 */
class HyroxBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base Híbrida',
                'title' => 'Base Híbrida — Motor aeróbico y técnica de estaciones sin carga',
                'description' => 'Construir el motor aeróbico (run Z2 de 15 a 30 min por tiempo), enseñar la técnica de las 8 estaciones sin carga o con carga mínima (SkiErg/Row con intervalos cortos, wall ball 4 kg a diana baja, sled sin carga si el gimnasio permite, lunges al peso corporal, burpees parciales) y tejer el core y la movilidad densa que sostendrán el volumen de N2-N3. Aquí no se corre deprisa ni se carga nada: se aprende.',
                'objective' => 'Motor aeróbico (run Z2 15-30 min), técnica de las 8 estaciones sin carga o con carga mínima, y core/movilidad densa; test de salida: HYROX PFT como marca base',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero (incl. quien nunca ha tocado un SkiErg, un sled ni ha corrido 30 minutos seguidos)',
                'skillFocus' => 'Técnica de estaciones sin carga, carrera Z2 y core anti-extensión',
            ],
            2 => [
                'name' => 'Nivel 2: Primeras Cargas',
                'title' => 'Primeras Cargas — El 50% de Open entra en escena',
                'description' => 'Introducir los pesos de estación al ~50% de Open (farmers 2×12 kg, wall ball 4 kg a diana media, sandbag/KB lunge 8-12 kg, sled con carga ligera ~60-75 kg), progresar los intervals de run de 400 a 800 m y entrenar las primeras transiciones run→estación con lógica de Roxzone. El test mezcla ítems de fuerza (wall ball y burpee) con las puertas HY1-HY3.',
                'objective' => 'Pesos 50% Open por estación, intervals de run 400-800 m y transiciones run→estación con lógica de Roxzone; puertas HY1-HY3 evaluadas',
                'difficultyRating' => 2,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Test N1 superado (PFT con marca registrada) o, para atletas de entrada directa, correr 15 min Z2 sin parar',
                'skillFocus' => 'Pesos 50% Open por estación, intervals de carrera 400-800 m y transiciones de Roxzone',
            ],
            3 => [
                'name' => 'Nivel 3: Media Hyrox',
                'title' => 'Media Hyrox — Simulacro y ritmo de Roxzone',
                'description' => 'Ejecutar el primer simulacro embebido (4 estaciones con runs 500 m, pesos 50% Open) como test del nivel, entrenar pares de estaciones con fatiga real, correr 1 km a ritmo Z3 y superar la puerta HY4 (farmers 2×16 kg ×100 m). Al superar N3, el atleta está listo para el tramo de estaciones al 100% Open (N4-N8).',
                'objective' => 'Simulacro de media Hyrox (Hyrox Simulacro L3) como test, pares de estaciones con fatiga, carrera de 1 km a Z3 y puerta HY4',
                'difficultyRating' => 3,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (incl. puertas HY1-HY3 superadas)',
                'skillFocus' => 'Simulacro de media Hyrox, pares de estaciones con fatiga y carrera de 1 km a Z3',
            ],
            4 => [
                'name' => 'Nivel 4: Estaciones Open',
                'title' => 'Estaciones Open — Cada estación al 100%, una a una',
                'description' => 'Llevar cada estación a los pesos oficiales Open por separado (sled push 152/102 kg, sled pull 103/78 kg, farmers 2×24/2×16 kg, wall ball 6/4 kg, sandbag lunge → Kettlebell Goblet Lunge 20/12 kg), con umbrales ergómetros largos (SkiErg/Row 1.500-2.000 m) y run de 1 km a Z3-Z4 en intervals. La lógica del nivel es "una estación a la vez": fuerza pura con series largas y descanso completo; los pares con fatiga llegan en N5.',
                'objective' => 'Estaciones al 100% de los pesos Open por separado y umbrales ergómetros de 1.500-2.000 m; re-test HY4',
                'difficultyRating' => 4,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (Hyrox Simulacro L3 bajo 35:00 + puerta HY4 superada)',
                'skillFocus' => 'Estaciones al 100% de los pesos Open por separado y umbrales ergómetros de 1.500-2.000 m',
            ],
            5 => [
                'name' => 'Nivel 5: Bloques dobles',
                'title' => 'Bloques dobles — Pares de estaciones con fatiga real',
                'description' => 'Unir las estaciones en pares con fatiga acumulada (push→pull, row→ski, farmers→lunges, wall ball después de run), cronometrar las transiciones de Roxzone y correr 1 km ×2-3 a Z4. El test es el primer simulacro embebido del tramo: Hyrox Simulacro L5 (6 estaciones al 75% Open).',
                'objective' => 'Pares de estaciones con fatiga, transiciones de Roxzone cronometradas y carrera de 1 km a Z4; test: Hyrox Simulacro L5',
                'difficultyRating' => 5,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (WB 6 kg ×30, farmers Open ×100 m, HY4 re-validada)',
                'skillFocus' => 'Pares de estaciones con fatiga, transiciones de Roxzone cronometradas y carrera de 1 km a Z4',
            ],
            6 => [
                'name' => 'Nivel 6: Hyrox 3/4',
                'title' => 'Hyrox 3/4 — El simulacro completo de 8 estaciones',
                'description' => 'Ejecutar el simulacro completo de 8 estaciones al ~65% de Open como test del nivel (benchmark embebido), entrenar trenes de 3 estaciones seguidas con fatiga acumulada, umbrales ergómetros largos (5.000 m totales entre SkiErg y Row en la sesión B) y run Z2 de 50-60 min en la sesión D. Es el nivel más largo del programa hasta aquí: la sesión C de Fase 3 con el simulacro puede rozar las 2 horas.',
                'objective' => 'Simulacro completo de 8 estaciones al ~65% Open como test, trenes de 3 estaciones con fatiga y umbral ergómetro de 5.000 m; re-tests HY1-HY3',
                'difficultyRating' => 6,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (Hyrox Simulacro L5 bajo 50:00)',
                'skillFocus' => 'Simulacro completo de 8 estaciones al 65% Open, trenes de 3 estaciones con fatiga y umbral ergómetro de 5.000 m',
            ],
            7 => [
                'name' => 'Nivel 7: Hyrox Open',
                'title' => 'Hyrox Open — La carrera completa, pesos oficiales',
                'description' => 'Completar el simulacro completo de 8 estaciones al 100% de pesos Open como test del nivel (benchmark L7), entrenando con ritmo por estaciones (cada estación con su split objetivo), runs de 1 km a Z4 y wall balls ×100 en series planificadas. Es el nivel de la consagración: todo lo construido en N1-N6 se junta en una sola carrera.',
                'objective' => 'Carrera completa de Hyrox al 100% de pesos Open como test (Hyrox Simulacro L7), ritmo por estaciones con splits objetivo y estrategia de Roxzone; re-test HY4 a carga Open',
                'difficultyRating' => 7,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (Hyrox Simulacro L6 + re-tests HY1-HY3)',
                'skillFocus' => 'Carrera completa de Hyrox al 100% de pesos Open, ritmo por estaciones y estrategia de Roxzone',
            ],
            8 => [
                'name' => 'Nivel 8: Ritmo de competición',
                'title' => 'Ritmo de competición — La carrera, a objetivo',
                'description' => 'Ejecutar el simulacro completo a ritmo de competición (benchmark L8: mismas dosis Open que L7, cap más exigente y splits afinados), repetir el simulacro en las fases de circuito para afilar transiciones y wall balls, y cerrar el programa con el PFT final comparado con la marca base de N1. Las variantes Pro pesan en las sesiones A como objetivo opcional para quien compite en esa categoría.',
                'objective' => 'Ritmo de competición en la Hyrox completa (Hyrox Simulacro L8), repeticiones de simulacro, estrategia de carrera y PFT de cierre comparado con la marca base de N1',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (Hyrox Simulacro L7 + HY4 a carga Open)',
                'skillFocus' => 'Ritmo de competición en la Hyrox completa, repeticiones de simulacro y estrategia de carrera',
            ],
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    public static function getSessionData(int $levelNumber, string $sessionKey, string $phase): array
    {
        $meta = self::sessionMeta($levelNumber, $sessionKey);

        $fuerzaSkill = self::fuerzaSkillBlock($levelNumber, $sessionKey, $phase);
        $wod = self::wodBlock($levelNumber, $sessionKey, $phase);

        $blocks = [
            self::block0($levelNumber, $phase, $sessionKey),
            $fuerzaSkill,
            $wod,
            self::posturalBlock($levelNumber, $sessionKey),
            self::descargaBlock($levelNumber, $sessionKey, $phase),
        ];

        if (isset($wod['benchmark'])) {
            // El bloque WOD es el benchmark embebido: el round ignora configs
            $blocks[2] = array_merge([
                'blockType' => TrainingRound::BLOCK_WOD,
                'rounds' => 1,
                'restBetweenRounds' => 1,
                'restBetweenExercises' => 5,
                'restBetweenSets' => 0,
                'exercises' => [],
            ], $wod);
        }

        return [
            'name' => $meta['name'],
            'goal' => $meta['goal'],
            'sessionType' => $meta['sessionType'],
            'blocks' => self::addRestBetweenBlocks($blocks, $levelNumber),
        ];
    }

    private static function sessionMeta(int $level, string $sessionKey): array
    {
        return match ($level) {
            1 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run + Estaciones de fuerza ligera', 'goal' => 'Run Z2 por tiempo y técnica de estaciones sin carga (squat, wall ball y sled)', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros — SkiErg + Row', 'goal' => 'Técnica de remada (piernas-core-brazos) en intervalos cortos + core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito de pares de estaciones', 'goal' => 'Pares de estaciones con fatiga de transición (lógica de Roxzone)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad', 'goal' => 'Run Z2 continuo (el run largo del nivel) + técnica de BBJ y plank', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            2 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run intervals + fuerza de estación', 'goal' => 'Intervals Z3 (400→800 m) + farmers, wall ball y sled al 50% Open', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales', 'goal' => 'SkiErg y Row a ritmo umbral + core anti-rotación', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuitos de pares con Roxzone', 'goal' => 'Pares con transición run→estación; ensayo de HY3 en el EMOM de F3', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad + HY1', 'goal' => 'Run Z2 continuo (kilómetro final cronometrado en F3, ensayo HY1)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            3 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run 1 km Z3 + estaciones con fatiga', 'goal' => 'Kilómetros a Z3 + sled, farmers (ensayo HY4) y wall ball al 50% Open', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales', 'goal' => 'SkiErg y Row en umbral (el 1 km de Row del PFT se construye aquí)', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Simulacro / Circuito', 'goal' => 'Circuitos de pares (F1/F2/F4) o benchmark embebido Hyrox Simulacro L3 (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad + HY4', 'goal' => 'Run Z2 con bloques Z3 + farmers 2×16 kg (kilómetro de farmers segmentado, ensayo HY4)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            4 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run intervals Z3-Z4 + fuerza Open del trineo y el farmers', 'goal' => 'Kilómetros Z3-Z4 + sled push y farmers en progresión a los pesos Open', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales largos', 'goal' => 'SkiErg y Row de 1.500-2.000 m a umbral sostenible + core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito — la estación Open en soledad', 'goal' => 'La estación Open del día con run delante; sin simulacro en N4', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad + ensayo HY4', 'goal' => 'Run Z2 con bloques Z3 + farmers con carga de puerta (ensayo del re-test HY4)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            5 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run 1 km Z4 + pares de fuerza con fatiga', 'goal' => 'Kilómetros Z3-Z4 + pares push→pull y farmers→lunge con fatiga', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros en par — row→ski', 'goal' => 'Par Row→SkiErg con fatiga + SkiErg de umbral suelto', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito de pares con Roxzone', 'goal' => 'Pares y triples con transiciones cronometradas (F1/F2/F4) o benchmark embebido Hyrox Simulacro L5 (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad', 'goal' => 'Run Z2 con bloques Z4 + farmers 200 m (ensayo dosis L5) y lunge cargado', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            6 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run Z4 + trenes de 3 estaciones', 'goal' => 'Kilómetros Z3-Z4 + trenes SkiErg→Sled Push→Sled Pull y Farmers→Lunge→Wall Ball', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros — 5.000 m de umbral', 'goal' => 'SkiErg y Row umbral hasta 5.000 m acumulados (F2) + core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Simulacro / trenes', 'goal' => 'Trenes con run (F1/F2/F4) o benchmark embebido Hyrox Simulacro L6 (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad + re-tests HY1-HY3', 'goal' => 'Run Z2 largo + ensayos exactos de los re-tests HY1-HY3 + farmers de agarre', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            7 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run a ritmo de carrera + estación Open con split', 'goal' => 'Kilómetros a ritmo objetivo/negativo + sled, lunge y farmers Open con splits cronometrados', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros a ritmo de carrera', 'goal' => 'SkiErg y Row a split de carrera (4:30 / 4:00 M) + core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Simulacro / circuito de ritmo', 'goal' => 'Trenes de estaciones con splits y transiciones anotadas (F1/F2/F4) o benchmark embebido Hyrox Simulacro L7 (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + re-test HY4 a carga Open', 'goal' => 'Run Z2 con bloques de carrera + farmers 2×24/2×16 kg ×100 m (re-test entero)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            8 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Intervals a ritmo de carrera con estación intercalada', 'goal' => 'Kilómetros a ritmo objetivo con ritmo negativo + wall balls ×100 en series y sled con split (variante Pro en nota)', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros a ritmo objetivo', 'goal' => 'SkiErg y Row a split afilado del L8 (4:20 / 3:50 M) + core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Repeticiones de simulacro', 'goal' => 'Medias carreras con splits (F1/F2), benchmark embebido Hyrox Simulacro L8 (F3) o movimiento (F4)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + PFT de cierre + movilidad', 'goal' => 'Run Z2 con bloques de carrera + farmers del PFT + BBJ cronometrado; PFT en RX como toma de contacto (F2)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function addRestBetweenBlocks(array $blocks, int $level): array
    {
        if (\count($blocks) <= 1) {
            return $blocks;
        }

        // N1-N6: transición breve entre bloques (60 s), no descanso de recuperación.
        // N7-N8: 90 s (regla del proyecto: niveles >6 → 90 s), sesiones más densas en calidad.
        $rest = $level <= 6 ? 60 : 90;
        foreach ($blocks as $index => &$block) {
            if ($index < \count($blocks) - 1) {
                $block['restAfterBlock'] = $rest;
            }
        }

        return $blocks;
    }

    private static function block(string $blockType, int $rounds, int $restRounds, int $restEx, array $exercises, int $restSets = 0): array
    {
        return [
            'blockType' => $blockType,
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
    // BLOQUE 0 — PREPARACIÓN Y MOVILIDAD (fluida, sin descansos de fuerza)
    // Siembra permanente de las puertas HY1-HY4.
    // Variantes: A (run/fuerza), B (ergómetros), C (circuito/simulacro),
    // D (aeróbico).
    // ========================================================================

    private static function block0(int $level, string $phase, string $sessionKey): array
    {
        return match ($level) {
            1 => self::block0L1($phase, $sessionKey),
            2 => self::block0L2($phase, $sessionKey),
            3 => self::block0L3($phase, $sessionKey),
            4 => self::block0L4($phase, $sessionKey),
            5 => self::block0L5($phase, $sessionKey),
            6 => self::block0L6($phase, $sessionKey),
            7, 8 => self::block0L7($phase, $sessionKey),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function block0L1(string $phase, string $sessionKey): array
    {
        // Tobillo (siembra HY1)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['20s/lado', '20s/lado', '10'],
            'progression' => ['25s/lado', '25s/lado', '12'],
            'intensification' => ['30s/lado', '30s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Rodilla/cuádriceps (siembra HY1)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['20s', '4/lado'],
            'progression' => ['30s', '6/lado'],
            'intensification' => ['40s', '6/lado'],
            'deload' => ['45s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/bisagra (siembra HY4)
        [$gluteBridge, $hipFlexor, $rockback] = match ($phase) {
            'base' => ['10', '20s/lado', '8'],
            'progression' => ['12', '25s/lado', '10'],
            'intensification' => ['15', '30s/lado', '10'],
            'deload' => ['10', '30s/lado', '8'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (siembra HY2/HY3)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['10', '10', '20s'],
            'progression' => ['12', '12', '20s'],
            'intensification' => ['15', '12', '25s'],
            'deload' => ['12', '10', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '8',
            'progression' => '10',
            'intensification' => '12',
            'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + estaciones): prepara piernas para correr
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Active March', '30s');
            $exercises[] = self::ex('Deep Squat Hold', '30s');
        }
        // Variante B (Ergómetros): dislocates extra antes del SkiErg + respiración
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Circuito): énfasis en tobillo (HY3) + glúteo medio si hay lunges
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
            $exercises[] = self::ex('Clamshell', '10/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L2(string $phase, string $sessionKey): array
    {
        // Tobillo/rodilla (HY1, foco)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '12'],
            'progression' => ['30s/lado', '30s/lado', '15'],
            'intensification' => ['35s/lado', '35s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (HY2, soporte)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['30s', '6/lado'],
            'progression' => ['40s', '8/lado'],
            'intensification' => ['45s', '8/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/bisagra (HY4, soporte)
        $hipFlexor = '30s/lado';
        [$rockback, $gluteBridge] = match ($phase) {
            'base' => ['10', '15'],
            'progression' => ['12', '15'],
            'intensification' => ['12', '15'],
            'deload' => ['10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (HY2/HY3)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '12', '25s'],
            'progression' => ['15', '15', '25s'],
            'intensification' => ['15', '15', '30s'],
            'deload' => ['12', '12', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '10',
            'progression', 'intensification' => '12',
            'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + fuerza): sentadilla profunda justo antes de la wall ball
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Deep Squat Hold', '30s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Circuito): ensayo HY3 (burpee lento + muñeca)
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Burpee', '5', 2);
            $exercises[] = self::ex('Wrist Push-Up', '6', 2);
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L3(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido: el
        // simulacro es el protagonista y entra rápido al calentamiento específico.
        if ('session_c' === $sessionKey && 'intensification' === $phase) {
            $minimal = [
                self::ex('Jumping Jack', '60s'),
                self::ex('Active March', '60s'),
                self::ex('Arm Circles', '10'),
                self::ex('Hip Circles', '10'),
                self::ex('Calf Stretch Wall', '30s/lado'),
                self::ex('Wrist Prayer', '20s'),
                self::ex('Scapular Push Up', '10'),
            ];

            return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $minimal, 5);
        }

        // Tobillo/rodilla (HY1, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '15'],
            'progression' => ['35s/lado', '35s/lado', '15'],
            'intensification' => ['40s/lado', '40s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (HY2, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['40s', '8/lado'],
            'progression' => ['45s', '8/lado'],
            'intensification' => ['60s', '8/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (HY4, foco nuevo)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base' => ['30s/lado', '12', '15'],
            'progression' => ['30s/lado', '12', '15'],
            'intensification' => ['40s/lado', '12', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (HY2/HY3, mantenido)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base', 'progression', 'intensification' => ['15', '15', '30s'],
            'deload' => ['12', '12', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base', 'progression' => '12',
            'intensification' => '15',
            'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + fuerza): sentadilla antes de la wall ball + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Deep Squat Hold', '30s');
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Simulacro/circuito): énfasis en tobillo y muñeca
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
            $exercises[] = self::ex('Wrist Prayer', '20s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L4(string $phase, string $sessionKey): array
    {
        // Tobillo/rodilla (HY1, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '15'],
            'progression' => ['35s/lado', '35s/lado', '15'],
            'intensification' => ['40s/lado', '40s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (HY2, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['45s', '8/lado'],
            'progression' => ['60s', '8/lado'],
            'intensification' => ['60s', '10/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (HY4, mantenido)
        $hipFlexor = match ($phase) {
            'base', 'progression' => '30s/lado',
            'intensification' => '40s/lado',
            'deload' => '30s/lado',
            default => throw new \InvalidArgumentException(),
        };
        [$rockback, $gluteBridge] = match ($phase) {
            'base', 'progression', 'intensification' => ['12', '15'],
            'deload' => ['10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro (foco nuevo: WB 6 kg a diana oficial)
        [$dislocate, $pullApart, $scapPushUp] = match ($phase) {
            'base', 'progression' => ['15', '15', '12'],
            'intensification' => ['20', '15', '15'],
            'deload' => ['12', '12', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Muñeca (HY3, mantenido)
        $wrist = match ($phase) {
            'base', 'progression' => '30s',
            'intensification' => '45s',
            'deload' => '30s',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Scapular Push Up', $scapPushUp),
            self::ex('Wrist Prayer', $wrist),
            self::ex('Wrist Extension Stretch', $wrist),
        ];

        // Variante A (Run + fuerza): sentadilla antes de la wall ball + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Deep Squat Hold', '30s');
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Circuito): énfasis en muñeca y tobillo + ensayo de BBJ (§2)
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
            $exercises[] = self::ex('Broad Jump Burpee', '3');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L5(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (simulacro L5)
        if ('session_c' === $sessionKey && 'intensification' === $phase) {
            return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, [
                self::ex('Jumping Jack', '60s'),
                self::ex('Active March', '60s'),
                self::ex('Arm Circles', '10'),
                self::ex('Hip Circles', '10'),
                self::ex('Calf Stretch Wall', '30s/lado'),
                self::ex('Wrist Prayer', '20s'),
                self::ex('Scapular Push Up', '10'),
            ], 5);
        }

        // Tobillo/rodilla (HY1, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '15'],
            'progression' => ['35s/lado', '35s/lado', '15'],
            'intensification' => ['40s/lado', '40s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (HY2, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['60s', '8/lado'],
            'progression', 'intensification' => ['60s', '10/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/agarre (foco: farmers→lunges)
        $hipFlexor = match ($phase) {
            'base' => '30s/lado',
            'progression', 'intensification' => '40s/lado',
            'deload' => '30s/lado',
            default => throw new \InvalidArgumentException(),
        };
        [$rockback, $gluteBridge] = match ($phase) {
            'base', 'progression' => ['12', '15'],
            'intensification' => ['15', '15'],
            'deload' => ['10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (HY2/HY3, mantenido)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base', 'progression' => ['15', '15', '30s'],
            'intensification' => ['20', '15', '45s'],
            'deload' => ['12', '12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '12',
            'progression', 'intensification' => '15',
            'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + fuerza): sentadilla antes del lunge + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Deep Squat Hold', '30s');
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Simulacro/circuito): énfasis en muñeca y tobillo
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L6(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (simulacro L6)
        if ('session_c' === $sessionKey && 'intensification' === $phase) {
            return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, [
                self::ex('Jumping Jack', '60s'),
                self::ex('Active March', '60s'),
                self::ex('Arm Circles', '10'),
                self::ex('Hip Circles', '10'),
                self::ex('Calf Stretch Wall', '30s/lado'),
                self::ex('Wrist Prayer', '20s'),
                self::ex('Scapular Push Up', '10'),
            ], 5);
        }

        // Tobillo/rodilla (HY1, re-test camino)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['35s/lado', '35s/lado', '15'],
            'progression' => ['40s/lado', '40s/lado', '15'],
            'intensification' => ['45s/lado', '45s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (HY2, re-test camino)
        [$deepSquat, $cossack] = match ($phase) {
            'base', 'progression' => ['60s', '10/lado'],
            'intensification' => ['75s', '10/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/cadena posterior (HY4, mantenido)
        $hipFlexor = match ($phase) {
            'base', 'progression' => '40s/lado',
            'intensification' => '45s/lado',
            'deload' => '30s/lado',
            default => throw new \InvalidArgumentException(),
        };
        [$rockback, $gluteBridge] = match ($phase) {
            'base' => ['12', '15'],
            'progression', 'intensification' => ['15', '15'],
            'deload' => ['10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (HY2/HY3, re-test camino)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '15', '45s'],
            'progression', 'intensification' => ['20', '15', '45s'],
            'deload' => ['12', '12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base', 'progression', 'intensification' => '15',
            'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + fuerza): sentadilla antes del lunge + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Deep Squat Hold', '30s');
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Simulacro/trenes): énfasis en muñeca y tobillo
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    /**
     * Bloque 0 de N7 y N8 (tablas idénticas en docs/detalle_hyrox_niveles_07-08.md).
     * Con la carga Open en juego, el Bloque 0 es la primera línea de defensa; en
     * N8 también es ritual de preparación: idéntico antes de cada simulacro.
     */
    private static function block0L7(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (simulacro)
        if ('session_c' === $sessionKey && 'intensification' === $phase) {
            return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, [
                self::ex('Jumping Jack', '60s'),
                self::ex('Active March', '60s'),
                self::ex('Arm Circles', '10'),
                self::ex('Hip Circles', '10'),
                self::ex('Calf Stretch Wall', '30s/lado'),
                self::ex('Wrist Prayer', '20s'),
                self::ex('Scapular Push Up', '10'),
            ], 5);
        }

        // Tobillo/rodilla (HY1, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['35s/lado', '35s/lado', '15'],
            'progression' => ['40s/lado', '40s/lado', '15'],
            'intensification' => ['45s/lado', '45s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Sentadilla profunda (HY2, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base', 'progression' => ['60s', '10/lado'],
            'intensification' => ['75s', '10/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/cadena posterior (HY4, mantenido/re-test camino)
        $hipFlexor = match ($phase) {
            'base' => '40s/lado',
            'progression', 'intensification' => '40s/lado',
            'deload' => '30s/lado',
            default => throw new \InvalidArgumentException(),
        };
        [$rockback, $gluteBridge] = match ($phase) {
            'base' => ['12', '15'],
            'progression', 'intensification' => ['15', '15'],
            'deload' => ['10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (HY2/HY3, mantenido)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '15', '45s'],
            'progression', 'intensification' => ['20', '15', '45s'],
            'deload' => ['12', '12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base', 'progression', 'intensification' => '15',
            'deload' => '10',
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Jumping Jack', '60s'),
            self::ex('Active March', '60s'),
            self::ex('Arm Circles', '10'),
            self::ex('Hip Circles', '10'),
            self::ex('Calf Stretch Wall', $calf),
            self::ex('Bent-Knee Soleus Stretch', $soleus),
            self::ex('Calf Raise on Step', $calfRaise),
            self::ex('Deep Squat Hold', $deepSquat),
            self::ex('Cossack Squat', $cossack),
            self::ex('Kneeling Hip Flexor Stretch', $hipFlexor),
            self::ex('Adductor Rockback', $rockback),
            self::ex('Glute Bridge', $gluteBridge),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + fuerza/ritmo): sentadilla antes del lunge/wall ball + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Deep Squat Hold', '30s');
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Simulacro/circuito): énfasis en muñeca y tobillo
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    // ========================================================================
    // BLOQUE POSTURAL (prehab — selección dosificada por sesión, nunca eliminado)
    // Descanso entre ejercicios posturales: 45-60 s; calidad por encima de fatiga.
    // ========================================================================

    private static function posturalBlock(int $level, string $sessionKey): array
    {
        return self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, self::posturalExercises($level, $sessionKey), 50);
    }

    private static function posturalExercises(int $level, string $sessionKey): array
    {
        return match ($level) {
            1 => match ($sessionKey) {
                // A: tobillo/core
                'session_a' => [
                    self::ex('Dead Bug', '8/lado', 2),
                    self::ex('Bird Dog', '6/lado', 2),
                    self::ex('Pallof Press', '10/lado', 2),
                ],
                // B: hombro
                'session_b' => [
                    self::ex('Scapular Push Up', '8', 2),
                    self::ex('Prone Y Raise', '8', 2),
                    self::ex('Face Pull', '12', 2),
                ],
                // C: lumbar/core
                'session_c' => [
                    self::ex('Dead Bug', '8/lado', 2),
                    self::ex('Bird Dog', '6/lado', 2),
                    self::ex('Plank', '30s', 2),
                ],
                // D: trío suave
                'session_d' => [
                    self::ex('Band Pull-Apart', '12', 2),
                    self::ex('Glute Bridge', '12', 2),
                    self::ex('Dead Bug', '8/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            2 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Dead Bug', '10/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Pallof Press', '12/lado', 2),
                ],
                'session_b' => [
                    self::ex('Scapular Push Up', '10', 2),
                    self::ex('Prone Y Raise', '10', 2),
                    self::ex('Face Pull', '15', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '10/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Plank', '45s', 2),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '15', 2),
                    self::ex('Glute Bridge', '15', 2),
                    self::ex('Dead Bug', '10/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            3 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Dead Bug', '12/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Pallof Press', '12/lado', 2),
                ],
                'session_b' => [
                    self::ex('Scapular Push Up', '10', 3),
                    self::ex('Prone Y Raise', '12', 2),
                    self::ex('Face Pull', '15', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '12/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Plank', '45s', 3),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '15', 2),
                    self::ex('Glute Bridge', '15', 2),
                    self::ex('Dead Bug', '10/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            4 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Dead Bug', '12/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Pallof Press', '15/lado', 2),
                ],
                'session_b' => [
                    self::ex('Scapular Push Up', '10', 3),
                    self::ex('Prone Y Raise', '12', 2),
                    self::ex('Face Pull', '15', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '12/lado', 2),
                    self::ex('Bird Dog', '8/lado', 2),
                    self::ex('Plank', '60s', 3),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '15', 2),
                    self::ex('Glute Bridge', '15', 2),
                    self::ex('Dead Bug', '10/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            5, 6, 7, 8 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Dead Bug', '10/lado', 3),
                    self::ex('Bird Dog', '10/lado', 2),
                    self::ex('Pallof Press', '15/lado', 2),
                ],
                'session_b' => [
                    self::ex('Scapular Push Up', '12', 3),
                    self::ex('Prone Y Raise', '12', 2),
                    self::ex('Face Pull', '15', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '12/lado', 2),
                    self::ex('Bird Dog', '10/lado', 2),
                    self::ex('Plank', '60s', 3),
                ],
                'session_d' => [
                    self::ex('Band Pull-Apart', '15', 2),
                    self::ex('Glute Bridge', '15', 2),
                    self::ex('Dead Bug', '12/lado', 2),
                ],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    // ========================================================================
    // BLOQUE DESCARGA (respiración + Child's Pose + estiramiento de la zona)
    // ========================================================================

    private static function descargaBlock(int $level, string $sessionKey, string $phase): array
    {
        $stretch = match ($sessionKey) {
            'session_a' => self::ex('Calf Stretch Wall', '30s/lado'),
            'session_b' => self::ex('Wrist Prayer', '30s'),
            'session_c' => self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
            'session_d' => self::ex('Deep Squat Hold', '45s'),
            default => throw new \InvalidArgumentException(),
        };

        $exercises = [
            self::ex('Diaphragmatic Breathing', 'deload' === $phase ? '120s' : '90s'),
            self::ex("Child's Pose", 'deload' === $phase ? '60s' : '45s'),
            $stretch,
        ];

        // En Fase 4 (Recuperación) la descarga se amplía con toda la movilidad del nivel
        if ('deload' === $phase) {
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
            $exercises[] = self::ex('Kneeling Hip Flexor Stretch', '30s/lado');
            $exercises[] = self::ex('Shoulder Dislocate', '10');
        }

        return self::block(TrainingRound::BLOCK_DESCARGA, 1, 1, 15, $exercises, 20);
    }

    // ========================================================================
    // BLOQUE FUERZA_SKILL
    // ========================================================================

    private static function fuerzaSkillBlock(int $level, string $sessionKey, string $phase): array
    {
        return match ($level) {
            1 => self::fuerzaSkillL1($sessionKey, $phase),
            2 => self::fuerzaSkillL2($sessionKey, $phase),
            3 => self::fuerzaSkillL3($sessionKey, $phase),
            4 => self::fuerzaSkillL4($sessionKey, $phase),
            5 => self::fuerzaSkillL5($sessionKey, $phase),
            6 => self::fuerzaSkillL6($sessionKey, $phase),
            7 => self::fuerzaSkillL7($sessionKey, $phase),
            8 => self::fuerzaSkillL8($sessionKey, $phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function fuerzaSkillL1(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Run Z2 por tiempo (ritmo conversacional; si hay que caminar, se camina y se anota)
                self::ex('Sprint', match ($phase) {
                    'base' => '900s',
                    'progression' => '1200s',
                    'intensification' => '1500s',
                    'deload' => '720s',
                    default => throw new \InvalidArgumentException(),
                }),
                // KB 8/6 kg; base del lunge de N2 y del wall ball
                self::ex('Goblet Squat', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 4 kg, diana baja (siembra HY2): la bola parte de sentadilla profunda
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '6', 'progression' => '8', 'intensification' => '8', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Sin carga (si no hay sled: Step Up 3×8/lado con mancuernas ligeras)
                self::ex('Sled Push', match ($phase) {
                    'base' => '20s', 'progression' => '30s', 'intensification' => '30s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Técnica primero: bisagra de cadera, remada piernas-core-brazos; Z2-Z3
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '25s', 'progression' => '25s', 'intensification' => '55s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                // Drive con piernas, luego espalda, luego brazos
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '50s', 'progression' => '50s', 'intensification' => '100s', 'deload' => '50s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 3, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_c' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Técnica pausada; semilla del sandbag lunge (carga en N2-N3)
                self::ex('Walking Lunge', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '12', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Siembra HY3: pecho al suelo, salto corto con control
                self::ex('Burpee', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // El run largo del nivel; en F3 se cierra con 1 km continuo (ensayo HY1)
                self::ex('Sprint', match ($phase) {
                    'base' => '900s', 'progression' => '1200s', 'intensification' => '1500s', 'deload' => '720s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Solo técnica de aterrizaje (§2): distancia corta, recepción perfecta
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '4', 'progression' => '5', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Plank', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillL2(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Intervals Z3 (entre intervalos, trote muy suave o caminata)
                self::ex('Sprint', match ($phase) {
                    'base' => '100s', 'progression' => '150s', 'intensification' => '200s', 'deload' => '100s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 6, 'progression' => 5, 'intensification' => 4, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                // 2×12/2×8 kg; ensayo camino de HY4
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '25s', 'progression' => '25s', 'intensification' => '30s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 4 kg, diana media; la dosis del test es ×25
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // F1: 3×4×12,5 m (60/40 kg); F2-F3: 4×12,5 m (75/50 kg) = 50 m por serie
                self::ex('Sled Push', match ($phase) {
                    'base', 'progression', 'intensification' => '90s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Últimos 250 m de cada serie a umbral controlado
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '55s', 'progression' => '55s', 'intensification' => '55s', 'deload' => '55s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 5, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                // El row del PFT es 1 km — aquí se construye la media
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '100s', 'progression' => '100s', 'intensification' => '150s', 'deload' => '100s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Activación corta, series limpias
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Primer lunge cargado (8/6 kg); semilla del sandbag lunge
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // En F3 el último kilómetro se cronometra sin caminar: ensayo explícito de HY1
                self::ex('Sprint', match ($phase) {
                    'base' => '1500s', 'progression' => '1800s', 'intensification' => '2100s', 'deload' => '1200s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Mancuernas ligeras; alternativa al goblet lunge
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '8/lado', 'progression' => '10/lado', 'intensification' => '10/lado', 'deload' => '8/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Plank', match ($phase) {
                    'base' => '45s', 'progression' => '50s', 'intensification' => '60s', 'deload' => '40s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillL3(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // 1 km a Z3; entre kilómetros, trote suave 2 min
                self::ex('Sprint', match ($phase) {
                    'base' => '270s', 'progression' => '270s', 'intensification' => '270s', 'deload' => '270s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // 50% Open (75/50 kg); en F3 la carga no sube (interferencia del simulacro)
                self::ex('Sled Push', match ($phase) {
                    'base', 'progression', 'intensification' => '90s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 5, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 2×16/2×12 kg es la dosis del test; en F3 se ensayan los 100 m completos (ensayo HY4)
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '30s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 4 kg; series planificadas, volumen de hombro sin fallo
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '20', 'deload' => '12',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Ritmo umbral; la dosis del simulacro es 500 m
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '100s', 'progression' => '100s', 'intensification' => '150s', 'deload' => '100s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // El 1 km de Row del PFT se construye aquí; salida controlada, cierre fuerte
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '150s', 'progression' => '210s', 'intensification' => '210s', 'deload' => '210s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico del simulacro: toca las 4 estaciones con dosis mínimas
                self::ex('Wall Ball Shot', '8'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Sled Push', '15s'),
                self::ex('Sled Pull', '15s'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '10', 'progression' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Mantiene el patrón de lunge cargado (12/8 kg) entre simulacros
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base' => '10', 'progression' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Z2 con bloques Z3 dentro (3×3' en F1 → 5×3' en F3): correr rápido ya fatigado (§4)
                self::ex('Sprint', match ($phase) {
                    'base' => '1800s', 'progression' => '2100s', 'intensification' => '2400s', 'deload' => '1500s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Kilómetro de farmers segmentado; en F3 se ensayan los 100 m completos (criterio exacto de HY4)
                self::ex('Farmer Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Volumen moderado con aterrizaje perfecto (§2)
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '6', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // BLOQUE WOD (circuitos de pares con fatiga de transición / simulacro)
    // ========================================================================

    private static function wodBlock(int $level, string $sessionKey, string $phase): array
    {
        return match ($level) {
            1 => self::wodL1($sessionKey, $phase),
            2 => self::wodL2($sessionKey, $phase),
            3 => self::wodL3($sessionKey, $phase),
            4 => self::wodL4($sessionKey, $phase),
            5 => self::wodL5($sessionKey, $phase),
            6 => self::wodL6($sessionKey, $phase),
            7 => self::wodL7($sessionKey, $phase),
            8 => self::wodL8($sessionKey, $phase),
            default => throw new \InvalidArgumentException("Nivel {$level} no definido"),
        };
    }

    private static function wodL1(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Wall Ball Shot', '5'),
                ]) + ['description' => '3 rondas: Sprint 100 m + Wall Ball Shot ×5 (4 kg, diana baja). 60-90 s entre rondas; transición caminando, sin prisa.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '35s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '4 rondas: Sprint 150 m + Wall Ball Shot ×8 (4 kg, diana baja). 60 s entre rondas; primer contacto con la lógica de Roxzone.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '4 rondas: Sprint 200 m + Burpee ×5. Ensayo de HY3 en contexto; burpees completos pero lentos.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Wall Ball Shot', '5'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Wall Ball Shot ×5 (4 kg). Movimiento, no fatiga; el PFT cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Sit-up', '10'),
                    self::ex('Glute Bridge', '10'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '3 rondas: 10 Sit-up + 10 Glute Bridge + 30 s Plank. 60-90 s entre rondas; core básico.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '6'),
                    self::ex('Mountain Climber', '8'),
                ]) + ['description' => "AMRAP 8': 6 Kettlebell Swing Russian (16/12 kg) + 8 Mountain Climber. Primer WOD del nivel; bisagra con KB (preparación del sled pull). Rondas estimadas para llenar la ventana del AMRAP."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 30, 15, [
                    self::ex('SkiErg Sprint', '30s'),
                    self::ex('Mountain Climber', '30s'),
                ], 30) + ['description' => '4 × (30 s trabajo / 30 s descanso): SkiErg Sprint y Mountain Climber alternando. Umbral suave; transiciones rápidas entre máquina y suelo.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Glute Bridge', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 8 Glute Bridge. Movimiento; el PFT cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 20, [
                    self::ex('Wall Ball Shot', '8'),
                    self::ex('Burpee', '6'),
                ]) + ['description' => '3 rondas: 8 Wall Ball Shot (4 kg, diana baja) + 6 Burpee. 60-90 s entre rondas; primer par empuje-motora.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Wall Ball Shot', '10'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => '4 rondas: 10 Wall Ball Shot (4 kg) + 8 Burpee. 60 s entre rondas; series planificadas en la WB (§3).'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Wall Ball Shot', '10'),
                    self::ex('Walking Lunge', '10'),
                    self::ex('Farmers Walk', '25s'),
                ]) + ['description' => '4 rondas: 10 Wall Ball Shot (4 kg) + 10 Walking Lunge + 40 m Farmers Walk (mancuernas ligeras). Primer triple estación→carga; siembra HY4 con carga mínima.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Wall Ball Shot', '6'),
                    self::ex('Burpee', '6'),
                ]) + ['description' => '2 rondas suaves: 6 Wall Ball Shot (4 kg) + 6 Burpee. Movimiento; el PFT cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Glute Bridge', '8'),
                ]) + ['description' => '3 rondas: 8 Step Up (bajo, ritmo suave) + 10 Mountain Climber + 8 Glute Bridge. 60-90 s entre rondas; sin carga.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '4 rondas: 10 Step Up + 10 Mountain Climber + 10 Sit-up. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '3 rondas: 10 Step Up (con mancuernas ligeras) + 10 Kettlebell Swing Russian (16/12 kg) + 30 s Hollow Body Hold. Primer gesto con carga sostenida; control lumbar al final.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el PFT cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function wodL2(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '3 rondas: Sprint 200 m + Wall Ball Shot ×8 (4 kg). 60-90 s entre rondas; primera transición con carga real.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '70s'),
                    self::ex('Farmers Walk', '20s'),
                ]) + ['description' => '4 rondas: Sprint 300 m + Farmers Walk 30 m (2×12/2×8 kg). 60 s entre rondas; el farmers tras correr es la estación más Roxzone que existe.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '70s'),
                    self::ex('Wall Ball Shot', '10'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '4 rondas: Sprint 300 m + Wall Ball Shot ×10 (4 kg) + Burpee ×5. Ensayo de la lógica del test; series de WB sin romper (§3).'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Wall Ball Shot', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 200 m + Wall Ball Shot ×6 (4 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Sit-up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (16/12 kg) + 8 Sit-up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '6'),
                    self::ex('Mountain Climber', '6'),
                ]) + ['description' => "AMRAP 10': 6 Kettlebell Swing Russian (16/12 kg) + 6 Mountain Climber. Bisagra bajo fatiga; control lumbar (§5). Rondas estimadas para llenar la ventana del AMRAP."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 10, [
                    self::ex('SkiErg Sprint', '60s'),
                ], 60) + ['description' => "5 × (1' trabajo / 1' descanso): SkiErg Sprint a ritmo umbral. 1.000 m acumulados a ritmo alto; ensayo del SkiErg del PFT."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Wall Ball Shot', '10'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '4 rondas: Sprint 200 m + Wall Ball Shot ×10 (4 kg) + Burpee ×5. 60-90 s entre rondas; par empuje-motora.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '70s'),
                    self::ex('Kettlebell Goblet Lunge', '10/lado'),
                    self::ex('Sled Push', '25s'),
                ]) + ['description' => '4 rondas: Sprint 300 m + Kettlebell Goblet Lunge 10/lado (8-12 kg) + Sled Push 12,5 m (60-75 kg). Par de piernas con fatiga; §1 en el sled.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 1, 10, [
                    self::ex('Sprint', '25s'),
                    self::ex('Wall Ball Shot', '10'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => "EMOM 12' (cada ronda modelada = 2 minutos): minutos impares: Sprint 100 m + Wall Ball Shot ×10 (4 kg); minutos pares: Burpee ×8. Ensayo HY3 en contexto; si no llegas al minuto, reduce reps, no técnica."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Wall Ball Shot', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 200 m + Wall Ball Shot ×6 (4 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 10 Step Up + 10 Mountain Climber + 30 s Hollow Body Hold. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Dumbbell Lunge', '8'),
                    self::ex('Push Up', '8'),
                ]) + ['description' => "AMRAP 12': 8 Kettlebell Swing Russian (16/12 kg) + 8 Dumbbell Lunge (ligero) + 8 Push Up. Primer AMRAP de 12' con carga; bisagra bajo fatiga. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function wodL3(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sprint', '90s'),
                    self::ex('Wall Ball Shot', '12'),
                ]) + ['description' => '3 rondas: Sprint 400 m + Wall Ball Shot ×12 (4 kg). 90 s entre rondas; primera transición larga.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '120s'),
                    self::ex('Sled Push', '25s'),
                    self::ex('Burpee', '6'),
                ]) + ['description' => '4 rondas: Sprint 500 m + Sled Push 12,5 m (75/50 kg) + Burpee ×6. Par push-motora con fatiga real; §1 en el sled.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sprint', '120s'),
                    self::ex('Farmers Walk', '30s'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Farmers Walk 50 m (2×16/2×12 kg). Mantener, no construir: el simulacro de la sesión C está cerca.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '70s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Wall Ball Shot ×8 (4 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas; bisagra pesada, control lumbar.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 10, [
                    self::ex('SkiErg Sprint', '60s'),
                ], 60) + ['description' => "5 × (1' trabajo / 1' descanso): SkiErg Sprint a umbral. Acumula 1.000-1.250 m; transiciones rápidas sentado-pie."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Mountain Climber', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => "AMRAP 12': 8 Kettlebell Swing Russian (24/16 kg) + 8 Mountain Climber + 8 Sit-up. Umbral sostenido; §5 si la lumbar protesta. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '120s'),
                    self::ex('SkiErg Sprint', '55s'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '4 rondas: Sprint 500 m + SkiErg Sprint 250 m + Wall Ball Shot ×10 (4 kg). 90 s entre rondas; par ergómetro-empuje con fatiga.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '120s'),
                    self::ex('Sled Push', '25s'),
                    self::ex('Sled Pull', '25s'),
                ]) + ['description' => '4 rondas: Sprint 500 m + Sled Push 12,5 m (75/50 kg) + Sled Pull 12,5 m (52/39 kg). El par push→pull es el más exigente de la carrera; §1 siempre.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Hyrox Simulacro L3',
                    'benchmarkNote' => 'Benchmark embebido: Hyrox Simulacro L3 en modalidad del nivel (pesos 50% Open de la ficha): 4×(run 500 m + estación: SkiErg 500, Sled Push 25 m, Sled Pull 25 m, WB ×25). Se ejecuta con el paso benchmark del player (stopwatch + cap 45:00); la marca alimenta el test del nivel. Empezar fresco: nada de sled pesado en la sesión anterior.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '70s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Wall Ball Shot ×8 (4 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Dumbbell Lunge', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 10 Dumbbell Lunge (ligero) + 10 Mountain Climber + 30 s Hollow Body Hold. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 7, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Step Up', '8'),
                    self::ex('Push Up', '8'),
                ]) + ['description' => "AMRAP 15': 8 Kettlebell Swing Russian (16/12 kg) + 8 Step Up + 8 Push Up. El AMRAP más largo del nivel; control de ritmo de principio a fin. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 4 — FUERZA_SKILL (estaciones Open por separado, series largas)
    // ========================================================================

    private static function fuerzaSkillL4(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // 1 km a Z3-Z4; el último kilómetro siempre al ritmo más sostenible
                self::ex('Sprint', '270s', match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Progresión a Open (152/102 kg): 4×12,5 m por serie
                self::ex('Sled Push', '25s', match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 5, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // F3 ensaya los 100 m de test (2×24/2×16 kg)
                self::ex('Farmers Walk', match ($phase) {
                    'base', 'progression' => '30s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Umbral largo: el último intervalo al mismo ritmo que el primero
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '200s', 'progression' => '300s', 'intensification' => '400s', 'deload' => '200s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // El 2.000 m continuo de F3 es el umbral más largo del nivel
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '200s', 'progression' => '300s', 'intensification' => '400s', 'deload' => '200s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 1, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 150),
            'session_c' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Primera wall ball a 6 kg: calienta el gesto del circuito
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '10', 'progression' => '15', 'intensification' => '20', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Progresión al peso sandbag Open (goblet 20/12 kg por simetría de implementos)
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Z2 con bloques Z3: cambiar de ritmo sin fundirse (§4)
                self::ex('Sprint', match ($phase) {
                    'base' => '2400s', 'progression' => '2700s', 'intensification' => '2700s', 'deload' => '1800s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Carga de la PUERTA (2×16/2×12 kg), no la del test; F3 ensaya los 100 m exactos del re-test
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Aterrizaje silencioso siempre (§2); el BBJ de 80 m del L6 se construye aquí
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '6', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 5 — FUERZA_SKILL (pares con fatiga: push→pull, farmers→lunge, row→ski)
    // ========================================================================

    private static function fuerzaSkillL5(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // 1 km Z3→Z4; F3 acerca el ritmo al Z4 del simulacro
                self::ex('Sprint', match ($phase) {
                    'base', 'progression' => '270s', 'intensification' => '240s', 'deload' => '270s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Par push→pull: transición caminando, sin descanso extra (§1 en ambos gestos)
                self::ex('Sled Push', '25s', match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Sled Pull', '25s', match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Segundo par: agarre → patrón unilateral (la estación 7 de la carrera)
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression', 'intensification' => '30s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base' => '8/lado', 'progression', 'intensification' => '10/lado', 'deload' => '8/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Par row→ski: cambio de máquina rápido, sin descanso extra; remadas largas (§3)
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '110s', 'progression' => '150s', 'intensification' => '200s', 'deload' => '100s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '55s', 'progression', 'intensification' => '100s', 'deload' => '55s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // SkiErg de umbral suelto: el 2.000 m de F3 mantiene el umbral de N4
                self::ex('SkiErg Sprint', match ($phase) {
                    'base', 'progression' => '200s', 'intensification' => '400s', 'deload' => '200s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification', 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico del simulacro: toca las estaciones del L5 con dosis mínimas
                self::ex('Wall Ball Shot', '10'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Sled Push', '15s'),
                self::ex('Broad Jump Burpee', '3'),
                self::ex('Farmers Walk', '15s'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '12', 'progression' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Mantiene el patrón de aterrizaje (§2) entre simulacros
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '5', 'progression' => '5', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Z2 con bloques Z4: ritmo de competición (§4); en F3 volumen sin novedad
                self::ex('Sprint', match ($phase) {
                    'base', 'progression' => '2700s', 'intensification' => '3000s', 'deload' => '2100s',
                    default => throw new \InvalidArgumentException(),
                }),
                // F3 ensaya los 200 m completos de la estación 6 del simulacro
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '60s', 'progression' => '90s', 'intensification' => '120s', 'deload' => '35s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Trabajo técnico, no fatiga nueva
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base' => '10/lado', 'progression' => '12/lado', 'intensification' => '12/lado', 'deload' => '8/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 6 — FUERZA_SKILL (trenes de 3 estaciones con fatiga acumulada)
    // ========================================================================

    private static function fuerzaSkillL6(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // F3 mantiene el Z4 sin subir volumen: el simulacro está cerca
                self::ex('Sprint', match ($phase) {
                    'base', 'progression' => '270s', 'intensification' => '240s', 'deload' => '270s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Tren 1 (estaciones 1-3): transiciones caminando, sin sentarse; §1 en el sled
                self::ex('SkiErg Sprint', match ($phase) {
                    'base', 'intensification' => '55s', 'progression' => '100s', 'deload' => '55s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Sled Push', '25s', match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Sled Pull', '25s', match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Tren 2 (estaciones 6-8): la wall ball llega con los hombros ya cargados (§3)
                self::ex('Farmers Walk', match ($phase) {
                    'base', 'intensification' => '25s', 'progression' => '30s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base', 'intensification' => '8/lado', 'progression' => '10/lado', 'deload' => '8/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Wall Ball Shot', match ($phase) {
                    'base', 'intensification' => '10', 'progression' => '15', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Acumula 3.000-4.000 m; en F3 se mantiene el volumen de F1
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '300s', 'progression' => '400s', 'intensification' => '300s', 'deload' => '200s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // Entre SkiErg y Row suman 5.000 m en F2
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '200s', 'progression', 'intensification' => '300s', 'deload' => '200s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 2, 'intensification', 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 180),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 8 estaciones con dosis mínimas
                self::ex('Wall Ball Shot', '10'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Sled Push', '15s'),
                self::ex('Sled Pull', '15s'),
                self::ex('Broad Jump Burpee', '3'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Kettlebell Goblet Lunge', '5/lado'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '15', 'progression' => '20', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Mantiene el aterrizaje silencioso (§2) entre simulacros
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '5', 'progression' => '6', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // En F3 baja el volumen y la novedad — el simulacro está cerca
                self::ex('Sprint', match ($phase) {
                    'base' => '3000s', 'progression' => '3300s', 'intensification' => '2700s', 'deload' => '2400s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Ensayos exactos de los re-tests del test (en F4 van en el test)
                ...('deload' === $phase ? [] : [match ($phase) {
                    'base' => self::ex('Sprint', '300s'), // HY1: 1 km sin caminar tras el run
                    'progression' => self::ex('Wall Ball Shot', '30'), // HY2: 30 WB 4 kg sin romper
                    'intensification' => self::ex('Burpee', '20'), // HY3: 20 burpees completos <2:00
                    default => throw new \InvalidArgumentException(),
                }]),
                // Mantiene el agarre de competición sin fatiga nueva
                self::ex('Farmer Walk', match ($phase) {
                    'base', 'progression' => '30s', 'intensification' => '25s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 4 — WOD (transición estación→run, una estación a la vez)
    // ========================================================================

    private static function wodL4(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sled Push', '25s'),
                    self::ex('Sprint', '90s'),
                ]) + ['description' => '3 rondas: Sled Push 12,5 m (75% de la carga del bloque) + Sprint 400 m. 90 s entre rondas; primer run saliendo del trineo.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Farmers Walk', '25s'),
                    self::ex('Sprint', '90s'),
                ]) + ['description' => '4 rondas: Farmers Walk 40 m (2×24/2×16 kg) + Sprint 400 m. 90 s entre rondas; los farmers tras correr llegan en N5 — aquí el orden se invierte a propósito.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sled Push', '25s'),
                    self::ex('Sprint', '120s'),
                ]) + ['description' => '4 rondas: Sled Push 12,5 m (152/102 kg) + Sprint 500 m. "Mantener, no construir": el sled no sube de carga esta fase; el run sí.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Farmers Walk', '20s'),
                    self::ex('Sprint', '70s'),
                ]) + ['description' => '2 rondas suaves: Farmers Walk 30 m ligero + Sprint 300 m. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 45, 10, [
                    self::ex('SkiErg Sprint', '45s'),
                ], 45) + ['description' => '5 × (45 s trabajo / 45 s descanso): SkiErg Sprint a umbral. Acumula 1.000-1.250 m; transiciones rápidas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Mountain Climber', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => "AMRAP 12': 8 Kettlebell Swing Russian (24/16 kg) + 8 Mountain Climber + 8 Sit-up. Umbral sostenido; control lumbar (§5). Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '90s'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '4 rondas: Sprint 400 m + Wall Ball Shot ×10 (6 kg). 90 s entre rondas; primera wall ball Open en fatiga.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '90s'),
                    self::ex('Wall Ball Shot', '15'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '4 rondas: Sprint 400 m + Wall Ball Shot ×15 (6 kg) + Burpee ×5. 90 s entre rondas; la dosis WB sube hacia la del test (×30).'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 5, 90, 20, [
                    self::ex('Sprint', '90s'),
                    self::ex('Kettlebell Goblet Lunge', '10/lado'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '5 rondas: Sprint 400 m + Kettlebell Goblet Lunge 10/lado (20/12 kg) + Burpee ×5. La estación del día es el lunge Open; aterrizajes silenciosos (§2).'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '70s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Wall Ball Shot ×8 (6 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Dumbbell Lunge', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 10 Dumbbell Lunge (ligero) + 10 Mountain Climber + 30 s Hollow Body Hold. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 7, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Step Up', '8'),
                    self::ex('Push Up', '8'),
                ]) + ['description' => "AMRAP 15': 8 Kettlebell Swing Russian (16/12 kg) + 8 Step Up + 8 Push Up. Control de ritmo: la última ronda como la primera. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 5 — WOD (pares y triples con Roxzone cronometrado)
    // ========================================================================

    private static function wodL5(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Sled Push', '25s'),
                    self::ex('Sled Pull', '25s'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Sled Push 12,5 m (90/60 kg) + Sled Pull 12,5 m (60/45 kg). 2 min entre rondas; primer par completo saliendo de correr.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Farmers Walk', '30s'),
                    self::ex('Kettlebell Goblet Lunge', '10/lado'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Farmers Walk 50 m (2×18/2×12 kg) + Kettlebell Goblet Lunge 10/lado (16/12 kg). 2 min entre rondas; transiciones cronometradas y anotadas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Sled Push', '25s'),
                ]) + ['description' => '2 rondas: Sprint 1 km + Sled Push 12,5 m (114/76 kg). "Mantener, no construir": volumen corto, nada de carga nueva antes del simulacro.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Farmers Walk', '20s'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Farmers Walk 30 m ligero. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 10, [
                    self::ex('SkiErg Sprint', '60s'),
                ], 60) + ['description' => "6 × (1' trabajo / 1' descanso): SkiErg Sprint a umbral. Acumula 1.500 m; el minuto de descanso se usa respirando, no sentado."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 7, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Mountain Climber', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => "AMRAP 15': 8 Kettlebell Swing Russian (24/16 kg) + 8 Mountain Climber + 8 Sit-up. El AMRAP más largo del nivel; última ronda como la primera. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Wall Ball Shot', '15'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Wall Ball Shot ×15 (6 kg) + Burpee ×8. 2 min entre rondas; transiciones cronometradas y anotadas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Sled Push', '25s'),
                    self::ex('Sled Pull', '25s'),
                    self::ex('Broad Jump Burpee', '45s'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Sled Push 12,5 m (114/76 kg) + Sled Pull 12,5 m (77/58 kg) + Broad Jump Burpee 30 m. El triple más largo antes del simulacro; §1 y §2 en cada gesto.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Hyrox Simulacro L5',
                    'benchmarkNote' => 'Benchmark embebido: Hyrox Simulacro L5 en modalidad RX de la ficha (75% Open): 6×(run 1 km + estación: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 60 m, Row 1 km, Farmers 200 m). Se ejecuta con el paso benchmark del player (stopwatch + cap 60:00); la marca alimenta el test del nivel. Empezar fresco: nada de sled pesado en la sesión anterior.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Wall Ball Shot ×8 (6 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Dumbbell Lunge', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 10 Dumbbell Lunge (ligero) + 10 Mountain Climber + 30 s Hollow Body Hold. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 7, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Step Up', '8'),
                    self::ex('Push Up', '8'),
                ]) + ['description' => "AMRAP 15': 8 Kettlebell Swing Russian (16/12 kg) + 8 Step Up + 8 Push Up. Mantener, no construir: a ritmo moderado. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 6 — WOD (trenes de 3 estaciones y benchmark embebido L6)
    // ========================================================================

    private static function wodL6(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('SkiErg Sprint', '55s'),
                    self::ex('Sled Push', '25s'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Tren 1 reducido (SkiErg 250 m + Sled Push 12,5 m, 90/60 kg). 2 min entre rondas; primer kilómetro saliendo de las estaciones 1-3.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Farmers Walk', '30s'),
                    self::ex('Wall Ball Shot', '15'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Tren 2 reducido (Farmers Walk 50 m + Wall Ball Shot ×15). 2 min entre rondas; el run tras el tren pesado es la estampa del km 7 de carrera.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Sled Pull', '25s'),
                ]) + ['description' => '2 rondas: Sprint 1 km + Sled Pull 12,5 m (67/50 kg). "Mantener, no construir": volumen mínimo antes del simulacro.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Farmers Walk', '20s'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Farmers Walk 30 m ligero. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 10, [
                    self::ex('SkiErg Sprint', '60s'),
                ], 60) + ['description' => "6 × (1' trabajo / 1' descanso): SkiErg Sprint a umbral. Acumula 1.500 m; transiciones respirando de pie."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Mountain Climber', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => "AMRAP 12': 8 Kettlebell Swing Russian (24/16 kg) + 8 Mountain Climber + 8 Sit-up. Volumen contenido; el simulacro está cerca. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('SkiErg Sprint', '100s'),
                    self::ex('Sled Push', '25s'),
                    self::ex('Sled Pull', '25s'),
                ]) + ['description' => '3 rondas: Sprint 1 km + SkiErg 500 m + Sled Push 12,5 m (100/68 kg) + Sled Pull 12,5 m (67/50 kg). 2 min entre rondas; el tren de la primera mitad con run.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Kettlebell Goblet Lunge', '30s'),
                    self::ex('Wall Ball Shot', '20'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Farmers Walk 100 m (2×16/2×12 kg) + Kettlebell Goblet Lunge 20 m (16/12 kg) + Wall Ball Shot ×20 (6/4 kg). 2 min entre rondas; el tren de la segunda mitad con run; la wall ball llega fundida — series de 10 (§3).'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Hyrox Simulacro L6',
                    'benchmarkNote' => 'Benchmark embebido: Hyrox Simulacro L6 en modalidad RX de la ficha (~65% Open): 8×(run 1 km + estación en orden oficial: SkiErg 1 km, Sled Push 50 m, Sled Pull 50 m, BBJ 80 m, Row 1 km, Farmers 200 m, Goblet Lunge 100 m, WB ×75). Se ejecuta con el paso benchmark del player (stopwatch + cap 90:00); la marca alimenta el test. Empezar fresco: las sesiones A/B/D de esta fase ya redujeron carga.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Wall Ball Shot', '8'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Wall Ball Shot ×8 (6 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Dumbbell Lunge', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 10 Dumbbell Lunge (ligero) + 10 Mountain Climber + 30 s Hollow Body Hold. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Mantener, no construir: el simulacro está cerca.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 7 — FUERZA_SKILL (estaciones Open con split objetivo)
    // ========================================================================

    private static function fuerzaSkillL7(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // F1 aprende el ritmo objetivo; F2 entrena el ritmo negativo; F3 repite el ritmo exacto
                self::ex('Sprint', '285s', match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 50 m cronometrados (152/102 kg); split M 2:30 / F 3:00; series largas: es potencia, no metcon (§1)
                self::ex('Sled Push', match ($phase) {
                    'base', 'progression', 'intensification' => '150s', 'deload' => '75s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification', 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Split M 4:00 / F 4:45 por 100 m; el lunge decide la segunda mitad de la carrera
                self::ex('Kettlebell Goblet Lunge', match ($phase) {
                    'base' => '30s', 'progression', 'intensification' => '45s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // F2-F3 ensayan los 100 m exactos del re-test HY4 a carga Open
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Split objetivo 4:30 M: salida controlada, remada larga, cierre fuerte
                self::ex('SkiErg Sprint', '270s', match ($phase) {
                    'base' => 2, 'progression' => 3, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // Split 4:00 M: la estación 5 llega con 5 km en las piernas
                self::ex('Rowing Machine Sprint', '240s', match ($phase) {
                    'base', 'progression' => 2, 'intensification', 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 150),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 8 estaciones con dosis mínimas
                self::ex('Wall Ball Shot', '15'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Sled Push', '15s'),
                self::ex('Sled Pull', '15s'),
                self::ex('Broad Jump Burpee', '3'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Kettlebell Goblet Lunge', '5/lado'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // F1-F2 acumulan hacia las ×100 (objetivo: 4 series de 25)
                self::ex('Wall Ball Shot', match ($phase) {
                    'base' => '20', 'progression' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Bloques de 10 m con aterrizaje válido (§2); el BBJ de 80 m son 8 bloques mentales
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '5', 'progression' => '6', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Z2 con bloques a ritmo de carrera; en F3 baja el volumen — el L7 está cerca
                self::ex('Sprint', match ($phase) {
                    'base' => '3000s', 'progression' => '3300s', 'intensification' => '2700s', 'deload' => '2400s',
                    default => throw new \InvalidArgumentException(),
                }),
                // El re-test entero: 100 m a carga Open sin soltar; F2 exige el objetivo de 2:00
                self::ex('Farmers Walk', match ($phase) {
                    'base', 'progression', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Mantenimiento del core anti-extensión para los runs tardíos
                self::ex('Plank', match ($phase) {
                    'base', 'progression' => '60s', 'intensification' => '60s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification', 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 8 — FUERZA_SKILL (ritmo de carrera: splits afinados, no fuerza)
    // ========================================================================

    private static function fuerzaSkillL8(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // El ADN del L8: 8 km a 4:15-4:30; F2 entrena el ritmo negativo
                self::ex('Sprint', '270s', match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 4 series de 25 con descansos de 10-15 s; objetivo sub-5:00 en carrera (§3)
                self::ex('Wall Ball Shot', match ($phase) {
                    'base', 'progression' => '25', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Volumen bajo, split exacto (2:30 M); variante Pro en nota: 202/152 kg si compites Pro
                self::ex('Sled Push', match ($phase) {
                    'base', 'progression' => '150s', 'intensification', 'deload' => '75s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Split afilado del L8: 10-15 s por debajo del L7 (4:20 M)
                self::ex('SkiErg Sprint', '260s', match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // La estación 5 con 5 km en las piernas: salida controlada desde el primer drive (3:50 M)
                self::ex('Rowing Machine Sprint', '230s', match ($phase) {
                    'base', 'progression' => 2, 'intensification', 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 8 estaciones con dosis mínimas
                self::ex('Wall Ball Shot', '15'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Sled Push', '15s'),
                self::ex('Sled Pull', '15s'),
                self::ex('Broad Jump Burpee', '3'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Kettlebell Goblet Lunge', '5/lado'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // F1-F2 afilan las series de 25 del ×100 (F2 sub-1:20 por serie)
                self::ex('Wall Ball Shot', match ($phase) {
                    'base', 'progression' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Bloques de 10 m con aterrizaje válido (§2); en F2 se cronometra el bloque
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '6', 'progression' => '8', 'deload' => '4',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Mantenimiento aeróbico del nivel más rápido del programa; en F3 baja el volumen
                self::ex('Sprint', match ($phase) {
                    'base' => '3000s', 'progression' => '3300s', 'intensification' => '2700s', 'deload' => '2400s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Los 100 m del PFT de cierre; nota Pro: 2×32/2×24 kg
                self::ex('Farmers Walk', match ($phase) {
                    'base', 'progression', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // El bloque de 10 m cronometrado extrapola el split de 80 m
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base', 'progression' => '35s', 'intensification' => '35s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 7 — WOD (run→estación a ritmo con transición cronometrada)
    // ========================================================================

    private static function wodL7(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Sled Push', '25s'),
                ]) + ['description' => '4 rondas: Sprint 1 km + Sled Push 12,5 m (152/102 kg). 2 min entre rondas; primer contacto con el Open en fatiga; transiciones de 20-30 s anotadas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 120, 20, [
                    self::ex('Sprint', '300s'),
                    self::ex('Wall Ball Shot', '25'),
                ]) + ['description' => '4 rondas: Sprint 1 km + Wall Ball Shot ×25 (6 kg) + transición cronometrada. 2 min entre rondas; series de 25 dentro del plan de ×100 (4×25); el run sale a ritmo, no a sprint (§4).'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Sprint', '285s'),
                    self::ex('Farmers Walk', '60s'),
                ]) + ['description' => '2 rondas: Sprint 1 km a ritmo exacto + Farmers Walk 100 m (2×24/2×16 kg). "Afilar, no construir": el par exacto del km 6-7 de la carrera, una sola vez bien hecho.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Wall Ball Shot ×10 (6 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('SkiErg Sprint', '270s'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 × (1.000 m SkiErg Sprint a ritmo objetivo + 10 Burpee): SkiErg con split exacto y salida inmediata de burpees. Ensayo de la estación 1 con fatiga de transición; §2 en cada burpee.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 10, [
                    self::ex('SkiErg Sprint', '60s'),
                ], 60) + ['description' => "6 × (1' trabajo / 1' descanso): SkiErg Sprint a umbral. Afilar: 1.500 m acumulados a ritmo, sin novedad."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '285s'),
                    self::ex('SkiErg Sprint', '100s'),
                    self::ex('Sled Push', '50s'),
                    self::ex('Sled Pull', '50s'),
                ]) + ['description' => '3 rondas: Sprint 1 km a ritmo + SkiErg 500 m (split) + Sled Push 25 m (152/102 kg) + Sled Pull 25 m (103/78 kg). 2 min entre rondas; el tren 1-3 con splits y transiciones anotadas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '285s'),
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Kettlebell Goblet Lunge', '45s'),
                    self::ex('Wall Ball Shot', '25'),
                ]) + ['description' => '3 rondas: Sprint 1 km a ritmo + Farmers Walk 100 m (2×24/2×16 kg) + Kettlebell Goblet Lunge 30 m (20/12 kg) + Wall Ball Shot ×25 (6 kg). 2 min entre rondas; el tren 6-8 con splits; la WB llega fundida — 4×25 planificadas (§3).'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Hyrox Simulacro L7',
                    'benchmarkNote' => 'Benchmark embebido: Hyrox Simulacro L7 en modalidad RX (8× run 1 km + estación en orden oficial, 100% Open). Stopwatch + cap 1:45:00; la marca alimenta el test. Plan: primeros 4 runs 5 s más lentos que el objetivo (ritmo negativo), Roxzone de 20-30 s, agua en cada transición.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Wall Ball Shot ×10 (6 kg). Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Dumbbell Lunge', '10'),
                    self::ex('Mountain Climber', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 10 Dumbbell Lunge (ligero) + 10 Mountain Climber + 30 s Hollow Body Hold. 60 s entre rondas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Afilar, no construir: el L7 está cerca.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 8 — WOD (repeticiones de simulacro a ritmo de competición)
    // ========================================================================

    private static function wodL8(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '270s'),
                    self::ex('Sled Push', '150s'),
                    self::ex('Sprint', '90s'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Sled Push 50 m (152/102 kg, split) + Sprint 400 m. 2 min entre rondas; el km 2-3 de la carrera con la estación 2 por medio.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '270s'),
                    self::ex('Wall Ball Shot', '25'),
                    self::ex('Sprint', '90s'),
                ]) + ['description' => '3 rondas: Sprint 1 km + Wall Ball Shot ×25 + Sprint 400 m (más rápido que el primero). Ritmo negativo explícito: el 400 m de cierre se corre más rápido que el del inicio.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Sprint', '270s'),
                    self::ex('Farmers Walk', '120s'),
                ]) + ['description' => '2 rondas: Sprint 1 km a ritmo exacto + Farmers Walk 200 m (2×24/2×16 kg, split 3:00). "Afilar, no construir": el par exacto del km 6 con split; nota Pro: farmers 2×32/2×24 kg.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Wall Ball Shot ×10. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('SkiErg Sprint', '260s'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 × (1.000 m SkiErg Sprint a split + 10 Burpee) y, para cerrar, 1.000 m Rowing Machine Sprint a split solo. Ensayo de estaciones 1 y 5 con fatiga de transición; §2 en los burpees.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 10, [
                    self::ex('SkiErg Sprint', '60s'),
                ], 60) + ['description' => "4 × (1' trabajo / 1' descanso): SkiErg Sprint a umbral. Afilar: volumen mínimo, ritmo exacto."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 20, [
                    self::ex('Sprint', '270s'),
                    self::ex('SkiErg Sprint', '270s'),
                    self::ex('Sprint', '270s'),
                    self::ex('Sled Push', '150s'),
                    self::ex('Sprint', '270s'),
                    self::ex('Sled Pull', '150s'),
                    self::ex('Sprint', '270s'),
                    self::ex('Broad Jump Burpee', '240s'),
                ]) + ['description' => '"Media carrera": 2×(run 1 km + estación ×4): Sprint 1 km + SkiErg 1 km + Sprint 1 km + Sled Push 50 m + Sprint 1 km + Sled Pull 50 m + Sprint 1 km + Broad Jump Burpee 80 m, y se repite el bloque. 3 min entre bloques; primera repetición completa del formato con splits; logro "Repetición de carrera" si se completa en ambas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 20, [
                    self::ex('Sprint', '270s'),
                    self::ex('Rowing Machine Sprint', '240s'),
                    self::ex('Sprint', '270s'),
                    self::ex('Farmers Walk', '120s'),
                    self::ex('Sprint', '270s'),
                    self::ex('Kettlebell Goblet Lunge', '150s'),
                    self::ex('Sprint', '270s'),
                    self::ex('Wall Ball Shot', '100'),
                ]) + ['description' => '"Segunda mitad": 2×(run 1 km + estación ×4): Sprint 1 km + Row 1 km + Sprint 1 km + Farmers Walk 200 m + Sprint 1 km + Kettlebell Goblet Lunge 100 m + Sprint 1 km + Wall Ball Shot ×100. 3 min entre bloques; la mitad que decide la carrera; WB ×100 con series de 25 (§3).'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'Hyrox Simulacro L8',
                    'benchmarkNote' => 'Benchmark embebido: Hyrox Simulacro L8 en modalidad RX (8× run 1 km + estación en orden oficial, 100% Open) a ritmo de competición. Stopwatch + cap 1:30:00; la marca alimenta el test. Plan: splits por estación escritos, ritmo negativo, Roxzone de 15-20 s, hidratación en cada transición; nota Pro: si compites Pro, esta sesión es tu simulacro con pesos Pro (202/152, 153/103, 2×32/2×24, goblet 28/20, WB 9/6 kg ×100).',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '200s'),
                    self::ex('Wall Ball Shot', '10'),
                ]) + ['description' => '2 rondas suaves: Sprint 800 m + Wall Ball Shot ×10. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_d' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Step Up', '10'),
                    self::ex('Kettlebell Swing Russian', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '3 rondas: 10 Step Up + 10 Kettlebell Swing Russian (16/12 kg) + 10 Sit-up. 60-90 s entre rondas.'],
                'progression' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'HYROX PFT',
                    'benchmarkNote' => 'HYROX PFT en modalidad RX (ficha del lote 1): run 1 km → burpees ×50 → farmers 100 m → WB ×50 → row 1 km → sled push 50 m → SkiErg 1 km → BBJ 50 m. Primera ejecución del PFT a pesos Open con marca registrada; referencia del cierre.',
                ],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Afilar, no construir: el L8 está cerca.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }
}
