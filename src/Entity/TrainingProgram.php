<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TrainingProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingProgramRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ]
)]
class TrainingProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $discipline = null;

    #[ORM\Column]
    private ?int $totalLevels = 12;

    #[ORM\Column(nullable: true)]
    private ?int $estimatedDurationWeeks = null;

    #[ORM\Column(length: 20)]
    private ?string $difficulty = null;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: TrainingLevel::class, orphanRemoval: true)]
    #[ORM\OrderBy(['levelNumber' => 'ASC'])]
    private Collection $levels;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->levels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): static
    {
        $this->discipline = $discipline;
        return $this;
    }

    public function getTotalLevels(): ?int
    {
        return $this->totalLevels;
    }

    public function setTotalLevels(int $totalLevels): static
    {
        $this->totalLevels = $totalLevels;
        return $this;
    }

    public function getEstimatedDurationWeeks(): ?int
    {
        return $this->estimatedDurationWeeks;
    }

    public function setEstimatedDurationWeeks(?int $estimatedDurationWeeks): static
    {
        $this->estimatedDurationWeeks = $estimatedDurationWeeks;
        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, TrainingLevel>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(TrainingLevel $level): static
    {
        if (!$this->levels->contains($level)) {
            $this->levels->add($level);
            $level->setProgram($this);
        }
        return $this;
    }
}
