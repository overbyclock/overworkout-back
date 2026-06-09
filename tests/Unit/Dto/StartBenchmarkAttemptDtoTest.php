<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\StartBenchmarkAttemptDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class StartBenchmarkAttemptDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidRx(): void
    {
        $dto = new StartBenchmarkAttemptDto(scalingUsed: 'rx');
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testValidScaled(): void
    {
        $dto = new StartBenchmarkAttemptDto(scalingUsed: 'scaled');
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testValidBeginner(): void
    {
        $dto = new StartBenchmarkAttemptDto(scalingUsed: 'beginner');
        $violations = $this->validator->validate($dto);
        $this->assertCount(0, $violations);
    }

    public function testScalingUsedIsRequired(): void
    {
        $dto = new StartBenchmarkAttemptDto(scalingUsed: '');
        $violations = $this->validator->validate($dto);
        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testInvalidScaling(): void
    {
        $dto = new StartBenchmarkAttemptDto(scalingUsed: 'invalid');
        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }
}
