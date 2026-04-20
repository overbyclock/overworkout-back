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

    public function getTraining(): ?Training
    {
        return $this->training;
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
    #[Groups([self::GROUP_READ, Training::GROUP_READ_DETAIL])]
    private Collection $trainingExerciseConfigurations;

    public function __construct()
    {
        $this->trainingExerciseConfigurations = new ArrayCollection();
    }

    /**
     * @return Collection<int, TrainingExerciseConfiguration>
     */
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
