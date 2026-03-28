<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== CORRIGIENDO EQUIPAMIENTO ===\n\n";

// Obtener IDs de equipamientos
$equipments = [];
$eqRows = $conn->fetchAllAssociative("SELECT id, name FROM equipments");
foreach ($eqRows as $eq) {
    $equipments[$eq['name']] = $eq['id'];
}

// Mapeo de ejercicios a equipamiento
$exerciseEquipment = [
    // BARRAS
    'Assisted Pistol Squat' => 'barras',
    'Sissy Squat' => 'barras',
    'Nordic Curl Negative' => 'barras',
    'Glute Ham Raise Negative' => 'barras',
    'Nordic Curl' => 'barras',
    'Glute Ham Raise' => 'barras',
    'Smith Machine Squat' => 'barras',
    'Barbell Squat' => 'barras',
    'Front Squat' => 'barras',
    'Trap Bar Deadlift' => 'barras',
    
    // BANCOS Y SOPORTES
    'Box Squat' => 'bancos_soportes',
    'Bulgarian Split Squat' => 'bancos_soportes',
    'Box Pistol Squat' => 'bancos_soportes',
    'Box Jump' => 'bancos_soportes',
    'Step Up' => 'bancos_soportes',
    'High Step Up' => 'bancos_soportes',
    'Depth Jump' => 'bancos_soportes',
    'Single Leg Box Jump' => 'bancos_soportes',
    'Pistol Squat to Box' => 'bancos_soportes',
    'Elevated Pistol Squat' => 'bancos_soportes',
    'Deficit Reverse Lunge' => 'bancos_soportes',
    
    // BANDAS
    'Nordic Curl with Band' => 'bandas',
    
    // ANILLAS
    'Hamstring Curl on Rings' => 'anillas',
    
    // MANCUERNAS
    'Dumbbell Squat' => 'mancuernas',
    'Dumbbell Lunge' => 'mancuernas',
    'Dumbbell Step Up' => 'mancuernas',
    'Dumbbell Calf Raise' => 'mancuernas',
    'Dumbbell Romanian Deadlift' => 'mancuernas',
    'Weighted Wall Sit' => 'mancuernas',
    'Weighted Pistol Squat' => 'mancuernas',
    
    // KETTLEBELLS
    'Goblet Squat' => 'kettlebells',
    'Kettlebell Swing' => 'kettlebells',
    'Kettlebell Goblet Lunge' => 'kettlebells',
    'Kettlebell Step Up' => 'kettlebells',
    'Kettlebell Deadlift' => 'kettlebells',
    
    // MÁQUINAS
    'Leg Press Machine' => 'maquinas',
    'Hack Squat Machine' => 'maquinas',
    'Leg Extension Machine' => 'maquinas',
    'Leg Curl Machine' => 'maquinas',
    'Machine Calf Raise' => 'maquinas',
];

foreach ($exerciseEquipment as $exerciseName => $equipmentName) {
    if (!isset($equipments[$equipmentName])) {
        echo "❌ Equipamiento no encontrado: $equipmentName\n";
        continue;
    }
    
    $equipmentId = $equipments[$equipmentName];
    
    $conn->executeStatement(
        "UPDATE exercises SET equipment_id = ? WHERE name = ?",
        [$equipmentId, $exerciseName]
    );
    
    echo "✅ $exerciseName → $equipmentName\n";
}

echo "\n=== VERIFICACIÓN ===\n";

// Contar cuántos tienen y cuántos no
$withEq = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'legs' AND equipment_id IS NOT NULL");
$withoutEq = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'legs' AND equipment_id IS NULL");

echo "Con equipamiento: $withEq\n";
echo "Sin equipamiento: $withoutEq\n";

// Verificar específicamente Nordic Curl with Band
$nordic = $conn->fetchAssociative("
    SELECT eq.name as equipment_name 
    FROM exercises e 
    LEFT JOIN equipments eq ON e.equipment_id = eq.id 
    WHERE e.name = 'Nordic Curl with Band'
");
echo "\nNordic Curl with Band: " . ($nordic['equipment_name'] ?: 'SIN EQUIPAMIENTO') . "\n";
