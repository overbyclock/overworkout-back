<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Levels;
use App\Enum\MuscleGroup;
use App\Repository\ExercisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ExercisesRepository::class)]
class Exercises
{
    public const GROUP_READ = 'exercise:read';
    public const GROUP_READ_DETAIL = 'exercise:read:detail';
    public const GROUP_WRITE = 'exercise:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, TrainingExerciseConfiguration::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, TrainingExerciseConfiguration::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?string $name = null;

    #[ORM\Column(enumType: MuscleGroup::class)]
    #[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
    private ?MuscleGroup $primaryMuscleGroup = null;

    #[ORM\Column(enumType: MuscleGroup::class, nullable: true)]
    #[Groups([self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
    private ?MuscleGroup $secondaryMuscleGroup = null;

    #[ORM\Column(enumType: Levels::class)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
    private ?Levels $level = null;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Equipments::class)]
    private ?Equipments $equipment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, Training::GROUP_READ_DETAIL])]
    private ?int $difficultyRating = 1;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $disciplines = [];

    /**
     * @var Collection<int, TrainingExerciseConfiguration>
     */
    #[ORM\OneToMany(targetEntity: TrainingExerciseConfiguration::class, mappedBy: 'exercise')]
    private Collection $trainingExerciseConfigurations;

    public function getDifficultyRating(): ?int
    {
        return $this->difficultyRating;
    }

    public function setDifficultyRating(?int $difficultyRating): static
    {
        $this->difficultyRating = $difficultyRating;

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

    public function getDisciplines(): ?array
    {
        return $this->disciplines ?? [];
    }

    public function setDisciplines(?array $disciplines): static
    {
        $this->disciplines = $disciplines;

        return $this;
    }

    public function __construct()
    {
        $this->trainingExerciseConfigurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrimaryMuscleGroup(): ?MuscleGroup
    {
        return $this->primaryMuscleGroup;
    }

    public function setPrimaryMuscleGroup(MuscleGroup $primaryMuscleGroup): static
    {
        $this->primaryMuscleGroup = $primaryMuscleGroup;

        return $this;
    }

    public function getSecondaryMuscleGroup(): ?MuscleGroup
    {
        return $this->secondaryMuscleGroup;
    }

    public function setSecondaryMuscleGroup(MuscleGroup $secondaryMuscleGroup): static
    {
        $this->secondaryMuscleGroup = $secondaryMuscleGroup;

        return $this;
    }

    public function getLevel(): ?Levels
    {
        return $this->level;
    }

    public function setLevel(Levels $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getEquipment(): ?Equipments
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipments $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection<int, TrainingExerciseConfiguration>
     */
    public function getTrainingExerciseConfigurations(): Collection
    {
        return $this->trainingExerciseConfigurations;
    }

    public function addTrainingExerciseConfiguration(TrainingExerciseConfiguration $trainingExerciseConfiguration): static
    {
        if (!$this->trainingExerciseConfigurations->contains($trainingExerciseConfiguration)) {
            $this->trainingExerciseConfigurations->add($trainingExerciseConfiguration);
            $trainingExerciseConfiguration->setExercise($this);
        }

        return $this;
    }

    public function removeTrainingExerciseConfiguration(TrainingExerciseConfiguration $trainingExerciseConfiguration): static
    {
        if ($this->trainingExerciseConfigurations->removeElement($trainingExerciseConfiguration)) {
            // set the owning side to null (unless already changed)
            if ($trainingExerciseConfiguration->getExercise() === $this) {
                $trainingExerciseConfiguration->setExercise(null);
            }
        }

        return $this;
    }
}
