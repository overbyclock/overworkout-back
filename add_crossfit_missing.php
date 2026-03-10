<?php
require_once __DIR__.'/vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

$conn = DriverManager::getConnection([
    'driver'=>'pdo_mysql','host'=>'127.0.0.1','port'=>3306,
    'user'=>'juan','password'=>'1234','dbname'=>'overworkout'
]);

echo "=== AÑADIENDO EJERCICIOS CROSSFIT FALTANTES ===\n\n";

// MOVIMIENTOS OLÍMPICOS
$olympic = [
    ['Power Clean', 'intermediate', 3, 'legs', 'back', 'Power clean. Desde suelo a hombros en un movimiento, sin squat completo. Olimpico CrossFit.', 'barras'],
    ['Hang Clean', 'intermediate', 3, 'legs', 'back', 'Hang clean. Desde muslos, clean a hombros. Segunda parte del movimiento.', 'barras'],
    ['Squat Clean', 'expert', 4, 'legs', 'back', 'Squat clean. Clean con squat profundo. Movimiento completo olímpico.', 'barras'],
    ['Clean Pull', 'beginner', 2, 'legs', 'back', 'Pull de clean. Desde suelo, extension completa sin turnover. Técnica.', 'barras'],
    ['Power Snatch', 'intermediate', 3, 'legs', 'shoulders', 'Power snatch. Desde suelo a overhead sin squat profundo. Velocidad.', 'barras'],
    ['Hang Snatch', 'intermediate', 3, 'legs', 'shoulders', 'Hang snatch. Desde muslos, snatch overhead. Técnica segunda fase.', 'barras'],
    ['Squat Snatch', 'expert', 4, 'legs', 'shoulders', 'Squat snatch. Snatch completo con recepción en squat. Técnica avanzada.', 'barras'],
    ['Snatch Pull', 'beginner', 2, 'legs', 'back', 'Pull de snatch. Extension alta sin turnover. Potencia vertical.', 'barras'],
    ['Push Jerk', 'intermediate', 3, 'shoulders', 'legs', 'Push jerk. Dip y drive, recepción con piernas flexionadas. Velocidad.', 'barras'],
    ['Split Jerk', 'intermediate', 3, 'shoulders', 'legs', 'Split jerk. Jerk con zancada adelante-atrás. Estabilidad.', 'barras'],
    ['Power Jerk', 'intermediate', 3, 'shoulders', 'legs', 'Power jerk. Recepción en cuarto de squat. Alternativa a split.', 'barras'],
    ['Push Press', 'intermediate', 2, 'shoulders', 'legs', 'Push press. Dip y drive, press con impulso piernas. Fuerza.', 'barras'],
    ['Thruster', 'intermediate', 3, 'legs', 'shoulders', 'Thruster. Front squat a push press en un movimiento. CrossFit clásico.', 'barras'],
    ['Cluster', 'expert', 4, 'legs', 'shoulders', 'Cluster. Squat clean a thruster. Movimiento complejo total.', 'barras'],
    ['Muscle Clean', 'beginner', 2, 'legs', 'back', 'Muscle clean. Clean sin rebote, piernas rectas. Técnica básica.', 'barras'],
    ['Muscle Snatch', 'beginner', 2, 'legs', 'shoulders', 'Muscle snatch. Snatch sin rebote ni squat. Técnica básica.', 'barras'],
    ['Overhead Squat', 'intermediate', 3, 'legs', 'shoulders', 'Sentadilla overhead. Con barra overhead, squat profundo. Movilidad.', 'barras'],
    ['Snatch Balance', 'intermediate', 3, 'shoulders', 'legs', 'Balance de snatch. Behind neck, drop bajo barra. Velocidad bajo peso.', 'barras'],
    ['Tall Clean', 'beginner', 2, 'legs', 'back', 'Clean alto. Desde caderas alto, trabajo turnover. Técnica.', 'barras'],
    ['Tall Snatch', 'beginner', 2, 'legs', 'shoulders', 'Snatch alto. Desde caderas alto a overhead. Técnica recepción.', 'barras'],
];

// HOMBROS - EJERCICIOS CROSSFIT
$shoulders = [
    ['Strict Press', 'beginner', 2, 'shoulders', 'triceps', 'Press estricto. Desde hombros, solo brazos, sin piernas. Fuerza pura.', 'barras'],
    ['Shoulder Press', 'beginner', 2, 'shoulders', 'triceps', 'Press de hombros. Pie a ancho de hombros, press overhead.', 'barras'],
    ['Seated Press', 'beginner', 2, 'shoulders', 'triceps', 'Press sentado. Sin impulso piernas, fuerza estricta hombros.', 'barras'],
    ['Z Press', 'intermediate', 3, 'shoulders', 'core', 'Z press. Sentado en suelo, piernas extendidas, press. Core + hombros.', 'barras'],
    ['Dumbbell Press', 'beginner', 2, 'shoulders', 'triceps', 'Press con mancuernas. Alterno o simultáneo, rango completo.', 'mancuernas'],
    ['Arnold Press', 'intermediate', 2, 'shoulders', 'triceps', 'Press Arnold. Palmas hacia ti, girar mientras subes. Rotación.', 'mancuernas'],
    ['Push Press', 'intermediate', 2, 'shoulders', 'legs', 'Push press. Dip y drive, impulso piernas para subir.', 'barras'],
    ['Jerk', 'intermediate', 3, 'shoulders', 'legs', 'Jerk. Dip drive, bajo barra con rebote bajo peso.', 'barras'],
    ['Barbell Push Press', 'intermediate', 2, 'shoulders', 'legs', 'Push press con barra. Explosivo, impulso de cadera.', 'barras'],
    ['Sumo Deadlift High Pull', 'intermediate', 3, 'shoulders', 'back', 'Sumo deadlift high pull. Peso suelo a mentón, amplio rango.', 'barras'],
    ['Upright Row', 'beginner', 2, 'shoulders', 'traps', 'Remo vertical. Barra desde caderas a mentón, codos altos.', 'barras'],
    ['Face Pull', 'beginner', 2, 'shoulders', 'back', 'Face pull. Cuerda a cara, codos altos, retraer escápulas.', 'maquinas'],
    ['Plate Press', 'beginner', 2, 'shoulders', 'core', 'Press con disco. Dos manos en disco, press overhead. Estabilidad.', 'pesos_libres'],
    ['Landmine Press', 'intermediate', 2, 'shoulders', 'core', 'Press landmine. Barra en esquina, press unilateral. Arco natural.', 'barras'],
    ['Pike Handstand Push Up', 'intermediate', 3, 'shoulders', 'triceps', 'HSPU pica. Cadera alta, cabeza al suelo. Progresión.', null],
    ['Kipping Handstand Push Up', 'intermediate', 3, 'shoulders', 'core', 'HSPU kipping. Impulso con cadera, explosivo. CrossFit style.', null],
    ['Wall Walk', 'intermediate', 2, 'shoulders', 'core', 'Caminata pared. Desde plancha, subir pies pared, andar hacia pino.', null],
    ['Strict HSPU', 'intermediate', 3, 'shoulders', 'triceps', 'Handstand push up estricto. Sin kipping, fuerza pura.', null],
    [' deficit HSPU', 'expert', 4, 'shoulders', 'triceps', 'HSPU con déficit. Manos elevadas, mayor rango. Más difícil.', 'bancos_soportes'],
    ['Handstand Hold', 'intermediate', 2, 'shoulders', 'core', 'Hold de pino. Mantener posición invertida. Equilibrio + fuerza.', null],
    ['Wall Ball Shot', 'intermediate', 3, 'legs', 'shoulders', 'Wall ball. Sentadilla lanzando balón a pared. CrossFit clásico.', 'balon_medicinal'],
    ['Wall Ball Overhead', 'intermediate', 3, 'legs', 'shoulders', 'Wall ball overhead. Lanzar por encima, mayor rango.', 'balon_medicinal'],
];

// KETTLEBELL CROSSFIT
$kettlebell = [
    ['Kettlebell Swing Russian', 'beginner', 2, 'hiit', 'glutes', 'Swing ruso. Hasta altura ojos, snap de cadera. Cardio + power.', 'kettlebells'],
    ['Kettlebell Swing American', 'intermediate', 3, 'hiit', 'shoulders', 'Swing americano. Sobre cabeza completo. Mayor rango.', 'kettlebells'],
    ['Kettlebell Clean', 'intermediate', 3, 'hiit', 'back', 'Clean con kettlebell. De suelo a rack, un movimiento.', 'kettlebells'],
    ['Kettlebell Snatch', 'intermediate', 3, 'hiit', 'shoulders', 'Snatch kettlebell. De suelo a overhead, técnica.', 'kettlebells'],
    ['Kettlebell Push Press', 'intermediate', 2, 'hiit', 'shoulders', 'Push press kettlebell. Impulso piernas, press overhead.', 'kettlebells'],
    ['Kettlebell Jerk', 'intermediate', 3, 'hiit', 'shoulders', 'Jerk kettlebell. Doble dip, explosivo.', 'kettlebells'],
    ['Kettlebell Long Cycle', 'expert', 4, 'hiit', 'shoulders', 'Ciclo largo. Clean y jerk continuos, resistencia.', 'kettlebells'],
    ['Kettlebell Figure 8', 'beginner', 2, 'hiit', 'core', 'Ocho con kettlebell. Pasar entre piernas en ocho. Coordinación.', 'kettlebells'],
    ['Kettlebell Halo', 'beginner', 1, 'hiit', 'core', 'Halo. Círculos alrededor de cabeza. Movilidad hombros.', 'kettlebells'],
    ['Kettlebell Around the World', 'beginner', 1, 'hiit', 'core', 'Alrededor del mundo. Círculos alrededor de cuerpo. Control.', 'kettlebells'],
    ['Kettlebell Turkish Get Up', 'intermediate', 3, 'hiit', 'core', 'TGU completo. De suelo a pie con peso overhead. Total.', 'kettlebells'],
    ['Kettlebell Windmill', 'intermediate', 2, 'hiit', 'core', 'Molino. Flexión lateral con peso overhead. Movilidad.', 'kettlebells'],
    ['Kettlebell Goblet Squat', 'beginner', 2, 'legs', 'core', 'Sentadilla goblet. Peso al pecho, squat profundo.', 'kettlebells'],
    ['Kettlebell Lunge', 'intermediate', 2, 'legs', 'core', 'Zancada con kettlebell. Rack o overhead, caminar.', 'kettlebells'],
    ['Kettlebell Step Up', 'intermediate', 2, 'legs', 'core', 'Step up con kettlebell. Unilateral, controlado.', 'kettlebells'],
    ['Kettlebell Romanian Deadlift', 'beginner', 2, 'hamstrings', 'glutes', 'RDL kettlebell. Bisagra cadera, isquios.', 'kettlebells'],
    ['Kettlebell Sumo Deadlift', 'beginner', 2, 'legs', 'back', 'Peso muerto sumo con kettlebell. Piernas anchas.', 'kettlebells'],
    ['Kettlebell Farmers Carry', 'beginner', 2, 'forearms', 'core', 'Farmers carry. Caminar con peso, agarre + core.', 'kettlebells'],
    ['Kettlebell Suitcase Carry', 'beginner', 2, 'core', 'forearms', 'Suitcase carry. Un peso un lado, anti-flexión lateral.', 'kettlebells'],
    ['Kettlebell Waiter Walk', 'intermediate', 2, 'shoulders', 'core', 'Camarero. Peso overhead, caminar. Estabilidad.', 'kettlebells'],
];

// MÁS EJERCICIOS CROSSFIT VARIADOS
$crossfit = [
    ['Box Jump Over', 'intermediate', 3, 'hiit', 'legs', 'Salto sobre caja. Saltar caja lateralmente, aterrizar otro lado. Agilidad.', 'bancos_soportes'],
    ['Burpee Box Jump', 'intermediate', 4, 'hiit', 'legs', 'Burpee a salto caja. Burpee completo, saltar sobre caja. Brutal.', 'bancos_soportes'],
    ['Burpee Pull Up', 'expert', 4, 'hiit', 'back', 'Burpee a dominada. Burpee, saltar a barra, pull up. Total.', 'barras'],
    ['Burpee Over Bar', 'intermediate', 3, 'hiit', 'core', 'Burpee sobre barra. Burpee, saltar sobre barra lateral. Cardio.', 'barras'],
    ['Chest to Bar Pull Up', 'intermediate', 3, 'back', 'biceps', 'Dominada pecho a barra. Tocar barra con pecho, rango completo.', 'barras'],
    ['Butterfly Pull Up', 'intermediate', 3, 'back', 'biceps', 'Dominada mariposa. Kipping circular eficiente, ritmo cardio.', 'barras'],
    ['Kipping Pull Up', 'intermediate', 2, 'back', 'biceps', 'Dominada kipping. Impulso cadera, eficiente cardio.', 'barras'],
    ['Strict Pull Up', 'beginner', 2, 'back', 'biceps', 'Dominada estricta. Sin kipping, fuerza pura.', 'barras'],
    ['Jumping Pull Up', 'beginner', 1, 'back', 'biceps', 'Dominada con salto. Usar impulso para ayudar. Progresión.', 'barras'],
    ['Band Assisted Pull Up', 'beginner', 1, 'back', 'biceps', 'Dominada asistida con banda. Reducir peso efectivo.', 'bandas'],
    ['Toes to Bar Kipping', 'intermediate', 3, 'core', 'hiit', 'T2B kipping. Con impulso, pies a barra. CrossFit.', 'barras'],
    ['Toes to Bar Strict', 'intermediate', 3, 'core', 'hiit', 'T2B estricto. Sin kipping, fuerza abdominal pura.', 'barras'],
    ['Knees to Elbows', 'intermediate', 2, 'core', 'hiit', 'Rodillas a codos. Tocar codos con rodillas colgado.', 'barras'],
    ['GHD Sit Up', 'intermediate', 2, 'core', 'hiit', 'Sit up en GHD. Máquina extensión cadera, rango completo.', 'maquinas'],
    ['GHD Back Extension', 'beginner', 1, 'back', 'glutes', 'Extensión lumbar GHD. Cadena posterior, hiperextensión controlada.', 'maquinas'],
    ['GHD Hip Extension', 'beginner', 1, 'glutes', 'hamstrings', 'Extensión cadera GHD. Isquios y glúteos, sin lumbar.', 'maquinas'],
    ['Rope Climb', 'intermediate', 3, 'back', 'biceps', 'Trepa cuerda. Subir usando brazos y pies técnica.', 'cuerda'],
    ['Rope Climb Legless', 'expert', 4, 'back', 'biceps', 'Trepa cuerda sin piernas. Solo brazos, fuerza extrema.', 'cuerda'],
    ['Air Squat', 'beginner', 1, 'legs', 'core', 'Sentadilla aire. Peso corporal, técnica básica CrossFit.', null],
    ['Front Squat', 'intermediate', 3, 'legs', 'core', 'Sentadilla frontal. Barra en rack, torso vertical. CrossFit.', 'barras'],
    ['Back Squat', 'intermediate', 3, 'legs', 'back', 'Sentadilla trasera. Barra en trapecios, básico fuerza.', 'barras'],
    ['Overhead Squat', 'intermediate', 3, 'legs', 'shoulders', 'Sentadilla overhead. Barra overhead, movilidad total.', 'barras'],
    ['Pause Squat', 'intermediate', 3, 'legs', 'core', 'Sentadilla pausada. Pausa 2-3 seg abajo, sin rebote.', 'barras'],
    ['Tempo Squat', 'intermediate', 3, 'legs', 'core', 'Sentadilla tempo. Control excéntrico y concéntrico.', 'barras'],
    ['Bulgarian Split Squat', 'intermediate', 2, 'legs', 'core', 'Zancada búlgara. Pie trasero elevado, unilateral.', 'bancos_soportes'],
    ['Walking Lunge', 'beginner', 2, 'legs', 'core', 'Zancada caminando. Movimiento funcional continuo.', null],
    ['Overhead Lunge', 'intermediate', 3, 'legs', 'shoulders', 'Zancada overhead. Peso sobre cabeza, estabilidad.', 'mancuernas'],
    ['Front Rack Lunge', 'intermediate', 3, 'legs', 'core', 'Zancada rack. Peso al frente, torso vertical.', 'mancuernas'],
    ['Step Up', 'beginner', 2, 'legs', 'core', 'Step up. Subir banco controlado, fuerza unilateral.', 'bancos_soportes'],
    ['Box Step Over', 'intermediate', 2, 'legs', 'core', 'Step over caja. Subir y bajar otro lado, cardio.', 'bancos_soportes'],
    ['Deadlift', 'beginner', 2, 'back', 'legs', 'Peso muerto. Levantar barra desde suelo, cadena posterior.', 'barras'],
    ['Sumo Deadlift', 'beginner', 2, 'legs', 'back', 'Peso muerto sumo. Piernas anchas, agarre interior.', 'barras'],
    ['Romanian Deadlift', 'beginner', 2, 'hamstrings', 'glutes', 'RDL. Piernas rígidas, flexión cadera, isquios.', 'barras'],
    ['Deficit Deadlift', 'intermediate', 3, 'back', 'legs', 'Peso muerto en déficit. Pie en plataforma, mayor rango.', 'barras'],
    ['Push Up', 'beginner', 1, 'chest', 'triceps', 'Flexión. Básico CrossFit, peso corporal.', null],
    ['Ring Dip', 'intermediate', 3, 'chest', 'triceps', 'Fondos en anillas. Estable, profundidad completa.', 'anillas'],
    ['Bar Dip', 'intermediate', 2, 'chest', 'triceps', 'Fondos en barras. Paralelas, básico empuje.', 'barras'],
    ['Ring Push Up', 'intermediate', 2, 'chest', 'core', 'Flexión en anillas. Inestable, core + pecho.', 'anillas'],
    ['Deficit Push Up', 'intermediate', 2, 'chest', 'triceps', 'Flexión en déficit. Manos elevadas, mayor rango.', 'bancos_soportes'],
    ['Handstand Push Up', 'intermediate', 3, 'shoulders', 'triceps', 'HSPU. Pino contra pared, bajar y subir.', null],
    ['Strict Handstand Push Up', 'intermediate', 3, 'shoulders', 'triceps', 'HSPU estricto. Sin kipping, fuerza.', null],
    ['Wall Ball', 'intermediate', 3, 'legs', 'shoulders', 'Wall ball. Sentadilla lanzando balón.', 'balon_medicinal'],
    ['Ball Slam', 'intermediate', 3, 'core', 'shoulders', 'Slam balón. Levantar alto, estrellar contra suelo.', 'balon_medicinal'],
    ['Sledgehammer Strike', 'intermediate', 3, 'core', 'shoulders', 'Golpes maza. Contra neumático, power rotational.', 'accesorios'],
    ['Assault Bike', 'beginner', 2, 'hiit', 'core', 'Bicicleta assault. Brazos y piernas juntos, cardio brutal.', 'maquinas'],
    ['Rowing Machine', 'beginner', 2, 'back', 'hiit', 'Remo máquina. Técnica piernas-espalda-brazos.', 'maquinas'],
    ['Ski Erg', 'beginner', 2, 'back', 'hiit', 'Ski erg. Simulación esquí de fondo, cardio brazos.', 'maquinas'],
    ['Echo Bike Sprint', 'intermediate', 3, 'hiit', 'legs', 'Sprint echo bike. Máxima intensidad, fan bike.', 'maquinas'],
    ['Double Unders', 'intermediate', 3, 'hiit', 'calves', 'Dobles saltos. Cuerda pasa dos veces por salto. Skill.', 'cuerda_saltar'],
    ['Single Unders', 'beginner', 1, 'hiit', 'calves', 'Saltos simples. Básico cardio con cuerda.', 'cuerda_saltar'],
    ['Triple Unders', 'expert', 4, 'hiit', 'calves', 'Triples saltos. Cuerda tres veces, altura extrema.', 'cuerda_saltar'],
    ['Crossover Single Under', 'intermediate', 2, 'hiit', 'calves', 'Cruzado simple. Cruzar brazos al saltar. Skill.', 'cuerda_saltar'],
    ['Medicine Ball Clean', 'intermediate', 3, 'legs', 'back', 'Clean con balón. De suelo a hombros, objeto raro.', 'balon_medicinal'],
    ['Dumbbell Snatch', 'intermediate', 3, 'hiit', 'shoulders', 'Snatch mancuerna. Unilateral, overhead.', 'mancuernas'],
    ['Dumbbell Clean', 'intermediate', 3, 'hiit', 'back', 'Clean mancuerna. A hombros, unilateral.', 'mancuernas'],
    ['Devil Press', 'intermediate', 4, 'hiit', 'chest', 'Devil press. Burpee + snatch mancuernas. Brutal.', 'mancuernas'],
    ['Man Maker', 'expert', 4, 'hiit', 'chest', 'Man maker. Push up, row, row, clean, press, squat. Todo.', 'mancuernas'],
    ['Synchro Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee sincronizado. En equipo, coordinación.', null],
    ['Bar Facing Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee cara a barra. Paralelo a barra, saltar lateral.', 'barras'],
    ['Bar Over Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee sobre barra. Saltar por encima longitudinal.', 'barras'],
    ['Lateral Burpee Over Bar', 'intermediate', 3, 'hiit', 'core', 'Burpee lateral sobre barra. De lado, saltar perpendicular.', 'barras'],
    ['Pull Up Bar Burpee', 'expert', 4, 'hiit', 'back', 'Burpee a dominada. Burpee, saltar a barra, pull up.', 'barras'],
    ['Chest to Bar Burpee', 'expert', 4, 'hiit', 'back', 'Burpee a C2B. Burpee, salto, chest to bar.', 'barras'],
    ['Muscle Up Burpee', 'expert', 5, 'hiit', 'chest', 'Burpee a muscle up. El más difícil, todo en uno.', 'barras'],
    ['Broad Jump Burpee', 'intermediate', 3, 'hiit', 'legs', 'Burpee salto largo. Hacia adelante, distancia.', null],
    ['Box Jump Burpee', 'intermediate', 4, 'hiit', 'legs', 'Burpee salto caja. Explosión total.', 'bancos_soportes'],
    ['Target Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee con target. Tocar objetivo 6"/12" sobre cabeza.', null],
    ['Jumping Jack Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee jumping jack. Entre burpees, jumping jacks.', null],
    ['Mountain Climber Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee escalador. 4 escaladores + burpee.', null],
    ['Push Up Burpee', 'intermediate', 3, 'hiit', 'chest', 'Burpee con flexión. Push up completo en el burpee.', null],
    ['Tuck Jump Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee tuck jump. Salto rodillas al pecho.', null],
    ['Star Jump Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee estrella. Salto brazos y piernas abiertos.', null],
    ['180 Burpee', 'intermediate', 3, 'hiit', 'core', 'Burpee 180. Media vuelta en el salto.', null],
    ['360 Burpee', 'expert', 4, 'hiit', 'core', 'Burpee 360. Vuelta completa en el aire.', null],
    ['Overhead Walking Lunge', 'intermediate', 3, 'legs', 'shoulders', 'Zancada caminando overhead. Peso sobre cabeza.', 'mancuernas'],
    ['Back Rack Walking Lunge', 'intermediate', 3, 'legs', 'back', 'Zancada caminando espalda. Barra en trapecios.', 'barras'],
    ['Front Rack Walking Lunge', 'intermediate', 3, 'legs', 'core', 'Zancada caminando frente. Barra en rack.', 'barras'],
    ['Farmer Carry', 'beginner', 2, 'forearms', 'core', 'Paseo granjero. Cargar peso caminar.', 'mancuernas'],
    ['Suitcase Carry', 'beginner', 2, 'core', 'forearms', 'Paseo maleta. Peso un lado, anti-lateral.', 'mancuernas'],
    ['Overhead Carry', 'intermediate', 3, 'shoulders', 'core', 'Paseo overhead. Peso sobre cabeza, estabilidad.', 'mancuernas'],
    ['Yoke Carry', 'intermediate', 3, 'legs', 'back', 'Paseo yoke. Carga pesada sobre hombros, caminar.', 'barras'],
    ['Sandbag Clean', 'intermediate', 3, 'back', 'legs', 'Clean con sandbag. Objeto raro a hombros.', 'accesorios'],
    ['Sandbag Carry', 'intermediate', 2, 'back', 'core', 'Paseo sandbag. Sobre hombros o bear hug.', 'accesorios'],
    ['Stone Shoulder', 'expert', 4, 'back', 'legs', 'Piedra a hombro. Strongman, levantar y colocar.', 'accesorios'],
    ['Stone Over Bar', 'expert', 4, 'back', 'legs', 'Piedra sobre barrera. Lanzar piedra por encima.', 'accesorios'],
    ['Tire Flip', 'intermediate', 3, 'legs', 'back', 'Vuelco neumático. Levantar y voltear.', null],
    ['Sled Push', 'intermediate', 3, 'legs', 'core', 'Empuje trineo. Empujar carga pesada.', 'trineo'],
    ['Sled Pull', 'intermediate', 3, 'back', 'legs', 'Arrastre trineo. Jalar hacia ti.', 'trineo'],
    ['Sprint', 'beginner', 3, 'hiit', 'legs', 'Sprint. Carrera máxima velocidad corta.', null],
    ['Shuttle Run', 'intermediate', 3, 'hiit', 'legs', 'Carrera shuttle. Ida y vuelta entre conos.', null],
    ['Suicide Sprint', 'intermediate', 3, 'hiit', 'legs', 'Suicidio sprint. Cada vez más lejos, volver.', null],
    ['Hill Sprint', 'intermediate', 4, 'hiit', 'legs', 'Sprint cuesta arriba. Pendiente, más difícil.', null],
    ['Sprint Relay', 'beginner', 2, 'hiit', 'legs', 'Relevos sprint. Por equipos, coordinación.', null],
    ['400m Run', 'beginner', 2, 'hiit', 'legs', 'Carrera 400m. Una vuelta pista, ritmo.', null],
    ['800m Run', 'intermediate', 3, 'hiit', 'legs', 'Carrera 800m. Dos vueltas, resistencia.', null],
    ['1 Mile Run', 'intermediate', 3, 'hiit', 'legs', 'Milla. Cuatro vueltas, pacing.', null],
    ['5K Run', 'expert', 3, 'hiit', 'legs', '5 kilómetros. Resistencia cardiovascular.', null],
    ['Row 500m', 'beginner', 2, 'hiit', 'back', 'Remo 500m. Sprint corto, alta intensidad.', 'maquinas'],
    ['Row 2K', 'intermediate', 3, 'hiit', 'back', 'Remo 2000m. Resistencia media.', 'maquinas'],
    ['Row 5K', 'expert', 3, 'hiit', 'back', 'Remo 5000m. Resistencia larga.', 'maquinas'],
    ['Assault Bike 10 cal', 'beginner', 2, 'hiit', 'core', 'Assault bike 10 calorías. Sprint corto.', 'maquinas'],
    ['Assault Bike 50 cal', 'intermediate', 3, 'hiit', 'core', 'Assault bike 50 calorías. Moderado.', 'maquinas'],
    ['Assault Bike 100 cal', 'expert', 4, 'hiit', 'core', 'Assault bike 100 calorías. Brutal.', 'maquinas'],
    ['Ski Erg 500m', 'beginner', 2, 'hiit', 'back', 'Ski 500m. Sprint corto.', 'maquinas'],
    ['Ski Erg 2K', 'intermediate', 3, 'hiit', 'back', 'Ski 2000m. Resistencia.', 'maquinas'],
    ['Echo Bike 10 cal', 'beginner', 2, 'hiit', 'legs', 'Echo bike 10 cal. Tormenta.', 'maquinas'],
    ['Echo Bike 50 cal', 'expert', 4, 'hiit', 'legs', 'Echo bike 50 cal. Tortura.', 'maquinas'],
    ['Double Under Practice', 'beginner', 1, 'hiit', 'calves', 'Práctica dobles. Trabajar técnica sin fatiga.', 'cuerda_saltar'],
    ['Handstand Walk Practice', 'beginner', 2, 'shoulders', 'core', 'Práctica caminata pino. Habilidad equilibrio.', null],
    ['Pistol Progression', 'beginner', 2, 'legs', 'core', 'Progresión pistol. Hacia dominada unilateral.', null],
    ['Muscle Up Progression', 'intermediate', 3, 'back', 'chest', 'Progresión muscle up. Transición pull-dip.', 'barras'],
    ['Bar Muscle Up', 'intermediate', 3, 'back', 'chest', 'Muscle up barra. Sobre barra alta, transición.', 'barras'],
    ['Ring Muscle Up', 'expert', 4, 'back', 'chest', 'Muscle up anillas. Más difícil, estable.', 'anillas'],
    ['Strict Muscle Up', 'expert', 4, 'back', 'chest', 'Muscle up estricto. Sin kipping, fuerza.', 'barras'],
    ['Butterfly Pull Up Practice', 'intermediate', 2, 'back', 'core', 'Práctica mariposa. Técnica kipping circular.', 'barras'],
    ['Chest to Bar Practice', 'beginner', 2, 'back', 'biceps', 'Práctica C2B. Alcance y técnica.', 'barras'],
    ['Toes to Bar Practice', 'beginner', 2, 'core', 'hiit', 'Práctica T2B. Kipping y compresión.', 'barras'],
    ['Wall Ball Practice', 'beginner', 1, 'legs', 'shoulders', 'Práctica wall ball. Precisión lanzamiento.', 'balon_medicinal'],
    ['Snatch Practice', 'beginner', 2, 'legs', 'shoulders', 'Práctica snatch. Técnica con PVC/barra ligera.', 'barras'],
    ['Clean Practice', 'beginner', 2, 'legs', 'back', 'Práctica clean. Técnica recepción.', 'barras'],
    ['Jerk Practice', 'beginner', 2, 'shoulders', 'legs', 'Práctica jerk. Dip drive y recepción.', 'barras'],
    ['Thruster Practice', 'beginner', 2, 'legs', 'shoulders', 'Práctica thruster. Transición squat-press.', 'barras'],
];

function insertExercises($conn, $exercises, $label) {
    echo "\n$label:\n";
    $count = 0;
    foreach ($exercises as $ex) {
        $exists = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE name = ?", [$ex[0]]);
        if ($exists == 0) {
            $disciplines = json_encode(['crossfit']);
            $conn->executeStatement(
                "INSERT INTO exercises (name, level, difficulty_rating, primary_muscle_group, secondary_muscle_group, description, media, equipment_id, disciplines) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, (SELECT id FROM equipments WHERE name = ? LIMIT 1), ?)",
                [$ex[0], $ex[1], $ex[2], $ex[3], $ex[4], $ex[5], 'https://www.youtube.com/results?search_query=' . urlencode($ex[0]), $ex[6], $disciplines]
            );
            echo "  ✅ {$ex[0]}\n"; $count++;
        } else {
            echo "  ℹ️ {$ex[0]} ya existe\n";
        }
    }
    return $count;
}

$total = 0;
$total += insertExercises($conn, $olympic, 'MOVIMIENTOS OLÍMPICOS');
$total += insertExercises($conn, $shoulders, 'HOMBROS CROSSFIT');
$total += insertExercises($conn, $kettlebell, 'KETTLEBELL CROSSFIT');
$total += insertExercises($conn, $crossfit, 'EJERCICIOS CROSSFIT VARIADOS');

echo "\n🎉 Total añadidos: $total\n";

// Verificar totales ahora
$totalCf = $conn->fetchOne("SELECT COUNT(*) FROM exercises WHERE JSON_CONTAINS(disciplines, '\"crossfit\"')");
echo "Total CrossFit ahora: $totalCf ejercicios\n";
