<?php
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'juan',
    'password' => '1234',
    'dbname' => 'overworkout',
]);

$columns = $conn->fetchAllAssociative('DESCRIBE equipments');
foreach ($columns as $col) {
    echo $col['Field'] . ' - ' . $col['Type'] . PHP_EOL;
}
