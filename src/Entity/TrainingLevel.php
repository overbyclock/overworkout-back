<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingLevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TrainingLevelRepository::class)]
#[ORM\Table(name: 'training_levels')]
class TrainingLevel
{
    final public const GROUP_READ = 'level:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'levels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingProgram $program = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?int $levelNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
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

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?array $tips = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?array $testRequirements = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?string $skillFocus = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?int $cyclesCompleted = 0;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?string $programVersion = 'v1';

    #[ORM\Column]
    private ?bool $isLockedByDefault = true;

    #[ORM\OneToMany(mappedBy: 'level', targetEntity: TrainingSkill::class, orphanRemoval: true)]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'level', targetEntity: LevelRequirement::class, orphanRemoval: true)]
    private Collection $requirements;

    #[ORM\OneToMany(mappedBy: 'trainingLevel', targetEntity: Training::class)]
    private Collection $trainings;

    #[ORM\OneToMany(mappedBy: 'trainingLevel', targetEntity: TrainingWeekInfo::class, orphanRemoval: true)]
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private Collection $weekInfos;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->requirements = new ArrayCollection();
        $this->trainings = new ArrayCollection();
        $this->weekInfos = new ArrayCollection();
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

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(?string $objective): static
    {
        $this->objective = $objective;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
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

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getTips(): ?array
    {
        return $this->tips;
    }

    public function setTips(?array $tips): static
    {
        $this->tips = $tips;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getTestRequirements(): ?array
    {
        return $this->testRequirements;
    }

    public function setTestRequirements(?array $testRequirements): static
    {
        $this->testRequirements = $testRequirements;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getSkillFocus(): ?string
    {
        return $this->skillFocus;
    }

    public function setSkillFocus(?string $skillFocus): static
    {
        $this->skillFocus = $skillFocus;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getCyclesCompleted(): ?int
    {
        return $this->cyclesCompleted;
    }

    public function setCyclesCompleted(?int $cyclesCompleted): static
    {
        $this->cyclesCompleted = $cyclesCompleted;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getProgramVersion(): ?string
    {
        return $this->programVersion;
    }

    public function setProgramVersion(?string $programVersion): static
    {
        $this->programVersion = $programVersion;

        return $this;
    }

    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
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
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getRequirements(): Collection
    {
        return $this->requirements;
    }

    /**
     * @return Collection<int, Training>
     */
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->setTrainingLevel($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): static
    {
        if ($this->trainings->removeElement($training)) {
            if ($training->getTrainingLevel() === $this) {
                $training->setTrainingLevel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrainingWeekInfo>
     */
    #[Groups([self::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    public function getWeekInfos(): Collection
    {
        return $this->weekInfos;
    }

    public function addWeekInfo(TrainingWeekInfo $weekInfo): static
    {
        if (!$this->weekInfos->contains($weekInfo)) {
            $this->weekInfos->add($weekInfo);
            $weekInfo->setTrainingLevel($this);
        }

        return $this;
    }

    public function removeWeekInfo(TrainingWeekInfo $weekInfo): static
    {
        if ($this->weekInfos->removeElement($weekInfo)) {
            if ($weekInfo->getTrainingLevel() === $this) {
                $weekInfo->setTrainingLevel(null);
            }
        }

        return $this;
    }
}
