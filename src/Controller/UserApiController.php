<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\LoginDto;
use App\Dto\Request\UserRegistrationDto;
use App\Dto\Request\UserUpdateDto;
use App\Entity\User;
use App\Mapper\UserMapper;
use App\Security\Voter\UserVoter;
use App\Service\JWTService;
use App\Service\UserPasswordHashService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHashService $passwordHashService,
        private readonly JWTService $jwtService,
        private readonly UserMapper $userMapper,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] UserRegistrationDto $dto
    ): JsonResponse {
        // Verificar si el email ya existe
        $existingUser = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $dto->email]);

        if (null !== $existingUser) {
            return $this->json(['error' => 'Email already registered'], 400);
        }

        // Crear usuario
        $user = $this->userMapper->fromRegistrationDto($dto);

        // Hashear contraseña
        $hashedPassword = $this->passwordHashService->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'User registered successfully',
            'user' => $this->normalizer->normalize($user, null, ['groups' => [User::GROUP_READ]]),
        ], 201);
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function login(
        #[MapRequestPayload] LoginDto $dto
    ): JsonResponse {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $dto->email]);

        if (null === $user || !$this->passwordHashService->verifyPassword($user, $dto->password)) {
            return $this->json(['error' => 'Invalid credentials'], 401);
        }

        // Generar token JWT
        $payload = [
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];

        $tokenData = $this->jwtService->generateToken($payload);

        // Actualizar último login
        $user->setLastlogin(new \DateTimeImmutable());
        $this->entityManager->flush();

        return $this->json([
            'token' => $tokenData['token'],
            'user' => $this->normalizer->normalize($user, null, ['groups' => [User::GROUP_READ]]),
            'expiresAt' => $tokenData['expiresAt'],
        ]);
    }

    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
    #[IsGranted(UserVoter::VIEW, subject: 'id')]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (null === $user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Verificar acceso con voter
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);

        $groups = $this->isGranted('ROLE_ADMIN') ? [User::GROUP_READ_ADMIN] : [User::GROUP_READ];

        return $this->json($this->normalizer->normalize($user, null, ['groups' => $groups]));
    }

    #[Route('/users/{id}', name: 'update_user', methods: ['PATCH'])]
    public function updateUser(
        int $id,
        Request $request,
        #[MapRequestPayload] UserUpdateDto $dto
    ): JsonResponse {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (null === $user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No changes provided'], 400);
        }

        $this->userMapper->updateFromDto($user, $dto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'User updated successfully',
            'user' => $this->normalizer->normalize($user, null, ['groups' => [User::GROUP_READ]]),
        ]);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (null === $user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'User deleted successfully']);
    }

    #[Route('/users', name: 'get_all_users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::LIST_ALL);

        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($users, null, ['groups' => [User::GROUP_READ_ADMIN]])
        );
    }
}
