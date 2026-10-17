<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Benchmark;
use App\Entity\User;
use App\Enum\BenchmarkFormat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Filtro por status en la lista de intentos del usuario:
 * permite al player retomar un attempt in_progress sin crear duplicados.
 */
class BenchmarkAttemptsListTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private $client;

    private ?string $authToken = null;

    private ?Benchmark $benchmark = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $benchmark = new Benchmark();
        $benchmark->setName('Fran');
        $benchmark->setType('for_time');
        $benchmark->setFormat(BenchmarkFormat::FOR_TIME);
        $this->entityManager->persist($benchmark);
        $this->entityManager->flush();
        $this->benchmark = $benchmark;

        $user = new User();
        $user->setNick('attemptslist');
        $user->setEmail('attemptslist@test.com');
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
            json_encode(['email' => 'attemptslist@test.com', 'password' => 'Test123!'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
        self::assertNotNull($this->authToken);
    }

    private function startAttempt(): int
    {
        $this->client->request(
            'POST',
            '/benchmarks/'.$this->benchmark->getId().'/start',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['scalingUsed' => 'rx'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $attemptId = $data['attempt']['id'] ?? null;
        self::assertNotNull($attemptId);

        return $attemptId;
    }

    private function finishAttempt(int $attemptId): void
    {
        $this->client->request(
            'POST',
            '/benchmarks/attempts/'.$attemptId.'/finish',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['status' => 'completed', 'totalTimeSeconds' => 300])
        );
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function listAttempts(string $query = ''): array
    {
        $this->client->request(
            'GET',
            '/benchmarks/attempts'.$query,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        return json_decode($this->client->getResponse()->getContent(), true);
    }

    public function testListAttemptsWithoutFilterReturnsAll(): void
    {
        $completedId = $this->startAttempt();
        $this->finishAttempt($completedId);
        $this->startAttempt(); // queda in_progress

        $attempts = $this->listAttempts('?benchmark_id='.$this->benchmark->getId());

        $this->assertCount(2, $attempts);
    }

    public function testListAttemptsFilteredByStatus(): void
    {
        $completedId = $this->startAttempt();
        $this->finishAttempt($completedId);
        $inProgressId = $this->startAttempt();

        $inProgress = $this->listAttempts('?benchmark_id='.$this->benchmark->getId().'&status=in_progress');
        $this->assertCount(1, $inProgress);
        $this->assertSame($inProgressId, $inProgress[0]['id']);
        $this->assertSame('in_progress', $inProgress[0]['status']);

        $completed = $this->listAttempts('?benchmark_id='.$this->benchmark->getId().'&status=completed');
        $this->assertCount(1, $completed);
        $this->assertSame($completedId, $completed[0]['id']);
        $this->assertSame('completed', $completed[0]['status']);

        $dnf = $this->listAttempts('?benchmark_id='.$this->benchmark->getId().'&status=dnf');
        $this->assertCount(0, $dnf);
    }

    public function testListAttemptsWithInvalidStatusReturns400(): void
    {
        $this->client->request(
            'GET',
            '/benchmarks/attempts?status=invalido',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }
}
