<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Achievement;
use App\Entity\User;
use App\Entity\UserAchievement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAchievement>
 */
class UserAchievementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAchievement::class);
    }

    /**
     * @return UserAchievement[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('ua')
            ->andWhere('ua.user = :user')
            ->setParameter('user', $user)
            ->orderBy('ua.unlockedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndAchievement(User $user, Achievement $achievement): ?UserAchievement
    {
        return $this->findOneBy(['user' => $user, 'achievement' => $achievement]);
    }

    public function hasAchievement(User $user, Achievement $achievement): bool
    {
        return null !== $this->findOneByUserAndAchievement($user, $achievement);
    }
}
