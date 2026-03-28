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

    // Balón medicinal
    $conn->executeStatement(
        'INSERT INTO equipments (name, category, icon, description, weight, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
        ['Balón medicinal', 'pesos_libres', 'sports_baseball', 'Balón medicinal con peso (5-9kg). Para lanzamientos, slams, sentadillas con peso y trabajo de core funcional.', null]
    );

    echo "✅ Creado: Balón medicinal\n\n";

    // Mostrar Pesos Libres
    $items = $conn->fetchAllAssociative("SELECT name FROM equipments WHERE category = 'pesos_libres'");
    echo 'PESOS LIBRES ('.count($items)."):\n";
    foreach ($items as $item) {
        echo "  - {$item['name']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
