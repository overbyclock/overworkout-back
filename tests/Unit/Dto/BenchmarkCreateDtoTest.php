<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\BenchmarkCreateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class BenchmarkCreateDtoTest extends TestCase
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
        $dto = new BenchmarkCreateDto(
            name: 'Fran',
            type: 'girl',
            rxWeightMale: 'Thrusters 43kg',
            eliteTimeMale: '2:30',
            beginnerTimeFemale: '10:00'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNameIsRequired(): void
    {
        $dto = new BenchmarkCreateDto(name: '', type: 'girl');

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('El nombre es obligatorio', $violations[0]->getMessage());
    }

    public function testTypeIsRequired(): void
    {
        $dto = new BenchmarkCreateDto(name: 'Fran', type: '');

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('El tipo es obligatorio', $violations[0]->getMessage());
    }

    public function testInvalidType(): void
    {
        $dto = new BenchmarkCreateDto(name: 'Fran', type: 'invalid');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El tipo debe ser girl, hero o benchmark', $violations[0]->getMessage());
    }

    public function testNameMaxLength(): void
    {
        $dto = new BenchmarkCreateDto(
            name: str_repeat('a', 256),
            type: 'benchmark'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('255', $violations[0]->getMessage());
    }

    public function testOptionalFieldsCanBeNull(): void
    {
        $dto = new BenchmarkCreateDto(name: 'Test', type: 'benchmark');

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testVideoUrlMustBeValid(): void
    {
        $dto = new BenchmarkCreateDto(
            name: 'Test',
            type: 'benchmark',
            videoUrl: 'not-a-url'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('La URL del vídeo no es válida', $violations[0]->getMessage());
    }
}
