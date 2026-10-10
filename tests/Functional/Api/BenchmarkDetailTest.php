<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Benchmark;
use App\Entity\BenchmarkExercise;
use App\Entity\Exercises;
use App\Entity\User;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class BenchmarkDetailTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    private ?string $authToken = null;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $user = new User();
        $user->setNick('benchmarktest');
        $user->setEmail('benchmark@test.com');
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
            json_encode([
                'email' => 'benchmark@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    public function testGetBenchmarkDetailExposesExerciseRepsPattern(): void
    {
        $exercise = new Exercises();
        $exercise->setName('Thruster');
        $exercise->setPrimaryMuscleGroup(MuscleGroup::SHOULDERS);
        $exercise->setLevel(Levels::BEGINNER);

        $benchmark = new Benchmark();
        $benchmark->setName('Fran');
        $benchmark->setType('for_time');
        $benchmark->setPartitioningAllowed(false);

        $benchmarkExercise = new BenchmarkExercise();
        $benchmarkExercise->setBenchmark($benchmark);
        $benchmarkExercise->setExercise($exercise);
        $benchmarkExercise->setPosition(0);
        $benchmarkExercise->setReps(21);
        $benchmarkExercise->setRepsPattern('21-15-9');

        $this->entityManager->persist($exercise);
        $benchmark->addExercise($benchmarkExercise);
        $this->entityManager->persist($benchmark);
        $this->entityManager->flush();

        $this->client->request(
            'GET',
            '/benchmarks/'.$benchmark->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('21-15-9', $data['exercises'][0]['repsPattern']);
    }
}
