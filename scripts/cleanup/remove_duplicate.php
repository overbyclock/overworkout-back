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
    
    // Eliminar el ejercicio "push up" duplicado
    $conn->executeStatement("DELETE FROM exercises WHERE name = 'push up'");
    
    echo "✅ Ejercicio 'push up' duplicado eliminado\n\n";
    
    // Verificar lista final
    $exercises = $conn->fetchAllAssociative('SELECT name, level, difficulty_rating FROM exercises ORDER BY level, difficulty_rating');
    
    echo "📋 Ejercicios finales:\n";
    foreach ($exercises as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }
    
    echo "\n🎉 ¡Base de datos limpia!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
