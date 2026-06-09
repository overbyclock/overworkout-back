<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== Corrección de descripciones a español ===\n\n";

$repo = $em->getRepository(\App\Entity\Benchmark::class);
$benchmarks = $repo->findAll();

// Mapeo de reemplazos para descripciones
$descReplacements = [
    'For time (partition pull-ups/push-ups/squats as needed):' => 'Por tiempo (particiona pull-ups/push-ups/squats como necesites):',
    'For time (partition run and burpee pull-ups as needed):' => 'Por tiempo (particiona la carrera y los burpee pull-ups como necesites):',
    'For time (chipper acumulativo):' => 'Por tiempo (chipper acumulativo):',
    'For time:' => 'Por tiempo:',
    'For time, ' => 'Por tiempo, ',
    '5 rounds for time, each for time:' => '5 rondas por tiempo, cada una por tiempo:',
    '5 rounds for time:' => '5 rondas por tiempo:',
    '5 rounds for max reps:' => '5 rondas por máximo de reps:',
    '3 rounds for time:' => '3 rondas por tiempo:',
    '3 rounds for max reps of 1 minute at each station:' => '3 rondas por máximo de reps de 1 minuto en cada estación:',
    '7 rounds for time:' => '7 rondas por tiempo:',
    '30 rounds for time:' => '30 rondas por tiempo:',
    '21-15-9 reps for time:' => '21-15-9 reps por tiempo:',
    '10-9-8-7-6-5-4-3-2-1 reps for time:' => '10-9-8-7-6-5-4-3-2-1 reps por tiempo:',
    '9-7-5 reps for time:' => '9-7-5 reps por tiempo:',
    '50-40-30-20-10 reps for time:' => '50-40-30-20-10 reps por tiempo:',
    '75 Power Snatches for time:' => '75 Power Snatches por tiempo:',
    '30 Clean and Jerks for time:' => '30 Clean and Jerks por tiempo:',
    'AMRAP in 20 minutes:' => 'AMRAP en 20 minutos:',
    'Every minute on the minute for 30 minutes:' => 'EMOM durante 30 minutos:',
    'Tabata intervals (20s work / 10s rest × 8 rounds) of:' => 'Intervalos Tabata (20s trabajo / 10s descanso × 8 rondas) de:',
    'Score = total reps + calories.' => 'Puntuación = total de reps + calorías.',
];

// Mapeo de reemplazos para scaling options
$scalingReplacements = [
    'Sin chaleco / Half Murph' => 'Sin chaleco / Media Murph',
    "'Dirty 30' — 30 reps de cada" => "'Dirty 30' — 30 reps de cada",
    'Mantener 5 rounds y rep scheme.' => 'Mantener 5 rondas y esquema de reps.',
    'Reduce a 20 min AMRAP con run de 200m' => 'Reduce a 20 min AMRAP con carrera de 200m',
];

$updated = 0;

foreach ($benchmarks as $benchmark) {
    $name = $benchmark->getName();
    if ('Abbate' === $name) {
        continue;
    }

    $desc = $benchmark->getDescription();
    $scaling = $benchmark->getScalingOptions();
    $changed = false;

    foreach ($descReplacements as $old => $new) {
        if (str_contains($desc, $old)) {
            $desc = str_replace($old, $new, $desc);
            $changed = true;
        }
    }

    foreach ($scalingReplacements as $old => $new) {
        if (str_contains((string)$scaling, $old)) {
            $scaling = str_replace($old, $new, (string)$scaling);
            $changed = true;
        }
    }

    if ($changed) {
        $benchmark->setDescription($desc);
        $benchmark->setScalingOptions($scaling);
        echo "  ✅ {$name}\n";
        ++$updated;
    }
}

$em->flush();

echo "\n=== Total corregidos: {$updated} ===\n";
