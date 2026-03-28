<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\EquipmentCreateDto;
use App\Dto\Request\EquipmentUpdateDto;
use App\Entity\Equipments;
use App\Mapper\EquipmentMapper;
use App\Security\Voter\EquipmentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api')]
class EquipmentsApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EquipmentMapper $equipmentMapper,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('/equipments', name: 'get_all_equipments', methods: ['GET'])]
    public function getAllEquipments(): JsonResponse
    {
        $this->denyAccessUnlessGranted(EquipmentVoter::LIST_ALL);

        $equipments = $this->entityManager->getRepository(Equipments::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($equipments, null, ['groups' => [Equipments::GROUP_READ]])
        );
    }

    #[Route('/equipments/{id}', name: 'get_equipment', methods: ['GET'])]
    public function getEquipment(int $id): JsonResponse
    {
        $equipment = $this->entityManager->getRepository(Equipments::class)->find($id);

        if ($equipment === null) {
            return $this->json(['error' => 'Equipment not found'], 404);
        }

        $this->denyAccessUnlessGranted(EquipmentVoter::VIEW, $equipment);

        return $this->json(
            $this->normalizer->normalize($equipment, null, ['groups' => [Equipments::GROUP_READ_DETAIL]])
        );
    }

    #[Route('/equipments', name: 'create_equipment', methods: ['POST'])]
    public function createEquipment(
        #[MapRequestPayload] EquipmentCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(EquipmentVoter::CREATE);

        $equipment = $this->equipmentMapper->fromCreateDto($dto);

        $this->entityManager->persist($equipment);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Equipment created successfully',
            'equipment' => $this->normalizer->normalize($equipment, null, ['groups' => [Equipments::GROUP_READ]]),
        ], 201);
    }

    #[Route('/equipments/{id}', name: 'update_equipment', methods: ['PATCH'])]
    public function updateEquipment(
        int $id,
        #[MapRequestPayload] EquipmentUpdateDto $dto
    ): JsonResponse {
        $equipment = $this->entityManager->getRepository(Equipments::class)->find($id);

        if ($equipment === null) {
            return $this->json(['error' => 'Equipment not found'], 404);
        }

        $this->denyAccessUnlessGranted(EquipmentVoter::EDIT, $equipment);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No changes provided'], 400);
        }

        $this->equipmentMapper->updateFromDto($equipment, $dto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Equipment updated successfully',
            'equipment' => $this->normalizer->normalize($equipment, null, ['groups' => [Equipments::GROUP_READ]]),
        ]);
    }

    #[Route('/equipments/{id}', name: 'delete_equipment', methods: ['DELETE'])]
    public function deleteEquipment(int $id): JsonResponse
    {
        $equipment = $this->entityManager->getRepository(Equipments::class)->find($id);

        if ($equipment === null) {
            return $this->json(['error' => 'Equipment not found'], 404);
        }

        $this->denyAccessUnlessGranted(EquipmentVoter::DELETE, $equipment);

        $this->entityManager->remove($equipment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Equipment deleted successfully']);
    }
}
