<?php

namespace App\Entity;

use App\Repository\TrainingRoundRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrainingRoundRepository::class)]
class TrainingRound
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(
      min:1,
      max:100,
      notInRangeMessage:'The round must be between {{ min }} and {{ max }}.',
    )]
    private ?int $round = null;

    #[ORM\Column]
    #[Assert\Positive(message:'The rest between rounds must be a positive value.')]
    private ?int $restBetweenRounds = null;

    #[ORM\ManyToOne(inversedBy: 'trainingRounds')]
    private ?Training $training = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRound(): ?int
    { 
      
        return $this->round;
    }

    public function setRound(int $round): static
    {
      if($round < 1 || $round > 100){
        throw new \InvalidArgumentException('The round must between 1 and 100');
      }
        $this->round = $round;

        return $this;
    }

    public function getRestBetweenRounds(): ?int
    {
        return $this->restBetweenRounds;
    }

    public function setRestBetweenRounds(int $restBetweenRounds): static
    {
        if($restBetweenRounds <= 0){
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
}
