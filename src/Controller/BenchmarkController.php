<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\BenchmarkCreateDto;
use App\Dto\Request\BenchmarkUpdateDto;
use App\Entity\Benchmark;
use App\Mapper\BenchmarkMapper;
use App\Repository\BenchmarkRepository;
use App\Security\Voter\BenchmarkVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/benchmarks')]
class BenchmarkController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BenchmarkRepository $benchmarkRepository,
        private readonly BenchmarkMapper $benchmarkMapper,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('', name: 'benchmark_list', methods: ['GET'])]
    public function listAll(): JsonResponse
    {
        $this->denyAccessUnlessGranted(BenchmarkVoter::LIST_ALL);

        $benchmarks = $this->benchmarkRepository->findAllOrdered();

        return $this->json($benchmarks, 200, [], ['groups' => [Benchmark::GROUP_READ]]);
    }

    #[Route('/{id}', name: 'benchmark_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        $benchmark = $this->benchmarkRepository->find($id);

        if (null === $benchmark) {
            return $this->json(['error' => 'Benchmark no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(BenchmarkVoter::VIEW, $benchmark);

        return $this->json($benchmark, 200, [], ['groups' => [Benchmark::GROUP_READ]]);
    }

    #[Route('', name: 'benchmark_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] BenchmarkCreateDto $dto
    ): JsonResponse {
        $this->denyAccessUnlessGranted(BenchmarkVoter::CREATE);

        $benchmark = $this->benchmarkMapper->fromCreateDto($dto);

        $this->entityManager->persist($benchmark);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Benchmark creado correctamente',
            'benchmark' => $this->normalizer->normalize($benchmark, null, ['groups' => [Benchmark::GROUP_READ]]),
        ], 201);
    }

    #[Route('/{id}', name: 'benchmark_update', methods: ['PUT'])]
    public function update(
        int $id,
        #[MapRequestPayload] BenchmarkUpdateDto $dto
    ): JsonResponse {
        $benchmark = $this->benchmarkRepository->find($id);

        if (null === $benchmark) {
            return $this->json(['error' => 'Benchmark no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(BenchmarkVoter::EDIT, $benchmark);

        $this->benchmarkMapper->updateFromDto($benchmark, $dto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Benchmark actualizado correctamente',
            'benchmark' => $this->normalizer->normalize($benchmark, null, ['groups' => [Benchmark::GROUP_READ]]),
        ]);
    }

    #[Route('/{id}', name: 'benchmark_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $benchmark = $this->benchmarkRepository->find($id);

        if (null === $benchmark) {
            return $this->json(['error' => 'Benchmark no encontrado'], 404);
        }

        $this->denyAccessUnlessGranted(BenchmarkVoter::DELETE, $benchmark);

        $this->entityManager->remove($benchmark);
        $this->entityManager->flush();

        return $this->json(['message' => 'Benchmark eliminado correctamente']);
    }
}
