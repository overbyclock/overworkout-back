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
