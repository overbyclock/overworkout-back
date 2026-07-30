<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Achievement;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Achievement>
 */
class AchievementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achievement::class);
    }

    /**
     * @return Achievement[]
     */
    public function findByProgram(TrainingProgram $program): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.program = :program')
            ->setParameter('program', $program)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Achievement[]
     */
    public function findGlobal(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.program IS NULL')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Logros de un tipo concreto asociados a un nivel (p. ej. logros de test del nivel).
     *
     * @return Achievement[]
     */
    public function findByLevelAndType(TrainingLevel $level, string $type): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.level = :level')
            ->andWhere('a.type = :type')
            ->setParameter('level', $level)
            ->setParameter('type', $type)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
