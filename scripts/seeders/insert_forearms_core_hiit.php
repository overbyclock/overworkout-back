<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== ANTEBRAZOS ===\n";
$forearms = [
    ['Wrist Curl', 'beginner', 1, 'forearms', 'biceps', 'Curl de muñeca con barra/mancuerna. Antebrazos en muslos, palmas arriba, flexionar muñecas.', 'mancuernas'],
    ['Reverse Wrist Curl', 'beginner', 1, 'forearms', 'triceps', 'Curl inverso de muñeca. Palmas abajo, extender muñecas. Trabaja extensores.', 'mancuernas'],
    ['Dead Hang', 'beginner', 1, 'forearms', 'back', 'Colgado pasivo. Simplemente colgarse de la barra todo el tiempo posible. Fuerza de agarre.', 'barras'],
    ['Active Hang', 'beginner', 2, 'forearms', 'back', 'Colgado activo. Escápulas retraídas y elevadas, activar dorsales. Preparación pull up.', 'barras'],
    ['One Arm Hang', 'intermediate', 2, 'forearms', 'back', 'Colgado una mano. Alternar manos, aguantar el máximo tiempo. Fuerza extrema de agarre.', 'barras'],
    ['Towel Hang', 'intermediate', 2, 'forearms', 'back', 'Colgado en toalla. Envolver toalla en barra, agarrar toalla. Mayor grosor = más difícil.', 'barras'],
    ['Finger Hang', 'expert', 3, 'forearms', 'back', 'Colgado en dedos. Solo 2-4 dedos por mano, máxima fuerza de dedos.', 'barras'],
    ['Wrist Roller', 'intermediate', 2, 'forearms', 'core', 'Roller de muñeca. Palo con cuerda y peso, enrollar subiendo y bajando peso.', 'barras'],
    ['Plate Pinch', 'intermediate', 2, 'forearms', 'core', 'Pellizco de discos. Agarrar discos lisos con pinza de dedos, aguantar tiempo.', 'pesos_libres'],
    ['Farmer Walk', 'beginner', 2, 'forearms', 'core', 'Paseo del granjero. Cargar peso pesado en cada mano, caminar. Fuerza de agarre + core.', 'mancuernas'],
    ['Bottoms Up Hold', 'intermediate', 3, 'forearms', 'shoulders', 'Sostén bottoms up. Kettlebell invertida (base arriba), estabilizar. Muñeca + hombro.', 'kettlebells'],
    ['Rubber Band Extension', 'beginner', 1, 'forearms', 'core', 'Extensión con banda. Banda alrededor de dedos, abrir contra resistencia. Extensores dedos.', 'bandas'],
    ['Fingertip Push Up', 'intermediate', 2, 'forearms', 'chest', 'Flexión en puntas de dedos. Todo el peso en dedos, flexión push up. Fuerza dedos.', null],
    ['Gripper Training', 'beginner', 1, 'forearms', 'core', 'Entrenamiento con gripper. Agarradera de resorte, cerrar repeticiones. Fuerza de agarre.', null],
    ['Hammer Curl Hold', 'intermediate', 2, 'forearms', 'biceps', 'Isométrico curl martillo. Sostener mancuerna en ángulo 90°, aguantar. Fuerza antebrazo.', 'mancuernas'],
];

$count = 0;
foreach ($forearms as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['calisthenics', 'climbing', 'fitness']);
        $conn->executeStatement(
            "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "  ✅ {$ex[0]}\n"; $count++;
    } else {
        echo "  ℹ️ Ya existe: {$ex[0]}\n";
    }
}
echo "Antebrazos añadidos: $count\n\n";

echo "=== CORE ===\n";
$core = [
    ['Plank', 'beginner', 1, 'core', 'none', 'Plancha frontal. Apoyo en antebrazos y puntas de pies, cuerpo recto. Isométrico básico.', null],
    ['Side Plank', 'beginner', 1, 'core', 'none', 'Plancha lateral. Apoyo en un antebrazo, caderas elevadas. Oblicuos.', null],
    ['Reverse Plank', 'beginner', 2, 'core', 'none', 'Plancha invertida. Sentado, manos atrás, elevar caderas. Core posterior.', null],
    ['High Plank', 'beginner', 1, 'core', 'none', 'Plancha alta. Apoyo en manos extendidas, push up hold. Slightly más fácil.', null],
    ['Plank with Leg Raise', 'beginner', 2, 'core', 'none', 'Plancha con elevación pierna. Alternar elevar piernas sin mover caderas.', null],
    ['Plank with Arm Raise', 'beginner', 2, 'core', 'none', 'Plancha con elevación brazo. Alternar levantar brazos sin rotar.', null],
    ['Mountain Climber', 'beginner', 2, 'core', 'none', 'Escalador. En posición push up, traer rodillas alternadamente al pecho. Cardio + core.', null],
    ['Cross Mountain Climber', 'intermediate', 2, 'core', 'none', 'Escalador cruzado. Rodillas al pecho opuesto, trabaja oblicuos.', null],
    ['Dead Bug', 'beginner', 1, 'core', 'none', 'Bicho muerto. Boca arriba, brazos y piernas arriba, bajar contralateral lento. Control lumbar.', null],
    ['Bird Dog Hold', 'beginner', 1, 'core', 'back', 'Postura pájaro-perro. Cuadrupedia, extender brazo y pierna contralateral, mantener. Estabilidad.', null],
    ['Hollow Body Hold', 'intermediate', 2, 'core', 'none', 'Posición hueco. Boca arriba, brazos y piernas elevadas, forma de banana. Gimnasia básica.', null],
    ['Hollow Body Rock', 'intermediate', 3, 'core', 'none', 'Mecedora hueca. Balancear adelante-atrás manteniendo posición hueco. Control dinámico.', null],
    ['Superman Hold', 'beginner', 1, 'core', 'back', 'Postura Superman. Boca abajo, elevar brazos y piernas. Core posterior.', null],
    ['Leg Raise', 'beginner', 2, 'core', 'none', 'Elevación piernas. Colgado o tumbado, elevar piernas rectas. Flexores cadera + bajo abdomen.', 'barras'],
    ['Knee Raise', 'beginner', 1, 'core', 'none', 'Elevación rodillas. Colgado, traer rodillas al pecho. Más fácil que leg raise.', 'barras'],
    ['L-Sit', 'intermediate', 3, 'core', 'none', 'Posición L. Colgado o en suelo, piernas extendidas horizontalmente. Core extremo.', null],
    ['L-Sit on Floor', 'intermediate', 3, 'core', 'none', 'L-sit en suelo. Manos en suelo, elevar cuerpo y piernas. Compresión + fuerza.', null],
    ['Tuck L-Sit', 'beginner', 2, 'core', 'none', 'L-sit tuck. Rodillas al pecho en vez de piernas extendidas. Progresión.', null],
    ['Dragon Flag Negative', 'expert', 3, 'core', 'back', 'Bandera dragón negativa. Sostener barra, elevar piernas verticales, bajar lento. Progresión.', 'barras'],
    ['Dragon Flag', 'expert', 4, 'core', 'back', 'Bandera dragón completa. Mantener cuerpo recto horizontal, solo omóplatos en banco. Máxima dificultad.', 'barras'],
    ['Windshield Wiper', 'expert', 3, 'core', 'none', 'Limpiaparabrisas. Colgado o en suelo, piernas arriba, rotar lado a lado. Oblicuos intenso.', null],
    ['Toes to Bar', 'intermediate', 3, 'core', 'none', 'Pies a la barra. Colgado, elevar pies rectos hasta tocar barra. Compresión completa.', 'barras'],
    ['Skinny Cat', 'beginner', 1, 'core', 'back', 'Gato flaco. Cuadrupedia, redondear espalda arriba, arquear abajo. Movilidad + core.', null],
    ['Ab Wheel Rollout', 'intermediate', 3, 'core', 'none', 'Rodillo abdominal. De rodillas, rodar adelante extendiendo, volver. Rueda abdominal.', 'accesorios'],
    ['Russian Twist', 'beginner', 2, 'core', 'none', 'Twist ruso. Sentado, inclinar atrás, rotar torso lado a lado. Oblicuos.', null],
    ['Crunch', 'beginner', 1, 'core', 'none', 'Abdominal clásico. Rodillas flexionadas, elevar hombros del suelo. Recto abdominal.', null],
    ['Bicycle Crunch', 'beginner', 2, 'core', 'none', 'Abdominal bicicleta. Elevar hombros, traer codo a rodilla contralateral alternando.', null],
    ['V-Up', 'intermediate', 2, 'core', 'none', 'Abdominal V. Tumbado, elevar brazos y piernas juntándose en V. Control total.', null],
    ['Pallof Press', 'intermediate', 2, 'core', 'none', 'Prensa Pallof. Resistencia lateral, extender brazos manteniendo torso estable. Anti-rotación.', 'bandas'],
    ['Copenhagen Plank', 'intermediate', 3, 'core', 'adductors', 'Plancha Copenhague. Pierna superior sobre banco, mantener horizontal. Aductores + core.', 'bancos_soportes'],
];

$count = 0;
foreach ($core as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['calisthenics', 'fitness', 'yoga']);
        $conn->executeStatement(
            "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "  ✅ {$ex[0]}\n"; $count++;
    } else {
        echo "  ℹ️ Ya existe: {$ex[0]}\n";
    }
}
echo "Core añadidos: $count\n\n";

echo "=== HIIT / CARDIO ===\n";
$hiit = [
    ['Burpee', 'intermediate', 3, 'full_body', 'core', 'Burpee completo. Desde pie, squat, plank, push up, salto arriba. Ejercicio total.', null],
    ['Half Burpee', 'beginner', 2, 'full_body', 'core', 'Medio burpee. Sin push up, solo squat, plank, salto. Más accesible.', null],
    ['Mountain Climbers', 'beginner', 2, 'full_body', 'core', 'Escaladores rápidos. Cardio intenso + core, alta frecuencia.', null],
    ['Jumping Jack', 'beginner', 1, 'full_body', 'calves', 'Saltos de tijera. Clásico cardio, calentamiento básico.', null],
    ['High Knees', 'beginner', 2, 'full_body', 'core', 'Rodillas altas corriendo. Cardio + flexores, intensidad alta.', null],
    ['Butt Kicks', 'beginner', 1, 'full_body', 'hamstrings', 'Talones a glúteos. Correr sin avanzar, talones tocan glúteos.', null],
    ['Skater Jumps', 'intermediate', 2, 'full_body', 'legs', 'Saltos de patinador. Lateral, cruzando piernas, impulso explosivo.', null],
    ['Ice Skaters', 'intermediate', 2, 'full_body', 'legs', 'Patinadores sobre hielo. Saltos laterales largos, tocar suelo.', null],
    ['Sprint', 'beginner', 3, 'full_body', 'legs', 'Sprint máximo velocidad. Carrera corta máxima intensidad.', null],
    ['Bear Crawl', 'intermediate', 2, 'full_body', 'core', 'Gateo de oso. Cuadrupedia, rodillas flotando, moverse rápido. Coordination.', null],
    ['Crab Walk', 'beginner', 1, 'full_body', 'core', 'Caminata de cangrejo. Sentado, manos y pies en suelo, moverse. Hombros + core.', null],
    ['Lateral Shuffle', 'beginner', 1, 'full_body', 'legs', 'Movimiento lateral rápido. Posición baja, pasos laterales rápidos. Agilidad.', null],
    ['Carioca', 'beginner', 1, 'full_body', 'core', 'Carioca/crossover step. Movimiento lateral cruzando piernas. Coordinación.', null],
    ['Inchworm', 'beginner', 2, 'full_body', 'core', 'Gusano. Desde pie, manos al suelo, caminar a plank, volver. Movilidad + core.', null],
    ['Squat Jump', 'intermediate', 2, 'full_body', 'legs', 'Sentadilla con salto. Pliométrico básico, potencia piernas.', null],
    ['Tuck Jump', 'intermediate', 3, 'full_body', 'core', 'Salto rodillas al pecho. Pliométrico avanzado, explosión total.', null],
    ['Star Jump', 'beginner', 2, 'full_body', 'calves', 'Salto estrella. En el aire abrir brazos y piernas como estrella.', null],
    ['Boxing Shuffle', 'beginner', 1, 'full_body', 'core', 'Shuffle de boxeo. Pies rápidos, peso balanceando. Cardio ligero.', null],
    ['Shadow Boxing', 'beginner', 2, 'full_body', 'core', 'Boxeo sombra. Golpes al aire, movimiento constante. Cardio + coordinación.', null],
    ['Assault Bike Sprint', 'intermediate', 3, 'full_body', 'core', 'Sprint en assault bike. Máxima intensidad, brazos y piernas juntos.', 'maquinas'],
    ['Rowing Machine Sprint', 'intermediate', 2, 'full_body', 'back', 'Remo sprint. 30 segundos máxima intensidad en máquina.', 'maquinas'],
    ['Jump Rope', 'beginner', 2, 'full_body', 'calves', 'Saltar cuerda. Cardio clásico, coordinación pies.', 'cuerda_saltar'],
    ['Double Unders', 'intermediate', 3, 'full_body', 'calves', 'Dobles saltos. Cuerda pasa dos veces por salto. Intensidad alta.', 'cuerda_saltar'],
    ['Battle Ropes', 'intermediate', 3, 'full_body', 'core', 'Cuerdas de batalla. Ondas alternas o simultáneas, cardio intenso.', 'accesorios'],
    ['Kettlebell Swing', 'intermediate', 3, 'full_body', 'glutes', 'Swing ruso. Hip hinge explosivo, cardio + fuerza.', 'kettlebells'],
    ['Medicine Ball Slam', 'intermediate', 3, 'full_body', 'core', 'Slam con balón medicinal. Levantar arriba, estrellar contra suelo. Potencia.', 'balon_medicinal'],
    ['Sled Push', 'intermediate', 3, 'full_body', 'legs', 'Empuje de trineo. Empujar trineo cargado, máxima intensidad.', 'trineo'],
    ['Sled Pull', 'intermediate', 3, 'full_body', 'back', 'Arrastre de trineo. Jalar trineo hacia ti, espalda + piernas.', 'trineo'],
    ['Farmers Walk', 'intermediate', 2, 'full_body', 'core', 'Paseo del granjero. Cargar peso y caminar rápido. Condición física total.', 'mancuernas'],
    ['Stair Run', 'beginner', 2, 'full_body', 'legs', 'Subir escaleras corriendo. Cardio intenso, piernas potencia.', null],
    ['Uphill Sprint', 'intermediate', 3, 'full_body', 'legs', 'Sprint cuesta arriba. Máxima intensidad en pendiente.', null],
];

$count = 0;
foreach ($hiit as $ex) {
    $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
    if ($exists == 0) {
        $disciplines = json_encode(['hiit', 'crossfit', 'cardio']);
        $conn->executeStatement(
            "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
             VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
            [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
        );
        echo "  ✅ {$ex[0]}\n"; $count++;
    } else {
        echo "  ℹ️ Ya existe: {$ex[0]}\n";
    }
}
echo "HIIT añadidos: $count\n\n";

echo "=== RESUMEN FINAL ===\n";
$summary = $conn->fetchAllAssociative("
    SELECT primary_muscle_group, COUNT(*) as count 
    FROM exercises 
    GROUP BY primary_muscle_group
    ORDER BY count DESC
");

foreach ($summary as $s) {
    echo "{$s['primary_muscle_group']}: {$s['count']}\n";
}
