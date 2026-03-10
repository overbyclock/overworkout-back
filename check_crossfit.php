<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== EJERCICIOS DE CROSSFIT ===\n\n";

// Obtener ejercicios de crossfit ordenados
$rows = $conn->fetchAllAssociative("
    SELECT name, level, difficulty_rating, primary_muscle_group, equipment_id
    FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"crossfit\"')
    ORDER BY level, difficulty_rating, primary_muscle_group, name
");

echo "Total ejercicios CrossFit: " . count($rows) . "\n\n";

// Agrupar por nivel y grupo muscular
$byLevel = [];
$byMuscle = [];

foreach ($rows as $row) {
    $level = $row['level'];
    $muscle = $row['primary_muscle_group'];
    $fires = str_repeat('🔥', $row['difficulty_rating']);
    
    if (!isset($byLevel[$level])) $byLevel[$level] = [];
    if (!isset($byLevel[$level][$muscle])) $byLevel[$level][$muscle] = [];
    $byLevel[$level][$muscle][] = $fires . " " . $row['name'];
    
    if (!isset($byMuscle[$muscle])) $byMuscle[$muscle] = ['beginner' => 0, 'intermediate' => 0, 'expert' => 0];
    $byMuscle[$muscle][$level]++;
}

// Mostrar por nivel
foreach (['beginner', 'intermediate', 'expert'] as $level) {
    if (!isset($byLevel[$level])) continue;
    echo "--- " . strtoupper($level) . " ---\n";
    foreach ($byLevel[$level] as $muscle => $exercises) {
        echo "\n$muscle (" . count($exercises) . "):\n";
        foreach (array_slice($exercises, 0, 8) as $ex) {
            echo "  $ex\n";
        }
        if (count($exercises) > 8) echo "  ... y " . (count($exercises) - 8) . " más\n";
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

// Revisar grupos con pocos ejercicios
foreach ($byMuscle as $muscle => $levels) {
    $total = $levels['beginner'] + $levels['intermediate'] + $levels['expert'];
    if ($total < 5) {
        echo "⚠️  $muscle: Solo $total ejercicios (recomendado: 5+)\n";
    }
}

// Verificar si hay suficientes ejercicios de olimpicos
$olympic = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"crossfit\"') 
    AND (name LIKE '%Snatch%' OR name LIKE '%Clean%' OR name LIKE '%Jerk%' OR name LIKE '%Clean and Jerk%')
");
if ($olympic < 5) {
    echo "⚠️  Movimientos olímpicos: Solo $olympic ejercicios (CrossFit necesita más)\n";
}

// Verificar kettlebell
$kb = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"crossfit\"') 
    AND equipment_id = (SELECT id FROM equipments WHERE name = 'Kettlebell')
");
if ($kb < 5) {
    echo "⚠️  Kettlebell: Solo $kb ejercicios\n";
}

echo "\nAnálisis completado.\n";
