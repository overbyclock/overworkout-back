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

    // Ejercicios de dominadas/pull-ups
    $pullups = [
        // PRINCIPIANTE
        ['Australian Pull Up', 'beginner', 1, 'back', 'biceps', 'Fila australiana. Barra a altura de cadera, cuerpo inclinado hacia atrás. Perfecto para iniciar el trabajo de espalda.', 'pull-up bars'],
        ['Negative Pull Up', 'beginner', 2, 'back', 'biceps', 'Saltas arriba y bajas lentamente (5 segundos). Control excéntrico para ganar fuerza.', 'pull-up bars'],
        ['Assisted Pull Up', 'beginner', 3, 'back', 'biceps', 'Con banda elástica o máquina de asistencia. Reduce el peso efectivo para progresar.', 'pull-up bars'],

        // INTERMEDIO
        ['Standard Pull Up', 'intermediate', 1, 'back', 'biceps', 'Agarre prono (palmas hacia afuera), anchura hombros. La dominada clásica por excelencia.', 'pull-up bars'],
        ['Chin Up', 'intermediate', 2, 'biceps', 'back', 'Agarre supino (palmas hacia ti). Más fácil que la prona, máximo énfasis en bíceps.', 'pull-up bars'],
        ['Wide Grip Pull Up', 'intermediate', 3, 'back', 'shoulders', 'Agarre ancho. Máximo énfasis en dorsal ancho para amplitud de espalda.', 'pull-up bars'],
        ['Close Grip Pull Up', 'intermediate', 3, 'back', 'biceps', 'Agarre cerrado. Enfocado en bíceps y trapecio, gran activación de brazos.', 'pull-up bars'],

        // EXPERTO
        ['Archer Pull Up', 'expert', 1, 'back', 'biceps', 'Un brazo se extiende lateralmente mientras tiras con el otro. Transición hacia una mano.', 'pull-up bars'],
        ['L-Sit Pull Up', 'expert', 2, 'back', 'core', 'Piernas extendidas en L durante todo el movimiento. Trabaja core y espalda simultáneamente.', 'pull-up bars'],
        ['Muscle Up', 'expert', 3, 'back', 'chest', 'Dominada explosiva que termina en fondo sobre la barra. Movimiento completo de tracción a empuje.', 'pull-up bars'],
        ['One-Arm Pull Up', 'expert', 3, 'back', 'biceps', 'Dominada con una sola mano. Máxima fuerza de espalda, bíceps y agarre.', 'pull-up bars'],
    ];

    foreach ($pullups as $pu) {
        // Verificar si ya existe
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$pu[0]]);
        if (0 === $exists) {
            $conn->executeStatement(
                'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NULL)',
                [$pu[0], $pu[1], $pu[2], $pu[3], $pu[4], $pu[5], 'https://www.youtube.com/results?search_query='.urlencode($pu[0])]
            );
            echo "✅ Creado: {$pu[0]} → {$pu[1]} (🔥{$pu[2]})\n";
        } else {
            echo "ℹ️ Ya existe: {$pu[0]}\n";
        }
    }

    echo "\n🎉 ¡Dominadas añadidas!\n\n";

    // Mostrar resumen por tipo
    $pushups = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE name LIKE '%Push Up%' ORDER BY level, difficulty_rating");
    $pullups_db = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE name NOT LIKE '%Push Up%' ORDER BY level, difficulty_rating");

    echo "📊 RESUMEN:\n\n";

    echo 'PUSH UPS ('.count($pushups)."):\n";
    foreach ($pushups as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }

    echo "\nDOMINADAS (".count($pullups_db)."):\n";
    foreach ($pullups_db as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
