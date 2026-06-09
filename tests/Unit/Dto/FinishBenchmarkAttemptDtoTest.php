<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\FinishBenchmarkAttemptDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class FinishBenchmarkAttemptDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidCompleted(): void
    {
        $dto = new FinishBenchmarkAttemptDto(
            status: 'completed',
            totalTimeSeconds: 600,
            scoreValue: '600',
            scoreDisplay: '10:00',
            notes: 'Buen intento'
        );
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testValidDnf(): void
    {
        $dto = new FinishBenchmarkAttemptDto(status: 'dnf');
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testStatusIsRequired(): void
    {
        $dto = new FinishBenchmarkAttemptDto(status: '');
        $violations = $this->validator->validate($dto);
        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testInvalidStatus(): void
    {
        $dto = new FinishBenchmarkAttemptDto(status: 'in_progress');
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }

    public function testNegativeTotalTimeInvalid(): void
    {
        $dto = new FinishBenchmarkAttemptDto(status: 'completed', totalTimeSeconds: -1);
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }

    public function testScoreDisplayMaxLength(): void
    {
        $dto = new FinishBenchmarkAttemptDto(status: 'completed', scoreDisplay: str_repeat('a', 51));
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }
}
