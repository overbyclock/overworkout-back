<?php

require __DIR__ . '/../vendor/autoload.php';

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
$conn = $em->getConnection();

try {
    $conn->executeStatement('ALTER TABLE achievements ADD program_id INT DEFAULT NULL');
    echo "Columna program_id añadida\n";
} catch (Exception $e) {
    echo "Columna ya existe o error: " . $e->getMessage() . "\n";
}

try {
    $conn->executeStatement('ALTER TABLE achievements ADD CONSTRAINT FK_ACHIEVEMENT_PROGRAM FOREIGN KEY (program_id) REFERENCES training_programs (id)');
    echo "Foreign key añadida\n";
} catch (Exception $e) {
    echo "FK ya existe o error: " . $e->getMessage() . "\n";
}

try {
    $conn->executeStatement('CREATE INDEX IDX_ACHIEVEMENT_PROGRAM ON achievements (program_id)');
    echo "Índice añadido\n";
} catch (Exception $e) {
    echo "Índice ya existe o error: " . $e->getMessage() . "\n";
}
