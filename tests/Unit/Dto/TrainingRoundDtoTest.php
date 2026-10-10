<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\TrainingRoundDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TrainingRoundDtoTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testFuerzaSkillBlockTypeIsAccepted(): void
    {
        $dto = new TrainingRoundDto(round: 1, blockType: 'fuerza_skill');

        $this->assertCount(0, $this->blockTypeViolationsOf($dto));
    }

    public function testWodBlockTypeIsAccepted(): void
    {
        $dto = new TrainingRoundDto(round: 1, blockType: 'wod');

        $this->assertCount(0, $this->blockTypeViolationsOf($dto));
    }

    public function testInvalidBlockTypeIsRejected(): void
    {
        $dto = new TrainingRoundDto(round: 1, blockType: 'invalido');

        $violations = $this->blockTypeViolationsOf($dto);

        $this->assertCount(1, $violations);
        $this->assertSame('El tipo de bloque no es válido.', reset($violations)->getMessage());
    }

    /**
     * @return list<\Symfony\Component\Validator\ConstraintViolationInterface>
     */
    private function blockTypeViolationsOf(TrainingRoundDto $dto): array
    {
        return array_values(array_filter(
            iterator_to_array($this->validator->validate($dto)),
            static fn ($violation): bool => 'blockType' === $violation->getPropertyPath()
        ));
    }
}
