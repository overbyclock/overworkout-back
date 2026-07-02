<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Training;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Entity\UserTrainingLog;
use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use App\Repository\UserLevelProgressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class TrainingSessionApiTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?UserLevelProgressRepository $progressRepository = null;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->progressRepository = $this->entityManager->getRepository(UserLevelProgress::class);
        $this->createTestUserAndLogin();
    }

    private function createTestUserAndLogin(): void
    {
        $this->testUser = new User();
        $this->testUser->setNick('sessiontest');
        $this->testUser->setEmail('session@test.com');
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
                'email' => 'session@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function createTrainingProgram(string $name = 'Test Program', string $slug = 'test-program'): TrainingProgram
    {
        $program = new TrainingProgram();
        $program->setName($name);
        $program->setSlug($slug);
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        return $program;
    }

    private function createTrainingLevel(TrainingProgram $program, int $levelNumber = 1): TrainingLevel
    {
        $level = new TrainingLevel();
        $level->setProgram($program);
        $level->setLevelNumber($levelNumber);
        $level->setName('Nivel '.$levelNumber);

        $this->entityManager->persist($level);
        $this->entityManager->flush();

        return $level;
    }

    private function createTraining(string $name = 'Test Training', ?TrainingLevel $level = null, ?int $weekNumber = null): Training
    {
        $training = new Training();
        $training->setName($name);
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setTarget(TargetWorkout::STRENGTH);
        $training->setTrainingLevel($level);
        $training->setWeekNumber($weekNumber);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        return $training;
    }

    public function testStartSessionForFreeTraining(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Sesión iniciada correctamente', $data['message']);
        $this->assertNotNull($data['log']['startedAt']);
        $this->assertNull($data['log']['completedAt']);
        $this->assertSame($training->getId(), $data['log']['training']['id']);
    }

    public function testCompleteSessionForFreeTraining(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/complete',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['durationSeconds' => 1200, 'xpEarned' => 75])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Sesión completada correctamente', $data['message']);
        $this->assertNotNull($data['log']['completedAt']);
        $this->assertSame(1200, $data['log']['durationSeconds']);
        $this->assertSame(75, $data['log']['xpEarned']);

        $this->entityManager->clear();
        $user = $this->entityManager->getRepository(User::class)->find($this->testUser->getId());
        $this->assertSame(75, $user->getXp());
        $this->assertSame(1, $user->getTotalWorkouts());
    }

    public function testStartSessionActivatesLevelProgress(): void
    {
        $program = $this->createTrainingProgram();
        $level = $this->createTrainingLevel($program);
        $training = $this->createTraining('Level Training', $level);

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $progress = $this->progressRepository->findByUserAndLevel($this->testUser, $level);
        $this->assertNotNull($progress);
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $progress->getStatus());
    }

    public function testCompleteSessionAdvancesWeekWhenPhaseCompleted(): void
    {
        $program = $this->createTrainingProgram();
        $level = $this->createTrainingLevel($program);
        $trainingA = $this->createTraining('Training A', $level, 0);
        $trainingB = $this->createTraining('Training B', $level, 0);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $progress->setCurrentWeek(0);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        // Completar el primer entrenamiento no debe avanzar la semana.
        $this->client->request(
            'POST',
            '/user/trainings/'.$trainingA->getId().'/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->client->request(
            'POST',
            '/user/trainings/'.$trainingA->getId().'/complete',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->entityManager->clear();
        $progress = $this->progressRepository->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(0, $progress->getCurrentWeek());

        // Completar el segundo entrenamiento debe avanzar la semana.
        $this->client->request(
            'POST',
            '/user/trainings/'.$trainingB->getId().'/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->client->request(
            'POST',
            '/user/trainings/'.$trainingB->getId().'/complete',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->entityManager->clear();
        $progress = $this->progressRepository->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(1, $progress->getCurrentWeek());
    }

    public function testGetNextSessionReturnsContinue(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->client->request(
            'GET',
            '/user/trainings/next',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('continue', $data['session']['type']);
        $this->assertSame($training->getId(), $data['session']['training']['id']);
    }

    public function testGetNextSessionReturnsNextPending(): void
    {
        $program = $this->createTrainingProgram();
        $level = $this->createTrainingLevel($program);
        $completedTraining = $this->createTraining('Completed Training', $level, 0);
        $pendingTraining = $this->createTraining('Pending Training', $level, 0);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $progress->setCurrentWeek(0);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $log = new UserTrainingLog();
        $log->setUser($this->testUser);
        $log->setTraining($completedTraining);
        $log->setSource(UserTrainingLog::SOURCE_PROGRAM);
        $log->setCompletedAt(new \DateTimeImmutable());
        $log->setXpEarned(50);
        $this->entityManager->persist($log);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/user/trainings/next',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('next', $data['session']['type']);
        $this->assertSame($pendingTraining->getId(), $data['session']['training']['id']);
        $this->assertSame(0, $data['session']['weekNumber']);
    }

    public function testStartSessionReturns404ForNonExistentTraining(): void
    {
        $this->client->request(
            'POST',
            '/user/trainings/999999/start',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Entrenamiento no encontrado', $data['message']);
    }

    public function testCompleteSessionReturns400WhenNoActiveSession(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/complete',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(400, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('No hay una sesión activa para este entrenamiento.', $data['error']);
    }
}
