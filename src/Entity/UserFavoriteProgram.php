<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserFavoriteProgramRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserFavoriteProgramRepository::class)]
#[ORM\Table(name: 'user_favorite_program')]
#[ORM\UniqueConstraint(name: 'unique_user_program_favorite', columns: ['user_id', 'training_program_id'])]
class UserFavoriteProgram
{
    public const GROUP_READ = 'user_favorite_program:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favoritePrograms')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: TrainingProgram::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?TrainingProgram $trainingProgram = null;

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

    public function getTrainingProgram(): ?TrainingProgram
    {
        return $this->trainingProgram;
    }

    public function setTrainingProgram(?TrainingProgram $trainingProgram): static
    {
        $this->trainingProgram = $trainingProgram;

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
