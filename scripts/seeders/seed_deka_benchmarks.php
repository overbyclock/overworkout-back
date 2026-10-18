<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

use App\Enum\BenchmarkFormat;

// Benchmark DEKA Media — docs/detalle_deka_niveles_01-03.md §"Benchmarks del lote"
// (ficha de spec §4.1 del programa DEKA Mastery; las fichas DEKA Strong/Mile/Fit
// se siembran en el lote de niveles 4-6).
// Idempotente: find-or-update por nombre; si el benchmark ya existe se actualiza
// in situ por position (los BenchmarkExercise pueden tener logs de usuarios).

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== OverWorkout — Seeder de benchmarks DEKA (DEKA Media) ===\n\n";

// ------------------------------------------------------------------
// 1. Resolver ejercicios del catálogo (resolución estricta por nombre)
// ------------------------------------------------------------------

$exerciseNames = [
    'Sprint', 'Reverse Lunge', 'Rowing Machine Sprint', 'Box Jump',
    'Farmers Walk', 'Burpee', 'Dumbbell Lunge', 'Sit-up', 'SkiErg Sprint',
    'Assault Bike Sprint', 'Medicine Ball Slam', 'Sled Push', 'Sled Pull',
    'Thruster', 'Burpee Over Bar', 'Dumbbell Thruster', 'Bear Crawl',
    'Standing Dumbbell Press', 'Jump Rope', 'Farmer Walk',
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
// 2. Ficha de benchmark (contenido transcrito del doc fuente)
// ------------------------------------------------------------------

$wods = [];

// Ficha — DEKA Media (media DEKA: 5 zonas, runs 160 m, 50% de los pesos)
$wods[] = [
    'name' => 'DEKA Media',
    'type' => 'benchmark',
    'description' => "Por tiempo · Media DEKA: las 5 primeras zonas del orden oficial con runs de 160 m, al 50% de los pesos de competición.\n\n"
        . "Estructura run→zona (5 rondas; el jugador registra cada zona como 'ronda'):\n"
        . "1. Sprint 160 m (~40-45 s) — Run 1, tras él la zancada\n"
        . "2. Reverse Lunge 15/lado — con 2×8/2×6 kg en mancuernas (nota: simula RAM 25/15 kg en back rack); rodilla de atrás al suelo, torso alto (§1)\n"
        . "3. Sprint 160 m — Run 2, tras él el remo\n"
        . "4. Rowing Machine Sprint 250 m — ritmo Z3; remada larga\n"
        . "5. Sprint 160 m — Run 3, tras él la caja\n"
        . "6. Box Jump ×10 — caja 24 pulgadas; box over permitido: Step Up/Box Step Over en nota (§2); sin doble bote\n"
        . "7. Sprint 160 m — Run 4, tras él el farmers\n"
        . "8. Farmers Walk 50 m — 2×12/2×8 kg; pasos cortos, agarre firme (§3)\n"
        . "9. Sprint 160 m — Run 5, tras él los burpees\n"
        . "10. Burpee ×10 — completos; aterrizaje controlado; el burpee cargado de competición llega en niveles altos\n\n"
        . "timeCap 30:00. Es el primer simulacro del programa y el test del Nivel 3.",
    'scaling' => "Scaled: zancada y farmers al peso corporal/mancuernas ligeras disponibles, Step Up en lugar de Box Jump, runs a 100 m. "
        . "Subida (N4+): pesos Open por separado y DEKA Strong/Mile en sus fichas (lote 4-6).",
    'elite_male' => '10:00',
    'elite_female' => '11:00',
    'advanced_male' => '13:00',
    'advanced_female' => '15:00',
    'intermediate_male' => '17:00',
    'intermediate_female' => '20:00',
    'beginner_male' => '24:00',
    'beginner_female' => '27:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 5,
    'time_cap' => 1800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'lunge 2×8 kg (nota RAM 25 kg), farmers 2×12 kg (nota Open 2×27 kg), MB 4,5 kg en nota',
    'rx_weight_female' => 'lunge 2×6 kg (nota RAM 15 kg), farmers 2×8 kg (nota Open 2×18 kg), MB 3 kg en nota',
    'movements' => ['run', 'lunge', 'row', 'box-jump', 'farmers-carry', 'burpee'],
    'exercises' => [
        ['exercise' => $exercises['Sprint'], 'position' => 1, 'distance' => 160],
        ['exercise' => $exercises['Reverse Lunge'], 'position' => 2, 'reps' => 15, 'rx_weight_male' => '2×8 kg', 'rx_weight_female' => '2×6 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 3, 'distance' => 160],
        ['exercise' => $exercises['Rowing Machine Sprint'], 'position' => 4, 'distance' => 250],
        ['exercise' => $exercises['Sprint'], 'position' => 5, 'distance' => 160],
        ['exercise' => $exercises['Box Jump'], 'position' => 6, 'reps' => 10],
        ['exercise' => $exercises['Sprint'], 'position' => 7, 'distance' => 160],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 8, 'distance' => 50, 'rx_weight_male' => '2×12 kg', 'rx_weight_female' => '2×8 kg'],
        ['exercise' => $exercises['Sprint'], 'position' => 9, 'distance' => 160],
        ['exercise' => $exercises['Burpee'], 'position' => 10, 'reps' => 10],
    ],
];

// ------------------------------------------------------------------
// 3. Fichas oficiales del lote 4-6 (DEKA Strong / Mile / Fit)
// ------------------------------------------------------------------

// Array ZONAS RX compartido (docs/detalle_deka_niveles_04-06.md, spec §2):
// las 10 zonas oficiales en orden, pesos oficiales M/F temporada 26/27.
// La zona 9 (sled) se guarda como fila única de Sled Push 50 m con la nota del
// pull en la descripción (el jugador anota el peso usado en el finish).
$zonesRx = static function () use ($exercises): array {
    return [
        ['exercise' => $exercises['Dumbbell Lunge'], 'position' => 1, 'reps' => 30, 'rx_weight_male' => '2×16 kg', 'rx_weight_female' => '2×10 kg'],
        ['exercise' => $exercises['Rowing Machine Sprint'], 'position' => 2, 'distance' => 500],
        ['exercise' => $exercises['Box Jump'], 'position' => 3, 'reps' => 20],
        ['exercise' => $exercises['Sit-up'], 'position' => 4, 'reps' => 25, 'rx_weight_male' => '9 kg', 'rx_weight_female' => '6.5 kg'],
        ['exercise' => $exercises['SkiErg Sprint'], 'position' => 5, 'distance' => 500],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 6, 'distance' => 100, 'rx_weight_male' => '2×27 kg', 'rx_weight_female' => '2×18 kg'],
        ['exercise' => $exercises['Assault Bike Sprint'], 'position' => 7, 'reps' => 25],
        ['exercise' => $exercises['Medicine Ball Slam'], 'position' => 8, 'reps' => 20, 'rx_weight_male' => '27 kg', 'rx_weight_female' => '18 kg'],
        ['exercise' => $exercises['Sled Push'], 'position' => 9, 'distance' => 50, 'rx_weight_male' => 'según gimnasio', 'rx_weight_female' => 'según gimnasio'],
        ['exercise' => $exercises['Burpee'], 'position' => 10, 'reps' => 20, 'rx_weight_male' => '20 kg', 'rx_weight_female' => '10 kg'],
    ];
};

// Intercala runs delante de cada zona (DEKA Mile: 160 m; DEKA Fit: 500 m)
$withRuns = static function (array $zones, int $runDistance) use ($exercises): array {
    $rows = [];
    $position = 1;
    foreach ($zones as $zone) {
        $rows[] = ['exercise' => $exercises['Sprint'], 'position' => $position++, 'distance' => $runDistance];
        $zone['position'] = $position++;
        $rows[] = $zone;
    }

    return $rows;
};

$rxWeightMale = 'según ZONAS RX (lunge 2×16 kg, farmers 2×27 kg, MB slam 27 kg, MB sit-up 9 kg)';
$rxWeightFemale = 'según ZONAS RX (lunge 2×10 kg, farmers 2×18 kg, MB slam 18 kg, MB sit-up 6.5 kg)';
$scalingOfficials = "Scaled: pesos del lote anterior (DEKA Media: lunge 2×8/2×6 kg, farmers 2×12/2×8 kg) y zonas reducidas (row/ski 250 m, sit-up ×15, bike 15 cal, sled con menos peso). "
    . "Nota zona 9: el peso del sled depende del gimnasio (sustituto del Torque lvl 3 M / lvl 2 F, Xebex+73 kg); en competición son 5×10 m ida y vuelta (push 50 m + pull 50 m).";

// Ficha — DEKA Strong (10 zonas RX como carrera completa SIN correr)
$wods[] = [
    'name' => 'DEKA Strong',
    'type' => 'benchmark',
    'description' => "Por tiempo · Las 10 zonas RX en orden como carrera completa SIN correr: la prueba de fuerza-resistencia pura de la DEKA.\n\n"
        . "Zonas (el jugador registra cada zona como 'ronda'; la transición entre zonas es caminando y forma parte del tiempo):\n"
        . "1. Dumbbell Lunge ×30 (15/lado) — 2×16/2×10 kg (nota: simula RAM 25/15 kg en back rack, §1)\n"
        . "2. Row 500 m · 3. Box Jump ×20 (caja 24 pulgadas; box over permitido, §2)\n"
        . "4. Sit-up ×25 con MB 9/6.5 kg (toque entre pies)\n"
        . "5. SkiErg 500 m · 6. Farmers Walk 100 m — 2×27/2×18 kg (en Strong/Mile: 10 m ida y vuelta ×5; §3)\n"
        . "7. Air Bike 25 cal continuas, sentado (sustituto: Echo Bike)\n"
        . "8. Medicine Ball Slam ×20 — 27/18 kg (simula dead ball shoulder over, §4)\n"
        . "9. Sled Push 50 m — seguido de Sled Pull 50 m (en la fila 9 va el push; el pull se ejecuta a continuación: 5×10 m ida y vuelta en competición)\n"
        . "10. Burpee ×20 — RAM 20/10 kg (nota; carga opcional en entrenamiento)\n\n"
        . "timeCap 30:00. Test del Nivel 5 (mínimo 24:00 / objetivo 18:00).",
    'scaling' => $scalingOfficials,
    'elite_male' => '11:00',
    'elite_female' => '12:30',
    'advanced_male' => '14:00',
    'advanced_female' => '16:00',
    'intermediate_male' => '18:00',
    'intermediate_female' => '21:00',
    'beginner_male' => '24:00',
    'beginner_female' => '28:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 10,
    'time_cap' => 1800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => $rxWeightMale,
    'rx_weight_female' => $rxWeightFemale,
    'movements' => ['lunge', 'row', 'box-jump', 'sit-up', 'ski-erg', 'farmers-carry', 'bike', 'ball-slam', 'sled', 'burpee'],
    'exercises' => $zonesRx(),
];

// Ficha — DEKA Mile (10 zonas RX con 10×160 m: el formato clásico)
$wods[] = [
    'name' => 'DEKA Mile',
    'type' => 'benchmark',
    'description' => "Por tiempo · Las 10 zonas RX con 10×160 m: el formato clásico de competición.\n\n"
        . "Estructura run→zona (10 rondas; el jugador registra cada zona como 'ronda'): run 160 m (~40-50 s) → zona del array ZONAS RX en orden "
        . "(1 zancada · 2 row 500 m · 3 box ×20 · 4 sit-up MB ×25 · 5 ski 500 m · 6 farmers 100 m i/v · 7 bike 25 cal · 8 slam ×20 · 9 sled push 50 m + pull 50 m · 10 burpee cargado ×20).\n\n"
        . "timeCap 60:00. Test del Nivel 6 (mínimo 33:00 / objetivo 27:00).",
    'scaling' => $scalingOfficials,
    'elite_male' => '18:00',
    'elite_female' => '20:00',
    'advanced_male' => '22:00',
    'advanced_female' => '25:00',
    'intermediate_male' => '27:00',
    'intermediate_female' => '31:00',
    'beginner_male' => '33:00',
    'beginner_female' => '38:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 10,
    'time_cap' => 3600,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => $rxWeightMale,
    'rx_weight_female' => $rxWeightFemale,
    'movements' => ['run', 'lunge', 'row', 'box-jump', 'sit-up', 'ski-erg', 'farmers-carry', 'bike', 'ball-slam', 'sled', 'burpee'],
    'exercises' => $withRuns($zonesRx(), 160),
];

// Ficha — DEKA Fit (10 zonas RX con 10×500 m: la carrera larga; test del lote 07-08)
$wods[] = [
    'name' => 'DEKA Fit',
    'type' => 'benchmark',
    'description' => "Por tiempo · Las 10 zonas RX con 10×500 m: la carrera larga. Se siembra en este lote; su uso en test se decide en el lote 07-08.\n\n"
        . "Estructura run→zona (10 rondas): run 500 m (~2:00-2:30) → zona del array ZONAS RX en orden.\n"
        . "Notas de formato Fit (spec §2): la zona 4 es Med Ball Sit-Up THROWS (throw a diana — mismo Sit-up con MB 9/6.5 kg), "
        . "la zona 8 es YOKE OVER (el Medicine Ball Slam queda como sustituto anotado, estándar distinto al dead ball) "
        . "y la zona 6 farmers en recorrido directo de 100 m (sin ida y vuelta).\n\n"
        . "timeCap 90:00.",
    'scaling' => $scalingOfficials,
    'elite_male' => '28:00',
    'elite_female' => '31:00',
    'advanced_male' => '33:00',
    'advanced_female' => '37:00',
    'intermediate_male' => '40:00',
    'intermediate_female' => '45:00',
    'beginner_male' => '48:00',
    'beginner_female' => '55:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 10,
    'time_cap' => 5400,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => $rxWeightMale,
    'rx_weight_female' => $rxWeightFemale,
    'movements' => ['run', 'lunge', 'row', 'box-jump', 'sit-up', 'ski-erg', 'farmers-carry', 'bike', 'ball-slam', 'sled', 'burpee'],
    'exercises' => $withRuns($zonesRx(), 500),
];

// ------------------------------------------------------------------
// 4. Fichas DEKA ATLAS (DEKA Rules of Competition v20260210, fuente verificada)
// ------------------------------------------------------------------

// Array ZONAS ATLAS compartido: las 10 zonas del test de fuerza (sin correr).
// $pesos = 'foundation' (oficial) o 'peak' (+20-25% aprox sobre Foundation —
// los pesos Peak exactos no están en las fuentes textuales: aproximados,
// confirmar en el evento).
$atlasZones = static function (string $division) use ($exercises): array {
    $p = 'peak' === $division
        ? ['thruster' => '52 kg', 'thruster_f' => '34 kg',
           'lunge' => '2×28 kg', 'lunge_f' => '2×20 kg',
           'g2oh' => '28 kg', 'g2oh_f' => '20 kg',
           'crawl' => '2×28 kg', 'crawl_f' => '2×18 kg',
           'situp' => '20 kg', 'situp_f' => '14 kg',
           'farmers' => '2×28 kg', 'farmers_f' => '2×18 kg',
           'press' => '2×22,5 kg', 'press_f' => '2×14 kg',
           'shoulder' => '57 kg', 'shoulder_f' => '40 kg', ]
        : ['thruster' => '43 kg', 'thruster_f' => '29,5 kg',
           'lunge' => '2×23 kg', 'lunge_f' => '2×16 kg',
           'g2oh' => '22,5 kg', 'g2oh_f' => '15 kg',
           'crawl' => '2×22,5 kg', 'crawl_f' => '2×15 kg',
           'situp' => '16 kg', 'situp_f' => '11 kg',
           'farmers' => '2×22,5 kg', 'farmers_f' => '2×15 kg',
           'press' => '2×17,5 kg', 'press_f' => '2×11 kg',
           'shoulder' => '45 kg', 'shoulder_f' => '32 kg', ];

    return [
        ['exercise' => $exercises['Thruster'], 'position' => 1, 'reps' => 20, 'rx_weight_male' => $p['thruster'], 'rx_weight_female' => $p['thruster_f']],
        ['exercise' => $exercises['Burpee Over Bar'], 'position' => 2, 'reps' => 20],
        ['exercise' => $exercises['Dumbbell Lunge'], 'position' => 3, 'reps' => 20, 'rx_weight_male' => $p['lunge'], 'rx_weight_female' => $p['lunge_f']],
        ['exercise' => $exercises['Dumbbell Thruster'], 'position' => 4, 'reps' => 20, 'rx_weight_male' => $p['g2oh'], 'rx_weight_female' => $p['g2oh_f']],
        ['exercise' => $exercises['Bear Crawl'], 'position' => 5, 'distance' => 40, 'rx_weight_male' => $p['crawl'], 'rx_weight_female' => $p['crawl_f']],
        ['exercise' => $exercises['Sit-up'], 'position' => 6, 'reps' => 20, 'rx_weight_male' => $p['situp'], 'rx_weight_female' => $p['situp_f']],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 7, 'distance' => 100, 'rx_weight_male' => $p['farmers'], 'rx_weight_female' => $p['farmers_f']],
        ['exercise' => $exercises['Standing Dumbbell Press'], 'position' => 8, 'reps' => 20, 'rx_weight_male' => $p['press'], 'rx_weight_female' => $p['press_f']],
        ['exercise' => $exercises['Jump Rope'], 'position' => 9, 'reps' => 100],
        ['exercise' => $exercises['Farmer Walk'], 'position' => 10, 'distance' => 100, 'rx_weight_male' => $p['shoulder'], 'rx_weight_female' => $p['shoulder_f']],
    ];
};

$scalingAtlas = "Scaled: thruster con KB/mancuernas menores, G2OH sin carga (peso corporal), bear crawl sin mancuernas, sit-up sin disco, farmers/shoulder carry al 50-60%. "
    . "Notas de pesos (Standards Chart, confirmar en el evento): farmer's carry rulebook = '100 lb total' (mapeado a 2×22,5/2×15 kg); shoulder-to-press ~35/25 lb; single unders = 100 (cantidad del Standards Chart). "
    . "PEAK: pesos aproximados +20-25% sobre Foundation (no publicados en las fuentes textuales — confirmar en el evento).";

// Ficha — DEKA ATLAS Foundation (pesos base, cap 30:00)
$wods[] = [
    'name' => 'DEKA ATLAS Foundation',
    'type' => 'benchmark',
    'description' => "Por tiempo · DEKA ATLAS (test de fuerza de 10 zonas, SIN correr), división Foundation (pesos base). timeCap 30:00.\n\n"
        . "Las 10 zonas en orden (el jugador registra cada zona como 'ronda'):\n"
        . "1. Barbell Thruster ×20 (43/29,5 kg — 95/65 lb)\n"
        . "2. Burpee Over Bar ×20 (barra olímpica)\n"
        . "3. Surrender Lunge ×20 (2×23/2×16 kg — 50/35 lb)\n"
        . "4. DB Ground-to-Overhead ×20 (alternando brazos, 10+10; 22,5/15 kg — 50/35 lb)\n"
        . "5. DB Bear Crawl 40 m (2×22,5/2×15 kg)\n"
        . "6. Weighted Sit-Up ×20 (disco 16/11 kg — 35/25 lb)\n"
        . "7. Farmer's Carry 100 m (2×22,5/2×15 kg; rulebook '100 lb total')\n"
        . "8. DB Shoulder-to-Press ×20 strict (2×17,5/2×11 kg — ~35/25 lb, Standards Chart)\n"
        . "9. Jump Rope Single Under ×100 (cantidad del Standards Chart)\n"
        . "10. ATLAS Shoulder Carry 100 m (45/32 kg — 100/70 lb)\n\n"
        . "timeCap 30:00. Test de los niveles N5/N7/N8 del programa DEKA ATLAS.",
    'scaling' => $scalingAtlas,
    'elite_male' => '12:00',
    'elite_female' => '14:00',
    'advanced_male' => '16:00',
    'advanced_female' => '18:30',
    'intermediate_male' => '20:00',
    'intermediate_female' => '23:00',
    'beginner_male' => '25:00',
    'beginner_female' => '28:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 10,
    'time_cap' => 1800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'thruster 43 kg, lunge 2×23 kg, G2OH 22,5 kg, farmers 2×22,5 kg, press 2×17,5 kg, shoulder 45 kg',
    'rx_weight_female' => 'thruster 29,5 kg, lunge 2×16 kg, G2OH 15 kg, farmers 2×15 kg, press 2×11 kg, shoulder 32 kg',
    'movements' => ['thruster', 'burpee-over-bar', 'lunge', 'g2oh', 'bear-crawl', 'sit-up', 'farmers-carry', 'press', 'jump-rope', 'shoulder-carry'],
    'exercises' => $atlasZones('foundation'),
];

// Ficha — DEKA ATLAS Peak (pesos aproximados +20-25%, cap 30:00)
$wods[] = [
    'name' => 'DEKA ATLAS Peak',
    'type' => 'benchmark',
    'description' => "Por tiempo · DEKA ATLAS (test de fuerza de 10 zonas, SIN correr), división Peak (pesos más pesados, MISMO cap de 30:00). "
        . "Mismas zonas y dosis que Foundation con pesos aproximados +20-25% (no publicados en las fuentes textuales — confirmar en el evento). "
        . "timeCap 30:00. Test de los niveles N7/N8 del programa DEKA ATLAS.",
    'scaling' => $scalingAtlas,
    'elite_male' => '14:00',
    'elite_female' => '16:00',
    'advanced_male' => '18:30',
    'advanced_female' => '21:00',
    'intermediate_male' => '23:00',
    'intermediate_female' => '26:00',
    'beginner_male' => '28:00',
    'beginner_female' => '30:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 10,
    'time_cap' => 1800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'thruster 52 kg, lunge 2×28 kg, G2OH 28 kg, farmers 2×28 kg, press 2×22,5 kg, shoulder 57 kg (aprox, confirmar)',
    'rx_weight_female' => 'thruster 34 kg, lunge 2×20 kg, G2OH 20 kg, farmers 2×18 kg, press 2×14 kg, shoulder 40 kg (aprox, confirmar)',
    'movements' => ['thruster', 'burpee-over-bar', 'lunge', 'g2oh', 'bear-crawl', 'sit-up', 'farmers-carry', 'press', 'jump-rope', 'shoulder-carry'],
    'exercises' => $atlasZones('peak'),
];

// Ficha — DEKA Fit Doble (2× DEKA Fit = 20 zonas; runs de 500 m embebidos
// en la transición entre zonas, nota del finish — criterio PFT de Hyrox)
$dobleZones = $zonesRx();
foreach ($zonesRx() as $row) {
    $row['position'] += 10;
    $dobleZones[] = $row;
}

$wods[] = [
    'name' => 'DEKA Fit Doble',
    'type' => 'benchmark',
    'description' => "Por tiempo · 2× DEKA Fit completado: 20 zonas + 10 km (2 rondas de 10 zonas del array ZONAS RX). "
        . "Cada zona va precedida de un run de 500 m embebido en la transición (el jugador lo anota en el finish). "
        . "timeCap 3:00:00.",
    'scaling' => "Scaled: 1× DEKA Fit (ficha DEKA Fit) o zonas al 50%; la modalidad se registra en el intento.",
    'elite_male' => '1:50',
    'elite_female' => '2:05',
    'advanced_male' => '2:20',
    'advanced_female' => '2:40',
    'intermediate_male' => '2:50',
    'intermediate_female' => '3:15',
    'beginner_male' => '3:20',
    'beginner_female' => '3:45',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 2,
    'time_cap' => 10800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => $rxWeightMale,
    'rx_weight_female' => $rxWeightFemale,
    'movements' => ['run', 'lunge', 'row', 'box-jump', 'sit-up', 'ski-erg', 'farmers-carry', 'bike', 'ball-slam', 'sled', 'burpee'],
    'exercises' => $dobleZones,
];

// Ficha — DEKA Fit Ultra (5× DEKA Fit = 50 zonas + 25 km; runs embebidos en notas)
$ultraZones = $zonesRx();
for ($round = 2; $round <= 5; ++$round) {
    foreach ($zonesRx() as $row) {
        $row['position'] += 10 * ($round - 1);
        $ultraZones[] = $row;
    }
}

$wods[] = [
    'name' => 'DEKA Fit Ultra',
    'type' => 'benchmark',
    'description' => "Por tiempo · DEKA FIT ULTRA: el circuito DEKA Fit completado ×5 = 50 zonas + 25 km de carrera (5 rondas de 10 zonas del array ZONAS RX). "
        . "Cada zona va precedida de un run de 500 m embebido en la transición (50 runs × 500 m = 25 km; el jugador los anota en el finish). "
        . "Pit Stop Zone: solo en el pit se puede comer/beber (pit crew, comida e hidratación). Cutoffs oficiales: élite 3:45 M / 4:15 F; age group 9:00. "
        . "Tiempos típicos: finisher 6-8 h, élite ~3:45-4:15. timeCap 8:00:00. En el player se usa el paso benchmark con pausas de pit stop (notas).",
    'scaling' => "Scaled: 'Media Ultra' 3× DEKA Fit (se resuelve en este programa) o zonas al 50%/75%; la modalidad se registra en el intento.",
    'elite_male' => '3:45',
    'elite_female' => '4:15',
    'advanced_male' => '5:00',
    'advanced_female' => '5:30',
    'intermediate_male' => '6:15',
    'intermediate_female' => '7:00',
    'beginner_male' => '7:30',
    'beginner_female' => '8:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 5,
    'time_cap' => 28800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => $rxWeightMale,
    'rx_weight_female' => $rxWeightFemale,
    'movements' => ['run', 'lunge', 'row', 'box-jump', 'sit-up', 'ski-erg', 'farmers-carry', 'bike', 'ball-slam', 'sled', 'burpee'],
    'exercises' => $ultraZones,
];

// ------------------------------------------------------------------
// 5. Persistir los benchmarks (idempotente por nombre)
// ------------------------------------------------------------------

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
