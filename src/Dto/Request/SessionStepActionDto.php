<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class SessionStepActionDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'La acción es obligatoria')]
        #[Assert\Choice(
            choices: ['complete', 'skip'],
            message: 'La acción debe ser complete o skip'
        )]
        public string $action,

        #[Assert\Choice(
            choices: ['step', 'exercise'],
            message: 'El scope debe ser step o exercise'
        )]
        public string $scope = 'step',
    ) {
    }
}
