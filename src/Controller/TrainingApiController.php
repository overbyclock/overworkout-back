<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\TrainingCreateDto;
use App\Dto\Request\TrainingUpdateDto;
use App\Entity\Training;
use App\Entity\User;
use App\Mapper\TrainingMapper;
use App\Security\Voter\TrainingVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TrainingApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingMapper $trainingMapper,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('/trainings', name: 'get_all_trainings', methods: ['GET'])]
    public function getAllTrainings(): JsonResponse
    {
        $this->denyAccessUnlessGranted(TrainingVoter::LIST_ALL);

        $trainings = $this->entityManager->getRepository(Training::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($trainings, null, ['groups' => [Training::GROUP_READ]])
        );
    }

    #[Route('/trainings/public', name: 'get_public_trainings', methods: ['GET'])]
    public function getPublicTrainings(): JsonResponse
    {
        $publicTrainings = $this->entityManager->getRepository(Training::class)
            ->findBy(['trainingUser' => null]);

        return $this->json(
            $this->normalizer->normalize($publicTrainings, null, [
                'groups' => [Training::GROUP_READ_DETAIL],
            ])
        );
    }

    #[Route('/trainings/user/{userId}', name: 'get_user_trainings', methods: ['GET'])]
    public function getUserTrainings(int $userId): JsonResponse
    {
        $this->denyAccessUnlessGranted(TrainingVoter::LIST_USER, $userId);

        $userTrainings = $this->entityManager->getRepository(Training::class)
            ->findBy(['trainingUser' => $userId]);

        return $this->json(
            $this->normalizer->normalize($userTrainings, null, [
                'groups' => [Training::GROUP_READ],
            ])
        );
    }

    #[Route('/trainings/{id}', name: 'get_training', methods: ['GET'])]
    public function getTraining(int $id): JsonResponse
    {
        $training = $this->entityManager->getRepository(Training::class)->find($id);

        if (null === $training) {
            return $this->json(['error' => 'Training not found'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingVoter::VIEW, $training);

        return $this->json(
            $this->normalizer->normalize($training, null, [
                'groups' => [Training::GROUP_READ_DETAIL],
            ])
        );
    }

    #[Route('/trainings', name: 'create_training', methods: ['POST'])]
    public function createTraining(
        #[MapRequestPayload] TrainingCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(TrainingVoter::CREATE);

        /** @var User $user */
        $user = $this->getUser();

        $training = $this->trainingMapper->fromCreateDto($dto, $user);

        // Si no es admin, asignar el usuario actual
        if (!$this->isGranted('ROLE_ADMIN')) {
            $training->setTrainingUser($user);
        }

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Training created successfully',
            'training' => $this->normalizer->normalize($training, null, [
                'groups' => [Training::GROUP_READ_DETAIL],
            ]),
        ], 201);
    }

    #[Route('/trainings/{id}', name: 'update_training', methods: ['PATCH'])]
    public function updateTraining(
        int $id,
        #[MapRequestPayload] TrainingUpdateDto $dto
    ): JsonResponse {
        $training = $this->entityManager->getRepository(Training::class)->find($id);

        if (null === $training) {
            return $this->json(['error' => 'Training not found'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingVoter::EDIT, $training);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No changes provided'], 400);
        }

        try {
            $this->trainingMapper->updateFromDto($training, $dto);
            $this->entityManager->flush();

            return $this->json([
                'message' => 'Training updated successfully',
                'training' => $this->normalizer->normalize($training, null, [
                    'groups' => [Training::GROUP_READ_DETAIL],
                ]),
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/trainings/{id}', name: 'delete_training', methods: ['DELETE'])]
    public function deleteTraining(int $id): JsonResponse
    {
        $training = $this->entityManager->getRepository(Training::class)->find($id);

        if (null === $training) {
            return $this->json(['error' => 'Training not found'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingVoter::DELETE, $training);

        $this->entityManager->remove($training);
        $this->entityManager->flush();

        return $this->json(['message' => 'Training deleted successfully']);
    }
}
