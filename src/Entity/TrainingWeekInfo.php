<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrainingWeekInfoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TrainingWeekInfoRepository::class)]
#[ORM\Table(name: 'training_week_infos')]
class TrainingWeekInfo
{
    public const GROUP_READ = 'training_week_info:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'weekInfos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrainingLevel $trainingLevel = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?int $weekNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?string $focus = null;

    #[ORM\Column(nullable: true)]
    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?string $note = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([self::GROUP_READ, TrainingLevel::GROUP_READ, TrainingProgram::GROUP_DETAIL])]
    private ?string $intensity = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWeekNumber(): ?int
    {
        return $this->weekNumber;
    }

    public function setWeekNumber(int $weekNumber): static
    {
        $this->weekNumber = $weekNumber;

        return $this;
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

    public function getFocus(): ?string
    {
        return $this->focus;
    }

    public function setFocus(?string $focus): static
    {
        $this->focus = $focus;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getIntensity(): ?string
    {
        return $this->intensity;
    }

    public function setIntensity(?string $intensity): static
    {
        $this->intensity = $intensity;

        return $this;
    }
}
