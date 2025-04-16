<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserPasswordHashService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;

class UserApiController extends AbstractController
{
    private $userPasswordHashService;
    private $entityManager;
    private $jwtService;

    public function __construct(UserPasswordHashService $userPasswordHashService, EntityManagerInterface $entityManager, JWTService $jwtService)
    {
        $this->userPasswordHashService = $userPasswordHashService;
        $this->entityManager = $entityManager;
        $this->jwtService = $jwtService;
    }

    private function normalizeRoles(array $roles): array
    {
        return array_map(function ($role) {
            if (is_object($role)) {
                return $role->getName();
            }
            return $role;
        }, $roles);
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON format: ' . json_last_error_msg()], 400);
        }

        $nick = $data['nick'] ?? null;
        $email = $data['email'] ?? null;
        $avatar = $data['avatar'] ?? null;
        $plainPassword = $data['password'] ?? null;

        if (!$nick || !$email || !$plainPassword) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            return new JsonResponse(['error' => 'Email already registered'], 400);
        }

        $user = $this->userPasswordHashService->createUser(
            $nick,
            $email,
            $avatar,
            $plainPassword
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->getId(),
                'nick' => $user->getNick(),
                'email' => $user->getEmail(),
                'roles' => $this->normalizeRoles($user->getRoles())
            ]
        ], 201);
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON format: ' . json_last_error_msg()], 400);
        }

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Invalid credentials'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user || !$this->userPasswordHashService->verifyPassword($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $payload = [
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $this->normalizeRoles($user->getRoles()),
        ];

        $tokenData = $this->jwtService->generateToken($payload);

        return new JsonResponse([
            'token' => $tokenData['token'],
            'userId' => $user->getId(),
            'roles' => $this->normalizeRoles($user->getRoles()),
            'expiresAt' => $tokenData['expiresAt']
        ]);
    }

    #[Route('api/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $authenticatedUser = $this->getUser();

        if (!$authenticatedUser instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        if ($authenticatedUser->getId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Action not allowed'], 403);
        }

        return new JsonResponse([
            'nick' => $user->getNick(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()->format('d-m-Y H:i:s'),
            'roles' => $this->normalizeRoles($user->getRoles()),
            'avatar' => $user->getAvatar(),
        ]);
    }

    #[Route('api/user/{id}', name: 'update_user', methods: ['PATCH'])]
    public function updateUser(Request $request, int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 400);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON format: ' . json_last_error_msg()], 400);
        }

        $authenticatedUser = $this->getUser();

        if (!$authenticatedUser instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        if (!in_array('ROLE_ADMIN', $authenticatedUser->getRoles()) && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Action not Allowed'], 403);
        }

        if (isset($data['nick'])) {
            $user->setNick($data['nick']);
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['avatar'])) {
            $user->setAvatar($data['avatar']);
        }

        if (isset($data['password'])) {
            if (!$this->userPasswordHashService->isPasswordValid($data['password'])) {
                return new JsonResponse(['error' => 'The password is weak. It needs to be at least 6 characters long and must include at least one uppercase letter, one lowercase letter, and one number.'], 400);
            }
            $hashedPassword = $this->userPasswordHashService->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User updated successfully'], 200);
    }

    #[Route('api/user/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser($id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $authenticatedUser = $this->getUser();

        if (!$authenticatedUser instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        if ($authenticatedUser->getId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Action not allowed'], 403);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User deleted successfully'], 200);
    }

    #[Route('api/users', name: 'get_all_users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $authenticatedUser = $this->getUser();

        if (!$authenticatedUser instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Action not allowed'], 403);
        }

        $users = $this->entityManager->getRepository(User::class)->findAll();
        $responseData = [];

        foreach ($users as $user) {
            $responseData[] = [
                'avatar' => $user->getAvatar(),
                'id' => $user->getId(),
                'nick' => $user->getNick(),
                'email' => $user->getEmail(),
                'createdAt' => $user->getCreatedAt()->format('d-m-Y H:i:s'),
                'lastlogin' => $user->getLastLogin() ? $user->getLastLogin()->format('d-m-Y H:i:s') : null,
                'roles' => $this->normalizeRoles($user->getRoles()),
            ];
        }

        return new JsonResponse($responseData);
    }
}
