<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingUpdateDto
{
    public function __construct(
        #[Assert\Choice(
            choices: ['Calistenia', 'Crossfit', 'Halters', 'Cardio', 'Stretching'],
            message: 'La disciplina seleccionada no es válida'
        )]
        public ?string $discipline = null,

        #[Assert\Choice(
            choices: ['Fuerza', 'Hipertrofia', 'Resistencia', 'Tecnica', 'Movilidad'],
            message: 'El objetivo seleccionado no es válido'
        )]
        public ?string $target = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\Valid]
        /** @var TrainingRoundDto[] */
        public ?array $rounds = null
    ) {
    }

    public function getDisciplineEnum(): ?Discipline
    {
        return null !== $this->discipline ? Discipline::from($this->discipline) : null;
    }

    public function getTargetEnum(): ?TargetWorkout
    {
        return null !== $this->target ? TargetWorkout::from($this->target) : null;
    }

    public function hasChanges(): bool
    {
        return null !== $this->discipline
            || null !== $this->target
            || null !== $this->name
            || null !== $this->rounds;
    }
}
