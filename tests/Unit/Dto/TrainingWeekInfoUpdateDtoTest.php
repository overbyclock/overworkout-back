<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingWeekInfoUpdateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingWeekInfoUpdateDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidDtoWithAllFields(): void
    {
        $dto = new TrainingWeekInfoUpdateDto(
            name: 'Updated',
            weekNumber: 2,
            focus: 'Updated focus',
            note: 'Updated note',
            intensity: 'alta',
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testEmptyDtoIsValid(): void
    {
        $dto = new TrainingWeekInfoUpdateDto();

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testHasChangesReturnsFalseWhenEmpty(): void
    {
        $dto = new TrainingWeekInfoUpdateDto();

        $this->assertFalse($dto->hasChanges());
    }

    public function testHasChangesReturnsTrueWithAnyField(): void
    {
        $dto = new TrainingWeekInfoUpdateDto(name: 'Updated');

        $this->assertTrue($dto->hasChanges());
    }

    public function testWeekNumberMustBePositive(): void
    {
        $dto = new TrainingWeekInfoUpdateDto(weekNumber: -1);

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }
}
