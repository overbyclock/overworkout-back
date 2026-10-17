<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

use App\Enum\BenchmarkFormat;

// Benchmarks Hyrox Mastery (lotes niveles 1-8) — docs/detalle_hyrox_niveles_01-03.md,
// docs/detalle_hyrox_niveles_04-06.md y docs/detalle_hyrox_niveles_07-08.md,
// §"Benchmarks del lote" de cada lote (fichas 1-6 de spec §4.1: HYROX PFT,
// Simulacro L3, L5, L6, L7 y L8).
// Idempotente: find-or-update por nombre (BenchmarkSeederHelper::seedBenchmark);
// los benchmarks ya existentes se actualizan in situ por position.

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== OverWorkout — Seeder de benchmarks Hyrox (PFT + Simulacro L3) ===\n\n";

// ------------------------------------------------------------------
// 1. Resolver ejercicios del catálogo (resolución estricta por nombre)
// ------------------------------------------------------------------

$exerciseNames = [
    'Sprint', 'Burpee', 'Farmers Walk', 'Wall Ball Shot',
    'Rowing Machine Sprint', 'Sled Push', 'SkiErg Sprint',
    'Broad Jump Burpee', 'Sled Pull', 'Kettlebell Goblet Lunge',
];

$exercises = [];
$missing = [];

foreach ($exerciseNames as $name) {
    $ex = $em->getRepository(\App\Entity\Exercises::class)->findOneBy(['name' => $name]);
    if (null === $ex) {
        $missing[] = $name;
    } else {
        $exercises[$name] = $ex;
    }
}

if ([] !== $missing) {
    echo "  ❌ Ejercicios no encontrados en el catálogo:\n";
    foreach ($missing as $name) {
        echo "     - {$name}\n";
    }
    echo "\n  Se aborta el seeder para no crear fichas incompletas.\n";
    exit(1);
}

echo '  ✔️  '.count($exercises)." ejercicios resueltos contra el catálogo\n\n";

// ------------------------------------------------------------------
// 2. Fichas de benchmark (contenido transcrito del doc fuente)
// ------------------------------------------------------------------

$wods = [];

// Ficha 1 — HYROX PFT (Physical Fitness Test oficial de clasificación)
$wods[] = [
    'name' => 'HYROX PFT',
    'type' => 'benchmark',
    'description' => "Por tiempo · Physical Fitness Test oficial de clasificación Hyrox (dosis cortas, ~50% de carrera):\n\n"
        . "1. Sprint 1 km (~4:30-6:00 según nivel)\n"
        . "2. Burpee ×50 (completos; validez §1.7-Hyrox §5 / HY3)\n"
        . "3. Farmers Walk 100 m (RX 2×24/2×16 kg; sin soltar, §HY4)\n"
        . "4. Wall Ball Shot ×50 (RX 6/4 kg a diana oficial; series planificadas §3)\n"
        . "5. Rowing Machine Sprint 1 km (ritmo sostenido, no sprint inicial)\n"
        . "6. Sled Push 50 m (RX 152/102 kg; zancada corta, torso alto §1)\n"
        . "7. SkiErg Sprint 1 km (ritmo Z3-Z4; remada larga piernas-brazos)\n"
        . "8. Broad Jump Burpee 50 m (aterrizaje silencioso §2; si no hay espacio: Burpee ×25)\n\n"
        . "El run corto de transición entre estaciones va embebido en la nota del finish. "
        . "timeCap 40:00. Es el test de marca de base del programa Hyrox Mastery (N1).",
    'scaling' => "Scaled N1: farmers 2×12/2×8 kg, WB 4 kg a diana baja, sled push sin carga o ausente (se anota), BBJ por Burpee si HY3 no está. "
        . "Progresión 50% Open: farmers 2×12/2×8 kg, WB 4 kg, sled push 76/51 kg. "
        . "Variantes Pro (objetivo opcional, niveles 7-8): farmers 2×32/2×24 kg, WB 9/6 kg, sled push 202/152 kg.",
    'elite_male' => '9:00',
    'elite_female' => '10:00',
    'advanced_male' => '13:00',
    'advanced_female' => '15:00',
    'intermediate_male' => '17:00',
    'intermediate_female' => '20:00',
    'beginner_male' => '22:00',
    'beginner_female' => '26:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => null,
    'time_cap' => 2400,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'farmers 2×24 kg, WB 6 kg, sled push 152 kg',
    'rx_weight_female' => 'farmers 2×16 kg, WB 4 kg, sled push 102 kg',
    'movements' => ['run', 'burpee', 'farmers-carry', 'wall-ball', 'row', 'sled-push', 'ski-erg', 'burpee-broad-jump'],
    'exercises' => [
        ['exercise' => $exercises['Sprint'], 'position' => 1, 'distance' => 1000],
        ['exercise' => $exercises['Burpee'], 'position' => 2, 'reps' => 50],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 3, 'distance' => 100, 'rx_weight_male' => '2×24 kg', 'rx_weight_female' => '2×16 kg'],
        ['exercise' => $exercises['Wall Ball Shot'], 'position' => 4, 'reps' => 50, 'rx_weight_male' => '6 kg', 'rx_weight_female' => '4 kg'],
        ['exercise' => $exercises['Rowing Machine Sprint'], 'position' => 5, 'distance' => 1000],
        ['exercise' => $exercises['Sled Push'], 'position' => 6, 'distance' => 50, 'rx_weight_male' => '152 kg', 'rx_weight_female' => '102 kg'],
        ['exercise' => $exercises['SkiErg Sprint'], 'position' => 7, 'distance' => 1000],
        ['exercise' => $exercises['Broad Jump Burpee'], 'position' => 8, 'distance' => 50],
    ],
];

// Ficha 2 — Hyrox Simulacro L3 (media Hyrox: 4 estaciones, runs 500 m, 50% Open)
$wods[] = [
    'name' => 'Hyrox Simulacro L3',
    'type' => 'benchmark',
    'description' => "Por tiempo · Media Hyrox: 4 estaciones con runs 500 m intercalados, pesos ligeros (50% Open).\n\n"
        . "Estructura run→estación (4 rondas; el jugador registra cada estación como 'ronda'):\n"
        . "1. Sprint 500 m (Run 1, tras él SkiErg)\n"
        . "2. SkiErg Sprint 500 m (ritmo Z3; transición corta)\n"
        . "3. Sprint 500 m (Run 2, tras él Sled Push)\n"
        . "4. Sled Push 25 m (76/51 kg; zancada corta §1)\n"
        . "5. Sprint 500 m (Run 3, tras él Sled Pull)\n"
        . "6. Sled Pull 25 m (52/39 kg; peso atrás, pasos cortos)\n"
        . "7. Sprint 500 m (Run 4, tras él Wall Balls)\n"
        . "8. Wall Ball Shot ×25 (WB 4 kg a diana baja; series de 5-10 §3)\n\n"
        . "timeCap 45:00. Es el primer simulacro del programa y el test del Nivel 3.",
    'scaling' => "Scaled: sled push sin carga/pull con banda, WB 4 kg a diana baja, runs a 400 m. "
        . "Subida (N5+, se resuelve en el lote L5): pesos 75% Open y runs 1 km.",
    'elite_male' => '24:00',
    'elite_female' => '27:00',
    'advanced_male' => '28:00',
    'advanced_female' => '32:00',
    'intermediate_male' => '32:00',
    'intermediate_female' => '37:00',
    'beginner_male' => '38:00',
    'beginner_female' => '42:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 4,
    'time_cap' => 2700,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'sled push 76 kg, sled pull 52 kg, WB 4 kg',
    'rx_weight_female' => 'sled push 51 kg, sled pull 39 kg, WB 4 kg',
    'movements' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'wall-ball'],
    'exercises' => [
        ['exercise' => $exercises['Sprint'], 'position' => 1, 'distance' => 500],
        ['exercise' => $exercises['SkiErg Sprint'], 'position' => 2, 'distance' => 500],
        ['exercise' => $exercises['Sprint'], 'position' => 3, 'distance' => 500],
        ['exercise' => $exercises['Sled Push'], 'position' => 4, 'distance' => 25, 'rx_weight_male' => '76 kg', 'rx_weight_female' => '51 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 5, 'distance' => 500],
        ['exercise' => $exercises['Sled Pull'], 'position' => 6, 'distance' => 25, 'rx_weight_male' => '52 kg', 'rx_weight_female' => '39 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 7, 'distance' => 500],
        ['exercise' => $exercises['Wall Ball Shot'], 'position' => 8, 'reps' => 25, 'rx_weight_male' => '4 kg', 'rx_weight_female' => '4 kg'],
    ],
];

// Ficha 3 — Hyrox Simulacro L5 (bloques dobles: 6 estaciones, runs 1 km, 75% Open)
$wods[] = [
    'name' => 'Hyrox Simulacro L5',
    'type' => 'benchmark',
    'description' => "Por tiempo · Bloques dobles: las 6 primeras estaciones del orden oficial con runs de 1 km, al 75% de los pesos Open.\n\n"
        . "Estructura run→estación (6 rondas; el jugador registra cada estación como 'ronda'):\n"
        . "1. Sprint 1 km (Run 1, tras él SkiErg)\n"
        . "2. SkiErg Sprint 1 km (ritmo Z3-Z4; transición corta)\n"
        . "3. Sprint 1 km (Run 2, tras él Sled Push)\n"
        . "4. Sled Push 50 m (4×12,5 m; 114/76 kg; zancada corta, torso alto §1)\n"
        . "5. Sprint 1 km (Run 3, tras él Sled Pull)\n"
        . "6. Sled Pull 50 m (77/58 kg; peso atrás, pasos cortos)\n"
        . "7. Sprint 1 km (Run 4, tras él Burpee Broad Jumps)\n"
        . "8. Broad Jump Burpee 60 m (aterrizaje silencioso §2; distancia reducida al 75%)\n"
        . "9. Sprint 1 km (Run 5, tras él Row)\n"
        . "10. Rowing Machine Sprint 1 km (ritmo sostenido, cierre fuerte)\n"
        . "11. Sprint 1 km (Run 6, tras él Farmers)\n"
        . "12. Farmers Walk 200 m (2×18/2×12 kg; sin soltar; postura erguida)\n\n"
        . "timeCap 60:00. Es el primer simulacro del tramo medio y el test del Nivel 5.",
    'scaling' => "Scaled: sled push 57/38 kg, sled pull 39/29 kg, farmers 2×12/2×8 kg, BBJ 40 m. "
        . "Subida (L6, ficha Hyrox Simulacro L6): pesos ~65% Open y BBJ 80 m.",
    'elite_male' => '40:00',
    'elite_female' => '44:00',
    'advanced_male' => '46:00',
    'advanced_female' => '51:00',
    'intermediate_male' => '52:00',
    'intermediate_female' => '57:00',
    'beginner_male' => '58:00',
    'beginner_female' => '63:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 6,
    'time_cap' => 3600,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'sled push 114 kg, sled pull 77 kg, farmers 2×18 kg',
    'rx_weight_female' => 'sled push 76 kg, sled pull 58 kg, farmers 2×12 kg',
    'movements' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'burpee-broad-jump', 'row', 'farmers-carry'],
    'exercises' => [
        ['exercise' => $exercises['Sprint'], 'position' => 1, 'distance' => 1000],
        ['exercise' => $exercises['SkiErg Sprint'], 'position' => 2, 'distance' => 1000],
        ['exercise' => $exercises['Sprint'], 'position' => 3, 'distance' => 1000],
        ['exercise' => $exercises['Sled Push'], 'position' => 4, 'distance' => 50, 'rx_weight_male' => '114 kg', 'rx_weight_female' => '76 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 5, 'distance' => 1000],
        ['exercise' => $exercises['Sled Pull'], 'position' => 6, 'distance' => 50, 'rx_weight_male' => '77 kg', 'rx_weight_female' => '58 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 7, 'distance' => 1000],
        ['exercise' => $exercises['Broad Jump Burpee'], 'position' => 8, 'distance' => 60],
        ['exercise' => $exercises['Sprint'], 'position' => 9, 'distance' => 1000],
        ['exercise' => $exercises['Rowing Machine Sprint'], 'position' => 10, 'distance' => 1000],
        ['exercise' => $exercises['Sprint'], 'position' => 11, 'distance' => 1000],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 12, 'distance' => 200, 'rx_weight_male' => '2×18 kg', 'rx_weight_female' => '2×12 kg'],
    ],
];

// Ficha 4 — Hyrox Simulacro L6 (Hyrox 3/4: 8 estaciones en orden oficial, runs 1 km, ~65% Open)
$wods[] = [
    'name' => 'Hyrox Simulacro L6',
    'type' => 'benchmark',
    'description' => "Por tiempo · Hyrox 3/4: las 8 estaciones en orden oficial completo con runs de 1 km, al ~65% de los pesos Open.\n\n"
        . "Estructura run→estación (8 rondas; el jugador registra cada estación como 'ronda'):\n"
        . "1. Sprint 1 km (Run 1, tras él SkiErg)\n"
        . "2. SkiErg Sprint 1 km (ritmo Z3; salida controlada)\n"
        . "3. Sprint 1 km (Run 2, tras él Sled Push)\n"
        . "4. Sled Push 50 m (4×12,5 m; 100/68 kg; zancada corta, torso alto §1)\n"
        . "5. Sprint 1 km (Run 3, tras él Sled Pull)\n"
        . "6. Sled Pull 50 m (67/50 kg; peso atrás, pasos cortos)\n"
        . "7. Sprint 1 km (Run 4, tras él Burpee Broad Jumps)\n"
        . "8. Broad Jump Burpee 80 m (distancia completa de carrera; aterrizaje silencioso §2)\n"
        . "9. Sprint 1 km (Run 5, tras él Row)\n"
        . "10. Rowing Machine Sprint 1 km (ritmo sostenido)\n"
        . "11. Sprint 1 km (Run 6, tras él Farmers)\n"
        . "12. Farmers Walk 200 m (2×16/2×12 kg; sin soltar)\n"
        . "13. Sprint 1 km (Run 7, tras él Sandbag Lunges)\n"
        . "14. Kettlebell Goblet Lunge 100 m (16/12 kg en goblet; simula el sandbag de competición, §1.7-Hyrox §1)\n"
        . "15. Sprint 1 km (Run 8, tras él Wall Balls)\n"
        . "16. Wall Ball Shot ×75 (6/4 kg a diana oficial; series planificadas de 10-15, §3)\n\n"
        . "timeCap 90:00. Es el test del Nivel 6.",
    'scaling' => "Scaled: pesos del Hyrox Simulacro L5 (sled push 114/76, pull 77/58, farmers 2×18/2×12, goblet 12/8 kg), WB ×50. "
        . "Subida (L7, lote de niveles 7-8): 100% de los pesos Open y WB ×100.",
    'elite_male' => '55:00',
    'elite_female' => '60:00',
    'advanced_male' => '62:00',
    'advanced_female' => '68:00',
    'intermediate_male' => '70:00',
    'intermediate_female' => '77:00',
    'beginner_male' => '78:00',
    'beginner_female' => '85:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 8,
    'time_cap' => 5400,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'sled push 100 kg, sled pull 67 kg, farmers 2×16 kg, KB goblet lunge 16 kg, WB 6 kg',
    'rx_weight_female' => 'sled push 68 kg, sled pull 50 kg, farmers 2×12 kg, KB goblet lunge 12 kg, WB 4 kg',
    'movements' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'burpee-broad-jump', 'row', 'farmers-carry', 'lunge', 'wall-ball'],
    'exercises' => [
        ['exercise' => $exercises['Sprint'], 'position' => 1, 'distance' => 1000],
        ['exercise' => $exercises['SkiErg Sprint'], 'position' => 2, 'distance' => 1000],
        ['exercise' => $exercises['Sprint'], 'position' => 3, 'distance' => 1000],
        ['exercise' => $exercises['Sled Push'], 'position' => 4, 'distance' => 50, 'rx_weight_male' => '100 kg', 'rx_weight_female' => '68 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 5, 'distance' => 1000],
        ['exercise' => $exercises['Sled Pull'], 'position' => 6, 'distance' => 50, 'rx_weight_male' => '67 kg', 'rx_weight_female' => '50 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 7, 'distance' => 1000],
        ['exercise' => $exercises['Broad Jump Burpee'], 'position' => 8, 'distance' => 80],
        ['exercise' => $exercises['Sprint'], 'position' => 9, 'distance' => 1000],
        ['exercise' => $exercises['Rowing Machine Sprint'], 'position' => 10, 'distance' => 1000],
        ['exercise' => $exercises['Sprint'], 'position' => 11, 'distance' => 1000],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 12, 'distance' => 200, 'rx_weight_male' => '2×16 kg', 'rx_weight_female' => '2×12 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 13, 'distance' => 1000],
        ['exercise' => $exercises['Kettlebell Goblet Lunge'], 'position' => 14, 'distance' => 100, 'rx_weight_male' => '16 kg', 'rx_weight_female' => '12 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 15, 'distance' => 1000],
        ['exercise' => $exercises['Wall Ball Shot'], 'position' => 16, 'reps' => 75, 'rx_weight_male' => '6 kg', 'rx_weight_female' => '4 kg'],
    ],
];

// ------------------------------------------------------------------
// 3. Persistir los benchmarks (idempotente por nombre)
// ------------------------------------------------------------------

// Definición compartida de la carrera Open (8× run 1 km + estación, 100% Open):
// usada por las fichas L7 y L8 (docs/detalle_hyrox_niveles_07-08.md, nota de diseño).
$openRaceExercises = static function () use ($exercises): array {
    return [
        ['exercise' => $exercises['Sprint'], 'position' => 1, 'distance' => 1000],
        ['exercise' => $exercises['SkiErg Sprint'], 'position' => 2, 'distance' => 1000],
        ['exercise' => $exercises['Sprint'], 'position' => 3, 'distance' => 1000],
        ['exercise' => $exercises['Sled Push'], 'position' => 4, 'distance' => 50, 'rx_weight_male' => '152 kg', 'rx_weight_female' => '102 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 5, 'distance' => 1000],
        ['exercise' => $exercises['Sled Pull'], 'position' => 6, 'distance' => 50, 'rx_weight_male' => '103 kg', 'rx_weight_female' => '78 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 7, 'distance' => 1000],
        ['exercise' => $exercises['Broad Jump Burpee'], 'position' => 8, 'distance' => 80],
        ['exercise' => $exercises['Sprint'], 'position' => 9, 'distance' => 1000],
        ['exercise' => $exercises['Rowing Machine Sprint'], 'position' => 10, 'distance' => 1000],
        ['exercise' => $exercises['Sprint'], 'position' => 11, 'distance' => 1000],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 12, 'distance' => 200, 'rx_weight_male' => '2×24 kg', 'rx_weight_female' => '2×16 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 13, 'distance' => 1000],
        ['exercise' => $exercises['Kettlebell Goblet Lunge'], 'position' => 14, 'distance' => 100, 'rx_weight_male' => '20 kg', 'rx_weight_female' => '12 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 15, 'distance' => 1000],
        ['exercise' => $exercises['Wall Ball Shot'], 'position' => 16, 'reps' => 100, 'rx_weight_male' => '6 kg', 'rx_weight_female' => '4 kg'],
    ];
};

// Ficha 5 — Hyrox Simulacro L7 (la carrera completa al 100% de pesos Open: construcción)
$wods[] = [
    'name' => 'Hyrox Simulacro L7',
    'type' => 'benchmark',
    'description' => "Por tiempo · La carrera completa al 100% de pesos Open: 8×1 km de carrera + las 8 estaciones en orden oficial (temporada 26/27). El objetivo de todo el programa.\n\n"
        . "Estructura run→estación (8 rondas; el jugador registra cada estación como 'ronda'):\n"
        . "Run 1 · SkiErg 1 km (split objetivo M ~4:30 / F ~5:00) · Run 2 · Sled Push 50 m (4×12,5 m; 152/102 kg; §1) · "
        . "Run 3 · Sled Pull 50 m (103/78 kg) · Run 4 · Broad Jump Burpee 80 m (§2; bloques de 10 m) · "
        . "Run 5 · Row 1 km (split M ~4:00 / F ~4:30) · Run 6 · Farmers Walk 200 m (2×24/2×16 kg) · "
        . "Run 7 · Kettlebell Goblet Lunge 100 m (20/12 kg; simula el sandbag) · Run 8 · Wall Ball Shot ×100 (6/4 kg; series de 10-15, §3).\n\n"
        . "timeCap 1:45:00. Test del Nivel 7 (mínimo 1:30:00 / objetivo 1:15:00).",
    'scaling' => "Scaled: pesos del Hyrox Simulacro L6 (sled push 100/68, pull 67/50, farmers 2×16/2×12, goblet 16/12 kg), WB ×75, BBJ 60 m. "
        . "Variantes Pro (notas, no RX de esta ficha): sled push 202/152 kg, pull 153/103 kg, farmers 2×32/2×24 kg, goblet 28/20 kg, WB 9/6 kg ×100.",
    'elite_male' => '55:00',
    'elite_female' => '60:00',
    'advanced_male' => '62:00',
    'advanced_female' => '68:00',
    'intermediate_male' => '70:00',
    'intermediate_female' => '77:00',
    'beginner_male' => '78:00',
    'beginner_female' => '85:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 8,
    'time_cap' => 6300,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'sled push 152 kg, sled pull 103 kg, farmers 2×24 kg, KB goblet lunge 20 kg, WB 6 kg',
    'rx_weight_female' => 'sled push 102 kg, sled pull 78 kg, farmers 2×16 kg, KB goblet lunge 12 kg, WB 4 kg',
    'movements' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'burpee-broad-jump', 'row', 'farmers-carry', 'lunge', 'wall-ball'],
    'exercises' => $openRaceExercises(),
];

// Ficha 6 — Hyrox Simulacro L8 (misma carrera a ritmo de competición: afilado)
$wods[] = [
    'name' => 'Hyrox Simulacro L8',
    'type' => 'benchmark',
    'description' => "Por tiempo · La misma carrera del L7 (8×1 km + 8 estaciones al 100% de pesos Open, orden oficial) un escalón de ritmo por encima: la ficha de competición. "
        . "Solo varían el cap (1:30:00) y las referencias respecto al Hyrox Simulacro L7.\n\n"
        . "timeCap 1:30:00. Test del Nivel 8 (mínimo 1:15:00 / objetivo 1:00:00 — exige runs a ~4:15 y Roxzone bajo control).",
    'scaling' => "Scaled: pesos L6 (como L7). "
        . "Variantes Pro (nota 'si compites Pro, entrena estos pesos en las sesiones A'): sled push 202/152 kg, pull 153/103 kg, farmers 2×32/2×24 kg, goblet 28/20 kg, WB 9/6 kg ×100.",
    'elite_male' => '50:00',
    'elite_female' => '55:00',
    'advanced_male' => '56:00',
    'advanced_female' => '62:00',
    'intermediate_male' => '63:00',
    'intermediate_female' => '70:00',
    'beginner_male' => '70:00',
    'beginner_female' => '77:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 8,
    'time_cap' => 5400,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'sled push 152 kg, sled pull 103 kg, farmers 2×24 kg, KB goblet lunge 20 kg, WB 6 kg',
    'rx_weight_female' => 'sled push 102 kg, sled pull 78 kg, farmers 2×16 kg, KB goblet lunge 12 kg, WB 4 kg',
    'movements' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'burpee-broad-jump', 'row', 'farmers-carry', 'lunge', 'wall-ball'],
    'exercises' => $openRaceExercises(),
];

echo '=== Sembrando '.count($wods)." benchmarks ===\n\n";

foreach ($wods as $wod) {
    $existing = $em->getRepository(\App\Entity\Benchmark::class)->findOneBy(['name' => $wod['name']]);

    if (null !== $existing) {
        // Actualización in situ: los BenchmarkExercise ya pueden tener logs de
        // usuarios (user_benchmark_exercise_log), así que no se borran: se
        // actualizan por position y solo se crean las posiciones que falten.
        echo "  🔄 Actualizando benchmark (in situ): {$wod['name']}\n";
        $benchmark = $existing;
        $benchmark->setDescription($wod['description'] ?? null);
        $benchmark->setScalingOptions($wod['scaling'] ?? null);
        $benchmark->setEliteTimeMale($wod['elite_male'] ?? null);
        $benchmark->setEliteTimeFemale($wod['elite_female'] ?? null);
        $benchmark->setAdvancedTimeMale($wod['advanced_male'] ?? null);
        $benchmark->setAdvancedTimeFemale($wod['advanced_female'] ?? null);
        $benchmark->setIntermediateTimeMale($wod['intermediate_male'] ?? null);
        $benchmark->setIntermediateTimeFemale($wod['intermediate_female'] ?? null);
        $benchmark->setBeginnerTimeMale($wod['beginner_male'] ?? null);
        $benchmark->setBeginnerTimeFemale($wod['beginner_female'] ?? null);

        $byPosition = [];
        foreach ($benchmark->getExercises() as $be) {
            $byPosition[$be->getPosition()] = $be;
        }

        foreach ($wod['exercises'] as $exData) {
            $be = $byPosition[$exData['position']] ?? null;
            if (null === $be) {
                $be = new \App\Entity\BenchmarkExercise();
                $be->setBenchmark($benchmark);
                $be->setPosition($exData['position']);
                $em->persist($be);
            }
            $be->setExercise($exData['exercise']);
            $be->setReps($exData['reps'] ?? null);
            $be->setDistanceMeters($exData['distance'] ?? null);
            $be->setDurationSeconds($exData['duration'] ?? null);
            $be->setCalories($exData['calories'] ?? null);
            $be->setRxWeightMale($exData['rx_weight_male'] ?? null);
            $be->setRxWeightFemale($exData['rx_weight_female'] ?? null);
            $be->setRestSeconds($exData['rest'] ?? null);
        }
        $em->flush();
    } else {
        $benchmark = $helper->seedBenchmark($wod);
    }

    $benchmark->setFormat($wod['format']);
    $benchmark->setRounds($wod['rounds'] ?? null);
    $benchmark->setTimeCapSeconds($wod['time_cap'] ?? null);
    $benchmark->setPartitioningAllowed($wod['partitioning'] ?? false);
    $benchmark->setTimerConfig($wod['timer'] ?? null);
    $benchmark->setMovements($wod['movements'] ?? null);
    $benchmark->setRxWeightMale($wod['rx_weight_male'] ?? null);
    $benchmark->setRxWeightFemale($wod['rx_weight_female'] ?? null);
}

$em->flush();

foreach ($wods as $wod) {
    $benchmark = $em->getRepository(\App\Entity\Benchmark::class)->findOneBy(['name' => $wod['name']]);
    echo "  #{$benchmark->getId()} {$benchmark->getName()}\n";
}

echo "\n=== Seeder completado con éxito ===\n";
