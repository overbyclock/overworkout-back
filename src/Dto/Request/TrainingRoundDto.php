<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\TrainingRound;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Un round puede ser un WOD embebido (benchmarkId) o llevar ejercicios propios;
 * basta con uno de los dos (o ambos).
 */
#[Assert\Callback('validateContenido')]
readonly class TrainingRoundDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El número de round es obligatorio')]
        #[Assert\Positive(message: 'El número de round debe ser positivo')]
        public int $round,

        #[Assert\PositiveOrZero(message: 'El descanso entre rounds debe ser positivo o cero')]
        public ?int $restBetweenRounds = 60,

        #[Assert\Valid]
        /** @var TrainingExerciseConfigDto[] */
        public array $exercises = [],

        #[Assert\Positive(message: 'El identificador del benchmark debe ser positivo')]
        public ?int $benchmarkId = null,

        #[Assert\Choice(
            choices: [TrainingRound::BLOCK_PREPARACION, TrainingRound::BLOCK_PRINCIPAL, TrainingRound::BLOCK_POSTURAL, TrainingRound::BLOCK_CADENA_POSTERIOR, TrainingRound::BLOCK_DESCARGA, TrainingRound::BLOCK_FUERZA_SKILL, TrainingRound::BLOCK_WOD],
            message: 'El tipo de bloque no es válido.',
        )]
        public ?string $blockType = null,

        // ID opcional para actualizaciones
        public ?int $id = null,

        // Nombre y descripción opcionales del bloque
        #[Assert\Length(max: 100, maxMessage: 'El nombre del bloque no puede superar {{ limit }} caracteres')]
        public ?string $name = null,

        public ?string $description = null
    ) {
    }

    public function validateContenido(ExecutionContextInterface $context): void
    {
        if (null === $this->benchmarkId && [] === $this->exercises) {
            $context->buildViolation('El round debe tener un benchmark (benchmarkId) o al menos un ejercicio.')
                ->atPath('exercises')
                ->addViolation();
        }
    }
}
