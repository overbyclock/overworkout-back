<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class LoginDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El email es obligatorio')]
        #[Assert\Email(message: 'El email no es válido')]
        public string $email,

        #[Assert\NotBlank(message: 'La contraseña es obligatoria')]
        public string $password
    ) {
    }
}
