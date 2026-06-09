<?php

declare(strict_types=1);

/**
 * Rellena campos de guia para todos los ejercicios que no tengan executionInstructions.
 * Clasifica por familias mediante coincidencia de palabras clave en el nombre.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Entity\Exercises;
use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__ . '/../../.env');

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

function getDefaultGuidance(): array
{
    return [
        'description' => 'Ejercicio de entrenamiento fisico. Enfocate en ejecutar cada repeticion con tecnica correcta y control total del movimiento.',
        'setupInstructions' => 'Asegurate de calentar adecuadamente antes de iniciar el ejercicio. Colocate en una posicion comoda y estable que te permita ejecutar el movimiento con buena tecnica.',
        'executionInstructions' => 'Realiza el movimiento enfocandote en la forma correcta y en la activacion del musculo objetivo. Controla tanto la fase concentrica como la excentrica sin prisas.',
        'movementStandards' => 'Prioriza la calidad sobre la cantidad en cada repeticion. Manten un rango de movimiento completo y una tecnica limpia durante todo el set.',
        'commonFaults' => ['Prisa excesiva que compromete la tecnica', 'Rango de movimiento incompleto', 'No calentar antes de entrenar', 'Usar cargas superiores a la tecnica actual'],
        'safetyTips' => 'Calienta progresivamente antes de cada sesion y escucha a tu cuerpo. Aplica la sobrecarga progresiva de forma gradual para evitar lesiones por exceso de volumen o intensidad.',
    ];
}

function buildFamilies(): array
{
    return [
        [
            'name' => 'Chin Up',
            'keywords' => ['Chin Up', 'chin-up', 'chinup'],
            'guidance' => [
                'description' => 'Dominada con agarre supino, movimiento fundamental de traccion vertical que enfatiza el trabajo de biceps y dorsales.',
                'setupInstructions' => 'Cuelga de la barra con un agarre supino a la anchura de los hombros. Manten los brazos completamente extendidos y el cuerpo alineado sin balancear.',
                'executionInstructions' => "Tira del cuerpo hacia arriba hasta llevar la barbilla por encima de la barra. Desciende de manera controlada hasta estirar completamente los brazos en la posicion inicial.",
                'movementStandards' => 'El rango de movimiento debe ser completo, con los brazos extendidos abajo y la barbilla por encima de la barra arriba. Evita impulsos con las piernas durante el recorrido.',
                'commonFaults' => ['Balanceo o impulso con las piernas', 'Repeticiones parciales sin extension completa', 'No subir la barbilla por encima de la barra', 'Agarre demasiado ancho o estrecho'],
                'safetyTips' => 'Protege la salud de los codos evitando movimientos bruscos. Realiza el descenso de forma controlada y deten el ejercicio si aparece molestia articular.',
            ],
        ],
        [
            'name' => 'L-Sit',
            'keywords' => ['L-Sit', 'V-Sit', 'Manna', 'Tuck Sit'],
            'guidance' => [
                'description' => 'Elemento gimnastico de fuerza isometrica donde el cuerpo se mantiene en forma de L con las piernas paralelas al suelo.',
                'setupInstructions' => 'Coloca las manos en el suelo, paralelas o anillas, directamente bajo los hombros. Manten los brazos bloqueados y los hombros activos antes de levantar las piernas.',
                'executionInstructions' => "Eleva las piernas hasta formar una L con el torso, manteniendolas paralelas al suelo. Manten los brazos extendidos y los hombros lejos de las orejas durante todo el tiempo.",
                'movementStandards' => 'Las piernas deben mantenerse paralelas al suelo y los brazos completamente bloqueados. El torso debe permanecer vertical sin inclinarse hacia atras.',
                'commonFaults' => ['Cadera elevada en pike en lugar de L', 'Rodillas flexionadas en lugar de piernas extendidas', 'Hombros que se encogen hacia las orejas', 'Inclinacion del torso hacia atras'],
                'safetyTips' => 'Acondiciona las munecas progresivamente antes de sesiones largas. Avanza de forma progresiva desde tuck sit hasta L-sit y V-sit completos.',
            ],
        ],
        [
            'name' => 'Hollow',
            'keywords' => ['Hollow Body', 'Hollow Rock', 'Hollow'],
            'guidance' => [
                'description' => 'Posicion isometrica o dinamica de core donde la zona lumbar permanece pegada al suelo formando una media luna con el cuerpo.',
                'setupInstructions' => 'Acuestate boca arriba y presiona la zona lumbar contra el suelo activando el core. Eleva ligeramente los hombros y las piernas manteniendo las rodillas extendidas o ligeramente flexionadas.',
                'executionInstructions' => "Eleva los hombros y las piernas del suelo manteniendo la zona lumbar pegada al suelo. Manten la contraccion abdominal durante todo el tiempo indicado o realiza el balanceo controlado.",
                'movementStandards' => 'La zona lumbar debe permanecer en contacto con el suelo en todo momento. No debe haber arco en la parte baja de la espalda durante la ejecucion.',
                'commonFaults' => ['Arco lumbar que se separa del suelo', 'Apertura excesiva de las costillas (rib flare)', 'Cabeza que se proyecta hacia delante', 'Flexion excesiva de las rodillas sin control'],
                'safetyTips' => 'Progresa gradualmente desde variantes mas sencillas como el hollow tuck. Deten el ejercicio si sientes tension excesiva en la zona lumbar.',
            ],
        ],
        [
            'name' => 'Front Lever',
            'keywords' => ['Front Lever', 'Tuck Front Lever', 'Straddle Front Lever', 'One Leg Front Lever'],
            'guidance' => [
                'description' => 'Elemento gimnastico de traccion donde el cuerpo se mantiene horizontal y recto suspendido de una barra con los brazos extendidos.',
                'setupInstructions' => 'Cuelga de la barra con los brazos extendidos y el cuerpo alineado. Desde el colgado activo, eleva el cuerpo hacia una posicion invertida para iniciar la bajada horizontal.',
                'executionInstructions' => "Manten el cuerpo en linea recta y horizontal respecto al suelo activando la espalda, el core y los hombros. Manten los brazos completamente extendidos durante toda la sujecion.",
                'movementStandards' => 'El cuerpo debe formar una linea recta desde los hombros hasta los pies. Los brazos deben permanecer bloqueados y el torso paralelo al suelo.',
                'commonFaults' => ['Cadera elevada en pike', 'Cadera que cae hacia el suelo', 'Brazos flexionados', 'Hombros que se encogen'],
                'safetyTips' => 'Acondiciona los hombros progresivamente antes de intentar la posicion completa. Progresa desde tuck front lever hasta straddle segun tu capacidad.',
            ],
        ],
        [
            'name' => 'Back Lever',
            'keywords' => ['Back Lever', 'Skin the Cat', 'German Hang'],
            'guidance' => [
                'description' => 'Elemento gimnastico donde el cuerpo se mantiene horizontal y recto en posicion invertida, suspendido de anillas o barra.',
                'setupInstructions' => 'Cuelga de la barra o anillas y eleva las piernas pasando por encima de la cabeza. Desde la posicion invertida, baja el cuerpo controladamente hacia la horizontal.',
                'executionInstructions' => "Manten el cuerpo recto y paralelo al suelo con los brazos extendidos. Activa el core, los gluteos y los hombros para sostener la posicion invertida.",
                'movementStandards' => 'El cuerpo debe permanecer recto y paralelo al suelo. Los brazos deben mantenerse bloqueados y los hombros activos durante toda la sujecion.',
                'commonFaults' => ['Cadera elevada en pike', 'Brazos flexionados', 'Perdida de control en la salida', 'Hombros que se encogen'],
                'safetyTips' => 'Prepara hombros y codos con ejercicios de movilidad y refuerzo. Practica primero skin the cat y german hang como progresiones previas.',
            ],
        ],
        [
            'name' => 'Handstand',
            'keywords' => ['Handstand', 'Handstand Walk', 'Handstand Push Up', 'Wall Walk', 'Wall Handstand', 'Freestanding'],
            'guidance' => [
                'description' => 'Posicion invertida sobre manos que desarrolla equilibrio, estabilidad de hombros y fuerza de core.',
                'setupInstructions' => 'Coloca las manos a la anchura de los hombros sobre una superficie firme. Manten los codos bloqueados y el cuerpo alineado desde las manos hasta los pies.',
                'executionInstructions' => "Ponte de cabeza contra la pared o libre, manteniendo una linea recta con los hombros abiertos. Empuja activamente contra el suelo y ajusta el equilibrio con los dedos y los hombros.",
                'movementStandards' => 'El cuerpo debe formar una linea recta con los hombros abiertos y los codos bloqueados. La cabeza debe permanecer en posicion neutral entre los brazos.',
                'commonFaults' => ['Espalda en arco (banana back)', 'Cabeza que sobresale excesivamente', 'Codos flexionados', 'Hombros cerrados o encogidos'],
                'safetyTips' => 'Practica la tecnica de caida contra la pared antes de intentar posiciones libres. Calienta munecas y hombros antes de entrenar pino.',
            ],
        ],
        [
            'name' => 'Pike',
            'keywords' => ['Pike Push Up', 'Elevated Pike', 'Pike Press'],
            'guidance' => [
                'description' => 'Variante de press vertical en posicion de pike que fortalece hombros y triceps con enfasis en la linea de empuje vertical.',
                'setupInstructions' => 'Colocate en posicion de plancha pero con las caderas elevadas formando una V invertida. Las manos deben estar a la anchura de los hombros y la cabeza entre los brazos.',
                'executionInstructions' => "Baja la cabeza de forma controlada hacia el suelo doblando los codos. Empuja de vuelta extendiendo los brazos manteniendo la cadera alta durante todo el movimiento.",
                'movementStandards' => 'La linea de empuje debe ser lo mas vertical posible, formando una linea recta desde las manos hasta las caderas. Los codos deben bloquearse en la parte superior.',
                'commonFaults' => ['Cadera demasiado baja (se convierte en flexion normal)', 'Codos excesivamente abiertos', 'No bajar la cabeza hasta el suelo', 'Perdida de alineacion de la cadera'],
                'safetyTips' => 'Protege el cuello manteniendo la cabeza entre los brazos y no golpees el suelo al bajar. Controla el descenso en todo momento.',
            ],
        ],
        [
            'name' => 'Ring',
            'keywords' => ['Ring Dip', 'Ring Push Up', 'Ring Row', 'Muscle Up'],
            'guidance' => [
                'description' => 'Ejercicios realizados en anillas gimnasticas que anaden un componente de estabilizacion y control articular.',
                'setupInstructions' => 'Ajusta las anillas a la altura adecuada y agarralas firmemente. Manten los brazos extendidos y el cuerpo estable antes de iniciar el movimiento.',
                'executionInstructions' => "Realiza el ejercicio correspondiente manteniendo las anillas lo mas estables posible. Gira las anillas hacia afuera en las posiciones de soporte para maximizar la activacion muscular.",
                'movementStandards' => 'En las posiciones de soporte, las anillas deben estar giradas hacia afuera con los codos bloqueados. El movimiento debe ser controlado sin balanceos excesivos.',
                'commonFaults' => ['Balanceo incontrolado de las anillas', 'Brazos flexionados en el soporte', 'No girar las anillas hacia afuera', 'Perdida de estabilidad del core'],
                'safetyTips' => 'Asegurate de tener buena fuerza de agarre y usa magnesio si es necesario. Verifica que las anillas esten bien ancladas antes de entrenar.',
            ],
        ],
        [
            'name' => 'Burpee',
            'keywords' => ['Burpee', 'Burpee Pull Up', 'Burpee Box Jump', 'Burpee Over Bar'],
            'guidance' => [
                'description' => 'Ejercicio de cuerpo completo que combina sentadilla, flexion y salto vertical en un movimiento fluido y de alta intensidad.',
                'setupInstructions' => 'Ponte de pie con los pies a la anchura de los hombros y los brazos a los lados. Manten el core activo y la postura erguida antes de iniciar.',
                'executionInstructions' => "Baja las manos al suelo, salta hacia atras a plancha, realiza una flexion con el pecho al suelo, vuelve a saltar los pies hacia delante y remata con un salto vertical con las manos por encima de la cabeza.",
                'movementStandards' => 'El pecho debe tocar el suelo en la fase de flexion. El salto final debe ser con extension completa de caderas y rodillas y las manos por encima de la cabeza.',
                'commonFaults' => ['Omitir la flexion o no tocar el pecho', 'No realizar el salto vertical final', 'Cadera caida en la plancha', 'Perdida de ritmo que compromete la tecnica'],
                'safetyTips' => 'Controla el ritmo y prioriza la tecnica sobre la velocidad cuando fatigas. Aterriza suavemente en cada fase del movimiento para proteger las articulaciones.',
            ],
        ],
        [
            'name' => 'Thruster',
            'keywords' => ['Thruster', 'Wall Ball', 'Ball Slam', 'Med Ball Clean'],
            'guidance' => [
                'description' => 'Combinacion de sentadilla front y push press en un solo movimiento fluido y potente.',
                'setupInstructions' => 'Coloca la barra, balon o mancuernas en posicion de front rack o agarre seguro. Manten los pies a la anchura de los hombros y el core activo.',
                'executionInstructions' => "Realiza una sentadilla completa y al subir aprovecha el impulso para press o lanzar. Termina con una extension completa de caderas, rodillas y brazos.",
                'movementStandards' => 'La cadera debe descender por debajo de la linea de las rodillas en la sentadilla. La extension final debe ser completa y estable sin flexionar articulaciones.',
                'commonFaults' => ['Sentadilla incompleta o por encima de paralelo', 'Bloqueo suave o incompleto arriba', 'Perdida del rack en front rack', 'Usar solo brazos sin impulso de piernas'],
                'safetyTips' => 'Controla el recibo o la recepcion del balon con cuidado. Manten distancia adecuada de la pared en wall balls y asegurate de tener espacio libre.',
            ],
        ],
        [
            'name' => 'Climb',
            'keywords' => ['Climb', 'Rope Climb', 'Pull Up Climb', 'Stair Climb'],
            'guidance' => [
                'description' => 'Ejercicios de ascenso que combinan fuerza de traccion, agarre y coordinacion de pies.',
                'setupInstructions' => 'Agarra la cuerda o los agarres con firmeza y posiciona el cuerpo cerca de la superficie de ascenso. Manten los brazos extendidos y el core activo antes de iniciar.',
                'executionInstructions' => "Tira con los brazos y coordina los pies para ascender de forma eficiente. En cuerdas, utiliza la tecnica de pinza con los pies para descansar los brazos cuando sea posible.",
                'movementStandards' => 'Debes tocar el objetivo marcado en la parte alta. Entre tirones, busca la extension completa de los brazos para maximizar la eficiencia del movimiento.',
                'commonFaults' => ['Ascender solo con brazos sin tecnica de pies', 'No extender los brazos entre tirones', 'Descenso descontrolado o brusco', 'Deslizamiento de los pies o manos'],
                'safetyTips' => 'Controla el descenso para evitar quemaduras de cuerda o caidas. Asegurate de tener suficiente fuerza de agarre y descansa cuando la tecnica se degrade.',
            ],
        ],
        [
            'name' => 'Pull Up',
            'keywords' => ['Pull Up', 'pull-up', 'pullup', 'Archer Pull', 'Typewriter Pull', 'Muscle Up'],
            'guidance' => [
                'description' => 'Dominada estandar con agarre prono, movimiento fundamental de traccion vertical que desarrolla dorsales y biceps.',
                'setupInstructions' => 'Cuelga de la barra con un agarre prono a una distancia ligeramente superior a la anchura de los hombros. Los brazos deben estar completamente extendidos y el cuerpo estable sin balanceo.',
                'executionInstructions' => "Tira del cuerpo hacia arriba hasta que la barbilla pase claramente por encima de la barra. Baja de forma controlada hasta la extension completa de los brazos antes de iniciar la siguiente repeticion.",
                'movementStandards' => 'Parte siempre de un dead hang con los brazos completamente extendidos. La barbilla debe superar la altura de la barra en la parte superior del movimiento.',
                'commonFaults' => ['Kipping o balanceo excesivo sin control', 'Medias repeticiones sin extender los brazos abajo', 'Encogimiento de hombros en la fase final', 'No subir la barbilla por encima de la barra'],
                'safetyTips' => 'Asegurate de tener suficiente movilidad de hombros antes de entrenar. No te dejes caer desde la parte alta y controla el descenso para proteger las articulaciones.',
            ],
        ],
        [
            'name' => 'Clean',
            'keywords' => ['Clean', 'Power Clean', 'Hang Clean', 'Squat Clean', 'Muscle Clean', 'Dumbbell Clean', 'Kettlebell Clean'],
            'guidance' => [
                'description' => 'Levantamiento olimpico donde la barra pasa del suelo a la posicion de front rack en un movimiento explosivo.',
                'setupInstructions' => 'Coloca los pies a la anchura de las caderas y agarra la barra con un agarre ligeramente mas ancho que los hombros. Manten la espalda neutra, el pecho arriba y los hombros por encima de la barra.',
                'executionInstructions' => "Levanta la barra del suelo extendiendo piernas y espalda de forma simultanea. Al llegar a las caderas, acelera con triple extension y recibe la barra en front rack con los codos altos.",
                'movementStandards' => 'La barra debe recibirse en front rack con los codos altos y el peso sobre los talones. El recibo debe ser con todo el pie apoyado y el torso erguido.',
                'commonFaults' => ['Tirar con los brazos antes de tiempo', 'Codigos lentos en el recibo', 'Recibir la barra con los codos bajos', 'Perdida de la curva lumbar'],
                'safetyTips' => 'Comienza con cargas ligeras y domina las posiciones intermedias antes de aumentar el peso. Usa proteccion de munecas si es necesario.',
            ],
        ],
        [
            'name' => 'Snatch',
            'keywords' => ['Snatch', 'Power Snatch', 'Hang Snatch', 'Squat Snatch', 'Muscle Snatch', 'Dumbbell Snatch', 'Kettlebell Snatch'],
            'guidance' => [
                'description' => 'Levantamiento olimpico donde la barra pasa del suelo a overhead en un solo movimiento continuo y explosivo.',
                'setupInstructions' => 'Coloca los pies a la anchura de las caderas con un agarre ancho. La barra debe estar sobre el centro del pie con la espalda neutra y los hombros por encima.',
                'executionInstructions' => "Tira la barra hacia arriba con triple extension y baja rapidamente bajo ella para recibirla overhead con los brazos bloqueados. Recupera la posicion de pie de forma estable.",
                'movementStandards' => 'La barra debe recibirse con los brazos completamente bloqueados y directamente sobre la cabeza. Los pies deben recuperar la posicion inicial tras el recibo.',
                'commonFaults' => ['Presionar la barra en lugar de recibirla bloqueada', 'Saltar hacia adelante para recibir', 'Agarre demasiado estrecho', 'Perdida de estabilidad overhead'],
                'safetyTips' => 'Prioriza la estabilidad overhead antes de aumentar la carga. Practica con PVC o barra vacia hasta dominar la trayectoria de la barra.',
            ],
        ],
        [
            'name' => 'Jerk',
            'keywords' => ['Jerk', 'Push Jerk', 'Split Jerk', 'Power Jerk'],
            'guidance' => [
                'description' => 'Levantamiento olimpico de empuje donde la barra pasa de front rack a overhead mediante impulso de piernas y recepcion.',
                'setupInstructions' => 'Coloca la barra en front rack con los codos altos y el torso erguido. Manten los pies a la anchura de las caderas y el core activado.',
                'executionInstructions' => "Realiza un dip controlado y corto, luego empuja explosivamente la barra hacia arriba. Recibe la barra overhead con los brazos bloqueados y recupera la posicion de pie.",
                'movementStandards' => 'Los brazos deben bloquearse completamente overhead y los pies deben volver a la posicion paralela tras el recibo. El dip debe ser corto y vertical.',
                'commonFaults' => ['Presionar la barra con los brazos en lugar de empujar con piernas', 'Recibo inestable', 'Dip excesivamente profundo', 'No recuperar la posicion de los pies'],
                'safetyTips' => 'Asegurate de tener buena movilidad de hombros y munecas. Practica el rack y el recibo con cargas ligeras antes de progresar.',
            ],
        ],
        [
            'name' => 'Kettlebell',
            'keywords' => ['Kettlebell', 'Turkish Get Up', 'Goblet Squat'],
            'guidance' => [
                'description' => 'Ejercicios con pesa rusa que priorizan la potencia de caderas, el control del core y la resistencia de agarre.',
                'setupInstructions' => 'Coloca la pesa rusa ligeramente por delante de tus pies y adopta una postura de hike (caderas atras, espalda neutra). Agarra firmemente el asa manteniendo los hombros activos.',
                'executionInstructions' => "Genera la fuerza desde la extension de caderas y rodillas, no tirando con los brazos. Manten la espalda neutra y la carga cerca del cuerpo durante todo el movimiento.",
                'movementStandards' => 'La potencia debe provenir principalmente de las caderas en movimientos de balanceo. En presses y rows, controla la carga sin utilizar impulso del torso.',
                'commonFaults' => ['Cuadrar el movimiento de balanceo como una sentadilla', 'Arco excesivo de la espalda baja', 'Levantar con los brazos en lugar de las caderas', 'Perdida del agarre o balanceo incontrolado'],
                'safetyTips' => 'Asegurate de tener espacio libre a tu alrededor antes de balancear. Manten un agarre firme y controla la carga en todo momento para evitar golpes.',
            ],
        ],
        [
            'name' => 'Deadlift',
            'keywords' => ['Deadlift', 'RDL', 'Romanian Deadlift', 'Stiff-Leg', 'Snatch Grip Deadlift', 'Deficit Deadlift', 'Sumo Deadlift', 'Trap Bar'],
            'guidance' => [
                'description' => 'Levantamiento de peso muerto desde el suelo, ejercicio fundamental de tiro que trabaja la cadena posterior completa.',
                'setupInstructions' => 'Coloca la barra sobre el centro del pie y agarra con las manos justo por fuera de las piernas. Manten la espalda neutra, el pecho arriba y los hombros ligeramente por delante de la barra.',
                'executionInstructions' => "Empuja el suelo con los pies mientras extiendes rodillas y caderas de forma simultanea. Manten la barra cerca de las piernas durante todo el recorrido hasta alcanzar la extension completa.",
                'movementStandards' => 'Al finalizar, las caderas y las rodillas deben estar completamente extendidas con los hombros ligeramente retraidos. La espalda debe permanecer neutra desde el inicio hasta el final.',
                'commonFaults' => ['Redondeo de la espalda bajo carga', 'Barra alejada de las piernas durante el tiro', 'No extender completamente caderas al final', 'Levantar con los brazos en lugar de las piernas'],
                'safetyTips' => 'Manten la columna vertebral neutral en todo momento y activa los dorsales para mantener la barra cerca. No uses una carga que comprometa tu tecnica.',
            ],
        ],
        [
            'name' => 'Lunge',
            'keywords' => ['Lunge', 'Split Squat', 'Curtsy Lunge', 'Walking Lunge', 'Reverse Lunge', 'Forward Lunge', 'Lateral Lunge'],
            'guidance' => [
                'description' => 'Ejercicio unilateral de piernas que desarrolla fuerza, equilibrio y estabilidad de cadera y rodilla.',
                'setupInstructions' => 'Adopta una posicion de zancada con una pierna al frente y la otra atras, separadas a una distancia comoda. Manten el torso erguido y las manos donde te resulte mas estable.',
                'executionInstructions' => "Baja la rodilla trasera hacia el suelo de forma controlada. La rodilla delantera debe quedar alineada sobre el tobillo y el torso debe permanecer vertical durante todo el movimiento.",
                'movementStandards' => 'La rodilla delantera debe formar aproximadamente 90 grados y quedar sobre el tobillo. El torso debe mantenerse erguido sin inclinarse excesivamente hacia delante.',
                'commonFaults' => ['Rodilla delantera que se desvia hacia adentro', 'Inclinacion excesiva del torso hacia delante', 'Paso demasiado corto o largo', 'No bajar lo suficiente la rodilla trasera'],
                'safetyTips' => 'Controla el tracking de la rodilla para que siga la direccion del pie delantero. Realiza el movimiento de forma controlada y evita golpes bruscos contra el suelo.',
            ],
        ],
        [
            'name' => 'Squat',
            'keywords' => ['Squat', 'Hack Squat', 'Sissy Squat', 'Cossack', 'Overhead Squat', 'Zercher', 'Box Squat', 'Belt Squat', 'Goblet', 'Sumo Squat', 'Front Squat', 'Back Squat', 'Bulgarian Split Squat'],
            'guidance' => [
                'description' => 'Sentadilla y sus variantes, movimiento fundamental de empuje de piernas que trabaja cuadriceps, gluteos y core.',
                'setupInstructions' => 'Coloca los pies a la anchura de los hombros o ligeramente mas abiertos segun la variante. Situa la carga (barra, mancuerna o peso corporal) de forma segura y manten el pecho erguido.',
                'executionInstructions' => "Lleva las caderas hacia atras y abajo doblando rodillas y caderas simultaneamente. Manten el peso en los talones y el pecho arriba durante todo el descenso y ascenso.",
                'movementStandards' => 'El pliegue de la cadera debe descender por debajo de la linea de las rodillas en la parte inferior. Las rodillas deben seguir la misma direccion que los dedos de los pies.',
                'commonFaults' => ['Rodillas hacia adentro (valgo de rodilla)', 'Levantar los talones del suelo', 'Redondeo de la espalda baja', 'Rebote en la parte inferior de la sentadilla'],
                'safetyTips' => 'Calienta adecuadamente antes de cargar peso y evita rebotar en la parte baja. Respira de forma controlada y deten el ejercicio si sientes dolor articular.',
            ],
        ],
        [
            'name' => 'Dip',
            'keywords' => ['Dip'],
            'guidance' => [
                'description' => 'Ejercicio de empuje vertical en barras paralelas o anillas que fortalece pecho, hombros y triceps.',
                'setupInstructions' => 'Colocate entre las barras o anillas con los brazos completamente bloqueados y el cuerpo vertical. Activa el core para mantener la estabilidad durante todo el ejercicio.',
                'executionInstructions' => "Baja controladamente hasta que los hombros queden ligeramente por debajo de los codos. Empuja hacia arriba hasta extender completamente los brazos sin rebotes en la parte baja.",
                'movementStandards' => 'El angulo de flexion de los codos debe ser de al menos 90 grados en la parte inferior. En la parte superior los codos deben bloquearse completamente manteniendo el control.',
                'commonFaults' => ['Codos excesivamente abiertos hacia los lados', 'Rango de movimiento incompleto', 'Rebote en la parte baja del movimiento', 'Perdida de alineacion del torso'],
                'safetyTips' => 'Ten cuidado con la impingement de hombro y no bajes mas alla de tu rango seguro. Si sientes pinchazos o molestias, reduce la profundidad o usa asistencia.',
            ],
        ],
        [
            'name' => 'Push Up',
            'keywords' => ['Push Up', 'push-up', 'pushup', 'Pseudo Planche Push', 'Plyo Push'],
            'guidance' => [
                'description' => 'Flexion de pecho con peso corporal, movimiento de empuje horizontal fundamental que trabaja pecho, hombros y triceps.',
                'setupInstructions' => 'Coloca las manos a la anchura de los hombros o ligeramente mas abiertas. Manten el cuerpo en una linea recta desde la cabeza hasta los talones, activando el core en todo momento.',
                'executionInstructions' => "Baja el pecho hasta rozar el suelo manteniendo los codos a unos 45 grados del torso. Empuja con fuerza hasta extender completamente los brazos, manteniendo la linea corporal estable durante todo el recorrido.",
                'movementStandards' => 'El pecho debe acercarse al suelo en cada repeticion. Los codos deben bloquearse en la parte superior y el core debe permanecer activo sin que la cadera se hunda o arquee.',
                'commonFaults' => ['Cadera caida o excesivamente arqueada', 'Codos excesivamente abiertos (formando 90 grados)', 'Repeticiones incompletas sin bajar el pecho', 'No bloquear los codos en la extension'],
                'safetyTips' => 'Cuida la salud de las munecas manteniendo una alineacion neutral. Deten el ejercicio si sientes dolor en los hombros y escala a una variante mas sencilla si es necesario.',
            ],
        ],
        [
            'name' => 'Planche',
            'keywords' => ['Planche', 'Tuck Planche', 'Straddle Planche', 'Full Planche', 'Planche Lean', 'Pseudo Planche'],
            'guidance' => [
                'description' => 'Elemento gimnastico de equilibrio y fuerza donde el cuerpo se mantiene paralelo al suelo sostenido por los brazos.',
                'setupInstructions' => 'Coloca las manos en el suelo con los dedos ligeramente hacia afuera e inclinate hacia delante. Manten los brazos bloqueados y los hombros por delante de las manos.',
                'executionInstructions' => "Manten el cuerpo paralelo al suelo activando hombros, core y piernas. Progresa desde planche lean y tuck hasta variantes mas avanzadas segun tu nivel.",
                'movementStandards' => 'El cuerpo debe permanecer paralelo al suelo con los brazos completamente extendidos. La cadera no debe elevarse ni hundirse respecto al torso.',
                'commonFaults' => ['Cadera elevada en pike', 'Brazos flexionados', 'Hombros que se encogen', 'Inclinacion insuficiente hacia adelante'],
                'safetyTips' => 'Acondiciona las munecas y los codos progresivamente. No intentes progresar demasiado rapido; la sobrecarga debe ser gradual y controlada.',
            ],
        ],
        [
            'name' => 'Plank',
            'keywords' => ['Plank', 'High Plank', 'Low Plank', 'Side Plank', 'Body Saw'],
            'guidance' => [
                'description' => 'Ejercicio isometrico de core donde el cuerpo se mantiene recto y rigido apoyado sobre antebrazos o manos.',
                'setupInstructions' => 'Apoya los antebrazos o las manos en el suelo segun la variante, con los codos o manos directamente bajo los hombros. Extiende las piernas hacia atras formando una linea recta con el cuerpo.',
                'executionInstructions' => "Manten la posicion activando el core, los gluteos y las piernas. Respira de forma constante sin dejar que la cadera se hunda o se eleve excesivamente.",
                'movementStandards' => 'La columna debe permanecer neutra con los gluteos contraidos y la cadera alineada con los hombros y los tobillos. El agarre al suelo debe ser firme y activo.',
                'commonFaults' => ['Cadera caida hacia el suelo', 'Cadera elevada excesivamente (pike)', 'Dejar la cabeza caer', 'Aguantar la respiracion'],
                'safetyTips' => 'Respira de forma constante durante la sujecion y deten el ejercicio si sientes dolor lumbar. Progresa gradualmente en tiempo para no sobrecargar la zona media.',
            ],
        ],
        [
            'name' => 'Machine',
            'keywords' => ['Machine', 'Leg Press', 'Hack Squat', 'Smith Machine', 'Leg Curl', 'Leg Extension', 'Chest Press Machine', 'Shoulder Press Machine', 'Lat Pulldown', 'Seated Row Machine', 'Pec Deck', 'Hip Abductor', 'Hip Adductor', 'Calf Raise Machine', 'Back Extension Machine'],
            'guidance' => [
                'description' => 'Ejercicios en maquinas de musculacion que guian el movimiento a traves de una trayectoria predeterminada.',
                'setupInstructions' => 'Ajusta el asiento, los respaldos y los pasadores de peso segun tu anatomia. Verifica que la articulacion a trabajar este alineada con el eje de rotacion de la maquina.',
                'executionInstructions' => "Sigue la trayectoria de la maquina de forma controlada manteniendo el contacto con los cojines. Completa el rango de movimiento sin rebotes en los cambios de direccion.",
                'movementStandards' => 'El rango de movimiento debe ser completo desde la posicion de estiramiento hasta la contraccion maxima. Manten el torso estable siguiendo las guias de la maquina.',
                'commonFaults' => ['Repeticiones parciales sin rango completo', 'Peso excesivo que compromete la tecnica', 'Despegar la espalda del respaldo', 'No ajustar la maquina a la anatomia personal'],
                'safetyTips' => 'Verifica siempre que los pasadores de peso esten bien colocados antes de iniciar. No fuerces articulaciones fuera de la trayectoria mecanica de la maquina.',
            ],
        ],
        [
            'name' => 'Cable',
            'keywords' => ['Cable', 'Cable Fly', 'Cable Crossover', 'Cable Curl', 'Cable Row', 'Cable Pullover', 'Cable Lateral Raise', 'Cable Crunch', 'Woodchopper'],
            'guidance' => [
                'description' => 'Ejercicios en poleas que mantienen tension constante sobre el musculo a lo largo de todo el recorrido.',
                'setupInstructions' => 'Ajusta la polea a la altura adecuada segun el ejercicio y selecciona el accesorio correcto. Colocate a una distancia que mantenga tension constante desde el inicio.',
                'executionInstructions' => "Realiza el movimiento controlando la carga en todo momento. Manten la tension constante en el musculo objetivo sin dejar que la pila de peso golpee entre repeticiones.",
                'movementStandards' => 'La tension debe mantenerse sobre el musculo durante todo el recorrido. El movimiento debe ser fluido y controlado sin momentum excesivo.',
                'commonFaults' => ['Usar momentum o balanceo del torso', 'Mala postura durante la ejecucion', 'Perdida de tension en los extremos', 'No controlar la fase excentrica'],
                'safetyTips' => 'Asegurate de que el pasador de la pila de peso este bien colocado. No uses cargas que te obliguen a comprometer la postura o la alineacion articular.',
            ],
        ],
        [
            'name' => 'Band',
            'keywords' => ['Band', 'Resistance Band', 'Band Pull Apart', 'Band Dislocate', 'Band Face Pull', 'Band Row', 'Band Push Up', 'Band Pull-Through', 'Monster Walk', 'Clamshell'],
            'guidance' => [
                'description' => 'Ejercicios con bandas elasticas que proporcionan resistencia variable y tension constante a lo largo del movimiento.',
                'setupInstructions' => 'Ancla la banda de forma segura o pisa el centro de ella segun el ejercicio. Agarra la banda con las manos en la posicion adecuada y manten la tension inicial.',
                'executionInstructions' => "Realiza el movimiento manteniendo tension constante en la banda durante todo el recorrido. Controla tanto la fase concentrica como la excentrica sin dejar que la banda te arrastre.",
                'movementStandards' => 'La tension debe mantenerse en todo momento, evitando que la banda quede floja en los puntos de cambio. El movimiento debe ser controlado y sin tirones bruscos.',
                'commonFaults' => ['Perdida de tension en los extremos del movimiento', 'Tirones bruscos o movimientos violentos', 'Anclaje inseguro de la banda', 'No controlar la vuelta excentrica'],
                'safetyTips' => 'Inspecciona la banda antes de usarla para detectar grietas o desgaste. No te pongas en la linea de tiro de una banda anclada por si se rompe.',
            ],
        ],
        [
            'name' => 'Core/Misc',
            'keywords' => ['Crunch', 'Sit Up', 'V-Up', 'Toes to Bar', 'Knee Raise', 'GHD', 'Back Extension', 'Glute Ham Raise', 'Superman', 'Dead Bug', 'Bird Dog', 'Pallof Press', 'Windmill', 'Turkish'],
            'guidance' => [
                'description' => 'Ejercicios de core y movimientos de accesorio que fortalecen la zona media y mejoran la estabilidad del tronco.',
                'setupInstructions' => 'Colocate en la posicion inicial segun el ejercicio: decubito supino, colgado o en el suelo boca abajo. Alinea la columna de forma neutra antes de iniciar el movimiento.',
                'executionInstructions' => "Activa la musculatura del core llevando el torso o las piernas hacia la posicion de contraccion. Realiza el movimiento de forma controlada sin utilizar impulso o momentum.",
                'movementStandards' => 'El movimiento debe ser controlado en todas sus fases. Evita compensar con otras partes del cuerpo y manten la tension en la zona media durante todo el recorrido.',
                'commonFaults' => ['Tension en el cuello o tirar de la cabeza', 'Usar momentum o impulso con el torso', 'Balanceo en ejercicios de colgado', 'No mantener la zona lumbar en posicion neutra'],
                'safetyTips' => 'Cuida la salud de la zona lumbar evitando arquear o redondear excesivamente. Si sientes molestias en el cuello, apoya la cabeza o cambia de variante.',
            ],
        ],
        [
            'name' => 'Press',
            'keywords' => ['Press', 'Overhead Press', 'Bench Press', 'Incline Press', 'Decline Press', 'Military Press', 'Push Press', 'Seated Press', 'Arnold Press', 'Landmine Press', 'Viking Press', 'Z Press', 'Floor Press'],
            'guidance' => [
                'description' => 'Ejercicios de empuje horizontal o vertical que desarrollan fuerza de hombros, pecho y triceps.',
                'setupInstructions' => 'Ajusta el agarre a la anchura adecuada y posiciona los omoplatos de forma estable. Manten el core activo y una ligera curva natural en la zona lumbar.',
                'executionInstructions' => "Empuja el peso siguiendo una trayectoria controlada hasta la extension completa de los brazos. Baja de forma controlada sin rebotes en el pecho o los hombros.",
                'movementStandards' => 'Los codos deben bloquearse completamente en la extension final. La barra o mancuernas deben seguir una trayectoria estable sin desviaciones laterales.',
                'commonFaults' => ['Arco excesivo de la espalda baja', 'Codos excesivamente abiertos', 'Rebote en el pecho al bajar', 'No bloquear los codos en la extension'],
                'safetyTips' => 'Cuida la salud de los hombros calentando adecuadamente. Usa un spotter o racks de seguridad en presses con barra pesada.',
            ],
        ],
        [
            'name' => 'Curl',
            'keywords' => ['Curl', 'Bicep Curl', 'Hammer Curl', 'Preacher Curl', 'Incline Curl', 'Spider Curl', 'Drag Curl', 'Zottman Curl'],
            'guidance' => [
                'description' => 'Ejercicios de flexion de codo que aislan el trabajo del biceps y los musculos flexores del antebrazo.',
                'setupInstructions' => 'Manten los codos fijos a los lados del cuerpo y agarra el peso con las palmas hacia arriba o en posicion neutra segun la variante. Manten el torso erguido sin balancear.',
                'executionInstructions' => "Flexiona los codos llevando el peso hacia los hombros de forma controlada. Baja extendiendo completamente los brazos sin dejar caer el peso.",
                'movementStandards' => 'Los codos deben permanecer fijos a los lados del torso sin desplazarse hacia delante. La extension debe ser completa en la parte inferior sin balanceos.',
                'commonFaults' => ['Usar momentum o balancear el torso', 'Encogimiento de hombros', 'No extender completamente los brazos abajo', 'Desplazar los codos hacia delante'],
                'safetyTips' => 'Protege la salud de los codos controlando la fase excentrica. No uses un peso que te obligue a compensar con el impulso del cuerpo.',
            ],
        ],
        [
            'name' => 'Extension',
            'keywords' => ['Extension', 'Tricep Extension', 'Skull Crusher', 'Overhead Extension', 'Cable Extension', 'Leg Extension'],
            'guidance' => [
                'description' => 'Ejercicios de extension de codo o rodilla que aislan el trabajo de triceps o cuadriceps.',
                'setupInstructions' => 'Posiciona el codo o la pierna de forma fija segun la variante (triceps o cuadriceps). Alinea la articulacion de forma neutra antes de iniciar la extension.',
                'executionInstructions' => "Extiende el miembro de forma controlada hasta la contraccion completa. Manten la parte superior del brazo o muslo fija durante todo el recorrido.",
                'movementStandards' => 'La contraccion debe ser completa en la extension sin hiperextender la articulacion. La parte proximal del miembro debe permanecer inmovil.',
                'commonFaults' => ['Mover la parte superior del brazo o muslo', 'Balancear el cuerpo para generar impulso', 'Hiperextender la articulacion', 'No completar la extension'],
                'safetyTips' => 'Manten la alineacion articular correcta y evita bloquear bruscamente la articulacion. Controla el peso en ambas fases del movimiento.',
            ],
        ],
        [
            'name' => 'Raise',
            'keywords' => ['Raise', 'Lateral Raise', 'Front Raise', 'Rear Delt Raise', 'Leg Raise', 'Calf Raise', 'Shrug', 'Face Pull', 'Reverse Fly'],
            'guidance' => [
                'description' => 'Ejercicios de elevacion que aislan deltoides, trapecios, pantorrillas u otros musculos mediante movimientos controlados.',
                'setupInstructions' => 'Colocate en posicion de pie o sentado segun la variante, con el torso erguido. Agarra el peso o maneja el implemento con las articulaciones alineadas.',
                'executionInstructions' => "Eleva el peso hasta la altura objetivo manteniendo un tempo controlado. Baja de forma lenta y controlada sin dejar caer el peso.",
                'movementStandards' => 'El movimiento debe realizarse con un tempo controlado sin utilizar impulso. El rango debe ser completo segun la variante especifica.',
                'commonFaults' => ['Usar momentum o impulso con el torso', 'Peso excesivo que reduce el rango', 'No controlar la fase excentrica', 'Encogimiento de hombros innecesario'],
                'safetyTips' => 'Protege el manguito rotador usando cargas moderadas y rangos controlados. Evita elevar el peso mas alla de lo que puedas controlar con buena tecnica.',
            ],
        ],
        [
            'name' => 'Row',
            'keywords' => ['Row', 'Renegade', 'Inverted Row', 'T-Bar', 'Chest-Supported', 'Bent Over Row', 'Pendlay', 'Seal Row', 'meadows row'],
            'guidance' => [
                'description' => 'Ejercicios de traccion horizontal que desarrollan la espalda media, los dorsales y los musculos retractores de los omoplatos.',
                'setupInstructions' => 'Adopta una posicion con la espalda neutra, ya sea inclinada desde la cadera o sentado en la maquina. Asegura un agarre firme y manten los hombros activos antes de iniciar el tiron.',
                'executionInstructions' => "Tira del peso hacia el torso llevando los codos cerca del cuerpo. Contrae los omoplatos en la parte final del movimiento y controla el regreso a la posicion inicial.",
                'movementStandards' => 'Los omoplatos deben juntarse en la fase de contraccion y el movimiento debe realizarse de forma controlada en todo momento. Evita utilizar impulso o momentum.',
                'commonFaults' => ['Redondeo de la espalda durante el tiron', 'Uso de momentum o impulso con el torso', 'No contraer los omoplatos al final', 'Codos alejados del cuerpo'],
                'safetyTips' => 'Manten la zona lumbar en posicion neutra y evita arquear la espalda. Activa el core como si fueras a recibir un golpe y no uses mas peso del que puedas controlar.',
            ],
        ],
        [
            'name' => 'Rope',
            'keywords' => ['Rope', 'Battle Ropes', 'Double Unders', 'Jump Rope', 'Crossover', 'Skipping'],
            'guidance' => [
                'description' => 'Ejercicios con cuerda que desarrollan resistencia cardiovascular, coordinacion y resistencia de antebrazos y hombros.',
                'setupInstructions' => 'Agarra la cuerda o el salto con las manos en la posicion adecuada y adopta una postura estable. En saltos, ajusta la longitud de la cuerda a tu estatura.',
                'executionInstructions' => "Genera movimientos consistentes ya sea con las munecas para saltos o con brazos y core para battle ropes. Manten un ritmo constante y controlado.",
                'movementStandards' => 'El ritmo debe ser constante y coherente durante el tiempo o repeticiones indicadas. En battle ropes, las olas deben llegar hasta el anclaje.',
                'commonFaults' => ['Movimiento solo con los brazos sin integrar el core', 'Tropezar o perder el ritmo en saltos', 'Olas pequenas o irregulares en battle ropes', 'Munecas rigidas en lugar de relajadas'],
                'safetyTips' => 'Verifica que la superficie sea adecuada para saltar. En battle ropes, asegurate de que el anclaje sea firme y de que nadie este cerca del radio de accion.',
            ],
        ],
        [
            'name' => 'Run',
            'keywords' => ['Run', 'Sprint', 'Jog', 'Interval Run', 'Hill Run', 'Tempo Run'],
            'guidance' => [
                'description' => 'Ejercicios de carrera a pie que mejoran la resistencia cardiovascular, la potencia de piernas y la capacidad aerobica.',
                'setupInstructions' => 'Utiliza calzado adecuado para la superficie donde vas a correr. Manten una postura erguida con los hombros relajados y la mirada al frente.',
                'executionInstructions' => "Aterriza con la parte media-delantera del pie manteniendo una zancada natural. Oscila los brazos a unos 90 grados de flexion y manten un ritmo respiratorio constante.",
                'movementStandards' => 'Manten un ritmo constante y coherente a lo largo de la distancia indicada. La cadencia debe ser natural sin sobreestirar la zancada.',
                'commonFaults' => ['Sobreestirar la zancada (overstriding)', 'Aterrizar excesivamente en el talon', 'Tension excesiva en hombros y brazos', 'Respiracion irregular o agitada'],
                'safetyTips' => 'Incrementa el kilometraje de forma progresiva e incluye dias de descanso. Manten una hidratacion adecuada y calienta antes de correr a alta intensidad.',
            ],
        ],
        [
            'name' => 'Jump',
            'keywords' => ['Jump', 'Box Jump', 'Broad Jump', 'Depth Jump', 'Tuck Jump', 'Jumping Jack', 'Bound', 'Hop', 'Vertical Jump', 'Lateral Jump'],
            'guidance' => [
                'description' => 'Ejercicios plyometricos de salto que desarrollan potencia explosiva, coordinacion y capacidad de amortiguacion.',
                'setupInstructions' => 'Adopta una posicion atletica con los pies a la anchura de los hombros y las rodillas ligeramente flexionadas. Prepara los brazos para impulsar el movimiento hacia arriba.',
                'executionInstructions' => "Explota extendiendo caderas, rodillas y tobillos de forma simultanea. Aterriza suavemente absorbiendo el impacto con una ligera flexion de rodillas y caderas.",
                'movementStandards' => 'El aterrizaje debe ser suave y silencioso, con los pies completamente apoyados. Controla la amortiguacion en cada repeticion para proteger las articulaciones.',
                'commonFaults' => ['Aterrizaje en valgo (rodillas hacia adentro)', 'Aterrizajes ruidosos o bruscos', 'No extender completamente en el salto', 'Perdida de equilibrio al caer'],
                'safetyTips' => 'Verifica que la superficie sea segura y estable antes de saltar. Selecciona una altura de caja apropiada a tu nivel y nunca saltes hacia atras desde una caja.',
            ],
        ],
        [
            'name' => 'Bike',
            'keywords' => ['Bike', 'Air Bike', 'Assault Bike', 'Spin', 'Cycling', 'Stationary Bike'],
            'guidance' => [
                'description' => 'Ejercicios en bicicleta estatica que mejoran la resistencia cardiovascular y la potencia de piernas mediante pedaleo continuo.',
                'setupInstructions' => 'Ajusta la altura del asiento para que la pierna tenga una flexion suave cuando el pedal esta abajo. Manten una postura erguida con los hombros relajados y las manos ligeras en el manillar.',
                'executionInstructions' => "Pedalea empujando y tirando del pedal de forma circular y constante. Manten una cadencia regular ajustando la resistencia segun el objetivo del entrenamiento.",
                'movementStandards' => 'La cadencia debe ser consistente y mantenerse dentro del rango objetivo. La postura no debe degradarse con el paso del tiempo ni la fatiga.',
                'commonFaults' => ['Rebotar excesivamente sobre el sillin', 'Postura encorvada o hombros caidos', 'Pedaleo solo empujando sin tirar', 'Cadencia irregular o excesivamente baja'],
                'safetyTips' => 'Calienta progresivamente antes de aumentar la resistencia o cadencia. Manten una hidratacion adecuada y ajusta el asiento correctamente para evitar molestias.',
            ],
        ],
        [
            'name' => 'Bear Crawl',
            'keywords' => ['Bear Crawl', 'Crab Walk', 'Inchworm', 'Caterpillar'],
            'guidance' => [
                'description' => 'Ejercicios de locomocion cuadrupeda que desarrollan coordinacion, estabilidad de core y resistencia de hombros.',
                'setupInstructions' => 'Colocate en posicion cuadrupeda con las manos bajo los hombros y las rodillas ligeramente flexionadas y flotando. Manten la espalda neutra y la mirada al suelo.',
                'executionInstructions' => "Avanza moviendo el brazo opuesto a la pierna de forma coordinada. Manten las caderas estables y bajas, sin dejar que se balanceen excesivamente de lado a lado.",
                'movementStandards' => 'Las caderas deben mantenerse al mismo nivel que los hombros sin elevarse excesivamente. El movimiento debe ser contralateral (brazo opuesto a pierna).',
                'commonFaults' => ['Cadera elevada excesivamente en pike', 'Movimiento ipsilateral (mismo lado)', 'Cadera que se balancea de lado a lado', 'Rodillas demasiado elevadas del suelo'],
                'safetyTips' => 'Prepara las munecas con movilidad previa y elige una superficie sin obstaculos. Manten el ritmo controlado para evitar resbalones.',
            ],
        ],
        [
            'name' => 'Carry',
            'keywords' => ['Carry', 'Farmers Walk', 'Suitcase Carry', 'Overhead Carry', 'Yoke Carry', 'Sandbag Carry', 'Bear Hug Carry'],
            'guidance' => [
                'description' => 'Ejercicios de transporte de carga que fortalecen el agarre, el core y la resistencia postural.',
                'setupInstructions' => 'Agarra el implemento (mancuernas, kettlebells, yugo, etc.) con un agarre firme y seguro. Endereza el torso, retrae los omoplatos y activa el core antes de dar el primer paso.',
                'executionInstructions' => "Camina manteniendo una postura erguida y controlada durante todo el recorrido. Evita inclinarte hacia los lados o dejar caer los hombros con la fatiga.",
                'movementStandards' => 'El torso debe permanecer vertical y estable. El paso debe ser firme y constante sin prisas que comprometan la alineacion de la columna.',
                'commonFaults' => ['Inclinarse hacia el lado de la carga', 'Dejar caer los hombros o la carga', 'Pasos inestables o descontrolados', 'Agarrar el peso con una sola mano sin estabilidad'],
                'safetyTips' => 'Respeta los limites de tu agarre y suelta la carga de forma segura si lo necesitas. No corras con cargas que comprometan tu equilibrio o vision periferica.',
            ],
        ],
        [
            'name' => 'Yoga/Mobility',
            'keywords' => ['Yoga', 'Stretch', 'Foam Roll', 'Mobility', 'Pigeon', 'Downward Dog', "Child's Pose", 'Warrior', 'Sun Salutation', 'Pilates', 'Flow'],
            'guidance' => [
                'description' => 'Ejercicios de movilidad, flexibilidad y consciencia corporal que mejoran el rango de movimiento y la recuperacion.',
                'setupInstructions' => 'Utiliza una esterilla y busca un espacio comodo y sin distracciones. Colocate en la posicion inicial relajando los hombros y alineando las articulaciones.',
                'executionInstructions' => "Manten la posicion estatica o fluye entre posturas de forma consciente y controlada. Respira profundamente y de forma constante durante todo el ejercicio.",
                'movementStandards' => 'La respiracion debe ser profunda y constante. Siente el estiramiento en el musculo, no en la articulacion, y evita forzar rangos que generen dolor.',
                'commonFaults' => ['Forzar el rango de movimiento mas alla de lo seguro', 'Aguantar la respiracion', 'Tension excesiva en hombros o mandibula', 'No respetar las senales de dolor articular'],
                'safetyTips' => 'Nunca fuerces un estiramiento hasta sentir dolor en las articulaciones. Respira profundamente y sale de la postura de forma controlada si sientes pinchazos o molestias.',
            ],
        ],
    ];
}

function matchFamily(string $exerciseName, array $families): ?array
{
    $lower = strtolower($exerciseName);
    foreach ($families as $family) {
        foreach ($family['keywords'] as $keyword) {
            if (str_contains($lower, strtolower($keyword))) {
                return ['name' => $family['name'], 'guidance' => $family['guidance']];
            }
        }
    }
    return null;
}

// --- Main execution ---

$families = buildFamilies();

$repository = $entityManager->getRepository(Exercises::class);
$exercises = $repository->createQueryBuilder('e')
    ->where('e.executionInstructions IS NULL')
    ->orderBy('e.id', 'ASC')
    ->getQuery()
    ->getResult();

$totalToProcess = count($exercises);
echo "Ejercicios sin contenido de guia: {$totalToProcess}\n";
echo "Iniciando actualizacion por lotes...\n\n";

$batchSize = 50;
$processed = 0;
$updated = 0;
$familyCounts = array_fill_keys(array_column($families, 'name'), 0);
$familyCounts['Default'] = 0;

foreach ($exercises as $exercise) {
    $match = matchFamily($exercise->getName(), $families);

    if ($match !== null) {
        $guidance = $match['guidance'];
        $familyCounts[$match['name']]++;
    } else {
        $guidance = getDefaultGuidance();
        $familyCounts['Default']++;
    }

    $exercise->setDescription($guidance['description']);
    $exercise->setSetupInstructions($guidance['setupInstructions']);
    $exercise->setExecutionInstructions($guidance['executionInstructions']);
    $exercise->setMovementStandards($guidance['movementStandards']);
    $exercise->setCommonFaults($guidance['commonFaults']);
    $exercise->setSafetyTips($guidance['safetyTips']);

    ++$updated;
    ++$processed;

    if ($processed % $batchSize === 0) {
        $entityManager->flush();
        echo "Progreso: {$processed} / {$totalToProcess} procesados...\n";
    }
}

$entityManager->flush();

// Summary
echo "\n========================================\n";
echo "Resumen de actualizacion\n";
echo "========================================\n";
echo "Total procesados: {$processed}\n";
echo "Total actualizados: {$updated}\n\n";

foreach ($familyCounts as $familyName => $count) {
    if ($count > 0) {
        echo sprintf("%-20s : %d\n", $familyName, $count);
    }
}

echo "\nDone.\n";
