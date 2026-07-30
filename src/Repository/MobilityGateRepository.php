<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MobilityGate;
use App\Entity\TrainingProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MobilityGate>
 */
class MobilityGateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobilityGate::class);
    }

    /**
     * Puertas de un programa (incluye las de catálogo global, sin programa asignado).
     *
     * @return MobilityGate[]
     */
    public function findByProgram(TrainingProgram $program): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.program = :program OR g.program IS NULL')
            ->setParameter('program', $program)
            ->orderBy('g.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByProgramAndCode(TrainingProgram $program, string $code): ?MobilityGate
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.code = :code')
            ->andWhere('g.program = :program OR g.program IS NULL')
            ->setParameter('code', $code)
            ->setParameter('program', $program)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
