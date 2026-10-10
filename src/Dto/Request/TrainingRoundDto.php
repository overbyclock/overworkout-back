<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\TrainingRound;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingRoundDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El número de round es obligatorio')]
        #[Assert\Positive(message: 'El número de round debe ser positivo')]
        public int $round,

        #[Assert\PositiveOrZero(message: 'El descanso entre rounds debe ser positivo o cero')]
        public ?int $restBetweenRounds = 60,

        #[Assert\NotBlank(message: 'Los ejercicios son obligatorios')]
        #[Assert\Valid]
        /** @var TrainingExerciseConfigDto[] */
        public array $exercises = [],

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
}
