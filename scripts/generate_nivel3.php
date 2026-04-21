<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '',
    'dbname' => 'overworkout',
]);

echo "=== GENERANDO NIVEL 3: PRINCIPIANTE II ===\n\n";

// 1. Obtener el TrainingLevel
$levelId = $conn->fetchOne('SELECT id FROM training_levels WHERE level_number = 3');
if (!$levelId) {
    echo "ERROR: No se encontro el TrainingLevel con level_number = 3\n";
    exit(1);
}
echo "TrainingLevel ID: $levelId\n";

// 2. Limpiar datos existentes para este nivel
$existingTrainings = $conn->fetchAllAssociative(
    'SELECT id FROM training WHERE training_level_id = ?',
    [$levelId]
);
$trainingIds = array_column($existingTrainings, 'id');

if ($trainingIds) {
    $placeholders = implode(',', array_fill(0, count($trainingIds), '?'));
    $rounds = $conn->fetchAllAssociative(
        "SELECT id FROM training_round WHERE training_id IN ($placeholders)",
        $trainingIds
    );
    $roundIds = array_column($rounds, 'id');

    if ($roundIds) {
        $rPlaceholders = implode(',', array_fill(0, count($roundIds), '?'));
        $conn->executeStatement(
            "DELETE FROM training_exercise_configuration WHERE training_round_id IN ($rPlaceholders)",
            $roundIds
        );
        echo "Eliminadas configs existentes: " . count($roundIds) . " rounds\n";
        $conn->executeStatement(
            "DELETE FROM training_round WHERE id IN ($rPlaceholders)",
            $roundIds
        );
        echo "Eliminados rounds existentes\n";
    }

    $conn->executeStatement(
        "DELETE FROM training WHERE training_level_id = ?",
        [$levelId]
    );
    echo "Eliminados trainings existentes: " . count($trainingIds) . "\n";
}

// 3. IDs de ejercicios
$exercises = [
    'Standard Push Up' => 14,
    'Diamond Push Up' => 15,
    'Wide Push Up' => 16,
    'Planche Lean' => 402,
    'Negative Pull Up' => 21,
    'Band Assisted Pull Up' => 519,
    'Australian Pull Up' => 20,
    'Active Hang' => 274,
    'Bulgarian Split Squat' => 154,
    'Air Squat' => 146,
    'Stationary Lunge' => 149,
    'Single Leg Glute Bridge' => 108,
    'Tuck L-Sit' => 303,
    'Plank' => 286,
    'Hollow Body Hold' => 296,
    'Wall Walk' => 487,
];

// 4. Definicion de las 16 sesiones
// Estructura: [week, day_key, name, rounds, rest_between_rounds, exercises]
// Cada ejercicio: [nombre, reps, max_time_for_reps, rest_between_exercises, rest_between_sets]
// Para holds: reps=null, max_time_for_reps=segundos
$sessions = [
    // SEMANA 0
    [
        0, 'day1_push', 'Dia 1: Push Base + Skill - Semana 0', 3, 120,
        [
            ['Standard Push Up', 8, null, 30, 30],
            ['Diamond Push Up', 5, null, 30, 30],
            ['Wide Push Up', 8, null, 30, 30],
            ['Planche Lean', null, 15, 30, 30],
        ]
    ],
    [
        0, 'day2_pull', 'Dia 2: Pull Base + Skill - Semana 0', 3, 120,
        [
            ['Negative Pull Up', 3, null, 30, 30],
            ['Band Assisted Pull Up', 5, null, 30, 30],
            ['Australian Pull Up', 10, null, 30, 30],
            ['Active Hang', null, 25, 30, 30],
        ]
    ],
    [
        0, 'day3_legs', 'Dia 3: Legs Base - Semana 0', 3, 120,
        [
            ['Bulgarian Split Squat', 6, null, 30, 30],
            ['Air Squat', 20, null, 30, 30],
            ['Stationary Lunge', 10, null, 30, 30],
            ['Single Leg Glute Bridge', 10, null, 30, 30],
        ]
    ],
    [
        0, 'day4_core', 'Dia 4: Core + Skills - Semana 0', 3, 90,
        [
            ['Tuck L-Sit', null, 5, 20, 20],
            ['Plank', null, 40, 20, 20],
            ['Hollow Body Hold', null, 20, 20, 20],
            ['Wall Walk', 2, null, 20, 20],
        ]
    ],

    // SEMANA 1
    [
        1, 'day1_push', 'Dia 1: Push Base + Skill - Semana 1', 3, 120,
        [
            ['Standard Push Up', 10, null, 30, 30],
            ['Diamond Push Up', 6, null, 30, 30],
            ['Wide Push Up', 10, null, 30, 30],
            ['Planche Lean', null, 20, 30, 30],
        ]
    ],
    [
        1, 'day2_pull', 'Dia 2: Pull Base + Skill - Semana 1', 3, 120,
        [
            ['Negative Pull Up', 4, null, 30, 30],
            ['Band Assisted Pull Up', 6, null, 30, 30],
            ['Australian Pull Up', 12, null, 30, 30],
            ['Active Hang', null, 30, 30, 30],
        ]
    ],
    [
        1, 'day3_legs', 'Dia 3: Legs Base - Semana 1', 3, 120,
        [
            ['Bulgarian Split Squat', 8, null, 30, 30],
            ['Air Squat', 22, null, 30, 30],
            ['Stationary Lunge', 12, null, 30, 30],
            ['Single Leg Glute Bridge', 12, null, 30, 30],
        ]
    ],
    [
        1, 'day4_core', 'Dia 4: Core + Skills - Semana 1', 3, 90,
        [
            ['Tuck L-Sit', null, 8, 20, 20],
            ['Plank', null, 45, 20, 20],
            ['Hollow Body Hold', null, 25, 20, 20],
            ['Wall Walk', 3, null, 20, 20],
        ]
    ],

    // SEMANA 2
    [
        2, 'day1_push', 'Dia 1: Push Progresion - Semana 2', 3, 120,
        [
            ['Standard Push Up', 12, null, 30, 30],
            ['Diamond Push Up', 7, null, 30, 30],
            ['Wide Push Up', 10, null, 30, 30],
            ['Planche Lean', null, 25, 30, 30],
        ]
    ],
    [
        2, 'day2_pull', 'Dia 2: Pull Progresion - Semana 2', 3, 120,
        [
            ['Negative Pull Up', 5, null, 30, 30],
            ['Band Assisted Pull Up', 8, null, 30, 30],
            ['Australian Pull Up', 12, null, 30, 30],
            ['Active Hang', null, 35, 30, 30],
        ]
    ],
    [
        2, 'day3_legs', 'Dia 3: Legs Progresion - Semana 2', 3, 120,
        [
            ['Bulgarian Split Squat', 8, null, 30, 30],
            ['Air Squat', 25, null, 30, 30],
            ['Stationary Lunge', 12, null, 30, 30],
            ['Single Leg Glute Bridge', 12, null, 30, 30],
        ]
    ],
    [
        2, 'day4_core', 'Dia 4: Core + Skills Progresion - Semana 2', 3, 120,
        [
            ['Tuck L-Sit', null, 10, 25, 25],
            ['Plank', null, 50, 25, 25],
            ['Hollow Body Hold', null, 25, 25, 25],
            ['Wall Walk', 3, null, 25, 25],
        ]
    ],

    // SEMANA 3
    [
        3, 'day1_push', 'Dia 1: Push Intensificacion - Semana 3', 4, 180,
        [
            ['Standard Push Up', 15, null, 45, 45],
            ['Diamond Push Up', 8, null, 45, 45],
            ['Wide Push Up', 12, null, 45, 45],
            ['Planche Lean', null, 30, 45, 45],
        ]
    ],
    [
        3, 'day2_pull', 'Dia 2: Pull Intensificacion - Semana 3', 4, 180,
        [
            ['Negative Pull Up', 5, null, 45, 45],
            ['Band Assisted Pull Up', 8, null, 45, 45],
            ['Australian Pull Up', 15, null, 45, 45],
            ['Active Hang', null, 40, 45, 45],
        ]
    ],
    [
        3, 'day3_legs', 'Dia 3: Legs Intensificacion - Semana 3', 4, 180,
        [
            ['Bulgarian Split Squat', 10, null, 45, 45],
            ['Air Squat', 25, null, 45, 45],
            ['Stationary Lunge', 15, null, 45, 45],
            ['Single Leg Glute Bridge', 15, null, 45, 45],
        ]
    ],
    [
        3, 'day4_core', 'Dia 4: Core + Skills Intensificacion - Semana 3', 3, 180,
        [
            ['Tuck L-Sit', null, 10, 30, 30],
            ['Plank', null, 60, 30, 30],
            ['Hollow Body Hold', null, 30, 30, 30],
            ['Wall Walk', 3, null, 30, 30],
        ]
    ],
];

$trainingCount = 0;
$roundCount = 0;
$configCount = 0;

foreach ($sessions as $session) {
    [$week, $dayKey, $name, $rounds, $restBetweenRounds, $exerciseList] = $session;

    // Insertar training
    $conn->executeStatement(
        'INSERT INTO training (discipline, target, created_at, rounds, training_user_id, name, is_benchmark, benchmark_type, rx_weight_male, rx_weight_female, elite_time, advanced_time, intermediate_time, beginner_time, training_level_id, week_number, day_key, is_circuit)
         VALUES (?, ?, NOW(), ?, NULL, ?, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?, ?, ?, 1)',
        ['calisthenics', 'strength', $rounds, $name, $levelId, $week, $dayKey]
    );
    $trainingId = (int) $conn->lastInsertId();
    $trainingCount++;

    // Insertar round
    $conn->executeStatement(
        'INSERT INTO training_round (training_id, sets_for_round, rest_between_rounds) VALUES (?, ?, ?)',
        [$trainingId, $rounds, $restBetweenRounds]
    );
    $roundId = (int) $conn->lastInsertId();
    $roundCount++;

    // Insertar exercise configs
    foreach ($exerciseList as $ex) {
        [$exName, $reps, $maxTime, $restBetweenExercises, $restBetweenSets] = $ex;
        $exerciseId = $exercises[$exName] ?? null;
        if (!$exerciseId) {
            echo "WARNING: No se encontro ID para ejercicio '$exName'\n";
            continue;
        }

        $conn->executeStatement(
            'INSERT INTO training_exercise_configuration (exercise_id, reps, max_time_for_reps, sets_for_exercise, rest_between_sets, weight, training_round_id, rest_between_exercises) VALUES (?, ?, ?, 1, ?, NULL, ?, ?)',
            [$exerciseId, $reps, $maxTime, $restBetweenSets, $roundId, $restBetweenExercises]
        );
        $configCount++;
    }
}

echo "\n=== RESUMEN ===\n";
echo "Trainings creados: $trainingCount\n";
echo "Rounds creados: $roundCount\n";
echo "Configs creadas: $configCount\n";
echo "\nNivel 3 generado correctamente.\n";
