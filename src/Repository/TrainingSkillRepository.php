<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrainingSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingSkill>
 */
class TrainingSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingSkill::class);
    }

    /**
     * @return TrainingSkill[] Returns skills by family
     */
    public function findByFamily(string $family): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.family = :family')
            ->setParameter('family', $family)
            ->orderBy('s.unlockAtLevel', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return TrainingSkill[] Returns skills unlocked at specific level
     */
    public function findByUnlockLevel(int $level): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.unlockAtLevel = :level')
            ->setParameter('level', $level)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return TrainingSkill[] Returns key skills only
     */
    public function findKeySkills(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isKeySkill = :key')
            ->setParameter('key', true)
            ->orderBy('s.unlockAtLevel', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
