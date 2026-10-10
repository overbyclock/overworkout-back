<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Benchmark;
use App\Entity\TestResult;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserLevelProgress;
use App\Enum\TestItemType;
use App\Repository\TestResultRepository;
use App\Repository\TrainingLevelRepository;
use App\Repository\UserLevelProgressRepository;
use App\Repository\UserTrainingLogRepository;
use Doctrine\ORM\EntityManagerInterface;

class LevelProgressService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserLevelProgressRepository $progressRepository,
        private readonly TrainingLevelRepository $levelRepository,
        private readonly AchievementUnlockService $achievementUnlockService,
        private readonly UserTrainingLogRepository $trainingLogRepository,
        private readonly TestResultRepository $testResultRepository,
    ) {
    }

    /**
     * Inicializa el progreso de un usuario para un programa.
     * Desbloquea el nivel 1 y deja el resto bloqueados.
     */
    public function initializeProgress(User $user, array $levels): void
    {
        foreach ($levels as $level) {
            $progress = new UserLevelProgress();
            $progress->setUser($user);
            $progress->setTrainingLevel($level);
            $progress->setStatus(UserLevelProgress::STATUS_LOCKED);
            $progress->setCyclesCompleted(0);
            $progress->setCurrentWeek(0);

            // El primer nivel queda in_progress, el resto locked
            if (1 === $level->getLevelNumber()) {
                $progress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
            }

            $this->entityManager->persist($progress);
        }

        $this->entityManager->flush();
    }

    /**
     * Inicia o reinicia el progreso de un programa sin afectar a otros programas activos.
     * Si el usuario ya tiene un progreso activo en este programa, no se realiza ningún cambio.
     * Si tiene progresos previos no activos, se eliminan para reiniciar de cero.
     */
    public function switchProgram(User $user, TrainingProgram $program): void
    {
        $existingProgresses = $this->progressRepository->findByUserAndProgram($user, $program);

        // Si ya existe un progreso activo en este programa, no hacemos nada.
        $hasActive = false;
        foreach ($existingProgresses as $progress) {
            if (\in_array($progress->getStatus(), UserLevelProgress::ACTIVE_STATUSES, true)) {
                $hasActive = true;
            }
        }

        if ($hasActive) {
            return;
        }

        // Eliminar progresos previos del programa objetivo para reiniciar de cero
        foreach ($existingProgresses as $progress) {
            $this->entityManager->remove($progress);
        }

        $this->entityManager->flush();

        // Inicializar progreso nuevo
        $levels = $this->levelRepository->findByProgramOrdered($program->getId());
        $this->initializeProgress($user, $levels);
    }

    /**
     * Abandona uno o todos los programas activos del usuario.
     * Si se indica un programa, solo se abandona ese. En caso contrario se abandonan todos los activos.
     * Las sesiones de entrenamiento en curso de los programas abandonados pasan a estado abandoned.
     */
    public function abandonActiveProgram(User $user, ?TrainingProgram $program = null): void
    {
        if (null !== $program) {
            $programProgresses = $this->progressRepository->findByUserAndProgram($user, $program);

            foreach ($programProgresses as $progress) {
                $progress->abandon();
            }

            $this->trainingLogRepository->abandonOpenSessionsForProgram($user, $program);

            $this->entityManager->flush();

            return;
        }

        $activeProgresses = $this->progressRepository->findAllActiveByUser($user);

        if (empty($activeProgresses)) {
            throw new \InvalidArgumentException('No tienes ningún programa activo para abandonar.');
        }

        $programs = [];
        foreach ($activeProgresses as $progress) {
            $progress->abandon();

            $progressProgram = $progress->getTrainingLevel()?->getProgram();
            if (null !== $progressProgram) {
                $programs[spl_object_id($progressProgram)] = $progressProgram;
            }
        }

        foreach ($programs as $abandonedProgram) {
            $this->trainingLogRepository->abandonOpenSessionsForProgram($user, $abandonedProgram);
        }

        $this->entityManager->flush();
    }

    /**
     * Registra los resultados de un test y evalúa si el usuario pasa de nivel.
     *
     * En programas con modo strict_gates: si la fuerza se supera pero alguna puerta
     * de movilidad falla, el nivel queda mobility_blocked (ciclo corto de movilidad,
     * sin repetir la fuerza). Si el progreso ya estaba mobility_blocked, la entrega
     * se evalúa como re-test: solo se exigen las puertas.
     *
     * @param array<int, array{name: string, value: int|float}>                                     $results
     * @param array<int, array{name: string, minimum: int|float, type?: string, gateCode?: string}> $requirements
     */
    public function submitTestResults(
        UserLevelProgress $progress,
        array $results,
        array $requirements,
        ?string $notes = null
    ): TestResult {
        $mode = $progress->getTrainingLevel()->getProgram()?->getTestEvaluationMode()
            ?? TrainingProgram::TEST_EVAL_LEGACY;

        $testResult = new TestResult();
        $testResult->setUserLevelProgress($progress);
        $testResult->setLevelNumber($progress->getTrainingLevel()->getLevelNumber());
        $testResult->setCycleNumber($progress->getCyclesCompleted());
        $testResult->setResults($results);
        $testResult->setNotes($notes);

        $this->entityManager->persist($testResult);

        $this->evaluateAndApplyOutcome($progress, $testResult, $requirements, $mode);

        $this->entityManager->flush();

        return $testResult;
    }

    /**
     * Evalúa un TestResult contra los requisitos del nivel y aplica el resultado:
     * pasa de nivel, bloqueo por movilidad o repetición de ciclo.
     * También desbloquea los logros por ítem de test.
     *
     * @param array<int, array{name: string, minimum?: int|float, target?: int|float|null, type?: string, gateCode?: string, direction?: string}> $requirements
     */
    private function evaluateAndApplyOutcome(
        UserLevelProgress $progress,
        TestResult $testResult,
        array $requirements,
        string $mode
    ): void {
        $isGateRetest = TrainingProgram::TEST_EVAL_STRICT_GATES === $mode
            && UserLevelProgress::STATUS_MOBILITY_BLOCKED === $progress->getStatus();

        if ($isGateRetest) {
            $gateRequirements = array_values(array_filter(
                $requirements,
                static fn (array $r): bool => TestItemType::fromNullable($r['type'] ?? null)->isGate(),
            ));
            $testResult->evaluate($gateRequirements, 'gates_only');
        } else {
            $testResult->evaluate($requirements, $mode);
        }

        // Logros por ítem: se desbloquean con cualquier test, se supere o no globalmente
        $this->achievementUnlockService->unlockTestItemAchievements(
            $progress->getUser(),
            $progress->getTrainingLevel(),
            $testResult,
            $requirements,
            [
                'levelNumber' => $progress->getTrainingLevel()->getLevelNumber(),
                'cycleNumber' => $progress->getCyclesCompleted(),
            ],
        );

        if ($testResult->isOverallPassed()) {
            $this->handleLevelPassed($progress);
        } elseif (TrainingProgram::TEST_EVAL_STRICT_GATES === $mode && $testResult->isStrengthPassed()) {
            $this->handleMobilityBlocked($progress);
        } else {
            $this->handleLevelRepeat($progress);
        }
    }

    /**
     * Busca, entre los progresos activos del usuario, el primer testRequirement de tipo
     * benchmark que referencia al benchmark dado.
     *
     * @return array{name: string, minimum?: int|float, direction?: string, unit?: string, type?: string, benchmarkId?: int}|null
     */
    public function findBenchmarkRequirement(User $user, Benchmark $benchmark): ?array
    {
        return $this->findBenchmarkMatch($user, $benchmark)['requirement'] ?? null;
    }

    /**
     * Primer progreso activo cuyo nivel referencia el benchmark en sus testRequirements,
     * junto al requirement concreto.
     *
     * @return array{progress: UserLevelProgress, requirement: array{name: string, minimum?: int|float, direction?: string, unit?: string, type?: string, benchmarkId?: int}}|null
     */
    private function findBenchmarkMatch(User $user, Benchmark $benchmark): ?array
    {
        foreach ($this->progressRepository->findAllActiveByUser($user) as $progress) {
            foreach ($progress->getTrainingLevel()?->getTestRequirements() ?? [] as $requirement) {
                if (TestItemType::Benchmark->value === ($requirement['type'] ?? null)
                    && ($requirement['benchmarkId'] ?? null) === $benchmark->getId()
                ) {
                    return ['progress' => $progress, 'requirement' => $requirement];
                }
            }
        }

        return null;
    }

    /**
     * Registra la marca de un benchmark cerrado en el TestResult del nivel en curso
     * que lo referencia como ítem de test, y re-evalúa el nivel si el test ya está completo.
     *
     * La marca cuenta aunque el scaling del attempt no sea RX: el mínimo es el mínimo.
     * Si $value es null (formato de score no parseable), se registra el ítem con valor
     * null y NO se re-evalúa el test.
     *
     * Semántica de auto-evaluación: los intent parciales (marcas acumuladas sin tener
     * resultado para TODOS los requirements del nivel) persisten el TestResult acumulado
     * SIN penalización — el outcome (repeat, completed, mobility_blocked) solo se aplica
     * cuando el test está completo o en la entrega formal vía submitTestResults. Así,
     * cerrar un WOD de práctica no repite el ciclo ni desbloquea niveles por accidente.
     *
     * Sin match → null, sin ningún efecto colateral.
     */
    public function recordBenchmarkResult(
        User $user,
        Benchmark $benchmark,
        int|float|null $value,
        string $unit,
        ?string $display = null
    ): ?TestResult {
        $match = $this->findBenchmarkMatch($user, $benchmark);

        if (null === $match) {
            return null;
        }

        $progress = $match['progress'];
        $requirement = $match['requirement'];
        $level = $progress->getTrainingLevel();
        $requirements = $level->getTestRequirements() ?? [];

        // Último TestResult del ciclo actual; si ya fue superado, se abre uno nuevo
        $testResult = $this->testResultRepository
            ->findLatestByProgressAndCycle($progress, $progress->getCyclesCompleted());

        if (null !== $testResult && $testResult->isOverallPassed()) {
            $testResult = null;
        }

        if (null === $testResult) {
            $testResult = new TestResult();
            $testResult->setUserLevelProgress($progress);
            $testResult->setLevelNumber($level->getLevelNumber());
            $testResult->setCycleNumber($progress->getCyclesCompleted());
            $testResult->setResults([]);
            $this->entityManager->persist($testResult);
        }

        // Añadir o actualizar el ítem (match por nombre del requirement)
        $results = array_values(array_filter(
            $testResult->getResults(),
            static fn (array $r): bool => ($r['name'] ?? null) !== $requirement['name'],
        ));
        $item = ['name' => $requirement['name'], 'value' => $value, 'unit' => $unit];
        if (null !== $display) {
            $item['display'] = $display;
        }
        $results[] = $item;
        $testResult->setResults($results);

        if (null === $value) {
            // Formato no parseable: se registra la marca pero no se auto-evalúa
            $this->entityManager->flush();

            return $testResult;
        }

        $mode = $level->getProgram()?->getTestEvaluationMode() ?? TrainingProgram::TEST_EVAL_LEGACY;

        // Solo se evalúa y aplica outcome si TODOS los requirements del nivel
        // tienen un resultado acumulado; en caso contrario es un intent parcial
        // y el progreso no se toca (sin repeat, sin completed, sin mobility_blocked).
        $resultNames = array_column($testResult->getResults(), 'name');
        $hasAllRequirements = [] !== $requirements
            && [] === array_diff(array_column($requirements, 'name'), $resultNames);

        if ($hasAllRequirements) {
            $this->evaluateAndApplyOutcome($progress, $testResult, $requirements, $mode);
        }

        $this->entityManager->flush();

        return $testResult;
    }

    /**
     * Avanza el progreso del usuario al siguiente nivel.
     */
    private function handleLevelPassed(UserLevelProgress $progress): void
    {
        $progress->setStatus(UserLevelProgress::STATUS_COMPLETED);
        $progress->setCompletedAt(new \DateTimeImmutable());

        // Desbloquear los logros de tipo test asociados al nivel superado
        $currentLevel = $progress->getTrainingLevel();
        $this->achievementUnlockService->unlockTestAchievementsForLevel(
            $progress->getUser(),
            $currentLevel,
            [
                'levelNumber' => $currentLevel->getLevelNumber(),
                'cycleNumber' => $progress->getCyclesCompleted(),
            ],
        );

        // Desbloquear siguiente nivel
        $nextLevel = $this->levelRepository->findOneBy([
            'program' => $currentLevel->getProgram(),
            'levelNumber' => $currentLevel->getLevelNumber() + 1,
        ]);

        if (null !== $nextLevel) {
            $nextProgress = $this->progressRepository->findByUserAndLevel($progress->getUser(), $nextLevel);

            if (null === $nextProgress) {
                $nextProgress = new UserLevelProgress();
                $nextProgress->setUser($progress->getUser());
                $nextProgress->setTrainingLevel($nextLevel);
                $this->entityManager->persist($nextProgress);
            }

            $nextProgress->setStatus(UserLevelProgress::STATUS_IN_PROGRESS);
            $nextProgress->setCyclesCompleted(0);
            $nextProgress->setCurrentWeek(0);
        }
    }

    /**
     * Marca el nivel para repetir (incrementa ciclo y reinicia semana).
     */
    private function handleLevelRepeat(UserLevelProgress $progress): void
    {
        $progress->incrementCycle();
        $progress->setStatus(UserLevelProgress::STATUS_REPEAT);
    }

    /**
     * Fuerza superada pero puerta de movilidad fallada: ciclo corto de movilidad.
     * No incrementa ciclo ni reinicia semana: el trabajo de fuerza no se repite.
     */
    private function handleMobilityBlocked(UserLevelProgress $progress): void
    {
        $progress->setStatus(UserLevelProgress::STATUS_MOBILITY_BLOCKED);
    }

    /**
     * Obtiene el progreso activo de un usuario.
     */
    public function getActiveProgress(User $user): ?UserLevelProgress
    {
        return $this->progressRepository->findActiveByUser($user);
    }

    /**
     * Avanza la semana actual del progreso activo.
     */
    public function advanceWeek(UserLevelProgress $progress): void
    {
        $progress->advanceWeek();
        $this->entityManager->flush();
    }
}
