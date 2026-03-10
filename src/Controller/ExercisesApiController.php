<?php

namespace App\Controller;

use App\Enum\Levels;
use App\Entity\Exercises;
use App\Entity\Equipments;
use App\Enum\MuscleGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExercisesApiController extends AbstractController
{
  private EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  #[Route('api/exercises', name: 'get_all_exercises', methods: ['GET'])]
  public function getAllExercises(): JsonResponse
  {
    $exercises = $this->entityManager->getRepository(Exercises::class)->findAll();
    $responseData = [];

    foreach ($exercises as $exercise) {
      $equipmentData = null;

      if ($exercise->getEquipment()) {
        $equipment = $exercise->getEquipment();
        $equipmentData = [
          'id' => $equipment->getId(),
          'name' => $equipment->getName(),
          'image' => $equipment->getImage(),
        ];
      }
      $responseData[] = [
        'id' => $exercise->getId(),
        'name' => $exercise->getName(),
        'primaryMuscleGroup' => $exercise->getPrimaryMuscleGroup()?->value,
        'secondaryMuscleGroup' => $exercise->getSecondaryMuscleGroup()?->value,
        'level' => $exercise->getLevel()?->value,
        'difficultyRating' => $exercise->getDifficultyRating(),
        'description' => $exercise->getDescription(),
        'disciplines' => $exercise->getDisciplines(),
        'equipment' => $equipmentData,
        'media' => $exercise->getMedia()
      ];
    }
    return new JsonResponse($responseData);
  }

  #[Route('api/exercises/{id}', name: 'get_exercise', methods: ['GET'])]
  public function getExercise(int $id): JsonResponse
  {
    $exercise = $this->entityManager->getRepository(Exercises::class)->find($id);
    if (!$exercise) {
      return new JsonResponse(['error' => 'Exercise not found'], 404);
    }

    $equipmentData = null;

    if ($exercise->getEquipment()) {
      $equipment = $exercise->getEquipment();
      $equipmentData = [
        'id' => $equipment->getId(),
        'name' => $equipment->getName(),
        'image' => $equipment->getImage(),
      ];
    }

    return new JsonResponse([
      'id' => $exercise->getId(),
      'name' => $exercise->getName(),
      'primaryMuscleGroup' => $exercise->getPrimaryMuscleGroup(),
      'secondaryMuscleGroup' => $exercise->getSecondaryMuscleGroup(),
      'level' => $exercise->getLevel()?->value,
      'difficultyRating' => $exercise->getDifficultyRating(),
      'description' => $exercise->getDescription(),
      'disciplines' => $exercise->getDisciplines(),
      'equipment' => $equipmentData,
      'media' => $exercise->getMedia()
    ]);
  }

  #[Route('api/exercises', name: 'create_exercise', methods: ['POST'])]
  public function createExercise(Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $data = json_decode($request->getContent(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return new JsonResponse(['error' => 'Invalid JSON format: ' . json_last_error_msg()], 400);
    }

    $name = $data['name'] ?? null;
    $primaryMuscleGroup = $data['primaryMuscleGroup'] ?? null;
    $secondaryMuscleGroup = $data['secondaryMuscleGroup'] ?? null;
    $level = $data['level'] ?? null;
    $equipmentId = $data['equipment'] ?? null;
    $media = $data['media'] ?? null;
    $difficultyRating = $data['difficultyRating'] ?? 1;
    $description = $data['description'] ?? null;
    $disciplines = $data['disciplines'] ?? ['calisthenics'];

    $equipment = null;
    if ($equipmentId) {
      $equipment = $this->entityManager->getRepository(Equipments::class)->find($equipmentId);
      if (!$equipment) {
        return new JsonResponse(['error' => 'Equipment not found'], 404);
      }
    }

    if (!$name || !$primaryMuscleGroup || !$level) {
      return new JsonResponse(['error' => 'Required fields: name, primaryMuscleGroup and level'], 400);
    }

    try {
      $primaryMuscleGroup = MuscleGroup::from($primaryMuscleGroup);
      $secondaryMuscleGroup = MuscleGroup::from($secondaryMuscleGroup);
      $level = Levels::from($level);
    } catch (\ValueError $e) {
      return new JsonResponse(['error' => 'Invalid value for muscle group or level'], 400);
    }

    $exercise = new Exercises();
    $exercise->setName($name);
    $exercise->setPrimaryMuscleGroup($primaryMuscleGroup);
    $exercise->setSecondaryMuscleGroup($secondaryMuscleGroup);
    $exercise->setLevel($level);
    $exercise->setEquipment($equipment);
    $exercise->setMedia($media);
    $exercise->setDifficultyRating($difficultyRating);
    $exercise->setDescription($description);
    $exercise->setDisciplines($disciplines);

    $this->entityManager->persist($exercise);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Exercise created susccessfully'], 201);
  }

  #[Route('api/exercises/{id}', name: 'update_exercise', methods: ['PATCH'])]
  public function updateExercise(int $id, Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $exercise = $this->entityManager->getRepository(Exercises::class)->find($id);

    if (!$exercise) {
      return new JsonResponse(['error' => 'Exercise not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['name'])) {
      $exercise->setName($data['name']);
    }

    if (isset($data['primaryMuscleGroup'])) {
      try {
        $primaryMuscleGroup = MuscleGroup::from($data['primaryMuscleGroup']);
        $exercise->setPrimaryMuscleGroup($primaryMuscleGroup);
      } catch (\ValueError $e) {
        return new JsonResponse(['error' => 'Invalid value for primaryMuscleGroup']);
      }
    }

    if (isset($data['secondaryMuscleGroup'])) {
      try {
        $secondaryMuscleGroup = $data['secondaryMuscleGroup'] ? MuscleGroup::from($data['secondaryMuscleGroup']) : null;
        $exercise->setSecondayMuscleGroup($secondaryMuscleGroup);
      } catch (\ValueError $e) {
        return new JsonResponse(['error' => 'Invalid value for secondaryMuscleGroup']);
      }
    }
    if (isset($data['level'])) {
      try {
        $level = Levels::from($data['level']);
        $exercise->setLevel($level);
      } catch (\ValueError $e) {
        return new JsonResponse(['error' => 'Invalid value for level']);
      }
    }
    if (isset($data['difficultyRating'])) {
      $exercise->setDifficultyRating($data['difficultyRating']);
    }
    if (isset($data['description'])) {
      $exercise->setDescription($data['description']);
    }
    if (isset($data['disciplines'])) {
      $exercise->setDisciplines($data['disciplines']);
    }

    if (isset($data['equipment'])) {
      $equipmentId = $data['equipment'];
      $equipment = $this->entityManager->getRepository(Equipments::class)->find($equipmentId);
      if ($equipment) {
        $exercise->setEquipment($equipment);
      } else {
        return new JsonResponse(['error' => 'Equipment not found'], 404);
      }
    }

    if (isset($data['media'])) {
      $exercise->setMedia($data['media']);
    }

    $this->entityManager->persist($exercise);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Exercise updated successfully'], 200);
  }

  #[Route('api/exercises/{id}', name: 'delete_exercise', methods: ['DELETE'])]
  public function deleteExercise(int $id): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $exercise = $this->entityManager->getRepository(Exercises::class)->find($id);

    if (!$exercise) {
      return new JsonResponse(['error' => 'Exercise not found'], 404);
    }

    $this->entityManager->remove($exercise);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Exercise deleted successfully'], 200);
  }
}


