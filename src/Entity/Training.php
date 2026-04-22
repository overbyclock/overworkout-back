<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    public const GROUP_READ = 'training:read';
    public const GROUP_READ_DETAIL = 'training:read:detail';
    public const GROUP_WRITE = 'training:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    private ?int $id = null;

    #[ORM\Column(enumType: Discipline::class)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, self::GROUP_WRITE])]
    private ?Discipline $discipline = null;

    #[ORM\Column(enumType: TargetWorkout::class, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, self::GROUP_WRITE])]
    private ?TargetWorkout $target = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $rounds = null;

    /**
     * @var Collection<int, TrainingRound>
     */
    #[ORM\OneToMany(targetEntity: TrainingRound::class, mappedBy: 'training', cascade: ['persist', 'remove'])]
    private Collection $trainingRounds;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL, self::GROUP_WRITE])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBenchmark = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCircuit = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $benchmarkType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rxWeightMale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rxWeightFemale = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $eliteTime = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $advancedTime = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $intermediateTime = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $beginnerTime = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(name: 'training_user_id', referencedColumnName: 'id', nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    private ?User $trainingUser = null;

    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?TrainingLevel $trainingLevel = null;

    #[ORM\Column(nullable: true)]
    private ?int $weekNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $dayKey = null;

    public function __construct()
    {
        $this->trainingRounds = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscipline(): ?Discipline
    {
        return $this->discipline;
    }

    public function setDiscipline(Discipline $discipline): static
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function getTarget(): ?TargetWorkout
    {
        return $this->target;
    }

    public function setTarget(TargetWorkout $target): static
    {
        $this->target = $target;

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

    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    public function getRounds(): ?int
    {
        return $this->rounds;
    }

    public function setRounds(?int $rounds): static
    {
        $this->rounds = $rounds;

        return $this;
    }

    /**
     * @return Collection<int, TrainingRound>
     */
    #[Groups([self::GROUP_READ_DETAIL])]
    public function getTrainingRounds(): Collection
    {
        return $this->trainingRounds;
    }

    public function addTrainingRound(TrainingRound $trainingRound): static
    {
        if (!$this->trainingRounds->contains($trainingRound)) {
            $this->trainingRounds->add($trainingRound);
            $trainingRound->setTraining($this);
        }

        return $this;
    }

    public function removeTrainingRound(TrainingRound $trainingRound): static
    {
        if ($this->trainingRounds->removeElement($trainingRound)) {
            // set the owning side to null (unless already changed)
            if ($trainingRound->getTraining() === $this) {
                $trainingRound->setTraining(null);
            }
        }

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

    public function getTrainingUser(): ?User
    {
        return $this->trainingUser;
    }

    public function setTrainingUser(?User $trainingUser): static
    {
        $this->trainingUser = $trainingUser;

        return $this;
    }

    public function isBenchmark(): ?bool
    {
        return $this->isBenchmark;
    }

    public function setIsBenchmark(?bool $isBenchmark): static
    {
        $this->isBenchmark = $isBenchmark;

        return $this;
    }

    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    #[\Symfony\Component\Serializer\Attribute\SerializedName('isCircuit')]
    public function isCircuit(): ?bool
    {
        return $this->isCircuit ?? false;
    }

    public function setIsCircuit(?bool $isCircuit): static
    {
        $this->isCircuit = $isCircuit;

        return $this;
    }

    public function getBenchmarkType(): ?string
    {
        return $this->benchmarkType;
    }

    public function setBenchmarkType(?string $benchmarkType): static
    {
        $this->benchmarkType = $benchmarkType;

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

    public function getEliteTime(): ?string
    {
        return $this->eliteTime;
    }

    public function setEliteTime(?string $eliteTime): static
    {
        $this->eliteTime = $eliteTime;

        return $this;
    }

    public function getAdvancedTime(): ?string
    {
        return $this->advancedTime;
    }

    public function setAdvancedTime(?string $advancedTime): static
    {
        $this->advancedTime = $advancedTime;

        return $this;
    }

    public function getIntermediateTime(): ?string
    {
        return $this->intermediateTime;
    }

    public function setIntermediateTime(?string $intermediateTime): static
    {
        $this->intermediateTime = $intermediateTime;

        return $this;
    }

    public function getBeginnerTime(): ?string
    {
        return $this->beginnerTime;
    }

    public function setBeginnerTime(?string $beginnerTime): static
    {
        $this->beginnerTime = $beginnerTime;

        return $this;
    }

    public function getTrainingLevel(): ?TrainingLevel
    {
        return $this->trainingLevel;
    }

    public function setTrainingLevel(?TrainingLevel $trainingLevel): static
    {
        $this->trainingLevel = $trainingLevel;

        return $this;
    }

    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    public function getWeekNumber(): ?int
    {
        return $this->weekNumber;
    }

    public function setWeekNumber(?int $weekNumber): static
    {
        $this->weekNumber = $weekNumber;

        return $this;
    }

    #[Groups([self::GROUP_READ, self::GROUP_READ_DETAIL])]
    public function getDayKey(): ?string
    {
        return $this->dayKey;
    }

    public function setDayKey(?string $dayKey): static
    {
        $this->dayKey = $dayKey;

        return $this;
    }
}
