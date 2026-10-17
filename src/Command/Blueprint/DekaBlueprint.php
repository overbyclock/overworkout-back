<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\TrainingRound;

/**
 * Blueprint del programa DEKA Mastery v1 (niveles 1-3 de 8).
 *
 * Contenido transcrito literalmente de docs/detalle_deka_niveles_01-03.md.
 * Estructura: 4 sesiones por fase (A=Run+Zonas, B=Ergómetros,
 * C=Simulacro/Circuito de zonas, D=Aeróbico+Movilidad).
 * Fases: base (55%), progression (80%), intensification (95%), deload (50%).
 *
 * Toda sesión tiene 5 bloques tipados (TrainingRound::BLOCK_*) en orden fijo:
 * preparación (Bloque 0, movilidad fluida sin descansos de fuerza) →
 * fuerza_skill → wod → postural (prehab rotado por sesión) → descarga.
 * restAfterBlock = 60 s en N1-N3 (transición entre bloques).
 *
 * Los AMRAP/EMOM se modelan como rondas estimadas para llenar la ventana del
 * formato: la duración sale de TrainingTimeCalculator, nunca se hardcodea.
 * El formato exacto (p. ej. "AMRAP 8'") va en la descripción del bloque.
 * La sesión C de N3 Fase 3 (intensification) es el benchmark embebido
 * 'DEKA Media': el round ES el benchmark (sin configs de ejercicios).
 *
 * Dosis de distancia (spec §5): el campo de dosis lleva el TIEMPO estimado
 * en segundos ('45s' ≈ 160 m del ritmo Mile, '60s' ≈ 250 m de remo o 100 m de
 * farmers, '120s' ≈ 500 m de ergómetro...) y la distancia exacta queda en la
 * descripción del bloque. Las calorías del air bike se modelan como reps
 * (10 cal ≈ reps 10). Sled de "4×12,5 m" se aplana; dosis "/lado" una vez.
 *
 * Omisiones: ninguna — todos los ejercicios citados existen en el catálogo
 * con la grafía usada (verificados contra SELECT name FROM exercises, 17-oct-2026;
 * doc §"Pendientes de catálogo"). El "ankle rock" se cubre con Calf Stretch Wall +
 * Bent-Knee Soleus Stretch + Calf Raise on Step + Step Up controlado (nota del doc).
 */
class DekaBlueprint
{
    public static function getLevelMeta(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => [
                'name' => 'Nivel 1: Base DEKA',
                'title' => 'Base DEKA — Técnica de las 10 zonas sin carga',
                'description' => 'Enseñar la técnica de las 10 zonas al peso corporal o con carga mínima (reverse lunge al peso corporal, box step overs bajas, burpees completos, farmers muy ligeros, row/ski/bike técnica con intervalos cortos), construir el motor base y acostumbrar a los runs cortos de 100-200 m que preceden cada zona. Aquí no se compite: se aprende el mapa de las 10 zonas.',
                'objective' => 'Técnica de las 10 zonas al peso corporal, ergómetros básicos y runs cortos de 100-200 m; test: ítems de fuerza al peso corporal',
                'difficultyRating' => 1,
                'color' => '#4ade80',
                'requirementsSummary' => 'Sin requisitos — punto de partida desde cero (incl. quien nunca ha hecho una DEKA ni tocado un air bike)',
                'skillFocus' => 'Técnica de las 10 zonas al peso corporal, ergómetros básicos y runs cortos',
            ],
            2 => [
                'name' => 'Nivel 2: Primeras cargas',
                'title' => 'Primeras cargas — El 50% de los pesos entra en las zonas',
                'description' => 'Llevar las zonas al 50% de los pesos de competición (zancada con 2×8/2×6 kg simulando el RAM, farmers 2×12/2×8 kg, medicine ball 4/3 kg, slam ball pesada 10/6 kg, burpee con carga ligera opcional), umbrales de bike (25 cal como referencia de umbral), box overs a caja 20-24" y runs de 160-250 m. El test mezcla ítems de fuerza con las puertas DK1-DK3.',
                'objective' => 'Zonas al 50% de los pesos de competición, umbrales de bike y box overs a caja 24"; puertas DK1-DK3 evaluadas',
                'difficultyRating' => 2,
                'color' => '#22d3ee',
                'requirementsSummary' => 'Mínimos del test N1 (zancada ×20, burpee ×15, box jump ×10, plancha 45 s)',
                'skillFocus' => 'Zonas al 50% de los pesos de competición, umbrales de bike y box overs a caja 24"',
            ],
            3 => [
                'name' => 'Nivel 3: Media DEKA',
                'title' => 'Media DEKA — Cinco zonas a ritmo de carrera',
                'description' => 'Ejecutar el primer simulacro embebido (5 zonas con runs de 160 m, pesos 50%) como test del nivel, entrenar transiciones zona→run con ritmo de Mile, umbrales ergómetros y superar la puerta DK4 (farmers 2×16 kg ×100 m). Al superar N3, el atleta está listo para las zonas al 100% Open y las carreras completas (N4-N6).',
                'objective' => 'Simulacro de media DEKA (DEKA Media) como test, transiciones zona→run a ritmo Mile y umbrales ergómetros; puerta DK4',
                'difficultyRating' => 3,
                'color' => '#facc15',
                'requirementsSummary' => 'Mínimos del test N2 (incl. DK1-DK3 superadas)',
                'skillFocus' => 'Simulacro de media DEKA, transiciones zona→run a ritmo Mile y umbrales ergómetros',
            ],
            4 => [
                'name' => 'Nivel 4: Zonas Open',
                'title' => 'Zonas Open — Cada zona al 100%, una a una',
                'description' => 'Llevar cada una de las 10 zonas a los pesos oficiales por separado como trabajo de fuerza (zancada 2×16/2×10 kg simulando el RAM, farmers 2×27/2×18 kg, slam ball 27/18 kg, burpee con 20/10 kg en nota, box overs caja 24" rítmicas, sled 5×10 m push/pull con peso), con umbrales ergómetros largos (row/ski 500-1.000 m, bike 25-50 cal) y runs de 160-250 m. La lógica del nivel es "una zona a la vez": fuerza pura con series largas; los trenes con fatiga llegan en N5.',
                'objective' => 'Las 10 zonas al 100% de los pesos oficiales por separado y umbrales ergómetros de 500-1.000 m; re-test DK4',
                'difficultyRating' => 4,
                'color' => '#fb923c',
                'requirementsSummary' => 'Mínimos del test N3 (DEKA Media + DK4)',
                'skillFocus' => 'Las 10 zonas al 100% de los pesos oficiales por separado y umbrales ergómetros de 500-1.000 m',
            ],
            5 => [
                'name' => 'Nivel 5: DEKA Strong',
                'title' => 'DEKA Strong — Las 10 zonas como carrera',
                'description' => 'Ejecutar las 10 zonas RX en orden, sin correr, como carrera completa (benchmark DEKA Strong embebido), entrenando zonas en parejas y trenes con fatiga y transiciones entre zonas cronometradas — en Strong la transición es parte del tiempo de carrera y se entrena como tal. El test es el primer benchmark oficial del programa.',
                'objective' => 'Las 10 zonas RX como carrera continua (DEKA Strong) y transiciones entre zonas cronometradas',
                'difficultyRating' => 5,
                'color' => '#f87171',
                'requirementsSummary' => 'Mínimos del test N4 (zonas Open + DK4 re-validada)',
                'skillFocus' => 'Las 10 zonas RX como carrera continua y transiciones entre zonas cronometradas',
            ],
            6 => [
                'name' => 'Nivel 6: DEKA Mile',
                'title' => 'DEKA Mile — Diez zonas, diez veces ciento sesenta metros',
                'description' => 'Ejecutar las 10 zonas RX con 10×160 m como test del nivel (benchmark DEKA Mile embebido), entrenando runs de 160 m a ritmo con zona intercalada (la transición run→zona es la zona de competición), transiciones cronometradas y volumen de carrera subido a ~55-65 min semanales. Tras superar N6 queda la transición al formato Fit (lote 07-08).',
                'objective' => 'DEKA Mile completa como test: zonas RX con 10×160 m a ritmo y transición run→zona cronometrada; re-tests DK1-DK3',
                'difficultyRating' => 6,
                'color' => '#ef4444',
                'requirementsSummary' => 'Mínimos del test N5 (DEKA Strong bajo 24:00)',
                'skillFocus' => 'DEKA Mile completa: zonas RX con 10×160 m a ritmo y transición run→zona cronometrada',
            ],
            7 => [
                'name' => 'Nivel 7: Transición Fit',
                'title' => 'Transición Fit — Cinco kilómetros, sin quemarse',
                'description' => 'Preparar los 5 km de carrera de la DEKA Fit sin quemar al atleta: runs de 500 m a ritmo con 1-2 zonas intercaladas por bloque (volumen progresivo de 2.500 a 4.000 m semanales en las sesiones clave), zonas RX en trenes de 2-3 con fatiga y umbrales ergómetros largos. El test es la DEKA Fit ejecutada en fase de transición (misma ficha, mínimo generoso, runs scaled opcionales).',
                'objective' => 'Transición a DEKA Fit: runs de 500 m a ritmo con zonas intercaladas y volumen progresivo de carrera; re-test DK4',
                'difficultyRating' => 7,
                'color' => '#dc2626',
                'requirementsSummary' => 'Mínimos del test N6 (DEKA Mile + re-tests DK1-DK3)',
                'skillFocus' => 'Transición a DEKA Fit: runs de 500 m a ritmo con zonas intercaladas y volumen progresivo de carrera',
            ],
            8 => [
                'name' => 'Nivel 8: DEKA Fit',
                'title' => 'DEKA Fit — La carrera completa, cierre del programa',
                'description' => 'Ejecutar la DEKA Fit completa (10×500 m + 10 zonas RX) como cierre del programa (benchmark embebido), afilando el ritmo de carrera en las repeticiones de circuito, la estrategia de transición y la gestión del esfuerzo largo. La marca se compara con la de N7 (transición) como cierre narrativo del progreso.',
                'objective' => 'DEKA Fit completa a ritmo de competición como test de cierre: ritmo negativo, transiciones de 3-5 pasos y gestión del esfuerzo largo',
                'difficultyRating' => 8,
                'color' => '#b91c1c',
                'requirementsSummary' => 'Mínimos del test N7 (DEKA Fit en transición + DK4 re-validada)',
                'skillFocus' => 'DEKA Fit completa a ritmo de competición: ritmo negativo, transiciones de 3-5 pasos y gestión del esfuerzo largo',
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
                'session_a' => ['name' => 'Sesión A: Run corto + zonas de fuerza al peso corporal', 'goal' => 'Run Z2 por tiempo + técnica de zancada, caja y farmers sin carga', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros — row, ski y bike técnica', 'goal' => 'Técnica de remada y pedaleo con intervalos cortos + core', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito de zonas', 'goal' => 'Mini-recorridos DEKA: zonas encadenadas con run entre medias', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico Z2 + movilidad', 'goal' => 'Run Z2 continuo (el run largo del nivel) + goblet y plank', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            2 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run + zonas de fuerza al 50%', 'goal' => 'Run Z2 con intervals + zancada, farmers y slam al 50% de los pesos', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales', 'goal' => 'Row/SkiErg umbrales + camino a las 25 cal continuas del bike (DK3)', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito de zonas con carga', 'goal' => 'Zonas 3-4-10 y 7-8-6-10 con fatiga de transición; sin simulacro en N2', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + DK4 sembrada', 'goal' => 'Run Z2 + kilómetro de farmers segmentado hacia los 2×16 kg de DK4', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            3 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run + zonas de fuerza al 50-75%', 'goal' => 'Intervals de 160 m a ritmo Mile + zancada, farmers (camino DK4) y slam', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales', 'goal' => 'Row/SkiErg de 500-750 m a umbral + bike de 20-25 cal', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito de zonas / DEKA Media', 'goal' => 'Zonas 1-3 y 3-4-6-10 con run (F1/F2/F4) o benchmark embebido DEKA Media (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + DK4', 'goal' => 'Run Z2 con 160 m integrados + farmers 2×16 kg (ensayo re-test)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            4 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run + zonas Open de fuerza', 'goal' => 'Intervals 160-250 m + zancada, farmers y slam en progresión a los pesos oficiales', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales largos', 'goal' => 'Row/SkiErg de 500-1.000 m a umbral + bike de 25-50 cal', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito — la zona Open en soledad', 'goal' => 'La zona Open del día con run delante; sin simulacro en N4', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + ensayo DK4', 'goal' => 'Run Z2 con 160 m + farmers con carga histórica de puerta (ensayo del re-test DK4)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            5 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Zonas de fuerza en parejas', 'goal' => 'Parejas zancada→farmers y slam→burpee con transición sin pausa (zonas 1-6 y 8-10)', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros de transición', 'goal' => 'Par Row→SkiErg sin descanso extra + bike de 25 cal acumuladas', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito de zonas / DEKA Strong', 'goal' => 'Zonas 1-4 y 6-10 con transiciones anotadas (F1/F2/F4) o benchmark embebido DEKA Strong (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad', 'goal' => 'Run Z2 + farmers de ida y vuelta (dosis exacta de la zona 6)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            6 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Run 160 m a ritmo + zonas RX intercaladas', 'goal' => '10×160 m a ritmo Mile + pares zancada→row y farmers→bike con run delante', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros a ritmo de carrera', 'goal' => 'Row/SkiErg de 500 m a split de carrera + bike de 25 cal (re-test DK3 en contexto)', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito run→zona / DEKA Mile', 'goal' => 'Primera y segunda mitad del Mile con runs (F1/F2/F4) o benchmark embebido DEKA Mile (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + re-tests DK1-DK3', 'goal' => 'Run Z2 con bloques de ritmo + ensayos exactos de los re-tests DK1-DK3', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            7 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Runs 500 m a ritmo + zonas intercaladas', 'goal' => '500 m a ritmo (ritmo negativo en F2) + trenes zancada→farmers y slam→burpee', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros umbrales largos', 'goal' => 'Row/SkiErg de 750-1.000 m a umbral + bike de 40-50 cal', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Circuito run→zona / DEKA Fit', 'goal' => 'Zonas 1-3 (2.500 m) y 4-7 (4.000 m) con runs (F1/F2/F4) o benchmark embebido DEKA Fit (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + re-test DK4', 'goal' => 'Run Z2 con 500 m integrados + farmers 2×16 kg (ensayo del re-test)', 'sessionType' => 'skill'],
                default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
            },
            8 => match ($sessionKey) {
                'session_a' => ['name' => 'Sesión A: Intervals a ritmo de carrera con zona intercalada', 'goal' => '500 m a ritmo de carrera (2:20, ritmo negativo) + zancada y farmers con split', 'sessionType' => 'strength'],
                'session_b' => ['name' => 'Sesión B: Ergómetros a ritmo objetivo', 'goal' => 'Row/SkiErg de 500 m a split afinado + bike de 50 cal', 'sessionType' => 'skill'],
                'session_c' => ['name' => 'Sesión C: Repeticiones de simulacro / DEKA Fit', 'goal' => 'Primera y segunda mitad del Fit con runs (F1/F2/F4) o benchmark embebido DEKA Fit RX (F3)', 'sessionType' => 'circuit'],
                'session_d' => ['name' => 'Sesión D: Aeróbico + movilidad + cierre', 'goal' => 'Run Z2 con bloques de carrera + farmers y BBJ de mantenimiento', 'sessionType' => 'skill'],
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

        // N1-N3: transición breve entre bloques (60 s), no descanso de recuperación.
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
    // Siembra permanente de las puertas DK1-DK4.
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
        // Tobillo/rodilla (siembra DK1/DK2)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['20s/lado', '20s/lado', '10'],
            'progression' => ['25s/lado', '25s/lado', '12'],
            'intensification' => ['30s/lado', '30s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, foco)
        [$deepSquat, $cossack, $gluteBridge] = match ($phase) {
            'base' => ['20s', '4/lado', '10'],
            'progression' => ['30s', '6/lado', '12'],
            'intensification' => ['40s', '6/lado', '15'],
            'deload' => ['45s', '6/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Caja/tobillo (DK2, foco): Step Up controlado sobre caja baja
        $stepUp = match ($phase) {
            'base' => '6/lado', 'progression' => '8/lado', 'intensification' => '10/lado', 'deload' => '8/lado',
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['10', '10', '20s'],
            'progression' => ['12', '12', '20s'],
            'intensification' => ['15', '12', '25s'],
            'deload' => ['12', '10', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '10',
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
            self::ex('Step Up', $stepUp),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + zonas): prepara piernas para correr y zancada
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Active March', '30s');
            $exercises[] = self::ex('Deep Squat Hold', '20s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Circuito): énfasis en muñeca (burpees) y glúteo medio si hay zancadas
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Clamshell', '10/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L2(string $phase, string $sessionKey): array
    {
        // Tobillo/rodilla (DK1/DK2, foco)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '12'],
            'progression' => ['30s/lado', '30s/lado', '15'],
            'intensification' => ['35s/lado', '35s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, foco)
        [$deepSquat, $cossack, $gluteBridge] = match ($phase) {
            'base' => ['30s', '6/lado', '12'],
            'progression' => ['40s', '8/lado', '15'],
            'intensification' => ['45s', '8/lado', '15'],
            'deload' => ['45s', '6/lado', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Caja/tobillo (DK2, soporte)
        [$stepUp, $boxStepOver] = match ($phase) {
            'base' => ['6/lado', '6'],
            'progression' => ['8/lado', '8'],
            'intensification' => ['10/lado', '8'],
            'deload' => ['8/lado', '6'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '12', '25s'],
            'progression' => ['15', '15', '25s'],
            'intensification' => ['15', '15', '30s'],
            'deload' => ['12', '12', '20s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '10', 'progression' => '12', 'intensification' => '12', 'deload' => '10',
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
            self::ex('Step Up', $stepUp),
            self::ex('Box Step Over', $boxStepOver),
            self::ex('Shoulder Dislocate', $dislocate),
            self::ex('Band Pull-Apart', $pullApart),
            self::ex('Wrist Prayer', $wristPrayer),
            self::ex('Scapular Push Up', $scapPushUp),
        ];

        // Variante A (Run + zonas): ensayo DK1 (zancada continua)
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Reverse Lunge', '20');
        }
        // Variante B (Ergómetros): ensayo DK3
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Assault Bike Sprint', '10');
        }
        // Variante C (Circuito): ensayo DK2 (box overs continuas)
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Box Step Over', '10');
            $exercises[] = self::ex('Wrist Prayer', '20s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L3(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (DEKA Media)
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

        // Tobillo/rodilla (DK1/DK2, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '15'],
            'progression' => ['35s/lado', '35s/lado', '15'],
            'intensification' => ['40s/lado', '40s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['40s', '8/lado'],
            'progression' => ['45s', '8/lado'],
            'intensification' => ['60s', '10/lado'],
            'deload' => ['45s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (DK4, foco nuevo)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base' => ['30s/lado', '12', '15'],
            'progression' => ['30s/lado', '12', '15'],
            'intensification' => ['40s/lado', '12', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '15', '30s'],
            'progression' => ['15', '15', '30s'],
            'intensification' => ['20', '15', '45s'],
            'deload' => ['12', '12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '12', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
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

        // Variante A (Run + zonas): ensayo DK1 (zancada continua ×30) + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Reverse Lunge', '30');
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

    private static function block0L4(string $phase, string $sessionKey): array
    {
        // Tobillo/rodilla (DK1/DK2, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '15'],
            'progression' => ['35s/lado', '35s/lado', '15'],
            'intensification' => ['40s/lado', '40s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['40s', '8/lado'],
            'progression' => ['60s', '8/lado'],
            'intensification' => ['60s', '10/lado'],
            'deload' => ['45s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (DK4, foco: 2×27/2×18 kg en juego)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base', 'progression' => ['30s/lado', '12', '15'],
            'intensification' => ['40s/lado', '12', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base', 'progression' => ['15', '15', '30s'],
            'intensification' => ['20', '15', '45s'],
            'deload' => ['12', '12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '12', 'progression' => '12', 'intensification' => '15', 'deload' => '10',
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

        // Variante A (Run + zonas): activación de agarre + sentadilla antes de la zancada
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Farmer Walk', '15s');
            $exercises[] = self::ex('Deep Squat Hold', '30s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Circuito): énfasis en muñeca (burpees/slams) y tobillo; ensayo de caja rítmica
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L5(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (DEKA Strong)
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

        // Tobillo/rodilla (DK1/DK2, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['30s/lado', '30s/lado', '15'],
            'progression' => ['35s/lado', '35s/lado', '15'],
            'intensification' => ['40s/lado', '40s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['60s', '8/lado'],
            'progression' => ['60s', '10/lado'],
            'intensification' => ['60s', '10/lado'],
            'deload' => ['45s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (DK4, mantenido)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base' => ['30s/lado', '12', '15'],
            'progression' => ['40s/lado', '12', '15'],
            'intensification' => ['40s/lado', '15', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (slam/burpee)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '15', '30s'],
            'progression' => ['15', '15', '45s'],
            'intensification' => ['20', '15', '45s'],
            'deload' => ['12', '12', '30s'],
            default => throw new \InvalidArgumentException(),
        };
        $scapPushUp = match ($phase) {
            'base' => '12', 'progression' => '15', 'intensification' => '15', 'deload' => '10',
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

        // Variante A (Run + zonas): activación de agarre antes del farmers
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Simulacro/circuito): énfasis en muñeca y agarre
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    private static function block0L6(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (DEKA Mile)
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

        // Tobillo/rodilla (DK1/DK2, re-test camino)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['35s/lado', '35s/lado', '15'],
            'progression' => ['40s/lado', '40s/lado', '15'],
            'intensification' => ['45s/lado', '45s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, re-test camino)
        [$deepSquat, $cossack] = match ($phase) {
            'base' => ['60s', '10/lado'],
            'progression' => ['60s', '10/lado'],
            'intensification' => ['75s', '10/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (DK4, mantenido)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base' => ['40s/lado', '12', '15'],
            'progression' => ['40s/lado', '15', '15'],
            'intensification' => ['45s/lado', '15', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (DK2/DK3 soporte)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '15', '45s'],
            'progression' => ['20', '15', '45s'],
            'intensification' => ['20', '15', '45s'],
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

        // Variante A (Run + zonas): ensayo DK1 (zancada continua ×30) + activación de agarre
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Reverse Lunge', '30');
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros): ensayo DK3
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Assault Bike Sprint', '10');
        }
        // Variante C (Simulacro/circuito): ensayo DK2 (box overs continuas)
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Box Step Over', '10');
            $exercises[] = self::ex('Wrist Prayer', '20s');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    /**
     * Bloque 0 de N7 y N8 (tablas idénticas en docs/detalle_deka_niveles_07-08.md).
     * Con 5 km de carrera y las 10 zonas en juego, el Bloque 0 es la primera
     * línea de defensa; en N8 también es ritual de carrera.
     */
    private static function block0L7(string $phase, string $sessionKey): array
    {
        // En Fase 3 la sesión C recorta el Bloque 0 al mínimo fluido (DEKA Fit)
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

        // Tobillo/rodilla (DK1/DK2, mantenido)
        [$calf, $soleus, $calfRaise] = match ($phase) {
            'base' => ['35s/lado', '35s/lado', '15'],
            'progression' => ['40s/lado', '40s/lado', '15'],
            'intensification' => ['45s/lado', '45s/lado', '15'],
            'deload' => ['30s/lado', '30s/lado', '10'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadera/zancada (DK1, mantenido)
        [$deepSquat, $cossack] = match ($phase) {
            'base', 'progression' => ['60s', '10/lado'],
            'intensification' => ['75s', '10/lado'],
            'deload' => ['60s', '6/lado'],
            default => throw new \InvalidArgumentException(),
        };
        // Cadena posterior/agarre (DK4, re-test camino/mantenido)
        [$hipFlexor, $rockback, $gluteBridge] = match ($phase) {
            'base' => ['40s/lado', '12', '15'],
            'progression' => ['40s/lado', '15', '15'],
            'intensification' => ['45s/lado', '15', '15'],
            'deload' => ['30s/lado', '10', '12'],
            default => throw new \InvalidArgumentException(),
        };
        // Hombro/muñeca (slam/burpee)
        [$dislocate, $pullApart, $wristPrayer] = match ($phase) {
            'base' => ['15', '15', '45s'],
            'progression' => ['20', '15', '45s'],
            'intensification' => ['20', '15', '45s'],
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

        // Variante A (Run + zonas): activación de agarre antes del farmers
        if ('session_a' === $sessionKey) {
            $exercises[] = self::ex('Farmer Walk', '15s');
        }
        // Variante B (Ergómetros)
        if ('session_b' === $sessionKey) {
            $exercises[] = self::ex('Shoulder Dislocate', '5');
            $exercises[] = self::ex('Diaphragmatic Breathing', '5');
        }
        // Variante C (Simulacro/circuito): énfasis en muñeca y agarre
        if ('session_c' === $sessionKey) {
            $exercises[] = self::ex('Wrist Prayer', '20s');
            $exercises[] = self::ex('Calf Stretch Wall', '30s/lado');
        }

        return self::block(TrainingRound::BLOCK_PREPARACION, 1, 1, 5, $exercises, 5);
    }

    // ========================================================================
    // BLOQUE POSTURAL (prehab — selección dosificada por sesión, nunca eliminado)
    // ========================================================================

    private static function posturalBlock(int $level, string $sessionKey): array
    {
        return self::block(TrainingRound::BLOCK_POSTURAL, 1, 1, 45, self::posturalExercises($level, $sessionKey), 50);
    }

    private static function posturalExercises(int $level, string $sessionKey): array
    {
        return match ($level) {
            1 => match ($sessionKey) {
                'session_a' => [
                    self::ex('Dead Bug', '8/lado', 2),
                    self::ex('Bird Dog', '6/lado', 2),
                    self::ex('Pallof Press', '10/lado', 2),
                ],
                'session_b' => [
                    self::ex('Scapular Push Up', '8', 2),
                    self::ex('Prone Y Raise', '8', 2),
                    self::ex('Face Pull', '12', 2),
                ],
                'session_c' => [
                    self::ex('Dead Bug', '8/lado', 2),
                    self::ex('Bird Dog', '6/lado', 2),
                    self::ex('Plank', '30s', 2),
                ],
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
                    self::ex('Dead Bug', '10/lado', 3),
                    self::ex('Bird Dog', '10/lado', 2),
                    self::ex('Pallof Press', '15/lado', 2),
                ],
                'session_b' => [
                    self::ex('Scapular Push Up', '10', 3),
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
            'session_a' => self::ex('Kneeling Hip Flexor Stretch', '30s/lado'),
            'session_b' => self::ex('Wrist Prayer', '30s'),
            'session_c' => self::ex('Calf Stretch Wall', '30s/lado'),
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
                // Run corto por tiempo, Z2 conversacional
                self::ex('Sprint', match ($phase) {
                    'base' => '480s', 'progression' => '600s', 'intensification' => '720s', 'deload' => '360s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Siembra DK1: rodilla de atrás al suelo, torso alto (§1)
                self::ex('Reverse Lunge', match ($phase) {
                    'base' => '8/lado', 'progression' => '10/lado', 'intensification' => '12/lado', 'deload' => '8/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Siembra DK2: step over fluido; el salto llega cuando esto sea rítmico (§2)
                self::ex('Box Step Over', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Siembra DK4/§3: pasos cortos, postura erguida, agarre firme
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '20s', 'progression' => '25s', 'intensification' => '25s', 'deload' => '15s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Drive con piernas, luego espalda, luego brazos
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '50s', 'progression' => '50s', 'intensification' => '100s', 'deload' => '50s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 3, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                // Remada larga piernas-core-brazos; la zona 5 se construye aquí
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '25s', 'progression' => '25s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
                // Siembra DK3: pedaleo continuo sentado
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '10', 'progression' => '10', 'intensification' => '15', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_c' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // 1 s de pausa abajo; rodilla al suelo (§1)
                self::ex('Reverse Lunge', match ($phase) {
                    'base' => '6/lado', 'progression' => '8/lado', 'intensification' => '10/lado', 'deload' => '6/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Burpee', match ($phase) {
                    'base' => '5', 'progression' => '6', 'intensification' => '8', 'deload' => '5',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // El run largo del nivel; DEKA exige poca carrera pero la exige siempre
                self::ex('Sprint', match ($phase) {
                    'base' => '720s', 'progression' => '900s', 'intensification' => '1080s', 'deload' => '600s',
                    default => throw new \InvalidArgumentException(),
                }),
                // KB 8/6 kg; base de la zancada cargada de N2
                self::ex('Goblet Squat', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Plank', match ($phase) {
                    'base' => '30s', 'progression' => '40s', 'intensification' => '45s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillL2(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Los intervals enseñan los runs 160 m del ritmo Mile
                self::ex('Sprint', match ($phase) {
                    'base' => '480s', 'progression' => '600s', 'intensification' => '720s', 'deload' => '360s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Mancuernas a los lados o en back rack (nota: simula RAM 15/10 kg); rodilla al suelo (§1)
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '10/lado', 'progression' => '12/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // 2×12/2×8 kg; §3: pasos cortos, agarre firme
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '25s', 'progression' => '30s', 'intensification' => '35s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Balón pesado; extensión completa de cadera antes que altura (§4)
                self::ex('Medicine Ball Slam', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // La zona 2 de competición es 500 m — aquí se construye
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '120s', 'progression' => '120s', 'intensification' => '180s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // La zona 5 es 500 m; umbral controlado
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '60s', 'progression' => '60s', 'intensification' => '120s', 'deload' => '60s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 4, 'intensification' => 4, 'deload' => 3,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Ensayo DK2: fluidas, sin doble bote (§2)
                self::ex('Box Jump', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Burpee', match ($phase) {
                    'base' => '6', 'progression' => '8', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 2, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Sprint', match ($phase) {
                    'base' => '900s', 'progression' => '1080s', 'intensification' => '1200s', 'deload' => '720s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Kilómetro de farmers segmentado hacia los 2×16 kg de DK4; §3
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Plank', match ($phase) {
                    'base' => '45s', 'progression' => '50s', 'intensification' => '60s', 'deload' => '40s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    private static function fuerzaSkillL3(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // 160 m con recuperación caminando de vuelta; F2-F3 fijan el ritmo objetivo
                self::ex('Sprint', '45s', match ($phase) {
                    'base' => 6, 'progression' => 8, 'intensification' => 8, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                // La carga sube a 2×10/2×8 kg en F3 (75% del RAM 25/15 kg, nota); §1 siempre
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '12/lado', 'progression' => '15/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // El 2×16 kg entra en F1; F3 ensaya los 100 m exactos del re-test; §3
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // §4: cadera completa, lumbar neutra; la zona 8 se construye aquí
                self::ex('Medicine Ball Slam', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '12', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // La zona 2 es 500 m: F2 la ensaya exacta; umbral sostenible
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '120s', 'progression' => '120s', 'intensification' => '180s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // La zona 5 es 500 m; remada larga hasta el final
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '120s', 'progression' => '120s', 'intensification' => '120s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico del simulacro: toca las 5 zonas con dosis mínimas
                self::ex('Dumbbell Lunge', '6/lado'),
                self::ex('Rowing Machine Sprint', '25s'),
                self::ex('Box Jump', '3'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Burpee', '3'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '8/lado', 'progression' => '10/lado', 'deload' => '8/lado',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Mantiene el patrón de la zona 3 entre simulacros
                self::ex('Box Jump', match ($phase) {
                    'base' => '8', 'progression' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // El volumen aeróbico del nivel con los 160 m del ritmo Mile integrados
                self::ex('Sprint', match ($phase) {
                    'base' => '1080s', 'progression' => '1200s', 'intensification' => '900s', 'deload' => '720s',
                    default => throw new \InvalidArgumentException(),
                }),
                // La carga de la puerta (2×16 kg) desde F1; F3 ensaya los 100 m exactos sin soltar
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Plank', match ($phase) {
                    'base' => '45s', 'progression' => '60s', 'intensification' => '60s', 'deload' => '40s',
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
    // BLOQUE WOD (circuitos de zonas con fatiga de transición / simulacro)
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
                    self::ex('Reverse Lunge', '6/lado'),
                ]) + ['description' => '3 rondas: Sprint 100 m + Reverse Lunge ×6/lado. 60-90 s entre rondas; transición caminando, sin prisa.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '35s'),
                    self::ex('Box Step Over', '8'),
                ]) + ['description' => '4 rondas: Sprint 150 m + Box Step Over ×8. 60 s entre rondas; la caja tras correr es la lógica DEKA.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '50s'),
                    self::ex('Reverse Lunge', '8/lado'),
                    self::ex('Burpee', '4'),
                ]) + ['description' => '4 rondas: Sprint 200 m + Reverse Lunge ×8/lado + Burpee ×4. Ensayo de la zona 1-10 conectadas.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Box Step Over', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Box Step Over ×6. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 15, [
                    self::ex('Sit-up', '10'),
                    self::ex('Glute Bridge', '10'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '3 rondas: 10 Sit-up + 10 Glute Bridge + 30 s Plank. 60 s entre rondas; core básico.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 15, [
                    self::ex('Medicine Ball Slam', '6'),
                    self::ex('Mountain Climber', '8'),
                ]) + ['description' => "AMRAP 8': 6 Medicine Ball Slam (6/4 kg) + 8 Mountain Climber. Primera bola: extensión de cadera completa, lumbar neutra (§4). Rondas estimadas para llenar la ventana del AMRAP."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 30, 15, [
                    self::ex('Assault Bike Sprint', '30s'),
                    self::ex('Mountain Climber', '30s'),
                ], 30) + ['description' => '4 × (30 s trabajo / 30 s descanso): Assault Bike Sprint y Mountain Climber alternando. Umbral suave; el bike con fatiga de transición.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Glute Bridge', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 8 Glute Bridge. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 75, 20, [
                    self::ex('Reverse Lunge', '8/lado'),
                    self::ex('Rowing Machine Sprint', '40s'),
                    self::ex('Box Step Over', '8'),
                ]) + ['description' => '3 rondas: Reverse Lunge ×8/lado + Row 200 m + Box Step Over ×8. 60-90 s entre rondas; zonas 1-3.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Reverse Lunge', '10/lado'),
                    self::ex('Rowing Machine Sprint', '60s'),
                    self::ex('Box Step Over', '10'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '4 rondas: Reverse Lunge ×10/lado + Row 250 m + Box Step Over ×10 + Sit-up ×10. Zonas 1-4; el sit-up con bola llega en N2.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Reverse Lunge', '10/lado'),
                    self::ex('Rowing Machine Sprint', '60s'),
                    self::ex('Box Step Over', '10'),
                    self::ex('Assault Bike Sprint', '15'),
                    self::ex('Burpee', '6'),
                ]) + ['description' => '4 rondas: Reverse Lunge ×10/lado + Row 250 m + Box Step Over ×10 + Assault Bike 15 cal + Burpee ×6. Seis zonas consecutivas; primer recorrido DEKA serio.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Reverse Lunge', '6/lado'),
                    self::ex('Box Step Over', '6'),
                ]) + ['description' => '2 rondas suaves: Reverse Lunge ×6/lado + Box Step Over ×6. Movimiento; el test cierra el nivel.'],
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
                    self::ex('Medicine Ball Slam', '10'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '3 rondas: 10 Step Up (con mancuernas ligeras) + 10 Medicine Ball Slam (6/4 kg) + 30 s Hollow Body Hold. Primer gesto con carga sostenida; control lumbar (§4).'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Step Up', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Movimiento; el test cierra el nivel.'],
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
                    self::ex('Dumbbell Lunge', '8/lado'),
                ]) + ['description' => '3 rondas: Sprint 160 m + Dumbbell Lunge ×8/lado (2×8/2×6 kg). 60-90 s entre rondas; primera zona cargada tras correr.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Farmers Walk', '25s'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Farmers Walk 40 m (2×12/2×8 kg). 60 s entre rondas; §3 en el farmers.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Medicine Ball Slam', '6'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Dumbbell Lunge ×10/lado + Medicine Ball Slam ×6. Zona 1 → zona 8, las dos extremas de la carrera.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Dumbbell Lunge', '6/lado'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Dumbbell Lunge ×6/lado. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Sit-up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (16/12 kg) + 8 Sit-up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 15, [
                    self::ex('Medicine Ball Slam', '6'),
                    self::ex('Mountain Climber', '8'),
                ]) + ['description' => "AMRAP 10': 6 Medicine Ball Slam (10/6 kg) + 8 Mountain Climber. §4: cadera completa, lumbar neutra. Rondas estimadas para llenar la ventana del AMRAP."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 20, [
                    self::ex('Assault Bike Sprint', '25'),
                    self::ex('Push Up', '10'),
                ]) + ['description' => '3 × (25 cal de Assault Bike Sprint continuas + 10 Push Up). Bike sin parar los pedales (DK3 entera) en contexto de fatiga; si no llega, reduce a 15+10 cal.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Glute Bridge', '8'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 8 Glute Bridge. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 20, [
                    self::ex('Box Jump', '8'),
                    self::ex('Sit-up', '10'),
                    self::ex('Burpee', '6'),
                ]) + ['description' => '4 rondas: Box Jump ×8 + Sit-up ×10 (MB 4/3 kg, toque) + Burpee ×6. 60-90 s entre rondas; zonas 3-4-10 en reducido.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 20, [
                    self::ex('Dumbbell Lunge', '8/lado'),
                    self::ex('Rowing Machine Sprint', '60s'),
                    self::ex('Box Jump', '8'),
                ]) + ['description' => '4 rondas: Dumbbell Lunge ×8/lado (2×8/2×6 kg) + Row 250 m + Box Jump ×8. Zonas 1-2-3 con fatiga real.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 20, [
                    self::ex('Assault Bike Sprint', '15'),
                    self::ex('Medicine Ball Slam', '8'),
                    self::ex('Farmers Walk', '25s'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => '3 rondas: Assault Bike 15 cal + Medicine Ball Slam ×8 (10/6 kg) + Farmers Walk 40 m (2×12/2×8 kg) + Burpee ×8. Zonas 7-8-6-10: el final de la carrera en serio.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Box Jump', '6'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '2 rondas suaves: Box Jump ×6 + Burpee ×5. Movimiento; el test cierra el nivel.'],
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
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Box Step Over', '8'),
                    self::ex('Push Up', '8'),
                ]) + ['description' => "AMRAP 12': 8 Kettlebell Swing Russian (16/12 kg) + 8 Box Step Over + 8 Push Up. Primer AMRAP con carga sostenida. Rondas estimadas para llenar la ventana del AMRAP."],
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
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Dumbbell Lunge', '8/lado'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Dumbbell Lunge ×8/lado (2×8/2×6 kg). 60-90 s entre rondas; la zona 1 saliendo de correr.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Farmers Walk', '30s'),
                    self::ex('Sprint', '45s'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Farmers Walk 50 m (2×16/2×12 kg) + Sprint 160 m. 90 s entre rondas; zona 6 con run por medio, como en la carrera.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 60, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Medicine Ball Slam', '8'),
                ]) + ['description' => '3 rondas: Sprint 160 m a ritmo exacto + Medicine Ball Slam ×8. "Mantener, no construir": la DEKA Media está cerca y no lleva slam — el volumen se recorta.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Dumbbell Lunge', '6/lado'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Dumbbell Lunge ×6/lado. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 10, [
                    self::ex('Assault Bike Sprint', '60s'),
                ], 60) + ['description' => "5 × (1' trabajo / 1' descanso): Assault Bike Sprint a umbral. Acumula 20-25 cal por intervalo."],
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
                    self::ex('Sprint', '45s'),
                    self::ex('Dumbbell Lunge', '8/lado'),
                    self::ex('Rowing Machine Sprint', '60s'),
                    self::ex('Box Jump', '8'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Dumbbell Lunge ×8/lado (2×8/2×6 kg) + Row 250 m + Box Jump ×8. 90 s entre rondas; las zonas 1-3 con run, el inicio del benchmark.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Box Jump', '10'),
                    self::ex('Farmers Walk', '30s'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Box Jump ×10 + Farmers Walk 50 m (2×12/2×8 kg) + Burpee ×8. 90 s entre rondas; las zonas 3-4-6-10 con transiciones cronometradas.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Media',
                    'benchmarkNote' => 'Benchmark embebido: DEKA Media en modalidad RX (50%): 5×(run 160 m + zona: zancada ×15 con 2×8/2×6 kg, row 250 m, box ×10 a caja 24", farmers 50 m con 2×12/2×8 kg, burpees ×10). Stopwatch + cap 30:00; la marca alimenta el test del nivel. Empezar fresco: nada de carga nueva en las sesiones previas.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Box Jump', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Box Jump ×6. Movimiento; el test cierra el nivel.'],
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
    // NIVEL 4 — FUERZA_SKILL (zonas Open por separado, series largas)
    // ========================================================================

    private static function fuerzaSkillL4(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // El ritmo Mile se consolida; los 250 m de F2 enseñan el cambio de ritmo
                self::ex('Sprint', match ($phase) {
                    'base' => '45s', 'progression' => '70s', 'intensification' => '45s', 'deload' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 8, 'progression' => 6, 'intensification' => 8, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                // Fuerza pura: series largas con descanso completo; rodilla al suelo (§1)
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '12/lado', 'progression' => '15/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // F3 ensaya los 100 m del test; §3: pasos cortos, agarre firme
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // §4: extensión de cadera completa; el dead ball de la zona 8 se construye aquí
                self::ex('Medicine Ball Slam', match ($phase) {
                    'base' => '8', 'progression' => '10', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // La zona 2 es 500 m; el 1.000 m continuo de F3 es el umbral más largo del nivel
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '120s', 'progression' => '180s', 'intensification' => '240s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '120s', 'progression' => '180s', 'intensification' => '240s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 1, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // La zona 7 es 25 cal; el 50 cal continuo de F3 construye la segunda mitad
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '25', 'progression' => '40', 'intensification' => '50', 'deload' => '25',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 3, 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 150),
            'session_c' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Dosis de test (×20) integrada desde F2; §2: sin doble bote
                self::ex('Box Jump', match ($phase) {
                    'base' => '10', 'progression' => '15', 'intensification' => '20', 'deload' => '10',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Toque entre pies; la zona 4 de la carrera
                self::ex('Sit-up', match ($phase) {
                    'base' => '15', 'progression' => '20', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Sprint', match ($phase) {
                    'base' => '1080s', 'progression' => '1200s', 'intensification' => '1200s', 'deload' => '720s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Carga histórica de la puerta (2×16 kg), no la de test; F3 ensaya los 100 m exactos
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Plank', match ($phase) {
                    'base' => '60s', 'progression' => '60s', 'intensification' => '60s', 'deload' => '45s',
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
    // NIVEL 5 — FUERZA_SKILL (parejas de zonas con fatiga y transiciones)
    // ========================================================================

    private static function fuerzaSkillL5(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // Strong no corre, pero el calentamiento corre
                self::ex('Sprint', match ($phase) {
                    'base', 'deload' => '25s', 'progression' => '25s', 'intensification' => '45s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 6, 'progression' => 8, 'intensification' => 6, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                // Par zonas 1-6: piernas → agarre; la transición sin pausa es la lección (§1/§3)
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '15/lado', 'progression' => '20/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '60s', 'intensification' => '30s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Par zonas 8-10: la mitad que decide la Strong; §4 en el slam
                self::ex('Medicine Ball Slam', match ($phase) {
                    'base' => '10', 'progression' => '15', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification', 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Burpee', match ($phase) {
                    'base' => '8', 'progression' => '12', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification', 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 120),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Zonas 2-5: el cambio de máquina sin descanso extra entrena la transición
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base', 'progression', 'intensification' => '120s', 'deload' => '60s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '60s', 'progression', 'intensification' => '120s', 'deload' => '60s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // La zona 7 exacta, acumulada
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '25', 'progression' => '25', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 10 zonas con dosis mínimas
                self::ex('Box Jump', '10'),
                self::ex('Dumbbell Lunge', '5/lado'),
                self::ex('Rowing Machine Sprint', '25s'),
                self::ex('Sit-up', '5'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Assault Bike Sprint', '5'),
                self::ex('Medicine Ball Slam', '5'),
                self::ex('Burpee', '3'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Box Jump', match ($phase) {
                    'base' => '10', 'progression' => '15', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Burpee', match ($phase) {
                    'base' => '8', 'progression' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Sprint', match ($phase) {
                    'base' => '1200s', 'progression' => '1500s', 'intensification' => '1200s', 'deload' => '900s',
                    default => throw new \InvalidArgumentException(),
                }),
                // F2-F3 ensayan la dosis exacta de la zona 6 (10 m i/v ×5); §3
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '60s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
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
    // NIVEL 6 — FUERZA_SKILL (runs 160 m a ritmo con zonas RX intercaladas)
    // ========================================================================

    private static function fuerzaSkillL6(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // El ADN del Mile: 10 runs a ritmo; F3 repite el ritmo exacto sin novedad
                self::ex('Sprint', '45s', match ($phase) {
                    'base' => 8, 'progression' => 10, 'intensification' => 8, 'deload' => 4,
                    default => throw new \InvalidArgumentException(),
                }),
                // Zonas 1-2 con el run delante: la zona se ejecuta con la respiración del run (§1)
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base', 'progression' => '15/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                ...('progression' === $phase ? [self::ex('Rowing Machine Sprint', '120s', 3)] : []),
                // Zonas 6-7: agarre → motor; §3 en el farmers
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '60s', 'progression' => '60s', 'intensification' => '30s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '25', 'progression' => '25', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // La zona 2 con split: la Strong la entrenó, el Mile la cronometra
                self::ex('Rowing Machine Sprint', '120s', match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('SkiErg Sprint', '120s', match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // 25 cal continuas, sentado; re-test DK3 en contexto
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '25', 'progression' => '25', 'intensification' => '25', 'deload' => '15',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 4, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 10 zonas con dosis mínimas
                self::ex('Box Jump', '10'),
                self::ex('Dumbbell Lunge', '5/lado'),
                self::ex('Rowing Machine Sprint', '25s'),
                self::ex('Sit-up', '5'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Assault Bike Sprint', '5'),
                self::ex('Medicine Ball Slam', '5'),
                self::ex('Sled Push', '10s'),
                self::ex('Burpee', '3'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Box Jump', match ($phase) {
                    'base' => '10', 'progression' => '15', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Burpee', match ($phase) {
                    'base' => '8', 'progression' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // En F3 baja el volumen — el Mile está cerca
                self::ex('Sprint', match ($phase) {
                    'base' => '1200s', 'progression' => '1500s', 'intensification' => '900s', 'deload' => '720s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Ensayos exactos de los re-tests del test (en F4 va en el test)
                ...('deload' === $phase ? [] : [match ($phase) {
                    'base' => self::ex('Reverse Lunge', '30'), // DK1 tras el run (la versión más dura)
                    'progression' => self::ex('Box Step Over', '20'), // DK2 frescas
                    'intensification' => self::ex('Assault Bike Sprint', '25'), // DK3 <2:00
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
    // NIVEL 4 — WOD (run→zona con la zona Open del día)
    // ========================================================================

    private static function wodL4(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                ]) + ['description' => '3 rondas: Sprint 160 m + Dumbbell Lunge ×10/lado (2×12/2×8 kg). 90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Farmers Walk', '30s'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Farmers Walk 50 m (2×27/2×18 kg). 90 s entre rondas; el farmers Open saliendo de correr.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Medicine Ball Slam', '8'),
                    self::ex('Burpee', '5'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Medicine Ball Slam ×8 (27/18 kg) + Burpee ×5 (carga opcional). "Mantener, no construir": la carga no sube esta fase.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Dumbbell Lunge', '6/lado'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Dumbbell Lunge ×6/lado. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 10, [
                    self::ex('Assault Bike Sprint', '60s'),
                ], 60) + ['description' => "5 × (1' trabajo / 1' descanso): Assault Bike Sprint a umbral. Acumula 25-30 cal por intervalo."],
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
                    self::ex('Sprint', '45s'),
                    self::ex('Box Jump', '10'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Box Jump ×10 (caja 24"). 90 s entre rondas; la caja en fatiga.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Box Jump', '15'),
                    self::ex('Sit-up', '15'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Box Jump ×15 + Sit-up ×15 (MB). La zona 3-4 del Mile en versión corta.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Sled Push', '30s'),
                    self::ex('Sled Pull', '30s'),
                    self::ex('Burpee', '8'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Sled Push 10 m + Sled Pull 10 m + Burpee ×8. Zona 9-10: el final de la carrera en serio; §1 en el sled.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Box Jump', '8'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Box Jump ×8. Movimiento; el test cierra el nivel.'],
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
                ]) + ['description' => "AMRAP 15': 8 Kettlebell Swing Russian (16/12 kg) + 8 Step Up + 8 Push Up. Control de ritmo de principio a fin. Rondas estimadas para llenar la ventana del AMRAP."],
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
    // NIVEL 5 — WOD (zonas con transiciones cronometradas / DEKA Strong)
    // ========================================================================

    private static function wodL5(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Farmers Walk', '30s'),
                ]) + ['description' => '3 rondas: Sprint 100 m + Dumbbell Lunge ×15/lado (2×16/2×10 kg) + Farmers Walk 50 m. 90 s entre rondas; zonas 1-6 comprimidas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Medicine Ball Slam', '10'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '3 rondas: Sprint 100 m + Medicine Ball Slam ×10 + Burpee ×10. 90 s entre rondas; zonas 8-10 comprimidas.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 90, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                ]) + ['description' => '2 rondas: Sprint 100 m + Dumbbell Lunge ×10/lado. "Mantener, no construir": volumen mínimo antes de la Strong.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Farmers Walk', '20s'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Farmers Walk 30 m ligero. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 5, 60, 10, [
                    self::ex('Assault Bike Sprint', '60s'),
                ], 60) + ['description' => "5 × (1' trabajo / 1' descanso): Assault Bike Sprint a umbral. 25 cal por intervalo; transición de pie, respirando."],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 6, 60, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Mountain Climber', '8'),
                    self::ex('Sit-up', '8'),
                ]) + ['description' => "AMRAP 12': 8 Kettlebell Swing Russian (24/16 kg) + 8 Mountain Climber + 8 Sit-up. Volumen contenido; la Strong está cerca. Rondas estimadas para llenar la ventana del AMRAP."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Box Jump', '15'),
                    self::ex('Sit-up', '20'),
                ]) + ['description' => '3 rondas: Dumbbell Lunge ×15/lado (2×16/2×10 kg) + Row 500 m + Box Jump ×15 + Sit-up ×20 (MB). 2 min entre rondas; zonas 1-4 con transiciones anotadas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Assault Bike Sprint', '25'),
                    self::ex('Medicine Ball Slam', '15'),
                    self::ex('Burpee', '12'),
                ]) + ['description' => '3 rondas: Farmers Walk 10 m ida y vuelta ×5 (2×27/2×18 kg) + Assault Bike 25 cal + Medicine Ball Slam ×15 + Burpee ×12. 2 min entre rondas; zonas 6-10: la segunda mitad de la carrera.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Strong',
                    'benchmarkNote' => 'Benchmark embebido: DEKA Strong en modalidad RX (las 10 zonas del array en orden, sin runs). Stopwatch + cap 30:00; la marca alimenta el test. Plan: transiciones de menos de 30 s, material preparado, salir de cada zona sin pausa.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Box Jump', '6'),
                    self::ex('Sit-up', '10'),
                ]) + ['description' => '2 rondas suaves: Box Jump ×6 + Sit-up ×10. Movimiento; el test cierra el nivel.'],
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
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Mantener, no construir: la Strong está cerca.'],
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
    // NIVEL 6 — WOD (run→zona→run / benchmark embebido DEKA Mile)
    // ========================================================================

    private static function wodL6(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Dumbbell Lunge', '10/lado'),
                    self::ex('Sprint', '45s'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Dumbbell Lunge ×10/lado (2×16/2×10 kg) + Sprint 160 m. 90 s entre rondas; zona 1 entre runs, como en la carrera.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 4, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Box Jump', '15'),
                    self::ex('Sprint', '45s'),
                    self::ex('Sit-up', '20'),
                ]) + ['description' => '4 rondas: Sprint 160 m + Box Jump ×15 + Sprint 160 m + Sit-up ×20 (MB). 90 s entre rondas; zonas 3-4 con runs por medio.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 3, 90, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Farmers Walk', '60s'),
                ]) + ['description' => '3 rondas: Sprint 160 m a ritmo exacto + Farmers Walk 100 m (2×27/2×18 kg). "Mantener, no construir": el par exacto del km 6, una vez bien hecho.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Dumbbell Lunge', '6/lado'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Dumbbell Lunge ×6/lado. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 × (500 m Rowing Machine Sprint a ritmo + 10 Burpee): row con split exacto y salida inmediata de burpees. Ensayo de la zona 2-10 con fatiga de transición.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 10, [
                    self::ex('Assault Bike Sprint', '60s'),
                ], 60) + ['description' => "4 × (1' trabajo / 1' descanso): Assault Bike Sprint a umbral. Afilar: ritmo exacto, sin novedad."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Sprint', '45s'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Sprint', '45s'),
                    self::ex('Box Jump', '15'),
                ]) + ['description' => '3 rondas: Sprint 160 m + Dumbbell Lunge ×15/lado (2×16/2×10 kg) + Sprint 160 m + Row 500 m + Sprint 160 m + Box Jump ×15. 2 min entre rondas; la primera mitad del Mile (zonas 1-3) con runs.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '45s'),
                    self::ex('Sit-up', '20'),
                    self::ex('Sprint', '45s'),
                    self::ex('SkiErg Sprint', '120s'),
                    self::ex('Sprint', '45s'),
                    self::ex('Farmers Walk', '60s'),
                ]) + ['description' => '3 rondas: Sprint 160 m + Sit-up ×20 (MB) + Sprint 160 m + SkiErg 500 m + Sprint 160 m + Farmers Walk 10 m ida y vuelta ×5. 2 min entre rondas; zonas 4-6: el tramo del farmers de ida y vuelta (§3).'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Mile',
                    'benchmarkNote' => 'Benchmark embebido: DEKA Mile en modalidad RX (10× run 160 m + zona del array ZONAS RX en orden). Stopwatch + cap 60:00; la marca alimenta el test. Plan: runs a 45-50 s, transiciones sub-20 s, salir de cada zona caminando 3 pasos y corriendo.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '25s'),
                    self::ex('Box Jump', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 100 m + Box Jump ×6. Movimiento; el test cierra el nivel.'],
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
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Mantener, no construir: el Mile está cerca.'],
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
    // NIVEL 7 — FUERZA_SKILL (runs 500 m a ritmo + trenes de zonas)
    // ========================================================================

    private static function fuerzaSkillL7(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // El ADN del Fit: 10 runs a ~2:15-2:30; F2 entrena el ritmo negativo
                self::ex('Sprint', match ($phase) {
                    'base', 'progression', 'intensification' => '150s', 'deload' => '150s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 4, 'progression' => 5, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Zonas 1-6 con el 500 m delante: la zona se ejecuta con las piernas del run (§1/§3)
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '15/lado', 'progression' => '15/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '60s', 'intensification' => '30s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression', 'intensification' => 3, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Zonas 8-10: §4 en el slam; el burpee en bloques de 5
                self::ex('Medicine Ball Slam', match ($phase) {
                    'base' => '10', 'progression' => '15', 'intensification' => '10', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('Burpee', match ($phase) {
                    'base' => '10', 'progression' => '12', 'intensification' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // La zona 2 con volumen doble: lo que corre cómodo sale solo en la carrera
                self::ex('Rowing Machine Sprint', match ($phase) {
                    'base' => '180s', 'progression' => '240s', 'intensification' => '180s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // La zona 5 llega con 2,5 km en las piernas en el Fit — aquí se construye esa sensación
                self::ex('SkiErg Sprint', match ($phase) {
                    'base' => '180s', 'progression' => '240s', 'intensification' => '240s', 'deload' => '120s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 2, 'intensification' => 1, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // El bike del Fit se paga con las piernas del run
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '40', 'progression' => '50', 'intensification' => '40', 'deload' => '25',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 150),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 10 zonas con dosis mínimas
                self::ex('Dumbbell Lunge', '8/lado'),
                self::ex('Rowing Machine Sprint', '25s'),
                self::ex('Box Jump', '5'),
                self::ex('Sit-up', '5'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Assault Bike Sprint', '5'),
                self::ex('Medicine Ball Slam', '5'),
                self::ex('Burpee', '3'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '12/lado', 'progression' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Burpee', match ($phase) {
                    'base' => '8', 'progression' => '10', 'deload' => '6',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // En F3 baja el volumen — el Fit está cerca
                self::ex('Sprint', match ($phase) {
                    'base' => '1500s', 'progression' => '1800s', 'intensification' => '1200s', 'deload' => '900s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Carga histórica de la puerta (2×16 kg); F3 ensaya los 100 m exactos del re-test
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '30s', 'progression' => '45s', 'intensification' => '60s', 'deload' => '25s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
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
    // NIVEL 8 — FUERZA_SKILL (ritmo de carrera: splits, no fuerza)
    // ========================================================================

    private static function fuerzaSkillL8(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 90, [
                // El ADN del Fit RX: 10 runs a ~2:15-2:20; F2 entrena el ritmo negativo
                self::ex('Sprint', '140s', match ($phase) {
                    'base' => 5, 'progression' => 6, 'intensification' => 4, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // Volumen bajo, técnica exacta: bloques de 10 en carrera (§1)
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '15/lado', 'progression' => '20/lado', 'intensification' => '15/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
                // La zona 6 con split: pasos cortos, agarre firme (§3)
                self::ex('Farmers Walk', match ($phase) {
                    'base', 'progression', 'intensification' => '60s', 'deload' => '30s',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base', 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_b' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // La zona 2 con split afinado: 10-15 s por debajo de la transición
                self::ex('Rowing Machine Sprint', '120s', match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                self::ex('SkiErg Sprint', '120s', match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 1, 'deload' => 1,
                    default => throw new \InvalidArgumentException(),
                }),
                // El bike del Fit: sentado y continuo
                self::ex('Assault Bike Sprint', match ($phase) {
                    'base' => '50', 'progression' => '50', 'intensification' => '40', 'deload' => '25',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 3, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            'session_c' => 'intensification' === $phase ? self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // Calentamiento específico: toca las 10 zonas con dosis mínimas
                self::ex('Dumbbell Lunge', '10/lado'),
                self::ex('Rowing Machine Sprint', '25s'),
                self::ex('Box Jump', '5'),
                self::ex('Sit-up', '5'),
                self::ex('SkiErg Sprint', '25s'),
                self::ex('Farmers Walk', '15s'),
                self::ex('Assault Bike Sprint', '5'),
                self::ex('Medicine Ball Slam', '5'),
                self::ex('Sled Push', '10s'),
                self::ex('Burpee', '3'),
            ], 60) : self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                self::ex('Dumbbell Lunge', match ($phase) {
                    'base' => '15/lado', 'progression' => '20/lado', 'deload' => '10/lado',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                self::ex('Box Jump', match ($phase) {
                    'base' => '10', 'progression' => '15', 'deload' => '8',
                    default => throw new \InvalidArgumentException(),
                }, 2),
            ], 60),
            'session_d' => self::block(TrainingRound::BLOCK_FUERZA_SKILL, 1, 1, 60, [
                // En F3 baja el volumen — el Fit RX está cerca
                self::ex('Sprint', match ($phase) {
                    'base' => '1500s', 'progression' => '1800s', 'intensification' => '1200s', 'deload' => '900s',
                    default => throw new \InvalidArgumentException(),
                }),
                // Mantiene la zona 6 con split, sin fatiga nueva
                self::ex('Farmers Walk', match ($phase) {
                    'base' => '60s', 'progression' => '60s', 'intensification' => '30s', 'deload' => '20s',
                    default => throw new \InvalidArgumentException(),
                }, 2),
                // Variante explosiva opcional para la zona 10; aterrizaje controlado
                self::ex('Broad Jump Burpee', match ($phase) {
                    'base' => '5', 'progression' => '5', 'intensification' => '5', 'deload' => '3',
                    default => throw new \InvalidArgumentException(),
                }, match ($phase) {
                    'base' => 2, 'progression' => 3, 'intensification' => 2, 'deload' => 2,
                    default => throw new \InvalidArgumentException(),
                }),
            ], 90),
            default => throw new \InvalidArgumentException("Sesión {$sessionKey} no válida"),
        };
    }

    // ========================================================================
    // NIVEL 7 — WOD (run→zona→run / benchmark embebido DEKA Fit transición)
    // ========================================================================

    private static function wodL7(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '150s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Sprint', '150s'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Dumbbell Lunge ×15/lado (2×16/2×10 kg) + Sprint 500 m. 2 min entre rondas; zona 1 entre runs, como en la carrera.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '150s'),
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Sprint', '150s'),
                    self::ex('Assault Bike Sprint', '25'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Farmers Walk 100 m (2×27/2×18 kg) + Sprint 500 m + Assault Bike 25 cal. 2 min entre rondas; zonas 6-7 con runs por medio; §3.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Sprint', '150s'),
                    self::ex('Box Jump', '15'),
                ]) + ['description' => '2 rondas: Sprint 500 m a ritmo exacto + Box Jump ×15. "Afilar, no construir": volumen mínimo, ritmo exacto.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '80s'),
                    self::ex('Dumbbell Lunge', '6/lado'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Dumbbell Lunge ×6/lado. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('SkiErg Sprint', '180s'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 × (750 m SkiErg Sprint a umbral + 10 Burpee): SkiErg con salida inmediata de burpees. Ensayo de la zona 5-10 con fatiga de transición.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 10, [
                    self::ex('Assault Bike Sprint', '60s'),
                ], 60) + ['description' => "4 × (1' trabajo / 1' descanso): Assault Bike Sprint a umbral. Afilar: ritmo exacto, sin novedad."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '150s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Sprint', '150s'),
                    self::ex('Box Jump', '15'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Dumbbell Lunge ×15/lado + Row 500 m + Sprint 500 m + Box Jump ×15. 2 min entre rondas; zonas 1-3 con runs (2.500 m de carrera).'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '150s'),
                    self::ex('Sit-up', '20'),
                    self::ex('SkiErg Sprint', '120s'),
                    self::ex('Sprint', '150s'),
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Sprint', '150s'),
                    self::ex('Assault Bike Sprint', '25'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Sit-up ×20 (MB) + SkiErg 500 m + Sprint 500 m + Farmers Walk 10 m i/v ×5 + Sprint 500 m + Assault Bike 25 cal. 2 min entre rondas; zonas 4-7 con runs (4.000 m acumulados en la sesión).'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Fit',
                    'benchmarkNote' => 'Benchmark embebido: DEKA Fit (ficha del lote 2) en modalidad elegida: RX o scaled con runs 250-400 m (la modalidad se registra en el intento). Stopwatch + cap 90:00; la marca alimenta el test. Plan: ritmo negativo, transiciones de 3-5 pasos, hidratación en los runs pares.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '80s'),
                    self::ex('Box Jump', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Box Jump ×6. Movimiento; el test cierra el nivel.'],
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
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Afilar, no construir: el Fit está cerca.'],
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
    // NIVEL 8 — WOD (repeticiones de carrera / benchmark embebido DEKA Fit RX)
    // ========================================================================

    private static function wodL8(string $sessionKey, string $phase): array
    {
        return match ($sessionKey) {
            'session_a' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '140s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Sprint', '140s'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Dumbbell Lunge ×15/lado (2×16/2×10 kg) + Sprint 500 m. 2 min entre rondas; la zona 1 entre runs, como en la carrera.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 3, 120, 20, [
                    self::ex('Sprint', '140s'),
                    self::ex('Assault Bike Sprint', '25'),
                    self::ex('Sprint', '140s'),
                ]) + ['description' => '3 rondas: Sprint 500 m + Assault Bike 25 cal + Sprint 500 m (más rápido que el primero). Ritmo negativo explícito: el segundo run se corre más rápido.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Sprint', '140s'),
                    self::ex('Farmers Walk', '60s'),
                ]) + ['description' => '2 rondas: Sprint 500 m a ritmo exacto + Farmers Walk 100 m (2×27/2×18 kg, split). "Afilar, no construir": el par exacto del km 6, una vez bien hecho.'],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '80s'),
                    self::ex('Dumbbell Lunge', '6/lado'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Dumbbell Lunge ×6/lado. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_b' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 4, 75, 15, [
                    self::ex('Kettlebell Swing Russian', '8'),
                    self::ex('Push Up', '8'),
                    self::ex('Hollow Body Hold', '30s'),
                ]) + ['description' => '4 rondas: 8 Kettlebell Swing Russian (24/16 kg) + 8 Push Up + 30 s Hollow Body Hold. 60-90 s entre rondas.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 120, 20, [
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Burpee', '10'),
                ]) + ['description' => '2 × (500 m Rowing Machine Sprint a split + 10 Burpee) y, para cerrar, 500 m SkiErg Sprint a split solo. Ensayo de zonas 2-5-10 con fatiga de transición.'],
                'intensification' => self::block(TrainingRound::BLOCK_WOD, 4, 60, 10, [
                    self::ex('Assault Bike Sprint', '60s'),
                ], 60) + ['description' => "4 × (1' trabajo / 1' descanso): Assault Bike Sprint a umbral. Afilar: volumen mínimo, ritmo exacto."],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 15, [
                    self::ex('Sit-up', '8'),
                    self::ex('Plank', '30s'),
                ]) + ['description' => '2 rondas suaves: 8 Sit-up + 30 s Plank. Movimiento; el test cierra el nivel.'],
                default => throw new \InvalidArgumentException(),
            },
            'session_c' => match ($phase) {
                'base' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 20, [
                    self::ex('Sprint', '140s'),
                    self::ex('Dumbbell Lunge', '15/lado'),
                    self::ex('Sprint', '140s'),
                    self::ex('Rowing Machine Sprint', '120s'),
                    self::ex('Sprint', '140s'),
                    self::ex('Box Jump', '20'),
                    self::ex('Sprint', '140s'),
                    self::ex('Sit-up', '25'),
                    self::ex('Sprint', '140s'),
                    self::ex('SkiErg Sprint', '120s'),
                ]) + ['description' => '"Primera mitad": 2×(run 500 m + zona ×5): Sprint 500 m + Dumbbell Lunge ×15/lado + Sprint 500 m + Row 500 m + Sprint 500 m + Box Jump ×20 + Sprint 500 m + Sit-up ×25 (MB) + Sprint 500 m + SkiErg 500 m, y se repite el bloque. 3 min entre bloques; zonas 1-5 con runs; primera repetición del formato.'],
                'progression' => self::block(TrainingRound::BLOCK_WOD, 2, 180, 20, [
                    self::ex('Sprint', '140s'),
                    self::ex('Farmers Walk', '60s'),
                    self::ex('Sprint', '140s'),
                    self::ex('Assault Bike Sprint', '25'),
                    self::ex('Sprint', '140s'),
                    self::ex('Medicine Ball Slam', '20'),
                    self::ex('Sprint', '140s'),
                    self::ex('Sled Push', '30s'),
                    self::ex('Sled Pull', '30s'),
                    self::ex('Sprint', '140s'),
                    self::ex('Burpee', '20'),
                ]) + ['description' => '"Segunda mitad": 2×(run 500 m + zona ×5): Sprint 500 m + Farmers Walk 100 m + Sprint 500 m + Assault Bike 25 cal + Sprint 500 m + Medicine Ball Slam ×20 + Sprint 500 m + Sled Push 10 m + Sled Pull 10 m + Sprint 500 m + Burpee ×20. 3 min entre bloques; zonas 6-10 con runs.'],
                'intensification' => [
                    'blockType' => TrainingRound::BLOCK_WOD,
                    'benchmark' => 'DEKA Fit',
                    'benchmarkNote' => 'Benchmark embebido: DEKA Fit en modalidad RX (10× run 500 m + zona del array ZONAS RX en orden). Stopwatch + cap 90:00; la marca alimenta el test y cierra el programa. Plan: ritmo negativo escrito, transiciones de 3-5 pasos, series planificadas (lunge 10+10+10, burpee 4×5, box 4×5), hidratación en los runs pares.',
                ],
                'deload' => self::block(TrainingRound::BLOCK_WOD, 2, 75, 20, [
                    self::ex('Sprint', '80s'),
                    self::ex('Box Jump', '6'),
                ]) + ['description' => '2 rondas suaves: Sprint 300 m + Box Jump ×6. Movimiento; el test cierra el nivel.'],
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
                ]) + ['description' => '2 rondas suaves: 8 Step Up + 8 Sit-up. Afilar, no construir: el Fit RX está cerca.'],
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
