<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Training;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFavoriteApiTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?KernelBrowser $client = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->createTestUserAndLogin();
    }

    private function createTestUserAndLogin(): void
    {
        $this->testUser = new User();
        $this->testUser->setNick('favoritetest');
        $this->testUser->setEmail('favorite@test.com');
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
                'email' => 'favorite@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function createTraining(string $name = 'Test Training'): Training
    {
        $training = new Training();
        $training->setName($name);
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setTarget(TargetWorkout::STRENGTH);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        return $training;
    }

    private function createProgram(string $name = 'Test Program', string $slug = 'test-program'): TrainingProgram
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

    public function testAddTrainingFavorite(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Entrenamiento añadido a favoritos', $data['message']);
        $this->assertSame($training->getId(), $data['favorite']['training']['id']);
    }

    public function testAddTrainingFavoriteIsIdempotent(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('El entrenamiento ya estaba en favoritos', $data['message']);
    }

    public function testAddProgramFavorite(): void
    {
        $program = $this->createProgram();

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Programa añadido a favoritos', $data['message']);
        $this->assertSame($program->getId(), $data['favorite']['trainingProgram']['id']);
    }

    public function testAddProgramFavoriteIsIdempotent(): void
    {
        $program = $this->createProgram();

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('El programa ya estaba en favoritos', $data['message']);
    }

    public function testRemoveTrainingFavorite(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/trainings/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Entrenamiento eliminado de favoritos', $data['message']);
    }

    public function testRemoveProgramFavorite(): void
    {
        $program = $this->createProgram();

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/programs/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Programa eliminado de favoritos', $data['message']);
    }

    public function testListFavorites(): void
    {
        $training = $this->createTraining('Fav Training');
        $program = $this->createProgram('Fav Program', 'fav-program');

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'GET',
            '/user/favorites',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(1, $data['trainings']);
        $this->assertCount(1, $data['programs']);
        $this->assertSame('Fav Training', $data['trainings'][0]['training']['name']);
        $this->assertSame('Fav Program', $data['programs'][0]['trainingProgram']['name']);

        $trainingFavorite = $data['trainings'][0];
        $this->assertArrayHasKey('estimatedDurationMin', $trainingFavorite['training']);
        $this->assertArrayHasKey('estimatedDurationMax', $trainingFavorite['training']);
        $this->assertArrayHasKey('rounds', $trainingFavorite['training']);

        $programFavorite = $data['programs'][0];
        $this->assertArrayHasKey('levelCount', $programFavorite['trainingProgram']);
        $this->assertArrayHasKey('totalPhases', $programFavorite['trainingProgram']);
        $this->assertArrayHasKey('totalSessions', $programFavorite['trainingProgram']);
    }

    public function testCannotManageOtherUserFavorite(): void
    {
        $training = $this->createTraining();

        $otherUser = new User();
        $otherUser->setNick('other');
        $otherUser->setEmail('other@fav.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));

        $this->entityManager->persist($otherUser);
        $this->entityManager->flush();

        // Otro usuario añade a favoritos
        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'other@fav.com',
                'password' => 'Test123!',
            ])
        );

        $loginData = json_decode($this->client->getResponse()->getContent(), true);
        $otherToken = $loginData['token'] ?? null;

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$otherToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/trainings/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(403, $response->getStatusCode());
    }

    public function testCannotManageOtherUserProgramFavorite(): void
    {
        $program = $this->createProgram();

        $otherUser = new User();
        $otherUser->setNick('otherprogram');
        $otherUser->setEmail('otherprogram@fav.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));

        $this->entityManager->persist($otherUser);
        $this->entityManager->flush();

        // Otro usuario añade a favoritos
        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'otherprogram@fav.com',
                'password' => 'Test123!',
            ])
        );

        $loginData = json_decode($this->client->getResponse()->getContent(), true);
        $otherToken = $loginData['token'] ?? null;

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$otherToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/programs/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(403, $response->getStatusCode());
    }

    public function testCannotFavoritePrivateTrainingOfOtherUser(): void
    {
        $otherUser = new User();
        $otherUser->setNick('otherowner');
        $otherUser->setEmail('otherowner@fav.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));

        $this->entityManager->persist($otherUser);
        $this->entityManager->flush();

        $training = new Training();
        $training->setName('Private Training');
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setTarget(TargetWorkout::STRENGTH);
        $training->setTrainingUser($otherUser);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(403, $response->getStatusCode());
    }

    public function testAddTrainingFavoriteReturns404ForNonExistentTraining(): void
    {
        $this->client->request(
            'POST',
            '/user/favorites/trainings/999999',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Entrenamiento no encontrado', $data['error']);
    }

    public function testAddProgramFavoriteReturns404ForNonExistentProgram(): void
    {
        $this->client->request(
            'POST',
            '/user/favorites/programs/999999',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Programa no encontrado', $data['error']);
    }

    public function testRemoveTrainingFavoriteReturns404ForNonExistentFavorite(): void
    {
        $this->client->request(
            'DELETE',
            '/user/favorites/trainings/999999',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Favorito no encontrado', $data['error']);
    }

    public function testRemoveProgramFavoriteReturns404ForNonExistentFavorite(): void
    {
        $this->client->request(
            'DELETE',
            '/user/favorites/programs/999999',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Favorito no encontrado', $data['error']);
    }
}
