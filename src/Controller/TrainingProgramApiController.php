<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\TrainingProgramCreateDto;
use App\Dto\Request\TrainingProgramUpdateDto;
use App\Entity\Achievement;
use App\Entity\TrainingLevel;
use App\Entity\TrainingProgram;
use App\Mapper\TrainingProgramMapper;
use App\Repository\AchievementRepository;
use App\Security\Voter\TrainingProgramVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/training-programs')]
class TrainingProgramApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingProgramMapper $trainingProgramMapper,
        private readonly NormalizerInterface $normalizer,
        private readonly AchievementRepository $achievementRepository,
    ) {
    }

    #[Route('', name: 'get_all_training_programs', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $this->denyAccessUnlessGranted(TrainingProgramVoter::LIST_ALL);

        $programs = $this->entityManager->getRepository(TrainingProgram::class)->findAll();

        return $this->json($programs, 200, [], ['groups' => ['program:read']]);
    }

    #[Route('/by-slug/{slug}', name: 'get_training_program_by_slug', methods: ['GET'])]
    public function getBySlug(string $slug): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->findOneBy(['slug' => $slug]);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingProgramVoter::VIEW, $program);

        return $this->json($program, 200, [], ['groups' => ['program:read', 'program:detail', 'level:read', 'training:read:detail', 'training_skill:read']]);
    }

    #[Route('/{id}', name: 'get_training_program', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getById(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingProgramVoter::VIEW, $program);

        return $this->json($program, 200, [], ['groups' => ['program:read', 'program:detail', 'level:read', 'training:read:detail', 'training_skill:read']]);
    }

    #[Route('/{id}/levels', name: 'get_program_levels', methods: ['GET'])]
    public function getLevels(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $levels = $this->entityManager->getRepository(TrainingLevel::class)
            ->findBy(['program' => $id], ['levelNumber' => 'ASC']);

        return $this->json($levels, 200, [], ['groups' => ['level:read', 'training:read:detail']]);
    }

    #[Route('', name: 'create_training_program', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TrainingProgramCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(TrainingProgramVoter::CREATE);

        $existing = $this->entityManager->getRepository(TrainingProgram::class)
            ->findOneBy(['slug' => $dto->slug]);

        if (null !== $existing) {
            return $this->json(['error' => 'Ya existe un programa con ese slug'], 409);
        }

        $program = $this->trainingProgramMapper->fromCreateDto($dto);

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Programa creado correctamente',
            'program' => $this->normalizer->normalize($program, null, ['groups' => [TrainingProgram::GROUP_READ]]),
        ], 201);
    }

    #[Route('/{id}', name: 'update_training_program', methods: ['PATCH'])]
    public function update(
        int $id,
        #[MapRequestPayload] TrainingProgramUpdateDto $dto
    ): JsonResponse {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingProgramVoter::EDIT, $program);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No se proporcionaron cambios'], 400);
        }

        if (null !== $dto->slug && $dto->slug !== $program->getSlug()) {
            $existing = $this->entityManager->getRepository(TrainingProgram::class)
                ->findOneBy(['slug' => $dto->slug]);

            if (null !== $existing) {
                return $this->json(['error' => 'Ya existe un programa con ese slug'], 409);
            }
        }

        $this->trainingProgramMapper->updateFromDto($program, $dto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Programa actualizado correctamente',
            'program' => $this->normalizer->normalize($program, null, ['groups' => [TrainingProgram::GROUP_READ]]),
        ]);
    }

    #[Route('/{id}', name: 'delete_training_program', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingProgramVoter::DELETE, $program);

        $this->entityManager->remove($program);
        $this->entityManager->flush();

        return $this->json(['message' => 'Programa eliminado correctamente']);
    }

    #[Route('/{id}/stats', name: 'get_training_program_stats', methods: ['GET'])]
    public function getStats(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingProgramVoter::VIEW, $program);

        // TODO: Calcular estadísticas reales desde UserLevelProgress
        return $this->json([
            'totalUsers' => 0,
            'avgProgress' => 0,
            'completions' => 0,
            'avgTime' => 0,
            'paused' => 0,
            'dropped' => 0,
        ]);
    }

    #[Route('/{id}/achievements', name: 'get_training_program_achievements', methods: ['GET'])]
    public function getAchievements(int $id): JsonResponse
    {
        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($id);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingProgramVoter::VIEW, $program);

        $achievements = $this->achievementRepository->findByProgram($program);

        return $this->json($achievements, 200, [], ['groups' => ['achievement:read']]);
    }
}
