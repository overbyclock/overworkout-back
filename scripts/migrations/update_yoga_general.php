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

    // Cambiar colchoneta de yoga a general
    $conn->executeStatement(
        "UPDATE equipments SET category = 'general' WHERE name = 'Colchoneta'"
    );
    echo "✅ Colchoneta cambiada de 'yoga' a 'general'\n";

    echo "\n🎉 ¡Categorías actualizadas!\n\n";

    // Mostrar equipos
    $allEquipments = $conn->fetchAllAssociative('SELECT name, category FROM equipments ORDER BY id');
    echo "📋 Equipos por categoría:\n";
    foreach ($allEquipments as $e) {
        echo "  - {$e['name']} → {$e['category']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
