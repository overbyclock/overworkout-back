<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const GROUP_READ = 'user:read';
    public const GROUP_READ_ADMIN = 'user:read:admin';
    public const GROUP_WRITE = 'user:write';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ, self::GROUP_READ_ADMIN])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_ADMIN, self::GROUP_WRITE])]
    private ?string $nick = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_ADMIN, self::GROUP_WRITE])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_ADMIN])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_ADMIN])]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ_ADMIN])]
    private ?\DateTimeInterface $lastlogin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_READ_ADMIN, self::GROUP_WRITE])]
    private ?string $avatar = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $trainingGoal = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $estimatedLevel = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $gender = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?float $weightKg = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?int $heightCm = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups([self::GROUP_READ, self::GROUP_WRITE])]
    private ?string $trainingLocation = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([self::GROUP_READ])]
    private int $xp = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([self::GROUP_READ])]
    private int $streakDays = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([self::GROUP_READ])]
    private int $totalWorkouts = 0;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([self::GROUP_READ])]
    private int $athleteLevel = 1;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    #[Groups([self::GROUP_READ_ADMIN])]
    private ?\DateTimeInterface $lastWorkoutAt = null;

    /**
     * @var Collection<int, Training>
     */
    #[ORM\OneToMany(targetEntity: Training::class, mappedBy: 'trainingUser')]
    private Collection $trainings;

    /**
     * @var Collection<int, UserLevelProgress>
     */
    #[ORM\OneToMany(targetEntity: UserLevelProgress::class, mappedBy: 'user')]
    private Collection $levelProgress;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->levelProgress = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(string $nick): static
    {
        $this->nick = $nick;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {

    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function setLastlogin(?\DateTimeInterface $lastlogin): static
    {
        $this->lastlogin = $lastlogin;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getTrainingGoal(): ?string
    {
        return $this->trainingGoal;
    }

    public function setTrainingGoal(?string $trainingGoal): static
    {
        $this->trainingGoal = $trainingGoal;

        return $this;
    }

    public function getEstimatedLevel(): ?string
    {
        return $this->estimatedLevel;
    }

    public function setEstimatedLevel(?string $estimatedLevel): static
    {
        $this->estimatedLevel = $estimatedLevel;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getWeightKg(): ?float
    {
        return $this->weightKg;
    }

    public function setWeightKg(?float $weightKg): static
    {
        $this->weightKg = $weightKg;

        return $this;
    }

    public function getHeightCm(): ?int
    {
        return $this->heightCm;
    }

    public function setHeightCm(?int $heightCm): static
    {
        $this->heightCm = $heightCm;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getTrainingLocation(): ?string
    {
        return $this->trainingLocation;
    }

    public function setTrainingLocation(?string $trainingLocation): static
    {
        $this->trainingLocation = $trainingLocation;

        return $this;
    }

    public function getXp(): int
    {
        return $this->xp;
    }

    public function setXp(int $xp): static
    {
        $this->xp = $xp;

        return $this;
    }

    public function addXp(int $amount): static
    {
        $this->xp += $amount;

        return $this;
    }

    public function getStreakDays(): int
    {
        return $this->streakDays;
    }

    public function setStreakDays(int $streakDays): static
    {
        $this->streakDays = $streakDays;

        return $this;
    }

    public function getTotalWorkouts(): int
    {
        return $this->totalWorkouts;
    }

    public function setTotalWorkouts(int $totalWorkouts): static
    {
        $this->totalWorkouts = $totalWorkouts;

        return $this;
    }

    public function incrementWorkouts(): static
    {
        ++$this->totalWorkouts;

        return $this;
    }

    public function getAthleteLevel(): int
    {
        return $this->athleteLevel;
    }

    public function setAthleteLevel(int $athleteLevel): static
    {
        $this->athleteLevel = $athleteLevel;

        return $this;
    }

    public function getLastWorkoutAt(): ?\DateTimeInterface
    {
        return $this->lastWorkoutAt;
    }

    public function setLastWorkoutAt(?\DateTimeInterface $lastWorkoutAt): static
    {
        $this->lastWorkoutAt = $lastWorkoutAt;

        return $this;
    }

    /**
     * @return Collection<int, Training>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->setTrainingUser($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): static
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getTrainingUser() === $this) {
                $training->setTrainingUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLevelProgress>
     */
    public function getLevelProgress(): Collection
    {
        return $this->levelProgress;
    }

    public function addLevelProgress(UserLevelProgress $levelProgress): static
    {
        if (!$this->levelProgress->contains($levelProgress)) {
            $this->levelProgress->add($levelProgress);
            $levelProgress->setUser($this);
        }

        return $this;
    }

    public function removeLevelProgress(UserLevelProgress $levelProgress): static
    {
        if ($this->levelProgress->removeElement($levelProgress)) {
            if ($levelProgress->getUser() === $this) {
                $levelProgress->setUser(null);
            }
        }

        return $this;
    }
}
