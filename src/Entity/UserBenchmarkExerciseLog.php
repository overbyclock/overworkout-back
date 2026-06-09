<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserBenchmarkExerciseLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserBenchmarkExerciseLogRepository::class)]
class UserBenchmarkExerciseLog
{
    public const GROUP_READ = 'benchmark_exercise_log:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exerciseLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBenchmarkAttempt $attempt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?BenchmarkExercise $benchmarkExercise = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $roundNumber = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $repsCompleted = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?string $weightUsed = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $distanceCompleted = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $caloriesCompleted = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $durationSeconds = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?int $restTakenSeconds = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ, UserBenchmarkAttempt::GROUP_READ])]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttempt(): ?UserBenchmarkAttempt
    {
        return $this->attempt;
    }

    public function setAttempt(?UserBenchmarkAttempt $attempt): static
    {
        $this->attempt = $attempt;

        return $this;
    }

    public function getBenchmarkExercise(): ?BenchmarkExercise
    {
        return $this->benchmarkExercise;
    }

    public function setBenchmarkExercise(?BenchmarkExercise $benchmarkExercise): static
    {
        $this->benchmarkExercise = $benchmarkExercise;

        return $this;
    }

    public function getRoundNumber(): ?int
    {
        return $this->roundNumber;
    }

    public function setRoundNumber(?int $roundNumber): static
    {
        $this->roundNumber = $roundNumber;

        return $this;
    }

    public function getRepsCompleted(): ?int
    {
        return $this->repsCompleted;
    }

    public function setRepsCompleted(?int $repsCompleted): static
    {
        $this->repsCompleted = $repsCompleted;

        return $this;
    }

    public function getWeightUsed(): ?string
    {
        return $this->weightUsed;
    }

    public function setWeightUsed(?string $weightUsed): static
    {
        $this->weightUsed = $weightUsed;

        return $this;
    }

    public function getDistanceCompleted(): ?int
    {
        return $this->distanceCompleted;
    }

    public function setDistanceCompleted(?int $distanceCompleted): static
    {
        $this->distanceCompleted = $distanceCompleted;

        return $this;
    }

    public function getCaloriesCompleted(): ?int
    {
        return $this->caloriesCompleted;
    }

    public function setCaloriesCompleted(?int $caloriesCompleted): static
    {
        $this->caloriesCompleted = $caloriesCompleted;

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

    public function getRestTakenSeconds(): ?int
    {
        return $this->restTakenSeconds;
    }

    public function setRestTakenSeconds(?int $restTakenSeconds): static
    {
        $this->restTakenSeconds = $restTakenSeconds;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
