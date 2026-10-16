<?php

declare(strict_types=1);

namespace App\Command\Blueprint;

use App\Entity\Achievement;
use App\Entity\TrainingRound;
use App\Enum\TestItemType;

/**
 * Contenido educativo del programa CrossFit Master v1 (niveles 1-15).
 *
 * Tips, fases del ciclo, tests de nivel, logros y puertas de movilidad,
 * transcritos literalmente de docs/detalle_crossfit_niveles_01-03.md,
 * docs/detalle_crossfit_niveles_04-06.md, docs/detalle_crossfit_niveles_07-09.md,
 * docs/detalle_crossfit_niveles_10-12.md y docs/detalle_crossfit_niveles_13-15.md.
 *
 * Los nombres de los ítems de testRequirements se usan como requirementKey
 * de los logros desbloqueados por ítem (deben coincidir EXACTAMENTE).
 *
 * Los ítems de test de tipo benchmark llevan 'benchmarkName' (nombre en el
 * catálogo) en lugar de 'benchmarkId': el comando resuelve el id en ejecución.
 */
class CrossfitMasterContent
{
    public static function getLevelContent(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => self::level1(),
            2 => self::level2(),
            3 => self::level3(),
            4 => self::level4(),
            5 => self::level5(),
            6 => self::level6(),
            7 => self::level7(),
            8 => self::level8(),
            9 => self::level9(),
            10 => self::level10(),
            11 => self::level11(),
            12 => self::level12(),
            13 => self::level13(),
            14 => self::level14(),
            15 => self::level15(),
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    /**
     * Logros del nivel (definición completa; el comando los crea/actualiza).
     */
    public static function getAchievements(int $levelNumber): array
    {
        return match ($levelNumber) {
            1 => self::achievementsL1(),
            2 => self::achievementsL2(),
            3 => self::achievementsL3(),
            4 => self::achievementsL4(),
            5 => self::achievementsL5(),
            6 => self::achievementsL6(),
            7 => self::achievementsL7(),
            8 => self::achievementsL8(),
            9 => self::achievementsL9(),
            10 => self::achievementsL10(),
            11 => self::achievementsL11(),
            12 => self::achievementsL12(),
            13 => self::achievementsL13(),
            14 => self::achievementsL14(),
            15 => self::achievementsL15(),
            default => throw new \InvalidArgumentException("Nivel {$levelNumber} no definido"),
        };
    }

    /**
     * Nombre visible del bloque de sesión según su tipo (y la sesión).
     */
    public static function getBlockName(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Preparación y movilidad',
            TrainingRound::BLOCK_FUERZA_SKILL => match ($sessionKey) {
                'session_a' => 'Fuerza básica',
                'session_b' => 'Skill con barra',
                'session_c' => 'Activación técnica',
                'session_d' => 'Fuerza mixta',
                default => 'Fuerza y skill',
            },
            TrainingRound::BLOCK_WOD => 'WOD / Metcon',
            TrainingRound::BLOCK_POSTURAL => match ($sessionKey) {
                'session_a' => 'Prehab de hombro',
                'session_b' => 'Prehab de muñeca',
                'session_c' => 'Prehab lumbar y core',
                'session_d' => 'Prehab de trío',
                default => 'Prehab',
            },
            TrainingRound::BLOCK_DESCARGA => 'Descarga',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Descripción del bloque de sesión. El comando añade el objetivo/tip de la
     * sesión y las notas de escalado de los benchmarks embebidos.
     */
    public static function getBlockDescription(string $blockType, string $sessionKey): string
    {
        return match ($blockType) {
            TrainingRound::BLOCK_PREPARACION => 'Movilidad fluida y acumulativa (activación, torácica, tobillo, cadera, hombro y muñeca) sin descansos de fuerza: transiciones de ~5 s.',
            TrainingRound::BLOCK_FUERZA_SKILL => 'Trabajo principal de fuerza y técnica de la sesión.',
            TrainingRound::BLOCK_WOD => 'WOD / Metcon de la sesión. El escalado es decisión del usuario en el player y también es protección (§1.7-CrossFit §10.4).',
            TrainingRound::BLOCK_POSTURAL => 'Prehab de la zona asignada a la sesión (hombro, muñeca, lumbar/core o trío). Calidad por encima de fatiga; nunca al fallo.',
            TrainingRound::BLOCK_DESCARGA => 'Vuelta a la calma: respiración diafragmática, Child\'s Pose y estiramiento suave de la zona trabajada.',
            default => throw new \InvalidArgumentException("Tipo de bloque {$blockType} no definido"),
        };
    }

    /**
     * Tips de sesión (docs/detalle_crossfit_niveles_01-03.md, sección de cada sesión).
     */
    public static function getSessionTip(int $levelNumber, string $sessionKey): ?string
    {
        $tips = match ($levelNumber) {
            1 => [
                'session_a' => 'La sentadilla y el hinge de hoy son la base de todo lo que viene: si la técnica se rompe, baja la carga, no las repes buenas (§1.7-CrossFit).',
                'session_b' => 'Barra vacía es un peso de verdad: si el press o el deadlift de hoy salen redondos, el cuerpo ya está aprendiendo lo que el N3 pedirá con carga (§1.2 encadenamiento).',
                'session_c' => 'En el metcon manda el ritmo que te deja repetir bien: rondas lentas y limpias > rondas rápidas y feas. El escalado también es protección (§1.7-CrossFit §10.4).',
                'session_d' => 'Los carries son el prehab más honesto que existe: si no puedes caminar erguido con la carga, todavía no la llevas a la barra.',
            ],
            2 => [
                'session_a' => 'El swing no es un squat frontal: es una bisagra. Si las rodillas dominan el gesto, vuelve al Kettlebell Romanian Deadlift pausado del Bloque 0.',
                'session_b' => 'El push press es el primer gesto dinámico del programa: la potencia sale de las piernas, no de la espalda baja. Lumbar neutra o no cuenta (§1.7-CrossFit §10.2).',
                'session_c' => 'El EMOM enseña a gestionar el reloj: termina las reps rápido y usa el resto del minuto para respirar. Rondas completas > reps heroicas.',
                'session_d' => 'La bisagra se entrena también cansado: en F3 mira que la lumbar no se redondee al final del AMRAP; ahí es donde CF5 se gana o se pierde.',
            ],
            3 => [
                'session_a' => 'Hoy la barra pesa lo que pesa: si CF1 no está (sentadilla profunda con talones pegados), el back squat se queda en goblet. La puerta manda sobre el ego.',
                'session_b' => 'Las negativas de F2 son el primer peldaño hacia la dominada estricta de N4-N6: baja en 3 segundos sin desplomarte. Primero estricto, luego dinámico (§1.7-CrossFit §10.1).',
                'session_c' => 'Fran escalada de F3 es el ensayo general del test: anota el tiempo y el escalado usado, y compáralo con las referencias del catálogo.',
                'session_d' => 'Cindy escalada enseña lo que un AMRAP largo pide: repartir, no fundirse. Anota tus rondas; en N4-N6 volverá como test.',
            ],
            4 => [
                'session_a' => 'El wall ball es sentadilla + press de piernas + throw: si CF1 o CF2 no están hoy, la bola baja de peso o de diana. La puerta manda sobre el ego.',
                'session_b' => 'La primera estricta se construye despacio: negativas de 5-6 s, banda cada vez más fina, y paciencia. Primero estricto, luego dinámico — el kipping de N5 no llega sin esta base (§1.7-CrossFit §10.1).',
                'session_c' => 'Tracción en metcon = agarre primero: si los antebrazos explotan antes que la espalda, alterna pull-up con box jump over y vuelve. Rondas completas > reps heroicas.',
                'session_d' => 'Cindy escalada de F2 es el ensayo general del test: anota rondas y escalado usado. Y en el T2B manda el core, no el columpio — todavía.',
            ],
            5 => [
                'session_a' => 'Hoy el RDL trabaja la bisagra que el test N6 te pedirá bajo fatiga (CF7): si la lumbar se redondea en la serie, termina la serie. Calidad por encima de kilos.',
                'session_b' => 'El power clean no se gana con la espalda: es extensión de cadera + tirón rápido + recepción. Con la barra ligera de hoy construye el gesto que N6 cargará y N10+ pesará (§1.7-CrossFit §10.2).',
                'session_c' => 'Annie escalada de F3 es un test de ritmo, no de héroe: los singles deben ser automáticos para que los dobles de F2-F3 de las próximas sesiones salgan solos.',
                'session_d' => 'El T2B con kipping nace del hollow: si al acabar el AMRAP no puedes mantener un Hollow Body Hold de 20 s, el kipping era columpio, no técnica.',
            ],
            6 => [
                'session_a' => 'El push jerk es el S2OH que el N7 usará con carga: hoy se aprende con barra ligera. Lumbar neutra, codos rápidos, y si la muñeca en recepción protesta >2/10 matinal, se congela el gesto (§10.3).',
                'session_b' => 'Clean y jerk en el mismo metcon es la letra pequeña de todos los WODs RX que vienen: hoy se practica la transición con cargas que perdonan.',
                'session_c' => 'Helen RX de F2 es el hito del tramo entero: si las pull-ups se rompen técnicamente a mitad, la marca RX no cuenta — escala a banda y deja constancia. Primero estricto, luego dinámico, y siempre técnica (§1.7-CrossFit §10.1/§10.4).',
                'session_d' => 'Cierra el tramo como empezaste: movilidad densa, carries honestos y un metcon que se disfruta. El test de N6 es una celebración del trabajo de N1 a N6, no un examen sorpresa.',
            ],
            7 => [
                'session_a' => 'El thruster es donde se gana o se pierde Fran: rack que no ahoga, extensión de cadera completa y la barra vuela. Si el press se convierte en grind, la carga es del pectoral, no de Fran.',
                'session_b' => 'Clean y jerk a 60/45 es exactamente la mitad de Grace: cada rep de hoy es una rep que Grace no te cobrará cara. Técnica primero, el reloj después (§1.7-CrossFit §10.4).',
                'session_c' => 'Fran es corta y mentirosa: 21-15-9 suena a nada y pesa como ningún AMRAP. Divide 8-7-6 / 5-4-2 si es tu primera vez, y recuerda: la marca solo cuenta si la técnica aguanta (§1.7-CrossFit §10.4).',
                'session_d' => 'Los dobles se ganan en la sesión D: sets cortos, cuerda corta, muñeca relajada. Cuando 10 salgan como 5, Fran deja de tener secretos.',
            ],
            8 => [
                'session_a' => 'El OHS es el detector de mentiras del overhead: si la barra baila a 30 kg, no es fuerza lo que falta, es posición. PVC y paciencia antes que kilos (§1.7-CrossFit).',
                'session_b' => 'El snatch es el gesto más técnico del programa: hoy es tall y hang, no suelo. La paciencia de hoy es el Randy del N12. Y en el HSPU manda la muñeca: dolor matinal >2/10 = HSPU fuera un ciclo (§1.7-CrossFit §10.3).',
                'session_c' => 'Grace es 30 repeticiones del ejercicio más caro del mundo: singles rápidos desde el principio ganan a series heroicas que mueren a la 15. Y 60/43 kg es la carga de test del push jerk — cada uno cuenta doble (§1.7-CrossFit §10.4).',
                'session_d' => 'El carry overhead es el OHS en movimiento: si la barra (o la mancuerna) no se sostiene en marcha, el OHS de mañana tampoco. Construye el overhead en todas las direcciones.',
            ],
            9 => [
                'session_a' => 'El deadlift pesado es un visitante de honor, no un inquilino: entra, trabaja a 1-2 RIR y se va. Si la lumbar negocia con la barra en la serie 3, la serie terminó (§1.7-CrossFit §10.2).',
                'session_b' => 'El muscle-up no se persigue: se siembra. False grip, transición lenta, negativa de 5 segundos — durante ciclos y ciclos. Cuando las piezas estén listas, la transición estará tan ensayada que será casi aburrida (§1.7-CrossFit §10.1).',
                'session_c' => 'Josh es Fran con la barra overhead: mismas rondas, mismo dolor, una dificultad más. Divide las pull-ups desde el principio —30-12, 20-10, 12-6 son marcas honestas— y guarda los hombros para la barra.',
                'session_d' => 'Los dobles bajo fatiga se entrenan con las piernas cansadas: box jumps y C2B antes, comba después. El que salta recto cuando está fresco no sabe combar; el que mantiene la técnica con el lactato alto, sí.',
            ],
            10 => [
                'session_a' => 'El squat clean no es un power clean con prisa: es un tirón, una recepción y UNA sentadilla, todo en el mismo latido. Si separas las partes en la F1, la F3 las unirá sola. Y recuerda: la recepción que se corta es power clean, no squat clean (§1.7-CrossFit §10.4).',
                'session_b' => 'La muscle-up no se fuerza: se ensaya hasta que sale sola. Transición asistida, negativa lenta, un intento fresco con descanso completo — ese es el camino. El día que salga, nadie en el box lo sabrá antes que tú (§1.7-CrossFit §10.1).',
                'session_c' => 'Elizabeth castiga dos cosas a la vez: la recepción del clean y el empuje en anillas. Divide los ring dips desde el principio (7-7-7 / 5-5-5 / 4-3-2) y deja que el squat clean respire. Los que queman los ring dips en la ronda de 21 pagan la de 9 el triple.',
                'session_d' => 'El kipping HSPU es un préstamo que se paga con intereses: hoy suma reps, mañana cobra hombros. Por eso el estricto sigue en el programa aunque el kipping entre — es lo que permite que esto sea sostenible (§1.7-CrossFit §10.1).',
            ],
            11 => [
                'session_a' => 'El C&J es la verdad de la barra: si el clean llega al rack limpio y el jerk bloquea arriba, da igual lo que pese — es válido. Si hay que pelear la recepción o press-out el jerk, la carga miente (§1.7-CrossFit §10.4).',
                'session_b' => 'El snatch de suelo premia la paciencia acumulada desde N8: tall, hang, power, squat — cada peldaño de los últimos tres niveles está en la barra de hoy. Si la primera fase se desordena, vuelve a Snatch Balance y reconstruye (§1.7-CrossFit).',
                'session_c' => 'DT es cinco rondas de un trato con la barra: 12 muertes suaves, 9 recepciones limpias, 6 bloqueos decisivos. Los que hacen singles rápidos de jerk ganan a los que hacen triples heroicos que mueren en la ronda 3. Y 70/52 kg es la carga del test — cada ronda de hoy es un ensayo (§1.7-CrossFit §10.4).',
                'session_d' => 'El front squat es el detector del clean: si 75 kg en el rack delantero se siente como casa, el clean de 70/47.5 no tendrá secretos. Construye la posición y la carga llegará sola.',
            ],
            12 => [
                'session_a' => 'El C&J a 70/47.5 ya no es un evento: es la carga de trabajo. Hoy se hace cansado, con la zancada corta y el bloqueo seco — porque así llegará el día del test. Lo que no se hace cansado hoy, no existe cuando cuenta (§1.7-CrossFit §10.4).',
                'session_b' => 'El Nate se gana en sesiones como esta: 2 muscle-ups frescos, un push invertido, 8 swings — repetido hasta que el cuerpo lo haga sin pensar. El WOD del test no será más difícil que la suma de sus piezas; por eso las piezas se entrenan por separado (§1.7-CrossFit).',
                'session_c' => 'Whitten es el Hero que separa a los impacientes de los atletas: 110 reps de cada cosa y una milla escondida en el medio. Nadie hace Whitten rápido; todo el mundo hace Whitten entero — esa es la victoria. Y en el Nate, las rondas se ganan en los swings: muñeca relajada, bisagra que no negocia (§1.7-CrossFit §10.2).',
                'session_d' => 'El volumen se acumula en sesiones aburridas: C2B de 8 en 8, T2B de 10 en 10, un snatch ligero que no se rompe nunca. Los Heroes se ganan aquí, no el día del WOD.',
            ],
            13 => [
                'session_a' => 'El deadlift de 170/120 kg no se levanta el día del test: se levanta hoy, con 155 y la espalda impecable. La barra pesada premia la paciencia y castiga la prisa: cada rep de hoy es un voto a favor del single del test (§1.7-CrossFit §10.4).',
                'session_b' => '80/60 en el snatch no es un número: es la prueba de que el overhead (CF2), la sentadilla (CF1) y la bisagra (CF7) llevan once niveles trabajando juntos. Si hoy la barra se siente ligera en el tirón y pesada solo en la mente, estás listo; si al revés, baja 5 kg y reconstruye (§1.7-CrossFit).',
                'session_c' => 'Amanda es el benchmark más desigual de los Girls: 21 reps totales, y cada una puede romperse. Divide los muscle-ups 5-4 / 4-3 / 3-2 desde el minuto cero y trata el snatch como un ritual: tirón, recepción, arriba. Los que hacen 9 MU seguidos terminan el snatch de 5 agarrando la rodilla (§1.7-CrossFit §10.4).',
                'session_d' => 'El HSPU en deficit es honestidad pura: las placas quitan el colchón del suelo y cualquier recorte de rango se paga al momento. Entra solo cuando el kipping plano sea aburrido de lo sólido — y mantén el estricto semanal: es el seguro de vida (§1.7-CrossFit §10.1).',
            ],
            14 => [
                'session_a' => 'El motor no se entrena con héroes: se entrena con AMRAPs de 25 minutos que nadie aplaude. El día del Hero solo se recoge lo sembrado en sesiones como esta — tres modalidades seguidas sin parar, a un ritmo que puedas defender en el minuto 20 (§1.7-CrossFit).',
                'session_b' => 'El Bar Muscle Up es la recompensa de cuatro niveles de anillas: la transición ya la tienes, solo cambia el apoyo. No la fuerces el primer día: la barra no perdona la prisa. Y cuando las MU de anillas entren en fatiga, piensa en series de 2-3 desde el principio — la serie de 5 que mueres en la 4 te la cobra el minuto siguiente (§1.7-CrossFit §10.4).',
                'session_c' => 'Los Heroes no se ganan con el primer 50%: se ganan con lo que te queda para el segundo. En Gunny, corre la primera milla como si aún te faltara todo — porque te falta. En Daniel, los pull-ups de salida son un depósito: quita series pequeñas y deja el crédito para los de la cola. El Hero RX se completa entero, no rápido: esa es la marca (§1.7-CrossFit).',
                'session_d' => 'El WOD largo de hoy es la caja negra del nivel: 30 minutos sin nadie que te diga si vas bien. Aprende a escuchar el ritmo, no el reloj: si en el minuto 20 puedes hablar en frases cortas, vas bien; si no, ya lo sabes. El motor se construye exactamente ahí, en el silencio entre el minuto 15 y el 25.',
            ],
            15 => [
                'session_a' => 'El C&J de 100/65 no se negocia con la barra: tiras, recibes en sentadilla y empujas con todo, o no cuenta. En el evento combinado, el primer evento (la barra) se gana en la cabeza: un single limpio y a descansar. El que celebra el C&J pierde el WOD que viene detrás (§1.7-CrossFit §10.4).',
                'session_b' => 'La muscle-up de competición es una administración: sets pequeños, descanso corto, cero héroes. Doce MU acumuladas son 4 series de 3 o 6 de 2 — elige antes de empezar y cumple. El día que necesites una serie de 5 para salvar el test, el test ya se perdió (§1.7-CrossFit §10.4).',
                'session_c' => 'Un evento combinado no es un WOD duro: es tres WODs con la misma cabeza. Entra al E1 sin recordar el E3, y al E3 sin recordar el E1 — cada evento se juega solo. Esa es la habilidad que se entrena hoy y la que el torneo de verdad exigirá: borrar y empezar de cero tres veces en una mañana (§1.7-CrossFit).',
                'session_d' => 'El OHS de 80/60 kg es la prueba de fuego invisible del nivel: si la barra vuela sobre la cabeza con las sentadillas vacías, todo el overhead del test está asegurado. La posición es el rendimiento — el día que dejes de trabajarla, dejará de trabajar para ti.',
            ],
            default => [],
        };

        return $tips[$sessionKey] ?? null;
    }

    /**
     * Puertas de movilidad CF1-CF7 del programa (spec §8).
     * N1 no evalúa ninguna; N2 evalúa CF5; N3 evalúa CF1 y CF4.
     * CF2/CF3/CF6/CF7 se definen ahora para lotes N4-N6 y posteriores.
     */
    public static function getMobilityGates(): array
    {
        return [
            [
                'code' => 'CF1',
                'name' => 'Sentadilla profunda pegada',
                'description' => 'Puerta de movilidad de sentadilla. Se evalúa en el Nivel 3; exigida a partir de N4 (squat cargado progresivo).',
                'criteria' => 'Deep Squat Hold 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor',
            ],
            [
                'code' => 'CF2',
                'name' => 'Overhead (hombro)',
                'description' => 'Puerta de movilidad de hombro overhead. Se evalúa en el Nivel 4 (re-test N7); exigida a partir de N5 (press, S2OH, wall ball a diana).',
                'criteria' => 'Shoulder Dislocate con banda/PVC ×15 sin compensar la lumbar; Overhead Squat con PVC/barra vacía ×10 en profundidad',
            ],
            [
                'code' => 'CF3',
                'name' => 'Muñeca en front rack',
                'description' => 'Puerta de movilidad de muñeca en front rack. Se evalúa en el Nivel 4 (re-test N7); exigida a partir de N5 (cleans).',
                'criteria' => 'Front rack con barra vacía 30 s/lado sin dolor ni desviación activa',
            ],
            [
                'code' => 'CF4',
                'name' => 'Dorsiflexión de tobillo',
                'description' => 'Puerta de movilidad de tobillo. Se evalúa en el Nivel 3; exigida a partir de N4 (lunges, wall ball).',
                'criteria' => 'Calf Stretch Wall + Bent-Knee Soleus Stretch 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón',
            ],
            [
                'code' => 'CF5',
                'name' => 'Cadera/bisagra',
                'description' => 'Puerta de movilidad de bisagra de cadera. Se evalúa en el Nivel 2; exigida a partir de N3 (deadlift con carga, KB swing progresivo).',
                'criteria' => 'Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada',
            ],
            [
                'code' => 'CF6',
                'name' => 'Muñeca en extensión',
                'description' => 'Puerta de movilidad de muñeca en extensión. Se evalúa en el Nivel 6 (re-test N9); exigida a partir de N7 (HSPU, handstand).',
                'criteria' => 'Wrist Push-Up 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10',
            ],
            [
                'code' => 'CF7',
                'name' => 'Bisagra lumbar controlada',
                'description' => 'Puerta de control lumbar en bisagra. Se evalúa en el Nivel 6 (re-test N9); exigida a partir de N7 (O-lifts, deadlifts en fatiga).',
                'criteria' => 'Dead Bug 2×10/lado + Bird Dog 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra',
            ],
        ];
    }

    // ========================================================================
    // LEVEL 1 — ON-RAMP
    // ========================================================================

    private static function level1(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): aprende los patrones (sentadilla, bisagra, empuje vertical, core) con PVC/barra vacía y KB ligero. Aquí no se falla: se aprende. Técnica por encima de volumen.',
                'Fase 2 (Progresión): sube reps o carga mínima (KB +2-4 kg); primer AMRAP de 8\' y primeros intervalos cortos.',
                'Fase 3 (Intensificación): calidad máxima con la carga más alta del nivel; ensayo de test integrado en las sesiones A y D.',
                'Fase 4 (Recuperación): mitad de volumen e intensidad; movilidad protagonista; el test de nivel se ejecuta al final, descansado.',
                'Regla §1.7-CrossFit: en N1 todo es estricto, no hay kipping; nada balístico sobre un hombro sin base estricta. El deadlift es técnico y de carga ligera-moderada; dolor matinal >2/10 = congelar el gesto un ciclo.',
                'Si fallas el test: revisa la profundidad de sentadilla (cadera bajo la rodilla, talones pegados) y la espalda neutra en la bisagra: la técnica rota no cuenta (§10.4). Repite la fase Base sin subir cargas.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Aprender patrones con PVC/barra vacía y KB ligero', 'note' => 'Variantes más fáciles; técnica por encima de volumen', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir reps o carga mínima (KB +2-4 kg)', 'note' => 'Primer AMRAP 8\'; primeros intervalos cortos', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Calidad máxima con la carga más alta del nivel', 'note' => 'Ensayo de test integrado en sesión A y D', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen e intensidad; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Air Squat', 'minimum' => 20, 'target' => 30, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad con cadera bajo la rodilla y talones pegados; rep con técnica rota no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Plank', 'minimum' => 40, 'target' => 60, 'unit' => 'seconds', 'direction' => 'gte', 'form' => 'Costillas abajo, glúteo apretado, respiración continua bajo tensión', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Goblet Squat (KB 8/6 kg)', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Cadera bajo la rodilla, talones pegados (siembra CF1); peso orientativo ±2 kg según KB disponible', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Kettlebell Deadlift (16/12 kg, técnico)', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Espalda neutra y cadera-bisagra (CF5) visible en cada rep; peso orientativo ±2 kg', 'type' => TestItemType::Fuerza->value],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL1(): array
    {
        return [
            [
                'code' => 'cf-n1-20-air-squat',
                'name' => '20 sentadillas al aire',
                'description' => 'Supera el mínimo de Air Squat (20 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Air Squat',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-plancha-40s',
                'name' => 'Plancha de 40 segundos',
                'description' => 'Supera el mínimo de Plank (40 s) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🧱', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Plank',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primer-goblet-test',
                'name' => 'Primer Goblet Squat de test',
                'description' => 'Supera el mínimo de Goblet Squat con KB 8/6 kg (10 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Goblet Squat (KB 8/6 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-bisagra-impecable',
                'name' => 'Bisagra impecable',
                'description' => 'Supera el mínimo de Kettlebell Deadlift técnico (10 reps) en el test de Nivel 1.',
                'category' => 'progress', 'icon' => '🔩', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Kettlebell Deadlift (16/12 kg, técnico)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-sentadilla-profunda',
                'name' => 'Primera sentadilla profunda pegada',
                'description' => 'Primera Air Squat con cadera bajo la rodilla y talones en el suelo.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primer-amrap',
                'name' => 'Primer AMRAP completado',
                'description' => 'Primera sesión C con el AMRAP completo sin parar el reloj.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primera-bisagra-barra',
                'name' => 'Primera bisagra con barra',
                'description' => 'Primer Kettlebell Deadlift / deadlift con barra vacía con 3 puntos de contacto y espalda neutra validados.',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n1-primer-press-barra',
                'name' => 'Primer press con barra',
                'description' => 'Primer Strict Press con barra vacía en línea, sin hiperextensión lumbar.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 2 — PRIMER IMPULSO
    // ========================================================================

    private static function level2(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primer EMOM y primeros WODs de 2 movimientos; swings con KB de trabajo ligero (16/12 kg); técnica de bisagra bajo la puerta CF5.',
                'Fase 2 (Progresión): sube carga mínima (goblet 12/8); swing sigue en 16/12 y salta a 24/16 solo en F3; primer AMRAP 10\'; Helen escalada como hito de sesión C.',
                'Fase 3 (Intensificación): swings a 24/16 ensayando el test; WODs de 2 movimientos a ritmo alto; ensayo de test integrado en sesión A.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-CrossFit: el swing americano no se usa en fatiga alta — en test y dosis, "Kettlebell Swing" siempre significa Kettlebell Swing Russian (§10.2). Deja pasar al menos una sesión completa entre bisagras cargadas exigentes.',
                'Puerta CF5: se evalúa en el test de este nivel (requisito efectivo del N3). Re-test implícito en cada envío de test; ensayo integrado en el Bloque 0 de la sesión C.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer EMOM y primeros WODs de 2 movimientos', 'note' => 'Swings con KB 16/12 kg; técnica de bisagra bajo la puerta CF5', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir carga mínima (goblet 12/8); primer AMRAP 10\'', 'note' => 'Helen escalada como hito de sesión C', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Swings a 24/16 ensayando el test; WODs a ritmo alto', 'note' => 'Ensayo de test integrado en sesión A', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Goblet Squat (KB 12/8 kg)', 'minimum' => 15, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Cadera bajo la rodilla, talones pegados; objetivo del test con 12/8 kg', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Kettlebell Swing Russian (24/16 kg)', 'minimum' => 15, 'target' => 21, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Bisagra visible en cada rep (criterio CF5); el swing americano no se usa en test (§10.2)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Plank', 'minimum' => 60, 'target' => 90, 'unit' => 'seconds', 'direction' => 'gte', 'form' => 'Costillas abajo, glúteo apretado, respiración continua', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Helen (benchmark escalado)', 'minimum' => 1080, 'target' => 840, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Helen en modalidad Scaled: KB menor (16/12 kg) y Pull Up asistidos con banda o Ring Row - Feet on Ground; marca registrada con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Helen'],
                        ['name' => 'Puerta CF5 — Cadera/bisagra', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Bisagra con barra vacía tocando 3 puntos (cabeza-tórax-cadera) ×10 reps controladas, sin flexión de rodillas dominante ni lumbar redondeada', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF5'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL2(): array
    {
        return [
            [
                'code' => 'cf-n2-goblet-15',
                'name' => 'Goblet de 15',
                'description' => 'Supera el mínimo de Goblet Squat con KB 12/8 kg (15 reps) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Goblet Squat (KB 12/8 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-plancha-60s',
                'name' => 'Plancha de 60 segundos',
                'description' => 'Supera el mínimo de Plank (60 s) en el test de Nivel 2.',
                'category' => 'progress', 'icon' => '🧱', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Plank',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-primer-benchmark',
                'name' => 'Primer benchmark registrado',
                'description' => 'Registra tu primer benchmark (Helen escalada) dentro de un envío de test del Nivel 2.',
                'category' => 'progress', 'icon' => '🏁', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Helen (benchmark escalado)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n2-primer-emom',
                'name' => 'Primer EMOM completado',
                'description' => 'Primer EMOM del nivel terminado con todas las rondas.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-swing-24-16',
                'name' => 'Swing de 24/16 kg',
                'description' => 'Primer Kettlebell Swing Russian con 24/16 kg y técnica validada (ensayo CF5).',
                'category' => 'skill', 'icon' => '🔔', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n2-helen-escalada',
                'name' => 'Helen escalada',
                'description' => 'Primera ejecución completa del benchmark Helen en sesión (hito de sesión, antes de ser test).',
                'category' => 'skill', 'icon' => '🏃', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n2-cargador-hombro',
                'name' => 'Cargador de hombro',
                'description' => 'Primer Kettlebell Halo de 10/lado sin dolor.',
                'category' => 'skill', 'icon' => '💫', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 3 — CONSOLIDACIÓN
    // ========================================================================

    private static function level3(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primer back squat y front squat con barra ligera; deadlift 50/35 técnico; WODs de 2 movimientos con barra/mancuernas.',
                'Fase 2 (Progresión): subir barra +5 kg; primeras negativas de dominada; primer AMRAP de 12\'; Cindy escalada como hito de sesión D.',
                'Fase 3 (Intensificación): ensayo de test en A (deadlift 60/40 ×5, push press 30/20 ×8); Fran escalada como hito de sesión C.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF1/CF4 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit: primero estricto, luego dinámico; O-lifts y deadlifts pesados nunca en fatiga alta; si la lumbar se redondea en el push press, se valida como estricto y se anota.',
                'Puertas CF1 y CF4: se evalúan en el test de este nivel (requisito efectivo del N4). Ensayos integrados: Deep Squat Hold creciente en Bloque 0 de todas las sesiones (CF1) y bloque tobillo completo en sesión D (CF4).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer back squat y front squat con barra ligera', 'note' => 'Deadlift 50/35 técnico; WODs de 2 movimientos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Subir barra +5 kg; primer AMRAP 12\'', 'note' => 'Primeras negativas de dominada; Cindy escalada como hito de sesión D', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Ensayo de test en A; WODs a ritmo alto', 'note' => 'Fran escalada como hito de sesión C', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF1/CF4 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Goblet Squat (KB 16/12 kg)', 'minimum' => 20, 'target' => 25, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad CF1: cadera bajo la rodilla, talones pegados', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Deadlift (60/40 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Carga de trabajo moderada, nunca en fatiga alta (§10.2); criterio de validez = CF5 visible en cada rep', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Push Press (30/20 kg)', 'minimum' => 8, 'target' => 12, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Gesto dinámico piernas-impulso; si la lumbar se redondea al bajar, se valida como estricto y se anota', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fran (benchmark escalado)', 'minimum' => 780, 'target' => 600, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Fran en modalidad Scaled: thruster con 20-30/15-20 kg o mancuernas, Pull Up asistidos con banda o jumping pull-ups controlados; marca con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Fran'],
                        ['name' => 'Puerta CF1 — Sentadilla profunda pegada', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Deep Squat Hold 60 s con talones pegados, rodillas abiertas y lumbar neutra, sin dolor', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF1'],
                        ['name' => 'Puerta CF4 — Dorsiflexión de tobillo', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Calf Stretch Wall + Bent-Knee Soleus Stretch 30 s/lado cada uno con talón pegado; rodilla a pared ≥10 cm sin despegar talón', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF4'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL3(): array
    {
        return [
            [
                'code' => 'cf-n3-goblet-20',
                'name' => 'Goblet de 16/12 kg ×20',
                'description' => 'Supera el mínimo de Goblet Squat con KB 16/12 kg (20 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Goblet Squat (KB 16/12 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-deadlift-60-40',
                'name' => 'Deadlift de 60/40 kg',
                'description' => 'Supera el mínimo de Deadlift a 60/40 kg (5 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🔩', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Deadlift (60/40 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-press-potencia',
                'name' => 'Primer press de potencia',
                'description' => 'Supera el mínimo de Push Press a 30/20 kg (8 reps) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Push Press (30/20 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-fran-registrada',
                'name' => 'Fran escalada registrada',
                'description' => 'Registra Fran en modalidad Scaled dentro de un envío de test del Nivel 3.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Fran (benchmark escalado)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n3-sentadilla-60s',
                'name' => 'Primera sentadilla profunda de 60 s',
                'description' => 'Primer Deep Squat Hold de 60 s pegado (ensayo CF1).',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-front-squat',
                'name' => 'Sentadilla frontal ligera',
                'description' => 'Primer Front Squat con 30/20 kg y criterio de profundidad.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n3-cindy-escalada',
                'name' => 'Cindy escalada',
                'description' => 'Primera ejecución completa de Cindy en modalidad Scaled en sesión D.',
                'category' => 'skill', 'icon' => '🏅', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n3-puerta-cf1',
                'name' => 'Puerta CF1 superada',
                'description' => 'Supera la puerta de movilidad CF1 (Sentadilla profunda pegada) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF1 — Sentadilla profunda pegada',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n3-puerta-cf4',
                'name' => 'Puerta CF4 superada',
                'description' => 'Supera la puerta de movilidad CF4 (Dorsiflexión de tobillo) en el test de Nivel 3.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF4 — Dorsiflexión de tobillo',
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 4 — PRIMERA TRACCIÓN
    // ========================================================================

    private static function level4(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): back squat 50/35 de trabajo, primer Bench Press, negativas de 5 s + banda en tracción, primeros Knee Raise; wall ball ligero (6/4 kg) y Box Jump bajo en metcons.',
                'Fase 2 (Progresión): banda de asistencia más fina o primeras Pull Up sueltas; Hanging Leg Raise → primeros Toes to Bar Strict; primer AMRAP de 12\' con wall ball; ensayo de CF2/CF3 en Bloque 0.',
                'Fase 3 (Intensificación): back squat 60/40 ensayando el test; volumen de tracción estricta moderado (tope 15-20 reps totales por sesión); Toes to Bar Strict en AMRAP; Cindy escalada como hito de sesión D.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF2/CF3 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit: primero estricto, luego dinámico — el kipping de N5 exige base estricta (objetivo de 5 dominadas); en N4 no hay kipping. Nada balístico sobre un hombro sin base.',
                'Puertas CF2 y CF3: se evalúan en el test de este nivel (requisito efectivo del N5: Power Clean, wall ball a diana, S2OH). Ensayos integrados en Bloque 0 de las sesiones A/B (CF2) y B/C (CF3). Si fuerza superada + puerta fallada → mobility_blocked (ciclo corto de movilidad).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Back squat 50/35 de trabajo; primer Bench Press', 'note' => 'Negativas de 5 s + banda; primeros Knee Raise; wall ball ligero y Box Jump bajo', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Banda más fina o primeras Pull Up sueltas', 'note' => 'Hanging Leg Raise → primeros Toes to Bar Strict; primer AMRAP 12\' con wall ball', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Back squat 60/40 ensayando el test', 'note' => 'Tope 15-20 reps de tracción estricta por sesión; Cindy escalada como hito', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF2/CF3 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Pull Up (estricta)', 'minimum' => 2, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Dominada estricta limpia; admite asistencia mínima con banda fina si aún no enlaza 2 limpias, pero el logro de primera dominada exige banda cero; el objetivo de 5 es la base mínima que N5 exige antes del kipping (§10.1)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Back Squat (60/40 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad con talones pegados (CF1); si la lumbar se va, baja carga antes que profundidad', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Toes to Bar Strict', 'minimum' => 5, 'target' => 10, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Contacto pies-barra sin balanceo de kipping; si no llega, Hanging Leg Raise + toque a la altura posible', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Cindy (benchmark escalado)', 'minimum' => 8, 'target' => 12, 'unit' => 'rounds', 'direction' => 'gte', 'form' => 'Cindy AMRAP 20\' en Scaled: 5 Band Assisted Pull Up (o Jumping Pull Up controlado) + 10 Push Up + 15 Air Squat por ronda; marca con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Cindy'],
                        ['name' => 'Puerta CF2 — Overhead (hombro)', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Shoulder Dislocate con banda/PVC ×15 sin compensar la lumbar; Overhead Squat con PVC/barra vacía ×10 en profundidad', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF2'],
                        ['name' => 'Puerta CF3 — Muñeca en front rack', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Front rack con barra vacía 30 s/lado sin dolor ni desviación activa', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF3'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL4(): array
    {
        return [
            [
                'code' => 'cf-n4-primera-dominada-estricta',
                'name' => 'Primera dominada estricta',
                'description' => 'Primera dominada estricta libre (banda cero) en el test de Nivel 4. Celebra la primera dominada libre de la vida del usuario.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Pull Up (estricta)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n4-sentadilla-60-40',
                'name' => 'Sentadilla de 60/40 kg',
                'description' => 'Supera el mínimo de Back Squat a 60/40 kg (5 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Back Squat (60/40 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-primer-t2b-test',
                'name' => 'Primer T2B estricto de test',
                'description' => 'Supera el mínimo de Toes to Bar Strict (5 reps) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Toes to Bar Strict',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-cindy-registrada',
                'name' => 'Cindy escalada registrada',
                'description' => 'Registra Cindy en modalidad Scaled (mínimo 8 rondas) dentro de un envío de test del Nivel 4.',
                'category' => 'progress', 'icon' => '🏅', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Cindy (benchmark escalado)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-puerta-cf2',
                'name' => 'Puerta CF2 superada',
                'description' => 'Supera la puerta de movilidad CF2 (Overhead, hombro) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF2 — Overhead (hombro)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-puerta-cf3',
                'name' => 'Puerta CF3 superada',
                'description' => 'Supera la puerta de movilidad CF3 (Muñeca en front rack) en el test de Nivel 4.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF3 — Muñeca en front rack',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-banda-fina',
                'name' => 'Banda fina, dominada cerca',
                'description' => 'Primer Band Assisted Pull Up con la banda más fina disponible, recorrido completo.',
                'category' => 'skill', 'icon' => '🎽', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-negativa-6s',
                'name' => 'Negativa de 6 segundos',
                'description' => 'Primer Negative Pull Up con bajada de 6 s controlada.',
                'category' => 'skill', 'icon' => '⏳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-primer-t2b',
                'name' => 'Primer Toes to Bar',
                'description' => 'Primer Toes to Bar Strict con contacto pies-barra, sin balanceo.',
                'category' => 'skill', 'icon' => '🏅', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n4-primer-wall-ball',
                'name' => 'Primer wall ball',
                'description' => 'Primer Wall Ball a diana con sentadilla profunda válida (CF1).',
                'category' => 'skill', 'icon' => '🏐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n4-primer-bench',
                'name' => 'Primer bench press',
                'description' => 'Primer Bench Press con barra de 30/20 kg y técnica validada.',
                'category' => 'skill', 'icon' => '🛋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 5 — MOTOR Y GIMNÁSTICOS
    // ========================================================================

    private static function level5(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): power clean de técnica con 30/20 kg; kipping en bloques cortos (tope 20 reps/sesión) solo si hay ≥5 estrictas; Double Under Practice en dosis de 10-15 intentos; T2B estricto consolidado.',
                'Fase 2 (Progresión): power clean 40/25 kg; primeros EMOM con kipping pull-up; primeros sets de 2-3 Double Unders; primer AMRAP de 15\'; Annie escalada como hito de sesión C.',
                'Fase 3 (Intensificación): power clean 50/35 kg ensayando el gesto del test N6; kipping en AMRAP con tope de volumen; Double Unders en singles sueltos dentro de WODs cortos; ensayo de test integrado.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (kipping condicionado): el kipping pull-up solo con ≥5 estrictas validadas; si el test N4 salió por debajo, sigue en progresión estricta (Band Assisted Pull Up → Pull Up) hasta conseguirlas. Volumen de kipping por sesión limitado a 20-30 reps, con al menos una sesión completa entre exposiciones intensas; dolor de hombro matinal >2/10 = congelar kipping un ciclo.',
                'Puertas: ninguna evaluada en N5. Se siembra CF6 (Wrist Push-Up con más volumen + apoyo en extensión) y CF7 (bisagra bajo fatiga en WODs con swing); se evalúan en N6.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Power clean técnico 30/20 kg; T2B estricto consolidado', 'note' => 'Kipping corto (tope 20 reps/sesión) solo con ≥5 estrictas; Double Under Practice 10-15 intentos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Power clean 40/25 kg; primeros EMOM con kipping', 'note' => 'Primeros sets de 2-3 Double Unders; primer AMRAP 15\'; Annie escalada como hito', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Power clean 50/35 kg ensayando el gesto del test N6', 'note' => 'Kipping en AMRAP con tope de volumen; ensayo de test integrado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Kipping Pull Up', 'minimum' => 10, 'target' => 15, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Kipping pull-up con arco controlado; si llegaste a N5 sin las 5 estrictas de base, este ítem se evalúa como Pull Up estricta ×5-8 y el kipping sigue bloqueado hasta superarlo (§10.1)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Toes to Bar', 'minimum' => 12, 'target' => 20, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Toes to Bar con kipping controlado nacido del hollow; si el balanceo sustituye al core, se valida como Toes to Bar Strict', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Power Clean (40/25 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción en power (sin squat clean) y front rack sin dolor (CF3); si la técnica se rompe, la rep no cuenta aunque se levante (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Annie (benchmark escalado)', 'minimum' => 960, 'target' => 720, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Annie FOR_TIME en Scaled: 50-40-30-20-10 de Single Unders + Sit-up; marca con scalingUsed = scaled', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Annie'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL5(): array
    {
        return [
            [
                'code' => 'cf-n5-kipping-de-10',
                'name' => 'Kipping de 10',
                'description' => 'Supera el mínimo de Kipping Pull Up (10 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🌀', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Kipping Pull Up',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-t2b-de-12',
                'name' => 'T2B de 12',
                'description' => 'Supera el mínimo de Toes to Bar (12 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Toes to Bar',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-power-clean-test',
                'name' => 'Primer power clean de test',
                'description' => 'Supera el mínimo de Power Clean a 40/25 kg (5 reps) en el test de Nivel 5.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Power Clean (40/25 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-annie-registrada',
                'name' => 'Annie escalada registrada',
                'description' => 'Registra Annie en modalidad Scaled (mínimo 16:00) dentro de un envío de test del Nivel 5.',
                'category' => 'progress', 'icon' => '🏃', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Annie (benchmark escalado)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-primer-kipping',
                'name' => 'Primer kipping pull-up',
                'description' => 'Primer Kipping Pull Up conectado, arco controlado, sin dolor de hombro. Solo sobre base estricta (≥5 dominadas).',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n5-primer-doble',
                'name' => 'Primer doble',
                'description' => 'Primer Double Under conectado (1-2 seguidos cuentan).',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n5-dobles-de-5',
                'name' => 'Dobles de 5',
                'description' => 'Primer set de 5 Double Unders sin fallo.',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n5-t2b-amrap',
                'name' => 'T2B kipping en AMRAP',
                'description' => 'Primer AMRAP completado con Toes to Bar (kipping) sin romper el hollow.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n5-clean-tecnico',
                'name' => 'Clean técnico validado',
                'description' => 'Primer Power Clean con recepción y front rack validados por el criterio del test.',
                'category' => 'skill', 'icon' => '✅', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 6 — UMBRAL RX
    // ========================================================================

    private static function level6(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): push jerk de técnica con 30/20 kg; power clean 45/30 kg; wall ball a diana de referencia (bola 9/6 kg en F3); Helen RX como hito de sesión C (primera toma de contacto, sin exigencia).',
                'Fase 2 (Progresión): push jerk 40/25 kg; power clean 50/35 kg; primer metcon con wall ball RX; Helen RX repetida como hito con objetivo de mejora de marca.',
                'Fase 3 (Intensificación): jerk + clean a cargas de test; ensayo de CF6/CF7 a dosis máximas; Helen RX como último ensayo antes del test; volumen de tracción dinámica alto pero acotado.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit: O-lifts y deadlifts pesados nunca en fatiga alta; el Power Clean de N5-N6 es técnico y con barra ligera-moderada; dolor matinal >2/10 = congelar el gesto un ciclo. En WODs con carga, cuando falla la forma se baja la dosis (§10.4).',
                'Puertas CF6 y CF7: se evalúan en el test de este nivel (requisito efectivo del N7: HSPU, handstand, deadlifts en fatiga, O-lifts). Ensayos integrados: bloque muñeca CF6 en Bloque 0 de todas las sesiones (dosis máximas en F3) y bisagra bajo fatiga en los WODs con swing (sesiones A/C).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Push jerk técnico 30/20 kg; power clean 45/30 kg', 'note' => 'Wall ball a diana de referencia; Helen RX como hito (sin exigencia)', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Push jerk 40/25 kg; power clean 50/35 kg', 'note' => 'Primer metcon con wall ball RX; Helen RX repetida con objetivo de mejora', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Jerk + clean a cargas de test', 'note' => 'Ensayo de CF6/CF7 a dosis máximas; Helen RX como último ensayo', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (CF6/CF7 a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Power Clean (50/35 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción y front rack sin dolor y sin lumbar rota (CF3/CF7); rep con técnica rota no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Push Jerk (45/30 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Impulso de piernas + caída bajo la barra con codos rápidos; bloqueo overhead estable (CF2); si el jerk se convierte en press roto, la rep no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Wall Ball (9/6 kg)', 'minimum' => 15, 'target' => 25, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Wall ball a diana de referencia RX con sentadilla profunda válida (CF1/CF2); si el throw se acorta, baja el peso de la bola', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Helen (benchmark RX)', 'minimum' => 1200, 'target' => 960, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Helen en modalidad RX (scalingUsed = rx): 3 rondas de 400 m Run + Kettlebell Swing Russian ×21 (24/16 kg) + Pull Up ×12; admite kipping controlado o estricta, sin banda; primer benchmark RX completo del programa', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Helen'],
                        ['name' => 'Puerta CF6 — Muñeca en extensión', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Wrist Push-Up 2×10 suave + apoyo en extensión 60 s sin dolor matinal >2/10', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF6'],
                        ['name' => 'Puerta CF7 — Bisagra lumbar controlada', 'minimum' => 1, 'target' => null, 'unit' => 'boolean', 'form' => 'Dead Bug 2×10/lado + Bird Dog 2×8/lado con pelvis quieta; bisagra bajo fatiga (KB swing ligero) sin perder curva neutra', 'type' => TestItemType::PuertaMovilidad->value, 'gateCode' => 'CF7'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL6(): array
    {
        return [
            [
                'code' => 'cf-n6-power-clean-50-35',
                'name' => 'Power clean de 50/35 kg',
                'description' => 'Supera el mínimo de Power Clean a 50/35 kg (5 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Power Clean (50/35 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-push-jerk-45-30',
                'name' => 'Push jerk de 45/30 kg',
                'description' => 'Supera el mínimo de Push Jerk a 45/30 kg (5 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Push Jerk (45/30 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-wall-ball-rx-15',
                'name' => 'Wall ball RX de 15',
                'description' => 'Supera el mínimo de Wall Ball a 9/6 kg a diana RX (15 reps) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🏐', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Wall Ball (9/6 kg)',
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n6-primer-wod-rx',
                'name' => 'Primer WOD RX',
                'description' => 'Registra Helen en modalidad RX dentro de un envío de test del Nivel 6. Celebra el primer benchmark completo en RX del usuario.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Helen (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n6-puerta-cf6',
                'name' => 'Puerta CF6 superada',
                'description' => 'Supera la puerta de movilidad CF6 (Muñeca en extensión) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF6 — Muñeca en extensión',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-puerta-cf7',
                'name' => 'Puerta CF7 superada',
                'description' => 'Supera la puerta de movilidad CF7 (Bisagra lumbar controlada) en el test de Nivel 6.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Puerta CF7 — Bisagra lumbar controlada',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n6-primer-push-jerk',
                'name' => 'Primer push jerk',
                'description' => 'Primer Push Jerk con recepción bajo la barra y bloqueo overhead estable.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n6-clean-jerk-ligero',
                'name' => 'Clean & jerk ligero',
                'description' => 'Primer metcon completado con Power Clean + Push Jerk en la misma sesión (EMOM de sesión B F2).',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n6-helen-rx-registrada',
                'name' => 'Helen RX registrada',
                'description' => 'Primera ejecución completa de Helen en RX en sesión (hito de sesión C F2, antes del test).',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n6-muneca-extension-60s',
                'name' => 'Muñeca de extensión 60 s',
                'description' => 'Primer apoyo en extensión de 60 s sin dolor matinal >2/10 (ensayo CF6).',
                'category' => 'skill', 'icon' => '🖐️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 7 — CONSOLIDACIÓN RX
    // ========================================================================

    private static function level7(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): thruster 35/25 kg de técnica; power clean 55/40 kg; push jerk 50/35 kg; kipping pull-up en bloques de 5-8 (tope 40/sesión); Double Unders en sets sueltos dentro de WODs cortos; primer Thruster en metcon.',
                'Fase 2 (Progresión): thruster 40/30 kg; power clean 60/45 kg; push jerk 55/40 kg; primer Fran parcial (thruster 40/30 + Kipping Pull Up 21-15-9); dobles en sets de 5-8 dentro de AMRAPs; tope de kipping 50/sesión.',
                'Fase 3 (Intensificación): thruster RX 43/29 kg ensayando el gesto de Fran; cargas de test en PC/PJ; re-test informal CF2/CF3 a dosis máximas; Fran RX como hito de sesión C; últimos ensayos de dobles en fatiga.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (kipping): tope 50 reps/sesión en F2-F3, con una sesión completa entre exposiciones intensas; dolor de hombro matinal >2/10 = volver a Pull Up estricta un ciclo.',
                'Re-test informal CF2/CF3 en Bloque 0 de F3 (Shoulder Dislocate ×15 + Overhead Squat PVC ×10; front rack con barra vacía 30 s/lado): es informativo; si falla, se baja la carga de trabajo y se repite el ensayo. No genera mobility_blocked.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Thruster 35/25 kg de técnica; primer thruster en metcon', 'note' => 'Power clean 55/40; push jerk 50/35; kipping en bloques (tope 40/sesión)', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Thruster 40/30; power clean 60/45; push jerk 55/40', 'note' => 'Primer Fran parcial; dobles en sets de 5-8; tope de kipping 50/sesión', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Thruster RX 43/29 ensayando Fran; cargas de test en PC/PJ', 'note' => 'Re-test informal CF2/CF3; Fran RX como hito de sesión C', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Power Clean (60/45 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción y front rack sin dolor y sin lumbar rota (CF3/CF7); rep con técnica rota no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Push Jerk (55/40 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Impulso + caída bajo la barra; bloqueo overhead estable (CF2); si se convierte en press roto, la rep no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Back Squat (80/60 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad con talones pegados (CF1) en cada rep', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Fran (benchmark RX)', 'minimum' => 780, 'target' => 600, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Fran en modalidad RX (scalingUsed = rx): 21-15-9 de Thruster 43/29 kg + Pull Up; pull-ups admitidas estrictas o kipping controlado, sin banda; timeCap 600 s como techo duro', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Fran'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL7(): array
    {
        return [
            [
                'code' => 'cf-n7-primera-fran-rx',
                'name' => 'Primera Fran RX',
                'description' => 'Registra Fran en modalidad RX dentro de un envío de test del Nivel 7. El hito central del nivel: el WOD más icónico de CrossFit completado en RX.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Fran (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n7-power-clean-60-45',
                'name' => 'Power clean de 60/45 kg',
                'description' => 'Supera el mínimo de Power Clean a 60/45 kg (3 reps) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Power Clean (60/45 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n7-push-jerk-55-40',
                'name' => 'Push jerk de 55/40 kg',
                'description' => 'Supera el mínimo de Push Jerk a 55/40 kg (3 reps) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Push Jerk (55/40 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n7-sentadilla-80-60',
                'name' => 'Sentadilla de 80/60 kg',
                'description' => 'Supera el mínimo de Back Squat a 80/60 kg (3 reps) en el test de Nivel 7.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Back Squat (80/60 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n7-primer-thruster-rx',
                'name' => 'Primer thruster RX',
                'description' => 'Primer Thruster con 43/29 kg y técnica válida (rack CF3 + bloqueo CF2).',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n7-dobles-metcon-sin-tope',
                'name' => 'Dobles en metcon sin tope',
                'description' => 'Primer AMRAP completado con Double Unders oficiales (sets acotados dentro del WOD, sin volver a singles).',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n7-kipping-20',
                'name' => 'Kipping de 20 seguidas',
                'description' => 'Primer set de 20 Kipping Pull Up sin romper el hollow.',
                'category' => 'skill', 'icon' => '🌀', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n7-clean-jerk-completo',
                'name' => 'Clean & jerk completo',
                'description' => 'Primer Clean and Jerk con clean recibido en power y jerk bloqueado en la misma unidad.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n7-retest-cf2-cf3',
                'name' => 'Re-test CF2/CF3 superado',
                'description' => 'Re-test informal de overhead y front rack a dosis máximas en Fase 3, sin síntomas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 8 — POTENCIA
    // ========================================================================

    private static function level8(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): snatch de posiciones (Tall Snatch → Hang Snatch) con 20/15 kg; OHS con barra vacía → 30/20 kg; HSPU progresión: Wall Walk y apoyo en pared; thruster RX 43/29 en metcons cortos; dobles en sets de 8-10.',
                'Fase 2 (Progresión): Power Snatch 30/25 kg; OHS 35/25 kg; Wall Handstand Push Up (negativa lenta o parciales con colchoneta); primer metcon con OHS ligero; primer Power Snatch en EMOM.',
                'Fase 3 (Intensificación): power snatch 40/30 kg ensayando el test; OHS 43/30 kg (carga de Nancy) en fuerza; primeros Strict HSPU parciales o Wall Handstand Push Up completos; Grace RX como hito de sesión C; push jerk 60/43 kg ensayando la carga del test.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (overhead y muñeca a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (HSPU): solo progresiones estrictas/negativas en este nivel — no hay kipping HSPU (exige base estricta consolidada: eso es N10+). Tope de volumen de pared: 30-40 reps/sesión entre Wall Walk, holds y HSPU parciales.',
                'Regla §1.7-CrossFit §10.3 (muñeca): el overhead de N8 (OHS, HSPU, snatch) hereda CF6 superada, pero el dolor matinal >2/10 en extensión sigue congelando snatch y HSPU un ciclo. El Snatch completo de suelo queda para el tramo élite (N10+).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Snatch de posiciones (tall → hang) 20/15 kg; OHS barra vacía → 30/20 kg', 'note' => 'HSPU progresión (Wall Walk y apoyo en pared); thruster RX en metcons cortos', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Power Snatch 30/25 kg; OHS 35/25 kg', 'note' => 'Wall Handstand Push Up (negativas/parciales); primer metcon con OHS ligero', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Power snatch 40/30 ensayando el test; OHS 43/30 en fuerza', 'note' => 'Grace RX como hito de sesión C; push jerk 60/43', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (overhead y muñeca a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Power Snatch (40/30 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción overhead amplia y estable, sin press-out marcado y sin dolor (CF2/CF6); desde hang o suelo, indistinto en este nivel (el snatch completo de suelo es N10+); se acepta Power Snatch recibida en power', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Overhead Squat (43/30 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Profundidad con CF1+CF2 válidas; si la barra se tira hacia delante fuera del plano medio, la rep no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Push Jerk (60/43 kg)', 'minimum' => 1, 'target' => 3, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'La carga coincide con el peso RX de Grace: es la validación de que el benchmark es ejecutable rep a rep', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Grace (benchmark RX)', 'minimum' => 600, 'target' => 480, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Grace en modalidad RX (scalingUsed = rx): 30 Clean and Jerk a 60/43 kg por tiempo; se admite power clean + push jerk (ejecución RX estándar según el benchmark); timeCap 600 s como techo duro', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Grace'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL8(): array
    {
        return [
            [
                'code' => 'cf-n8-grace-rx',
                'name' => 'Grace RX registrada',
                'description' => 'Registra Grace en modalidad RX dentro de un envío de test del Nivel 8. Segundo Girl RX completo del programa.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Grace (benchmark RX)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n8-power-snatch-40-30',
                'name' => 'Power snatch de 40/30 kg',
                'description' => 'Supera el mínimo de Power Snatch a 40/30 kg (3 reps) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Power Snatch (40/30 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n8-ohs-43-30',
                'name' => 'Overhead squat de 43/30 kg',
                'description' => 'Supera el mínimo de Overhead Squat a 43/30 kg (3 reps) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Overhead Squat (43/30 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n8-push-jerk-60-43',
                'name' => 'Push jerk de 60/43 kg',
                'description' => 'Supera el mínimo de Push Jerk a 60/43 kg (1 rep) en el test de Nivel 8.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Push Jerk (60/43 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n8-primer-snatch',
                'name' => 'Primer snatch',
                'description' => 'Primer Power Snatch recibido en power con bloqueo overhead estable.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n8-primer-hspu',
                'name' => 'Primer HSPU',
                'description' => 'Primer Wall Handstand Push Up completo (o Strict HSPU) con rango mínimo validado y muñeca sin dolor.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n8-dobles-de-20',
                'name' => 'Dobles de 20',
                'description' => 'Primer set de 20 Double Unders sin fallo.',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n8-ohs-43-30-sesion',
                'name' => 'OHS de 43/30 en sesión',
                'description' => 'Primer Overhead Squat con la carga RX de referencia (43/30 kg) fuera de test.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n8-hspu-negativa-5s',
                'name' => 'HSPU negativa de 5 segundos',
                'description' => 'Primera HSPU Negative (o Wall Handstand Push Up negativa) con bajada de 5 s controlada.',
                'category' => 'skill', 'icon' => '⏳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 9 — MOTOR ÉLITE
    // ========================================================================

    private static function level9(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): deadlift de trabajo 80/60 kg vuelve a la sesión A; C2B progresión (agarre amplio); anillas: false grip, soporte, Ring Row - Elevated Feet; Daniel escalado como primer Hero (hito de sesión C); dobles en sets de 10-15.',
                'Fase 2 (Progresión): deadlift 90/65 kg; Chest to Bar Pull Up primeros reps; anillas: Ring Dip y Assisted Muscle Up Transition / Muscle Up Negative; primer WOD con OHS RX en metcon largo; Aguja de Dobles como hito de sesión D.',
                'Fase 3 (Intensificación): deadlift 100/70 kg ensayando el test; re-test informal CF6/CF7 a dosis máximas; C2B en AMRAP con tope; Josh RX como hito de sesión C (segunda y última toma antes del test); volumen de anillas acotado.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (anillas y bisagra a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (volumen balístico): tope 60 reps/sesión entre kipping pull-up, C2B y T2B en F2-F3; la progresión a anillas sustituye, no suma: cuando entra C2B volumen, baja una serie de kipping pull-up. Dolor de hombro matinal >2/10 = volver a Pull Up estricta un ciclo.',
                'Re-test informal CF6/CF7 en Bloque 0 de F3 (ensayo a dosis máximas): informativo; si falla CF7, el deadlift de trabajo baja 10 kg hasta re-superarla. No genera mobility_blocked (decisión 1).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Deadlift de trabajo 80/60 kg; C2B progresión (agarre amplio)', 'note' => 'Anillas: false grip, soporte, Ring Row - Elevated Feet; Daniel escalado como primer Hero', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Deadlift 90/65 kg; primeros Chest to Bar Pull Up', 'note' => 'Anillas: Ring Dip y transición asistida; Aguja de Dobles como hito de sesión D', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Deadlift 100/70 kg ensayando el test; C2B en AMRAP', 'note' => 'Re-test informal CF6/CF7; Josh RX como hito (última toma antes del test)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (anillas y bisagra a dosis F4)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Deadlift (100/70 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'CF7 validada en el ensayo informal de F3; barra pegada, bloqueo sin hiperextensión; si la lumbar se va, la rep no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Thruster (43/29 kg)', 'minimum' => 5, 'target' => 10, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Thruster RX con rack limpio (CF3) y bloqueo overhead (CF2)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Chest to Bar Pull Up', 'minimum' => 3, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Barbilla claramente sobre la barra (contacto pecho-barra o inmediatamente por encima); barbilla en la barra = kipping pull-up, no cuenta como C2B', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Josh (benchmark RX)', 'minimum' => 1710, 'target' => 1410, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Josh en modalidad RX (scalingUsed = rx): 21-15-9 de Overhead Squat 43/29 kg + pull-ups ×42-30-18; pull-ups admitidas estrictas o kipping controlado; el OHS exige CF1+CF2 válidas en profundidad; timeCap 1800 s', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Josh'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL9(): array
    {
        return [
            [
                'code' => 'cf-n9-josh-rx',
                'name' => 'Josh RX registrada',
                'description' => 'Registra Josh en modalidad RX dentro de un envío de test del Nivel 9. Cierra el tramo RX con un Hero WOD completo: el usuario es atleta RX.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Josh (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n9-deadlift-100-70',
                'name' => 'Deadlift de 100/70 kg',
                'description' => 'Supera el mínimo de Deadlift a 100/70 kg (3 reps) en el test de Nivel 9.',
                'category' => 'progress', 'icon' => '🔩', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Deadlift (100/70 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n9-thruster-rx-10',
                'name' => 'Thruster RX de 10',
                'description' => 'Supera el mínimo de Thruster a 43/29 kg (5 reps) en el test de Nivel 9.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Thruster (43/29 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n9-primer-c2b-test',
                'name' => 'Primer chest-to-bar de test',
                'description' => 'Supera el mínimo de Chest to Bar Pull Up (3 reps) en el test de Nivel 9.',
                'category' => 'progress', 'icon' => '🎯', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Chest to Bar Pull Up',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n9-primer-c2b',
                'name' => 'Primer chest-to-bar',
                'description' => 'Primer Chest to Bar Pull Up con la barbilla claramente sobre la barra.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n9-primer-hero-wod',
                'name' => 'Primer Hero WOD',
                'description' => 'Primera ejecución completa de un Hero WOD (Daniel escalado de sesión C F2).',
                'category' => 'skill', 'icon' => '🎖️', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n9-aguja-de-dobles',
                'name' => 'Aguja de Dobles registrada',
                'description' => 'Primera ejecución del benchmark Aguja de Dobles con dobles oficiales (3 estaciones: Devil Press, Toes to Bar y Double Unders).',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n9-false-grip-20s',
                'name' => 'False grip de 20 segundos',
                'description' => 'Primer False Grip Hang de 20 s sin dolor de muñeca/flexores.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n9-soporte-anillas-20s',
                'name' => 'Primer soporte en anillas de 20 segundos',
                'description' => 'Primer Ring Support Hold de 20 s con anillas quietas y hombros activos.',
                'category' => 'skill', 'icon' => '💍', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n9-transicion-mu-asistida',
                'name' => 'Primera transición de muscle-up asistida',
                'description' => 'Primer Assisted Muscle Up Transition completo (pecho sobre las anillas, rotación de muñecas incluida).',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n9-dobles-50',
                'name' => 'Dobles de 50 acumulados',
                'description' => 'Primer WOD completado acumulando 50+ Double Unders sin fallo grave de técnica.',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 10 — PRIMERA MUSCLE-UP
    // ========================================================================

    private static function level10(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): squat clean técnico con 50/35 kg (desde Power Clean + Front Squat combinados); anillas: Assisted Muscle Up Transition y Muscle Up Negative a dosis altas; HSPU estricto con tope (sin kipping); primer Hang Power Clean de 65/45 kg en fuerza.',
                'Fase 2 (Progresión): squat clean 55/40 kg; Muscle Up Negative 5 s + primeros intentos de Ring Muscle Up (estricta, pies asistidos si hace falta); re-test informal CF6 antes del primer kipping HSPU; Kipping Handstand Push Up con tope 20/sesión; hang power clean 70/50 kg.',
                'Fase 3 (Intensificación): squat clean 60/43 kg ensayando el test y la carga de Elizabeth; re-test informal CF3; primera Ring Muscle Up completa como hito de sesión B; kipping HSPU con tope 30/sesión; Elizabeth RX como hito de sesión C.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (anillas y muñeca a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (primero estricto): la primera muscle-up se estrena estricta (transición completa controlada) antes de permitir el kipping en volumen; el kipping HSPU llega en N10 solo tras tres niveles de HSPU estricto/negativo, con tope de volumen de 30-40 reps/sesión.',
                'Dosificación del volumen invertido (HSPU + MU): tope 40-50 reps/sesión en F2-F3; una sesión completa entre exposiciones intensas de hombro. Dolor matinal de hombro/muñeca/flexores >2/10 → vuelta a estricto (HSPU) y transición asistida (MU) un ciclo.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Squat clean técnico 50/35; anillas: transición asistida y negativas', 'note' => 'HSPU estricto con tope (sin kipping); primer Hang Power Clean 65/45', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Squat clean 55/40; primeros intentos de Ring Muscle Up', 'note' => 'Re-test informal CF6; kipping HSPU tope 20/sesión; HPC 70/50', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Squat clean 60/43 ensayando el test; primera MU como hito', 'note' => 'Re-test informal CF3; kipping HSPU tope 30; Elizabeth RX como hito', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (anillas y muñeca)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Ring Muscle Up', 'minimum' => 1, 'target' => 3, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Transición completa (pecho sobre las anillas, rotación de muñecas) con bloqueo en soporte; saltar la transición ("pez" sin rotación) no cuenta (§10.4); se acepta la primera MU estricta o kipping controlada', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Squat Clean (60/43 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción en sentadilla profunda (CF1+CF3); recepción cortada = Power Clean, no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Strict HSPU', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Rango mínimo validado (cabeza al suelo/colchoneta del nivel de trabajo N8-N9); sin kipping en este ítem: es el ancla estricta (§10.1)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Elizabeth (benchmark RX)', 'minimum' => 840, 'target' => 660, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Elizabeth en modalidad RX (scalingUsed = rx): 21-15-9 de Squat Clean 60/43 kg + Ring Dip; umbrales bajo el timeCap 900 s del benchmark', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Elizabeth'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL10(): array
    {
        return [
            [
                'code' => 'cf-n10-primera-muscle-up',
                'name' => 'Primera muscle-up',
                'description' => 'Supera el mínimo de Ring Muscle Up (1 rep) en el test de Nivel 10. El hito central del nivel y del gimnástico del programa: la primera Ring Muscle Up completa.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Ring Muscle Up',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n10-squat-clean-60-43',
                'name' => 'Squat clean de 60/43 kg',
                'description' => 'Supera el mínimo de Squat Clean a 60/43 kg (3 reps) en el test de Nivel 10.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Squat Clean (60/43 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n10-hspu-estricto-test',
                'name' => 'HSPU estricto de test',
                'description' => 'Supera el mínimo de Strict HSPU (3 reps) en el test de Nivel 10.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Strict HSPU',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n10-elizabeth-rx',
                'name' => 'Elizabeth RX registrada',
                'description' => 'Registra Elizabeth en modalidad RX (mínimo 14:00) dentro de un envío de test del Nivel 10.',
                'category' => 'progress', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Elizabeth (benchmark RX)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n10-primera-rmu-sesion',
                'name' => 'Primera Ring Muscle Up (fuera de test)',
                'description' => 'Primer Ring Muscle Up completo en sesión (hito de sesión B F3).',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n10-primera-mu-estricta',
                'name' => 'Primera muscle-up estricta',
                'description' => 'Primer Ring Muscle Up sin kipping, con transición controlada de principio a fin.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n10-primer-kipping-hspu',
                'name' => 'Primer kipping HSPU',
                'description' => 'Primer Kipping Handstand Push Up con rango mínimo y hollow controlado, tras la base estricta.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n10-primera-elizabeth-rx',
                'name' => 'Primera Elizabeth RX',
                'description' => 'Primera ejecución completa de Elizabeth en RX (sesión C F3).',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n10-ring-dip-10',
                'name' => 'Ring dip de 10',
                'description' => 'Primer set de 10 Ring Dip sin romper el rango.',
                'category' => 'skill', 'icon' => '💍', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n10-transicion-asistida-5s',
                'name' => 'Transición asistida de 5 segundos',
                'description' => 'Primer Assisted Muscle Up Transition con 5 s de pausa con el pecho sobre las anillas.',
                'category' => 'skill', 'icon' => '⏳', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
            [
                'code' => 'cf-n10-negativa-mu-8s',
                'name' => 'Negativa de muscle-up de 8 segundos',
                'description' => 'Primera Muscle Up Negative con bajada de 8 s controlada.',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 11 — PESADO
    // ========================================================================

    private static function level11(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): snatch de suelo técnico: Snatch Balance + Squat Snatch con 40/30 kg; C&J 60/43 kg; back squat 90/70 kg; anillas a mantenimiento (1 MU suelto por sesión en B); DT descompuesto en piezas.',
                'Fase 2 (Progresión): squat snatch 45/35 kg; C&J 65/45 kg; back squat 95/70 kg; primer DT completo a 60/43 kg (escalado) en sesión C; Ring Muscle Up kipping admitida en volumen bajo.',
                'Fase 3 (Intensificación): squat snatch 55/40 kg ensayando el test; C&J 70/47.5 kg ensayando el test; re-test informal CF2/CF6; back squat 100/70 kg ensayo; DT RX como hito de sesión C.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (overhead y enganche a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.2 (lumbar): el pesado pasa a los O-lifts recibidos en squat y el DT RX (deadlift 70/52 kg en metcon — carga técnica moderada en fatiga, con CF7 vigente). Nunca O-lift pesado en metcon: en WODs entran a carga de trabajo o Power Snatch/Hang Power Clean ligeros.',
                'Dosificación: 1-2 RIR en todo el trabajo pesado; singles de squat snatch y C&J con descanso completo (150-180 s en F3). Una sesión completa entre bisagras pesadas (back squat A / snatch B) y entre overheads pesados (C&J C / snatch B) — §10.5.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Snatch de suelo técnico (Snatch Balance + Squat Snatch 40/30)', 'note' => 'C&J 60/43; back squat 90/70; anillas a mantenimiento; DT descompuesto', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Squat snatch 45/35; C&J 65/45; back squat 95/70', 'note' => 'Primer DT completo escalado en sesión C; MU kipping en volumen bajo', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Squat snatch 55/40 y C&J 70/47.5 ensayando el test', 'note' => 'Re-test informal CF2/CF6; back squat 100/70; DT RX como hito', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (overhead y enganche)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Squat Snatch (55/40 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción en Overhead Squat profundo (CF1+CF2), sin press-out marcado; recepción cortada = Power Snatch, no cuenta (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Clean and Jerk (70/47.5 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción del clean en squat (CF1+CF3) + jerk bloqueado (CF2); la unidad completa en un solo gesto encadenado', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Back Squat (100/70 kg)', 'minimum' => 3, 'target' => 5, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'CF1 en cada rep; si la profundidad se pierde en la serie, la serie termina', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'DT (benchmark RX)', 'minimum' => 1320, 'target' => 1080, 'unit' => 'time', 'direction' => 'lte', 'form' => 'DT en modalidad RX (scalingUsed = rx): 5 rondas de 12 Deadlift + 9 Hang Power Clean + 6 Push Jerk a 70/52 kg (sin timeCap en catálogo); el deadlift táctil-and-go sin rebote (CF7), hang clean en power, jerk en bloqueo', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DT'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL11(): array
    {
        return [
            [
                'code' => 'cf-n11-dt-rx',
                'name' => 'DT RX registrada',
                'description' => 'Registra DT en modalidad RX dentro de un envío de test del Nivel 11. El primer Hero con barra pesada RX: el hito del nivel "Pesado".',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DT (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n11-squat-snatch-55-40',
                'name' => 'Snatch de suelo de 55/40 kg',
                'description' => 'Supera el mínimo de Squat Snatch a 55/40 kg (3 reps) en el test de Nivel 11.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Squat Snatch (55/40 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n11-cj-70-475',
                'name' => 'Clean & jerk de 70/47.5 kg',
                'description' => 'Supera el mínimo de Clean and Jerk a 70/47.5 kg (3 reps) en el test de Nivel 11.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Clean and Jerk (70/47.5 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n11-sentadilla-100-70',
                'name' => 'Sentadilla de 100/70 kg',
                'description' => 'Supera el mínimo de Back Squat a 100/70 kg (3 reps) en el test de Nivel 11.',
                'category' => 'progress', 'icon' => '🦵', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Back Squat (100/70 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n11-primer-squat-snatch',
                'name' => 'Primer Squat Snatch completo',
                'description' => 'Primer Squat Snatch desde el suelo con recepción en profundidad y bloqueo overhead.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n11-primer-cj-rx',
                'name' => 'Primer Clean & Jerk RX completo',
                'description' => 'Primer Clean and Jerk unificado (squat clean + jerk) con la carga RX 70/47.5 fuera de test.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n11-primer-dt-escalado',
                'name' => 'Primer DT escalado completo',
                'description' => 'Primer DT completo a carga de trabajo (sesión C F2).',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n11-primer-dt-rx',
                'name' => 'Primer DT RX',
                'description' => 'Primera ejecución de DT en RX (sesión C F3, antes del test).',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n11-mu-doble',
                'name' => 'Muscle-up doble',
                'description' => 'Primer set de 2 Ring Muscle Up sin bajar de las anillas entre rep y rep.',
                'category' => 'skill', 'icon' => '💍', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n11-retest-cf2-cf6',
                'name' => 'Re-test CF2/CF6 superado',
                'description' => 'Re-test informal de overhead y muñeca a dosis máximas en F3, sin síntomas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 12 — HEROES
    // ========================================================================

    private static function level12(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): volumen gimnástico con tope: Ring Muscle Up en EMOMs (tope 8/sesión), Kipping Handstand Push Up en metcons (tope 30/sesión), C2B en fatiga; O-lifts en metcon a 50/35 kg máx. (§10.2); primer Hero de volumen moderado.',
                'Fase 2 (Progresión): MU en WODs con tope 10-12/sesión; kipping HSPU con tope 40; C2B ×10+ en AMRAPs; Whitten RX como hito de sesión C; primeros WODs con C&J ligero en fatiga.',
                'Fase 3 (Intensificación): toques de test: 5 MU acumuladas, 8 HSPU kipping, C&J 70/47.5; re-test informal CF6/CF7; Nate RX como hito de sesión C (segunda y última toma antes del test).',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (volumen balístico): tope 80 reps/sesión entre MU, kipping HSPU, C2B y T2B en F2-F3; una sesión completa entre exposiciones intensas de hombro. Dolor matinal >2/10 → vuelta a variantes estrictas/asistidas un ciclo.',
                'Regla §1.7-CrossFit §10.2 (O-lifts en metcon): nunca superan 60/43 kg (carga de trabajo técnica). Excepción deliberada: los Heroes oficiales del catálogo (Whitten, Nate) según su definición; la regla de seguridad es "técnica rota = se para y se descansa" (§10.4).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Volumen gimnástico con tope (MU en EMOMs, kipping HSPU en metcons)', 'note' => 'O-lifts en metcon a 50/35 máx. (§10.2); primer Hero de volumen moderado', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'MU en WODs (tope 10-12); kipping HSPU (tope 40)', 'note' => 'Whitten RX como hito de sesión C; C&J ligero en fatiga', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Toques de test: 5 MU, 8 HSPU kipping, C&J 70/47.5', 'note' => 'Re-test informal CF6/CF7; Nate RX como hito (última toma)', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Ring Muscle Up', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Acumuladas en series de 1-2 con descanso entre series; transición completa con bloqueo en soporte (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Kipping Handstand Push Up', 'minimum' => 8, 'target' => 12, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Rango mínimo validado con hollow/kipping controlado; si el cuerpo se arquea o la cabeza no llega, la rep no cuenta', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Clean and Jerk (70/47.5 kg)', 'minimum' => 5, 'target' => 8, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción en squat + jerk bloqueado; reps acumuladas en series de 1-2 (línea S2OH: Grace → Push Jerk 60/43 → C&J 70/47.5)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Nate (benchmark RX)', 'minimum' => 5, 'target' => 8, 'unit' => 'rounds', 'direction' => 'gte', 'form' => 'Nate en modalidad RX (scalingUsed = rx): AMRAP 20 min de 2 Ring Muscle Up + 4 Handstand Push Up + 8 Kettlebell Swing American (32/24 kg); HSPU con kipping controlado; MU con transición completa (pez sin rotación no cuenta, §10.4); swings con bisagra CF7', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Nate'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL12(): array
    {
        return [
            [
                'code' => 'cf-n12-nate-rx',
                'name' => 'Nate RX registrada',
                'description' => 'Registra Nate en modalidad RX (mínimo 5 rondas) dentro de un envío de test del Nivel 12. El cierre del arco del muscle-up: el benchmark oficial de MU + HSPU + potencia, completado en RX.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Nate (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n12-mu-de-5',
                'name' => 'Muscle-up de 5',
                'description' => 'Supera el mínimo de Ring Muscle Up (5 reps) en el test de Nivel 12.',
                'category' => 'progress', 'icon' => '💍', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Ring Muscle Up',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n12-hspu-kipping-8',
                'name' => 'HSPU kipping de 8',
                'description' => 'Supera el mínimo de Kipping Handstand Push Up (8 reps) en el test de Nivel 12.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Kipping Handstand Push Up',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n12-cj-70-475-de-5',
                'name' => 'Clean & jerk de 70/47.5 kg de 5',
                'description' => 'Supera el mínimo de Clean and Jerk a 70/47.5 kg (5 reps) en el test de Nivel 12.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Clean and Jerk (70/47.5 kg)',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n12-primer-hero-5-rondas',
                'name' => 'Primer Hero de 5 rondas',
                'description' => 'Primera ejecución completa de Whitten en RX (sesión C F2): 5 rondas densas de 22 reps × 4 + 400 m run.',
                'category' => 'skill', 'icon' => '🎖️', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n12-primera-nate-rx',
                'name' => 'Primera Nate RX',
                'description' => 'Primera ejecución del benchmark Nate en RX (sesión C F3, antes del test).',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n12-mu-en-wod',
                'name' => 'Muscle-up en WOD',
                'description' => 'Primer metcon completado con Ring Muscle Up oficiales (tope respetado, transición limpia).',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n12-doble-mu',
                'name' => 'Doble de muscle-ups',
                'description' => 'Primer set de 2 Ring Muscle Up sin bajar de las anillas (repetible).',
                'category' => 'skill', 'icon' => '💍', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n12-hspu-kipping-10',
                'name' => 'HSPU kipping de 10',
                'description' => 'Primer set de 10 Kipping Handstand Push Up con rango y hollow controlados.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n12-c2b-10-fatiga',
                'name' => 'C2B de 10 en fatiga',
                'description' => 'Primer set de 10 Chest to Bar Pull Up dentro de un WOD, no en fuerza.',
                'category' => 'skill', 'icon' => '🎯', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n12-retest-cf6-cf7',
                'name' => 'Re-test CF6/CF7 superado',
                'description' => 'Re-test informal de muñeca y bisagra a dosis máximas en F3, sin síntomas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 13 — FUERZA COMPETICIÓN
    // ========================================================================

    private static function level13(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): deadlift 140/100 kg; squat snatch 60/45 kg; C&J 70/50 kg; Deficit Deadlift técnico 100/70 kg; HSPU deficit ligero (placa fina) ×3; MU en mantenimiento (3×2/sesión en B); Amanda descompuesta (MU sueltos + snatch 50/35 kg).',
                'Fase 2 (Progresión): deadlift 155/110 kg; squat snatch 70/52 kg; C&J 80/55 kg; Deficit Deadlift 120/85 kg; HSPU deficit ×5 con tope 20; primer Amanda escalado (snatch 55/40, MU asistidas) en sesión C; Deficit Handstand Push Up entra en WODs.',
                'Fase 3 (Intensificación): deadlift 170/120 kg ensayando el test; squat snatch 80/60 kg ensayo; C&J 90/60 kg ensayo; re-test informal CF2/CF6; Amanda RX como hito de sesión C (segunda y última toma antes del test).',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista (bisagra y overhead a dosis F4); test de nivel al final.',
                'Regla §1.7-CrossFit §10.2 (lumbar): el deadlift vuelve al test en N13 (170/120 kg) tras dos niveles de mantenimiento, construido por Deficit Deadlift y RDL de fuerza. En metcon, bisagra solo a cargas técnicas (swings, deadlifts ≤ 100/70 en fatiga) con CF7 vigente.',
                'Dosificación: 1-2 RIR en todo el trabajo pesado; singles de squat snatch y C&J con descanso completo (150-180 s en F3). Una sesión completa entre bisagras pesadas (deadlift A / Deficit Deadlift D) y entre overheads pesados (snatch B / C&J C) — §10.5.',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Deadlift 140/100; squat snatch 60/45; C&J 70/50', 'note' => 'Deficit Deadlift técnico 100/70; HSPU deficit ligero; Amanda descompuesta', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'Deadlift 155/110; squat snatch 70/52; C&J 80/55', 'note' => 'HSPU deficit tope 20; primer Amanda escalado en sesión C', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Deadlift 170/120 ensayando el test; snatch 80/60; C&J 90/60', 'note' => 'Re-test informal CF2/CF6; Amanda RX como hito', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista (bisagra y overhead)', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Deadlift (170/120 kg)', 'minimum' => 1, 'target' => 3, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Bloqueo completo, barra pegada, sin rebote (CF5+CF7); agarre libre; el objetivo de 3 reps acumuladas en series de 1 con 60 s entre singles', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Squat Snatch (80/60 kg)', 'minimum' => 1, 'target' => 3, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción en Overhead Squat profundo (CF1+CF2), sin press-out marcado (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Clean and Jerk (90/60 kg)', 'minimum' => 1, 'target' => 2, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción del clean en squat (CF1+CF3) + jerk bloqueado (CF2); la unidad completa en un solo gesto encadenado', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Amanda (benchmark RX)', 'minimum' => 570, 'target' => 450, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Amanda en modalidad RX (scalingUsed = rx): 9-7-5 de Ring Muscle Up + Squat Snatch 60/43 kg; MU con transición completa (pez sin rotación no cuenta, §10.4); umbrales bajo el timeCap 600 s del benchmark', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Amanda'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL13(): array
    {
        return [
            [
                'code' => 'cf-n13-amanda-rx',
                'name' => 'Amanda RX registrada',
                'description' => 'Registra Amanda en modalidad RX (mínimo 9:30) dentro de un envío de test del Nivel 13. El primer benchmark del tramo de competición: habilidad gimnástica + potencia olímpica bajo cronómetro.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Amanda (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n13-deadlift-170-120',
                'name' => 'Deadlift de 170/120 kg',
                'description' => 'Supera el mínimo de Deadlift a 170/120 kg (1 rep) en el test de Nivel 13. La carga absoluta más alta del programa.',
                'category' => 'progress', 'icon' => '🔩', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Deadlift (170/120 kg)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n13-snatch-80-60',
                'name' => 'Snatch de 80/60 kg',
                'description' => 'Supera el mínimo de Squat Snatch a 80/60 kg (1 rep) en el test de Nivel 13. La marca élite de snatch del programa.',
                'category' => 'progress', 'icon' => '🏋️', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Squat Snatch (80/60 kg)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n13-cj-90-60',
                'name' => 'Clean & jerk de 90/60 kg',
                'description' => 'Supera el mínimo de Clean and Jerk a 90/60 kg (1 rep) en el test de Nivel 13.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Clean and Jerk (90/60 kg)',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n13-primer-snatch-80-60',
                'name' => 'Primer snatch de 80/60 kg',
                'description' => 'Primer Squat Snatch completo con la carga élite fuera de test.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n13-primer-cj-90-60',
                'name' => 'Primer C&J de 90/60 kg',
                'description' => 'Primer Clean and Jerk unificado con la carga élite fuera de test.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n13-primer-deadlift-170-120',
                'name' => 'Primer deadlift de 170/120 kg',
                'description' => 'Primer single del deadlift de test en sesión (A F3).',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n13-primer-hspu-deficit',
                'name' => 'Primer HSPU en deficit',
                'description' => 'Primer Deficit Handstand Push Up con rango completo y hollow controlado.',
                'category' => 'skill', 'icon' => '🤸', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n13-primera-amanda-escalada',
                'name' => 'Primera Amanda escalada',
                'description' => 'Primer Amanda completo a carga de trabajo (sesión C F2).',
                'category' => 'skill', 'icon' => '⏱️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n13-primera-amanda-rx',
                'name' => 'Primera Amanda RX',
                'description' => 'Primera ejecución de Amanda en RX (sesión C F3, antes del test).',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n13-mu-triple',
                'name' => 'Muscle-up triple',
                'description' => 'Primer set de 3 Ring Muscle Up sin bajar de las anillas.',
                'category' => 'skill', 'icon' => '💍', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n13-retest-cf2-cf6',
                'name' => 'Re-test CF2/CF6 superado',
                'description' => 'Re-test informal de overhead y muñeca a dosis máximas en F3, sin síntomas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 14 — MOTOR COMPETICIÓN
    // ========================================================================

    private static function level14(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): volumen gimnástico con tope: Ring Muscle Up 3×2 + Bar Muscle Up técnica (primer contacto), C2B ×10 en fatiga, T2B ×12; primer WOD largo de 25\'; Gunny escalado (chaleco 10/7.5 kg o sin chaleco) como hito intermedio.',
                'Fase 2 (Progresión): MU en sets de 2-3 en WODs largos (tope 12/sesión); Bar Muscle Up en volumen (sets de 2-3); C2B ×12 en AMRAPs; Gunny RX como hito de sesión C (chaleco 20/15 kg, timeCap 2400 s); primeros dobles/triples de Hang Power Clean en fatiga (§10.2).',
                'Fase 3 (Intensificación): toques de test: C2B ×25 acumuladas, T2B ×30, MU ×8 en sets de 2; re-test informal CF1/CF3; Daniel RX como hito de sesión C (segunda y última toma antes del test); Jerry RX como WOD de mantenimiento de motor.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (volumen balístico): tope 100 reps/sesión entre MU, kipping HSPU, C2B y T2B en F2-F3; una sesión completa entre exposiciones intensas de hombro. Dolor matinal >2/10 → vuelta a variantes estrictas/asistidas un ciclo.',
                'Dosificación de los WODs largos (30-40\'): ritmo de competición 80-85% del ritmo de carrera, negativas solo en la última tercera parte. Los O-lifts en metcon nunca superan 60/43 kg (§10.2).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Volumen gimnástico con tope (MU 3×2 + BMU técnica)', 'note' => 'C2B ×10 en fatiga; primer WOD largo de 25\'; Gunny escalado como hito intermedio', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'MU en sets de 2-3 en WODs largos; BMU en volumen', 'note' => 'Gunny RX como hito de sesión C (chaleco 20/15 kg)', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'Toques de test: C2B ×25, T2B ×30, MU ×8', 'note' => 'Re-test informal CF1/CF3; Daniel RX como hito; Jerry RX de mantenimiento', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Chest to Bar Pull Up', 'minimum' => 25, 'target' => 35, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Acumuladas en series de 5-8 con descanso breve; pecho sobre la barra en cada rep (§10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Toes to Bar', 'minimum' => 30, 'target' => 40, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Acumuladas en series de 8-10; los pies tocan la barra, no la carga de impulso', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Ring Muscle Up', 'minimum' => 8, 'target' => 12, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Acumuladas en series de 2-3 con descanso completo entre series; transición completa con bloqueo en soporte (pez sin rotación no cuenta, §10.4)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Daniel (benchmark RX)', 'minimum' => 1140, 'target' => 930, 'unit' => 'time', 'direction' => 'lte', 'form' => 'Daniel en modalidad RX (scalingUsed = rx): 50 Pull Up + 400 m Run + 21 Thruster (43/29 kg) + 800 m Run + 21 Thruster (43/29 kg) + 400 m Run + 50 Pull Up; thrusters con bloqueo overhead (CF2); umbrales bajo el timeCap 1200 s del benchmark', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'Daniel'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL14(): array
    {
        return [
            [
                'code' => 'cf-n14-daniel-rx',
                'name' => 'Daniel RX registrado',
                'description' => 'Registra Daniel en modalidad RX (mínimo 19:00) dentro de un envío de test del Nivel 14. El Hero grande del tramo de motor: 50 pull-ups + barra + carrera en formato piramidal.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Daniel (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n14-c2b-25',
                'name' => 'C2B de 25 en test',
                'description' => 'Supera el mínimo de Chest to Bar Pull Up (25 reps) en el test de Nivel 14.',
                'category' => 'progress', 'icon' => '🎯', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Chest to Bar Pull Up',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n14-t2b-30',
                'name' => 'T2B de 30 en test',
                'description' => 'Supera el mínimo de Toes to Bar (30 reps) en el test de Nivel 14.',
                'category' => 'progress', 'icon' => '🤸', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Toes to Bar',
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n14-mu-8-fatiga',
                'name' => 'Muscle-up de 8 en fatiga',
                'description' => 'Supera el mínimo de Ring Muscle Up (8 reps) en el test de Nivel 14.',
                'category' => 'progress', 'icon' => '💍', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Ring Muscle Up',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n14-primer-hero-chaleco',
                'name' => 'Primer Hero con chaleco RX',
                'description' => 'Primera ejecución completa de Gunny en RX (sesión C F2): dos millas con chaleco 20/15 kg.',
                'category' => 'skill', 'icon' => '🎖️', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n14-primer-daniel-rx',
                'name' => 'Primer Daniel RX',
                'description' => 'Primera ejecución de Daniel en RX (sesión C F3, antes del test).',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n14-primer-jerry-rx',
                'name' => 'Primer Jerry RX',
                'description' => 'Primera ejecución de Jerry en RX (sesión D F3): 1 milla + 2K remo (Rowing Machine) + 1 milla.',
                'category' => 'skill', 'icon' => '🚣', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n14-primer-hero-sub-intermediate',
                'name' => 'Primer Hero sub-intermediate',
                'description' => 'Primer Hero RX por debajo de la referencia intermediate del catálogo (Gunny < 32:30, Daniel < 18:00 o Jerry < 27:00).',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n14-primera-bmu',
                'name' => 'Primera Bar Muscle Up',
                'description' => 'Primer Bar Muscle Up completo con throw-over limpio.',
                'category' => 'skill', 'icon' => '🏆', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n14-dobles-mu-wod',
                'name' => 'Dobles de muscle-up en WOD',
                'description' => 'Primer set de 2 Ring Muscle Up dentro de un WOD de más de 15 minutos.',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n14-dobles-100',
                'name' => 'Dobles de 100',
                'description' => 'Primer set continuo de 100 Double Unders sin fallo.',
                'category' => 'skill', 'icon' => '🪢', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n14-retest-cf1-cf3',
                'name' => 'Re-test CF1/CF3 superado',
                'description' => 'Re-test informal de sentadilla profunda y front rack a dosis máximas en F3, sin síntomas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }

    // ========================================================================
    // LEVEL 15 — PICO
    // ========================================================================

    private static function level15(): array
    {
        return [
            'tips' => [
                'Fase 1 (Base): primer evento combinado simulado (formato Games): C&J moderado (70/50 kg, primer evento) + WOD gimnástico (segundo) + DT escalado (tercero); MU en sets de 2-3 (tope 12/sesión); C&J 80/55 kg en fuerza.',
                'Fase 2 (Progresión): C&J 90/60 kg; MU en sets de 3-4 (tope 15); segundo evento combinado con C&J 80/55 + MU + DT a 60/43 kg; HSPU deficit en metcons (tope 40/sesión).',
                'Fase 3 (Intensificación): C&J 100/65 kg ensayando el test; re-test informal CF6/CF7; tercer evento combinado de competición (formato exacto del test: C&J pesado + MU volumen + DT RX) como hito de sesión C.',
                'Fase 4 (Recuperación): mitad de volumen; movilidad protagonista; test de nivel al final.',
                'Regla §1.7-CrossFit §10.1 (volumen balístico): tope 120 reps/sesión en F2-F3; una sesión completa entre exposiciones intensas de hombro. Dolor matinal >2/10 → vuelta a variantes estrictas/asistidas un ciclo.',
                'Test en formato evento combinado (orden de competición): el C&J se ejecuta primero; tras 5-8 min de transición, las MU acumuladas; tras otros 5-8 min, el DT RX. Es el mismo formato de la sesión C F3 — el usuario ya lo ha competido dos veces en el nivel. Los ensayos del F3 se separan del test del F4 por al menos 48 h reales de recuperación activa (§10.5).',
            ],
            'trainingWeeks' => [
                ['week' => 0, 'name' => 'Fase 1: Base', 'focus' => 'Primer evento combinado simulado (C&J + gimnástico + DT escalado)', 'note' => 'MU en sets de 2-3 (tope 12/sesión); C&J 80/55 en fuerza', 'intensity' => '55%'],
                ['week' => 1, 'name' => 'Fase 2: Progresión', 'focus' => 'C&J 90/60; segundo evento combinado (C&J 80/55 + MU + DT 60/43)', 'note' => 'MU en sets de 3-4 (tope 15); HSPU deficit en metcons', 'intensity' => '80%'],
                ['week' => 2, 'name' => 'Fase 3: Intensificación', 'focus' => 'C&J 100/65 ensayando el test; tercer evento combinado (formato exacto)', 'note' => 'Re-test informal CF6/CF7; DT RX como cierre del torneo simulado', 'intensity' => '95%'],
                ['week' => 3, 'name' => 'Fase 4: Recuperación', 'focus' => 'Mitad de volumen; movilidad protagonista', 'note' => 'Test de nivel al final (evento combinado)', 'intensity' => '50%'],
            ],
            'testWeek' => [
                'tests' => [
                    'requirements' => [
                        ['name' => 'Clean and Jerk (100/65 kg)', 'minimum' => 1, 'target' => 2, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Recepción del clean en squat (CF1+CF3) + jerk bloqueado (CF2); el objetivo de 2 reps acumuladas en series de 1 con 60-90 s entre singles; PRIMER EVENTO del test combinado', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'Ring Muscle Up', 'minimum' => 12, 'target' => 18, 'unit' => 'reps', 'direction' => 'gte', 'form' => 'Acumuladas en series de 2-4 con descanso completo entre series; transición completa con bloqueo en soporte (pez sin rotación no cuenta, §10.4); SEGUNDO EVENTO del test combinado (5-8 min de transición)', 'type' => TestItemType::Fuerza->value],
                        ['name' => 'DT (benchmark RX)', 'minimum' => 960, 'target' => 750, 'unit' => 'time', 'direction' => 'lte', 'form' => 'DT en modalidad RX (scalingUsed = rx): 5 rondas de 12 Deadlift + 9 Hang Power Clean + 6 Push Jerk a 70/52 kg (sin timeCap en catálogo); deadlift táctil-and-go sin rebote (CF7), hang clean en power, jerk en bloqueo; TERCER EVENTO del test combinado — la marca se hace con el C&J y las MU ya en el cuerpo', 'type' => TestItemType::Benchmark->value, 'benchmarkName' => 'DT'],
                    ],
                ],
            ],
        ];
    }

    private static function achievementsL15(): array
    {
        return [
            [
                'code' => 'cf-n15-dt-rx-a-fondo',
                'name' => 'DT RX a fondo',
                'description' => 'Registra DT en modalidad RX (mínimo 16:00) dentro de un envío de test del Nivel 15. El DT RX por debajo de 16:00 tras un C&J de 100/65 y 12 muscle-ups: la marca del atleta de competición.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'DT (benchmark RX)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n15-cj-100-65',
                'name' => 'Clean & jerk de 100/65 kg',
                'description' => 'Supera el mínimo de Clean and Jerk a 100/65 kg (1 rep) en el test de Nivel 15. La barra más pesada del programa.',
                'category' => 'progress', 'icon' => '💪', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => true,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Clean and Jerk (100/65 kg)',
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n15-mu-12',
                'name' => 'Muscle-up de 12 en volumen',
                'description' => 'Supera el mínimo de Ring Muscle Up (12 reps) en el test de Nivel 15.',
                'category' => 'progress', 'icon' => '💍', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_TEST,
                'specialCelebration' => false,
                'requirementType' => Achievement::REQ_TEST_ITEM_MIN, 'requirementKey' => 'Ring Muscle Up',
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n15-primer-cj-100-65',
                'name' => 'Primer C&J de 100/65 kg',
                'description' => 'Primer Clean and Jerk completo con la carga élite fuera de test.',
                'category' => 'skill', 'icon' => '👑', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => true,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n15-primer-evento-combinado',
                'name' => 'Primer evento combinado completado',
                'description' => 'Primer formato de tres eventos en una sesión (sesión C F1) con los tres resultados anotados.',
                'category' => 'skill', 'icon' => '🏟️', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n15-primer-dt-rx-tras-cj',
                'name' => 'Primer DT RX tras C&J pesado',
                'description' => 'Primer DT RX ejecutado como tercer evento de un combinado (sesión C F3).',
                'category' => 'skill', 'icon' => '🔥', 'tier' => Achievement::TIER_LEGENDARIO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 200,
            ],
            [
                'code' => 'cf-n15-mu-15',
                'name' => 'Muscle-up de 15 acumuladas',
                'description' => 'Primer entrenamiento con 15+ Ring Muscle Up acumuladas en sesión (tope superado).',
                'category' => 'skill', 'icon' => '💪', 'tier' => Achievement::TIER_EPICO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 150,
            ],
            [
                'code' => 'cf-n15-ohs-80-60',
                'name' => 'OHS de 80/60 kg',
                'description' => 'Primer Overhead Squat completo con la carga del nivel.',
                'category' => 'skill', 'icon' => '🏋️', 'tier' => Achievement::TIER_RARO, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 100,
            ],
            [
                'code' => 'cf-n15-retest-cf6-cf7',
                'name' => 'Re-test CF6/CF7 superado',
                'description' => 'Re-test informal de muñeca y bisagra a dosis máximas en F3, sin síntomas.',
                'category' => 'progress', 'icon' => '🚪', 'tier' => Achievement::TIER_COMUN, 'type' => Achievement::TYPE_HITO,
                'specialCelebration' => false,
                'requirementType' => null, 'requirementKey' => null,
                'xpReward' => 50,
            ],
        ];
    }
}
