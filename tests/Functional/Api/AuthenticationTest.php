<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthenticationTest extends WebTestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $user = new User();
        $user->setNick('testuser');
        $user->setEmail('test@example.com');
        $user->setPassword($passwordHasher->hashPassword($user, 'Test123!'));
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        $client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'password' => 'Test123!',
            ])
        );

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('nick', $data['user']);
        $this->assertSame('testuser', $data['user']['nick']);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'nonexistent@example.com',
                'password' => 'wrongpassword',
            ])
        );

        $response = $client->getResponse();
        $this->assertSame(401, $response->getStatusCode());
    }

    public function testAccessProtectedEndpointWithoutToken(): void
    {
        $client = static::createClient();
        $client->request('GET', '/trainings');

        $response = $client->getResponse();
        $this->assertSame(401, $response->getStatusCode());
    }

    public function testRegisterNewUser(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nick' => 'testuser',
                'email' => 'test@example.com',
                'password' => 'Test123!',
            ])
        );

        $response = $client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('user', $data);
    }

    public function testRegisterWithInvalidData(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nick' => '',
                'email' => 'invalid-email',
                'password' => '123',
            ])
        );

        $response = $client->getResponse();
        $this->assertSame(422, $response->getStatusCode());
    }
}
