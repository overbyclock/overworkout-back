<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class EquipmentUpdateDto
{
    public function __construct(
        #[Assert\Length(
            max: 255,
            maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
        )]
        public ?string $name = null,

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

    public function hasChanges(): bool
    {
        return null !== $this->name
            || null !== $this->image
            || null !== $this->description
            || null !== $this->category
            || null !== $this->icon
            || null !== $this->weight;
    }
}
