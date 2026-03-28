<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Training;
use App\Entity\User;
use App\Enum\Discipline;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Tests CRUD de Training.
 *
 * Verifica que los usuarios pueden crear, leer, actualizar y eliminar sus entrenamientos.
 */
class TrainingCrudTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager;

    private ?UserPasswordHasherInterface $passwordHasher;

    private ?string $authToken = null;

    private ?User $testUser = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        // Crear usuario de test y obtener token
        $this->createTestUserAndLogin();
    }

    protected function tearDown(): void
    {
        $this->cleanTestData();
        parent::tearDown();
    }

    private function createTestUserAndLogin(): void
    {
        // Crear usuario
        $this->testUser = new User();
        $this->testUser->setNick('trainingtest');
        $this->testUser->setEmail('training@test.com');
        $this->testUser->setPassword($this->passwordHasher->hashPassword($this->testUser, 'Test123!'));
        $this->testUser->setRoles(['ROLE_USER']);

        $this->entityManager->persist($this->testUser);
        $this->entityManager->flush();

        // Login para obtener token
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nick' => 'trainingtest',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function cleanTestData(): void
    {
        // Eliminar trainings del usuario de test
        $trainings = $this->entityManager->getRepository(Training::class)
            ->findBy(['user' => $this->testUser]);

        foreach ($trainings as $training) {
            $this->entityManager->remove($training);
        }

        // Eliminar usuario de test
        if ($this->testUser) {
            $this->entityManager->remove($this->testUser);
        }

        $this->entityManager->flush();
    }

    /**
     * Test: Crear un nuevo training.
     */
    public function testCreateTraining(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/trainings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'Test Training',
                'discipline' => Discipline::CALISTHENICS->value,
                'description' => 'Training de prueba',
            ])
        );

        $response = $client->getResponse();

        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Test Training', $data['name']);
    }

    /**
     * Test: Listar trainings del usuario.
     */
    public function testListTrainings(): void
    {
        // Crear un training primero
        $training = new Training();
        $training->setName('Mi Training');
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setUser($this->testUser);
        $training->setTarget('strength');

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        // Listar
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/trainings',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data); // Solo debe ver su propio training
    }

    /**
     * Test: Usuario no puede ver trainings de otro usuario.
     */
    public function testUserCannotSeeOtherUserTrainings(): void
    {
        // Crear otro usuario con un training
        $otherUser = new User();
        $otherUser->setNick('otheruser');
        $otherUser->setEmail('other@test.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));

        $this->entityManager->persist($otherUser);

        $otherTraining = new Training();
        $otherTraining->setName('Other Training');
        $otherTraining->setDiscipline(Discipline::FITNESS);
        $otherTraining->setUser($otherUser);
        $otherTraining->setTarget('cardio');

        $this->entityManager->persist($otherTraining);
        $this->entityManager->flush();

        // Listar con nuestro usuario
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/trainings',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        // No debe ver el training del otro usuario
        foreach ($data as $training) {
            $this->assertNotSame('Other Training', $training['name']);
        }

        // Cleanup
        $this->entityManager->remove($otherTraining);
        $this->entityManager->remove($otherUser);
        $this->entityManager->flush();
    }

    /**
     * Test: Actualizar un training propio.
     */
    public function testUpdateOwnTraining(): void
    {
        // Crear training
        $training = new Training();
        $training->setName('Original Name');
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setUser($this->testUser);
        $training->setTarget('strength');

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        $trainingId = $training->getId();

        // Actualizar
        $client = static::createClient();
        $client->request(
            'PATCH',
            "/api/trainings/{$trainingId}",
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/merge-patch+json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'Updated Name',
            ])
        );

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Updated Name', $data['name']);
    }

    /**
     * Test: Eliminar un training propio.
     */
    public function testDeleteOwnTraining(): void
    {
        // Crear training
        $training = new Training();
        $training->setName('To Delete');
        $training->setDiscipline(Discipline::CROSSFIT);
        $training->setUser($this->testUser);
        $training->setTarget('hiit');

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        $trainingId = $training->getId();

        // Eliminar
        $client = static::createClient();
        $client->request(
            'DELETE',
            "/api/trainings/{$trainingId}",
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $client->getResponse();

        $this->assertSame(204, $response->getStatusCode());

        // Verificar que ya no existe
        $deletedTraining = $this->entityManager->getRepository(Training::class)->find($trainingId);
        $this->assertNull($deletedTraining);
    }
}
