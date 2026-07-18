<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestResult;
use App\Entity\Training;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
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
        private readonly LevelProgressService $levelProgressService,
        private readonly UserLevelProgressRepository $progressRepository,
        private readonly TrainingLevelRepository $levelRepository,
        private readonly NormalizerInterface $normalizer,
        private readonly EntityManagerInterface $entityManager,
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

        if (null === $activeProgress) {
            return $this->json(['error' => 'No active progress found'], 404);
        }

        return $this->json(
            $this->normalizer->normalize($activeProgress, null, [
                'groups' => [UserLevelProgress::GROUP_READ],
            ])
        );
    }

    #[Route('/user/programs/active', name: 'get_active_programs', methods: ['GET'])]
    public function getActivePrograms(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $activeProgresses = $this->progressRepository->findAllActiveByUser($user);
        $programs = [];

        foreach ($activeProgresses as $progress) {
            $program = $progress->getTrainingLevel()->getProgram();
            if (isset($programs[$program->getId()])) {
                continue;
            }

            $programData = $this->normalizer->normalize($program, null, [
                'groups' => [TrainingProgram::GROUP_READ],
            ]);

            $programProgresses = $this->progressRepository->findByUserAndProgram($user, $program);
            $completedLevels = 0;
            $currentLevelNumber = 1;

            foreach ($programProgresses as $programProgress) {
                if (UserLevelProgress::STATUS_COMPLETED === $programProgress->getStatus()) {
                    ++$completedLevels;
                }

                if (\in_array($programProgress->getStatus(), [UserLevelProgress::STATUS_IN_PROGRESS, UserLevelProgress::STATUS_REPEAT], true)) {
                    $currentLevelNumber = $programProgress->getTrainingLevel()->getLevelNumber();
                }
            }

            $totalLevels = $program->getTotalLevels() ?: \count($programProgresses);
            $percentage = $totalLevels > 0 ? (int) round(($completedLevels / $totalLevels) * 100) : 0;

            $programData['progress'] = [
                'completedLevels' => $completedLevels,
                'totalLevels' => $totalLevels,
                'currentLevelNumber' => $currentLevelNumber,
                'percentage' => $percentage,
            ];

            $programs[$program->getId()] = $programData;
        }

        return $this->json(array_values($programs));
    }

    #[Route('/user/progress/{levelId}/test', name: 'submit_test_results', methods: ['POST'])]
    public function submitTestResults(int $levelId, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $level = $this->levelRepository->find($levelId);
        if (null === $level) {
            return $this->json(['error' => 'Level not found'], 404);
        }

        $progress = $this->progressRepository->findByUserAndLevel($user, $level);
        if (null === $progress) {
            return $this->json(['error' => 'Progress not found for this level'], 404);
        }

        if (UserLevelProgress::STATUS_LOCKED === $progress->getStatus()) {
            return $this->json(['error' => 'Level is locked'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['results']) || !\is_array($data['results'])) {
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
        if (null === $level) {
            return $this->json(['error' => 'Level not found'], 404);
        }

        $progress = $this->progressRepository->findByUserAndLevel($user, $level);
        if (null === $progress) {
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

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($programId);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        $levels = $this->levelRepository->findByProgramOrdered($programId);

        if (empty($levels)) {
            return $this->json(['error' => 'Program has no levels'], 404);
        }

        $this->levelProgressService->switchProgram($user, $program);

        return $this->json([
            'message' => 'Progress initialized',
            'program' => $this->normalizer->normalize($program, null, ['groups' => [TrainingProgram::GROUP_READ]]),
            'levelsCount' => \count($levels),
        ]);
    }

    #[Route('/user/programs/{programId}/switch', name: 'switch_program', methods: ['POST'])]
    public function switchProgram(int $programId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($programId);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        $levels = $this->levelRepository->findByProgramOrdered($programId);

        if (empty($levels)) {
            return $this->json(['error' => 'Program has no levels'], 404);
        }

        $this->levelProgressService->switchProgram($user, $program);

        return $this->json([
            'message' => 'Program switched successfully',
            'program' => $this->normalizer->normalize($program, null, ['groups' => [TrainingProgram::GROUP_READ]]),
            'levelsCount' => \count($levels),
        ]);
    }

    #[Route('/user/programs/active/{programId}', name: 'abandon_active_program', requirements: ['programId' => '\\d+'], methods: ['DELETE'])]
    public function abandonActiveProgram(int $programId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($programId);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        $this->levelProgressService->abandonActiveProgram($user, $program);

        return $this->json(['message' => 'Programa abandonado correctamente.']);
    }

    #[Route('/user/programs/{programId}/levels', name: 'get_user_program_levels', requirements: ['programId' => '\\d+'], methods: ['GET'])]
    public function getUserProgramLevels(int $programId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($programId);
        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $levels = $this->levelRepository->findByProgramOrdered($programId);
        $progressItems = $this->progressRepository->findByUserAndProgram($user, $program);
        $progressByLevelId = [];
        foreach ($progressItems as $progressItem) {
            $progressByLevelId[$progressItem->getTrainingLevel()->getId()] = $progressItem;
        }

        $result = [];
        foreach ($levels as $level) {
            $levelData = $this->normalizer->normalize($level, null, [
                'groups' => [TrainingLevel::GROUP_READ],
            ]);

            // El contexto level:read no expone los campos de Training: normalizarlos aparte.
            $levelData['trainings'] = $this->normalizer->normalize($level->getTrainings()->toArray(), null, [
                'groups' => [Training::GROUP_READ],
            ]);

            $progress = $progressByLevelId[$level->getId()] ?? null;
            $levelData['progress'] = null === $progress ? null : $this->normalizer->normalize($progress, null, [
                'groups' => [UserLevelProgress::GROUP_READ],
            ]);
            $result[] = $levelData;
        }

        return $this->json($result);
    }

    #[Route('/user/programs/{programId}/levels/{levelId}', name: 'get_user_level_detail', requirements: ['programId' => '\\d+', 'levelId' => '\\d+'], methods: ['GET'])]
    public function getUserLevelDetail(int $programId, int $levelId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($programId);
        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $level = $this->levelRepository->find($levelId);
        if (null === $level || $level->getProgram()?->getId() !== $programId) {
            return $this->json(['error' => 'Nivel no encontrado'], 404);
        }

        $progress = $this->progressRepository->findByUserAndLevel($user, $level);

        $phases = [];
        foreach ($level->getWeekInfos() as $weekInfo) {
            $weekNumber = $weekInfo->getWeekNumber();
            $phases[$weekNumber] = [
                'id' => $weekInfo->getId(),
                'weekNumber' => $weekNumber,
                'name' => $weekInfo->getName(),
                'focus' => $weekInfo->getFocus(),
                'note' => $weekInfo->getNote(),
                'intensity' => $weekInfo->getIntensity(),
                'sessions' => [],
            ];
        }

        foreach ($level->getTrainings() as $training) {
            $weekNumber = $training->getWeekNumber();
            if (null === $weekNumber || !isset($phases[$weekNumber])) {
                continue;
            }

            $phases[$weekNumber]['sessions'][] = $this->normalizer->normalize($training, null, [
                'groups' => [Training::GROUP_READ_DETAIL],
            ]);
        }

        // Asegurar 4 fases ordenadas, incluso si faltan weekInfos
        $orderedPhases = [];
        for ($weekNumber = 0; $weekNumber < 4; ++$weekNumber) {
            $orderedPhases[] = $phases[$weekNumber] ?? [
                'weekNumber' => $weekNumber,
                'name' => 'Fase '.($weekNumber + 1),
                'focus' => null,
                'note' => null,
                'intensity' => null,
                'sessions' => [],
            ];
        }

        $currentWeek = $progress?->getCurrentWeek() ?? 0;
        $status = $progress?->getStatus() ?? UserLevelProgress::STATUS_LOCKED;
        $isLevelLocked = UserLevelProgress::STATUS_LOCKED === $status;

        foreach ($orderedPhases as $index => &$phase) {
            $phaseNumber = $index;
            if ($isLevelLocked) {
                $phase['status'] = 'locked';
            } elseif ($phaseNumber < $currentWeek) {
                $phase['status'] = 'completed';
            } elseif ($phaseNumber === $currentWeek) {
                $phase['status'] = 'active';
            } else {
                $phase['status'] = 'locked';
            }

            foreach ($phase['sessions'] as $sessionIndex => &$session) {
                if ('active' === $phase['status']) {
                    $session['status'] = 0 === $sessionIndex ? 'active' : 'locked';
                } else {
                    $session['status'] = 'completed' === $phase['status'] ? 'completed' : 'locked';
                }
            }
            unset($session);
        }
        unset($phase);

        $testAvailable = !$isLevelLocked && $currentWeek >= 3;

        return $this->json([
            'level' => $this->normalizer->normalize($level, null, [
                'groups' => [TrainingLevel::GROUP_READ],
            ]),
            'phases' => $orderedPhases,
            'progress' => null === $progress ? null : $this->normalizer->normalize($progress, null, [
                'groups' => [UserLevelProgress::GROUP_READ],
            ]),
            'test' => [
                'available' => $testAvailable,
                'requirements' => $level->getTestRequirements(),
                'latestResult' => null === $progress ? null : $this->normalizer->normalize($progress->getLatestTestResult(), null, [
                    'groups' => [TestResult::GROUP_READ],
                ]),
            ],
        ]);
    }
}
