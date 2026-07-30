<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserAchievementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * Logro desbloqueado por un usuario.
 *
 * Sustituye a la tabla legacy `user_achievements` (excluida del schema_filter),
 * que ya no está gestionada por Doctrine.
 */
#[ORM\Entity(repositoryClass: UserAchievementRepository::class)]
#[ORM\Table(name: 'user_achievement')]
#[ORM\UniqueConstraint(name: 'uniq_user_achievement', columns: ['user_id', 'achievement_id'])]
class UserAchievement
{
    public const GROUP_READ = 'user_achievement:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::GROUP_READ])]
    private ?Achievement $achievement = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $unlockedAt = null;

    /**
     * Contexto del desbloqueo (p. ej. {"levelNumber": 4, "cycleNumber": 2}).
     *
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?array $context = null;

    public function __construct()
    {
        $this->unlockedAt = new \DateTimeImmutable();
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

    public function getAchievement(): ?Achievement
    {
        return $this->achievement;
    }

    public function setAchievement(?Achievement $achievement): static
    {
        $this->achievement = $achievement;

        return $this;
    }

    public function getUnlockedAt(): ?\DateTimeImmutable
    {
        return $this->unlockedAt;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @param array<string, mixed>|null $context
     */
    public function setContext(?array $context): static
    {
        $this->context = $context;

        return $this;
    }
}
