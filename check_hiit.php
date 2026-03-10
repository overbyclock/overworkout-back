<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;
$conn = DriverManager::getConnection(['driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,'user'=>'juan','password'=>'1234','dbname'=>'overworkout']);

echo "=== EJERCICIOS HIIT/CARDIO (full_body) ===\n\n";

$rows = $conn->fetchAllAssociative("
    SELECT name, level, difficulty_rating 
    FROM exercises 
    WHERE primary_muscle_group = 'full_body' 
    ORDER BY level, difficulty_rating, name
");

foreach ($rows as $row) {
    $fires = str_repeat('🔥', $row['difficulty_rating']);
    echo "  {$row['level']} $fires {$row['name']}\n";
}

echo "\nTotal HIIT/Cardio: " . count($rows) . "\n";
