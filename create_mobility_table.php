<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== CREANDO TABLA DE MOVILIDAD/ESTIRAMIENTOS ===\n\n";

// Crear tabla mobility_exercises
$sql = "CREATE TABLE IF NOT EXISTS mobility_exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('warmup', 'cooldown') NOT NULL,
    category VARCHAR(50) NOT NULL,
    duration VARCHAR(50) DEFAULT NULL,
    muscle_group VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    equipment_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_category (category),
    INDEX idx_muscle_group (muscle_group),
    FOREIGN KEY (equipment_id) REFERENCES equipments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->executeStatement($sql);
echo "✅ Tabla mobility_exercises creada\n\n";

// Añadir ejercicios de WARM-UP (Calentamiento)
$warmup = [
    // Cardio ligero
    ['Jumping Jacks', 'warmup', 'cardio', '2-3 minutos', 'full_body', 'Saltos de tijera clásicos. Calentamiento cardiovascular general.', null],
    ['High Knees', 'warmup', 'cardio', '1-2 minutos', 'legs', 'Trotar levantando rodillas altas. Activa flexores de cadera.', null],
    ['Butt Kicks', 'warmup', 'cardio', '1-2 minutos', 'legs', 'Trotar llevando talones a glúteos. Calienta isquiotibiales.', null],
    ['Light Jogging', 'warmup', 'cardio', '3-5 minutos', 'legs', 'Trote suave sin esfuerzo. Aumenta temperatura corporal.', null],
    ['Jump Rope Easy', 'warmup', 'cardio', '2-3 minutos', 'calves', 'Saltar cuerda suave. Coordinación + cardio ligero.', 'cuerda_saltar'],
    
    // Movilidad articular
    ['Arm Circles', 'warmup', 'mobility', '30 segundos', 'shoulders', 'Círculos con brazos. Pequeños a grandes, ambas direcciones.', null],
    ['Shoulder Rolls', 'warmup', 'mobility', '30 segundos', 'shoulders', 'Rotar hombros hacia adelante y atrás. Liberar tensión.', null],
    ['Wrist Circles', 'warmup', 'mobility', '20 segundos', 'forearms', 'Círculos con muñecas. Preparar para agarres.', null],
    ['Ankle Circles', 'warmup', 'mobility', '20 segundos por pie', 'calves', 'Círculos con tobillos. Prevenir esguinces.', null],
    ['Hip Circles', 'warmup', 'mobility', '30 segundos', 'legs', 'Círculos con cadera. Movilidad articular.', null],
    ['Torso Twists', 'warmup', 'mobility', '30 segundos', 'core', 'Girar torso de lado a lado. Movilizar columna.', null],
    ['Neck Rolls', 'warmup', 'mobility', '20 segundos', 'none', 'Círculos suaves con cabeza. Relajar cuello.', null],
    
    // Movilidad dinámica
    ['Leg Swings Forward', 'warmup', 'dynamic', '10-15 reps por pierna', 'legs', 'Péndulos pierna adelante-atrás. Sostenerse en pared.', null],
    ['Leg Swings Side', 'warmup', 'dynamic', '10-15 reps por pierna', 'adductors', 'Péndulos pierna lateral. Abrir y cerrar.', null],
    ['Arm Swings', 'warmup', 'dynamic', '10-15 reps', 'shoulders', 'Brazos cruzados adelante, abrir atrás. Movilidad pectoral.', null],
    ['Torso Rotations', 'warmup', 'dynamic', '10 reps cada lado', 'core', 'Rotaciones de tronco con brazos extendidos.', null],
    ['Hip Openers', 'warmup', 'dynamic', '10 reps por pierna', 'legs', 'Elevar rodilla y abrir hacia afuera. Movilidad cadera.', null],
    ['World\'s Greatest Stretch', 'warmup', 'dynamic', '5-8 reps por lado', 'full_body', 'Lunge + rotación + estiramiento completo. Total body.', null],
    ['Inchworm', 'warmup', 'dynamic', '5-8 reps', 'full_body', 'Manos al suelo, caminar a plank, volver. Flexibilidad posterior.', null],
    ['Spiderman Crawl', 'warmup', 'dynamic', '5-8 reps por lado', 'legs', 'Lunge profundo, codos a suelo. Movilidad cadera.', null],
    ['Frankenstein Walk', 'warmup', 'dynamic', '10 pasos', 'hamstrings', 'Patear pierna recta adelante, tocar dedos. Isquios.', null],
    ['Quad Walk', 'warmup', 'dynamic', '10 pasos', 'quads', 'Caminar agarrando tobillo a glúteo. Cuádriceps.', null],
    ['Lateral Lunges', 'warmup', 'dynamic', '8 reps por lado', 'adductors', 'Paso lateral en sentadilla. Aductores.', null],
    ['Walking Knee Hugs', 'warmup', 'dynamic', '10 pasos', 'glutes', 'Caminar abrazando rodilla al pecho. Glúteos.', null],
    ['Walking Ankle Grabs', 'warmup', 'dynamic', '10 pasos', 'quads', 'Caminar agarrando empeine estirando cuádriceps.', null],
    
    // Activación con bandas
    ['Band Pull Aparts', 'warmup', 'activation', '15-20 reps', 'shoulders', 'Separar banda frente a pecho, retraer escápulas.', 'bandas'],
    ['Band Dislocates', 'warmup', 'activation', '10-15 reps', 'shoulders', 'Círculos con banda por encima y detrás. Movilidad hombros.', 'bandas'],
    ['Band Face Pulls', 'warmup', 'activation', '15-20 reps', 'shoulders', 'Tirar banda a cara, codos altos. Activar dorsales.', 'bandas'],
    ['Band Monster Walks', 'warmup', 'activation', '10 pasos cada dirección', 'glutes', 'Pasos laterales con banda en rodillas. Glúteo medio.', 'bandas'],
    ['Band Glute Bridge', 'warmup', 'activation', '15 reps', 'glutes', 'Puente con banda sobre rodillas. Activar glúteos.', 'bandas'],
    
    // Activación muscular
    ['Glute Bridge', 'warmup', 'activation', '15 reps', 'glutes', 'Puente de glúteos. Despertar musculatura posterior.', null],
    ['Bird Dog', 'warmup', 'activation', '10 reps por lado', 'core', 'Cuadrupedia contralateral. Estabilidad core.', null],
    ['Dead Bug', 'warmup', 'activation', '10 reps por lado', 'core', 'Contralateral tumbado. Activación abdominal profunda.', null],
    ['Plank Hold', 'warmup', 'activation', '30 segundos', 'core', 'Plancha frontal. Activación core.', null],
    ['Side Plank Hold', 'warmup', 'activation', '20 segundos por lado', 'core', 'Plancha lateral. Oblicuos.', null],
    ['Clamshells', 'warmup', 'activation', '15 reps por lado', 'glutes', 'Abrir rodillas estando de lado. Glúteo medio.', null],
    ['Fire Hydrants', 'warmup', 'activation', '12 reps por lado', 'glutes', 'Elevar rodilla lateralmente. Glúteos.', null],
    ['Cat Cow', 'warmup', 'mobility', '10 reps', 'back', 'Arco y redondeo espalda. Movilidad columna.', null],
    ['Scapular Push Ups', 'warmup', 'activation', '10-12 reps', 'back', 'Push up sin flexionar codos, solo movimiento escápula.', null],
    ['Wall Slides', 'warmup', 'activation', '10-12 reps', 'shoulders', 'Deslizar brazos por pared, mantener contacto. Hombros.', null],
    
    // Específicos según entrenamiento
    ['Air Squats', 'warmup', 'specific', '10-15 reps', 'legs', 'Sentadillas con peso corporal. Preparar patrón squat.', null],
    ['Push Ups', 'warmup', 'specific', '5-10 reps', 'chest', 'Flexiones ligeras. Preparar empuje.', null],
    ['Pull Up Hang', 'warmup', 'specific', '20-30 segundos', 'back', 'Colgado pasivo. Estirar espalda.', 'barras'],
    ['Light Deadlift', 'warmup', 'specific', '10 reps con barra vacía', 'back', 'Peso muerto con barra sola. Técnica.', 'barras'],
];

// Añadir ejercicios de COOL-DOWN (Enfriamiento)
$cooldown = [
    // Estiramientos estáticos - parte superior
    ['Chest Doorway Stretch', 'cooldown', 'static', '30-45 segundos', 'chest', 'Antebrazo en marco, girar cuerpo. Estirar pectoral.', null],
    ['Cross Body Shoulder Stretch', 'cooldown', 'static', '30 segundos por brazo', 'shoulders', 'Brazo cruzado al pecho, tirar con otro. Deltoides.', null],
    ['Overhead Triceps Stretch', 'cooldown', 'static', '30 segundos por brazo', 'triceps', 'Brazo arriba, flexionar codo, empujar. Tríceps.', null],
    ['Child\'s Pose', 'cooldown', 'static', '1-2 minutos', 'back', 'Postura del niño. Rodillas al suelo, brazos extendidos. Espalda baja.', null],
    ['Cat Cow Stretch', 'cooldown', 'static', '10 respiraciones', 'back', 'Arco y redondeo suave. Movilidad columna.', null],
    ['Thread the Needle', 'cooldown', 'static', '30 segundos por lado', 'shoulders', 'Rotación torácica desde cuadrupedia. Hombros y espalda.', null],
    ['Neck Stretch Side', 'cooldown', 'static', '20 segundos por lado', 'none', 'Inclinar cabeza lateralmente. Trapecio.', null],
    ['Neck Stretch Rotation', 'cooldown', 'static', '20 segundos por lado', 'none', 'Girar cabeza lateralmente. Cuello.', null],
    ['Wrist Flexor Stretch', 'cooldown', 'static', '20 segundos por muñeca', 'forearms', 'Tirar dedos hacia atrás. Flexores muñeca.', null],
    ['Wrist Extensor Stretch', 'cooldown', 'static', '20 segundos por muñeca', 'forearms', 'Tirar dedos hacia adelante. Extensores.', null],
    ['Lat Stretch', 'cooldown', 'static', '30 segundos por lado', 'back', 'Manos en banco/suelo, inclinar cuerpo. Dorsal ancho.', 'bancos_soportes'],
    
    // Estiramientos estáticos - parte inferior
    ['Standing Quad Stretch', 'cooldown', 'static', '30 segundos por pierna', 'legs', 'Pie a glúteo, mantener equilibrio. Cuádriceps.', null],
    ['Seated Hamstring Stretch', 'cooldown', 'static', '30-45 segundos', 'hamstrings', 'Sentado, pierna extendida, inclinar torso. Isquios.', null],
    ['Standing Hamstring Stretch', 'cooldown', 'static', '30 segundos por pierna', 'hamstrings', 'Pie en banco, inclinar torso. Isquios de pie.', 'bancos_soportes'],
    ['Pigeon Pose', 'cooldown', 'static', '1-2 minutos por lado', 'glutes', 'Postura de la paloma. Glúteo profundo.', null],
    ['Figure Four Stretch', 'cooldown', 'static', '30 segundos por lado', 'glutes', 'Tobillo sobre rodilla, sentar atrás. Glúteo.', null],
    ['Butterfly Stretch', 'cooldown', 'static', '1-2 minutos', 'adductors', 'Plantas pies juntas, rodillas afuera. Aductores.', null],
    ['Frog Stretch', 'cooldown', 'static', '1-2 minutos', 'adductors', 'Rodillas anchas, empujar cadera atrás. Aductores profundos.', null],
    ['90-90 Stretch', 'cooldown', 'static', '45 segundos por lado', 'glutes', 'Ambas rodillas 90°, girar cadera. Rotación externa.', null],
    ['Kneeling Hip Flexor Stretch', 'cooldown', 'static', '30 segundos por lado', 'legs', 'Zancada profunda, empujar cadera. Flexores cadera.', null],
    ['Couch Stretch', 'cooldown', 'static', '1-2 minutos por lado', 'legs', 'Rodilla en suelo, pie en pared/banco. Flexores intensos.', 'bancos_soportes'],
    ['Downward Dog', 'cooldown', 'static', '1 minuto', 'full_body', 'Perro mirando abajo. Estiramiento cadena posterior.', null],
    ['Calf Stretch Wall', 'cooldown', 'static', '30 segundos por pierna', 'calves', 'Mano en pared, talón en suelo. Gemelos.', null],
    ['Seated Spinal Twist', 'cooldown', 'static', '30 segundos por lado', 'back', 'Sentado, girar torso. Rotación columna.', null],
    ['Happy Baby Pose', 'cooldown', 'static', '1-2 minutos', 'adductors', 'Boca arriba, agarrar pies, rodillas afuera. Relajación.', null],
    ['Supine Hamstring Stretch', 'cooldown', 'static', '30 segundos por pierna', 'hamstrings', 'Tumbado, pierna arriba, tirar. Isquios.', null],
    ['Lying Knee to Chest', 'cooldown', 'static', '30 segundos por pierna', 'glutes', 'Tumbado, rodilla al pecho. Glúteo bajo.', null],
    ['Lying Spinal Twist', 'cooldown', 'static', '30 segundos por lado', 'back', 'Tumbado, rodilla cruzada al lado. Torácica.', null],
    
    // Foam rolling
    ['Foam Roll Quads', 'cooldown', 'myofascial', '1-2 minutos', 'legs', 'Rodillo frente muslo. Liberación cuádriceps.', 'accesorios'],
    ['Foam Roll IT Band', 'cooldown', 'myofascial', '1-2 minutos por lado', 'legs', 'Rodillo lateral muslo. Banda iliotibial.', 'accesorios'],
    ['Foam Roll Hamstrings', 'cooldown', 'myofascial', '1-2 minutos', 'hamstrings', 'Rodillo debajo muslo. Isquiotibiales.', 'accesorios'],
    ['Foam Roll Calves', 'cooldown', 'myofascial', '1-2 minutos por pierna', 'calves', 'Rodillo pantorrilla. Gemelos y sóleo.', 'accesorios'],
    ['Foam Roll Glutes', 'cooldown', 'myofascial', '1-2 minutos por lado', 'glutes', 'Rodillo sentado, cruzar pierna. Glúteos.', 'accesorios'],
    ['Foam Roll Back', 'cooldown', 'myofascial', '1-2 minutos', 'back', 'Rodillo espalda, evitar lumbar baja. Dorsal.', 'accesorios'],
    ['Foam Roll Lats', 'cooldown', 'myofascial', '1-2 minutos por lado', 'back', 'Rodillo lado espalda, brazo arriba. Dorsal ancho.', 'accesorios'],
    ['Foam Roll Thoracic', 'cooldown', 'myofascial', '1-2 minutos', 'back', 'Rodillo media espalda, brazos cruzados. Torácica.', 'accesorios'],
    ['Foam Roll Chest', 'cooldown', 'myofascial', '1 minuto por lado', 'chest', 'Rodillo diagonal pecho, brazo extendido. Pectoral.', 'accesorios'],
    ['Foam Roll Shoulders', 'cooldown', 'myofascial', '1 minuto por lado', 'shoulders', 'Rodillo lateral deltoides. Hombros.', 'accesorios'],
    ['Foam Roll Shins', 'cooldown', 'myofascial', '30-60 segundos', 'legs', 'Rodillo frente espinilla. Tibial anterior.', 'accesorios'],
    
    // Lacrosse ball
    ['Lacrosse Ball Feet', 'cooldown', 'myofascial', '1-2 minutos por pie', 'calves', 'Bola en arco del pie, presionar. Fascitis plantar.', 'accesorios'],
    ['Lacrosse Ball Glutes', 'cooldown', 'myofascial', '1-2 minutos por lado', 'glutes', 'Bola en glúteo, encontrar puntos dolorosos.', 'accesorios'],
    ['Lacrosse Ball Shoulder', 'cooldown', 'myofascial', '1-2 minutos por lado', 'shoulders', 'Bola en pared, hombro. Deltoides.', 'accesorios'],
    ['Lacrosse Ball Chest', 'cooldown', 'myofascial', '1-2 minutos por lado', 'chest', 'Bola en puerta/pecho. Pectoral.', 'accesorios'],
    ['Lacrosse Ball Hip Flexor', 'cooldown', 'myofascial', '1-2 minutos por lado', 'legs', 'Bola en iliopsoas, panza abajo. Flexores.', 'accesorios'],
    ['Lacrosse Ball Calf', 'cooldown', 'myofascial', '1-2 minutos por pierna', 'calves', 'Bola en pantorrilla, mover tobillo. Gemelos.', 'accesorios'],
    
    // Relajación respiración
    ['Diaphragmatic Breathing', 'cooldown', 'breathing', '2-3 minutos', 'core', 'Respiración diafragmática. Manos en barriga, inhalar profundo.', null],
    ['Box Breathing', 'cooldown', 'breathing', '2-3 minutos', 'none', 'Respiración caja. Inhalar 4, mantener 4, exhalar 4, mantener 4.', null],
    ['4-7-8 Breathing', 'cooldown', 'breathing', '2-3 minutos', 'none', 'Inhalar 4, mantener 7, exhalar 8. Relajación profunda.', null],
    ['Corpse Pose', 'cooldown', 'relaxation', '3-5 minutos', 'full_body', 'Savasana. Tumbado boca arriba, relajación total.', null],
    ['Legs Up Wall', 'cooldown', 'relaxation', '5-10 minutos', 'legs', 'Piernas arriba en pared. Drenaje linfático, relajación.', null],
];

$allExercises = array_merge($warmup, $cooldown);

echo "Insertando ejercicios de movilidad...\n\n";

$count = 0;
foreach ($allExercises as $ex) {
    // Verificar si ya existe
    $exists = $conn->fetchOne(
        "SELECT COUNT(*) FROM mobility_exercises WHERE name = ? AND type = ?",
        [$ex[0], $ex[1]]
    );
    
    if ($exists == 0) {
        $conn->executeStatement(
            "INSERT INTO mobility_exercises (name, type, category, duration, muscle_group, description, equipment_id) 
             VALUES (?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1))",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], $ex[6]]
        );
        $count++;
    }
}

echo "✅ Total ejercicios de movilidad añadidos: $count\n\n";

// Contar por tipo
$warmupCount = $conn->fetchOne("SELECT COUNT(*) FROM mobility_exercises WHERE type = 'warmup'");
$cooldownCount = $conn->fetchOne("SELECT COUNT(*) FROM mobility_exercises WHERE type = 'cooldown'");

echo "📊 Resumen:\n";
echo "  Warm-up (Calentamiento): $warmupCount ejercicios\n";
echo "  Cool-down (Enfriamiento): $cooldownCount ejercicios\n";
echo "  Total: " . ($warmupCount + $cooldownCount) . " ejercicios\n";
