<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TestItemType;
use App\Repository\TestResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TestResultRepository::class)]
class TestResult
{
    public const GROUP_READ = 'test_result:read';
    public const GROUP_WRITE = 'test_result:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'testResults')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserLevelProgress $userLevelProgress = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private int $levelNumber = 0;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private int $cycleNumber = 0;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $testDate = null;

    #[ORM\Column(type: 'json')]
    #[Groups([self::GROUP_READ])]
    private array $results = [];

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private bool $overallPassed = false;

    /**
     * En modo strict_gates: si se superaron todos los mínimos de fuerza.
     * En modo legacy queda en true (no aplica).
     */
    #[ORM\Column(options: ['default' => true])]
    #[Groups([self::GROUP_READ])]
    private bool $strengthPassed = true;

    /**
     * En modo strict_gates: si se superaron todas las puertas de movilidad (y re-tests).
     * En modo legacy queda en true (no aplica).
     */
    #[ORM\Column(options: ['default' => true])]
    #[Groups([self::GROUP_READ])]
    private bool $mobilityPassed = true;

    /**
     * Códigos de las puertas de movilidad falladas (p. ej. ["G1", "G4"]).
     *
     * @var string[]|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?array $failedGates = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $notes = null;

    public function __construct()
    {
        $this->testDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLevelProgress(): ?UserLevelProgress
    {
        return $this->userLevelProgress;
    }

    public function setUserLevelProgress(?UserLevelProgress $userLevelProgress): static
    {
        $this->userLevelProgress = $userLevelProgress;

        return $this;
    }

    public function getLevelNumber(): int
    {
        return $this->levelNumber;
    }

    public function setLevelNumber(int $levelNumber): static
    {
        $this->levelNumber = $levelNumber;

        return $this;
    }

    public function getCycleNumber(): int
    {
        return $this->cycleNumber;
    }

    public function setCycleNumber(int $cycleNumber): static
    {
        $this->cycleNumber = $cycleNumber;

        return $this;
    }

    public function getTestDate(): ?\DateTimeImmutable
    {
        return $this->testDate;
    }

    public function setTestDate(\DateTimeImmutable $testDate): static
    {
        $this->testDate = $testDate;

        return $this;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function setResults(array $results): static
    {
        $this->results = $results;

        return $this;
    }

    public function isOverallPassed(): bool
    {
        return $this->overallPassed;
    }

    public function setOverallPassed(bool $overallPassed): static
    {
        $this->overallPassed = $overallPassed;

        return $this;
    }

    public function isStrengthPassed(): bool
    {
        return $this->strengthPassed;
    }

    public function setStrengthPassed(bool $strengthPassed): static
    {
        $this->strengthPassed = $strengthPassed;

        return $this;
    }

    public function isMobilityPassed(): bool
    {
        return $this->mobilityPassed;
    }

    public function setMobilityPassed(bool $mobilityPassed): static
    {
        $this->mobilityPassed = $mobilityPassed;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getFailedGates(): ?array
    {
        return $this->failedGates;
    }

    /**
     * @param string[]|null $failedGates
     */
    public function setFailedGates(?array $failedGates): static
    {
        $this->failedGates = $failedGates;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Calcula si el usuario pasó basado en los resultados y los requisitos mínimos.
     *
     * Modos:
     * - legacy: regla histórica — pasa si supera al menos 3 de 4 pruebas (o todas si hay menos de 4).
     * - strict_gates: todos los mínimos de fuerza Y todas las puertas de movilidad (regla §1.2 de la
     *   arquitectura v4: los mínimos del test son la entrada del nivel siguiente).
     * - gates_only: re-evaluación tras un bloqueo por movilidad; solo se evalúan las puertas
     *   (la fuerza ya se superó en el test que produjo el bloqueo).
     *
     * results: [{name, value, minimum, unit}].
     *
     * @param array<int, array{name: string, minimum?: int|float, type?: string, gateCode?: string}> $requirements
     */
    public function evaluate(array $requirements, string $mode = TrainingProgram::TEST_EVAL_LEGACY): static
    {
        if (TrainingProgram::TEST_EVAL_STRICT_GATES === $mode || 'gates_only' === $mode) {
            return $this->evaluateStrict($requirements, 'gates_only' === $mode);
        }

        $passedCount = 0;
        $evaluatedResults = [];

        foreach ($this->results as $result) {
            $req = array_find($requirements, fn ($r) => $r['name'] === $result['name']);
            $minimum = $req['minimum'] ?? 0;
            $passed = $result['value'] >= $minimum;
            if ($passed) {
                ++$passedCount;
            }
            $evaluatedResults[] = array_merge($result, [
                'minimum' => $minimum,
                'passed' => $passed,
            ]);
        }

        // Pasar si supera al menos 3 de 4 tests (o todos si son menos de 4)
        $total = \count($requirements);
        $threshold = $total >= 4 ? 3 : $total;
        $this->overallPassed = $passedCount >= $threshold;
        $this->strengthPassed = true;
        $this->mobilityPassed = true;
        $this->failedGates = null;
        $this->results = $evaluatedResults;

        return $this;
    }

    /**
     * Evaluación estricta (v4): itera los requisitos; un requisito sin resultado cuenta como fallo.
     *
     * @param array<int, array{name: string, minimum?: int|float, type?: string, gateCode?: string}> $requirements
     */
    private function evaluateStrict(array $requirements, bool $gatesOnly): static
    {
        $evaluatedResults = [];
        $failedGates = [];
        $strengthPassed = true;
        $mobilityPassed = true;

        foreach ($requirements as $req) {
            $type = TestItemType::fromNullable($req['type'] ?? null);
            $minimum = $req['minimum'] ?? 0;
            $result = array_find($this->results, fn ($r) => $r['name'] === $req['name']);
            $passed = null !== $result && $result['value'] >= $minimum;

            if ($type->isGate()) {
                if (!$passed) {
                    $mobilityPassed = false;
                    $failedGates[] = $req['gateCode'] ?? $req['name'];
                }
            } elseif (!$passed) {
                $strengthPassed = false;
            }

            $evaluated = array_merge($result ?? ['name' => $req['name'], 'value' => null], [
                'minimum' => $minimum,
                'passed' => $passed,
                'type' => $type->value,
            ]);
            if (isset($req['gateCode'])) {
                $evaluated['gateCode'] = $req['gateCode'];
            }
            $evaluatedResults[] = $evaluated;
        }

        // En un re-test de puertas la fuerza ya se superó previamente.
        $this->strengthPassed = $gatesOnly ? true : $strengthPassed;
        $this->mobilityPassed = $mobilityPassed;
        $this->failedGates = [] === $failedGates ? null : $failedGates;
        $this->overallPassed = $this->strengthPassed && $this->mobilityPassed;
        $this->results = $evaluatedResults;

        return $this;
    }
}
