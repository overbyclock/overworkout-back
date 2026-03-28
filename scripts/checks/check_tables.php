<?php

declare(strict_types=1);
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
$tables = $conn->fetchAllAssociative('SHOW TABLES');
echo "Tablas existentes:\n";
foreach ($tables as $table) {
    $values = array_values($table);
    echo '  - '.$values[0]."\n";
}
