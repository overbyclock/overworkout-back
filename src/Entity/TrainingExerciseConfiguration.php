<?php

namespace App\Entity;

use App\Repository\TrainingExcerciseConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrainingExcerciseConfigurationRepository::class)]
class TrainingExerciseConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trainingExcerciseConfigurations')]
    private ?Exercises $exercise = null;

    #[ORM\Column(nullable:true)]
    private ?int $reps = null;

    #[ORM\Column(nullable:true)]
    private ?int $maxTimeForReps = null;

    #[ORM\Column]
    #[Assert\Positive(message:'Sets must be a positive value.')]
    private ?int $sets = null;

    #[ORM\Column]
    #[Assert\Positive(message:'The rest between sets must be a positive value.')]
    private ?int $restBetweenSets = null;

    #[ORM\Column(nullable: true)]
    private ?int $weight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExercise(): ?Exercises
    {
        return $this->exercise;
    }

    public function setExercise(?Exercises $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): static
    {   
        if($reps !== null && $reps <= 0){
          throw new \InvalidArgumentException('The reps must be a positive value');
        }
        $this->reps = $reps;

        return $this;
    }

    public function getSets(): ?int
    {
        return $this->sets;
    }

    public function setSets(int $sets): static
    {
      if($sets !== null && $sets <= 0){
        throw new \InvalidArgumentException('Sets must be a positive value');
      }
        $this->sets = $sets;

        return $this;
    }

    public function getRestBetweenSets(): ?int
    {
        return $this->restBetweenSets;
    }

    public function setRestBetweenSets(int $restBetweenSets): static
    {
        if($restBetweenSets <= 0){
          throw new \InvalidArgumentException('The rest between sets must be a positive value');
        }
        $this->restBetweenSets = $restBetweenSets;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    { 
        if($weight !== null && ($weight < 20 || $weight > 100 )){
          throw new \InvalidArgumentException('Weight percentage must be between 20 and 100.');
        }
        $this->weight = $weight;

        return $this;
    }

    public function getMaxTimeForReps(): ?int
    {
        return $this->maxTimeForReps;
    }

    public function setMaxTimeForReps(?int $maxTimeForReps): static
    {
        $this->maxTimeForReps = $maxTimeForReps;

        return $this;
    }

    public function validate():void
    {
      if($this->reps === null && $this->maxTimeForReps === null){
        throw new \InvalidArgumentException('Either reps or maxTimeForReps must be set.');
      }

      if($this->reps !== null && $this->maxTimeForReps !== null){
        throw new \InvalidArgumentException('Only one of reps or maxTimeForReps can be set.');
      }
    }
}
