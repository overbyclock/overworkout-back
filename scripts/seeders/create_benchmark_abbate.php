<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Entity\Benchmark;
use App\Entity\BenchmarkExercise;
use App\Entity\Exercises;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env.local');

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

/** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
$doctrine = $kernel->getContainer()->get('doctrine');
$em = $doctrine->getManager();

// 1. Buscar o crear ejercicio "Run"
$runRepo = $em->getRepository(Exercises::class);
$run = $runRepo->findOneBy(['name' => 'Run']);

if (null === $run) {
    echo "Creando ejercicio 'Run'...\n";
    $run = new Exercises();
    $run->setName('Run');
    $run->setPrimaryMuscleGroup(MuscleGroup::FULL_BODY);
    // secondaryMuscleGroup se deja null por defecto
    $run->setLevel(Levels::NOLEVEL);
    $run->setDifficultyRating(1);
    $em->persist($run);
    $em->flush();
    echo "Ejercicio 'Run' creado con id: {$run->getId()}\n";
} else {
    echo "Ejercicio 'Run' ya existe con id: {$run->getId()}\n";
}

// 2. Obtener Clean and Jerk
$cleanJerk = $runRepo->findOneBy(['name' => 'Clean and Jerk']);
if (null === $cleanJerk) {
    fwrite(STDERR, "ERROR: No se encontró el ejercicio 'Clean and Jerk'\n");
    exit(1);
}
echo "Ejercicio 'Clean and Jerk' encontrado con id: {$cleanJerk->getId()}\n";

// 3. Crear benchmark Abbate
$benchmarkRepo = $em->getRepository(Benchmark::class);
$existing = $benchmarkRepo->findOneBy(['name' => 'Abbate']);

if (null !== $existing) {
    echo "Benchmark 'Abbate' ya existe con id: {$existing->getId()}. Limpiando ejercicios previos...\n";
    $existing->clearExercises();
    $em->flush();
    $benchmark = $existing;
} else {
    echo "Creando benchmark 'Abbate'...\n";
    $benchmark = new Benchmark();
    $benchmark->setName('Abbate');
    $benchmark->setType('hero');
    $em->persist($benchmark);
}

$benchmark->setDescription(
    "Por tiempo (time cap recomendado: 30-40 minutos):\n\n"
    . "1. Corre 1.600 metros\n"
    . "2. 21 Clean & Jerk — 70 kg (hombre) / 50 kg (mujer)\n"
    . "3. Corre 800 metros\n"
    . "4. 21 Clean & Jerk — 70 kg (hombre) / 50 kg (mujer)\n"
    . "5. Corre 1.600 metros\n\n"
    . "Escalado sugerido (Intermediate): Clean & Jerk 52,5 kg / 35 kg\n"
    . "Escalado sugerido (Beginner): Distancia reducida a la mitad, Clean & Jerk 30 kg / 20 kg (o mancuernas)\n\n"
    . "Hero WOD dedicado al Sargento del Cuerpo de Marines de EE.UU. Matthew T. Abbate, "
    . "de 26 años, fallecido el 2 de diciembre de 2010 en la provincia de Helmand, Afganistán, "
    . "durante operaciones de combate."
);

$benchmark->setEliteTimeMale('25:00');
$benchmark->setEliteTimeFemale('30:00');
$benchmark->setAdvancedTimeMale('35:00');
$benchmark->setAdvancedTimeFemale('40:00');
$benchmark->setIntermediateTimeMale('45:00');
$benchmark->setIntermediateTimeFemale('50:00');
$benchmark->setBeginnerTimeMale('55:00');
$benchmark->setBeginnerTimeFemale('60:00');

$benchmark->setScalingOptions(
    "RX: 21 Clean & Jerk @ 70/50 kg + runs completos\n"
    . "Intermediate: 21 Clean & Jerk @ 52,5/35 kg + runs completos\n"
    . "Beginner: 15 Clean & Jerk @ 30/20 kg (o mancuernas) + runs a 800m/400m/800m"
);

$em->flush();

// 4. Crear ejercicios del benchmark
$exercisesData = [
    ['exercise' => $run, 'position' => 1, 'distanceMeters' => 1600, 'reps' => null],
    ['exercise' => $cleanJerk, 'position' => 2, 'distanceMeters' => null, 'reps' => 21, 'rxWeightMale' => '70kg', 'rxWeightFemale' => '50kg'],
    ['exercise' => $run, 'position' => 3, 'distanceMeters' => 800, 'reps' => null],
    ['exercise' => $cleanJerk, 'position' => 4, 'distanceMeters' => null, 'reps' => 21, 'rxWeightMale' => '70kg', 'rxWeightFemale' => '50kg'],
    ['exercise' => $run, 'position' => 5, 'distanceMeters' => 1600, 'reps' => null],
];

foreach ($exercisesData as $data) {
    $be = new BenchmarkExercise();
    $be->setBenchmark($benchmark);
    $be->setExercise($data['exercise']);
    $be->setPosition($data['position']);
    $be->setReps($data['reps']);
    $be->setDistanceMeters($data['distanceMeters']);
    $be->setRxWeightMale($data['rxWeightMale'] ?? null);
    $be->setRxWeightFemale($data['rxWeightFemale'] ?? null);
    $em->persist($be);
}

$em->flush();

echo "\n✅ Benchmark 'Abbate' creado/actualizado correctamente.\n";
echo "   ID: {$benchmark->getId()}\n";
echo "   Ejercicios asociados: " . count($exercisesData) . "\n";
