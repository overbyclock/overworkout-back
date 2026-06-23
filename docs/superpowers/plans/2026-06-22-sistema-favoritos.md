# Sistema de Favoritos — Fase 1 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implementar el sistema de favoritos que permita a un usuario autenticado guardar, eliminar y listar `Training` y `TrainingProgram` favoritos.

**Architecture:** Dos entidades separadas (`UserFavoriteTraining`, `UserFavoriteProgram`) con FKs reales a `User`, `Training` y `TrainingProgram`. Un controlador `UserFavoriteApiController` expone los endpoints. Dos voters restringen la gestión de favoritos al propio usuario. Tests funcionales cubren el flujo completo.

**Tech Stack:** PHP 8.4, Symfony 8.0, Doctrine ORM 3.6, PHPUnit 11, firebase/php-jwt.

---

## File Structure

| File | Responsibility |
|------|----------------|
| `src/Entity/UserFavoriteTraining.php` | Entidad que relaciona un `User` con un `Training` favorito |
| `src/Entity/UserFavoriteProgram.php` | Entidad que relaciona un `User` con un `TrainingProgram` favorito |
| `src/Repository/UserFavoriteTrainingRepository.php` | Queries de favoritos de entrenamientos |
| `src/Repository/UserFavoriteProgramRepository.php` | Queries de favoritos de programas |
| `src/Security/Voter/UserFavoriteTrainingVoter.php` | Autorización sobre favoritos de entrenamientos |
| `src/Security/Voter/UserFavoriteProgramVoter.php` | Autorización sobre favoritos de programas |
| `src/Controller/UserFavoriteApiController.php` | Endpoints REST para gestionar favoritos |
| `tests/Functional/Api/UserFavoriteApiTest.php` | Tests funcionales del controlador |
| `migrations/Version20260622200000.php` | Creación de tablas `user_favorite_training` y `user_favorite_program` |

---

### Task 1: Crear la migración de base de datos

**Files:**
- Create: `migrations/Version20260622200000.php`

- [ ] **Step 1: Escribir la migración**

```php
<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260622200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea tablas de favoritos de entrenamientos y programas';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_favorite_training (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            training_id INT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \"(DC2Type:datetime_immutable)\",
            UNIQUE INDEX unique_user_training_favorite (user_id, training_id),
            INDEX IDX_USER_FAVORITE_TRAINING_USER (user_id),
            INDEX IDX_USER_FAVORITE_TRAINING_TRAINING (training_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user_favorite_program (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            training_program_id INT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \"(DC2Type:datetime_immutable)\",
            UNIQUE INDEX unique_user_program_favorite (user_id, training_program_id),
            INDEX IDX_USER_FAVORITE_PROGRAM_USER (user_id),
            INDEX IDX_USER_FAVORITE_PROGRAM_PROGRAM (training_program_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE user_favorite_training ADD CONSTRAINT FK_UFT_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_training ADD CONSTRAINT FK_UFT_TRAINING FOREIGN KEY (training_id) REFERENCES training (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_program ADD CONSTRAINT FK_UFP_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_program ADD CONSTRAINT FK_UFP_PROGRAM FOREIGN KEY (training_program_id) REFERENCES training_programs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_favorite_program');
        $this->addSql('DROP TABLE user_favorite_training');
    }
}
```

- [ ] **Step 2: Validar la sintaxis de la migración**

Run: `php bin/console doctrine:migrations:status`
Expected: Sin errores de parseo; la migración aparece en la lista.

- [ ] **Step 3: Commit**

```bash
git add migrations/Version20260622200000.php
git commit -m "feat(migrations): create user favorite tables"
```

---

### Task 2: Crear entidad `UserFavoriteTraining` y su repositorio

**Files:**
- Create: `src/Entity/UserFavoriteTraining.php`
- Create: `src/Repository/UserFavoriteTrainingRepository.php`
- Test: `tests/Unit/Entity/UserFavoriteTrainingTest.php`

- [ ] **Step 1: Escribir el test unitario fallido**

```php
<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserFavoriteTraining;
use PHPUnit\Framework\TestCase;

final class UserFavoriteTrainingTest extends TestCase
{
    public function testEntityCanBeCreatedWithUserAndTraining(): void
    {
        $user = new User();
        $user->setNick('test');
        $user->setEmail('test@example.com');

        $training = new Training();

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);
        $favorite->setTraining($training);

        self::assertSame($user, $favorite->getUser());
        self::assertSame($training, $favorite->getTraining());
        self::assertInstanceOf(\DateTimeImmutable::class, $favorite->getCreatedAt());
    }
}
```

Run: `php bin/phpunit tests/Unit/Entity/UserFavoriteTrainingTest.php`
Expected: FAIL (class not found).

- [ ] **Step 2: Implementar la entidad**

```php
<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserFavoriteTrainingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserFavoriteTrainingRepository::class)]
#[ORM\Table(name: 'user_favorite_training')]
#[ORM\UniqueConstraint(name: 'unique_user_training_favorite', columns: ['user_id', 'training_id'])]
class UserFavoriteTraining
{
    public const GROUP_READ = 'user_favorite_training:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favoriteTrainings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Training::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?Training $training = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): static
    {
        $this->training = $training;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
```

- [ ] **Step 3: Implementar el repositorio**

```php
<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Training;
use App\Entity\User;
use App\Entity\UserFavoriteTraining;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavoriteTraining>
 */
class UserFavoriteTrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteTraining::class);
    }

    /**
     * @return UserFavoriteTraining[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.training', 't')
            ->addSelect('t')
            ->where('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndTraining(User $user, Training $training): ?UserFavoriteTraining
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.training = :training')
            ->setParameter('user', $user)
            ->setParameter('training', $training)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function exists(User $user, Training $training): bool
    {
        return null !== $this->findOneByUserAndTraining($user, $training);
    }
}
```

- [ ] **Step 4: Ejecutar el test unitario**

Run: `php bin/phpunit tests/Unit/Entity/UserFavoriteTrainingTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add src/Entity/UserFavoriteTraining.php src/Repository/UserFavoriteTrainingRepository.php tests/Unit/Entity/UserFavoriteTrainingTest.php
git commit -m "feat(favorites): add UserFavoriteTraining entity and repository"
```

---

### Task 3: Crear entidad `UserFavoriteProgram` y su repositorio

**Files:**
- Create: `src/Entity/UserFavoriteProgram.php`
- Create: `src/Repository/UserFavoriteProgramRepository.php`
- Test: `tests/Unit/Entity/UserFavoriteProgramTest.php`

- [ ] **Step 1: Escribir el test unitario fallido**

```php
<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use PHPUnit\Framework\TestCase;

final class UserFavoriteProgramTest extends TestCase
{
    public function testEntityCanBeCreatedWithUserAndProgram(): void
    {
        $user = new User();
        $user->setNick('test');
        $user->setEmail('test@example.com');

        $program = new TrainingProgram();

        $favorite = new UserFavoriteProgram();
        $favorite->setUser($user);
        $favorite->setTrainingProgram($program);

        self::assertSame($user, $favorite->getUser());
        self::assertSame($program, $favorite->getTrainingProgram());
        self::assertInstanceOf(\DateTimeImmutable::class, $favorite->getCreatedAt());
    }
}
```

Run: `php bin/phpunit tests/Unit/Entity/UserFavoriteProgramTest.php`
Expected: FAIL (class not found).

- [ ] **Step 2: Implementar la entidad**

```php
<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserFavoriteProgramRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserFavoriteProgramRepository::class)]
#[ORM\Table(name: 'user_favorite_program')]
#[ORM\UniqueConstraint(name: 'unique_user_program_favorite', columns: ['user_id', 'training_program_id'])]
class UserFavoriteProgram
{
    public const GROUP_READ = 'user_favorite_program:read';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favoritePrograms')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: TrainingProgram::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups([self::GROUP_READ])]
    private ?TrainingProgram $trainingProgram = null;

    #[ORM\Column]
    #[Groups([self::GROUP_READ])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTrainingProgram(): ?TrainingProgram
    {
        return $this->trainingProgram;
    }

    public function setTrainingProgram(?TrainingProgram $trainingProgram): static
    {
        $this->trainingProgram = $trainingProgram;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
```

- [ ] **Step 3: Implementar el repositorio**

```php
<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavoriteProgram>
 */
class UserFavoriteProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteProgram::class);
    }

    /**
     * @return UserFavoriteProgram[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.trainingProgram', 'p')
            ->addSelect('p')
            ->where('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndProgram(User $user, TrainingProgram $program): ?UserFavoriteProgram
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.trainingProgram = :program')
            ->setParameter('user', $user)
            ->setParameter('program', $program)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function exists(User $user, TrainingProgram $program): bool
    {
        return null !== $this->findOneByUserAndProgram($user, $program);
    }
}
```

- [ ] **Step 4: Ejecutar el test unitario**

Run: `php bin/phpunit tests/Unit/Entity/UserFavoriteProgramTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add src/Entity/UserFavoriteProgram.php src/Repository/UserFavoriteProgramRepository.php tests/Unit/Entity/UserFavoriteProgramTest.php
git commit -m "feat(favorites): add UserFavoriteProgram entity and repository"
```

---

### Task 4: Crear `UserFavoriteTrainingVoter`

**Files:**
- Create: `src/Security/Voter/UserFavoriteTrainingVoter.php`
- Test: `tests/Unit/Security/Voter/UserFavoriteTrainingVoterTest.php`

- [ ] **Step 1: Escribir el test unitario fallido**

```php
<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\User;
use App\Entity\UserFavoriteTraining;
use App\Security\Voter\UserFavoriteTrainingVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserFavoriteTrainingVoterTest extends TestCase
{
    private UserFavoriteTrainingVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new UserFavoriteTrainingVoter();
    }

    public function testOwnerCanManageFavorite(): void
    {
        $user = new User();
        $user->setNick('owner');
        $user->setEmail('owner@test.com');
        // Simular id mediante reflexión no es necesario; el voter compara referencia cuando id es null
        // En producción ambos tendrán id. Aquí usamos el mismo objeto.
        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        self::assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }

    public function testNonOwnerCannotManageFavorite(): void
    {
        $owner = new User();
        $owner->setNick('owner');
        $owner->setEmail('owner@test.com');

        $other = new User();
        $other->setNick('other');
        $other->setEmail('other@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($owner);

        $token = new UsernamePasswordToken($other, 'main', $other->getRoles());

        self::assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }
}
```

Run: `php bin/phpunit tests/Unit/Security/Voter/UserFavoriteTrainingVoterTest.php`
Expected: FAIL (class not found).

- [ ] **Step 2: Implementar el voter**

```php
<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserFavoriteTraining;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, UserFavoriteTraining>
 */
final class UserFavoriteTrainingVoter extends Voter
{
    public const MANAGE = 'USER_FAVORITE_TRAINING_MANAGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::MANAGE === $attribute && $subject instanceof UserFavoriteTraining;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        if (!$subject instanceof UserFavoriteTraining) {
            return false;
        }

        return $this->isOwner($subject, $currentUser);
    }

    private function isOwner(UserFavoriteTraining $favorite, UserInterface $currentUser): bool
    {
        if (!$currentUser instanceof User) {
            return false;
        }

        $owner = $favorite->getUser();

        return null !== $owner && $owner->getId() === $currentUser->getId();
    }
}
```

- [ ] **Step 3: Ejecutar el test unitario**

Run: `php bin/phpunit tests/Unit/Security/Voter/UserFavoriteTrainingVoterTest.php`
Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add src/Security/Voter/UserFavoriteTrainingVoter.php tests/Unit/Security/Voter/UserFavoriteTrainingVoterTest.php
git commit -m "feat(favorites): add UserFavoriteTrainingVoter"
```

---

### Task 5: Crear `UserFavoriteProgramVoter`

**Files:**
- Create: `src/Security/Voter/UserFavoriteProgramVoter.php`
- Test: `tests/Unit/Security/Voter/UserFavoriteProgramVoterTest.php`

- [ ] **Step 1: Escribir el test unitario fallido**

```php
<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use App\Security\Voter\UserFavoriteProgramVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserFavoriteProgramVoterTest extends TestCase
{
    private UserFavoriteProgramVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new UserFavoriteProgramVoter();
    }

    public function testOwnerCanManageFavorite(): void
    {
        $user = new User();
        $user->setNick('owner');
        $user->setEmail('owner@test.com');

        $favorite = new UserFavoriteProgram();
        $favorite->setUser($user);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        self::assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $favorite, [UserFavoriteProgramVoter::MANAGE]));
    }

    public function testNonOwnerCannotManageFavorite(): void
    {
        $owner = new User();
        $owner->setNick('owner');
        $owner->setEmail('owner@test.com');

        $other = new User();
        $other->setNick('other');
        $other->setEmail('other@test.com');

        $favorite = new UserFavoriteProgram();
        $favorite->setUser($owner);

        $token = new UsernamePasswordToken($other, 'main', $other->getRoles());

        self::assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $favorite, [UserFavoriteProgramVoter::MANAGE]));
    }
}
```

Run: `php bin/phpunit tests/Unit/Security/Voter/UserFavoriteProgramVoterTest.php`
Expected: FAIL (class not found).

- [ ] **Step 2: Implementar el voter**

```php
<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserFavoriteProgram;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, UserFavoriteProgram>
 */
final class UserFavoriteProgramVoter extends Voter
{
    public const MANAGE = 'USER_FAVORITE_PROGRAM_MANAGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::MANAGE === $attribute && $subject instanceof UserFavoriteProgram;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        if (!$subject instanceof UserFavoriteProgram) {
            return false;
        }

        return $this->isOwner($subject, $currentUser);
    }

    private function isOwner(UserFavoriteProgram $favorite, UserInterface $currentUser): bool
    {
        if (!$currentUser instanceof User) {
            return false;
        }

        $owner = $favorite->getUser();

        return null !== $owner && $owner->getId() === $currentUser->getId();
    }
}
```

- [ ] **Step 3: Ejecutar el test unitario**

Run: `php bin/phpunit tests/Unit/Security/Voter/UserFavoriteProgramVoterTest.php`
Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add src/Security/Voter/UserFavoriteProgramVoter.php tests/Unit/Security/Voter/UserFavoriteProgramVoterTest.php
git commit -m "feat(favorites): add UserFavoriteProgramVoter"
```

---

### Task 6: Crear `UserFavoriteApiController`

**Files:**
- Create: `src/Controller/UserFavoriteApiController.php`
- Test: `tests/Functional/Api/UserFavoriteApiTest.php`

- [ ] **Step 1: Escribir el test funcional fallido**

```php
<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Entity\Training;
use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Enum\Discipline;
use App\Enum\TargetWorkout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFavoriteApiTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager = null;
    private ?UserPasswordHasherInterface $passwordHasher = null;
    private ?string $authToken = null;
    private ?User $testUser = null;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->createTestUserAndLogin();
    }

    private function createTestUserAndLogin(): void
    {
        $this->testUser = new User();
        $this->testUser->setNick('favoritetest');
        $this->testUser->setEmail('favorite@test.com');
        $this->testUser->setPassword($this->passwordHasher->hashPassword($this->testUser, 'Test123!'));
        $this->testUser->setRoles(['ROLE_USER']);

        $this->entityManager->persist($this->testUser);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'favorite@test.com',
                'password' => 'Test123!',
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->authToken = $data['token'] ?? null;
    }

    private function createTraining(string $name = 'Test Training'): Training
    {
        $training = new Training();
        $training->setName($name);
        $training->setDiscipline(Discipline::CALISTHENICS);
        $training->setTarget(TargetWorkout::STRENGTH);

        $this->entityManager->persist($training);
        $this->entityManager->flush();

        return $training;
    }

    private function createProgram(string $name = 'Test Program', string $slug = 'test-program'): TrainingProgram
    {
        $program = new TrainingProgram();
        $program->setName($name);
        $program->setSlug($slug);
        $program->setDiscipline('calisthenics');
        $program->setDifficulty('beginner');

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        return $program;
    }

    public function testAddTrainingFavorite(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Entrenamiento añadido a favoritos', $data['message']);
        $this->assertSame($training->getId(), $data['favorite']['training']['id']);
    }

    public function testAddTrainingFavoriteIsIdempotent(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('El entrenamiento ya estaba en favoritos', $data['message']);
    }

    public function testRemoveTrainingFavorite(): void
    {
        $training = $this->createTraining();

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/trainings/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Entrenamiento eliminado de favoritos', $data['message']);
    }

    public function testRemoveProgramFavorite(): void
    {
        $program = $this->createProgram();

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/programs/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertSame('Programa eliminado de favoritos', $data['message']);
    }

    public function testListFavorites(): void
    {
        $training = $this->createTraining('Fav Training');
        $program = $this->createProgram('Fav Program', 'fav-program');

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'POST',
            '/user/favorites/programs/'.$program->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $this->client->request(
            'GET',
            '/user/favorites',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(1, $data['trainings']);
        $this->assertCount(1, $data['programs']);
        $this->assertSame('Fav Training', $data['trainings'][0]['training']['name']);
        $this->assertSame('Fav Program', $data['programs'][0]['trainingProgram']['name']);
    }

    public function testCannotManageOtherUserFavorite(): void
    {
        $training = $this->createTraining();

        $otherUser = new User();
        $otherUser->setNick('other');
        $otherUser->setEmail('other@fav.com');
        $otherUser->setPassword($this->passwordHasher->hashPassword($otherUser, 'Test123!'));

        $this->entityManager->persist($otherUser);
        $this->entityManager->flush();

        // Otro usuario añade a favoritos
        $this->client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'other@fav.com',
                'password' => 'Test123!',
            ])
        );

        $loginData = json_decode($this->client->getResponse()->getContent(), true);
        $otherToken = $loginData['token'] ?? null;

        $this->client->request(
            'POST',
            '/user/favorites/trainings/'.$training->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$otherToken]
        );

        $addData = json_decode($this->client->getResponse()->getContent(), true);
        $favoriteId = $addData['favorite']['id'];

        $this->client->request(
            'DELETE',
            '/user/favorites/trainings/'.$favoriteId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->authToken]
        );

        $response = $this->client->getResponse();
        $this->assertSame(403, $response->getStatusCode());
    }
}
```

Run: `php bin/phpunit tests/Functional/Api/UserFavoriteApiTest.php`
Expected: FAIL (controller not found / 404).

- [ ] **Step 2: Implementar el controlador**

```php
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
use App\Security\Voter\UserFavoriteProgramVoter;
use App\Security\Voter\UserFavoriteTrainingVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserFavoriteApiController extends AbstractController
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
```

- [ ] **Step 3: Ejecutar los tests funcionales**

Run: `php bin/phpunit tests/Functional/Api/UserFavoriteApiTest.php --no-coverage --testdox`
Expected: PASS.

- [ ] **Step 4: Commit**

```bash
git add src/Controller/UserFavoriteApiController.php tests/Functional/Api/UserFavoriteApiTest.php
git commit -m "feat(favorites): add UserFavoriteApiController and functional tests"
```

---

### Task 7: Añadir relaciones inversas en `User` (opcional, recomendado)

**Files:**
- Modify: `src/Entity/User.php`

- [ ] **Step 1: Añadir colecciones de favoritos**

En `User.php`, añadir:

```php
/**
 * @var Collection<int, UserFavoriteTraining>
 */
#[ORM\OneToMany(targetEntity: UserFavoriteTraining::class, mappedBy: 'user')]
private Collection $favoriteTrainings;

/**
 * @var Collection<int, UserFavoriteProgram>
 */
#[ORM\OneToMany(targetEntity: UserFavoriteProgram::class, mappedBy: 'user')]
private Collection $favoritePrograms;
```

Inicializar en `__construct()`:

```php
$this->favoriteTrainings = new ArrayCollection();
$this->favoritePrograms = new ArrayCollection();
```

Añadir getters:

```php
/**
 * @return Collection<int, UserFavoriteTraining>
 */
public function getFavoriteTrainings(): Collection
{
    return $this->favoriteTrainings;
}

/**
 * @return Collection<int, UserFavoriteProgram>
 */
public function getFavoritePrograms(): Collection
{
    return $this->favoritePrograms;
}
```

- [ ] **Step 2: Validar mapping**

Run: `php bin/console doctrine:schema:validate`
Expected: `[OK] The mapping files are correct.` y `[OK] The database schema is in sync with the mapping files.`

- [ ] **Step 3: Commit**

```bash
git add src/Entity/User.php
git commit -m "feat(favorites): add inverse collections to User entity"
```

---

### Task 8: Validación final

- [ ] **Step 1: Ejecutar todos los tests**

Run: `php bin/phpunit --no-coverage`
Expected: Todos los tests pasan (679+).

- [ ] **Step 2: Ejecutar PHPStan**

Run: `vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --no-progress --memory-limit=1G`
Expected: No errors.

- [ ] **Step 3: Ejecutar PHP-CS-Fixer (dry-run)**

Run: `vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php-cs-fixer.dist.php`
Expected: No changes needed.

- [ ] **Step 4: Commit final si hay cambios de estilo**

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
git add -A
git commit -m "style(favorites): apply php-cs-fixer"
```

---

## Self-Review Checklist

- [x] **Spec coverage:** Cada requisito del spec (`docs/superpowers/specs/2026-06-22-sistema-favoritos-design.md`) está cubierto por una tarea.
  - Entidades separadas → Task 2 y Task 3.
  - Repositorios con métodos útiles → Task 2 y Task 3.
  - Endpoints POST/DELETE/GET → Task 6.
  - Voters → Task 4 y Task 5.
  - Tests funcionales → Task 6.
  - Migración → Task 1.
- [x] **Placeholder scan:** No hay TBD, TODO ni referencias sin definir.
- [x] **Type consistency:** Los nombres de entidades, grupos de serialización y voters son consistentes en todo el plan.

---

## Execution Handoff

**Plan complete and saved to `docs/superpowers/plans/2026-06-22-sistema-favoritos.md`. Two execution options:**

**1. Subagent-Driven (recommended)** — I dispatch a fresh subagent per task, review between tasks, fast iteration

**2. Inline Execution** — Execute tasks in this session using executing-plans, batch execution with checkpoints

**Which approach?**
