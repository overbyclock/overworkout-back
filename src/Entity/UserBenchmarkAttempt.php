<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserBenchmarkAttemptRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserBenchmarkAttemptRepository::class)]
class UserBenchmarkAttempt
{
    public const GROUP_READ = 'benchmark_attempt:read';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DNF = 'dnf';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?Benchmark $benchmark = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $totalTimeSeconds = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $scoreValue = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $scoreDisplay = null;

    #[ORM\Column(length: 30)]
    #[Groups([self::GROUP_READ])]
    private ?string $scalingUsed = null;

    #[ORM\Column(length: 30)]
    #[Groups([self::GROUP_READ])]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $notes = null;

    /**
     * @var Collection<int, UserBenchmarkExerciseLog>
     */
    #[ORM\OneToMany(targetEntity: UserBenchmarkExerciseLog::class, mappedBy: 'attempt', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups([self::GROUP_READ])]
    private Collection $exerciseLogs;

    public function __construct()
    {
        $this->exerciseLogs = new ArrayCollection();
        $this->startedAt = new \DateTimeImmutable();
        $this->status = self::STATUS_IN_PROGRESS;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBenchmark(): ?Benchmark
    {
        return $this->benchmark;
    }

    public function setBenchmark(?Benchmark $benchmark): static
    {
        $this->benchmark = $benchmark;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getTotalTimeSeconds(): ?int
    {
        return $this->totalTimeSeconds;
    }

    public function setTotalTimeSeconds(?int $totalTimeSeconds): static
    {
        $this->totalTimeSeconds = $totalTimeSeconds;

        return $this;
    }

    public function getScoreValue(): ?string
    {
        return $this->scoreValue;
    }

    public function setScoreValue(?string $scoreValue): static
    {
        $this->scoreValue = $scoreValue;

        return $this;
    }

    public function getScoreDisplay(): ?string
    {
        return $this->scoreDisplay;
    }

    public function setScoreDisplay(?string $scoreDisplay): static
    {
        $this->scoreDisplay = $scoreDisplay;

        return $this;
    }

    public function getScalingUsed(): ?string
    {
        return $this->scalingUsed;
    }

    public function setScalingUsed(string $scalingUsed): static
    {
        $this->scalingUsed = $scalingUsed;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    /**
     * @return Collection<int, UserBenchmarkExerciseLog>
     */
    public function getExerciseLogs(): Collection
    {
        return $this->exerciseLogs;
    }

    public function addExerciseLog(UserBenchmarkExerciseLog $exerciseLog): static
    {
        if (!$this->exerciseLogs->contains($exerciseLog)) {
            $this->exerciseLogs->add($exerciseLog);
            $exerciseLog->setAttempt($this);
        }

        return $this;
    }

    public function removeExerciseLog(UserBenchmarkExerciseLog $exerciseLog): static
    {
        if ($this->exerciseLogs->removeElement($exerciseLog)) {
            if ($exerciseLog->getAttempt() === $this) {
                $exerciseLog->setAttempt(null);
            }
        }

        return $this;
    }
}
