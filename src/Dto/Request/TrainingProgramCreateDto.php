<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingProgramCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public string $name,

        #[Assert\NotBlank(message: 'El slug es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El slug no puede tener más de {{ limit }} caracteres'
        )]
        #[Assert\Regex(
            pattern: '/^[a-z0-9-]+$/',
            message: 'El slug solo puede contener letras minúsculas, números y guiones'
        )]
        public string $slug,

        #[Assert\NotBlank(message: 'La disciplina es obligatoria')]
        #[Assert\Choice(
            choices: ['calisthenics', 'crossfit', 'fitness', 'calisthenicsfitness'],
            message: 'La disciplina seleccionada no es válida'
        )]
        public string $discipline,

        #[Assert\NotBlank(message: 'La dificultad es obligatoria')]
        #[Assert\Choice(
            choices: ['beginner', 'intermediate', 'expert', 'nolevel'],
            message: 'La dificultad seleccionada no es válida'
        )]
        public string $difficulty,

        #[Assert\Positive(message: 'El número total de niveles debe ser positivo')]
        public int $totalLevels = 12,

        #[Assert\PositiveOrZero(message: 'La duración estimada no puede ser negativa')]
        public ?int $estimatedDurationWeeks = null,

        #[Assert\Length(
            max: 500,
            maxMessage: 'La URL de la imagen no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $imageUrl = null,

        public ?string $description = null,

        public bool $isActive = true,
    ) {
    }
}
