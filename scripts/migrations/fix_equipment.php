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

    // Quitar equipamiento de todos los ejercicios
    $conn->executeStatement('UPDATE exercises SET equipment_id = NULL');

    echo "✅ Equipamiento eliminado de todos los ejercicios\n\n";

    // Verificar
    $exercises = $conn->fetchAllAssociative('SELECT name, equipment_id FROM exercises');
    foreach ($exercises as $e) {
        $eq = $e['equipment_id'] ? "ID: {$e['equipment_id']}" : 'Sin equipamiento';
        echo "  - {$e['name']}: {$eq}\n";
    }

    echo "\n🎉 ¡Listo!\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
