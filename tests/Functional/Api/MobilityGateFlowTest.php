<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\MobilityGate;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Flujo de puertas de movilidad (modo strict_gates):
 * fuerza superada + puerta fallada → mobility_blocked; re-test de puertas → nivel completado.
 */
class MobilityGateFlowTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private $client;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?TrainingProgram $testProgram = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        // Programa en modo strict_gates con 2 niveles y la puerta G1 en el test del nivel 1
        $program = new TrainingProgram();
        $program->setName('Programa Puertas');
        $program->setDescription('Test');
        $program->setSlug('programa-puertas');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');
        $program->setTestEvaluationMode(TrainingProgram::TEST_EVAL_STRICT_GATES);
        $this->entityManager->persist($program);

        for ($i = 1; $i <= 2; ++$i) {
            $level = new TrainingLevel();
            $level->setProgram($program);
            $level->setLevelNumber($i);
            $level->setName("Nivel {$i}");
            $level->setTitle("Nivel {$i}");
            $level->setEstimatedDurationWeeks(4);
            $level->setProgramVersion('v4');

            if (1 === $i) {
                $level->setTestRequirements([
                    ['name' => 'Dominada estricta', 'minimum' => 1, 'target' => 3, 'unit' => 'reps', 'form' => 'Estricta'],
                    ['name' => 'Pike push-up', 'minimum' => 6, 'target' => 10, 'unit' => 'reps', 'form' => 'Rango completo'],
                    ['name' => 'Puerta G1 — Flexión de hombro', 'minimum' => 1, 'target' => null, 'unit' => 'bool', 'form' => 'Brazos tocan pared', 'type' => 'puerta_movilidad', 'gateCode' => 'G1'],
                ]);
            }

            $this->entityManager->persist($level);
        }

        $gate = new MobilityGate();
        $gate->setCode('G1');
        $gate->setName('Flexión de hombro completa');
        $gate->setCriteria('De pie contra pared, brazos suben hasta tocar la pared sin arquear.');
        $gate->setProgram($program);
        $this->entityManager->persist($gate);

        $this->testUser = new User();
        $this->testUser->setNick('gatetest');
        $this->testUser->setEmail('gates@test.com');
        $this->testUser->setPassword($passwordHasher->hashPassword($this->testUser, 'Test123!'));
        $this->testUser->setRoles(['ROLE_USER']);
        $this->entityManager->persist($this->testUser);

        $this->entityManager->flush();
        $this->testProgram = $program;

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'gates@test.com', 'password' => 'Test123!'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function getLevel(int $levelNumber): TrainingLevel
    {
        return $this->entityManager->getRepository(TrainingLevel::class)
            ->findOneBy(['program' => $this->testProgram, 'levelNumber' => $levelNumber]);
    }

    private function createProgress(TrainingLevel $level, string $status): UserLevelProgress
    {
        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus($status);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        return $progress;
    }

    private function submitTest(TrainingLevel $level, array $results, array $requirements): array
    {
        $this->client->request(
            'POST',
            '/user/progress/'.$level->getId().'/test',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['results' => $results, 'requirements' => $requirements])
        );

        return [
            'status' => $this->client->getResponse()->getStatusCode(),
            'data' => json_decode($this->client->getResponse()->getContent(), true),
        ];
    }

    private function levelOnePayload(int $dominadas, int $pikePushUps, int $gateG1): array
    {
        return [
            'results' => [
                ['name' => 'Dominada estricta', 'value' => $dominadas],
                ['name' => 'Pike push-up', 'value' => $pikePushUps],
                ['name' => 'Puerta G1 — Flexión de hombro', 'value' => $gateG1],
            ],
            'requirements' => [
                ['name' => 'Dominada estricta', 'minimum' => 1],
                ['name' => 'Pike push-up', 'minimum' => 6],
                ['name' => 'Puerta G1 — Flexión de hombro', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G1'],
            ],
        ];
    }

    public function testStrengthPassedButGateFailedBlocksMobility(): void
    {
        $level = $this->getLevel(1);
        $this->createProgress($level, UserLevelProgress::STATUS_IN_PROGRESS);

        $payload = $this->levelOnePayload(2, 8, 0);
        $response = $this->submitTest($level, $payload['results'], $payload['requirements']);

        $this->assertSame(200, $response['status']);
        $this->assertFalse($response['data']['passed']);
        $this->assertTrue($response['data']['mobilityBlocked']);
        $this->assertSame(['G1'], $response['data']['failedGates']);

        // No incrementa ciclo ni reinicia semana: la fuerza no se repite
        $this->entityManager->clear();
        $progress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(UserLevelProgress::STATUS_MOBILITY_BLOCKED, $progress->getStatus());
        $this->assertSame(0, $progress->getCyclesCompleted());

        // El nivel 2 NO se desbloquea
        $nextProgress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->getLevel(2));
        $this->assertNull($nextProgress);
    }

    public function testGateRetestAfterBlockCompletesLevel(): void
    {
        $level = $this->getLevel(1);
        $this->createProgress($level, UserLevelProgress::STATUS_IN_PROGRESS);

        // 1. Bloqueo por movilidad
        $payload = $this->levelOnePayload(2, 8, 0);
        $this->submitTest($level, $payload['results'], $payload['requirements']);

        // 2. Re-test: solo se envían las puertas
        $response = $this->submitTest(
            $level,
            [['name' => 'Puerta G1 — Flexión de hombro', 'value' => 1]],
            [['name' => 'Puerta G1 — Flexión de hombro', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G1']],
        );

        $this->assertSame(200, $response['status']);
        $this->assertTrue($response['data']['passed']);
        $this->assertFalse($response['data']['mobilityBlocked']);

        // Nivel completado y siguiente desbloqueado
        $this->entityManager->clear();
        $progress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(UserLevelProgress::STATUS_COMPLETED, $progress->getStatus());

        $nextProgress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->getLevel(2));
        $this->assertNotNull($nextProgress);
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $nextProgress->getStatus());
    }

    public function testGateRetestStillFailingKeepsBlocked(): void
    {
        $level = $this->getLevel(1);
        $this->createProgress($level, UserLevelProgress::STATUS_IN_PROGRESS);

        $payload = $this->levelOnePayload(2, 8, 0);
        $this->submitTest($level, $payload['results'], $payload['requirements']);

        $response = $this->submitTest(
            $level,
            [['name' => 'Puerta G1 — Flexión de hombro', 'value' => 0]],
            [['name' => 'Puerta G1 — Flexión de hombro', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G1']],
        );

        $this->assertFalse($response['data']['passed']);
        $this->assertTrue($response['data']['mobilityBlocked']);

        $this->entityManager->clear();
        $progress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(UserLevelProgress::STATUS_MOBILITY_BLOCKED, $progress->getStatus());
        $this->assertSame(0, $progress->getCyclesCompleted());
    }

    public function testStrengthFailureRepeatsCycle(): void
    {
        $level = $this->getLevel(1);
        $this->createProgress($level, UserLevelProgress::STATUS_IN_PROGRESS);

        // Pike push-up por debajo del mínimo: en strict_gates un solo fallo de fuerza suspende
        $payload = $this->levelOnePayload(2, 3, 1);
        $response = $this->submitTest($level, $payload['results'], $payload['requirements']);

        $this->assertSame(200, $response['status']);
        $this->assertFalse($response['data']['passed']);
        $this->assertFalse($response['data']['mobilityBlocked']);

        $this->entityManager->clear();
        $progress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $level);
        $this->assertSame(UserLevelProgress::STATUS_REPEAT, $progress->getStatus());
        $this->assertSame(1, $progress->getCyclesCompleted());
    }

    public function testLevelDetailExposesGatesAndBlockedState(): void
    {
        $level = $this->getLevel(1);
        $this->createProgress($level, UserLevelProgress::STATUS_IN_PROGRESS);

        $payload = $this->levelOnePayload(2, 8, 0);
        $this->submitTest($level, $payload['results'], $payload['requirements']);

        $this->client->request(
            'GET',
            '/user/programs/'.$this->testProgram->getId().'/levels/'.$level->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(1, $data['test']['gates']);
        $this->assertSame('G1', $data['test']['gates'][0]['code']);
        $this->assertSame('Flexión de hombro completa', $data['test']['gates'][0]['name']);
        $this->assertTrue($data['test']['mobilityBlocked']);
        $this->assertSame(['G1'], $data['test']['failedGates']);
    }

    public function testGetMobilityGatesByProgram(): void
    {
        $this->client->request(
            'GET',
            '/mobility-gates?program='.$this->testProgram->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(1, $data);
        $this->assertSame('G1', $data[0]['code']);
        $this->assertSame('Flexión de hombro completa', $data[0]['name']);
    }

    public function testGetMobilityGatesRequiresProgramParam(): void
    {
        $this->client->request(
            'GET',
            '/mobility-gates',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }
}
