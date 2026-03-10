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
    
    // Más máquinas de gimnasio
    $machines = [
        // Pectoral
        ['Peck deck', 'maquinas', 'fitness_center', 'Máquina de mariposa (peck deck). Aperturas guiadas para aislamiento del pectoral.', null],
        ['Cruces de polea', 'maquinas', 'fitness_center', 'Poleas ajustables para cruces de pecho desde arriba o abajo. Trabajo constante de tensión.', null],
        
        // Espalda
        ['Remo sentado', 'maquinas', 'fitness_center', 'Máquina de remo sentado con apoyo de pecho. Agarre neutro o prono para dorsal.', null],
        ['Pullover', 'maquinas', 'fitness_center', 'Máquina de pullover (nautilus). Movimiento circular para dorsal y serrato.', null],
        
        // Piernas
        ['Curl femoral acostado', 'maquinas', 'fitness_center', 'Máquina de curl femoral boca abajo. Aislamiento de isquiotibiales.', null],
        ['Elevación de gemelos', 'maquinas', 'fitness_center', 'Máquina de gemelos sentado o de pie. Trabajo completo de la pantorrilla.', null],
        ['Abductores', 'maquinas', 'fitness_center', 'Máquina de abductores de cadera. Separación de piernas contra resistencia.', null],
        ['Adductores', 'maquinas', 'fitness_center', 'Máquina de adductores de cadera. Aducción de piernas, trabajo de la cara interna.', null],
        ['Prensa de piernas', 'maquinas', 'fitness_center', 'Prensa inclinada o horizontal. Sentadilla guiada con respaldo.', null],
        ['Hack squat', 'maquinas', 'fitness_center', 'Máquina de hack squat. Sentadilla con carga en los hombros y guía de movimiento.', null],
        
        // Hombros
        ['Elevaciones laterales', 'maquinas', 'fitness_center', 'Máquina de elevaciones laterales guiadas. Aislamiento del deltoides lateral.', null],
        ['Pájaro', 'maquinas', 'fitness_center', 'Reverse pec deck. Elevaciones posteriores para deltoides trasero y trapecio.', null],
        
        // Brazos
        ['Curl de bíceps', 'maquinas', 'fitness_center', 'Máquina de curl de bíceps con apoyo de brazos. Aislamiento completo.', null],
        ['Extensiones de tríceps', 'maquinas', 'fitness_center', 'Máquina de extensión de tríceps en polea o disco. Empuje descendente.', null],
        
        // Core
        ['Torso rotation', 'maquinas', 'fitness_center', 'Máquina de rotación de torso. Giro controlado para oblicuos y core.', null],
        ['Crunch abdominal', 'maquinas', 'fitness_center', 'Máquina de crunch abdominal guiado. Flexión de tronco con resistencia.', null],
    ];
    
    foreach ($machines as $machine) {
        // Verificar si ya existe
        $exists = $conn->fetchOne("SELECT COUNT(*) FROM equipments WHERE name = ?", [$machine[0]]);
        if ($exists == 0) {
            $conn->executeStatement(
                "INSERT INTO equipments (name, category, icon, description, weight, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                [$machine[0], $machine[1], $machine[2], $machine[3], $machine[4]]
            );
            echo "✅ Creado: {$machine[0]}\n";
        } else {
            echo "ℹ️ Ya existe: {$machine[0]}\n";
        }
    }
    
    echo "\n🎉 ¡Todas las máquinas añadidas!\n\n";
    
    // Mostrar resumen
    $categories = ['barras', 'pesos_libres', 'bancos_soportes', 'accesorios', 'maquinas'];
    foreach ($categories as $cat) {
        $items = $conn->fetchAllAssociative("SELECT name FROM equipments WHERE category = ?", [$cat]);
        if (count($items) > 0) {
            echo strtoupper($cat) . " (" . count($items) . "):\n";
            foreach ($items as $item) {
                echo "  - {$item['name']}\n";
            }
            echo "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
