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

    $descriptions = [
        'Wall Push Up' => 'Ejercicio vertical con manos en la pared. Perfecto para iniciarse sin carga de peso.',
        'Incline Push Up' => 'Manos en banco elevado. Reduce la carga permitiendo progresar al push up estándar.',
        'Knee Push Up' => 'Push up con rodillas apoyadas en el suelo. Más accesible que el estándar manteniendo buena forma.',
        'Standard Push Up' => 'El clásico push up. Cuerpo recto, baja hasta que el pecho casi toca el suelo.',
        'Diamond Push Up' => 'Manos juntas formando diamante. Máximo énfasis en tríceps.',
        'Wide Push Up' => 'Manos colocadas anchas. Enfocado en pectoral y hombros.',
        'Decline Push Up' => 'Pies elevados sobre banco o step. Aumenta la carga en la parte superior del pecho.',
        'Plyometric Push Up' => 'Versión explosiva donde las manos despegan del suelo. Desarrolla potencia.',
        'Spiderman Push Up' => 'Llevas la rodilla al codo en cada repetición. Trabaja pecho y oblicuos simultáneamente.',
        'One-Arm Push Up' => 'Push up con un solo brazo. Requiere fuerza extrema de pecho, hombros y core.',
    ];

    foreach ($descriptions as $name => $description) {
        $conn->executeStatement(
            'UPDATE exercises SET description = ? WHERE name = ?',
            [$description, $name]
        );
        echo "✅ {$name}\n";
    }

    echo "\n🎉 ¡Descripciones actualizadas!\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
