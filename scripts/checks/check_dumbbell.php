<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection(['driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306, 'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout']);

$rows = $conn->fetchAllAssociative("SELECT name, equipment_id FROM exercises WHERE name LIKE '%Dumbbell%' OR name LIKE '%Weighted%'");
echo "Ejercicios con mancuernas:\n";
foreach ($rows as $row) {
    $eq = $row['equipment_id'] ? 'Con equipo' : 'SIN EQUIPO';
    echo "  - {$row['name']} ($eq)\n";
}
