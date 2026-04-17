<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHashService
{
    private $passwordHasher;

    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function createUser(string $nick, string $email, string $avatar, string $plainPassword): User
    {
        if (!$this->isPasswordValid($plainPassword)) {
            throw new \InvalidArgumentException('The password is weak. It needs to be at least 6 characters long and must include at least one uppercase letter, one lowercase letter, and one number.');
        }

        $user = new User();
        $user->setNick($nick);
        $user->setEmail($email);
        $user->setAvatar($avatar);

        $hashedPassword = $this->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $user->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    public function verifyPassword(User $user, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }

    public function isPasswordValid(string $password): bool
    {
        // La contraseña debe tener al menos 6 caracteres,
        // incluir al menos una letra mayúscula, una letra minúscula y un número.
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/';

        return preg_match($pattern, $password);
    }
}
