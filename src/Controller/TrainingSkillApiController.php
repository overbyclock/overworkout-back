<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\TrainingSkillCreateDto;
use App\Dto\Request\TrainingSkillUpdateDto;
use App\Entity\TrainingSkill;
use App\Mapper\TrainingSkillMapper;
use App\Security\Voter\TrainingSkillVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/training-skills')]
class TrainingSkillApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrainingSkillMapper $trainingSkillMapper,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('', name: 'get_all_training_skills', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $this->denyAccessUnlessGranted(TrainingSkillVoter::LIST_ALL);

        $skills = $this->entityManager->getRepository(TrainingSkill::class)->findAll();

        return $this->json(
            $this->normalizer->normalize($skills, null, ['groups' => [TrainingSkill::GROUP_READ]])
        );
    }

    #[Route('/{id}', name: 'get_training_skill', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        $skill = $this->entityManager->getRepository(TrainingSkill::class)->find($id);

        if (null === $skill) {
            return $this->json(['error' => 'Skill no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingSkillVoter::VIEW, $skill);

        return $this->json(
            $this->normalizer->normalize($skill, null, ['groups' => [TrainingSkill::GROUP_READ]])
        );
    }

    #[Route('', name: 'create_training_skill', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TrainingSkillCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(TrainingSkillVoter::CREATE);

        try {
            $skill = $this->trainingSkillMapper->fromCreateDto($dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Skill creado correctamente',
            'skill' => $this->normalizer->normalize($skill, null, ['groups' => [TrainingSkill::GROUP_READ]]),
        ], 201);
    }

    #[Route('/{id}', name: 'update_training_skill', methods: ['PATCH'])]
    public function update(
        int $id,
        #[MapRequestPayload] TrainingSkillUpdateDto $dto
    ): JsonResponse {
        $skill = $this->entityManager->getRepository(TrainingSkill::class)->find($id);

        if (null === $skill) {
            return $this->json(['error' => 'Skill no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingSkillVoter::EDIT, $skill);

        if (!$dto->hasChanges()) {
            return $this->json(['message' => 'No se proporcionaron cambios'], 400);
        }

        try {
            $this->trainingSkillMapper->updateFromDto($skill, $dto);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        $this->entityManager->flush();

        return $this->json([
            'message' => 'Skill actualizado correctamente',
            'skill' => $this->normalizer->normalize($skill, null, ['groups' => [TrainingSkill::GROUP_READ]]),
        ]);
    }

    #[Route('/{id}', name: 'delete_training_skill', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $skill = $this->entityManager->getRepository(TrainingSkill::class)->find($id);

        if (null === $skill) {
            return $this->json(['error' => 'Skill no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingSkillVoter::DELETE, $skill);

        $this->entityManager->remove($skill);
        $this->entityManager->flush();

        return $this->json(['message' => 'Skill eliminado correctamente']);
    }
}
