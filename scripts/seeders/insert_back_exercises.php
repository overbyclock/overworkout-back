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

    // Variantes de dominadas para espalda
    $exercises = [
        // PRINCIPIANTE - Australian Rows (fila australiana)
        ['Australian Pull Up - Wide', 'beginner', 1, 'back', 'biceps', 'Fila australiana con agarre ancho. Barra a altura de cadera, cuerpo inclinado, agarre prono ancho. Enfoque en dorsal ancho.', 'barras'],
        ['Australian Pull Up - Normal', 'beginner', 1, 'back', 'biceps', 'Fila australiana con agarre normal prono. Barra a altura de cadera, agarre a ancho de hombros.', 'barras'],
        ['Australian Pull Up - Close', 'beginner', 2, 'back', 'biceps', 'Fila australiana con agarre cerrado. Manos juntas, mayor énfasis en bíceps y trapecio.', 'barras'],
        ['Australian Pull Up - Supino', 'beginner', 2, 'back', 'biceps', 'Fila australiana con agarre supino (palmas hacia ti). Más fácil, énfasis en bíceps.', 'barras'],
        ['Australian Pull Up - Neutro', 'beginner', 2, 'back', 'biceps', 'Fila australiana con agarre neutro (palmas enfrentadas). Usando anillas o barra especial. Menos tensión en muñecas.', 'barras'],

        // NEGATIVAS
        ['Negative Pull Up - Wide', 'beginner', 2, 'back', 'biceps', 'Dominada negativa con agarre ancho. Saltas arriba y bajas lentamente (5 segundos). Preparación para dominada completa.', 'barras'],
        ['Negative Pull Up - Normal', 'beginner', 2, 'back', 'biceps', 'Dominada negativa con agarre normal prono. Control excéntrico para ganar fuerza.', 'barras'],
        ['Negative Pull Up - Supino', 'beginner', 3, 'back', 'biceps', 'Dominada negativa con agarre supino. Más fácil que la prona, ideal para progresar.', 'barras'],
        ['Negative Pull Up - Neutro', 'beginner', 3, 'back', 'biceps', 'Dominada negativa con agarre neutro. Posición natural de las muñecas.', 'barras'],

        // ASISTIDAS
        ['Assisted Pull Up - Wide', 'beginner', 3, 'back', 'biceps', 'Dominada asistida con agarre ancho. Con banda elástica o máquina de asistencia.', 'barras'],
        ['Assisted Pull Up - Normal', 'beginner', 3, 'back', 'biceps', 'Dominada asistida con agarre normal. Reduce el peso efectivo.', 'barras'],
        ['Assisted Pull Up - Close', 'beginner', 3, 'back', 'biceps', 'Dominada asistida con agarre cerrado. Más fácil que la ancha.', 'barras'],
        ['Assisted Pull Up - Supino', 'beginner', 3, 'back', 'biceps', 'Dominada asistida con agarre supino (chin up). La variante más fácil.', 'barras'],

        // INTERMEDIO - DOMINADAS COMPLETAS
        ['Wide Grip Pull Up', 'intermediate', 1, 'back', 'shoulders', 'Dominada con agarre ancho prono. Palmas hacia afuera, manos más allá del ancho de hombros. Máximo énfasis en dorsal ancho.', 'barras'],
        ['Standard Pull Up', 'intermediate', 1, 'back', 'biceps', 'Dominada estándar con agarre prono a ancho de hombros. El clásico pull up.', 'barras'],
        ['Close Grip Pull Up', 'intermediate', 2, 'back', 'biceps', 'Dominada con agarre cerrado prono. Manos juntas o casi juntas. Más énfasis en bíceps.', 'barras'],
        ['Chin Up', 'intermediate', 1, 'back', 'biceps', 'Dominada con agarre supino (palmas hacia ti). Ancho de hombros. Más fácil que prono, máximo bíceps.', 'barras'],
        ['Close Grip Chin Up', 'intermediate', 2, 'back', 'biceps', 'Dominada con agarre supino cerrado. Manos juntas, énfasis extremo en bíceps.', 'barras'],
        ['Neutral Grip Pull Up', 'intermediate', 1, 'back', 'biceps', 'Dominada con agarre neutro (palmas enfrentadas). Posición natural, menos tensión en muñecas. Usando anillas o barra especial.', 'barras'],
        ['Close Neutral Grip Pull Up', 'intermediate', 2, 'back', 'biceps', 'Dominada con agarre neutro cerrado. Mayor rango de movimiento en bíceps.', 'barras'],

        // INTERMEDIO - MIX GRIP
        ['Mixed Grip Pull Up', 'intermediate', 2, 'back', 'biceps', 'Dominada con agarre mixto. Una mano prona, otra supina. Combina beneficios de ambos agarres.', 'barras'],

        // EXPERTO - AVANZADAS
        ['Archer Pull Up - Wide', 'expert', 2, 'back', 'biceps', 'Dominada arquero con agarre ancho. Un brazo se extiende lateralmente mientras tiras. Transición a una mano.', 'barras'],
        ['Archer Pull Up - Normal', 'expert', 2, 'back', 'biceps', 'Dominada arquero con agarre normal. Alternas el énfasis entre brazos.', 'barras'],
        ['One Arm Pull Up', 'expert', 3, 'back', 'biceps', 'Dominada con una sola mano. Máxima fuerza de espalda, bíceps y agarre. Agarre prono.', 'barras'],
        ['One Arm Chin Up', 'expert', 3, 'back', 'biceps', 'Dominada con una mano agarre supino. Ligeramente más fácil que la prona.', 'barras'],
        ['Muscle Up - Wide', 'expert', 3, 'back', 'chest', 'Muscle up con agarre ancho. Dominada explosiva que termina en fondo. Mayor dificultad.', 'barras'],
        ['Muscle Up - Normal', 'expert', 3, 'back', 'chest', 'Muscle up estándar. Transición de tracción a empuje en un movimiento fluido.', 'barras'],
        ['Muscle Up - Close', 'expert', 3, 'back', 'chest', 'Muscle up con agarre cerrado. Más difícil que el ancho, mayor énfasis en tríceps.', 'barras'],
        ['L-Sit Pull Up - Wide', 'expert', 3, 'back', 'core', 'Dominada en L con agarre ancho. Piernas extendidas formando L. Trabaja espalda y core simultáneamente.', 'barras'],
        ['L-Sit Pull Up - Normal', 'expert', 3, 'back', 'core', 'Dominada en L estándar. Core + espalda en un ejercicio.', 'barras'],
        ['L-Sit Pull Up - Close', 'expert', 3, 'back', 'core', 'Dominada en L con agarre cerrado. Mayor dificultad por el equilibrio.', 'barras'],
        ['Commando Pull Up', 'expert', 2, 'back', 'biceps', 'Dominada comando. Agarre paralelo a la barra (lateral), alternando lados. Estilo escalada.', 'barras'],
        ['Around The World', 'expert', 3, 'back', 'shoulders', 'Dominada circular. Recorrido frontal a lateral, movimiento complejo de hombros.', 'barras'],
    ];

    foreach ($exercises as $ex) {
        // Verificar si ya existe
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
        if (0 === $exists) {
            $disciplines = json_encode(['calisthenics', 'crossfit', 'fitness']);
            $conn->executeStatement(
                'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)',
                [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query='.urlencode($ex[0]), $ex[6], $disciplines]
            );
            echo "✅ {$ex[0]}\n";
        } else {
            echo "ℹ️ Ya existe: {$ex[0]}\n";
        }
    }

    echo "\n🎉 ¡Ejercicios de espalda añadidos!\n\n";

    // Contar por nivel
    $levels = $conn->fetchAllAssociative("SELECT level, COUNT(*) as count FROM exercises WHERE primary_muscle_group = 'back' GROUP BY level");
    echo "Dominadas por nivel:\n";
    foreach ($levels as $level) {
        echo "  - {$level['level']}: {$level['count']}\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
