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

    // Eliminar Archer Pike Push Up
    $conn->executeStatement("DELETE FROM exercises WHERE name = 'Archer Pike Push Up'");

    echo "✅ 'Archer Pike Push Up' eliminado\n\n";

    // Mostrar ejercicios de hombros restantes
    $shoulders = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'shoulders' ORDER BY level, difficulty_rating");

    echo '📋 HOMBROS ('.count($shoulders)."):\n";
    foreach ($shoulders as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
