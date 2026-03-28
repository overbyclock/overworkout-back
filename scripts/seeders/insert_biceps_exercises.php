<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== AÑADIENDO EJERCICIOS DE BÍCEPS ===\n\n";

$exercises = [
    // === PRINCIPIANTE - Australian Rows enfocadas en bíceps ===
    ['Australian Chin Up', 'beginner', 1, 'biceps', 'back', 'Fila australiana con agarre supino (palmas hacia ti). Enfasis en bíceps, barra a altura cadera.', 'barras'],
    ['Australian Curl', 'beginner', 2, 'biceps', 'back', 'Curl australiano. Acostado bajo barra, agarre supino, tirar barra a frente como curl. Aislamiento bíceps.', 'barras'],
    ['Ring Curl', 'beginner', 1, 'biceps', 'core', 'Curl con anillas. Anillas a altura cadera, agarre neutro, hacer curl flexionando codos. Excelente para bíceps y estabilidad.', 'anillas'],
    ['Band Bicep Curl', 'beginner', 1, 'biceps', 'core', 'Curl con banda elástica. Pie sobre banda, curl de bíceps clásico con resistencia progresiva.', 'bandas'],
    ['Door Frame Curl', 'beginner', 1, 'biceps', 'back', 'Curl en marco de puerta. Agarrar marco con agarre supino, inclinar atrás y hacer curl con peso corporal.', null],
    ['Towel Curl', 'beginner', 2, 'biceps', 'core', 'Curl con toalla. Envolver toalla alrededor de poste/barra, agarre supino, inclinar atrás y hacer curl. Resistencia ajustable.', null],
    
    // === INTERMEDIO - Dominadas específicas para bíceps ===
    ['Chin Up Hold', 'intermediate', 2, 'biceps', 'back', 'Isométrico en chin up. Mantenerse arriba de chin up (90° o arriba) el máximo tiempo. Máxima contracción bíceps.', 'barras'],
    ['Chin Up Negative', 'intermediate', 2, 'biceps', 'back', 'Chin up excéntrico. Subir de cualquier forma, bajar en 3-5 segundos controlado. Fuerza bíceps en fase negativa.', 'barras'],
    ['Commando Pull Up', 'intermediate', 2, 'biceps', 'back', 'Dominada comando. Agarre paralelo a barra (lateral), alternar lados. Trabaja bíceps desde ángulo diferente.', 'barras'],
    ['Close Grip Chin Up', 'intermediate', 2, 'biceps', 'back', 'Dominada supino agarre cerrado. Manos juntas, máximo enfoque en bíceps.', 'barras'],
    ['Mixed Grip Pull Up', 'intermediate', 2, 'biceps', 'back', 'Dominada agarre mixto. Una mano supina (trabaja bíceps), otra prona. Alternar para balance.', 'barras'],
    ['Ring Curl Isometric', 'intermediate', 2, 'biceps', 'core', 'Curl isométrico en anillas. Mantener flexión de codos 90° con anillas. Estabilidad + bíceps.', 'anillas'],
    ['Archer Ring Curl', 'intermediate', 3, 'biceps', 'core', 'Curl arquero en anillas. Extender un brazo mientras el otro hace curl, alternar. Unilateral progresivo.', 'anillas'],
    ['Pelican Curl', 'intermediate', 3, 'biceps', 'core', 'Curl pelícano con anillas. Brazos extendidos atrás, flexionar codos trayendo anillas a frente. Estiramiento profundo + contracción.', 'anillas'],
    ['TRX Bicep Curl', 'intermediate', 2, 'biceps', 'core', 'Curl con TRX. Suspensión, codos altos, flexionar trayendo manos a frente. Control corporal + bíceps.', 'trx'],
    ['Inverted Row Curl', 'intermediate', 2, 'biceps', 'back', 'Curl en remo invertida. Posición de remo, pero hacer movimiento de curl flexionando solo codos.', 'barras'],
    
    // === EXPERTO - Dominadas avanzadas y one arm ===
    ['One Arm Chin Up', 'expert', 4, 'biceps', 'back', 'Dominada una mano supino. El santo grial del bíceps en calistenia. Máxima fuerza de bíceps y agarre.', 'barras'],
    ['One Arm Chin Up Negative', 'expert', 3, 'biceps', 'back', 'One arm chin up excéntrico. Bajar desde arriba controladamente con una mano. Progresión a completo.', 'barras'],
    ['One Arm Ring Curl', 'expert', 3, 'biceps', 'core', 'Curl una mano en anillas. Una anilla, cuerpo inclinado, curl unilateral. Máxima estabilidad.', 'anillas'],
    ['Weighted Chin Up', 'expert', 3, 'biceps', 'back', 'Dominada supino con peso. Lastre extra, aumentar resistencia progresivamente.', 'barras'],
    ['L-Sit Chin Up', 'expert', 3, 'biceps', 'core', 'Chin up en L. Piernas extendidas horizontalmente mientras se hace chin up. Core + bíceps.', 'barras'],
    ['Muscle Up to Chin Up', 'expert', 3, 'biceps', 'chest', 'Muscle up que termina en chin up. Transición de dominada a fondo.', 'barras'],
    ['Behind The Back Chin Up', 'expert', 3, 'biceps', 'back', 'Dominada supino detrás de espalda. Agarre por detrás de cabeza, trabaja bíceps desde ángulo único.', 'barras'],
    ['Typewriter Chin Up', 'expert', 3, 'biceps', 'back', 'Chin up máquina de escribir. Arriba de chin up, moverse lateralmente extendiendo un brazo. Tiempo bajo tensión.', 'barras'],
    ['Archer Chin Up', 'expert', 3, 'biceps', 'back', 'Chin up arquero. Un brazo extendido lateralmente, el otro hace el trabajo. Progresión a one arm.', 'barras'],
    ['90 Degree Hold to Chin Up', 'expert', 3, 'biceps', 'back', 'Isométrico 90° a chin up. Mantener 90°, subir, bajar a 90°. Control en rango medio.', 'barras'],
    
    // === CON EQUIPAMIENTO ===
    ['Barbell Curl', 'beginner', 2, 'biceps', 'core', 'Curl con barra. Pie a ancho de hombros, barra con agarre supino, curl clásico. El básico de gimnasio.', 'barras'],
    ['Dumbbell Curl', 'beginner', 1, 'biceps', 'core', 'Curl con mancuernas. Una en cada mano, agarre supino, curl alterno o simultáneo.', 'mancuernas'],
    ['Hammer Curl', 'beginner', 1, 'biceps', 'core', 'Curl martillo con mancuernas. Agarre neutro, trabaja braquial y bíceps braquial.', 'mancuernas'],
    ['Incline Dumbbell Curl', 'intermediate', 2, 'biceps', 'core', 'Curl en banco inclinado. Banco a 45°, brazos caen atrás, estiramiento máximo del bíceps.', 'bancos_soportes'],
    ['Preacher Curl', 'intermediate', 2, 'biceps', 'core', 'Curl en banco predicador. Brazos apoyados en ángulo, elimina impulso, aislamiento total.', 'bancos_soportes'],
    ['Concentration Curl', 'intermediate', 2, 'biceps', 'core', 'Curl de concentración. Sentado, codo contra muslo interno, máxima contracción.', 'mancuernas'],
    ['Cable Curl', 'beginner', 2, 'biceps', 'core', 'Curl en polea baja. Resistencia constante durante todo el rango de movimiento.', 'maquinas'],
    ['Drag Curl', 'intermediate', 2, 'biceps', 'shoulders', 'Curl arrastrando. Con barra, tirar codos atrás, barra cerca del cuerpo. Enfasis en cabeza externa.', 'barras'],
    ['Spider Curl', 'intermediate', 2, 'biceps', 'core', 'Curl araña. Acostado boca abajo en banco inclinado, brazos cuelgan, curl hacia arriba. Pico de contracción.', 'bancos_soportes'],
    ['Zottman Curl', 'intermediate', 2, 'biceps', 'forearms', 'Curl Zottman. Fase positiva supina, negativa prona. Trabaja bíceps y antebrazos.', 'mancuernas'],
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

echo "\n🎉 Añadidos $added ejercicios de bíceps\n\n";

// Resumen
$levels = $conn->fetchAllAssociative("SELECT level, COUNT(*) as count FROM exercises WHERE primary_muscle_group = 'biceps' GROUP BY level");
echo "Ejercicios de bíceps por nivel:\n";
foreach ($levels as $level) {
    echo "  - {$level['level']}: {$level['count']}\n";
}
