<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Tests de Autenticación JWT
 * 
 * Verifica que el login y registro funcionan correctamente.
 */
class AuthenticationTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        
        // Limpiar usuarios de test antes de cada test
        $this->cleanTestUsers();
    }

    protected function tearDown(): void
    {
        $this->cleanTestUsers();
        parent::tearDown();
    }

    private function cleanTestUsers(): void
    {
        $users = $this->entityManager->getRepository(User::class)
            ->findBy(['nick' => 'testuser']);
        
        foreach ($users as $user) {
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();
    }

    /**
     * Test: Login con credenciales válidas
     */
    public function testLoginWithValidCredentials(): void
    {
        // Crear usuario de prueba
        $user = new User();
        $user->setNick('testuser');
        $user->setEmail('test@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'Test123!'));
        $user->setRoles(['ROLE_USER']);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Hacer petición de login
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nick' => 'testuser',
                'password' => 'Test123!',
            ])
        );

        $response = $client->getResponse();
        
        // Verificar respuesta exitosa
        $this->assertSame(200, $response->getStatusCode());
        
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('userId', $data);
        $this->assertArrayHasKey('nick', $data);
        $this->assertSame('testuser', $data['nick']);
    }

    /**
     * Test: Login con credenciales inválidas
     */
    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nick' => 'nonexistent',
                'password' => 'wrongpassword',
            ])
        );

        $response = $client->getResponse();
        
        // Debe devolver 401 Unauthorized
        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * Test: Acceso a endpoint protegido sin token
     */
    public function testAccessProtectedEndpointWithoutToken(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/trainings');

        $response = $client->getResponse();
        
        // Debe devolver 401 Unauthorized
        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * Test: Registro de nuevo usuario
     */
    public function testRegisterNewUser(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/register',
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

    /**
     * Test: Registro con datos inválidos
     */
    public function testRegisterWithInvalidData(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nick' => '',  // Vacío
                'email' => 'invalid-email',  // Email inválido
                'password' => '123',  // Contraseña débil
            ])
        );

        $response = $client->getResponse();
        
        // Debe devolver 400 Bad Request
        $this->assertSame(400, $response->getStatusCode());
    }
}
