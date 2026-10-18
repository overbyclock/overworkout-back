<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

use App\Enum\BenchmarkFormat;

// Benchmarks Spartan OCR Prep — Spartan Race temporada 2026 (fuentes verificadas).
// Simuladores de carrera: run del tramo en nota + obstáculos como ejercicios.
// Penalización por obstáculo fallado: 30 burpees (nota en descripción).
// Idempotente: find-or-update por nombre; si el benchmark ya existe se actualiza
// in situ por position (los BenchmarkExercise pueden tener logs de usuarios).

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== OverWorkout — Seeder de benchmarks Spartan OCR Prep ===\n\n";

// ------------------------------------------------------------------
// 1. Resolver ejercicios del catálogo (resolución estricta por nombre)
// ------------------------------------------------------------------

$exerciseNames = [
    'Sprint', 'Bear Crawl', 'Monkey Bars', 'Spear Throw', 'Rope Climb',
    'Bucket Carry', 'Farmers Walk', 'Tire Flip', 'Wall Walk', 'Box Jump',
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
// 2. Obstáculos compartidos (helper) y fichas
// ------------------------------------------------------------------

// Array OBSTÁCULOS compartido: los obstáculos clásicos con el run del tramo
// embebido en la nota del finish (criterio PFT de Hyrox). $variant añade los
// obstáculos de Super/Beast.
$spartanObstacles = static function (string $variant) use ($exercises): array {
    $rows = [
        ['exercise' => $exercises['Bear Crawl'], 'position' => 1, 'distance' => 20],
        ['exercise' => $exercises['Monkey Bars'], 'position' => 2],
        ['exercise' => $exercises['Spear Throw'], 'position' => 3, 'reps' => 1],
        ['exercise' => $exercises['Rope Climb'], 'position' => 4, 'reps' => 1],
        ['exercise' => $exercises['Bucket Carry'], 'position' => 5, 'distance' => 50],
        ['exercise' => $exercises['Farmers Walk'], 'position' => 6, 'distance' => 100],
        ['exercise' => $exercises['Wall Walk'], 'position' => 7, 'reps' => 2],
        ['exercise' => $exercises['Box Jump'], 'position' => 8, 'reps' => 10],
    ];

    if ('super' === $variant || 'beast' === $variant) {
        $rows[] = ['exercise' => $exercises['Tire Flip'], 'position' => 9, 'reps' => 10];
        $rows[] = ['exercise' => $exercises['Bucket Carry'], 'position' => 10, 'distance' => 50];
    }

    return $rows;
};

$scalingSpartan = 'Scaled: obstáculos asistidos (mono asistido, cuerda con negativa controlada, cubo 50%), spear throw con balón medicinal, y penalización de 30 burpees reducida a 15 si el atleta está en fase de aprendizaje (registrar en notas). '
    . 'Penalización oficial: obstáculo fallado = 30 burpees.';

// Ficha 1 — Spartan Sprint Sim (5 km trail + 8 obstáculos, cap 90:00)
$wods[] = [
    'name' => 'Spartan Sprint Sim',
    'type' => 'benchmark',
    'description' => "Por tiempo · Simulador de Spartan Sprint: 5 km de trail con desnivel/barro (embebidos en las transiciones; el jugador los anota en el finish) + 8 obstáculos clásicos.\n\n"
        . "Obstáculos (orden): 1. Barbed Wire Crawl 20 m · 2. Monkey Bars · 3. Spear Throw a diana (7,6 m) · 4. Rope Climb ×1 · 5. Bucket Carry 50 m · 6. Sandbag Carry 100 m (Farmers Walk) · 7. Muro 2 m (Wall Walk ×2) · 8. Muro alto (Box Jump ×10).\n\n"
        . "Penalización oficial: obstáculo fallado = 30 burpees (anotar en el finish). timeCap 1:30:00.",
    'scaling' => $scalingSpartan,
    'elite_male' => '40:00',
    'elite_female' => '48:00',
    'advanced_male' => '55:00',
    'advanced_female' => '1:05',
    'intermediate_male' => '1:10',
    'intermediate_female' => '1:20',
    'beginner_male' => '1:25',
    'beginner_female' => '1:35',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 5400,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'bucket 30 kg, sandbag 2×22,5 kg',
    'rx_weight_female' => 'bucket 20 kg, sandbag 2×15 kg',
    'movements' => ['trail-run', 'crawl', 'monkey-bars', 'spear-throw', 'rope-climb', 'bucket-carry', 'sandbag-carry', 'walls'],
    'exercises' => $spartanObstacles('sprint'),
];

// Ficha 2 — Spartan Super Sim (10 km + 10 obstáculos, cap 3:00)
$wods[] = [
    'name' => 'Spartan Super Sim',
    'type' => 'benchmark',
    'description' => "Por tiempo · Simulador de Spartan Super: 10 km de trail + 10 obstáculos (los 8 del Sprint + Tire Flip ×10 + segundo Bucket Carry 50 m).\n\n"
        . "Penalización oficial: obstáculo fallado = 30 burpees. timeCap 3:00:00.",
    'scaling' => $scalingSpartan,
    'elite_male' => '1:20',
    'elite_female' => '1:35',
    'advanced_male' => '1:50',
    'advanced_female' => '2:10',
    'intermediate_male' => '2:20',
    'intermediate_female' => '2:45',
    'beginner_male' => '2:50',
    'beginner_female' => '3:10',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 10800,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'bucket 30 kg, sandbag 2×22,5 kg',
    'rx_weight_female' => 'bucket 20 kg, sandbag 2×15 kg',
    'movements' => ['trail-run', 'crawl', 'monkey-bars', 'spear-throw', 'rope-climb', 'bucket-carry', 'sandbag-carry', 'tire-flip', 'walls'],
    'exercises' => $spartanObstacles('super'),
];

// Ficha 3 — Spartan Beast Sim (21 km + 12 obstáculos, cap 6:00)
$wods[] = [
    'name' => 'Spartan Beast Sim',
    'type' => 'benchmark',
    'description' => "Por tiempo · Simulador de Spartan Beast: 21 km de trail + 12 obstáculos (10 del Super + segundo Tire Flip ×10 + Multi-Rig en notas).\n\n"
        . "Penalización oficial: obstáculo fallado = 30 burpees. Nutrición en carrera a partir de la 1ª hora (60-90 g carb/h). timeCap 6:00:00.",
    'scaling' => $scalingSpartan,
    'elite_male' => '2:45',
    'elite_female' => '3:15',
    'advanced_male' => '3:45',
    'advanced_female' => '4:20',
    'intermediate_male' => '4:45',
    'intermediate_female' => '5:30',
    'beginner_male' => '5:30',
    'beginner_female' => '6:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 21600,
    'timer' => null,
    'partitioning' => false,
    'rx_weight_male' => 'bucket 30 kg, sandbag 2×22,5 kg',
    'rx_weight_female' => 'bucket 20 kg, sandbag 2×15 kg',
    'movements' => ['trail-run', 'crawl', 'monkey-bars', 'spear-throw', 'rope-climb', 'bucket-carry', 'sandbag-carry', 'tire-flip', 'walls'],
    'exercises' => $spartanObstacles('beast'),
];

// ------------------------------------------------------------------
// 3. Persistir los benchmarks (idempotente por nombre)
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
