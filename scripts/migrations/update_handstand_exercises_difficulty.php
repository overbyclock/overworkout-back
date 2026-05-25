<?php

declare(strict_types=1);

/**
 * Migracion de datos: Ajustar nivel de dificultad de ejercicios basicos de handstand
 * tras revision con experto entrenador.
 *
 * Criterio: En el contexto de un programa de skill especializado (Handstand Balance Mastery),
 * estos ejercicios son fundamentales de nivel principiante, no intermedio.
 *
 * Ejercicios afectados:
 * - Chest to Wall Handstand: intermediate -> beginner
 * - Frog Stand: intermediate -> beginner
 * - Hollow Body Hold: intermediate -> beginner
 */

require __DIR__ . '/../../vendor/autoload.php';

$dsn = 'mysql:host=127.0.0.1;dbname=overworkout;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '');

$updates = [
    ['name' => 'Chest to Wall Handstand', 'old_level' => 'intermediate', 'new_level' => 'beginner'],
    ['name' => 'Frog Stand', 'old_level' => 'intermediate', 'new_level' => 'beginner'],
    ['name' => 'Hollow Body Hold', 'old_level' => 'intermediate', 'new_level' => 'beginner'],
];

$stmt = $pdo->prepare('UPDATE exercises SET level = :new_level WHERE name = :name AND level = :old_level');

echo "=== ACTUALIZANDO NIVELES DE EJERCICIOS HANDSTAND ===\n\n";

foreach ($updates as $update) {
    $stmt->execute([
        ':name' => $update['name'],
        ':old_level' => $update['old_level'],
        ':new_level' => $update['new_level'],
    ]);

    $affected = $stmt->rowCount();

    if ($affected > 0) {
        echo "✅ [{$update['name']}] {$update['old_level']} -> {$update['new_level']} (filas: {$affected})\n";
    } else {
        echo "⚠️ [{$update['name']}] No se actualizo (posiblemente ya era {$update['new_level']} o no existe)\n";
    }
}

echo "\n=== COMPLETADO ===\n";
