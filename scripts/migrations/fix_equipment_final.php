<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306,
    'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout',
]);

echo "=== CORRIGIENDO EQUIPAMIENTO ===\n\n";

// Obtener IDs de equipamientos
$equipments = [];
$eqRows = $conn->fetchAllAssociative('SELECT id, name FROM equipments');
foreach ($eqRows as $eq) {
    $equipments[$eq['name']] = $eq['id'];
}

// Mapeo de ejercicios a equipamiento (usando nombres reales de BD)
$exerciseEquipment = [
    // BARRAS - Barra olímpica para sentadillas, Barra de dominadas para nordic
    'Assisted Pistol Squat' => 'Barra de dominadas',
    'Sissy Squat' => 'Barra de dominadas',
    'Smith Machine Squat' => 'Barra olímpica',
    'Barbell Squat' => 'Barra olímpica',
    'Front Squat' => 'Barra olímpica',
    'Trap Bar Deadlift' => 'Barra olímpica',

    // Nordic curl usa barra de dominadas para anclar pies
    'Nordic Curl Negative' => 'Barra de dominadas',
    'Glute Ham Raise Negative' => 'Barra de dominadas',
    'Nordic Curl' => 'Barra de dominadas',
    'Glute Ham Raise' => 'Barra de dominadas',

    // BANCOS
    'Box Squat' => 'Banco',
    'Bulgarian Split Squat' => 'Banco',
    'Box Pistol Squat' => 'Banco',
    'Box Jump' => 'Banco',
    'Step Up' => 'Banco',
    'High Step Up' => 'Banco',
    'Depth Jump' => 'Banco',
    'Single Leg Box Jump' => 'Banco',
    'Pistol Squat to Box' => 'Banco',
    'Elevated Pistol Squat' => 'Banco',
    'Deficit Reverse Lunge' => 'Banco',

    // BANDAS
    'Nordic Curl with Band' => 'Banda elástica',

    // ANILLAS
    'Hamstring Curl on Rings' => 'Anillas',

    // MANCUERNAS
    'Dumbbell Squat' => 'Mancuernas',
    'Dumbbell Lunge' => 'Mancuernas',
    'Dumbbell Step Up' => 'Mancuernas',
    'Dumbbell Calf Raise' => 'Mancuernas',
    'Dumbbell Romanian Deadlift' => 'Mancuernas',
    'Weighted Wall Sit' => 'Mancuernas',
    'Weighted Pistol Squat' => 'Mancuernas',

    // KETTLEBELLS
    'Goblet Squat' => 'Kettlebell',
    'Kettlebell Swing' => 'Kettlebell',
    'Kettlebell Goblet Lunge' => 'Kettlebell',
    'Kettlebell Step Up' => 'Kettlebell',
    'Kettlebell Deadlift' => 'Kettlebell',

    // MÁQUINAS ESPECÍFICAS
    'Leg Press Machine' => 'Prensa de piernas',
    'Hack Squat Machine' => 'Hack squat',
    'Leg Extension Machine' => 'Extensión de cuádriceps',
    'Leg Curl Machine' => 'Curl femoral acostado',
    'Machine Calf Raise' => 'Elevación de gemelos',
];

$fixed = 0;
foreach ($exerciseEquipment as $exerciseName => $equipmentName) {
    if (!isset($equipments[$equipmentName])) {
        echo "❌ Equipamiento no encontrado: $equipmentName\n";

        continue;
    }

    $equipmentId = $equipments[$equipmentName];

    $result = $conn->executeStatement(
        'UPDATE exercises SET equipment_id = ? WHERE name = ?',
        [$equipmentId, $exerciseName]
    );

    if ($result > 0) {
        echo "✅ $exerciseName → $equipmentName\n";
        ++$fixed;
    } else {
        echo "ℹ️ No encontrado o ya actualizado: $exerciseName\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Ejercicios actualizados: $fixed\n";

// Contar cuántos tienen y cuántos no
$withEq = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'legs' AND equipment_id IS NOT NULL");
$withoutEq = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'legs' AND equipment_id IS NULL");

echo "Con equipamiento: $withEq\n";
echo "Sin equipamiento: $withoutEq (ejercicios con peso corporal)\n";

// Verificar específicamente Nordic Curl with Band
$nordic = $conn->fetchAssociative("
    SELECT eq.name as equipment_name 
    FROM exercises e 
    LEFT JOIN equipments eq ON e.equipment_id = eq.id 
    WHERE e.name = 'Nordic Curl with Band'
");
echo "\nNordic Curl with Band: ".($nordic['equipment_name'] ?: 'SIN EQUIPAMIENTO')."\n";
