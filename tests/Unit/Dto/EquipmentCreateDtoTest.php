<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\EquipmentCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class EquipmentCreateDtoTest extends TestCase
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
        $dto = new EquipmentCreateDto(
            name: 'Barra Dominadas',
            image: 'https://example.com/bar.png',
            description: 'Barra para dominadas',
            category: 'calistenia',
            icon: 'bar-icon',
            weight: 10.5
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new EquipmentCreateDto(name: '');

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('El nombre es obligatorio', $violations[0]->getMessage());
    }

    public function testNameMaxLength(): void
    {
        $dto = new EquipmentCreateDto(name: str_repeat('a', 256));

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('255', $violations[0]->getMessage());
    }

    public function testOptionalFieldsCanBeNull(): void
    {
        $dto = new EquipmentCreateDto(name: 'Test Equipment');

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testWeightMustBePositiveOrZero(): void
    {
        $dto = new EquipmentCreateDto(
            name: 'Test',
            weight: -5
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('positivo o cero', $violations[0]->getMessage());
    }
}
