<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/training-programs')]
class TrainingProgramApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('', name: 'get_all_training_programs', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $programs = $this->entityManager->getRepository(TrainingProgram::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($programs, null, ['groups' => ['program:read']])
        );
    }

    #[Route('/{id}', name: 'get_training_program', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        return $this->json(
            $this->normalizer->normalize($program, null, ['groups' => ['program:read', 'program:detail']])
        );
    }

    #[Route('/{id}/levels', name: 'get_program_levels', methods: ['GET'])]
    public function getLevels(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        $levels = $this->entityManager->getRepository(TrainingLevel::class)
            ->findBy(['program' => $id], ['levelNumber' => 'ASC']);

        return $this->json(
            $this->normalizer->normalize($levels, null, ['groups' => ['level:read']])
        );
    }
}
