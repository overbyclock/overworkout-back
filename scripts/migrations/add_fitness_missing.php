<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306,
    'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout',
]);

echo "=== AÑADIENDO EJERCICIOS FITNESS FALTANTES ===\n\n";

// MÁQUINAS DE GYM
$machines = [
    ['Chest Press Machine', 'beginner', 2, 'chest', 'triceps', 'Press de pecho en máquina. Sentado, empujar handles hacia adelante. Controlado y seguro.', 'maquinas'],
    ['Pec Deck Fly', 'beginner', 2, 'chest', 'shoulders', 'Aperturas en máquina. Abrazar movimiento, aislamiento pectoral.', 'maquinas'],
    ['Cable Crossover', 'intermediate', 2, 'chest', 'core', 'Crucero de poleas. Desde arriba, abajo, o medio. Ángulos variables.', 'maquinas'],
    ['Lat Pulldown', 'beginner', 2, 'back', 'biceps', 'Jalón al pecho. Tirar barra hacia pecho, controlado. Básico espalda.', 'maquinas'],
    ['Seated Cable Row', 'beginner', 2, 'back', 'biceps', 'Remo sentado en polea. Tirar hacia abdomen, retraer escápulas.', 'maquinas'],
    ['Machine Row', 'beginner', 2, 'back', 'biceps', 'Remo en máquina. Apoyo pecho, tirar handles. Aislamiento dorsal.', 'maquinas'],
    ['T-Bar Row', 'intermediate', 3, 'back', 'biceps', 'Remo T. Barra en landmine, tirar a abdomen. Espeso dorsal.', 'barras'],
    ['Hammer Strength Row', 'intermediate', 2, 'back', 'biceps', 'Remo hammer strength. Máquina con trayectoria independiente.', 'maquinas'],
    ['Shoulder Press Machine', 'beginner', 2, 'shoulders', 'triceps', 'Press militar en máquina. Sentado, empujar hacia arriba. Seguro.', 'maquinas'],
    ['Lateral Raise Machine', 'beginner', 2, 'shoulders', 'none', 'Elevaciones laterales máquina. Aislamiento deltoides medio.', 'maquinas'],
    ['Reverse Pec Deck', 'beginner', 2, 'shoulders', 'back', 'Pec deck inverso. Cara a máquina, tirar atrás. Deltoides posterior.', 'maquinas'],
    ['Cable Lateral Raise', 'beginner', 2, 'shoulders', 'none', 'Elevaciones laterales en polea. Tensión constante.', 'maquinas'],
    ['Cable Front Raise', 'beginner', 2, 'shoulders', 'core', 'Elevaciones frontales polea. Unilateral o bilateral.', 'maquinas'],
    ['Leg Press', 'beginner', 2, 'legs', 'glutes', 'Prensa de piernas. Sentado, empujar plataforma. Cuádriceps e isquios.', 'maquinas'],
    ['Hack Squat Machine', 'beginner', 2, 'legs', 'glutes', 'Hack squat. Espalda apoyada, sentadilla guiada.', 'maquinas'],
    ['Leg Extension', 'beginner', 2, 'legs', 'none', 'Extensión de cuádriceps. Sentado, extender rodillas.', 'maquinas'],
    ['Leg Curl', 'beginner', 2, 'hamstrings', 'none', 'Curl femoral. Acostado boca abajo, flexionar rodillas.', 'maquinas'],
    ['Standing Calf Raise Machine', 'beginner', 2, 'calves', 'none', 'Elevación de gemelos de pie. Máquina, rango completo.', 'maquinas'],
    ['Seated Calf Raise Machine', 'beginner', 2, 'calves', 'none', 'Elevación de gemelos sentado. Sóleo, rodillas flexionadas.', 'maquinas'],
    ['Hip Abduction Machine', 'beginner', 2, 'adductors', 'none', 'Abducción de cadera. Abrir piernas contra resistencia.', 'maquinas'],
    ['Hip Adduction Machine', 'beginner', 2, 'adductors', 'none', 'Aducción de cadera. Juntar piernas, muslo interno.', 'maquinas'],
    ['Glute Kickback Machine', 'beginner', 2, 'glutes', 'none', 'Patada de glúteos en máquina. Extensión cadera controlada.', 'maquinas'],
    ['Smith Machine Squat', 'beginner', 2, 'legs', 'glutes', 'Sentadilla smith. Barra guiada, segura para principiantes.', 'maquinas'],
    ['Smith Machine Bench Press', 'beginner', 2, 'chest', 'triceps', 'Press banca smith. Barra guiada, sin spotter.', 'maquinas'],
    ['Smith Machine Shoulder Press', 'beginner', 2, 'shoulders', 'triceps', 'Press militar smith. Vertical seguro.', 'maquinas'],
    ['Smith Machine Lunge', 'beginner', 2, 'legs', 'glutes', 'Zancada smith. Barra guiada, equilibrio.', 'maquinas'],
    ['Cable Curl', 'beginner', 2, 'biceps', 'none', 'Curl en polea baja. Barra recta o cuerda, tensión constante.', 'maquinas'],
    ['Cable Triceps Extension', 'beginner', 2, 'triceps', 'none', 'Extensión de tríceps en polea alta. Pushdown.', 'maquinas'],
    ['Cable Kickback', 'intermediate', 2, 'triceps', 'none', 'Patada de tríceps en polea. Unilateral, pico contracción.', 'maquinas'],
    ['Cable Crunch', 'beginner', 2, 'core', 'none', 'Crunch en polea alta. Arrodillado, flexionar torso.', 'maquinas'],
    ['Cable Woodchopper', 'intermediate', 2, 'core', 'shoulders', 'Leñador en polea. Rotación diagonal, oblicuos.', 'maquinas'],
    ['Cable Rotations', 'beginner', 2, 'core', 'shoulders', 'Rotaciones en polea. Anti-rotación core.', 'maquinas'],
    ['Assisted Dip Machine', 'beginner', 1, 'chest', 'triceps', 'Fondos asistidos. Reducir peso corporal con plataforma.', 'maquinas'],
    ['Assisted Pull Up Machine', 'beginner', 1, 'back', 'biceps', 'Dominadas asistidas. Reducir peso con plataforma.', 'maquinas'],
    ['Ab Crunch Machine', 'beginner', 2, 'core', 'none', 'Máquina de abdominales. Flexión sentado controlada.', 'maquinas'],
    ['Back Extension Machine', 'beginner', 2, 'back', 'glutes', 'Hiperextensión lumbar. Máquina 45° o romana.', 'maquinas'],
    ['Rotary Torso Machine', 'beginner', 2, 'core', 'none', 'Torso rotatorio. Giro sentado, oblicuos.', 'maquinas'],
    ['Ab Coaster', 'beginner', 2, 'core', 'none', 'Ab coaster. Elevación piernas guiada, lower abs.', 'maquinas'],
];

// HOMBROS - MÁS VARIEDAD
$shoulders = [
    ['Seated Dumbbell Press', 'beginner', 2, 'shoulders', 'triceps', 'Press con mancuernas sentado. Rango completo, controlado.', 'mancuernas'],
    ['Standing Dumbbell Press', 'intermediate', 2, 'shoulders', 'core', 'Press de pie con mancuernas. Estabilidad + fuerza.', 'mancuernas'],
    ['Arnold Press', 'intermediate', 2, 'shoulders', 'triceps', 'Press Arnold. Rotación mientras subes, completo.', 'mancuernas'],
    ['Dumbbell Lateral Raise', 'beginner', 2, 'shoulders', 'none', 'Elevaciones laterales. Deltoides medio, aislamiento.', 'mancuernas'],
    ['Dumbbell Front Raise', 'beginner', 2, 'shoulders', 'core', 'Elevaciones frontales. Deltoides anterior.', 'mancuernas'],
    ['Dumbbell Rear Delt Fly', 'beginner', 2, 'shoulders', 'back', 'Pájaro posterior. Inclinado, mancuernas atrás.', 'mancuernas'],
    ['Upright Row', 'intermediate', 2, 'shoulders', 'traps', 'Remo vertical. Barra o mancuernas a mentón.', 'barras'],
    ['Barbell Overhead Press', 'intermediate', 3, 'shoulders', 'core', 'Press militar con barra. Pie, empujar desde hombros.', 'barras'],
    ['Push Press', 'intermediate', 3, 'shoulders', 'legs', 'Push press. Impulso piernas, explosivo.', 'barras'],
    ['Behind Neck Press', 'expert', 3, 'shoulders', 'triceps', 'Press tras nuca. Desde atrás, movilidad requerida.', 'barras'],
    ['Landmine Press', 'beginner', 2, 'shoulders', 'core', 'Press landmine. Barra en esquina, arco natural.', 'barras'],
    ['Landmine Lateral Raise', 'intermediate', 2, 'shoulders', 'core', 'Elevación lateral landmine. Unilateral, controlado.', 'barras'],
    ['Face Pull', 'beginner', 2, 'shoulders', 'back', 'Face pull. Cuerda a cara, codos altos, retraer.', 'maquinas'],
    ['External Rotation', 'beginner', 1, 'shoulders', 'none', 'Rotación externa. Codo en costilla, cable o banda. Hombro sano.', 'bandas'],
    ['Internal Rotation', 'beginner', 1, 'shoulders', 'none', 'Rotación interna. Oposición a externa, equilibrio.', 'bandas'],
    ['Scaption Raise', 'beginner', 2, 'shoulders', 'none', 'Elevación scaption. 45° entre frontal y lateral, pulgar arriba.', 'mancuernas'],
    ['Cuban Rotation', 'intermediate', 2, 'shoulders', 'none', 'Rotación cubana. Elevación lateral, rotar externamente. Manguito.', 'mancuernas'],
    ['Bradford Press', 'intermediate', 3, 'shoulders', 'triceps', 'Press Bradford. Barra pasa por frente y atrás alternando.', 'barras'],
    ['Viking Press', 'intermediate', 3, 'shoulders', 'legs', 'Press viking. Landmine doble mano, explosivo.', 'barras'],
    ['Bottoms Up Press', 'expert', 3, 'shoulders', 'core', 'Press bottoms up. Kettlebell invertida, máxima estabilidad.', 'kettlebells'],
];

// EJERCICIOS DE ESTIRAMIENTOS/MOVILIDAD
$stretching = [
    ['Foam Rolling Quads', 'beginner', 1, 'legs', 'none', 'Rodillo cuádriceps. Liberación miofascial frontal.', 'accesorios'],
    ['Foam Rolling Back', 'beginner', 1, 'back', 'none', 'Rodillo espalda. Masaje miofascial dorsal.', 'accesorios'],
    ['Foam Rolling IT Band', 'beginner', 1, 'legs', 'none', 'Rodillo banda iliotibial. Liberación lateral pierna.', 'accesorios'],
    ['Foam Rolling Calves', 'beginner', 1, 'calves', 'none', 'Rodillo gemelos. Liberación pantorrilla.', 'accesorios'],
    ['Static Stretching Hamstrings', 'beginner', 1, 'hamstrings', 'none', 'Estiramiento isquios. Sedente o de pie, mantener 30s.', null],
    ['Static Stretching Quadriceps', 'beginner', 1, 'legs', 'none', 'Estiramiento cuádriceps. Pie a glúteo, mantener.', null],
    ['Static Stretching Chest', 'beginner', 1, 'chest', 'none', 'Estiramiento pecho. Puerta o pared, abrir.', null],
    ['Static Stretching Shoulders', 'beginner', 1, 'shoulders', 'none', 'Estiramiento hombros. Cruzar brazo, tirar.', null],
    ['Dynamic Leg Swings', 'beginner', 1, 'legs', 'none', 'Péndulos piernas. Balancear adelante-atrás, calentamiento.', null],
    ['Arm Circles', 'beginner', 1, 'shoulders', 'none', 'Círculos brazos. Pequeños a grandes, calentamiento.', null],
    ['Torso Twists', 'beginner', 1, 'core', 'none', 'Giros torso. Rotar tronco, movilidad columna.', null],
    ['Cat Cow Stretch', 'beginner', 1, 'back', 'core', 'Gato-vaca. Arco y redondeo espalda, movilidad.', null],
    ['Child Pose', 'beginner', 1, 'back', 'none', 'Postura del niño. Yoga, estiramiento lumbar.', null],
    ['Downward Dog', 'beginner', 1, 'back', 'calves', 'Perro abajo. Yoga, estiramiento cadena posterior.', null],
    ['Cobra Stretch', 'beginner', 1, 'core', 'back', 'Cobra. Extensión lumbar, yoga.', null],
    ['Pigeon Pose', 'intermediate', 1, 'glutes', 'none', 'Paloma. Yoga, estiramiento profundo glúteos.', null],
    ['Butterfly Stretch', 'beginner', 1, 'adductors', 'none', 'Mariposa. Plantas pies juntas, rodillas afuera.', null],
    ['Frog Stretch', 'intermediate', 2, 'adductors', 'none', 'Rana. Rodillas anchas, estiramiento aductores.', null],
    ['90-90 Stretch', 'intermediate', 1, 'glutes', 'none', 'Estiramiento 90-90. Rotación cadera, movilidad.', null],
    ['Hip Flexor Stretch', 'beginner', 1, 'legs', 'none', 'Estiramiento flexores cadera. Zancada, empujar cadera.', null],
    ['Doorway Stretch', 'beginner', 1, 'chest', 'shoulders', 'Estiramiento puerta. Brazos en marco, inclinar.', null],
    ['Thread the Needle', 'beginner', 1, 'shoulders', 'back', 'Hilo aguja. Yoga, rotación torácica.', null],
    ['Worlds Greatest Stretch', 'intermediate', 2, 'full_body', 'none', 'Estiramiento más grande mundo. Lunge, rotación, completo.', null],
    ['Spiderman Stretch', 'beginner', 1, 'legs', 'core', 'Estiramiento spiderman. Lunge con rotación, movilidad.', null],
    ['Inchworm', 'beginner', 1, 'hamstrings', 'core', 'Gusano. Manos al suelo, caminar a plank, estirar.', null],
    ['Leg Swings', 'beginner', 1, 'legs', 'none', 'Péndulos piernas. Balance lateral y frontal.', null],
    ['Band Pull Apart', 'beginner', 1, 'shoulders', 'back', 'Separación banda. Tirar banda aparte, postura.', 'bandas'],
    ['Band Dislocates', 'beginner', 1, 'shoulders', 'none', 'Dislocaciones banda. Circular brazos con banda, movilidad.', 'bandas'],
    ['Foam Rolling Lats', 'beginner', 1, 'back', 'none', 'Rodillo dorsal ancho. Liberación lateral espalda.', 'accesorios'],
    ['Lacrosse Ball Shoulder', 'beginner', 1, 'shoulders', 'none', 'Bola lacrosse hombro. Liberación puntos gatillo.', 'accesorios'],
    ['Lacrosse Ball Glutes', 'beginner', 1, 'glutes', 'none', 'Bola lacrosse glúteos. Masaje profundo glúteos.', 'accesorios'],
];

// EJERCICIOS FUNCIONALES/CARDIO LIGERO
$cardio = [
    ['Walking', 'beginner', 1, 'hiit', 'none', 'Caminar. Actividad base, recuperación, cardio suave.', null],
    ['Incline Walking', 'beginner', 1, 'hiit', 'calves', 'Caminata inclinada. Cinta con pendiente, piernas.', 'cinta_correr'],
    ['StairMaster', 'beginner', 2, 'hiit', 'legs', 'Escalera máquina. Subir escalones continuos.', 'maquinas'],
    ['Elliptical', 'beginner', 1, 'hiit', 'full_body', 'Elíptica. Bajo impacto, brazos y piernas.', 'maquinas'],
    ['Stationary Bike', 'beginner', 1, 'hiit', 'legs', 'Bicicleta estática. Sentado, cardio piernas.', 'bicicleta_estatica'],
    ['Recumbent Bike', 'beginner', 1, 'hiit', 'legs', 'Bicicleta reclinada. Espalda apoyada, cómoda.', 'bicicleta_estatica'],
    ['Rowing Light', 'beginner', 1, 'hiit', 'back', 'Remo suave. Técnica y resistencia moderada.', 'maquinas'],
    ['Swimming', 'intermediate', 2, 'full_body', 'none', 'Natación. Cero impacto, total body.', null],
    ['Water Aerobics', 'beginner', 1, 'full_body', 'none', 'Aeróbicos acuáticos. Bajo impacto, resistencia agua.', null],
    ['Battle Ropes Light', 'beginner', 2, 'hiit', 'core', 'Cuerdas batalla suave. Ondas moderadas.', 'accesorios'],
    ['Medicine Ball Toss', 'beginner', 2, 'hiit', 'core', 'Lanzamiento balón ligero. Con pareja o pared.', 'balon_medicinal'],
    ['Step Machine', 'beginner', 2, 'hiit', 'legs', 'Stepper máquina. Subir escalones continuo.', 'maquinas'],
    ['VersaClimber', 'intermediate', 3, 'hiit', 'full_body', 'Escalada vertical. Brazos y piernas alternos.', 'maquinas'],
    [' Jacob Ladder', 'intermediate', 3, 'hiit', 'full_body', 'Escalera infinita. Subir escalones inclinado.', 'maquinas'],
];

function insertExercises($conn, $exercises, $label)
{
    echo "\n$label:\n";
    $count = 0;
    foreach ($exercises as $ex) {
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
        if (0 === $exists) {
            $disciplines = json_encode(['fitness']);
            $conn->executeStatement(
                'INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)',
                [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query='.urlencode($ex[0]), $ex[6], $disciplines]
            );
            echo "  ✅ {$ex[0]}\n";
            ++$count;
        } else {
            echo "  ℹ️ {$ex[0]} ya existe\n";
        }
    }

    return $count;
}

$total = 0;
$total += insertExercises($conn, $machines, 'MÁQUINAS GYM');
$total += insertExercises($conn, $shoulders, 'HOMBROS FITNESS');
$total += insertExercises($conn, $stretching, 'ESTIRAMIENTOS/MOVILIDAD');
$total += insertExercises($conn, $cardio, 'CARDIO LIGERO');

echo "\n🎉 Total añadidos: $total\n";

// Verificar totales ahora
$totalFit = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE JSON_CONTAINS(disciplines, '\"fitness\"')");
echo "Total Fitness ahora: $totalFit ejercicios\n";
