<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BenchmarkExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BenchmarkExercise>
 */
class BenchmarkExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BenchmarkExercise::class);
    }
}
