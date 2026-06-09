<?php

declare(strict_types=1);

/**
 * Rellena campos de guía (setup, ejecución, estándares, fallos, seguridad)
 * para los ejercicios que forman parte de benchmarks (WODs).
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Entity\Exercises;
use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__ . '/../../.env');

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

$guidanceData = [
    'Air Squat' => [
        'description' => 'Sentadilla con peso corporal, movimiento fundamental del entrenamiento funcional.',
        'setupInstructions' => 'Pies a anchura de hombros o ligeramente más abiertos. Dedos de los pies ligeramente hacia afuera. Brazos extendidos al frente o cruzados sobre el pecho.',
        'executionInstructions' => "1. Inicia el movimiento llevando las caderas hacia atrás y abajo.\n2. Mantén el pecho erguido y la espalda neutra.\n3. Baja hasta que la cadera pase por debajo de la línea de las rodillas.\n4. Sube extendiendo caderas y rodillas simultáneamente hasta la posición inicial.",
        'movementStandards' => 'La cadera debe descender por debajo de la línea de las rodillas en la parte inferior. Las rodillas deben extenderse completamente arriba.',
        'commonFaults' => ['Levantar los talones del suelo', 'Inclinarse excesivamente hacia delante', 'No bajar hasta la profundidad completa', 'Rodillas hacia adentro (valgo)'],
        'safetyTips' => 'Mantén el peso en los talones. No dejes que las rodillas se desplacen excesivamente hacia adelante. Activa el core durante todo el movimiento.',
    ],
    'Back Extension' => [
        'description' => 'Extensión de la columna lumbar y cadena posterior usando un banco de extensión o máquina.',
        'setupInstructions' => 'Coloca los muslos sobre el pad acolchado, con los tobillos fijados bajo los rodillos. Cuerpo perpendicular al suelo, brazos cruzados sobre el pecho.',
        'executionInstructions' => "1. Flexiona el torso hacia abajo de forma controlada, redondeando ligeramente la espalda.\n2. Extiende el torso hacia arriba hasta que el cuerpo forme una línea recta.\n3. Contrae los glúteos y lumbares en la parte alta.\n4. Baja de forma controlada sin rebotes.",
        'movementStandards' => 'El torso debe formar una línea recta con las piernas en la extensión máxima. El rango completo incluye flexionar por debajo de los 90 grados.',
        'commonFaults' => ['Extender demasiado (hiperextensión lumbar)', 'Usar impulso en lugar de control muscular', 'No bajar lo suficiente'],
        'safetyTips' => 'Evita la hiperextensión excesiva. No uses impulso. Mantén el movimiento controlado en todo momento.',
    ],
    'Bench Press' => [
        'description' => 'Press de banca con barra, ejercicio fundamental de empuje horizontal.',
        'setupInstructions' => 'Acuéstate en el banco con los ojos bajo la barra. Pies firmes en el suelo. Agarre ligeramente más ancho que los hombros. Retrae los omóplatos.',
        'executionInstructions' => "1. Desengancha la barra y mantén los brazos extendidos.\n2. Baja la barra de forma controlada hacia la parte inferior del pecho.\n3. Toca ligeramente el pecho sin rebotar.\n4. Empuja la barra hacia arriba hasta bloquear los codos.",
        'movementStandards' => 'La barra debe tocar el pecho (o quedar muy cerca) en la parte baja. Los codos deben bloquearse en la extensión completa. Las nalgas no deben despegar del banco.',
        'commonFaults' => ['Levantar las nalgas del banco', 'Rebotar la barra en el pecho', 'Codos excesivamente abiertos', 'Arco excesivo de la espalda'],
        'safetyTips' => 'Usa un spotter o racks de seguridad para cargas pesadas. No rebotar la barra. Mantén las nalgas en el banco en todo momento.',
    ],
    'Box Jump' => [
        'description' => 'Salto a caja o banco, desarrolla potencia explosiva de piernas.',
        'setupInstructions' => 'Colócate frente a la caja a una distancia de un paso. Pies a anchura de hombros. Brazos a los lados.',
        'executionInstructions' => "1. Realiza una sentadilla parcial rápida como preparación.\n2. Lanza los brazos hacia arriba simultáneamente.\n3. Salta extendiendo caderas y rodillas explosivamente.\n4. Aterriza suavemente sobre la caja con ambos pies completamente.\n5. Extiende las rodillas y caderas al final (stand completo).\n6. Baja de forma controlada o da un paso atrás.",
        'movementStandards' => 'Ambos pies deben aterrizar completamente sobre la caja. Las caderas y rodillas deben extenderse completamente en la posición final.',
        'commonFaults' => ['Aterrizar solo con las puntas de los pies', 'No extender completamente arriba', 'Usar un impulso excesivo de carrera', 'Bajar saltando hacia atrás'],
        'safetyTips' => 'No bajes saltando hacia atrás (riesgo de lesión de Aquiles). Baja dando un paso o salta hacia delante. Asegúrate de que la caja es estable.',
    ],
    'Burpee' => [
        'description' => 'Ejercicio de cuerpo completo que combina sentadilla, flexión y salto vertical.',
        'setupInstructions' => 'De pie con los pies a anchura de hombros. Brazos a los lados del cuerpo.',
        'executionInstructions' => "1. Baja las manos al suelo frente a los pies.\n2. Salta con los pies hacia atrás a posición de flexión (plancha).\n3. Realiza una flexión con el pecho tocando el suelo.\n4. Salta con los pies hacia delante entre las manos.\n5. Salta verticalmente con las manos sobre la cabeza.\n6. Aterriza suavemente y repite.",
        'movementStandards' => 'El pecho debe tocar el suelo en cada rep. Los codos deben extenderse en la plancha. El salto vertical debe ser con ambos pies juntos y manos por encima de la cabeza.',
        'commonFaults' => ['No tocar el pecho al suelo', 'Saltar los pies solo hasta las manos sin plancha', 'No saltar verticalmente al final', 'Cadera caída en la plancha'],
        'safetyTips' => 'Aterriza suavemente en cada fase. Mantén el core activo durante la plancha. Si fatigas, reduce la velocidad antes de perder la técnica.',
    ],
    'Burpee Pull-up' => [
        'description' => 'Combinación de burpee completo seguido de una dominada al saltar hacia la barra.',
        'setupInstructions' => 'De pie debajo de la barra de dominadas con los pies a anchura de hombros.',
        'executionInstructions' => "1. Realiza un burpee completo (manos al suelo, pies atrás, pecho al suelo, pies adelante).\n2. Desde la posición de pie, salta y agarra la barra.\n3. Realiza una dominada con la barbilla por encima de la barra.\n4. Suelta la barra y aterriza de pie para el siguiente rep.",
        'movementStandards' => 'El pecho debe tocar el suelo en el burpee. La barbilla debe pasar por encima de la barra en la dominada. Ambos movimientos deben completarse en cada rep.',
        'commonFaults' => ['No tocar el pecho al suelo', 'Dominada incompleta (barbilla no pasa)', 'Separar el salto del agarre de la barra'],
        'safetyTips' => 'Asegúrate de que la barra está a la altura correcta para alcanzarla al saltar. Aterriza suavemente después de soltar la barra.',
    ],
    'Clean and Jerk' => [
        'description' => 'Levantamiento olímpico compuesto por un clean (recogida al hombro) y un jerk (empuje overhead).',
        'setupInstructions' => 'Barra en el suelo frente a los pies. Agarre a anchura de hombros o ligeramente más ancho. Espalda plana, pecho arriba, caderas bajas.',
        'executionInstructions' => "CLEAN:\n1. Levanta la barra del suelo con piernas y espalda.\n2. Al llegar a la altura de las caderas, acelera con triple extensión.\n3. Realiza el pull bajo girando los codos alrededor de la barra.\n4. Recibe la barra en front rack con una sentadilla frontal.\n5. Recupera la posición de pie.\n\nJERK:\n6. Realiza un dip controlado con las rodillas.\n7. Empuja explosivamente la barra overhead.\n8. Recibe con los codos bloqueados y pies divididos.\n9. Recupera la posición de pie con los pies juntos.",
        'movementStandards' => 'El clean debe terminar con la barra en front rack y el atleta de pie. El jerk debe terminar con la barra overhead, codos bloqueados, pies juntos.',
        'commonFaults' => ['Recibir el clean con los codos bajos', 'No bloquear los codos en el jerk', 'Dip excesivamente profundo', 'Perder el rack en front rack'],
        'safetyTips' => 'No intentes cargas que no puedas recibir de forma segura. Usa ganchos o cinta en las muñecas si es necesario. Practica primero con PVC o barra vacía.',
    ],
    'Deadlift' => [
        'description' => 'Levantamiento de peso muerto desde el suelo, ejercicio fundamental de tiro.',
        'setupInstructions' => 'Barra sobre el centro del pie. Pies a anchura de cadera o ligeramente más anchos. Agarre a la anchura de las piernas (doble agarre o mixto). Espalda plana, pecho arriba.',
        'executionInstructions' => "1. Mantén la barra cerca de las espinillas al iniciar el tiro.\n2. Empuja el suelo con los pies manteniendo la espalda neutra.\n3. Extiende las rodillas y caderas simultáneamente.\n4. Termina de pie con hombros hacia atrás y caderas completamente extendidas.\n5. Baja la barra de forma controlada siguiendo la misma trayectoria.",
        'movementStandards' => 'La barra debe partir del suelo y terminar con las caderas y rodillas completamente extendidas. Los hombros deben estar ligeramente por detrás de la barra al final.',
        'commonFaults' => ['Redondear la espalda baja', 'Levantar con los brazos', 'Barra alejada de las piernas', 'No extender completamente las caderas'],
        'safetyTips' => 'Nunca redondees la espalda. Mantén la barra cerca del cuerpo en todo momento. No rebotar la barra en el suelo.',
    ],
    'Double Unders' => [
        'description' => 'Salto a la comba donde la cuerda pasa dos veces bajo los pies en un solo salto.',
        'setupInstructions' => 'Comba ajustada a la altura adecuada (tocando las axilas al doblar por la mitad). Pies juntos, codos cerca del cuerpo, muñecas relajadas.',
        'executionInstructions' => "1. Salta con una ligera flexión de tobillos.\n2. Gira la comba rápidamente con las muñecas (no con los brazos).\n3. La cuerda debe pasar dos veces completas bajo los pies antes de aterrizar.\n4. Mantén un ritmo constante y minimiza la altura del salto.",
        'movementStandards' => 'La cuerda debe pasar dos veces completas bajo los pies en un solo salto. Solo se cuentan los saltos donde esto ocurre.',
        'commonFaults' => ['Girar la comba con los brazos en lugar de muñecas', 'Saltar excesivamente alto', 'Codos alejados del cuerpo', 'Pésimo timing (la cuerda golpea los pies)'],
        'safetyTips' => 'Usa calzado adecuado para saltar. Mantén el impacto suave aterrizando sobre las puntas de los pies. Descansa si sientes dolor en la pantorrilla o Aquiles.',
    ],
    'Handstand Push Up' => [
        'description' => 'Flexión en posición de pino contra la pared, movimiento de empuje overhead avanzado.',
        'setupInstructions' => 'Coloca las manos a 15-30 cm de la pared. Ponte en pino contra la pared con la cabeza entre las manos. Brazos extendidos.',
        'executionInstructions' => "1. Baja la cabeza de forma controlada hacia el suelo.\n2. La cabeza debe tocar ligeramente el suelo (o un abmat/cojín).\n3. Empuja con los brazos extendiendo los codos.\n4. Termina con los codos completamente bloqueados y el cuerpo en línea recta.",
        'movementStandards' => 'Los codos deben bloquearse en la extensión completa. La cabeza debe tocar el suelo en cada rep. El cuerpo debe mantenerse en línea recta.',
        'commonFaults' => ['No bajar hasta tocar el suelo', 'No bloquear los codos', 'Arco excesivo de la espalda', 'Alejarse de la pared al empujar'],
        'safetyTips' => 'Usa un abmat o cojín para proteger la cabeza. Asegúrate de que la pared es estable. No realices kipping si no dominas el movimiento estricto.',
    ],
    'Hang Power Clean' => [
        'description' => 'Clean iniciado desde la posición de hang (barra por encima de las rodillas), recibiendo en sentadilla parcial.',
        'setupInstructions' => 'Barra en hang (por encima de las rodillas), espalda plana, pecho arriba, hombros por encima de la barra.',
        'executionInstructions' => "1. Baja la barra suavemente hasta la posición de hang manteniendo la espalda plana.\n2. Desde el hang, acelera con triple extensión (caderas, rodillas, tobillos).\n3. Realiza el pull bajo girando los codos rápidamente.\n4. Recibe la barra en front rack con una sentadilla parcial (power).\n5. Recupera la posición de pie con codos altos.",
        'movementStandards' => 'La barra debe partir desde el hang (por encima de las rodillas). Debe recibirse en front rack con una sentadilla por encima de paralelo.',
        'commonFaults' => ['Recibir en una sentadilla completa en lugar de power', 'Codigos bajos en el rack', 'No extender completamente antes del pull bajo', 'Barra alejada del cuerpo'],
        'safetyTips' => 'Mantén la barra cerca del cuerpo durante todo el movimiento. Asegúrate de tener espacio para soltar la barra si es necesario.',
    ],
    'Jumping Pull Up' => [
        'description' => 'Dominada asistida por un salto desde el suelo, usada como escala o en WODs con alta intensidad.',
        'setupInstructions' => 'Barra a una altura donde puedas alcanzarla de puntillas con los brazos extendidos.',
        'executionInstructions' => "1. Desde de pie, realiza un pequeño salto para impulsarte hacia arriba.\n2. Agarra la barra firmemente.\n3. Usa el impulso del salto para subir la barbilla por encima de la barra.\n4. Baja de forma controlada hasta que los brazos estén completamente extendidos.",
        'movementStandards' => 'La barbilla debe pasar por encima de la barra. Los brazos deben extenderse completamente en la parte baja.',
        'commonFaults' => ['No extender completamente los brazos abajo', 'No subir la barbilla por encima', 'Dejarse caer sin control'],
        'safetyTips' => 'Baja de forma controlada para no lastimar los hombros. No uses un salto excesivo que te haga perder el control del cuerpo.',
    ],
    'Kettlebell Swing' => [
        'description' => 'Balanceo de pesa rusa, movimiento de potencia de cadena posterior.',
        'setupInstructions' => 'Pies a anchura de hombros o ligeramente más anchos. Pesa rusa entre los pies, ligeramente por delante. Espalda plana, pecho arriba.',
        'executionInstructions' => "1. Agacha las caderas y agarra la pesa rusa con ambas manos.\n2. Inicia el balanceo empujando las caderas hacia atrás.\n3. Impulsa las caderas hacia delante explosivamente.\n4. La pesa rusa debe subir impulsada por el impulso de las caderas (no con los brazos).\n5. La pesa debe llegar a la altura del pecho ( Russian swing ) o por encima de la cabeza ( American swing ).\n6. Deja que la pesa rusa baje naturalmente y repite el impulso de cadera.",
        'movementStandards' => 'En Russian swing: la pesa debe llegar a la altura del pecho con los brazos paralelos al suelo. En American swing: la pesa debe pasar por encima de la cabeza con los codos bloqueados. Las caderas y rodillas deben extenderse completamente en la parte alta.',
        'commonFaults' => ['Levantar la pesa con los brazos en lugar de las caderas', 'Cuadrar la espalda en la bajada', 'Flexionar excesivamente las rodillas (hacer una sentadilla)', 'No extender las caderas completamente'],
        'safetyTips' => 'Mantén la espalda plana en todo momento. No levantes la pesa con los brazos. Asegúrate de tener espacio alrededor.',
    ],
    'Knees to Elbows' => [
        'description' => 'Movimiento en barra donde las rodillas se llevan a tocar los codos.',
        'setupInstructions' => 'Agarra la barra con un agarre más ancho que los hombros. Cuelga con los brazos completamente extendidos.',
        'executionInstructions' => "1. Desde la posición de colgado, activa el core.\n2. Eleva las rodillas hacia arriba y adentro.\n3. Toca los codos con las rodillas (o los muslos superiores en versión scaled).\n4. Baja las piernas de forma controlada hasta la extensión completa.\n5. Puedes usar un pequeño kipping para generar momentum.",
        'movementStandards' => 'Las rodillas deben tocar los codos (o los muslos superiores en versión scaled). Los brazos deben extenderse completamente en la posición de colgado.',
        'commonFaults' => ['No tocar los codos', 'No extender completamente los brazos', 'Balanceo excesivo sin control', 'Usar solo impulso sin contraer el core'],
        'safetyTips' => 'No te balancees excesivamente. Controla la bajada para evitar lastimar los hombros. Si no llegas a los codos, haz toes-to-bar o knee raises como escala.',
    ],
    'Muscle Up' => [
        'description' => 'Movimiento compuesto que combina una dominada explosiva con un dip en la parte alta de la barra.',
        'setupInstructions' => 'Agarra la barra con agarre falso (false grip) o estándar, ligeramente más ancho que los hombros. Cuelga con los brazos extendidos.',
        'executionInstructions' => "1. Realiza una dominada explosiva tirando la barra hacia el pecho.\n2. En la parte alta de la dominada, realiza una transición rápida pasando los codos por encima de la barra.\n3. Termina con un dip empujando hasta bloquear los codos sobre la barra.\n4. Baja de forma controlada repitiendo la transición inversa.",
        'movementStandards' => 'Los codos deben pasar por encima de la barra durante la transición. Los codos deben bloquearse en la extensión completa al final. Los brazos deben estar completamente extendidos al inicio.',
        'commonFaults' => ['No realizar la transición (solo dominada alta)', 'No bloquear los codos en el dip', 'Usar excesivo kipping sin control', 'No extender los brazos al inicio'],
        'safetyTips' => 'Asegúrate de tener dominadas y dips estrictos antes de intentar muscle ups. La transición puede lastimar los hombros si se hace incorrectamente. Usa bandas elásticas como progresión.',
    ],
    'Overhead Squat' => [
        'description' => 'Sentadilla realizada con la barra sostenida overhead con los codos bloqueados.',
        'setupInstructions' => 'Barra overhead con agarre ancho, codos bloqueados. Pies a anchura de hombros o ligeramente más anchos. Brazos extendidos y activos.',
        'executionInstructions' => "1. Mantén la barra estable overhead con los codos bloqueados.\n2. Inicia la sentadilla llevando las caderas hacia atrás y abajo.\n3. Baja hasta que la cadera pase por debajo de la línea de las rodillas.\n4. Mantén la barra centrada sobre la cabeza durante todo el movimiento.\n5. Sube extendiendo caderas y rodillas simultáneamente.\n6. Termina con los codos bloqueados y la barra estable.",
        'movementStandards' => 'La barra debe mantenerse overhead durante todo el movimiento. La cadera debe descender por debajo de la línea de las rodillas. Los codos deben estar bloqueados.',
        'commonFaults' => ['Dejar caer la barra hacia adelante', 'No mantener los codos bloqueados', 'No bajar hasta la profundidad completa', 'Pies excesivamente cerrados'],
        'safetyTips' => 'No intentes cargas que no puedas sostener overhead de forma estable. Practica primero con PVC o barra vacía. Si pierdes el equilibrio, suelta la barra hacia adelante.',
    ],
    'Pistol Squat' => [
        'description' => 'Sentadilla a una pierna, movimiento avanzado de fuerza y equilibrio.',
        'setupInstructions' => 'De pie sobre una pierna. La otra pierna extendida al frente. Brazos extendidos al frente para equilibrio.',
        'executionInstructions' => "1. Mantén la pierna libre extendida al frente.\n2. Baja en sentadilla sobre la pierna de apoyo manteniendo el equilibrio.\n3. La cadera debe descender por debajo de la rodilla.\n4. Sube extendiendo la rodilla y cadera de la pierna de apoyo.\n5. Mantén la pierna libre extendida durante todo el movimiento.",
        'movementStandards' => 'La cadera debe descender por debajo de la rodilla. La pierna libre debe permanecer extendida (o al menos sin tocar el suelo). Debes recuperar la posición de pie.',
        'commonFaults' => ['La pierna libre toca el suelo', 'No bajar hasta la profundidad completa', 'Perder el equilibrio y caer', 'Levantar el talón de la pierna de apoyo'],
        'safetyTips' => 'Practica primero con una caja o banco detrás. Usa un punto fijo visual para mantener el equilibrio. No fuerces el rango si no tienes la movilidad suficiente.',
    ],
    'Power Clean' => [
        'description' => 'Clean iniciado desde el suelo, recibiendo la barra en front rack con una sentadilla parcial (power).',
        'setupInstructions' => 'Barra en el suelo. Agarre a anchura de hombros. Espalda plana, pecho arriba, caderas bajas.',
        'executionInstructions' => "1. Levanta la barra del suelo con piernas y espalda.\n2. Al llegar a la altura de las caderas, acelera con triple extensión.\n3. Realiza el pull bajo girando los codos alrededor de la barra.\n4. Recibe la barra en front rack con una sentadilla parcial (por encima de paralelo).\n5. Recupera la posición de pie con codos altos.",
        'movementStandards' => 'La barra debe partir del suelo. Debe recibirse en front rack. La sentadilla de recepción debe ser por encima de paralelo (power).',
        'commonFaults' => ['Recibir en una sentadilla completa (squat clean)', 'Codigos bajos en el rack', 'No extender completamente antes del pull bajo', 'Barra alejada del cuerpo'],
        'safetyTips' => 'Mantén la barra cerca del cuerpo durante todo el movimiento. Practica primero con PVC o barra vacía. Asegúrate de tener espacio para soltar la barra.',
    ],
    'Power Snatch' => [
        'description' => 'Snatch iniciado desde el suelo, recibiendo la barra overhead con una sentadilla parcial.',
        'setupInstructions' => 'Barra en el suelo. Agarre ancho (snatch grip). Espalda plana, pecho arriba, caderas bajas.',
        'executionInstructions' => "1. Levanta la barra del suelo manteniéndola cerca del cuerpo.\n2. Al llegar a la altura de las caderas, acelera con triple extensión.\n3. Tira la barra hacia arriba y atrás.\n4. Baja rápidamente bajo la barra en una sentadilla parcial.\n5. Recibe la barra overhead con los codos bloqueados.\n6. Recupera la posición de pie con la barra estable overhead.",
        'movementStandards' => 'La barra debe partir del suelo. Debe recibirse overhead con los codos bloqueados. La sentadilla de recepción debe ser por encima de paralelo.',
        'commonFaults' => ['Recibir en una sentadilla completa', 'No bloquear los codos', 'Barra adelantada en la recepción', 'Agarre demasiado estrecho'],
        'safetyTips' => 'El snatch requiere buena movilidad de hombros y muñecas. Practica primero con PVC. No intentes cargas que no puedas recibir de forma segura.',
    ],
    'Pull Up' => [
        'description' => 'Dominada estándar, movimiento de tracción vertical fundamental.',
        'setupInstructions' => 'Agarra la barra con las palmas hacia afuera (pronación), a una distancia ligeramente más ancha que los hombros. Cuelga con los brazos completamente extendidos.',
        'executionInstructions' => "1. Desde la posición de colgado, activa los dorsales y los bíceps.\n2. Tira del cuerpo hacia arriba hasta que la barbilla pase por encima de la barra.\n3. Mantén el cuerpo recto sin balanceo excesivo.\n4. Baja de forma controlada hasta la extensión completa de los brazos.",
        'movementStandards' => 'La barbilla debe pasar claramente por encima de la barra. Los brazos deben estar completamente extendidos en la posición de colgado (dead hang) al inicio de cada rep.',
        'commonFaults' => ['No extender completamente los brazos abajo', 'No subir la barbilla por encima', 'Balanceo excesivo (kipping sin control)', 'Codos abiertos'],
        'safetyTips' => 'No te dejes caer desde la parte alta. Controla la bajada para proteger los hombros. Si no puedes hacer dominadas estrictas, usa bandas elásticas o máquina asistida.',
    ],
    'Push Jerk' => [
        'description' => 'Jerk iniciado desde front rack, usando impulso de piernas para empujar la barra overhead.',
        'setupInstructions' => 'Barra en front rack, codos altos. Pies a anchura de hombros.',
        'executionInstructions' => "1. Realiza un dip controlado doblando ligeramente las rodillas.\n2. Impulsa explosivamente extendiendo rodillas y caderas.\n3. Empuja la barra overhead con los brazos.\n4. Recibe la barra con los codos bloqueados y pies divididos (split jerk) o en el mismo sitio (push jerk).\n5. Recupera la posición de pie con la barra estable overhead.",
        'movementStandards' => 'Los codos deben bloquearse en la extensión completa. La barra debe estar directamente sobre la cabeza. Debes recuperar la posición de pie.',
        'commonFaults' => ['Dip excesivamente profundo', 'No bloquear los codos', 'Perder el rack en front rack', 'No recuperar la posición de pie'],
        'safetyTips' => 'El dip debe ser corto y controlado. No lo uses como sentadilla. Mantén el core activo para no perder el rack.',
    ],
    'Push Press' => [
        'description' => 'Press de hombros asistido por impulso de piernas, iniciado desde front rack.',
        'setupInstructions' => 'Barra en front rack, codos altos. Pies a anchura de hombros.',
        'executionInstructions' => "1. Realiza un dip controlado doblando ligeramente las rodillas.\n2. Impulsa explosivamente extendiendo rodillas y caderas.\n3. Aprovecha el impulso para empujar la barra overhead.\n4. Bloquea los codos con la barra directamente sobre la cabeza.\n5. Baja la barra controladamente de vuelta a front rack.",
        'movementStandards' => 'Los codos deben bloquearse en la extensión completa. La barra debe estar directamente sobre la cabeza. No debe haber rebote en el rack.',
        'commonFaults' => ['Dip excesivamente profundo', 'No bloquear los codos', 'Hacer un press puro sin usar las piernas', 'Perder el rack en front rack'],
        'safetyTips' => 'El dip debe ser corto (5-10 cm). No lo conviertas en una sentadilla. Mantén el core activo para proteger la lumbar.',
    ],
    'Push Up' => [
        'description' => 'Flexión de pecho con peso corporal, movimiento de empuje horizontal fundamental.',
        'setupInstructions' => 'Posición de plancha con las manos a la altura del pecho, ligeramente más anchas que los hombros. Cuerpo recto desde la cabeza hasta los talones.',
        'executionInstructions' => "1. Mantén el cuerpo en línea recta.\n2. Baja el pecho al suelo doblando los codos.\n3. Los codos deben formar aproximadamente 45 grados con el cuerpo.\n4. El pecho debe tocar el suelo (o acercarse).\n5. Empuja hasta extender completamente los codos.",
        'movementStandards' => 'El pecho debe tocar el suelo en la parte baja. Los codos deben bloquearse en la extensión completa. El cuerpo debe mantenerse en línea recta sin arquear ni hundir la cadera.',
        'commonFaults' => ['Cadera caída o arqueada', 'No bajar hasta tocar el suelo', 'Codos excesivamente abiertos (90 grados)', 'No bloquear los codos arriba'],
        'safetyTips' => 'Mantén el core activo para evitar que la cadera se caiga. No fuerces el rango si no tienes la fuerza suficiente; escala a rodillas o plancha inclinada.',
    ],
    'Ring Dip' => [
        'description' => 'Dip en anillas gimnásticas, movimiento de empuje vertical en superficie inestable.',
        'setupInstructions' => 'Agarra las anillas y levántate a la posición de soporte con los brazos extendidos. Las anillas deben estar cerca del cuerpo.',
        'executionInstructions' => "1. Mantén los codos cerca del cuerpo.\n2. Baja controladamente doblando los codos.\n3. Los hombros deben bajar ligeramente por debajo de los codos.\n4. Empuja hasta bloquear completamente los codos.\n5. Mantén las anillas estables durante todo el movimiento.",
        'movementStandards' => 'Los codos deben bloquearse en la extensión completa. Los hombros deben bajar por debajo de los codos en la parte baja.',
        'commonFaults' => ['No bloquear los codos', 'No bajar lo suficiente', 'Dejar que las anillas se separen excesivamente', 'Balanceo incontrolado'],
        'safetyTips' => 'Las anillas son inestables; mantén el core muy activo. No bajes más allá de tu rango de movimiento seguro. Si eres principiante, practica primero en paralelas fijas.',
    ],
    'Rope Climb' => [
        'description' => 'Ascenso por cuerda usando fuerza de brazos y coordinación de pies.',
        'setupInstructions' => 'Colócate frente a la cuerda. Agarra la cuerda con ambas manos por encima de la cabeza. La cuerda debe estar firme y segura.',
        'executionInstructions' => "1. Tírate hacia arriba con los brazos.\n2. Eleva las rodillas y pinza la cuerda entre los pies (técnica J-hook o wrap).\n3. Extie nde las piernas para impulsarte hacia arriba.\n4. Sube las manos lo más alto posible.\n5. Repite hasta tocar el objetivo (generalmente una campana o marca).\n6. Baja de forma controlada deslizando las manos.",
        'movementStandards' => 'Debes tocar el objetivo marcado en la parte alta de la cuerda. No está permitido usar solo los brazos sin la técnica de pies (depende de las reglas del WOD).',
        'commonFaults' => ['Usar solo brazos sin técnica de pies', 'Bajar demasiado rápido (quemaduras de cuerda)', 'No tocar el objetivo', 'Pies que se deslizan de la cuerda'],
        'safetyTips' => 'Usa calcetines largos o protectores de espinillas. Baja de forma controlada para evitar quemaduras. Asegúrate de que la cuerda esté bien anclada.',
    ],
    'Rowing Machine' => [
        'description' => 'Remo en máquina de remo (Concept2 u otra), ejercicio cardiovascular de cuerpo completo.',
        'setupInstructions' => 'Siéntate en la máquina con los pies asegurados en las correas. Agarra el mango con ambas manos. Rodillas flexionadas, torso ligeramente inclinado hacia delante.',
        'executionInstructions' => "1. Inicia empujando con las piernas manteniendo los brazos rectos.\n2. Cuando las piernas estén casi extendidas, inclina ligeramente el torso hacia atrás.\n3. Tira del mango hacia el abdomen inferior doblando los codos.\n4. Extiende los brazos primero, luego inclina el torso hacia delante, y finalmente flexiona las rodillas.\n5. El orden es: piernas-espalda-brazos, brazos-espalda-piernas.",
        'movementStandards' => 'El monitor de la máquina registra las calorías o metros. Debes alcanzar la distancia o calorías indicadas en el WOD.',
        'commonFaults' => ['Tirar con los brazos antes de extender las piernas', 'Inclinar excesivamente el torso hacia atrás', 'Flexionar las rodillas antes de extender los brazos', 'Agarrar el mango demasiado alto'],
        'safetyTips' => 'Mantén la espalda neutra durante todo el movimiento. No tires con la espalda; usa las piernas como motor principal. Ajusta el amortiguador (damper) a un nivel adecuado.',
    ],
    'Run' => [
        'description' => 'Carrera a pie, ya sea en el sitio, al aire libre o en cinta.',
        'setupInstructions' => 'De pie con buena postura. Hombros relajados, mirada al frente.',
        'executionInstructions' => "1. Mantén una zancada natural y ritmo constante.\n2. Aterriza sobre la parte media-delantera del pie.\n3. Mantén los brazos relajados con un ángulo de 90 grados.\n4. Respira de forma controlada.\n5. Completa la distancia indicada en el WOD.",
        'movementStandards' => 'Debes completar la distancia indicada (generalmente en metros o millas). En cinta, el monitor debe registrar la distancia.',
        'commonFaults' => ['Aterrizar sobre el talón con excesivo impacto', 'Carrera con los hombros tensos', 'Respiración irregular', 'Acabar demasiado rápido y quemarse'],
        'safetyTips' => 'Calienta antes de correr a ritmo intenso. Usa calzado adecuado. Mantén una hidratación adecuada. No forces el ritmo si sientes dolor.',
    ],
    'Sit-up' => [
        'description' => 'Abdominal clásico en el suelo, movimiento de flexión del tronco.',
        'setupInstructions' => 'Acuéstate boca arriba con las rodillas flexionadas y los pies apoyados en el suelo. Manos detrás de la cabeza o cruzadas sobre el pecho.',
        'executionInstructions' => "1. Desde la posición de decúbito supino, flexiona el tronco hacia arriba.\n2. Los hombros deben pasar la línea vertical de las caderas.\n3. Baja de forma controlada hasta que los omóplatos toquen el suelo.\n4. Mantén los pies en contacto con el suelo durante todo el movimiento.",
        'movementStandards' => 'Los hombros deben pasar la línea de las caderas en la parte alta. Los omóplatos deben tocar el suelo en la parte baja.',
        'commonFaults' => ['No tocar el suelo con los omóplatos', 'Usar impulso de los brazos', 'Levantar los pies del suelo', 'No subir lo suficiente'],
        'safetyTips' => 'No tires de la cabeza con las manos. Mantén el cuello neutro. Si tienes problemas de espalda, considera usar un abmat o hacer crunches.',
    ],
    'Snatch' => [
        'description' => 'Levantamiento olímpico donde la barra pasa del suelo a overhead en un solo movimiento continuo.',
        'setupInstructions' => 'Barra en el suelo. Agarre ancho (snatch grip). Espalda plana, pecho arriba, caderas bajas. Hombros por encima de la barra.',
        'executionInstructions' => "1. Levanta la barra del suelo manteniéndola cerca del cuerpo.\n2. Al llegar a la altura de las caderas, acelera con triple extensión.\n3. Tira la barra hacia arriba y atrás.\n4. Baja rápidamente bajo la barra en una sentadilla profunda.\n5. Recibe la barra overhead con los codos bloqueados.\n6. Recupera la posición de pie con la barra estable overhead.",
        'movementStandards' => 'La barra debe partir del suelo y terminar overhead con los codos bloqueados. Debes recuperar la posición de pie.',
        'commonFaults' => ['No bloquear los codos', 'Barra adelantada en la recepción', 'No bajo lo suficiente en la sentadilla de recepción', 'Agarre demasiado estrecho'],
        'safetyTips' => 'El snatch requiere buena movilidad de hombros, muñecas y tobillos. Practica primero con PVC. No intentes cargas que no puedas recibir de forma segura.',
    ],
    'Squat Clean' => [
        'description' => 'Clean iniciado desde el suelo, recibiendo la barra en front rack con una sentadilla completa.',
        'setupInstructions' => 'Barra en el suelo. Agarre a anchura de hombros. Espalda plana, pecho arriba, caderas bajas.',
        'executionInstructions' => "1. Levanta la barra del suelo con piernas y espalda.\n2. Al llegar a la altura de las caderas, acelera con triple extensión.\n3. Realiza el pull bajo girando los codos alrededor de la barra.\n4. Recibe la barra en front rack con una sentadilla completa.\n5. Recupera la posición de pie con codos altos.",
        'movementStandards' => 'La barra debe partir del suelo. Debe recibirse en front rack. La sentadilla de recepción debe ser completa (cadera por debajo de la rodilla).',
        'commonFaults' => ['Recibir en una sentadilla parcial (power clean)', 'Codigos bajos en el rack', 'No extender completamente antes del pull bajo', 'Barra alejada del cuerpo'],
        'safetyTips' => 'Mantén la barra cerca del cuerpo durante todo el movimiento. Practica primero con PVC o barra vacía. Asegúrate de tener espacio para soltar la barra.',
    ],
    'Sumo Deadlift High Pull' => [
        'description' => 'Levantamiento desde el suelo con posición de sumo seguido de un tirón hacia la barbilla.',
        'setupInstructions' => 'Pies en posición de sumo (muy abiertos, dedos hacia afuera). Barra entre los pies. Agarre estrecho entre las piernas. Espalda plana.',
        'executionInstructions' => "1. Levanta la barra del suelo extendiendo rodillas y caderas.\n2. Cuando la barra pase las rodillas, acelera con triple extensión.\n3. Tira la barra hacia arriba guiándola con los codos altos.\n4. La barra debe llegar a la altura de la barbilla con los codos por encima de la barra.\n5. Baja la barra de forma controlada.",
        'movementStandards' => 'La barra debe partir del suelo. Debe llegar a la altura de la barbilla con los codos por encima.',
        'commonFaults' => ['Usar los brazos antes de extender completamente', 'No subir la barra hasta la barbilla', 'Redondear la espalda', 'Codigos bajos en el tirón'],
        'safetyTips' => 'Mantén la espalda plana en todo momento. No uses excesivo peso; este movimiento es técnico y de alta velocidad. Baja de forma controlada.',
    ],
    'Thruster' => [
        'description' => 'Combinación de sentadilla front y push press en un solo movimiento fluido.',
        'setupInstructions' => 'Barra en front rack, codos altos. Pies a anchura de hombros.',
        'executionInstructions' => "1. Baja en una sentadilla completa manteniendo la barra en front rack.\n2. Sube explosivamente del fondo de la sentadilla.\n3. Aprovecha el impulso de las piernas para empujar la barra overhead.\n4. Bloquea los codos con la barra sobre la cabeza.\n5. Baja la barra controladamente de vuelta a front rack.",
        'movementStandards' => 'La sentadilla debe ser completa (cadera por debajo de la rodilla). Los codos deben bloquearse en la extensión completa overhead.',
        'commonFaults' => ['No bajar hasta la sentadilla completa', 'No bloquear los codos overhead', 'Hacer un press de hombros en lugar de usar las piernas', 'Perder el rack en front rack'],
        'safetyTips' => 'Mantén el core activo para proteger la lumbar. No uses peso que no puedas sostener en front rack. Si fatigas, descansa antes de perder la técnica.',
    ],
    'Walking Lunge' => [
        'description' => 'Zancada caminando, movimiento unilateral de piernas.',
        'setupInstructions' => 'De pie con los pies juntos. Manos en las caderas o sosteniendo mancuernas/kettlebells.',
        'executionInstructions' => "1. Da un paso largo hacia delante con una pierna.\n2. Baja la rodilla trasera hasta casi tocar el suelo.\n3. La rodilla delantera debe formar un ángulo de 90 grados.\n4. Empuja con la pierna delantera para levantarte y llevar la pierna trasera al frente.\n5. Continúa caminando alternando las piernas.",
        'movementStandards' => 'La rodilla trasera debe tocar o casi tocar el suelo. El torso debe mantenerse erguido. Debes completar el número de pasos indicado.',
        'commonFaults' => ['No bajar lo suficiente', 'Inclinarse excesivamente hacia delante', 'Paso demasiado corto', 'Rodilla delantera que se desplaza hacia adentro'],
        'safetyTips' => 'Mantén el torso erguido. No dejes que la rodilla delantera se desplace excesivamente hacia adelante del pie. Usa calzado estable.',
    ],
    'Wall Ball' => [
        'description' => 'Lanzamiento de balón medicinal contra una pared desde una sentadilla.',
        'setupInstructions' => 'De pie frente a la pared a una distancia de un brazo extendido. Sostén la balón medicinal a la altura del pecho. Pies a anchura de hombros.',
        'executionInstructions' => "1. Baja en una sentadilla completa manteniendo la balón en front rack.\n2. Sube explosivamente del fondo.\n3. Aprovecha el impulso de las piernas para lanzar la balón hacia arriba.\n4. La balón debe tocar la pared por encima de la línea objetivo (generalmente 3m hombres / 2.7m mujeres).\n5. Atrapa la balón al bajar y repite el movimiento.",
        'movementStandards' => 'La sentadilla debe ser completa (cadera por debajo de la rodilla). La balón debe tocar la pared por encima de la línea objetivo.',
        'commonFaults' => ['No bajar hasta la sentadilla completa', 'Lanzar la balón sin usar las piernas', 'No tocar la línea objetivo', 'Atrapar la balón con los brazos extendidos'],
        'safetyTips' => 'Mantén la balón cerca del pecho. No la dejes caer sobre ti al atraparla. Asegúrate de que la pared es adecuada para wall balls.',
    ],
];

$updated = 0;
$repository = $entityManager->getRepository(Exercises::class);

foreach ($guidanceData as $name => $data) {
    $exercise = $repository->findOneBy(['name' => $name]);

    if (null === $exercise) {
        echo "⚠️ Ejercicio no encontrado: {$name}\n";
        continue;
    }

    $exercise->setDescription($data['description']);
    $exercise->setSetupInstructions($data['setupInstructions']);
    $exercise->setExecutionInstructions($data['executionInstructions']);
    $exercise->setMovementStandards($data['movementStandards']);
    $exercise->setCommonFaults($data['commonFaults']);
    $exercise->setSafetyTips($data['safetyTips']);

    ++$updated;
    echo "✅ Actualizado: {$name} (ID: {$exercise->getId()})\n";
}

$entityManager->flush();

echo "\n📊 Total actualizados: {$updated} / " . count($guidanceData) . "\n";
