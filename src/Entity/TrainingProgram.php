<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TrainingProgramRepository::class)]
#[ORM\Table(name: 'training_programs')]
class TrainingProgram
{
    final public const GROUP_READ = 'program:read';
    final public const GROUP_DETAIL = 'program:detail';

    /**
     * Modos de evaluación de los tests de nivel:
     * - legacy: regla histórica (3 de 4 mínimos superados).
     * - strict_gates: todos los mínimos de fuerza + todas las puertas de movilidad.
     */
    final public const TEST_EVAL_LEGACY = 'legacy';
    final public const TEST_EVAL_STRICT_GATES = 'strict_gates';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, 'achievement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, 'achievement:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups([self::GROUP_READ, 'achievement:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Groups([self::GROUP_READ])]
    private ?string $discipline = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $totalLevels = 12;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $estimatedDurationWeeks = null;

    #[ORM\Column(length: 20)]
    #[Groups([self::GROUP_READ])]
    private ?string $difficulty = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?bool $isActive = true;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 20, options: ['default' => self::TEST_EVAL_LEGACY])]
    #[Groups([self::GROUP_READ])]
    private string $testEvaluationMode = self::TEST_EVAL_LEGACY;

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

    public function getTestEvaluationMode(): string
    {
        return $this->testEvaluationMode;
    }

    public function setTestEvaluationMode(string $testEvaluationMode): static
    {
        $this->testEvaluationMode = $testEvaluationMode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, TrainingLevel>
     */
    #[Groups([self::GROUP_DETAIL])]
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

    #[Groups([self::GROUP_READ])]
    public function getLevelCount(): int
    {
        return $this->levels->count();
    }

    #[Groups([self::GROUP_READ])]
    public function getTotalPhases(): int
    {
        $total = 0;

        foreach ($this->levels as $level) {
            $total += $level->getWeekInfos()->count();
        }

        return $total;
    }

    #[Groups([self::GROUP_READ])]
    public function getTotalSessions(): int
    {
        $total = 0;

        foreach ($this->levels as $level) {
            $total += $level->getTrainings()->count();
        }

        return $total;
    }
}
