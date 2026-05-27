<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\TrainingWeekInfoCreateDto;
use App\Dto\Request\TrainingWeekInfoUpdateDto;
use App\Entity\TrainingLevel;
use App\Entity\TrainingWeekInfo;
use Doctrine\ORM\EntityManagerInterface;

readonly class TrainingWeekInfoMapper
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function fromCreateDto(TrainingWeekInfoCreateDto $dto): TrainingWeekInfo
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)->find($dto->levelId);

        if (null === $level) {
            throw new \InvalidArgumentException('Nivel no encontrado: '.$dto->levelId);
        }

        $weekInfo = new TrainingWeekInfo();
        $weekInfo->setTrainingLevel($level);
        $weekInfo->setName($dto->name);
        $weekInfo->setWeekNumber($dto->weekNumber);
        $weekInfo->setFocus($dto->focus);
        $weekInfo->setNote($dto->note);
        $weekInfo->setIntensity($dto->intensity);

        return $weekInfo;
    }

    public function updateFromDto(TrainingWeekInfo $weekInfo, TrainingWeekInfoUpdateDto $dto): void
    {
        if (null !== $dto->name) {
            $weekInfo->setName($dto->name);
        }

        if (null !== $dto->weekNumber) {
            $weekInfo->setWeekNumber($dto->weekNumber);
        }

        if (null !== $dto->focus) {
            $weekInfo->setFocus($dto->focus);
        }

        if (null !== $dto->note) {
            $weekInfo->setNote($dto->note);
        }

        if (null !== $dto->intensity) {
            $weekInfo->setIntensity($dto->intensity);
        }
    }
}
