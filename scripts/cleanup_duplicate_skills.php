<?php

require __DIR__ . '/../vendor/autoload.php';

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

$conn = $em->getConnection();

// Eliminar skills duplicados por programa + nombre, manteniendo el de menor ID
$conn->executeStatement('
    DELETE s1 FROM training_skills s1
    INNER JOIN training_skills s2
    WHERE s1.id > s2.id
    AND s1.program_id = s2.program_id
    AND s1.name = s2.name
');

echo "Skills duplicados eliminados\n";
