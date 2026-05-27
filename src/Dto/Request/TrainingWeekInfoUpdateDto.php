<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingWeekInfoUpdateDto
{
    public function __construct(
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\Positive(message: 'El número de semana debe ser positivo')]
        public ?int $weekNumber = null,

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

    public function hasChanges(): bool
    {
        return null !== $this->name
            || null !== $this->weekNumber
            || null !== $this->focus
            || null !== $this->note
            || null !== $this->intensity;
    }
}
