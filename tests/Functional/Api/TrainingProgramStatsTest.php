<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\TrainingProgram;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TrainingProgramStatsTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?string $authToken = null;

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
        $program->setName('Test Stats Program');
        $program->setSlug('test-stats-program');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');
        $program->setTotalLevels(6);

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        $this->testProgram = $program;
    }

    private function createProgram(string $name, string $slug, string $discipline, string $difficulty, bool $isActive = true): TrainingProgram
    {
        $program = new TrainingProgram();
        $program->setName($name);
        $program->setSlug($slug);
        $program->setDiscipline($discipline);
        $program->setDifficulty($difficulty);
        $program->setTotalLevels(6);
        $program->setIsActive($isActive);

        $this->entityManager->persist($program);

        return $program;
    }

    private function createTestUserAndLogin(): void
    {
        $user = new User();
        $user->setNick('statstest');
        $user->setEmail('stats@test.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'Test123!'));
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
                'email' => 'stats@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    public function testGetProgramStatsReturnsZeroValues(): void
    {
        $this->client->request(
            'GET',
            '/training-programs/'.$this->testProgram->getId().'/stats',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame(0, $data['totalUsers']);
        $this->assertSame(0, $data['avgProgress']);
        $this->assertSame(0, $data['completions']);
        $this->assertSame(0, $data['avgTime']);
        $this->assertSame(0, $data['paused']);
        $this->assertSame(0, $data['dropped']);
    }

    public function testGetProgramStatsNotFound(): void
    {
        $this->client->request(
            'GET',
            '/training-programs/99999/stats',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetProgramsFilterByDiscipline(): void
    {
        $this->createProgram('CrossFit Program', 'crossfit-program', 'crossfit', 'intermediate');
        $this->createProgram('Fitness Program', 'fitness-program', 'fitness', 'beginner');
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/training-programs?discipline=calisthenics',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame('Test Stats Program', $data[0]['name']);
    }

    public function testGetProgramsFilterByDifficulty(): void
    {
        $this->createProgram('Advanced Program', 'advanced-program', 'calisthenics', 'expert');
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/training-programs?difficulty=beginner',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame('Test Stats Program', $data[0]['name']);
    }

    public function testGetProgramsFilterByActive(): void
    {
        $this->createProgram('Inactive Program', 'inactive-program', 'calisthenics', 'beginner', false);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/training-programs?active=1',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertGreaterThanOrEqual(1, \count($data));
        $this->assertTrue($data[0]['isActive']);
    }
}
