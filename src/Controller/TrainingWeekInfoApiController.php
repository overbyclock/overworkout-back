<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\TrainingWeekInfoCreateDto;
use App\Dto\Request\TrainingWeekInfoUpdateDto;
use App\Entity\TrainingWeekInfo;
use App\Mapper\TrainingWeekInfoMapper;
use App\Security\Voter\TrainingWeekInfoVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/training-week-infos')]
class TrainingWeekInfoApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingWeekInfoMapper $trainingWeekInfoMapper,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('', name: 'get_all_training_week_infos', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $this->denyAccessUnlessGranted(TrainingWeekInfoVoter::LIST_ALL);

        $weekInfos = $this->entityManager->getRepository(TrainingWeekInfo::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($weekInfos, null, ['groups' => [TrainingWeekInfo::GROUP_READ]])
        );
    }

    #[Route('/{id}', name: 'get_training_week_info', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getById(int $id): JsonResponse
    {
        $weekInfo = $this->entityManager->getRepository(TrainingWeekInfo::class)->find($id);

        if (null === $weekInfo) {
            return $this->json(['error' => 'Información de semana no encontrada'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingWeekInfoVoter::VIEW, $weekInfo);

        return $this->json(
            $this->normalizer->normalize($weekInfo, null, ['groups' => [TrainingWeekInfo::GROUP_READ]])
        );
    }

    #[Route('', name: 'create_training_week_info', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TrainingWeekInfoCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(TrainingWeekInfoVoter::CREATE);

        try {
            $weekInfo = $this->trainingWeekInfoMapper->fromCreateDto($dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $this->entityManager->persist($weekInfo);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Información de semana creada correctamente',
            'weekInfo' => $this->normalizer->normalize($weekInfo, null, ['groups' => [TrainingWeekInfo::GROUP_READ]]),
        ], 201);
    }

    #[Route('/{id}', name: 'update_training_week_info', methods: ['PATCH'])]
    public function update(
        int $id,
        #[MapRequestPayload] TrainingWeekInfoUpdateDto $dto
    ): JsonResponse {
        $weekInfo = $this->entityManager->getRepository(TrainingWeekInfo::class)->find($id);

        if (null === $weekInfo) {
            return $this->json(['error' => 'Información de semana no encontrada'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingWeekInfoVoter::EDIT, $weekInfo);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No se proporcionaron cambios'], 400);
        }

        $this->trainingWeekInfoMapper->updateFromDto($weekInfo, $dto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Información de semana actualizada correctamente',
            'weekInfo' => $this->normalizer->normalize($weekInfo, null, ['groups' => [TrainingWeekInfo::GROUP_READ]]),
        ]);
    }

    #[Route('/{id}', name: 'delete_training_week_info', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $weekInfo = $this->entityManager->getRepository(TrainingWeekInfo::class)->find($id);

        if (null === $weekInfo) {
            return $this->json(['error' => 'Información de semana no encontrada'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingWeekInfoVoter::DELETE, $weekInfo);

        $this->entityManager->remove($weekInfo);
        $this->entityManager->flush();

        return $this->json(['message' => 'Información de semana eliminada correctamente']);
    }
}
