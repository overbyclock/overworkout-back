<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\Levels;
use App\Enum\MuscleGroup;
use App\Entity\Equipments;
use App\Repository\ExercisesRepository;
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

    #[ORM\JoinColumn(nullable:true)]
    #[ORM\ManyToOne(targetEntity: Equipments::class)]
    private ?bool $equipment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

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

    public function getEquipment(): ?bool
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
}
