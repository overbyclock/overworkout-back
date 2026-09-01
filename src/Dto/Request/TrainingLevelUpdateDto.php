<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingLevelUpdateDto
{
    public function __construct(
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\Positive(message: 'El número de nivel debe ser positivo')]
        public ?int $levelNumber = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El título no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $title = null,

        public ?string $description = null,

        public ?string $objective = null,

        #[Assert\Positive(message: 'La duración estimada debe ser positiva')]
        public ?int $estimatedDurationWeeks = null,

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
        public ?string $programVersion = null,

        public ?bool $isInfinite = null,

        public ?bool $isLockedByDefault = null,
    ) {
    }

    public function hasChanges(): bool
    {
        return null !== $this->name
            || null !== $this->levelNumber
            || null !== $this->title
            || null !== $this->description
            || null !== $this->objective
            || null !== $this->estimatedDurationWeeks
            || null !== $this->difficultyRating
            || null !== $this->color
            || null !== $this->icon
            || null !== $this->requirementsSummary
            || null !== $this->tips
            || null !== $this->testRequirements
            || null !== $this->skillFocus
            || null !== $this->programVersion
            || null !== $this->isInfinite
            || null !== $this->isLockedByDefault;
    }
}
