<?php

namespace App\Entity;

use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(enumType: Discipline::class)]
  private ?Discipline $discipline = null;

  #[ORM\Column(enumType: TargetWorkout::class)]
  private ?TargetWorkout $target = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column(nullable: true)]
  private ?int $rounds = null;

  /**
   * @var Collection<int, TrainingRound>
   */
  #[ORM\OneToMany(targetEntity: TrainingRound::class, mappedBy: 'training')]
  private Collection $trainingRounds;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $name = null;

  #[ORM\ManyToOne(inversedBy: 'trainings')]
  private ?User $trainingUser = null;

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
}
