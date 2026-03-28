<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UserUpdateDto
{
    public function __construct(
        #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'El nick debe tener al menos {{ limit }} caracteres',
            maxMessage: 'El nick no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $nick = null,

        #[Assert\Email(message: 'El email no es válido')]
        public ?string $email = null,

        #[Assert\Length(
            max: 255,
            maxMessage: 'El avatar no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $avatar = null,

        #[Assert\Length(
            min: 6,
            minMessage: 'La contraseña debe tener al menos {{ limit }} caracteres'
        )]
        #[Assert\Regex(
            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            message: 'La contraseña debe contener al menos una mayúscula, una minúscula y un número'
        )]
        public ?string $password = null
    ) {
    }

    public function hasChanges(): bool
    {
        return null !== $this->nick
            || null !== $this->email
            || null !== $this->avatar
            || null !== $this->password;
    }
}
