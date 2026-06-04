<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BenchmarkExerciseDto
{
    public function __construct(
        #[Assert\Positive(message: 'El ejercicio es obligatorio')]
        public int $exerciseId,

        #[Assert\NotNull(message: 'La posición es obligatoria')]
        #[Assert\Positive(message: 'La posición debe ser mayor que 0')]
        public int $position,

        #[Assert\PositiveOrZero(message: 'Las repeticiones no pueden ser negativas')]
        public ?int $reps = null,

        #[Assert\PositiveOrZero(message: 'La distancia no puede ser negativa')]
        public ?int $distanceMeters = null,

        #[Assert\PositiveOrZero(message: 'La duración no puede ser negativa')]
        public ?int $durationSeconds = null,

        #[Assert\PositiveOrZero(message: 'Las calorías no pueden ser negativas')]
        public ?int $calories = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'El peso RX hombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $rxWeightMale = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'El peso RX mujer no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $rxWeightFemale = null,

        #[Assert\PositiveOrZero(message: 'El descanso no puede ser negativo')]
        public ?int $restSeconds = null,
    ) {
    }
}
