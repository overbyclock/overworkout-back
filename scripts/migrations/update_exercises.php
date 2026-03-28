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

    // Borrar ejercicios que no son push ups (para centrarnos)
    $conn->executeStatement("DELETE FROM exercises WHERE name NOT LIKE '%Push Up%'");
    echo "✅ Borrados ejercicios no relacionados\n";

    // Actualizar niveles de los existentes
    $updates = [
        ['Wall Push Up', 'beginner', 1, 'Ejercicio vertical con manos en la pared. Perfecto para iniciarse sin carga de peso.'],
        ['Knee Push Up', 'beginner', 3, 'Push up con rodillas apoyadas en el suelo. Más accesible que el estándar manteniendo buena forma.'],
        ['One-Arm Push Up', 'expert', 3, 'Push up con un solo brazo. Requiere fuerza extrema de pecho, hombros y core.'],
    ];

    foreach ($updates as $update) {
        $conn->executeStatement(
            'UPDATE exercises SET level = ?, difficulty_rating = ? WHERE name = ?',
            [$update[1], $update[2], $update[0]]
        );
        echo "✅ Actualizado: {$update[0]} → {$update[1]} (🔥{$update[2]})\n";
    }

    // Insertar ejercicios faltantes
    $newExercises = [
        ['Incline Push Up', 'beginner', 2, 'chest', 'triceps', 'Manos en banco elevado. Reduce la carga permitiendo progresar al push up estándar.'],
        ['Standard Push Up', 'intermediate', 1, 'chest', 'triceps', 'El clásico push up. Cuerpo recto, baja hasta que el pecho casi toca el suelo.'],
        ['Diamond Push Up', 'intermediate', 2, 'triceps', 'chest', 'Manos juntas formando diamante. Máximo énfasis en tríceps.'],
        ['Wide Push Up', 'intermediate', 3, 'chest', 'shoulders', 'Manos colocadas anchas. Enfocado en pectoral y hombros.'],
        ['Decline Push Up', 'intermediate', 3, 'chest', 'shoulders', 'Pies elevados sobre banco o step. Aumenta la carga en la parte superior del pecho.'],
        ['Plyometric Push Up', 'expert', 1, 'chest', 'shoulders', 'Versión explosiva donde las manos despegan del suelo. Desarrolla potencia.'],
        ['Spiderman Push Up', 'expert', 2, 'chest', 'core', 'Llevas la rodilla al codo en cada repetición. Trabaja pecho y oblicuos simultáneamente.'],
    ];

    foreach ($newExercises as $ex) {
        // Verificar si ya existe
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
        if (0 === $exists) {
            $conn->executeStatement(
                'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, media, equipment_id) 
                 VALUES (?, ?, ?, ?, ?, ?, NULL)',
                [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], 'https://www.youtube.com/results?search_query='.urlencode($ex[0])]
            );
            echo "✅ Creado: {$ex[0]} → {$ex[1]} (🔥{$ex[2]})\n";
        } else {
            echo "ℹ️ Ya existe: {$ex[0]}\n";
        }
    }

    echo "\n🎉 ¡Ejercicios actualizados!\n";

    // Mostrar resumen
    $exercises = $conn->fetchAllAssociative('SELECT name, level, difficulty_rating FROM exercises ORDER BY level, difficulty_rating');
    echo "\n📋 Ejercicios en BD:\n";
    foreach ($exercises as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
