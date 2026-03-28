<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MobilityExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MobilityExercise>
 */
class MobilityExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobilityExercise::class);
    }

    /**
     * @return MobilityExercise[] Returns an array of MobilityExercise objects by type
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.type = :type')
            ->setParameter('type', $type)
            ->orderBy('m.category', 'ASC')
            ->addOrderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return MobilityExercise[] Returns an array of MobilityExercise objects by category
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.category = :category')
            ->setParameter('category', $category)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
