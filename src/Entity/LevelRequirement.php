<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LevelRequirementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LevelRequirementRepository::class)]
#[ORM\Table(name: 'level_requirements')]
class LevelRequirement
{
    final public const GROUP_READ = 'requirement:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TrainingLevel::class, inversedBy: 'requirements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingLevel $level = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $minValue = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxValue = null;

    #[ORM\Column(nullable: true)]
    private ?int $targetValue = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(nullable: true)]
    private ?int $orderIndex = null;

    #[ORM\Column]
    private ?bool $isRequired = true;

    public function getId(): ?int
    {
        return $this->id;
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

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getMinValue(): ?int
    {
        return $this->minValue;
    }

    public function setMinValue(?int $minValue): static
    {
        $this->minValue = $minValue;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getMaxValue(): ?int
    {
        return $this->maxValue;
    }

    public function setMaxValue(?int $maxValue): static
    {
        $this->maxValue = $maxValue;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getTargetValue(): ?int
    {
        return $this->targetValue;
    }

    public function setTargetValue(?int $targetValue): static
    {
        $this->targetValue = $targetValue;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getOrderIndex(): ?int
    {
        return $this->orderIndex;
    }

    public function setOrderIndex(?int $orderIndex): static
    {
        $this->orderIndex = $orderIndex;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function isRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): static
    {
        $this->isRequired = $isRequired;

        return $this;
    }
}
