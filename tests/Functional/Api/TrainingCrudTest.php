<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Benchmark;
use App\Entity\Exercises;
use App\Entity\Training;
use App\Entity\User;
use App\Enum\Discipline;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use App\Enum\TargetWorkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TrainingCrudTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?UserPasswordHasherInterface $passwordHasher = null;

    private ?string $authToken = null;

    private ?User $testUser = null;

    private ?Exercises $testExercise = null;

    private ?Benchmark $testBenchmark = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $this->createTestExercise();
        $this->createTestBenchmark();
        $this->createTestUserAndLogin();
    }

    private function createTestBenchmark(): void
    {
        $benchmark = new Benchmark();
        $benchmark->setName('Fran Test');
        $benchmark->setType('girl');

        $this->entityManager->persist($benchmark);
        $this->entityManager->flush();

        $this->testBenchmark = $benchmark;
    }

    private function createTestExercise(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Test Exercise');
        $exercise->setPrimaryMuscleGroup(MuscleGroup::CHEST);
        $exercise->setLevel(Levels::BEGINNER);

        $this->entityManager->persist($exercise);
        $this->entityManager->flush();

        $this->testExercise = $exercise;
    }

    private function createTestUserAndLogin(): void
    {
        $this->testUser = new User();
        $this->testUser->setNick('trainingtest');
        $this->testUser->setEmail('training@test.com');
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
                'email' => 'training@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    public function testCreateTraining(): void
    {
        $this->client->request(
            'POST',
            '/trainings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'Test Training',
                'discipline' => Discipline::CALISTHENICS->value,
                'target' => TargetWorkout::STRENGTH->value,
                'rounds' => [
                    [
                        'round' => 1,
                        'restBetweenRounds' => 60,
                        'exercises' => [
                            [
                                'exerciseId' => $this->testExercise->getId(),
                                'reps' => 10,
                                'sets' => 3,
                            ],
                        ],
                    ],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('training', $data);
        $this->assertSame('Test Training', $data['training']['name']);
    }

    public function testCreateTrainingConRoundBenchmark(): void
    {
        $this->client->request(
            'POST',
            '/trainings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'WOD Training',
                'discipline' => Discipline::CROSSFIT->value,
                'target' => TargetWorkout::STRENGTH->value,
                'rounds' => [
                    [
                        'round' => 1,
                        'restBetweenRounds' => 60,
                        'blockType' => 'wod',
                        'benchmarkId' => $this->testBenchmark->getId(),
                        'exercises' => [],
                    ],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame($this->testBenchmark->getId(), $data['training']['trainingRounds'][0]['benchmarkId']);
    }

    public function testRecuperarTrainingConRoundBenchmarkMuestraBenchmarkId(): void
    {
        $this->client->request(
            'POST',
            '/trainings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'WOD Training',
                'discipline' => Discipline::CROSSFIT->value,
                'target' => TargetWorkout::STRENGTH->value,
                'rounds' => [
                    [
                        'round' => 1,
                        'restBetweenRounds' => 60,
                        'blockType' => 'wod',
                        'benchmarkId' => $this->testBenchmark->getId(),
                        'exercises' => [],
                    ],
                ],
            ])
        );

        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
        $trainingId = json_decode($this->client->getResponse()->getContent(), true)['training']['id'];

        $this->client->request(
            'GET',
            "/trainings/{$trainingId}",
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame($this->testBenchmark->getId(), $data['trainingRounds'][0]['benchmarkId']);
    }

    public function testBenchmarkIdInexistenteDevuelve400(): void
    {
        $this->client->request(
            'POST',
            '/trainings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'WOD Training',
                'discipline' => Discipline::CROSSFIT->value,
                'target' => TargetWorkout::STRENGTH->value,
                'rounds' => [
                    [
                        'round' => 1,
                        'restBetweenRounds' => 60,
                        'benchmarkId' => 999999,
                        'exercises' => [],
                    ],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testListTrainings(): void
    {
        $training = new Training();
        $training->setName('Mi Training');
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setTrainingUser($this->testUser);
        $training->setTarget(TargetWorkout::STRENGTH);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/trainings/user/'.$this->testUser->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
    }

    public function testUserCannotSeeOtherUserTrainings(): void
    {
        $otherUser = new User();
        $otherUser->setNick('otheruser');
        $otherUser->setEmail('other@test.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));

        $this->entityManager->persist($otherUser);

        $otherTraining = new Training();
        $otherTraining->setName('Other Training');
        $otherTraining->setDiscipline(Discipline::FITNESS);
        $otherTraining->setTrainingUser($otherUser);
        $otherTraining->setTarget(TargetWorkout::FATBURNING);

        $this->entityManager->persist($otherTraining);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/trainings/user/'.$this->testUser->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        foreach ($data as $training) {
            $this->assertNotSame('Other Training', $training['name']);
        }
    }

    public function testUpdateOwnTraining(): void
    {
        $training = new Training();
        $training->setName('Original Name');
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setTrainingUser($this->testUser);
        $training->setTarget(TargetWorkout::STRENGTH);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        $trainingId = $training->getId();

        $this->client->request(
            'PATCH',
            "/trainings/{$trainingId}",
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

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Updated Name', $data['training']['name']);
    }

    public function testUpdateRoundWithoutBenchmarkIdPreservesBenchmark(): void
    {
        $this->client->request(
            'POST',
            '/trainings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'name' => 'WOD Training',
                'discipline' => Discipline::CROSSFIT->value,
                'target' => TargetWorkout::STRENGTH->value,
                'rounds' => [
                    [
                        'round' => 1,
                        'restBetweenRounds' => 60,
                        'blockType' => 'wod',
                        'benchmarkId' => $this->testBenchmark->getId(),
                        'exercises' => [
                            [
                                'exerciseId' => $this->testExercise->getId(),
                                'reps' => 10,
                                'sets' => 3,
                            ],
                        ],
                    ],
                ],
            ])
        );

        $this->assertSame(201, $this->client->getResponse()->getStatusCode());
        $created = json_decode($this->client->getResponse()->getContent(), true)['training'];
        $trainingId = $created['id'];
        $roundId = $created['trainingRounds'][0]['id'];

        // PATCH del nombre del round SIN enviar benchmarkId: el benchmark se conserva
        $this->client->request(
            'PATCH',
            "/trainings/{$trainingId}",
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/merge-patch+json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken,
            ],
            json_encode([
                'rounds' => [
                    [
                        'id' => $roundId,
                        'round' => 1,
                        'restBetweenRounds' => 90,
                        'blockType' => 'wod',
                        'name' => 'WOD renombrado',
                        'exercises' => [
                            [
                                'exerciseId' => $this->testExercise->getId(),
                                'reps' => 12,
                                'sets' => 3,
                            ],
                        ],
                    ],
                ],
            ])
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->client->request(
            'GET',
            "/trainings/{$trainingId}",
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('WOD renombrado', $data['trainingRounds'][0]['name']);
        $this->assertSame($this->testBenchmark->getId(), $data['trainingRounds'][0]['benchmarkId']);
    }

    public function testDeleteOwnTraining(): void
    {
        $training = new Training();
        $training->setName('To Delete');
        $training->setDiscipline(Discipline::CROSSFIT);
        $training->setTrainingUser($this->testUser);
        $training->setTarget(TargetWorkout::REPBUILDING);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        $trainingId = $training->getId();

        $this->client->request(
            'DELETE',
            "/trainings/{$trainingId}",
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $deletedTraining = $this->entityManager->getRepository(Training::class)->find($trainingId);
        $this->assertNull($deletedTraining);
    }
}
