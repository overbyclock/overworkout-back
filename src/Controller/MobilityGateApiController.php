<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MobilityGate;
use App\Entity\TrainingProgram;
use App\Repository\MobilityGateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Catálogo de puertas de movilidad (safety gates) de los programas.
 * Solo lectura: las puertas se gestionan mediante seeders.
 */
#[Route('/mobility-gates')]
class MobilityGateApiController extends AbstractController
{
    public function __construct(
        private readonly MobilityGateRepository $mobilityGateRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * Puertas de un programa (incluye las de catálogo global).
     */
    #[Route('', name: 'get_mobility_gates', methods: ['GET'])]
    public function getAll(Request $request): JsonResponse
    {
        $programId = $request->query->get('program');

        if (null === $programId || !is_numeric($programId)) {
            return $this->json(['error' => 'Falta el parámetro program (id del programa).'], 400);
        }

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find((int) $programId);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        return $this->json(
            $this->normalizer->normalize($this->mobilityGateRepository->findByProgram($program), null, [
                'groups' => [MobilityGate::GROUP_READ],
            ])
        );
    }

    #[Route('/{id}', name: 'get_mobility_gate', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $gate = $this->mobilityGateRepository->find($id);

        if (null === $gate) {
            return $this->json(['error' => 'Puerta de movilidad no encontrada'], 404);
        }

        return $this->json(
            $this->normalizer->normalize($gate, null, [
                'groups' => [MobilityGate::GROUP_READ],
            ])
        );
    }
}
