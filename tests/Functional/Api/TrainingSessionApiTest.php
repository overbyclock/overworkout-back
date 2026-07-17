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
use App\Entity\UserLevelProgress;
use App\Entity\UserTrainingLog;
use App\Enum\Discipline;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
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

        $round = new TrainingRound();
        $round->setSetsForRound(1);
        $round->setRestBetweenRounds(60);

        $config = new TrainingExerciseConfiguration();
        $config->setExercise($this->createExercise($name.' Exercise'));
        $config->setReps(10);
        $config->setSets(2);
        $config->setRestBetweenSets(60);
        $config->setRestBetweenExercises(30);

        $round->addTrainingExerciseConfiguration($config);
        $training->addTrainingRound($round);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        return $training;
    }

    private function createExercise(string $name): Exercises
    {
        $exercise = new Exercises();
        $exercise->setName($name);
        $exercise->setPrimaryMuscleGroup(MuscleGroup::CHEST);
        $exercise->setLevel(Levels::BEGINNER);

        $this->entityManager->persist($exercise);

        return $exercise;
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

    public function testGetNextSessionReturnsNullWhenNoActiveSessionOrProgram(): void
    {
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
        $this->assertSame('No hay sesiones pendientes', $data['message']);
        $this->assertNull($data['session']);
    }

    public function testStartSessionCreatesStepsSnapshot(): void
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
        $this->assertArrayHasKey('logId', $data);
        $this->assertSame(0, $data['currentPosition']);
        // Fixture: 2 series + 1 descanso entre series = 3 pasos
        $this->assertCount(3, $data['steps']);
        $this->assertSame('exercise', $data['steps'][0]['type']);
        $this->assertSame('pending', $data['steps'][0]['status']);
        $this->assertSame(10, $data['steps'][0]['payload']['reps']);
        $this->assertSame('rest', $data['steps'][1]['type']);
        $this->assertSame(60, $data['steps'][1]['payload']['restSeconds']);
        $this->assertSame('exercise', $data['steps'][2]['type']);
    }

    public function testStartSessionIsIdempotent(): void
    {
        $training = $this->createTraining();

        $headers = [
            'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            'HTTP_ACCEPT' => 'application/json',
        ];

        $this->client->request('POST', '/user/trainings/'.$training->getId().'/start', [], [], $headers);
        $firstData = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->request('POST', '/user/trainings/'.$training->getId().'/start', [], [], $headers);

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Sesión ya iniciada', $data['message']);
        $this->assertSame($firstData['logId'], $data['logId']);
        $this->assertCount(3, $data['steps']);
    }

    public function testStartSessionSetsFreeSourceForTrainingWithoutLevel(): void
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

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame(UserTrainingLog::SOURCE_FREE, $data['log']['source']);
    }

    public function testGetSessionReturnsStepsAndPosition(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);

        $this->client->request(
            'GET',
            '/user/sessions/'.$logId,
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
        $this->assertSame($logId, $data['logId']);
        $this->assertSame($training->getId(), $data['training']['id']);
        $this->assertSame(0, $data['currentPosition']);
        $this->assertNull($data['completedAt']);
        $this->assertCount(3, $data['steps']);
    }

    public function testGetSessionReturns404ForNonExistentSession(): void
    {
        $this->client->request(
            'GET',
            '/user/sessions/999999',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testGetSessionReturns403ForOtherUsersSession(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);

        $otherUser = new User();
        $otherUser->setNick('otheruser');
        $otherUser->setEmail('other@test.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));
        $otherUser->setRoles(['ROLE_USER']);
        $this->entityManager->persist($otherUser);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'other@test.com', 'password' => 'Test123!'])
        );
        $otherToken = json_decode($this->client->getResponse()->getContent(), true)['token'];

        $this->client->request(
            'GET',
            '/user/sessions/'.$logId,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$otherToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testCompleteStepAdvancesCurrentPosition(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);
        $steps = $this->getSessionSteps($logId);

        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$steps[0]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['action' => 'complete'])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame(1, $data['currentPosition']);
        $this->assertSame('completed', $data['steps'][0]['status']);
        $this->assertNotNull($data['steps'][0]['completedAt']);
    }

    public function testSkipStepMarksAsSkipped(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);
        $steps = $this->getSessionSteps($logId);

        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$steps[0]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['action' => 'skip'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('skipped', $data['steps'][0]['status']);
        $this->assertSame(1, $data['currentPosition']);
    }

    public function testSkipWithExerciseScopeSkipsWholeExercise(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);
        $steps = $this->getSessionSteps($logId);

        // Fixture: ejercicio de 2 series (pasos 0 y 2) con descanso intermedio (paso 1)
        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$steps[0]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['action' => 'skip', 'scope' => 'exercise'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('skipped', $data['steps'][0]['status']);
        $this->assertSame('skipped', $data['steps'][1]['status']);
        $this->assertSame('skipped', $data['steps'][2]['status']);
        // Sin pasos pendientes: el puntero queda tras el último paso
        $this->assertSame(3, $data['currentPosition']);
    }

    public function testStepActionReturns404ForStepFromAnotherSession(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);

        $otherTraining = $this->createTraining('Other Training');
        $otherLogId = $this->startSessionAndGetLogId($otherTraining);
        $otherSteps = $this->getSessionSteps($otherLogId);

        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$otherSteps[0]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['action' => 'complete'])
        );

        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testStepActionReturns409WhenSessionCompleted(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);
        $steps = $this->getSessionSteps($logId);

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/complete',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['durationSeconds' => 300])
        );

        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$steps[0]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['action' => 'complete'])
        );

        $this->assertSame(409, $this->client->getResponse()->getStatusCode());
    }

    public function testSkipWithExerciseScopeOnRestStepReturns400(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);
        $steps = $this->getSessionSteps($logId);

        // El paso 1 es un descanso
        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$steps[1]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['action' => 'skip', 'scope' => 'exercise'])
        );

        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testBackReopensPreviousStep(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);
        $steps = $this->getSessionSteps($logId);

        // Completar el primer paso y retroceder
        $this->client->request(
            'PATCH',
            '/user/sessions/'.$logId.'/steps/'.$steps[0]['id'],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['action' => 'complete'])
        );

        $this->client->request(
            'POST',
            '/user/sessions/'.$logId.'/back',
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
        $this->assertSame(0, $data['currentPosition']);
        $this->assertSame('pending', $data['steps'][0]['status']);
        $this->assertNull($data['steps'][0]['completedAt']);
    }

    public function testBackOnFirstStepReturns400(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);

        $this->client->request(
            'POST',
            '/user/sessions/'.$logId.'/back',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testBackReturns409WhenSessionCompleted(): void
    {
        $training = $this->createTraining();
        $logId = $this->startSessionAndGetLogId($training);

        $this->client->request(
            'POST',
            '/user/trainings/'.$training->getId().'/complete',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['durationSeconds' => 300])
        );

        $this->client->request(
            'POST',
            '/user/sessions/'.$logId.'/back',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertSame(409, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getSessionSteps(int $logId): array
    {
        $this->client->request(
            'GET',
            '/user/sessions/'.$logId,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        return $data['steps'];
    }

    private function startSessionAndGetLogId(Training $training): int
    {
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

        $data = json_decode($this->client->getResponse()->getContent(), true);

        return $data['logId'];
    }
}
