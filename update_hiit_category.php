<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== ACTUALIZANDO HIIT COMO GRUPO MUSCULAR ===\n\n";

// Cambiar todos los ejercicios full_body que son de disciplina HIIT a grupo muscular HIIT
$result = $conn->executeStatement("
    UPDATE exercises 
    SET primary_muscle_group = 'hiit',
        secondary_muscle_group = 'full_body'
    WHERE primary_muscle_group = 'full_body'
");

echo "✅ Actualizados $result ejercicios de full_body a HIIT\n\n";

// Verificar
$count = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'hiit'");
echo "Total ejercicios HIIT: $count\n";
