<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== CREANDO REQUISITOS DE EVALUACIÓN ===\n\n";

// Obtener IDs de niveles
$levelIds = [];
$rows = $conn->fetchAllAssociative("SELECT id, level_number FROM training_levels WHERE program_id = 1");
foreach ($rows as $row) {
    $levelIds[$row['level_number']] = $row['id'];
}

if (empty($levelIds)) {
    echo "ERROR: No se encontraron niveles\n";
    exit;
}

// Verificar si ya hay requisitos
$exists = $conn->fetchOne("SELECT COUNT(*) FROM level_requirements");
if ($exists > 0) {
    echo "Ya existen $exists requisitos. ¿Sobrescribir? (s/n): ";
    // En modo automático, asumimos que sí
    $conn->executeStatement("DELETE FROM level_requirements");
    echo "Requisitos anteriores eliminados\n";
}

// Definir requisitos por nivel
$requirements = [
    // NIVEL 1 → 2: Novato a Principiante I
    1 => [
        ['exercise_reps', 'Flexiones inclinadas', '10 repeticiones perfectas', 10, 'reps', true],
        ['exercise_reps', 'Sentadillas', '20 sentadillas seguidas', 20, 'reps', true],
        ['exercise_time', 'Plancha', '30 segundos plank', 30, 'seconds', true],
        ['exercise_reps', 'Australian rows', '5 dominadas australianas', 5, 'reps', true],
    ],
    
    // NIVEL 2 → 3: Principiante I a II
    2 => [
        ['exercise_reps', 'Flexiones estandar', '10 flexiones perfectas', 10, 'reps', true],
        ['exercise_time', 'Plancha completa', '45 segundos plank', 45, 'seconds', true],
        ['exercise_reps', 'Pike push-ups', '5 pike push-ups', 5, 'reps', true],
        ['exercise_reps', 'Sentadillas profundas', '15 sentadillas profundas', 15, 'reps', true],
    ],
    
    // NIVEL 3 → 4: Principiante II a III
    3 => [
        ['exercise_reps', 'Dominadas negativas', '3 dominadas negativas (5s bajada)', 3, 'reps', true],
        ['exercise_reps', 'Flexiones diamante', '8 flexiones diamante', 8, 'reps', true],
        ['exercise_time', 'L-sit tuck', '10 segundos L-sit tuck', 10, 'seconds', true],
        ['exercise_reps', 'Wall walks', '3 wall walks completos', 3, 'reps', true],
    ],
    
    // NIVEL 4 → 5: Principiante III a Intermedio I
    4 => [
        ['exercise_reps', 'Primera dominada', '1 dominada completa', 1, 'reps', true],
        ['exercise_reps', 'Flexiones', '20 flexiones seguidas', 20, 'reps', true],
        ['exercise_time', 'Handstand pared', '20 segundos handstand pecho a pared', 20, 'seconds', true],
        ['exercise_time', 'L-sit', '3 segundos L-sit completo', 3, 'seconds', true],
    ],
    
    // NIVEL 5 → 6: Intermedio I a II
    5 => [
        ['exercise_reps', 'Dominadas', '5 dominadas seguidas', 5, 'reps', true],
        ['exercise_reps', 'Fondos', '8 fondos en paralelas', 8, 'reps', true],
        ['skill_unlocked', 'Muscle-up', 'Demostrar progresión muscle-up', 0, 'boolean', false],
        ['exercise_reps', 'Pistol squat asistido', '3 pistol squats asistidos por pierna', 3, 'reps', true],
    ],
    
    // NIVEL 6 → 7: Intermedio II a III
    6 => [
        ['exercise_reps', 'Dominadas', '8 dominadas seguidas', 8, 'reps', true],
        ['exercise_reps', 'Muscle-up', '1 muscle-up completo', 1, 'reps', true],
        ['exercise_reps', 'Pistol squat', '1 pistol squat completo por pierna', 1, 'reps', true],
        ['exercise_time', 'Tuck front lever', '10 segundos tuck front lever', 10, 'seconds', true],
    ],
    
    // NIVEL 7 → 8: Intermedio III a Avanzado I
    7 => [
        ['exercise_time', 'Handstand libre', '5 segundos handstand libre', 5, 'seconds', true],
        ['exercise_time', 'Tuck planche', '5 segundos tuck planche', 5, 'seconds', true],
        ['exercise_reps', 'Dominadas L-sit', '3 dominadas L-sit', 3, 'reps', true],
        ['exercise_reps', 'Flexiones arquero', '5 flexiones arquero por lado', 5, 'reps', true],
    ],
    
    // NIVEL 8 → 9: Avanzado I a II
    8 => [
        ['exercise_reps', 'Muscle-up anillas', '3 muscle-ups en anillas', 3, 'reps', true],
        ['exercise_time', 'Straddle front lever', '5 segundos straddle FL', 5, 'seconds', true],
        ['exercise_reps', 'Handstand walk', '5 metros handstand walk', 5, 'reps', true],
        ['exercise_time', 'Back lever', '10 segundos back lever', 10, 'seconds', true],
    ],
    
    // NIVEL 9 → 10: Avanzado II a III
    9 => [
        ['exercise_reps', 'Dominada una mano negativa', '3 negativas por brazo', 3, 'reps', true],
        ['exercise_time', 'Human flag', '5 segundos human flag', 5, 'seconds', true],
        ['exercise_time', 'Straddle planche', '5 segundos straddle planche', 5, 'seconds', true],
        ['exercise_reps', 'Dominadas con peso', '+10kg x 3 reps', 3, 'reps', true],
    ],
    
    // NIVEL 10 → 11: Avanzado III a Experto
    10 => [
        ['exercise_reps', 'One-arm pull-up', '1 dominada una mano', 1, 'reps', true],
        ['exercise_reps', 'One-arm push-up', '1 flexión una mano', 1, 'reps', true],
        ['exercise_time', 'Full planche', '3 segundos full planche', 3, 'seconds', true],
        ['exercise_reps', 'Handstand push-up libre', '1 HSPU libre', 1, 'reps', true],
    ],
    
    // NIVEL 11 → 12: Experto a Master
    11 => [
        ['exercise_reps', 'One-arm pull-ups', '3 dominadas una mano seguidas', 3, 'reps', true],
        ['exercise_time', 'Full planche', '5 segundos full planche', 5, 'seconds', true],
        ['exercise_reps', 'HSPU libre', '3 handstand push-ups libres', 3, 'reps', true],
        ['exercise_time', 'Maltese', '3 segundos maltese cross', 3, 'seconds', true],
    ],
    
    // NIVEL 12: Master (solo mantenimiento)
    12 => [
        ['custom_test', 'Demostración libre', 'Demostrar combinación de skills', 0, 'boolean', true],
    ],
];

$totalInserted = 0;
foreach ($requirements as $levelNumber => $reqs) {
    if (!isset($levelIds[$levelNumber])) {
        echo "Saltando nivel $levelNumber - no encontrado\n";
        continue;
    }
    
    $levelId = $levelIds[$levelNumber];
    
    foreach ($reqs as $index => $req) {
        $conn->executeStatement(
            "INSERT INTO level_requirements (level_id, requirement_type, name, description, target_value, target_unit, is_mandatory, display_order) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$levelId, $req[0], $req[1], $req[2], $req[3], $req[4], $req[5], $index + 1]
        );
        $totalInserted++;
    }
    
    echo "Nivel $levelNumber: " . count($reqs) . " requisitos\n";
}

echo "\n✅ TOTAL: $totalInserted requisitos creados\n";

// Mostrar resumen
$summary = $conn->fetchAllAssociative("
    SELECT l.level_number, l.name, COUNT(r.id) as reqs
    FROM training_levels l
    LEFT JOIN level_requirements r ON r.level_id = l.id
    WHERE l.program_id = 1
    GROUP BY l.id
    ORDER BY l.level_number
");

echo "\nResumen por nivel:\n";
foreach ($summary as $s) {
    echo "  Nivel {$s['level_number']}: {$s['reqs']} requisitos\n";
}
