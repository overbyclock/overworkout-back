<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\UserProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserProfileApiController extends AbstractController
{
    public function __construct(
        private readonly UserProfileService $userProfileService,
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('/user/profile/setup', name: 'user_profile_setup', methods: ['POST'])]
    public function setup(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        if (!\is_array($data)) {
            return $this->json(['error' => 'Invalid JSON body'], 400);
        }

        $this->userProfileService->updateProfile($user, $data);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Perfil actualizado correctamente',
            'profile' => $this->normalizer->normalize($user, null, [
                'groups' => [User::GROUP_READ],
            ]),
        ]);
    }

    #[Route('/user/profile/assessment', name: 'user_profile_assessment', methods: ['POST'])]
    public function assessment(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        if (!\is_array($data) || !isset($data['results']) || !\is_array($data['results'])) {
            return $this->json(['error' => 'Se requiere un array de resultados'], 400);
        }

        // Persistir preferencias del onboarding si se proporcionan
        $profileData = [
            'trainingGoal' => $data['trainingGoal'] ?? $user->getTrainingGoal(),
            'estimatedLevel' => $data['estimatedLevel'] ?? $user->getEstimatedLevel(),
        ];
        $this->userProfileService->updateProfile($user, $profileData);

        $recommendedProgram = $this->userProfileService->recommendProgram(
            $data['results'],
            $profileData
        );

        if (null === $recommendedProgram) {
            return $this->json([
                'error' => 'No se encontró un programa recomendado. Contacta con soporte.',
            ], 404);
        }

        // Recompensa por completar la evaluación
        $user->addXp(100);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Evaluación completada',
            'recommendedProgram' => $recommendedProgram,
        ]);
    }
}
