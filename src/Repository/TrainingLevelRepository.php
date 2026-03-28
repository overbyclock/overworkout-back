<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrainingLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingLevel>
 */
class TrainingLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingLevel::class);
    }

    /**
     * @return TrainingLevel[] Returns levels for a program ordered by number
     */
    public function findByProgramOrdered(int $programId): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.program = :programId')
            ->setParameter('programId', $programId)
            ->orderBy('l.levelNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByProgramAndNumber(int $programId, int $levelNumber): ?TrainingLevel
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.program = :programId')
            ->andWhere('l.levelNumber = :levelNumber')
            ->setParameter('programId', $programId)
            ->setParameter('levelNumber', $levelNumber)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
