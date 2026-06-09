<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== OverWorkout — Seeder de benchmarks restantes ===\n\n";

// Obtener ejercicios existentes
$exercises = [];
$exerciseNames = [
    'Run', 'Pull Up', 'Push Up', 'Air Squat', 'Deadlift', 'Thruster',
    'Box Jump', 'Wall Ball', 'Kettlebell Swing', 'Burpee', 'Rowing Machine',
    'Double Unders', 'Handstand Push Up', 'Muscle Up', 'Chest to Bar Pull Up',
    'Ring Dip', 'Rope Climb', 'Overhead Squat', 'Power Clean', 'Push Press',
    'Push Jerk', 'Snatch', 'Shoulder Press', 'Back Squat', 'Front Squat', 'Walking Lunge',
    'Wall Walk', 'Pistol Squat', 'L-Sit', 'V-Up', 'GHD Sit Up',
    'Toes to Bar', 'Jumping Pull Up', 'Sumo Deadlift High Pull', 'Ball Slam',
    'Assault Bike', 'Ski Erg', 'Farmer Carry', 'Devil Press', 'Sled Push',
    'Sled Pull', 'Dumbbell Snatch', 'Dumbbell Clean', 'Dumbbell Thruster',
    'Dumbbell Lunge', 'Power Snatch', 'Clean and Jerk', 'Squat Clean',
    'Back Extension', 'Sit-up', 'Bench Press', 'Hang Power Clean', 'Burpee Pull-up',
];

foreach ($exerciseNames as $name) {
    $ex = $em->getRepository(\App\Entity\Exercises::class)->findOneBy(['name' => $name]);
    if (null !== $ex) {
        $exercises[$name] = $ex;
    }
}

echo "Ejercicios cargados: " . count($exercises) . "\n";

// Fallback por si algún ejercicio no cargó por discrepancia de nombre
$fallbacks = [
    'Knees to Elbows' => 522,
];
foreach ($fallbacks as $name => $id) {
    if (!isset($exercises[$name])) {
        $ex = $em->getRepository(\App\Entity\Exercises::class)->find($id);
        if (null !== $ex) {
            $exercises[$name] = $ex;
            echo "  ↩️ Fallback cargado: {$name} (id: {$id})\n";
        }
    }
}

echo "\n";
$wods = [];

// ========== GIRL WODs restantes (10) ==========

// Angie
$wods[] = [
    'name' => 'Angie',
    'type' => 'girl',
    'description' => "Por tiempo:\n\n"
        . "• 100 Pull-ups\n"
        . "• 100 Push-ups\n"
        . "• 100 Sit-ups\n"
        . "• 100 Air Squats\n\n"
        . "Completa todos los reps de un movimiento antes de pasar al siguiente.",
    'scaling' => "Reduce reps a 75 o 50 (intermedio/principiante). "
        . "Pull-ups con banda / Push-ups desde rodillas / Sit-ups abdominales.",
    'elite_male' => '12:00',
    'elite_female' => '14:00',
    'advanced_male' => '17:00',
    'advanced_female' => '20:00',
    'intermediate_male' => '25:00',
    'intermediate_female' => '29:00',
    'beginner_male' => '35:00',
    'beginner_female' => '40:00',
    'exercises' => [
        ['exercise' => $exercises['Pull Up'], 'position' => 1, 'reps' => 100],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 100],
        ['exercise' => $exercises['Sit-up'], 'position' => 3, 'reps' => 100],
        ['exercise' => $exercises['Air Squat'], 'position' => 4, 'reps' => 100],
    ],
];

// Barbara
$wods[] = [
    'name' => 'Barbara',
    'type' => 'girl',
    'description' => "5 rondas por tiempo, cada una por tiempo:\n\n"
        . "• 20 Pull-ups\n"
        . "• 30 Push-ups\n"
        . "• 40 Sit-ups\n"
        . "• 50 Air Squats\n\n"
        . "Rest precisely 3 minutes between rounds.",
    'scaling' => "Reduce rounds a 4 o 3 (intermedio/principiante). "
        . "Reduce reps proporcionalmente: 15/20/25/30.",
    'elite_male' => '15:00',
    'elite_female' => '17:00',
    'advanced_male' => '22:00',
    'advanced_female' => '25:00',
    'intermediate_male' => '30:00',
    'intermediate_female' => '34:00',
    'beginner_male' => '45:00',
    'beginner_female' => '50:00',
    'exercises' => [
        ['exercise' => $exercises['Pull Up'], 'position' => 1, 'reps' => 20],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 30],
        ['exercise' => $exercises['Sit-up'], 'position' => 3, 'reps' => 40],
        ['exercise' => $exercises['Air Squat'], 'position' => 4, 'reps' => 50],
    ],
];

// Chelsea
$wods[] = [
    'name' => 'Chelsea',
    'type' => 'girl',
    'description' => "EMOM durante 30 minutos:\n\n"
        . "• 5 Pull-ups\n"
        . "• 10 Push-ups\n"
        . "• 15 Air Squats\n\n"
        . "Si fallas un minuto, descansa el siguiente y reanuda.",
    'scaling' => "Reduce a 20 o 15 minutos (intermedio/principiante). "
        . "Reduce reps a 3/6/9 o usa asistencias.",
    'elite_male' => '30 rounds',
    'elite_female' => '28 rounds',
    'advanced_male' => '25 rounds',
    'advanced_female' => '23 rounds',
    'intermediate_male' => '20 rounds',
    'intermediate_female' => '18 rounds',
    'beginner_male' => '15 rounds',
    'beginner_female' => '13 rounds',
    'exercises' => [
        ['exercise' => $exercises['Pull Up'], 'position' => 1, 'reps' => 5],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 10],
        ['exercise' => $exercises['Air Squat'], 'position' => 3, 'reps' => 15],
    ],
];

// Eva
$wods[] = [
    'name' => 'Eva',
    'type' => 'girl',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 800 meter Run\n"
        . "• 30 Kettlebell Swings (2 pood / 1.5 pood)\n"
        . "• 30 Pull-ups\n\n"
        . "Benchmark de resistencia cardiovascular y fuerza de agarre.",
    'scaling' => "Reduce peso KB a 24/16 kg (intermedio) o 16/12 kg (principiante). "
        . "Reduce reps a 20/20 (intermedio) o 15/15 (principiante).",
    'elite_male' => '25:00',
    'elite_female' => '28:00',
    'advanced_male' => '32:00',
    'advanced_female' => '36:00',
    'intermediate_male' => '40:00',
    'intermediate_female' => '45:00',
    'beginner_male' => '55:00',
    'beginner_female' => '62:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 800],
        ['exercise' => $exercises['Kettlebell Swing'], 'position' => 2, 'reps' => 30, 'rx_weight_male' => '32kg', 'rx_weight_female' => '24kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => 30],
    ],
];

// Jackie
$wods[] = [
    'name' => 'Jackie',
    'type' => 'girl',
    'description' => "Por tiempo:\n\n"
        . "• 1,000 meter Row\n"
        . "• 50 Thrusters (45 lb / 35 lb)\n"
        . "• 30 Pull-ups\n\n"
        . "Benchmark clásico de remo, barra y gimnasia.",
    'scaling' => "Reduce peso a 35/25 lb (intermedio) o barra vacía (principiante). "
        . "Pull-ups con banda o jumping pull-ups.",
    'elite_male' => '6:00',
    'elite_female' => '7:00',
    'advanced_male' => '8:00',
    'advanced_female' => '9:00',
    'intermediate_male' => '11:00',
    'intermediate_female' => '12:00',
    'beginner_male' => '15:00',
    'beginner_female' => '17:00',
    'exercises' => [
        ['exercise' => $exercises['Rowing Machine'], 'position' => 1, 'distance' => 1000],
        ['exercise' => $exercises['Thruster'], 'position' => 2, 'reps' => 50, 'rx_weight_male' => '20kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => 30],
    ],
];

// Karen
$wods[] = [
    'name' => 'Karen',
    'type' => 'girl',
    'description' => "Por tiempo:\n\n"
        . "• 150 Wall Ball Shots (20 lb / 14 lb)\n\n"
        . "Un movimiento, 150 reps. Test de resistencia de piernas y hombros.",
    'scaling' => "Reduce peso a 14/10 lb (intermedio) o 10/8 lb (principiante). "
        . "Reduce reps a 100 (intermedio) o 75 (principiante).",
    'elite_male' => '4:00',
    'elite_female' => '5:00',
    'advanced_male' => '6:00',
    'advanced_female' => '7:00',
    'intermediate_male' => '9:00',
    'intermediate_female' => '11:00',
    'beginner_male' => '15:00',
    'beginner_female' => '17:00',
    'exercises' => [
        ['exercise' => $exercises['Wall Ball'], 'position' => 1, 'reps' => 150, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
    ],
];

// Kelly
$wods[] = [
    'name' => 'Kelly',
    'type' => 'girl',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 400 meter Run\n"
        . "• 30 Box Jumps (24\" / 20\")\n"
        . "• 30 Wall Ball Shots (20 lb / 14 lb)\n\n"
        . "Benchmark de resistencia cardiovascular y potencia.",
    'scaling' => "Box jumps a step-ups (intermedio/principiante). "
        . "Reduce peso WB o reps a 20 (intermedio) o 15 (principiante).",
    'elite_male' => '18:00',
    'elite_female' => '20:00',
    'advanced_male' => '25:00',
    'advanced_female' => '28:00',
    'intermediate_male' => '35:00',
    'intermediate_female' => '39:00',
    'beginner_male' => '50:00',
    'beginner_female' => '55:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 400],
        ['exercise' => $exercises['Box Jump'], 'position' => 2, 'reps' => 30],
        ['exercise' => $exercises['Wall Ball'], 'position' => 3, 'reps' => 30, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
    ],
];

// Linda
$wods[] = [
    'name' => 'Linda',
    'type' => 'girl',
    'description' => "10-9-8-7-6-5-4-3-2-1 reps por tiempo:\n\n"
        . "• Deadlift (1.5× bodyweight)\n"
        . "• Bench Press (bodyweight)\n"
        . "• Clean (0.75× bodyweight)\n\n"
        . "Conocida como 'The Three Bars of Death'. Escalera descendente con pesos basados en BW.",
    'scaling' => "Reduce porcentajes a 1.0×/0.75×/0.5× (intermedio) o 0.75×/0.5×/0.5× (principiante). "
        . "O usa pesos fijos: 100/70/50 kg aprox.",
    'elite_male' => '12:00',
    'elite_female' => '14:00',
    'advanced_male' => '17:00',
    'advanced_female' => '20:00',
    'intermediate_male' => '25:00',
    'intermediate_female' => '29:00',
    'beginner_male' => '40:00',
    'beginner_female' => '46:00',
    'exercises' => [
        ['exercise' => $exercises['Deadlift'], 'position' => 1, 'reps' => 55, 'rx_weight_male' => '1.5xBW', 'rx_weight_female' => '1.5xBW'],
        ['exercise' => $exercises['Bench Press'], 'position' => 2, 'reps' => 55, 'rx_weight_male' => 'BW', 'rx_weight_female' => 'BW'],
        ['exercise' => $exercises['Squat Clean'], 'position' => 3, 'reps' => 55, 'rx_weight_male' => '0.75xBW', 'rx_weight_female' => '0.75xBW'],
    ],
];

// Nancy
$wods[] = [
    'name' => 'Nancy',
    'type' => 'girl',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 400 meter Run\n"
        . "• 15 Overhead Squats (95 lb / 65 lb)\n\n"
        . "Benchmark de resistencia de sentadilla overhead y cardio.",
    'scaling' => "Reduce peso a 65/45 lb (intermedio) o 45/35 lb (principiante). "
        . "OHS a front squats si la movilidad de hombros lo limita.",
    'elite_male' => '12:00',
    'elite_female' => '14:00',
    'advanced_male' => '15:00',
    'advanced_female' => '17:00',
    'intermediate_male' => '20:00',
    'intermediate_female' => '22:00',
    'beginner_male' => '30:00',
    'beginner_female' => '33:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 400],
        ['exercise' => $exercises['Overhead Squat'], 'position' => 2, 'reps' => 15, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
    ],
];

// Nicole
$wods[] = [
    'name' => 'Nicole',
    'type' => 'girl',
    'description' => "AMRAP en 20 minutos:\n\n"
        . "• 400 meter Run\n"
        . "• Max reps Pull-ups\n\n"
        . "Score = número total de pull-ups completados.",
    'scaling' => "Reduce a 20 min AMRAP con carrera de 200m (principiante). "
        . "Pull-ups con banda o ring rows.",
    'elite_male' => '15 rounds',
    'elite_female' => '14 rounds',
    'advanced_male' => '12 rounds',
    'advanced_female' => '11 rounds',
    'intermediate_male' => '9 rounds',
    'intermediate_female' => '8 rounds',
    'beginner_male' => '6 rounds',
    'beginner_female' => '5 rounds',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 400],
        ['exercise' => $exercises['Pull Up'], 'position' => 2, 'reps' => null],
    ],
];


// ========== Otros benchmarks (5) ==========

// Fight Gone Bad
$wods[] = [
    'name' => 'Fight Gone Bad',
    'type' => 'benchmark',
    'description' => "3 rondas por m�ximo de reps de 1 minuto en cada estaci�n:\n\n"
        . "• Wall Ball Shots (20 lb / 14 lb)\n"
        . "• Sumo Deadlift High Pulls (75 lb / 55 lb)\n"
        . "• Box Jumps (20\")\n"
        . "• Push Presses (75 lb / 55 lb)\n"
        . "• Row (calories)\n\n"
        . "Rest 1 minute between rounds. Puntuaci�n = total de reps + calor�as.",
    'scaling' => "Reduce pesos a 45/35 lb (intermedio) o 35/25 lb (principiante). "
        . "Box jumps a step-ups. Reduce a 2 rounds (principiante).",
    'elite_male' => '400 pts',
    'elite_female' => '360 pts',
    'advanced_male' => '350 pts',
    'advanced_female' => '315 pts',
    'intermediate_male' => '300 pts',
    'intermediate_female' => '270 pts',
    'beginner_male' => '250 pts',
    'beginner_female' => '225 pts',
    'exercises' => [
        ['exercise' => $exercises['Wall Ball'], 'position' => 1, 'duration' => 60, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Sumo Deadlift High Pull'], 'position' => 2, 'duration' => 60, 'rx_weight_male' => '34kg', 'rx_weight_female' => '25kg'],
        ['exercise' => $exercises['Box Jump'], 'position' => 3, 'duration' => 60],
        ['exercise' => $exercises['Push Press'], 'position' => 4, 'duration' => 60, 'rx_weight_male' => '34kg', 'rx_weight_female' => '25kg'],
        ['exercise' => $exercises['Rowing Machine'], 'position' => 5, 'duration' => 60],
    ],
];

// Filthy 50
$wods[] = [
    'name' => 'Filthy 50',
    'type' => 'benchmark',
    'description' => "Por tiempo, 50 reps of each:\n\n"
        . "• Box Jumps (24\" / 20\")\n"
        . "• Jumping Pull-ups\n"
        . "• Kettlebell Swings (35 lb / 25 lb)\n"
        . "• Walking Lunges\n"
        . "• Knees-to-Elbows\n"
        . "• Push Presses (45 lb / 35 lb)\n"
        . "• Back Extensions\n"
        . "• Wall Ball Shots (20 lb / 14 lb)\n"
        . "• Burpees\n"
        . "• Double-unders\n\n"
        . "El chipper clásico de CrossFit. 500 reps totales.",
    'scaling' => "'Dirty 30' — 30 reps de cada (intermedio/principiante). "
        . "Reduce pesos KB y WB. Single-unders en vez de double-unders.",
    'elite_male' => '18:00',
    'elite_female' => '21:00',
    'advanced_male' => '25:00',
    'advanced_female' => '29:00',
    'intermediate_male' => '35:00',
    'intermediate_female' => '40:00',
    'beginner_male' => '50:00',
    'beginner_female' => '58:00',
    'exercises' => [
        ['exercise' => $exercises['Box Jump'], 'position' => 1, 'reps' => 50],
        ['exercise' => $exercises['Jumping Pull Up'], 'position' => 2, 'reps' => 50],
        ['exercise' => $exercises['Kettlebell Swing'], 'position' => 3, 'reps' => 50, 'rx_weight_male' => '16kg', 'rx_weight_female' => '12kg'],
        ['exercise' => $exercises['Walking Lunge'], 'position' => 4, 'reps' => 50],
        ['exercise' => $exercises['Knees to Elbows'], 'position' => 5, 'reps' => 50],
        ['exercise' => $exercises['Push Press'], 'position' => 6, 'reps' => 50, 'rx_weight_male' => '20kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Back Extension'], 'position' => 7, 'reps' => 50],
        ['exercise' => $exercises['Wall Ball'], 'position' => 8, 'reps' => 50, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Burpee'], 'position' => 9, 'reps' => 50],
        ['exercise' => $exercises['Double Unders'], 'position' => 10, 'reps' => 50],
    ],
];

// The Seven
$wods[] = [
    'name' => 'The Seven',
    'type' => 'benchmark',
    'description' => "7 rondas por tiempo:\n\n"
        . "• 7 Handstand Push-ups\n"
        . "• 7 Thrusters (135 lb / 95 lb)\n"
        . "• 7 Knees-to-Elbows\n"
        . "• 7 Deadlifts (245 lb / 165 lb)\n"
        . "• 7 Burpees\n"
        . "• 7 Kettlebell Swings (2 pood / 1.5 pood)\n"
        . "• 7 Pull-ups\n\n"
        . "Benchmark dedicado a los 7 oficiales de la CIA caídos en Afganistán en 2009.",
    'scaling' => "Reduce pesos: thrusters a 95/65, deadlifts a 185/125, KB a 24/16 kg (intermedio). "
        . "HSPU a pike push-ups. KTE a hanging knee raises.",
    'elite_male' => '18:00',
    'elite_female' => '21:00',
    'advanced_male' => '25:00',
    'advanced_female' => '29:00',
    'intermediate_male' => '35:00',
    'intermediate_female' => '40:00',
    'beginner_male' => '50:00',
    'beginner_female' => '58:00',
    'exercises' => [
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 1, 'reps' => 7],
        ['exercise' => $exercises['Thruster'], 'position' => 2, 'reps' => 7, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
        ['exercise' => $exercises['Knees to Elbows'], 'position' => 3, 'reps' => 7],
        ['exercise' => $exercises['Deadlift'], 'position' => 4, 'reps' => 7, 'rx_weight_male' => '111kg', 'rx_weight_female' => '75kg'],
        ['exercise' => $exercises['Burpee'], 'position' => 5, 'reps' => 7],
        ['exercise' => $exercises['Kettlebell Swing'], 'position' => 6, 'reps' => 7, 'rx_weight_male' => '32kg', 'rx_weight_female' => '24kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 7, 'reps' => 7],
    ],
];

// Lynne
$wods[] = [
    'name' => 'Lynne',
    'type' => 'benchmark',
    'description' => "5 rondas por m�ximo de reps:\n\n"
        . "• Bench Press (bodyweight)\n"
        . "• Pull-ups\n\n"
        . "Score = reps totales de ambos movimientos sumados.",
    'scaling' => "Reduce peso bench a 0.75×BW (intermedio) o 0.5×BW (principiante). "
        . "Pull-ups con banda o ring rows.",
    'elite_male' => '15 rounds',
    'elite_female' => '13 rounds',
    'advanced_male' => '12 rounds',
    'advanced_female' => '10 rounds',
    'intermediate_male' => '9 rounds',
    'intermediate_female' => '8 rounds',
    'beginner_male' => '6 rounds',
    'beginner_female' => '5 rounds',
    'exercises' => [
        ['exercise' => $exercises['Bench Press'], 'position' => 1, 'reps' => null, 'rx_weight_male' => 'BW', 'rx_weight_female' => 'BW'],
        ['exercise' => $exercises['Pull Up'], 'position' => 2, 'reps' => null],
    ],
];

// Tabata Something Else
$wods[] = [
    'name' => 'Tabata Something',
    'type' => 'benchmark',
    'description' => "Intervalos Tabata (20s trabajo / 10s descanso × 8 rondas) de:\n\n"
        . "• Pull-ups\n"
        . "• Push-ups\n"
        . "• Sit-ups\n"
        . "• Air Squats\n\n"
        . "Score = suma total de reps de los 4 movimientos.",
    'scaling' => "Reduce intervalos a 6 o 4 rounds (intermedio/principiante). "
        . "Usa asistencias para pull-ups y push-ups.",
    'elite_male' => '100 reps',
    'elite_female' => '90 reps',
    'advanced_male' => '80 reps',
    'advanced_female' => '72 reps',
    'intermediate_male' => '60 reps',
    'intermediate_female' => '54 reps',
    'beginner_male' => '40 reps',
    'beginner_female' => '36 reps',
    'exercises' => [
        ['exercise' => $exercises['Pull Up'], 'position' => 1, 'duration' => 240],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'duration' => 240],
        ['exercise' => $exercises['Sit-up'], 'position' => 3, 'duration' => 240],
        ['exercise' => $exercises['Air Squat'], 'position' => 4, 'duration' => 240],
    ],
];

// ------------------------------------------------------------------
// Ejecutar seed
// ------------------------------------------------------------------

echo "--- Procesando benchmarks restantes ---\n";

foreach ($wods as $wod) {
    $helper->seedBenchmark($wod);
}

echo "\n=== Seeder completado con éxito ===\n";
