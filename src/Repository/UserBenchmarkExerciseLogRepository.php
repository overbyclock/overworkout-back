<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserBenchmarkExerciseLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserBenchmarkExerciseLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBenchmarkExerciseLog::class);
    }
}
