<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrainingLevel;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLevelProgress>
 */
class UserLevelProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLevelProgress::class);
    }

    public function findByUserAndLevel(User $user, TrainingLevel $level): ?UserLevelProgress
    {
        return $this->createQueryBuilder('ulp')
            ->andWhere('ulp.user = :user')
            ->andWhere('ulp.trainingLevel = :level')
            ->setParameter('user', $user)
            ->setParameter('level', $level)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return UserLevelProgress[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('ulp')
            ->andWhere('ulp.user = :user')
            ->setParameter('user', $user)
            ->orderBy('ulp.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByUser(User $user): ?UserLevelProgress
    {
        return $this->createQueryBuilder('ulp')
            ->andWhere('ulp.user = :user')
            ->andWhere('ulp.status IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('statuses', [UserLevelProgress::STATUS_IN_PROGRESS, UserLevelProgress::STATUS_REPEAT])
            ->orderBy('ulp.startedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(UserLevelProgress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserLevelProgress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
