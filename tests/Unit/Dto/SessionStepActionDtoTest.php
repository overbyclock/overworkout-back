<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\SessionStepActionDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SessionStepActionDtoTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidCompleteActionPasses(): void
    {
        $dto = new SessionStepActionDto(action: 'complete');

        $this->assertCount(0, $this->validator->validate($dto));
        $this->assertSame('step', $dto->scope);
    }

    public function testValidSkipWithExerciseScopePasses(): void
    {
        $dto = new SessionStepActionDto(action: 'skip', scope: 'exercise');

        $this->assertCount(0, $this->validator->validate($dto));
    }

    public function testInvalidActionFails(): void
    {
        $dto = new SessionStepActionDto(action: 'pause');

        $this->assertGreaterThan(0, \count($this->validator->validate($dto)));
    }

    public function testInvalidScopeFails(): void
    {
        $dto = new SessionStepActionDto(action: 'skip', scope: 'block');

        $this->assertGreaterThan(0, \count($this->validator->validate($dto)));
    }
}
