<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Entity\Benchmark;
use App\Entity\BenchmarkExercise;
use App\Entity\Exercises;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;

class BenchmarkSeederHelper
{
    private EntityManagerInterface $em;

    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env.local');

        $kernel = new \App\Kernel('dev', true);
        $kernel->boot();

        /** @var \Doctrine\Persistence\ManagerRegistry $doctrine */
        $doctrine = $kernel->getContainer()->get('doctrine');
        $this->em = $doctrine->getManager();
    }

    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    public function findOrCreateExercise(
        string $name,
        ?MuscleGroup $muscleGroup = null,
        ?Levels $level = null,
        int $difficulty = 1
    ): Exercises {
        $repo = $this->em->getRepository(Exercises::class);
        $ex = $repo->findOneBy(['name' => $name]);

        if (null !== $ex) {
            return $ex;
        }

        $ex = new Exercises();
        $ex->setName($name);
        $ex->setPrimaryMuscleGroup($muscleGroup ?? MuscleGroup::FULL_BODY);
        $ex->setLevel($level ?? Levels::NOLEVEL);
        $ex->setDifficultyRating($difficulty);
        $this->em->persist($ex);
        $this->em->flush();

        echo "  🆕 Ejercicio creado: {$name} (id: {$ex->getId()})\n";

        return $ex;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function seedBenchmark(array $data): Benchmark
    {
        $repo = $this->em->getRepository(Benchmark::class);
        $benchmark = $repo->findOneBy(['name' => $data['name']]);

        if (null === $benchmark) {
            $benchmark = new Benchmark();
            $benchmark->setName($data['name']);
            $benchmark->setType($data['type']);
            $this->em->persist($benchmark);
            echo "  🆕 Benchmark creado: {$data['name']}\n";
        } else {
            echo "  🔄 Actualizando benchmark: {$data['name']}\n";
            $benchmark->clearExercises();
            $this->em->flush();
        }

        $benchmark->setDescription($data['description'] ?? null);
        $benchmark->setScalingOptions($data['scaling'] ?? null);
        $benchmark->setVideoUrl($data['video'] ?? null);

        // Tiempos por nivel
        $benchmark->setEliteTimeMale($data['elite_male'] ?? null);
        $benchmark->setEliteTimeFemale($data['elite_female'] ?? null);
        $benchmark->setAdvancedTimeMale($data['advanced_male'] ?? null);
        $benchmark->setAdvancedTimeFemale($data['advanced_female'] ?? null);
        $benchmark->setIntermediateTimeMale($data['intermediate_male'] ?? null);
        $benchmark->setIntermediateTimeFemale($data['intermediate_female'] ?? null);
        $benchmark->setBeginnerTimeMale($data['beginner_male'] ?? null);
        $benchmark->setBeginnerTimeFemale($data['beginner_female'] ?? null);

        $this->em->flush();

        foreach ($data['exercises'] as $exData) {
            $be = new BenchmarkExercise();
            $be->setBenchmark($benchmark);
            $be->setExercise($exData['exercise']);
            $be->setPosition($exData['position']);
            $be->setReps($exData['reps'] ?? null);
            $be->setDistanceMeters($exData['distance'] ?? null);
            $be->setDurationSeconds($exData['duration'] ?? null);
            $be->setCalories($exData['calories'] ?? null);
            $be->setRxWeightMale($exData['rx_weight_male'] ?? null);
            $be->setRxWeightFemale($exData['rx_weight_female'] ?? null);
            $be->setRestSeconds($exData['rest'] ?? null);
            $this->em->persist($be);
        }

        $this->em->flush();
        echo "  ✅ {$data['name']} listo. Ejercicios: " . count($data['exercises']) . "\n";

        return $benchmark;
    }

    /**
     * Actualiza los tiempos femeninos de benchmarks existentes sin tocar sus ejercicios.
     *
     * @param array<string, array<string, string|null>> $fixes
     */
    public function fixFemaleTimes(array $fixes): void
    {
        $repo = $this->em->getRepository(Benchmark::class);

        foreach ($fixes as $name => $times) {
            $benchmark = $repo->findOneBy(['name' => $name]);
            if (null === $benchmark) {
                echo "  ⚠️ Benchmark no encontrado: {$name}\n";
                continue;
            }

            $updated = false;
            $fields = [
                'elite_time_female' => 'setEliteTimeFemale',
                'advanced_time_female' => 'setAdvancedTimeFemale',
                'intermediate_time_female' => 'setIntermediateTimeFemale',
                'beginner_time_female' => 'setBeginnerTimeFemale',
            ];

            foreach ($fields as $key => $setter) {
                if (isset($times[$key]) && null !== $times[$key]) {
                    $benchmark->$setter($times[$key]);
                    $updated = true;
                }
            }

            if ($updated) {
                $this->em->flush();
                echo "  ♀️ Tiempos femeninos actualizados: {$name}\n";
            }
        }
    }
}
