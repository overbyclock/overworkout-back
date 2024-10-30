<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\TrainingRound;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\Exercises;
use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrainingApiController extends AbstractController
{
  private EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  #[Route('api/trainings', name: 'get_all_trainings', methods: ['GET'])]
  public function getAllTrainings(): JsonResponse
  {
    $trainings = $this->entityManager->getRepository(Training::class)->findAll();
    $responseData = [];

    foreach ($trainings as $training) {
      $responseData[] = $this->getTrainingData($training);
    }
    return new JsonResponse($responseData);
  }

  #[Route('api/trainings/{id}', name: 'get_training', methods: ['GET'])]
  public function getTraining(int $id): JsonResponse
  {
    $training = $this->entityManager->getRepository(Training::class)->find($id);

    if (!$training) {
      return new JsonResponse(['error' => 'Training not found'], 404);
    }
    return new JsonResponse($this->getTrainingData($training));
  }

  public function createTraining(Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $data = json_decode($request->getContent(), true);

    $discipline = $data['discipline'] ?? null;
    $target = $data['target'] ?? null;
    $rounds = $data['rounds'] ?? null;

    if (!($discipline || !$target || !is_array($rounds))) {
      return new JsonResponse(['error' => 'Invalid training data'], 400);
    }

    $training = new Training();
    $training->setDiscipline(Discipline::from($discipline));
    $training->setTarget(TargetWorkout::from($target));
    $training->setTrainingUser($this->getUser());

    $this->entityManager->persist($training);

    foreach ($rounds as $roundData) {
      $round = new TrainingRound();
      $round->setRound($roundData['round']);
      $round->setRestBetweenRounds($roundData['rest_between_rounds']);
      $round->setTraining($training);

      foreach ($roundData['exercises'] as $exerciseData) {
        $exerciseConfig = new TrainingExerciseConfiguration();
        $exercise = $this->entityManager->getRepository(Exercises::class)->find($exerciseData['exercise_id']);

        if (!$exercise) {
          return new JsonResponse(['error' => 'Invalid exercise ID: ' . $exerciseData['exercise_id']], 400);
        }

        $exerciseConfig->setExercise($exercise);
        $exerciseConfig->setReps($exerciseData['reps'] ?? null);
        $exerciseConfig->setSets($exerciseData['sets']);
        $exerciseConfig->setRestBetweenSets($exerciseData['rest_between_sets']);
        $exerciseConfig->setMaxTimeForReps($exerciseData['max_time_for_reps'] ?? null);
        $exerciseConfig->setWeight($exerciseData['weight'] ?? null);

        $this->entityManager->persist($exerciseConfig);
        $round->addTrainingExerciseConfiguration($exerciseConfig);
      }

      $this->entityManager->persist($round);
      $training->addTrainingRound($round);
    }

    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Training created successfully'], 201);
  }

  #[Route('api/trainings/{id}', name: 'update_training', methods: ['PATCH'])]
  public function updateTraining(int $id, Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $training = $this->entityManager->getRepository(Training::class)->find($id);

    if (!$training) {
      return new JsonResponse(['error' => 'Training not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['name'])) {
      $training->setName($data['name']);
    }

    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Training updated successfully'], 200);
  }

  #[Route('api/trainings/{id}', name: 'delete_training', methods: ['DELETE'])]
  public function deleteTraining(int $id): JsonResponse
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $training = $this->entityManager->getRepository(Training::class)->find($id);

    if (!$training) {
      return new JsonResponse(['error' => 'Training not found'], 404);
    }

    $this->entityManager->remove($training);
    $this->entityManager->flush();
    return new JsonResponse();
  }

  private function getTrainingData($training)
  {
    $trainingData = [
      'id' => $training->getId(),
      'name' => $training->getName(),
      'discipline' => $training->getDiscipline(),
      'target' => $training->getTarget(),
      'createdAt' => $training->getCreatedAt(),
      'reounds' => [],
    ];

    foreach ($training->getTrainingRounds() as $round) {
      $roundData = [
        'round' => $round->getRound(),
        'restBetweenRounds' => $round->getRestBetweenRounds(),
        'exercises' => []
      ];

      foreach ($round->getTrainingExcerciseConfigurations() as $exerciseConfig) {
        $roundData['exercises'][] = [
          'exercise' => $exerciseConfig->getExercise()->getName(),
          'reps' => $exerciseConfig->getReps(),
          'sets' => $exerciseConfig->getSets(),
          'restBetweenSets' => $exerciseConfig->getResetBetweenSets(),
          'maxTimeForReps' => $exerciseConfig->getMaxTimeForReps(),
          'weight' => $exerciseConfig->getWeight(),
        ];
      }
      $trainingData['rounds'][] = $roundData;
    }
    return $trainingData;
  }
}
