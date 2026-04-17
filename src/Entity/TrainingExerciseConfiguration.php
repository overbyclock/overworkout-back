<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingExerciseConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrainingExerciseConfigurationRepository::class)]
class TrainingExerciseConfiguration
{
    public const GROUP_READ = 'training_exercise_config:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trainingExerciseConfigurations')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?Exercises $exercise = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $reps = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $maxTimeForReps = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'Sets must be a positive value.')]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $setsForExercise = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'The rest between sets must be a positive value.')]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $restBetweenSets = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
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

    public function setReps(?int $reps): static
    {
        if (null !== $reps && $reps <= 0) {
            throw new \InvalidArgumentException('The reps must be a positive value');
        }
        $this->reps = $reps;

        return $this;
    }

    public function getSets(): ?int
    {
        return $this->setsForExercise;
    }

    public function setSets(int $setsForExercise): static
    {
        if ($setsForExercise <= 0) {
            throw new \InvalidArgumentException('Sets must be a positive value');
        }
        $this->setsForExercise = $setsForExercise;

        return $this;
    }

    public function getRestBetweenSets(): ?int
    {
        return $this->restBetweenSets;
    }

    public function setRestBetweenSets(int $restBetweenSets): static
    {
        if ($restBetweenSets <= 0) {
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
        if (null !== $weight && ($weight < 20 || $weight > 100)) {
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

    public function validate(): void
    {
        if (null === $this->reps && null === $this->maxTimeForReps) {
            throw new \InvalidArgumentException('Either reps or maxTimeForReps must be set.');
        }

        if (null !== $this->reps && null !== $this->maxTimeForReps) {
            throw new \InvalidArgumentException('Only one of reps or maxTimeForReps can be set.');
        }
    }

    #[ORM\ManyToOne(targetEntity: TrainingRound::class, inversedBy: 'trainingExerciseConfigurations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?TrainingRound $trainingRound = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: 'The rest between sets must be a positive value.')]
    #[Groups([self::GROUP_READ, TrainingRound::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $restBetweenExercises = null;

    public function getTrainingRound(): ?TrainingRound
    {
        return $this->trainingRound;
    }

    public function setTrainingRound(?TrainingRound $trainingRound): static
    {
        $this->trainingRound = $trainingRound;

        return $this;
    }

    public function getRestBetweenExercises(): ?int
    {
        return $this->restBetweenExercises;
    }

    public function setRestBetweenExercises(?int $restBetweenExercises): static
    {
        if (null !== $restBetweenExercises && $restBetweenExercises <= 0) {
            throw new \InvalidArgumentException('The rest between exercises must be a positive value.');
        }
        $this->restBetweenExercises = $restBetweenExercises;

        return $this;
    }
}
