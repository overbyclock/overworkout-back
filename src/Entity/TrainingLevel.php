<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TrainingLevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingLevelRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ]
)]
class TrainingLevel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'levels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingProgram $program = null;

    #[ORM\Column]
    private ?int $levelNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objective = null;

    #[ORM\Column(nullable: true)]
    private ?int $estimatedDurationWeeks = 12;

    #[ORM\Column(nullable: true)]
    private ?int $difficultyRating = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $requirementsSummary = null;

    #[ORM\Column]
    private ?bool $isLockedByDefault = true;

    #[ORM\OneToMany(mappedBy: 'level', targetEntity: TrainingSkill::class, orphanRemoval: true)]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'level', targetEntity: LevelRequirement::class, orphanRemoval: true)]
    private Collection $requirements;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->requirements = new ArrayCollection();
    }

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

    public function getLevelNumber(): ?int
    {
        return $this->levelNumber;
    }

    public function setLevelNumber(int $levelNumber): static
    {
        $this->levelNumber = $levelNumber;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
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

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(?string $objective): static
    {
        $this->objective = $objective;
        return $this;
    }

    public function getEstimatedDurationWeeks(): ?int
    {
        return $this->estimatedDurationWeeks;
    }

    public function setEstimatedDurationWeeks(?int $estimatedDurationWeeks): static
    {
        $this->estimatedDurationWeeks = $estimatedDurationWeeks;
        return $this;
    }

    public function getDifficultyRating(): ?int
    {
        return $this->difficultyRating;
    }

    public function setDifficultyRating(?int $difficultyRating): static
    {
        $this->difficultyRating = $difficultyRating;
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function getRequirementsSummary(): ?string
    {
        return $this->requirementsSummary;
    }

    public function setRequirementsSummary(?string $requirementsSummary): static
    {
        $this->requirementsSummary = $requirementsSummary;
        return $this;
    }

    public function isIsLockedByDefault(): ?bool
    {
        return $this->isLockedByDefault;
    }

    public function setIsLockedByDefault(bool $isLockedByDefault): static
    {
        $this->isLockedByDefault = $isLockedByDefault;
        return $this;
    }

    /**
     * @return Collection<int, TrainingSkill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    /**
     * @return Collection<int, LevelRequirement>
     */
    public function getRequirements(): Collection
    {
        return $this->requirements;
    }
}
