<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CommunityPostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommunityPostRepository::class)]
#[ORM\Table(name: 'community_posts')]
class CommunityPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    private ?string $type = 'progress';

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $mediaUrls = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Achievement $achievement = null;

    #[ORM\Column(nullable: true)]
    private ?int $levelReached = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?TrainingSkill $skillUnlocked = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prExercise = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prValue = null;

    #[ORM\Column]
    private ?bool $isPublic = true;

    #[ORM\Column]
    private ?int $likesCount = 0;

    #[ORM\Column]
    private ?int $commentsCount = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getMediaUrls(): ?array
    {
        return $this->mediaUrls;
    }

    public function setMediaUrls(?array $mediaUrls): static
    {
        $this->mediaUrls = $mediaUrls;

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

    public function getLevelReached(): ?int
    {
        return $this->levelReached;
    }

    public function setLevelReached(?int $levelReached): static
    {
        $this->levelReached = $levelReached;

        return $this;
    }

    public function getSkillUnlocked(): ?TrainingSkill
    {
        return $this->skillUnlocked;
    }

    public function setSkillUnlocked(?TrainingSkill $skillUnlocked): static
    {
        $this->skillUnlocked = $skillUnlocked;

        return $this;
    }

    public function getPrExercise(): ?string
    {
        return $this->prExercise;
    }

    public function setPrExercise(?string $prExercise): static
    {
        $this->prExercise = $prExercise;

        return $this;
    }

    public function getPrValue(): ?string
    {
        return $this->prValue;
    }

    public function setPrValue(?string $prValue): static
    {
        $this->prValue = $prValue;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getLikesCount(): ?int
    {
        return $this->likesCount;
    }

    public function setLikesCount(int $likesCount): static
    {
        $this->likesCount = $likesCount;

        return $this;
    }

    public function getCommentsCount(): ?int
    {
        return $this->commentsCount;
    }

    public function setCommentsCount(int $commentsCount): static
    {
        $this->commentsCount = $commentsCount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
