<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class FinishBenchmarkAttemptDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El estado final es obligatorio')]
        #[Assert\Choice(
            choices: ['completed', 'dnf'],
            message: 'El estado debe ser completed o dnf'
        )]
        public string $status,

        #[Assert\PositiveOrZero(message: 'El tiempo total debe ser positivo o cero')]
        public ?int $totalTimeSeconds = null,

        #[Assert\Length(max: 50, maxMessage: 'El valor del score no puede tener más de {{ limit }} caracteres')]
        public ?string $scoreValue = null,

        #[Assert\Length(max: 50, maxMessage: 'El display del score no puede tener más de {{ limit }} caracteres')]
        public ?string $scoreDisplay = null,

        public ?string $notes = null
    ) {
    }
}
