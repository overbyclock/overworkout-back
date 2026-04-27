<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/training-programs')]
class TrainingProgramApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('', name: 'get_all_training_programs', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $programs = $this->entityManager->getRepository(TrainingProgram::class)->findAll();

        return $this->json($programs, 200, [], ['groups' => ['program:read']]);
    }

    #[Route('/by-slug/{slug}', name: 'get_training_program_by_slug', methods: ['GET'])]
    public function getBySlug(string $slug): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        return $this->json($program, 200, [], ['groups' => ['program:read', 'program:detail', 'level:read', 'training:read:detail']]);
    }

    #[Route('/{id}', name: 'get_training_program', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getById(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Program not found'], 404);
        }

        return $this->json($program, 200, [], ['groups' => ['program:read', 'program:detail', 'level:read', 'training:read:detail']]);
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

        return $this->json($levels, 200, [], ['groups' => ['level:read', 'training:read:detail']]);
    }
}
