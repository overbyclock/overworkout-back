<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Achievement;
use App\Entity\TestResult;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Repository\AchievementRepository;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Desbloqueo de logros de usuario.
 *
 * Los métodos no hacen flush: lo realiza el llamador al final de su flujo
 * (o unlockByCode con $flush = true para uso directo desde controladores).
 */
class AchievementUnlockService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AchievementRepository $achievementRepository,
        private readonly UserAchievementRepository $userAchievementRepository,
    ) {
    }

    /**
     * Desbloquea un logro para un usuario. Idempotente: devuelve null si ya lo tenía.
     *
     * @param array<string, mixed>|null $context
     */
    public function unlock(User $user, Achievement $achievement, ?array $context = null): ?UserAchievement
    {
        if ($this->userAchievementRepository->hasAchievement($user, $achievement)) {
            return null;
        }

        $userAchievement = (new UserAchievement())
            ->setUser($user)
            ->setAchievement($achievement)
            ->setContext($context);

        $this->entityManager->persist($userAchievement);

        return $userAchievement;
    }

    /**
     * Desbloquea un logro de tipo hito por su código (primera ejecución en sesión).
     *
     * @param array<string, mixed>|null $context
     *
     * @throws \InvalidArgumentException si el logro no existe o no es de tipo hito
     */
    public function unlockHitoByCode(User $user, string $code, ?array $context = null): ?UserAchievement
    {
        $achievement = $this->achievementRepository->findOneBy(['code' => $code]);

        if (null === $achievement) {
            throw new \InvalidArgumentException('Logro no encontrado: '.$code);
        }

        if (Achievement::TYPE_HITO !== $achievement->getType()) {
            throw new \InvalidArgumentException('El logro '.$code.' no es de tipo hito y no puede desbloquearse manualmente.');
        }

        $userAchievement = $this->unlock($user, $achievement, $context);
        $this->entityManager->flush();

        return $userAchievement;
    }

    /**
     * Desbloquea los logros de tipo test asociados a un nivel (al superar su test).
     *
     * @param array<string, mixed>|null $context
     *
     * @return int número de logros desbloqueados
     */
    public function unlockTestAchievementsForLevel(User $user, TrainingLevel $level, ?array $context = null): int
    {
        $unlocked = 0;

        foreach ($this->achievementRepository->findByLevelAndType($level, Achievement::TYPE_TEST) as $achievement) {
            // Los logros por ítem se desbloquean en cada envío de test, no al completar el nivel
            if (\in_array($achievement->getRequirementType(), [Achievement::REQ_TEST_ITEM_MIN, Achievement::REQ_TEST_ITEM_OBJ], true)) {
                continue;
            }

            if (null !== $this->unlock($user, $achievement, $context)) {
                ++$unlocked;
            }
        }

        return $unlocked;
    }

    /**
     * Desbloquea los logros por ítem de test (requirementType test_item_min / test_item_obj).
     *
     * Se evalúan con CUALQUIER test enviado del nivel, se supere o no globalmente:
     * el umbral es el minimum (test_item_min) o el target (test_item_obj) del ítem,
     * tomado de requirementValue si está fijado o del requirement del ítem si no.
     *
     * @param array<int, array{name: string, minimum?: int|float, target?: int|float|null}> $requirements
     * @param array<string, mixed>|null                                                     $context
     *
     * @return int número de logros desbloqueados
     */
    public function unlockTestItemAchievements(User $user, TrainingLevel $level, TestResult $testResult, array $requirements, ?array $context = null): int
    {
        $unlocked = 0;

        foreach ($this->achievementRepository->findByLevelAndType($level, Achievement::TYPE_TEST) as $achievement) {
            $requirementType = $achievement->getRequirementType();
            if (Achievement::REQ_TEST_ITEM_MIN !== $requirementType && Achievement::REQ_TEST_ITEM_OBJ !== $requirementType) {
                continue;
            }

            $itemName = $achievement->getRequirementKey();
            if (null === $itemName) {
                continue;
            }

            $result = array_find($testResult->getResults(), static fn (array $r): bool => ($r['name'] ?? null) === $itemName);
            if (null === $result || null === ($result['value'] ?? null)) {
                continue;
            }

            $requirement = array_find($requirements, static fn (array $r): bool => $r['name'] === $itemName);
            $threshold = $achievement->getRequirementValue()
                ?? (Achievement::REQ_TEST_ITEM_OBJ === $requirementType
                    ? ($requirement['target'] ?? null)
                    : ($requirement['minimum'] ?? null));

            if (null === $threshold || $result['value'] < $threshold) {
                continue;
            }

            if (null !== $this->unlock($user, $achievement, $context)) {
                ++$unlocked;
            }
        }

        return $unlocked;
    }
}
