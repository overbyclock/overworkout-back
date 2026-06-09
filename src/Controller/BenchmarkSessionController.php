<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\FinishBenchmarkAttemptDto;
use App\Dto\Request\LogBenchmarkExerciseDto;
use App\Dto\Request\StartBenchmarkAttemptDto;
use App\Entity\User;
use App\Entity\UserBenchmarkAttempt;
use App\Security\Voter\BenchmarkAttemptVoter;
use App\Service\BenchmarkSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BenchmarkSessionController extends AbstractController
{
    public function __construct(
        private readonly BenchmarkSessionService $sessionService,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('/benchmarks/{id}/start', name: 'benchmark_start_attempt', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function startAttempt(
        int $id,
        #[MapRequestPayload] StartBenchmarkAttemptDto $dto
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $attempt = $this->sessionService->startAttempt($id, $user, $dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'message' => 'Intento iniciado correctamente',
            'attempt' => $this->normalizer->normalize($attempt, null, ['groups' => [UserBenchmarkAttempt::GROUP_READ]]),
        ], 201);
    }

    #[Route('/benchmarks/attempts/{id}/log-exercise', name: 'benchmark_log_exercise', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function logExercise(
        int $id,
        #[MapRequestPayload] LogBenchmarkExerciseDto $dto
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(BenchmarkAttemptVoter::EDIT, $id);

        try {
            $log = $this->sessionService->logExercise($id, $user, $dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'message' => 'Ejercicio registrado correctamente',
            'log' => $this->normalizer->normalize($log, null, ['groups' => [UserBenchmarkAttempt::GROUP_READ]]),
        ], 201);
    }

    #[Route('/benchmarks/attempts/{id}/finish', name: 'benchmark_finish_attempt', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function finishAttempt(
        int $id,
        #[MapRequestPayload] FinishBenchmarkAttemptDto $dto
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(BenchmarkAttemptVoter::EDIT, $id);

        try {
            $attempt = $this->sessionService->finishAttempt($id, $user, $dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'message' => 'Intento finalizado correctamente',
            'attempt' => $this->normalizer->normalize($attempt, null, ['groups' => [UserBenchmarkAttempt::GROUP_READ]]),
        ]);
    }

    #[Route('/benchmarks/attempts/{id}', name: 'benchmark_get_attempt', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getAttempt(int $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(BenchmarkAttemptVoter::VIEW, $id);

        try {
            $attempt = $this->sessionService->getAttempt($id, $user);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }

        return $this->json(
            $this->normalizer->normalize($attempt, null, ['groups' => [UserBenchmarkAttempt::GROUP_READ]])
        );
    }

    #[Route('/benchmarks/attempts', name: 'benchmark_list_attempts', methods: ['GET'])]
    public function listAttempts(\Symfony\Component\HttpFoundation\Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(BenchmarkAttemptVoter::LIST_OWN);

        $benchmarkId = $request->query->getInt('benchmark_id', 0) ?: null;

        $attempts = $this->sessionService->listUserAttempts($user, $benchmarkId);

        return $this->json(
            $this->normalizer->normalize($attempts, null, ['groups' => [UserBenchmarkAttempt::GROUP_READ]])
        );
    }

    #[Route('/benchmarks/attempts/{id}', name: 'benchmark_delete_attempt', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteAttempt(int $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted(BenchmarkAttemptVoter::DELETE, $id);

        try {
            $this->sessionService->deleteAttempt($id, $user);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json(['message' => 'Intento eliminado correctamente']);
    }
}
