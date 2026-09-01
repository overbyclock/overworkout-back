<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingLevelCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public string $name,

        #[Assert\NotNull(message: 'El ID del programa es obligatorio')]
        #[Assert\Positive(message: 'El ID del programa debe ser positivo')]
        public int $programId,

        #[Assert\NotNull(message: 'El número de nivel es obligatorio')]
        #[Assert\Positive(message: 'El número de nivel debe ser positivo')]
        public int $levelNumber,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El título no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $title = null,

        public ?string $description = null,

        public ?string $objective = null,

        #[Assert\Positive(message: 'La duración estimada debe ser positiva')]
        public ?int $estimatedDurationWeeks = 12,

        #[Assert\Range(
            min: 1,
            max: 10,
            notInRangeMessage: 'La dificultad debe estar entre {{ min }} y {{ max }}'
        )]
        public ?int $difficultyRating = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El color no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $color = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'El icono no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $icon = null,

        public ?string $requirementsSummary = null,

        public ?array $tips = null,

        public ?array $testRequirements = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'El skill focus no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $skillFocus = null,

        #[Assert\Length(
            max: 10,
            maxMessage: 'La versión del programa no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $programVersion = 'v1',

        public ?bool $isInfinite = null,

        public bool $isLockedByDefault = true,
    ) {
    }
}
