<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserTrainingSessionStepRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserTrainingSessionStepRepository::class)]
class UserTrainingSessionStep
{
    public const GROUP_READ = 'session_step:read';

    public const TYPE_EXERCISE = 'exercise';
    public const TYPE_REST = 'rest';

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SKIPPED = 'skipped';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sessionSteps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTrainingLog $log = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private int $position = 0;

    #[ORM\Column(length: 20)]
    #[Groups([self::GROUP_READ])]
    private string $type = self::TYPE_EXERCISE;

    #[ORM\Column(length: 20)]
    #[Groups([self::GROUP_READ])]
    private string $status = self::STATUS_PENDING;

    /** @var array<string, mixed> */
    #[ORM\Column(type: Types::JSON)]
    #[Groups([self::GROUP_READ])]
    private array $payload = [];

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $completedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLog(): ?UserTrainingLog
    {
        return $this->log;
    }

    public function setLog(?UserTrainingLog $log): static
    {
        $this->log = $log;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPending(): bool
    {
        return self::STATUS_PENDING === $this->status;
    }

    public function complete(): void
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completedAt = new \DateTimeImmutable();
    }

    public function skip(): void
    {
        $this->status = self::STATUS_SKIPPED;
        $this->completedAt = new \DateTimeImmutable();
    }

    public function reopen(): void
    {
        $this->status = self::STATUS_PENDING;
        $this->completedAt = null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function setPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }
}
