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

    $exercises = $conn->fetchAllAssociative('SELECT id, name, primary_muscle_group, level, difficulty_rating FROM exercises');

    echo "Ejercicios encontrados:\n";
    foreach ($exercises as $exercise) {
        echo "- {$exercise['name']} | Level: {$exercise['level']} | Fuego: {$exercise['difficulty_rating']}\n";
    }

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
