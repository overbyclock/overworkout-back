<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TestResult;
use App\Entity\UserLevelProgress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestResult>
 */
class TestResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestResult::class);
    }

    /**
     * @return TestResult[]
     */
    public function findByUserLevelProgress(UserLevelProgress $progress): array
    {
        return $this->createQueryBuilder('tr')
            ->andWhere('tr.userLevelProgress = :progress')
            ->setParameter('progress', $progress)
            ->orderBy('tr.testDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLatestForProgress(UserLevelProgress $progress): ?TestResult
    {
        return $this->createQueryBuilder('tr')
            ->andWhere('tr.userLevelProgress = :progress')
            ->setParameter('progress', $progress)
            ->orderBy('tr.testDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(TestResult $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TestResult $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
