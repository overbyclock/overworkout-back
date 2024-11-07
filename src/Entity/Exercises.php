<?php

namespace App\Entity;

use App\Enum\Levels;
use App\Enum\MuscleGroup;
use App\Entity\Equipments;
use App\Repository\ExercisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExercisesRepository::class)]
class Exercises
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(enumType: MuscleGroup::class)]
  private ?MuscleGroup $primaryMuscleGroup = null;

  #[ORM\Column(enumType: MuscleGroup::class)]
  private ?MuscleGroup $secondaryMuscleGroup = null;

  #[ORM\Column(enumType: Levels::class)]
  private ?Levels $level = null;

  #[ORM\JoinColumn(nullable: true)]
  #[ORM\ManyToOne(targetEntity: Equipments::class)]
  private ?Equipments $equipment = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $media = null;

  /**
   * @var Collection<int, TrainingExerciseConfiguration>
   */
  #[ORM\OneToMany(targetEntity: TrainingExerciseConfiguration::class, mappedBy: 'exercise')]
  private Collection $trainingExerciseConfigurations;

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
