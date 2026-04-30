<?php

declare(strict_types=1);

namespace App\Entity;

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
     * results: [{name, value, minimum, unit}].
     */
    public function evaluate(array $requirements): static
    {
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
        $this->results = $evaluatedResults;

        return $this;
    }
}
