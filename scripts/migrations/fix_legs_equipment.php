<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

// Corregir equipamiento de ejercicios existentes
$updates = [
    // Barras
    ['Assisted Pistol Squat', 'barras'],
    ['Sissy Squat', 'barras'],
    ['Nordic Curl Negative', 'barras'],
    ['Glute Ham Raise Negative', 'barras'],
    ['Nordic Curl', 'barras'],
    ['Glute Ham Raise', 'barras'],
    
    // Bancos
    ['Box Squat', 'bancos_soportes'],
    ['Bulgarian Split Squat', 'bancos_soportes'],
    ['Box Pistol Squat', 'bancos_soportes'],
    ['Box Jump', 'bancos_soportes'],
    ['Step Up', 'bancos_soportes'],
    ['High Step Up', 'bancos_soportes'],
    ['Depth Jump', 'bancos_soportes'],
    ['Single Leg Box Jump', 'bancos_soportes'],
    ['Pistol Squat to Box', 'bancos_soportes'],
    ['Elevated Pistol Squat', 'bancos_soportes'],
    
    // Bandas
    ['Nordic Curl with Band', 'bandas'],
    
    // Anillas
    ['Hamstring Curl on Rings', 'anillas'],
];

echo "=== CORRIGIENDO EQUIPAMIENTO ===\n";
foreach ($updates as $upd) {
    $conn->executeStatement("
        UPDATE exercises e 
        JOIN equipments eq ON eq.name = ?
        SET e.equipment_id = eq.id 
        WHERE e.name = ?
    ", [$upd[1], $upd[0]]);
    echo "✅ {$upd[0]} → {$upd[1]}\n";
}

echo "\n=== EJERCICIOS PRINCIPIANTE CON EQUIPAMIENTO (3 FUEGOS) ===\n";

// Añadir ejercicios de principiante con 3 fuegos usando equipamiento
$beginner3fire = [
    ['Goblet Squat', 'beginner', 3, 'legs', 'glutes', 'Sentadilla goblet con kettlebell. Peso al pecho, sentadilla profunda. Excelente para aprender técnica con carga.', 'kettlebells'],
    ['Dumbbell Squat', 'beginner', 3, 'legs', 'glutes', 'Sentadilla con mancuernas. Una en cada mano, a los lados. Progresión natural desde air squat.', 'mancuernas'],
    ['Kettlebell Swing', 'beginner', 3, 'legs', 'glutes', 'Swing básico con kettlebell. Movimiento de cadera explosivo, trabaja potencia posterior.', 'kettlebells'],
    ['Dumbbell Lunge', 'beginner', 3, 'legs', 'glutes', 'Zancada con mancuernas. Una en cada mano, zancada caminando o estática.', 'mancuernas'],
    ['Kettlebell Goblet Lunge', 'beginner', 3, 'legs', 'glutes', 'Zancada goblet. Kettlebell al pecho, zancada hacia adelante o atrás.', 'kettlebells'],
    ['Leg Press Machine', 'beginner', 3, 'legs', 'glutes', 'Prensa de piernas en máquina. Sentado, empujar plataforma con pies. Controlado y seguro.', 'maquinas'],
    ['Hack Squat Machine', 'beginner', 3, 'legs', 'glutes', 'Hack squat en máquina. Espalda apoyada, sentadilla guiada. Enfocado en cuádriceps.', 'maquinas'],
    ['Leg Extension Machine', 'beginner', 3, 'legs', 'calves', 'Extensión de cuádriceps en máquina. Sentado, extender rodillas contra resistencia.', 'maquinas'],
    ['Leg Curl Machine', 'beginner', 3, 'legs', 'glutes', 'Curl femoral en máquina. Acostado boca abajo, flexionar rodillas. Aislamiento isquiotibiales.', 'maquinas'],
    ['Smith Machine Squat', 'beginner', 3, 'legs', 'glutes', 'Sentadilla en máquina Smith. Barra guiada, mayor seguridad para principiantes.', 'barras'],
    ['Barbell Squat', 'beginner', 3, 'legs', 'glutes', 'Sentadilla con barra. Barra sobre trapecios, sentadilla profunda. El rey de las sentadillas.', 'barras'],
    ['Front Squat', 'beginner', 3, 'legs', 'glutes', 'Sentadilla frontal. Barra sobre hombros/deltoide anterior, torso más vertical.', 'barras'],
    ['Dumbbell Step Up', 'beginner', 3, 'legs', 'glutes', 'Subida a banco con mancuernas. Una en cada mano, subir con fuerza de pierna.', 'mancuernas'],
    ['Kettlebell Step Up', 'beginner', 3, 'legs', 'glutes', 'Subida a banco con kettlebell. Al pecho o colgando, subida controlada.', 'kettlebells'],
    ['Weighted Wall Sit', 'beginner', 3, 'legs', 'core', 'Sentadilla pared con peso. Mancuerna o plato sobre piernas, aguantar tiempo.', 'mancuernas'],
    ['Dumbbell Calf Raise', 'beginner', 3, 'legs', 'calves', 'Elevación de talones con mancuernas. Una en cada mano, elevar talones.', 'mancuernas'],
    ['Machine Calf Raise', 'beginner', 3, 'legs', 'calves', 'Elevación de talones en máquina. Sentado o de pie, según máquina disponible.', 'maquinas'],
    ['Trap Bar Deadlift', 'beginner', 3, 'legs', 'glutes', 'Peso muerto con barra hexagonal. Agarre neutro, más fácil que barra recta.', 'barras'],
    ['Kettlebell Deadlift', 'beginner', 3, 'legs', 'glutes', 'Peso muerto con kettlebell. Entre pies, elevar caderas y hombros juntos.', 'kettlebells'],
    ['Dumbbell Romanian Deadlift', 'beginner', 3, 'legs', 'glutes', 'Peso muerto rumano con mancuernas. Piernas rígidas, flexión de cadera. Isquios y glúteos.', 'mancuernas'],
];

foreach ($beginner3fire as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['calisthenics', 'crossfit', 'fitness', 'powerlifting']);
        $conn->executeStatement("
            INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
            VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)
        ", [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]);
        echo "✅ Nuevo: {$ex[0]} ({$ex[6]})\n";
    } else {
        echo "ℹ️ Ya existe: {$ex[0]}\n";
    }
}

echo "\n=== RESUMEN FINAL ===\n";
$summary = $conn->fetchAllAssociative("
    SELECT level, difficulty_rating, COUNT(*) as count 
    FROM exercises 
    WHERE primary_muscle_group = 'legs' 
    GROUP BY level, difficulty_rating 
    ORDER BY level, difficulty_rating
");

foreach ($summary as $s) {
    $fires = str_repeat('🔥', $s['difficulty_rating']);
    echo "{$s['level']} $fires: {$s['count']}\n";
}

$total = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'legs'");
echo "\nTotal ejercicios de piernas: $total\n";
