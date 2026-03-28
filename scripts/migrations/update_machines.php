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

    // Actualizar nombres genéricos a específicos
    $updates = [
        ['Máquina de pecho', 'Press de pecho', 'Máquina de press de pecho guiado. Movimiento vertical con agarre prono para trabajo de pectoral.'],
        ['Máquina de piernas', 'Extensión de cuádriceps', 'Máquina de extensión de cuádriceps sentado. Aislamiento perfecto para la parte frontal del muslo.'],
        ['Máquina de espalda', 'Jalón al pecho', 'Máquina de jalón al pecho (lat pulldown). Trabajo de dorsal ancho con barra ancha.'],
        ['Máquina de hombros', 'Press militar', 'Máquina de press militar guiado. Press de hombros vertical con respaldo.'],
    ];

    foreach ($updates as $update) {
        $conn->executeStatement(
            'UPDATE equipments SET name = ?, description = ? WHERE name = ?',
            [$update[1], $update[2], $update[0]]
        );
        echo "✅ {$update[0]} → {$update[1]}\n";
    }

    echo "\n🎉 ¡Nombres actualizados!\n\n";

    // Mostrar máquinas
    $machines = $conn->fetchAllAssociative("SELECT name, description FROM equipments WHERE category = 'maquinas'");
    echo "MÁQUINAS:\n";
    foreach ($machines as $m) {
        echo "  - {$m['name']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
