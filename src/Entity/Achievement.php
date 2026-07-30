<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AchievementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AchievementRepository::class)]
#[ORM\Table(name: 'achievements')]
class Achievement
{
    /** Tiers de rareza de los logros. */
    public const TIER_COMUN = 'comun';
    public const TIER_POCO_COMUN = 'poco_comun';
    public const TIER_RARO = 'raro';
    public const TIER_EPICO = 'epico';
    public const TIER_LEGENDARIO = 'legendario';

    /** Tipos de logro: de test (al superar un nivel) o de hito (primera ejecución en sesión). */
    public const TYPE_TEST = 'test';
    public const TYPE_HITO = 'hito';

    /**
     * Requisitos de logros por ítem de test (con requirementKey = nombre del ítem):
     * se desbloquean cuando el valor del ítem supera el mínimo (o el objetivo)
     * en CUALQUIER test enviado, aunque el test global no se supere.
     */
    public const REQ_TEST_ITEM_MIN = 'test_item_min';
    public const REQ_TEST_ITEM_OBJ = 'test_item_obj';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['achievement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['achievement:read'])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['achievement:read'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Groups(['achievement:read'])]
    private ?string $category = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $icon = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $color = null;

    #[ORM\Column]
    #[Groups(['achievement:read'])]
    private ?int $xpReward = 0;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $requirementType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['achievement:read'])]
    private ?int $requirementValue = null;

    /**
     * Clave del requisito (p. ej. el nombre del ítem de test para logros por ítem).
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $requirementKey = null;

    #[ORM\Column]
    #[Groups(['achievement:read'])]
    private ?bool $isSecret = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['achievement:read'])]
    private ?TrainingProgram $program = null;

    /**
     * Tier de rareza (comun … legendario). Null = logro sin tier (genérico).
     */
    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $tier = null;

    /**
     * Tipo de logro: test (al superar un nivel) o hito (primera ejecución en sesión).
     * Null = logro genérico (progress, consistency, volume, secret…).
     */
    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['achievement:read'])]
    private ?string $type = null;

    /**
     * Nivel del programa al que está asociado el logro (si aplica).
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['achievement:read'])]
    private ?TrainingLevel $level = null;

    /**
     * Hitos culturales con celebración propia (p. ej. Primera Dominada, Muscle-Up).
     */
    #[ORM\Column(options: ['default' => false])]
    #[Groups(['achievement:read'])]
    private bool $specialCelebration = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getXpReward(): ?int
    {
        return $this->xpReward;
    }

    public function setXpReward(int $xpReward): static
    {
        $this->xpReward = $xpReward;

        return $this;
    }

    public function getRequirementType(): ?string
    {
        return $this->requirementType;
    }

    public function setRequirementType(?string $requirementType): static
    {
        $this->requirementType = $requirementType;

        return $this;
    }

    public function getRequirementValue(): ?int
    {
        return $this->requirementValue;
    }

    public function setRequirementValue(?int $requirementValue): static
    {
        $this->requirementValue = $requirementValue;

        return $this;
    }

    public function getRequirementKey(): ?string
    {
        return $this->requirementKey;
    }

    public function setRequirementKey(?string $requirementKey): static
    {
        $this->requirementKey = $requirementKey;

        return $this;
    }

    public function isIsSecret(): ?bool
    {
        return $this->isSecret;
    }

    public function setIsSecret(bool $isSecret): static
    {
        $this->isSecret = $isSecret;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getProgram(): ?TrainingProgram
    {
        return $this->program;
    }

    public function setProgram(?TrainingProgram $program): static
    {
        $this->program = $program;

        return $this;
    }

    public function getTier(): ?string
    {
        return $this->tier;
    }

    public function setTier(?string $tier): static
    {
        $this->tier = $tier;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLevel(): ?TrainingLevel
    {
        return $this->level;
    }

    public function setLevel(?TrainingLevel $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function hasSpecialCelebration(): bool
    {
        return $this->specialCelebration;
    }

    public function setSpecialCelebration(bool $specialCelebration): static
    {
        $this->specialCelebration = $specialCelebration;

        return $this;
    }
}
