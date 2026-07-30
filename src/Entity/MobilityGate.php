<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MobilityGateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * Puerta de movilidad (safety gate) de un programa de entrenamiento.
 *
 * Las puertas se evalúan dentro de los tests de nivel (ítems de testRequirements
 * con type "puerta_movilidad" o "re_test" que referencian esta entidad por su code).
 * Una misma puerta puede re-testearse en varios niveles del programa.
 */
#[ORM\Entity(repositoryClass: MobilityGateRepository::class)]
#[ORM\Table(name: 'mobility_gate')]
#[ORM\UniqueConstraint(name: 'uniq_mobility_gate_program_code', columns: ['program_id', 'code'])]
class MobilityGate
{
    public const GROUP_READ = 'mobility_gate:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    /**
     * Código corto de la puerta (p. ej. "G1"…"G6"). Único dentro del programa.
     */
    #[ORM\Column(length: 10)]
    #[Groups([self::GROUP_READ])]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    #[Groups([self::GROUP_READ])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $description = null;

    /**
     * Criterio literal de evaluación de la puerta (qué debe ejecutar el usuario).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?string $criteria = null;

    /**
     * Programa al que pertenece la puerta. Null = puerta de catálogo global.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([self::GROUP_READ])]
    private ?TrainingProgram $program = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCriteria(): ?string
    {
        return $this->criteria;
    }

    public function setCriteria(?string $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getProgram(): ?TrainingProgram
    {
        return $this->program;
    }

    public function setProgram(?TrainingProgram $program): static
    {
        $this->program = $program;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
