<?php
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'user'     => 'juan',
    'password' => '1234',
    'dbname'   => 'overworkout',
];

try {
    $conn = DriverManager::getConnection($connectionParams);
    
    // Máquinas específicas de CrossFit
    $machines = [
        ['Assault Bike', 'maquinas', 'pedal_bike', 'Bicicleta estática con ventilador de aire y mangos para brazos. Resistencia progresiva cuanto más rápido pedalees. Full body cardio intenso.', null],
        ['SkiErg', 'maquinas', 'fitness_center', 'Máquina de esquí de fondo con dos mangos. Tiras hacia abajo simulando el movimiento de esquí. Trabajo de espalda, hombros y core.', null],
    ];
    
    foreach ($machines as $machine) {
        // Verificar si ya existe
        $exists = $conn->fetchOne("SELECT COUNT(*) FROM equipments WHERE name = ?", [$machine[0]]);
        if ($exists == 0) {
            $conn->executeStatement(
                "INSERT INTO equipments (name, category, icon, description, weight, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                [$machine[0], $machine[1], $machine[2], $machine[3], $machine[4]]
            );
            echo "✅ Creado: {$machine[0]}\n";
        } else {
            echo "ℹ️ Ya existe: {$machine[0]}\n";
        }
    }
    
    echo "\n🎉 ¡Máquinas de CrossFit añadidas!\n\n";
    
    // Mostrar máquinas
    $machines = $conn->fetchAllAssociative("SELECT name FROM equipments WHERE category = 'maquinas' ORDER BY name");
    echo "MÁQUINAS (" . count($machines) . "):\n";
    foreach ($machines as $m) {
        echo "  - {$m['name']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
