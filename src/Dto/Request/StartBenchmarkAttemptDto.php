<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class StartBenchmarkAttemptDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El tipo de escala es obligatorio')]
        #[Assert\Choice(
            choices: ['rx', 'scaled', 'beginner'],
            message: 'El tipo de escala debe ser rx, scaled o beginner'
        )]
        public string $scalingUsed
    ) {
    }
}
