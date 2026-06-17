<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Entity\TrainingSkill;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TrainingSkillCrudTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?string $adminToken = null;

    private ?string $userToken = null;

    private ?int $testProgramId = null;

    private ?int $testLevelId = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $this->createTestProgramAndLevel();
        $this->createTestUsersAndLogin();
    }

    private function createTestProgramAndLevel(): void
    {
        $program = new TrainingProgram();
        $program->setName('Test Program');
        $program->setSlug('test-program');
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('intermediate');
        $program->setTotalLevels(12);

        $level = new TrainingLevel();
        $level->setName('Nivel 1');
        $level->setLevelNumber(1);
        $level->setProgram($program);
        $level->setProgramVersion('v1');

        $this->entityManager->persist($program);
        $this->entityManager->persist($level);
        $this->entityManager->flush();

        $this->testProgramId = $program->getId();
        $this->testLevelId = $level->getId();
    }

    private function getTestProgram(): TrainingProgram
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($this->testProgramId);
        $this->assertInstanceOf(TrainingProgram::class, $program);

        return $program;
    }

    private function getTestLevel(): TrainingLevel
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)->find($this->testLevelId);
        $this->assertInstanceOf(TrainingLevel::class, $level);

        return $level;
    }

    private function createTestUsersAndLogin(): void
    {
        $admin = new User();
        $admin->setNick('admintest');
        $admin->setEmail('admin@test.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Test123!'));
        $admin->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setNick('usertest');
        $user->setEmail('user@test.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'Test123!'));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($admin);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Login admin
        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'admin@test.com',
                'password' => 'Test123!',
            ])
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->adminToken = $data['token'] ?? null;

        // Login user
        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'user@test.com',
                'password' => 'Test123!',
            ])
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->userToken = $data['token'] ?? null;
    }

    public function testAdminCanCreateSkill(): void
    {
        $this->client->request(
            'POST',
            '/training-skills',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->adminToken,
            ],
            json_encode([
                'name' => 'Handstand Wall',
                'family' => 'handstand',
                'programId' => $this->testProgramId,
                'levelId' => $this->testLevelId,
                'unlockAtLevel' => 1,
                'description' => 'Apoyo en pared',
                'isKeySkill' => true,
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('skill', $data);
        $this->assertSame('Handstand Wall', $data['skill']['name']);
        $this->assertSame('handstand', $data['skill']['family']);
    }

    public function testUserCannotCreateSkill(): void
    {
        $this->client->request(
            'POST',
            '/training-skills',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->userToken,
            ],
            json_encode([
                'name' => 'Forbidden Skill',
                'family' => 'test',
                'programId' => $this->testProgramId,
                'levelId' => $this->testLevelId,
                'unlockAtLevel' => 1,
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(403, $response->getStatusCode());
    }

    public function testListSkills(): void
    {
        $skill = new TrainingSkill();
        $skill->setName('Test Skill');
        $skill->setFamily('test');
        $skill->setProgram($this->getTestProgram());
        $skill->setLevel($this->getTestLevel());
        $skill->setUnlockAtLevel(1);

        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/training-skills',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->userToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame('Test Skill', $data[0]['name']);
    }

    public function testGetSkillById(): void
    {
        $skill = new TrainingSkill();
        $skill->setName('Single Skill');
        $skill->setFamily('test');
        $skill->setProgram($this->getTestProgram());
        $skill->setLevel($this->getTestLevel());
        $skill->setUnlockAtLevel(2);

        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/training-skills/'.$skill->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->userToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Single Skill', $data['name']);
        $this->assertSame(2, $data['unlockAtLevel']);
    }

    public function testAdminCanUpdateSkill(): void
    {
        $skill = new TrainingSkill();
        $skill->setName('Original');
        $skill->setFamily('original');
        $skill->setProgram($this->getTestProgram());
        $skill->setLevel($this->getTestLevel());
        $skill->setUnlockAtLevel(1);

        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        $skillId = $skill->getId();

        $this->client->request(
            'PATCH',
            '/training-skills/'.$skillId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/merge-patch+json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->adminToken,
            ],
            json_encode([
                'name' => 'Updated',
                'unlockAtLevel' => 3,
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Updated', $data['skill']['name']);
        $this->assertSame(3, $data['skill']['unlockAtLevel']);
    }

    public function testAdminCanDeleteSkill(): void
    {
        $skill = new TrainingSkill();
        $skill->setName('To Delete');
        $skill->setFamily('test');
        $skill->setProgram($this->getTestProgram());
        $skill->setLevel($this->getTestLevel());
        $skill->setUnlockAtLevel(1);

        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        $skillId = $skill->getId();

        $this->client->request(
            'DELETE',
            '/training-skills/'.$skillId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->adminToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->entityManager->clear();
        $deletedSkill = $this->entityManager->getRepository(TrainingSkill::class)->find($skillId);
        $this->assertNull($deletedSkill);
    }

    public function testGetSkillReturns404ForInvalidId(): void
    {
        $this->client->request(
            'GET',
            '/training-skills/99999',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->userToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());
    }
}
