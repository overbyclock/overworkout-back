<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BenchmarkExerciseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BenchmarkExerciseRepository::class)]
class BenchmarkExercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Benchmark::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    private Benchmark $benchmark;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([Benchmark::GROUP_READ])]
    private Exercises $exercise;

    #[ORM\Column]
    #[Groups([Benchmark::GROUP_READ])]
    private int $position;

    #[ORM\Column(nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?int $reps = null;

    #[ORM\Column(nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?int $distanceMeters = null;

    #[ORM\Column(nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?int $durationSeconds = null;

    #[ORM\Column(nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?int $calories = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?string $rxWeightMale = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?string $rxWeightFemale = null;

    #[ORM\Column(nullable: true)]
    #[Groups([Benchmark::GROUP_READ])]
    private ?int $restSeconds = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBenchmark(): Benchmark
    {
        return $this->benchmark;
    }

    public function setBenchmark(Benchmark $benchmark): static
    {
        $this->benchmark = $benchmark;

        return $this;
    }

    public function getExercise(): Exercises
    {
        return $this->exercise;
    }

    public function setExercise(Exercises $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(?int $reps): static
    {
        $this->reps = $reps;

        return $this;
    }

    public function getDistanceMeters(): ?int
    {
        return $this->distanceMeters;
    }

    public function setDistanceMeters(?int $distanceMeters): static
    {
        $this->distanceMeters = $distanceMeters;

        return $this;
    }

    public function getDurationSeconds(): ?int
    {
        return $this->durationSeconds;
    }

    public function setDurationSeconds(?int $durationSeconds): static
    {
        $this->durationSeconds = $durationSeconds;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getRxWeightMale(): ?string
    {
        return $this->rxWeightMale;
    }

    public function setRxWeightMale(?string $rxWeightMale): static
    {
        $this->rxWeightMale = $rxWeightMale;

        return $this;
    }

    public function getRxWeightFemale(): ?string
    {
        return $this->rxWeightFemale;
    }

    public function setRxWeightFemale(?string $rxWeightFemale): static
    {
        $this->rxWeightFemale = $rxWeightFemale;

        return $this;
    }

    public function getRestSeconds(): ?int
    {
        return $this->restSeconds;
    }

    public function setRestSeconds(?int $restSeconds): static
    {
        $this->restSeconds = $restSeconds;

        return $this;
    }
}
