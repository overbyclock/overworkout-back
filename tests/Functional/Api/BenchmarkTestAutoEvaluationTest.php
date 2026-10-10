<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Benchmark;
use App\Entity\TestResult;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Enum\BenchmarkFormat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Auto-evaluación del test de nivel al cerrar un attempt de benchmark:
 * (a) marca dentro del mínimo → nivel completado; (b) fuera → repeat con ciclo incrementado;
 * (c) benchmark no referenciado por ningún test → sin efectos.
 */
class BenchmarkTestAutoEvaluationTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private $client;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?Benchmark $fran = null;

    private ?Benchmark $grace = null;

    private ?TrainingLevel $levelOne = null;

    private ?TrainingLevel $levelTwo = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        // Programa en modo legacy con 2 niveles; el test del nivel 1 exige Fran <= 300 s
        $program = new TrainingProgram();
        $program->setName('Programa CrossFit AutoTest');
        $program->setDescription('Test');
        $program->setSlug('programa-cf-autotest');
        $program->setDiscipline('crossfit');
        $program->setDifficulty('beginner');
        $this->entityManager->persist($program);

        $fran = new Benchmark();
        $fran->setName('Fran');
        $fran->setType('for_time');
        $fran->setFormat(BenchmarkFormat::FOR_TIME);
        $this->entityManager->persist($fran);

        // Benchmark no referenciado por ningún test de nivel
        $grace = new Benchmark();
        $grace->setName('Grace');
        $grace->setType('for_time');
        $grace->setFormat(BenchmarkFormat::FOR_TIME);
        $this->entityManager->persist($grace);
        $this->entityManager->flush(); // necesario para que los benchmarks tengan id

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
                    [
                        'name' => 'Fran',
                        'minimum' => 300,
                        'direction' => 'lte',
                        'unit' => 'segundos',
                        'type' => 'benchmark',
                        'benchmarkId' => $fran->getId(),
                    ],
                ]);
            }

            $this->entityManager->persist($level);

            if (1 === $i) {
                $this->levelOne = $level;
            } else {
                $this->levelTwo = $level;
            }
        }

        $this->testUser = new User();
        $this->testUser->setNick('cfautotest');
        $this->testUser->setEmail('cfautotest@test.com');
        $this->testUser->setPassword($passwordHasher->hashPassword($this->testUser, 'Test123!'));
        $this->testUser->setRoles(['ROLE_USER']);
        $this->entityManager->persist($this->testUser);

        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($this->levelOne);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);

        $this->entityManager->flush();
        $this->fran = $fran;
        $this->grace = $grace;

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'cfautotest@test.com', 'password' => 'Test123!'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function startAndFinishAttempt(int $benchmarkId, int $totalTimeSeconds): int
    {
        $this->client->request(
            'POST',
            '/benchmarks/'.$benchmarkId.'/start',
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

        $this->client->request(
            'POST',
            '/benchmarks/attempts/'.$attemptId.'/finish',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode(['status' => 'completed', 'totalTimeSeconds' => $totalTimeSeconds])
        );

        return $this->client->getResponse()->getStatusCode();
    }

    /**
     * @return TestResult[]
     */
    private function findTestResults(TrainingLevel $level): array
    {
        $progress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $level);

        return $this->entityManager->getRepository(TestResult::class)
            ->findByUserLevelProgress($progress);
    }

    public function testCompletedAttemptWithinMinimumPassesLevel(): void
    {
        $status = $this->startAndFinishAttempt($this->fran->getId(), 240);
        $this->assertSame(200, $status);

        $this->entityManager->clear();

        $testResults = $this->findTestResults($this->levelOne);
        $this->assertCount(1, $testResults);
        $this->assertTrue($testResults[0]->isOverallPassed());
        $this->assertSame('Fran', $testResults[0]->getResults()[0]['name']);
        $this->assertSame(240, $testResults[0]->getResults()[0]['value']);

        // Nivel 1 completado y nivel 2 desbloqueado
        $progressOne = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->levelOne);
        $this->assertSame(UserLevelProgress::STATUS_COMPLETED, $progressOne->getStatus());

        $progressTwo = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->levelTwo);
        $this->assertNotNull($progressTwo);
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $progressTwo->getStatus());
    }

    public function testCompletedAttemptAboveMinimumRepeatsCycle(): void
    {
        $status = $this->startAndFinishAttempt($this->fran->getId(), 320);
        $this->assertSame(200, $status);

        $this->entityManager->clear();

        $testResults = $this->findTestResults($this->levelOne);
        $this->assertCount(1, $testResults);
        $this->assertFalse($testResults[0]->isOverallPassed());

        $progressOne = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->levelOne);
        $this->assertSame(UserLevelProgress::STATUS_REPEAT, $progressOne->getStatus());
        $this->assertSame(1, $progressOne->getCyclesCompleted());

        // El nivel 2 no se desbloquea
        $progressTwo = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->levelTwo);
        $this->assertNull($progressTwo);
    }

    public function testUnreferencedBenchmarkAttemptHasNoSideEffects(): void
    {
        $status = $this->startAndFinishAttempt($this->grace->getId(), 120);
        $this->assertSame(200, $status);

        $this->entityManager->clear();

        $this->assertCount(0, $this->findTestResults($this->levelOne));

        $progressOne = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($this->testUser, $this->levelOne);
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $progressOne->getStatus());
        $this->assertSame(0, $progressOne->getCyclesCompleted());
    }

    /**
     * Intent parcial en modo strict_gates (solo la marca del benchmark, falta el ítem
     * de fuerza): el TestResult se acumula sin outcome — el progreso sigue in_progress,
     * el ciclo no se incrementa y no se desbloquea el nivel siguiente.
     */
    public function testPartialAttemptInStrictGatesDoesNotApplyOutcome(): void
    {
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $program = new TrainingProgram();
        $program->setName('Programa CrossFit Strict Gates');
        $program->setDescription('Test');
        $program->setSlug('programa-cf-strict-gates');
        $program->setDiscipline('crossfit');
        $program->setDifficulty('beginner');
        $program->setTestEvaluationMode(TrainingProgram::TEST_EVAL_STRICT_GATES);
        $this->entityManager->persist($program);

        $helen = new Benchmark();
        $helen->setName('Helen');
        $helen->setType('girl');
        $helen->setFormat(BenchmarkFormat::FOR_TIME);
        $this->entityManager->persist($helen);
        $this->entityManager->flush();

        $levelOne = new TrainingLevel();
        $levelOne->setProgram($program);
        $levelOne->setLevelNumber(1);
        $levelOne->setName('Nivel 1');
        $levelOne->setTitle('Nivel 1');
        $levelOne->setEstimatedDurationWeeks(4);
        $levelOne->setProgramVersion('v4');
        $levelOne->setTestRequirements([
            [
                'name' => 'Helen',
                'minimum' => 600,
                'direction' => 'lte',
                'unit' => 'segundos',
                'type' => 'benchmark',
                'benchmarkId' => $helen->getId(),
            ],
            [
                'name' => 'Fuerza',
                'minimum' => 10,
                'direction' => 'gte',
                'unit' => 'reps',
            ],
        ]);
        $this->entityManager->persist($levelOne);

        $levelTwo = new TrainingLevel();
        $levelTwo->setProgram($program);
        $levelTwo->setLevelNumber(2);
        $levelTwo->setName('Nivel 2');
        $levelTwo->setTitle('Nivel 2');
        $levelTwo->setEstimatedDurationWeeks(4);
        $levelTwo->setProgramVersion('v4');
        $this->entityManager->persist($levelTwo);

        $user = new User();
        $user->setNick('cfstrictgates');
        $user->setEmail('cfstrictgates@test.com');
        $user->setPassword($passwordHasher->hashPassword($user, 'Test123!'));
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->persist($user);

        $progress = new UserLevelProgress();
        $progress->setUser($user);
        $progress->setTrainingLevel($levelOne);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'cfstrictgates@test.com', 'password' => 'Test123!'])
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $token = $data['token'] ?? null;
        self::assertNotNull($token);

        // Cerrar Helen por encima del mínimo: marca registrada, pero falta el ítem de fuerza
        $this->client->request(
            'POST',
            '/benchmarks/'.$helen->getId().'/start',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer '.$token],
            json_encode(['scalingUsed' => 'rx'])
        );
        $attemptId = json_decode($this->client->getResponse()->getContent(), true)['attempt']['id'] ?? null;
        self::assertNotNull($attemptId);

        $this->client->request(
            'POST',
            '/benchmarks/attempts/'.$attemptId.'/finish',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer '.$token],
            json_encode(['status' => 'completed', 'totalTimeSeconds' => 720])
        );
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->entityManager->clear();

        // TestResult acumulado con el ítem, sin evaluación (overallPassed false por defecto)
        $storedProgress = $this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($user, $levelOne);
        $testResults = $this->entityManager->getRepository(TestResult::class)
            ->findByUserLevelProgress($storedProgress);
        $this->assertCount(1, $testResults);
        $this->assertFalse($testResults[0]->isOverallPassed());
        $this->assertSame('Helen', $testResults[0]->getResults()[0]['name']);
        $this->assertSame(720, $testResults[0]->getResults()[0]['value']);

        // Sin outcome: sigue in_progress, ciclo intacto y nivel 2 no desbloqueado
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $storedProgress->getStatus());
        $this->assertSame(0, $storedProgress->getCyclesCompleted());
        $this->assertNull($this->entityManager->getRepository(UserLevelProgress::class)
            ->findByUserAndLevel($user, $levelTwo));
    }
}
