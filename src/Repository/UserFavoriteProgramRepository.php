<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavoriteProgram>
 */
class UserFavoriteProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteProgram::class);
    }

    /**
     * @return UserFavoriteProgram[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.trainingProgram', 'p')
            ->addSelect('p')
            ->where('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndProgram(User $user, TrainingProgram $program): ?UserFavoriteProgram
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.trainingProgram = :program')
            ->setParameter('user', $user)
            ->setParameter('program', $program)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function exists(User $user, TrainingProgram $program): bool
    {
        return null !== $this->findOneByUserAndProgram($user, $program);
    }
}
