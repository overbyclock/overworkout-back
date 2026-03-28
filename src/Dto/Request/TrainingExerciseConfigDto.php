<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TrainingExerciseConfigDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El ID del ejercicio es obligatorio')]
        #[Assert\Positive(message: 'El ID del ejercicio debe ser positivo')]
        public int $exerciseId,

        #[Assert\PositiveOrZero(message: 'Las repeticiones deben ser positivas o cero')]
        public ?int $reps = null,

        #[Assert\Positive(message: 'Las series deben ser positivas')]
        public ?int $sets = 1,

        #[Assert\PositiveOrZero(message: 'El descanso entre series debe ser positivo o cero')]
        public ?int $restBetweenSets = 30,

        #[Assert\PositiveOrZero(message: 'El descanso entre ejercicios debe ser positivo o cero')]
        public ?int $restBetweenExercises = 15,

        #[Assert\PositiveOrZero(message: 'El tiempo máximo debe ser positivo o cero')]
        public ?int $maxTimeForReps = null,

        #[Assert\PositiveOrZero(message: 'El peso debe ser positivo o cero')]
        public ?float $weight = null,

        // ID opcional para actualizaciones
        public ?int $id = null
    ) {
    }
}
