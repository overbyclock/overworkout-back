<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;
use App\Service\TrainingSessionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_USER')]
final class TrainingSessionApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingSessionService $sessionService,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('/user/trainings/{trainingId}/start', name: 'training_session_start', requirements: ['trainingId' => '\\d+'], methods: ['POST'])]
    public function startSession(int $trainingId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $training = $this->findTrainingOr404($trainingId);

        try {
            $result = $this->sessionService->startSession($user, $training);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $log = $result['log'];

        return $this->json([
            'message' => $result['created'] ? 'Sesión iniciada correctamente' : 'Sesión ya iniciada',
            'log' => $this->normalizer->normalize($log, null, [
                'groups' => [UserTrainingLog::GROUP_READ, Training::GROUP_READ],
            ]),
            'logId' => $log->getId(),
            'currentPosition' => $log->getCurrentPosition(),
            'steps' => $this->normalizer->normalize($log->getSessionSteps()->toArray(), null, [
                'groups' => [UserTrainingSessionStep::GROUP_READ],
            ]),
        ], $result['created'] ? 201 : 200);
    }

    #[Route('/user/trainings/{trainingId}/complete', name: 'training_session_complete', requirements: ['trainingId' => '\\d+'], methods: ['POST'])]
    public function completeSession(int $trainingId, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $training = $this->findTrainingOr404($trainingId);

        $data = json_decode($request->getContent(), true);
        $durationSeconds = isset($data['durationSeconds']) && \is_int($data['durationSeconds']) ? $data['durationSeconds'] : null;
        $xpEarned = isset($data['xpEarned']) && \is_int($data['xpEarned']) ? $data['xpEarned'] : 50;

        try {
            $log = $this->sessionService->completeSession($user, $training, $durationSeconds, $xpEarned);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'message' => 'Sesión completada correctamente',
            'log' => $this->normalizer->normalize($log, null, [
                'groups' => [UserTrainingLog::GROUP_READ, Training::GROUP_READ],
            ]),
        ]);
    }

    #[Route('/user/sessions/{logId}', name: 'training_session_show', requirements: ['logId' => '\\d+'], methods: ['GET'])]
    public function getSession(int $logId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $log = $this->findLogOr404($logId);
        $this->assertLogOwnership($log, $user);

        return $this->json([
            'logId' => $log->getId(),
            'training' => $this->normalizer->normalize($log->getTraining(), null, [
                'groups' => [Training::GROUP_READ],
            ]),
            'currentPosition' => $log->getCurrentPosition(),
            'completedAt' => $log->getCompletedAt()?->format(\DateTimeInterface::ATOM),
            'steps' => $this->normalizer->normalize($log->getSessionSteps()->toArray(), null, [
                'groups' => [UserTrainingSessionStep::GROUP_READ],
            ]),
        ]);
    }

    #[Route('/user/trainings/next', name: 'training_session_next', methods: ['GET'])]
    public function getNextSession(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $nextSession = $this->sessionService->getNextSessionForUser($user);

        if (null === $nextSession) {
            return $this->json(['message' => 'No hay sesiones pendientes', 'session' => null]);
        }

        return $this->json([
            'message' => 'Siguiente sesión encontrada',
            'session' => $nextSession,
        ]);
    }

    private function findTrainingOr404(int $trainingId): Training
    {
        $training = $this->entityManager->getRepository(Training::class)->find($trainingId);

        if (null === $training) {
            throw new NotFoundHttpException('Entrenamiento no encontrado');
        }

        return $training;
    }

    private function findLogOr404(int $logId): UserTrainingLog
    {
        $log = $this->entityManager->getRepository(UserTrainingLog::class)->find($logId);

        if (null === $log) {
            throw new NotFoundHttpException('Sesión no encontrada');
        }

        return $log;
    }

    private function assertLogOwnership(UserTrainingLog $log, User $user): void
    {
        if ($log->getUser()?->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('No tienes acceso a esta sesión');
        }
    }
}
