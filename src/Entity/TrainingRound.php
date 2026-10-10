<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingRoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrainingRoundRepository::class)]
class TrainingRound
{
    public const GROUP_READ = 'training_round:read';

    /**
     * Tipos de bloque de una sesión (opcional; null en programas sin bloques tipados).
     * Corresponde a la arquitectura de 5 bloques: Bloque 0 (preparación/movilidad),
     * principal, salud postural, cadena posterior/cadera y descarga.
     * Los bloques fuerza_skill y wod son específicos de sesiones CrossFit.
     */
    public const BLOCK_PREPARACION = 'preparacion';
    public const BLOCK_PRINCIPAL = 'principal';
    public const BLOCK_POSTURAL = 'postural';
    public const BLOCK_CADENA_POSTERIOR = 'cadena_posterior';
    public const BLOCK_DESCARGA = 'descarga';
    public const BLOCK_FUERZA_SKILL = 'fuerza_skill';
    public const BLOCK_WOD = 'wod';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: 'The round must be between {{ min }} and {{ max }}.',
    )]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $setsForRound = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'The rest between rounds must be a positive value.')]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $restBetweenRounds = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?int $restAfterBlock = null;

    /**
     * Tipo de bloque de la sesión (ver constantes BLOCK_*). Null = sin tipar.
     */
    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Choice(
        choices: [self::BLOCK_PREPARACION, self::BLOCK_PRINCIPAL, self::BLOCK_POSTURAL, self::BLOCK_CADENA_POSTERIOR, self::BLOCK_DESCARGA, self::BLOCK_FUERZA_SKILL, self::BLOCK_WOD],
        message: 'El tipo de bloque no es válido.',
    )]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?string $blockType = null;

    /**
     * Nombre visible del bloque (p. ej. "Preparación y movilidad"). Null = sin nombre.
     */
    #[ORM\Column(length: 100, nullable: true)]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?string $name = null;

    /**
     * Descripción opcional del bloque (qué se trabaja en él).
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private ?string $description = null;

    /**
     * Benchmark (WOD con nombre) embebido en el round. Si está presente,
     * las configuraciones de ejercicios propias del round se ignoran.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'benchmark_id', referencedColumnName: 'id', nullable: true)]
    private ?Benchmark $benchmark = null;

    #[ORM\ManyToOne(inversedBy: 'trainingRounds')]
    #[ORM\JoinColumn(name: 'training_id', referencedColumnName: 'id')]
    private ?Training $training = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    public function getSetsForRound(): ?int
    {
        return $this->setsForRound;
    }

    public function setSetsForRound(int $setsForRound): static
    {
        if ($setsForRound < 1 || $setsForRound > 100) {
            throw new \InvalidArgumentException('The round must between 1 and 100');
        }
        $this->setsForRound = $setsForRound;

        return $this;
    }

    /** @deprecated Use getSetsForRound() */
    public function getRound(): ?int
    {
        return $this->setsForRound;
    }

    /** @deprecated Use setSetsForRound() */
    public function setRound(int $setsForRound): static
    {
        return $this->setSetsForRound($setsForRound);
    }

    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    public function getRestBetweenRounds(): ?int
    {
        return $this->restBetweenRounds;
    }

    public function setRestBetweenRounds(int $restBetweenRounds): static
    {
        if ($restBetweenRounds <= 0) {
            throw new \InvalidArgumentException('The rest between rounds must be a positive value');
        }
        $this->restBetweenRounds = $restBetweenRounds;

        return $this;
    }

    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    public function getRestAfterBlock(): ?int
    {
        return $this->restAfterBlock;
    }

    public function setRestAfterBlock(?int $restAfterBlock): static
    {
        $this->restAfterBlock = $restAfterBlock;

        return $this;
    }

    public function getBlockType(): ?string
    {
        return $this->blockType;
    }

    public function setBlockType(?string $blockType): static
    {
        $this->blockType = $blockType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function getBenchmark(): ?Benchmark
    {
        return $this->benchmark;
    }

    public function setBenchmark(?Benchmark $benchmark): static
    {
        $this->benchmark = $benchmark;

        return $this;
    }

    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    public function getBenchmarkId(): ?int
    {
        return $this->benchmark?->getId();
    }

    public function setTraining(?Training $training): static
    {
        $this->training = $training;

        return $this;
    }

    /**
     * @var Collection<int, TrainingExerciseConfiguration>
     */
    #[ORM\OneToMany(targetEntity: TrainingExerciseConfiguration::class, mappedBy: 'trainingRound', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['id' => 'ASC'])]
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private Collection $trainingExerciseConfigurations;

    public function __construct()
    {
        $this->trainingExerciseConfigurations = new ArrayCollection();
    }

    /**
     * @return Collection<int, TrainingExerciseConfiguration>
     */
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    public function getTrainingExerciseConfigurations(): Collection
    {
        return $this->trainingExerciseConfigurations;
    }

    public function addTrainingExerciseConfiguration(TrainingExerciseConfiguration $exerciseConfig): static
    {
        if (!$this->trainingExerciseConfigurations->contains($exerciseConfig)) {
            $this->trainingExerciseConfigurations->add($exerciseConfig);
            $exerciseConfig->setTrainingRound($this);
        }

        return $this;
    }

    public function removeTrainingExerciseConfiguration(TrainingExerciseConfiguration $exerciseConfig): static
    {
        if ($this->trainingExerciseConfigurations->removeElement($exerciseConfig)) {
            if ($exerciseConfig->getTrainingRound() === $this) {
                $exerciseConfig->setTrainingRound(null);
            }
        }

        return $this;
    }
}
