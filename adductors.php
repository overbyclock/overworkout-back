<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;
$conn = DriverManager::getConnection(['driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,'user'=>'juan','password'=>'1234','dbname'=>'overworkout']);

// Mover ejercicios de aductores
$conn->executeStatement("UPDATE exercises SET primary_muscle_group = 'adductors', secondary_muscle_group = 'legs' WHERE name = 'Side Lunge'");
$conn->executeStatement("UPDATE exercises SET primary_muscle_group = 'adductors', secondary_muscle_group = 'legs' WHERE name = 'Cossack Squat'");

// Añadir más ejercicios de aductores
$newAdductors = [
    ['Adductor Machine', 'beginner', 2, 'adductors', 'legs', 'Máquina de aductores. Sentado, juntar rodillas contra resistencia. Aislamiento de aductores.', 'maquinas'],
    ['Copenhagen Plank', 'intermediate', 3, 'adductors', 'core', 'Plancha de Copenhague. Pierna superior sobre banco, mantener cuerpo horizontal. Intenso para aductores.', 'bancos_soportes'],
    ['Side Lying Adductor Lift', 'beginner', 1, 'adductors', 'legs', 'Elevación de aductor tumbado. De lado, elevar pierna inferior hacia arriba. Aislamiento.', null],
    ['Sumo Squat', 'beginner', 2, 'adductors', 'legs', 'Sentadilla sumo. Piernas muy abiertas, puntas de pies hacia afuera, bajar. Enfasis en aductores.', null],
    ['Goblet Squat with Band', 'beginner', 2, 'adductors', 'legs', 'Sentadilla goblet con banda alrededor de rodillas. Resistencia lateral trabaja aductores.', 'bandas'],
    ['Standing Adductor Slide', 'beginner', 2, 'adductors', 'legs', 'Deslizamiento de aductor de pie. Con disco/toalla, deslizar pierna hacia dentro. Control excéntrico.', null],
];

echo "Añadiendo ejercicios de ADUCTORES:\n";
foreach ($newAdductors as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['calisthenics', 'fitness']);
        $conn->executeStatement(
            "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "  ✅ {$ex[0]}\n";
    } else {
        echo "  ℹ️ Ya existe: {$ex[0]}\n";
    }
}

echo "\nResumen Aductores:\n";
$count = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'adductors'");
echo "Total: $count ejercicios\n";
