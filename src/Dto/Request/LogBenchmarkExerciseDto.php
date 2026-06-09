<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class LogBenchmarkExerciseDto
{
    public function __construct(
        #[Assert\NotNull(message: 'El ID del ejercicio del benchmark es obligatorio')]
        #[Assert\Positive(message: 'El ID del ejercicio debe ser positivo')]
        public int $benchmarkExerciseId,

        #[Assert\PositiveOrZero(message: 'El número de ronda debe ser positivo o cero')]
        public ?int $roundNumber = null,

        #[Assert\PositiveOrZero(message: 'Las repeticiones deben ser positivas o cero')]
        public ?int $repsCompleted = null,

        #[Assert\Length(max: 50, maxMessage: 'El peso no puede tener más de {{ limit }} caracteres')]
        public ?string $weightUsed = null,

        #[Assert\PositiveOrZero(message: 'La distancia debe ser positiva o cero')]
        public ?int $distanceCompleted = null,

        #[Assert\PositiveOrZero(message: 'Las calorías deben ser positivas o cero')]
        public ?int $caloriesCompleted = null,

        #[Assert\PositiveOrZero(message: 'La duración debe ser positiva o cero')]
        public ?int $durationSeconds = null,

        #[Assert\PositiveOrZero(message: 'El descanso debe ser positivo o cero')]
        public ?int $restTakenSeconds = null,

        public ?string $notes = null
    ) {
    }
}
