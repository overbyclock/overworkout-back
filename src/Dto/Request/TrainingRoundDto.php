<?php

declare(strict_types=1);

namespace App\Dto\Request;

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

        // ID opcional para actualizaciones
        public ?int $id = null
    ) {
    }
}
