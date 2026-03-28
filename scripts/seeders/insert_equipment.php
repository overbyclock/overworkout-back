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
    
    // Equipamiento básico (solo nombre ya que la tabla es simple)
    $equipments = [
        'Barra de dominadas',
        'Paralelas',
        'Mancuernas',
        'Barra olímpica',
        'Kettlebell',
        'Banco',
        'Cuerda de saltar',
        'Banda elástica',
        'Anillas',
        'Colchoneta',
    ];
    
    foreach ($equipments as $name) {
        // Verificar si ya existe
        $exists = $conn->fetchOne("SELECT COUNT(*) FROM equipments WHERE name = ?", [$name]);
        if ($exists == 0) {
            $conn->executeStatement(
                "INSERT INTO equipments (name, created_at) VALUES (?, NOW())",
                [$name]
            );
            echo "✅ Creado: {$name}\n";
        } else {
            echo "ℹ️ Ya existe: {$name}\n";
        }
    }
    
    echo "\n🎉 ¡Equipamiento añadido!\n\n";
    
    // Mostrar equipos
    $allEquipments = $conn->fetchAllAssociative('SELECT id, name FROM equipments ORDER BY id');
    echo "📋 Equipos en BD:\n";
    foreach ($allEquipments as $e) {
        echo "  - {$e['name']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
