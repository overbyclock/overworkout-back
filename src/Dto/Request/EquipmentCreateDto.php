<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class EquipmentCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public string $name,

        #[Assert\Length(
            max: 255,
            maxMessage: 'La imagen no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $image = null,

        public ?string $description = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'La categoría no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $category = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'El icono no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $icon = null,

        #[Assert\PositiveOrZero(message: 'El peso debe ser positivo o cero')]
        public ?float $weight = null
    ) {
    }
}
