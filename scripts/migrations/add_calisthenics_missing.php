<?php

declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver' => 'pdo_mysql', 'host' => '127.0.0.1', 'port' => 3306,
    'user' => 'juan', 'password' => '1234', 'dbname' => 'overworkout',
]);

echo "=== AÑADIENDO EJERCICIOS DE CALISTENIA FALTANTES ===\n\n";

// CORE EXPERTO (faltan)
$coreExpert = [
    ['Human Flag', 'expert', 4, 'core', 'shoulders', 'Bandera humana. Sujetar barra vertical, extender cuerpo horizontal lateralmente. Fuerza extrema core y hombros.', 'barras'],
    ['Human Flag Raise', 'expert', 4, 'core', 'shoulders', 'Elevación a bandera humana. Desde colgado, elevar cuerpo a posición flag. Progresión.', 'barras'],
    ['Front Lever Raise', 'expert', 4, 'core', 'back', 'Elevación a front lever. Desde colgado, elevar cuerpo horizontal brazos extendidos. Explosivo.', 'barras'],
    ['Back Lever Raise', 'expert', 4, 'core', 'back', 'Elevación a back lever. Desde colgado, elevar atrás cuerpo horizontal. Progresión back lever.', 'barras'],
    ['Victorian Cross', 'expert', 5, 'core', 'biceps', 'Cruz victoriana. Anillas, brazos extendidos lateralmente, cuerpo horizontal. Máxima dificultad calistenia.', 'anillas'],
    ['Planche', 'expert', 5, 'core', 'shoulders', 'Plancha. Apoyo en manos, cuerpo horizontal paralelo al suelo. Elite calistenia.', null],
    ['Straddle Planche', 'expert', 4, 'core', 'shoulders', 'Plancha straddle. Piernas abiertas en V, cuerpo horizontal. Progresión plancha.', null],
    ['Tuck Planche', 'intermediate', 3, 'core', 'shoulders', 'Plancha tuck. Rodillas al pecho, cuerpo horizontal. Progresión plancha.', null],
    ['Planche Lean', 'beginner', 2, 'core', 'shoulders', 'Inclinación plancha. Apoyo en manos, inclinar cuerpo adelante sobre manos. Preparación.', null],
    ['Planche Push Up', 'expert', 5, 'core', 'chest', 'Flexión en plancha. Desde plancha, bajar y subir. Extrema dificultad.', null],
    ['L-Sit to Handstand', 'expert', 4, 'core', 'shoulders', 'L-sit a pino. Desde L-sit, elevar a pino. Transición fuerza.', null],
    ['Hollow Body Press', 'expert', 3, 'core', 'shoulders', 'Press cuerpo hueco. Desde hollow body, empujar con brazos hacia arriba. Core intenso.', null],
];

// PECHO - más variedad
$chest = [
    ['Wide Grip Push Up', 'beginner', 1, 'chest', 'shoulders', 'Flexión agarre ancho. Manos más allá de hombros, énfasis en pecho.', null],
    ['Close Grip Push Up', 'beginner', 2, 'chest', 'triceps', 'Flexión agarre cerrado. Manos juntas, más tríceps.', null],
    ['Tempo Push Up', 'intermediate', 2, 'chest', 'core', 'Flexión tempo. 3 segundos abajo, pausa, 3 arriba. Control muscular.', null],
    ['Ring Push Up', 'intermediate', 2, 'chest', 'core', 'Flexión en anillas. Inestable, mayor activación pecho y estabilizadores.', 'anillas'],
    ['Ring Archer Push Up', 'intermediate', 3, 'chest', 'core', 'Flexión arquero en anillas. Un brazo extendido, otro flexiona. Unilateral.', 'anillas'],
    ['Explosive Push Up', 'intermediate', 3, 'chest', 'core', 'Flexión explosiva. Empujar tan fuerte que manos despeguen. Power.', null],
    ['Clapping Push Up', 'intermediate', 3, 'chest', 'core', 'Flexión con aplauso. Plyo push up intermedio, coordinación.', null],
    ['Superman Push Up', 'expert', 4, 'chest', 'core', 'Flexión superman. En el aire extender brazos adelante. Máxima explosión.', null],
    ['Flying Superman Push Up', 'expert', 4, 'chest', 'core', 'Flexión superman volando. Brazos y piernas extendidas en el aire. Elite plyo.', null],
    ['Pseudo Planche Lean Push Up', 'intermediate', 3, 'chest', 'shoulders', 'Flexión con lean pseudo plancha. Manos a altura cadera, codo atrás.', null],
    ['Maltese Push Up', 'expert', 5, 'chest', 'core', 'Flexión maltesa. Manos a altura cadera, cuerpo horizontal bajo. Avanzado.', null],
    ['One Arm Push Up', 'expert', 4, 'chest', 'core', 'Flexión una mano. El clásico, requiere fuerza y estabilidad extrema.', null],
    ['Archer Push Up', 'intermediate', 3, 'chest', 'core', 'Flexión arquero. Un brazo extendido lateral, otro hace trabajo. Progresión one arm.', null],
    ['Typewriter Push Up', 'intermediate', 3, 'chest', 'core', 'Flexión máquina escribir. Arriba moverse lateralmente. Tiempo tensión.', null],
    ['Spartan Push Up', 'intermediate', 3, 'chest', 'core', 'Flexión espartano. Push up, rodilla al codo, alternar. Cardio + pecho.', null],
];

// HOMBROS - más progresiones
$shoulders = [
    ['Pike Push Up', 'beginner', 1, 'shoulders', 'triceps', 'Flexión pica. Cadera alta, cabeza al suelo. Hombros + tríceps.', null],
    ['Elevated Pike Push Up', 'beginner', 2, 'shoulders', 'triceps', 'Flexión pica elevada. Pies en banco, más profundidad.', 'bancos_soportes'],
    ['Feet on Wall Pike Push Up', 'intermediate', 3, 'shoulders', 'triceps', 'Flexión pica pies en pared. Pies altos en pared, casi vertical.', null],
    ['Crow Stand', 'intermediate', 3, 'shoulders', 'core', 'Postura cuervo. Manos en suelo, rodillas en tríceps, equilibrar. Intro handstand.', null],
    ['Frog Stand', 'intermediate', 3, 'shoulders', 'core', 'Postura rana. Como crow pero más estable, preparación.', null],
    ['Headstand Hold', 'beginner', 2, 'shoulders', 'core', 'Parada de cabeza. Cabeza y manos en triángulo, mantener. Balance.', null],
    ['Tripod Headstand', 'beginner', 2, 'shoulders', 'core', 'Parada trípode. Cabeza y dos manos, rodillas en brazos. Estable.', null],
    ['Wall Handstand Hold', 'beginner', 2, 'shoulders', 'core', 'Pino contra pared. Espalda o cara a pared, mantener pino. Base handstand.', null],
    ['Wall Handstand Push Up', 'intermediate', 3, 'shoulders', 'triceps', 'Flexión pino pared. Pino contra pared, bajar y subir. Intenso.', null],
    ['Chest to Wall Handstand', 'intermediate', 3, 'shoulders', 'core', 'Pino pecho a pared. Mejor alineación que espalda a pared.', null],
    ['Freestanding Handstand', 'expert', 4, 'shoulders', 'core', 'Pino libre. Sin pared, equilibrio puro. Elite.', null],
    ['Freestanding Handstand Push Up', 'expert', 5, 'shoulders', 'triceps', 'Flexión pino libre. Máxima dificultad empuje vertical.', null],
    ['Handstand Walk', 'expert', 4, 'shoulders', 'core', 'Caminata pino. Andar sobre manos en pino. Control + equilibrio.', null],
    ['Handstand Press', 'expert', 5, 'shoulders', 'core', 'Prensa a pino. Desde pie o sentado, elevar a pino sin saltar. Fuerza.', null],
    ['Straddle Press Handstand', 'expert', 4, 'shoulders', 'core', 'Prensa straddle a pino. Piernas abiertas, presionar a pino. Gimnasia.', null],
    ['HSPU Negative', 'intermediate', 3, 'shoulders', 'triceps', 'Handstand push up negativo. Bajar lento desde pino. Progresión.', null],
    ['Wall Assisted L-Sit to Handstand', 'expert', 4, 'shoulders', 'core', 'L-sit a pino asistido. Contra pared, transición completa.', null],
    ['Planche Lean Hold', 'intermediate', 2, 'shoulders', 'core', 'Hold inclinación plancha. Inclinar cuerpo sobre manos sin doblar codos.', null],
    ['Pseudo Planche Hold', 'intermediate', 3, 'shoulders', 'core', 'Hold pseudo plancha. Manos a altura cadera, cuerpo recto inclinado.', null],
];

// GEMELOS EXPERTO
$calvesExpert = [
    ['Single Leg Box Jump', 'expert', 3, 'calves', 'legs', 'Salto a caja una pierna. Desde una pierna, saltar a banco. Explosivo.', 'bancos_soportes'],
    ['Depth Jump to Sprint', 'expert', 3, 'calves', 'legs', 'Salto desde altura a sprint. Caer de banco, inmediatamente sprint. Reactividad.', 'bancos_soportes'],
    ['Single Leg Depth Jump', 'expert', 4, 'calves', 'legs', 'Salto desde altura una pierna. Caer de banco una pierna, saltar. Elite plyo.', 'bancos_soportes'],
    ['Pogo Jumps', 'intermediate', 2, 'calves', 'legs', 'Saltos pogo. Saltos rápidos minimizando tiempo en suelo. Elasticidad.', null],
    ['Ankle Hop', 'beginner', 1, 'calves', 'legs', 'Saltos tobillo. Saltos pequeños y rápidos desde tobillos. Calentamiento.', null],
    ['Calf Raise Isometric Hold', 'intermediate', 2, 'calves', 'legs', 'Isométrico elevación gemelos. Mantener en punta máximo tiempo.', null],
    ['Eccentric Calf Raise', 'intermediate', 2, 'calves', 'legs', 'Elevación gemelos excéntrica. Subir dos pies, bajar lento uno.', null],
];

// GLUTEOS EXPERTO
$glutesExpert = [
    ['Single Leg Hip Thrust Hold', 'expert', 3, 'glutes', 'core', 'Isométrico hip thrust una pierna. Mantener arriba máximo tiempo. Intenso.', null],
    ['Weighted Hip Thrust', 'intermediate', 3, 'glutes', 'core', 'Hip thrust con peso. Mancuerna o barra sobre caderas. Progresión.', 'barras'],
    ['B-Stance Hip Thrust', 'intermediate', 2, 'glutes', 'core', 'Hip thrust B-stance. Una pierna adelante apoyo ligero, otra trabajo. Transición.', null],
    ['Frog Pump Hold', 'intermediate', 2, 'glutes', 'core', 'Isométrico frog pump. Mantener arriba en frog pump. Burnout glúteos.', null],
    ['Glute March', 'beginner', 1, 'glutes', 'core', 'Marcha de glúteos. En puente, alternar elevar piernas. Control.', null],
    ['Shoulder Elevated Hip Thrust', 'intermediate', 2, 'glutes', 'core', 'Hip thrust hombros elevados. Espalda en banco, mayor rango.', 'bancos_soportes'],
    ['Feet Elevated Hip Thrust', 'intermediate', 2, 'glutes', 'core', 'Hip thrust pies elevados. Pies en banco, mayor flexión cadera.', 'bancos_soportes'],
];

// ADUCTORES EXPERTO
$adductorsExpert = [
    ['Copenhagen Plank Raise', 'expert', 4, 'adductors', 'core', 'Elevación en plancha Copenhague. Desde abajo, elevar cadera a posición plank. Dinámico.', 'bancos_soportes'],
    ['Side Plank Leg Lift', 'intermediate', 2, 'adductors', 'core', 'Plancha lateral con elevación pierna. Elevar pierna superior, trabaja aductor.', null],
    ['Adductor Slide Curl', 'intermediate', 3, 'adductors', 'legs', 'Curl deslizante aductor. Deslizar pierna adentro mientras bajas, control excéntrico.', null],
    ['Standing Copenhagen', 'intermediate', 3, 'adductors', 'core', 'Copenhague de pie. Pierna en banco, bajar y subir cuerpo. Funcional.', 'bancos_soportes'],
    ['Side Lunge Slide', 'beginner', 2, 'adductors', 'legs', 'Sentadilla lateral deslizante. Deslizar pierna lateral, controlar bajada.', null],
];

// Función para insertar
function insertExercises($conn, $exercises, $label)
{
    echo "\n$label:\n";
    $count = 0;
    foreach ($exercises as $ex) {
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM exercises WHERE name = ?', [$ex[0]]);
        if (0 === $exists) {
            $disciplines = json_encode(['calisthenics']);
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
$total += insertExercises($conn, $coreExpert, 'CORE EXPERTO');
$total += insertExercises($conn, $chest, 'PECHO');
$total += insertExercises($conn, $shoulders, 'HOMBROS');
$total += insertExercises($conn, $calvesExpert, 'GEMELOS');
$total += insertExercises($conn, $glutesExpert, 'GLUTEOS');
$total += insertExercises($conn, $adductorsExpert, 'ADUCTORES');

echo "\n🎉 Total añadidos: $total\n";

// Verificar core experto ahora
$coreExpertCount = $conn->fetchOne("
    SELECT COUNT(*) FROM exercises 
    WHERE JSON_CONTAINS(disciplines, '\"calisthenics\"') 
    AND primary_muscle_group = 'core' AND level = 'expert'
");
echo "\nCore experto ahora: $coreExpertCount ejercicios\n";
