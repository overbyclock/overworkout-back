<?php
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'user'     => 'juan',
    'password' => '1234',
    'dbname'   => 'overworkout',
];

try {
    $conn = DriverManager::getConnection($connectionParams);
    
    // Ejercicios de hombros
    $shoulders = [
        // PRINCIPIANTE
        ['Pike Push Up', 'beginner', 1, 'shoulders', 'triceps', 'Cadera elevada formando pico. Empujas hacia arriba como si fuera un press militar. Base del trabajo de hombros en calistenia.', null],
        ['Wall Handstand Hold', 'beginner', 2, 'shoulders', 'core', 'Parada de manos contra pared. Mantienes posición invertida estática. Acostumbras al peso sobre brazos y desarrollas equilibrio.', null],
        ['Elevated Pike Push Up', 'beginner', 3, 'shoulders', 'triceps', 'Pies en banco o step para mayor inclinación. Más peso va a hombros. Puente perfecto hacia el handstand push up.', null],
        
        // INTERMEDIO
        ['Handstand Push Up', 'intermediate', 1, 'shoulders', 'triceps', 'Parada de manos contra pared, bajas hasta tocar el suelo con la cabeza. El press militar invertido completo por excelencia.', null],
        ['Pseudo Planche Push Up', 'intermediate', 2, 'shoulders', 'chest', 'Manos giradas hacia atrás, cuerpo inclinado hacia adelante. Preparación para la plancha con mucho trabajo de hombro anterior.', null],
        ['Archer Pike Push Up', 'intermediate', 3, 'shoulders', 'triceps', 'Un brazo se extiende lateralmente mientras empujas. Transición progresiva hacia el handstand de una mano.', null],
        
        // EXPERTO
        ['Freestanding Handstand Push Up', 'expert', 1, 'shoulders', 'core', 'Handstand push up sin pared. Requiere equilibrio perfecto combinado con fuerza extrema de hombros.', null],
        ['90 Degree Push Up', 'expert', 2, 'shoulders', 'chest', 'Desde posición de plancha impulsas el cuerpo hasta handstand. Transición dinámica que requiere fuerza explosiva de hombro.', null],
        ['Planche Push Up', 'expert', 3, 'shoulders', 'core', 'Cuerpo horizontal paralelo al suelo, sostenido solo por los brazos. Hombro en extrema flexión, nivel elite de calistenia.', null],
        ['One-Arm Handstand Push Up', 'expert', 3, 'shoulders', 'core', 'Parada de manos con una sola mano contra pared. Máxima fuerza unilateral de hombro y estabilidad.', null],
    ];
    
    foreach ($shoulders as $ex) {
        // Verificar si ya existe
        $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
        if ($exists == 0) {
            $conn->executeStatement(
                "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6]]
            );
            echo "✅ Creado: {$ex[0]} → {$ex[1]} (🔥{$ex[2]})\n";
        } else {
            echo "ℹ️ Ya existe: {$ex[0]}\n";
        }
    }
    
    echo "\n🎉 ¡Ejercicios de hombros añadidos!\n\n";
    
    // Mostrar resumen por grupo muscular
    $back = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'back' ORDER BY level, difficulty_rating");
    $chest = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'chest' ORDER BY level, difficulty_rating");
    $shoulders = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'shoulders' ORDER BY level, difficulty_rating");
    $triceps = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'triceps' ORDER BY level, difficulty_rating");
    $biceps = $conn->fetchAllAssociative("SELECT name, level, difficulty_rating FROM exercises WHERE primary_muscle_group = 'biceps' ORDER BY level, difficulty_rating");
    
    echo "📊 RESUMEN POR GRUPO MUSCULAR:\n\n";
    
    echo "ESPALDA (" . count($back) . "):\n";
    foreach ($back as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }
    
    echo "\nPECHO (" . count($chest) . "):\n";
    foreach ($chest as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }
    
    echo "\nHOMBROS (" . count($shoulders) . "):\n";
    foreach ($shoulders as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }
    
    echo "\nTRÍCEPS (" . count($triceps) . "):\n";
    foreach ($triceps as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }
    
    echo "\nBÍCEPS (" . count($biceps) . "):\n";
    foreach ($biceps as $e) {
        echo "  - {$e['name']} | {$e['level']} | 🔥{$e['difficulty_rating']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
