<?php
// Script para insertar ejercicios de prueba

require_once __DIR__.'/vendor/autoload.php';

use App\Entity\Exercises;
use App\Enum\MuscleGroup;
use App\Enum\Levels;
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
    
    // Verificar equipamiento disponible
    $equipments = $conn->fetchAllAssociative('SELECT id, name FROM equipments');
    echo "Equipamiento disponible:\n";
    print_r($equipments);
    
    $equipmentId = count($equipments) > 0 ? $equipments[0]['id'] : null;
    
    // Ejercicios Push Up a crear
    $exercises = [
        [
            'name' => 'Wall Push Up',
            'primaryMuscleGroup' => 'chest',
            'secondaryMuscleGroup' => 'triceps',
            'level' => 'beginner',
            'difficultyRating' => 1,
            'media' => 'https://www.youtube.com/watch?v=wall-pushup'
        ],
        [
            'name' => 'Knee Push Up',
            'primaryMuscleGroup' => 'chest',
            'secondaryMuscleGroup' => 'triceps',
            'level' => 'intermediate',
            'difficultyRating' => 2,
            'media' => 'https://www.youtube.com/watch?v=knee-pushup'
        ],
        [
            'name' => 'One-Arm Push Up',
            'primaryMuscleGroup' => 'chest',
            'secondaryMuscleGroup' => 'triceps',
            'level' => 'expert',
            'difficultyRating' => 3,
            'media' => 'https://www.youtube.com/watch?v=onearm-pushup'
        ]
    ];
    
    foreach ($exercises as $exercise) {
        $sql = "INSERT INTO exercises (name, primary_muscle_group, secondary_muscle_group, level, difficulty_rating, media, equipment_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $conn->executeStatement($sql, [
            $exercise['name'],
            $exercise['primaryMuscleGroup'],
            $exercise['secondaryMuscleGroup'],
            $exercise['level'],
            $exercise['difficultyRating'],
            $exercise['media'],
            $equipmentId
        ]);
        
        echo "✅ Creado: {$exercise['name']} - {$exercise['level']} (🔥{$exercise['difficultyRating']})\n";
    }
    
    echo "\n🎉 ¡Ejercicios creados exitosamente!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
