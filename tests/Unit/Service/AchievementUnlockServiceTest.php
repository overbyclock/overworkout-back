<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Achievement;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Repository\AchievementRepository;
use App\Repository\UserAchievementRepository;
use App\Service\AchievementUnlockService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class AchievementUnlockServiceTest extends TestCase
{
    public function testUnlockCreatesUserAchievement(): void
    {
        $user = new User();
        $achievement = $this->createAchievement('PRIMERA_DOMINADA');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, userAchievementRepository: $userAchievementRepository);

        $userAchievementRepository->expects(self::once())
            ->method('hasAchievement')
            ->with($user, $achievement)
            ->willReturn(false);

        $entityManager->expects(self::once())
            ->method('persist')
            ->with(self::isInstanceOf(UserAchievement::class));

        $userAchievement = $service->unlock($user, $achievement, ['levelNumber' => 4]);

        self::assertInstanceOf(UserAchievement::class, $userAchievement);
        self::assertSame($user, $userAchievement->getUser());
        self::assertSame($achievement, $userAchievement->getAchievement());
        self::assertSame(['levelNumber' => 4], $userAchievement->getContext());
        self::assertNotNull($userAchievement->getUnlockedAt());
    }

    public function testUnlockIsIdempotent(): void
    {
        $user = new User();
        $achievement = $this->createAchievement('PRIMERA_DOMINADA');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, userAchievementRepository: $userAchievementRepository);

        $userAchievementRepository->expects(self::once())
            ->method('hasAchievement')
            ->willReturn(true);

        $entityManager->expects(self::never())->method('persist');

        self::assertNull($service->unlock($user, $achievement));
    }

    public function testUnlockHitoByCodeThrowsWhenNotFound(): void
    {
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $service = $this->createService(achievementRepository: $achievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Logro no encontrado');

        $service->unlockHitoByCode(new User(), 'NO_EXISTE');
    }

    public function testUnlockHitoByCodeThrowsWhenNotHito(): void
    {
        $achievement = $this->createAchievement('NIVEL_4_COMPLETADO', Achievement::TYPE_TEST);

        $achievementRepository = $this->createMock(AchievementRepository::class);
        $service = $this->createService(achievementRepository: $achievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($achievement);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no es de tipo hito');

        $service->unlockHitoByCode(new User(), 'NIVEL_4_COMPLETADO');
    }

    public function testUnlockHitoByCodeSuccess(): void
    {
        $user = new User();
        $achievement = $this->createAchievement('PRIMER_CROW', Achievement::TYPE_HITO);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, $achievementRepository, $userAchievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findOneBy')
            ->willReturn($achievement);

        $userAchievementRepository->expects(self::once())
            ->method('hasAchievement')
            ->willReturn(false);

        $entityManager->expects(self::once())->method('persist');
        $entityManager->expects(self::once())->method('flush');

        $userAchievement = $service->unlockHitoByCode($user, 'PRIMER_CROW');

        self::assertInstanceOf(UserAchievement::class, $userAchievement);
    }

    public function testUnlockTestAchievementsForLevel(): void
    {
        $user = new User();
        $level = new TrainingLevel();
        $achievementA = $this->createAchievement('A', Achievement::TYPE_TEST);
        $achievementB = $this->createAchievement('B', Achievement::TYPE_TEST);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, $achievementRepository, $userAchievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findByLevelAndType')
            ->with($level, Achievement::TYPE_TEST)
            ->willReturn([$achievementA, $achievementB]);

        // El primero se desbloquea, el segundo ya lo tenía
        $userAchievementRepository->expects(self::exactly(2))
            ->method('hasAchievement')
            ->willReturnOnConsecutiveCalls(false, true);

        $entityManager->expects(self::once())->method('persist');

        $unlocked = $service->unlockTestAchievementsForLevel($user, $level, ['levelNumber' => 4]);

        self::assertSame(1, $unlocked);
    }

    public function testUnlockTestItemAchievementsUnlocksWhenMinimumReached(): void
    {
        $user = new User();
        $level = new TrainingLevel();
        $achievement = $this->createAchievement('PRIMERA_PLANCHA_30', Achievement::TYPE_TEST);
        $achievement->setRequirementType(Achievement::REQ_TEST_ITEM_MIN);
        $achievement->setRequirementKey('Plancha frontal');
        $achievement->setRequirementValue(30);

        $testResult = new \App\Entity\TestResult();
        $testResult->setResults([['name' => 'Plancha frontal', 'value' => 35, 'passed' => true]]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, $achievementRepository, $userAchievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findByLevelAndType')
            ->willReturn([$achievement]);

        $userAchievementRepository->expects(self::once())
            ->method('hasAchievement')
            ->willReturn(false);

        $entityManager->expects(self::once())->method('persist');

        $unlocked = $service->unlockTestItemAchievements(
            $user,
            $level,
            $testResult,
            [['name' => 'Plancha frontal', 'minimum' => 30]],
        );

        self::assertSame(1, $unlocked);
    }

    public function testUnlockTestItemAchievementsSkipsBelowThreshold(): void
    {
        $user = new User();
        $level = new TrainingLevel();
        $achievement = $this->createAchievement('PRIMERA_PLANCHA_30', Achievement::TYPE_TEST);
        $achievement->setRequirementType(Achievement::REQ_TEST_ITEM_MIN);
        $achievement->setRequirementKey('Plancha frontal');

        $testResult = new \App\Entity\TestResult();
        $testResult->setResults([['name' => 'Plancha frontal', 'value' => 25, 'passed' => false]]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, $achievementRepository, $userAchievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findByLevelAndType')
            ->willReturn([$achievement]);

        $userAchievementRepository->expects(self::never())->method('hasAchievement');
        $entityManager->expects(self::never())->method('persist');

        $unlocked = $service->unlockTestItemAchievements(
            $user,
            $level,
            $testResult,
            [['name' => 'Plancha frontal', 'minimum' => 30]],
        );

        self::assertSame(0, $unlocked);
    }

    public function testUnlockTestItemAchievementsUsesTargetForObjectiveType(): void
    {
        $user = new User();
        $level = new TrainingLevel();
        // "12 flexiones": se gana con la marca objetivo, no el mínimo
        $achievement = $this->createAchievement('DOCE_FLEXIONES', Achievement::TYPE_TEST);
        $achievement->setRequirementType(Achievement::REQ_TEST_ITEM_OBJ);
        $achievement->setRequirementKey('Flexión completa');

        $testResult = new \App\Entity\TestResult();
        $testResult->setResults([['name' => 'Flexión completa', 'value' => 12, 'passed' => true]]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, $achievementRepository, $userAchievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findByLevelAndType')
            ->willReturn([$achievement]);

        $userAchievementRepository->expects(self::once())
            ->method('hasAchievement')
            ->willReturn(false);

        $entityManager->expects(self::once())->method('persist');

        // 12 >= objetivo 12 aunque solo 8 >= mínimo 8 era necesario para el test
        $unlocked = $service->unlockTestItemAchievements(
            $user,
            $level,
            $testResult,
            [['name' => 'Flexión completa', 'minimum' => 8, 'target' => 12]],
        );

        self::assertSame(1, $unlocked);
    }

    public function testUnlockTestItemAchievementsIgnoresLevelCompletionAchievements(): void
    {
        $user = new User();
        $level = new TrainingLevel();
        $levelAchievement = $this->createAchievement('NIVEL_COMPLETADO', Achievement::TYPE_TEST);

        $testResult = new \App\Entity\TestResult();
        $testResult->setResults([['name' => 'Plancha frontal', 'value' => 60]]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $achievementRepository = $this->createMock(AchievementRepository::class);
        $userAchievementRepository = $this->createMock(UserAchievementRepository::class);
        $service = $this->createService($entityManager, $achievementRepository, $userAchievementRepository);

        $achievementRepository->expects(self::once())
            ->method('findByLevelAndType')
            ->willReturn([$levelAchievement]);

        $userAchievementRepository->expects(self::never())->method('hasAchievement');
        $entityManager->expects(self::never())->method('persist');

        $unlocked = $service->unlockTestItemAchievements($user, $level, $testResult, []);

        self::assertSame(0, $unlocked);
    }

    private function createService(
        ?EntityManagerInterface $entityManager = null,
        ?AchievementRepository $achievementRepository = null,
        ?UserAchievementRepository $userAchievementRepository = null,
    ): AchievementUnlockService {
        return new AchievementUnlockService(
            $entityManager ?? $this->createStub(EntityManagerInterface::class),
            $achievementRepository ?? $this->createStub(AchievementRepository::class),
            $userAchievementRepository ?? $this->createStub(UserAchievementRepository::class),
        );
    }

    private function createAchievement(string $code, ?string $type = null): Achievement
    {
        $achievement = new Achievement();
        $achievement->setCode($code);
        $achievement->setName($code);
        $achievement->setCategory('skill');
        $achievement->setType($type);

        return $achievement;
    }
}
