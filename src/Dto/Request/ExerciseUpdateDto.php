<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ExerciseUpdateDto
{
    public function __construct(
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\Choice(
            choices: ['chest', 'back', 'legs', 'glutes', 'hamstrings', 'calves', 'adductors', 'shoulders', 'traps', 'biceps', 'triceps', 'forearms', 'core', 'hiit', 'full_body', 'none'],
            message: 'El grupo muscular seleccionado no es válido'
        )]
        public ?string $primaryMuscleGroup = null,

        #[Assert\Choice(
            choices: ['chest', 'back', 'legs', 'glutes', 'hamstrings', 'calves', 'adductors', 'shoulders', 'traps', 'biceps', 'triceps', 'forearms', 'core', 'hiit', 'full_body', 'none'],
            message: 'El grupo muscular seleccionado no es válido'
        )]
        public ?string $secondaryMuscleGroup = null,

        #[Assert\Choice(
            choices: ['beginner', 'intermediate', 'expert', 'nolevel'],
            message: 'El nivel seleccionado no es válido'
        )]
        public ?string $level = null,

        #[Assert\Positive(message: 'El ID del equipamiento debe ser positivo')]
        public ?int $equipmentId = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El media no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $media = null,

        #[Assert\Range(
            min: 1,
            max: 5,
            notInRangeMessage: 'La dificultad debe estar entre {{ min }} y {{ max }}'
        )]
        public ?int $difficultyRating = null,

        public ?string $description = null,

        public ?array $disciplines = null
    ) {
    }

    public function getPrimaryMuscleGroupEnum(): ?\App\Enum\MuscleGroup
    {
        return $this->primaryMuscleGroup !== null
            ? \App\Enum\MuscleGroup::from($this->primaryMuscleGroup)
            : null;
    }

    public function getSecondaryMuscleGroupEnum(): ?\App\Enum\MuscleGroup
    {
        return $this->secondaryMuscleGroup !== null
            ? \App\Enum\MuscleGroup::from($this->secondaryMuscleGroup)
            : null;
    }

    public function getLevelEnum(): ?\App\Enum\Levels
    {
        return $this->level !== null
            ? \App\Enum\Levels::from($this->level)
            : null;
    }

    public function hasChanges(): bool
    {
        return $this->name !== null
            || $this->primaryMuscleGroup !== null
            || $this->secondaryMuscleGroup !== null
            || $this->level !== null
            || $this->equipmentId !== null
            || $this->media !== null
            || $this->difficultyRating !== null
            || $this->description !== null
            || $this->disciplines !== null;
    }
}
