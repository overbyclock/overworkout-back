<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AchievementUpdateDto
{
    public function __construct(
        #[Assert\Length(max: 255, maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres')]
        public ?string $name = null,

        #[Assert\Length(max: 50, maxMessage: 'La categoría no puede tener más de {{ limit }} caracteres')]
        public ?string $category = null,

        public ?string $description = null,
        public ?string $icon = null,
        public ?string $color = null,
        public ?int $xpReward = null,
        public ?string $requirementType = null,
        public ?int $requirementValue = null,
        public ?bool $isSecret = null,
        public ?int $programId = null,
    ) {
    }

    public function hasChanges(): bool
    {
        return null !== $this->name
            || null !== $this->category
            || null !== $this->description
            || null !== $this->icon
            || null !== $this->color
            || null !== $this->xpReward
            || null !== $this->requirementType
            || null !== $this->requirementValue
            || null !== $this->isSecret
            || null !== $this->programId;
    }
}
