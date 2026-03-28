<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\Request\UserRegistrationDto;
use App\Dto\Request\UserUpdateDto;
use App\Entity\User;
use App\Service\UserPasswordHashService;

readonly class UserMapper
{
    public function __construct(
        private UserPasswordHashService $passwordHashService
    ) {
    }

    public function fromRegistrationDto(UserRegistrationDto $dto): User
    {
        $user = new User();
        $user->setNick($dto->nick);
        $user->setEmail($dto->email);
        $user->setAvatar($dto->avatar);
        $user->setCreatedAt(new \DateTime());

        return $user;
    }

    public function updateFromDto(User $user, UserUpdateDto $dto): void
    {
        if (null !== $dto->nick) {
            $user->setNick($dto->nick);
        }

        if (null !== $dto->email) {
            $user->setEmail($dto->email);
        }

        if (null !== $dto->avatar) {
            $user->setAvatar($dto->avatar);
        }

        if (null !== $dto->password) {
            $hashedPassword = $this->passwordHashService->hashPassword($user, $dto->password);
            $user->setPassword($hashedPassword);
        }
    }
}
