<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingWeekInfoCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public string $name,

        #[Assert\NotNull(message: 'El ID del nivel es obligatorio')]
        #[Assert\Positive(message: 'El ID del nivel debe ser positivo')]
        public int $levelId,

        #[Assert\NotNull(message: 'El número de semana es obligatorio')]
        #[Assert\Positive(message: 'El número de semana debe ser positivo')]
        public int $weekNumber,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El foco no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $focus = null,

        public ?string $note = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'La intensidad no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $intensity = null,
    ) {
    }
}
