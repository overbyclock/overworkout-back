<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingSkillCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public string $name,

        #[Assert\NotBlank(message: 'La familia es obligatoria')]
        #[Assert\Length(
            max: 50,
            maxMessage: 'La familia no puede tener más de {{ limit }} caracteres'
        )]
        public string $family,

        #[Assert\NotNull(message: 'El programa es obligatorio')]
        #[Assert\Positive(message: 'El ID del programa debe ser positivo')]
        public ?int $programId = null,

        #[Assert\NotNull(message: 'El nivel es obligatorio')]
        #[Assert\Positive(message: 'El ID del nivel debe ser positivo')]
        public ?int $levelId = null,

        #[Assert\NotNull(message: 'El nivel de desbloqueo es obligatorio')]
        #[Assert\PositiveOrZero(message: 'El nivel de desbloqueo debe ser positivo o cero')]
        public ?int $unlockAtLevel = null,

        public ?string $description = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'El icono no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $icon = null,

        #[Assert\PositiveOrZero(message: 'El nivel de maestría debe ser positivo o cero')]
        public ?int $masteryAtLevel = null,

        #[Assert\Length(
            max: 500,
            maxMessage: 'La URL del video no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $videoTutorialUrl = null,

        #[Assert\PositiveOrZero(message: 'La puntuación de dificultad debe ser positiva o cero')]
        public ?int $difficultyScore = null,

        public ?bool $isKeySkill = false
    ) {
    }
}
