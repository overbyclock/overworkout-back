<?php

namespace App\Repository;

use App\Entity\Benchmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Benchmark>
 */
class BenchmarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Benchmark::class);
    }

    /**
     * @return Benchmark[] Returns array of Benchmarks ordered by type and name
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.type', 'ASC')
            ->addOrderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Benchmark[] Returns benchmarks by type (girl, hero, benchmark)
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.type = :type')
            ->setParameter('type', $type)
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
