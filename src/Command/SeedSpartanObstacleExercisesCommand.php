<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Equipments;
use App\Entity\Exercises;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Seed de los ejercicios de obstáculos Spartan para Spartan OCR Prep.
 *
 * Fichas nuevas del catálogo (obstáculos clásicos de Spartan Race, temporada
 * 2026): Monkey Bars, Spear Throw, Bucket Carry y Barbed Wire Crawl.
 * Idempotente: find-or-update por nombre. Los obstáculos ya existentes del
 * catálogo (Rope Climb, Farmers Walk, Tire Flip, Wall Walk, Bear Crawl...)
 * no se tocan.
 */
#[AsCommand(
    name: 'app:seed-spartan-obstacle-exercises',
    description: 'Crea/actualiza los ejercicios de obstáculos Spartan (Monkey Bars, Spear Throw, Bucket Carry, Barbed Wire Crawl)',
)]
class SeedSpartanObstacleExercisesCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Spartan OCR Prep — Seed de ejercicios de obstáculos');

        $created = 0;
        $updated = 0;

        foreach ($this->getExercisesData() as $data) {
            $exercise = $this->em->getRepository(Exercises::class)
                ->findOneBy(['name' => $data['name']]);

            if (null === $exercise) {
                $exercise = new Exercises();
                $exercise->setName($data['name']);
                ++$created;
            } else {
                ++$updated;
            }

            $exercise->setPrimaryMuscleGroup($data['primaryMuscleGroup']);
            $exercise->setSecondaryMuscleGroup($data['secondaryMuscleGroup'] ?? null);
            $exercise->setLevel($data['level']);
            $exercise->setDifficultyRating($data['difficultyRating']);
            $exercise->setDisciplines(['crossfit']);
            $exercise->setDescription($data['description']);
            $exercise->setSetupInstructions($data['setup']);
            $exercise->setExecutionInstructions($data['execution']);
            $exercise->setMovementStandards($data['standards']);
            $exercise->setCommonFaults($data['commonFaults']);
            $exercise->setSafetyTips($data['safetyTips']);

            if (null !== ($data['equipment'] ?? null)) {
                $equipment = $this->em->getRepository(Equipments::class)
                    ->findOneBy(['name' => $data['equipment']]);
                if (null === $equipment) {
                    $this->io->warning(\sprintf('Equipamiento "%s" no encontrado para "%s".', $data['equipment'], $data['name']));
                } else {
                    $exercise->setEquipment($equipment);
                }
            }

            $this->em->persist($exercise);
        }

        $this->em->flush();

        $this->io->success(\sprintf('Ejercicios creados: %d · actualizados: %d', $created, $updated));

        return Command::SUCCESS;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getExercisesData(): array
    {
        return [
            [
                'name' => 'Monkey Bars',
                'primaryMuscleGroup' => MuscleGroup::BACK,
                'secondaryMuscleGroup' => MuscleGroup::FOREARMS,
                'level' => Levels::INTERMEDIATE,
                'difficultyRating' => 3,
                'equipment' => 'Barra de dominadas',
                'description' => 'Travesía de barras paralelas suspendidas: obstáculo clásico de Spartan Race. Se cruza colgando con un columpio controlado de barra en barra, alternando agarres. Exige agarre, espalda y coordinación; la progresión parte del dead hang y del columpio de un solo brazo.',
                'setup' => "1. Cológate en la primera barra con agarre completo y hombros activos (lejos de las orejas).\n2. Balancea el cuerpo suavemente hacia delante.\n3. Mira la siguiente barra, no tus manos.",
                'execution' => "1. Con el columpio, suelta una mano en el punto más alto del balanceo.\n2. Agarra la siguiente barra y deja que el cuerpo oscile.\n3. Alterna manos con el columpio; no fuerces la travesía estático.\n4. Termina con los pies juntos en la plataforma de salida.",
                'standards' => "Travesía completa sin pisar el suelo.\nColumpio controlado (sin balanceo descontrolado).\nHombros lejos de las orejas.\nSin rebote en las muñecas en la recepción.",
                'commonFaults' => ['Cruzar estático, brazo a brazo, hasta agotar el agarre', 'Balanceo excesivo que te tira de la barra', 'Mirar las manos en vez de la siguiente barra', 'Saltar a la primera barra con muñecas rígidas'],
                'safetyTips' => 'Progresión: dead hang 60 s → colgador alterno → travesía asistida → completa. Alternativa sin estructura: Dead Hang con nota. Dolor matinal >2/10 en hombro/muñeca = bajar volumen un ciclo (regla §1.7).',
            ],
            [
                'name' => 'Spear Throw',
                'primaryMuscleGroup' => MuscleGroup::SHOULDERS,
                'secondaryMuscleGroup' => MuscleGroup::CORE,
                'level' => Levels::INTERMEDIATE,
                'difficultyRating' => 2,
                'equipment' => null,
                'description' => 'Lanzamiento de lanza o jabalina corta a una diana (7,6 m en Spartan Race). Se lanza con un paso cruzado y el brazo completo; el fallo en carrera cuesta 30 burpees. Obstáculo de técnica, no de fuerza: el que entrena el estándar antes que la velocidad no paga burpees.',
                'setup' => "1. Posición de lanzador: pie de lanzamiento atrás, hombro orientado a la diana.\n2. Lanza apoyada junto a la oreja, codo alto.\n3. Fija la mirada en el centro de la diana, no en la lanza.",
                'execution' => "1. Da un paso cruzado hacia la diana con el pie contrario al brazo de lanzamiento.\n2. Abre la cadera y traza el brazo por encima del hombro.\n3. Suelta la lanza alto, con dedos detrás de la punta.\n4. El cuerpo completo apunta a la diana al terminar.",
                'standards' => "La lanza queda clavada en la diana (diana de 7,6 m en competición).\nPaso cruzado visible.\nCuerpo orientado a la diana al soltar.\nSuelta por encima del hombro, no a la altura del pecho.",
                'commonFaults' => ['Lanzar solo con el brazo (sin paso ni cadera)', 'Soltar la lanza en horizontal', 'Fijarse en la lanza y no en la diana', 'Olvidar el re-intento: en carrera solo hay un lanzamiento'],
                'safetyTips' => 'Solo se lanza a diana despejada. Alternativa sin lanza: Medicine Ball Chest Throw a diana (o mochila cargada) con la misma técnica de paso cruzado. Dolor de hombro al lanzar = técnica o descanso, nunca más fuerza bruta.',
            ],
            [
                'name' => 'Bucket Carry',
                'primaryMuscleGroup' => MuscleGroup::LEGS,
                'secondaryMuscleGroup' => MuscleGroup::FOREARMS,
                'level' => Levels::INTERMEDIATE,
                'difficultyRating' => 3,
                'equipment' => 'Mancuernas',
                'description' => 'Obstáculo de carga de Spartan Race: caminar con un cubo cargado de arena/peso (20-30 kg en competición) a pecho o con los brazos extendidos, por un recorrido con desnivel y barro. Trabaja piernas, agarre y tronco bajo carga real.',
                'setup' => "1. Levanta el cubo con la espalda neutra: rodillas fuera, cadera atrás.\n2. Acomódalo a pecho (agarre de oso) o agárralo con los brazos extendidos por los laterales.\n3. Postura alta antes de caminar.",
                'execution' => "1. Camina con pasos cortos y erguidos, mirada al frente.\n2. Aprieta el cubo contra el pecho (o activa los lats con los brazos rectos).\n3. En las subidas acorta el paso; en las bajadas no corras.\n4. Si el agarre falla, posa el cubo: no lo sueltes por el desnivel.",
                'standards' => "Recorrido completo sin soltar el cubo.\nEspalda neutra en todo momento.\nPostura erguida, pasos cortos.\nEl cubo no toca el suelo salvo en la posada planificada.",
                'commonFaults' => ['Agarrar el cubo con los brazos colgando (se desliza)', 'Espalda redondeada en la salida del suelo', 'Prisa en el desnivel: el barro castiga', 'Soltar el cubo cuesta la penalización: 30 burpees'],
                'safetyTips' => 'Alternativa sin cubo: Kettlebell Goblet Lunge o Suitcase Carry con nota "bucket carry". Levanta siempre con la espalda neutra. Dolor lumbar >2/10 al día siguiente = menos carga, más técnica.',
            ],
            [
                'name' => 'Barbed Wire Crawl',
                'primaryMuscleGroup' => MuscleGroup::CORE,
                'secondaryMuscleGroup' => MuscleGroup::SHOULDERS,
                'level' => Levels::BEGINNER,
                'difficultyRating' => 2,
                'equipment' => null,
                'description' => 'Gateo bajo alambre de espino (15-30 m en Spartan Race): avanzar a cuatro puntas con la cadera al mínimo, antebrazos en el suelo, por barro o césped. Obstáculo de fondo y técnica: quien gatea bajo y rítmico descansa; quien se apoya alto paga el cuello y la espalda.',
                'setup' => "1. A cuatro puntas bajo la línea del alambre: antebrazos apoyados, cadera baja.\n2. Espalda plana, cuello neutro: mira el suelo 1 m por delante.\n3. Respiración rítmica: el gateo es un metrónomo.",
                'execution' => "1. Avanza la mano y la rodilla contrarias al mismo tiempo.\n2. Mantén la cadera al mínimo sin rozar el suelo si hay barro hondo.\n3. En las subidas, empuja con los antebrazos; en las bajadas, controla.\n4. Sal de rodillas antes de incorporarte: no te levantes de golpe bajo el alambre.",
                'standards' => "Recorrido completo sin tocarse el alambre.\nCadera baja, espalda plana.\nAvance rítmico y continuo.\nIncorporación de salida controlada.",
                'commonFaults' => ['Cadera alta: se engancha la espalda o el dorsal', 'Gateo cruzado (mano y rodilla del mismo lado)', 'Aguantar la respiración en los tramos largos', 'Rozar el alambre por prisa'],
                'safetyTips' => 'El equivalente de entrenamiento es Bear Crawl con nota "bajo, cadera al suelo". En barro, protege las rodillas. Dolor en el cuello o lumbar al día siguiente = cadera más baja, ritmo más lento.',
            ],
        ];
    }
}
