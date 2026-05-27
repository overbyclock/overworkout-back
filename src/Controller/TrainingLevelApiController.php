<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\TrainingLevelCreateDto;
use App\Dto\Request\TrainingLevelUpdateDto;
use App\Entity\TrainingLevel;
use App\Mapper\TrainingLevelMapper;
use App\Security\Voter\TrainingLevelVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/training-levels')]
class TrainingLevelApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingLevelMapper $trainingLevelMapper,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('', name: 'get_all_training_levels', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $this->denyAccessUnlessGranted(TrainingLevelVoter::LIST_ALL);

        $levels = $this->entityManager->getRepository(TrainingLevel::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($levels, null, ['groups' => [TrainingLevel::GROUP_READ]])
        );
    }

    #[Route('/{id}', name: 'get_training_level', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getById(int $id): JsonResponse
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)->find($id);

        if (null === $level) {
            return $this->json(['error' => 'Nivel no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingLevelVoter::VIEW, $level);

        return $this->json(
            $this->normalizer->normalize($level, null, ['groups' => [TrainingLevel::GROUP_READ, 'program:detail']])
        );
    }

    #[Route('', name: 'create_training_level', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TrainingLevelCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(TrainingLevelVoter::CREATE);

        try {
            $level = $this->trainingLevelMapper->fromCreateDto($dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $this->entityManager->persist($level);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Nivel creado correctamente',
            'level' => $this->normalizer->normalize($level, null, ['groups' => [TrainingLevel::GROUP_READ]]),
        ], 201);
    }

    #[Route('/{id}', name: 'update_training_level', methods: ['PATCH'])]
    public function update(
        int $id,
        #[MapRequestPayload] TrainingLevelUpdateDto $dto
    ): JsonResponse {
        $level = $this->entityManager->getRepository(TrainingLevel::class)->find($id);

        if (null === $level) {
            return $this->json(['error' => 'Nivel no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingLevelVoter::EDIT, $level);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No se proporcionaron cambios'], 400);
        }

        $this->trainingLevelMapper->updateFromDto($level, $dto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Nivel actualizado correctamente',
            'level' => $this->normalizer->normalize($level, null, ['groups' => [TrainingLevel::GROUP_READ]]),
        ]);
    }

    #[Route('/{id}', name: 'delete_training_level', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $level = $this->entityManager->getRepository(TrainingLevel::class)->find($id);

        if (null === $level) {
            return $this->json(['error' => 'Nivel no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingLevelVoter::DELETE, $level);

        $this->entityManager->remove($level);
        $this->entityManager->flush();

        return $this->json(['message' => 'Nivel eliminado correctamente']);
    }
}
