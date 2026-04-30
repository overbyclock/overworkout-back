<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestResult;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use App\Service\LevelProgressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LevelProgressApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LevelProgressService $levelProgressService,
        private readonly UserLevelProgressRepository $progressRepository,
        private readonly TrainingLevelRepository $levelRepository,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('/user/progress', name: 'get_user_progress', methods: ['GET'])]
    public function getUserProgress(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $progressItems = $this->progressRepository->findByUser($user);

        return $this->json(
            $this->normalizer->normalize($progressItems, null, [
                'groups' => [UserLevelProgress::GROUP_READ],
            ])
        );
    }

    #[Route('/user/progress/active', name: 'get_active_progress', methods: ['GET'])]
    public function getActiveProgress(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $activeProgress = $this->levelProgressService->getActiveProgress($user);

        if ($activeProgress === null) {
            return $this->json(['error' => 'No active progress found'], 404);
        }

        return $this->json(
            $this->normalizer->normalize($activeProgress, null, [
                'groups' => [UserLevelProgress::GROUP_READ],
            ])
        );
    }

    #[Route('/user/progress/{levelId}/test', name: 'submit_test_results', methods: ['POST'])]
    public function submitTestResults(int $levelId, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $level = $this->levelRepository->find($levelId);
        if ($level === null) {
            return $this->json(['error' => 'Level not found'], 404);
        }

        $progress = $this->progressRepository->findByUserAndLevel($user, $level);
        if ($progress === null) {
            return $this->json(['error' => 'Progress not found for this level'], 404);
        }

        if ($progress->getStatus() === UserLevelProgress::STATUS_LOCKED) {
            return $this->json(['error' => 'Level is locked'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['results']) || !is_array($data['results'])) {
            return $this->json(['error' => 'Missing or invalid results array'], 400);
        }

        $results = $data['results'];
        $requirements = $data['requirements'] ?? [];
        $notes = $data['notes'] ?? null;

        try {
            $testResult = $this->levelProgressService->submitTestResults(
                $progress,
                $results,
                $requirements,
                $notes
            );

            return $this->json([
                'message' => $testResult->isOverallPassed()
                    ? 'Test passed! Level completed.'
                    : 'Test failed. Repeat cycle required.',
                'passed' => $testResult->isOverallPassed(),
                'testResult' => $this->normalizer->normalize($testResult, null, [
                    'groups' => [TestResult::GROUP_READ],
                ]),
                'progress' => $this->normalizer->normalize($progress, null, [
                    'groups' => [UserLevelProgress::GROUP_READ],
                ]),
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/user/progress/{levelId}/advance-week', name: 'advance_week', methods: ['POST'])]
    public function advanceWeek(int $levelId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $level = $this->levelRepository->find($levelId);
        if ($level === null) {
            return $this->json(['error' => 'Level not found'], 404);
        }

        $progress = $this->progressRepository->findByUserAndLevel($user, $level);
        if ($progress === null) {
            return $this->json(['error' => 'Progress not found'], 404);
        }

        $this->levelProgressService->advanceWeek($progress);

        return $this->json([
            'message' => 'Week advanced',
            'currentWeek' => $progress->getCurrentWeek(),
        ]);
    }

    #[Route('/user/progress/init/{programId}', name: 'init_progress', methods: ['POST'])]
    public function initializeProgress(int $programId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $levels = $this->levelRepository->findByProgramOrdered($programId);

        if (empty($levels)) {
            return $this->json(['error' => 'Program not found or has no levels'], 404);
        }

        $this->levelProgressService->initializeProgress($user, $levels);

        return $this->json([
            'message' => 'Progress initialized',
            'levelsCount' => count($levels),
        ]);
    }
}
