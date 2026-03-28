<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306,
    'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout',
]);

echo "=== REORGANIZANDO GRUPOS MUSCULARES ===\n\n";

// Ejercicios que deberían ir en GLÚTEOS (cambiar primary a glutes, secondary a legs)
$glutesExercises = [
    ['Glute Bridge', 'glutes', 'legs'],
    ['Single Leg Glute Bridge', 'glutes', 'legs'],
    ['Donkey Kick', 'glutes', 'legs'],
];

echo "Moviendo a GLÚTEOS:\n";
foreach ($glutesExercises as $ex) {
    $conn->executeStatement(
        'UPDATE exercises SET primary_muscle_group = ?, secondary_muscle_group = ? WHERE name = ?',
        [$ex[1], $ex[2], $ex[0]]
    );
    echo "  ✅ {$ex[0]} → Primario: {$ex[1]}, Secundario: {$ex[2]}\n";
}

// Ejercicios que deberían ir en GEMELOS (cambiar primary a calves, secondary a legs)
$calvesExercises = [
    ['Dumbbell Calf Raise', 'calves', 'legs'],
    ['Machine Calf Raise', 'calves', 'legs'],
];

echo "\nMoviendo a GEMELOS:\n";
foreach ($calvesExercises as $ex) {
    $conn->executeStatement(
        'UPDATE exercises SET primary_muscle_group = ?, secondary_muscle_group = ? WHERE name = ?',
        [$ex[1], $ex[2], $ex[0]]
    );
    echo "  ✅ {$ex[0]} → Primario: {$ex[1]}, Secundario: {$ex[2]}\n";
}

// Añadir más ejercicios específicos de glúteos
$newGlutes = [
    ['Hip Thrust', 'beginner', 2, 'glutes', 'legs', 'Hip thrust con peso corporal. Espalda en banco, elevar cadera contrayendo glúteos. Máxima activación de glúteos.', 'bancos_soportes'],
    ['Single Leg Hip Thrust', 'intermediate', 2, 'glutes', 'legs', 'Hip thrust una pierna. Una pierna apoyada en suelo, elevar cadera. Más difícil y estable.', 'bancos_soportes'],
    ['Frog Pump', 'beginner', 1, 'glutes', 'legs', 'Puente rana. Tumbado, pies juntas rodillas abiertas, elevar cadera. Aislamiento glúteos medio.', null],
    ['Fire Hydrant', 'beginner', 1, 'glutes', 'legs', 'Hidrante. A cuatro patas, elevar rodilla lateralmente. Trabaja glúteo medio.', null],
    ['Glute Kickback', 'beginner', 1, 'glutes', 'legs', 'Patada atrás. A cuatro patas, extender pierna hacia atrás. Glúteo mayor.', null],
    ['Standing Glute Kickback', 'beginner', 1, 'glutes', 'legs', 'Patada atrás de pie. De pie, extender pierna hacia atrás. Equilibrio + glúteos.', null],
    ['Cable Pull Through', 'intermediate', 2, 'glutes', 'legs', 'Pull through en polea. De espaldas a polea baja, tirar hacia adelante flexionando cadera. Crucero de polea.', 'maquinas'],
    ['Kettlebell Swing', 'beginner', 3, 'glutes', 'legs', 'Swing ruso con kettlebell. Movimiento de cadera explosivo, trabaja potencia de glúteos e isquios.', 'kettlebells'],
];

echo "\nAñadiendo ejercicios de GLÚTEOS:\n";
foreach ($newGlutes as $ex) {
    // Verificar si ya existe
    $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
    if (0 === $exists) {
        $disciplines = json_encode(['calisthenics', 'crossfit', 'fitness']);
        $conn->executeStatement(
            'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)',
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query='.urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "  ✅ Nuevo: {$ex[0]}\n";
    } else {
        // Si existe con otro grupo muscular, actualizarlo
        $conn->executeStatement(
            'UPDATE exercises SET primary_muscle_group = ?, secondary_muscle_group = ? WHERE name = ?',
            [$ex[3], $ex[4], $ex[0]]
        );
        echo "  🔄 Actualizado: {$ex[0]}\n";
    }
}

// Añadir más ejercicios específicos de gemelos
$newCalves = [
    ['Standing Calf Raise', 'beginner', 1, 'calves', 'legs', 'Elevación de talones de pie. De pie, elevar talones contrayendo gemelos. Peso corporal o con carga.', null],
    ['Seated Calf Raise', 'beginner', 2, 'calves', 'legs', 'Elevación de talones sentado. Rodillas flexionadas 90°, elevar talones. Trabaja sóleo.', 'bancos_soportes'],
    ['Single Leg Calf Raise', 'beginner', 2, 'calves', 'legs', 'Elevación una pierna. Mayor dificultad y equilibrio.', null],
    ['Calf Raise on Step', 'beginner', 1, 'calves', 'legs', 'Elevación en escalón. Talones fuera del escalón, bajar estirando y subir. Mayor rango.', 'bancos_soportes'],
    ['Jump Rope Calf Raises', 'beginner', 2, 'calves', 'calves', 'Saltos con cuerda alternando talones. Pliométrico para gemelos.', 'cuerda_saltar'],
    ['Sled Push', 'intermediate', 3, 'calves', 'legs', 'Empuje de trineo. Empujar trineo cargado, trabajo intenso de gemelos y cadena posterior.', 'trineo'],
];

echo "\nAñadiendo ejercicios de GEMELOS:\n";
foreach ($newCalves as $ex) {
    $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
    if (0 === $exists) {
        $disciplines = json_encode(['calisthenics', 'crossfit', 'fitness']);
        $conn->executeStatement(
            'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)',
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query='.urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "  ✅ Nuevo: {$ex[0]}\n";
    } else {
        echo "  ℹ️ Ya existe: {$ex[0]}\n";
    }
}

echo "\n=== RESUMEN FINAL ===\n";
$summary = $conn->fetchAllAssociative("
    SELECT primary_muscle_group, COUNT(*) as count 
    FROM exercises 
    WHERE primary_muscle_group IN ('legs', 'glutes', 'calves', 'adductors')
    GROUP BY primary_muscle_group
    ORDER BY count DESC
");

foreach ($summary as $s) {
    $label = match ($s['primary_muscle_group']) {
        'legs' => 'Piernas',
        'glutes' => 'Glúteos',
        'calves' => 'Gemelos',
        'adductors' => 'Aductores',
        default => $s['primary_muscle_group']
    };
    echo "$label: {$s['count']} ejercicios\n";
}
