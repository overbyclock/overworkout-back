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
