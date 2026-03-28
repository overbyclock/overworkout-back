<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'juan',
    'password' => '1234',
    'dbname' => 'overworkout',
];

try {
    $conn = DriverManager::getConnection($connectionParams);

    // Actualizar categorías según tipo de equipo
    $updates = [
        // Barras (para dominadas, dips, suspensiones)
        ['pull-up bars', 'barras'],
        ['dip bars', 'barras'],
        ['Barra de dominadas', 'barras'],
        ['Paralelas', 'barras'],
        ['Anillas', 'barras'],

        // Pesos libres
        ['Mancuernas', 'pesos_libres'],
        ['Barra olímpica', 'pesos_libres'],
        ['Kettlebell', 'pesos_libres'],

        // Bancos y soportes
        ['Banco', 'bancos_soportes'],

        // Accesorios
        ['Cuerda de saltar', 'accesorios'],
        ['Banda elástica', 'accesorios'],
        ['Colchoneta', 'accesorios'],
    ];

    foreach ($updates as $update) {
        $conn->executeStatement(
            'UPDATE equipments SET category = ? WHERE name = ?',
            [$update[1], $update[0]]
        );
        echo "✅ {$update[0]} → {$update[1]}\n";
    }

    echo "\n🎉 ¡Categorías actualizadas!\n\n";

    // Mostrar resumen por categoría
    $categories = ['barras', 'pesos_libres', 'bancos_soportes', 'accesorios', 'maquinas'];
    foreach ($categories as $cat) {
        $items = $conn->fetchAllAssociative('SELECT name FROM equipments WHERE category = ?', [$cat]);
        if (count($items) > 0) {
            echo strtoupper($cat).' ('.count($items)."):\n";
            foreach ($items as $item) {
                echo "  - {$item['name']}\n";
            }
            echo "\n";
        }
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
}
