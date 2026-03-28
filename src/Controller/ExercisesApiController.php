<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\ExerciseCreateDto;
use App\Dto\Request\ExerciseUpdateDto;
use App\Entity\Exercises;
use App\Mapper\ExerciseMapper;
use App\Security\Voter\ExerciseVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExercisesApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExerciseMapper $exerciseMapper,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('/exercises', name: 'get_all_exercises', methods: ['GET'])]
    public function getAllExercises(): JsonResponse
    {
        $this->denyAccessUnlessGranted(ExerciseVoter::LIST_ALL);

        $exercises = $this->entityManager->getRepository(Exercises::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($exercises, null, ['groups' => [Exercises::GROUP_READ]])
        );
    }

    #[Route('/exercises/{id}', name: 'get_exercise', methods: ['GET'])]
    public function getExercise(int $id): JsonResponse
    {
        $exercise = $this->entityManager->getRepository(Exercises::class)->find($id);

        if ($exercise === null) {
            return $this->json(['error' => 'Exercise not found'], 404);
        }

        $this->denyAccessUnlessGranted(ExerciseVoter::VIEW, $exercise);

        return $this->json(
            $this->normalizer->normalize($exercise, null, ['groups' => [Exercises::GROUP_READ_DETAIL]])
        );
    }

    #[Route('/exercises', name: 'create_exercise', methods: ['POST'])]
    public function createExercise(
        #[MapRequestPayload] ExerciseCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(ExerciseVoter::CREATE);

        try {
            $exercise = $this->exerciseMapper->fromCreateDto($dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $this->entityManager->persist($exercise);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Exercise created successfully',
            'exercise' => $this->normalizer->normalize($exercise, null, ['groups' => [Exercises::GROUP_READ]]),
        ], 201);
    }

    #[Route('/exercises/{id}', name: 'update_exercise', methods: ['PATCH'])]
    public function updateExercise(
        int $id,
        #[MapRequestPayload] ExerciseUpdateDto $dto
    ): JsonResponse {
        $exercise = $this->entityManager->getRepository(Exercises::class)->find($id);

        if ($exercise === null) {
            return $this->json(['error' => 'Exercise not found'], 404);
        }

        $this->denyAccessUnlessGranted(ExerciseVoter::EDIT, $exercise);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No changes provided'], 400);
        }

        try {
            $this->exerciseMapper->updateFromDto($exercise, $dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $this->entityManager->flush();

        return $this->json([
            'message' => 'Exercise updated successfully',
            'exercise' => $this->normalizer->normalize($exercise, null, ['groups' => [Exercises::GROUP_READ]]),
        ]);
    }

    #[Route('/exercises/{id}', name: 'delete_exercise', methods: ['DELETE'])]
    public function deleteExercise(int $id): JsonResponse
    {
        $exercise = $this->entityManager->getRepository(Exercises::class)->find($id);

        if ($exercise === null) {
            return $this->json(['error' => 'Exercise not found'], 404);
        }

        $this->denyAccessUnlessGranted(ExerciseVoter::DELETE, $exercise);

        $this->entityManager->remove($exercise);
        $this->entityManager->flush();

        return $this->json(['message' => 'Exercise deleted successfully']);
    }
}
