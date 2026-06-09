<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__ . '/../../.env');

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

$exercises = $entityManager->getRepository(\App\Entity\Exercises::class)
    ->createQueryBuilder('e')
    ->where('e.executionInstructions IS NULL')
    ->orderBy('e.name', 'ASC')
    ->getQuery()
    ->getResult();

$names = [];
foreach ($exercises as $exercise) {
    $names[] = $exercise->getName();
}

sort($names);

// Categorizar por familias
$families = [
    'Push Up' => [],
    'Pull Up' => [],
    'Chin Up' => [],
    'Muscle Up' => [],
    'Dip' => [],
    'Row' => [],
    'Squat' => [],
    'Lunge' => [],
    'Deadlift' => [],
    'Hinge' => [],
    'Core' => [],
    'Plank' => [],
    'Hollow' => [],
    'L-Sit' => [],
    'Handstand' => [],
    'Pike' => [],
    'Planche' => [],
    'Front Lever' => [],
    'Back Lever' => [],
    'Skin the Cat' => [],
    'Ring' => [],
    'Run' => [],
    'Jump' => [],
    'Burpee' => [],
    'Clean' => [],
    'Snatch' => [],
    'Jerk' => [],
    'Press' => [],
    'Curl' => [],
    'Extension' => [],
    'Raise' => [],
    'Machine' => [],
    'Cable' => [],
    'Band' => [],
    'Kettlebell' => [],
    'Thruster' => [],
    'Wall Ball' => [],
    'Rope' => [],
    'Bike' => [],
    'Rowing' => [],
    'Climb' => [],
    'Bear Crawl' => [],
    'Crawl' => [],
    'Swing' => [],
    'Farmers' => [],
    'Sled' => [],
    'Carry' => [],
    'Turkish' => [],
    'Other' => [],
];

foreach ($names as $name) {
    $matched = false;
    $lower = strtolower($name);

    foreach ($families as $keyword => &$list) {
        if (str_contains($lower, strtolower($keyword))) {
            $list[] = $name;
            $matched = true;
            break;
        }
    }

    if (!$matched) {
        $families['Other'][] = $name;
    }
}

$totalCategorized = 0;
foreach ($families as $family => $list) {
    $count = count($list);
    if ($count > 0) {
        echo "\n=== {$family} ({$count}) ===\n";
        foreach (array_slice($list, 0, 10) as $item) {
            echo "  - {$item}\n";
        }
        if ($count > 10) {
            echo "  ... y " . ($count - 10) . " más\n";
        }
        $totalCategorized += $count;
    }
}

echo "\n\n📊 Total ejercicios sin contenido: " . count($names) . "\n";
echo "📊 Total categorizados: {$totalCategorized}\n";
