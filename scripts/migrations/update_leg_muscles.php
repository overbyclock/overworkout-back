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

    // Mapeo de ejercicios a músculo secundario correcto
    $updates = [
        // Glúteos
        ['Air Squat', 'glutes'],
        ['Box Squat', 'glutes'],
        ['Stationary Lunge', 'glutes'],
        ['Reverse Lunge', 'glutes'],
        ['Walking Lunge', 'glutes'],
        ['Side Lunge', 'adductors'],  // Aductores en sentadilla lateral
        ['Glute Bridge', 'glutes'],
        ['Single Leg Glute Bridge', 'glutes'],
        ['Donkey Kick', 'glutes'],
        ['Cossack Squat', 'adductors'],
        ['Bulgarian Split Squat', 'glutes'],
        ['Curtsy Lunge', 'glutes'],
        ['Step Up', 'glutes'],
        ['High Step Up', 'glutes'],

        // Gemelos/pantorrilla
        ['Jump Squat', 'calves'],
        ['Box Jump', 'calves'],
        ['Broad Jump', 'calves'],
        ['Single Leg Box Jump', 'calves'],
        ['Depth Jump', 'calves'],
        ['Tuck Jump', 'calves'],
    ];

    foreach ($updates as $update) {
        $conn->executeStatement(
            'UPDATE exercises SET secondary_muscle_group = ? WHERE name = ?',
            [$update[1], $update[0]]
        );
        echo "✅ {$update[0]} → {$update[1]}\n";
    }

    echo "\n🎉 ¡Músculos secundarios actualizados!\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
