<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== EJERCICIOS DE PIERNAS SIN EQUIPAMIENTO (que deberían tener) ===\n\n";

$rows = $conn->fetchAllAssociative("
    SELECT e.name, e.level, e.difficulty_rating, eq.name as equipment_name 
    FROM exercises e 
    LEFT JOIN equipments eq ON e.equipment_id = eq.id 
    WHERE e.primary_muscle_group = 'legs' 
    ORDER BY e.equipment_id IS NULL DESC, e.name
");

echo "SIN EQUIPAMIENTO (equipment_id IS NULL):\n";
foreach ($rows as $row) {
    if ($row['equipment_name'] === null) {
        $fires = str_repeat('🔥', $row['difficulty_rating']);
        echo "  ❌ {$row['name']} - {$row['level']} $fires\n";
    }
}

echo "\n\nCON EQUIPAMIENTO:\n";
foreach ($rows as $row) {
    if ($row['equipment_name'] !== null) {
        $fires = str_repeat('🔥', $row['difficulty_rating']);
        echo "  ✅ {$row['name']} - {$row['equipment_name']}\n";
    }
}

// Específicamente Nordic Curl with Band
echo "\n\n=== VERIFICACIÓN ESPECÍFICA ===\n";
$nordic = $conn->fetchAssociative("
    SELECT e.name, eq.name as equipment_name 
    FROM exercises e 
    LEFT JOIN equipments eq ON e.equipment_id = eq.id 
    WHERE e.name = 'Nordic Curl with Band'
");
if ($nordic) {
    $eq = $nordic['equipment_name'] ?: 'SIN EQUIPAMIENTO';
    echo "Nordic Curl with Band: $eq\n";
}
