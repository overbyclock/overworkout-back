<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\AchievementCreateDto;
use App\Dto\Request\AchievementUpdateDto;
use App\Entity\Achievement;
use App\Entity\TrainingProgram;
use App\Security\Voter\AchievementVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/achievements')]
class AchievementApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('', name: 'get_all_achievements', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $this->denyAccessUnlessGranted(AchievementVoter::LIST_ALL);

        $achievements = $this->entityManager->getRepository(Achievement::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($achievements, null, ['groups' => ['achievement:read']])
        );
    }

    #[Route('/{id}', name: 'get_achievement', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        $achievement = $this->entityManager->getRepository(Achievement::class)->find($id);

        if (null === $achievement) {
            return $this->json(['error' => 'Logro no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(AchievementVoter::VIEW, $achievement);

        return $this->json(
            $this->normalizer->normalize($achievement, null, ['groups' => ['achievement:read']])
        );
    }

    #[Route('', name: 'create_achievement', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] AchievementCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(AchievementVoter::CREATE);

        $existing = $this->entityManager->getRepository(Achievement::class)->findOneBy(['code' => $dto->code]);
        if (null !== $existing) {
            return $this->json(['error' => 'Ya existe un logro con ese código'], 400);
        }

        $achievement = new Achievement();
        $achievement->setCode($dto->code);
        $achievement->setName($dto->name);
        $achievement->setCategory($dto->category ?? 'progress');
        $achievement->setDescription($dto->description);
        $achievement->setIcon($dto->icon);
        $achievement->setColor($dto->color);
        $achievement->setXpReward($dto->xpReward ?? 0);
        $achievement->setRequirementType($dto->requirementType);
        $achievement->setRequirementValue($dto->requirementValue);
        $achievement->setIsSecret($dto->isSecret ?? false);

        if (null !== $dto->programId) {
            $program = $this->entityManager->getRepository(TrainingProgram::class)->find($dto->programId);
            if (null === $program) {
                return $this->json(['error' => 'Programa no encontrado'], 400);
            }
            $achievement->setProgram($program);
        }

        $this->entityManager->persist($achievement);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Logro creado correctamente',
            'achievement' => $this->normalizer->normalize($achievement, null, ['groups' => ['achievement:read']]),
        ], 201);
    }

    #[Route('/{id}', name: 'update_achievement', methods: ['PATCH'])]
    public function update(
        int $id,
        #[MapRequestPayload] AchievementUpdateDto $dto
    ): JsonResponse {
        $achievement = $this->entityManager->getRepository(Achievement::class)->find($id);

        if (null === $achievement) {
            return $this->json(['error' => 'Logro no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(AchievementVoter::EDIT, $achievement);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No se proporcionaron cambios'], 400);
        }

        if (null !== $dto->name) {
            $achievement->setName($dto->name);
        }
        if (null !== $dto->category) {
            $achievement->setCategory($dto->category);
        }
        if (null !== $dto->description) {
            $achievement->setDescription($dto->description);
        }
        if (null !== $dto->icon) {
            $achievement->setIcon($dto->icon);
        }
        if (null !== $dto->color) {
            $achievement->setColor($dto->color);
        }
        if (null !== $dto->xpReward) {
            $achievement->setXpReward($dto->xpReward);
        }
        if (null !== $dto->requirementType) {
            $achievement->setRequirementType($dto->requirementType);
        }
        if (null !== $dto->requirementValue) {
            $achievement->setRequirementValue($dto->requirementValue);
        }
        if (null !== $dto->isSecret) {
            $achievement->setIsSecret($dto->isSecret);
        }
        if (null !== $dto->programId) {
            $program = $this->entityManager->getRepository(TrainingProgram::class)->find($dto->programId);
            if (null === $program) {
                return $this->json(['error' => 'Programa no encontrado'], 400);
            }
            $achievement->setProgram($program);
        }

        $this->entityManager->flush();

        return $this->json([
            'message' => 'Logro actualizado correctamente',
            'achievement' => $this->normalizer->normalize($achievement, null, ['groups' => ['achievement:read']]),
        ]);
    }

    #[Route('/{id}', name: 'delete_achievement', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $achievement = $this->entityManager->getRepository(Achievement::class)->find($id);

        if (null === $achievement) {
            return $this->json(['error' => 'Logro no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(AchievementVoter::DELETE, $achievement);

        $this->entityManager->remove($achievement);
        $this->entityManager->flush();

        return $this->json(['message' => 'Logro eliminado correctamente']);
    }
}
