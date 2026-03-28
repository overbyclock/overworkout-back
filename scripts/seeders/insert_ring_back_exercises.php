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

    // Ejercicios de espalda con anillas
    $exercises = [
        // PRINCIPIANTE - Ring Rows
        ['Ring Row - Feet on Ground', 'beginner', 1, 'back', 'biceps', 'Fila con anillas. Manos en las anillas, pies en el suelo, cuerpo inclinado. Tirar pecho hacia las anillas. Agarre neutro natural.', 'anillas'],
        ['Ring Row - Elevated Feet', 'beginner', 2, 'back', 'biceps', 'Fila con anillas y pies elevados. Mayor dificultad por el ángulo más pronunciado.', 'anillas'],
        ['Ring Row - Wide Grip', 'beginner', 2, 'back', 'biceps', 'Fila con anillas y agarre ancho. Anillas separadas al máximo, énfasis en dorsal ancho.', 'anillas'],
        ['Ring Row - Close Grip', 'beginner', 2, 'back', 'biceps', 'Fila con anillas y agarre cerrado. Anillas juntas, mayor trabajo de bíceps.', 'anillas'],
        ['Ring Row - Single Arm', 'beginner', 3, 'back', 'biceps', 'Fila con una sola anilla. Brazo auxiliar agarra la muñeca o antebrazo del brazo de trabajo. Transición a one arm row.', 'anillas'],

        // PRINCIPIANTE - Tuck positions
        ['Tuck Front Lever Hold', 'beginner', 2, 'back', 'core', 'Posición tuck front lever. Colgado de anillas, rodillas al pecho, mantener cuerpo horizontal. Isométrico para espalda y core.', 'anillas'],
        ['Tuck Front Lever Row', 'beginner', 3, 'back', 'biceps', 'Remo en tuck front lever. Desde posición tuck horizontal, tirar anillas hacia el pecho.', 'anillas'],
        ['Skin The Cat', 'beginner', 2, 'back', 'shoulders', 'Giro completo con anillas. Colgado, elevar piernas y pasar por encima, rotación completa de hombros. Movilidad y fuerza.', 'anillas'],
        ['German Hang', 'beginner', 1, 'back', 'shoulders', 'Posición alemana. Final del skin the cat, colgado boca arriba con brazos extendidos. Estiramiento y movilidad.', 'anillas'],

        // INTERMEDIO
        ['Ring Pull Up', 'intermediate', 1, 'back', 'biceps', 'Dominada con anillas. Agarre neutro natural, permite rotación de muñecas durante el movimiento. Más amigable con articulaciones.', 'anillas'],
        ['Ring Pull Up - False Grip', 'intermediate', 2, 'back', 'biceps', 'Dominada con falso agarre. La muñeca descansa sobre el anillo, preparación para muscle up con anillas.', 'anillas'],
        ['Arch Body Hold on Rings', 'intermediate', 2, 'back', 'core', 'Posición arco en anillas. Colgado boca abajo, elevar brazos y piernas formando arco. Isométrico para cadena posterior.', 'anillas'],
        ['Back Lever Hold', 'intermediate', 2, 'back', 'core', 'Posición back lever. Colgado, girar hacia planche invertida, mantener cuerpo horizontal boca abajo. Control escapular.', 'anillas'],
        ['Back Lever Row', 'intermediate', 3, 'back', 'biceps', 'Remo en back lever. Desde back lever, tirar anillas hacia el pecho.', 'anillas'],
        ['Advanced Tuck Front Lever Hold', 'intermediate', 3, 'back', 'core', 'Tuck avanzado. Rodillas ligeramente separadas del pecho, espalda más horizontal que tuck normal.', 'anillas'],
        ['Advanced Tuck Front Lever Row', 'intermediate', 3, 'back', 'biceps', 'Remo en tuck avanzado. Mayor dificultad que tuck normal.', 'anillas'],
        ['Ring Face Pull', 'intermediate', 2, 'back', 'shoulders', 'Face pull con anillas. Tirar anillas hacia la cara, codos altos. Trabajo de trapecio y deltoides posteriores.', 'anillas'],
        ['Ring Reverse Fly', 'intermediate', 2, 'back', 'shoulders', 'Pájaro con anillas. Cuerpo inclinado, abrir brazos lateralmente. Aislamiento de deltoides posteriores.', 'anillas'],

        // EXPERTO
        ['Ring Muscle Up', 'expert', 3, 'back', 'chest', 'Muscle up con anillas. Usando falso agarre, transición más suave que en barra fija. Mayor rango de movimiento.', 'anillas'],
        ['Straddle Front Lever Hold', 'expert', 3, 'back', 'core', 'Front lever straddle. Piernas abiertas en V, cuerpo horizontal. Progresión hacia front lever completo.', 'anillas'],
        ['Straddle Front Lever Row', 'expert', 3, 'back', 'biceps', 'Remo en straddle front lever. Tirar anillas hacia pecho desde posición straddle.', 'anillas'],
        ['Full Front Lever Hold', 'expert', 3, 'back', 'core', 'Front lever completo. Cuerpo completamente extendido horizontal, sólo agarre de anillas. Máxima fuerza de espalda y core.', 'anillas'],
        ['Full Front Lever Row', 'expert', 4, 'back', 'biceps', 'Remo en front lever completo. El ejercicio de dominadas más difícil. Tirar pecho a las anillas desde front lever.', 'anillas'],
        ['One Arm Ring Row', 'expert', 3, 'back', 'biceps', 'Fila con una anilla. Cuerpo inclinado, tirar con un solo brazo, el otro libre o agarrado a la cadera.', 'anillas'],
        ['Weighted Ring Pull Up', 'expert', 3, 'back', 'biceps', 'Dominada con peso en anillas. Lastre colgado del cinturón o pies, agarre neutro natural.', 'anillas'],
        ['Muscle Up to L-Sit', 'expert', 3, 'back', 'core', 'Muscle up que termina en L-sit sobre anillas. Combinación de tracción, empuje y core.', 'anillas'],
        ['Victorian Cross Pull Out', 'expert', 4, 'back', 'biceps', 'Victorian cross. Anillas abajo, brazos extendidos lateralmente, levantar cuerpo horizontal. Extremadamente difícil.', 'anillas'],
    ];

    foreach ($exercises as $ex) {
        // Verificar si ya existe
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
        if (0 === $exists) {
            $disciplines = json_encode(['calisthenics', 'gymnastics']);
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

    echo "\n🎉 ¡Ejercicios de espalda con anillas añadidos!\n\n";

    // Contar totales de espalda
    $total = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'back'");
    echo "Total ejercicios de espalda: {$total}\n";

    $ring = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE equipment_id = (SELECT id FROM equipments WHERE name = 'anillas')");
    echo "Ejercicios con anillas: {$ring}\n";

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
