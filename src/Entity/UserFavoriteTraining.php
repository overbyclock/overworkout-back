<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserFavoriteTrainingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserFavoriteTrainingRepository::class)]
#[ORM\Table(name: 'user_favorite_training')]
#[ORM\UniqueConstraint(name: 'unique_user_training_favorite', columns: ['user_id', 'training_id'])]
class UserFavoriteTraining
{
    public const GROUP_READ = 'user_favorite_training:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favoriteTrainings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Training::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?Training $training = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
