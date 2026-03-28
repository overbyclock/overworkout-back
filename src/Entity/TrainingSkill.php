<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingSkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingSkillRepository::class)]
class TrainingSkill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingProgram $program = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingLevel $level = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $family = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?int $unlockAtLevel = null;

    #[ORM\Column(nullable: true)]
    private ?int $masteryAtLevel = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $videoTutorialUrl = null;

    #[ORM\Column(nullable: true)]
    private ?int $difficultyScore = null;

    #[ORM\Column]
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
}
