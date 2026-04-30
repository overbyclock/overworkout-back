<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\TestResult;
use App\Entity\UserLevelProgress;
use PHPUnit\Framework\TestCase;

class TestResultTest extends TestCase
{
    private TestResult $testResult;

    protected function setUp(): void
    {
        $this->testResult = new TestResult();
    }

    public function testInitialState(): void
    {
        $this->assertNull($this->testResult->getId());
        $this->assertSame(0, $this->testResult->getLevelNumber());
        $this->assertSame(0, $this->testResult->getCycleNumber());
        $this->assertNotNull($this->testResult->getTestDate());
        $this->assertSame([], $this->testResult->getResults());
        $this->assertFalse($this->testResult->isOverallPassed());
        $this->assertNull($this->testResult->getNotes());
    }

    public function testSettersAndGetters(): void
    {
        $progress = new UserLevelProgress();

        $this->testResult->setUserLevelProgress($progress);
        $this->assertSame($progress, $this->testResult->getUserLevelProgress());

        $this->testResult->setLevelNumber(5);
        $this->assertSame(5, $this->testResult->getLevelNumber());

        $this->testResult->setCycleNumber(2);
        $this->assertSame(2, $this->testResult->getCycleNumber());

        $date = new \DateTimeImmutable('2026-04-28');
        $this->testResult->setTestDate($date);
        $this->assertSame($date, $this->testResult->getTestDate());

        $results = [
            ['name' => 'Pull-ups', 'value' => 12],
            ['name' => 'Push-ups', 'value' => 25],
        ];
        $this->testResult->setResults($results);
        $this->assertSame($results, $this->testResult->getResults());

        $this->testResult->setOverallPassed(true);
        $this->assertTrue($this->testResult->isOverallPassed());

        $this->testResult->setNotes('Felt strong today');
        $this->assertSame('Felt strong today', $this->testResult->getNotes());
    }

    public function testEvaluateAllPassed(): void
    {
        $this->testResult->setResults([
            ['name' => 'Pull-ups', 'value' => 12],
            ['name' => 'Push-ups', 'value' => 25],
            ['name' => 'Squats', 'value' => 30],
            ['name' => 'Plank', 'value' => 60],
        ]);

        $requirements = [
            ['name' => 'Pull-ups', 'minimum' => 10],
            ['name' => 'Push-ups', 'minimum' => 20],
            ['name' => 'Squats', 'minimum' => 25],
            ['name' => 'Plank', 'minimum' => 45],
        ];

        $this->testResult->evaluate($requirements);

        $this->assertTrue($this->testResult->isOverallPassed());

        $results = $this->testResult->getResults();
        $this->assertTrue($results[0]['passed']);
        $this->assertTrue($results[1]['passed']);
        $this->assertTrue($results[2]['passed']);
        $this->assertTrue($results[3]['passed']);
    }

    public function testEvaluatePartialPass(): void
    {
        // 3 de 4 pasan → overallPassed = true (threshold = 3)
        $this->testResult->setResults([
            ['name' => 'Pull-ups', 'value' => 12],
            ['name' => 'Push-ups', 'value' => 15], // fail
            ['name' => 'Squats', 'value' => 30],
            ['name' => 'Plank', 'value' => 60],
        ]);

        $requirements = [
            ['name' => 'Pull-ups', 'minimum' => 10],
            ['name' => 'Push-ups', 'minimum' => 20],
            ['name' => 'Squats', 'minimum' => 25],
            ['name' => 'Plank', 'minimum' => 45],
        ];

        $this->testResult->evaluate($requirements);

        $this->assertTrue($this->testResult->isOverallPassed());

        $results = $this->testResult->getResults();
        $this->assertTrue($results[0]['passed']);
        $this->assertFalse($results[1]['passed']);
        $this->assertTrue($results[2]['passed']);
        $this->assertTrue($results[3]['passed']);
    }

    public function testEvaluateFail(): void
    {
        // Solo 2 de 4 pasan → overallPassed = false
        $this->testResult->setResults([
            ['name' => 'Pull-ups', 'value' => 5],  // fail
            ['name' => 'Push-ups', 'value' => 15], // fail
            ['name' => 'Squats', 'value' => 30],
            ['name' => 'Plank', 'value' => 60],
        ]);

        $requirements = [
            ['name' => 'Pull-ups', 'minimum' => 10],
            ['name' => 'Push-ups', 'minimum' => 20],
            ['name' => 'Squats', 'minimum' => 25],
            ['name' => 'Plank', 'minimum' => 45],
        ];

        $this->testResult->evaluate($requirements);

        $this->assertFalse($this->testResult->isOverallPassed());
    }

    public function testEvaluateLessThanFourRequirements(): void
    {
        // Solo 2 tests → threshold = 2 (todos deben pasar)
        $this->testResult->setResults([
            ['name' => 'Pull-ups', 'value' => 12],
            ['name' => 'Push-ups', 'value' => 15], // fail
        ]);

        $requirements = [
            ['name' => 'Pull-ups', 'minimum' => 10],
            ['name' => 'Push-ups', 'minimum' => 20],
        ];

        $this->testResult->evaluate($requirements);

        $this->assertFalse($this->testResult->isOverallPassed());

        // Ambos pasan
        $this->testResult->setResults([
            ['name' => 'Pull-ups', 'value' => 12],
            ['name' => 'Push-ups', 'value' => 25],
        ]);

        $this->testResult->evaluate($requirements);
        $this->assertTrue($this->testResult->isOverallPassed());
    }
}
