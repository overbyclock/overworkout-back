<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\JWTService;
use App\Service\UserPasswordHashService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class  UserApiController extends AbstractController
{
    private $userPasswordHashService;
    private $entityManager;

    public function __construct(UserPasswordHashService $userPasswordHashService, EntityManagerInterface $entityManager)
    {
        $this->userPasswordHashService = $userPasswordHashService;
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nick = $data['nick'] ?? null;
        $email = $data['email'] ?? null;
        $plainPassword = $data['password'] ?? null;

        if (!$nick || !$email || !$plainPassword) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $user = $this->userPasswordHashService->createUser($nick, $email, $plainPassword);
        return new JsonResponse(['message' => 'User created successfully'], 201);
    }

    #[Route('/login', name: 'user_login', methods:['POST'])]
    public function login(Request $request, JWTService $jwtService):JsonResponse
    {
      $data = json_decode($request->getContent(),true);

      $email = $data['email'] ?? '';
      $password = $data['password'] ?? '';

      $user = $this->entityManager->getRepository(User::class)->findOneBy(['email'=>$email]);

      if(!$user || !password_verify($password,$user->getPassword())){
        return new JsonResponse(['error'=>'Invalid crendentials'],401);
      }

      $payload = [
        'user_id' => $user->getId(),
        'email' => $user->getEmail(),
        'roles' => $user->getRoles(),
       ];

       $token = $jwtService->generateToken($payload);

      return new JsonResponse(['token' => $token]);
    }

    #[Route('api/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse([
            'nick' => $user->getNick(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()->format('d-m-Y H:i:s'),
            'roles' => $user->getRoles(),
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

        if (isset($data['nick'])) {
            $user->setNick($data['nick']);
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $hashedPassword = $this->userPasswordHashService->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User updated successfully'], 200);
    }

    #[Route('api/user/{id}', name:'delete_user', methods:['DELETE'])]
    public function deleteUser($id): JsonResponse
    {
      $user = $this->entityManager->getRepository(User::class)->find($id);

      if(!$user){
        return new JsonResponse(['error'=> 'User not found'],404);
      }

      $this->entityManager->remove($user);
      $this->entityManager->flush();

      return new JsonResponse(['message'=>'User deleted successfully'],200);
    }

    #[Route('api/users', name: 'get_all_users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $responseData = [];

        foreach ($users as $user) {
            $responseData[] = [
                'id' => $user->getId(),
                'nick' => $user->getNick(),
                'email' => $user->getEmail(),
                'createdAt' => $user->getCreatedAt()->format('d-m-Y H:i:s'),
                'roles' => $user->getRoles(),
            ];
        }

        return new JsonResponse($responseData);
    }
}
