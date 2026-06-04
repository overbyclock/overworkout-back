<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BenchmarkUpdateDto
{
    public function __construct(
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

        #[Assert\Choice(
            choices: ['girl', 'hero', 'benchmark'],
            message: 'El tipo debe ser girl, hero o benchmark'
        )]
        public ?string $type = null,

        #[Assert\Length(
            max: 65535,
            maxMessage: 'La descripción es demasiado larga'
        )]
        public ?string $description = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El peso RX hombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $rxWeightMale = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El peso RX mujer no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $rxWeightFemale = null,

        /** @var string[]|null */
        public ?array $movements = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo élite hombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $eliteTimeMale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo élite mujer no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $eliteTimeFemale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo avanzado hombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $advancedTimeMale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo avanzado mujer no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $advancedTimeFemale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo intermedio hombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $intermediateTimeMale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo intermedio mujer no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $intermediateTimeFemale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo principiante hombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $beginnerTimeMale = null,

        #[Assert\Length(
            max: 20,
            maxMessage: 'El tiempo principiante mujer no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $beginnerTimeFemale = null,

        #[Assert\Length(
            max: 65535,
            maxMessage: 'Las opciones de escalado son demasiado largas'
        )]
        public ?string $scalingOptions = null,

        #[Assert\Url(message: 'La URL del vídeo no es válida')]
        #[Assert\Length(
            max: 500,
            maxMessage: 'La URL no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $videoUrl = null,

        /**
         * @var BenchmarkExerciseDto[]|null
         */
        #[Assert\All([
            new Assert\Type(BenchmarkExerciseDto::class),
        ])]
        #[Assert\Valid]
        public ?array $exercises = null,
    ) {
    }
}
