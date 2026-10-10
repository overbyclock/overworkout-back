<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\FinishBenchmarkAttemptDto;
use App\Dto\Request\LogBenchmarkExerciseDto;
use App\Dto\Request\StartBenchmarkAttemptDto;
use App\Entity\Benchmark;
use App\Entity\BenchmarkExercise;
use App\Entity\User;
use App\Entity\UserBenchmarkAttempt;
use App\Entity\UserBenchmarkExerciseLog;
use Doctrine\ORM\EntityManagerInterface;

readonly class BenchmarkSessionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LevelProgressService $levelProgressService
    ) {
    }

    public function startAttempt(int $benchmarkId, User $user, StartBenchmarkAttemptDto $dto): UserBenchmarkAttempt
    {
        $benchmark = $this->entityManager->getRepository(Benchmark::class)->find($benchmarkId);

        if (null === $benchmark) {
            throw new \InvalidArgumentException('Benchmark no encontrado');
        }

        $attempt = new UserBenchmarkAttempt();
        $attempt->setUser($user);
        $attempt->setBenchmark($benchmark);
        $attempt->setScalingUsed($dto->scalingUsed);

        $this->entityManager->persist($attempt);
        $this->entityManager->flush();

        return $attempt;
    }

    public function logExercise(int $attemptId, User $user, LogBenchmarkExerciseDto $dto): UserBenchmarkExerciseLog
    {
        $attempt = $this->findAttemptOrFail($attemptId, $user);

        if (UserBenchmarkAttempt::STATUS_IN_PROGRESS !== $attempt->getStatus()) {
            throw new \InvalidArgumentException('No se pueden registrar ejercicios en un intento que no está en progreso');
        }

        $benchmarkExercise = $this->entityManager->getRepository(BenchmarkExercise::class)->find($dto->benchmarkExerciseId);

        if (null === $benchmarkExercise) {
            throw new \InvalidArgumentException('Ejercicio del benchmark no encontrado');
        }

        // Verificar que el ejercicio pertenece al benchmark del intento
        if ($benchmarkExercise->getBenchmark()->getId() !== $attempt->getBenchmark()->getId()) {
            throw new \InvalidArgumentException('El ejercicio no pertenece al benchmark de este intento');
        }

        $log = new UserBenchmarkExerciseLog();
        $log->setAttempt($attempt);
        $log->setBenchmarkExercise($benchmarkExercise);
        $log->setRoundNumber($dto->roundNumber);
        $log->setRepsCompleted($dto->repsCompleted);
        $log->setWeightUsed($dto->weightUsed);
        $log->setDistanceCompleted($dto->distanceCompleted);
        $log->setCaloriesCompleted($dto->caloriesCompleted);
        $log->setDurationSeconds($dto->durationSeconds);
        $log->setRestTakenSeconds($dto->restTakenSeconds);
        $log->setNotes($dto->notes);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return $log;
    }

    public function finishAttempt(int $attemptId, User $user, FinishBenchmarkAttemptDto $dto): UserBenchmarkAttempt
    {
        $attempt = $this->findAttemptOrFail($attemptId, $user);

        if (UserBenchmarkAttempt::STATUS_IN_PROGRESS !== $attempt->getStatus()) {
            throw new \InvalidArgumentException('El intento ya ha sido finalizado');
        }

        $attempt->setStatus($dto->status);
        $attempt->setFinishedAt(new \DateTimeImmutable());

        if (null !== $dto->totalTimeSeconds) {
            $attempt->setTotalTimeSeconds($dto->totalTimeSeconds);
        } else {
            $attempt->setTotalTimeSeconds(
                (int) (new \DateTimeImmutable())->getTimestamp() - $attempt->getStartedAt()->getTimestamp()
            );
        }

        if (null !== $dto->scoreValue) {
            $attempt->setScoreValue($dto->scoreValue);
        }

        if (null !== $dto->scoreDisplay) {
            $attempt->setScoreDisplay($dto->scoreDisplay);
        }

        if (null !== $dto->notes) {
            $attempt->setNotes($dto->notes);
        }

        // Auto-calcular scoreDisplay para For Time si no se proporcionó
        if (null === $dto->scoreDisplay && null !== $attempt->getTotalTimeSeconds()) {
            $attempt->setScoreDisplay($this->formatTime($attempt->getTotalTimeSeconds()));
        }

        $this->entityManager->flush();

        // Auto-evaluación: si el benchmark alimenta el test de un nivel en curso, registrar la marca
        if (UserBenchmarkAttempt::STATUS_COMPLETED === $dto->status) {
            $this->recordBenchmarkInLevelTest($user, $attempt);
        }

        return $attempt;
    }

    /**
     * Registra la marca del attempt en el TestResult del nivel que referencia el benchmark.
     * La marca cuenta aunque el scaling no sea RX.
     */
    private function recordBenchmarkInLevelTest(User $user, UserBenchmarkAttempt $attempt): void
    {
        $requirement = $this->levelProgressService->findBenchmarkRequirement($user, $attempt->getBenchmark());

        if (null === $requirement) {
            return;
        }

        $direction = $requirement['direction'] ?? 'gte';
        $value = null;
        $unit = $requirement['unit'] ?? 'reps';

        if ('lte' === $direction) {
            // Marcas de tiempo: el valor es el tiempo total en segundos
            $value = $attempt->getTotalTimeSeconds();
            $unit = $requirement['unit'] ?? 'segundos';
        } else {
            // Rondas/reps: el score se parsea desde scoreValue
            $value = $this->parseScoreValue($attempt->getScoreValue());
        }

        $this->levelProgressService->recordBenchmarkResult(
            $user,
            $attempt->getBenchmark(),
            $value,
            $unit,
            $attempt->getScoreDisplay()
        );
    }

    /**
     * Parsea el score de un benchmark a número.
     *
     * Formatos aceptados:
     * - numérico puro ("240", "15.5") → tal cual;
     * - rondas + reps ("5+30", "5 + 30") → suma aproximada (documentado).
     *
     * Cualquier otro formato → null (se registra la marca sin auto-evaluar).
     */
    private function parseScoreValue(?string $scoreValue): int|float|null
    {
        if (null === $scoreValue) {
            return null;
        }

        $scoreValue = trim($scoreValue);

        if (is_numeric($scoreValue)) {
            return $scoreValue + 0;
        }

        if (1 === preg_match('/^(\d+)\s*\+\s*(\d+)$/', $scoreValue, $matches)) {
            return (int) $matches[1] + (int) $matches[2];
        }

        return null;
    }

    public function deleteAttempt(int $attemptId, User $user): void
    {
        $attempt = $this->findAttemptOrFail($attemptId, $user);

        if (UserBenchmarkAttempt::STATUS_IN_PROGRESS !== $attempt->getStatus()) {
            throw new \InvalidArgumentException('Solo se pueden eliminar intentos en progreso');
        }

        $this->entityManager->remove($attempt);
        $this->entityManager->flush();
    }

    public function getAttempt(int $attemptId, User $user): UserBenchmarkAttempt
    {
        return $this->findAttemptOrFail($attemptId, $user);
    }

    /**
     * @return array<int, UserBenchmarkAttempt>
     */
    public function listUserAttempts(User $user, ?int $benchmarkId = null): array
    {
        $qb = $this->entityManager->getRepository(UserBenchmarkAttempt::class)
            ->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.startedAt', 'DESC');

        if (null !== $benchmarkId) {
            $qb->andWhere('a.benchmark = :benchmark')
                ->setParameter('benchmark', $benchmarkId);
        }

        return $qb->getQuery()->getResult();
    }

    private function findAttemptOrFail(int $attemptId, User $user): UserBenchmarkAttempt
    {
        $attempt = $this->entityManager->getRepository(UserBenchmarkAttempt::class)->find($attemptId);

        if (null === $attempt) {
            throw new \InvalidArgumentException('Intento no encontrado');
        }

        if ($attempt->getUser()?->getId() !== $user->getId()) {
            throw new \InvalidArgumentException('No tienes permiso para acceder a este intento');
        }

        return $attempt;
    }

    private function formatTime(int $totalSeconds): string
    {
        $minutes = (int) floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;

        if ($minutes >= 60) {
            $hours = (int) floor($minutes / 60);
            $minutes = $minutes % 60;

            return \sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return \sprintf('%d:%02d', $minutes, $seconds);
    }
}
