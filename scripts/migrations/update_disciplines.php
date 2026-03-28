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

    // Asignar disciplinas a los ejercicios
    $disciplinesMap = [
        // Push Ups - principalmente calistenia pero también crossfit y fitness
        'Wall Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Incline Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Knee Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Standard Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Diamond Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Wide Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Decline Push Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Plyometric Push Up' => ['calisthenics', 'crossfit'],
        'Spiderman Push Up' => ['calisthenics', 'crossfit'],
        'One-Arm Push Up' => ['calisthenics'],

        // Pull Ups - universales
        'Australian Pull Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Negative Pull Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Assisted Pull Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Standard Pull Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Chin Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Wide Grip Pull Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Close Grip Pull Up' => ['calisthenics', 'crossfit', 'fitness'],
        'Archer Pull Up' => ['calisthenics'],
        'L-Sit Pull Up' => ['calisthenics', 'crossfit'],
        'Muscle Up' => ['calisthenics', 'crossfit'],
        'One-Arm Pull Up' => ['calisthenics'],

        // Hombros - mayormente calistenia
        'Pike Push Up' => ['calisthenics', 'fitness'],
        'Wall Handstand Hold' => ['calisthenics'],
        'Elevated Pike Push Up' => ['calisthenics', 'fitness'],
        'Handstand Push Up' => ['calisthenics', 'crossfit'],
        'Pseudo Planche Push Up' => ['calisthenics'],
        'Deficit Handstand Push Up' => ['calisthenics'],
        'Freestanding Handstand Push Up' => ['calisthenics'],
        '90 Degree Push Up' => ['calisthenics'],
        'Planche Push Up' => ['calisthenics'],
        'One-Arm Handstand Push Up' => ['calisthenics'],
    ];

    foreach ($disciplinesMap as $name => $disciplines) {
        $json = json_encode($disciplines);
        $conn->executeStatement(
            'UPDATE exercises SET disciplines = ? WHERE name = ?',
            [$json, $name]
        );
        echo "✅ {$name} → ".implode(', ', $disciplines)."\n";
    }

    echo "\n🎉 ¡Disciplinas actualizadas!\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
