<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserTrainingLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTrainingLog>
 */
class UserTrainingLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTrainingLog::class);
    }

    /**
     * Devuelve las fechas (YYYY-MM-DD) con al menos un entrenamiento completado
     * desde la fecha indicada, ordenadas de más reciente a más antigua.
     *
     * @return array<int, string>
     */
    public function findCompletedDatesByUser(User $user, \DateTimeInterface $since): array
    {
        $result = $this->createQueryBuilder('log')
            ->select('SUBSTRING(log.completedAt, 1, 10) AS completedDate')
            ->where('log.user = :user')
            ->andWhere('log.completedAt >= :since')
            ->setParameter('user', $user)
            ->setParameter('since', $since)
            ->groupBy('completedDate')
            ->orderBy('completedDate', 'DESC')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'completedDate');
    }

    /**
     * Cuenta los entrenamientos completados por un usuario en un rango de fechas,
     * ambos inclusive.
     */
    public function countCompletedInDateRange(User $user, \DateTimeInterface $start, \DateTimeInterface $end): int
    {
        $result = $this->createQueryBuilder('log')
            ->select('COUNT(log.id)')
            ->where('log.user = :user')
            ->andWhere('log.completedAt >= :start')
            ->andWhere('log.completedAt <= :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }
}
