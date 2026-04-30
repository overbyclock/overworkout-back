<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserLevelProgressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserLevelProgressRepository::class)]
class UserLevelProgress
{
    public const GROUP_READ = 'user_level_progress:read';

    public const STATUS_LOCKED = 'locked';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REPEAT = 'repeat';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'levelProgress')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?TrainingLevel $trainingLevel = null;

    #[ORM\Column(length: 20)]
    #[Groups([self::GROUP_READ])]
    private string $status = self::STATUS_LOCKED;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private int $cyclesCompleted = 0;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private int $currentWeek = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    /**
     * @var Collection<int, TestResult>
     */
    #[ORM\OneToMany(targetEntity: TestResult::class, mappedBy: 'userLevelProgress', cascade: ['persist', 'remove'])]
    #[Groups([self::GROUP_READ])]
    private Collection $testResults;

    public function __construct()
    {
        $this->testResults = new ArrayCollection();
        $this->startedAt = new \DateTimeImmutable();
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

    public function getTrainingLevel(): ?TrainingLevel
    {
        return $this->trainingLevel;
    }

    public function setTrainingLevel(?TrainingLevel $trainingLevel): static
    {
        $this->trainingLevel = $trainingLevel;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCyclesCompleted(): int
    {
        return $this->cyclesCompleted;
    }

    public function setCyclesCompleted(int $cyclesCompleted): static
    {
        $this->cyclesCompleted = $cyclesCompleted;

        return $this;
    }

    public function incrementCycle(): static
    {
        $this->cyclesCompleted++;
        $this->currentWeek = 0;
        $this->status = self::STATUS_IN_PROGRESS;

        return $this;
    }

    public function getCurrentWeek(): int
    {
        return $this->currentWeek;
    }

    public function setCurrentWeek(int $currentWeek): static
    {
        $this->currentWeek = $currentWeek;

        return $this;
    }

    public function advanceWeek(): static
    {
        if ($this->currentWeek < 3) {
            $this->currentWeek++;
        }

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

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * @return Collection<int, TestResult>
     */
    public function getTestResults(): Collection
    {
        return $this->testResults;
    }

    public function addTestResult(TestResult $testResult): static
    {
        if (!$this->testResults->contains($testResult)) {
            $this->testResults->add($testResult);
            $testResult->setUserLevelProgress($this);
        }

        return $this;
    }

    public function removeTestResult(TestResult $testResult): static
    {
        if ($this->testResults->removeElement($testResult)) {
            if ($testResult->getUserLevelProgress() === $this) {
                $testResult->setUserLevelProgress(null);
            }
        }

        return $this;
    }

    #[Groups([self::GROUP_READ])]
    public function getLatestTestResult(): ?TestResult
    {
        return $this->testResults->last() ?: null;
    }
}
