<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BenchmarkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BenchmarkRepository::class)]
class Benchmark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null; // girl, hero, benchmark

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rxWeightMale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rxWeightFemale = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $movements = null;

    // Rango Élite
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $eliteTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $eliteTimeFemale = null;

    // Rango Avanzado
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $advancedTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $advancedTimeFemale = null;

    // Rango Intermedio
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $intermediateTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $intermediateTimeFemale = null;

    // Rango Principiante
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $beginnerTimeMale = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $beginnerTimeFemale = null;

    // Escalado opciones
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $scalingOptions = null;

    // Video tutorial
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $videoUrl = null;

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
}
