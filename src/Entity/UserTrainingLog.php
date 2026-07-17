<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserTrainingLogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserTrainingLogRepository::class)]
class UserTrainingLog
{
    public const GROUP_READ = 'user_training_log:read';

    public const SOURCE_PROGRAM = 'program';
    public const SOURCE_FREE = 'free';
    public const SOURCE_BENCHMARK = 'benchmark';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userTrainingLogs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userTrainingLogs')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?Training $training = null;

    #[ORM\Column(length: 30)]
    #[Groups([self::GROUP_READ])]
    private string $source = self::SOURCE_PROGRAM;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $durationSeconds = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([self::GROUP_READ])]
    private int $xpEarned = 0;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups([self::GROUP_READ])]
    private int $currentPosition = 0;

    /**
     * @var Collection<int, UserTrainingSessionStep>
     */
    #[ORM\OneToMany(targetEntity: UserTrainingSessionStep::class, mappedBy: 'log', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $sessionSteps;

    public function __construct()
    {
        $this->startedAt = new \DateTimeImmutable();
        $this->completedAt = null;
        $this->sessionSteps = new ArrayCollection();
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

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): static
    {
        $this->training = $training;

        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): static
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

    public function getDurationSeconds(): ?int
    {
        return $this->durationSeconds;
    }

    public function setDurationSeconds(?int $durationSeconds): static
    {
        $this->durationSeconds = $durationSeconds;

        return $this;
    }

    public function getXpEarned(): int
    {
        return $this->xpEarned;
    }

    public function setXpEarned(int $xpEarned): static
    {
        $this->xpEarned = $xpEarned;

        return $this;
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }

    public function setCurrentPosition(int $currentPosition): static
    {
        $this->currentPosition = $currentPosition;

        return $this;
    }

    /**
     * @return Collection<int, UserTrainingSessionStep>
     */
    public function getSessionSteps(): Collection
    {
        return $this->sessionSteps;
    }

    public function addSessionStep(UserTrainingSessionStep $step): static
    {
        if (!$this->sessionSteps->contains($step)) {
            $this->sessionSteps->add($step);
            $step->setLog($this);
        }

        return $this;
    }

    public function removeSessionStep(UserTrainingSessionStep $step): static
    {
        if ($this->sessionSteps->removeElement($step)) {
            if ($step->getLog() === $this) {
                $step->setLog(null);
            }
        }

        return $this;
    }
}
