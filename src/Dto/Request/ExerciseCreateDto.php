<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ExerciseCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public string $name,

        #[Assert\NotBlank(message: 'El grupo muscular primario es obligatorio')]
        #[Assert\Choice(
            choices: ['chest', 'back', 'legs', 'glutes', 'hamstrings', 'calves', 'adductors', 'shoulders', 'traps', 'biceps', 'triceps', 'forearms', 'core', 'hiit', 'full_body', 'none'],
            message: 'El grupo muscular seleccionado no es válido'
        )]
        public string $primaryMuscleGroup,

        #[Assert\NotBlank(message: 'El nivel es obligatorio')]
        #[Assert\Choice(
            choices: ['beginner', 'intermediate', 'expert', 'nolevel'],
            message: 'El nivel seleccionado no es válido'
        )]
        public string $level,

        #[Assert\Choice(
            choices: ['chest', 'back', 'legs', 'glutes', 'hamstrings', 'calves', 'adductors', 'shoulders', 'traps', 'biceps', 'triceps', 'forearms', 'core', 'hiit', 'full_body', 'none'],
            message: 'El grupo muscular seleccionado no es válido'
        )]
        public ?string $secondaryMuscleGroup = null,

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
        public ?int $difficultyRating = 1,

        public ?string $description = null,

        public ?array $disciplines = ['calisthenics']
    ) {
    }

    public function getPrimaryMuscleGroupEnum(): \App\Enum\MuscleGroup
    {
        return \App\Enum\MuscleGroup::from($this->primaryMuscleGroup);
    }

    public function getSecondaryMuscleGroupEnum(): ?\App\Enum\MuscleGroup
    {
        return null !== $this->secondaryMuscleGroup
            ? \App\Enum\MuscleGroup::from($this->secondaryMuscleGroup)
            : null;
    }

    public function getLevelEnum(): \App\Enum\Levels
    {
        return \App\Enum\Levels::from($this->level);
    }
}
