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
            choices: ['calisthenics', 'crossfit', 'fitness', 'calisthenicsfitness'],
            message: 'La disciplina seleccionada no es válida'
        )]
        public string $discipline,

        #[Assert\NotBlank(message: 'El objetivo es obligatorio')]
        #[Assert\Choice(
            choices: ['strength', 'fatburning', 'repbuilding', 'warmup'],
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
        public array $rounds = [],

        #[Assert\Positive(message: 'El ID del nivel debe ser positivo')]
        public ?int $trainingLevelId = null,

        #[Assert\Positive(message: 'El número de semana debe ser positivo')]
        public ?int $weekNumber = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'La clave del día no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $dayKey = null,

        #[Assert\Length(
            max: 30,
            maxMessage: 'El tipo de sesión no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $sessionType = null,
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
