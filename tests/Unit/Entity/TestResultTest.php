<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\TestResult;
use App\Entity\TrainingProgram;
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

    public function testEvaluateLegacyKeepsGateDefaults(): void
    {
        $this->testResult->setResults([
            ['name' => 'Pull-ups', 'value' => 5],
            ['name' => 'Push-ups', 'value' => 15],
            ['name' => 'Squats', 'value' => 30],
            ['name' => 'Plank', 'value' => 60],
        ]);

        $this->testResult->evaluate([
            ['name' => 'Pull-ups', 'minimum' => 10],
            ['name' => 'Push-ups', 'minimum' => 20],
            ['name' => 'Squats', 'minimum' => 25],
            ['name' => 'Plank', 'minimum' => 45],
        ]);

        // En modo legacy los campos de puertas no aplican
        $this->assertTrue($this->testResult->isStrengthPassed());
        $this->assertTrue($this->testResult->isMobilityPassed());
        $this->assertNull($this->testResult->getFailedGates());
    }

    public function testEvaluateStrictGatesAllPassed(): void
    {
        $this->testResult->setResults([
            ['name' => 'Dominada estricta', 'value' => 1],
            ['name' => 'Pike push-up', 'value' => 6],
            ['name' => 'Puerta G1 — Flexión de hombro', 'value' => 1],
            ['name' => 'Puerta G2 — Extensión de muñeca', 'value' => 1],
        ]);

        $this->testResult->evaluate([
            ['name' => 'Dominada estricta', 'minimum' => 1],
            ['name' => 'Pike push-up', 'minimum' => 6],
            ['name' => 'Puerta G1 — Flexión de hombro', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G1'],
            ['name' => 'Puerta G2 — Extensión de muñeca', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G2'],
        ], TrainingProgram::TEST_EVAL_STRICT_GATES);

        $this->assertTrue($this->testResult->isOverallPassed());
        $this->assertTrue($this->testResult->isStrengthPassed());
        $this->assertTrue($this->testResult->isMobilityPassed());
        $this->assertNull($this->testResult->getFailedGates());

        $results = $this->testResult->getResults();
        $this->assertSame('puerta_movilidad', $results[2]['type']);
        $this->assertSame('G1', $results[2]['gateCode']);
    }

    public function testEvaluateStrictGatesStrengthFailed(): void
    {
        // En strict_gates NO vale la regla 3 de 4: un solo fallo de fuerza suspende
        $this->testResult->setResults([
            ['name' => 'Dominada estricta', 'value' => 1],
            ['name' => 'Pike push-up', 'value' => 3], // fail
            ['name' => 'Puerta G1', 'value' => 1],
        ]);

        $this->testResult->evaluate([
            ['name' => 'Dominada estricta', 'minimum' => 1],
            ['name' => 'Pike push-up', 'minimum' => 6],
            ['name' => 'Puerta G1', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G1'],
        ], TrainingProgram::TEST_EVAL_STRICT_GATES);

        $this->assertFalse($this->testResult->isOverallPassed());
        $this->assertFalse($this->testResult->isStrengthPassed());
        $this->assertTrue($this->testResult->isMobilityPassed());
        $this->assertNull($this->testResult->getFailedGates());
    }

    public function testEvaluateStrictGatesMobilityBlocked(): void
    {
        // Fuerza superada, puerta fallada → bloqueo por movilidad
        $this->testResult->setResults([
            ['name' => 'Dominada estricta', 'value' => 2],
            ['name' => 'Pike push-up', 'value' => 8],
            ['name' => 'Puerta G1', 'value' => 1],
            ['name' => 'Puerta G2', 'value' => 0], // fail
        ]);

        $this->testResult->evaluate([
            ['name' => 'Dominada estricta', 'minimum' => 1],
            ['name' => 'Pike push-up', 'minimum' => 6],
            ['name' => 'Puerta G1', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G1'],
            ['name' => 'Puerta G2', 'minimum' => 1, 'type' => 'puerta_movilidad', 'gateCode' => 'G2'],
        ], TrainingProgram::TEST_EVAL_STRICT_GATES);

        $this->assertFalse($this->testResult->isOverallPassed());
        $this->assertTrue($this->testResult->isStrengthPassed());
        $this->assertFalse($this->testResult->isMobilityPassed());
        $this->assertSame(['G2'], $this->testResult->getFailedGates());
    }

    public function testEvaluateStrictGatesMissingResultCountsAsFailure(): void
    {
        // Un requisito sin resultado enviado cuenta como fallo
        $this->testResult->setResults([
            ['name' => 'Dominada estricta', 'value' => 1],
        ]);

        $this->testResult->evaluate([
            ['name' => 'Dominada estricta', 'minimum' => 1],
            ['name' => 'Pike push-up', 'minimum' => 6],
        ], TrainingProgram::TEST_EVAL_STRICT_GATES);

        $this->assertFalse($this->testResult->isOverallPassed());
        $this->assertFalse($this->testResult->isStrengthPassed());

        $results = $this->testResult->getResults();
        $this->assertNull($results[1]['value']);
        $this->assertFalse($results[1]['passed']);
    }

    public function testEvaluateGatesOnlyRetest(): void
    {
        // Re-test tras bloqueo: solo se evalúan puertas, la fuerza se da por superada
        $this->testResult->setResults([
            ['name' => 'Re-test G1', 'value' => 1],
            ['name' => 'Re-test G2', 'value' => 1],
        ]);

        $this->testResult->evaluate([
            ['name' => 'Re-test G1', 'minimum' => 1, 'type' => 're_test', 'gateCode' => 'G1'],
            ['name' => 'Re-test G2', 'minimum' => 1, 'type' => 're_test', 'gateCode' => 'G2'],
        ], 'gates_only');

        $this->assertTrue($this->testResult->isOverallPassed());
        $this->assertTrue($this->testResult->isStrengthPassed());
        $this->assertTrue($this->testResult->isMobilityPassed());

        // Y si una puerta sigue fallando, sigue bloqueado
        $this->testResult->setResults([
            ['name' => 'Re-test G1', 'value' => 1],
            ['name' => 'Re-test G2', 'value' => 0],
        ]);

        $this->testResult->evaluate([
            ['name' => 'Re-test G1', 'minimum' => 1, 'type' => 're_test', 'gateCode' => 'G1'],
            ['name' => 'Re-test G2', 'minimum' => 1, 'type' => 're_test', 'gateCode' => 'G2'],
        ], 'gates_only');

        $this->assertFalse($this->testResult->isOverallPassed());
        $this->assertTrue($this->testResult->isStrengthPassed());
        $this->assertFalse($this->testResult->isMobilityPassed());
        $this->assertSame(['G2'], $this->testResult->getFailedGates());
    }
}
