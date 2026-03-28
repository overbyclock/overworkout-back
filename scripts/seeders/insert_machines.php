<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'juan',
    'password' => '1234',
    'dbname' => 'overworkout',
];

try {
    $conn = DriverManager::getConnection($connectionParams);

    // Máquinas de gimnasio
    $machines = [
        ['Cinta de correr', 'maquinas', 'directions_run', 'Máquina de cardio para correr/caminar en casa. Control de velocidad e inclinación.', null],
        ['Bicicleta estática', 'maquinas', 'pedal_bike', 'Bicicleta fija para cardio de bajo impacto. Ideal para calentar o sesiones LISS.', null],
        ['Elíptica', 'maquinas', 'directions_run', 'Máquina de cardio de bajo impacto que simula caminar/correr sin golpes en las articulaciones.', null],
        ['Remoergómetro', 'maquinas', 'rowing', 'Máquina de remo para cardio y trabajo de espalda completo. Movimiento pull completo.', null],
        ['Máquina de pecho', 'maquinas', 'fitness_center', 'Press de pecho guiado con poleas o discos. Movimiento controlado y seguro.', null],
        ['Máquina de piernas', 'maquinas', 'fitness_center', 'Extensión de cuádriceps y femorales en máquina guiada. Aislamiento perfecto.', null],
        ['Máquina de espalda', 'maquinas', 'fitness_center', 'Remo sentado o polea alta guiada. Trabajo de dorsal con trayectoria fija.', null],
        ['Máquina de hombros', 'maquinas', 'fitness_center', 'Press militar guiado para hombros. Movimiento vertical controlado.', null],
    ];

    foreach ($machines as $machine) {
        // Verificar si ya existe
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM equipments WHERE name = ?', [$machine[0]]);
        if (0 === $exists) {
            $conn->executeStatement(
                'INSERT INTO equipments (name, category, icon, description, weight, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
                [$machine[0], $machine[1], $machine[2], $machine[3], $machine[4]]
            );
            echo "✅ Creado: {$machine[0]}\n";
        } else {
            echo "ℹ️ Ya existe: {$machine[0]}\n";
        }
    }

    echo "\n🎉 ¡Máquinas añadidas!\n\n";

    // Mostrar resumen
    $categories = ['barras', 'pesos_libres', 'bancos_soportes', 'accesorios', 'maquinas'];
    foreach ($categories as $cat) {
        $items = $conn->fetchAllAssociative('SELECT name FROM equipments WHERE category = ?', [$cat]);
        if (count($items) > 0) {
            echo strtoupper($cat).' ('.count($items)."):\n";
            foreach ($items as $item) {
                echo "  - {$item['name']}\n";
            }
            echo "\n";
        }
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
