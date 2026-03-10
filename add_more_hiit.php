<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== AÑADIENDO MÁS EJERCICIOS HIIT ===\n\n";

$hiit = [
    // Más plyometría
    ['Broad Jump Burpee', 'intermediate', 3, 'full_body', 'core', 'Burpee con salto largo. Desde burpee, saltar hacia adelante lo más lejos posible.', null],
    ['Box Jump Burpee', 'intermediate', 3, 'full_body', 'core', 'Burpee con salto a caja. Desde burpee, saltar aterrizando sobre caja.', 'bancos_soportes'],
    ['180 Jump Squat', 'intermediate', 3, 'full_body', 'core', 'Sentadilla con salto y giro. Saltar girando 180°, aterrizar en sentadilla.', null],
    ['Switch Lunge Jump', 'intermediate', 3, 'full_body', 'legs', 'Zancada alterna con salto. Cambiar piernas en el aire, pliométrico intenso.', null],
    ['Split Squat Jump', 'intermediate', 3, 'full_body', 'legs', 'Salto en zancada. Desde zancada, saltar explosivamente, cambiar piernas.', null],
    ['Plyo Push Up', 'expert', 3, 'full_body', 'chest', 'Flexión pliométrica. Empujar tan fuerte que las manos despeguen del suelo.', null],
    ['Clap Push Up', 'expert', 3, 'full_body', 'chest', 'Flexión con aplauso. Plyo push up con aplauso, máxima explosión.', null],
    ['Depth Jump to Box Jump', 'expert', 4, 'full_body', 'legs', 'Salto desde altura a caja. Caer de banco, inmediatamente saltar a caja más alta.', 'bancos_soportes'],
    ['Bounding', 'intermediate', 3, 'full_body', 'legs', 'Saltos largos alternando. Saltar lo más lejos posible con cada pierna alternando.', null],
    ['Power Skip', 'beginner', 2, 'full_body', 'legs', 'Skipping potente. Saltar llevando rodilla alta y brazo opuesto arriba, explosivo.', null],
    ['A Skips', 'beginner', 1, 'full_body', 'legs', 'Skipping técnico A. Rodilla alta, dorsiflexión pie, aterrizaje en metatarso. Técnica carrera.', null],
    ['B Skips', 'beginner', 2, 'full_body', 'legs', 'Skipping técnico B. Como A pero extendiendo pierna adelante antes de bajar. Extensión completa.', null],
    ['Fast Feet', 'beginner', 1, 'full_body', 'core', 'Pies rápidos. Posición baja, pies moviéndose rápido sin desplazamiento. Cardio rápido.', null],
    ['Ladder Drills', 'beginner', 2, 'full_body', 'legs', 'Ejercicios en escalera de agilidad. In-in-out, lateral, carioca. Coordinación.', null],
    ['Cone Drills', 'beginner', 2, 'full_body', 'legs', 'Ejercicios en conos. Slalom, cinco diez cinco, T-drill. Agilidad y cambios dirección.', null],
    
    // Más con equipamiento
    ['SkiErg Sprint', 'intermediate', 3, 'full_body', 'back', 'Sprint en SkiErg. Remar sentado máxima intensidad. Cardio brazos y core.', 'maquinas'],
    ['Air Bike Tabata', 'intermediate', 4, 'full_body', 'core', 'Tabata en air bike. 20 segundos máxima intensidad, 10 descanso, x8. Brutal.', 'maquinas'],
    ['Rower Tabata', 'intermediate', 4, 'full_body', 'back', 'Tabata en remero. 20/10 x8 rounds, sprint máximo. Total body cardio.', 'maquinas'],
    ['Kettlebell Snatch', 'intermediate', 3, 'full_body', 'shoulders', 'Arranque con kettlebell. De suelo a sobre cabeza en un movimiento. Potencia total.', 'kettlebells'],
    ['Kettlebell Clean and Press', 'intermediate', 3, 'full_body', 'shoulders', 'Clean y press kettlebell. Traer a hombro y subir. Fuerza + cardio.', 'kettlebells'],
    ['Kettlebell Goblet Squat to Press', 'intermediate', 3, 'full_body', 'shoulders', 'Sentadilla goblet a press. Al subir, press de kettlebell. Total body.', 'kettlebells'],
    ['Dumbbell Thruster', 'intermediate', 3, 'full_body', 'legs', 'Thruster con mancuernas. Sentadilla a press de hombros. CrossFit classic.', 'mancuernas'],
    ['Dumbbell Devil Press', 'intermediate', 4, 'full_body', 'chest', 'Devil press. Burpee con mancuernas + swing + press. Extremo.', 'mancuernas'],
    ['Wall Ball Shot', 'intermediate', 3, 'full_body', 'legs', 'Tiro de balón a pared. Sentadilla lanzando balón medicinal arriba. CrossFit.', 'balon_medicinal'],
    ['Wall Ball Overhead Throw', 'intermediate', 3, 'full_body', 'core', 'Lanzamiento de balón por encima. Impulsar balón hacia atrás por encima cabeza.', 'balon_medicinal'],
    ['Medicine Ball Chest Throw', 'beginner', 2, 'full_body', 'chest', 'Lanzamiento de balón desde pecho. Contra pared o pareja, explosivo.', 'balon_medicinal'],
    ['Medicine Ball Rotational Throw', 'intermediate', 2, 'full_body', 'core', 'Lanzamiento rotacional. De lado, girar y lanzar balón. Oblicuos + power.', 'balon_medicinal'],
    ['Sledgehammer Strike', 'intermediate', 3, 'full_body', 'core', 'Golpes con maza. Golpear neumático con maza, alternar lados. Power + cardio.', 'accesorios'],
    ['Tire Flip', 'intermediate', 3, 'full_body', 'legs', 'Vuelco de neumático. Levantar y voltear neumático pesado. Strongman cardio.', null],
    ['Parallette Burpee', 'intermediate', 3, 'full_body', 'chest', 'Burpee en paralelas. Manos en paralelas, push up, salto. Más profundo.', 'barras'],
    ['Ring Burpee', 'expert', 4, 'full_body', 'chest', 'Burpee en anillas. Push up en anillas, extremademente inestable. Elite.', 'anillas'],
    
    // Circuitos y combos
    ['Man Maker', 'expert', 4, 'full_body', 'chest', 'Creador de hombres. Con mancuernas: push up, row, row, clean, press, squat. Todo en uno.', 'mancuernas'],
    ['Ground to Overhead', 'intermediate', 3, 'full_body', 'legs', 'De suelo a sobre cabeza. Levantar peso desde suelo hasta extensión completa. CrossFit.', 'mancuernas'],
    ['Clean and Jerk', 'intermediate', 4, 'full_body', 'shoulders', 'Arranque y envión. Levantar a hombros y empujar sobre cabeza. Halterofilia.', 'barras'],
    ['Snatch', 'expert', 4, 'full_body', 'shoulders', 'Arranque completo. De suelo a sobre cabeza en un movimiento. Técnica + potencia.', 'barras'],
    ['Turkish Get Up', 'intermediate', 3, 'full_body', 'core', 'Levantamiento turco. De suelo a pie con peso sobre cabeza. Movilidad + fuerza.', 'kettlebells'],
    ['Windmill', 'intermediate', 2, 'full_body', 'core', 'Molino. Con peso sobre cabeza, tocar pie contrario. Flexibilidad + estabilidad.', 'kettlebells'],
    ['Plate Push', 'beginner', 2, 'full_body', 'core', 'Empuje de disco. Empujar disco de peso por suelo. Core + cardio.', 'pesos_libres'],
    ['Sled Drag', 'intermediate', 3, 'full_body', 'back', 'Arrastre de trineo hacia atrás. Caminar hacia atrás jalando trineo. Espalda + piernas.', 'trineo'],
    ['Prowler Push', 'intermediate', 4, 'full_body', 'legs', 'Empuje de prowler. Trineo bajo empujado máxima velocidad. Piernas explosivas.', 'trineo'],
    
    // Más cardio movilidad
    ['Jumping Jack Variations', 'beginner', 1, 'full_body', 'calves', 'Variaciones jumping jack. Cruzar brazos/piernas, abrir cerrar, etc.', null],
    ['Seal Jacks', 'beginner', 1, 'full_body', 'calves', 'Seal jacks. Brazos adelante cruzando, piernas abriendo. Alternativa jumping jack.', null],
    ['Plank Jacks', 'intermediate', 2, 'full_body', 'core', 'Jumping jack en plank. En posición plank, saltar pies abriendo y cerrando.', null],
    ['Squat Jacks', 'intermediate', 2, 'full_body', 'legs', 'Jumping jack en sentadilla. En squat, saltar abriendo y cerrando piernas.', null],
    ['Ski Jumps', 'intermediate', 2, 'full_body', 'legs', 'Saltos de esquí. Lateral de lado a lado como esquiador. Cardio lateral.', null],
    ['Scissor Kicks', 'beginner', 1, 'full_body', 'core', 'Patadas de tijera. Tumbado, piernas alternando arriba-abajo. Core + cardio.', null],
    ['Flutter Kicks', 'beginner', 1, 'full_body', 'core', 'Aleteo de piernas. Tumbado, pequeños movimientos alternos rápidos. Lower abs.', null],
    ['Step Up with Knee Drive', 'intermediate', 2, 'full_body', 'core', 'Step up con elevación de rodilla. Subir y traer rodilla arriba. Control.', 'bancos_soportes'],
    ['Lateral Step Up', 'intermediate', 2, 'full_body', 'legs', 'Step up lateral. De lado al banco, subir. Estabilidad + fuerza.', 'bancos_soportes'],
    ['Transverse Lunge', 'intermediate', 2, 'full_body', 'legs', 'Zancada transversal. Paso diagonal atrás, rotar cadera. Funcional.', null],
    ['Curtsy Step Up', 'intermediate', 3, 'full_body', 'legs', 'Step up de reverencia. Curtsy lunge a step up. Coordinación + fuerza.', 'bancos_soportes'],
];

$added = 0;
foreach ($hiit as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['hiit', 'crossfit', 'cardio']);
        $conn->executeStatement(
            "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "✅ {$ex[0]}\n"; $added++;
    } else {
        echo "ℹ️ Ya existe: {$ex[0]}\n";
    }
}

echo "\n🎉 Añadidos $added ejercicios HIIT nuevos\n";
echo "\nTotal HIIT ahora: " . $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE primary_muscle_group = 'full_body'") . "\n";
