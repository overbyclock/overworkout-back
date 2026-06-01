<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingSkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TrainingSkillRepository::class)]
#[ORM\Table(name: 'training_skills')]
class TrainingSkill
{
    final public const GROUP_READ = 'training_skill:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingProgram $program = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingLevel $level = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups([self::GROUP_READ])]
    private ?string $family = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $icon = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $unlockAtLevel = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $masteryAtLevel = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $videoTutorialUrl = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $difficultyScore = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?bool $isKeySkill = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLevel(): ?TrainingLevel
    {
        return $this->level;
    }

    public function setLevel(?TrainingLevel $level): static
    {
        $this->level = $level;

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

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): static
    {
        $this->family = $family;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUnlockAtLevel(): ?int
    {
        return $this->unlockAtLevel;
    }

    public function setUnlockAtLevel(int $unlockAtLevel): static
    {
        $this->unlockAtLevel = $unlockAtLevel;

        return $this;
    }

    public function getMasteryAtLevel(): ?int
    {
        return $this->masteryAtLevel;
    }

    public function setMasteryAtLevel(?int $masteryAtLevel): static
    {
        $this->masteryAtLevel = $masteryAtLevel;

        return $this;
    }

    public function getVideoTutorialUrl(): ?string
    {
        return $this->videoTutorialUrl;
    }

    public function setVideoTutorialUrl(?string $videoTutorialUrl): static
    {
        $this->videoTutorialUrl = $videoTutorialUrl;

        return $this;
    }

    public function getDifficultyScore(): ?int
    {
        return $this->difficultyScore;
    }

    public function setDifficultyScore(?int $difficultyScore): static
    {
        $this->difficultyScore = $difficultyScore;

        return $this;
    }

    public function isIsKeySkill(): ?bool
    {
        return $this->isKeySkill;
    }

    public function setIsKeySkill(bool $isKeySkill): static
    {
        $this->isKeySkill = $isKeySkill;

        return $this;
    }

    #[Groups([self::GROUP_READ])]
    public function getProgramId(): ?int
    {
        return $this->program?->getId();
    }

    #[Groups([self::GROUP_READ])]
    public function getProgramName(): ?string
    {
        return $this->program?->getName();
    }

    #[Groups([self::GROUP_READ])]
    public function getLevelId(): ?int
    {
        return $this->level?->getId();
    }

    #[Groups([self::GROUP_READ])]
    public function getLevelName(): ?string
    {
        return $this->level?->getName();
    }
}
