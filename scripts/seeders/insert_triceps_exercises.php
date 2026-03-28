<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== AÑADIENDO EJERCICIOS DE TRÍCEPS ===\n\n";

$exercises = [
    // === PRINCIPIANTE - Fondos asistidos y básicos ===
    ['Bench Dips', 'beginner', 1, 'triceps', 'chest', 'Fondos en banco. Manos en banco atrás, pies en suelo o extendidos, bajar y subir. Básico de tríceps.', 'bancos_soportes'],
    ['Knee Dips', 'beginner', 1, 'triceps', 'chest', 'Fondos con rodillas. Similar a bench dips pero con rodillas flexionadas, menos peso corporal.', 'bancos_soportes'],
    ['Assisted Dips', 'beginner', 2, 'triceps', 'chest', 'Fondos asistidos. Con banda elástica o máquina, ayuda para completar el movimiento.', 'bandas'],
    ['Negative Dips', 'beginner', 2, 'triceps', 'chest', 'Fondos negativos. Subir de cualquier forma, bajar controlado 3-5 segundos. Progresión a fondos completos.', 'barras'],
    ['Close Grip Push Up', 'beginner', 2, 'triceps', 'chest', 'Flexión con agarre cerrado. Manos juntas forma diamante, mayor énfasis en tríceps.', null],
    ['Knee Close Grip Push Up', 'beginner', 1, 'triceps', 'chest', 'Flexión cerrada con rodillas. Variación más fácil de close grip push up.', null],
    ['Wall Triceps Extension', 'beginner', 1, 'triceps', 'core', 'Extensión de tríceps en pared. Manos en pared, flexionar y extender codos. Muy básico.', null],
    ['Band Triceps Pushdown', 'beginner', 1, 'triceps', 'core', 'Pushdown con banda. Banda alta, empujar hacia abajo extendiendo codos. Aislamiento básico.', 'bandas'],
    ['Band Overhead Extension', 'beginner', 1, 'triceps', 'core', 'Extensión por encima con banda. Banda detrás de espalda, extender brazos arriba. Cabeza larga.', 'bandas'],
    
    // === INTERMEDIO - Fondos completos y variaciones ===
    ['Parallel Bar Dips', 'intermediate', 2, 'triceps', 'chest', 'Fondos en paralelas. Cuerpo vertical, bajar hasta 90°, subir. El clásico tríceps.', 'barras'],
    ['Ring Dips', 'intermediate', 2, 'triceps', 'chest', 'Fondos en anillas. Mayor estabilidad requerida, más trabajo de tríceps por control.', 'anillas'],
    ['Straight Bar Dips', 'intermediate', 2, 'triceps', 'chest', 'Fondos en barra recta. Barra frente al cuerpo, agarre prono. Variación diferente.', 'barras'],
    ['Korean Dips', 'intermediate', 3, 'triceps', 'back', 'Fondos coreanos. En barra baja, cuerpo atrás, tocar glúteos en suelo. Movimiento amplio.', 'barras'],
    ['Slow Dips', 'intermediate', 2, 'triceps', 'chest', 'Fondos lentos. 3 segundos bajando, 3 segundos subiendo. Tiempo bajo tensión.', 'barras'],
    ['Pause Dips', 'intermediate', 2, 'triceps', 'chest', 'Fondos con pausa. Pausa 2 segundos abajo, explosivos arriba. Sin rebote.', 'barras'],
    ['Russian Dips', 'intermediate', 3, 'triceps', 'shoulders', 'Fondos rusos. Bajar, inclinar cuerpo adelante, volver y subir. Transición compleja.', 'barras'],
    ['Explosive Dips', 'intermediate', 3, 'triceps', 'chest', 'Fondos explosivos. Bajar controlado, subir con explosión. Potencia.', 'barras'],
    ['L-Sit Dips', 'intermediate', 3, 'triceps', 'core', 'Fondos en L. Piernas extendidas horizontalmente durante fondos. Core + tríceps.', 'barras'],
    ['Weighted Dips', 'intermediate', 3, 'triceps', 'chest', 'Fondos con peso. Lastre colgado del cinturón, aumentar progresivamente.', 'barras'],
    
    // === INTERMEDIO - Flexiones avanzadas de tríceps ===
    ['Triceps Push Up', 'intermediate', 2, 'triceps', 'chest', 'Flexión de tríceps. Codos cerca del cuerpo, bajar en línea recta. Enfoque tríceps.', null],
    ['Pseudo Planche Push Up', 'intermediate', 3, 'triceps', 'shoulders', 'Flexión pseudo plancha. Manos a altura de cadera, codo hacia atrás. Planche preparation.', null],
    ['Tiger Bend Push Up', 'intermediate', 3, 'triceps', 'chest', 'Flexión tiger bend. De rodillas o pie, bajar sobre antebrazos y subir extendiendo. Prensa francesa.', null],
    ['Bodyweight Skull Crusher', 'intermediate', 2, 'triceps', 'core', 'Skull crusher con peso corporal. En barra baja, cabeza bajo barra, extender codos. Aislamiento.', 'barras'],
    ['Reverse Grip Push Up', 'intermediate', 2, 'triceps', 'chest', 'Flexión agarre inverso. Manos giradas hacia afuera, subir con tríceps.', null],
    
    // === EXPERTO - Fondos avanzados ===
    ['Single Bar Dip', 'expert', 3, 'triceps', 'chest', 'Fondos en una barra. Solo una barra, control extremo de equilibrio. Máxima estabilidad.', 'barras'],
    ['Ring Turn Out Dips', 'expert', 3, 'triceps', 'shoulders', 'Fondos en anillas con giro. Arriba girar anillas hacia afuera (RTO). Máxima dificultad anillas.', 'anillas'],
    ['Bulgarian Ring Dip', 'expert', 3, 'triceps', 'chest', 'Fondos búlgaros en anillas. Anillas altas, cuerpo inclinado, profundidad extrema.', 'anillas'],
    ['One Arm Dip', 'expert', 3, 'triceps', 'chest', 'Fondos una mano. Una mano en barra/paralela, otra libre. Extrema dificultad.', 'barras'],
    ['Muscle Up Negative', 'expert', 3, 'triceps', 'back', 'Muscle up excéntrico. De arriba, bajar lento a L-sit. Tríceps controlando bajada.', 'barras'],
    ['90 Degree Push Up', 'expert', 3, 'triceps', 'shoulders', 'Flexión 90 grados. Planche hold a flexión, vuelta a hold. Isométrico + dinámico.', null],
    ['Handstand Push Up', 'expert', 3, 'triceps', 'shoulders', 'Flexión en pino. Desde pino contra pared, bajar cabeza y subir. Hombros + tríceps.', null],
    ['Deficit Handstand Push Up', 'expert', 4, 'triceps', 'shoulders', 'Flexión en pino con déficit. Manos elevadas, mayor rango. Máxima dificultad.', 'bancos_soportes'],
    ['Tiger Bend Handstand', 'expert', 4, 'triceps', 'shoulders', 'Pino tiger bend. Bajar de pino a soporte en antebrazos y subir. Fuerza extrema tríceps.', null],
    
    // === CON EQUIPAMIENTO ===
    ['Triceps Extension Bar', 'beginner', 2, 'triceps', 'chest', 'Extensión de tríceps con barra. Tumbado, barra por encima, flexionar y extender codos. Skull crusher.', 'barras'],
    ['Dumbbell Triceps Extension', 'beginner', 2, 'triceps', 'core', 'Extensión de tríceps con mancuerna. Por encima de cabeza o tumbado, extender codo.', 'mancuernas'],
    ['Cable Triceps Pushdown', 'beginner', 2, 'triceps', 'core', 'Pushdown en polea alta. Codos fijos, empujar barra/cuerda hacia abajo. Clásico gimnasio.', 'maquinas'],
    ['Cable Overhead Extension', 'beginner', 2, 'triceps', 'core', 'Extensión por encima en polea. Desde abajo, extender brazos por encima de cabeza.', 'maquinas'],
    ['Close Grip Bench Press', 'intermediate', 2, 'triceps', 'chest', 'Press banca agarre cerrado. Manos juntas, bajar a pecho, subir. Tríceps + pecho.', 'barras'],
    ['Floor Press', 'intermediate', 2, 'triceps', 'chest', 'Press en suelo. Tumbado en suelo, press con barra/mancuernas. Rango limitado, enfoque tríceps.', 'barras'],
    ['JM Press', 'intermediate', 3, 'triceps', 'chest', 'Press JM. Híbrido entre press y extensión, barra baja en pecho, extender. Potente para tríceps.', 'barras'],
    ['French Press', 'intermediate', 2, 'triceps', 'core', 'Press francés. De pie o sentado, extensión de tríceps con barra/mancuernas por encima.', 'barras'],
    ['Kickback', 'beginner', 1, 'triceps', 'core', 'Patada de tríceps. Inclinado, codo fijo, extender brazo hacia atrás. Aislamiento pico contracción.', 'mancuernas'],
    ['Triceps Machine Dip', 'beginner', 1, 'triceps', 'chest', 'Fondos en máquina. Máquina de fondos asistidos o no, guía estable.', 'maquinas'],
];

$added = 0;
foreach ($exercises as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['calisthenics', 'crossfit', 'fitness', 'bodybuilding']);
        $conn->executeStatement(
            "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "✅ {$ex[0]}\n";
        $added++;
    } else {
        echo "ℹ️ Ya existe: {$ex[0]}\n";
    }
}

echo "\n🎉 Añadidos $added ejercicios de tríceps\n\n";

// Resumen
$levels = $conn->fetchAllAssociative("SELECT level, COUNT(*) as count FROM exercises WHERE primary_muscle_group = 'triceps' GROUP BY level");
echo "Ejercicios de tríceps por nivel:\n";
foreach ($levels as $level) {
    echo "  - {$level['level']}: {$level['count']}\n";
}
