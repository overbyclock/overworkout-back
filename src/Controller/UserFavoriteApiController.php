<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Training;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use App\Entity\UserFavoriteTraining;
use App\Repository\UserFavoriteProgramRepository;
use App\Repository\UserFavoriteTrainingRepository;
use App\Security\Voter\TrainingVoter;
use App\Security\Voter\UserFavoriteProgramVoter;
use App\Security\Voter\UserFavoriteTrainingVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_USER')]
final class UserFavoriteApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFavoriteTrainingRepository $trainingFavoriteRepository,
        private readonly UserFavoriteProgramRepository $programFavoriteRepository,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('/user/favorites', name: 'get_user_favorites', methods: ['GET'])]
    public function getUserFavorites(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $trainingFavorites = $this->trainingFavoriteRepository->findByUser($user);
        $programFavorites = $this->programFavoriteRepository->findByUser($user);

        return $this->json([
            'trainings' => $this->normalizer->normalize($trainingFavorites, null, [
                'groups' => [UserFavoriteTraining::GROUP_READ, Training::GROUP_READ],
            ]),
            'programs' => $this->normalizer->normalize($programFavorites, null, [
                'groups' => [UserFavoriteProgram::GROUP_READ, TrainingProgram::GROUP_READ],
            ]),
        ]);
    }

    #[Route('/user/favorites/trainings/{trainingId}', name: 'add_training_favorite', requirements: ['trainingId' => '\\d+'], methods: ['POST'])]
    public function addTrainingFavorite(int $trainingId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $training = $this->entityManager->getRepository(Training::class)->find($trainingId);

        if (null === $training) {
            return $this->json(['error' => 'Entrenamiento no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(TrainingVoter::VIEW, $training);

        $existing = $this->trainingFavoriteRepository->findOneByUserAndTraining($user, $training);

        if (null !== $existing) {
            return $this->json([
                'message' => 'El entrenamiento ya estaba en favoritos',
                'favorite' => $this->normalizer->normalize($existing, null, [
                    'groups' => [UserFavoriteTraining::GROUP_READ, Training::GROUP_READ],
                ]),
            ]);
        }

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);
        $favorite->setTraining($training);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Entrenamiento añadido a favoritos',
            'favorite' => $this->normalizer->normalize($favorite, null, [
                'groups' => [UserFavoriteTraining::GROUP_READ, Training::GROUP_READ],
            ]),
        ], 201);
    }

    #[Route('/user/favorites/trainings/{favoriteId}', name: 'remove_training_favorite', requirements: ['favoriteId' => '\\d+'], methods: ['DELETE'])]
    public function removeTrainingFavorite(int $favoriteId): JsonResponse
    {
        $favorite = $this->trainingFavoriteRepository->find($favoriteId);

        if (null === $favorite) {
            return $this->json(['error' => 'Favorito no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(UserFavoriteTrainingVoter::MANAGE, $favorite);

        $this->entityManager->remove($favorite);
        $this->entityManager->flush();

        return $this->json(['message' => 'Entrenamiento eliminado de favoritos']);
    }

    #[Route('/user/favorites/programs/{programId}', name: 'add_program_favorite', requirements: ['programId' => '\\d+'], methods: ['POST'])]
    public function addProgramFavorite(int $programId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $program = $this->entityManager->getRepository(TrainingProgram::class)->find($programId);

        if (null === $program) {
            return $this->json(['error' => 'Programa no encontrado'], 404);
        }

        $existing = $this->programFavoriteRepository->findOneByUserAndProgram($user, $program);

        if (null !== $existing) {
            return $this->json([
                'message' => 'El programa ya estaba en favoritos',
                'favorite' => $this->normalizer->normalize($existing, null, [
                    'groups' => [UserFavoriteProgram::GROUP_READ, TrainingProgram::GROUP_READ],
                ]),
            ]);
        }

        $favorite = new UserFavoriteProgram();
        $favorite->setUser($user);
        $favorite->setTrainingProgram($program);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Programa añadido a favoritos',
            'favorite' => $this->normalizer->normalize($favorite, null, [
                'groups' => [UserFavoriteProgram::GROUP_READ, TrainingProgram::GROUP_READ],
            ]),
        ], 201);
    }

    #[Route('/user/favorites/programs/{favoriteId}', name: 'remove_program_favorite', requirements: ['favoriteId' => '\\d+'], methods: ['DELETE'])]
    public function removeProgramFavorite(int $favoriteId): JsonResponse
    {
        $favorite = $this->programFavoriteRepository->find($favoriteId);

        if (null === $favorite) {
            return $this->json(['error' => 'Favorito no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(UserFavoriteProgramVoter::MANAGE, $favorite);

        $this->entityManager->remove($favorite);
        $this->entityManager->flush();

        return $this->json(['message' => 'Programa eliminado de favoritos']);
    }
}
