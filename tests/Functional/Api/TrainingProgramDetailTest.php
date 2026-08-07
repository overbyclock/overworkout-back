<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingRound;
use App\Entity\User;
use App\Enum\Discipline;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use App\Enum\TargetWorkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Contrato del detalle de programa (getById / getBySlug): el payload incluye
 * los niveles con sus trainings (datos básicos), pero NO el detalle de rondas
 * y ejercicios — ese lo sirven /training-levels/{id} y /trainings/{id}.
 * Protege el fix de rendimiento: serializar el grafo completo tardaba 14+ s
 * (v4: 6.400 configuraciones de ejercicio) y superaba el timeout del front.
 */
class TrainingProgramDetailTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?string $authToken = null;

    private ?TrainingProgram $testProgram = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $this->createProgramWithGraph();
        $this->createTestUserAndLogin($passwordHasher);
    }

    private function createProgramWithGraph(): void
    {
        $program = new TrainingProgram();
        $program->setName('Test Detail Program');
        $program->setSlug('test-detail-program');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');
        $program->setTotalLevels(2);

        $exercise = new Exercises();
        $exercise->setName('Test Detail Exercise');
        $exercise->setPrimaryMuscleGroup(MuscleGroup::CHEST);
        $exercise->setLevel(Levels::BEGINNER);
        $this->entityManager->persist($exercise);

        foreach ([1, 2] as $levelNumber) {
            $level = new TrainingLevel();
            $level->setProgram($program);
            $level->setLevelNumber($levelNumber);
            $level->setName('Nivel '.$levelNumber);
            $this->entityManager->persist($level);

            $training = new Training();
            $training->setName('Sesión N'.$levelNumber);
            $training->setDiscipline(Discipline::CALISTHENICS);
            $training->setTarget(TargetWorkout::STRENGTH);
            $training->setTrainingLevel($level);
            $this->entityManager->persist($training);

            $round = new TrainingRound();
            $round->setTraining($training);
            $round->setRound(1);
            $round->setRestBetweenRounds(60);
            $this->entityManager->persist($round);

            $config = new TrainingExerciseConfiguration();
            $config->setTrainingRound($round);
            $config->setExercise($exercise);
            $config->setSets(3);
            $config->setRestBetweenSets(30);
            $this->entityManager->persist($config);
        }

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        $this->testProgram = $program;
    }

    private function createTestUserAndLogin(UserPasswordHasherInterface $passwordHasher): void
    {
        $user = new User();
        $user->setNick('detailtest');
        $user->setEmail('detail@test.com');
        $user->setPassword($passwordHasher->hashPassword($user, 'Test123!'));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'detail@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    public function testGetProgramByIdReturnsFullGraph(): void
    {
        $this->client->request(
            'GET',
            '/training-programs/'.$this->testProgram->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertResponseIsSuccessful();
        $this->assertProgramGraph(json_decode($this->client->getResponse()->getContent(), true));
    }

    public function testGetProgramBySlugReturnsFullGraph(): void
    {
        $this->client->request(
            'GET',
            '/training-programs/by-slug/test-detail-program',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertResponseIsSuccessful();
        $this->assertProgramGraph(json_decode($this->client->getResponse()->getContent(), true));
    }

    private function assertProgramGraph(array $data): void
    {
        $this->assertSame('Test Detail Program', $data['name']);
        $this->assertCount(2, $data['levels']);

        $level = $data['levels'][0];
        $this->assertSame('Nivel 1', $level['name']);
        $this->assertArrayHasKey('trainings', $level);
        $this->assertCount(1, $level['trainings']);

        $training = $level['trainings'][0];
        $this->assertSame('Sesión N1', $training['name']);
        $this->assertSame('calisthenics', $training['discipline']);

        // El detalle de rondas/ejercicios ya no viaja en este endpoint
        $this->assertArrayNotHasKey('trainingRounds', $training);
    }
}
