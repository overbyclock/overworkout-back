<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== LIMPIANDO EJERCICIOS DE FITNESS ===\n\n";

// Lista de ejercicios a eliminar (estiramientos, movilidad, cardio ligero)
$toRemove = [
    // Foam rolling
    'Foam Rolling Quads', 'Foam Rolling Back', 'Foam Rolling IT Band', 
    'Foam Rolling Calves', 'Foam Rolling Lats', 'Lacrosse Ball Shoulder',
    'Lacrosse Ball Glutes',
    
    // Estiramientos estáticos
    'Static Stretching Hamstrings', 'Static Stretching Quadriceps',
    'Static Stretching Chest', 'Static Stretching Shoulders',
    'Child Pose', 'Downward Dog', 'Cobra Stretch', 'Pigeon Pose',
    'Butterfly Stretch', 'Frog Stretch', '90-90 Stretch', 'Hip Flexor Stretch',
    'Doorway Stretch', 'Thread the Needle', 'Cat Cow Stretch',
    
    // Movilidad suave
    'Dynamic Leg Swings', 'Arm Circles', 'Torso Twists', 'Leg Swings',
    'Band Pull Apart', 'Band Dislocates', 'Worlds Greatest Stretch',
    'Spiderman Stretch', 'Inchworm',
    
    // Cardio ligero/recuperación
    'Walking', 'Incline Walking', 'StairMaster', 'Elliptical',
    'Stationary Bike', 'Recumbent Bike', 'Rowing Light', 'Swimming',
    'Water Aerobics', 'Battle Ropes Light', 'Medicine Ball Toss',
    'Step Machine', 'VersaClimber', 'Jacob Ladder',
];

$deleted = 0;
foreach ($toRemove as $exerciseName) {
    $result = $conn->executeStatement(
        "DELETE FROM exercises WHERE name = ?",
        [$exerciseName]
    );
    if ($result > 0) {
        echo "🗑️ Eliminado: $exerciseName\n";
        $deleted++;
    }
}

echo "\n✅ Total eliminados: $deleted\n";

// Verificar totales ahora
$totalFit = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE JSON_CONTAINS(disciplines, '\"fitness\"')");
echo "Total Fitness ahora: $totalFit ejercicios\n";

// Verificar máquinas ahora
$machines = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"fitness\"') 
    AND equipment_id IN (SELECT id FROM equipments WHERE category = 'maquinas')
");
echo "Ejercicios con máquinas: $machines\n";
