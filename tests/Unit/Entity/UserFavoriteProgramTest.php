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
