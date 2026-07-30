<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Achievement;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Entity\UserLevelProgress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Logros de usuario: desbloqueo manual de hitos, desbloqueo automático de logros
 * de test al superar un nivel, y listado.
 */
class UserAchievementApiTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private $client;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?TrainingProgram $testProgram = null;

    private ?TrainingLevel $testLevel = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $program = new TrainingProgram();
        $program->setName('Programa Logros');
        $program->setDescription('Test');
        $program->setSlug('programa-logros');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');
        $this->entityManager->persist($program);

        $level = new TrainingLevel();
        $level->setProgram($program);
        $level->setLevelNumber(1);
        $level->setName('Nivel 1');
        $level->setTitle('Nivel 1');
        $level->setEstimatedDurationWeeks(4);
        $this->entityManager->persist($level);

        $this->testUser = new User();
        $this->testUser->setNick('achievementtest');
        $this->testUser->setEmail('achievements@test.com');
        $this->testUser->setPassword($passwordHasher->hashPassword($this->testUser, 'Test123!'));
        $this->testUser->setRoles(['ROLE_USER']);
        $this->entityManager->persist($this->testUser);

        // Progreso activo en el programa (requisito para desbloquear hitos del programa)
        $progress = new UserLevelProgress();
        $progress->setUser($this->testUser);
        $progress->setTrainingLevel($level);
        $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->entityManager->persist($progress);

        $this->entityManager->flush();
        $this->testProgram = $program;
        $this->testLevel = $level;

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'achievements@test.com', 'password' => 'Test123!'])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function createAchievement(string $code, ?string $type, ?TrainingLevel $level = null): Achievement
    {
        $achievement = new Achievement();
        $achievement->setCode($code);
        $achievement->setName($code);
        $achievement->setCategory('skill');
        $achievement->setType($type);
        $achievement->setTier(Achievement::TIER_COMUN);
        $achievement->setProgram($this->testProgram);
        $achievement->setLevel($level);
        $this->entityManager->persist($achievement);
        $this->entityManager->flush();

        return $achievement;
    }

    private function unlockRequest(array $payload): array
    {
        $this->client->request(
            'POST',
            '/user/achievements/unlock',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode($payload)
        );

        return [
            'status' => $this->client->getResponse()->getStatusCode(),
            'data' => json_decode($this->client->getResponse()->getContent(), true),
        ];
    }

    public function testUnlockHitoAchievement(): void
    {
        $this->createAchievement('PRIMER_CROW', Achievement::TYPE_HITO);

        $response = $this->unlockRequest(['code' => 'PRIMER_CROW', 'context' => ['levelNumber' => 5]]);

        $this->assertSame(201, $response['status']);
        $this->assertTrue($response['data']['unlocked']);
        $this->assertSame('PRIMER_CROW', $response['data']['userAchievement']['achievement']['code']);
    }

    public function testUnlockIsIdempotent(): void
    {
        $this->createAchievement('PRIMER_CROW', Achievement::TYPE_HITO);

        $this->unlockRequest(['code' => 'PRIMER_CROW']);
        $response = $this->unlockRequest(['code' => 'PRIMER_CROW']);

        $this->assertSame(200, $response['status']);
        $this->assertFalse($response['data']['unlocked']);
    }

    public function testUnlockRejectsTestTypeAchievement(): void
    {
        $this->createAchievement('NIVEL_1_COMPLETADO', Achievement::TYPE_TEST, $this->testLevel);

        $response = $this->unlockRequest(['code' => 'NIVEL_1_COMPLETADO']);

        $this->assertSame(400, $response['status']);
    }

    public function testUnlockRejectsUnknownCode(): void
    {
        $response = $this->unlockRequest(['code' => 'NO_EXISTE']);

        $this->assertSame(400, $response['status']);
    }

    public function testUnlockRejectsAchievementOfInactiveProgram(): void
    {
        // Logro de otro programa en el que el usuario no tiene progreso activo
        $otherProgram = new TrainingProgram();
        $otherProgram->setName('Otro Programa');
        $otherProgram->setSlug('otro-programa');
        $otherProgram->setDiscipline('calisthenics');
        $otherProgram->setDifficulty('beginner');
        $this->entityManager->persist($otherProgram);

        $achievement = new Achievement();
        $achievement->setCode('HITO_AJENO');
        $achievement->setName('HITO_AJENO');
        $achievement->setCategory('skill');
        $achievement->setType(Achievement::TYPE_HITO);
        $achievement->setProgram($otherProgram);
        $this->entityManager->persist($achievement);
        $this->entityManager->flush();

        $response = $this->unlockRequest(['code' => 'HITO_AJENO']);

        $this->assertSame(403, $response['status']);
    }

    public function testGetUserAchievements(): void
    {
        $this->createAchievement('PRIMER_CROW', Achievement::TYPE_HITO);
        $this->unlockRequest(['code' => 'PRIMER_CROW']);

        $this->client->request(
            'GET',
            '/user/achievements',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(1, $data);
        $this->assertSame('PRIMER_CROW', $data[0]['achievement']['code']);
        $this->assertArrayHasKey('unlockedAt', $data[0]);
    }

    public function testTestAchievementsUnlockAutomaticallyOnLevelPassed(): void
    {
        $this->createAchievement('NIVEL_1_COMPLETADO', Achievement::TYPE_TEST, $this->testLevel);

        $this->client->request(
            'POST',
            '/user/progress/'.$this->testLevel->getId().'/test',
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

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->entityManager->clear();
        $achievement = $this->entityManager->getRepository(Achievement::class)
            ->findOneBy(['code' => 'NIVEL_1_COMPLETADO']);
        $userAchievement = $this->entityManager->getRepository(UserAchievement::class)
            ->findOneByUserAndAchievement($this->testUser, $achievement);

        $this->assertNotNull($userAchievement);
        $this->assertSame(1, $userAchievement->getContext()['levelNumber']);
    }

    public function testProgramAchievementsIncludeUnlockedFlag(): void
    {
        $this->createAchievement('PRIMER_CROW', Achievement::TYPE_HITO);
        $this->createAchievement('OTRO_HITO', Achievement::TYPE_HITO);
        $this->unlockRequest(['code' => 'PRIMER_CROW']);

        $this->client->request(
            'GET',
            '/training-programs/'.$this->testProgram->getId().'/achievements',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $byCode = array_column($data, 'unlocked', 'code');
        $this->assertTrue($byCode['PRIMER_CROW']);
        $this->assertFalse($byCode['OTRO_HITO']);
    }
}
