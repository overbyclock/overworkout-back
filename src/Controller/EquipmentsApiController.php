<?php

namespace App\Controller;

use App\Entity\Equipments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipmentsApiController extends AbstractController
{
  private EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  #[Route('api/equipments', name: 'get_all_equipments', methods: ['GET'])]
  public function getAllEquipments(): JsonResponse
  {
    $equipments = $this->entityManager->getRepository(Equipments::class)->findAll();
    $responseData = [];

    foreach ($equipments as $equipment) {
      $responseData[] = [
        'id' => $equipment->getId(),
        'name' => $equipment->getName(),
        'image' => $equipment->getImage(),
      ];
    }

    return new JsonResponse($responseData);
  }

  #[Route('api/equipments/{id}', name: 'get_equipment', methods: ['GET'])]
  public function getEquipment(int $id): JsonResponse
  {
    $equipment = $this->entityManager->getRepository(Equipments::class)->find($id);

    if (!$equipment) {
      return new JsonResponse(['error' => 'Equipment not found'], 404);
    }

    return new JsonResponse([
      'id' => $equipment->getId(),
      'name' => $equipment->getName(),
      'image' => $equipment->getImage(),
    ]);
  }

  #[Route('api/equipments', name: 'create_equipment', methods: ['POST'])]
  public function createEquipment(Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $data = json_decode($request->getContent(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return new JsonResponse(['eror' => 'Invalid JSON format: ' . json_last_error_msg()], 400);
    }

    $name = $data['name'] ?? null;
    $image = $data['image'] ?? null;

    if (!$name) {
      return new JsonResponse(['error' => 'Required field name'], 400);
    }

    $equipment = new Equipments();
    $equipment->setName($name);
    $equipment->setImage($image);
    $equipment->setCreatedAt(new \DateTimeImmutable());

    $this->entityManager->persist($equipment);
    $this->entityManager->flush();


    return new JsonResponse(['message' => 'Equipment created succesfully'], 201);
  }

  #[Route('api/equipments/{id}', name: 'update_equipment', methods: ['PATCH'])]
  public function updateEquipment(int $id, Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $equipment = $this->entityManager->getRepository(Equipments::class)->find($id);

    if (!$equipment) {
      return new JsonResponse(['error' => 'Equipment not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['name'])) {
      $equipment->setName($data['name']);
    }

    if (isset($data['image'])) {
      $equipment->setImage($data['image']);
    }

    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Equipment updated successfully'], 200);
  }

  #[Route('api/equipments/{id}', name: 'delete_equipment', methods: ['DELETE'])]
  public function deleteEquipment(int $id): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $equipment = $this->entityManager->getRepository(Equipments::class)->find($id);

    if (!$equipment) {
      return new JsonResponse(['error' => 'Equipment not found'], 404);
    }

    $this->entityManager->remove($equipment);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Equipment deleted sucessfully'], 200);
  }
}
