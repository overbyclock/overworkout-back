<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\SessionStepActionDto;
use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserTrainingLog;
use App\Entity\UserTrainingSessionStep;
use App\Service\TrainingSessionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

        $training = $log->getTraining();

        return $this->json([
            'message' => $result['created'] ? 'Sesión iniciada correctamente' : 'Sesión ya iniciada',
            'log' => $this->normalizer->normalize($log, null, [
                'groups' => [UserTrainingLog::GROUP_READ, Training::GROUP_READ],
            ]),
            'logId' => $log->getId(),
            'currentPosition' => $log->getCurrentPosition(),
            'skillFocus' => $training?->getTrainingLevel()?->getSkillFocus(),
            'estimatedDurationMin' => $training?->getEstimatedDurationMin(),
            'estimatedDurationMax' => $training?->getEstimatedDurationMax(),
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

        $stepsCompleted = 0;
        $stepsSkipped = 0;
        foreach ($log->getSessionSteps() as $step) {
            if (UserTrainingSessionStep::STATUS_COMPLETED === $step->getStatus()) {
                ++$stepsCompleted;
            } else {
                ++$stepsSkipped;
            }
        }

        return $this->json([
            'message' => 'Sesión completada correctamente',
            'log' => $this->normalizer->normalize($log, null, [
                'groups' => [UserTrainingLog::GROUP_READ, Training::GROUP_READ],
            ]),
            'stepsCompleted' => $stepsCompleted,
            'stepsSkipped' => $stepsSkipped,
        ]);
    }

    #[Route('/user/sessions/{logId}', name: 'training_session_show', requirements: ['logId' => '\\d+'], methods: ['GET'])]
    public function getSession(int $logId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $log = $this->findLogOr404($logId);
        $this->assertLogOwnership($log, $user);

        $training = $log->getTraining();

        return $this->json([
            'logId' => $log->getId(),
            'training' => $this->normalizer->normalize($training, null, [
                'groups' => [Training::GROUP_READ],
            ]),
            'currentPosition' => $log->getCurrentPosition(),
            'completedAt' => $log->getCompletedAt()?->format(\DateTimeInterface::ATOM),
            'skillFocus' => $training?->getTrainingLevel()?->getSkillFocus(),
            'estimatedDurationMin' => $training?->getEstimatedDurationMin(),
            'estimatedDurationMax' => $training?->getEstimatedDurationMax(),
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

    #[Route('/user/sessions/{logId}/steps/{stepId}', name: 'training_session_step_action', requirements: ['logId' => '\\d+', 'stepId' => '\\d+'], methods: ['PATCH'])]
    public function applyStepAction(int $logId, int $stepId, #[MapRequestPayload] SessionStepActionDto $dto): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $log = $this->findLogOr404($logId);
        $this->assertLogOwnership($log, $user);
        $step = $this->findStepInLogOr404($log, $stepId);

        try {
            $this->sessionService->applyStepAction($log, $step, $dto->action, $dto->scope);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'message' => 'Paso actualizado correctamente',
            'currentPosition' => $log->getCurrentPosition(),
            'steps' => $this->normalizer->normalize($log->getSessionSteps()->toArray(), null, [
                'groups' => [UserTrainingSessionStep::GROUP_READ],
            ]),
        ]);
    }

    #[Route('/user/sessions/{logId}/abandon', name: 'training_session_abandon', requirements: ['logId' => '\\d+'], methods: ['POST'])]
    public function abandonSession(int $logId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $log = $this->findLogOr404($logId);
        $this->assertLogOwnership($log, $user);

        try {
            $this->sessionService->abandonSession($user, $log);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        }

        return $this->json([
            'message' => 'Sesión abandonada correctamente',
            'logId' => $log->getId(),
            'status' => $log->getStatus(),
        ]);
    }

    #[Route('/user/sessions/{logId}/back', name: 'training_session_back', requirements: ['logId' => '\\d+'], methods: ['POST'])]
    public function goBack(int $logId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $log = $this->findLogOr404($logId);
        $this->assertLogOwnership($log, $user);

        try {
            $this->sessionService->goBack($log);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json([
            'message' => 'Paso anterior reabierto',
            'currentPosition' => $log->getCurrentPosition(),
            'steps' => $this->normalizer->normalize($log->getSessionSteps()->toArray(), null, [
                'groups' => [UserTrainingSessionStep::GROUP_READ],
            ]),
        ]);
    }

    private function findStepInLogOr404(UserTrainingLog $log, int $stepId): UserTrainingSessionStep
    {
        foreach ($log->getSessionSteps() as $step) {
            if ($step->getId() === $stepId) {
                return $step;
            }
        }

        throw new NotFoundHttpException('Paso no encontrado');
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
