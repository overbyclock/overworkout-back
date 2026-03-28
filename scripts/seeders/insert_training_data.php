<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'juan',
    'password' => '1234',
    'dbname' => 'overworkout',
]);

echo "=== INSERTANDO DATOS DE ENTRENAMIENTO ===\n\n";

// Verificar si ya existe el programa
$exists = $conn->fetchOne("SELECT id FROM training_programs WHERE slug = 'calisthenia-master'");
if ($exists) {
    echo "El programa 'Calistenia Master' ya existe (ID: $exists)\n";
    $programId = $exists;
} else {
    // Insertar Programa
    $conn->executeStatement(
        'INSERT INTO training_programs (name, slug, description, discipline, total_levels, difficulty, image_url) 
         VALUES (?, ?, ?, ?, ?, ?, ?)',
        [
            'Calistenia Master',
            'calisthenia-master',
            'Programa completo de calistenia desde cero hasta nivel experto. 12 niveles progresivos con evaluaciones y desbloqueo de skills.',
            'calisthenics',
            12,
            'mixed',
            '/images/programs/calisthenia-master.jpg',
        ]
    );
    $programId = $conn->lastInsertId();
    echo "Programa creado (ID: $programId)\n";
}

// Verificar si ya hay niveles
$levelCount = $conn->fetchOne('SELECT COUNT(*) FROM training_levels WHERE program_id = ?', [$programId]);
if ($levelCount > 0) {
    echo "Ya existen $levelCount niveles para este programa\n";
} else {
    // Insertar los 12 niveles
    $levels = [
        [1, 'Novato Absoluto', 'Los primeros pasos', 'Aprender movimientos basicos', 3, 1, '#10b981', '🌱'],
        [2, 'Principiante I', 'Base solida', 'Dominar peso corporal basico', 3, 1, '#34d399', '🌿'],
        [3, 'Principiante II', 'Introduccion tecnica', 'Primeros skills', 3, 2, '#4ade80', '🍃'],
        [4, 'Principiante III', 'Dominada completa', 'Primera dominada y handstand', 3, 2, '#22c55e', '🌳'],
        [5, 'Intermedio I', 'Muscle-up', 'Volumen y primer muscle-up', 3, 2, '#facc15', '🎯'],
        [6, 'Intermedio II', 'Fuerza unilateral', 'Pistol y skills', 3, 3, '#fbbf24', '⚡'],
        [7, 'Intermedio III', 'Skills avanzados', 'HS libre y planche', 3, 3, '#f59e0b', '🔥'],
        [8, 'Avanzado I', 'Control total', 'Straddle skills', 3, 3, '#f97316', '💪'],
        [9, 'Avanzado II', 'One-arm prep', 'Preparacion unilateral', 3, 4, '#fb923c', '🦁'],
        [10, 'Avanzado III', 'Dominio absoluto', 'One-arms y planche', 3, 4, '#ea580c', '👑'],
        [11, 'Experto', 'Maestria tecnica', 'Combinaciones', 3, 4, '#dc2626', '🏆'],
        [12, 'Master', 'Elite calistenia', 'Innovacion', 3, 5, '#b91c1c', '⭐'],
    ];

    foreach ($levels as $level) {
        $conn->executeStatement(
            'INSERT INTO training_levels (program_id, level_number, name, title, objective, estimated_duration_weeks, difficulty_rating, color, icon) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$programId, $level[0], $level[1], $level[2], $level[3], $level[4], $level[5], $level[6], $level[7]]
        );
    }
    echo "12 niveles creados\n";
}

// Insertar skills
$skillCount = $conn->fetchOne('SELECT COUNT(*) FROM training_skills WHERE program_id = ?', [$programId]);
if ($skillCount > 0) {
    echo "Ya existen $skillCount skills\n";
} else {
    // Obtener IDs de niveles
    $levelIds = [];
    $rows = $conn->fetchAllAssociative('SELECT id, level_number FROM training_levels WHERE program_id = ?', [$programId]);
    foreach ($rows as $row) {
        $levelIds[$row['level_number']] = $row['id'];
    }

    $skills = [
        [$levelIds[3], 'Handstand Prep', 'handstand', 'Preparacion pino', 3, 6],
        [$levelIds[3], 'L-sit Base', 'core', 'L-sit basico', 3, 5],
        [$levelIds[4], 'Handstand Wall', 'handstand', 'Pino pared', 4, 8],
        [$levelIds[4], 'First Pull-up', 'strength', 'Primera dominada', 4, 7],
        [$levelIds[5], 'Muscle-up Journey', 'muscle_up', 'Camino al MU', 5, 9],
        [$levelIds[5], 'L-sit Advanced', 'core', 'L-sit avanzado', 5, 7],
        [$levelIds[6], 'Pistol Squat', 'strength', 'Sentadilla una pierna', 6, 8],
        [$levelIds[6], 'Front Lever Tuck', 'front_lever', 'FL tuck', 6, 9],
        [$levelIds[7], 'Freestanding Handstand', 'handstand', 'Pino libre', 7, 10],
        [$levelIds[7], 'Tuck Planche', 'planche', 'Planche tuck', 7, 10],
        [$levelIds[8], 'Muscle-up Rings', 'muscle_up', 'MU anillas', 8, 9],
        [$levelIds[8], 'Straddle Front Lever', 'front_lever', 'FL straddle', 8, 11],
        [$levelIds[9], 'One-arm Pull-up Prep', 'strength', 'Prep OAP', 9, 12],
        [$levelIds[9], 'Human Flag', 'human_flag', 'Bandera', 9, 12],
        [$levelIds[10], 'Full Planche', 'planche', 'Planche full', 10, 13],
        [$levelIds[10], 'One-arm Push-up', 'strength', 'OAPU', 10, 12],
        [$levelIds[11], 'Freestanding HSPU', 'handstand', 'HSPU libre', 11, 14],
        [$levelIds[12], 'One-arm Handstand', 'handstand', 'Pino una mano', 12, 15],
    ];

    foreach ($skills as $skill) {
        $conn->executeStatement(
            'INSERT INTO training_skills (program_id, level_id, name, family, description, unlock_at_level, mastery_at_level, is_key_skill) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$programId, $skill[0], $skill[1], $skill[2], $skill[3], $skill[4], $skill[5], true]
        );
    }
    echo count($skills)." skills insertados\n";
}

echo "\n✅ DATOS INSERTADOS CORRECTAMENTE\n";
echo "Programa ID: $programId\n";
