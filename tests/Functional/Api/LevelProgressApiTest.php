<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LevelProgressApiTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?TrainingProgram $testProgram = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $this->createTestProgram();
        $this->createTestUserAndLogin();
    }

    private function createTestProgram(): void
    {
        $program = new TrainingProgram();
        $program->setName('Test Program');
        $program->setDescription('Test description');
        $program->setSlug('test-program');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');

        $this->entityManager->persist($program);

        for ($i = 1; $i <= 3; ++$i) {
            $level = new TrainingLevel();
            $level->setProgram($program);
            $level->setLevelNumber($i);
            $level->setName("Level {$i}");
            $level->setTitle("Level {$i}");
            $level->setEstimatedDurationWeeks(4);
            $level->setProgramVersion('v2');
            $this->entityManager->persist($level);
        }

        $this->entityManager->flush();
        $this->testProgram = $program;
    }

    private function createTestUserAndLogin(): void
    {
        $this->testUser = new User();
        $this->testUser->setNick('progresstest');
        $this->testUser->setEmail('progress@test.com');
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
                'email' => 'progress@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    public function testInitializeProgress(): void
    {
        $this->client->request(
            'POST',
            '/user/progress/init/'.$this->testProgram->getId(),
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
        $this->assertSame('Progress initialized', $data['message']);
        $this->assertSame(3, $data['levelsCount']);
    }

    public function testGetUserProgress(): void
    {
        $levels = $this->entityManager->getRepository(TrainingLevel::class)
            ->findByProgramOrdered($this->testProgram->getId());

        foreach ($levels as $level) {
            $progress = new UserLevelProgress();
            $progress->setUser($this->testUser);
            $progress->setTrainingLevel($level);
            $progress->setStatus(UserLevelProgress::STATUS_LOCKED);
            $this->entityManager->persist($progress);
        }
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/user/progress',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(3, $data);
    }

    public function testGetActiveProgress(): void
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => 1]);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/user/progress/active',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $data['status']);
    }

    public function testSubmitTestResultsPass(): void
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => 1]);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/user/progress/'.$level->getId().'/test',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'results' => [
                    ['name' => 'Pull-ups', 'value' => 12],
                    ['name' => 'Push-ups', 'value' => 25],
                    ['name' => 'Squats', 'value' => 30],
                    ['name' => 'Plank', 'value' => 60],
                ],
                'requirements' => [
                    ['name' => 'Pull-ups', 'minimum' => 10],
                    ['name' => 'Push-ups', 'minimum' => 20],
                    ['name' => 'Squats', 'minimum' => 25],
                    ['name' => 'Plank', 'minimum' => 45],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['passed']);
        $this->assertSame('Test passed! Level completed.', $data['message']);
    }

    public function testSubmitTestResultsFail(): void
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => 1]);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/user/progress/'.$level->getId().'/test',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'results' => [
                    ['name' => 'Pull-ups', 'value' => 5],
                    ['name' => 'Push-ups', 'value' => 10],
                    ['name' => 'Squats', 'value' => 30],
                    ['name' => 'Plank', 'value' => 60],
                ],
                'requirements' => [
                    ['name' => 'Pull-ups', 'minimum' => 10],
                    ['name' => 'Push-ups', 'minimum' => 20],
                    ['name' => 'Squats', 'minimum' => 25],
                    ['name' => 'Plank', 'minimum' => 45],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['passed']);
        $this->assertSame('Test failed. Repeat cycle required.', $data['message']);

        // Verificar que el progreso se actualizó a repeat
        $progress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(UserLevelProgress::STATUS_REPEAT, $progress->getStatus());
        $this->assertSame(1, $progress->getCyclesCompleted());
        $this->assertSame(0, $progress->getCurrentWeek());
    }

    public function testAdvanceWeek(): void
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => 1]);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $progress->setCurrentWeek(1);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/user/progress/'.$level->getId().'/advance-week',
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
        $this->assertSame(2, $data['currentWeek']);
    }

    public function testLockedLevelCannotSubmitTest(): void
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => 2]);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_LOCKED);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/user/progress/'.$level->getId().'/test',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'results' => [
                    ['name' => 'Pull-ups', 'value' => 12],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(403, $response->getStatusCode());
    }

    public function testMissingResultsReturnsError(): void
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => 1]);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/user/progress/'.$level->getId().'/test',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['notes' => 'No results'])
        );

        $response = $this->client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
    }
}
