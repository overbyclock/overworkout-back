<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingProgramUpdateDto
{
    public function __construct(
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El slug no puede tener más de {{ limit }} caracteres'
        )]
        #[Assert\Regex(
            pattern: '/^[a-z0-9-]+$/',
            message: 'El slug solo puede contener letras minúsculas, números y guiones'
        )]
        public ?string $slug = null,

        #[Assert\Choice(
            choices: ['calisthenics', 'crossfit', 'fitness', 'calisthenicsfitness'],
            message: 'La disciplina seleccionada no es válida'
        )]
        public ?string $discipline = null,

        #[Assert\Choice(
            choices: ['beginner', 'intermediate', 'expert', 'nolevel'],
            message: 'La dificultad seleccionada no es válida'
        )]
        public ?string $difficulty = null,

        #[Assert\Positive(message: 'El número total de niveles debe ser positivo')]
        public ?int $totalLevels = null,

        #[Assert\PositiveOrZero(message: 'La duración estimada no puede ser negativa')]
        public ?int $estimatedDurationWeeks = null,

        #[Assert\Length(
            max: 500,
            maxMessage: 'La URL de la imagen no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $imageUrl = null,

        public ?string $description = null,

        public ?bool $isActive = null,
    ) {
    }

    public function hasChanges(): bool
    {
        return null !== $this->name
            || null !== $this->slug
            || null !== $this->discipline
            || null !== $this->difficulty
            || null !== $this->totalLevels
            || null !== $this->estimatedDurationWeeks
            || null !== $this->imageUrl
            || null !== $this->description
            || null !== $this->isActive;
    }
}
