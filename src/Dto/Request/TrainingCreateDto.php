<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'La disciplina es obligatoria')]
        #[Assert\Choice(
            choices: ['Calistenia', 'Crossfit', 'Halters', 'Cardio', 'Stretching'],
            message: 'La disciplina seleccionada no es válida'
        )]
        public string $discipline,

        #[Assert\NotBlank(message: 'El objetivo es obligatorio')]
        #[Assert\Choice(
            choices: ['Fuerza', 'Hipertrofia', 'Resistencia', 'Tecnica', 'Movilidad'],
            message: 'El objetivo seleccionado no es válido'
        )]
        public string $target,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\NotBlank(message: 'Los rounds son obligatorios')]
        #[Assert\Count(
            min: 1,
            minMessage: 'Debe haber al menos un round'
        )]
        #[Assert\Valid]
        /** @var TrainingRoundDto[] */
        public array $rounds = []
    ) {
    }

    public function getDisciplineEnum(): Discipline
    {
        return Discipline::from($this->discipline);
    }

    public function getTargetEnum(): TargetWorkout
    {
        return TargetWorkout::from($this->target);
    }
}
