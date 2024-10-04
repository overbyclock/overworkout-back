<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserPasswordHashService
{
  private $passwordHasher;
  private $entityManager;

  public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
  {
    $this->passwordHasher = $passwordHasher;
    $this->entityManager = $entityManager;
  }
  public function createUser(string $nick, string $email, string $plainPassword): User
  {
    $user = new User();
    $user->setNick($nick);
    $user->setEmail($email);
    
    $hashedPassword = $this->passwordHasher->hashPassword($user,$plainPassword);
    $user->setPassword($hashedPassword);

    $user->setCreatedAt(new \DateTime());

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
  }

  public function hashPassword(User $user, string $plainPassword):string
  {
    return $this->passwordHasher->hashPassword($user,$plainPassword);
  }

}

