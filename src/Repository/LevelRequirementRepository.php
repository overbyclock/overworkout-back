<?php

namespace App\Repository;

use App\Entity\LevelRequirement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LevelRequirement>
 */
class LevelRequirementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LevelRequirement::class);
    }

    /**
     * @return LevelRequirement[] Returns an array of LevelRequirement objects for a specific level
     */
    public function findByLevel(int $levelId): array
    {
        return $this->createQueryBuilder('lr')
            ->andWhere('lr.level = :levelId')
            ->setParameter('levelId', $levelId)
            ->orderBy('lr.orderIndex', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
