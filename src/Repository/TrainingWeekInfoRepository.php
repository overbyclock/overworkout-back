<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrainingWeekInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingWeekInfo>
 */
class TrainingWeekInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingWeekInfo::class);
    }
}
