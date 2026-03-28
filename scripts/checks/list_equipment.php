<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;
$conn = DriverManager::getConnection(['driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,'user'=>'juan','password'=>'1234','dbname'=>'overworkout']);
$rows = $conn->fetchAllAssociative('SELECT id, name FROM equipments ORDER BY name');
foreach ($rows as $row) {
    echo $row['id'] . ': ' . $row['name'] . "\n";
}
