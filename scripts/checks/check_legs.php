<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql',
    'host'=>'127.0.0.1',
    'port'=>3306,
    'user'=>'juan',
    'password'=>'1234',
    'dbname'=>'overworkout'
]);

$rows = $conn->fetchAllAssociative("
    SELECT e.name, e.level, e.difficulty_rating, eq.name as equipment_name 
    FROM exercises e 
    LEFT JOIN equipments eq ON e.equipment_id = eq.id 
    WHERE e.primary_muscle_group = 'legs' 
    ORDER BY e.level, e.difficulty_rating, e.name
");

echo "=== EJERCICIOS DE PIERNAS ===\n\n";

$currentLevel = '';
$currentRating = 0;

foreach ($rows as $row) {
    $level = strtoupper($row['level']);
    $rating = $row['difficulty_rating'];
    $fires = str_repeat('🔥', $rating);
    $eq = $row['equipment_name'] ?: 'Sin equipo';
    
    if ($level !== $currentLevel) {
        echo "\n--- $level ---\n";
        $currentLevel = $level;
    }
    
    echo "$fires {$row['name']} ($eq)\n";
}

echo "\n\nTotal: " . count($rows) . " ejercicios\n";

echo "\n=== RESUMEN POR DIFICULTAD ===\n";
$summary = $conn->fetchAllAssociative("
    SELECT level, difficulty_rating, COUNT(*) as count 
    FROM exercises 
    WHERE primary_muscle_group = 'legs' 
    GROUP BY level, difficulty_rating 
    ORDER BY level, difficulty_rating
");

foreach ($summary as $s) {
    $fires = str_repeat('🔥', $s['difficulty_rating']);
    echo "{$s['level']} $fires: {$s['count']}\n";
}
