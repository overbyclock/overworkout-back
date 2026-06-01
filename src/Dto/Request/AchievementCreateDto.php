<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AchievementCreateDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'El código es obligatorio')]
        #[Assert\Length(max: 100, maxMessage: 'El código no puede tener más de {{ limit }} caracteres')]
        public string $code,

        #[Assert\NotBlank(message: 'El nombre es obligatorio')]
        #[Assert\Length(max: 255, maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres')]
        public string $name,

        #[Assert\Length(max: 50, maxMessage: 'La categoría no puede tener más de {{ limit }} caracteres')]
        public ?string $category = null,

        public ?string $description = null,
        public ?string $icon = null,
        public ?string $color = null,

        #[Assert\NotNull(message: 'La recompensa de XP es obligatoria')]
        public ?int $xpReward = 0,

        public ?string $requirementType = null,
        public ?int $requirementValue = null,

        public ?bool $isSecret = false,
        public ?int $programId = null,
    ) {
    }
}
