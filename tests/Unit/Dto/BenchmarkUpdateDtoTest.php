<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\BenchmarkUpdateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class BenchmarkUpdateDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testEmptyDtoIsValid(): void
    {
        $dto = new BenchmarkUpdateDto();

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testValidDtoWithAllFields(): void
    {
        $dto = new BenchmarkUpdateDto(
            name: 'Updated Fran',
            type: 'girl',
            description: 'Updated description',
            rxWeightMale: '50kg',
            rxWeightFemale: '35kg',
            movements: ['thrusters', 'pull-ups'],
            eliteTimeMale: '2:00',
            eliteTimeFemale: '2:30',
            advancedTimeMale: '3:00',
            advancedTimeFemale: '3:30',
            intermediateTimeMale: '5:00',
            intermediateTimeFemale: '5:30',
            beginnerTimeMale: '8:00',
            beginnerTimeFemale: '9:00',
            scalingOptions: 'Scale to lighter weight',
            videoUrl: 'https://example.com/video'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testInvalidType(): void
    {
        $dto = new BenchmarkUpdateDto(type: 'invalid');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El tipo debe ser girl, hero o benchmark', $violations[0]->getMessage());
    }

    public function testNameMaxLength(): void
    {
        $dto = new BenchmarkUpdateDto(name: str_repeat('a', 256));

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('255', $violations[0]->getMessage());
    }

    public function testVideoUrlMustBeValid(): void
    {
        $dto = new BenchmarkUpdateDto(videoUrl: 'not-a-url');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('La URL del vídeo no es válida', $violations[0]->getMessage());
    }
}
