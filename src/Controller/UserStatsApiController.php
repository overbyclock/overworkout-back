<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\TrainingRepository;
use App\Security\Voter\TrainingVoter;
use App\Service\UserStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserStatsApiController extends AbstractController
{
    public function __construct(
        private readonly UserStatsService $userStatsService,
        private readonly TrainingRepository $trainingRepository,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('/user/stats', name: 'user_stats_dashboard', methods: ['GET'])]
    public function getDashboardStats(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($this->userStatsService->getDashboardStats($user));
    }

    #[Route('/user/trainings/{id}/complete', name: 'user_training_complete', methods: ['POST'])]
    public function completeTraining(int $id, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $training = $this->trainingRepository->find($id);

        if (null === $training) {
            return $this->json(['error' => 'Entrenamiento no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingVoter::VIEW, $training);

        $data = json_decode($request->getContent(), true);
        $context = \is_array($data) ? $data : [];

        $log = $this->userStatsService->recordTrainingCompletion($user, $training, $context);

        return $this->json([
            'message' => 'Entrenamiento completado',
            'log' => $this->normalizer->normalize($log, null, ['groups' => ['user_training_log:read']]),
            'stats' => $this->userStatsService->getDashboardStats($user),
        ]);
    }
}
