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

    // Insertar Deficit Handstand Push Up
    $conn->executeStatement(
        'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        [
            'Deficit Handstand Push Up',
            'intermediate',
            3,
            'shoulders',
            'triceps',
            'Manos apoyadas en paralelas, libros o bloques elevados. Aumenta el rango de recorrido: bajas más allá del nivel de las manos, tocando el suelo con la cabeza entre los bloques. Mayor profundidad = más trabajo de hombros y trapecio.',
            'https://www.youtube.com/results?search_query='.urlencode('Deficit Handstand Push Up'),
            null,
        ]
    );

    echo "✅ Deficit Handstand Push Up añadido → intermediate (🔥🔥🔥)\n\n";

    // Mostrar ejercicios de hombros actualizados
    $shoulders = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'shoulders' ORDER BY level, difficulty_rating");

    echo '📋 HOMBROS ('.count($shoulders)."):\n";
    foreach ($shoulders as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
