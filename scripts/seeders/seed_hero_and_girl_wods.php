<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

use App\Enum\MuscleGroup;
use App\Enum\Levels;

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== OverWorkout — Seeder de Hero & Girl WODs ===\n\n";

// ------------------------------------------------------------------
// 1. Obtener ejercicios existentes
// ------------------------------------------------------------------
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
];

foreach ($exerciseNames as $name) {
    $ex = $em->getRepository(\App\Entity\Exercises::class)->findOneBy(['name' => $name]);
    if (null !== $ex) {
        $exercises[$name] = $ex;
    }
}

// ------------------------------------------------------------------
// 2. Crear ejercicios faltantes que necesitamos
// ------------------------------------------------------------------
$toCreate = [
    ['Back Extension', MuscleGroup::BACK, Levels::NOLEVEL, 1],
    ['Sit-up', MuscleGroup::CORE, Levels::NOLEVEL, 1],
    ['Bench Press', MuscleGroup::CHEST, Levels::NOLEVEL, 1],
    ['Hang Power Clean', MuscleGroup::FULL_BODY, Levels::NOLEVEL, 3],
    ['Burpee Pull-up', MuscleGroup::FULL_BODY, Levels::NOLEVEL, 3],
];

foreach ($toCreate as $item) {
    $exercises[$item[0]] = $helper->findOrCreateExercise($item[0], $item[1], $item[2], $item[3]);
}

echo "\n";

// ------------------------------------------------------------------
// 3. Definición de los 23 WODs (14 Hero + 9 Girl)
// ------------------------------------------------------------------

$wods = [];

// ========== HERO WODs ==========

// Murph
$wods[] = [
    'name' => 'Murph',
    'type' => 'hero',
    'description' => "Por tiempo (particiona pull-ups/push-ups/squats como necesites):\n\n"
        . "• 1 mile Run\n"
        . "• 100 Pull-ups\n"
        . "• 200 Push-ups\n"
        . "• 300 Air Squats\n"
        . "• 1 mile Run\n\n"
        . "RX: 20 lb vest (male) / 14 lb vest (female).\n\n"
        . "Hero WOD dedicado al Teniente de la Marina de EE.UU. Michael P. Murphy, "
        . "fallecido el 28 de junio de 2005 en Afganistán. Se realiza cada Memorial Day.",
    'scaling' => "Sin chaleco / Media Murph (800m, 50/100/150, 800m) / "
        . "Anillos asistidos / Push-ups desde rodillas / Air squats reducidas",
    'elite_male' => '32:00',
    'elite_female' => '36:00',
    'advanced_male' => '40:00',
    'advanced_female' => '46:00',
    'intermediate_male' => '52:00',
    'intermediate_female' => '58:00',
    'beginner_male' => '70:00',
    'beginner_female' => '75:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 1609],
        ['exercise' => $exercises['Pull Up'], 'position' => 2, 'reps' => 100],
        ['exercise' => $exercises['Push Up'], 'position' => 3, 'reps' => 200],
        ['exercise' => $exercises['Air Squat'], 'position' => 4, 'reps' => 300],
        ['exercise' => $exercises['Run'], 'position' => 5, 'distance' => 1609],
    ],
];

// DT
$wods[] = [
    'name' => 'DT',
    'type' => 'hero',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 12 Deadlifts (155 lb / 105 lb)\n"
        . "• 9 Hang Power Cleans (155 lb / 105 lb)\n"
        . "• 6 Push Jerks (155 lb / 105 lb)\n\n"
        . "Hero WOD dedicado al Sargento de la Fuerza Aérea de EE.UU. Timothy P. Davis, "
        . "fallecido el 20 de febrero de 2009 en Afganistán.",
    'scaling' => "Reduce peso a 115/75 lb (intermedio) o 75/55 lb (principiante). "
        . "Mantener 5 rondas y esquema de reps.",
    'elite_male' => '5:00',
    'elite_female' => '6:30',
    'advanced_male' => '8:00',
    'advanced_female' => '10:00',
    'intermediate_male' => '12:00',
    'intermediate_female' => '15:00',
    'beginner_male' => '18:00',
    'beginner_female' => '22:00',
    'exercises' => [
        ['exercise' => $exercises['Deadlift'], 'position' => 1, 'reps' => 12, 'rx_weight_male' => '70kg', 'rx_weight_female' => '47kg'],
        ['exercise' => $exercises['Hang Power Clean'], 'position' => 2, 'reps' => 9, 'rx_weight_male' => '70kg', 'rx_weight_female' => '47kg'],
        ['exercise' => $exercises['Push Jerk'], 'position' => 3, 'reps' => 6, 'rx_weight_male' => '70kg', 'rx_weight_female' => '47kg'],
    ],
];

// Michael
$wods[] = [
    'name' => 'Michael',
    'type' => 'hero',
    'description' => "3 rondas por tiempo:\n\n"
        . "• 800 meter Run\n"
        . "• 50 Back Extensions\n"
        . "• 50 Sit-ups\n\n"
        . "Hero WOD dedicado al Teniente de la Marina de EE.UU. Michael McGreevy, "
        . "fallecido el 28 de junio de 2005 en Afganistán.",
    'scaling' => "Reduce reps a 35/35 (intermedio) o 25/25 (principiante). "
        . "Back extensions en suelo o good mornings con barra ligera.",
    'elite_male' => '14:00',
    'elite_female' => '16:30',
    'advanced_male' => '18:00',
    'advanced_female' => '22:00',
    'intermediate_male' => '26:00',
    'intermediate_female' => '30:00',
    'beginner_male' => '38:00',
    'beginner_female' => '44:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 800],
        ['exercise' => $exercises['Back Extension'], 'position' => 2, 'reps' => 50],
        ['exercise' => $exercises['Sit-up'], 'position' => 3, 'reps' => 50],
    ],
];

// Ryan
$wods[] = [
    'name' => 'Ryan',
    'type' => 'hero',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 7 Muscle-ups\n"
        . "• 21 Burpees\n\n"
        . "Hero WOD dedicado al bombero Ryan Hummert de Maplewood, Missouri, "
        . "fallecido en cumplimiento del deber el 13 de agosto de 2008.",
    'scaling' => "Muscle-ups asistidos (banda) o 14 pull-ups + 14 dips por ronda. "
        . "Burpees a step-over (principiante).",
    'elite_male' => '9:00',
    'elite_female' => '11:00',
    'advanced_male' => '13:00',
    'advanced_female' => '16:00',
    'intermediate_male' => '20:00',
    'intermediate_female' => '24:00',
    'beginner_male' => '32:00',
    'beginner_female' => '38:00',
    'exercises' => [
        ['exercise' => $exercises['Muscle Up'], 'position' => 1, 'reps' => 7],
        ['exercise' => $exercises['Burpee'], 'position' => 2, 'reps' => 21],
    ],
];

// JT
$wods[] = [
    'name' => 'JT',
    'type' => 'hero',
    'description' => "21-15-9 reps por tiempo:\n\n"
        . "• Handstand Push-ups\n"
        . "• Ring Dips\n"
        . "• Push-ups\n\n"
        . "Hero WOD dedicado al Suboficial de 1ª Clase Jeff Taylor de la Marina de EE.UU., "
        . "fallecido el 11 de junio de 2005 en Afganistán.",
    'scaling' => "HSPU con kipping o pike push-ups (intermedio) / push-ups desde rodillas (principiante). "
        . "Ring dips con banda o bench dips.",
    'elite_male' => '4:30',
    'elite_female' => '6:30',
    'advanced_male' => '7:00',
    'advanced_female' => '10:00',
    'intermediate_male' => '12:00',
    'intermediate_female' => '16:00',
    'beginner_male' => '20:00',
    'beginner_female' => '26:00',
    'exercises' => [
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 1, 'reps' => 21],
        ['exercise' => $exercises['Ring Dip'], 'position' => 2, 'reps' => 21],
        ['exercise' => $exercises['Push Up'], 'position' => 3, 'reps' => 21],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 4, 'reps' => 15],
        ['exercise' => $exercises['Ring Dip'], 'position' => 5, 'reps' => 15],
        ['exercise' => $exercises['Push Up'], 'position' => 6, 'reps' => 15],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 7, 'reps' => 9],
        ['exercise' => $exercises['Ring Dip'], 'position' => 8, 'reps' => 9],
        ['exercise' => $exercises['Push Up'], 'position' => 9, 'reps' => 9],
    ],
];

// Randy
$wods[] = [
    'name' => 'Randy',
    'type' => 'hero',
    'description' => "75 Power Snatches por tiempo:\n\n"
        . "• 75 lb (male) / 55 lb (female)\n\n"
        . "Hero WOD dedicado al veterano del LAPD SWAT Randy Simmons, "
        . "fallecido en cumplimiento del deber el 7 de febrero de 2008.",
    'scaling' => "Reduce peso a 55/35 lb (intermedio) o 45/25 lb (principiante). "
        . "Power snatch desde hang si la técnica desde suelo no está consolidada.",
    'elite_male' => '3:30',
    'elite_female' => '4:30',
    'advanced_male' => '5:30',
    'advanced_female' => '7:00',
    'intermediate_male' => '9:00',
    'intermediate_female' => '11:00',
    'beginner_male' => '14:00',
    'beginner_female' => '17:00',
    'exercises' => [
        ['exercise' => $exercises['Power Snatch'], 'position' => 1, 'reps' => 75, 'rx_weight_male' => '34kg', 'rx_weight_female' => '25kg'],
    ],
];

// Roy
$wods[] = [
    'name' => 'Roy',
    'type' => 'hero',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 15 Deadlifts (225 lb / 155 lb)\n"
        . "• 20 Box Jumps (24\" / 20\")\n"
        . "• 25 Pull-ups\n\n"
        . "Hero WOD dedicado al Sargento del Cuerpo de Marines de EE.UU. Michael C. Roy, "
        . "fallecido en Irak el 22 de octubre de 2006.",
    'scaling' => "Reduce peso muerto a 155/105 lb (intermedio) o 95/65 lb (principiante). "
        . "Box jumps a step-ups / Pull-ups con banda o jumping pull-ups.",
    'elite_male' => '11:00',
    'elite_female' => '14:00',
    'advanced_male' => '16:00',
    'advanced_female' => '20:00',
    'intermediate_male' => '23:00',
    'intermediate_female' => '28:00',
    'beginner_male' => '35:00',
    'beginner_female' => '42:00',
    'exercises' => [
        ['exercise' => $exercises['Deadlift'], 'position' => 1, 'reps' => 15, 'rx_weight_male' => '102kg', 'rx_weight_female' => '70kg'],
        ['exercise' => $exercises['Box Jump'], 'position' => 2, 'reps' => 20],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => 25],
    ],
];

// Jason
$wods[] = [
    'name' => 'Jason',
    'type' => 'hero',
    'description' => "Por tiempo:\n\n"
        . "• 100 Squats\n"
        . "• 5 Muscle-ups\n"
        . "• 75 Squats\n"
        . "• 10 Muscle-ups\n"
        . "• 50 Squats\n"
        . "• 15 Muscle-ups\n"
        . "• 25 Squats\n"
        . "• 20 Muscle-ups\n\n"
        . "Hero WOD dedicado al Navy SEAL Jason Dale Lewis, "
        . "fallecido el 6 de julio de 2007 en Irak.",
    'scaling' => "Muscle-ups asistidos (banda) o sub a 10 pull-ups + 10 dips por set. "
        . "Air squats a box squats si la movilidad lo requiere.",
    'elite_male' => '14:00',
    'elite_female' => '17:00',
    'advanced_male' => '20:00',
    'advanced_female' => '25:00',
    'intermediate_male' => '30:00',
    'intermediate_female' => '38:00',
    'beginner_male' => '45:00',
    'beginner_female' => '55:00',
    'exercises' => [
        ['exercise' => $exercises['Air Squat'], 'position' => 1, 'reps' => 100],
        ['exercise' => $exercises['Muscle Up'], 'position' => 2, 'reps' => 5],
        ['exercise' => $exercises['Air Squat'], 'position' => 3, 'reps' => 75],
        ['exercise' => $exercises['Muscle Up'], 'position' => 4, 'reps' => 10],
        ['exercise' => $exercises['Air Squat'], 'position' => 5, 'reps' => 50],
        ['exercise' => $exercises['Muscle Up'], 'position' => 6, 'reps' => 15],
        ['exercise' => $exercises['Air Squat'], 'position' => 7, 'reps' => 25],
        ['exercise' => $exercises['Muscle Up'], 'position' => 8, 'reps' => 20],
    ],
];

// Badger
$wods[] = [
    'name' => 'Badger',
    'type' => 'hero',
    'description' => "3 rondas por tiempo:\n\n"
        . "• 30 Squat Cleans (95 lb / 65 lb)\n"
        . "• 30 Pull-ups\n"
        . "• 800 meter Run\n\n"
        . "Hero WOD dedicado al Chief Petty Officer Mark Carter de la Marina de EE.UU., "
        . "fallecido en Irak el 11 de diciembre de 2007.",
    'scaling' => "Reduce peso a 75/55 lb (intermedio) o 45/35 lb (principiante). "
        . "Squat cleans a power cleans + front squats. Pull-ups con banda.",
    'elite_male' => '11:00',
    'elite_female' => '14:00',
    'advanced_male' => '16:00',
    'advanced_female' => '20:00',
    'intermediate_male' => '25:00',
    'intermediate_female' => '30:00',
    'beginner_male' => '38:00',
    'beginner_female' => '46:00',
    'exercises' => [
        ['exercise' => $exercises['Squat Clean'], 'position' => 1, 'reps' => 30, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 2, 'reps' => 30],
        ['exercise' => $exercises['Run'], 'position' => 3, 'distance' => 800],
    ],
];

// Clovis
$wods[] = [
    'name' => 'Clovis',
    'type' => 'hero',
    'description' => "Por tiempo (particiona la carrera y los burpee pull-ups como necesites):\n\n"
        . "• Run 10 miles\n"
        . "• 150 Burpee Pull-ups\n\n"
        . "Hero WOD dedicado al Cpl. Clovis T. Ray del Ejército de EE.UU., "
        . "fallecido el 15 de julio de 2012 en Afganistán.",
    'scaling' => "Reduce run a 5 millas (intermedio) o 3 millas (principiante). "
        . "Burpee pull-ups a burpees + jumping pull-ups por separado.",
    'elite_male' => '50:00',
    'elite_female' => '60:00',
    'advanced_male' => '65:00',
    'advanced_female' => '78:00',
    'intermediate_male' => '85:00',
    'intermediate_female' => '100:00',
    'beginner_male' => '120:00',
    'beginner_female' => '140:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 16093],
        ['exercise' => $exercises['Burpee Pull-up'], 'position' => 2, 'reps' => 150],
    ],
];

// Holleyman
$wods[] = [
    'name' => 'Holleyman',
    'type' => 'hero',
    'description' => "30 rondas por tiempo:\n\n"
        . "• 5 Wall Ball Shots (20 lb / 14 lb)\n"
        . "• 3 Handstand Push-ups\n"
        . "• 1 Power Clean (225 lb / 155 lb)\n\n"
        . "Hero WOD dedicado al Sargento del Ejército de EE.UU. Aaron Holleyman, "
        . "fallecido el 30 de agosto de 2004 en Iraq.",
    'scaling' => "Reduce clean a 155/105 lb (intermedio) o 115/75 lb (principiante). "
        . "HSPU a pike push-ups o push presses con mancuernas. Wall ball a 14/10 lb.",
    'elite_male' => '11:00',
    'elite_female' => '14:00',
    'advanced_male' => '16:00',
    'advanced_female' => '20:00',
    'intermediate_male' => '25:00',
    'intermediate_female' => '30:00',
    'beginner_male' => '40:00',
    'beginner_female' => '48:00',
    'exercises' => [
        ['exercise' => $exercises['Wall Ball'], 'position' => 1, 'reps' => 5, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 2, 'reps' => 3],
        ['exercise' => $exercises['Power Clean'], 'position' => 3, 'reps' => 1, 'rx_weight_male' => '102kg', 'rx_weight_female' => '70kg'],
    ],
];

// Kalsu
$wods[] = [
    'name' => 'Kalsu',
    'type' => 'hero',
    'description' => "Por tiempo:\n\n"
        . "• 100 Thrusters (135 lb / 95 lb)\n"
        . "• 5 Burpees al inicio y al tope de cada minuto (EMOM)\n\n"
        . "Hero WOD dedicado a Robert James 'Bob' Kalsu, jugador de fútbol americano "
        . "y soldado fallecido en Vietnam el 21 de julio de 1970.",
    'scaling' => "Reduce peso a 95/65 lb (intermedio) o 75/55 lb (principiante). "
        . "Burpees a 3 reps por minuto (principiante).",
    'elite_male' => '11:00',
    'elite_female' => '13:30',
    'advanced_male' => '15:00',
    'advanced_female' => '18:00',
    'intermediate_male' => '20:00',
    'intermediate_female' => '24:00',
    'beginner_male' => '30:00',
    'beginner_female' => '36:00',
    'exercises' => [
        ['exercise' => $exercises['Burpee'], 'position' => 1, 'reps' => 5, 'rest' => 60],
        ['exercise' => $exercises['Thruster'], 'position' => 2, 'reps' => 100, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
    ],
];

// Tommy V
$wods[] = [
    'name' => 'Tommy V',
    'type' => 'hero',
    'description' => "Por tiempo:\n\n"
        . "• 21 Thrusters (115 lb / 75 lb)\n"
        . "• 12 Rope Climbs (15 ft)\n"
        . "• 15 Thrusters (115 lb / 75 lb)\n"
        . "• 9 Rope Climbs (15 ft)\n"
        . "• 9 Thrusters (115 lb / 75 lb)\n"
        . "• 6 Rope Climbs (15 ft)\n\n"
        . "Hero WOD dedicado al Senior Chief Petty Officer Thomas J. Valentine, "
        . "fallecido en un accidente de entrenamiento el 13 de febrero de 2008.",
    'scaling' => "Reduce peso a 95/65 lb (intermedio) o 75/55 lb (principiante). "
        . "Rope climbs a 5 pull-ups + 5 toes-to-bar por ascenso. Sub a escalada de cuerda sentado.",
    'elite_male' => '7:30',
    'elite_female' => '9:30',
    'advanced_male' => '12:00',
    'advanced_female' => '15:00',
    'intermediate_male' => '18:00',
    'intermediate_female' => '22:00',
    'beginner_male' => '30:00',
    'beginner_female' => '36:00',
    'exercises' => [
        ['exercise' => $exercises['Thruster'], 'position' => 1, 'reps' => 21, 'rx_weight_male' => '52kg', 'rx_weight_female' => '34kg'],
        ['exercise' => $exercises['Rope Climb'], 'position' => 2, 'reps' => 12],
        ['exercise' => $exercises['Thruster'], 'position' => 3, 'reps' => 15, 'rx_weight_male' => '52kg', 'rx_weight_female' => '34kg'],
        ['exercise' => $exercises['Rope Climb'], 'position' => 4, 'reps' => 9],
        ['exercise' => $exercises['Thruster'], 'position' => 5, 'reps' => 9, 'rx_weight_male' => '52kg', 'rx_weight_female' => '34kg'],
        ['exercise' => $exercises['Rope Climb'], 'position' => 6, 'reps' => 6],
    ],
];

// Zachary
$wods[] = [
    'name' => 'Zachary',
    'type' => 'hero',
    'description' => "Por tiempo (chipper acumulativo):\n\n"
        . "• 10 Burpees\n"
        . "• 25 Push-ups\n"
        . "• 10 Burpees\n"
        . "• 25 Push-ups\n"
        . "• 50 Walking Lunges\n"
        . "• 10 Burpees\n"
        . "• 25 Push-ups\n"
        . "• 50 Walking Lunges\n"
        . "• 100 Sit-ups\n"
        . "• 10 Burpees\n"
        . "• 25 Push-ups\n"
        . "• 50 Walking Lunges\n"
        . "• 100 Sit-ups\n"
        . "• 150 Air Squats\n\n"
        . "Hero WOD dedicado al Sargento del Ejército de EE.UU. Zachary Tellier, "
        . "fallecido el 29 de septiembre de 2007 en Afganistán.",
    'scaling' => "Reduce reps a la mitad (intermedio) o un tercio (principiante). "
        . "Push-ups desde rodillas / Sit-ups en abdominales crunch.",
    'elite_male' => '9:00',
    'elite_female' => '11:00',
    'advanced_male' => '13:00',
    'advanced_female' => '16:00',
    'intermediate_male' => '20:00',
    'intermediate_female' => '24:00',
    'beginner_male' => '32:00',
    'beginner_female' => '38:00',
    'exercises' => [
        ['exercise' => $exercises['Burpee'], 'position' => 1, 'reps' => 10],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 25],
        ['exercise' => $exercises['Burpee'], 'position' => 3, 'reps' => 10],
        ['exercise' => $exercises['Push Up'], 'position' => 4, 'reps' => 25],
        ['exercise' => $exercises['Walking Lunge'], 'position' => 5, 'reps' => 50],
        ['exercise' => $exercises['Burpee'], 'position' => 6, 'reps' => 10],
        ['exercise' => $exercises['Push Up'], 'position' => 7, 'reps' => 25],
        ['exercise' => $exercises['Walking Lunge'], 'position' => 8, 'reps' => 50],
        ['exercise' => $exercises['Sit-up'], 'position' => 9, 'reps' => 100],
        ['exercise' => $exercises['Burpee'], 'position' => 10, 'reps' => 10],
        ['exercise' => $exercises['Push Up'], 'position' => 11, 'reps' => 25],
        ['exercise' => $exercises['Walking Lunge'], 'position' => 12, 'reps' => 50],
        ['exercise' => $exercises['Sit-up'], 'position' => 13, 'reps' => 100],
        ['exercise' => $exercises['Air Squat'], 'position' => 14, 'reps' => 150],
    ],
];


// ========== GIRL WODs ==========

// Fran
$wods[] = [
    'name' => 'Fran',
    'type' => 'girl',
    'description' => "21-15-9 reps por tiempo:\n\n"
        . "• Thrusters (95 lb / 65 lb)\n"
        . "• Pull-ups\n\n"
        . "El benchmark más icónico de CrossFit. Rápido, brutal y humillante.",
    'scaling' => "Reduce peso a 65/45 lb (intermedio) o 45/35 lb (principiante). "
        . "Pull-ups a jumping pull-ups o ring rows.",
    'elite_male' => '2:30',
    'elite_female' => '3:30',
    'advanced_male' => '4:00',
    'advanced_female' => '5:30',
    'intermediate_male' => '6:30',
    'intermediate_female' => '8:30',
    'beginner_male' => '10:00',
    'beginner_female' => '13:00',
    'exercises' => [
        ['exercise' => $exercises['Thruster'], 'position' => 1, 'reps' => 21, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 2, 'reps' => 21],
        ['exercise' => $exercises['Thruster'], 'position' => 3, 'reps' => 15, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 4, 'reps' => 15],
        ['exercise' => $exercises['Thruster'], 'position' => 5, 'reps' => 9, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 6, 'reps' => 9],
    ],
];

// Grace
$wods[] = [
    'name' => 'Grace',
    'type' => 'girl',
    'description' => "30 Clean and Jerks por tiempo:\n\n"
        . "• 135 lb (male) / 95 lb (female)\n\n"
        . "Benchmark clásico de potencia y resistencia con barra.",
    'scaling' => "Reduce peso a 95/65 lb (intermedio) o 75/55 lb (principiante). "
        . "Power clean + push jerk permitidos.",
    'elite_male' => '2:00',
    'elite_female' => '2:45',
    'advanced_male' => '3:30',
    'advanced_female' => '4:30',
    'intermediate_male' => '5:00',
    'intermediate_female' => '6:30',
    'beginner_male' => '8:00',
    'beginner_female' => '10:00',
    'exercises' => [
        ['exercise' => $exercises['Clean and Jerk'], 'position' => 1, 'reps' => 30, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
    ],
];

// Helen
$wods[] = [
    'name' => 'Helen',
    'type' => 'girl',
    'description' => "3 rondas por tiempo:\n\n"
        . "• 400 meter Run\n"
        . "• 21 Kettlebell Swings (53 lb / 35 lb)\n"
        . "• 12 Pull-ups\n\n"
        . "Benchmark de resistencia cardiovascular y fuerza de agarre.",
    'scaling' => "KB a 35/26 lb (intermedio) o 26/18 lb (principiante). "
        . "Pull-ups con banda o ring rows.",
    'elite_male' => '7:00',
    'elite_female' => '8:30',
    'advanced_male' => '9:30',
    'advanced_female' => '11:30',
    'intermediate_male' => '13:00',
    'intermediate_female' => '15:30',
    'beginner_male' => '17:00',
    'beginner_female' => '20:00',
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 400],
        ['exercise' => $exercises['Kettlebell Swing'], 'position' => 2, 'reps' => 21, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => 12],
    ],
];

// Diane
$wods[] = [
    'name' => 'Diane',
    'type' => 'girl',
    'description' => "21-15-9 reps por tiempo:\n\n"
        . "• Deadlifts (225 lb / 155 lb)\n"
        . "• Handstand Push-ups\n\n"
        . "Benchmark de fuerza de tren posterior y gimnasia invertida.",
    'scaling' => "Reduce peso a 155/105 lb (intermedio) o 95/65 lb (principiante). "
        . "HSPU a pike push-ups o push presses con mancuernas.",
    'elite_male' => '3:00',
    'elite_female' => '4:00',
    'advanced_male' => '5:00',
    'advanced_female' => '6:30',
    'intermediate_male' => '8:00',
    'intermediate_female' => '10:30',
    'beginner_male' => '13:00',
    'beginner_female' => '16:00',
    'exercises' => [
        ['exercise' => $exercises['Deadlift'], 'position' => 1, 'reps' => 21, 'rx_weight_male' => '102kg', 'rx_weight_female' => '70kg'],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 2, 'reps' => 21],
        ['exercise' => $exercises['Deadlift'], 'position' => 3, 'reps' => 15, 'rx_weight_male' => '102kg', 'rx_weight_female' => '70kg'],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 4, 'reps' => 15],
        ['exercise' => $exercises['Deadlift'], 'position' => 5, 'reps' => 9, 'rx_weight_male' => '102kg', 'rx_weight_female' => '70kg'],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 6, 'reps' => 9],
    ],
];

// Elizabeth
$wods[] = [
    'name' => 'Elizabeth',
    'type' => 'girl',
    'description' => "21-15-9 reps por tiempo:\n\n"
        . "• Squat Cleans (135 lb / 95 lb)\n"
        . "• Ring Dips\n\n"
        . "Benchmark de potencia con barra y resistencia de empuje en anillas.",
    'scaling' => "Reduce peso a 95/65 lb (intermedio) o 75/55 lb (principiante). "
        . "Ring dips con banda o bench dips.",
    'elite_male' => '3:30',
    'elite_female' => '5:00',
    'advanced_male' => '6:00',
    'advanced_female' => '8:00',
    'intermediate_male' => '10:00',
    'intermediate_female' => '13:00',
    'beginner_male' => '15:00',
    'beginner_female' => '19:00',
    'exercises' => [
        ['exercise' => $exercises['Squat Clean'], 'position' => 1, 'reps' => 21, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
        ['exercise' => $exercises['Ring Dip'], 'position' => 2, 'reps' => 21],
        ['exercise' => $exercises['Squat Clean'], 'position' => 3, 'reps' => 15, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
        ['exercise' => $exercises['Ring Dip'], 'position' => 4, 'reps' => 15],
        ['exercise' => $exercises['Squat Clean'], 'position' => 5, 'reps' => 9, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
        ['exercise' => $exercises['Ring Dip'], 'position' => 6, 'reps' => 9],
    ],
];

// Amanda
$wods[] = [
    'name' => 'Amanda',
    'type' => 'girl',
    'description' => "9-7-5 reps por tiempo:\n\n"
        . "• Muscle-ups\n"
        . "• Squat Snatches (135 lb / 95 lb)\n\n"
        . "Benchmark de habilidad gimnástica y potencia olímpica.",
    'scaling' => "Reduce peso a 95/65 lb (intermedio) o 65/45 lb (principiante). "
        . "Muscle-ups asistidos (banda) o sub a pull-ups + dips.",
    'elite_male' => '3:00',
    'elite_female' => '4:00',
    'advanced_male' => '5:00',
    'advanced_female' => '6:30',
    'intermediate_male' => '8:00',
    'intermediate_female' => '10:30',
    'beginner_male' => '14:00',
    'beginner_female' => '17:00',
    'exercises' => [
        ['exercise' => $exercises['Muscle Up'], 'position' => 1, 'reps' => 9],
        ['exercise' => $exercises['Snatch'], 'position' => 2, 'reps' => 9, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
        ['exercise' => $exercises['Muscle Up'], 'position' => 3, 'reps' => 7],
        ['exercise' => $exercises['Snatch'], 'position' => 4, 'reps' => 7, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
        ['exercise' => $exercises['Muscle Up'], 'position' => 5, 'reps' => 5],
        ['exercise' => $exercises['Snatch'], 'position' => 6, 'reps' => 5, 'rx_weight_male' => '61kg', 'rx_weight_female' => '43kg'],
    ],
];

// Mary
$wods[] = [
    'name' => 'Mary',
    'type' => 'girl',
    'description' => "AMRAP en 20 minutos:\n\n"
        . "• 5 Handstand Push-ups\n"
        . "• 10 Pistol Squats (alternating)\n"
        . "• 15 Pull-ups\n\n"
        . "Benchmark de habilidad gimnástica avanzada y resistencia.",
    'scaling' => "HSPU a pike push-ups / Pistols a pistols asistidos (caja) o air squats. "
        . "Pull-ups con banda o ring rows.",
    'elite_male' => '18 rounds',
    'elite_female' => '16 rounds',
    'advanced_male' => '14 rounds',
    'advanced_female' => '12 rounds',
    'intermediate_male' => '10 rounds',
    'intermediate_female' => '9 rounds',
    'beginner_male' => '6 rounds',
    'beginner_female' => '5 rounds',
    'exercises' => [
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 1, 'reps' => 5],
        ['exercise' => $exercises['Pistol Squat'], 'position' => 2, 'reps' => 10],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => 15],
    ],
];

// Cindy
$wods[] = [
    'name' => 'Cindy',
    'type' => 'girl',
    'description' => "AMRAP en 20 minutos:\n\n"
        . "• 5 Pull-ups\n"
        . "• 10 Push-ups\n"
        . "• 15 Air Squats\n\n"
        . "Benchmark de peso corporal y resistencia muscular.",
    'scaling' => "Pull-ups con banda / Push-ups desde rodillas / Air squats a box squats.",
    'elite_male' => '20 rounds',
    'elite_female' => '18 rounds',
    'advanced_male' => '15 rounds',
    'advanced_female' => '14 rounds',
    'intermediate_male' => '12 rounds',
    'intermediate_female' => '11 rounds',
    'beginner_male' => '8 rounds',
    'beginner_female' => '7 rounds',
    'exercises' => [
        ['exercise' => $exercises['Pull Up'], 'position' => 1, 'reps' => 5],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 10],
        ['exercise' => $exercises['Air Squat'], 'position' => 3, 'reps' => 15],
    ],
];

// Annie
$wods[] = [
    'name' => 'Annie',
    'type' => 'girl',
    'description' => "50-40-30-20-10 reps por tiempo:\n\n"
        . "• Double-unders\n"
        . "• Sit-ups\n\n"
        . "Benchmark de coordinación con cuerda y resistencia de core.",
    'scaling' => "Double-unders a single-unders (×2 o ×3 reps). "
        . "Sit-ups a crunches o knee tucks.",
    'elite_male' => '5:30',
    'elite_female' => '6:30',
    'advanced_male' => '7:30',
    'advanced_female' => '8:30',
    'intermediate_male' => '10:00',
    'intermediate_female' => '11:30',
    'beginner_male' => '14:00',
    'beginner_female' => '16:00',
    'exercises' => [
        ['exercise' => $exercises['Double Unders'], 'position' => 1, 'reps' => 50],
        ['exercise' => $exercises['Sit-up'], 'position' => 2, 'reps' => 50],
        ['exercise' => $exercises['Double Unders'], 'position' => 3, 'reps' => 40],
        ['exercise' => $exercises['Sit-up'], 'position' => 4, 'reps' => 40],
        ['exercise' => $exercises['Double Unders'], 'position' => 5, 'reps' => 30],
        ['exercise' => $exercises['Sit-up'], 'position' => 6, 'reps' => 30],
        ['exercise' => $exercises['Double Unders'], 'position' => 7, 'reps' => 20],
        ['exercise' => $exercises['Sit-up'], 'position' => 8, 'reps' => 20],
        ['exercise' => $exercises['Double Unders'], 'position' => 9, 'reps' => 10],
        ['exercise' => $exercises['Sit-up'], 'position' => 10, 'reps' => 10],
    ],
];

// ------------------------------------------------------------------
// 4. Ejecutar seed de los 23 WODs
// ------------------------------------------------------------------

echo "--- Procesando WODs ---\n";

foreach ($wods as $wod) {
    $helper->seedBenchmark($wod);
}

echo "\n--- WODs completados ---\n\n";

// ------------------------------------------------------------------
// 5. Corregir tiempos femeninos de benchmarks restantes (16)
// ------------------------------------------------------------------

echo "--- Corrigiendo tiempos femeninos duplicados ---\n";

$femaleFixes = [
    'Cindy' => [
        'elite_time_female' => '18 rounds',
        'advanced_time_female' => '14 rounds',
        'intermediate_time_female' => '11 rounds',
        'beginner_time_female' => '7 rounds',
    ],
    'Mary' => [
        'elite_time_female' => '16 rounds',
        'advanced_time_female' => '12 rounds',
        'intermediate_time_female' => '9 rounds',
        'beginner_time_female' => '5 rounds',
    ],
    'Nicole' => [
        'elite_time_female' => '14 rounds',
        'advanced_time_female' => '11 rounds',
        'intermediate_time_female' => '8 rounds',
        'beginner_time_female' => '5 rounds',
    ],
    'Angie' => [
        'elite_time_female' => '14:00',
        'advanced_time_female' => '20:00',
        'intermediate_time_female' => '29:00',
        'beginner_time_female' => '40:00',
    ],
    'Barbara' => [
        'elite_time_female' => '17:00',
        'advanced_time_female' => '25:00',
        'intermediate_time_female' => '34:00',
        'beginner_time_female' => '50:00',
    ],
    'Kelly' => [
        'elite_time_female' => '20:00',
        'advanced_time_female' => '28:00',
        'intermediate_time_female' => '39:00',
        'beginner_time_female' => '55:00',
    ],
    'Eva' => [
        'elite_time_female' => '28:00',
        'advanced_time_female' => '36:00',
        'intermediate_time_female' => '45:00',
        'beginner_time_female' => '62:00',
    ],
    'Jackie' => [
        'elite_time_female' => '7:00',
        'advanced_time_female' => '9:00',
        'intermediate_time_female' => '12:00',
        'beginner_time_female' => '17:00',
    ],
    'Karen' => [
        'elite_time_female' => '5:00',
        'advanced_time_female' => '7:00',
        'intermediate_time_female' => '11:00',
        'beginner_time_female' => '17:00',
    ],
    'Linda' => [
        'elite_time_female' => '14:00',
        'advanced_time_female' => '20:00',
        'intermediate_time_female' => '29:00',
        'beginner_time_female' => '46:00',
    ],
    'Nancy' => [
        'elite_time_female' => '14:00',
        'advanced_time_female' => '17:00',
        'intermediate_time_female' => '22:00',
        'beginner_time_female' => '33:00',
    ],
    'Chelsea' => [
        'elite_time_female' => '28 rounds',
        'advanced_time_female' => '23 rounds',
        'intermediate_time_female' => '18 rounds',
        'beginner_time_female' => '13 rounds',
    ],
    'Fight Gone Bad' => [
        'elite_time_female' => '360 pts',
        'advanced_time_female' => '315 pts',
        'intermediate_time_female' => '270 pts',
        'beginner_time_female' => '225 pts',
    ],
    'Filthy 50' => [
        'elite_time_female' => '21:00',
        'advanced_time_female' => '29:00',
        'intermediate_time_female' => '40:00',
        'beginner_time_female' => '58:00',
    ],
    'The Seven' => [
        'elite_time_female' => '21:00',
        'advanced_time_female' => '29:00',
        'intermediate_time_female' => '40:00',
        'beginner_time_female' => '58:00',
    ],
    'Lynne' => [
        'elite_time_female' => '13 rounds',
        'advanced_time_female' => '10 rounds',
        'intermediate_time_female' => '8 rounds',
        'beginner_time_female' => '5 rounds',
    ],
    'Tabata Something' => [
        'elite_time_female' => '90 reps',
        'advanced_time_female' => '72 reps',
        'intermediate_time_female' => '54 reps',
        'beginner_time_female' => '36 reps',
    ],
];

$helper->fixFemaleTimes($femaleFixes);

echo "\n=== Seeder completado con éxito ===\n";
