<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingWeekInfoCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TrainingWeekInfoCreateDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidDto(): void
    {
        $dto = new TrainingWeekInfoCreateDto(
            name: 'Semana 1: Fundamentos',
            levelId: 1,
            weekNumber: 1,
            focus: 'Fuerza básica',
            note: 'Nota de la semana',
            intensity: 'media',
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new TrainingWeekInfoCreateDto(
            name: '',
            levelId: 1,
            weekNumber: 1,
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
    }

    public function testLevelIdMustBePositive(): void
    {
        $dto = new TrainingWeekInfoCreateDto(
            name: 'Semana 1',
            levelId: -1,
            weekNumber: 1,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }

    public function testWeekNumberMustBePositive(): void
    {
        $dto = new TrainingWeekInfoCreateDto(
            name: 'Semana 1',
            levelId: 1,
            weekNumber: 0,
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
    }
}
