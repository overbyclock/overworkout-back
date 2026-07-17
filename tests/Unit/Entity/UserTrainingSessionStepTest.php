<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\UserTrainingSessionStep;
use PHPUnit\Framework\TestCase;

final class UserTrainingSessionStepTest extends TestCase
{
    public function testNewStepIsPending(): void
    {
        $step = new UserTrainingSessionStep();

        $this->assertSame(UserTrainingSessionStep::STATUS_PENDING, $step->getStatus());
        $this->assertTrue($step->isPending());
        $this->assertNull($step->getCompletedAt());
    }

    public function testCompleteMarksStepAsCompleted(): void
    {
        $step = new UserTrainingSessionStep();
        $step->complete();

        $this->assertSame(UserTrainingSessionStep::STATUS_COMPLETED, $step->getStatus());
        $this->assertFalse($step->isPending());
        $this->assertNotNull($step->getCompletedAt());
    }

    public function testSkipMarksStepAsSkipped(): void
    {
        $step = new UserTrainingSessionStep();
        $step->skip();

        $this->assertSame(UserTrainingSessionStep::STATUS_SKIPPED, $step->getStatus());
        $this->assertFalse($step->isPending());
        $this->assertNotNull($step->getCompletedAt());
    }

    public function testReopenReturnsStepToPending(): void
    {
        $step = new UserTrainingSessionStep();
        $step->complete();
        $step->reopen();

        $this->assertSame(UserTrainingSessionStep::STATUS_PENDING, $step->getStatus());
        $this->assertTrue($step->isPending());
        $this->assertNull($step->getCompletedAt());
    }
}
