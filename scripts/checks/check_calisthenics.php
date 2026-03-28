<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306,
    'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout',
]);

echo "=== EJERCICIOS DE CALISTENIA ===\n\n";

// Obtener ejercicios de calistenia ordenados
$rows = $conn->fetchAllAssociative("
    SELECT name, level, difficulty_rating, primary_muscle_group, equipment_id
    FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"calisthenics\"')
    ORDER BY level, difficulty_rating, primary_muscle_group, name
");

echo 'Total ejercicios calistenia: '.count($rows)."\n\n";

// Agrupar por nivel y grupo muscular
$byLevel = [];
$byMuscle = [];

foreach ($rows as $row) {
    $level = $row['level'];
    $muscle = $row['primary_muscle_group'];
    $fires = str_repeat('🔥', $row['difficulty_rating']);

    if (!isset($byLevel[$level])) {
        $byLevel[$level] = [];
    }
    if (!isset($byLevel[$level][$muscle])) {
        $byLevel[$level][$muscle] = [];
    }
    $byLevel[$level][$muscle][] = $fires.' '.$row['name'];

    if (!isset($byMuscle[$muscle])) {
        $byMuscle[$muscle] = ['beginner' => 0, 'intermediate' => 0, 'expert' => 0];
    }
    ++$byMuscle[$muscle][$level];
}

// Mostrar por nivel
foreach (['beginner', 'intermediate', 'expert'] as $level) {
    if (!isset($byLevel[$level])) {
        continue;
    }
    echo '--- '.strtoupper($level)." ---\n";
    foreach ($byLevel[$level] as $muscle => $exercises) {
        echo "\n$muscle (".count($exercises)."):\n";
        foreach (array_slice($exercises, 0, 10) as $ex) {
            echo "  $ex\n";
        }
        if (count($exercises) > 10) {
            echo '  ... y '.(count($exercises) - 10)." más\n";
        }
    }
    echo "\n";
}

echo "\n=== COBERTURA POR GRUPO MUSCULAR Y NIVEL ===\n\n";
foreach ($byMuscle as $muscle => $levels) {
    $total = $levels['beginner'] + $levels['intermediate'] + $levels['expert'];
    echo sprintf("%-15s | 🔥: %2d | 🔥🔥: %2d | 🔥🔥🔥: %2d | Total: %3d\n",
        $muscle,
        $levels['beginner'],
        $levels['intermediate'],
        $levels['expert'],
        $total
    );
}

// Detectar carencias
echo "\n=== POSIBLES CARENIAS ===\n\n";

// Pierna principiante - revisar si hay suficientes
$legBeginner = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"calisthenics\"') 
    AND primary_muscle_group IN ('legs', 'glutes', 'hamstrings', 'calves')
    AND level = 'beginner'
");
if ($legBeginner < 10) {
    echo "⚠️  Pierna principiante: Solo $legBeginner ejercicios (recomendado: 10+)\n";
}

// Core experto - revisar si hay suficientes
$coreExpert = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"calisthenics\"') 
    AND primary_muscle_group = 'core'
    AND level = 'expert'
");
if ($coreExpert < 5) {
    echo "⚠️  Core experto: Solo $coreExpert ejercicios (recomendado: 5+)\n";
}

// Hombros - revisar cobertura
$shoulders = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"calisthenics\"') 
    AND primary_muscle_group = 'shoulders'
");
if ($shoulders < 10) {
    echo "⚠️  Hombros: Solo $shoulders ejercicios en total (recomendado: 10+)\n";
}

echo "\nAnálisis completado.\n";
