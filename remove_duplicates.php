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
    
    // Eliminar duplicados en inglés
    $conn->executeStatement("DELETE FROM equipments WHERE name = 'pull-up bars'");
    $conn->executeStatement("DELETE FROM equipments WHERE name = 'dip bars'");
    
    echo "✅ Eliminados: pull-up bars y dip bars (duplicados)\n\n";
    
    // Mostrar equipos restantes
    $equipments = $conn->fetchAllAssociative('SELECT name, category FROM equipments ORDER BY category, name');
    
    $currentCat = '';
    foreach ($equipments as $eq) {
        if ($eq['category'] !== $currentCat) {
            $currentCat = $eq['category'];
            echo "\n" . strtoupper($currentCat) . ":\n";
        }
        echo "  - {$eq['name']}\n";
    }
    
    echo "\n🎉 ¡Base de datos limpia!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
