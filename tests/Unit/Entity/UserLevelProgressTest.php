<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\TestResult;
use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use PHPUnit\Framework\TestCase;

class UserLevelProgressTest extends TestCase
{
    private UserLevelProgress $progress;

    protected function setUp(): void
    {
        $this->progress = new UserLevelProgress();
    }

    public function testInitialState(): void
    {
        $this->assertNull($this->progress->getId());
        $this->assertSame(UserLevelProgress::STATUS_LOCKED, $this->progress->getStatus());
        $this->assertSame(0, $this->progress->getCyclesCompleted());
        $this->assertSame(0, $this->progress->getCurrentWeek());
        $this->assertNotNull($this->progress->getStartedAt());
        $this->assertNull($this->progress->getCompletedAt());
        $this->assertCount(0, $this->progress->getTestResults());
    }

    public function testSettersAndGetters(): void
    {
        $user = new User();
        $user->setNick('testuser');

        $level = new TrainingLevel();

        $this->progress->setUser($user);
        $this->assertSame($user, $this->progress->getUser());

        $this->progress->setTrainingLevel($level);
        $this->assertSame($level, $this->progress->getTrainingLevel());

        $this->progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $this->progress->getStatus());

        $this->progress->setCyclesCompleted(2);
        $this->assertSame(2, $this->progress->getCyclesCompleted());

        $this->progress->setCurrentWeek(2);
        $this->assertSame(2, $this->progress->getCurrentWeek());

        $completedAt = new \DateTimeImmutable('2026-04-28');
        $this->progress->setCompletedAt($completedAt);
        $this->assertSame($completedAt, $this->progress->getCompletedAt());
    }

    public function testAdvanceWeek(): void
    {
        $this->progress->setCurrentWeek(0);
        $this->progress->advanceWeek();
        $this->assertSame(1, $this->progress->getCurrentWeek());

        $this->progress->advanceWeek();
        $this->assertSame(2, $this->progress->getCurrentWeek());

        $this->progress->advanceWeek();
        $this->assertSame(3, $this->progress->getCurrentWeek());

        // No debería pasar de 3 (semana 0-3 = 4 semanas)
        $this->progress->advanceWeek();
        $this->assertSame(3, $this->progress->getCurrentWeek());
    }

    public function testIncrementCycle(): void
    {
        $this->progress->setCyclesCompleted(1);
        $this->progress->setCurrentWeek(3);
        $this->progress->setStatus(UserLevelProgress::STATUS_COMPLETED);

        $this->progress->incrementCycle();

        $this->assertSame(2, $this->progress->getCyclesCompleted());
        $this->assertSame(0, $this->progress->getCurrentWeek());
        $this->assertSame(UserLevelProgress::STATUS_IN_PROGRESS, $this->progress->getStatus());
    }

    public function testTestResultsCollection(): void
    {
        $result = new TestResult();
        $result->setLevelNumber(1);
        $result->setCycleNumber(0);

        $this->progress->addTestResult($result);
        $this->assertCount(1, $this->progress->getTestResults());
        $this->assertSame($this->progress, $result->getUserLevelProgress());

        // No duplicar
        $this->progress->addTestResult($result);
        $this->assertCount(1, $this->progress->getTestResults());

        $this->progress->removeTestResult($result);
        $this->assertCount(0, $this->progress->getTestResults());
    }

    public function testGetLatestTestResult(): void
    {
        $this->assertNull($this->progress->getLatestTestResult());

        $result1 = new TestResult();
        $result1->setLevelNumber(1);

        $result2 = new TestResult();
        $result2->setLevelNumber(2);

        $this->progress->addTestResult($result1);
        $this->progress->addTestResult($result2);

        $this->assertSame($result2, $this->progress->getLatestTestResult());
    }

    public function testStatusConstants(): void
    {
        $this->assertSame('locked', UserLevelProgress::STATUS_LOCKED);
        $this->assertSame('in_progress', UserLevelProgress::STATUS_IN_PROGRESS);
        $this->assertSame('completed', UserLevelProgress::STATUS_COMPLETED);
        $this->assertSame('repeat', UserLevelProgress::STATUS_REPEAT);
    }
}
