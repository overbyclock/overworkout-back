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
use App\Entity\UserTrainingLog;
use App\Enum\Discipline;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserStatsApiTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?Training $testTraining = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $this->createTestTraining();
        $this->createTestUserAndLogin();
    }

    private function createTestTraining(): void
    {
        $program = new TrainingProgram();
        $program->setName('Stats Test Program');
        $program->setDescription('Test description');
        $program->setSlug('stats-test-program');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');
        $this->entityManager->persist($program);

        $level = new TrainingLevel();
        $level->setProgram($program);
        $level->setLevelNumber(1);
        $level->setName('Level 1');
        $level->setTitle('Level 1');
        $level->setEstimatedDurationWeeks(4);
        $level->setProgramVersion('v2');
        $this->entityManager->persist($level);

        $training = new Training();
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setName('Stats Test Training');
        $training->setTrainingLevel($level);
        $this->entityManager->persist($training);

        // Desde Task 3, iniciar sesión exige que el entrenamiento tenga contenido.
        $exercise = new Exercises();
        $exercise->setName('Stats Test Exercise');
        $exercise->setPrimaryMuscleGroup(MuscleGroup::CHEST);
        $exercise->setLevel(Levels::BEGINNER);
        $this->entityManager->persist($exercise);

        $config = new TrainingExerciseConfiguration();
        $config->setExercise($exercise);
        $config->setReps(10);
        $config->setSets(1);
        $config->setRestBetweenSets(60);

        $round = new TrainingRound();
        $round->setSetsForRound(1);
        $round->setRestBetweenRounds(60);
        $round->addTrainingExerciseConfiguration($config);
        $training->addTrainingRound($round);

        $this->entityManager->flush();

        $this->testTraining = $training;
    }

    private function createTestUserAndLogin(): void
    {
        $this->testUser = new User();
        $this->testUser->setNick('statstest');
        $this->testUser->setEmail('stats@test.com');
        $this->testUser->setPassword($this->passwordHasher->hashPassword($this->testUser, 'Test123!'));
        $this->testUser->setRoles(['ROLE_USER']);

        $this->entityManager->persist($this->testUser);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'stats@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    public function testGetDashboardStatsReturnsInitialZeros(): void
    {
        $this->client->request(
            'GET',
            '/user/stats',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame(0, $data['streakDays']);
        $this->assertSame(0, $data['weeklyWorkouts']);
        $this->assertSame(0, $data['xp']);
        $this->assertSame(0, $data['totalWorkouts']);
        $this->assertSame(1, $data['athleteLevel']);
    }

    public function testCompleteTrainingCreatesLogAndUpdatesStats(): void
    {
        $this->client->request(
            'POST',
            '/user/trainings/'.$this->testTraining->getId().'/start',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ]
        );

        $this->client->request(
            'POST',
            '/user/trainings/'.$this->testTraining->getId().'/complete',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Sesión completada correctamente', $data['message']);
        $this->assertSame('program', $data['log']['source']);

        $this->entityManager->clear();
        $user = $this->entityManager->getRepository(User::class)->find($this->testUser->getId());
        $this->assertSame(50, $user->getXp());
        $this->assertSame(1, $user->getTotalWorkouts());
    }

    public function testCompleteTrainingWithCustomPayload(): void
    {
        $this->client->request(
            'POST',
            '/user/trainings/'.$this->testTraining->getId().'/start',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ]
        );

        $this->client->request(
            'POST',
            '/user/trainings/'.$this->testTraining->getId().'/complete',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'durationSeconds' => 1800,
                'xpEarned' => 75,
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame(75, $data['log']['xpEarned']);
        $this->assertSame(1800, $data['log']['durationSeconds']);
    }

    public function testCompleteTrainingReturns404ForMissingTraining(): void
    {
        $this->client->request(
            'POST',
            '/user/trainings/99999/complete',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDashboardStatsReflectsCompletedWorkouts(): void
    {
        $today = new \DateTimeImmutable();

        $log = new UserTrainingLog();
        $log->setUser($this->testUser);
        $log->setTraining($this->testTraining);
        $log->setCompletedAt($today);
        $log->setXpEarned(50);
        $this->testUser->addXp(50);
        $this->testUser->incrementWorkouts();
        $this->entityManager->persist($log);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/user/stats',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame(1, $data['streakDays']);
        $this->assertSame(1, $data['weeklyWorkouts']);
        $this->assertSame(50, $data['xp']);
        $this->assertSame(1, $data['totalWorkouts']);
    }

    public function testUnauthenticatedRequestsAreRejected(): void
    {
        $this->client->request('GET', '/user/stats');
        $this->assertSame(401, $this->client->getResponse()->getStatusCode());

        $this->client->request(
            'POST',
            '/user/trainings/'.$this->testTraining->getId().'/complete'
        );
        $this->assertSame(401, $this->client->getResponse()->getStatusCode());
    }
}
