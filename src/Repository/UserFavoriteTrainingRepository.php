<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserFavoriteTraining;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavoriteTraining>
 */
class UserFavoriteTrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteTraining::class);
    }

    /**
     * @return UserFavoriteTraining[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.training', 't')
            ->addSelect('t')
            ->where('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndTraining(User $user, Training $training): ?UserFavoriteTraining
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.training = :training')
            ->setParameter('user', $user)
            ->setParameter('training', $training)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function exists(User $user, Training $training): bool
    {
        return null !== $this->findOneByUserAndTraining($user, $training);
    }
}
