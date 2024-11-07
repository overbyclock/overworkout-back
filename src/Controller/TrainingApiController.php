<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\TrainingRound;
use App\Entity\TrainingExerciseConfiguration;
use App\Entity\Exercises;
use App\Entity\User;
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
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $trainings = $this->entityManager->getRepository(Training::class)->findAll();
    $responseData = [];

    foreach ($trainings as $training) {
      $responseData[] = $this->getTrainingData($training);
    }
    return new JsonResponse($responseData);
  }

  #[Route('api/trainings/public', name: 'get_public_trainings', methods: ['GET'])]
  public function getPublicTrainings(): JsonResponse
  {
    $publicTrainings = $this->entityManager->getRepository(Training::class)->findBy(['trainingUser' => null]);
    $responseData = [];

    foreach ($publicTrainings as $training) {
      $responseData[] = $this->getTrainingData($training);
    }
    return new JsonResponse($responseData);
  }

  #[Route('api/trainings/user/{userId}', name: 'get_user_trainings', methods: ['GET'])]
  public function getUserTrainings(int $userId): JsonResponse
  {
    $authenticatedUser = $this->getUser();

    if (!$authenticatedUser instanceof User) {
      return new JsonResponse(['error' => 'User not authenticated'], 403);
    }

    if (!$this->isGranted('ROLE_ADMIN') && $authenticatedUser->getId() !== $userId) {
      return new JsonResponse(['error' => 'Access denied'], 403);
    }

    $userTrainings = $this->entityManager->getRepository(Training::class)->findBy(['trainingUser' => $userId]);

    $responseData = [];

    foreach ($userTrainings as $training) {
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

    $authenticatedUser = $this->getUser();
    if (!$authenticatedUser instanceof User) {
      return new JsonResponse(['error' => 'User not authenticated'], 403);
    }

    if (
      $training->getTrainingUser() !== null &&
      $training->getTrainingUser()->getId() !== $authenticatedUser->getid() &&
      !$this->isGranted('ROLE_ADMIN')
    ) {
      return new JsonResponse(['error' => 'Access denied: You do not have permission to view this training'], 403);
    }
    return new JsonResponse($this->getTrainingData($training));
  }

  #[Route('api/trainings', name: 'create_training', methods: ['POST'])]
  public function createTraining(Request $request): JsonResponse
  {
    $authenticatedUser = $this->getUser();
    if (!$authenticatedUser instanceof User) {
      return new JsonResponse(['error' => 'User not authenticated'], 403);
    }

    $data = json_decode($request->getContent(), true);

    if (!$data && json_last_error() !== JSON_ERROR_NONE) {
      return new JsonResponse(['error' => 'Invalid JSON format'], 400);
    }

    $discipline = $data['discipline'] ?? null;
    $target = $data['target'] ?? null;
    $rounds = $data['rounds'] ?? null;
    $name = $data['name'] ?? null;

    if (!($discipline || !$target || !is_array($rounds))) {
      return new JsonResponse(['error' => 'Invalid training data: discipline, target, and rounds are required'], 400);
    }

    $training = new Training();
    $training->setDiscipline(Discipline::from($discipline));
    $training->setTarget(TargetWorkout::from($target));
    $training->setName($name);
    $training->setCreatedAt(new \DateTimeImmutable());

    if (!$this->isGranted('ROLE_ADMIN')) {
      $training->setTrainingUser($authenticatedUser);
    }

    $this->entityManager->persist($training);

    foreach ($rounds as $roundData) {
      $round = new TrainingRound();

      $round->setRound($roundData['round'] ?? 1);
      $round->setRestBetweenRounds($roundData['rest_between_rounds'] ?? 60);
      $round->setTraining($training);

      foreach ($roundData['exercises'] as $exerciseData) {
        $exerciseConfig = new TrainingExerciseConfiguration();
        $exercise = $this->entityManager->getRepository(Exercises::class)->find($exerciseData['exercise_id']);

        if (!$exercise) {
          return new JsonResponse(['error' => 'Invalid exercise ID: ' . $exerciseData['exercise_id']], 400);
        }

        $exerciseConfig->setExercise($exercise);
        $exerciseConfig->setReps($exerciseData['reps'] ?? null);
        $exerciseConfig->setSets($exerciseData['sets'  ?? 1]);
        $exerciseConfig->setRestBetweenSets($exerciseData['rest_between_sets'] ?? 30);
        $exerciseConfig->setRestBetweenExercises($exerciseData['rest_between_exercises'] ?? 15);
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
    $training = $this->entityManager->getRepository(Training::class)->find($id);

    if (!$training) {
      return new JsonResponse(['error' => 'Training not found'], 404);
    }

    $authenticatedUser = $this->getUser();

    if (!$this->isGranted('ROLE_ADMIN') && $training->getTrainingUser() !== $authenticatedUser) {
      return new JsonResponse(['error' => 'You are not authorized to update this training'], 403);
    }

    $data = json_decode($request->getContent(), true);

    if (!$data || json_last_error() !== JSON_ERROR_NONE) {
      return new JsonResponse(['error' => 'Invalid JSON format'], 400);
    }

    if (isset($data['discipline'])) {
      $training->setDiscipline(Discipline::from($data['discipline']));
    }

    if (isset($data['target'])) {
      $training->setTarget(TargetWorkout::from($data['target']));
    }

    if (isset($data['name'])) {
      $training->setName($data['name']);
    }

    if (isset($data['rounds']) && is_array($data['rounds'])) {
      foreach ($data['rounds'] as $roundData) {
        $round = $this->entityManager->getRepository(TrainingRound::class)->find($roundData['id'] ?? null);

        if ($round && $round->getTraining() === $training) {
          $round->setRound($roundData['round'] ?? $round->getRound());
          $round->setRestBetweenRounds($roundData['rest_between_rounds'] ?? $round->getRestBetweenRounds());
        } else {

          $round = new TrainingRound();
          $round->setRound($roundData['round'] ?? 1);
          $round->setRestBetweenRounds($roundData['rest_between_rounds'] ?? 60);
          $round->setTraining($training);
          $training->addTrainingRound($round);
        }

        if (isset($roundData['exercises']) && is_array($roundData['exercises'])) {
          foreach ($roundData['exercises'] as $exerciseData) {
            $exerciseConfig = $this->entityManager->getRepository(TrainingExerciseConfiguration::class)
              ->find($exerciseData['id'] ?? null);

            if ($exerciseConfig && $exerciseConfig->getTrainingRound() === $round) {

              $exerciseConfig->setReps($exerciseData['reps'] ?? $exerciseConfig->getReps());
              $exerciseConfig->setSets($exerciseData['sets'] ?? $exerciseConfig->getSets());
              $exerciseConfig->setRestBetweenSets($exerciseData['rest_between_sets'] ?? $exerciseConfig->getRestBetweenSets());
              $exerciseConfig->setRestBetweenExercises($exerciseData['rest_between_exercises'] ?? $exerciseConfig->getRestBetweenExercises());
              $exerciseConfig->setMaxTimeForReps($exerciseData['max_time_for_reps'] ?? $exerciseConfig->getMaxTimeForReps());
              $exerciseConfig->setWeight($exerciseData['weight'] ?? $exerciseConfig->getWeight());
            } else {

              $exercise = $this->entityManager->getRepository(Exercises::class)->find($exerciseData['exercise_id']);

              if (!$exercise) {
                return new JsonResponse(['error' => 'Invalid exercise ID: ' . $exerciseData['exercise_id']], 400);
              }

              $exerciseConfig = new TrainingExerciseConfiguration();
              $exerciseConfig->setExercise($exercise);
              $exerciseConfig->setReps($exerciseData['reps'] ?? null);
              $exerciseConfig->setSets($exerciseData['sets'] ?? 1);
              $exerciseConfig->setRestBetweenSets($exerciseData['rest_between_sets'] ?? 30);
              $exerciseConfig->setRestBetweenExercises($exerciseData['rest_between_exercises'] ?? 15);
              $exerciseConfig->setMaxTimeForReps($exerciseData['max_time_for_reps'] ?? null);
              $exerciseConfig->setWeight($exerciseData['weight'] ?? null);

              $this->entityManager->persist($exerciseConfig);
              $round->addTrainingExerciseConfiguration($exerciseConfig);
            }
          }
        }

        $this->entityManager->persist($round);
      }
    }

    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Training updated successfully'], 200);
  }


  #[Route('api/trainings/{id}', name: 'delete_training', methods: ['DELETE'])]
  public function deleteTraining(int $id): JsonResponse
  {
    $authenticatedUser = $this->getUser();

    if (!$authenticatedUser instanceof User) {
      return new JsonResponse(['error' => 'User not authenticated'], 403);
    }

    $training = $this->entityManager->getRepository(Training::class)->find($id);

    if (!$training) {
      return new JsonResponse(['error' => 'Training not found'], 404);
    }

    if (
      $authenticatedUser->getId() !== $training->getTrainingUser()?->getId() &&
      !$this->isGranted('ROLE_ADMIN')
    ) {
      return new JsonResponse(['error' => 'Access denied: You can only delete your own training or be an admin'], 403);
    }

    $this->entityManager->remove($training);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Training deleted successfully'], 200);
  }

  private function getTrainingData($training)
  {
    $trainingData = [
      'id' => $training->getId(),
      'name' => $training->getName(),
      'discipline' => $training->getDiscipline(),
      'target' => $training->getTarget(),
      'createdAt' => $training->getCreatedAt(),
      'rounds' => [],
    ];

    foreach ($training->getTrainingRounds() as $round) {
      $roundData = [
        'round' => $round->getRound(),
        'restBetweenRounds' => $round->getRestBetweenRounds(),
        'exercises' => []
      ];

      foreach ($round->getTrainingExerciseConfigurations() as $exerciseConfig) {
        $roundData['exercises'][] = [
          'exercise' => $exerciseConfig->getExercise()->getName(),
          'reps' => $exerciseConfig->getReps(),
          'sets' => $exerciseConfig->getSets(),
          'restBetweenExercises' => $exerciseConfig->getRestBetweenExercises(),
          'restBetweenSets' => $exerciseConfig->getRestBetweenSets(),
          'maxTimeForReps' => $exerciseConfig->getMaxTimeForReps(),
          'weight' => $exerciseConfig->getWeight(),
        ];
      }
      $trainingData['rounds'][] = $roundData;
    }
    return $trainingData;
  }
}
