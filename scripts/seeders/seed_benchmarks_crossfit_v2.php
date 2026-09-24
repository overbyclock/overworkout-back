<?php

declare(strict_types=1);

require __DIR__ . '/BenchmarkSeederHelper.php';

use App\Enum\BenchmarkFormat;

// Catálogo de benchmarks CrossFit — docs/catalogo_benchmarks_crossfit.md (2026-09-30).
// Nota: el resumen del doc anuncia 32 WODs nuevos, pero solo hay 31 fichas especificadas
// (en interval_stations el doc cuenta 4 y lista 3). Se siembran las 31 documentadas.
//
// Convenciones:
// - Tiempos de referencia en formato 'MM:SS' (punto medio de los rangos del doc).
// - Puntuaciones no temporales (rondas, pts, reps) se guardan como texto descriptivo,
//   igual que los benchmarks existentes ('28 rounds', '360 pts').
// - Los WODs originales (⭐ en el doc) se guardan sin el símbolo y con type 'benchmark'.

$helper = new BenchmarkSeederHelper();
$em = $helper->getEm();

echo "=== OverWorkout — Seeder de benchmarks CrossFit v2 ===\n\n";

// ------------------------------------------------------------------
// 1. Resolver ejercicios del catálogo (resolución estricta por nombre)
// ------------------------------------------------------------------

$exerciseNames = [
    'Air Squat', 'Assault Bike', 'Bar Facing Burpee', 'Bar Muscle Up', 'Box Jump',
    'Box Jump Over', 'Burpee', 'Burpee Over Bar', 'Chest to Bar Pull Up', 'Deadlift',
    'Devil Press', 'Double Unders', 'Handstand Push Up', 'Hollow Body Rock', 'Jump Squat',
    'Kettlebell Goblet Squat', 'Kettlebell Swing', 'Kettlebell Swing American', 'Muscle Up',
    'Overhead Squat', 'Power Clean', 'Pull Up', 'Push Jerk', 'Push Press', 'Push Up',
    'Rowing Machine', 'Run', 'Sit-up', 'Strict Handstand Push Up', 'Sumo Deadlift High Pull',
    'Thruster', 'Toes to Bar', 'V-Up', 'Wall Ball',
];

$exercises = [];
$missing = [];

foreach ($exerciseNames as $name) {
    $ex = $em->getRepository(\App\Entity\Exercises::class)->findOneBy(['name' => $name]);
    if (null === $ex) {
        $missing[] = $name;
    } else {
        $exercises[$name] = $ex;
    }
}

if ([] !== $missing) {
    echo "  ❌ Ejercicios no encontrados en el catálogo:\n";
    foreach ($missing as $name) {
        echo "     - {$name}\n";
    }
    echo "\n  Se aborta el seeder para no crear fichas incompletas.\n";
    exit(1);
}

echo "  ✔️  ".count($exercises)." ejercicios resueltos contra el catálogo\n\n";

// ------------------------------------------------------------------
// 2. Helper para construir las filas de las escaleras (ladder)
// ------------------------------------------------------------------

/**
 * Genera las filas de un ladder intercalando los ejercicios por nivel,
 * siguiendo el criterio de los benchmarks existentes con esquema
 * desplegado (p. ej. JT: cada bloque de reps es una fila).
 *
 * @param array<int, array{exercise: \App\Entity\Exercises, rx_male?: string, rx_female?: string}> $defs
 * @param array<int, int>                                                                          $levels
 *
 * @return array<int, array<string, mixed>>
 */
$ladderRows = static function (array $defs, array $levels): array {
    $rows = [];
    $position = 1;

    foreach ($levels as $level) {
        foreach ($defs as $def) {
            $row = [
                'exercise' => $def['exercise'],
                'position' => $position++,
                'reps' => $level,
            ];
            if (isset($def['rx_male'])) {
                $row['rx_weight_male'] = $def['rx_male'];
                $row['rx_weight_female'] = $def['rx_female'];
            }
            $rows[] = $row;
        }
    }

    return $rows;
};

$wods = [];

// ========== LADDER (5) ==========

// 1.1 Escalera Ascendente 1-10
$wods[] = [
    'name' => 'Escalera Ascendente 1-10',
    'type' => 'benchmark',
    'description' => "Por tiempo · 10 niveles (1-2-3-...-10 de cada ejercicio):\n\n"
        . "• 1 Thruster (43/29 kg) + 1 Pull-up\n"
        . "• 2 Thrusters + 2 Pull-ups\n"
        . "• ... hasta 10 + 10\n\n"
        . "Couplet clásico ascendente. Cada nivel se completa antes de subir. "
        . "WOD original OverWorkout.",
    'scaling' => "Thruster 30/20 kg o Dumbbell Thruster 2×15/10; "
        . "Australian Pull Up o banda.",
    'elite_male' => '7:00',
    'elite_female' => '8:00',
    'advanced_male' => '10:00',
    'advanced_female' => '10:00',
    'intermediate_male' => '13:30',
    'intermediate_female' => '13:30',
    'beginner_male' => '17:00',
    'beginner_female' => '17:00',
    'format' => BenchmarkFormat::LADDER,
    'rounds' => 10,
    'time_cap' => 1080,
    'timer' => null,
    'movements' => ['thruster', 'pull-up'],
    'exercises' => $ladderRows([
        ['exercise' => $exercises['Thruster'], 'rx_male' => '43kg', 'rx_female' => '29kg'],
        ['exercise' => $exercises['Pull Up']],
    ], range(1, 10)),
];

// 1.2 Escalera Descendente 10-1
$wods[] = [
    'name' => 'Escalera Descendente 10-1',
    'type' => 'benchmark',
    'description' => "Por tiempo · 10 niveles (10-9-8-...-1 de cada ejercicio):\n\n"
        . "• 10 Wall Balls (9/6 kg) + 10 Box Jumps\n"
        . "• 9 + 9\n"
        . "• ... hasta 1 + 1\n\n"
        . "Descendente de wall balls y box jumps. Ritmo sostenido; el último nivel debe ser un sprint. "
        . "WOD original OverWorkout.",
    'scaling' => "Wall ball 6/4 kg; Box Step Over en vez de salto.",
    'elite_male' => '5:30',
    'elite_female' => '6:30',
    'advanced_male' => '8:00',
    'advanced_female' => '8:00',
    'intermediate_male' => '11:00',
    'intermediate_female' => '11:00',
    'beginner_male' => '14:00',
    'beginner_female' => '14:00',
    'format' => BenchmarkFormat::LADDER,
    'rounds' => 10,
    'time_cap' => 900,
    'timer' => null,
    'movements' => ['wall-ball', 'box-jump'],
    'exercises' => $ladderRows([
        ['exercise' => $exercises['Wall Ball'], 'rx_male' => '9kg', 'rx_female' => '6kg'],
        ['exercise' => $exercises['Box Jump']],
    ], range(10, 1, -1)),
];

// 1.3 Pirámide Deadlift — Burpee
$wods[] = [
    'name' => 'Pirámide Deadlift — Burpee',
    'type' => 'benchmark',
    'description' => "Por tiempo · 9 niveles (2-4-6-8-10-8-6-4-2 de cada ejercicio):\n\n"
        . "• Nivel 1: 2 Deadlifts (100/70 kg) + 2 Burpees Over Bar\n"
        . "• Nivel 2: 4 + 4\n"
        . "• ... hasta el pico de 10 + 10, y se desciende en espejo\n\n"
        . "Pirámide de peso muerto con burpee over bar. El pico (10-10) es el punto de inflexión; "
        . "gestiona el agarre. WOD original OverWorkout.",
    'scaling' => "Deadlift 70/50 kg; burpee sin salto sobre barra (lateral).",
    'elite_male' => '9:00',
    'elite_female' => '10:00',
    'advanced_male' => '13:00',
    'advanced_female' => '13:00',
    'intermediate_male' => '16:00',
    'intermediate_female' => '16:00',
    'beginner_male' => '19:00',
    'beginner_female' => '19:00',
    'format' => BenchmarkFormat::LADDER,
    'rounds' => 9,
    'time_cap' => 1200,
    'timer' => null,
    'movements' => ['deadlift', 'burpee'],
    'exercises' => $ladderRows([
        ['exercise' => $exercises['Deadlift'], 'rx_male' => '100kg', 'rx_female' => '70kg'],
        ['exercise' => $exercises['Burpee Over Bar']],
    ], [2, 4, 6, 8, 10, 8, 6, 4, 2]),
];

// 1.4 Escalera Kettlebell 1-10
$wods[] = [
    'name' => 'Escalera Kettlebell 1-10',
    'type' => 'benchmark',
    'description' => "Por tiempo · 10 niveles (1-2-3-...-10 de cada ejercicio):\n\n"
        . "• 1 Kettlebell Swing American (24/16 kg) + 1 Kettlebell Goblet Squat\n"
        . "• 2 + 2\n"
        . "• ... hasta 10 + 10\n\n"
        . "Doblete de kettlebell: swing americano + goblet squat. Piernas y sistema metabólico. "
        . "WOD original OverWorkout.",
    'scaling' => "KB 16/12 kg; Kettlebell Swing Russian (menor alcance).",
    'elite_male' => '6:00',
    'elite_female' => '7:00',
    'advanced_male' => '9:00',
    'advanced_female' => '9:00',
    'intermediate_male' => '12:00',
    'intermediate_female' => '12:00',
    'beginner_male' => '14:30',
    'beginner_female' => '14:30',
    'format' => BenchmarkFormat::LADDER,
    'rounds' => 10,
    'time_cap' => 900,
    'timer' => null,
    'movements' => ['kettlebell-swing', 'squat'],
    'exercises' => $ladderRows([
        ['exercise' => $exercises['Kettlebell Swing American'], 'rx_male' => '24kg', 'rx_female' => '16kg'],
        ['exercise' => $exercises['Kettlebell Goblet Squat'], 'rx_male' => '24kg', 'rx_female' => '16kg'],
    ], range(1, 10)),
];

// 1.5 Escalera Gimnástica 2-12
$wods[] = [
    'name' => 'Escalera Gimnástica 2-12',
    'type' => 'benchmark',
    'description' => "Por tiempo · 6 niveles (2-4-6-8-10-12 de cada ejercicio):\n\n"
        . "• 2 Toes to Bar + 2 Chest to Bar\n"
        . "• 4 + 4\n"
        . "• ... hasta 12 + 12\n\n"
        . "Escalera de habilidad gimnástica: toes to bar + chest to bar. Exige core y agarre; "
        . "rompe en series cortas planificadas. WOD original OverWorkout.",
    'scaling' => "Toes to Bar Kipping o Knees to Chest; Pull Up normal o Australian Pull Up.",
    'elite_male' => '9:00',
    'elite_female' => '10:00',
    'advanced_male' => '13:00',
    'advanced_female' => '13:00',
    'intermediate_male' => '16:00',
    'intermediate_female' => '16:00',
    'beginner_male' => '19:00',
    'beginner_female' => '19:00',
    'format' => BenchmarkFormat::LADDER,
    'rounds' => 6,
    'time_cap' => 1200,
    'timer' => null,
    'movements' => ['toes-to-bar', 'pull-up'],
    'exercises' => $ladderRows([
        ['exercise' => $exercises['Toes to Bar']],
        ['exercise' => $exercises['Chest to Bar Pull Up']],
    ], [2, 4, 6, 8, 10, 12]),
];

// ========== EMOM (5) ==========

// 2.1 Death by Burpee
$wods[] = [
    'name' => 'Death by Burpee',
    'type' => 'benchmark',
    'description' => "EMOM abierto:\n\n"
        . "• Minuto 1: 1 burpee\n"
        . "• Minuto 2: 2 burpees\n"
        . "• +1 burpee cada minuto hasta no poder completar la serie\n\n"
        . "El clásico \"death by\". La puntuación es el último minuto completado con éxito "
        . "(p. ej. \"14+12\"). WOD original OverWorkout.",
    'scaling' => "Empezar en 3-4 burpees el minuto 1, o burpee sin flexión "
        . "(Jump Squat + plancha).",
    'elite_male' => '18+ min',
    'elite_female' => '18+ min',
    'advanced_male' => '14-17 min',
    'advanced_female' => '14-17 min',
    'intermediate_male' => '10-13 min',
    'intermediate_female' => '10-13 min',
    'beginner_male' => '6-9 min',
    'beginner_female' => '6-9 min',
    'format' => BenchmarkFormat::EMOM,
    'rounds' => null,
    'time_cap' => 1200,
    'timer' => ['type' => 'emom', 'intervalSeconds' => 60],
    'movements' => ['burpee'],
    'exercises' => [
        ['exercise' => $exercises['Burpee'], 'position' => 1, 'reps' => null],
    ],
];

// 2.2 EMOM 12 Wall Ball / Toes to Bar
$wods[] = [
    'name' => 'EMOM 12 Wall Ball / Toes to Bar',
    'type' => 'benchmark',
    'description' => "EMOM 12 minutos:\n\n"
        . "• Minutos impares: 15 Wall Balls (9/6 kg)\n"
        . "• Minutos pares: 12 Toes to Bar\n\n"
        . "Referencia: élite sin romper series; avanzado con ≤2 pausas. WOD original OverWorkout.",
    'scaling' => "Wall ball 6/4 kg; TTB kipping o V-Up.",
    'elite_male' => null,
    'elite_female' => null,
    'advanced_male' => null,
    'advanced_female' => null,
    'intermediate_male' => null,
    'intermediate_female' => null,
    'beginner_male' => null,
    'beginner_female' => null,
    'format' => BenchmarkFormat::EMOM,
    'rounds' => 12,
    'time_cap' => 720,
    'timer' => ['type' => 'emom', 'intervalSeconds' => 60],
    'movements' => ['wall-ball', 'toes-to-bar'],
    'exercises' => [
        ['exercise' => $exercises['Wall Ball'], 'position' => 1, 'reps' => 15, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Toes to Bar'], 'position' => 2, 'reps' => 12],
    ],
];

// 2.3 EMOM 10 Barra
$wods[] = [
    'name' => 'EMOM 10 Barra',
    'type' => 'benchmark',
    'description' => "EMOM 10 minutos:\n\n"
        . "• Minutos impares: 3 Power Cleans (70/47,5 kg)\n"
        . "• Minutos pares: 3 Push Jerks (70/47,5 kg)\n\n"
        . "Referencia por carga: élite 90/60 kg o unbroken; intermedio 60/40 kg; "
        . "principiante 50/30 kg. WOD original OverWorkout.",
    'scaling' => "Reducir carga antes que reps; Hang Power Clean si la técnica del suelo falla.",
    'elite_male' => null,
    'elite_female' => null,
    'advanced_male' => null,
    'advanced_female' => null,
    'intermediate_male' => null,
    'intermediate_female' => null,
    'beginner_male' => null,
    'beginner_female' => null,
    'format' => BenchmarkFormat::EMOM,
    'rounds' => 10,
    'time_cap' => 600,
    'timer' => ['type' => 'emom', 'intervalSeconds' => 60],
    'movements' => ['power-clean', 'push-jerk'],
    'exercises' => [
        ['exercise' => $exercises['Power Clean'], 'position' => 1, 'reps' => 3, 'rx_weight_male' => '70kg', 'rx_weight_female' => '47.5kg'],
        ['exercise' => $exercises['Push Jerk'], 'position' => 2, 'reps' => 3, 'rx_weight_male' => '70kg', 'rx_weight_female' => '47.5kg'],
    ],
];

// 2.4 EMOM 14 Gimnástico
$wods[] = [
    'name' => 'EMOM 14 Gimnástico',
    'type' => 'benchmark',
    'description' => "EMOM 14 minutos:\n\n"
        . "• Minutos impares: 10 Chest to Bar Pull Ups\n"
        . "• Minutos pares: 8 Handstand Push-ups estrictos\n\n"
        . "Referencia: élite sin romper series; avanzado con HSPU kipping. "
        . "WOD original OverWorkout.",
    'scaling' => "C2B → Pull Up; estricto → Kipping Handstand Push Up o pike; reducir a 6/6.",
    'elite_male' => null,
    'elite_female' => null,
    'advanced_male' => null,
    'advanced_female' => null,
    'intermediate_male' => null,
    'intermediate_female' => null,
    'beginner_male' => null,
    'beginner_female' => null,
    'format' => BenchmarkFormat::EMOM,
    'rounds' => 14,
    'time_cap' => 840,
    'timer' => ['type' => 'emom', 'intervalSeconds' => 60],
    'movements' => ['pull-up', 'handstand-push-up'],
    'exercises' => [
        ['exercise' => $exercises['Chest to Bar Pull Up'], 'position' => 1, 'reps' => 10],
        ['exercise' => $exercises['Strict Handstand Push Up'], 'position' => 2, 'reps' => 8],
    ],
];

// 2.5 EMOM 16 Motor
$wods[] = [
    'name' => 'EMOM 16 Motor',
    'type' => 'benchmark',
    'description' => "EMOM 16 minutos:\n\n"
        . "• Minutos impares: 12 cal Assault Bike\n"
        . "• Minutos pares: 10 Kettlebell Swings (24/16 kg)\n\n"
        . "Referencia: élite 15 cal / unbroken; intermedio 10 cal; principiante bike 8 cal + "
        . "Kettlebell Swing Russian 16/12. WOD original OverWorkout.",
    'scaling' => "Ajustar calorías antes que swings; Ski Erg como alternativa al bike.",
    'elite_male' => null,
    'elite_female' => null,
    'advanced_male' => null,
    'advanced_female' => null,
    'intermediate_male' => null,
    'intermediate_female' => null,
    'beginner_male' => null,
    'beginner_female' => null,
    'format' => BenchmarkFormat::EMOM,
    'rounds' => 16,
    'time_cap' => 960,
    'timer' => ['type' => 'emom', 'intervalSeconds' => 60],
    'movements' => ['assault-bike', 'kettlebell-swing'],
    'exercises' => [
        ['exercise' => $exercises['Assault Bike'], 'position' => 1, 'calories' => 12],
        ['exercise' => $exercises['Kettlebell Swing'], 'position' => 2, 'reps' => 10, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
    ],
];

// ========== TABATA (3) ==========

// 3.1 Tabata This! (oficial)
$wods[] = [
    'name' => 'Tabata This!',
    'type' => 'benchmark',
    'description' => "8 rondas Tabata (20s trabajo / 10s descanso) de cada movimiento, "
        . "con 1 min de descanso entre movimientos:\n\n"
        . "• Air Squat\n"
        . "• Rowing Machine (cal)\n"
        . "• Pull Up\n"
        . "• Push Up\n"
        . "• Sit-up\n\n"
        . "Benchmark oficial de CrossFit. Registra el mínimo de reps de cada bloque; "
        . "la puntuación final es la suma de los cinco mínimos.",
    'scaling' => "Australian Pull Up, flexiones de rodillas, GHD Sit Up solo si está habituado.",
    'elite_male' => '90+ pts',
    'elite_female' => '90+ pts',
    'advanced_male' => '70-89 pts',
    'advanced_female' => '70-89 pts',
    'intermediate_male' => '50-69 pts',
    'intermediate_female' => '50-69 pts',
    'beginner_male' => '<50 pts',
    'beginner_female' => '<50 pts',
    'format' => BenchmarkFormat::TABATA,
    'rounds' => 8,
    'time_cap' => 1440,
    'timer' => ['type' => 'tabata', 'workSeconds' => 20, 'restSeconds' => 10, 'rounds' => 8],
    'movements' => ['squat', 'row', 'pull-up', 'push-up', 'core'],
    'exercises' => [
        ['exercise' => $exercises['Air Squat'], 'position' => 1, 'reps' => null],
        ['exercise' => $exercises['Rowing Machine'], 'position' => 2, 'calories' => null],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => null],
        ['exercise' => $exercises['Push Up'], 'position' => 4, 'reps' => null],
        ['exercise' => $exercises['Sit-up'], 'position' => 5, 'reps' => null],
    ],
];

// 3.2 Tabata Core
$wods[] = [
    'name' => 'Tabata Core',
    'type' => 'benchmark',
    'description' => "8 rondas Tabata (20s/10s) de Hollow Body Rock, descanso 1 min, "
        . "8 rondas Tabata de V-Up.\n\n"
        . "Trabajo de core anti-extensión + flexión. Puntuación: suma de mínimos de ambos bloques. "
        . "WOD original OverWorkout.",
    'scaling' => "Hollow Body Hold (estático) en vez de rock; Sit-up en vez de V-Up.",
    'elite_male' => '20+ / 25+ reps',
    'elite_female' => '20+ / 25+ reps',
    'advanced_male' => '15-19 / 20-24 reps',
    'advanced_female' => '15-19 / 20-24 reps',
    'intermediate_male' => '10-14 / 14-19 reps',
    'intermediate_female' => '10-14 / 14-19 reps',
    'beginner_male' => '6-9 / 8-13 reps',
    'beginner_female' => '6-9 / 8-13 reps',
    'format' => BenchmarkFormat::TABATA,
    'rounds' => 8,
    'time_cap' => 600,
    'timer' => ['type' => 'tabata', 'workSeconds' => 20, 'restSeconds' => 10, 'rounds' => 8],
    'movements' => ['core'],
    'exercises' => [
        ['exercise' => $exercises['Hollow Body Rock'], 'position' => 1, 'reps' => null],
        ['exercise' => $exercises['V-Up'], 'position' => 2, 'reps' => null],
    ],
];

// 3.3 Tabata Pistón
$wods[] = [
    'name' => 'Tabata Pistón',
    'type' => 'benchmark',
    'description' => "8 rondas Tabata (20s/10s) alternando cada intervalo:\n\n"
        . "• Double Unders (20s)\n"
        . "• Air Squat (20s)\n\n"
        . "16 intervalos en total (8 de cada movimiento). Puntuación: total de reps "
        . "(dobles cuentan como 1). WOD original OverWorkout.",
    'scaling' => "Jump Rope simples (cuenta doble) o saltos sin cuerda; air squat a "
        . "Box Step Over bajo si hay molestias de rodilla.",
    'elite_male' => '300+ reps',
    'elite_female' => '300+ reps',
    'advanced_male' => '220-299 reps',
    'advanced_female' => '220-299 reps',
    'intermediate_male' => '140-219 reps',
    'intermediate_female' => '140-219 reps',
    'beginner_male' => '<140 reps',
    'beginner_female' => '<140 reps',
    'format' => BenchmarkFormat::TABATA,
    'rounds' => 8,
    'time_cap' => 480,
    'timer' => ['type' => 'tabata', 'workSeconds' => 20, 'restSeconds' => 10, 'rounds' => 8],
    'movements' => ['jump-rope', 'squat'],
    'exercises' => [
        ['exercise' => $exercises['Double Unders'], 'position' => 1, 'reps' => null],
        ['exercise' => $exercises['Air Squat'], 'position' => 2, 'reps' => null],
    ],
];

// ========== CHIPPER (4) ==========

// 4.1 Filthy 30
$wods[] = [
    'name' => 'Filthy 30',
    'type' => 'benchmark',
    'description' => "1 vuelta por tiempo:\n\n"
        . "• 30 Box Jumps\n"
        . "• 30 Jump Squats\n"
        . "• 30 Kettlebell Swing American (24/16 kg)\n"
        . "• 30 Push-ups\n"
        . "• 30 Sit-ups\n"
        . "• 30 cal Assault Bike\n\n"
        . "Homenaje al Filthy 50 ya existente en el catálogo. Volumen moderado, ritmo constante; "
        . "el bike final es la prueba. WOD original OverWorkout.",
    'scaling' => "Box step; Kettlebell Swing Russian 16/12; flexiones de rodillas.",
    'elite_male' => '13:00',
    'elite_female' => '14:00',
    'advanced_male' => '16:30',
    'advanced_female' => '16:30',
    'intermediate_male' => '20:30',
    'intermediate_female' => '20:30',
    'beginner_male' => '24:00',
    'beginner_female' => '24:00',
    'format' => BenchmarkFormat::CHIPPER,
    'rounds' => 1,
    'time_cap' => 1500,
    'timer' => null,
    'movements' => ['box-jump', 'squat', 'kettlebell-swing', 'push-up', 'core', 'assault-bike'],
    'exercises' => [
        ['exercise' => $exercises['Box Jump'], 'position' => 1, 'reps' => 30],
        ['exercise' => $exercises['Jump Squat'], 'position' => 2, 'reps' => 30],
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 3, 'reps' => 30, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Push Up'], 'position' => 4, 'reps' => 30],
        ['exercise' => $exercises['Sit-up'], 'position' => 5, 'reps' => 30],
        ['exercise' => $exercises['Assault Bike'], 'position' => 6, 'calories' => 30],
    ],
];

// 4.2 Big Wave
$wods[] = [
    'name' => 'Big Wave',
    'type' => 'benchmark',
    'description' => "1 vuelta por tiempo:\n\n"
        . "• 50 Wall Balls (9/6 kg)\n"
        . "• 40 Box Jump Overs\n"
        . "• 30 Toes to Bar\n"
        . "• 20 Power Cleans (60/40 kg)\n"
        . "• 10 Bar Muscle-ups\n"
        . "• 400m Run\n\n"
        . "De lo metabólico a la barra y la gimnasia; termina corriendo. "
        . "Ritmo conservador en los primeros 100 reps. WOD original OverWorkout.",
    'scaling' => "WB 6/4; Pull Up + Box Jump Over bajo en vez de BMU; power clean 50/35 kg.",
    'elite_male' => '15:00',
    'elite_female' => '17:00',
    'advanced_male' => '20:30',
    'advanced_female' => '20:30',
    'intermediate_male' => '24:00',
    'intermediate_female' => '24:00',
    'beginner_male' => '25:00',
    'beginner_female' => '25:00',
    'format' => BenchmarkFormat::CHIPPER,
    'rounds' => 1,
    'time_cap' => 1500,
    'timer' => null,
    'movements' => ['wall-ball', 'box-jump', 'toes-to-bar', 'power-clean', 'muscle-up', 'run'],
    'exercises' => [
        ['exercise' => $exercises['Wall Ball'], 'position' => 1, 'reps' => 50, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Box Jump Over'], 'position' => 2, 'reps' => 40],
        ['exercise' => $exercises['Toes to Bar'], 'position' => 3, 'reps' => 30],
        ['exercise' => $exercises['Power Clean'], 'position' => 4, 'reps' => 20, 'rx_weight_male' => '60kg', 'rx_weight_female' => '40kg'],
        ['exercise' => $exercises['Bar Muscle Up'], 'position' => 5, 'reps' => 10],
        ['exercise' => $exercises['Run'], 'position' => 6, 'distance' => 400],
    ],
];

// 4.3 El Descenso
$wods[] = [
    'name' => 'El Descenso',
    'type' => 'benchmark',
    'description' => "1 vuelta por tiempo:\n\n"
        . "• 50 Kettlebell Swing American (24/16 kg)\n"
        . "• 40 Sit-ups\n"
        . "• 30 Box Jumps\n"
        . "• 20 Power Cleans (60/40 kg)\n"
        . "• 10 Strict Handstand Push-ups\n\n"
        . "Pirámide de volumen invertido: empieza fuerte en swings y termina con habilidad de "
        . "hombro bajo fatiga. WOD original OverWorkout.",
    'scaling' => "Kipping Handstand Push Up o Pike Handstand Push Up; cleans 50/35 kg; "
        . "Box Step Over.",
    'elite_male' => '13:00',
    'elite_female' => '15:00',
    'advanced_male' => '18:00',
    'advanced_female' => '18:00',
    'intermediate_male' => '21:00',
    'intermediate_female' => '21:00',
    'beginner_male' => '22:00',
    'beginner_female' => '22:00',
    'format' => BenchmarkFormat::CHIPPER,
    'rounds' => 1,
    'time_cap' => 1320,
    'timer' => null,
    'movements' => ['kettlebell-swing', 'core', 'box-jump', 'power-clean', 'handstand-push-up'],
    'exercises' => [
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 1, 'reps' => 50, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Sit-up'], 'position' => 2, 'reps' => 40],
        ['exercise' => $exercises['Box Jump'], 'position' => 3, 'reps' => 30],
        ['exercise' => $exercises['Power Clean'], 'position' => 4, 'reps' => 20, 'rx_weight_male' => '60kg', 'rx_weight_female' => '40kg'],
        ['exercise' => $exercises['Strict Handstand Push Up'], 'position' => 5, 'reps' => 10],
    ],
];

// 4.4 Tritón
$wods[] = [
    'name' => 'Tritón',
    'type' => 'benchmark',
    'description' => "1 vuelta por tiempo:\n\n"
        . "• 60 cal Rowing Machine\n"
        . "• 50 Wall Balls (9/6 kg)\n"
        . "• 40 Kettlebell Swing American (24/16 kg)\n"
        . "• 30 Box Jump Overs\n"
        . "• 20 Push Press (43/29 kg)\n"
        . "• 10 Bar Muscle-ups\n\n"
        . "Chipper largo de tres bloques: remo/metabólico, kettlebell/pierna y barra/gimnasia. "
        . "WOD original OverWorkout.",
    'scaling' => "Pull Up en vez de BMU; wall ball 6/4; remo por calorías a ritmo sostenible "
        . "(~70% esfuerzo).",
    'elite_male' => '19:30',
    'elite_female' => '21:30',
    'advanced_male' => '26:00',
    'advanced_female' => '26:00',
    'intermediate_male' => '31:00',
    'intermediate_female' => '31:00',
    'beginner_male' => '37:00',
    'beginner_female' => '37:00',
    'format' => BenchmarkFormat::CHIPPER,
    'rounds' => 1,
    'time_cap' => 1800,
    'timer' => null,
    'movements' => ['row', 'wall-ball', 'kettlebell-swing', 'box-jump', 'push-press', 'muscle-up'],
    'exercises' => [
        ['exercise' => $exercises['Rowing Machine'], 'position' => 1, 'calories' => 60],
        ['exercise' => $exercises['Wall Ball'], 'position' => 2, 'reps' => 50, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 3, 'reps' => 40, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Box Jump Over'], 'position' => 4, 'reps' => 30],
        ['exercise' => $exercises['Push Press'], 'position' => 5, 'reps' => 20, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Bar Muscle Up'], 'position' => 6, 'reps' => 10],
    ],
];

// ========== INTERVAL STATIONS (3) ==========

// 5.1 Tríplice
$wods[] = [
    'name' => 'Tríplice',
    'type' => 'benchmark',
    'description' => "3 rondas × 1 min por estación (máx. reps):\n\n"
        . "• Push Press (43/29 kg)\n"
        . "• Sumo Deadlift High Pull (43/29 kg)\n"
        . "• Box Jump\n"
        . "• Push Up\n"
        . "• Air Squat\n\n"
        . "Descanso 1 min entre rondas. Variante del Fight Gone Bad (ya en catálogo) con barra "
        . "más ligera y box jump. Mide potencia de salida sostenida. Puntuación: total de reps. "
        . "WOD original OverWorkout.",
    'scaling' => "Barra 30/20 kg; Box Step Over; flexiones de rodillas.",
    'elite_male' => '400+ pts',
    'elite_female' => '400+ pts',
    'advanced_male' => '320-399 pts',
    'advanced_female' => '320-399 pts',
    'intermediate_male' => '240-319 pts',
    'intermediate_female' => '240-319 pts',
    'beginner_male' => '<240 pts',
    'beginner_female' => '<240 pts',
    'format' => BenchmarkFormat::INTERVAL_STATIONS,
    'rounds' => 3,
    'time_cap' => 1200,
    'rest_between_rounds' => 60,
    'timer' => ['type' => 'interval', 'workSeconds' => 60, 'restBetweenStationsSeconds' => 0, 'restBetweenRoundsSeconds' => 60],
    'movements' => ['push-press', 'deadlift', 'box-jump', 'push-up', 'squat'],
    'exercises' => [
        ['exercise' => $exercises['Push Press'], 'position' => 1, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Sumo Deadlift High Pull'], 'position' => 2, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Box Jump'], 'position' => 3],
        ['exercise' => $exercises['Push Up'], 'position' => 4],
        ['exercise' => $exercises['Air Squat'], 'position' => 5],
    ],
];

// 5.2 Estaciones de Remo
$wods[] = [
    'name' => 'Estaciones de Remo',
    'type' => 'benchmark',
    'description' => "3 rondas × 1 min por estación (máx. trabajo):\n\n"
        . "• Rowing Machine (cal)\n"
        . "• Wall Ball (9/6 kg)\n"
        . "• Box Jump Over\n\n"
        . "Descanso 1 min entre rondas. Tres estaciones metabólicas. La transición cuenta: "
        . "no hay descanso entre estaciones dentro de la ronda. Puntuación: calorías + reps. "
        . "WOD original OverWorkout.",
    'scaling' => "Ski Erg o Assault Bike en vez de remo; wall ball 6/4 kg.",
    'elite_male' => '55+ cal / 90+ reps',
    'elite_female' => '55+ cal / 90+ reps',
    'advanced_male' => '45-54 / 75-89',
    'advanced_female' => '45-54 / 75-89',
    'intermediate_male' => '35-44 / 55-74',
    'intermediate_female' => '35-44 / 55-74',
    'beginner_male' => '<35 / <55',
    'beginner_female' => '<35 / <55',
    'format' => BenchmarkFormat::INTERVAL_STATIONS,
    'rounds' => 3,
    'time_cap' => 840,
    'rest_between_rounds' => 60,
    'timer' => ['type' => 'interval', 'workSeconds' => 60, 'restBetweenRoundsSeconds' => 60],
    'movements' => ['row', 'wall-ball', 'box-jump'],
    'exercises' => [
        ['exercise' => $exercises['Rowing Machine'], 'position' => 1],
        ['exercise' => $exercises['Wall Ball'], 'position' => 2, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Box Jump Over'], 'position' => 3],
    ],
];

// 5.3 Aguja de Dobles
$wods[] = [
    'name' => 'Aguja de Dobles',
    'type' => 'benchmark',
    'description' => "4 rondas × 45s por estación (máx. reps):\n\n"
        . "• Devil Press (2×15/10 kg)\n"
        . "• Toes to Bar\n"
        . "• Double Unders\n\n"
        . "Descanso 45s entre rondas. Intervalos cortos de alta densidad. "
        . "Puntuación: total de reps (dobles = 1). WOD original OverWorkout.",
    'scaling' => "Dumbbell Snatch alterno ligero (2×10/6) en vez de devil press; V-Up; "
        . "saltos simples.",
    'elite_male' => '180+ reps',
    'elite_female' => '180+ reps',
    'advanced_male' => '140-179 reps',
    'advanced_female' => '140-179 reps',
    'intermediate_male' => '100-139 reps',
    'intermediate_female' => '100-139 reps',
    'beginner_male' => '<100 reps',
    'beginner_female' => '<100 reps',
    'format' => BenchmarkFormat::INTERVAL_STATIONS,
    'rounds' => 4,
    'time_cap' => 960,
    'rest_between_rounds' => 45,
    'timer' => ['type' => 'interval', 'workSeconds' => 45, 'restBetweenStationsSeconds' => 0, 'restBetweenRoundsSeconds' => 45],
    'movements' => ['devil-press', 'toes-to-bar', 'jump-rope'],
    'exercises' => [
        ['exercise' => $exercises['Devil Press'], 'position' => 1, 'rx_weight_male' => '2x15kg', 'rx_weight_female' => '2x10kg'],
        ['exercise' => $exercises['Toes to Bar'], 'position' => 2],
        ['exercise' => $exercises['Double Unders'], 'position' => 3],
    ],
];

// ========== AMRAP (5) ==========

// 6.1 Nate (oficial, hero)
$wods[] = [
    'name' => 'Nate',
    'type' => 'hero',
    'description' => "AMRAP 20 minutos:\n\n"
        . "• 2 Muscle-ups\n"
        . "• 4 Handstand Push-ups\n"
        . "• 8 Kettlebell Swing American (32/24 kg)\n\n"
        . "Hero WOD oficial en honor al CPO Nathan Hardy (US Navy SEAL), fallecido en Irak "
        . "el 4 de febrero de 2008. Gimnasia de alta habilidad + potencia; el muscle-up es "
        . "el cuello de botella.",
    'scaling' => "Bar Muscle Up o Chest to Bar Pull Up; Kipping Handstand Push Up o "
        . "Pike Handstand Push Up; KB 24/16.",
    'elite_male' => '25+ rounds',
    'elite_female' => '25+ rounds',
    'advanced_male' => '17-24 rounds',
    'advanced_female' => '17-24 rounds',
    'intermediate_male' => '11-16 rounds',
    'intermediate_female' => '11-16 rounds',
    'beginner_male' => '5-10 rounds',
    'beginner_female' => '5-10 rounds',
    'format' => BenchmarkFormat::AMRAP,
    'rounds' => null,
    'time_cap' => 1200,
    'timer' => ['type' => 'amrap', 'durationSeconds' => 1200],
    'movements' => ['muscle-up', 'handstand-push-up', 'kettlebell-swing'],
    'exercises' => [
        ['exercise' => $exercises['Muscle Up'], 'position' => 1, 'reps' => 2],
        ['exercise' => $exercises['Handstand Push Up'], 'position' => 2, 'reps' => 4],
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 3, 'reps' => 8, 'rx_weight_male' => '32kg', 'rx_weight_female' => '24kg'],
    ],
];

// 6.2 Motor City
$wods[] = [
    'name' => 'Motor City',
    'type' => 'benchmark',
    'description' => "AMRAP 12 minutos:\n\n"
        . "• 12 cal Assault Bike\n"
        . "• 12 Wall Balls (9/6 kg)\n"
        . "• 12 Toes to Bar\n\n"
        . "Triplete metabólico/gimnástico. Ritmo objetivo: completar cada vuelta en ~2 min. "
        . "WOD original OverWorkout.",
    'scaling' => "10 cal; WB 6/4; V-Up o Toes to Bar Kipping.",
    'elite_male' => '7+ rounds',
    'elite_female' => '7+ rounds',
    'advanced_male' => '5.5-6.5 rounds',
    'advanced_female' => '5.5-6.5 rounds',
    'intermediate_male' => '4-5 rounds',
    'intermediate_female' => '4-5 rounds',
    'beginner_male' => '3 rounds',
    'beginner_female' => '3 rounds',
    'format' => BenchmarkFormat::AMRAP,
    'rounds' => null,
    'time_cap' => 720,
    'timer' => ['type' => 'amrap', 'durationSeconds' => 720],
    'movements' => ['assault-bike', 'wall-ball', 'toes-to-bar'],
    'exercises' => [
        ['exercise' => $exercises['Assault Bike'], 'position' => 1, 'calories' => 12],
        ['exercise' => $exercises['Wall Ball'], 'position' => 2, 'reps' => 12, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
        ['exercise' => $exercises['Toes to Bar'], 'position' => 3, 'reps' => 12],
    ],
];

// 6.3 Tres Amigos
$wods[] = [
    'name' => 'Tres Amigos',
    'type' => 'benchmark',
    'description' => "AMRAP 15 minutos:\n\n"
        . "• 5 Deadlifts (100/70 kg)\n"
        . "• 10 Bar-facing Burpees\n"
        . "• 15 Air Squats\n\n"
        . "Barra, burpee y sentadilla: el ciclo básico de CrossFit. Carga moderada, "
        . "sin paradas en el deadlift. WOD original OverWorkout.",
    'scaling' => "Deadlift 70/50 kg; burpee sin saltar la barra (lateral).",
    'elite_male' => '9+ rounds',
    'elite_female' => '9+ rounds',
    'advanced_male' => '7-8 rounds',
    'advanced_female' => '7-8 rounds',
    'intermediate_male' => '5-6 rounds',
    'intermediate_female' => '5-6 rounds',
    'beginner_male' => '3-4 rounds',
    'beginner_female' => '3-4 rounds',
    'format' => BenchmarkFormat::AMRAP,
    'rounds' => null,
    'time_cap' => 900,
    'timer' => ['type' => 'amrap', 'durationSeconds' => 900],
    'movements' => ['deadlift', 'burpee', 'squat'],
    'exercises' => [
        ['exercise' => $exercises['Deadlift'], 'position' => 1, 'reps' => 5, 'rx_weight_male' => '100kg', 'rx_weight_female' => '70kg'],
        ['exercise' => $exercises['Bar Facing Burpee'], 'position' => 2, 'reps' => 10],
        ['exercise' => $exercises['Air Squat'], 'position' => 3, 'reps' => 15],
    ],
];

// 6.4 Manivela
$wods[] = [
    'name' => 'Manivela',
    'type' => 'benchmark',
    'description' => "AMRAP 10 minutos:\n\n"
        . "• 10 Kettlebell Swing American (24/16 kg)\n"
        . "• 10 Kettlebell Goblet Squats (24/16 kg)\n"
        . "• 10 Sit-ups\n\n"
        . "Solo kettlebell y core. El goblet squat con la misma pesa que el swing lo convierte "
        . "en retorno de inversión inmediato. WOD original OverWorkout.",
    'scaling' => "KB 16/12 kg; Kettlebell Swing Russian.",
    'elite_male' => '8+ rounds',
    'elite_female' => '8+ rounds',
    'advanced_male' => '6-7 rounds',
    'advanced_female' => '6-7 rounds',
    'intermediate_male' => '4-5 rounds',
    'intermediate_female' => '4-5 rounds',
    'beginner_male' => '3 rounds',
    'beginner_female' => '3 rounds',
    'format' => BenchmarkFormat::AMRAP,
    'rounds' => null,
    'time_cap' => 600,
    'timer' => ['type' => 'amrap', 'durationSeconds' => 600],
    'movements' => ['kettlebell-swing', 'squat', 'core'],
    'exercises' => [
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 1, 'reps' => 10, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Kettlebell Goblet Squat'], 'position' => 2, 'reps' => 10, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Sit-up'], 'position' => 3, 'reps' => 10],
    ],
];

// 6.5 Aguja
$wods[] = [
    'name' => 'Aguja',
    'type' => 'benchmark',
    'description' => "AMRAP 20 minutos:\n\n"
        . "• 400m Run\n"
        . "• 15 Push-ups\n"
        . "• 10 Pull-ups\n\n"
        . "Contraste carrera/gimnasia. Correr rápido pero dejando aire para el pull-up. "
        . "WOD original OverWorkout.",
    'scaling' => "Australian Pull Up; flexiones de rodillas; 300m si no hay espacio.",
    'elite_male' => '9+ rounds',
    'elite_female' => '9+ rounds',
    'advanced_male' => '7-8 rounds',
    'advanced_female' => '7-8 rounds',
    'intermediate_male' => '5-6 rounds',
    'intermediate_female' => '5-6 rounds',
    'beginner_male' => '3-4 rounds',
    'beginner_female' => '3-4 rounds',
    'format' => BenchmarkFormat::AMRAP,
    'rounds' => null,
    'time_cap' => 1200,
    'timer' => ['type' => 'amrap', 'durationSeconds' => 1200],
    'movements' => ['run', 'push-up', 'pull-up'],
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 400],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 15],
        ['exercise' => $exercises['Pull Up'], 'position' => 3, 'reps' => 10],
    ],
];

// ========== HEROES for_time (6) ==========

// 7.1 Daniel (oficial)
$wods[] = [
    'name' => 'Daniel',
    'type' => 'hero',
    'description' => "Por tiempo:\n\n"
        . "• 50 Pull-ups\n"
        . "• 400m Run\n"
        . "• 21 Thrusters (43/29 kg)\n"
        . "• 800m Run\n"
        . "• 21 Thrusters (43/29 kg)\n"
        . "• 400m Run\n"
        . "• 50 Pull-ups\n\n"
        . "Hero WOD oficial en honor al SFC Daniel Crabtree (US Army), fallecido el 28 de junio "
        . "de 2005 en Afganistán. Estructura piramidal: gimnasia → carrera → barra → carrera "
        . "larga → barra → carrera → gimnasia.",
    'scaling' => "Thruster 35/25 kg; Australian Pull Up; series de 10 en los pull-ups.",
    'elite_male' => '12:00',
    'elite_female' => '13:00',
    'advanced_male' => '15:00',
    'advanced_female' => '15:00',
    'intermediate_male' => '18:00',
    'intermediate_female' => '18:00',
    'beginner_male' => '19:30',
    'beginner_female' => '19:30',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 1200,
    'partitioning' => false,
    'timer' => null,
    'movements' => ['pull-up', 'run', 'thruster'],
    'exercises' => [
        ['exercise' => $exercises['Pull Up'], 'position' => 1, 'reps' => 50],
        ['exercise' => $exercises['Run'], 'position' => 2, 'distance' => 400],
        ['exercise' => $exercises['Thruster'], 'position' => 3, 'reps' => 21, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Run'], 'position' => 4, 'distance' => 800],
        ['exercise' => $exercises['Thruster'], 'position' => 5, 'reps' => 21, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Run'], 'position' => 6, 'distance' => 400],
        ['exercise' => $exercises['Pull Up'], 'position' => 7, 'reps' => 50],
    ],
];

// 7.2 Josh (oficial)
$wods[] = [
    'name' => 'Josh',
    'type' => 'hero',
    'description' => "Por tiempo (pirámide descendente):\n\n"
        . "• 21 Overhead Squats (43/29 kg) + 42 Pull-ups\n"
        . "• 15 Overhead Squats + 30 Pull-ups\n"
        . "• 9 Overhead Squats + 18 Pull-ups\n\n"
        . "Hero WOD oficial en honor al CPO Fernando Jorge (US Coast Guard), fallecido el "
        . "28 de febrero de 2007. Pirámide descendente 21-15-9 sobre 42-30-18. "
        . "La movilidad de hombro en OHS manda.",
    'scaling' => "OHS 30/20 kg, con mancuernas o front squat; Australian Pull Up.",
    'elite_male' => '11:30',
    'elite_female' => '13:30',
    'advanced_male' => '18:00',
    'advanced_female' => '18:00',
    'intermediate_male' => '23:30',
    'intermediate_female' => '23:30',
    'beginner_male' => '28:30',
    'beginner_female' => '28:30',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 1800,
    'timer' => null,
    'movements' => ['overhead-squat', 'pull-up'],
    'exercises' => [
        ['exercise' => $exercises['Overhead Squat'], 'position' => 1, 'reps' => 21, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 2, 'reps' => 42],
        ['exercise' => $exercises['Overhead Squat'], 'position' => 3, 'reps' => 15, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 4, 'reps' => 30],
        ['exercise' => $exercises['Overhead Squat'], 'position' => 5, 'reps' => 9, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Pull Up'], 'position' => 6, 'reps' => 18],
    ],
];

// 7.3 Gunny (oficial)
$wods[] = [
    'name' => 'Gunny',
    'type' => 'hero',
    'description' => "Por tiempo:\n\n"
        . "• 1 milla Run con chaleco (20/15 kg)\n"
        . "• 50 Push-ups\n"
        . "• 50 Sit-ups\n"
        . "• 1 milla Run con chaleco (20/15 kg)\n\n"
        . "Hero WOD oficial en honor a Carl \"Gunny\" Hardin (USMC). Dos millas lastradas con "
        . "gimnasia al centro. Correr conservador la primera milla es clave.",
    'scaling' => "Chaleco 10 kg o sin peso; 800m Run ida y vuelta si no hay espacio; "
        . "flexiones de rodillas.",
    'elite_male' => '20:00',
    'elite_female' => '22:00',
    'advanced_male' => '27:00',
    'advanced_female' => '27:00',
    'intermediate_male' => '32:30',
    'intermediate_female' => '32:30',
    'beginner_male' => '38:00',
    'beginner_female' => '38:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 2400,
    'timer' => null,
    'movements' => ['run', 'push-up', 'core'],
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 1609, 'rx_weight_male' => '20kg', 'rx_weight_female' => '15kg'],
        ['exercise' => $exercises['Push Up'], 'position' => 2, 'reps' => 50],
        ['exercise' => $exercises['Sit-up'], 'position' => 3, 'reps' => 50],
        ['exercise' => $exercises['Run'], 'position' => 4, 'distance' => 1609, 'rx_weight_male' => '20kg', 'rx_weight_female' => '15kg'],
    ],
];

// 7.4 Jerry (oficial)
$wods[] = [
    'name' => 'Jerry',
    'type' => 'hero',
    'description' => "Por tiempo:\n\n"
        . "• 1 milla Run\n"
        . "• 2K Row\n"
        . "• 1 milla Run\n\n"
        . "Hero WOD oficial en honor al Sgt. Jerry Zangli (US Army). Monostructural puro: "
        . "el 2K de remo se hace a ritmo de 2K test, no a paseo.",
    'scaling' => "Remo por calorías equivalentes (~45 cal) si no hay concepto de distancia; 1500m.",
    'elite_male' => '17:00',
    'elite_female' => '19:00',
    'advanced_male' => '22:30',
    'advanced_female' => '22:30',
    'intermediate_male' => '27:00',
    'intermediate_female' => '27:00',
    'beginner_male' => '32:30',
    'beginner_female' => '32:30',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 1,
    'time_cap' => 2100,
    'timer' => null,
    'movements' => ['run', 'row'],
    'exercises' => [
        ['exercise' => $exercises['Run'], 'position' => 1, 'distance' => 1609],
        ['exercise' => $exercises['Rowing Machine'], 'position' => 2, 'distance' => 2000],
        ['exercise' => $exercises['Run'], 'position' => 3, 'distance' => 1609],
    ],
];

// 7.5 Whitten (oficial)
$wods[] = [
    'name' => 'Whitten',
    'type' => 'hero',
    'description' => "5 rondas por tiempo:\n\n"
        . "• 22 Kettlebell Swing American (24/16 kg)\n"
        . "• 22 Box Jumps\n"
        . "• 400m Run\n"
        . "• 22 Burpees\n"
        . "• 22 Wall Balls (9/6 kg)\n\n"
        . "Hero WOD oficial en honor al Capt. Derek Whitten. Cinco rondas densas; el burpee "
        . "después del run es el punto crítico. Rompe wall balls en 11-11 desde el principio.",
    'scaling' => "KB 16/12; Box Step Over; WB 6/4. Mantener 5 rondas aunque reduciendo "
        . "reps a 15.",
    'elite_male' => '27:30',
    'elite_female' => '30:30',
    'advanced_male' => '37:00',
    'advanced_female' => '37:00',
    'intermediate_male' => '44:00',
    'intermediate_female' => '44:00',
    'beginner_male' => '49:00',
    'beginner_female' => '49:00',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 5,
    'time_cap' => 3000,
    'timer' => null,
    'movements' => ['kettlebell-swing', 'box-jump', 'run', 'burpee', 'wall-ball'],
    'exercises' => [
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 1, 'reps' => 22, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Box Jump'], 'position' => 2, 'reps' => 22],
        ['exercise' => $exercises['Run'], 'position' => 3, 'distance' => 400],
        ['exercise' => $exercises['Burpee'], 'position' => 4, 'reps' => 22],
        ['exercise' => $exercises['Wall Ball'], 'position' => 5, 'reps' => 22, 'rx_weight_male' => '9kg', 'rx_weight_female' => '6kg'],
    ],
];

// 7.6 Wittman (oficial)
$wods[] = [
    'name' => 'Wittman',
    'type' => 'hero',
    'description' => "7 rondas por tiempo:\n\n"
        . "• 15 Kettlebell Swing American (24/16 kg)\n"
        . "• 15 Power Cleans (43/29 kg)\n"
        . "• 15 Box Jumps\n\n"
        . "Hero WOD oficial en honor al Lt. Justin \"Wittman\" Legg (USMC), registrado en la "
        . "comunidad como homenaje a un soldado caído. Triplete corto × 7: gestión de agarre "
        . "(swing + clean) y pantorrilla (box jump).",
    'scaling' => "KB 16/12; cleans 35/25 kg; Box Step Over.",
    'elite_male' => '14:30',
    'elite_female' => '16:30',
    'advanced_male' => '21:00',
    'advanced_female' => '21:00',
    'intermediate_male' => '26:00',
    'intermediate_female' => '26:00',
    'beginner_male' => '29:30',
    'beginner_female' => '29:30',
    'format' => BenchmarkFormat::FOR_TIME,
    'rounds' => 7,
    'time_cap' => 1800,
    'timer' => null,
    'movements' => ['kettlebell-swing', 'power-clean', 'box-jump'],
    'exercises' => [
        ['exercise' => $exercises['Kettlebell Swing American'], 'position' => 1, 'reps' => 15, 'rx_weight_male' => '24kg', 'rx_weight_female' => '16kg'],
        ['exercise' => $exercises['Power Clean'], 'position' => 2, 'reps' => 15, 'rx_weight_male' => '43kg', 'rx_weight_female' => '29kg'],
        ['exercise' => $exercises['Box Jump'], 'position' => 3, 'reps' => 15],
    ],
];

// ------------------------------------------------------------------
// 3. Persistir los benchmarks (idempotente por nombre)
// ------------------------------------------------------------------

echo "=== Sembrando ".count($wods)." benchmarks ===\n\n";

foreach ($wods as $wod) {
    $benchmark = $helper->seedBenchmark($wod);

    $benchmark->setFormat($wod['format']);
    $benchmark->setRounds($wod['rounds'] ?? null);
    $benchmark->setTimeCapSeconds($wod['time_cap'] ?? null);
    $benchmark->setRestBetweenRoundsSeconds($wod['rest_between_rounds'] ?? null);
    $benchmark->setPartitioningAllowed($wod['partitioning'] ?? false);
    $benchmark->setTimerConfig($wod['timer'] ?? null);
    $benchmark->setMovements($wod['movements'] ?? null);
}

$em->flush();

// ------------------------------------------------------------------
// 4. Resumen por formato
// ------------------------------------------------------------------

echo "\n=== Resumen por formato ===\n";

$all = $em->getRepository(\App\Entity\Benchmark::class)->findAll();
$counts = [];

foreach ($all as $benchmark) {
    $key = $benchmark->getFormat()?->value ?? 'sin_formato';
    $counts[$key] = ($counts[$key] ?? 0) + 1;
}

ksort($counts);

foreach ($counts as $format => $count) {
    echo "  {$format}: {$count}\n";
}

echo '  TOTAL: '.count($all)."\n";
echo "\n=== Seeder completado con éxito ===\n";
