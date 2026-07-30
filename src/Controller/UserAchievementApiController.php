<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserAchievement;
use App\Repository\AchievementRepository;
use App\Repository\UserAchievementRepository;
use App\Repository\UserLevelProgressRepository;
use App\Service\AchievementUnlockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserAchievementApiController extends AbstractController
{
    private const SERIALIZATION_GROUPS = [UserAchievement::GROUP_READ, 'achievement:read'];

    public function __construct(
        private readonly AchievementUnlockService $achievementUnlockService,
        private readonly AchievementRepository $achievementRepository,
        private readonly UserAchievementRepository $userAchievementRepository,
        private readonly UserLevelProgressRepository $progressRepository,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * Logros desbloqueados por el usuario autenticado.
     */
    #[Route('/user/achievements', name: 'get_user_achievements', methods: ['GET'])]
    public function getUserAchievements(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(
            $this->normalizer->normalize($this->userAchievementRepository->findByUser($user), null, [
                'groups' => self::SERIALIZATION_GROUPS,
            ])
        );
    }

    /**
     * Desbloquea un logro de tipo hito (primera ejecución de un movimiento en sesión).
     * Idempotente: si ya estaba desbloqueado, responde 200 con unlocked=false.
     */
    #[Route('/user/achievements/unlock', name: 'unlock_user_achievement', methods: ['POST'])]
    public function unlockAchievement(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? null;

        if (!\is_string($code) || '' === $code) {
            return $this->json(['error' => 'Falta el código del logro (code).'], 400);
        }

        // El logro debe pertenecer a un programa activo del usuario (o ser global)
        $achievement = $this->achievementRepository->findOneBy(['code' => $code]);
        if (null !== $achievement && null !== $achievement->getProgram()) {
            $activeProgresses = $this->progressRepository->findActiveByUserAndProgram($user, $achievement->getProgram());
            if ([] === $activeProgresses) {
                return $this->json(['error' => 'El logro pertenece a un programa que no tienes activo.'], 403);
            }
        }

        try {
            $userAchievement = $this->achievementUnlockService->unlockHitoByCode(
                $user,
                $code,
                \is_array($data['context'] ?? null) ? $data['context'] : null,
            );
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        if (null === $userAchievement) {
            return $this->json([
                'message' => 'El logro ya estaba desbloqueado.',
                'unlocked' => false,
            ]);
        }

        return $this->json([
            'message' => 'Logro desbloqueado.',
            'unlocked' => true,
            'userAchievement' => $this->normalizer->normalize($userAchievement, null, [
                'groups' => self::SERIALIZATION_GROUPS,
            ]),
        ], 201);
    }
}
