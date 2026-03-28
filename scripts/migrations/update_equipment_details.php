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
    
    // Actualizar equipos con descripciones y categorías
    $updates = [
        ['Barra de dominadas', 'Barra fija para realizar dominadas, muscle ups y ejercicios de suspensión. Esencial para entrenamiento de espalda.', 'calisthenics', 'sports_gymnastics', null],
        ['Paralelas', 'Paralelas para fondos, ejercicios de calistenia y soporte de manos. Perfectas para trabajar pecho y tríceps.', 'calisthenics', 'horizontal_rule', null],
        ['Mancuernas', 'Pesas libres versátiles para ejercicios de fuerza de brazos, hombros y pecho. Disponibles en varios pesos.', 'weights', 'fitness_center', null],
        ['Barra olímpica', 'Barra estándar de 20kg para levantamiento de peso muerto, sentadilla y press. Compatible con discos olímpicos.', 'weights', 'horizontal_rule', 20],
        ['Kettlebell', 'Pesa rusa con mango para ejercicios funcionales, swings y trabajo de core. Ideal para fuerza y resistencia.', 'weights', 'local_drink', null],
        ['Banco', 'Banco ajustable para press de banca, step ups y ejercicios de peso corporal. Base de cualquier gimnasio.', 'general', 'single_bed', null],
        ['Cuerda de saltar', 'Cuerda ligera para cardio, coordinación y calentamiento. Portátil y efectiva para quemar calorías.', 'cardio', 'auto_fix_high', null],
        ['Banda elástica', 'Banda de resistencia para asistir dominadas, estiramientos y ejercicios de rehabilitación. Varios niveles de resistencia.', 'accessories', 'auto_fix_high', null],
        ['Anillas', 'Anillas gimnásticas ajustables para dips, muscle ups y ejercicios de estabilidad. Requieren control corporal.', 'calisthenics', 'sports_gymnastics', null],
        ['Colchoneta', 'Colchoneta acolchada para ejercicios de suelo, yoga, abdominales y estiramientos. Protección y comodidad.', 'yoga', 'single_bed', null],
        ['pull-up bars', 'Barras para dominadas', 'calisthenics', 'sports_gymnastics', null],
        ['dip bars', 'Barras paralelas para fondos', 'calisthenics', 'horizontal_rule', null],
    ];
    
    foreach ($updates as $update) {
        $conn->executeStatement(
            "UPDATE equipments SET description = ?, category = ?, icon = ?, weight = ? WHERE name = ?",
            [$update[1], $update[2], $update[3], $update[4], $update[0]]
        );
        echo "✅ Actualizado: {$update[0]}\n";
    }
    
    echo "\n🎉 ¡Equipamiento actualizado!\n\n";
    
    // Mostrar equipos
    $allEquipments = $conn->fetchAllAssociative('SELECT name, category, description FROM equipments ORDER BY id');
    echo "📋 Equipos en BD:\n";
    foreach ($allEquipments as $e) {
        $desc = $e['description'] ? substr($e['description'], 0, 40) . '...' : 'Sin descripción';
        echo "  - {$e['name']} ({$e['category']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
