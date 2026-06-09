<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

use App\Enum\BenchmarkFormat;

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();
$repo = $em->getRepository(\App\Entity\Benchmark::class);

echo "=== Actualizando formatos de benchmarks ===\n\n";

$configs = [
    // Hero WODs
    'Murph'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 4800, 'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => true,  'timerConfig' => null],
    'DT'         => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Michael'    => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 3,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Ryan'       => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'JT'         => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 600,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Randy'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 900,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Roy'        => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Jason'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 3600,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Badger'     => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 3,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Clovis'     => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 8400,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => true,  'timerConfig' => null],
    'Holleyman'  => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 30,   'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Kalsu'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => ['emomPenalty' => ['exercise' => 'Burpee', 'reps' => 5, 'intervalSeconds' => 60]]],
    'Tommy V'    => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 1800,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Zachary'    => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 2400,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Abbate'     => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 2400,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],

    // Girl WODs
    'Fran'       => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 600,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Grace'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 600,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Helen'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 3,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Diane'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 600,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Elizabeth'  => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 900,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Amanda'     => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 600,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Mary'       => ['format' => BenchmarkFormat::AMRAP,     'rounds' => null, 'timeCapSeconds' => 1200,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Cindy'      => ['format' => BenchmarkFormat::AMRAP,     'rounds' => null, 'timeCapSeconds' => 1200,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Annie'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 900,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Angie'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 2400,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Barbara'    => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => 180,  'partitioningAllowed' => false, 'timerConfig' => null],
    'Chelsea'    => ['format' => BenchmarkFormat::EMOM,      'rounds' => 30,   'timeCapSeconds' => 1800,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Eva'        => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Jackie'     => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 1200,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Karen'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 900,   'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Kelly'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Linda'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => null, 'timeCapSeconds' => 2400,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Nancy'      => ['format' => BenchmarkFormat::FOR_TIME,  'rounds' => 5,    'timeCapSeconds' => null,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Nicole'     => ['format' => BenchmarkFormat::AMRAP,     'rounds' => null, 'timeCapSeconds' => 1200,  'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],

    // Otros benchmarks
    'Fight Gone Bad' => ['format' => BenchmarkFormat::INTERVAL_STATIONS, 'rounds' => 3, 'timeCapSeconds' => 1020, 'restBetweenRoundsSeconds' => 60, 'partitioningAllowed' => false, 'timerConfig' => ['stations' => 5, 'stationDurationSeconds' => 60]],
    'Filthy 50'      => ['format' => BenchmarkFormat::CHIPPER, 'rounds' => null, 'timeCapSeconds' => 3600, 'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'The Seven'      => ['format' => BenchmarkFormat::FOR_TIME, 'rounds' => 7, 'timeCapSeconds' => null, 'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Lynne'          => ['format' => BenchmarkFormat::FOR_TIME, 'rounds' => 5, 'timeCapSeconds' => null, 'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => null],
    'Tabata Something' => ['format' => BenchmarkFormat::TABATA, 'rounds' => null, 'timeCapSeconds' => 960, 'restBetweenRoundsSeconds' => null, 'partitioningAllowed' => false, 'timerConfig' => ['workSeconds' => 20, 'restSeconds' => 10, 'roundsPerExercise' => 8]],
];

$updated = 0;

foreach ($configs as $name => $cfg) {
    $benchmark = $repo->findOneBy(['name' => $name]);
    if (null === $benchmark) {
        echo "  ⚠️ No encontrado: {$name}\n";
        continue;
    }

    $benchmark->setFormat($cfg['format']);
    $benchmark->setRounds($cfg['rounds']);
    $benchmark->setTimeCapSeconds($cfg['timeCapSeconds']);
    $benchmark->setRestBetweenRoundsSeconds($cfg['restBetweenRoundsSeconds']);
    $benchmark->setPartitioningAllowed($cfg['partitioningAllowed']);
    $benchmark->setTimerConfig($cfg['timerConfig']);

    echo "  ✅ {$name} → {$cfg['format']->value}\n";
    ++$updated;
}

$em->flush();

echo "\n=== Total actualizados: {$updated} ===\n";
