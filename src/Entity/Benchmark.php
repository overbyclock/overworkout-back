<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\BenchmarkFormat;
use App\Repository\BenchmarkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BenchmarkRepository::class)]
class Benchmark
{
    public const GROUP_READ = 'benchmark:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups([self::GROUP_READ])]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $description = null;

    #[ORM\Column(length: 30, enumType: BenchmarkFormat::class, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?BenchmarkFormat $format = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $rounds = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $timeCapSeconds = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?int $restBetweenRoundsSeconds = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private bool $partitioningAllowed = false;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?array $timerConfig = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $rxWeightMale = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $rxWeightFemale = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?array $movements = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $eliteTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $eliteTimeFemale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $advancedTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $advancedTimeFemale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $intermediateTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $intermediateTimeFemale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $beginnerTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $beginnerTimeFemale = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $scalingOptions = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $videoUrl = null;

    /**
     * @var Collection<int, BenchmarkExercise>
     */
    #[ORM\OneToMany(targetEntity: BenchmarkExercise::class, mappedBy: 'benchmark', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups([self::GROUP_READ])]
    private Collection $exercises;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getFormat(): ?BenchmarkFormat
    {
        return $this->format;
    }

    public function setFormat(?BenchmarkFormat $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getRounds(): ?int
    {
        return $this->rounds;
    }

    public function setRounds(?int $rounds): static
    {
        $this->rounds = $rounds;

        return $this;
    }

    public function getTimeCapSeconds(): ?int
    {
        return $this->timeCapSeconds;
    }

    public function setTimeCapSeconds(?int $timeCapSeconds): static
    {
        $this->timeCapSeconds = $timeCapSeconds;

        return $this;
    }

    public function getRestBetweenRoundsSeconds(): ?int
    {
        return $this->restBetweenRoundsSeconds;
    }

    public function setRestBetweenRoundsSeconds(?int $restBetweenRoundsSeconds): static
    {
        $this->restBetweenRoundsSeconds = $restBetweenRoundsSeconds;

        return $this;
    }

    public function isPartitioningAllowed(): bool
    {
        return $this->partitioningAllowed;
    }

    public function setPartitioningAllowed(bool $partitioningAllowed): static
    {
        $this->partitioningAllowed = $partitioningAllowed;

        return $this;
    }

    public function getTimerConfig(): ?array
    {
        return $this->timerConfig;
    }

    public function setTimerConfig(?array $timerConfig): static
    {
        $this->timerConfig = $timerConfig;

        return $this;
    }

    public function getRxWeightMale(): ?string
    {
        return $this->rxWeightMale;
    }

    public function setRxWeightMale(?string $rxWeightMale): static
    {
        $this->rxWeightMale = $rxWeightMale;

        return $this;
    }

    public function getRxWeightFemale(): ?string
    {
        return $this->rxWeightFemale;
    }

    public function setRxWeightFemale(?string $rxWeightFemale): static
    {
        $this->rxWeightFemale = $rxWeightFemale;

        return $this;
    }

    public function getMovements(): ?array
    {
        return $this->movements;
    }

    public function setMovements(?array $movements): static
    {
        $this->movements = $movements;

        return $this;
    }

    public function getEliteTimeMale(): ?string
    {
        return $this->eliteTimeMale;
    }

    public function setEliteTimeMale(?string $eliteTimeMale): static
    {
        $this->eliteTimeMale = $eliteTimeMale;

        return $this;
    }

    public function getEliteTimeFemale(): ?string
    {
        return $this->eliteTimeFemale;
    }

    public function setEliteTimeFemale(?string $eliteTimeFemale): static
    {
        $this->eliteTimeFemale = $eliteTimeFemale;

        return $this;
    }

    public function getAdvancedTimeMale(): ?string
    {
        return $this->advancedTimeMale;
    }

    public function setAdvancedTimeMale(?string $advancedTimeMale): static
    {
        $this->advancedTimeMale = $advancedTimeMale;

        return $this;
    }

    public function getAdvancedTimeFemale(): ?string
    {
        return $this->advancedTimeFemale;
    }

    public function setAdvancedTimeFemale(?string $advancedTimeFemale): static
    {
        $this->advancedTimeFemale = $advancedTimeFemale;

        return $this;
    }

    public function getIntermediateTimeMale(): ?string
    {
        return $this->intermediateTimeMale;
    }

    public function setIntermediateTimeMale(?string $intermediateTimeMale): static
    {
        $this->intermediateTimeMale = $intermediateTimeMale;

        return $this;
    }

    public function getIntermediateTimeFemale(): ?string
    {
        return $this->intermediateTimeFemale;
    }

    public function setIntermediateTimeFemale(?string $intermediateTimeFemale): static
    {
        $this->intermediateTimeFemale = $intermediateTimeFemale;

        return $this;
    }

    public function getBeginnerTimeMale(): ?string
    {
        return $this->beginnerTimeMale;
    }

    public function setBeginnerTimeMale(?string $beginnerTimeMale): static
    {
        $this->beginnerTimeMale = $beginnerTimeMale;

        return $this;
    }

    public function getBeginnerTimeFemale(): ?string
    {
        return $this->beginnerTimeFemale;
    }

    public function setBeginnerTimeFemale(?string $beginnerTimeFemale): static
    {
        $this->beginnerTimeFemale = $beginnerTimeFemale;

        return $this;
    }

    public function getScalingOptions(): ?string
    {
        return $this->scalingOptions;
    }

    public function setScalingOptions(?string $scalingOptions): static
    {
        $this->scalingOptions = $scalingOptions;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    /**
     * @return Collection<int, BenchmarkExercise>
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(BenchmarkExercise $exercise): static
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->setBenchmark($this);
        }

        return $this;
    }

    public function removeExercise(BenchmarkExercise $exercise): static
    {
        $this->exercises->removeElement($exercise);

        return $this;
    }

    public function clearExercises(): static
    {
        $this->exercises->clear();

        return $this;
    }
}
